<?php





$StorageClassesDescription = array (
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




$StorageClasses = array (
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





$Relocation_type = array (
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





$IMAGE_SECTION_HEADER_Characteristics_L = array(
    
    
    
    
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



$IMAGE_SECTION_HEADER_Characteristics_H = array(

    
    
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

$IMAGE_SECTION_HEADER_Characteristics_H_Align = array(
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

$IMAGE_FILE_HEADER_MACHINE = array(
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



?>