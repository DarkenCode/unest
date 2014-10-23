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
	//         $branch: 'code' | 'usable' | 'fat'
	//         $key: sub index  
	public static function GetByDListUnit($unit,$branch='code',$key=false){
	    $ret = false;
        if (isset ($unit['poly'])){
		    $ret = self::Get(POLY,$unit['poly'],$branch,$unit['c'],$key);
		}elseif (isset ($unit['bone'])){
		    $ret = self::Get(BONE,$unit['bone'],$branch,$unit['c'],$key);
		}elseif (isset ($unit['meat'])){
		    $ret = self::Get(MEAT,$unit['meat'],$branch,$unit['c'],$key);
		}else{
		    $ret = self::Get(SOUL,$unit['c'],$branch,false,$key);
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
		    if ('code' == $branch){
			    $ret = self::$_Asm_Result[$id];
			}elseif ('usable' == $branch){
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

	//按正则清除 可用mem表
	public static function doFilterMemUsable(&$usable_mem,$filter){
	    global $all_valid_mem_opt_index;
		if (is_array($usable_mem)){
			$tmp = $usable_mem;
			foreach ($tmp as $i => $a){		
				if (preg_match('/'."$filter".'/',$all_valid_mem_opt_index[$a]['code'])){
					unset ($usable_mem[$i]);				
				}			
			}
		}
	}

	public static function FilterMemUsable($unit,$filter){
		if (isset ($unit['poly'])){	
			self::doFilterMemUsable(self::$_poly_result[$unit['poly']]['usable'][$unit['c']]['p']['mem_opt_able'],$filter);
			self::doFilterMemUsable(self::$_poly_result[$unit['poly']]['usable'][$unit['c']]['n']['mem_opt_able'],$filter);
		}elseif (isset ($unit['bone'])){
		    self::doFilterMemUsable(self::$_bone_result[$unit['bone']]['usable'][$unit['c']]['p']['mem_opt_able'],$filter);
			self::doFilterMemUsable(self::$_bone_result[$unit['bone']]['usable'][$unit['c']]['n']['mem_opt_able'],$filter);	
		}elseif (isset ($unit['meat'])){
		    self::doFilterMemUsable(self::$_meat_result[$unit['meat']]['usable'][$unit['c']]['p']['mem_opt_able'],$filter);
			self::doFilterMemUsable(self::$_meat_result[$unit['meat']]['usable'][$unit['c']]['n']['mem_opt_able'],$filter);
		}else{
			self::doFilterMemUsable(self::$_soul_usable[$unit['c']]['p']['mem_opt_able'],$filter);	
		    self::doFilterMemUsable(self::$_soul_usable[$unit['c']]['n']['mem_opt_able'],$filter);	
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
		if (isset($unit['bone'])){
		    if ($direct == self::$_bone_result[$unit['bone']]['fat'][$unit['c']]){
			    return true;
			}
		}elseif (isset($unit['poly'])){
		    if ($direct == self::$_poly_result[$unit['poly']]['fat'][$unit['c']]){
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
			if (!isset($user_strength['poly']['max'])){
				$user_strength['poly']['max'] = $default_max;			
			}
			if (!isset($user_strength['poly']['min'])){
				$user_strength['poly']['min'] = intval($user_strength['poly']['max']/2);
			}elseif ($user_strength['poly']['max'] < $user_strength['poly']['min']){
				$user_strength['poly']['max'] = $user_strength['poly']['min'];
			} 
			//			
			if (!isset($user_strength['bone']['max'])){
				$user_strength['bone']['max'] = $default_max;			
			}
			if (!isset($user_strength['bone']['min'])){
				$user_strength['bone']['min'] = intval($user_strength['bone']['max']/2);
			}elseif ($user_strength['bone']['max'] < $user_strength['bone']['min']){
				$user_strength['bone']['max'] = $user_strength['bone']['min'];
			}
			//
			if (!isset($user_strength['meat']['max'])){
				$user_strength['meat']['max'] = $default_max;			
			}
			if (!isset($user_strength['meat']['min'])){
				$user_strength['meat']['min'] = intval($user_strength['meat']['max']/2);
			}elseif ($user_strength['meat']['max'] < $user_strength['meat']['min']){
				$user_strength['meat']['max'] = $user_strength['meat']['min'];
			}

			$c_poly_strength = mt_rand($user_strength['poly']['min'],$user_strength['poly']['max']);
			$c_bone_strength = mt_rand($user_strength['bone']['min'],$user_strength['bone']['max']);
			$c_meat_strength = mt_rand($user_strength['meat']['min'],$user_strength['meat']['max']);

			//是否有强度超过 最大强度设置
			if (false !== $max_strength){
				if ($c_poly_strength > $max_strength){
					GeneralFunc::LogInsert('the strength number exceeds maximum of '.'poly'.', ('.$c_poly_strength.' -> '.$max_strength.')',3);
					$c_poly_strength = $max_strength;
				}
				if ($c_bone_strength > $max_strength){
					GeneralFunc::LogInsert('the strength number exceeds maximum of '.'bone'.', ('.$c_bone_strength.' -> '.$max_strength.')',3);
					$c_bone_strength = $max_strength;
				}
				if ($c_meat_strength > $max_strength){
					GeneralFunc::LogInsert('the strength number exceeds maximum of '.'meat'.', ('.$c_meat_strength.' -> '.$max_strength.')',3);
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