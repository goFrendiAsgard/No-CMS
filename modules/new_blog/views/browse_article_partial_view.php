<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$contents = '';
for($i=0; $i<count($result); $i++){
	$record = $result[$i];
	$contents .= '<div id="record_'.$record->article_id.'" class="record_container well">';

	// show columns
	$contents .= '<b>Article Title :</b> '.$record->article_title.'  <br />'; 
	$contents .= '<b>Article Url :</b> '.$record->article_url.'  <br />'; 
	$contents .= '<b>Date :</b> '.$record->date.'  <br />'; 
	$contents .= '<b>Author User Id :</b> '.$record->author_user_id.'  <br />'; 
	$contents .= '<b>Content :</b> '.$record->content.'  <br />'; 
	$contents .= '<b>Allow Comment :</b> '.$record->allow_comment.'  <br />'; 

	// edit and delete button
	if($allow_navigate_backend){
		$contents .= '<div class="edit_delete_record_container">';
		$contents .= '<a href="'.$backend_url.'/edit/'.$record->article_id.'" class="btn edit_record" primary_key = "'.$record->article_id.'">Edit</a>';
		$contents .= '&nbsp;';
		$contents .= '<a href="'.$backend_url.'/delete/'.$record->article_id.'" class="btn delete_record" primary_key = "'.$record->article_id.'">Delete</a>';
		$contents .= '</div>';
	}
	$contents .= '</div>';
}

echo $contents;