<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}


class OrganFat{


	//输入 最大容许的 脂肪 字节数
	public static function start($max_size,$enter_flag,$unit,$direct){		


		$fat_size_array = array(  //脂肪字节长度 可选范围
			0,0,0,1,1,1,2,2,2,3,3,3,4,4,4,5,5,5,6,7,8,9,10,
		);
		$ret = '';
		
		$fat_size = array_rand($fat_size_array);

		$fat_size = $fat_size_array[$fat_size];

		if (0 == $fat_size){
			return '';
		}

		if (!ConstructionDlinkedListOpt::OplenIncrease($fat_size)){
			return '';
		}

		$effected_rel_jmp_array = false;
		
		$tmp = ConstructionDlinkedListOpt::ReadRelJmpPointer($unit);
		if (is_array($tmp)){
			foreach ($tmp as $a => $b){
				if ($b & $direct){
					$effected_rel_jmp_array[$a] = $a;
					//简单起见，会导致 rel.jmp oplen改变的变化都被放弃，即 原'range'<= 127,增加脂肪后，大于127
					$c_range = ConstructionDlinkedListOpt::readRelJmpRange($a,'range');
					if (($c_range <= 127) and (($c_range + $fat_size) > 127)){
					//if (($c_rel_jmp_range[$a]['max'] - $c_rel_jmp_range[$a]['range']) < $fat_size){ //有限 定长跳转 不够 写入脂肪，放弃...
						echo "<br> give up fat overflow  $a :".$c_rel_jmp_range[$a]['range'].' + '.$fat_size.' changed the range bits!';
						ConstructionDlinkedListOpt::OplenIncrease($fat_size,false);
						return '';
					}
				}
			}
		}

		if ($fat_size){
			if ($effected_rel_jmp_array){ //有限定长跳转 ['range'] - 脂肪size		
				foreach ($effected_rel_jmp_array as $a){
					ConstructionDlinkedListOpt::increaseRelJmpRange($a,$fat_size);//$c_rel_jmp_range[$a]['range'] += $fat_size;  
				}
			}
			$ret = 'db ';
			for ($i = 0;$i < $fat_size;$i++){
				$ret .= mt_rand (0,255);
				$ret .= ',';
			}
			$ret .= $enter_flag;
			
		}

		return $ret;
	}


}



?>