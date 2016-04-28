<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Installer extends MX_Controller{

    public function __construct(){
        parent::__construct();
        if(ENVIRONMENT != 'first-time'){
            show_404();
        }
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->helper('form');
        $this->load->model('install_model');
    }

    protected function get_input(){
        $this->install_model->db_protocol                  = (string)$this->input->post('db_protocol');
        $this->install_model->db_host                      = (string)$this->input->post('db_host');
        $this->install_model->db_name                      = (string)$this->input->post('db_name');
        $this->install_model->db_port                      = (string)$this->input->post('db_port');
        $this->install_model->db_username                  = (string)$this->input->post('db_username');
        $this->install_model->db_password                  = (string)$this->input->post('db_password');
        $this->install_model->db_table_prefix              = (string)$this->input->post('db_table_prefix');
        $this->install_model->admin_email                  = (string)$this->input->post('admin_email');
        $this->install_model->admin_real_name              = (string)$this->input->post('admin_real_name');
        $this->install_model->admin_user_name              = (string)$this->input->post('admin_user_name');
        $this->install_model->admin_password               = (string)$this->input->post('admin_password');
        $this->install_model->admin_confirm_password       = (string)$this->input->post('admin_confirm_password');
        $this->install_model->hide_index                   = $this->input->post('hide_index')=='true';
        $this->install_model->gzip_compression             = $this->input->post('gzip_compression')=='true';
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

    public function check(){
        $this->get_input();
        echo json_encode($this->install_model->check_installation());
    }

    public function index(){
        $extensions = get_loaded_extensions();
        $data = array(
            'php_version'           => phpversion(),
            'mysql_installed'       => in_array('mysql', $extensions),
            'mysqli_installed'      => in_array('mysqli', $extensions),
            'pdo_mysql_installed'   => in_array('pdo_mysql', $extensions),
            'pdo_pgsql_installed'   => in_array('pdo_pgsql', $extensions),
            'pdo_sqlite_installed'  => in_array('pdo_sqlite', $extensions)
        );
        $this->load->view('installer/installer_index', $data);
    }

    public function install(){
        $this->get_input();
        $check_installation = $this->install_model->check_installation();
        $success = $check_installation['success'];
        $module_installed = FALSE;
        if($success){
            log_message('debug', 'Start installing main website');
            $this->install_model->build_configuration();
            log_message('debug', 'Configuration built for main website');
            $this->install_model->build_database();
            log_message('debug', 'Database built for main website');
            $module_installed = $this->install_model->install_modules();
            log_message('debug', 'Modules Installed for main website');
        }
        $data['module_installed'] = $module_installed;
        $data['success'] = $success;
        $data['admin_user_name'] = $this->install_model->admin_user_name;
        $data['admin_password'] = $this->install_model->admin_password;
        $this->load->view('installer/installer_install', $data);
    }

}
