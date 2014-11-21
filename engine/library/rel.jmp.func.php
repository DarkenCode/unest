<?php

////////////////////////////////////////////////
//处理rel jmp 数组相关

class RelJmp{
	////////////////////////////////////////////////
	//
	//根据新range重新计算rel.jmp指令的opcode len
	//
	public static function resize_rel_jmp_array($rel_jmp_range_key){

		//var_dump ($rel_jmp_range_key);
		//var_dump ($c_rel_jmp_range);

		$old_rel_jmp_range = ConstructionDlinkedListOpt::ReadRollingRelJmpRange();
		
		while (!empty($rel_jmp_range_key)){
			$a = array_pop($rel_jmp_range_key);
			$b = ConstructionDlinkedListOpt::readRelJmpRange($a);//$c_rel_jmp_range[$a];

			if (isset($old_rel_jmp_range[$a])){
				if ($old_rel_jmp_range[$a]['range'] == $b['range']){  //未改变，下一个
					//echo "<br><br>same -> $a :";
					//var_dump ($old_rel_jmp_range[$a]);
					//var_dump ($b);
					continue;
				}else{                                                //原有，改变了跨度，检查跨度变化是否引起位数(byte)变化,如是，则重算
																	 //                   未改变位数...不会导致Oplen变化，直接continue
					if ((($old_rel_jmp_range[$a]['range'] <= 127) and ($b['range'] <= 127)) or (($old_rel_jmp_range[$a]['range'] > 127) and ($b['range'] > 127))){
						//echo "<br><br>old -> $a :";
						//var_dump ($old_rel_jmp_range[$a]);
						//var_dump ($b);			                                                              
						continue;
					}
					//echo "<br><br>old ->bit changed $a :";
					//var_dump ($old_rel_jmp_range[$a]);
					//var_dump ($b);			                                                              
				}
			}else{                                                    //新rel.jmp ，重算
				//echo "<br><br>new -> $a :";
				//var_dump ($b);
			}
			//开始重算
			$tmp = self::get_addition_List_info($a,true,false);
			$c_change = $tmp['len'] - ConstructionDlinkedListOpt::getDlinkedList($a,'len');//新增字节数(减少则为负)
			if ($c_change){                                                  //oplen 发生改变	

				ConstructionDlinkedListOpt::OplenIncrease($c_change);
				
				//var_dump ($c_change);
				//var_dump ($soul_writein_Dlinked_List[$a]);
				ConstructionDlinkedListOpt::setDlinkedList($tmp['len'],$a,'len');
				//var_dump ($tmp);
				
				//影响 本单位影响 的 其他 rel.jmp 
				$tmp = ConstructionDlinkedListOpt::ReadRelJmpPointer($a);
				foreach ($tmp as $z => $y){
					if ($y & 4){
						ConstructionDlinkedListOpt::increaseRelJmpRange($z,$c_change); //$c_rel_jmp_range[$z]['range'] += $c_change;
						if (!isset($rel_jmp_range_key[$z])){
							//echo "<br><font color=red><b>recalculate $z  $c_change</b></font>";
							$rel_jmp_range_key[$z] = $z;
						}
					}
				}		
			}else{
				//echo "<br>no change...";
			}
		}
		//exit;


		return true;
	}

