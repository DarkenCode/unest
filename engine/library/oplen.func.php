
<?php

//涉及 计算 目标代码长度 的函数群

require dirname(__FILE__)."/../include/opcode_len_array.php";

class OpLen{

	//计算目标指令 长度(byte)
	//
	//$opt['prefix']    => array(...)      //前缀
	//    ['operation'] => string 'SUB'    //指令
	//    ['p_type']    =>                 //参数类型
	//                     0 => string 'r' (length=1)            // r 寄存器 i 常数 m 内存地址
	//                     1 => string 'i' (length=1)
	//    ['p_bits']    =>                 //参数位数
	//                     0 => int 32                           //8 16 32 /寄存器分8.9.16.32 (9为16位的高位,8为低位)
	//                     1 => int 32
	//    ['params']    =>                 //参数
	//                     0 => string 'ESP' (length=3)
	//                     1 => string '0X100' (length=5)
	//    ['range']     =>                 //当指令为rel_jmp时，且跨度已知  
	//                                                            跨度未知时 返回 最大可能长度
	//
	//$ignore_m : 忽略 mem 带来的长度影响 SIB ,imm 等...
	//
	//返回：>= 0 整数
	//     false 未知 指令 or 错误指令 (如: loop n ; n > 127)
	//
	//注:长度 可长不可短，长了没事，短了可能导致超过定长rel.jmp.range，编译出错 => 此问题不在这里判断
	//
	//注:所有['range']未知的rel.jmp，返回可能的最大长度(非false)
	//
	public static function code_len($opt,$ignore_m = false){

		global $range_limit_static_jmp;
		
		if (('CALL' === $opt['operation']) and ('i' === $opt['p_type'][0])){
			return 5;
		}

		if (('JMP' === $opt['operation']) and ('i' === $opt['p_type'][0])){
			if ((isset($opt['range'])) and ($opt['range'] <= 127)){
				return 2;
			}
			return 5;		
		}
	   
		if (isset($range_limit_static_jmp[$opt['operation']])){
			if ($range_limit_static_jmp[$opt['operation']] !== false){ //loop jecxz...
				if (($opt['range'] > $range_limit_static_jmp[$opt['operation']]) and (isset($opt['range']))){
					return false;
				}
				return 2;
			}
		}    

		global $opcode_len_arrays;
		global $opcode_len_result;
		

		$opcode_len = 0;
		if (isset($opt['prefix'])){
			$opcode_len = count($opt['prefix']);    
		}
		$p_number = count($opt['p_type']);

		global $my_cc;
		if (isset($my_cc[$opt['operation']])){ //XXXcc 条件指令
			if ('Jcc' === $my_cc[$opt['operation']]){ //简化计算，按最大
				if ((isset($opt['range'])) and ($opt['range'] <= 127)){
					return 2;
				}
				return 6;
			}
			$possible_arrays = $opcode_len_arrays[$my_cc[$opt['operation']]][$p_number];
			$possible_result = $opcode_len_result[$my_cc[$opt['operation']]][$p_number];
		}else{
			$possible_arrays = $opcode_len_arrays[$opt['operation']][$p_number];
			$possible_result = $opcode_len_result[$opt['operation']][$p_number];
		}
		$result = false; 
		$oplen = 0;
		if (!is_array($possible_arrays)){ //无匹配对象,指令?
			echo "<br>no match OP: $p_number";
			var_dump($opt);
		}else{
			if (0 == $p_number){
				$result = $possible_result;
			}else{	
				//内存 分析
				if (false === $ignore_m){
					global $all_valid_mem_opcode_len;
					foreach ($opt['p_type'] as $a => $b){
						if ('m' === $b){
							if (isset($all_valid_mem_opcode_len[$opt['params'][$a]])){
								$oplen = $all_valid_mem_opcode_len[$opt['params'][$a]];
							}else{
								$oplen = self::mem_addition_len($opt['params'][$a]);
							}
							break; //一个指令 仅有一个内存地址
						}				
					}				
				}
				//分析 $possible_array 各参数 type ,剔出不符的，保留可能符合的，如有绝对符合的，则跳出
				foreach ($possible_arrays as $number => $c_check){
					//echo "<br> c_check:";
					//var_dump ($c_check);
					$compare_ret = self::compare_types($c_check,$opt);
					if (1 === $compare_ret){               //可能符合，加入
						$result[] = $possible_result[$number];
						//echo "<br>return 1";
					}elseif (2 === $compare_ret){          //完全符合，直接返回
						$oplen += $possible_result[$number]; 
						return $oplen;
					}
				}
			}
			
			//从可能符合的长度中，选取最大长度作为
			//echo "<br> result:";
			//var_dump ($result);
			if (count($result) > 1){
				echo "<br>multi result:";
				var_dump ($result);
			}
			if ($result){
				$result = max($result);
				//echo "<br>sib:";
				//var_dump ($oplen);
				//var_dump ($result);
				$oplen += $result;
				return $oplen;
			}
			//max($result)
		}

		GeneralFunc::internal_log_save('unkown opcode : return 15 [func code_len] ',$opt);

		return 15; //对不能识别指令，默认返回最大长度
	}
	//比较指令 附加参数 是否合
	//符合 返回2  ； 可能符合 返回 1 ； 不符合返回0
	private static function compare_types($check,$opt){
		global $match_params;
		global $match_types;
		global $match_bits;

		$ret = 2;
		foreach ($check as $a => $b){
			if (isset($match_params[$b])){
				if (isset($match_params[$b][$opt['params'][$a]])){
					continue; //此参数完全符合，Next...
				}
			}

			if (isset($match_types[$b])){
				if (isset($match_types[$b][$opt['p_type'][$a]])){
					if (isset($match_bits[$b])){
						if (isset($match_bits[$b][$opt['p_bits'][$a]])){
							continue; //完全符合，Next...
						}
					}else{
						continue; //此参数完全符合，Next...
					}
				}
			}
			$ret = 0;
			break;
		}
		return $ret;
	}

	// 根据 内存参数 内容...
	//     计算 新增长度 (含 SIB)
	private static function mem_addition_len($mem){
		
		$ret = 0;
		if (false !== (strpos($mem,'S:'))){ //段寄存器
			$ret ++;
		}
		
		$ret += 5; //max : SIB+dword
		
		return $ret;
	}


	//检查跨度统计 (debuge时使用)
	public static function range_checker_4_debug(){    
		global $c_rel_jmp_range;



		$c = ConstructionDlinkedListOpt::readListFirstUnit();

		var_dump ($c_rel_jmp_range);
		if (is_array($c_rel_jmp_range)){
			foreach ($c_rel_jmp_range as $a => $b){
				$range = $c_rel_jmp_range[$a]['range'];
				
				unset ($c_rel_jmp_range[$a]['label']);
				$len = get_code_len ($a);

				if ($range !== $c_rel_jmp_range[$a]['range']){
					echo "<br> error: range not equal: $a : $range !== ".$c_rel_jmp_range[$a]['range'];
					var_dump ($c_rel_jmp_range[$a]);
					foreach ($c_rel_jmp_range[$a]['unit'] as $a => $b){
						echo "<br>$a: ";

					}
					exit ('exit from range_checker_4_debug');
				}else{
					echo "<br>range checked OK: $a $range = ".$c_rel_jmp_range[$a]['range'];
				}
			}	
		}	
	}
}

?>