<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}



class ConstructionDlinkedListOpt{
    
    private static $_soul_writein_Dlinked_List;        
    private static $_s_w_Dlinked_List_index;           
	private static $_soul_writein_Dlinked_List_start;  
	private static $_c_rel_jmp_range;
	private static $_c_rel_jmp_pointer;
	private static $_c_usable_oplen;

    
	private static $_rollback_soul_writein_Dlinked_List;
    private static $_rollback_s_w_Dlinked_List_index;
	private static $_rollback_soul_writein_Dlinked_List_start;
	private static $_rollback_c_rel_jmp_range;
	private static $_rollback_c_rel_jmp_pointer;
	private static $_rollback_c_usable_oplen;
    
	
	public static function init($c_soul_writein_Dlinked_List_Total,$c_rel_jmp_range,$c_rel_jmp_pointer){
	    self::$_soul_writein_Dlinked_List = $c_soul_writein_Dlinked_List_Total['list'];	
		self::$_s_w_Dlinked_List_index = $c_soul_writein_Dlinked_List_Total['index'];
		self::$_soul_writein_Dlinked_List_start = 0; 
		self::$_c_rel_jmp_range = $c_rel_jmp_range;
		self::$_c_rel_jmp_pointer = $c_rel_jmp_pointer;
		self::$_c_usable_oplen = false;              
	}

	
	public static function ready(){
	    self::$_rollback_soul_writein_Dlinked_List       = self::$_soul_writein_Dlinked_List;	
		self::$_rollback_s_w_Dlinked_List_index          = self::$_s_w_Dlinked_List_index;
		self::$_rollback_soul_writein_Dlinked_List_start = self::$_soul_writein_Dlinked_List_start;
		self::$_rollback_c_rel_jmp_range                 = self::$_c_rel_jmp_range;
		self::$_rollback_c_rel_jmp_pointer               = self::$_c_rel_jmp_pointer;
		self::$_rollback_c_usable_oplen                  = self::$_c_usable_oplen;
	}
	
	public static function rollback(){
	    self::$_soul_writein_Dlinked_List       = self::$_rollback_soul_writein_Dlinked_List;	
		self::$_s_w_Dlinked_List_index          = self::$_rollback_s_w_Dlinked_List_index;
		self::$_soul_writein_Dlinked_List_start = self::$_rollback_soul_writein_Dlinked_List_start;
		self::$_c_rel_jmp_range                 = self::$_rollback_c_rel_jmp_range;
		self::$_c_rel_jmp_pointer               = self::$_rollback_c_rel_jmp_pointer;
		self::$_c_usable_oplen                  = self::$_rollback_c_usable_oplen;
	}

	
	
	public static function ReadyInit(){
		self::$_c_rel_jmp_range   = array();
	    self::$_c_rel_jmp_pointer = array();
	}
    
	public static function RelJmpRange2Pointer($unit){
		foreach (self::$_c_rel_jmp_range[$unit]['unit'] as $a => $b){
		    self::$_c_rel_jmp_pointer[$a][$unit] = $b;
		}	
	}

	
	
	
	
	public static function ReadRollingRelJmpRange(){
	    return self::$_rollback_c_rel_jmp_range;
	}
	
	public static function ReadRollingDlinkedList(){
	    return self::$_rollback_soul_writein_Dlinked_List;
	}

    
	
	public static function ReadRelJmpPointer($unit=false){
		if (false === $unit){
			return self::$_c_rel_jmp_pointer;    
		}
	    return self::$_c_rel_jmp_pointer[$unit];
	}
	
