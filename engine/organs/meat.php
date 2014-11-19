<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}

class OrganMeat{
    private static $_index = 1; //meat 唯一编号,全目标 唯一

	private static $_relate_array;
	private static $_usable_envir;
	private static $_usable_index;
	private static $_usable_array;
	private static $_same_inst_rate;

    public static function init(){
		//////////////////
		//初始化 取得 meat repo库
		$cf = @file_get_contents(dirname(__FILE__).'/../templates/meat.tpl.dat');
		if ($cf == false){
			GeneralFunc::LogInsert('fail to open meat repo',2);
		}else{
			$meat_array = unserialize($cf);//反序列化，并赋值  
			$repo_number = count($meat_array);
			//随机采用血肉模板
			$t = mt_rand(0,$repo_number-1);
			//echo "<br>meat model:<br>";
			//var_dump ($t);
			self::$_relate_array = $meat_array[$t]['relate'];
			self::$_usable_envir = $meat_array[$t]['usable_envir'];
			self::$_usable_index = $meat_array[$t]['usable_index'];
			self::$_usable_array = $meat_array[$t]['usable_array'];
			self::$_same_inst_rate = $meat_array[$t]['meat_same_inst_rate'];

			//放弃div idiv 作为血肉可选指令
			foreach (self::$_usable_index['DIV'] as $a){
				unset (self::$_usable_envir['ALL'][$a]);	    
			}
			foreach (self::$_usable_index['IDIV'] as $a){
				unset (self::$_usable_envir['ALL'][$a]);	    
			}
			//
		}
	}



	///////////////////////////////////////////////////////////////////////////////
	//
	// 把生成的血肉 插入 链表
	private static function meat_insert_into_list($current_forward,$meat_generated,$direct = P){



		$prev = false;
		$next = false;

		if (P === $direct){

			if (ConstructionDlinkedListOpt::issetDlinkedListUnit($current_forward,P)){		

				$prev = ConstructionDlinkedListOpt::getDlinkedList($current_forward,P);			
			}
			$next = $current_forward;
		}else{

			if (ConstructionDlinkedListOpt::issetDlinkedListUnit($current_forward,N)){		

				$next = ConstructionDlinkedListOpt::getDlinkedList($current_forward,N);			
			}
			$prev = $current_forward;	
		}

		$c_meat = self::$_index - $meat_generated;
		
		//echo "\r\n\r\n $current_forward [ $prev $next ], $meat_generated , $direct ,$c_meat,$UNIQUE_meat_index,$UNIQUE_meat_index";

		for (;$c_meat < self::$_index;$c_meat++){
			if (false !== $prev){

				ConstructionDlinkedListOpt::setDlinkedList(ConstructionDlinkedListOpt::getDlinkedListIndex(),$prev,N);
			}else{ //血肉 插入 首行
				ConstructionDlinkedListOpt::setListFirstUnit();
			}

			ConstructionDlinkedListOpt::setDlinkedList($c_meat,ConstructionDlinkedListOpt::getDlinkedListIndex(),MEAT);

			ConstructionDlinkedListOpt::setDlinkedList(98,ConstructionDlinkedListOpt::getDlinkedListIndex(),C);
			
			if (GenerateFunc::is_effect_ipsp(OrgansOperator::Get(MEAT,$c_meat,CODE,98),0)){

				ConstructionDlinkedListOpt::setDlinkedList(true,ConstructionDlinkedListOpt::getDlinkedListIndex(),'ipsp');			

			}

			if (false !== $prev){

				ConstructionDlinkedListOpt::setDlinkedList($prev,ConstructionDlinkedListOpt::getDlinkedListIndex(),P);
			}

			$prev = ConstructionDlinkedListOpt::getDlinkedListIndex();	   

			ConstructionDlinkedListOpt::incDlinkedListIndex();
		}
		if (false !== $next){


			ConstructionDlinkedListOpt::insertDlinkedList($prev,$next);
		}
	}

	

