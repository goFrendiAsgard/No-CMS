<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for
 *
 * @author No-CMS Module Generator
 */
class Contact_us extends CMS_Secure_Controller {

    protected function do_override_url_map($URL_MAP){
        $module_path = $this->cms_module_path();
        $navigation_name = $this->n('index');
        $URL_MAP[$module_path.'/'.$module_path] = $navigation_name;
        $URL_MAP[$module_path] = $navigation_name;
        return $URL_MAP;
    }

    private function __random_string($length=10){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        $size = strlen( $chars );
        $str = '';
        for( $i = 0; $i < $length; $i++ ){
            $str .= $chars[ rand( 0, $size - 1 ) ];
        }
        return $str;
    }

    public function index(){
        // the honey_pot, every fake input should be empty
        $honey_pot_pass = (strlen($this->input->post('name', ''))==0) &&
            (strlen($this->input->post('email', ''))==0) &&
            (strlen($this->input->post('content', ''))==0);
        if(!$honey_pot_pass){
            show_404();
            die();
        }

        // get previously generated secret code
        $previous_secret_code = $this->session->userdata('__contact_us_secret_code');
        if($previous_secret_code === NULL){
            $previous_secret_code = $this->__random_string();
        }

        $name = $this->input->post($previous_secret_code.'name');
        $email = $this->input->post($previous_secret_code.'email');
        $content = $this->input->post($previous_secret_code.'content');

        $success = TRUE;
        $error_message = '';
        $show_success_message = FALSE;
        if($this->input->post('send')){
            if($honey_pot_pass){
                $valid_email = preg_match('/@.+\./', $email);
                if(!$valid_email){
                    $success = FALSE;
                    $error_message = "Invalid email";
                }
                if($name == NULL || $name == ''){
                    $success = FALSE;
                    $error_message = 'Name is empty';
                }
                if($content == NULL || $content == ''){
                    $success = FALSE;
                    $error_message = 'Message is empty';
                }
                if($success){
                    $data['name'] = $name;
                    $data['email'] = $email;
                    $data['content'] = html_entity_decode($content);
                    $data['read'] = 0;
                    $this->db->insert($this->t('message'), $data);
                    $name = '';
                    $email = '';
                    $content = '';
                    $show_success_message = TRUE;
                }
            }else{
                show_404();
                die();
            }
        }

        // generate new secret code
        $secret_code = $this->__random_string();
        $this->session->set_userdata('__contact_us_secret_code', $secret_code);

        $data['secret_code'] = $secret_code;
        $data['success'] = $success;
        $data['show_success_message'] = $show_success_message;
        $data['error_message'] = $error_message;
        $data['name'] = $name;
        $data['email'] = $email;
        $data['content'] = $content;
        $this->view($this->cms_module_path().'/contact_us_index', $data,
            $this->n('index'));
    }
}
