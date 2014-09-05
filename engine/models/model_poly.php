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
    'rand' => array(
	    '0' => array('m32','r32'),		
	),
	'rand_privilege' => array(
            '0' => 2,
    ),
	'code' => array (	    
	    '2' => array(
		        'operation' => 'MOV',
				'params'    => array('r_0','p_0'),
		),
	    '7' => array(
		        'operation' => 'PUSH',
				'params'    => array('r_0'),
		),
    ), 	

	'p_bits' => array(
	    '2' => array (1 => 32),
	),

	'r_forbid' => array(             
		'p' => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		'n' => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
);

$poly_model_repo['PUSH'][2] = array( 	
    'rand' => array(
	    '0' => array('i','r32'),		
	),
	'code' => array (	    
	    '2' => array(
		        'operation' => 'PUSH',
				'params'    => array('r_0'),
		),
	    '7' => array(
		        'operation' => 'MOV',
				'params'    => array('[ESP]','p_0'),
		),
    ), 		
	'p_type' => array(                   
	    '7' => array (0 => 'm'),
	),
	'p_bits' => array(                   
	    '7' => array (0 => 32),
	),
);



$poly_model_repo['RET'][1] = array( 	
   


    'rand' => array(
	    '0' => array('m32','r32'),		
	),
	'rand_privilege' => array(
            '0' => 2,
    ),
	'code' => array (
	    '2' => array(
		        'operation' => 'POP',
				'params'    => array('r_0'),
		),
	    '7' => array(
		        'operation' => 'JMP',
				'params'    => array('r_0'),
		),
    ), 	
	'r_forbid' => array(             
		'p' => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		'n' => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
);

$poly_model_repo['RET'][2] = array( 	
    'rand' => array(
	    '0' => array('r32'),		
	),	

	'rand_privilege' => array(
            '0' => 3,
    ),

	'code' => array (
	    '1' => array(
		        'operation' => 'POP',
				'params'    => array('r_0'),
		),
	    '3' => array(
		        'operation' => 'ADD',
				'params'    => array('ESP','p_0'),
		),
		'5' => array (
		       'operation' => 'JMP',
			   'params'    => array ('r_0'),
		),
    ),
	
	'p_type' => array(             
	    '3' => array (0 => 'r'),
	),

	'p_bits' => array(              
	    '3' => array (32),
	),

    'r_forbid' => array(        
	    'p' => array(
  	      '5' => array(
		    '0' => 1,
		  ),
		  '3' => array(
		    '0' => 1,
		  ),
		),
		'n' => array(
		  '3' => array(
			'0' => 1,   
		  ),
		  '1' => array(
			'0' => 1,   
		  ),
		),
	),

    'p_forbid' => array(
		'p' => array(
		  '3' => array(
			'0' => 1,			
		  ),
		),
		'n' => array(
		  '1' => array(
			'0' => 1,   
		  ),
		),
	),

	'new_regs' => array(           
		'flag' => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),

);



$poly_model_repo['CMP'][1] = array(
	'code' => array (
	    '2' => array(
		        'operation' => 'SUB',
				'params'    => array('p_0','p_1'),
		),
    ), 	
    'new_regs' => array(           
		'normal' => array(
		    '0'  => 1,
		),
	),
);

$poly_model_repo['CMP'][2] = array(
    'rand' => array(
	    '0' => array('r32'),		
	),
	'rand_privilege' => array(
            '0' => 2,
    ),
	'code' => array (
	    '2' => array(
		        'operation' => 'MOV',
				'params'    => array('r_0','p_0'),
		),
		'7' => array(
		        'operation' => 'CMP',
				'params'    => array('r_0','p_1'),
		),
    ), 	
	'r_forbid' => array(             
	    'p' => array(
  	      '7' => array(
		    '0' => 1,
		  ),
		),
		'n' => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
);

$poly_model_repo['CMP'][3] = array(
    'rand' => array(
	    '0' => array('r32'),		
	),
	'rand_privilege' => array(
            '0' => 3,
    ),
	'code' => array (
	    '1' => array(
		        'operation' => 'MOV',
				'params'    => array('r_0','p_1'),
		),
		'3' => array(
		        'operation' => 'CMP',
				'params'    => array('p_0','r_0'),
		),
    ), 	

	'p_forbid' => array(             
	    'p' => array(
  	      '3' => array(
		    '0' => 1,
		  ),
		),
		'n' => array(
		  '1' => array(
			'0' => 1,   
		  ),
		),
	),

	'r_forbid' => array( 
	    'p' => array(
  	      '3' => array(
		    '0' => 1,
		  ),
		),
		'n' => array(
		  '1' => array(
			'0' => 1,   
		  ),
		),
	),
);



$poly_model_repo['DEC'][1] = array(
	'code' => array (
	    '2' => array(
		        'operation' => 'ADD',
				'params'    => array('p_0','-1'),
		),
    ),
	'p_type' => array(                   
	    '2' => array (1 => 'i'),
	),
	'p_bits' => array(                  
	    '2' => array (32,32),
	),	
    'new_regs' => array(           
	    'flag' => array(
		    'CF' => 1
		),
	),
);
$poly_model_repo['DEC'][2] = array(
	'code' => array (
	    '2' => array(
		        'operation' => 'SUB',
				'params'    => array('p_0','1'),
		),
    ),
	'p_type' => array(                   
	    '2' => array (1 => 'i'),
	),
	'p_bits' => array(                  
	    '2' => array (32,32),
	),	
    'new_regs' => array(           
	    'flag' => array(
		    'CF' => 1
		),
	),
);



$poly_model_repo['INC'][1] = array(
	'code' => array (
	    '2' => array(
		        'operation' => 'ADD',
				'params'    => array('p_0','1'),
		),
    ),
	'p_type' => array(                   
	    '2' => array (1 => 'i'),
	),
	'p_bits' => array(                  
	    '2' => array (32,32),
	),	
    'new_regs' => array(           
	    'flag' => array(
		    'CF' => 1
		),
	),
);
$poly_model_repo['INC'][2] = array(
	'code' => array (
	    '2' => array(
		        'operation' => 'SUB',
				'params'    => array('p_0','-1'),
		),
    ),
	'p_type' => array(                   
	    '2' => array (1 => 'i'),
	),
	'p_bits' => array(                  
	    '2' => array (32,32),
	),	
    'new_regs' => array(           
	    'flag' => array(
		    'CF' => 1
		),
	),
);



$poly_model_repo['SUB'][1] = array(
 	'ooo' => array(             
		2,7
	),
    'rand' => array(
	    '0' => array( 'i'),		
	),
	'code' => array (
	    '2' => array(
		        'operation' => 'ADD',
				'params'    => array('p_0','r_0'),
		),
		'7' => array(
		        'operation' => 'SUB',
				'params'    => array('p_0','p_1 + r_0'),
		),   
    ),
	'p_type' => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	'p_bits' => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	'p_forbid' => array(             
		'p' => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		'n' => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    'new_regs' => array(           
	    'flag' => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);

$poly_model_repo['SUB'][2] = array(
    'rand' => array(
	    '0' => array( 'i'),		
	),
	'code' => array (
	    '2' => array(
		        'operation' => 'ADD',
				'params'    => array('p_0','- ( p_1 ) - r_0'),
		),
		'7' => array(
		        'operation' => 'ADD',
				'params'    => array('p_0','r_0'),
		),   
    ),
	'p_type' => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	'p_bits' => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	'p_forbid' => array(             
		'p' => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		'n' => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    'new_regs' => array(           
	    'flag' => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);

$poly_model_repo['SUB'][3] = array(
    'rand' => array(
	    '0' => array( 'i'),		
	),
	'code' => array (
	    '2' => array(
		        'operation' => 'SUB',
				'params'    => array('p_0','p_1 - r_0'),
		),
		'7' => array(
		        'operation' => 'SUB',
				'params'    => array('p_0','r_0'),
		),   
    ),
	'p_type' => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	'p_bits' => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	'p_forbid' => array(             
		'p' => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		'n' => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    'new_regs' => array(           
	    'flag' => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);


$poly_model_repo['SUB'][4] = array(
 	'ooo' => array(             
		2,7
	),
	'code' => array (
	    '2' => array(
		        'operation' => 'ADD',
				'params'    => array('p_0','- ( p_1 ) - 1'),
		),
		'7' => array(
		        'operation' => 'INC',
				'params'    => array('p_0'),
		),   
    ),
	'p_type' => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	'p_bits' => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	'p_forbid' => array(             
		'p' => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		'n' => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    'new_regs' => array(           
	    'flag' => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);


$poly_model_repo['SUB'][5] = array(
 	'ooo' => array(             
		2,7
	),
	'code' => array (
	    '2' => array(
		        'operation' => 'ADD',
				'params'    => array('p_0','- ( p_1 ) + 1'),
		),
		'7' => array(
		        'operation' => 'DEC',
				'params'    => array('p_0'),
		),   
    ),
	'p_type' => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	'p_bits' => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	'p_forbid' => array(             
		'p' => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		'n' => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    'new_regs' => array(           
	    'flag' => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);


$poly_model_repo['SUB'][6] = array(
 	'ooo' => array(             
		2,7
	),
	'code' => array (
	    '2' => array(
		        'operation' => 'SUB',
				'params'    => array('p_0','p_1 + 1'),
		),
		'7' => array(
		        'operation' => 'INC',
				'params'    => array('p_0'),
		),   
    ),
	'p_type' => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	'p_bits' => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	'p_forbid' => array(             
		'p' => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		'n' => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    'new_regs' => array(           
	    'flag' => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);

$poly_model_repo['SUB'][7] = array(
 	'ooo' => array(             
		2,7
	),
	'code' => array (
	    '2' => array(
		        'operation' => 'SUB',
				'params'    => array('p_0','p_1 - 1'),
		),
		'7' => array(
		        'operation' => 'DEC',
				'params'    => array('p_0'),
		),   
    ),
	'p_type' => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	'p_bits' => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	'p_forbid' => array(             
		'p' => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		'n' => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    'new_regs' => array(           
	    'flag' => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);


$poly_model_repo['SUB'][8] = array(
    'rand' => array(
	    '0' => array('r32'),		
	),
	'code' => array (
	    '2' => array(
		        'operation' => 'MOV',
				'params'    => array('r_0','p_1'),
		),
		'7' => array(
		        'operation' => 'SUB',
				'params'    => array('p_0','r_0'),
		),   
    ),	
	'r_forbid' => array(             
	    'p' => array(
  	      '7' => array(
		    '0' => 1,
		  ),
		),
		'n' => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
   
);



$poly_model_repo['ADD'][1] = array(
 	'ooo' => array(             
		2,7
	),
    'rand' => array(
	    '0' => array( 'i'),		
	),
	'code' => array (
	    '2' => array(
		        'operation' => 'ADD',
				'params'    => array('p_0','p_1 + r_0'),
		),
		'7' => array(
		        'operation' => 'SUB',
				'params'    => array('p_0','r_0'),
		),   
    ),
	'p_type' => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	'p_bits' => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	'p_forbid' => array(             
		'p' => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		'n' => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    'new_regs' => array(           
	    'flag' => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);

$poly_model_repo['ADD'][2] = array(
    'rand' => array(
	    '0' => array( 'i'),		
	),
	'code' => array (
	    '2' => array(
		        'operation' => 'ADD',
				'params'    => array('p_0','p_1 - r_0'),
		),
		'7' => array(
		        'operation' => 'ADD',
				'params'    => array('p_0','r_0'),
		),   
    ),
	'p_type' => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	'p_bits' => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	'p_forbid' => array(             
		'p' => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		'n' => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    'new_regs' => array(           
	    'flag' => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);

$poly_model_repo['ADD'][3] = array(
    'rand' => array(
	    '0' => array( 'i'),		
	),
	'code' => array (
	    '2' => array(
		        'operation' => 'SUB',
				'params'    => array('p_0','- ( p_1 ) - r_0'),
		),
		'7' => array(
		        'operation' => 'SUB',
				'params'    => array('p_0','r_0'),
		),   
    ),
	'p_type' => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	'p_bits' => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	'p_forbid' => array(             
		'p' => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		'n' => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    'new_regs' => array(           
	    'flag' => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);


$poly_model_repo['ADD'][4] = array(
 	'ooo' => array(             
		2,7
	),
	'code' => array (
	    '2' => array(
		        'operation' => 'ADD',
				'params'    => array('p_0',' p_1 - 1'),
		),
		'7' => array(
		        'operation' => 'INC',
				'params'    => array('p_0'),
		),   
    ),
	'p_type' => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	'p_bits' => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	'p_forbid' => array(             
		'p' => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		'n' => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    'new_regs' => array(           
	    'flag' => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);


$poly_model_repo['ADD'][5] = array(
 	'ooo' => array(             
		2,7
	),
	'code' => array (
	    '2' => array(
		        'operation' => 'ADD',
				'params'    => array('p_0',' p_1 + 1'),
		),
		'7' => array(
		        'operation' => 'DEC',
				'params'    => array('p_0'),
		),   
    ),
	'p_type' => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	'p_bits' => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	'p_forbid' => array(             
		'p' => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		'n' => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    'new_regs' => array(           
	    'flag' => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);


$poly_model_repo['ADD'][6] = array(
 	'ooo' => array(             
		2,7
	),
	'code' => array (
	    '2' => array(
		        'operation' => 'SUB',
				'params'    => array('p_0','- ( p_1 ) + 1'),
		),
		'7' => array(
		        'operation' => 'INC',
				'params'    => array('p_0'),
		),   
    ),
	'p_type' => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	'p_bits' => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	'p_forbid' => array(             
		'p' => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		'n' => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    'new_regs' => array(           
	    'flag' => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);

$poly_model_repo['ADD'][7] = array(
 	'ooo' => array(             
		2,7
	),
	'code' => array (
	    '2' => array(
		        'operation' => 'SUB',
				'params'    => array('p_0','- ( p_1 ) - 1'),
		),
		'7' => array(
		        'operation' => 'DEC',
				'params'    => array('p_0'),
		),   
    ),
	'p_type' => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	'p_bits' => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	'p_forbid' => array(             
		'p' => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		'n' => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    'new_regs' => array(           
	    'flag' => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);



$poly_model_repo['MOV'][1] = array(
    'rand' => array(
	    '0' => array( 'i'),		
	),
	'code' => array (
	    '2' => array(
		        'operation' => 'MOV',
				'params'    => array('p_0','p_1 + r_0'),
		),
		'7' => array(
		        'operation' => 'SUB',
				'params'    => array('p_0','r_0'),
		),   
    ),
	'p_type' => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	'p_bits' => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),
	'p_forbid' => array(             
		'p' => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		'n' => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    'new_regs' => array(           
	    'flag' => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);


$poly_model_repo['MOV'][2] = array(
    'rand' => array(
	    '0' => array( 'i'),		
	),	
	'code' => array (
	    '2' => array(
		        'operation' => 'MOV',
				'params'    => array('p_0','p_1 - r_0'),
		),
		'7' => array(
		        'operation' => 'ADD',
				'params'    => array('p_0','r_0'),
		),   
    ),
	'p_type' => array(                   
	    '2' => array (1 => 'i'),
	    '7' => array (1 => 'i'),
	),
	'p_bits' => array(                  
	    '2' => array (32,32),
		'7' => array (32,32),
	),

	'p_forbid' => array(             
		'p' => array(
		  '7' => array(
			'0' => 1,			
		  ),
		),
		'n' => array(
		  '2' => array(
			'0' => 1,   
		  ),
		),
	),
    'new_regs' => array(           
	    'flag' => array(
		    'OF' => 1,'SF' => 1,'ZF' => 1,'AF' => 1,'CF' => 1,'PF' => 1,
		),
	),
);






$poly_model_repo['CALL'][1] = array(
    
    'rel_reset'  => array( 
	    '0' => array (
		    'Type'    => 6,
		    'isLabel' => false,
		), 
	),
	
	'fat'  => array(
	    '7'  => 2, 
		'8'  => 1,
		'11' => 2,
		'12' => 1  
	),

	'code' => array(
			'2' => array(
					'operation' => "CALL",
					'params' => array("SOLID_JMP_8_FROM_2_P"),
			),
			'7' => array(
					'operation' => "JMP",
					'params' => array("SOLID_JMP_12_FROM_7_P"),
			),
			'8' => array(
					'label' => "SOLID_JMP_8_FROM_2_P",
			),
			'9' => array(
					'operation' => "PUSH",
					'params' => array("p_0"),
			),
			'11' => array(
					'operation' => "RET",
			),
			'12' => array(			        
					'label' => "SOLID_JMP_12_FROM_7_P",
			),			
	),
    'specific_usable' => array(          
	    '2' => array('2' => 'n'),
		'7' => array('1' => 'n'),
	),
    'params' => array(
	    '2' => array('SOLID_JMP_9_FROM_2'),
		'7' => array('SOLID_JMP_12_FROM_7'),
		'9' => array('p_0'),             
	),
    'p_type' => array(                   
	    '2' => array ('i'),
	    '7' => array ('i'),
	),
    'p_bits' => array(                  
	    '2' => array (32),
		'7' => array (32),
	),	    
);

$poly_model_repo['CALL'][2] = array(
	
	'fat'  => array(
	    '7'  => 2,
		'9'  => 2,
		'8'  => 1,
		'12' => 1  
	),

    'code' => array(
		'2'  => array (
		         'operation' => 'CALL',
				 'params' => array("SOLID_JMP_8_FROM_2_P"),
		),
		'7'  => array (
		         'operation' => 'JMP',
				 'params' => array("SOLID_JMP_12_FROM_7_P"),
		),
		'8' => array(
		         'label' => "SOLID_JMP_8_FROM_2_P",
		),
		'9'  => array (
		         'operation' => 'JMP',
				 'params' => array("p_0"),
		),
		'12' => array(			        
				'label' => "SOLID_JMP_12_FROM_7_P",
		),	
	),
	'specific_usable' => array(          
	    '2' => array('2' => 'n'),
		'7' => array('1' => 'n'),
	),
    'p_type' => array(                   
	    '2' => array ('i'),
	    '7' => array ('i'),
	),
    'p_bits' => array(                  
	    '2' => array (32),
		'7' => array (32),
	),
	
);





$poly_model_repo['JMP'][1] = array (
        'ooo' => array(             
		    0,1
		),		
		'code' => array (        
		    '0' => array (
			        'operation' => 'JA',
					'params' => array('p_0'),
			),			
		    '1' => array (
			        'operation' => 'JNA',
					'params' => array('p_0'),
			),
		),
		'p_type' => array(                   
	        '0' => array ('i'),
	        '1' => array ('i'),
	    ),
        'p_bits' => array(                  
	        '0' => array (32),
		    '1' => array (32),
	    ),
        'flag_forbid' => array(             
		    'p' => array(
		      '1' => array(
			    'ZF' => 1,
			    'CF' => 1,
			  ),
			),
			'n' => array(
			  '0' => array(
			    'ZF' => 1,
			    'CF' => 1,   
			  ),
			),
		),
);

$poly_model_repo['JMP'][2] = array (
        'ooo' => array(             
		    0,1
		),		
		'code' => array (        
		    '0' => array (
			        'operation' => 'JAE',
					'params' => array('p_0'),
			),			
		    '1' => array (
			        'operation' => 'JNAE',
					'params' => array('p_0'),
			),
		),
		'p_type' => array(                   
	        '0' => array ('i'),
	        '1' => array ('i'),
	    ),
        'p_bits' => array(                  
	        '0' => array (32),
		    '1' => array (32),
	    ),
        'flag_forbid' => array(             
		    'p' => array(
		      '1' => array(
			    'CF' => 1,
			  ),
			),
			'n' => array(
			  '0' => array(
			    'CF' => 1,   
			  ),
			),
		),
);

$poly_model_repo['JMP'][3] = array (
        'ooo' => array(             
		    0,1
		),		
		'code' => array (        
		    '0' => array (
			        'operation' => 'JGE',
					'params' => array('p_0'),
			),			
		    '1' => array (
			        'operation' => 'JNGE',
					'params' => array('p_0'),
			),
		),
		'p_type' => array(                   
	        '0' => array ('i'),
	        '1' => array ('i'),
	    ),
        'p_bits' => array(                  
	        '0' => array (32),
		    '1' => array (32),
	    ),
        'flag_forbid' => array(             
		    'p' => array(
		      '1' => array(
			    'SF' => 1,
			    'OF' => 1,   
			  ),
			),
			'n' => array(
			  '0' => array(
			    'SF' => 1,   
			    'OF' => 1,   
			  ),
			),
		),
);


$poly_model_repo['JMP'][4] = array (
        'ooo' => array(             
		    0,1
		),		
		'code' => array (        
		    '0' => array (
			        'operation' => 'JG',
					'params' => array('p_0'),
			),			
		    '1' => array (
			        'operation' => 'JNG',
					'params' => array('p_0'),
			),
		),
		'p_type' => array(                   
	        '0' => array ('i'),
	        '1' => array ('i'),
	    ),
        'p_bits' => array(                  
	        '0' => array (32),
		    '1' => array (32),
	    ),
        'flag_forbid' => array(             
		    'p' => array(
		      '1' => array(
			    'SF' => 1,
			    'OF' => 1,   
				'ZF' => 1,
			  ),
			),
			'n' => array(
			  '0' => array(
			    'SF' => 1,   
			    'OF' => 1,
				'ZF' => 1,   
			  ),
			),
		),
);


$poly_model_repo['JMP'][5] = array (
        'ooo' => array(             
		    0,1
		),		
		'code' => array (        
		    '0' => array (
			        'operation' => 'JO',
					'params' => array('p_0'),
			),			
		    '1' => array (
			        'operation' => 'JNO',
					'params' => array('p_0'),
			),
		),
		'p_type' => array(                   
	        '0' => array ('i'),
	        '1' => array ('i'),
	    ),
        'p_bits' => array(                  
	        '0' => array (32),
		    '1' => array (32),
	    ),
        'flag_forbid' => array(             
		    'p' => array(
		      '1' => array(
			    'OF' => 1,
			  ),
			),
			'n' => array(
			  '0' => array(  
			    'OF' => 1,   
			  ),
			),
		),
);


$poly_model_repo['JMP'][6] = array (
        'ooo' => array(             
		    0,1
		),		
		'code' => array (        
		    '0' => array (
			        'operation' => 'JS',
					'params' => array('p_0'),
			),			
		    '1' => array (
			        'operation' => 'JNS',
					'params' => array('p_0'),
			),
		),
		'p_type' => array(                   
	        '0' => array ('i'),
	        '1' => array ('i'),
	    ),
        'p_bits' => array(                  
	        '0' => array (32),
		    '1' => array (32),
	    ),
        'flag_forbid' => array(             
		    'p' => array(
		      '1' => array(
			    'SF' => 1,
			  ),
			),
			'n' => array(
			  '0' => array(  
			    'SF' => 1,   
			  ),
			),
		),
);


$poly_model_repo['JMP'][7] = array (
        'ooo' => array(             
		    0,1
		),		
		'code' => array (        
		    '0' => array (
			        'operation' => 'JPE',
					'params' => array('p_0'),
			),			
		    '1' => array (
			        'operation' => 'JPO',
					'params' => array('p_0'),
			),
		),
		'p_type' => array(                   
	        '0' => array ('i'),
	        '1' => array ('i'),
	    ),
        'p_bits' => array(                  
	        '0' => array (32),
		    '1' => array (32),
	    ),
        'flag_forbid' => array(             
		    'p' => array(
		      '1' => array(
			    'PF' => 1,
			  ),
			),
			'n' => array(
			  '0' => array(  
			    'PF' => 1,   
			  ),
			),
		),
);

$poly_model_repo['JMP'][8] = array (
        'ooo' => array(             
		    0,1
		),
		'code' => array (        
		    '0' => array (
			        'operation' => 'JE',
					'params' => array('p_0'),
			),			
		    '1' => array (
			        'operation' => 'JNE',
					'params' => array('p_0'),
			),
		),
		'p_type' => array(                   
	        '0' => array ('i'),
	        '1' => array ('i'),
	    ),
        'p_bits' => array(                  
	        '0' => array (32),
		    '1' => array (32),
	    ),
        'flag_forbid' => array(             
		    'p' => array(
		      '1' => array(
				'ZF' => 1,
			  ),
			),
			'n' => array(
			  '0' => array(  
				'ZF' => 1,
			  ),
			),
		),
);

?>