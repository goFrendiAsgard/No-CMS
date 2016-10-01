<?php
    // get primary key
    $primary_key = '';
    foreach($columns as $column){
        $column_name = $column['name'];
        $column_role = $column['role'];
        $column_caption = $column['caption'];
        if($column_role == 'primary'){
            $primary_key = $column_name;
            break;
        }
    }
?>
&lt;?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$record_template = isset($record_template)? $record_template: $default_record_template;
$config = array(
        'record_template' => $record_template,
        'backend_url'     => $backend_url,
        'primary_key'     => '<?php echo $primary_key; ?>',
        'allow_edit'      => $have_edit_privilege && $allow_navigate_backend,
        'allow_delete'    => $have_delete_privilege && $allow_navigate_backend,
    );
foreach($result as $record){
    echo parse_record($record, $config);
}
