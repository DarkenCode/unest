<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}

class DebugFunc{

	//////////////////////////////////////////////////////
	//
	//生成 血肉
	//$usable : 可用范围
	//$prev   : 上一个链表 index，无则为false;
	//$next   : 下一个链表 index，无则为false;
	//$type   : 调试用 ... 特殊标记 产生特殊血肉
	private static function gen_code_4_debug_usable_array($usable,$prev,$next,$type = '0x0cccccccc'){
		
		global $all_valid_mem_opt_index;


		
		$result = false;
		$i = 0;    


		if (!empty($usable[NORMAL_WRITE_ABLE])){
			foreach ($usable[NORMAL_WRITE_ABLE] as $a => $b){
				if (isset($b[32])){ //有32位的只处理32位即可
					$result[CODE][$i][OPERATION] = 'MOV';
					$result[CODE][$i][PARAMS][0] = Instruction::getRegByIdxBits(32,$a);
					$result[CODE][$i][PARAMS][1] = $type;    				
					$result[CODE][$i][P_TYPE][0] = 'r';
					$result[CODE][$i][P_TYPE][1] = 'i';
					$result[CODE][$i][P_BITS][0] = 32;
					$result[CODE][$i][P_BITS][1] = 32;
					$i ++;			
				}else{
					foreach ($b as $c => $d){				
						$result[CODE][$i][OPERATION] = 'MOV';
						$result[CODE][$i][PARAMS][0] = Instruction::getRegByIdxBits($c,$a);
						$result[CODE][$i][PARAMS][1] = $type;    
						$result[CODE][$i][P_TYPE][0] = 'r';
						$result[CODE][$i][P_TYPE][1] = 'i';
						$result[CODE][$i][P_BITS][0] = $c;
						$result[CODE][$i][P_BITS][1] = $c;				
						$i ++;				
					}
				}
			}
		}
		//elseif (false !== $usable[STACK]){
		//	$result[CODE][$i][OPERATION] = 'push';
		//	$result[CODE][$i][PARAMS][0] = 'eax';
		//	$i ++;
		//	$result[CODE][$i][OPERATION] = 'mov';
		//	$result[CODE][$i][PARAMS][0] = 'eax';
		//	$result[CODE][$i][PARAMS][1] = $type;    				
		//	$i ++;
		//	$result[CODE][$i][OPERATION] = 'pop';
		//	$result[CODE][$i][PARAMS][0] = 'eax';
		//	$i ++;
		//}

		
		if (is_array($usable[MEM_OPT_ABLE])){
			foreach ($usable[MEM_OPT_ABLE] as $a => $b){	
				$z = $all_valid_mem_opt_index[$b][CODE];
				$v = $all_valid_mem_opt_index[$b];
				if ($v[OPT] >= 2){
					if ($v[BITS] == 32){
						if (false === strpos($z,'_RELINFO_')){ //含重定位的内存地址，暂不处理
							$result[CODE][$i][OPERATION] = 'MOV';
							$result[CODE][$i][PARAMS][0] = $z;
							$result[CODE][$i][PARAMS][1] = $type;       
							$result[CODE][$i][P_TYPE][0] = 'm';
							$result[CODE][$i][P_TYPE][1] = 'i';
							$result[CODE][$i][P_BITS][0] = $c;
							$result[CODE][$i][P_BITS][1] = $c;	 				
							$i ++; 
						}else{
							
						}
					}
				}
			}
		}

		if (false !== $result){
			$c_meat_index = OrganMeat::append($result);//$meat_result_array[$UNIQUE_meat_index] = $result;				
			foreach ($result[CODE] as $a => $b){
				if (false !== $prev){

					ConstructionDlinkedListOpt::setDlinkedList(ConstructionDlinkedListOpt::getDlinkedListIndex(),$prev,N);				
				}else{

					ConstructionDlinkedListOpt::setListFirstUnit();

				}

				ConstructionDlinkedListOpt::setDlinkedList($prev,ConstructionDlinkedListOpt::getDlinkedListIndex(),P);

				ConstructionDlinkedListOpt::setDlinkedList($c_meat_index,ConstructionDlinkedListOpt::getDlinkedListIndex(),MEAT);

				ConstructionDlinkedListOpt::setDlinkedList($a,ConstructionDlinkedListOpt::getDlinkedListIndex(),C);
				//
				//label 暂未考虑
				//if (isset($b[LABEL])){
				//
				//}

				$prev = ConstructionDlinkedListOpt::getDlinkedListIndex();
		
				ConstructionDlinkedListOpt::incDlinkedListIndex();
			} 
			if (false !== $next){

				ConstructionDlinkedListOpt::setDlinkedList(ConstructionDlinkedListOpt::getDlinkedListIndex() - 1,$next,P);

				ConstructionDlinkedListOpt::setDlinkedList($next,ConstructionDlinkedListOpt::getDlinkedListIndex() - 1,N);
			}
			//$UNIQUE_meat_index ++;
		}
		return;
	}

	//

	public static function debug_usable_array($c_lp){		


		$p_lp   = false;                            //上一个指针
		$n_lp   = false;                            //下一个指针



		while (true){
			if (ConstructionDlinkedListOpt::issetDlinkedListUnit($c_lp,N)){
				$n_lp = ConstructionDlinkedListOpt::getDlinkedList($c_lp,N);
			}else{
				$n_lp = false;
			}

			$current = ConstructionDlinkedListOpt::getDlinkedList($c_lp);

			$c_usable = OrgansOperator::Get(SOUL,$current[C],USABLE);
			
			if (false !== $c_usable[P]){
				//echo "<br>prev $c_lp   -> $p_lp";
				self::gen_code_4_debug_usable_array($c_usable[P],$p_lp,$c_lp,'0xaaaaaaaa');
				//exit;
			}
			if (false !== $c_usable[N]){
				//echo "<br>next $c_lp  -> $n_lp";
				self::gen_code_4_debug_usable_array($c_usable[N],$c_lp,$n_lp,'0xbbbbbbbb');
			}


			//echo "<br>$c_lp";
			//var_dump ($current);
			//var_dump ($c_usable);

			if (false === $n_lp){
				break;
			}else{
				if (ConstructionDlinkedListOpt::issetDlinkedListUnit($n_lp,P)){
					$p_lp = ConstructionDlinkedListOpt::getDlinkedList($n_lp,P);
				}else{
					$p_lp = $c_lp;
				}
				$c_lp = $n_lp;
			}
		}

	}
}

?>