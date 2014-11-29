<?php

ini_set('display_errors',0);
error_reporting(E_ERROR);

require dirname(__FILE__)."/language.inc.php";

//用户可设置变量部分
$user_option['del_last_nop'] = true; //自动去掉节表末尾用来对齐的 nop 以及 0xcch

///////////////////////////////////////////
//当前系统命令行 最长 字符 长度 见readme  2013/04/19
define ('ARG_MAX',5000);                  
//////////////////////////////////////////
define('UNEST.ORG', TRUE);
////////////////////////////////////////////
//ready -> generate 中间文件版本号,避免ready生成的rdy文件被不匹配的generat处理
define ('ENGIN_VER',6);                  
//////////////////////////////////////////
//organs Templates Version
define('MEAT_TPL_VER',1);
define('BONE_TPL_VER',1);
define('POLY_TPL_VER',1);

//////////////////////////////////////////
//character types
  define ('CTPL_OPT',1); //指令 
//define ('CTPL_POS',2); //位置 (如：前中后)
//define ('CTPL_PRM',3); //参数 (如：有无整数)

//////////////////////////////////////////
//inst array index
define ('PREFIX',           1);
define ('OPERATION',        2);
define ('P_TYPE',           3);
define ('P_BITS',           4);
define ('PARAMS',           5);
define ('REL',              6);
define ('STACK',            7);
define ('P_M_REG',          8);
//soul usable
define ('P',                  9);
define ('N',                 10);
define ('FLAG_WRITE_ABLE',   11);
define ('NORMAL_WRITE_ABLE', 12);
define ('MEM_OPT_ABLE',      13);
//$soul_forbid
define ('FLAG',              14);
define ('NORMAL',            15);

//inst struction
define ('CODE',16);
define ('OPT', 17);
define ('BITS',18);
define ('REG', 19);

//List
define ('C',    20);
define ('LABEL',21);
//////////////////////////////////////////
//organs 
define ('MEAT',22);
define ('BONE',23);
define ('POLY',24);
define ('SOUL',25);

//others
define ('USABLE',26);

//poly templates
define ('FAT',            27);
define ('OOO',            28);
define ('DRAND',          29);
define ('RAND_PRIVILEGE', 30);
define ('R_FORBID',       31);
define ('P_FORBID',       32);
define ('NEW_REGS',       33);
define ('SPECIFIC_USABLE',34);
define ('FLAG_FORBID',    35);
define ('REL_RESET',      36);

?>