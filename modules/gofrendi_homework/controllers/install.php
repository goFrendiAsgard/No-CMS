<?php

/**
 * Description of install
 *
 * @author gofrendi
 */
class Install extends CMS_Module_Installer{
    protected $DEPENDENCIES = array('artificial_intelligence'); 
    
    //this should be what happen when user install this module
    protected function do_install(){
        $this->remove_all();
        $this->build_all();
    }
    //this should be what happen when user uninstall this module
    protected function do_uninstall(){
        $this->remove_all();
    }
    
    private function remove_all(){
         
        $this->remove_navigation("gofrendi_homework_train_iris");
        $this->remove_navigation("gofrendi_homework_train_and"); 
        $this->remove_navigation("gofrendi_homework_train_or"); 
        $this->remove_navigation("gofrendi_homework_train_xor"); 
        $this->remove_navigation("gofrendi_homework_data_iris");
        $this->remove_navigation("gofrendi_homework_index");
    }
    
    private function build_all(){
        
        $this->add_navigation("gofrendi_homework_index","Tugas AI", "gofrendi_homework", 3);
        $this->add_navigation("gofrendi_homework_data_iris","Data (Iris)", "gofrendi_homework/data_iris", 3, "gofrendi_homework_index");
        $this->add_navigation("gofrendi_homework_train_iris","Train (Iris)", "gofrendi_homework/train_iris", 3, "gofrendi_homework_index");
        $this->add_navigation("gofrendi_homework_train_and","Train (AND)", "gofrendi_homework/train_and", 3, "gofrendi_homework_index");
        $this->add_navigation("gofrendi_homework_train_or","Train (OR)", "gofrendi_homework/train_or", 3, "gofrendi_homework_index");
        $this->add_navigation("gofrendi_homework_train_xor","Train (XOR)", "gofrendi_homework/train_xor", 3, "gofrendi_homework_index");
    }
}

?>
