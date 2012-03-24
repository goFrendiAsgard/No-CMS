<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Core functions of No-CMS
 *
 * @author gofrendi
 */
class CMS_Model extends CI_Model {

    public function __construct() {
        parent::__construct();

        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->helper('form');
        $this->load->library('user_agent');
        $this->load->library('session');
        $this->load->library('form_validation');
        /* ------------------ */
    }
    
    /**
     * @author goFrendiAsgard
     * @param type $key
     * @param type $value
     * @return mixed
     * @desc if value specified, this will set CI session, else it will return CI session 
     */
    public function cms_ci_session($key, $value = NULL) {
        if (isset($value)) {
            $this->session->set_userdata($key, $value);
        }
        return $this->session->userdata($key);
    }

    /**
     * @author  goFrendiAsgard
     * @param  string $key   
     * @desc  delete a CI session 
     */
    public function cms_unset_ci_session($key) {
        $this->session->unset_userdata($key);
    }

    /**
     * @author  goFrendiAsgard
     * @param  string $username  
     * @return mixed
     * @desc  if username specified, this will set cms_username session, else, it will return cms_username  
     */
    public function cms_username($username = NULL) {
        return $this->cms_ci_session('cms_username', $username);
    }

    /**
     * @author  goFrendiAsgard
     * @param  int $userid
     * @desc  if userid specified, this will set cms_userid session, else, it will return cms_userid  
     */
    public function cms_userid($userid = NULL) {
        return $this->cms_ci_session('cms_userid', $userid);
    }

    /**
     * @author  goFrendiAsgard
     * @param  parent_id, max_menu_depth
     * @desc  return navigation child if parent_id specified, else it will return root navigation
     */
    public function cms_navigations($parent_id = NULL, $max_menu_depth = NULL) {
        $user_name = $this->cms_username();
        $user_id = $this->cms_userid();
        $not_login = !$user_name ? "TRUE" : "FALSE";
        $login = $user_name ? "TRUE" : "FALSE";
        $super_user = $user_id == 1 ? "TRUE" : "FALSE";

        //get max_menu_depth from configuration
        if (!isset($parent_id)) {
            $max_menu_depth = $this->cms_get_config('max_menu_depth');
        }

        if ($max_menu_depth > 0) {
            $max_menu_depth--;
        } else {
            return array();
        }

        $where_is_root = !isset($parent_id) ? "(parent_id IS NULL)" : "parent_id = '" . addslashes($parent_id) . "'";
        $query = $this->db->query(
                "SELECT navigation_id, navigation_name, is_static, title, description, url, active,
                    (
                        (authorization_id = 1) OR
                        (authorization_id = 2 AND $not_login) OR
                        (authorization_id = 3 AND $login) OR
                        (
                            (authorization_id = 4 AND $login) AND 
                            (
                                (SELECT COUNT(*) FROM cms_group_user AS gu WHERE gu.group_id=1 AND gu.user_id ='" . addslashes($user_id) . "')>0
                                    OR $super_user OR
                                (SELECT COUNT(*) FROM cms_group_navigation AS gn
                                    WHERE 
                                        gn.navigation_id=n.navigation_id AND
                                        gn.group_id IN 
                                            (SELECT group_id FROM cms_group_user WHERE user_id = '" . addslashes($user_id) . "')
                                )>0
                            )
                        )
                    ) AS allowed
                FROM cms_navigation AS n WHERE
                    $where_is_root ORDER BY n.index"
        );
        $result = array();
        foreach ($query->result() as $row) {
            $children = $this->cms_navigations($row->navigation_id, $max_menu_depth);
            $have_allowed_children = false;
            foreach($children as $child){
                if($child["allowed"]){
                    $have_allowed_children = true;
                    break;
                }
            }
            $result[] = array(
                "navigation_id" => $row->navigation_id,
                "navigation_name" => $row->navigation_name,
                "title" => $this->cms_lang($row->title),
                "description" => $row->description,
                "url" => $row->url,
                "is_static" => $row->is_static,
                "active"=> $row->active,
                "child" => $children,
                "allowed" => $row->allowed,
                "have_allowed_children" => $have_allowed_children
            );
        }
        return $result;
    }

