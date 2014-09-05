<?php

if(!defined('UNEST.ORG')) {
	exit('Access Denied');
}



$segment_flag = array('CS' => 'CS', 'DS' => 'DS', 'SS' => 'SS', 'ES' => 'ES', 'FS' => 'FS', 'GS' => 'GS');


$all_eflags   = array('OF'     ,'SF'     ,'ZF'     ,'AF'     ,'CF'     ,'PF'     ,'DF'     ,'TF'     ,'IF'     ,'NT'     ,'RF'     );
$all_eflags_0 = array('OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,'DF' => 2,'TF' => 3,'IF' => 3,'NT' => 3,'RF' => 3); 
                      
                      
					  
					  
					  


$cond_jmp = array(
  'JA'        => 1, 
  'JAE'       => 1,         
  'JB'        => 1,         
  'JBE'       => 1, 
  'JC'        => 1,         
  'JCXZ'      => 2,         
  'JECXZ'     => 2,        
  'JRCXZ'     => 2,        
  'JE'        => 1,         
  'JG'        => 1,
  'JGE'       => 1, 
  'JL'        => 1, 
  'JLE'       => 1,
  'JNA'       => 1, 
  'JNAE'      => 1,
  'JNB'       => 1,
  'JNBE'      => 1,
  'JNC'       => 1,
  'JNE'       => 1,
  'JNG'       => 1,
  'JNGE'      => 1,
  'JNL'       => 1,
  'JNLE'      => 1,
  'JNO'       => 1,
  'JNP'       => 1,
  'JNS'       => 1,
  'JNZ'       => 1,
  'JO'        => 1,
  'JP'        => 1,
  'JPE'       => 1,
  'JPO'       => 1,
  'JS'        => 1,
  'JZ'        => 1,
  'JMPE'      => 1,

  'LOOP'      => 2,        
  'LOOPE'     => 2,
  'LOOPZ'     => 2,
  'LOOPNE'    => 2,
  'LOOPNZ'    => 2,
  

);


$abs_jmp = array(
    'JMP'       => 1,
);




$register['8']  = array('AL' => 8,   'CL' => 8,  'DL' => 8,  'BL' => 8,);

$register['9']  = array('AH' => 9,   'CH' => 9,  'DH' => 9,  'BH' => 9,);

$register['16'] = array('AX' => 16,  'CX' => 16, 'DX' => 16, 'BX' => 16, 'SI' => 16, 'DI' => 16,  'BP' => 16,  'SP' => 16,  'IP' => 16);

$register['32'] = array('EAX' => 32,'ECX' => 32,'EDX' => 32,'EBX' => 32,'ESI' => 32,'EDI' => 32, 'EBP' => 32, 'ESP' => 32, 'EIP' => 32);

$register['64'] = array('RAX' => 64,'RCX' => 64,'RDX' => 64,'RBX' => 64,'RSI' => 64,'RDI' => 64, 'RBP' => 64, 'RSP' => 64, 'RIP' => 32);

$total_register = $register['8'] + $register['9'] + $register['16'] + $register['32'] + $register['64'];

$registersss['8']  = array ('EAX' => 'AL', 'ECX' => 'CL',  'EDX'=> 'DL',  'EBX' => 'BL');
$registersss['9']  = array ('EAX' => 'AH', 'ECX' => 'CH',  'EDX'=> 'DH',  'EBX' => 'BH');
$registersss['16'] = array ('EAX' => 'AX', 'ECX' => 'CX',  'EDX'=> 'DX',  'EBX' => 'BX', 'ESI' => 'SI',  'EDI'=> 'DI', 'EBP' => 'BP',  'ESP' => 'SP');
$registersss['32'] = array ('EAX' => 'EAX','ECX' => 'ECX', 'EDX'=> 'EDX', 'EBX' => 'EBX','ESI' => 'ESI', 'EDI'=> 'EDI','EBP' => 'EBP', 'ESP' => 'ESP');
$registersss['64'] = array ('EAX' => 'RAX','ECX' => 'RCX', 'EDX'=> 'RDX', 'EBX' => 'RBX','ESI' => 'RSI', 'EDI'=> 'RDI','EBP' => 'RBP', 'ESP' => 'RSP');

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




$Intel_instruction_mem_opt = array( 
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




$Intel_instruction = array(
  'DB'        => array('data' => 1),
  'DW'        => array('data' => 1),
  'DD'        => array('data' => 1),


  'AAA'       => array(              'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'AX'=>3),
  'AAB'       => array(              'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'AX'=>3),
  'AAM'       => array(              'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'AL'=>3,'AH'=>2),
  'AAS'       => array(              'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>3,'CF'=>2,'PF'=>2,'AX'=>3),
  'ADC'       => array('0'=>3,'1'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>3,'PF'=>2),
  'ADD'       => array('0'=>3,'1'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),
  'AND'       => array('0'=>3,'1'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),
  'ARPL'      => array (                             'ZF'=>2),
  'BB0_RESET' => array(), 
  'BB1_RESET' => array(), 
  'BOUND'     => array('0'=>1,),
  'BSF'       => array('0'=>2,       'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,),
  'BSR'       => array('0'=>2,       'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,),
  'BSWAP'     => array('0'=>3,),
  'BT'        => array('0'=>1,'1'=>1,'OF'=>2,'SF'=>2,        'AF'=>2,'CF'=>2,'PF'=>2,),
  'BTC'       => array('0'=>3,       'OF'=>2,'SF'=>2,        'AF'=>2,'CF'=>2,'PF'=>2,),
  'BTR'       => array('0'=>3,       'OF'=>2,'SF'=>2,        'AF'=>2,'CF'=>2,'PF'=>2,),
  'BTS'       => array('0'=>3,       'OF'=>2,'SF'=>2,        'AF'=>2,'CF'=>2,'PF'=>2,),
  'CALL'      => array('STACK'=>3,'0'=>1,'ESP'=>3,'IP'=>2),
  'CBW'       => array(                                                              'AL'=>3,'AX'=>2),
  'CDQ'       => array(                                                                              'EAX'=>1,'EDX'=>2),
  'CDQE'      => array(                                                                              'EAX'=>3,'RAX'=>2),
  'CLC'       => array(                                              'CF'=>2), 
  'CLD'       => array('DF'=>2),
  'CLGI'      => array(), 
  'CLI'       => array('IF'=>2),
  'CLTS'      => array(),
  'CMC'       => array(                                              'CF'=>2),

  'CMOVA'     => array('0'=>2,'1'=>1,                'ZF'=>1,        'CF'=>1),         
  'CMOVAE'    => array('0'=>2,'1'=>1,                                'CF'=>1),         
  'CMOVB'     => array('0'=>2,'1'=>1,                                'CF'=>1),         
  'CMOVBE'    => array('0'=>2,'1'=>1,                'ZF'=>1,        'CF'=>1),         
  'CMOVC'     => array('0'=>2,'1'=>1,                                'CF'=>1),         
  'CMOVE'     => array('0'=>2,'1'=>1,                'ZF'=>1),                         
  'CMOVG'     => array('0'=>2,'1'=>1,'OF'=>1,'SF'=>1,'ZF'=>1),                         
  'CMOVGE'    => array('0'=>2,'1'=>1,'OF'=>1,'SF'=>1),                                 
  'CMOVL'     => array('0'=>2,'1'=>1,'OF'=>1,'SF'=>1),                                 
  'CMOVLE'    => array('0'=>2,'1'=>1,'OF'=>1,'SF'=>1,'ZF'=>1),                         
  'CMOVNA'    => array('0'=>2,'1'=>1,                'ZF'=>1,        'CF'=>1),         
  'CMOVNAE'   => array('0'=>2,'1'=>1,                                'CF'=>1),         
  'CMOVNB'    => array('0'=>2,'1'=>1,                                'CF'=>1),         
  'CMOVNBE'   => array('0'=>2,'1'=>1,                'ZF'=>1,        'CF'=>1),         
  'CMOVNC'    => array('0'=>2,'1'=>1,                                'CF'=>1),         
  'CMOVNE'    => array('0'=>2,'1'=>1,                'ZF'=>1),                         
  'CMOVNG'    => array('0'=>2,'1'=>1,'OF'=>1,'SF'=>1,'ZF'=>1),                         
  'CMOVNGE'   => array('0'=>2,'1'=>1,'OF'=>1,'SF'=>1),                                 
  'CMOVNL'    => array('0'=>2,'1'=>1,'OF'=>1,'SF'=>1),                                 
  'CMOVNLE'   => array('0'=>2,'1'=>1,'OF'=>1,'SF'=>1,'ZF'=>1),                         
  'CMOVNO'    => array('0'=>2,'1'=>1,'OF'=>1),                                         
  'CMOVNP'    => array('0'=>2,'1'=>1,                                        'PF'=>1), 
  'CMOVNS'    => array('0'=>2,'1'=>1,        'SF'=>1),                                 
  'CMOVNZ'    => array('0'=>2,'1'=>1,                'ZF'=>1),                         
  'CMOVO'     => array('0'=>2,'1'=>1,'OF'=>1),                                         
  'CMOVP'     => array('0'=>2,'1'=>1,                                        'PF'=>1), 
  'CMOVPE'    => array('0'=>2,'1'=>1,                                        'PF'=>1), 
  'CMOVPO'    => array('0'=>2,'1'=>1,                                        'PF'=>1), 
  'CMOVS'     => array('0'=>2,'1'=>1,        'SF'=>1),                                 
  'CMOVZ'     => array('0'=>2,'1'=>1,                'ZF'=>1),                         

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
  'DMINT'     => array(),

  'EMMS'      => array(), 
  'ENTER'     => array('STACK'=>3,'BP'=>3,'SP'=>3), 
  'EQU'       => array(),
  
  'F2XM1'     => array(), 
  'FABS'      => array(), 
  'FADD'      => array('0'=>1), 
  'FADDP'	  => array('0'=>1), 
  'FBLD'      => array(), 
  'FBSTP'     => array(), 
  'FCHS'      => array(), 
  'FCLEX'     => array(), 
  'FCMOVB'    => array(                                              'CF'=>1),        
  'FCMOVBE'   => array(                              'ZF'=>1,        'CF'=>1),        
  'FCMOVE'    => array(                              'ZF'=>1),                        
  'FCMOVNB'   => array(                                              'CF'=>1),        
  'FCMOVNBE'  => array(                              'ZF'=>1,        'CF'=>1),        
  'FCMOVNE'   => array(                              'ZF'=>1),                        
  'FCMOVNU'   => array(		                                                 'PF'=>1),
  'FCMOVU'    => array(                                                      'PF'=>1),
  'FCOM'      => array(),	
  'FCOMI'	  => array(              'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),
  'FCOMIP'	  => array(              'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),   
  'FCOMP'	  => array(),	
  'FCOMPP'	  => array(),	
  'FCOS'      => array(),   
  'FDECSTP'   => array(),   
  'FDISI'     => array(),   
  'FDIV'      => array(),   
  'FDIVP'     => array(),   
  'FDIVR'     => array(),   
  'FDIVRP'    => array(),   


  'FEMMS'     => array(),   
  'FENI'      => array(),   
  'FFREE'     => array(),   
  'FFREEP'    => array(), 
  'FIADD'	  => array(), 

  'FICOM'     => array(), 
  'FICOMP'    => array(), 
  'FIDIV'     => array(),   
  'FIDIVR'    => array(),   
  'FILD'      => array(), 
  'FIMUL'     => array(), 
  'FINCSTP'   => array(), 
  'FINIT'     => array(), 
  'FIST'      => array(), 
  'FISTP'     => array(), 
  'FISTTP'    => array(), 
  'FISUB'     => array(), 
  'FISUBR'    => array(), 
  'FLD'       => array(), 
  'FLD1'      => array(),  
  'FLDCW'     => array(),  
  'FLDENV'    => array(),  
  'FLDL2E'    => array(),  
  'FLDL2T'    => array(),  
  'FLDLG2'    => array(),  
  'FLDLN2'    => array(),  
  'FLDPI'     => array(),  
  'FLDZ'      => array(),  

  'FMUL'      => array(),  
  'FMULP'     => array(),  
  'FNCLEX'	  => array(),  
  'FNDISI'    => array(),
  'FNENI'     => array(),
  'FNINIT'    => array(), 
  'FNOP'      => array(), 
  'FNSAVE'    => array(), 
  'FNSTCW'    => array(), 
  'FNSTENV'   => array(), 
  'FNSTSW'    => array(), 
  'FPATAN'    => array(),
  'FPREM'     => array(),
  'FPREM1'    => array(),
  'FPTAN'     => array(),
  'FRNDINT'   => array(),
  'FRSTOR'    => array(),
  'FSAVE'     => array(),
  'FSCALE'    => array(),
  'FSETPM'    => array(),
  'FSIN'      => array(),
  'FSINCOS'   => array(),
  'FSQRT'     => array(),
  'FST'       => array(),
  'FSTCW'     => array(),
  'FSTENV'    => array(),
  'FSTP'      => array(),
  'FSTSW'     => array(),
  'FSUB'      => array(), 
  'FSUBP'     => array(), 
  'FSUBR'     => array(), 
  'FSUBRP'    => array(), 
  'FTST'      => array(), 
  'FUCOM'     => array(), 
  'FUCOMI'    => array(                 'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2), 
  'FUCOMIP'   => array(                 'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2), 
  'FUCOMP'    => array(), 
  'FUCOMPP'   => array(), 
  'FWAIT'     => array(), 
  'FXAM'      => array(), 
  'FXCH'      => array(), 
  'FXTRACT'   => array(), 
  'FYL2X'     => array(), 
  'FYL2XP1'   => array(), 
  'HLT'       => array(), 
  'IBTS'      => array(), 
  'ICEBP'     => array(), 
  'IDIV'      => array('0'=>1,        'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'AX'=>3,'DX'=>3,), 

  'IMUL'      => array('multi_op' => 3,
                       
                       '1' => array('0'=>1,              'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'AX'=>3,'DX'=>3), 
					   
					   '2' => array('0'=>3,'1'=>1,       'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,),
					   
					   '3' => array('0'=>2,'1'=>1,'2'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,),
				 ),
  'IN'        => array('0'=>2,'1'=>1), 
  'INC'       => array('0'=>3,       'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,        'PF'=>2), 
  'INSB'      => array('DF'=>1,'DX'=>1,'DI'=>3), 
  'INSD'      => array('DF'=>1,'DX'=>1,'DI'=>3), 
  'INSW'      => array('DF'=>1,'DX'=>1,'DI'=>3), 
  
  'INT'       => array('OF' => 3,'SF' => 3,'ZF' => 3,'AF' => 3,'CF' => 3,'PF' => 3,'DF' => 3,'TF' => 3,'IF' => 3,'NT' => 2),  
  'INT01'     => array('OF' => 3,'SF' => 3,'ZF' => 3,'AF' => 3,'CF' => 3,'PF' => 3,'DF' => 3,'TF' => 3,'IF' => 3,'NT' => 2),
  'INT1'      => array('OF' => 3,'SF' => 3,'ZF' => 3,'AF' => 3,'CF' => 3,'PF' => 3,'DF' => 3,'TF' => 3,'IF' => 3,'NT' => 2),	
  'INT03'     => array('OF' => 3,'SF' => 3,'ZF' => 3,'AF' => 3,'CF' => 3,'PF' => 3,'DF' => 3,'TF' => 3,'IF' => 3,'NT' => 2),	
  'INT3'      => array('OF' => 3,'SF' => 3,'ZF' => 3,'AF' => 3,'CF' => 3,'PF' => 3,'DF' => 3,'TF' => 3,'IF' => 3,'NT' => 2),	
  'INTO'      => array('OF' => 3,'SF' => 3,'ZF' => 3,'AF' => 3,'CF' => 3,'PF' => 3,'DF' => 3,'TF' => 3,'IF' => 3,'NT' => 2),	
  'INVD'      => array(), 
  'INVPCID'   => array('0'=>3),
  'INVLPG'    => array(),
  'INVLPGA'	  => array('EAX'=>3,'ECX'=>3),
  'IRET'      => array('STACK'=>3,'ESP'=>3,'IP'=>2,'OF' => 2,'SF' => 2,'ZF' => 2,'AF' => 2,'CF' => 2,'PF' => 2,'DF' => 2,'TF' => 2,'IF' => 2,'NT' => 1),
  'IRETD'     => array('STACK'=>3,'ESP'=>3,'IP'=>2,'OF' => 2,'SF' => 2,'ZF' => 2,'AF' => 2,'CF' => 2,'PF' => 2,'DF' => 2,'TF' => 2,'IF' => 2),
  'IRETQ'     => array('STACK'=>3,'ESP'=>3,'IP'=>2,'OF' => 2,'SF' => 2,'ZF' => 2,'AF' => 2,'CF' => 2,'PF' => 2,'DF' => 2,'TF' => 2,'IF' => 2),
  'IRETW'     => array('STACK'=>3,'ESP'=>3,'IP'=>2,'OF' => 2,'SF' => 2,'ZF' => 2,'AF' => 2,'CF' => 2,'PF' => 2,'DF' => 2,'TF' => 2,'IF' => 2),

  'JA'        => array('0'=>1,'CF'=>1,'ZF'=>1,'IP'=>2), 
  'JAE'       => array('0'=>1,'CF'=>1,'IP'=>2),         
  'JB'        => array('0'=>1,'CF'=>1,'IP'=>2),         
  'JBE'       => array('0'=>1,'CF'=>1,'ZF'=>1,'IP'=>2), 
  'JC'        => array('0'=>1,'CF'=>1,'IP'=>2),         
  'JCXZ'      => array('0'=>1,'CX'=>1,'IP'=>2),         
  'JECXZ'     => array('0'=>1,'ECX'=>1,'IP'=>2),        
  'JE'        => array('0'=>1,'ZF'=>1,'IP'=>2),         
  'JG'        => array('0'=>1,'OF'=>1,'SF'=>1,'ZF'=>1,'IP'=>2),
  'JGE'       => array('0'=>1,'OF'=>1,'SF'=>1,'IP'=>2), 
  'JL'        => array('0'=>1,'OF'=>1,'SF'=>1,'IP'=>2), 
  'JLE'       => array('0'=>1,'OF'=>1,'SF'=>1,'ZF'=>1,'IP'=>2),
  'JNA'       => array('0'=>1,'CF'=>1,'ZF'=>1,'IP'=>2), 
  'JNAE'      => array('0'=>1,'CF'=>1,'IP'=>2),
  'JNB'       => array('0'=>1,'CF'=>1,'IP'=>2),
  'JNBE'      => array('0'=>1,'CF'=>1,'ZF'=>1,'IP'=>2),
  'JNC'       => array('0'=>1,'CF'=>1,'IP'=>2),
  'JNE'       => array('0'=>1,'ZF'=>1,'IP'=>2),
  'JNG'       => array('0'=>1,'OF'=>1,'SF'=>1,'ZF'=>1,'IP'=>2),
  'JNGE'      => array('0'=>1,'OF'=>1,'SF'=>1,'IP'=>2),
  'JNL'       => array('0'=>1,'OF'=>1,'SF'=>1,'IP'=>2),
  'JNLE'      => array('0'=>1,'OF'=>1,'SF'=>1,'ZF'=>1,'IP'=>2),
  'JNO'       => array('0'=>1,'OF'=>1,'IP'=>2),
  'JNP'       => array('0'=>1,'PF'=>1,'IP'=>2),
  'JNS'       => array('0'=>1,'SF'=>1,'IP'=>2),
  'JNZ'       => array('0'=>1,'ZF'=>1,'IP'=>2),
  'JO'        => array('0'=>1,'OF'=>1,'IP'=>2),
  'JP'        => array('0'=>1,'PF'=>1,'IP'=>2),
  'JPE'       => array('0'=>1,'PF'=>1,'IP'=>2),
  'JPO'       => array('0'=>1,'PF'=>1,'IP'=>2),
  'JS'        => array('0'=>1,'SF'=>1,'IP'=>2),
  'JZ'        => array('0'=>1,'ZF'=>1,'IP'=>2),
  'JRCXZ'     => array('0'=>1,'RCX'=>1,'IP'=>2),        
  'JMP'       => array('0'=>1,'IP'=>2),
  'JMPE'      => array('0'=>1,'IP'=>2),

  'LAHF'      => array(        'SF'=>1,'ZF'=>1,'AF'=>1,'CF'=>1,'PF'=>1,'AH'=>2),
  'LAR'       => array('0'=>2,'1'=>1,  'ZF'=>2),
  'LDS'		  => array('0'=>2,'1'=>1),
  'LEA'       => array('0'=>2,'1'=> -1),
  'LEAVE'     => array('STACK'=>3,'BP'=>3,'SP'=>2),
  'LES'       => array('0'=>2),
  'LFENCE'    => array(),
  'LFS'       => array('0'=>2),
  'LGDT'      => array(),      
  'LGS'       => array('0'=>2),
  'LIDT'      => array(),      
  'LLDT'      => array('0'=>1),
  'LMSW'      => array('0'=>1),
  'LOCK'      => array('isPrefix'=>1),


  'LODSB'     => array('DF'=>1,'ESI'=>3,'AL'=>2),
  'LODSD'     => array('DF'=>1,'ESI'=>3,'EAX'=>2),
  'LODSQ'     => array('DF'=>1,'RSI'=>3,'RAX'=>2),
  'LODSW'     => array('DF'=>1,'ESI'=>3,'AX'=>2),

  'LOOP'      => array('0'=>1,'CX'=>3,'IP'=>2),        
  'LOOPE'     => array('0'=>1,'CX'=>3,'ZF'=>1,'IP'=>2),
  'LOOPZ'     => array('0'=>1,'CX'=>3,'ZF'=>1,'IP'=>2),
  'LOOPNE'    => array('0'=>1,'CX'=>3,'ZF'=>1,'IP'=>2),
  'LOOPNZ'    => array('0'=>1,'CX'=>3,'ZF'=>1,'IP'=>2),
  'LSL'       => array('0'=>2,'1'=>1,'ZF'=>2,),
  'LSS'       => array('0'=>2,'1'=>1),
  'LTR'       => array('0'=>1),
  'MFENCE'    => array(),
  'MONITOR'   => array('EAX'=>1,'ECX'=>3,'EDX'=>3),
  'MOV'       => array('0'=>2,'1'=>1),  
  'MOVD'      => array('0'=>2,'1'=>1),  
  'MOVSB'     => array('DF'=>1,'SI'=>3,'DI'=>3),
  'MOVSD'	  => array('DF'=>1,'SI'=>3,'DI'=>3),
  'MOVSQ'     => array('DF'=>1,'SI'=>3,'DI'=>3),
  'MOVSW'     => array('DF'=>1,'SI'=>3,'DI'=>3),
  'MOVSX'     => array('0'=>2,'1'=>1),
  'MOVSXD'    => array('0'=>2,'1'=>1),
  'MOVZX'     => array('0'=>2,'1'=>1),
  'MUL'       => array('0'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'AX'=>3,'DX'=>3), 
  'MWAIT'     => array('EAX'=>3,'ECX'=>3),
  'NEG'       => array('0'=>3,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2), 
  'NOP'       => array(), 
  'NOT'       => array('0'=>3), 
  'OR'        => array('0'=>3,'1'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2), 
  'OUT'       => array('0'=>1,'1'=>1),
  'OUTSB'     => array('DF'=>1,'DX'=>1,'SI'=>3),
  'OUTSD'	  => array('DF'=>1,'DX'=>1,'SI'=>3),
  'OUTSW'	  => array('DF'=>1,'DX'=>1,'SI'=>3),
  'PACKSSDW'  => array(),
  'PACKSSWB'  => array(),
  'PACKUSWB'  => array(),
  'PADDB'     => array(),
  'PADDD'     => array(),
  'PADDSB'    => array(),
  'PADDSIW'   => array(),
  'PADDSW'    => array(),
  'PADDUSB'   => array(),
  'PADDUSW'   => array(),
  'PADDW'     => array(),
  'PAND'      => array(),
  'PANDN'     => array(),
  'PAUSE'     => array(),


  'POP'       => array('STACK'=>3,'0'=>2,'ESP'=>3),
  'POPA'      => array('STACK'=>3,'DI'=>2, 'SI'=>2, 'BP'=>2, 'BX'=>2, 'DX'=>2, 'CX'=>2, 'AX'=>2,'ESP'=>3),
  'POPAD'     => array('STACK'=>3,'EDI'=>2,'ESI'=>2,'EBP'=>2,'EBX'=>2,'EDX'=>2,'ECX'=>2,'EAX'=>2,'ESP'=>3),
  'POPAW'	  => array('STACK'=>3,'DI'=>2, 'SI'=>2, 'BP'=>2, 'BX'=>2, 'DX'=>2, 'CX'=>2, 'AX'=>2,'ESP'=>3),
  'POPF'      => array('STACK'=>3,'ESP'=>3,'OF' => 2,'SF' => 2,'ZF' => 2,'AF' => 2,'CF' => 2,'PF' => 2,'DF' => 2,'TF' => 2,'IF' => 2,'NT' => 2),
  'POPFD'	  => array('STACK'=>3,'ESP'=>3,'OF' => 2,'SF' => 2,'ZF' => 2,'AF' => 2,'CF' => 2,'PF' => 2,'DF' => 2,'TF' => 2,'IF' => 2),
  'POPFQ'	  => array('STACK'=>3,'ESP'=>3,'OF' => 2,'SF' => 2,'ZF' => 2,'AF' => 2,'CF' => 2,'PF' => 2,'DF' => 2,'TF' => 2,'IF' => 2),
  'POPFW'	  => array('STACK'=>3,'ESP'=>3,'OF' => 2,'SF' => 2,'ZF' => 2,'AF' => 2,'CF' => 2,'PF' => 2,'DF' => 2,'TF' => 2,'IF' => 2),
  'POR'       => array(),
  'PREFETCH'  => array(),
  'PREFETCHW' => array(),


  'PUSH'      => array('STACK'=>3,'0'=>1,'ESP'=>3),
  'PUSHA'     => array('STACK'=>3,'AX'=>1, 'CX'=>1, 'DX'=>1, 'BX'=>1, 'SP'=>1, 'BP'=>1, 'SI'=>1, 'DI'=>1, 'ESP'=>3),
  'PUSHAD'	  => array('STACK'=>3,'EDI'=>1,'ESI'=>1,'EBP'=>1,'EBX'=>1,'EDX'=>1,'ECX'=>1,'EAX'=>1,'ESP'=>3),
  'PUSHAW'    => array('STACK'=>3,'EDI'=>1,'ESI'=>1,'EBP'=>1,'EBX'=>1,'EDX'=>1,'ECX'=>1,'EAX'=>1,'ESP'=>3),
  'PUSHF'     => array('STACK'=>3,'ESP'=>3,'OF'=>1,'SF'=>1,'ZF'=>1,'AF'=>1,'CF'=>1,'PF'=>1,'DF'=>1,'TF'=>1,'IF'=>1),
  'PUSHFD'    => array('STACK'=>3,'ESP'=>3,'OF'=>1,'SF'=>1,'ZF'=>1,'AF'=>1,'CF'=>1,'PF'=>1,'DF'=>1,'TF'=>1,'IF'=>1),
  'PUSHFQ'    => array('STACK'=>3,'ESP'=>3,'OF'=>1,'SF'=>1,'ZF'=>1,'AF'=>1,'CF'=>1,'PF'=>1,'DF'=>1,'TF'=>1,'IF'=>1),
  'PUSHFW'    => array('STACK'=>3,'ESP'=>3,'OF'=>1,'SF'=>1,'ZF'=>1,'AF'=>1,'CF'=>1,'PF'=>1,'DF'=>1,'TF'=>1,'IF'=>1),
  'PXOR'      => array(),
  'RCL'       => array('0'=>3,'1'=>1,'CF'=>3,'OF'=>2),
  'RCR'       => array('0'=>3,'1'=>1,'CF'=>3,'OF'=>2),
  'RDSHR'     => array('0'=>2),
  'RDMSR'     => array('ECX'=>1,'EDX'=>2,'EAX'=>2),
  'RDPMC'     => array('ECX'=>1,'EDX'=>2,'EAX'=>2),
  'RDTSC'     => array('EDX'=>2,'EAX'=>2),
  'RDTSCP'    => array('EDX'=>2,'EAX'=>2,'ECX'=>2),
  'REP'       => array('isPrefix'=>1,'CX'=>3,'ZF'=>3),
  'REPE'      => array('isPrefix'=>1,'CX'=>3,'ZF'=>3),
  'REPZ'      => array('isPrefix'=>1,'CX'=>3,'ZF'=>3),
  'REPNE'     => array('isPrefix'=>1,'CX'=>3,'ZF'=>3),
  'REPNZ'     => array('isPrefix'=>1,'CX'=>3,'ZF'=>3),
  'RET'       => array('multi_op' => 2,
                       '0' => array('STACK'=>3,       'SP'=>3,'IP'=>2),
					   '1' => array('STACK'=>3,'0'=>1,'SP'=>3,'IP'=>2),					   
					   ),
  'RETF'      => array('STACK'=>3,'0'=>1,'SP'=>3,'IP'=>2),
  'RETN'	  => array('STACK'=>3,'0'=>1,'SP'=>3,'IP'=>2),
  'ROL'       => array('0'=>3,'1'=>1,'CF'=>3,'OF'=>2),
  'ROR'       => array('0'=>3,'1'=>1,'CF'=>3,'OF'=>2),


  'RSM'       => array('OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'DF'=>2,'TF'=>2,'IF'=>2,'NT' => 2,'RF' => 2),

  'SAHF'      => array('AH'=>1,              'SF'=>2,'ZF'=>2,'AF'=>2,'PF'=>2,'CF'=>2 ),
  'SAL'       => array('0'=>3,'1'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'PF'=>2,'CF'=>2),

  'SAR'       => array('0'=>3,'1'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'PF'=>2,'CF'=>2),
  'SBB'       => array('0'=>3,'1'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>3,'PF'=>2),
  'SCASB'     => array( 'AL'=>1, 'DI'=>3,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),
  'SCASD'     => array('EAX'=>1,'EDI'=>3,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),
  'SCASQ'     => array( 'AX'=>1,'EDI'=>3,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),
  'SCASW'     => array('RAX'=>1,'EDI'=>3,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),
  'SETA'      => array('0'=>2,'CF'=>1,'ZF'=>1), 
  'SETAE'     => array('0'=>2,'CF'=>1), 
  'SETB'      => array('0'=>2,'CF'=>1), 
  'SETBE'     => array('0'=>2,'CF'=>1,'ZF'=>1), 
  'SETC'      => array('0'=>2,'CF'=>1), 
  'SETE'      => array('0'=>2,'ZF'=>1), 
  'SETG'      => array('0'=>2,'SF'=>1,'OF'=>1,'ZF'=>1), 
  'SETGE'     => array('0'=>2,'SF'=>1,'OF'=>1), 
  'SETL'      => array('0'=>2,'SF'=>1,'OF'=>1), 
  'SETLE'     => array('0'=>2,'SF'=>1,'OF'=>1,'ZF'=>1), 
  'SETNA'     => array('0'=>2,'CF'=>1,'ZF'=>1), 
  'SETNAE'    => array('0'=>2,'CF'=>1), 
  'SETNB'     => array('0'=>2,'CF'=>1), 
  'SETNBE'    => array('0'=>2,'CF'=>1,'ZF'=>1), 
  'SETNC'     => array('0'=>2,'CF'=>1), 
  'SETNE'     => array('0'=>2,'ZF'=>1), 
  'SETNG'     => array('0'=>2,'SF'=>1,'OF'=>1,'ZF'=>1), 
  'SETNGE'    => array('0'=>2,'SF'=>1,'OF'=>1), 
  'SETNL'     => array('0'=>2,'SF'=>1,'OF'=>1), 
  'SETNLE'    => array('0'=>2,'SF'=>1,'OF'=>1,'ZF'=>1), 
  'SETNO'     => array('0'=>2,'OF'=>1), 
  'SETNP'     => array('0'=>2,'PF'=>1), 
  'SETNS'     => array('0'=>2,'SF'=>1), 
  'SETNZ'     => array('0'=>2,'ZF'=>1), 
  'SETO'      => array('0'=>2,'OF'=>1), 
  'SETP'      => array('0'=>2,'PF'=>1), 
  'SETPE'     => array('0'=>2,'PF'=>1), 
  'SETPO'     => array('0'=>2,'PF'=>1), 
  'SETS'      => array('0'=>2,'SF'=>1), 
  'SETZ'      => array('0'=>2,'ZF'=>1), 
  'SFENCE'    => array(),
  'SGDT'      => array('0'=>2),
  'SHL'       => array('0'=>3,'1'=>1,       'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'PF'=>2,'CF'=>2),
  'SHLD'      => array('0'=>3,'1'=>1,'2'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'PF'=>2,'CF'=>2),
  'SHR'       => array('0'=>3,'1'=>1,       'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'PF'=>2,'CF'=>2),
  'SHRD'      => array('0'=>3,'1'=>1,'2'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'PF'=>2,'CF'=>2),
  'SIDT'      => array('0'=>2),
  'SLDT'      => array('0'=>2),


  'SMSW'      => array('0'=>2),
  'STC'       => array('CF'=>2),
  'STD'       => array('DF'=>2),

  'STI'       => array('IF'=>2),
  'STOSB'     => array( 'AL'=>1, 'DI'=>3,'DF'=>1),
  'STOSD'     => array('EAX'=>1, 'DI'=>3,'DF'=>1),
  'STOSQ'     => array('RAX'=>1, 'DI'=>3,'DF'=>1),
  'STOSW'     => array( 'AX'=>1, 'DI'=>3,'DF'=>1),
  'STR'       => array('0'=>2),
  'SUB'       => array('0'=>3,'1'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'PF'=>2,'CF'=>2),


  'SWAPGS'    => array(),
  'SYSCALL'   => array('RCX'=>2,'IP'=>2,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'DF'=>2,'TF'=>2,'IF'=>2),
  'SYSENTER'  => array('IP'=>2),
  'SYSEXIT'   => array('IP'=>2),
  'SYSRET'    => array('RCX'=>2,'IP'=>2,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'DF'=>2,'TF'=>2,'IF'=>2),
  'TEST'      => array('0'=>1,'1'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'PF'=>2,'CF'=>2),


  'UD2'       => array(),


  'VERR'      => array('0'=>1,'ZF'=>2),
  'VERW'	  => array('0'=>1,'ZF'=>2),

  'FWAIT'     => array(),
  'WBINVD'    => array(),
  'WRSHR'     => array('0'=>1),
  'WRMSR'     => array('EDX'=>1,'EAX'=>1,'ECX'=>1),
  'XADD'      => array('0'=>3,'1'=>3,'CF'=>2,'PF'=>2,'AF'=>2,'SF'=>2,'ZF'=>2,'OF'=>2),


  'XCHG'      => array('0'=>3,'1'=>3),
  'XLATB'     => array('AL'=>3,'BX'=>1),
  'XLAT'      => array('AL'=>3,'BX'=>1),
  'XOR'       => array('0'=>3,'1'=>1,'CF'=>2,'PF'=>2,'AF'=>2,'SF'=>2,'ZF'=>2,'OF'=>2),



  'ADDPS' => array(),
  'ADDSS' => array(),
  'ANDPS' => array(),
  'ANDNPS'=> array(),



  'CMPPS' => array(),
  'CMPSS' => array(),
  'COMISS'=> array(),
  'CVTPI2PS' => array(),
  'CVTPS2PI' => array(),
  'CVTSI2SS' => array('0'=>1,'1'=>1),
  'CVTSS2SI' => array('0'=>2),
  'CVTTPS2PI'=> array(),             
  'CVTTSS2SI'=>	array('0'=>2),
  'DIVPS'	 => array(),
  'DIVSS'    => array(),
  'MAXPS'	 => array(),
  'MAXSS'    => array(),
  'MINPS'    => array(),
  'MINSS'    => array(),
  'MOVAPS'   => array(),
  'MOVHPS'   => array(),
  'MOVLHPS'  => array(),
  'MOVLPS'   => array(),
  'MOVHLPS'  => array(),
  'MOVMSKPS' => array('0'=>2),
  'MOVNTPS'  => array(),
  'MOVSS'    => array(),
  'MOVUPS'   => array(),
  'MULPS'    => array(),
  'MULSS'    => array(),
  'ORPS'     => array(),
  'RCPPS'    => array(),
  'RCPSS'    => array(),

  'RSQRTPS'  => array(),
  'RSQRTSS'  => array(),
  'SHUFPS'   => array(),
  'SQRTPS'   => array(),
  'SQRTSS'   => array(),
  'STMXCSR'  => array(),
  'SUBPS'    => array(),
  'SUBSS'    => array(),
  'UCOMISS'  => array('OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),
  'UNPCKHPS' => array(),
  'UNPCKLPS' => array(),
  'XORPS'    => array(),


  'FXRSTOR'  => array(),
  'FXRSTOR64'=> array(),
  'FXSAVE'   => array(),
  'FXSAVE64' => array(),




  'XGETBV'   => array('ECX'=>1,'EDX'=>2,'EAX'=>2),
  'XSETBV'   => array('ECX'=>1,'EDX'=>1,'EAX'=>1),
  'XSAVE'    => array('EDX'=>1,'EAX'=>1),

  'XSAVEOPT' => array('EDX'=>1,'EAX'=>1),

  'XRSTOR'   => array('EDX'=>1,'EAX'=>1),






  'PREFETCHNTA'    => array(),
  'PREFETCHT0'     => array(),
  'PREFETCHT1'     => array(),
  'PREFETCHT2'     => array(),




  'MASKMOVQ' => array('DI'=>1),
  'MOVNTQ'   => array(),




  'PSHUFW'   => array(),


  'MASKMOVDQU' => array('DI'=>1),


  'MOVNTDQ'    => array(),
  'MOVNTI'     => array('1'=>1),
  'MOVNTPD'    => array(),




  
  'MOVDQA'    => array(),
  'MOVDQU'    => array(),
  'MOVDQ2Q'   => array(),
  'MOVQ'      => array(),
  'MOVQ2DQ'   => array(),







  'PADDQ'     => array(),






  'PAVGB'     => array(),
  'PAVGW'	  => array(),
  'PCMPEQB'   => array(),
  'PCMPEQW'   => array(),
  'PCMPEQD'   => array(),
  'PCMPGTB'   => array(),
  'PCMPGTW'   => array(),
  'PCMPGTD'   => array(),

  'PINSRW'    => array('1'=>1),
  'PMADDWD'   => array(),
  'PMAXSW'    => array(),
  'PMAXUB'    => array(),
  'PMINSW'    => array(),
  'PMINUB'    => array(),
  'PMOVMSKB'  => array('0'=>2),
  'PMULHUW'   => array(),
  'PMULHW'    => array(),
  'PMULLW'    => array(),
  'PMULUDQ'   => array(),

  'PSADBW'    => array(),
  'PSHUFD'    => array(),
  'PSHUFHW'   => array(),
  'PSHUFLW'   => array(),
  'PSLLDQ'    => array(),
  'PSLLW'     => array(),
  'PSLLD'     => array(),
  'PSLLQ'     => array(),
  'PSRAW'     => array(),
  'PSRAD'     => array(),
  'PSRLDQ'    => array(),
  'PSRLW'     => array(),
  'PSRLD'     => array(),
  'PSRLQ'     => array(),
  'PSUBB'     => array(),
  'PSUBW'     => array(),
  'PSUBD'     => array(),
  'PSUBQ'     => array(),
  'PSUBSB'    => array(),
  'PSUBSW'    => array(),
  'PSUBUSB'   => array(),
  'PSUBUSW'   => array(),
  'PUNPCKHBW' => array(),
  'PUNPCKHWD' => array(),
  'PUNPCKHDQ' => array(),
  'PUNPCKHQDQ'=> array(),
  'PUNPCKLBW' => array(),
  'PUNPCKLWD' => array(),
  'PUNPCKLDQ' => array(),
  'PUNPCKLQDQ'=> array(),



  'ADDPD' => array(),
  'ADDSD' => array(),
  'ANDPD' => array(),
  'ANDNPD'=> array(),



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
  'MAXPD'    => array(),
  'MAXSD'    => array(),
  'MINPD'	 => array(),
  'MINSD'    => array(),
  'MOVAPD'   => array(),
  'MOVHPD'   => array(),
  'MOVLPD'   => array(),
  'MOVMSKPD' => array('0'=>2),
  
  'MOVUPD'   => array(),
  'MULPD'    => array(),
  'MULSD'    => array(),
  'ORPD'     => array(),
  'SHUFPD'   => array(),
  'SQRTPD'   => array(),
  'SQRTSD'   => array(),
  'SUBPD'    => array(),
  'SUBSD'    => array(),
  'UCOMISD'  => array('OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),
  'UNPCKHPD' => array(),
  'UNPCKLPD' => array(),
  'XORPD'    => array(),


  'ADDSUBPD' => array(),
  'ADDSUBPS' => array(),
  'HADDPD'   => array(),
  'HADDPS'   => array(),
  'HSUBPD'   => array(),
  'HSUBPS'   => array(),

  'MOVDDUP'  => array(),
  'MOVSHDUP' => array(),
  'MOVSLDUP' => array(),


  'PALIGNR'  => array(),
  'PHADDW'   => array(),
  'PHADDD'   => array(),
  'PHADDSW'  => array(),
  'PHSUBW'   => array(),
  'PHSUBD'   => array(),
  'PHSUBSW'  => array(),
  'PMADDUBSW'=> array(),
  'PMULHRSW' => array(),
  'PSHUFB'   => array(),
  'PSIGNB'   => array(),
  'PSIGNW'   => array(),
  'PSIGND'   => array(),


  'AESENC'     => array(),
  'AESENCLAST' => array(),
  'AESDEC'     => array(),
  'AESDECLAST' => array(),
  'AESIMC'     => array(),
  'AESKEYGENASSIST' => array(),



  'CMPPD' => array(),


  'PABSB' => array(),
  'PABSW' => array(),
  'PABSD' => array(),



  'BLENDPD'     => array(),
  'BLENDPS'     => array(),
  'BLENDVPD'    => array(),
  'BLENDVPS'    => array(),
  'DPPD'        => array(),
  'DPPS'		=> array(),
  'EXTRACTPS'   => array('0'=>2),
  'INSERTPS'    => array(), 
  'MOVNTDQA'    => array(), 
  'MPSADBW'     => array(), 
  'PACKUSDW'    => array(), 
  'PBLENDVB'    => array(), 
  'PBLENDW'     => array(), 
  'PCMPEQQ'     => array(), 
  'PEXTRB'      => array('0'=>2), 
  'PEXTRD'		=> array('0'=>2), 
  'PEXTRQ'		=> array('0'=>2), 
  'PEXTRW'      => array('0'=>2), 
  'PHMINPOSUW'  => array('1'=>1),
  'PINSRB'      => array('1'=>1),
  'PINSRD'		=> array('1'=>1),
  'PINSRQ'		=> array('1'=>1),
  'PMAXSB'      => array(),
  'PMAXSD'      => array(),
  'PMAXUD'      => array(),
  'PMAXUW'      => array(),
  'PMINSB'      => array(),
  'PMINSD'      => array(),
  'PMINUD'      => array(),
  'PMINUW'      => array(),
  'PMOVSXBW'    => array(),
  'PMOVSXBD'    => array(),
  'PMOVSXBQ'    => array(),
  'PMOVSXWD'    => array(),
  'PMOVSXWQ'    => array(),
  'PMOVSXDQ'    => array(),
  'PMOVZXBW'    => array(),
  'PMOVZXBD'    => array(),
  'PMOVZXBQ'    => array(),
  'PMOVZXWD'    => array(),
  'PMOVZXWQ'    => array(),
  'PMOVZXDQ'    => array(),
  'PMULDQ'      => array(),
  'PMULLD'      => array(),
  'PTEST'       => array(              'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),
  'ROUNDPD'     => array(),
  'ROUNDPS'     => array(),
  'ROUNDSD'     => array(),
  'ROUNDSS'     => array(),




  'CRC32'       => array('0'=>3,'1'=>1),
  'PCMPESTRI'   => array('OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'EAX'=>1,'EDX'=>1,'ECX'=>2),
  'PCMPESTRM'   => array('OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'EAX'=>1,'EDX'=>1),
  'PCMPISTRI'   => array('OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'ECX'=>2),
  'PCMPISTRM'   => array('OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),	
  'PCMPGTQ'     => array(),
  'POPCNT'      => array('0'=>2,'1'=>1,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2),





  'PCLMULQDQ'   => array(),







  'VEXTRACTF128'=> array(), 

  'VINSERTF128' => array(),

  'VMASKMOVDQU' => array(),
  'VMASKMOVPS'  => array(),
  'VMASKMOVPD'  => array(),
 
  'VPERMILPD'   => array(),
  'VPERMILPS'   => array(),
  'VPERM2F128'  => array(),

  'VTESTPS'     => array('SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'AH'=>2),
  'VTESTPD'     => array('SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'AH'=>2),

  'VZEROALL'    => array(),
  'VZEROUPPER'  => array(),



  'MOVBE'       => array('0'=>2,'1'=>1),




  'RDFSBASE'  => array('0'=>2),
  'RDGSBASE'  => array('0'=>2),
  'RDRAND'    => array('0'=>2,'OF'=>2,'SF'=>2,'ZF'=>2,'AF'=>2,'CF'=>2,'PF'=>2,'DF'=>2,'TF'=>2,'IF'=>2),

  'WRFSBASE'  => array(),
  'WRGSBASE'  => array(),

  'VCVTPH2PS' => array(),
  'VCVTPS2PH' => array(),





  'VBROADCASTSS'   => array(),
  'VBROADCASTSS'   => array(),
  'VBROADCASTSD'   => array(),
  'VBROADCASTI128' => array(),

);



?>