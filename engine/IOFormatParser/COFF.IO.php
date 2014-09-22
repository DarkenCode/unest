<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}

class IOFormatParser{
    
	
	
	

	private static $_StorageClassesDescription = array (
		'IMAGE_SYM_CLASS_END_OF_FUNCTION' => '表示函数结尾的特殊符号，用于调试。',
		'IMAGE_SYM_CLASS_NULL' => '未被赋予存储类别。',
		'IMAGE_SYM_CLASS_AUTOMATIC' => '自动（堆栈）变量。Value域指出此变量在栈帧中的偏移。',
		'IMAGE_SYM_CLASS_EXTERNAL' => 'Microsoft的工具使用此值来表示外部符号。如果SectionNumber域为0（IMAGE_SYM_UNDEFINED），那么Value域给出大小；如果SectionNumber 域不为0，那么Value域给出节中的偏移。',
		'IMAGE_SYM_CLASS_STATIC' => '符号在节中的偏移。如果Value域为0，那么此符号表示节名。',
		'IMAGE_SYM_CLASS_REGISTER' => '寄存器变量。Value域给出寄存器编号。',
		'IMAGE_SYM_CLASS_EXTERNAL_DEF' => '在外部定义的符号。',
		'IMAGE_SYM_CLASS_LABEL' => '模块中定义的代码标号。Value域给出此符号在节中的偏移。',
		'IMAGE_SYM_CLASS_UNDEFINED_LABEL' => '引用的未定义的代码标号。',
		'IMAGE_SYM_CLASS_MEMBER_OF_STRUCT' => '结构体成员。Value域指出是第几个成员。',
		'IMAGE_SYM_CLASS_ARGUMENT' => '函数的形式参数（形参）。Value域指出是第几个参数。',
		'IMAGE_SYM_CLASS_STRUCT_TAG' => '结构体名。',
		'IMAGE_SYM_CLASS_MEMBER_OF_UNION' => '共用体成员。Value域指出是第几个成员。',
		'IMAGE_SYM_CLASS_UNION_TAG' => '共用体名。',
		'IMAGE_SYM_CLASS_TYPE_DEFINITION' => 'Typedef项。',
		'IMAGE_SYM_CLASS_UNDEFINED_STATIC' => '静态数据声明。',
		'IMAGE_SYM_CLASS_ENUM_TAG' => '枚举类型名。',
		'IMAGE_SYM_CLASS_MEMBER_OF_ENUM' => '枚举类型成员。Value域指出是第几个成员。',
		'IMAGE_SYM_CLASS_REGISTER_PARAM' => '寄存器参数。',
		'IMAGE_SYM_CLASS_BIT_FIELD' => '位域。Value域指出是位域中的第几个位。',
		'IMAGE_SYM_CLASS_BLOCK' => ' .bb（beginning of block，块开头) 或.eb记录（end of block，块结尾）。Value域是代码位置，它是一个可重定位的地址。',
		'IMAGE_SYM_CLASS_FUNCTION' => 'Microsoft的工具用此值来表示定义函数范围的符号记录，这些符号记录分别是：.bf（begin function，函数开头）、.ef（end function，函数结尾）以及.lf（lines in function，函数中的行）。对于.lf 记录来说，Value域给出了源代码中此函数所占的行数。对于.ef记录来说，Value域给出了函数代码的大小。',
		'IMAGE_SYM_CLASS_END_OF_STRUCT' => '结构体末尾。',
		'IMAGE_SYM_CLASS_FILE' => 'Microsoft的工具以及传统COFF格式都使用此值来表示源文件符号记录。这种符号表记录后面跟着给出文件名的辅助符号表记录。',
		'IMAGE_SYM_CLASS_SECTION' => '节的定义（Microsoft的工具使用STATIC存储类别代替）。',
		'IMAGE_SYM_CLASS_WEAK_EXTERNAL' => '弱外部符号。要获取更多信息，请参考5.5.3节“辅助符号表记录格式之三：弱外部符号”。',
		'IMAGE_SYM_CLASS_CLR_TOKEN' => '表示CLR记号的符号。它的名称是这个记号的十六进制值的ASCII码表示。要获取更多信息，请参考5.5.7节“CLR记号定义”。',
	);

	
	
	
	private static $_StorageClasses = array (
		'IMAGE_SYM_CLASS_END_OF_FUNCTION' => -1,
		'IMAGE_SYM_CLASS_NULL'            => 0,
		'IMAGE_SYM_CLASS_AUTOMATIC'       => 1,
		'IMAGE_SYM_CLASS_EXTERNAL'        => 2,
		'IMAGE_SYM_CLASS_STATIC'          => 3,
		'IMAGE_SYM_CLASS_REGISTER'        => 4,
		'IMAGE_SYM_CLASS_EXTERNAL_DEF'    => 5,
		'IMAGE_SYM_CLASS_LABEL'           => 6,
		'IMAGE_SYM_CLASS_UNDEFINED_LABEL' => 7,
		'IMAGE_SYM_CLASS_MEMBER_OF_STRUCT'=> 8,
		'IMAGE_SYM_CLASS_ARGUMENT'        => 9,
		'IMAGE_SYM_CLASS_STRUCT_TAG'      => 10,
		'IMAGE_SYM_CLASS_MEMBER_OF_UNION' => 11,
		'IMAGE_SYM_CLASS_UNION_TAG'       => 12,
		'IMAGE_SYM_CLASS_TYPE_DEFINITION' => 13,
		'IMAGE_SYM_CLASS_UNDEFINED_STATIC'=> 14,
		'IMAGE_SYM_CLASS_ENUM_TAG'        => 15,
		'IMAGE_SYM_CLASS_MEMBER_OF_ENUM'  => 16,
		'IMAGE_SYM_CLASS_REGISTER_PARAM'  => 17,
		'IMAGE_SYM_CLASS_BIT_FIELD'       => 18,

		'IMAGE_SYM_CLASS_FAR_EXTERNAL'    => 68,

		'IMAGE_SYM_CLASS_BLOCK'           => 100,
		'IMAGE_SYM_CLASS_FUNCTION'        => 101,
		'IMAGE_SYM_CLASS_END_OF_STRUCT'   => 102,
		'IMAGE_SYM_CLASS_FILE'            => 103,
	
		'IMAGE_SYM_CLASS_SECTION'         => 104,
		'IMAGE_SYM_CLASS_WEAK_EXTERNAL'   => 105,

		'IMAGE_SYM_CLASS_CLR_TOKEN'       => 107,
	);


	
	
	
	private static $_Relocation_type = array (
		'IMAGE_REL_I386_ABSOLUTE' => 0,
		'IMAGE_REL_I386_DIR16'    => 1,
		'IMAGE_REL_I386_REL16'    => 2,
		'IMAGE_REL_I386_DIR32'    => 6,
		'IMAGE_REL_I386_DIR32NB'  => 7,
		'IMAGE_REL_I386_SEG12'    => 9,
		'IMAGE_REL_I386_SECTION'  => 10,
		'IMAGE_REL_I386_SECREL'   => 11,
		'IMAGE_REL_I386_TOKEN'    => 12,
		'IMAGE_REL_I386_SECREL7'  => 13,
		'IMAGE_REL_I386_REL32'    => 20,
	);

	

	

