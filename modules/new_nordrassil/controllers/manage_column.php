<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Manage_Column
 *
 * @author No-CMS Module Generator
 */
class Manage_Column extends CMS_Priv_Strict_Controller {

	protected $URL_MAP = array();

	public function index(){

		// initialize groceryCRUD
        $crud = new grocery_CRUD();
        $crud->unset_jquery();

        // set model
        $crud->set_model($this->cms_module_path().'/grocerycrud_column_model');

        // adjust groceryCRUD's language to No-CMS's language
        $crud->set_language($this->cms_language());

        // table name
        $crud->set_table($this->cms_complete_table_name('column'));

        // set subject
        $crud->set_subject('Column');

        // displayed columns on list
        $crud->columns('table_id','name','caption','data_type','data_size','role','lookup_table_id','lookup_column_id','relation_table_id','relation_table_column_id','relation_selection_column_id','relation_priority_column_id','selection_table_id','selection_column_id','priority','value_selection_mode','value_selection_item','options');
        // displayed columns on edit operation
        $crud->edit_fields('table_id','name','caption','data_type','data_size','role','lookup_table_id','lookup_column_id','relation_table_id','relation_table_column_id','relation_selection_column_id','relation_priority_column_id','selection_table_id','selection_column_id','priority','value_selection_mode','value_selection_item','options');
        // displayed columns on add operation
        $crud->add_fields('table_id','name','caption','data_type','data_size','role','lookup_table_id','lookup_column_id','relation_table_id','relation_table_column_id','relation_selection_column_id','relation_priority_column_id','selection_table_id','selection_column_id','priority','value_selection_mode','value_selection_item','options');

        // caption of each columns
        $crud->display_as('table_id','Table');
        $crud->display_as('name','Name');
        $crud->display_as('caption','Caption');
        $crud->display_as('data_type','Data Type');
        $crud->display_as('data_size','Data Size');
        $crud->display_as('role','Role');
        $crud->display_as('lookup_table_id','Lookup Table');
        $crud->display_as('lookup_column_id','Lookup Column');
        $crud->display_as('relation_table_id','Relation Table');
        $crud->display_as('relation_table_column_id','Relation Column (To This Table)');
        $crud->display_as('relation_selection_column_id','Relation Column (To Selection Table)');
        $crud->display_as('relation_priority_column_id','Relation Priority Column');
        $crud->display_as('selection_table_id','Selection Table');
        $crud->display_as('selection_column_id','Selection Column');
        $crud->display_as('priority','Priority');
        $crud->display_as('value_selection_mode','Value Selection Mode');
        $crud->display_as('value_selection_item','Value Selection Item');
        $crud->display_as('options','Options');

		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// HINT: Put set relation (lookup) codes here
		// (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation)
		// eg:
		// 		$crud->set_relation( $field_name , $related_table, $related_title_field , $where , $order_by );
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$crud->set_relation('table_id', $this->cms_complete_table_name('table'), 'name');
		$crud->set_relation('lookup_table_id', $this->cms_complete_table_name('table'), 'name');
		$crud->set_relation('lookup_column_id', $this->cms_complete_table_name('column'), 'name');
		$crud->set_relation('relation_table_id', $this->cms_complete_table_name('table'), 'name');
		$crud->set_relation('relation_table_column_id', $this->cms_complete_table_name('column'), 'name');
		$crud->set_relation('relation_selection_column_id', $this->cms_complete_table_name('column'), 'name');
		$crud->set_relation('relation_priority_column_id', $this->cms_complete_table_name('column'), 'name');
		$crud->set_relation('selection_table_id', $this->cms_complete_table_name('table'), 'name');
		$crud->set_relation('selection_column_id', $this->cms_complete_table_name('column'), 'name');

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// HINT: Put set relation_n_n (detail many to many) codes here
		// (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation_n_n)
		// eg:
		// 		$crud->set_relation_n_n( $field_name, $relation_table, $selection_table, $primary_key_alias_to_this_table,
		// 			$primary_key_alias_to_selection_table , $title_field_selection_table, $priority_field_relation );
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$crud->set_relation_n_n('options',
		    $this->cms_complete_table_name('column_option'),
		    $this->cms_complete_table_name('template_option'),
			'column_id', 'option_id',
			'name', NULL);

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// HINT: Put custom field type here
		// (documentation: http://www.grocerycrud.com/documentation/options_functions/field_type)
		// eg:
		// 		$crud->field_type( $field_name , $field_type, $value  );
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$crud->field_type('data_type', 'enum', array('int','varchar','char','real','text','date','tinyint','smallint','mediumint','integer','bigint','float','double','decimal','numeric','datetime','timestamp','time','year','tinyblob','tinytext','blob','mediumblob','mediumtext','longblob','longtext'));
		$crud->field_type('role', 'enum', array('primary','lookup','detail many to many','detail one to many'));
		$crud->field_type('value_selection_mode', 'enum', array('set','enum'));


        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// HINT: Put callback here
		// (documentation: httm://www.grocerycrud.com/documentation/options_functions)
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$crud->callback_before_insert(array($this,'before_insert'));
		$crud->callback_before_update(array($this,'before_update'));
		$crud->callback_before_delete(array($this,'before_delete'));
		$crud->callback_after_insert(array($this,'after_insert'));
		$crud->callback_after_update(array($this,'after_update'));
		$crud->callback_after_delete(array($this,'after_delete'));



        // render
        $output = $crud->render();
        $this->view($this->cms_module_path().'/manage_column_view', $output,
            $this->cms_complete_navigation_name('manage_column'));

    }

    public function before_insert($post_array){
		return TRUE;
	}

	public function after_insert($post_array, $primary_key){
		$success = $this->after_insert_or_update($post_array, $primary_key);
		return $success;
	}

	public function before_update($post_array, $primary_key){
		return TRUE;
	}

	public function after_update($post_array, $primary_key){
		$success = $this->after_insert_or_update($post_array, $primary_key);
		return $success;
	}

	public function before_delete($primary_key){

		return TRUE;
	}

	public function after_delete($primary_key){
		return TRUE;
	}

	public function after_insert_or_update($post_array, $primary_key){

        return TRUE;
	}



}