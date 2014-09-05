<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}
function out_file_buff_head($sec){
    return false;
}

function out_file_format_gen(){
    global $newCodeSection;
	global $binary_filename;

	$newCodeSection[1]['size'] = filesize($binary_filename);
}

function out_file_gen_name(){
	global $outputfile;
	return $outputfile;
}



?>