<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for
 *
 * @author No-CMS Module Generator
 */
class Cck extends CMS_Secure_Controller {

    protected function do_override_url_map($URL_MAP){
        $module_path = $this->cms_module_path();
        $navigation_name = $this->n('index');
        $URL_MAP[$module_path.'/'.$module_path] = $navigation_name;
        $URL_MAP[$module_path] = $navigation_name;
        return $URL_MAP;
    }

    public function index(){
    	$data['content'] = $this->cms_submenu_screen($this->n('index'));
        $this->view($this->cms_module_path().'/Cck_index', $data,
            $this->n('index'));
    }
}