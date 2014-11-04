<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}

//显示
$INFO_SHOW = true;

echo '<head><meta charset="utf-8"></head>';

class DebugShowFunc{

	//显示 sp_define 变量
	public static function my_shower_09($sp_define_pattern,$sp_define){
		echo '<br>======== sp_define 变量 =======<br>';
		echo '<table border=1><tr bgcolor=pink><td>';
		var_dump ($sp_define);
		echo '</td><td>'.$sp_define_pattern.'</td>';	
		echo '</tr>';
		echo '</table>';
	}

	//显示 灵魂 gen阶段 受 用户设置 stock 指针影响 而 ipsp 被设置的情况
	public static function my_shower_08($sp_define,$array,$list,$soul){
		echo '<br>======== 显示 灵魂 gen阶段 受 用户设置 stock 指针影响 而 ipsp 被设置的情况 =======<br>';
		echo "<table border = 1>";
		echo '<tr><td>line</td><td>prefix</td><td>instruction</td><td>p0</td><td>p1</td><td>p2</td><td>ipsp</td><td>sp defined</td></tr>';
		foreach ($array as $a => $b){
			if ('#C0C0C0' == $color){
				$color = '#FFFFFF';
			}else{
				$color = '#C0C0C0';
			}
			$c = $list[$a]['c'];
			echo '<tr bgcolor='.$color.'><td>'.$c.'</td><td>'.$soul[$c]['prefix'].'</td><td>'.$soul[$c]['operation'].'</td><td>';
			echo $soul[$c]['params'][0];
			echo '</td><td>';
			echo $soul[$c]['params'][1];
			echo '</td><td>';
			echo $soul[$c]['params'][2];
			echo '</td>';
			if (true === $list[$a]['ipsp']){
				echo '<td bgcolor=red>保护';
			}
			echo '</td><td bgcolor=yellow>'.$sp_define.'</td></tr>';
		}	
		echo '</table>';
	}

	//显示 处理 目标段 信息 (比较 usr config 的配置信息)
	public static function my_shower_07($array,$ignore_sec){
		echo '<br>============= 处理目标段信息 ( $myTables[CodeSectionArray] ) 比较 -> usr.config配置段信息 ============= <br>';
		echo "<table border=1>";
		echo '<tr bgcolor="yellow"><td>ID</td><td><font color=blue>name</font></td><td>base</td><td>sec_name</td><td>PointerToRawData</td><td>SizeOfRawData</td><td>PointerToRelocation</td><td>NumberOfRelocation</td><td>Characteristics_L</td><td>Characteristics_H</td></tr>';
		foreach ($array as $a => $b){
			echo '<tr>';
			echo '<td>'.$a.'</td>';
			echo '<td><font color=blue>'.$b['name'].'</font></td>';
			echo '<td>'.$b['base'].'</td>';
			echo '<td>'.$b['sec_name'].'</td>';
			echo '<td>'.$b['PointerToRawData'].'</td>';
			echo '<td>'.$b['SizeOfRawData'].'</td>';
			echo '<td>'.$b['PointerToRelocation'].'</td>';
			echo '<td>'.$b['NumberOfRelocation'].'</td>';
			echo '<td>'.$b['Characteristics_L'].'</td>';
			echo '<td>'.$b['Characteristics_H'].'</td>';
			echo '</tr>';
		}
		foreach ($ignore_sec as $a => $b){
			echo '<tr>';
			echo '<td><font color=red>忽略段</font></td>';
			echo '<td><font color=red>';
			echo "$a";
			echo '</font></td>';
			echo '<td>-</td>';
			echo '<td>-</td>';
			echo '<td>-</td>';
			echo '<td>-</td>';
			echo '<td>-</td>';
			echo '<td>-</td>';
			echo '<td>-</td>';
			echo '<td>-</td>';
			echo '</tr>';
		}
		echo '</table>';    
	}

