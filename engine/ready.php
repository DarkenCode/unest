<?php

define('UNEST.ORG', TRUE);
//ini_set('display_errors',0);
error_reporting(E_ERROR); 

require dirname(__FILE__)."/library/ready.func.php";
require dirname(__FILE__)."/library/general.func.php";
require dirname(__FILE__)."/library/preprocess.func.php";

require dirname(__FILE__)."/library/data.construction.php";

require_once dirname(__FILE__)."/include/intel_instruction.php";

require_once dirname(__FILE__)."/include/intel_instruction_array.php";

require_once dirname(__FILE__)."/include/config.inc.php";

require dirname(__FILE__)."/library/config.func.php";

require dirname(__FILE__)."/library/rel.jmp.func.php";

require_once dirname(__FILE__)."/../nasm.inc.php";

//////////////////////////////////////////
//堆栈指针 寄存器
$stack_pointer_reg = 'ESP';

//////////////////////////////////////////
//同时支持$_GET/$_POST/命令行输入 的参数
$my_params = GeneralFunc::get_params($argv);

//////////////////////////////////////////
//捕获超时
$complete_finished = false; //执行完成标志
register_shutdown_function('shutdown_except');
//////////////////////////////////////////
//检查参数并构造路径等
if ($my_params['echo']){
    require_once dirname(__FILE__)."/library/debug_show.func.php";
}

if (isset($my_params['maxinput'])){
    $max_input = $my_params['maxinput'];
}else{
    $max_input = false;
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
if (!isset($my_params['path'])){
    GeneralFunc::LogInsert('need param path');
}

if (!isset($my_params['filename'])){
    GeneralFunc::LogInsert('need param filename');
}else{
    $input_filename = $base_addr.'/'.$my_params['path'].'/'.$my_params['filename'];
}

if (!isset($my_params['cnf'])){
    GeneralFunc::LogInsert('need param cnf');
}else{
	$usr_cfg_file = $base_addr.'/'.$my_params['cnf']; //用户设置
}


if ('bin' === $my_params['type']){
    $output_type = 'BIN';
}elseif ('coff' === $my_params['type']){	
	$output_type = 'COFF';
}else{
	GeneralFunc::LogInsert('need param type');
}

$file_format_parser = dirname(__FILE__)."/IOFormatParser/".$output_type.".IO.php";

if (file_exists($file_format_parser)){
	require $file_format_parser;
}else{
    GeneralFunc::LogInsert('type without file format parser');
}

if (!isset($my_params['output'])){
    GeneralFunc::LogInsert('need param output');
}else{
	$bin_file = $base_addr.'/'.$my_params['output'].'/'.$my_params['filename'].".bin";
	$asm_file = $base_addr.'/'.$my_params['output'].'/'.$my_params['filename'].".asm";
	$rdy_file = $base_addr.'/'.$my_params['output'].'/'.$my_params['filename'].".rdy";     //obj 分析完成保存文件
	$out_file = $base_addr.'/'.$my_params['output'].'/'.$my_params['filename'].".out.asm";	
}


$UniqueHead = 'UNEST_'; //独特的头部标志，防止与代码中字符冲突 (如冲突，增加随机字符，注:禁止增加下划线 英文字符必须为大写)
$pattern_reloc = '/('."$UniqueHead".'RELINFO_[\d]{1,}_[\d]{1,}_[\d]{1,})/';  //匹配 reloc 信息

if (!GeneralFunc::LogHasErr()){
	////////////////////////////////////////////////////////

	$exetime_record = array();
	GeneralFunc::exetime_record(); //获取程序开始执行的时间

	////////////////////////////////////////////////////////
	//目标处理文件格式处理
		$myTables = array();
		$handle = fopen($input_filename,'rb');
		if (!$handle){
			GeneralFunc::LogInsert('fail to open file:'.$input_filename);
		}else{
			$buf = fread($handle,filesize($input_filename));
			fclose($handle);

			$input_filesize = filesize($input_filename);
			
			IOFormatParser::in_file_format();
			$exetime_record['analysis_input_file_format'] = GeneralFunc::exetime_record(); //获取程序执行的时间
		}
	///////////////////////////////////////////////////////
}

//读取配置文件
if (!GeneralFunc::LogHasErr()){


	$preprocess_config = array();   // 预处理设置
	$protect_section   = array();   // 保护段设置
	$dynamic_insert    = array();   // 动态赋值设置
	$user_cnf = array();            // 用户配置(除预处理部分)


	if (false === CfgParser::get_usr_config(false,$usr_cfg_file,$user_cnf,$preprocess_config,true)){ //读取配置文件失败，不做任何处理？放弃
	    GeneralFunc::LogInsert($language['without_cfg_file']);        
	}
    
	$preprocess_sec_name = CfgParser::GetPreprocess_sec();
	if (!count($preprocess_sec_name)){
	    GeneralFunc::LogInsert($language['without_act_sec']);        
	}else{
		//过滤 非指定 节表
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
				GeneralFunc::LogInsert($language['ignore_ready_sec'].$a,3);
			}
		}
        
		if ($my_params['echo']){
			DebugShowFunc::my_shower_07($myTables['CodeSectionArray'],$ignore_sec);
		}
	}




	if (isset($preprocess_config['protect_section'])){  // 保护段设置
		$protect_section = $preprocess_config['protect_section'];
		//检测是否重叠保护段
		if (PreprocessFunc::is_overlap_section($protect_section)){
			GeneralFunc::LogInsert($language['overlay_protect_section']);      
		}	
	}
	if (isset($preprocess_config['dynamic_insert'])){  // 动态写入设置
		$dynamic_insert = $preprocess_config['dynamic_insert'];
		//检测是否重叠保护段
		if (PreprocessFunc::is_overlap_section($dynamic_insert)){
			GeneralFunc::LogInsert($language['overlay_dynamic_insert']);      
		}	
	}	
}


