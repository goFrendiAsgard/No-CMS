<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Base Model of No-CMS
 *
 * @author gofrendi
 */
class CMS_Model extends CI_Model
{
    private $__cms_model_properties;

    public function __construct()
    {
        parent::__construct();

        // load helpers and libraries
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->helper('form');
        $this->load->library('user_agent');
        $this->load->library('session');
        $this->load->helper('cms_helper');
        $this->load->library('form_validation');
        $this->load->database();

        // PHP 5.3 ask for timezone, and throw a warning whenever it is not available
        // so, just give this one :)
        $timezone = @date_default_timezone_get();
        if (!isset($timezone) || $timezone == '') {
            $timezone = @ini_get('date.timezone');
        }
        if (!isset($timezone) || $timezone == '') {
            $timezone = 'UTC';
        }
        date_default_timezone_set($timezone);

        // accessing file is faster than accessing database
        // but I think accessing variable is faster than both of them
        $this->__cms_model_properties = array(
            'session' => array(),
            'language_dictionary' => array(),
            'config' => array()
        );

    }

    /**
     * @author goFrendiAsgard
     * @param  string $table_name
     * @return string
     * @desc   return good table name
     */
    public function cms_complete_table_name($table_name){
        $module_path = $this->cms_module_path();
        if($module_path == 'main' or $module_path == ''){
            return cms_table_name($table_name);
        }else{
            return cms_module_table_name($module_path, $table_name);
        }
    }

    /**
     * @author goFrendiAsgard
     * @param  string $navigation_name
     * @return string
     * @desc   return good table name
     */
    public function cms_complete_navigation_name($navigation_name){
        $module_path = $this->cms_module_path();
        if($module_path == 'main' or $module_path == ''){
            return $navigation_name;
        }else{
            return cms_module_navigation_name($module_path, $navigation_name);
        }
    }

    /**
     * @author goFrendiAsgard
     * @param  string $key
     * @param  mixed $value
     * @return mixed
     * @desc   if value specified, this will set CI_Session["key"], else it will return CI_session["key"]
     */
    public function cms_ci_session($key, $value = NULL)
    {
        if (isset($value)) {
            $this->session->set_userdata($key, $value);
            $this->__cms_model_properties['session'][$key] = $value;
        }
        // add to __cms_model_properties if not exists
        if (!isset($this->__cms_model_properties['session'][$key])) {
            $this->__cms_model_properties['session'][$key] = $this->session->userdata($key);
        }
        return $this->__cms_model_properties['session'][$key];
    }

    /**
     * @author goFrendiAsgard
     * @param  string $key
     * @desc   unset CI_session["key"]
     */
    public function cms_unset_ci_session($key)
    {
        $this->session->unset_userdata($key);
        unset($this->__cms_model_properties['session'][$key]);
    }

    /**
     * @author goFrendiAsgard
     * @param  string $user_name
     * @return mixed
     * @desc   set or get CI_Session["cms_user_name"]
     */
    public function cms_user_name($user_name = NULL)
    {
        return $this->cms_ci_session('cms_user_name', $user_name);
    }

    /**
     * @author goFrendiAsgard
     * @param  string $real_name
     * @return mixed
     * @desc   set or get CI_Session["cms_user_real_name"]
     */
    public function cms_user_real_name($real_name = NULL)
    {
        return $this->cms_ci_session('cms_user_real_name', $real_name);
    }

    /**
     * @author goFrendiAsgard
     * @param  string $email
     * @return mixed
     * @desc   set or get CI_Session["cms_user_email"]
     */
    public function cms_user_email($email = NULL)
    {
        return $this->cms_ci_session('cms_user_email', $email);
    }

    /**
     * @author goFrendiAsgard
     * @param  int $user_id
     * @desc   set or get CI_Session["cms_user_id"]
     */
    public function cms_user_id($user_id = NULL)
    {
        return $this->cms_ci_session('cms_user_id', $user_id);
    }

