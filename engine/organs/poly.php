<?php


if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}

class OrganPoly{

    private static $_index = 1; //poly 唯一编号，一组一个

	private static $_poly_model_index;
	private static $_poly_model_repo;

    /////////////////////////////////////////////
	public static function init(){
	    require dirname(__FILE__)."/../templates/poly.tpl.php";
        self::$_poly_model_index = $poly_model_index;
		self::$_poly_model_repo  = $poly_model_repo;
	}

	
	/////////////////////////////////////////////
	//比较 2个内存地址是否相同，考虑重定位 副本的问题
	// DWORD [DWORD FS:UNEST_RELINFO_104_71_2] === DWORD [DWORD FS:UNEST_RELINFO_104_71_0]
	private static function is_same_mem($a,$b){
		global $pattern_reloc_4_replace;
		global $UniqueHead;
						
		if ($a === $b){
			return true;
		}
		//考虑重定位
		$replacement = "$UniqueHead".'RELINFO_'.'$2_$3';
		$a = preg_replace($pattern_reloc_4_replace,$replacement,$a); 
		$b = preg_replace($pattern_reloc_4_replace,$replacement,$b); 

		if ($a === $b){
			return true;
		}
		return false;
	}


	////////////////////////////////////////////
	//继承前后可用记录给多态代码
	//
	private static function inherit_usable_to_poly(&$poly_model,$specific_usable,$soul_usable,$flag_forbid,$param_forbid,$rand_forbid,$rand_result,$org){

		global $all_valid_mem_opt_index;
		global $avmoi_ptr;


		foreach ($poly_model['code'] as $a => $b){ //应根据流程来走，这里简单起见，以代码顺序来走 | 直到有多态模板复杂到需要按流程来进行
			if (isset($soul_usable['p'])){
				$poly_model['usable'][$a]['p'] = $soul_usable['p'];     
				$poly_model['usable'][$a]['n'] = $soul_usable['p'];     
			}
		}
		
		if (isset($soul_usable['n'])){
			$poly_model['usable'][$a]['n'] = $soul_usable['n'];     
		}else{
			unset ($poly_model['usable'][$a]['n']);     
		}

		//特指 的 usable继承
		if ($specific_usable){
			foreach ($specific_usable as $a => $b){			
				if ($b['1']){
					unset ($poly_model['usable'][$a]['p']);
					$poly_model['usable'][$a]['p'] = $soul_usable[$b['1']];     
				}elseif ($b['2']){
					unset ($poly_model['usable'][$a]['n']);
					$poly_model['usable'][$a]['n'] = $soul_usable[$b['2']];     
				}
			}
		}

		//继承中 去除 模板中进制  禁止操作 项 (flag reg)
		if (isset ($flag_forbid)){
			if (isset ($flag_forbid['p'])){
				foreach ($flag_forbid['p'] as $z => $y){
					foreach ($y as $v => $w){
						unset ($poly_model['usable'][$z]['p']['flag_write_able'][$v]);
					}
				}
			}
			if (isset ($flag_forbid['n'])){
				foreach ($flag_forbid['n'] as $z => $y){
					foreach ($y as $v => $w){
						unset ($poly_model['usable'][$z]['n']['flag_write_able'][$v]);
					}
				}
			}
			unset ($poly_model['flag_forbid']);
		}


		//继承中 去除 模板中进制  禁止操作 项 (参数)
		if (isset ($param_forbid)){
			if (isset ($param_forbid['p'])){
				foreach ($param_forbid['p'] as $z => $y){
					foreach ($y as $v => $w){
						if ('r' == $org['p_type'][$v]){ //简化处理，直接去掉目标通用寄存器 所有可能位数的 可写权限
							$standard_reg = Instruction::getGeneralRegIndex($org['params'][$v]);
							unset ($poly_model['usable'][$z]['p']['normal_write_able'][$standard_reg]);
							//除寄存器外，还需要去除所有与此寄存器相关的内存地址的usable
							if (isset($poly_model['usable'][$z]['p']['mem_opt_able'])){
								$s = $poly_model['usable'][$z]['p']['mem_opt_able'];
								foreach ($s as $u => $t){
									if (isset ($all_valid_mem_opt_index[$t]['reg'])){
										foreach ($all_valid_mem_opt_index[$t]['reg'] as $j => $k){
											if ($standard_reg === $k){
												unset ($poly_model['usable'][$z]['p']['mem_opt_able'][$u]);
												break;
											}
										}
									}
								}              
							}
						}elseif ('m' == $org['p_type'][$v]){ //简化处理，直接去掉目标 内存地址 所有可能位数的 可写权限
							if (is_array($poly_model['usable'][$z]['p']['mem_opt_able'])){
								$s = $poly_model['usable'][$z]['p']['mem_opt_able'];
								foreach ($s as $u => $t){
									if (self::is_same_mem($org['params'][$v],$all_valid_mem_opt_index[$t]['code'])){
										if ($all_valid_mem_opt_index[$t]['opt'] > 1){
											$all_valid_mem_opt_index[$avmoi_ptr] = $all_valid_mem_opt_index[$t];
											$all_valid_mem_opt_index[$avmoi_ptr]['opt'] = 1;
											$poly_model['usable'][$z]['p']['mem_opt_able'][$u] = $avmoi_ptr;
											$avmoi_ptr ++;                                                                  
										}
									}
								}
							}
						}
					}
				}
			}
			if (isset ($param_forbid['n'])){
				foreach ($param_forbid['n'] as $z => $y){
					foreach ($y as $v => $w){
						if ('r' == $org['p_type'][$v]){ //简化处理，直接去掉目标通用寄存器 所有可能位数的 可写权限
							$standard_reg = Instruction::getGeneralRegIndex($org['params'][$v]);
							unset ($poly_model['usable'][$z]['n']['normal_write_able'][$standard_reg]);
							//除寄存器外，还需要去除所有与此寄存器相关的内存地址的usable
							if (isset($poly_model['usable'][$z]['n']['mem_opt_able'])){
								$s = $poly_model['usable'][$z]['n']['mem_opt_able'];
								foreach ($s as $u => $t){
									if (isset ($all_valid_mem_opt_index[$t]['reg'])){
										foreach ($all_valid_mem_opt_index[$t]['reg'] as $j => $k){
											if ($standard_reg === $k){
												unset ($poly_model['usable'][$z]['n']['mem_opt_able'][$u]);
												break;
											}
										}
									}
								}       
							}
						}elseif ('m' == $org['p_type'][$v]){ //简化处理，直接去掉目标 内存地址 所有可能位数的 可写权限
							if (isset($poly_model['usable'][$z]['n']['mem_opt_able'])){
								$s = $poly_model['usable'][$z]['n']['mem_opt_able'];
								foreach ($s as $u => $t){
									if (self::is_same_mem($org['params'][$v],$all_valid_mem_opt_index[$t]['code'])){
										if ($all_valid_mem_opt_index[$t]['opt'] > 1){
											$all_valid_mem_opt_index[$avmoi_ptr] = $all_valid_mem_opt_index[$t];
											$all_valid_mem_opt_index[$avmoi_ptr]['opt'] = 1;
											$poly_model['usable'][$z]['n']['mem_opt_able'][$u] = $avmoi_ptr;
											$avmoi_ptr ++;                                                                  
										}
									}
								}
							}
						}
					}
				}
			}
		}

		//继承中 去除 模板中进制  禁止操作 项 (随机值)
		if (isset ($rand_forbid)){
			if (isset ($rand_forbid['p'])){
				foreach ($rand_forbid['p'] as $z => $y){
					foreach ($y as $v => $w){
						if (Instruction::getGeneralRegIndex($rand_result[$v])){ //通用寄存器 简化处理 不区分bits 全部取消
							$cri = Instruction::getGeneralRegIndex($rand_result[$v]);
							unset ($poly_model['usable'][$z]['p']['normal_write_able'][$cri]);
						}else{                                           //内存地址
							if (isset($poly_model['usable'][$z]['p']['mem_opt_able'])){
								$x = $poly_model['usable'][$z]['p']['mem_opt_able'];
								foreach ($x as $t => $u){
									//if ($rand_result[$v] === $all_valid_mem_opt_index[$u]['code']){
									if (self::is_same_mem($rand_result[$v],$all_valid_mem_opt_index[$u]['code'])){
										//echo "   same";
										unset ($poly_model['usable'][$z]['p']['mem_opt_able'][$t]); 
									}
								}
							}
						}
					}
				}
			}
			if (isset ($rand_forbid['n'])){
				foreach ($rand_forbid['n'] as $z => $y){
					foreach ($y as $v => $w){
						if (Instruction::getGeneralRegIndex($rand_result[$v])){ //通用寄存器 简化处理 不区分bits 全部取消
							$cri = Instruction::getGeneralRegIndex($rand_result[$v]);
							unset ($poly_model['usable'][$z]['n']['normal_write_able'][$cri]);
						}else{                                           //内存地址
							if (isset($poly_model['usable'][$z]['n']['mem_opt_able'])){
								$x = $poly_model['usable'][$z]['n']['mem_opt_able'];
								foreach ($x as $t => $u){
									if (self::is_same_mem($rand_result[$v],$all_valid_mem_opt_index[$u]['code'])){
																			echo "   same";
											unset ($poly_model['usable'][$z]['n']['mem_opt_able'][$t]);                                                                     
									}
								}                                               
							}
						}
					}
				}               
			}
		}

		//通用寄存器 什么情况下可能需要被主动禁用 ？
		/*
		if (isset ($normal_forbid)){
				if (isset ($normal_forbid['p'])){
						foreach ($normal_forbid['p'] as $z => $y){
							foreach ($y as $v => $w){
									unset ($poly_model['usable'][$z]['p']['normal_write_able'][$v]);
								}
						}               
				}
				if (isset ($normal_forbid['n'])){
						foreach ($normal_forbid['n'] as $z => $y){
							foreach ($y as $v => $w){
									unset ($poly_model['usable'][$z]['n']['normal_write_able'][$v]);
								}
						}               
				}
				unset ($poly_model['normal_forbid']);
		}*/
			return;
	}

