<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}

class OrganBone{

	private static $_index = 1; //bone 唯一编号,全目标 唯一

    private static $_bone_model_repo;
	private static $_bone_model_index;
	private static $_bone_model_index_multi;

    private static $_bone_multi_max_size;

	private static $_delay_remove;    //待删除(原始label 多通道后)

	private static $_bone_units;          //骨架位 DListID
	private static $_multi_bone_unit_rate;//多通道 内单位character.Rate 原值
	private static $_multi_bone_map;      //多通道 DListID 对应关系

    public static function init(){
		require dirname(__FILE__)."/../templates/bone.tpl.php";
	    //self::$_index = 1;
		self::$_bone_model_index       = $bone_model_index;
		self::$_bone_model_index_multi = $bone_model_index_multi;
		self::$_bone_model_repo        = $bone_model_repo;
		self::$_bone_multi_max_size    = $bone_multi_max_size;
	}

	//清除指定 usable中 影响 ipsp的单位 (根据 双向链表 索引 开始 -> 结束 )
	private static function remove_ipsp_from_usable_list($start,$end){

		global $all_valid_mem_opt_index;

		$c_lp = $start;		
		while (true){

            OrgansOperator::FilterMemUsable($c_lp);	   

			if ($c_lp === $end){
				break;
			}
			if (!ConstructionDlinkedListOpt::issetDlinkedListUnit($c_lp,N)){
				break;
			}
			$c_lp = ConstructionDlinkedListOpt::getDlinkedList($c_lp,N);
		}
		
	}

	// 从灵魂 处 继承骨架的 usable
	private static function inherit_bone_usable(&$c_bone_result_array,$c_soul_position){

		//第一个 / 最后一个必定是 骨架单位
		foreach ($c_bone_result_array['process'] as $x){ //多通道? 直接覆盖
			$first = false;
			$last  = false;	
			$buff  = array();
			foreach ($x as $b){
				if (isset($b['s'])){
					if (isset($c_soul_position[$b['s']])){
						$first = ConstructionDlinkedListOpt::get_usable_from_DlinkedList($c_soul_position[$b['s']]['first'],P);
						$last  = ConstructionDlinkedListOpt::get_usable_from_DlinkedList($c_soul_position[$b['s']]['last'],N);					
						if (isset($buff)){
							//echo '<br>buff:';
							//var_dump ($buff);
							foreach ($buff as $z){
								if (isset($z[P])){
									$c_bone_result_array[USABLE][$z[P]][P] = $first;
								}elseif (isset($z[N])){
									$c_bone_result_array[USABLE][$z[N]][N] = $first;
								}
							}
							unset ($buff);
							$first = false;
						}
					}
				}else{
					$buff[] = $b; //待处理
				}		
			}
			if ((isset($buff)) && (false !== $last)){
				//			echo '<br>buff_ end:';
				//			var_dump ($buff);
				foreach ($buff as $z){
					if (isset($z[P])){
						$c_bone_result_array[USABLE][$z[P]][P] = $last;
					}elseif (isset($z[N])){
						$c_bone_result_array[USABLE][$z[N]][N] = $last;
					}
				}	   
			}
		}
		//对 IPSP 保护区内单位 所有usable 做 消除 ipsp 处理(含 骨架n 灵魂 骨架p)
		if (isset($c_bone_result_array['ipsp'])){
			//var_dump ($c_bone_result_array);
			foreach ($c_bone_result_array['ipsp'] as $a => $b){
				//echo "<br>$a: ";
				if (true === $b){   //灵魂集
					if (isset($c_soul_position[$a]['first'])){
						self::remove_ipsp_from_usable_list($c_soul_position[$a]['first'],$c_soul_position[$a]['last']);
					}
				}elseif (1 === $b){ //prev
					if (isset($c_bone_result_array[USABLE][$a][P][MEM_OPT_ABLE])){
						GenerateFunc::doFilterMemUsable($c_bone_result_array[USABLE][$a][P][MEM_OPT_ABLE]);
					}			    
				}elseif (2 === $b){ //next
					if (isset($c_bone_result_array[USABLE][$a][N][MEM_OPT_ABLE])){
						GenerateFunc::doFilterMemUsable($c_bone_result_array[USABLE][$a][N][MEM_OPT_ABLE]);
					}			    
				}
			}
		}

		//对fat区域，做stack可用标记
		foreach ($c_bone_result_array[FAT] as $y => $x){
			if (1 == $x){
				$c_bone_result_array[USABLE][$y][P][STACK] = true;
			}else{
				$c_bone_result_array[USABLE][$y][N][STACK] = true;
			}
		}
		//对多态结果进行stack可用状态设置(根据usable)
		GeneralFunc::soul_stack_set($c_bone_result_array[CODE],$c_bone_result_array[USABLE]);
	}