    /**
     * @author goFrendiAsgard
     * @desc return quick links
     */
    public function cms_quicklinks() {
        $user_name = $this->cms_username();
        $user_id = $this->cms_userid();
        $not_login = !$user_name ? "TRUE" : "FALSE";
        $login = $user_name ? "TRUE" : "FALSE";
        $super_user = $user_id == 1 ? "TRUE" : "FALSE";

        $query = $this->db->query(
                "SELECT q.navigation_id, navigation_name, is_static, title, description, url 
                        FROM 
                        	cms_navigation AS n,
                        	cms_quicklink AS q 
                        WHERE
                        	(
                        		q.navigation_id = n.navigation_id
                        	) 
                        	AND
                            (
                                (authorization_id = 1) OR
                                (authorization_id = 2 AND $not_login) OR
                                (authorization_id = 3 AND $login) OR
                                (
                                    (authorization_id = 4 AND $login) AND 
                                    (
                                        (SELECT COUNT(*) FROM cms_group_user AS gu WHERE gu.group_id=1 AND gu.user_id ='" . addslashes($user_id) . "')>0
                                            OR $super_user OR
                                        (SELECT COUNT(*) FROM cms_group_navigation AS gn
                                            WHERE 
                                                gn.navigation_id=n.navigation_id AND
                                                gn.group_id IN 
                                                    (SELECT group_id FROM cms_group_user WHERE user_id = '" . addslashes($user_id) . "')
                                        )>0
                                    )
                                )
                            ) ORDER BY q.index"
        );
        $result = array();
        foreach ($query->result() as $row) {
            $result[] = array(
                "navigation_id" => $row->navigation_id,
                "navigation_name" => $row->navigation_name,
                "title" => $this->cms_lang($row->title),
                "description" => $row->description,
                "url" => $row->url,
                "is_static" => $row->is_static
            );
        }
        return $result;
    }

    /**
     * @author  goFrendiAsgard
     * @param  parent_id, max_menu_depth
     * @desc  return navigation child if parent_id specified, else it will return root navigation
     */
    public function cms_widgets($slug = NULL) {
        $user_name = $this->cms_username();
        $user_id = $this->cms_userid();
        $not_login = !$user_name ? "TRUE" : "FALSE";
        $login = $user_name ? "TRUE" : "FALSE";
        $super_user = $user_id == 1 ? "TRUE" : "FALSE";

        if (isset($slug)) {
            $whereSlug = "slug = '" . addcslashes($slug) . "'";
        } else {
            $whereSlug = "1=1";
        }

        $query = $this->db->query(
                "SELECT widget_id, widget_name, is_static, title, description, url, slug 
                FROM cms_widget AS w WHERE
                    (                        
                        (authorization_id = 1) OR
                        (authorization_id = 2 AND $not_login) OR
                        (authorization_id = 3 AND $login) OR
                        (
                            (authorization_id = 4 AND $login) AND 
                            (
                                (SELECT COUNT(*) FROM cms_group_user AS gu WHERE gu.group_id=1 AND gu.user_id ='" . addslashes($user_id) . "')>0
                                    OR $super_user OR
                                (SELECT COUNT(*) FROM cms_group_widget AS gw
                                    WHERE 
                                        gw.widget_id=w.widget_id AND
                                        gw.group_id IN 
                                            (SELECT group_id FROM cms_group_user WHERE user_id = '" . addslashes($user_id) . "')
                                )>0
                            )
                        )
                    ) AND active=1 AND $whereSlug ORDER BY w.index"
        );
        $result = array();
        foreach ($query->result() as $row) {
            $result[] = array(
                "widget_id" => $row->widget_id,
                "widget_name" => $row->widget_name,
                "title" => $this->cms_lang($row->title),
                "description" => $row->description,
                "is_static" => $row->is_static,
                "url" => $row->url,
                "slug" => $row->slug
            );
        }
        return $result;
    }

    /**
     * @author  goFrendiAsgard
     * @param  navigation_name
     * @desc  return parent of navigation_name's detail, only used for get_navigation_path
     */
    private function get_navigation_parent($navigation_name) {
        if (!$navigation_name)
            return false;
        $query = $this->db->query(
                "SELECT navigation_id, navigation_name, title, description, url  
                    FROM cms_navigation 
                    WHERE navigation_id = (
                        SELECT parent_id FROM cms_navigation
                        WHERE navigation_name = '" . addslashes($navigation_name) . "'
                    )"
        );
        if ($query->num_rows == 0)
            return false;
        else {
            foreach ($query->result() as $row) {
                return array(
                    "navigation_name" => $row->navigation_name,
                    "title" => $this->cms_lang($row->title),
                    "description" => $row->description,
                    "url" => $row->url
                );
            }
        }
    }

