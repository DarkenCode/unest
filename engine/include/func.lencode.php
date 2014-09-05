
<?php





























function code_len($opt,$ignore_m = false){

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
		if ($range_limit_static_jmp[$opt['operation']] !== false){ 
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
    if (isset($my_cc[$opt['operation']])){ 
	    if ('Jcc' === $my_cc[$opt['operation']]){ 
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
    if (!is_array($possible_arrays)){ 
		echo "<br>fuck: $p_number";
	    var_dump($opt);
	}else{
		if (0 == $p_number){
			$result = $possible_result;
		}else{	
			
			if (false === $ignore_m){
				global $all_valid_mem_opcode_len;
				foreach ($opt['p_type'] as $a => $b){
				    if ('m' === $b){
                        if (isset($all_valid_mem_opcode_len[$opt['params'][$a]])){
						    $oplen = $all_valid_mem_opcode_len[$opt['params'][$a]];
						}else{
							$oplen = mem_addition_len($opt['params'][$a]);
						}
						break; 
					}				
				}				
			}
			
			foreach ($possible_arrays as $number => $c_check){
				
				
				$compare_ret = compare_types($c_check,$opt);
				if (1 === $compare_ret){               
					$result[] = $possible_result[$number];
					
				}elseif (2 === $compare_ret){          
					$oplen += $possible_result[$number]; 
					return $oplen;
				}
			}
		}
		
		
		
		
		if (count($result) > 1){
			echo "<br>multi result:";
		    var_dump ($result);
		}
		if ($result){
			$result = max($result);
			
			
			
			$oplen += $result;
			return $oplen;
		}
		
	}

    internal_log_save('unkown opcode : return 15 [func code_len] ',$opt);

	return 15; 
}


function compare_types($check,$opt){
	global $match_params;
	global $match_types;
	global $match_bits;

    $ret = 2;
	foreach ($check as $a => $b){
		if (isset($match_params[$b])){
		    if (isset($match_params[$b][$opt['params'][$a]])){
			    continue; 
			}
		}

		if (isset($match_types[$b])){
			if (isset($match_types[$b][$opt['p_type'][$a]])){
			    if (isset($match_bits[$b])){
				    if (isset($match_bits[$b][$opt['p_bits'][$a]])){
					    continue; 
					}
				}else{
				    continue; 
				}
			}
		}
		$ret = 0;
		break;
	}
    return $ret;
}



function mem_addition_len($mem){
	
	$ret = 0;
	if (false !== (strpos($mem,'S:'))){ 
	    $ret ++;
	}
	
	$ret += 5; 
	
	return $ret;
}



function range_checker_4_debug(){    
    global $c_rel_jmp_range;
	global $soul_writein_Dlinked_List;
    global $soul_writein_Dlinked_List_start;

    $c = $soul_writein_Dlinked_List_start;

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
					var_dump ($soul_writein_Dlinked_List[$a]);
				}
				exit ('exit from range_checker_4_debug');
			}else{
				echo "<br>range checked OK: $a $range = ".$c_rel_jmp_range[$a]['range'];
			}
		}	
	}	
}

?>