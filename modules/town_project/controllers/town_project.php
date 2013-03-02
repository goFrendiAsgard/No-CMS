<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for
 *
 * @author No-CMS Module Generator
 */
class town_project extends CMS_Controller {
    public function index(){
        $module_path = $this->cms_module_path();
    	$data['content'] = $this->cms_submenu_screen(cms_well_name($module_path,'index'));
        $this->view('town_project/town_project_index', $data, cms_well_name($module_path,'index'));
    }
}