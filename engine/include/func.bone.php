<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}


$UNIQUE_bone_index = 1; 



function remove_ipsp_from_usable(&$usable_mem){
    global $all_valid_mem_opt_index;
	global $c_user_cnf_stack_pointer_define;
	if (is_array($usable_mem)){
		$tmp = $usable_mem;
		foreach ($tmp as $i => $a){		
			if (preg_match('/'."$c_user_cnf_stack_pointer_define".'/',$all_valid_mem_opt_index[$a]['code'])){
				unset ($usable_mem[$i]);				
			}			
		}
	}
}

function remove_ipsp_from_usable_list($start,$end){
	global $c_soul_usable;
	global $poly_result_array;
    global $bone_result_array;
	global $meat_result_array;
	global $all_valid_mem_opt_index;

    $c_lp = $start;
	
	while (true){
		$asm_id = ConstructionDlinkedListOpt::getDlinkedList($c_lp,'c');
		if (ConstructionDlinkedListOpt::issetDlinkedListUnit($c_lp,'poly')){  
			$tmp = ConstructionDlinkedListOpt::getDlinkedList($c_lp,'poly');
   		    remove_ipsp_from_usable ($poly_result_array[$tmp]['usable'][$asm_id]['p']['mem_opt_able']);
   		    remove_ipsp_from_usable ($poly_result_array[$tmp]['usable'][$asm_id]['n']['mem_opt_able']);	   
		}elseif (ConstructionDlinkedListOpt::issetDlinkedListUnit($c_lp,'bone')){  
			$tmp = ConstructionDlinkedListOpt::getDlinkedList($c_lp,'bone');
   		    remove_ipsp_from_usable ($bone_result_array[$tmp]['usable'][$asm_id]['p']['mem_opt_able']);
   		    remove_ipsp_from_usable ($bone_result_array[$tmp]['usable'][$asm_id]['n']['mem_opt_able']);   
		}elseif (ConstructionDlinkedListOpt::issetDlinkedListUnit($c_lp,'meat')){  
			$tmp = ConstructionDlinkedListOpt::getDlinkedList($c_lp,'meat');
   		    remove_ipsp_from_usable ($meat_result_array[$tmp]['usable'][$asm_id]['p']['mem_opt_able']);
   		    remove_ipsp_from_usable ($meat_result_array[$tmp]['usable'][$asm_id]['n']['mem_opt_able']);
	    }else{                                                    
			remove_ipsp_from_usable ($c_soul_usable[$asm_id]['p']['mem_opt_able']);
			remove_ipsp_from_usable ($c_soul_usable[$asm_id]['n']['mem_opt_able']);
		}

        if ($c_lp === $end){
		    break;
		}
        if (!ConstructionDlinkedListOpt::issetDlinkedListUnit($c_lp,'n')){
		    break;
		}
		$c_lp = ConstructionDlinkedListOpt::getDlinkedList($c_lp,'n');
	}
    
}


