<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$contents = '';
for($i=0; $i<count($result); $i++){
    $record = $result[$i];
    $contents .= '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">';
    $contents .= '<div id="record_'.$record->id.'" class="record_container thumbnail">';

    if($record->use_subdomain){
        $subsite_url = str_replace('://','://'.$record->name.'.', $site_url);
    }else{
        $subsite_url = $site_url.'site-'.$record->name;
    }

    // show columns
    $image_path = '';
    if($record->logo === NULL || $record->logo == ''){
        $image_path = base_url('modules/{{ module_path }}/assets/images/default-logo.png');
    }else{
        $image_path = base_url('modules/{{ module_path }}/assets/uploads/'.$record->logo);
    }
    $contents .= '<img src="'.$image_path.'" style="max-height:64px; max-width:64px;" />';
    $contents .= '<div class="caption">';
    $contents .= '<h3>'.$record->name.'</h3>';
    $contents .= '<p>'.$record->description.'</p>';
    $contents .= '<p>';
    $contents .= '<a href="'.$subsite_url.'" class="btn btn-primary">Go To Site</a>';
    if($allow_navigate_backend && $record->allow_edit){
        $contents .= '&nbsp;<a href="'.$edit_url.'/'.$record->name.'" class="btn btn-default">Edit</a>';
    }
    $contents .= '</p>';
    $contents .= '</div>';


    $contents .= '</div>';
    $contents .= '</div>';
}

echo $contents;