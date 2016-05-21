<?php
	if(!function_exists('strip_table_prefix')){
	    function strip_table_prefix($table_name, $table_prefix){
	        if(!isset($table_prefix) || $table_prefix == ''){
	            return $table_name;
	        }
	        if(strpos($table_name, $table_prefix.'_') === 0){
	            $table_name = substr($table_name, strlen($table_prefix.'_'));
	        }
	        return $table_name;
	    }
	}

    $stripped_table_name = strip_table_prefix($table_name, $table_prefix);

	$select_array = array();
	$join_array = array();
	$like_array = array();
	foreach($columns as $column){
		$column_name = $column['name'];
		$column_role = $column['role'];
		if($column_role == 'primary' || $column_role == ''){
			$select_array[] = $stripped_table_name.'.'.$column_name;
			if($column_role != 'primary'){
				$like_array[] = '\''.$stripped_table_name.'.'.$column_name.'\', $keyword';
			}
		}else if($column_role == 'lookup'){
			$lookup_table_name = $column['lookup_table_name'];
            $stripped_lookup_table_name = strip_table_prefix($lookup_table_name, $table_prefix);
			$lookup_column_name = $column['lookup_column_name'];
			$lookup_table_primary_key = $column['lookup_table_primary_key'];
			$select_array[] = $stripped_lookup_table_name.'.'.$lookup_column_name.' as '.$stripped_lookup_table_name.'_'.$lookup_column_name;
			$join_array[] = '$this->t(\''.$stripped_lookup_table_name.'\').\' as '.$stripped_lookup_table_name.'\', \''.$stripped_table_name.'.'.$column_name.'='.strip_table_prefix($lookup_table_name, $table_prefix).'.'.$lookup_table_primary_key.'\', \'left\'';
			$like_array[] = '\''.strip_table_prefix($lookup_table_name, $table_prefix).'.'.$lookup_column_name.'\', $keyword';
		}
	}
	$select = implode(', ',$select_array);
?>
&lt;?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of {{ model_name }}
 *
 * @author No-CMS Module Generator
 */
class {{ model_name }} extends  CMS_Model{

    public function get_data($keyword, $page=0){
        $limit = 10;
        $query = $this->db->select('<?php echo $select; ?>')
            ->from($this->t('<?php echo $stripped_table_name; ?>').' as <?php echo $stripped_table_name; ?>')
<?php
	foreach($join_array as $join){
		echo '            ->join('.$join.')'.PHP_EOL;
	}
	for($i=0; $i<count($like_array); $i++){
		if($i==0){
			echo '            ->like('.$like_array[$i].')'.PHP_EOL;
		}else{
			echo '            ->or_like('.$like_array[$i].')'.PHP_EOL;
		}

	}
?>
            ->limit($limit, $page*$limit)
            ->get();
        $result = $query->result();
        return $result;
    }

}
