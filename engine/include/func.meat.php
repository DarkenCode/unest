<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}

unset($meat_model_repo[0]); 
$UNIQUE_meat_index = 1;




$cf = @file_get_contents(dirname(__FILE__).'/../models/model_meat.dat');
if ($cf == false){
	$output['warning'][] = 'fail to open meat repo';
}else{
	$meat_array = unserialize($cf);
	$repo_number = count($meat_array);
	
	$t = mt_rand(0,$repo_number-1);
	echo "<br>meat model:<br>";
	var_dump ($t);
	$meat_relate_array = $meat_array[$t]['relate'];
    $meat_usable_envir = $meat_array[$t]['usable_envir'];
	$meat_usable_index = $meat_array[$t]['usable_index'];
    $meat_usable_array = $meat_array[$t]['usable_array'];
    $meat_same_inst_rate = $meat_array[$t]['meat_same_inst_rate'];

    
	foreach ($meat_usable_index['DIV'] as $a){
		unset ($meat_usable_envir['ALL'][$a]);	    
	}
	foreach ($meat_usable_index['IDIV'] as $a){
		unset ($meat_usable_envir['ALL'][$a]);	    
	}
	
}





function meat_insert_into_list($current_forward,$meat_generated,$direct = 'p'){
    global $UNIQUE_meat_index;
    global $soul_writein_Dlinked_List;
	global $s_w_Dlinked_List_index;
	global $soul_writein_Dlinked_List_start;
    global $meat_result_array;
    global $c_user_cnf_stack_pointer_define;

	$prev = false;
	$next = false;

    if ('p' === $direct){
		if (isset($soul_writein_Dlinked_List[$current_forward]['p'])){
		    $prev = $soul_writein_Dlinked_List[$current_forward]['p'];
		}
	    $next = $current_forward;
	}else{
		if (isset($soul_writein_Dlinked_List[$current_forward]['n'])){
		    $next = $soul_writein_Dlinked_List[$current_forward]['n'];
		}
        $prev = $current_forward;	
	}




    $c_meat = $UNIQUE_meat_index - $meat_generated;
	
	

    for (;$c_meat < $UNIQUE_meat_index;$c_meat++){
		if (false !== $prev){
			$soul_writein_Dlinked_List[$prev]['n'] = $s_w_Dlinked_List_index;
		}else{ 
		    $soul_writein_Dlinked_List_start = $s_w_Dlinked_List_index;
		}
		$soul_writein_Dlinked_List[$s_w_Dlinked_List_index]['meat'] = $c_meat;
		$soul_writein_Dlinked_List[$s_w_Dlinked_List_index]['c'] = 98;
		
		if (is_effect_ipsp($meat_result_array[$c_meat]['code'][98],0,$c_user_cnf_stack_pointer_define)){
			$soul_writein_Dlinked_List[$s_w_Dlinked_List_index]['ipsp'] = true;			
		    
		}

        if (false !== $prev){
			$soul_writein_Dlinked_List[$s_w_Dlinked_List_index]['p'] = $prev;
		}
		$prev = $s_w_Dlinked_List_index;	   
		$s_w_Dlinked_List_index ++;
	}
	if (false !== $next){
	    $soul_writein_Dlinked_List[$next]['p'] = $prev;
		$soul_writein_Dlinked_List[$prev]['n'] = $next;
	}





}





function get_meat_usable_repo($flag_usable,$reg_usable,$m_r32,$m_w32){	
	global $meat_usable_envir; 
	global $meat_model_repo;
    global $all_eflags;
	global $registersss;

    $ret = false;
    


	$filter[] = $meat_usable_envir['ALL'];           

	foreach ($all_eflags as $a){
	    if (!isset($flag_usable[$a])){
			if (isset($meat_usable_envir[$a])){
				$filter[] = $meat_usable_envir[$a];
			}
		}
	}

	foreach ($registersss['32'] as $a){
		if (!isset($reg_usable[$a])){
			if (isset($meat_usable_envir[$a])){
				$filter[] = $meat_usable_envir[$a];
			}
		}
	}

	if ((is_array($reg_usable))&&(0 < count($reg_usable))){
		
	}else{
		$filter[] = $meat_usable_envir['r32'];
	}

	if (true !== $m_r32){
		$filter[] = $meat_usable_envir['m_r32'];
	}
	
	if (true !== $m_w32){
		$filter[] = $meat_usable_envir['m_w32'];	
	}    
	
	if (count ($filter) > 1){
		$ret = call_user_func_array('array_diff',$filter);  
	}else{
	    $ret = $meat_usable_envir['ALL'];
	}
    
    return $ret;

}









