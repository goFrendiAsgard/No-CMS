<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Manage_layout
 *
 * @author No-CMS Module Generator
 */
class Manage_layout extends CMS_CRUD_Controller {

    protected $URL_MAP = array();
    protected $TABLE_NAME = 'main_layout';
    protected $COLUMN_NAMES = array('layout_name', 'template');
    protected $PRIMARY_KEY = 'layout_id';
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
        $crud->set_subject('Layout');

        // displayed columns on list, edit, and add, uncomment to use
        $crud->columns('layout_name');
        //$crud->edit_fields('layout_name', 'template', '_updated_by', '_updated_at');
        //$crud->add_fields('layout_name', 'template', '_created_by', '_created_at');
        //$crud->set_read_fields('layout_name', 'template');

        // caption of each columns
        $crud->display_as('layout_name','Layout Code');
        $crud->display_as('template','Template');

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
        $crud->unique_fields('layout_name');

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
        $crud->unset_texteditor('template');
        // default layout should not be deleted
        $operation = $crud->getState();
        if ($operation == 'edit' || $operation == 'update' || $operation == 'update_validation') {
            $row = $this->cms_get_record($this->t('main_layout'), 'layout_id', $this->PK_VALUE);
            if(in_array($row->layout_name, array('default', 'default-one-column', 'default-two-column', 'default-three-column', 'slide', 'slide-one-column', 'slide-two-column', 'slide-three-column', 'minimal'))){
                $crud->field_type('layout_name', 'readonly');
                $crud->required_fields('template');
            }
            $crud->required_fields('layout_name', 'template');
        }else{
            $crud->required_fields('layout_name', 'template');
        }

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
        $this->view($this->cms_module_path().'/Manage_layout_view', $output,
            $this->n('main_layout_management'), $config);
    }

    public function _callback_field_template($value, $primary_key){
        // assets
        $html = '<script src="'.base_url().'assets/nocms/js/jquery-ace/ace/ace.js"></script>
            <script src="'.base_url().'assets/nocms/js/jquery-ace/ace/theme-eclipse.js"></script>
            <script src="'.base_url().'assets/nocms/js/jquery-ace/ace/mode-html.js"></script>
            <script src="'.base_url().'assets/nocms/js/jquery-ace/jquery-ace.min.js"></script>';
        // input
        $html .= '<textarea id="field-template" name="template">'.$value.'</textarea>';
        // mutation
        $html .= '<script type="text/javascript">
                $("#field-template").ace({
                    theme: "eclipse",
                    lang: "html",
                    width: "100%",
                    height: "500px"
                });
                var decorator = $("#field-template").data("ace");
                var aceInstance = decorator.editor.ace;
                console.log(aceInstance);
                aceInstance.setFontSize("16px");
            </script>';
        return $html;
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
        if($primary_key == 1){
            return FALSE;
        }
        $row = $this->cms_get_record($this->t('main_layout'), 'layout_id', $primary_key);
        // default layout should not be deleted
        if(in_array($row->layout_name, array('default', 'default-one-column', 'default-two-column', 'default-three-column', 'slide', 'slide-one-column', 'slide-two-column', 'slide-three-column', 'minimal'))){
            return FALSE;
        }
        return TRUE;
    }

    public function _allow_edit($primary_key){
        return TRUE;
    }

    public function _allow_delete($primary_key){
        return $this->_show_delete($primary_key);
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
