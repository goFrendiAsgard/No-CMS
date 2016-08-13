<?php
    if(!function_exists('front_view_partial_strip_table_prefix')){
        function front_view_partial_strip_table_prefix($project_db_table_prefix, $table_name){
            if(!isset($project_db_table_prefix) || $project_db_table_prefix == ''){
                return $table_name;
            }
            if(strpos($table_name, $project_db_table_prefix) === 0){
                $table_name = substr($table_name, strlen($project_db_table_prefix));
            }
            if($table_name[0]=='_'){
                $table_name = substr($table_name,1);
            }
            return $table_name;
        }
    }

    $fields = array();
    $captions = array();
    $primary_key = '';
    foreach($columns as $column){
        $column_name = $column['name'];
        $column_role = $column['role'];
        $column_caption = $column['caption'];
        if($column_role == 'primary'){
            $primary_key = $column_name;
        }else if($column_role == ''){
            $fields[] = $column_name;
            $captions[] = $column_caption;
        }else if($column_role == 'lookup'){
            $lookup_table_name = $column['lookup_table_name'];
            $lookup_table_name = front_view_partial_strip_table_prefix($table_prefix, $lookup_table_name);
            $lookup_column_name = $column['lookup_column_name'];
            $fields[] = $lookup_table_name.'_'.$lookup_column_name;
            $captions[] = $column_caption;
        }
    }
    // defining record_template
    $record_template  = '<div id="record_{{ record:'.$primary_key.' }}" class="record_container panel panel-default">'.PHP_EOL;
    $record_template .= '    <div class="panel-body">'.PHP_EOL;
    for($i=0; $i<count($fields); $i++){
        $record_template .= '        <!-- '.strtoupper($captions[$i]).' -->'.PHP_EOL;    
        $record_template .= '        <div class="row">'.PHP_EOL;
        $record_template .= '            <div class="col-md-4"><b>'.$captions[$i].'</b></div>'.PHP_EOL;
        $record_template .= '            <div class="col-md-8">{{ record:'.$fields[$i].' }}</div>'.PHP_EOL;
        $record_template .= '        </div>'.PHP_EOL;
    }
    $record_template .= '        <div class="edit_delete_record_container pull-right">{{ backend_urls }}</div>'.PHP_EOL;
    $record_template .= '        <div style="clear:both;"></div>'.PHP_EOL;
    $record_template .= '    </div>'.PHP_EOL;
    $record_template .= '</div>';

?>
&lt;?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$record_template = isset($record_template)? $record_template: '<?php echo $record_template; ?>';
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
