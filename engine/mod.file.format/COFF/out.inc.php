<?php

if(!defined('UNEST.ORG')) {
        exit('Access Denied');
}

function out_file_buff_head($sec){
	return 'dd sec_'."$sec".'_end - sec_'."$sec".'_start';
}

function out_file_format_gen(){
	global $binary_filename;
    global $newCodeSection;
	global $CodeSectionArray;
	global $reloc_info_2_rewrite_table;
	global $non_null_labels;
	global $obj_filename;
	global $output;

    	
		$binary_file_buf = file_get_contents($binary_filename);
		get_contents_from_result_binary($binary_file_buf,$CodeSectionArray,$reloc_info_2_rewrite_table,$newCodeSection,$non_null_labels); 
		
		$handle = fopen($obj_filename,'rb');
		if ($handle){
			$buf = fread($handle,filesize($obj_filename));
			fclose($handle);
		}else{
		    $output['error'][] = "fail to open original obj file: ".$obj_filename;
		}
		
		
		build_final_obj($buf,$binary_file_buf,$newCodeSection,$CodeSectionArray);
}

function out_file_gen_name(){
	global $out_file;
	return "$out_file".'.bin';
}





function build_final_obj($buf,$binary_file_buf,$newCodeSection,$CodeSectionArray){
    require dirname(__FILE__)."/Obj_Header_array.php";       
    
	

	
	
	$c_lp = strlen($buf);
	
	foreach ($newCodeSection as $a => $b){        
        $align = 1;
		foreach ($IMAGE_SECTION_HEADER_Characteristics_H_Align as $c => $d){
		    if ($d == ($CodeSectionArray[$a]['Characteristics_H'] & $d)){
			    $align = $c;
				break;
			}
		}
		
		$buf .= substr($binary_file_buf,$newCodeSection[$a]['PointerToRelocation'],$newCodeSection[$a]['NumberOfRelocation'] * (4 + 4 + 2));
        $buf .= substr($binary_file_buf,$newCodeSection[$a]['addr'],$newCodeSection[$a]['size']);
        
		
        $push_size_for_align = $align - $newCodeSection[$a]['size'] % $align;
		if ($push_size_for_align != $align){
			$newCodeSection[$a]['size'] += $push_size_for_align; 
		    for (;$push_size_for_align > 0;$push_size_for_align --){
			    $buf .= "\xcc"; 
			}	
		}

		
		hex_write($buf,$CodeSectionArray[$a]['base'] + 4 + 4,$c_lp);  
		$c_lp += $newCodeSection[$a]['NumberOfRelocation'] * (4 + 4 + 2);
        
        
		
        hex_write($buf,$CodeSectionArray[$a]['base'] + 4 + 4 + 4 + 4,$newCodeSection[$a]['NumberOfRelocation'],2);
		hex_write($buf,$CodeSectionArray[$a]['base'],$newCodeSection[$a]['size']);      
		
        hex_write($buf,$CodeSectionArray[$a]['base'] + 4,$c_lp);  

		
		
        $c_lp += $newCodeSection[$a]['size'];
	}    

	
	global $outputfile;
	file_put_contents($outputfile,$buf);

  
}







function get_contents_from_result_binary($binary_file_buf,$CodeSectionArray,$reloc_info_2_rewrite_table,&$newCodeSection,$non_null_labels){
    $c_lp = 0; 
	foreach ($CodeSectionArray as $a => $b){
	    $size = get_hex_2_dec($binary_file_buf,$c_lp,4,true);

		$c_lp += 4;
		$NumberOfRelocation = 0;
		$rewrite_rel32 = array();
        $newCodeSection[$a]['PointerToRelocation'] = $c_lp;		
		if (is_array($reloc_info_2_rewrite_table[$a])){
			$NumberOfRelocation = count($reloc_info_2_rewrite_table[$a]);
			foreach ($reloc_info_2_rewrite_table[$a] as $c => $d){
			    $c_lp += 4 + 4 + 2;
                if ($non_null_labels[$tmp[2]][$tmp[3]][$tmp[4]]){ 
                    $rewrite_rel32[$rva] = $non_null_labels[$tmp[2]][$tmp[3]][$tmp[4]];
				}
			}
		}
		$newCodeSection[$a]['addr'] = $c_lp;
		$newCodeSection[$a]['size'] = $size;
		$newCodeSection[$a]['NumberOfRelocation']  = $NumberOfRelocation;
        
		foreach ($rewrite_rel32 as $z => $y){	
			
			$y = substr('00000000',0,8 - strlen($y)).$y;
			
			$binary_file_buf[$c_lp+$z + 3] = pack("H*",substr($y,0,2));
			$binary_file_buf[$c_lp+$z + 2] = pack("H*",substr($y,2,2));
			$binary_file_buf[$c_lp+$z + 1] = pack("H*",substr($y,4,2));
			$binary_file_buf[$c_lp+$z]     = pack("H*",substr($y,6,2));
		}
		$c_lp += $size;
	}   

	return true;
} 


?>