	////////////////////////////////////////////
	//对可乱序的多态模板进行乱序处理
	//返回 乱序后的多态模板
	//
	//
	private static function ooo ($poly_model){
		$ret = $poly_model;
			$t = $poly_model['ooo'];        
			if (shuffle($t)){
				if ($t != $poly_model['ooo']){
						foreach ($poly_model['ooo'] as $a => $b){
								if ($t[$a] != $b){
										$ret['code'][$t[$a]] = $poly_model['code'][$b];
										$ret['p_type'][$t[$a]]    = $poly_model['p_type'][$b];
										$ret['p_bits'][$t[$a]]    = $poly_model['p_bits'][$b];
									}
							}
					}
			}
		return $ret;
	}

	////////////////////////////////////////////
	//当前指令 是否含 随机 内存地址(或用来构成该地址的寄存器) 操作
	//【用来构成该地址的寄存器】无须判断 见：readme.poly.txt
	//含有，返回true， 不含，返回false
	private static function org_include_mem($org,$mem){
		if (isset($org['p_type'])){
			foreach ($org['p_type'] as $a => $b){
				if ($b === 'm'){
					if ($mem['code'] === $org['params'][$a]){
						return true;
					}
				}
			}
		}
		return false;
	}

	////////////////////////////////////////////
	//检查目标多态模板是否可用 (new_regs 与 soul_usable['next'] 比较)
	//
	//随机部分 检查的同时 也 生成
	//
	private static function check_poly_usable ($c_usable,$org,&$usable_poly_model,&$rand_result){


		global $all_valid_mem_opt_index;

		$obj = $org['operation'];

		$tmp = $usable_poly_model;
		foreach ($tmp as $a => $b){
			//检查new stack 是否冲突
			if (true !== $org['stack']){			
				if (true === self::$_poly_model_repo[$obj][$b]['new_stack']){
					echo "<font color=red>stack conflict!";
					var_dump ($usable_poly_model[$a]);
					echo '</font>';
					unset($usable_poly_model[$a]);
					continue;
				}		    
			}
			////////////////////////
			$break = false;
			if (is_array(self::$_poly_model_repo[$obj][$b]['new_regs']['normal'])){ //检查新增 通用 寄存器 或 内存地址
				foreach (self::$_poly_model_repo[$obj][$b]['new_regs']['normal'] as $c => $d){ //目前 仅考虑 32位 通用寄存器
					if (Instruction::getGeneralRegIndex($org['params'][$c])){        //原始指令参数中的通用寄存器
						$c = Instruction::getGeneralRegIndex($org['params'][$c]);
						if (!$c_usable['n']['normal_write_able'][$c][32]){ //仅 检查 Next 部分，见 readme_poly.txt 2013/04/19
							//echo "<br> $sec $line $c";
							unset ($usable_poly_model[$a]);
							$break = true;
							break;
						}
					}elseif (Instruction::getGeneralRegIndex($c)){                   //独立的通用寄存器
						if (!$c_usable['n']['normal_write_able'][$c][32]){ 
							unset ($usable_poly_model[$a]);
							$break = true;
							break;
						}
					}else{ //内存地址
						$available = false;
						foreach ($c_usable['n']['mem_opt_able'] as $e => $f){
							if ((2 <= $all_valid_mem_opt_index[$f]['opt'])&&($all_valid_mem_opt_index[$f]['code'] === $org['params'][$c])){
								$available = true;
							}
						}
						if (!$available){
							//var_dump ($org['params'][$c]);
							unset ($usable_poly_model[$a]);
							$break = true;
							break;
						}
					}
				}
			}			
			if ($break){
				continue;
			}
			if (is_array(self::$_poly_model_repo[$obj][$b]['new_regs']['flag'])){ //检查新增 标志 寄存器
				foreach (self::$_poly_model_repo[$obj][$b]['new_regs']['flag'] as $c => $d){
					if (!$c_usable['n']['flag_write_able'][$c]){ //仅 检查 Next 部分，见 readme_poly.txt 2013/04/19
						//echo "<br> $sec $line $c";
						unset ($usable_poly_model[$a]);
						$break = true;
						break;
					}
				}
			}                              
			if ($break){
				continue;
			}
			//echo "<br>+++++++++++++++++++++++++++++++++++++++++++++++<br>";
			if (isset(self::$_poly_model_repo[$obj][$b]['rand'])){ //需要获得 随机数(寄存器/内存)
															//
															// 目前为简单起见，仅处理 32 位
															//
				$c_usable_normal = $c_usable['p']['normal_write_able'];
				$rand_mem = false;
				foreach (self::$_poly_model_repo[$obj][$b]['rand'] as $z => $y){
					if (shuffle ($y)){
						foreach ($y as $x){
							if ($x == 'i'){
								$r_int = GenerateFunc::rand_interger();							
								$rand_result[$a][$z] = $r_int['value'];
								$rand_result[$a]['p_type'][$z] = 'i';
								$rand_result[$a]['p_bits'][$z] = 32; // 整数一律 默认32位
							}elseif (($x == 'm32')&&(!$rand_mem)){
								$c_usable_mem_readonly = false;
								$c_usable_mem_writable = false;
								if (isset($c_usable['p']['mem_opt_able'])){
									foreach ($c_usable['p']['mem_opt_able'] as $v => $w){
										if (32 == $all_valid_mem_opt_index[$w]['bits']){//合适的位数
											$c_usable_mem_readonly[$w] = true;
												if ($all_valid_mem_opt_index[$w]['opt'] >= 2){       //前方可写入 内存地址
													$c_usable_mem_writable[$w] = true;										
												}
										}
									}
									if (self::$_poly_model_repo[$obj][$b]['rand_privilege'][$z] >=2){ //需要写权限
										if (false !== $c_usable_mem_writable){
											$w = array_rand($c_usable_mem_writable);	
											//当前指令 不含 随机 内存地址(或用来构成该地址的寄存器) 操作
											if (false === self::org_include_mem($org,$all_valid_mem_opt_index[$w])){
												$rand_result[$a][$z] = $all_valid_mem_opt_index[$w]['code'];
												$rand_result[$a]['p_type'][$z] = 'm';
												$rand_result[$a]['p_bits'][$z] = 32;
												$rand_mem = true; //内存地址只能 一次
											}									
										}
									}elseif (false !== $c_usable_mem_readonly){                                                      //只要读权限
										$w = array_rand($c_usable_mem_readonly);							
										$rand_result[$a][$z] = $all_valid_mem_opt_index[$w]['code'];
										$rand_result[$a]['p_type'][$z] = 'm';
										$rand_result[$a]['p_bits'][$z] = 32;
									}
								}
							}elseif ($x == 'r32'){							
								if (self::$_poly_model_repo[$obj][$b]['rand_privilege'][$z] >=2 ){ //需要写权限
									if (isset($c_usable['p']['normal_write_able'])){
										$c_usable_normal_reg = false;
										foreach ($c_usable['p']['normal_write_able'] as $j => $k){
											if ($k[32]){
												$c_usable_normal_reg[$j] = true;
											}
										}
										if (false !== $c_usable_normal_reg){
											$rand_result[$a][$z] = array_rand($c_usable_normal_reg);
											$rand_result[$a]['p_type'][$z] = 'r';
											$rand_result[$a]['p_bits'][$z] = 32; // 整数一律 默认32位
										}							
									}
								}else{                                                      //只要读权限
									
									$rand_result[$a][$z] = array_rand(Instruction::getRegsByBits(32));
									$rand_result[$a]['p_type'][$z] = 'r';
									$rand_result[$a]['p_bits'][$z] = 32; // 整数一律 默认32位
								}   
							}

							if (isset($rand_result[$a][$z])){
								break;
							}
						}
					}
					if (!isset($rand_result[$a][$z])){
						unset ($usable_poly_model[$a]);
						break;
					}
				}
			}
		}
	}
	////////////////////////////////////////////
	//根据 多态模板 生成 多态代码
	//调用前已做过可用性检查，这里直接生成 返回
	//
	private static function generat_poly_code($org,$soul_usable,$poly_model,$rand_result,$int3 = false){
		global $c_rel_info;
		global $UniqueHead;

		global $sec;
			$ret = array();

			if (isset($poly_model['ooo'])){ //乱序
				$poly_model = self::ooo($poly_model);
			}
		 
			if ($int3){
				$ret['code']['int3']['operation'] = 'int3';
			}
			$ret['fat'] = $poly_model['fat'];

			$specific_usable = false;
			if (isset($poly_model['specific_usable'])){
				$specific_usable = $poly_model['specific_usable'];
			}

			//修正参数中 数据(固定跳转/原参数继承/...)
			foreach ($poly_model['code'] as $a => $b){//        foreach ($poly_model['operation'] as $a => $b){
					if (isset($b['label'])){
						$ret['code'][$a]['label'] = $UniqueHead.$b['label'].self::$_index." : ";
						continue;
					}	

					$ret['code'][$a]['operation'] = $b['operation'];
					if (!is_array($b['params'])){ //无参数
							continue;
					}
					$bb = $b['params'];
					foreach ($bb as $c => $d){
							if ('SOLID_JMP_' === substr($d,0,10)){ //固定跳转标号
									$tmp = explode ('_',$d);
									$d = $UniqueHead.$d.self::$_index;                                                  
							}else{
								//原参数的继承
								if (preg_match_all('/(p_)([\d]{1,})/',$d,$mat)){
									$mat = array_flip($mat[2]); 
									foreach ($mat as $z => $y){  									
										if (isset($org['rel'][$z])){	
											//if (('i' == $org['p_type'][$z])||('m' == $org['p_type'][$z])){//整数 或 内存 可能含有 重定位数据，
																											//重定位数据多态时可能被多处引用，这里需要复制一个新标号
											$new = GenerateFunc::reloc_inc_copy_naked($org['rel'][$z]['i'],$org['rel'][$z]['c']);
											//echo "<br> ".$org['rel'][$z]['i'].': '.$org['rel'][$z]['c'].' -> '.$new;
											$c_rel_info[$org['rel'][$z]['i']][$new] = $c_rel_info[$org['rel'][$z]['i']][$org['rel'][$z]['c']];

											//echo "<br>$sec: ".$org['rel']['i']."[$new] = ".$org['rel']['i'].'['.$org['rel']['c'].']';

											if (is_array($poly_model['rel_reset'][$z])){
												foreach ($poly_model['rel_reset'][$z] as $zz => $yy){
													$c_rel_info[$org['rel'][$z]['i']][$new][$zz] = $yy;
												}
											}
											//echo "<br>first:";
											//var_dump ($org['params'][$z]);
											$c_org_params = 
											str_replace("$UniqueHead".'RELINFO_'.$sec.'_'.$org['rel'][$z]['i'].'_'.$org['rel'][$z]['c'],"$UniqueHead".'RELINFO_'.$sec.'_'.$org['rel'][$z]['i'].'_'.$new,$org['params'][$z]);
											//echo "<br>last: $c ".$org['rel']['i'].' '.$new;
											//var_dump ($c_org_params);
											//$ret['code'][$a]['rel']['n'] = $c;
											$ret['code'][$a]['rel'][$c]['i'] = $org['rel'][$z]['i'];
											$ret['code'][$a]['rel'][$c]['c'] = $new;	
											$d = str_replace('p_'.$z,$c_org_params,$d);
										}else{  //p_n的n一般不会超过3个(指令参数最多不过3个),所以不考虑p_10会被p_1错误替换的问题
											$d = str_replace('p_'.$z,$org['params'][$z],$d);
										}
										if (!isset($poly_model['p_type'][$a][$c])){ //模板中手工指定的优先
											$poly_model['p_type'][$a][$c] = $org['p_type'][$z];
										}
										if (!isset($poly_model['p_bits'][$a][$c])){ //模板中手工指定的优先
											$poly_model['p_bits'][$a][$c] = $org['p_bits'][$z];
										}
									}
								}
								if (preg_match_all('/(r_)([\d]{1,})/',$d,$mat)){ //多态模板中的rand部分的替换
									$mat = array_flip($mat[2]); 
									foreach ($mat as $z => $y){             
										if ('m' == $rand_result['p_type'][$z]){//随机 内存 可能含有 重定位数据，
																			   //重定位数据多态时可能被多处引用，这里需要复制一个新标号
											if (GenerateFunc::reloc_inc_copy($rand_result[$z],$old,$new)){

												//var_dump ($rand_result);
												//exit;
												$rand_rel_inc[$z] = true;
												$c_rel_info[$old[1]][$new] = $c_rel_info[$old[1]][$old[2]];
												$rand_result[$z] = str_replace("$UniqueHead".'RELINFO_'.$old[0].'_'.$old[1].'_'.$old[2],"$UniqueHead".'RELINFO_'.$old[0].'_'.$old[1].'_'.$new,$rand_result[$z]);
												$ret['code'][$a]['rel'][$c]['i'] = $old[1];
												$ret['code'][$a]['rel'][$c]['c'] = $new;
											}
										}
										//p_n的n一般不会超过3个(指令参数最多不过3个),所以不考虑p_10会被p_1错误替换的问题
										$d = str_replace('r_'.$z,$rand_result[$z],$d);
									}
									if (!isset($poly_model['p_type'][$a][$c])){ //模板中手工指定的优先
										$poly_model['p_type'][$a][$c] = $rand_result['p_type'][$z];
									}
									if (!isset($poly_model['p_bits'][$a][$c])){ //模板中手工指定的优先
										$poly_model['p_bits'][$a][$c] = $rand_result['p_bits'][$z];
									}
								}
						}
						$ret['code'][$a]['params'][$c] = $d;
						$ret['code'][$a]['p_type'][$c] = $poly_model['p_type'][$a][$c];
						$ret['code'][$a]['p_bits'][$c] = $poly_model['p_bits'][$a][$c];					
					}
			}
			//修正 前后文 可用寄存器 及 内存地址
			if (isset($soul_usable)){
					self::inherit_usable_to_poly($ret,$specific_usable,$soul_usable,$poly_model['flag_forbid'],$poly_model['p_forbid'],$poly_model['r_forbid'],$rand_result,$org);
			}
		return $ret;
	}

