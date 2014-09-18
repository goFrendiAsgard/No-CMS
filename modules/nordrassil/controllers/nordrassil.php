<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for
 *
 * @author No-CMS Module Generator
 */
class nordrassil extends CMS_Priv_Strict_Controller {

    protected function do_override_url_map($URL_MAP){
        $module_path = $this->cms_module_path();
        $URL_MAP[$module_path] = $this->cms_complete_navigation_name('index');
        $URL_MAP[$module_path.'/nordrassil'] = $this->cms_complete_navigation_name('index');
        $URL_MAP[$module_path.'/nordrassil/import'] = $this->cms_complete_navigation_name('index');
        return $URL_MAP;
    }

    public function index(){
    	$this->load->model($this->cms_module_path().'/data/nds_model');
		$data['projects'] = $this->nds_model->get_all_project();

    	$data['content'] = $this->cms_submenu_screen($this->cms_complete_navigation_name('index'));
        $this->view($this->cms_module_path().'/nordrassil_index',$data,
            $this->cms_complete_navigation_name('index'));
    }

    public function import(){
        $this->load->model($this->cms_module_path().'/data/nds_model');
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
            redirect(site_url($this->cms_module_path().'/data/nds/project/edit/'.$project_id));
        }else{
            redirect(site_url($this->cms_module_path().'/nordrassil'));
        }
    }
}