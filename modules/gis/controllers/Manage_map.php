<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Manage_map
 *
 * @author No-CMS Module Generator
 */
class Manage_map extends CMS_CRUD_Controller {

    protected $URL_MAP = array();
    protected $TABLE_NAME = 'map';
    protected $COLUMN_NAMES = array('map_name', 'map_desc', 'latitude', 'longitude', 'gmap_roadmap', 'gmap_satellite', 'gmap_hybrid', 'zoom', 'height', 'width', 'layer', 'cloudmade_basemap', 'custom_form', 'custom_javascript');
    protected $PRIMARY_KEY = 'map_id';
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
        $crud->set_subject('Map');

        // displayed columns on list, edit, and add, uncomment to use
        $crud->columns('map_name', 'map_desc', 'layer', 'cloudmade_basemap');
        //$crud->edit_fields('map_name', 'map_desc', 'latitude', 'longitude', 'gmap_roadmap', 'gmap_satellite', 'gmap_hybrid', 'zoom', 'height', 'width', 'layer', 'cloudmade_basemap', '_updated_by', '_updated_at');
        //$crud->add_fields('map_name', 'map_desc', 'latitude', 'longitude', 'gmap_roadmap', 'gmap_satellite', 'gmap_hybrid', 'zoom', 'height', 'width', 'layer', 'cloudmade_basemap', '_created_by', '_created_at');
        //$crud->set_read_fields('map_name', 'map_desc', 'latitude', 'longitude', 'gmap_roadmap', 'gmap_satellite', 'gmap_hybrid', 'zoom', 'height', 'width', 'layer', 'cloudmade_basemap');

        // caption of each columns
        $crud->display_as('map_name','Map Name');
        $crud->display_as('map_desc','Map Desc');
        $crud->display_as('latitude','Latitude');
        $crud->display_as('longitude','Longitude');
        $crud->display_as('gmap_roadmap','Gmap Roadmap');
        $crud->display_as('gmap_satellite','Gmap Satellite');
        $crud->display_as('gmap_hybrid','Gmap Hybrid');
        $crud->display_as('zoom','Zoom');
        $crud->display_as('height','Height');
        $crud->display_as('width','Width');
        $crud->display_as('layer','Layer');
        $crud->display_as('cloudmade_basemap','Basemap');
        $crud->display_as('custom_form', 'Custom Form');
        $crud->display_as('custom_javascript', 'Custom Javascript');

        $crud->set_field_half_width(array('longitude', 'latitude'));
        $crud->set_field_one_third_width(array('gmap_roadmap', 'gmap_satellite', 'gmap_hybrid', 'zoom', 'height', 'width'));

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
        $crud->required_fields('map_name', 'latitude', 'longitude', 'gmap_roadmap', 'gmap_satellite', 'gmap_hybrid', 'zoom', 'height', 'width');

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
        $crud->field_type('gmap_hybrid', 'true_false');
        $crud->field_type('gmap_satellite', 'true_false');
        $crud->field_type('gmap_roadmap', 'true_false');
        $crud->unset_texteditor('custom_form');
        $crud->unset_texteditor('custom_javascript');


        ////////////////////////////////////////////////////////////////////////
        // HINT: Put Tabs (if needed)
        // usage:
        //     $crud->set_outside_tab($how_many_field_outside_tab);
        //     $crud->set_tabs(array(
        //        'First Tab Caption'  => $how_many_field_on_first_tab,
        //        'Second Tab Caption' => $how_many_field_on_second_tab,
        //     ));
        ////////////////////////////////////////////////////////////////////////
        $crud->set_outside_tab(10);
        $crud->set_tabs(array(
            'Layers' => 1,
            'Basemaps' => 1,
            'Custom HTML and Javascript' => 2,
        ));

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
        $this->view($this->cms_module_path().'/Manage_map_view', $output,
            $this->n('manage_map'), $config);
    }


    // returned on insert and edit
    public function _callback_field_layer($value, $primary_key){
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
                'layer', // DETAIL TABLE NAME
                'layer_id', // DETAIL PK NAME
                'map_id', // DETAIL FK NAME
                $primary_key, // CURRENT TABLE PK VALUE
                $lookup_config_list, // LOOKUP CONFIGS
                $many_to_many_config_list, // MANY TO MANY CONFIGS
                $set_column_option_list, // SET OPTIONS
                $enum_column_option_list // ENUM OPTIONS
            );
        $data['primary_key'] = $primary_key;
        // Parse the data to the view
        return $this->load->view($this->cms_module_path().'/field_map_layer',$data, TRUE);
    }

    // returned on view
    public function _callback_column_layer($value, $row){
        return $this->_humanized_record_count(
                'layer', // DETAIL TABLE NAME
                'map_id', // DETAIL FK NAME
                $row->map_id, // CURRENT TABLE PK VALUE
                array( // CAPTIONS
                    'single_caption'    => 'Layer',
                    'multiple_caption'  => 'Layers',
                    'zero_caption'      => 'No Layer',
                )
            );
    }


    // returned on insert and edit
    public function _callback_field_cloudmade_basemap($value, $primary_key){
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
                'cloudmade_basemap', // DETAIL TABLE NAME
                'basemap_id', // DETAIL PK NAME
                'map_id', // DETAIL FK NAME
                $primary_key, // CURRENT TABLE PK VALUE
                $lookup_config_list, // LOOKUP CONFIGS
                $many_to_many_config_list, // MANY TO MANY CONFIGS
                $set_column_option_list, // SET OPTIONS
                $enum_column_option_list // ENUM OPTIONS
            );
        // Parse the data to the view
        return $this->load->view($this->cms_module_path().'/field_map_cloudmade_basemap',$data, TRUE);
    }

    // returned on view
    public function _callback_column_cloudmade_basemap($value, $row){
        return $this->_humanized_record_count(
                'cloudmade_basemap', // DETAIL TABLE NAME
                'map_id', // DETAIL FK NAME
                $row->map_id, // CURRENT TABLE PK VALUE
                array( // CAPTIONS
                    'single_caption'    => 'Basemap',
                    'multiple_caption'  => 'Basemaps',
                    'zero_caption'      => 'No Cloudmade Basemap',
                )
            );
    }


    public function _after_insert_or_update($post_array, $primary_key){
        // SAVE CHANGES OF layer
        $data = json_decode($this->input->post('md_real_field_layer_col'), TRUE);
        $this->_save_one_to_many(
            'layer', // FIELD NAME
            'layer', // DETAIL TABLE NAME
            'layer_id', // DETAIL PK NAME
            'map_id', // DETAIL FK NAME
            $primary_key, // PARENT PRIMARY KEY VALUE
            $data, // DATA
            $real_column_list=array('layer_id', 'layer_name', 'group_name'), // REAL DETAIL COLUMN NAMES
            $set_column_list=array(), // SET DETAIL COLUMN NAMES
            $many_to_many_config_list=array()
        );

        // SAVE CHANGES OF cloudmade_basemap
        $data = json_decode($this->input->post('md_real_field_cloudmade_basemap_col'), TRUE);
        $this->_save_one_to_many(
            'cloudmade_basemap', // FIELD NAME
            'cloudmade_basemap', // DETAIL TABLE NAME
            'basemap_id', // DETAIL PK NAME
            'map_id', // DETAIL FK NAME
            $primary_key, // PARENT PRIMARY KEY VALUE
            $data, // DATA
            $real_column_list=array('basemap_id', 'basemap_name', 'url', 'max_zoom', 'attribution'), // REAL DETAIL COLUMN NAMES
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
