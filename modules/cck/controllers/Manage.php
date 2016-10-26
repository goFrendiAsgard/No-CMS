<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Manage_entity
 *
 * @author No-CMS Module Generator
 */
class Manage extends CMS_CRUD_Controller {

    protected $URL_MAP = array();
    protected $TABLE_NAME = '';
    protected $COLUMN_NAMES = array();
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
    protected $AUTOMATICALLY_USE_PRIVILEGES = FALSE;

    public function __construct(){
        parent::__construct();
        $this->load->model($this->cms_module_path().'/cck_model');
    }

    protected function is_group_id_in_table($group_id_list, $id_entity, $table_name){
        $record_list = $this->cms_get_record_list($table_name, 'id_entity', $id_entity);
        foreach($record_list as $record){
            if(in_array($record->id_group, $group_id_list)){
                return TRUE;
            }
        }
        return FALSE;
    }

    public function __call($method, $args){
        if(strpos($method, '_cck_callback_column_') === 0){
            $id_field = substr($method, strlen('_cck_callback_column_'));
            array_unshift($args, $id_field);
            return call_user_func_array(array($this, '_cck_callback_column'), $args);
        }else if(strpos($method, '_cck_callback_field_') === 0){
            $id_field = substr($method, strlen('_cck_callback_field_'));
            array_unshift($args, $id_field);
            return call_user_func_array(array($this, '_cck_callback_field'), $args);
        }else{
            return parent::__call($method, $args);
        }
    }

    private function randomize_string($value){
        $time = date('Y:m:d H:i:s');
        return substr(md5($value.$time),0,6);
    }

    protected function make_crud($id_entity=NULL){
        $this->TABLE_NAME = 'data_'.$id_entity;

        // get entity and fields
        $entity = $this->cms_get_record($this->t('entity'), 'id', $id_entity);
        $this->db->order_by('order_index');
        $field_list = $this->cms_get_record_list($this->t('field'), 'id_entity', $id_entity);
        $this->ENTITY = $entity;
        $this->FIELD_LIST = $field_list;
        // unset add, edit, delete
        $group_id_list = $this->cms_user_group_id();
        if($entity->id_authorization_add>3 && !$this->cms_user_is_super_admin() && !$this->is_group_id_in_table($group_id_list, $id_entity, $this->t('group_entity_add'))){
            $this->UNSET_ADD = TRUE;
        }
        if($entity->id_authorization_edit>3 && !$this->cms_user_is_super_admin() && !$this->is_group_id_in_table($group_id_list, $id_entity, $this->t('group_entity_edit'))){
            $this->UNSET_EDIT = TRUE;
        }
        if($entity->id_authorization_delete>3 && !$this->cms_user_is_super_admin() && !$this->is_group_id_in_table($group_id_list, $id_entity, $this->t('group_entity_delete'))){
            $this->UNSET_DELETE = TRUE;
        }
        if($entity->id_authorization_view>3 && !$this->cms_user_is_super_admin() && !$this->is_group_id_in_table($group_id_list, $id_entity, $this->t('group_entity_view'))){
            $this->UNSET_LIST = TRUE;
            $this->UNSET_BACK_TO_LIST = TRUE;
            $this->UNSET_PRINT = TRUE;
            $this->UNSET_EXPORT = TRUE;
        }

        // call parent's make_crud
        $crud = parent::make_crud();

        ////////////////////////////////////////////////////////////////////////
        // HINT: You can access this variables after calling parent's make_crud method:
        //      $this->CRUD
        //      $this->STATE
        //      $this->STATE_INFO
        //      $this->PK_VALUE
        ////////////////////////////////////////////////////////////////////////

        // handle redirect etc
        if($entity->per_user_limitation == 1){
            // get max record per user and determine whether record count per user is limited or not
            $max_record_per_user = is_numeric($entity->max_record_per_user)? $entity->max_record_per_user : 0;
            $unlimited_record_per_user = $max_record_per_user <= 0;
            if(!$unlimited_record_per_user){
                // get actual table name
                $table_name = $this->t('data_'.$id_entity);
                // get record count of respective uer
                $this->db->where('_created_by', $this->cms_user_id());
                $record_count = $this->db->count_all_results($table_name);
                if($record_count >= $max_record_per_user && $this->STATE == 'add'){
                    redirect($this->cms_module_path().'/manage/index/'.$id_entity);
                }
            }
            // only able to see their own record, except super admin
            if(!$this->cms_user_is_super_admin()){
                $crud->where('_created_by', $this->cms_user_id());
            }
        }

        // set subject
        $crud->set_subject(ucwords($entity->name));

        // get fields, determine display as
        $view_field_name_list = array();
        $add_field_name_list = array('_created_by', '_created_at');
        $edit_field_name_list = array('_updated_by', '_updated_at');
        foreach($field_list as $field){
            if($field->shown_on_add){
                $add_field_name_list[] = 'field_'.$field->id;
            }
            if($field->shown_on_edit){
                $edit_field_name_list[] = 'field_'.$field->id;
            }
            if($field->shown_on_view){
                $view_field_name_list[] = 'field_'.$field->id;
            }
            // display_as
            $crud->display_as('field_'.$field->id, $field->name);
        }

        call_user_func_array(array($crud, 'columns'), $view_field_name_list);
        call_user_func_array(array($crud, 'edit_fields'), $edit_field_name_list);
        call_user_func_array(array($crud, 'add_fields'), $add_field_name_list);
        call_user_func_array(array($crud, 'set_read_fields'), $view_field_name_list);


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

        // cck callback
        foreach($field_list as $field){
            $id_field = $field->id;
            $column_name = 'field_'.$field->id;
            // callback column
            $crud->callback_column($column_name, array($this, '_cck_callback_column_'.$id_field));
            $crud->callback_column($crud->basic_db_table.'.'.$column_name,
                array($this, '_cck_callback_column_'.$id_field));
            $crud->callback_column($this->cms_unique_field_name($column_name), array($this, '_cck_callback_column_'.$id_field));
            // callback field
            $crud->callback_field($column_name, array($this, '_cck_callback_field_'.$id_field));
            $crud->callback_field($crud->basic_db_table.'.'.$column_name,
                array($this, '_cck_callback_field_'.$id_field));
            $crud->callback_field($this->cms_unique_field_name($column_name), array($this, '_cck_callback_field_'.$id_field));
        }

        $this->CRUD = $crud;
        return $crud;
    }

