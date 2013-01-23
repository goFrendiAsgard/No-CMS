<?php
class Generator extends CMS_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->library('nordrassil/NordrassilLib');
		$this->nds = new NordrassilLib();
	}
	
	public function index($project_id){		
		$projects = $this->nds->get_project($project_id);
		$project_name = $projects['name'];
		$project_db_server = $projects['db_server'];
		$project_db_schema = $projects['db_schema'];
		$project_db_port = $projects['db_port'];
		$project_db_password = $projects['db_password'];
		$project_db_user = $projects['db_user'];
		$project_options = $projects['options'];
		$tables = $projects['tables'];
		
		$this->load->helper('inflector');
		$project_path = dirname(BASEPATH).'/modules/'.underscore($project_name).'/';
		
		$this->create_directory($project_path);
		$this->create_install_db_file($project_path, $tables);
		$this->create_uninstall_db_file($project_path, $tables);
		$this->create_installer($project_path, $tables, $project_name);
		$this->create_main_controller_and_view($project_path, $project_name);
		$this->create_controller_and_view($project_path, $project_name, $tables);
		$this->create_front_controller_and_view($project_path, $project_name, $tables);
		
		
		if($this->input->is_ajax_request()){
			$response = array('success'=>TRUE, 'message'=>'nothing wrong');
			$this->cms_show_json($response);
		}else{
			$this->cms_show_variable($projects);
		}
		
	}
	
	private function create_front_controller_and_view($project_path, $project_name, $tables){
		// filter tables, just the everything without "dont_make_form" option
		$selected_tables = array();
		for($i=0; $i<count($tables); $i++){
			$table = $tables[$i];
			if($table['options']['make_frontpage']){
				$selected_tables[] = $table;
			}
		}
		$tables = $selected_tables;
		
		$this->load->helper('inflector');
		// get save_project_name
		$save_project_name = underscore($project_name);
		foreach($tables as $table){
			$table_name = $table['name'];
			$table_caption = $table['caption'];
			$save_table_name = underscore($table_name);
			$controller_name = $save_project_name.'_'.$save_table_name;
			$navigation_name = $save_project_name.'_front_'.$save_table_name;
			$columns = $table['columns'];
			
			$pattern = array(
				'project_name',
				'controller_name',
				'table_name',
				'navigation_name',
				'table_caption',
			);
			$replacement = array(
				$save_project_name,
				$controller_name,
				$table_name,
				$navigation_name,
				$table_caption,
			);
			// prepare data
			$data = array(
				'table_name' => $table_name,
				'columns' => $columns
			);
			// controllers
			$str = $this->nds->read_view('nordrassil/default_generator/front_controller.php',$data,$pattern,$replacement);
			$this->nds->write_file($project_path.'controllers/front/'.$controller_name.'.php', $str);
			// models
			$str = $this->nds->read_view('nordrassil/default_generator/front_model.php',$data,$pattern,$replacement);
			$this->nds->write_file($project_path.'models/front/'.$controller_name.'_model.php', $str);
			// views
			$str = $this->nds->read_view('nordrassil/default_generator/front_view.php',$data,$pattern,$replacement);
			$this->nds->write_file($project_path.'views/front/'.$controller_name.'_index.php', $str);
		}
		
	}
	
	private function create_controller_and_view($project_path, $project_name, $tables){		
		// filter tables, just the everything without "dont_make_form" option
		$selected_tables = array();
		for($i=0; $i<count($tables); $i++){
			$table = $tables[$i];
			if(!$table['options']['dont_make_form']){
				$selected_tables[] = $table;
			}
		}
		$tables = $selected_tables;
		
		$this->load->helper('inflector');
		// get save_project_name
		$save_project_name = underscore($project_name);
		foreach($tables as $table){
			$table_name = $table['name'];
			$table_caption = $table['caption'];
			$save_table_name = underscore($table_name);
			$navigation_name = $save_project_name.'_'.$save_table_name;
			$columns = $table['columns'];
			// get field_list and display_as command
			$field_list_array = array();
			$display_as_array = array();
			$set_relation_array = array();
			$set_relation_n_n_array = array();
			$hide_field_array = array();
			$detail_callback_call_array = array();
			$detail_callback_declaration_array = array();
			$detail_before_delete_array = array();
			$detail_after_insert_or_update_array = array();
			foreach($columns as $column){
				if($column['role']=='primary') continue;
				$column_name = $column['name'];
				$column_caption = $column['caption'];
				// field_list
				$field_list_array[] = '\''.$column['name'].'\'';
				// display_as
				$display_as_array[] = $this->nds->read_view('nordrassil/default_generator/controller_partial/display_as',NULL,
					array('field_name', 'field_caption'),
					array($column_name, $column_caption)
				);
				// set_relation
				if($column['role']=='lookup'){
					$lookup_table_name = $column['lookup_table_name'];
					$lookup_column_name = $column['lookup_column_name'];
					$set_relation_array[] = $this->nds->read_view('nordrassil/default_generator/controller_partial/set_relation',NULL,
						array('field_name', 'lookup_table_name', 'lookup_field_name'),
						array($column_name, $lookup_table_name, $lookup_column_name)
					);
				}
				// set_relation_n_n
				if($column['role']=='detail many to many'){
					$relation_table_name = $column['relation_table_name'];
					$selection_table_name = $column['selection_table_name'];
					$relation_table_column_name = $column['relation_table_column_name'];
					$relation_selection_column_name = $column['relation_selection_column_name'];
					$selection_column_name = $column['selection_column_name'];
					$relation_priority_column_name = $column['relation_priority_column_name'];
					if($relation_priority_column_name==''){
						$relation_priority_column_name = 'NULL';
					}else{
						$relation_priority_column_name = '\''.$relation_priority_column_name.'\'';
					}
					$set_relation_n_n_array[] = $this->nds->read_view('nordrassil/default_generator/controller_partial/set_relation_n_n',NULL,
						array('field_name', 'relation_table_name', 'selection_table_name', 'relation_table_field_name',
							'relation_selection_field_name', 'selection_field_name', 'relation_priority_field_name'
						),
						array($column_name, $relation_table_name, $selection_table_name, $relation_table_column_name,
							$relation_selection_column_name, $selection_column_name, $relation_priority_column_name
						)
					);
				}
				// hide_field
				if($column['options']['hide']){
					$hide_field_array[] = $this->nds->read_view('nordrassil/default_generator/controller_partial/hide_field',NULL,
						'field_name',$column_name
					);
				}
				// detail (one to many) field
				if($column['role']=='detail one to many'){
					$detail_table_name = $column['relation_table_name'];
					$detail_foreign_key_name = $column['relation_table_column_name'];
					$detail_primary_key_name = '';
					$detail_table = array();
					foreach($tables as $detail_table_candidate){
						if($detail_table_candidate['name'] == $detail_table_name){
							$detail_table = $detail_table_candidate;
							$detail_columns = $detail_table['columns'];
							foreach($detail_columns as $detail_column){
								if($detail_column['role']=='primary'){
									$detail_primary_key_name = $detail_column['name'];
									break;
								}
							}
							break;
						}
					}
					$master_primary_key_name = '';
					foreach($columns as $primary_key_candidate){
						if($primary_key_candidate['role']=='primary'){
							$master_primary_key_name = $primary_key_candidate['name'];
							break;
						}
					}
					$data = array(
						'project_name' => $save_project_name,
						'master_table_name' => $table_name,
						'master_column_name' => $column_name,
						'master_primary_key_name' => $master_primary_key_name,						
						'detail_table_name' => $detail_table_name,
						'detail_foreign_key_name' => $detail_foreign_key_name,
						'detail_primary_key_name' => $detail_primary_key_name,						
						'detail_table' => $detail_table,
					);
					$detail_callback_call_array[] = $this->nds->read_view('nordrassil/default_generator/controller_partial/detail_callback_call',
						$data,
						'field_name',$column_name
					);
					$detail_callback_declaration_array[] = $this->nds->read_view('nordrassil/default_generator/controller_partial/detail_callback_declaration',
						NULL,
						'field_name',$column_name
					);
					// detail after insert_or_update
					$detail_after_insert_or_update_array[] = $this->nds->read_view('nordrassil/default_generator/controller_partial/detail_after_insert_or_update',
						$data
					);
					// detail before_delete
					$detail_before_delete_array[] = $this->nds->read_view('nordrassil/default_generator/controller_partial/detail_before_delete',
						$data
					);
					// view
					$str = $this->nds->read_view('nordrassil/default_generator/callback_field_view', $data);
					$this->nds->write_file($project_path.'views/data/field_'.$save_project_name.'_'.$table_name.'_'.$column_name.'.php', $str);
				}
			}
			$field_list = implode(',',$field_list_array);
			$display_as = implode(PHP_EOL, $display_as_array);
			$set_relation = implode(PHP_EOL, $set_relation_array);
			$set_relation_n_n = implode(PHP_EOL, $set_relation_n_n_array);
			$hide_field = implode(PHP_EOL, $hide_field_array);
			$detail_callback_call = implode(PHP_EOL, $detail_callback_call_array);
			$detail_callback_declaration = implode(PHP_EOL, $detail_callback_declaration_array);
			$detail_before_delete = implode(PHP_EOL, $detail_before_delete_array);
			$detail_after_insert_or_update = implode(PHP_EOL, $detail_after_insert_or_update_array);
			// create pattern & replacment
			$pattern = array(
				'navigation_name',
				'table_name',
				'table_caption',
				'controller_name',
				'field_list',
				'display_as',
				'set_relation',
				'set_relation_n_n',
				'hide_field',
				'directory',
				'detail_callback_call',
				'detail_callback_declaration',
				'detail_before_delete',
				'detail_after_insert_or_update'
			);
			$replacement = array(
				$navigation_name,
				$table_name,
				$table_caption,
				$navigation_name,
				$field_list,
				$display_as,
				$set_relation,
				$set_relation_n_n,
				$hide_field,
				$save_project_name,
				$detail_callback_call,
				$detail_callback_declaration,
				$detail_before_delete,
				$detail_after_insert_or_update,
			);
			// controllers
			$str = $this->nds->read_view('nordrassil/default_generator/controller.php',NULL,$pattern,$replacement);
			$this->nds->write_file($project_path.'controllers/data/'.$navigation_name.'.php', $str);
			// models
			$str = $this->nds->read_view('nordrassil/default_generator/model.php',NULL,$pattern,$replacement);
			$this->nds->write_file($project_path.'models/data/'.$navigation_name.'_model.php', $str);
			// views
			$str = $this->nds->read_view('nordrassil/default_generator/view.php',NULL,$pattern,$replacement);
			$this->nds->write_file($project_path.'views/data/'.$navigation_name.'_index.php', $str);
			
		}
		
	}
	
	private function create_main_controller_and_view($project_path, $project_name){
		$this->load->helper('inflector');
		// get save_project_name
		$save_project_name = underscore($project_name);
		$pattern = array(
			'navigation_parent_name',
			'directory',
			'main_controller',
			'project_name',
		); 
		$replacement = array(
			$save_project_name.'_index',
			$save_project_name,
			$save_project_name,
			$project_name,
		);
		// main controller
		$str = $this->nds->read_view('nordrassil/default_generator/main_controller', NULL, $pattern, $replacement);
		$this->nds->write_file($project_path.'controllers/'.$save_project_name.'.php', $str);
		// main view
		$str = $this->nds->read_view('nordrassil/default_generator/main_view', NULL, $pattern, $replacement);
		$this->nds->write_file($project_path.'views/'.$save_project_name.'_index.php', $str);
	}
	
	private function create_installer($project_path, $tables, $project_name){
		$this->load->helper('inflector');
		// get save_project_name
		$save_project_name = underscore($project_name);
		$table_list_array = array();
		// get table_list
		foreach($tables as $table){
			$table_list_array[] = '\''.$table['name'].'\'';
		}
		$table_list = implode(',',$table_list_array);
		
		//remove_navigations
		$remove_back_navigations = '';
		$remove_front_navigations = '';
		foreach(array_reverse($tables) as $table){
			if(!$table['options']['dont_make_form']){
				$table_name = $table['name'];
				$save_table_name = underscore($table_name);
				$navigation_name = $save_project_name.'_'.$save_table_name;
				$front_navigation_name = $save_project_name.'_front_'.$save_table_name;
				// back
				$str = $this->nds->read_view('nordrassil/default_generator/install_partial/remove_back_navigation',NULL,
					'navigation_name', $navigation_name
				);
				$remove_back_navigations .= $str.PHP_EOL;
				// front
				if($table['options']['make_frontpage']){					
					$str = $this->nds->read_view('nordrassil/default_generator/install_partial/remove_front_navigation',NULL,
						'front_navigation_name', $front_navigation_name);
					$remove_front_navigations .= $str.PHP_EOL;
				}
			}
		}
		$remove_navigations = $remove_front_navigations.$remove_back_navigations;
				
		//add_navigations
		$add_back_navigations = '';
		$add_front_navigations = '';
		foreach($tables as $table){
			if(!$table['options']['dont_make_form']){
				$table_name = $table['name'];
				$save_table_name = underscore($table_name);
				$table_caption = $table['caption'];
				$navigation_name = $save_project_name.'_'.$save_table_name;	
				$front_navigation_name = $save_project_name.'_front_'.$save_table_name;
				$pattern = 	array(
						'front_navigation_name',
						'navigation_name',
						'navigation_caption',
						'navigation_parent_name',
					);
				$replacement = array(
						$front_navigation_name,
						$navigation_name,
						$table_caption,
						$save_project_name.'_index',
					);
				// back
				$str = $this->nds->read_view('nordrassil/default_generator/install_partial/add_back_navigation',NULL,
					$pattern, $replacement);
				$add_back_navigations .= $str.PHP_EOL;
				// front
				if($table['options']['make_frontpage']){					
					$str = $this->nds->read_view('nordrassil/default_generator/install_partial/add_front_navigation',NULL,
						$pattern, $replacement);
					$add_front_navigations .= $str.PHP_EOL;
				}
			}
		}
		$add_navigations = $add_front_navigations.$add_back_navigations;
		
		$pattern = array(
			'namespace',
			'table_list',
			'navigation_parent_name',
			'remove_navigations',
			'add_navigations',
			'directory',
			'main_controller',
			'project_name',
		); 
		$replacement = array(
			underscore($this->cms_user_name()).'.'.$save_project_name,
			$table_list,
			$save_project_name.'_index',
			$remove_navigations,
			$add_navigations,
			$project_name,
			$save_project_name,
			$project_name
		);
		$str = $this->nds->read_view('default_generator/install',NULL,$pattern, $replacement);
		$this->nds->write_file($project_path.'controllers/install.php', $str);
	}

	private function create_directory($project_path){
		// prepare directory		
		$this->nds->make_directory($project_path);
		$this->nds->make_directory($project_path.'assets/');
		$this->nds->make_directory($project_path.'assets/db/');
		$this->nds->make_directory($project_path.'assets/languages/');
		$this->nds->make_directory($project_path.'assets/navigation_icon/');
		$this->nds->make_directory($project_path.'assets/scripts/');
		$this->nds->make_directory($project_path.'assets/styles/');
		$this->nds->make_directory($project_path.'assets/images/');
		$this->nds->make_directory($project_path.'controllers/');
		$this->nds->make_directory($project_path.'controllers/data/');
		$this->nds->make_directory($project_path.'controllers/front/');
		$this->nds->make_directory($project_path.'models/');
		$this->nds->make_directory($project_path.'models/data');
		$this->nds->make_directory($project_path.'models/front');
		$this->nds->make_directory($project_path.'views/');
		$this->nds->make_directory($project_path.'views/data');
		$this->nds->make_directory($project_path.'views/front');
		// create htaccess
		$str = $this->nds->read_view('default_generator/htaccess');
		$this->nds->write_file($project_path.'assets/db/.htaccess', $str);
		$this->nds->write_file($project_path.'assets/languages/.htaccess', $str);
		$this->nds->write_file($project_path.'controllers/.htaccess', $str);
		$this->nds->write_file($project_path.'models/.htaccess', $str);
	}

	private function create_install_db_file($project_path, $tables){
		$selected_tables = array();
		for($i=0; $i<count($tables); $i++){
			$table = $tables[$i];
			if(!$table['options']['dont_create_table']){
				$selected_tables[] = $table;
			}
		}
		$str = $this->nds->get_create_table_syntax($selected_tables);
		$this->nds->write_file($project_path.'assets/db/install.sql', $str);
	}
	
	private function create_uninstall_db_file($project_path, $tables){
		$selected_tables = array();
		for($i=0; $i<count($tables); $i++){
			$table = $tables[$i];
			if(!$table['options']['dont_create_table']){
				$selected_tables[] = $table;
			}
		}
		$str = $this->nds->get_drop_table_syntax($selected_tables);
		$this->nds->write_file($project_path.'assets/db/uninstall.sql', $str);
	}
	
}
?>