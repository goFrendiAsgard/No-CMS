<?php

/**
 * Description of install
 *
 * @author gofrendi
 */
class Install extends CMS_Module_Installer{
    //this should be what happen when user install this module
    protected function do_install(){
        $this->remove_all();
        $this->build_all();
        redirect('tugas_ai');
    }
    //this should be what happen when user uninstall this module
    protected function do_uninstall(){
        $this->remove_all();
        redirect('main');
    }
    
    private function remove_all(){
         
        $this->remove_navigation("tugas_ai_train_iris");
        $this->remove_navigation("tugas_ai_train_and"); 
        $this->remove_navigation("tugas_ai_train_or"); 
        $this->remove_navigation("tugas_ai_train_xor"); 
        $this->remove_navigation("tugas_ai_data_iris");
        $this->remove_navigation("tugas_ai_index");
    }
    
    private function build_all(){
        
        $this->add_navigation("tugas_ai_index","Tugas AI", "tugas_ai", 3);
        $this->add_navigation("tugas_ai_data_iris","Data (Iris)", "tugas_ai/data_iris", 3, "tugas_ai_index");
        $this->add_navigation("tugas_ai_train_iris","Train (Iris)", "tugas_ai/train_iris", 3, "tugas_ai_index");
        $this->add_navigation("tugas_ai_train_and","Train (AND)", "tugas_ai/train_and", 3, "tugas_ai_index");
        $this->add_navigation("tugas_ai_train_or","Train (OR)", "tugas_ai/train_or", 3, "tugas_ai_index");
        $this->add_navigation("tugas_ai_train_xor","Train (XOR)", "tugas_ai/train_xor", 3, "tugas_ai_index");
    }
}

?>
