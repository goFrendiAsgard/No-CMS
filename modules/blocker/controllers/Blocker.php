<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for
 *
 * @author No-CMS Module Generator
 */
class Blocker extends CMS_Secure_Controller {

    protected function do_override_url_map($URL_MAP){
        $module_path = $this->cms_module_path();
        $navigation_name = $this->n('index');
        $URL_MAP[$module_path.'/'.$module_path] = $navigation_name;
        $URL_MAP[$module_path] = $navigation_name;
        return $URL_MAP;
    }

    public function index(){
        $this->load->model('blocker/blocker_model');
        
        if($this->input->post('script')){
            $htaccess_content = file_get_contents(FCPATH.'.htaccess');
            $before = '';
            $after = '';
            $index_before = strpos($htaccess_content, '# {{ DENY }}');
            $before = substr($htaccess_content, 0, $index_before);
            
            $index_after = strpos($htaccess_content, '# {{ END OF DENY }}') + strlen('# {{ END OF DENY }}');
            $after = substr($htaccess_content, $index_after);
            
            $htaccess_content = $before .'# {{ DENY }}'.PHP_EOL. $this->input->post('script') .'    # {{ END OF DENY }}'. $after;
            file_put_contents(FCPATH.'.htaccess', $htaccess_content);
        }
        
        $data['script'] = $this->blocker_model->get_all();
        $data['menu'] = $this->cms_submenu_screen($this->n('index'));
        $this->view($this->cms_module_path().'/blocker_index', $data,
            $this->n('index'));
    }
}