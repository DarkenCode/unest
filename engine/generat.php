<?php

define('UNEST.ORG', TRUE);
ini_set('display_errors',0);
error_reporting(E_ERROR); 


$stack_pointer_reg = 'ESP';






require_once dirname(__FILE__)."/library/generate.func.php";
require_once dirname(__FILE__)."/library/general.func.php";

require dirname(__FILE__)."/library/data.construction.php";

require_once dirname(__FILE__)."/include/intel_instruction.php";
require_once dirname(__FILE__)."/include/intel_instruction_array.php";
require_once dirname(__FILE__)."/include/config.inc.php";


require dirname(__FILE__)."/organs/poly.php";
require dirname(__FILE__)."/models/model_poly.php";
require dirname(__FILE__)."/organs/bone.php";
require dirname(__FILE__)."/models/model_bone.php";
require dirname(__FILE__)."/organs/meat.php";
OrganMeat::init();


require_once dirname(__FILE__)."/organs/fat.php";

require_once dirname(__FILE__)."/include/func.config.php";

require_once dirname(__FILE__)."/../nasm.inc.php";

require_once dirname(__FILE__)."/include/opcode_len_array.php";

require_once dirname(__FILE__)."/include/func.lencode.php";

require_once dirname(__FILE__)."/include/func.rel.jmp.php";





$my_params = GeneralFunc::get_params($argv);



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
    GeneralFunc::LogInsert('need param timelimit');
}

if (isset($my_params['base'])){
    $base_addr = $my_params['base'];
}else{
    GeneralFunc::LogInsert('need param base');
}

if (isset($my_params['log'])){
    $log_path = $base_addr.'/'.$my_params['log'];
}else{
    GeneralFunc::LogInsert('need param log');
}

if (!isset($my_params['outputfile'])){
    GeneralFunc::LogInsert('need param outputfile');
}else{
    $outputfile = $base_addr.'/'.$my_params['outputfile'];	
	$out_file   = $base_addr.'/'.$my_params['outputfile'].".out.asm";
}





	$poly_enable = false;                          
    

	
	
	
	
	
			$poly_enable = true;
	
	
	
	
	
	


if (!isset($my_params['filename'])){
    GeneralFunc::LogInsert('need param filename');
}else{
    $filename = $my_params['filename'];
	
	
	
	$obj_filename = $base_addr."/"."$filename";
}

if (!isset($my_params['cnf'])){
    GeneralFunc::LogInsert('need param cnf');
}else{
	$usr_cfg_file = $base_addr.'/'.$my_params['cnf']; 
}

if (!isset($my_params['rdy'])){
    GeneralFunc::LogInsert('need param rdy');
}else{
	$rdy_file = $base_addr.'/'.$my_params['rdy']; 
}

if (!is_file($obj_filename)){
    GeneralFunc::LogInsert('file is not exist: '.$obj_filename);
}

if (isset($my_params['sd'])){
    $setvalue_dynamic = $my_params['sd'];
}else{
    $setvalue_dynamic = false;	
}

