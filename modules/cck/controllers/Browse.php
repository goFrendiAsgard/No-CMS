<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Browse extends CMS_Secure_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model($this->cms_module_path().'/cck_model');
    }

    public function index($id_entity=NULL){
        if(!is_numeric($id_entity)){
            redirect('main/index');
        }
        $entity = $this->cms_get_record($this->t('entity'), 'id', $id_entity);
        $browse_navigation_name = $this->n('entity_'.$id_entity.'_browse');
        $manage_navigation_name = $this->n('entity_'.$id_entity.'_manage');
        $add_navigation_name = $this->n('entity_'.$id_entity.'_add');
        $add_privilege_name = $this->n('add_data_'.$id_entity);
        $edit_privilege_name = $this->n('edit_data_'.$id_entity);
        $delete_privilege_name = $this->n('delete_data_'.$id_entity);
        // define data
        $module_path = $this->cms_module_path();
        $data = array(
            'allow_navigate_backend'    => $this->cms_allow_navigate($manage_navigation_name),
            'have_add_privilege'        => $this->cms_have_privilege($add_privilege_name),
            'have_edit_privilege'       => $this->cms_have_privilege($edit_privilege_name),
            'have_delete_privilege'     => $this->cms_have_privilege($delete_privilege_name),
            'backend_url'               => site_url($module_path.'/manage/index/'.$id_entity),
            'module_path'               => $module_path,
            'first_data'                => Modules::run($module_path.'/browse/get_data', $id_entity, 0, ''),
            'id_entity'                 => $id_entity,
            'entity_name'               => $entity->name,
        );
        $this->view($this->cms_module_path().'/Browse_view', $data, $browse_navigation_name);
    }

    public function get_data($id_entity = NULL, $page = 0, $keyword = ''){
        if(!is_numeric($id_entity)){
            redirect('main/index');
        }
        // get entity and field list
        $entity = $this->cms_get_record($this->t('entity'), 'id', $id_entity);
        $field_list = $this->cms_get_record_list($this->t('field'), 'id_entity', $id_entity);
        // get navigation and privilege names
        $browse_navigation_name = $this->n('entity_'.$id_entity.'_browse');
        $manage_navigation_name = $this->n('entity_'.$id_entity.'_manage');
        $add_navigation_name = $this->n('entity_'.$id_entity.'_add');
        $add_privilege_name = $this->n('add_data_'.$id_entity);
        $edit_privilege_name = $this->n('edit_data_'.$id_entity);
        $delete_privilege_name = $this->n('delete_data_'.$id_entity);
        $module_path = $this->cms_module_path();
        // get page and keyword parameter
        $post_keyword   = $this->input->post('keyword');
        $post_page      = $this->input->post('page');
        if($keyword == '' && $post_keyword != NULL) $keyword = $post_keyword;
        if($page == 0 && $post_page != NULL) $page = $post_page;
        // get the record list
        $limit = 10;
        foreach($field_list as $field){
            $this->db->or_like('field_'.$field->id, $keyword);
        }
        $query = $this->db->select('*')
            ->from($this->t('data_'.$id_entity))
            ->limit($limit, $page*$limit)
            ->get();
        $record_list = $query->result();
        // assembly the result
        $result = array();
        foreach($record_list as $record){
            $result[] = (object)array(
                'id' => $record->id,
                'content' => $this->cck_model->get_actual_per_record_view($id_entity, $record)
            );
        }
        // show it :)
        $data = array(
            'result'                    => $result,
            'allow_navigate_backend'    => $this->cms_allow_navigate($manage_navigation_name),
            'have_add_privilege'        => $this->cms_have_privilege($add_privilege_name),
            'have_edit_privilege'       => $this->cms_have_privilege($edit_privilege_name),
            'have_delete_privilege'     => $this->cms_have_privilege($delete_privilege_name),
            'backend_url'               => site_url($module_path.'/manage/index/'.$id_entity),
            'id_entity'                 => $id_entity,
            'entity_name'               => $entity->name,
        );
        $config = array('only_content'=>TRUE);
        $this->view($module_path.'/Browse_partial_view',$data,
           $browse_navigation_name, $config);
    }
}