    public function _cck_callback_column($id_field, $value, $row){
        return $this->cck_model->get_actual_field_view($id_field, $value);
    }

    public function _cck_callback_field($id_field, $value, $primary_key){
        return $this->cck_model->get_actual_field_input($id_field, $value);
    }

    public function index($id_entity=NULL){
        // create crud
        $crud = $this->make_crud($id_entity);

        // render
        $render = $this->render_crud($crud);
        $output = $render['output'];
        $config = $render['config'];
        $output->id_entity = $id_entity;

        // determine navigation name
        if($this->STATE == 'add'){
            $navigation_name = $this->n('entity_'.$id_entity.'_add');
        }else{
            $navigation_name = $this->n('entity_'.$id_entity.'_manage');
        }

        // show the view
        $this->view($this->cms_module_path().'/Manage_view', $output,
            $navigation_name, $config);
    }

    public function _after_insert_or_update($post_array, $primary_key){
        return TRUE;
    }

    public function _before_insert_or_update($post_array, $primary_key=NULL){
        // get field list
        $field_code_list = array();
        foreach($this->FIELD_LIST as $field){
            $field_code_list[] = 'field_'.$field->id;
        }
        foreach($field_code_list as $key){
            if(array_key_exists($key, $post_array) && is_array($post_array[$key])){
                $value = implode(PHP_EOL, $post_array[$key]);
                $post_array[$key] = $value;
            }else if(array_key_exists($key, $_FILES) && $_FILES[$key]['name'] != ''){
                $tmp_name = $_FILES[$key]['tmp_name'];
                $file_name = $_FILES[$key]['name'];
                $file_name = $this->randomize_string($file_name).$file_name;
                $upload_path = FCPATH.'modules/'.$this->cms_module_path().'/assets/uploads/';
                move_uploaded_file($tmp_name, $upload_path.$file_name);
                @chmod($upload_path.$file_name, 0744);
                $post_array[$key] = $file_name;
            }
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
        return TRUE;
    }

    public function _after_delete($primary_key){
        return TRUE;
    }

}
