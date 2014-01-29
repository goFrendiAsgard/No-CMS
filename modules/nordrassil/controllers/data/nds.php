<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of nds
 *
 * @author No-CMS Module Generator
 */
class nds extends CMS_Controller {

    public function template(){
    	$this->cms_guard_page($this->cms_complete_navigation_name('template'));
        $crud = $this->new_crud();
		$crud->unset_jquery();

		$crud->set_subject('Generator Template');

        // table name
        $crud->set_table($this->cms_complete_table_name('template'));

        // displayed columns on list
        $crud->columns('name','generator_path','options','manage_options');
        // displayed columns on edit operation
        $crud->edit_fields('name','generator_path','options');
        // displayed columns on add operation
        $crud->add_fields('name','generator_path');
        
        $crud->unset_read();

        $crud->required_fields('name','generator_path');
        $crud->unique_fields('name');

        // caption of each columns
        $crud->display_as('name','Name');
        $crud->display_as('generator_path','Generator Path');
		$crud->display_as('options','Options');
		$crud->display_as('manage_options','Manage Options');

		$crud->callback_column('manage_options',array($this,'callback_column_template_manage_options'));
		$crud->callback_column('options',array($this,'callback_column_template_options'));
		$crud->callback_edit_field('options',array($this,'callback_edit_field_template_options'));

		$crud->callback_before_delete(array($this, 'callback_template_before_delete'));


        // adjust grocery-crud language
        $crud->set_language($this->cms_language());

        // render
        $output = $crud->render();
        $this->view($this->cms_module_path()."/data/nds_template", $output, $this->cms_complete_navigation_name('template'));
    }

	public function callback_template_before_delete($primary_key){
		$this->load->model($this->cms_module_path().'/data/nds_model');
		$this->nds_model->before_delete_template($primary_key);
		return TRUE;
	}

	public function callback_column_template_manage_options($value, $row){
		$primary_key = $row->template_id;
		$url = site_url($this->cms_module_path().'/data/nds/template_option');
		$caption = 'Option';
		return $this->get_detail_action($caption, $url, $primary_key);
	}

	public function callback_column_template_options($value, $row){
		$primary_key = $row->template_id;
		$url = site_url($this->cms_module_path().'/data/nds/template_option');
		$this->load->model('/data/nds_model');
		$result = $this->nds_model->get_template_option_by_template($primary_key);
		return $this->get_detail_data($result, $url, $primary_key, 'option_id', 'name');
	}

	public function callback_edit_field_template_options($value, $primary_key){
		$url = site_url($this->cms_module_path().'/data/nds/template_option');
		$caption = 'Option';
		$this->load->model($this->cms_module_path().'/data/nds_model');
		$result = $this->nds_model->get_template_option_by_template($primary_key);
		$action = $this->get_detail_action($caption, $url, $primary_key);
		$data = $this->get_detail_data($result, $url, $primary_key, 'option_id', 'name');
		return $action.br().$data;
	}

    public function template_option($template_id=NULL){
    	$this->cms_guard_page($this->cms_complete_navigation_name('template'));
        $crud = $this->new_crud();
		$crud->unset_jquery();

        // table name
        $crud->set_table($this->cms_complete_table_name('template_option'));

        // displayed columns on list
        $crud->columns('template_id','name','description','option_type');
        // displayed columns on edit operation
        $crud->edit_fields('template_id','name','description','option_type');
        // displayed columns on add operation
        $crud->add_fields('template_id','name','description','option_type');

        $crud->required_fields('name','option_type');

        // caption of each columns
        $crud->display_as('template_id','Template');
        $crud->display_as('name','Name');
        $crud->display_as('description','Description');
        $crud->display_as('option_type','Option Type');

		$crud->set_relation('template_id',$this->cms_complete_table_name('template'),'name');
		$crud->unset_texteditor('description');
		$crud->change_field_type('option_type', 'enum', array('project','table','column'));

		if(isset($template_id) && intval($template_id)>0){
    		$crud->where($this->cms_complete_table_name('template_option').'.template_id', $template_id);
    		$crud->change_field_type('template_id', 'hidden', $template_id);
    	}

		$crud->callback_column($this->unique_field_name('template_id'),array($this,'callback_column_template_option_template_id'));

		$crud->callback_before_delete(array($this,'callback_template_option_before_delete'));

        // adjust grocery-crud language
        $crud->set_language($this->cms_language());

        // render
        $output = $crud->render();
		if(isset($template_id)){
			$output->template_id = $template_id;
		}
        $this->view($this->cms_module_path()."/data/nds_template_option", $output, $this->cms_complete_navigation_name('template'));
    }

