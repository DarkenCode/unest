<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}

/******************************************/
//organ.func.php (for ready)

define ('SOUL',4);

class OrgansOperator{
	private static $_Asm_Result;

    //init
	public static function init($sec){
        global $StandardAsmResultArray;
    
        self::$_Asm_Result  = $StandardAsmResultArray[$sec];       
	}


    //根据 双链表 单位 返回 organ 单位
	//params:
	//         $unit:     DList' unit (array)
	//         $branch: 'code' | 'usable' | 'fat'
	//         $status: sub index  
	public static function GetByDListUnit($unit,$branch='code',$key=false){
		
		$ret = false;

		if (isset(self::$_Asm_Result[$unit['c']])){
			$ret = self::$_Asm_Result[$unit['c']];
		}
		
		if ((false !== $key) and (false !== $ret)){
		    if (isset($ret[$key])){
			    $ret = $ret[$key];
			}else{
			    $ret = false;
			}
		}
	    return $ret;
	}
}
/******************************************/
//用于 准备 的 函数 集

class ReadyFunc{

	//////////////////////////////////////////////////////
	// 1 去掉 所有可用记录 中的 空 行 
	// 2 使用 可用内存记录索引数组，一种可用内存 具体描述 保存在索引中，使用的地方都是调用
	// 3 'prev' => 'p' // 'next' => 'n'
	//
	public static function compress_same_char_output($soul_usable,&$all_valid_mem_opt_index){
		$all_valid_mem_opt_record  = array(); //有效内存  集 ['code']['bits']['opt'] = index number
		$ret = array();
		$index = 1;
		foreach ($soul_usable as $a => $b){
			foreach ($b as $c => $d){
				if ($d['prev']['flag_write_able']){
					$ret[$a][$c]['p']['flag_write_able'] = $d['prev']['flag_write_able'];
				}
				if ($d['prev']['normal_write_able']){
					$ret[$a][$c]['p']['normal_write_able'] = $d['prev']['normal_write_able'];
				}
				if ($d['prev']['mem_opt_able']){
					foreach ($d['prev']['mem_opt_able'] as $z => $y){
						if ($all_valid_mem_opt_record[$z][$y['bits']][$y['opt']]){
							$ret[$a][$c]['p']['mem_opt_able'][] = $all_valid_mem_opt_record[$z][$y['bits']][$y['opt']];
						}else{
							$all_valid_mem_opt_index[$index] = $d['prev']['mem_opt_able'][$z];
							$all_valid_mem_opt_index[$index]['code'] = $z;
							$all_valid_mem_opt_record[$z][$y['bits']][$y['opt']] = $index;
							$ret[$a][$c]['p']['mem_opt_able'][] = $index;
							$index ++;
						}
					}
				}
				if (true === $d['prev']['stack']){
					$ret[$a][$c]['p']['stack'] = true;
				}

				if ($d['next']['flag_write_able']){
					$ret[$a][$c]['n']['flag_write_able'] = $d['next']['flag_write_able'];
				}
				if ($d['next']['normal_write_able']){
					$ret[$a][$c]['n']['normal_write_able'] = $d['next']['normal_write_able'];
				}
				if ($d['next']['mem_opt_able']){
					foreach ($d['next']['mem_opt_able'] as $z => $y){
						if ($all_valid_mem_opt_record[$z][$y['bits']][$y['opt']]){
							$ret[$a][$c]['n']['mem_opt_able'][] = $all_valid_mem_opt_record[$z][$y['bits']][$y['opt']];
						}else{
							$all_valid_mem_opt_index[$index] = $d['next']['mem_opt_able'][$z];
							$all_valid_mem_opt_index[$index]['code'] = $z;
							$all_valid_mem_opt_record[$z][$y['bits']][$y['opt']] = $index;
							$ret[$a][$c]['n']['mem_opt_able'][] = $index;
							$index ++;
						}
					}
				}
				if (true === $d['next']['stack']){
					$ret[$a][$c]['n']['stack'] = true;
				}
			}
		}
		return $ret;
	}


	///////////////////////////////////////////////////////
	//同位置 可用内存合并
	//规则：
	//      可读 内存地址 -》 清所有bits较小的 可读          内存地址
	//      读写 内存地址 -》 清所有bits较小的 可读 (或读写) 内存地址
	private static function do_merge_same_loc_mem($b){
		$ret = $b;
		foreach ($b as $code => $c){
			unset ($c['reg']);
			if (1 == count($c)){ //只有一个，结束
				continue;
			}
			if ($c[32] > 1){     //32位写
				unset ($ret[$code][16]);
				unset ($ret[$code][8]);
				continue;
			}elseif ($c[16] > 1){
				unset ($ret[$code][8]);
			}
			if ($c[32] == 1){
				if (1 == $ret[$code][16]){
					unset ($ret[$code][16]);
				}
				if (1 == $ret[$code][8]){
					unset ($ret[$code][8]);
				}
				continue;
			}elseif ($c[16] == 1){
				if (1 == $ret[$code][8]){
					unset ($ret[$code][8]);
				}
				continue;
			}
		}
		return $ret;
	}

	private static function merge_same_loc_mem($z){
		$ret = array();
		foreach ($z as $a => $b){
			if (isset ($b['prev'])){
				$ret[$a]['prev'] = self::do_merge_same_loc_mem($b['prev']);			
			}
			if (isset ($b['next'])){
				$ret[$a]['next'] = self::do_merge_same_loc_mem($b['next']);			
			}
		}
		return $ret;
	}




	//////////////////////////////////////////////////////
	//同流程 中 有效内存读写 AND 合并
	//保守原则，code相同： 读+写=读  bits不同，取 小
	//          code不同： 丢弃
	//
	private static function combine_valid_mem_array($c_exec_list,$c_valid_mem_opt_tmp){
		$ret = array();
		
		foreach ($c_exec_list as $a => $b){	    
			foreach ($b as $c => $d){
				if ('-' === $d)
					continue;
				if (!$history_prev[$d]){  //  第一次，直接赋值
					$history_prev[$d] = true;
					$ret[$d]['prev'] = $c_valid_mem_opt_tmp[$a][$d]['prev'];
				}else{               //非第一次，做merge
					if ((!is_array($ret[$d]['prev'])) || (!is_array($c_valid_mem_opt_tmp[$a][$d]['prev']))){
						unset ($ret[$d]['prev']);
					}else{
						if ($ret[$d]['prev'] == $c_valid_mem_opt_tmp[$a][$d]['prev']){
						
						}else{			
							//echo "<font color=red><br>$d prev<br>$a</font>";
							$ret[$d]['prev'] = self::merge_available_mem_array ($ret[$d]['prev'],$c_valid_mem_opt_tmp[$a][$d]['prev']);
						}
					}
				}

				if ('-' === $b[$c + 1]){ //jcc 跳转 的后保护被忽略，而不是禁用所有 | jmp/ret/call 之类绝对跳转不管，后一指令的前保护就等于继承了
						continue;
				}
				if (!$history_next[$d]){  //  第一次，直接赋值
					
					$history_next[$d] = true;
					$ret[$d]['next'] = $c_valid_mem_opt_tmp[$a][$d]['next'];				
				}else{               //非第一次，做merge
					if ((!is_array($ret[$d]['next'])) || (!is_array($c_valid_mem_opt_tmp[$a][$d]['next']))){
						unset ($ret[$d]['next']);
					}else{
						if ($ret[$d]['next'] == $c_valid_mem_opt_tmp[$a][$d]['next']){ 
							
						}else{
							//echo "<font color=red><br>$d next<br>$a</font>";
							$ret[$d]['next'] = self::merge_available_mem_array ($ret[$d]['next'],$c_valid_mem_opt_tmp[$a][$d]['next']);						
						}
					}
				}
			}
		}

		return $ret;
	}
	//对 有效内存 记录 进行 and 操作
	private static function merge_available_mem_array ($a,$b){
		$ret = array();	
		foreach ($a as $z => $y){
			if ($b[$z]){                   //相同 内存地址，按保守 合并
				$c_reg = false;
				if (isset($b[$z]['reg'])){ //相同 内存地址，被影响的通用寄存器肯定相同
					$ret[$z]['reg'] = $b[$z]['reg'];
					unset($b[$z]['reg']);
					unset($a[$z]['reg']);
				}
				foreach ($b[$z] as $c => $d){
					if ($a[$z][$c] == $d){ // 有 完全相同的
						$ret[$z][$c] = $d;
						continue;
					}				
					$c_bits = 8;
					$c_opt  = 1;
					foreach ($a[$z] as $e => $f){ // 保守 取得
						if ($e > $c){
							$c_bits = $c;
						}else{
							$c_bits = $e;
						}
						$c_opt = $f & $d;
					}			
					$ret[$z][$c_bits] = $c_opt;
				}
				/*
				echo "<br>+++++++++++ $z +++++++++<br>";
				var_dump ($a[$z]);
				var_dump ($b[$z]);
				var_dump ($ret[$z]);
				/*
				if ($b[$z]['bits'] < $y['bits']){
					$ret[$z] = $b[$z];
				}else{
					$ret[$z] = $y;
				}
				$ret[$z]['opt'] = $b[$z]['opt'] & $y['opt']; 
				*/
			}    
		}	
		return $ret;
	}

	//////////////////////////////////////////////////////
	//根据位数，生成 标准 可用内存信息 
	//
	/*
	$valid_mem_opt_array[sec][line]
	array
	  'DWORD [EBP+0X10]' =>                                   
		array
		  'opt' => int 1
		  'bits' => int 32
		  'reg' =>                           //内存地址中含有 的寄存器 -》反映到 $normal_register_opt_array 作为只读
			array                            //                           位数默认都为32位
			  0 => string 'EBP' (length=3)
	*/
	private static function mem_opt_bits_parser($a,&$ret){
		foreach ($a as $b => $c){		
			if (is_array($c['prev'])){
				foreach ($c['prev'] as $d => $e){
					foreach ($e as $f => $g){
						if ($f != 'reg'){
							$code = $d; 
							//if ($f == 8){
							//	$code = 'BYTE '.$d; 
							//}elseif ($f == 16){
							//	$code = 'WORD '.$d; 
							//}else{
							//	$code = 'DWORD '.$d; 
							//}
							$ret[$b]['prev']['mem_opt_able'][$code]['opt']  = $g; 
							$ret[$b]['prev']['mem_opt_able'][$code]['bits'] = $f; 				
							if (isset($e['reg'])){
								$ret[$b]['prev']['mem_opt_able'][$code]['reg'] = $e['reg'];
							}
							//var_dump ($ret[$b]['prev']);
						}
					}
				}		 
			}
			if (is_array($c['next'])){
				foreach ($c['next'] as $d => $e){
					foreach ($e as $f => $g){
						if ($f != 'reg'){
							$code = $d; 
							//if ($f == 8){
							//	$code = 'BYTE '.$d; 
							//}elseif ($f == 16){
							//	$code = 'WORD '.$d; 
							//}else{
							//	$code = 'DWORD '.$d; 
							//}
							$ret[$b]['next']['mem_opt_able'][$code]['opt']  = $g; 
							$ret[$b]['next']['mem_opt_able'][$code]['bits'] = $f; 				
							if (isset($e['reg'])){
								$ret[$b]['next']['mem_opt_able'][$code]['reg'] = $e['reg'];
							}
						}
					}
				}
			}
		}
	}