function inherit_bone_usable(&$c_bone_result_array,$c_soul_position){	
	
	foreach ($c_bone_result_array['process'] as $x){ 
        $first = false;
		$last  = false;	
		$buff  = array();
		foreach ($x as $b){
			if (isset($b['s'])){
				if (isset($c_soul_position[$b['s']])){
					$first = ConstructionDlinkedListOpt::get_usable_from_DlinkedList($c_soul_position[$b['s']]['first'],'p');
					$last  = ConstructionDlinkedListOpt::get_usable_from_DlinkedList($c_soul_position[$b['s']]['last'],'n');					
					if (isset($buff)){
						
						
						foreach ($buff as $z){
							if (isset($z['p'])){
								$c_bone_result_array['usable'][$z['p']]['p'] = $first;
							}elseif (isset($z['n'])){
								$c_bone_result_array['usable'][$z['n']]['n'] = $first;
							}
						}
						unset ($buff);
						$first = false;
					}
				}
			}else{
				$buff[] = $b; 
			}		
		}
		if ((isset($buff)) && (false !== $last)){
			
			
			foreach ($buff as $z){
				if (isset($z['p'])){
					$c_bone_result_array['usable'][$z['p']]['p'] = $last;
				}elseif (isset($z['n'])){
					$c_bone_result_array['usable'][$z['n']]['n'] = $last;
				}
			}	   
		}
	}
	
	if (isset($c_bone_result_array['ipsp'])){
		
		foreach ($c_bone_result_array['ipsp'] as $a => $b){
			
		    if (true === $b){   
			    if (isset($c_soul_position[$a]['first'])){
					remove_ipsp_from_usable_list($c_soul_position[$a]['first'],$c_soul_position[$a]['last']);
				}
			}elseif (1 === $b){ 
			    if (isset($c_bone_result_array['usable'][$a]['p']['mem_opt_able'])){
					remove_ipsp_from_usable($c_bone_result_array['usable'][$a]['p']['mem_opt_able']);
				}			    
			}elseif (2 === $b){ 
			    if (isset($c_bone_result_array['usable'][$a]['n']['mem_opt_able'])){
					remove_ipsp_from_usable($c_bone_result_array['usable'][$a]['n']['mem_opt_able']);
				}			    
			}
		}
	}

    
    foreach ($c_bone_result_array['fat'] as $y => $x){
		if (1 == $x){
			$c_bone_result_array['usable'][$y]['p']['stack'] = true;
		}else{
			$c_bone_result_array['usable'][$y]['n']['stack'] = true;
		}
	}
    
	GeneralFunc::soul_stack_set($c_bone_result_array['code'],$c_bone_result_array['usable']);
}


function poly_compress(&$copy_buff,&$c_first,&$c_last){
    global $poly_result_reverse_array;

	global $poly_result_reverse_array;

	$c_lp = $c_last;

    $counter       = 0;
	$buf           = array();
    $c_count_index = false; 
	$compress = false;

	while (true){
		
        if (isset($copy_buff[$c_lp]['poly'])){
			if ($c_count_index === $copy_buff[$c_lp]['poly']){
			    $counter ++;
				$buf[] = $c_lp;
			}else{
			    $counter = 1;
				unset ($buf);
				$buf[] = $c_lp;
				$c_count_index = $copy_buff[$c_lp]['poly'];
			}
			if ($counter === $poly_result_reverse_array[$copy_buff[$c_lp]['poly']]['n']){ 
				$compress = true;
				break;
			}
		}elseif (isset($copy_buff[$c_lp]['label'])){ 
			$buf[] = $c_lp;
	    }else{
			$c_count_index = false; 
		}

        if ($c_lp === $c_first){
		    break;
		}
		if (!isset($copy_buff[$c_lp]['p'])){			
		    break;
		}
	    $c_lp = $copy_buff[$c_lp]['p'];
	
	}
    
	if (true === $compress){ 
		$lp_linked_list = $poly_result_reverse_array[$c_count_index]['i']; 

		$copy_buff[$lp_linked_list] = ConstructionDlinkedListOpt::getDlinkedList($lp_linked_list);
		unset($copy_buff[$lp_linked_list]['302']);
		$next = false;		
		
		foreach ($buf as $a){
			if (false === $next){
				$copy_buff[$lp_linked_list]['n'] = $copy_buff[$a]['n']; 
				if (isset($copy_buff[$copy_buff[$a]['n']])){
                    $copy_buff[$copy_buff[$a]['n']]['p'] = $lp_linked_list;
				}
				$next = true;
			}
			if ($c_first === $a){ 
				$c_first = $lp_linked_list;
			}			
			if ($c_last  === $a){ 
				$c_last  = $lp_linked_list;
			}
			if (isset($copy_buff[$a]['p'])){
			    $copy_buff[$lp_linked_list]['p'] = $copy_buff[$a]['p'];
			}
		    unset ($copy_buff[$a]);
		}	
		if (isset($copy_buff[$copy_buff[$lp_linked_list]['p']])){
			$copy_buff[$copy_buff[$lp_linked_list]['p']]['n'] = $lp_linked_list;
		}
	}
	return $compress;
}


