<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}

/******************************************/
//用于 Generate阶段 的 函数 集

class GenerateFunc{

	//获取指令在intel instruction数组中的定义
	public static function get_inst_define($opt,$para){
		global $Intel_instruction;
		$ret = false;
		if (isset($Intel_instruction[$opt])){
			$ret = $Intel_instruction[$opt];
			if (isset($ret['multi_op'])){
				$para_count = intval(count($para));
				if (isset($ret[$para_count])){
					$ret = $ret[$para_count];
				}else{
					$ret = false;
				}
			}
		}
		return $ret;
	}	
	
	//////////////////////////////////////////////////
	//位数精度调整 (高 -> 低)
	// 如: (4,8,16,32) -> (8,32)
	//
	public static function bits_precision_adjust($bits){
		if ($bits <= 8){
			$bits = 8;
		}else{
			$bits = 32;
		}
		return $bits;
	}

	//////////////////////////////////////////////////
	//检测整数并返回位数(8,16,32)
	//非整数则返回false
	public static function get_bit_from_inter($value){
		if (!is_numeric($value)){ //过滤含非数字字符
			return false;
		}
		$a = intval($value);
		if ($a == $value){        //过滤小数点以及超过32位范围整数
			if (($a <= 127) and ($a >= -128)){
				return 8;
			}
			if (($a <= 32767) and ($a >= -32768)){
				return 16;
			}
			if (($a <= 2147483647) and ($a >= -2147483648)){
				return 32;
			}
		}

		return false;
	}

	//////////////////////////////////////////////////
	//检测16位数字
	private static function is_32bit_hex($value){
		return preg_match("/^[0-9A-F]{1,8}$/",$value);
	}
		
	//自定义随机 算法
	//
	// 返回true 概率 = 1/$n
	// rand (1,$a)
	//
	public static function my_rand($n){
		
		if (1 == $n){
			return true;
		}
		if ($n < 1){
			return false;
		}

		if (1 < mt_rand (1,$n)){
			return false;
		}

		return true;
	}

	///////////////////////////////////////////////
	//
	//2级随机，使分布更均匀
	//
	public static function multi_level_rand($one,$two){
		$a = mt_rand(1,$one);
		$a = intval(ceil($two / $a));
		$b = mt_rand(1,$a);
		return $b;
	}


	public static function reset_ipsp_list_by_stack_pointer_define($sp_define,&$list,$soul){
		$ret = false;
		$tmp = $list;
		foreach ($tmp as $i => $a){
			if ((true !== $a['ipsp']) && (!isset($a['label']))){
				if (true === GeneralFunc::is_effect_ipsp($soul[$a['c']],1,$sp_define)){
					$list[$i]['ipsp'] = true;
					$ret[$i] = true;
				}
			}
		}
		return $ret;
	}


	///////////////////////////////////
	//随机 整数
	//返回 $ret['value'] = 12
	//         ['bits']  = 8  
	//
	public static function rand_interger($bits = false){

		$usable_bits = array(4 => true,8 => true,16 => true,32 => true);

		$ret = 1;
		
		$tmp = $usable_bits;

		$new_ret['value'] = 1;
		$new_ret['bits']  = 4;
			
		if (false !== $bits){ //往下覆盖，如指定 16位，包含 16,8,4位
			foreach ($tmp as $a => $b){
				if ($a > $bits){
					unset ($usable_bits[$a]);    
				}
			}
		}   

		if (count($usable_bits)){
			$bits = array_rand($usable_bits); 		
		
			//var_dump ($bits);
			if (4 === $bits){
				$ret = mt_rand (0,7);
			}elseif (8 === $bits){
				$ret = mt_rand (8,127);
			}elseif (16 === $bits){
				$ret = mt_rand (128,32767);   
			}else{
				$ret = mt_rand (32768,2147483647);			
			}	

			if (mt_rand(0,1)){
				$ret = '-'.$ret;
			}
		}
		$new_ret['value'] = $ret;
		$new_ret['bits']  = $bits;
		return $new_ret;
	}

