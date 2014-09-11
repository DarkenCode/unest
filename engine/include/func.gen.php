<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}







function get_inst_define($opt,$para){
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




function get_unit_by_soul_writein_Dlinked_List($n){
    global $soul_writein_Dlinked_List;
	global $c_Asm_Result;
	global $c_soul_usable;
    global $meat_result_array;
	global $bone_result_array;
	global $poly_result_array;

	$ret = false;

	if (isset($soul_writein_Dlinked_List[$n]['poly'])){
	    $ret['code']   = $poly_result_array[$soul_writein_Dlinked_List[$n]['poly']]['code'][$soul_writein_Dlinked_List[$n]['c']];
		$ret['usable'] = $poly_result_array[$soul_writein_Dlinked_List[$n]['poly']]['usable'][$soul_writein_Dlinked_List[$n]['c']];
	}elseif (isset($soul_writein_Dlinked_List[$n]['bone'])){
        $ret['code']   = $bone_result_array[$soul_writein_Dlinked_List[$n]['bone']]['code'][$soul_writein_Dlinked_List[$n]['c']];
		$ret['usable'] = $bone_result_array[$soul_writein_Dlinked_List[$n]['bone']]['usable'][$soul_writein_Dlinked_List[$n]['c']];
	}elseif (isset($soul_writein_Dlinked_List[$n]['meat'])){
		$ret['code']   = $meat_result_array[$soul_writein_Dlinked_List[$n]['meat']]['code'][$soul_writein_Dlinked_List[$n]['c']];
		$ret['usable'] = $meat_result_array[$soul_writein_Dlinked_List[$n]['meat']]['usable'][$soul_writein_Dlinked_List[$n]['c']];	
	}else{		
		if (isset($c_Asm_Result[$soul_writein_Dlinked_List[$n]['c']])){
			$ret['code']   = $c_Asm_Result[$soul_writein_Dlinked_List[$n]['c']];
			$ret['usable'] = $c_soul_usable[$soul_writein_Dlinked_List[$n]['c']];
		}
	}
	return $ret;

}





function soul_stack_set(&$code,$usable){
	foreach ($code as $a => $b){
		if ((true !== $usable[$a]['p']['stack']) or (true !== $usable[$a]['n']['stack'])){
			$code[$a]['stack'] = false;
		}else{
			$code[$a]['stack'] = true;
		}		
	}
}





function bits_precision_adjust($bits){
    if ($bits <= 8){
	    $bits = 8;
	}else{
	    $bits = 32;
	}
    return $bits;
}




function get_bit_from_inter($value){
    if (!is_numeric($value)){ 
	    return false;
	}
	$a = intval($value);
	if ($a == $value){        
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



function is_32bit_hex($value){
    return preg_match("/^[0-9A-F]{1,8}$/",$value);
}




function internal_log_save($title,$contents=false){
    global $engin_version;

	$log_path = dirname(__FILE__)."/../../log/$engin_version/";

	if (!is_dir($log_path)){
	    if (!mkdir($log_path)){
		    error_log("fail to mkdir: $log_path",1,"1094566308@qq.com","From: internal_fail@unest.org");
			return false;
	    }
	}

    $log_file = $log_path."log.txt";
    
	if(!flock($fp=fopen($log_file,'a+'), LOCK_NB | LOCK_EX)){
	    return false;	
	}
    
	$header  = "\r\n\r\n";
	$header .= date("Y-m-d H:i:s",time());
    $header .= "\r\n";
	$header .= "[".$log_path."]";
    $header .= "\r\nTitle:".$title; 	
	if (false !== $contents){
		$header .= "\r\n";
		if (is_array($contents)){
			$header .= "===array start===\r\n";
			$header .= serialize($contents);
			$header .= "\r\n===array end===";
		}else{
			$header .= $contents;
		}
	}
	$header .= "\r\n --------- end ---------\r\n";


    fseek($fp,23);
    fwrite ($fp,$header,strlen($header));
    
	flock($fp,LOCK_UN);
    fclose($fp);
	
    return true;
}








function my_rand($n){
    
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








function get_file_line($filename){
	$line = 0;
	@$fp = fopen($filename , 'r');  
    if($fp){  
		
		while(stream_get_line($fp,8192,"\n")){  
		    $line++;  
		}
        fclose($fp);
		return $line;
	}
	return false;
}





function shutdown_except(){
    global $complete_finished;
	global $output;
	global $log_path;
	global $exetime_record;
    if ((!$complete_finished)&&(empty($output['error']))){
		$output['error'][] = 'unexpected shutdown, maximum execution time exceeded or other errors';
	}
    
	file_put_contents($log_path,json_encode($output));  

	var_dump ($output);
	var_dump ($exetime_record);
	echo "<br>memory_get_usage: ";
    var_dump (memory_get_usage());
}


 
 
 

 function get_params($argv){
 	$ret = false;
 
    if (is_array($_REQUEST)){
 	    $ret = $_REQUEST;
 	}	
    if (count($argv) > 1){
        parse_str($argv[1],$ret);
 	}
 	return $ret;
 }




function is_soul_unit($array){

    if (isset($array['soul'])){
	    return true;
	}

    if ((!isset($array['bone'])) and (!isset($array['meat'])) and (!isset($array['poly']))){
	    return true;
	}

	return false;
}




function collect_obj($insert_pointer,$number){
    global $soul_writein_Dlinked_List;

    $ret = false;

	$current = $insert_pointer;

    while (isset($soul_writein_Dlinked_List[$current]['302'])){ 
		$current =  $soul_writein_Dlinked_List[$current]['302'];		
	}

    
	
	global $c_MeatMutation;
	$soulfocus = my_rand($c_MeatMutation);
	if (($soulfocus) and (false === is_soul_unit($soul_writein_Dlinked_List[$current]))){

		$p = $current;
		$n = $current;	


		while (true){
            
			if (false !== $p){
				if (isset($soul_writein_Dlinked_List[$p]['p'])){
					$p = $soul_writein_Dlinked_List[$p]['p'];
                    
					
					
					if (is_soul_unit($soul_writein_Dlinked_List[$p])){
					    $current = $p;
						break;
					}
				}else{
					$p = false;
				}
			}

			if (false !== $n){
				if (isset($soul_writein_Dlinked_List[$n]['n'])){
					$n = $soul_writein_Dlinked_List[$n]['n'];
                    
					
					
					if (is_soul_unit($soul_writein_Dlinked_List[$n])){
					    $current = $n;
						break;
					}
				}else{
					$n = false;
				}
			}

			if ((false === $p) and (false === $n)){ 
			    break;
			}
		}	
	}
    



	

    $current_forward  = $current;
	$current_backward = $current;
    $have_forward  = true;
	$have_backward = true;
    
	$prev_obj = array();
	$next_obj = array();

	while ($number > 0){		
		$meat_generated = 0;
		
		if (true === $have_forward){			
			if (isset($soul_writein_Dlinked_List[$current_forward]['p'])){
				$current_forward = $soul_writein_Dlinked_List[$current_forward]['p'];
				$prev_obj[] = $current_forward;
				$number --;
			}else{				
				if (false === $have_backward){
				    break;
				}
				$have_forward = false;
			}
		}
		if (0 == $number){
		    break;
		}

        if (true === $have_backward){
			if (isset($soul_writein_Dlinked_List[$current_backward]['n'])){
				$current_backward = $soul_writein_Dlinked_List[$current_backward]['n'];
				$next_obj[] = $current_backward;
				$number --;
			}else{
				if (false === $have_forward){
				    break;
				}
				$have_backward = false;
			}
		}		
	}    
    
	$i = 1; 
	if (count($prev_obj) > 0){
		$prev_obj = array_reverse($prev_obj);
		foreach ($prev_obj as $a){
		    $ret[$i] = $a;
			$i ++;
		}		
	}
    $ret[$i] = $current;
	$i ++;
	if (count($next_obj) > 0){
		foreach ($next_obj as $a){
			$ret[$i] = $a;
			$i++;
		}
	}
    
    
	filter_fill_rel_jmp($ret,15);
    
    return $ret;
}







function filter_fill_rel_jmp(&$ret,$filter=15){
	global $c_rel_jmp_range;
	global $c_rel_jmp_pointer;

    
	$forbid_unit = false;
	
	
	if (is_array($c_rel_jmp_range)){
		foreach ($c_rel_jmp_range as $a => $b){
			if (false !== $b['max']){
				if ($b['max'] - $b['range'] < $filter){
					$forbid_unit[$a] = $a;
				}
			}
		}
		if ($forbid_unit){
			
			
			
			
			$usable_objs = false;
			$n = 0;
			$i = 1;
			foreach ($ret as $a => $b){
				
				
				if (isset($c_rel_jmp_pointer[$b])){
					foreach ($c_rel_jmp_pointer[$b] as $c => $d){
						if (isset($forbid_unit[$c])){
							 $n ++;
							 $i = 0;
							 break;
						}
					}
				}
				if ($i){
					$usable_objs[$n][$i] = $b; 
				}
				$i ++;			
			}
			
			
			$ret = array();
			if ($usable_objs){			
				foreach ($usable_objs as $a => $b){
					if (count($b) > count($ret)){
						$ret = $b;
					}
				}
			}
			
			
			   
			
		}
    

    
    
	
	
	
	

	}

    return;
}





function multi_level_rand($one,$two){
    $a = mt_rand(1,$one);
	$a = intval(ceil($two / $a));
	$b = mt_rand(1,$a);
	return $b;
}


function reset_ipsp_list_by_stack_pointer_define($sp_define,&$list,$soul){
	$ret = false;
	$tmp = $list;
    foreach ($tmp as $i => $a){
	    if ((true !== $a['ipsp']) && (!isset($a['label']))){
		    if (true === is_effect_ipsp($soul[$a['c']],1,$sp_define)){
			    $list[$i]['ipsp'] = true;
				$ret[$i] = true;
			}
		}
	}
	return $ret;
}








function is_effect_ipsp($asm,$rule = 1,$sp_define = false){
	global $Intel_instruction;
	global $con_abs_jmp;
	global $stack_pointer_reg;
	global $registersss;
	global $register_assort;
	
	if (isset($con_abs_jmp[$asm['operation']])){ 
	    return true;
	}

    $opt = $Intel_instruction[$asm['operation']];

	if ($opt['multi_op']){
		$i = count($asm['p_type']);
		$opt = $opt[$i];
	}

	if (isset($opt['STACK'])){
	    return true;
	}
	
	if (is_array($asm['params'])){ 
	    foreach ($asm['params'] as $a => $b){
			if ('i' !== $asm['p_type'][$a]){
				if ((0 === $rule) && ($opt[$a] <= 1)){
				    continue;
				}
				if ($opt[$a] < 1){ 
				    continue;
				}
				if ('r' === $asm['p_type'][$a]){
					if ($register_assort[$b] == $stack_pointer_reg){
						return true;
					}
				}
				if ((false !== $sp_define)&&('m' === $asm['p_type'][$a])){
					if (preg_match('/'."$sp_define".'/',$b)){						
						return true;
					}
				}
		    }
		}
	}
	return false;
}






function rand_interger($bits = false){

    $usable_bits = array(4 => true,8 => true,16 => true,32 => true);

    $ret = 1;
    
	$tmp = $usable_bits;

	$new_ret['value'] = 1;
	$new_ret['bits']  = 4;
		
	if (false !== $bits){ 
	    foreach ($tmp as $a => $b){
		    if ($a > $bits){
			    unset ($usable_bits[$a]);    
			}
		}
	}   

	if (count($usable_bits)){
	    $bits = array_rand($usable_bits); 		
	
		
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



function reloc_inc_copy_naked($index,$copy = 0){
    global $c_rel_info;
    $copy ++;
    while (isset($c_rel_info[$index][$copy])){
		$copy ++; 
	}
	return $copy;    
}

?>