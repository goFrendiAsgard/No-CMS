<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of help
 *
 * @author gofrendi
 */
class Help extends CMS_Controller{
    //put your code here
    public function index(){
        $this->view('help/toc', NULL, 'help');
    }
    public function topic($topic_name){
        $this->view('help/topic/'.$topic_name, NULL, 'help');
    }
}

?>
