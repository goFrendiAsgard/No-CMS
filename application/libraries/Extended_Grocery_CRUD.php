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

	public function __construct(){
		parent::__construct();
		$this->_ci = &get_instance();

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