    // 对照 ConstructionDlinkedListOpt::remove_from_DlinkedList
	private static function remove_from_DlinkedList($c_lp,&$lp_first,&$lp_last,&$copy_buff){
		$prev = false;
		$next = false;		

		if (isset($copy_buff[$c_lp][P])){
			$prev = $copy_buff[$c_lp][P];
		}else{ //无 P, 摘除目标是 首单位
			$lp_first = false;		
		}
		
		if (isset($copy_buff[$c_lp][N])){
			$next = $copy_buff[$c_lp][N];
		}else{ //无 N, 摘除目标是 尾单位
			$lp_last = false;		
		}
		//unset ($copy_buff[$c_lp]);
		$copy_buff[$c_lp]['302'] = $next;
		if (false !== $prev)
			unset ($copy_buff[$prev][N]);
		if (false !== $next)
			unset ($copy_buff[$next][P]);

		if ((false !== $prev)&&(false !== $next)){
			$copy_buff[$prev][N] = $next;
			$copy_buff[$next][P] = $prev;
		}elseif (false !== $next){ //if Prev == false
			$lp_first = $next;
		}elseif (false !== $prev){ //if Next == false
			$lp_last = $prev;
		}
	}

	//对骨架多通道 进行副本的复制
	private static function soul_copy($source_first,$source_last,&$dest){

		
		$c_first  = $source_first;
		$c_lp     = $source_first;
		$copy_buff = array(); //副本 缓存区
		
		while (true){
			
			if (!isset(self::$_multi_bone_unit_rate[$c_lp])){
			    self::$_multi_bone_unit_rate[$c_lp] = Character::getAllRate($c_lp);
			}

			$copy_buff[$c_lp] = ConstructionDlinkedListOpt::getDlinkedList($c_lp);
			if ($c_lp === $source_last){
				break;
			}

			if (!ConstructionDlinkedListOpt::issetDlinkedListUnit($c_lp,N)){
				break;
			}

			$c_lp = ConstructionDlinkedListOpt::getDlinkedList($c_lp,N);
		}
		$c_last = $c_lp;

		//去除首个单位 Prev 指针 和 末尾单位 Next 指针
		unset ($copy_buff[$c_first][P]);
		unset ($copy_buff[$c_last][N]);
		//副本 和 正本 的标签 部分，2者只保留一处
		$c_lp = $c_first;

		$dest['first'] = $c_first;
		$dest['last']  = $c_last;
		while (true){		
			//echo "b $c_lp !== $c_last ,";
			if (isset($copy_buff[$c_lp][N])){
				$next = $copy_buff[$c_lp][N];
			}else{
				$next = false;
			}
			if (isset($copy_buff[$c_lp][LABEL])){         //标号在 副本产生时，随机保留一份(副本或正本中)
			    if (isset(self::$_delay_remove[$c_lp])){    //已被标识为待清除，说明已有副本替代，这里直接去掉
					self::remove_from_DlinkedList($c_lp,$dest['first'],$dest['last'],$copy_buff);	
				}else{
					if (mt_rand(0,1)){
						self::$_delay_remove[$c_lp] = $c_lp; // 正本中清除 留到 骨架插入完成后清理
					}else{
						self::remove_from_DlinkedList($c_lp,$dest['first'],$dest['last'],$copy_buff);	
					}
				}
			}
			if ($c_lp === $c_last){
				break;
			}
			if (false === $next){
				break;
			}
			$c_lp = $next;
		}
		//var_dump($dest);
		//var_dump($copy_buff);
		return $copy_buff;
	}