	//显示用户设置
	public static function my_shower_06($usr_cfg_file,$sec_name,$user_cnf){
		echo "<br>============= 显示用户设置 ( $usr_cfg_file ) ============= <br>";
		foreach ($sec_name as $sec_name_show => $a){
			$save = false;
			$total_sec_num = array();
			$table_name = array();
			$table_value = array();
			$table_name[] = "sec name";
			$table_value[] = $sec_name_show;
			$table_name[] = "sec number";		
			foreach ($a as $sec_num){	
				$total_sec_num[$sec_num] = $sec_num;    
			}
			$table_value[] = $total_sec_num;
			
			$a = $sec_num;
			$b = $user_cnf[$a];
					
			if (is_array($b)){
				foreach ($b as $z => $y){
					$table_name[]  = $z;
					$table_value[] = $y;
				}
			}else{
				$table_name[]  = $a;
				$table_value[] = $b;
			}


			echo '<table border = 1><tr>';
			foreach ($table_name as $i => $a){			
				if ($i%2){
					echo "<td>";
				}else{
					echo "<td bgcolor='#c0c0c0'>";
				}
				echo "$a"."</td>";
			}
			echo '</tr><tr>';
			foreach ($table_value as $i=> $a){			
				if ($i%2){
					echo "<td>";
				}else{
					echo "<td bgcolor='#c0c0c0'>";
				}
				var_dump ($a);
				echo '</td>';
			}        		
			echo '</tr>';
			echo '</table><br>';	
		}	

	}


	//显示骨架(预分配方案(ipsp已完成)，以及STACK冲突判断结果)
	public static function my_shower_05($c_bone_model,$bone_obj,$stack_unusable,$isConflict,$conflict_position){
		//global $soul_writein_Dlinked_List;
		$soul_writein_Dlinked_List = ConstructionDlinkedListOpt::getDlinkedListTotal();
		echo "<br>============= 显示骨架(预分配方案(ipsp已完成)，以及STACK冲突判断结果) ============= <br>";
		
		if (!$isConflict){
			unset($conflict_position);
			echo "<br><font color='blue'><b>conflict !! NO</b></font>";
		}else{
			echo "<br><font color='red'><b>conflict !! YES</b></font>";
		}

		$boned_soul_array = array();
		
		$i = 0;
		foreach ($c_bone_model['direct'] as $a => $b){
			if ($b){
				$i ++;
				for (;$i<=$b;$i++){
					$boned_soul_array[$a][$i] = $bone_obj[$i];
				}
				$i = $b;			
			}	    
		}

		//var_dump ($c_bone_model['direct']);
		//var_dump ($bone_obj);
		echo '<table border = 1><tr><td>Bone Number</td><td>BoneObj编号</td><td>链表编号</td><td>Type</td><td>Code</td><td>Params</td><td>usable[stack]</td><td>stack_unusable结果[同项]</td><td>冲突</td></tr>';
		foreach ($c_bone_model['code'] as $a => $b){
			if (true === $b){
				if (count ($boned_soul_array[$a]) > 0){
					$rowspan = count($boned_soul_array[$a]);
					$show_rowspan = true;
					foreach ($boned_soul_array[$a] as $z => $y){
						echo '<tr>';
						if ($show_rowspan){
							echo '<td rowspan='.$rowspan.'>';
							echo $a;
							echo '</td>';
						}
						echo '<td>';
						echo "$z".'</td><td>';
						echo "$y".'</td><td>';
						if (isset($soul_writein_Dlinked_List[$y]['poly'])){
						   echo '[多态]';
						}elseif (isset($soul_writein_Dlinked_List[$y]['bone'])){
						   echo '[骨架]';
						}elseif (isset($soul_writein_Dlinked_List[$y]['meat'])){
						   echo '[血肉]';
						}else{
						   echo '[灵魂]';
						}
						echo '</td><td>';
						if (isset($soul_writein_Dlinked_List[$y]['label'])){
							var_dump ($soul_writein_Dlinked_List[$y]['label']);
						}
						$x = ConstructionDlinkedListOpt::get_unit_by_soul_writein_Dlinked_List($y);
						var_dump ($x['code']['operation']);
						echo '</td><td>';
						var_dump ($x['code']['params']);
						echo '</td>';
						echo '<td>';
						if (true === $x['usable']['p']['stack']){
							echo '<font color=blue>前方Stack可用</font>';
						}else{
							echo '<font color=red>前方Stack禁用</font>';
						}					
						if (true === $x['usable']['n']['stack']){
							echo '<br><font color=blue>后方Stack可用</font>';
						}else{
							echo '<br><font color=red>后方Stack禁用</font>';
						}	
						echo '</td>';
						if ($show_rowspan){
							echo '<td rowspan='.$rowspan.'>';
							var_dump ($stack_unusable[$a]);
							echo '</td>';
							echo '<td rowspan='.$rowspan.'>';
							if ($a === $conflict_position['code']){
								var_dump ($conflict_position);
							}
							echo '</td>';
						}
						$show_rowspan = false;
					}
				}else{
					echo '<tr><td>'.$a.'</td><td>???</td><td>???</td><td>???</td><td>???</td><td>Empty or Not Copy Yet</td>';
				}
				echo '</tr>';
			}else{ //骨架本身
				echo '<tr bgcolor="yellow"><td>'.$a.'</td><td></td><td></td><td>当前骨架</td><td>';
				var_dump ($b);
				echo '</td><td></td>';
				$tmp = GenerateFunc::get_inst_define($b['operation'],$b['params']);
				echo '<td>';
				if (isset($tmp['STACK'])){
					echo '<font color=blue>Stack需要</font>';
				}
				echo '</td>';
				echo '<td></td>';
				echo '<td>';
				if ($a === $conflict_position['bone']){
					var_dump ($conflict_position);
				}
				echo '</td>';
				echo '</tr>';
			}
		}
		echo '</table>';

	}

