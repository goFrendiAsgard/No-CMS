<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hauth extends CMS_Controller {

    public function __construct()
    {
        // Constructor to auto-load HybridAuthLib
        parent::__construct();
        if(in_array  ('curl', get_loaded_extensions())){
            try{
                $this->load->library('Hybridauthlib');
            }catch(Exception $e){
                redirect('');
            }
        }else{
            redirect('');
        }
    }

    public function index(){
        $this->cms_guard_page('main_third_party_auth');
        if(in_array  ('curl', get_loaded_extensions())){
            // Send to the view all permitted services as a user profile if authenticated
            $data['providers'] = $this->hybridauthlib->getProviders();
            foreach($data['providers'] as $provider=>$d) {
                if ($d['connected'] == 1) {
                    $data['providers'][$provider]['user_profile'] = $this->hybridauthlib->authenticate($provider)->getUserProfile();
                }
            }
        }else{
            $data['providers'] = array();
        }
        $this->view('main/hauth/home', $data, 'main_third_party_auth');
    }

    public function open_id(){
        $this->cms_guard_page('main_third_party_auth');
        $this->view('main/hauth/open_id', NULL, 'main_third_party_auth');
    }

    public function login($provider)
    {
        log_message('debug', "controllers.HAuth.login($provider) called");

        $params = array();
        if($provider == 'OpenID'){
            $open_id_identifier = $this->input->post('open_id_identifier');
            if(!$open_id_identifier){
                redirect('main/hauth/open_id');
            }else{
                $params['openid_identifier'] = $open_id_identifier;
            }
        }

        try
        {
            log_message('debug', 'controllers.HAuth.login: loading HybridAuthLib');


            if ($this->hybridauthlib->providerEnabled($provider))
            {
                log_message('debug', "controllers.HAuth.login: service $provider enabled, trying to authenticate.");
                $service = $this->hybridauthlib->authenticate($provider, $params);

                if ($service->isUserConnected())
                {
                    // twitter doesn't provide email address, we should humbly ask to the user...
                    if($provider == 'Twitter'){
                        // check if the user already registered in our database
                        $status = $this->cms_third_party_status();
                        $identifier = $status[$provider]['identifier'];
                        $query = $this->db->select('auth_'.$provider)
                            ->from($this->cms_user_table_name())
                            ->where('auth_'.$provider, $identifier)
                            ->get();
                        // if it is not, let's humbly ask the user's email
                        if($query->num_rows() == 0){
                            redirect('main/hauth/email/'.$provider);
                        }
                    }

                    $this->cms_third_party_login($provider);
                    //$this->hybridauthlib->logoutAllProviders();
                    log_message('debug', 'controller.HAuth.login: user authenticated.');
                    redirect('', 'refresh');

                }
                else // Cannot authenticate user
                    {
                    show_error('Cannot authenticate user');
                }
            }
            else // This service is not enabled.
                {
                log_message('error', 'controllers.HAuth.login: This provider is not enabled ('.$provider.')');
                show_404($_SERVER['REQUEST_URI']);
            }
        }
        catch(Exception $e)
        {
            $error = 'Unexpected error';
            switch($e->getCode())
            {
            case 0 : $error = 'Unspecified error.'; break;
            case 1 : $error = 'Hybriauth configuration error.'; break;
            case 2 : $error = 'Provider not properly configured.'; break;
            case 3 : $error = 'Unknown or disabled provider.'; break;
            case 4 : $error = 'Missing provider application credentials.'; break;
            case 5 : log_message('debug', 'controllers.HAuth.login: Authentification failed. The user has canceled the authentication or the provider refused the connection.');
                //redirect();
                if (isset($service))
                {
                    log_message('debug', 'controllers.HAuth.login: logging out from service.');
                    $service->logout();
                }
                show_error('User has cancelled the authentication or the provider refused the connection.');
                break;
            case 6 : $error = 'User profile request failed. Most likely the user is not connected to the provider and he should to authenticate again.';
                break;
            case 7 : $error = 'User not connected to the provider.';
                break;
            }

            if (isset($service))
            {
                $service->logout();
            }

            log_message('error', 'controllers.HAuth.login: '.$error);
            show_error('Error authenticating user.');
        }
    }

    // Ask for email (I really hate twitter for this....)
    public function email($provider){
        if($this->input->post('email') == NULL || $this->input->post('email') == ''){
            $data = array('provider'=>$provider);
            $this->view('main/hauth/email', $data, 'main_third_party_auth');
        }else{
            $email = $this->input->post('email');
            $this->cms_third_party_login($provider, $email);
            //$this->hybridauthlib->logoutAllProviders();
            log_message('debug', 'controller.HAuth.login: user authenticated.');
            redirect('', 'refresh');
        }

    }

    // Little json api and variable output testing function. Make it easy with JS to verify a session.  ;)
    public function status()
    {
        $this->cms_show_json($this->cms_third_party_status());
    }

    public function endpoint()
    {

        log_message('debug', 'controllers.HAuth.endpoint called.');
        log_message('info', 'controllers.HAuth.endpoint: $_REQUEST: '.print_r($_REQUEST, TRUE));

        if ($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            log_message('debug', 'controllers.HAuth.endpoint: the request method is GET, copying REQUEST array into GET array.');
            $_GET = $_REQUEST;
        }

        log_message('debug', 'controllers.HAuth.endpoint: loading the original HybridAuth endpoint script.');
        require_once APPPATH.'/third_party/hybridauth/index.php';

    }
}

/* End of file hauth.php */
/* Location: ./application/controllers/hauth.php */
