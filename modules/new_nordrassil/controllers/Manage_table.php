<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$core_dirname = dirname(dirname(__FILE__));
if(substr($core_dirname,-1) != '/'){
    $core_dirname .= '/';
}
$core_dirname .= 'core/';
include($core_dirname.'Nds_Special_Crud_Controller.php');
/**
 * Description of Manage_table
 *
 * @author No-CMS Module Generator
 */
class Manage_table extends Nds_Special_CRUD_Controller {

    protected $URL_MAP = array();
    protected $TABLE_NAME = 'table';
    protected $COLUMN_NAMES = array('project_id', 'name', 'caption', 'priority', 'data', 'column', 'table_option');
    protected $PRIMARY_KEY = 'table_id';
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

        ////////////////////////////////////////////////////////////////////////
        // HINT: You can access this variables after calling parent's make_crud method:
        //      $this->CRUD
        //      $this->STATE
        //      $this->STATE_INFO
        //      $this->PK_VALUE
        ////////////////////////////////////////////////////////////////////////

        // set subject
        $crud->set_subject('Table');

        // displayed columns on list, edit, and add, uncomment to use
        //$crud->columns('project_id', 'name', 'caption', 'priority', 'data', 'column', 'table_option');
        //$crud->edit_fields('project_id', 'name', 'caption', 'priority', 'data', 'column', 'table_option', '_updated_by', '_updated_at');
        //$crud->add_fields('project_id', 'name', 'caption', 'priority', 'data', 'column', 'table_option', '_created_by', '_created_at');
        //$crud->set_read_fields('project_id', 'name', 'caption', 'priority', 'data', 'column', 'table_option');

        // caption of each columns
        $crud->display_as('project_id','Project');
        $crud->display_as('name','Name');
        $crud->display_as('caption','Caption');
        $crud->display_as('priority','Priority');
        $crud->display_as('data','Data');
        $crud->display_as('column','Column');
        $crud->display_as('table_option','Table Option');

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
        $crud->set_relation('project_id', $this->t('project'), 'template_id');

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




        ////////////////////////////////////////////////////////////////////////
        // HINT: Put Tabs (if needed)
        // usage:
        //     $crud->set_outside_tab($how_many_field_outside_tab);
        //     $crud->set_tabs(array(
        //        'First Tab Caption'  => $how_many_field_on_first_tab,
        //        'Second Tab Caption' => $how_many_field_on_second_tab,
        //     ));
        ////////////////////////////////////////////////////////////////////////

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