function gen_invalid_mem_address(){    
    global $registersss;	

    $ret = false;

	$first  = 0;
	$second = 0;
	$third  = 0;

	if (mt_rand(0,3)){		
	    $ret = array_rand($registersss[32]);
		$first = 1;
	}

	if (mt_rand(0,4)){
		$tmp = array_rand($registersss[32]);
		if ('ESP' != $tmp){
			if (false !== $ret){
				$ret .= '+';
		    }        
            $ret .= $tmp;
			$tmp = array_rand(array('2'=>true,'4'=>true,'8'=>true));
			$ret .= '*'.$tmp;

			$second = 1;

			if ((0 === $first) and ($tmp > 2)){ 
			    $second = 2;
			}
		}
	}
	if ((false === $ret)||(mt_rand(0,3))){
	    if (false !== $ret){
		    $ret .= '+';
		}
		$r_int = rand_interger();
		$ret .= $r_int['value'];

		$third = bits_precision_adjust($r_int['bits']);
	}

	$ret = '['.$ret.']';

    
	global $all_valid_mem_opcode_len;
    global $mem_effect_len_array;
    
	$len = $mem_effect_len_array['max'];
	if (isset($mem_effect_len_array[$first][$second][$third])){
	    $len = $mem_effect_len_array[$first][$second][$third];
	}
    $all_valid_mem_opcode_len[$ret] = $len;

    

	return $ret;

}








function meat_params_generate(&$ret,$p_type,$p_bits,$p_static,$reg_usable,$mem_usable,$mem_write_usable,$opt){
    global $Intel_instruction;
	global $registersss;
	global $c_rel_info;

	
	
	
	
    
	
	
    
    $c_inst = false;
	if ($Intel_instruction[$opt]['multi_op']){ 
	    $tmp = count($p_type);
	    if (isset($Intel_instruction[$opt][$tmp])){
		    $c_inst = $Intel_instruction[$opt][$tmp];
		}
	}else{
	    $c_inst = $Intel_instruction[$opt];
	}
    
    if (false === $c_inst){                    
	    return false;
	}


	foreach ($p_type as $number => $type){

        if (isset($p_static[$number])){  
		    $ret['params'][$number] = $p_static[$number];		
		}else{
			if ('i' === $type){        
				$r_int = rand_interger($p_bits[$number]);
				$ret['params'][$number] = $r_int['value'];
				
			}elseif ('r' === $type){   
				if (1 >= $c_inst[$number]){ 
					$tmp = array_rand($registersss[$p_bits[$number]]);
				}else{                                       
					if (is_array($reg_usable)){
						$tmp = array_rand($reg_usable);
					}else{ 
						    echo '<br>调试错误信息, 出现需要可写通用寄存器 而 没有，所以meat构建失败...debugging...<br>';
                            var_dump ($p_type);
						    var_dump ($p_bits);
							var_dump ($p_static);
							var_dump ($reg_usable);
							var_dump ($mem_usable);
							var_dump ($mem_write_usable);
							var_dump ($opt);
					    return false;
					}
				}		    
				$ret['params'][$number] = $registersss[$p_bits[$number]][$tmp];
			}elseif ('m' === $type){   
				if (-1 == $c_inst[$number]){     
					$ret['params'][$number] = gen_invalid_mem_address();                
				}else{
					if (1 == $c_inst[$number]){ 
						$c_mem_usable_array = $mem_usable;
					}else{                                       
						$c_mem_usable_array = $mem_write_usable;
					}
					
					
					
					
					$tmp = array_rand($c_mem_usable_array);				
					$ret['params'][$number] = $c_mem_usable_array[$tmp]['code'];
					if (isset($c_mem_usable_array[$tmp]['rel'])){        
						if (reloc_inc_copy($ret['params'][$number],$old,$new)){
							$ret['params'][$number] = str_replace("$UniqueHead".'RELINFO_'.$old[0].'_'.$old[1].'_'.$old[2],"$UniqueHead".'RELINFO_'.$old[0].'_'.$old[1].'_'.$new,$ret['params'][$number]);
							$ret['rel'][$number]['i'] = $old[1];
							$ret['rel'][$number]['c'] = $new;
							$c_rel_info[$old[1]][$new] = $c_rel_info[$old[1]][$old[2]];
						}			    
					}
				}			
			}else{                     
				return false;
			}
		}

		if (!isset($ret['params'][$number])){ 
			return false;
		}
	}
	
	
	return true;
    
}











