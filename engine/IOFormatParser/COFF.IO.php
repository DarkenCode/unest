<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}

class IOFormatParser{
    
	//
	// Storage classes. 中文 描述
	//

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

	//
	// Storage classes.
	//
	private static $_StorageClasses = array (
		'IMAGE_SYM_CLASS_END_OF_FUNCTION' => -1,//     (BYTE )-1
		'IMAGE_SYM_CLASS_NULL'            => 0,//                0x0000
		'IMAGE_SYM_CLASS_AUTOMATIC'       => 1,//           0x0001
		'IMAGE_SYM_CLASS_EXTERNAL'        => 2,//            0x0002
		'IMAGE_SYM_CLASS_STATIC'          => 3,//              0x0003
		'IMAGE_SYM_CLASS_REGISTER'        => 4,//            0x0004
		'IMAGE_SYM_CLASS_EXTERNAL_DEF'    => 5,//        0x0005
		'IMAGE_SYM_CLASS_LABEL'           => 6,//               0x0006
		'IMAGE_SYM_CLASS_UNDEFINED_LABEL' => 7,//     0x0007
		'IMAGE_SYM_CLASS_MEMBER_OF_STRUCT'=> 8,//    0x0008
		'IMAGE_SYM_CLASS_ARGUMENT'        => 9,//            0x0009
		'IMAGE_SYM_CLASS_STRUCT_TAG'      => 10,//          0x000A
		'IMAGE_SYM_CLASS_MEMBER_OF_UNION' => 11,//     0x000B
		'IMAGE_SYM_CLASS_UNION_TAG'       => 12,//           0x000C
		'IMAGE_SYM_CLASS_TYPE_DEFINITION' => 13,//     0x000D
		'IMAGE_SYM_CLASS_UNDEFINED_STATIC'=> 14,//    0x000E
		'IMAGE_SYM_CLASS_ENUM_TAG'        => 15,//            0x000F
		'IMAGE_SYM_CLASS_MEMBER_OF_ENUM'  => 16,//      0x0010
		'IMAGE_SYM_CLASS_REGISTER_PARAM'  => 17,//      0x0011
		'IMAGE_SYM_CLASS_BIT_FIELD'       => 18,//           0x0012

		'IMAGE_SYM_CLASS_FAR_EXTERNAL'    => 68,//       0x0044  //

		'IMAGE_SYM_CLASS_BLOCK'           => 100,//               0x0064
		'IMAGE_SYM_CLASS_FUNCTION'        => 101,//   0x0065
		'IMAGE_SYM_CLASS_END_OF_STRUCT'   => 102,//       0x0066
		'IMAGE_SYM_CLASS_FILE'            => 103,//        0x0067
	// new
		'IMAGE_SYM_CLASS_SECTION'         => 104,//             0x0068
		'IMAGE_SYM_CLASS_WEAK_EXTERNAL'   => 105,//       0x0069

		'IMAGE_SYM_CLASS_CLR_TOKEN'       => 107,//           0x006B
	);


	//
	// I386 relocation types.
	//
	private static $_Relocation_type = array (
		'IMAGE_REL_I386_ABSOLUTE' => 0,//0000;   //重定位被忽略。
		'IMAGE_REL_I386_DIR16'    => 1,//0x0001  // 不支持。 Direct 16-bit reference to the symbols virtual address
		'IMAGE_REL_I386_REL16'    => 2,//0x0002  // 不支持。PC-relative 16-bit reference to the symbols virtual address
		'IMAGE_REL_I386_DIR32'    => 6,//0x0006  // 重定位目标的32位VA。Direct 32-bit reference to the symbols virtual address
		'IMAGE_REL_I386_DIR32NB'  => 7,//0x0007  // 重定位目标的32位RVA。Direct 32-bit reference to the symbols virtual address, base not included
		'IMAGE_REL_I386_SEG12'    => 9,//0x0009  // 不支持。 Direct 16-bit reference to the segment-selector bits of a 32-bit virtual address
		'IMAGE_REL_I386_SECTION'  => 10,//0x000A     包含重定位目标的节的16位索引。用于支持调试信息。
		'IMAGE_REL_I386_SECREL'   => 11,//0x000B     重定位目标相对于它所在节开头的32位偏移。用于支持调试信息和静态线程局部存储。
		'IMAGE_REL_I386_TOKEN'    => 12,//0x000C  // CLR记号。 clr token
		'IMAGE_REL_I386_SECREL7'  => 13,//0x000D  // 相对于重定位目标所在节基地址的7位偏移。 7 bit offset from base of section containing target
		'IMAGE_REL_I386_REL32'    => 20,//0x0014  // 重定位目标的32位相对偏移。用于支持x86的相对分支和CALL指令。 PC-relative 32-bit reference to the symbols virtual address
	);

