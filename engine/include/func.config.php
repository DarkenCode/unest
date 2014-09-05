<?php









function usr_config_parser($buffer,$preprocess,&$preprocess_config,&$all_configure_array,&$section_array,&$global_array,&$i){

	global $language;
	global $output;


    $in_section = false;  
	$c_name     = false;  
	$c_ret      = array();

	foreach ($buffer as $line => $a){  
		$line ++ ;
		$a = trim($a);
		if ((0 == strlen($a)) or ('//' === substr($a,0,2))){ 
			continue;
		}
		if (false === $in_section){
			if ('==SectionConfig==' === $a){
				$c_ret  = array();
				$c_name = false;
				$in_section = 1;    
				continue;
			}elseif ('==GlobalConfig==' === $a){
				$in_section = 2;    
				continue;
			}elseif ('==PreprocessConfig==' === $a){
				if (false !== $preprocess_config){  
				    $output['error'][] = $language['dup_preprocessconfig'];
					return false;
				}
				$in_section = 3; 
				continue;
			}				    
		}else{
			if (('==/SectionConfig==' === $a)&&(1 === $in_section)){
				if (false !== $c_name){
					$all_configure_array[$i] = $c_ret;
					if (isset($section_array[$c_name])){
						$output['error'][] = $language['dup_section'].$c_name;
						return false;
					}
					$section_array[$c_name] = $i;
					$i ++;
				}
				$c_name = false;
				$in_section = false;
				$c_ret  = array();
				continue;
			}elseif (('==/GlobalConfig==' === $a)&&(2 === $in_section)){
				$all_configure_array[$i]           = $c_ret;
				$global_array[$i] = $i;
                $i ++;
				$in_section = false;
				$c_ret  = array();
				continue;
			}elseif (('==/PreprocessConfig==' === $a)&&(3 === $in_section)){
				$in_section = false;
				continue; 
			}else{
				$tmp = preg_split('/\s+/',$a); 
				if (count($tmp) > 1){
					$name  = trim ($tmp[0]);							
					$value = trim ($tmp[1]);
		
					if (3 === $in_section) { 
						if ('@protect_section' === $name){
							$size = intval($tmp[2]); 
							$value = intval($value);
							if (!isset($preprocess_config[$value]) or ($size > $preprocess_config[$value])){
								$preprocess_config['protect_section'][$value] = $size;
							}									
						}elseif ('@dynamic_insert' === $name){
							$size = intval($tmp[2]); 
							$value = intval($value);
							if (!isset($preprocess_config[$value]) or ($size > $preprocess_config[$value])){
								$preprocess_config['dynamic_insert'][$value] = $size;
							}
						}	
						continue;
					}else{
						
						
						
                        if ('@name' === $name){
							if (false === $c_name){
								$c_name = $value;								
							}else{ 
							    $output['warning'][] = $language['double_usr_cfg_secname'].' '.$line.' : '.$a;
							}
							continue;
						}else{
							$tmp = check_cnf_value ($name,$value,$c_ret);
                            if (1 === $tmp){
								$output['warning'][] = $language['unknown_usr_cfg_name'].' '.$line.' : '.$a;
							}elseif (2 === $tmp){
								
								$output['warning'][] = $language['dismatch_usr_cfg_value'].' '.$line.' : '.$a;
							}
							continue;				
						}
					}
				}
			}
		}
		$output['notice'][] = $language['unkown_usr_cfg_line'].' '.$line.' : '.$a;
	}

}







function get_usr_config($sec_name,$filename,&$user_config,&$user_strength,&$user_cnf,&$preprocess_config,$preprocess = false,&$preprocess_sec_name){

   	global $language;
	global $output;

    $buffer = false;
	$handle = @fopen("$filename", "r");
    if ($handle) {
		while (!feof($handle)) {
			$buffer[] = fgets($handle, 4096);			
		}
		fclose($handle);

		if (is_array($buffer)){
			$all_configure_array = array(); 
                                            
					        				
            $section_array = array();       
	                                        
            $global_array = array();        
	                                        
            $preprocess_config = false;     
                                            
			$index = 1;                     
                                            
		    usr_config_parser($buffer,$preprocess,$preprocess_config,$all_configure_array,$section_array,$global_array,$index);  

			
			foreach ($section_array as $a => $b){
				$preprocess_sec_name[$a] = true;
			}
			
			
			if (false === $preprocess){
				
				foreach ($sec_name as $c_name => $v){
					if (!isset($section_array[$c_name])){
						$all_configure_array[$index] = array();
						$section_array[$c_name] = $index;
						$index ++;
					}
				}
				
				foreach ($section_array as $c_name => $v){
					if (!isset($sec_name[$c_name])){
						unset ($all_configure_array[$v]);
						$output['warning'][] = $language['unknown_cfg_sec_name'].$c_name;
					}
				}

				
				global_configure_extended ($all_configure_array,$global_array);

				
				foreach ($sec_name as $c_name => $v){
					$i = $section_array[$c_name];
					$user_config[$c_name]            = $all_configure_array[$i]['unprotect'];
					$user_config[$c_name]['protect'] = $all_configure_array[$i]['protect'];
					if (true === $all_configure_array[$i]['stack_usable']){
					    $user_config[$c_name]['stack_usable'] = true;
					}
					foreach ($v as $sec_id){
						$user_cnf[$sec_id]           = $all_configure_array[$i];
						$user_strength[$sec_id]      = $all_configure_array[$i]['strength'];
					}
				}		
			}
			return true;
		}
	}
    return false;
}


