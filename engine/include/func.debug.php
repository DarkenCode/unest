<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}










function gen_code_4_debug_usable_array($usable,$prev,$next,$type = '0x0cccccccc'){
	
	global $all_valid_mem_opt_index;
	global $registersss;

	global $soul_writein_Dlinked_List;
    global $s_w_Dlinked_List_index;
    
	global $UNIQUE_meat_index;
	global $meat_result_array;
    
    $result = false;
	$i = 0;    


    if (!empty($usable['normal_write_able'])){
		foreach ($usable['normal_write_able'] as $a => $b){
			if (isset($b[32])){ 
				$result['code'][$i]['operation'] = 'MOV';
				$result['code'][$i]['params'][0] = $registersss[32][$a];
				$result['code'][$i]['params'][1] = $type;    				
				$result['code'][$i]['p_type'][0] = 'r';
				$result['code'][$i]['p_type'][1] = 'i';
                $result['code'][$i]['p_bits'][0] = 32;
				$result['code'][$i]['p_bits'][1] = 32;
				$i ++;			
			}else{
				foreach ($b as $c => $d){				
					$result['code'][$i]['operation'] = 'MOV';
					$result['code'][$i]['params'][0] = $registersss[$c][$a];
					$result['code'][$i]['params'][1] = $type;    
					$result['code'][$i]['p_type'][0] = 'r';
					$result['code'][$i]['p_type'][1] = 'i';
					$result['code'][$i]['p_bits'][0] = $c;
					$result['code'][$i]['p_bits'][1] = $c;				
					$i ++;				
				}
			}
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	

	
	if (is_array($usable['mem_opt_able'])){
	    foreach ($usable['mem_opt_able'] as $a => $b){	
			$z = $all_valid_mem_opt_index[$b]['code'];
			$v = $all_valid_mem_opt_index[$b];
			if ($v['opt'] >= 2){
				if ($v['bits'] == 32){
					if (false === strpos($z,'_RELINFO_')){ 
                        $result['code'][$i]['operation'] = 'MOV';
						$result['code'][$i]['params'][0] = $z;
						$result['code'][$i]['params'][1] = $type;       
						$result['code'][$i]['p_type'][0] = 'm';
						$result['code'][$i]['p_type'][1] = 'i';
						$result['code'][$i]['p_bits'][0] = $c;
						$result['code'][$i]['p_bits'][1] = $c;	 				
						$i ++; 
					}else{
					    
					}
				}
			}
		}
	}

	if (false !== $result){
	    $meat_result_array[$UNIQUE_meat_index] = $result;				
        foreach ($result['code'] as $a => $b){
			if (false !== $prev){
			    $soul_writein_Dlinked_List[$prev]['n'] = $s_w_Dlinked_List_index;			
		    }else{

				global $soul_writein_Dlinked_List_start;

			    $soul_writein_Dlinked_List_start = $s_w_Dlinked_List_index;
			}
			$soul_writein_Dlinked_List[$s_w_Dlinked_List_index]['p'] = $prev;
		    $soul_writein_Dlinked_List[$s_w_Dlinked_List_index]['meat'] = $UNIQUE_meat_index;			
		    $soul_writein_Dlinked_List[$s_w_Dlinked_List_index]['c']    = $a;
			
			
			
			
			
			$prev = $s_w_Dlinked_List_index;
		    $s_w_Dlinked_List_index ++;		
		} 
		if (false !== $next){
			$soul_writein_Dlinked_List[$next]['p'] = $s_w_Dlinked_List_index - 1;
			$soul_writein_Dlinked_List[$s_w_Dlinked_List_index - 1]['n'] = $next;
		}
		$UNIQUE_meat_index ++;
	}
    return;
}



function debug_usable_array($c_lp){		 
    
	global $c_soul_usable;


	$p_lp   = false;                            
	$n_lp   = false;                            

	global $soul_writein_Dlinked_List;

	while (true){

	    if (isset($soul_writein_Dlinked_List[$c_lp]['n'])){
			$n_lp = $soul_writein_Dlinked_List[$c_lp]['n'];
		}else{
			$n_lp = false;
		}

        $current = $soul_writein_Dlinked_List[$c_lp];

	    $c_usable = $c_soul_usable[$current['c']];		
        
		if (false !== $c_usable['p']){
			
		    gen_code_4_debug_usable_array($c_usable['p'],$p_lp,$c_lp,'0xaaaaaaaa');
			
		}
        if (false !== $c_usable['n']){
			
		    gen_code_4_debug_usable_array($c_usable['n'],$c_lp,$n_lp,'0xbbbbbbbb');
		}


        
        
		

	    if (false === $n_lp){
		    break;
		}else{
			if (isset($soul_writein_Dlinked_List[$n_lp]['p'])){				
				$p_lp = $soul_writein_Dlinked_List[$n_lp]['p'];
			}else{
				$p_lp = $c_lp;
			}
			$c_lp = $n_lp;
		}
	}

}
?>