	//php 做大数运算很麻烦，分成高低位处理

	//IMAGE_SECTION_HEADER_Characteristics 低位

	private static $_IMAGE_SECTION_HEADER_Characteristics_L = array(
		//0x00000000    Reserved.
		//0x00000001    Reserved.
		//0x00000002    Reserved.
		//0x00000004    Reserved.
		'IMAGE_SCN_TYPE_NO_PAD' => 8,//0x00000008,
		//The section should not be padded to the next boundary. This flag is obsolete and is replaced by IMAGE_SCN_ALIGN_1BYTES.
		//0x00000010    Reserved.
		'IMAGE_SCN_CNT_CODE'    => 32,//0x00000020
		//The section contains executable code.
		'IMAGE_SCN_CNT_INITIALIZED_DATA' => 64,//0x00000040
		//The section contains initialized data.
		'IMAGE_SCN_CNT_UNINITIALIZED_DATA' => 128,//0x00000080
		//The section contains uninitialized data.
		'IMAGE_SCN_LNK_OTHER' => 256,//0x00000100
		//Reserved.
		'IMAGE_SCN_LNK_INFO'  => 512,//0x00000200
		//The section contains comments or other information. This is valid only for object files.
		//0x00000400    Reserved.
		'IMAGE_SCN_LNK_REMOVE'=> 2048,//0x00000800
		//The section will not become part of the image. This is valid only for object files.
		'IMAGE_SCN_LNK_COMDAT'=> 4096,//0x00001000
		//The section contains COMDAT data. This is valid only for object files.
		//0x00002000    Reserved.
		'IMAGE_SCN_NO_DEFER_SPEC_EXC' => 16384,//0x00004000
		//Reset speculative exceptions handling bits in the TLB entries for this section.
		'IMAGE_SCN_GPREL' => 32768,//0x00008000
	);