    /**
     * @author  goFrendiAsgard
     * @param  navigation_name
     * @desc  return navigation detail, only used for get_navigation_path
     */
    private function get_navigation($navigation_name) {
        if (!$navigation_name)
            return false;
        $query = $this->db->query(
                "SELECT navigation_id, navigation_name, title, description, url 
                    FROM cms_navigation 
                    WHERE navigation_name = '" . addslashes($navigation_name) . "'"
        );
        if ($query->num_rows == 0)
            return false;
        else {
            foreach ($query->result() as $row) {
                return array(
                    "navigation_name" => $row->navigation_name,
                    "title" => $this->cms_lang($row->title),
                    "description" => $row->description,
                    "url" => $row->url
                );
            }
        }
    }

    /**
     * @author  goFrendiAsgard
     * @param  navigation_name
     * @desc  return navigation path, used for layout
     */
    public function cms_get_navigation_path($navigation_name = NULL) {
        if (!isset($navigation_name))
            return array();
        $result = array($this->get_navigation($navigation_name));
        while ($parent = $this->get_navigation_parent($navigation_name)) {
            $result[] = $parent;
            $navigation_name = $parent["navigation_name"];
        }
        //result should be in reverse order
        for ($i = 0; $i < ceil(count($result) / 2); $i++) {
            $temp = $result[$i];
            $result[$i] = $result[count($result) - 1 - $i];
            $result[count($result) - 1 - $i] = $temp;
        }
        return $result;
    }

    /**
     * @author  goFrendiAsgard
     * @desc  return privileges of current user
     */
    public function cms_privileges() {
        $user_name = $this->cms_username();
        $user_id = $this->cms_userid();
        $not_login = !isset($user_name) ? "TRUE" : "FALSE";
        $login = isset($user_name) ? "TRUE" : "FALSE";
        $super_user = $user_id == 1 ? "TRUE" : "FALSE";

        $query = $this->db->query(
                "SELECT privilege_name, title, description 
                FROM cms_privilege AS p WHERE
                    (authorization_id = 1) OR
                    (authorization_id = 2 AND $not_login) OR
                    (authorization_id = 3 AND $login) OR
                    (
                        (authorization_id = 4 AND $login AND 
                        (
                            (SELECT COUNT(*) FROM cms_group_user AS gu WHERE gu.group_id=1 AND gu.user_id ='" . addslashes($user_id) . "')>0
                                OR $super_user OR
                            (SELECT COUNT(*) FROM cms_group_privilege AS gp
                                WHERE 
                                    gp.privilege_id=p.privilege_id AND
                                    gp.group_id IN 
                                        (SELECT group_id FROM cms_group_user WHERE user_id = '" . addslashes($user_id) . "')
                            )>0)
                        )
                    )"
        );
        $result = array();
        foreach ($query->result() as $row) {
            $result[] = array(
                "privilege_name" => $row->privilege_name,
                "title" => $row->title,
                "description" => $row->description
            );
        }
        return $result;
    }

    /**
     * @author  goFrendiAsgard
     * @param  navigation, navigations
     * @desc  only used in allow_navigate
     */
    private function _allow_navigate($navigation, $navigations = NULL) {        
        if (!isset($navigations))
            $navigations = $this->cms_navigations();
        for ($i=0; $i<count($navigations); $i++) {
            if ($navigation == $navigations[$i]["navigation_name"] && $navigations[$i]["allowed"]==1){
                return true;            
            }else if ($this->_allow_navigate($navigation, $navigations[$i]["child"])){  
                return true;
            }
        }
        return false;
    }

    /**
     * @author  goFrendiAsgard
     * @param  navigation
     * @desc  check if user authorized to navigate into a page specified in parameter
     */
    public function cms_allow_navigate($navigation) {
        return $this->_allow_navigate($navigation);
    }

    /**
     * @author  goFrendiAsgard
     * @param  privilege
     * @desc  check if user have privilege specified in parameter
     */
    public function cms_have_privilege($privilege) {
        $privileges = $this->cms_privileges();
        for ($i = 0; $i < count($privileges); $i++) {
            if ($privilege == $privileges[$i]["privilege_name"])
                return true;
        }
        return false;
    }

    /**
     * @author  goFrendiAsgard
     * @param  identity, password
     * @desc  login
     */
    public function cms_do_login($identity, $password) {
        $query = $this->db->query(
                "SELECT user_id, user_name FROM cms_user WHERE
                    (user_name = '" . $identity . "' OR email = '" . $identity . "') AND
                    password = '" . md5($password) . "' AND
                    active = TRUE"
        );
        foreach ($query->result() as $row) {
            $this->cms_username($row->user_name);
            $this->cms_userid($row->user_id);
            return true;
        }
        return false;
    }

    /**
     * @author  goFrendiAsgard
     * @param  
     * @desc  logout
     */
    public function cms_do_logout() {
        $this->cms_unset_ci_session('cms_username');
        $this->cms_unset_ci_session('cms_userid');
    }

