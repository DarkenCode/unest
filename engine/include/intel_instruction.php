<?php

if(!defined('UNEST.ORG')) {
	exit('Access Denied');
}


//所有寄存器
/*
//8bit 低位
$register['8']  = array('AL' => 8,   'CL' => 8,  'DL' => 8,  'BL' => 8,);
//9bit 高位
$register['9']  = array('AH' => 9,   'CH' => 9,  'DH' => 9,  'BH' => 9,);
//16bit
$register['16'] = array('AX' => 16,  'CX' => 16, 'DX' => 16, 'BX' => 16, 'SI' => 16, 'DI' => 16,  'BP' => 16,  'SP' => 16,  'IP' => 16);
//32bit
$register['32'] = array('EAX' => 32,'ECX' => 32,'EDX' => 32,'EBX' => 32,'ESI' => 32,'EDI' => 32, 'EBP' => 32, 'ESP' => 32, 'EIP' => 32);
//64bit
$register['64'] = array('RAX' => 64,'RCX' => 64,'RDX' => 64,'RBX' => 64,'RSI' => 64,'RDI' => 64, 'RBP' => 64, 'RSP' => 64, 'RIP' => 64);

$total_register = $register['8'] + $register['9'] + $register['16'] + $register['32'] + $register['64'];
*/
/*
$registersss['8']  = array ('EAX' => 'AL', 'ECX' => 'CL',  'EDX'=> 'DL',  'EBX' => 'BL');
$registersss['9']  = array ('EAX' => 'AH', 'ECX' => 'CH',  'EDX'=> 'DH',  'EBX' => 'BH');
$registersss['16'] = array ('EAX' => 'AX', 'ECX' => 'CX',  'EDX'=> 'DX',  'EBX' => 'BX', 'ESI' => 'SI',  'EDI'=> 'DI', 'EBP' => 'BP',  'ESP' => 'SP');
$registersss['32'] = array ('EAX' => 'EAX','ECX' => 'ECX', 'EDX'=> 'EDX', 'EBX' => 'EBX','ESI' => 'ESI', 'EDI'=> 'EDI','EBP' => 'EBP', 'ESP' => 'ESP');
$registersss['64'] = array ('EAX' => 'RAX','ECX' => 'RCX', 'EDX'=> 'RDX', 'EBX' => 'RBX','ESI' => 'RSI', 'EDI'=> 'RDI','EBP' => 'RBP', 'ESP' => 'RSP');
*/
/*
$register_assort = array(
    'AL' => 'EAX','AH' => 'EAX','AX' => 'EAX','EAX'=> 'EAX','RAX'=> 'EAX',
	'CL' => 'ECX','CH' => 'ECX','CX' => 'ECX','ECX'=> 'ECX','RCX'=> 'ECX',
	'DL' => 'EDX','DH' => 'EDX','DX' => 'EDX','EDX'=> 'EDX','RDX'=> 'EDX',
	'BL' => 'EBX','BH' => 'EBX','BX' => 'EBX','EBX'=> 'EBX','RBX'=> 'EBX',
                                'SI' => 'ESI','ESI'=> 'ESI','RSI'=> 'ESI',
								'DI' => 'EDI','EDI'=> 'EDI','RDI'=> 'EDI',
								'BP' => 'EBP','EBP'=> 'EBP','RBP'=> 'EBP',
								'SP' => 'ESP','ESP'=> 'ESP','RSP'=> 'ESP',
								'IP' => 'EIP','EIP'=> 'EIP','RIP'=> 'EIP',
);
*/
/*

Format:

'xxxx' => array( //指令名 xxxx    
    '0' = -1,    //第一个参数               不读也不写 (LEA)
    '0' =  1,     //第一个参数(如果是寄存器) 读取 动作	
	'1' =  2,     //第二个参数(如果是寄存器) 写入 动作
	'2' =  3,     //第三个参数(如果是寄存器) 读取并写入 动作
	'edi' = 2,   //固定对edi有写入动作
	'cx' =1,     //固定对cx有读取动作
	'zf' =1,     //对zf标志有读取动作
	'cf' =2,     //对cf标志有写入动作
)

如果根据操作数还有分类,数组中再设置数组
'xxxx' => array(
    
	'multi_op' => 2, //根据操作数再设数组 (个数)

    '0' => array(  //无参数情况
	),

	'1' => array(  //1个参数情况
	
	),

)


注：目前仅考虑基本寄存器，不考虑SSE/MMX等

*/

$Intel_instruction_mem_opt = array( //所有 隐含 内存操作的指令
    'MOVSB' => array(
	                 array( 'code' => '[ESI]','opt'  => 1,'bits' => 8,'reg' => array('ESI')),
	                 array( 'code' => '[EDI]','opt'  => 2,'bits' => 8,'reg' => array('EDI')),	
               ),
    'MOVSW' => array(
	                 array( 'code' => '[ESI]','opt'  => 1,'bits' => 16,'reg' => array('ESI')),
	                 array( 'code' => '[EDI]','opt'  => 2,'bits' => 16,'reg' => array('EDI')),	
               ),
    'MOVSD' => array(
	                 array( 'code' => '[ESI]','opt'  => 1,'bits' => 32,'reg' => array('ESI')),
	                 array( 'code' => '[EDI]','opt'  => 2,'bits' => 32,'reg' => array('EDI')),	
               ),
    'STOSB' => array(array( 'code' => '[EDI]','opt'  => 2,'bits' =>  8,'reg' => array('EDI'))),
	'STOSW' => array(array( 'code' => '[EDI]','opt'  => 2,'bits' => 16,'reg' => array('EDI'))),
	'STOSD' => array(array( 'code' => '[EDI]','opt'  => 2,'bits' => 32,'reg' => array('EDI'))),

    'LODSB' => array(array( 'code' => '[ESI]','opt'  => 1,'bits' =>  8,'reg' => array('ESI'))),
	'LODSW' => array(array( 'code' => '[ESI]','opt'  => 1,'bits' => 16,'reg' => array('ESI'))),
	'LODSD' => array(array( 'code' => '[ESI]','opt'  => 1,'bits' => 32,'reg' => array('ESI'))),

	'SCASB' => array(array( 'code' => '[EDI]','opt'  => 1,'bits' =>  8,'reg' => array('EDI'))),
	'SCASW' => array(array( 'code' => '[EDI]','opt'  => 1,'bits' => 16,'reg' => array('EDI'))),
	'SCASD' => array(array( 'code' => '[EDI]','opt'  => 1,'bits' => 32,'reg' => array('EDI'))),
);


///////////////////////////////////////////////////////////////////////////////////////////////