function meat_generate($usable_meat_repo,$c_meat_no,$reg_usable,$mem_usable,$mem_write_usable,$c_usable_array){

	global $meat_model_repo;
	global $UNIQUE_meat_index;
	global $meat_result_array;

    $ret = 0;  

    for ($i = 0;$i < $c_meat_no;$i ++){



		
		
		
		
        
		
		
		
		
		
		
		
		
        
        

	    
        

   	    $result['code'][98] = $usable_meat_repo;
		$result['usable'][98]['p'] = $c_usable_array;
		$result['usable'][98]['n'] = $c_usable_array;
        
		$success = true; 
        
		if (isset($usable_meat_repo['p_type'])){ 
			$success = meat_params_generate($result['code'][98],$usable_meat_repo['p_type'],$usable_meat_repo['p_bits'],$usable_meat_repo['static'],$reg_usable,$mem_usable,$mem_write_usable,$usable_meat_repo['operation']);
		}

        if (true === $success){		
			
			
            
			soul_stack_set($result['code'],$result['usable']);

			$meat_result_array[$UNIQUE_meat_index] = $result;
			$UNIQUE_meat_index ++;
			$ret ++;
		}
	} 
	return $ret;

}






function meat_start($List_id,$direct = 'p'){
    global $soul_writein_Dlinked_List;
    global $meat_result_array;
	global $bone_result_array;
	global $poly_result_array;
    global $c_soul_usable;

	global $all_valid_mem_opt_index;
    
    if ('p' === $direct){
	    $fat = 1;
	}else{
	    $fat = 2;
	}


    $c_usable_array = array();
	if (isset($soul_writein_Dlinked_List[$List_id]['poly'])){
		
        if ($fat === $poly_result_array[$soul_writein_Dlinked_List[$List_id]['poly']]['fat'][$soul_writein_Dlinked_List[$List_id]['c']]){
			
		    return 0;
		}
	    $c_usable_array = $poly_result_array[$soul_writein_Dlinked_List[$List_id]['poly']]['usable'][$soul_writein_Dlinked_List[$List_id]['c']][$direct];
	}elseif (isset($soul_writein_Dlinked_List[$List_id]['bone'])){
		
		if ($fat === $poly_result_array[$soul_writein_Dlinked_List[$List_id]['bone']]['fat'][$soul_writein_Dlinked_List[$List_id]['c']]){
			
			return 0;
		}
		$c_usable_array = $bone_result_array[$soul_writein_Dlinked_List[$List_id]['bone']]['usable'][$soul_writein_Dlinked_List[$List_id]['c']][$direct];
	}elseif (isset($soul_writein_Dlinked_List[$List_id]['meat'])){
				
	    $c_usable_array = $meat_result_array[$soul_writein_Dlinked_List[$List_id]['meat']]['usable'][$soul_writein_Dlinked_List[$List_id]['c']][$direct];
	}else{
		
	    $c_usable_array = $c_soul_usable[$soul_writein_Dlinked_List[$List_id]['c']][$direct];
	}

    
	$prev_inst = false; 
	$next_inst = false; 
    if ('p' === $direct){
		if (isset($soul_writein_Dlinked_List[$List_id]['p'])){
			$prev_inst = get_inst_from_list($soul_writein_Dlinked_List[$List_id]['p'],'p');
		}else{
			$prev_inst = 'empty';
		}
		$next_inst = get_inst_from_list($List_id,'n');
	}else{
		if (isset($soul_writein_Dlinked_List[$List_id]['n'])){
			$next_inst = get_inst_from_list($soul_writein_Dlinked_List[$List_id]['n'],'n');
		}else{
			$next_inst = 'empty';
		}
	    $prev_inst = get_inst_from_list($List_id,'p');
	}
    

    global $meat_relate_array;      

    global $c_MeatMutation;
	
    $meat_mutation = my_rand($c_MeatMutation);    
	
	
	
	
	
	
    

    if (false === $meat_mutation){
		if (!isset($meat_relate_array[$prev_inst][$next_inst])){ 
			
			return 0;
		}
	}

    
	$reg_usable  = false;
	$mem_usable  = false;
	$mem_write_usable = false; 
	$flag_usable = $c_usable_array['flag_write_able'];
	$m_w32 = false;
	$m_r32 = false;

    
	if (is_array($c_usable_array['normal_write_able'])){
		foreach ($c_usable_array['normal_write_able'] as $a => $b){
			if ($b[32]){
				if ('ESP' !== $a){  
					$reg_usable[$a] = true;
				}
			}	
		}
	}
	
	if (is_array($c_usable_array['mem_opt_able'])){
		foreach ($c_usable_array['mem_opt_able'] as $a => $b){
            

			if ($all_valid_mem_opt_index[$b]['bits'] == 32){
				$mem_usable[] = $all_valid_mem_opt_index[$b];				
				$m_r32 = true;
				if ($all_valid_mem_opt_index[$b]['opt'] > 1){
					$mem_write_usable[] = $all_valid_mem_opt_index[$b];
					$m_w32 = true;
				}
			}
		}
	}

	
	

    
	$usable_meat_repo = get_meat_usable_repo($flag_usable,$reg_usable,$m_r32,$m_w32);

    
	global $meat_usable_index;
    global $meat_usable_array;
	global $meat_same_inst_rate;
     
    $final_usable_model = false; 

	
    
    
	if (false === $meat_mutation){
		
		if (isset($meat_relate_array[$prev_inst][$next_inst])){
			foreach ($meat_relate_array[$prev_inst][$next_inst] as $a => $relate_rate){
				
				
				foreach ($meat_usable_index[$a] as $repo_index){
					if (isset($usable_meat_repo[$repo_index])){
						$final_usable_model[$repo_index] = $meat_usable_array[$repo_index];   
						$final_usable_model[$repo_index]['rate'] = $relate_rate * $meat_same_inst_rate[$repo_index];
					}
				}
			}
		}		
		
	    
	    
		

	}else{ 
		 foreach ($meat_usable_index as $a => $b){
			foreach ($meat_usable_index[$a] as $repo_index){
				if (isset($usable_meat_repo[$repo_index])){
					$final_usable_model[$repo_index] = $meat_usable_array[$repo_index];   
					$final_usable_model[$repo_index]['rate'] = $meat_same_inst_rate[$repo_index];
				}
			}
		}
		
		
		
		
	}

	if (false === $final_usable_model){ 
	    return 0;
	}

	
	$c_obj_model = random_repo_by_rate($final_usable_model);

    return meat_generate($c_obj_model,1,$reg_usable,$mem_usable,$mem_write_usable,$c_usable_array);

}






