<?php














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

$mem_effect_len_array['max'] = 5; 



$can_not_deal_operation = array(
    
	
	
	
	
	
	
	
);



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



$Jcc = array(
    'JA','JAE','JB','JBE','JC','JE','JG','JGE','JL','JLE','JNA','JNAE','JNB','JNBE','JNC','JNE','JNG','JNGE','JNL','JNLE','JNO','JNP','JNS','JNZ','JO','JP','JPE','JPO','JS','JZ'
);





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


$eip_instruction = array('JA'        => 1, 
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

						 'JMP'       => 1,
						 'CALL'      => 1,	 
						 );


?>