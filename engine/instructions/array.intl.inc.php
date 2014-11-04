<?php

//mem (sib) 影响指令长度
//$mem_effect_len_array[a][b][c]  = n;
//                      a: 首个寄存器存在为 1 ，不存在为 0                      例(大写部分): lea eax,[EAX+ecx*2+123]
//                      b: 第二寄存器不存在为 0, x1 or x2 为1 , x4 or x 8 为2   例(大写部分): lea eax,[eax+ECX*2+123]
//                      c: 整数部分位数 0 or 8 or 32
//                      n: 影响长度
//
// 另:第二寄存器 乘数如>= 4 且第一寄存器不存在，影响长度固定为 5 (最大)
//
$mem_effect_len_array[1][1][0]  = 1;
$mem_effect_len_array[1][1][8]  = 2;
$mem_effect_len_array[1][1][32] = 5;

$mem_effect_len_array[1][0][0]  = 0;
$mem_effect_len_array[1][0][8]  = 1;
$mem_effect_len_array[1][0][32] = 4;

$mem_effect_len_array[0][0][8]  = 4;
$mem_effect_len_array[0][0][32] = 4;

$mem_effect_len_array[0][1][0]  = 5;
$mem_effect_len_array[0][1][8]  = 2;
$mem_effect_len_array[0][1][32] = 5;

$mem_effect_len_array['max'] = 5; //默认max


//当段中有如下指令时无法处理，放弃整段
$can_not_deal_operation = array(

);


//定长跳转有range限制的
$range_limit_static_jmp = array(
    'LOOP'   => 127,
	'LOOPE'  => 127,
	'LOOPZ'  => 127,
	'LOOPNE' => 127,
	'LOOPNZ' => 127,

	'JCXZ'   => 127,
	'JECXZ'  => 127,
	'JRCXZ'  => 127,

    'JA'  => false,
	'JAE' => false,
	'JB'  => false,
	'JBE' => false,
	'JC'  => false,
	'JE'  => false,
	'JG'  => false,
	'JGE' => false,
	'JL'  => false,
	'JLE' => false,
	'JNA' => false,
	'JNAE'=> false,
	'JNB' => false,
	'JNBE'=> false,
	'JNC' => false,
	'JNE' => false,
	'JNG' => false,
	'JNGE'=> false,
	'JNL' => false,
	'JNLE'=> false,
	'JNO' => false,
	'JNP' => false,
	'JNS' => false,
	'JNZ' => false,
	'JO'  => false,
	'JP'  => false,
	'JPE' => false,
	'JPO' => false,
	'JS'  => false,
	'JZ'  => false,

	'JMP' => false,

);

