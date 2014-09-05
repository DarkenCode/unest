<?php









function resize_rel_jmp_array($rel_jmp_range_key,$old_rel_jmp_range){
    global $soul_writein_Dlinked_List;
    global $c_rel_jmp_range;
	global $c_rel_jmp_pointer;
    global $c_usable_oplen;

	
	

    
    while (!empty($rel_jmp_range_key)){
		$a = array_pop($rel_jmp_range_key);
		$b = $c_rel_jmp_range[$a];

		if (isset($old_rel_jmp_range[$a])){
			if ($old_rel_jmp_range[$a]['range'] == $b['range']){  
			    
				
				
				continue;
			}else{                                                
				                                                 
				if ((($old_rel_jmp_range[$a]['range'] <= 127) and ($b['range'] <= 127)) or (($old_rel_jmp_range[$a]['range'] > 127) and ($b['range'] > 127))){
					
					
					
					continue;
				}
			    
				
				
			}
		}else{                                                    
			
			
		}
		
        $tmp = get_addition_List_info($a,true,false);
		$c_change = $tmp['len'] - $soul_writein_Dlinked_List[$a]['len']; 
		if ($c_change){                                                  
			if (false !== $c_usable_oplen){
				$c_usable_oplen -= $c_change;
			}
			
			
			$soul_writein_Dlinked_List[$a]['len'] = $tmp['len'];
			
			
			
			foreach ($c_rel_jmp_pointer[$a] as $z => $y){
				if ($y & 4){
					$c_rel_jmp_range[$z]['range'] += $c_change;
					if (!isset($rel_jmp_range_key[$z])){
						
						$rel_jmp_range_key[$z] = $z;
					}
				}
			}		
		}else{
		    
		}
	}
    


	return true;
}