	////////////////////////////////////////////////
	//完成构造 定长跳转 的数据结构 ($rel_jmp_range $rel_jmp_pointer)
	//    同时 完成 未知长度单位之长度
	//
	//返回 true
	//     false : rel_jmp over range (exp: loop n ; n > 127)
	//           : 构建失败，程序逻辑错误
	//
	//$rel_jmp_range   输出 见 /readme/readme.arrays.txt
	//$rel_jmp_pointer 输出 见 /readme/readme.arrays.txt
	//$discard_objs    被丢弃部分，默认为无，false
	//$backup_List     原单位链表 (discard为真时，discard以前的List)
	//$c_List_start    起始单位
	//
	public static function  reset_rel_jmp_array($discard_objs = false,$backup_List=false,$c_List_start=0){  

		$in_cache_last_src   = false;
		$in_cache_last_label = false;
		$in_cache_last_whole = false;

		$in_cache_src   = array();
		$in_cache_label = array();


		if (false === $discard_objs){ //首次初始化 , 指定 启始unit
			$c_unit = $c_List_start;
			$end_unit = false;			
		}else{
			//构建 $in_cache_数组 //去除 discard_objs中内容
			self::init_in_cache_array($discard_objs,$backup_List,$c_List_start,$c_unit,$end_unit,$in_cache_last_src,$in_cache_last_label,$in_cache_last_whole,$in_cache_src,$in_cache_label);

	/*
			echo "<br>vvvvvvvvvvvvvvvvvvv";		
			echo "<br> discard_objs:";
			var_dump ($discard_objs);
			echo "<br> in_cache_label:";
			var_dump ($in_cache_label);		
			echo "<br> in_cache_src:";
			var_dump ($in_cache_src);		
			foreach ($in_cache_src as $a => $b){
				echo "$a :";
				var_dump ($b);
			}

			echo "<br> in_cache_last_whole:";
			var_dump ($in_cache_last_whole);		
			echo "<br> in_cache_last_label:";
			var_dump ($in_cache_last_label);
			echo "<br> in_cache_last_src:";
			var_dump ($in_cache_last_src);
			foreach ($in_cache_last_src as $a => $b){
				echo "$a :";
				var_dump ($b);
			}
			echo "<br>vvvvvvvvvvvvvvvvvvv";


			var_dump ($c_unit);
			var_dump ($end_unit);	
	*/
			
		}   

		////////////////////////////////////////////
		//开始遍历	
		while (true){

			if ($c_unit === $end_unit){
				break;
			}

			if (!ConstructionDlinkedListOpt::issetDlinkedListUnit($c_unit,'len')){ //当前单位无len，获取之 (len,rel_jmp)
				$tmp = self::get_addition_List_info($c_unit,true,true);
				ConstructionDlinkedListOpt::setDlinkedList($tmp['len'],$c_unit,'len');
				if (isset($tmp['rel_jmp'])){
					ConstructionDlinkedListOpt::setDlinkedList($tmp['rel_jmp'],$c_unit,'rel_jmp');
				}
			}

			ConstructionDlinkedListOpt::OplenIncrease(ConstructionDlinkedListOpt::getDlinkedList($c_unit,'len'));
			
			if (false !== $in_cache_last_whole){ //需要插入
				foreach ($in_cache_last_whole as $a){
					ConstructionDlinkedListOpt::SetRelJmpPointer($c_unit,$a,1 | 2 | 4);    
					ConstructionDlinkedListOpt::setRelJmpRange(1 | 2 | 4,$a,'unit',$c_unit);//$rel_jmp_range[$a]['unit'][$c_unit] = 1 | 2 | 4;
					//$rel_jmp_range[$a]['range'] += $soul_writein_Dlinked_List[$c_unit]['len'];
					ConstructionDlinkedListOpt::increaseRelJmpRange($a,ConstructionDlinkedListOpt::getDlinkedList($c_unit,'len'));
					//range 超过 max 返回false
					if (ConstructionDlinkedListOpt::outRelJmpRange($a)){
						return false;
					}
				}
			}

			if (ConstructionDlinkedListOpt::issetDlinkedListUnit($c_unit,LABEL)){  //is label
				$label = ConstructionDlinkedListOpt::getDlinkedList($c_unit,LABEL);
				if (is_array($in_cache_src[$label])){
					foreach ($in_cache_src[$label] as $a => $b){
						ConstructionDlinkedListOpt::setRelJmpRange($b,$a);//$rel_jmp_range[$a] = $b;
						ConstructionDlinkedListOpt::setRelJmpRange(1 | 4,$a,'unit',$c_unit);//$rel_jmp_range[$a]['unit'][$c_unit] = 1 | 4;
						//range 超过 max 返回false
						if (ConstructionDlinkedListOpt::outRelJmpRange($a)){
							return false;
						}
						//foreach ($rel_jmp_range[$a]['unit'] as $c => $d){
						ConstructionDlinkedListOpt::RelJmpRange2Pointer($a);
					}
					unset($in_cache_src[$label]);
				}
				$in_cache_label[$label]['unit'][$c_unit] = 2 | 4;
			}

			if (ConstructionDlinkedListOpt::issetDlinkedListUnit($c_unit,'rel_jmp')){ //is rel jmp
				$label = ConstructionDlinkedListOpt::getDlinkedList($c_unit,'rel_jmp',LABEL);
				if (isset($in_cache_label[$label])){
					//$rel_jmp_range[$c_unit]['max']    = $soul_writein_Dlinked_List[$c_unit]['rel_jmp']['max'];
					ConstructionDlinkedListOpt::setRelJmpRange(ConstructionDlinkedListOpt::getDlinkedList($c_unit,'rel_jmp','max'),$c_unit,'max');				
					//$rel_jmp_range[$c_unit][LABEL]  = $soul_writein_Dlinked_List[$c_unit]['rel_jmp'][LABEL];
					ConstructionDlinkedListOpt::setRelJmpRange(ConstructionDlinkedListOpt::getDlinkedList($c_unit,'rel_jmp',LABEL),$c_unit,LABEL);
					//$rel_jmp_range[$c_unit]['range']  = $in_cache_label[$label]['range'];
					ConstructionDlinkedListOpt::setRelJmpRange($in_cache_label[$label]['range'],$c_unit,'range');
					//$rel_jmp_range[$c_unit]['range'] += $soul_writein_Dlinked_List[$c_unit]['len'];
					ConstructionDlinkedListOpt::increaseRelJmpRange($c_unit,ConstructionDlinkedListOpt::getDlinkedList($c_unit,'len'));
					//$rel_jmp_range[$c_unit]['unit']   = $in_cache_label[$label]['unit'];
					ConstructionDlinkedListOpt::setRelJmpRange($in_cache_label[$label]['unit'],$c_unit,'unit');
					//$rel_jmp_range[$c_unit]['unit'][$c_unit] = 1 | 4;
					ConstructionDlinkedListOpt::setRelJmpRange(1 | 4,$c_unit,'unit',$c_unit);
					//range 超过 max 返回false
					if (true === ConstructionDlinkedListOpt::outRelJmpRange($c_unit)){
						return false;
					}
					ConstructionDlinkedListOpt::RelJmpRange2Pointer($c_unit);
				}else{
					$in_cache_src[$label][$c_unit]          = ConstructionDlinkedListOpt::getDlinkedList($c_unit,'rel_jmp');
					$in_cache_src[$label][$c_unit]['range'] = 0;
					$in_cache_src[$label][$c_unit]['unit'][$c_unit] = 2;
					
				}
			}

			//为 in_cache_ 函数累加当前单位
			$tmp = $in_cache_label;
			foreach ($tmp as $label => $a){
				if (!isset($in_cache_label[$label]['unit'][$c_unit])){
					$in_cache_label[$label]['range'] += ConstructionDlinkedListOpt::getDlinkedList($c_unit,'len');
					$in_cache_label[$label]['unit'][$c_unit] = 1 | 2 | 4;
				}
			}
			
			$tmp = $in_cache_src;
			foreach ($tmp as $label => $a){
				foreach ($a as $b => $c){
					if (!isset($in_cache_src[$label][$b]['unit'][$c_unit])){	
						$in_cache_src[$label][$b]['range'] += ConstructionDlinkedListOpt::getDlinkedList($c_unit,'len');
						$in_cache_src[$label][$b]['unit'][$c_unit] = 1 | 2 | 4;
					}
				}
			}

			//next unit
			if (ConstructionDlinkedListOpt::issetDlinkedListUnit($c_unit,N)){
				$c_unit = ConstructionDlinkedListOpt::getDlinkedList($c_unit,N);
			}else{
				break;
			}
		}

		//$in_cache_last_?? 处理
		if (false !== $in_cache_last_src){    //链表 当前范围后 还有src需要当前label进行匹配
			foreach ($in_cache_last_src as $label => $v){
				if (isset($in_cache_label[$label])){
					foreach ($v as $a => $b){
						ConstructionDlinkedListOpt::setRelJmpRange($b,$a);//$rel_jmp_range[$a] = $b;
						//$rel_jmp_range[$a]['range'] += $in_cache_label[$label]['range'];
						ConstructionDlinkedListOpt::increaseRelJmpRange($a,$in_cache_label[$label]['range']);
						//range 超过 max 返回false
						if (ConstructionDlinkedListOpt::outRelJmpRange($a)){
							return false;
						}
						//foreach ($rel_jmp_range[$a]['unit'] as $c => $d){
						ConstructionDlinkedListOpt::RelJmpRange2Pointer($a);
					
						foreach ($in_cache_label[$label]['unit'] as $c => $d){
							ConstructionDlinkedListOpt::setRelJmpRange($d,$a,'unit',$c);
							ConstructionDlinkedListOpt::SetRelJmpPointer($c,$a,$d);
						}
					}
				}else{ //无法匹配，程序逻辑错误!!! 写log
					$log = array();
					$log['in_cache_last_src'] = $in_cache_last_src;
					$log['in_cache_label']    = $in_cache_label;
					GeneralFunc::internal_log_save('still have $in_cache_src here till func end : [func reset_rel_jmp_array] ',$log);
					return false;
					//var_dump ($in_cache_last_src);
					//var_dump ($in_cache_label);
					//exit('<br><br>fucka!!!!!!!! '.$label);
					
				}
			}	    
		}

		if (false !== $in_cache_last_label){  //链表 当前范围后 还有label需要当前src进行匹配
			foreach ($in_cache_last_label as $label => $v){
				if (isset($in_cache_src[$label])){
					foreach ($in_cache_src[$label] as $a => $b){
						ConstructionDlinkedListOpt::setRelJmpRange($b,$a);//$rel_jmp_range[$a] = $b;
						//$rel_jmp_range[$a]['range'] += $in_cache_last_label[$label]['range'];
						ConstructionDlinkedListOpt::increaseRelJmpRange($a,$in_cache_last_label[$label]['range']);
						//range 超过 max 返回false
						if (ConstructionDlinkedListOpt::outRelJmpRange($a)){
							return false;
						}
						//foreach ($rel_jmp_range[$a]['unit'] as $c => $d){
						ConstructionDlinkedListOpt::RelJmpRange2Pointer($a);

						foreach ($in_cache_last_label[$label]['unit'] as $c => $d){
							ConstructionDlinkedListOpt::setRelJmpRange($d,$a,'unit',$c);//$rel_jmp_range[$a]['unit'][$c] = $d;
							ConstructionDlinkedListOpt::SetRelJmpPointer($c,$a,$d);
						}
					}						
					unset($in_cache_src[$label]);
				}else{ //无法匹配，程序逻辑错误!!! 写入log
					$log = array();
					$log['in_cache_last_label'] = $in_cache_last_label;
					$log[LABEL]               = $label;
					$log['in_cache_src']        = $in_cache_src;
					GeneralFunc::internal_log_save('fail to search Label (of $in_cache_last_label) in $in_cache_src : [func reset_rel_jmp_array] ',$log);
					return false;
					//var_dump ($in_cache_last_label);
					//exit('<br><br>fuckb!!!!!!!!'.$label);				
				}
			}
		}

		if (!empty($in_cache_src)){  //仍有src未匹配到label
			$log = array();
			$log['in_cache_last_label'] = $in_cache_last_label;
			$log['in_cache_src']        = $in_cache_src;
			GeneralFunc::internal_log_save('still have $in_cache_src here till func end : [func reset_rel_jmp_array] ',$log);
			return false;
			//var_dump ($in_cache_src);
			//var_dump ($in_cache_last_label);
			//exit ('<b><font color=red><br><br>fuckC!!!!!!!!</font></b>');			
		}

		return true;
	}