	///////////////////////////////////////////////////////////////
	//副本 含重定位...
	//             ...新建一个bone_result_array[]...
	//             ...复制并inc 重定位部分
	private static function rel_copy_create($asm,$usable){

		$ret = self::$_index;
		$value[CODE][99] = $asm;
		$value[USABLE][99] = $usable;
		OrgansOperator::Set(BONE,self::$_index,$value);
		self::$_index ++;
		return $ret;
	}

	///////////////////////////////////////////////////////////////
	//副本复制进链表，并完成前后部的链接
	//    对副本中含有重定位 值的单位，重定位信息副本累加
	private static function insert_copy_2_list(&$c_prev,$copy,$soul_position){

		global $c_rel_info;
		global $sec;

	   
		$c_lp = $soul_position['first'];
		while (true){
            
			self::$_multi_bone_map[$c_lp][] = ConstructionDlinkedListOpt::getDlinkedListIndex(); //multi bone Map

			$tmp_multi_bone_poly[] = ConstructionDlinkedListOpt::getDlinkedListIndex();
			//echo " ,c $c_lp !== ".$soul_position['last'].' , ';

			ConstructionDlinkedListOpt::setDlinkedList($copy[$c_lp],ConstructionDlinkedListOpt::getDlinkedListIndex());

			$c_asm    = OrgansOperator::GetByDListUnit($copy[$c_lp],CODE);
			$c_usable = OrgansOperator::GetByDListUnit($copy[$c_lp],USABLE);

			if (isset($c_asm[REL])){
				//var_dump ($soul_writein_Dlinked_List[$s_w_Dlinked_List_index]);
				//var_dump ($c_asm);
				//var_dump ($c_usable);


				ConstructionDlinkedListOpt::unsetDlinkedList(POLY);
				ConstructionDlinkedListOpt::unsetDlinkedList(MEAT);

				ConstructionDlinkedListOpt::setDlinkedList(self::rel_copy_create($c_asm,$c_usable),ConstructionDlinkedListOpt::getDlinkedListIndex(),BONE);

				ConstructionDlinkedListOpt::setDlinkedList(99,ConstructionDlinkedListOpt::getDlinkedListIndex(),C);
				//var_dump ($bone_result_array[$soul_writein_Dlinked_List[$s_w_Dlinked_List_index][BONE]][CODE]);
				//var_dump ($bone_result_array[$soul_writein_Dlinked_List[$s_w_Dlinked_List_index][BONE]][USABLE]);
				//if (count($c_asm[REL]) > 1){ //测试多个重定位
				//	var_dump ($c_asm);
				//}
				foreach ($c_asm[REL] as $p_number => $p_rel_info){
					$old_rel_n = $p_number;
					$old_rel_i = $p_rel_info['i'];
					$old_rel_c = $p_rel_info[C];
					$new = GenerateFunc::reloc_inc_copy_naked($old_rel_i,$old_rel_c);
					//echo "$old_rel_n $old_rel_i $old_rel_c";

					$tmp_tmp = ConstructionDlinkedListOpt::getDlinkedList(ConstructionDlinkedListOpt::getDlinkedListIndex(),BONE);

					OrgansOperator::Set(BONE,$tmp_tmp,$new,array(CODE,99,REL,$p_number,C));
					
					$c_rel_info[$old_rel_i][$new] = $c_rel_info[$old_rel_i][$old_rel_c];
					
                    $tmp = OrgansOperator::Get(BONE,$tmp_tmp,CODE,99,PARAMS,$old_rel_n);
					$tmp = str_replace(UNIQUEHEAD.'RELINFO_'.$sec.'_'.$old_rel_i.'_'.$old_rel_c,UNIQUEHEAD.'RELINFO_'.$sec.'_'.$old_rel_i.'_'.$new,$tmp);
                    OrgansOperator::Set(BONE,$tmp_tmp,$tmp,array(CODE,99,PARAMS,$old_rel_n));					

					//var_dump ($bone_result_array[$soul_writein_Dlinked_List[$s_w_Dlinked_List_index][BONE]][CODE]);
					//var_dump ($garble_rel_info[$sec][$old_rel_i][$new]);
					//echo "**********************<br>";
				}
			}


			ConstructionDlinkedListOpt::insertDlinkedListByIndex($c_prev);

			$c_prev = ConstructionDlinkedListOpt::getDlinkedListIndex();
			if ($c_lp === $soul_position['last']){

				ConstructionDlinkedListOpt::incDlinkedListIndex();
				break;
			}
			if (!isset($copy[$c_lp][N])){
				//echo "<br>Fuck...".$c_lp.' === '.$soul_position['last'];
				//var_dump ($copy);
				//exit;

				ConstructionDlinkedListOpt::incDlinkedListIndex();
				break;
			}
			$c_lp = $copy[$c_lp][N];		

			ConstructionDlinkedListOpt::incDlinkedListIndex();
		}


		return;
	}

