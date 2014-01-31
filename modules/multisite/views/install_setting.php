<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
echo '<h3>Setting '.$module_directory.'</h3>';
if(!$IS_ACTIVE){
    echo form_open(site_url($module_directory.'/install/setting'));
    echo form_label('Table Prefix').br();
    echo form_input('module_table_prefix', $module_table_prefix).br();
    echo form_label('Module Prefix').br();
    echo form_input('module_prefix', $module_prefix).br();
    echo form_submit('submit','Save Setting');
    echo form_close();
}else{
    echo 'No setting available';
}
echo br();
echo anchor(site_url('main/module_management'),'Back to module management');