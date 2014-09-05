<?php

define('UNEST.ORG', TRUE);
ini_set('display_errors',0);
error_reporting(E_ERROR); 


$stack_pointer_reg = 'SP';






require_once dirname(__FILE__)."/include/func.gen.php";

require_once dirname(__FILE__)."/include/intel_instruction.php";
require_once dirname(__FILE__)."/include/intel_instruction_array.php";
require_once dirname(__FILE__)."/include/config.inc.php";

require_once dirname(__FILE__)."/include/func.poly.php";
require_once dirname(__FILE__)."/models/model_poly.php";

require_once dirname(__FILE__)."/include/func.bone.php";
require_once dirname(__FILE__)."/models/model_bone.php";

require_once dirname(__FILE__)."/include/func.meat.php";


require_once dirname(__FILE__)."/include/func.fat.php";

require_once dirname(__FILE__)."/include/func.config.php";

require_once dirname(__FILE__)."/../nasm.inc.php";

require_once dirname(__FILE__)."/include/opcode_len_array.php";

require_once dirname(__FILE__)."/include/func.lencode.php";

require_once dirname(__FILE__)."/include/func.rel.jmp.php";

require_once dirname(__FILE__)."/include/func.debug.php";



$my_params = get_params($argv);



$complete_finished = false; 
register_shutdown_function('shutdown_except');



if (isset($my_params['maxstrength'])){
    $max_strength = $my_params['maxstrength'];
}else{
    $max_strength = false;
}

if (isset($my_params['maxoutput'])){
    $max_output = $my_params['maxoutput'];
}else{
    $max_output = false;
}

if (isset($my_params['timelimit'])){
    set_time_limit($my_params['timelimit']);
}else{
    $output['error'][] = 'need param timelimit';
}

if (isset($my_params['base'])){
    $base_addr = $my_params['base'];
}else{
    $output['error'][] = 'need param base';
}

if (isset($my_params['log'])){
    $log_path = $base_addr.'/'.$my_params['log'];
}else{
    $output['error'][] = 'need param log';
}

if (!isset($my_params['outputfile'])){
    $output['error'][] = 'need param outputfile';
}else{
    $outputfile = $base_addr.'/'.$my_params['outputfile'];	
	$out_file   = $base_addr.'/'.$my_params['outputfile'].".out.asm";
}





	$poly_enable = false;                          
    

	
	
	
	
	
			$poly_enable = true;
	
	
	
	
	
	


if (!isset($my_params['filename'])){
    $output['error'][] = 'need param filename';
}else{
    $filename = $my_params['filename'];
	
	
	
	$obj_filename = $base_addr."/"."$filename";
}

if (!isset($my_params['cnf'])){
    $output['error'][] = 'need param cnf';
}else{
	$usr_cfg_file = $base_addr.'/'.$my_params['cnf']; 
}

if (!isset($my_params['rdy'])){
    $output['error'][] = 'need param rdy';
}else{
	$rdy_file = $base_addr.'/'.$my_params['rdy']; 
}

if (!is_file($obj_filename)){
    $output['error'][] = 'file is not exist: '.$obj_filename;
}

if (isset($my_params['sd'])){
    $setvalue_dynamic = $my_params['sd'];
}else{
    $setvalue_dynamic = false;	
}

