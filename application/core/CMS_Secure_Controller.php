<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * CMS_Secure_Controller class.
 *
 * @author gofrendi
 */
class CMS_Secure_Controller extends CMS_Controller
{
    private $navigation_name = '';

    protected $URL_MAP = array();
    protected $ALLOW_UNKNOWN_NAVIGATION_NAME = false;

    public function __construct()
    {
        parent::__construct();
        $this->URL_MAP = $this->do_override_url_map($this->URL_MAP);
        $uriString = $this->uri->uri_string();
        $navigation_name = null;
        if (isset($this->URL_MAP[$uriString])) {
            if (!isset($navigation_name)) {
                $navigation_name = $this->cms_navigation_name($this->URL_MAP[$uriString]);
            }
            if (!isset($navigation_name)) {
                $navigation_name = $this->URL_MAP[$uriString];
            }
        } else {
            foreach ($this->URL_MAP as $key => $value) {
                if ($uriString == $this->cms_parse_keyword($key)) {
                    if (!isset($navigation_name)) {
                        $navigation_name = $this->cms_navigation_name($key);
                    }
                    if (!isset($navigation_name)) {
                        $navigation_name = $this->URL_MAP[$key];
                    }
                    break;
                }
            }
        }
        if (!isset($navigation_name)) {
            $navigation_name = $this->cms_navigation_name($uriString);
        }
        $this->cms_guard_page($navigation_name);
        if (!$this->__cms_dynamic_widget && $uriString != '' && !$this->ALLOW_UNKNOWN_NAVIGATION_NAME && !isset($navigation_name)) {
            if ($this->input->is_ajax_request()) {
                $response = array(
                    'success' => false,
                    'message' => 'unauthorized access',
                );
                $this->cms_show_json($response);
                die();
            } else {
                $this->cms_redirect();
            }
        }
        $this->navigation_name = $navigation_name;
    }

    protected function do_override_url_map($URL_MAP)
    {
        return $URL_MAP;
    }

    protected function cms_override_navigation_name($navigation_name)
    {
        if (!isset($navigation_name) || $navigation_name == '') {
            $navigation_name = $this->navigation_name;
        }

        return $navigation_name;
    }

    protected function cms_override_config($config)
    {
        $config['always_allow'] = true;

        return $config;
    }

    public function view($view_url, $data = null, $navigation_name = null, $config = null, $return_as_string = false)
    {
        if (is_bool($navigation_name) && count($config) == 0) {
            $return_as_string = $navigation_name;
            $navigation_name = null;
            $config = null;
        } elseif (is_bool($config)) {
            $return_as_string = $config;
            $config = null;
        }
        if (!isset($config) || !is_array($config)) {
            $config = array();
        }
        $navigation_name = $this->cms_override_navigation_name($navigation_name);
        $config = $this->cms_override_config($config);

        return parent::view($view_url, $data, $navigation_name, $config, $return_as_string);
    }
}