function global_configure_extended (&$all_configure_array,$global_array){
	$a = $all_configure_array;
	$c_global = array(); 
    foreach ($a as $key => $value){
	    if (isset($global_array[$key])){ 
		    patch_array($value,$c_global);
			$c_global = $value;
			unset ($all_configure_array[$key]);
		}elseif (!empty($c_global)){        
		    patch_array($all_configure_array[$key],$c_global);
		}
	}
}






function patch_array(&$a,$b){
    if (!is_array($a)){
	    $a = $b;
	}elseif (is_array($b)){
		foreach ($b as $z => $y){
			if (!isset($a[$z])){
			    $a[$z] = $b[$z];
			}elseif (is_array($y)){
				patch_array($a[$z],$b[$z]);
			}
		}
	}

    return;
}








function check_cnf_value ($name,$value,&$c_ret){
	global $all_eflags_0;
    global $registersss;

    if ('@unprotect' === $name){
		$value = strtoupper($value);
		if ('STATUS_FLAGS' === $value){							
			$c_ret['unprotect']['flag']['CF'] = 1;
			$c_ret['unprotect']['flag']['PF'] = 1;
			$c_ret['unprotect']['flag']['AF'] = 1;
			$c_ret['unprotect']['flag']['ZF'] = 1;
			$c_ret['unprotect']['flag']['SF'] = 1;
			$c_ret['unprotect']['flag']['OF'] = 1;
			return true;
		}elseif (isset($all_eflags_0[$value])){
			$c_ret['unprotect']['flag'][$value] = 1;
			return true;
		}elseif (isset($registersss['32'][$value])){
			$c_ret['unprotect']['normal'][$value]['32'] = 1;
			return true;
		}								 
	}elseif ('@protect' === $name){
		$value = strtolower($value);
		if ('thread_memory' === $value){
			$c_ret['protect']['thread_memory'] = true;  
			return true;
		}
	}elseif ('@strength_poly_max' === $name){
		if (is_numeric($value)){
			$c_ret['strength']['poly']['max'] = intval($value);
			return true;
		}
	}elseif ('@strength_poly_min' === $name){
		if (is_numeric($value)){
			$c_ret['strength']['poly']['min'] = intval($value);
			return true;
		}
	}elseif ('@strength_bone_max' === $name){
		if (is_numeric($value)){
			$c_ret['strength']['bone']['max'] = intval($value);
			return true;
		}
	}elseif ('@strength_bone_min' === $name){
		if (is_numeric($value)){
			$c_ret['strength']['bone']['min'] = intval($value);
			return true;
		}
	}elseif ('@strength_meat_max' === $name){
		if (is_numeric($value)){
			$c_ret['strength']['meat']['max'] = intval($value);
			return true;
		}
	}elseif ('@strength_meat_min' === $name){
		if (is_numeric($value)){
			$c_ret['strength']['meat']['min'] = intval($value);
			return true;
		}
	}elseif ('@strength_default' === $name){
		if (is_numeric($value)){
			$c_ret['strength']['default'] = intval($value);
			return true;
		}
	}elseif ('@meat_mutation' === $name){
		if (is_numeric($value)){
			$c_ret['meat_mutation'] = intval($value);
			return true;
		}
	}elseif ('@soul_focus' === $name){
		if (is_numeric($value)){
			$c_ret['soul_focus'] = intval($value);
			return true;
		}
	}elseif ('@output_opcode_max' === $name){
		if (is_numeric($value)){
			$c_ret['output_opcode_max'] = intval($value);
			return true;
		}
	}elseif ('@setvalue_dynamic' === $name){
		$value = strtolower($value);
		$c_ret['setvalue_dynamic'][$value] = true;
		return true;	
	}elseif ('@gen4debug01' === $name){
	    if ('on' === strtolower($value)){
            $c_ret['gen4debug01'] = true;
		    return true;
		}		
	}elseif ('@stack_usable' === $name){
	    if ('on' === strtolower($value)){
            $c_ret['stack_usable'] = true;
		    return true;
		}
	}elseif ('@stack_pointer_define' === $name){
		$value = strtoupper($value);
	    if (isset($registersss['32'][$value])){
			$c_ret['stack_pointer_define'][$value] = $value;
            return true;
		}		
	}else{  
		return 1;
	}
    return 2;
}