if (!GeneralFunc::LogHasErr()){

	$cf = @file_get_contents($rdy_file);

	if ($cf == false){
		GeneralFunc::LogInsert('fail to open ready file');
	
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

        
		$file_format_parser = dirname(__FILE__)."/IOFormatParser/".$output_type.".IO.php";
		if (file_exists($file_format_parser)){
			require $file_format_parser;
		}else{
			GeneralFunc::LogInsert('type without file format parser');
		}
        
		$rel_jmp_range    = $cf['rel_jmp_range'];
		$rel_jmp_pointer  = $cf['rel_jmp_pointer'];
		$rel_jmp_switcher = $cf['rel_jmp_switcher'];

		if ($engin_version !== $cf['engin_version']){
			GeneralFunc::LogInsert('unmatch generat version: '."$engin_version".' !== '.$cf['engin_version']);
		}

		unset($cf);

		if (('BIN' !== $output_type)&&('COFF' !== $output_type)){ 
			GeneralFunc::LogInsert($language['unkown_output_type']."$output_type");      
		}

		if (!empty($dynamic_insert)){ 
		    GeneralFunc::get_dynamic_insert_value($dynamic_insert);
		}

		$poly_strength          = array();   
		$poly_result_array      = array();   

		$pattern_reloc           = '/('."$UniqueHead".'RELINFO_[\d]{1,}_[\d]{1,}_[\d]{1,})/';  
		$pattern_reloc_4_replace = '/('."$UniqueHead".'RELINFO_(\d+)_(\d+)_(\d+))/';  

		$mem_usage_record = array(); 
		
		$exetime_record = array();
		GeneralFunc::exetime_record(); 

		
		
		$user_config = false;
		$user_strength = false;
		$preprocess_config = array(); 
		$user_cnf = array();          
		$c_sec_name = array();
		if (false === get_usr_config($sec_name,$usr_cfg_file,$user_config,$user_strength,$user_cnf,$preprocess_config,false,$c_sec_name)){ 
			GeneralFunc::LogInsert($language['without_cfg_file']);        
		}
        		
		if (false !== $setvalue_dynamic){
			affect_setvalue_dynamic($sec_name,$setvalue_dynamic,$user_cnf);
		}		
			
		
		
		
		
		
		
		
		
		
		if ($ready_preprocess_config !== $preprocess_config){
			GeneralFunc::LogInsert($language['nomatch_preprocess_config']);		
		}
		unset($ready_preprocess_config);
		unset($preprocess_config);
        
        foreach ($c_sec_name as $a => $b){
		    if (!isset($preprocess_sec_name[$a])){
			    GeneralFunc::LogInsert($language['new_sec_increase_rdy'].$a);
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

if (!GeneralFunc::LogHasErr()){

	
    foreach ($CodeSectionArray as $sec => $body){

        
		
		
		if (GeneralFunc::LogHasErr()){
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
			$tmp = GenerateFunc::reset_ipsp_list_by_stack_pointer_define($c_user_cnf_stack_pointer_define,$soul_writein_Dlinked_List_Total[$sec]['list'],$StandardAsmResultArray[$sec]);			
		}

		
		
		ConstructionDlinkedListOpt::init($soul_writein_Dlinked_List_Total[$sec],$rel_jmp_range[$sec],$rel_jmp_pointer[$sec]);
		
		$org_List = $soul_writein_Dlinked_List_Total[$sec]['list']; 
		$org_length_soul_writein_Dlinked_List = count($org_List);   
		
        $c_Asm_Result  = $StandardAsmResultArray[$sec];       
		$c_soul_usable = $soul_usable[$sec];	
		
        
		
		$c_rel_jmp_switcher = $rel_jmp_switcher[$sec];
		if (isset($user_cnf[$sec]['output_opcode_max'])){ 
            $c_rel_jmp_switcher = true;
            $tmp = ConstructionDlinkedListOpt::OplenInit($user_cnf[$sec]['output_opcode_max'] - $body['SizeOfRawData']); 
			if (1 == $tmp){       
				GeneralFunc::LogInsert($language['section_name'].$body['name'].$language['section_number']."$sec ".$language['max_output_less_org']);
				break;
			}elseif (2 == $tmp){  
				GeneralFunc::LogInsert($language['section_name'].$body['name'].$language['section_number']."$sec ".$language['max_output_equal_org'],2);		
				continue;
			}
		}

		
		if (isset($garble_rel_info[$sec])){
			$c_rel_info = $garble_rel_info[$sec];
		}else{
			$c_rel_info = false;
		}


		
        
        

       if (true === $user_cnf[$sec]['gen4debug01']){
		   

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
			
			$c_poly_strength = mt_rand($c_user_strength['poly']['min'],$c_user_strength['poly']['max']);
			$c_bone_strength = mt_rand($c_user_strength['bone']['min'],$c_user_strength['bone']['max']);
			$c_meat_strength = mt_rand($c_user_strength['meat']['min'],$c_user_strength['meat']['max']);
			
			
			if (false !== $max_strength){
				if ($c_poly_strength > $max_strength){
					GeneralFunc::LogInsert($language['strength_too_bit'].'poly'.', ('.$c_poly_strength.' -> '.$max_strength.')',3);
					$c_poly_strength = $max_strength;
				}
				if ($c_bone_strength > $max_strength){
					GeneralFunc::LogInsert($language['strength_too_bit'].'bone'.', ('.$c_bone_strength.' -> '.$max_strength.')',3);
					$c_bone_strength = $max_strength;
				}
				if ($c_meat_strength > $max_strength){
					GeneralFunc::LogInsert($language['strength_too_bit'].'meat'.', ('.$c_meat_strength.' -> '.$max_strength.')',3);
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
				GeneralFunc::LogInsert($language['section_name'].$body['name'].$language['section_number']."$sec ".$language['section_without_garble'],2);			
			}
			
			shuffle($garble_process);
			
			foreach ($garble_process as $c_process){			
				
				ConstructionDlinkedListOpt::ready();

				
				if ('poly' === $c_process){		
					
					
					$pointer = array_rand($org_List); 
					$length  = mt_rand (1,$org_length_soul_writein_Dlinked_List); 

					$pointer = ConstructionDlinkedListOpt::getRandDlinkedListUnit();

					$objs = ConstructionDlinkedListOpt::collect_obj_from_DlinkedList($pointer,$length);
					
					if (!empty($objs)){
						OrganPoly::start($objs,$my_params['echo']); 
						$exetime_record['poly'] += GeneralFunc::exetime_record($stime); 
					}										
				}elseif ('meat' === $c_process){
					
					
					$pointer = array_rand($org_List); 
					$length  = mt_rand (1,$org_length_soul_writein_Dlinked_List);                    

					$pointer = ConstructionDlinkedListOpt::getRandDlinkedListUnit();

					$objs = ConstructionDlinkedListOpt::collect_obj_from_DlinkedList($pointer,$length);
					
					
					if (!empty($objs)){
						OrganMeat::start($objs,$length * 2);  					
						$exetime_record['meat'] += GeneralFunc::exetime_record($stime); 
					}
				}elseif ('bone' === $c_process){
					
					

					$multi_bone_poly = false;         
					
					$pointer = array_rand($org_List); 
					$length  = mt_rand (1,$org_length_soul_writein_Dlinked_List); 

					$pointer = ConstructionDlinkedListOpt::getRandDlinkedListUnit();
                    $length  = GenerateFunc::multi_level_rand(10,ConstructionDlinkedListOpt::numDlinkedList());
					
				   
				   
				   
					$objs = ConstructionDlinkedListOpt::collect_obj_from_DlinkedList($pointer,$length);				

					if (!empty($objs)){
						OrganBone::start($objs,$language);

						if ($multi_bone_poly){							
							foreach ($multi_bone_poly as $z){
								OrganPoly::start($z,$my_params['echo']);
							}						
						}
						$exetime_record['bone'] += GeneralFunc::exetime_record($stime); 
					}
				}else{
					GeneralFunc::LogInsert('unkown act in process: '.$c_process.' at section: '.$sec.'.',2);
					continue;
				}
				
				
				if ((!empty($objs)) && (true === $c_rel_jmp_switcher)){	
					
					$break_now = false;
					$ret_rel_jmp_deal = reset_rel_jmp_array($objs,ConstructionDlinkedListOpt::ReadRollingDlinkedList(),ConstructionDlinkedListOpt::readListFirstUnit());
					if ($ret_rel_jmp_deal){
						$rel_jmp_range_key = array();
						foreach (ConstructionDlinkedListOpt::readRelJmpRange() as $a => $b){
							$rel_jmp_range_key[$a] = $a;
						}
						$ret_rel_jmp_deal = resize_rel_jmp_array($rel_jmp_range_key); 
					}

					if (!ConstructionDlinkedListOpt::OplenIncrease(0)){
						$ret_rel_jmp_deal = false;
						$break_now = true;
						echo '<br><font color=red>c_usable_oplen is done ... $c_usable_oplen...</font><br>';
					}

					if (false === $ret_rel_jmp_deal){
					  
					  
					  echo "<br><font color=red>roll back...</font><br>";					  	
                      ConstructionDlinkedListOpt::rollback();
					}              
					$exetime_record['adjust_rel_jmp'] += GeneralFunc::exetime_record($stime); 
					
					if (true === $break_now){ 
						break;
					}			
				}			
			}

			

			

			
			
		}

	    
		
		if (false === GenerateFunc::gen_asm_file($out_file,$sec,$c_Asm_Result,$reloc_info_2_rewrite_table,$non_null_labels)){
			if (0 === $max_output){
				GeneralFunc::LogInsert($language['too_big_output']);
			}else{
				GeneralFunc::LogInsert($language['unkown_fatal_error_113']);
			}		
		}		

		$exetime_record['gen_asm_file'] += GeneralFunc::exetime_record($stime); 
		
        $mem_usage_record[$sec] = number_format(memory_get_usage());

		
		
	}		
}
   

	if (!GeneralFunc::LogHasErr()){		
			
			$report_filename = "$out_file".'.report';
			$binary_filename = IOFormatParser::out_file_gen_name();
			
			exec ("$nasm -f bin \"$out_file\" -o \"$binary_filename\" -Z \"$report_filename\" -Xvc");
			
			$exetime_record['nasm final obj'] = GeneralFunc::exetime_record($stime); 

			if (file_exists($binary_filename)){
				$newCodeSection = array(); 
										   
										   
				IOFormatParser::out_file_format_gen();

				
				foreach ($user_cnf as $uc_sec => $v){
                    if (isset($user_cnf[$uc_sec]['output_opcode_max'])){
						
					    if ($user_cnf[$uc_sec]['output_opcode_max'] < $newCodeSection[$uc_sec]['size']){
							GeneralFunc::LogInsert($language['output_more_max'].' (sec:'.$uc_sec.')');
							
							unlink($binary_filename);
						    break;
						}
					}
				}
				
			}else{ 
			    GeneralFunc::LogInsert('compile fail, generate stopped.');
			}
			$exetime_record['others'] = GeneralFunc::exetime_record($stime); 

	}

    
	
	
	

    $complete_finished = true; 
exit;





?>