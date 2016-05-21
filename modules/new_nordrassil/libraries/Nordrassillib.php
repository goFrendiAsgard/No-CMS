<?php
class Nordrassillib{
	public function __construct(){
		$this->ci =& get_instance();
		$this->ci->load->model('nordrassil/nds_model');
		$this->available_data_type = $this->ci->nds_model->available_data_type;
		$this->type_without_length = $this->ci->nds_model->type_without_length;
		$this->auto_increment_data_type = $this->ci->nds_model->auto_increment_data_type;
		$this->detault_data_type = $this->ci->nds_model->detault_data_type;
	}
	public function get_project($project_id){
		return $this->ci->nds_model->get_project($project_id);
	}
	public function install_template($template_name, $generator_path, $project_options = array(), $table_options = array(), $column_options = array()){
		return $this->ci->nds_model->install_template($template_name, $generator_path, $project_options, $table_options, $column_options);
	}
	public function get_create_table_syntax($tables){
		return $this->ci->nds_model->get_create_table_syntax($tables);
	}
    public function get_drop_table_forge($tables){
        return $this->ci->nds_model->get_drop_table_forge($tables);
    }
    public function get_create_table_forge($tables, $default_fields = array()){
        return $this->ci->nds_model->get_create_table_forge($tables, $default_fields);
    }
    public function get_insert_table($tables){
    	return $this->ci->nds_model->get_insert_table($tables);
    }
	public function get_drop_table_syntax($tables){
		return $this->ci->nds_model->get_drop_table_syntax($tables);
	}
	public function get_insert_table_syntax($project_id, $tables){
		return $this->ci->nds_model->get_insert_table_syntax($project_id, $tables);
	}
	public function replace($string, $pattern, $replacement){
		if(!isset($pattern)){
			return $string;
		}
		if(is_array($pattern)){
			$patterns = $pattern;
			$replacements = $replacement;
			for($i=0; $i<count($patterns); $i++){
				$pattern = $patterns[$i];
				$replacement = $replacements[$i];
				$string = $this->replace($string, $pattern, $replacement);
			}
			return $string;
		}else{
			return str_replace('{{ '.$pattern.' }}', $replacement, $string);
		}
	}
	public function delete_file($file_or_directory_name){
		if(is_file($file_or_directory_name)){
			unlink($file_or_directory_name);
		}else if(is_dir($file_or_directory_name)){
			$files = glob($file_or_directory_name . '*', GLOB_MARK);
			foreach($files as $file){
				$this->delete_file($file_or_directory_name);
			}
			rmdir($file_or_directory_name);
		}
	}
	public function copy_file($source_file_name, $destination_file_name){
		copy($source_file_name, $destintation_file_name);
	}
	public function read_file($file_name){
		return file_get_contents($file_name);
	}
	public function write_file($file_name, $content){
		@chmod(dirname($file_name), 0777);
	    if(file_exists($file_name)){
	        @chmod($file_name, 0777);
            if(file_get_contents($file_name) != $content){
                $backup_file_name = $file_name.'.'.date('Y-m-d-H-i-s').'.bak';
                copy($file_name, $backup_file_name);
                unlink($file_name);
            }
	    }
		file_put_contents($file_name, $content);
        @chmod($file_name, 0777);
	}
	public function copy_file_and_replace($source_file_name, $destination_file_name, $pattern, $replacement){
		$string = file_get_contents($source_file_name);
		$string = $this->replace($string, $pattern, $replacement);
		file_put_contents($destination_file_name, $string);
	}
	public function make_directory($directory_name){
		if(!is_dir($directory_name)){
			mkdir($directory_name,0777,TRUE);
		}
		@chmod($directory_name,0777);
	}
	public function read_view($view_name, $data=NULL, $pattern=NULL, $replacement=NULL){
		$string = $this->ci->load->view($view_name,$data,True);
		$string = $this->replace($string, $pattern, $replacement);
		$string = str_replace(array('&lt;?', '?&gt;'), array('<?', '?>'), $string);
		return $string;
	}
}
?>
