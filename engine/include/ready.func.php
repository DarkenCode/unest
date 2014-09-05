<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}






function exetime_record(&$stime){
	

	$etime=microtime(true);
	$total=$etime-$stime;   
	$str_total = var_export($total, TRUE);  
	if(substr_count($str_total,"E")){  
		$float_total = floatval(substr($str_total,5));  
		$total = $float_total/100000;  				
	}
    $stime = microtime(true); 
	return $total;
	

}



function del_blank_array($a){
    if (empty($a)){
	    return false;
	}
	return true;
}






function compress_same_char_output($soul_usable,&$all_valid_mem_opt_index){
	$all_valid_mem_opt_record  = array(); 
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







function do_merge_same_loc_mem($b){
    $ret = $b;
	foreach ($b as $code => $c){
		unset ($c['reg']);
		if (1 == count($c)){ 
			continue;
		}
		if ($c[32] > 1){     
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
function merge_same_loc_mem($z){
    $ret = array();
	foreach ($z as $a => $b){
	    if (isset ($b['prev'])){
		    $ret[$a]['prev'] = do_merge_same_loc_mem($b['prev']);			
		}
		if (isset ($b['next'])){
		    $ret[$a]['next'] = do_merge_same_loc_mem($b['next']);			
		}
	}
    return $ret;
}









function combine_valid_mem_array($c_exec_list,$c_valid_mem_opt_tmp){
    $ret = array();
	
	foreach ($c_exec_list as $a => $b){	    
		foreach ($b as $c => $d){
			if ('-' === $d)
				continue;
			if (!$history_prev[$d]){  
			    $history_prev[$d] = true;
				$ret[$d]['prev'] = $c_valid_mem_opt_tmp[$a][$d]['prev'];
			}else{               
			    if ((!is_array($ret[$d]['prev'])) || (!is_array($c_valid_mem_opt_tmp[$a][$d]['prev']))){
				    unset ($ret[$d]['prev']);
				}else{
					if ($ret[$d]['prev'] == $c_valid_mem_opt_tmp[$a][$d]['prev']){
					
					}else{			
						
						$ret[$d]['prev'] = merge_available_mem_array ($ret[$d]['prev'],$c_valid_mem_opt_tmp[$a][$d]['prev']);
					}
				}
			}

            if ('-' === $b[$c + 1]){ 
				    continue;
			}
            if (!$history_next[$d]){  
			    
			    $history_next[$d] = true;
				$ret[$d]['next'] = $c_valid_mem_opt_tmp[$a][$d]['next'];				
			}else{               
				if ((!is_array($ret[$d]['next'])) || (!is_array($c_valid_mem_opt_tmp[$a][$d]['next']))){
				    unset ($ret[$d]['next']);
				}else{
					if ($ret[$d]['next'] == $c_valid_mem_opt_tmp[$a][$d]['next']){ 
						
					}else{
					    
						$ret[$d]['next'] = merge_available_mem_array ($ret[$d]['next'],$c_valid_mem_opt_tmp[$a][$d]['next']);						
					}
				}
			}
		}
	}

    return $ret;
}

function merge_available_mem_array ($a,$b){
	$ret = array();	
	foreach ($a as $z => $y){
	    if ($b[$z]){                   
			$c_reg = false;
			if (isset($b[$z]['reg'])){ 
			    $ret[$z]['reg'] = $b[$z]['reg'];
				unset($b[$z]['reg']);
				unset($a[$z]['reg']);
			}
			foreach ($b[$z] as $c => $d){
				if ($a[$z][$c] == $d){ 
					$ret[$z][$c] = $d;
				    continue;
				}				
				$c_bits = 8;
				$c_opt  = 1;
				foreach ($a[$z] as $e => $f){ 
				    if ($e > $c){
					    $c_bits = $c;
					}else{
					    $c_bits = $e;
					}
					$c_opt = $f & $d;
				}			
				$ret[$z][$c_bits] = $c_opt;
			}
			

		}    
	}	
	return $ret;
}






function mem_opt_bits_parser($a,&$ret){
	foreach ($a as $b => $c){		
		if (is_array($c['prev'])){
            foreach ($c['prev'] as $d => $e){
			    foreach ($e as $f => $g){
			        if ($f != 'reg'){
                        $code = $d; 
						
						
						
						
						
						
						
						$ret[$b]['prev']['mem_opt_able'][$code]['opt']  = $g; 
						$ret[$b]['prev']['mem_opt_able'][$code]['bits'] = $f; 				
				        if (isset($e['reg'])){
							$ret[$b]['prev']['mem_opt_able'][$code]['reg'] = $e['reg'];
						}
						
					}
				}
			}		 
		}
		if (is_array($c['next'])){
		    foreach ($c['next'] as $d => $e){
			    foreach ($e as $f => $g){
			        if ($f != 'reg'){
						$code = $d; 
						
						
						
						
						
						
						
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





























function mem_usable_combine($new,$old_bits,$old_opt){

	
	
	


	$ret = array();
	$ret['reg'] = $new['reg'];
	unset ($new['reg']);
	foreach ($new  as $a => $b){
		if ($old_bits <= $a){      
		    if ($b == 1){    
			    $ret[$a] = 1;				
			}else{                 
			    $ret[$a] = 3;
			}		
		}else{                     
		    if ($b == 1){          
			    $ret[$old_bits] = 1;			
			}elseif ($old_opt == 1){     
                $ret[$old_bits] = 1;			
                $ret[$a] = 3;
			}else{                 
			    $ret[$old_bits] = 3;
			}
		
		}
	}
    
	
    
	return $ret;
}











function break_avoid_mem_usable_reg($obj_reg,$c_normal_register_opt){
    
	$break = false;
	if ($obj_reg){ 
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
		
		
	}
	
	return $break;
}







function get_execlist_usable_mem($c_exec_thread_list,$c_valid_mem_opt_array,&$ret,$c_normal_register_opt_array){
	
	
    foreach ($c_valid_mem_opt_array as $a => $b){
	    $objs = array_keys($c_exec_thread_list,$a);
		
		foreach ($objs as $c => $d){
			for ($k = 0;;$k++){
				if (!$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]){
				    break;
				}
			
				$obj_reg = false;
				
				if ($c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['reg']){
					$obj_reg = $c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['reg'];
				}
				$opt_permission = 1;
                if (-1 == $c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['opt']){ 
				                                                                       
				    continue;
				}
				if (2 == $c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['opt']){
				    $opt_permission = 3;
				}

				$c_bits = $c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['bits'];

				
				$i = $d;

				while ($i >= 0){ 
					if (isset($ret[$c_exec_thread_list[$i]]['prev'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']])){
						$ret[$c_exec_thread_list[$i]]['prev'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']] = mem_usable_combine ($ret[$c_exec_thread_list[$i]]['prev'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']],$c_bits,$opt_permission);
					}else{
						$ret[$c_exec_thread_list[$i]]['prev'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']][$c_bits] = $opt_permission;				
					}                     
                    if ($obj_reg){						
						$ret[$c_exec_thread_list[$i]]['prev'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']]['reg'] = $obj_reg;
					}
					
					$i--;			
					if ('-' === $c_exec_thread_list[$i]){ 
						$i--;	
						
						if (break_avoid_mem_usable_reg($obj_reg,$c_normal_register_opt_array[$c_exec_thread_list[$i]])){
						    break;
						}
						continue;
					}	
					if ($i >=0 ){
						if (isset($ret[$c_exec_thread_list[$i]]['next'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']])){
						    $ret[$c_exec_thread_list[$i]]['next'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']] = mem_usable_combine ($ret[$c_exec_thread_list[$i]]['next'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']],$c_bits,$opt_permission);
					    }else{						
						    $ret[$c_exec_thread_list[$i]]['next'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']][$c_bits] = $opt_permission;
					    }						
						if ($obj_reg){
							$ret[$c_exec_thread_list[$i]]['next'][$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['code']]['reg'] = $obj_reg;
						}
						
					}else{
						break;
					}

					
					
					if (break_avoid_mem_usable_reg($obj_reg,$c_normal_register_opt_array[$c_exec_thread_list[$i]])){
					    break;
					}					
					
					
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
	
	
    foreach ($c_valid_mem_opt_array as $a => $b){
	    $objs = array_keys($c_exec_thread_list,$a);
		foreach ($objs as $c => $d){
			for ($k = 0;;$k++){
				if (!$c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]){
				    break;
				}
			
				$obj_reg = false;
				
				if ($c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['reg']){
					$obj_reg = $c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['reg'];
				}
				
                if (-1 == $c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['opt']){ 
				                                                                       
				    continue;
				}				
				
				$c_bits = $c_valid_mem_opt_array[$c_exec_thread_list[$d]][$k]['bits'];

				
				$i = $d;				
				$opt_permission = 1; 
				
				while ($c_exec_thread_list[$i+1]){        
														  
					                                      
					
					
					if (break_avoid_mem_usable_reg($obj_reg,$c_normal_register_opt_array[$c_exec_thread_list[$i]])){
					    break;
					}						
					
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
					if ('-' === $c_exec_thread_list[$i + 1]){  
						$i ++;
						continue;
					}
				}
			}
		}
	}
}




function add_stack_usable (&$soul_usable,$stack_result,$c_exec_thread_list,$sec){	
	foreach ($stack_result as $a => $b){
		foreach ($b as $c => $d){
			if ($d === 3){ 
			    $soul_usable[$sec][$c_exec_thread_list[$c]]['next']['stack'] = false;
				$soul_usable[$sec][$c_exec_thread_list[$c]]['prev']['stack'] = false;
			}elseif ($d === 2){
				$soul_usable[$sec][$c_exec_thread_list[$c]]['next']['stack'] = false;
			}elseif ($d === 1){
				
				
			    $soul_usable[$sec][$c_exec_thread_list[$c]]['prev']['stack'] = false;
				
			}			
		}
	}	
}



	                                    
	                                    
										
										
	                                    



function get_soul_usable_limit($CodeSectionArray,$exec_thread_list,$StandardAsmResultArray,$stack_used,$stack_broke){

	global $normal_register_opt_array;
	global $flag_register_opt_array;
    global $valid_mem_opt_array;
	global $registersss;
    
	global $soul_forbid;
    global $soul_usable;

	$normal_reg_extend_down = array( 
       8 => array (8 => true),
	   9 => array (9 => true),
	  16 => array (8 => true,9=>true,16=>true),
	  32 => array (8 => true,9=>true,16=>true,32=>true),
	);
	$normal_reg_extend_all = array( 
	   8 => array (8 => true,16=>true,32=>true),
	   9 => array (9 => true,16=>true,32=>true),
	  16 => array (8 => true,9=>true,16=>true,32=>true),
	  32 => array (8 => true,9=>true,16=>true,32=>true),
	);

	foreach ($CodeSectionArray as $a => $b){
		echo "<br>+++++++++++++++++<br>";
		$prev_dealed_record = array(); 
		$next_dealed_record = array(); 
		$c_valid_mem_opt_tmp = array();
		foreach ($exec_thread_list[$a] as $c => $d){
            
            if (is_array($valid_mem_opt_array[$a])){
                get_execlist_usable_mem($d,$valid_mem_opt_array[$a],$c_valid_mem_opt_tmp[$c],$normal_register_opt_array[$a]);
			}
            
			$c_flag_reg_usable_array   = array();
			$c_normal_reg_usable_array = array();
			$c_forbid_flag_reg_usable_array   = array();
			$c_forbid_normal_reg_usable_array = array();

            
			$stack_flag   = false;    
			$stack_buff   = array();  
			$stack_result = array();


			for ($max = count($d) - 1; $max >= 0; $max--){	  
				if ('-' === $exec_thread_list[$a][$c][$max]){ 
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
							add_normal_array ($soul_forbid[$a][$exec_thread_list[$a][$c][$max]]['n']['normal'],$c_forbid_normal_reg_usable_array);
						if (!empty($c_forbid_flag_reg_usable_array))
							add_flag_array ($soul_forbid[$a][$exec_thread_list[$a][$c][$max]]['n']['flag'],$c_forbid_flag_reg_usable_array);
                        merge_flag_reg_array  ($soul_usable[$a][$exec_thread_list[$a][$c][$max]]['next']['flag_write_able'],$c_flag_reg_usable_array);
					    merge_normal_reg_array($soul_usable[$a][$exec_thread_list[$a][$c][$max]]['next']['normal_write_able'],$c_normal_reg_usable_array);
					}
				}
				
				if (is_array($flag_register_opt_array[$a][$d[$max]])){
					foreach ($flag_register_opt_array[$a][$d[$max]] as $e => $f){
						if ($f == 2){ 
							$c_flag_reg_usable_array[$e] = true;
							unset ($c_forbid_flag_reg_usable_array[$e]);
						}else{ 
						    unset ($c_flag_reg_usable_array[$e]);
							$c_forbid_flag_reg_usable_array[$e] = true;
						}
					}
				}
				
				if (is_array($normal_register_opt_array[$a][$d[$max]])){
                    foreach ($normal_register_opt_array[$a][$d[$max]] as $e => $f){
						if ($e === 'EIP')  
							continue;	
						foreach ($f as $g => $h){							
							if ($h == 2){ 
								foreach ($normal_reg_extend_down[$g] as $i => $j){
                                    if (isset($registersss[$i][$e]))
										$c_normal_reg_usable_array[$e][$i] = true;
								}
								foreach ($normal_reg_extend_all[$g] as $i => $j){  
								    unset ($c_forbid_normal_reg_usable_array[$e][$i]);
								}
							}else{ 
								foreach ($normal_reg_extend_all[$g] as $i => $j){									
								    unset ($c_normal_reg_usable_array[$e][$i]);
								}
								foreach ($normal_reg_extend_down[$g] as $i => $j){ 
									if (isset($registersss[$i][$e]))
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
						add_normal_array ($soul_forbid[$a][$exec_thread_list[$a][$c][$max]]['p']['normal'],$c_forbid_normal_reg_usable_array);
					if (!empty($c_forbid_flag_reg_usable_array))
						add_flag_array ($soul_forbid[$a][$exec_thread_list[$a][$c][$max]]['p']['flag'],$c_forbid_flag_reg_usable_array);
					merge_flag_reg_array ($soul_usable[$a][$exec_thread_list[$a][$c][$max]]['prev']['flag_write_able'],$c_flag_reg_usable_array);
					merge_normal_reg_array($soul_usable[$a][$exec_thread_list[$a][$c][$max]]['prev']['normal_write_able'],$c_normal_reg_usable_array);
				}
				
				
				if (!isset($soul_usable[$a][$exec_thread_list[$a][$c][$max]]['prev']['stack'])){
					$soul_usable[$a][$exec_thread_list[$a][$c][$max]]['prev']['stack'] = true;
				}
				if (!isset($soul_usable[$a][$exec_thread_list[$a][$c][$max]]['next']['stack'])){
					$soul_usable[$a][$exec_thread_list[$a][$c][$max]]['next']['stack'] = true;
				}
				
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
					
					unset($stack_buff);
					$stack_flag = true;
				}
			}
			if ((false === $stack_flag)&&(!empty($stack_buff))){
				$stack_result[$max] = $stack_buff;
			}
            add_stack_usable ($soul_usable,$stack_result,$exec_thread_list[$a][$c],$a); 

		}		
		
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
		
		if (is_array($valid_mem_opt_array[$a])){			
			$c_valid_mem_opt_tmp = combine_valid_mem_array($exec_thread_list[$a],$c_valid_mem_opt_tmp);	
			
			$c_valid_mem_opt_tmp = merge_same_loc_mem($c_valid_mem_opt_tmp);
			
			mem_opt_bits_parser($c_valid_mem_opt_tmp,$soul_usable[$a]);			
		}
	}
	
}



function add_normal_array (&$a,$b){
    foreach ($b as $c => $d){
	    foreach ($d as $e => $f){
		    $a[$c][$e] = true;
		}
	}
}


function add_flag_array (&$a,$b){
    foreach ($b as $c => $d){
	   $a[$c] = true;
	}
}


function merge_normal_reg_array (&$a,$b){
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



function merge_flag_reg_array (&$a,$b){
    $tmp = array();
	foreach ($a as $z => $y){
	    if ($b[$z] == $y){
		    $tmp[$z] = $y;
		}
	}
	$a = $tmp;
}









function deal_exec_thread_list_get($c_line,$c_asm_array,$c_thread_id,$c_bound_end,$c_solid_jmp_to,&$exec_thread_list,$list_id,&$c_enumming_array,&$list_id_ptr,&$jmp_back_record,&$bound_start){
    
	global $con_abs_jmp;	
	global $Intel_instruction;

    while ($c_line < $c_bound_end){
		if (isset($c_asm_array[$c_line])){
	        $exec_thread_list[$c_thread_id][$list_id][] = $c_line;

            if (1 === $con_abs_jmp[$c_asm_array[$c_line]['operation']]){ 
				$list_id_ptr ++;
				$exec_thread_list[$c_thread_id][$list_id_ptr] = $exec_thread_list[$c_thread_id][$list_id];
				$c_enumming_array[$list_id_ptr] = $c_line + 1;		    
            	if ($c_solid_jmp_to[$c_line]){ 
                    if ($c_solid_jmp_to[$c_line] <= $c_line){ 
					    if (true !== $jmp_back_record[$c_line][$c_solid_jmp_to[$c_line]]){
							$jmp_back_record[$c_line][$c_solid_jmp_to[$c_line]] = true;
						}else{
							return;
						}
					}
					$exec_thread_list[$c_thread_id][$list_id][] = '-'; 
					$c_line = $c_solid_jmp_to[$c_line];
				    continue;					
				}else{      
					
				    return;
				}
			}

			if (2 === $con_abs_jmp[$c_asm_array[$c_line]['operation']]){ 
				                                                         
			    $tmp = $c_line + 1;
				if (!$bound_start[$tmp]){					
					$bound_start[$tmp] = true;
					$list_id_ptr ++;
					$c_enumming_array[$list_id_ptr] = $c_line + 1;					
				}
				if ($c_solid_jmp_to[$c_line]){ 
				    if ($c_solid_jmp_to[$c_line] <= $c_line){ 
					    if (true !== $jmp_back_record[$c_line][$c_solid_jmp_to[$c_line]]){
							$jmp_back_record[$c_line][$c_solid_jmp_to[$c_line]] = true;
						}else{
						    return;
						}
					}				
					$exec_thread_list[$c_thread_id][$list_id][] = '-'; 
					$c_line = $c_solid_jmp_to[$c_line];
					continue;
				}else{                         
					return;
				}        
			}

			if ($Intel_instruction[$c_asm_array[$c_line]['operation']]['data']){ 
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
	
}






function exec_thread_list_get($CodeSectionArray,$StandardAsmResultArray,&$exec_thread_list,$solid_jmp_to,$AsmLastSec){    
	foreach ($CodeSectionArray as $a => $b){
		$jmp_back_record  = array(); 
		$c_enumming_array = array();
		$bound_start = array();      
		$list_id_ptr = 0;
		
        $c_enumming_array[0] = key ($StandardAsmResultArray[$a]);
		                                                         
        $bound_start[0] = true;
        while (count($c_enumming_array)){
			foreach ($c_enumming_array as $c => $d){
				break;
			}
			unset ($c_enumming_array[$c]);
            deal_exec_thread_list_get($d,$StandardAsmResultArray[$a],$a,key($AsmLastSec[$a]),$solid_jmp_to[$a],$exec_thread_list,$c,$c_enumming_array,$list_id_ptr,$jmp_back_record,$bound_start);	   
		}
		
		
		
	}
	
}









function standard_asm(&$myTables,&$garble_rel_info,$AsmResultArray,&$StandardAsmResultArray,&$stack_used,&$stack_broke,&$output,$language){
    
	global $Intel_instruction;
	global $Intel_instruction_mem_opt;
    global $total_register;

	global $all_eflags_0;
	global $register_assort;
	global $normal_register_opt_array;
	global $flag_register_opt_array;
	global $valid_mem_opt_array;

	global $UniqueHead;
	global $pattern_reloc;  

	global $my_cc;

	global $can_not_deal_operation; 

	foreach ($total_register as $a => $b){
        $pattern_regs .=  '('."$a".')|';
	}
    $pattern_regs = substr ($pattern_regs,0,strlen($pattern_regs) - 1);
    
	$CodeSectionArray = $myTables['CodeSectionArray'];
	foreach ($CodeSectionArray as $a => $b){
	    foreach ($AsmResultArray[$a] as $c => $d){
			$c_solid_bits = array();    
			$mem_ptr_bits_recal = false;
			$param_include_r_m = false; 
            $c_reloc = false;
			if (preg_match($pattern_reloc,$d['asm'],$tmp)){
			    $c_reloc = $tmp[0];
			}
			
			
			
			
			
			
			
			$tmp = preg_split('/( |,|\[)/',$d['asm'],-1,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
			$p_ptr = 0;
			$break = false;
			$c_param_type_complete = false; 
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
					
					if (false === $c_param_type_complete){
						
						                                 
														 
														 
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
                        }elseif ($total_register[$x]){
							$StandardAsmResultArray[$a][$c]['p_type'][$p_ptr] = 'r';
							$StandardAsmResultArray[$a][$c]['p_bits'][$p_ptr] = $total_register[$x];
							$c_solid_bits[$p_ptr] = $total_register[$x];
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
					
					if ($Intel_instruction[$x]['isPrefix']){
					    $StandardAsmResultArray[$a][$c]['prefix'][] = $x;	
						
						
						foreach ($Intel_instruction[$x] as $z => $y){
							if ($all_eflags_0[$z]){
								$flag_register_opt_array[$a][$c][$z] |= $y;
							}elseif ($total_register[$z]){ 
								$normal_register_opt_array[$a][$c][$register_assort[$z]][32] |= $y;
							}
						}

					}elseif (is_array($Intel_instruction[$x])){
						if (isset($can_not_deal_operation[$x])){ 
						    $output['warning'][] = $language['section_name']." ".$myTables['CodeSectionArray'][$a]['name'].$language['section_number']." $a ".$language['total_linenumber']." $c , $x ".$language['canot_deal_instruction'];
							$break = true;
							break;
							
						}
					    $StandardAsmResultArray[$a][$c]['operation'] = $x;	
						if (is_array($Intel_instruction_mem_opt[$x])){ 
 						    $valid_mem_opt_array[$a][$c] = $Intel_instruction_mem_opt[$x];							
						}
						if ($my_cc[$x] === 'SETcc'){                   
							$StandardAsmResultArray[$a][$c]['p_bits'][$p_ptr] = 8;
						}
					}else{ 
						 $output['warning'][] = $language['section_name']." ".$myTables['CodeSectionArray'][$a]['name'].$language['section_number']." $a ".$language['total_linenumber']." $c , $x ".$language['unknow_instruction'];
				         $break = true;
						 break;
					}					
					
				}
			}			
			
			if (is_array($StandardAsmResultArray[$a][$c]['p_type'])){
				foreach ($StandardAsmResultArray[$a][$c]['p_type'] as $z => $y){
					if ('m' === $y){
						if (preg_match('/\[([^\(]*)\]/',$StandardAsmResultArray[$a][$c]['params'][$z],$tmp_filter)){ 
							$StandardAsmResultArray[$a][$c]['params'][$z] = $tmp_filter[0];
						}
					}
				}			
			}
            

			if ($break){ 
			    break;
			}else{

				if (isset($Intel_instruction[$StandardAsmResultArray[$a][$c]['operation']]['multi_op'])){ 
                    $multi_op = count ($StandardAsmResultArray[$a][$c]['params']);
					if (isset($Intel_instruction[$StandardAsmResultArray[$a][$c]['operation']][$multi_op])){
						$c_instruction = $Intel_instruction[$StandardAsmResultArray[$a][$c]['operation']][$multi_op];
					}else{
					    $output['warning'][] = $language['section_name']." ".$myTables['CodeSectionArray'][$a]['name'].$language['section_number']." $a ".$language['total_linenumber']." $c , ".$StandardAsmResultArray[$a][$c]['operation']." ($multi_op) ".$language['multi_op_fail'];
				        $break = true;
						break;
					}
				}else{
					$c_instruction = $Intel_instruction[$StandardAsmResultArray[$a][$c]['operation']];					
				}
				
				foreach ($c_instruction as $z => $y){
					if ($all_eflags_0[$z]){
						$flag_register_opt_array[$a][$c][$z] |= $y;
					}elseif ($total_register[$z]){ 
						$normal_register_opt_array[$a][$c][$register_assort[$z]][32] |= $y;
						
						if ($register_assort[$z] === 'ESP'){ 
						    $stack_used[$a][$c] = true;
						}
					}
				}

				
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
				

                if (true === $param_include_r_m){
				    foreach ($StandardAsmResultArray[$a][$c]['params'] as $z => $y){
						if (!$c_instruction[$z]){ 
							$output['warning'][] = $language['section_name']." ".$myTables['CodeSectionArray'][$a]['name'].$language['section_number']." $a ".$language['total_linenumber']." $c , ". $language['nasm_param_not_found'].$StandardAsmResultArray[$a][$c]['operation'].' '.$language['nasm_pnf_param_number'].$z.' , '.$language['giveup_c_section'];							
							$break = true;
							break;
							
						}
					    if ('r' === $StandardAsmResultArray[$a][$c]['p_type'][$z]){ 
							$normal_register_opt_array[$a][$c][$register_assort[$y]][$StandardAsmResultArray[$a][$c]['p_bits'][$z]] |= $c_instruction[$z];								
							if ($c_instruction[$z] > 1){
								if ($StandardAsmResultArray[$a][$c]['params'][$z] === 'ESP'){ 
								    $stack_broke[$a][$c] = true;
								}
							}

						}elseif ('m' === $StandardAsmResultArray[$a][$c]['p_type'][$z]){ 
						    $c_valid_mem_opt_array = array(); 
							$c_valid_mem_opt_array['code'] = $y;
							

							$c_valid_mem_opt_array['opt']  = $c_instruction[$z];
							$c_valid_mem_opt_array['bits'] = $StandardAsmResultArray[$a][$c]['p_bits'][$z];
							
							if (preg_match_all('/'."$pattern_regs".'/',$y,$tmp)){
								foreach ($tmp[0] as $w => $v){
								    $StandardAsmResultArray[$a][$c]['p_m_reg'][$z][$register_assort[$v]] = 1;
									$normal_register_opt_array[$a][$c][$register_assort[$v]][$total_register[$v]] |= 1;
									$c_valid_mem_opt_array['reg'][] = $v;
								}
							}    
							$valid_mem_opt_array[$a][$c][] = $c_valid_mem_opt_array;
						}
					}				
				}

				if ($break){ 
			        break;
				}

				
				
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
                
                
				
				
				
				if ('XOR' == $StandardAsmResultArray[$a][$c]['operation']){
				    if ($StandardAsmResultArray[$a][$c]['params'][0] === $StandardAsmResultArray[$a][$c]['params'][1]){
					    if ($register_assort[$StandardAsmResultArray[$a][$c]['params'][0]]){
							$c_bits = $StandardAsmResultArray[$a][$c]['p_bits'][0];
							$c_reg  = $register_assort[$StandardAsmResultArray[$a][$c]['params'][0]];
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







function is_equal_or_neg($a,$b){
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




function rel_label_replacer(&$myTables,&$AsmResultArray,$LineNum_Code2Reloc,&$output,$language){
    global $UniqueHead;
	foreach ($LineNum_Code2Reloc as $a => $b){
	    
		foreach ($b as $c => $d){
			foreach ($d as $e => $f){ 
			    break;
			}
			$tmp_asm = preg_split("/(0X[0-9A-F]{1,8})/",$AsmResultArray[$a][$c]['asm'],-1,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
			for ($i = count($tmp_asm) - 1;$i > 0 ; $i--){            
			    if (preg_match("/^0X[0-9A-F]{1,8}$/",$tmp_asm[$i])){
					$f --;                                           
					if (0 == $f)                                     
						break;                                       
				}
			}		
             
			if ($i == 0){ 
			    $output['warning'][] = $language['section_name']." ".$myTables['CodeSectionArray'][$a]['name'].$language['section_number']." $a ".$language['reloc_rva']." ".$myTables['RelocArray'][$a][$e]['VirtualAddress']." ".$language['cant_loc_value_or_label'];
                unset ($myTables['CodeSectionArray'][$a]);
				break;
			}else{
				
				
				
				if (($tmp_asm[$i] === '0X'.$myTables['RelocArray'][$a][$e]['value']) || (is_equal_or_neg($tmp_asm[$i],$myTables['RelocArray'][$a][$e]['value']))){
					
					

				}else{    
				          
						  
						  
					$tmp_asm[0] = str_replace('DWORD','',$tmp_asm[0]);
                    $myTables['RelocArray'][$a][$e]['isLabel'] = true;
				}
				$tmp_asm[$i] = "$UniqueHead".'RELINFO_'."$a".'_'."$e".'_0';

                $AsmResultArray[$a][$c]['asm'] = '';
				

				foreach ($tmp_asm as $z => $y){
			        $AsmResultArray[$a][$c]['asm'] .= $y;
			    }
				
				
			}				    
		}		
	}
}








function eip_label_replacer($AsmLastSec,&$solid_jmp_array,&$solid_jmp_to,&$myTables,&$AsmResultArray,$LineNum_Code2Reloc,&$output,$language){
	
	global $eip_instruction;
	global $UniqueHead;
    global $user_option;

	foreach ($myTables['CodeSectionArray'] as $a => $b){
		
		$filt_nop_ptr = false;
		foreach ($AsmResultArray[$a] as $c => $d){
			if ($AsmResultArray[$a][$c]['asm'] == 'NOP'){
				if ($filt_nop_ptr == false){
					$filt_nop_ptr = $c;
				}
			}else{
			    $filt_nop_ptr = false;
			}
			if (!$LineNum_Code2Reloc[$a][$c]){ 
			    $tmp = explode (' ',$d['asm']);
			    if ($eip_instruction[$tmp[0]]){        
					                                   
													   
                                                       
													   
													   
													   
                	$f = count ($tmp) - 1;
					if ($tmp[$f - 1] == 'DWORD'){
					    unset ($tmp[$f - 1]);
					}
					if ($tmp[$f - 1] == 'SHORT'){
					    unset ($tmp[$f - 1]);
					}
				    if (preg_match("/^0X[0-9A-F]{1,8}$/",$tmp[$f])){ 
						$i = hexdec($tmp[$f]);
						
						if ((!$AsmResultArray[$a][$i])&&(!$AsmLastSec[$a][$i])){
							$output['warning'][] = $language['section_name']." ".$myTables['CodeSectionArray'][$a]['name'].$language['section_number']." $a ".$language['jmp_dest_out_rang'];
							unset ($myTables['CodeSectionArray'][$a]);
						    break;
						}
						$tmp[$f] = "$UniqueHead".'SOLID_JMP_'."$i".'_FROM_'."$c"; 
						$solid_jmp_array[$a][$i][] = $tmp[$f].' : ';                 
						$solid_jmp_to[$a][$c] = $i;
						$AsmResultArray[$a][$c]['asm'] = implode(' ',$tmp);
    				    
					    continue;
					}else{
    				    
					    continue;				    
					}
				}
			}
		}
		if (true === $user_option['del_last_nop']){
			if ($filt_nop_ptr){ 
				for ($z = $c;$z >= $filt_nop_ptr;$z--){
					unset ($AsmResultArray[$a][$z]);    
				}
			}
		}
	}
}





function sec_reloc_format(&$myTables,&$AsmResultArray,&$AsmLastSec,&$output,$language,&$LineNum_Code2Reloc,$DebugShow = false){

    $RelocArray       = $myTables['RelocArray'];
    
	
	
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
				$AsmLastSec[$d][$a] = true; 
				break;
			}			
		}			    
	}
	
	$a += $AsmResultArray[$d][$a]['len'];
	$AsmLastSec[$d][$a] = true; 
	
	if ($DebugShow){
		echo "<br>*****<br>";
		$ccolor = "black";	
	}
	foreach ($myTables['CodeSectionArray'] as $a => $b){
		$lp_reloc = 1; 
		if ($DebugShow){
			echo "<font color = $ccolor><br><b>$a<b><br>";
		}
        foreach ($AsmResultArray[$a] as $c => $d){
			

            if (is_array($RelocArray[$a][$lp_reloc])){
				if  (($RelocArray[$a][$lp_reloc]['Type'] != 6) && ($RelocArray[$a][$lp_reloc]['Type'] != 20)){
					
					
				    $output['warning'][] = $language['section_name']." ".$myTables['CodeSectionArray'][$a]['name'].$language['section_number']." $a ".$language['reloc_rva']." ".$RelocArray[$a][$lp_reloc]['VirtualAddress']." ".$language['disenable_rel_type'];
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
							$myTables['RelocArray'][$a][$lp_reloc]['value'] = strtoupper($value);
							if (!preg_match("/^0X[0-9A-F]{1,8}$/",'0X'.$myTables['RelocArray'][$a][$lp_reloc]['value'])){ 
								$output['warning'][] = $language['section_name']." ".$myTables['CodeSectionArray'][$a]['name'].$language['section_number']." $a ".$language['reloc_rva']." ".$RelocArray[$a][$lp_reloc]['VirtualAddress']." ".$language['one_asm_more_reloc'];
                                unset ($myTables['CodeSectionArray'][$a]);
							    break;
							}
							if ($RelocArray[$a][$lp_reloc]['Type'] == 20){
								
								$output['notice'][] = $language['section_name']." ".$myTables['CodeSectionArray'][$a]['name'].$language['section_number']." $a ".$language['reloc_rva']." ".$RelocArray[$a][$lp_reloc]['VirtualAddress']." ".$language['rel32_not_null'];
							}
						}else{
						    $myTables['RelocArray'][$a][$lp_reloc]['value'] = '0';						
						}
						if ($DebugShow){
							echo "<br> $c [".$d['rva']."]{".$d['len']."} : $reloc_bin ".$d['asm'];
							echo "<br><<<----------------------------";
						}
						if ($LineNum_Code2Reloc[$a][$c]){ 
                            $output['warning'][] = $language['section_name']." ".$myTables['CodeSectionArray'][$a]['name'].$language['section_number']." $a ".$language['reloc_rva']." ".$RelocArray[$a][$lp_reloc]['VirtualAddress']." ".$language['one_asm_more_reloc'];
                            unset ($myTables['CodeSectionArray'][$a]);
							break;
						} 						
						if (($d['rva'] + $d['len']) == $RelocArray[$a][$lp_reloc]['VirtualAddress'] + 4){ 
							$LineNum_Code2Reloc[$a][$c][$lp_reloc] = 1;						
						}else{                                                                            
							$LineNum_Code2Reloc[$a][$c][$lp_reloc] = 2;
						}
						$lp_reloc ++;
					}
                    
				}else{
					
					
					
					
					$output['warning'][] = $language['section_name']." ".$myTables['CodeSectionArray'][$a]['name'].$language['section_number']." $a ".$language['reloc_rva']." ".$RelocArray[$a][$lp_reloc]['VirtualAddress']." ".$language['rva_not_align'];
					unset ($myTables['CodeSectionArray'][$a]);
					break;
					
					
				}
			}
			

		}
		if ($DebugShow){
			if ($ccolor == "black"){
				$ccolor = "red";
			}else{
				$ccolor = "black";
			}
		}
	}



	
    return;	

}








function format_disasm_file($asm_file,$bin_filesize,&$AsmResultArray,&$output,$language){
	
	if (!($file = fopen("$asm_file", "r"))){
	    $output['error'][] = $language['open_asm_file_fail'];
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

			if (!is_binStruction ($binary)){ 
				$output['error'][] = $language['illegal_binary'].',line:'."$line";
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
		if (!is_binStruction ($binary)){ 
			$output['error'][] = $language['illegal_binary'].',line : '."$line";
		    break;
		}
		$AsmResultArray[$address]['asm'] = strtoupper($assemble);
		$AsmResultArray[$address]['bin'] = $binary;
        
		$prev_address = $address;
	}
	fclose($file);
		
    if ($bin_filesize != $total_bin_size){
		
		$output['error'][] = $language['result_bin_not_same_size'];
	}
	if (!empty($output['error'])){
		return false;
	}	
    return true;
}


function is_binStruction ($bin){
	if (strlen($bin)%2){
	    return false;
	}
    return preg_match("/^[0-9A-F]{2,16}$/",$bin);
}











function collect_and_disasm($bin_file,$asm_file,$disasm,$CodeSectionArray,$buff,&$bin_filesize,$protect_sec,&$p_sec_abs,&$output,$language,$DebugShow = false){
	global $ARG_MAX;
    global $max_input; 

	$output_line_number = 0 ; 

	$Syn_Max = $ARG_MAX - strlen ("$disasm -b 32 "."$bin_file >> $asm_file");
	
    
	if(file_exists($bin_file)){
		if (!unlink($bin_file)){
			$output['error'][] = $language['obj_filename_del_fail'];
	        return false;	
		}
    }
	if(file_exists($asm_file)){
		if (!unlink($asm_file)){
			$output['error'][] = $language['obj_filename_del_fail'];
		    return false;
		}
    }
	
    $next_orgin = 0;   
	$orgin = 0;
    $write_to = '';
	$tmp_Synchronisation = 0; 
	$Synchronisation = "";
    
	foreach ($CodeSectionArray as $a => $b){
    
		$write_to .= substr($buff,$b['PointerToRawData'],$b['SizeOfRawData']);	
		
        if (isset($protect_sec[$a])){ 
		    foreach ($protect_sec[$a] as $z => $y){
                $Synchronisation .= "-s";
				$Synchronisation .= $tmp_Synchronisation + $z;  
				$Synchronisation .= ' ';
                $Synchronisation .= "-s";
				$Synchronisation .= $tmp_Synchronisation + $z + $y;
				$Synchronisation .= ' ';
				$p_sec_abs[$tmp_Synchronisation + $z] = $tmp_Synchronisation + $z + $y; 
			}
		}

		$tmp_Synchronisation += $b['SizeOfRawData']; 
		
		
		$next_orgin += $b['SizeOfRawData'];
		
        
		if (strlen($Synchronisation) > $Syn_Max - 500 ){ 
		                                                 
			if (!disasm_to_file($bin_file,$write_to,$bin_filesize,$Syn_Max,$asm_file,$language,$output,$max_input,$disasm,$orgin,$Synchronisation)){
				return false;
			}

            $orgin = $next_orgin;
			$write_to = '';			
			$Synchronisation = "";		
		}else{
			$Synchronisation .= "-s"."$tmp_Synchronisation ";
		}
		

	}	

	if (strlen($Synchronisation)){
		if (!disasm_to_file($bin_file,$write_to,$bin_filesize,$Syn_Max,$asm_file,$language,$output,$max_input,$disasm,$orgin,$Synchronisation)){
		    return false;
		}
	}


	if ($DebugShow){
		echo "$bin_filesize <br>";
		echo "$disasm_query <br>";
	}
    
	return filesize($asm_file);
}

function disasm_to_file($bin_file,$write_to,&$bin_filesize,$Syn_Max,$asm_file,$language,&$output,$max_input,$disasm,$orgin,$Synchronisation){

    $bin_filesize += file_put_contents ($bin_file,$write_to);
	$disasm_query = "$disasm -b 32 ".'-o'."$orgin "."$Synchronisation"."$bin_file >> $asm_file";

    echo ("<br> $disasm_query");
	if (strlen($disasm_query) > $Syn_Max){       
		$output['error'][] = $language['too_many_arg'];
		return false;
	}
	exec ("$disasm_query");
	
	if (false !== $max_input){
		$output_line_number = get_file_line($asm_file);
		if ($output_line_number > $max_input){
			$output['error'][] = $language['too_big_input_01'].$output_line_number.$language['too_big_input_02'].$max_input.$language['too_big_input_03'];
			return false;
		}
	}
    return true;
}





function  init_rel_jmp_array(&$rel_jmp_range,&$rel_jmp_pointer,$solid_jmp_to){
	global $UniqueHead;
    global $soul_writein_Dlinked_List_Total; 
    global $range_limit_static_jmp;
	global $StandardAsmResultArray;


    foreach ($solid_jmp_to as $sec => $a){
	    foreach ($a as $source => $dest ){

			$dest = "$UniqueHead".'SOLID_JMP_'."$dest".'_FROM_'."$source".' : ';
			$source = search_list_number_from_org($source,$soul_writein_Dlinked_List_Total[$sec]['list']);

            $c_opt = $StandardAsmResultArray[$sec][$soul_writein_Dlinked_List_Total[$sec]['list'][$source]['c']]['operation'];
            
			if (isset($range_limit_static_jmp[$c_opt])){
				$dest   = search_list_number_from_org($dest,$soul_writein_Dlinked_List_Total[$sec]['list'],true);
				if ((false === $source) or (false === $dest)){ 
					return fasle;
				}else{
					echo "<br>solid jmp:  $source -> $dest";				
					$units = array();
					$rel_jmp_range[$sec][$source]['label'] = $dest;	
					if ($dest < $source){  
						$rel_jmp_range[$sec][$source]['range'] = range_get_from_list($dest,$source,$soul_writein_Dlinked_List_Total[$sec]['list'],$source,$units,-1);
					}else{					
						$rel_jmp_range[$sec][$source]['range'] = range_get_from_list($source,$dest,$soul_writein_Dlinked_List_Total[$sec]['list'],$source,$units);
					}
					if (false === $rel_jmp_range[$sec][$source]['range']){
						return false;
					}
					$rel_jmp_range[$sec][$source]['unit'] = $units;
					
					
					foreach ($units as $a => $b){
						$rel_jmp_pointer[$sec][$a][$source] = $b;
					}
					
					
					$c_opt = $StandardAsmResultArray[$sec][$soul_writein_Dlinked_List_Total[$sec]['list'][$source]['c']]['operation'];
					if (isset($range_limit_static_jmp[$c_opt])){
						$rel_jmp_range[$sec][$source]['max'] = $range_limit_static_jmp[$c_opt];
					}
				}	
			}
		}
	}
    return true;
}

function range_get_from_list($start,$end,$list,$n,&$units,$order = 1){

	$range = 0;		
    $c = $start;
    
	$units[$c] = 2;
	
	while (true){
        $c = $list[$c]['n'];         

		if ($c === $end){
		    if (-1 === $order){ 
			    $range += $list[$c]['len'];
			    $units[$c] = 1 | 4;
			}else{
				$units[$c] = 1;
			}
			return $range;
		}
        if (isset($list[$c]['len'])){
			$range += $list[$c]['len'];
		}
		$units[$c] = 1 | 2 | 4;
        
		if (!isset($list[$c]['n'])){
		    break;
		}

	}
	return false;    
}


function search_list_number_from_org($org,$list,$label=false){
	foreach ($list as $num => $a){
	    if (true === $label){
		    if ($org === $a['label']){
			    return $num;
			}
		}else{
		    if ($org === $a['c']){
			    return $num;
			}
		}
	}

	return false;
}




function redeal_split_opt($StandardAsmResultArray,$exec_thread_list,&$soul_forbid,&$soul_usable){	
	foreach ($exec_thread_list as $sec => $c_exec_thread_list){
		$split = array();
		foreach ($c_exec_thread_list as $a => $b){
			$z = end($b);
			$split[$z] = true;
		}
		$need = false;
		foreach ($StandardAsmResultArray[$sec] as $line => $a){
			if (false !== $need){ 
				unset ($soul_forbid[$sec][$need]['n']);
				$soul_forbid[$sec][$need]['n'] = $soul_forbid[$sec][$line]['p']; 
				unset ($soul_usable[$sec][$need]['n']);				
				$soul_usable[$sec][$need]['n'] = $soul_usable[$sec][$line]['p'];
				$need = false;
			}
		    if (isset($split[$line])){ 
			    $need = $line;    
			}
		}
		if (false !== $need){ 
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
?>