function soul_copy($source_first,$source_last,&$dest,&$delay_remove){

	
	$c_first  = $source_first;
   	$c_lp     = $source_first;
	$copy_buff = array(); 
    
	while (true){
		

		$copy_buff[$c_lp] = ConstructionDlinkedListOpt::getDlinkedList($c_lp);
		if ($c_lp === $source_last){
		    break;
		}

		if (!ConstructionDlinkedListOpt::issetDlinkedListUnit($c_lp,'n')){
		    break;
		}

		$c_lp = ConstructionDlinkedListOpt::getDlinkedList($c_lp,'n');
	}
	$c_last = $c_lp;

	
	while (count($copy_buff) > 1){		
		if (false === poly_compress($copy_buff,$c_first,$c_last)){
		    break;
		}				
	}
    
    unset ($copy_buff[$c_first]['p']);
	unset ($copy_buff[$c_last]['n']);
	
    $c_lp = $c_first;

	$dest['first'] = $c_first;
	$dest['last']  = $c_last;
	while (true){		
		
		if (isset($copy_buff[$c_lp]['n'])){
			$next = $copy_buff[$c_lp]['n'];
		}else{
			$next = false;
		}
		if (isset($copy_buff[$c_lp]['label'])){ 
			if (mt_rand(0,1)){
				$delay_remove[] = $c_lp; 
			}else{
				ConstructionDlinkedListOpt::remove_from_DlinkedList($c_lp,$dest['first'],$dest['last'],$copy_buff);	
			}
		}
		if ($c_lp === $c_last){
		    break;
		}
		if (false === $next){
		    break;
		}
		$c_lp = $next;
	}
	
	
	return $copy_buff;
}







function rel_copy_create($asm,$usable){
    global $UNIQUE_bone_index;
	global $bone_result_array;
    $bone_index = $UNIQUE_bone_index;
	$UNIQUE_bone_index ++;
    $bone_result_array[$bone_index]['code'][99]    = $asm;
    $bone_result_array[$bone_index]['usable'][99] = $usable;
	return $bone_index;
}




