<?php









function resize_rel_jmp_array($rel_jmp_range_key){

	
	

    $old_rel_jmp_range = ConstructionDlinkedListOpt::ReadRollingRelJmpRange();
    
    while (!empty($rel_jmp_range_key)){
		$a = array_pop($rel_jmp_range_key);
		$b = ConstructionDlinkedListOpt::readRelJmpRange($a);

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
        $c_change = $tmp['len'] - ConstructionDlinkedListOpt::getDlinkedList($a,'len');
		if ($c_change){                                                  

			ConstructionDlinkedListOpt::OplenIncrease($c_change);
			
			
			
			ConstructionDlinkedListOpt::setDlinkedList($tmp['len'],$a,'len');
			
			
			
			$tmp = ConstructionDlinkedListOpt::ReadRelJmpPointer($a);
			foreach ($tmp as $z => $y){
				if ($y & 4){
					ConstructionDlinkedListOpt::increaseRelJmpRange($z,$c_change); 
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















function  reset_rel_jmp_array($discard_objs = false,$backup_List=false,$c_List_start=0){  

	$in_cache_last_src   = false;
	$in_cache_last_label = false;
	$in_cache_last_whole = false;

	$in_cache_src   = array();
	$in_cache_label = array();


	if (false === $discard_objs){ 
	    $c_unit = $c_List_start;
		$end_unit = false;			
	}else{
		
		init_in_cache_array($discard_objs,$backup_List,$c_List_start,$c_unit,$end_unit,$in_cache_last_src,$in_cache_last_label,$in_cache_last_whole,$in_cache_src,$in_cache_label);



		
	}   

	
	
	while (true){
		
        if ($c_unit === $end_unit){
		    break;
		}

        if (!ConstructionDlinkedListOpt::issetDlinkedListUnit($c_unit,'len')){ 
		    $tmp = get_addition_List_info($c_unit,true,true);
			ConstructionDlinkedListOpt::setDlinkedList($tmp['len'],$c_unit,'len');
			if (isset($tmp['rel_jmp'])){
				ConstructionDlinkedListOpt::setDlinkedList($tmp['rel_jmp'],$c_unit,'rel_jmp');
			}
		}

        ConstructionDlinkedListOpt::OplenIncrease(ConstructionDlinkedListOpt::getDlinkedList($c_unit,'len'));
		
		if (false !== $in_cache_last_whole){ 
		    foreach ($in_cache_last_whole as $a){
			    ConstructionDlinkedListOpt::SetRelJmpPointer($c_unit,$a,1 | 2 | 4);    
				ConstructionDlinkedListOpt::setRelJmpRange(1 | 2 | 4,$a,'unit',$c_unit);
				
				ConstructionDlinkedListOpt::increaseRelJmpRange($a,ConstructionDlinkedListOpt::getDlinkedList($c_unit,'len'));
				
				if (ConstructionDlinkedListOpt::outRelJmpRange($a)){
				    return false;
				}
			}
		}

        if (ConstructionDlinkedListOpt::issetDlinkedListUnit($c_unit,'label')){  
			$label = ConstructionDlinkedListOpt::getDlinkedList($c_unit,'label');
		    if (is_array($in_cache_src[$label])){
				foreach ($in_cache_src[$label] as $a => $b){
					ConstructionDlinkedListOpt::setRelJmpRange($b,$a);
                    ConstructionDlinkedListOpt::setRelJmpRange(1 | 4,$a,'unit',$c_unit);
					
					if (ConstructionDlinkedListOpt::outRelJmpRange($a)){
						return false;
					}
					
					ConstructionDlinkedListOpt::RelJmpRange2Pointer($a);
				}
				unset($in_cache_src[$label]);
			}
            $in_cache_label[$label]['unit'][$c_unit] = 2 | 4;
		}

        if (ConstructionDlinkedListOpt::issetDlinkedListUnit($c_unit,'rel_jmp')){ 
			$label = ConstructionDlinkedListOpt::getDlinkedList($c_unit,'rel_jmp','label');
			if (isset($in_cache_label[$label])){
			    
				ConstructionDlinkedListOpt::setRelJmpRange(ConstructionDlinkedListOpt::getDlinkedList($c_unit,'rel_jmp','max'),$c_unit,'max');				
                
				ConstructionDlinkedListOpt::setRelJmpRange(ConstructionDlinkedListOpt::getDlinkedList($c_unit,'rel_jmp','label'),$c_unit,'label');
			    
				ConstructionDlinkedListOpt::setRelJmpRange($in_cache_label[$label]['range'],$c_unit,'range');
				
                ConstructionDlinkedListOpt::increaseRelJmpRange($c_unit,ConstructionDlinkedListOpt::getDlinkedList($c_unit,'len'));
				
				ConstructionDlinkedListOpt::setRelJmpRange($in_cache_label[$label]['unit'],$c_unit,'unit');
			    
				ConstructionDlinkedListOpt::setRelJmpRange(1 | 4,$c_unit,'unit',$c_unit);
                
				if (true === ConstructionDlinkedListOpt::outRelJmpRange($c_unit)){
					return false;
				}
				ConstructionDlinkedListOpt::RelJmpRange2Pointer($c_unit);
			}else{
                $in_cache_src[$label][$c_unit]          = ConstructionDlinkedListOpt::getDlinkedList($c_unit,'rel_jmp');
                $in_cache_src[$label][$c_unit]['range'] = 0;
				$in_cache_src[$label][$c_unit]['unit'][$c_unit] = 2;
				
			}
		}

		
		$tmp = $in_cache_label;
		foreach ($tmp as $label => $a){
			if (!isset($in_cache_label[$label]['unit'][$c_unit])){
				$in_cache_label[$label]['range'] += ConstructionDlinkedListOpt::getDlinkedList($c_unit,'len');
				$in_cache_label[$label]['unit'][$c_unit] = 1 | 2 | 4;
			}
		}
        
		$tmp = $in_cache_src;
		foreach ($tmp as $label => $a){
		    foreach ($a as $b => $c){
				if (!isset($in_cache_src[$label][$b]['unit'][$c_unit])){	
					$in_cache_src[$label][$b]['range'] += ConstructionDlinkedListOpt::getDlinkedList($c_unit,'len');
					$in_cache_src[$label][$b]['unit'][$c_unit] = 1 | 2 | 4;
				}
			}
		}

		
		if (ConstructionDlinkedListOpt::issetDlinkedListUnit($c_unit,'n')){
			$c_unit = ConstructionDlinkedListOpt::getDlinkedList($c_unit,'n');
		}else{
		    break;
		}
	}




    
	if (false !== $in_cache_last_src){    
		foreach ($in_cache_last_src as $label => $v){
			if (isset($in_cache_label[$label])){
				foreach ($v as $a => $b){
					ConstructionDlinkedListOpt::setRelJmpRange($b,$a);
					
					ConstructionDlinkedListOpt::increaseRelJmpRange($a,$in_cache_label[$label]['range']);
					
                    if (ConstructionDlinkedListOpt::outRelJmpRange($a)){
						return false;
					}
					
					ConstructionDlinkedListOpt::RelJmpRange2Pointer($a);
				
					foreach ($in_cache_label[$label]['unit'] as $c => $d){
						ConstructionDlinkedListOpt::setRelJmpRange($d,$a,'unit',$c);
						ConstructionDlinkedListOpt::SetRelJmpPointer($c,$a,$d);
					}
				}
			}else{ 
				$log = array();
				$log['in_cache_last_src'] = $in_cache_last_src;
				$log['in_cache_label']    = $in_cache_label;
				GeneralFunc::internal_log_save('still have $in_cache_src here till func end : [func reset_rel_jmp_array] ',$log);
				return false;
				
				
			    
				
			}
		}	    
	}

	if (false !== $in_cache_last_label){  
		foreach ($in_cache_last_label as $label => $v){
		    if (isset($in_cache_src[$label])){
				foreach ($in_cache_src[$label] as $a => $b){
				    ConstructionDlinkedListOpt::setRelJmpRange($b,$a);
                    
					ConstructionDlinkedListOpt::increaseRelJmpRange($a,$in_cache_last_label[$label]['range']);
                    
					if (ConstructionDlinkedListOpt::outRelJmpRange($a)){
						return false;
					}
					
					ConstructionDlinkedListOpt::RelJmpRange2Pointer($a);

					foreach ($in_cache_last_label[$label]['unit'] as $c => $d){
						ConstructionDlinkedListOpt::setRelJmpRange($d,$a,'unit',$c);
						ConstructionDlinkedListOpt::SetRelJmpPointer($c,$a,$d);
					}
				}						
				unset($in_cache_src[$label]);
			}else{ 
				$log = array();
				$log['in_cache_last_label'] = $in_cache_last_label;
				$log['label']               = $label;
				$log['in_cache_src']        = $in_cache_src;
				GeneralFunc::internal_log_save('fail to search Label (of $in_cache_last_label) in $in_cache_src : [func reset_rel_jmp_array] ',$log);
				return false;
				
				
			}
		}
	}

	if (!empty($in_cache_src)){  
		$log = array();
        $log['in_cache_last_label'] = $in_cache_last_label;
		$log['in_cache_src']        = $in_cache_src;
		GeneralFunc::internal_log_save('still have $in_cache_src here till func end : [func reset_rel_jmp_array] ',$log);
		return false;
		
		
		
	}

    return true;
}




function init_in_cache_array($discard_objs,$backup_List,$c_List_start,&$c_unit,&$end_unit,&$in_cache_last_src,&$in_cache_last_label,&$in_cache_last_whole,&$in_cache_src,&$in_cache_label){



		
		
		$del_array = false;
		foreach ($discard_objs as $b){
			ConstructionDlinkedListOpt::OplenIncrease(ConstructionDlinkedListOpt::getDlinkedList($b,'len'),false);			
			$tmp = ConstructionDlinkedListOpt::ReadRelJmpPointer($b);
			if (is_array($tmp)){
			    foreach ($tmp as $c => $d){
				    ConstructionDlinkedListOpt::unsetRelJmpRange($c,'unit',$b);
					if ($d & 4){
						
						ConstructionDlinkedListOpt::increaseRelJmpRange($c,ConstructionDlinkedListOpt::getDlinkedList($b,'len'),false);
					}
					if ($d !== (1 | 2 | 4)){    
						$del_array[$c] = $c;
					}
				}				
				ConstructionDlinkedListOpt::UnsetRelJmpPointer($b);
			}
		}


		
		
		$reserve_start_pointer = array();
		$reserve_last_pointer  = array();
		$tmp = reset($discard_objs);
		
		if (isset($backup_List[$tmp]['p'])){
            $tmp_tmp = ConstructionDlinkedListOpt::ReadRelJmpPointer($backup_List[$tmp]['p']);
			if (is_array($tmp_tmp)){
				foreach ($tmp_tmp as $a => $b){
					if ($b & 2){
						$reserve_start_pointer[$a] = $b;	
					}
				}			
			}
			$c_unit = ConstructionDlinkedListOpt::getDlinkedList($backup_List[$tmp]['p'],'n');
		}else{
			$c_unit = $c_List_start;       
		}
		
		$tmp = end($discard_objs);
		
		if (isset($backup_List[$tmp]['n'])){
			$tmp_tmp = ConstructionDlinkedListOpt::ReadRelJmpPointer($backup_List[$tmp]['p']);
			if (is_array($tmp_tmp)){
				foreach ($tmp_tmp as $a => $b){
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
				$tmp_tmp = $rel_jmp_range[$a]['label'];
			    if (in_array($a,$discard_objs)){
				    $in_cache_label[$tmp_tmp] = ConstructionDlinkedListOpt::readRelJmpRange($a);
				}else{
				    $in_cache_src[$tmp_tmp][$a] = ConstructionDlinkedListOpt::readRelJmpRange($a);
				}
			}
		}	

		foreach ($reserve_last_pointer as $a => $b){
			$tmp_tmp = $rel_jmp_range[$a]['label'];
		    if (in_array($a,$discard_objs)){
			    $in_cache_last_label[$tmp_tmp] = ConstructionDlinkedListOpt::readRelJmpRange($a);
			}else{
				$in_cache_last_src[$tmp_tmp][$a] = ConstructionDlinkedListOpt::readRelJmpRange($a);
			}
		}

		
		
        if (false !== $del_array){
		    foreach ($del_array as $a){
				
				foreach (ConstructionDlinkedListOpt::readRelJmpRange($a,'unit') as $c => $d){					
				    ConstructionDlinkedListOpt::UnsetRelJmpPointer($c,$a);
				}
			    ConstructionDlinkedListOpt::unsetRelJmpRange($a);
			}
		}







		return true;        

}






function get_addition_List_info($unit,$get_len=false,$get_rel_jmp=false){

	global $UniqueHead;

	global $range_limit_static_jmp;

	$ret = false; 


    $c_opt = ConstructionDlinkedListOpt::getCode_from_DlinkedList($unit);
	if (false === $c_opt){
	    $ret = array('len' => 0);
	}else{
		
		if ($get_rel_jmp){ 
		    if ((isset($range_limit_static_jmp[$c_opt['operation']])) and (0 === strpos($c_opt['params'][0],"$UniqueHead".'SOLID_JMP_'))){ 
			    $ret['rel_jmp']['max'] = $range_limit_static_jmp[$c_opt['operation']];    
				$ret['rel_jmp']['label'] = $c_opt['params'][0].' : ';
			}
		}

		if ($get_len){   
			if (ConstructionDlinkedListOpt::issetRelJmpRange($unit)){     
	            $c_opt['range'] = ConstructionDlinkedListOpt::readRelJmpRange($unit,'range');
			}
			
		    $ret['len'] = code_len($c_opt);
		}
		
	}

	return $ret;

}





?>