	//多重数组比较
	public static function compare_multi_array(&$a,&$b){
		$tmp = $a;
		foreach ($tmp as $k => $v){
			if (isset($b[$k])){
				if (is_array($v)){
					if (true === self::compare_multi_array($a[$k],$b[$k])){
						unset($a[$k]);
						unset($b[$k]);
					}
				}elseif ($b[$k] === $v){
					unset($a[$k]);
					unset($b[$k]);
				}
			}
		}
		if ((empty($a)) and (empty($b))){
			return true;
		}
		return false;

	}

	//显示链表(按序)以及rel_jmp结构
	public static function my_shower_04($sec,$rel_jmp_range,$rel_jmp_pointer,$soul_writein_Dlinked_List_start,$soul_writein_Dlinked_List){

		echo "<br>============= 显示链表(按序),OpCode(without Fat) 以及rel_jmp结构 [Section Number: $sec] ============= <br>";
		echo '<table border = 1><tr><td>List Number</td><td>List contents</td><td>opcode</td><td>range</td><td>pointer</td></tr>';
		$c_unit = $soul_writein_Dlinked_List_start;
		while (true){
			echo '<tr><td>';
			echo '['."$c_unit".']';
			echo '</td><td>';
			var_dump ($soul_writein_Dlinked_List[$c_unit]);
			echo '</td><td>';
			//抽取代码
			if (isset($soul_writein_Dlinked_List[$c_unit]['label'])){
				echo $soul_writein_Dlinked_List[$c_unit]['label'];
			}else{
				$c_opt = OrgansOperator::GetByDListUnit($soul_writein_Dlinked_List[$c_unit],'code');				
				var_dump ($c_opt);
			}
			echo '</td><td>';
			var_dump ($rel_jmp_range[$c_unit]);
			echo '</td><td>';
			var_dump ($rel_jmp_pointer[$c_unit]);
			echo '</td></tr>';
			if (!isset($soul_writein_Dlinked_List[$c_unit]['n'])){
				break;
			}
			$c_unit = $soul_writein_Dlinked_List[$c_unit]['n'];
		}
		echo '</table>';
	}




