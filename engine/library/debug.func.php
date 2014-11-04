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


		if (!empty($usable['normal_write_able'])){
			foreach ($usable['normal_write_able'] as $a => $b){
				if (isset($b[32])){ //有32位的只处理32位即可
					$result['code'][$i]['operation'] = 'MOV';
					$result['code'][$i]['params'][0] = Instruction::getRegByIdxBits(32,$a);
					$result['code'][$i]['params'][1] = $type;    				
					$result['code'][$i]['p_type'][0] = 'r';
					$result['code'][$i]['p_type'][1] = 'i';
					$result['code'][$i]['p_bits'][0] = 32;
					$result['code'][$i]['p_bits'][1] = 32;
					$i ++;			
				}else{
					foreach ($b as $c => $d){				
						$result['code'][$i]['operation'] = 'MOV';
						$result['code'][$i]['params'][0] = Instruction::getRegByIdxBits($c,$a);
						$result['code'][$i]['params'][1] = $type;    
						$result['code'][$i]['p_type'][0] = 'r';
						$result['code'][$i]['p_type'][1] = 'i';
						$result['code'][$i]['p_bits'][0] = $c;
						$result['code'][$i]['p_bits'][1] = $c;				
						$i ++;				
					}
				}
			}
		}
		//elseif (false !== $usable['stack']){
		//	$result['code'][$i]['operation'] = 'push';
		//	$result['code'][$i]['params'][0] = 'eax';
		//	$i ++;
		//	$result['code'][$i]['operation'] = 'mov';
		//	$result['code'][$i]['params'][0] = 'eax';
		//	$result['code'][$i]['params'][1] = $type;    				
		//	$i ++;
		//	$result['code'][$i]['operation'] = 'pop';
		//	$result['code'][$i]['params'][0] = 'eax';
		//	$i ++;
		//}

		
		if (is_array($usable['mem_opt_able'])){
			foreach ($usable['mem_opt_able'] as $a => $b){	
				$z = $all_valid_mem_opt_index[$b]['code'];
				$v = $all_valid_mem_opt_index[$b];
				if ($v['opt'] >= 2){
					if ($v['bits'] == 32){
						if (false === strpos($z,'_RELINFO_')){ //含重定位的内存地址，暂不处理
							$result['code'][$i]['operation'] = 'MOV';
							$result['code'][$i]['params'][0] = $z;
							$result['code'][$i]['params'][1] = $type;       
							$result['code'][$i]['p_type'][0] = 'm';
							$result['code'][$i]['p_type'][1] = 'i';
							$result['code'][$i]['p_bits'][0] = $c;
							$result['code'][$i]['p_bits'][1] = $c;	 				
							$i ++; 
						}else{
							
						}
					}
				}
			}
		}

		if (false !== $result){
			$c_meat_index = OrganMeat::append($result);//$meat_result_array[$UNIQUE_meat_index] = $result;				
			foreach ($result['code'] as $a => $b){
				if (false !== $prev){

					ConstructionDlinkedListOpt::setDlinkedList(ConstructionDlinkedListOpt::getDlinkedListIndex(),$prev,'n');				
				}else{

					ConstructionDlinkedListOpt::setListFirstUnit();

				}

				ConstructionDlinkedListOpt::setDlinkedList($prev,ConstructionDlinkedListOpt::getDlinkedListIndex(),'p');

				ConstructionDlinkedListOpt::setDlinkedList($c_meat_index,ConstructionDlinkedListOpt::getDlinkedListIndex(),'meat');

				ConstructionDlinkedListOpt::setDlinkedList($a,ConstructionDlinkedListOpt::getDlinkedListIndex(),'c');
				//
				//label 暂未考虑
				//if (isset($b['label'])){
				//
				//}

				$prev = ConstructionDlinkedListOpt::getDlinkedListIndex();
		
				ConstructionDlinkedListOpt::incDlinkedListIndex();
			} 
			if (false !== $next){

				ConstructionDlinkedListOpt::setDlinkedList(ConstructionDlinkedListOpt::getDlinkedListIndex() - 1,$next,'p');

				ConstructionDlinkedListOpt::setDlinkedList($next,ConstructionDlinkedListOpt::getDlinkedListIndex() - 1,'n');
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
			if (ConstructionDlinkedListOpt::issetDlinkedListUnit($c_lp,'n')){
				$n_lp = ConstructionDlinkedListOpt::getDlinkedList($c_lp,'n');
			}else{
				$n_lp = false;
			}

			$current = ConstructionDlinkedListOpt::getDlinkedList($c_lp);

			$c_usable = OrgansOperator::Get(SOUL,$current['c'],'usable');
			
			if (false !== $c_usable['p']){
				//echo "<br>prev $c_lp   -> $p_lp";
				self::gen_code_4_debug_usable_array($c_usable['p'],$p_lp,$c_lp,'0xaaaaaaaa');
				//exit;
			}
			if (false !== $c_usable['n']){
				//echo "<br>next $c_lp  -> $n_lp";
				self::gen_code_4_debug_usable_array($c_usable['n'],$c_lp,$n_lp,'0xbbbbbbbb');
			}


			//echo "<br>$c_lp";
			//var_dump ($current);
			//var_dump ($c_usable);

			if (false === $n_lp){
				break;
			}else{
				if (ConstructionDlinkedListOpt::issetDlinkedListUnit($n_lp,'p')){
					$p_lp = ConstructionDlinkedListOpt::getDlinkedList($n_lp,'p');
				}else{
					$p_lp = $c_lp;
				}
				$c_lp = $n_lp;
			}
		}

	}
}

?>