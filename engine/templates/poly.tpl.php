<?php

if(!defined('UNEST.ORG')) {
	exit('Access Denied');
}







$poly_model_index['CALL'][1]['rel20'] = array(1,2);
$poly_model_index['CALL'][1]['r32']   = array(1,2);
$poly_model_index['CALL'][1]['m32']   = array(1,2);

$poly_model_index['JMP'][1]['i']       = array(1,2,3,4,5,6,7,8);
$poly_model_index['JMP'][1]['rel20']   = array(1,2,3,4,5,6,7,8);




$poly_model_index['MOV'][2]['ESP']['i']    = false;
$poly_model_index['MOV'][2]['r32']['i']    = array(1,2);
$poly_model_index['MOV'][2]['m32']['i']    = array(1,2);

$poly_model_index['ADD'][2]['ESP']['i'] = false;
$poly_model_index['ADD'][2]['r32']['i'] = array(1,1,1,2,2,2,3,3,3,4,5,6,7);
$poly_model_index['ADD'][2]['m32']['i'] = array(1,1,1,2,2,2,3,3,3,4,5,6,7);

$poly_model_index['SUB'][2]['ESP']['i'] = false;
$poly_model_index['SUB'][2]['r32']['i'] = array(1,1,1,2,2,2,3,3,3,4,5,6,7);
$poly_model_index['SUB'][2]['m32']['i'] = array(1,1,1,2,2,2,3,3,3,4,5,6,7);

$poly_model_index['INC'][1]['ESP'] = false;
$poly_model_index['INC'][1]['r32'] = array(1,2);
$poly_model_index['INC'][1]['m32'] = array(1,2);

$poly_model_index['DEC'][1]['ESP'] = false;
$poly_model_index['DEC'][1]['r32'] = array(1,2);
$poly_model_index['DEC'][1]['m32'] = array(1,2);

$poly_model_index['CMP'][2]['r32']['i'] = array(1,2,3);
$poly_model_index['CMP'][2]['r32']['r32'] = array(1,2,3);
$poly_model_index['CMP'][2]['m32']['i'] = array(1,2,3);
$poly_model_index['CMP'][2]['m32']['r32'] = array(1,2,3);



$poly_model_index['RET'][0] = array(1);
$poly_model_index['RET'][1]['i'] = array(2);

$poly_model_index['PUSH'][1]['i'] = array(1,2);