function random_repo_by_rate($usable_model){
	
	
	$c_rate = 0;
	foreach ($usable_model as $id => $value){
	    $usable_model[$id]['rate'] += $c_rate;       
		$c_rate = $usable_model[$id]['rate'];       
	}

	
	
    $r = mt_rand(1,$c_rate);      

	foreach ($usable_model as $id => $value){
	    if ($r <= $usable_model[$id]['rate']){
			unset ($usable_model[$id]['rate']);
			return $usable_model[$id];			
		}
	}
    return false;
}








function get_inst_from_list($List_id,$direct){
    global $soul_writein_Dlinked_List;
    global $meat_result_array;
	global $bone_result_array;
	global $poly_result_array;
	global $c_Asm_Result;

    $ret = false;
    $tmp = false; 
    while (isset($soul_writein_Dlinked_List[$List_id]['label'])){ 
		
		
		if (isset($soul_writein_Dlinked_List[$List_id][$direct])){
			$List_id = $soul_writein_Dlinked_List[$List_id][$direct];
		}else{
			
			return 'empty';
		}	
	}
	if (isset($soul_writein_Dlinked_List[$List_id]['poly'])){
		if (isset($poly_result_array[$soul_writein_Dlinked_List[$List_id]['poly']]['code'][$soul_writein_Dlinked_List[$List_id]['c']]['operation'])){
			$ret = $poly_result_array[$soul_writein_Dlinked_List[$List_id]['poly']]['code'][$soul_writein_Dlinked_List[$List_id]['c']]['operation'];
		}
		
	}elseif (isset($soul_writein_Dlinked_List[$List_id]['bone'])){
		if (isset($bone_result_array[$soul_writein_Dlinked_List[$List_id]['bone']]['code'][$soul_writein_Dlinked_List[$List_id]['c']]['operation'])){
			$ret = $bone_result_array[$soul_writein_Dlinked_List[$List_id]['bone']]['code'][$soul_writein_Dlinked_List[$List_id]['c']]['operation'];
		}
		
	}elseif (isset($soul_writein_Dlinked_List[$List_id]['meat'])){
		if (isset($meat_result_array[$soul_writein_Dlinked_List[$List_id]['meat']]['code'][$soul_writein_Dlinked_List[$List_id]['c']]['operation'])){
			$ret = $meat_result_array[$soul_writein_Dlinked_List[$List_id]['meat']]['code'][$soul_writein_Dlinked_List[$List_id]['c']]['operation'];
		}
		
	}elseif (isset($c_Asm_Result[$soul_writein_Dlinked_List[$List_id]['c']])){	
		if (isset($c_Asm_Result[$soul_writein_Dlinked_List[$List_id]['c']]['operation'])){
			$ret = $c_Asm_Result[$soul_writein_Dlinked_List[$List_id]['c']]['operation'];
		}
		
	}

	
	return $ret;
}











