<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$contents = '';
for($i=0; $i<count($result); $i++){
	$record = $result[$i];
	$contents .= '<div id="record_'.$record->city_id.'" class="record_container well">';

	// show columns
	$contents .= '<b>Country :</b> '.$record->country_name.'  <br />'; 
	$contents .= '<b>Name :</b> '.$record->name.'  <br />'; 

	// edit and delete button
	if($allow_navigate_backend){
		$contents .= '<div class="edit_delete_record_container">';
		$contents .= '<a href="'.$backend_url.'/edit/'.$record->city_id.'" class="btn edit_record btn-default" primary_key = "'.$record->city_id.'">Edit</a>';
		$contents .= '&nbsp;';
		$contents .= '<a href="'.$backend_url.'/delete/'.$record->city_id.'" class="btn delete_record btn-danger" primary_key = "'.$record->city_id.'">Delete</a>';
		$contents .= '</div>';
	}
	$contents .= '</div>';
}

echo $contents;