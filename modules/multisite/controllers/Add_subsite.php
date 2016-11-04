<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Manage_Subsite
 *
 * @author No-CMS Module Generator
 */
class Add_subsite extends CMS_Secure_Controller {

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
        $this->install_model->db_table_prefix   = cms_table_prefix();
        $this->install_model->is_subsite        = TRUE;
        $this->install_model->subsite           = $this->input->post('subsite');
        $this->install_model->subsite_aliases   = (string)$this->input->post('aliases');
        $this->install_model->set_subsite();

        $this->install_model->hide_index        = TRUE;
        $this->install_model->gzip_compression  = FALSE;
    }

    public function index(){
        $this->load->model($this->cms_module_path().'/subsite_model');
        $data = array(
                'theme_list'    => $this->subsite_model->public_theme_list(),
                'layout_list'   => $this->cms_get_layout(),
                'template_list' => $this->subsite_model->template_list(),
            );
        $this->view($this->cms_module_path().'/add_subsite_index', $data, $this->n('add_subsite'));
    }

    public function check(){
        $this->get_input();
        $this->cms_show_json($this->install_model->check_installation());
    }

    public function install(){
        $module_path = $this->cms_module_path();
        $this->get_input();
        $check_installation = $this->install_model->check_installation();
        $success = $check_installation['success'];

        $this->load->model($this->cms_module_path().'/subsite_model');
        $template = $this->subsite_model->get_single_template($this->input->post('template'));
        $homepage_layout = $this->input->post('homepage_layout');
        $default_layout = $this->input->post('default_layout');
        $theme = $this->input->post('theme');

        // get configs
        $configs = $template != NULL? $template->configuration: $this->cms_get_config('cms_subsite_configs');
        $configs = @json_decode($configs, TRUE);
        if(!$configs){
            $configs = array();
        }
        if($theme != ''){
            $configs['site_theme'] = $theme;
        }
        if($default_layout != ''){
            $configs['site_layout'] = $default_layout;
        }

        // get modules
        $modules = $template != NULL? $template->modules: $this->cms_get_config('cms_subsite_modules');
        $modules = explode(',', $modules);
        $new_modules = array();
        foreach($modules as $module){
            $module = trim($module);
            if(!in_array($module, $new_modules)){
                $new_modules[] = $module;
            }
        }
        $modules = $new_modules;

        // upload the logo
        $upload_path = FCPATH.'modules/'.$this->cms_module_path().'/assets/uploads/';
        $file_name = NULL;
        if(isset($_FILES['logo']) && isset($_FILES['logo']['name']) && $_FILES['logo']['name'] != '' && getimagesize($_FILES['logo']['tmp_name']) !== FALSE){
            $tmp_name = $_FILES['logo']['tmp_name'];
            $file_name = $_FILES['logo']['name'];
            $file_name = $this->randomize_string($file_name).$file_name;
            move_uploaded_file($tmp_name, $upload_path.$file_name);
            @chmod($upload_path.$file_name, 644);
            $subsite = $this->install_model->subsite;
            $logo_file_name = FCPATH.'assets/nocms/images/custom_logo/'.$subsite.$_FILES['logo']['name'];
            $this->cms_resize_image($upload_path.$file_name, 800, 125, $logo_file_name);
            @chmod($logo_file_name, 644);
            $configs['site_logo'] = '{{ base_url }}assets/nocms/images/custom_logo/'.$subsite.$_FILES['logo']['name'];
        }

        $module_installed = FALSE;
        if($success){
            $this->install_model->configs = $configs;
            $this->install_model->modules = $modules;
            $config = array(
                    'subsite_home_content'=> $template != NULL? $template->homepage: $this->cms_get_config('cms_subsite_home_content', TRUE),
                    'subsite_homepage_layout' => $homepage_layout,
                    'subsite_user_id' => $this->cms_user_id(),
                );
            log_message('debug', 'Start installing '.$this->install_model->subsite.' subsite');
            $this->install_model->build_configuration($config);
            log_message('debug', 'Configuration built for '.$this->install_model->subsite.' subsite');
            $this->install_model->build_database($config);
            log_message('debug', 'Database built for '.$this->install_model->subsite.' subsite');
            $module_installed = $this->install_model->install_modules();
            log_message('debug', 'Module installed for '.$this->install_model->subsite.' subsite');
        }

        // $this->cms_override_module_path($module_path);

        $data = array(
            'name'=> $this->install_model->subsite,
            'logo'=>$file_name,
            'description'=>$this->input->post('description'),
            'use_subdomain'=>$this->input->post('use_subdomain')=='true',
            'aliases'=>$this->input->post('aliases'),
            'user_id'=>$this->cms_user_id(),
            'active'=>1,
        );
        $this->db->insert($this->t('subsite'), $data);
        $this->load->model($this->cms_module_path().'/subsite_model');

        $data = $check_installation;
        $data['module_installed'] = $module_installed;
        $data['admin_user_name'] = $this->install_model->admin_user_name;
        $data['admin_password'] = $this->install_model->admin_password;
        $data['subsite'] = $this->install_model->subsite;
        $this->view($this->cms_module_path().'/add_subsite_install', $data, $this->n('add_subsite'));

    }

    private function randomize_string($value){
        $time = date('Y:m:d H:i:s');
        return substr(md5($value.$time),0,6);
    }

}