    //根据多态目标 返回 可用多态模板数组,无可用返回false 
	//此处不考虑usable限制，仅根据opt,para 获取所有可用tpl
    public static function get_usable_models($obj){
        global $pattern_reloc;
		global $c_rel_info;
		global $stack_pointer_reg;
	

	    $ret = false;

		$usable_poly_model = self::$_poly_model_index[$obj['operation']];

		if (is_array($usable_poly_model)){ //初步 检测是否有可用多态模板(指令名)            
			$p_num = count($obj['p_type']);
			$usable_poly_model = $usable_poly_model[$p_num];
			if ($p_num){                    
				foreach ($obj['p_type'] as $a => $b){
					if ($b == 'r'){ //通用寄存器 可能有 直接按寄存器 进行的索引(优先于类型的索引)
						if (isset($usable_poly_model[$obj['params'][$a]])){
							$usable_poly_model = $usable_poly_model[$obj['params'][$a]];	
							continue;
						}
						//r 区分出为堆栈指针的寄存器 's'
						if (Instruction::getGeneralRegIndex($obj['params'][$a]) == $stack_pointer_reg){
							$b = 's';
						}
					}
					if ($b == 'i'){ //常数 有可能含有 重定位 & 常数忽略位数
						if (preg_match($pattern_reloc,$obj['params'][$a],$tmp)){										
							$tmp = explode ('_',$tmp[0]);
							$tmp_rel = 'rel'.$c_rel_info[$tmp[3]][$tmp[4]]['Type'];
							//含重定位的整数，可能有 直接按 重定位+Type 进行的索引(优先于'i'的索引)
							if (isset($usable_poly_model[$tmp_rel])){
								$usable_poly_model = $usable_poly_model[$tmp_rel];	
								continue;
							} 											
						}
					}else{
						$b .= $obj['p_bits'][$a]; //加上位数信息
					}
					$usable_poly_model = $usable_poly_model[$b];    
				}
			}
			if (count($usable_poly_model)){											
			    $ret = 	$usable_poly_model;
			}
		}

		return $ret;
	}