function  reset_rel_jmp_array(&$rel_jmp_range,&$rel_jmp_pointer,$discard_objs = false,$backup_List=false,$c_List_start=0){
    
    global $soul_writein_Dlinked_List;
	global $c_usable_oplen;  

	$in_cache_last_src   = false;
	$in_cache_last_label = false;
	$in_cache_last_whole = false;

	$in_cache_src   = array();
	$in_cache_label = array();


	if (false === $discard_objs){ 
	    $c_unit = $c_List_start;
		$end_unit = false;			
	}else{
		
		init_in_cache_array($discard_objs,$backup_List,$rel_jmp_range,$rel_jmp_pointer,$c_List_start,$c_unit,$end_unit,$in_cache_last_src,$in_cache_last_label,$in_cache_last_whole,$in_cache_src,$in_cache_label,$c_usable_oplen);



		
	}   

	
	
	while (true){
		
        if ($c_unit === $end_unit){
		    break;
		}

		if (!isset($soul_writein_Dlinked_List[$c_unit]['len'])){ 
		    $tmp = get_addition_List_info($c_unit,true,true);
			$soul_writein_Dlinked_List[$c_unit]['len'] = $tmp['len'];
			if (isset($tmp['rel_jmp'])){
				$soul_writein_Dlinked_List[$c_unit]['rel_jmp'] = $tmp['rel_jmp'];
			}
		}

		if (false !== $c_usable_oplen){
			$c_usable_oplen -= $soul_writein_Dlinked_List[$c_unit]['len'];
		}
		
		if (false !== $in_cache_last_whole){ 
		    foreach ($in_cache_last_whole as $a){
			    $rel_jmp_pointer[$c_unit][$a] = 1 | 2 | 4;    
				$rel_jmp_range[$a]['unit'][$c_unit] = 1 | 2 | 4;
				$rel_jmp_range[$a]['range'] += $soul_writein_Dlinked_List[$c_unit]['len'];
				
				if (false !== $rel_jmp_range[$a]['max']){
					if ($rel_jmp_range[$a]['max'] < $rel_jmp_range[$a]['range']){
						return false;
					}
				}
			}
		}

        if (isset($soul_writein_Dlinked_List[$c_unit]['label'])){  
			$label = $soul_writein_Dlinked_List[$c_unit]['label'];
		    if (is_array($in_cache_src[$label])){
				foreach ($in_cache_src[$label] as $a => $b){
					$rel_jmp_range[$a] = $b;
                    $rel_jmp_range[$a]['unit'][$c_unit] = 1 | 4;
					
					if (false !== $rel_jmp_range[$a]['max']){
						if ($rel_jmp_range[$a]['max'] < $rel_jmp_range[$a]['range']){
							return false;
						}
					}
					foreach ($rel_jmp_range[$a]['unit'] as $c => $d){
					    $rel_jmp_pointer[$c][$a] = $d;
					}
				}
				unset($in_cache_src[$label]);
			}
            $in_cache_label[$label]['unit'][$c_unit] = 2 | 4;
		}

		if (isset($soul_writein_Dlinked_List[$c_unit]['rel_jmp'])){ 
			$label = $soul_writein_Dlinked_List[$c_unit]['rel_jmp']['label'];
			if (isset($in_cache_label[$label])){
			    $rel_jmp_range[$c_unit]['max']    = $soul_writein_Dlinked_List[$c_unit]['rel_jmp']['max'];
                $rel_jmp_range[$c_unit]['label']  = $soul_writein_Dlinked_List[$c_unit]['rel_jmp']['label'];
			    $rel_jmp_range[$c_unit]['range']  = $in_cache_label[$label]['range'];
				$rel_jmp_range[$c_unit]['range'] += $soul_writein_Dlinked_List[$c_unit]['len'];
				$rel_jmp_range[$c_unit]['unit']   = $in_cache_label[$label]['unit'];
			    $rel_jmp_range[$c_unit]['unit'][$c_unit] = 1 | 4;
                
				if (false !== $rel_jmp_range[$c_unit]['max']){
				    if ($rel_jmp_range[$c_unit]['max'] < $rel_jmp_range[$c_unit]['range']){
					    return false;
					}
				}
				foreach ($rel_jmp_range[$c_unit]['unit'] as $c => $d){
					    $rel_jmp_pointer[$c][$c_unit] = $d;
				}
			}else{
			    $in_cache_src[$label][$c_unit]          = $soul_writein_Dlinked_List[$c_unit]['rel_jmp'];
                $in_cache_src[$label][$c_unit]['range'] = 0;
				$in_cache_src[$label][$c_unit]['unit'][$c_unit] = 2;
				
			}
		}

		
		$tmp = $in_cache_label;
		foreach ($tmp as $label => $a){
			if (!isset($in_cache_label[$label]['unit'][$c_unit])){
			    $in_cache_label[$label]['range'] += $soul_writein_Dlinked_List[$c_unit]['len'];			
				$in_cache_label[$label]['unit'][$c_unit] = 1 | 2 | 4;
			}
		}
        
		$tmp = $in_cache_src;
		foreach ($tmp as $label => $a){
		    foreach ($a as $b => $c){
				if (!isset($in_cache_src[$label][$b]['unit'][$c_unit])){
                    $in_cache_src[$label][$b]['range'] += $soul_writein_Dlinked_List[$c_unit]['len'];				
					$in_cache_src[$label][$b]['unit'][$c_unit] = 1 | 2 | 4;
				}
			}
		}

		
	    if (isset($soul_writein_Dlinked_List[$c_unit]['n'])){
		    $c_unit = $soul_writein_Dlinked_List[$c_unit]['n'];			
		}else{
		    break;
		}
	}




    
	if (false !== $in_cache_last_src){    
		foreach ($in_cache_last_src as $label => $v){
			if (isset($in_cache_label[$label])){
				foreach ($v as $a => $b){
					$rel_jmp_range[$a] = $b;
					$rel_jmp_range[$a]['range'] += $in_cache_label[$label]['range'];
					
					if (false !== $rel_jmp_range[$a]['max']){
						if ($rel_jmp_range[$a]['max'] < $rel_jmp_range[$a]['range']){
							return false;
						}
					}
					foreach ($rel_jmp_range[$a]['unit'] as $c => $d){
						$rel_jmp_pointer[$c][$a] = $d;
					}
					foreach ($in_cache_label[$label]['unit'] as $c => $d){
						$rel_jmp_range[$a]['unit'][$c] = $d;
						$rel_jmp_pointer[$c][$a] = $d;
					}
				}
			}else{ 
				$log = array();
				$log['in_cache_last_src'] = $in_cache_last_src;
				$log['in_cache_label']    = $in_cache_label;
				internal_log_save('still have $in_cache_src here till func end : [func reset_rel_jmp_array] ',$log);
				return false;
				
				
			    
				
			}
		}	    
	}

	if (false !== $in_cache_last_label){  
		foreach ($in_cache_last_label as $label => $v){
		    if (isset($in_cache_src[$label])){
				foreach ($in_cache_src[$label] as $a => $b){
				    $rel_jmp_range[$a] = $b;
                    $rel_jmp_range[$a]['range'] += $in_cache_last_label[$label]['range'];
                    
					if (false !== $rel_jmp_range[$a]['max']){
						if ($rel_jmp_range[$a]['max'] < $rel_jmp_range[$a]['range']){
							return false;
						}
					}
					foreach ($rel_jmp_range[$a]['unit'] as $c => $d){
						$rel_jmp_pointer[$c][$a] = $d;
					}
					foreach ($in_cache_last_label[$label]['unit'] as $c => $d){
						$rel_jmp_range[$a]['unit'][$c] = $d;
						$rel_jmp_pointer[$c][$a] = $d;
					}
				}						
				unset($in_cache_src[$label]);
			}else{ 
				$log = array();
				$log['in_cache_last_label'] = $in_cache_last_label;
				$log['label']               = $label;
				$log['in_cache_src']        = $in_cache_src;
				internal_log_save('fail to search Label (of $in_cache_last_label) in $in_cache_src : [func reset_rel_jmp_array] ',$log);
				return false;
				
				
			}
		}
	}

	if (!empty($in_cache_src)){  
		$log = array();
        $log['in_cache_last_label'] = $in_cache_last_label;
		$log['in_cache_src']        = $in_cache_src;
		internal_log_save('still have $in_cache_src here till func end : [func reset_rel_jmp_array] ',$log);
		return false;
		
		
		
	}

    return true;
}