	public static function SetRelJmpPointer($unit,$key,$value){
	    self::$_c_rel_jmp_pointer[$unit][$key] = $value;
		return;
	}
	public static function UnsetRelJmpPointer($unit,$key=false){
		if (false === $key){
			unset (self::$_c_rel_jmp_pointer[$unit]);
		}else{
			unset (self::$_c_rel_jmp_pointer[$unit][$key]);
		}
		return;
	}
	public static function issetRelJmpPointer($unit){
	    return isset(self::$_c_rel_jmp_pointer[$unit]);
	}

	
	
	
	
	
	private static function filter_fill_rel_jmp(&$ret,$filter=15){

		
		$forbid_unit = false;
		
		
		if (is_array(self::$_c_rel_jmp_range)){
			foreach (self::$_c_rel_jmp_range as $a => $b){
				if (false !== $b['max']){
					if ($b['max'] - $b['range'] < $filter){
						$forbid_unit[$a] = $a;
					}
				}
			}
			if ($forbid_unit){
				
				
				
				
				$usable_objs = false;
				$n = 0;
				$i = 1;
				foreach ($ret as $a => $b){
					
					
					
					if (isset (self::$_c_rel_jmp_pointer[$b])){
						foreach (self::$_c_rel_jmp_pointer[$b] as $c => $d){
							if (isset($forbid_unit[$c])){
								 $n ++;
								 $i = 0;
								 break;
							}
						}
					}
					if ($i){
						$usable_objs[$n][$i] = $b; 
					}
					$i ++;			
				}
				
				
				$ret = array();
				if ($usable_objs){			
					foreach ($usable_objs as $a => $b){
						if (count($b) > count($ret)){
							$ret = $b;
						}
					}
				}
				
				
				   
				
			}
		

		
		
		
		
		
		

		}

		return;
	}

	 
	
	public static function readRelJmpRange($unit=false,$key=false){
		if (false === $unit){
			return self::$_c_rel_jmp_range;    
		}
		if (false === $key){
		    return self::$_c_rel_jmp_range[$unit];    
		}
	    return self::$_c_rel_jmp_range[$unit][$key];
	}	
	
	public static function setRelJmpRange($value,$unit,$key=false,$skey=false){
		if (false !== $skey){
		    self::$_c_rel_jmp_range[$unit][$key][$skey] = $value;
		}elseif (false !== $key){
			self::$_c_rel_jmp_range[$unit][$key]        = $value;
		}else{
			self::$_c_rel_jmp_range[$unit]              = $value;
		}
	}
	public static function unsetRelJmpRange($unit,$key=false,$skey=false){
		if (false === $key){
			unset (self::$_c_rel_jmp_range[$unit]);
		}elseif (false === $skey){
			unset (self::$_c_rel_jmp_range[$unit][$key]);
		}else{
			unset (self::$_c_rel_jmp_range[$unit][$key][$skey]);
		}
		return;
	}	
	public static function increaseRelJmpRange($unit,$inc_value,$df=true){
		if ($df)
			self::$_c_rel_jmp_range[$unit]['range'] += $inc_value;
		else
			self::$_c_rel_jmp_range[$unit]['range'] -= $inc_value;
	}
    public static function issetRelJmpRange($unit,$key = 'range'){
	    return isset(self::$_c_rel_jmp_range[$unit][$key]);
	}
	public static function outRelJmpRange($unit){
        if (false !== self::$_c_rel_jmp_range[$unit]['max']){
			if (self::$_c_rel_jmp_range[$unit]['max'] < self::$_c_rel_jmp_range[$unit]['range']){
				return true;
			}
		}
		return false;
	}


    
    
	public static function OplenInit($oplen){
		if ($oplen > 0){
		    self::$_c_usable_oplen = $oplen;
		}elseif ($oplen < 0){
		    self::$_c_usable_oplen = 0;
			return 1;
		}else{
		    self::$_c_usable_oplen = 0;		
			return 2;
		}
		return 0;
	}

    
	                               
								   
	public static function OplenIncrease($inc_len,$type=true){	
		if (false === self::$_c_usable_oplen){
		    return true;
		}
		if ($type){
			self::$_c_usable_oplen -= $inc_len;
		}else{
			self::$_c_usable_oplen += $inc_len;
		}
		if (self::$_c_usable_oplen >= 0){
			return true;
		}		
		return false;		
	}

	
	
	public static function readListFirstUnit(){
	    return self::$_soul_writein_Dlinked_List_start;
	}
	public static function setListFirstUnit(){
	    self::$_soul_writein_Dlinked_List_start = self::$_s_w_Dlinked_List_index;
	}


	
	
    
    
	public static function getDlinkedListIndex(){
	    return  self::$_s_w_Dlinked_List_index;
	}
	
	public static function incDlinkedListIndex(){
	    self::$_s_w_Dlinked_List_index ++;
	}

    
	public static function issetDlinkedListUnit($key,$skey){
	    return (isset(self::$_soul_writein_Dlinked_List[$key][$skey]));
	}

	
	public static function numDlinkedList(){
	    return count(self::$_soul_writein_Dlinked_List);
	}
	
    public static function getRandDlinkedListUnit(){
	    return array_rand(self::$_soul_writein_Dlinked_List);
	}
	