	//对骨架进行初始化(label & Jcc)
	private static function init_bone_model(&$c_bone,$bone_obj){ 


	

		$bone_index = self::$_index;
		self::$_index ++;

	   // var_dump ($c_bone['direct']);


		//补完骨架中的label 标号
		foreach ($c_bone[CODE] as $a => $b){
			if (isset($b[PARAMS][0])){ //跳转参数 最多只可能 有一个 且 为跳转目标标号
				$c_bone[CODE][$a][PARAMS][0] = UNIQUEHEAD.$c_bone[CODE][$a][PARAMS][0].$bone_index;
				if ('Jcc' === $b[OPERATION]){
					$c_bone[CODE][$a][OPERATION] = Instruction::randUnLmtJcc();
				}
			}elseif (isset($b[LABEL])){
				$c_bone[CODE][$a][LABEL] = UNIQUEHEAD.$c_bone[CODE][$a][LABEL].$bone_index." : ";
				//$c_bone[CODE][$a][OPERATION] = UNIQUEHEAD.$c_bone[CODE][$a][LABEL].$bone_index." : ";
			}		
		}

		//var_dump ($bone_obj);
		
		//根据['direct'] 整理出 每个 灵魂位 实际分配到的 开始灵魂(和 结束灵魂) 的链表编号,修改链表时要用
		$soul_position = array();
		$z = 1; //
		$c_last = false;
		foreach ($c_bone['direct'] as $a => $b){
			if ($b){
				$soul_position[$a]['first'] = $bone_obj[$z];
				$soul_position[$a]['last']  = $bone_obj[$b];
				$z = $b + 1;

				if (ConstructionDlinkedListOpt::issetDlinkedListUnit($soul_position[$a]['last'],N)){

					$c_last = ConstructionDlinkedListOpt::getDlinkedList($soul_position[$a]['last'],N);
				}else{
					$c_last = false;
				}
			}
		}

		//根据['copy'] 整理出 灵魂 副本
		$copy = array();
		$copy_count = 0;
		self::$_delay_remove = array();
		if (isset($c_bone['copy'])){
			foreach ($c_bone['copy'] as $a => $b){
				$copy[$b] = self::soul_copy($soul_position[$a]['first'],$soul_position[$a]['last'],$soul_position[$b]);
			}
		}
		
		//echo "<br>copy...";
		//var_dump ($copy);
		//var_dump ($c_bone);
		//var_dump ($soul_position);
		echo "<br>*****************************************<br>";
		//修改 链表，把骨架加入进去

		$c_bone_list_start = ConstructionDlinkedListOpt::getDlinkedListIndex();
		$c_prev = false;

		if (ConstructionDlinkedListOpt::issetDlinkedListUnit($bone_obj[1],P)){

			$c_prev = ConstructionDlinkedListOpt::getDlinkedList($bone_obj[1],P);
		}
		$c_soul_ptr = 1;
		foreach ($c_bone[CODE] as $a => $b){			    
			if (true === $b){ //单位
				if (isset($soul_position[$a]['first'])){ //有效
					if (isset($copy[$a])){ //副本
						self::insert_copy_2_list($c_prev,$copy[$a],$soul_position[$a]);
					}else{
						ConstructionDlinkedListOpt::insertDlinkedList($c_prev,$soul_position[$a]['first']);
						$c_prev = $soul_position[$a]['last'];
					}				
				}
			}else{            //骨架
				if (false === $c_prev){ // 整个链表 首个单位，无 'Prev'
					ConstructionDlinkedListOpt::setListFirstUnit();
				}else{
					ConstructionDlinkedListOpt::insertDlinkedListByIndex($c_prev);
				}

				$c_prev = ConstructionDlinkedListOpt::getDlinkedListIndex();

				ConstructionDlinkedListOpt::setDlinkedList(true,$c_prev,'ipsp');

				ConstructionDlinkedListOpt::setDlinkedList($bone_index,$c_prev,BONE);

				ConstructionDlinkedListOpt::setDlinkedList($a,$c_prev,C);
				if (isset($b[LABEL])){
					ConstructionDlinkedListOpt::setDlinkedList($b[LABEL],$c_prev,LABEL);
				}
                self::$_bone_units[$c_prev] = $c_prev;
				ConstructionDlinkedListOpt::incDlinkedListIndex();
			}
		}
		if (false !== $c_last){

			ConstructionDlinkedListOpt::insertDlinkedList($c_prev,$c_last);
		}

		//正本中想需要清除的标号
		foreach (self::$_delay_remove as $a){
			ConstructionDlinkedListOpt::remove_from_DlinkedList($a);
		}


		// 从灵魂 处 继承骨架的 usable
		self::inherit_bone_usable($c_bone,$soul_position);

		OrgansOperator::Set(BONE,$bone_index,$c_bone);

		//echo "<br>************** $bone_index <br>";

		return $bone_index;
	}