	////////////////////////////////////////////
	//对指定指令进行多态处理
	private static function collect_usable_poly_model($obj,$c_usable,&$ret){
		
		$usable_poly_model = self::get_usable_models($obj);
        if ($usable_poly_model){
			$rand_result = array();
			if (is_array($usable_poly_model)){
				self::check_poly_usable ($c_usable,$obj,$usable_poly_model,$rand_result);
				//随机获得 多态模板
				if (count($usable_poly_model)){

					$x = array_rand($usable_poly_model);

					if (isset(self::$_poly_model_repo[$obj['operation']][$usable_poly_model[$x]])){ //开始根据 多态模板 生成 多态 代码						        
						if ('int3' === $x){
							$ret = self::generat_poly_code($obj,$c_usable,self::$_poly_model_repo[$obj['operation']][$usable_poly_model[$x]],$rand_result[$x],true);
						}else{
							$ret = self::generat_poly_code($obj,$c_usable,self::$_poly_model_repo[$obj['operation']][$usable_poly_model[$x]],$rand_result[$x]);
						}
						//对多态结果进行stack可用状态设置(根据usable)
						GeneralFunc::soul_stack_set($ret['code'],$ret['usable']);
						return true;
					}else{						
						global $language;						
						GeneralFunc::LogInsert($language['poly_repo_null'].$obj['operation'].'['.$x.']',2);						
					}
					
				}
			}
		}
		return false;
	}

