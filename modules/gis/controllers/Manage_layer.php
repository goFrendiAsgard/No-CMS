<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Manage_layer
 *
 * @author No-CMS Module Generator
 */
class Manage_layer extends CMS_CRUD_Controller {

    protected $URL_MAP = array();
    protected $TABLE_NAME = 'layer';
    protected $COLUMN_NAMES = array('map_id', 'layer_name', 'group_name', 'layer_desc', 'z_index', 'shown', 'radius', 'fill_color', 'color', 'weight', 'opacity', 'fill_opacity', 'image_url', 'use_json_url', 'json_url', 'json_sql', 'json_shape_column', 'json_popup_content', 'json_label', 'searchable', 'use_search_url', 'search_url', 'search_sql', 'search_result_content', 'search_result_x_column', 'search_result_y_column');
    protected $PRIMARY_KEY = 'layer_id';
    protected $UNSET_JQUERY = TRUE;
    protected $UNSET_READ = TRUE;
    protected $UNSET_ADD = FALSE;
    protected $UNSET_EDIT = FALSE;
    protected $UNSET_DELETE = FALSE;
    protected $UNSET_LIST = FALSE;
    protected $UNSET_BACK_TO_LIST = FALSE;
    protected $UNSET_PRINT = FALSE;
    protected $UNSET_EXPORT = FALSE;