if (empty($output['error'])){

	$cf = @file_get_contents($rdy_file);

	if ($cf == false){
		$output['error'][] = 'fail to open ready file';
	
	}else{
		
		$cf = unserialize($cf);

		$StandardAsmResultArray = $cf['StandardAsmResultArray'];
		$garble_rel_info        = $cf['garble_rel_info'];
		
		$UniqueHead             = $cf['UniqueHead'];
		$CodeSectionArray       = $cf['CodeSectionArray'];
		$soul_usable            = $cf['soul_usable'];
		$soul_forbid            = $cf['soul_forbid'];
		$all_valid_mem_opt_index= $cf['valid_mem_index'];
		$sec_name               = $cf['sec_name'];
		$soul_writein_Dlinked_List_Total = $cf['soul_writein_Dlinked_List_Total'];
		$avmoi_ptr              = $cf['valid_mem_index_ptr']; 
		$avmoi_ptr ++;
		$all_valid_mem_opcode_len = $cf['valid_mem_len'];
		
		
		$output_type            = $cf['output_type'];
		$ready_preprocess_config= $cf['preprocess_config'];   
		$dynamic_insert         = $cf['dynamic_insert'];      

		$preprocess_sec_name    = $cf['preprocess_sec_name']; 

        
		$file_format_parser = dirname(__FILE__)."/mod.file.format/".$output_type."/out.inc.php";
		if (file_exists($file_format_parser)){
			require $file_format_parser;
		}else{
			$output['error'][] = 'type without file format parser';
		}
        
		$rel_jmp_range    = $cf['rel_jmp_range'];
		$rel_jmp_pointer  = $cf['rel_jmp_pointer'];
		$rel_jmp_switcher = $cf['rel_jmp_switcher'];

		if ($engin_version !== $cf['engin_version']){
			$output['error'][] = 'unmatch generat version: '."$engin_version".' !== '.$cf['engin_version'];
		}

		unset($cf);

		if (('BIN' !== $output_type)&&('COFF' !== $output_type)){ 
			$output['error'][] = $language['unkown_output_type']."$output_type";      
		}

		if (!empty($dynamic_insert)){ 
		    get_dynamic_insert_value($dynamic_insert);
		}

		$poly_strength          = array();   
		$poly_result_array      = array();   

		$pattern_reloc           = '/('."$UniqueHead".'RELINFO_[\d]{1,}_[\d]{1,}_[\d]{1,})/';  
		$pattern_reloc_4_replace = '/('."$UniqueHead".'RELINFO_(\d+)_(\d+)_(\d+))/';  

		$mem_usage_record = array(); 
		
		$exetime_record = array();
		$stime = 0;
		exetime_record($stime); 

		
		
		$user_config = false;
		$user_strength = false;
		$preprocess_config = array(); 
		$user_cnf = array();          
		$c_sec_name = array();
		if (false === get_usr_config($sec_name,$usr_cfg_file,$user_config,$user_strength,$user_cnf,$preprocess_config,false,$c_sec_name)){ 
			$output['error'][] = $language['without_cfg_file'];        
		}
        		
		if (false !== $setvalue_dynamic){
			affect_setvalue_dynamic($sec_name,$setvalue_dynamic,$user_cnf);
		}		
			
		
		
		
		
		
		
		
		
		
		if ($ready_preprocess_config !== $preprocess_config){
			$output['error'][] = $language['nomatch_preprocess_config'];		
		}
		unset($ready_preprocess_config);
		unset($preprocess_config);
        
        foreach ($c_sec_name as $a => $b){
		    if (!isset($preprocess_sec_name[$a])){
			    $output['error'][] = $language['new_sec_increase_rdy'].$a;
			}
		}
		unset($c_sec_name);

		
		
		
		
		
		
		
		reconfigure_soul_usable ($sec_name,$user_config,$user_cnf,$soul_writein_Dlinked_List_Total,$soul_usable,$soul_forbid); 

		
        $init_asm_file = "[bits 32]\r\n";
        foreach ($dynamic_insert as $a => $b){			
			if (isset($b['new'])){
			    $insert_value = $b['new'];
			}else{           
				$insert_value = $b['default'];
			}
			$init_asm_file .= '%define '.$UniqueHead.'dynamic_insert_'.$a.' '.$insert_value."\r\n";
		}

		file_put_contents($out_file,$init_asm_file);

		$reloc_info_2_rewrite_table = array(); 
											   

		$non_null_labels = array();             
												
	}
}

