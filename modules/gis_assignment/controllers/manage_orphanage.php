<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Manage_Orphanage
 *
 * @author No-CMS Module Generator
 */
class Manage_Orphanage extends CMS_Priv_Strict_Controller {

	protected $URL_MAP = array();

	public function index(){
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// initialize groceryCRUD
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $crud = new grocery_CRUD();
        $crud->unset_jquery();

        // set model
        $crud->set_model($this->cms_module_path().'/grocerycrud_orphanage_model');

        // adjust groceryCRUD's language to No-CMS's language
        $crud->set_language($this->cms_language());

        // table name
        $crud->set_table($this->cms_complete_table_name('orphanage'));

        // set subject
        $crud->set_subject('Orphanage');

        // displayed columns on list
        $crud->columns('uploader_nrp','orphanage_name','longitude','latitude','address','phone','religion_id','website','min_age','max_age','history','organization','facility','public_transportation','other_description','photo_1','photo_2','photo_3','gender');
        // displayed columns on edit operation
        $crud->edit_fields('uploader_nrp','orphanage_name','longitude','latitude','address','phone','religion_id','website','min_age','max_age','history','organization','facility','public_transportation','other_description','photo_1','photo_2','photo_3','gender');
        // displayed columns on add operation
        $crud->add_fields('uploader_nrp','orphanage_name','longitude','latitude','address','phone','religion_id','website','min_age','max_age','history','organization','facility','public_transportation','other_description','photo_1','photo_2','photo_3','gender');

        // caption of each columns
        $crud->display_as('uploader_nrp','Uploader\'s Student Registration Id');
        $crud->display_as('orphanage_name','Orphanage Name');
        $crud->display_as('longitude','Longitude');
        $crud->display_as('latitude','Latitude');
        $crud->display_as('address','Address');
        $crud->display_as('phone','Phone');
        $crud->display_as('religion_id','Religion');
        $crud->display_as('website','Website');
        $crud->display_as('min_age','Min Age');
        $crud->display_as('max_age','Max Age');
        $crud->display_as('history','History');
        $crud->display_as('organization','Organization');
        $crud->display_as('facility','Facility');
        $crud->display_as('public_transportation','Public Transportation');
        $crud->display_as('other_description','Other Description');
        $crud->display_as('photo_1','Photo 1');
        $crud->display_as('photo_2','Photo 2');
        $crud->display_as('photo_3','Photo 3');
        $crud->display_as('gender','Gender');

		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// HINT: Put set relation (lookup) codes here
		// (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation)
		// eg:
		// 		$crud->set_relation( $field_name , $related_table, $related_title_field , $where , $order_by );
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$crud->set_relation('religion_id', $this->cms_complete_table_name('religion'), 'name');

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// HINT: Put set relation_n_n (detail many to many) codes here
		// (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation_n_n)
		// eg:
		// 		$crud->set_relation_n_n( $field_name, $relation_table, $selection_table, $primary_key_alias_to_this_table,
		// 			$primary_key_alias_to_selection_table , $title_field_selection_table, $priority_field_relation );
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// HINT: Put custom field type here
		// (documentation: http://www.grocerycrud.com/documentation/options_functions/field_type)
		// eg:
		// 		$crud->field_type( $field_name , $field_type, $value  );
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$crud->field_type('gender', 'enum', array('male','female','both'));


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



        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // render
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $output = $crud->render();
        $this->view($this->cms_module_path().'/manage_orphanage_view', $output,
            $this->cms_complete_navigation_name('manage_orphanage'));

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