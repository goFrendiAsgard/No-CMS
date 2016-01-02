<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for
 *
 * @author goFrendiAsgard
 */
class Notif extends CMS_Secure_Controller {

    protected function do_override_url_map($URL_MAP){
        $module_path = $this->cms_module_path();
        $URL_MAP[$module_path.'/notif/new_message'] = $this->n('index');
        return $URL_MAP;
    }

    public function new_message(){
        if($this->cms_allow_navigate($this->n('manage_message'))){
            $record_list = $this->cms_get_record_list($this->t('message'), 'read', 0);
            $notif = count($record_list);
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
