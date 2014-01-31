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
?>
&lt;?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$contents = '';
for($i=0; $i<count($result); $i++){
	$record = $result[$i];
	$contents .= '<div id="record_'.$record-><?php echo $primary_key; ?>.'" class="record_container well">';

	// show columns
<?php
	for($i=0; $i<count($fields); $i++){
		echo '	$contents .= \'<b>'.$captions[$i].' :</b> \'.$record->'.$fields[$i].'.\'  <br />\'; '.PHP_EOL;
	}
?>

	// edit and delete button
	if($allow_navigate_backend){
		$contents .= '<div class="edit_delete_record_container">';
		$contents .= '<a href="'.$backend_url.'/edit/'.$record-><?php echo $primary_key; ?>.'" class="btn edit_record btn-default" primary_key = "'.$record-><?php echo $primary_key; ?>.'">Edit</a>';
		$contents .= '&nbsp;';
		$contents .= '<a href="'.$backend_url.'/delete/'.$record-><?php echo $primary_key; ?>.'" class="btn delete_record btn-danger" primary_key = "'.$record-><?php echo $primary_key; ?>.'">Delete</a>';
		$contents .= '</div>';
	}
	$contents .= '</div>';
}

echo $contents;