function meat_create($objs,$meat_max_number){
    global $soul_writein_Dlinked_List;

    $c_total_meat_generated = 0;

	$new_meat_gen = false; 

    $first_unit = false;    
	$last_unit  = false;    
    

	foreach ($objs as $a){		
		if (false === $first_unit){
		    $first_unit = $a;
		}		
		$last_unit = $a;
		
		if (meat_start ($a,'p')){ 
            meat_insert_into_list($a,1,'p');
			$meat_max_number --;
			$c_total_meat_generated ++;
			$new_meat_gen = true;
		}
		
		if (meat_start ($a,'n')){ 
		    meat_insert_into_list($a,1,'n');
			$meat_max_number --;	
			$c_total_meat_generated ++;
			$new_meat_gen = true;
		}
		if ($meat_max_number <= 0){
		    break;
		}
	}
    
	

    while (($new_meat_gen) and ($meat_max_number > 0)){		
		$current_unit = $first_unit;
        $new_meat_gen = false;

        
		
		
		

        while (($current_unit != $last_unit) and ($meat_max_number > 0)){
		    
			if (!isset($soul_writein_Dlinked_List[$current_unit]['n'])){      
				$current_unit = false;
			}else{
				$current_unit = $soul_writein_Dlinked_List[$current_unit]['n'];	
			}

	        if (meat_start ($current_unit,'p')){ 
				meat_insert_into_list($current_unit,1,'p');
				$meat_max_number --;
				$c_total_meat_generated ++;
				$new_meat_gen = true;
			}

			if (meat_start ($current_unit,'n')){ 
				meat_insert_into_list($current_unit,1,'n');
				$meat_max_number --;
				$c_total_meat_generated ++;
				$new_meat_gen = true;
			}

			if (false === $current_unit){
			    break;
			}
			
		}    
		
	}

	return $c_total_meat_generated;
	
}





?>