<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$contents = '';
foreach($result as $record){
    $contents .= '<div id="record_'.$record->city_id.'" class="record_container panel panel-default">';
    $contents .= '<div class="panel-body">';

    // TABLE
    $contents .= '<table class="table table-hover">';
    $contents .= '<tbody>';

    // COLUMNS
    //COUNTRY
    $contents .= '<tr>';
    $contents .= '<th>Country</th>';
    $contents .= '<td>' . $record->country_name . '</td>';
    $contents .= '<tr>';
    //NAME
    $contents .= '<tr>';
    $contents .= '<th>Name</th>';
    $contents .= '<td>' . $record->name . '</td>';
    $contents .= '<tr>';

    $contents .= '</tbody>';
    $contents .= '</table>';


    // EDIT AND DELETE BUTTON
    if($allow_navigate_backend && ($have_edit_privilege || $have_delete_privilege)){

        $contents .= '<div class="edit_delete_record_container pull-right">';

        // EDIT BUTTON
        if($have_edit_privilege){
            $contents .= '<a href="'.$backend_url.'/edit/'.$record->city_id.'" class="btn edit_record btn-default" primary_key = "'.$record->city_id.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
            $contents .= '&nbsp;';
        }
        // DELETE BUTTON
        if($have_delete_privilege){
            $contents .= '<a href="'.$backend_url.'/delete/'.$record->city_id.'" class="btn delete_record btn-danger" primary_key = "'.$record->city_id.'"><i class="glyphicon glyphicon-remove"></i> Delete</a>';
        }

        $contents .= '</div>';

        $contents .= '<div style="clear:both;"></div>';
    }

    // end of div record
    $contents .= '</div>';
    $contents .= '</div>';
}

echo $contents;
