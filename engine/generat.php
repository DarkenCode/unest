<?php

define('UNEST.ORG', TRUE);
//ini_set('display_errors',0);
error_reporting(E_ERROR); 
//////////////////////////////////////////
//堆栈指针 寄存器
$stack_pointer_reg = 'ESP';

//////////////////////////////////////////
//organs 
define ('MEAT',1);
define ('BONE',2);
define ('POLY',3);
define ('SOUL',4);

require dirname(__FILE__)."/library/generate.func.php";
require dirname(__FILE__)."/library/general.func.php";

require dirname(__FILE__)."/library/data.construction.php";

require dirname(__FILE__)."/library/organ.func.php";

require_once dirname(__FILE__)."/include/intel_instruction.php";
require_once dirname(__FILE__)."/include/config.inc.php";

require dirname(__FILE__)."/library/instruction.func.php";
Instruction::init();

require dirname(__FILE__)."/library/character.func.php";
Character::init();

require dirname(__FILE__)."/organs/poly.php";
OrganPoly::init();

require dirname(__FILE__)."/organs/bone.php";
OrganBone::init();

require dirname(__FILE__)."/organs/meat.php";
OrganMeat::init();

require dirname(__FILE__)."/organs/fat.php";

require dirname(__FILE__)."/library/config.func.php";

require_once dirname(__FILE__)."/../nasm.inc.php";

require dirname(__FILE__)."/library/oplen.func.php";

require dirname(__FILE__)."/library/rel.jmp.func.php";

require dirname(__FILE__)."/library/debug.func.php";

//////////////////////////////////////////
//同时支持$_GET/$_POST/命令行输入 的参数
$my_params = GeneralFunc::get_params($argv);

//////////////////////////////////////////
//捕获超时
$complete_finished = false; //执行完成标志
register_shutdown_function('shutdown_except');
//////////////////////////////////////////
//检查参数并构造路径等

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

