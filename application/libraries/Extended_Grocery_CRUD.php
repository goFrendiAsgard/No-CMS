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
class Extended_Grocery_CRUD extends Grocery_CRUD{

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

    protected $tabs = NULL;

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

    // fix issue http://www.grocerycrud.com/forums/topic/1975-bug-in-the-search/
    protected $unsearchable_field = array();

    public function __construct(){
        parent::__construct();
        $this->_ci = &get_instance();
        // resolve HMVC set rule callback problem
        $this->form_validation = $this->_ci->form_validation;
    }

    public function set_tabs($data){
        $this->tabs = $data;
    }

    public function add_tab($caption, $count){
        if($this->tabs == NULL){
            $this->tabs = array();
        }
        $this->tabs[$key] = $count;
    }

    protected function set_default_Model()
    {
        $db_driver = $this->_ci->db->platform();
        $model_name = 'grocery_crud_model_'.$db_driver;
        $model_alias = 'm'.substr(md5(rand()), 0, rand(4,15) );
        if (file_exists(APPPATH.'/models/'.$model_name.'.php')){
            $this->_ci->load->model('grocery_crud_model');
            $this->_ci->load->model('grocery_crud_generic_model');
            $this->_ci->load->model($model_name,$model_alias);
            $this->basic_model = $this->_ci->{$model_alias};
        }else{
            $this->_ci->load->model('grocery_CRUD_Model');
            $this->basic_model = new grocery_CRUD_Model();
        }
    }

    protected function _trim_print_string($value)
    {
        return $value;
        /*
        $value = str_replace(array("&nbsp;","&amp;","&gt;","&lt;"),array(" ","&",">","<"),$value);

        //If the value has only spaces and nothing more then add the whitespace html character
        if(str_replace(" ","",$value) == "")
            $value = "&nbsp;";

        return strip_tags($value);
         */
    }

    /**
     *
     * Load the language strings array from the language file
     */
    protected function _load_language()
    {
        if($this->language === null)
        {
            $this->language = strtolower($this->config->default_language);
        }
        if(file_exists($this->default_language_path.'/'.$this->language.'.php')){
            include($this->default_language_path.'/'.$this->language.'.php');
        }else{
            include($this->default_language_path.'/english.php');
        }

        foreach($lang as $handle => $lang_string)
            if(!isset($this->lang_strings[$handle]))
                $this->lang_strings[$handle] = $lang_string;

        $this->default_true_false_text = array( $this->l('form_inactive') , $this->l('form_active'));
        $this->subject = $this->subject === null ? $this->l('list_record') : $this->subject;

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
                $search_where = '1=0';


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

                $this->where($search_where, NULL, FALSE);
            }
        }
    }

    // fix issue of NULL unique field (it is possible). The property_exists function is more robbust than isset
    protected function db_update_validation(){
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