	///////////////////////////////////////////////////////////////////////////////
	//
	// 获得 当前插入位 可用 血肉 模板 
	//
	private static function get_meat_usable_repo($flag_usable,$reg_usable,$m_r32,$m_w32){	

	
	

		$ret = false;
		
		$filter[] = self::$_usable_envir['ALL'];           // 不可用 部分 记录
        
        $all_eflags = Instruction::getEflags();
		foreach ($all_eflags as $a){
			if (!isset($flag_usable[$a])){
				if (isset(self::$_usable_envir[$a])){
					$filter[] = self::$_usable_envir[$a];
				}
			}
		}

		foreach (Instruction::getRegsByBits(32) as $a){
			if (!isset($reg_usable[$a])){
				if (isset(self::$_usable_envir[$a])){
					$filter[] = self::$_usable_envir[$a];
				}
			}
		}

		if ((is_array($reg_usable))&&(0 < count($reg_usable))){
			
		}else{
			$filter[] = self::$_usable_envir['r32'];
		}

		if (true !== $m_r32){
			$filter[] = self::$_usable_envir['m_r32'];
		}
		
		if (true !== $m_w32){
			$filter[] = self::$_usable_envir['m_w32'];	
		}    
		
		if (count ($filter) > 1){
			$ret = call_user_func_array('array_diff',$filter);  
		}else{
			$ret = self::$_usable_envir['ALL'];
		}

		return $ret;

	}

		
	//////////////////////////////////////////////////////////////////////////////
	//
	//生成 LEA 的第二参数(无需有效的内存地址)(无需位数前缀定义)
	//
	//生成规则   通用寄存器(可选) + 通用寄存器(可选 除esp) × (1 or 2 or 4 or 8) + 整数(可选)
	//           
	//生成后加入 all_valid_mem_opcode_len 数组,以供后来计算指令长度
	//
	private static function gen_invalid_mem_address(){    


		$ret = false;

		$first  = 0;
		$second = 0;
		$third  = 0;

		if (mt_rand(0,3)){		
			$ret = array_rand(Instruction::getRegsByBits(32));
			$first = 1;
		}

		if (mt_rand(0,4)){
			$tmp = array_rand(Instruction::getRegsByBits(32));
			if ('ESP' != $tmp){
				if (false !== $ret){
					$ret .= '+';
				}        
				$ret .= $tmp;
				$tmp = array_rand(array('2'=>true,'4'=>true,'8'=>true));
				$ret .= '*'.$tmp;

				$second = 1;

				if ((0 === $first) and ($tmp > 2)){ //第二寄存器 乘数如大于2 且第一寄存器不存在，影响长度固定为 5 (最大)
					$second = 2;
				}
			}
		}
		if ((false === $ret)||(mt_rand(0,3))){
			if (false !== $ret){
				$ret .= '+';
			}
			$r_int = GenerateFunc::rand_interger();
			$ret .= $r_int['value'];

			$third = GenerateFunc::bits_precision_adjust($r_int[BITS]);
		}

		$ret = '['.$ret.']';

		//生成后 计算影响 指令长度 加入 all_valid_mem_opcode_len 数组
		global $all_valid_mem_opcode_len;		
		
		$len = Instruction::getMemEffectLen($first,$second,$third);
		
		$all_valid_mem_opcode_len[$ret] = $len;

		//echo "<br>fuck<b>$ret [$first][$second][$third] = $len </b><br>";

		return $ret;

	}	

