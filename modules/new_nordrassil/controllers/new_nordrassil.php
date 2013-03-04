<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for
 *
 * @author No-CMS Module Generator
 */
class new_nordrassil extends CMS_Controller {
    public function index(){
    	$data['content'] = $this->cms_submenu_screen($this->cms_complete_navigation_name('index'));
        $this->view($this->cms_module_path().'/new_nordrassil_index', $data,
            $this->cms_complete_navigation_name('index'));
    }
}