	//高位
	private static $_IMAGE_SECTION_HEADER_Characteristics_H = array(

		//The section contains data referenced through the global pointer.
		//0x00010000    Reserved.
		'IMAGE_SCN_MEM_PURGEABLE' => 2,//131072,//0x00020000
		//Reserved.
		'IMAGE_SCN_MEM_LOCKED' => 4,//262144,//0x00040000
		//Reserved.
		'IMAGE_SCN_MEM_PRELOAD' => 8,//524288,//0x00080000
		//Reserved.
		'IMAGE_SCN_ALIGN_1BYTES' => 16,//1048576,//0x00100000
		//Align data on a 1-byte boundary. This is valid only for object files.
		'IMAGE_SCN_ALIGN_2BYTES' => 32,//2097152,//0x00200000
		//Align data on a 2-byte boundary. This is valid only for object files.
		'IMAGE_SCN_ALIGN_4BYTES' => 48,//3145728,//0x00300000
		//Align data on a 4-byte boundary. This is valid only for object files.
		'IMAGE_SCN_ALIGN_8BYTES' => 64,//4194304,//0x00400000
		//Align data on a 8-byte boundary. This is valid only for object files.
		'IMAGE_SCN_ALIGN_16BYTES'=> 80,//5242880,//0x00500000
		//Align data on a 16-byte boundary. This is valid only for object files.
		'IMAGE_SCN_ALIGN_32BYTES'=> 96,//6291456,//0x00600000
		//Align data on a 32-byte boundary. This is valid only for object files.
		'IMAGE_SCN_ALIGN_64BYTES'=> 112,//7340032,//0x00700000
		//Align data on a 64-byte boundary. This is valid only for object files.
		'IMAGE_SCN_ALIGN_128BYTES'=>128,//8388608,//0x00800000
		//Align data on a 128-byte boundary. This is valid only for object files.
		'IMAGE_SCN_ALIGN_256BYTES'=>144,//9437184,//0x00900000
		//Align data on a 256-byte boundary. This is valid only for object files.
		'IMAGE_SCN_ALIGN_512BYTES'=>160,//10485760,//0x00A00000
		//Align data on a 512-byte boundary. This is valid only for object files.
		'IMAGE_SCN_ALIGN_1024BYTES'=>176,//11534336,//0x00B00000
		//Align data on a 1024-byte boundary. This is valid only for object files.
		'IMAGE_SCN_ALIGN_2048BYTES'=>192,//12582912,//0x00C00000
		//Align data on a 2048-byte boundary. This is valid only for object files.
		'IMAGE_SCN_ALIGN_4096BYTES'=>208,//13631488,//0x00D00000
		//Align data on a 4096-byte boundary. This is valid only for object files.
		'IMAGE_SCN_ALIGN_8192BYTES'=>224,//14680064,//0x00E00000
		//Align data on a 8192-byte boundary. This is valid only for object files.
		'IMAGE_SCN_LNK_NRELOC_OVFL'=>256,//16777216,//0x01000000
		//The section contains extended relocations. The count of relocations for the section exceeds the 16 bits that is reserved for it in the section header. If the NumberOfRelocations field in the section header is 0xffff, the actual relocation count is stored in the VirtualAddress field of the first relocation. It is an error if IMAGE_SCN_LNK_NRELOC_OVFL is set and there are fewer than 0xffff relocations in the section.
		'IMAGE_SCN_MEM_DISCARDABLE'=>512,//33554432,  // 0x02000000
		//The section can be discarded as needed.
		'IMAGE_SCN_MEM_NOT_CACHED' =>1024,//67108864,  // 0x04000000
		//The section cannot be cached.
		'IMAGE_SCN_MEM_NOT_PAGED'  =>2048,//134217728, // 0x08000000
		//The section cannot be paged.
		'IMAGE_SCN_MEM_SHARED'     =>4096,//268435456, // 0x10000000
		//The section can be shared in memory.
		'IMAGE_SCN_MEM_EXECUTE'    =>8192,//536870912, // 0x20000000
		//The section can be executed as code.
		'IMAGE_SCN_MEM_READ'       =>16384,//1073741824,// 0x40000000
		//The section can be read.
		'IMAGE_SCN_MEM_WRITE'      =>32768,//2147483648,// 0x80000000
		//The section can be write 
	);
	//节表对齐 标准
	private static $_IMAGE_SECTION_HEADER_Characteristics_H_Align = array(
		8192 => 224,//'IMAGE_SCN_ALIGN_8192BYTES'=>224,//14680064,//0x00E00000
		4096 => 208,//'IMAGE_SCN_ALIGN_4096BYTES'=>208,//13631488,//0x00D00000
		2048 => 192,//'IMAGE_SCN_ALIGN_2048BYTES'=>192,//12582912,//0x00C00000
		1024 => 176,//'IMAGE_SCN_ALIGN_1024BYTES'=>176,//11534336,//0x00B00000
		512  => 160,//'IMAGE_SCN_ALIGN_512BYTES'=>160,//10485760,//0x00A00000
		256  => 144,//'IMAGE_SCN_ALIGN_256BYTES'=>144,//9437184,//0x00900000
		128  => 128,//'IMAGE_SCN_ALIGN_128BYTES'=>128,//8388608,//0x00800000
		64   => 112,//'IMAGE_SCN_ALIGN_64BYTES'=> 112,//7340032,//0x00700000
		32   => 96,//'IMAGE_SCN_ALIGN_32BYTES'=> 96,//6291456,//0x00600000
		16   => 80,//'IMAGE_SCN_ALIGN_16BYTES'=> 80,//5242880,//0x00500000
		8    => 64,//'IMAGE_SCN_ALIGN_8BYTES' => 64,//4194304,//0x00400000
		4    => 48,//'IMAGE_SCN_ALIGN_4BYTES' => 48,//3145728,//0x00300000
		2    => 32,//'IMAGE_SCN_ALIGN_2BYTES' => 32,//2097152,//0x00200000
		1    => 16,//'IMAGE_SCN_ALIGN_1BYTES' => 16,//1048576,//0x00100000        
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
    //////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////

	public static function in_file_format(){

		global $language;
		global $myTables;
		global $my_params;
		global $buf;
		global $preprocess_sec_name;

		//保存 代码段 的 节表位置 -> SizeOfRawData,PointerToRawData
		$CodeSectionArray = array();
		$RelocArray = array();
		
		//分析COFF格式，并获得需要的数组返回
		$myTables = array(); 
		if (!self::analysis_coff($buf,$language,$myTables,$my_params['echo'])){
			GeneralFunc::LogInsert('analysis coff fail.');
			//var_dump ($output);
			//exit;
		}
	}


	/******************************************/
	//用于 COFF 格式 解析 的 函数 集
	/////////////////////////////////////////////////////


	///////////////////////////////////////////
	//对COFF格式进行简单分析，为下一步处理做准备
	//
	//返回输出 $output
	//
	//成功返回 $Coff[需要被处理的节表数组]
	//
	//
	//
	private static function analysis_coff($buf,$language,&$ret,$DebugShow = false){
		
		$lpObj = 0;
		$machine = self::get_hex_2_dec($buf,$lpObj,2); //取Machine域
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
		//echo "<br>节表个数: $numberofsections <br>";
		//$timestamp  = get_hex_2_dec($buf,$lpObj,4,true);
		$lpObj += 4;
		//echo "<br>$timestamp<br>";
		//echo date("Y-m-d H:i:s", $timestamp) ; 
		//echo "<br>";
		//echo "<br>=================================================<br>";

		$PointerToSymbolTable  = self::get_hex_2_dec($buf,$lpObj,4,true);
		$lpObj += 4;
		//echo "<br>PointerToSymbolTable: $PointerToSymbolTable<br>";

		$NumberOfSymbols  = self::get_hex_2_dec($buf,$lpObj,4,true);
		$lpObj += 4;
		//echo "<br>NumberOfSymbols: $NumberOfSymbols<br>";

		//echo "<br>=================================================<br>";

		$PointToStringTable = $PointerToSymbolTable + $NumberOfSymbols * (8+4+2+2+1+1);
		//echo "<br>PointToStringTable: $PointToStringTable<br>";

		$SizeOfStringTable = self::get_hex_2_dec($buf,$PointToStringTable,4,true);
		//echo "<br>SizeOfStringTable: $SizeOfStringTable<br>";
		//echo "<br>=================================================<br>";

		$SizeOfOptionalHeader  = self::get_hex_2_dec($buf,$lpObj,2,true);
		$lpObj += 2;
		//echo "<br>SizeOfOptionalHeader: $SizeOfOptionalHeader<br>";
		if ($SizeOfOptionalHeader != 0){
			GeneralFunc::LogInsert($language['illegal_opt_head_size']);
			return false;
		}

		//$Characteristics = get_hex_2_dec($buf,$lpObj,2);
		$lpObj += 2;
		//echo "<br>Characteristics: $Characteristics<br>";
		$SecNum = 1;                     //段 编号(从1开始)
		for (;$numberofsections;$numberofsections --){
			$sec_name = substr($buf,$lpObj,8);
			$lpObj += 8;
			//echo "<tr><td>$SecNum</td><td>$sec_name</td>";
			$VirtualSize = self::get_hex_2_dec($buf,$lpObj,4);
			$lpObj += 4;
			$VirtualAddress = self::get_hex_2_dec($buf,$lpObj,4);
			$lpObj += 4;

			$tmp = $lpObj; //保留,如果本节为.code节表，需要记录本值
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
			$tmp_Characteristics_L = self::get_hex_2_dec($buf,$lpObj,2,true); //低位
			$lpObj += 2;
			$tmp_Characteristics_H = self::get_hex_2_dec($buf,$lpObj,2,true); //高位
			$lpObj += 2;
			
			//echo "<td>0x$VirtualSize</td><td>0x$VirtualAddress</td><td>$SizeOfRawData</td><td>$PointerToRawData</td><td>0x$PointerToRelocation</td><td>0x$PointerToLinenumber</td><td>$NumberOfRelocation</td><td>$NumberOfLinenumbers</td><td>0x$Characteristics";
			foreach (self::$_IMAGE_SECTION_HEADER_Characteristics_H as $key => $value){
				if ($value == ($tmp_Characteristics_H & $value)){
					//echo "  $key |";
				}
			}
			foreach (self::$_IMAGE_SECTION_HEADER_Characteristics_L as $key => $value){
				if ($value == ($tmp_Characteristics_L & $value)){
					//echo "  $key |";
					if ($key == 'IMAGE_SCN_CNT_CODE'){                        //代码节 属性
						if ($SizeOfRawData > 0){ //       长度 有效
							$CodeSectionArray[$SecNum]['base'] = $tmp;        //节表信息 基址
							$CodeSectionArray[$SecNum]['sec_name'] = trim($sec_name);
							$CodeSectionArray[$SecNum]['PointerToRawData'] = $PointerToRawData;
							$CodeSectionArray[$SecNum]['SizeOfRawData'] = $SizeOfRawData;
							$CodeSectionArray[$SecNum]['PointerToRelocation'] = $PointerToRelocation;
							$CodeSectionArray[$SecNum]['NumberOfRelocation'] = $NumberOfRelocation;
							$CodeSectionArray[$SecNum]['Characteristics_L'] = $tmp_Characteristics_L;
							$CodeSectionArray[$SecNum]['Characteristics_H'] = $tmp_Characteristics_H;

						  //  echo "</td>";    
						  //  echo "</tr>";

			
							if ($PointerToRelocation != 0){
								$c_relocation = $PointerToRelocation;
								for ($c_reloc_number = 1;$c_reloc_number <= $NumberOfRelocation;$c_reloc_number ++){
									$reloc_VirtualAddress   = self::get_hex_2_dec($buf,$c_relocation,4,true);
									$c_relocation += 4;
									$reloc_SymbolTableIndex = self::get_hex_2_dec($buf,$c_relocation,4,true);
									$c_relocation += 4;
									$reloc_Type             = self::get_hex_2_dec($buf,$c_relocation,2,true);
									$c_relocation += 2;
									//echo "<tr bgcolor=\"yellow\"><td></td><td>重定位 $c_reloc_number:</td><td>VirtualAddress:</td><td>$reloc_VirtualAddress</td><td>SymbolTableIndex:</td><td>$reloc_SymbolTableIndex</td><td>Type:</td><td>$reloc_Type";
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

		/*  符号表 */
		$SymbalTableArray = array();
		$symbalTableIndex = 0;
		//echo "<br><br>**************** symbalTable  一览 ************<br>";
		//echo "<table border = 1><tr><td>Index</td><td>Name</td><td>Value</td><td>SectionNumber</td><td>Type</td><td>StorageClass</td><td>NumberOfAuxSymbols</td><td>备注</td></tr>";

		$lpObj = $PointerToSymbolTable;
		$auxTable = 0; //辅助表
		$auxType  = 0; //辅助表类型
		for (;$NumberOfSymbols;$NumberOfSymbols --){
			if ($auxTable > 0){
				$auxTable --;
				if ($auxType == 1){ //.file
					//echo "<tr bgcolor=\"yellow\">";	
					//echo "<td>$symbalTableIndex</td>";		
					$name = substr($buf,$lpObj,8);
					$lpObj += 8;
					//echo "<td>$name</td>";
					$name = substr($buf,$lpObj,8);
					$lpObj += 8;
					//echo "<td>$name</td>";
					$name = substr($buf,$lpObj,2);
					$lpObj += 2;
					//echo "<td>$name</td>";	
				}elseif (false !== $record_aux){ //保留 接下来的辅助表)
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
					/* 辅助表 格式 
					1A000000 //节中数据的大小        
					0400 //节中重定位项的数目        
					0000 //节中行号信息项的数目        
					00000000 //公共数据的校验和，COMDAT节相关。        
					0000 //与此节相关的节在节表中的索引，COMDAT节相关。        
					00 //表示COMDAT选择方式的数字，COMDAT节相关。        
					000000 //未用   */
					//echo "<tr bgcolor=\"pink\">";
					//echo "<td>$symbalTableIndex</td>";			
					$tmp = self::get_hex_2_dec($buf,$lpObj,4);
					$lpObj += 4;
					//echo "<td>0x$tmp</td>";
					$tmp = self::get_hex_2_dec($buf,$lpObj,2);
					$lpObj += 2;
					//echo "<td>0x$tmp</td>";
					$tmp = self::get_hex_2_dec($buf,$lpObj,2);
					$lpObj += 2;
					//echo "<td>0x$tmp</td>";
					$tmp = self::get_hex_2_dec($buf,$lpObj,4);
					$lpObj += 4;
					//echo "<td>0x$tmp</td>";
					$tmp = self::get_hex_2_dec($buf,$lpObj,2);
					$lpObj += 2;
					//echo "<td>0x$tmp</td>";
					$tmp = self::get_hex_2_dec($buf,$lpObj,1);
					$lpObj += 1;
					//echo "<td>0x$tmp</td>";
					$tmp = self::get_hex_2_dec($buf,$lpObj,3);
					$lpObj += 3;
					//echo "<td>0x$tmp</td>";
				}
				//echo "</tr>";
			}else{
				$record_aux = false; //保留 接下来的辅助表
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
							//$name .= "<br>";
							$count = 0;
						}
					}while ($tmp != "\0");
				}else{
					$name = substr($buf,$lpObj,8);
					$lpObj += 8;
				}
				//echo "<tr>";
				//echo "<td>$symbalTableIndex</td>";
				//echo "<td>$name</td>"; 	
				$Value = self::get_hex_2_dec($buf,$lpObj,4);
				$Value1 = self::get_hex_2_dec($buf,$lpObj,4,true);
				$lpObj += 4;
				//echo "<td>0x$Value / $Value1</td>"; 
				$SectionNumber = self::get_hex_2_dec($buf,$lpObj,2,true);
				$lpObj += 2;
				//echo "<td>$SectionNumber</td>";
				$Type = self::get_hex_2_dec($buf,$lpObj,2);
				$lpObj += 2;
				//if ($Type == '0020'){
				//	$Type .= " [<font color=red>函数</font>]";
				//}
				//echo "<td>0x$Type</td>";
				$StorageClass = self::get_hex_2_dec($buf,$lpObj,1,true);
				$lpObj += 1;
				//echo "<td>$StorageClass";
				//foreach ($StorageClasses as $key => $value){
				//	if ($value == $StorageClass){
				//		echo "    <b>$key</b> : ".$StorageClassesDescription[$key]." |";
				//	}			
				//}
				//echo "</td>";
				$NumberOfAuxSymbols = self::get_hex_2_dec($buf,$lpObj,1,true);
				$lpObj += 1;
				//echo "<td>$NumberOfAuxSymbols</td>";	
				//echo "</tr>";
				//处理辅助表
				$auxTable = $NumberOfAuxSymbols;
				if ($auxTable > 0){
					if (self::$_StorageClasses['IMAGE_SYM_CLASS_FILE'] == $StorageClass){//('fffe' == $SectionNumber){//if (".file\0\0\0" == $name){
						$auxType = 1;
					}else{
						$auxType = $name;
					}
				}
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
				//                                                                                _
				//判断 是否为处理目标的 条件  【1】：符号存储类别 == IMAGE_SYM_CLASS_STATIC(3)     | 此符号代表 节名
				//                            【2】：如果Value域为0                               _|
				//                            【3】：SectionNumber在$CodeSectionArray[key]中存在  -  'IMAGE_SCN_CNT_CODE' 代码节属性
				//                            【4】：NumberOfAuxSymbols = 1 | 2                   -  有辅助节表(节定义) debug版本为 2 (后一个未知) ,release 为 1
				//                            【5】：节表名不为 ..$unest_disable (放弃, 留到后面过滤，这里收集所有的代码段)
				//获得                  symbalTableArray['SectionNumber'] struct (
				//                          'name' => '.txt$unest_xxx',   //备忘：混淆完成后节表名后缀后面变为(空|$1|$2)，让节表在最后链接时次序随机
				//                          'addr' => '', //偏移地址
				//                      )
				//                      
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				if ((self::$_StorageClasses['IMAGE_SYM_CLASS_STATIC'] == $StorageClass) && (0 == $Value1)){ // 此符号代表 节名
					if (isset($CodeSectionArray[$SectionNumber])){
						if ($NumberOfAuxSymbols){
							$SymbalTableArray[$SectionNumber]['name'] = trim($name);
							$SymbalTableArray[$SectionNumber]['base'] = $c_addr;
							$record_aux = $SectionNumber; //保留 接下来的辅助表
							//节表名 -> 传递
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
				if ($AuxiliaryTableArray[$a]){ //辅助表
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
		
	//exit;

		$count = count($CodeSectionArray);
		if ($count > 8190){
			GeneralFunc::LogInsert($language['too_much_sections']);
			return false;
		}

		if (count($SymbalTableArray) != $count){
			GeneralFunc::LogInsert($language['not_same_code_sym_array'],2);
		}

		//返回 4 个 表  $CodeSectionArray / $RelocArray / $SymbalTableArray / $AuxiliaryTableArray
		$ret['CodeSectionArray']    = $CodeSectionArray;
		$ret['RelocArray']          = $RelocArray;
		$ret['SymbalTableArray']    = $SymbalTableArray;
		$ret['AuxiliaryTableArray'] = $AuxiliaryTableArray;
	  
		return true;
	}

	///////////////////////////////////////////////////////////
	//指针处取指定长度字节并转换(大/小尾)为10进制(或16进制)返回
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

	//
	// 输出
	//

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


			//编译成功，开始解析
			$binary_file_buf = file_get_contents($binary_filename);
			self::get_contents_from_result_binary($binary_file_buf,$CodeSectionArray,$reloc_info_2_rewrite_table,$newCodeSection,$non_null_labels); 
			
			$handle = fopen($obj_filename,'rb');
			if ($handle){
				$buf = fread($handle,filesize($obj_filename));
				fclose($handle);
			}else{
				GeneralFunc::LogInsert("fail to open original obj file: ".$obj_filename);
			}
			
			//开始构建 最终的 obj 文件
			self::build_final_obj($buf,$binary_file_buf,$newCodeSection,$CodeSectionArray);
	}

	public static function out_file_gen_name(){
		global $out_file;
		return "$out_file".'.bin';
	}

	/////////////////////////////////////////////////////
	//   
	//    生成最终 obj 文件内容 
	//
	private static function build_final_obj($buf,$binary_file_buf,$newCodeSection,$CodeSectionArray){
		
		//global $IMAGE_SECTION_HEADER_Characteristics_H_Align;

		//把修改过的段内容(重定位表，数据) 放置在原文件末尾
		//！注：以后可以利用被丢弃的内容的位置
		$c_lp = strlen($buf);
		//echo "<br>$c_lp<br>";
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
			
			//计算为了节表对齐需要添加的空位
			$push_size_for_align = $align - $newCodeSection[$a]['size'] % $align;
			if ($push_size_for_align != $align){
				$newCodeSection[$a]['size'] += $push_size_for_align; 
				for (;$push_size_for_align > 0;$push_size_for_align --){
					$buf .= "\xcc"; //可用随机数
				}	
			}

			//$HEXpointToReloc  = sprintf("%08x",$c_lp);		
			self::hex_write($buf,$CodeSectionArray[$a]['base'] + 4 + 4,$c_lp);  
			$c_lp += $newCodeSection[$a]['NumberOfRelocation'] * (4 + 4 + 2);
			//$HEXpointToRaw    = sprintf("%08x",$c_lp);
			//$HEXnumberOfReloc = sprintf("%04x",$newCodeSection[$a]['NumberOfRelocation']);
			//$HEXsizeOfRaw     = sprintf("%08x",$newCodeSection[$a]['size']);
			self::hex_write($buf,$CodeSectionArray[$a]['base'] + 4 + 4 + 4 + 4,$newCodeSection[$a]['NumberOfRelocation'],2);
			self::hex_write($buf,$CodeSectionArray[$a]['base'],$newCodeSection[$a]['size']);      
			
			self::hex_write($buf,$CodeSectionArray[$a]['base'] + 4,$c_lp);  

			//echo "<br><br>$a<br>$HEXsizeOfRaw<br>$HEXpointToRaw<br>$HEXpointToReloc<br>$HEXnumberOfReloc";
			//var_dump ($myTables['CodeSectionArray'][$a]['base']); //-> sizeofRaw , PointToRaw, PointToReloc, 0 , NumberOfReloc(dw)
			$c_lp += $newCodeSection[$a]['size'];
		}    

		//file_put_contents("./tmp/fuck.bin",$buf);
		global $outputfile;
		file_put_contents($outputfile,$buf);

	  
	}


	/////////////////////////////////////////////////////
	//
	//    根据编译结果文件 提取信息  
	//

	private static function get_contents_from_result_binary($binary_file_buf,$CodeSectionArray,$reloc_info_2_rewrite_table,&$newCodeSection,$non_null_labels){
		$c_lp = 0; //指针
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
					if ($non_null_labels[$tmp[2]][$tmp[3]][$tmp[4]]){ //需要更新rel32标号位 数值
						$rewrite_rel32[$rva] = $non_null_labels[$tmp[2]][$tmp[3]][$tmp[4]];
					}
				}
			}
			$newCodeSection[$a]['addr'] = $c_lp;
			$newCodeSection[$a]['size'] = $size;
			$newCodeSection[$a]['NumberOfRelocation']  = $NumberOfRelocation;
			//如果有 跳转 标号 是非0 值，这里再赋值
			foreach ($rewrite_rel32 as $z => $y){	
				//为 $y 左侧 补足 0
				$y = substr('00000000',0,8 - strlen($y)).$y;
				//echo "<br> $y";
				$binary_file_buf[$c_lp+$z + 3] = pack("H*",substr($y,0,2));
				$binary_file_buf[$c_lp+$z + 2] = pack("H*",substr($y,2,2));
				$binary_file_buf[$c_lp+$z + 1] = pack("H*",substr($y,4,2));
				$binary_file_buf[$c_lp+$z]     = pack("H*",substr($y,6,2));
			}
			$c_lp += $size;
		}   

		return true;
	} 


	/////////////////////////////////////////////////////
	//16进制 写入 
	//$bits  4(dword) or 2(word)
	//
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