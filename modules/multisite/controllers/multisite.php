<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for
 *
 * @author No-CMS Module Generator
 */
class multisite extends CMS_Priv_Strict_Controller {

    private function randomize_string($value){
        $time = date('Y:m:d H:i:s');
        return substr(md5($value.$time),0,6);
    }

    protected function do_override_url_map($URL_MAP){
        $module_path = $this->cms_module_path();
        $navigation_name = $this->cms_complete_navigation_name('index');
        $URL_MAP[$module_path.'/'.$module_path] = $navigation_name;
        $URL_MAP[$module_path] = $navigation_name;
        $URL_MAP[$module_path.'/'.$module_path.'/get_data'] = $navigation_name;
        $URL_MAP[$module_path.'/get_data'] = $navigation_name;
        return $URL_MAP;
    }

    protected function make_associative_array($array){
        $new_array = array();
        foreach($array as $element){
            $new_array[$element] = $element;
        }
        return $new_array;
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

    public function edit($site_name){
        $this->cms_guard_page($this->cms_complete_navigation_name('index'), 'modify_subsite');
        $this->load->model($this->cms_module_path().'/subsite_model');
        // get module and theme list
        $module_list = $this->subsite_model->module_list();
        $theme_list = $this->subsite_model->theme_list();
        // if btn_save clicked
        if($this->input->post('btn_save')){
            $activated_module_list = $this->input->post('modules');
            $activated_theme_list = $this->input->post('themes');
            $activated_module_list = $activated_module_list===NULL? array() : $activated_module_list;
            $activated_theme_list = $activated_theme_list===NULL? array() : $activated_theme_list;
            $modules = $activated_module_list == NULL? '' : implode(',', $activated_module_list);
            $themes = $activated_theme_list == NULL? '' : implode(',', $activated_theme_list);
            $description = $this->input->post('description');
            $name = $this->input->post('name');
            $use_subdomain = $this->input->post('use_subdomain') == 'True'? 1 : 0;

            // upload the logo
            $upload_path = FCPATH.'modules/'.$this->cms_module_path().'/assets/uploads/';
            $file_name = NULL;
            if(isset($_FILES['logo']) && isset($_FILES['logo']['name']) && $_FILES['logo']['name'] != ''){
                $tmp_name = $_FILES['logo']['tmp_name'];
                $file_name = $_FILES['logo']['name'];
                $file_name = $this->randomize_string($file_name).$file_name;
                move_uploaded_file($tmp_name, $upload_path.$file_name);
            }
            $logo = $file_name;
            
            $data = array(
                    'modules'=>$modules,
                    'themes'=>$themes,
                    'description'=>$description,
                    'use_subdomain'=>$use_subdomain,
                );
            if($logo !== NULL){
                $data['logo'] = $logo;
            }
            $this->db->update($this->cms_complete_table_name('subsite'), $data, array('name'=>$site_name));
            $this->subsite_model->update_configs();

            // edit module configuration file
            foreach($module_list as $module){
                // get subsite allowed of this module
                $subsite_auth_file = FCPATH . 'modules/' . $module . '/subsite_auth.php';
                if (file_exists($subsite_auth_file)){
                    unset($subsite_allowed);
                    include($subsite_auth_file);
                }
                if(!isset($subsite_allowed) || $subsite_allowed == NULL){
                    $subsite_allowed = array();
                }

                // add if not in subsite allowed
                $content = file_get_contents($subsite_auth_file);
                if(in_array($module, $activated_module_list) && !in_array($site_name, $subsite_allowed)){
                    // to be activated
                    $content .= PHP_EOL.'$subsite_allowed[] = \''.$site_name.'\';';
                    @file_put_contents($subsite_auth_file, $content);
                }else if(!in_array($module, $activated_module_list) && in_array($site_name, $subsite_allowed)){
                    // to be deactivated
                    $content = str_replace( PHP_EOL.'$subsite_allowed[] = \''.$site_name.'\';', '', $content);
                    @file_put_contents($subsite_auth_file, $content);
                }
            }

            // edit theme configuration file
            foreach($theme_list as $theme){
                // get subsite allowed of this theme
                $subsite_auth_file = FCPATH . 'themes/' . $theme . '/subsite_auth.php';
                if (file_exists($subsite_auth_file)){
                    unset($subsite_allowed);
                    include($subsite_auth_file);
                }
                if(!isset($subsite_allowed) || $subsite_allowed == NULL){
                    $subsite_allowed = array();
                }

                // add if not in subsite allowed
                $content = file_get_contents($subsite_auth_file);
                if(in_array($theme, $activated_theme_list) && !in_array($site_name, $subsite_allowed)){
                    // to be activated
                    $content .= PHP_EOL.'$subsite_allowed[] = \''.$site_name.'\';';
                    @file_put_contents($subsite_auth_file, $content);
                }else if(!in_array($theme, $activated_theme_list) && in_array($site_name, $subsite_allowed)){
                    // to be deactivated
                    $content = str_replace( PHP_EOL.'$subsite_allowed[] = \''.$site_name.'\';', '', $content);
                    @file_put_contents($subsite_auth_file, $content);
                }
            }

        }
        // get data
        $subsite = $this->subsite_model->get_one_data($site_name);
        $data = array(
            'edit_url' => $this->cms_module_path() == 'multisite'? 
                site_url($this->cms_module_path().'/edit/'.$site_name) :
                site_url($this->cms_module_path().'/multisite/edit/'.$site_name),         
            'description' => $subsite->description,
            'name' => $subsite->name,
            'logo' => $subsite->logo,
            'use_subdomain' => $subsite->use_subdomain,
            'modules' => $subsite->modules,
            'themes' => $subsite->themes,
            'aliases'=> $subsite->aliases,
            'module_list' => $this->make_associative_array($module_list),
            'theme_list' => $this->make_associative_array($theme_list),
        );
        // show
        $config = array('privileges'=>array('modify_subsite'));
        $this->view($this->cms_module_path().'/multisite_edit', $data,
            $this->cms_complete_navigation_name('index'), $config);
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
            'allow_navigate_backend' => CMS_SUBSITE == '' && $this->cms_have_privilege('modify_subsite'),
            'edit_url' => $this->cms_module_path() == 'multisite'? site_url($this->cms_module_path().'/edit') :site_url($this->cms_module_path().'/multisite/edit'),
        );
        $config = array('only_content'=>TRUE);
        $this->view($this->cms_module_path().'/browse_subsite_partial_view',$data,
           $this->cms_complete_navigation_name('browse_subsite'), $config);
    }
}