<?php


if(!defined('UNEST.ORG')) {
    exit('Access Denied');
}

class PreprocessFunc{

	//根据 dynamic insert 记录 替换 $StandardAsmResultArray 中对应 整数参数
	public static function dynamic_insert_dealer($dynamic_insert_array,&$StandardAsmResultArray){
		global $language;
		global $UniqueHead;

		$ret = array();
		foreach ($StandardAsmResultArray as $sec_id => $a){

			//var_dump ($StandardAsmResultArray[$sec_id]);

			if (!isset($dynamic_insert_array[$sec_id])){
				continue;
			}
			$c_sec_rva = false; //当前节表 代码行号 偏移        
			foreach ($a as $line => $contents){
				if (false === $c_sec_rva){
					$c_sec_rva = $line;
					$prev_line = $line;
				}
				
				$c_dynamic_insert = $dynamic_insert_array[$sec_id];
				foreach ($c_dynamic_insert as $z => $y){
					if (($line - $c_sec_rva > $z) or ($line - $c_sec_rva >= $z + $y['size'])){					
						if ($line - $c_sec_rva === $z + $y['size']){ //整数参数 必须在 2进制的 最末位 (db指令除外)
							if ((1 == $y['size']) or (2 == $y['size']) or (4 == $y['size'])){
								//echo "<br> $line - $c_sec_rva > $z yes: ".$prev_line;
								//指令参数是否含有整数且不为重定位目标
								//var_dump ($StandardAsmResultArray[$sec_id][$prev_line]); 
								$last_int_param_no = false;
								foreach ($StandardAsmResultArray[$sec_id][$prev_line]['p_type'] as $p_number => $type){ //取得最后一个整数参数
									if ('i' === $type){
										$last_int_param_no = $p_number;
										break;
									}
								}
								if ((false !== $last_int_param_no) and (!isset($StandardAsmResultArray[$sec_id][$prev_line]['rel'][$last_int_param_no]))){
									$ret[$y['org']]['default']  = $StandardAsmResultArray[$sec_id][$prev_line]['params'][$last_int_param_no];	
									if (1 == $y['size']){
										$ret[$y['org']]['bits'] = 8;					    
									}elseif (2 == $y['size']){
										$ret[$y['org']]['bits'] = 16;								
									}elseif (4 == $y['size']){
										$ret[$y['org']]['bits'] = 32;								
									}								
									$StandardAsmResultArray[$sec_id][$prev_line]['params'][$last_int_param_no] = $UniqueHead.'dynamic_insert_'.$y['org'];
									//var_dump ($last_int_param_no);
									unset ($dynamic_insert_array[$sec_id][$z]);
								}
							}
						}//elseif ('DB' === )                       //db指令
					}				
				}

				$prev_line = $line;
				//echo "<br>$sec_id  $line - $c_sec_rva : ";
				//var_dump ($contents);
			}
			
			if (!empty($dynamic_insert_array[$sec_id])){ //段内有未处理的，即为不符条件，无法正确定位的，warning之
				foreach ($dynamic_insert_array[$sec_id] as $z => $y){
					GeneralFunc::LogInsert('fail to location the dynamic insert : '.$y['org'].' - '.$y['size'],2);    
				}
			}
		}

		//var_dump ($ret);
		return $ret;
	}

	//处理 保护段 (把汇编指令修正为: db xx ，并合并为一个单位)  
	public static function format_protect_section($p_sec_abs,&$AsmResultArray,$language){    
		$boundary = false;
		$tmp = $AsmResultArray;
		foreach ($tmp as $a => $b){
			if (isset($p_sec_abs[$a])){
				$boundary = $p_sec_abs[$a];	
				$deal_index = $a;                       //处理行号
			}
			
			if ($boundary === $a){
				$boundary = false;
			}
			  
			if (false !== $boundary){		    
				$deal_contents[$deal_index][] = $a;     //处理内容			
			}	
		}
		
		if (is_array($deal_contents)){
			//开始处理
			foreach ($deal_contents as $index => $value){
				$AsmResultArray[$index]['asm'] = 'DB ';
				foreach ($value as $a => $b){
					if ($index !== $b){
						$AsmResultArray[$index]['len'] += $AsmResultArray[$b]['len'];
						$AsmResultArray[$index]['bin'] .= $AsmResultArray[$b]['bin'];
						unset ($AsmResultArray[$b]);
					}
				}
				$tmp = str_split($AsmResultArray[$index]['bin'],2);
				if ($AsmResultArray[$index]['len'] !== count($tmp)){ //长度不符，出错
					GeneralFunc::LogInsert($language['fail_split_protect_sec']);
					return false;		
				}else{
					foreach ($tmp as $c => $d){
						$AsmResultArray[$index]['asm'] .= '0x'.$d.',';
					}
				}
			}
		}
		return true;  
	}

	//检测是否有 合并重叠的 保护段
	//.判断设置，有重叠(不含连续)的直接合并，如： 51 10 /  52 10 =》 重叠  出错
	//                                            51 10 /  61 10 =》 连续  正常
	//
	public static function is_overlap_section($section){
		$tmp = $section;

		foreach ($section as $address => $length){
			unset ($tmp[$address]);
			foreach ($tmp as $a => $b){
				if (($address + $length <= $a) or ($address >= $a + $b)){
					continue;
				}
				return true;
			}		
		}
		return false;
	}

	//关联动态插入和混淆目标段
	public static function bind_dynamic_insert_2_sec($dynamic_section,$section,$language){
		$ret = false;
		$tmp = $dynamic_section;
		foreach ($section as $a => $b){
			foreach ($tmp as $c => $d){
				if (($c >= $b['PointerToRawData']) and ($c < $b['PointerToRawData'] + $b['SizeOfRawData'])){
					$rva = $c - $b['PointerToRawData'];
					if ($rva + $d > $b['SizeOfRawData']){ //dynamic_insert定义超过了段的范围
						GeneralFunc::LogInsert($language['dynamic_insert_overflow_sec'].$c.' - '.$d);
						return false;
					}else{
						$ret[$a][$rva]['size'] = $d;
						$ret[$a][$rva]['org']  = $c;
						unset($dynamic_section[$c]);
					}
				}
			}
		}

		if (!empty($dynamic_section)){ //有不在混淆目标段 范围内 的dynamic insert 设置，notice之 将被忽略 
			foreach ($dynamic_section as $a => $b){
				 GeneralFunc::LogInsert($language['dynamic_insert_will_ignore'].$a.' - '.$b,3);
			}
			var_dump ($dynamic_section);
		}
		return $ret;
	}


	//关联保护段和混淆目标段
	public static function bind_protect_section_2_sec($protect_section,$section,$language){
		$ret = false;
		
		foreach ($section as $a => $b){
			foreach ($protect_section as $c => $d){
				if (($c >= $b['PointerToRawData']) and ($c < $b['PointerToRawData'] + $b['SizeOfRawData'])){
					$rva = $c - $b['PointerToRawData'];
					if ($rva + $d > $b['SizeOfRawData']){ //protect_section定义超过了段的范围
						GeneralFunc::LogInsert($language['protect_section_overflow_sec'].$c.' - '.$d);
						return false;
					}else{
						$ret[$a][$rva] = $d;
					}
				}
			}
		}
		
		//var_dump ($protect_section);
		//var_dump ($section);
		return $ret;
		
	}
}

?>