	public function callback_template_option_before_delete($primary_key){
		$this->load->model($this->cms_module_path().'/data/nds_model');
		$this->nds_model->before_delete_template_option($primary_key);
		return TRUE;
	}

	public function callback_column_template_option_template_id($value, $row){
		$parent_key = $row->template_id;
		$url = site_url($this->cms_module_path().'/data/nds/template');
		return $this->get_back_to_parent_action($value, $url, $parent_key);
	}

    public function project(){
    	$this->cms_guard_page($this->cms_complete_navigation_name('project'));
        $crud = $this->new_crud();
		$crud->unset_jquery();

		$crud->set_subject('Project');

        // table name
        $crud->set_table($this->cms_complete_table_name('project'));

        // displayed columns on list
        $crud->columns('template_id','name','options','tables','manage_tables');
        // displayed columns on edit operation
        $crud->edit_fields('template_id','name','options','db_server','db_port','db_schema','db_user','db_password','db_table_prefix','tables');
        // displayed columns on add operation
        $crud->add_fields('template_id','name','options','db_server','db_port','db_schema','db_user','db_password','db_table_prefix');

        $crud->required_fields('template_id','name');
        $crud->unique_fields('name');
        
        $crud->unset_read();

        // caption of each columns
        $crud->display_as('template_id','Template');
        $crud->display_as('name','Name');
		$crud->display_as('options','Options');
		$crud->display_as('db_server','Database Server');
		$crud->display_as('db_port','Database Port');
		$crud->display_as('db_schema','Database Schema');
		$crud->display_as('db_user','Database User');
		$crud->display_as('db_password','Database Password');
		$crud->display_as('db_table_prefix','Database Table Prefix');
		$crud->display_as('tables','Tables');
		$crud->display_as('manage_tables','Manage Tables');

		$crud->field_type('db_password','password');

		$crud->set_relation('template_id',$this->cms_complete_table_name('template'),'name');
		$crud->set_relation_n_n('options',$this->cms_complete_table_name('project_option'),
		  $this->cms_complete_table_name('template_option'),'project_id','option_id','name');

		$crud->callback_after_insert(array($this, 'project_after_insert'));
		$crud->callback_before_delete(array($this, 'callback_project_before_delete'));

		$crud->callback_column('manage_tables',array($this,'callback_column_project_manage_tables'));
		$crud->callback_column('tables',array($this,'callback_column_project_tables'));
		$crud->callback_edit_field('tables',array($this,'callback_edit_field_project_tables'));


        // adjust grocery-crud language
        $crud->set_language($this->cms_language());

        // render
        $output = $crud->render();
        $this->view($this->cms_module_path()."/data/nds_project", $output, $this->cms_complete_navigation_name('project'));
    }

	public function project_after_insert($post_array, $primary_key){
		$this->load->model($this->cms_module_path().'/data/synchronize_model');
    	$this->synchronize_model->synchronize($primary_key);
    	return true;
	}

	public function callback_project_before_delete($primary_key){
		$this->load->model($this->cms_module_path().'/data/nds_model');
		$this->nds_model->before_delete_project($primary_key);
		return TRUE;
	}

	public function callback_column_project_manage_tables($value, $row){
		$primary_key = $row->project_id;
		$url = site_url($this->cms_module_path().'/data/nds/table');
		$caption = 'Table';
		return $this->get_detail_action($caption, $url, $primary_key, TRUE);
	}

