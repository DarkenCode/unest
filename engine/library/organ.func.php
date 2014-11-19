<?php

//维护，操作 Organs 产生的数据结构
class OrgansOperator{
    private static $_poly_result;
	private static $_poly_result_reverse;
    private static $_bone_result;
    private static $_meat_result;
	private static $_soul_usable;
	private static $_Asm_Result;


    //init
	public static function init($sec){
        global $StandardAsmResultArray;
        global $soul_usable;

	    self::$_poly_result = array();
		self::$_poly_result_reverse = array();
		self::$_bone_result = array();
		self::$_meat_result = array();

        self::$_Asm_Result  = $StandardAsmResultArray[$sec];       
		self::$_soul_usable = $soul_usable[$sec];
	}

    //根据 双链表 单位 返回 organ 单位
	//params:
	//         $unit:     DList' unit (array)
	//         $branch: CODE | USABLE | FAT
	//         $key: sub index  
	public static function GetByDListUnit($unit,$branch=CODE,$key=false){
	    $ret = false;
        if (isset ($unit[POLY])){
		    $ret = self::Get(POLY,$unit[POLY],$branch,$unit[C],$key);
		}elseif (isset ($unit[BONE])){
		    $ret = self::Get(BONE,$unit[BONE],$branch,$unit[C],$key);
		}elseif (isset ($unit[MEAT])){
		    $ret = self::Get(MEAT,$unit[MEAT],$branch,$unit[C],$key);
		}else{
		    $ret = self::Get(SOUL,$unit[C],$branch,false,$key);
		}		
		return $ret;	
	}

	//根据指定下标获取value
	// params:       $organ : MEAT | BONE | ...
	//               $id    : organ[?]
	//               $branch: code | usable | fat | ...
	//               $sid   : id in organ unit
	//               $key   : array index
	//
	public static function Get($organ,$id,$branch,$sid=false,$key=false,$skey=false){
	    $ret = false;
		if (MEAT === $organ){
		    if (isset(self::$_meat_result[$id][$branch][$sid])){
			    $ret = self::$_meat_result[$id][$branch][$sid];
			}
		}elseif (BONE === $organ){
		    if (isset(self::$_bone_result[$id][$branch][$sid])){
			    $ret = self::$_bone_result[$id][$branch][$sid];
			}
		}elseif (POLY === $organ){
		    if (isset(self::$_poly_result[$id][$branch][$sid])){
			    $ret = self::$_poly_result[$id][$branch][$sid];
			}
		}elseif (SOUL === $organ){
		    if (CODE == $branch){
			    $ret = self::$_Asm_Result[$id];
			}elseif (USABLE == $branch){
			    $ret = self::$_soul_usable[$id];			
			}
		}

        if ((false !== $key) and (false !== $ret)){
		    if (isset($ret[$key])){
			    $ret = $ret[$key];
			}else{
			    $ret = false;
			}
		}

		if ((false !== $skey) and (false !== $ret)){
		    if (isset($ret[$skey])){
			    $ret = $ret[$skey];
			}else{
			    $ret = false;
			}
		}

		return $ret;
	}

	//为Organ.Result 数组赋值
	public static function Set($organ,$id,$value,$extend = false){
	    if (MEAT == $organ){
			self::$_meat_result[$id] = $value;
		}elseif (BONE == $organ){
			if (false === $extend){
				self::$_bone_result[$id] = $value;
			}else{
				if (4 == count($extend)){
					self::$_bone_result[$id][$extend[0]][$extend[1]][$extend[2]][$extend[3]] = $value;
				}elseif (5 == count($extend)){
					self::$_bone_result[$id][$extend[0]][$extend[1]][$extend[2]][$extend[3]][$extend[4]] = $value;
				}
			}
		}elseif (POLY == $organ){
			self::$_poly_result[$id] = $value;
		}
	}

	public static function FilterMemUsable($unit){
		if (isset ($unit[POLY])){	
			GenerateFunc::doFilterMemUsable(self::$_poly_result[$unit[POLY]][USABLE][$unit[C]][P][MEM_OPT_ABLE]);
			GenerateFunc::doFilterMemUsable(self::$_poly_result[$unit[POLY]][USABLE][$unit[C]][N][MEM_OPT_ABLE]);
		}elseif (isset ($unit[BONE])){
		    GenerateFunc::doFilterMemUsable(self::$_bone_result[$unit[BONE]][USABLE][$unit[C]][P][MEM_OPT_ABLE]);
			GenerateFunc::doFilterMemUsable(self::$_bone_result[$unit[BONE]][USABLE][$unit[C]][N][MEM_OPT_ABLE]);	
		}elseif (isset ($unit[MEAT])){
		    GenerateFunc::doFilterMemUsable(self::$_meat_result[$unit[MEAT]][USABLE][$unit[C]][P][MEM_OPT_ABLE]);
			GenerateFunc::doFilterMemUsable(self::$_meat_result[$unit[MEAT]][USABLE][$unit[C]][N][MEM_OPT_ABLE]);
		}else{
			GenerateFunc::doFilterMemUsable(self::$_soul_usable[$unit[C]][P][MEM_OPT_ABLE]);	
		    GenerateFunc::doFilterMemUsable(self::$_soul_usable[$unit[C]][N][MEM_OPT_ABLE]);	
		}	
	}

