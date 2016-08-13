<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$record_template = isset($record_template)? $record_template: '<div id="record_{{ record:city_id }}" class="record_container panel panel-default">
    <div class="panel-body">
        <!-- COUNTRY -->
        <div class="row">
            <div class="col-md-4"><b>Country</b></div>
            <div class="col-md-8">{{ record:country_name }}</div>
        </div>
        <!-- NAME -->
        <div class="row">
            <div class="col-md-4"><b>Name</b></div>
            <div class="col-md-8">{{ record:name }}</div>
        </div>
        <div class="edit_delete_record_container pull-right">{{ backend_urls }}</div>
        <div style="clear:both;"></div>
    </div>
</div>';
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