	private static $_IMAGE_SECTION_HEADER_Characteristics_L = array(
		
		
		
		
		'IMAGE_SCN_TYPE_NO_PAD' => 8,
		
		
		'IMAGE_SCN_CNT_CODE'    => 32,
		
		'IMAGE_SCN_CNT_INITIALIZED_DATA' => 64,
		
		'IMAGE_SCN_CNT_UNINITIALIZED_DATA' => 128,
		
		'IMAGE_SCN_LNK_OTHER' => 256,
		
		'IMAGE_SCN_LNK_INFO'  => 512,
		
		
		'IMAGE_SCN_LNK_REMOVE'=> 2048,
		
		'IMAGE_SCN_LNK_COMDAT'=> 4096,
		
		
		'IMAGE_SCN_NO_DEFER_SPEC_EXC' => 16384,
		
		'IMAGE_SCN_GPREL' => 32768,
	);


	
	private static $_IMAGE_SECTION_HEADER_Characteristics_H = array(

		
		
		'IMAGE_SCN_MEM_PURGEABLE' => 2,
		
		'IMAGE_SCN_MEM_LOCKED' => 4,
		
		'IMAGE_SCN_MEM_PRELOAD' => 8,
		
		'IMAGE_SCN_ALIGN_1BYTES' => 16,
		
		'IMAGE_SCN_ALIGN_2BYTES' => 32,
		
		'IMAGE_SCN_ALIGN_4BYTES' => 48,
		
		'IMAGE_SCN_ALIGN_8BYTES' => 64,
		
		'IMAGE_SCN_ALIGN_16BYTES'=> 80,
		
		'IMAGE_SCN_ALIGN_32BYTES'=> 96,
		
		'IMAGE_SCN_ALIGN_64BYTES'=> 112,
		
		'IMAGE_SCN_ALIGN_128BYTES'=>128,
		
		'IMAGE_SCN_ALIGN_256BYTES'=>144,
		
		'IMAGE_SCN_ALIGN_512BYTES'=>160,
		
		'IMAGE_SCN_ALIGN_1024BYTES'=>176,
		
		'IMAGE_SCN_ALIGN_2048BYTES'=>192,
		
		'IMAGE_SCN_ALIGN_4096BYTES'=>208,
		
		'IMAGE_SCN_ALIGN_8192BYTES'=>224,
		
		'IMAGE_SCN_LNK_NRELOC_OVFL'=>256,
		
		'IMAGE_SCN_MEM_DISCARDABLE'=>512,
		
		'IMAGE_SCN_MEM_NOT_CACHED' =>1024,
		
		'IMAGE_SCN_MEM_NOT_PAGED'  =>2048,
		
		'IMAGE_SCN_MEM_SHARED'     =>4096,
		
		'IMAGE_SCN_MEM_EXECUTE'    =>8192,
		
		'IMAGE_SCN_MEM_READ'       =>16384,
		
		'IMAGE_SCN_MEM_WRITE'      =>32768,
		
	);
	
