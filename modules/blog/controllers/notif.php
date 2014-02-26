<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for
 *
 * @author goFrendiAsgard
 */
class Notif extends CMS_Priv_Strict_Controller {
    public function new_comment(){
        $this->load->model($this->cms_module_path().'/article_model');
        $notif = $this->article_model->new_comment_num();
        $result = array('success'=>TRUE,'notif'=>'');
        if($notif>0){
            $result['notif'] = $notif;
        }
        echo json_encode($result);
    }
}