$poly_model_repo['PUSH'][1] = array( 	
    DRAND => array(
	    '0' => array('m32','r32'),		
	),
	RAND_PRIVILEGE => array(
            '0' => 2,
    ),
	CODE => array (	    
	    '2' => array(
		        OPERATION => 'MOV',
				PARAMS    => array('r_0','p_0'),
		),
	    '7' => array(
		        OPERATION => 'PUSH',
				PARAMS    => array('r_0'),
		),
    ), 	

	P_BITS => array(
	    '2' => array (1 => 32),
	),

	R_FORBID => array(             
		P => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		N => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
);

$poly_model_repo['PUSH'][2] = array( 	
    DRAND => array(
	    '0' => array('i','r32'),		
	),
	CODE => array (	    
	    '2' => array(
		        OPERATION => 'PUSH',
				PARAMS    => array('r_0'),
		),
	    '7' => array(
		        OPERATION => 'MOV',
				PARAMS    => array('[ESP]','p_0'),
		),
    ), 		
	P_TYPE => array(                   
	    '7' => array (0 => 'm'),
	),
	P_BITS => array(                   
	    '7' => array (0 => 32),
	),
);



$poly_model_repo['RET'][1] = array( 	
   


    DRAND => array(
	    '0' => array('m32','r32'),		
	),
	RAND_PRIVILEGE => array(
            '0' => 2,
    ),
	CODE => array (
	    '2' => array(
		        OPERATION => 'POP',
				PARAMS    => array('r_0'),
		),
	    '7' => array(
		        OPERATION => 'JMP',
				PARAMS    => array('r_0'),
		),
    ), 	
	R_FORBID => array(             
		P => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		N => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
);

$poly_model_repo['RET'][2] = array( 	
    DRAND => array(
	    '0' => array('r32'),		
	),	

	RAND_PRIVILEGE => array(
            '0' => 3,
    ),

	CODE => array (
	    '1' => array(
		        OPERATION => 'POP',
				PARAMS    => array('r_0'),
		),
	    '3' => array(
		        OPERATION => 'ADD',
				PARAMS    => array('ESP','p_0'),
		),
		'5' => array (
		       OPERATION => 'JMP',
			   PARAMS    => array ('r_0'),
		),
    ),
	
	P_TYPE => array(             
	    '3' => array (0 => 'r'),
	),

	P_BITS => array(              
	    '3' => array (32),
	),

    R_FORBID => array(        
	    P => array(
  	      '5' => array(
		    '0' => 1,
		  ),
		  '3' => array(
		    '0' => 1,
		  ),
		),
		N => array(
		  '3' => array(
			'0' => 1,   
		  ),
		  '1' => array(
			'0' => 1,   
		  ),
		),
	),

    P_FORBID => array(
		P => array(
		  '3' => array(
			'0' => 1,			
		  ),
		),
		N => array(
		  '1' => array(
			'0' => 1,   
		  ),
		),
	),

	NEW_REGS => array(           
		FLAG => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),

);



$poly_model_repo['CMP'][1] = array(
	CODE => array (
	    '2' => array(
		        OPERATION => 'SUB',
				PARAMS    => array('p_0','p_1'),
		),
    ), 	
    NEW_REGS => array(           
		NORMAL => array(
		    '0'  => 1,
		),
	),
);

$poly_model_repo['CMP'][2] = array(
    DRAND => array(
	    '0' => array('r32'),		
	),
	RAND_PRIVILEGE => array(
            '0' => 2,
    ),
	CODE => array (
	    '2' => array(
		        OPERATION => 'MOV',
				PARAMS    => array('r_0','p_0'),
		),
		'7' => array(
		        OPERATION => 'CMP',
				PARAMS    => array('r_0','p_1'),
		),
    ), 	
	R_FORBID => array(             
	    P => array(
  	      '7' => array(
		    '0' => 1,
		  ),
		),
		N => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
);

$poly_model_repo['CMP'][3] = array(
    DRAND => array(
	    '0' => array('r32'),		
	),
	RAND_PRIVILEGE => array(
            '0' => 3,
    ),
	CODE => array (
	    '1' => array(
		        OPERATION => 'MOV',
				PARAMS    => array('r_0','p_1'),
		),
		'3' => array(
		        OPERATION => 'CMP',
				PARAMS    => array('p_0','r_0'),
		),
    ), 	

	P_FORBID => array(             
	    P => array(
  	      '3' => array(
		    '0' => 1,
		  ),
		),
		N => array(
		  '1' => array(
			'0' => 1,   
		  ),
		),
	),

	R_FORBID => array( 
	    P => array(
  	      '3' => array(
		    '0' => 1,
		  ),
		),
		N => array(
		  '1' => array(
			'0' => 1,   
		  ),
		),
	),
);



$poly_model_repo['DEC'][1] = array(
	CODE => array (
	    '2' => array(
		        OPERATION => 'ADD',
				PARAMS    => array('p_0','-1'),
		),
    ),
	P_TYPE => array(                   
	    '2' => array (1 => 'i'),
	),
	P_BITS => array(                  
	    '2' => array (32,32),
	),	
    NEW_REGS => array(           
	    FLAG => array(
		    'CF' => 1
		),
	),
);
$poly_model_repo['DEC'][2] = array(
	CODE => array (
	    '2' => array(
		        OPERATION => 'SUB',
				PARAMS    => array('p_0','1'),
		),
    ),
	P_TYPE => array(                   
	    '2' => array (1 => 'i'),
	),
	P_BITS => array(                  
	    '2' => array (32,32),
	),	
    NEW_REGS => array(           
	    FLAG => array(
		    'CF' => 1
		),
	),
);



$poly_model_repo['INC'][1] = array(
	CODE => array (
	    '2' => array(
		        OPERATION => 'ADD',
				PARAMS    => array('p_0','1'),
		),
    ),
	P_TYPE => array(                   
	    '2' => array (1 => 'i'),
	),
	P_BITS => array(                  
	    '2' => array (32,32),
	),	
    NEW_REGS => array(           
	    FLAG => array(
		    'CF' => 1
		),
	),
);
$poly_model_repo['INC'][2] = array(
	CODE => array (
	    '2' => array(
		        OPERATION => 'SUB',
				PARAMS    => array('p_0','-1'),
		),
    ),
	P_TYPE => array(                   
	    '2' => array (1 => 'i'),
	),
	P_BITS => array(                  
	    '2' => array (32,32),
	),	
    NEW_REGS => array(           
	    FLAG => array(
		    'CF' => 1
		),
	),
);



$poly_model_repo['SUB'][1] = array(
 	OOO => array(             
		2,7
	),
    DRAND => array(
	    '0' => array( 'i'),		
	),
	CODE => array (
	    '2' => array(
		        OPERATION => 'ADD',
				PARAMS    => array('p_0','r_0'),
		),
		'7' => array(
		        OPERATION => 'SUB',
				PARAMS    => array('p_0','p_1 + r_0'),
		),   
    ),
	P_TYPE => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	P_BITS => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	P_FORBID => array(             
		P => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		N => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    NEW_REGS => array(           
	    FLAG => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);

$poly_model_repo['SUB'][2] = array(
    DRAND => array(
	    '0' => array( 'i'),		
	),
	CODE => array (
	    '2' => array(
		        OPERATION => 'ADD',
				PARAMS    => array('p_0','- ( p_1 ) - r_0'),
		),
		'7' => array(
		        OPERATION => 'ADD',
				PARAMS    => array('p_0','r_0'),
		),   
    ),
	P_TYPE => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	P_BITS => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	P_FORBID => array(             
		P => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		N => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    NEW_REGS => array(           
	    FLAG => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);

$poly_model_repo['SUB'][3] = array(
    DRAND => array(
	    '0' => array( 'i'),		
	),
	CODE => array (
	    '2' => array(
		        OPERATION => 'SUB',
				PARAMS    => array('p_0','p_1 - r_0'),
		),
		'7' => array(
		        OPERATION => 'SUB',
				PARAMS    => array('p_0','r_0'),
		),   
    ),
	P_TYPE => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	P_BITS => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	P_FORBID => array(             
		P => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		N => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    NEW_REGS => array(           
	    FLAG => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);


$poly_model_repo['SUB'][4] = array(
 	OOO => array(             
		2,7
	),
	CODE => array (
	    '2' => array(
		        OPERATION => 'ADD',
				PARAMS    => array('p_0','- ( p_1 ) - 1'),
		),
		'7' => array(
		        OPERATION => 'INC',
				PARAMS    => array('p_0'),
		),   
    ),
	P_TYPE => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	P_BITS => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	P_FORBID => array(             
		P => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		N => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    NEW_REGS => array(           
	    FLAG => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);


$poly_model_repo['SUB'][5] = array(
 	OOO => array(             
		2,7
	),
	CODE => array (
	    '2' => array(
		        OPERATION => 'ADD',
				PARAMS    => array('p_0','- ( p_1 ) + 1'),
		),
		'7' => array(
		        OPERATION => 'DEC',
				PARAMS    => array('p_0'),
		),   
    ),
	P_TYPE => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	P_BITS => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	P_FORBID => array(             
		P => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		N => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    NEW_REGS => array(           
	    FLAG => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);


$poly_model_repo['SUB'][6] = array(
 	OOO => array(             
		2,7
	),
	CODE => array (
	    '2' => array(
		        OPERATION => 'SUB',
				PARAMS    => array('p_0','p_1 + 1'),
		),
		'7' => array(
		        OPERATION => 'INC',
				PARAMS    => array('p_0'),
		),   
    ),
	P_TYPE => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	P_BITS => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	P_FORBID => array(             
		P => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		N => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    NEW_REGS => array(           
	    FLAG => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);

$poly_model_repo['SUB'][7] = array(
 	OOO => array(             
		2,7
	),
	CODE => array (
	    '2' => array(
		        OPERATION => 'SUB',
				PARAMS    => array('p_0','p_1 - 1'),
		),
		'7' => array(
		        OPERATION => 'DEC',
				PARAMS    => array('p_0'),
		),   
    ),
	P_TYPE => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	P_BITS => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	P_FORBID => array(             
		P => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		N => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    NEW_REGS => array(           
	    FLAG => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);


$poly_model_repo['SUB'][8] = array(
    DRAND => array(
	    '0' => array('r32'),		
	),
	CODE => array (
	    '2' => array(
		        OPERATION => 'MOV',
				PARAMS    => array('r_0','p_1'),
		),
		'7' => array(
		        OPERATION => 'SUB',
				PARAMS    => array('p_0','r_0'),
		),   
    ),	
	R_FORBID => array(             
	    P => array(
  	      '7' => array(
		    '0' => 1,
		  ),
		),
		N => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
   
);



$poly_model_repo['ADD'][1] = array(
 	OOO => array(             
		2,7
	),
    DRAND => array(
	    '0' => array( 'i'),		
	),
	CODE => array (
	    '2' => array(
		        OPERATION => 'ADD',
				PARAMS    => array('p_0','p_1 + r_0'),
		),
		'7' => array(
		        OPERATION => 'SUB',
				PARAMS    => array('p_0','r_0'),
		),   
    ),
	P_TYPE => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	P_BITS => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	P_FORBID => array(             
		P => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		N => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    NEW_REGS => array(           
	    FLAG => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);

$poly_model_repo['ADD'][2] = array(
    DRAND => array(
	    '0' => array( 'i'),		
	),
	CODE => array (
	    '2' => array(
		        OPERATION => 'ADD',
				PARAMS    => array('p_0','p_1 - r_0'),
		),
		'7' => array(
		        OPERATION => 'ADD',
				PARAMS    => array('p_0','r_0'),
		),   
    ),
	P_TYPE => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	P_BITS => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	P_FORBID => array(             
		P => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		N => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    NEW_REGS => array(           
	    FLAG => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);

$poly_model_repo['ADD'][3] = array(
    DRAND => array(
	    '0' => array( 'i'),		
	),
	CODE => array (
	    '2' => array(
		        OPERATION => 'SUB',
				PARAMS    => array('p_0','- ( p_1 ) - r_0'),
		),
		'7' => array(
		        OPERATION => 'SUB',
				PARAMS    => array('p_0','r_0'),
		),   
    ),
	P_TYPE => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	P_BITS => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	P_FORBID => array(             
		P => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		N => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    NEW_REGS => array(           
	    FLAG => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);


$poly_model_repo['ADD'][4] = array(
 	OOO => array(             
		2,7
	),
	CODE => array (
	    '2' => array(
		        OPERATION => 'ADD',
				PARAMS    => array('p_0',' p_1 - 1'),
		),
		'7' => array(
		        OPERATION => 'INC',
				PARAMS    => array('p_0'),
		),   
    ),
	P_TYPE => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	P_BITS => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	P_FORBID => array(             
		P => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		N => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    NEW_REGS => array(           
	    FLAG => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);


$poly_model_repo['ADD'][5] = array(
 	OOO => array(             
		2,7
	),
	CODE => array (
	    '2' => array(
		        OPERATION => 'ADD',
				PARAMS    => array('p_0',' p_1 + 1'),
		),
		'7' => array(
		        OPERATION => 'DEC',
				PARAMS    => array('p_0'),
		),   
    ),
	P_TYPE => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	P_BITS => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	P_FORBID => array(             
		P => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		N => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    NEW_REGS => array(           
	    FLAG => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);


$poly_model_repo['ADD'][6] = array(
 	OOO => array(             
		2,7
	),
	CODE => array (
	    '2' => array(
		        OPERATION => 'SUB',
				PARAMS    => array('p_0','- ( p_1 ) + 1'),
		),
		'7' => array(
		        OPERATION => 'INC',
				PARAMS    => array('p_0'),
		),   
    ),
	P_TYPE => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	P_BITS => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	P_FORBID => array(             
		P => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		N => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    NEW_REGS => array(           
	    FLAG => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);

$poly_model_repo['ADD'][7] = array(
 	OOO => array(             
		2,7
	),
	CODE => array (
	    '2' => array(
		        OPERATION => 'SUB',
				PARAMS    => array('p_0','- ( p_1 ) - 1'),
		),
		'7' => array(
		        OPERATION => 'DEC',
				PARAMS    => array('p_0'),
		),   
    ),
	P_TYPE => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	P_BITS => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	P_FORBID => array(             
		P => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		N => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    NEW_REGS => array(           
	    FLAG => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);



$poly_model_repo['MOV'][1] = array(
    DRAND => array(
	    '0' => array( 'i'),		
	),
	CODE => array (
	    '2' => array(
		        OPERATION => 'MOV',
				PARAMS    => array('p_0','p_1 + r_0'),
		),
		'7' => array(
		        OPERATION => 'SUB',
				PARAMS    => array('p_0','r_0'),
		),   
    ),
	P_TYPE => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	P_BITS => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	P_FORBID => array(             
		P => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		N => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    NEW_REGS => array(           
	    FLAG => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);


$poly_model_repo['MOV'][2] = array(
    DRAND => array(
	    '0' => array( 'i'),		
	),	
	CODE => array (
	    '2' => array(
		        OPERATION => 'MOV',
				PARAMS    => array('p_0','p_1 - r_0'),
		),
		'7' => array(
		        OPERATION => 'ADD',
				PARAMS    => array('p_0','r_0'),
		),   
    ),
	P_TYPE => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	P_BITS => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),

	P_FORBID => array(             
		P => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		N => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    NEW_REGS => array(           
	    FLAG => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);






$poly_model_repo['CALL'][1] = array(
    
    REL_RESET  => array( 
	    '0' => array (
		    'Type'    => 6,
		    'isLabel' => false,
		), 
	),
	
	FAT  => array(
	    '7'  => 2, 
		'8'  => 1,
		'11' => 2,
		'12' => 1  
	),

	CODE => array(
			'2' => array(
					OPERATION => "CALL",
					PARAMS => array("SOLID_JMP_8_FROM_2_P"),
			),
			'7' => array(
					OPERATION => "JMP",
					PARAMS => array("SOLID_JMP_12_FROM_7_P"),
			),
			'8' => array(
					LABEL => "SOLID_JMP_8_FROM_2_P",
			),
			'9' => array(
					OPERATION => "PUSH",
					PARAMS => array("p_0"),
			),
			'11' => array(
					OPERATION => "RET",
			),
			'12' => array(			        
					LABEL => "SOLID_JMP_12_FROM_7_P",
			),			
	),
    SPECIFIC_USABLE => array(          
	    '2' => array('2' => N),
		'7' => array('1' => N),
	),
    PARAMS => array(
	    '2' => array('SOLID_JMP_9_FROM_2'),
		'7' => array('SOLID_JMP_12_FROM_7'),
		'9' => array('p_0'),             
	),
    P_TYPE => array(                   
	    '2' => array ('i'),
	    '7' => array ('i'),
	),
    P_BITS => array(                  
	    '2' => array (32),
		'7' => array (32),
	),	    
);

$poly_model_repo['CALL'][2] = array(
	
	FAT  => array(
	    '7'  => 2,
		'9'  => 2,
		'8'  => 1,
		'12' => 1  
	),

    CODE => array(
		'2'  => array (
		         OPERATION => 'CALL',
				 PARAMS => array("SOLID_JMP_8_FROM_2_P"),
		),
		'7'  => array (
		         OPERATION => 'JMP',
				 PARAMS => array("SOLID_JMP_12_FROM_7_P"),
		),
		'8' => array(
		         LABEL => "SOLID_JMP_8_FROM_2_P",
		),
		'9'  => array (
		         OPERATION => 'JMP',
				 PARAMS => array("p_0"),
		),
		'12' => array(			        
				LABEL => "SOLID_JMP_12_FROM_7_P",
		),	
	),
	SPECIFIC_USABLE => array(          
	    '2' => array('2' => N),
		'7' => array('1' => N),
	),
    P_TYPE => array(                   
	    '2' => array ('i'),
	    '7' => array ('i'),
	),
    P_BITS => array(                  
	    '2' => array (32),
		'7' => array (32),
	),
	
);





$poly_model_repo['JMP'][1] = array (
        OOO => array(             
		    0,1
		),		
		CODE => array (        
		    '0' => array (
			        OPERATION => 'JA',
					PARAMS => array('p_0'),
			),			
		    '1' => array (
			        OPERATION => 'JNA',
					PARAMS => array('p_0'),
			),
		),
		P_TYPE => array(                   
	        '0' => array ('i'),
	        '1' => array ('i'),
	    ),
        P_BITS => array(                  
	        '0' => array (32),
		    '1' => array (32),
	    ),
        FLAG_FORBID => array(             
		    P => array(
		      '1' => array(
			    'ZF' => 1,
			    'CF' => 1,
			  ),
			),
			N => array(
			  '0' => array(
			    'ZF' => 1,
			    'CF' => 1,   
			  ),
			),
		),
);

$poly_model_repo['JMP'][2] = array (
        OOO => array(             
		    0,1
		),		
		CODE => array (        
		    '0' => array (
			        OPERATION => 'JAE',
					PARAMS => array('p_0'),
			),			
		    '1' => array (
			        OPERATION => 'JNAE',
					PARAMS => array('p_0'),
			),
		),
		P_TYPE => array(                   
	        '0' => array ('i'),
	        '1' => array ('i'),
	    ),
        P_BITS => array(                  
	        '0' => array (32),
		    '1' => array (32),
	    ),
        FLAG_FORBID => array(             
		    P => array(
		      '1' => array(
			    'CF' => 1,
			  ),
			),
			N => array(
			  '0' => array(
			    'CF' => 1,   
			  ),
			),
		),
);

$poly_model_repo['JMP'][3] = array (
        OOO => array(             
		    0,1
		),		
		CODE => array (        
		    '0' => array (
			        OPERATION => 'JGE',
					PARAMS => array('p_0'),
			),			
		    '1' => array (
			        OPERATION => 'JNGE',
					PARAMS => array('p_0'),
			),
		),
		P_TYPE => array(                   
	        '0' => array ('i'),
	        '1' => array ('i'),
	    ),
        P_BITS => array(                  
	        '0' => array (32),
		    '1' => array (32),
	    ),
        FLAG_FORBID => array(             
		    P => array(
		      '1' => array(
			    'SF' => 1,
			    'OF' => 1,   
			  ),
			),
			N => array(
			  '0' => array(
			    'SF' => 1,   
			    'OF' => 1,   
			  ),
			),
		),
);


$poly_model_repo['JMP'][4] = array (
        OOO => array(             
		    0,1
		),		
		CODE => array (        
		    '0' => array (
			        OPERATION => 'JG',
					PARAMS => array('p_0'),
			),			
		    '1' => array (
			        OPERATION => 'JNG',
					PARAMS => array('p_0'),
			),
		),
		P_TYPE => array(                   
	        '0' => array ('i'),
	        '1' => array ('i'),
	    ),
        P_BITS => array(                  
	        '0' => array (32),
		    '1' => array (32),
	    ),
        FLAG_FORBID => array(             
		    P => array(
		      '1' => array(
			    'SF' => 1,
			    'OF' => 1,   
				'ZF' => 1,
			  ),
			),
			N => array(
			  '0' => array(
			    'SF' => 1,   
			    'OF' => 1,
				'ZF' => 1,   
			  ),
			),
		),
);


$poly_model_repo['JMP'][5] = array (
        OOO => array(             
		    0,1
		),		
		CODE => array (        
		    '0' => array (
			        OPERATION => 'JO',
					PARAMS => array('p_0'),
			),			
		    '1' => array (
			        OPERATION => 'JNO',
					PARAMS => array('p_0'),
			),
		),
		P_TYPE => array(                   
	        '0' => array ('i'),
	        '1' => array ('i'),
	    ),
        P_BITS => array(                  
	        '0' => array (32),
		    '1' => array (32),
	    ),
        FLAG_FORBID => array(             
		    P => array(
		      '1' => array(
			    'OF' => 1,
			  ),
			),
			N => array(
			  '0' => array(  
			    'OF' => 1,   
			  ),
			),
		),
);


$poly_model_repo['JMP'][6] = array (
        OOO => array(             
		    0,1
		),		
		CODE => array (        
		    '0' => array (
			        OPERATION => 'JS',
					PARAMS => array('p_0'),
			),			
		    '1' => array (
			        OPERATION => 'JNS',
					PARAMS => array('p_0'),
			),
		),
		P_TYPE => array(                   
	        '0' => array ('i'),
	        '1' => array ('i'),
	    ),
        P_BITS => array(                  
	        '0' => array (32),
		    '1' => array (32),
	    ),
        FLAG_FORBID => array(             
		    P => array(
		      '1' => array(
			    'SF' => 1,
			  ),
			),
			N => array(
			  '0' => array(  
			    'SF' => 1,   
			  ),
			),
		),
);


$poly_model_repo['JMP'][7] = array (
        OOO => array(             
		    0,1
		),		
		CODE => array (        
		    '0' => array (
			        OPERATION => 'JPE',
					PARAMS => array('p_0'),
			),			
		    '1' => array (
			        OPERATION => 'JPO',
					PARAMS => array('p_0'),
			),
		),
		P_TYPE => array(                   
	        '0' => array ('i'),
	        '1' => array ('i'),
	    ),
        P_BITS => array(                  
	        '0' => array (32),
		    '1' => array (32),
	    ),
        FLAG_FORBID => array(             
		    P => array(
		      '1' => array(
			    'PF' => 1,
			  ),
			),
			N => array(
			  '0' => array(  
			    'PF' => 1,   
			  ),
			),
		),
);

$poly_model_repo['JMP'][8] = array (
        OOO => array(             
		    0,1
		),
		CODE => array (        
		    '0' => array (
			        OPERATION => 'JE',
					PARAMS => array('p_0'),
			),			
		    '1' => array (
			        OPERATION => 'JNE',
					PARAMS => array('p_0'),
			),
		),
		P_TYPE => array(                   
	        '0' => array ('i'),
	        '1' => array ('i'),
	    ),
        P_BITS => array(                  
	        '0' => array (32),
		    '1' => array (32),
	    ),
        FLAG_FORBID => array(             
		    P => array(
		      '1' => array(
				'ZF' => 1,
			  ),
			),
			N => array(
			  '0' => array(  
				'ZF' => 1,
			  ),
			),
		),
);

?>