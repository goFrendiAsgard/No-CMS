<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Manage_label
 *
 * @author No-CMS Module Generator
 */
class Manage_label extends CMS_CRUD_Controller {

    protected $URL_MAP = array();
    protected $TABLE_NAME = 'label';
    protected $COLUMN_NAMES = array('name', 'status', 'ip', 'leech', 'useragent');
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
        $crud->set_subject('Label');

        // displayed columns on list, edit, and add, uncomment to use
        $crud->columns('name', 'status');
        //$crud->edit_fields('name', 'status', 'ip', 'leech', 'useragent', '_updated_by', '_updated_at');
        //$crud->add_fields('name', 'status', 'ip', 'leech', 'useragent', '_created_by', '_created_at');
        //$crud->set_read_fields('name', 'status', 'ip', 'leech', 'useragent');

        // caption of each columns
        $crud->display_as('name','Name');
        $crud->display_as('status','Status');
        $crud->display_as('ip','IP List');
        $crud->display_as('leech','Leech List');
        $crud->display_as('useragent','User Agent List');

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
        $crud->field_type('status', 'enum', array('not set','black list','white list'));



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
        $this->view($this->cms_module_path().'/Manage_label_view', $output,
            $this->n('manage_label'), $config);
    }


    // returned on insert and edit
    public function _callback_field_ip($value, $primary_key){
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
                'ip', // DETAIL TABLE NAME
                'id', // DETAIL PK NAME
                'id_label', // DETAIL FK NAME
                $primary_key, // CURRENT TABLE PK VALUE
                $lookup_config_list, // LOOKUP CONFIGS
                $many_to_many_config_list, // MANY TO MANY CONFIGS
                $set_column_option_list, // SET OPTIONS
                $enum_column_option_list // ENUM OPTIONS
            );
        // Parse the data to the view
        return $this->load->view($this->cms_module_path().'/field_label_ip',$data, TRUE);
    }

    // returned on view
    public function _callback_column_ip($value, $row){
        return $this->_humanized_record_count(
                'ip', // DETAIL TABLE NAME
                'id_label', // DETAIL FK NAME
                $row->id, // CURRENT TABLE PK VALUE
                array( // CAPTIONS
                    'single_caption'    => 'Ip',
                    'multiple_caption'  => 'Ips',
                    'zero_caption'      => 'No Ip',
                )
            );
    }


    // returned on insert and edit
    public function _callback_field_leech($value, $primary_key){
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
                'leech', // DETAIL TABLE NAME
                'id', // DETAIL PK NAME
                'id_label', // DETAIL FK NAME
                $primary_key, // CURRENT TABLE PK VALUE
                $lookup_config_list, // LOOKUP CONFIGS
                $many_to_many_config_list, // MANY TO MANY CONFIGS
                $set_column_option_list, // SET OPTIONS
                $enum_column_option_list // ENUM OPTIONS
            );
        // Parse the data to the view
        return $this->load->view($this->cms_module_path().'/field_label_leech',$data, TRUE);
    }

    // returned on view
    public function _callback_column_leech($value, $row){
        return $this->_humanized_record_count(
                'leech', // DETAIL TABLE NAME
                'id_label', // DETAIL FK NAME
                $row->id, // CURRENT TABLE PK VALUE
                array( // CAPTIONS
                    'single_caption'    => 'Leech',
                    'multiple_caption'  => 'Leechs',
                    'zero_caption'      => 'No Leech',
                )
            );
    }


    // returned on insert and edit
    public function _callback_field_useragent($value, $primary_key){
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
                'useragent', // DETAIL TABLE NAME
                'id', // DETAIL PK NAME
                'id_label', // DETAIL FK NAME
                $primary_key, // CURRENT TABLE PK VALUE
                $lookup_config_list, // LOOKUP CONFIGS
                $many_to_many_config_list, // MANY TO MANY CONFIGS
                $set_column_option_list, // SET OPTIONS
                $enum_column_option_list // ENUM OPTIONS
            );
        // Parse the data to the view
        return $this->load->view($this->cms_module_path().'/field_label_useragent',$data, TRUE);
    }

    // returned on view
    public function _callback_column_useragent($value, $row){
        return $this->_humanized_record_count(
                'useragent', // DETAIL TABLE NAME
                'id_label', // DETAIL FK NAME
                $row->id, // CURRENT TABLE PK VALUE
                array( // CAPTIONS
                    'single_caption'    => 'Useragent',
                    'multiple_caption'  => 'Useragents',
                    'zero_caption'      => 'No Useragent',
                )
            );
    }


    public function _after_insert_or_update($post_array, $primary_key){
        // SAVE CHANGES OF ip
        $data = json_decode($this->input->post('md_real_field_ip_col'), TRUE);
        $this->_save_one_to_many(
            'ip', // FIELD NAME
            'ip', // DETAIL TABLE NAME
            'id', // DETAIL PK NAME
            'id_label', // DETAIL FK NAME
            $primary_key, // PARENT PRIMARY KEY VALUE
            $data, // DATA
            $real_column_list=array('id', 'content'), // REAL DETAIL COLUMN NAMES
            $set_column_list=array(), // SET DETAIL COLUMN NAMES
            $many_to_many_config_list=array()
        );

        // SAVE CHANGES OF leech
        $data = json_decode($this->input->post('md_real_field_leech_col'), TRUE);
        $this->_save_one_to_many(
            'leech', // FIELD NAME
            'leech', // DETAIL TABLE NAME
            'id', // DETAIL PK NAME
            'id_label', // DETAIL FK NAME
            $primary_key, // PARENT PRIMARY KEY VALUE
            $data, // DATA
            $real_column_list=array('id', 'content'), // REAL DETAIL COLUMN NAMES
            $set_column_list=array(), // SET DETAIL COLUMN NAMES
            $many_to_many_config_list=array()
        );

        // SAVE CHANGES OF useragent
        $data = json_decode($this->input->post('md_real_field_useragent_col'), TRUE);
        $this->_save_one_to_many(
            'useragent', // FIELD NAME
            'useragent', // DETAIL TABLE NAME
            'id', // DETAIL PK NAME
            'id_label', // DETAIL FK NAME
            $primary_key, // PARENT PRIMARY KEY VALUE
            $data, // DATA
            $real_column_list=array('id', 'content'), // REAL DETAIL COLUMN NAMES
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
