<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$contents = '';
for($i=0; $i<count($result); $i++){
	$record = $result[$i];
	$contents .= '<div id="record_'.$record->id.'" class="record_container well">';

	// show columns
	$contents .= '<b>Name :</b> '.$record->name.'  <br />'; 
	$contents .= '<b>Price :</b> '.$record->price.'  <br />'; 
	$contents .= '<b>Discount :</b> '.$record->discount.'  <br />'; 
	$contents .= '<b>Photo :</b> <img height="100px" src="{{ base_url }}modules/shop/assets/uploads/'.$record->photo.'" />  <br />'; 
	$contents .= '<b>Description :</b> '.$record->description.'  <br />'; 
	$contents .= '<b>Availability :</b> '.$record->availability.'  <br />'; 

	// edit and delete button
	if($allow_navigate_backend){
		$contents .= '<div class="edit_delete_record_container">';
		$contents .= '<a href="'.$backend_url.'/edit/'.$record->id.'" class="btn edit_record btn-default" primary_key = "'.$record->id.'">Edit</a>';
		$contents .= '&nbsp;';
		$contents .= '<a href="'.$backend_url.'/delete/'.$record->id.'" class="btn delete_record btn-danger" primary_key = "'.$record->id.'">Delete</a>';
		$contents .= '</div>';
	}
	$contents .= '</div>';
}

echo $contents;