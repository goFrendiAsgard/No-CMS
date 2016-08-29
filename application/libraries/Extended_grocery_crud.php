<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Extension of grocery_CRUD
 *
 * A proper way of extending Grocery CRUD
 *
 * @package    	Extension of grocery_CRUD
 * @copyright  	-
 * @license    	-
 * @version    	1.0
 * @author     	-
 */

class Extended_grocery_crud extends Grocery_CRUD{

    public $form_validation;
	protected $_ci = null;
	protected $extension_extras=array();
	protected $callback_before_insert_ext=array();
	protected $callback_after_insert_ext=array();
	protected $callback_insert_ext=array();
	protected $callback_before_update_ext=array();
	protected $callback_after_update_ext=array();
	protected $callback_update_ext=array();
	protected $callback_before_delete_ext=array();
	protected $callback_after_delete_ext=array();
	protected $callback_delete_ext=array();
	protected $callback_post_render=array();

    protected $callback_show_edit = NULL;
    protected $callback_show_delete = NULL;

    protected $outside_tab       = 0;
    protected $tabs              = NULL;
    protected $tab_glyphicons    = array();
    protected $field_half_width  = array();
    protected $field_one_third_width = array();
    protected $field_two_third_width = array();
    protected $field_quarter_width = array();

    /* Make these accessible */
    public $basic_db_table;
    public $columns;
    public $add_fields;
    public $edit_fields;
    public $display_as;

    /* The unsetters */
    public $unset_texteditor     = array();
    public $unset_add            = false;
    public $unset_edit           = false;
    public $unset_delete         = false;
    public $unset_read           = false;
    public $unset_jquery         = false;
    public $unset_jquery_ui      = false;
    public $unset_bootstrap      = false;
    public $unset_list           = false;
    public $unset_export         = false;
    public $unset_print          = false;
    public $unset_back_to_list   = false;
    public $unset_columns        = null;
    public $unset_add_fields     = null;
    public $unset_edit_fields    = null;

    /* Added by Go Frendi. to modify search form, do this:
    $crud->unset_default_search();
    $crud->search_form_components = '<input name=....';
    */
    public $unset_default_search   = false;
    public $search_form_components = '';

    // fix issue http://www.grocerycrud.com/forums/topic/1975-bug-in-the-search/
    protected $unsearchable_field = array();

    public function __construct(){
        parent::__construct();
        $this->_ci = &get_instance();
        // resolve HMVC set rule callback problem
        $this->form_validation = $this->_ci->form_validation;
    }

    public function cms_lang($keyword){
        if(property_exists($this->_ci, 'no_cms_autoupdate_model')){
            return $this->_ci->no_cms_autoupdate_model->cms_lang($keyword);
        }else if(property_exists($this->_ci, 'no_cms_model')){
            return $this->_ci->no_cms_model->cms_lang($keyword);
        }else{
            return $keyword;
        }
    }

    public function set_tabs($data){
        $this->tabs = $data;
    }

    public function set_tab_glyphicons($data){
        $this->tab_glyphicons = $data;
    }

    public function set_outside_tab($data){
        $this->outside_tab = $data;
    }

    public function set_field_half_width($data){
        $this->field_half_width = $data;
    }

    public function set_field_one_third_width($data){
        $this->field_one_third_width = $data;
    }

    public function set_field_two_third_width($data){
        $this->field_two_third_width = $data;
    }

    public function set_field_quarter_width($data){
        $this->field_quarter_width = $data;
    }

    public function set_search_form_components($html){
        $this->search_form_components = $html;
    }

    public function add_tab($caption, $count){
        if($this->tabs == NULL){
            $this->tabs = array();
        }
        $this->tabs[$key] = $count;
    }

    public function add_tab_glyphicon($glyphicon){
        if($this->tab_glyphicons == NULL){
            $this->tab_glyphicons = array();
        }
        $this->tab_glyphicons[$key] = $glyphicon;
    }

    public function unset_default_search()
    {
        $this->unset_default_search = true;

        return $this;
    }

    // OVERRIDE: set default model
    protected function set_default_Model()
    {
        $db_driver = $this->_ci->db->platform();
        $model_name = 'Grocery_crud_model_'.$db_driver;
        $model_alias = 'm'.substr(md5(rand()), 0, rand(4,15) );
        if (file_exists(APPPATH.'/models/'.$model_name.'.php')){
            $this->_ci->load->model('grocery_crud_model');
            $this->_ci->load->model('grocery_crud_generic_model');
            $this->_ci->load->model($model_name,$model_alias);
            $this->basic_model = $this->_ci->{$model_alias};
        }else{
            $this->_ci->load->model('grocery_crud_model');
            $this->_ci->load->model('grocery_crud_generic_model');
            $this->basic_model = $this->_ci->grocery_crud_generic_model;
        }
    }

    // OVERRIDE: allow html to be shown
    protected function _trim_print_string($value)
    {
        return $value;
    }
    