//条件指令 集中
$my_cc = array(
    'JA'  => 'Jcc',
	'JAE' => 'Jcc',
	'JB'  => 'Jcc',
	'JBE' => 'Jcc',
	'JC'  => 'Jcc',
	'JE'  => 'Jcc',
	'JG'  => 'Jcc',
	'JGE' => 'Jcc',
	'JL'  => 'Jcc',
	'JLE' => 'Jcc',
	'JNA' => 'Jcc',
	'JNAE'=> 'Jcc',
	'JNB' => 'Jcc',
	'JNBE'=> 'Jcc',
	'JNC' => 'Jcc',
	'JNE' => 'Jcc',
	'JNG' => 'Jcc',
	'JNGE'=> 'Jcc',
	'JNL' => 'Jcc',
	'JNLE'=> 'Jcc',
	'JNO' => 'Jcc',
	'JNP' => 'Jcc',
	'JNS' => 'Jcc',
	'JNZ' => 'Jcc',
	'JO'  => 'Jcc',
	'JP'  => 'Jcc',
	'JPE' => 'Jcc',
	'JPO' => 'Jcc',
	'JS'  => 'Jcc',
	'JZ'  => 'Jcc',
	'JCXZ'   => 'Jcc',
	'JECXZ'  => 'Jcc',
	'JRCXZ'  => 'Jcc',

    'CMOVA'  => 'CMOVcc',
	'CMOVAE' => 'CMOVcc',
	'CMOVB'  => 'CMOVcc',
	'CMOVBE' => 'CMOVcc',
	'CMOVC'  => 'CMOVcc',
	'CMOVE'  => 'CMOVcc',
	'CMOVG'  => 'CMOVcc',
	'CMOVGE' => 'CMOVcc',
	'CMOVL'  => 'CMOVcc',
	'CMOVLE' => 'CMOVcc',
	'CMOVNA' => 'CMOVcc',
	'CMOVNAE'=> 'CMOVcc',
	'CMOVNB' => 'CMOVcc',
	'CMOVNBE'=> 'CMOVcc',
	'CMOVNC' => 'CMOVcc',
	'CMOVNE' => 'CMOVcc',
	'CMOVNG' => 'CMOVcc',
	'CMOVNGE'=> 'CMOVcc',
	'CMOVNL' => 'CMOVcc',
	'CMOVNLE'=> 'CMOVcc',
	'CMOVNO' => 'CMOVcc',
	'CMOVNP' => 'CMOVcc',
	'CMOVNS' => 'CMOVcc',
	'CMOVNZ' => 'CMOVcc',
	'CMOVO'  => 'CMOVcc',
	'CMOVP'  => 'CMOVcc',
	'CMOVPE' => 'CMOVcc',
	'CMOVPO' => 'CMOVcc',
	'CMOVS'  => 'CMOVcc',
	'CMOVZ'  => 'CMOVcc',

    'SETA'  => 'SETcc',
	'SETAE' => 'SETcc',
	'SETB'  => 'SETcc',
	'SETBE' => 'SETcc',
	'SETC'  => 'SETcc',
	'SETE'  => 'SETcc',
	'SETG'  => 'SETcc',
	'SETGE' => 'SETcc',
	'SETL'  => 'SETcc',
	'SETLE' => 'SETcc',
	'SETNA' => 'SETcc',
	'SETNAE'=> 'SETcc',
	'SETNB' => 'SETcc',
	'SETNBE'=> 'SETcc',
	'SETNC' => 'SETcc',
	'SETNE' => 'SETcc',
	'SETNG' => 'SETcc',
	'SETNGE'=> 'SETcc',
	'SETNL' => 'SETcc',
	'SETNLE'=> 'SETcc',
	'SETNO' => 'SETcc',
	'SETNP' => 'SETcc',
	'SETNS' => 'SETcc',
	'SETNZ' => 'SETcc',
	'SETO'  => 'SETcc',
	'SETP'  => 'SETcc',
	'SETPE' => 'SETcc',
	'SETPO' => 'SETcc',
	'SETS'  => 'SETcc',
	'SETZ'  => 'SETcc',
);


//所有条件跳转
$Jcc_without_limit = array(
    'JA','JAE','JB','JBE','JC','JE','JG','JGE','JL','JLE','JNA','JNAE','JNB','JNBE','JNC','JNE','JNG','JNGE','JNL','JNLE','JNO','JNP','JNS','JNZ','JO','JP','JPE','JPO','JS','JZ'
);

//绝对&条件 跳转  
// 2 绝对跳转
// 1 相对跳转
// 同时 作为 IP 改变 指令
$con_abs_jmp = array(
					'CALL' => 2,
					'IRET' => 2,
					'IRETD' => 2,
					'IRETQ' => 2,
					'IRETW' => 2,
					'JA' => 1,
					'JAE' => 1,
					'JB' => 1,
					'JBE' => 1,
					'JC' => 1,
					'JCXZ' => 1,
					'JECXZ' => 1,
					'JE' => 1,
					'JG' => 1,
					'JGE' => 1,
					'JL' => 1,
					'JLE' => 1,
					'JNA' => 1,
					'JNAE' => 1,
					'JNB' => 1,
					'JNBE' => 1,
					'JNC' => 1,
					'JNE' => 1,
					'JNG' => 1,
					'JNGE' => 1,
					'JNL' => 1,
					'JNLE' => 1,
					'JNO' => 1,
					'JNP' => 1,
					'JNS' => 1,
					'JNZ' => 1,
					'JO' => 1,
					'JP' => 1,
					'JPE' => 1,
					'JPO' => 1,
					'JS' => 1,
					'JZ' => 1,
					'JRCXZ' => 1,
					'JMP' => 2,
					'JMPE' => 2,
					'LOOP' => 1,
					'LOOPE' => 1,
					'LOOPZ' => 1,
					'LOOPNE' => 1,
					'LOOPNZ' => 1,
					'RET' => 2,
					'RETF' => 2,
					'RETN' => 2,
					'SYSCALL' => 2,
					'SYSENTER' => 2,
					'SYSEXIT' => 2,
					'SYSRET' => 2,
				);

