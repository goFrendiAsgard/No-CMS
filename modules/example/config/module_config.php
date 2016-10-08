<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['module_table_prefix']  = 'twn';
$config['module_prefix']        = 'example';

// Record template configuration

$config['example_city_record_template'] = '<div id="record_{{ record:city_id }}" class="record_container panel panel-default">'.PHP_EOL.
    '    <div class="panel-body">'.PHP_EOL.
    '        <!-- COUNTRY -->'.PHP_EOL.
    '        <div class="row">'.PHP_EOL.
    '            <div class="col-md-4"><b>Country</b></div>'.PHP_EOL.
    '            <div class="col-md-8">{{ record:country_name }}</div>'.PHP_EOL.
    '        </div>'.PHP_EOL.
    '        <!-- NAME -->'.PHP_EOL.
    '        <div class="row">'.PHP_EOL.
    '            <div class="col-md-4"><b>Name</b></div>'.PHP_EOL.
    '            <div class="col-md-8">{{ record:name }}</div>'.PHP_EOL.
    '        </div>'.PHP_EOL.
    '        <div class="edit_delete_record_container pull-right">{{ backend_urls }}</div>'.PHP_EOL.
    '        <div style="clear:both;"></div>'.PHP_EOL.
    '    </div>'.PHP_EOL.
    '</div>';
