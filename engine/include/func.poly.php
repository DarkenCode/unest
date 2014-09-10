<?php


if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}

$UNIQUE_poly_index = 1; 




function is_same_mem($a,$b){
    global $pattern_reloc_4_replace;
	global $UniqueHead;
					
	if ($a === $b){
	    return true;
	}
	
	$replacement = "$UniqueHead".'RELINFO_'.'$2_$3';
	$a = preg_replace($pattern_reloc_4_replace,$replacement,$a); 
    $b = preg_replace($pattern_reloc_4_replace,$replacement,$b); 

	if ($a === $b){
	    return true;
	}
    return false;
}






function inherit_usable_to_poly(&$poly_model,$specific_usable,$soul_usable,$flag_forbid,$param_forbid,$rand_forbid,$rand_result,$org){

    global $all_valid_mem_opt_index;
    global $avmoi_ptr;
	global $register_assort;

    foreach ($poly_model['code'] as $a => $b){ 
        if (isset($soul_usable['p'])){
			$poly_model['usable'][$a]['p'] = $soul_usable['p'];     
			$poly_model['usable'][$a]['n'] = $soul_usable['p'];     
		}
    }
	
	if (isset($soul_usable['n'])){
		$poly_model['usable'][$a]['n'] = $soul_usable['n'];     
	}else{
		unset ($poly_model['usable'][$a]['n']);     
	}

	
	if ($specific_usable){
	    foreach ($specific_usable as $a => $b){			
			if ($b['1']){
				unset ($poly_model['usable'][$a]['p']);
				$poly_model['usable'][$a]['p'] = $soul_usable[$b['1']];     
		    }elseif ($b['2']){
				unset ($poly_model['usable'][$a]['n']);
				$poly_model['usable'][$a]['n'] = $soul_usable[$b['2']];     
			}
		}
	}

	
	if (isset ($flag_forbid)){
		if (isset ($flag_forbid['p'])){
			foreach ($flag_forbid['p'] as $z => $y){
				foreach ($y as $v => $w){
					unset ($poly_model['usable'][$z]['p']['flag_write_able'][$v]);
				}
			}
		}
        if (isset ($flag_forbid['n'])){
			foreach ($flag_forbid['n'] as $z => $y){
				foreach ($y as $v => $w){
					unset ($poly_model['usable'][$z]['n']['flag_write_able'][$v]);
				}
			}
		}
        unset ($poly_model['flag_forbid']);
	}


    
    if (isset ($param_forbid)){
		if (isset ($param_forbid['p'])){
			foreach ($param_forbid['p'] as $z => $y){
				foreach ($y as $v => $w){
					if ('r' == $org['p_type'][$v]){ 
						$standard_reg = $register_assort[$org['params'][$v]];
						unset ($poly_model['usable'][$z]['p']['normal_write_able'][$standard_reg]);
						
                        if (isset($poly_model['usable'][$z]['p']['mem_opt_able'])){
						    $s = $poly_model['usable'][$z]['p']['mem_opt_able'];
							foreach ($s as $u => $t){
								if (isset ($all_valid_mem_opt_index[$t]['reg'])){
									foreach ($all_valid_mem_opt_index[$t]['reg'] as $j => $k){
										if ($standard_reg === $k){
											unset ($poly_model['usable'][$z]['p']['mem_opt_able'][$u]);
											break;
										}
									}
								}
							}              
						}
					}elseif ('m' == $org['p_type'][$v]){ 
						if (is_array($poly_model['usable'][$z]['p']['mem_opt_able'])){
							$s = $poly_model['usable'][$z]['p']['mem_opt_able'];
							foreach ($s as $u => $t){
								if (is_same_mem($org['params'][$v],$all_valid_mem_opt_index[$t]['code'])){
									if ($all_valid_mem_opt_index[$t]['opt'] > 1){
										$all_valid_mem_opt_index[$avmoi_ptr] = $all_valid_mem_opt_index[$t];
										$all_valid_mem_opt_index[$avmoi_ptr]['opt'] = 1;
										$poly_model['usable'][$z]['p']['mem_opt_able'][$u] = $avmoi_ptr;
										$avmoi_ptr ++;                                                                  
									}
								}
							}
						}
					}
				}
			}
		}
		if (isset ($param_forbid['n'])){
			foreach ($param_forbid['n'] as $z => $y){
				foreach ($y as $v => $w){
					if ('r' == $org['p_type'][$v]){ 
						$standard_reg = $register_assort[$org['params'][$v]];
						unset ($poly_model['usable'][$z]['n']['normal_write_able'][$standard_reg]);
						
						if (isset($poly_model['usable'][$z]['n']['mem_opt_able'])){
							$s = $poly_model['usable'][$z]['n']['mem_opt_able'];
							foreach ($s as $u => $t){
								if (isset ($all_valid_mem_opt_index[$t]['reg'])){
									foreach ($all_valid_mem_opt_index[$t]['reg'] as $j => $k){
										if ($standard_reg === $k){
											unset ($poly_model['usable'][$z]['n']['mem_opt_able'][$u]);
											break;
										}
									}
								}
							}       
						}
					}elseif ('m' == $org['p_type'][$v]){ 
                        if (isset($poly_model['usable'][$z]['n']['mem_opt_able'])){
							$s = $poly_model['usable'][$z]['n']['mem_opt_able'];
							foreach ($s as $u => $t){
								if (is_same_mem($org['params'][$v],$all_valid_mem_opt_index[$t]['code'])){
									if ($all_valid_mem_opt_index[$t]['opt'] > 1){
										$all_valid_mem_opt_index[$avmoi_ptr] = $all_valid_mem_opt_index[$t];
										$all_valid_mem_opt_index[$avmoi_ptr]['opt'] = 1;
										$poly_model['usable'][$z]['n']['mem_opt_able'][$u] = $avmoi_ptr;
										$avmoi_ptr ++;                                                                  
									}
								}
							}
						}
					}
				}
			}
		}
	}

    
    if (isset ($rand_forbid)){
		if (isset ($rand_forbid['p'])){
			foreach ($rand_forbid['p'] as $z => $y){
				foreach ($y as $v => $w){
					if (isset ($register_assort[$rand_result[$v]])){ 
						unset ($poly_model['usable'][$z]['p']['normal_write_able'][$register_assort[$rand_result[$v]]]);
					}else{                                           
						if (isset($poly_model['usable'][$z]['p']['mem_opt_able'])){
							$x = $poly_model['usable'][$z]['p']['mem_opt_able'];
							foreach ($x as $t => $u){
								
								if (is_same_mem($rand_result[$v],$all_valid_mem_opt_index[$u]['code'])){
									
									unset ($poly_model['usable'][$z]['p']['mem_opt_able'][$t]); 
								}
							}
						}
					}
				}
			}
		}
		if (isset ($rand_forbid['n'])){
			foreach ($rand_forbid['n'] as $z => $y){
				foreach ($y as $v => $w){
					if (isset ($register_assort[$rand_result[$v]])){ 
						unset ($poly_model['usable'][$z]['n']['normal_write_able'][$register_assort[$rand_result[$v]]]);
					}else{                                           
						if (isset($poly_model['usable'][$z]['n']['mem_opt_able'])){
							$x = $poly_model['usable'][$z]['n']['mem_opt_able'];
							foreach ($x as $t => $u){
								if (is_same_mem($rand_result[$v],$all_valid_mem_opt_index[$u]['code'])){
																		echo "   same";
										unset ($poly_model['usable'][$z]['n']['mem_opt_able'][$t]);                                                                     
								}
							}                                               
						}
					}
				}
			}               
		}
	}

	
	

        return;
}





