<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}

//主 链表 及 相关 结构 操作 类

define (MIN_REL_JMP_RANGE_RESERVE,5); //rel_jmp range 极限保留字节数 (小于此数,所有单位character.Rate清除)

class ConstructionDlinkedListOpt{
    //链表记录
    private static $_soul_writein_Dlinked_List;        //双向链表 (数组结构)
    private static $_s_w_Dlinked_List_index;           //双向链表插入位置指针
	private static $_soul_writein_Dlinked_List_start;  //双向链表首单位指针
	private static $_c_rel_jmp_range;
	private static $_c_rel_jmp_pointer;
	private static $_c_usable_oplen;

    //回滚记录
	private static $_rollback_soul_writein_Dlinked_List;
    private static $_rollback_s_w_Dlinked_List_index;
	private static $_rollback_soul_writein_Dlinked_List_start;
	private static $_rollback_c_rel_jmp_range;
	private static $_rollback_c_rel_jmp_pointer;
	private static $_rollback_c_usable_oplen;

	//记录rel.jmp.range已到极限或无限 ()
    private static $_forbid_rel_jmp;


    //搜索所有rel_jmp列表，将所有到达range限制的单位character.Rate清除
	public static function forbidMinRanges(){
	    foreach (self::$_c_rel_jmp_range as $i => $v){
			if (isset(self::$_forbid_rel_jmp[$i])){
			    continue;
			}elseif (false === $v['max']){  // no limit range => not need forbid check
			    self::$_forbid_rel_jmp[$i] = true;
			}elseif ($v['max'] <= $v['range'] + MIN_REL_JMP_RANGE_RESERVE){
				//echo "<br>$$$$$$$$$$$$$$$$$$$$$$$$$$$$$";
				//echo $v['max'].' <= '.$v['range']." + ".MIN_REL_JMP_RANGE_RESERVE;
			    self::$_forbid_rel_jmp[$i] = true;
				foreach ($v['unit'] as $u => $vv){
				    Character::removeRate($u);
				}
			}
		}
		//var_dump (self::$_forbid_rel_jmp);
	}

	//初始化 变量s
	public static function init($c_soul_writein_Dlinked_List_Total,$c_rel_jmp_range,$c_rel_jmp_pointer){
	    self::$_soul_writein_Dlinked_List = $c_soul_writein_Dlinked_List_Total['list'];	
		self::$_s_w_Dlinked_List_index    = $c_soul_writein_Dlinked_List_Total['index'];
		self::$_soul_writein_Dlinked_List_start = 0; //顺序写入双向链表 起始 位 编号 | 未被多态|混淆 ，肯定为 [0]
		self::$_c_rel_jmp_range = $c_rel_jmp_range;
		self::$_c_rel_jmp_pointer = $c_rel_jmp_pointer;
		self::$_c_usable_oplen = false;              //可用代码长度限制 剩余值 (设置为false 代表不限制代码长度)

		self::$_forbid_rel_jmp = array();
	}

	//准备回滚
	public static function ready(){
	    self::$_rollback_soul_writein_Dlinked_List       = self::$_soul_writein_Dlinked_List;	
		self::$_rollback_s_w_Dlinked_List_index          = self::$_s_w_Dlinked_List_index;
		self::$_rollback_soul_writein_Dlinked_List_start = self::$_soul_writein_Dlinked_List_start;
		self::$_rollback_c_rel_jmp_range                 = self::$_c_rel_jmp_range;
		self::$_rollback_c_rel_jmp_pointer               = self::$_c_rel_jmp_pointer;
		self::$_rollback_c_usable_oplen                  = self::$_c_usable_oplen;
	}
	//开始回滚
	public static function rollback(){
	    self::$_soul_writein_Dlinked_List       = self::$_rollback_soul_writein_Dlinked_List;	
		self::$_s_w_Dlinked_List_index          = self::$_rollback_s_w_Dlinked_List_index;
		self::$_soul_writein_Dlinked_List_start = self::$_rollback_soul_writein_Dlinked_List_start;
		self::$_c_rel_jmp_range                 = self::$_rollback_c_rel_jmp_range;
		self::$_c_rel_jmp_pointer               = self::$_rollback_c_rel_jmp_pointer;
		self::$_c_usable_oplen                  = self::$_rollback_c_usable_oplen;
	}

