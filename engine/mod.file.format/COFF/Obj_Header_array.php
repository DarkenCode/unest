<?php





$StorageClassesDescription = array (
    'IMAGE_SYM_CLASS_END_OF_FUNCTION' => '��ʾ������β��������ţ����ڵ��ԡ�',
    'IMAGE_SYM_CLASS_NULL' => 'δ������洢���',
    'IMAGE_SYM_CLASS_AUTOMATIC' => '�Զ�����ջ��������Value��ָ���˱�����ջ֡�е�ƫ�ơ�',
    'IMAGE_SYM_CLASS_EXTERNAL' => 'Microsoft�Ĺ���ʹ�ô�ֵ����ʾ�ⲿ���š����SectionNumber��Ϊ0��IMAGE_SYM_UNDEFINED������ôValue�������С�����SectionNumber ��Ϊ0����ôValue��������е�ƫ�ơ�',
    'IMAGE_SYM_CLASS_STATIC' => '�����ڽ��е�ƫ�ơ����Value��Ϊ0����ô�˷��ű�ʾ������',
    'IMAGE_SYM_CLASS_REGISTER' => '�Ĵ���������Value������Ĵ�����š�',
    'IMAGE_SYM_CLASS_EXTERNAL_DEF' => '���ⲿ����ķ��š�',
    'IMAGE_SYM_CLASS_LABEL' => 'ģ���ж���Ĵ����š�Value������˷����ڽ��е�ƫ�ơ�',
    'IMAGE_SYM_CLASS_UNDEFINED_LABEL' => '���õ�δ����Ĵ����š�',
    'IMAGE_SYM_CLASS_MEMBER_OF_STRUCT' => '�ṹ���Ա��Value��ָ���ǵڼ�����Ա��',
    'IMAGE_SYM_CLASS_ARGUMENT' => '��������ʽ�������βΣ���Value��ָ���ǵڼ���������',
    'IMAGE_SYM_CLASS_STRUCT_TAG' => '�ṹ������',
    'IMAGE_SYM_CLASS_MEMBER_OF_UNION' => '�������Ա��Value��ָ���ǵڼ�����Ա��',
    'IMAGE_SYM_CLASS_UNION_TAG' => '����������',
    'IMAGE_SYM_CLASS_TYPE_DEFINITION' => 'Typedef�',
    'IMAGE_SYM_CLASS_UNDEFINED_STATIC' => '��̬����������',
    'IMAGE_SYM_CLASS_ENUM_TAG' => 'ö����������',
    'IMAGE_SYM_CLASS_MEMBER_OF_ENUM' => 'ö�����ͳ�Ա��Value��ָ���ǵڼ�����Ա��',
    'IMAGE_SYM_CLASS_REGISTER_PARAM' => '�Ĵ���������',
    'IMAGE_SYM_CLASS_BIT_FIELD' => 'λ��Value��ָ����λ���еĵڼ���λ��',
    'IMAGE_SYM_CLASS_BLOCK' => ' .bb��beginning of block���鿪ͷ) ��.eb��¼��end of block�����β����Value���Ǵ���λ�ã�����һ�����ض�λ�ĵ�ַ��',
    'IMAGE_SYM_CLASS_FUNCTION' => 'Microsoft�Ĺ����ô�ֵ����ʾ���庯����Χ�ķ��ż�¼����Щ���ż�¼�ֱ��ǣ�.bf��begin function��������ͷ����.ef��end function��������β���Լ�.lf��lines in function�������е��У�������.lf ��¼��˵��Value�������Դ�����д˺�����ռ������������.ef��¼��˵��Value������˺�������Ĵ�С��',
    'IMAGE_SYM_CLASS_END_OF_STRUCT' => '�ṹ��ĩβ��',
    'IMAGE_SYM_CLASS_FILE' => 'Microsoft�Ĺ����Լ���ͳCOFF��ʽ��ʹ�ô�ֵ����ʾԴ�ļ����ż�¼�����ַ��ű��¼������Ÿ����ļ����ĸ������ű��¼��',
    'IMAGE_SYM_CLASS_SECTION' => '�ڵĶ��壨Microsoft�Ĺ���ʹ��STATIC�洢�����棩��',
    'IMAGE_SYM_CLASS_WEAK_EXTERNAL' => '���ⲿ���š�Ҫ��ȡ������Ϣ����ο�5.5.3�ڡ��������ű��¼��ʽ֮�������ⲿ���š���',
    'IMAGE_SYM_CLASS_CLR_TOKEN' => '��ʾCLR�Ǻŵķ��š���������������Ǻŵ�ʮ������ֵ��ASCII���ʾ��Ҫ��ȡ������Ϣ����ο�5.5.7�ڡ�CLR�ǺŶ��塱��',
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