    public static function unsetDlinkedList($key = false){
	    if (false !== $key){
			unset (self::$_soul_writein_Dlinked_List[self::$_s_w_Dlinked_List_index][$key]);
		}else{
			unset (self::$_soul_writein_Dlinked_List[self::$_s_w_Dlinked_List_index]);
		}
	}

    public static function insertDlinkedListByIndex($prev){
	    self::insertDlinkedList($prev,self::$_s_w_Dlinked_List_index);
	}
	
	public static function insertDlinkedList($prev,$next){
	    self::$_soul_writein_Dlinked_List[$prev]['n'] = $next;
        self::$_soul_writein_Dlinked_List[$next]['p'] = $prev;
	}
		
	
	public static function setDlinkedList(){		
		$arg = func_get_args();
		$num = func_num_args();        
		if (2 == $num){
			self::$_soul_writein_Dlinked_List[$arg[1]] = $arg[0];	
		}elseif (3 === $num){
			self::$_soul_writein_Dlinked_List[$arg[1]][$arg[2]] = $arg[0];	
		}
	}
	
	public static function getDlinkedListTotal(){
	    return self::$_soul_writein_Dlinked_List;
	}

    public static function getDlinkedList(){
        $arg = func_get_args();
		$num = func_num_args();
		if (1 == $num){
		    return self::$_soul_writein_Dlinked_List[$arg[0]];
		}elseif (2 == $num){
		    return self::$_soul_writein_Dlinked_List[$arg[0]][$arg[1]];
		}elseif (3 == $num){
		    return self::$_soul_writein_Dlinked_List[$arg[0]][$arg[1]][$arg[2]];
		}
	}

	
	
	public static function get_usable_from_DlinkedList($id,$position){
		global $c_soul_usable;
		global $poly_result_array;
		global $bone_result_array;
		global $meat_result_array;

		$asm_id = self::$_soul_writein_Dlinked_List[$id]['c'];
		if (isset(self::$_soul_writein_Dlinked_List[$id]['poly'])){      
			return $poly_result_array[self::$_soul_writein_Dlinked_List[$id]['poly']]['usable'][$asm_id][$position];	   
		}elseif (isset(self::$_soul_writein_Dlinked_List[$id]['bone'])){ 
			return $bone_result_array[self::$_soul_writein_Dlinked_List[$id]['bone']]['usable'][$asm_id][$position];
		}elseif (isset(self::$_soul_writein_Dlinked_List[$id]['meat'])){ 
			return $meat_result_array[self::$_soul_writein_Dlinked_List[$id]['meat']]['usable'][$asm_id][$position];
		}else{                                                    
			return $c_soul_usable[$asm_id][$position];
		}
		return $id;
	}
	
	
	
