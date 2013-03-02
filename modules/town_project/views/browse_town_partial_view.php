<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$contents = '';
for($i=0; $i<count($result); $i++){
	$record = $result[$i];
	$contents .= '<div id="record_'.$record->town_id.'" class="record_container well">';
	
	// show columns
	$contents .= '<b>Country :</b> '.$record->twn_country_name.'  <br />'; 
	$contents .= '<b>Name :</b> '.$record->name.'  <br />'; 
	
	// edit and delete button
	if($allow_navigate_backend){
		$contents .= '<div class="edit_delete_record_container">';
		$contents .= '<a href="'.$backend_url.'/edit/'.$record->town_id.'" class="btn edit_record" primary_key = "'.$record->town_id.'">Edit</a>';
		$contents .= '&nbsp;';
		$contents .= '<a href="'.$backend_url.'/delete/'.$record->town_id.'" class="btn delete_record" primary_key = "'.$record->town_id.'">Delete</a>';
		$contents .= '</div>';
	}
	$contents .= '</div>';
}

echo $contents;