	public function callback_column_project_tables($value, $row){
		$primary_key = $row->project_id;
		$url = site_url($this->cms_module_path().'/data/nds/table');
		$this->load->model('/data/nds_model');
		$result = $this->nds_model->get_table_by_project($primary_key);
		return $this->get_detail_data($result, $url, $primary_key, 'table_id', 'name');
	}

	public function callback_edit_field_project_tables($value, $primary_key){
		$url = site_url($this->cms_module_path().'/data/nds/table');
		$caption = 'Table';
		$this->load->model('/data/nds_model');
		$result = $this->nds_model->get_table_by_project($primary_key);
		$action = $this->get_detail_action($caption, $url, $primary_key);
		$data = $this->get_detail_data($result, $url, $primary_key, 'table_id', 'name');
		return $action.br().$data;
	}

    public function table($project_id = NULL){
    	$this->cms_guard_page($this->cms_complete_navigation_name('project'));
        $crud = $this->new_crud();
		$crud->unset_jquery();

		$crud->set_subject('Table');

        // table name
        $crud->set_table($this->cms_complete_table_name('table'));

		if(isset($project_id) && intval($project_id)>0){
    		$crud->where($this->cms_complete_table_name('table').'.project_id', $project_id);
    		// displayed columns on list
        	$crud->columns('name','caption','priority','options','columns','manage_columns');
    	}else{
    		// displayed columns on list
        	$crud->columns('project_id','name','caption','priority','options','columns','manage_columns');
    	}
    	$crud->order_by('priority');

        $crud->required_fields('name','caption');
        
        $crud->unset_read();

        // displayed columns on edit operation
        $crud->edit_fields('project_id','name','caption','priority','options','columns');
        // displayed columns on add operation
        $crud->add_fields('project_id','name','caption','priority','options');

        $crud->set_rules('priority','Priority','numeric');

        // caption of each columns
        $crud->display_as('project_id','Project');
        $crud->display_as('name','Name');
        $crud->display_as('caption','Caption');
        $crud->display_as('priority','Priority');
		$crud->display_as('options','Options');
		$crud->display_as('columns','Columns');
		$crud->display_as('manage_columns','Manage Columns');
		$crud->display_as('priority', 'Order Index');

        $crud->set_relation('project_id',$this->cms_complete_table_name('project'),'name');
		$crud->set_relation_n_n('options',$this->cms_complete_table_name('table_option'),
		    $this->cms_complete_table_name('template_option'),'table_id','option_id','name');

		if(isset($project_id) && intval($project_id)>0){
    		$crud->change_field_type('project_id', 'hidden', $project_id);
    	}

		$crud->callback_before_delete(array($this, 'callback_table_before_delete'));

		$crud->callback_column('manage_columns',array($this,'callback_column_table_manage_columns'));
		$crud->callback_column('columns',array($this,'callback_column_table_columns'));
		$crud->callback_edit_field('columns',array($this,'callback_edit_field_table_columns'));
		$crud->callback_column($this->unique_field_name('project_id'),array($this,'callback_column_table_project_id'));

        // adjust grocery-crud language
        $crud->set_language($this->cms_language());

        // render
        $output = $crud->render();
		if(isset($project_id) && is_numeric($project_id)){
			$output->project_id = $project_id;
			$this->load->model($this->cms_module_path().'/data/nds_model');
			$output->project_name = $this->nds_model->get_project_name($project_id);
		}
        $this->view($this->cms_module_path()."/data/nds_table", $output, $this->cms_complete_navigation_name('project'));
    }

	public function callback_table_before_delete($primary_key){
		$this->load->model($this->cms_module_path().'/data/nds_model');
		$this->nds_model->before_delete_table($primary_key);
		return TRUE;
	}

	public function callback_column_table_project_id($value, $row){
		$parent_key = $row->project_id;
		$url = site_url($this->cms_module_path().'/data/nds/project');
		return $this->get_back_to_parent_action($value, $url, $parent_key);
	}

	public function callback_column_table_manage_columns($value, $row){
		$primary_key = $row->table_id;
		$url = site_url($this->cms_module_path().'/data/nds/column');
		$caption = 'Column';
		return $this->get_detail_action($caption, $url, $primary_key, TRUE);
	}

