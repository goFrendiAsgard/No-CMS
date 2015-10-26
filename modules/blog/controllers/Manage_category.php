<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Manage_Category
 *
 * @author No-CMS Module Generator
 */
class Manage_category extends CMS_Secure_Controller {

	protected $URL_MAP = array();

	public function index(){
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// initialize groceryCRUD
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $crud = $this->new_crud();
        $crud->unset_jquery();

        // set model
        // $crud->set_model($this->cms_module_path().'/grocerycrud_category_model');

        // adjust groceryCRUD's language to No-CMS's language
        $crud->set_language($this->cms_language());

        // table name
        $crud->set_table($this->t('category'));

        // set subject
        $crud->set_subject('Category');

        // displayed columns on list
        $crud->columns('category_name','description','articles');
        // displayed columns on edit operation
        $crud->edit_fields('category_name','description','articles');
        // displayed columns on add operation
        $crud->add_fields('category_name','description','articles');
        // required field
        $crud->required_fields('category_name');        
        $crud->unique_fields('category_name');
        $crud->unset_read();

        // caption of each columns
        $crud->display_as('category_name','Category Name');
        $crud->display_as('description','Description');
        $crud->display_as('articles','Articles');

		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// HINT: Put set relation (lookup) codes here
		// (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation)
		// eg:
		// 		$crud->set_relation( $field_name , $related_table, $related_title_field , $where , $order_by );
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// HINT: Put set relation_n_n (detail many to many) codes here
		// (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation_n_n)
		// eg:
		// 		$crud->set_relation_n_n( $field_name, $relation_table, $selection_table, $primary_key_alias_to_this_table,
		// 			$primary_key_alias_to_selection_table , $title_field_selection_table, $priority_field_relation );
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$crud->set_relation_n_n('articles',
		    $this->t('category_article'),
		    $this->t('article'),
			'category_id', 'article_id',
			'article_title', NULL);

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// HINT: Put custom field type here
		// (documentation: http://www.grocerycrud.com/documentation/options_functions/field_type)
		// eg:
		// 		$crud->field_type( $field_name , $field_type, $value  );
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $crud->unset_texteditor('description');


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

        // prepare css & js, add them to config
        $config = array();
        $asset = new Cms_asset();
        foreach($output->css_files as $file){
            $asset->add_css($file);
        }
        $config['css'] = $asset->compile_css();

        foreach($output->js_files as $file){
            $asset->add_js($file);
        }
        $config['js'] = $asset->compile_js();

        // show the view
        $this->view($this->cms_module_path().'/manage_category_view', $output,
            $this->n('manage_category'), $config);

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