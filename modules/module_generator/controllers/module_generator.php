<?php 

class Module_Generator extends CMS_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model($this->cms_module_path().'/generator_model');		
	}
	public function index(){
		$hostname = $this->input->post('hostname');
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$database = $this->input->post('database');
		$dbdriver = $this->input->post('dbdriver');
		$custom_setting = $this->input->post('custom_setting');
		$submitted = $this->input->post('submit')?TRUE:FALSE;
		if(!$hostname) $hostname = "localhost";
		if(!$username) $username = "root";
		$config = array(
				"hostname" => $hostname,
				"username" => $username,
				"password" => $password,
				"database" => $database,
				"dbdriver" => $dbdriver,
			);
		$data = $config;
		$data["custom_setting"] = $custom_setting;
		if($submitted && !$custom_setting){
			$this->session->unset_userdata('db_config');
			redirect(site_url($this->cms_module_path().'/module_generator/set'));
		}else if($submitted && $custom_setting){
			$success = @mysql_connect($hostname, $username, $password) &&
				@mysql_select_db($database);
			if($success){
				$this->session->set_userdata(array("db_config"=>$config));
				redirect(site_url($this->cms_module_path().'/module_generator/set'));
			}else{
				$data["error"] = TRUE;
				$this->view('generator_index', $data, 'module_generator_index');
			}
		}else{	
			$data["error"] = FALSE;					
			$this->view('generator_index', $data, 'module_generator_index');
		}
	}
	public function set(){
		$data = array(
				"tables" => $this->generator_model->list_tables()
			);
		$this->view('generator_set', $data, 'module_generator_index');
	}
	public function generate(){
		$this->load->helper('inflector');
		// get data from post
		$namespace = $this->input->post('namespace');
		$directory = $this->input->post('directory');
		$structures = $this->input->post('structure');
		$overwrite = $this->input->post('overwrite');
		if($namespace) $namespace = underscore($namespace);
		if($directory) $directory = underscore($directory);
		
		// error checking
		$errors = array();
		if(!$namespace){
			$errors[] = "Undefined namespace";
		}
		if(!$directory){
			$errors[] = "Undefined directory";
		}else{
			if($overwrite && $this->is_exists($directory) && !$this->is_writable($directory)){
				$errors[] = "Directory '/modules/$directory' is not writable";		
			}else if(!$overwrite && $this->is_exists($directory)){
				$errors[] = "Directory '/modules/$directory' already exists, activate 'Force overwrite' option to overwrite";
			}
		}
		if(!$structures){
			$errors[] = "No table selected";
		}		
		if(!$this->is_writable('')){
			$errors[] = "Directory '/modules' is not writable";
		}
		
		// if error show the error, else do the process
		if(count($errors)>0){
			$return = array(
					"success"=>false,
					"errors"=>$errors
				);
			$this->cms_show_json($return);
		}else{
			// make controllers variable
			$controllers = array();
			foreach($structures as $structure){
				$controller_name = underscore($structure['controller_name']);
				$table_name = $structure['table_name'];
				$navigation_caption = $structure['navigation_caption'];
				$function_name = underscore($navigation_caption);
				
				if($function_name == $controller_name){
					$function_name = 'action_'.$function_name;
				}
				
				$result = array(
						"table_name" => $table_name,
						"fields" => $this->generator_model->list_fields($table_name),
						"ai_field" => $this->generator_model->auto_increment_field($table_name),
						"navigation_name" => underscore($directory.'_'.$navigation_caption),
						"navigation_caption" => $navigation_caption,
						"function_name" => $function_name,
						"navigation_url"=>$controller_name.'/'.$function_name,
					);
				
				if(!isset($controllers[$controller_name])){
					$controllers[$controller_name] = array();
				}			
				$controllers[$controller_name][] = $result;
			}	
	
			// we got all the data
			$module = array(
					"namespace" => $namespace,
					"directory" => $directory,
					"overwrite" => $overwrite,
					"controllers" => $controllers,
				);		
			$this->make_module($module);
			// return success
			$return = array(
						"success"=>true,
						"module"=>$module
					);
			$this->cms_show_json($return);
			
		}
	}
    
    private function make_module($module){
    	$this->load->helper('inflector');
    	
    	$current_module_path = $this->cms_module_path();
    	$directory = $module['directory'];
    	$controllers = $module['controllers'];
    	$overwrite = $module['overwrite'];
    	$namespace = $module['namespace'];
    	
    	// delete the old one, if overwrite choosen
    	if($overwrite){
    		$this->remove_file($directory);
    	}
    	// create all necessary directory
    	$this->make_folder($directory);
    	$this->make_folder($directory.'/models');
    	$this->make_folder($directory.'/models/data');
    	$this->make_folder($directory.'/views');
    	$this->make_folder($directory.'/views/data');
    	$this->make_folder($directory.'/controllers');
    	$this->make_folder($directory.'/controllers/data');
    	$this->make_folder($directory.'/assets');
    	$this->make_folder($directory.'/assets/db');
    	$this->make_folder($directory.'/assets/images');
    	$this->make_folder($directory.'/assets/scripts');
    	$this->make_folder($directory.'/assets/styles');
    	
    	$this->make_htaccess_deny($directory.'/models');
    	$this->make_htaccess_deny($directory.'/views');
    	$this->make_htaccess_deny($directory.'/controllers');
    	$this->make_htaccess_deny($directory.'/assets/db');
    	
    	// get all unique table_name & controller name    	
    	$unique_table_names = array();
    	$controller_names = array();
    	$navigations = array();
    	foreach($controllers as $controller_name=>$properties){
    		$controller_names[] = $controller_name;
    		for($i=0; $i<count($properties); $i++){
    			$property = $properties[$i];
	    		$table_name = $property['table_name'];
	    		$navigations[] = array(
	    				"navigation_name" => $property["navigation_name"],
	    				"navigation_caption"=>$property["navigation_caption"],
	    				"navigation_url"=>$property["navigation_url"],
	    			);
	    		// check if table_name already exist in unique_table_names
	    		$exist = false;
	    		foreach($unique_table_names as $unique_table_name){
	    			if($unique_table_name == $table_name){
	    				$exists = true;
	    				break;
	    			}
	    		}
	    		// add if not exist
	    		if(!$exist){
	    			$unique_table_names[] = $table_name;
	    		}
    		}
    	}
    	
    	// assets/db/install.sql
    	$str = $this->generator_model->get_create_tables_syntax($unique_table_names);
    	$this->make_file($directory.'/assets/db/install.sql', $str);
    	
    	// assets/db/uninstall.sql
    	$reverse_unique_table_names = $this->reverse_array($unique_table_names); 
    	$str = $this->generator_model->get_drop_tables_syntax($reverse_unique_table_names);
    	$this->make_file($directory.'/assets/db/uninstall.sql', $str);
    	
    	// controllers/install.php
    	$add_navigation_template = $this->read_file($current_module_path."/res/install_partial/add_navigation.php.txt");
    	$remove_navigation_template = $this->read_file($current_module_path."/res/install_partial/remove_navigation.php.txt");
    	$navigation_parent_name = $directory.'_index'; 
    	$add_navigation = "";
    	$remove_navigation = "";    	
    	foreach($navigations as $navigation){
    		$search = array(
    				'@navigation_name', 
    				'@navigation_caption',
    				'@navigation_url', 
    				'@navigation_parent_name',
    			);
    		$replace = array(
    				$navigation['navigation_name'],
    				$navigation['navigation_caption'],
    				$navigation['navigation_url'],
    				$navigation_parent_name,
    			);
    		$add_navigation .= $this->replace($add_navigation_template, $search, $replace).PHP_EOL;
    		$remove_navigation = $this->replace($remove_navigation_template, $search, $replace).
    			PHP_EOL.$remove_navigation;
    	}
    	$quoted_unique_table_names = array();
    	foreach($unique_table_names as $table_name){
    		$quoted_unique_table_names[] = "'".$table_name."'";
    	}
    	$unique_table_list = implode(', ', $quoted_unique_table_names);
    	$search = array(
    			'@navigation_parent_name',
    			'@add_navigations',
    			'@remove_navigations',
    			'@namespace',
    			'@directory',
    			'@table_list',
    		);
    	$replace = array(
    			$navigation_parent_name,
    			$add_navigation,
    			$remove_navigation,
    			$namespace,
    			$directory,
    			$unique_table_list,
    		); 
    	$this->copy_file($current_module_path.'/res/install.php.txt', 
    			$directory.'/controllers/install.php', $search, $replace); 
    	
    	// controllers/@directory.php
    	$search = array('@directory', '@navigation_parent_name');
    	$replace = array($directory, $navigation_parent_name);
    	$this->copy_file($current_module_path.'/res/main_controller.php.txt',
    			$directory.'/controllers/'.$directory.'.php', $search, $replace);
    	
    	// views/@directory.php
    	$search = array('@directory');
    	$replace = array($directory);
    	$this->copy_file($current_module_path.'/res/main_view.php.txt',
    			$directory.'/views/'.$directory.'_index.php', $search, $replace);
    	
    	
    	// controllers/data/controller.php
    	$display_as_template = $this->read_file($current_module_path."/res/controller_partial/display_as.php.txt");
    	$function_template = $this->read_file($current_module_path."/res/controller_partial/function.php.txt");
    	foreach($controllers as $controller_name=>$properties){
    		$function = '';    		   		
    		foreach($properties as $property){
    			$display_as = '';
    			$table_name = $property['table_name'];
    			$navigation_name = $property['navigation_name'];
    			$fields = $property['fields'];
    			$ai_field = $property['ai_field'];
    			$function_name = $property['function_name'];
    			$quoted_fields = array();
    			foreach($fields as $field){
    				if($field == $ai_field) continue;
    				// quoted_fields
    				$quoted_fields[] = "'".$field."'";
    				// display_as
    				$search = array('@field_name', '@field_caption');
    				$replace = array($field, humanize($field));
    				$display_as .= $this->replace($display_as_template, $search, $replace).PHP_EOL;
    			}
    			$field_list = implode(',', $quoted_fields);
    			// functions
    			$search = array(
    					'@table_name',
    					'@function_name',
    					'@field_list',
    					'@navigation_name',
    					'@display_as',
    				);
    			$replace = array(
    					$table_name,
    					$function_name,
    					$field_list,
    					$navigation_name,
    					$display_as,
    				);
    			$function .= $this->replace($function_template, $search, $replace).PHP_EOL.PHP_EOL;
    		}
    		$search = array('@controller_name', '@functions');
    		$replace = array($controller_name, $function);
    		$this->copy_file($current_module_path.'/res/controller.php.txt',
    				$directory.'/controllers/data/'.$controller_name.'.php', $search, $replace);
    		
    	}
    	
    	
    }
	
    private function is_exists($fileName){
    	return file_exists(BASEPATH.'../modules/'.$fileName);
    }
    
    private function is_writable($fileName){    	
    	return is_writable(BASEPATH.'../modules/'.$fileName);
    }
    
    private function make_folder($folderName){
    	mkdir(BASEPATH.'../modules/'.$folderName, 0777);
    	chmod(BASEPATH.'../modules/'.$folderName, 0777);
    }
    
    private function make_file($fileName, $content){
    	file_put_contents(BASEPATH.'../modules/'.$fileName, $content);
    	chmod(BASEPATH.'../modules/'.$fileName, 0777);
    }
    
    private function make_htaccess_deny($folderName){
    	$this->make_file($folderName.'/.htaccess', 'Deny From All');
    }
    
    private function read_file($fileName){
    	return file_get_contents(BASEPATH.'../modules/'.$fileName);
    }
    
    private function copy_file($source, $destination, $search, $replace){
    	$content = $this->read_file($source);
    	$content = $this->replace($content, $search, $replace);
    	$this->make_file($destination, $content);
    }
    
    private function remove_file($fileName, $recursion_call = false){
    	if(!$recursion_call){
    		$fileName = BASEPATH.'../modules/'.$fileName;
    	}
    	if (is_dir($fileName)) {
    		$objects = scandir($fileName);
    		foreach ($objects as $object) {
    			if ($object != "." && $object != "..") {
    				$this->remove_file($fileName."/".$object, true);
    			}
    		}
    		rmdir($fileName);
    	}else{
    		unlink($fileName);
    	}
    }
    
    private function replace($str,$search,$replace){
    	if(count($search)==count($replace)){
    		for($i=0; $i<count($search); $i++){
    			$str = str_replace($search, $replace, $str);
    		}
    	}
    	return $str;
    }
    
    private function reverse_array($arr){
    	$result = array();
    	for($i=count($arr)-1; $i>=0; $i--){
    		$result[] = $arr[$i];
    	}
    	return $result;
    }	
	
}