	//显示多态 (支持 随机的 强度 表示),取代 function my_shower_02
	public static function my_shower_03($org_List_index,$poly_List_index,$c_poly_array){
		//global $soul_writein_Dlinked_List;
		$soul_writein_Dlinked_List = ConstructionDlinkedListOpt::getDlinkedListTotal();
		
		global $all_valid_mem_opt_index;

		echo "<br>============= 多态 结构 ===============<br>";
		//foreach ($StandardAsmResultArray as $a => $b){
		echo "<br><b>$a</b>";
		echo '<table border = 1><tr><td>sub tree</td><td>line</td><td>Instruction</td><td>Prev usable</td><td>Next usable</td></tr>';
		
		$org_List = $soul_writein_Dlinked_List[$org_List_index];
		if (isset($org_List['poly'])){
			$type   = '【多】';
		}elseif (isset($org_List['bone'])){
			$type   = '【骨】';
		}elseif (isset($org_List['meat'])){
			$type   = '【肉】';
		}else{
			$type   = '【魂】';
		}		
		$d      = OrgansOperator::GetByDListUnit($org_List,'code');
		$usable = OrgansOperator::GetByDListUnit($org_List,'usable');

		echo '<tr><td>'.'0'.'</td><td>'.$org_List_index.'</td><td>';
		echo "$type";
		if (is_array($d['prefix'])){
			foreach ($d['prefix'] as $z => $y){
				echo "$y ";
			}
		}
		echo $d['operation'];
		if (is_array($d['params'])){
			foreach ($d['params'] as $z => $y){
				echo " $y ,";
			}
		}
		var_dump ($d['p_type']);	
		var_dump ($d['p_bits']);
		echo "Stack:";
		if (true === $d['stack']){
			echo "true";
		}else{
			echo "false | null";
		}
		echo '</td><td>';
		///////////////////////////////////////////////////////////////////// Prev
		if (isset($usable['p']['flag_write_able'])){
			echo '<font color=pink>';
			foreach ($usable['p']['flag_write_able'] as $z => $y){
				echo " $z;";
			}
			echo '</font>';
		}
		if (isset($usable['p']['normal_write_able'])){
			echo '<font color=blue>';
			foreach ($usable['p']['normal_write_able'] as $z => $y){
				echo " $z{";
				foreach ($y as $v => $w){
					echo $v.',';  
				}
				echo "};";
			}
			echo '</font>';
		}
		if (isset($usable['p']['mem_opt_able'])){
			echo '<font color=red>';
			foreach ($usable['p']['mem_opt_able'] as $z => $y){
				$zz = $all_valid_mem_opt_index[$y]['code'];
				$v  = $all_valid_mem_opt_index[$y];
				echo '<br>'.$y.':'.$zz.' {'.$v['bits'].'位 - ';
				if ($v['opt'] == 1)
					echo "读; ";
				elseif ($v['opt'] == 2)
					echo "写; ";
				elseif ($v['opt'] == 3)
					echo "读写; ";
				else
					echo "<font color=red><b>未知?</b></font>; ";	

				if (is_array($v['reg'])){
					echo '(';
					foreach ($v['reg'] as $u => $t){
						echo "$t,";    
					}
					echo ')';
				}
			}
			echo '</font>';
		}
		if (true !== ($usable['p']['stack'])){
			echo ' <b>堆栈禁用</b> ';
		}
		echo '</td><td>';
		///////////////////////////////////////////////////////////////////// Next
		if (isset($usable['n']['flag_write_able'])){
			echo '<font color=pink>';
			foreach ($usable['n']['flag_write_able'] as $z => $y){
				echo " $z;";
			}
			echo '</font>';
		}
		if (isset($usable['n']['normal_write_able'])){
			echo '<font color=blue>';
			foreach ($usable['n']['normal_write_able'] as $z => $y){
				echo " $z{";
				foreach ($y as $v => $w){
					echo $v.',';  
				}
				echo "};";
			}
			echo '</font>';
		}
		if (isset($usable['n']['mem_opt_able'])){
			echo '<font color=red>';
			foreach ($usable['n']['mem_opt_able'] as $z => $y){
				$zz = $all_valid_mem_opt_index[$y]['code'];
				$v  = $all_valid_mem_opt_index[$y];
				echo '<br>'.$y.':'.$zz.' {'.$v['bits'].'位 - ';
				if ($v['opt'] == 1)
					echo "读; ";
				elseif ($v['opt'] == 2)
					echo "写; ";
				elseif ($v['opt'] == 3)
					echo "读写; ";
				else
					echo "<font color=red><b>未知?</b></font>; ";	
				
				if (is_array($v['reg'])){
					echo '(';
					foreach ($v['reg'] as $u => $t){
						echo "$t,";    
					}
					echo ')';
				}
			}
			echo '</font>';
		}
		if (true !== ($usable['p']['stack'])){
			echo ' <b>堆栈禁用</b> ';
		}
		echo '</td></tr>';
		
		self::my_shower_03_func_1 ($poly_List_index,$c_poly_array);

		echo "</table>";
	}


