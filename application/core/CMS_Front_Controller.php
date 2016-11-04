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

    protected $SEARCH_POST_KEYS             = array('keyword');
    protected $SEARCH_POST_DEFAULT_VALUES   = array(
        'keyword' => '',
    );

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

    // The first parameter of this function is page, followed by search parameters
    protected function _get_data(){
        $module_path = $this->cms_module_path();
        $arg_list = func_get_args();
        if(count($arg_list) == 0){
            $arg_list[] = 0;
        }
        $new_arg_list = array(); // when call 'get_data' from model, page should be the last parameter
        // first argument is $page
        $post_page = $this->input->post('page');
        if($post_page != NULL){
            $page = $post_page;
        }else{
            $page = count($arg_list)>0? $arg_list[0]:0;
        }
        // other arguments are search parameters
        for($i=0; $i<count($this->SEARCH_POST_KEYS); $i++){
            $key = $this->SEARCH_POST_KEYS[$i];
            $arg_index = $i+1;
            // default value
            $default_value = array_key_exists($key, $this->SEARCH_POST_DEFAULT_VALUES)?
                $this->SEARCH_POST_DEFAULT_VALUES[$key]: NULL;
            if(count($arg_list)<$arg_index+1){
                $arg_list[] = $default_value;
            }
            // post value
            $post_value = $this->input->post($key);
            if($post_value != NULL){
                $arg_list[$arg_index] = $post_value;
            }
            // add to new_arg_list
            $new_arg_list[] = $arg_list[$arg_index];
        }
        $new_arg_list[] = $page;


        // get data from model
        $this->load->model($module_path.'/'. $this->MODEL_PATH);
        $result = call_user_func_array(array($this->{$this->MODEL_PATH}, 'get_data'), $new_arg_list);
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
    // The first parameter of this function is $page, followed by search parameters
    public function get_data(){
        $module_path = $this->cms_module_path();
        $arg_list = func_get_args();
        $data = call_user_func_array(array($this, '_get_data'), $arg_list);
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
            $asset = new CMS_Asset();
            $asset->add_cms_js("nocms/js/jquery-ace/ace/ace.js");
            $asset->add_cms_js("nocms/js/jquery-ace/ace/theme-eclipse.js");
            $asset->add_cms_js("nocms/js/jquery-ace/ace/mode-html.js");
            $asset->add_cms_js("nocms/js/jquery-ace/ace/mode-javascript.js");
            $asset->add_cms_js("nocms/js/jquery-ace/ace/mode-css.js");
            $asset->add_cms_js("nocms/js/jquery-ace/jquery-ace.min.js");
            $ace_js = $asset->compile_js();
            $data = array(
                'record_template'         => $this->cms_get_config($this->CONFIG_TEMPLATE_NAME, TRUE),
                'default_record_template' => $this->cms_get_module_config($this->CONFIG_TEMPLATE_NAME),
                'ace_js'                  => $ace_js,
            );
            $this->view($module_path.'/'. $this->CONFIG_VIEW_PATH,$data,
                $this->n($this->NAVIGATION_NAME));
        }
    }
}
