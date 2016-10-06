<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Browse_city
 *
 * @author No-CMS Module Generator
 */

class Browse_city extends CMS_Secure_Controller {
    protected $CONTROLLER_PATH = 'browse_city';
    protected $NAVIGATION_NAME = 'browse_city';
    protected $BACK_CONTROLLER_PATH = 'manage_city';
    protected $BACK_NAVIGATION_NAME = 'manage_city';

    protected $ADD_PRIVILEGE_NAME = 'add_city';
    protected $EDIT_PRIVILEGE_NAME = 'edit_city';
    protected $DELETE_PRIVILEGE_NAME = 'delete_city';
    protected $EDIT_TEMPLATE_PRIVILEGE_NAME = 'edit_city_record_template';

    protected $CONFIG_TEMPLATE_NAME = 'example_city_record_template';

    protected $VIEW_PATH = 'Browse_city_view';
    protected $PARTIAL_VIEW_PATH = 'Browse_city_partial_view';
    protected $CONFIG_VIEW_PATH = 'Browse_city_template_config_view';

    protected $MODEL_PATH = 'city_model';

    public function index(){
        $module_path = $this->cms_module_path();
        $data = $this->_index();
        $this->view($module_path.'/'.$this->VIEW_PATH, $data,
            $this->n($this->NAVIGATION_NAME));
    }

    public function get_data($page = 0, $keyword = ''){
        $module_path = $this->cms_module_path();
        $data = $this->_get_data($page, $keyword);
        $config = array('only_content'=>TRUE);
        $this->view($module_path.'/'.$this->PARTIAL_VIEW_PATH, $data,
            $this->n($this->NAVIGATION_NAME), $config);
    }
}