	//递归 多态 子数
	private static function my_shower_03_func_1($poly_List_index,$c_poly_array){
		global $all_valid_mem_opt_index;

		foreach ($c_poly_array['code'] as $a => $d){    
		
			echo '<tr bgcolor=\'#C0C0C0\'><td>';
			echo '>';
			echo '</td><td>'.$a.'</td><td>';		
			if (isset($d['label'])){
				echo $d['label'];
			}else{
				if (is_array($d['prefix'])){
					foreach ($d['prefix'] as $z => $y){
						echo "$y ";
					}
				}
				echo $d['operation'];
				if (is_array($d['params'])){
					foreach ($d['params'] as $z => $y){
						echo " $y ,";
					}
				}
			}
			var_dump ($d['p_type']);
			var_dump ($d['p_bits']);
			echo "Stack:";
			if (true !== $d['stack']){
				echo "false | null";
			}else{
				echo "true";
			}
			echo '</td><td>';
			///////////////////////////////////////////////////////////////////// Prev
			if ($c_poly_array['fat'][$a] === 1){
				echo "<b>FAT</b>";
			}else{		
				if (isset($c_poly_array['usable'][$a]['p']['flag_write_able'])){
					echo '<font color=pink>';
					foreach ($c_poly_array['usable'][$a]['p']['flag_write_able'] as $z => $y){
						echo " $z;";
					}
					echo '</font>';
				}
				if (isset($c_poly_array['usable'][$a]['p']['normal_write_able'])){
					echo '<font color=blue>';
					foreach ($c_poly_array['usable'][$a]['p']['normal_write_able'] as $z => $y){
						echo " $z{";
						foreach ($y as $v => $w){
							echo $v.',';  
						}
						echo "};";
					}
					echo '</font>';
				}

				if (isset($c_poly_array['usable'][$a]['p']['mem_opt_able'])){
					echo '<font color=red>';
					foreach ($c_poly_array['usable'][$a]['p']['mem_opt_able'] as $z => $y){
						$zz = $all_valid_mem_opt_index[$y]['code'];
						$v  = $all_valid_mem_opt_index[$y];
						echo '<br>'.$y.':'.$zz.' {'.$v['bits'].'位 - ';
						if ($v['opt'] == 1)
							echo "读; ";
						elseif ($v['opt'] == 2)
							echo "写; ";
						elseif ($v['opt'] == 3)
							echo "读写; ";
						else
							echo "<font color=red><b>未知?</b></font>; ";	

						if (is_array($v['reg'])){
							echo '(';
							foreach ($v['reg'] as $u => $t){
								echo "$t,";    
							}
							echo ')';
						}
						echo "}";
					}
					echo '</font>';
				}
				if (true !== ($c_poly_array['usable'][$a]['p']['stack'])){
					echo ' <b>堆栈禁用</b> ';
				}
			}
			echo '</td><td>';
			///////////////////////////////////////////////////////////////////// Next
			if ($c_poly_array['fat'][$a] === 2){
				echo "<b>FAT</b>";
			}else{		
				if (isset($c_poly_array['usable'][$a]['n']['flag_write_able'])){
					echo '<font color=pink>';
					foreach ($c_poly_array['usable'][$a]['n']['flag_write_able'] as $z => $y){
						echo " $z;";
					}
					echo '</font>';
				}
				if (isset($c_poly_array['usable'][$a]['n']['normal_write_able'])){
					echo '<font color=blue>';
					foreach ($c_poly_array['usable'][$a]['n']['normal_write_able'] as $z => $y){
						echo " $z{";
						foreach ($y as $v => $w){
							echo $v.',';  
						}
						echo "};";
					}
					echo '</font>';
				}
				if (isset($c_poly_array['usable'][$a]['n']['mem_opt_able'])){
					echo '<font color=red>';
					foreach ($c_poly_array['usable'][$a]['n']['mem_opt_able'] as $z => $y){
						$zz = $all_valid_mem_opt_index[$y]['code'];
						$v  = $all_valid_mem_opt_index[$y];
						echo '<br>'.$y.':'.$zz.' {'.$v['bits'].'位 - ';
						if ($v['opt'] == 1)
							echo "读; ";
						elseif ($v['opt'] == 2)
							echo "写; ";
						elseif ($v['opt'] == 3)
							echo "读写; ";
						else
							echo "<font color=red><b>未知?</b></font>; ";	

						if (is_array($v['reg'])){
							echo '(';
							foreach ($v['reg'] as $u => $t){
								echo "$t,";    
							}
							echo ')';
						}
						echo "}";
					}
					echo '</font>';
				}
				if (true !== ($c_poly_array['usable'][$a]['n']['stack'])){
					echo ' <b>堆栈禁用</b> ';
				}
			}
			echo '</td></tr>';

			$poly_index ++;
		
		}
		
	//	exit;


	}

