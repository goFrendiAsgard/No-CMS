<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$contents = '';
for($i=0; $i<count($result); $i++){
	$record = $result[$i];
	$contents .= '<div id="record_'.$record->id_barang.'" class="record_container well">';

	// show columns
	$contents .= '<b>Kode Barang :</b> '.$record->kode_barang.'  <br />'; 
	$contents .= '<b>Nama :</b> '.$record->nama.'  <br />'; 

	// edit and delete button
	if($allow_navigate_backend){
		$contents .= '<div class="edit_delete_record_container">';
		$contents .= '<a href="'.$backend_url.'/edit/'.$record->id_barang.'" class="btn edit_record" primary_key = "'.$record->id_barang.'">Edit</a>';
		$contents .= '&nbsp;';
		$contents .= '<a href="'.$backend_url.'/delete/'.$record->id_barang.'" class="btn delete_record" primary_key = "'.$record->id_barang.'">Delete</a>';
		$contents .= '</div>';
	}
	$contents .= '</div>';
}

echo $contents;