function insert_copy_2_list(&$c_prev,$copy,$soul_position){


	global $c_Asm_Result;
	global $c_soul_usable;
	global $poly_result_array;
	global $bone_result_array;
	global $meat_result_array;
	global $c_rel_info;
	global $sec;
	global $UniqueHead;
    global $multi_bone_poly; 

   
	$c_lp = $soul_position['first'];
	while (true){

		$tmp_multi_bone_poly[] = ConstructionDlinkedListOpt::getDlinkedListIndex();
		

		ConstructionDlinkedListOpt::setDlinkedList($copy[$c_lp],ConstructionDlinkedListOpt::getDlinkedListIndex());
        if (isset($copy[$c_lp]['poly'])){
		    $c_asm    = $poly_result_array[$copy[$c_lp]['poly']]['code'][$copy[$c_lp]['c']];
            $c_usable = $poly_result_array[$copy[$c_lp]['poly']]['usable'][$copy[$c_lp]['c']];
		}elseif (isset($copy[$c_lp]['bone'])){
		    $c_asm    = $bone_result_array[$copy[$c_lp]['bone']]['code'][$copy[$c_lp]['c']];
			$c_usable = $bone_result_array[$copy[$c_lp]['bone']]['usable'][$copy[$c_lp]['c']];
		}elseif (isset($copy[$c_lp]['meat'])){
		    $c_asm    = $meat_result_array[$copy[$c_lp]['meat']]['code'][$copy[$c_lp]['c']];
			$c_usable = $meat_result_array[$copy[$c_lp]['meat']]['usable'][$copy[$c_lp]['c']];
		}else{
			$c_asm    = $c_Asm_Result[$copy[$c_lp]['c']];
			$c_usable = $c_soul_usable[$copy[$c_lp]['c']];
		}
		if (isset($c_asm['rel'])){
			
		    
			


			ConstructionDlinkedListOpt::unsetDlinkedList('poly');
			ConstructionDlinkedListOpt::unsetDlinkedList('meat');

			ConstructionDlinkedListOpt::setDlinkedList(rel_copy_create($c_asm,$c_usable),ConstructionDlinkedListOpt::getDlinkedListIndex(),'bone');

            ConstructionDlinkedListOpt::setDlinkedList(99,ConstructionDlinkedListOpt::getDlinkedListIndex(),'c');
            
            
			
			
			
			
			foreach ($c_asm['rel'] as $p_number => $p_rel_info){
				$old_rel_n = $p_number;
				$old_rel_i = $p_rel_info['i'];
				$old_rel_c = $p_rel_info['c'];
				$new = GenerateFunc::reloc_inc_copy_naked($old_rel_i,$old_rel_c);
				

				$tmp_tmp = ConstructionDlinkedListOpt::getDlinkedList(ConstructionDlinkedListOpt::getDlinkedListIndex(),'bone');
				$bone_result_array[$tmp_tmp]['code'][99]['rel'][$p_number]['c'] = $new;
				
				$c_rel_info[$old_rel_i][$new] = $c_rel_info[$old_rel_i][$old_rel_c];
				

				$bone_result_array[$tmp_tmp]['code'][99]['params'][$old_rel_n] = str_replace("$UniqueHead".'RELINFO_'.$sec.'_'.$old_rel_i.'_'.$old_rel_c,"$UniqueHead".'RELINFO_'.$sec.'_'.$old_rel_i.'_'.$new,$bone_result_array[$tmp_tmp]['code'][99]['params'][$old_rel_n]);

				
				
				
			}
		}


        ConstructionDlinkedListOpt::insertDlinkedListByIndex($c_prev);

		$c_prev = ConstructionDlinkedListOpt::getDlinkedListIndex();
		if ($c_lp === $soul_position['last']){

			ConstructionDlinkedListOpt::incDlinkedListIndex();
			break;
		}
		if (!isset($copy[$c_lp]['n'])){
			
			
			

			ConstructionDlinkedListOpt::incDlinkedListIndex();
			break;
		}
		$c_lp = $copy[$c_lp]['n'];		

		ConstructionDlinkedListOpt::incDlinkedListIndex();
	}

	$multi_bone_poly[] = $tmp_multi_bone_poly;

	return;
}


