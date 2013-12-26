<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Manage_Subsite
 *
 * @author No-CMS Module Generator
 */
class Add_Subsite extends CMS_Priv_Strict_Controller {

    protected $URL_MAP = array();

    public function __construct(){        
        parent::__construct();
        if(CMS_SUBSITE != ''){
            redirect(($module_path == 'multisite'? $module_path : $module_path.'/multisite'));
        }
        $this->load->model('installer/install_model');
        $this->install_model = new Install_Model();
    }

    protected function get_input(){
        // get these from old setting
        $this->install_model->db_table_prefix              = cms_table_prefix();

        $this->install_model->subsite                      = (string)$this->input->post('subsite');
        $this->install_model->admin_email                  = (string)$this->input->post('admin_email');
        $this->install_model->admin_real_name              = (string)$this->input->post('admin_real_name');
        $this->install_model->admin_user_name              = (string)$this->input->post('admin_user_name');
        $this->install_model->admin_password               = (string)$this->input->post('admin_password');
        $this->install_model->admin_confirm_password       = (string)$this->input->post('admin_confirm_password');
        $this->install_model->hide_index                   = $this->input->post('hide_index')=='true';
        $this->install_model->gzip_compression             = $this->input->post('gzip_compression')=='true';
        $this->install_model->site_domain                  = $this->input->post('site_domain');
        $this->install_model->auth_enable_facebook         = $this->input->post('auth_enable_facebook')=='true';
        $this->install_model->auth_facebook_app_id         = (string)$this->input->post('auth_facebook_app_id');
        $this->install_model->auth_facebook_app_secret     = $this->input->post('auth_facebook_app_secret');
        $this->install_model->auth_enable_twitter          = $this->input->post('auth_enable_twitter')=='true';
        $this->install_model->auth_twitter_app_key         = (string)$this->input->post('auth_twitter_app_key');
        $this->install_model->auth_twitter_app_secret      = (string)$this->input->post('auth_twitter_app_secret');
        $this->install_model->auth_enable_google           = $this->input->post('auth_enable_google')=='true';
        $this->install_model->auth_google_app_id           = (string)$this->input->post('auth_google_app_id');
        $this->install_model->auth_google_app_secret       = $this->input->post('auth_google_app_secret');
        $this->install_model->auth_enable_yahoo            = $this->input->post('auth_enable_yahoo')=='true';
        $this->install_model->auth_yahoo_app_id            = (string)$this->input->post('auth_yahoo_app_id');
        $this->install_model->auth_yahoo_app_secret        = (string)$this->input->post('auth_yahoo_app_secret');
        $this->install_model->auth_enable_linkedin         = $this->input->post('auth_enable_linkedin')=='true';
        $this->install_model->auth_linkedin_app_key        = (string)$this->input->post('auth_linkedin_app_key');
        $this->install_model->auth_linkedin_app_secret     = $this->input->post('auth_linkedin_app_secret');
        $this->install_model->auth_enable_myspace          = $this->input->post('auth_enable_myspace')=='true';
        $this->install_model->auth_myspace_app_key         = (string)$this->input->post('auth_myspace_app_key');
        $this->install_model->auth_myspace_app_secret      = (string)$this->input->post('auth_myspace_app_secret');
        $this->install_model->auth_enable_foursquare       = $this->input->post('auth_enable_foursquare')=='true';
        $this->install_model->auth_foursquare_app_id       = (string)$this->input->post('auth_foursquare_app_id');
        $this->install_model->auth_foursquare_app_secret   = (string)$this->input->post('auth_foursquare_app_secret');
        $this->install_model->auth_enable_windows_live     = $this->input->post('auth_enable_windows_live')=='true';
        $this->install_model->auth_windows_live_app_id     = (string)$this->input->post('auth_windows_live_app_id');
        $this->install_model->auth_windows_live_app_secret = (string)$this->input->post('auth_windows_live_app_secret');
        $this->install_model->auth_enable_open_id          = $this->input->post('auth_enable_open_id')=='true';
        $this->install_model->auth_enable_aol              = $this->input->post('auth_enable_aol')=='true';
    }

    public function index(){        
        $data = NULL;
        $this->view($this->cms_module_path().'/add_subsite_index', $data, $this->cms_complete_navigation_name('add_subsite'));
    }

    protected function check_installation(){
        $check_installation = $this->install_model->check_installation();
        if($this->install_model->subsite == ''){
            $check_installation['success'] = FALSE;
            $check_installation['error_list'][] = 'Subsite cannot be empty';
        }
        return $check_installation;
    }

    public function check(){
        $this->get_input();
        $check_installation = $this->check_installation();
        $this->cms_show_json($check_installation);
    }

    public function install(){
        $this->get_input();
        $check_installation = $this->check_installation();
        $success = $check_installation['success'];
        if($success){
            $this->install_model->build_database();
            $this->install_model->build_configuration();
        }
        $data = $check_installation;
        $this->view($this->cms_module_path().'/add_subsite_install', $data, $this->cms_complete_navigation_name('add_subsite'));

    }

}