if (empty($output['error'])){

	
    foreach ($CodeSectionArray as $sec => $body){

        
		
		
		if (!empty($output['error'])){
		    break;
		}

		echo "<br>++++++++++++++++++++++++ $sec ++++++++++++++++++++++++++ <br>";
        
		
		$c_user_cnf_stack_pointer_define = false;
		if (is_array($user_cnf[$sec]['stack_pointer_define'])){
			foreach ($user_cnf[$sec]['stack_pointer_define'] as $a){
				foreach ($registersss as $b => $c){
					if (isset($c[$a])){
						$c_user_cnf_stack_pointer_define  .=  '('.$c[$a].')|';					
					}
				}
			}
			if (false !== $c_user_cnf_stack_pointer_define){
			    $c_user_cnf_stack_pointer_define = substr ($c_user_cnf_stack_pointer_define,0,strlen($c_user_cnf_stack_pointer_define) - 1);
			}
		}
		
		if (false !== $c_user_cnf_stack_pointer_define){	
			$tmp = reset_ipsp_list_by_stack_pointer_define($c_user_cnf_stack_pointer_define,$soul_writein_Dlinked_List_Total[$sec]['list'],$StandardAsmResultArray[$sec]);

		}

		
		
		unset ($soul_writein_Dlinked_List);
		$soul_writein_Dlinked_List = $soul_writein_Dlinked_List_Total[$sec]['list'];
		$s_w_Dlinked_List_index = $soul_writein_Dlinked_List_Total[$sec]['index'];
		$soul_writein_Dlinked_List_start = 0; 
		$org_List = $soul_writein_Dlinked_List;                                    
		$org_length_soul_writein_Dlinked_List = count($soul_writein_Dlinked_List); 
		
        $c_Asm_Result  = $StandardAsmResultArray[$sec];       
		$c_soul_usable = $soul_usable[$sec];	
		
        
		$c_rel_jmp_range    = $rel_jmp_range[$sec];
		$c_rel_jmp_pointer  = $rel_jmp_pointer[$sec];
		$c_rel_jmp_switcher = $rel_jmp_switcher[$sec];
		if (isset($user_cnf[$sec]['output_opcode_max'])){ 
            $c_rel_jmp_switcher = true;
			$c_usable_oplen     =  $user_cnf[$sec]['output_opcode_max'] - $body['SizeOfRawData']; 
			
			
			if ($c_usable_oplen < 0){         
				$output['error'][]   = $language['section_name'].$body['name'].$language['section_number']."$sec ".$language['max_output_less_org'];
				break;
			}elseif ($c_usable_oplen === 0){  
				$output['warning'][] = $language['section_name'].$body['name'].$language['section_number']."$sec ".$language['max_output_equal_org'];		
				continue;
			}
		}else{
		    $c_usable_oplen     = false;
		}
		
		
		if (isset($garble_rel_info[$sec])){
			$c_rel_info = $garble_rel_info[$sec];
		}else{
			$c_rel_info = false;
		}


		
        
        

       if (true === $user_cnf[$sec]['gen4debug01']){
		    $output['notice'][] = "gen4debug01 option was effected on section: $sec , name: ".$body['name']; 
			debug_usable_array($soul_writein_Dlinked_List_start);
			if ($c_rel_jmp_switcher){ 
			    $tmp = reset_rel_jmp_array($check_rel_jmp_range,$check_rel_jmp_pointer,false,false,$soul_writein_Dlinked_List_start);
				if (false === $tmp){  
				    $output['error'][] = $language['debug_rel_jmp_out_range'];
				}
			}
            
			
		}else{
		
			if (isset($user_cnf[$sec]['meat_mutation'])){
				$c_MeatMutation = $user_cnf[$sec]['meat_mutation'];
			}else{
				$c_MeatMutation = 10; 
									  
			}

			if (isset($user_cnf[$sec]['soul_focus'])){
				$c_SoulFocus = $user_cnf[$sec]['soul_focus'];
			}else{
				$c_SoulFocus = 3;     
								  
								  
			}
			
			

			
			
			unset ($poly_result_array);
			$poly_result_array = array();
			unset ($poly_result_reverse_array);
			$poly_result_reverse_array = array();
			

			
			
			unset ($bone_result_array);
			$bone_result_array = array();      
			
			
			
			unset ($meat_result_array);
			$meat_result_array = array();        
			

			
			
			
			
			
			
			$c_user_strength = $user_strength[$sec];
			$default_max = 0;
			if (isset($c_user_strength['default'])){
				$default_max = intval(ceil(($org_length_soul_writein_Dlinked_List * $c_user_strength['default'])/100));
			}
			
			if (!isset($c_user_strength['poly']['max'])){
				$c_user_strength['poly']['max'] = $default_max;			
			}
			if (!isset($c_user_strength['poly']['min'])){
				$c_user_strength['poly']['min'] = intval($c_user_strength['poly']['max']/2);
			}elseif ($c_user_strength['poly']['max'] < $c_user_strength['poly']['min']){
				$c_user_strength['poly']['max'] = $c_user_strength['poly']['min'];
			} 
			
			if (!isset($c_user_strength['bone']['max'])){
				$c_user_strength['bone']['max'] = $default_max;			
			}
			if (!isset($c_user_strength['bone']['min'])){
				$c_user_strength['bone']['min'] = intval($c_user_strength['bone']['max']/2);
			}elseif ($c_user_strength['bone']['max'] < $c_user_strength['bone']['min']){
				$c_user_strength['bone']['max'] = $c_user_strength['bone']['min'];
			}
			
			if (!isset($c_user_strength['meat']['max'])){
				$c_user_strength['meat']['max'] = $default_max;			
			}
			if (!isset($c_user_strength['meat']['min'])){
				$c_user_strength['meat']['min'] = intval($c_user_strength['meat']['max']/2);
			}elseif ($c_user_strength['meat']['max'] < $c_user_strength['meat']['min']){
				$c_user_strength['meat']['max'] = $c_user_strength['meat']['min'];
			}
			
			if ($my_params['echo']){
				var_dump ($c_user_strength);
			}

			$c_poly_strength = mt_rand($c_user_strength['poly']['min'],$c_user_strength['poly']['max']);
			$c_bone_strength = mt_rand($c_user_strength['bone']['min'],$c_user_strength['bone']['max']);
			$c_meat_strength = mt_rand($c_user_strength['meat']['min'],$c_user_strength['meat']['max']);
			
			
			if (false !== $max_strength){
				if ($c_poly_strength > $max_strength){
					$output['notice'][] = $language['strength_too_bit'].'poly'.', ('.$c_poly_strength.' -> '.$max_strength.')';
					$c_poly_strength = $max_strength;
				}
				if ($c_bone_strength > $max_strength){
					$output['notice'][] = $language['strength_too_bit'].'bone'.', ('.$c_bone_strength.' -> '.$max_strength.')';
					$c_bone_strength = $max_strength;
				}
				if ($c_meat_strength > $max_strength){
					$output['notice'][] = $language['strength_too_bit'].'meat'.', ('.$c_meat_strength.' -> '.$max_strength.')';
					$c_meat_strength = $max_strength;
				}
			}

			echo '<br>poly strength: '.$c_poly_strength.' ; bone strength: '.$c_bone_strength.' ; meat strength: '.$c_meat_strength;


			$garble_process = array();	

			if (true === $poly_enable){
				for ($i = $c_poly_strength;$i > 0;$i--){		    
					$garble_process[] = 'poly';
				}    
			}

			for ($i = $c_bone_strength;$i > 0;$i--){		    
				$garble_process[] = 'bone';
			}
			
			for ($i = $c_meat_strength;$i > 0;$i--){		    
				$garble_process[] = 'meat';
			}

			if (0 == count($garble_process)){ 
				$output['warning'][] = $language['section_name'].$body['name'].$language['section_number']."$sec ".$language['section_without_garble'];			
			}
			
			shuffle($garble_process);

			if ($my_params['echo']){
				var_dump ($garble_process);
			}
			
			foreach ($garble_process as $c_process){			
				
				unset ($rollback);
				$rollback['List']            = $soul_writein_Dlinked_List;
				$rollback['List_start']      = $soul_writein_Dlinked_List_start;
				$rollback['List_index']      = $s_w_Dlinked_List_index;
				$rollback['rel_jmp_range']   = $c_rel_jmp_range;
				$rollback['rel_jmp_pointer'] = $c_rel_jmp_pointer;
				$rollback['usable_oplen']    = $c_usable_oplen;
				
				if ('poly' === $c_process){		
					
					
					$pointer = array_rand($org_List); 
					$length  = mt_rand (1,$org_length_soul_writein_Dlinked_List); 


					$pointer = array_rand($soul_writein_Dlinked_List);
					

					$objs = collect_obj($pointer,$length);
					
					if (!empty($objs)){
						poly_start($objs,$my_params['echo']); 
						$exetime_record['poly'] += exetime_record($stime); 
					}				
				}elseif ('meat' === $c_process){	
					
					
					$pointer = array_rand($org_List); 
					$length  = mt_rand (1,$org_length_soul_writein_Dlinked_List);                    

					$pointer = array_rand($soul_writein_Dlinked_List);
					

					$objs = collect_obj($pointer,$length);
					
					
					if (!empty($objs)){
						meat_create($objs,$length * 2);  					
						$exetime_record['meat'] += exetime_record($stime); 
					}
				}elseif ('bone' === $c_process){
					
					

					$multi_bone_poly = false;         
					
					$pointer = array_rand($org_List); 
					$length  = mt_rand (1,$org_length_soul_writein_Dlinked_List); 

					$pointer = array_rand($soul_writein_Dlinked_List);
					$length  = multi_level_rand(10,count($soul_writein_Dlinked_List));
					
				   
				   
				   
					$objs = collect_obj($pointer,$length);				

					if (!empty($objs)){
						bone_create($objs,$output,$language);

						if ($multi_bone_poly){
							if ($my_params['echo']){
								echo '<br>多通道 骨架可能需要 多态 副本通道内单位:';
								var_dump ($multi_bone_poly);
								echo "<br><br><br>List view before poly:";
								var_dump ($soul_writein_Dlinked_List);
							}
							foreach ($multi_bone_poly as $z){
								poly_start($z,$my_params['echo']);
							}						
						}
						$exetime_record['bone'] += exetime_record($stime); 
					}
				}else{
					$output['warning'][] = 'unkown act in process: '.$c_process.' at section: '.$sec.'.';
					continue;
				}
				
				
				if ((!empty($objs)) && (true === $c_rel_jmp_switcher)){	
					
					$break_now = false;
					$ret_rel_jmp_deal = reset_rel_jmp_array($c_rel_jmp_range,$c_rel_jmp_pointer,$objs,$rollback['List'],$soul_writein_Dlinked_List_start);
					if ($ret_rel_jmp_deal){
						$rel_jmp_range_key = array();
						foreach ($c_rel_jmp_range as $a => $b){
							$rel_jmp_range_key[$a] = $a;
						}
						$ret_rel_jmp_deal = resize_rel_jmp_array($rel_jmp_range_key,$rollback['rel_jmp_range']); 
					}

					if ($c_usable_oplen < 0){
						$ret_rel_jmp_deal = false;
						$break_now = true;
						echo "<br><font color=red>c_usable_oplen is done ... $c_usable_oplen...</font><br>";
					}

					if (false === $ret_rel_jmp_deal){
					  
					  
					  echo "<br><font color=red>roll back...</font><br>";
					  var_dump ($c_rel_jmp_range);				

					  $soul_writein_Dlinked_List       = $rollback['List'];
					  $soul_writein_Dlinked_List_start = $rollback['List_start'];
					  $s_w_Dlinked_List_index          = $rollback['List_index'];
					  $c_rel_jmp_range                 = $rollback['rel_jmp_range'];
					  $c_rel_jmp_pointer               = $rollback['rel_jmp_pointer'];		
					  $c_usable_oplen                  = $rollback['usable_oplen'];
					}              
					$exetime_record['adjust_rel_jmp'] += exetime_record($stime); 
					
					if (true === $break_now){ 
						break;
					}			
				}			
			}

			

			

			echo '<br><br> $c_usable_oplen with fat: ';
			var_dump ($c_usable_oplen);
		}

	    
		
		if (false === gen_asm_file($out_file,$sec,$soul_writein_Dlinked_List,$c_Asm_Result,$reloc_info_2_rewrite_table,$non_null_labels)){
			if (0 === $max_output){
				$output['error'][] = $language['too_big_output'];
			}else{
				$output['error'][] = $language['unkown_fatal_error_113'];
			}		
		}		

		$exetime_record['gen_asm_file'] += exetime_record($stime); 
		
        $mem_usage_record[$sec] = number_format(memory_get_usage());

		echo '<br><br> $c_usable_oplen with fat: ';
		var_dump ($c_usable_oplen);
	}		
}
   

	if (empty($output['error'])){			
			
			$report_filename = "$out_file".'.report';
			$binary_filename = out_file_gen_name();
			
			exec ("$nasm -f bin \"$out_file\" -o \"$binary_filename\" -Z \"$report_filename\" -Xvc");
			
			$exetime_record['nasm final obj'] = exetime_record($stime); 

			if (file_exists($binary_filename)){
				$newCodeSection = array(); 
										   
										   
				out_file_format_gen();

				
				foreach ($user_cnf as $uc_sec => $v){
                    if (isset($user_cnf[$uc_sec]['output_opcode_max'])){
					    if ($user_cnf[$uc_sec]['output_opcode_max'] < $newCodeSection[$uc_sec]['size']){
							
							$output['error'][] = $language['output_more_max'];
							
							unlink($binary_filename);
						    break;
						}
					}
				}
				
			}else{ 
			    $output['error'][] = 'compile fail, generate stopped.';
			}
			$exetime_record['others'] = exetime_record($stime); 

	}

    
	if (isset($my_params['log'])){ 
	    file_put_contents($base_addr.'/'.$my_params['log'],json_encode($output));  
	}

    $complete_finished = true; 
