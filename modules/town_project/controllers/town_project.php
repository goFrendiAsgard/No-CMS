<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for
 *
 * @author No-CMS Module Generator
 */
class town_project extends CMS_Controller {
    public function index(){
    	$data['content'] = $this->cms_submenu_screen($this->cms_complete_navigation_name('index'));
        $this->view('town_project/town_project_index', $data, $this->cms_complete_navigation_name('index'));
    }
}