function ooo ($poly_model){
    $ret = $poly_model;
        $t = $poly_model['ooo'];        
        if (shuffle($t)){
            if ($t != $poly_model['ooo']){
                    foreach ($poly_model['ooo'] as $a => $b){
                            if ($t[$a] != $b){
                                    $ret['code'][$t[$a]] = $poly_model['code'][$b];
									$ret['p_type'][$t[$a]]    = $poly_model['p_type'][$b];
									$ret['p_bits'][$t[$a]]    = $poly_model['p_bits'][$b];
                                }
                        }
                }
        }
    return $ret;
}




function sub_rand_usable_reg($org,$ret){

        global $register_assort;
        if (isset($org['p_type'])){
                foreach ($org['p_type'] as $a => $b){
                        if ($b === 'r'){
                                $z = $register_assort[$org['params'][$a]];
                                unset ($ret[$z]);
                        }
                }       
        }
        global $Intel_instruction;
        $opt = $Intel_instruction[$org['operation']];
        if ($opt['multi_op']){
           $i = count($org['p_type']);
           $opt = $opt[$i];
        }
        foreach ($opt as $a => $b){
            if ($register_assort[$a]){
				unset ($ret[$register_assort[$a]]);
            }
        }
    return $ret;
}





function org_include_mem($org,$mem){
    if (isset($org['p_type'])){
		foreach ($org['p_type'] as $a => $b){
			if ($b === 'm'){
				if ($mem['code'] === $org['params'][$a]){
					return true;
				}
			}
		}
	}
	return false;
}






