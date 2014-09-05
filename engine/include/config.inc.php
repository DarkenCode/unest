<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}



$user_option['del_last_nop'] = true; 

$engin_version = 5;                  



$output['error']   = array(); 
$output['warning'] = array(); 
$output['notice']  = array(); 



$language = array(
    'not_support_Machine'     => 'not support current Machine',                             
	'illegal_support_Machine' => 'illegal signal of Machine',                               
	'illegal_opt_head_size'   => 'illegal size of optional header',                         
    'not_same_code_sym_array' => 'symbole table number different from section table number',
	'no_legal_opt_obj'        => 'not found deal object',                                   
	'too_much_sections'       => 'too much section table to deal',                          
	'disasm_file_not_found'   => 'disassemble fail',                                        
	'result_bin_not_same_size'=> 'fatal error, result size un match by binary size',        
	'open_asm_file_fail'      => 'fail to open disassemble file,operation stopped',         
	'illegal_binary'          => 'fail to analysis disassemble file,illegal binary code',   
	'no_target_sec'           => 'not found any target section',                            
	'section_name'            => 'section name:',
	'section_number'          => ',section number:',
	'reloc_rva'               => ',reloc offset',
	'last_not_align'          => ',fail to align last byte,give up',
	'rva_not_align'           => ',fail to align offset,give up',
    'disenable_rel_type'      => ',fail to dispose reloc attribute,give up',
	'rel32_not_null'          => ',illegal reloc default value,possible fail in runtime',
	'jmp_dest_out_rang'       => ',a static jump out of current section range,give up',
	'one_asm_more_reloc'      => ',single instruction has multi reloc record,give up',
	'cant_loc_value_or_label' => ',fail to locate integer part of reloc instruction,give up',
	'total_linenumber'        => ',line number',
	'unknow_instruction'      => ',unkown instruction,give up',
	'canot_deal_instruction'  => ',ignore instruction,give up',
	'jmp_dest_out_rang_error' => ',a static jump out of current section range,internal error,contact us',
	'nasm_param_not_found'    => 'undefined param:',
	'nasm_pnf_name'           => 'instruction:',
	'nasm_pnf_param_number'   => 'param number:',
	'multi_op_fail'           => 'fail to parse number of params,give up',
	'giveup_c_section'        => 'give up deal with current section',
	'obj_filename_del_fail'   => 'file is corrupted',
	'fail_bone_array'         => 'fail to allocate code,architecture config wrong,number:',
	'illegal_rel_type'        => 'undefined reloc type,give up',
	'poly_repo_null'          => 'undefined poly model number: ',
	'illega_mem_param'        => 'invalid memory address format: ',
	'without_cfg_file'        => 'fail to open configure file',
	'unkown_usr_cfg_line'     => 'configure file fail to be parsed, line',
	'section_without_garble'  => ',strength was set as 0,give up',
	'unkown_output_type'      => 'unkown file format',
	'too_big_input_01'        => 'The code line number (',
	'too_big_input_02'        => ') exceeds maximum (',
	'too_big_input_03'        => ')',
	'too_big_output'          => 'the output code line number exceeds maximum',
	'strength_too_bit'        => 'the strength number exceeds maximum of ',
	'nomatch_preprocess_config' => 'have to re-preprocess after preprocess config was modified',
	'protect_section_overflow_sec' => 'defined protect section overflow section boundary : ',
	'dynamic_insert_overflow_sec'  => 'defined dynamic insert overflow section boundary : ',
    'fail_split_protect_sec'  => 'fail to split protect section code',

	'overlay_protect_section' => 'protect section is overlaid with another',
	'overlay_dynamic_insert'  => 'dynamic insert is overlaid with another',
	'dynamic_insert_will_ignore' => 'illegal dynamic insert config,will be ignore : ',
	'dynamic_insert_not_array'=> 'illegal data type of dynamic insert submitted',
	'none_dynamic_insert_key' => 'dynamic insert to illegal address: ',
	'illegal_dynamci_insert_value' => 'illegal value of dynamic insert: ',
	'toobig_dynamci_insert_value'  => 'dynamic insert bits bigger than original : ',
	
	'dup_preprocessconfig'    => 'there are more than one preprocess section in configure file',
	'dup_section'             => 'dup section name in configure file : ',
	'unknown_cfg_sec_name'     => 'ignored unknown section name in configure file : ',
	'unknown_usr_cfg_name'     => 'unknown key name, line',       
	'dismatch_usr_cfg_value'   => 'mismatch in value type, line', 
	'double_usr_cfg_secname'   => 'can not set section name more than one, line',

	'without_act_sec'          => 'no obj section has been set',

    
	'debug_rel_jmp_out_range' => 'rel jmp range more than max,fail!',
	'invalid_debug_value'     => 'invalid debug value was defined: ',

    
	'setvalue_with_off'         => 'refuse setvalue in section : ',
	'setvalue_illegal_sec'      => 'unknown section name to setvalue : ',
	'setvalue_unknown_key'      => 'unknown setvalue name : ',
	'setvalue_mismatch_value'   => 'mismatch in setvalue : ',	

	'max_output_less_org'     => ',output_opcode_max value less than origin size', 
    'max_output_equal_org'    => ',output_opcode_max value equal with origin size, give up', 

    
	'unkown_fatal_error_113'  => 'unkown error #113',
	'too_many_arg'            => 'internal error #37，contact us please',
	'init_rel_jmp_fail'       => 'internal error #27，contact us please',
	'output_more_max'         => 'internal error #41, contact us please', 
    
	
	'ignore_ready_sec'        => 'ignore no exists section name (in config file): ',

	
	'new_sec_increase_rdy'    => 'new section be increased (in config file) after preprocessed: ',


);

$ARG_MAX = 5000;                     
$MAX_INCLUDE_MULTI_PROCESS_BONE = 50; 

?>