	///////////////////////////////////
	//重定位信息副本累加
	public static function reloc_inc_copy_naked($index,$copy = 0){
		global $c_rel_info;
		$copy ++;
		while (isset($c_rel_info[$index][$copy])){
			$copy ++; //累计，直到有一个未被使用的副本号 | 注:这里仍未被占用，只是发现一个可用副本号而已
		}
		return $copy;    
	}

	///////////////////////////////////
	//重定位信息副本累加
	public static function reloc_inc_copy($obj,&$old,&$new){
		global $c_rel_info;
		global $UniqueHead;
		global $pattern_reloc;

		if (preg_match($pattern_reloc,$obj,$tmp)){
			$tmp = explode ('_',$tmp[0]);
			$old[0] = $tmp[2];
			$old[1] = $tmp[3];
			$old[2] = $tmp[4];
			$new    = $tmp[4];
			while (isset($c_rel_info[$old[1]][$new])){
					$new ++; //累计，直到有一个未被使用的副本号 | 注:这里仍未被占用，只是发现一个可用副本号而已
			}
			return true;    
		}
		return false;
	}


	//处理代码(重定位,尺寸strict)等...写入汇编文件前最后处理
	private static function gen_asm_file_kid($c_sec,$c_obj,&$buf,&$buf_head,$enter_flag,&$reloc_info_2_rewrite_table,&$non_null_labels,$commit=''){
		global $pattern_reloc;
		global $c_rel_info;
		global $sec;
		global $UniqueHead;

		$asm = '';
		//当前指令 含有的重定位 (完整参数) 如： UNEST_RELINFO_104_3_2 + 123 
		//         后面判断 并 替换时 使用
		//         一条指令 可 含 多个重定位 (一个参数 至多 一个重定位，如多个，不知不同的exe加载器是否支持对同一地址的多次重定位运算)
		//         (ready部分限制了源码 一条指令 至多一个重定位，Poly 可导致一条指令 多个重定位)
		$rel_param_result = false;

		if (is_array($c_obj['prefix'])){
			foreach ($c_obj['prefix'] as $z => $y){
				$asm .= $y.' ';
			}
		}
		$asm .= $c_obj['operation'].' ';
									//                    _
		$last_params_type = 0;      // 最后参数 是否为 i   |
		$last_params_cont = "";     // 最后参数            |
		$mem_bits = 0;              // 内存参数 位数      _| 
		if (is_array($c_obj['params'])){
			foreach ($c_obj['params'] as $z => $y){
				if ($z){
					$asm .= ',';
				}
				
				if (isset($c_obj['rel'][$z])){ //if (preg_match($pattern_reloc,$y,$tmp)){
					$rel_param_result[$z]['org'] = $y;				             
				}
				
				if ($c_obj['p_type'][$z] == 'm'){        //内存指针 参数，每个指令至多有一条
					$mem_bits = $c_obj['p_bits'][$z];
					//根据位数给内存指针加前缀
					if ('LEA' !== $c_obj['operation']){  //lea eax,[...]
						if (8 == $c_obj['p_bits'][$z]){
							$asm .= 'byte ';
						}elseif (16 == $c_obj['p_bits'][$z]){
							$asm .= 'word ';
						}else{
							$asm .= 'dword ';
						}
					}
				}			
				$asm .= $y;

				$last_params_cont = $y;                  //最后一个参数
				$last_params_type = $c_obj['p_type'][$z];//最后一个参数 类型
				$last_params_bits = $c_obj['p_bits'][$z];//最后一个参数 位数
			}
		}	

		if (false !== $rel_param_result){
			//var_dump ($rel_param_result);
			if (count($rel_param_result) > 1){
				var_dump ($rel_param_result);
			}
			$label_buf = '';
			foreach ($rel_param_result as $z => $y){
				$c_rel_index = $c_obj['rel'][$z]['i'];
				$c_rel_copy  = $c_obj['rel'][$z]['c'];
				$c_rel_name  = $UniqueHead.'RELINFO_'.$sec.'_'.$c_rel_index.'_'.$c_rel_copy; 
				
				//$c_rel = explode ('_',$y['rel']);
				//
				//当重定位 类型 isMem 且 最后参数为 imm，则重定位 不在末 4位，特殊处理，见 readme.reloc.txt
				//VirtualAddress
				if (($c_rel_info[$c_rel_index][$c_rel_copy]['isMem'])&&($last_params_type === 'i')){
					$asm  = substr($asm,0,strlen($asm) - strlen($last_params_cont));
					//
					$last_params_modified_bits = $mem_bits;
					if ((8 == $last_params_bits) or (16 == $last_params_bits) or (32 == $last_params_bits)){ //最后整数单位位数有效且小于Mem.bits...
						if ($last_params_bits < $mem_bits){
							$last_params_modified_bits = $last_params_bits;
						}
						
					}
					if (8 == $last_params_modified_bits){
						$asm .= 'byte strict '.$last_params_cont;
						$buf_head .= 'dd '.$c_rel_name.'_label - sec_'."$c_sec".'_start - 4 - 1'."$enter_flag";                
					}elseif (16 == $last_params_modified_bits){
						$asm .= 'word strict '.$last_params_cont;
						$buf_head .= 'dd '.$c_rel_name.'_label - sec_'."$c_sec".'_start - 4 - 2'."$enter_flag";                
					}else{
						$asm .= 'dword strict '.$last_params_cont;
						$buf_head .= 'dd '.$c_rel_name.'_label - sec_'."$c_sec".'_start - 4 - 4'."$enter_flag";                
					}
					//排错 (已解决?!)
					if ($mem_bits != $last_params_modified_bits){
						echo "<br> BUG?? : $mem_bits , $last_params_bits , $last_params_modified_bits , $last_params_type";
						echo "<br> <font color=red> $asm </font>";
					}
					//
				}else{
					$buf_head .= 'dd '.$c_rel_name.'_label - sec_'."$c_sec".'_start - 4'."$enter_flag";                
				}
																						   //SymbolTableIndex
				$buf_head .= 'dd '.$c_rel_info[$c_rel_index][$c_rel_copy]['SymbolTableIndex']."$enter_flag";  
				$buf_head .= 'dw '.$c_rel_info[$c_rel_index][$c_rel_copy]['Type']."$enter_flag";//Type

				$reloc_info_2_rewrite_table[$c_sec][] = $c_rel_name;
				if ($c_rel_info[$c_rel_index][$c_rel_copy]['isLabel']){ //标号
					str_replace($c_rel_name,' strict '.$c_rel_name.'_label',$asm);
					$asm = str_replace($c_rel_name,' strict '.$c_rel_name.'_label',$asm);
					if ($c_rel_info[$c_rel_index][$c_rel_copy]['value'] !== '0'){
						$non_null_labels[$sec][$c_rel_index][$c_rel_copy] = $c_rel_info[$c_rel_index][$c_rel_copy]['value'];
					}
				}else{                                              //参数
					if ($c_rel_info[$c_rel_index][$c_rel_copy]['isMem']){//内存指针
						$asm = str_replace('[','[DWORD ',$asm);                     //作为内存指针的重定位  强制定位为32位 [DWORD xxx]
						$asm = str_replace($c_rel_name,'0x'.$c_rel_info[$c_rel_index][$c_rel_copy]['value'],$asm);
					}else{                                          //常数	
																	//常数有可能被多态为： mov eax,- (UNEST_RELINFO_104_3_2) / 需 2步替换
																	//                     先整个参数前加strict定义，然后再替换重定位值
						$asm = str_replace($y['org'],' strict dword '.$y['org'],$asm);
						$asm = str_replace($c_rel_name,'0x'.$c_rel_info[$c_rel_index][$c_rel_copy]['value'],$asm);
					}
				}
				$label_buf .= "$enter_flag".$c_rel_name.'_label'.' : ';
			}
			$buf .= $asm;
			$buf .= $label_buf;
		}else{			    
			$buf .= $asm;
		}			
		$buf .= "$commit"."$enter_flag";

		return;
	}

	
	public static function gen_asm_file($out_file,$a,&$reloc_info_2_rewrite_table,&$non_null_labels){
		global $UniqueHead;

		//global $c_soul_usable;
		
		global $max_output; //输出 最大 行数



		global $my_params;

		$total_buf = '';	
		$enter_flag = "\r\n";//"<br>";//"\r\n";
			
			if ($buf_head = IOFormatParser::out_file_buff_head($a)){
				$buf_head .= $enter_flag;
			}

			$buf = 'sec_'."$a".'_start:'."$enter_flag";
			
			//echo "<br><font color=red>$soul_writein_Dlinked_List_start</font>";
			$next = ConstructionDlinkedListOpt::readListFirstUnit();

			while (true){
				//echo "<br>$next :";
				//var_dump ($soul_writein_Dlinked_List[$next]);
				if ($max_output){
					$max_output --;
				}

				$current = ConstructionDlinkedListOpt::getDlinkedList($next);
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				//prev.fat ? -> 加入脂肪	
				///*
				if (OrgansOperator::CheckFatAble($current,1)){
					$buf .= OrganFat::start(5,$enter_flag,$next,1);
				}
				//*/
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				//内容
				if ($my_params['echo']){
					$show_len = '[List No:'.$next.'] [len:'.$current['len'].']';
					if (ConstructionDlinkedListOpt::issetRelJmpRange($next)){//	if (isset($c_rel_jmp_range[$next])){
						$show_len .= '[range (without Fat):';
						$show_len .= ConstructionDlinkedListOpt::readRelJmpRange($next,'range');
						$show_len .=']';          //$show_len .= '[range:'.$c_rel_jmp_range[$next]['range'].']';
					}
				}else{
					$show_len = '';
				}

				if (isset($current['label'])){
					$buf .= $current['label']."$enter_flag";
				}else{
					$comment = '';
					if (isset ($current['poly'])){	
						$comment = ' ;@@@ poly';
						if (true === $current['soul']){
							$comment = ' ;@@@ poly [from soul]';
						}						
					}elseif (isset ($current['bone'])){							
						$comment = ' ;&&& bone';
					}elseif (isset ($current['meat'])){	
						$comment = ' ;*** meat';
					}else{
						$comment = ';### org opt';
					}
					self::gen_asm_file_kid($a,OrgansOperator::GetByDListUnit($current,'code'),$buf,$buf_head,$enter_flag,$reloc_info_2_rewrite_table,$non_null_labels,$comment.$show_len);					
				}
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				//next.fat? -> 加入脂肪
				///*
				if (OrgansOperator::CheckFatAble($current,2)){
					$buf .= OrganFat::start(5,$enter_flag,$next,1);
				}
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				if (ConstructionDlinkedListOpt::issetDlinkedListUnit($next,'n')){	
					$next =  ConstructionDlinkedListOpt::getDlinkedList($next,'n');

					//echo " $next -> ";

				}else{
					break;
				}
			}	

			$buf .= 'sec_'."$a".'_end:'."$enter_flag";
			   
			$total_buf .= "$enter_flag".";********** section $a **********"."$enter_flag";
			$total_buf .= "$buf_head";
			$total_buf .= "$buf";
		
		if (0 === $max_output){	
			return false;
		}
		

		file_put_contents ($out_file,$total_buf,FILE_APPEND);
		return true;

	}

}