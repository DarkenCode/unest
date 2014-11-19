<?php
/*语言文件*/
$language = array(
    'not_support_Machine'     => 'not support current Machine',                             //'不支持的Machine标志 ',
	'illegal_support_Machine' => 'illegal signal of Machine',                               //'非法的Machine标志',
	'illegal_opt_head_size'   => 'illegal size of optional header',                         //'非法的可选头部长度',
    'not_same_code_sym_array' => 'symbole table number different from section table number',//'符号表与节表数目不一致',
	'no_legal_opt_obj'        => 'not found deal object',                                   //'未发现可操作目标!!!',
	'too_much_sections'       => 'too much section table to deal',                          //'目标文件节表数量过多',
	'disasm_file_not_found'   => 'disassemble fail',                                        //反汇编结果文件不存在，可能反汇编失败',
	'result_bin_not_same_size'=> 'fatal error, result size un match by binary size',        //'结果 长度 与 二进制代码长度不符，致命错误!!!',
	'open_asm_file_fail'      => 'fail to open disassemble file,operation stopped',         //打开 反汇编结果文件 失败，操作终止',
	'illegal_binary'          => 'fail to analysis disassemble file,illegal binary code',   //'分析 反汇编结果文件 失败，非法的2进制串',
	'no_target_sec'           => 'not found any target section',                            //未设置混淆目标?
	'section_name'            => 'section name:',//'节表名:',
	'section_number'          => ',section number:',//',节表编号:',
	'reloc_rva'               => ',reloc offset',//',重定位偏移',
	'last_not_align'          => ',fail to align last byte,give up',//',末位对齐失败,放弃对此节表处理',
	'rva_not_align'           => ',fail to align offset,give up',//',偏移对齐失败,放弃对此节表处理',
    'disenable_rel_type'      => ',fail to dispose reloc attribute,give up',//',无法处理的重定位项属性,放弃对此节表处理',
	'rel32_not_null'          => ',illegal reloc default value,possible fail in runtime',//',重定位目标相对偏移初始值非零,此重定位源如果为代码并被混淆处理,很可能运行时出错',
	'jmp_dest_out_rang'       => ',a static jump out of current section range,give up',//',固定跳转目的不在当前节表范围内,放弃对此节表处理',
	'one_asm_more_reloc'      => ',single instruction has multi reloc record,give up',//',单条指令对应多条重定位记录,放弃对此节表处理',
	'cant_loc_value_or_label' => ',fail to locate integer part of reloc instruction,give up',//',重定位指令无法定位数值部分,放弃对此节表处理',
	'total_linenumber'        => ',line number',//',总行数',
	'unknow_instruction'      => ',unkown instruction,give up',//',未知指令,放弃对此节表处理',
	'canot_deal_instruction'  => ',ignore instruction,give up',//',当前版本未处理指令,放弃对此节表处理',
	'jmp_dest_out_rang_error' => ',a static jump out of current section range,internal error,contact us',//',固定跳转目的不在当前节表范围内,内部错误,请联系我们',
	'nasm_param_not_found'    => 'undefined param:',//'指令参数定义缺失:',
	'nasm_pnf_name'           => 'instruction:',//'指令名:',
	'nasm_pnf_param_number'   => 'param number:',//'缺失参数编号:',
	'multi_op_fail'           => 'fail to parse number of params,give up',//'不被识别的参数个数,放弃对此节表处理',
	'giveup_c_section'        => 'give up deal with current section',//'放弃对此节表处理',
	'obj_filename_del_fail'   => 'file is corrupted',//'目标文件可能被占用，操作失败',
	'fail_bone_array'         => 'fail to allocate code,architecture config wrong,number:',//'分配代码至混淆架构位置时错误，可能是混淆架构设置错误，编号:',
	'illegal_rel_type'        => 'undefined reloc type,give up',//'重定位类型与应用不符,放弃对此节表处理',
	'poly_repo_null'          => 'undefined poly model number: ',//'多态模板编号未定义：',
	'illega_mem_param'        => 'invalid memory address format: ',//'错误的内存地址格式: ',
	'without_cfg_file'        => 'fail to open configure file',//'打开配置文件失败',
	'unkown_usr_cfg_line'     => 'configure file fail to be parsed, line',//'配置文件内容错误或无法被正确解析，行号',
	'section_without_garble'  => ',strength was set as 0,give up',//'，处理强度为0，不做任何处理。',
	'unkown_output_type'      => 'unkown file format',//'未知的格式定义: ',
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
	
	'dup_preprocessconfig'    => 'there are more than one preprocess section in configure file',//预处理config段超过一个
	'dup_section'             => 'dup section name in configure file : ',
	'unknown_cfg_sec_name'     => 'ignored unknown section name in configure file : ',
	'unknown_usr_cfg_name'     => 'unknown key name, line',       //用户设置 名 未知
	'dismatch_usr_cfg_value'   => 'mismatch in value type, line', //用户设置 值 不符
	'double_usr_cfg_secname'   => 'can not set section name more than one, line',

	'without_act_sec'          => 'no obj section has been set',

    //debug 
	'debug_rel_jmp_out_range' => 'rel jmp range more than max,fail!',
	'invalid_debug_value'     => 'invalid debug value was defined: ',

    //revalue
	'setvalue_with_off'         => 'refuse setvalue in section : ',
	'setvalue_illegal_sec'      => 'unknown section name to setvalue : ',
	'setvalue_unknown_key'      => 'unknown setvalue name : ',
	'setvalue_mismatch_value'   => 'mismatch in setvalue : ',	

	'max_output_less_org'     => ',output_opcode_max value less than origin size', //设置的最大输出字节数 小于 原代码字节数
    'max_output_equal_org'    => ',output_opcode_max value equal with origin size, give up', //设置的最大输出字节数 等于 原代码字节数

    //编号错误
	'unkown_fatal_error_113'  => 'unkown error #113',
	'too_many_arg'            => 'internal error #37，contact us please',
	'init_rel_jmp_fail'       => 'internal error #27，contact us please',
	'output_more_max'         => 'internal error #41, contact us please', //最后编译结果代码超过max_output设置,oplen 计算有误
    
	//ready
	'ignore_ready_sec'        => 'ignore no exists section name (in config file): ',

	//rdy
	'new_sec_increase_rdy'    => 'new section be increased (in config file) after preprocessed: ',


);
?>