exit;



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






function hex_write(&$buf,$lp,$contents,$bits = 4){
	if (!$isHex){
		$tmp = '%0'.($bits*2).'x';
	    $y = sprintf($tmp,$contents);
	}
   	if ($bits == 4){
		$buf[$lp + 3] = pack("H*",substr($y,0,2));
		$buf[$lp + 2] = pack("H*",substr($y,2,2));
		$buf[$lp + 1] = pack("H*",substr($y,4,2));
		$buf[$lp]     = pack("H*",substr($y,6,2));
	    return true;
	}elseif ($bits == 2){
		$buf[$lp + 1] = pack("H*",substr($y,0,2));
		$buf[$lp]     = pack("H*",substr($y,2,2));	
	}
	return false;
}



function gen_asm_file($out_file,$a,$soul_writein_Dlinked_List,$c_Asm_Result,&$reloc_info_2_rewrite_table,&$non_null_labels){
	global $UniqueHead;
    global $soul_writein_Dlinked_List_start;
    global $c_soul_usable;
	
	global $poly_result_array;
	global $bone_result_array;
	global $meat_result_array;

	global $max_output; 

	global $c_rel_jmp_range;

	global $my_params;

	$total_buf = '';	
    $enter_flag = "\r\n";
	    
		if ($buf_head = out_file_buff_head($a)){
		    $buf_head .= $enter_flag;
		}

		$buf = 'sec_'."$a".'_start:'."$enter_flag";
		
		
		$next = $soul_writein_Dlinked_List_start;

		while (true){
			
			
			if ($max_output){
			    $max_output --;
			}

			$current = $soul_writein_Dlinked_List[$next];
            
			
			
            if (isset ($current['bone'])){
			    if (1 === $bone_result_array[$current['bone']]['fat'][$current['c']]){ 
					$buf .= fat_create(5,$enter_flag,$next,1);
				}				
			}elseif (isset ($current['poly'])){
				if (1 === $poly_result_array[$current['poly']]['fat'][$current['c']]){ 
					$buf .= fat_create(5,$enter_flag,$next,1);
				}
			}		
			
            
			
			if ($my_params['echo']){
				$show_len = '[List No:'.$next.'] [len:'.$current['len'].']';
				if (isset($c_rel_jmp_range[$next])){
					$show_len .= '[range:'.$c_rel_jmp_range[$next]['range'].']';
				}
			}else{
			    $show_len = '';
			}

            if (isset($current['label'])){
			    $buf .= $current['label']."$enter_flag";
			}elseif (isset ($current['poly'])){	
				$poly_comment = ';@@@ poly';
				if (true === $current['soul']){
					$poly_comment = ';@@@ poly [from org]';
				}
				gen_asm_file_kid($a,$poly_result_array[$current['poly']]['code'][$current['c']],$buf,$buf_head,$enter_flag,$reloc_info_2_rewrite_table,$non_null_labels,$poly_comment.$show_len);				
            }elseif (isset ($current['bone'])){							
				gen_asm_file_kid($a,$bone_result_array[$current['bone']]['code'][$current['c']],$buf,$buf_head,$enter_flag,$reloc_info_2_rewrite_table,$non_null_labels,';&&& bone'.$show_len);
			}elseif (isset ($current['meat'])){							
				gen_asm_file_kid($a,$meat_result_array[$current['meat']]['code'][$current['c']],$buf,$buf_head,$enter_flag,$reloc_info_2_rewrite_table,$non_null_labels,' ;*** meat'.$show_len);
			}else{
                gen_asm_file_kid($a,$c_Asm_Result[$current['c']],$buf,$buf_head,$enter_flag,$reloc_info_2_rewrite_table,$non_null_labels,';### org opt'.$show_len);
			}
            
			
			
            if (isset ($current['bone'])){
			    if (2 === $bone_result_array[$current['bone']]['fat'][$current['c']]){ 
				    $buf .= fat_create(5,$enter_flag,$next,2);
				}
			}elseif (isset ($current['poly'])){
				if (2 === $poly_result_array[$current['poly']]['fat'][$current['c']]){ 
					$buf .= fat_create(5,$enter_flag,$next,2);
				}
			}
            
			if (isset($soul_writein_Dlinked_List[$next]['n'])){
				$next =  $soul_writein_Dlinked_List[$next]['n'];
				
				

			}else{
				break;
			}
		}	

		$buf .= 'sec_'."$a".'_end:'."$enter_flag";
           
		$total_buf .= "$enter_flag".";********** section $a **********"."$enter_flag";
		$total_buf .= "$buf_head";
		$total_buf .= "$buf";
	
	if (0 === $max_output){	
	    return false;
	}
	

    file_put_contents ($out_file,$total_buf,FILE_APPEND);
    return true;

}

