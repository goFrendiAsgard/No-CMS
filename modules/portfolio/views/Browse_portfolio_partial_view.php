<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$record_template = isset($record_template)? $record_template: $default_record_template;
$config = array(
        'record_template' => $record_template,
        'backend_url'     => $backend_url,
        'primary_key'     => 'id',
        'allow_edit'      => $have_edit_privilege && $allow_navigate_backend,
        'allow_delete'    => $have_delete_privilege && $allow_navigate_backend,
    );
foreach($result as $record){
    if($record->image != ''){
        $record->image_component = '<img class="col-xs-12" src="{{ MODULE_BASE_URL }}assets/uploads/'.$record->image.'" />';
        $record->image_link = '<a target="blank" class="btn btn-primary" href="{{ MODULE_BASE_URL }}assets/uploads/'.$record->image.'"><i class="glyphicon glyphicon-image"></i> Show</a>';
    }else{
        $record->image_component = '';
        $record->image_link = '';
    }
    if($record->url != ''){
        $record->url = '<a target="blank" href="'.$record->url.'" class="btn btn-default">Visit</a>';
    }
    echo parse_record($record, $config);
}