	//出错则返回 false
	private static function collect_usable_bone_model ($bone_obj,$last_ipsp,$c_bone_model){
		//echo "<font color=blue>";
		//var_dump ($bone_obj);
		//var_dump ($last_ipsp);
		$c_soul = 1;
		$c_soul_length = count($bone_obj);
		//var_dump ($c_soul_length);
		//echo "</font>";
		$direct_num = count ($c_bone_model['direct']);
		foreach ($c_bone_model['direct'] as $a => $b){
			$direct_num --;			
			if (!$c_soul_length){ //无 可分配灵魂，所有位置都设为0
				$c_bone_model['direct'][$a] = 0;
			}else{
				if (0 == $direct_num){ //最后一个，直接覆盖到末尾
					$c_bone_model['direct'][$a] = $c_soul - 1 + $c_soul_length;
					break;
				}
				if ((1 === $b)||(!$last_ipsp)){               //本位置可包含所有灵魂 或 灵魂中 不含 IP/SP 影响
					$c_position = mt_rand (0,$c_soul_length); 
					//echo "<br>type 1 rand (0,$c_soul_length) $c_position";
				}elseif (2 === $b){                           //本位置 可包含所有灵魂,且所有代码中含有的对IP/SP影响的灵魂，必须全部 在 本块前(含本块) 分配完
					$c_position = mt_rand ($last_ipsp,$c_soul_length);
					//echo "<br>type 2 rand ($last_ipsp,$c_soul_length) $c_position";
				}elseif (0 === $b){                           //本位置不能含有 对IP/SP影响的灵魂
					//确定最后一个不含 对IP/SP影响的灵魂 作为 可选 边界
					for ($i = 0;$i < $c_soul_length;$i++){

						if (ConstructionDlinkedListOpt::issetDlinkedListUnit($bone_obj[$c_soul + $i],'ipsp')){
							break;
						}   
					}
					$c_position = mt_rand (0,$i);
					//echo "<br>type 3 rand (0,$i) $c_position";
				}else{ //未知属性，返回错误
					return false;
				}

				if ($c_position){			    
					$c_soul_length -= $c_position;
					if ($last_ipsp > $c_position){
						$last_ipsp -= $c_position;
					}else{
						$last_ipsp = 0;
					}				
					$c_soul += $c_position;
					$c_bone_model['direct'][$a] = $c_soul - 1;
				}else{
					$c_bone_model['direct'][$a] = 0;		
				}			
			}
		}

		if ((0 == $b)&&($c_bone_model['direct'][$a])){ //检查最后一个位置是否含有 不支持的类型 ，因为最后一个位置是未经 过滤，直接赋到末 灵魂的
			//echo "<br>check the last position from $c_soul to ".$c_bone_model['direct'][$a]."<br>";
			for ($i = $c_soul;$i <= $c_bone_model['direct'][$a];$i ++){			    

				if (ConstructionDlinkedListOpt::issetDlinkedListUnit($bone_obj[$i],'ipsp')){
					return false;
				}
			}			
		}	

		//检查插入代码/位置【stack禁用】 是否与 bone.templates【stack used】 冲突
		if (false !== self::check_bone_stack_conflict($c_bone_model,$bone_obj)){
			return false;
		}

        self::doTearPosition($c_bone_model,$bone_obj);

		$bone_index = self::init_bone_model($c_bone_model,$bone_obj);		
	
		return true;
		
	}