    // OVERRIDE: the parent class has this method as private, you should change it into protected
    // this should add chmod 644 to the file
    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error) {
        $file = new stdClass();
        $file->name = $this->trim_file_name($name, $type);
        $file->size = intval($size);
        $file->type = $type;
        $error = $this->has_error($uploaded_file, $file, $error);
        if (!$error && $file->name) {
            $file_path = $this->options['upload_dir'].$file->name;
            $append_file = !$this->options['discard_aborted_uploads'] &&
                is_file($file_path) && $file->size > filesize($file_path);
            clearstatcache();
            if ($uploaded_file && is_uploaded_file($uploaded_file)) {
                // multipart/formdata uploads (POST method uploads)
                if ($append_file) {
                    file_put_contents(
                        $file_path,
                        fopen($uploaded_file, 'r'),
                        FILE_APPEND
                    );
                } else {
                    move_uploaded_file($uploaded_file, $file_path);
                    @chmod($file_path, 644);
                }
            } else {
                // Non-multipart uploads (PUT method support)
                file_put_contents(
                    $file_path,
                    fopen('php://input', 'r'),
                    $append_file ? FILE_APPEND : 0
                );
            }
            $file_size = filesize($file_path);
            if ($file_size === $file->size) {
            		if ($this->options['orient_image']) {
            		    $this->orient_image($file_path);
            		}
                $file->url = $this->options['upload_url'].rawurlencode($file->name);
                foreach($this->options['image_versions'] as $version => $options) {
                    if ($this->create_scaled_image($file->name, $options)) {
                        $file->{$version.'_url'} = $options['upload_url']
                            .rawurlencode($file->name);
                    }
                }
            } else if ($this->options['discard_aborted_uploads']) {
                unlink($file_path);
                $file->error = "It seems that this user doesn't have permissions to upload to this folder";
            }
            $file->size = $file_size;
            $file->delete_url = $this->options['script_url']
                .'?file='.rawurlencode($file->name);
            $file->delete_type = 'DELETE';
        } else {
            $file->error = $error;
        }
        return $file;
    }



    // OVERRIDE: getListSuccessUrl
    protected function getListSuccessUrl($primary_key = null)
	{
        $list_success_url = parent::getListSuccessUrl($primary_key);
        if(isset($_GET['from'])){
            if(strpos($list_success_url, '&from=') === FALSE && strpos($list_success_url, '?from=') === FALSE){
        		// list "from" to "list_url"
        		if(strpos($list_success_url, '?') !== FALSE){
        			$list_success_url .= '&from='.$_GET['from'];
        		}else{
        			$list_success_url .= '?from='.$_GET['from'];
        		}
            }
    	}
        return $list_success_url;
	}

    public function callback_show_edit($callback = null)
	{
		$this->callback_show_edit = $callback;
		return $this;
	}
    public function callback_show_delete($callback = null)
	{
		$this->callback_show_delete = $callback;
		return $this;
	}

    // OVERRIDE: unsearchable field, allow edit & allow delete
    protected function showList($ajax = false, $state_info = null)
	{
		$data = $this->get_common_data();

		$data->order_by 	= $this->order_by;

		$data->types 		= $this->get_field_types();

		$data->list = $this->get_list();
		$data->list = $this->change_list($data->list , $data->types);
		$data->list = $this->change_list_add_actions($data->list);

		$data->total_results = $this->get_total_results();

		$data->columns 				= $this->get_columns();

        // added by go frendi
        $data->unsearchable_field   = $this->unsearchable_field;
        $data->unset_default_search = $this->unset_default_search;
        $data->search_form_components = $this->search_form_components;
        // end of addtion

		$data->success_message		= $this->get_success_message_at_list($state_info);

		$data->primary_key 			= $this->get_primary_key();
		$data->add_url				= $this->getAddUrl();
		$data->edit_url				= $this->getEditUrl();
		$data->delete_url			= $this->getDeleteUrl();
        $data->delete_multiple_url	= $this->getDeleteMultipleUrl();
		$data->read_url				= $this->getReadUrl();
		$data->ajax_list_url		= $this->getAjaxListUrl();
		$data->ajax_list_info_url	= $this->getAjaxListInfoUrl();
		$data->export_url			= $this->getExportToExcelUrl();
		$data->print_url			= $this->getPrintUrl();
		$data->actions				= $this->actions;
		$data->unique_hash			= $this->get_method_hash();
		$data->order_by				= $this->order_by;

		$data->unset_add			= $this->unset_add;
		$data->unset_edit			= $this->unset_edit;
		$data->unset_read			= $this->unset_read;
		$data->unset_delete			= $this->unset_delete;
		$data->unset_export			= $this->unset_export;
		$data->unset_print			= $this->unset_print;

		$default_per_page = $this->config->default_per_page;
		$data->paging_options = $this->config->paging_options;
		$data->default_per_page		= is_numeric($default_per_page) && $default_per_page >1 && in_array($default_per_page,$data->paging_options)? $default_per_page : 25;

		if($data->list === false)
		{
			throw new Exception('It is impossible to get data. Please check your model and try again.', 13);
			$data->list = array();
		}

		foreach($data->list as $num_row => $row)
		{
            $data->list[$num_row]->primary_key_value = $row->{$data->primary_key};
			$data->list[$num_row]->edit_url = $data->edit_url.'/'.$row->{$data->primary_key};
			$data->list[$num_row]->delete_url = $data->delete_url.'/'.$row->{$data->primary_key};
			$data->list[$num_row]->read_url = $data->read_url.'/'.$row->{$data->primary_key};
            // added by gofrendi

            // callback allow edit
            if($this->callback_show_edit != NULL){
                $data->list[$num_row]->__show_edit = call_user_func($this->callback_show_edit, $row->{$data->primary_key});
            }else{
                $data->list[$num_row]->__show_edit = TRUE;
            }
            // callback allow delete
            if($this->callback_show_delete != NULL){
                $data->list[$num_row]->__show_delete = call_user_func($this->callback_show_delete, $row->{$data->primary_key});
            }else{
                $data->list[$num_row]->__show_delete = TRUE;
            }
		}

		if(!$ajax)
		{
			$this->_add_js_vars(array('dialog_forms' => $this->config->dialog_forms));

			$data->list_view = $this->_theme_view('list.php',$data,true);
			$this->_theme_view('list_template.php',$data);
		}
		else
		{
			$this->set_echo_and_die();
			$this->_theme_view('list.php',$data);
		}
	}

    // OVERRIDE: Fix issue of http://www.grocerycrud.com/forums/topic/61-default-field-values-for-add-form/
    protected function get_add_input_fields($field_values = null)
	{
		$fields = $this->get_add_fields();
		$types 	= $this->get_field_types();

		$input_fields = array();

		foreach($fields as $field_num => $field)
		{
			$field_info = $types[$field->field_name];

            // added by gofrendi
            $default_value = isset($field_info->default)? $field_info->default : null;
            // modified by gofrendi
            $field_value = !empty($field_values) && isset($field_values->{$field->field_name}) ? $field_values->{$field->field_name} : $default_value;

			if(!isset($this->callback_add_field[$field->field_name]))
			{
				$field_input = $this->get_field_input($field_info, $field_value);
			}
			else
			{
				$field_input = $field_info;
				$field_input->input = call_user_func($this->callback_add_field[$field->field_name], $field_value, null, $field_info);
			}

			switch ($field_info->crud_type) {
				case 'invisible':
					unset($this->add_fields[$field_num]);
					unset($fields[$field_num]);
					continue;
				break;
				case 'hidden':
					$this->add_hidden_fields[] = $field_input;
					unset($this->add_fields[$field_num]);
					unset($fields[$field_num]);
					continue;
				break;
			}

			$input_fields[$field->field_name] = $field_input;
		}

		return $input_fields;
	}

    // OVERRIDE: if language file is not found, fallback to english
    protected function _load_language()
	{
		if($this->language === null)
		{
			$this->language = strtolower($this->config->default_language);
		}

        // modified by gofrendi
        if(file_exists($this->default_language_path.'/'.$this->language.'.php')){
            include($this->default_language_path.'/'.$this->language.'.php');
        }else{
            include($this->default_language_path.'/english.php');
        }
        // end of modification

		foreach($lang as $handle => $lang_string)
			if(!isset($this->lang_strings[$handle]))
				$this->lang_strings[$handle] = $lang_string;

		$this->default_true_false_text = array( $this->l('form_inactive') , $this->l('form_active'));
		$this->subject = $this->subject === null ? $this->l('list_record') : $this->subject;

	}

    // Fix isset unique field problem (isset doesn't work, property_exists work)
    protected function db_update_validation()
    {
        $validation_result = (object)array('success'=>false);

        $field_types = $this->get_field_types();
        $required_fields = $this->required_fields;
        $unique_fields = $this->_unique_fields;
        $edit_fields = $this->get_edit_fields();

        if(!empty($required_fields))
        {
            foreach($edit_fields as $edit_field)
            {
                $field_name = $edit_field->field_name;
                if(!isset($this->validation_rules[$field_name]) && in_array( $field_name, $required_fields) )
                {
                    $this->set_rules( $field_name, $field_types[$field_name]->display_as, 'required');
                }
            }
        }


        /** Checking for unique fields. If the field value is not unique then
         * return a validation error straight away, if not continue... */
        if(!empty($unique_fields))
        {
            $form_validation = $this->form_validation();

            $form_validation_check = false;

            foreach($edit_fields as $edit_field)
            {
                $field_name = $edit_field->field_name;
                if(in_array( $field_name, $unique_fields) )
                {
                    $state_info = $this->getStateInfo();
                    $primary_key = $this->get_primary_key();
                    $field_name_value = $_POST[$field_name];

                    $this->basic_model->where($primary_key,$state_info->primary_key);
                    $row = $this->basic_model->get_row();

                    if(!property_exists($row, $field_name)) {
                        log_message('error', print_r($row, TRUE));
                        throw new Exception("The field name doesn't exist in the database. ".
                                            "Please use the unique fields only for fields ".
                                            "that exist in the database");
                    }

                    $previous_field_name_value = $row->$field_name;

                    if(!empty($previous_field_name_value) && $previous_field_name_value != $field_name_value) {
                        $form_validation->set_rules( $field_name,
                                $field_types[$field_name]->display_as,
                                'is_unique['.$this->basic_db_table.'.'.$field_name.']');

                        $form_validation_check = true;
                    }
                }
            }

            if($form_validation_check && !$form_validation->run())
            {
                $validation_result->error_message = $form_validation->error_string();
                $validation_result->error_fields = $form_validation->_error_array;

                return $validation_result;
            }
        }

        if(!empty($this->validation_rules))
        {
            $form_validation = $this->form_validation();

            $edit_fields = $this->get_edit_fields();

            foreach($edit_fields as $edit_field)
            {
                $field_name = $edit_field->field_name;
                if(isset($this->validation_rules[$field_name]))
                {
                    $rule = $this->validation_rules[$field_name];
                    $form_validation->set_rules($rule['field'],$rule['label'],$rule['rules']);
                }
            }

            if($form_validation->run())
            {
                $validation_result->success = true;
            }
            else
            {
                $validation_result->error_message = $form_validation->error_string();
                $validation_result->error_fields = $form_validation->_error_array;
            }
        }
        else
        {
            $validation_result->success = true;
        }

        return $validation_result;
    }

    protected function validation_layout($validation_result)
	{
		@ob_end_clean();
        echo str_replace('\\/', '/', json_encode($validation_result));
		//echo json_encode($validation_result, JSON_UNESCAPED_SLASHES);
		$this->set_echo_and_die();
	}

    protected function insert_layout($insert_result = false)
	{
		@ob_end_clean();
		if($insert_result === false)
		{
			echo json_encode(array('success' => false));
		}
		else
		{
			$success_message = '<p>'.$this->l('insert_success_message');

			if(!$this->unset_back_to_list && !empty($insert_result) && !$this->unset_edit)
			{
				$success_message .= " <a class='go-to-edit-form' href='".$this->getEditUrl($insert_result)."'>".$this->l('form_edit')." {$this->subject}</a> ";

				if (!$this->_is_ajax()) {
					$success_message .= $this->l('form_or');
				}
			}

			if(!$this->unset_back_to_list && !$this->_is_ajax())
			{
				$success_message .= " <a href='".$this->getListUrl()."'>".$this->l('form_go_back_to_list')."</a>";

			}

			$success_message .= '</p>';
			/*
			echo json_encode(array(
					'success' => true ,
					'insert_primary_key' => $insert_result,
					'success_message' => htmlentities($success_message),
					'success_list_url'	=> $this->getListSuccessUrl($insert_result)
			), JSON_UNESCAPED_SLASHES);
            */
            echo str_replace('\\/', '/', json_encode(array(
					'success' => true ,
					'insert_primary_key' => $insert_result,
					'success_message' => htmlentities($success_message),
					'success_list_url'	=> $this->getListSuccessUrl($insert_result)
			)));
		}
		$this->set_echo_and_die();
	}

    protected function update_layout($update_result = false, $state_info = null)
	{
		@ob_end_clean();
		if($update_result === false)
		{
			echo json_encode(array('success' => $update_result));
		}
		else
		{
			$success_message = '<p>'.$this->l('update_success_message');
			if(!$this->unset_back_to_list && !$this->_is_ajax())
			{
				$success_message .= " <a href='".$this->getListUrl()."'>".$this->l('form_go_back_to_list')."</a>";
			}
			$success_message .= '</p>';
			/*
			echo json_encode(array(
					'success' => true ,
					'insert_primary_key' => $update_result,
					'success_message' => htmlentities($success_message),
					'success_list_url'	=> $this->getListSuccessUrl($state_info->primary_key)
			), JSON_UNESCAPED_SLASHES);
			*/
            echo str_replace('\\/', '/', json_encode(array(
					'success' => true ,
					'insert_primary_key' => $update_result,
					'success_message' => htmlentities($success_message),
					'success_list_url'	=> $this->getListSuccessUrl($state_info->primary_key)
			)));
		}
		$this->set_echo_and_die();
	}

    /* Extra field types Functions
     */
	public function field_type_ext($field , $type, $extras = null){
        if($field && $type){
    		switch ($type) {
    			case 'yes_no':
    				$this->field_type($field,'dropdown', array('1' => 'Yes', '0' => 'No'));
    				break;

    			/*
    			 * If you want to add another field type
    			 * you just set the name in the case and
    			 * the functions inside it
    			 */

    			default:
    				# code...
    				break;
    		}
    	}
    }

    // fix issue http://www.grocerycrud.com/forums/topic/1975-bug-in-the-search/
	public function unset_search_field($field){
       	$this->unsearchable_field[] = $field;
    }

    public function get_actual_columns(){
        $field_types = $this->get_field_types();
        $actual_columns = array();
        foreach($field_types as $field) {
           		if( isset($field->db_extra) && $field->db_extra != 'auto_increment' ){
               		if(!in_array($field->name, $this->unsearchable_field)){
               			$actual_columns[] = $field->name;
               		}
           		}
		 }
        return $actual_columns;
    }

    protected function set_ajax_list_queries($state_info = null){
        if(!empty($state_info->per_page))
        {
            if(empty($state_info->page) || !is_numeric($state_info->page) )
                $this->limit($state_info->per_page);
            else
            {
                $limit_page = ( ($state_info->page-1) * $state_info->per_page );
                $this->limit($state_info->per_page, $limit_page);
            }
        }

        if(!empty($state_info->order_by))
        {
            $this->order_by($state_info->order_by[0],$state_info->order_by[1]);
        }

        if(!empty($state_info->search))
        {
            //Get the list of actual columns and then before adding it to search ..
            //compare it with the field ... does it exists? .. if yes.. great ..
            //go ahead and add it to search list.. if not.. just ignore it
            $actual_columns = $this->get_actual_columns();

            if(!empty($this->relation))
                foreach($this->relation as $relation_name => $relation_values)
                    $temp_relation[$this->_unique_field_name($relation_name)] = $this->_get_field_names_to_search($relation_values);

            // get basic table name (added by gofrendi)
            $basic_table = $this->basic_db_table;

            if($state_info->search->field !== null)
            {
                if(isset($temp_relation[$state_info->search->field]))
                {
                    if(is_array($temp_relation[$state_info->search->field]))
                        foreach($temp_relation[$state_info->search->field] as $search_field)
                            $this->or_like($search_field , $state_info->search->text);
                    else
                        $this->like($temp_relation[$state_info->search->field] , $state_info->search->text);
                }
                elseif(isset($this->relation_n_n[$state_info->search->field]))
                {
                    $escaped_text = $this->basic_model->escape_str($state_info->search->text);
                    $this->having($state_info->search->field." LIKE '%".$escaped_text."%'");
                }
                else
                {
                    // added by gofrendi, to skip non actual column search
                    $search_field_part = explode('.',$state_info->search->field);
                    $real_search_field = $search_field_part[count($search_field_part)-1];
                    if(in_array($real_search_field, $actual_columns)){
                        $this->like($basic_table.'.'.$real_search_field , $state_info->search->text);
                    }
                }
            }
            else
            {
                $columns = $this->get_columns();

                $search_text = $state_info->search->text;
                $escaped_text = $this->basic_model->escape_str($state_info->search->text);


                if(!empty($this->where)){
                    /* TODO: this produce error on select count (ajax_list_info)
                    foreach($this->where as $where){
                        $this->basic_model->having($where[0],$where[1],$where[2]);
                    }*/
                }
                $search_where = '(1=0';


                foreach($columns as $column)
                {
                    if(isset($temp_relation[$column->field_name]))
                    {
                        if(is_array($temp_relation[$column->field_name]))
                        {
                            foreach($temp_relation[$column->field_name] as $search_field)
                            {
                                //$this->or_like($search_field, $search_text);
                                $search_where .= " OR " .
                                    $this->basic_model->protect_identifiers($search_field).
                                    " LIKE '%" . $escaped_text . "%'";
                            }
                        }
                        else
                        {
                            //$this->or_like($temp_relation[$column->field_name], $search_text);
                            $search_where .= " OR " .
                                    $this->basic_model->protect_identifiers($temp_relation[$column->field_name]).
                                    " LIKE '%" . $escaped_text . "%'";
                        }
                    }
                    elseif(isset($this->relation_n_n[$column->field_name]))
                    {
                        //@todo have a where for the relation_n_n statement

                        list($field_name, $relation_table, $selection_table, $primary_key_alias_to_this_table,
                        $primary_key_alias_to_selection_table, $title_field_selection_table, $priority_field_relation_table) = array_values((array)$this->relation_n_n[$column->field_name]);

                        $primary_key_selection_table = $this->basic_model->get_primary_key($selection_table);

                        $field = "";
                        $use_template = strpos($title_field_selection_table,'{') !== false;
                        $field_name_hash = $this->_unique_field_name($title_field_selection_table);
                        if($use_template)
                        {
                            $title_field_selection_table = str_replace(" ", "&nbsp;", $title_field_selection_table);
                            $field .= $this->basic_model->build_concat_from_template($this->protect_identifiers($title_field_selection_table));
                            //$field .= "CONCAT('".str_replace(array('{','}'),array("',COALESCE(",", ''),'"),str_replace("'","\\'",$this->protect_identifiers($title_field_selection_table)))."')";
                        }
                        else
                        {
                            $field .= $this->basic_model->protect_identifiers($selection_table.'.'.$title_field_selection_table);
                        }

                        //$subquery = $this->basic_model->build_relation_n_n_subquery($field, $selection_table, $relation_table, $primary_key_alias_to_selection_table, $primary_key_selection_table, $primary_key_alias_to_this_table, $field_name);
                        $subquery = "(SELECT GROUP_CONCAT(DISTINCT ".$this->basic_model->protect_identifiers($field).") FROM ".$this->basic_model->protect_identifiers($selection_table)
                            ." LEFT JOIN ".$this->basic_model->protect_identifiers($relation_table)
                            ." ON ".$this->basic_model->protect_identifiers($relation_table.".".$primary_key_alias_to_selection_table)." = ".$this->basic_model->protect_identifiers($selection_table.".".$primary_key_selection_table)
                            ." WHERE ".$this->basic_model->protect_identifiers($relation_table.".".$primary_key_alias_to_this_table)." = ".$this->basic_model->protect_identifiers($this->basic_db_table.".".$this->basic_model->get_primary_key($this->basic_db_table))
                            ." GROUP BY ".$this->basic_model->protect_identifiers($relation_table.".".$primary_key_alias_to_this_table).") ";
                        //$this->or_where($subquery." LIKE '%".$escaped_text."%'", NULL, FALSE);
                        $search_where .= " OR " . $subquery. " LIKE '%" . $escaped_text . "%'";

                    }
                    else
                    {
                        $search_field_part = explode('.',$column->field_name);
                        $real_search_field = $search_field_part[count($search_field_part)-1];
                        if(in_array($real_search_field, $actual_columns)){
                            //$this->or_like($basic_table.'.'.$real_search_field, $search_text);
                            $search_where .= " OR " .
                                $this->basic_model->protect_identifiers($basic_table.'.'.$real_search_field).
                                " LIKE '%" . $escaped_text . "%'";
                        }
                    }
                }

                $search_where .= ')';

                $this->where($search_where, NULL, FALSE);
            }
        }
    }


    protected function get_integer_input($field_info,$value)
    {
        $this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.numeric.min.js');
        $this->set_js_config($this->default_javascript_path.'/jquery_plugins/config/jquery.numeric.config.js');
        $extra_attributes = '';
        if(!empty($field_info->db_max_length))
            $extra_attributes .= "maxlength='{$field_info->db_max_length}'";
        $input = "<input id='field-{$field_info->name}' name='{$field_info->name}' type='text' value='$value' class='numeric form-control' $extra_attributes />";
        return $input;
    }

    protected function get_true_false_input($field_info,$value)
    {

        $value_is_null = empty($value) && $value !== '0' && $value !== 0 ? true : false;

        $input = "<div class='pretty-radio-buttons'>";

        $true_string = is_array($field_info->extras) && array_key_exists(1,$field_info->extras) ? $field_info->extras[1] : $this->default_true_false_text[1];
        $checked = $value === '1' || ($value_is_null && $field_info->default === '1') ? "checked = 'checked'" : "";
        $input .= "<label><input id='field-{$field_info->name}-true' class='radio-uniform'  type='radio' name='{$field_info->name}' value='1' $checked /> ".$true_string."</label> ";

        $false_string =  is_array($field_info->extras) && array_key_exists(0,$field_info->extras) ? $field_info->extras[0] : $this->default_true_false_text[0];
        $checked = $value === '0' || ($value_is_null && $field_info->default === '0') ? "checked = 'checked'" : "";
        $input .= "<label><input id='field-{$field_info->name}-false' class='radio-uniform' type='radio' name='{$field_info->name}' value='0' $checked /> ".$false_string."</label>";

        $input .= "</div>";

        return $input;
    }

    protected function get_string_input($field_info,$value)
    {
        $value = !is_string($value) ? '' : str_replace('"',"&quot;",$value);

        $extra_attributes = '';
        if(!empty($field_info->db_max_length))
            $extra_attributes .= "maxlength='{$field_info->db_max_length}'";
        $input = "<input id='field-{$field_info->name}' class='form-control' name='{$field_info->name}' type='text' value=\"$value\" $extra_attributes />";
        return $input;
    }

    protected function get_text_input($field_info,$value)
    {
        if($field_info->extras == 'text_editor')
        {
            $editor = 'ckeditor';
            $this->set_js_lib($this->default_texteditor_path.'/ckeditor/ckeditor.js');
            $this->set_js_lib($this->default_texteditor_path.'/ckeditor/adapters/jquery.js');
            $this->set_js_config($this->default_javascript_path.'/jquery_plugins/config/jquery.ckeditor.config.js');

            $class_name = $this->config->text_editor_type == 'minimal' ? 'mini-texteditor' : 'texteditor';

            $input = "<textarea id='field-{$field_info->name}' name='{$field_info->name}' class='$class_name form-control' >$value</textarea>";
        }
        else
        {
            $input = "<textarea id='field-{$field_info->name}' name='{$field_info->name}' class='form-control'>$value</textarea>";
        }
        return $input;
    }

    protected function get_datetime_input($field_info,$value)
    {
        $this->set_css($this->default_css_path.'/ui/simple/'.grocery_CRUD::JQUERY_UI_CSS);
        $this->set_css($this->default_css_path.'/jquery_plugins/jquery.ui.datetime.css');
        $this->set_css($this->default_css_path.'/jquery_plugins/jquery-ui-timepicker-addon.css');
        $this->set_js_lib($this->default_javascript_path.'/jquery_plugins/ui/'.grocery_CRUD::JQUERY_UI_JS);
        $this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery-ui-timepicker-addon.js');

        if($this->language !== 'english')
        {
            include($this->default_config_path.'/language_alias.php');
            if(array_key_exists($this->language, $language_alias))
            {
                $i18n_date_js_file = $this->default_javascript_path.'/jquery_plugins/ui/i18n/datepicker/jquery.ui.datepicker-'.$language_alias[$this->language].'.js';
                if(file_exists($i18n_date_js_file))
                {
                    $this->set_js_lib($i18n_date_js_file);
                }

                $i18n_datetime_js_file = $this->default_javascript_path.'/jquery_plugins/ui/i18n/timepicker/jquery-ui-timepicker-'.$language_alias[$this->language].'.js';
                if(file_exists($i18n_datetime_js_file))
                {
                    $this->set_js_lib($i18n_datetime_js_file);
                }
            }
        }

        $this->set_js_config($this->default_javascript_path.'/jquery_plugins/config/jquery-ui-timepicker-addon.config.js');

        if(!empty($value) && $value != '0000-00-00 00:00:00' && $value != '1970-01-01 00:00:00'){
            list($year,$month,$day) = explode('-',substr($value,0,10));
            $date = date($this->php_date_format, mktime(0,0,0,$month,$day,$year));
            $datetime = $date.substr($value,10);
        }
        else
        {
            $datetime = '';
        }
        $input = "<input id='field-{$field_info->name}' name='{$field_info->name}' type='text' value='$datetime' maxlength='19' class='datetime-input form-control' />
        <a class='datetime-input-clear' tabindex='-1'>".$this->l('form_button_clear')."</a>
        ({$this->ui_date_format}) hh:mm:ss";
        return $input;
    }

    protected function get_hidden_input($field_info,$value)
    {
        if($field_info->extras !== null && $field_info->extras != false)
            $value = $field_info->extras;
        $input = "<input id='field-{$field_info->name}' class='form-control' type='hidden' name='{$field_info->name}' value='$value' />";
        return $input;
    }

    protected function get_password_input($field_info,$value)
    {
        $value = !is_string($value) ? '' : $value;

        $extra_attributes = '';
        if(!empty($field_info->db_max_length))
            $extra_attributes .= "maxlength='{$field_info->db_max_length}'";
        $input = "<input id='field-{$field_info->name}' class='form-control' name='{$field_info->name}' type='password' value='$value' $extra_attributes />";
        return $input;
    }

    protected function get_date_input($field_info,$value)
    {
        $this->set_css($this->default_css_path.'/ui/simple/'.grocery_CRUD::JQUERY_UI_CSS);
        $this->set_js_lib($this->default_javascript_path.'/jquery_plugins/ui/'.grocery_CRUD::JQUERY_UI_JS);

        if($this->language !== 'english')
        {
            include($this->default_config_path.'/language_alias.php');
            if(array_key_exists($this->language, $language_alias))
            {
                $i18n_date_js_file = $this->default_javascript_path.'/jquery_plugins/ui/i18n/datepicker/jquery.ui.datepicker-'.$language_alias[$this->language].'.js';
                if(file_exists($i18n_date_js_file))
                {
                    $this->set_js_lib($i18n_date_js_file);
                }
            }
        }

        $this->set_js_config($this->default_javascript_path.'/jquery_plugins/config/jquery.datepicker.config.js');

        if(!empty($value) && $value != '0000-00-00' && $value != '1970-01-01')
        {
            list($year,$month,$day) = explode('-',substr($value,0,10));
            $date = date($this->php_date_format, mktime(0,0,0,$month,$day,$year));
        }
        else
        {
            $date = '';
        }

        $input = "<input id='field-{$field_info->name}' name='{$field_info->name}' type='text' value='$date' maxlength='10' class='datepicker-input form-control' />
        <a class='datepicker-input-clear' tabindex='-1'>".$this->l('form_button_clear')."</a> (".$this->ui_date_format.")";
        return $input;
    }

    protected function get_dropdown_input($field_info,$value)
    {
        $this->load_js_chosen();
        $this->set_js_config($this->default_javascript_path.'/jquery_plugins/config/jquery.chosen.config.js');

        $select_title = str_replace('{field_display_as}',$field_info->display_as,$this->l('set_relation_title'));

        $input = "<select id='field-{$field_info->name}' name='{$field_info->name}' class='chosen-select form-control' data-placeholder='".$select_title."'>";
        $options = array('' => '') + $field_info->extras;
        foreach($options as $option_value => $option_label)
        {
            $selected = !empty($value) && $value == $option_value ? "selected='selected'" : '';
            $input .= "<option value='$option_value' $selected >$option_label</option>";
        }

        $input .= "</select>";
        return $input;
    }

    protected function get_enum_input($field_info,$value)
    {
        $this->load_js_chosen();
        $this->set_js_config($this->default_javascript_path.'/jquery_plugins/config/jquery.chosen.config.js');

        $select_title = str_replace('{field_display_as}',$field_info->display_as,$this->l('set_relation_title'));

        $input = "<select id='field-{$field_info->name}' name='{$field_info->name}' class='chosen-select form-control' data-placeholder='".$select_title."'>";
        $options_array = $field_info->extras !== false && is_array($field_info->extras)? $field_info->extras : explode("','",substr($field_info->db_max_length,1,-1));
        $options_array = array('' => '') + $options_array;

        foreach($options_array as $option)
        {
            $selected = !empty($value) && $value == $option ? "selected='selected'" : '';
            $input .= "<option value='$option' $selected >$option</option>";
        }

        $input .= "</select>";
        return $input;
    }

    // OVERRIDE: add hidden field
    protected function get_readonly_input($field_info, $value)
	{
		$read_only_value = "&nbsp;";

	    if (!empty($value) && !is_array($value)) {
	    	$read_only_value = $value;
    	} elseif (is_array($value)) {
    		$all_values = array_values($value);
    		$read_only_value = implode(", ",$all_values);
    	}
        return '<div id="field-'.$field_info->name.'" class="readonly_label">'.$read_only_value.
            "<input id='field-{$field_info->name}' class='form-control' name='{$field_info->name}' type='hidden' value='$read_only_value' /></div>";
	}

    protected function get_set_input($field_info,$value)
    {
        $this->load_js_chosen();
        $this->set_js_config($this->default_javascript_path.'/jquery_plugins/config/jquery.chosen.config.js');

        $options_array = $field_info->extras !== false && is_array($field_info->extras)? $field_info->extras : explode("','",substr($field_info->db_max_length,1,-1));
        $selected_values    = !empty($value) ? explode(",",$value) : array();

        $select_title = str_replace('{field_display_as}',$field_info->display_as,$this->l('set_relation_title'));
        $input = "<select id='field-{$field_info->name}' name='{$field_info->name}[]' multiple='multiple' size='8' class='chosen-multiple-select form-control' data-placeholder='$select_title' style='width:510px;' >";

        foreach($options_array as $option)
        {
            $selected = !empty($value) && in_array($option,$selected_values) ? "selected='selected'" : '';
            $input .= "<option value='$option' $selected >$option</option>";
        }

        $input .= "</select>";

        return $input;
    }

    protected function get_multiselect_input($field_info,$value)
    {
        $this->load_js_chosen();
        $this->set_js_config($this->default_javascript_path.'/jquery_plugins/config/jquery.chosen.config.js');

        $options_array = $field_info->extras;
        $selected_values    = !empty($value) ? explode(",",$value) : array();

        $select_title = str_replace('{field_display_as}',$field_info->display_as,$this->l('set_relation_title'));
        $input = "<select id='field-{$field_info->name}' name='{$field_info->name}[]' multiple='multiple' size='8' class='chosen-multiple-select' data-placeholder='$select_title' style='width:510px;' >";

        foreach($options_array as $option_value => $option_label)
        {
            $selected = !empty($value) && in_array($option_value,$selected_values) ? "selected='selected'" : '';
            $input .= "<option value='$option_value' $selected >$option_label</option>";
        }

        $input .= "</select>";

        return $input;
    }

    protected function get_relation_input($field_info,$value)
    {
        $this->load_js_chosen();
        $this->set_js_config($this->default_javascript_path.'/jquery_plugins/config/jquery.chosen.config.js');

        $ajax_limitation = 10000;
        $total_rows = $this->get_relation_total_rows($field_info->extras);


        //Check if we will use ajax for our queries or just clien-side javascript
        $using_ajax = $total_rows > $ajax_limitation ? true : false;

        //We will not use it for now. It is not ready yet. Probably we will have this functionality at version 1.4
        $using_ajax = false;

        //If total rows are more than the limitation, use the ajax plugin
        $ajax_or_not_class = $using_ajax ? 'chosen-select' : 'chosen-select';

        $this->_inline_js("var ajax_relation_url = '".$this->getAjaxRelationUrl()."';\n");

        $select_title = str_replace('{field_display_as}',$field_info->display_as,$this->l('set_relation_title'));
        $input = "<select id='field-{$field_info->name}'  name='{$field_info->name}' class='$ajax_or_not_class' data-placeholder='$select_title' style='width:300px'>";
        $input .= "<option value=''></option>";

        if(!$using_ajax)
        {
            $options_array = $this->get_relation_array($field_info->extras);
            foreach($options_array as $option_value => $option)
            {
                $selected = !empty($value) && $value == $option_value ? "selected='selected'" : '';
                $input .= "<option value='$option_value' $selected >$option</option>";
            }
        }
        elseif(!empty($value) || (is_numeric($value) && $value == '0') ) //If it's ajax then we only need the selected items and not all the items
        {
            $selected_options_array = $this->get_relation_array($field_info->extras, $value);
            foreach($selected_options_array as $option_value => $option)
            {
                $input .= "<option value='$option_value'selected='selected' >$option</option>";
            }
        }

        $input .= "</select>";
        return $input;
    }

    protected function get_relation_readonly_input($field_info,$value)
    {
        $options_array = $this->get_relation_array($field_info->extras);

        $value = isset($options_array[$value]) ? $options_array[$value] : '';

        return $this->get_readonly_input($field_info, $value);
    }

    protected function get_upload_file_readonly_input($field_info,$value)
    {
        $file = $file_url = base_url().$field_info->extras->upload_path.'/'.$value;

        $value = !empty($value) ? '<a href="'.$file.'" target="_blank">'.$value.'</a>' : '';

        return $this->get_readonly_input($field_info, $value);
    }

    protected function get_relation_n_n_input($field_info_type, $selected_values)
    {
        $has_priority_field = !empty($field_info_type->extras->priority_field_relation_table) ? true : false;
        $is_ie_7 = isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7') !== false) ? true : false;

        if($has_priority_field || $is_ie_7)
        {
            $this->set_css($this->default_css_path.'/ui/simple/'.grocery_CRUD::JQUERY_UI_CSS);
            $this->set_css($this->default_css_path.'/jquery_plugins/ui.multiselect.css');
            $this->set_js_lib($this->default_javascript_path.'/jquery_plugins/ui/'.grocery_CRUD::JQUERY_UI_JS);
            $this->set_js_lib($this->default_javascript_path.'/jquery_plugins/ui.multiselect.min.js');
            $this->set_js_config($this->default_javascript_path.'/jquery_plugins/config/jquery.multiselect.js');

            if($this->language !== 'english')
            {
                include($this->default_config_path.'/language_alias.php');
                if(array_key_exists($this->language, $language_alias))
                {
                    $i18n_date_js_file = $this->default_javascript_path.'/jquery_plugins/ui/i18n/multiselect/ui-multiselect-'.$language_alias[$this->language].'.js';
                    if(file_exists($i18n_date_js_file))
                    {
                        $this->set_js_lib($i18n_date_js_file);
                    }
                }
            }
        }
        else
        {
            $this->set_css($this->default_css_path.'/jquery_plugins/chosen/chosen.css');
            $this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.chosen.min.js');
            $this->set_js_config($this->default_javascript_path.'/jquery_plugins/config/jquery.chosen.config.js');
        }

        $this->_inline_js("var ajax_relation_url = '".$this->getAjaxRelationUrl()."';\n");

        $field_info         = $this->relation_n_n[$field_info_type->name]; //As we use this function the relation_n_n exists, so don't need to check
        $unselected_values  = $this->get_relation_n_n_unselected_array($field_info, $selected_values);

        if(empty($unselected_values) && empty($selected_values))
        {
            $input = "Please add {$field_info_type->display_as} first";
        }
        else
        {
            $css_class = $has_priority_field || $is_ie_7 ? 'multiselect': 'chosen-multiple-select';
            $width_style = $has_priority_field || $is_ie_7 ? '' : 'width:510px;';

            $select_title = str_replace('{field_display_as}',$field_info_type->display_as,$this->l('set_relation_title'));
            $input = "<select id='field-{$field_info_type->name}' name='{$field_info_type->name}[]' multiple='multiple' size='8' class='$css_class form-control' data-placeholder='$select_title' style='$width_style' >";

            if(!empty($unselected_values))
                foreach($unselected_values as $id => $name)
                {
                    $input .= "<option value='$id'>$name</option>";
                }

            if(!empty($selected_values))
                foreach($selected_values as $id => $name)
                {
                    $input .= "<option value='$id' selected='selected'>$name</option>";
                }

            $input .= "</select>";
        }

        return $input;
    }

    /**********************/

    /* Soft Delete Setter
     * When is called, overrides the default delete function with another that only sets a field named 'deleted' to 1.
     */

    public function set_soft_delete($field='deleted', $deleted_value=1){
    	if(!$field){
    		$field='deleted';
    	}
    	$this->extension_extras['soft_delete']['field']=$field;
		$this->extension_extras['soft_delete']['deleted_value']=$deleted_value;
    	$this->callback_delete(array($this,'soft_delete_me'));
    }

    public function soft_delete_me($primary_key){
    	$field=$this->extension_extras['soft_delete']['field'];
    	$value=$this->extension_extras['soft_delete']['deleted_value'];
    	return $this->_ci->db->update($this->basic_db_table,array($field => $value),array($this->get_primary_key() => $primary_key));
    }
    /************************************************/


    /* APPEND FIELD Functions
	 * 	Append at the End. Eliminate repetitions.
     */
    public function append_fields(){
		$args = func_get_args();

		if(isset($args[0]) && is_array($args[0]))
		{
			$args = $args[0];
		}

		$this->add_fields = array_unique(array_merge($this->add_fields,$args));
		$this->edit_fields = array_unique(array_merge($this->edit_fields,$args));

		return $this;
	}

	public function append_add_fields()	{
		$args = func_get_args();

		if(isset($args[0]) && is_array($args[0]))
		{
			$args = $args[0];
		}

		$this->add_fields = array_unique(array_merge($this->add_fields,$args));

		return $this;
	}

	public function append_edit_fields(){
		$args = func_get_args();

		if(isset($args[0]) && is_array($args[0]))
		{
			$args = $args[0];
		}

		$this->edit_fields = array_unique(array_merge($this->edit_fields,$args));

		return $this;
	}



	/********************************************************/


	/* Prepend FIELD Functions
	 * 	Append at the Beginning. Eliminate repetitions.
     */
	public function prepend_fields(){
		$args = func_get_args();

		if(isset($args[0]) && is_array($args[0]))
		{
			$args = $args[0];
		}

		$this->add_fields = array_unique(array_merge($args,$this->add_fields));
		$this->edit_fields = array_unique(array_merge($args,$this->edit_fields));

		return $this;
	}

	public function prepend_add_fields(){
		$args = func_get_args();

		if(isset($args[0]) && is_array($args[0]))
		{
			$args = $args[0];
		}

		$this->add_fields = array_unique(array_merge($args,$this->add_fields));

		return $this;
	}

	public function prepend_edit_fields(){
		$args = func_get_args();

		if(isset($args[0]) && is_array($args[0]))
		{
			$args = $args[0];
		}

		$this->edit_fields = array_unique(array_merge($args,$this->edit_fields));

		return $this;
	}

	/********************************************************/


	/* Append After FIELD Functions
	 * 	Append after first field in parameters. Eliminate repetitions.
     */

	public function append_fields_after(){
		$args = func_get_args();

		if(func_num_args ()>1){
			$after_field=$args[0];

			if(isset($args[1]) && is_array($args[1])){
				$args = $args[1];
			}else{
				unset($args[0]);
			}


			$this->append_add_fields_after($after_field,$args);
			$this->append_edit_fields_after($after_field,$args);

		}

		return $this;
	}

	public function append_add_fields_after(){
		$args = func_get_args();

		if(func_num_args ()>1){
			$after_field=$args[0];

			if(isset($args[1]) && is_array($args[1])){
				$args = $args[1];
			}else{
				unset($args[0]);
			}

			$split_key=array_search($after_field, $this->add_fields);
			if($split_key!==FALSE){
				$add_fields_array=array_diff($this->add_fields, $args);
				$first_fields_list = array_slice($add_fields_array, 0, $split_key+1);
				$middle_fields_list = $args;
				$last_fields_list = array_slice($add_fields_array, $split_key);
				$this->add_fields = array_unique(array_merge($first_fields_list,$middle_fields_list,$last_fields_list));
			}else{
				$this->append_add_fields($args);
			}
		}

		return $this;
	}

	public function append_edit_fields_after(){
		$args = func_get_args();

		if(func_num_args ()>1){
			$after_field=$args[0];

			if(isset($args[1]) && is_array($args[1])){
				$args = $args[1];
			}else{
				unset($args[0]);
			}

			$split_key=array_search($after_field, $this->edit_fields);
			if($split_key!==FALSE){
				$edit_fields_array=array_diff($this->edit_fields, $args);
				$first_fields_list = array_slice($edit_fields_array, 0, $split_key+1);
				$middle_fields_list = $args;
				$last_fields_list = array_slice($edit_fields_array, $split_key);
				$this->edit_fields = array_unique(array_merge($first_fields_list,$middle_fields_list,$last_fields_list));
			}else{
				$this->append_edit_fields($args);
			}
		}

		return $this;
	}

	/********************************************************/


	/* APPEND COLUMNS Function
	 * 	Append at the End. Eliminate repetitions.
     */
	public function append_columns(){
		$args = func_get_args();

		if(isset($args[0]) && is_array($args[0])){
			$args = $args[0];
		}

		$this->columns = array_unique(array_merge($this->columns,$args));

		return $this;
	}

	/* APPEND COLUMNS Function
	 * 	Append at the Beginning. Eliminate repetitions.
     */
	public function prepend_columns(){
		$args = func_get_args();

		if(isset($args[0]) && is_array($args[0])){
			$args = $args[0];
		}

		$this->columns = array_unique(array_merge($args,$this->columns));

		return $this;
	}

    /***************************************/


	/* REMOVE FIELD Functions
	 * 	Removes the fields passed as parameters from the actual field list
     */
    public function remove_fields(){
		$args = func_get_args();

		if(isset($args[0]) && is_array($args[0]))
		{
			$args = $args[0];
		}

		$this->add_fields = array_unique(array_diff($this->add_fields,$args));
		$this->edit_fields = array_unique(array_diff($this->edit_fields,$args));

		return $this;
	}

	public function remove_add_fields(){
		$args = func_get_args();

		if(isset($args[0]) && is_array($args[0]))
		{
			$args = $args[0];
		}

		$this->add_fields = array_unique(array_diff($this->add_fields,$args));

		return $this;
	}

	public function remove_edit_fields(){
		$args = func_get_args();

		if(isset($args[0]) && is_array($args[0]))
		{
			$args = $args[0];
		}

		$this->edit_fields = array_unique(array_diff($this->edit_fields,$args));

		return $this;
	}
	/********************************************************/


	/* REMOVE COLUMNS Function
	 * 	Removes the columns passed as parameters from the actual columns list
     */
	public function remove_columns(){
		$args = func_get_args();

		if(isset($args[0]) && is_array($args[0])){
			$args = $args[0];
		}

		$this->columns = array_unique(array_diff($this->columns,$args));

		return $this;
	}


    /***************************************/


	/* Extended Callback Functions
	 * 	Replace the standar callbacks so you can queue many of them
     */


	/*****  INSERT  ******/
	public function callback_before_insert($callback = null,$override_all=0){
		if(!$override_all){
			$this->callback_before_insert_ext[] = $callback;
			if($this->callback_before_insert == null){
				$this->callback_before_insert = array($this,'extended_callback_before_insert');
			}
		}else{
			parent::callback_before_insert($callback);
		}

		return $this;
	}

	protected function extended_callback_before_insert($post_array){
		foreach ($this->callback_before_insert_ext as $key => $callback) {
			if(is_array($post_array)){
				$post_array = call_user_func($callback, $post_array);
			}
		}

		return $post_array;
	}

	public function callback_after_insert($callback = null,$override_all=0){
		if(!$override_all){
			$this->callback_after_insert_ext[] = $callback;
			if($this->callback_after_insert == null){
				$this->callback_after_insert = array($this,'extended_callback_after_insert');
			}
		}else{
			parent::callback_after_insert($callback);
		}

		return $this;
	}

	protected function extended_callback_after_insert($post_array,$primary_key){
		$continue=1;
		foreach ($this->callback_after_insert_ext as $key => $callback) {
			if($continue){
				$continue = call_user_func($callback, $post_array,$primary_key);
			}
		}

		return $post_array;
	}

	/*****  UPDATE  ******/
	public function callback_before_update($callback = null,$override_all=0){
		if(!$override_all){
			$this->callback_before_update_ext[] = $callback;
			if($this->callback_before_update == null){
				$this->callback_before_update = array($this,'extended_callback_before_update');
			}
		}else{
			parent::callback_before_update($callback);
		}

		return $this;
	}

	protected function extended_callback_before_update($post_array, $primary_key){
		foreach ($this->callback_before_update_ext as $key => $callback) {
			if(is_array($post_array)){
				$post_array = call_user_func($callback, $post_array, $primary_key);
			}
		}

		return $post_array;
	}

	public function callback_after_update($callback = null,$override_all=0){
		if(!$override_all){
			$this->callback_after_update_ext[] = $callback;
			if($this->callback_after_update == null){
				$this->callback_after_update = array($this,'extended_callback_after_update');
			}
		}else{
			parent::callback_after_update($callback);
		}

		return $this;
	}

	protected function extended_callback_after_update($post_array,$primary_key){
		$continue=1;
		foreach ($this->callback_after_update_ext as $key => $callback) {
			if($continue){
				$continue = call_user_func($callback, $post_array,$primary_key);
			}
		}

		return $continue;
	}


	/*****  DELETE  ******/
	public function callback_before_delete($callback = null,$override_all=0){
		if(!$override_all){
			$this->callback_before_delete_ext[] = $callback;
			if($this->callback_before_delete == null){
				$this->callback_before_delete = array($this,'extended_callback_before_delete');
			}
		}else{
			parent::callback_before_delete($callback);
		}

		return $this;
	}

	protected function extended_callback_before_delete($primary_key){
		$continue=1;
		foreach ($this->callback_before_delete_ext as $key => $callback) {
			if($continue){
				$continue = call_user_func($callback, $primary_key);
			}
		}

		return $continue;
	}

	public function callback_after_delete($callback = null,$override_all=0){
		if(!$override_all){
			$this->callback_after_delete_ext[] = $callback;
			if($this->callback_after_delete == null){
				$this->callback_after_delete = array($this,'extended_callback_after_delete');
			}
		}else{
			parent::callback_after_delete($callback);
		}

		return $this;
	}

	protected function extended_callback_after_delete($primary_key){
		$continue=1;
		foreach ($this->callback_after_delete_ext as $key => $callback) {
			if($continue){
				$continue = call_user_func($callback, $primary_key);
			}
		}

		return $continue;
	}

	public function callback_post_render($callback = null,$override_all=0){
		if(!$override_all){
			$this->callback_post_render[] = $callback;
		}else{
			$this->callback_post_render = array();
			$this->callback_post_render[] = $callback;
		}

		return $this;
	}


	protected function post_render(){
		$output=$this->get_layout();

		if(count($this->callback_post_render)){
			foreach ($this->callback_post_render as $key => $callback) {
				$output = call_user_func($callback, $output);
			}
		}

        // add some javascript and css so that the detail will be easier
        $state = $this->getState();
        if(in_array($state, array('add','edit','success'))){
            $mandatory_css_files = array(
                    base_url('assets/grocery_crud/css/jquery_plugins/chosen/chosen.css'),
                    base_url('assets/grocery_crud/css/jquery_plugins/jquery.ui.datetime.css'),
                    base_url('assets/grocery_crud/css/jquery_plugins/jquery-ui-timepicker-addon.css')
                );
            $mandatory_js_files = array(
                    base_url('assets/grocery_crud/js/jquery_plugins/ui/'.grocery_CRUD::JQUERY_UI_JS),
                    base_url('assets/grocery_crud/js/jquery_plugins/jquery.chosen.min.js'),
                    base_url('assets/grocery_crud/js/jquery_plugins/ui.multiselect.min.js'),
                    base_url('assets/grocery_crud/js/jquery_plugins/jquery.numeric.min.js'),
                    //base_url('assets/grocery_crud/js/jquery_plugins/jquery.ui.datetime.js'),
                    base_url('assets/grocery_crud/js/jquery_plugins/jquery-ui-timepicker-addon.js'),
                );

            foreach($mandatory_css_files as $mandatory_css_file){
                if(!in_array($mandatory_css_file, $output->css_files)){
                    $output->css_files[] = $mandatory_css_file;
                }
            }
            foreach($mandatory_js_files as $mandatory_js_file){
                if(!in_array($mandatory_js_file, $this->js_files)){
                    $output->js_files[] = $mandatory_js_file;
                }
            }
        }

        // sort js so that combination will be better
        $config_js = array();
        foreach($output->js_files as $js_file){
            if(strpos($js_file, base_url('assets/grocery_crud/js/jquery_plugins/config')) === 0){
                $config_js[] = $js_file;
            }
        }
        $plugin_js = array();
        foreach($output->js_files as $js_file){
            if(!in_array($js_file, $config_js) && strpos($js_file, base_url('assets/grocery_crud/js/jquery_plugins')) === 0){
                $plugin_js[] = $js_file;
            }
        }
        $text_editor_js = array();
        foreach($output->js_files as $js_file){
            if(strpos($js_file, base_url('assets/grocery_crud/texteditor')) === 0){
                $text_editor_js[] = $js_file;
            }
        }
        $theme_js = array();
        foreach($output->js_files as $js_file){
            if(strpos($js_file, base_url('assets/grocery_crud/themes')) === 0){
                $theme_js[] = $js_file;
            }
        }

        $other_js = array();
        foreach($output->js_files as $js_file){
            if(!in_array($js_file, $config_js) && !in_array($js_file, $plugin_js) &&
                !in_array($js_file, $text_editor_js) && !in_array($js_file, $theme_js)){
                $other_js[] = $js_file;
            }
        }

        $output->js_files = array_merge($plugin_js, $theme_js, $text_editor_js, $other_js, $config_js);

        return $output;
	}

	public function render(){
		parent::render();

		return $this->post_render();
	}

    /***************************************/
    /***************************************/
    /***************************************/

 	/* EXAMPLE OF BASIC SETUPS USE*/

    public function basic_gc_config($table_name, $content_public_name, $template='twitter-bootstrap'){
    	$this->set_theme($template);

	    $this->set_table($table_name)
        	->set_subject($content_public_name);

		$this->set_soft_delete();

		$this->columns('name','created','public');

		$this->field_type_ext('public','yes_no');


		$this->required_fields('name');

		$this->fields(
			'name',
			'public'
		);

    }
}
