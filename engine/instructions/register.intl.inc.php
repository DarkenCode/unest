<?php


//标志寄存器  1: status flag  2:control flag  3:system flag(不全)
$eflags = array('OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,'DF' => 2,'TF' => 3,'IF' => 3,'NT' => 3,'RF' => 3);  

//段寄存器
$segment = array('CS' => 'CS', 'DS' => 'DS', 'SS' => 'SS', 'ES' => 'ES', 'FS' => 'FS', 'GS' => 'GS');


//通用寄存器 (相关寄存器在同一数组中)
$general_register[] = array( 8 => 'AL' , 9 => 'AH' , 16 => 'AX' , 32 => 'EAX' ,);
$general_register[] = array( 8 => 'BL' , 9 => 'BH' , 16 => 'BX' , 32 => 'EBX' ,);
$general_register[] = array( 8 => 'CL' , 9 => 'CH' , 16 => 'CX' , 32 => 'ECX' ,);
$general_register[] = array( 8 => 'DL' , 9 => 'DH' , 16 => 'DX' , 32 => 'EDX' ,);
$general_register[] = array(                         16 => 'BP' , 32 => 'EBP' ,);
$general_register[] = array(                         16 => 'SI' , 32 => 'ESI' ,);
$general_register[] = array(                         16 => 'DI' , 32 => 'EDI' ,);
$general_register[] = array(                         16 => 'SP' , 32 => 'ESP' ,);
$general_register[] = array(                         16 => 'IP' , 32 => 'EIP' ,);

?>