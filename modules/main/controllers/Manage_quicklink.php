<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Manage_quicklink
 *
 * @author No-CMS Module Generator
 */
class Manage_quicklink extends CMS_CRUD_Controller {

    protected $URL_MAP = array();
    protected $TABLE_NAME = 'main_quicklink';
    protected $COLUMN_NAMES = array('navigation_id', 'index');
    protected $PRIMARY_KEY = 'quicklink_id';
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
        $crud->order_by('index', 'asc');

        ////////////////////////////////////////////////////////////////////////
        // HINT: You can access this variables after calling parent's make_crud method:
        //      $this->CRUD
        //      $this->STATE
        //      $this->STATE_INFO
        //      $this->PK_VALUE
        ////////////////////////////////////////////////////////////////////////

        // set subject
        $crud->set_subject('Quicklink');

        // displayed columns on list, edit, and add, uncomment to use
        $crud->columns('navigation_id');
        //$crud->edit_fields('navigation_id', 'index', '_updated_by', '_updated_at');
        //$crud->add_fields('navigation_id', 'index', '_created_by', '_created_at');
        //$crud->set_read_fields('navigation_id', 'index');

        // caption of each columns
        $crud->display_as('navigation_id','Navigation');
        $crud->display_as('index','Index');

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
        $crud->required_fields('navigation_id');

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put required field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/unique_fields)
        // eg:
        //      $crud->unique_fields( $field1, $field2, $field3, ... );
        ////////////////////////////////////////////////////////////////////////
        $crud->unique_fields('navigation_id');

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
        $crud->set_relation('navigation_id', $this->t('main_navigation'), 'navigation_name');

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
        $crud->field_type('index', 'hidden');



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
        $this->view($this->cms_module_path().'/Manage_quicklink_view', $output,
            $this->n('main_quicklink_management'), $config);
    }

    public function _callback_column_navigation_id($value, $row)
    {
        $html = '<a name="'.$row->quicklink_id.'"></a>';
        $html .= '<span>'.$value.'</span>';
        $html .= '<input type="hidden" class="quicklink_id" value="'.$row->quicklink_id.'" />';

        if (isset($_SESSION['__mark_move_quicklink_id'])) {
            $mark_move_quicklink_id = $_SESSION['__mark_move_quicklink_id'];
            if ($row->quicklink_id == $mark_move_quicklink_id) {
                // cancel link
                $html .= ' | <a href="'.site_url($this->cms_module_path().'/manage_quicklink/quicklink_move_cancel').'"><i class="glyphicon glyphicon-repeat"></i> Undo</a>';
            } else {
                // paste before, paste after, paste inside
                $html .= '<br /><a href="'.site_url($this->cms_module_path().'/manage_quicklink/quicklink_move_before/'.$row->quicklink_id).'"><i class="glyphicon glyphicon-open"></i> Put Before</a>';
                $html .= ' | <a href="'.site_url($this->cms_module_path().'/manage_quicklink/quicklink_move_after/'.$row->quicklink_id).'"><i class="glyphicon glyphicon-save"></i> Put After</a>';
            }
        } else {
            $html .= ' | <a href="'.site_url($this->cms_module_path().'/manage_quicklink/quicklink_mark_move/'.$row->quicklink_id).'"><i class="glyphicon glyphicon-share-alt"></i> Move</a>';
        }

        return $html;
    }

    public function quicklink_mark_move($quicklink_id)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['__mark_move_quicklink_id'] = $quicklink_id;
        redirect($this->cms_module_path().'/manage_quicklink/index#'.$quicklink_id, 'refresh');
    }

    public function quicklink_move_cancel()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $quicklink_id = $_SESSION['__mark_move_quicklink_id'];
        unset($_SESSION['__mark_move_quicklink_id']);
        redirect($this->cms_module_path().'/manage_quicklink/index#'.$quicklink_id, 'refresh');
    }

    public function quicklink_move_before($dst_quicklink_id)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $src_quicklink_id = $_SESSION['__mark_move_quicklink_id'];
        $this->cms_do_move_quicklink_before($src_quicklink_id, $dst_quicklink_id);
        unset($_SESSION['__mark_move_quicklink_id']);
        redirect($this->cms_module_path().'/manage_quicklink/index#'.$src_quicklink_id, 'refresh');
    }
    public function quicklink_move_after($dst_quicklink_id)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $src_quicklink_id = $_SESSION['__mark_move_quicklink_id'];
        $this->cms_do_move_quicklink_after($src_quicklink_id, $dst_quicklink_id);
        unset($_SESSION['__mark_move_quicklink_id']);
        redirect($this->cms_module_path().'/manage_quicklink/index#'.$src_quicklink_id, 'refresh');
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
        $query = $this->db->select_max('index')
            ->from(cms_table_name('main_quicklink'))
            ->get();
        $row = $query->row();
        $index = $row->index;
        if (!isset($index)) {
            $index = 1;
        } else {
            $index = $index + 1;
        }

        $post_array['index'] = $index;

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