function check_poly_usable ($c_soul_usable,$org,&$usable_poly_model,&$rand_result){
    global $poly_model_repo;
    global $register_assort;
    global $all_valid_mem_opt_index;

    $obj = $org['operation'];

	$tmp = $usable_poly_model;
	foreach ($tmp as $a => $b){
        
        if (true !== $org['stack']){			
			if (true === $poly_model_repo[$obj][$b]['new_stack']){
				echo "<font color=red>stack conflict!";
                var_dump ($usable_poly_model[$a]);
				echo '</font>';
			    unset($usable_poly_model[$a]);
				continue;
			}		    
		}
		
		$break = false;
		if (is_array($poly_model_repo[$obj][$b]['new_regs']['normal'])){ 
			foreach ($poly_model_repo[$obj][$b]['new_regs']['normal'] as $c => $d){ 
				if (isset($register_assort[$org['params'][$c]])){        
					$c = $register_assort[$org['params'][$c]];
					if (!$c_soul_usable['n']['normal_write_able'][$c][32]){ 
						
						unset ($usable_poly_model[$a]);
						$break = true;
						break;
					}
				}elseif (isset($register_assort[$c])){                   
                    if (!$c_soul_usable['n']['normal_write_able'][$c][32]){ 
						unset ($usable_poly_model[$a]);
						$break = true;
						break;
					}
				}else{ 
					$available = false;
					foreach ($c_soul_usable['n']['mem_opt_able'] as $e => $f){
						if ((2 <= $all_valid_mem_opt_index[$f]['opt'])&&($all_valid_mem_opt_index[$f]['code'] === $org['params'][$c])){
							$available = true;
						}
					}
					if (!$available){
						
						unset ($usable_poly_model[$a]);
						$break = true;
						break;
					}
				}
			}
		}			
		if ($break){
			continue;
		}
		if (is_array($poly_model_repo[$obj][$b]['new_regs']['flag'])){ 
			foreach ($poly_model_repo[$obj][$b]['new_regs']['flag'] as $c => $d){
				if (!$c_soul_usable['n']['flag_write_able'][$c]){ 
					
					unset ($usable_poly_model[$a]);
					$break = true;
					break;
				}
			}
		}                              
		if ($break){
			continue;
		}
		
		if (isset($poly_model_repo[$obj][$b]['rand'])){ 
			                                            
														
														
			$c_usable_normal = $c_soul_usable['p']['normal_write_able'];
			$rand_mem = false;
			foreach ($poly_model_repo[$obj][$b]['rand'] as $z => $y){
				if (shuffle ($y)){
					foreach ($y as $x){
						if ($x == 'i'){
							$r_int = rand_interger();							
							$rand_result[$a][$z] = $r_int['value'];
							$rand_result[$a]['p_type'][$z] = 'i';
							$rand_result[$a]['p_bits'][$z] = 32; 
						}elseif (($x == 'm32')&&(!$rand_mem)){
							$c_usable_mem_readonly = false;
							$c_usable_mem_writable = false;
							if (isset($c_soul_usable['p']['mem_opt_able'])){
								foreach ($c_soul_usable['p']['mem_opt_able'] as $v => $w){
									if (32 == $all_valid_mem_opt_index[$w]['bits']){
										$c_usable_mem_readonly[$w] = true;
											if ($all_valid_mem_opt_index[$w]['opt'] >= 2){       
												$c_usable_mem_writable[$w] = true;										
											}
									}
								}
								if ($poly_model_repo[$obj][$b]['rand_privilege'][$z] >=2){ 
									if (false !== $c_usable_mem_writable){
										$w = array_rand($c_usable_mem_writable);	
										
										if (false === org_include_mem($org,$all_valid_mem_opt_index[$w])){
											$rand_result[$a][$z] = $all_valid_mem_opt_index[$w]['code'];
											$rand_result[$a]['p_type'][$z] = 'm';
											$rand_result[$a]['p_bits'][$z] = 32;
											$rand_mem = true; 
										}									
									}
								}elseif (false !== $c_usable_mem_readonly){                                                      
								    $w = array_rand($c_usable_mem_readonly);							
									$rand_result[$a][$z] = $all_valid_mem_opt_index[$w]['code'];
									$rand_result[$a]['p_type'][$z] = 'm';
									$rand_result[$a]['p_bits'][$z] = 32;
								}
							}
						}elseif ($x == 'r32'){							
						    if ($poly_model_repo[$obj][$b]['rand_privilege'][$z] >=2 ){ 
								if (isset($c_soul_usable['p']['normal_write_able'])){
									$c_usable_normal_reg = false;
									foreach ($c_soul_usable['p']['normal_write_able'] as $j => $k){
									    if ($k[32]){
										    $c_usable_normal_reg[$j] = true;
										}
									}
									if (false !== $c_usable_normal_reg){
									    $rand_result[$a][$z] = array_rand($c_usable_normal_reg);
										$rand_result[$a]['p_type'][$z] = 'r';
										$rand_result[$a]['p_bits'][$z] = 32; 
									}							
								}
							}else{                                                      
								global $registersss;
							    $rand_result[$a][$z] = array_rand($registersss['32']);
								$rand_result[$a]['p_type'][$z] = 'r';
								$rand_result[$a]['p_bits'][$z] = 32; 
							}   
						}

						if (isset($rand_result[$a][$z])){
							break;
						}
					}
				}
				if (!isset($rand_result[$a][$z])){
					unset ($usable_poly_model[$a]);
					break;
				}
			}
		}
	}
}