if ($my_params['echo']){
    require_once dirname(__FILE__)."/library/debug_show.func.php";
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

if (!isset($my_params['filename'])){
    GeneralFunc::LogInsert('need param filename');
}else{
    $filename = $my_params['filename'];
	$obj_filename = $base_addr."/"."$filename";
}

if (!isset($my_params['cnf'])){
    GeneralFunc::LogInsert('need param cnf');
}else{
	$usr_cfg_file = $base_addr.'/'.$my_params['cnf']; //用户设置
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
		
		$cf = unserialize($cf);//反序列化，并赋值  

		$StandardAsmResultArray = $cf['StandardAsmResultArray'];
		$garble_rel_info        = $cf['garble_rel_info'];
		//$solid_jmp_array        = $cf['solid_jmp_array'];
		$UniqueHead             = $cf['UniqueHead'];
		$CodeSectionArray       = $cf['CodeSectionArray'];
		$soul_usable            = $cf['soul_usable'];
		$soul_forbid            = $cf['soul_forbid'];
		$all_valid_mem_opt_index= $cf['valid_mem_index'];
		$sec_name               = $cf['sec_name'];
		$soul_writein_Dlinked_List_Total = $cf['soul_writein_Dlinked_List_Total'];
		$avmoi_ptr              = $cf['valid_mem_index_ptr']; //$all_valid_mem_opt_index 的当前编号
		$avmoi_ptr ++;
		$all_valid_mem_opcode_len = $cf['valid_mem_len'];
		//$AffiliateUsableArray   = $cf['AffiliateUsableArray'];
		//$soul_effect_reg        = $cf['soul_effect_reg']; //指令 影响 通用寄存器
		$output_type            = $cf['output_type'];
		$ready_preprocess_config= $cf['preprocess_config'];   //保护(不进行混淆)设置
		$dynamic_insert         = $cf['dynamic_insert'];      //动态 插入

		$preprocess_sec_name    = $cf['preprocess_sec_name']; //预处理阶段收集的操作目标段 名

        //加载输出文件格式 解析器
		$file_format_parser = dirname(__FILE__)."/IOFormatParser/".$output_type.".IO.php";
		if (file_exists($file_format_parser)){
			require $file_format_parser;
		}else{
			GeneralFunc::LogInsert('type without file format parser');
		}
        //
		$rel_jmp_range    = $cf['rel_jmp_range'];
		$rel_jmp_pointer  = $cf['rel_jmp_pointer'];
		$rel_jmp_switcher = $cf['rel_jmp_switcher'];

		if ($engin_version !== $cf['engin_version']){
			GeneralFunc::LogInsert('unmatch generat version: '."$engin_version".' !== '.$cf['engin_version']);
		}

		unset($cf);

		if (('BIN' !== $output_type)&&('COFF' !== $output_type)){ //未知的文件类型
			GeneralFunc::LogInsert($language['unkown_output_type']."$output_type");      
		}

		if (!empty($dynamic_insert)){ //确定 POST or Get 传递进来的动态插入数据
		    GeneralFunc::get_dynamic_insert_value($dynamic_insert);
		}

		$poly_strength          = array();   //$cf['Poly_']; 多态强度 $poly_strength['sec'] = strength;
		//$poly_result_array      = array();   // 记录 多态 结果

		$pattern_reloc           = '/('."$UniqueHead".'RELINFO_[\d]{1,}_[\d]{1,}_[\d]{1,})/';  //匹配 reloc 信息
		$pattern_reloc_4_replace = '/('."$UniqueHead".'RELINFO_(\d+)_(\d+)_(\d+))/';  //匹配 reloc 信息

		$mem_usage_record = array(); //内存使用记录
		
		$exetime_record = array();
		GeneralFunc::exetime_record(); //获取程序开始执行的时间

		////////////////////////////////////////////////////////////////////////////////
		//访问数据库 或 配置文件，


		$preprocess_config = array(); // 预处理设置
		$user_cnf = array();          // 用户配置(除预处理部分)
		if (false === CfgParser::get_usr_config($sec_name,$usr_cfg_file,$user_cnf,$preprocess_config,false)){ //读取配置文件失败，不做任何处理？放弃
			GeneralFunc::LogInsert($language['without_cfg_file']);        
		}
        
		if ($my_params['echo']){
			DebugShowFunc::my_shower_06($usr_cfg_file,$sec_name,$user_cnf);
		}

		//setvalue_dynamic 动态调整 原设置值
		if (false !== $setvalue_dynamic){
			CfgParser::affect_setvalue_dynamic($sec_name,$setvalue_dynamic,$user_cnf);
		}		
			
		
		//echo "<br>[user configur]";
		//foreach ($user_cnf as $a => $b){
		//	echo "<br> $a :";
		//	var_dump ($b);
		//}
		//exit;	
		////////////////////////////////////////////////////////////////////////////////
		//比较预处理设置项是否更改(相较ready阶段)
		if ($ready_preprocess_config !== $preprocess_config){
			GeneralFunc::LogInsert($language['nomatch_preprocess_config']);		
		}
		unset($ready_preprocess_config);
		unset($preprocess_config);

        //比较 段名是否有新增(相较ready阶段)
		CfgParser::CmpPreprocess_sec($preprocess_sec_name);


		////////////////////////////////////////////////////////////////////////////////
		//根据 用户 对 节表 定义，对 soul_usable 进行调整 (除soul_forbid 显式禁止的外)
		//var_dump ($sec_name);	
		//var_dump ($user_config);
		//var_dump ($user_strength);
		//var_dump ($user_config['unest_there']['normal']);
		//exit;
		CfgParser::reconfigure_soul_usable ($sec_name,$user_cnf,$soul_writein_Dlinked_List_Total,$soul_usable,$soul_forbid); //usable

		if ($my_params['echo']){
			DebugShowFunc::my_shower_01($CodeSectionArray,$StandardAsmResultArray,$exec_thread_list);
		}		

		//初始化 汇编输出文件 以及 动态写入 内容
        $init_asm_file = "[bits 32]\r\n";
        foreach ($dynamic_insert as $a => $b){			
			if (isset($b['new'])){
			    $insert_value = $b['new'];
			}else{           //无最新赋值则使用 原始值
				$insert_value = $b['default'];
			}
			$init_asm_file .= '%define '.$UniqueHead.'dynamic_insert_'.$a.' '.$insert_value."\r\n";
		}

		file_put_contents($out_file,$init_asm_file);

		$reloc_info_2_rewrite_table = array(); //重定位信息 ,用来 重写 Obj文件中的重定位表 / 
											   //次序与 汇编后 段头 dword 部分 相同

		$non_null_labels = array();             //应用跳转 标号 的 非 零 单位
												//需要 编译完成后 再修改其值
	}
}



