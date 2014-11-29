<?php


//$match_params[...] 搜索 匹配目标的['params']

$match_params['reg_al']  = array('AL' => true);
$match_params['reg_ax']  = array('AX' => true);
$match_params['reg_eax'] = array('EAX' => true);
$match_params['reg_rax'] = array('RAX' => true);
  
$match_params['reg_dx']  = array('DX' => true);
$match_params['reg_edx'] = array('EDX' => true);

$match_params['reg_cl']  = array('CL' => true);
$match_params['reg_ecx'] = array('ECX' => true);
$match_params['reg_cx']  = array('CX' => true);
$match_params['reg_rcx'] = array('RCX' => true);

$match_params['unity']   = array('1' => true,);  

$match_params['reg32na'] = array ('ECX' => true,'EDX' => true,'EBX' => true,'ESI' => true,'EDI' => true,'EBP' => true,'ESP' => true);


//$match_types[...] 搜索 匹配目标的[P_TYPE]
//$match_bits[...]  搜索 匹配目标的[P_BITS] (同时 与 match_types 匹配)

$match_types['imm']   = array('i' => true); 
$match_types['mem']   = array('m' => true);

$match_types['reg8']  = array('r' => true);
$match_bits['reg8']   = array('8' => true);

$match_types['reg16'] = array('r' => true);
$match_bits['reg16']  = array('16' => true);

$match_types['reg32'] = array('r' => true);
$match_bits['reg32']  = array('32' => true);


$match_types['rm8']  = array('r' => true,'m' => true);
$match_bits['rm8']   = array('8' => true);
$match_types['rm16']  = array('r' => true,'m' => true);
$match_bits['rm16']   = array('16' => true);
$match_types['rm32']  = array('r' => true,'m' => true);
$match_bits['rm32']   = array('32' => true);
$match_types['rm64']  = array('r' => true,'m' => true);
$match_bits['rm64']   = array('64' => true);

$match_types['imm8']  = array('i' => true);
$match_bits['imm8']   = array('8' => true,'4' => true);  
$match_types['imm16'] = array('i' => true);
$match_bits['imm16']  = array('16' => true);  
$match_types['imm32'] = array('i' => true);
$match_bits['imm32']  = array('32' => true);  

    
$match_types['sbyteword']    = array('i' => true);
$match_bits['sbyteword']     = array('8' => true);
$match_types['sbytedword']   = array('i' => true);
$match_bits['sbytedword']    = array('8' => true);
$match_types['sbyteword16']  = array('i' => true);
$match_bits['sbyteword16']   = array('8' => true);
$match_types['sbytedword32'] = array('i' => true);
$match_bits['sbytedword32']  = array('8' => true);
$match_types['sbytedword64'] = array('i' => true);
$match_bits['sbytedword64']  = array('8' => true);

//////////////////////////////////////////////////////////////

