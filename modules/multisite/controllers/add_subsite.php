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
            redirect(($module_path == 'multisite'? $module_path : $module_path.'/multisite'),'refresh');
        }
        $this->load->model('installer/install_model');
        $this->install_model = new Install_Model();
    }

    protected function get_input(){
        // get these from old setting
        $this->install_model->db_table_prefix              = cms_table_prefix();
        $this->install_model->is_subsite                   = TRUE;
        $this->install_model->subsite                      = strtolower(str_replace(' ', '', (string)$this->input->post('subsite')));
        $this->install_model->subsite_aliases              = (string)$this->input->post('aliases');
        $this->install_model->set_subsite();

        $this->install_model->admin_email                  = (string)$this->input->post('admin_email');
        $this->install_model->admin_real_name              = (string)$this->input->post('admin_real_name');
        $this->install_model->admin_user_name              = (string)$this->input->post('admin_user_name');
        $this->install_model->admin_password               = (string)$this->input->post('admin_password');
        $this->install_model->admin_confirm_password       = (string)$this->input->post('admin_confirm_password');
        $this->install_model->hide_index                   = TRUE;
        $this->install_model->gzip_compression             = FALSE;
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

    public function check(){
        $this->get_input();
        $this->cms_show_json($this->install_model->check_installation());
    }

    public function install(){
        $this->get_input();
        $check_installation = $this->install_model->check_installation();
        $success = $check_installation['success'];
        $module_installed = FALSE;
        if($success){
            $config = array('subsite_home_content'=> $this->cms_get_config('cms_subsite_home_content', TRUE));
            $this->install_model->build_database($config);
            $this->install_model->build_configuration($config);
            $module_installed = $this->install_model->install_modules();
        }

        // upload the logo
        $upload_path = FCPATH.'modules/'.$this->cms_module_path().'/assets/uploads/';
        $file_name = NULL;
        if(isset($_FILES['logo']) && isset($_FILES['logo']['name']) && $_FILES['logo']['name'] != ''){
            $tmp_name = $_FILES['logo']['tmp_name'];
            $file_name = $_FILES['logo']['name'];
            $file_name = $this->randomize_string($file_name).$file_name;
            move_uploaded_file($tmp_name, $upload_path.$file_name);
        }

        $data = array(
            'name'=> $this->install_model->subsite,
            'logo'=>$file_name,
            'description'=>$this->input->post('description'),
            'use_subdomain'=>$this->input->post('use_subdomain')=='true',
            'aliases'=>$this->input->post('aliases'),
            'user_id'=>$this->cms_user_id(),
            'active'=>1,
        );
        $this->db->insert($this->cms_complete_table_name('subsite'), $data);
        $this->load->model($this->cms_module_path().'/subsite_model');
        $this->subsite_model->update_configs();

        $data = $check_installation;
        $data['module_installed'] = $module_installed;
        $data['admin_user_name'] = $this->install_model->admin_user_name;
        $data['admin_password'] = $this->install_model->admin_password;
        $data['subsite'] = $this->install_model->subsite;
        $this->view($this->cms_module_path().'/add_subsite_install', $data, $this->cms_complete_navigation_name('add_subsite'));

    }

    private function randomize_string($value){
        $time = date('Y:m:d H:i:s');
        return substr(md5($value.$time),0,6);
    }

}