	public static function remove_from_DlinkedList($c_lp,&$lp_first,&$lp_last,&$copy_buff = false){
		$prev = false;
		$next = false;

		$isDefaultList = false;

		if (false === $copy_buff){
		    $copy_buff = self::$_soul_writein_Dlinked_List;		
		    $isDefaultList = true;
		}

		if (isset($copy_buff[$c_lp]['p'])){
			$prev = $copy_buff[$c_lp]['p'];
		}else{ 
			$lp_first = false;		
		}
		
		if (isset($copy_buff[$c_lp]['n'])){
			$next = $copy_buff[$c_lp]['n'];
		}else{ 
			$lp_last = false;		
		}
		
		$copy_buff[$c_lp]['302'] = $next;
		if (false !== $prev)
			unset ($copy_buff[$prev]['n']);
		if (false !== $next)
			unset ($copy_buff[$next]['p']);

		if ((false !== $prev)&&(false !== $next)){
			$copy_buff[$prev]['n'] = $next;
			$copy_buff[$next]['p'] = $prev;
		}elseif (false !== $next){ 
			$lp_first = $next;
		}elseif (false !== $prev){ 
			$lp_last = $prev;
		}

		if ($isDefaultList){
            self::$_soul_writein_Dlinked_List = $copy_buff;		
		}
	}
		
	
	
	
	
	
	public static function get_inst_from_DlinkedList($List_id,$direct){
		
		global $meat_result_array;
		global $bone_result_array;
		global $poly_result_array;
		global $c_Asm_Result;

		$ret = false;
		$tmp = false; 
		while (isset(self::$_soul_writein_Dlinked_List[$List_id]['label'])){ 
			
			
			if (isset(self::$_soul_writein_Dlinked_List[$List_id][$direct])){
				$List_id = self::$_soul_writein_Dlinked_List[$List_id][$direct];
			}else{
				
				return 'empty';
			}	
		}
		if (isset(self::$_soul_writein_Dlinked_List[$List_id]['poly'])){
			if (isset($poly_result_array[self::$_soul_writein_Dlinked_List[$List_id]['poly']]['code'][self::$_soul_writein_Dlinked_List[$List_id]['c']]['operation'])){
				$ret = $poly_result_array[self::$_soul_writein_Dlinked_List[$List_id]['poly']]['code'][self::$_soul_writein_Dlinked_List[$List_id]['c']]['operation'];
			}
			
		}elseif (isset(self::$_soul_writein_Dlinked_List[$List_id]['bone'])){
			if (isset($bone_result_array[self::$_soul_writein_Dlinked_List[$List_id]['bone']]['code'][self::$_soul_writein_Dlinked_List[$List_id]['c']]['operation'])){
				$ret = $bone_result_array[self::$_soul_writein_Dlinked_List[$List_id]['bone']]['code'][self::$_soul_writein_Dlinked_List[$List_id]['c']]['operation'];
			}
			
		}elseif (isset(self::$_soul_writein_Dlinked_List[$List_id]['meat'])){
			if (isset($meat_result_array[self::$_soul_writein_Dlinked_List[$List_id]['meat']]['code'][self::$_soul_writein_Dlinked_List[$List_id]['c']]['operation'])){
				$ret = $meat_result_array[self::$_soul_writein_Dlinked_List[$List_id]['meat']]['code'][self::$_soul_writein_Dlinked_List[$List_id]['c']]['operation'];
			}
			
		}elseif (isset($c_Asm_Result[self::$_soul_writein_Dlinked_List[$List_id]['c']])){	
			if (isset($c_Asm_Result[self::$_soul_writein_Dlinked_List[$List_id]['c']]['operation'])){
				$ret = $c_Asm_Result[self::$_soul_writein_Dlinked_List[$List_id]['c']]['operation'];
			}
			
		}

		
		return $ret;
	}

    
	public static function getCode_from_DlinkedList($unit){
		global $poly_result_array;
		global $bone_result_array;
		global $meat_result_array;
		global $c_Asm_Result;

		$ret = false;
		if (isset(self::$_soul_writein_Dlinked_List[$unit]['label'])){
		    
		}else{
			if (isset(self::$_soul_writein_Dlinked_List[$unit]['poly'])){
				$ret = $poly_result_array[self::$_soul_writein_Dlinked_List[$unit]['poly']]['code'][self::$_soul_writein_Dlinked_List[$unit]['c']];	
			}elseif (isset(self::$_soul_writein_Dlinked_List[$unit]['bone'])){
				$ret = $bone_result_array[self::$_soul_writein_Dlinked_List[$unit]['bone']]['code'][self::$_soul_writein_Dlinked_List[$unit]['c']];
			}elseif (isset(self::$_soul_writein_Dlinked_List[$unit]['meat'])){
				$ret = $meat_result_array[self::$_soul_writein_Dlinked_List[$unit]['meat']]['code'][self::$_soul_writein_Dlinked_List[$unit]['c']];
			}elseif (isset($c_Asm_Result[self::$_soul_writein_Dlinked_List[$unit]['c']])){
				$ret = $c_Asm_Result[self::$_soul_writein_Dlinked_List[$unit]['c']];
			}
		}
		return $ret;
	}


    
	