	//////////////////////////////////////////////////////////////////////////////
	//
	// 生成 meat 的 params
	//
	// 返回 $ret['params'] , $ret['rel']
	//
	private static function meat_params_generate(&$ret,$p_type,$p_bits,$p_static,$reg_usable,$mem_usable,$mem_write_usable,$opt){


		global $c_rel_info;

		//var_dump ($p_type);
		//var_dump ($p_bits);
		//echo '<br>***************************************';
		//var_dump ($reg_usable);
		//var_dump ($mem_usable);
		//echo '<br>mem_write_usable:';
		//var_dump ($mem_write_usable);
		
		$c_inst = false;
		$c_inst = Instruction::getInstructionOpt($opt,count($p_type));
		
		if (false === $c_inst){                    //
			return false;
		}


		foreach ($p_type as $number => $type){

			if (isset($p_static[$number])){  //有固定寄存器变量存在(当前来说此变量 仅为通用寄存器且只读，所以无需事先判断是否可用，直接使用之)
				$ret[PARAMS][$number] = $p_static[$number];		
			}else{
				if ('i' === $type){        //随机整数
					$r_int = GenerateFunc::rand_interger($p_bits[$number]);
					$ret[PARAMS][$number] = $r_int['value'];
					//$ret[P_BITS][$number] = $r_int[BITS];
				}elseif ('r' === $type){   //寄存器，需要确认 操作(读 or 写)
					if (1 >= $c_inst[$number]){ //只读				
						$tmp = array_rand(Instruction::getRegsByBits($p_bits[$number]));
					}else{                                       // 可写
						if (is_array($reg_usable)){
							$tmp = array_rand($reg_usable);
						}else{ //前面应该已过滤，不应该执行到此处,assert 之
								echo '<br>调试错误信息, 出现需要可写通用寄存器 而 没有，所以meat构建失败...assert...<br>';
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
					$ret[PARAMS][$number] = Instruction::getRegByIdxBits($p_bits[$number],$tmp);
				}elseif ('m' === $type){   //内存，需要确认 操作(读 or 写)
					if (-1 == $c_inst[$number]){     //无需有效的内存地址（LEA）
						$ret[PARAMS][$number] = self::gen_invalid_mem_address();                
					}else{
						if (1 == $c_inst[$number]){ //只读
							$c_mem_usable_array = $mem_usable;
						}else{                                       // 可写
							$c_mem_usable_array = $mem_write_usable;
						}
						//var_dump ($opt);
						//var_dump ($c_inst[$number]);
						//var_dump ($mem_usable);
						//var_dump ($c_mem_usable_array);
						$tmp = array_rand($c_mem_usable_array);				
						$ret[PARAMS][$number] = $c_mem_usable_array[$tmp][CODE];
						if (isset($c_mem_usable_array[$tmp][REL])){        // 内存地址 含 重定位信息
							if (GenerateFunc::reloc_inc_copy($ret[PARAMS][$number],$old,$new)){
								$ret[PARAMS][$number] = str_replace("$UniqueHead".'RELINFO_'.$old[0].'_'.$old[1].'_'.$old[2],"$UniqueHead".'RELINFO_'.$old[0].'_'.$old[1].'_'.$new,$ret[PARAMS][$number]);
								$ret[REL][$number]['i'] = $old[1];
								$ret[REL][$number][C] = $new;
								$c_rel_info[$old[1]][$new] = $c_rel_info[$old[1]][$old[2]];
							}			    
						}
					}			
				}else{                     // ? 什么玩意
					return false;
				}
			}

			if ((!$ret[PARAMS][$number]) or (!isset($ret[PARAMS][$number]))){ //获取参数失败 (可能性: p_type='register',p_bits='8',$tmp='EDI' ===> edi没有8bit的形态)
				return false;
			}
		}
		return true;
		
	}


	//////////////////////////////////////////////////////////////////////////////
	//
	// 生成 meat
	//
	// $usable_meat_repo 可用血肉 数组 集
	// $c_meat_no        本单位血肉 数量
	// $reg_usable       可写通用寄存器
	// $mem_usable       可用内存地址
	// $c_usable_array   用来给生成的血肉 继承的前后 usable
	//
	private static function meat_generate($usable_meat_repo,$c_meat_no,$reg_usable,$mem_usable,$mem_write_usable,$c_usable_array){

		$ret = 0;  

		for ($i = 0;$i < $c_meat_no;$i ++){



			////////////////////////////////////////////////////////////////
			//根据 rate 区分
			//$c_rate = mt_rand(1,9);
			//$c_usable_meat_repo = $usable_meat_repo;
			
			//foreach ($usable_meat_repo as $a => $b){
			//	if ($meat_model_repo[$b]['rate'] < $c_rate){
			//		unset($c_usable_meat_repo[$a]);
			//	}
			//}
			//if (empty($c_usable_meat_repo)){
			//    continue;
			//}
			
			//$result = array();

			//$meat_index = array_rand ($c_usable_meat_repo); //随机 取得 使用 模板
			//$meat_index = $c_usable_meat_repo[$meat_index];

			$result[CODE][98] = $usable_meat_repo;
			$result[USABLE][98][P] = $c_usable_array;
			$result[USABLE][98][N] = $c_usable_array;
			
			$success = true; 
			
			if (isset($usable_meat_repo[P_TYPE])){ //有参数，则根据type/bits 生成参数
				$success = self::meat_params_generate($result[CODE][98],$usable_meat_repo[P_TYPE],$usable_meat_repo[P_BITS],$usable_meat_repo['static'],$reg_usable,$mem_usable,$mem_write_usable,$usable_meat_repo[OPERATION]);
			}

			if (true === $success){		
				//var_dump ($result[CODE]);
				//var_dump ($result[USABLE]);
				//对多态结果进行stack可用状态设置(根据usable)
				GeneralFunc::soul_stack_set($result[CODE],$result[USABLE]);

				self::append($result);

				$ret ++;
			}
		} 
		return $ret;

	}

	//插入meat_result
	public static function append($result){
	    OrgansOperator::Set(MEAT,self::$_index,$result);
		$ret = self::$_index;
		self::$_index ++;
		return $ret;
	}


	//////////////////////////////////////////////////////////////////////////////
	//
	//根据rate随机得到本次使用的血肉模板
	//
	//返回 选中目标
	private static function random_repo_by_rate($usable_model){
		
		//将rate调整为 阶梯 分布，以备 随机
		$c_rate = 0;
		foreach ($usable_model as $id => $value){
			$usable_model[$id]['rate'] += $c_rate;       
			$c_rate = $usable_model[$id]['rate'];       
		}

		//var_dump ($usable_model);
		//echo "<br><br>max: ".$c_rate."<br>";
		$r = mt_rand(1,$c_rate);      

		foreach ($usable_model as $id => $value){
			if ($r <= $usable_model[$id]['rate']){
				unset ($usable_model[$id]['rate']);
				return $usable_model[$id];			
			}
		}
		return false;
	}



	//////////////////////////////////////////////////////////////////////////////
	//
	// 得到对应usable，并开始生成血肉 单位
	//

	private static function meat_start($List_id,$direct = P){

		global $all_valid_mem_opt_index;
		
		if (P === $direct){
			$fat_direct = 1;
		}else{
			$fat_direct = 2;
		}
        
        if (OrgansOperator::CheckFatAble(ConstructionDlinkedListOpt::getDlinkedList($List_id),$fat_direct)){
		    return 0;
		}

        $c_usable_array = OrgansOperator::GetByDListUnit(ConstructionDlinkedListOpt::getDlinkedList($List_id),USABLE,$direct);

		//根据 亲缘性 获得可用 血肉指令集
		$prev_inst = false; //前指令
		$next_inst = false; //后指令
		if (P === $direct){

			if (ConstructionDlinkedListOpt::issetDlinkedListUnit($List_id,P)){

				$prev_inst = ConstructionDlinkedListOpt::get_inst_from_DlinkedList(ConstructionDlinkedListOpt::getDlinkedList($List_id,P),P);
			}else{
				$prev_inst = 'empty';
			}
			$next_inst = ConstructionDlinkedListOpt::get_inst_from_DlinkedList($List_id,N);
		}else{

			if (ConstructionDlinkedListOpt::issetDlinkedListUnit($List_id,N)){
				$next_inst = ConstructionDlinkedListOpt::get_inst_from_DlinkedList(ConstructionDlinkedListOpt::getDlinkedList($List_id,N),N);
			}else{
				$next_inst = 'empty';
			}
			$prev_inst = ConstructionDlinkedListOpt::get_inst_from_DlinkedList($List_id,P);
		}
		//echo "<br>$prev_inst $next_inst<br>";

		

		global $c_MeatMutation;
		
		$meat_mutation = GenerateFunc::my_rand($c_MeatMutation);    //根据 当前突变概率 随机获取
		
		//if ($meat_mutation){
		//    echo "<br> <font color=red>meat mutation</font>";
		//}else{
		//   echo "<br> <font color=blue>meat relation</font>";
		//}
		//$meat_mutation = true;          //测试时强制设置为血肉突变

		if (false === $meat_mutation){
			if (!isset(self::$_relate_array[$prev_inst][$next_inst])){ //亲缘性 无可用
				//echo "<br>Fuck unusable relate... <br>";
				return 0;
			}
		}

		// 分析 各 单位 可用 
		$reg_usable  = false;
		$mem_usable  = false;
		$mem_write_usable = false; //内存地址 可写
		$flag_usable = $c_usable_array[FLAG_WRITE_ABLE];
		$m_w32 = false;
		$m_r32 = false;

		// 简单起见，目前仅处理 32位 可用信息
		if (is_array($c_usable_array[NORMAL_WRITE_ABLE])){
			foreach ($c_usable_array[NORMAL_WRITE_ABLE] as $a => $b){
				if ($b[32]){
					if ('ESP' !== $a){  //这里简单化处理，应该判断stack是否为可用，则判断对应的ESP操作
						$reg_usable[$a] = true;
					}
				}	
			}
		}
		
		if (is_array($c_usable_array[MEM_OPT_ABLE])){
			foreach ($c_usable_array[MEM_OPT_ABLE] as $a => $b){
				//var_dump ($all_valid_mem_opt_index[$b]); 

				if ($all_valid_mem_opt_index[$b][BITS] == 32){
					$mem_usable[] = $all_valid_mem_opt_index[$b];				
					$m_r32 = true;
					if ($all_valid_mem_opt_index[$b][OPT] > 1){
						$mem_write_usable[] = $all_valid_mem_opt_index[$b];
						$m_w32 = true;
					}
				}
			}
		}

		//var_dump ($c_usable_array);
		//var_dump ($reg_usable);

		//可用模板(envir)
		$usable_meat_repo = self::get_meat_usable_repo($flag_usable,$reg_usable,$m_r32,$m_w32);

		//模板索引 rate filter   



		 
		$final_usable_model = false; //最终 可用模板

		//var_dump ($meat_relate_array[$prev_inst][$next_inst]);
		//var_dump ($usable_meat_repo);
		
		if (false === $meat_mutation){
			
			if (isset(self::$_relate_array[$prev_inst][$next_inst])){
				foreach (self::$_relate_array[$prev_inst][$next_inst] as $a => $relate_rate){
					//echo '<br>'.$a.' : '.$relate_rate;
					//var_dump ($meat_usable_index[$a]);
					foreach (self::$_usable_index[$a] as $repo_index){
						if (isset($usable_meat_repo[$repo_index])){
							$final_usable_model[$repo_index] = self::$_usable_array[$repo_index];   
							$final_usable_model[$repo_index]['rate'] = $relate_rate * self::$_same_inst_rate[$repo_index];
						}
					}
				}
			}
		}else{ //突变～～～忽略亲缘性
			 foreach (self::$_usable_index as $a => $b){
				foreach (self::$_usable_index[$a] as $repo_index){
					if (isset($usable_meat_repo[$repo_index])){
						$final_usable_model[$repo_index] = self::$_usable_array[$repo_index];   
						$final_usable_model[$repo_index]['rate'] = self::$_same_inst_rate[$repo_index];
					}
				}
			}
		}
        //echo '<br>$final_usable_model';
		//var_dump ($final_usable_model);

		if (false === $final_usable_model){ //无 可用模板
			return 0;
		}

		//根据rate随机得到本次使用的血肉模板
		$c_obj_model = self::random_repo_by_rate($final_usable_model);

		return self::meat_generate($c_obj_model,1,$reg_usable,$mem_usable,$mem_write_usable,$c_usable_array);

	}


	//////////////////////////////////////////////////////////////////////////////
	//
	// 以起始点开始，向上/下 2个方向 扩展 遍历
	// 
	// $current           起始点 同时向 上/下 2个方向扩展
	// $meat_length       影响条数
	// $meat_max_number   最大血肉可用条数
	//

	public static function start($objs){

        $meat_max_number = 50;

		$c_total_meat_generated = 0;

		$new_meat_gen = false; //统计在本次循环中是否有新血肉生成,决定进一步循环 or 结束

		$first_unit = false;    //  第一个单位
		$last_unit  = false;    //最后一个单位 
		

		foreach ($objs as $a){		
			if (false === $first_unit){
				$first_unit = $a;
			}		
			$last_unit = $a;
			if (self::meat_start ($a,P)){ //生成完成，加入List链表
				self::meat_insert_into_list($a,1,P);
				$meat_max_number --;
				$c_total_meat_generated ++;
				$new_meat_gen = true;
			}	
			if (self::meat_start ($a,N)){ //生成完成，加入List链表
				self::meat_insert_into_list($a,1,N);
				$meat_max_number --;	
				$c_total_meat_generated ++;
				$new_meat_gen = true;
			}
			if ($meat_max_number <= 0){
				break;
			}
		}
		
		//【如$max_meat_count仍然有剩余...】

		while (($new_meat_gen) and ($meat_max_number > 0)){		
			$current_unit = $first_unit;
			$new_meat_gen = false;

			//echo '<br>**************************';
			//var_dump ($meat_max_number);
			//var_dump ($current_unit);
			//var_dump ($last_unit);

			while (($current_unit != $last_unit) and ($meat_max_number > 0)){
				

				if (!ConstructionDlinkedListOpt::issetDlinkedListUnit($current_unit,N)){ //这里去下一个，绕过新建血肉 
					$current_unit = false;
				}else{

					$current_unit = ConstructionDlinkedListOpt::getDlinkedList($current_unit,N);
				}

				if (self::meat_start ($current_unit,P)){ //生成完成，加入List链表
					self::meat_insert_into_list($current_unit,1,P);
					$meat_max_number --;
					$c_total_meat_generated ++;
					$new_meat_gen = true;
				}

				if (self::meat_start ($current_unit,N)){ //生成完成，加入List链表
					self::meat_insert_into_list($current_unit,1,N);
					$meat_max_number --;
					$c_total_meat_generated ++;
					$new_meat_gen = true;
				}

				if (false === $current_unit){
					break;
				}
				
			}    
			//echo "<br> $meat_max_number  continue...<br>";
		}

		return $c_total_meat_generated;
		
	}

}



?>