function generat_poly_code($org,$soul_usable,$poly_model,$rand_result,$int3 = false){
    global $c_rel_info;
    global $UniqueHead;
    global $UNIQUE_poly_index;
	global $sec;
        $ret = array();

        if (isset($poly_model['ooo'])){ 
            $poly_model = ooo($poly_model);
        }
     
        if ($int3){
            $ret['code']['int3']['operation'] = 'int3';
        }
		$ret['fat'] = $poly_model['fat'];

        $specific_usable = false;
        if (isset($poly_model['specific_usable'])){
			$specific_usable = $poly_model['specific_usable'];
		}

        
		foreach ($poly_model['code'] as $a => $b){
			    if (isset($b['label'])){
                    $ret['code'][$a]['label'] = $UniqueHead.$b['label'].$UNIQUE_poly_index." : ";
				    continue;
				}	

                $ret['code'][$a]['operation'] = $b['operation'];
                if (!is_array($b['params'])){ 
                        continue;
                }
                $bb = $b['params'];
                foreach ($bb as $c => $d){
                        if ('SOLID_JMP_' === substr($d,0,10)){ 
                                $tmp = explode ('_',$d);
                                $d = $UniqueHead.$d.$UNIQUE_poly_index;                                                  
                        }else{
							
							if (preg_match_all('/(p_)([\d]{1,})/',$d,$mat)){
                                $mat = array_flip($mat[2]); 
								foreach ($mat as $z => $y){  									
									if (isset($org['rel'][$z])){	
										
                                                                                                        
                                        $new = reloc_inc_copy_naked($org['rel'][$z]['i'],$org['rel'][$z]['c']);
										
										$c_rel_info[$org['rel'][$z]['i']][$new] = $c_rel_info[$org['rel'][$z]['i']][$org['rel'][$z]['c']];

										

										if (is_array($poly_model['rel_reset'][$z])){
											foreach ($poly_model['rel_reset'][$z] as $zz => $yy){
												$c_rel_info[$org['rel'][$z]['i']][$new][$zz] = $yy;
											}
										}
										
										
										$c_org_params = 
										str_replace("$UniqueHead".'RELINFO_'.$sec.'_'.$org['rel'][$z]['i'].'_'.$org['rel'][$z]['c'],"$UniqueHead".'RELINFO_'.$sec.'_'.$org['rel'][$z]['i'].'_'.$new,$org['params'][$z]);
                                        
										
										
										$ret['code'][$a]['rel'][$c]['i'] = $org['rel'][$z]['i'];
										$ret['code'][$a]['rel'][$c]['c'] = $new;	
										$d = str_replace('p_'.$z,$c_org_params,$d);
									}else{  
									    $d = str_replace('p_'.$z,$org['params'][$z],$d);
									}
									if (!isset($poly_model['p_type'][$a][$c])){ 
										$poly_model['p_type'][$a][$c] = $org['p_type'][$z];
									}
									if (!isset($poly_model['p_bits'][$a][$c])){ 
										$poly_model['p_bits'][$a][$c] = $org['p_bits'][$z];
									}
								}
							}
						    if (preg_match_all('/(r_)([\d]{1,})/',$d,$mat)){ 
							    $mat = array_flip($mat[2]); 
								foreach ($mat as $z => $y){             
									if ('m' == $rand_result['p_type'][$z]){
																		   
										if (reloc_inc_copy($rand_result[$z],$old,$new)){

											
											
											$rand_rel_inc[$z] = true;
											$c_rel_info[$old[1]][$new] = $c_rel_info[$old[1]][$old[2]];
											$rand_result[$z] = str_replace("$UniqueHead".'RELINFO_'.$old[0].'_'.$old[1].'_'.$old[2],"$UniqueHead".'RELINFO_'.$old[0].'_'.$old[1].'_'.$new,$rand_result[$z]);
											$ret['code'][$a]['rel'][$c]['i'] = $old[1];
											$ret['code'][$a]['rel'][$c]['c'] = $new;
										}
									}
									
									$d = str_replace('r_'.$z,$rand_result[$z],$d);
								}
								if (!isset($poly_model['p_type'][$a][$c])){ 
									$poly_model['p_type'][$a][$c] = $rand_result['p_type'][$z];
								}
                                if (!isset($poly_model['p_bits'][$a][$c])){ 
									$poly_model['p_bits'][$a][$c] = $rand_result['p_bits'][$z];
								}
							}
					}
					$ret['code'][$a]['params'][$c] = $d;
					$ret['code'][$a]['p_type'][$c] = $poly_model['p_type'][$a][$c];
					$ret['code'][$a]['p_bits'][$c] = $poly_model['p_bits'][$a][$c];					
				}
		}
        
        if (isset($soul_usable)){
                inherit_usable_to_poly($ret,$specific_usable,$soul_usable,$poly_model['flag_forbid'],$poly_model['p_forbid'],$poly_model['r_forbid'],$rand_result,$org);
        }
    return $ret;
}