//直接 按节表 逐个 处理，避免内存占用过多
foreach ($CodeSectionArray as $sec => $body){

	//各节表 代码 原始长度
	//echo "<br>code size: $sec: ".$body['SizeOfRawData'];
	
	if (GeneralFunc::LogHasErr()){
		break;
	}

	echo "<br>++++++++++++++++++++++++ $sec ++++++++++++++++++++++++++ <br>";        

	GenerateFunc::initStackPointer($sec,$my_params['echo']);		
	
	GenerateFunc::reset_ipsp_list_by_stack_pointer_define($soul_writein_Dlinked_List_Total[$sec]['list'],$StandardAsmResultArray[$sec],$my_params['echo']);
			 

	////////////////////////////////////////////////////////////////////////////////////////////////
	//顺序写入 双向链表 信息
	ConstructionDlinkedListOpt::init($soul_writein_Dlinked_List_Total[$sec],$rel_jmp_range[$sec],$rel_jmp_pointer[$sec]);		
	
	//init Organs Arrays
	OrgansOperator::init($sec);        
	//
	////////////////////////////////////////////////////////////////////////////////////////////////
	//根据 双向链表 信息 初始化 character.Rate
	Character::initDList($soul_writein_Dlinked_List_Total[$sec]['list']);
	
	$c_rel_jmp_switcher = $rel_jmp_switcher[$sec];

	if (isset($user_cnf[$sec]['output_opcode_max'])){ //设置了最大输出，则必须计算rel.jmp
		$c_rel_jmp_switcher = true;
		$tmp = ConstructionDlinkedListOpt::OplenInit($user_cnf[$sec]['output_opcode_max'] - $body['SizeOfRawData']); //用户设置的最大体积 - 当前段已有字节 = 可用
		if (1 == $tmp){       //设置最大输出size 不足原代码，显示错误
			GeneralFunc::LogInsert($language['section_name'].$body['name'].$language['section_number']."$sec ".$language['max_output_less_org']);
			break;
		}elseif (2 == $tmp){  //设置最大输出size 等于 原代码，显示警告
			GeneralFunc::LogInsert($language['section_name'].$body['name'].$language['section_number']."$sec ".$language['max_output_equal_org'],2);		
			continue;
		}
	}
	//重定位信息	
	if (isset($garble_rel_info[$sec])){
		$c_rel_info = $garble_rel_info[$sec];
	}else{
		$c_rel_info = false;
	}


	//测试soul_usable 项是否有问题,所有可写单位(寄存器,内存地址)和可读单位(内存地址)都写入操作代码，(注:flag目前不管)
	//                             这样...当soul_usable有问题，我们就能通过运行结构文件发现了(除不会出错的那种问题...)
	

   if (true === $user_cnf[$sec]['gen4debug01']){
		GeneralFunc::LogInsert("gen4debug01 option was effected on section: $sec , name: ".$body['name'],3); //debug 应用,提示之
		DebugFunc::debug_usable_array(ConstructionDlinkedListOpt::readListFirstUnit());
		if ($c_rel_jmp_switcher){ //识别是否超过定长跳转范围，如果超过...返回error
			$tmp = RelJmp::reset_rel_jmp_array(false,false,ConstructionDlinkedListOpt::readListFirstUnit());
			if (false === $tmp){  // 失败,定长跳转超过max or 其它 ？
				GeneralFunc::LogInsert($language['debug_rel_jmp_out_range']);
			}
		}
		//var_dump ($soul_writein_Dlinked_List );
		//exit;
		
	}else{
	
		if (isset($user_cnf[$sec]['meat_mutation'])){
			$c_MeatMutation = $user_cnf[$sec]['meat_mutation'];
		}else{
			$c_MeatMutation = 10; //血肉突变 默认值 mt_rand(1,$c_MeatMutation); if (==1){mutation...} 
								  //         false -> not mutation
		}

		if (isset($user_cnf[$sec]['soul_focus'])){
			$c_SoulFocus = $user_cnf[$sec]['soul_focus'];
		}else{
			$c_SoulFocus = 3;     //灵魂焦点 select_obj时必须以原始代码为标记
							  //   mt_rand(1,$c_SoulFocus); if (==1){true...} 
							  //   if (0==$c_SoulFocus) {false}
		}
		//var_dump ($sec_name);
		//echo "<br> $sec : <br> MeatMutation : $c_MeatMutation <br> SoulFocus : $c_SoulFocus";

		////////////////////////////////////////////////////////////////////////////////////////////////
		//生成 处理 poly,bone,meat执行顺序的数组
		
		$organ_process = OrgansOperator::GenOrganProcess(CfgParser::GetStrength($sec),count($soul_writein_Dlinked_List_Total[$sec]['list']),$max_strength);
		
		if ($my_params['echo']){
			var_dump ($organ_process);
		}

		if (0 == count($organ_process)){ //无任何处理
			GeneralFunc::LogInsert($language['section_name'].$body['name'].$language['section_number']."$sec ".$language['section_without_garble'],2);			
		}

//Character::show();

		////////////////////////////////////////////////////////////////////////////////////////////////////////
		foreach ($organ_process as $c_process){			

			//###### 识别可用长度过小的rel.jmp单位，清character.Rate
			if (true === $c_rel_jmp_switcher){
				ConstructionDlinkedListOpt::forbidMinRanges();
			}
            //###### 确认Organ操作目标，并character.Rate --
			$objs = false;
            if (POLY === $c_process){
				if (false !== ($a = Character::random(POLY))){
					$objs[1] = $a;
					Character::modifyRate(POLY,$a,-1);
				}						
			}elseif (MEAT === $c_process){
				$a = Character::random(MEAT);
				$b = Character::random(MEAT);
				$objs = ConstructionDlinkedListOpt::getAmongObjs($a,$b);
				foreach ($objs as $a){
					Character::modifyRate(MEAT,$a,-1);
				}
			}elseif (BONE === $c_process){
				$a = Character::random(BONE);
				$b = Character::random(BONE);	
				$objs = ConstructionDlinkedListOpt::getAmongObjs($a,$b);
				Character::modifyRate(BONE,$a,-1); //Bone 仅对撕裂位 character.Rate --
				Character::modifyRate(BONE,$b,-1);
			}else{
				GeneralFunc::LogInsert('unkown act in process: '.$c_process.' at section: '.$sec.'.',2);
			}			
			if (false === $objs){
			    continue;
			}
			//###### 保存当前双向链表及character.Rate，当rel.jmp不合适时可回滚还原
			ConstructionDlinkedListOpt::ready();
			Character::ready();	
			//###### Organ操作目标 开始处理
			if (POLY === $c_process){
				OrganPoly::start($objs,$my_params['echo']);
			}elseif (MEAT === $c_process){
				OrganMeat::start($objs);
			}elseif (BONE === $c_process){
				OrganBone::start($objs);
			}

			$exetime_record['organ'][$c_process] += GeneralFunc::exetime_record($stime); //获取执行的时间
			
			//######				
			if (true === $c_rel_jmp_switcher){	
				
				echo "<br>check .. is short jump out of range ?";
				
				$break_now = false;
				$ret_rel_jmp_deal = RelJmp::reset_rel_jmp_array($objs,ConstructionDlinkedListOpt::ReadRollingDlinkedList(),ConstructionDlinkedListOpt::readListFirstUnit());
				if ($ret_rel_jmp_deal){
					$rel_jmp_range_key = array();
					foreach (ConstructionDlinkedListOpt::readRelJmpRange() as $a => $b){
						$rel_jmp_range_key[$a] = $a;
					}
					$ret_rel_jmp_deal = RelJmp::resize_rel_jmp_array($rel_jmp_range_key); //根据新range重新计算rel.jmp指令的opcode len
				}

				if (!ConstructionDlinkedListOpt::OplenIncrease(0)){
					$ret_rel_jmp_deal = false;
					$break_now = true;
					echo '<br><font color=red>c_usable_oplen is done ... $c_usable_oplen...</font><br>';
				}

				if (false === $ret_rel_jmp_deal){					  
				  echo "<br><font color=red>roll back...</font><br>";					  	
				  ConstructionDlinkedListOpt::rollback();
				  Character::rollback();
				}              
				$exetime_record['adjust_rel_jmp'] += GeneralFunc::exetime_record($stime); //获取程序执行的时间				
			
				if (true === $break_now){ //oplen可用被突破,回滚后 放弃余下处理部分
					break;
				}					
			}			
			//var_dump ($objs);
			//var_dump ($c_process);
			//Character::show();
		}	
	}

	//var_dump (ConstructionDlinkedListOpt::readRelJmpRange());		

	//生成 用于编译的 源文件
	//对混淆后代码进行处理，让编译后进行定位处理
	if (false === GenerateFunc::gen_asm_file($out_file,$sec,$reloc_info_2_rewrite_table,$non_null_labels)){
		if (0 === $max_output){
			GeneralFunc::LogInsert($language['too_big_output']);
		}else{
			GeneralFunc::LogInsert($language['unkown_fatal_error_113']);
		}		
	}		

	$exetime_record['gen_asm_file'] += GeneralFunc::exetime_record($stime); //获取程序执行的时间
	
	$mem_usage_record[$sec] = number_format(memory_get_usage());

	//echo '<br><br> $c_usable_oplen with fat: ';
	//var_dump ($c_usable_oplen);
}	
   

