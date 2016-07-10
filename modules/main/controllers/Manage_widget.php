<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include(FCPATH.'modules/main/core/CMS_Predefined_Callback_CRUD_Controller.php');

/**
 * Description of Manage_widget
 *
 * @author No-CMS Module Generator
 */
class Manage_widget extends CMS_Predefined_Callback_CRUD_Controller {

    protected $URL_MAP = array();
    protected $TABLE_NAME = 'main_widget';
    protected $COLUMN_NAMES = array('widget_name', 'title', 'description', 'active', 'index', 'is_static',
    'url', 'static_content', 'slug', 'authorization_id', 'group_widget');
    protected $PRIMARY_KEY = 'widget_id';
    protected $UNSET_JQUERY = TRUE;
    protected $UNSET_READ = TRUE;
    protected $UNSET_ADD = FALSE;
    protected $UNSET_EDIT = FALSE;
    protected $UNSET_DELETE = FALSE;
    protected $UNSET_LIST = FALSE;
    protected $UNSET_BACK_TO_LIST = FALSE;
    protected $UNSET_PRINT = FALSE;
    protected $UNSET_EXPORT = FALSE;

    protected $default_widgets = array(
        'section_custom_style',
        'section_custom_script',
        'section_top_fix',
        'section_banner',
        'section_left',
        'section_right',
        'section_bottom',
    );

    protected $default_widget_id_list = array();

    public function __construct(){
        parent::__construct();
        $widget_list = $this->cms_get_record_list(cms_table_name('main_widget'));
        foreach($widget_list as $widget){
            if(in_array($widget->widget_name, $this->default_widgets)){
                $this->default_widget_id_list[] = $widget->widget_id;
                // completed, no need to seek anymore
                if(count($this->default_widget_id_list) >= $this->default_widgets){
                    break;
                }
            }
        }
    }