	//打印 数组
	public static function Printer(){
	    var_dump (self::$_meat_result);
	}

	// 前(后)是否可插入脂肪(fat)
	//params:  $unit  :  DList's unit
	//         $direct:  1 prev   2 next
	//
	public static function CheckFatAble($unit,$direct = 1){
		if (isset($unit[BONE])){
		    if ($direct == self::$_bone_result[$unit[BONE]][FAT][$unit[C]]){
			    return true;
			}
		}elseif (isset($unit[POLY])){
		    if ($direct == self::$_poly_result[$unit[POLY]][FAT][$unit[C]]){
			    return true;
			}
		}
		return false;	
	}

	//poly 反向数组
	public static function SetPolyReverse($i,$k,$v){
	    self::$_poly_result_reverse[$i][$k] = $v;
	}
	public static function GetPolyReverse($i,$k){
	    return self::$_poly_result_reverse[$i][$k];
	}

	//生成 organs 处理流程 数组
	public static function GenOrganProcess($user_strength,$count,$max_strength){

			$default_max = 0;
			if (isset($user_strength['default'])){
				$default_max = intval(ceil(($count * $user_strength['default'])/100));
			}
			//
			if (!isset($user_strength[POLY]['max'])){
				$user_strength[POLY]['max'] = $default_max;			
			}
			if (!isset($user_strength[POLY]['min'])){
				$user_strength[POLY]['min'] = intval($user_strength[POLY]['max']/2);
			}elseif ($user_strength[POLY]['max'] < $user_strength[POLY]['min']){
				$user_strength[POLY]['max'] = $user_strength[POLY]['min'];
			} 
			//			
			if (!isset($user_strength[BONE]['max'])){
				$user_strength[BONE]['max'] = $default_max;			
			}
			if (!isset($user_strength[BONE]['min'])){
				$user_strength[BONE]['min'] = intval($user_strength[BONE]['max']/2);
			}elseif ($user_strength[BONE]['max'] < $user_strength[BONE]['min']){
				$user_strength[BONE]['max'] = $user_strength[BONE]['min'];
			}
			//
			if (!isset($user_strength[MEAT]['max'])){
				$user_strength[MEAT]['max'] = $default_max;			
			}
			if (!isset($user_strength[MEAT]['min'])){
				$user_strength[MEAT]['min'] = intval($user_strength[MEAT]['max']/2);
			}elseif ($user_strength[MEAT]['max'] < $user_strength[MEAT]['min']){
				$user_strength[MEAT]['max'] = $user_strength[MEAT]['min'];
			}

			$c_poly_strength = mt_rand($user_strength[POLY]['min'],$user_strength[POLY]['max']);
			$c_bone_strength = mt_rand($user_strength[BONE]['min'],$user_strength[BONE]['max']);
			$c_meat_strength = mt_rand($user_strength[MEAT]['min'],$user_strength[MEAT]['max']);

			//是否有强度超过 最大强度设置
			if (false !== $max_strength){
				if ($c_poly_strength > $max_strength){
					GeneralFunc::LogInsert('the strength number exceeds maximum of '.POLY.', ('.$c_poly_strength.' -> '.$max_strength.')',3);
					$c_poly_strength = $max_strength;
				}
				if ($c_bone_strength > $max_strength){
					GeneralFunc::LogInsert('the strength number exceeds maximum of '.BONE.', ('.$c_bone_strength.' -> '.$max_strength.')',3);
					$c_bone_strength = $max_strength;
				}
				if ($c_meat_strength > $max_strength){
					GeneralFunc::LogInsert('the strength number exceeds maximum of '.MEAT.', ('.$c_meat_strength.' -> '.$max_strength.')',3);
					$c_meat_strength = $max_strength;
				}
			}

			$process = array();	

			
			for ($i = $c_poly_strength;$i > 0;$i--){		    
				$process[] = POLY;
			}    

			for ($i = $c_bone_strength;$i > 0;$i--){		    
				$process[] = BONE;
			}
			
			for ($i = $c_meat_strength;$i > 0;$i--){		    
				$process[] = MEAT;
			}
			
			shuffle($process);

			return $process;
	}
   
}


?>