function gen_asm_file_kid($c_sec,$c_obj,&$buf,&$buf_head,$enter_flag,&$reloc_info_2_rewrite_table,&$non_null_labels,$commit=''){
    global $pattern_reloc;
	global $c_rel_info;
	global $sec;
    global $UniqueHead;

	$asm = '';
	
	
	
	
	$rel_param_result = false;

	if (is_array($c_obj['prefix'])){
		foreach ($c_obj['prefix'] as $z => $y){
			$asm .= $y.' ';
		}
	}
	$asm .= $c_obj['operation'].' ';
								
	$last_params_type = 0;      
	$last_params_cont = "";     
	$mem_bits = 0;              
	if (is_array($c_obj['params'])){
		foreach ($c_obj['params'] as $z => $y){
			if ($z){
				$asm .= ',';
			}
			
			if (isset($c_obj['rel'][$z])){ 
				$rel_param_result[$z]['org'] = $y;				             
			}
			
			if ($c_obj['p_type'][$z] == 'm'){        
				$mem_bits = $c_obj['p_bits'][$z];
				
				if ('LEA' !== $c_obj['operation']){  
					if (8 == $c_obj['p_bits'][$z]){
						$asm .= 'byte ';
					}elseif (16 == $c_obj['p_bits'][$z]){
						$asm .= 'word ';
					}else{
						$asm .= 'dword ';
					}
				}
			}			
			$asm .= $y;

			$last_params_cont = $y;                  
			$last_params_type = $c_obj['p_type'][$z];
			$last_params_bits = $c_obj['p_bits'][$z];
		}
	}	

	if (false !== $rel_param_result){
		
		if (count($rel_param_result) > 1){
		    var_dump ($rel_param_result);
		}
		$label_buf = '';
        foreach ($rel_param_result as $z => $y){
            $c_rel_index = $c_obj['rel'][$z]['i'];
			$c_rel_copy  = $c_obj['rel'][$z]['c'];
			$c_rel_name  = $UniqueHead.'RELINFO_'.$sec.'_'.$c_rel_index.'_'.$c_rel_copy; 
			
			
			
			
			
			if (($c_rel_info[$c_rel_index][$c_rel_copy]['isMem'])&&($last_params_type === 'i')){
				$asm  = substr($asm,0,strlen($asm) - strlen($last_params_cont));
				
				$last_params_modified_bits = $mem_bits;
				if ((8 == $last_params_bits) or (16 == $last_params_bits) or (32 == $last_params_bits)){ 
				    if ($last_params_bits < $mem_bits){
					    $last_params_modified_bits = $last_params_bits;
					}
					
				}
				if (8 == $last_params_modified_bits){
					$asm .= 'byte strict '.$last_params_cont;
					$buf_head .= 'dd '.$c_rel_name.'_label - sec_'."$c_sec".'_start - 4 - 1'."$enter_flag";                
				}elseif (16 == $last_params_modified_bits){
					$asm .= 'word strict '.$last_params_cont;
					$buf_head .= 'dd '.$c_rel_name.'_label - sec_'."$c_sec".'_start - 4 - 2'."$enter_flag";                
				}else{
					$asm .= 'dword strict '.$last_params_cont;
					$buf_head .= 'dd '.$c_rel_name.'_label - sec_'."$c_sec".'_start - 4 - 4'."$enter_flag";                
				}
				
				if ($mem_bits != $last_params_modified_bits){
					echo "<br> BUG?? : $mem_bits , $last_params_bits , $last_params_modified_bits , $last_params_type";
					echo "<br> <font color=red> $asm </font>";
				}
				
			}else{
				$buf_head .= 'dd '.$c_rel_name.'_label - sec_'."$c_sec".'_start - 4'."$enter_flag";                
			}
									                                                   
			$buf_head .= 'dd '.$c_rel_info[$c_rel_index][$c_rel_copy]['SymbolTableIndex']."$enter_flag";  
			$buf_head .= 'dw '.$c_rel_info[$c_rel_index][$c_rel_copy]['Type']."$enter_flag";

			$reloc_info_2_rewrite_table[$c_sec][] = $c_rel_name;
			if ($c_rel_info[$c_rel_index][$c_rel_copy]['isLabel']){ 
				str_replace($c_rel_name,' strict '.$c_rel_name.'_label',$asm);
				$asm = str_replace($c_rel_name,' strict '.$c_rel_name.'_label',$asm);
				if ($c_rel_info[$c_rel_index][$c_rel_copy]['value'] !== '0'){
					$non_null_labels[$sec][$c_rel_index][$c_rel_copy] = $c_rel_info[$c_rel_index][$c_rel_copy]['value'];
				}
			}else{                                              
				if ($c_rel_info[$c_rel_index][$c_rel_copy]['isMem']){
					$asm = str_replace('[','[DWORD ',$asm);                     
					$asm = str_replace($c_rel_name,'0x'.$c_rel_info[$c_rel_index][$c_rel_copy]['value'],$asm);
				}else{                                          
																
																
					$asm = str_replace($y['org'],' strict dword '.$y['org'],$asm);
					$asm = str_replace($c_rel_name,'0x'.$c_rel_info[$c_rel_index][$c_rel_copy]['value'],$asm);
				}
			}
			$label_buf .= "$enter_flag".$c_rel_name.'_label'.' : ';
		}
		$buf .= $asm;
		$buf .= $label_buf;
	}else{			    
		$buf .= $asm;
	}			
	$buf .= "$commit"."$enter_flag";

    return;
}



