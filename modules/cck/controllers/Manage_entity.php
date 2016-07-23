<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Manage_entity
 *
 * @author No-CMS Module Generator
 */
class Manage_entity extends CMS_CRUD_Controller {

    protected $URL_MAP = array();
    protected $TABLE_NAME = 'entity';
    protected $COLUMN_NAMES = array('name', 'per_user_limitation', 'max_record_per_user', 'id_authorization_view', 'group_entity_view', 'id_authorization_add', 'group_entity_add', 'id_authorization_edit', 'group_entity_edit', 'id_authorization_delete', 'group_entity_delete', 'id_authorization_browse', 'group_entity_browse', 'field', 'per_record_html', 'custom_per_record_html');
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

    protected function make_crud(){
        $crud = parent::make_crud();

        ////////////////////////////////////////////////////////////////////////
        // HINT: You can access this variables after calling parent's make_crud method:
        //      $this->CRUD
        //      $this->STATE
        //      $this->STATE_INFO
        //      $this->PK_VALUE
        ////////////////////////////////////////////////////////////////////////

        // set subject
        $crud->set_subject('Entity');

        // displayed columns on list, edit, and add, uncomment to use
        $crud->columns('name');
        //$crud->edit_fields('name', 'max_record_per_user', 'per_user_limitation', 'id_authorization_view', 'id_authorization_add', 'id_authorization_edit', 'id_authorization_delete', 'id_authorization_browse', 'field', 'group_entity_add', 'group_entity_edit', 'group_entity_view', 'group_entity_browse', 'group_entity_delete', '_updated_by', '_updated_at');
        //$crud->add_fields('name', 'max_record_per_user', 'per_user_limitation', 'id_authorization_view', 'id_authorization_add', 'id_authorization_edit', 'id_authorization_delete', 'id_authorization_browse', 'field', 'group_entity_add', 'group_entity_edit', 'group_entity_view', 'group_entity_browse', 'group_entity_delete', '_created_by', '_created_at');
        //$crud->set_read_fields('name', 'max_record_per_user', 'per_user_limitation', 'id_authorization_view', 'id_authorization_add', 'id_authorization_edit', 'id_authorization_delete', 'id_authorization_browse', 'field', 'group_entity_add', 'group_entity_edit', 'group_entity_view', 'group_entity_browse', 'group_entity_delete');

        // caption of each columns
        $crud->display_as('name','Name');
        $crud->display_as('max_record_per_user','Max Record Per User');
        $crud->display_as('per_user_limitation','Per User Limitation');
        $crud->display_as('id_authorization_view','View Authorization');
        $crud->display_as('id_authorization_add','Add Authorization');
        $crud->display_as('id_authorization_edit','Edit Authorization');
        $crud->display_as('id_authorization_delete','Delete Authorization');
        $crud->display_as('id_authorization_browse','Browse Authorization');
        $crud->display_as('field','Field');
        $crud->display_as('group_entity_add','Add Group');
        $crud->display_as('group_entity_edit','Edit Group');
        $crud->display_as('group_entity_view','View Group');
        $crud->display_as('group_entity_browse','Browse Group');
        $crud->display_as('group_entity_delete','Delete Group');
        $crud->display_as('per_record_html','Per Record HTML');

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
        $crud->required_fields('name', 'id_authorization_view', 'id_authorization_add', 'id_authorization_edit', 'id_authorization_delete', 'id_authorization_browse');

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put required field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/unique_fields)
        // eg:
        //      $crud->unique_fields( $field1, $field2, $field3, ... );
        ////////////////////////////////////////////////////////////////////////
        $crud->unique_fields('name');


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
        $crud->set_relation('id_authorization_view', cms_table_name('main_authorization'), 'authorization_name');
        $crud->set_relation('id_authorization_add', cms_table_name('main_authorization'), 'authorization_name');
        $crud->set_relation('id_authorization_edit', cms_table_name('main_authorization'), 'authorization_name');
        $crud->set_relation('id_authorization_delete', cms_table_name('main_authorization'), 'authorization_name');
        $crud->set_relation('id_authorization_browse', cms_table_name('main_authorization'), 'authorization_name');

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put set relation_n_n (detail many to many) codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation_n_n)
        // eg:
        //      $crud->set_relation_n_n( $field_name, $relation_table, $selection_table, $primary_key_alias_to_this_table,
        //          $primary_key_alias_to_selection_table , $title_field_selection_table, $priority_field_relation );
        ////////////////////////////////////////////////////////////////////////
        $crud->set_relation_n_n('group_entity_add',
            $this->t('group_entity_add'),
            cms_table_name('main_group'),
            'id_entity', 'id_group',
            'group_name', NULL);
        $crud->set_relation_n_n('group_entity_edit',
            $this->t('group_entity_edit'),
            cms_table_name('main_group'),
            'id_entity', 'id_group',
            'group_name', NULL);
        $crud->set_relation_n_n('group_entity_view',
            $this->t('group_entity_view'),
            cms_table_name('main_group'),
            'id_entity', 'id_group',
            'group_name', NULL);
        $crud->set_relation_n_n('group_entity_browse',
            $this->t('group_entity_browse'),
            cms_table_name('main_group'),
            'id_entity', 'id_group',
            'group_name', NULL);
        $crud->set_relation_n_n('group_entity_delete',
            $this->t('group_entity_delete'),
            cms_table_name('main_group'),
            'id_entity', 'id_group',
            'group_name', NULL);

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put custom field type here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/field_type)
        // eg:
        //      $crud->field_type( $field_name , $field_type, $value  );
        ////////////////////////////////////////////////////////////////////////
        $crud->field_type('per_user_limitation', 'true_false');
        $crud->unset_texteditor('per_record_html');

        // set hidden field to let the view know whether per_record_html is custom or default.
        $custom_per_record_html = 'FALSE';
        if($this->PK_VALUE > 0){
            $current_entity = $this->cms_get_record($this->t('entity'), 'id', $this->PK_VALUE);
            if($current_entity != NULL && trim($current_entity->per_record_html) != ''){
                $custom_per_record_html = 'TRUE';
            }
        }
        $crud->field_type('custom_per_record_html', 'hidden', $custom_per_record_html);

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put Tabs (if needed)
        // usage:
        //     $crud->set_outside_tab($how_many_field_outside_tab);
        //     $crud->set_tabs(array(
        //        'First Tab Caption'  => $how_many_field_on_first_tab,
        //        'Second Tab Caption' => $how_many_field_on_second_tab,
        //     ));
        ////////////////////////////////////////////////////////////////////////
        $crud->set_field_half_width(array('per_user_limitation', 'max_record_per_user', 'id_authorization_view', 'group_entity_view', 'id_authorization_add', 'group_entity_add', 'id_authorization_edit', 'group_entity_edit', 'id_authorization_delete', 'group_entity_delete', 'id_authorization_browse', 'group_entity_browse'));

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

    public function index(){
        // create crud
        $crud = $this->make_crud();

        // render
        $render = $this->render_crud($crud);
        $output = $render['output'];
        $config = $render['config'];
        $output->id_entity = $this->PK_VALUE;

        // show the view
        $this->view($this->cms_module_path().'/Manage_entity_view', $output,
            $this->n('manage_entity'), $config);
    }


    // returned on insert and edit
    public function _callback_field_field($value, $primary_key){
        // Options for detail table's column with SET type
        $set_column_option_list = array();
        // Options for detail table's column with ENUM type
        $enum_column_option_list = array();
        // Detail table's one-to-many columns configurations
        $lookup_config_list = array(
            'id_template' => array(
                'selection_table'         => 'template',
                'selection_pk_column'     => 'id',
                'selection_lookup_column' => 'name',
            ),
        );
        // Detail table's many-to-many columns configurations
        $many_to_many_config_list = array();
        // Prepare the data by using defined configurations and options
        $data = $this->_one_to_many_callback_field_data(
                'field', // DETAIL TABLE NAME
                'id', // DETAIL PK NAME
                'id_entity', // DETAIL FK NAME
                $primary_key, // CURRENT TABLE PK VALUE
                $lookup_config_list, // LOOKUP CONFIGS
                $many_to_many_config_list, // MANY TO MANY CONFIGS
                $set_column_option_list, // SET OPTIONS
                $enum_column_option_list // ENUM OPTIONS
            );
        $data['primary_key'] = $primary_key;
        // Parse the data to the view
        return $this->load->view($this->cms_module_path().'/field_entity_field',$data, TRUE);
    }

    // returned on view
    public function _callback_column_field($value, $row){
        return $this->_humanized_record_count(
                'field', // DETAIL TABLE NAME
                'id_entity', // DETAIL FK NAME
                $row->id, // CURRENT TABLE PK VALUE
                array( // CAPTIONS
                    'single_caption'    => 'Field',
                    'multiple_caption'  => 'Fields',
                    'zero_caption'      => 'No Field',
                )
            );
    }


    public function _after_insert_or_update($post_array, $primary_key){
        // SAVE CHANGES OF field
        $data = json_decode($this->input->post('md_real_field_field_col'), TRUE);
        $this->_save_one_to_many(
            'field', // FIELD NAME
            'field', // DETAIL TABLE NAME
            'id', // DETAIL PK NAME
            'id_entity', // DETAIL FK NAME
            $primary_key, // PARENT PRIMARY KEY VALUE
            $data, // DATA
            array('id', 'name', 'id_template'), // REAL DETAIL COLUMN NAMES
            array(), // SET DETAIL COLUMN NAMES
            $many_to_many_config_list=array()
        );

        // adjust tables
        $this->cck_model->adjust_physical_table($primary_key);
        // adjust navigation and privilege
        $this->cck_model->adjust_navigation($primary_key); 
        return TRUE;
    }

    public function _before_insert_or_update($post_array, $primary_key=NULL){
        // if per_per_record_html is equal to the default then no changes should be done
        if($post_array['custom_per_record_html'] == 'TRUE'){
            $per_record_html = $post_array['per_record_html'];
            $default_pattern = $this->cck_model->get_default_per_record_html_pattern($primary_key);
            if($this->cck_model->remove_white_spaces($per_record_html) == $this->cck_model->remove_white_spaces($default_pattern)){
                $post_array['per_record_html'] = '';
            }
        }else{
            $post_array['per_record_html'] = '';
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
        // delete corresponding field
        $this->db->delete($this->t('field'),
            array('id_entity'=>$primary_key));
        // delete navigation and privilege
        $this->cck_model->delete_navigation($primary_key);
        return TRUE;
    }

    public function _after_delete($primary_key){
        return TRUE;
    }

}
