<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}





function shutdown_except(){
    global $complete_finished;
	global $log_path;
	global $exetime_record;
    if ((!$complete_finished)&&(!GeneralFunc::LogHasErr())){
		GeneralFunc::LogInsert('unexpected shutdown, maximum execution time exceeded or other errors');
	}
	$output = GeneralFunc::LogRead();
    
	file_put_contents($log_path,json_encode($output));  

	var_dump ($output);
	var_dump ($exetime_record);
	echo "<br>memory_get_usage: ";
    var_dump (memory_get_usage());
}






class GeneralFunc{
    
	
	private static $_error   = array();
	private static $_warning = array();
	private static $_notice  = array();

	
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


	
	
	private static $_stime = 0;
	public static function exetime_record(){
		

		$etime=microtime(true);
		$total=$etime - self::$_stime;   
		$str_total = var_export($total, TRUE);  
		if(substr_count($str_total,"E")){  
			$float_total = floatval(substr($str_total,5));  
			$total = $float_total/100000;  				
		}
		self::$_stime = microtime(true); 
		return $total;
		

	}    

    
	
	 
	 
	 
	
	public static function get_params($argv){
		$ret = false;
	 
		if (is_array($_REQUEST)){
			$ret = $_REQUEST;
		}	
		if (count($argv) > 1){
			parse_str($argv[1],$ret);
		}
		return $ret;
	 }


	
	
	public static function soul_stack_set(&$code,$usable){
		foreach ($code as $a => $b){
			if ((true !== $usable[$a]['p']['stack']) or (true !== $usable[$a]['n']['stack'])){
				$code[$a]['stack'] = false;
			}else{
				$code[$a]['stack'] = true;
			}		
		}
	}

	
	
	
	
	
	
	public static function get_file_line($filename){
		$line = 0;
		@$fp = fopen($filename , 'r');  
		if($fp){  
			
			while(stream_get_line($fp,8192,"\n")){  
				$line++;  
			}
			fclose($fp);
			return $line;
		}
		return false;
	}

	
	
	

	public static function internal_log_save($title,$contents=false){
		global $engin_version;

		$log_path = dirname(__FILE__)."/../../log/$engin_version/";

		if (!is_dir($log_path)){
			if (!mkdir($log_path)){
				error_log("fail to mkdir: $log_path",1,"1094566308@qq.com","From: internal_fail@unest.org");
				return false;
			}
		}

		$log_file = $log_path."log.txt";
		
		if(!flock($fp=fopen($log_file,'a+'), LOCK_NB | LOCK_EX)){
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


	
	
	
	
	
	
	
	public static function is_effect_ipsp($asm,$rule = 1,$sp_define = false){
		global $Intel_instruction;
		global $con_abs_jmp;
		global $stack_pointer_reg;
		global $registersss;
		global $register_assort;
		
		if (isset($con_abs_jmp[$asm['operation']])){ 
			return true;
		}

		$opt = $Intel_instruction[$asm['operation']];

		if ($opt['multi_op']){
			$i = count($asm['p_type']);
			$opt = $opt[$i];
		}

		if (isset($opt['STACK'])){
			return true;
		}
		
		if (is_array($asm['params'])){ 
			foreach ($asm['params'] as $a => $b){
				if ('i' !== $asm['p_type'][$a]){
					if ((0 === $rule) && ($opt[$a] <= 1)){
						continue;
					}
					if ($opt[$a] < 1){ 
						continue;
					}
					if ('r' === $asm['p_type'][$a]){
						if ($register_assort[$b] == $stack_pointer_reg){
							return true;
						}
					}
					if ((false !== $sp_define)&&('m' === $asm['p_type'][$a])){
						if (preg_match('/'."$sp_define".'/',$b)){						
							return true;
						}
					}
				}
			}
		}
		return false;
	}


	
	
	public static function get_dynamic_insert_value (&$dynamic_insert){

		global $language;
		global $my_params;

		$new_dynamic_insert = $my_params['di'];
		if (isset($new_dynamic_insert)){
			if (is_array($new_dynamic_insert)){
				foreach ($new_dynamic_insert as $key => $value){
					if (isset($dynamic_insert[$key])){					
						$tmp = GenerateFunc::get_bit_from_inter($value);
						if ($tmp){
							if ($tmp <= $dynamic_insert[$key]['bits']){
								$dynamic_insert[$key]['new'] = $value;
							}else{
								GeneralFunc::LogInsert($language['toobig_dynamci_insert_value'].'di['.$key.'] : '.$tmp.' > '.$dynamic_insert[$key]['bits']);	
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
		
		
		
	}

}