function init_in_cache_array($discard_objs,$backup_List,&$rel_jmp_range,&$rel_jmp_pointer,$c_List_start,&$c_unit,&$end_unit,&$in_cache_last_src,&$in_cache_last_label,&$in_cache_last_whole,&$in_cache_src,&$in_cache_label,&$c_usable_oplen){

        global $soul_writein_Dlinked_List;


		
		
		$del_array = false;
		foreach ($discard_objs as $b){
			if (false !== $c_usable_oplen){
				$c_usable_oplen += $soul_writein_Dlinked_List[$b]['len'];	
			}
			if (is_array($rel_jmp_pointer[$b])){
			    foreach ($rel_jmp_pointer[$b] as $c => $d){
				    unset ($rel_jmp_range[$c]['unit'][$b]);
					if ($d & 4){
					    $rel_jmp_range[$c]['range'] -= $soul_writein_Dlinked_List[$b]['len'];					
					}
					if ($d !== (1 | 2 | 4)){    
						$del_array[$c] = $c;
					}
				}				
				unset ($rel_jmp_pointer[$b]);
			}
		}


		
		
		$reserve_start_pointer = array();
		$reserve_last_pointer  = array();
		$tmp = reset($discard_objs);
		
		if (isset($backup_List[$tmp]['p'])){
			if (is_array($rel_jmp_pointer[$backup_List[$tmp]['p']])){
				foreach ($rel_jmp_pointer[$backup_List[$tmp]['p']] as $a => $b){
					if ($b & 2){
						$reserve_start_pointer[$a] = $b;	
					}
				}			
			}
			$c_unit = $soul_writein_Dlinked_List[$backup_List[$tmp]['p']]['n'];
		}else{
			$c_unit = $c_List_start;       
		}
		
		$tmp = end($discard_objs);
		
		if (isset($backup_List[$tmp]['n'])){
			if (is_array($rel_jmp_pointer[$backup_List[$tmp]['n']])){
				foreach ($rel_jmp_pointer[$backup_List[$tmp]['n']] as $a => $b){
					if ($b & 1){
						$reserve_last_pointer[$a] = $b;	
					}
				}
			}
			$end_unit = $backup_List[$tmp]['n'];
		}else{
			$end_unit = false;               
		}
   

		
		
		
		
		
        $tmp = $reserve_start_pointer;
		foreach ($tmp  as $a => $b){
		    if (isset($reserve_last_pointer[$a])){
                $in_cache_last_whole[$a] = $a;
			    unset ($reserve_last_pointer[$a]);
				unset ($reserve_start_pointer[$a]);
			}else{
			    if (in_array($a,$discard_objs)){
				    $in_cache_label[$rel_jmp_range[$a]['label']] = $rel_jmp_range[$a];
				}else{
				    $in_cache_src[$rel_jmp_range[$a]['label']][$a] = $rel_jmp_range[$a];
				}
			}
		}	

		foreach ($reserve_last_pointer as $a => $b){
		    if (in_array($a,$discard_objs)){
			    $in_cache_last_label[$rel_jmp_range[$a]['label']] = $rel_jmp_range[$a];
			}else{
				$in_cache_last_src[$rel_jmp_range[$a]['label']][$a] = $rel_jmp_range[$a];
			}
		}

		
		
        if (false !== $del_array){
		    foreach ($del_array as $a){
				foreach ($rel_jmp_range[$a]['unit'] as $c => $d){
				    unset ($rel_jmp_pointer[$c][$a]);
				}
			    unset ($rel_jmp_range[$a]);
			}
		}







		return true;        

}