	///////////////////////////////////////////////////////
	//有效内存合并
	//注：新为当前指令影响 | 旧为继承而来的可用内存
	//    w:读写  r: 只读
	//
	//            新             旧                  now
	// bit相同    r              w                   bit r
	// bit相同    r              r                   bit r

	// bit相同    w              r                   bit w
	// bit相同    w              w                   bit w

	// 新bit大    r              w                   新bit r 
	// 新bit大    r              r                   新bit r

	// 新bit大    w              r                   新bit w
	// 新bit大    w              w                   新bit w

	// 旧bit大    r              w                   旧bit r
	// 旧bit大    r              r                   旧bit r

	// 旧bit大    w              r                   旧bit r  &  新bit w
	// 旧bit大    w              w                   旧bit w
	//
	//向上遍历  数组为当前指令影响，bits/opt是继承而来
	private static function mem_usable_combine($new,$old_bits,$old_opt){

		//echo "<br>+++++++++++++++++++++++<br>";
		//var_dump ($new);
		//echo "<br> $old_bits  $old_opt";


		$ret = array();
		$ret['reg'] = $new['reg'];
		unset ($new['reg']);
		foreach ($new  as $a => $b){
			if ($old_bits <= $a){      //新bits大 或 bits相同
				if ($b == 1){    //新 r
					$ret[$a] = 1;				
				}else{                 //新 w
					$ret[$a] = 3;
				}		
			}else{                     //旧bits 大 
				if ($b == 1){          //新 r
					$ret[$old_bits] = 1;			
				}elseif ($old_opt == 1){     //新w 旧r
					$ret[$old_bits] = 1;			
					$ret[$a] = 3;
				}else{                 //新w 旧w
					$ret[$old_bits] = 3;
				}
			
			}
		}
		
		//var_dump ($ret);
		
		return $ret;
	}

	//////////////////////////////////////////////////////
	//指令有对 构造 有效内存  的通用寄存器的操作，中断 继承
	//返回 TRUE    中断
	//     FALSE 不中断 
	private static function break_avoid_mem_usable_reg($obj_reg,$c_normal_register_opt){
		//////////////////////////////////////////////////////////////////////
		$break = false;
		if ($obj_reg){ //有效内存 由 寄存器 构建，当寄存器被改写，中断所有操作
			foreach ($obj_reg as $z => $y){
				if (is_array($c_normal_register_opt[$y])){
					foreach ($c_normal_register_opt[$y] as $x => $w){
						if ($w > 1){										
							$break = true;
							break;
						}
					}
				}
				if ($break)	
					break;						
			}
			//if ($break)	
			//	break;
		}
		//////////////////////////////////////////////////////////////////////
		return $break;
	}