    /**
     * @author  goFrendiAsgard
     * @param  user_name, email, real_name, password
     * @desc  register
     */
    public function cms_do_register($user_name, $email, $real_name, $password) {
        $data = array(
            "user_name" => $user_name,
            "email" => $email,
            "real_name" => $real_name,
            "password" => md5($password),
            "active" => TRUE
        );
        $this->db->insert('cms_user', $data);
    }

    /**
     * @author  goFrendiAsgard
     * @param  user_name, email, real_name, password
     * @desc  change profile
     */
    public function cms_do_change_profile($user_name, $email, $real_name, $password) {
        $data = array(
            "user_name" => $user_name,
            "email" => $email,
            "real_name" => $real_name,
            "password" => md5($password),
            "active" => 1
        );
        $where = array(
            "user_name" => $user_name
        );
        $this->db->update('cms_user', $data, $where);
    }

    /**
     * @author  goFrendiAsgard
     * @param  module_name
     * @desc  checked if module installed
     */
    public function cms_is_module_installed($module_name) {
        $query = $this->db->query(
                "SELECT count(*) as reccount FROM cms_module WHERE module_name = '" . addslashes($module_name) . "'");
        foreach ($query->result() as $row) {
            if ($row->reccount > 0) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * @author  goFrendiAsgard
     * @param  
     * @desc  get module list
     */
    public function cms_get_module_list() {
        $this->load->helper('directory');
        $directories = directory_map('modules', 1);
        $module = array();
        foreach ($directories as $directory) {
            if (!is_dir('modules/' . $directory))
                continue;

            //temporary module_name = directory_name
            $module_name = $directory;

            $module[] = array(
                "path" => $directory,
                "installed" => $this->cms_is_module_installed($module_name)
            );
        }
        return $module;
    }

    /**
     * @author  goFrendiAsgard
     * @param  
     * @desc  get layout list
     */
    public function cms_get_layout_list() {
        $this->load->helper('directory');
        $directories = directory_map('themes', 1);
        $module = array();
        foreach ($directories as $directory) {
            if (!is_dir('themes/' . $directory))
                continue;

            $layout_name = $directory;

            $module[] = array(
                "path" => $directory,
                "used" => $this->cms_get_config('site_theme') == $layout_name
            );
        }
        return $module;
    }

    /**
     * @author  goFrendiAsgard
     * @param  identity 
     * @desc  generate activation code, 
     */
    public function cms_generate_activation_code($identity) {
        $query = $this->db->query(
                "SELECT user_name, real_name, user_id, email FROM cms_user WHERE
                    (user_name = '" . $identity . "' OR email = '" . $identity . "') AND
                    active = TRUE"
        );
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $user_id = $row->user_id;
            $email_to_address = $row->email;
            $user_name = $row->user_name;
            $real_name = $row->real_name;
            $activation_code = random_string();

            //update, add activation_code
            $data = array("activation_code" => md5($activation_code));
            $where = array("user_id" => $user_id);
            $this->db->update('cms_user', $data, $where);
            $this->load->library('email');

            //send activation email to user
            $email_from_address = $this->cms_get_config('cms_email_address');
            $email_from_name = $this->cms_get_config('cms_email_name');
            $email_subject = $this->cms_get_config('cms_email_forgot_subject');
            $email_message = $this->cms_get_config('cms_email_forgot_message');
            $activation_link = site_url('main/forgot/' . $activation_code);

            $email_message = str_replace('@realname', $real_name, $email_message);
            $email_message = str_replace('@activation_link', $activation_link, $email_message);

            //send email to user
            $this->cms_send_email($email_from_address, $email_from_name, $email_to_address, $email_subject, $email_message);
            return true;
        }
        return false;
    }

    /**
     * @author  goFrendiAsgard
     * @param  activation_code, new_password
     * @desc  generate_activation_code
     */
    public function cms_forgot_password($activation_code, $new_password) {
        $query = $this->db->query(
                "SELECT user_id FROM cms_user WHERE
                    (activation_code = '" . md5($activation_code) . "') AND
                    active = TRUE"
        );
        foreach ($query->result() as $row) {
            $user_id = $row->user_id;
            $data = array(
                "password" => md5($new_password),
                "activation_code" => NULL
            );
            $where = array("user_id" => $user_id);
            $this->db->update('cms_user', $data, $where);
        }
    }

    /**
     * @author  goFrendiAsgard
     * @param  from_address, from_name, to_address, subject, message
     * @desc  send email 
     */
    public function cms_send_email($from_address, $from_name, $to_address, $subject, $message) {
        $this->load->library('email');
        //send email to user
        $config['useragent'] = $this->cms_get_config('cms_email_useragent');
        $config['protocol'] = $this->cms_get_config('cms_email_protocol');
        $config['mailpath'] = $this->cms_get_config('cms_email_mailpath');
        $config['smtp_host'] = $this->cms_get_config('cms_email_smtp_host');
        $config['smtp_user'] = $this->cms_get_config('cms_email_smtp_user');
        $config['smtp_pass'] = $this->cms_get_config('cms_email_smtp_pass');
        $config['smtp_port'] = $this->cms_get_config('cms_email_smtp_port');
        $config['smtp_timeout'] = $this->cms_get_config('cms_email_smtp_timeout');
        $config['wordwrap'] = (boolean) $this->cms_get_config('cms_email_wordwrap');
        $config['wrapchars'] = $this->cms_get_config('cms_email_wrapchars');
        $config['mailtype'] = $this->cms_get_config('cms_email_mailtype');
        $config['charset'] = $this->cms_get_config('cms_email_charset');
        $config['validate'] = (boolean) $this->cms_get_config('cms_email_validate');
        $config['priority'] = $this->cms_get_config('cms_email_priority');
        $config['crlf'] = $this->cms_get_config('cms_email_crlf');
        $config['newline'] = $this->cms_get_config('cms_email_newline');
        $config['bcc_batch_mode'] = (boolean) $this->cms_get_config('cms_email_bcc_batch_mode');
        $config['bcc_batch_size'] = $this->cms_get_config('cms_email_bcc_batch_size');

        $this->email->initialize($config);
        $this->email->from($from_address, $from_name);
        $this->email->to($to_address);
        $this->email->subject($subject);
        $this->email->message($message);

        $this->email->send();
        //echo $this->email->print_debugger();
    }

    /**
     * @author  goFrendiAsgard
     * @param  activation_code 
     * @desc  valid_activation_code
     */
    public function cms_valid_activation_code($activation_code) {
        $query = $this->db->query(
                "SELECT activation_code FROM cms_user WHERE
                    (activation_code = '" . md5($activation_code) . "') AND
                    (activation_code IS NOT NULL) AND
                    active = TRUE"
        );
        if ($query->num_rows() > 0)
            return true;
        else
            return false;
    }

    /**
     * @author  goFrendiAsgard
     * @param string $name config name
     * @param string $value new value
     * @param string $description description
     * @return void
     * @desc  set config
     */
    public function cms_set_config($name, $value, $description = NULL) {
        $query = $this->db->query(
                "SELECT config_id FROM cms_config WHERE
                    config_name = '" . addslashes($name) . "'"
        );
        if ($query->num_rows() > 0) {
            $data = array("value" => $value);
            if (isset($description))
                $data['description'] = $description;
            $where = array("config_name" => $name);
            $this->db->update("cms_config", $data, $where);
        }
        else {
            $data = array(
                "value" => $value,
                "config_name" => $name
            );
            if (isset($description))
                $data['description'] = $description;
            $this->db->insert("cms_config", $data);
        }
    }

    /**
     * @author  goFrendiAsgard
     * @param  string $name
     * @return void
     * @desc  unset config
     */
    public function cms_unset_config($name) {
        $where = array("config_name" => $name);
        $query = $this->db->delete("cms_config", $where);
    }

    /**
     * @author  goFrendiAsgard
     * @param  string $name
     * @return string
     * @desc  get config
     */
    public function cms_get_config($name) {
        $query = $this->db->query(
                "SELECT `value` FROM cms_config WHERE
                    config_name = '" . addslashes($name) . "'"
        );
        $row = $query->row();
        $value = $row->value;
        $value = $this->cms_parse_keyword($value);
        return $value;
    }

    /**
     * @author goFrendiAsgard
     * @param string $key
     * @param string $module
     * @return string
     * @desc get language
     */
    public function cms_lang($key, $module = NULL) {
        $language = $this->cms_get_config('site_language');
        if (!isset($module)) {
            $language_file = "assets/nocms/languages/$language.php";
        } else {
            $language_file = "modules/$module/assets/languages/$language.php";
        }

        if (file_exists($language_file)) {
            include($language_file);

            if (isset($lang[$key])) {
                return $lang[$key];
            } else {
                return $key;
            }
        } else {
            return $key;
        }
    }

    /**
     * @author goFrendiAsgard
     * @param string $value
     * @return string
     * @desc parse keyword like @site_url and @base_url 
     */
    public function cms_parse_keyword($value) {
        $value = str_replace('@site_url', site_url(), $value);
        $value = str_replace('@base_url', base_url(), $value);
        return $value;
    }

}

?>