	public static function get_unit_by_soul_writein_Dlinked_List($n){
		global $c_Asm_Result;
		global $c_soul_usable;
		global $meat_result_array;
		global $bone_result_array;
		global $poly_result_array;

		$ret = false;

		if (isset(self::$_soul_writein_Dlinked_List[$n]['poly'])){
			$ret['code']   = $poly_result_array[self::$_soul_writein_Dlinked_List[$n]['poly']]['code'][self::$_soul_writein_Dlinked_List[$n]['c']];
			$ret['usable'] = $poly_result_array[self::$_soul_writein_Dlinked_List[$n]['poly']]['usable'][self::$_soul_writein_Dlinked_List[$n]['c']];
		}elseif (isset(self::$_soul_writein_Dlinked_List[$n]['bone'])){
			$ret['code']   = $bone_result_array[self::$_soul_writein_Dlinked_List[$n]['bone']]['code'][self::$_soul_writein_Dlinked_List[$n]['c']];
			$ret['usable'] = $bone_result_array[self::$_soul_writein_Dlinked_List[$n]['bone']]['usable'][self::$_soul_writein_Dlinked_List[$n]['c']];
		}elseif (isset(self::$_soul_writein_Dlinked_List[$n]['meat'])){
			$ret['code']   = $meat_result_array[self::$_soul_writein_Dlinked_List[$n]['meat']]['code'][self::$_soul_writein_Dlinked_List[$n]['c']];
			$ret['usable'] = $meat_result_array[self::$_soul_writein_Dlinked_List[$n]['meat']]['usable'][self::$_soul_writein_Dlinked_List[$n]['c']];	
		}else{		
			if (isset($c_Asm_Result[self::$_soul_writein_Dlinked_List[$n]['c']])){
				$ret['code']   = $c_Asm_Result[self::$_soul_writein_Dlinked_List[$n]['c']];
				$ret['usable'] = $c_soul_usable[self::$_soul_writein_Dlinked_List[$n]['c']];
			}
		}
		return $ret;

	}

    
	private static function is_soul_unit($array){

		if (isset($array['soul'])){
			return true;
		}

		if ((!isset($array['bone'])) and (!isset($array['meat'])) and (!isset($array['poly']))){
			return true;
		}

		return false;
	}
	
	
	
	
	public static function collect_obj_from_DlinkedList($insert_pointer,$number){

		$ret = false;

		$current = $insert_pointer;

		while (isset(self::$_soul_writein_Dlinked_List[$current]['302'])){ 
			$current =  self::$_soul_writein_Dlinked_List[$current]['302'];		
		}

		
		
		global $c_MeatMutation;
		$soulfocus = GenerateFunc::my_rand($c_MeatMutation);
		if (($soulfocus) and (false === self::is_soul_unit(self::$_soul_writein_Dlinked_List[$current]))){

			$p = $current;
			$n = $current;	
	
	
			while (true){
				
				if (false !== $p){
					if (isset(self::$_soul_writein_Dlinked_List[$p]['p'])){
						$p = self::$_soul_writein_Dlinked_List[$p]['p'];
						
						
						
						if (self::is_soul_unit(self::$_soul_writein_Dlinked_List[$p])){
							$current = $p;
							break;
						}
					}else{
						$p = false;
					}
				}

				if (false !== $n){
					if (isset(self::$_soul_writein_Dlinked_List[$n]['n'])){
						$n = self::$_soul_writein_Dlinked_List[$n]['n'];
						
						
						
						if (self::is_soul_unit(self::$_soul_writein_Dlinked_List[$n])){
							$current = $n;
							break;
						}
					}else{
						$n = false;
					}
				}

				if ((false === $p) and (false === $n)){ 
					break;
				}
			}	
		}
		
	
	

		

		$current_forward  = $current;
		$current_backward = $current;
		$have_forward  = true;
		$have_backward = true;
		
		$prev_obj = array();
		$next_obj = array();

		while ($number > 0){		
			$meat_generated = 0;
			
			if (true === $have_forward){			
				if (isset(self::$_soul_writein_Dlinked_List[$current_forward]['p'])){
					$current_forward = self::$_soul_writein_Dlinked_List[$current_forward]['p'];
					$prev_obj[] = $current_forward;
					$number --;
				}else{				
					if (false === $have_backward){
						break;
					}
					$have_forward = false;
				}
			}
			if (0 == $number){
				break;
			}

			if (true === $have_backward){
				if (isset(self::$_soul_writein_Dlinked_List[$current_backward]['n'])){
					$current_backward = self::$_soul_writein_Dlinked_List[$current_backward]['n'];
					$next_obj[] = $current_backward;
					$number --;
				}else{
					if (false === $have_forward){
						break;
					}
					$have_backward = false;
				}
			}		
		}    
		
		$i = 1; 
		if (count($prev_obj) > 0){
			$prev_obj = array_reverse($prev_obj);
			foreach ($prev_obj as $a){
				$ret[$i] = $a;
				$i ++;
			}		
		}
		$ret[$i] = $current;
		$i ++;
		if (count($next_obj) > 0){
			foreach ($next_obj as $a){
				$ret[$i] = $a;
				$i++;
			}
		}
		
		
		self::filter_fill_rel_jmp($ret,15);
		
		return $ret;
	}


}

?>