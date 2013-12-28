<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$contents = '';
for($i=0; $i<count($result); $i++){
    $record = $result[$i];
    $contents .= '<div class="col-xs-6 col-md-4">';
    $contents .= '<div id="record_'.$record->id.'" class="record_container thumbnail">';    

    if($record->use_subdomain){
        $url = str_replace('://','://'.$record->name.'.', $site_url);
    }else{
        $url = $site_url.'site-'.$record->name;
    }
    $contents .= '<a href="'.$url.'" style="text-decoration:none;">';

    // show columns
    $image_path = '';
    if($record->logo === NULL || $record->logo == ''){
        $image_path = base_url('modules/{{ module_path }}/assets/images/default-logo.png');
    }else{
        $image_path = base_url('modules/{{ module_path }}/assets/uploads/'.$record->logo);
    }
    $contents .= '<img src="'.$image_path.'" style="max-height:64px;" />';
    $contents .= '<div class="caption">';
    $contents .= '<h3>'.$record->name.'</h3>'; 
    $contents .= '<p>'.$record->description.'</p>';
    $contents .= '</div>';
    $contents .= '</a>';


    $contents .= '</div>';
    $contents .= '</div>';
}

echo $contents;