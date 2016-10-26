<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Browse_portfolio
 *
 * @author No-CMS Module Generator
 */

class Browse_portfolio extends CMS_Front_Controller {
    protected $CONTROLLER_PATH = 'browse_portfolio';
    protected $NAVIGATION_NAME = 'browse_portfolio';
    protected $BACK_CONTROLLER_PATH = 'manage_portfolio';
    protected $BACK_NAVIGATION_NAME = 'manage_portfolio';

    protected $ADD_PRIVILEGE_NAME = 'add_portfolio';
    protected $EDIT_PRIVILEGE_NAME = 'edit_portfolio';
    protected $DELETE_PRIVILEGE_NAME = 'delete_portfolio';
    protected $EDIT_TEMPLATE_PRIVILEGE_NAME = 'edit_portfolio_record_template';

    protected $CONFIG_TEMPLATE_NAME = 'portfolio_record_template';

    protected $VIEW_PATH = 'Browse_portfolio_view';
    protected $PARTIAL_VIEW_PATH = 'Browse_portfolio_partial_view';
    protected $CONFIG_VIEW_PATH = 'Browse_portfolio_template_config_view';

    protected $MODEL_PATH = 'portfolio_model';

    protected $SEARCH_POST_KEYS             = array('keyword');
    protected $SEARCH_POST_DEFAULT_VALUES   = array(
        'keyword' => '',
    );

    public function index(){
        $module_path = $this->cms_module_path();
        $data = $this->_index();
        $this->view($module_path.'/'.$this->VIEW_PATH, $data,
            $this->n($this->NAVIGATION_NAME));
    }

    public function get_data(){
        $module_path = $this->cms_module_path();
        $arg_list = func_get_args();
        $data = call_user_func_array(array($this, '_get_data'), $arg_list);
        $config = array('only_content'=>TRUE);
        $this->view($module_path.'/'.$this->PARTIAL_VIEW_PATH, $data,
            $this->n($this->NAVIGATION_NAME), $config);
    }

}
