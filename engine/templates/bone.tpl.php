<?php

if(!defined('UNEST.ORG')) {
	exit('Access Denied');
}



$bone_model_index       = array(1);


$bone_model_index_multi = array(1,1,2);


$bone_multi_max_size = 50;




$bone_model_repo[1] = array(

	'direct' => array(  
	                    
	                    
	    '7' => 2,       
		'3' => 0,
	),
	FAT  => array(
	    '0' => 2, 
		'2' => 1, 
		'4' => 2,
		'6' => 1

	),
	'process' => array(           
	    0 => array(
			0 => array (P => 0),    
			1 => array (N => 6),    
			2 => array ('s' => 7),    
			3 => array (P => 8),    
			4 => array (N => 2),    
			5 => array ('s' => 3),    
			6 => array (P => 4),    
			7 => array (N => 8),    
        ),
	),

    'ipsp' => array(             
	    '2' => 2,                
		'3' => true,             
		'4' => 1,                
	),


	CODE => array(
			'0' => array(
					OPERATION => "JMP",
					PARAMS => array('0' => "SOLID_JMP_6_FROM_0_B"),      
					P_TYPE => array('0' => 'i'),
			),
			'2' => array(
					LABEL => "SOLID_JMP_2_FROM_8_B",				
			),
			'3' => true,       
			'4' => array(
					OPERATION => "RET",
			),
			'6' => array(			        
					LABEL => "SOLID_JMP_6_FROM_0_B",
			),
			'7' => true,       
			'8' => array(
					OPERATION => "CALL",
					PARAMS => array('0' => "SOLID_JMP_2_FROM_8_B"),
					P_TYPE => array('0' => 'i'),
			),
		),
);




$bone_model_repo[2] = array(

    FAT  => array(
	    '4' => 2, 
		'6' => 1  
	),

	'direct' => array(
		'3' => 1,
	),

	'copy' => array(
	    3 => 7
	),

	'process' => array(           
	    0 => array(
			0 => array (P => 0),    
			1 => array (N => 6),
			2 => array ('s' => 7),
			3 => array (P => 8),
			4 => array (N => 8),
		),       
		1 => array(
			0 => array (P => 0),    
			1 => array (N => 0),  
			2 => array ('s' => 3),
			3 => array (P => 4),
			4 => array (N => 8),		  		
		),
	),


	CODE => array(
			'0' => array(
					OPERATION => "Jcc",
					PARAMS => array('0' => "SOLID_JMP_6_FROM_0_B"),      
			),			
			'3' => true,       
			'4' => array(
					OPERATION => "JMP",
					PARAMS => array('0' => "SOLID_JMP_8_FROM_4_B"),      
					P_TYPE => array('0' => 'i'),
			),
			'6' => array(			        
					LABEL => "SOLID_JMP_6_FROM_0_B",
			),
			'7' => true,       
			'8' => array(
					LABEL => "SOLID_JMP_8_FROM_4_B",				
			),
		),
);




?>