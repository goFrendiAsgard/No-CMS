<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of cms_model
 *
 * @author gofrendi
 */
class CMS_Controller extends CI_Controller {

    private $is_mobile = false;

    public function __construct() {
        parent::__construct();

        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->helper('form');
        $this->load->library('user_agent');
        //$this->load->library('session');
        $this->load->library('form_validation');
        /* ------------------ */

        $this->load->library('grocery_CRUD');
        $this->load->library('template');

        $this->load->model('CMS_Model');

        $this->is_mobile = $this->agent->is_mobile();
    }

    /**
     * @author  goFrendiAsgard
     * @param  key,value
     * @desc  if value specified, this will set CI session, else, it will return CI session
     */
    private function cms_ci_session($key, $value = NULL) {
        return $this->CMS_Model->cms_ci_session($key, $value);
    }

    /**
     * @author  goFrendiAsgard
     * @param  key
     * @desc  delete a CI session
     */
    private function cms_unset_ci_session($key) {
        return $this->CMS_Model->cms_unset_ci_session($key);
    }

    /**
     * @author  goFrendiAsgard
     * @param  username
     * @desc  if username specified, this will set cms_username session, else, it will return cms_username
     */
    protected function cms_username($username = NULL) {
        return $this->CMS_Model->cms_username($username);
    }

    /**
     * @author  goFrendiAsgard
     * @param  userid
     * @desc  if userid specified, this will set cms_userid session, else, it will return cms_userid
     */
    protected function cms_userid($userid = NULL) {
        return $this->CMS_Model->cms_userid($userid);
    }

    /**
     * @author  goFrendiAsgard
     * @param  parent_id, max_menu_depth
     * @desc  return navigation child if parent_id specified, else it will return root navigation
     */
    private function cms_navigations($parent_id = NULL, $max_menu_depth = NULL) {
        return $this->CMS_Model->cms_navigations($parent_id, $max_menu_depth);
    }

    /**
     * @author goFrendiAsgard
     * @desc return quick links
     */
    private function cms_quicklinks() {
        return $this->CMS_Model->cms_quicklinks();
    }

    /**
     * @author  goFrendiAsgard
     * @param  parent_id, max_menu_depth
     * @desc  return navigation child if parent_id specified, else it will return root navigation
     */
    private function cms_widgets($slug = NULL) {
        return $this->CMS_Model->cms_widgets($slug);
    }

    /**
     * @author  goFrendiAsgard
     * @param  navigation_name
     * @desc  return navigation path, used for layout
     */
    private function cms_get_navigation_path($navigation_name = NULL) {
        return $this->CMS_Model->cms_get_navigation_path($navigation_name);
    }

    /**
     * @author  goFrendiAsgard
     * @desc  return privileges of current user
     */
    private function cms_privileges() {
        return $this->CMS_Model->cms_privileges();
    }

    /**
     * @author  goFrendiAsgard
     * @param  navigation
     * @desc  check if user authorized to navigate into a page specified in parameter
     */
    protected function cms_allow_navigate($navigation) {
        return $this->CMS_Model->cms_allow_navigate($navigation);
    }

    /**
     * @author  goFrendiAsgard
     * @param  privilege
     * @desc  check if user have privilege specified in parameter
     */
    protected function cms_have_privilege($privilege) {
        return $this->CMS_Model->cms_have_privilege($privilege);
    }

    /**
     * @author  goFrendiAsgard
     * @param  identity, password
     * @desc  login
     */
    protected function cms_do_login($identity, $password) {
        return $this->CMS_Model->cms_do_login($identity, $password);
    }

    /**
     * @author  goFrendiAsgard
     * @param
     * @desc  logout
     */
    protected function cms_do_logout() {
        $this->CMS_Model->cms_do_logout();
    }

    /**
     * @author  goFrendiAsgard
     * @param  user_name, email, real_name, password
     * @desc  register
     */
    protected function cms_do_register($user_name, $email, $real_name, $password) {
        return $this->CMS_Model->cms_do_register($user_name, $email, $real_name, $password);
    }

    /**
     * @author  goFrendiAsgard
     * @param  user_name, email, real_name, password
     * @desc  change profile
     */
    protected function cms_do_change_profile($user_name, $email, $real_name, $password) {
        return $this->CMS_Model->cms_do_change_profile($user_name, $email, $real_name, $password);
    }

