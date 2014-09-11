<?php

define('UNEST.ORG', TRUE);
ini_set('display_errors',0);
error_reporting(E_ERROR); 

require_once dirname(__FILE__)."/include/ready.func.php";
require_once dirname(__FILE__)."/include/ready.preprocess.php";

require_once dirname(__FILE__)."/include/intel_instruction.php";

require_once dirname(__FILE__)."/include/intel_instruction_array.php";

require_once dirname(__FILE__)."/include/config.inc.php";

require_once dirname(__FILE__)."/include/func.gen.php";

require_once dirname(__FILE__)."/include/func.config.php";

require_once dirname(__FILE__)."/include/func.rel.jmp.php";

require_once dirname(__FILE__)."/../nasm.inc.php";



$stack_pointer_reg = 'ESP';



$my_params = get_params($argv);



$complete_finished = false; 
register_shutdown_function('shutdown_except');

if (isset($my_params['maxinput'])){
    $max_input = $my_params['maxinput'];
}else{
    $max_input = false;
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
if (!isset($my_params['path'])){
    $output['error'][] = 'need param path';
}

if (!isset($my_params['filename'])){
    $output['error'][] = 'need param filename';
}else{
    $input_filename = $base_addr.'/'.$my_params['path'].'/'.$my_params['filename'];
}

if (!isset($my_params['cnf'])){
    $output['error'][] = 'need param cnf';
}else{
	$usr_cfg_file = $base_addr.'/'.$my_params['cnf']; 
}


if ('bin' === $my_params['type']){
    $output_type = 'BIN';
}elseif ('coff' === $my_params['type']){	
	$output_type = 'COFF';
}else{
	$output['error'][] = 'need param type';
}

$file_format_parser = dirname(__FILE__)."/mod.file.format/".$output_type."/in.inc.php";

if (file_exists($file_format_parser)){
	require $file_format_parser;
}else{
    $output['error'][] = 'type without file format parser';
}

if (!isset($my_params['output'])){
    $output['error'][] = 'need param output';
}else{
	$bin_file = $base_addr.'/'.$my_params['output'].'/'.$my_params['filename'].".bin";
	$asm_file = $base_addr.'/'.$my_params['output'].'/'.$my_params['filename'].".asm";
	$rdy_file = $base_addr.'/'.$my_params['output'].'/'.$my_params['filename'].".rdy";     
	$out_file = $base_addr.'/'.$my_params['output'].'/'.$my_params['filename'].".out.asm";	
}


$UniqueHead = 'UNEST_'; 
$pattern_reloc = '/('."$UniqueHead".'RELINFO_[\d]{1,}_[\d]{1,}_[\d]{1,})/';  



$exetime_record = array();
$stime = 0;
exetime_record($stime); 



	$myTables = array();
	$handle = fopen($input_filename,'rb');
	if (!$handle){
	    $output['error'] = 'fail to open file:'.$input_filename;
	}else{
		$buf = fread($handle,filesize($input_filename));
		fclose($handle);

		$input_filesize = filesize($input_filename);
        
		in_file_format();
	    $exetime_record['analysis_input_file_format'] = exetime_record($stime); 
	}



if (empty($output['error'])){
    $user_config = false;
	$user_strength = false;
	$preprocess_config = array();   
	$protect_section   = array();   
	$dynamic_insert    = array();   
	$user_cnf = array();            
	$preprocess_sec_name = array();   

	if (false === get_usr_config(false,$usr_cfg_file,$user_config,$user_strength,$user_cnf,$preprocess_config,true,$preprocess_sec_name)){ 
	    $output['error'][] = $language['without_cfg_file'];        
	}

	if (!count($preprocess_sec_name)){
	    $output['error'][] = $language['without_act_sec'];        
	}else{
		
		$ignore_sec = $preprocess_sec_name;
		$tmp = $myTables['CodeSectionArray'];
		foreach ($tmp as $a => $b){
			if (!isset($preprocess_sec_name[$b['name']])){
				unset ($myTables['CodeSectionArray'][$a]);
			}else{
			    unset($ignore_sec[$b['name']]);
			}
		}

		if (count($ignore_sec)){
			foreach ($ignore_sec as $a => $b){
				$output['notice'][] = $language['ignore_ready_sec'].$a;
			}
		}
        
	}

	unset($user_config);
	unset($user_strength);

	if (isset($preprocess_config['protect_section'])){  
		$protect_section = $preprocess_config['protect_section'];
		
		if (is_overlap_section($protect_section)){
			$output['error'][] = $language['overlay_protect_section'];      
		}	
	}
	if (isset($preprocess_config['dynamic_insert'])){  
		$dynamic_insert = $preprocess_config['dynamic_insert'];
		
		if (is_overlap_section($dynamic_insert)){
			$output['error'][] = $language['overlay_dynamic_insert'];      
		}	
	}	
}


if (empty($output['error'])){
	
	$protect_section_array = false; 
	if (!empty($protect_section)){
		$protect_section_array = bind_protect_section_2_sec($protect_section,$myTables['CodeSectionArray'],$language,$output);
	}
	
}

if (empty($output['error'])){
	
	$dynamic_insert_array = false; 
	if (!empty($dynamic_insert)){
		$dynamic_insert_array = bind_dynamic_insert_2_sec($dynamic_insert,$myTables['CodeSectionArray'],$language,$output);
	}
	
}



    if (empty($output['error'])){
		
		
		
		
		
		
		
        
		
		$bin_filesize = 0;

		if (!count($myTables['CodeSectionArray'])){ 
		    $output['error'][] = $language['no_target_sec'];
		}else{		
			$p_sec_abs = array(); 
			$asm_size = collect_and_disasm($bin_file,$asm_file,$disasm,$myTables['CodeSectionArray'],$buf,$bin_filesize,$protect_section_array,$p_sec_abs,$output,$language,false);            

			if (empty($output['error'])){
				$exetime_record['collect_and_disasm'] = exetime_record($stime); 
			   
				$LineNum_Code2Reloc = array();  
												
												
												
				$AsmLastSec = array();          
												
				if ($asm_size){
					if (format_disasm_file($asm_file,$bin_filesize,$AsmResultArray,$output,$language)){
						$exetime_record['format_disasm_file'] = exetime_record($stime); 
                        if (!empty($protect_section)){ 
						    format_protect_section ($p_sec_abs,$AsmResultArray,$output,$language);
							$exetime_record['format_protect_section'] = exetime_record($stime); 
						}
						sec_reloc_format($myTables,$AsmResultArray,$AsmLastSec,$output,$language,$LineNum_Code2Reloc);			
						$exetime_record['sec_reloc_format'] = exetime_record($stime); 
					}
				}else{
					$output['error'][] = $language['disasm_file_not_found'];
				}
			}
		}
	}

    if (empty($output['error'])){
		
		
		
		$solid_jmp_array = array();    
		$solid_jmp_to    = array();    

		eip_label_replacer($AsmLastSec,$solid_jmp_array,$solid_jmp_to,$myTables,$AsmResultArray,$LineNum_Code2Reloc,$output,$language);

		
		rel_label_replacer($myTables,$AsmResultArray,$LineNum_Code2Reloc,$output,$language);

		$exetime_record['eip rel label replace'] = exetime_record($stime); 
	   
		$garble_rel_info = array();  
									 
									 
									 
									 
									 
									 
									 
        
		$z = $myTables['CodeSectionArray'];
		foreach ($z as $a => $b){
			if (is_array($myTables['RelocArray'][$a])){
				foreach ($myTables['RelocArray'][$a] as $c => $d){
					if (((20 === $d['Type']) && (true === $d['isLabel'])) || ((6 === $d['Type']) && (!$d['isLabel']))){
					    $garble_rel_info[$a][$c][0] = $d;
					}else{
						unset ($myTables['CodeSectionArray'][$a]);						
                        $output['warning'][] = $language['section_name']." ".$b['name'].$language['section_number']." $a ".$language['illegal_rel_type'];
                        break;  
					}
				}
			}
		}

		
		$StandardAsmResultArray = array();	
											
											
											
											
											
											
											
											
											
											
											
											
											


		
		$normal_register_opt_array = array(); 
											  
		$flag_register_opt_array   = array(); 
											  
		$valid_mem_opt_array       = array(); 
											  
											  
											  
											  
											  
											  
											  
        $stack_used                = array(); 
		$stack_broke               = array(); 


		standard_asm($myTables,$garble_rel_info,$AsmResultArray,$StandardAsmResultArray,$stack_used,$stack_broke,$output,$language);
		
		
		
		
		$exetime_record['disasm to standard'] = exetime_record($stime); 
		

		
		$exec_thread_list = array();        
											
											
											
		exec_thread_list_get($myTables['CodeSectionArray'],$StandardAsmResultArray,$exec_thread_list,$solid_jmp_to,$AsmLastSec);

		$exetime_record['exec thread list'] = exetime_record($stime); 

		
		$soul_forbid = array(); 
		$soul_usable = array();             
											
											
											
											
											
											

		
		get_soul_usable_limit($myTables['CodeSectionArray'],$exec_thread_list,$StandardAsmResultArray,$stack_used,$stack_broke);
		
		$exetime_record['usable register and memory'] = exetime_record($stime); 

		
		
		$all_valid_mem_opt_index   = array();   
												
												
		$soul_usable = compress_same_char_output($soul_usable,$all_valid_mem_opt_index);        
		
		$exetime_record['compress same char to output'] = exetime_record($stime); 

		
		
		
		$sec_name = array();
		foreach ($myTables['CodeSectionArray'] as $a => $b){
			$sec_name[$b['name']][] = $a;
		}

        
        
		$soul_writein_Dlinked_List_Total = array();
		foreach ($myTables['CodeSectionArray'] as $sec => $b){            
		    $soul_writein_Dlinked_List = array();
			$s_w_Dlinked_List_index = 0;
			$prev = false;	
			$c_solid_jmp_array = $solid_jmp_array[$sec];
			$c_Asm_Result = $StandardAsmResultArray[$sec];
			$lp_asm_result = count($c_Asm_Result) + 1;

			$label_index = -1; 
        	foreach ($c_Asm_Result as $z => $y){			
			    $gret = generat_soul_writein_Dlinked_List($soul_writein_Dlinked_List,$z,$y,$s_w_Dlinked_List_index,$prev,$c_solid_jmp_array);
			    unset ($c_solid_jmp_array[$z]);
			}
			if (!empty($c_solid_jmp_array)){
				foreach ($c_solid_jmp_array as $x => $y){
					if ($x <= $z){ 
								   
						$output['error'][] = $language['section_name']." ".$b['name'].$language['section_number']." $sec ".$language['jmp_dest_out_rang_error'];
						break;
					}
					foreach ($y as $w){
						add_soul_writein_Dlinked_List($soul_writein_Dlinked_List,$s_w_Dlinked_List_index,$prev,$w,$z,true);				
					}
				}		
			}
			$soul_writein_Dlinked_List_Total[$sec]['index'] = $s_w_Dlinked_List_index;   
			$soul_writein_Dlinked_List_Total[$sec]['list']  = $soul_writein_Dlinked_List;
			
		
		
		
		
		
		
		}
	}    

    
	$dynamic_insert_result = array();
	if (empty($output['error'])){
		$dynamic_insert_result = dynamic_insert_dealer($dynamic_insert_array,$StandardAsmResultArray);
	}

    if (empty($output['error'])){
		
		parser_rel_usable_mem ($all_valid_mem_opt_index);

	    
	    
		scan_affiliate_usable ($soul_usable,$soul_forbid);

		
		foreach ($sec_name as $a => $b){	    
		    foreach ($b as $c => $sec_id){
				$c_list = $soul_writein_Dlinked_List_Total[$sec_id]['list'][0]; 
				while (true){					  
                    $f = $c_list['c'];				    
					if (true === $soul_usable[$sec_id][$f]['p']['stack']){ 
					    unset($soul_usable[$sec_id][$f]['p']['normal_write_able'][$stack_pointer_reg]);
                        $soul_forbid[$sec_id][$f]['p']['normal'][$stack_pointer_reg][32] = true;
					}
					if (true === $soul_usable[$sec_id][$f]['n']['stack']){ 
					    unset($soul_usable[$sec_id][$f]['n']['normal_write_able'][$stack_pointer_reg]);
                        $soul_forbid[$sec_id][$f]['n']['normal'][$stack_pointer_reg][32] = true;
					}
					if (isset($c_list['n'])){
						$c_list = $soul_writein_Dlinked_List_Total[$sec_id]['list'][$c_list['n']];
					}else{
						break;
					}
				}
			}    
		}		


        $all_valid_mem_opcode_len = array();
		
		
		
		require_once dirname(__FILE__)."/include/opcode_len_array.php";
		require_once dirname(__FILE__)."/include/func.lencode.php";
		
		foreach ($soul_writein_Dlinked_List_Total as $number => $z){			
			foreach ($soul_writein_Dlinked_List_Total[$number]['list'] as $a => $b){
				if (isset($b['label'])){
				
				}else{
					if (isset($StandardAsmResultArray[$number][$b['c']]['p_type'])){
						foreach ($StandardAsmResultArray[$number][$b['c']]['p_type'] as $c => $d){
							if ('m' === $d){
								$c_len = code_len($StandardAsmResultArray[$number][$b['c']],true);
								if ($c_len <= $b['len']){
									$all_valid_mem_opcode_len[$StandardAsmResultArray[$number][$b['c']]['params'][$c]] = $b['len'] - $c_len;
								}	
							}
						}
					}
				}
				
			}
			echo "<br>##########################  $number ######################";  
			var_dump ($all_valid_mem_opcode_len);
				
		}
				 
		$exetime_record['init mem_addition'] = exetime_record($stime); 
		
        
		
		foreach ($soul_writein_Dlinked_List_Total as $sec => $a){
			$soul_writein_Dlinked_List = $soul_writein_Dlinked_List_Total[$sec]['list'];
			$c_Asm_Result = $StandardAsmResultArray[$sec];
			foreach ($soul_writein_Dlinked_List as $a => $b){
				$tmp = get_addition_List_info($a,false,true);
				if (isset($tmp['rel_jmp'])){
					$soul_writein_Dlinked_List_Total[$sec]['list'][$a]['rel_jmp'] = $tmp['rel_jmp'];
				}
			}
		}
        
		
		$rel_jmp_range    = array();
		$rel_jmp_pointer  = array();
		$rel_jmp_switcher = array(); 
		$c_usable_oplen   = false;   
		foreach ($soul_writein_Dlinked_List_Total as $sec => $a){
			$soul_writein_Dlinked_List = $soul_writein_Dlinked_List_Total[$sec]['list'];
			if (!reset_rel_jmp_array($rel_jmp_range[$sec],$rel_jmp_pointer[$sec])){ 
			    $output['error'][] = $language['init_rel_jmp_fail'];  
			}else{
				if (is_array($rel_jmp_range[$sec])){
					foreach ($rel_jmp_range[$sec] as $z => $y){
						if (false !== $y['max']){
							$rel_jmp_switcher[$sec] = true;
							break;
						}
					}
				}
			}
		}
		
		
		if (false === true){
			require_once dirname(__FILE__)."/include/opcode_len_array.php";
		    require_once dirname(__FILE__)."/include/func.lencode.php";
            
			foreach ($soul_writein_Dlinked_List_Total as $number => $b){
                echo "<br>##########################  $number ######################";  			
				foreach ($soul_writein_Dlinked_List_Total[$number]['list'] as $a => $b){
					if (isset($b['label'])){
					
					
					}else{
						$c_len = code_len($StandardAsmResultArray[$number][$b['c']]);
						
							echo "<br>";
							var_dump($StandardAsmResultArray[$number][$b['c']]);
							echo "<br>len = ".$b['len'];
							echo " = $c_len";
						
					}
				}
			}
			exit;
		}
		
        
		redeal_split_opt($StandardAsmResultArray,$exec_thread_list,$soul_forbid,$soul_usable);
		
        
		
        foreach ($StandardAsmResultArray as $a => $b){
			soul_stack_set($StandardAsmResultArray[$a],$soul_usable[$a]);
		}
         
		if (empty($output['error'])){
			
			
			$rdy_output['StandardAsmResultArray']          = $StandardAsmResultArray;
			$rdy_output['garble_rel_info']                 = $garble_rel_info;
			
			$rdy_output['UniqueHead']                      = $UniqueHead;
			$rdy_output['CodeSectionArray']                = $myTables['CodeSectionArray'];

			$rdy_output['preprocess_sec_name']             = $preprocess_sec_name;
			
			$rdy_output['soul_usable']                     = $soul_usable;
			$rdy_output['soul_forbid']                     = $soul_forbid;
			$rdy_output['valid_mem_index']                 = $all_valid_mem_opt_index;
			$rdy_output['valid_mem_len']                   = $all_valid_mem_opcode_len;
			$rdy_output['valid_mem_index_ptr']             = count($all_valid_mem_opt_index);
			$rdy_output['sec_name']                        = $sec_name;
			$rdy_output['soul_writein_Dlinked_List_Total'] = $soul_writein_Dlinked_List_Total;
		  
	      
			$rdy_output['output_type']                     = $output_type; 
			$rdy_output['engin_version']                   = $engin_version;
			$rdy_output['preprocess_config']               = $preprocess_config;
			$rdy_output['dynamic_insert']                  = $dynamic_insert_result;
			
			$rdy_output['rel_jmp_range']    = $rel_jmp_range;
			$rdy_output['rel_jmp_pointer']  = $rel_jmp_pointer;
			$rdy_output['rel_jmp_switcher'] = $rel_jmp_switcher;


			file_put_contents($rdy_file,serialize($rdy_output)); 
		}

     
	}

    echo "<br><br><br><br>";
	echo "binary size: ";
	var_dump ($asm_size);
	echo "<br><br><br><br>";

    $complete_finished = true;

exit;




function scan_affiliate_usable (&$soul_usable,&$soul_forbid){
	global $all_valid_mem_opt_index;
    
	
    $tmp = $soul_usable;
	foreach ($tmp as $sec => $a){	    
		foreach ($a as $line => $b){
		    
			if (is_array($b['p']['mem_opt_able'])){
			    
				    foreach ($b['p']['mem_opt_able'] as $mem_index){
						if (isset($all_valid_mem_opt_index[$mem_index]['reg'])){
						    foreach ($all_valid_mem_opt_index[$mem_index]['reg'] as $reg){								
							    if (isset($b['p']['normal_write_able'][$reg])){
								    unset ($soul_usable[$sec][$line]['p']['normal_write_able'][$reg]);
								}
                                $soul_forbid[$sec][$line]['p']['normal'][$reg][32] = true;								
								
							}
						}
					}
				
			}
			if (is_array($b['n']['mem_opt_able'])){
			    
				    foreach ($b['n']['mem_opt_able'] as $mem_index){
						if (isset($all_valid_mem_opt_index[$mem_index]['reg'])){
						    foreach ($all_valid_mem_opt_index[$mem_index]['reg'] as $reg){
							    if (isset($b['n']['normal_write_able'][$reg])){
									unset ($soul_usable[$sec][$line]['n']['normal_write_able'][$reg]);
								    
								}
								$soul_forbid[$sec][$line]['n']['normal'][$reg][32] = true;
							}
						}
					}
				
			}
		}
	}
	return true;

}



function parser_rel_usable_mem (&$all_valid_mem_opt_index){
	
	global $pattern_reloc;

    $tmp = $all_valid_mem_opt_index;
	foreach ($tmp as $a => $b){
		if (preg_match($pattern_reloc,$b['code'],$z)){
			$z = explode ('_',$z[0]);
			$all_valid_mem_opt_index[$a]['rel'] = $z[3];
		}	
	}
}




function add_soul_writein_Dlinked_List(&$soul_writein_Dlinked_List,&$num,&$prev,$label,$asm,$ia = false){
	global $label_index;
	global $sec;
	global $soul_usable;
	global $soul_forbid;

	global $AsmResultArray;

	if (false !== $prev){
		$soul_writein_Dlinked_List[$num]['p']  = $prev;
		$soul_writein_Dlinked_List[$prev]['n'] = $num;
	}
	if (false !== $label){
		$soul_writein_Dlinked_List[$num]['label'] = $label;
		
        if (true === $ia){ 
		    if (isset($soul_usable[$sec][$asm]['n'])){ 
				$soul_usable[$sec][$label_index]['p'] = $soul_usable[$sec][$asm]['n'];		
				$soul_usable[$sec][$label_index]['n'] = $soul_usable[$sec][$asm]['n'];
			}
			if (isset($soul_forbid[$sec][$asm]['n'])){ 
				$soul_forbid[$sec][$label_index]['p'] = $soul_forbid[$sec][$asm]['n'];	
				$soul_forbid[$sec][$label_index]['n'] = $soul_forbid[$sec][$asm]['n'];	
			}
		}else{ 
		    if (isset($soul_usable[$sec][$asm]['p'])){ 
				$soul_usable[$sec][$label_index]['p'] = $soul_usable[$sec][$asm]['p'];		
				$soul_usable[$sec][$label_index]['n'] = $soul_usable[$sec][$asm]['p'];	
			}
			if (isset($soul_forbid[$sec][$asm]['p'])){ 
				$soul_forbid[$sec][$label_index]['p'] = $soul_forbid[$sec][$asm]['p'];	
				$soul_forbid[$sec][$label_index]['n'] = $soul_forbid[$sec][$asm]['p'];	
			}
		}
		$soul_writein_Dlinked_List[$num]['c'] = $label_index;
		$soul_writein_Dlinked_List[$num]['len'] = 0; 
		$label_index --;
	}else{
		global $c_Asm_Result;
		global $p_sec_abs;

		if (isset($p_sec_abs[$asm])){ 
		    $soul_writein_Dlinked_List[$num]['ipsp'] = true;
		}elseif (is_effect_ipsp($c_Asm_Result[$asm])){ 
		    $soul_writein_Dlinked_List[$num]['ipsp'] = true;
		}

        $soul_writein_Dlinked_List[$num]['c'] = $asm;
		
		$soul_writein_Dlinked_List[$num]['len'] = intval(strlen($AsmResultArray[$sec][$asm]['bin'])/2);		
	}
	
	$prev = $num;
	$num ++;
}

function generat_soul_writein_Dlinked_List(&$soul_writein_Dlinked_List,$a,$b,&$num,&$prev,$c_solid_jmp_array){	

	if (isset($c_solid_jmp_array[$a])){ 
		foreach ($c_solid_jmp_array[$a] as $z => $y){
			add_soul_writein_Dlinked_List($soul_writein_Dlinked_List,$num,$prev,$y,$a);				
		}
	}	
	
    add_soul_writein_Dlinked_List($soul_writein_Dlinked_List,$num,$prev,false,$a);

	return;
}



?>