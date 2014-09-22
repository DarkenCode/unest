<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}

class IOFormatParser{
    
	public static function in_file_format(){
		global $myTables;

		global $input_filesize;
		
			
			
			

			
			
			

			$myTables['CodeSectionArray'][1]['PointerToRawData'] = 0;
			$myTables['CodeSectionArray'][1]['name'] = '.text$unest_binary';
			$myTables['CodeSectionArray'][1]['SizeOfRawData']    = $input_filesize;
	}

	public static function out_file_buff_head($sec){
		return false;
	}

	public static function out_file_format_gen(){
		global $newCodeSection;
		global $binary_filename;

		$newCodeSection[1]['size'] = filesize($binary_filename);
	}

	public static function out_file_gen_name(){
		global $outputfile;
		return $outputfile;
	}
}


?>