    /**
     * @author  goFrendiAsgard
     * @param   int parent_id
     * @param   int max_menu_depth
     * @desc    return navigation child if parent_id specified, else it will return root navigation
     *           the max depth of menu is depended on max_menud_depth
     */
    public function cms_navigations($parent_id = NULL, $max_menu_depth = NULL)
    {
        $user_name  = $this->cms_user_name();
        $user_id    = $this->cms_user_id();
        $not_login  = !$user_name ? "TRUE" : "FALSE";
        $login      = $user_name ? "TRUE" : "FALSE";
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
        $query         = $this->db->query("SELECT navigation_id, navigation_name, is_static, title, description, url, active,
                	(
                        (authorization_id = 1) OR
                        (authorization_id = 2 AND $not_login) OR
                        (authorization_id = 3 AND $login) OR
                        (
                            (authorization_id = 4 AND $login) AND
                            (
                                (SELECT COUNT(*) FROM ".cms_table_name('main_group_user')." AS gu WHERE gu.group_id=1 AND gu.user_id ='" . addslashes($user_id) . "')>0
                                    OR $super_user OR
                                (SELECT COUNT(*) FROM ".cms_table_name('main_group_navigation')." AS gn
                                    WHERE
                                        gn.navigation_id=n.navigation_id AND
                                        gn.group_id IN
                                            (SELECT group_id FROM ".cms_table_name('main_group_user')." WHERE user_id = '" . addslashes($user_id) . "')
                                )>0
                            )
                        )
					) AS allowed
                FROM ".cms_table_name('main_navigation')." AS n WHERE
                    $where_is_root ORDER BY n.index");
        $result        = array();
        foreach ($query->result() as $row) {
            $children              = $this->cms_navigations($row->navigation_id, $max_menu_depth);
            $have_allowed_children = false;
            foreach ($children as $child) {
                if ($child["allowed"]) {
                    $have_allowed_children = true;
                    break;
                }
            }
            if ((!isset($row->url) || $row->url == '') && $row->is_static == 1) {
                $url = site_url('main/static_page/' . $row->navigation_name);
            } else {
                if (strpos(strtoupper($row->url), 'HTTP://') !== FALSE || strpos(strtoupper($row->url), 'HTTPS://') !== FALSE) {
                    $url = $row->url;
                } else {
                    $url = site_url($row->url);
                }
            }
            $result[] = array(
                "navigation_id" => $row->navigation_id,
                "navigation_name" => $row->navigation_name,
                "title" => $this->cms_lang($row->title),
                "description" => $row->description,
                "url" => $url,
                "is_static" => $row->is_static,
                "active" => $row->active,
                "child" => $children,
                "allowed" => $row->allowed,
                "have_allowed_children" => $have_allowed_children
            );
        }
        return $result;
    }

    /**
     * @author goFrendiAsgard
     * @return mixed
     * @desc   return quick links
     */
    public function cms_quicklinks()
    {
        $user_name  = $this->cms_user_name();
        $user_id    = $this->cms_user_id();
        $not_login  = !$user_name ? "TRUE" : "FALSE";
        $login      = $user_name ? "TRUE" : "FALSE";
        $super_user = $user_id == 1 ? "TRUE" : "FALSE";

        $query  = $this->db->query("SELECT q.navigation_id, navigation_name, is_static, title, description, url
                        FROM
                        	".cms_table_name('main_navigation')." AS n,
                        	".cms_table_name('main_quicklink')." AS q
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
                                        (SELECT COUNT(*) FROM ".cms_table_name('main_group_user')." AS gu WHERE gu.group_id=1 AND gu.user_id ='" . addslashes($user_id) . "')>0
                                            OR $super_user OR
                                        (SELECT COUNT(*) FROM ".cms_table_name('main_group_navigation')." AS gn
                                            WHERE
                                                gn.navigation_id=n.navigation_id AND
                                                gn.group_id IN
                                                    (SELECT group_id FROM ".cms_table_name('main_group_user')." WHERE user_id = '" . addslashes($user_id) . "')
                                        )>0
                                    )
                                )
                            ) ORDER BY q.index");
        $result = array();
        foreach ($query->result() as $row) {
            if ((!isset($row->url) || $row->url == '') && $row->is_static == 1) {
                $url = 'main/static_page/' . $row->navigation_name;
            } else {
                if (strpos(strtoupper($row->url), 'HTTP://') !== FALSE || strpos(strtoupper($row->url), 'HTTPS://') !== FALSE) {
                    $url = $row->url;
                } else {
                    $url = site_url($row->url);
                }
            }
            $result[] = array(
                "navigation_id" => $row->navigation_id,
                "navigation_name" => $row->navigation_name,
                "title" => $this->cms_lang($row->title),
                "description" => $row->description,
                "url" => $url,
                "is_static" => $row->is_static
            );
        }
        return $result;
    }

    /**
     * @author  goFrendiAsgard
     * @param   slug
     * @return  mixed
     * @desc    return widgets
     */
    public function cms_widgets($slug = NULL)
    {
        $user_name  = $this->cms_user_name();
        $user_id    = $this->cms_user_id();
        $not_login  = !$user_name ? "TRUE" : "FALSE";
        $login      = $user_name ? "TRUE" : "FALSE";
        $super_user = $user_id == 1 ? "TRUE" : "FALSE";
        $slug_where = isset($slug)? "slug LIKE '".addslashes($slug)."'" : "1=1";

        $query  = $this->db->query("SELECT
        			widget_id, widget_name, is_static, title,
        			description, url, slug, static_content
                FROM ".cms_table_name('main_widget')." AS w WHERE
                    (
                        (authorization_id = 1) OR
                        (authorization_id = 2 AND $not_login) OR
                        (authorization_id = 3 AND $login) OR
                        (
                            (authorization_id = 4 AND $login) AND
                            (
                                (SELECT COUNT(*) FROM ".cms_table_name('main_group_user')." AS gu WHERE gu.group_id=1 AND gu.user_id ='" . addslashes($user_id) . "')>0
                                    OR $super_user OR
                                (SELECT COUNT(*) FROM ".cms_table_name('main_group_widget')." AS gw
                                    WHERE
                                        gw.widget_id=w.widget_id AND
                                        gw.group_id IN
                                            (SELECT group_id FROM ".cms_table_name('main_group_user')." WHERE user_id = '" . addslashes($user_id) . "')
                                )>0
                            )
                        )
                    ) AND active=1 AND $slug_where ORDER BY `index`");
        $result = array();
        foreach ($query->result() as $row) {
            // generate widget content
            $content = '';
            if ($row->is_static == 1) {
                $content = $row->static_content;
            } else {
                // url
                $url = $row->url;
                // content
                $content .= '<div id="_cms_widget_' . $row->widget_id . '">';

                if (strpos(strtoupper($url), 'HTTP://') !== FALSE || strpos(strtoupper($url), 'HTTPS://') !== FALSE) {
                    $response = NULL;
                    // use CURL
                    if (in_array('curl', get_loaded_extensions())) {
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        $response = @curl_exec($ch);
                        curl_close($ch);
                    }
                    // use file get content
                    if (!isset($response)) {
                        $response = @file_get_contents($url);
                    }
                    // add the content
                    if (isset($response)) {
                        $response = preg_replace('#(href|src|action)="([^:"]*)(?:")#', '$1="' . $url . '/$2"', $response);
                        $content .= $response;
                    }
                } else {
                    $this->cms_ci_session('cms_dynamic_widget', TRUE);
                    $content .= @Modules::run($url);
                    $this->cms_unset_ci_session('cms_dynamic_widget');
                }
                $content .= '</div>';
            }
            // make widget based on slug
            $slugs = explode(',', $row->slug);
            foreach ($slugs as $slug) {
                $slug = trim($slug);
                if (!isset($result[$slug])) {
                    $result[$slug] = array();
                }
                $result[$slug][] = array(
                    "widget_id" => $row->widget_id,
                    "widget_name" => $row->widget_name,
                    "title" => $this->cms_lang($row->title),
                    "description" => $row->description,
                    "content" => $content
                );
            }

        }

        return $result;
    }

    /**
     * @author  goFrendiAsgard
     * @param   string navigation_name
     * @return  string
     * @desc    return submenu screen
     */
    public function cms_submenu_screen($navigation_name)
    {
        $submenus = array();
        if (!isset($navigation_name)) {
            $submenus = $this->cms_navigations(NULL, 1);
        } else {
            $query = $this->db->select('navigation_id')->from(cms_table_name('main_navigation'))->where('navigation_name', $navigation_name)->get();
            if ($query->num_rows() == 0) {
                return '';
            } else {
                $row           = $query->row();
                $navigation_id = $row->navigation_id;
                $submenus      = $this->cms_navigations($navigation_id, 1);
            }
        }

        $html = '<ul class="thumbnails row-fluid">';
        $module_path = $this->cms_module_path();
        $image_directories = array();
        if($module_path != ''){
           $image_directories[] = "modules/$module_path/assets/navigation_icon";
        }
        $image_directories[] = "assets/nocms/navigation_icon";
        foreach($this->cms_get_module_list() as $module_list){
            $other_module_path = $module_list['module_path'];
            $image_directories[] = "modules/$other_module_path/assets/navigation_icon";
        }
        foreach ($submenus as $submenu) {
            $navigation_name = $submenu["navigation_name"];
            $title           = $submenu["title"];
            $url             = $submenu["url"];
            $description     = $submenu["description"];
            $allowed         = $submenu["allowed"];
            if (!$allowed) continue;

            // check image in current module

            $image_file_names = array();
            $image_file_names[] = $navigation_name.'.png';
            if($module_path !== '' && $module_path !== 'main'){
                $module_prefix = cms_module_prefix($this->cms_module_path());
                $navigation_parts = explode('_', $navigation_name);
                if(count($navigation_parts)>0 && $navigation_parts[0] == $module_prefix){
                    $image_file_names[] = substr($navigation_name, strlen($module_prefix)+1).'.png';
                }
            }
            $image_file_path = '';
            foreach($image_directories as $image_directory){
                foreach($image_file_names as $image_file_name){
                    $image_file_path  = $image_directory.'/'.$image_file_name;
                    if (!file_exists($image_file_path)) {
                        $image_file_path = '';
                    }
                    if ($image_file_path !== ''){
                        break;
                    }
                }
                if ($image_file_path !== ''){
                    break;
                }
            }


            // default icon
            if ($image_file_path == '') {
                $image_file_path = 'assets/nocms/images/icons/package.png';
            }
            $html .= '<li class="well" style="width:80px!important; height:90px!important; float:left!important; list-style-type:none;">';
            $html .= '<a href="' . $url . '" style="width: 100%; height: 100%; display: block;">';
            if ($image_file_path != '') {
                $html .= '<img style="max-width:32px; max-height:32px;" src="' . base_url($image_file_path) . '" /><br /><br />';
            }
            $html .= $title . '</a>';
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    /**
     * @author  goFrendiAsgard
     * @param   string navigation_name
     * @return  mixed
     * @desc    return parent of navigation_name's detail, only used for get_navigation_path
     */
    private function __cms_get_navigation_parent($navigation_name)
    {
        if (!$navigation_name)
            return false;
        $query = $this->db->query("SELECT navigation_id, navigation_name, title, description, url
                    FROM ".cms_table_name('main_navigation')."
                    WHERE navigation_id = (
                        SELECT parent_id FROM ".cms_table_name('main_navigation')."
                        WHERE navigation_name = '" . addslashes($navigation_name) . "'
                    )");
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
     * @param   string navigation_name
     * @return  mixed
     * @desc    return navigation detail, only used for get_navigation_path
     */
    private function __cms_get_navigation($navigation_name)
    {
        if (!$navigation_name)
            return false;
        $query = $this->db->query("SELECT navigation_id, navigation_name, title, description, url
                    FROM ".cms_table_name('main_navigation')."
                    WHERE navigation_name = '" . addslashes($navigation_name) . "'");
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
     * @param   string navigation_name
     * @return  mixed
     * @desc    return navigation path, used for layout
     */
    public function cms_get_navigation_path($navigation_name = NULL)
    {
        if (!isset($navigation_name))
            return array();
        $result = array(
            $this->__cms_get_navigation($navigation_name)
        );
        while ($parent = $this->__cms_get_navigation_parent($navigation_name)) {
            $result[]        = $parent;
            $navigation_name = $parent["navigation_name"];
        }
        //result should be in reverse order
        for ($i = 0; $i < ceil(count($result) / 2); $i++) {
            $temp                            = $result[$i];
            $result[$i]                      = $result[count($result) - 1 - $i];
            $result[count($result) - 1 - $i] = $temp;
        }
        return $result;
    }

    /**
     * @author  goFrendiAsgard
     * @return  mixed
     * @desc    return privileges of current user
     */
    public function cms_privileges()
    {
        $user_name  = $this->cms_user_name();
        $user_id    = $this->cms_user_id();
        $not_login  = !isset($user_name) ? "TRUE" : "FALSE";
        $login      = isset($user_name) ? "TRUE" : "FALSE";
        $super_user = $user_id == 1 ? "TRUE" : "FALSE";

        $query  = $this->db->query("SELECT privilege_name, title, description
                FROM ".cms_table_name('main_privilege')." AS p WHERE
                    (authorization_id = 1) OR
                    (authorization_id = 2 AND $not_login) OR
                    (authorization_id = 3 AND $login) OR
                    (
                        (authorization_id = 4 AND $login AND
                        (
                            (SELECT COUNT(*) FROM ".cms_table_name('main_group_user')." AS gu WHERE gu.group_id=1 AND gu.user_id ='" . addslashes($user_id) . "')>0
                                OR $super_user OR
                            (SELECT COUNT(*) FROM ".cms_table_name('main_group_privilege')." AS gp
                                WHERE
                                    gp.privilege_id=p.privilege_id AND
                                    gp.group_id IN
                                        (SELECT group_id FROM ".cms_table_name('main_group_user')." WHERE user_id = '" . addslashes($user_id) . "')
                            )>0)
                        )
                    )");
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
     * @param   string navigation_name
     * @param   mixed navigations
     * @return  bool
     * @desc    only used in cms_allow_navigate
     */
    private function __cms_allow_navigate($navigation_name, $navigations = NULL)
    {
        if (!isset($navigations))
            $navigations = $this->cms_navigations();
        for ($i = 0; $i < count($navigations); $i++) {
            if ($navigation_name == $navigations[$i]["navigation_name"] && $navigations[$i]["allowed"] == 1) {
                return true;
            } else if ($this->__cms_allow_navigate($navigation_name, $navigations[$i]["child"])) {
                return true;
            }
        }
        return false;
    }

    /**
     * @author  goFrendiAsgard
     * @param   string navigation_name
     * @return  bool
     * @desc    check if user authorized to navigate into a page specified in parameter
     */
    public function cms_allow_navigate($navigation_name)
    {
        if($this->cms_user_id()==1) return TRUE;
        else return $this->__cms_allow_navigate($navigation_name);
    }

    /**
     * @author  goFrendiAsgard
     * @param   string privilege_name
     * @return  bool
     * @desc    check if user have privilege specified in parameter
     */
    public function cms_have_privilege($privilege_name)
    {
        if($this->cms_user_id()==1) return TRUE;
        else{
            $privileges = $this->cms_privileges();
            for ($i = 0; $i < count($privileges); $i++) {
                if ($privilege_name == $privileges[$i]["privilege_name"])
                    return TRUE;
            }
            return FALSE;
        }
    }

    /**
     * @author  goFrendiAsgard
     * @param   string identity
     * @param   string password
     * @return  bool
     * @desc    login with identity and password. Identity can be user_name or e-mail
     */
    public function cms_do_login($identity, $password)
    {
        $query = $this->db->query("SELECT user_id, user_name, real_name, email FROM ".cms_table_name('main_user')." WHERE
                    (user_name = '" . addslashes($identity) . "' OR email = '" . addslashes($identity) . "') AND
                    password = '" . md5($password) . "' AND
                    active = TRUE");
        foreach ($query->result() as $row) {
            $this->cms_user_name($row->user_name);
            $this->cms_user_id($row->user_id);
            $this->cms_user_real_name($row->real_name);
            $this->cms_user_email($row->email);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * @author  goFrendiAsgard
     * @desc    logout
     */
    public function cms_do_logout()
    {
        $this->cms_unset_ci_session('cms_user_name');
        $this->cms_unset_ci_session('cms_user_id');
        $this->cms_unset_ci_session('cms_user_real_name');
        $this->cms_unset_ci_session('cms_user_email');
    }

    /**
     * @author  goFrendiAsgard
     * @param   string user_name
     * @param   string email
     * @param   string real_name
     * @param   string password
     * @desc    register new user
     */
    public function cms_do_register($user_name, $email, $real_name, $password)
    {
        // check if activation needed
        $need_activation = strtoupper($this->cms_get_config('cms_signup_activation')) == 'TRUE';
        $data            = array(
            "user_name" => $user_name,
            "email" => $email,
            "real_name" => $real_name,
            "password" => md5($password),
            "active" => !$need_activation // depend on activation needed or not
        );
        $this->db->insert(cms_table_name('main_user'), $data);
        // send activation code if needed
        if ($need_activation) {
            $this->cms_generate_activation_code($user_name, TRUE, 'SIGNUP');
        }

    }

    /**
     * @author  goFrendiAsgard
     * @param   string user_name
     * @param   string email
     * @param   string real_name
     * @param   string password
     * @desc    change current profile (user_name, email, real_name and password)
     */
    public function cms_do_change_profile($user_name, $email, $real_name, $password = NULL)
    {
        $data = array(
            "user_name" => $user_name,
            "email" => $email,
            "real_name" => $real_name,
            "active" => 1
        );
        if (isset($password)) {
            $data['password'] = md5($password);
        }
        $where = array(
            "user_name" => $user_name
        );
        $this->db->update(cms_table_name('main_user'), $data, $where);
    }

    /**
     * @author  goFrendiAsgard
     * @param   string module_name
     * @return  bool
     * @desc    checked if module installed
     */
    public function cms_is_module_active($module_name)
    {
        $query = $this->db->query("SELECT module_id FROM ".cms_table_name('main_module')." WHERE module_name = '" . addslashes($module_name) . "'");
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
        return false;
    }

    /**
     * @author  goFrendiAsgard
     * @return  mixed
     * @desc    get module list
     */
    public function cms_get_module_list()
    {
        $this->load->helper('directory');
        $directories = directory_map('modules', 1);
        $module      = array();
        foreach ($directories as $directory) {
            if (!is_dir('modules/' . $directory))
                continue;

            if (!file_exists('modules/' . $directory . '/controllers/install.php'))
                continue;

            $files              = directory_map('modules/' . $directory . '/controllers', 1);
            $module_controllers = array();
            foreach ($files as $file) {
                $filename_array = explode('.', $file);
                $extension      = $filename_array[count($filename_array) - 1];
                unset($filename_array[count($filename_array) - 1]);
                $filename = implode('.', $filename_array);
                if ($extension == 'php' && $filename != 'install') {
                    $module_controllers[] = $filename;
                }
            }
            $module_name = $this->cms_module_name($directory);
            $module[]    = array(
                "module_name" => $module_name,
                "module_path" => $directory,
                "active" => $module_name != "",
                "controllers" => $module_controllers
            );
        }
        return $module;
    }

    /**
     * @author  goFrendiAsgard
     * @param   string module_name
     * @return  string
     * @desc    get module_path (folder name) of specified module_name (name space)
     */
    public function cms_module_path($module_name = NULL)
    {
        if (!isset($module_name)) {
            return $this->router->fetch_module();
        } else {
            $SQL   = "SELECT module_path FROM ".cms_table_name('main_module')." WHERE module_name='" . addslashes($module_name) . "'";
            $query = $this->db->query($SQL);
            if ($query->num_rows() > 0) {
                $row = $query->row();
                return $row->module_path;
            } else {
                return '';
            }
        }
    }

    /**
     * @author  goFrendiAsgard
     * @param   string module_path
     * @return  string
     * @desc    get module_name (name space) of specified module_path (folder name)
     */
    public function cms_module_name($module_path)
    {
        $SQL   = "SELECT module_name FROM ".cms_table_name('main_module')." WHERE module_path='" . addslashes($module_path) . "'";
        $query = $this->db->query($SQL);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->module_name;
        } else {
            return '';
        }

    }

    /**
     * @author  goFrendiAsgard
     * @return  mixed
     * @desc    get layout list
     */
    public function cms_get_layout_list()
    {
        $this->load->helper('directory');
        $directories = directory_map('themes', 1);
        $module      = array();
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
     * @param   string identity
     * @param	bool send_mail
     * @param   string reason (FORGOT, SIGNUP)
     * @return  bool
     * @desc    generate activation code, and send email to applicant
     */
    public function cms_generate_activation_code($identity, $send_mail = FALSE, $reason = 'FORGOT')
    {
        // if generate activation reason is "FORGOT", then user should be active
        $where_active = '1=1';
        if ($reason == 'FORGOT') {
            $where_active = 'active = TRUE';
        }
        // generate query
        $query = $this->db->query("SELECT user_name, real_name, user_id, email FROM ".cms_table_name('main_user')." WHERE
                    (user_name = '" . addslashes($identity) . "' OR email = '" . addslashes($identity) . "') AND
                    $where_active");
        if ($query->num_rows() > 0) {
            $row              = $query->row();
            $user_id          = $row->user_id;
            $email_to_address = $row->email;
            $user_name        = $row->user_name;
            $real_name        = $row->real_name;
            $activation_code  = random_string();

            //update, add activation_code
            $data  = array(
                "activation_code" => md5($activation_code)
            );
            $where = array(
                "user_id" => $user_id
            );
            $this->db->update(cms_table_name('main_user'), $data, $where);
            $this->load->library('email');
            if ($send_mail) {
                //prepare activation email to user
                $email_from_address = $this->cms_get_config('cms_email_reply_address');
                $email_from_name    = $this->cms_get_config('cms_email_reply_name');

                $email_subject = 'Account Activation';
                $email_message = 'Dear, {{ user_real_name }}<br />Click <a href="{{ site_url }}main/activate/{{ activation_code }}">{{ site_url }}main/activate/{{ activation_code }}</a> to activate your account';
                if (strtoupper($reason) == 'FORGOT') {
                    $email_subject = $this->cms_get_config('cms_email_forgot_subject');
                    $email_message = $this->cms_get_config('cms_email_forgot_message');
                } else if (strtoupper($reason) == 'SIGNUP') {
                    $email_subject = $this->cms_get_config('cms_email_signup_subject');
                    $email_message = $this->cms_get_config('cms_email_signup_message');
                }

                $email_message = str_replace('{{ user_real_name }}', $real_name, $email_message);
                $email_message = str_replace('{{ activation_code }}', $activation_code, $email_message);
                //send email to user
                return $this->cms_send_email($email_from_address, $email_from_name, $email_to_address, $email_subject, $email_message);
            }
            // if send_mail == false, than it should be succeed
            return true;
        }
        return false;
    }

    /**
     * @author  goFrendiAsgard
     * @param   string activation_code
     * @param   string new_password
     * @return  bool success
     * @desc    activate user
     */
    public function cms_activate_account($activation_code, $new_password = NULL)
    {
        $query = $this->db->query("SELECT user_id FROM ".cms_table_name('main_user')." WHERE
                    (activation_code = '" . md5($activation_code) . "')");
        if ($query->num_rows() > 0) {
            $row     = $query->row();
            $user_id = $row->user_id;
            $data    = array(
                "activation_code" => NULL,
                "active" => TRUE
            );
            if (isset($new_password)) {
                $data['password'] = md5($new_password);
            }

            $where = array(
                "user_id" => $user_id
            );
            $this->db->update(cms_table_name('main_user'), $data, $where);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * @author  goFrendiAsgard
     * @param   string from_address
     * @param   string from_name
     * @param   string to_address
     * @param   string subject
     * @param   string message
     * @desc    send email
     */
    public function cms_send_email($from_address, $from_name, $to_address, $subject, $message)
    {
        $this->load->library('email');
        //send email to user
        $config['useragent']      = (string) $this->cms_get_config('cms_email_useragent');
        $config['protocol']       = (string) $this->cms_get_config('cms_email_protocol');
        $config['mailpath']       = (string) $this->cms_get_config('cms_email_mailpath');
        $config['smtp_host']      = (string) $this->cms_get_config('cms_email_smtp_host');
        $config['smtp_user']      = (string) $this->cms_get_config('cms_email_smtp_user');
        $config['smtp_pass']      = (string) $this->cms_get_config('cms_email_smtp_pass');
        $config['smtp_port']      = (integer) $this->cms_get_config('cms_email_smtp_port');
        $config['smtp_timeout']   = (integer) $this->cms_get_config('cms_email_smtp_timeout');
        $config['wordwrap']       = (boolean) $this->cms_get_config('cms_email_wordwrap');
        $config['wrapchars']      = (integer) $this->cms_get_config('cms_email_wrapchars');
        $config['mailtype']       = (string) $this->cms_get_config('cms_email_mailtype');
        $config['charset']        = (string) $this->cms_get_config('cms_email_charset');
        $config['validate']       = (boolean) $this->cms_get_config('cms_email_validate');
        $config['priority']       = (integer) $this->cms_get_config('cms_email_priority');
        $config['crlf']           = "\r\n";
        $config['newline']        = "\r\n";
        $config['bcc_batch_mode'] = (boolean) $this->cms_get_config('cms_email_bcc_batch_mode');
        $config['bcc_batch_size'] = (integer) $this->cms_get_config('cms_email_bcc_batch_size');

        $message = $this->cms_parse_keyword($message);

        $this->email->initialize($config);
        $this->email->from($from_address, $from_name);
        $this->email->to($to_address);
        $this->email->subject($subject);
        $this->email->message($message);

        $success = $this->email->send();
        log_message('debug', $this->email->print_debugger());
        return $success;
    }

    /**
     * @author  goFrendiAsgard
     * @param   string activation_code
     * @return  bool
     * @desc    validate activation_code
     */
    public function cms_valid_activation_code($activation_code)
    {
        $query = $this->db->query("SELECT activation_code FROM ".cms_table_name('main_user')." WHERE
                    (activation_code = '" . md5($activation_code) . "') AND
                    (activation_code IS NOT NULL)");
        if ($query->num_rows() > 0)
            return true;
        else
            return false;
    }

    /**
     * @author  goFrendiAsgard
     * @param   string name
     * @param   string value
     * @param   string description
     * @desc    set config variable
     */
    public function cms_set_config($name, $value, $description = NULL)
    {
        $query = $this->db->query("SELECT config_id FROM ".cms_table_name('main_config')." WHERE
                    config_name = '" . addslashes($name) . "'");
        if ($query->num_rows() > 0) {
            $data = array(
                "value" => $value
            );
            if (isset($description))
                $data['description'] = $description;
            $where = array(
                "config_name" => $name
            );
            $this->db->update(cms_table_name('main_config'), $data, $where);
        } else {
            $data = array(
                "value" => $value,
                "config_name" => $name
            );
            if (isset($description))
                $data['description'] = $description;
            $this->db->insert(cms_table_name('main_config'), $data);
        }
        cms_config($name, $value);
        // save as __cms_model_properties too
        $this->__cms_model_properties['config'][$name] = $value;
    }

    /**
     * @author  goFrendiAsgard
     * @param   string name
     * @desc    unset configuration variable
     */
    public function cms_unset_config($name)
    {
        $where = array(
            "config_name" => $name
        );
        $query = $this->db->delete(cms_table_name('main_config'), $where);
    }

    /**
     * @author  goFrendiAsgard
     * @param   string name, bool raw
     * @return  string
     * @desc    get configuration variable
     */
    public function cms_get_config($name, $raw = FALSE)
    {
        $value = cms_config($name);
        if($value === FALSE){
            if (!isset($this->__cms_model_properties['config'][$name])) {
                $query  = $this->db->query("SELECT `value` FROM ".cms_table_name('main_config')." WHERE
                            config_name = '" . addslashes($name) . "'");
                if($query->num_rows()>0){
                    $row    = $query->row();
                    $value  = $row->value;
                }else{
                    $value  = '';
                }
                $this->__cms_model_properties['config'][$name] = $value;
            } else {
                $value = $this->__cms_model_properties['config'][$name];
            }
            cms_config($name, $value);
        }

        // if raw is false, then don't parse keyword
        if (!$raw) {
            $value = $this->cms_parse_keyword($value);
        }
        return $value;
    }

    /**
     * @author	goFrendiAsgard
     * @param	string language
     * @return	string language
     * @desc	set language for this session only
     */
    public function cms_language($language = NULL)
    {
        if (isset($language)) {
            $this->cms_ci_session('cms_lang', $language);
        } else {
            $language = '';
            $language = $this->cms_ci_session('cms_lang');
            if (!$language) {
                $language = $this->cms_get_config('site_language', True);
                $this->cms_ci_session('cms_lang', $language);
            }
            return $language;
        }
    }

    /**
     * @author	goFrendiAsgard
     * @return	array list of available languages
     * @desc	get available languages
     */
    public function cms_language_list()
    {
        $this->load->helper('file');
        $result = get_filenames('assets/nocms/languages');
        for ($i = 0; $i < count($result); $i++) {
            $result[$i] = str_ireplace('.php', '', $result[$i]);
        }
        return $result;
    }

    /**
     * @author  goFrendiAsgard
     * @return  mixed
     * @desc    get all language dictionary
     */
    public function cms_language_dictionary()
    {
        $language = $this->cms_language();
        if (count($this->__cms_model_properties['language_dictionary']) == 0) {
            $lang = array();

            // language setting from all modules but this current module
            $modules = $this->cms_get_module_list();
            foreach ($modules as $module) {
                $module_path = $module['module_path'];
                if ($module_path != $this->cms_module_path()) {
                    $local_language_file = "modules/$module_path/assets/languages/$language.php";
                    if (file_exists($local_language_file)) {
                        include($local_language_file);
                    }
                }
            }
            // global nocms language setting override previous language setting
            $language_file = "assets/nocms/languages/$language.php";
            if (file_exists($language_file)) {
                include($language_file);
            }
            // language setting from current module
            $module_path         = $this->cms_module_path();
            $local_language_file = "modules/$module_path/assets/languages/$language.php";
            if (file_exists($local_language_file)) {
                include($local_language_file);
            }

            $this->__cms_model_properties['language_dictionary'] = $lang;
        }

        return $this->__cms_model_properties['language_dictionary'];
    }

    /**
     * @author  goFrendiAsgard
     * @param   string key
     * @return  string
     * @desc    get translation of key in site_language
     */
    public function cms_lang($key)
    {
        $language = $this->cms_language();

        $dictionary = $this->cms_language_dictionary();

        // get the language
        if (isset($dictionary[$key])) {
            return $dictionary[$key];
        } else {
            return $key;
        }


    }

    /**
     * @author goFrendiAsgard
     * @param  string value
     * @return string
     * @desc   parse keyword like {{ site_url  }} , {{ base_url }} , {{ user_name }} , {{ module_path }} and {{ language }}
     */
    public function cms_parse_keyword($value)
    {
        $value = $this->cms_escape_template($value);

        $pattern     = array();
        $replacement = array();

        // user_name
        $pattern[]     = "/\{\{ user_id \}\}/si";
        $replacement[] = $this->cms_user_id();

        // user_name
        $pattern[]     = "/\{\{ user_name \}\}/si";
        $replacement[] = $this->cms_user_name();

        // user_real_name
        $pattern[]     = "/\{\{ user_real_name \}\}/si";
        $replacement[] = $this->cms_user_real_name();

        // user_email
        $pattern[]     = "/\{\{ user_email \}\}/si";
        $replacement[] = $this->cms_user_email();

        // site_url
        $site_url = site_url();
        if ($site_url[strlen($site_url) - 1] != '/')
            $site_url .= '/';
        $pattern[]     = '/\{\{ site_url \}\}/si';
        $replacement[] = $site_url;

        // base_url
        $base_url = base_url();
        if ($base_url[strlen($base_url) - 1] != '/')
            $base_url .= '/';
        $pattern[]     = '/\{\{ base_url \}\}/si';
        $replacement[] = $base_url;

        // module_path & module_name
        $module_path = $this->cms_module_path();
        $module_name = $this->cms_module_name($module_path);
        $module_site_url = site_url($module_path);
        $module_base_url = base_url($module_path);
        if ($module_site_url[strlen($module_site_url) - 1] != '/')
            $module_site_url .= '/';
        if ($module_base_url[strlen($module_base_url) - 1] != '/')
            $module_base_url .= '/';
        $pattern[]     = '/\{\{ module_path \}\}/si';
        $replacement[] = $module_path;
        $pattern[]     = '/\{\{ module_site_url \}\}/si';
        $replacement[] = $module_site_url;
        $pattern[]     = '/\{\{ module_base_url \}\}/si';
        $replacement[] = $module_base_url;
        $pattern[]     = '/\{\{ module_name \}\}/si';
        $replacement[] = $module_name;

        // language
        $pattern[]     = '/\{\{ language \}\}/si';
        $replacement[] = $this->cms_language();

        // execute regex
        $value = preg_replace($pattern, $replacement, $value);

        // translate language
        $pattern = '/\{\{ language:(.*?) \}\}/si';
        // execute regex
        $value   = preg_replace_callback($pattern, array(
            $this,
            '__cms_preg_replace_callback_lang'
        ), $value);

        // if language, elif
        $language    = $this->cms_language();
        $pattern     = array();
        $pattern[]   = "/\{\{ if_language:$language \}\}(.*?)\{\{ elif_language:.*?\{\{ end_if \}\}/si";
        $pattern[]   = "/\{\{ if_language:$language \}\}(.*?)\{\{ else \}\}.*?\{\{ end_if \}\}/si";
        $pattern[]   = "/\{\{ if_language:$language \}\}(.*?)\{\{ end_if \}\}/si";
        $pattern[]   = "/\{\{ if_language:.*?\{\{ elif_language:$language \}\}(.*?)\{\{ elif_language:.*?\{\{ end_if \}\}/si";
        $pattern[]   = "/\{\{ if_language:.*?\{\{ elif_language:$language \}\}(.*?)\{\{ else \}\}.*?\{\{ end_if \}\}/si";
        $pattern[]   = "/\{\{ if_language:.*?\{\{ elif_language:$language \}\}(.*?)\{\{ end_if \}\}/si";
        $pattern[]   = "/\{\{ if_language:.*?\{\{ else \}\}(.*?)\{\{ end_if \}\}/si";
        $pattern[]   = "/\{\{ if_language:.*?\{\{ end_if \}\}/si";
        $replacement = '$1';
        // execute regex
        $value       = preg_replace($pattern, $replacement, $value);

        // clear un-translated language
        $pattern     = array();
        $pattern     = "/\{\{ if_language:.*?\{\{ end_if \}\}/s";
        $replacement = '';
        // execute regex
        $value       = preg_replace($pattern, $replacement, $value);

        // configuration
        $pattern = '/\{\{ (.*?) \}\}/si';
        // execute regex
        $value   = preg_replace_callback($pattern, array(
            $this,
            '__cms_preg_replace_callback_config'
        ), $value);

        $value = $this->cms_unescape_template($value);
        return $value;
    }

    /**
     * @author goFrendiAsgard
     * @param  string user_name
     * @return bool
     * @desc   check if user already exists
     */
    public function cms_is_user_exists($username)
    {
        $SQL      = "SELECT user_name FROM ".cms_table_name('main_user')." WHERE user_name='" . addslashes($username) . "'";
        $query    = $this->db->query($SQL);
        $num_rows = $query->num_rows();
        return $num_rows > 0;
    }


    /**
     * @author goFrendiAsgard
     * @param  string expression
     * @return string
     * @desc return a "save" pattern which is not replace anything inside HTML tag, and
     * anything between <textarea></textarea> and <option></option>
     */
    public function cms_escape_template($str)
    {
        $pattern   = array();
        $pattern[] = '/(<textarea[^<>]*>)(.*?)(<\/textarea>)/si';
        $pattern[] = '/(value *= *")(.*?)(")/si';
        $pattern[] = "/(value *= *')(.*?)(')/si";

        $str = preg_replace_callback($pattern, array(
            $this,
            '__cms_preg_replace_callback_escape_template'
        ), $str);

        return $str;
    }

    /**
     * @author goFrendiAsgard
     * @param  string expression
     * @return string
     * @desc return an "unsave" pattern which is not replace anything inside HTML tag, and
     * anything between <textarea></textarea> and <option></option>
     */
    public function cms_unescape_template($str)
    {
        $pattern   = array();
        $pattern[] = '/(<textarea[^<>]*>)(.*?)(<\/textarea>)/si';
        $pattern[] = '/(value *= *")(.*?)(")/si';
        $pattern[] = "/(value *= *')(.*?)(')/si";
        $str       = preg_replace_callback($pattern, array(
            $this,
            '__cms_preg_replace_callback_unescape_template'
        ), $str);

        return $str;
    }

    /**
     * @author goFrendiAsgard
     * @param  array arr
     * @return string
     * @desc replace every '{{' and '}}' in $arr[1] into &#123; and &#125;
     */
    private function __cms_preg_replace_callback_unescape_template($arr)
    {
        $to_replace     = array(
            '{{ ',
            ' }}'
        );
        $to_be_replaced = array(
            '&#123;&#123; ',
            ' &#125;&#125;'
        );
        return $arr[1] . str_replace($to_be_replaced, $to_replace, $arr[2]) . $arr[3];
    }

    /**
     * @author goFrendiAsgard
     * @param  array arr
     * @return string
     * @desc replace every &#123; and &#125; in $arr[1] into '{{' and '}}';
     */
    private function __cms_preg_replace_callback_escape_template($arr)
    {
        $to_be_replaced = array(
            '{{ ',
            ' }}'
        );
        $to_replace     = array(
            '&#123;&#123; ',
            ' &#125;&#125;'
        );
        return $arr[1] . str_replace($to_be_replaced, $to_replace, $arr[2]) . $arr[3];
    }

    /**
     * @author goFrendiAsgard
     * @param  array arr
     * @return string
     * @desc replace $arr[1] with respective language;
     */
    private function __cms_preg_replace_callback_lang($arr)
    {
        return $this->cms_lang($arr[1]);
    }

    private function __cms_preg_replace_callback_config($arr)
    {
        $raw_config_value = $this->cms_get_config($arr[1]);
        // avoid recursion
        if(strpos($raw_config_value, '{{ '.$arr[1].' }}') !== FALSE){
            $raw_config_value = str_replace('{{ '.$arr[1].' }}', ' ', $raw_config_value);
        }
        return $this->cms_parse_keyword($raw_config_value);
    }

    /**
     * @author goFrendiAsgard
     * @return array providers
     */
    public function cms_third_party_providers()
    {
        if (!in_array('curl', get_loaded_extensions())) {
            return array();
        }
        $this->load->library('HybridAuthLib');
        $providers = $this->hybridauthlib->getProviders();
        return $providers;
    }

    /**
     * @author goFrendiAsgard
     * @return array status
     * @desc return all status from third-party provider
     */
    public function cms_third_party_status()
    {
        if (!in_array('curl', get_loaded_extensions())) {
            return array();
        }
        $this->load->library('HybridAuthLib');
        $status    = array();
        $connected = $this->hybridauthlib->getConnectedProviders();
        foreach ($connected as $provider) {
            if ($this->hybridauthlib->providerEnabled($provider)) {
                $service = $this->hybridauthlib->authenticate($provider);
                if ($service->isUserConnected()) {
                    $status[$provider] = (array) $this->hybridauthlib->getAdapter($provider)->getUserProfile();
                }
            }
        }
        return $status;
    }

    /**
     * @author goFrendiAsgard
     * @return boolean success
     * @desc login/register by using third-party provider
     */
    public function cms_third_party_login($provider)
    {
        // if provider not valid then exit
        $status = $this->cms_third_party_status();
        if (!isset($status[$provider]))
            return FALSE;

        $identifier = $status[$provider]['identifier'];


        $user_id = $this->cms_user_id();
        $query   = $this->db->select('user_id')->from(cms_table_name('main_user'))->where('auth_' . $provider, $identifier)->get();
        if ($query->num_rows() > 0) { // get user_id based on auth field
            $row     = $query->row();
            $user_id = $row->user_id;
        } else { // no identifier match, register it to the database
            $third_party_email        = $status[$provider]['email'];
            $third_party_display_name = $status[$provider]['firstName'];
            // if email match with the database, set $user_id
            if ($user_id == FALSE) {
                $query = $this->db->select('user_id')->from(cms_table_name('main_user'))->where('email', $third_party_email)->get();
                if ($query->num_rows() > 0) {
                    $row     = $query->row();
                    $user_id = $row->user_id;
                }
            }
            // if $user_id set (already_login, or $status[provider]['email'] match with database)
            if ($user_id != FALSE) {
                $data  = array(
                    'auth_' . $provider => $identifier
                );
                $where = array(
                    'user_id' => $user_id
                );
                $this->db->update(cms_table_name('main_user'), $data, $where);
            } else { // if not already login, register provider and id to the database
                $new_user_name = $third_party_display_name;

                // ensure there is no duplicate user name
                $duplicate = TRUE;
                while ($duplicate) {
                    $query = $this->db->select('user_name')->from(cms_table_name('main_user'))->where('user_name', $new_user_name)->get();
                    if ($query->num_rows() > 0) {
                        $query         = $this->db->select('user_name')->from(cms_table_name('main_user'))->get();
                        $user_count    = $query->num_rows();
                        $new_user_name = 'user_' . $user_count . ' (' . $new_user_name . ')';
                    } else {
                        $duplicate = FALSE;
                    }
                }

                // insert to database
                $data = array(
                    'user_name' => $new_user_name,
                    'email' => $third_party_email,
                    'auth_' . $provider => $identifier
                );
                $this->db->insert(cms_table_name('main_user'), $data);

                // get user_id
                $query = $this->db->select('user_id')->from(cms_table_name('main_user'))->where('email', $third_party_email)->get();
                if ($query->num_rows() > 0) {
                    $row     = $query->row();
                    $user_id = $row->user_id;
                }
            }
        }


        // set cms_user_id, cms_user_name, cms_user_email, cms_user_real_name, just as when login from the normal way
        $query = $this->db->select('user_id, user_name, email, real_name')->from(cms_table_name('main_user'))->where('user_id', $user_id)->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $this->cms_user_id($row->user_id);
            $this->cms_user_name($row->user_name);
            $this->cms_user_real_name($row->real_name);
            $this->cms_user_email($row->email);
            return TRUE;
        }
        return FALSE;
    }

}

class MY_Model extends CMS_Model
{
}