function affect_setvalue_dynamic($sec_name,$setvalue_dynamic,&$user_cnf){
    global $output;
	global $language;

    foreach ($setvalue_dynamic as $name => $a){
	    if (isset($sec_name[$name])){
			$v = $sec_name[$name];
			foreach ($v as $a){
                foreach ($setvalue_dynamic[$name] as $key => $new_value){
					if (true === $user_cnf[$a]['setvalue_dynamic'][$key]){
						$tmp = check_cnf_value('@'.$key,$new_value,$user_cnf[$a]);
						if (1 === $tmp){ 
							$output['warning'][] = $language['setvalue_unknown_key'].'['.$name.']['.$key.'] = '.$new_value;
						}elseif (2 === $tmp){ 
							$output['warning'][] = $language['setvalue_mismatch_value'].'['.$name.']['.$key.'] = '.$new_value;
						}						
					}else{ 
				        $output['warning'][] = $language['setvalue_with_off'].'['.$name.']['.$key.']';
						break;
					}
				}
			}
		}else{ 
		    $output['warning'][] = $language['setvalue_illegal_sec'].$name;
		}
	}

}





function reconfigure_soul_usable ($sec_name,$sec_soul_usable_define,$user_cnf,$soul_writein_Dlinked_List_Total,&$soul_usable,$soul_forbid){
	global $StandardAsmResultArray;
    global $all_valid_mem_opt_index;
	global $avmoi_ptr;

	foreach ($sec_name as $a => $b){	    
		if (empty($sec_soul_usable_define[$a])){
		    continue;
		}
	
		$c_define = $sec_soul_usable_define[$a]; 
		foreach ($b as $c => $d){
			$c_list = $soul_writein_Dlinked_List_Total[$d]['list'][0]; 
			while (true){				
				$f = $c_list['c']; 
                if (true === $c_define['protect']['thread_memory']){   
					if (is_array($soul_usable[$d][$f]['p']['mem_opt_able'])){
					    foreach ($soul_usable[$d][$f]['p']['mem_opt_able'] as $z => $y){
							if (1 < $all_valid_mem_opt_index[$y]['opt']){
                                $all_valid_mem_opt_index[$avmoi_ptr] = $all_valid_mem_opt_index[$y];
                                $all_valid_mem_opt_index[$avmoi_ptr]['opt'] &= 1;
								$soul_usable[$d][$f]['p']['mem_opt_able'][$z] = $avmoi_ptr;
								$avmoi_ptr ++;
							}
						}
					}
					if (is_array($soul_usable[$d][$f]['n']['mem_opt_able'])){
					    foreach ($soul_usable[$d][$f]['n']['mem_opt_able'] as $z => $y){
							if (1 < $all_valid_mem_opt_index[$y]['opt']){
                                $all_valid_mem_opt_index[$avmoi_ptr] = $all_valid_mem_opt_index[$y];
                                $all_valid_mem_opt_index[$avmoi_ptr]['opt'] &= 1;
								$soul_usable[$d][$f]['n']['mem_opt_able'][$z] = $avmoi_ptr;
								$avmoi_ptr ++;
							}
						}
					}
				}
			    
			    if (isset($c_define['flag'])){
					foreach ($c_define['flag'] as $z => $y){
						if (!isset($soul_forbid[$d][$f]['p']['flag'][$z])){
							$soul_usable[$d][$f]['p']['flag_write_able'][$z] = $y;
						}
						if (!isset($soul_forbid[$d][$f]['n']['flag'][$z])){
							$soul_usable[$d][$f]['n']['flag_write_able'][$z] = $y;
						}
					}
				}
				
				if (isset($c_define['normal'])){
					foreach ($c_define['normal'] as $z => $y){
						if (!isset($soul_forbid[$d][$f]['p']['normal'][$z])){
							foreach ($c_define['normal'][$z] as $x => $w){
								$soul_usable[$d][$f]['p']['normal_write_able'][$z][$x] = $y;
							}
						}
						if (!isset($soul_forbid[$d][$f]['n']['normal'][$z])){
							foreach ($c_define['normal'][$z] as $x => $w){
								$soul_usable[$d][$f]['n']['normal_write_able'][$z][$x] = $y;
							}
						}
					}
				}

				if (true === $c_define['stack_usable']){   
					unset ($soul_usable[$d][$f]['p']['normal_write_able']['ESP']);
					unset ($soul_usable[$d][$f]['n']['normal_write_able']['ESP']);
				    $soul_usable[$d][$f]['p']['stack'] = true;
				    $soul_usable[$d][$f]['n']['stack'] = true;
					$StandardAsmResultArray[$d][$f]['stack'] = true;
				}
				
				
				if (isset($c_list['n'])){
					$c_list = $soul_writein_Dlinked_List_Total[$d]['list'][$c_list['n']];
				}else{
				    break;
				}
			}			
		}
	}
}


?>