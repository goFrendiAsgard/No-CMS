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
        $is_super_admin = in_array($this->cms_user_id(), $this->cms_user_group_id());
        // get module and theme list
        $module_list = $this->subsite_model->module_list();
        $theme_list = $this->subsite_model->theme_list();
        // if btn_save clicked
        $save = false;
        if($this->input->post('btn_save')){
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
                    'description'=>$description,
                    'use_subdomain'=>$use_subdomain,
                );
            if($logo !== NULL){
                $data['logo'] = $logo;
            }
            if($is_super_admin){
                $activated_module_list = $this->input->post('modules');
                $activated_theme_list = $this->input->post('themes');
                $activated_module_list = $activated_module_list===NULL? array() : $activated_module_list;
                $activated_theme_list = $activated_theme_list===NULL? array() : $activated_theme_list;
                $modules = $activated_module_list == NULL? '' : implode(',', $activated_module_list);
                $themes = $activated_theme_list == NULL? '' : implode(',', $activated_theme_list);
                $data['modules'] = $modules;
                $data['themes'] = $themes;
            }
            $this->db->update($this->cms_complete_table_name('subsite'), $data, array('name'=>$site_name));
            $this->subsite_model->update_configs();
            $save = true;
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
            'is_super_admin' => $is_super_admin,
            'save' => $save,
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

    public function ajax_user_multisite(){
        $user_id = $this->cms_user_id();
        $query = $this->db->select('name')
            ->from($this->cms_complete_table_name('subsite'))
            ->where('user_id', $user_id)
            ->get();
        $subsite_list = array();
        foreach($query->result() as $row){
            $subsite_list[] = $row->name;
        }
        echo json_encode($subsite_list);
    }
}