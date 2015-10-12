<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * CMS_CRUD_Controller class.
 *
 * @author gofrendi
 */
class CMS_CRUD_Controller extends CMS_Secure_Controller
{
    protected $TABLE_NAME;
    protected $PRIMARY_KEY;
    protected $CRUD;
    protected $STATE;
    protected $STATE_INFO;
    protected $PK_VALUE;
    protected $UNSET_JQUERY = TRUE;
    protected $UNSET_READ = TRUE;
    protected $UNSET_ADD = FALSE;
    protected $UNSET_EDIT = FALSE;
    protected $UNSET_DELETE = FALSE;
    protected $UNSET_LIST = FALSE;
    protected $UNSET_BACK_TO_LIST = FALSE;
    protected $UNSET_PRINT = FALSE;
    protected $UNSET_EXPORT = FALSE;

    protected function make_crud()
    {
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // initialize groceryCRUD
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $this->CRUD = $this->new_crud();

        // check state & get PK_VALUE
        $this->STATE = $this->CRUD->getState();
        $this->STATE_INFO = $this->CRUD->getStateInfo();
        $this->PK_VALUE = isset($this->STATE_INFO->PK_VALUE)? $this->STATE_INFO->PK_VALUE : NULL;

        // unset jquery (we use No-CMS's default jquery)
        $this->CRUD->unset_jquery();

        // privilege to read
        if($this->UNSET_READ || !$this->cms_have_privilege($this->cms_complete_navigation_name('read_' . $this->TABLE_NAME))){
            $this->CRUD->unset_read();
        }

        // privilege to add
        if($this->UNSET_ADD || !$this->cms_have_privilege($this->cms_complete_navigation_name('add_' . $this->TABLE_NAME))){
            $this->CRUD->unset_add();
        }

        // privilege to edit
        if($this->UNSET_EDIT || !$this->cms_have_privilege($this->cms_complete_navigation_name('edit_' . $this->TABLE_NAME))){
            $this->CRUD->unset_edit();
        }

        // privilege to delete
        if($this->UNSET_DELETE || !$this->cms_have_privilege($this->cms_complete_navigation_name('delete_' . $this->TABLE_NAME))){
            $this->CRUD->unset_delete();
        }

        // privilege to list (uncomment if you need it)
        if($this->UNSET_LIST || !$this->cms_have_privilege($this->cms_complete_navigation_name('list_' . $this->TABLE_NAME))){
            $this->CRUD->unset_list();
        }

        // privilege to back_to_list
        if($this->UNSET_BACK_TO_LIST || !$this->cms_have_privilege($this->cms_complete_navigation_name('back_to_list_' . $this->TABLE_NAME))){
            $this->CRUD->unset_back_to_list();
        }

        // privilege to print
        if($this->UNSET_PRINT || !$this->cms_have_privilege($this->cms_complete_navigation_name('print_' . $this->TABLE_NAME))){
            $this->CRUD->unset_print();
        }

        // privilege to export
        if($this->UNSET_EXPORT || !$this->cms_have_privilege($this->cms_complete_navigation_name('export_' . $this->TABLE_NAME))){
            $this->CRUD->unset_export();
        }

        // adjust groceryCRUD's language to No-CMS's language
        $this->CRUD->set_language($this->cms_language());

        // table name
        $this->CRUD->set_table($this->cms_complete_table_name($this->TABLE_NAME));

        // primary key
        $this->CRUD->set_primary_key($this->PRIMARY_KEY);

        // callbacks
        $this->CRUD->callback_before_insert(array($this,'_before_insert'));
        $this->CRUD->callback_before_update(array($this,'_before_update'));
        $this->CRUD->callback_before_delete(array($this,'_before_delete'));
        $this->CRUD->callback_after_insert(array($this,'_after_insert'));
        $this->CRUD->callback_after_update(array($this,'_after_update'));
        $this->CRUD->callback_after_delete(array($this,'_after_delete'));

        // hidden fields
        $this->CRUD->field_type('_created_at', 'hidden');
        $this->CRUD->field_type('_created_by', 'hidden');
        $this->CRUD->field_type('_updated_by', 'hidden');
        $this->CRUD->field_type('_updated_at', 'hidden');

        return $this->CRUD;
    }

    protected function render_crud($crud = NULL){
        if($crud == NULL){
            $crud = $this->CRUD;
        }
        $output = $crud->render();

        // prepare css and js, add them to config
        $config = array();
        $asset = new Cms_asset();
        foreach($output->css_files as $file){
            $asset->add_css($file);
        }
        $config['css'] = $asset->compile_css();

        foreach($output->js_files as $file){
            $asset->add_js($file);
        }
        $config['js'] = $asset->compile_js();

        return array('output'=>$output, 'config'=>$config);
    }

    public function delete_selection(){
        $crud = $this->make_crud();
        if(!$crud->unset_delete){
            $id_list = json_decode($this->input->post('data'));
            foreach($id_list as $id){
                if($this->_before_delete($id)){
                    $this->db->delete($this->cms_complete_table_name('job'),
                        array($this->PRIMARY_KEY=>$id));
                    $this->_after_delete($id);
                }
            }
        }
    }

    public function _before_insert($post_array){
        $post_array = $this->_before_insert_or_update($post_array);
        if(array_key_exists('_created_at', $post_array)){
            $post_array['_created_at'] = date('Y-m-d H:i:s');
        }
        if(array_key_exists('_created_by', $post_array)){
            $post_array['_created_by'] = $this->cms_user_id();
        }
        return $post_array;
    }

    public function _after_insert($post_array, $PK_VALUE){
        $success = $this->_after_insert_or_update($post_array, $PK_VALUE);
        return $success;
    }

    public function _before_update($post_array, $PK_VALUE){
        $post_array = $this->_before_insert_or_update($post_array, $PK_VALUE);
        if(array_key_exists('_updated_at', $post_array)){
            $post_array['_updated_at'] = date('Y-m-d H:i:s');
        }
        if(array_key_exists('_updated_by', $post_array)){
            $post_array['_updated_by'] = $this->cms_user_id();
        }
        return $post_array;
    }

    public function _after_update($post_array, $PK_VALUE){
        $success = $this->_after_insert_or_update($post_array, $PK_VALUE);
        return $success;
    }

    public function _before_delete($PK_VALUE){
        return TRUE;
    }

    public function _after_delete($PK_VALUE){
        return TRUE;
    }

    public function _after_insert_or_update($post_array, $PK_VALUE){
        return TRUE;
    }

    public function _before_insert_or_update($post_array, $PK_VALUE=NULL){
        return $post_array;
    }

}
