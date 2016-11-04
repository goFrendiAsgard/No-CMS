&lt;?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of {{ controller_name }}
 *
 * @author No-CMS Module Generator
 */
class {{ controller_name }} extends CMS_CRUD_Controller {

    protected $URL_MAP = array();
    protected $TABLE_NAME = '{{ table_name }}';
    protected $COLUMN_NAMES = array({{ field_list }});
    protected $PRIMARY_KEY = '{{ primary_key }}';
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
        $crud->set_subject('{{ table_caption }}');

        // displayed columns on list, edit, and add, uncomment to use
        //$crud->columns({{ field_list }});
        //$crud->edit_fields({{ edit_field_list }});
        //$crud->add_fields({{ add_field_list }});
        //$crud->set_read_fields({{ field_list }});

        // caption of each columns
{{ display_as }}

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
        {{ required_fields }}

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put required field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/unique_fields)
        // eg:
        //      $crud->unique_fields( $field1, $field2, $field3, ... );
        ////////////////////////////////////////////////////////////////////////
        {{ unique_fields }}

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_rules)
        // eg:
        //      $crud->set_rules( $field_name , $caption, $filter );
        ////////////////////////////////////////////////////////////////////////
{{ set_rules }}

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put set relation (lookup) codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation)
        // eg:
        //      $crud->set_relation( $field_name , $related_table, $related_title_field , $where , $order_by );
        ////////////////////////////////////////////////////////////////////////
{{ set_relation }}

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put set relation_n_n (detail many to many) codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation_n_n)
        // eg:
        //      $crud->set_relation_n_n( $field_name, $relation_table, $selection_table, $primary_key_alias_to_this_table,
        //          $primary_key_alias_to_selection_table , $title_field_selection_table, $priority_field_relation );
        ////////////////////////////////////////////////////////////////////////
{{ set_relation_n_n }}

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put custom field type here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/field_type)
        // eg:
        //      $crud->field_type( $field_name , $field_type, $value  );
        ////////////////////////////////////////////////////////////////////////
{{ enum_set_field }}
{{ hide_field }}
{{ upload }}

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

    ////////////////////////////////////////////////////////////////////////////
    // Landing function
    ////////////////////////////////////////////////////////////////////////////
    public function index(){
        // create crud
        $crud = $this->make_crud();

        // render
        $render = $this->render_crud($crud);
        $output = $render['output'];
        $config = $render['config'];

        // show the view
        $this->view($this->cms_module_path().'/{{ view_import_name }}', $output,
            $this->n('{{ navigation_name }}'), $config);
    }

{{ detail_callback_declaration }}

    ////////////////////////////////////////////////////////////////////////////
    // After insert or update, return TRUE if success
    // Use this if you want to do something after insert/update.
    // Typically contains scripts to save detail tables.
    ////////////////////////////////////////////////////////////////////////////
    public function _after_insert_or_update($post_array, $primary_key){
{{ detail_after_insert_or_update }}
        return TRUE;
    }

    ////////////////////////////////////////////////////////////////////////////
    // Before insert or update, return new $post_array or FALSE if you want to
    // cancel the operation.
    // Use this if you need to preprocess and alter the data before insert or
    // update operation.
    ////////////////////////////////////////////////////////////////////////////
    public function _before_insert_or_update($post_array, $primary_key=NULL){
        return $post_array;
    }

    ////////////////////////////////////////////////////////////////////////////
    // Return true if edit button is shown for this record,
    // return false otherwise
    ////////////////////////////////////////////////////////////////////////////
    public function _show_edit($primary_key){
        return TRUE;
    }

    ////////////////////////////////////////////////////////////////////////////
    // Return true if delete button is shown for this record,
    // return false otherwise
    ////////////////////////////////////////////////////////////////////////////
    public function _show_delete($primary_key){
        return TRUE;
    }

    ////////////////////////////////////////////////////////////////////////////
    // Return true if edit operation is allowed for this record,
    // return false otherwise
    ////////////////////////////////////////////////////////////////////////////
    public function _allow_edit($primary_key){
        return TRUE;
    }

    ////////////////////////////////////////////////////////////////////////////
    // Return true if delete operation is allowed for this record,
    // return false otherwise
    ////////////////////////////////////////////////////////////////////////////
    public function _allow_delete($primary_key){
        return TRUE;
    }

    ////////////////////////////////////////////////////////////////////////////
    // Before insert, return new $post_array or FALSE if you want to
    // cancel the operation. Use this if you need to preprocess and alter
    // the data before insert operation.
    ////////////////////////////////////////////////////////////////////////////
    public function _before_insert($post_array){
        return $post_array;
    }

    ////////////////////////////////////////////////////////////////////////////
    // After insert, return TRUE if success
    // Use this if you want to do something after insert.
    ////////////////////////////////////////////////////////////////////////////
    public function _after_insert($post_array, $primary_key){
        return TRUE;
    }

    ////////////////////////////////////////////////////////////////////////////
    // Before update, return new $post_array or FALSE if you want to
    // cancel the operation. Use this if you need to preprocess and alter
    // the data before update operation.
    ////////////////////////////////////////////////////////////////////////////
    public function _before_update($post_array, $primary_key){
        return $post_array;
    }

    ////////////////////////////////////////////////////////////////////////////
    // After update, return TRUE if success
    // Use this if you want to do something after update.
    ////////////////////////////////////////////////////////////////////////////
    public function _after_update($post_array, $primary_key){
        return TRUE;
    }

    ////////////////////////////////////////////////////////////////////////////
    // Before delete, return new $post_array or FALSE if you want to
    // cancel the operation. Use this if you need to preprocess and alter
    // the data before delete operation. Typically delete detail table
    ////////////////////////////////////////////////////////////////////////////
    public function _before_delete($primary_key){
{{ detail_before_delete }}
        return TRUE;
    }

    ////////////////////////////////////////////////////////////////////////////
    // After delete, return TRUE if success
    // Use this if you want to do something after delete.
    ////////////////////////////////////////////////////////////////////////////
    public function _after_delete($primary_key){
        return TRUE;
    }

}