	/////////////////////////////////////////////////////////////////////////////////////////////
	//ready 阶段，特殊初始化
	public static function ReadyInit(){
		self::$_c_rel_jmp_range   = array();
	    self::$_c_rel_jmp_pointer = array();
	}
    //range => pointer 转换
	public static function RelJmpRange2Pointer($unit){
		foreach (self::$_c_rel_jmp_range[$unit]['unit'] as $a => $b){
		    self::$_c_rel_jmp_pointer[$a][$unit] = $b;
		}	
	}

	////////////////////////////////////////////////////////////////////////////////////////////
	//roll 准备阶段值操作
	
	//读取roll back 准备中的 jmp_range 值
	public static function ReadRollingRelJmpRange(){
	    return self::$_rollback_c_rel_jmp_range;
	}
	//读取roll back 准备中的 list 值
	public static function ReadRollingDlinkedList(){
	    return self::$_rollback_soul_writein_Dlinked_List;
	}

    //获取2单位间所有单位，按次序排列
    public static function getAmongObjs($a,$b){		
		if ($a == $b){
		    return array(1 => $a);
		}		
		$objs = self::seekNextObj($a,$b);
        if (false === $objs){
			$objs = self::seekNextObj($b,$a);
		}
        return $objs;		
	}
	private static function seekNextObj($c,$target){
		$objs = array();
		$objs[1] = $c;
		while (isset(self::$_soul_writein_Dlinked_List[$c][N])){
		    $c = self::$_soul_writein_Dlinked_List[$c][N];
			$objs[] = $c;
            if ($c == $target){
				return $objs;
			}  
		}	    
		return false;
	}

	////////////////////////////////////////////////////////////////////////////////////////////
	//
	public static function isValidID($id){
		if (isset(self::$_soul_writein_Dlinked_List[$id])){
		    if (!isset(self::$_soul_writein_Dlinked_List[$id]['302'])){
			    return true;
			}
		}
	    return false;
	}

    ////////////////////////////////////////////////////////////////////////////////////////////	
	//读取指定unit 的 $_c_rel_jmp_pointer 值
	public static function ReadRelJmpPointer($unit=false){
		if (false === $unit){
			return self::$_c_rel_jmp_pointer;    
		}
	    return self::$_c_rel_jmp_pointer[$unit];
	}
	//
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


	 ////////////////////////////////////////////////////////////////////////////////////////////	
	//读取指定unit 的 $_c_rel_jmp_range; 值
	public static function readRelJmpRange($unit=false,$key=false){
		if (false === $unit){
			return self::$_c_rel_jmp_range;    
		}
		if (false === $key){
		    return self::$_c_rel_jmp_range[$unit];    
		}
	    return self::$_c_rel_jmp_range[$unit][$key];
	}	
	//
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


    ////////////////////////////////////////////////////////////////////////////////////////////
    //可用代码长度 初始化 返回：1 不足   / 2 无可用 / 0 正常
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

    //计算可用代码长度 限制 剩余值 // 返回 true: 成功 false:失败，剩余不足
	                               // 传递 $inc_len = 0 ;可用来检测 可用代码长度 是否已不足
								   // $type == false : 数值为增加
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

	////////////////////////////////////////////////////////////////////////////////////////////
	//链条表 首单位 操作 (设置 and 读取)
	public static function readListFirstUnit(){
	    return self::$_soul_writein_Dlinked_List_start;
	}
	public static function setListFirstUnit(){
	    self::$_soul_writein_Dlinked_List_start = self::$_s_w_Dlinked_List_index;
	}


	////////////////////////////////////////////////////////////////////////////////////////////
	//双向链表 $soul_writein_Dlinked_List 及 链表指针 $s_w_Dlinked_List_index 操作函数s
    
    //链表 插入指针 位置 读取
	public static function getDlinkedListIndex(){
	    return  self::$_s_w_Dlinked_List_index;
	}
	//链表 插入指针 自增
	public static function incDlinkedListIndex(){
	    self::$_s_w_Dlinked_List_index ++;
	}

    //判断链表单位是否有效
	public static function issetDlinkedListUnit($key,$skey){
	    return (isset(self::$_soul_writein_Dlinked_List[$key][$skey]));
	}

	//双向链表 总 单位个数
	public static function numDlinkedList(){
	    return count(self::$_soul_writein_Dlinked_List);
	}
	//双向链表中随机 获取 一 单位
    public static function getRandDlinkedListUnit(){
	    return array_rand(self::$_soul_writein_Dlinked_List);
	}
	//链表单位清除
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
	//链表插入
	public static function insertDlinkedList($prev,$next){
	    self::$_soul_writein_Dlinked_List[$prev][N] = $next;
        self::$_soul_writein_Dlinked_List[$next][P] = $prev;
	}
		