	private static $_IMAGE_SECTION_HEADER_Characteristics_H_Align = array(
		8192 => 224,
		4096 => 208,
		2048 => 192,
		1024 => 176,
		512  => 160,
		256  => 144,
		128  => 128,
		64   => 112,
		32   => 96,
		16   => 80,
		8    => 64,
		4    => 48,
		2    => 32,
		1    => 16,
	);

	private static $_IMAGE_FILE_HEADER_MACHINE = array(
		'0000' => 'IMAGE_FILE_MACHINE_UNKNOWN',  
		'01d3' => 'IMAGE_FILE_MACHINE_AM33',  
		'8664' => 'IMAGE_FILE_MACHINE_AMD64',  
		'01c0' => 'IMAGE_FILE_MACHINE_ARM',  
		'0ebc' => 'IMAGE_FILE_MACHINE_EBC',  
		'014c' => 'IMAGE_FILE_MACHINE_I386',  
		'0200' => 'IMAGE_FILE_MACHINE_IA64',  
		'9041' => 'IMAGE_FILE_MACHINE_M32R',  
		'0266' => 'IMAGE_FILE_MACHINE_MIPS16',  
		'0366' => 'IMAGE_FILE_MACHINE_MIPSFPU',  
		'0466' => 'IMAGE_FILE_MACHINE_MIPSFPU16',  
		'01f0' => 'IMAGE_FILE_MACHINE_POWERPC',  
		'01f1' => 'IMAGE_FILE_MACHINE_POWERPCFP',  
		'0166' => 'IMAGE_FILE_MACHINE_R4000',  
		'01a2' => 'IMAGE_FILE_MACHINE_SH3',  
		'01a3' => 'IMAGE_FILE_MACHINE_SH3DSP',  
		'01a6' => 'IMAGE_FILE_MACHINE_SH4',  
		'01a8' => 'IMAGE_FILE_MACHINE_SH5',  
		'01c2' => 'IMAGE_FILE_MACHINE_THUMB',  
		'0169' => 'IMAGE_FILE_MACHINE_WCEMIPSV2',
	);
    
    
    

