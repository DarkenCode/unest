<?php

if(!defined('UNEST.ORG')) {
	exit('Access Denied');
}



$bone_model_index       = array(1);


$bone_model_index_multi = array(1,2);




$bone_model_repo[1] = array(

	'direct' => array(  
	                    
	                    
	    '7' => 2,       
		'3' => 0,
	),
	'fat'  => array(
	    '0' => 2, 
		'2' => 1, 
		'4' => 2,
		'6' => 1

	),
	'process' => array(           
	    0 => array(
			0 => array ('p' => 0),    
			1 => array ('n' => 6),    
			2 => array ('s' => 7),    
			3 => array ('p' => 8),    
			4 => array ('n' => 2),    
			5 => array ('s' => 3),    
			6 => array ('p' => 4),    
			7 => array ('n' => 8),    
        ),
	),

    'ipsp' => array(             
	    '2' => 2,                
		'3' => true,             
		'4' => 1,                
	),


	'code' => array(
			'0' => array(
					'operation' => "JMP",
					'params' => array('0' => "SOLID_JMP_6_FROM_0_B"),      
					'p_type' => array('0' => 'i'),
			),
			'2' => array(
					'label' => "SOLID_JMP_2_FROM_8_B",				
			),
			'3' => true,       
			'4' => array(
					'operation' => "RET",
			),
			'6' => array(			        
					'label' => "SOLID_JMP_6_FROM_0_B",
			),
			'7' => true,       
			'8' => array(
					'operation' => "CALL",
					'params' => array('0' => "SOLID_JMP_2_FROM_8_B"),
					'p_type' => array('0' => 'i'),
			),
		),
);




$bone_model_repo[2] = array(

    'fat'  => array(
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
			0 => array ('p' => 0),    
			1 => array ('n' => 6),
			2 => array ('s' => 7),
			3 => array ('p' => 8),
			4 => array ('n' => 8),
		),       
		1 => array(
			0 => array ('p' => 0),    
			1 => array ('n' => 0),  
			2 => array ('s' => 3),
			3 => array ('p' => 4),
			4 => array ('n' => 8),		  		
		),
	),


	'code' => array(
			'0' => array(
					'operation' => "Jcc",
					'params' => array('0' => "SOLID_JMP_6_FROM_0_B"),      
			),			
			'3' => true,       
			'4' => array(
					'operation' => "JMP",
					'params' => array('0' => "SOLID_JMP_8_FROM_4_B"),      
					'p_type' => array('0' => 'i'),
			),
			'6' => array(			        
					'label' => "SOLID_JMP_6_FROM_0_B",
			),
			'7' => true,       
			'8' => array(
					'label' => "SOLID_JMP_8_FROM_4_B",				
			),
		),
);




?>