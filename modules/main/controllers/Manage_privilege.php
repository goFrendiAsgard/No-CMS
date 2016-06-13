<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include(FCPATH.'modules/main/core/CMS_Predefined_Callback_CRUD_Controller.php');

/**
 * Description of Manage_privilege
 *
 * @author No-CMS Module Generator
 */
class Manage_privilege extends CMS_Predefined_Callback_CRUD_Controller {

    protected $URL_MAP = array();
    protected $TABLE_NAME = 'main_privilege';
    protected $COLUMN_NAMES = array('privilege_name', 'title', 'description', 'authorization_id', 'group_privilege');
    protected $PRIMARY_KEY = 'privilege_id';
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
        $crud->set_subject('Privilege');

        // displayed columns on list, edit, and add, uncomment to use
        $crud->columns('privilege_name', 'title', 'description');
        //$crud->edit_fields('privilege_name', 'title', 'description', 'authorization_id', 'group_privilege', '_updated_by', '_updated_at');
        //$crud->add_fields('privilege_name', 'title', 'description', 'authorization_id', 'group_privilege', '_created_by', '_created_at');
        //$crud->set_read_fields('privilege_name', 'title', 'description', 'authorization_id', 'group_privilege');

        // caption of each columns
        $crud->display_as('privilege_name','Privilege Name');
        $crud->display_as('title','Title');
        $crud->display_as('description','Description');
        $crud->display_as('authorization_id','Authorization');
        $crud->display_as('group_privilege','Group Privilege');

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
        $crud->required_fields('privilege_name');

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put required field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/unique_fields)
        // eg:
        //      $crud->unique_fields( $field1, $field2, $field3, ... );
        ////////////////////////////////////////////////////////////////////////
        $crud->unique_fields('privilege_name');

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
        $crud->set_relation('authorization_id', $this->t('main_authorization'), 'authorization_name');

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put set relation_n_n (detail many to many) codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation_n_n)
        // eg:
        //      $crud->set_relation_n_n( $field_name, $relation_table, $selection_table, $primary_key_alias_to_this_table,
        //          $primary_key_alias_to_selection_table , $title_field_selection_table, $priority_field_relation );
        ////////////////////////////////////////////////////////////////////////
        $crud->set_relation_n_n('group_privilege',
            $this->t('main_group_privilege'),
            $this->t('main_group'),
            'privilege_id', 'group_id',
            'group_name', NULL);


        ////////////////////////////////////////////////////////////////////////
        // HINT: Put custom field type here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/field_type)
        // eg:
        //      $crud->field_type( $field_name , $field_type, $value  );
        ////////////////////////////////////////////////////////////////////////
        $crud->unset_texteditor('description');



        ////////////////////////////////////////////////////////////////////////
        // HINT: Put Tabs (if needed)
        // usage:
        //     $crud->set_outside_tab($how_many_field_outside_tab);
        //     $crud->set_tabs(array(
        //        'First Tab Caption'  => $how_many_field_on_first_tab,
        //        'Second Tab Caption' => $how_many_field_on_second_tab,
        //     ));
        ////////////////////////////////////////////////////////////////////////
        $crud->set_field_half_width(array('authorization_id', 'group_privilege'));

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
        $this->view($this->cms_module_path().'/Manage_privilege_view', $output,
            $this->n('main_privilege_management'), $config);
    }

    public function _callback_field_group_privilege($value, $primary_key){
        if($value === NULL){
            $value = array();
        }
        $query = $this->db->select('group_id, group_name')
            ->from(cms_table_name('main_group'))
            ->limit(20)
            ->get();
        $html = '<select id="field-group_privilege" name="group_privilege[]" multiple="multiple" size="8" class="form-control" data-placeholder="Select privileges">';
        // add old values
        foreach($value as $key=>$val){
            $html .= '<option selected value = "'.$key.'" >'.$val.'</option>';
        }
        // add other values
        foreach($query->result() as $row){
            if(!array_key_exists($row->group_id, $value)){
                $html .= '<option value = "'.$row->group_id.'" >'.$row->group_name.'</option>';
            }
        }
        $html .= '</select>';
        $html .= '<script>';
        $html .= '$("#field-group_privilege").chosen({allow_single_deselect:true, width:"100%", search_contains: true});';
        $html .= 'chosen_ajaxify("field-group_privilege", "{{ SITE_URL }}main/ajax/groups/");';
        $html .= '</script>';
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
        $this->db->delete(cms_table_name('main_group_privilege'), array('privilege_id' => $primary_key));
        return TRUE;
    }

}