    /**
     * @author  goFrendiAsgard
     * @param  view_url, data, navigation_name, privilege_required, custom_theme, custom_layout return_as_string
     * @desc  replace $this->load->view. This method will also load header, menu etc except there is _only_content parameter via GET or POST
     */
    protected function view($view_url, $data = NULL, $navigation_name = NULL, $privilege_required = NULL, $custom_theme = NULL, $custom_layout = NULL, $return_as_string = FALSE) {
        /**
        $this->output->cache(1);
        $this->template->set_cache(1); 
         * 
         */       
        
        $result = NULL;

        $this->load->helper('url');

        //it can be called as $this->view('view_path', $data, true);
        //or $this->view('view_path', $data, $navigation_name, true);
        if (is_bool($navigation_name) && !isset($privilege_required) && !isset($custom_theme) && !isset($custom_layout)) {
            $return_as_string = $navigation_name;
            $navigation_name = NULL;
        } else if (is_bool($privilege_required) && !isset($custom_theme) && !isset($custom_layout)) {
            $return_as_string = $privilege_required;
            $privilege_required = NULL;
        } else if (is_bool($custom_theme) && !isset($custom_layout)) {
            $return_as_string = $custom_theme;
            $custom_theme = NULL;
        } else if (is_bool($custom_layout)) {
            $return_as_string = $custom_layout;
            $custom_layout = NULL;
        }

        //if no navigation_name provided, just guess it through the url
        if (!isset($navigation_name)) {
            $uriString = $this->uri->uri_string();
            $SQL = "SELECT navigation_name FROM cms_navigation WHERE url = '" . addslashes($uriString) . "'";
            $query = $this->db->query($SQL);
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $navigation_name = $row->navigation_name;
            }
        }

        //check allowance
        if (!isset($navigation_name) || $this->cms_allow_navigate($navigation_name)) {
            if (!isset($privilege_required)) {
                $allowed = true;
            } else if (count($privilege_required) > 0) {
                //privilege_required is array
                $allowed = true;
                foreach ($privilege_required as $privilege) {
                    $allowed = $allowed && $this->cms_have_privilege($privilege);
                    if (!$allowed)
                        break;
                }
            }else {//privilege_required is string
                $allowed = $this->cms_have_privilege($privilege_required);
            }
        } else {
            $allowed = false;
        }