function collect_usable_poly_model($obj,$c_soul_usable,$c_poly_strength,&$ret){
    global $poly_model_index;
    global $poly_model_repo;
	global $pattern_reloc;
	global $c_rel_info;
    
	global $stack_pointer_reg;
	global $register_assort;

    $usable_poly_model = $poly_model_index[$obj['operation']];

    if (is_array($usable_poly_model)){ 
		$p_num = count($obj['p_type']);
		$usable_poly_model = $usable_poly_model[$p_num];
		if ($p_num){                    
			foreach ($obj['p_type'] as $a => $b){
				if ($b == 'r'){ 
					if (isset($usable_poly_model[$obj['params'][$a]])){
						$usable_poly_model = $usable_poly_model[$obj['params'][$a]];	
						continue;
					}
					
					if ($register_assort[$obj['params'][$a]] == $stack_pointer_reg){
						$b = 's';
					}
				}
				if ($b == 'i'){ 
					if (preg_match($pattern_reloc,$obj['params'][$a],$tmp)){										
						$tmp = explode ('_',$tmp[0]);
						$tmp_rel = 'rel'.$c_rel_info[$tmp[3]][$tmp[4]]['Type'];
						
						if (isset($usable_poly_model[$tmp_rel])){
							$usable_poly_model = $usable_poly_model[$tmp_rel];	
							continue;
						} 											
					}
				}else{
				    $b .= $obj['p_bits'][$a]; 
				}
				$usable_poly_model = $usable_poly_model[$b];    
			}
		}
		if (count($usable_poly_model)){											
			if (mt_rand(0,$c_poly_strength) == 0){ 
				return false;                           
			}
			$rand_result = array();
			if (is_array($usable_poly_model)){
				check_poly_usable ($c_soul_usable,$obj,$usable_poly_model,$rand_result);
				
				if (count($usable_poly_model)){

					$x = array_rand($usable_poly_model);

					if (isset($poly_model_repo[$obj['operation']][$usable_poly_model[$x]])){ 
						if ('int3' === $x){
							$ret = generat_poly_code($obj,$c_soul_usable,$poly_model_repo[$obj['operation']][$usable_poly_model[$x]],$rand_result[$x],true);
						}else{
							$ret = generat_poly_code($obj,$c_soul_usable,$poly_model_repo[$obj['operation']][$usable_poly_model[$x]],$rand_result[$x]);
						}
						
						soul_stack_set($ret['code'],$ret['usable']);
						return true;
					}else{
						global $output;
						global $language;						
						$output['warning'][] = $language['poly_repo_null'].$obj['operation'].'['.$x.']';						
					}
					
				}
			}
		}
	}
	return false;
}



