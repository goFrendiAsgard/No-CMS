<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for
 *
 * @author goFrendiAsgard
 */
class Notif extends CMS_Secure_Controller {

    protected function do_override_url_map($URL_MAP){
        $module_path = $this->cms_module_path();
        $URL_MAP[$module_path.'/notif/new_comment'] = $this->n('index');
        return $URL_MAP;
    }

    public function new_comment(){
        if($this->cms_allow_navigate($this->n('manage_article'))){
            $this->load->model($this->cms_module_path().'/article_model');
            $notif = $this->article_model->new_comment_num();
            $result = array('success'=>TRUE,'notif'=>'');
            if($notif>0){
                $result['notif'] = $notif;
            }
        }else{
            $result = array('success'=>TRUE, 'notif'=>'');
        }
        echo json_encode($result);
    }
}
