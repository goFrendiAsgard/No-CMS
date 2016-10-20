<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$record_template = isset($record_template)? $record_template: $default_record_template;
$config = array(
        'record_template' => $record_template,
        'backend_url'     => $backend_url,
        'primary_key'     => 'city_id',
        'allow_edit'      => $have_edit_privilege && $allow_navigate_backend,
        'allow_delete'    => $have_delete_privilege && $allow_navigate_backend,
    );
foreach($result as $record){
    echo parse_record($record, $config);
}
