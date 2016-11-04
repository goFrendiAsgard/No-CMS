<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$contents = '';
foreach($result as $record){
    // EDIT AND DELETE BUTTON
    $backend_url_content = '';
    if($allow_navigate_backend && ($have_edit_privilege || $have_delete_privilege)){
        // EDIT BUTTON
        if($have_edit_privilege){
            $backend_url_content .= '<a href="'.$backend_url.'/edit/'.$record->id.'" class="btn edit_record btn-default" primary_key = "'.$record->id.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
            $backend_url_content .= '&nbsp;';
        }
        // DELETE BUTTON
        if($have_delete_privilege){
            $backend_url_content .= '<a href="'.$backend_url.'/delete/'.$record->id.'" class="btn delete_record btn-danger" primary_key = "'.$record->id.'"><i class="glyphicon glyphicon-remove"></i> Delete</a>';
        }
    }
    // CONTENT
    $contents .= str_ireplace(
        array('{{ record_id }}', '{{ backend_url }}'),
        array($record->id, $backend_url_content),
        $record->content
    );
}

echo $contents;
