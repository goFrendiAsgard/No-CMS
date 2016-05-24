<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for
 *
 * @author No-CMS Module Generator
 */
class Nordrassil extends CMS_Secure_Controller {

    protected function do_override_url_map($URL_MAP){
        $module_path = $this->cms_module_path();
        $navigation_name = $this->n('index');
        $URL_MAP[$module_path.'/'.$module_path] = $navigation_name;
        $URL_MAP[$module_path] = $navigation_name;
        return $URL_MAP;
    }

    public function index(){
        $this->load->model($this->cms_module_path().'/nds_model');
        $data['projects'] = $this->nds_model->get_all_project();
    	$data['content'] = $this->cms_submenu_screen($this->n('index'));
        $this->view($this->cms_module_path().'/Nordrassil_index', $data,
            $this->n('index'));
    }

    public function import(){
        $this->load->model($this->cms_module_path().'/nds_model');
        $seed = $this->input->post('seed');
        try{
            $seed = json_decode($seed, TRUE);
            if(!is_array($seed)){
                redirect($this->cms_module_path());
            }
        }catch(Exception $e){
            redirect($this->cms_module_path());
        }
        $project_id = $this->nds_model->import_project($seed);
        if($project_id !== FALSE){
            redirect(site_url($this->cms_module_path().'/manage_project/index/edit/'.$project_id));
        }else{
            redirect(site_url($this->cms_module_path().'/nordrassil'));
        }
    }
}
