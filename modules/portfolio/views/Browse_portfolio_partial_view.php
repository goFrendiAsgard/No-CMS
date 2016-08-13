<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$contents = '';
foreach($result as $record){
    $contents .= '<div id="record_'.$record->id.'" class="record_container panel panel-default">';
    $contents .= '<div class="panel-body">';

    // TABLE
    $contents .= '<table class="table">';
    $contents .= '<tbody>';

    // COLUMNS
    //NAME
    $contents .= '<tr>';
        $contents .= '<td class="row col-md-5" rowspan="3">';
            $contents .= '<img class="col-md-12" src="{{ MODULE_BASE_URL }}assets/uploads/' . $record->image . '" />';

            $contents .= '<div class="col-md-12" style="padding-top:10px;">';
            if(trim($record->url) != ''){
                $contents .= '<a target="blank" class="btn btn-primary" href="' . $record->url . '"><i class="glyphicon glyphicon-link"></i> Visit</a>&nbsp;';
            }
            $contents .= '<a target="blank" class="btn btn-primary" href="{{ MODULE_BASE_URL }}assets/uploads/' . $record->image .'"><i class="glyphicon glyphicon-image"></i> Show</a>';
            $contents .= '</div>';

        $contents .= '</td>';
        $contents .= '<th class="col-md-3">Name</th>';
        $contents .= '<td class="col-md-4">' . $record->name . '</td>';
    $contents .= '</tr>';
    //CATEGORY
    $contents .= '<tr>';
        $contents .= '<th>Category</th>';
        $contents .= '<td>' . $record->category_name . '</td>';
    $contents .= '</tr>';
    //DESCRIPTION
    $contents .= '<tr>';
        $contents .= '<th>Description</th>';
        $contents .= '<td>' . $record->description . '</td>';
    $contents .= '</tr>';

    $contents .= '</tbody>';
    $contents .= '</table>';


    // EDIT AND DELETE BUTTON
    if($allow_navigate_backend && ($have_edit_privilege || $have_delete_privilege)){

        $contents .= '<div class="edit_delete_record_container pull-right">';

        // EDIT BUTTON
        if($have_edit_privilege){
            $contents .= '<a href="'.$backend_url.'/edit/'.$record->id.'" class="btn edit_record btn-default" primary_key = "'.$record->id.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
            $contents .= '&nbsp;';
        }
        // DELETE BUTTON
        if($have_delete_privilege){
            $contents .= '<a href="'.$backend_url.'/delete/'.$record->id.'" class="btn delete_record btn-danger" primary_key = "'.$record->id.'"><i class="glyphicon glyphicon-remove"></i> Delete</a>';
        }

        $contents .= '</div>';

        $contents .= '<div style="clear:both;"></div>';
    }

    // end of div record
    $contents .= '</div>';
    $contents .= '</div>';
}

echo $contents;
