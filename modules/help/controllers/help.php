<?php

/**
 * Description of help
 *
 * @author gofrendi
 */
class Help extends CMS_Controller{
    //put your code here
    public function index(){
        $this->view($this->cms_module_path('gofrendi.noCMS.help').'/toc', NULL, 'help');
    }
    public function topic($topic_name){
        $this->view($this->cms_module_path('gofrendi.noCMS.help').'/topic/'.$topic_name, NULL, 'help');
    }    
}

?>