	// 指令 一览 表
	public static function my_shower_01($CodeSectionArray,$StandardAsmResultArray,$exec_thread_list){
		
		global $normal_register_opt_array;
		global $flag_register_opt_array;
		global $valid_mem_opt_array;
		global $soul_usable;

		global $all_valid_mem_opt_index;
		global $soul_forbid;
		global $soul_writein_Dlinked_List_Total;

		echo "<br>";
		echo "<b><font color=red>注: </font>可用寄存器(通用/标志)  不可用则不显示  <font color=red><del>显式不可用</del></font></b>";
		foreach ($CodeSectionArray as $a => $b){
			echo "<br>###################### 节表编号 $a #########################";
			echo '<table border = 1><tr><td>thread id</td><td>line number</td></tr>';
			if (is_array($exec_thread_list)){
				foreach ($exec_thread_list[$a] as $c => $d){
					echo "<tr><td>$c</td><td>";
					if (is_array($d)){
						foreach ($d as $e => $f){
							echo "$f , ";
						}
					}
					echo "</td></tr>";
				}
				echo '</table>';
			}
			$color = 'white';
			echo '<table border = 1><tr><td>line</td><td>prefix</td><td>instruction</td><td>p0</td><td>p1</td><td>p2</td><td>normal regs</td><td>flag regs</td><td>valid mem addr</td><td>stack</td><td>ipsp</td></tr>';
			
			$c_list = $soul_writein_Dlinked_List_Total[$a]['list'][0];
			while (true){			
				$c = $c_list['c'];
				$d = $StandardAsmResultArray[$a][$c];
			//foreach ($StandardAsmResultArray[$a] as $c => $d){
				if ($color == 'white')
					$color = '#C0C0C0';
				else
					$color = 'white';
				//prev
				echo '<tr bgcolor='."$color".'><td><b><-</b></td><td><b>Prev</b></td>';
				echo '<td><b>usable</b></td><td><b>record</b></td><td></td><td></td><td>';
				if (is_array($soul_usable[$a][$c]['p']['normal_write_able'])){
					foreach ($soul_usable[$a][$c]['p']['normal_write_able'] as $z => $v){
						foreach ($v as $x => $w){
							echo Instruction::getRegByIdxBits($x,$z);
							echo " , ";
						}					
					}
				}
				if (is_array($soul_forbid[$a][$c]['p']['normal'])){
					echo '<font color = red>';
					foreach ($soul_forbid[$a][$c]['p']['normal'] as $z => $v){
						echo '<del>';
						foreach ($v as $x => $w){
							if (!Instruction::getRegByIdxBits($x,$z)){
								echo "$x - $z";
							}else
								echo Instruction::getRegByIdxBits($x,$z);
							echo " , ";
						}					
						echo '</del>';
					}
					echo '</font>';
				}
				echo '</td><td>';
				if (is_array($soul_usable[$a][$c]['p']['flag_write_able'])){
					foreach ($soul_usable[$a][$c]['p']['flag_write_able'] as $z => $v){
						echo " $z ,";
					}
				}
				if (is_array($soul_forbid[$a][$c]['p']['flag'])){
					echo '<font color = red>';
					foreach ($soul_forbid[$a][$c]['p']['flag'] as $z => $v){
						echo '<del>';
						echo " $z";
						echo '</del>';
						echo ',';
					}
					echo '</font>';
				}			
				echo '</td><td>';
				if (is_array($soul_usable[$a][$c]['p']['mem_opt_able'])){
					foreach ($soul_usable[$a][$c]['p']['mem_opt_able'] as $z => $v){
						$z = $all_valid_mem_opt_index[$v]['code'];
						$v = $all_valid_mem_opt_index[$v];
						echo $z.' {'.$v['bits'].'位 - ';
						if ($v['opt'] == 1)
							echo "读; ";
						elseif ($v['opt'] == 2)
							echo "写; ";
						elseif ($v['opt'] == 3)
							echo "读写; ";
						else
							echo "<font color=red><b>未知?</b></font>; ";	

						if (is_array($v['reg'])){
							echo '(';
							foreach ($v['reg'] as $u => $t){
								echo "$t,";    
							}
							echo ')';
						}
						echo "}<br>";
					}
				}
				echo '</td>';
				if (true !== $soul_usable[$a][$c]['p']['stack']){
					echo '<td bgcolor=red> 禁用';
				}else{
					echo '<td>可用';
				}
				echo '</td><td>';
				echo '</td></tr>';
				///////////////////////////////////////////////////
				//main
				echo '<tr bgcolor='."$color".'><td>';
				echo "$c";
				echo '</td><td>';

				if (isset($c_list['label'])){
					echo '<b><font color = red>Label</font></b></td><td>'.$c_list['label'].'</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
				}else{
					if (is_array($d['prefix'])){
						foreach ($d['prefix'] as $z => $y){
							echo "$y ";
						}
					}
					echo '</td><td>';
					echo $d['operation'];
					for ($w = 0;$w < 3;$w++){
						echo '</td><td>';
						if ('r' == $d['p_type'][$w]){
							echo "<font color=red>";
						}elseif('m' == $d['p_type'][$w]){
							echo "<font color=blue>";
						}elseif('i' == $d['p_type'][$w]){
							echo "<font color=black>";
						}
						if ($d['params'][$w]){
							echo $d['params'][$w];
							echo '[<b>';
							echo $d['p_bits'][$w];
							echo '</b> 位]';
							if ($d['p_m_reg'][$w]){
								echo ' {<b>';
								foreach ($d['p_m_reg'][$w] as $z => $y){
									echo "$z ";
								}
								echo '</b>}';
							}
						}
						echo "</font>";
					}	
					echo '</td><td>';
					if (is_array($normal_register_opt_array[$a][$c])){
						foreach ($normal_register_opt_array[$a][$c] as $z => $y){
							echo "$z {";
							foreach ($y as $w => $v){
								echo "$w".'位';
								if ($v == 1)
									echo "读; ";
								elseif ($v == 2)
									echo "写; ";
								elseif ($v == 3)
									echo "读写; ";
								else
									echo "<font color=red><b>未知?</b></font>; ";	
							}
							echo "}<br>";
						}
					}
					echo '</td><td>';
					if (is_array($flag_register_opt_array[$a][$c])){
						foreach ($flag_register_opt_array[$a][$c] as $z => $v){
							echo "$z {";
								if ($v == 1)
									echo "读; ";
								elseif ($v == 2)
									echo "写; ";
								elseif ($v == 3)
									echo "读写; ";
								else
									echo "<font color=red><b>未知?</b></font>; ";	
							
							echo "}<br>";
						}
					}
					echo '</td><td>';
					if (is_array($valid_mem_opt_array[$a][$c])){
						foreach ($valid_mem_opt_array[$a][$c] as $z => $v){
							echo $v['code'].' {'.$v['bits'].'位 - ';
							if ($v['opt'] == 1)
								echo "读; ";
							elseif ($v['opt'] == 2)
								echo "写; ";
							elseif ($v['opt'] == 3)
								echo "读写; ";
							else
								echo "<font color=red><b>未知(".$v['opt'].")?</b></font>; ";	

							if (is_array($v['reg'])){
								echo '(';
								foreach ($v['reg'] as $u => $t){
									echo "$t,";    
								}
								echo ')';
							}
							echo "}<br>";
						}
					}
					echo '</td>';
					
					if (true !== $d['stack']){
						echo '<td bgcolor=red> 禁用';
					}else{
						echo '<td>可用';
					}				
					
					echo '</td>';
					
					if (true === $c_list['ipsp']){
						echo '<td bgcolor=red> 保护';
					}else{
						echo '<td>';
					}
					echo '</td></tr>';
				}
				//next
				echo '<tr bgcolor='."$color".'><td><b>-></b></td><td><b>Next</b></td>';
				echo '<td><b>usable</b></td><td><b>record</b></td><td></td><td></td><td>';
				if (is_array($soul_usable[$a][$c]['n']['normal_write_able'])){
					foreach ($soul_usable[$a][$c]['n']['normal_write_able'] as $z => $v){
						foreach ($v as $x => $w){
							echo Instruction::getRegByIdxBits($x,$z);
							echo " , ";
						}					
					}
				}
				if (is_array($soul_forbid[$a][$c]['n']['normal'])){
					echo '<font color = red>';
					foreach ($soul_forbid[$a][$c]['n']['normal'] as $z => $v){
						echo '<del>';
						foreach ($v as $x => $w){
							if (!Instruction::getRegByIdxBits($x,$z)){
								echo "$x - $z";
							}else
								echo Instruction::getRegByIdxBits($x,$z);
							echo " , ";
						}					
						echo '</del>';
					}
					echo '</font>';
				}
				echo '</td><td>';
				if (is_array($soul_usable[$a][$c]['n']['flag_write_able'])){
					foreach ($soul_usable[$a][$c]['n']['flag_write_able'] as $z => $v){
						echo " $z ,";
					}
				}
				if (is_array($soul_forbid[$a][$c]['n']['flag'])){
					echo '<font color = red>';
					foreach ($soul_forbid[$a][$c]['n']['flag'] as $z => $v){
						echo '<del>';
						echo " $z";
						echo '</del>';
						echo ',';
					}
					echo '</font>';
				}
				echo '</td><td>';
				if (is_array($soul_usable[$a][$c]['n']['mem_opt_able'])){
					foreach ($soul_usable[$a][$c]['n']['mem_opt_able'] as $z => $v){
						$z = $all_valid_mem_opt_index[$v]['code'];
						$v = $all_valid_mem_opt_index[$v];
						echo $z.' {'.$v['bits'].'位 - ';
						if ($v['opt'] == 1)
							echo "读; ";
						elseif ($v['opt'] == 2)
							echo "写; ";
						elseif ($v['opt'] == 3)
							echo "读写; ";
						else
							echo "<font color=red><b>未知?</b></font>; ";	

						if (is_array($v['reg'])){
							echo '(';
							foreach ($v['reg'] as $u => $t){
								echo "$t,";    
							}
							echo ')';
						}
						echo "}<br>";
					}
				}
				echo '</td>';
				if (true !== $soul_usable[$a][$c]['n']['stack']){
					echo '<td bgcolor=red> 禁用';
				}else{
					echo '<td>可用';
				}				
				echo '</td><td>';
				echo '</td></tr>';
				
				///////////////////////////////////////////////////////
				//
				if (isset($c_list['n'])){
					$c_list = $soul_writein_Dlinked_List_Total[$a]['list'][$c_list['n']];
				}else{
					break;
				}
				 
			} 
			echo '</table>';
		}
		
		return;
	}



}



?>