	public function callback_column_table_columns($value, $row){
		$primary_key = $row->table_id;
		$url = site_url($this->cms_module_path().'/data/nds/column');
		$this->load->model('/data/nds_model');
		$result = $this->nds_model->get_column_by_table($primary_key);
		return $this->get_detail_data($result, $url, $primary_key, 'column_id', 'name');
	}

	public function callback_edit_field_table_columns($value, $primary_key){
		$url = site_url($this->cms_module_path().'/data/nds/column');
		$caption = 'Column';
		$this->load->model('/data/nds_model');
		$result = $this->nds_model->get_column_by_table($primary_key);
		$action = $this->get_detail_action($caption, $url, $primary_key);
		$data = $this->get_detail_data($result, $url, $primary_key, 'column_id', 'name');
		return $action.br().$data;
	}

    public function column($table_id=NULL){
    	$this->cms_guard_page($this->cms_complete_navigation_name('project'));
    	$this->load->model($this->cms_module_path().'/data/nds_model');
        $crud = $this->new_crud();
		$crud->unset_jquery();

		$crud->set_subject('Column');

        // table name
        $crud->set_table($this->cms_complete_table_name('column'));
		if(isset($table_id) && intval($table_id)>0){
    		$crud->where($this->cms_complete_table_name('column').'.table_id', $table_id);
			// displayed columns on list
        	$crud->columns('name','caption','role','data_type','data_size','options','priority');
    	}else{
    		// displayed columns on list
        	$crud->columns('table_id','name','caption','role','data_type','data_size','options','priority');
    	}
    	$crud->order_by('priority');

        $crud->required_fields('name','caption');
        
        $crud->unset_read();

        // displayed columns on edit operation
        $crud->edit_fields('table_id','name','caption','role','data_type','data_size','value_selection_mode','value_selection_item','options','priority','lookup_table_id','lookup_column_id','relation_table_id','relation_table_column_id',
        	'relation_selection_column_id','relation_priority_column_id','selection_table_id','selection_column_id');
        // displayed columns on add operation
        $crud->add_fields('table_id','name','caption','role','data_type','data_size','value_selection_mode','value_selection_item','options','priority','lookup_table_id','lookup_column_id','relation_table_id','relation_table_column_id',
        	'relation_selection_column_id','relation_priority_column_id','selection_table_id','selection_column_id');

        $crud->set_rules('priority','Priority','numeric');
        
        // caption of each columns
        $crud->display_as('table_id','Table');
		$crud->display_as('name','Name');
        $crud->display_as('caption','Caption');
        $crud->display_as('data_type','Data Type');
		$crud->display_as('data_size','Size');
        $crud->display_as('role','Role');
        $crud->display_as('lookup_table_id','Lookup Table');
        $crud->display_as('lookup_column_id','Lookup Shown Column');
        $crud->display_as('relation_table_id','Relation Table');
        $crud->display_as('relation_table_column_id','Relation Column To This Table');
        $crud->display_as('relation_selection_column_id','Relation Column To Selection Table');
        $crud->display_as('relation_priority_column_id','Relation Priority Column');
        $crud->display_as('selection_table_id','Selection Table');
		$crud->display_as('selection_column_id','Selection Shown Column');
		$crud->display_as('value_selection_mode','Selection Mode');
		$crud->display_as('value_selection_item','Selection Item');
		$crud->display_as('priority', 'Order Index');

		$crud->field_type('data_type', 'enum', $this->nds_model->available_data_type);
		$crud->field_type('role', 'enum', array('primary','lookup','detail many to many','detail one to many'));
		$crud->field_type('value_selection_mode', 'enum', array('set','enum'));

		$crud->set_relation('table_id',$this->cms_complete_table_name('table'),'name');
		$crud->set_relation_n_n('options',$this->cms_complete_table_name('column_option'),
		  $this->cms_complete_table_name('template_option'),'column_id','option_id','name');

		$crud->set_relation('lookup_table_id',$this->cms_complete_table_name('table'),'name');
		$crud->set_relation('relation_table_id',$this->cms_complete_table_name('table'),'name');
		$crud->set_relation('selection_table_id',$this->cms_complete_table_name('table'),'name');

		$crud->set_relation('lookup_column_id',$this->cms_complete_table_name('column'),'name');
		$crud->set_relation('relation_table_column_id',$this->cms_complete_table_name('column'),'name');
		$crud->set_relation('relation_selection_column_id',$this->cms_complete_table_name('column'),'name');
		$crud->set_relation('relation_priority_column_id',$this->cms_complete_table_name('column'),'name');
		$crud->set_relation('selection_column_id',$this->cms_complete_table_name('column'),'name');

		if(isset($table_id) && intval($table_id)>0){
    		$crud->change_field_type('table_id', 'hidden', $table_id);
    	}

		$crud->callback_before_delete(array($this,'callback_column_before_delete'));

		$crud->callback_column($this->unique_field_name('table_id'),array($this,'callback_column_column_table_id'));

        // adjust grocery-crud language
        $crud->set_language($this->cms_language());

        // render
        $output = $crud->render();
		if(isset($table_id) && is_numeric($table_id)){
			$this->load->model($this->cms_module_path().'/data/nds_model');
			$query = $this->db->select('project_id')->from($this->cms_complete_table_name('table'))->where('table_id',$table_id)->get();
			$row = $query->row();
			$project_id = $row->project_id;
			$output->project_id = $project_id;
			$output->project_name = $this->nds_model->get_project_name($project_id);
			$output->table_id = $table_id;
			$output->table_name = $this->nds_model->get_table_name($table_id);
		}
        $this->view($this->cms_module_path()."/data/nds_column", $output, $this->cms_complete_navigation_name('project'));
    }