    //撕裂位(骨架插入位) bone.Rate 减 1 , 最少为 1
	private static function doTearPosition($c_bone_model,$bone_obj){
	    
		$_tear_position = array();
		$s = reset($bone_obj); //头
		$e = end($bone_obj);   //尾

		//位于 ['direct']位 的单位与下一单位(如果有的话,下一单位为下一unit的开头) 
        foreach ($c_bone_model['direct'] as $a){
			if (isset($bone_obj[$a])){
				$_tear_position[$bone_obj[$a]] = $bone_obj[$a];
				if (isset($bone_obj[$a + 1])){
					$_tear_position[$bone_obj[$a + 1]] = $bone_obj[$a + 1];
				}
			}
		} 

        //头尾character.Rate已处理，不重复处理
        unset ($_tear_position[$s]);
        unset ($_tear_position[$e]);

		//bone.Rate - 1
		foreach ($_tear_position as $a){
			Character::modifyRate(BONE,$a,-1);
		}
	}

	//不冲突? 返回false
	//  冲突? 返回冲突位置 -> 
	private static function check_bone_stack_conflict($c_bone_model,$bone_obj){

		$i = 0;
		$stack_unusable = false; //[P] = false -> 前禁用
		$ret = false;
		foreach ($c_bone_model['direct'] as $a => $b){
			if ($b){
				$i ++;
				$tmp = ConstructionDlinkedListOpt::get_unit_by_soul_writein_Dlinked_List($bone_obj[$i]);     //获取指定编号链表的前 or 后可用数组
				if (true !== $tmp[USABLE][P][STACK]){
					$stack_unusable[$a][P] = true;
				}				
				$i = $b;
				$tmp = ConstructionDlinkedListOpt::get_unit_by_soul_writein_Dlinked_List($bone_obj[$i]); //获取指定编号链表的前 or 后可用数组
				if (true !== $tmp[USABLE][N][STACK]){
					$stack_unusable[$a][N] = true;
				}
			}	    
		}

		$conflict_position = array(); //[BONE] [CODE] ['code_direct'] //冲突点 {只搜集一个冲突点}

		if ($stack_unusable){		
			foreach ($c_bone_model['process'] as $a => $b){
				$stack_use = false;       //当前需要stack 使用 (by $c_bone_model)
				$stack_forbid = false;    //当前stack禁用      (by $stack_unusable)
				foreach ($b as $c => $d){
					if (isset($d[P])){
						$conflict_position[BONE] = $d[P];
						if (isset ($c_bone_model[CODE][$d[P]][OPERATION])){
							$tmp = Instruction::getInstructionOpt($c_bone_model[CODE][$d[P]][OPERATION],count($c_bone_model[CODE][$d[P]][PARAMS]));
							if (isset($tmp[STACK])){
								if ($stack_forbid){
									$ret = true;
									break;
								}
								$stack_use = true;
							}else{
								$stack_use = false;
							}
						}
					}elseif (isset($d['s'])){
						if (($stack_use) and (true === $stack_unusable[$d['s']][P])){ //conflict
							$conflict_position[CODE] = $d['s'];
							$conflict_position['code_direct'] = P;
							$ret = true;
							break;
						}
						$conflict_position[CODE] = $d['s'];
						$conflict_position['code_direct'] = N;
						if (true === $stack_unusable[$d['s']][N]){
							$stack_forbid = true;
						}else{
							$stack_forbid = false;
						}
					}
				}
				if ($ret){
					break;
				}
			}
		}
		if (defined('DEBUG_ECHO')){
			DebugShowFunc::my_shower_05 ($c_bone_model,$bone_obj,$stack_unusable,$ret,$conflict_position);
		}
		return $ret;
	}