function insert_into_list ($org,$poly_index,$asm_array,$from_soul=false){
    global $soul_writein_Dlinked_List; 
	global $s_w_Dlinked_List_index;
	global $soul_writein_Dlinked_List_start;
    global $c_user_cnf_stack_pointer_define;

    $ret = $s_w_Dlinked_List_index;

    $soul_writein_Dlinked_List[$org]['302'] = $s_w_Dlinked_List_index; 

	$c_prev = false;
	if (isset($soul_writein_Dlinked_List[$org]['p'])){
	    $c_prev = $soul_writein_Dlinked_List[$org]['p'];
	}
	$c_last = false;
	if (isset($soul_writein_Dlinked_List[$org]['n'])){
	    $c_last = $soul_writein_Dlinked_List[$org]['n'];
	}
    foreach ($asm_array as $a => $b){
		if (false === $c_prev){
			$soul_writein_Dlinked_List_start = $s_w_Dlinked_List_index;
		}else{
			$soul_writein_Dlinked_List[$c_prev]['n'] = $s_w_Dlinked_List_index;        
		    $soul_writein_Dlinked_List[$s_w_Dlinked_List_index]['p'] = $c_prev;            
		}
	    $soul_writein_Dlinked_List[$s_w_Dlinked_List_index]['c'] = $a;
		$soul_writein_Dlinked_List[$s_w_Dlinked_List_index]['poly'] = $poly_index;    
		if ($from_soul){ 
			$soul_writein_Dlinked_List[$s_w_Dlinked_List_index]['soul'] = true;    		
		}
		if (isset($b['label'])){
			$soul_writein_Dlinked_List[$s_w_Dlinked_List_index]['label'] = $b['label'];
		}elseif (is_effect_ipsp($b,1,$c_user_cnf_stack_pointer_define)){
				$soul_writein_Dlinked_List[$s_w_Dlinked_List_index]['ipsp'] = true;
		}
		$c_prev = $s_w_Dlinked_List_index;
		$s_w_Dlinked_List_index ++;
	}
	if (false !== $c_last){
		$soul_writein_Dlinked_List[$c_prev]['n'] = $c_last;
		$soul_writein_Dlinked_List[$c_last]['p'] = $c_prev;
	}
    return $ret;
}