	//设置 $in_cache数组 for function reset_rel_jmp_array
	//     同时清除 discard_objs 涉及单位
	//
	private static function init_in_cache_array($discard_objs,$backup_List,$c_List_start,&$c_unit,&$end_unit,&$in_cache_last_src,&$in_cache_last_label,&$in_cache_last_whole,&$in_cache_src,&$in_cache_label){

	/*
			var_dump ($discard_objs);
			var_dump ($backup_List);
			var_dump ($soul_writein_Dlinked_List);

	echo "<br>objs";
	var_dump ($discard_objs);
	echo "<br>old arrays______________________________";
	var_dump ($rel_jmp_range);
	var_dump ($rel_jmp_pointer);
	echo "<br>______________________________";

	*/
			////////////////////////////////////////
			//清除discard_objs 涉及单位
			$del_array = false;
			foreach ($discard_objs as $b){
				ConstructionDlinkedListOpt::OplenIncrease(ConstructionDlinkedListOpt::getDlinkedList($b,'len'),false);			
				$tmp = ConstructionDlinkedListOpt::ReadRelJmpPointer($b);
				if (is_array($tmp)){
					foreach ($tmp as $c => $d){
						ConstructionDlinkedListOpt::unsetRelJmpRange($c,'unit',$b);//unset ($rel_jmp_range[$c]['unit'][$b]);
						if ($d & 4){
							//$rel_jmp_range[$c]['range'] -= $soul_writein_Dlinked_List[$b]['len'];					
							ConstructionDlinkedListOpt::increaseRelJmpRange($c,ConstructionDlinkedListOpt::getDlinkedList($b,'len'),false);
						}
						if ($d !== (1 | 2 | 4)){    //rel_jmp 单位(src 或 label),留到$in_cache赋值完成后清除 $rel_jmp_range[$b]						
							$del_array[$c] = $c;
						}
					}				
					ConstructionDlinkedListOpt::UnsetRelJmpPointer($b);
				}
			}

			////////////////////////////////////////
			//起始单位 (新单位)
			$reserve_start_pointer = array();
			$reserve_last_pointer  = array();
			$tmp = reset($discard_objs);
			//echo "<br><font color=blue>reset: $tmp</font>";
			if (isset($backup_List[$tmp][P])){
				$tmp_tmp = ConstructionDlinkedListOpt::ReadRelJmpPointer($backup_List[$tmp][P]);
				if (is_array($tmp_tmp)){
					foreach ($tmp_tmp as $a => $b){
						if ($b & 2){
							$reserve_start_pointer[$a] = $b;	
						}
					}			
				}
				$c_unit = ConstructionDlinkedListOpt::getDlinkedList($backup_List[$tmp][P],N);
			}else{
				$c_unit = $c_List_start;       //从头开始,则新链表的第一单位为开始
			}
			//末尾单位
			$tmp = end($discard_objs);
			//echo "<br><font color=blue>end: $tmp</font>";
			if (isset($backup_List[$tmp][N])){
				$tmp_tmp = ConstructionDlinkedListOpt::ReadRelJmpPointer($backup_List[$tmp][N]);
				
				if (is_array($tmp_tmp)){					
					foreach ($tmp_tmp as $a => $b){
						if ($b & 1){
							$reserve_last_pointer[$a] = $b;	
						}
					}
				}
				$end_unit = $backup_List[$tmp][N];
			}else{
				$end_unit = false;               //到链表结束为止
			}

            /*
			echo "<br>vvvvvvvvvvvvvvvvvvv";
			var_dump ($reserve_start_pointer);
			var_dump ($reserve_last_pointer);
			echo "<br>vvvvvvvvvvvvvvvvvvv";
            */	   
 			//$reserve_start_pointer $reserve_last_pointer 都存在的，放入 $in_cache_last_whole
			//$reserve_start_pointer 存在，且, $obj[N]不存在       放入 $in_cache_src
			//       ..                        $obj[N]  存在       放入 $in_cache_label
			//$reserve_last_pointer  存在，且  $obj[N]不存在       放入 $in_cache_last_src
			//       ..                        $obj[N]  存在       放入 $in_cache_last_label
			$tmp = $reserve_start_pointer;
			foreach ($tmp  as $a => $b){
				if (isset($reserve_last_pointer[$a])){
					$in_cache_last_whole[$a] = $a;
					unset ($reserve_last_pointer[$a]);
					unset ($reserve_start_pointer[$a]);
				}else{
					$tmp_tmp = ConstructionDlinkedListOpt::readRelJmpRange($a,LABEL);//$tmp_tmp = $rel_jmp_range[$a][LABEL];
					if (in_array($a,$discard_objs)){
						$in_cache_label[$tmp_tmp] = ConstructionDlinkedListOpt::readRelJmpRange($a);//$rel_jmp_range[$a];
					}else{
						$in_cache_src[$tmp_tmp][$a] = ConstructionDlinkedListOpt::readRelJmpRange($a);//$rel_jmp_range[$a];
					}
				}
			}	

			foreach ($reserve_last_pointer as $a => $b){
				$tmp_tmp = ConstructionDlinkedListOpt::readRelJmpRange($a,LABEL);//$tmp_tmp = $rel_jmp_range[$a][LABEL];
				if (in_array($a,$discard_objs)){
					$in_cache_last_label[$tmp_tmp] = ConstructionDlinkedListOpt::readRelJmpRange($a);//$rel_jmp_range[$a];
				}else{
					$in_cache_last_src[$tmp_tmp][$a] = ConstructionDlinkedListOpt::readRelJmpRange($a);//$rel_jmp_range[$a];
				}
			}

			//////////////////////////////////////////////////////////////////
			//清除 src 在 obj 中单位
			if (false !== $del_array){
				foreach ($del_array as $a){
					//foreach ($rel_jmp_range[$a]['unit'] as $c => $d){
					foreach (ConstructionDlinkedListOpt::readRelJmpRange($a,'unit') as $c => $d){					
						ConstructionDlinkedListOpt::UnsetRelJmpPointer($c,$a);
					}
					ConstructionDlinkedListOpt::unsetRelJmpRange($a);//unset ($rel_jmp_range[$a]);
				}
			}
	/*
	echo "<br>new arrays______________________________";
	var_dump (ConstructionDlinkedListOpt::readRelJmpRange());
	var_dump (ConstructionDlinkedListOpt::ReadRelJmpPointer());
	echo "<br>______________________________";
	*/

			return true;        

	}