	/////////////////////////////////////////////
	//把 多态 结果插入 代码 顺序 链表
	private static function insert_into_list ($org,$poly_index,$asm_array,$from_soul=false){




		$ret = ConstructionDlinkedListOpt::getDlinkedListIndex();


		ConstructionDlinkedListOpt::setDlinkedList(ConstructionDlinkedListOpt::getDlinkedListIndex(),$org,'302'); //302 moved 标记  	    

		$c_prev = false;

		if (ConstructionDlinkedListOpt::issetDlinkedListUnit($org,'p')){

			$c_prev = ConstructionDlinkedListOpt::getDlinkedList($org,'p');
		}
		$c_last = false;

		if (ConstructionDlinkedListOpt::issetDlinkedListUnit($org,'n')){

			$c_last = ConstructionDlinkedListOpt::getDlinkedList($org,'n');
		}

		foreach ($asm_array as $a => $b){
			if (false === $c_prev){
				ConstructionDlinkedListOpt::setListFirstUnit();
			}else{
				ConstructionDlinkedListOpt::insertDlinkedListByIndex($c_prev);			
			}
	 
			ConstructionDlinkedListOpt::setDlinkedList($a,ConstructionDlinkedListOpt::getDlinkedListIndex(),'c');
	 
			ConstructionDlinkedListOpt::setDlinkedList($poly_index,ConstructionDlinkedListOpt::getDlinkedListIndex(),'poly');
			if ($from_soul){ //poly 源自 原始代码
				ConstructionDlinkedListOpt::setDlinkedList(true,ConstructionDlinkedListOpt::getDlinkedListIndex(),'soul');
			}
			if (isset($b['label'])){
				ConstructionDlinkedListOpt::setDlinkedList($b['label'],ConstructionDlinkedListOpt::getDlinkedListIndex(),'label');
			}elseif (GenerateFunc::is_effect_ipsp($b,1)){
				ConstructionDlinkedListOpt::setDlinkedList(true,ConstructionDlinkedListOpt::getDlinkedListIndex(),'ipsp');
			}

			$c_prev = ConstructionDlinkedListOpt::getDlinkedListIndex();

			ConstructionDlinkedListOpt::incDlinkedListIndex();
		}
		if (false !== $c_last){


			ConstructionDlinkedListOpt::insertDlinkedList($c_prev,$c_last);
		}
		return $ret;
	}


