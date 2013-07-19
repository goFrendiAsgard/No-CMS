<?php
class Synchronize_Model extends CMS_Model{
	private $connection;
	private $db_server;
	private $db_user;
	private $db_port;
	private $db_password;
	private $db_schema;
	private $numeric_data_type = array(
			'int','real','tinyint', 'smallint', 'mediumint',
			'integer', 'bigint', 'float', 'double',
			'decimal', 'numeric',
		);

	public function synchronize($project_id){
		// make project_id save of SQL injection
		$save_project_id = addslashes($project_id);

		// delete related column_option
		$where = "column_id IN (SELECT column_id FROM ".$this->cms_complete_table_name('column').
		  ", ".$this->cms_complete_table_name('table')." WHERE
		  ".$this->cms_complete_table_name('column.table_id')." = ".$this->cms_complete_table_name('table').".table_id AND project_id='$save_project_id')";
		$this->db->delete($this->cms_complete_table_name('column_option'),$where);

		// delete related column
		$where = "table_id IN (SELECT table_id FROM ".$this->cms_complete_table_name('table')." WHERE project_id='$save_project_id')";
		$this->db->delete($this->cms_complete_table_name('column'),$where);

		// delete related table_option
		$where = "table_id IN (SELECT table_id FROM ".$this->cms_complete_table_name('table')." WHERE project_id='$save_project_id')";
		$this->db->delete($this->cms_complete_table_name('table_option'),$where);

		// delete from table
		$where = array('project_id'=>$project_id);
		$this->db->delete($this->cms_complete_table_name('table'),$where);


		// select the current nor_project
		$query = $this->db->select('db_server, db_user, db_password, db_schema, db_port, db_table_prefix')
			->from($this->cms_complete_table_name('project'))
			->where(array('project_id'=>$project_id))
			->get();
		if($query->num_rows()>0){
			$row = $query->row();
			$this->db_server = $row->db_server;
			$this->db_port = $row->db_port;
			$this->db_user = $row->db_user;
			$this->db_password = $row->db_password;
			$this->db_schema = $row->db_schema;
			$this->db_table_prefix = $row->db_table_prefix;
			if(!isset($this->db_port) || $this->db_port == ''){
				$this->db_port = '3306';
			}
			$this->connection = mysqli_connect($this->db_server, $this->db_user, $this->db_password, 'information_schema', $this->db_port);
			mysqli_select_db($this->connection, 'information_schema');
			$this->create_table($project_id);
			return TRUE;
		}else{
			return FALSE;
		}
	}

	private function create_table($project_id){
		$this->load->helper('inflector');
		$save_db_schema = addslashes($this->db_schema);
		$save_db_table_prefix = addslashes($this->db_table_prefix);
		$SQL = "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA='$save_db_schema' AND TABLE_NAME like'$save_db_table_prefix%'";
		$result = mysqli_query($this->connection, $SQL);

		while($row = mysqli_fetch_array($result)){
			// create caption
			$caption = '';
			$prefix_length = isset($this->db_table_prefix)?strlen($this->db_table_prefix):0;
			if($prefix_length>0){
				$caption = substr($row['TABLE_NAME'], $prefix_length);
				$caption = humanize($caption);
				$caption = trim($caption);
				if(strlen($caption)==0){
					$caption = humanize($row['TABLE_NAME']);
				}
			}else{
				$caption = humanize($row['TABLE_NAME']);
			}
			// inserting the table
			$data = array(
					'project_id' => $project_id,
					'name'=> $row['TABLE_NAME'],
					'caption' => $caption
				);
			$this->db->insert($this->cms_complete_table_name('table'), $data);
			// inserting the field
			$table_id = $this->db->insert_id();
			$table_name = $row['TABLE_NAME'];
			$this->create_field($table_id, $table_name);
		}
	}

	private function create_field($table_id, $table_name){
		$this->load->helper('inflector');
		$save_db_schema = addslashes($this->db_schema);
		$save_table_name = addslashes($table_name);
		$SQL =
			"SELECT
				COLUMN_NAME, DATA_TYPE, IS_NULLABLE, CHARACTER_MAXIMUM_LENGTH,
				NUMERIC_PRECISION, NUMERIC_SCALE, COLUMN_KEY
			FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='$save_db_schema' AND TABLE_NAME='$save_table_name'";
		$result = mysqli_query($this->connection, $SQL);
		while($row = mysqli_fetch_array($result)){
			if($row['COLUMN_KEY'] == 'PRI'){
				$role = 'primary';
			}else{
				$role = '';
			}
			$data_type = $row['DATA_TYPE'];
			$length = NULL;
			$value_selection_mode = NULL;
			$value_selection_item = NULL;
			// get enum or set
			if($data_type == 'set' || $data_type == 'enum'){
				$pattern = '/^'.$data_type.'\((.*)\)$/';
				$value_selection_mode = $data_type;
				$data_type = 'varchar';
				$length = 255;

				$column_sql = "SHOW COLUMNS FROM $save_db_schema.$save_table_name WHERE field ='".addslashes($row['COLUMN_NAME'])."'";
				$column_result = mysqli_query($this->connection, $column_sql);
				$column_row = mysqli_fetch_array($column_result);
				$type = $column_row['Type'];
				$matches = array();
				if(preg_match($pattern, $type, $matches)>0){
					$value_selection_item = $matches[1];
				}
			}else{
				// get length (data_size) of the column
				if(in_array($data_type, $this->numeric_data_type)){
					$length = $row['NUMERIC_PRECISION'];
				}else{
					$length = $row['CHARACTER_MAXIMUM_LENGTH'];
				}
				if(!isset($length)){
					$length = 10;
				}
			}
			if($role == 'primary'){
				$data_type = 'int';
				$length = 10;
			}
			// inserting the field
			$data = array(
					'table_id' => $table_id,
					'name'=> $row['COLUMN_NAME'],
					'caption' => humanize($row['COLUMN_NAME']),
					'data_type' => $data_type,
					'data_size' => $length,
					'role' => $role,
					'value_selection_mode'=>$value_selection_mode,
					'value_selection_item'=>$value_selection_item,
				);
			$this->db->insert($this->cms_complete_table_name('column'), $data);
		}

	}
}
?>