    protected function make_crud($map_id = NULL){
        $crud = parent::make_crud();

        ////////////////////////////////////////////////////////////////////////
        // HINT: You can access this variables after calling parent's make_crud method:
        //      $this->CRUD
        //      $this->STATE
        //      $this->STATE_INFO
        //      $this->PK_VALUE
        ////////////////////////////////////////////////////////////////////////

        if($map_id != NULL){
            $crud->where($this->t('layer').'.map_id', $map_id);
            // displayed columns on list
            $crud->columns('layer_name', 'group_name');
        }else{
            $crud->columns('map_id', 'layer_name', 'group_name');
        }
        $crud->order_by('map_id');

        // set subject
        $crud->set_subject('Layer');

        // displayed columns on list, edit, and add, uncomment to use
        //$crud->columns('map_id', 'layer_name', 'group_name', 'layer_desc', 'z_index', 'shown', 'radius', 'fill_color', 'color', 'weight', 'opacity', 'fill_opacity', 'image_url', 'json_sql', 'json_shape_column', 'json_popup_content', 'json_label', 'use_json_url', 'json_url', 'searchable', 'search_sql', 'search_result_content', 'search_result_x_column', 'search_result_y_column', 'use_search_url', 'search_url');
        //$crud->edit_fields('map_id', 'layer_name', 'group_name', 'layer_desc', 'z_index', 'shown', 'radius', 'fill_color', 'color', 'weight', 'opacity', 'fill_opacity', 'image_url', 'json_sql', 'json_shape_column', 'json_popup_content', 'json_label', 'use_json_url', 'json_url', 'searchable', 'search_sql', 'search_result_content', 'search_result_x_column', 'search_result_y_column', 'use_search_url', 'search_url', '_updated_by', '_updated_at');
        //$crud->add_fields('map_id', 'layer_name', 'group_name', 'layer_desc', 'z_index', 'shown', 'radius', 'fill_color', 'color', 'weight', 'opacity', 'fill_opacity', 'image_url', 'json_sql', 'json_shape_column', 'json_popup_content', 'json_label', 'use_json_url', 'json_url', 'searchable', 'search_sql', 'search_result_content', 'search_result_x_column', 'search_result_y_column', 'use_search_url', 'search_url', '_created_by', '_created_at');
        //$crud->set_read_fields('map_id', 'layer_name', 'group_name', 'layer_desc', 'z_index', 'shown', 'radius', 'fill_color', 'color', 'weight', 'opacity', 'fill_opacity', 'image_url', 'json_sql', 'json_shape_column', 'json_popup_content', 'json_label', 'use_json_url', 'json_url', 'searchable', 'search_sql', 'search_result_content', 'search_result_x_column', 'search_result_y_column', 'use_search_url', 'search_url');

        // caption of each columns
        $crud->display_as('map_id','Map');
        $crud->display_as('layer_name','Layer Name');
        $crud->display_as('group_name','Group Name');
        $crud->display_as('layer_desc','Layer Desc');
        $crud->display_as('z_index','Z Index');
        $crud->display_as('shown','Shown');
        $crud->display_as('radius','Radius');
        $crud->display_as('fill_color','Fill Color');
        $crud->display_as('color','Color');
        $crud->display_as('weight','Weight');
        $crud->display_as('opacity','Opacity');
        $crud->display_as('fill_opacity','Fill Opacity');
        $crud->display_as('image_url','Image Url');
        $crud->display_as('json_sql','Json Sql');
        $crud->display_as('json_shape_column','Json Shape Column');
        $crud->display_as('json_popup_content','Json Popup Content');
        $crud->display_as('json_label','Json Label');
        $crud->display_as('use_json_url','Use Json Url');
        $crud->display_as('json_url','Json Url');
        $crud->display_as('searchable','Searchable');
        $crud->display_as('search_sql','Search Sql');
        $crud->display_as('search_result_content','Search Result Content');
        $crud->display_as('search_result_x_column','Search Result X Column');
        $crud->display_as('search_result_y_column','Search Result Y Column');
        $crud->display_as('use_search_url','Use Search Url');
        $crud->display_as('search_url','Search Url');

        $crud->set_field_half_width(array('layer_name', 'group_name', 'search_result_x_column', 'search_result_y_column', 'use_search_url', 'search_url', 'use_json_url', 'json_url'));
        $crud->set_field_one_third_width(array('z_index', 'shown', 'radius', 'fill_color', 'color', 'weight', 'opacity', 'fill_opacity'));

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
        if($map_id == NULL){
            $crud->required_fields('map_id', 'layer_name', 'z_index', 'shown', 'radius', 'fill_color', 'color', 'weight', 'opacity', 'fill_opacity', 'use_json_url', 'searchable', 'use_search_url');
        }else{
            $crud->required_fields('layer_name', 'z_index', 'shown', 'radius', 'fill_color', 'color', 'weight', 'opacity', 'fill_opacity', 'use_json_url', 'searchable', 'use_search_url');
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
        if($map_id == NULL){
            $crud->set_relation('map_id', $this->t('map'), 'map_name');
        }else{
            $crud->field_type('map_id', 'hidden', $map_id);
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
        $crud->field_type('shown', 'true_false');
        $crud->field_type('searchable', 'true_false');
        $crud->field_type('use_search_url', 'true_false');
        $crud->field_type('use_json_url', 'true_false');
        $crud->unset_texteditor('json_sql');
        $crud->unset_texteditor('json_popup_content');
        $crud->unset_texteditor('json_label');
        $crud->unset_texteditor('search_sql');
        $crud->unset_texteditor('search_result_content');



        ////////////////////////////////////////////////////////////////////////
        // HINT: Put Tabs (if needed)
        // usage:
        //     $crud->set_outside_tab($how_many_field_outside_tab);
        //     $crud->set_tabs(array(
        //        'First Tab Caption'  => $how_many_field_on_first_tab,
        //        'Second Tab Caption' => $how_many_field_on_second_tab,
        //     ));
        ////////////////////////////////////////////////////////////////////////
        $crud->set_outside_tab(12);
        $crud->set_tabs(array(
            'JSON' => 6,
            'Search Options' => 7,
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

    public function index($map_id = NULL){
        // create crud
        if(!is_numeric($map_id)){
            $map_id = NULL;
        }
        $crud = $this->make_crud($map_id);

        // render
        $render = $this->render_crud($crud);
        $output = $render['output'];
        $config = $render['config'];

        if(isset($map_id) && is_numeric($map_id)){
            $output->map_id = $map_id;
            $row_map = $this->cms_get_record($this->t('map'), 'map_id', $map_id);
            $output->map_name = $row_map->map_name;
        }

        // show the view
        $this->view($this->cms_module_path().'/Manage_layer_view', $output,
            $this->n('manage_layer'), $config);
    }



    public function _after_insert_or_update($post_array, $primary_key){

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