//后面 跟 跳转 目的 标号的 跳转指令
$eip_instruction = array('JA'        => 1, //高于（CF=0 且 ZF=0）时短跳转
						 'JAE'       => 1,         //高于或等于 (CF=0) 时短跳转
						 'JB'        => 1,         //低于 (CF=1) 时短跳转
						 'JBE'       => 1, //低于或等于（CF=1 或 ZF=1）时短跳转
						 'JC'        => 1,         //进位 (CF=1) 时短跳转
						 'JCXZ'      => 2,         //CX 寄存器为 0 时短跳转
						 'JECXZ'     => 2,        //ECX 寄存器为 0 时短跳转
						 'JRCXZ'     => 2,        //RCX 寄存器为 0 时短跳转
						 'JE'        => 1,         //等于 (ZF=1) 时短跳转
						 'JG'        => 1,//大于（ZF=0 且 SF=OF）时短跳转
						 'JGE'       => 1, //大于或等于 (SF=OF) 时短跳转
						 'JL'        => 1, //小于 (SF<>OF) 时短跳转
						 'JLE'       => 1,//小于或等于（ZF=1 或 SF<>OF）时短跳转
						 'JNA'       => 1, //不高于（CF=1 或 ZF=1）时短跳转
						 'JNAE'      => 1,//不高于或等于 (CF=1) 时短跳转
						 'JNB'       => 1,//不低于 (CF=0) 时短跳转
						 'JNBE'      => 1,//不低于或等于（CF=0 或 ZF=0）时短跳转
						 'JNC'       => 1,//无进位 (CF=0) 时短跳转
						 'JNE'       => 1,//不相等 (ZF=0) 时短跳转
						 'JNG'       => 1,//不大于（ZF=1 或 SF<>OF）时短跳转
						 'JNGE'      => 1,//不大于或等于 (SF<>OF) 时短跳转
						 'JNL'       => 1,//不小于 (SF＝OF) 时短跳转
						 'JNLE'      => 1,//不小于或等于（ZF=0 且 SF=OF）时短跳转
						 'JNO'       => 1,//不上溢 (OF=0) 时短跳转
						 'JNP'       => 1,//奇校验 (PF=0) 时短跳转
						 'JNS'       => 1,//正数时 (SF＝0) 短跳转
						 'JNZ'       => 1,//不为零 (ZF=0) 时短跳转
						 'JO'        => 1,//上溢 (OF=1) 时短跳转
						 'JP'        => 1,//偶校验 (PF=1) 时短跳转
						 'JPE'       => 1,//偶校验 (PF=1) 时短跳转
						 'JPO'       => 1,//奇校验 (PF=0) 时短跳转
						 'JS'        => 1,//负数 (SF=1) 时短跳转
						 'JZ'        => 1,//为零 (ZF  1) 时短跳转
						 'JMPE'      => 1,//		RM32				[M:	O32 0F 00 /6]				IA64

						 'LOOP'      => 2,        //递减计数；计数  0 时短跳转
						 'LOOPE'     => 2,//递减计数；计数  0 且 ZF=1 时短跳转
						 'LOOPZ'     => 2,//递减计数；计数  0 且 ZF=1 时短跳转
						 'LOOPNE'    => 2,//递减计数；计数  0 且 ZF=0 时短跳转
						 'LOOPNZ'    => 2,//递减计数；计数  0 且 ZF=0 时短跳转

						 'JMP'       => 1,
						 'CALL'      => 1,	 
						 );


?>