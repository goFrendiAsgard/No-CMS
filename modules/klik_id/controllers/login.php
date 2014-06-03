<?php
class Login extends CMS_Controller{
    private $app_id = 'nocms';
    private $app_token = '';

    // go to klikindonesia
    function login(){
        $this->load->helper('url');
        redirect('https://auth.klikindonesia.or.id/authorize.php?scope=authorizations&appid='.
            $this->app_id.'&access_type=login');
    }

    // get back and validate token
    function validate(){
        // get input
        $app_id = $this->app_id;
        $app_token = $this->app_token;
        $user_token = $this->input->get('token');

        if (!function_exists('curl_init')){
            die('curl is not installed!');
        }
        $url = 'https://auth.klikindonesia.or.id/api.php/authorizations/'.$app_id.'/'.$user_token;
        $token_app ='Akses Token aplikasi anda';
        $header = array();
        $header[] = 'Authorization: Bearer '.$app_token;
        $header[] = 'Content-Type: application/json'; // type data yang tersedia saat ini dalam format json
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, false); // methode yg digunakan GET
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);            //to suppress the curl output
        $result_data = curl_exec($ch);
        if($result = curl_exec($ch)==true){
            print_r($result_data);
        }else{
            throw new Exception();
        }
        curl_close ($ch);
    }
}
