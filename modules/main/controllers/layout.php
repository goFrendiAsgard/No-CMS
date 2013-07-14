<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Layout extends CMS_Controller{
    
    protected $theme = 'neutral';
    
    public function __construct(){
        parent::__construct();
        $this->theme = $this->cms_get_config('site_theme');
    }

    public function index(){
        $this->view('layout_index', NULL, 'main_layout');
    }
}