$opcode_len_arrays['AAA']['0'][] = array('0' => 'void',);
$opcode_len_result['AAA']['0'][] = 1;  //37]
$opcode_len_arrays['AAD']['0'][] = array('0' => 'void',);
$opcode_len_result['AAD']['0'][] = 2;  //d5 0a]
$opcode_len_arrays['AAD']['1'][] = array('0' => 'imm',);
$opcode_len_result['AAD']['1'][] = 2;  //d5 ib,u]
$opcode_len_arrays['AAM']['0'][] = array('0' => 'void',);
$opcode_len_result['AAM']['0'][] = 2;  //d4 0a]
$opcode_len_arrays['AAM']['1'][] = array('0' => 'imm',);
$opcode_len_result['AAM']['1'][] = 2;  //d4 ib,u]
$opcode_len_arrays['AAS']['0'][] = array('0' => 'void',);
$opcode_len_result['AAS']['0'][] = 1;  //3f]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'mem','1' => 'reg8',);
$opcode_len_result['ADC']['2'][] = 2;  //hle 10 /r]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'reg8','1' => 'reg8',);
$opcode_len_result['ADC']['2'][] = 2;  //10 /r]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'mem','1' => 'reg16',);
$opcode_len_result['ADC']['2'][] = 3;  //hle o16 11 /r]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['ADC']['2'][] = 3;  //o16 11 /r]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'mem','1' => 'reg32',);
$opcode_len_result['ADC']['2'][] = 2;  //hle o32 11 /r]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['ADC']['2'][] = 2;  //o32 11 /r]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'reg8','1' => 'mem',);
$opcode_len_result['ADC']['2'][] = 2;  //12 /r]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'reg8','1' => 'reg8',);
$opcode_len_result['ADC']['2'][] = 2;  //12 /r]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['ADC']['2'][] = 3;  //o16 13 /r]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['ADC']['2'][] = 3;  //o16 13 /r]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['ADC']['2'][] = 2;  //o32 13 /r]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['ADC']['2'][] = 2;  //o32 13 /r]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'rm16','1' => 'imm8',);
$opcode_len_result['ADC']['2'][] = 4;  //hle o16 83 /2 ib,s]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'rm32','1' => 'imm8',);
$opcode_len_result['ADC']['2'][] = 3;  //hle o32 83 /2 ib,s]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'reg_al','1' => 'imm',);
$opcode_len_result['ADC']['2'][] = 2;  //14 ib]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'reg_ax','1' => 'sbyteword',);
$opcode_len_result['ADC']['2'][] = 4;  //o16 83 /2 ib,s]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'reg_ax','1' => 'imm',);
$opcode_len_result['ADC']['2'][] = 4;  //o16 15 iw]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'reg_eax','1' => 'sbytedword',);
$opcode_len_result['ADC']['2'][] = 3;  //o32 83 /2 ib,s]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'reg_eax','1' => 'imm',);
$opcode_len_result['ADC']['2'][] = 5;  //o32 15 id]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'rm8','1' => 'imm',);
$opcode_len_result['ADC']['2'][] = 3;  //hle 80 /2 ib]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'rm16','1' => 'sbyteword',);
$opcode_len_result['ADC']['2'][] = 4;  //hle o16 83 /2 ib,s]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'rm16','1' => 'imm',);
$opcode_len_result['ADC']['2'][] = 5;  //hle o16 81 /2 iw]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'rm32','1' => 'sbytedword',);
$opcode_len_result['ADC']['2'][] = 3;  //hle o32 83 /2 ib,s]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'rm32','1' => 'imm',);
$opcode_len_result['ADC']['2'][] = 6;  //hle o32 81 /2 id]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'mem','1' => 'imm8',);
$opcode_len_result['ADC']['2'][] = 3;  //hle 80 /2 ib]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'mem','1' => 'sbyteword16',);
$opcode_len_result['ADC']['2'][] = 4;  //hle o16 83 /2 ib,s]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'mem','1' => 'imm16',);
$opcode_len_result['ADC']['2'][] = 5;  //hle o16 81 /2 iw]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'mem','1' => 'sbytedword32',);
$opcode_len_result['ADC']['2'][] = 3;  //hle o32 83 /2 ib,s]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'mem','1' => 'imm32',);
$opcode_len_result['ADC']['2'][] = 6;  //hle o32 81 /2 id]
$opcode_len_arrays['ADC']['2'][] = array('0' => 'rm8','1' => 'imm',);
$opcode_len_result['ADC']['2'][] = 3;  //hle 82 /2 ib]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'mem','1' => 'reg8',);
$opcode_len_result['ADD']['2'][] = 2;  //hle 00 /r]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'reg8','1' => 'reg8',);
$opcode_len_result['ADD']['2'][] = 2;  //00 /r]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'mem','1' => 'reg16',);
$opcode_len_result['ADD']['2'][] = 3;  //hle o16 01 /r]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['ADD']['2'][] = 3;  //o16 01 /r]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'mem','1' => 'reg32',);
$opcode_len_result['ADD']['2'][] = 2;  //hle o32 01 /r]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['ADD']['2'][] = 2;  //o32 01 /r]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'reg8','1' => 'mem',);
$opcode_len_result['ADD']['2'][] = 2;  //02 /r]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'reg8','1' => 'reg8',);
$opcode_len_result['ADD']['2'][] = 2;  //02 /r]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['ADD']['2'][] = 3;  //o16 03 /r]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['ADD']['2'][] = 3;  //o16 03 /r]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['ADD']['2'][] = 2;  //o32 03 /r]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['ADD']['2'][] = 2;  //o32 03 /r]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'rm16','1' => 'imm8',);
$opcode_len_result['ADD']['2'][] = 4;  //hle o16 83 /0 ib,s]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'rm32','1' => 'imm8',);
$opcode_len_result['ADD']['2'][] = 3;  //hle o32 83 /0 ib,s]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'reg_al','1' => 'imm',);
$opcode_len_result['ADD']['2'][] = 2;  //04 ib]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'reg_ax','1' => 'sbyteword',);
$opcode_len_result['ADD']['2'][] = 4;  //o16 83 /0 ib,s]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'reg_ax','1' => 'imm',);
$opcode_len_result['ADD']['2'][] = 4;  //o16 05 iw]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'reg_eax','1' => 'sbytedword',);
$opcode_len_result['ADD']['2'][] = 3;  //o32 83 /0 ib,s]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'reg_eax','1' => 'imm',);
$opcode_len_result['ADD']['2'][] = 5;  //o32 05 id]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'rm8','1' => 'imm',);
$opcode_len_result['ADD']['2'][] = 3;  //hle 80 /0 ib]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'rm16','1' => 'sbyteword',);
$opcode_len_result['ADD']['2'][] = 4;  //hle o16 83 /0 ib,s]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'rm16','1' => 'imm',);
$opcode_len_result['ADD']['2'][] = 5;  //hle o16 81 /0 iw]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'rm32','1' => 'sbytedword',);
$opcode_len_result['ADD']['2'][] = 3;  //hle o32 83 /0 ib,s]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'rm32','1' => 'imm',);
$opcode_len_result['ADD']['2'][] = 6;  //hle o32 81 /0 id]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'mem','1' => 'imm8',);
$opcode_len_result['ADD']['2'][] = 3;  //hle 80 /0 ib]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'mem','1' => 'sbyteword16',);
$opcode_len_result['ADD']['2'][] = 4;  //hle o16 83 /0 ib,s]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'mem','1' => 'imm16',);
$opcode_len_result['ADD']['2'][] = 5;  //hle o16 81 /0 iw]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'mem','1' => 'sbytedword32',);
$opcode_len_result['ADD']['2'][] = 3;  //hle o32 83 /0 ib,s]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'mem','1' => 'imm32',);
$opcode_len_result['ADD']['2'][] = 6;  //hle o32 81 /0 id]
$opcode_len_arrays['ADD']['2'][] = array('0' => 'rm8','1' => 'imm',);
$opcode_len_result['ADD']['2'][] = 3;  //hle 82 /0 ib]
$opcode_len_arrays['AND']['2'][] = array('0' => 'mem','1' => 'reg8',);
$opcode_len_result['AND']['2'][] = 2;  //hle 20 /r]
$opcode_len_arrays['AND']['2'][] = array('0' => 'reg8','1' => 'reg8',);
$opcode_len_result['AND']['2'][] = 2;  //20 /r]
$opcode_len_arrays['AND']['2'][] = array('0' => 'mem','1' => 'reg16',);
$opcode_len_result['AND']['2'][] = 3;  //hle o16 21 /r]
$opcode_len_arrays['AND']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['AND']['2'][] = 3;  //o16 21 /r]
$opcode_len_arrays['AND']['2'][] = array('0' => 'mem','1' => 'reg32',);
$opcode_len_result['AND']['2'][] = 2;  //hle o32 21 /r]
$opcode_len_arrays['AND']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['AND']['2'][] = 2;  //o32 21 /r]
$opcode_len_arrays['AND']['2'][] = array('0' => 'reg8','1' => 'mem',);
$opcode_len_result['AND']['2'][] = 2;  //22 /r]
$opcode_len_arrays['AND']['2'][] = array('0' => 'reg8','1' => 'reg8',);
$opcode_len_result['AND']['2'][] = 2;  //22 /r]
$opcode_len_arrays['AND']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['AND']['2'][] = 3;  //o16 23 /r]
$opcode_len_arrays['AND']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['AND']['2'][] = 3;  //o16 23 /r]
$opcode_len_arrays['AND']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['AND']['2'][] = 2;  //o32 23 /r]
$opcode_len_arrays['AND']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['AND']['2'][] = 2;  //o32 23 /r]
$opcode_len_arrays['AND']['2'][] = array('0' => 'rm16','1' => 'imm8',);
$opcode_len_result['AND']['2'][] = 4;  //hle o16 83 /4 ib,s]
$opcode_len_arrays['AND']['2'][] = array('0' => 'rm32','1' => 'imm8',);
$opcode_len_result['AND']['2'][] = 3;  //hle o32 83 /4 ib,s]
$opcode_len_arrays['AND']['2'][] = array('0' => 'reg_al','1' => 'imm',);
$opcode_len_result['AND']['2'][] = 2;  //24 ib]
$opcode_len_arrays['AND']['2'][] = array('0' => 'reg_ax','1' => 'sbyteword',);
$opcode_len_result['AND']['2'][] = 4;  //o16 83 /4 ib,s]
$opcode_len_arrays['AND']['2'][] = array('0' => 'reg_ax','1' => 'imm',);
$opcode_len_result['AND']['2'][] = 4;  //o16 25 iw]
$opcode_len_arrays['AND']['2'][] = array('0' => 'reg_eax','1' => 'sbytedword',);
$opcode_len_result['AND']['2'][] = 3;  //o32 83 /4 ib,s]
$opcode_len_arrays['AND']['2'][] = array('0' => 'reg_eax','1' => 'imm',);
$opcode_len_result['AND']['2'][] = 5;  //o32 25 id]
$opcode_len_arrays['AND']['2'][] = array('0' => 'rm8','1' => 'imm',);
$opcode_len_result['AND']['2'][] = 3;  //hle 80 /4 ib]
$opcode_len_arrays['AND']['2'][] = array('0' => 'rm16','1' => 'sbyteword',);
$opcode_len_result['AND']['2'][] = 4;  //hle o16 83 /4 ib,s]
$opcode_len_arrays['AND']['2'][] = array('0' => 'rm16','1' => 'imm',);
$opcode_len_result['AND']['2'][] = 5;  //hle o16 81 /4 iw]
$opcode_len_arrays['AND']['2'][] = array('0' => 'rm32','1' => 'sbytedword',);
$opcode_len_result['AND']['2'][] = 3;  //hle o32 83 /4 ib,s]
$opcode_len_arrays['AND']['2'][] = array('0' => 'rm32','1' => 'imm',);
$opcode_len_result['AND']['2'][] = 6;  //hle o32 81 /4 id]
$opcode_len_arrays['AND']['2'][] = array('0' => 'mem','1' => 'imm8',);
$opcode_len_result['AND']['2'][] = 3;  //hle 80 /4 ib]
$opcode_len_arrays['AND']['2'][] = array('0' => 'mem','1' => 'sbyteword16',);
$opcode_len_result['AND']['2'][] = 4;  //hle o16 83 /4 ib,s]
$opcode_len_arrays['AND']['2'][] = array('0' => 'mem','1' => 'imm16',);
$opcode_len_result['AND']['2'][] = 5;  //hle o16 81 /4 iw]
$opcode_len_arrays['AND']['2'][] = array('0' => 'mem','1' => 'sbytedword32',);
$opcode_len_result['AND']['2'][] = 3;  //hle o32 83 /4 ib,s]
$opcode_len_arrays['AND']['2'][] = array('0' => 'mem','1' => 'imm32',);
$opcode_len_result['AND']['2'][] = 6;  //hle o32 81 /4 id]
$opcode_len_arrays['AND']['2'][] = array('0' => 'rm8','1' => 'imm',);
$opcode_len_result['AND']['2'][] = 3;  //hle 82 /4 ib]
$opcode_len_arrays['ARPL']['2'][] = array('0' => 'mem','1' => 'reg16',);
$opcode_len_result['ARPL']['2'][] = 2;  //63 /r]
$opcode_len_arrays['ARPL']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['ARPL']['2'][] = 2;  //63 /r]
$opcode_len_arrays['BB0_RESET']['0'][] = array('0' => 'void',);
$opcode_len_result['BB0_RESET']['0'][] = 2;  //0f 3a]
$opcode_len_arrays['BB1_RESET']['0'][] = array('0' => 'void',);
$opcode_len_result['BB1_RESET']['0'][] = 2;  //0f 3b]
$opcode_len_arrays['BOUND']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['BOUND']['2'][] = 3;  //o16 62 /r]
$opcode_len_arrays['BOUND']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['BOUND']['2'][] = 2;  //o32 62 /r]
$opcode_len_arrays['BSF']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['BSF']['2'][] = 4;  //o16 nof3 0f bc /r]
$opcode_len_arrays['BSF']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['BSF']['2'][] = 4;  //o16 nof3 0f bc /r]
$opcode_len_arrays['BSF']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['BSF']['2'][] = 3;  //o32 nof3 0f bc /r]
$opcode_len_arrays['BSF']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['BSF']['2'][] = 3;  //o32 nof3 0f bc /r]
$opcode_len_arrays['BSR']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['BSR']['2'][] = 4;  //o16 nof3 0f bd /r]
$opcode_len_arrays['BSR']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['BSR']['2'][] = 4;  //o16 nof3 0f bd /r]
$opcode_len_arrays['BSR']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['BSR']['2'][] = 3;  //o32 nof3 0f bd /r]
$opcode_len_arrays['BSR']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['BSR']['2'][] = 3;  //o32 nof3 0f bd /r]
$opcode_len_arrays['BSWAP']['1'][] = array('0' => 'reg32',);
$opcode_len_result['BSWAP']['1'][] = 2;  //o32 0f c8+r]
$opcode_len_arrays['BT']['2'][] = array('0' => 'mem','1' => 'reg16',);
$opcode_len_result['BT']['2'][] = 4;  //o16 0f a3 /r]
$opcode_len_arrays['BT']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['BT']['2'][] = 4;  //o16 0f a3 /r]
$opcode_len_arrays['BT']['2'][] = array('0' => 'mem','1' => 'reg32',);
$opcode_len_result['BT']['2'][] = 3;  //o32 0f a3 /r]
$opcode_len_arrays['BT']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['BT']['2'][] = 3;  //o32 0f a3 /r]
$opcode_len_arrays['BT']['2'][] = array('0' => 'rm16','1' => 'imm',);
$opcode_len_result['BT']['2'][] = 5;  //o16 0f ba /4 ib,u]
$opcode_len_arrays['BT']['2'][] = array('0' => 'rm32','1' => 'imm',);
$opcode_len_result['BT']['2'][] = 4;  //o32 0f ba /4 ib,u]
$opcode_len_arrays['BTC']['2'][] = array('0' => 'mem','1' => 'reg16',);
$opcode_len_result['BTC']['2'][] = 4;  //hle o16 0f bb /r]
$opcode_len_arrays['BTC']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['BTC']['2'][] = 4;  //o16 0f bb /r]
$opcode_len_arrays['BTC']['2'][] = array('0' => 'mem','1' => 'reg32',);
$opcode_len_result['BTC']['2'][] = 3;  //hle o32 0f bb /r]
$opcode_len_arrays['BTC']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['BTC']['2'][] = 3;  //o32 0f bb /r]
$opcode_len_arrays['BTC']['2'][] = array('0' => 'rm16','1' => 'imm',);
$opcode_len_result['BTC']['2'][] = 5;  //hle o16 0f ba /7 ib,u]
$opcode_len_arrays['BTC']['2'][] = array('0' => 'rm32','1' => 'imm',);
$opcode_len_result['BTC']['2'][] = 4;  //hle o32 0f ba /7 ib,u]
$opcode_len_arrays['BTR']['2'][] = array('0' => 'mem','1' => 'reg16',);
$opcode_len_result['BTR']['2'][] = 4;  //hle o16 0f b3 /r]
$opcode_len_arrays['BTR']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['BTR']['2'][] = 4;  //o16 0f b3 /r]
$opcode_len_arrays['BTR']['2'][] = array('0' => 'mem','1' => 'reg32',);
$opcode_len_result['BTR']['2'][] = 3;  //hle o32 0f b3 /r]
$opcode_len_arrays['BTR']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['BTR']['2'][] = 3;  //o32 0f b3 /r]
$opcode_len_arrays['BTR']['2'][] = array('0' => 'rm16','1' => 'imm',);
$opcode_len_result['BTR']['2'][] = 5;  //hle o16 0f ba /6 ib,u]
$opcode_len_arrays['BTR']['2'][] = array('0' => 'rm32','1' => 'imm',);
$opcode_len_result['BTR']['2'][] = 4;  //hle o32 0f ba /6 ib,u]
$opcode_len_arrays['BTS']['2'][] = array('0' => 'mem','1' => 'reg16',);
$opcode_len_result['BTS']['2'][] = 4;  //hle o16 0f ab /r]
$opcode_len_arrays['BTS']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['BTS']['2'][] = 4;  //o16 0f ab /r]
$opcode_len_arrays['BTS']['2'][] = array('0' => 'mem','1' => 'reg32',);
$opcode_len_result['BTS']['2'][] = 3;  //hle o32 0f ab /r]
$opcode_len_arrays['BTS']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['BTS']['2'][] = 3;  //o32 0f ab /r]
$opcode_len_arrays['BTS']['2'][] = array('0' => 'rm16','1' => 'imm',);
$opcode_len_result['BTS']['2'][] = 5;  //hle o16 0f ba /5 ib,u]
$opcode_len_arrays['BTS']['2'][] = array('0' => 'rm32','1' => 'imm',);
$opcode_len_result['BTS']['2'][] = 4;  //hle o32 0f ba /5 ib,u]
$opcode_len_arrays['CALL']['1'][] = array('0' => 'imm|far',);
$opcode_len_result['CALL']['1'][] = 7;  //odf 9a iwd seg]
$opcode_len_arrays['CALL']['1'][] = array('0' => 'imm16|far',);
$opcode_len_result['CALL']['1'][] = 8;  //o16 9a iwd seg]
$opcode_len_arrays['CALL']['1'][] = array('0' => 'imm32|far',);
$opcode_len_result['CALL']['1'][] = 7;  //o32 9a iwd seg]
$opcode_len_arrays['CALL']['1'][] = array('0' => 'imm:imm',);
$opcode_len_result['CALL']['1'][] = 7;  //odf 9a iwd iw]
$opcode_len_arrays['CALL']['1'][] = array('0' => 'imm16:imm',);
$opcode_len_result['CALL']['1'][] = 6;  //o16 9a iw iw]
$opcode_len_arrays['CALL']['1'][] = array('0' => 'imm:imm16',);
$opcode_len_result['CALL']['1'][] = 6;  //o16 9a iw iw]
$opcode_len_arrays['CALL']['1'][] = array('0' => 'imm32:imm',);
$opcode_len_result['CALL']['1'][] = 7;  //o32 9a id iw]
$opcode_len_arrays['CALL']['1'][] = array('0' => 'imm:imm32',);
$opcode_len_result['CALL']['1'][] = 7;  //o32 9a id iw]
$opcode_len_arrays['CALL']['1'][] = array('0' => 'mem|far',);
$opcode_len_result['CALL']['1'][] = 2;  //odf ff /3]
$opcode_len_arrays['CALL']['1'][] = array('0' => 'mem16|far',);
$opcode_len_result['CALL']['1'][] = 3;  //o16 ff /3]
$opcode_len_arrays['CALL']['1'][] = array('0' => 'mem32|far',);
$opcode_len_result['CALL']['1'][] = 2;  //o32 ff /3]
$opcode_len_arrays['CALL']['1'][] = array('0' => 'mem|near',);
$opcode_len_result['CALL']['1'][] = 2;  //odf ff /2]
$opcode_len_arrays['CALL']['1'][] = array('0' => 'rm16|near',);
$opcode_len_result['CALL']['1'][] = 3;  //o16 ff /2]
$opcode_len_arrays['CALL']['1'][] = array('0' => 'rm32|near',);
$opcode_len_result['CALL']['1'][] = 2;  //o32 ff /2]
$opcode_len_arrays['CALL']['1'][] = array('0' => 'mem',);
$opcode_len_result['CALL']['1'][] = 2;  //odf ff /2]
$opcode_len_arrays['CALL']['1'][] = array('0' => 'rm16',);
$opcode_len_result['CALL']['1'][] = 3;  //o16 ff /2]
$opcode_len_arrays['CALL']['1'][] = array('0' => 'rm32',);
$opcode_len_result['CALL']['1'][] = 2;  //o32 ff /2]
$opcode_len_arrays['CBW']['0'][] = array('0' => 'void',);
$opcode_len_result['CBW']['0'][] = 2;  //o16 98]
$opcode_len_arrays['CDQ']['0'][] = array('0' => 'void',);
$opcode_len_result['CDQ']['0'][] = 1;  //o32 99]
$opcode_len_arrays['CLC']['0'][] = array('0' => 'void',);
$opcode_len_result['CLC']['0'][] = 1;  //f8]
$opcode_len_arrays['CLD']['0'][] = array('0' => 'void',);
$opcode_len_result['CLD']['0'][] = 1;  //fc]
$opcode_len_arrays['CLI']['0'][] = array('0' => 'void',);
$opcode_len_result['CLI']['0'][] = 1;  //fa]
$opcode_len_arrays['CLTS']['0'][] = array('0' => 'void',);
$opcode_len_result['CLTS']['0'][] = 2;  //0f 06]
$opcode_len_arrays['CMC']['0'][] = array('0' => 'void',);
$opcode_len_result['CMC']['0'][] = 1;  //f5]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'mem','1' => 'reg8',);
$opcode_len_result['CMP']['2'][] = 2;  //38 /r]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'reg8','1' => 'reg8',);
$opcode_len_result['CMP']['2'][] = 2;  //38 /r]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'mem','1' => 'reg16',);
$opcode_len_result['CMP']['2'][] = 3;  //o16 39 /r]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['CMP']['2'][] = 3;  //o16 39 /r]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'mem','1' => 'reg32',);
$opcode_len_result['CMP']['2'][] = 2;  //o32 39 /r]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['CMP']['2'][] = 2;  //o32 39 /r]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'reg8','1' => 'mem',);
$opcode_len_result['CMP']['2'][] = 2;  //3a /r]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'reg8','1' => 'reg8',);
$opcode_len_result['CMP']['2'][] = 2;  //3a /r]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['CMP']['2'][] = 3;  //o16 3b /r]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['CMP']['2'][] = 3;  //o16 3b /r]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['CMP']['2'][] = 2;  //o32 3b /r]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['CMP']['2'][] = 2;  //o32 3b /r]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'rm16','1' => 'imm8',);
$opcode_len_result['CMP']['2'][] = 4;  //o16 83 /7 ib,s]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'rm32','1' => 'imm8',);
$opcode_len_result['CMP']['2'][] = 3;  //o32 83 /7 ib,s]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'reg_al','1' => 'imm',);
$opcode_len_result['CMP']['2'][] = 2;  //3c ib]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'reg_ax','1' => 'sbyteword',);
$opcode_len_result['CMP']['2'][] = 4;  //o16 83 /7 ib,s]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'reg_ax','1' => 'imm',);
$opcode_len_result['CMP']['2'][] = 4;  //o16 3d iw]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'reg_eax','1' => 'sbytedword',);
$opcode_len_result['CMP']['2'][] = 3;  //o32 83 /7 ib,s]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'reg_eax','1' => 'imm',);
$opcode_len_result['CMP']['2'][] = 5;  //o32 3d id]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'rm8','1' => 'imm',);
$opcode_len_result['CMP']['2'][] = 3;  //80 /7 ib]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'rm16','1' => 'sbyteword',);
$opcode_len_result['CMP']['2'][] = 4;  //o16 83 /7 ib,s]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'rm16','1' => 'imm',);
$opcode_len_result['CMP']['2'][] = 5;  //o16 81 /7 iw]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'rm32','1' => 'sbytedword',);
$opcode_len_result['CMP']['2'][] = 3;  //o32 83 /7 ib,s]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'rm32','1' => 'imm',);
$opcode_len_result['CMP']['2'][] = 6;  //o32 81 /7 id]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'mem','1' => 'imm8',);
$opcode_len_result['CMP']['2'][] = 3;  //80 /7 ib]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'mem','1' => 'sbyteword16',);
$opcode_len_result['CMP']['2'][] = 4;  //o16 83 /7 ib,s]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'mem','1' => 'imm16',);
$opcode_len_result['CMP']['2'][] = 5;  //o16 81 /7 iw]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'mem','1' => 'sbytedword32',);
$opcode_len_result['CMP']['2'][] = 3;  //o32 83 /7 ib,s]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'mem','1' => 'imm32',);
$opcode_len_result['CMP']['2'][] = 6;  //o32 81 /7 id]
$opcode_len_arrays['CMP']['2'][] = array('0' => 'rm8','1' => 'imm',);
$opcode_len_result['CMP']['2'][] = 3;  //82 /7 ib]
$opcode_len_arrays['CMPSB']['0'][] = array('0' => 'void',);
$opcode_len_result['CMPSB']['0'][] = 1;  //repe a6]
$opcode_len_arrays['CMPSD']['0'][] = array('0' => 'void',);
$opcode_len_result['CMPSD']['0'][] = 1;  //repe o32 a7]
$opcode_len_arrays['CMPSW']['0'][] = array('0' => 'void',);
$opcode_len_result['CMPSW']['0'][] = 2;  //repe o16 a7]
$opcode_len_arrays['CMPXCHG']['2'][] = array('0' => 'mem','1' => 'reg8',);
$opcode_len_result['CMPXCHG']['2'][] = 3;  //hle 0f b0 /r]
$opcode_len_arrays['CMPXCHG']['2'][] = array('0' => 'reg8','1' => 'reg8',);
$opcode_len_result['CMPXCHG']['2'][] = 3;  //0f b0 /r]
$opcode_len_arrays['CMPXCHG']['2'][] = array('0' => 'mem','1' => 'reg16',);
$opcode_len_result['CMPXCHG']['2'][] = 4;  //hle o16 0f b1 /r]
$opcode_len_arrays['CMPXCHG']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['CMPXCHG']['2'][] = 4;  //o16 0f b1 /r]
$opcode_len_arrays['CMPXCHG']['2'][] = array('0' => 'mem','1' => 'reg32',);
$opcode_len_result['CMPXCHG']['2'][] = 3;  //hle o32 0f b1 /r]
$opcode_len_arrays['CMPXCHG']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['CMPXCHG']['2'][] = 3;  //o32 0f b1 /r]
$opcode_len_arrays['CMPXCHG486']['2'][] = array('0' => 'mem','1' => 'reg8',);
$opcode_len_result['CMPXCHG486']['2'][] = 3;  //0f a6 /r]
$opcode_len_arrays['CMPXCHG486']['2'][] = array('0' => 'reg8','1' => 'reg8',);
$opcode_len_result['CMPXCHG486']['2'][] = 3;  //0f a6 /r]
$opcode_len_arrays['CMPXCHG486']['2'][] = array('0' => 'mem','1' => 'reg16',);
$opcode_len_result['CMPXCHG486']['2'][] = 4;  //o16 0f a7 /r]
$opcode_len_arrays['CMPXCHG486']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['CMPXCHG486']['2'][] = 4;  //o16 0f a7 /r]
$opcode_len_arrays['CMPXCHG486']['2'][] = array('0' => 'mem','1' => 'reg32',);
$opcode_len_result['CMPXCHG486']['2'][] = 3;  //o32 0f a7 /r]
$opcode_len_arrays['CMPXCHG486']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['CMPXCHG486']['2'][] = 3;  //o32 0f a7 /r]
$opcode_len_arrays['CPUID']['0'][] = array('0' => 'void',);
$opcode_len_result['CPUID']['0'][] = 2;  //0f a2]
$opcode_len_arrays['CPU_READ']['0'][] = array('0' => 'void',);
$opcode_len_result['CPU_READ']['0'][] = 2;  //0f 3d]
$opcode_len_arrays['CPU_WRITE']['0'][] = array('0' => 'void',);
$opcode_len_result['CPU_WRITE']['0'][] = 2;  //0f 3c]
$opcode_len_arrays['CWD']['0'][] = array('0' => 'void',);
$opcode_len_result['CWD']['0'][] = 2;  //o16 99]
$opcode_len_arrays['CWDE']['0'][] = array('0' => 'void',);
$opcode_len_result['CWDE']['0'][] = 1;  //o32 98]
$opcode_len_arrays['DAA']['0'][] = array('0' => 'void',);
$opcode_len_result['DAA']['0'][] = 1;  //27]
$opcode_len_arrays['DAS']['0'][] = array('0' => 'void',);
$opcode_len_result['DAS']['0'][] = 1;  //2f]
$opcode_len_arrays['DEC']['1'][] = array('0' => 'reg16',);
$opcode_len_result['DEC']['1'][] = 2;  //o16 48+r]
$opcode_len_arrays['DEC']['1'][] = array('0' => 'reg32',);
$opcode_len_result['DEC']['1'][] = 1;  //o32 48+r]
$opcode_len_arrays['DEC']['1'][] = array('0' => 'rm8',);
$opcode_len_result['DEC']['1'][] = 2;  //hle fe /1]
$opcode_len_arrays['DEC']['1'][] = array('0' => 'rm16',);
$opcode_len_result['DEC']['1'][] = 3;  //hle o16 ff /1]
$opcode_len_arrays['DEC']['1'][] = array('0' => 'rm32',);
$opcode_len_result['DEC']['1'][] = 2;  //hle o32 ff /1]
$opcode_len_arrays['DIV']['1'][] = array('0' => 'rm8',);
$opcode_len_result['DIV']['1'][] = 2;  //f6 /6]
$opcode_len_arrays['DIV']['1'][] = array('0' => 'rm16',);
$opcode_len_result['DIV']['1'][] = 3;  //o16 f7 /6]
$opcode_len_arrays['DIV']['1'][] = array('0' => 'rm32',);
$opcode_len_result['DIV']['1'][] = 2;  //o32 f7 /6]
$opcode_len_arrays['DMINT']['0'][] = array('0' => 'void',);
$opcode_len_result['DMINT']['0'][] = 2;  //0f 39]
$opcode_len_arrays['EMMS']['0'][] = array('0' => 'void',);
$opcode_len_result['EMMS']['0'][] = 2;  //0f 77]
$opcode_len_arrays['ENTER']['2'][] = array('0' => 'imm','1' => 'imm',);
$opcode_len_result['ENTER']['2'][] = 4;  //c8 iw ib,u]
$opcode_len_arrays['F2XM1']['0'][] = array('0' => 'void',);
$opcode_len_result['F2XM1']['0'][] = 2;  //d9 f0]
$opcode_len_arrays['FABS']['0'][] = array('0' => 'void',);
$opcode_len_result['FABS']['0'][] = 2;  //d9 e1]
$opcode_len_arrays['FADD']['1'][] = array('0' => 'mem32',);
$opcode_len_result['FADD']['1'][] = 2;  //d8 /0]
$opcode_len_arrays['FADD']['1'][] = array('0' => 'mem64',);
$opcode_len_result['FADD']['1'][] = 2;  //dc /0]
$opcode_len_arrays['FADD']['1'][] = array('0' => 'fpureg|to',);
$opcode_len_result['FADD']['1'][] = 2;  //dc c0+r]
$opcode_len_arrays['FADD']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FADD']['1'][] = 2;  //d8 c0+r]
$opcode_len_arrays['FADD']['2'][] = array('0' => 'fpureg','1' => 'fpu0',);
$opcode_len_result['FADD']['2'][] = 2;  //dc c0+r]
$opcode_len_arrays['FADD']['2'][] = array('0' => 'fpu0','1' => 'fpureg',);
$opcode_len_result['FADD']['2'][] = 2;  //d8 c0+r]
$opcode_len_arrays['FADD']['0'][] = array('0' => 'void',);
$opcode_len_result['FADD']['0'][] = 2;  //de c1]
$opcode_len_arrays['FADDP']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FADDP']['1'][] = 2;  //de c0+r]
$opcode_len_arrays['FADDP']['2'][] = array('0' => 'fpureg','1' => 'fpu0',);
$opcode_len_result['FADDP']['2'][] = 2;  //de c0+r]
$opcode_len_arrays['FADDP']['0'][] = array('0' => 'void',);
$opcode_len_result['FADDP']['0'][] = 2;  //de c1]
$opcode_len_arrays['FBLD']['1'][] = array('0' => 'mem80',);
$opcode_len_result['FBLD']['1'][] = 2;  //df /4]
$opcode_len_arrays['FBLD']['1'][] = array('0' => 'mem',);
$opcode_len_result['FBLD']['1'][] = 2;  //df /4]
$opcode_len_arrays['FBSTP']['1'][] = array('0' => 'mem80',);
$opcode_len_result['FBSTP']['1'][] = 2;  //df /6]
$opcode_len_arrays['FBSTP']['1'][] = array('0' => 'mem',);
$opcode_len_result['FBSTP']['1'][] = 2;  //df /6]
$opcode_len_arrays['FCHS']['0'][] = array('0' => 'void',);
$opcode_len_result['FCHS']['0'][] = 2;  //d9 e0]
$opcode_len_arrays['FCMOVB']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FCMOVB']['1'][] = 2;  //da c0+r]
$opcode_len_arrays['FCMOVB']['2'][] = array('0' => 'fpu0','1' => 'fpureg',);
$opcode_len_result['FCMOVB']['2'][] = 2;  //da c0+r]
$opcode_len_arrays['FCMOVB']['0'][] = array('0' => 'void',);
$opcode_len_result['FCMOVB']['0'][] = 2;  //da c1]
$opcode_len_arrays['FCMOVBE']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FCMOVBE']['1'][] = 2;  //da d0+r]
$opcode_len_arrays['FCMOVBE']['2'][] = array('0' => 'fpu0','1' => 'fpureg',);
$opcode_len_result['FCMOVBE']['2'][] = 2;  //da d0+r]
$opcode_len_arrays['FCMOVBE']['0'][] = array('0' => 'void',);
$opcode_len_result['FCMOVBE']['0'][] = 2;  //da d1]
$opcode_len_arrays['FCMOVE']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FCMOVE']['1'][] = 2;  //da c8+r]
$opcode_len_arrays['FCMOVE']['2'][] = array('0' => 'fpu0','1' => 'fpureg',);
$opcode_len_result['FCMOVE']['2'][] = 2;  //da c8+r]
$opcode_len_arrays['FCMOVE']['0'][] = array('0' => 'void',);
$opcode_len_result['FCMOVE']['0'][] = 2;  //da c9]
$opcode_len_arrays['FCMOVNB']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FCMOVNB']['1'][] = 2;  //db c0+r]
$opcode_len_arrays['FCMOVNB']['2'][] = array('0' => 'fpu0','1' => 'fpureg',);
$opcode_len_result['FCMOVNB']['2'][] = 2;  //db c0+r]
$opcode_len_arrays['FCMOVNB']['0'][] = array('0' => 'void',);
$opcode_len_result['FCMOVNB']['0'][] = 2;  //db c1]
$opcode_len_arrays['FCMOVNBE']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FCMOVNBE']['1'][] = 2;  //db d0+r]
$opcode_len_arrays['FCMOVNBE']['2'][] = array('0' => 'fpu0','1' => 'fpureg',);
$opcode_len_result['FCMOVNBE']['2'][] = 2;  //db d0+r]
$opcode_len_arrays['FCMOVNBE']['0'][] = array('0' => 'void',);
$opcode_len_result['FCMOVNBE']['0'][] = 2;  //db d1]
$opcode_len_arrays['FCMOVNE']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FCMOVNE']['1'][] = 2;  //db c8+r]
$opcode_len_arrays['FCMOVNE']['2'][] = array('0' => 'fpu0','1' => 'fpureg',);
$opcode_len_result['FCMOVNE']['2'][] = 2;  //db c8+r]
$opcode_len_arrays['FCMOVNE']['0'][] = array('0' => 'void',);
$opcode_len_result['FCMOVNE']['0'][] = 2;  //db c9]
$opcode_len_arrays['FCMOVNU']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FCMOVNU']['1'][] = 2;  //db d8+r]
$opcode_len_arrays['FCMOVNU']['2'][] = array('0' => 'fpu0','1' => 'fpureg',);
$opcode_len_result['FCMOVNU']['2'][] = 2;  //db d8+r]
$opcode_len_arrays['FCMOVNU']['0'][] = array('0' => 'void',);
$opcode_len_result['FCMOVNU']['0'][] = 2;  //db d9]
$opcode_len_arrays['FCMOVU']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FCMOVU']['1'][] = 2;  //da d8+r]
$opcode_len_arrays['FCMOVU']['2'][] = array('0' => 'fpu0','1' => 'fpureg',);
$opcode_len_result['FCMOVU']['2'][] = 2;  //da d8+r]
$opcode_len_arrays['FCMOVU']['0'][] = array('0' => 'void',);
$opcode_len_result['FCMOVU']['0'][] = 2;  //da d9]
$opcode_len_arrays['FCOM']['1'][] = array('0' => 'mem32',);
$opcode_len_result['FCOM']['1'][] = 2;  //d8 /2]
$opcode_len_arrays['FCOM']['1'][] = array('0' => 'mem64',);
$opcode_len_result['FCOM']['1'][] = 2;  //dc /2]
$opcode_len_arrays['FCOM']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FCOM']['1'][] = 2;  //d8 d0+r]
$opcode_len_arrays['FCOM']['2'][] = array('0' => 'fpu0','1' => 'fpureg',);
$opcode_len_result['FCOM']['2'][] = 2;  //d8 d0+r]
$opcode_len_arrays['FCOM']['0'][] = array('0' => 'void',);
$opcode_len_result['FCOM']['0'][] = 2;  //d8 d1]
$opcode_len_arrays['FCOMI']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FCOMI']['1'][] = 2;  //db f0+r]
$opcode_len_arrays['FCOMI']['2'][] = array('0' => 'fpu0','1' => 'fpureg',);
$opcode_len_result['FCOMI']['2'][] = 2;  //db f0+r]
$opcode_len_arrays['FCOMI']['0'][] = array('0' => 'void',);
$opcode_len_result['FCOMI']['0'][] = 2;  //db f1]
$opcode_len_arrays['FCOMIP']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FCOMIP']['1'][] = 2;  //df f0+r]
$opcode_len_arrays['FCOMIP']['2'][] = array('0' => 'fpu0','1' => 'fpureg',);
$opcode_len_result['FCOMIP']['2'][] = 2;  //df f0+r]
$opcode_len_arrays['FCOMIP']['0'][] = array('0' => 'void',);
$opcode_len_result['FCOMIP']['0'][] = 2;  //df f1]
$opcode_len_arrays['FCOMP']['1'][] = array('0' => 'mem32',);
$opcode_len_result['FCOMP']['1'][] = 2;  //d8 /3]
$opcode_len_arrays['FCOMP']['1'][] = array('0' => 'mem64',);
$opcode_len_result['FCOMP']['1'][] = 2;  //dc /3]
$opcode_len_arrays['FCOMP']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FCOMP']['1'][] = 2;  //d8 d8+r]
$opcode_len_arrays['FCOMP']['2'][] = array('0' => 'fpu0','1' => 'fpureg',);
$opcode_len_result['FCOMP']['2'][] = 2;  //d8 d8+r]
$opcode_len_arrays['FCOMP']['0'][] = array('0' => 'void',);
$opcode_len_result['FCOMP']['0'][] = 2;  //d8 d9]
$opcode_len_arrays['FCOMPP']['0'][] = array('0' => 'void',);
$opcode_len_result['FCOMPP']['0'][] = 2;  //de d9]
$opcode_len_arrays['FCOS']['0'][] = array('0' => 'void',);
$opcode_len_result['FCOS']['0'][] = 2;  //d9 ff]
$opcode_len_arrays['FDECSTP']['0'][] = array('0' => 'void',);
$opcode_len_result['FDECSTP']['0'][] = 2;  //d9 f6]
$opcode_len_arrays['FDIV']['1'][] = array('0' => 'mem32',);
$opcode_len_result['FDIV']['1'][] = 2;  //d8 /6]
$opcode_len_arrays['FDIV']['1'][] = array('0' => 'mem64',);
$opcode_len_result['FDIV']['1'][] = 2;  //dc /6]
$opcode_len_arrays['FDIV']['1'][] = array('0' => 'fpureg|to',);
$opcode_len_result['FDIV']['1'][] = 2;  //dc f8+r]
$opcode_len_arrays['FDIV']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FDIV']['1'][] = 2;  //d8 f0+r]
$opcode_len_arrays['FDIV']['2'][] = array('0' => 'fpureg','1' => 'fpu0',);
$opcode_len_result['FDIV']['2'][] = 2;  //dc f8+r]
$opcode_len_arrays['FDIV']['2'][] = array('0' => 'fpu0','1' => 'fpureg',);
$opcode_len_result['FDIV']['2'][] = 2;  //d8 f0+r]
$opcode_len_arrays['FDIV']['0'][] = array('0' => 'void',);
$opcode_len_result['FDIV']['0'][] = 2;  //de f9]
$opcode_len_arrays['FDIVP']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FDIVP']['1'][] = 2;  //de f8+r]
$opcode_len_arrays['FDIVP']['2'][] = array('0' => 'fpureg','1' => 'fpu0',);
$opcode_len_result['FDIVP']['2'][] = 2;  //de f8+r]
$opcode_len_arrays['FDIVP']['0'][] = array('0' => 'void',);
$opcode_len_result['FDIVP']['0'][] = 2;  //de f9]
$opcode_len_arrays['FDIVR']['1'][] = array('0' => 'mem32',);
$opcode_len_result['FDIVR']['1'][] = 2;  //d8 /7]
$opcode_len_arrays['FDIVR']['1'][] = array('0' => 'mem64',);
$opcode_len_result['FDIVR']['1'][] = 2;  //dc /7]
$opcode_len_arrays['FDIVR']['1'][] = array('0' => 'fpureg|to',);
$opcode_len_result['FDIVR']['1'][] = 2;  //dc f0+r]
$opcode_len_arrays['FDIVR']['2'][] = array('0' => 'fpureg','1' => 'fpu0',);
$opcode_len_result['FDIVR']['2'][] = 2;  //dc f0+r]
$opcode_len_arrays['FDIVR']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FDIVR']['1'][] = 2;  //d8 f8+r]
$opcode_len_arrays['FDIVR']['2'][] = array('0' => 'fpu0','1' => 'fpureg',);
$opcode_len_result['FDIVR']['2'][] = 2;  //d8 f8+r]
$opcode_len_arrays['FDIVR']['0'][] = array('0' => 'void',);
$opcode_len_result['FDIVR']['0'][] = 2;  //de f1]
$opcode_len_arrays['FDIVRP']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FDIVRP']['1'][] = 2;  //de f0+r]
$opcode_len_arrays['FDIVRP']['2'][] = array('0' => 'fpureg','1' => 'fpu0',);
$opcode_len_result['FDIVRP']['2'][] = 2;  //de f0+r]
$opcode_len_arrays['FDIVRP']['0'][] = array('0' => 'void',);
$opcode_len_result['FDIVRP']['0'][] = 2;  //de f1]
$opcode_len_arrays['FEMMS']['0'][] = array('0' => 'void',);
$opcode_len_result['FEMMS']['0'][] = 2;  //0f 0e]
$opcode_len_arrays['FFREE']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FFREE']['1'][] = 2;  //dd c0+r]
$opcode_len_arrays['FFREE']['0'][] = array('0' => 'void',);
$opcode_len_result['FFREE']['0'][] = 2;  //dd c1]
$opcode_len_arrays['FFREEP']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FFREEP']['1'][] = 2;  //df c0+r]
$opcode_len_arrays['FFREEP']['0'][] = array('0' => 'void',);
$opcode_len_result['FFREEP']['0'][] = 2;  //df c1]
$opcode_len_arrays['FIADD']['1'][] = array('0' => 'mem32',);
$opcode_len_result['FIADD']['1'][] = 2;  //da /0]
$opcode_len_arrays['FIADD']['1'][] = array('0' => 'mem16',);
$opcode_len_result['FIADD']['1'][] = 2;  //de /0]
$opcode_len_arrays['FICOM']['1'][] = array('0' => 'mem32',);
$opcode_len_result['FICOM']['1'][] = 2;  //da /2]
$opcode_len_arrays['FICOM']['1'][] = array('0' => 'mem16',);
$opcode_len_result['FICOM']['1'][] = 2;  //de /2]
$opcode_len_arrays['FICOMP']['1'][] = array('0' => 'mem32',);
$opcode_len_result['FICOMP']['1'][] = 2;  //da /3]
$opcode_len_arrays['FICOMP']['1'][] = array('0' => 'mem16',);
$opcode_len_result['FICOMP']['1'][] = 2;  //de /3]
$opcode_len_arrays['FIDIV']['1'][] = array('0' => 'mem32',);
$opcode_len_result['FIDIV']['1'][] = 2;  //da /6]
$opcode_len_arrays['FIDIV']['1'][] = array('0' => 'mem16',);
$opcode_len_result['FIDIV']['1'][] = 2;  //de /6]
$opcode_len_arrays['FIDIVR']['1'][] = array('0' => 'mem32',);
$opcode_len_result['FIDIVR']['1'][] = 2;  //da /7]
$opcode_len_arrays['FIDIVR']['1'][] = array('0' => 'mem16',);
$opcode_len_result['FIDIVR']['1'][] = 2;  //de /7]
$opcode_len_arrays['FILD']['1'][] = array('0' => 'mem32',);
$opcode_len_result['FILD']['1'][] = 2;  //db /0]
$opcode_len_arrays['FILD']['1'][] = array('0' => 'mem16',);
$opcode_len_result['FILD']['1'][] = 2;  //df /0]
$opcode_len_arrays['FILD']['1'][] = array('0' => 'mem64',);
$opcode_len_result['FILD']['1'][] = 2;  //df /5]
$opcode_len_arrays['FIMUL']['1'][] = array('0' => 'mem32',);
$opcode_len_result['FIMUL']['1'][] = 2;  //da /1]
$opcode_len_arrays['FIMUL']['1'][] = array('0' => 'mem16',);
$opcode_len_result['FIMUL']['1'][] = 2;  //de /1]
$opcode_len_arrays['FINCSTP']['0'][] = array('0' => 'void',);
$opcode_len_result['FINCSTP']['0'][] = 2;  //d9 f7]
$opcode_len_arrays['FIST']['1'][] = array('0' => 'mem32',);
$opcode_len_result['FIST']['1'][] = 2;  //db /2]
$opcode_len_arrays['FIST']['1'][] = array('0' => 'mem16',);
$opcode_len_result['FIST']['1'][] = 2;  //df /2]
$opcode_len_arrays['FISTP']['1'][] = array('0' => 'mem32',);
$opcode_len_result['FISTP']['1'][] = 2;  //db /3]
$opcode_len_arrays['FISTP']['1'][] = array('0' => 'mem16',);
$opcode_len_result['FISTP']['1'][] = 2;  //df /3]
$opcode_len_arrays['FISTP']['1'][] = array('0' => 'mem64',);
$opcode_len_result['FISTP']['1'][] = 2;  //df /7]
$opcode_len_arrays['FISTTP']['1'][] = array('0' => 'mem16',);
$opcode_len_result['FISTTP']['1'][] = 2;  //df /1]
$opcode_len_arrays['FISTTP']['1'][] = array('0' => 'mem32',);
$opcode_len_result['FISTTP']['1'][] = 2;  //db /1]
$opcode_len_arrays['FISTTP']['1'][] = array('0' => 'mem64',);
$opcode_len_result['FISTTP']['1'][] = 2;  //dd /1]
$opcode_len_arrays['FISUB']['1'][] = array('0' => 'mem32',);
$opcode_len_result['FISUB']['1'][] = 2;  //da /4]
$opcode_len_arrays['FISUB']['1'][] = array('0' => 'mem16',);
$opcode_len_result['FISUB']['1'][] = 2;  //de /4]
$opcode_len_arrays['FISUBR']['1'][] = array('0' => 'mem32',);
$opcode_len_result['FISUBR']['1'][] = 2;  //da /5]
$opcode_len_arrays['FISUBR']['1'][] = array('0' => 'mem16',);
$opcode_len_result['FISUBR']['1'][] = 2;  //de /5]
$opcode_len_arrays['FLD']['1'][] = array('0' => 'mem32',);
$opcode_len_result['FLD']['1'][] = 2;  //d9 /0]
$opcode_len_arrays['FLD']['1'][] = array('0' => 'mem64',);
$opcode_len_result['FLD']['1'][] = 2;  //dd /0]
$opcode_len_arrays['FLD']['1'][] = array('0' => 'mem80',);
$opcode_len_result['FLD']['1'][] = 2;  //db /5]
$opcode_len_arrays['FLD']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FLD']['1'][] = 2;  //d9 c0+r]
$opcode_len_arrays['FLD']['0'][] = array('0' => 'void',);
$opcode_len_result['FLD']['0'][] = 2;  //d9 c1]
$opcode_len_arrays['FLD1']['0'][] = array('0' => 'void',);
$opcode_len_result['FLD1']['0'][] = 2;  //d9 e8]
$opcode_len_arrays['FLDCW']['1'][] = array('0' => 'mem',);
$opcode_len_result['FLDCW']['1'][] = 2;  //d9 /5]
$opcode_len_arrays['FLDENV']['1'][] = array('0' => 'mem',);
$opcode_len_result['FLDENV']['1'][] = 2;  //d9 /4]
$opcode_len_arrays['FLDL2E']['0'][] = array('0' => 'void',);
$opcode_len_result['FLDL2E']['0'][] = 2;  //d9 ea]
$opcode_len_arrays['FLDL2T']['0'][] = array('0' => 'void',);
$opcode_len_result['FLDL2T']['0'][] = 2;  //d9 e9]
$opcode_len_arrays['FLDLG2']['0'][] = array('0' => 'void',);
$opcode_len_result['FLDLG2']['0'][] = 2;  //d9 ec]
$opcode_len_arrays['FLDLN2']['0'][] = array('0' => 'void',);
$opcode_len_result['FLDLN2']['0'][] = 2;  //d9 ed]
$opcode_len_arrays['FLDPI']['0'][] = array('0' => 'void',);
$opcode_len_result['FLDPI']['0'][] = 2;  //d9 eb]
$opcode_len_arrays['FLDZ']['0'][] = array('0' => 'void',);
$opcode_len_result['FLDZ']['0'][] = 2;  //d9 ee]
$opcode_len_arrays['FMUL']['1'][] = array('0' => 'mem32',);
$opcode_len_result['FMUL']['1'][] = 2;  //d8 /1]
$opcode_len_arrays['FMUL']['1'][] = array('0' => 'mem64',);
$opcode_len_result['FMUL']['1'][] = 2;  //dc /1]
$opcode_len_arrays['FMUL']['1'][] = array('0' => 'fpureg|to',);
$opcode_len_result['FMUL']['1'][] = 2;  //dc c8+r]
$opcode_len_arrays['FMUL']['2'][] = array('0' => 'fpureg','1' => 'fpu0',);
$opcode_len_result['FMUL']['2'][] = 2;  //dc c8+r]
$opcode_len_arrays['FMUL']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FMUL']['1'][] = 2;  //d8 c8+r]
$opcode_len_arrays['FMUL']['2'][] = array('0' => 'fpu0','1' => 'fpureg',);
$opcode_len_result['FMUL']['2'][] = 2;  //d8 c8+r]
$opcode_len_arrays['FMUL']['0'][] = array('0' => 'void',);
$opcode_len_result['FMUL']['0'][] = 2;  //de c9]
$opcode_len_arrays['FMULP']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FMULP']['1'][] = 2;  //de c8+r]
$opcode_len_arrays['FMULP']['2'][] = array('0' => 'fpureg','1' => 'fpu0',);
$opcode_len_result['FMULP']['2'][] = 2;  //de c8+r]
$opcode_len_arrays['FMULP']['0'][] = array('0' => 'void',);
$opcode_len_result['FMULP']['0'][] = 2;  //de c9]
$opcode_len_arrays['FNCLEX']['0'][] = array('0' => 'void',);
$opcode_len_result['FNCLEX']['0'][] = 2;  //db e2]
$opcode_len_arrays['FNDISI']['0'][] = array('0' => 'void',);
$opcode_len_result['FNDISI']['0'][] = 2;  //db e1]
$opcode_len_arrays['FNENI']['0'][] = array('0' => 'void',);
$opcode_len_result['FNENI']['0'][] = 2;  //db e0]
$opcode_len_arrays['FNINIT']['0'][] = array('0' => 'void',);
$opcode_len_result['FNINIT']['0'][] = 2;  //db e3]
$opcode_len_arrays['FNOP']['0'][] = array('0' => 'void',);
$opcode_len_result['FNOP']['0'][] = 2;  //d9 d0]
$opcode_len_arrays['FNSAVE']['1'][] = array('0' => 'mem',);
$opcode_len_result['FNSAVE']['1'][] = 2;  //dd /6]
$opcode_len_arrays['FNSTCW']['1'][] = array('0' => 'mem',);
$opcode_len_result['FNSTCW']['1'][] = 2;  //d9 /7]
$opcode_len_arrays['FNSTENV']['1'][] = array('0' => 'mem',);
$opcode_len_result['FNSTENV']['1'][] = 2;  //d9 /6]
$opcode_len_arrays['FNSTSW']['1'][] = array('0' => 'mem',);
$opcode_len_result['FNSTSW']['1'][] = 2;  //dd /7]
$opcode_len_arrays['FNSTSW']['1'][] = array('0' => 'reg_ax',);
$opcode_len_result['FNSTSW']['1'][] = 2;  //df e0]
$opcode_len_arrays['FPATAN']['0'][] = array('0' => 'void',);
$opcode_len_result['FPATAN']['0'][] = 2;  //d9 f3]
$opcode_len_arrays['FPREM']['0'][] = array('0' => 'void',);
$opcode_len_result['FPREM']['0'][] = 2;  //d9 f8]
$opcode_len_arrays['FPREM1']['0'][] = array('0' => 'void',);
$opcode_len_result['FPREM1']['0'][] = 2;  //d9 f5]
$opcode_len_arrays['FPTAN']['0'][] = array('0' => 'void',);
$opcode_len_result['FPTAN']['0'][] = 2;  //d9 f2]
$opcode_len_arrays['FRNDINT']['0'][] = array('0' => 'void',);
$opcode_len_result['FRNDINT']['0'][] = 2;  //d9 fc]
$opcode_len_arrays['FRSTOR']['1'][] = array('0' => 'mem',);
$opcode_len_result['FRSTOR']['1'][] = 2;  //dd /4]
$opcode_len_arrays['FSCALE']['0'][] = array('0' => 'void',);
$opcode_len_result['FSCALE']['0'][] = 2;  //d9 fd]
$opcode_len_arrays['FSETPM']['0'][] = array('0' => 'void',);
$opcode_len_result['FSETPM']['0'][] = 2;  //db e4]
$opcode_len_arrays['FSIN']['0'][] = array('0' => 'void',);
$opcode_len_result['FSIN']['0'][] = 2;  //d9 fe]
$opcode_len_arrays['FSINCOS']['0'][] = array('0' => 'void',);
$opcode_len_result['FSINCOS']['0'][] = 2;  //d9 fb]
$opcode_len_arrays['FSQRT']['0'][] = array('0' => 'void',);
$opcode_len_result['FSQRT']['0'][] = 2;  //d9 fa]
$opcode_len_arrays['FST']['1'][] = array('0' => 'mem32',);
$opcode_len_result['FST']['1'][] = 2;  //d9 /2]
$opcode_len_arrays['FST']['1'][] = array('0' => 'mem64',);
$opcode_len_result['FST']['1'][] = 2;  //dd /2]
$opcode_len_arrays['FST']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FST']['1'][] = 2;  //dd d0+r]
$opcode_len_arrays['FST']['0'][] = array('0' => 'void',);
$opcode_len_result['FST']['0'][] = 2;  //dd d1]
$opcode_len_arrays['FSTP']['1'][] = array('0' => 'mem32',);
$opcode_len_result['FSTP']['1'][] = 2;  //d9 /3]
$opcode_len_arrays['FSTP']['1'][] = array('0' => 'mem64',);
$opcode_len_result['FSTP']['1'][] = 2;  //dd /3]
$opcode_len_arrays['FSTP']['1'][] = array('0' => 'mem80',);
$opcode_len_result['FSTP']['1'][] = 2;  //db /7]
$opcode_len_arrays['FSTP']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FSTP']['1'][] = 2;  //dd d8+r]
$opcode_len_arrays['FSTP']['0'][] = array('0' => 'void',);
$opcode_len_result['FSTP']['0'][] = 2;  //dd d9]
$opcode_len_arrays['FSUB']['1'][] = array('0' => 'mem32',);
$opcode_len_result['FSUB']['1'][] = 2;  //d8 /4]
$opcode_len_arrays['FSUB']['1'][] = array('0' => 'mem64',);
$opcode_len_result['FSUB']['1'][] = 2;  //dc /4]
$opcode_len_arrays['FSUB']['1'][] = array('0' => 'fpureg|to',);
$opcode_len_result['FSUB']['1'][] = 2;  //dc e8+r]
$opcode_len_arrays['FSUB']['2'][] = array('0' => 'fpureg','1' => 'fpu0',);
$opcode_len_result['FSUB']['2'][] = 2;  //dc e8+r]
$opcode_len_arrays['FSUB']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FSUB']['1'][] = 2;  //d8 e0+r]
$opcode_len_arrays['FSUB']['2'][] = array('0' => 'fpu0','1' => 'fpureg',);
$opcode_len_result['FSUB']['2'][] = 2;  //d8 e0+r]
$opcode_len_arrays['FSUB']['0'][] = array('0' => 'void',);
$opcode_len_result['FSUB']['0'][] = 2;  //de e9]
$opcode_len_arrays['FSUBP']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FSUBP']['1'][] = 2;  //de e8+r]
$opcode_len_arrays['FSUBP']['2'][] = array('0' => 'fpureg','1' => 'fpu0',);
$opcode_len_result['FSUBP']['2'][] = 2;  //de e8+r]
$opcode_len_arrays['FSUBP']['0'][] = array('0' => 'void',);
$opcode_len_result['FSUBP']['0'][] = 2;  //de e9]
$opcode_len_arrays['FSUBR']['1'][] = array('0' => 'mem32',);
$opcode_len_result['FSUBR']['1'][] = 2;  //d8 /5]
$opcode_len_arrays['FSUBR']['1'][] = array('0' => 'mem64',);
$opcode_len_result['FSUBR']['1'][] = 2;  //dc /5]
$opcode_len_arrays['FSUBR']['1'][] = array('0' => 'fpureg|to',);
$opcode_len_result['FSUBR']['1'][] = 2;  //dc e0+r]
$opcode_len_arrays['FSUBR']['2'][] = array('0' => 'fpureg','1' => 'fpu0',);
$opcode_len_result['FSUBR']['2'][] = 2;  //dc e0+r]
$opcode_len_arrays['FSUBR']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FSUBR']['1'][] = 2;  //d8 e8+r]
$opcode_len_arrays['FSUBR']['2'][] = array('0' => 'fpu0','1' => 'fpureg',);
$opcode_len_result['FSUBR']['2'][] = 2;  //d8 e8+r]
$opcode_len_arrays['FSUBR']['0'][] = array('0' => 'void',);
$opcode_len_result['FSUBR']['0'][] = 2;  //de e1]
$opcode_len_arrays['FSUBRP']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FSUBRP']['1'][] = 2;  //de e0+r]
$opcode_len_arrays['FSUBRP']['2'][] = array('0' => 'fpureg','1' => 'fpu0',);
$opcode_len_result['FSUBRP']['2'][] = 2;  //de e0+r]
$opcode_len_arrays['FSUBRP']['0'][] = array('0' => 'void',);
$opcode_len_result['FSUBRP']['0'][] = 2;  //de e1]
$opcode_len_arrays['FTST']['0'][] = array('0' => 'void',);
$opcode_len_result['FTST']['0'][] = 2;  //d9 e4]
$opcode_len_arrays['FUCOM']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FUCOM']['1'][] = 2;  //dd e0+r]
$opcode_len_arrays['FUCOM']['2'][] = array('0' => 'fpu0','1' => 'fpureg',);
$opcode_len_result['FUCOM']['2'][] = 2;  //dd e0+r]
$opcode_len_arrays['FUCOM']['0'][] = array('0' => 'void',);
$opcode_len_result['FUCOM']['0'][] = 2;  //dd e1]
$opcode_len_arrays['FUCOMI']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FUCOMI']['1'][] = 2;  //db e8+r]
$opcode_len_arrays['FUCOMI']['2'][] = array('0' => 'fpu0','1' => 'fpureg',);
$opcode_len_result['FUCOMI']['2'][] = 2;  //db e8+r]
$opcode_len_arrays['FUCOMI']['0'][] = array('0' => 'void',);
$opcode_len_result['FUCOMI']['0'][] = 2;  //db e9]
$opcode_len_arrays['FUCOMIP']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FUCOMIP']['1'][] = 2;  //df e8+r]
$opcode_len_arrays['FUCOMIP']['2'][] = array('0' => 'fpu0','1' => 'fpureg',);
$opcode_len_result['FUCOMIP']['2'][] = 2;  //df e8+r]
$opcode_len_arrays['FUCOMIP']['0'][] = array('0' => 'void',);
$opcode_len_result['FUCOMIP']['0'][] = 2;  //df e9]
$opcode_len_arrays['FUCOMP']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FUCOMP']['1'][] = 2;  //dd e8+r]
$opcode_len_arrays['FUCOMP']['2'][] = array('0' => 'fpu0','1' => 'fpureg',);
$opcode_len_result['FUCOMP']['2'][] = 2;  //dd e8+r]
$opcode_len_arrays['FUCOMP']['0'][] = array('0' => 'void',);
$opcode_len_result['FUCOMP']['0'][] = 2;  //dd e9]
$opcode_len_arrays['FUCOMPP']['0'][] = array('0' => 'void',);
$opcode_len_result['FUCOMPP']['0'][] = 2;  //da e9]
$opcode_len_arrays['FXAM']['0'][] = array('0' => 'void',);
$opcode_len_result['FXAM']['0'][] = 2;  //d9 e5]
$opcode_len_arrays['FXCH']['1'][] = array('0' => 'fpureg',);
$opcode_len_result['FXCH']['1'][] = 2;  //d9 c8+r]
$opcode_len_arrays['FXCH']['2'][] = array('0' => 'fpureg','1' => 'fpu0',);
$opcode_len_result['FXCH']['2'][] = 2;  //d9 c8+r]
$opcode_len_arrays['FXCH']['2'][] = array('0' => 'fpu0','1' => 'fpureg',);
$opcode_len_result['FXCH']['2'][] = 2;  //d9 c8+r]
$opcode_len_arrays['FXCH']['0'][] = array('0' => 'void',);
$opcode_len_result['FXCH']['0'][] = 2;  //d9 c9]
$opcode_len_arrays['FXTRACT']['0'][] = array('0' => 'void',);
$opcode_len_result['FXTRACT']['0'][] = 2;  //d9 f4]
$opcode_len_arrays['FYL2X']['0'][] = array('0' => 'void',);
$opcode_len_result['FYL2X']['0'][] = 2;  //d9 f1]
$opcode_len_arrays['FYL2XP1']['0'][] = array('0' => 'void',);
$opcode_len_result['FYL2XP1']['0'][] = 2;  //d9 f9]
$opcode_len_arrays['HLT']['0'][] = array('0' => 'void',);
$opcode_len_result['HLT']['0'][] = 1;  //f4]
$opcode_len_arrays['IBTS']['2'][] = array('0' => 'mem','1' => 'reg16',);
$opcode_len_result['IBTS']['2'][] = 4;  //o16 0f a7 /r]
$opcode_len_arrays['IBTS']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['IBTS']['2'][] = 4;  //o16 0f a7 /r]
$opcode_len_arrays['IBTS']['2'][] = array('0' => 'mem','1' => 'reg32',);
$opcode_len_result['IBTS']['2'][] = 3;  //o32 0f a7 /r]
$opcode_len_arrays['IBTS']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['IBTS']['2'][] = 3;  //o32 0f a7 /r]
$opcode_len_arrays['ICEBP']['0'][] = array('0' => 'void',);
$opcode_len_result['ICEBP']['0'][] = 1;  //f1]
$opcode_len_arrays['IDIV']['1'][] = array('0' => 'rm8',);
$opcode_len_result['IDIV']['1'][] = 2;  //f6 /7]
$opcode_len_arrays['IDIV']['1'][] = array('0' => 'rm16',);
$opcode_len_result['IDIV']['1'][] = 3;  //o16 f7 /7]
$opcode_len_arrays['IDIV']['1'][] = array('0' => 'rm32',);
$opcode_len_result['IDIV']['1'][] = 2;  //o32 f7 /7]
$opcode_len_arrays['IMUL']['1'][] = array('0' => 'rm8',);
$opcode_len_result['IMUL']['1'][] = 2;  //f6 /5]
$opcode_len_arrays['IMUL']['1'][] = array('0' => 'rm16',);
$opcode_len_result['IMUL']['1'][] = 3;  //o16 f7 /5]
$opcode_len_arrays['IMUL']['1'][] = array('0' => 'rm32',);
$opcode_len_result['IMUL']['1'][] = 2;  //o32 f7 /5]
$opcode_len_arrays['IMUL']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['IMUL']['2'][] = 4;  //o16 0f af /r]
$opcode_len_arrays['IMUL']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['IMUL']['2'][] = 4;  //o16 0f af /r]
$opcode_len_arrays['IMUL']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['IMUL']['2'][] = 3;  //o32 0f af /r]
$opcode_len_arrays['IMUL']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['IMUL']['2'][] = 3;  //o32 0f af /r]
$opcode_len_arrays['IMUL']['3'][] = array('0' => 'reg16','1' => 'mem','2' => 'imm8',);
$opcode_len_result['IMUL']['3'][] = 4;  //o16 6b /r ib,s]
$opcode_len_arrays['IMUL']['3'][] = array('0' => 'reg16','1' => 'mem','2' => 'sbyteword',);
$opcode_len_result['IMUL']['3'][] = 4;  //o16 6b /r ib,s]
$opcode_len_arrays['IMUL']['3'][] = array('0' => 'reg16','1' => 'mem','2' => 'imm16',);
$opcode_len_result['IMUL']['3'][] = 5;  //o16 69 /r iw]
$opcode_len_arrays['IMUL']['3'][] = array('0' => 'reg16','1' => 'mem','2' => 'imm',);
$opcode_len_result['IMUL']['3'][] = 5;  //o16 69 /r iw]
$opcode_len_arrays['IMUL']['3'][] = array('0' => 'reg16','1' => 'reg16','2' => 'imm8',);
$opcode_len_result['IMUL']['3'][] = 4;  //o16 6b /r ib,s]
$opcode_len_arrays['IMUL']['3'][] = array('0' => 'reg16','1' => 'reg16','2' => 'sbyteword',);
$opcode_len_result['IMUL']['3'][] = 4;  //o16 6b /r ib,s]
$opcode_len_arrays['IMUL']['3'][] = array('0' => 'reg16','1' => 'reg16','2' => 'imm16',);
$opcode_len_result['IMUL']['3'][] = 5;  //o16 69 /r iw]
$opcode_len_arrays['IMUL']['3'][] = array('0' => 'reg16','1' => 'reg16','2' => 'imm',);
$opcode_len_result['IMUL']['3'][] = 5;  //o16 69 /r iw]
$opcode_len_arrays['IMUL']['3'][] = array('0' => 'reg32','1' => 'mem','2' => 'imm8',);
$opcode_len_result['IMUL']['3'][] = 3;  //o32 6b /r ib,s]
$opcode_len_arrays['IMUL']['3'][] = array('0' => 'reg32','1' => 'mem','2' => 'sbytedword',);
$opcode_len_result['IMUL']['3'][] = 3;  //o32 6b /r ib,s]
$opcode_len_arrays['IMUL']['3'][] = array('0' => 'reg32','1' => 'mem','2' => 'imm32',);
$opcode_len_result['IMUL']['3'][] = 6;  //o32 69 /r id]
$opcode_len_arrays['IMUL']['3'][] = array('0' => 'reg32','1' => 'mem','2' => 'imm',);
$opcode_len_result['IMUL']['3'][] = 6;  //o32 69 /r id]
$opcode_len_arrays['IMUL']['3'][] = array('0' => 'reg32','1' => 'reg32','2' => 'imm8',);
$opcode_len_result['IMUL']['3'][] = 3;  //o32 6b /r ib,s]
$opcode_len_arrays['IMUL']['3'][] = array('0' => 'reg32','1' => 'reg32','2' => 'sbytedword',);
$opcode_len_result['IMUL']['3'][] = 3;  //o32 6b /r ib,s]
$opcode_len_arrays['IMUL']['3'][] = array('0' => 'reg32','1' => 'reg32','2' => 'imm32',);
$opcode_len_result['IMUL']['3'][] = 6;  //o32 69 /r id]
$opcode_len_arrays['IMUL']['3'][] = array('0' => 'reg32','1' => 'reg32','2' => 'imm',);
$opcode_len_result['IMUL']['3'][] = 6;  //o32 69 /r id]
$opcode_len_arrays['IMUL']['2'][] = array('0' => 'reg16','1' => 'imm8',);
$opcode_len_result['IMUL']['2'][] = 4;  //o16 6b /r ib,s]
$opcode_len_arrays['IMUL']['2'][] = array('0' => 'reg16','1' => 'sbyteword',);
$opcode_len_result['IMUL']['2'][] = 4;  //o16 6b /r ib,s]
$opcode_len_arrays['IMUL']['2'][] = array('0' => 'reg16','1' => 'imm16',);
$opcode_len_result['IMUL']['2'][] = 5;  //o16 69 /r iw]
$opcode_len_arrays['IMUL']['2'][] = array('0' => 'reg16','1' => 'imm',);
$opcode_len_result['IMUL']['2'][] = 5;  //o16 69 /r iw]
$opcode_len_arrays['IMUL']['2'][] = array('0' => 'reg32','1' => 'imm8',);
$opcode_len_result['IMUL']['2'][] = 3;  //o32 6b /r ib,s]
$opcode_len_arrays['IMUL']['2'][] = array('0' => 'reg32','1' => 'sbytedword',);
$opcode_len_result['IMUL']['2'][] = 3;  //o32 6b /r ib,s]
$opcode_len_arrays['IMUL']['2'][] = array('0' => 'reg32','1' => 'imm32',);
$opcode_len_result['IMUL']['2'][] = 6;  //o32 69 /r id]
$opcode_len_arrays['IMUL']['2'][] = array('0' => 'reg32','1' => 'imm',);
$opcode_len_result['IMUL']['2'][] = 6;  //o32 69 /r id]
$opcode_len_arrays['IN']['2'][] = array('0' => 'reg_al','1' => 'imm',);
$opcode_len_result['IN']['2'][] = 2;  //e4 ib,u]
$opcode_len_arrays['IN']['2'][] = array('0' => 'reg_ax','1' => 'imm',);
$opcode_len_result['IN']['2'][] = 3;  //o16 e5 ib,u]
$opcode_len_arrays['IN']['2'][] = array('0' => 'reg_eax','1' => 'imm',);
$opcode_len_result['IN']['2'][] = 2;  //o32 e5 ib,u]
$opcode_len_arrays['IN']['2'][] = array('0' => 'reg_al','1' => 'reg_dx',);
$opcode_len_result['IN']['2'][] = 1;  //ec]
$opcode_len_arrays['IN']['2'][] = array('0' => 'reg_ax','1' => 'reg_dx',);
$opcode_len_result['IN']['2'][] = 2;  //o16 ed]
$opcode_len_arrays['IN']['2'][] = array('0' => 'reg_eax','1' => 'reg_dx',);
$opcode_len_result['IN']['2'][] = 1;  //o32 ed]
$opcode_len_arrays['INC']['1'][] = array('0' => 'reg16',);
$opcode_len_result['INC']['1'][] = 2;  //o16 40+r]
$opcode_len_arrays['INC']['1'][] = array('0' => 'reg32',);
$opcode_len_result['INC']['1'][] = 1;  //o32 40+r]
$opcode_len_arrays['INC']['1'][] = array('0' => 'rm8',);
$opcode_len_result['INC']['1'][] = 2;  //hle fe /0]
$opcode_len_arrays['INC']['1'][] = array('0' => 'rm16',);
$opcode_len_result['INC']['1'][] = 3;  //hle o16 ff /0]
$opcode_len_arrays['INC']['1'][] = array('0' => 'rm32',);
$opcode_len_result['INC']['1'][] = 2;  //hle o32 ff /0]
$opcode_len_arrays['INSB']['0'][] = array('0' => 'void',);
$opcode_len_result['INSB']['0'][] = 1;  //6c]
$opcode_len_arrays['INSD']['0'][] = array('0' => 'void',);
$opcode_len_result['INSD']['0'][] = 1;  //o32 6d]
$opcode_len_arrays['INSW']['0'][] = array('0' => 'void',);
$opcode_len_result['INSW']['0'][] = 2;  //o16 6d]
$opcode_len_arrays['INT']['1'][] = array('0' => 'imm',);
$opcode_len_result['INT']['1'][] = 2;  //cd ib,u]
$opcode_len_arrays['INT01']['0'][] = array('0' => 'void',);
$opcode_len_result['INT01']['0'][] = 1;  //f1]
$opcode_len_arrays['INT1']['0'][] = array('0' => 'void',);
$opcode_len_result['INT1']['0'][] = 1;  //f1]
$opcode_len_arrays['INT03']['0'][] = array('0' => 'void',);
$opcode_len_result['INT03']['0'][] = 1;  //cc]
$opcode_len_arrays['INT3']['0'][] = array('0' => 'void',);
$opcode_len_result['INT3']['0'][] = 1;  //cc]
$opcode_len_arrays['INTO']['0'][] = array('0' => 'void',);
$opcode_len_result['INTO']['0'][] = 1;  //ce]
$opcode_len_arrays['INVD']['0'][] = array('0' => 'void',);
$opcode_len_result['INVD']['0'][] = 2;  //0f 08]
$opcode_len_arrays['INVPCID']['2'][] = array('0' => 'reg32','1' => 'mem128',);
$opcode_len_result['INVPCID']['2'][] = 5;  //66 0f 38 82 /r]
$opcode_len_arrays['INVPCID']['2'][] = array('0' => 'reg64','1' => 'mem128',);
$opcode_len_result['INVPCID']['2'][] = 5;  //66 0f 38 82 /r]
$opcode_len_arrays['INVLPG']['1'][] = array('0' => 'mem',);
$opcode_len_result['INVLPG']['1'][] = 3;  //0f 01 /7]
$opcode_len_arrays['INVLPGA']['0'][] = array('0' => 'void',);
$opcode_len_result['INVLPGA']['0'][] = 3;  //0f 01 df]
$opcode_len_arrays['IRET']['0'][] = array('0' => 'void',);
$opcode_len_result['IRET']['0'][] = 1;  //odf cf]
$opcode_len_arrays['IRETD']['0'][] = array('0' => 'void',);
$opcode_len_result['IRETD']['0'][] = 1;  //o32 cf]
$opcode_len_arrays['IRETW']['0'][] = array('0' => 'void',);
$opcode_len_result['IRETW']['0'][] = 2;  //o16 cf]
$opcode_len_arrays['JMP']['1'][] = array('0' => 'imm|far',);
$opcode_len_result['JMP']['1'][] = 7;  //odf ea iwd seg]
$opcode_len_arrays['JMP']['1'][] = array('0' => 'imm16|far',);
$opcode_len_result['JMP']['1'][] = 8;  //o16 ea iwd seg]
$opcode_len_arrays['JMP']['1'][] = array('0' => 'imm32|far',);
$opcode_len_result['JMP']['1'][] = 7;  //o32 ea iwd seg]
$opcode_len_arrays['JMP']['1'][] = array('0' => 'imm:imm',);
$opcode_len_result['JMP']['1'][] = 7;  //odf ea iwd iw]
$opcode_len_arrays['JMP']['1'][] = array('0' => 'imm16:imm',);
$opcode_len_result['JMP']['1'][] = 6;  //o16 ea iw iw]
$opcode_len_arrays['JMP']['1'][] = array('0' => 'imm:imm16',);
$opcode_len_result['JMP']['1'][] = 6;  //o16 ea iw iw]
$opcode_len_arrays['JMP']['1'][] = array('0' => 'imm32:imm',);
$opcode_len_result['JMP']['1'][] = 7;  //o32 ea id iw]
$opcode_len_arrays['JMP']['1'][] = array('0' => 'imm:imm32',);
$opcode_len_result['JMP']['1'][] = 7;  //o32 ea id iw]
$opcode_len_arrays['JMP']['1'][] = array('0' => 'mem|far',);
$opcode_len_result['JMP']['1'][] = 2;  //odf ff /5]
$opcode_len_arrays['JMP']['1'][] = array('0' => 'mem16|far',);
$opcode_len_result['JMP']['1'][] = 3;  //o16 ff /5]
$opcode_len_arrays['JMP']['1'][] = array('0' => 'mem32|far',);
$opcode_len_result['JMP']['1'][] = 2;  //o32 ff /5]
$opcode_len_arrays['JMP']['1'][] = array('0' => 'mem|near',);
$opcode_len_result['JMP']['1'][] = 2;  //odf ff /4]
$opcode_len_arrays['JMP']['1'][] = array('0' => 'rm16|near',);
$opcode_len_result['JMP']['1'][] = 3;  //o16 ff /4]
$opcode_len_arrays['JMP']['1'][] = array('0' => 'rm32|near',);
$opcode_len_result['JMP']['1'][] = 2;  //o32 ff /4]
$opcode_len_arrays['JMP']['1'][] = array('0' => 'mem',);
$opcode_len_result['JMP']['1'][] = 2;  //odf ff /4]
$opcode_len_arrays['JMP']['1'][] = array('0' => 'rm16',);
$opcode_len_result['JMP']['1'][] = 3;  //o16 ff /4]
$opcode_len_arrays['JMP']['1'][] = array('0' => 'rm32',);
$opcode_len_result['JMP']['1'][] = 2;  //o32 ff /4]
$opcode_len_arrays['JMPE']['1'][] = array('0' => 'rm16',);
$opcode_len_result['JMPE']['1'][] = 4;  //o16 0f 00 /6]
$opcode_len_arrays['JMPE']['1'][] = array('0' => 'rm32',);
$opcode_len_result['JMPE']['1'][] = 3;  //o32 0f 00 /6]
$opcode_len_arrays['LAHF']['0'][] = array('0' => 'void',);
$opcode_len_result['LAHF']['0'][] = 1;  //9f]
$opcode_len_arrays['LAR']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['LAR']['2'][] = 4;  //o16 0f 02 /r]
$opcode_len_arrays['LAR']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['LAR']['2'][] = 4;  //o16 0f 02 /r]
$opcode_len_arrays['LAR']['2'][] = array('0' => 'reg16','1' => 'reg32',);
$opcode_len_result['LAR']['2'][] = 4;  //o16 0f 02 /r]
$opcode_len_arrays['LAR']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['LAR']['2'][] = 3;  //o32 0f 02 /r]
$opcode_len_arrays['LAR']['2'][] = array('0' => 'reg32','1' => 'reg16',);
$opcode_len_result['LAR']['2'][] = 3;  //o32 0f 02 /r]
$opcode_len_arrays['LAR']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['LAR']['2'][] = 3;  //o32 0f 02 /r]
$opcode_len_arrays['LDS']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['LDS']['2'][] = 3;  //o16 c5 /r]
$opcode_len_arrays['LDS']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['LDS']['2'][] = 2;  //o32 c5 /r]
$opcode_len_arrays['LEA']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['LEA']['2'][] = 3;  //o16 8d /r]
$opcode_len_arrays['LEA']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['LEA']['2'][] = 2;  //o32 8d /r]
$opcode_len_arrays['LEAVE']['0'][] = array('0' => 'void',);
$opcode_len_result['LEAVE']['0'][] = 1;  //c9]
$opcode_len_arrays['LES']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['LES']['2'][] = 3;  //o16 c4 /r]
$opcode_len_arrays['LES']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['LES']['2'][] = 2;  //o32 c4 /r]
$opcode_len_arrays['LFS']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['LFS']['2'][] = 4;  //o16 0f b4 /r]
$opcode_len_arrays['LFS']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['LFS']['2'][] = 3;  //o32 0f b4 /r]
$opcode_len_arrays['LGDT']['1'][] = array('0' => 'mem',);
$opcode_len_result['LGDT']['1'][] = 3;  //0f 01 /2]
$opcode_len_arrays['LGS']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['LGS']['2'][] = 4;  //o16 0f b5 /r]
$opcode_len_arrays['LGS']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['LGS']['2'][] = 3;  //o32 0f b5 /r]
$opcode_len_arrays['LIDT']['1'][] = array('0' => 'mem',);
$opcode_len_result['LIDT']['1'][] = 3;  //0f 01 /3]
$opcode_len_arrays['LLDT']['1'][] = array('0' => 'mem',);
$opcode_len_result['LLDT']['1'][] = 3;  //0f 00 /2]
$opcode_len_arrays['LLDT']['1'][] = array('0' => 'mem16',);
$opcode_len_result['LLDT']['1'][] = 3;  //0f 00 /2]
$opcode_len_arrays['LLDT']['1'][] = array('0' => 'reg16',);
$opcode_len_result['LLDT']['1'][] = 3;  //0f 00 /2]
$opcode_len_arrays['LMSW']['1'][] = array('0' => 'mem',);
$opcode_len_result['LMSW']['1'][] = 3;  //0f 01 /6]
$opcode_len_arrays['LMSW']['1'][] = array('0' => 'mem16',);
$opcode_len_result['LMSW']['1'][] = 3;  //0f 01 /6]
$opcode_len_arrays['LMSW']['1'][] = array('0' => 'reg16',);
$opcode_len_result['LMSW']['1'][] = 3;  //0f 01 /6]
$opcode_len_arrays['LOADALL']['0'][] = array('0' => 'void',);
$opcode_len_result['LOADALL']['0'][] = 2;  //0f 07]
$opcode_len_arrays['LOADALL286']['0'][] = array('0' => 'void',);
$opcode_len_result['LOADALL286']['0'][] = 2;  //0f 05]
$opcode_len_arrays['LODSB']['0'][] = array('0' => 'void',);
$opcode_len_result['LODSB']['0'][] = 1;  //ac]
$opcode_len_arrays['LODSD']['0'][] = array('0' => 'void',);
$opcode_len_result['LODSD']['0'][] = 1;  //o32 ad]
$opcode_len_arrays['LODSW']['0'][] = array('0' => 'void',);
$opcode_len_result['LODSW']['0'][] = 2;  //o16 ad]
$opcode_len_arrays['LSL']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['LSL']['2'][] = 4;  //o16 0f 03 /r]
$opcode_len_arrays['LSL']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['LSL']['2'][] = 4;  //o16 0f 03 /r]
$opcode_len_arrays['LSL']['2'][] = array('0' => 'reg16','1' => 'reg32',);
$opcode_len_result['LSL']['2'][] = 4;  //o16 0f 03 /r]
$opcode_len_arrays['LSL']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['LSL']['2'][] = 3;  //o32 0f 03 /r]
$opcode_len_arrays['LSL']['2'][] = array('0' => 'reg32','1' => 'reg16',);
$opcode_len_result['LSL']['2'][] = 3;  //o32 0f 03 /r]
$opcode_len_arrays['LSL']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['LSL']['2'][] = 3;  //o32 0f 03 /r]
$opcode_len_arrays['LSS']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['LSS']['2'][] = 4;  //o16 0f b2 /r]
$opcode_len_arrays['LSS']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['LSS']['2'][] = 3;  //o32 0f b2 /r]
$opcode_len_arrays['LTR']['1'][] = array('0' => 'mem',);
$opcode_len_result['LTR']['1'][] = 3;  //0f 00 /3]
$opcode_len_arrays['LTR']['1'][] = array('0' => 'mem16',);
$opcode_len_result['LTR']['1'][] = 3;  //0f 00 /3]
$opcode_len_arrays['LTR']['1'][] = array('0' => 'reg16',);
$opcode_len_result['LTR']['1'][] = 3;  //0f 00 /3]
$opcode_len_arrays['MONITOR']['0'][] = array('0' => 'void',);
$opcode_len_result['MONITOR']['0'][] = 3;  //0f 01 c8]
$opcode_len_arrays['MONITOR']['3'][] = array('0' => 'reg_eax','1' => 'reg_ecx','2' => 'reg_edx',);
$opcode_len_result['MONITOR']['3'][] = 3;  //0f 01 c8]
$opcode_len_arrays['MONITOR']['3'][] = array('0' => 'reg_rax','1' => 'reg_ecx','2' => 'reg_edx',);
$opcode_len_result['MONITOR']['3'][] = 3;  //0f 01 c8]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'mem','1' => 'reg_sreg',);
$opcode_len_result['MOV']['2'][] = 2;  //8c /r]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg16','1' => 'reg_sreg',);
$opcode_len_result['MOV']['2'][] = 3;  //o16 8c /r]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg32','1' => 'reg_sreg',);
$opcode_len_result['MOV']['2'][] = 2;  //o32 8c /r]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg_sreg','1' => 'mem',);
$opcode_len_result['MOV']['2'][] = 2;  //8e /r]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg_sreg','1' => 'reg16',);
$opcode_len_result['MOV']['2'][] = 2;  //8e /r]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg_sreg','1' => 'reg32',);
$opcode_len_result['MOV']['2'][] = 2;  //8e /r]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg_sreg','1' => 'reg16',);
$opcode_len_result['MOV']['2'][] = 3;  //o16 8e /r]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg_sreg','1' => 'reg32',);
$opcode_len_result['MOV']['2'][] = 2;  //o32 8e /r]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg_al','1' => 'mem_offs',);
$opcode_len_result['MOV']['2'][] = 5;  //a0 iwdq]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg_ax','1' => 'mem_offs',);
$opcode_len_result['MOV']['2'][] = 6;  //o16 a1 iwdq]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg_eax','1' => 'mem_offs',);
$opcode_len_result['MOV']['2'][] = 5;  //o32 a1 iwdq]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'mem_offs','1' => 'reg_al',);
$opcode_len_result['MOV']['2'][] = 5;  //a2 iwdq]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'mem_offs','1' => 'reg_ax',);
$opcode_len_result['MOV']['2'][] = 6;  //o16 a3 iwdq]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'mem_offs','1' => 'reg_eax',);
$opcode_len_result['MOV']['2'][] = 5;  //o32 a3 iwdq]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg32','1' => 'reg_dreg',);
$opcode_len_result['MOV']['2'][] = 3;  //0f 21 /r]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg_dreg','1' => 'reg32',);
$opcode_len_result['MOV']['2'][] = 3;  //0f 23 /r]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg32','1' => 'reg_treg',);
$opcode_len_result['MOV']['2'][] = 3;  //0f 24 /r]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg_treg','1' => 'reg32',);
$opcode_len_result['MOV']['2'][] = 3;  //0f 26 /r]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'mem','1' => 'reg8',);
$opcode_len_result['MOV']['2'][] = 2;  //hlexr 88 /r]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg8','1' => 'reg8',);
$opcode_len_result['MOV']['2'][] = 2;  //88 /r]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'mem','1' => 'reg16',);
$opcode_len_result['MOV']['2'][] = 3;  //hlexr o16 89 /r]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['MOV']['2'][] = 3;  //o16 89 /r]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'mem','1' => 'reg32',);
$opcode_len_result['MOV']['2'][] = 2;  //hlexr o32 89 /r]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['MOV']['2'][] = 2;  //o32 89 /r]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg8','1' => 'mem',);
$opcode_len_result['MOV']['2'][] = 2;  //8a /r]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg8','1' => 'reg8',);
$opcode_len_result['MOV']['2'][] = 2;  //8a /r]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['MOV']['2'][] = 3;  //o16 8b /r]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['MOV']['2'][] = 3;  //o16 8b /r]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['MOV']['2'][] = 2;  //o32 8b /r]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['MOV']['2'][] = 2;  //o32 8b /r]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg8','1' => 'imm',);
$opcode_len_result['MOV']['2'][] = 2;  //b0+r ib]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg16','1' => 'imm',);
$opcode_len_result['MOV']['2'][] = 4;  //o16 b8+r iw]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'reg32','1' => 'imm',);
$opcode_len_result['MOV']['2'][] = 5;  //o32 b8+r id]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'rm8','1' => 'imm',);
$opcode_len_result['MOV']['2'][] = 3;  //hlexr c6 /0 ib]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'rm16','1' => 'imm',);
$opcode_len_result['MOV']['2'][] = 5;  //hlexr o16 c7 /0 iw]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'rm32','1' => 'imm',);
$opcode_len_result['MOV']['2'][] = 6;  //hlexr o32 c7 /0 id]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'mem','1' => 'imm8',);
$opcode_len_result['MOV']['2'][] = 3;  //hlexr c6 /0 ib]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'mem','1' => 'imm16',);
$opcode_len_result['MOV']['2'][] = 5;  //hlexr o16 c7 /0 iw]
$opcode_len_arrays['MOV']['2'][] = array('0' => 'mem','1' => 'imm32',);
$opcode_len_result['MOV']['2'][] = 6;  //hlexr o32 c7 /0 id]
$opcode_len_arrays['MOVSB']['0'][] = array('0' => 'void',);
$opcode_len_result['MOVSB']['0'][] = 1;  //a4]
$opcode_len_arrays['MOVSD']['0'][] = array('0' => 'void',);
$opcode_len_result['MOVSD']['0'][] = 1;  //o32 a5]
$opcode_len_arrays['MOVSW']['0'][] = array('0' => 'void',);
$opcode_len_result['MOVSW']['0'][] = 2;  //o16 a5]
$opcode_len_arrays['MOVSX']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['MOVSX']['2'][] = 4;  //o16 0f be /r]
$opcode_len_arrays['MOVSX']['2'][] = array('0' => 'reg16','1' => 'reg8',);
$opcode_len_result['MOVSX']['2'][] = 4;  //o16 0f be /r]
$opcode_len_arrays['MOVSX']['2'][] = array('0' => 'reg32','1' => 'rm8',);
$opcode_len_result['MOVSX']['2'][] = 3;  //o32 0f be /r]
$opcode_len_arrays['MOVSX']['2'][] = array('0' => 'reg32','1' => 'rm16',);
$opcode_len_result['MOVSX']['2'][] = 3;  //o32 0f bf /r]
$opcode_len_arrays['MOVZX']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['MOVZX']['2'][] = 4;  //o16 0f b6 /r]
$opcode_len_arrays['MOVZX']['2'][] = array('0' => 'reg16','1' => 'reg8',);
$opcode_len_result['MOVZX']['2'][] = 4;  //o16 0f b6 /r]
$opcode_len_arrays['MOVZX']['2'][] = array('0' => 'reg32','1' => 'rm8',);
$opcode_len_result['MOVZX']['2'][] = 3;  //o32 0f b6 /r]
$opcode_len_arrays['MOVZX']['2'][] = array('0' => 'reg32','1' => 'rm16',);
$opcode_len_result['MOVZX']['2'][] = 3;  //o32 0f b7 /r]
$opcode_len_arrays['MUL']['1'][] = array('0' => 'rm8',);
$opcode_len_result['MUL']['1'][] = 2;  //f6 /4]
$opcode_len_arrays['MUL']['1'][] = array('0' => 'rm16',);
$opcode_len_result['MUL']['1'][] = 3;  //o16 f7 /4]
$opcode_len_arrays['MUL']['1'][] = array('0' => 'rm32',);
$opcode_len_result['MUL']['1'][] = 2;  //o32 f7 /4]
$opcode_len_arrays['MWAIT']['0'][] = array('0' => 'void',);
$opcode_len_result['MWAIT']['0'][] = 3;  //0f 01 c9]
$opcode_len_arrays['MWAIT']['2'][] = array('0' => 'reg_eax','1' => 'reg_ecx',);
$opcode_len_result['MWAIT']['2'][] = 3;  //0f 01 c9]
$opcode_len_arrays['NEG']['1'][] = array('0' => 'rm8',);
$opcode_len_result['NEG']['1'][] = 2;  //hle f6 /3]
$opcode_len_arrays['NEG']['1'][] = array('0' => 'rm16',);
$opcode_len_result['NEG']['1'][] = 3;  //hle o16 f7 /3]
$opcode_len_arrays['NEG']['1'][] = array('0' => 'rm32',);
$opcode_len_result['NEG']['1'][] = 2;  //hle o32 f7 /3]
$opcode_len_arrays['NOP']['0'][] = array('0' => 'void',);
$opcode_len_result['NOP']['0'][] = 1;  //norexb nof3 90]
$opcode_len_arrays['NOP']['1'][] = array('0' => 'rm16',);
$opcode_len_result['NOP']['1'][] = 4;  //o16 0f 1f /0]
$opcode_len_arrays['NOP']['1'][] = array('0' => 'rm32',);
$opcode_len_result['NOP']['1'][] = 3;  //o32 0f 1f /0]
$opcode_len_arrays['NOT']['1'][] = array('0' => 'rm8',);
$opcode_len_result['NOT']['1'][] = 2;  //hle f6 /2]
$opcode_len_arrays['NOT']['1'][] = array('0' => 'rm16',);
$opcode_len_result['NOT']['1'][] = 3;  //hle o16 f7 /2]
$opcode_len_arrays['NOT']['1'][] = array('0' => 'rm32',);
$opcode_len_result['NOT']['1'][] = 2;  //hle o32 f7 /2]
$opcode_len_arrays['OR']['2'][] = array('0' => 'mem','1' => 'reg8',);
$opcode_len_result['OR']['2'][] = 2;  //hle 08 /r]
$opcode_len_arrays['OR']['2'][] = array('0' => 'reg8','1' => 'reg8',);
$opcode_len_result['OR']['2'][] = 2;  //08 /r]
$opcode_len_arrays['OR']['2'][] = array('0' => 'mem','1' => 'reg16',);
$opcode_len_result['OR']['2'][] = 3;  //hle o16 09 /r]
$opcode_len_arrays['OR']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['OR']['2'][] = 3;  //o16 09 /r]
$opcode_len_arrays['OR']['2'][] = array('0' => 'mem','1' => 'reg32',);
$opcode_len_result['OR']['2'][] = 2;  //hle o32 09 /r]
$opcode_len_arrays['OR']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['OR']['2'][] = 2;  //o32 09 /r]
$opcode_len_arrays['OR']['2'][] = array('0' => 'reg8','1' => 'mem',);
$opcode_len_result['OR']['2'][] = 2;  //0a /r]
$opcode_len_arrays['OR']['2'][] = array('0' => 'reg8','1' => 'reg8',);
$opcode_len_result['OR']['2'][] = 2;  //0a /r]
$opcode_len_arrays['OR']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['OR']['2'][] = 3;  //o16 0b /r]
$opcode_len_arrays['OR']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['OR']['2'][] = 3;  //o16 0b /r]
$opcode_len_arrays['OR']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['OR']['2'][] = 2;  //o32 0b /r]
$opcode_len_arrays['OR']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['OR']['2'][] = 2;  //o32 0b /r]
$opcode_len_arrays['OR']['2'][] = array('0' => 'rm16','1' => 'imm8',);
$opcode_len_result['OR']['2'][] = 4;  //hle o16 83 /1 ib,s]
$opcode_len_arrays['OR']['2'][] = array('0' => 'rm32','1' => 'imm8',);
$opcode_len_result['OR']['2'][] = 3;  //hle o32 83 /1 ib,s]
$opcode_len_arrays['OR']['2'][] = array('0' => 'reg_al','1' => 'imm',);
$opcode_len_result['OR']['2'][] = 2;  //0c ib]
$opcode_len_arrays['OR']['2'][] = array('0' => 'reg_ax','1' => 'sbyteword',);
$opcode_len_result['OR']['2'][] = 4;  //o16 83 /1 ib,s]
$opcode_len_arrays['OR']['2'][] = array('0' => 'reg_ax','1' => 'imm',);
$opcode_len_result['OR']['2'][] = 4;  //o16 0d iw]
$opcode_len_arrays['OR']['2'][] = array('0' => 'reg_eax','1' => 'sbytedword',);
$opcode_len_result['OR']['2'][] = 3;  //o32 83 /1 ib,s]
$opcode_len_arrays['OR']['2'][] = array('0' => 'reg_eax','1' => 'imm',);
$opcode_len_result['OR']['2'][] = 5;  //o32 0d id]
$opcode_len_arrays['OR']['2'][] = array('0' => 'rm8','1' => 'imm',);
$opcode_len_result['OR']['2'][] = 3;  //hle 80 /1 ib]
$opcode_len_arrays['OR']['2'][] = array('0' => 'rm16','1' => 'sbyteword',);
$opcode_len_result['OR']['2'][] = 4;  //hle o16 83 /1 ib,s]
$opcode_len_arrays['OR']['2'][] = array('0' => 'rm16','1' => 'imm',);
$opcode_len_result['OR']['2'][] = 5;  //hle o16 81 /1 iw]
$opcode_len_arrays['OR']['2'][] = array('0' => 'rm32','1' => 'sbytedword',);
$opcode_len_result['OR']['2'][] = 3;  //hle o32 83 /1 ib,s]
$opcode_len_arrays['OR']['2'][] = array('0' => 'rm32','1' => 'imm',);
$opcode_len_result['OR']['2'][] = 6;  //hle o32 81 /1 id]
$opcode_len_arrays['OR']['2'][] = array('0' => 'mem','1' => 'imm8',);
$opcode_len_result['OR']['2'][] = 3;  //hle 80 /1 ib]
$opcode_len_arrays['OR']['2'][] = array('0' => 'mem','1' => 'sbyteword16',);
$opcode_len_result['OR']['2'][] = 4;  //hle o16 83 /1 ib,s]
$opcode_len_arrays['OR']['2'][] = array('0' => 'mem','1' => 'imm16',);
$opcode_len_result['OR']['2'][] = 5;  //hle o16 81 /1 iw]
$opcode_len_arrays['OR']['2'][] = array('0' => 'mem','1' => 'sbytedword32',);
$opcode_len_result['OR']['2'][] = 3;  //hle o32 83 /1 ib,s]
$opcode_len_arrays['OR']['2'][] = array('0' => 'mem','1' => 'imm32',);
$opcode_len_result['OR']['2'][] = 6;  //hle o32 81 /1 id]
$opcode_len_arrays['OR']['2'][] = array('0' => 'rm8','1' => 'imm',);
$opcode_len_result['OR']['2'][] = 3;  //hle 82 /1 ib]
$opcode_len_arrays['OUT']['2'][] = array('0' => 'imm','1' => 'reg_al',);
$opcode_len_result['OUT']['2'][] = 2;  //e6 ib,u]
$opcode_len_arrays['OUT']['2'][] = array('0' => 'imm','1' => 'reg_ax',);
$opcode_len_result['OUT']['2'][] = 3;  //o16 e7 ib,u]
$opcode_len_arrays['OUT']['2'][] = array('0' => 'imm','1' => 'reg_eax',);
$opcode_len_result['OUT']['2'][] = 2;  //o32 e7 ib,u]
$opcode_len_arrays['OUT']['2'][] = array('0' => 'reg_dx','1' => 'reg_al',);
$opcode_len_result['OUT']['2'][] = 1;  //ee]
$opcode_len_arrays['OUT']['2'][] = array('0' => 'reg_dx','1' => 'reg_ax',);
$opcode_len_result['OUT']['2'][] = 2;  //o16 ef]
$opcode_len_arrays['OUT']['2'][] = array('0' => 'reg_dx','1' => 'reg_eax',);
$opcode_len_result['OUT']['2'][] = 1;  //o32 ef]
$opcode_len_arrays['OUTSB']['0'][] = array('0' => 'void',);
$opcode_len_result['OUTSB']['0'][] = 1;  //6e]
$opcode_len_arrays['OUTSD']['0'][] = array('0' => 'void',);
$opcode_len_result['OUTSD']['0'][] = 1;  //o32 6f]
$opcode_len_arrays['OUTSW']['0'][] = array('0' => 'void',);
$opcode_len_result['OUTSW']['0'][] = 2;  //o16 6f]
$opcode_len_arrays['PAUSE']['0'][] = array('0' => 'void',);
$opcode_len_result['PAUSE']['0'][] = 1;  //f3i 90]
$opcode_len_arrays['PDISTIB']['2'][] = array('0' => 'mmxreg','1' => 'mem',);
$opcode_len_result['PDISTIB']['2'][] = 3;  //0f 54 /r]
$opcode_len_arrays['PMACHRIW']['2'][] = array('0' => 'mmxreg','1' => 'mem',);
$opcode_len_result['PMACHRIW']['2'][] = 3;  //0f 5e /r]
$opcode_len_arrays['PMVGEZB']['2'][] = array('0' => 'mmxreg','1' => 'mem',);
$opcode_len_result['PMVGEZB']['2'][] = 3;  //0f 5c /r]
$opcode_len_arrays['PMVLZB']['2'][] = array('0' => 'mmxreg','1' => 'mem',);
$opcode_len_result['PMVLZB']['2'][] = 3;  //0f 5b /r]
$opcode_len_arrays['PMVNZB']['2'][] = array('0' => 'mmxreg','1' => 'mem',);
$opcode_len_result['PMVNZB']['2'][] = 3;  //0f 5a /r]
$opcode_len_arrays['PMVZB']['2'][] = array('0' => 'mmxreg','1' => 'mem',);
$opcode_len_result['PMVZB']['2'][] = 3;  //0f 58 /r]
$opcode_len_arrays['POP']['1'][] = array('0' => 'reg16',);
$opcode_len_result['POP']['1'][] = 2;  //o16 58+r]
$opcode_len_arrays['POP']['1'][] = array('0' => 'reg32',);
$opcode_len_result['POP']['1'][] = 1;  //o32 58+r]
$opcode_len_arrays['POP']['1'][] = array('0' => 'rm16',);
$opcode_len_result['POP']['1'][] = 3;  //o16 8f /0]
$opcode_len_arrays['POP']['1'][] = array('0' => 'rm32',);
$opcode_len_result['POP']['1'][] = 2;  //o32 8f /0]
$opcode_len_arrays['POP']['1'][] = array('0' => 'reg_es',);
$opcode_len_result['POP']['1'][] = 1;  //07]
$opcode_len_arrays['POP']['1'][] = array('0' => 'reg_cs',);
$opcode_len_result['POP']['1'][] = 1;  //0f]
$opcode_len_arrays['POP']['1'][] = array('0' => 'reg_ss',);
$opcode_len_result['POP']['1'][] = 1;  //17]
$opcode_len_arrays['POP']['1'][] = array('0' => 'reg_ds',);
$opcode_len_result['POP']['1'][] = 1;  //1f]
$opcode_len_arrays['POP']['1'][] = array('0' => 'reg_fs',);
$opcode_len_result['POP']['1'][] = 2;  //0f a1]
$opcode_len_arrays['POP']['1'][] = array('0' => 'reg_gs',);
$opcode_len_result['POP']['1'][] = 2;  //0f a9]
$opcode_len_arrays['POPA']['0'][] = array('0' => 'void',);
$opcode_len_result['POPA']['0'][] = 1;  //odf 61]
$opcode_len_arrays['POPAD']['0'][] = array('0' => 'void',);
$opcode_len_result['POPAD']['0'][] = 1;  //o32 61]
$opcode_len_arrays['POPAW']['0'][] = array('0' => 'void',);
$opcode_len_result['POPAW']['0'][] = 2;  //o16 61]
$opcode_len_arrays['POPF']['0'][] = array('0' => 'void',);
$opcode_len_result['POPF']['0'][] = 1;  //odf 9d]
$opcode_len_arrays['POPFD']['0'][] = array('0' => 'void',);
$opcode_len_result['POPFD']['0'][] = 1;  //o32 9d]
$opcode_len_arrays['POPFQ']['0'][] = array('0' => 'void',);
$opcode_len_result['POPFQ']['0'][] = 1;  //o32 9d]
$opcode_len_arrays['POPFW']['0'][] = array('0' => 'void',);
$opcode_len_result['POPFW']['0'][] = 2;  //o16 9d]
$opcode_len_arrays['PREFETCH']['1'][] = array('0' => 'mem',);
$opcode_len_result['PREFETCH']['1'][] = 3;  //0f 0d /0]
$opcode_len_arrays['PREFETCHW']['1'][] = array('0' => 'mem',);
$opcode_len_result['PREFETCHW']['1'][] = 3;  //0f 0d /1]
$opcode_len_arrays['PUSH']['1'][] = array('0' => 'reg16',);
$opcode_len_result['PUSH']['1'][] = 2;  //o16 50+r]
$opcode_len_arrays['PUSH']['1'][] = array('0' => 'reg32',);
$opcode_len_result['PUSH']['1'][] = 1;  //o32 50+r]
$opcode_len_arrays['PUSH']['1'][] = array('0' => 'rm16',);
$opcode_len_result['PUSH']['1'][] = 3;  //o16 ff /6]
$opcode_len_arrays['PUSH']['1'][] = array('0' => 'rm32',);
$opcode_len_result['PUSH']['1'][] = 2;  //o32 ff /6]
$opcode_len_arrays['PUSH']['1'][] = array('0' => 'reg_es',);
$opcode_len_result['PUSH']['1'][] = 1;  //06]
$opcode_len_arrays['PUSH']['1'][] = array('0' => 'reg_cs',);
$opcode_len_result['PUSH']['1'][] = 1;  //0e]
$opcode_len_arrays['PUSH']['1'][] = array('0' => 'reg_ss',);
$opcode_len_result['PUSH']['1'][] = 1;  //16]
$opcode_len_arrays['PUSH']['1'][] = array('0' => 'reg_ds',);
$opcode_len_result['PUSH']['1'][] = 1;  //1e]
$opcode_len_arrays['PUSH']['1'][] = array('0' => 'reg_fs',);
$opcode_len_result['PUSH']['1'][] = 2;  //0f a0]
$opcode_len_arrays['PUSH']['1'][] = array('0' => 'reg_gs',);
$opcode_len_result['PUSH']['1'][] = 2;  //0f a8]
$opcode_len_arrays['PUSH']['1'][] = array('0' => 'imm8',);
$opcode_len_result['PUSH']['1'][] = 2;  //6a ib,s]
$opcode_len_arrays['PUSH']['1'][] = array('0' => 'sbyteword16',);
$opcode_len_result['PUSH']['1'][] = 3;  //o16 6a ib,s]
$opcode_len_arrays['PUSH']['1'][] = array('0' => 'imm16',);
$opcode_len_result['PUSH']['1'][] = 4;  //o16 68 iw]
$opcode_len_arrays['PUSH']['1'][] = array('0' => 'sbytedword32',);
$opcode_len_result['PUSH']['1'][] = 2;  //o32 6a ib,s]
$opcode_len_arrays['PUSH']['1'][] = array('0' => 'imm32',);
$opcode_len_result['PUSH']['1'][] = 5;  //o32 68 id]
$opcode_len_arrays['PUSH']['1'][] = array('0' => 'sbytedword32',);
$opcode_len_result['PUSH']['1'][] = 2;  //o32 6a ib,s]
$opcode_len_arrays['PUSH']['1'][] = array('0' => 'imm32',);
$opcode_len_result['PUSH']['1'][] = 5;  //o32 68 id]
$opcode_len_arrays['PUSHA']['0'][] = array('0' => 'void',);
$opcode_len_result['PUSHA']['0'][] = 1;  //odf 60]
$opcode_len_arrays['PUSHAD']['0'][] = array('0' => 'void',);
$opcode_len_result['PUSHAD']['0'][] = 1;  //o32 60]
$opcode_len_arrays['PUSHAW']['0'][] = array('0' => 'void',);
$opcode_len_result['PUSHAW']['0'][] = 2;  //o16 60]
$opcode_len_arrays['PUSHF']['0'][] = array('0' => 'void',);
$opcode_len_result['PUSHF']['0'][] = 1;  //odf 9c]
$opcode_len_arrays['PUSHFD']['0'][] = array('0' => 'void',);
$opcode_len_result['PUSHFD']['0'][] = 1;  //o32 9c]
$opcode_len_arrays['PUSHFQ']['0'][] = array('0' => 'void',);
$opcode_len_result['PUSHFQ']['0'][] = 1;  //o32 9c]
$opcode_len_arrays['PUSHFW']['0'][] = array('0' => 'void',);
$opcode_len_result['PUSHFW']['0'][] = 2;  //o16 9c]
$opcode_len_arrays['RCL']['2'][] = array('0' => 'rm8','1' => 'unity',);
$opcode_len_result['RCL']['2'][] = 2;  //d0 /2]
$opcode_len_arrays['RCL']['2'][] = array('0' => 'rm8','1' => 'reg_cl',);
$opcode_len_result['RCL']['2'][] = 2;  //d2 /2]
$opcode_len_arrays['RCL']['2'][] = array('0' => 'rm8','1' => 'imm8',);
$opcode_len_result['RCL']['2'][] = 3;  //c0 /2 ib,u]
$opcode_len_arrays['RCL']['2'][] = array('0' => 'rm16','1' => 'unity',);
$opcode_len_result['RCL']['2'][] = 3;  //o16 d1 /2]
$opcode_len_arrays['RCL']['2'][] = array('0' => 'rm16','1' => 'reg_cl',);
$opcode_len_result['RCL']['2'][] = 3;  //o16 d3 /2]
$opcode_len_arrays['RCL']['2'][] = array('0' => 'rm16','1' => 'imm8',);
$opcode_len_result['RCL']['2'][] = 4;  //o16 c1 /2 ib,u]
$opcode_len_arrays['RCL']['2'][] = array('0' => 'rm32','1' => 'unity',);
$opcode_len_result['RCL']['2'][] = 2;  //o32 d1 /2]
$opcode_len_arrays['RCL']['2'][] = array('0' => 'rm32','1' => 'reg_cl',);
$opcode_len_result['RCL']['2'][] = 2;  //o32 d3 /2]
$opcode_len_arrays['RCL']['2'][] = array('0' => 'rm32','1' => 'imm8',);
$opcode_len_result['RCL']['2'][] = 3;  //o32 c1 /2 ib,u]
$opcode_len_arrays['RCR']['2'][] = array('0' => 'rm8','1' => 'unity',);
$opcode_len_result['RCR']['2'][] = 2;  //d0 /3]
$opcode_len_arrays['RCR']['2'][] = array('0' => 'rm8','1' => 'reg_cl',);
$opcode_len_result['RCR']['2'][] = 2;  //d2 /3]
$opcode_len_arrays['RCR']['2'][] = array('0' => 'rm8','1' => 'imm8',);
$opcode_len_result['RCR']['2'][] = 3;  //c0 /3 ib,u]
$opcode_len_arrays['RCR']['2'][] = array('0' => 'rm16','1' => 'unity',);
$opcode_len_result['RCR']['2'][] = 3;  //o16 d1 /3]
$opcode_len_arrays['RCR']['2'][] = array('0' => 'rm16','1' => 'reg_cl',);
$opcode_len_result['RCR']['2'][] = 3;  //o16 d3 /3]
$opcode_len_arrays['RCR']['2'][] = array('0' => 'rm16','1' => 'imm8',);
$opcode_len_result['RCR']['2'][] = 4;  //o16 c1 /3 ib,u]
$opcode_len_arrays['RCR']['2'][] = array('0' => 'rm32','1' => 'unity',);
$opcode_len_result['RCR']['2'][] = 2;  //o32 d1 /3]
$opcode_len_arrays['RCR']['2'][] = array('0' => 'rm32','1' => 'reg_cl',);
$opcode_len_result['RCR']['2'][] = 2;  //o32 d3 /3]
$opcode_len_arrays['RCR']['2'][] = array('0' => 'rm32','1' => 'imm8',);
$opcode_len_result['RCR']['2'][] = 3;  //o32 c1 /3 ib,u]
$opcode_len_arrays['RDSHR']['1'][] = array('0' => 'rm32',);
$opcode_len_result['RDSHR']['1'][] = 3;  //o32 0f 36 /0]
$opcode_len_arrays['RDMSR']['0'][] = array('0' => 'void',);
$opcode_len_result['RDMSR']['0'][] = 2;  //0f 32]
$opcode_len_arrays['RDPMC']['0'][] = array('0' => 'void',);
$opcode_len_result['RDPMC']['0'][] = 2;  //0f 33]
$opcode_len_arrays['RDTSC']['0'][] = array('0' => 'void',);
$opcode_len_result['RDTSC']['0'][] = 2;  //0f 31]
$opcode_len_arrays['RDTSCP']['0'][] = array('0' => 'void',);
$opcode_len_result['RDTSCP']['0'][] = 3;  //0f 01 f9]
$opcode_len_arrays['RET']['0'][] = array('0' => 'void',);
$opcode_len_result['RET']['0'][] = 1;  //c3]
$opcode_len_arrays['RET']['1'][] = array('0' => 'imm',);
$opcode_len_result['RET']['1'][] = 3;  //c2 iw]
$opcode_len_arrays['RETF']['0'][] = array('0' => 'void',);
$opcode_len_result['RETF']['0'][] = 1;  //cb]
$opcode_len_arrays['RETF']['1'][] = array('0' => 'imm',);
$opcode_len_result['RETF']['1'][] = 3;  //ca iw]
$opcode_len_arrays['RETN']['0'][] = array('0' => 'void',);
$opcode_len_result['RETN']['0'][] = 1;  //c3]
$opcode_len_arrays['RETN']['1'][] = array('0' => 'imm',);
$opcode_len_result['RETN']['1'][] = 3;  //c2 iw]
$opcode_len_arrays['ROL']['2'][] = array('0' => 'rm8','1' => 'unity',);
$opcode_len_result['ROL']['2'][] = 2;  //d0 /0]
$opcode_len_arrays['ROL']['2'][] = array('0' => 'rm8','1' => 'reg_cl',);
$opcode_len_result['ROL']['2'][] = 2;  //d2 /0]
$opcode_len_arrays['ROL']['2'][] = array('0' => 'rm8','1' => 'imm8',);
$opcode_len_result['ROL']['2'][] = 3;  //c0 /0 ib,u]
$opcode_len_arrays['ROL']['2'][] = array('0' => 'rm16','1' => 'unity',);
$opcode_len_result['ROL']['2'][] = 3;  //o16 d1 /0]
$opcode_len_arrays['ROL']['2'][] = array('0' => 'rm16','1' => 'reg_cl',);
$opcode_len_result['ROL']['2'][] = 3;  //o16 d3 /0]
$opcode_len_arrays['ROL']['2'][] = array('0' => 'rm16','1' => 'imm8',);
$opcode_len_result['ROL']['2'][] = 4;  //o16 c1 /0 ib,u]
$opcode_len_arrays['ROL']['2'][] = array('0' => 'rm32','1' => 'unity',);
$opcode_len_result['ROL']['2'][] = 2;  //o32 d1 /0]
$opcode_len_arrays['ROL']['2'][] = array('0' => 'rm32','1' => 'reg_cl',);
$opcode_len_result['ROL']['2'][] = 2;  //o32 d3 /0]
$opcode_len_arrays['ROL']['2'][] = array('0' => 'rm32','1' => 'imm8',);
$opcode_len_result['ROL']['2'][] = 3;  //o32 c1 /0 ib,u]
$opcode_len_arrays['ROR']['2'][] = array('0' => 'rm8','1' => 'unity',);
$opcode_len_result['ROR']['2'][] = 2;  //d0 /1]
$opcode_len_arrays['ROR']['2'][] = array('0' => 'rm8','1' => 'reg_cl',);
$opcode_len_result['ROR']['2'][] = 2;  //d2 /1]
$opcode_len_arrays['ROR']['2'][] = array('0' => 'rm8','1' => 'imm8',);
$opcode_len_result['ROR']['2'][] = 3;  //c0 /1 ib,u]
$opcode_len_arrays['ROR']['2'][] = array('0' => 'rm16','1' => 'unity',);
$opcode_len_result['ROR']['2'][] = 3;  //o16 d1 /1]
$opcode_len_arrays['ROR']['2'][] = array('0' => 'rm16','1' => 'reg_cl',);
$opcode_len_result['ROR']['2'][] = 3;  //o16 d3 /1]
$opcode_len_arrays['ROR']['2'][] = array('0' => 'rm16','1' => 'imm8',);
$opcode_len_result['ROR']['2'][] = 4;  //o16 c1 /1 ib,u]
$opcode_len_arrays['ROR']['2'][] = array('0' => 'rm32','1' => 'unity',);
$opcode_len_result['ROR']['2'][] = 2;  //o32 d1 /1]
$opcode_len_arrays['ROR']['2'][] = array('0' => 'rm32','1' => 'reg_cl',);
$opcode_len_result['ROR']['2'][] = 2;  //o32 d3 /1]
$opcode_len_arrays['ROR']['2'][] = array('0' => 'rm32','1' => 'imm8',);
$opcode_len_result['ROR']['2'][] = 3;  //o32 c1 /1 ib,u]
$opcode_len_arrays['RDM']['0'][] = array('0' => 'void',);
$opcode_len_result['RDM']['0'][] = 2;  //0f 3a]
$opcode_len_arrays['RSDC']['2'][] = array('0' => 'reg_sreg','1' => 'mem80',);
$opcode_len_result['RSDC']['2'][] = 3;  //0f 79 /r]
$opcode_len_arrays['RSLDT']['1'][] = array('0' => 'mem80',);
$opcode_len_result['RSLDT']['1'][] = 3;  //0f 7b /0]
$opcode_len_arrays['RSM']['0'][] = array('0' => 'void',);
$opcode_len_result['RSM']['0'][] = 2;  //0f aa]
$opcode_len_arrays['RSTS']['1'][] = array('0' => 'mem80',);
$opcode_len_result['RSTS']['1'][] = 3;  //0f 7d /0]
$opcode_len_arrays['SAHF']['0'][] = array('0' => 'void',);
$opcode_len_result['SAHF']['0'][] = 1;  //9e]
$opcode_len_arrays['SAL']['2'][] = array('0' => 'rm8','1' => 'unity',);
$opcode_len_result['SAL']['2'][] = 2;  //d0 /4]
$opcode_len_arrays['SAL']['2'][] = array('0' => 'rm8','1' => 'reg_cl',);
$opcode_len_result['SAL']['2'][] = 2;  //d2 /4]
$opcode_len_arrays['SAL']['2'][] = array('0' => 'rm8','1' => 'imm8',);
$opcode_len_result['SAL']['2'][] = 3;  //c0 /4 ib,u]
$opcode_len_arrays['SAL']['2'][] = array('0' => 'rm16','1' => 'unity',);
$opcode_len_result['SAL']['2'][] = 3;  //o16 d1 /4]
$opcode_len_arrays['SAL']['2'][] = array('0' => 'rm16','1' => 'reg_cl',);
$opcode_len_result['SAL']['2'][] = 3;  //o16 d3 /4]
$opcode_len_arrays['SAL']['2'][] = array('0' => 'rm16','1' => 'imm8',);
$opcode_len_result['SAL']['2'][] = 4;  //o16 c1 /4 ib,u]
$opcode_len_arrays['SAL']['2'][] = array('0' => 'rm32','1' => 'unity',);
$opcode_len_result['SAL']['2'][] = 2;  //o32 d1 /4]
$opcode_len_arrays['SAL']['2'][] = array('0' => 'rm32','1' => 'reg_cl',);
$opcode_len_result['SAL']['2'][] = 2;  //o32 d3 /4]
$opcode_len_arrays['SAL']['2'][] = array('0' => 'rm32','1' => 'imm8',);
$opcode_len_result['SAL']['2'][] = 3;  //o32 c1 /4 ib,u]
$opcode_len_arrays['SALC']['0'][] = array('0' => 'void',);
$opcode_len_result['SALC']['0'][] = 1;  //d6]
$opcode_len_arrays['SAR']['2'][] = array('0' => 'rm8','1' => 'unity',);
$opcode_len_result['SAR']['2'][] = 2;  //d0 /7]
$opcode_len_arrays['SAR']['2'][] = array('0' => 'rm8','1' => 'reg_cl',);
$opcode_len_result['SAR']['2'][] = 2;  //d2 /7]
$opcode_len_arrays['SAR']['2'][] = array('0' => 'rm8','1' => 'imm8',);
$opcode_len_result['SAR']['2'][] = 3;  //c0 /7 ib,u]
$opcode_len_arrays['SAR']['2'][] = array('0' => 'rm16','1' => 'unity',);
$opcode_len_result['SAR']['2'][] = 3;  //o16 d1 /7]
$opcode_len_arrays['SAR']['2'][] = array('0' => 'rm16','1' => 'reg_cl',);
$opcode_len_result['SAR']['2'][] = 3;  //o16 d3 /7]
$opcode_len_arrays['SAR']['2'][] = array('0' => 'rm16','1' => 'imm8',);
$opcode_len_result['SAR']['2'][] = 4;  //o16 c1 /7 ib,u]
$opcode_len_arrays['SAR']['2'][] = array('0' => 'rm32','1' => 'unity',);
$opcode_len_result['SAR']['2'][] = 2;  //o32 d1 /7]
$opcode_len_arrays['SAR']['2'][] = array('0' => 'rm32','1' => 'reg_cl',);
$opcode_len_result['SAR']['2'][] = 2;  //o32 d3 /7]
$opcode_len_arrays['SAR']['2'][] = array('0' => 'rm32','1' => 'imm8',);
$opcode_len_result['SAR']['2'][] = 3;  //o32 c1 /7 ib,u]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'mem','1' => 'reg8',);
$opcode_len_result['SBB']['2'][] = 2;  //hle 18 /r]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'reg8','1' => 'reg8',);
$opcode_len_result['SBB']['2'][] = 2;  //18 /r]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'mem','1' => 'reg16',);
$opcode_len_result['SBB']['2'][] = 3;  //hle o16 19 /r]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['SBB']['2'][] = 3;  //o16 19 /r]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'mem','1' => 'reg32',);
$opcode_len_result['SBB']['2'][] = 2;  //hle o32 19 /r]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['SBB']['2'][] = 2;  //o32 19 /r]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'reg8','1' => 'mem',);
$opcode_len_result['SBB']['2'][] = 2;  //1a /r]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'reg8','1' => 'reg8',);
$opcode_len_result['SBB']['2'][] = 2;  //1a /r]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['SBB']['2'][] = 3;  //o16 1b /r]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['SBB']['2'][] = 3;  //o16 1b /r]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['SBB']['2'][] = 2;  //o32 1b /r]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['SBB']['2'][] = 2;  //o32 1b /r]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'rm16','1' => 'imm8',);
$opcode_len_result['SBB']['2'][] = 4;  //hle o16 83 /3 ib,s]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'rm32','1' => 'imm8',);
$opcode_len_result['SBB']['2'][] = 3;  //hle o32 83 /3 ib,s]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'reg_al','1' => 'imm',);
$opcode_len_result['SBB']['2'][] = 2;  //1c ib]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'reg_ax','1' => 'sbyteword',);
$opcode_len_result['SBB']['2'][] = 4;  //o16 83 /3 ib,s]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'reg_ax','1' => 'imm',);
$opcode_len_result['SBB']['2'][] = 4;  //o16 1d iw]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'reg_eax','1' => 'sbytedword',);
$opcode_len_result['SBB']['2'][] = 3;  //o32 83 /3 ib,s]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'reg_eax','1' => 'imm',);
$opcode_len_result['SBB']['2'][] = 5;  //o32 1d id]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'rm8','1' => 'imm',);
$opcode_len_result['SBB']['2'][] = 3;  //hle 80 /3 ib]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'rm16','1' => 'sbyteword',);
$opcode_len_result['SBB']['2'][] = 4;  //hle o16 83 /3 ib,s]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'rm16','1' => 'imm',);
$opcode_len_result['SBB']['2'][] = 5;  //hle o16 81 /3 iw]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'rm32','1' => 'sbytedword',);
$opcode_len_result['SBB']['2'][] = 3;  //hle o32 83 /3 ib,s]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'rm32','1' => 'imm',);
$opcode_len_result['SBB']['2'][] = 6;  //hle o32 81 /3 id]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'mem','1' => 'imm8',);
$opcode_len_result['SBB']['2'][] = 3;  //hle 80 /3 ib]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'mem','1' => 'sbyteword16',);
$opcode_len_result['SBB']['2'][] = 4;  //hle o16 83 /3 ib,s]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'mem','1' => 'imm16',);
$opcode_len_result['SBB']['2'][] = 5;  //hle o16 81 /3 iw]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'mem','1' => 'sbytedword32',);
$opcode_len_result['SBB']['2'][] = 3;  //hle o32 83 /3 ib,s]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'mem','1' => 'imm32',);
$opcode_len_result['SBB']['2'][] = 6;  //hle o32 81 /3 id]
$opcode_len_arrays['SBB']['2'][] = array('0' => 'rm8','1' => 'imm',);
$opcode_len_result['SBB']['2'][] = 3;  //hle 82 /3 ib]
$opcode_len_arrays['SCASB']['0'][] = array('0' => 'void',);
$opcode_len_result['SCASB']['0'][] = 1;  //repe ae]
$opcode_len_arrays['SCASD']['0'][] = array('0' => 'void',);
$opcode_len_result['SCASD']['0'][] = 1;  //repe o32 af]
$opcode_len_arrays['SCASW']['0'][] = array('0' => 'void',);
$opcode_len_result['SCASW']['0'][] = 2;  //repe o16 af]
$opcode_len_arrays['SGDT']['1'][] = array('0' => 'mem',);
$opcode_len_result['SGDT']['1'][] = 3;  //0f 01 /0]
$opcode_len_arrays['SHL']['2'][] = array('0' => 'rm8','1' => 'unity',);
$opcode_len_result['SHL']['2'][] = 2;  //d0 /4]
$opcode_len_arrays['SHL']['2'][] = array('0' => 'rm8','1' => 'reg_cl',);
$opcode_len_result['SHL']['2'][] = 2;  //d2 /4]
$opcode_len_arrays['SHL']['2'][] = array('0' => 'rm8','1' => 'imm8',);
$opcode_len_result['SHL']['2'][] = 3;  //c0 /4 ib,u]
$opcode_len_arrays['SHL']['2'][] = array('0' => 'rm16','1' => 'unity',);
$opcode_len_result['SHL']['2'][] = 3;  //o16 d1 /4]
$opcode_len_arrays['SHL']['2'][] = array('0' => 'rm16','1' => 'reg_cl',);
$opcode_len_result['SHL']['2'][] = 3;  //o16 d3 /4]
$opcode_len_arrays['SHL']['2'][] = array('0' => 'rm16','1' => 'imm8',);
$opcode_len_result['SHL']['2'][] = 4;  //o16 c1 /4 ib,u]
$opcode_len_arrays['SHL']['2'][] = array('0' => 'rm32','1' => 'unity',);
$opcode_len_result['SHL']['2'][] = 2;  //o32 d1 /4]
$opcode_len_arrays['SHL']['2'][] = array('0' => 'rm32','1' => 'reg_cl',);
$opcode_len_result['SHL']['2'][] = 2;  //o32 d3 /4]
$opcode_len_arrays['SHL']['2'][] = array('0' => 'rm32','1' => 'imm8',);
$opcode_len_result['SHL']['2'][] = 3;  //o32 c1 /4 ib,u]
$opcode_len_arrays['SHLD']['3'][] = array('0' => 'mem','1' => 'reg16','2' => 'imm',);
$opcode_len_result['SHLD']['3'][] = 5;  //o16 0f a4 /r ib,u]
$opcode_len_arrays['SHLD']['3'][] = array('0' => 'reg16','1' => 'reg16','2' => 'imm',);
$opcode_len_result['SHLD']['3'][] = 5;  //o16 0f a4 /r ib,u]
$opcode_len_arrays['SHLD']['3'][] = array('0' => 'mem','1' => 'reg32','2' => 'imm',);
$opcode_len_result['SHLD']['3'][] = 4;  //o32 0f a4 /r ib,u]
$opcode_len_arrays['SHLD']['3'][] = array('0' => 'reg32','1' => 'reg32','2' => 'imm',);
$opcode_len_result['SHLD']['3'][] = 4;  //o32 0f a4 /r ib,u]
$opcode_len_arrays['SHLD']['3'][] = array('0' => 'mem','1' => 'reg16','2' => 'reg_cl',);
$opcode_len_result['SHLD']['3'][] = 4;  //o16 0f a5 /r]
$opcode_len_arrays['SHLD']['3'][] = array('0' => 'reg16','1' => 'reg16','2' => 'reg_cl',);
$opcode_len_result['SHLD']['3'][] = 4;  //o16 0f a5 /r]
$opcode_len_arrays['SHLD']['3'][] = array('0' => 'mem','1' => 'reg32','2' => 'reg_cl',);
$opcode_len_result['SHLD']['3'][] = 3;  //o32 0f a5 /r]
$opcode_len_arrays['SHLD']['3'][] = array('0' => 'reg32','1' => 'reg32','2' => 'reg_cl',);
$opcode_len_result['SHLD']['3'][] = 3;  //o32 0f a5 /r]
$opcode_len_arrays['SHR']['2'][] = array('0' => 'rm8','1' => 'unity',);
$opcode_len_result['SHR']['2'][] = 2;  //d0 /5]
$opcode_len_arrays['SHR']['2'][] = array('0' => 'rm8','1' => 'reg_cl',);
$opcode_len_result['SHR']['2'][] = 2;  //d2 /5]
$opcode_len_arrays['SHR']['2'][] = array('0' => 'rm8','1' => 'imm8',);
$opcode_len_result['SHR']['2'][] = 3;  //c0 /5 ib,u]
$opcode_len_arrays['SHR']['2'][] = array('0' => 'rm16','1' => 'unity',);
$opcode_len_result['SHR']['2'][] = 3;  //o16 d1 /5]
$opcode_len_arrays['SHR']['2'][] = array('0' => 'rm16','1' => 'reg_cl',);
$opcode_len_result['SHR']['2'][] = 3;  //o16 d3 /5]
$opcode_len_arrays['SHR']['2'][] = array('0' => 'rm16','1' => 'imm8',);
$opcode_len_result['SHR']['2'][] = 4;  //o16 c1 /5 ib,u]
$opcode_len_arrays['SHR']['2'][] = array('0' => 'rm32','1' => 'unity',);
$opcode_len_result['SHR']['2'][] = 2;  //o32 d1 /5]
$opcode_len_arrays['SHR']['2'][] = array('0' => 'rm32','1' => 'reg_cl',);
$opcode_len_result['SHR']['2'][] = 2;  //o32 d3 /5]
$opcode_len_arrays['SHR']['2'][] = array('0' => 'rm32','1' => 'imm8',);
$opcode_len_result['SHR']['2'][] = 3;  //o32 c1 /5 ib,u]
$opcode_len_arrays['SHRD']['3'][] = array('0' => 'mem','1' => 'reg16','2' => 'imm',);
$opcode_len_result['SHRD']['3'][] = 5;  //o16 0f ac /r ib,u]
$opcode_len_arrays['SHRD']['3'][] = array('0' => 'reg16','1' => 'reg16','2' => 'imm',);
$opcode_len_result['SHRD']['3'][] = 5;  //o16 0f ac /r ib,u]
$opcode_len_arrays['SHRD']['3'][] = array('0' => 'mem','1' => 'reg32','2' => 'imm',);
$opcode_len_result['SHRD']['3'][] = 4;  //o32 0f ac /r ib,u]
$opcode_len_arrays['SHRD']['3'][] = array('0' => 'reg32','1' => 'reg32','2' => 'imm',);
$opcode_len_result['SHRD']['3'][] = 4;  //o32 0f ac /r ib,u]
$opcode_len_arrays['SHRD']['3'][] = array('0' => 'mem','1' => 'reg16','2' => 'reg_cl',);
$opcode_len_result['SHRD']['3'][] = 4;  //o16 0f ad /r]
$opcode_len_arrays['SHRD']['3'][] = array('0' => 'reg16','1' => 'reg16','2' => 'reg_cl',);
$opcode_len_result['SHRD']['3'][] = 4;  //o16 0f ad /r]
$opcode_len_arrays['SHRD']['3'][] = array('0' => 'mem','1' => 'reg32','2' => 'reg_cl',);
$opcode_len_result['SHRD']['3'][] = 3;  //o32 0f ad /r]
$opcode_len_arrays['SHRD']['3'][] = array('0' => 'reg32','1' => 'reg32','2' => 'reg_cl',);
$opcode_len_result['SHRD']['3'][] = 3;  //o32 0f ad /r]
$opcode_len_arrays['SIDT']['1'][] = array('0' => 'mem',);
$opcode_len_result['SIDT']['1'][] = 3;  //0f 01 /1]
$opcode_len_arrays['SLDT']['1'][] = array('0' => 'mem',);
$opcode_len_result['SLDT']['1'][] = 3;  //0f 00 /0]
$opcode_len_arrays['SLDT']['1'][] = array('0' => 'mem16',);
$opcode_len_result['SLDT']['1'][] = 3;  //0f 00 /0]
$opcode_len_arrays['SLDT']['1'][] = array('0' => 'reg16',);
$opcode_len_result['SLDT']['1'][] = 4;  //o16 0f 00 /0]
$opcode_len_arrays['SLDT']['1'][] = array('0' => 'reg32',);
$opcode_len_result['SLDT']['1'][] = 3;  //o32 0f 00 /0]
$opcode_len_arrays['SKINIT']['0'][] = array('0' => 'void',);
$opcode_len_result['SKINIT']['0'][] = 3;  //0f 01 de]
$opcode_len_arrays['SMI']['0'][] = array('0' => 'void',);
$opcode_len_result['SMI']['0'][] = 1;  //f1]
$opcode_len_arrays['SMINT']['0'][] = array('0' => 'void',);
$opcode_len_result['SMINT']['0'][] = 2;  //0f 38]
$opcode_len_arrays['SMINTOLD']['0'][] = array('0' => 'void',);
$opcode_len_result['SMINTOLD']['0'][] = 2;  //0f 7e]
$opcode_len_arrays['SMSW']['1'][] = array('0' => 'mem',);
$opcode_len_result['SMSW']['1'][] = 3;  //0f 01 /4]
$opcode_len_arrays['SMSW']['1'][] = array('0' => 'mem16',);
$opcode_len_result['SMSW']['1'][] = 3;  //0f 01 /4]
$opcode_len_arrays['SMSW']['1'][] = array('0' => 'reg16',);
$opcode_len_result['SMSW']['1'][] = 4;  //o16 0f 01 /4]
$opcode_len_arrays['SMSW']['1'][] = array('0' => 'reg32',);
$opcode_len_result['SMSW']['1'][] = 3;  //o32 0f 01 /4]
$opcode_len_arrays['STC']['0'][] = array('0' => 'void',);
$opcode_len_result['STC']['0'][] = 1;  //f9]
$opcode_len_arrays['STD']['0'][] = array('0' => 'void',);
$opcode_len_result['STD']['0'][] = 1;  //fd]
$opcode_len_arrays['STI']['0'][] = array('0' => 'void',);
$opcode_len_result['STI']['0'][] = 1;  //fb]
$opcode_len_arrays['STOSB']['0'][] = array('0' => 'void',);
$opcode_len_result['STOSB']['0'][] = 1;  //aa]
$opcode_len_arrays['STOSD']['0'][] = array('0' => 'void',);
$opcode_len_result['STOSD']['0'][] = 1;  //o32 ab]
$opcode_len_arrays['STOSW']['0'][] = array('0' => 'void',);
$opcode_len_result['STOSW']['0'][] = 2;  //o16 ab]
$opcode_len_arrays['STR']['1'][] = array('0' => 'mem',);
$opcode_len_result['STR']['1'][] = 3;  //0f 00 /1]
$opcode_len_arrays['STR']['1'][] = array('0' => 'mem16',);
$opcode_len_result['STR']['1'][] = 3;  //0f 00 /1]
$opcode_len_arrays['STR']['1'][] = array('0' => 'reg16',);
$opcode_len_result['STR']['1'][] = 4;  //o16 0f 00 /1]
$opcode_len_arrays['STR']['1'][] = array('0' => 'reg32',);
$opcode_len_result['STR']['1'][] = 3;  //o32 0f 00 /1]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'mem','1' => 'reg8',);
$opcode_len_result['SUB']['2'][] = 2;  //hle 28 /r]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'reg8','1' => 'reg8',);
$opcode_len_result['SUB']['2'][] = 2;  //28 /r]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'mem','1' => 'reg16',);
$opcode_len_result['SUB']['2'][] = 3;  //hle o16 29 /r]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['SUB']['2'][] = 3;  //o16 29 /r]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'mem','1' => 'reg32',);
$opcode_len_result['SUB']['2'][] = 2;  //hle o32 29 /r]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['SUB']['2'][] = 2;  //o32 29 /r]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'reg8','1' => 'mem',);
$opcode_len_result['SUB']['2'][] = 2;  //2a /r]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'reg8','1' => 'reg8',);
$opcode_len_result['SUB']['2'][] = 2;  //2a /r]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['SUB']['2'][] = 3;  //o16 2b /r]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['SUB']['2'][] = 3;  //o16 2b /r]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['SUB']['2'][] = 2;  //o32 2b /r]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['SUB']['2'][] = 2;  //o32 2b /r]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'rm16','1' => 'imm8',);
$opcode_len_result['SUB']['2'][] = 4;  //hle o16 83 /5 ib,s]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'rm32','1' => 'imm8',);
$opcode_len_result['SUB']['2'][] = 3;  //hle o32 83 /5 ib,s]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'reg_al','1' => 'imm',);
$opcode_len_result['SUB']['2'][] = 2;  //2c ib]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'reg_ax','1' => 'sbyteword',);
$opcode_len_result['SUB']['2'][] = 4;  //o16 83 /5 ib,s]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'reg_ax','1' => 'imm',);
$opcode_len_result['SUB']['2'][] = 4;  //o16 2d iw]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'reg_eax','1' => 'sbytedword',);
$opcode_len_result['SUB']['2'][] = 3;  //o32 83 /5 ib,s]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'reg_eax','1' => 'imm',);
$opcode_len_result['SUB']['2'][] = 5;  //o32 2d id]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'rm8','1' => 'imm',);
$opcode_len_result['SUB']['2'][] = 3;  //hle 80 /5 ib]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'rm16','1' => 'sbyteword',);
$opcode_len_result['SUB']['2'][] = 4;  //hle o16 83 /5 ib,s]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'rm16','1' => 'imm',);
$opcode_len_result['SUB']['2'][] = 5;  //hle o16 81 /5 iw]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'rm32','1' => 'sbytedword',);
$opcode_len_result['SUB']['2'][] = 3;  //hle o32 83 /5 ib,s]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'rm32','1' => 'imm',);
$opcode_len_result['SUB']['2'][] = 6;  //hle o32 81 /5 id]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'mem','1' => 'imm8',);
$opcode_len_result['SUB']['2'][] = 3;  //hle 80 /5 ib]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'mem','1' => 'sbyteword16',);
$opcode_len_result['SUB']['2'][] = 4;  //hle o16 83 /5 ib,s]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'mem','1' => 'imm16',);
$opcode_len_result['SUB']['2'][] = 5;  //hle o16 81 /5 iw]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'mem','1' => 'sbytedword32',);
$opcode_len_result['SUB']['2'][] = 3;  //hle o32 83 /5 ib,s]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'mem','1' => 'imm32',);
$opcode_len_result['SUB']['2'][] = 6;  //hle o32 81 /5 id]
$opcode_len_arrays['SUB']['2'][] = array('0' => 'rm8','1' => 'imm',);
$opcode_len_result['SUB']['2'][] = 3;  //hle 82 /5 ib]
$opcode_len_arrays['SVDC']['2'][] = array('0' => 'mem80','1' => 'reg_sreg',);
$opcode_len_result['SVDC']['2'][] = 3;  //0f 78 /r]
$opcode_len_arrays['SVLDT']['1'][] = array('0' => 'mem80',);
$opcode_len_result['SVLDT']['1'][] = 3;  //0f 7a /0]
$opcode_len_arrays['SVTS']['1'][] = array('0' => 'mem80',);
$opcode_len_result['SVTS']['1'][] = 3;  //0f 7c /0]
$opcode_len_arrays['SWAPGS']['0'][] = array('0' => 'void',);
$opcode_len_result['SWAPGS']['0'][] = 3;  //0f 01 f8]
$opcode_len_arrays['SYSCALL']['0'][] = array('0' => 'void',);
$opcode_len_result['SYSCALL']['0'][] = 2;  //0f 05]
$opcode_len_arrays['SYSENTER']['0'][] = array('0' => 'void',);
$opcode_len_result['SYSENTER']['0'][] = 2;  //0f 34]
$opcode_len_arrays['SYSEXIT']['0'][] = array('0' => 'void',);
$opcode_len_result['SYSEXIT']['0'][] = 2;  //0f 35]
$opcode_len_arrays['SYSRET']['0'][] = array('0' => 'void',);
$opcode_len_result['SYSRET']['0'][] = 2;  //0f 07]
$opcode_len_arrays['TEST']['2'][] = array('0' => 'mem','1' => 'reg8',);
$opcode_len_result['TEST']['2'][] = 2;  //84 /r]
$opcode_len_arrays['TEST']['2'][] = array('0' => 'reg8','1' => 'reg8',);
$opcode_len_result['TEST']['2'][] = 2;  //84 /r]
$opcode_len_arrays['TEST']['2'][] = array('0' => 'mem','1' => 'reg16',);
$opcode_len_result['TEST']['2'][] = 3;  //o16 85 /r]
$opcode_len_arrays['TEST']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['TEST']['2'][] = 3;  //o16 85 /r]
$opcode_len_arrays['TEST']['2'][] = array('0' => 'mem','1' => 'reg32',);
$opcode_len_result['TEST']['2'][] = 2;  //o32 85 /r]
$opcode_len_arrays['TEST']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['TEST']['2'][] = 2;  //o32 85 /r]
$opcode_len_arrays['TEST']['2'][] = array('0' => 'reg8','1' => 'mem',);
$opcode_len_result['TEST']['2'][] = 2;  //84 /r]
$opcode_len_arrays['TEST']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['TEST']['2'][] = 3;  //o16 85 /r]
$opcode_len_arrays['TEST']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['TEST']['2'][] = 2;  //o32 85 /r]
$opcode_len_arrays['TEST']['2'][] = array('0' => 'reg_al','1' => 'imm',);
$opcode_len_result['TEST']['2'][] = 2;  //a8 ib]
$opcode_len_arrays['TEST']['2'][] = array('0' => 'reg_ax','1' => 'imm',);
$opcode_len_result['TEST']['2'][] = 4;  //o16 a9 iw]
$opcode_len_arrays['TEST']['2'][] = array('0' => 'reg_eax','1' => 'imm',);
$opcode_len_result['TEST']['2'][] = 5;  //o32 a9 id]
$opcode_len_arrays['TEST']['2'][] = array('0' => 'rm8','1' => 'imm',);
$opcode_len_result['TEST']['2'][] = 3;  //f6 /0 ib]
$opcode_len_arrays['TEST']['2'][] = array('0' => 'rm16','1' => 'imm',);
$opcode_len_result['TEST']['2'][] = 5;  //o16 f7 /0 iw]
$opcode_len_arrays['TEST']['2'][] = array('0' => 'rm32','1' => 'imm',);
$opcode_len_result['TEST']['2'][] = 6;  //o32 f7 /0 id]
$opcode_len_arrays['TEST']['2'][] = array('0' => 'mem','1' => 'imm8',);
$opcode_len_result['TEST']['2'][] = 3;  //f6 /0 ib]
$opcode_len_arrays['TEST']['2'][] = array('0' => 'mem','1' => 'imm16',);
$opcode_len_result['TEST']['2'][] = 5;  //o16 f7 /0 iw]
$opcode_len_arrays['TEST']['2'][] = array('0' => 'mem','1' => 'imm32',);
$opcode_len_result['TEST']['2'][] = 6;  //o32 f7 /0 id]
$opcode_len_arrays['UD0']['0'][] = array('0' => 'void',);
$opcode_len_result['UD0']['0'][] = 2;  //0f ff]
$opcode_len_arrays['UD1']['0'][] = array('0' => 'void',);
$opcode_len_result['UD1']['0'][] = 2;  //0f b9]
$opcode_len_arrays['UD2B']['0'][] = array('0' => 'void',);
$opcode_len_result['UD2B']['0'][] = 2;  //0f b9]
$opcode_len_arrays['UD2']['0'][] = array('0' => 'void',);
$opcode_len_result['UD2']['0'][] = 2;  //0f 0b]
$opcode_len_arrays['UD2A']['0'][] = array('0' => 'void',);
$opcode_len_result['UD2A']['0'][] = 2;  //0f 0b]
$opcode_len_arrays['VERR']['1'][] = array('0' => 'mem',);
$opcode_len_result['VERR']['1'][] = 3;  //0f 00 /4]
$opcode_len_arrays['VERR']['1'][] = array('0' => 'mem16',);
$opcode_len_result['VERR']['1'][] = 3;  //0f 00 /4]
$opcode_len_arrays['VERR']['1'][] = array('0' => 'reg16',);
$opcode_len_result['VERR']['1'][] = 3;  //0f 00 /4]
$opcode_len_arrays['VERW']['1'][] = array('0' => 'mem',);
$opcode_len_result['VERW']['1'][] = 3;  //0f 00 /5]
$opcode_len_arrays['VERW']['1'][] = array('0' => 'mem16',);
$opcode_len_result['VERW']['1'][] = 3;  //0f 00 /5]
$opcode_len_arrays['VERW']['1'][] = array('0' => 'reg16',);
$opcode_len_result['VERW']['1'][] = 3;  //0f 00 /5]
$opcode_len_arrays['WBINVD']['0'][] = array('0' => 'void',);
$opcode_len_result['WBINVD']['0'][] = 2;  //0f 09]
$opcode_len_arrays['WRSHR']['1'][] = array('0' => 'rm32',);
$opcode_len_result['WRSHR']['1'][] = 3;  //o32 0f 37 /0]
$opcode_len_arrays['WRMSR']['0'][] = array('0' => 'void',);
$opcode_len_result['WRMSR']['0'][] = 2;  //0f 30]
$opcode_len_arrays['XADD']['2'][] = array('0' => 'mem','1' => 'reg8',);
$opcode_len_result['XADD']['2'][] = 3;  //hle 0f c0 /r]
$opcode_len_arrays['XADD']['2'][] = array('0' => 'reg8','1' => 'reg8',);
$opcode_len_result['XADD']['2'][] = 3;  //0f c0 /r]
$opcode_len_arrays['XADD']['2'][] = array('0' => 'mem','1' => 'reg16',);
$opcode_len_result['XADD']['2'][] = 4;  //hle o16 0f c1 /r]
$opcode_len_arrays['XADD']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['XADD']['2'][] = 4;  //o16 0f c1 /r]
$opcode_len_arrays['XADD']['2'][] = array('0' => 'mem','1' => 'reg32',);
$opcode_len_result['XADD']['2'][] = 3;  //hle o32 0f c1 /r]
$opcode_len_arrays['XADD']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['XADD']['2'][] = 3;  //o32 0f c1 /r]
$opcode_len_arrays['XBTS']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['XBTS']['2'][] = 4;  //o16 0f a6 /r]
$opcode_len_arrays['XBTS']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['XBTS']['2'][] = 4;  //o16 0f a6 /r]
$opcode_len_arrays['XBTS']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['XBTS']['2'][] = 3;  //o32 0f a6 /r]
$opcode_len_arrays['XBTS']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['XBTS']['2'][] = 3;  //o32 0f a6 /r]
$opcode_len_arrays['XCHG']['2'][] = array('0' => 'reg_ax','1' => 'reg16',);
$opcode_len_result['XCHG']['2'][] = 2;  //o16 90+r]
$opcode_len_arrays['XCHG']['2'][] = array('0' => 'reg_eax','1' => 'reg32na',);
$opcode_len_result['XCHG']['2'][] = 1;  //o32 90+r]
$opcode_len_arrays['XCHG']['2'][] = array('0' => 'reg16','1' => 'reg_ax',);
$opcode_len_result['XCHG']['2'][] = 2;  //o16 90+r]
$opcode_len_arrays['XCHG']['2'][] = array('0' => 'reg32na','1' => 'reg_eax',);
$opcode_len_result['XCHG']['2'][] = 1;  //o32 90+r]
$opcode_len_arrays['XCHG']['2'][] = array('0' => 'reg_eax','1' => 'reg_eax',);
$opcode_len_result['XCHG']['2'][] = 1;  //o32 90]
$opcode_len_arrays['XCHG']['2'][] = array('0' => 'reg8','1' => 'mem',);
$opcode_len_result['XCHG']['2'][] = 2;  //hlenl 86 /r]
$opcode_len_arrays['XCHG']['2'][] = array('0' => 'reg8','1' => 'reg8',);
$opcode_len_result['XCHG']['2'][] = 2;  //86 /r]
$opcode_len_arrays['XCHG']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['XCHG']['2'][] = 3;  //hlenl o16 87 /r]
$opcode_len_arrays['XCHG']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['XCHG']['2'][] = 3;  //o16 87 /r]
$opcode_len_arrays['XCHG']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['XCHG']['2'][] = 2;  //hlenl o32 87 /r]
$opcode_len_arrays['XCHG']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['XCHG']['2'][] = 2;  //o32 87 /r]
$opcode_len_arrays['XCHG']['2'][] = array('0' => 'mem','1' => 'reg8',);
$opcode_len_result['XCHG']['2'][] = 2;  //hlenl 86 /r]
$opcode_len_arrays['XCHG']['2'][] = array('0' => 'reg8','1' => 'reg8',);
$opcode_len_result['XCHG']['2'][] = 2;  //86 /r]
$opcode_len_arrays['XCHG']['2'][] = array('0' => 'mem','1' => 'reg16',);
$opcode_len_result['XCHG']['2'][] = 3;  //hlenl o16 87 /r]
$opcode_len_arrays['XCHG']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['XCHG']['2'][] = 3;  //o16 87 /r]
$opcode_len_arrays['XCHG']['2'][] = array('0' => 'mem','1' => 'reg32',);
$opcode_len_result['XCHG']['2'][] = 2;  //hlenl o32 87 /r]
$opcode_len_arrays['XCHG']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['XCHG']['2'][] = 2;  //o32 87 /r]
$opcode_len_arrays['XLATB']['0'][] = array('0' => 'void',);
$opcode_len_result['XLATB']['0'][] = 1;  //d7]
$opcode_len_arrays['XLAT']['0'][] = array('0' => 'void',);
$opcode_len_result['XLAT']['0'][] = 1;  //d7]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'mem','1' => 'reg8',);
$opcode_len_result['XOR']['2'][] = 2;  //hle 30 /r]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'reg8','1' => 'reg8',);
$opcode_len_result['XOR']['2'][] = 2;  //30 /r]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'mem','1' => 'reg16',);
$opcode_len_result['XOR']['2'][] = 3;  //hle o16 31 /r]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['XOR']['2'][] = 3;  //o16 31 /r]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'mem','1' => 'reg32',);
$opcode_len_result['XOR']['2'][] = 2;  //hle o32 31 /r]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['XOR']['2'][] = 2;  //o32 31 /r]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'reg8','1' => 'mem',);
$opcode_len_result['XOR']['2'][] = 2;  //32 /r]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'reg8','1' => 'reg8',);
$opcode_len_result['XOR']['2'][] = 2;  //32 /r]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['XOR']['2'][] = 3;  //o16 33 /r]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['XOR']['2'][] = 3;  //o16 33 /r]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['XOR']['2'][] = 2;  //o32 33 /r]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['XOR']['2'][] = 2;  //o32 33 /r]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'rm16','1' => 'imm8',);
$opcode_len_result['XOR']['2'][] = 4;  //hle o16 83 /6 ib,s]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'rm32','1' => 'imm8',);
$opcode_len_result['XOR']['2'][] = 3;  //hle o32 83 /6 ib,s]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'reg_al','1' => 'imm',);
$opcode_len_result['XOR']['2'][] = 2;  //34 ib]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'reg_ax','1' => 'sbyteword',);
$opcode_len_result['XOR']['2'][] = 4;  //o16 83 /6 ib,s]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'reg_ax','1' => 'imm',);
$opcode_len_result['XOR']['2'][] = 4;  //o16 35 iw]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'reg_eax','1' => 'sbytedword',);
$opcode_len_result['XOR']['2'][] = 3;  //o32 83 /6 ib,s]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'reg_eax','1' => 'imm',);
$opcode_len_result['XOR']['2'][] = 5;  //o32 35 id]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'rm8','1' => 'imm',);
$opcode_len_result['XOR']['2'][] = 3;  //hle 80 /6 ib]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'rm16','1' => 'sbyteword',);
$opcode_len_result['XOR']['2'][] = 4;  //hle o16 83 /6 ib,s]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'rm16','1' => 'imm',);
$opcode_len_result['XOR']['2'][] = 5;  //hle o16 81 /6 iw]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'rm32','1' => 'sbytedword',);
$opcode_len_result['XOR']['2'][] = 3;  //hle o32 83 /6 ib,s]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'rm32','1' => 'imm',);
$opcode_len_result['XOR']['2'][] = 6;  //hle o32 81 /6 id]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'mem','1' => 'imm8',);
$opcode_len_result['XOR']['2'][] = 3;  //hle 80 /6 ib]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'mem','1' => 'sbyteword16',);
$opcode_len_result['XOR']['2'][] = 4;  //hle o16 83 /6 ib,s]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'mem','1' => 'imm16',);
$opcode_len_result['XOR']['2'][] = 5;  //hle o16 81 /6 iw]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'mem','1' => 'sbytedword32',);
$opcode_len_result['XOR']['2'][] = 3;  //hle o32 83 /6 ib,s]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'mem','1' => 'imm32',);
$opcode_len_result['XOR']['2'][] = 6;  //hle o32 81 /6 id]
$opcode_len_arrays['XOR']['2'][] = array('0' => 'rm8','1' => 'imm',);
$opcode_len_result['XOR']['2'][] = 3;  //hle 82 /6 ib]
$opcode_len_arrays['CMOVcc']['2'][] = array('0' => 'reg16','1' => 'mem',);
$opcode_len_result['CMOVcc']['2'][] = 4;  //o16 0f 40+c /r]
$opcode_len_arrays['CMOVcc']['2'][] = array('0' => 'reg16','1' => 'reg16',);
$opcode_len_result['CMOVcc']['2'][] = 4;  //o16 0f 40+c /r]
$opcode_len_arrays['CMOVcc']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['CMOVcc']['2'][] = 3;  //o32 0f 40+c /r]
$opcode_len_arrays['CMOVcc']['2'][] = array('0' => 'reg32','1' => 'reg32',);
$opcode_len_result['CMOVcc']['2'][] = 3;  //o32 0f 40+c /r]
$opcode_len_arrays['SETcc']['1'][] = array('0' => 'mem',);
$opcode_len_result['SETcc']['1'][] = 3;  //0f 90+c /0]
$opcode_len_arrays['SETcc']['1'][] = array('0' => 'reg8',);
$opcode_len_result['SETcc']['1'][] = 3;  //0f 90+c /0]
$opcode_len_arrays['ADDSS']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm32',);
$opcode_len_result['ADDSS']['2'][] = 4;  //f3 0f 58 /r]
$opcode_len_arrays['CMPEQSS']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm32',);
$opcode_len_result['CMPEQSS']['2'][] = 5;  //f3 0f c2 /r 00]
$opcode_len_arrays['CMPLESS']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm32',);
$opcode_len_result['CMPLESS']['2'][] = 5;  //f3 0f c2 /r 02]
$opcode_len_arrays['CMPLTSS']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm32',);
$opcode_len_result['CMPLTSS']['2'][] = 5;  //f3 0f c2 /r 01]
$opcode_len_arrays['CMPNEQSS']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm32',);
$opcode_len_result['CMPNEQSS']['2'][] = 5;  //f3 0f c2 /r 04]
$opcode_len_arrays['CMPNLESS']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm32',);
$opcode_len_result['CMPNLESS']['2'][] = 5;  //f3 0f c2 /r 06]
$opcode_len_arrays['CMPNLTSS']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm32',);
$opcode_len_result['CMPNLTSS']['2'][] = 5;  //f3 0f c2 /r 05]
$opcode_len_arrays['CMPORDSS']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm32',);
$opcode_len_result['CMPORDSS']['2'][] = 5;  //f3 0f c2 /r 07]
$opcode_len_arrays['CMPUNORDSS']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm32',);
$opcode_len_result['CMPUNORDSS']['2'][] = 5;  //f3 0f c2 /r 03]
$opcode_len_arrays['CMPSS']['3'][] = array('0' => 'xmmreg','1' => 'mem','2' => 'imm',);
$opcode_len_result['CMPSS']['3'][] = 5;  //f3 0f c2 /r ib,u]
$opcode_len_arrays['CMPSS']['3'][] = array('0' => 'xmmreg','1' => 'xmmreg','2' => 'imm',);
$opcode_len_result['CMPSS']['3'][] = 5;  //f3 0f c2 /r ib,u]
$opcode_len_arrays['CVTSI2SS']['2'][] = array('0' => 'xmmreg','1' => 'mem',);
$opcode_len_result['CVTSI2SS']['2'][] = 4;  //f3 0f 2a /r]
$opcode_len_arrays['CVTSI2SS']['2'][] = array('0' => 'xmmreg','1' => 'rm32',);
$opcode_len_result['CVTSI2SS']['2'][] = 4;  //f3 0f 2a /r]
$opcode_len_arrays['CVTSS2SI']['2'][] = array('0' => 'reg32','1' => 'xmmreg',);
$opcode_len_result['CVTSS2SI']['2'][] = 4;  //f3 0f 2d /r]
$opcode_len_arrays['CVTSS2SI']['2'][] = array('0' => 'reg32','1' => 'mem',);
$opcode_len_result['CVTSS2SI']['2'][] = 4;  //f3 0f 2d /r]
$opcode_len_arrays['CVTTSS2SI']['2'][] = array('0' => 'reg32','1' => 'xmmrm',);
$opcode_len_result['CVTTSS2SI']['2'][] = 4;  //f3 0f 2c /r]
$opcode_len_arrays['DIVSS']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm32',);
$opcode_len_result['DIVSS']['2'][] = 4;  //f3 0f 5e /r]
$opcode_len_arrays['MAXSS']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm32',);
$opcode_len_result['MAXSS']['2'][] = 4;  //f3 0f 5f /r]
$opcode_len_arrays['MINSS']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm32',);
$opcode_len_result['MINSS']['2'][] = 4;  //f3 0f 5d /r]
$opcode_len_arrays['MOVSS']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm32',);
$opcode_len_result['MOVSS']['2'][] = 4;  //f3 0f 10 /r]
$opcode_len_arrays['MOVSS']['2'][] = array('0' => 'mem32','1' => 'xmmreg',);
$opcode_len_result['MOVSS']['2'][] = 4;  //f3 0f 11 /r]
$opcode_len_arrays['MOVSS']['2'][] = array('0' => 'xmmreg','1' => 'xmmreg',);
$opcode_len_result['MOVSS']['2'][] = 4;  //f3 0f 10 /r]
$opcode_len_arrays['MULSS']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm32',);
$opcode_len_result['MULSS']['2'][] = 4;  //f3 0f 59 /r]
$opcode_len_arrays['RCPSS']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm32',);
$opcode_len_result['RCPSS']['2'][] = 4;  //f3 0f 53 /r]
$opcode_len_arrays['RSQRTSS']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm32',);
$opcode_len_result['RSQRTSS']['2'][] = 4;  //f3 0f 52 /r]
$opcode_len_arrays['SQRTSS']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm32',);
$opcode_len_result['SQRTSS']['2'][] = 4;  //f3 0f 51 /r]
$opcode_len_arrays['SUBSS']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm32',);
$opcode_len_result['SUBSS']['2'][] = 4;  //f3 0f 5c /r]
$opcode_len_arrays['XGETBV']['0'][] = array('0' => 'void',);
$opcode_len_result['XGETBV']['0'][] = 3;  //0f 01 d0]
$opcode_len_arrays['XSETBV']['0'][] = array('0' => 'void',);
$opcode_len_result['XSETBV']['0'][] = 3;  //0f 01 d1]
$opcode_len_arrays['PREFETCHNTA']['1'][] = array('0' => 'mem8',);
$opcode_len_result['PREFETCHNTA']['1'][] = 3;  //0f 18 /0]
$opcode_len_arrays['PREFETCHT0']['1'][] = array('0' => 'mem8',);
$opcode_len_result['PREFETCHT0']['1'][] = 3;  //0f 18 /1]
$opcode_len_arrays['PREFETCHT1']['1'][] = array('0' => 'mem8',);
$opcode_len_result['PREFETCHT1']['1'][] = 3;  //0f 18 /2]
$opcode_len_arrays['PREFETCHT2']['1'][] = array('0' => 'mem8',);
$opcode_len_result['PREFETCHT2']['1'][] = 3;  //0f 18 /3]
$opcode_len_arrays['MASKMOVDQU']['2'][] = array('0' => 'xmmreg','1' => 'xmmreg',);
$opcode_len_result['MASKMOVDQU']['2'][] = 4;  //66 0f f7 /r]
$opcode_len_arrays['MOVNTDQ']['2'][] = array('0' => 'mem','1' => 'xmmreg',);
$opcode_len_result['MOVNTDQ']['2'][] = 4;  //66 0f e7 /r]
$opcode_len_arrays['MOVNTPD']['2'][] = array('0' => 'mem','1' => 'xmmreg',);
$opcode_len_result['MOVNTPD']['2'][] = 4;  //66 0f 2b /r]
$opcode_len_arrays['MOVDQA']['2'][] = array('0' => 'xmmreg','1' => 'xmmreg',);
$opcode_len_result['MOVDQA']['2'][] = 4;  //66 0f 6f /r]
$opcode_len_arrays['MOVDQA']['2'][] = array('0' => 'mem','1' => 'xmmreg',);
$opcode_len_result['MOVDQA']['2'][] = 4;  //66 0f 7f /r]
$opcode_len_arrays['MOVDQA']['2'][] = array('0' => 'xmmreg','1' => 'mem',);
$opcode_len_result['MOVDQA']['2'][] = 4;  //66 0f 6f /r]
$opcode_len_arrays['MOVDQA']['2'][] = array('0' => 'xmmreg','1' => 'xmmreg',);
$opcode_len_result['MOVDQA']['2'][] = 4;  //66 0f 7f /r]
$opcode_len_arrays['MOVDQU']['2'][] = array('0' => 'xmmreg','1' => 'xmmreg',);
$opcode_len_result['MOVDQU']['2'][] = 4;  //f3 0f 6f /r]
$opcode_len_arrays['MOVDQU']['2'][] = array('0' => 'mem','1' => 'xmmreg',);
$opcode_len_result['MOVDQU']['2'][] = 4;  //f3 0f 7f /r]
$opcode_len_arrays['MOVDQU']['2'][] = array('0' => 'xmmreg','1' => 'mem',);
$opcode_len_result['MOVDQU']['2'][] = 4;  //f3 0f 6f /r]
$opcode_len_arrays['MOVDQU']['2'][] = array('0' => 'xmmreg','1' => 'xmmreg',);
$opcode_len_result['MOVDQU']['2'][] = 4;  //f3 0f 7f /r]
$opcode_len_arrays['MOVDQ2Q']['2'][] = array('0' => 'mmxreg','1' => 'xmmreg',);
$opcode_len_result['MOVDQ2Q']['2'][] = 4;  //f2 0f d6 /r]
$opcode_len_arrays['MOVQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmreg',);
$opcode_len_result['MOVQ']['2'][] = 4;  //f3 0f 7e /r]
$opcode_len_arrays['MOVQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmreg',);
$opcode_len_result['MOVQ']['2'][] = 4;  //66 0f d6 /r]
$opcode_len_arrays['MOVQ']['2'][] = array('0' => 'mem','1' => 'xmmreg',);
$opcode_len_result['MOVQ']['2'][] = 4;  //66 0f d6 /r]
$opcode_len_arrays['MOVQ']['2'][] = array('0' => 'xmmreg','1' => 'mem',);
$opcode_len_result['MOVQ']['2'][] = 4;  //f3 0f 7e /r]
$opcode_len_arrays['MOVQ2DQ']['2'][] = array('0' => 'xmmreg','1' => 'mmxreg',);
$opcode_len_result['MOVQ2DQ']['2'][] = 4;  //f3 0f d6 /r]
$opcode_len_arrays['PACKSSWB']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PACKSSWB']['2'][] = 4;  //66 0f 63 /r]
$opcode_len_arrays['PACKSSDW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PACKSSDW']['2'][] = 4;  //66 0f 6b /r]
$opcode_len_arrays['PACKUSWB']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PACKUSWB']['2'][] = 4;  //66 0f 67 /r]
$opcode_len_arrays['PADDB']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PADDB']['2'][] = 4;  //66 0f fc /r]
$opcode_len_arrays['PADDW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PADDW']['2'][] = 4;  //66 0f fd /r]
$opcode_len_arrays['PADDD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PADDD']['2'][] = 4;  //66 0f fe /r]
$opcode_len_arrays['PADDQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PADDQ']['2'][] = 4;  //66 0f d4 /r]
$opcode_len_arrays['PADDSB']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PADDSB']['2'][] = 4;  //66 0f ec /r]
$opcode_len_arrays['PADDSW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PADDSW']['2'][] = 4;  //66 0f ed /r]
$opcode_len_arrays['PADDUSB']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PADDUSB']['2'][] = 4;  //66 0f dc /r]
$opcode_len_arrays['PADDUSW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PADDUSW']['2'][] = 4;  //66 0f dd /r]
$opcode_len_arrays['PAND']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PAND']['2'][] = 4;  //66 0f db /r]
$opcode_len_arrays['PANDN']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PANDN']['2'][] = 4;  //66 0f df /r]
$opcode_len_arrays['PAVGB']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PAVGB']['2'][] = 4;  //66 0f e0 /r]
$opcode_len_arrays['PAVGW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PAVGW']['2'][] = 4;  //66 0f e3 /r]
$opcode_len_arrays['PCMPEQB']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PCMPEQB']['2'][] = 4;  //66 0f 74 /r]
$opcode_len_arrays['PCMPEQW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PCMPEQW']['2'][] = 4;  //66 0f 75 /r]
$opcode_len_arrays['PCMPEQD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PCMPEQD']['2'][] = 4;  //66 0f 76 /r]
$opcode_len_arrays['PCMPGTB']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PCMPGTB']['2'][] = 4;  //66 0f 64 /r]
$opcode_len_arrays['PCMPGTW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PCMPGTW']['2'][] = 4;  //66 0f 65 /r]
$opcode_len_arrays['PCMPGTD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PCMPGTD']['2'][] = 4;  //66 0f 66 /r]
$opcode_len_arrays['PEXTRW']['3'][] = array('0' => 'reg32','1' => 'xmmreg','2' => 'imm',);
$opcode_len_result['PEXTRW']['3'][] = 5;  //66 0f c5 /r ib,u]
$opcode_len_arrays['PINSRW']['3'][] = array('0' => 'xmmreg','1' => 'reg16','2' => 'imm',);
$opcode_len_result['PINSRW']['3'][] = 5;  //66 0f c4 /r ib,u]
$opcode_len_arrays['PINSRW']['3'][] = array('0' => 'xmmreg','1' => 'reg32','2' => 'imm',);
$opcode_len_result['PINSRW']['3'][] = 5;  //66 0f c4 /r ib,u]
$opcode_len_arrays['PINSRW']['3'][] = array('0' => 'xmmreg','1' => 'mem','2' => 'imm',);
$opcode_len_result['PINSRW']['3'][] = 5;  //66 0f c4 /r ib,u]
$opcode_len_arrays['PINSRW']['3'][] = array('0' => 'xmmreg','1' => 'mem16','2' => 'imm',);
$opcode_len_result['PINSRW']['3'][] = 5;  //66 0f c4 /r ib,u]
$opcode_len_arrays['PMADDWD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMADDWD']['2'][] = 4;  //66 0f f5 /r]
$opcode_len_arrays['PMAXSW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMAXSW']['2'][] = 4;  //66 0f ee /r]
$opcode_len_arrays['PMAXUB']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMAXUB']['2'][] = 4;  //66 0f de /r]
$opcode_len_arrays['PMINSW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMINSW']['2'][] = 4;  //66 0f ea /r]
$opcode_len_arrays['PMINUB']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMINUB']['2'][] = 4;  //66 0f da /r]
$opcode_len_arrays['PMOVMSKB']['2'][] = array('0' => 'reg32','1' => 'xmmreg',);
$opcode_len_result['PMOVMSKB']['2'][] = 4;  //66 0f d7 /r]
$opcode_len_arrays['PMULHUW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMULHUW']['2'][] = 4;  //66 0f e4 /r]
$opcode_len_arrays['PMULHW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMULHW']['2'][] = 4;  //66 0f e5 /r]
$opcode_len_arrays['PMULLW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMULLW']['2'][] = 4;  //66 0f d5 /r]
$opcode_len_arrays['PMULUDQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMULUDQ']['2'][] = 4;  //66 0f f4 /r]
$opcode_len_arrays['POR']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['POR']['2'][] = 4;  //66 0f eb /r]
$opcode_len_arrays['PSADBW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PSADBW']['2'][] = 4;  //66 0f f6 /r]
$opcode_len_arrays['PSHUFD']['3'][] = array('0' => 'xmmreg','1' => 'xmmreg','2' => 'imm',);
$opcode_len_result['PSHUFD']['3'][] = 5;  //66 0f 70 /r ib]
$opcode_len_arrays['PSHUFD']['3'][] = array('0' => 'xmmreg','1' => 'mem','2' => 'imm',);
$opcode_len_result['PSHUFD']['3'][] = 5;  //66 0f 70 /r ib]
$opcode_len_arrays['PSHUFHW']['3'][] = array('0' => 'xmmreg','1' => 'xmmreg','2' => 'imm',);
$opcode_len_result['PSHUFHW']['3'][] = 5;  //f3 0f 70 /r ib]
$opcode_len_arrays['PSHUFHW']['3'][] = array('0' => 'xmmreg','1' => 'mem','2' => 'imm',);
$opcode_len_result['PSHUFHW']['3'][] = 5;  //f3 0f 70 /r ib]
$opcode_len_arrays['PSHUFLW']['3'][] = array('0' => 'xmmreg','1' => 'xmmreg','2' => 'imm',);
$opcode_len_result['PSHUFLW']['3'][] = 5;  //f2 0f 70 /r ib]
$opcode_len_arrays['PSHUFLW']['3'][] = array('0' => 'xmmreg','1' => 'mem','2' => 'imm',);
$opcode_len_result['PSHUFLW']['3'][] = 5;  //f2 0f 70 /r ib]
$opcode_len_arrays['PSLLDQ']['2'][] = array('0' => 'xmmreg','1' => 'imm',);
$opcode_len_result['PSLLDQ']['2'][] = 5;  //66 0f 73 /7 ib,u]
$opcode_len_arrays['PSLLW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PSLLW']['2'][] = 4;  //66 0f f1 /r]
$opcode_len_arrays['PSLLW']['2'][] = array('0' => 'xmmreg','1' => 'imm',);
$opcode_len_result['PSLLW']['2'][] = 5;  //66 0f 71 /6 ib,u]
$opcode_len_arrays['PSLLD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PSLLD']['2'][] = 4;  //66 0f f2 /r]
$opcode_len_arrays['PSLLD']['2'][] = array('0' => 'xmmreg','1' => 'imm',);
$opcode_len_result['PSLLD']['2'][] = 5;  //66 0f 72 /6 ib,u]
$opcode_len_arrays['PSLLQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PSLLQ']['2'][] = 4;  //66 0f f3 /r]
$opcode_len_arrays['PSLLQ']['2'][] = array('0' => 'xmmreg','1' => 'imm',);
$opcode_len_result['PSLLQ']['2'][] = 5;  //66 0f 73 /6 ib,u]
$opcode_len_arrays['PSRAW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PSRAW']['2'][] = 4;  //66 0f e1 /r]
$opcode_len_arrays['PSRAW']['2'][] = array('0' => 'xmmreg','1' => 'imm',);
$opcode_len_result['PSRAW']['2'][] = 5;  //66 0f 71 /4 ib,u]
$opcode_len_arrays['PSRAD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PSRAD']['2'][] = 4;  //66 0f e2 /r]
$opcode_len_arrays['PSRAD']['2'][] = array('0' => 'xmmreg','1' => 'imm',);
$opcode_len_result['PSRAD']['2'][] = 5;  //66 0f 72 /4 ib,u]
$opcode_len_arrays['PSRLDQ']['2'][] = array('0' => 'xmmreg','1' => 'imm',);
$opcode_len_result['PSRLDQ']['2'][] = 5;  //66 0f 73 /3 ib,u]
$opcode_len_arrays['PSRLW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PSRLW']['2'][] = 4;  //66 0f d1 /r]
$opcode_len_arrays['PSRLW']['2'][] = array('0' => 'xmmreg','1' => 'imm',);
$opcode_len_result['PSRLW']['2'][] = 5;  //66 0f 71 /2 ib,u]
$opcode_len_arrays['PSRLD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PSRLD']['2'][] = 4;  //66 0f d2 /r]
$opcode_len_arrays['PSRLD']['2'][] = array('0' => 'xmmreg','1' => 'imm',);
$opcode_len_result['PSRLD']['2'][] = 5;  //66 0f 72 /2 ib,u]
$opcode_len_arrays['PSRLQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PSRLQ']['2'][] = 4;  //66 0f d3 /r]
$opcode_len_arrays['PSRLQ']['2'][] = array('0' => 'xmmreg','1' => 'imm',);
$opcode_len_result['PSRLQ']['2'][] = 5;  //66 0f 73 /2 ib,u]
$opcode_len_arrays['PSUBB']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PSUBB']['2'][] = 4;  //66 0f f8 /r]
$opcode_len_arrays['PSUBW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PSUBW']['2'][] = 4;  //66 0f f9 /r]
$opcode_len_arrays['PSUBD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PSUBD']['2'][] = 4;  //66 0f fa /r]
$opcode_len_arrays['PSUBQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PSUBQ']['2'][] = 4;  //66 0f fb /r]
$opcode_len_arrays['PSUBSB']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PSUBSB']['2'][] = 4;  //66 0f e8 /r]
$opcode_len_arrays['PSUBSW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PSUBSW']['2'][] = 4;  //66 0f e9 /r]
$opcode_len_arrays['PSUBUSB']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PSUBUSB']['2'][] = 4;  //66 0f d8 /r]
$opcode_len_arrays['PSUBUSW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PSUBUSW']['2'][] = 4;  //66 0f d9 /r]
$opcode_len_arrays['PUNPCKHBW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PUNPCKHBW']['2'][] = 4;  //66 0f 68 /r]
$opcode_len_arrays['PUNPCKHWD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PUNPCKHWD']['2'][] = 4;  //66 0f 69 /r]
$opcode_len_arrays['PUNPCKHDQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PUNPCKHDQ']['2'][] = 4;  //66 0f 6a /r]
$opcode_len_arrays['PUNPCKHQDQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PUNPCKHQDQ']['2'][] = 4;  //66 0f 6d /r]
$opcode_len_arrays['PUNPCKLBW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PUNPCKLBW']['2'][] = 4;  //66 0f 60 /r]
$opcode_len_arrays['PUNPCKLWD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PUNPCKLWD']['2'][] = 4;  //66 0f 61 /r]
$opcode_len_arrays['PUNPCKLDQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PUNPCKLDQ']['2'][] = 4;  //66 0f 62 /r]
$opcode_len_arrays['PUNPCKLQDQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PUNPCKLQDQ']['2'][] = 4;  //66 0f 6c /r]
$opcode_len_arrays['PXOR']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PXOR']['2'][] = 4;  //66 0f ef /r]
$opcode_len_arrays['ADDPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['ADDPD']['2'][] = 4;  //66 0f 58 /r]
$opcode_len_arrays['ADDSD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['ADDSD']['2'][] = 4;  //f2 0f 58 /r]
$opcode_len_arrays['ANDNPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['ANDNPD']['2'][] = 4;  //66 0f 55 /r]
$opcode_len_arrays['ANDPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['ANDPD']['2'][] = 4;  //66 0f 54 /r]
$opcode_len_arrays['CMPEQPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['CMPEQPD']['2'][] = 5;  //66 0f c2 /r 00]
$opcode_len_arrays['CMPEQSD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['CMPEQSD']['2'][] = 5;  //f2 0f c2 /r 00]
$opcode_len_arrays['CMPLEPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['CMPLEPD']['2'][] = 5;  //66 0f c2 /r 02]
$opcode_len_arrays['CMPLESD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['CMPLESD']['2'][] = 5;  //f2 0f c2 /r 02]
$opcode_len_arrays['CMPLTPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['CMPLTPD']['2'][] = 5;  //66 0f c2 /r 01]
$opcode_len_arrays['CMPLTSD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['CMPLTSD']['2'][] = 5;  //f2 0f c2 /r 01]
$opcode_len_arrays['CMPNEQPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['CMPNEQPD']['2'][] = 5;  //66 0f c2 /r 04]
$opcode_len_arrays['CMPNEQSD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['CMPNEQSD']['2'][] = 5;  //f2 0f c2 /r 04]
$opcode_len_arrays['CMPNLEPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['CMPNLEPD']['2'][] = 5;  //66 0f c2 /r 06]
$opcode_len_arrays['CMPNLESD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['CMPNLESD']['2'][] = 5;  //f2 0f c2 /r 06]
$opcode_len_arrays['CMPNLTPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['CMPNLTPD']['2'][] = 5;  //66 0f c2 /r 05]
$opcode_len_arrays['CMPNLTSD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['CMPNLTSD']['2'][] = 5;  //f2 0f c2 /r 05]
$opcode_len_arrays['CMPORDPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['CMPORDPD']['2'][] = 5;  //66 0f c2 /r 07]
$opcode_len_arrays['CMPORDSD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['CMPORDSD']['2'][] = 5;  //f2 0f c2 /r 07]
$opcode_len_arrays['CMPUNORDPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['CMPUNORDPD']['2'][] = 5;  //66 0f c2 /r 03]
$opcode_len_arrays['CMPUNORDSD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['CMPUNORDSD']['2'][] = 5;  //f2 0f c2 /r 03]
$opcode_len_arrays['CMPPD']['3'][] = array('0' => 'xmmreg','1' => 'xmmrm128','2' => 'imm8',);
$opcode_len_result['CMPPD']['3'][] = 5;  //66 0f c2 /r ib,u]
$opcode_len_arrays['CMPSD']['3'][] = array('0' => 'xmmreg','1' => 'xmmrm128','2' => 'imm8',);
$opcode_len_result['CMPSD']['3'][] = 5;  //f2 0f c2 /r ib,u]
$opcode_len_arrays['COMISD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['COMISD']['2'][] = 4;  //66 0f 2f /r]
$opcode_len_arrays['CVTDQ2PD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['CVTDQ2PD']['2'][] = 4;  //f3 0f e6 /r]
$opcode_len_arrays['CVTPD2DQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['CVTPD2DQ']['2'][] = 4;  //f2 0f e6 /r]
$opcode_len_arrays['CVTPD2PI']['2'][] = array('0' => 'mmxreg','1' => 'xmmrm',);
$opcode_len_result['CVTPD2PI']['2'][] = 4;  //66 0f 2d /r]
$opcode_len_arrays['CVTPD2PS']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['CVTPD2PS']['2'][] = 4;  //66 0f 5a /r]
$opcode_len_arrays['CVTPI2PD']['2'][] = array('0' => 'xmmreg','1' => 'mmxrm',);
$opcode_len_result['CVTPI2PD']['2'][] = 4;  //66 0f 2a /r]
$opcode_len_arrays['CVTPS2DQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['CVTPS2DQ']['2'][] = 4;  //66 0f 5b /r]
$opcode_len_arrays['CVTSD2SS']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['CVTSD2SS']['2'][] = 4;  //f2 0f 5a /r]
$opcode_len_arrays['CVTSI2SD']['2'][] = array('0' => 'xmmreg','1' => 'mem',);
$opcode_len_result['CVTSI2SD']['2'][] = 4;  //f2 0f 2a /r]
$opcode_len_arrays['CVTSS2SD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['CVTSS2SD']['2'][] = 4;  //f3 0f 5a /r]
$opcode_len_arrays['CVTTPD2PI']['2'][] = array('0' => 'mmxreg','1' => 'xmmrm',);
$opcode_len_result['CVTTPD2PI']['2'][] = 4;  //66 0f 2c /r]
$opcode_len_arrays['CVTTPD2DQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['CVTTPD2DQ']['2'][] = 4;  //66 0f e6 /r]
$opcode_len_arrays['CVTTPS2DQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['CVTTPS2DQ']['2'][] = 4;  //f3 0f 5b /r]
$opcode_len_arrays['DIVPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['DIVPD']['2'][] = 4;  //66 0f 5e /r]
$opcode_len_arrays['DIVSD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['DIVSD']['2'][] = 4;  //f2 0f 5e /r]
$opcode_len_arrays['MAXPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['MAXPD']['2'][] = 4;  //66 0f 5f /r]
$opcode_len_arrays['MAXSD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['MAXSD']['2'][] = 4;  //f2 0f 5f /r]
$opcode_len_arrays['MINPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['MINPD']['2'][] = 4;  //66 0f 5d /r]
$opcode_len_arrays['MINSD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['MINSD']['2'][] = 4;  //f2 0f 5d /r]
$opcode_len_arrays['MOVAPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmreg',);
$opcode_len_result['MOVAPD']['2'][] = 4;  //66 0f 28 /r]
$opcode_len_arrays['MOVAPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmreg',);
$opcode_len_result['MOVAPD']['2'][] = 4;  //66 0f 29 /r]
$opcode_len_arrays['MOVAPD']['2'][] = array('0' => 'mem','1' => 'xmmreg',);
$opcode_len_result['MOVAPD']['2'][] = 4;  //66 0f 29 /r]
$opcode_len_arrays['MOVAPD']['2'][] = array('0' => 'xmmreg','1' => 'mem',);
$opcode_len_result['MOVAPD']['2'][] = 4;  //66 0f 28 /r]
$opcode_len_arrays['MOVHPD']['2'][] = array('0' => 'mem','1' => 'xmmreg',);
$opcode_len_result['MOVHPD']['2'][] = 4;  //66 0f 17 /r]
$opcode_len_arrays['MOVHPD']['2'][] = array('0' => 'xmmreg','1' => 'mem',);
$opcode_len_result['MOVHPD']['2'][] = 4;  //66 0f 16 /r]
$opcode_len_arrays['MOVLPD']['2'][] = array('0' => 'mem64','1' => 'xmmreg',);
$opcode_len_result['MOVLPD']['2'][] = 4;  //66 0f 13 /r]
$opcode_len_arrays['MOVLPD']['2'][] = array('0' => 'xmmreg','1' => 'mem64',);
$opcode_len_result['MOVLPD']['2'][] = 4;  //66 0f 12 /r]
$opcode_len_arrays['MOVMSKPD']['2'][] = array('0' => 'reg32','1' => 'xmmreg',);
$opcode_len_result['MOVMSKPD']['2'][] = 4;  //66 0f 50 /r]
$opcode_len_arrays['MOVSD']['2'][] = array('0' => 'xmmreg','1' => 'xmmreg',);
$opcode_len_result['MOVSD']['2'][] = 4;  //f2 0f 10 /r]
$opcode_len_arrays['MOVSD']['2'][] = array('0' => 'xmmreg','1' => 'xmmreg',);
$opcode_len_result['MOVSD']['2'][] = 4;  //f2 0f 11 /r]
$opcode_len_arrays['MOVSD']['2'][] = array('0' => 'mem64','1' => 'xmmreg',);
$opcode_len_result['MOVSD']['2'][] = 4;  //f2 0f 11 /r]
$opcode_len_arrays['MOVSD']['2'][] = array('0' => 'xmmreg','1' => 'mem64',);
$opcode_len_result['MOVSD']['2'][] = 4;  //f2 0f 10 /r]
$opcode_len_arrays['MOVUPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmreg',);
$opcode_len_result['MOVUPD']['2'][] = 4;  //66 0f 10 /r]
$opcode_len_arrays['MOVUPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmreg',);
$opcode_len_result['MOVUPD']['2'][] = 4;  //66 0f 11 /r]
$opcode_len_arrays['MOVUPD']['2'][] = array('0' => 'mem','1' => 'xmmreg',);
$opcode_len_result['MOVUPD']['2'][] = 4;  //66 0f 11 /r]
$opcode_len_arrays['MOVUPD']['2'][] = array('0' => 'xmmreg','1' => 'mem',);
$opcode_len_result['MOVUPD']['2'][] = 4;  //66 0f 10 /r]
$opcode_len_arrays['MULPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['MULPD']['2'][] = 4;  //66 0f 59 /r]
$opcode_len_arrays['MULSD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['MULSD']['2'][] = 4;  //f2 0f 59 /r]
$opcode_len_arrays['ORPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['ORPD']['2'][] = 4;  //66 0f 56 /r]
$opcode_len_arrays['SHUFPD']['3'][] = array('0' => 'xmmreg','1' => 'xmmreg','2' => 'imm',);
$opcode_len_result['SHUFPD']['3'][] = 5;  //66 0f c6 /r ib,u]
$opcode_len_arrays['SHUFPD']['3'][] = array('0' => 'xmmreg','1' => 'mem','2' => 'imm',);
$opcode_len_result['SHUFPD']['3'][] = 5;  //66 0f c6 /r ib,u]
$opcode_len_arrays['SQRTPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['SQRTPD']['2'][] = 4;  //66 0f 51 /r]
$opcode_len_arrays['SQRTSD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['SQRTSD']['2'][] = 4;  //f2 0f 51 /r]
$opcode_len_arrays['SUBPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['SUBPD']['2'][] = 4;  //66 0f 5c /r]
$opcode_len_arrays['SUBSD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['SUBSD']['2'][] = 4;  //f2 0f 5c /r]
$opcode_len_arrays['UCOMISD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['UCOMISD']['2'][] = 4;  //66 0f 2e /r]
$opcode_len_arrays['UNPCKHPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm128',);
$opcode_len_result['UNPCKHPD']['2'][] = 4;  //66 0f 15 /r]
$opcode_len_arrays['UNPCKLPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm128',);
$opcode_len_result['UNPCKLPD']['2'][] = 4;  //66 0f 14 /r]
$opcode_len_arrays['XORPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm128',);
$opcode_len_result['XORPD']['2'][] = 4;  //66 0f 57 /r]
$opcode_len_arrays['ADDSUBPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['ADDSUBPD']['2'][] = 4;  //66 0f d0 /r]
$opcode_len_arrays['ADDSUBPS']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['ADDSUBPS']['2'][] = 4;  //f2 0f d0 /r]
$opcode_len_arrays['HADDPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['HADDPD']['2'][] = 4;  //66 0f 7c /r]
$opcode_len_arrays['HADDPS']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['HADDPS']['2'][] = 4;  //f2 0f 7c /r]
$opcode_len_arrays['HSUBPD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['HSUBPD']['2'][] = 4;  //66 0f 7d /r]
$opcode_len_arrays['HSUBPS']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['HSUBPS']['2'][] = 4;  //f2 0f 7d /r]
$opcode_len_arrays['LDDQU']['2'][] = array('0' => 'xmmreg','1' => 'mem',);
$opcode_len_result['LDDQU']['2'][] = 4;  //f2 0f f0 /r]
$opcode_len_arrays['MOVDDUP']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['MOVDDUP']['2'][] = 4;  //f2 0f 12 /r]
$opcode_len_arrays['MOVSHDUP']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['MOVSHDUP']['2'][] = 4;  //f3 0f 16 /r]
$opcode_len_arrays['MOVSLDUP']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['MOVSLDUP']['2'][] = 4;  //f3 0f 12 /r]
$opcode_len_arrays['CLGI']['0'][] = array('0' => 'void',);
$opcode_len_result['CLGI']['0'][] = 3;  //0f 01 dd]
$opcode_len_arrays['STGI']['0'][] = array('0' => 'void',);
$opcode_len_result['STGI']['0'][] = 3;  //0f 01 dc]
$opcode_len_arrays['VMCALL']['0'][] = array('0' => 'void',);
$opcode_len_result['VMCALL']['0'][] = 3;  //0f 01 c1]
$opcode_len_arrays['VMCLEAR']['1'][] = array('0' => 'mem',);
$opcode_len_result['VMCLEAR']['1'][] = 4;  //66 0f c7 /6]
$opcode_len_arrays['VMFUNC']['0'][] = array('0' => 'void',);
$opcode_len_result['VMFUNC']['0'][] = 3;  //0f 01 d4]
$opcode_len_arrays['VMLAUNCH']['0'][] = array('0' => 'void',);
$opcode_len_result['VMLAUNCH']['0'][] = 3;  //0f 01 c2]
$opcode_len_arrays['VMLOAD']['0'][] = array('0' => 'void',);
$opcode_len_result['VMLOAD']['0'][] = 3;  //0f 01 da]
$opcode_len_arrays['VMMCALL']['0'][] = array('0' => 'void',);
$opcode_len_result['VMMCALL']['0'][] = 3;  //0f 01 d9]
$opcode_len_arrays['VMRESUME']['0'][] = array('0' => 'void',);
$opcode_len_result['VMRESUME']['0'][] = 3;  //0f 01 c3]
$opcode_len_arrays['VMRUN']['0'][] = array('0' => 'void',);
$opcode_len_result['VMRUN']['0'][] = 3;  //0f 01 d8]
$opcode_len_arrays['VMSAVE']['0'][] = array('0' => 'void',);
$opcode_len_result['VMSAVE']['0'][] = 3;  //0f 01 db]
$opcode_len_arrays['VMXOFF']['0'][] = array('0' => 'void',);
$opcode_len_result['VMXOFF']['0'][] = 3;  //0f 01 c4]
$opcode_len_arrays['VMXON']['1'][] = array('0' => 'mem',);
$opcode_len_result['VMXON']['1'][] = 4;  //f3 0f c7 /6]
$opcode_len_arrays['PABSB']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PABSB']['2'][] = 5;  //66 0f 38 1c /r]
$opcode_len_arrays['PABSW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PABSW']['2'][] = 5;  //66 0f 38 1d /r]
$opcode_len_arrays['PABSD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PABSD']['2'][] = 5;  //66 0f 38 1e /r]
$opcode_len_arrays['PALIGNR']['3'][] = array('0' => 'xmmreg','1' => 'xmmrm','2' => 'imm',);
$opcode_len_result['PALIGNR']['3'][] = 6;  //66 0f 3a 0f /r ib,u]
$opcode_len_arrays['PHADDW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PHADDW']['2'][] = 5;  //66 0f 38 01 /r]
$opcode_len_arrays['PHADDD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PHADDD']['2'][] = 5;  //66 0f 38 02 /r]
$opcode_len_arrays['PHADDSW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PHADDSW']['2'][] = 5;  //66 0f 38 03 /r]
$opcode_len_arrays['PHSUBW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PHSUBW']['2'][] = 5;  //66 0f 38 05 /r]
$opcode_len_arrays['PHSUBD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PHSUBD']['2'][] = 5;  //66 0f 38 06 /r]
$opcode_len_arrays['PHSUBSW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PHSUBSW']['2'][] = 5;  //66 0f 38 07 /r]
$opcode_len_arrays['PMADDUBSW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMADDUBSW']['2'][] = 5;  //66 0f 38 04 /r]
$opcode_len_arrays['PMULHRSW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMULHRSW']['2'][] = 5;  //66 0f 38 0b /r]
$opcode_len_arrays['PSHUFB']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PSHUFB']['2'][] = 5;  //66 0f 38 00 /r]
$opcode_len_arrays['PSIGNB']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PSIGNB']['2'][] = 5;  //66 0f 38 08 /r]
$opcode_len_arrays['PSIGNW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PSIGNW']['2'][] = 5;  //66 0f 38 09 /r]
$opcode_len_arrays['PSIGND']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PSIGND']['2'][] = 5;  //66 0f 38 0a /r]
$opcode_len_arrays['EXTRQ']['3'][] = array('0' => 'xmmreg','1' => 'imm','2' => 'imm',);
$opcode_len_result['EXTRQ']['3'][] = 6;  //66 0f 78 /0 ib,u ib,u]
$opcode_len_arrays['EXTRQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmreg',);
$opcode_len_result['EXTRQ']['2'][] = 4;  //66 0f 79 /r]
$opcode_len_arrays['INSERTQ']['4'][] = array('0' => 'xmmreg','1' => 'xmmreg','2' => 'imm','3' => 'imm',);
$opcode_len_result['INSERTQ']['4'][] = 6;  //f2 0f 78 /r ib,u ib,u]
$opcode_len_arrays['INSERTQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmreg',);
$opcode_len_result['INSERTQ']['2'][] = 4;  //f2 0f 79 /r]
$opcode_len_arrays['MOVNTSD']['2'][] = array('0' => 'mem','1' => 'xmmreg',);
$opcode_len_result['MOVNTSD']['2'][] = 4;  //f2 0f 2b /r]
$opcode_len_arrays['MOVNTSS']['2'][] = array('0' => 'mem','1' => 'xmmreg',);
$opcode_len_result['MOVNTSS']['2'][] = 4;  //f3 0f 2b /r]
$opcode_len_arrays['LZCNT']['2'][] = array('0' => 'reg16','1' => 'rm16',);
$opcode_len_result['LZCNT']['2'][] = 4;  //o16 f3i 0f bd /r]
$opcode_len_arrays['LZCNT']['2'][] = array('0' => 'reg32','1' => 'rm32',);
$opcode_len_result['LZCNT']['2'][] = 3;  //o32 f3i 0f bd /r]
$opcode_len_arrays['BLENDPD']['3'][] = array('0' => 'xmmreg','1' => 'xmmrm','2' => 'imm',);
$opcode_len_result['BLENDPD']['3'][] = 6;  //66 0f 3a 0d /r ib,u]
$opcode_len_arrays['BLENDPS']['3'][] = array('0' => 'xmmreg','1' => 'xmmrm','2' => 'imm',);
$opcode_len_result['BLENDPS']['3'][] = 6;  //66 0f 3a 0c /r ib,u]
$opcode_len_arrays['BLENDVPD']['3'][] = array('0' => 'xmmreg','1' => 'xmmrm','2' => 'xmm0',);
$opcode_len_result['BLENDVPD']['3'][] = 5;  //66 0f 38 15 /r]
$opcode_len_arrays['BLENDVPS']['3'][] = array('0' => 'xmmreg','1' => 'xmmrm','2' => 'xmm0',);
$opcode_len_result['BLENDVPS']['3'][] = 5;  //66 0f 38 14 /r]
$opcode_len_arrays['DPPD']['3'][] = array('0' => 'xmmreg','1' => 'xmmrm','2' => 'imm',);
$opcode_len_result['DPPD']['3'][] = 6;  //66 0f 3a 41 /r ib,u]
$opcode_len_arrays['DPPS']['3'][] = array('0' => 'xmmreg','1' => 'xmmrm','2' => 'imm',);
$opcode_len_result['DPPS']['3'][] = 6;  //66 0f 3a 40 /r ib,u]
$opcode_len_arrays['EXTRACTPS']['3'][] = array('0' => 'rm32','1' => 'xmmreg','2' => 'imm',);
$opcode_len_result['EXTRACTPS']['3'][] = 6;  //66 0f 3a 17 /r ib,u]
$opcode_len_arrays['INSERTPS']['3'][] = array('0' => 'xmmreg','1' => 'xmmrm','2' => 'imm',);
$opcode_len_result['INSERTPS']['3'][] = 6;  //66 0f 3a 21 /r ib,u]
$opcode_len_arrays['MOVNTDQA']['2'][] = array('0' => 'xmmreg','1' => 'mem128',);
$opcode_len_result['MOVNTDQA']['2'][] = 5;  //66 0f 38 2a /r]
$opcode_len_arrays['MPSADBW']['3'][] = array('0' => 'xmmreg','1' => 'xmmrm','2' => 'imm',);
$opcode_len_result['MPSADBW']['3'][] = 6;  //66 0f 3a 42 /r ib,u]
$opcode_len_arrays['PACKUSDW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PACKUSDW']['2'][] = 5;  //66 0f 38 2b /r]
$opcode_len_arrays['PBLENDVB']['3'][] = array('0' => 'xmmreg','1' => 'xmmrm','2' => 'xmm0',);
$opcode_len_result['PBLENDVB']['3'][] = 5;  //66 0f 38 10 /r]
$opcode_len_arrays['PBLENDW']['3'][] = array('0' => 'xmmreg','1' => 'xmmrm','2' => 'imm',);
$opcode_len_result['PBLENDW']['3'][] = 6;  //66 0f 3a 0e /r ib,u]
$opcode_len_arrays['PCMPEQQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PCMPEQQ']['2'][] = 5;  //66 0f 38 29 /r]
$opcode_len_arrays['PEXTRB']['3'][] = array('0' => 'reg32','1' => 'xmmreg','2' => 'imm',);
$opcode_len_result['PEXTRB']['3'][] = 6;  //66 0f 3a 14 /r ib,u]
$opcode_len_arrays['PEXTRB']['3'][] = array('0' => 'mem8','1' => 'xmmreg','2' => 'imm',);
$opcode_len_result['PEXTRB']['3'][] = 6;  //66 0f 3a 14 /r ib,u]
$opcode_len_arrays['PEXTRW']['3'][] = array('0' => 'reg32','1' => 'xmmreg','2' => 'imm',);
$opcode_len_result['PEXTRW']['3'][] = 6;  //66 0f 3a 15 /r ib,u]
$opcode_len_arrays['PEXTRW']['3'][] = array('0' => 'mem16','1' => 'xmmreg','2' => 'imm',);
$opcode_len_result['PEXTRW']['3'][] = 6;  //66 0f 3a 15 /r ib,u]
$opcode_len_arrays['PHMINPOSUW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PHMINPOSUW']['2'][] = 5;  //66 0f 38 41 /r]
$opcode_len_arrays['PINSRB']['3'][] = array('0' => 'xmmreg','1' => 'mem','2' => 'imm',);
$opcode_len_result['PINSRB']['3'][] = 6;  //66 0f 3a 20 /r ib,u]
$opcode_len_arrays['PINSRB']['3'][] = array('0' => 'xmmreg','1' => 'reg32','2' => 'imm',);
$opcode_len_result['PINSRB']['3'][] = 6;  //66 0f 3a 20 /r ib,u]
$opcode_len_arrays['PMAXSB']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMAXSB']['2'][] = 5;  //66 0f 38 3c /r]
$opcode_len_arrays['PMAXSD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMAXSD']['2'][] = 5;  //66 0f 38 3d /r]
$opcode_len_arrays['PMAXUD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMAXUD']['2'][] = 5;  //66 0f 38 3f /r]
$opcode_len_arrays['PMAXUW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMAXUW']['2'][] = 5;  //66 0f 38 3e /r]
$opcode_len_arrays['PMINSB']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMINSB']['2'][] = 5;  //66 0f 38 38 /r]
$opcode_len_arrays['PMINSD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMINSD']['2'][] = 5;  //66 0f 38 39 /r]
$opcode_len_arrays['PMINUD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMINUD']['2'][] = 5;  //66 0f 38 3b /r]
$opcode_len_arrays['PMINUW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMINUW']['2'][] = 5;  //66 0f 38 3a /r]
$opcode_len_arrays['PMOVSXBW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMOVSXBW']['2'][] = 5;  //66 0f 38 20 /r]
$opcode_len_arrays['PMOVSXBD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMOVSXBD']['2'][] = 5;  //66 0f 38 21 /r]
$opcode_len_arrays['PMOVSXBQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMOVSXBQ']['2'][] = 5;  //66 0f 38 22 /r]
$opcode_len_arrays['PMOVSXWD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMOVSXWD']['2'][] = 5;  //66 0f 38 23 /r]
$opcode_len_arrays['PMOVSXWQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMOVSXWQ']['2'][] = 5;  //66 0f 38 24 /r]
$opcode_len_arrays['PMOVSXDQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMOVSXDQ']['2'][] = 5;  //66 0f 38 25 /r]
$opcode_len_arrays['PMOVZXBW']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMOVZXBW']['2'][] = 5;  //66 0f 38 30 /r]
$opcode_len_arrays['PMOVZXBD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMOVZXBD']['2'][] = 5;  //66 0f 38 31 /r]
$opcode_len_arrays['PMOVZXBQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMOVZXBQ']['2'][] = 5;  //66 0f 38 32 /r]
$opcode_len_arrays['PMOVZXWD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMOVZXWD']['2'][] = 5;  //66 0f 38 33 /r]
$opcode_len_arrays['PMOVZXWQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMOVZXWQ']['2'][] = 5;  //66 0f 38 34 /r]
$opcode_len_arrays['PMOVZXDQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMOVZXDQ']['2'][] = 5;  //66 0f 38 35 /r]
$opcode_len_arrays['PMULDQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMULDQ']['2'][] = 5;  //66 0f 38 28 /r]
$opcode_len_arrays['PMULLD']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PMULLD']['2'][] = 5;  //66 0f 38 40 /r]
$opcode_len_arrays['PTEST']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PTEST']['2'][] = 5;  //66 0f 38 17 /r]
$opcode_len_arrays['ROUNDPD']['3'][] = array('0' => 'xmmreg','1' => 'xmmrm','2' => 'imm',);
$opcode_len_result['ROUNDPD']['3'][] = 6;  //66 0f 3a 09 /r ib,u]
$opcode_len_arrays['ROUNDPS']['3'][] = array('0' => 'xmmreg','1' => 'xmmrm','2' => 'imm',);
$opcode_len_result['ROUNDPS']['3'][] = 6;  //66 0f 3a 08 /r ib,u]
$opcode_len_arrays['ROUNDSD']['3'][] = array('0' => 'xmmreg','1' => 'xmmrm','2' => 'imm',);
$opcode_len_result['ROUNDSD']['3'][] = 6;  //66 0f 3a 0b /r ib,u]
$opcode_len_arrays['ROUNDSS']['3'][] = array('0' => 'xmmreg','1' => 'xmmrm','2' => 'imm',);
$opcode_len_result['ROUNDSS']['3'][] = 6;  //66 0f 3a 0a /r ib,u]
$opcode_len_arrays['PCMPESTRI']['3'][] = array('0' => 'xmmreg','1' => 'xmmrm','2' => 'imm',);
$opcode_len_result['PCMPESTRI']['3'][] = 6;  //66 0f 3a 61 /r ib,u]
$opcode_len_arrays['PCMPESTRM']['3'][] = array('0' => 'xmmreg','1' => 'xmmrm','2' => 'imm',);
$opcode_len_result['PCMPESTRM']['3'][] = 6;  //66 0f 3a 60 /r ib,u]
$opcode_len_arrays['PCMPISTRI']['3'][] = array('0' => 'xmmreg','1' => 'xmmrm','2' => 'imm',);
$opcode_len_result['PCMPISTRI']['3'][] = 6;  //66 0f 3a 63 /r ib,u]
$opcode_len_arrays['PCMPISTRM']['3'][] = array('0' => 'xmmreg','1' => 'xmmrm','2' => 'imm',);
$opcode_len_result['PCMPISTRM']['3'][] = 6;  //66 0f 3a 62 /r ib,u]
$opcode_len_arrays['PCMPGTQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm',);
$opcode_len_result['PCMPGTQ']['2'][] = 5;  //66 0f 38 37 /r]
$opcode_len_arrays['POPCNT']['2'][] = array('0' => 'reg16','1' => 'rm16',);
$opcode_len_result['POPCNT']['2'][] = 4;  //o16 f3i 0f b8 /r]
$opcode_len_arrays['POPCNT']['2'][] = array('0' => 'reg32','1' => 'rm32',);
$opcode_len_result['POPCNT']['2'][] = 3;  //o32 f3i 0f b8 /r]
$opcode_len_arrays['GETSEC']['0'][] = array('0' => 'void',);
$opcode_len_result['GETSEC']['0'][] = 2;  //0f 37]
$opcode_len_arrays['MOVBE']['2'][] = array('0' => 'reg16','1' => 'mem16',);
$opcode_len_result['MOVBE']['2'][] = 5;  //o16 norep 0f 38 f0 /r]
$opcode_len_arrays['MOVBE']['2'][] = array('0' => 'reg32','1' => 'mem32',);
$opcode_len_result['MOVBE']['2'][] = 4;  //o32 norep 0f 38 f0 /r]
$opcode_len_arrays['MOVBE']['2'][] = array('0' => 'mem16','1' => 'reg16',);
$opcode_len_result['MOVBE']['2'][] = 5;  //o16 norep 0f 38 f1 /r]
$opcode_len_arrays['MOVBE']['2'][] = array('0' => 'mem32','1' => 'reg32',);
$opcode_len_result['MOVBE']['2'][] = 4;  //o32 norep 0f 38 f1 /r]
$opcode_len_arrays['AESENC']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm128',);
$opcode_len_result['AESENC']['2'][] = 5;  //66 0f 38 dc /r]
$opcode_len_arrays['AESENCLAST']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm128',);
$opcode_len_result['AESENCLAST']['2'][] = 5;  //66 0f 38 dd /r]
$opcode_len_arrays['AESDEC']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm128',);
$opcode_len_result['AESDEC']['2'][] = 5;  //66 0f 38 de /r]
$opcode_len_arrays['AESDECLAST']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm128',);
$opcode_len_result['AESDECLAST']['2'][] = 5;  //66 0f 38 df /r]
$opcode_len_arrays['AESIMC']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm128',);
$opcode_len_result['AESIMC']['2'][] = 5;  //66 0f 38 db /r]
$opcode_len_arrays['AESKEYGENASSIST']['3'][] = array('0' => 'xmmreg','1' => 'xmmrm128','2' => 'imm8',);
$opcode_len_result['AESKEYGENASSIST']['3'][] = 6;  //66 0f 3a df /r ib]
$opcode_len_arrays['PCLMULLQLQDQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm128',);
$opcode_len_result['PCLMULLQLQDQ']['2'][] = 6;  //66 0f 3a 44 /r 00]
$opcode_len_arrays['PCLMULHQLQDQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm128',);
$opcode_len_result['PCLMULHQLQDQ']['2'][] = 6;  //66 0f 3a 44 /r 01]
$opcode_len_arrays['PCLMULLQHQDQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm128',);
$opcode_len_result['PCLMULLQHQDQ']['2'][] = 6;  //66 0f 3a 44 /r 10]
$opcode_len_arrays['PCLMULHQHQDQ']['2'][] = array('0' => 'xmmreg','1' => 'xmmrm128',);
$opcode_len_result['PCLMULHQHQDQ']['2'][] = 6;  //66 0f 3a 44 /r 11]
$opcode_len_arrays['PCLMULQDQ']['3'][] = array('0' => 'xmmreg','1' => 'xmmrm128','2' => 'imm8',);
$opcode_len_result['PCLMULQDQ']['3'][] = 6;  //66 0f 3a 44 /r ib]
$opcode_len_arrays['RDRAND']['1'][] = array('0' => 'reg16',);
$opcode_len_result['RDRAND']['1'][] = 4;  //o16 0f c7 /6]
$opcode_len_arrays['RDRAND']['1'][] = array('0' => 'reg32',);
$opcode_len_result['RDRAND']['1'][] = 3;  //o32 0f c7 /6]
$opcode_len_arrays['RDSEED']['1'][] = array('0' => 'reg16',);
$opcode_len_result['RDSEED']['1'][] = 4;  //o16 0f c7 /7]
$opcode_len_arrays['RDSEED']['1'][] = array('0' => 'reg32',);
$opcode_len_result['RDSEED']['1'][] = 3;  //o32 0f c7 /7]
$opcode_len_arrays['CLAC']['0'][] = array('0' => 'void',);
$opcode_len_result['CLAC']['0'][] = 3;  //0f 01 ca]
$opcode_len_arrays['STAC']['0'][] = array('0' => 'void',);
$opcode_len_result['STAC']['0'][] = 3;  //0f 01 cb]
$opcode_len_arrays['XSTORE']['0'][] = array('0' => 'void',);
$opcode_len_result['XSTORE']['0'][] = 3;  //0f a7 c0]
$opcode_len_arrays['XABORT']['1'][] = array('0' => 'imm',);
$opcode_len_result['XABORT']['1'][] = 3;  //c6 f8 ib]
$opcode_len_arrays['XABORT']['1'][] = array('0' => 'imm8',);
$opcode_len_result['XABORT']['1'][] = 3;  //c6 f8 ib]
$opcode_len_arrays['XEND']['0'][] = array('0' => 'void',);
$opcode_len_result['XEND']['0'][] = 3;  //0f 01 d5]
$opcode_len_arrays['XTEST']['0'][] = array('0' => 'void',);
$opcode_len_result['XTEST']['0'][] = 3;  //0f 01 d6]
$opcode_len_arrays['TZCNT']['2'][] = array('0' => 'reg16','1' => 'rm16',);
$opcode_len_result['TZCNT']['2'][] = 4;  //o16 f3i 0f bc /r]
$opcode_len_arrays['TZCNT']['2'][] = array('0' => 'reg32','1' => 'rm32',);
$opcode_len_result['TZCNT']['2'][] = 3;  //o32 f3i 0f bc /r]
$opcode_len_arrays['HINT_NOP0']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP0']['1'][] = 4;  //o16 0f 18 /0]
$opcode_len_arrays['HINT_NOP0']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP0']['1'][] = 3;  //o32 0f 18 /0]
$opcode_len_arrays['HINT_NOP1']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP1']['1'][] = 4;  //o16 0f 18 /1]
$opcode_len_arrays['HINT_NOP1']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP1']['1'][] = 3;  //o32 0f 18 /1]
$opcode_len_arrays['HINT_NOP2']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP2']['1'][] = 4;  //o16 0f 18 /2]
$opcode_len_arrays['HINT_NOP2']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP2']['1'][] = 3;  //o32 0f 18 /2]
$opcode_len_arrays['HINT_NOP3']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP3']['1'][] = 4;  //o16 0f 18 /3]
$opcode_len_arrays['HINT_NOP3']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP3']['1'][] = 3;  //o32 0f 18 /3]
$opcode_len_arrays['HINT_NOP4']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP4']['1'][] = 4;  //o16 0f 18 /4]
$opcode_len_arrays['HINT_NOP4']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP4']['1'][] = 3;  //o32 0f 18 /4]
$opcode_len_arrays['HINT_NOP5']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP5']['1'][] = 4;  //o16 0f 18 /5]
$opcode_len_arrays['HINT_NOP5']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP5']['1'][] = 3;  //o32 0f 18 /5]
$opcode_len_arrays['HINT_NOP6']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP6']['1'][] = 4;  //o16 0f 18 /6]
$opcode_len_arrays['HINT_NOP6']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP6']['1'][] = 3;  //o32 0f 18 /6]
$opcode_len_arrays['HINT_NOP7']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP7']['1'][] = 4;  //o16 0f 18 /7]
$opcode_len_arrays['HINT_NOP7']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP7']['1'][] = 3;  //o32 0f 18 /7]
$opcode_len_arrays['HINT_NOP8']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP8']['1'][] = 4;  //o16 0f 19 /0]
$opcode_len_arrays['HINT_NOP8']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP8']['1'][] = 3;  //o32 0f 19 /0]
$opcode_len_arrays['HINT_NOP9']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP9']['1'][] = 4;  //o16 0f 19 /1]
$opcode_len_arrays['HINT_NOP9']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP9']['1'][] = 3;  //o32 0f 19 /1]
$opcode_len_arrays['HINT_NOP10']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP10']['1'][] = 4;  //o16 0f 19 /2]
$opcode_len_arrays['HINT_NOP10']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP10']['1'][] = 3;  //o32 0f 19 /2]
$opcode_len_arrays['HINT_NOP11']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP11']['1'][] = 4;  //o16 0f 19 /3]
$opcode_len_arrays['HINT_NOP11']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP11']['1'][] = 3;  //o32 0f 19 /3]
$opcode_len_arrays['HINT_NOP12']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP12']['1'][] = 4;  //o16 0f 19 /4]
$opcode_len_arrays['HINT_NOP12']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP12']['1'][] = 3;  //o32 0f 19 /4]
$opcode_len_arrays['HINT_NOP13']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP13']['1'][] = 4;  //o16 0f 19 /5]
$opcode_len_arrays['HINT_NOP13']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP13']['1'][] = 3;  //o32 0f 19 /5]
$opcode_len_arrays['HINT_NOP14']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP14']['1'][] = 4;  //o16 0f 19 /6]
$opcode_len_arrays['HINT_NOP14']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP14']['1'][] = 3;  //o32 0f 19 /6]
$opcode_len_arrays['HINT_NOP15']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP15']['1'][] = 4;  //o16 0f 19 /7]
$opcode_len_arrays['HINT_NOP15']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP15']['1'][] = 3;  //o32 0f 19 /7]
$opcode_len_arrays['HINT_NOP16']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP16']['1'][] = 4;  //o16 0f 1a /0]
$opcode_len_arrays['HINT_NOP16']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP16']['1'][] = 3;  //o32 0f 1a /0]
$opcode_len_arrays['HINT_NOP17']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP17']['1'][] = 4;  //o16 0f 1a /1]
$opcode_len_arrays['HINT_NOP17']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP17']['1'][] = 3;  //o32 0f 1a /1]
$opcode_len_arrays['HINT_NOP18']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP18']['1'][] = 4;  //o16 0f 1a /2]
$opcode_len_arrays['HINT_NOP18']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP18']['1'][] = 3;  //o32 0f 1a /2]
$opcode_len_arrays['HINT_NOP19']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP19']['1'][] = 4;  //o16 0f 1a /3]
$opcode_len_arrays['HINT_NOP19']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP19']['1'][] = 3;  //o32 0f 1a /3]
$opcode_len_arrays['HINT_NOP20']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP20']['1'][] = 4;  //o16 0f 1a /4]
$opcode_len_arrays['HINT_NOP20']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP20']['1'][] = 3;  //o32 0f 1a /4]
$opcode_len_arrays['HINT_NOP21']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP21']['1'][] = 4;  //o16 0f 1a /5]
$opcode_len_arrays['HINT_NOP21']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP21']['1'][] = 3;  //o32 0f 1a /5]
$opcode_len_arrays['HINT_NOP22']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP22']['1'][] = 4;  //o16 0f 1a /6]
$opcode_len_arrays['HINT_NOP22']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP22']['1'][] = 3;  //o32 0f 1a /6]
$opcode_len_arrays['HINT_NOP23']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP23']['1'][] = 4;  //o16 0f 1a /7]
$opcode_len_arrays['HINT_NOP23']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP23']['1'][] = 3;  //o32 0f 1a /7]
$opcode_len_arrays['HINT_NOP24']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP24']['1'][] = 4;  //o16 0f 1b /0]
$opcode_len_arrays['HINT_NOP24']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP24']['1'][] = 3;  //o32 0f 1b /0]
$opcode_len_arrays['HINT_NOP25']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP25']['1'][] = 4;  //o16 0f 1b /1]
$opcode_len_arrays['HINT_NOP25']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP25']['1'][] = 3;  //o32 0f 1b /1]
$opcode_len_arrays['HINT_NOP26']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP26']['1'][] = 4;  //o16 0f 1b /2]
$opcode_len_arrays['HINT_NOP26']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP26']['1'][] = 3;  //o32 0f 1b /2]
$opcode_len_arrays['HINT_NOP27']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP27']['1'][] = 4;  //o16 0f 1b /3]
$opcode_len_arrays['HINT_NOP27']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP27']['1'][] = 3;  //o32 0f 1b /3]
$opcode_len_arrays['HINT_NOP28']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP28']['1'][] = 4;  //o16 0f 1b /4]
$opcode_len_arrays['HINT_NOP28']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP28']['1'][] = 3;  //o32 0f 1b /4]
$opcode_len_arrays['HINT_NOP29']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP29']['1'][] = 4;  //o16 0f 1b /5]
$opcode_len_arrays['HINT_NOP29']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP29']['1'][] = 3;  //o32 0f 1b /5]
$opcode_len_arrays['HINT_NOP30']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP30']['1'][] = 4;  //o16 0f 1b /6]
$opcode_len_arrays['HINT_NOP30']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP30']['1'][] = 3;  //o32 0f 1b /6]
$opcode_len_arrays['HINT_NOP31']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP31']['1'][] = 4;  //o16 0f 1b /7]
$opcode_len_arrays['HINT_NOP31']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP31']['1'][] = 3;  //o32 0f 1b /7]
$opcode_len_arrays['HINT_NOP32']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP32']['1'][] = 4;  //o16 0f 1c /0]
$opcode_len_arrays['HINT_NOP32']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP32']['1'][] = 3;  //o32 0f 1c /0]
$opcode_len_arrays['HINT_NOP33']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP33']['1'][] = 4;  //o16 0f 1c /1]
$opcode_len_arrays['HINT_NOP33']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP33']['1'][] = 3;  //o32 0f 1c /1]
$opcode_len_arrays['HINT_NOP34']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP34']['1'][] = 4;  //o16 0f 1c /2]
$opcode_len_arrays['HINT_NOP34']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP34']['1'][] = 3;  //o32 0f 1c /2]
$opcode_len_arrays['HINT_NOP35']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP35']['1'][] = 4;  //o16 0f 1c /3]
$opcode_len_arrays['HINT_NOP35']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP35']['1'][] = 3;  //o32 0f 1c /3]
$opcode_len_arrays['HINT_NOP36']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP36']['1'][] = 4;  //o16 0f 1c /4]
$opcode_len_arrays['HINT_NOP36']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP36']['1'][] = 3;  //o32 0f 1c /4]
$opcode_len_arrays['HINT_NOP37']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP37']['1'][] = 4;  //o16 0f 1c /5]
$opcode_len_arrays['HINT_NOP37']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP37']['1'][] = 3;  //o32 0f 1c /5]
$opcode_len_arrays['HINT_NOP38']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP38']['1'][] = 4;  //o16 0f 1c /6]
$opcode_len_arrays['HINT_NOP38']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP38']['1'][] = 3;  //o32 0f 1c /6]
$opcode_len_arrays['HINT_NOP39']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP39']['1'][] = 4;  //o16 0f 1c /7]
$opcode_len_arrays['HINT_NOP39']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP39']['1'][] = 3;  //o32 0f 1c /7]
$opcode_len_arrays['HINT_NOP40']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP40']['1'][] = 4;  //o16 0f 1d /0]
$opcode_len_arrays['HINT_NOP40']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP40']['1'][] = 3;  //o32 0f 1d /0]
$opcode_len_arrays['HINT_NOP41']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP41']['1'][] = 4;  //o16 0f 1d /1]
$opcode_len_arrays['HINT_NOP41']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP41']['1'][] = 3;  //o32 0f 1d /1]
$opcode_len_arrays['HINT_NOP42']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP42']['1'][] = 4;  //o16 0f 1d /2]
$opcode_len_arrays['HINT_NOP42']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP42']['1'][] = 3;  //o32 0f 1d /2]
$opcode_len_arrays['HINT_NOP43']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP43']['1'][] = 4;  //o16 0f 1d /3]
$opcode_len_arrays['HINT_NOP43']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP43']['1'][] = 3;  //o32 0f 1d /3]
$opcode_len_arrays['HINT_NOP44']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP44']['1'][] = 4;  //o16 0f 1d /4]
$opcode_len_arrays['HINT_NOP44']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP44']['1'][] = 3;  //o32 0f 1d /4]
$opcode_len_arrays['HINT_NOP45']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP45']['1'][] = 4;  //o16 0f 1d /5]
$opcode_len_arrays['HINT_NOP45']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP45']['1'][] = 3;  //o32 0f 1d /5]
$opcode_len_arrays['HINT_NOP46']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP46']['1'][] = 4;  //o16 0f 1d /6]
$opcode_len_arrays['HINT_NOP46']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP46']['1'][] = 3;  //o32 0f 1d /6]
$opcode_len_arrays['HINT_NOP47']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP47']['1'][] = 4;  //o16 0f 1d /7]
$opcode_len_arrays['HINT_NOP47']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP47']['1'][] = 3;  //o32 0f 1d /7]
$opcode_len_arrays['HINT_NOP48']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP48']['1'][] = 4;  //o16 0f 1e /0]
$opcode_len_arrays['HINT_NOP48']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP48']['1'][] = 3;  //o32 0f 1e /0]
$opcode_len_arrays['HINT_NOP49']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP49']['1'][] = 4;  //o16 0f 1e /1]
$opcode_len_arrays['HINT_NOP49']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP49']['1'][] = 3;  //o32 0f 1e /1]
$opcode_len_arrays['HINT_NOP50']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP50']['1'][] = 4;  //o16 0f 1e /2]
$opcode_len_arrays['HINT_NOP50']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP50']['1'][] = 3;  //o32 0f 1e /2]
$opcode_len_arrays['HINT_NOP51']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP51']['1'][] = 4;  //o16 0f 1e /3]
$opcode_len_arrays['HINT_NOP51']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP51']['1'][] = 3;  //o32 0f 1e /3]
$opcode_len_arrays['HINT_NOP52']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP52']['1'][] = 4;  //o16 0f 1e /4]
$opcode_len_arrays['HINT_NOP52']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP52']['1'][] = 3;  //o32 0f 1e /4]
$opcode_len_arrays['HINT_NOP53']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP53']['1'][] = 4;  //o16 0f 1e /5]
$opcode_len_arrays['HINT_NOP53']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP53']['1'][] = 3;  //o32 0f 1e /5]
$opcode_len_arrays['HINT_NOP54']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP54']['1'][] = 4;  //o16 0f 1e /6]
$opcode_len_arrays['HINT_NOP54']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP54']['1'][] = 3;  //o32 0f 1e /6]
$opcode_len_arrays['HINT_NOP55']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP55']['1'][] = 4;  //o16 0f 1e /7]
$opcode_len_arrays['HINT_NOP55']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP55']['1'][] = 3;  //o32 0f 1e /7]
$opcode_len_arrays['HINT_NOP56']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP56']['1'][] = 4;  //o16 0f 1f /0]
$opcode_len_arrays['HINT_NOP56']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP56']['1'][] = 3;  //o32 0f 1f /0]
$opcode_len_arrays['HINT_NOP57']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP57']['1'][] = 4;  //o16 0f 1f /1]
$opcode_len_arrays['HINT_NOP57']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP57']['1'][] = 3;  //o32 0f 1f /1]
$opcode_len_arrays['HINT_NOP58']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP58']['1'][] = 4;  //o16 0f 1f /2]
$opcode_len_arrays['HINT_NOP58']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP58']['1'][] = 3;  //o32 0f 1f /2]
$opcode_len_arrays['HINT_NOP59']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP59']['1'][] = 4;  //o16 0f 1f /3]
$opcode_len_arrays['HINT_NOP59']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP59']['1'][] = 3;  //o32 0f 1f /3]
$opcode_len_arrays['HINT_NOP60']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP60']['1'][] = 4;  //o16 0f 1f /4]
$opcode_len_arrays['HINT_NOP60']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP60']['1'][] = 3;  //o32 0f 1f /4]
$opcode_len_arrays['HINT_NOP61']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP61']['1'][] = 4;  //o16 0f 1f /5]
$opcode_len_arrays['HINT_NOP61']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP61']['1'][] = 3;  //o32 0f 1f /5]
$opcode_len_arrays['HINT_NOP62']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP62']['1'][] = 4;  //o16 0f 1f /6]
$opcode_len_arrays['HINT_NOP62']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP62']['1'][] = 3;  //o32 0f 1f /6]
$opcode_len_arrays['HINT_NOP63']['1'][] = array('0' => 'rm16',);
$opcode_len_result['HINT_NOP63']['1'][] = 4;  //o16 0f 1f /7]
$opcode_len_arrays['HINT_NOP63']['1'][] = array('0' => 'rm32',);
$opcode_len_result['HINT_NOP63']['1'][] = 3;  //o32 0f 1f /7]

?>