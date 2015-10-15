<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Manage_tourism
 *
 * @author No-CMS Module Generator
 */
class Manage_tourism extends CMS_CRUD_Controller {

    protected $URL_MAP = array();
    protected $TABLE_NAME = 'tourism';
    protected $PRIMARY_KEY = 'tourism_id';
    protected $UNSET_JQUERY = TRUE;
    protected $UNSET_READ = TRUE;
    protected $UNSET_ADD = FALSE;
    protected $UNSET_EDIT = FALSE;
    protected $UNSET_DELETE = FALSE;
    protected $UNSET_LIST = FALSE;
    protected $UNSET_BACK_TO_LIST = FALSE;
    protected $UNSET_PRINT = FALSE;
    protected $UNSET_EXPORT = FALSE;

    protected function make_crud(){
        $crud = parent::make_crud();

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: You can access this variables after calling parent's make_crud method:
        //      $this->CRUD
        //      $this->STATE
        //      $this->STATE_INFO
        //      $this->PK_VALUE
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // set subject
        $crud->set_subject('Tourism');
        // displayed columns on list
        $crud->columns('name');
        // displayed columns on edit operation
        $crud->edit_fields('name', '_updated_by', '_updated_at');
        // displayed columns on add operation
        $crud->add_fields('name', '_created_by', '_created_at');
        // caption of each columns
        $crud->display_as('name','Name');

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put required field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/required_fields)
        // eg:
        //      $crud->required_fields( $field1, $field2, $field3, ... );
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $crud->required_fields('name');

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put required field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/unique_fields)
        // eg:
        //      $crud->unique_fields( $field1, $field2, $field3, ... );
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $crud->unique_fields('name');

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_rules)
        // eg:
        //      $crud->set_rules( $field_name , $caption, $filter );
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put set relation (lookup) codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation)
        // eg:
        //      $crud->set_relation( $field_name , $related_table, $related_title_field , $where , $order_by );
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put set relation_n_n (detail many to many) codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation_n_n)
        // eg:
        //      $crud->set_relation_n_n( $field_name, $relation_table, $selection_table, $primary_key_alias_to_this_table,
        //          $primary_key_alias_to_selection_table , $title_field_selection_table, $priority_field_relation );
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put custom field type here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/field_type)
        // eg:
        //      $crud->field_type( $field_name , $field_type, $value  );
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////




        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put Tabs (if needed)
        // usage:
        //     $crud->set_outside_tab($how_many_field_outside_tab);
        //     $crud->set_tabs(array(
        //        'First Tab Caption'  => $how_many_field_on_first_tab,
        //        'Second Tab Caption' => $how_many_field_on_second_tab,
        //     ));
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////



        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put callback here
        // (documentation: httm://www.grocerycrud.com/documentation/options_functions)
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put custom error message here
        // (documentation: httm://www.grocerycrud.com/documentation/set_lang_string)
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // $crud->set_lang_string('delete_error_message', 'Cannot delete the record');
        // $crud->set_lang_string('update_error',         'Cannot edit the record'  );
        // $crud->set_lang_string('insert_error',         'Cannot add the record'   );

        $this->CRUD = $crud;
        return $crud;
    }

    public function index(){
        // create crud
        $crud = $this->make_crud();

        // render
        $render = $this->render_crud($crud);
        $output = $render['output'];
        $config = $render['config'];

        // show the view
        $this->view($this->cms_module_path().'/Manage_tourism_view', $output,
            $this->cms_complete_navigation_name('manage_tourism'), $config);
    }

    public function _after_insert_or_update($post_array, $primary_key){


        $success = parent::_after_insert_or_update($post_array, $primary_key);
        // HINT : Put your code here
        return $success;
    }

    public function _before_insert_or_update($post_array, $primary_key=NULL){
        $post_array = parent::_before_insert_or_update($post_array, $primary_key);
        // HINT : Put your code here
        return $post_array;
    }




    public function _before_insert($post_array){
        $post_array = parent::_before_insert($post_array);
        // HINT : Put your code here
        return $post_array;
    }

    public function _after_insert($post_array, $primary_key){
        $success = parent::_after_insert($post_array, $primary_key);
        // HINT : Put your code here
        return $success;
    }

    public function _before_update($post_array, $primary_key){
        $post_array = parent::_before_update($post_array, $primary_key);
        // HINT : Put your code here
        return $post_array;
    }

    public function _after_update($post_array, $primary_key){
        $success = parent::_after_update($post_array, $primary_key);
        // HINT : Put your code here
        return $success;
    }

    public function _before_delete($primary_key){
        $success = parent::_before_delete($primary_key);
        // HINT : Put your code here
        return $success;
    }

    public function _after_delete($primary_key){
        $success = parent::_after_delete($primary_key);
        // HINT : Put your code here
        return $success;
    }


}