	//为多通道bone单位分配character.Rate, readme.bone.txt [2014.10.22]
	private static function resetRate(){

		//echo '<br> multi bone unit map';
		foreach (self::$_multi_bone_map as $a => $b){						
			//echo "<br>$a :::";
			//var_dump (self::$_multi_bone_unit_rate[$a]);
			$b[] = $a;
			shuffle($b);
			$addRate = 0;
			foreach ($b as $c){				
				if (ConstructionDlinkedListOpt::isValidID($c)){
					if ($addRate){
						character::cloneRate($c,self::$_multi_bone_unit_rate[$a],$addRate);
					}
					$addRate ++;
				}
			}
		}
	}

	public static function start($bone_obj){

		self::$_delay_remove         = array();
		self::$_bone_units           = array();
		self::$_multi_bone_unit_rate = array();
		self::$_multi_bone_map       = array();


		if (count($bone_obj) <= self::$_bone_multi_max_size){
			$c_bone_model_index = self::$_bone_model_index_multi;
		}else{ //too much obj to use multi bone templates.
		    $c_bone_model_index = self::$_bone_model_index;
		}

		$last_ipsp = false;
		
		foreach ($bone_obj as $a => $b){
			if ((ConstructionDlinkedListOpt::getDlinkedList($b,'ipsp'))||(ConstructionDlinkedListOpt::getDlinkedList($b,LABEL))){
				$last_ipsp = $a; //记录 目标 骨架 数组 中 最后一个 影响 ipsp 
			}
		}
		
		$x = array_rand($c_bone_model_index);
		$z = $c_bone_model_index[$x];
		echo "<br> bone repo index: $z ";
		//$z = 2; //测试 多通道，强制指定
		if ($z){
			if (self::collect_usable_bone_model ($bone_obj,$last_ipsp,self::$_bone_model_repo[$z])){ //骨架出错,代表 骨架模块 有问题	
                if (!empty(self::$_multi_bone_map)){ // set character.Rate for 多通道 bone
					self::resetRate();	
				}			
				foreach (self::$_bone_units as $a){ // bone init character.Rate
				    Character::initUnit($a,BONE);
				}				
			}else{
				global $language;
				GeneralFunc::LogInsert($language['fail_bone_array'].$z,2);				
			}			
		}else{ //骨架数组为空...
			return;   
		}
	}
}

?>