        // show the view
        $this->view($this->cms_module_path().'/Manage_table_view', $output,
            $this->n('manage_table'), $config);
    }


    // returned on insert and edit
    public function _callback_field_column($value, $primary_key){
        // Options for detail table's column with SET type
        $set_column_option_list = array();
        // Options for detail table's column with ENUM type
        $enum_column_option_list = array();
        // Detail table's one-to-many columns configurations
        $lookup_config_list = array(
            'lookup_table_id' => array(
                'selection_table'         => 'table',
                'selection_pk_column'     => 'table_id',
                'selection_lookup_column' => 'name',
            ),
            'lookup_column_id' => array(
                'selection_table'         => 'column',
                'selection_pk_column'     => 'column_id',
                'selection_lookup_column' => 'name',
            ),
            'relation_table_id' => array(
                'selection_table'         => 'table',
                'selection_pk_column'     => 'table_id',
                'selection_lookup_column' => 'name',
            ),
            'relation_table_column_id' => array(
                'selection_table'         => 'column',
                'selection_pk_column'     => 'column_id',
                'selection_lookup_column' => 'name',
            ),
            'relation_selection_column_id' => array(
                'selection_table'         => 'column',
                'selection_pk_column'     => 'column_id',
                'selection_lookup_column' => 'name',
            ),
            'relation_priority_column_id' => array(
                'selection_table'         => 'column',
                'selection_pk_column'     => 'column_id',
                'selection_lookup_column' => 'name',
            ),
            'selection_table_id' => array(
                'selection_table'         => 'table',
                'selection_pk_column'     => 'table_id',
                'selection_lookup_column' => 'name',
            ),
            'selection_column_id' => array(
                'selection_table'         => 'column',
                'selection_pk_column'     => 'column_id',
                'selection_lookup_column' => 'name',
            ),
        );
        // Detail table's many-to-many columns configurations
        $many_to_many_config_list = array();
        // Prepare the data by using defined configurations and options
        $data = $this->_one_to_many_callback_field_data(
                'column', // DETAIL TABLE NAME
                'column_id', // DETAIL PK NAME
                'table_id', // DETAIL FK NAME
                $primary_key, // CURRENT TABLE PK VALUE
                $lookup_config_list, // LOOKUP CONFIGS
                $many_to_many_config_list, // MANY TO MANY CONFIGS
                $set_column_option_list, // SET OPTIONS
                $enum_column_option_list // ENUM OPTIONS
            );
        // Parse the data to the view
        return $this->load->view($this->cms_module_path().'/field_table_column',$data, TRUE);
    }

    // returned on view
    public function _callback_column_column($value, $row){
        return $this->_humanized_record_count(
                'column', // DETAIL TABLE NAME
                'table_id', // DETAIL FK NAME
                $row->table_id, // CURRENT TABLE PK VALUE
                array( // CAPTIONS
                    'single_caption'    => 'Column',
                    'multiple_caption'  => 'Columns',
                    'zero_caption'      => 'No Column',
                )
            );
    }


    // returned on insert and edit
    public function _callback_field_table_option($value, $primary_key){
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
                'table_option', // DETAIL TABLE NAME
                'id', // DETAIL PK NAME
                'table_id', // DETAIL FK NAME
                $primary_key, // CURRENT TABLE PK VALUE
                $lookup_config_list, // LOOKUP CONFIGS
                $many_to_many_config_list, // MANY TO MANY CONFIGS
                $set_column_option_list, // SET OPTIONS
                $enum_column_option_list // ENUM OPTIONS
            );
        // Parse the data to the view
        return $this->load->view($this->cms_module_path().'/field_table_table_option',$data, TRUE);
    }

    // returned on view
    public function _callback_column_table_option($value, $row){
        return $this->_humanized_record_count(
                'table_option', // DETAIL TABLE NAME
                'table_id', // DETAIL FK NAME
                $row->table_id, // CURRENT TABLE PK VALUE
                array( // CAPTIONS
                    'single_caption'    => 'Table Option',
                    'multiple_caption'  => 'Table Options',
                    'zero_caption'      => 'No Table Option',
                )
            );
    }


    public function _after_insert_or_update($post_array, $primary_key){
        // SAVE CHANGES OF column
        $data = json_decode($this->input->post('md_real_field_column_col'), TRUE);
        $this->_save_one_to_many(
            'column', // FIELD NAME
            'column', // DETAIL TABLE NAME
            'column_id', // DETAIL PK NAME
            'table_id', // DETAIL FK NAME
            $primary_key, // PARENT PRIMARY KEY VALUE
            $data, // DATA
            $real_column_list=array('column_id', 'name', 'caption', 'data_type', 'data_size', 'role', 'lookup_table_id', 'lookup_column_id', 'relation_table_id', 'relation_table_column_id', 'relation_selection_column_id', 'relation_priority_column_id', 'selection_table_id', 'selection_column_id', 'priority', 'value_selection_mode', 'value_selection_item'), // REAL DETAIL COLUMN NAMES
            $set_column_list=array(), // SET DETAIL COLUMN NAMES
            $many_to_many_config_list=array()
        );

        // SAVE CHANGES OF table_option
        $data = json_decode($this->input->post('md_real_field_table_option_col'), TRUE);
        $this->_save_one_to_many(
            'table_option', // FIELD NAME
            'table_option', // DETAIL TABLE NAME
            'id', // DETAIL PK NAME
            'table_id', // DETAIL FK NAME
            $primary_key, // PARENT PRIMARY KEY VALUE
            $data, // DATA
            $real_column_list=array('id', 'option_id'), // REAL DETAIL COLUMN NAMES
            $set_column_list=array(), // SET DETAIL COLUMN NAMES
            $many_to_many_config_list=array()
        );

        return TRUE;
    }

    public function _before_insert_or_update($post_array, $primary_key=NULL){
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
        return TRUE;
    }

    public function _after_delete($primary_key){
        return TRUE;
    }

}
