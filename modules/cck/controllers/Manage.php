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

    public function __construct(){
        parent::__construct();
        $this->load->model($this->cms_module_path().'/cck_model');
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

    protected function make_crud($id_entity=NULL){
        $this->TABLE_NAME = 'data_'.$id_entity;

        $crud = parent::make_crud();

        ////////////////////////////////////////////////////////////////////////
        // HINT: You can access this variables after calling parent's make_crud method:
        //      $this->CRUD
        //      $this->STATE
        //      $this->STATE_INFO
        //      $this->PK_VALUE
        ////////////////////////////////////////////////////////////////////////
        // get entity and fields
        $entity = $this->cms_get_record($this->t('entity'), 'id', $id_entity);
        $field_list = $this->cms_get_record_list($this->t('field'), 'id_entity', $id_entity);

        // set subject
        $crud->set_subject(ucwords($entity->name));

        // get fields, determine display as
        $view_field_name_list = array();
        $add_field_name_list = array();
        $edit_field_name_list = array();
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