function poly_start_single ($list_index,$soul_usable,$AsmResultArray,$poly_strength){    
    global $soul_writein_Dlinked_List;
	global $UNIQUE_poly_index;   
	global $poly_result_array;
	global $poly_result_reverse_array;
	global $s_w_Dlinked_List_index;

	$usable_poly_model = array();

   	$poly_sub_tree = $s_w_Dlinked_List_index;  

	if ($poly_strength){
	    if (collect_usable_poly_model($AsmResultArray,$soul_usable,$poly_strength,$usable_poly_model)){
			
			$poly_result_reverse_array[$UNIQUE_poly_index]['i'] = $list_index;                
			$poly_result_reverse_array[$UNIQUE_poly_index]['n'] = count($usable_poly_model['code']);                
			
			insert_into_list ($list_index,$UNIQUE_poly_index,$usable_poly_model['code']);
			
			$poly_result_array[$UNIQUE_poly_index] = $usable_poly_model;
			$UNIQUE_poly_index ++;
			
			
		}else{
		    return false;
		}
        $poly_strength --; 
	}
	

	
    
	while (true){
		$c_list = $soul_writein_Dlinked_List; 
		if (!isset($c_list[$poly_sub_tree])){
		    break;
		}    
		while (isset($c_list[$poly_sub_tree])){
			if (!isset($c_list[$poly_sub_tree]['label'])){		    
				if ($poly_strength){
					$d = $c_list[$poly_sub_tree]['poly'];
					$e = $c_list[$poly_sub_tree]['c'];
					$f = $poly_result_array[$d]['code'][$e];
					
					
					if (collect_usable_poly_model($f,$poly_result_array[$d]['usable'][$e],$poly_strength,$usable_poly_model)){					
						
						$poly_result_reverse_array[$UNIQUE_poly_index]['i'] = $poly_sub_tree;
						$poly_result_reverse_array[$UNIQUE_poly_index]['n'] = count($usable_poly_model['code']);
						
						insert_into_list ($poly_sub_tree,$UNIQUE_poly_index,$usable_poly_model['code']);						
						$poly_result_array[$UNIQUE_poly_index] = $usable_poly_model;
						$UNIQUE_poly_index ++;

						
						
					}
				}
			}
			$poly_sub_tree++;
		}
		$poly_strength --;
	}
    return true;
}







function poly_start ($objs,$echo = false){ 
    global $poly_result_array;
	global $bone_result_array;
	global $meat_result_array;
	global $c_Asm_Result;
	global $c_soul_usable;
	global $soul_writein_Dlinked_List;
	global $UNIQUE_poly_index;
	global $poly_result_reverse_array;

	foreach ($objs as $a){
        $b = $soul_writein_Dlinked_List[$a];

		$from_soul = false;		
		
		if (isset($b['label'])){          
			continue;
		}elseif (isset($b['poly'])){      
		
		    $c_obj    = $poly_result_array[$b['poly']]['code'][$b['c']];
			$c_usable = $poly_result_array[$b['poly']]['usable'][$b['c']];
			if (true === $b['soul']){
			    $from_soul = true;	
			}
		}elseif (isset($b['bone'])){ 
		
		    $c_obj    = $bone_result_array[$b['bone']]['code'][$b['c']];
			$c_usable = $bone_result_array[$b['bone']]['usable'][$b['c']];
		}elseif (isset($b['meat'])){ 
		
		    $c_obj    = $meat_result_array[$b['meat']]['code'][$b['c']];
			$c_usable = $meat_result_array[$b['meat']]['usable'][$b['c']];
		}else{                                                    

		    $from_soul = true;	
		
		    $c_obj    = $c_Asm_Result[$b['c']];
			$c_usable = $c_soul_usable[$b['c']];			

		}
		
		

		$c_poly_result = array();		

		if (collect_usable_poly_model($c_obj,$c_usable,100,$c_poly_result)){ 
			
			$poly_result_reverse_array[$UNIQUE_poly_index]['i'] = $a;                
			$poly_result_reverse_array[$UNIQUE_poly_index]['n'] = count($c_poly_result['code']);                
            
			
			$insert_List_index = insert_into_list ($a,$UNIQUE_poly_index,$c_poly_result['code'],$from_soul);
			$poly_result_array[$UNIQUE_poly_index] = $c_poly_result;
			$UNIQUE_poly_index ++;            
			
		}	
	}
}






function reloc_inc_copy($obj,&$old,&$new){
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
				$new ++; 
		}
		return true;    
	}
    return false;
}

?>