$Intel_instruction = array(
  'DB'        => array('data' => 1),
  'DW'        => array('data' => 1),
  'DD'        => array('data' => 1),

//# Conventional instructions
  'AAA'       => array(              'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'AX'=>3),
  'AAB'       => array(              'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'AX'=>3),
  'AAM'       => array(              'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'AL'=>3,'AH'=>2),
  'AAS'       => array(              'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>3,'CF'=>2,'PF'=>2,'AX'=>3),
  'ADC'       => array('0'=>3,'1'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>3,'PF'=>2),
  'ADD'       => array('0'=>3,'1'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),
  'AND'       => array('0'=>3,'1'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),
  'ARPL'      => array (                             'ZF'=>2),
  'BB0_RESET' => array(), //unkown
  'BB1_RESET' => array(), //unkown
  'BOUND'     => array('0'=>1,),
  'BSF'       => array('0'=>2,       'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,),
  'BSR'       => array('0'=>2,       'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,),
  'BSWAP'     => array('0'=>3,),
  'BT'        => array('0'=>1,'1'=>1,'OF'=>2,'SF'=>2,        'AF'=>2,'CF'=>2,'PF'=>2,),
  'BTC'       => array('0'=>3,       'OF'=>2,'SF'=>2,        'AF'=>2,'CF'=>2,'PF'=>2,),
  'BTR'       => array('0'=>3,       'OF'=>2,'SF'=>2,        'AF'=>2,'CF'=>2,'PF'=>2,),
  'BTS'       => array('0'=>3,       'OF'=>2,'SF'=>2,        'AF'=>2,'CF'=>2,'PF'=>2,),
  'CALL'      => array('STACK'=>3,'0'=>1,'ESP'=>3,'IP'=>2),//,'EAX'=>2), //注：CALL 本身不改变EAX，这里暂时为_std调用函数而设置 * 临时
  'CBW'       => array(                                                              'AL'=>3,'AX'=>2),
  'CDQ'       => array(                                                                              'EAX'=>1,'EDX'=>2),
  'CDQE'      => array(                                                                              'EAX'=>3,'RAX'=>2),
  'CLC'       => array(                                              'CF'=>2), 
  'CLD'       => array('DF'=>2),
  'CLGI'      => array(), //unkown
  'CLI'       => array('IF'=>2),
  'CLTS'      => array(),
  'CMC'       => array(                                              'CF'=>2),

  'CMOVA'     => array('0'=>2,'1'=>1,                'ZF'=>1,        'CF'=>1),         //高于（CF=0 且 ZF=0）时移动
  'CMOVAE'    => array('0'=>2,'1'=>1,                                'CF'=>1),         //高于或等于 (CF=0) 时移动
  'CMOVB'     => array('0'=>2,'1'=>1,                                'CF'=>1),         //   低于 (CF=1) 时移动
  'CMOVBE'    => array('0'=>2,'1'=>1,                'ZF'=>1,        'CF'=>1),         //   低于或等于（CF=1 或 ZF=1）时移动
  'CMOVC'     => array('0'=>2,'1'=>1,                                'CF'=>1),         // 进位 (CF=1) 时移动
  'CMOVE'     => array('0'=>2,'1'=>1,                'ZF'=>1),                         //等于 (ZF=1) 时移动
  'CMOVG'     => array('0'=>2,'1'=>1,'OF'=>1,'SF'=>1,'ZF'=>1),                         //大于（ZF=0 且 SF=OF）时移动
  'CMOVGE'    => array('0'=>2,'1'=>1,'OF'=>1,'SF'=>1),                                 // 大于或等于 (SF=OF) 时移动
  'CMOVL'     => array('0'=>2,'1'=>1,'OF'=>1,'SF'=>1),                                 // 小于 (SF<>OF) 时移动
  'CMOVLE'    => array('0'=>2,'1'=>1,'OF'=>1,'SF'=>1,'ZF'=>1),                         //小于或等于（ZF=1 或 SF<>OF）时移动
  'CMOVNA'    => array('0'=>2,'1'=>1,                'ZF'=>1,        'CF'=>1),         // 不高于（CF=1 或 ZF=1）时移动
  'CMOVNAE'   => array('0'=>2,'1'=>1,                                'CF'=>1),         //不高于或等于 (CF=1) 时移动
  'CMOVNB'    => array('0'=>2,'1'=>1,                                'CF'=>1),         //不低于 (CF=0) 时移动
  'CMOVNBE'   => array('0'=>2,'1'=>1,                'ZF'=>1,        'CF'=>1),         //不低于或等于（CF=0 且 ZF=0）时移动
  'CMOVNC'    => array('0'=>2,'1'=>1,                                'CF'=>1),         //无进位 (CF=0) 时移动
  'CMOVNE'    => array('0'=>2,'1'=>1,                'ZF'=>1),                         //不等于 (ZF=0) 时移动
  'CMOVNG'    => array('0'=>2,'1'=>1,'OF'=>1,'SF'=>1,'ZF'=>1),                         //  r16, r/m16不大于（ZF=1 或 SF<>OF）时移动
  'CMOVNGE'   => array('0'=>2,'1'=>1,'OF'=>1,'SF'=>1),                                 //  r16, r/m16不大于或等于 (SF<>OF) 时移动
  'CMOVNL'    => array('0'=>2,'1'=>1,'OF'=>1,'SF'=>1),                                 //  r16, r/m16不小于 (SF=OF) 时移动
  'CMOVNLE'   => array('0'=>2,'1'=>1,'OF'=>1,'SF'=>1,'ZF'=>1),                         //  r16, r/m16不小于或等于（ZF=0 且 SF=OF）时移动
  'CMOVNO'    => array('0'=>2,'1'=>1,'OF'=>1),                                         //   r16, r/m16不上溢 (OF=0) 时移动
  'CMOVNP'    => array('0'=>2,'1'=>1,                                        'PF'=>1), // r16, r/m16奇校验 (PF=0) 时移动
  'CMOVNS'    => array('0'=>2,'1'=>1,        'SF'=>1),                                 // r16, r/m16正数 (SF=0) 时移动
  'CMOVNZ'    => array('0'=>2,'1'=>1,                'ZF'=>1),                         // r16, r/m16不为零 (ZF=0) 时移动
  'CMOVO'     => array('0'=>2,'1'=>1,'OF'=>1),                                         //r16, r/m16上溢 (OF=0) 时移动
  'CMOVP'     => array('0'=>2,'1'=>1,                                        'PF'=>1), //r16, r/m16偶校验 (PF=1) 时移动
  'CMOVPE'    => array('0'=>2,'1'=>1,                                        'PF'=>1), //r16, r/m16偶校验 (PF=1) 时移动
  'CMOVPO'    => array('0'=>2,'1'=>1,                                        'PF'=>1), //r16, r/m16奇校验 (PF=0) 时移动
  'CMOVS'     => array('0'=>2,'1'=>1,        'SF'=>1),                                 //r16, r/m16负数 (SF=1) 时移动
  'CMOVZ'     => array('0'=>2,'1'=>1,                'ZF'=>1),                         //r16, r/m16为零 (ZF=1) 时移动

  'CMP'       => array('0'=>1,'1'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),
  'CMPSB'     => array(      'DF'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'SI'=>3,'DI'=>3),
  'CMPSD'     => array(      'DF'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'SI'=>3,'DI'=>3),
  'CMPSQ'	  => array(      'DF'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'SI'=>3,'DI'=>3),
  'CMPSW'     => array(      'DF'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'SI'=>3,'DI'=>3),
  'CMPXCHG'   => array(              'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'AL'=>3,'AX'=>3),
  'CMPXCHG486'=> array('0'=>3,'1'=>3,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),
  'CMPXCHG8B' => array('0'=>3,                       'ZF'=>2,                'EDX'=>3,'EAX'=>3,'ECX'=>1,'EBX'=>1),
  'CMPXCHG16B'=> array('0'=>3,                       'ZF'=>2,                'RDX'=>3,'RAX'=>3,'RCX'=>1,'RBX'=>1),
  'CPUID'     => array(                                                      'EAX'=>3,'EBX'=>2,'ECX'=>2,'EDX'=>2),
  'CPU_READ'  => array(                                                                      'EAX'=>2,'EBX'=>1),
  'CPU_WRITE' => array(                                                                      'EAX'=>1,'EBX'=>1),
  'CQO'       => array(                                                                      'RAX'=>1,'RDX'=>2),
  'CWD'       => array(                                                                      'AX'=>1,'DX'=>2),
  'CWDE'      => array(                                                                      'AX'=>3,'EAX'=>2),
  
  'DAA'       => array(              'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>3,'CF'=>3,'PF'=>2,'AL'=>3),
  'DAS'       => array(              'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>3,'CF'=>3,'PF'=>2,'AL'=>3),
  'DEC'       => array('0'=>3,       'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,        'PF'=>2,),
  'DIV'       => array('0'=>1,       'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'AX'=>3,'DX'=>3),
  'DMINT'     => array(),//unkown

  'EMMS'      => array(), //将 x87 FPU 标记字设置为空
  'ENTER'     => array('STACK'=>3,'BP'=>3,'SP'=>3), //生成过程参数的堆栈帧
  'EQU'       => array(),
  
  'F2XM1'     => array(), // - 计算 2x-1
  'FABS'      => array(), //将 ST 替换成其绝对值。
  'FADD'      => array('0'=>1), //加法	
  'FADDP'	  => array('0'=>1), //加法 
  'FBLD'      => array(), //加载二进制编码的十进制数
  'FBSTP'     => array(), //存储 BCD 整数并弹出
  'FCHS'      => array(), //对 ST(0) 的符号求补
  'FCLEX'     => array(), //在检查未决的无掩码浮点异常之后，清除浮点异常标志。
  'FCMOVB'    => array(                                              'CF'=>1),        //低于 (CF=1) 时移动
  'FCMOVBE'   => array(                              'ZF'=>1,        'CF'=>1),        //低于或等于（CF=1 或 ZF=1）时移动
  'FCMOVE'    => array(                              'ZF'=>1),                        //等于 (ZF=1) 时移动
  'FCMOVNB'   => array(                                              'CF'=>1),        //不低于 (CF=0) 时移动
  'FCMOVNBE'  => array(                              'ZF'=>1,        'CF'=>1),        //不低于或等于（CF=0 且 ZF=0）时移动
  'FCMOVNE'   => array(                              'ZF'=>1),                        //不等于 (ZF=0) 时移动
  'FCMOVNU'   => array(		                                                 'PF'=>1),//有序 (PF=0) 时移动
  'FCMOVU'    => array(                                                      'PF'=>1),//无序 (PF=1) 时移动
  'FCOM'      => array(),	//比较实数
  'FCOMI'	  => array(              'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),//比较实数并设置 EFLAGS
  'FCOMIP'	  => array(              'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),   //比较实数并设置 EFLAGS
  'FCOMP'	  => array(),	//比较实数	
  'FCOMPP'	  => array(),	//比较实数
  'FCOS'      => array(),   //余弦
  'FDECSTP'   => array(),   //递减栈顶指针
  'FDISI'     => array(),   //unkown
  'FDIV'      => array(),   //除法
  'FDIVP'     => array(),   //除法
  'FDIVR'     => array(),   //被除
  'FDIVRP'    => array(),   //被除


  'FEMMS'     => array(),   //3dnow
  'FENI'      => array(),   //unkown
  'FFREE'     => array(),   //释放浮点寄存器
  'FFREEP'    => array(), //unkown
  'FIADD'	  => array(), //加法 

  'FICOM'     => array(), //比较整数
  'FICOMP'    => array(), //比较整数	
  'FIDIV'     => array(),   //除法
  'FIDIVR'    => array(),   //被除
  'FILD'      => array(), //加载整数
  'FIMUL'     => array(), //乘法
  'FINCSTP'   => array(), //递增栈顶指针
  'FINIT'     => array(), //初始化浮点单元		void				[	wait db e3]				8086,FPU
  'FIST'      => array(), //存储整数
  'FISTP'     => array(), //存储整数
  'FISTTP'    => array(), //存储整数，带截断	
  'FISUB'     => array(), //减法
  'FISUBR'    => array(), //被减
  'FLD'       => array(), //加载实数
  'FLD1'      => array(),  //将 +1.0 压入 FPU 寄存器堆栈。
  'FLDCW'     => array(),  //加载 x87 FPU 控制字
  'FLDENV'    => array(),  //加载 x87 FPU 环境
  'FLDL2E'    => array(),  //将 log2e 压入 FPU 寄存器堆栈。
  'FLDL2T'    => array(),  //将 log210 压入 FPU 寄存器堆栈。
  'FLDLG2'    => array(),  //将 log102 压入 FPU 寄存器堆栈。
  'FLDLN2'    => array(),  //将 loge2 压入 FPU 寄存器堆栈。
  'FLDPI'     => array(),  //将  压入 FPU 寄存器堆栈。
  'FLDZ'      => array(),  //将 +0.0 压入 FPU 寄存器堆栈。

  'FMUL'      => array(),  //乘法
  'FMULP'     => array(),  //乘法		fpureg				[r:	de c8+r]				8086,FPU
  'FNCLEX'	  => array(),  //加法
  'FNDISI'    => array(),//		void				[	db e1]					8086,FPU
  'FNENI'     => array(),//		void				[	db e0]					8086,FPU
  'FNINIT'    => array(), //初始化浮点单元
  'FNOP'      => array(), //无操作
  'FNSAVE'    => array(), //存储 x87 FPU 状态
  'FNSTCW'    => array(), //存储 x87 FPU 控制字
  'FNSTENV'   => array(), //存储 x87 FPU 环境
  'FNSTSW'    => array(), //存储 x87 FPU 状态字 //执行 指令时，处理器会在执行其它指令之前更新 AX 寄存器。这样，就可以确保 AX 寄存器存储的状态是 FPU 指令完成之前的状态。
  'FPATAN'    => array(),//部分反正切
  'FPREM'     => array(),//部分余数
  'FPREM1'    => array(),//部分余数
  'FPTAN'     => array(),//部分正切
  'FRNDINT'   => array(),//取整
  'FRSTOR'    => array(),//还原 x87 FPU 状态
  'FSAVE'     => array(),//存储 x87 FPU 状态
  'FSCALE'    => array(),//缩放
  'FSETPM'    => array(),//		void				[	db e4]					286,FPU
  'FSIN'      => array(),//正弦
  'FSINCOS'   => array(),//正弦与余弦
  'FSQRT'     => array(),//平方根
  'FST'       => array(),//存储实数
  'FSTCW'     => array(),//存储 x87 FPU 控制字
  'FSTENV'    => array(),//存储 x87 FPU 环境
  'FSTP'      => array(),//存储实数
  'FSTSW'     => array(),//存储 x87 FPU 状态字 //执行 指令时，处理器会在执行其它指令之前更新 AX 寄存器。这样，就可以确保 AX 寄存器存储的状态是 FPU 指令完成之前的状态。
  'FSUB'      => array(), //减法
  'FSUBP'     => array(), //减法
  'FSUBR'     => array(), //被减
  'FSUBRP'    => array(), //被减
  'FTST'      => array(), //测试
  'FUCOM'     => array(), //无序比较实数
  'FUCOMI'    => array(                 'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2), //比较实数并设置 EFLAGS
  'FUCOMIP'   => array(                 'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2), //比较实数并设置 EFLAGS
  'FUCOMP'    => array(), //无序比较实数
  'FUCOMPP'   => array(), //无序比较实数
  'FWAIT'     => array(), //等待
  'FXAM'      => array(), //确定 ST(0) 中的值或数字的类别
  'FXCH'      => array(), //交换寄存器内容
  'FXTRACT'   => array(), //提取指数与有效位
  'FYL2X'     => array(), //计算 y * log2x
  'FYL2XP1'   => array(), //计算 y * log2(x +1)
  'HLT'       => array(), //暂停
  'IBTS'      => array(), //unkown
  'ICEBP'     => array(), //unkown
  'IDIV'      => array('0'=>1,        'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'AX'=>3,'DX'=>3,), //有符号除法

  'IMUL'      => array('multi_op' => 3,
                       //单操作数形式。此形式与 MUL 指令使用的形式完全相同。这里，源操作数（位于通用寄存器或内存位置）乘以 AL、AX 或 EAX 寄存器（取决于操作数大小）中的值，乘积分别存储到 AX、DX:AX 或 EDX:EAX 寄存器。
                       '1' => array('0'=>1,              'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'AX'=>3,'DX'=>3), 
					   //双操作数形式。对于此种形式，目标操作数（第一个操作数）乘以源操作数（第二个操作数）。目标操作数是通用寄存器，源操作数可以是立即数、通用寄存器或内存位置。乘积随后存储到目标操作数位置。
					   '2' => array('0'=>3,'1'=>1,       'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,),
					   //三操作数形式。此种形式需要一个目标操作数（第一个操作数）与两个源操作数（第二个与第三个操作数）。这里，第一个源操作数（可以是通用寄存器或内存位置）乘以第二个源操作数（立即数）。乘积随后存储到目标操作数（通用寄存器）。
					   '3' => array('0'=>2,'1'=>1,'2'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,),
				 ),//有符号乘法				 
  'IN'        => array('0'=>2,'1'=>1), //从端口输入
  'INC'       => array('0'=>3,       'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,        'PF'=>2), //递增 1
  'INSB'      => array('DF'=>1,'DX'=>1,'DI'=>3), //从端口输入到字符串 从 DX 指定的 I/O 端口将字节输入 ES:(E)DI 指定的内存位置
  'INSD'      => array('DF'=>1,'DX'=>1,'DI'=>3), //从端口输入到字符串 从 DX 指定的 I/O 端口将双字输入 ES:(E)DI 指定的内存位置
  'INSW'      => array('DF'=>1,'DX'=>1,'DI'=>3), //从端口输入到字符串 从 DX 指定的 I/O 端口将字输入 ES:(E)DI 指定的内存位置
  //ALL_FLAG=>3
  'INT'       => array('OF' => 3,'SF' => 3,'ZF' => 3,'AF' => 3,'CF' => 3,'PF' => 3,'DF' => 3,'TF' => 3,'IF' => 3,'NT' => 2),  //调用中断过程
  'INT01'     => array('OF' => 3,'SF' => 3,'ZF' => 3,'AF' => 3,'CF' => 3,'PF' => 3,'DF' => 3,'TF' => 3,'IF' => 3,'NT' => 2),
  'INT1'      => array('OF' => 3,'SF' => 3,'ZF' => 3,'AF' => 3,'CF' => 3,'PF' => 3,'DF' => 3,'TF' => 3,'IF' => 3,'NT' => 2),	//	void				[	f1]		386
  'INT03'     => array('OF' => 3,'SF' => 3,'ZF' => 3,'AF' => 3,'CF' => 3,'PF' => 3,'DF' => 3,'TF' => 3,'IF' => 3,'NT' => 2),	//	void				[	cc]		8086,ND
  'INT3'      => array('OF' => 3,'SF' => 3,'ZF' => 3,'AF' => 3,'CF' => 3,'PF' => 3,'DF' => 3,'TF' => 3,'IF' => 3,'NT' => 2),	//	void				[	cc]		8086
  'INTO'      => array('OF' => 3,'SF' => 3,'ZF' => 3,'AF' => 3,'CF' => 3,'PF' => 3,'DF' => 3,'TF' => 3,'IF' => 3,'NT' => 2),	//	void				[	ce]	8086,NOLONG
  'INVD'      => array(), //使内部缓存失效
  'INVPCID'   => array('0'=>3),//Invalidate Process-Context Identifier
  'INVLPG'    => array(),//使 TLB 项目失效
  'INVLPGA'	  => array('EAX'=>3,'ECX'=>3),//unkown
  'IRET'      => array('STACK'=>3,'ESP'=>3,'IP'=>2,'OF' => 2,'SF' => 2,'ZF' => 2,'AF' => 2,'CF' => 2,'PF' => 2,'DF' => 2,'TF' => 2,'IF' => 2,'NT' => 1),//中断返回
  'IRETD'     => array('STACK'=>3,'ESP'=>3,'IP'=>2,'OF' => 2,'SF' => 2,'ZF' => 2,'AF' => 2,'CF' => 2,'PF' => 2,'DF' => 2,'TF' => 2,'IF' => 2),//中断返回
  'IRETQ'     => array('STACK'=>3,'ESP'=>3,'IP'=>2,'OF' => 2,'SF' => 2,'ZF' => 2,'AF' => 2,'CF' => 2,'PF' => 2,'DF' => 2,'TF' => 2,'IF' => 2),//中断返回
  'IRETW'     => array('STACK'=>3,'ESP'=>3,'IP'=>2,'OF' => 2,'SF' => 2,'ZF' => 2,'AF' => 2,'CF' => 2,'PF' => 2,'DF' => 2,'TF' => 2,'IF' => 2),//中断返回

  'JA'        => array('0'=>1,'CF'=>1,'ZF'=>1,'IP'=>2), //高于（CF=0 且 ZF=0）时短跳转
  'JAE'       => array('0'=>1,'CF'=>1,'IP'=>2),         //高于或等于 (CF=0) 时短跳转
  'JB'        => array('0'=>1,'CF'=>1,'IP'=>2),         //低于 (CF=1) 时短跳转
  'JBE'       => array('0'=>1,'CF'=>1,'ZF'=>1,'IP'=>2), //低于或等于（CF=1 或 ZF=1）时短跳转
  'JC'        => array('0'=>1,'CF'=>1,'IP'=>2),         //进位 (CF=1) 时短跳转
  'JCXZ'      => array('0'=>1,'CX'=>1,'IP'=>2),         //CX 寄存器为 0 时短跳转
  'JECXZ'     => array('0'=>1,'ECX'=>1,'IP'=>2),        //ECX 寄存器为 0 时短跳转
  'JE'        => array('0'=>1,'ZF'=>1,'IP'=>2),         //等于 (ZF=1) 时短跳转
  'JG'        => array('0'=>1,'OF'=>1,'SF'=>1,'ZF'=>1,'IP'=>2),//大于（ZF=0 且 SF=OF）时短跳转
  'JGE'       => array('0'=>1,'OF'=>1,'SF'=>1,'IP'=>2), //大于或等于 (SF=OF) 时短跳转
  'JL'        => array('0'=>1,'OF'=>1,'SF'=>1,'IP'=>2), //小于 (SF<>OF) 时短跳转
  'JLE'       => array('0'=>1,'OF'=>1,'SF'=>1,'ZF'=>1,'IP'=>2),//小于或等于（ZF=1 或 SF<>OF）时短跳转
  'JNA'       => array('0'=>1,'CF'=>1,'ZF'=>1,'IP'=>2), //不高于（CF=1 或 ZF=1）时短跳转
  'JNAE'      => array('0'=>1,'CF'=>1,'IP'=>2),//不高于或等于 (CF=1) 时短跳转
  'JNB'       => array('0'=>1,'CF'=>1,'IP'=>2),//不低于 (CF=0) 时短跳转
  'JNBE'      => array('0'=>1,'CF'=>1,'ZF'=>1,'IP'=>2),//不低于或等于（CF=0 或 ZF=0）时短跳转
  'JNC'       => array('0'=>1,'CF'=>1,'IP'=>2),//无进位 (CF=0) 时短跳转
  'JNE'       => array('0'=>1,'ZF'=>1,'IP'=>2),//不相等 (ZF=0) 时短跳转
  'JNG'       => array('0'=>1,'OF'=>1,'SF'=>1,'ZF'=>1,'IP'=>2),//不大于（ZF=1 或 SF<>OF）时短跳转
  'JNGE'      => array('0'=>1,'OF'=>1,'SF'=>1,'IP'=>2),//不大于或等于 (SF<>OF) 时短跳转
  'JNL'       => array('0'=>1,'OF'=>1,'SF'=>1,'IP'=>2),//不小于 (SF＝OF) 时短跳转
  'JNLE'      => array('0'=>1,'OF'=>1,'SF'=>1,'ZF'=>1,'IP'=>2),//不小于或等于（ZF=0 且 SF=OF）时短跳转
  'JNO'       => array('0'=>1,'OF'=>1,'IP'=>2),//不上溢 (OF=0) 时短跳转
  'JNP'       => array('0'=>1,'PF'=>1,'IP'=>2),//奇校验 (PF=0) 时短跳转
  'JNS'       => array('0'=>1,'SF'=>1,'IP'=>2),//正数时 (SF＝0) 短跳转
  'JNZ'       => array('0'=>1,'ZF'=>1,'IP'=>2),//不为零 (ZF=0) 时短跳转
  'JO'        => array('0'=>1,'OF'=>1,'IP'=>2),//上溢 (OF=1) 时短跳转
  'JP'        => array('0'=>1,'PF'=>1,'IP'=>2),//偶校验 (PF=1) 时短跳转
  'JPE'       => array('0'=>1,'PF'=>1,'IP'=>2),//偶校验 (PF=1) 时短跳转
  'JPO'       => array('0'=>1,'PF'=>1,'IP'=>2),//奇校验 (PF=0) 时短跳转
  'JS'        => array('0'=>1,'SF'=>1,'IP'=>2),//负数 (SF=1) 时短跳转
  'JZ'        => array('0'=>1,'ZF'=>1,'IP'=>2),//为零 (ZF  1) 时短跳转
  'JRCXZ'     => array('0'=>1,'RCX'=>1,'IP'=>2),        //ECX 寄存器为 0 时短跳转
  'JMP'       => array('0'=>1,'IP'=>2),//		mem64				[m:	o64nw ff /4]				X64
  'JMPE'      => array('0'=>1,'IP'=>2),//		rm32				[m:	o32 0f 00 /6]				IA64

  'LAHF'      => array(        'SF'=>1,'ZF'=>1,'AF'=>1,'CF'=>1,'PF'=>1,'AH'=>2),//将状态标志加载到 AH 寄存器
  'LAR'       => array('0'=>2,'1'=>1,  'ZF'=>2),//加载访问权限字节
  'LDS'		  => array('0'=>2,'1'=>1),//加载远指针
  'LEA'       => array('0'=>2,'1'=> -1),//加载有效地址// lea 的第二参数 不读也不写
  'LEAVE'     => array('STACK'=>3,'BP'=>3,'SP'=>2),//高级过程退出
  'LES'       => array('0'=>2),//加载远指针
  'LFENCE'    => array(),//加载边界
  'LFS'       => array('0'=>2),//加载远指针
  'LGDT'      => array(),      //加载全局描述符表格寄存器
  'LGS'       => array('0'=>2),//加载远指针
  'LIDT'      => array(),      //加载中断描述符表格寄存器
  'LLDT'      => array('0'=>1),//加载局部描述符表格寄存器
  'LMSW'      => array('0'=>1),//加载机器状态字
  'LOCK'      => array('isPrefix'=>1),//声言 LOCK# 信号前缀
//LOADALL		void				[	0f 07]					386,UNDOC
//LOADALL286	void				[	0f 05]					286,UNDOC
  'LODSB'     => array('DF'=>1,'ESI'=>3,'AL'=>2),//加载字符串
  'LODSD'     => array('DF'=>1,'ESI'=>3,'EAX'=>2),//加载字符串
  'LODSQ'     => array('DF'=>1,'RSI'=>3,'RAX'=>2),//加载字符串
  'LODSW'     => array('DF'=>1,'ESI'=>3,'AX'=>2),//加载字符串

  'LOOP'      => array('0'=>1,'CX'=>3,'IP'=>2),        //递减计数；计数  0 时短跳转
  'LOOPE'     => array('0'=>1,'CX'=>3,'ZF'=>1,'IP'=>2),//递减计数；计数  0 且 ZF=1 时短跳转
  'LOOPZ'     => array('0'=>1,'CX'=>3,'ZF'=>1,'IP'=>2),//递减计数；计数  0 且 ZF=1 时短跳转
  'LOOPNE'    => array('0'=>1,'CX'=>3,'ZF'=>1,'IP'=>2),//递减计数；计数  0 且 ZF=0 时短跳转
  'LOOPNZ'    => array('0'=>1,'CX'=>3,'ZF'=>1,'IP'=>2),//递减计数；计数  0 且 ZF=0 时短跳转
  'LSL'       => array('0'=>2,'1'=>1,'ZF'=>2,),//加载段限制
  'LSS'       => array('0'=>2,'1'=>1),//加载远指针
  'LTR'       => array('0'=>1),//加载任务寄存器
  'MFENCE'    => array(),//内存边界		void				[	0f ae f0]				WILLAMETTE,SSE2
  'MONITOR'   => array('EAX'=>1,'ECX'=>3,'EDX'=>3),//设置监视器地址		reg_rax,reg_ecx,reg_edx		[---:	0f 01 c8]				X64,ND
  'MOV'       => array('0'=>2,'1'=>1),  //移动
  'MOVD'      => array('0'=>2,'1'=>1),  //移动双字		rm32,mmxreg			[mr:	np 0f 7e /r]				PENT,MMX,SD
  'MOVSB'     => array('DF'=>1,'SI'=>3,'DI'=>3),//		void				[	a4]					8086
  'MOVSD'	  => array('DF'=>1,'SI'=>3,'DI'=>3),//	void				[	o32 a5]					386
  'MOVSQ'     => array('DF'=>1,'SI'=>3,'DI'=>3),//		void				[	o64 a5]					X64
  'MOVSW'     => array('DF'=>1,'SI'=>3,'DI'=>3),//		void				[	o16 a5]					8086
  'MOVSX'     => array('0'=>2,'1'=>1),//带符号扩展移动
  'MOVSXD'    => array('0'=>2,'1'=>1),//带符号扩展移动
  'MOVZX'     => array('0'=>2,'1'=>1),//带零扩展移动		reg64,rm16			[rm:	o64 0f b7 /r]				X64
  'MUL'       => array('0'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'AX'=>3,'DX'=>3), //无符号乘法		rm8		[m:	f6 /4]	8086
  'MWAIT'     => array('EAX'=>3,'ECX'=>3),//监视器等待		reg_eax,reg_ecx			[--:	0f 01 c9]				PRESCOTT,ND
  'NEG'       => array('0'=>3,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2), //二的补码取反
  'NOP'       => array(), //		void				[	norexb 90]				8086
  'NOT'       => array('0'=>3), //一的补码取反		rm64				[m:	hle o64 f7 /2]				X64,LOCK
  'OR'        => array('0'=>3,'1'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2), //逻辑或
  'OUT'       => array('0'=>1,'1'=>1),//输出到端口
  'OUTSB'     => array('DF'=>1,'DX'=>1,'SI'=>3),//		void				[	6e]					186
  'OUTSD'	  => array('DF'=>1,'DX'=>1,'SI'=>3),//	void				[	o32 6f]					386
  'OUTSW'	  => array('DF'=>1,'DX'=>1,'SI'=>3),//	void				[	o16 6f]					186
  'PACKSSDW'  => array(),//有符号饱和压缩	mmxreg,mmxrm			[rm:	np o64nw 0f 6b /r]			PENT,MMX,SQ
  'PACKSSWB'  => array(),//有符号饱和压缩
  'PACKUSWB'  => array(),//无符号饱和压缩
  'PADDB'     => array(),//压缩加法		mmxreg,mmxrm			[rm:	np o64nw 0f fc /r]			PENT,MMX,SQ
  'PADDD'     => array(),//压缩加法		mmxreg,mmxrm			[rm:	np o64nw 0f fe /r]			PENT,MMX,SQ
  'PADDSB'    => array(),//压缩饱和加法		mmxreg,mmxrm			[rm:	np o64nw 0f ec /r]			PENT,MMX,SQ
  'PADDSIW'   => array(),//MMX Packed Addition to Implicit Destination		mmxreg,mmxrm			[rm:	o64nw 0f 51 /r]				PENT,MMX,SQ,CYRIX
  'PADDSW'    => array(),//压缩饱和加法		mmxreg,mmxrm			[rm:	np o64nw 0f ed /r]			PENT,MMX,SQ
  'PADDUSB'   => array(),//压缩无符号饱和加法		mmxreg,mmxrm			[rm:	np o64nw 0f dc /r]			PENT,MMX,SQ
  'PADDUSW'   => array(),//压缩无符号饱和加法		mmxreg,mmxrm			[rm:	np o64nw 0f dd /r]			PENT,MMX,SQ
  'PADDW'     => array(),//压缩加法		mmxreg,mmxrm			[rm:	np o64nw 0f fd /r]			PENT,MMX,SQ
  'PAND'      => array(),//逻辑与		mmxreg,mmxrm			[rm:	np o64nw 0f db /r]			PENT,MMX,SQ
  'PANDN'     => array(),//逻辑与非		mmxreg,mmxrm			[rm:	np o64nw 0f df /r]			PENT,MMX,SQ
  'PAUSE'     => array(),//暂停预设时间量		void				[	norexb f3i 90]				8086
/*
PAVEB		mmxreg,mmxrm			[rm:	o64nw 0f 50 /r]				PENT,MMX,SQ,CYRIX
PAVGUSB		mmxreg,mmxrm			[rm:	o64nw 0f 0f /r bf]			PENT,3DNOW,SQ
PDISTIB		mmxreg,mem			[rm:	0f 54 /r]				PENT,MMX,SM,CYRIX
PF2ID		mmxreg,mmxrm			[rm:	o64nw 0f 0f /r 1d]			PENT,3DNOW,SQ
PFACC		mmxreg,mmxrm			[rm:	o64nw 0f 0f /r ae]			PENT,3DNOW,SQ
PFADD		mmxreg,mmxrm			[rm:	o64nw 0f 0f /r 9e]			PENT,3DNOW,SQ
PFCMPEQ		mmxreg,mmxrm			[rm:	o64nw 0f 0f /r b0]			PENT,3DNOW,SQ
PFCMPGE		mmxreg,mmxrm			[rm:	o64nw 0f 0f /r 90]			PENT,3DNOW,SQ
PFCMPGT		mmxreg,mmxrm			[rm:	o64nw 0f 0f /r a0]			PENT,3DNOW,SQ
PFMAX		mmxreg,mmxrm			[rm:	o64nw 0f 0f /r a4]			PENT,3DNOW,SQ
PFMIN		mmxreg,mmxrm			[rm:	o64nw 0f 0f /r 94]			PENT,3DNOW,SQ
PFMUL		mmxreg,mmxrm			[rm:	o64nw 0f 0f /r b4]			PENT,3DNOW,SQ
PFRCP		mmxreg,mmxrm			[rm:	o64nw 0f 0f /r 96]			PENT,3DNOW,SQ
PFRCPIT1	mmxreg,mmxrm			[rm:	o64nw 0f 0f /r a6]			PENT,3DNOW,SQ
PFRCPIT2	mmxreg,mmxrm			[rm:	o64nw 0f 0f /r b6]			PENT,3DNOW,SQ
PFRSQIT1	mmxreg,mmxrm			[rm:	o64nw 0f 0f /r a7]			PENT,3DNOW,SQ
PFRSQRT		mmxreg,mmxrm			[rm:	o64nw 0f 0f /r 97]			PENT,3DNOW,SQ
PFSUB		mmxreg,mmxrm			[rm:	o64nw 0f 0f /r 9a]			PENT,3DNOW,SQ
PFSUBR		mmxreg,mmxrm			[rm:	o64nw 0f 0f /r aa]			PENT,3DNOW,SQ
PI2FD		mmxreg,mmxrm			[rm:	o64nw 0f 0f /r 0d]			PENT,3DNOW,SQ
PMACHRIW	mmxreg,mem			[rm:	0f 5e /r]				PENT,MMX,SM,CYRIX
PMAGW		mmxreg,mmxrm			[rm:	o64nw 0f 52 /r]				PENT,MMX,SQ,CYRIX
PMULHRIW	mmxreg,mmxrm			[rm:	o64nw 0f 5d /r]				PENT,MMX,SQ,CYRIX
PMULHRWA	mmxreg,mmxrm			[rm:	o64nw 0f 0f /r b7]			PENT,3DNOW,SQ
PMULHRWC	mmxreg,mmxrm			[rm:	o64nw 0f 59 /r]				PENT,MMX,SQ,CYRIX
PMVGEZB		mmxreg,mem			[rm:	0f 5c /r]				PENT,MMX,SQ,CYRIX
PMVLZB		mmxreg,mem			[rm:	0f 5b /r]				PENT,MMX,SQ,CYRIX
PMVNZB		mmxreg,mem			[rm:	0f 5a /r]				PENT,MMX,SQ,CYRIX
PMVZB		mmxreg,mem			[rm:	0f 58 /r]				PENT,MMX,SQ,CYRIX
*/
  'POP'       => array('STACK'=>3,'0'=>2,'ESP'=>3),//将值弹出堆栈		reg16				[r:	o16 58+r]				8086
  'POPA'      => array('STACK'=>3,'DI'=>2, 'SI'=>2, 'BP'=>2, 'BX'=>2, 'DX'=>2, 'CX'=>2, 'AX'=>2,'ESP'=>3),//弹出所有通用寄存器		void				[	odf 61]					186,NOLONG
  'POPAD'     => array('STACK'=>3,'EDI'=>2,'ESI'=>2,'EBP'=>2,'EBX'=>2,'EDX'=>2,'ECX'=>2,'EAX'=>2,'ESP'=>3),//弹出所有通用寄存器		void				[	o32 61]					386,NOLONG
  'POPAW'	  => array('STACK'=>3,'DI'=>2, 'SI'=>2, 'BP'=>2, 'BX'=>2, 'DX'=>2, 'CX'=>2, 'AX'=>2,'ESP'=>3),//	void				[	o16 61]					186,NOLONG
  'POPF'      => array('STACK'=>3,'ESP'=>3,'OF' => 2,'SF' => 2,'ZF' => 2,'AF' => 2,'CF' => 2,'PF' => 2,'DF' => 2,'TF' => 2,'IF' => 2,'NT' => 2),//All flags may be affected;		void				[	odf 9d]					8086
  'POPFD'	  => array('STACK'=>3,'ESP'=>3,'OF' => 2,'SF' => 2,'ZF' => 2,'AF' => 2,'CF' => 2,'PF' => 2,'DF' => 2,'TF' => 2,'IF' => 2),//All flags may be affected;	void				[	o32 9d]					386,NOLONG
  'POPFQ'	  => array('STACK'=>3,'ESP'=>3,'OF' => 2,'SF' => 2,'ZF' => 2,'AF' => 2,'CF' => 2,'PF' => 2,'DF' => 2,'TF' => 2,'IF' => 2),//All flags may be affected,	void				[	o32 9d]					X64
  'POPFW'	  => array('STACK'=>3,'ESP'=>3,'OF' => 2,'SF' => 2,'ZF' => 2,'AF' => 2,'CF' => 2,'PF' => 2,'DF' => 2,'TF' => 2,'IF' => 2),//All flags may be affected	void				[	o16 9d]					8086
  'POR'       => array(),//逻辑位或		mmxreg,mmxrm			[rm:	np o64nw 0f eb /r]			PENT,MMX,SQ
  'PREFETCH'  => array(),//将数据预取到缓存	mem				[m:	0f 0d /0]				PENT,3DNOW,SQ
  'PREFETCHW' => array(),//将数据预取到缓存	mem				[m:	0f 0d /1]				PENT,3DNOW,SQ

//PSUBSIW		mmxreg,mmxrm			[rm:	o64nw 0f 55 /r]				PENT,MMX,SQ,CYRIX
  'PUSH'      => array('STACK'=>3,'0'=>1,'ESP'=>3),//将字或双字压入堆栈		imm64				[i:	o64nw 68+s ibd,s]			X64,AR0,SZ
  'PUSHA'     => array('STACK'=>3,'AX'=>1, 'CX'=>1, 'DX'=>1, 'BX'=>1, 'SP'=>1, 'BP'=>1, 'SI'=>1, 'DI'=>1, 'ESP'=>3),//压入所有通用寄存器
  'PUSHAD'	  => array('STACK'=>3,'EDI'=>1,'ESI'=>1,'EBP'=>1,'EBX'=>1,'EDX'=>1,'ECX'=>1,'EAX'=>1,'ESP'=>3),//压入所有通用寄存器	void				[	o32 60]					386,NOLONG
  'PUSHAW'    => array('STACK'=>3,'EDI'=>1,'ESI'=>1,'EBP'=>1,'EBX'=>1,'EDX'=>1,'ECX'=>1,'EAX'=>1,'ESP'=>3),//压入所有通用寄存器		void				[	o16 60]					186,NOLONG
  'PUSHF'     => array('STACK'=>3,'ESP'=>3,'OF'=>1,'SF'=>1,'ZF'=>1,'AF'=>1,'CF'=>1,'PF'=>1,'DF'=>1,'TF'=>1,'IF'=>1),//将 EFLAGS 寄存器压入堆栈		void				[	odf 9c]					8086
  'PUSHFD'    => array('STACK'=>3,'ESP'=>3,'OF'=>1,'SF'=>1,'ZF'=>1,'AF'=>1,'CF'=>1,'PF'=>1,'DF'=>1,'TF'=>1,'IF'=>1),//将 EFLAGS 寄存器压入堆栈		void				[	o32 9c]					386,NOLONG
  'PUSHFQ'    => array('STACK'=>3,'ESP'=>3,'OF'=>1,'SF'=>1,'ZF'=>1,'AF'=>1,'CF'=>1,'PF'=>1,'DF'=>1,'TF'=>1,'IF'=>1),//将 EFLAGS 寄存器压入堆栈		void				[	o32 9c]					X64
  'PUSHFW'    => array('STACK'=>3,'ESP'=>3,'OF'=>1,'SF'=>1,'ZF'=>1,'AF'=>1,'CF'=>1,'PF'=>1,'DF'=>1,'TF'=>1,'IF'=>1),//将 EFLAGS 寄存器压入堆栈		void				[	o16 9c]					8086
  'PXOR'      => array(),//逻辑异或		mmxreg,mmxrm			[rm:	np o64nw 0f ef /r]			PENT,MMX,SQ
  'RCL'       => array('0'=>3,'1'=>1,'CF'=>3,'OF'=>2),//循环左移 9 位（CF 与 r/m8）一次
  'RCR'       => array('0'=>3,'1'=>1,'CF'=>3,'OF'=>2),//循环右移 9 位（CF 与 r/m8）一次		rm8,unity			[m-:	d0 /3]					8086
  'RDSHR'     => array('0'=>2),//		rm32				[m:	o32 0f 36 /0]				P6,CYRIX,SMM
  'RDMSR'     => array('ECX'=>1,'EDX'=>2,'EAX'=>2),//将 ECX 指定的 MSR 加载到 EDX:EAX		void				[	0f 32]					PENT,PRIV
  'RDPMC'     => array('ECX'=>1,'EDX'=>2,'EAX'=>2),//将 ECX 指定的性能监视计数器读入 EDX:EAX		void				[	0f 33]					P6
  'RDTSC'     => array('EDX'=>2,'EAX'=>2),//将时间标签计数器读入 EDX:EAX	void				[	0f 31]					PENT
  'RDTSCP'    => array('EDX'=>2,'EAX'=>2,'ECX'=>2),//Read Time-Stamp Counter and Processor ID		void				[	0f 01 f9]				X86_64
  'REP'       => array('isPrefix'=>1,'CX'=>3,'ZF'=>3),//重复字符串操作前缀
  'REPE'      => array('isPrefix'=>1,'CX'=>3,'ZF'=>3),//重复字符串操作前缀
  'REPZ'      => array('isPrefix'=>1,'CX'=>3,'ZF'=>3),//重复字符串操作前缀
  'REPNE'     => array('isPrefix'=>1,'CX'=>3,'ZF'=>3),//重复字符串操作前缀
  'REPNZ'     => array('isPrefix'=>1,'CX'=>3,'ZF'=>3),//重复字符串操作前缀
  'RET'       => array('multi_op' => 2,
                       '0' => array('STACK'=>3,       'SP'=>3,'IP'=>2),
					   '1' => array('STACK'=>3,'0'=>1,'SP'=>3,'IP'=>2),					   
					   ),//		imm				[i:	c2 iw]					8086,SW
  'RETF'      => array('STACK'=>3,'0'=>1,'SP'=>3,'IP'=>2),//FAR RET 		imm				[i:	ca iw]					8086,SW
  'RETN'	  => array('STACK'=>3,'0'=>1,'SP'=>3,'IP'=>2),//NEAR RET	imm				[i:	c2 iw]					8086,SW
  'ROL'       => array('0'=>3,'1'=>1,'CF'=>3,'OF'=>2),//循环左移 32 位 r/m32 一次		rm8,unity			[m-:	d0 /0]					8086
  'ROR'       => array('0'=>3,'1'=>1,'CF'=>3,'OF'=>2),//		rm8,unity			[m-:	d0 /1]					8086
/*
RDM		void				[	0f 3a]					P6,CYRIX,ND
RSDC		reg_sreg,mem80			[rm:	0f 79 /r]				486,CYRIX,SMM
RSLDT		mem80				[m:	0f 7b /0]				486,CYRIX,SMM
*/
  'RSM'       => array('OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'DF'=>2,'TF'=>2,'IF'=>2,'NT' => 2,'RF' => 2),//从系统管理模式恢复		void				[	0f aa]					PENT,SMM
//RSTS		mem80				[m:	0f 7d /0]				486,CYRIX,SMM
  'SAHF'      => array('AH'=>1,              'SF'=>2,'ZF'=>2,'AF'=>2,'PF'=>2,'CF'=>2 ),//将 AH 存储到标志		void				[	9e]					8086
  'SAL'       => array('0'=>3,'1'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'PF'=>2,'CF'=>2),//移位		rm64,imm			[mi:	o64 c1 /4 ib,u]				X64,ND,SB
//SALC		void				[	d6]					8086,UNDOC
  'SAR'       => array('0'=>3,'1'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'PF'=>2,'CF'=>2),//移位			rm8,unity			[m-:	d0 /7]					8086
  'SBB'       => array('0'=>3,'1'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>3,'PF'=>2),//带借位整数减法		mem,reg8			[mr:	hle 18 /r]				8086,SM,LOCK
  'SCASB'     => array( 'AL'=>1, 'DI'=>3,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),//扫描字符串		void				[	repe ae]				8086
  'SCASD'     => array('EAX'=>1,'EDI'=>3,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),//扫描字符串		void				[	repe o32 af]				386
  'SCASQ'     => array( 'AX'=>1,'EDI'=>3,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),//扫描字符串		void				[	repe o64 af]				X64
  'SCASW'     => array('RAX'=>1,'EDI'=>3,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),//扫描字符串		void				[	repe o16 af]				8086
  'SETA'      => array('0'=>2,'CF'=>1,'ZF'=>1), //高于（CF=0 且 ZF=0）时设置字节
  'SETAE'     => array('0'=>2,'CF'=>1), //高于或等于 (CF=0)时设置字节
  'SETB'      => array('0'=>2,'CF'=>1), //低于 (CF=1) 时设置字节
  'SETBE'     => array('0'=>2,'CF'=>1,'ZF'=>1), //低于或等于（CF=1 或 ZF=1）时设置字节
  'SETC'      => array('0'=>2,'CF'=>1), //进位 (CF=1) 时设置字节
  'SETE'      => array('0'=>2,'ZF'=>1), //等于 (ZF=1) 时设置字节
  'SETG'      => array('0'=>2,'SF'=>1,'OF'=>1,'ZF'=>1), //大于（ZF=0 且 SF=OF）时设置字节
  'SETGE'     => array('0'=>2,'SF'=>1,'OF'=>1), //大于或等于 (SF=OF) 时设置字节
  'SETL'      => array('0'=>2,'SF'=>1,'OF'=>1), //小于 (SF<>OF) 时设置字节
  'SETLE'     => array('0'=>2,'SF'=>1,'OF'=>1,'ZF'=>1), //小于或等于（ZF=1 或 SF<>OF）时设置字节
  'SETNA'     => array('0'=>2,'CF'=>1,'ZF'=>1), //不高于（CF=1 或 ZF=1）时设置字节
  'SETNAE'    => array('0'=>2,'CF'=>1), //不高于或等于 (CF=1) 时设置字节
  'SETNB'     => array('0'=>2,'CF'=>1), //不低于 (CF=0) 时设置字节
  'SETNBE'    => array('0'=>2,'CF'=>1,'ZF'=>1), //不低于或等于（CF=0 且 ZF=0）时设置字节
  'SETNC'     => array('0'=>2,'CF'=>1), //无进位 (CF=0) 时设置字节
  'SETNE'     => array('0'=>2,'ZF'=>1), //等于 (ZF=0) 时设置字节
  'SETNG'     => array('0'=>2,'SF'=>1,'OF'=>1,'ZF'=>1), //不大于（ZF=1 或 SF<>OF）时设置字节
  'SETNGE'    => array('0'=>2,'SF'=>1,'OF'=>1), //不大于或等于 (SF<>OF) 时设置字节
  'SETNL'     => array('0'=>2,'SF'=>1,'OF'=>1), //不小于 (SF=OF) 时设置字节
  'SETNLE'    => array('0'=>2,'SF'=>1,'OF'=>1,'ZF'=>1), //不小于或等于（ZF=0 且 SF=OF）时设置字节
  'SETNO'     => array('0'=>2,'OF'=>1), //不上溢 (OF=0) 时设置字节
  'SETNP'     => array('0'=>2,'PF'=>1), //奇校验 (PF=0) 时设置字节
  'SETNS'     => array('0'=>2,'SF'=>1), //正数 (SF=0) 时设置字节
  'SETNZ'     => array('0'=>2,'ZF'=>1), //不为零 (ZF=0) 时设置字节
  'SETO'      => array('0'=>2,'OF'=>1), //上溢 (OF=1) 时设置字节
  'SETP'      => array('0'=>2,'PF'=>1), //偶校验 (PF=1) 时设置字节
  'SETPE'     => array('0'=>2,'PF'=>1), //偶校验 (PF=1) 时设置字节
  'SETPO'     => array('0'=>2,'PF'=>1), //奇校验 (PF=0) 时设置字节
  'SETS'      => array('0'=>2,'SF'=>1), //负数 (SF=1) 时设置字节
  'SETZ'      => array('0'=>2,'ZF'=>1), //为零 (ZF=1) 时设置字节
  'SFENCE'    => array(),//存储边界		void				[	0f ae f8]				X64,AMD
  'SGDT'      => array('0'=>2),//存储全局表寄存器		mem				[m:	0f 01 /0]				286
  'SHL'       => array('0'=>3,'1'=>1,       'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'PF'=>2,'CF'=>2),//移位		rm8,unity			[m-:	d0 /4]					8086
  'SHLD'      => array('0'=>3,'1'=>1,'2'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'PF'=>2,'CF'=>2),//双精度左移		reg64,reg64,reg_cl		[mr-:	o64 0f a5 /r] X64
  'SHR'       => array('0'=>3,'1'=>1,       'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'PF'=>2,'CF'=>2),//移位	rm8,reg_cl			[m-:	d2 /5]					8086
  'SHRD'      => array('0'=>3,'1'=>1,'2'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'PF'=>2,'CF'=>2),//双精度右移		reg64,reg64,reg_cl		[mr-:	o64 0f ad /r] X64
  'SIDT'      => array('0'=>2),//存储中断描述符表格寄存器		mem				[m:	0f 01 /1]				286
  'SLDT'      => array('0'=>2),//存储局部描述符表格寄存		mem				[m:	0f 00 /0]				286
/*
SKINIT		void				[	0f 01 de]				X64
SMI		void				[	f1]					386,UNDOC
SMINT		void				[	0f 38]					P6,CYRIX,ND
; Older Cyrix chips had this; they had to move due to conflict with MMX
SMINTOLD	void				[	0f 7e]					486,CYRIX,ND
*/
  'SMSW'      => array('0'=>2),//存储机器状态字		reg32				[m:	o32 0f 01 /4]				386
  'STC'       => array('CF'=>2),//设置进位标志		void				[	f9]					8086
  'STD'       => array('DF'=>2),//设置方向标志		void				[	fd]					8086
//STGI		void				[	0f 01 dc]				X64
  'STI'       => array('IF'=>2),//设置中断标志		void				[	fb]					8086
  'STOSB'     => array( 'AL'=>1, 'DI'=>3,'DF'=>1),//存储字符串		void				[	aa]					8086
  'STOSD'     => array('EAX'=>1, 'DI'=>3,'DF'=>1),//存储字符串		void				[	o32 ab]					386
  'STOSQ'     => array('RAX'=>1, 'DI'=>3,'DF'=>1),//存储字符串		void				[	o64 ab]					X64
  'STOSW'     => array( 'AX'=>1, 'DI'=>3,'DF'=>1),//存储字符串		void				[	o16 ab]					8086
  'STR'       => array('0'=>2),//存储任务寄存		reg64				[m:	o64 0f 00 /1]				X64
  'SUB'       => array('0'=>3,'1'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'PF'=>2,'CF'=>2),//减法		mem,reg8			[mr:	hle 28 /r]				8086,SM,LOCK
/*
SVDC		mem80,reg_sreg			[mr:	0f 78 /r]				486,CYRIX,SMM
SVLDT		mem80				[m:	0f 7a /0]				486,CYRIX,SMM,ND
SVTS		mem80				[m:	0f 7c /0]				486,CYRIX,SMM
*/
  'SWAPGS'    => array(),//	Swap GS Base Register	void				[	0f 01 f8]				X64
  'SYSCALL'   => array('RCX'=>2,'IP'=>2,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'DF'=>2,'TF'=>2,'IF'=>2),// Fast System Call		void				[	0f 05]					P6,AMD
  'SYSENTER'  => array('IP'=>2),//快速转换到系统调用入口	void				[	0f 34]					P6
  'SYSEXIT'   => array('IP'=>2),// 从系统调用入口快速转出		void				[	0f 35]					P6,PRIV
  'SYSRET'    => array('RCX'=>2,'IP'=>2,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'DF'=>2,'TF'=>2,'IF'=>2),// Return From Fast System Call		void				[	0f 07]					P6,PRIV,AMD
  'TEST'      => array('0'=>1,'1'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'PF'=>2,'CF'=>2),//逻辑比较
/*
UD0		void				[	0f ff]					186,UNDOC
UD1		void				[	0f b9]					186,UNDOC
UD2B		void				[	0f b9]					186,UNDOC,ND
*/
  'UD2'       => array(),//未定义的指令
/*
UD2A		void				[	0f 0b]					186,ND
UMOV		mem,reg8			[mr:	np 0f 10 /r]				386,UNDOC,SM,ND
UMOV		reg8,reg8			[mr:	np 0f 10 /r]				386,UNDOC,ND
UMOV		mem,reg16			[mr:	np o16 0f 11 /r]			386,UNDOC,SM,ND
UMOV		reg16,reg16			[mr:	np o16 0f 11 /r]			386,UNDOC,ND
UMOV		mem,reg32			[mr:	np o32 0f 11 /r]			386,UNDOC,SM,ND
UMOV		reg32,reg32			[mr:	np o32 0f 11 /r]			386,UNDOC,ND
UMOV		reg8,mem			[rm:	np 0f 12 /r]				386,UNDOC,SM,ND
UMOV		reg8,reg8			[rm:	np 0f 12 /r]				386,UNDOC,ND
UMOV		reg16,mem			[rm:	np o16 0f 13 /r]			386,UNDOC,SM,ND
UMOV		reg16,reg16			[rm:	np o16 0f 13 /r]			386,UNDOC,ND
UMOV		reg32,mem			[rm:	np o32 0f 13 /r]			386,UNDOC,SM,ND
UMOV		reg32,reg32			[rm:	np o32 0f 13 /r]			386,UNDOC,ND
*/
  'VERR'      => array('0'=>1,'ZF'=>2),//验证段的读取或写入		reg16				[m:	0f 00 /4]				286,PROT
  'VERW'	  => array('0'=>1,'ZF'=>2),//验证段的读取或写入  	mem				[m:	0f 00 /5]				286,PROT

  'FWAIT'     => array(),//检查未决的无掩码浮点异常。		void				[	wait]					8086
  'WBINVD'    => array(),//写回并使缓存无效		void				[	0f 09]					486,PRIV
  'WRSHR'     => array('0'=>1),//
  'WRMSR'     => array('EDX'=>1,'EAX'=>1,'ECX'=>1),//将 EDX:EAX 中的值写入 ECX 指定的 MSR	void				[	0f 30]					PENT,PRIV
  'XADD'      => array('0'=>3,'1'=>3,'CF'=>2,'PF'=>2,'AF'=>2,'SF'=>2,'ZF'=>2,'OF'=>2),//交换并相加		reg64,reg64			[mr:	o64 0f c1 /r]				X64
/*
XBTS		reg16,mem			[rm:	o16 0f a6 /r]				386,SW,UNDOC,ND
XBTS		reg16,reg16			[rm:	o16 0f a6 /r]				386,UNDOC,ND
XBTS		reg32,mem			[rm:	o32 0f a6 /r]				386,SD,UNDOC,ND
XBTS		reg32,reg32			[rm:	o32 0f a6 /r]				386,UNDOC,ND
*/
  'XCHG'      => array('0'=>3,'1'=>3),//交换寄存器/内存与寄存器		reg64,reg_rax			[r-:	o64 90+r]				X64
  'XLATB'     => array('AL'=>3,'BX'=>1),//表格查询转译		void				[	d7]					8086
  'XLAT'      => array('AL'=>3,'BX'=>1),//表格查询转译		void				[	d7]					8086
  'XOR'       => array('0'=>3,'1'=>1,'CF'=>2,'PF'=>2,'AF'=>2,'SF'=>2,'ZF'=>2,'OF'=>2),//逻辑异或		mem,imm32			[mi:	hle o32 81+s /6 ibd]			386,SM,LOCK


//# Katmai Streaming SIMD instructions (SSE -- a.k.a. KNI, XMM, MMX2)
  'ADDPS' => array(),
  'ADDSS' => array(),
  'ANDPS' => array(),
  'ANDNPS'=> array(),

//; CMPPS/CMPSS must come after the specific ops; that way the disassembler will find the
//; specific ops first and only disassemble illegal ones as cmpps/cmpss.
  'CMPPS' => array(),
  'CMPSS' => array(),
  'COMISS'=> array(),
  'CVTPI2PS' => array(),
  'CVTPS2PI' => array(),
  'CVTSI2SS' => array('0'=>1,'1'=>1),
  'CVTSS2SI' => array('0'=>2),
  'CVTTPS2PI'=> array(),             //	mmxreg,xmmrm			[rm:	np 0f 2c /r]				KATMAI,SSE,MMX,SQ
  'CVTTSS2SI'=>	array('0'=>2),
  'DIVPS'	 => array(),
  'DIVSS'    => array(),
  'MAXPS'	 => array(),//最大压缩单精度浮点值
  'MAXSS'    => array(),//最大标量单精度浮点值
  'MINPS'    => array(),//最小压缩单精度浮点值		xmmreg,xmmrm128			[rm:	np 0f 5d /r]				KATMAI,SSE
  'MINSS'    => array(),//最小标量单精度浮点值		xmmreg,xmmrm32			[rm:	f3 0f 5d /r]				KATMAI,SSE
  'MOVAPS'   => array(),//移动对齐的压缩单精度浮点值		xmmreg,xmmrm128			[rm:	np 0f 28 /r]				KATMAI,SSE
  'MOVHPS'   => array(),//移动高位压缩单精度浮点值		xmmreg,mem64			[rm:	np 0f 16 /r]				KATMAI,SSE
  'MOVLHPS'  => array(),//压缩单精度浮点值从低位移到高位		xmmreg,xmmreg			[rm:	np 0f 16 /r]				KATMAI,SSE
  'MOVLPS'   => array(),//移动低位压缩单精度浮点值		mem64,xmmreg			[mr:	np 0f 13 /r]				KATMAI,SSE
  'MOVHLPS'  => array(),//压缩单精度浮点值从高位移到低位 		xmmreg,xmmreg			[rm:	np 0f 12 /r]				KATMAI,SSE
  'MOVMSKPS' => array('0'=>2),//提取压缩单精度浮点值符号掩码	reg32,xmmreg			[rm:	np 0f 50 /r]				KATMAI,SSE
  'MOVNTPS'  => array(),//非暂时移动对齐的四个压缩单精度浮点值		mem128,xmmreg			[mr:	np 0f 2b /r]				KATMAI,SSE
  'MOVSS'    => array(),//移动标量单精度浮点值		xmmreg,xmmrm32			[rm:	f3 0f 10 /r]				KATMAI,SSE
  'MOVUPS'   => array(),//移动未对齐的压缩单精度浮点值		xmmrm128,xmmreg			[mr:	np 0f 11 /r]				KATMAI,SSE
  'MULPS'    => array(),//压缩单精度浮点乘法		xmmreg,xmmrm128			[rm:	np 0f 59 /r]				KATMAI,SSE
  'MULSS'    => array(),//标量单精度浮点乘法		xmmreg,xmmrm32			[rm:	f3 0f 59 /r]				KATMAI,SSE
  'ORPS'     => array(),//单精度浮点值逻辑位或		xmmreg,xmmrm128			[rm:	np 0f 56 /r]				KATMAI,SSE
  'RCPPS'    => array(),//压缩单精度浮点倒数		xmmreg,xmmrm128			[rm:	np 0f 53 /r]				KATMAI,SSE
  'RCPSS'    => array(),//标量单精度浮点倒数		xmmreg,xmmrm32			[rm:	f3 0f 53 /r]				KATMAI,SSE

  'RSQRTPS'  => array(),//压缩单精度浮点平方根倒数		xmmreg,xmmrm128			[rm:	np 0f 52 /r]				KATMAI,SSE
  'RSQRTSS'  => array(),//标量单精度浮点平方根倒数		xmmreg,xmmrm32			[rm:	f3 0f 52 /r]				KATMAI,SSE
  'SHUFPS'   => array(),//单精度浮点值乱序		xmmreg,xmmrm128,imm8		[rmi:	np 0f c6 /r ib,u]			KATMAI,SSE
  'SQRTPS'   => array(),//压缩单精度浮点平方根		xmmreg,xmmrm128			[rm:	np 0f 51 /r]				KATMAI,SSE
  'SQRTSS'   => array(),//标量单精度浮点平方根		xmmreg,xmmrm32			[rm:	f3 0f 51 /r]				KATMAI,SSE
  'STMXCSR'  => array(),//		mem32				[m:	0f ae /3]				KATMAI,SSE
  'SUBPS'    => array(),//压缩单精度浮点减法		xmmreg,xmmrm128			[rm:	np 0f 5c /r]				KATMAI,SSE
  'SUBSS'    => array(),//标量单精度浮点减法		xmmreg,xmmrm32			[rm:	f3 0f 5c /r]				KATMAI,SSE
  'UCOMISS'  => array('OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),//无序比较标量单精度浮点值并设置 EFLAGS		xmmreg,xmmrm32			[rm:	np 0f 2e /r]				KATMAI,SSE
  'UNPCKHPS' => array(),//扩展高位压缩单精度浮点值	xmmreg,xmmrm128			[rm:	np 0f 15 /r]				KATMAI,SSE
  'UNPCKLPS' => array(),//扩展低位压缩单精度浮点值	xmmreg,xmmrm128			[rm:	np 0f 14 /r]				KATMAI,SSE
  'XORPS'    => array(),//单精度浮点值逻辑位异或		xmmreg,xmmrm128			[rm:	np 0f 57 /r]				KATMAI,SSE

//;# Introduced in Deschutes but necessary for SSE support
  'FXRSTOR'  => array(),//还原 x87 FPU、MMX? 技术、数据流单指令多数据扩展指令集、第二代数据流单指令多数据扩展指令集状态
  'FXRSTOR64'=> array(),
  'FXSAVE'   => array(),//保存 x87 FPU、MMX? 技术、数据流单指令多数据扩展指令集、第二代数据流单指令多数据扩展指令集状态
  'FXSAVE64' => array(),

//;# XSAVE group (AVX and extended state)
//; Introduced in late Penryn ... we really need to clean up the handling
//; of CPU feature bits.
  'XGETBV'   => array('ECX'=>1,'EDX'=>2,'EAX'=>2),//Get Value of Extended Control Register		void				[	np 0f 01 d0]				NEHALEM
  'XSETBV'   => array('ECX'=>1,'EDX'=>1,'EAX'=>1),//Set Extended Control Register		void				[	np 0f 01 d1]				NEHALEM,PRIV
  'XSAVE'    => array('EDX'=>1,'EAX'=>1),//Save Processor Extended States		mem				[m:	0f ae /4]				NEHALEM
//XSAVE64		mem				[m:	o64 0f ae /4]				LONG,NEHALEM
  'XSAVEOPT' => array('EDX'=>1,'EAX'=>1),//Save Processor Extended States Optimized	mem				[m:	0f ae /6]				FUTURE
//XSAVEOPT64	mem				[m:	o64 0f ae /6]				LONG,FUTURE
  'XRSTOR'   => array('EDX'=>1,'EAX'=>1),//Restore Processor Extended States		mem				[m:	0f ae /5]				NEHALEM
//XRSTOR64	mem				[m:	o64 0f ae /5]				LONG,NEHALEM


//; These instructions are not SSE-specific; they are
//;# Generic memory operations
//; and work even if CR4.OSFXFR == 0
  'PREFETCHNTA'    => array(),//将数据预取到缓存	mem				[m:	0f 18 /0]				KATMAI
  'PREFETCHT0'     => array(),//将数据预取到缓存	mem				[m:	0f 18 /1]				KATMAI
  'PREFETCHT1'     => array(),//将数据预取到缓存	mem				[m:	0f 18 /2]				KATMAI
  'PREFETCHT2'     => array(),//将数据预取到缓存	mem				[m:	0f 18 /3]				KATMAI
//SFENCE		void				[	0f ae f8]				KATMAI


//;# New MMX instructions introduced in Katmai
  'MASKMOVQ' => array('DI'=>1),//带掩码移动四字
  'MOVNTQ'   => array(),//非暂时移动四字		mem,mmxreg			[mr:	np 0f e7 /r]				KATMAI,MMX,SQ


//; PINSRW is documented as using a reg32, but it's really using only 16 bit
//; -- accept either, but be truthful in disassembly
  'PSHUFW'   => array(),//压缩字乱序		mmxreg,mmxrm,imm		[rmi:	np o64nw 0f 70 /r ib]			KATMAI,MMX,SM2,SB,AR2

//;# Willamette SSE2 Cacheability Instructions
  'MASKMOVDQU' => array('DI'=>1),//带掩码移动未对齐的双四字
//; CLFLUSH needs its own feature flag implemented one day
//CLFLUSH		mem				[m:	0f ae /7]				WILLAMETTE,SSE2
  'MOVNTDQ'    => array(),//非暂时移动双四字		mem,xmmreg			[mr:	66 0f e7 /r]				WILLAMETTE,SSE2,SO
  'MOVNTI'     => array('1'=>1),//非暂时移动双字		mem,reg64			[mr:	o64 np 0f c3 /r]			X64,SQ
  'MOVNTPD'    => array(),//非暂时移动压缩双精度浮点值		mem,xmmreg			[mr:	66 0f 2b /r]				WILLAMETTE,SSE2,SO
//LFENCE		void				[	0f ae e8]				WILLAMETTE,SSE2
//MFENCE

//;# Willamette MMX instructions (SSE2 SIMD Integer Instructions)
  //MOVD		mem,xmmreg			[mr:	66 norexw 0f 7e /r]			WILLAMETTE,SSE2,SD
  'MOVDQA'    => array(),//移动对齐的双四字		xmmreg,xmmreg			[rm:	66 0f 6f /r]				WILLAMETTE,SSE2
  'MOVDQU'    => array(),//移动未对齐的双四字		xmmreg,xmmreg			[mr:	f3 0f 7f /r]				WILLAMETTE,SSE2
  'MOVDQ2Q'   => array(),//移动四字		mmxreg,xmmreg			[rm:	f2 0f d6 /r]				WILLAMETTE,SSE2
  'MOVQ'      => array(),//移动四字		rm64,xmmreg			[mr:	66 o64 0f 7e /r]			X64,SSE2
  'MOVQ2DQ'   => array(),//移动四字		xmmreg,mmxreg			[rm:	f3 0f d6 /r]				WILLAMETTE,SSE2
//PACKSSWB	xmmreg,xmmrm			[rm:	66 0f 63 /r]				WILLAMETTE,SSE2,SO
//PACKSSDW	xmmreg,xmmrm			[rm:	66 0f 6b /r]				WILLAMETTE,SSE2,SO
//PACKUSWB	xmmreg,xmmrm			[rm:	66 0f 67 /r]				WILLAMETTE,SSE2,SO
//PADDB		xmmreg,xmmrm			[rm:	66 0f fc /r]				WILLAMETTE,SSE2,SO
//PADDW		xmmreg,xmmrm			[rm:	66 0f fd /r]				WILLAMETTE,SSE2,SO
//PADDD		xmmreg,xmmrm			[rm:	66 0f fe /r]				WILLAMETTE,SSE2,SO
//PADDQ		mmxreg,mmxrm			[rm:	np 0f d4 /r]				WILLAMETTE,MMX,SQ
  'PADDQ'     => array(),//压缩四字加法 Add Packed Quadword Integers		xmmreg,xmmrm			[rm:	66 0f d4 /r]				WILLAMETTE,SSE2,SO
//PADDSB		xmmreg,xmmrm			[rm:	66 0f ec /r]				WILLAMETTE,SSE2,SO
//PADDSW		xmmreg,xmmrm			[rm:	66 0f ed /r]				WILLAMETTE,SSE2,SO
//PADDUSB		xmmreg,xmmrm			[rm:	66 0f dc /r]				WILLAMETTE,SSE2,SO
//PADDUSW		xmmreg,xmmrm			[rm:	66 0f dd /r]				WILLAMETTE,SSE2,SO
//PAND		xmmreg,xmmrm			[rm:	66 0f db /r]				WILLAMETTE,SSE2,SO
//PANDN		xmmreg,xmmrm			[rm:	66 0f df /r]				WILLAMETTE,SSE2,SO
  'PAVGB'     => array(),//压缩平均		xmmreg,xmmrm			[rm:	66 0f e0 /r]				WILLAMETTE,SSE2,SO
  'PAVGW'	  => array(),//压缩平均	xmmreg,xmmrm			[rm:	66 0f e3 /r]				WILLAMETTE,SSE2,SO
  'PCMPEQB'   => array(),//压缩比较等于 		xmmreg,xmmrm			[rm:	66 0f 74 /r]				WILLAMETTE,SSE2,SO
  'PCMPEQW'   => array(),//压缩比较等于		xmmreg,xmmrm			[rm:	66 0f 75 /r]				WILLAMETTE,SSE2,SO
  'PCMPEQD'   => array(),//压缩比较等于		xmmreg,xmmrm			[rm:	66 0f 76 /r]				WILLAMETTE,SSE2,SO
  'PCMPGTB'   => array(),//压缩比较大于		xmmreg,xmmrm			[rm:	66 0f 64 /r]				WILLAMETTE,SSE2,SO
  'PCMPGTW'   => array(),//压缩比较大于		xmmreg,xmmrm			[rm:	66 0f 65 /r]				WILLAMETTE,SSE2,SO
  'PCMPGTD'   => array(),//压缩比较大于		xmmreg,xmmrm			[rm:	66 0f 66 /r]				WILLAMETTE,SSE2,SO
//PEXTRW		reg32,xmmreg,imm		[rmi:	66 0f c5 /r ib,u]			WILLAMETTE,SSE2,SB,AR2
  'PINSRW'    => array('1'=>1),//插入字		xmmreg,mem16,imm		[rmi:	66 0f c4 /r ib,u]			WILLAMETTE,SSE2,SB,AR2
  'PMADDWD'   => array(),//压缩乘法与加法		xmmreg,xmmrm			[rm:	66 0f f5 /r]				WILLAMETTE,SSE2,SO
  'PMAXSW'    => array(),//压缩有符号字整数最大值		xmmreg,xmmrm			[rm:	66 0f ee /r]				WILLAMETTE,SSE2,SO
  'PMAXUB'    => array(),//压缩无符号字节整数最大值		xmmreg,xmmrm			[rm:	66 0f de /r]				WILLAMETTE,SSE2,SO
  'PMINSW'    => array(),//压缩有符号字整数最小值		xmmreg,xmmrm			[rm:	66 0f ea /r]				WILLAMETTE,SSE2,SO
  'PMINUB'    => array(),//压缩无符号字节整数最小值		xmmreg,xmmrm			[rm:	66 0f da /r]				WILLAMETTE,SSE2,SO
  'PMOVMSKB'  => array('0'=>2),//字节掩码移到通用寄存器	reg32,xmmreg			[rm:	66 0f d7 /r]				WILLAMETTE,SSE2
  'PMULHUW'   => array(),//压缩无符号乘法取高位		xmmreg,xmmrm			[rm:	66 0f e4 /r]				WILLAMETTE,SSE2,SO
  'PMULHW'    => array(),//压缩有符号乘法取高位		xmmreg,xmmrm			[rm:	66 0f e5 /r]				WILLAMETTE,SSE2,SO
  'PMULLW'    => array(),//压缩有符号乘法取低位		xmmreg,xmmrm			[rm:	66 0f d5 /r]				WILLAMETTE,SSE2,SO
  'PMULUDQ'   => array(),//无符号双字乘法		xmmreg,xmmrm			[rm:	66 0f f4 /r]				WILLAMETTE,SSE2,SO
//POR		xmmreg,xmmrm			[rm:	66 0f eb /r]				WILLAMETTE,SSE2,SO
  'PSADBW'    => array(),//压缩绝对差值之和		xmmreg,xmmrm			[rm:	66 0f f6 /r]				WILLAMETTE,SSE2,SO
  'PSHUFD'    => array(),//压缩双字乱序		xmmreg,xmmreg,imm		[rmi:	66 0f 70 /r ib]				WILLAMETTE,SSE2,SB,AR2
  'PSHUFHW'   => array(),//压缩高位字乱序		xmmreg,xmmreg,imm		[rmi:	f3 0f 70 /r ib]				WILLAMETTE,SSE2,SB,AR2
  'PSHUFLW'   => array(),//压缩低位字乱序		xmmreg,mem,imm			[rmi:	f2 0f 70 /r ib]				WILLAMETTE,SSE2,SM2,SB,AR2
  'PSLLDQ'    => array(),//压缩双四字逻辑左移		xmmreg,imm			[mi:	66 0f 73 /7 ib,u]			WILLAMETTE,SSE2,SB,AR1
  'PSLLW'     => array(),//压缩逻辑左移		xmmreg,xmmrm			[rm:	66 0f f1 /r]				WILLAMETTE,SSE2,SO
  'PSLLD'     => array(),//压缩逻辑左移		xmmreg,xmmrm			[rm:	66 0f f2 /r]				WILLAMETTE,SSE2,SO
  'PSLLQ'     => array(),//压缩逻辑左移		xmmreg,xmmrm			[rm:	66 0f f3 /r]				WILLAMETTE,SSE2,SO
  'PSRAW'     => array(),//压缩算术右移		xmmreg,imm			[mi:	66 0f 71 /4 ib,u]			WILLAMETTE,SSE2,SB,AR1
  'PSRAD'     => array(),//压缩算术右移		xmmreg,imm			[mi:	66 0f 72 /4 ib,u]			WILLAMETTE,SSE2,SB,AR1
  'PSRLDQ'    => array(),//压缩双四字逻辑右移		xmmreg,imm			[mi:	66 0f 73 /3 ib,u]			WILLAMETTE,SSE2,SB,AR1
  'PSRLW'     => array(),//压缩逻辑右移		xmmreg,xmmrm			[rm:	66 0f d1 /r]				WILLAMETTE,SSE2,SO
  'PSRLD'     => array(),//压缩逻辑右移		xmmreg,xmmrm			[rm:	66 0f d2 /r]				WILLAMETTE,SSE2,SO
  'PSRLQ'     => array(),//压缩逻辑右移		xmmreg,xmmrm			[rm:	66 0f d3 /r]				WILLAMETTE,SSE2,SO
  'PSUBB'     => array(),//压缩减法		xmmreg,xmmrm			[rm:	66 0f f8 /r]				WILLAMETTE,SSE2,SO
  'PSUBW'     => array(),//压缩减法		xmmreg,xmmrm			[rm:	66 0f f9 /r]				WILLAMETTE,SSE2,SO
  'PSUBD'     => array(),//压缩减法		xmmreg,xmmrm			[rm:	66 0f fa /r]				WILLAMETTE,SSE2,SO
  'PSUBQ'     => array(),//压缩减法		mmxreg,mmxrm			[rm:	np o64nw 0f fb /r]			WILLAMETTE,SSE2,SO
  'PSUBSB'    => array(),//压缩饱和减法		xmmreg,xmmrm			[rm:	66 0f e8 /r]				WILLAMETTE,SSE2,SO
  'PSUBSW'    => array(),//压缩饱和减法		xmmreg,xmmrm			[rm:	66 0f e9 /r]				WILLAMETTE,SSE2,SO
  'PSUBUSB'   => array(),//压缩无符号饱和减法		xmmreg,xmmrm			[rm:	66 0f d8 /r]				WILLAMETTE,SSE2,SO
  'PSUBUSW'   => array(),//压缩无符号饱和减法		xmmreg,xmmrm			[rm:	66 0f d9 /r]				WILLAMETTE,SSE2,SO
  'PUNPCKHBW' => array(),//扩展高位压缩数据	xmmreg,xmmrm			[rm:	66 0f 68 /r]				WILLAMETTE,SSE2,SO
  'PUNPCKHWD' => array(),//扩展高位压缩数据	xmmreg,xmmrm			[rm:	66 0f 69 /r]				WILLAMETTE,SSE2,SO
  'PUNPCKHDQ' => array(),//扩展高位压缩数据	xmmreg,xmmrm			[rm:	66 0f 6a /r]				WILLAMETTE,SSE2,SO
  'PUNPCKHQDQ'=> array(),//扩展高位压缩数据	xmmreg,xmmrm			[rm:	66 0f 6d /r]				WILLAMETTE,SSE2,SO
  'PUNPCKLBW' => array(),//扩展低位压缩数据	xmmreg,xmmrm			[rm:	66 0f 60 /r]				WILLAMETTE,SSE2,SO
  'PUNPCKLWD' => array(),//扩展低位压缩数据	xmmreg,xmmrm			[rm:	66 0f 61 /r]				WILLAMETTE,SSE2,SO
  'PUNPCKLDQ' => array(),//扩展低位压缩数据	xmmreg,xmmrm			[rm:	66 0f 62 /r]				WILLAMETTE,SSE2,SO
  'PUNPCKLQDQ'=> array(),//扩展低位压缩数据	xmmreg,xmmrm			[rm:	66 0f 6c /r]				WILLAMETTE,SSE2,SO
//PXOR		xmmreg,xmmrm			[rm:	66 0f ef /r]				WILLAMETTE,SSE2,SO

//# Willamette Streaming SIMD instructions (SSE2)
  'ADDPD' => array(),
  'ADDSD' => array(),
  'ANDPD' => array(),
  'ANDNPD'=> array(),
//; CMPPD/CMPSD must come after the specific ops; that way the disassembler will find the
//; specific ops first and only disassemble illegal ones as cmppd/cmpsd.
//'CMPSD' => array(), //注释掉，因为重复了
  'COMISD'=> array(),
  'CVTDQ2PD' => array(),
  'CVTDQ2PS' => array(),
  'CVTPD2DQ' => array(),
  'CVTPD2PI' => array(),
  'CVTPD2PS' => array(),
  'CVTPI2PD' => array(),
  'CVTPS2DQ' => array(),
  'CVTPS2PD' => array(),
  'CVTSD2SI' => array('0'=>2),
  'CVTSD2SS' => array(),
  'CVTSI2SD' => array('1'=>1),
  'CVTSS2SD' => array(),
  'CVTTPD2PI'=> array(),
  'CVTTPD2DQ'=>	array(),
  'CVTTPS2DQ'=> array(),
  'CVTTSD2SI'=> array('0'=>2),
  'DIVPD'	 => array(),
  'DIVSD'	 => array(),
  'MAXPD'    => array(),//最大压缩双精度浮点值
  'MAXSD'    => array(),//最大标量双精度浮点值
  'MINPD'	 => array(),//最小压缩双精度浮点值	xmmreg,xmmrm			[rm:	66 0f 5d /r]				WILLAMETTE,SSE2,SO
  'MINSD'    => array(),//最小标量双精度浮点值		xmmreg,xmmrm			[rm:	f2 0f 5d /r]				WILLAMETTE,SSE2
  'MOVAPD'   => array(),//移动对齐的压缩双精度浮点值		xmmreg,xmmreg			[rm:	66 0f 28 /r]				WILLAMETTE,SSE2
  'MOVHPD'   => array(),//移动高位压缩双精度浮点值		mem,xmmreg			[mr:	66 0f 17 /r]				WILLAMETTE,SSE2
  'MOVLPD'   => array(),//移动低位压缩双精度浮点值		xmmreg,mem			[rm:	66 0f 12 /r]				WILLAMETTE,SSE2
  'MOVMSKPD' => array('0'=>2),//取压缩双精度浮点值符号掩码	reg32,xmmreg			[rm:	66 0f 50 /r]				WILLAMETTE,SSE2
  //MOVSD		xmmreg,mem64			[rm:	f2 0f 10 /r]				WILLAMETTE,SSE2
  'MOVUPD'   => array(),//移动未对齐的压缩双精度浮点值		xmmreg,xmmreg			[rm:	66 0f 10 /r]				WILLAMETTE,SSE2
  'MULPD'    => array(),//压缩双精度浮点乘法		xmmreg,xmmrm			[rm:	66 0f 59 /r]				WILLAMETTE,SSE2,SO
  'MULSD'    => array(),//标量双精度浮点乘法		xmmreg,xmmrm			[rm:	f2 0f 59 /r]				WILLAMETTE,SSE2
  'ORPD'     => array(),//双精度浮点值逻辑位或		xmmreg,xmmrm			[rm:	66 0f 56 /r]				WILLAMETTE,SSE2,SO
  'SHUFPD'   => array(),//双精度浮点值乱序		xmmreg,mem,imm			[rmi:	66 0f c6 /r ib,u]			WILLAMETTE,SSE2,SM,SB,AR2
  'SQRTPD'   => array(),//压缩双精度浮点平方根		xmmreg,xmmrm			[rm:	66 0f 51 /r]				WILLAMETTE,SSE2,SO
  'SQRTSD'   => array(),//标量双精度浮点平方根		xmmreg,xmmrm			[rm:	f2 0f 51 /r]				WILLAMETTE,SSE2
  'SUBPD'    => array(),//压缩双精度浮点减法		xmmreg,xmmrm			[rm:	66 0f 5c /r]				WILLAMETTE,SSE2,SO
  'SUBSD'    => array(),//标量双精度浮点减法		xmmreg,xmmrm			[rm:	f2 0f 5c /r]				WILLAMETTE,SSE2
  'UCOMISD'  => array('OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),//无序比较标量双精度浮点值并设置 EFLAGS		xmmreg,xmmrm	[rm:	66 0f 2e /r]	WILLAMETTE,SSE2
  'UNPCKHPD' => array(),//扩展高位压缩双精度浮点值	xmmreg,xmmrm128			[rm:	66 0f 15 /r]				WILLAMETTE,SSE2
  'UNPCKLPD' => array(),//扩展低位压缩双精度浮点值	xmmreg,xmmrm128			[rm:	66 0f 14 /r]				WILLAMETTE,SSE2
  'XORPD'    => array(),//双精度浮点值逻辑位异或		xmmreg,xmmrm128			[rm:	66 0f 57 /r]				WILLAMETTE,SSE2

//# Prescott New Instructions (SSE3)
  'ADDSUBPD' => array(),
  'ADDSUBPS' => array(),
  'HADDPD'   => array(),//压缩双精度浮点水平加法
  'HADDPS'   => array(),//压缩单精度浮点水平加法
  'HSUBPD'   => array(),//压缩双精度浮点水平减法
  'HSUBPS'   => array(),//压缩单精度浮点水平减法

  'MOVDDUP'  => array(),//移动一个双精度浮点值并复制		xmmreg,xmmrm			[rm:	f2 0f 12 /r]				PRESCOTT,SSE3
  'MOVSHDUP' => array(),//移动压缩单精度浮点值高位并复制	xmmreg,xmmrm			[rm:	f3 0f 16 /r]				PRESCOTT,SSE3
  'MOVSLDUP' => array(),//移动压缩单精度浮点值低位并复制	xmmreg,xmmrm			[rm:	f3 0f 12 /r]				PRESCOTT,SSE3

//;# Tejas New Instructions (SSSE3)
  'PALIGNR'  => array(),//		mmxreg,mmxrm,imm		[rmi:	np 0f 3a 0f /r ib,u]			SSSE3,MMX,SQ
  'PHADDW'   => array(),//Packed Horizontal Add		xmmreg,xmmrm			[rm:	66 0f 38 01 /r]				SSSE3
  'PHADDD'   => array(),//Packed Horizontal Add		xmmreg,xmmrm			[rm:	66 0f 38 02 /r]				SSSE3
  'PHADDSW'  => array(),//Packed Horizontal Add and Saturate		xmmreg,xmmrm			[rm:	66 0f 38 03 /r]				SSSE3
  'PHSUBW'   => array(),//Packed Horizontal Subtract		mmxreg,mmxrm			[rm:	np 0f 38 05 /r]				SSSE3,MMX,SQ
  'PHSUBD'   => array(),//Packed Horizontal Subtract		mmxreg,mmxrm			[rm:	np 0f 38 06 /r]				SSSE3,MMX,SQ
  'PHSUBSW'  => array(),//Packed Horizontal Subtract and Saturate		xmmreg,xmmrm			[rm:	66 0f 38 07 /r]				SSSE3
  'PMADDUBSW'=> array(),//Multiply and Add Packed Signed and Unsigned Bytes	mmxreg,mmxrm			[rm:	np 0f 38 04 /r]				SSSE3,MMX,SQ
  'PMULHRSW' => array(),//Packed Multiply High with Round and Scale	xmmreg,xmmrm			[rm:	66 0f 38 0b /r]				SSSE3
  'PSHUFB'   => array(),//Packed Shuffle Bytes		mmxreg,mmxrm			[rm:	np 0f 38 00 /r]				SSSE3,MMX,SQ
  'PSIGNB'   => array(),//Packed SIGN		mmxreg,mmxrm			[rm:	np 0f 38 08 /r]				SSSE3,MMX,SQ
  'PSIGNW'   => array(),//Packed SIGN		xmmreg,xmmrm			[rm:	66 0f 38 09 /r]				SSSE3
  'PSIGND'   => array(),//Packed SIGN		xmmreg,xmmrm			[rm:	66 0f 38 0a /r]				SSSE3

//# Intel AES instructions
  'AESENC'     => array(),
  'AESENCLAST' => array(),
  'AESDEC'     => array(),
  'AESDECLAST' => array(),
  'AESIMC'     => array(),
  'AESKEYGENASSIST' => array(),

//; CMPPD/CMPSD must come after the specific ops; that way the disassembler will find the
//; specific ops first and only disassemble illegal ones as cmppd/cmpsd.
  'CMPPD' => array(),

//# Tejas New Instructions (SSSE3)
  'PABSB' => array(),//		xmmreg,xmmrm			[rm:	66 0f 38 1c /r]				SSSE3
  'PABSW' => array(),//		mmxreg,mmxrm			[rm:	np 0f 38 1d /r]				SSSE3,MMX,SQ
  'PABSD' => array(),//		mmxreg,mmxrm			[rm:	np 0f 38 1e /r]				SSSE3,MMX,SQ


//# Penryn New Instructions (SSE4.1)
  'BLENDPD'     => array(),
  'BLENDPS'     => array(),
  'BLENDVPD'    => array(),
  'BLENDVPS'    => array(),
  'DPPD'        => array(),
  'DPPS'		=> array(),
  'EXTRACTPS'   => array('0'=>2),
  'INSERTPS'    => array(), //	xmmreg,xmmrm,imm		[rmi:	66 0f 3a 21 /r ib,u]			SSE41,SD
  'MOVNTDQA'    => array(), //Load Double Quadword Non-Temporal Aligned Hint	xmmreg,mem			[rm:	66 0f 38 2a /r]				SSE41
  'MPSADBW'     => array(), //Compute Multiple Packed Sums of Absolute Difference		xmmreg,xmmrm,imm		[rmi:	66 0f 3a 42 /r ib,u]			SSE41
  'PACKUSDW'    => array(), //无符号饱和压缩	xmmreg,xmmrm			[rm:	66 0f 38 2b /r]				SSE41
  'PBLENDVB'    => array(), //Variable Blend Packed Bytes	xmmreg,xmmrm,xmm0		[rm-:	66 0f 38 10 /r]				SSE41
  'PBLENDW'     => array(), //Blend Packed Words		xmmreg,xmmrm,imm		[rmi:	66 0f 3a 0e /r ib,u]			SSE41
  'PCMPEQQ'     => array(), //Compare Packed Qword Data for Equal		xmmreg,xmmrm			[rm:	66 0f 38 29 /r]				SSE41
  'PEXTRB'      => array('0'=>2), //Extract Byte/Dword/Qword		reg64,xmmreg,imm		[mri:	o64 66 0f 3a 14 /r ib,u]		SSE41,X64
  'PEXTRD'		=> array('0'=>2), //rm32,xmmreg,imm			[mri:	66 0f 3a 16 /r ib,u]			SSE41
  'PEXTRQ'		=> array('0'=>2), //rm64,xmmreg,imm			[mri:	o64 66 0f 3a 16 /r ib,u]		SSE41,X64
  'PEXTRW'      => array('0'=>2), //提取字		reg32,xmmreg,imm		[mri:	66 0f 3a 15 /r ib,u]			SSE41
  'PHMINPOSUW'  => array('1'=>1),//Packed Horizontal Word Minimum 	xmmreg,xmmrm			[rm:	66 0f 38 41 /r]				SSE41
  'PINSRB'      => array('1'=>1),//Insert Byte/Dword/Qword		xmmreg,reg32,imm		[rmi:	66 0f 3a 20 /r ib,u]			SSE41,SB,AR2
  'PINSRD'		=> array('1'=>1),//Insert Byte/Dword/Qwordxmmreg,mem,imm			[rmi:	66 0f 3a 22 /r ib,u]			SSE41,SB,AR2
  'PINSRQ'		=> array('1'=>1),//Insert Byte/Dword/Qword xmmreg,mem,imm			[rmi:	o64 66 0f 3a 22 /r ib,u]		SSE41,X64,SB,AR2
  'PMAXSB'      => array(),//Maximum of Packed Signed Byte Integers		xmmreg,xmmrm			[rm:	66 0f 38 3c /r]				SSE41
  'PMAXSD'      => array(),//Maximum of Packed Signed Dword Integers		xmmreg,xmmrm			[rm:	66 0f 38 3d /r]				SSE41
  'PMAXUD'      => array(),//Maximum of Packed Unsigned Dword Integers		xmmreg,xmmrm			[rm:	66 0f 38 3f /r]				SSE41
  'PMAXUW'      => array(),//Maximum of Packed Word Integers		xmmreg,xmmrm			[rm:	66 0f 38 3e /r]				SSE41
  'PMINSB'      => array(),//Minimum of Packed Signed Byte Integers		xmmreg,xmmrm			[rm:	66 0f 38 38 /r]				SSE41
  'PMINSD'      => array(),//Minimum of Packed Dword Integers		xmmreg,xmmrm			[rm:	66 0f 38 39 /r]				SSE41
  'PMINUD'      => array(),//Minimum of Packed Dword Integers		xmmreg,xmmrm			[rm:	66 0f 38 3b /r]				SSE41
  'PMINUW'      => array(),//Minimum of Packed Word Integers		xmmreg,xmmrm			[rm:	66 0f 38 3a /r]				SSE41
  'PMOVSXBW'    => array(),//Packed Move with Sign Extend	xmmreg,xmmrm			[rm:	66 0f 38 20 /r]				SSE41,SQ
  'PMOVSXBD'    => array(),//Packed Move with Sign Extend	xmmreg,xmmrm			[rm:	66 0f 38 21 /r]				SSE41,SD
  'PMOVSXBQ'    => array(),//Packed Move with Sign Extend	xmmreg,xmmrm			[rm:	66 0f 38 22 /r]				SSE41,SW
  'PMOVSXWD'    => array(),//Packed Move with Sign Extend	xmmreg,xmmrm			[rm:	66 0f 38 23 /r]				SSE41,SQ
  'PMOVSXWQ'    => array(),//Packed Move with Sign Extend	xmmreg,xmmrm			[rm:	66 0f 38 24 /r]				SSE41,SD
  'PMOVSXDQ'    => array(),//Packed Move with Sign Extend	xmmreg,xmmrm			[rm:	66 0f 38 25 /r]				SSE41,SQ
  'PMOVZXBW'    => array(),//Packed Move with Zero Extend	xmmreg,xmmrm			[rm:	66 0f 38 30 /r]				SSE41,SQ
  'PMOVZXBD'    => array(),//Packed Move with Zero Extend	xmmreg,xmmrm			[rm:	66 0f 38 31 /r]				SSE41,SD
  'PMOVZXBQ'    => array(),//Packed Move with Zero Extend	xmmreg,xmmrm			[rm:	66 0f 38 32 /r]				SSE41,SW
  'PMOVZXWD'    => array(),//Packed Move with Zero Extend	xmmreg,xmmrm			[rm:	66 0f 38 33 /r]				SSE41,SQ
  'PMOVZXWQ'    => array(),//Packed Move with Zero Extend	xmmreg,xmmrm			[rm:	66 0f 38 34 /r]				SSE41,SD
  'PMOVZXDQ'    => array(),//Packed Move with Zero Extend	xmmreg,xmmrm			[rm:	66 0f 38 35 /r]				SSE41,SQ
  'PMULDQ'      => array(),//Multiply Packed Signed Dword Integers		xmmreg,xmmrm			[rm:	66 0f 38 28 /r]				SSE41
  'PMULLD'      => array(),//Multiply Packed Signed Dword Integers and Store Low  Result	xmmreg,xmmrm			[rm:	66 0f 38 40 /r]				SSE41
  'PTEST'       => array(              'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),//Logical Compare		xmmreg,xmmrm			[rm:	66 0f 38 17 /r]				SSE41
  'ROUNDPD'     => array(),//Round Packed Double Precision Floating-Point Values		xmmreg,xmmrm,imm		[rmi:	66 0f 3a 09 /r ib,u]			SSE41
  'ROUNDPS'     => array(),//Round Packed Single Precision Floating-Point Values		xmmreg,xmmrm,imm		[rmi:	66 0f 3a 08 /r ib,u]			SSE41
  'ROUNDSD'     => array(),//Round Scalar Double Precision Floating-Point Values		xmmreg,xmmrm,imm		[rmi:	66 0f 3a 0b /r ib,u]			SSE41
  'ROUNDSS'     => array(),//Round Scalar Single Precision Floating-Point Values		xmmreg,xmmrm,imm		[rmi:	66 0f 3a 0a /r ib,u]			SSE41



//;# Nehalem New Instructions (SSE4.2)
  'CRC32'       => array('0'=>3,'1'=>1),
  'PCMPESTRI'   => array('OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'EAX'=>1,'EDX'=>1,'ECX'=>2),//	xmmreg,xmmrm,imm		[rmi:	66 0f 3a 61 /r ib,u]			SSE42
  'PCMPESTRM'   => array('OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'EAX'=>1,'EDX'=>1),//Packed Compare Explicit Length Strings, Return Mask	xmmreg,xmmrm,imm		[rmi:	66 0f 3a 60 /r ib,u]			SSE42
  'PCMPISTRI'   => array('OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'ECX'=>2),//	xmmreg,xmmrm,imm		[rmi:	66 0f 3a 63 /r ib,u]			SSE42
  'PCMPISTRM'   => array('OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),	//Packed Compare Implicit Length Strings, Return Maskxmmreg,xmmrm,imm		[rmi:	66 0f 3a 62 /r ib,u]			SSE42
  'PCMPGTQ'     => array(),//Compare Packed Data for Greater Than		xmmreg,xmmrm			[rm:	66 0f 38 37 /r]				SSE42
  'POPCNT'      => array('0'=>2,'1'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),//		reg64,rm64			[rm:	o64 f3i 0f b8 /r]			NEHALEM,SQ,X64


//;# Intel Carry-Less Multiplication instructions (CLMUL)
/*
PCLMULLQLQDQ	xmmreg,xmmrm128			[rm:	66 0f 3a 44 /r 00]			SSE,WESTMERE
PCLMULHQLQDQ	xmmreg,xmmrm128			[rm:	66 0f 3a 44 /r 01]			SSE,WESTMERE
PCLMULLQHQDQ	xmmreg,xmmrm128			[rm:	66 0f 3a 44 /r 10]			SSE,WESTMERE
PCLMULHQHQDQ	xmmreg,xmmrm128			[rm:	66 0f 3a 44 /r 11]			SSE,WESTMERE
*/
  'PCLMULQDQ'   => array(),//	xmmreg,xmmrm128,imm8		[rmi:	66 0f 3a 44 /r ib]			SSE,WESTMERE




//;# Intel AVX instructions

//; Specific aliases first, then the generic version, to keep the disassembler happy...
  'VEXTRACTF128'=> array(), //Extract Packed Floating-Point Values	xmmrm128,ymmreg,imm8		[mri:	vex.256.66.0f3a.w0 19 /r ib]		AVX,SANDYBRIDGE

  'VINSERTF128' => array(),//Insert Packed Floating-Point Values	ymmreg,ymmreg,xmmrm128,imm8	[rvmi:	vex.nds.256.66.0f3a.w0 18 /r ib]	AVX,SANDYBRIDGE

  'VMASKMOVDQU' => array(),//Conditional SIMD Packed Loads and Stores	xmmreg,xmmreg			[rm:	vex.128.66.0f f7 /r]			AVX,SANDYBRIDGE
  'VMASKMOVPS'  => array(),//Conditional SIMD Packed Loads and Stores	mem256,ymmreg,ymmreg		[mvr:	vex.nds.256.66.0f38.w0 2e /r]		AVX,SANDYBRIDGE,SY
  'VMASKMOVPD'  => array(),//Conditional SIMD Packed Loads and Stores	xmmreg,xmmreg,mem128		[rvm:	vex.nds.128.66.0f38.w0 2d /r]		AVX,SANDYBRIDGE
 
  'VPERMILPD'   => array(),//Permute Double-Precision Floating-Point Values	xmmreg,xmmreg,xmmrm128		[rvm:	vex.nds.128.66.0f38.w0 0d /r]		AVX,SANDYBRIDGE
  'VPERMILPS'   => array(),//Permute Single-Precision Floating-Point Values	xmmreg,xmmreg,xmmrm128		[rvm:	vex.nds.128.66.0f38.w0 0c /r]		AVX,SANDYBRIDGE
  'VPERM2F128'  => array(),//Permute Floating-Point Values	ymmreg,ymmreg,ymmrm256,imm8	[rvmi:	vex.nds.256.66.0f3a.w0 06 /r ib]	AVX,SANDYBRIDGE

  'VTESTPS'     => array('SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'AH'=>2),//Packed Bit Test		ymmreg,ymmrm256			[rm:	vex.256.66.0f38.w0 0e /r]		AVX,SANDYBRIDGE
  'VTESTPD'     => array('SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'AH'=>2),//Packed Bit Test		xmmreg,xmmrm128			[rm:	vex.128.66.0f38.w0 0f /r]		AVX,SANDYBRIDGE

  'VZEROALL'    => array(),//Zero All YMM Registers	void				[	vex.256.0f.w0 77]			AVX,SANDYBRIDGE
  'VZEROUPPER'  => array(),//Zero Upper Bits of YMM Registers	void				[	vex.128.0f.w0 77]			AVX,SANDYBRIDGE

//;# Intel new instructions in ???
//; Is NEHALEM right here?
  'MOVBE'       => array('0'=>2,'1'=>1),//Move Data After Swapping Bytes		reg16,mem16			[rm:	o16 0f 38 f0 /r]			NEHALEM,SM

//;# Intel post-32 nm processor instructions
//;
//; Per AVX spec revision 7, document 319433-007
  'RDFSBASE'  => array('0'=>2),//	reg32				[m:	f3 0f ae /0]				LONG,FUTURE
  'RDGSBASE'  => array('0'=>2),//	reg32				[m:	f3 0f ae /1]				LONG,FUTURE
  'RDRAND'    => array('0'=>2,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'DF'=>2,'TF'=>2,'IF'=>2),//Read Random Number

  'WRFSBASE'  => array(),//Write FS/GS Segment Base	reg32				[m:	f3 0f ae /2]				LONG,FUTURE
  'WRGSBASE'  => array(),//Write FS/GS Segment Base	reg64				[m:	o64 f3 0f ae /3]			LONG,FUTURE

  'VCVTPH2PS' => array(),//Convert 16-bit FP Values to Single-Precision FP Values 	xmmreg,xmmrm64			[rm:	vex.128.66.0f38.w0 13 /r]		AVX,FUTURE
  'VCVTPS2PH' => array(),//Convert Single-Precision FP value to 16-bit FP value   	xmmrm128,ymmreg,imm8		[mri:	vex.256.66.0f3a.w0 1d /r ib]		AVX,FUTURE

//;# Intel AVX2 instructions
//;
//; based on pub number 319433-011 dated July 2011
//;
  'VBROADCASTSS'   => array(),//Load with Broadcast	xmmreg,xmmreg			[rm:	vex.128.66.0f38.w0 18 /r]		FUTURE,AVX2
  'VBROADCASTSS'   => array(),//Load with Broadcast	ymmreg,xmmreg			[rm:	vex.256.66.0f38.w0 18 /r]		FUTURE,AVX2
  'VBROADCASTSD'   => array(),//Load with Broadcast	ymmreg,xmmreg			[rm:	vex.256.66.0f38.w0 19 /r]		FUTURE,AVX2
  'VBROADCASTI128' => array(),//Load with Broadcast	ymmreg,mem128			[rm:	vex.256.66.0f38.w0 5a /r]		FUTURE,AVX2

);



?>