	//////////////////////////////////////////////////////
	//见 readme.txt
	//2013/03/29
	//有效内存读写归纳
	private static function get_execlist_usable_mem($c_exec_thread_list,$c_valid_mem_opt_array,&$ret,$c_normal_register_opt_array){
		//////////////////////////////////////////////
		//向上继承
		foreach ($c_valid_mem_opt_array as $a => $b){
			$objs = array_keys($c_exec_thread_list,$a);
			//var_dump ($objs);
			foreach ($objs as $c => $d){
				for ($k = 0;;$k++){
					if (!$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]){
						break;
					}
				
					$obj_reg = false;
					//正常情况下，一条指令至多一个内存地址，直接[$k = 0]取
					if ($c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['reg']){
						$obj_reg = $c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['reg'];
					}
					$opt_permission = 1;
					if (-1 == $c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['opt']){ //如 lea指令的第二参数，不读也不写
																						   //                      不作为有效内存处理
						continue;
					}
					if (2 == $c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['opt']){
						$opt_permission = 3;
					}

					$c_bits = $c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['bits'];

					//
					$i = $d;

					while ($i >= 0){ //向上遍历					
						if (isset($ret[$c_exec_thread_list[$i]]['prev'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']])){
							$ret[$c_exec_thread_list[$i]]['prev'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']] = self::mem_usable_combine ($ret[$c_exec_thread_list[$i]]['prev'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']],$c_bits,$opt_permission);
						}else{
							$ret[$c_exec_thread_list[$i]]['prev'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']][$c_bits] = $opt_permission;				
						}                     
						if ($obj_reg){						
							$ret[$c_exec_thread_list[$i]]['prev'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']]['reg'] = $obj_reg;
						}
						//echo $c_exec_thread_list[$i]." prev,";
						$i--;			
						if ('-' === $c_exec_thread_list[$i]){ //来源跳转					
							$i--;	
							//有效内存 由 寄存器 构建，当寄存器被改写，中断所有操作
							if (self::break_avoid_mem_usable_reg($obj_reg,$c_normal_register_opt_array[$c_exec_thread_list[$i]])){
								break;
							}
							continue;
						}	
						if ($i >=0 ){
							if (isset($ret[$c_exec_thread_list[$i]]['next'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']])){
								$ret[$c_exec_thread_list[$i]]['next'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']] = self::mem_usable_combine ($ret[$c_exec_thread_list[$i]]['next'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']],$c_bits,$opt_permission);
							}else{						
								$ret[$c_exec_thread_list[$i]]['next'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']][$c_bits] = $opt_permission;
							}						
							if ($obj_reg){
								$ret[$c_exec_thread_list[$i]]['next'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']]['reg'] = $obj_reg;
							}
							//echo $c_exec_thread_list[$i]." after,";
						}else{
							break;
						}

						//////////////////////////////////////////////////////////////////////
						//有效内存 由 寄存器 构建，当寄存器被改写，中断所有操作
						if (self::break_avoid_mem_usable_reg($obj_reg,$c_normal_register_opt_array[$c_exec_thread_list[$i]])){
							break;
						}					
						//////////////////////////////////////////////////////////////////////
						//有任何(除 自身 外) 任何操作 【MEM】则断 -> 写操作
						if ($opt_permission > 1){
							if (is_array($c_valid_mem_opt_array[$c_exec_thread_list[$i]][$k])){
								if ($c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code'] !== $c_valid_mem_opt_array[$c_exec_thread_list[$i]][$k]['code']){
									$opt_permission = 1;
								}
							}
						}
					}		
				}
			}        
		}
		//////////////////////////////////////////////
		//向下继承
		foreach ($c_valid_mem_opt_array as $a => $b){
			$objs = array_keys($c_exec_thread_list,$a);
			foreach ($objs as $c => $d){
				for ($k = 0;;$k++){
					if (!$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]){
						break;
					}
				
					$obj_reg = false;
					//正常情况下，一条指令至多一个内存地址，直接[$k = 0]取
					if ($c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['reg']){
						$obj_reg = $c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['reg'];
					}
					
					if (-1 == $c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['opt']){ //如 lea指令的第二参数，不读也不写
																						   //                      不作为有效内存处理
						continue;
					}				
					
					$c_bits = $c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['bits'];

					//
					$i = $d;				
					$opt_permission = 1; //只读		向下继承，全部只读
					
					while ($c_exec_thread_list[$i+1]){        //向下遍历，继承 可读
															  //如果到本流程最后一个单位，此单位后可用为空 
															  //
						//////////////////////////////////////////////////////////////////////
						//有效内存 由 寄存器 构建，当寄存器被改写，中断所有操作
						if (self::break_avoid_mem_usable_reg($obj_reg,$c_normal_register_opt_array[$c_exec_thread_list[$i]])){
							break;
						}						
						//////////////////////////////////////////////////////////////////////					
						if (!isset($ret[$c_exec_thread_list[$i]]['next'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']][$c_bits])){
							$ret[$c_exec_thread_list[$i]]['next'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']][$c_bits] = $opt_permission;
							if ($obj_reg){
								$ret[$c_exec_thread_list[$i]]['next'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']]['reg'] = $obj_reg;
							}
						}
						$i ++;	
						if (!isset($ret[$c_exec_thread_list[$i]]['prev'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']][$c_bits])){
							$ret[$c_exec_thread_list[$i]]['prev'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']][$c_bits] = $opt_permission;
							if ($obj_reg){
								$ret[$c_exec_thread_list[$i]]['prev'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']]['reg'] = $obj_reg;
							}
						}
						if ('-' === $c_exec_thread_list[$i + 1]){  //来源跳转
							$i ++;
							continue;
						}
					}
				}
			}
		}
	}

	//把 堆栈记录 保存进 usable数组

	private static function add_stack_usable (&$soul_usable,$stack_result,$c_exec_thread_list,$sec){	
		foreach ($stack_result as $a => $b){
			foreach ($b as $c => $d){
				if ($d === 3){ //后
					$soul_usable[$sec][$c_exec_thread_list[$c]]['next']['stack'] = false;
					$soul_usable[$sec][$c_exec_thread_list[$c]]['prev']['stack'] = false;
				}elseif ($d === 2){
					$soul_usable[$sec][$c_exec_thread_list[$c]]['next']['stack'] = false;
				}elseif ($d === 1){
					//echo "<br>fuck $a  $c ".$c_exec_thread_list[$c];
					//var_dump ($soul_usable[$sec][$c_exec_thread_list[$c]]);
					$soul_usable[$sec][$c_exec_thread_list[$c]]['prev']['stack'] = false;
					//var_dump ($soul_usable[$sec][$c_exec_thread_list[$c]]);
				}			
			}
		}	
	}

	//////////////////////////////////////////////////////
	//$soul_usable[section][line][prev][reg_write_able] [EAX] => bits
											//                                 [flag_write_able] [CF]  => 1
											//                                 [mem_read_able  ] ?
											//                                 [mem_write_able ] ?
											//                                  
											//                           [next]
	//////////////////////////////////////////////////////
	//
	//
	public static function get_soul_usable_limit($CodeSectionArray,$exec_thread_list,$StandardAsmResultArray,$stack_used,$stack_broke){

		global $normal_register_opt_array;
		global $flag_register_opt_array;
		global $valid_mem_opt_array;

		
		global $soul_forbid;
		global $soul_usable;

		$normal_reg_extend_down = array( //位数向下包容  {{{al}{ah}ax}eax}
		   8 => array (8 => true),
		   9 => array (9 => true),
		  16 => array (8 => true,9=>true,16=>true),
		  32 => array (8 => true,9=>true,16=>true,32=>true),
		);
		$normal_reg_extend_all = array( //位数向上/下包容
		   8 => array (8 => true,16=>true,32=>true),
		   9 => array (9 => true,16=>true,32=>true),
		  16 => array (8 => true,9=>true,16=>true,32=>true),
		  32 => array (8 => true,9=>true,16=>true,32=>true),
		);

		foreach ($CodeSectionArray as $a => $b){
			echo "<br>+++++++++++++++++<br>";
			$prev_dealed_record = array(); // 前部
			$next_dealed_record = array(); // 后部 已处理记录，未处理时直接赋值，已处理后，与新值做 and 运算后附值
			$c_valid_mem_opt_tmp = array();// 临时 保存 流程中 有效内存 灵魂 前后 可用记录
			foreach ($exec_thread_list[$a] as $c => $d){
				//处理本流程的有效内存
				if (is_array($valid_mem_opt_array[$a])){
					self::get_execlist_usable_mem($d,$valid_mem_opt_array[$a],$c_valid_mem_opt_tmp[$c],$normal_register_opt_array[$a]);
				}
				//
				$c_flag_reg_usable_array   = array();
				$c_normal_reg_usable_array = array();
				$c_forbid_flag_reg_usable_array   = array();
				$c_forbid_normal_reg_usable_array = array();

				//堆栈 可用 
				$stack_flag   = false;    //默认堆栈不可用
				$stack_buff   = array();  //临时数组
				$stack_result = array();//搜集本流程中不可用堆栈的指令行号


				for ($max = count($d) - 1; $max >= 0; $max--){	  // 倒序 遍历
					if ('-' === $exec_thread_list[$a][$c][$max]){ // 跳转 ..忽略 来源soul的 Next 项 赋值
						$miss_next_element = true; 
						continue;
					}
					if ($miss_next_element){
						$miss_next_element = false;
					}else{
						if (!$next_dealed_record[$exec_thread_list[$a][$c][$max]]){
							$soul_usable[$a][$exec_thread_list[$a][$c][$max]]['next']['flag_write_able']    = $c_flag_reg_usable_array;
							$soul_usable[$a][$exec_thread_list[$a][$c][$max]]['next']['normal_write_able']  = $c_normal_reg_usable_array;
							$next_dealed_record[$exec_thread_list[$a][$c][$max]] = true;
							if (!empty($c_forbid_flag_reg_usable_array))
								$soul_forbid[$a][$exec_thread_list[$a][$c][$max]]['n']['flag']   = $c_forbid_flag_reg_usable_array;
							if (!empty($c_forbid_normal_reg_usable_array))
								$soul_forbid[$a][$exec_thread_list[$a][$c][$max]]['n']['normal'] = $c_forbid_normal_reg_usable_array;
						}else{
							if (!empty($c_forbid_normal_reg_usable_array))
								self::add_normal_array ($soul_forbid[$a][$exec_thread_list[$a][$c][$max]]['n']['normal'],$c_forbid_normal_reg_usable_array);
							if (!empty($c_forbid_flag_reg_usable_array))
								self::add_flag_array ($soul_forbid[$a][$exec_thread_list[$a][$c][$max]]['n']['flag'],$c_forbid_flag_reg_usable_array);
							self::merge_flag_reg_array  ($soul_usable[$a][$exec_thread_list[$a][$c][$max]]['next']['flag_write_able'],$c_flag_reg_usable_array);
							self::merge_normal_reg_array($soul_usable[$a][$exec_thread_list[$a][$c][$max]]['next']['normal_write_able'],$c_normal_reg_usable_array);
						}
					}
					//标志 寄存器
					if (is_array($flag_register_opt_array[$a][$d[$max]])){
						foreach ($flag_register_opt_array[$a][$d[$max]] as $e => $f){
							if ($f == 2){ //2. 写操作
								$c_flag_reg_usable_array[$e] = true;
								unset ($c_forbid_flag_reg_usable_array[$e]);
							}else{ // 1.只读 或 3.读写							
								unset ($c_flag_reg_usable_array[$e]);
								$c_forbid_flag_reg_usable_array[$e] = true;
							}
						}
					}
					//通用寄存器
					if (is_array($normal_register_opt_array[$a][$d[$max]])){
						foreach ($normal_register_opt_array[$a][$d[$max]] as $e => $f){
							if ($e === 'EIP')  //忽略EIP
								continue;	
							foreach ($f as $g => $h){							
								if ($h == 2){ //2. 写操作 ：eax可写  => al,ah,ax,eax 可写   : 向下扩展 $normal_reg_extend_down
									foreach ($normal_reg_extend_down[$g] as $i => $j){
										if (Instruction::getRegByIdxBits($i,$e))
											$c_normal_reg_usable_array[$e][$i] = true;
									}
									foreach ($normal_reg_extend_all[$g] as $i => $j){  // 显性 禁写
										unset ($c_forbid_normal_reg_usable_array[$e][$i]);
									}
								}else{ // 1.只读 或 3.读写：ax不可写 => al,ah,ax,eax 不可写 : 向上/下扩展 $normal_reg_extend_all
									foreach ($normal_reg_extend_all[$g] as $i => $j){									
										unset ($c_normal_reg_usable_array[$e][$i]);
									}
									foreach ($normal_reg_extend_down[$g] as $i => $j){ // 显性 禁写
										if (Instruction::getRegByIdxBits($i,$e))
											$c_forbid_normal_reg_usable_array[$e][$i] = true;
									}
								}
							}
						}
					}
					if (!$prev_dealed_record[$exec_thread_list[$a][$c][$max]]){
						$soul_usable[$a][$exec_thread_list[$a][$c][$max]]['prev']['flag_write_able']    = $c_flag_reg_usable_array;
						$soul_usable[$a][$exec_thread_list[$a][$c][$max]]['prev']['normal_write_able']  = $c_normal_reg_usable_array;
						$prev_dealed_record[$exec_thread_list[$a][$c][$max]] = true;
						if (!empty($c_forbid_flag_reg_usable_array))
							$soul_forbid[$a][$exec_thread_list[$a][$c][$max]]['p']['flag']   = $c_forbid_flag_reg_usable_array;
						if (!empty($c_forbid_normal_reg_usable_array))
							$soul_forbid[$a][$exec_thread_list[$a][$c][$max]]['p']['normal'] = $c_forbid_normal_reg_usable_array;
					}else{
						if (!empty($c_forbid_normal_reg_usable_array))
							self::add_normal_array ($soul_forbid[$a][$exec_thread_list[$a][$c][$max]]['p']['normal'],$c_forbid_normal_reg_usable_array);
						if (!empty($c_forbid_flag_reg_usable_array))
							self::add_flag_array ($soul_forbid[$a][$exec_thread_list[$a][$c][$max]]['p']['flag'],$c_forbid_flag_reg_usable_array);
						self::merge_flag_reg_array ($soul_usable[$a][$exec_thread_list[$a][$c][$max]]['prev']['flag_write_able'],$c_flag_reg_usable_array);
						self::merge_normal_reg_array($soul_usable[$a][$exec_thread_list[$a][$c][$max]]['prev']['normal_write_able'],$c_normal_reg_usable_array);
					}
					
					//堆栈可用 统计 处理 (未设置的一律设置为 容许)
					if (!isset($soul_usable[$a][$exec_thread_list[$a][$c][$max]]['prev']['stack'])){
						$soul_usable[$a][$exec_thread_list[$a][$c][$max]]['prev']['stack'] = true;
					}
					if (!isset($soul_usable[$a][$exec_thread_list[$a][$c][$max]]['next']['stack'])){
						$soul_usable[$a][$exec_thread_list[$a][$c][$max]]['next']['stack'] = true;
					}
					//
					if (false === $stack_flag){
						$stack_buff[$max] = 3;
					}
					if ($stack_broke[$a][$exec_thread_list[$a][$c][$max]]){					
						if (true === $stack_flag){
							$stack_flag = false;
						}else{
							$stack_buff[$max] = 2;
							$stack_result[$max] = $stack_buff;
							unset($stack_buff);
						}	    
						$stack_buff[$max] = 1;
					}elseif ($stack_used[$a][$exec_thread_list[$a][$c][$max]]){					
						//echo "<br><font color=red>$a $max</font>";
						unset($stack_buff);
						$stack_flag = true;
					}
				}
				if ((false === $stack_flag)&&(!empty($stack_buff))){
					$stack_result[$max] = $stack_buff;
				}
				self::add_stack_usable ($soul_usable,$stack_result,$exec_thread_list[$a][$c],$a); //把 堆栈记录 保存进 usable数组			

			}		
			//把寄存器 可写 记录合并 {8,9,16} => 16  {8,9,16,32} => 32
			foreach ($StandardAsmResultArray[$a] as $z => $y){
				if ($soul_usable[$a][$z]['prev']['normal_write_able']){
					foreach ($soul_usable[$a][$z]['prev']['normal_write_able'] as $x => $w){
						if ($w[32]){
							unset ($soul_usable[$a][$z]['prev']['normal_write_able'][$x][8]);
							unset ($soul_usable[$a][$z]['prev']['normal_write_able'][$x][9]);
							unset ($soul_usable[$a][$z]['prev']['normal_write_able'][$x][16]);
						}elseif ($w[16]){
							unset ($soul_usable[$a][$z]['prev']['normal_write_able'][$x][8]);
							unset ($soul_usable[$a][$z]['prev']['normal_write_able'][$x][9]);
						}
					}
				}
				if ($soul_forbid[$a][$z]['p']['normal']){
					foreach ($soul_forbid[$a][$z]['p']['normal'] as $x => $w){
						if ($w[32]){
							unset ($soul_forbid[$a][$z]['p']['normal'][$x][8]);
							unset ($soul_forbid[$a][$z]['p']['normal'][$x][9]);
							unset ($soul_forbid[$a][$z]['p']['normal'][$x][16]);
						}elseif ($w[16]){
							unset ($soul_forbid[$a][$z]['p']['normal'][$x][8]);
							unset ($soul_forbid[$a][$z]['p']['normal'][$x][9]);
						}
					}
				}
				if (is_array($soul_usable[$a][$z]['next']['normal_write_able'])){
					foreach ($soul_usable[$a][$z]['next']['normal_write_able'] as $x => $w){
						if ($w[32]){
							unset ($soul_usable[$a][$z]['next']['normal_write_able'][$x][8]);
							unset ($soul_usable[$a][$z]['next']['normal_write_able'][$x][9]);
							unset ($soul_usable[$a][$z]['next']['normal_write_able'][$x][16]);
						}elseif ($w[16]){
							unset ($soul_usable[$a][$z]['next']['normal_write_able'][$x][8]);
							unset ($soul_usable[$a][$z]['next']['normal_write_able'][$x][9]);
						}
					}
				}
				if ($soul_forbid[$a][$z]['n']['normal']){
					foreach ($soul_forbid[$a][$z]['n']['normal'] as $x => $w){
						if ($w[32]){
							unset ($soul_forbid[$a][$z]['n']['normal'][$x][8]);
							unset ($soul_forbid[$a][$z]['n']['normal'][$x][9]);
							unset ($soul_forbid[$a][$z]['n']['normal'][$x][16]);
						}elseif ($w[16]){
							unset ($soul_forbid[$a][$z]['n']['normal'][$x][8]);
							unset ($soul_forbid[$a][$z]['n']['normal'][$x][9]);
						}
					}
				}
			}
			//把当前节表 有效内存 可用记录 AND 操作
			if (is_array($valid_mem_opt_array[$a])){			
				$c_valid_mem_opt_tmp = self::combine_valid_mem_array($exec_thread_list[$a],$c_valid_mem_opt_tmp);	
				// 合并 同类
				$c_valid_mem_opt_tmp = self::merge_same_loc_mem($c_valid_mem_opt_tmp);
				// 标准化 可用 内存地址
				self::mem_opt_bits_parser($c_valid_mem_opt_tmp,$soul_usable[$a]);			
			}
		}
		//exit;
	}
	
	//
	//对通用寄存器 进行 or 操作
	private static function add_normal_array (&$a,$b){
		foreach ($b as $c => $d){
			foreach ($d as $e => $f){
				$a[$c][$e] = true;
			}
		}
	}
	//
	//对标志寄存器 进行 or 操作
	private static function add_flag_array (&$a,$b){
		foreach ($b as $c => $d){
		   $a[$c] = true;
		}
	}
	//
	//对通用寄存器 进行 and 操作
	private static function merge_normal_reg_array (&$a,$b){
		$tmp = array();
		foreach ($a as $z => $y){
			if (is_array($b[$z])){
				foreach ($b[$z] as $x => $w){
					if ($y[$x]){
						$tmp[$z][$x] = true;
					}
				}    
			}else{
				continue;
			}
		}
		$a = $tmp;
	}

	//
	//对标志寄存器 进行 and 操作
	private static function merge_flag_reg_array (&$a,$b){
		$tmp = array();
		foreach ($a as $z => $y){
			if ($b[$z] == $y){
				$tmp[$z] = $y;
			}
		}
		$a = $tmp;
	}


		
	//////////////////////////////////////////////////////
	//
	//
	//标志着流程结束的指令  值   2 参数为非 常数 时结束，如重定位/寄存器/内存指针
	//                           3 条件跳转 参数为 非 常数 时，流程只计算 下一指令
	//
	//处理：遇到跳转，跟着跳转目标走，如为条件跳转，记录下一指令入$c_enumming_array
	//
	private static function deal_exec_thread_list_get($c_line,$c_asm_array,$c_thread_id,$c_bound_end,$c_solid_jmp_to,&$exec_thread_list,$list_id,&$c_enumming_array,&$list_id_ptr,&$jmp_back_record,&$bound_start){
		
	
		global $Intel_instruction;

		while ($c_line < $c_bound_end){
			if (isset($c_asm_array[$c_line])){
				$exec_thread_list[$c_thread_id][$list_id][] = $c_line;

				if (Instruction::isJmp($c_asm_array[$c_line]['operation'],1)){ //条件跳转			    
					$list_id_ptr ++;
					$exec_thread_list[$c_thread_id][$list_id_ptr] = $exec_thread_list[$c_thread_id][$list_id];
					$c_enumming_array[$list_id_ptr] = $c_line + 1;		    
					if ($c_solid_jmp_to[$c_line]){ //有明确跳转 dest
						if ($c_solid_jmp_to[$c_line] <= $c_line){ //回跳记录
							if (true !== $jmp_back_record[$c_line][$c_solid_jmp_to[$c_line]]){
								$jmp_back_record[$c_line][$c_solid_jmp_to[$c_line]] = true;
							}else{
								return;
							}
						}
						$exec_thread_list[$c_thread_id][$list_id][] = '-'; //跳转标志
						$c_line = $c_solid_jmp_to[$c_line];
						continue;					
					}else{      //无明确跳转  dest 
						//echo "<br> fuck $c_thread_id $c_line";
						return;
					}
				}

				if (Instruction::isJmp($c_asm_array[$c_line]['operation'],2)){ //绝对跳转 (备忘:ret/jmp... 后面应无需考虑执行)
																		 	  //         (         call   后面应  需考虑执行)
					$tmp = $c_line + 1;
					if (!$bound_start[$tmp]){					
						$bound_start[$tmp] = true;
						$list_id_ptr ++;
						$c_enumming_array[$list_id_ptr] = $c_line + 1;					
					}
					if ($c_solid_jmp_to[$c_line]){ //有明确跳转 dest
						if ($c_solid_jmp_to[$c_line] <= $c_line){ //回跳记录
							if (true !== $jmp_back_record[$c_line][$c_solid_jmp_to[$c_line]]){
								$jmp_back_record[$c_line][$c_solid_jmp_to[$c_line]] = true;
							}else{
								return;
							}
						}				
						$exec_thread_list[$c_thread_id][$list_id][] = '-'; //跳转标志
						$c_line = $c_solid_jmp_to[$c_line];
						continue;
					}else{                         //无明确跳转 dest / 作为 bound_end -> 下一行作为 bound_start
						return;
					}        
				}

				if ($Intel_instruction[$c_asm_array[$c_line]['operation']]['data']){ //数据定义
					$tmp = $c_line + 1;
					if (!$bound_start[$tmp]){					
						$bound_start[$tmp] = true;
						$list_id_ptr ++;
						$c_enumming_array[$list_id_ptr] = $c_line + 1;					
					}
					return;
				}
			}
			$c_line ++;        
		}
		//echo "$c_line,$c_asm_array,$c_thread_id,$c_bound_end,$c_solid_jmp_to <br><br><br>";
	}


	//////////////////////////////////////////////////////
	//
	//统计 指令 流程
	//
	public static function exec_thread_list_get($CodeSectionArray,$StandardAsmResultArray,&$exec_thread_list,$solid_jmp_to,$AsmLastSec){    
		foreach ($CodeSectionArray as $a => $b){
			$jmp_back_record  = array(); //记录回跳，以免死循环
			$c_enumming_array = array();
			$bound_start = array();      //流程开始 第一 条记录，以免重复
			$list_id_ptr = 0;
			//防 递归 溢出，使用函数方式
			$c_enumming_array[0] = key ($StandardAsmResultArray[$a]);//待处理 的 数组
																	 //[thread_id] => 下一个开始指令 行
			$bound_start[0] = true;
			while (count($c_enumming_array)){
				foreach ($c_enumming_array as $c => $d){
					break;
				}
				unset ($c_enumming_array[$c]);
				self::deal_exec_thread_list_get($d,$StandardAsmResultArray[$a],$a,key($AsmLastSec[$a]),$solid_jmp_to[$a],$exec_thread_list,$c,$c_enumming_array,$list_id_ptr,$jmp_back_record,$bound_start);	   
			}
			//这里 不用，见 readme.txt 2013/03/31
			//对同类项进行合并(包含之中的不做合并，必须完全相同)
			//$exec_thread_list[$a] = combine_same_thread($exec_thread_list[$a]);
		}
		//exit;
	}


	//////////////////////////////////////////////////////
	//
	//将 指令 数组 标准化 处理
	//
	public static function standard_asm(&$myTables,&$garble_rel_info,$AsmResultArray,&$StandardAsmResultArray,&$stack_used,&$stack_broke,$language){
		
		global $Intel_instruction;
		global $Intel_instruction_mem_opt;




		global $normal_register_opt_array;
		global $flag_register_opt_array;
		global $valid_mem_opt_array;

		global $UniqueHead;
		global $pattern_reloc;  //匹配 reloc 信息



		$pattern_regs = Instruction::genRegPattern();

		$CodeSectionArray = $myTables['CodeSectionArray'];
		foreach ($CodeSectionArray as $a => $b){
			foreach ($AsmResultArray[$a] as $c => $d){
				$c_solid_bits = array();    //当前指令参数中有明确指向的位数信息; [参数位置] = bits 
				$mem_ptr_bits_recal = false;//当前指令参数中内存地址没有明确位信息，需要再确定
				$param_include_r_m = false; //当前指令参数有寄存器 或 内存指针
				$c_reloc = false;
				if (preg_match($pattern_reloc,$d['asm'],$tmp)){
					$c_reloc = $tmp[0];
				}
				//把单条指令分为:[prefix][0]        前缀指令
				//                       [1][...]
				//               [operation]        指令
				//               [params][0]        参数
				//                       [1][...]
				//               [p_type][0] i/ m/ r
				//               [p_bits][0] 8/16/32
				$tmp = preg_split('/( |,|\[)/',$d['asm'],-1,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
				$p_ptr = 0;
				$break = false;
				$c_param_type_complete = false; //当前 参数 类型 未 获取
				foreach ($tmp as $z => $x){
					if ($x === ','){
						$p_ptr ++;
						$c_param_type_complete = false;
						continue;
					}

					if (isset($StandardAsmResultArray[$a][$c]['operation'])){
						if ((' ' === $x)&&(!isset($StandardAsmResultArray[$a][$c]['params'][$p_ptr]))){
							continue;
						} 
						//$x = strtoupper ($x);					    
						if (false === $c_param_type_complete){
							//默认  参数类型为i 位数为32     // 寄存器 位数可以确定; 常数(reloc)位数 默认设置为当前编译器位数
															 // 内存指针位数 确定步骤： 1: [...] 前明确有描述，如 byte [...] ,word [...], dword [...]
															 //                         2: 与同指令其它参数做对比 mov al,[123] ，位数为 byte
															 //                         3: 默认设置为 32
							if (!isset($StandardAsmResultArray[$a][$c]['p_type'][$p_ptr])){ 
								$StandardAsmResultArray[$a][$c]['p_type'][$p_ptr] = 'i';
							}
							if (!isset($StandardAsmResultArray[$a][$c]['p_bits'][$p_ptr])){
								$StandardAsmResultArray[$a][$c]['p_bits'][$p_ptr] = 32;							
							}						
							if ('[' === $x) {
								$StandardAsmResultArray[$a][$c]['p_type'][$p_ptr] = 'm';
								if (!$c_solid_bits[$p_ptr]){
									$mem_ptr_bits_recal[$p_ptr] = true;
								}
								$c_param_type_complete = true;
								$param_include_r_m = true;
							}elseif (Instruction::getGeneralRegBits($x)){
								$StandardAsmResultArray[$a][$c]['p_type'][$p_ptr] = 'r';
								$StandardAsmResultArray[$a][$c]['p_bits'][$p_ptr] = Instruction::getGeneralRegBits($x);
								$c_solid_bits[$p_ptr] = Instruction::getGeneralRegBits($x);
								$c_param_type_complete = true;
								$param_include_r_m = true;
							}elseif ('BYTE' === $x){
								$StandardAsmResultArray[$a][$c]['p_bits'][$p_ptr] = 8;
								$c_solid_bits[$p_ptr] = 8;
							}elseif ('WORD' === $x){
								$StandardAsmResultArray[$a][$c]['p_bits'][$p_ptr] = 16;
								$c_solid_bits[$p_ptr] = 16;
							}elseif	('DWORD' === $x){
								$StandardAsmResultArray[$a][$c]['p_bits'][$p_ptr] = 32;
								$c_solid_bits[$p_ptr] = 32;
							}
						}
						$StandardAsmResultArray[$a][$c]['params'][$p_ptr] .= $x; 					
					}else{					
						if ($x === ' '){
							continue;
						}
						//$x = strtoupper($x);
						if ($Intel_instruction[$x]['isPrefix']){
							$StandardAsmResultArray[$a][$c]['prefix'][] = $x;	
							
							//前缀 对 通用/标志 寄存器的影响//
							foreach ($Intel_instruction[$x] as $z => $y){
								if (Instruction::isEflag($z)){
									$flag_register_opt_array[$a][$c][$z] |= $y;
								}elseif (Instruction::getGeneralRegBits($z)){ //32位指令 修改 寄存器都 为32位
									$cri = Instruction::getGeneralRegIndex($z);
									$normal_register_opt_array[$a][$c][$cri][32] |= $y;
								}
							}

						}elseif (is_array($Intel_instruction[$x])){
							if (Instruction::isCantDealInst($x)){ //无法处理的指令，丢弃整个段 的操作
								GeneralFunc::LogInsert($language['section_name']." ".$myTables['CodeSectionArray'][$a]['name'].$language['section_number']." $a ".$language['total_linenumber']." $c , $x ".$language['canot_deal_instruction'],2);
								$break = true;
								break;
								//exit ($x);
							}
							$StandardAsmResultArray[$a][$c]['operation'] = $x;	
							if (is_array($Intel_instruction_mem_opt[$x])){ //指令有 内存操作 (排除前缀)
								$valid_mem_opt_array[$a][$c] = $Intel_instruction_mem_opt[$x];
							}
							if (Instruction::isMatchCC('SETcc',$x)){                   //指令对 参数 位数的影响
								$StandardAsmResultArray[$a][$c]['p_bits'][$p_ptr] = 8;
							}
						}else{ //非法了
							 GeneralFunc::LogInsert($language['section_name']." ".$myTables['CodeSectionArray'][$a]['name'].$language['section_number']." $a ".$language['total_linenumber']." $c , $x ".$language['unknow_instruction'],2);
							 $break = true;
							 break;
						}					
						/////////////////////////////////
					}
				}			
				//检查参数，如有内存地址，则净化它，取得干净的[...]内容(去前缀,含[]号)
				if (is_array($StandardAsmResultArray[$a][$c]['p_type'])){
					foreach ($StandardAsmResultArray[$a][$c]['p_type'] as $z => $y){
						if ('m' === $y){
							if (preg_match('/\[([^\(]*)\]/',$StandardAsmResultArray[$a][$c]['params'][$z],$tmp_filter)){ 
								$StandardAsmResultArray[$a][$c]['params'][$z] = $tmp_filter[0];
							}
						}
					}			
				}
				//

				if ($break){ //出错，放弃当前节表处理
					break;
				}else{

					if (isset($Intel_instruction[$StandardAsmResultArray[$a][$c]['operation']]['multi_op'])){ // 此指令为 可变参数 型? imul 
						$multi_op = count ($StandardAsmResultArray[$a][$c]['params']);
						if (isset($Intel_instruction[$StandardAsmResultArray[$a][$c]['operation']][$multi_op])){
							$c_instruction = $Intel_instruction[$StandardAsmResultArray[$a][$c]['operation']][$multi_op];
						}else{
							GeneralFunc::LogInsert($language['section_name']." ".$myTables['CodeSectionArray'][$a]['name'].$language['section_number']." $a ".$language['total_linenumber']." $c , ".$StandardAsmResultArray[$a][$c]['operation']." ($multi_op) ".$language['multi_op_fail'],2);
							$break = true;
							break;
						}
					}else{
						$c_instruction = $Intel_instruction[$StandardAsmResultArray[$a][$c]['operation']];					
					}
					//指令 对 通用/标志 寄存器的影响//
					foreach ($c_instruction as $z => $y){
						if (Instruction::isEflag($z)){
							$flag_register_opt_array[$a][$c][$z] |= $y;
						}elseif (Instruction::getGeneralRegBits($z)){ //32位指令 修改 寄存器都 为32位
							$cri = Instruction::getGeneralRegIndex($z);
							$normal_register_opt_array[$a][$c][$cri][32] |= $y;
							
							if (Instruction::getGeneralRegIndex($z) === 'ESP'){ //指令 堆栈操作，堆栈有效
								$stack_used[$a][$c] = true;
							}
						}
					}

					//内存指针 位数 未确认，需要重新确认								
					if (false !== $mem_ptr_bits_recal){	
						foreach ($c_solid_bits as $z => $y){
							foreach ($mem_ptr_bits_recal as $v => $w){							
								if ($y == 9){
									$y = 8;
								}
								$StandardAsmResultArray[$a][$c]['p_bits'][$v] = $y;								
							}
							break;    
						}
					}

					//对 重定位 项 确认一下，看是否 属于 内存指针 (注：假设一条指令 最多 只 含 一个重定位数据)
					if ($c_reloc !== false){
						foreach ($StandardAsmResultArray[$a][$c]['params'] as $v => $w){
							if (false !== strpos ($w,$c_reloc)){
								$c_rel = explode ('_',$c_reloc);
								$StandardAsmResultArray[$a][$c]['rel'][$v]['i'] = $c_rel[3];  
								$StandardAsmResultArray[$a][$c]['rel'][$v]['c'] = $c_rel[4];  
								if ($StandardAsmResultArray[$a][$c]['p_type'][$v] === 'm'){								
									$garble_rel_info[$c_rel[2]][$c_rel[3]][$c_rel[4]]['isMem'] = true;
									break;
								} 	
							}
						}					
					}
					//统计当前指令 参数 对 通用/标志寄存器 / 内存地址指针 的影响 

					if (true === $param_include_r_m){
						foreach ($StandardAsmResultArray[$a][$c]['params'] as $z => $y){
							if (!$c_instruction[$z]){ //指令集 参数 数组中不存在，丢弃节表
								GeneralFunc::LogInsert($language['section_name']." ".$myTables['CodeSectionArray'][$a]['name'].$language['section_number']." $a ".$language['total_linenumber']." $c , ". $language['nasm_param_not_found'].$StandardAsmResultArray[$a][$c]['operation'].' '.$language['nasm_pnf_param_number'].$z.' , '.$language['giveup_c_section'],2);							
								$break = true;
								break;
								//$c_instruction[$z] = 3;
							}
							if ('r' === $StandardAsmResultArray[$a][$c]['p_type'][$z]){ //寄存器	
								$cri = Instruction::getGeneralRegIndex($y);
								$normal_register_opt_array[$a][$c][$cri][$StandardAsmResultArray[$a][$c]['p_bits'][$z]] |= $c_instruction[$z];								
								if ($c_instruction[$z] > 1){
									if ($StandardAsmResultArray[$a][$c]['params'][$z] === 'ESP'){ //参数 ESP 操作，堆栈有效性 中断
										$stack_broke[$a][$c] = true;
									}
								}

							}elseif ('m' === $StandardAsmResultArray[$a][$c]['p_type'][$z]){ //内存指针
								$c_valid_mem_opt_array = array(); //临时
								$c_valid_mem_opt_array['code'] = $y;
								/*
								if (preg_match('/\[([^\(]*)\]/',$y,$tmp_filter)){                   //获取 干净的内存指针(去掉前缀)
									$c_valid_mem_opt_array['code'] = $tmp_filter[0];  
									$StandardAsmResultArray[$a][$c]['params'][$z] = $tmp_filter[0];  
								}else{
									$output['warning'][] = $language['section_name']." ".$myTables['CodeSectionArray'][$a]['name'].$language['section_number']." $a ".$language['total_linenumber']." $c , ". $language['illega_mem_param'].$y.' , '.$language['giveup_c_section'];							
									$break = true;
									break;
								}*/
								$c_valid_mem_opt_array['opt']  = $c_instruction[$z];
								$c_valid_mem_opt_array['bits'] = $StandardAsmResultArray[$a][$c]['p_bits'][$z];
								//先判断内存指针中是否 含 寄存器，如是，则记为 读
								if (preg_match_all('/'."$pattern_regs".'/',$y,$tmp)){
									foreach ($tmp[0] as $w => $v){
										$cri = Instruction::getGeneralRegIndex($v);
										$StandardAsmResultArray[$a][$c]['p_m_reg'][$z][$cri] = 1;
										$crb = Instruction::getGeneralRegBits($v);
										$normal_register_opt_array[$a][$c][$cri][$crb] |= 1;
										$c_valid_mem_opt_array['reg'][] = $v;
									}
								}    
								$valid_mem_opt_array[$a][$c][] = $c_valid_mem_opt_array;
							}
						}				
					}

					if ($break){ //出错，放弃当前节表处理
						break;
					}

					//对整数 作 去除前缀定义处理 (避免 以后多态时出错 byte 0x2 + 12345678 compile -> byte 0x2)
					//对内存地址去除前缀定义处理 (方便合并 相同内存地址)
					if (isset($StandardAsmResultArray[$a][$c]['p_type'])){
						foreach ($StandardAsmResultArray[$a][$c]['p_type'] as $z => $y){
							if ('i' == $y){
								if (32 == $StandardAsmResultArray[$a][$c]['p_bits'][$z])
									$StandardAsmResultArray[$a][$c]['params'][$z] = str_replace ('DWORD','',$StandardAsmResultArray[$a][$c]['params'][$z]);
								if (16 == $StandardAsmResultArray[$a][$c]['p_bits'][$z])
									$StandardAsmResultArray[$a][$c]['params'][$z] = str_replace ( 'WORD','',$StandardAsmResultArray[$a][$c]['params'][$z]);
								if (8  == $StandardAsmResultArray[$a][$c]['p_bits'][$z])
									$StandardAsmResultArray[$a][$c]['params'][$z] = str_replace ( 'BYTE','',$StandardAsmResultArray[$a][$c]['params'][$z]);
							}
						}
					}
					//LEA 指令特殊处理，第二参数不作为有效内存处理，仅将其内含寄存器(如有的话)，作为读操作
					//if ('LEA' == $StandardAsmResultArray[$a][$c]['operation']){
					//    var_dump ($StandardAsmResultArray[$a][$c]);				
					//}
					//XOR 指令特殊处理，当参数 相同时 ，参数不再为读写，而是只写入
					if ('XOR' == $StandardAsmResultArray[$a][$c]['operation']){
						if ($StandardAsmResultArray[$a][$c]['params'][0] === $StandardAsmResultArray[$a][$c]['params'][1]){
							if (Instruction::getGeneralRegIndex($StandardAsmResultArray[$a][$c]['params'][0])){
								$c_bits = $StandardAsmResultArray[$a][$c]['p_bits'][0];
								$c_reg  = Instruction::getGeneralRegIndex($StandardAsmResultArray[$a][$c]['params'][0]);
								$normal_register_opt_array[$a][$c][$c_reg][$c_bits] = 2;    
							}     
						}
					}
				}			
			}
			if ($break){
				unset ($StandardAsmResultArray[$a]);
				unset ($myTables['CodeSectionArray'][$a]);
			}

		}    
	}


	///////////////////////////////////////////////////////////////////////////////////////////////
	//检查二个 16进制数是否 为互相NEG 或 相同
	//参数 1: '0X72F7DBAC'  2: '8D082454'
	//比较方法：先去掉参数1前面2字节,然后，从后面取四个数字，转为10进制比较
	//                                     再取前面四个数字，转为10进制比较 (位数不足，用0补足)
	//相等则返回 1 ，相NEG则 返回2 ，否则返回 0
	private static function is_equal_or_neg($a,$b){
		$a = substr($a,2,strlen($a));
		$a = str_pad($a,8,'0',STR_PAD_LEFT);
		$b = str_pad($b,8,'0',STR_PAD_LEFT); 
		if ($a === $b){  
			return 1;
		}	
		$a_low  = hexdec(substr ($a,4,8));
		$b_low  = hexdec(substr ($b,4,8));    
		$a_high = hexdec(substr ($a,0,4));
		$b_high = hexdec(substr ($b,0,4));	
		if (($a_low + $b_low == 65536)&&($a_high + $b_high == 65535)){
			return 2;
		}    
		return 0;
	}


	////////////////////////////////////////////
	//对重定位 目标 进行标号/define 变量名替换
	//
	public static function rel_label_replacer(&$myTables,&$AsmResultArray,$LineNum_Code2Reloc,$language){
		global $UniqueHead;
		foreach ($LineNum_Code2Reloc as $a => $b){
			//echo "<br>******** $a *************<br>";
			foreach ($b as $c => $d){
				foreach ($d as $e => $f){ //只取第一个，一个指令不可能对于多个重定位，生成数组的函数已判断
					break;
				}
				$tmp_asm = preg_split("/(0X[0-9A-F]{1,8})/",$AsmResultArray[$a][$c]['asm'],-1,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
				for ($i = count($tmp_asm) - 1;$i > 0 ; $i--){            //从最后开始往 前找 ~
					if (preg_match("/^0X[0-9A-F]{1,8}$/",$tmp_asm[$i])){
						$f --;                                           //
						if (0 == $f)                                     // 根据标记，重定位可能不在末尾，见readme.reloc.txt中描述
							break;                                       // 
					}
				}		
				 
				if ($i == 0){ //未找到 数据 ？不可能 。。。丢弃对此节的处理
					GeneralFunc::LogInsert($language['section_name']." ".$myTables['CodeSectionArray'][$a]['name'].$language['section_number']." $a ".$language['reloc_rva']." ".$myTables['RelocArray'][$a][$e]['VirtualAddress']." ".$language['cant_loc_value_or_label'],2);
					unset ($myTables['CodeSectionArray'][$a]);
					break;
				}else{
					
					//ndisasm 反汇编时，整数做有符号处理，如：机器码 008B5424088D 反汇编为 add [ebx-0x72f7dbac],cl 而 不是 add [ebx+8D082454], cl
					//所以需要做 比较 是否 2数 为互 neg
					if (($tmp_asm[$i] === '0X'.$myTables['RelocArray'][$a][$e]['value']) || (self::is_equal_or_neg($tmp_asm[$i],$myTables['RelocArray'][$a][$e]['value']))){
						//echo "<br>$a $e  $jj ".$tmp_asm[$i]." === '0X'.".$myTables['RelocArray'][$a][$e]['value']."<br>";
						/*
						if ($a == 317){						
							echo "<br>$e  $jj ".$tmp_asm[$i]." === '0X'.".$myTables['RelocArray'][$a][$e]['value'];
							exit;
						}*/
					}else{    //标号前DWORD去除,jmp  dword strict xxx 【ok】
							  //                call dword strict xxx 【ok】
							  //                jcc  dword strict xxx 【fail】
							  //						
						$tmp_asm[0] = str_replace('DWORD','',$tmp_asm[0]);
						$myTables['RelocArray'][$a][$e]['isLabel'] = true;
					}
					$tmp_asm[$i] = "$UniqueHead".'RELINFO_'."$a".'_'."$e".'_0';

					$AsmResultArray[$a][$c]['asm'] = '';
					/*
					if ($myTables['RelocArray'][$a][$e]['isLabel']){
						$AsmResultArray[$a][$c]['asm'] = '<font color =red>';
					}else{
						$AsmResultArray[$a][$c]['asm'] = '<font color =blue>';
					}*/
					foreach ($tmp_asm as $z => $y){
						$AsmResultArray[$a][$c]['asm'] .= $y;
					}
					//$AsmResultArray[$a][$c]['asm'] .= '</font>';
					
				}				    
			}		
		}
	}


	///////////////////////////////////////////
	//为所有eip跳转指令(重定位的都设跳转目标为下一指令) 定位 以后添加 Label 的位置信息
	//                  跳转指令 后的 dword 去掉
	//替换  eip跳转指令 后的常数为 Label
	//
	//btw: 过滤 每个段中 末尾用来填充 对齐 的 nop
	//
	public static function eip_label_replacer($AsmLastSec,&$solid_jmp_array,&$solid_jmp_to,&$myTables,&$AsmResultArray,$LineNum_Code2Reloc,$language){
		//后面 跟 跳转 目的 标号的 跳转指令

		global $UniqueHead;
		global $user_option;

		foreach ($myTables['CodeSectionArray'] as $a => $b){
			//echo "<br><br>*********** $a ********<br>";
			$filt_nop_ptr = false;
			foreach ($AsmResultArray[$a] as $c => $d){
				if ($AsmResultArray[$a][$c]['asm'] == 'NOP'){
					if ($filt_nop_ptr == false){
						$filt_nop_ptr = $c;
					}
				}else{
					$filt_nop_ptr = false;
				}
				if (!$LineNum_Code2Reloc[$a][$c]){ //没有重定位
					$tmp = explode (' ',$d['asm']);
					if (Instruction::isEipInst($tmp[0])){  //不考虑 前缀 指令 * 跳转指令前面 有可能有前缀吗？ 不可能()
														   //                   体现在反汇编级的前缀只有 Lock / rep / repz
														   //Change DEFAULT operand size. (66)  不会体现在反汇编代码 前缀部分
														   //Change DEFAULT address size. (67)  不会体现在反汇编代码 前缀部分
														   //Repeat prefixes. (F2, F3)
														   //Segment override prefixes(change DEFAULT segment). (2E, 36, 3E, 26, 64, 65) 不会体现在反汇编代码 前缀部分
														   //LOCK prefix. (F0)
						$f = count ($tmp) - 1;
						if ($tmp[$f - 1] == 'DWORD'){
							unset ($tmp[$f - 1]);
						}
						if ($tmp[$f - 1] == 'SHORT'){
							unset ($tmp[$f - 1]);
						}
						if (preg_match("/^0X[0-9A-F]{1,8}$/",$tmp[$f])){ //最后一个单位是 目标行
							$i = hexdec($tmp[$f]);
							//判断 目标 行在 同一节 内 // 
							if ((!$AsmResultArray[$a][$i])&&(!$AsmLastSec[$a][$i])){
								GeneralFunc::LogInsert($language['section_name']." ".$myTables['CodeSectionArray'][$a]['name'].$language['section_number']." $a ".$language['jmp_dest_out_rang'],2);
								unset ($myTables['CodeSectionArray'][$a]);
								break;
							}
							$tmp[$f] = "$UniqueHead".'SOLID_JMP_'."$i".'_FROM_'."$c"; //Label 号 "$UniqueHead"."solid_jmp_DEST_from_SOURCE":
							$solid_jmp_array[$a][$i][] = $tmp[$f].' : ';                 //标号 加个冒号
							$solid_jmp_to[$a][$c] = $i;
							$AsmResultArray[$a][$c]['asm'] = implode(' ',$tmp);
							//echo "<br>$<font color=red>$c: ".$AsmResultArray[$a][$c]['asm']."</font>";
							continue;
						}else{
							//echo "<br>$<font color=red><b>$c: ".$d['asm']."</b></font>";
							continue;				    
						}
					}
				}
			}
			if (true === $user_option['del_last_nop']){
				if ($filt_nop_ptr){ //过滤 每个段中 末尾用来填充 对齐 的 nop
					for ($z = $c;$z >= $filt_nop_ptr;$z--){
						unset ($AsmResultArray[$a][$z]);    
					}
				}
			}
		}
	}


	///////////////////////////////////////////
	//对 反汇编 结果 根据 段节表/重定位 数据
	//   进行 处理

	public static function sec_reloc_format(&$myTables,&$AsmResultArray,&$AsmLastSec,$language,&$LineNum_Code2Reloc,$DebugShow = false){

		$RelocArray       = $myTables['RelocArray'];
		//根据节表信息对汇编代码分段，结构：[节表编号][在disasm文件位置(总)] = array ('asm' => '汇编代码','bin' => '2进制代码','len' => '代码长度','rva' => '相对节表偏移');
		//                                            [...]
		//反编译时以节表开始位 定义了 同步点，这里不再 判断节表开始位是否 一致
		$c = 0;
		$tmp_AsmResultArray = $AsmResultArray;
		$AsmResultArray = array();
		foreach ($myTables['CodeSectionArray'] as $d => $e){
			$c += $e['SizeOfRawData'];	
			$rva = 0;
			foreach ($tmp_AsmResultArray as $a => $b){
				if ($a < $c){
					$b['rva'] = $rva;
					$AsmResultArray[$d][$a] = $b;
					$rva += $b['len'];
					unset ($tmp_AsmResultArray[$a]);
				}else{
					$AsmLastSec[$d][$a] = true; //末尾行号
					break;
				}			
			}			    
		}
		//最后一个节表 末尾行
		$a += $AsmResultArray[$d][$a]['len'];
		$AsmLastSec[$d][$a] = true; //末尾行号
		
		if ($DebugShow){
			echo "<br>*****<br>";
			$ccolor = "black";	
		}
		foreach ($myTables['CodeSectionArray'] as $a => $b){
			$lp_reloc = 1; //当前处理的 重定位 数组 key
			if ($DebugShow){
				echo "<font color = $ccolor><br><b>$a<b><br>";
			}
			foreach ($AsmResultArray[$a] as $c => $d){
				/****************判断重定位表是否符合规范，否则放弃对当前节表的处理*****************************/
				if (is_array($RelocArray[$a][$lp_reloc])){
					if  (($RelocArray[$a][$lp_reloc]['Type'] != 6) && ($RelocArray[$a][$lp_reloc]['Type'] != 20)){
						//目前只处理 ： IMAGE_REL_I386_DIR32 0x0006 重定位目标的32 位VA
						//              IMAGE_REL_I386_REL32 0x0014 重定位目标的32 位相对偏移。用于支持x86 的相对分支和CALL 指令。
						GeneralFunc::LogInsert($language['section_name']." ".$myTables['CodeSectionArray'][$a]['name'].$language['section_number']." $a ".$language['reloc_rva']." ".$RelocArray[$a][$lp_reloc]['VirtualAddress']." ".$language['disenable_rel_type'],2);
						unset ($myTables['CodeSectionArray'][$a]);
						break;						
					}
					if ($d['rva'] <= $RelocArray[$a][$lp_reloc]['VirtualAddress']){;
						if (($d['rva'] + $d['len']) >= $RelocArray[$a][$lp_reloc]['VirtualAddress'] + 4){
			
							$rel_start = $RelocArray[$a][$lp_reloc]['VirtualAddress'] - $d['rva'];
							$reloc_bin = substr ($d['bin'],$rel_start * 2,8);

							if ($reloc_bin !== '00000000'){
								$value = false;
								for ($i = 6;$i >= 0;$i-=2){
									$tmp = substr($reloc_bin,$i,2);     
									if ($value === false){
										if ($tmp[0] == '0'){
											if ($tmp[1] == '0'){
												continue;
											}else{
												$tmp = $tmp[1];
											}
										}	
									}						
									$value .= $tmp;
								}
								$myTables['RelocArray'][$a][$lp_reloc]['value'] = strtoupper($value);//strtolower($value);								
								if (!preg_match("/^0X[0-9A-F]{1,8}$/",'0X'.$myTables['RelocArray'][$a][$lp_reloc]['value'])){ //未正确转换，出错了
									GeneralFunc::LogInsert($language['section_name']." ".$myTables['CodeSectionArray'][$a]['name'].$language['section_number']." $a ".$language['reloc_rva']." ".$RelocArray[$a][$lp_reloc]['VirtualAddress']." ".$language['one_asm_more_reloc'],2);
									unset ($myTables['CodeSectionArray'][$a]);
									break;
								}
								if ($RelocArray[$a][$lp_reloc]['Type'] == 20){
									//用于跳转指令的重定位 初始值 如果不为 00000000? 【跳转目标】多态后可能出错 | 警告之
									GeneralFunc::LogInsert($language['section_name']." ".$myTables['CodeSectionArray'][$a]['name'].$language['section_number']." $a ".$language['reloc_rva']." ".$RelocArray[$a][$lp_reloc]['VirtualAddress']." ".$language['rel32_not_null'],3);
								}
							}else{
								$myTables['RelocArray'][$a][$lp_reloc]['value'] = '0';						
							}
							if ($DebugShow){
								echo "<br> $c [".$d['rva']."]{".$d['len']."} : $reloc_bin ".$d['asm'];
								echo "<br><<<----------------------------";
							}
							if ($LineNum_Code2Reloc[$a][$c]){ //一条指令 对应 多个重定位，放弃处理
								GeneralFunc::LogInsert($language['section_name']." ".$myTables['CodeSectionArray'][$a]['name'].$language['section_number']." $a ".$language['reloc_rva']." ".$RelocArray[$a][$lp_reloc]['VirtualAddress']." ".$language['one_asm_more_reloc'],2);
								unset ($myTables['CodeSectionArray'][$a]);
								break;
							} 						
							if (($d['rva'] + $d['len']) == $RelocArray[$a][$lp_reloc]['VirtualAddress'] + 4){ //重定位在最后
								$LineNum_Code2Reloc[$a][$c][$lp_reloc] = 1;						
							}else{                                                                            //重定位不在最后，后面还有一个imm	
								$LineNum_Code2Reloc[$a][$c][$lp_reloc] = 2;
							}
							$lp_reloc ++;
						}
						
					}else{
						//重定位偏移 小于 当前处理 偏移，出错~ 1:重定位表未按序排列
						//                                     2:上一个长度不够，一般是因为反汇编错误，代码/数据混合。如: 0: 0000 add [eax],al  ;<- 重定位指针=0，长度为4
						//                                                                                                2: 0000
						//                                     3:
						GeneralFunc::LogInsert($language['section_name']." ".$myTables['CodeSectionArray'][$a]['name'].$language['section_number']." $a ".$language['reloc_rva']." ".$RelocArray[$a][$lp_reloc]['VirtualAddress']." ".$language['rva_not_align'],2);
						unset ($myTables['CodeSectionArray'][$a]);
						break;
						//echo "<br>!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!".$RelocArray[$a][$lp_reloc]['VirtualAddress']."<br>";
						//$lp_reloc ++;
					}
				}
				/**********************************************************************************************/
			}
			if ($DebugShow){
				if ($ccolor == "black"){
					$ccolor = "red";
				}else{
					$ccolor = "black";
				}
			}
		}



		//var_dump ($RelocArray);
		return;	

	}

	///////////////////////////////////////////
	//对反汇编 结果文件进行 分析
	//根据节表信息 进行 分段

	public static function format_disasm_file($asm_file,$bin_filesize,&$AsmResultArray,$language){

		if (!($file = fopen("$asm_file", "r"))){
			GeneralFunc::LogInsert($language['open_asm_file_fail']);
			return false;
		} 

		$total_bin_size = 0;
		$AsmResultArray = array();
		$line = 0;
		while(!feof($file)){
			$line ++;
			$c = fgets($file);
			$c = trim($c);
			$address  = strtok($c," ");

			if ('-' === $address[0]){
				$binary = trim($address);			
				$binary = substr($binary,1,strlen($binary) - 1);
				$AsmResultArray[$prev_address]['bin'] .= $binary;
				$a = strlen($binary)/2;
				$AsmResultArray[$prev_address]['len'] += $a;
				$total_bin_size += $a;

				if (!self::is_binStruction ($binary)){ // 获得2进制代码非法
					GeneralFunc::LogInsert($language['illegal_binary'].',line:'."$line");
					break;
				}

				continue;
			}elseif(8 != strlen($address)){
				continue;
			}

			$cc = explode(' ',$c);
			$address  = hexdec($cc[0]);
			$binary   = trim($cc[2]);
			$i = 3;
			$assemble = "";
			while (isset($cc[$i])){
				$assemble .= $cc[$i]." ";
				$i ++;
			}
			$assemble = trim($assemble);
			$a = strlen($binary)/2;
			$total_bin_size += $a;
			$AsmResultArray[$address]['len'] = $a ;
			if (!self::is_binStruction ($binary)){ // 获得2进制代码非法
				GeneralFunc::LogInsert($language['illegal_binary'].',line : '."$line");
				break;
			}
			$AsmResultArray[$address]['asm'] = strtoupper($assemble);
			$AsmResultArray[$address]['bin'] = $binary;
			//$AsmResultArray[$address]['lsize'] += $total_bin_size;
			$prev_address = $address;
		}
		fclose($file);
			
		if ($bin_filesize != $total_bin_size){
			//echo "$bin_filesize != $total_bin_size<br>";
			GeneralFunc::LogInsert($language['result_bin_not_same_size']);
		}
		if (GeneralFunc::LogHasErr()){
			return false;
		}	
		return true;
	}

	///////////////////////////////////////////
	////合法的binary指令字符串
	private static function is_binStruction ($bin){
		if (strlen($bin)%2){
			return false;
		}
		return preg_match("/^[0-9A-F]{2,16}$/",$bin);
	}


	///////////////////////////////////////////
	////收集所有目标段内容，写入到一个文件中
	////返回反汇编后文件体积，或返回false 失败
	////
	////注：$Synchronisation 可能过长，多次循环进行 readme 2013/04/19
	////语法 ndisasm -o123 -s124 c:\xxxx.bin
	////             -o123 起始偏移设置为123                                 
	////             -s124 124bit 为 段 界限符 (从偏移设置开始计算，此例即为相对于开头的01byte偏移)
	////
	public static function collect_and_disasm($bin_file,$asm_file,$disasm,$CodeSectionArray,$buff,&$bin_filesize,$protect_sec,&$p_sec_abs,$language,$DebugShow = false){
		global $ARG_MAX;
		global $max_input; //限制的最大行数

		$output_line_number = 0 ; //生成的汇编文件总行数

		$Syn_Max = $ARG_MAX - strlen ("$disasm -b 32 "."$bin_file >> $asm_file");
		
		//删除原文件
		if(file_exists($bin_file)){
			if (!unlink($bin_file)){
				GeneralFunc::LogInsert($language['obj_filename_del_fail']);
				return false;	
			}
		}
		if(file_exists($asm_file)){
			if (!unlink($asm_file)){
				GeneralFunc::LogInsert($language['obj_filename_del_fail']);
				return false;
			}
		}
		
		$next_orgin = 0;   //下一个编译起始位
		$orgin = 0;
		$write_to = '';
		$tmp_Synchronisation = 0; //对每个段开头做一次定位，防止上一个段末尾数据扰乱了下一个
		$Synchronisation = "";
		
		foreach ($CodeSectionArray as $a => $b){
		
			$write_to .= substr($buff,$b['PointerToRawData'],$b['SizeOfRawData']);	
			
			if (isset($protect_sec[$a])){ //有保护定义,可能是数据，放置 手工同步项
				foreach ($protect_sec[$a] as $z => $y){
					$Synchronisation .= "-s";
					$Synchronisation .= $tmp_Synchronisation + $z;  
					$Synchronisation .= ' ';
					$Synchronisation .= "-s";
					$Synchronisation .= $tmp_Synchronisation + $z + $y;
					$Synchronisation .= ' ';
					$p_sec_abs[$tmp_Synchronisation + $z] = $tmp_Synchronisation + $z + $y; //保护区域(绝对 [开始] => 结束)(反汇编代码行号)
				}
			}

			$tmp_Synchronisation += $b['SizeOfRawData']; 
			
			//echo "<br> ".$b['SizeOfRawData'];
			$next_orgin += $b['SizeOfRawData'];
			//if (strlen($Synchronisation) > 0 ){  
			//if (true){  //测试 逐个 节表 disasm
			if (strlen($Synchronisation) > $Syn_Max - 500 ){ //留500个字符 做后备 (单个Synchronisation 最长不过 -s4294967295)
															 //
				if (!self::disasm_to_file($bin_file,$write_to,$bin_filesize,$Syn_Max,$asm_file,$language,$max_input,$disasm,$orgin,$Synchronisation)){
					return false;
				}

				$orgin = $next_orgin;
				$write_to = '';			
				$Synchronisation = "";		
			}else{
				$Synchronisation .= "-s"."$tmp_Synchronisation ";
			}
			//exit;

		}	

		if (strlen($Synchronisation)){
			if (!self::disasm_to_file($bin_file,$write_to,$bin_filesize,$Syn_Max,$asm_file,$language,$max_input,$disasm,$orgin,$Synchronisation)){
				return false;
			}
		}


		if ($DebugShow){
			echo "$bin_filesize <br>";
			echo "$disasm_query <br>";
		}
		//var_dump ($output_line_number);
		return filesize($asm_file);
	}


	private static function disasm_to_file($bin_file,$write_to,&$bin_filesize,$Syn_Max,$asm_file,$language,$max_input,$disasm,$orgin,$Synchronisation){

		$bin_filesize += file_put_contents ($bin_file,$write_to);
		$disasm_query = "$disasm -b 32 ".'-o'."$orgin "."$Synchronisation"."$bin_file >> $asm_file";

		echo ("<br> $disasm_query");
		if (strlen($disasm_query) > $Syn_Max){       //命令行长度超过最大限制
			GeneralFunc::LogInsert($language['too_many_arg']);//." $Syn_Max";
			return false;
		}
		exec ("$disasm_query");
		// 检查输出文件行数超限制
		if (false !== $max_input){
			$output_line_number = GeneralFunc::get_file_line($asm_file);
			if ($output_line_number > $max_input){
				GeneralFunc::LogInsert($language['too_big_input_01'].$output_line_number.$language['too_big_input_02'].$max_input.$language['too_big_input_03']);
				return false;
			}
		}
		return true;
	}


	//对隔断代码(如 call ,ret 等)的后方保护，再处理 根据: 如果后面还有单位，则复制后单位的前保护；
	//                                                    如果后面没有单位，则去掉所有soul_usable ,soul_forbid
	//                                                                                            
	public static function redeal_split_opt($StandardAsmResultArray,$exec_thread_list,&$soul_forbid,&$soul_usable){	
		foreach ($exec_thread_list as $sec => $c_exec_thread_list){
			$split = array();
			foreach ($c_exec_thread_list as $a => $b){
				$z = end($b);
				$split[$z] = true;
			}
			$need = false;
			foreach ($StandardAsmResultArray[$sec] as $line => $a){
				if (false !== $need){ //上一个是split opt，赋值		
					unset ($soul_forbid[$sec][$need]['n']);
					$soul_forbid[$sec][$need]['n'] = $soul_forbid[$sec][$line]['p']; 
					unset ($soul_usable[$sec][$need]['n']);				
					$soul_usable[$sec][$need]['n'] = $soul_usable[$sec][$line]['p'];
					$need = false;
				}
				if (isset($split[$line])){ //遇到split opt
					$need = $line;    
				}
			}
			if (false !== $need){ //末尾也是个split opt
				if (true === $soul_usable[$sec][$need]['n']['stack']){
					$use_stack = true;
				}
				unset ($soul_forbid[$sec][$need]['n']);
				unset ($soul_usable[$sec][$need]['n']);
				if (true === $use_stack){
					$soul_usable[$sec][$need]['n']['stack'] = true;
				}
			}
		}

		return;
	}

	////////////////////////////////////////////////////////////////////////////////
	//
	//
	public static function scan_affiliate_usable (&$soul_usable,&$soul_forbid){
		global $all_valid_mem_opt_index;
		
		//$ret = array();
		$tmp = $soul_usable;
		foreach ($tmp as $sec => $a){	    
			foreach ($a as $line => $b){
				//echo "<br>line $line: ";
				if (is_array($b['p']['mem_opt_able'])){
					//if (is_array($b['p']['normal_write_able'])){
						foreach ($b['p']['mem_opt_able'] as $mem_index){
							if (isset($all_valid_mem_opt_index[$mem_index]['reg'])){
								foreach ($all_valid_mem_opt_index[$mem_index]['reg'] as $reg){								
									if (isset($b['p']['normal_write_able'][$reg])){
										unset ($soul_usable[$sec][$line]['p']['normal_write_able'][$reg]);
									}
									$soul_forbid[$sec][$line]['p']['normal'][$reg][32] = true;								
									//    $ret[$sec][$line]['p'][$reg][] = $mem_index;
								}
							}
						}
					//}
				}
				if (is_array($b['n']['mem_opt_able'])){
					//if (is_array($b['n']['normal_write_able'])){
						foreach ($b['n']['mem_opt_able'] as $mem_index){
							if (isset($all_valid_mem_opt_index[$mem_index]['reg'])){
								foreach ($all_valid_mem_opt_index[$mem_index]['reg'] as $reg){
									if (isset($b['n']['normal_write_able'][$reg])){
										unset ($soul_usable[$sec][$line]['n']['normal_write_able'][$reg]);
										//$ret[$sec][$line]['n'][$reg][] = $mem_index;
									}
									$soul_forbid[$sec][$line]['n']['normal'][$reg][32] = true;
								}
							}
						}
					//}
				}
			}
		}
		return true;//$ret;

	}
	////////////////////////////////////////////////////////////////////////////////
	//
	//
	public static function parser_rel_usable_mem (&$all_valid_mem_opt_index){
		
		global $pattern_reloc;

		$tmp = $all_valid_mem_opt_index;
		foreach ($tmp as $a => $b){
			if (preg_match($pattern_reloc,$b['code'],$z)){
				$z = explode ('_',$z[0]);
				$all_valid_mem_opt_index[$a]['rel'] = $z[3];
			}	
		}
	}


	////////////////////////////////////////////////////////////////////////////////
	//生成 $soul_writein_Dlinked_List 代码顺序 双向 链表
	//
	public static function add_soul_writein_Dlinked_List(&$soul_writein_Dlinked_List,&$num,&$prev,$label,$asm,$ia = false){
		global $label_index;
		global $sec;
		global $soul_usable;
		global $soul_forbid;

		global $AsmResultArray;

		if (false !== $prev){
			$soul_writein_Dlinked_List[$num]['p']  = $prev;
			$soul_writein_Dlinked_List[$prev]['n'] = $num;
		}
		if (false !== $label){
			$soul_writein_Dlinked_List[$num]['label'] = $label;
			//$soul_writein_Dlinked_List[$num]['ipsp'] = true; //label 默认影响ipsp，无需设置
			if (true === $ia){ 
				if (isset($soul_usable[$sec][$asm]['n'])){ //(继承 目标 指令的 next_usable)
					$soul_usable[$sec][$label_index]['p'] = $soul_usable[$sec][$asm]['n'];		
					$soul_usable[$sec][$label_index]['n'] = $soul_usable[$sec][$asm]['n'];
				}
				if (isset($soul_forbid[$sec][$asm]['n'])){ //(继承 目标 指令的 next_forbid)
					$soul_forbid[$sec][$label_index]['p'] = $soul_forbid[$sec][$asm]['n'];	
					$soul_forbid[$sec][$label_index]['n'] = $soul_forbid[$sec][$asm]['n'];	
				}
			}else{ 
				if (isset($soul_usable[$sec][$asm]['p'])){ //(继承 目标 指令的 prev_usable)
					$soul_usable[$sec][$label_index]['p'] = $soul_usable[$sec][$asm]['p'];		
					$soul_usable[$sec][$label_index]['n'] = $soul_usable[$sec][$asm]['p'];	
				}
				if (isset($soul_forbid[$sec][$asm]['p'])){ //(继承 目标 指令的 prev_forbid)
					$soul_forbid[$sec][$label_index]['p'] = $soul_forbid[$sec][$asm]['p'];	
					$soul_forbid[$sec][$label_index]['n'] = $soul_forbid[$sec][$asm]['p'];	
				}
			}
			$soul_writein_Dlinked_List[$num]['c'] = $label_index;
			$soul_writein_Dlinked_List[$num]['len'] = 0; //标号长度为0 byte
			$label_index --;
		}else{
			global $c_Asm_Result;
			global $p_sec_abs;

			if (isset($p_sec_abs[$asm])){ //保护段，默认为 ip/sp
				$soul_writein_Dlinked_List[$num]['ipsp'] = true;
			}elseif (GeneralFunc::is_effect_ipsp($c_Asm_Result[$asm])){ //识别指令 是否影响 IP/SP
				$soul_writein_Dlinked_List[$num]['ipsp'] = true;
			}

			$soul_writein_Dlinked_List[$num]['c'] = $asm;
			
			$soul_writein_Dlinked_List[$num]['len'] = intval(strlen($AsmResultArray[$sec][$asm]['bin'])/2);		
		}
		
		$prev = $num;
		$num ++;
	}

	public static function generat_soul_writein_Dlinked_List(&$soul_writein_Dlinked_List,$a,$b,&$num,&$prev,$c_solid_jmp_array){	

		if (isset($c_solid_jmp_array[$a])){ //标号
			foreach ($c_solid_jmp_array[$a] as $z => $y){
				self::add_soul_writein_Dlinked_List($soul_writein_Dlinked_List,$num,$prev,$y,$a);				
			}
		}	
		
		self::add_soul_writein_Dlinked_List($soul_writein_Dlinked_List,$num,$prev,false,$a);

		return;
	}

}