	////////////////////////////////////////////
	//
	//多态 处理
	//

	public static function start ($objs){ 
        
		$obj = $objs[1];

		$b = ConstructionDlinkedListOpt::getDlinkedList($obj);

		$from_soul = false;				

		if (isset($b['poly'])){      //多态
			if (true === $b['soul']){
				$from_soul = true;	
			}
		}elseif (isset($b['bone'])){ //骨架		
		
		}elseif (isset($b['meat'])){ //血肉
		
		}else{                                                    //原始灵魂		
			$from_soul = true;	
		}
		$c_obj    = OrgansOperator::GetByDListUnit($b,'code');
		$c_usable = OrgansOperator::GetByDListUnit($b,'usable');

		$c_poly_result = array();		

		if (self::collect_usable_poly_model($c_obj,$c_usable,$c_poly_result)){
			//生成 多态 逆向 数组
			OrgansOperator::SetPolyReverse(self::$_index,'i',$obj);           
			OrgansOperator::SetPolyReverse(self::$_index,'n',count($c_poly_result['code']));
			
			//把 多态 结果插入 代码 顺序 链表
			$insert_List_index = self::insert_into_list ($obj,self::$_index,$c_poly_result['code'],$from_soul);
			OrgansOperator::Set(POLY,self::$_index,$c_poly_result);

            //原单位Character.Rate清零 / 新单位init.character 初始化 & 继承原单位
			$old = Character::getAllRate($obj);
			Character::removeRate($obj);
            for ($i = $insert_List_index;$i < ConstructionDlinkedListOpt::getDlinkedListIndex();$i ++){
				$new = Character::initUnit($i,POLY);
				Character::mergeRate($i,$new,$old);
			}

			self::$_index ++;
			global $my_params;
			if ($my_params['echo']){
				DebugShowFunc::my_shower_03($obj,$insert_List_index,$c_poly_result);
			}
		}	
	}
}

?>