function init_bone_model(&$c_bone,$bone_obj){ 

    global $UNIQUE_bone_index;
	global $bone_result_array;


	global $UniqueHead;
	global $Jcc;

    $bone_index = $UNIQUE_bone_index;
	$UNIQUE_bone_index ++;

   


    
    foreach ($c_bone['code'] as $a => $b){
	    if (isset($b['params'][0])){ 
		    $c_bone['code'][$a]['params'][0] = $UniqueHead.$c_bone['code'][$a]['params'][0].$bone_index;
			if ('Jcc' === $b['operation']){
			    $c_bone['code'][$a]['operation'] = $Jcc[array_rand($Jcc)];
			}
		}elseif (isset($b['label'])){
			$c_bone['code'][$a]['label'] = $UniqueHead.$c_bone['code'][$a]['label'].$bone_index." : ";
			
		}
	
	}

	
	
	
	$soul_position = array();
	$z = 1; 
	$c_last = false;
    foreach ($c_bone['direct'] as $a => $b){
	    if ($b){
		    $soul_position[$a]['first'] = $bone_obj[$z];
		    $soul_position[$a]['last']  = $bone_obj[$b];
			$z = $b + 1;

            if (ConstructionDlinkedListOpt::issetDlinkedListUnit($soul_position[$a]['last'],'n')){

				$c_last = ConstructionDlinkedListOpt::getDlinkedList($soul_position[$a]['last'],'n');
			}else{
			    $c_last = false;
			}
		}
	}

    
	$copy = array();
	$delay_remove = array();
	if (isset($c_bone['copy'])){
	    foreach ($c_bone['copy'] as $a => $b){
		    $copy[$b] = soul_copy($soul_position[$a]['first'],$soul_position[$a]['last'],$soul_position[$b],$delay_remove);
		}
	}
	
    
	
    
	

	$c_bone_list_start = ConstructionDlinkedListOpt::getDlinkedListIndex();
	$c_prev = false;

    if (ConstructionDlinkedListOpt::issetDlinkedListUnit($bone_obj[1],'p')){

		$c_prev = ConstructionDlinkedListOpt::getDlinkedList($bone_obj[1],'p');
	}
	$c_soul_ptr = 1;
    foreach ($c_bone['code'] as $a => $b){			    
		if (true === $b){ 
			if (isset($soul_position[$a]['first'])){ 
			    if (isset($copy[$a])){ 
				    insert_copy_2_list($c_prev,$copy[$a],$soul_position[$a]);
				}else{					


					ConstructionDlinkedListOpt::insertDlinkedList($c_prev,$soul_position[$a]['first']);
					$c_prev = $soul_position[$a]['last'];
				}				
			}
		}else{            
		    if (false === $c_prev){ 
				ConstructionDlinkedListOpt::setListFirstUnit();
			}else{
				
				


				ConstructionDlinkedListOpt::insertDlinkedListByIndex($c_prev);
			}

			ConstructionDlinkedListOpt::setDlinkedList(true,ConstructionDlinkedListOpt::getDlinkedListIndex(),'ipsp');

			ConstructionDlinkedListOpt::setDlinkedList($bone_index,ConstructionDlinkedListOpt::getDlinkedListIndex(),'bone');

			ConstructionDlinkedListOpt::setDlinkedList($a,ConstructionDlinkedListOpt::getDlinkedListIndex(),'c');
			if (isset($b['label'])){

                ConstructionDlinkedListOpt::setDlinkedList($b['label'],ConstructionDlinkedListOpt::getDlinkedListIndex(),'label');
			}
			

			$c_prev = ConstructionDlinkedListOpt::getDlinkedListIndex();

			ConstructionDlinkedListOpt::incDlinkedListIndex();
		}
	}
	if (false !== $c_last){


		ConstructionDlinkedListOpt::insertDlinkedList($c_prev,$c_last);
	}

	$bone_result_array[$bone_index] = $c_bone;

    
	$none = false;
	foreach ($delay_remove as $a){
		
		
		
	    ConstructionDlinkedListOpt::remove_from_DlinkedList($a,ConstructionDlinkedListOpt::readListFirstUnit(),$none);
	}


	
	inherit_bone_usable($bone_result_array[$bone_index],$soul_position);

	

    return $bone_index;
}


function collect_usable_bone_model ($bone_obj,$last_ipsp,$c_bone_model){
    
	
	
	$c_soul = 1;
	$c_soul_length = count($bone_obj);
	
	
	$direct_num = count ($c_bone_model['direct']);
	foreach ($c_bone_model['direct'] as $a => $b){
		$direct_num --;			
		if (!$c_soul_length){ 
			$c_bone_model['direct'][$a] = 0;
		}else{
			if (0 == $direct_num){ 
				$c_bone_model['direct'][$a] = $c_soul - 1 + $c_soul_length;
				break;
			}
			if ((1 === $b)||(!$last_ipsp)){               
				$c_position = mt_rand (0,$c_soul_length); 
				
			}elseif (2 === $b){                           
				$c_position = mt_rand ($last_ipsp,$c_soul_length);
				
			}elseif (0 === $b){                           
				
				for ($i = 0;$i < $c_soul_length;$i++){

                    if (ConstructionDlinkedListOpt::issetDlinkedListUnit($bone_obj[$c_soul + $i],'ipsp')){
						break;
					}   
				}
				$c_position = mt_rand (0,$i);
				
			}else{ 
				return false;
			}

			if ($c_position){			    
				$c_soul_length -= $c_position;
				if ($last_ipsp > $c_position){
					$last_ipsp -= $c_position;
				}else{
					$last_ipsp = 0;
				}				
				$c_soul += $c_position;
				$c_bone_model['direct'][$a] = $c_soul - 1;
			}else{
				$c_bone_model['direct'][$a] = 0;		
			}			
		}
	}

	if ((0 == $b)&&($c_bone_model['direct'][$a])){ 
		
		for ($i = $c_soul;$i <= $c_bone_model['direct'][$a];$i ++){			    

            if (ConstructionDlinkedListOpt::issetDlinkedListUnit($bone_obj[$i],'ipsp')){
				return false;
			}
		}			
	}	

    
    if (false !== check_bone_stack_conflict($c_bone_model,$bone_obj)){
	    return false;
	}

    $bone_index = init_bone_model($c_bone_model,$bone_obj);
    
    return true;
	
}



