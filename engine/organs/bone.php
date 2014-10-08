<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}

class OrganBone{
    private static $_index = 1; //bone 唯一编号,全目标 唯一

    public static function init(){
	    self::$_index = 1;
	}

	//清除指定 usable中 影响 ipsp的单位 (根据 双向链表 索引 开始 -> 结束 )
	private static function remove_ipsp_from_usable_list($start,$end){

		global $all_valid_mem_opt_index;
        global $c_user_cnf_stack_pointer_define;

		$c_lp = $start;		
		while (true){

            OrgansOperator::FilterMemUsable($c_lp,$c_user_cnf_stack_pointer_define);	   

			if ($c_lp === $end){
				break;
			}
			if (!ConstructionDlinkedListOpt::issetDlinkedListUnit($c_lp,'n')){
				break;
			}
			$c_lp = ConstructionDlinkedListOpt::getDlinkedList($c_lp,'n');
		}
		
	}

	// 从灵魂 处 继承骨架的 usable
	private static function inherit_bone_usable(&$c_bone_result_array,$c_soul_position){
		
		global $c_user_cnf_stack_pointer_define;

		//第一个 / 最后一个必定是 骨架单位
		foreach ($c_bone_result_array['process'] as $x){ //多通道? 直接覆盖
			$first = false;
			$last  = false;	
			$buff  = array();
			foreach ($x as $b){
				if (isset($b['s'])){
					if (isset($c_soul_position[$b['s']])){
						$first = ConstructionDlinkedListOpt::get_usable_from_DlinkedList($c_soul_position[$b['s']]['first'],'p');
						$last  = ConstructionDlinkedListOpt::get_usable_from_DlinkedList($c_soul_position[$b['s']]['last'],'n');					
						if (isset($buff)){
							//echo '<br>buff:';
							//var_dump ($buff);
							foreach ($buff as $z){
								if (isset($z['p'])){
									$c_bone_result_array['usable'][$z['p']]['p'] = $first;
								}elseif (isset($z['n'])){
									$c_bone_result_array['usable'][$z['n']]['n'] = $first;
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
					if (isset($z['p'])){
						$c_bone_result_array['usable'][$z['p']]['p'] = $last;
					}elseif (isset($z['n'])){
						$c_bone_result_array['usable'][$z['n']]['n'] = $last;
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
					if (isset($c_bone_result_array['usable'][$a]['p']['mem_opt_able'])){
						OrgansOperator::doFilterMemUsable($c_bone_result_array['usable'][$a]['p']['mem_opt_able'],$c_user_cnf_stack_pointer_define);
					}			    
				}elseif (2 === $b){ //next
					if (isset($c_bone_result_array['usable'][$a]['n']['mem_opt_able'])){
						OrgansOperator::doFilterMemUsable($c_bone_result_array['usable'][$a]['n']['mem_opt_able'],$c_user_cnf_stack_pointer_define);
					}			    
				}
			}
		}

		//对fat区域，做stack可用标记
		foreach ($c_bone_result_array['fat'] as $y => $x){
			if (1 == $x){
				$c_bone_result_array['usable'][$y]['p']['stack'] = true;
			}else{
				$c_bone_result_array['usable'][$y]['n']['stack'] = true;
			}
		}
		//对多态结果进行stack可用状态设置(根据usable)
		GeneralFunc::soul_stack_set($c_bone_result_array['code'],$c_bone_result_array['usable']);
	}

	////对 副本 多态部分进行压缩处理
	private static function poly_compress(&$copy_buff,&$c_first,&$c_last){

		$c_lp = $c_last;

		$counter       = 0;
		$buf           = array();
		$c_count_index = false; 
		$compress = false;
	//echo "<br>**************************<br>";
		while (true){
			//echo "<br>$c_lp";
			if (isset($copy_buff[$c_lp]['poly'])){
				if ($c_count_index === $copy_buff[$c_lp]['poly']){
					$counter ++;
					$buf[] = $c_lp;
				}else{
					$counter = 1;
					unset ($buf);
					$buf[] = $c_lp;
					$c_count_index = $copy_buff[$c_lp]['poly'];
				}
				if ($counter == OrgansOperator::GetPolyReverse($copy_buff[$c_lp]['poly'],'n')){ //压缩
					$compress = true;
					break;
				}
			}elseif (isset($copy_buff[$c_lp]['label'])){ //非 多态标号 ，忽略
				$buf[] = $c_lp;
			}else{
				$c_count_index = false; 
			}

			if ($c_lp === $c_first){
				break;
			}
			if (!isset($copy_buff[$c_lp]['p'])){			
				break;
			}
			$c_lp = $copy_buff[$c_lp]['p'];
		
		}
		
		if (true === $compress){ //进行压缩
			$lp_linked_list = OrgansOperator::GetPolyReverse($c_count_index,'i'); //压缩目标 在链表中的 index号

			$copy_buff[$lp_linked_list] = ConstructionDlinkedListOpt::getDlinkedList($lp_linked_list);
			unset($copy_buff[$lp_linked_list]['302']);
			$next = false;		
			//调整 相关单位 的PREV/NEXT 指针并清除 被压缩目标代码
			foreach ($buf as $a){
				if (false === $next){
					$copy_buff[$lp_linked_list]['n'] = $copy_buff[$a]['n']; //压缩后 NEXT指针 继承 被压缩代码的 第一个即最后单位的NEXT(从下往上遍历)
					if (isset($copy_buff[$copy_buff[$a]['n']])){
						$copy_buff[$copy_buff[$a]['n']]['p'] = $lp_linked_list;
					}
					$next = true;
				}
				if ($c_first === $a){ //首单位 包含在 压缩目标中
					$c_first = $lp_linked_list;
				}			
				if ($c_last  === $a){ //末单位 包含在 压缩目标中
					$c_last  = $lp_linked_list;
				}
				if (isset($copy_buff[$a]['p'])){
					$copy_buff[$lp_linked_list]['p'] = $copy_buff[$a]['p'];
				}
				unset ($copy_buff[$a]);
			}	
			if (isset($copy_buff[$copy_buff[$lp_linked_list]['p']])){
				$copy_buff[$copy_buff[$lp_linked_list]['p']]['n'] = $lp_linked_list;
			}
		}
		return $compress;
	}

    // 对照 ConstructionDlinkedListOpt::remove_from_DlinkedList
	private static function remove_from_DlinkedList($c_lp,&$lp_first,&$lp_last,&$copy_buff){
		$prev = false;
		$next = false;		

		if (isset($copy_buff[$c_lp]['p'])){
			$prev = $copy_buff[$c_lp]['p'];
		}else{ //无 'p', 摘除目标是 首单位
			$lp_first = false;		
		}
		
		if (isset($copy_buff[$c_lp]['n'])){
			$next = $copy_buff[$c_lp]['n'];
		}else{ //无 'n', 摘除目标是 尾单位
			$lp_last = false;		
		}
		//unset ($copy_buff[$c_lp]);
		$copy_buff[$c_lp]['302'] = $next;
		if (false !== $prev)
			unset ($copy_buff[$prev]['n']);
		if (false !== $next)
			unset ($copy_buff[$next]['p']);

		if ((false !== $prev)&&(false !== $next)){
			$copy_buff[$prev]['n'] = $next;
			$copy_buff[$next]['p'] = $prev;
		}elseif (false !== $next){ //if Prev == false
			$lp_first = $next;
		}elseif (false !== $prev){ //if Next == false
			$lp_last = $prev;
		}
	}

	//对骨架多通道 进行副本的复制
	private static function soul_copy($source_first,$source_last,&$dest,&$delay_remove){

		
		$c_first  = $source_first;
		$c_lp     = $source_first;
		$copy_buff = array(); //副本 缓存区
		
		while (true){
			//echo "a $c_lp !== $source_last,";

			$copy_buff[$c_lp] = ConstructionDlinkedListOpt::getDlinkedList($c_lp);
			if ($c_lp === $source_last){
				break;
			}

			if (!ConstructionDlinkedListOpt::issetDlinkedListUnit($c_lp,'n')){
				break;
			}

			$c_lp = ConstructionDlinkedListOpt::getDlinkedList($c_lp,'n');
		}
		$c_last = $c_lp;

		//对 副本 多态部分进行压缩处理
		while (count($copy_buff) > 1){		
			if (false === self::poly_compress($copy_buff,$c_first,$c_last)){
				break;
			}				
		}
		//去除首个单位 Prev 指针 和 末尾单位 Next 指针
		unset ($copy_buff[$c_first]['p']);
		unset ($copy_buff[$c_last]['n']);
		//副本 和 正本 的标签 部分，2者只保留一处
		$c_lp = $c_first;

		$dest['first'] = $c_first;
		$dest['last']  = $c_last;
		while (true){		
			//echo "b $c_lp !== $c_last ,";
			if (isset($copy_buff[$c_lp]['n'])){
				$next = $copy_buff[$c_lp]['n'];
			}else{
				$next = false;
			}
			if (isset($copy_buff[$c_lp]['label'])){ //标号在 副本产生时，随机保留一份(副本或正本中)		
				if (mt_rand(0,1)){
					$delay_remove[] = $c_lp; // 正本中清除 留到 骨架插入完成后清理
				}else{
					self::remove_from_DlinkedList($c_lp,$dest['first'],$dest['last'],$copy_buff);	
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
		$value['code'][99] = $asm;
		$value['usable'][99] = $usable;
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
		global $UniqueHead;
		global $multi_bone_poly; //多通道 骨架完成后副本部分需要被poly

	   
		$c_lp = $soul_position['first'];
		while (true){

			$tmp_multi_bone_poly[] = ConstructionDlinkedListOpt::getDlinkedListIndex();
			//echo " ,c $c_lp !== ".$soul_position['last'].' , ';

			ConstructionDlinkedListOpt::setDlinkedList($copy[$c_lp],ConstructionDlinkedListOpt::getDlinkedListIndex());

			$c_asm    = OrgansOperator::GetByDListUnit($copy[$c_lp],'code');
			$c_usable = OrgansOperator::GetByDListUnit($copy[$c_lp],'usable');

			if (isset($c_asm['rel'])){
				//var_dump ($soul_writein_Dlinked_List[$s_w_Dlinked_List_index]);
				//var_dump ($c_asm);
				//var_dump ($c_usable);


				ConstructionDlinkedListOpt::unsetDlinkedList('poly');
				ConstructionDlinkedListOpt::unsetDlinkedList('meat');

				ConstructionDlinkedListOpt::setDlinkedList(self::rel_copy_create($c_asm,$c_usable),ConstructionDlinkedListOpt::getDlinkedListIndex(),'bone');

				ConstructionDlinkedListOpt::setDlinkedList(99,ConstructionDlinkedListOpt::getDlinkedListIndex(),'c');
				//var_dump ($bone_result_array[$soul_writein_Dlinked_List[$s_w_Dlinked_List_index]['bone']]['code']);
				//var_dump ($bone_result_array[$soul_writein_Dlinked_List[$s_w_Dlinked_List_index]['bone']]['usable']);
				//if (count($c_asm['rel']) > 1){ //测试多个重定位
				//	var_dump ($c_asm);
				//}
				foreach ($c_asm['rel'] as $p_number => $p_rel_info){
					$old_rel_n = $p_number;
					$old_rel_i = $p_rel_info['i'];
					$old_rel_c = $p_rel_info['c'];
					$new = GenerateFunc::reloc_inc_copy_naked($old_rel_i,$old_rel_c);
					//echo "$old_rel_n $old_rel_i $old_rel_c";

					$tmp_tmp = ConstructionDlinkedListOpt::getDlinkedList(ConstructionDlinkedListOpt::getDlinkedListIndex(),'bone');

					OrgansOperator::Set(BONE,$tmp_tmp,$new,array('code',99,'rel',$p_number,'c'));
					
					$c_rel_info[$old_rel_i][$new] = $c_rel_info[$old_rel_i][$old_rel_c];
					
                    $tmp = OrgansOperator::Get(BONE,$tmp_tmp,'code',99,'params',$old_rel_n);
					$tmp = str_replace("$UniqueHead".'RELINFO_'.$sec.'_'.$old_rel_i.'_'.$old_rel_c,"$UniqueHead".'RELINFO_'.$sec.'_'.$old_rel_i.'_'.$new,$tmp);
                    OrgansOperator::Set(BONE,$tmp_tmp,$tmp,array('code',99,'params',$old_rel_n));					

					//var_dump ($bone_result_array[$soul_writein_Dlinked_List[$s_w_Dlinked_List_index]['bone']]['code']);
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
			if (!isset($copy[$c_lp]['n'])){
				//echo "<br>Fuck...".$c_lp.' === '.$soul_position['last'];
				//var_dump ($copy);
				//exit;

				ConstructionDlinkedListOpt::incDlinkedListIndex();
				break;
			}
			$c_lp = $copy[$c_lp]['n'];		

			ConstructionDlinkedListOpt::incDlinkedListIndex();
		}

		$multi_bone_poly[] = $tmp_multi_bone_poly;

		return;
	}

	//对骨架进行初始化(label & Jcc)
	private static function init_bone_model(&$c_bone,$bone_obj){ 

		global $UniqueHead;
		global $Jcc;

		$bone_index = self::$_index;
		self::$_index ++;

	   // var_dump ($c_bone['direct']);


		//补完骨架中的label 标号
		foreach ($c_bone['code'] as $a => $b){
			if (isset($b['params'][0])){ //跳转参数 最多只可能 有一个 且 为跳转目标标号
				$c_bone['code'][$a]['params'][0] = $UniqueHead.$c_bone['code'][$a]['params'][0].$bone_index;
				if ('Jcc' === $b['operation']){
					$c_bone['code'][$a]['operation'] = $Jcc[array_rand($Jcc)];
				}
			}elseif (isset($b['label'])){
				$c_bone['code'][$a]['label'] = $UniqueHead.$c_bone['code'][$a]['label'].$bone_index." : ";
				//$c_bone['code'][$a]['operation'] = $UniqueHead.$c_bone['code'][$a]['label'].$bone_index." : ";
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

				if (ConstructionDlinkedListOpt::issetDlinkedListUnit($soul_position[$a]['last'],'n')){

					$c_last = ConstructionDlinkedListOpt::getDlinkedList($soul_position[$a]['last'],'n');
				}else{
					$c_last = false;
				}
			}
		}

		//根据['copy'] 整理出 灵魂 副本
		$copy = array();
		$delay_remove = array();
		if (isset($c_bone['copy'])){
			foreach ($c_bone['copy'] as $a => $b){
				$copy[$b] = self::soul_copy($soul_position[$a]['first'],$soul_position[$a]['last'],$soul_position[$b],$delay_remove);
			}
		}
		//var_dump ($copy);
		//var_dump ($c_bone);
		//var_dump ($soul_position);
		//echo "<br>*****************************************<br>";
		//修改 链表，把骨架加入进去

		$c_bone_list_start = ConstructionDlinkedListOpt::getDlinkedListIndex();
		$c_prev = false;

		if (ConstructionDlinkedListOpt::issetDlinkedListUnit($bone_obj[1],'p')){

			$c_prev = ConstructionDlinkedListOpt::getDlinkedList($bone_obj[1],'p');
		}
		$c_soul_ptr = 1;
		foreach ($c_bone['code'] as $a => $b){			    
			if (true === $b){ //灵魂
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
					/*
					if (4 === $a){
						global $c_Asm_Result;
						echo "<br><font color=red>$a $a $a : $c_prev : $s_w_Dlinked_List_index</font><br>";
						var_dump ($soul_writein_Dlinked_List[$c_prev]);

					}*/				


					ConstructionDlinkedListOpt::insertDlinkedListByIndex($c_prev);
				}

				ConstructionDlinkedListOpt::setDlinkedList(true,ConstructionDlinkedListOpt::getDlinkedListIndex(),'ipsp');

				ConstructionDlinkedListOpt::setDlinkedList($bone_index,ConstructionDlinkedListOpt::getDlinkedListIndex(),'bone');

				ConstructionDlinkedListOpt::setDlinkedList($a,ConstructionDlinkedListOpt::getDlinkedListIndex(),'c');
				if (isset($b['label'])){

					ConstructionDlinkedListOpt::setDlinkedList($b['label'],ConstructionDlinkedListOpt::getDlinkedListIndex(),'label');
				}
				//var_dump ($soul_writein_Dlinked_List[$s_w_Dlinked_List_index]);

				$c_prev = ConstructionDlinkedListOpt::getDlinkedListIndex();

				ConstructionDlinkedListOpt::incDlinkedListIndex();
			}
		}
		if (false !== $c_last){


			ConstructionDlinkedListOpt::insertDlinkedList($c_prev,$c_last);
		}

		

		//正本中想需要清除的标号 
		foreach ($delay_remove as $a){
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

		$bone_index = self::init_bone_model($c_bone_model,$bone_obj);
		
		return true;
		
	}

	//不冲突? 返回false
	//  冲突? 返回冲突位置 -> 
	private static function check_bone_stack_conflict($c_bone_model,$bone_obj){
		global $my_params;
		$i = 0;
		$stack_unusable = false; //['p'] = false -> 前禁用
		$ret = false;
		foreach ($c_bone_model['direct'] as $a => $b){
			if ($b){
				$i ++;
				$tmp = ConstructionDlinkedListOpt::get_unit_by_soul_writein_Dlinked_List($bone_obj[$i]);     //获取指定编号链表的前 or 后可用数组
				if (true !== $tmp['usable']['p']['stack']){
					$stack_unusable[$a]['p'] = true;
				}				
				$i = $b;
				$tmp = ConstructionDlinkedListOpt::get_unit_by_soul_writein_Dlinked_List($bone_obj[$i]); //获取指定编号链表的前 or 后可用数组
				if (true !== $tmp['usable']['n']['stack']){
					$stack_unusable[$a]['n'] = true;
				}
			}	    
		}

		$conflict_position = array(); //['bone'] ['code'] ['code_direct'] //冲突点 {只搜集一个冲突点}

		if ($stack_unusable){		
			foreach ($c_bone_model['process'] as $a => $b){
				$stack_use = false;       //当前需要stack 使用 (by $c_bone_model)
				$stack_forbid = false;    //当前stack禁用      (by $stack_unusable)
				foreach ($b as $c => $d){
					if (isset($d['p'])){
						$conflict_position['bone'] = $d['p'];
						if (isset ($c_bone_model['code'][$d['p']]['operation'])){
							$tmp = GenerateFunc::get_inst_define($c_bone_model['code'][$d['p']]['operation'],$c_bone_model['code'][$d['p']]['params']);
							if (isset($tmp['STACK'])){
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
						if (($stack_use) and (true === $stack_unusable[$d['s']]['p'])){ //conflict
							$conflict_position['code'] = $d['s'];
							$conflict_position['code_direct'] = 'p';
							$ret = true;
							break;
						}
						$conflict_position['code'] = $d['s'];
						$conflict_position['code_direct'] = 'n';
						if (true === $stack_unusable[$d['s']]['n']){
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
		if ($my_params['echo']){
			DebugShowFunc::my_shower_05 ($c_bone_model,$bone_obj,$stack_unusable,$ret,$conflict_position);
		}
		return $ret;
	}

	public static function start($bone_obj,$language){		 

		global $bone_model_index;
		global $bone_model_index_multi;
		global $bone_model_repo;
		global $MAX_INCLUDE_MULTI_PROCESS_BONE;

		if (count($bone_obj) <= $MAX_INCLUDE_MULTI_PROCESS_BONE){  //可用多通道骨架 最大
			$c_bone_model_index = $bone_model_index_multi;
		}else{
			$c_bone_model_index = $bone_model_index;
		}

		$last_ipsp = false;
		if (is_array($bone_obj)){
			foreach ($bone_obj as $a => $b){

				if ((ConstructionDlinkedListOpt::getDlinkedList($b,'ipsp'))||(ConstructionDlinkedListOpt::getDlinkedList($b,'label'))){
					$last_ipsp = $a; //记录 目标 骨架 数组 中 最后一个 影响 ipsp 
				}
			}
		}		
		$x = array_rand($c_bone_model_index);
		$z = $c_bone_model_index[$x];
		echo "<br> bone repo index: $z ";
		//$z = 2; //测试 多通道，强制指定
		if ($z){
			$start_index = self::$_index;

			$start_lp    = ConstructionDlinkedListOpt::getDlinkedListIndex();


			$ready_for_poly_start = ConstructionDlinkedListOpt::getDlinkedListIndex();

			if (!self::collect_usable_bone_model ($bone_obj,$last_ipsp,$bone_model_repo[$z])){ //骨架出错,代表 骨架模块 有问题
				GeneralFunc::LogInsert($language['fail_bone_array'].$z,2);				
			}
		}else{ //骨架数组为空...
			return;   
		}
	}
}

?>