	public static function in_file_format(){

		global $language;
		global $myTables;
		global $my_params;
		global $buf;
		global $preprocess_sec_name;

		
		$CodeSectionArray = array();
		$RelocArray = array();
		
		
		$myTables = array(); 
		if (!self::analysis_coff($buf,$language,$myTables,$my_params['echo'])){
			GeneralFunc::LogInsert('analysis coff fail.');
			
			
		}
	}


	

	
	


	
	
	
	
	
	
	
	
	
	private static function analysis_coff($buf,$language,&$ret,$DebugShow = false){
		
		$lpObj = 0;
		$machine = self::get_hex_2_dec($buf,$lpObj,2); 
		$lpObj += 2;
		if (isset(self::$_IMAGE_FILE_HEADER_MACHINE[$machine])){
			if (self::$_IMAGE_FILE_HEADER_MACHINE[$machine] != 'IMAGE_FILE_MACHINE_I386'){
				GeneralFunc::LogInsert($language['not_support_Machine'].': '.self::$_IMAGE_FILE_HEADER_MACHINE[$machine]);
				return false;
			}
		}else{
			GeneralFunc::LogInsert($language['illegal_support_Machine']);
			return false;
		}

		$numberofsections  = self::get_hex_2_dec($buf,$lpObj,2,true);
		$lpObj += 2;
		
		
		$lpObj += 4;
		
		
		
		

		$PointerToSymbolTable  = self::get_hex_2_dec($buf,$lpObj,4,true);
		$lpObj += 4;
		

		$NumberOfSymbols  = self::get_hex_2_dec($buf,$lpObj,4,true);
		$lpObj += 4;
		

		

		$PointToStringTable = $PointerToSymbolTable + $NumberOfSymbols * (8+4+2+2+1+1);
		

		$SizeOfStringTable = self::get_hex_2_dec($buf,$PointToStringTable,4,true);
		
		

		$SizeOfOptionalHeader  = self::get_hex_2_dec($buf,$lpObj,2,true);
		$lpObj += 2;
		
		if ($SizeOfOptionalHeader != 0){
			GeneralFunc::LogInsert($language['illegal_opt_head_size']);
			return false;
		}

		
		$lpObj += 2;
		
		$SecNum = 1;                     
		for (;$numberofsections;$numberofsections --){
			$sec_name = substr($buf,$lpObj,8);
			$lpObj += 8;
			
			$VirtualSize = self::get_hex_2_dec($buf,$lpObj,4);
			$lpObj += 4;
			$VirtualAddress = self::get_hex_2_dec($buf,$lpObj,4);
			$lpObj += 4;

			$tmp = $lpObj; 
			$SizeOfRawData = self::get_hex_2_dec($buf,$lpObj,4,true);
			$lpObj += 4;
			$PointerToRawData = self::get_hex_2_dec($buf,$lpObj,4,true);
			$lpObj += 4;
			$PointerToRelocation = self::get_hex_2_dec($buf,$lpObj,4,true);
			$lpObj += 4;
			$PointerToLinenumber = self::get_hex_2_dec($buf,$lpObj,4);
			$lpObj += 4;
			$NumberOfRelocation = self::get_hex_2_dec($buf,$lpObj,2,true);
			$lpObj += 2;
			$NumberOfLinenumbers = self::get_hex_2_dec($buf,$lpObj,2,true);
			$lpObj += 2;
			$Characteristics = self::get_hex_2_dec($buf,$lpObj,4);
			$tmp_Characteristics_L = self::get_hex_2_dec($buf,$lpObj,2,true); 
			$lpObj += 2;
			$tmp_Characteristics_H = self::get_hex_2_dec($buf,$lpObj,2,true); 
			$lpObj += 2;
			
			
			foreach (self::$_IMAGE_SECTION_HEADER_Characteristics_H as $key => $value){
				if ($value == ($tmp_Characteristics_H & $value)){
					
				}
			}
			foreach (self::$_IMAGE_SECTION_HEADER_Characteristics_L as $key => $value){
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
									$reloc_VirtualAddress   = self::get_hex_2_dec($buf,$c_relocation,4,true);
									$c_relocation += 4;
									$reloc_SymbolTableIndex = self::get_hex_2_dec($buf,$c_relocation,4,true);
									$c_relocation += 4;
									$reloc_Type             = self::get_hex_2_dec($buf,$c_relocation,2,true);
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
					$AuxiliaryTableArray[$record_aux][$auxTable]['Length'] = self::get_hex_2_dec($buf,$lpObj,4,true);
					$lpObj += 4; 
					$AuxiliaryTableArray[$record_aux][$auxTable]['NumberOfRelocations'] = self::get_hex_2_dec($buf,$lpObj,2,true);
					$lpObj += 2; 
					$AuxiliaryTableArray[$record_aux][$auxTable]['NumberOfLinenumbers'] = self::get_hex_2_dec($buf,$lpObj,2,true);
					$lpObj += 2; 
					$AuxiliaryTableArray[$record_aux][$auxTable]['CheckSum'] = self::get_hex_2_dec($buf,$lpObj,4);
					$lpObj += 4;
					$AuxiliaryTableArray[$record_aux][$auxTable]['Number'] = self::get_hex_2_dec($buf,$lpObj,2,true);
					$lpObj += 2;  
					$AuxiliaryTableArray[$record_aux][$auxTable]['Selection'] = self::get_hex_2_dec($buf,$lpObj,1,true);
					$lpObj += 1;
					$lpObj += 3;
				}else{
					

					
					
					$tmp = self::get_hex_2_dec($buf,$lpObj,4);
					$lpObj += 4;
					
					$tmp = self::get_hex_2_dec($buf,$lpObj,2);
					$lpObj += 2;
					
					$tmp = self::get_hex_2_dec($buf,$lpObj,2);
					$lpObj += 2;
					
					$tmp = self::get_hex_2_dec($buf,$lpObj,4);
					$lpObj += 4;
					
					$tmp = self::get_hex_2_dec($buf,$lpObj,2);
					$lpObj += 2;
					
					$tmp = self::get_hex_2_dec($buf,$lpObj,1);
					$lpObj += 1;
					
					$tmp = self::get_hex_2_dec($buf,$lpObj,3);
					$lpObj += 3;
					
				}
				
			}else{
				$record_aux = false; 
				$c_addr = $lpObj;
				$tmp = self::get_hex_2_dec($buf,$lpObj,4,true);
				if ($tmp == 0){
					$lpObj += 4;
					$name_offset = self::get_hex_2_dec($buf,$lpObj,4,true);
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
				
				
				
				$Value = self::get_hex_2_dec($buf,$lpObj,4);
				$Value1 = self::get_hex_2_dec($buf,$lpObj,4,true);
				$lpObj += 4;
				
				$SectionNumber = self::get_hex_2_dec($buf,$lpObj,2,true);
				$lpObj += 2;
				
				$Type = self::get_hex_2_dec($buf,$lpObj,2);
				$lpObj += 2;
				
				
				
				
				$StorageClass = self::get_hex_2_dec($buf,$lpObj,1,true);
				$lpObj += 1;
				
				
				
				
				
				
				
				$NumberOfAuxSymbols = self::get_hex_2_dec($buf,$lpObj,1,true);
				$lpObj += 1;
				
				
				
				$auxTable = $NumberOfAuxSymbols;
				if ($auxTable > 0){
					if (self::$_StorageClasses['IMAGE_SYM_CLASS_FILE'] == $StorageClass){
						$auxType = 1;
					}else{
						$auxType = $name;
					}
				}
				
				
				
				
				
				
				
				
				
				
				
				
				
				if ((self::$_StorageClasses['IMAGE_SYM_CLASS_STATIC'] == $StorageClass) && (0 == $Value1)){ 
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
			GeneralFunc::LogInsert($language['too_much_sections']);
			return false;
		}

		if (count($SymbalTableArray) != $count){
			GeneralFunc::LogInsert($language['not_same_code_sym_array'],2);
		}

		
		$ret['CodeSectionArray']    = $CodeSectionArray;
		$ret['RelocArray']          = $RelocArray;
		$ret['SymbalTableArray']    = $SymbalTableArray;
		$ret['AuxiliaryTableArray'] = $AuxiliaryTableArray;
	  
		return true;
	}

	
	
	private static function get_hex_2_dec($buf,$lpBuf,$size,$dec = false){
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

	
	
	

	public static function out_file_buff_head($sec){
		return 'dd sec_'."$sec".'_end - sec_'."$sec".'_start';
	}

	public static function out_file_format_gen(){
		global $binary_filename;
		global $newCodeSection;
		global $CodeSectionArray;
		global $reloc_info_2_rewrite_table;
		global $non_null_labels;
		global $obj_filename;


			
			$binary_file_buf = file_get_contents($binary_filename);
			self::get_contents_from_result_binary($binary_file_buf,$CodeSectionArray,$reloc_info_2_rewrite_table,$newCodeSection,$non_null_labels); 
			
			$handle = fopen($obj_filename,'rb');
			if ($handle){
				$buf = fread($handle,filesize($obj_filename));
				fclose($handle);
			}else{
				GeneralFunc::LogInsert("fail to open original obj file: ".$obj_filename);
			}
			
			
			self::build_final_obj($buf,$binary_file_buf,$newCodeSection,$CodeSectionArray);
	}

	public static function out_file_gen_name(){
		global $out_file;
		return "$out_file".'.bin';
	}

	
	
	
	
	private static function build_final_obj($buf,$binary_file_buf,$newCodeSection,$CodeSectionArray){
		
		

		
		
		$c_lp = strlen($buf);
		
		foreach ($newCodeSection as $a => $b){        
			$align = 1;
			foreach (self::$_IMAGE_SECTION_HEADER_Characteristics_H_Align as $c => $d){
				if ($d == ($CodeSectionArray[$a]['Characteristics_H'] & $d)){
					$align = $c;
					break;
				}
			}
			
			$buf .= substr($binary_file_buf,$newCodeSection[$a]['PointerToRelocation'],$newCodeSection[$a]['NumberOfRelocation'] * (4 + 4 + 2));
			$buf .= substr($binary_file_buf,$newCodeSection[$a]['addr'],$newCodeSection[$a]['size']);
			
			
			$push_size_for_align = $align - $newCodeSection[$a]['size'] % $align;
			if ($push_size_for_align != $align){
				$newCodeSection[$a]['size'] += $push_size_for_align; 
				for (;$push_size_for_align > 0;$push_size_for_align --){
					$buf .= "\xcc"; 
				}	
			}

			
			self::hex_write($buf,$CodeSectionArray[$a]['base'] + 4 + 4,$c_lp);  
			$c_lp += $newCodeSection[$a]['NumberOfRelocation'] * (4 + 4 + 2);
			
			
			
			self::hex_write($buf,$CodeSectionArray[$a]['base'] + 4 + 4 + 4 + 4,$newCodeSection[$a]['NumberOfRelocation'],2);
			self::hex_write($buf,$CodeSectionArray[$a]['base'],$newCodeSection[$a]['size']);      
			
			self::hex_write($buf,$CodeSectionArray[$a]['base'] + 4,$c_lp);  

			
			
			$c_lp += $newCodeSection[$a]['size'];
		}    

		
		global $outputfile;
		file_put_contents($outputfile,$buf);

	  
	}


	
	
	
	

	private static function get_contents_from_result_binary($binary_file_buf,$CodeSectionArray,$reloc_info_2_rewrite_table,&$newCodeSection,$non_null_labels){
		$c_lp = 0; 
		foreach ($CodeSectionArray as $a => $b){
			$size = self::get_hex_2_dec($binary_file_buf,$c_lp,4,true);

			$c_lp += 4;
			$NumberOfRelocation = 0;
			$rewrite_rel32 = array();
			$newCodeSection[$a]['PointerToRelocation'] = $c_lp;		
			if (is_array($reloc_info_2_rewrite_table[$a])){
				$NumberOfRelocation = count($reloc_info_2_rewrite_table[$a]);
				foreach ($reloc_info_2_rewrite_table[$a] as $c => $d){
					$c_lp += 4 + 4 + 2;
					if ($non_null_labels[$tmp[2]][$tmp[3]][$tmp[4]]){ 
						$rewrite_rel32[$rva] = $non_null_labels[$tmp[2]][$tmp[3]][$tmp[4]];
					}
				}
			}
			$newCodeSection[$a]['addr'] = $c_lp;
			$newCodeSection[$a]['size'] = $size;
			$newCodeSection[$a]['NumberOfRelocation']  = $NumberOfRelocation;
			
			foreach ($rewrite_rel32 as $z => $y){	
				
				$y = substr('00000000',0,8 - strlen($y)).$y;
				
				$binary_file_buf[$c_lp+$z + 3] = pack("H*",substr($y,0,2));
				$binary_file_buf[$c_lp+$z + 2] = pack("H*",substr($y,2,2));
				$binary_file_buf[$c_lp+$z + 1] = pack("H*",substr($y,4,2));
				$binary_file_buf[$c_lp+$z]     = pack("H*",substr($y,6,2));
			}
			$c_lp += $size;
		}   

		return true;
	} 


	
	
	
	
	private static function hex_write(&$buf,$lp,$contents,$bits = 4){
		if (!$isHex){
			$tmp = '%0'.($bits*2).'x';
			$y = sprintf($tmp,$contents);
		}
		if ($bits == 4){
			$buf[$lp + 3] = pack("H*",substr($y,0,2));
			$buf[$lp + 2] = pack("H*",substr($y,2,2));
			$buf[$lp + 1] = pack("H*",substr($y,4,2));
			$buf[$lp]     = pack("H*",substr($y,6,2));
			return true;
		}elseif ($bits == 2){
			$buf[$lp + 1] = pack("H*",substr($y,0,2));
			$buf[$lp]     = pack("H*",substr($y,2,2));	
		}
		return false;
	}

}