	public function callback_column_before_delete($primary_key){
		$this->load->model($this->cms_module_path().'/data/nds_model');
		$this->nds_model->before_delete_column($primary_key);
		return TRUE;
	}

	public function callback_column_column_table_id($value, $row){
		$parent_key = $row->table_id;
		$url = site_url($this->cms_module_path().'/data/nds/table');
		return $this->get_back_to_parent_action($value, $url, $parent_key);
	}

	private function unique_field_name($field_name) {
            return 's'.substr(md5($field_name),0,8); //This s is because is better for a string to begin with a letter and not with a number
    }

	private function preprocess_url($url){
		if(strlen($url)>0){
			if($url[strlen($url)-1] != '/'){
				$url .= '/';
			}
		}
		return $url;
	}

	private function get_back_to_parent_action($value, $link, $parent_key){
		$link = $this->preprocess_url($link);
		$html = anchor(
				$link.'edit/'.$parent_key,
				$value,
				array('class'=>'btn btn-mini')
			);
		return $html;
	}

	private function get_detail_action($caption, $link, $primary_key, $narrow = FALSE){
		$link = $this->preprocess_url($link);
		$html = '';
		// show all
		$html .= anchor(
				$link.$primary_key,
				'Show All '.$caption,
				array('class'=>'btn btn-mini')
			);
		if($narrow){
			$html .= br();
		}else{
			$html .= '&nbsp;';
		}
		// add new
		$html .= anchor(
				$link.$primary_key.'/add',
				'Add New '.$caption,
				array('class'=>'btn btn-mini')
			);
		return $html;
	}

	private function get_detail_data($model_result, $link, $primary_key, $primary_key_lookup_field='id', $title_lookup_field='name'){
		$link = $this->preprocess_url($link);
		$arr = array();
		$char_count=0;
		foreach($model_result as $row){
			$arr[] = anchor(
					$link.$primary_key.'/edit/'.$row->{$primary_key_lookup_field},
					$row->{$title_lookup_field},
					array(
						'class'=>'btn btn-mini',
						'style'=>'float:left;')
				);
			// just add some spaces and new lines
			$char_count += strlen($row->{$title_lookup_field});
			if($char_count>=26){
				$arr[] = br();
				$char_count = 0;
			}else{
				$arr[] = '<span style="float:left;">&nbsp;</span>';
			}
		}
		$html = implode('',$arr);
		return $html;
	}


}