if (!GeneralFunc::LogHasErr()){		
	//生成完成，开始编译
	$report_filename = "$out_file".'.report';
	$binary_filename = IOFormatParser::out_file_gen_name();
	
	exec ("$nasm -f bin \"$out_file\" -o \"$binary_filename\" -Z \"$report_filename\" -Xvc");
	
	$exetime_record['nasm final obj'] = GeneralFunc::exetime_record($stime); //获取程序执行的时间

	if (file_exists($binary_filename)){
		$newCodeSection = array(); //$newCodeSection[节表编号][addr] 
								   //                         [size]
								   //                         [NumberOfRelocation]
		IOFormatParser::out_file_format_gen();

		//最后比较 最终生成代码长度 是否超过 用户配置 @output_opcode_max
		foreach ($user_cnf as $uc_sec => $v){
			if (isset($user_cnf[$uc_sec]['output_opcode_max'])){
				//echo "<br><font color=red><b>test $uc_sec : ".$user_cnf[$uc_sec]['output_opcode_max'].' !< '.$newCodeSection[$uc_sec]['size'].'</font></b>';
				if ($user_cnf[$uc_sec]['output_opcode_max'] < $newCodeSection[$uc_sec]['size']){
					GeneralFunc::LogInsert($language['output_more_max'].' (sec:'.$uc_sec.')');
					//出错，删除result文件
					unlink($binary_filename);
					break;
				}
			}
		}
		
	}else{ //编译失败，参见Log文件 $report_filename
		GeneralFunc::LogInsert('compile fail, generate stopped.');
	}
	$exetime_record['others'] = GeneralFunc::exetime_record($stime); //获取程序执行的时间

}

//输出$output[] 到日志文件,jason格式
//if (isset($my_params['log'])){ 
//    file_put_contents($base_addr.'/'.$my_params['log'],json_encode($output));  
//}

$complete_finished = true; //执行完成标志
exit;





?>