    protected function make_crud(){
        $crud = parent::make_crud();
        $crud->order_by('index, slug', 'asc');

        ////////////////////////////////////////////////////////////////////////
        // HINT: You can access this variables after calling parent's make_crud method:
        //      $this->CRUD
        //      $this->STATE
        //      $this->STATE_INFO
        //      $this->PK_VALUE
        ////////////////////////////////////////////////////////////////////////

        // set subject
        $crud->set_subject('Widget');

        // displayed columns on list, edit, and add, uncomment to use
        $crud->columns('widget_name');
        //$crud->columns('widget_name', 'title', 'description', 'url', 'authorization_id', 'active', 'index', 'is_static', 'static_content', 'slug', 'group_widget');
        //$crud->edit_fields('widget_name', 'title', 'description', 'url', 'authorization_id', 'active', 'index', 'is_static', 'static_content', 'slug', 'group_widget', '_updated_by', '_updated_at');
        //$crud->add_fields('widget_name', 'title', 'description', 'url', 'authorization_id', 'active', 'index', 'is_static', 'static_content', 'slug', 'group_widget', '_created_by', '_created_at');
        //$crud->set_read_fields('widget_name', 'title', 'description', 'url', 'authorization_id', 'active', 'index', 'is_static', 'static_content', 'slug', 'group_widget');

        // caption of each columns
        $crud->display_as('widget_name','Widget Name');
        $crud->display_as('title','Title');
        $crud->display_as('description','Description');
        $crud->display_as('url','Url');
        $crud->display_as('authorization_id','Authorization');
        $crud->display_as('active','Shown when called by slug');
        $crud->display_as('index','Index');
        $crud->display_as('is_static','Is Static');
        $crud->display_as('static_content','Static Content');
        $crud->display_as('slug','Slug');
        $crud->display_as('group_widget','Group Widget');

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
        $editable = !in_array($this->PK_VALUE, $this->default_widget_id_list);
        if($editable){
            $crud->required_fields('widget_name');
        }else{
            $crud->field_type('widget_name', 'readonly');
            $crud->field_type('description', 'readonly');
        }

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put required field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/unique_fields)
        // eg:
        //      $crud->unique_fields( $field1, $field2, $field3, ... );
        ////////////////////////////////////////////////////////////////////////
        $crud->unique_fields('widget_name');

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
        $crud->set_relation_n_n('group_widget',
            $this->t('main_group_widget'),
            $this->t('main_group'),
            'widget_id', 'group_id',
            'group_name', NULL);

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put custom field type here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/field_type)
        // eg:
        //      $crud->field_type( $field_name , $field_type, $value  );
        ////////////////////////////////////////////////////////////////////////
        $crud->field_type('active', 'true_false');
        $crud->field_type('is_static', 'true_false');
        $crud->field_type('index', 'hidden');
        $crud->unset_texteditor('static_content');
        $crud->unset_texteditor('description');

        $crud->set_field_half_width(array('active', 'is_static', 'authorization_id', 'group_widget'));

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
        $this->view($this->cms_module_path().'/Manage_widget_view', $output,
            $this->n('main_widget_management'), $config);
    }

    public function toggle_widget_active($widget_id)
    {
        if ($this->input->is_ajax_request()) {
            $this->db->select('active')->from(cms_table_name('main_widget'))->where('widget_id', $widget_id);
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $new_value = ($row->active == 0) ? 1 : 0;
                $this->db->update(cms_table_name('main_widget'), array(
                    'active' => $new_value,
                ), array(
                    'widget_id' => $widget_id,
                ));
                $this->cms_show_json(array(
                    'success' => true,
                ));
            } else {
                $this->cms_show_json(array(
                    'success' => false,
                ));
            }
        }
    }

    public function widget_mark_move($widget_id)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['__mark_move_widget_id'] = $widget_id;
        redirect($this->cms_module_path().'/manage_widget/index#'.$widget_id, 'refresh');
    }

    public function widget_move_cancel()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $widget_id = $_SESSION['__mark_move_widget_id'];
        unset($_SESSION['__mark_move_widget_id']);
        redirect($this->cms_module_path().'/manage_widget/index#'.$widget_id, 'refresh');
    }

    public function widget_move_before($dst_widget_id)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $src_widget_id = $_SESSION['__mark_move_widget_id'];
        $this->cms_do_move_widget_before($src_widget_id, $dst_widget_id);
        unset($_SESSION['__mark_move_widget_id']);
        redirect($this->cms_module_path().'/manage_widget/index#'.$src_widget_id, 'refresh');
    }
    public function widget_move_after($dst_widget_id)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $src_widget_id = $_SESSION['__mark_move_widget_id'];
        $this->cms_do_move_widget_after($src_widget_id, $dst_widget_id);
        unset($_SESSION['__mark_move_widget_id']);
        redirect($this->cms_module_path().'/manage_widget/index#'.$src_widget_id, 'refresh');
    }

    public function _callback_column_widget_name($value, $row)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $html = '<a name="'.$row->widget_id.'"></a>';
        $html .= '<span>'.$value.' ('.$row->title.')</span>';
        $html .= '<input type="hidden" class="widget_id" value="'.$row->widget_id.'" /><br />';
        if (isset($row->slug) && $row->slug != null && $row->slug != '') {
            $html .= '<span style="font-size:smaller;">'.$row->slug.'</span><br />';
        }
        // active or not
        $target = site_url($this->cms_module_path().'/manage_widget/toggle_widget_active/'.$row->widget_id);
        if ($row->active == 0) {
            $html .= '<a href="#" target="'.$target.'" class="widget_active"><i class="glyphicon glyphicon-eye-open"></i> <span>Inactive</span></a>';
        } else {
            $html .= '<a href="#" target="'.$target.'" class="widget_active"><i class="glyphicon glyphicon-eye-open"></i> <span>Active</span></a>';
        }

        if (isset($_SESSION['__mark_move_widget_id'])) {
            $mark_move_widget_id = $_SESSION['__mark_move_widget_id'];
            if ($row->widget_id == $mark_move_widget_id) {
                // cancel link
                $html .= ' | <a href="'.site_url($this->cms_module_path().'/manage_widget/widget_move_cancel').'"><i class="glyphicon glyphicon-repeat"></i> Undo</a>';
            } else {
                // paste before, paste after, paste inside
                $html .= ' | <a href="'.site_url($this->cms_module_path().'/manage_widget/widget_move_before/'.$row->widget_id).'"><i class="glyphicon glyphicon-open"></i> Put Before</a>';
                $html .= ' | <a href="'.site_url($this->cms_module_path().'/manage_widget/widget_move_after/'.$row->widget_id).'"><i class="glyphicon glyphicon-save"></i> Put After</a>';
            }
        } else {
            $html .= ' | <a href="'.site_url($this->cms_module_path().'/manage_widget/widget_mark_move/'.$row->widget_id).'"><i class="glyphicon glyphicon-share-alt"></i> Move</a>';
        }

        return $html;
    }

    public function _callback_field_group_widget($value, $primary_key){
        if($value === NULL){
            $value = array();
        }
        $query = $this->db->select('group_id, group_name')
            ->from(cms_table_name('main_group'))
            ->limit(20)
            ->get();
        $html = '<select id="field-group_widget" name="group_widget[]" multiple="multiple" size="8" class="form-control" data-placeholder="Select widgets">';
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
        $html .= '$("#field-group_widget").chosen({allow_single_deselect:true, width:"100%", search_contains: true});';
        $html .= 'chosen_ajaxify("field-group_widget", "{{ SITE_URL }}main/ajax/groups/");';
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
        // default widgets cannot be deleted
        return !in_array($primary_key, $this->default_widget_id_list);
    }

    public function _allow_edit($primary_key){
        return TRUE;
    }

    public function _allow_delete($primary_key){
        return $this->_show_delete($primary_key);
    }

    public function _before_insert($post_array){
        $query = $this->db->select_max('index')
            ->from(cms_table_name('main_widget'))
            ->get();
        $row = $query->row();
        $index = $row->index;
        if (!isset($index)) {
            $index = 1;
        } else {
            $index = $index + 1;
        }

        $post_array['index'] = $index;

        if (!isset($post_array['authorization_id']) || $post_array['authorization_id'] == '') {
            $post_array['authorization_id'] = 1;
        }

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
