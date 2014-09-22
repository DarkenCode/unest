<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}





class GenerateFunc{

	
	public static function get_inst_define($opt,$para){
		global $Intel_instruction;
		$ret = false;
		if (isset($Intel_instruction[$opt])){
			$ret = $Intel_instruction[$opt];
			if (isset($ret['multi_op'])){
				$para_count = intval(count($para));
				if (isset($ret[$para_count])){
					$ret = $ret[$para_count];
				}else{
					$ret = false;
				}
			}
		}
		return $ret;
	}	
	
	
	
	
	
	public static function bits_precision_adjust($bits){
		if ($bits <= 8){
			$bits = 8;
		}else{
			$bits = 32;
		}
		return $bits;
	}

	
	
	
	public static function get_bit_from_inter($value){
		if (!is_numeric($value)){ 
			return false;
		}
		$a = intval($value);
		if ($a == $value){        
			if (($a <= 127) and ($a >= -128)){
				return 8;
			}
			if (($a <= 32767) and ($a >= -32768)){
				return 16;
			}
			if (($a <= 2147483647) and ($a >= -2147483648)){
				return 32;
			}
		}

		return false;
	}

	
	
	private static function is_32bit_hex($value){
		return preg_match("/^[0-9A-F]{1,8}$/",$value);
	}
		
	
	
	
	
	
	public static function my_rand($n){
		
		if (1 == $n){
			return true;
		}
		if ($n < 1){
			return false;
		}

		if (1 < mt_rand (1,$n)){
			return false;
		}

		return true;
	}

	
	
	
	
	public static function multi_level_rand($one,$two){
		$a = mt_rand(1,$one);
		$a = intval(ceil($two / $a));
		$b = mt_rand(1,$a);
		return $b;
	}


	public static function reset_ipsp_list_by_stack_pointer_define($sp_define,&$list,$soul){
		$ret = false;
		$tmp = $list;
		foreach ($tmp as $i => $a){
			if ((true !== $a['ipsp']) && (!isset($a['label']))){
				if (true === GeneralFunc::is_effect_ipsp($soul[$a['c']],1,$sp_define)){
					$list[$i]['ipsp'] = true;
					$ret[$i] = true;
				}
			}
		}
		return $ret;
	}


	
	
	
	
	
	public static function rand_interger($bits = false){

		$usable_bits = array(4 => true,8 => true,16 => true,32 => true);

		$ret = 1;
		
		$tmp = $usable_bits;

		$new_ret['value'] = 1;
		$new_ret['bits']  = 4;
			
		if (false !== $bits){ 
			foreach ($tmp as $a => $b){
				if ($a > $bits){
					unset ($usable_bits[$a]);    
				}
			}
		}   

		if (count($usable_bits)){
			$bits = array_rand($usable_bits); 		
		
			
			if (4 === $bits){
				$ret = mt_rand (0,7);
			}elseif (8 === $bits){
				$ret = mt_rand (8,127);
			}elseif (16 === $bits){
				$ret = mt_rand (128,32767);   
			}else{
				$ret = mt_rand (32768,2147483647);			
			}	

			if (mt_rand(0,1)){
				$ret = '-'.$ret;
			}
		}
		$new_ret['value'] = $ret;
		$new_ret['bits']  = $bits;
		return $new_ret;
	}

	
	
	public static function reloc_inc_copy_naked($index,$copy = 0){
		global $c_rel_info;
		$copy ++;
		while (isset($c_rel_info[$index][$copy])){
			$copy ++; 
		}
		return $copy;    
	}


	
	private static function gen_asm_file_kid($c_sec,$c_obj,&$buf,&$buf_head,$enter_flag,&$reloc_info_2_rewrite_table,&$non_null_labels,$commit=''){
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

	
	public static function gen_asm_file($out_file,$a,$c_Asm_Result,&$reloc_info_2_rewrite_table,&$non_null_labels){
		global $UniqueHead;

		global $c_soul_usable;
		
		global $poly_result_array;
		global $bone_result_array;
		global $meat_result_array;

		global $max_output; 



		global $my_params;

		$total_buf = '';	
		$enter_flag = "\r\n";
			
			if ($buf_head = IOFormatParser::out_file_buff_head($a)){
				$buf_head .= $enter_flag;
			}

			$buf = 'sec_'."$a".'_start:'."$enter_flag";
			
			
			$next = ConstructionDlinkedListOpt::readListFirstUnit();

			while (true){
				
				
				if ($max_output){
					$max_output --;
				}

				$current = ConstructionDlinkedListOpt::getDlinkedList($next);
				
				
				
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
					if (ConstructionDlinkedListOpt::issetRelJmpRange($next)){
						$show_len .= '[range (without Fat):';
						$show_len .= ConstructionDlinkedListOpt::readRelJmpRange($next,'range');
						$show_len .=']';          
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
					self::gen_asm_file_kid($a,$poly_result_array[$current['poly']]['code'][$current['c']],$buf,$buf_head,$enter_flag,$reloc_info_2_rewrite_table,$non_null_labels,$poly_comment.$show_len);				
				}elseif (isset ($current['bone'])){							
					self::gen_asm_file_kid($a,$bone_result_array[$current['bone']]['code'][$current['c']],$buf,$buf_head,$enter_flag,$reloc_info_2_rewrite_table,$non_null_labels,';&&& bone'.$show_len);
				}elseif (isset ($current['meat'])){							
					self::gen_asm_file_kid($a,$meat_result_array[$current['meat']]['code'][$current['c']],$buf,$buf_head,$enter_flag,$reloc_info_2_rewrite_table,$non_null_labels,' ;*** meat'.$show_len);
				}else{
					self::gen_asm_file_kid($a,$c_Asm_Result[$current['c']],$buf,$buf_head,$enter_flag,$reloc_info_2_rewrite_table,$non_null_labels,';### org opt'.$show_len);
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
				
				if (ConstructionDlinkedListOpt::issetDlinkedListUnit($next,'n')){	
					$next =  ConstructionDlinkedListOpt::getDlinkedList($next,'n');
					

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

}