<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}

///////////////////////////////////////////////
//
//捕获退出(输出log日志)
//
function shutdown_except(){
    global $complete_finished;

	global $exetime_record;
    if ((!$complete_finished)&&(!GeneralFunc::LogHasErr())){
		GeneralFunc::LogInsert('unexpected shutdown, maximum execution time exceeded or other errors');
	}
	$output = GeneralFunc::LogRead();
    //输出$output[] 到日志文件,jason格式
	file_put_contents(CfgParser::params('log'),json_encode($output));  

	var_dump ($output);
	var_dump ($exetime_record);
	echo "<br>memory_get_usage: ";
    var_dump (memory_get_usage());
}


/******************************************/
//通用 函数 集

class GeneralFunc{
    //
	// 日志记录 操作函数s
	private static $_error   = array();
	private static $_warning = array();
	private static $_notice  = array();

	//写记录日志 $type 1: error  2:warning  3:notice
	public static function LogInsert($log,$type=1){
		if (1 === $type){
		    self::$_error[] = $log;   
		}elseif (2 === $type){
		    self::$_warning[] = $log;   
		}else{
		    self::$_notice[]  = $log;   
		}	    
	}
	public static function LogRead(){
	    $ret['error']   = self::$_error;
	    $ret['warning'] = self::$_warning;
	    $ret['notice']  = self::$_notice;
		return $ret;
	}
	public static function LogHasErr(){
	    if (empty(self::$_error)){
		    return false;
		}
		return true;
	}


	/////////////////////////////////////////////////////
	//统计运行时间
	private static $_stime = 0;
	public static function exetime_record(){
		/*       记录函数运行时间              */
		$etime=microtime(true);//获取程序执行结束的时间  
		$total=$etime - self::$_stime;   //计算差值  
		$str_total = var_export($total, TRUE);  
		if(substr_count($str_total,"E")){  
			$float_total = floatval(substr($str_total,5));  
			$total = $float_total/100000;  				
		}
		self::$_stime = microtime(true); //获取程序开始执行的时间
		return $total;
		/**************************************/
	}    

	///////////////////////////////////////////////////
	//根据usable前后stack确定指令的stack环境(可用or 不可用)
	public static function soul_stack_set(&$code,$usable){
		foreach ($code as $a => $b){
			if ((true !== $usable[$a][P][STACK]) or (true !== $usable[$a][N][STACK])){
				$code[$a][STACK] = false;
			}else{
				$code[$a][STACK] = true;
			}		
		}
	}

	///////////////////////////////////////////////
	//
	//获取文件行数(失败返回false,成功返回行数)
	//
	// 注：超长汇编指令(换行) 未考虑
	//
	public static function get_file_line($filename){
		$line = 0;
		@$fp = fopen($filename , 'r');  
		if($fp){  
			//获取文件的一行内容，注意：需要php5才支持该函数；  
			while(stream_get_line($fp,8192,"\n")){  
				$line++;  
			}
			fclose($fp);//关闭文件  
			return $line;
		}
		return false;
	}

	
	///////////////////////////////////////////////
	//内部错误 日志 保存(保存到文件 or 发送到邮件)

	public static function internal_log_save($title,$contents=false){

		$log_path = dirname(__FILE__)."/../../log/ENGIN_VER/";

		if (!is_dir($log_path)){
			if (!mkdir($log_path)){
				error_log("fail to mkdir: $log_path",1,"1094566308@qq.com","From: internal_fail@unest.org");
				return false;
			}
		}

		$log_file = $log_path."log.txt";
		
		if(!flock($fp=fopen($log_file,'a+'), LOCK_NB | LOCK_EX)){//无法取得锁就退出
			return false;	
		}
		
		$header  = "\r\n\r\n";
		$header .= date("Y-m-d H:i:s",time());
		$header .= "\r\n";
		$header .= "[".$log_path."]";
		$header .= "\r\nTitle:".$title; 	
		if (false !== $contents){
			$header .= "\r\n";
			if (is_array($contents)){
				$header .= "===array start===\r\n";
				$header .= serialize($contents);
				$header .= "\r\n===array end===";
			}else{
				$header .= $contents;
			}
		}
		$header .= "\r\n --------- end ---------\r\n";


		fseek($fp,23);
		fwrite ($fp,$header,strlen($header));
		
		flock($fp,LOCK_UN);
		fclose($fp);
		
		return true;
	}


	//////////////////////////////////////////////
	//
	//识别 目标指令是否需要ipsp保护
	//
	public static function is_effect_ipsp($asm,$rule = 1,$sp_define = false){


		global $stack_pointer_reg;
	

		
		if (Instruction::isJmp($asm[OPERATION])){ //绝对 或 相对 跳转
			return true;
		}

		$opt = Instruction::getInstructionOpt($asm[OPERATION],count($asm[P_TYPE]));

	




		if (isset($opt[STACK])){
			return true;
		}
		
		if (is_array($asm[PARAMS])){ //参数，寄存器SP 或 ESP ，读或写 操作	
			foreach ($asm[PARAMS] as $a => $b){
				if ('i' !== $asm[P_TYPE][$a]){
					if ((0 === $rule) && ($opt[$a] <= 1)){
						continue;
					}
					if ($opt[$a] < 1){ // lea
						continue;
					}
					if ('r' === $asm[P_TYPE][$a]){
						if (Instruction::getGeneralRegIndex($b) == $stack_pointer_reg){
							return true;
						}
					}
					if ((false !== $sp_define)&&('m' === $asm[P_TYPE][$a])){
						if (preg_match('/'."$sp_define".'/',$b)){						
							return true;
						}
					}
				}
			}
		}
		return false;
	}


	///////////////////////////////////////////////////////////
	//确定 POST or Get 传递进来的动态插入数据
	public static function get_dynamic_insert_value (&$dynamic_insert){

		global $language;


		$new_dynamic_insert = CfgParser::params('di');
		if (isset($new_dynamic_insert)){
			if (is_array($new_dynamic_insert)){
				foreach ($new_dynamic_insert as $key => $value){
					if (isset($dynamic_insert[$key])){					
						$tmp = GenerateFunc::get_bit_from_inter($value);
						if ($tmp){
							if ($tmp <= $dynamic_insert[$key][BITS]){
								$dynamic_insert[$key]['new'] = $value;
							}else{
								GeneralFunc::LogInsert($language['toobig_dynamci_insert_value'].'di['.$key.'] : '.$tmp.' > '.$dynamic_insert[$key][BITS]);	
							}
						}else{
							GeneralFunc::LogInsert($language['illegal_dynamci_insert_value'].$value);	
						}
					}else{
						GeneralFunc::LogInsert($language['none_dynamic_insert_key'].$key);
					}
				}
			}else{
				GeneralFunc::LogInsert($language['dynamic_insert_not_array']);
			}
		}
		//var_dump ($new_dynamic_insert);
		//var_dump ($dynamic_insert);
		//exit;
	}

}