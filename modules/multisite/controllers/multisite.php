<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for
 *
 * @author No-CMS Module Generator
 */
class multisite extends CMS_Priv_Strict_Controller {

    protected function do_override_url_map($URL_MAP){
        $module_path = $this->cms_module_path();
        $navigation_name = $this->cms_complete_navigation_name('index');
        $URL_MAP[$module_path.'/'.$module_path] = $navigation_name;
        $URL_MAP[$module_path] = $navigation_name;
        $URL_MAP[$module_path.'/'.$module_path.'/get_data'] = $navigation_name;
        $URL_MAP[$module_path.'/get_data'] = $navigation_name;
        return $URL_MAP;
    }

    public function index(){   
        $data = array(            
            'allow_navigate_backend' => CMS_SUBSITE == '' && $this->cms_allow_navigate($this->cms_complete_navigation_name('add_subsite')),
            'backend_url' => site_url($this->cms_module_path().'/add_subsite/index'),
            'module_path' => $this->cms_module_path(),
        );
        $this->view($this->cms_module_path().'/multisite_index',$data,
            $this->cms_complete_navigation_name('index'));
    }

    public function get_data(){
        // only accept ajax request
        if(!$this->input->is_ajax_request()) $this->cms_redirect();
        // get page and keyword parameter
        $keyword = $this->input->post('keyword');
        $page = $this->input->post('page');
        if(!$keyword) $keyword = '';
        if(!$page) $page = 0;

        // get data from model
        $this->load->model($this->cms_module_path().'/subsite_model');
        $this->Subsite_Model = new Subsite_Model();
        $result = $this->Subsite_Model->get_data($keyword, $page);

        // get the original site_url (without site-* or subdomain)
        $site_url = site_url();
        // remove any site-*
        $site_url = preg_replace('/site-.*/', '', $site_url);        
        // remove any relevant subdomain
        include(FCPATH.'site.php');
        $subdomain_prefixes = $available_site;
        for($i=0; $i<count($subdomain_prefixes); $i++){
            $subdomain_prefixes[$i] .= '.';
        }
        $site_url = str_replace($subdomain_prefixes, '', $site_url);

        $data = array(
            'site_url' => $site_url,
            'result'=>$result,
            'allow_navigate_backend' => CMS_SUBSITE == '' && $this->cms_allow_navigate($this->cms_complete_navigation_name('add_subsite')),
            'backend_url' => site_url($this->cms_module_path().'/add_subsite/index'),
        );
        $config = array('only_content'=>TRUE);
        $this->view($this->cms_module_path().'/browse_subsite_partial_view',$data,
           $this->cms_complete_navigation_name('browse_subsite'), $config);
    }
}