	//取得 补完 List 单位'len' 'rel_jmp'
	//返回: array['len']
	//           ['rel_jmp']['max']
	//                      [LABEL]
	//
	public static function get_addition_List_info($unit,$get_len=false,$get_rel_jmp=false){



		

		$ret = false; 


		$c_opt = ConstructionDlinkedListOpt::getCode_from_DlinkedList($unit);
		if (false === $c_opt){
			$ret = array('len' => 0);
		}else{
			
			if ($get_rel_jmp){ //获取 rel_jmp 信息
				if ((Instruction::isJmpStatic($c_opt[OPERATION])) and (0 === strpos($c_opt[PARAMS][0],UNIQUEHEAD.'SOLID_JMP_'))){ //定长跳转
					$ret['rel_jmp']['max'] = Instruction::getJmpRangeLmt($c_opt[OPERATION]);
					$ret['rel_jmp'][LABEL] = $c_opt[PARAMS][0].' : ';
				}
			}

			if ($get_len){   //获取 指令长度 信息			
				if (ConstructionDlinkedListOpt::issetRelJmpRange($unit)){     //定长跳转 (已有range)
					$c_opt['range'] = ConstructionDlinkedListOpt::readRelJmpRange($unit,'range');//$c_rel_jmp_range[$unit]['range'];		
				}
				//var_dump ($c_opt);
				$ret['len'] = OpLen::code_len($c_opt);
			}
			
		}

		return $ret;

	}

}



?>