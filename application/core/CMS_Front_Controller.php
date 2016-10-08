<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CMS_Front_Controller extends CMS_Secure_Controller {

    protected $CONTROLLER_PATH;         // name of this controller (e.g: "browse_city")
    protected $NAVIGATION_NAME;         // navigation name of this controller (e.g: "browse_city")
    protected $BACK_CONTROLLER_PATH;    // name of back controller
    protected $BACK_NAVIGATION_NAME;    // navigation name of back controller

    protected $ADD_PRIVILEGE_NAME;      // privilege name of add action in back_controller
    protected $EDIT_PRIVILEGE_NAME;     // privilege name of edit action in back controller
    protected $DELETE_PRIVILEGE_NAME;   // privilege name of delete action in back_controller
    protected $EDIT_TEMPLATE_PRIVILEGE_NAME; // privilege name to edit record template

    protected $CONFIG_TEMPLATE_NAME; // configuration name of record template

    protected $VIEW_PATH;            // view path of controller_path/index (e.g: Browse_city_view)
    protected $PARTIAL_VIEW_PATH;    // view path of controller_path/get_data
    protected $CONFIG_VIEW_PATH;     // view path of template config

    protected $MODEL_PATH;           // model path

    protected function do_override_url_map($URL_MAP){
        $module_path = $this->cms_module_path();
        $navigation_name = $this->n($this->NAVIGATION_NAME);
        $URL_MAP[$module_path.'/'.$this->CONTROLLER_PATH] = $navigation_name;
        $URL_MAP[$module_path] = $navigation_name;
        $URL_MAP[$module_path.'/'.$this->CONTROLLER_PATH.'/get_data'] = $navigation_name;
        $URL_MAP[$module_path.'/get_data'] = $navigation_name;
        $URL_MAP[$module_path.'/'.$this->CONTROLLER_PATH.'/config'] = $navigation_name;
        $URL_MAP[$module_path.'/config'] = $navigation_name;
        return $URL_MAP;
    }

    // Used for index
    protected function _index(){
        $module_path = $this->cms_module_path();
        $data = array(
            'allow_navigate_backend'       => $this->cms_allow_navigate($this->n($this->BACK_NAVIGATION_NAME)),
            'have_add_privilege'           => $this->cms_have_privilege($this->n($this->ADD_PRIVILEGE_NAME)),
            'have_edit_privilege'          => $this->cms_have_privilege($this->n($this->EDIT_PRIVILEGE_NAME)),
            'have_delete_privilege'        => $this->cms_have_privilege($this->n($this->DELETE_PRIVILEGE_NAME)),
            'have_edit_template_privilege' => $this->cms_have_privilege($this->n($this->EDIT_TEMPLATE_PRIVILEGE_NAME)),
            'backend_url'                  => site_url($module_path.'/'.$this->BACK_CONTROLLER_PATH.'/index'),
            'module_path'                  => $module_path,
            'first_data'                   => Modules::run($module_path.'/'.$this->CONTROLLER_PATH.'/get_data', 0, ''),
        );
        return $data;
    }
    public function index(){
        $data = $this->_index();
        $this->view($module_path.'/'.$this->VIEW_PATH, $data,
            $this->n($this->NAVIGATION_NAME));
    }

    protected function _get_data($page = 0, $keyword = ''){
        $module_path = $this->cms_module_path();
        // get page and keyword parameter
        $post_keyword   = $this->input->post('keyword');
        $post_page      = $this->input->post('page');
        if($keyword == '' && $post_keyword != NULL) $keyword = $post_keyword;
        if($page == 0 && $post_page != NULL) $page = $post_page;
        // get data from model
        $this->load->model($module_path.'/'. $this->MODEL_PATH);
        $result = $this->{$this->MODEL_PATH}->get_data($keyword, $page);
        $data = array(
            'result'                       => $result,
            'allow_navigate_backend'       => $this->cms_allow_navigate($this->n($this->BACK_NAVIGATION_NAME)),
            'have_add_privilege'           => $this->cms_have_privilege($this->n($this->ADD_PRIVILEGE_NAME)),
            'have_edit_privilege'          => $this->cms_have_privilege($this->n($this->EDIT_PRIVILEGE_NAME)),
            'have_delete_privilege'        => $this->cms_have_privilege($this->n($this->DELETE_PRIVILEGE_NAME)),
            'have_edit_template_privilege' => $this->cms_have_privilege($this->n($this->EDIT_TEMPLATE_PRIVILEGE_NAME)),
            'backend_url'                  => site_url($module_path.'/'.$this->BACK_CONTROLLER_PATH.'/index'),

            'record_template'              => $this->cms_get_config($this->CONFIG_TEMPLATE_NAME, TRUE),
            'default_record_template'      => $this->cms_get_module_config($this->CONFIG_TEMPLATE_NAME),
        );
        return $data;
    }
    public function get_data($page = 0, $keyword = ''){
        $data = $this->_get_data($page, $keyword);
        $config = array('only_content'=>TRUE);
        $this->view($module_path.'/'.$this->PARTIAL_VIEW_PATH, $data,
            $this->n($this->NAVIGATION_NAME), $config);
    }

    public function template_config(){
        $module_path = $this->cms_module_path();
        // redirect if doesn't have privilege
        if(!$this->cms_have_privilege($this->n($this->EDIT_TEMPLATE_PRIVILEGE_NAME))){
            redirect($module_path . '/'. $this->CONTROLLER_PATH);
        }
        // save changes
        if($this->input->post('template') !== NULL){
            $this->cms_set_config($this->CONFIG_TEMPLATE_NAME, $this->input->post('template'));
            redirect($module_path . '/'. $this->CONTROLLER_PATH);
        }else{
            $this->load->library('cms_asset');
            $data = array(
                'record_template'         => $this->cms_get_config($this->CONFIG_TEMPLATE_NAME, TRUE),
                'default_record_template' => $this->cms_get_module_config($this->CONFIG_TEMPLATE_NAME),
            );
            $this->view($module_path.'/'. $this->CONFIG_VIEW_PATH,$data,
                $this->n($this->NAVIGATION_NAME));
        }
    }
}
