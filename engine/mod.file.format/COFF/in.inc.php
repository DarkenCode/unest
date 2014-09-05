<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}


function in_file_format(){
    global $output;
	global $language;
	global $myTables;
	global $my_params;
	global $buf;
	global $preprocess_sec_name;

	
	$CodeSectionArray = array();
	$RelocArray = array();
	
	
	$myTables = array(); 
	if (!analysis_coff($buf,$language,$output,$myTables,$my_params['echo'])){
		$output['error'][] = 'analysis coff fail.';
		
		
	}
}

















function analysis_coff($buf,$language,&$output,&$ret,$DebugShow = false){
    require dirname(__FILE__)."/Obj_Header_array.php";       
	
	$lpObj = 0;
	$machine = get_hex_2_dec($buf,$lpObj,2); 
	$lpObj += 2;
	if (isset($IMAGE_FILE_HEADER_MACHINE[$machine])){
		if ($IMAGE_FILE_HEADER_MACHINE[$machine] != 'IMAGE_FILE_MACHINE_I386'){
			$output['error'][] = $language['not_support_Machine'].': '.$IMAGE_FILE_HEADER_MACHINE[$machine];
			return false;
		}
	}else{
		$output['error'][] = $language['illegal_support_Machine'];
		return false;
	}

    $numberofsections  = get_hex_2_dec($buf,$lpObj,2,true);
	$lpObj += 2;
	
    
    $lpObj += 4;
    
    
    
    

    $PointerToSymbolTable  = get_hex_2_dec($buf,$lpObj,4,true);
    $lpObj += 4;
    

    $NumberOfSymbols  = get_hex_2_dec($buf,$lpObj,4,true);
    $lpObj += 4;
    

    

    $PointToStringTable = $PointerToSymbolTable + $NumberOfSymbols * (8+4+2+2+1+1);
    

    $SizeOfStringTable = get_hex_2_dec($buf,$PointToStringTable,4,true);
    
    

    $SizeOfOptionalHeader  = get_hex_2_dec($buf,$lpObj,2,true);
    $lpObj += 2;
    
    if ($SizeOfOptionalHeader != 0){
		$output['error'][] = $language['illegal_opt_head_size'];
        return false;
	}

    
	$lpObj += 2;
	
    $SecNum = 1;                     
    for (;$numberofsections;$numberofsections --){
		$sec_name = substr($buf,$lpObj,8);
		$lpObj += 8;
		
		$VirtualSize = get_hex_2_dec($buf,$lpObj,4);
		$lpObj += 4;
		$VirtualAddress = get_hex_2_dec($buf,$lpObj,4);
		$lpObj += 4;

		$tmp = $lpObj; 
		$SizeOfRawData = get_hex_2_dec($buf,$lpObj,4,true);
		$lpObj += 4;
		$PointerToRawData = get_hex_2_dec($buf,$lpObj,4,true);
		$lpObj += 4;
		$PointerToRelocation = get_hex_2_dec($buf,$lpObj,4,true);
		$lpObj += 4;
		$PointerToLinenumber = get_hex_2_dec($buf,$lpObj,4);
		$lpObj += 4;
		$NumberOfRelocation = get_hex_2_dec($buf,$lpObj,2,true);
		$lpObj += 2;
		$NumberOfLinenumbers = get_hex_2_dec($buf,$lpObj,2,true);
		$lpObj += 2;
		$Characteristics = get_hex_2_dec($buf,$lpObj,4);
		$tmp_Characteristics_L = get_hex_2_dec($buf,$lpObj,2,true); 
		$lpObj += 2;
		$tmp_Characteristics_H = get_hex_2_dec($buf,$lpObj,2,true); 
		$lpObj += 2;
		
		
		foreach ($IMAGE_SECTION_HEADER_Characteristics_H as $key => $value){
			if ($value == ($tmp_Characteristics_H & $value)){
				
			}
		}
		foreach ($IMAGE_SECTION_HEADER_Characteristics_L as $key => $value){
			if ($value == ($tmp_Characteristics_L & $value)){
				
				if ($key == 'IMAGE_SCN_CNT_CODE'){                        
					if ($SizeOfRawData > 0){ 
						$CodeSectionArray[$SecNum]['base'] = $tmp;        
						$CodeSectionArray[$SecNum]['sec_name'] = trim($sec_name);
						$CodeSectionArray[$SecNum]['PointerToRawData'] = $PointerToRawData;
						$CodeSectionArray[$SecNum]['SizeOfRawData'] = $SizeOfRawData;
						$CodeSectionArray[$SecNum]['PointerToRelocation'] = $PointerToRelocation;
						$CodeSectionArray[$SecNum]['NumberOfRelocation'] = $NumberOfRelocation;
						$CodeSectionArray[$SecNum]['Characteristics_L'] = $tmp_Characteristics_L;
						$CodeSectionArray[$SecNum]['Characteristics_H'] = $tmp_Characteristics_H;

					  
					  

		
						if ($PointerToRelocation != 0){
							$c_relocation = $PointerToRelocation;
							for ($c_reloc_number = 1;$c_reloc_number <= $NumberOfRelocation;$c_reloc_number ++){
								$reloc_VirtualAddress   = get_hex_2_dec($buf,$c_relocation,4,true);
								$c_relocation += 4;
								$reloc_SymbolTableIndex = get_hex_2_dec($buf,$c_relocation,4,true);
								$c_relocation += 4;
								$reloc_Type             = get_hex_2_dec($buf,$c_relocation,2,true);
								$c_relocation += 2;
								
								$RelocArray[$SecNum][$c_reloc_number]['VirtualAddress']   = $reloc_VirtualAddress;
								$RelocArray[$SecNum][$c_reloc_number]['SymbolTableIndex'] = $reloc_SymbolTableIndex;
								$RelocArray[$SecNum][$c_reloc_number]['Type'] = $reloc_Type;								
							}
						}				
					}
				}
			}
		}
		$SecNum ++;
	}

	if ($DebugShow){
		echo "<br><br>**************** section  一览 ************<br>";
		echo "<table border = 1><tr><td>段编号</td><td>段描述基址</td><td>段名</td><td>PointerToRawData</td><td>SizeOfRawData</td><td>PointerToRelocation</td><td>NumberOfRelocation</td><td>Characteristics(L)</td><td>Characteristics(H)</td></tr>";
		foreach ($CodeSectionArray as $SecNum => $b){
			echo "<tr>";
			echo "<td>$SecNum</td>";
			echo "<td>".$CodeSectionArray[$SecNum]['base']."</td>";
			echo "<td>".$CodeSectionArray[$SecNum]['sec_name']."</td>";
			echo "<td>".$CodeSectionArray[$SecNum]['PointerToRawData']."</td>";
			echo "<td>".$CodeSectionArray[$SecNum]['SizeOfRawData']."</td>";
			echo "<td>".$CodeSectionArray[$SecNum]['PointerToRelocation']."</td>";
			echo "<td>".$CodeSectionArray[$SecNum]['NumberOfRelocation']."</td>";
			echo "<td>".$CodeSectionArray[$SecNum]['Characteristics_L']."</td>";
			echo "<td>".$CodeSectionArray[$SecNum]['Characteristics_H']."</td>";
			echo "</tr>";
			if ($RelocArray[$SecNum]){
				foreach ($RelocArray[$SecNum] as $c_reloc_number => $b){
					echo "<tr bgcolor=\"yellow\"><td></td><td>reloc</td><td>$c_reloc_number:</td><td>VirtualAddress:</td><td>".$RelocArray[$SecNum][$c_reloc_number]['VirtualAddress']."</td><td>SymbolTableIndex:</td><td>".$RelocArray[$SecNum][$c_reloc_number]['SymbolTableIndex']."</td><td>Type:</td><td>".$RelocArray[$SecNum][$c_reloc_number]['Type']."</td></tr>";
				}
			}
		}
		echo "</table>";
    }		

    

    $SymbalTableArray = array();
	$symbalTableIndex = 0;
	
	

	$lpObj = $PointerToSymbolTable;
	$auxTable = 0; 
	$auxType  = 0; 
	for (;$NumberOfSymbols;$NumberOfSymbols --){
		if ($auxTable > 0){
			$auxTable --;
			if ($auxType == 1){ 
				
				
				$name = substr($buf,$lpObj,8);
				$lpObj += 8;
				
				$name = substr($buf,$lpObj,8);
				$lpObj += 8;
				
				$name = substr($buf,$lpObj,2);
				$lpObj += 2;
				
			}elseif (false !== $record_aux){ 
			    $AuxiliaryTableArray[$record_aux][$auxTable]['base'] = $lpObj;
				$AuxiliaryTableArray[$record_aux][$auxTable]['Length'] = get_hex_2_dec($buf,$lpObj,4,true);
                $lpObj += 4; 
				$AuxiliaryTableArray[$record_aux][$auxTable]['NumberOfRelocations'] = get_hex_2_dec($buf,$lpObj,2,true);
                $lpObj += 2; 
				$AuxiliaryTableArray[$record_aux][$auxTable]['NumberOfLinenumbers'] = get_hex_2_dec($buf,$lpObj,2,true);
                $lpObj += 2; 
				$AuxiliaryTableArray[$record_aux][$auxTable]['CheckSum'] = get_hex_2_dec($buf,$lpObj,4);
                $lpObj += 4;
				$AuxiliaryTableArray[$record_aux][$auxTable]['Number'] = get_hex_2_dec($buf,$lpObj,2,true);
                $lpObj += 2;  
				$AuxiliaryTableArray[$record_aux][$auxTable]['Selection'] = get_hex_2_dec($buf,$lpObj,1,true);
                $lpObj += 1;
				$lpObj += 3;
			}else{
				

				
				
				$tmp = get_hex_2_dec($buf,$lpObj,4);
				$lpObj += 4;
				
				$tmp = get_hex_2_dec($buf,$lpObj,2);
				$lpObj += 2;
				
				$tmp = get_hex_2_dec($buf,$lpObj,2);
				$lpObj += 2;
				
				$tmp = get_hex_2_dec($buf,$lpObj,4);
				$lpObj += 4;
				
				$tmp = get_hex_2_dec($buf,$lpObj,2);
				$lpObj += 2;
				
				$tmp = get_hex_2_dec($buf,$lpObj,1);
				$lpObj += 1;
				
				$tmp = get_hex_2_dec($buf,$lpObj,3);
				$lpObj += 3;
				
			}
			
		}else{
			$record_aux = false; 
			$c_addr = $lpObj;
			$tmp = get_hex_2_dec($buf,$lpObj,4,true);
			if ($tmp == 0){
				$lpObj += 4;
				$name_offset = get_hex_2_dec($buf,$lpObj,4,true);
				$lpObj += 4;
				$name = '';
				$count = 0;
				do {
					$tmp = substr($buf,$PointToStringTable + $name_offset,1);
					$name_offset ++;
					$count ++;
					$name .= $tmp;
					if ($count == 18){
						
						$count = 0;
					}
				}while ($tmp != "\0");
			}else{
				$name = substr($buf,$lpObj,8);
				$lpObj += 8;
			}
			
			
			
			$Value = get_hex_2_dec($buf,$lpObj,4);
			$Value1 = get_hex_2_dec($buf,$lpObj,4,true);
			$lpObj += 4;
			
			$SectionNumber = get_hex_2_dec($buf,$lpObj,2,true);
			$lpObj += 2;
			
			$Type = get_hex_2_dec($buf,$lpObj,2);
			$lpObj += 2;
			
			
			
			
			$StorageClass = get_hex_2_dec($buf,$lpObj,1,true);
			$lpObj += 1;
			
			
			
			
			
			
			
			$NumberOfAuxSymbols = get_hex_2_dec($buf,$lpObj,1,true);
			$lpObj += 1;
			
			
			
			$auxTable = $NumberOfAuxSymbols;
			if ($auxTable > 0){
				if ($StorageClasses['IMAGE_SYM_CLASS_FILE'] == $StorageClass){
					$auxType = 1;
				}else{
					$auxType = $name;
				}
			}
			
			
			
			
			
			
			
			
			
			
			
			
			
			if (($StorageClasses['IMAGE_SYM_CLASS_STATIC'] == $StorageClass) && (0 == $Value1)){ 
				if (isset($CodeSectionArray[$SectionNumber])){
					if ($NumberOfAuxSymbols){
						$SymbalTableArray[$SectionNumber]['name'] = trim($name);
						$SymbalTableArray[$SectionNumber]['base'] = $c_addr;
						$record_aux = $SectionNumber; 
						
						$CodeSectionArray[$SectionNumber]['name'] = trim($name);
					}
				}
			}
		}
		$symbalTableIndex ++;
	}    

    if ($DebugShow){
		$i = 1;
		echo "<br>Symbole table objects<table border = 1>";
		echo "<tr><td>i</td><td>No.</td><td>Name</td><td>base</td><td>Length</td><td>NumberOfRelocations</td><td>NumberOfLinenumbers</td><td>CheckSum</td><td>Number</td><td>Selection</td></tr>";
		foreach ($SymbalTableArray as $a => $b){
			echo "<tr><td>$i</td><td>";
			echo "$a</td><td>";
			echo $SymbalTableArray[$a]['name']."</td><td>"; 
			echo $SymbalTableArray[$a]['base']; 
			echo "</td></tr>";
			if ($AuxiliaryTableArray[$a]){ 
				foreach ($AuxiliaryTableArray[$a] as $c => $d){
					echo "<tr bgcolor = \"pink\">";
					echo "<td></td><td></td><td>辅助符号表</td><td>";
					echo $AuxiliaryTableArray[$a][$c]['base']."</td><td>";
					echo $AuxiliaryTableArray[$a][$c]['Length']."</td><td>";
					echo $AuxiliaryTableArray[$a][$c]['NumberOfRelocations']."</td><td>";
					echo $AuxiliaryTableArray[$a][$c]['NumberOfLinenumbers']."</td><td>";
					echo "0x".$AuxiliaryTableArray[$a][$c]['CheckSum']."</td><td>";
					echo $AuxiliaryTableArray[$a][$c]['Number']."</td><td>";
					echo $AuxiliaryTableArray[$a][$c]['Selection'];
				
					echo "</td>";
					echo "</tr>";
				}
			}
			$i ++;
		}
		echo "</table>";
    }
	


    $count = count($CodeSectionArray);
    if ($count > 8190){
		$output['error'][] = $language['too_much_sections'];
	    return false;
	}

    if (count($SymbalTableArray) != $count){
	    $output['warning'][] = $language['not_same_code_sym_array'];
	}

    
    $ret['CodeSectionArray']    = $CodeSectionArray;
	$ret['RelocArray']          = $RelocArray;
	$ret['SymbalTableArray']    = $SymbalTableArray;
	$ret['AuxiliaryTableArray'] = $AuxiliaryTableArray;
  
	return true;
}



function get_hex_2_dec($buf,$lpBuf,$size,$dec = false){
    $tmp = '';
	$lpBuf += $size;
	$lpBuf --;
	for (;$size;$size--){
	    $tmp .= substr($buf,$lpBuf,1);
		$lpBuf --;
	}
	$tmp = bin2hex($tmp);
	if ($dec){
		$tmp = hexdec($tmp);
	}
    return $tmp;
}


?>