        //if allowed then show, else don't
        if ($allowed) {
            if ((isset($_REQUEST['_only_content']))) {
                $result = $this->load->view($view_url, $data, $return_as_string);
            } else {
                //get configuration
                $data_partial['site_name'] = $this->cms_get_config('site_name');
                $data_partial['site_slogan'] = $this->cms_get_config('site_slogan');
                $data_partial['site_footer'] = $this->cms_get_config('site_footer');
                $data_partial['site_theme'] = $this->cms_get_config('site_theme');
                $data_partial['site_logo'] = $this->cms_get_config('site_logo');
                $data_partial['site_favicon'] = $this->cms_get_config('site_favicon');

                //get navigations
                $navigations = $this->cms_navigations();
                $navigation_path = $this->cms_get_navigation_path($navigation_name);
                $data_partial['navigations'] = $navigations;
                $data_partial['navigation_path'] = $navigation_path;

                //get widget
                $widget = $this->cms_widgets();
                $data_partial['widget'] = $widget;

                //get user name
                $data_partial['user_name'] = $this->cms_username();

                //get quicklinks
                $data_partial['quicklinks'] = $this->cms_quicklinks();

                //if $custom_theme defined, use it as theme
                //else use site_theme configuration
                if (isset($custom_theme)) {
                    $theme = $custom_theme;
                } else {
                    $theme = $data_partial['site_theme'];
                }

                //if $custom_layout defined, use it as layout
                //else look at user agent
                if (isset($custom_layout)) {
                    $layout = $custom_layout;
                } else {
                    $layout = $this->is_mobile ? 'mobile' : 'default';
                }

                //let's decide the real theme and layout used by their availability
                if (!$this->cms_themes_okay($theme, $layout)) {
                    if ($layout == 'mobile' && $this->cms_themes_okay($theme, 'default')) {
                        $layout = 'default';
                    } else {
                        $theme = 'neutral';
                    }
                }

                //re-adjust $data_partial['site_theme']
                $data_partial['site_theme'] = $theme;

                //backend template
                $cms_userid = $this->cms_userid();
                if (isset($cms_userid) && $cms_userid) {
                    if ($this->cms_themes_okay($theme, $layout . '_backend')) {
                        $layout = $layout . '_backend';
                    }
                }

                //set layout and partials                
                $this->template->set_theme($theme);
                $this->template->set_layout($layout);
                $this->template->set_partial('header', 'partials/' . $layout . '/header.php', $data_partial);
                $this->template->set_partial('left', 'partials/' . $layout . '/left.php', $data_partial);
                $this->template->set_partial('footer', 'partials/' . $layout . '/footer.php', $data_partial);
                $this->template->set_partial('right', 'partials/' . $layout . '/right.php', $data_partial);
                $this->template->set_partial('navigation_path', 'partials/' . $layout . '/navigation_path.php', $data_partial);

                $result = $this->template->build($view_url, $data, $return_as_string);
            }
        } else {
            //if user not authorized, show login, save current url
            $this->load->library('session');
            $uriString = $this->uri->uri_string();

            $this->session->set_flashdata('old_url', $uriString);

            redirect('main/login');
        }
        return $result;
    }

    private function cms_themes_okay($theme, $layout) {
        return
                is_file('themes/' . $theme . '/views/layouts/' . $layout . '.php') &&
                is_file('themes/' . $theme . '/views/partials/' . $layout . '/footer.php') &&
                is_file('themes/' . $theme . '/views/partials/' . $layout . '/header.php') &&
                is_file('themes/' . $theme . '/views/partials/' . $layout . '/navigation_path.php') &&
                is_file('themes/' . $theme . '/views/partials/' . $layout . '/left.php') &&
                is_file('themes/' . $theme . '/views/partials/' . $layout . '/right.php');
    }

    /**
     * @author  goFrendiAsgard
     * @param  module_name
     * @desc  checked if module installed
     */
    protected function cms_is_module_installed($module_name) {
        return $this->CMS_Model->cms_is_module_installed($module_name);
    }

    /**
     * @author  goFrendiAsgard
     * @param
     * @desc  get module list
     */
    protected function cms_get_module_list() {
        return $this->CMS_Model->cms_get_module_list();
    }

    /**
     * @author  goFrendiAsgard
     * @param
     * @desc  get module list
     */
    protected function cms_get_layout_list() {
        return $this->CMS_Model->cms_get_layout_list();
    }

    /**
     * @author  goFrendiAsgard
     * @param  identity
     * @desc  generate activation code,
     */
    protected function cms_generate_activation_code($identity) {
        return $this->CMS_Model->cms_generate_activation_code($identity);
    }

    /**
     * @author  goFrendiAsgard
     * @param  activation_code, new_password
     * @desc  generate_activation_code
     */
    protected function cms_forgot_password($activation_code, $new_password) {
        return $this->CMS_Model->cms_forgot_password($activation_code, $new_password);
    }

    /**
     * @author  goFrendiAsgard
     * @param  from_address, from_name, to_address, subject, message
     * @desc  generate activation code,
     */
    protected function cms_send_email($from_address, $from_name, $to_address, $subject, $message) {
        return $this->CMS_Model->cms_send_email($from_address, $from_name, $to_address, $subject, $message);
    }

    /**
     * @author  goFrendiAsgard
     * @param  activation_code
     * @desc  valid_activation_code
     */
    protected function cms_valid_activation_code($activation_code) {
        return $this->CMS_Model->cms_valid_activation_code($activation_code);
    }

    /**
     * @author  goFrendiAsgard
     * @param string $name config name
     * @param string $value new value
     * @param string $description description
     * @return void
     * @desc  set config
     */
    protected function cms_set_config($name, $value, $description = NULL) {
        return $this->CMS_Model->cms_set_config($name, $value, $description);        
    }

    /** 
     * @author  goFrendiAsgard
     * @param  string $name
     * @return void
     * @desc  unset config
     */
    protected function cms_unset_config($name) {
        return $this->CMS_Model->cms_unset_config($name);
    }

    /**
     * @author  goFrendiAsgard
     * @param  string $name
     * @return string
     * @desc  get config
     */
    protected function cms_get_config($name) {
        return $this->CMS_Model->cms_get_config($name);
    }

    /**
     * @author goFrendiAsgard
     * @param string $key, string $module
     * @return string
     * @desc get language
     */
    protected function cms_lang($key, $module =NULL) {
        return $this->CMS_Model->cms_lang($key, $module);
    }
    
     /**
     * @author goFrendiAsgard
     * @param string $value
     * @return string
     * @desc parse keyword like @site_url and @base_url 
     */
    public function cms_parse_keyword($value) {
        return $this->CMS_Model->cms_parse_keyword($value);
    }

}