function get_hex_2_dec($buf,$lpBuf,$size,$dec = false){
    $tmp = '';
	$lpBuf += $size;
	$lpBuf --;
	for (;$size;$size--){
	    $tmp .= substr($buf,$lpBuf,1);
		$lpBuf --;
	}
	$tmp = bin2hex($tmp);
	if ($dec){
		$tmp = hexdec($tmp);
	}
    return $tmp;
}



function get_dynamic_insert_value (&$dynamic_insert){
	global $output;
	global $language;
    global $my_params;

    $new_dynamic_insert = $my_params['di'];
	if (isset($new_dynamic_insert)){
	    if (is_array($new_dynamic_insert)){
		    foreach ($new_dynamic_insert as $key => $value){
			    if (isset($dynamic_insert[$key])){					
					$tmp = get_bit_from_inter($value);
                    if ($tmp){
						if ($tmp <= $dynamic_insert[$key]['bits']){
							$dynamic_insert[$key]['new'] = $value;
						}else{
						    $output['error'][] = $language['toobig_dynamci_insert_value'].'di['.$key.'] : '.$tmp.' > '.$dynamic_insert[$key]['bits'];	
						}
					}else{
					    $output['error'][] = $language['illegal_dynamci_insert_value'].$value;	
					}
				}else{
				    $output['error'][] = $language['none_dynamic_insert_key'].$key;
				}
			}
		}else{
		    $output['error'][] = $language['dynamic_insert_not_array'];
		}
	}
	
	
	
}

?>