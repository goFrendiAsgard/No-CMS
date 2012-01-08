<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of module_generator
 *
 * @author gofrendi
 */
class module_generator extends CMS_Controller{
    //put your code here
    public function index(){
        
        $data = array();
        $this->load->model('module_generator/data');
        $tables = $this->data->get_tables();
        foreach($tables as $table){
            $data['tables'][$table] = $table; 
        }
        $this->view('module_generator/module_generator_index', $data, 'module_generator_index');
    }
    
    public function make(){
        $moduleName = $this->input->post('module_name');
        $tables = $this->input->post('tables');
        if(!$moduleName || !$tables){
            redirect('module_generator/index');
        }
        $existingModules = $this->cms_get_module_list();
        foreach($existingModules as $existingModule){
            $existingModuleName = $existingModule['path'];
            if($moduleName == $existingModuleName){
                redirect('module_generator/index');
                break;
            }
        }
        
        //It's okay now to make the new module
        $this->make_folder($moduleName);
        $this->make_folder($moduleName.'/models');
        $this->make_folder($moduleName.'/views');
        $this->make_folder($moduleName.'/controllers');
        $this->make_file($moduleName.'/controllers/install.php','foo');
        $this->make_file($moduleName.'/controllers/'.$moduleName,'foo');
        
    }
    
    private function make_folder($folderName){
        mkdir(BASEPATH.'../modules/'.$folderName, 0777);
    }
    
    private function make_file($fileName, $content){
        file_put_contents(BASEPATH.'../modules/'.$fileName, $content);
    }
}

?>
