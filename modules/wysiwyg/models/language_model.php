<?php

/**
 * Description of language_model
 *
 * @author gofrendi
 */
class Language_Model extends CMS_Model {
    public function get_language(){
        $this->load->helper('file');
        $result = get_filenames('assets/nocms/languages');
        for($i=0; $i<count($result); $i++){
            $result[$i] = str_ireplace('.php', '', $result[$i]);
        }
        return $result;
    }
}
