<?php


if(!defined('UNEST.ORG')) {
    exit('Access Denied');
}



function dynamic_insert_dealer($dynamic_insert_array,&$StandardAsmResultArray){
    global $output;
	global $language;
	global $UniqueHead;

	$ret = array();
	foreach ($StandardAsmResultArray as $sec_id => $a){

		

		if (!isset($dynamic_insert_array[$sec_id])){
		    continue;
		}
		$c_sec_rva = false; 
		foreach ($a as $line => $contents){
			if (false === $c_sec_rva){
			    $c_sec_rva = $line;
				$prev_line = $line;
			}
            
			$c_dynamic_insert = $dynamic_insert_array[$sec_id];
            foreach ($c_dynamic_insert as $z => $y){
				if (($line - $c_sec_rva > $z) or ($line - $c_sec_rva >= $z + $y['size'])){					
					if ($line - $c_sec_rva === $z + $y['size']){ 
						if ((1 == $y['size']) or (2 == $y['size']) or (4 == $y['size'])){
							
							
							
							$last_int_param_no = false;
							foreach ($StandardAsmResultArray[$sec_id][$prev_line]['p_type'] as $p_number => $type){ 
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
								
								unset ($dynamic_insert_array[$sec_id][$z]);
							}
						}
					}
				}				
			}

            $prev_line = $line;
			
			
		}
		
		if (!empty($dynamic_insert_array[$sec_id])){ 
			foreach ($dynamic_insert_array[$sec_id] as $z => $y){
				$output['warning'][] = 'fail to location the dynamic insert : '.$y['org'].' - '.$y['size'];    
			}
		}
	}

	
    return $ret;
}


function format_protect_section($p_sec_abs,&$AsmResultArray,&$output,$language){    
    $boundary = false;
	$tmp = $AsmResultArray;
	foreach ($tmp as $a => $b){
	    if (isset($p_sec_abs[$a])){
		    $boundary = $p_sec_abs[$a];	
			$deal_index = $a;                       
		}
		
		if ($boundary === $a){
		    $boundary = false;
		}
          
		if (false !== $boundary){		    
			$deal_contents[$deal_index][] = $a;     
		}	
	}
    
	if (is_array($deal_contents)){
		
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
			if ($AsmResultArray[$index]['len'] !== count($tmp)){ 
				$output['error'][] = $language['fail_split_protect_sec'];
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





function is_overlap_section($section){
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


function bind_dynamic_insert_2_sec($dynamic_section,$section,$language,&$output){
	$ret = false;
    $tmp = $dynamic_section;
	foreach ($section as $a => $b){
	    foreach ($tmp as $c => $d){
		    if (($c >= $b['PointerToRawData']) and ($c < $b['PointerToRawData'] + $b['SizeOfRawData'])){
			    $rva = $c - $b['PointerToRawData'];
				if ($rva + $d > $b['SizeOfRawData']){ 
				    $output['error'][] = $language['dynamic_insert_overflow_sec'].$c.' - '.$d;
				    return false;
				}else{
					$ret[$a][$rva]['size'] = $d;
					$ret[$a][$rva]['org']  = $c;
					unset($dynamic_section[$c]);
				}
			}
		}
	}

	if (!empty($dynamic_section)){ 
	    foreach ($dynamic_section as $a => $b){
		     $output['notice'][] = $language['dynamic_insert_will_ignore'].$a.' - '.$b;
		}
		var_dump ($dynamic_section);
	}
	return $ret;
}



function bind_protect_section_2_sec($protect_section,$section,$language,&$output){
    $ret = false;
	
	foreach ($section as $a => $b){
	    foreach ($protect_section as $c => $d){
		    if (($c >= $b['PointerToRawData']) and ($c < $b['PointerToRawData'] + $b['SizeOfRawData'])){
			    $rva = $c - $b['PointerToRawData'];
				if ($rva + $d > $b['SizeOfRawData']){ 
				    $output['error'][] = $language['protect_section_overflow_sec'].$c.' - '.$d;
				    return false;
				}else{
					$ret[$a][$rva] = $d;
				}
			}
		}
	}
    
	
	
	return $ret;
    
}


?>