function get_addition_List_info($unit,$get_len=false,$get_rel_jmp=false){
    
	global $soul_writein_Dlinked_List;

	global $poly_result_array;
	global $bone_result_array;
	global $meat_result_array;
	global $c_Asm_Result;

	global $UniqueHead;

	global $range_limit_static_jmp;

	$ret = false; 

    if (isset($soul_writein_Dlinked_List[$unit]['label'])){ 

    	$ret = array('len' => 0);

	}else{
		$c_opt = false;

		
		if (isset($soul_writein_Dlinked_List[$unit]['poly'])){
			$c_opt = $poly_result_array[$soul_writein_Dlinked_List[$unit]['poly']]['code'][$soul_writein_Dlinked_List[$unit]['c']];	
		}elseif (isset($soul_writein_Dlinked_List[$unit]['bone'])){
			$c_opt = $bone_result_array[$soul_writein_Dlinked_List[$unit]['bone']]['code'][$soul_writein_Dlinked_List[$unit]['c']];
		}elseif (isset($soul_writein_Dlinked_List[$unit]['meat'])){
			$c_opt = $meat_result_array[$soul_writein_Dlinked_List[$unit]['meat']]['code'][$soul_writein_Dlinked_List[$unit]['c']];
		}else{
			$c_opt = $c_Asm_Result[$soul_writein_Dlinked_List[$unit]['c']];
		}
		if ($get_rel_jmp){ 
		    if ((isset($range_limit_static_jmp[$c_opt['operation']])) and (0 === strpos($c_opt['params'][0],"$UniqueHead".'SOLID_JMP_'))){ 
			    $ret['rel_jmp']['max'] = $range_limit_static_jmp[$c_opt['operation']];    
				$ret['rel_jmp']['label'] = $c_opt['params'][0].' : ';
			}
		}

		if ($get_len){   
			global $c_rel_jmp_range;
			if (isset($c_rel_jmp_range[$unit]['range'])){     
	            $c_opt['range'] = $c_rel_jmp_range[$unit]['range'];		
			}
			
		    $ret['len'] = code_len($c_opt);
		}
		
	}

	return $ret;

}





?>