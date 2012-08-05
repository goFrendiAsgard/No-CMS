<?php

/**
 * Description of module_generator
 *
 * @author gofrendi
 */
class module_generator extends CMS_Controller{
    //put your code here
    public function index(){
        $this->load->model($this->cms_module_path('gofrendi.noCMS.moduleGenerator').'/data');
        $tables = $this->data->get_tables();
        
        $data = array(); 
        foreach($tables as $table){
            $data['tables'][$table] = $table; 
        }
        $this->view($this->cms_module_path('gofrendi.noCMS.moduleGenerator').'/module_generator_index', $data, 'module_generator_index');
    }
    
    public function make(){
        
        $moduleNameSpace = $this->input->post('module_namespace');
        $moduleDirectory = $this->input->post('module_directory');
        $moduleNameSpace = $this->replace($moduleNameSpace,
        		array(' '),
        		array('_'));
        $moduleDirectory = $this->replace($moduleDirectory,
        		array(' '),
        		array('_'));
        $tables = $this->input->post('tables');
        if(!$moduleNameSpace || !$tables || !moduleDirectory){
            redirect($this->cms_module_path('gofrendi.noCMS.moduleGenerator').'/index');
        }
        $existingModules = $this->cms_get_module_list();
        foreach($existingModules as $existingModule){
            $existingModuleDirectory = $existingModule['path'];
            if($moduleDirectory == $existingModuleDirectory){
                redirect($this->cms_module_path('gofrendi.noCMS.moduleGenerator').'/index');
                break;
            }
        }
        
        // It's okay now to make the new module        
        $this->load->model($this->cms_module_path('gofrendi.noCMS.moduleGenerator').'/data');
        
        // make the directories
        $this->make_folder($moduleDirectory);
        $this->make_folder($moduleDirectory.'/models');
        $this->make_folder($moduleDirectory.'/views');
        $this->make_folder($moduleDirectory.'/controllers');
        
        $str = file_get_contents(BASEPATH.'../modules/'.$this->cms_module_path('gofrendi.noCMS.moduleGenerator').'/resources/installer_controller.txt');
        $str = $this->replace($str,
                    array(
                        '@moduleName',
                    	'@moduleDirectory',
                        '@dropTable',
                        '@createTable',
                        '@addNavigation',
                        '@removeNavigation'
                    ),
                    array(
                        $moduleNameSpace,
                    	$moduleDirectory,
                        $this->data->get_drop_syntax($this->reverse_array($tables)),
                        $this->data->get_create_syntax($tables),
                        $this->data->get_add_navigation($moduleDirectory, $tables),
                        $this->data->get_remove_navigation($moduleDirectory, $this->reverse_array($tables))
                    )
                );
        $this->make_file($moduleDirectory.'/controllers/install.php', $str);
        
        $str = file_get_contents(BASEPATH.'../modules/'.$this->cms_module_path('gofrendi.noCMS.moduleGenerator').'/resources/main_controller.txt');
        $str = $this->replace($str,
                    array(
                        '@moduleName',
                    	'@moduleDirectory',
                        '@functions',
                    ),
                    array(
                        $moduleNameSpace,
                    	$moduleDirectory,
                        $this->data->get_functions($moduleDirectory, $tables)
                    )
                );
        $this->make_file($moduleDirectory.'/controllers/'.$moduleDirectory.'.php',$str);
        
        $str = file_get_contents(BASEPATH.'../modules/'.$this->cms_module_path('gofrendi.noCMS.moduleGenerator').'/resources/view_index.txt');
        $str = $this->replace($str,
                    array(
                        '@moduleName',
                    	'@moduleDirectory'
                    ),
                    array(
                        $moduleNameSpace,
                    	$moduleDirectory
                    )
                );
        $this->make_file($moduleDirectory.'/views/'.$moduleDirectory.'_index.php',$str);
        
        $str = 'Deny from all';
        $this->make_file($moduleDirectory.'/models/.htaccess',$str);
        $this->make_file($moduleDirectory.'/views/.htaccess',$str);
        $this->make_file($moduleDirectory.'/controllers/.htaccess',$str);
        
        redirect('main/module_management');
        
    }
    
    private function make_folder($folderName){
        mkdir(BASEPATH.'../modules/'.$folderName, 0777);
        chmod(BASEPATH.'../modules/'.$folderName, 0777);
    }
    
    private function make_file($fileName, $content){
        file_put_contents(BASEPATH.'../modules/'.$fileName, $content);
        chmod(BASEPATH.'../modules/'.$fileName, 0777);
    }
    
    private function replace($str,$search,$replace){
        if(count($search)==count($replace)){
            for($i=0; $i<count($search); $i++){
                $str = str_replace($search, $replace, $str);
            }
        }
        return $str;
    }
    
    private function reverse_array($arr){
        $result = array();
        for($i=count($arr)-1; $i>=0; $i--){
            $result[] = $arr[$i];
        }
        return $result;
    }
    
}

?>