function check_bone_stack_conflict($c_bone_model,$bone_obj){
	global $my_params;
	$i = 0;
	$stack_unusable = false; 
	$ret = false;
	foreach ($c_bone_model['direct'] as $a => $b){
		if ($b){
			$i ++;
			$tmp = ConstructionDlinkedListOpt::get_unit_by_soul_writein_Dlinked_List($bone_obj[$i]);     
			if (true !== $tmp['usable']['p']['stack']){
				$stack_unusable[$a]['p'] = true;
			}				
			$i = $b;
			$tmp = ConstructionDlinkedListOpt::get_unit_by_soul_writein_Dlinked_List($bone_obj[$i]); 
			if (true !== $tmp['usable']['n']['stack']){
				$stack_unusable[$a]['n'] = true;
			}
		}	    
	}

    $conflict_position = array(); 

	if ($stack_unusable){		
		foreach ($c_bone_model['process'] as $a => $b){
			$stack_use = false;       
			$stack_forbid = false;    
		    foreach ($b as $c => $d){
			    if (isset($d['p'])){
					$conflict_position['bone'] = $d['p'];
					if (isset ($c_bone_model['code'][$d['p']]['operation'])){
                        $tmp = GenerateFunc::get_inst_define($c_bone_model['code'][$d['p']]['operation'],$c_bone_model['code'][$d['p']]['params']);
						if (isset($tmp['STACK'])){
						    if ($stack_forbid){
							    $ret = true;
								break;
							}
                            $stack_use = true;
						}else{
						    $stack_use = false;
						}
					}
				}elseif (isset($d['s'])){
				    if (($stack_use) and (true === $stack_unusable[$d['s']]['p'])){ 
					    $conflict_position['code'] = $d['s'];
						$conflict_position['code_direct'] = 'p';
					    $ret = true;
						break;
					}
					$conflict_position['code'] = $d['s'];
                    $conflict_position['code_direct'] = 'n';
					if (true === $stack_unusable[$d['s']]['n']){
						$stack_forbid = true;
					}else{
						$stack_forbid = false;
					}
				}
			}
			if ($ret){
			    break;
			}
		}
	}
    return $ret;
}

function bone_create($bone_obj,$language){		 

    global $bone_model_index;
	global $bone_model_index_multi;
    global $bone_model_repo;
    global $MAX_INCLUDE_MULTI_PROCESS_BONE;
    global $UNIQUE_bone_index;
	global $bone_result_array;



	if (count($bone_obj) <= $MAX_INCLUDE_MULTI_PROCESS_BONE){  
		$c_bone_model_index = $bone_model_index_multi;
	}else{
		$c_bone_model_index = $bone_model_index;
	}

	$last_ipsp = false;
	if (is_array($bone_obj)){
		foreach ($bone_obj as $a => $b){

			if ((ConstructionDlinkedListOpt::getDlinkedList($b,'ipsp'))||(ConstructionDlinkedListOpt::getDlinkedList($b,'label'))){
				$last_ipsp = $a; 
			}
		}
	}		
	$x = array_rand($c_bone_model_index);
	$z = $c_bone_model_index[$x];
	echo "<br> bone repo index: $z ";
	
	if ($z){
		$start_index = $UNIQUE_bone_index;

		$start_lp    = ConstructionDlinkedListOpt::getDlinkedListIndex();


		$ready_for_poly_start = ConstructionDlinkedListOpt::getDlinkedListIndex();

		if (!collect_usable_bone_model ($bone_obj,$last_ipsp,$bone_model_repo[$z])){ 
		    GeneralFunc::LogInsert($language['fail_bone_array'].$z,2);	
			
			

		}

	}else{ 
	    return;   
	}
}

?>