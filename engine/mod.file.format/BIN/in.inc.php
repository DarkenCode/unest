<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}

function in_file_format(){
	global $myTables;
    global $output;
    global $input_filesize;
	
		
		
		

		
		
		

		$myTables['CodeSectionArray'][1]['PointerToRawData'] = 0;
		$myTables['CodeSectionArray'][1]['name'] = '.text$unest_binary';
		$myTables['CodeSectionArray'][1]['SizeOfRawData']    = $input_filesize;
}

?>