<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Manage_field
 *
 * @author No-CMS Module Generator
 */
class Manage_field extends CMS_CRUD_Controller {

    protected $URL_MAP = array();
    protected $TABLE_NAME = 'field';
    protected $COLUMN_NAMES = array('name', 'id_template', 'id_entity', 'input', 'view', 'shown_on_add', 'shown_on_edit', 'shown_on_view', 'option', 'custom_input', 'custom_view');
    protected $PRIMARY_KEY = 'id';
    protected $UNSET_JQUERY = TRUE;
    protected $UNSET_READ = TRUE;
    protected $UNSET_ADD = FALSE;
    protected $UNSET_EDIT = FALSE;
    protected $UNSET_DELETE = FALSE;
    protected $UNSET_LIST = FALSE;
    protected $UNSET_BACK_TO_LIST = FALSE;
    protected $UNSET_PRINT = FALSE;
    protected $UNSET_EXPORT = FALSE;

    public function __construct(){
        parent::__construct();
        $this->load->model($this->cms_module_path().'/cck_model');
    }

    protected function make_crud($id_entity = NULL){
        $crud = parent::make_crud();

        ////////////////////////////////////////////////////////////////////////
        // HINT: You can access this variables after calling parent's make_crud method:
        //      $this->CRUD
        //      $this->STATE
        //      $this->STATE_INFO
        //      $this->PK_VALUE
        ////////////////////////////////////////////////////////////////////////
        if($id_entity != NULL){
            $crud->where($this->t('field').'.id_entity', $id_entity);
            // displayed columns on list
            $crud->columns('name', 'id_template');
        }else{
            $crud->columns('name', 'id_template', 'id_entity');
        }

        // set subject
        $crud->set_subject('Field');

        // displayed columns on list, edit, and add, uncomment to use
        //$crud->columns('name', 'id_template', 'id_entity', 'add_input', 'edit_input', 'view_html', 'shown_on_add', 'shown_on_edit', 'shown_on_delete', 'option');
        //$crud->edit_fields('name', 'id_template', 'id_entity', 'add_input', 'edit_input', 'view_html', 'shown_on_add', 'shown_on_edit', 'shown_on_delete', 'option', '_updated_by', '_updated_at');
        //$crud->add_fields('name', 'id_template', 'id_entity', 'add_input', 'edit_input', 'view_html', 'shown_on_add', 'shown_on_edit', 'shown_on_delete', 'option', '_created_by', '_created_at');
        //$crud->set_read_fields('name', 'id_template', 'id_entity', 'add_input', 'edit_input', 'view_html', 'shown_on_add', 'shown_on_edit', 'shown_on_delete', 'option');

        // caption of each columns
        $crud->display_as('name','Name');
        $crud->display_as('id_template','Template');
        $crud->display_as('id_entity','Entity');
        $crud->display_as('input','Input');
        $crud->display_as('view','View');
        $crud->display_as('shown_on_add','Shown On Add');
        $crud->display_as('shown_on_edit','Shown On Edit');
        $crud->display_as('shown_on_view','Shown On View');
        $crud->display_as('option','Option');

        ////////////////////////////////////////////////////////////////////////
        // This function will automatically detect every methods in this controller and link it to corresponding column
        // if the name is match by convention. In other word, you don't need to manually define callback.
        // Here is the convention (replace COLUMN_NAME with your column's name)
        //
        // * callback column (called when viewing the data as list):
        //      public function _callback_column_COLUMN_NAME($value, $row){}
        //
        // * callback field (called when show add and edit form):
        //      public function _callback_field_COLUMN_NAME($value, $primary_key){}
        //
        // * validation rule callback (field validation when adding/editing data)
        //      public function COLUMN_NAME_validation($value){}
        ////////////////////////////////////////////////////////////////////////
        $this->build_default_callback();

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put required field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/required_fields)
        // eg:
        //      $crud->required_fields( $field1, $field2, $field3, ... );
        ////////////////////////////////////////////////////////////////////////
        if($id_entity == NULL){
            $crud->required_fields('name', 'id_entity');
        }else{
            $crud->required_fields('name');
        }

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put required field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/unique_fields)
        // eg:
        //      $crud->unique_fields( $field1, $field2, $field3, ... );
        ////////////////////////////////////////////////////////////////////////


        ////////////////////////////////////////////////////////////////////////
        // HINT: Put field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_rules)
        // eg:
        //      $crud->set_rules( $field_name , $caption, $filter );
        ////////////////////////////////////////////////////////////////////////


        ////////////////////////////////////////////////////////////////////////
        // HINT: Put set relation (lookup) codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation)
        // eg:
        //      $crud->set_relation( $field_name , $related_table, $related_title_field , $where , $order_by );
        ////////////////////////////////////////////////////////////////////////
        $crud->set_relation('id_template', $this->t('template'), 'name');
        if($id_entity == NULL){
            $crud->set_relation('id_entity', $this->t('entity'), 'name');
        }else{
            $crud->field_type('id_entity', 'hidden', $id_entity);
        }

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put set relation_n_n (detail many to many) codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation_n_n)
        // eg:
        //      $crud->set_relation_n_n( $field_name, $relation_table, $selection_table, $primary_key_alias_to_this_table,
        //          $primary_key_alias_to_selection_table , $title_field_selection_table, $priority_field_relation );
        ////////////////////////////////////////////////////////////////////////


        ////////////////////////////////////////////////////////////////////////
        // HINT: Put custom field type here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/field_type)
        // eg:
        //      $crud->field_type( $field_name , $field_type, $value  );
        ////////////////////////////////////////////////////////////////////////
        $crud->field_type('shown_on_add', 'true_false');
        $crud->field_type('shown_on_edit', 'true_false');
        $crud->field_type('shown_on_view', 'true_false');
        $crud->unset_texteditor('input');
        $crud->unset_texteditor('view');

        $custom_input = 'FALSE';
        $custom_view = 'FALSE';
        if($this->PK_VALUE > 0){
            $current_field = $this->cms_get_record($this->t('field'), 'id', $this->PK_VALUE);
            if($current_field != NULL ){
                if(trim($current_field->input) != ''){
                    $custom_input = 'TRUE';
                }
                if(trim($current_field->view) != ''){
                    $custom_view = 'TRUE';
                }
            }
        }
        $crud->field_type('custom_input', 'hidden', $custom_input);
        $crud->field_type('custom_view', 'hidden', $custom_view);


        ////////////////////////////////////////////////////////////////////////
        // HINT: Put Tabs (if needed)
        // usage:
        //     $crud->set_outside_tab($how_many_field_outside_tab);
        //     $crud->set_tabs(array(
        //        'First Tab Caption'  => $how_many_field_on_first_tab,
        //        'Second Tab Caption' => $how_many_field_on_second_tab,
        //     ));
        ////////////////////////////////////////////////////////////////////////

        $crud->set_field_one_third_width(array('shown_on_add', 'shown_on_edit', 'shown_on_view'));

        ////////////////////////////////////////////////////////////////////////
        // HINT: Create custom search form (if needed)
        // usage:
        //     $crud->unset_default_search();
        //     // Your custom form
        //     $html =  '<div class="row container col-md-12" style="margin-bottom:10px;">';
        //     $html .= '</div>';
        //     $html .= '<input name="keyword" placeholder="Keyword" value="'.$keyword.'" /> &nbsp;';
        //     $html .= '<input type="button" value="Search" class="crud_search btn btn-primary form-control" id="crud_search" />';
        //     $crud->set_search_form_components($html);
        ////////////////////////////////////////////////////////////////////////



        ////////////////////////////////////////////////////////////////////////
        // HINT: Put callback here
        // (documentation: httm://www.grocerycrud.com/documentation/options_functions)
        ////////////////////////////////////////////////////////////////////////


        ////////////////////////////////////////////////////////////////////////
        // HINT: Put custom error message here
        // (documentation: httm://www.grocerycrud.com/documentation/set_lang_string)
        ////////////////////////////////////////////////////////////////////////
        // $crud->set_lang_string('delete_error_message', 'Cannot delete the record');
        // $crud->set_lang_string('update_error',         'Cannot edit the record'  );
        // $crud->set_lang_string('insert_error',         'Cannot add the record'   );

        $this->CRUD = $crud;
        return $crud;
    }

    public function index($id_entity = NULL){
        // create crud
        if(!is_numeric($id_entity)){
            $id_entity = NULL;
        }
        $crud = $this->make_crud($id_entity);

        // render
        $render = $this->render_crud($crud);
        $output = $render['output'];
        $config = $render['config'];

        if(isset($id_entity) && is_numeric($id_entity)){
            $output->id_entity = $id_entity;
        }

        // show the view
        $this->view($this->cms_module_path().'/Manage_field_view', $output,
            $this->n('manage_field'), $config);
    }


    // returned on insert and edit
    public function _callback_field_option($value, $primary_key){
        // Options for detail table's column with SET type
        $set_column_option_list = array();
        // Options for detail table's column with ENUM type
        $enum_column_option_list = array();
        // Detail table's one-to-many columns configurations
        $lookup_config_list = array();
        // Detail table's many-to-many columns configurations
        $many_to_many_config_list = array();
        // Prepare the data by using defined configurations and options
        $data = $this->_one_to_many_callback_field_data(
                'option', // DETAIL TABLE NAME
                'id', // DETAIL PK NAME
                'id_field', // DETAIL FK NAME
                $primary_key, // CURRENT TABLE PK VALUE
                $lookup_config_list, // LOOKUP CONFIGS
                $many_to_many_config_list, // MANY TO MANY CONFIGS
                $set_column_option_list, // SET OPTIONS
                $enum_column_option_list // ENUM OPTIONS
            );
        // Parse the data to the view
        return $this->load->view($this->cms_module_path().'/field_field_option',$data, TRUE);
    }

    // returned on view
    public function _callback_column_option($value, $row){
        return $this->_humanized_record_count(
                'option', // DETAIL TABLE NAME
                'id_field', // DETAIL FK NAME
                $row->id, // CURRENT TABLE PK VALUE
                array( // CAPTIONS
                    'single_caption'    => 'Option',
                    'multiple_caption'  => 'Options',
                    'zero_caption'      => 'No Option',
                )
            );
    }


    public function _after_insert_or_update($post_array, $primary_key){
        // SAVE CHANGES OF option
        $data = json_decode($this->input->post('md_real_field_option_col'), TRUE);
        $this->_save_one_to_many(
            'option', // FIELD NAME
            'option', // DETAIL TABLE NAME
            'id', // DETAIL PK NAME
            'id_field', // DETAIL FK NAME
            $primary_key, // PARENT PRIMARY KEY VALUE
            $data, // DATA
            array('id', 'name', 'shown'), // REAL DETAIL COLUMN NAMES
            array(), // SET DETAIL COLUMN NAMES
            $many_to_many_config_list=array()
        );

        // adjust tables
        $this->cck_model->adjust_physical_table($post_array['id_entity']);

        return TRUE;
    }

    public function _before_insert_or_update($post_array, $primary_key=NULL){
        // if view is equal to the default then no changes should be done
        if($post_array['custom_view'] == 'TRUE'){
            $view = $post_array['view'];
            $default_pattern = $this->cck_model->get_view_pattern_by_template($post_array['id_template']);
            if($this->cck_model->remove_white_spaces($view) == $this->cck_model->remove_white_spaces($default_pattern)){
                $post_array['view'] = '';
            }
        }else{
            $post_array['view'] = '';
        }
        // if input is equal to the default then no changes should be done
        if($post_array['custom_input'] == 'TRUE'){
            $input = $post_array['input'];
            $default_pattern = $this->cck_model->get_input_pattern_by_template($post_array['id_template']);
            if($this->cck_model->remove_white_spaces($input) == $this->cck_model->remove_white_spaces($default_pattern)){
                $post_array['input'] = '';
            }
        }else{
            $post_array['input'] = '';
        }
        return $post_array;
    }

    public function _show_edit($primary_key){
        return TRUE;
    }

    public function _show_delete($primary_key){
        return TRUE;
    }

    public function _allow_edit($primary_key){
        return TRUE;
    }

    public function _allow_delete($primary_key){
        return TRUE;
    }

    public function _before_insert($post_array){
        return $post_array;
    }

    public function _after_insert($post_array, $primary_key){
        return TRUE;
    }

    public function _before_update($post_array, $primary_key){
        return $post_array;
    }

    public function _after_update($post_array, $primary_key){
        return TRUE;
    }

    public function _before_delete($primary_key){
        // delete corresponding option
        $this->db->delete($this->t('option'),
              array('id_field'=>$primary_key));
        return TRUE;
    }

    public function _after_delete($primary_key){
        return TRUE;
    }

}