	//链表单位设值
	public static function setDlinkedList(){		
		$arg = func_get_args();
		$num = func_num_args();        
		if (2 == $num){
			self::$_soul_writein_Dlinked_List[$arg[1]] = $arg[0];	
		}elseif (3 === $num){
			self::$_soul_writein_Dlinked_List[$arg[1]][$arg[2]] = $arg[0];	
		}
	}
	//链表读取
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

	////////////////////////////////////////////////////////////////////////////
	//根据 $soul_writein_Dlinked_List 链表号 获得 该单位的 前(后)usable 数组
	public static function get_usable_from_DlinkedList($id,$position){
        return OrgansOperator::GetByDListUnit(self::$_soul_writein_Dlinked_List[$id],USABLE,$position);
	}
	
	//////////////////////////////////////////////////////////////////////////
	//摘除 双向 链表 中的 指定单位 (对照 OrganBone::remove_from_DlinkedList)
	public static function remove_from_DlinkedList($c_lp){

		Character::removeRate($c_lp); //清character.Rate

		$prev = false;
		$next = false;

		if (isset(self::$_soul_writein_Dlinked_List[$c_lp][P])){
			$prev = self::$_soul_writein_Dlinked_List[$c_lp][P];
		}
		
		if (isset(self::$_soul_writein_Dlinked_List[$c_lp][N])){
			$next = self::$_soul_writein_Dlinked_List[$c_lp][N];
		}

		//unset ($copy_buff[$c_lp]);
		self::$_soul_writein_Dlinked_List[$c_lp]['302'] = $next;

		if (false !== $prev)
			unset (self::$_soul_writein_Dlinked_List[$prev][N]);
		if (false !== $next)
			unset (self::$_soul_writein_Dlinked_List[$next][P]);

		if ((false !== $prev)&&(false !== $next)){
			self::$_soul_writein_Dlinked_List[$prev][N] = $next;
			self::$_soul_writein_Dlinked_List[$next][P] = $prev;
		}elseif (false !== $next){ //if Prev == false
			self::$_soul_writein_Dlinked_List_start = $next;
		}//elseif (false !== $prev){ //if Next == false 最后一个，直接清除即可
	}
    
	//////////////////////////////////////////////////////////////////////////////
	//
	// 根据 链表号 获得指令名 (Label 返回 下一个(按方向))
	// 为 亲缘性 服务
	//
	public static function get_inst_from_DlinkedList($List_id,$direct){

		$ret = false;
		$tmp = false; 
		while (isset(self::$_soul_writein_Dlinked_List[$List_id][LABEL])){ //标签 则按方向取下一个(向上或向下)
			//echo "<br><font color=red>$List_id: Label -> $direct </font>";
			//var_dump (self::$_soul_writein_Dlinked_List[$List_id]);
			if (isset(self::$_soul_writein_Dlinked_List[$List_id][$direct])){
				$List_id = self::$_soul_writein_Dlinked_List[$List_id][$direct];
			}else{
				//echo "<br><font color=red>$List_id: Label -> empty </font>";						
				return 'empty';
			}	
		}

		$ret = OrgansOperator::GetByDListUnit(self::$_soul_writein_Dlinked_List[$List_id],CODE,OPERATION);
		
		return $ret;
	}

    //获取 链表单位 代码
	public static function getCode_from_DlinkedList($unit){

		$ret = false;
		if (isset(self::$_soul_writein_Dlinked_List[$unit][LABEL])){
		    
		}else{
            $ret = OrgansOperator::GetByDListUnit(self::$_soul_writein_Dlinked_List[$unit],CODE);
		}
		return $ret;
	}


    //根据链表编号获取单位[CODE] and [USABLE]
	//需要仅对应当前$sec的全局变量 $soul_writein_Dlinked_List / $c_Asm_Result / $c_soul_usable / $meat_result_array / $bone_result_array / $poly_result_array;
	public static function get_unit_by_soul_writein_Dlinked_List($n){

		$ret = false;
        
		$code   = OrgansOperator::GetByDListUnit(self::$_soul_writein_Dlinked_List[$n],CODE);
		if (false != $code){
			$ret[CODE] = $code;
			$ret[USABLE] = OrgansOperator::GetByDListUnit(self::$_soul_writein_Dlinked_List[$n],USABLE);
		}

		return $ret;

	}

    //判断是否为原始代码
	private static function is_soul_unit($array){

		if (isset($array[SOUL])){
			return true;
		}

		if ((!isset($array[BONE])) and (!isset($array[MEAT])) and (!isset($array[POLY]))){
			return true;
		}

		return false;
	}


}

?>