if (!GeneralFunc::LogHasErr()){
	////关联保护段和混淆目标段
	$protect_section_array = false; //['sec_number']['rva'] => size ; (rva = 相对段开头的偏移地址)
	if (!empty($protect_section)){
		$protect_section_array = PreprocessFunc::bind_protect_section_2_sec($protect_section,$myTables['CodeSectionArray'],$language);
	}
	//var_dump ($protect_section_array);
}

if (!GeneralFunc::LogHasErr()){
	////关联保护段和混淆目标段
	$dynamic_insert_array = false; //['sec_number']['rva'] => size ; (rva = 相对段开头的偏移地址)
	if (!empty($dynamic_insert)){
		$dynamic_insert_array = PreprocessFunc::bind_dynamic_insert_2_sec($dynamic_insert,$myTables['CodeSectionArray'],$language);
	}
	//var_dump ($dynamic_insert_array);
}

//////////////////////////////////////////////////////////

    if (!GeneralFunc::LogHasErr()){
		//////////////////////////////////////////////////
		//单独测试 目标 某个节表 ---> 
		//$b = 201; //目标节表 编号
		//$a = $myTables['CodeSectionArray'][$b];
		//unset ($myTables['CodeSectionArray']);
		//$myTables['CodeSectionArray'][$b] = $a;
		//////////////////////////////////////////////////
        
		//把需要处理的代码段提取出来，放到一个文件内，并对其进行反汇编
		$bin_filesize = 0;

		if (!count($myTables['CodeSectionArray'])){ //无目标
		    GeneralFunc::LogInsert($language['no_target_sec']);
		}else{		
			$p_sec_abs = array(); //保护区域(绝对 [开始] => 结束)(反汇编代码行号)
			$asm_size = ReadyFunc::collect_and_disasm($bin_file,$asm_file,$disasm,$myTables['CodeSectionArray'],$buf,$bin_filesize,$protect_section_array,$p_sec_abs,$language,false);            

			if (!GeneralFunc::LogHasErr()){
				$exetime_record['collect_and_disasm'] = GeneralFunc::exetime_record(); //获取程序执行的时间
			   
				$LineNum_Code2Reloc = array();  //代码对应重定位
												//$LineNum_Code2Reloc[节表编号][代码行数][重定位编号 1] = true;
												//                                       [.........  2] = true;
												//
				$AsmLastSec = array();          //节表末尾标行号[节表编号][代码行数] = true;
												//
				if ($asm_size){
					if (ReadyFunc::format_disasm_file($asm_file,$bin_filesize,$AsmResultArray,$language)){
						$exetime_record['format_disasm_file'] = GeneralFunc::exetime_record(); //获取程序执行的时间
                        if (!empty($protect_section)){ //处理 保护段 (把汇编指令修正为: db xx ，并合并为一个单位)  
						    PreprocessFunc::format_protect_section ($p_sec_abs,$AsmResultArray,$language);
							$exetime_record['format_protect_section'] = GeneralFunc::exetime_record(); //获取程序执行的时间						
						}
						ReadyFunc::sec_reloc_format($myTables,$AsmResultArray,$AsmLastSec,$language,$LineNum_Code2Reloc);			
						$exetime_record['sec_reloc_format'] = GeneralFunc::exetime_record(); //获取程序执行的时间
					}
				}else{
					GeneralFunc::LogInsert($language['disasm_file_not_found']);
				}
			}
		}
	}

    if (!GeneralFunc::LogHasErr()){
		//
		//为所有eip跳转指令(重定位的都设跳转目标为下一指令) 定位 以后添加 Label 的位置信息
		//替换  eip跳转指令 后的常数为 Label
		$solid_jmp_array = array();    //保存固定跳转 Dest / Source 的数组 $solid_jmp_array[sec][dest][n]           = Label Name
		$solid_jmp_to    = array();    //保存固定跳转 来源 -> 目的         $solid_jmp_to   [sec][source] = dest

		ReadyFunc::eip_label_replacer($AsmLastSec,$solid_jmp_array,$solid_jmp_to,$myTables,$AsmResultArray,$LineNum_Code2Reloc,$language);

		//对重定位 目标 进行标号/define 变量名替换
		ReadyFunc::rel_label_replacer($myTables,$AsmResultArray,$LineNum_Code2Reloc,$language);

		$exetime_record['eip rel label replace'] = GeneralFunc::exetime_record(); //获取程序执行的时间
	   
		$garble_rel_info = array();  //保存混淆后的重定位信息
									 //结构 struct [段编号][原始编号][副本号] => [SymbolTableIndex] 符号表 索引号
									 //                                          [Type]             类型 rel32 dir32
									 //                                          [value]            原始值
									 //                                          [isLabel]          值 or 地址Label
									 //                                          [isMem]            普通值 or 内存地址
									 //注：原始的副本号为 0
									 //
        // 过滤 重定位 type = 20 必须为跳转标号 / type = 6 必须是值(非标号) | 否则丢弃此段，不做处理
		$z = $myTables['CodeSectionArray'];
		foreach ($z as $a => $b){
			if (is_array($myTables['RelocArray'][$a])){
				foreach ($myTables['RelocArray'][$a] as $c => $d){
					if (((20 === $d['Type']) && (true === $d['isLabel'])) || ((6 === $d['Type']) && (!$d['isLabel']))){
					    $garble_rel_info[$a][$c][0] = $d;
					}else{
						unset ($myTables['CodeSectionArray'][$a]);						
                        GeneralFunc::LogInsert($language['section_name']." ".$b['name'].$language['section_number']." $a ".$language['illegal_rel_type'],2);
                        break;  
					}
				}
			}
		}

		//
		$StandardAsmResultArray = array();	//保存 标准化后 的代码  [line_number] => array(
											//                                             'prefix'[] => 前缀
											//                                             'operation'=> 指令
											//                                             'params'[] => array ( 参数
											//                                                               '0' => 'eax';
											//                                                               '1' => '1'
											//                                                               '2' => '[eax+0x0]'
											//                                                           )
											//                                             'p_type'[] => array ( 参数类型
											//                                                               '0' => 'r','1' => 'i','2' => 'm'
											//                                                           )
											//                                             'p_bits'[] => array ( 参数位数
											//                                                               '0' => 32, '1' => 0 //整数无位数, '2' => 32
											//                                                           )


		
		$normal_register_opt_array = array(); //普通寄存器操作记录 数组
											  //[sec][line][reg][bits] = 1 readonly 2 writeonly 3 read & write
		$flag_register_opt_array   = array(); //标志寄存器操作记录 数组
											  //[sec][line][reg] = 1 readonly 2 writeonly 3 read & write
		$valid_mem_opt_array       = array(); //有效内存  操作记录 数组
											  //[sec][line][][code]  = '[eax+012]'
											  //             [reg][] = 'EAX' 
											  //             [bits]  = 32
											  //             [opt]   = 1 readonly 2 writeonly 3 read & write
											  //             [reloc] = '7_3_0'
											  //
											  //                                                 _
        $stack_used                = array(); // //堆栈  使用       (非参数，被操作)              | [sec][line] => true;
		$stack_broke               = array(); // //堆栈  ESP 被改变 (ESP作为参数 且 Opt > 1)     _|

		ReadyFunc::standard_asm($myTables,$garble_rel_info,$AsmResultArray,$StandardAsmResultArray,$stack_used,$stack_broke,$language);
		//var_dump ($stack_used);
		//var_dump ($stack_broke);
		//var_dump ($StandardAsmResultArray[44]);
		//var_dump ($StandardAsmResultArray[2911]);
		$exetime_record['disasm to standard'] = GeneralFunc::exetime_record(); //获取程序执行的时间
		//

		//
		$exec_thread_list = array();        //逐个分析 节表中代码 所有可能 流程 [section][thread No][n]   => line_number
											//                                                      [n+1] => ...
											//
											//
		ReadyFunc::exec_thread_list_get($myTables['CodeSectionArray'],$StandardAsmResultArray,$exec_thread_list,$solid_jmp_to,$AsmLastSec);

		$exetime_record['exec thread list'] = GeneralFunc::exetime_record(); //获取程序执行的时间

		//显式/隐式 可用记录 见 readme.txt 2013/04/15
		$soul_forbid = array(); 
		$soul_usable = array();             //$soul_usable[section][line][prev][reg_write_able ] [EAX] => bits
											//                                 [flag_write_able] [CF]  => 1
											//                                 [mem_read_able  ] ?
											//                                 [mem_write_able ] ?
											//                                  
											//                           [next]
											//

		//获得 灵魂(代码)前后 可用(读写) 通用/标志 寄存器 及 内存地址 , 堆栈可用 一览 
		ReadyFunc::get_soul_usable_limit($myTables['CodeSectionArray'],$exec_thread_list,$StandardAsmResultArray,$stack_used,$stack_broke);
		
		$exetime_record['usable register and memory'] = GeneralFunc::exetime_record(); //获取程序执行的时间

		//压缩相同 可用内存 描述，以减少 生成配置文件的体积 readme 2013/04/02
		//$all_valid_mem_opt_record  = array(); //有效内存  集 ['code']['bits'][opt] = index number | [函数 中 局部变量]
		$all_valid_mem_opt_index   = array();   //有效内存索引 [index number] = 'code' => '[...]'
												//                              'bits' => 8/16/32
												//                              'opt'  => 1/2/3
		$soul_usable = ReadyFunc::compress_same_char_output($soul_usable,$all_valid_mem_opt_index);        
		
		$exetime_record['compress same char to output'] = GeneralFunc::exetime_record(); //获取程序执行的时间

		//////////////////////////////////////////////////////////////////////////////////////////////////////
		//抽取 节表名 .xxx$bbb >> convert to >> 'bbb'
		//$sec_name[xxx][] = sec_number
		$sec_name = array();
		foreach ($myTables['CodeSectionArray'] as $a => $b){
			$sec_name[$b['name']][] = $a;
		}

        //////////////////////////////////////////////////////////////////////////////////////////////////////
        //对 各节表 指令/Label 生成 代码顺序写入 双向链表
		$soul_writein_Dlinked_List_Total = array();
		foreach ($myTables['CodeSectionArray'] as $sec => $b){            
		    $soul_writein_Dlinked_List = array();
			$s_w_Dlinked_List_index = 0;
			$prev = false;	
			$c_solid_jmp_array = $solid_jmp_array[$sec];
			OrgansOperator::init($sec);
			$lp_asm_result = count($StandardAsmResultArray[$sec]) + 1;

			$label_index = -1; //label 编号从 -1 起
        	foreach ($StandardAsmResultArray[$sec] as $z => $y){			
			    $gret = ReadyFunc::generat_soul_writein_Dlinked_List($soul_writein_Dlinked_List,$z,$y,$s_w_Dlinked_List_index,$prev,$c_solid_jmp_array);
			    unset ($c_solid_jmp_array[$z]);
			}
			if (!empty($c_solid_jmp_array)){//剩余的label 都放在末尾 (继承 上一指令的 next_usable)
				foreach ($c_solid_jmp_array as $x => $y){
					if ($x <= $z){ //出错了，放到末尾的标号不能比最后一个有效指令行数小，这里直接返回致命错误，不再做放弃当前
								   //-节表处理，因为前面 已做过滤处理，这里出错说明代码逻辑有问题，需要修正
						GeneralFunc::LogInsert($language['section_name']." ".$b['name'].$language['section_number']." $sec ".$language['jmp_dest_out_rang_error']);
						break;
					}
					foreach ($y as $w){
						ReadyFunc::add_soul_writein_Dlinked_List($soul_writein_Dlinked_List,$s_w_Dlinked_List_index,$prev,$w,$z,true);				
					}
				}		
			}
			$soul_writein_Dlinked_List_Total[$sec]['index'] = $s_w_Dlinked_List_index;   //末尾 (新单位 可用编号)
			$soul_writein_Dlinked_List_Total[$sec]['list']  = $soul_writein_Dlinked_List;
			
		//echo "<br>$sec :";
		//var_dump ($StandardAsmResultArray[$sec]);
		//var_dump ($solid_jmp_array[$sec]);
		//var_dump ($solid_jmp_to[$sec]);
		//var_dump ($soul_writein_Dlinked_List_Total[$sec]['index']);
		//var_dump ($soul_writein_Dlinked_List_Total[$sec]['list']);
		}
	}    

    //根据 dynamic insert 记录 替换 $StandardAsmResultArray 中对应 整数参数
	$dynamic_insert_result = array();
	if (!GeneralFunc::LogHasErr()){
		$dynamic_insert_result = PreprocessFunc::dynamic_insert_dealer($dynamic_insert_array,$StandardAsmResultArray);
	}

    if (!GeneralFunc::LogHasErr()){
		//对可用内存地址(含重定位部分) 进行解析,见 readme.arrays.txt 
		ReadyFunc::parser_rel_usable_mem ($all_valid_mem_opt_index);

	    //扫描所有关联的可用通用寄存器 与 内存 指针 ,如:usable.reg: edi & usable.mem: [edi] 
	    //$AffiliateUsableArray = scan_affiliate_usable ($soul_usable,$soul_forbid);
		ReadyFunc::scan_affiliate_usable ($soul_usable,$soul_forbid);

		//所有堆栈有效单位，禁止对堆栈指针的可写定义
		foreach ($sec_name as $a => $b){	    
		    foreach ($b as $c => $sec_id){
				$c_list = $soul_writein_Dlinked_List_Total[$sec_id]['list'][0]; //起始位默认是0
				while (true){					  
                    $f = $c_list['c'];				    
					if (true === $soul_usable[$sec_id][$f]['p']['stack']){ //堆栈有效
					    unset($soul_usable[$sec_id][$f]['p']['normal_write_able'][$stack_pointer_reg]);
                        $soul_forbid[$sec_id][$f]['p']['normal'][$stack_pointer_reg][32] = true;
					}
					if (true === $soul_usable[$sec_id][$f]['n']['stack']){ //堆栈有效
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
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		//取得所有单位 mem 参数 对opcode 长度的影响		
		require dirname(__FILE__)."/library/oplen.func.php";
		
		foreach ($soul_writein_Dlinked_List_Total as $number => $z){			
			foreach ($soul_writein_Dlinked_List_Total[$number]['list'] as $a => $b){
				if (isset($b['label'])){
				
				}else{
					if (isset($StandardAsmResultArray[$number][$b['c']]['p_type'])){
						foreach ($StandardAsmResultArray[$number][$b['c']]['p_type'] as $c => $d){
							if ('m' === $d){
								$c_len = OpLen::code_len($StandardAsmResultArray[$number][$b['c']],true);
								if ($c_len <= $b['len']){
									$all_valid_mem_opcode_len[$StandardAsmResultArray[$number][$b['c']]['params'][$c]] = $b['len'] - $c_len;
								}	
							}
						}
					}
				}
				// echo "<br>##########################  $number ######################";  
			}
			echo "<br>##########################  $number ######################";  
			var_dump ($all_valid_mem_opcode_len);
				//exit;
		}
				 
		$exetime_record['init mem_addition'] = GeneralFunc::exetime_record(); //获取程序执行的时间   
		
        //////////////////////////////////////////////////////////////////////////////////////////////////////
		//为链表补充'rel_jmp'数组
		foreach ($soul_writein_Dlinked_List_Total as $sec => $a){
			$soul_writein_Dlinked_List = $soul_writein_Dlinked_List_Total[$sec]['list'];
			OrgansOperator::init($sec);
			foreach ($soul_writein_Dlinked_List as $a => $b){
				$tmp = RelJmp::get_addition_List_info($a,false,true);
				if (isset($tmp['rel_jmp'])){
					$soul_writein_Dlinked_List_Total[$sec]['list'][$a]['rel_jmp'] = $tmp['rel_jmp'];
				}
			}
		}
        //////////////////////////////////////////////////////////////////////////////////////////////////////
		//完成构造 定长跳转 的数据结构
		$rel_jmp_range    = array();
        $rel_jmp_pointer  = array();
		$rel_jmp_switcher = array(); //段中有 rel.jmp['max']单位，generat处理时需要rel.jmp
		$c_usable_oplen   = false;   //ready阶段不计代码可用长度
		foreach ($soul_writein_Dlinked_List_Total as $sec => $a){
			$soul_writein_Dlinked_List = $soul_writein_Dlinked_List_Total[$sec]['list'];
			ConstructionDlinkedListOpt::ReadyInit();
			if (!RelJmp::reset_rel_jmp_array()){ //rel.jmp 返回错误				
			    GeneralFunc::LogInsert($language['init_rel_jmp_fail']);  
			}else{
				$rel_jmp_pointer[$sec] = ConstructionDlinkedListOpt::ReadRelJmpPointer();
				$rel_jmp_range[$sec]   = ConstructionDlinkedListOpt::readRelJmpRange();
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
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		//测试 opcode len 计算
		if (false === true){
		    require dirname(__FILE__)."/library/oplen.func.php";
            
			foreach ($soul_writein_Dlinked_List_Total as $number => $b){
                echo "<br>##########################  $number ######################";  			
				foreach ($soul_writein_Dlinked_List_Total[$number]['list'] as $a => $b){
					if (isset($b['label'])){
					
					
					}else{
						$c_len = OpLen::code_len($StandardAsmResultArray[$number][$b['c']]);
						//if ($b['len'] !== $c_len){
							echo "<br>";
							var_dump($StandardAsmResultArray[$number][$b['c']]);
							echo "<br>len = ".$b['len'];
							echo " = $c_len";
						//}
					}
				}
			}
			exit;
		}
		
        //对隔断代码(如 call ,ret 等)的后方保护，再处理 根据:(如果后面还有单位，则复制后单位的前保护；如果后面没有单位，则去掉所有soul_usable ,soul_forbid)
		ReadyFunc::redeal_split_opt($StandardAsmResultArray,$exec_thread_list,$soul_forbid,$soul_usable);
		
        //为灵魂赋予stack是否可用标识
		//灵魂前后都stack可用，则灵魂可用，否则则不可用
        foreach ($StandardAsmResultArray as $a => $b){
			GeneralFunc::soul_stack_set($StandardAsmResultArray[$a],$soul_usable[$a]);
		}

		if (!GeneralFunc::LogHasErr()){
			//////////////////////////////////////////////////////////////////////////////////////////////////////
			//初始化完成，将数据保存入文档，供给下一步骤使用 
			$rdy_output['StandardAsmResultArray']          = $StandardAsmResultArray;
			$rdy_output['garble_rel_info']                 = $garble_rel_info;
			//$rdy_output['solid_jmp_array']                 = $solid_jmp_array;
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
		  //$rdy_output['soul_effect_reg']        =	$normal_register_opt_array;
	      //$rdy_output['AffiliateUsableArray']            = $AffiliateUsableArray;
			$rdy_output['output_type']                     = $output_type; //binary or coff 
			$rdy_output['engin_version']                   = $engin_version;
			$rdy_output['preprocess_config']               = $preprocess_config;
			$rdy_output['dynamic_insert']                  = $dynamic_insert_result;
			
			$rdy_output['rel_jmp_range']    = $rel_jmp_range;
			$rdy_output['rel_jmp_pointer']  = $rel_jmp_pointer;
			$rdy_output['rel_jmp_switcher'] = $rel_jmp_switcher;

			file_put_contents($rdy_file,serialize($rdy_output)); 
		}

		if ($my_params['echo']){
			DebugShowFunc::my_shower_01($myTables['CodeSectionArray'],$StandardAsmResultArray,$exec_thread_list);
		}
	}

    echo "<br><br><br><br>";
	echo "binary size: ";
	var_dump ($asm_size);
	echo "<br><br><br><br>";

    $complete_finished = true;

exit;

?>