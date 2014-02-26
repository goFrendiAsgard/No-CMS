<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Base Model of No-CMS
 *
 * @author gofrendi
 */
class CMS_Model extends CI_Model
{
    private $__cms_model_properties;
    
    private function __update(){
        $old_version = cms_config('__cms_version');
        $current_version = '0.6.6.0';
        
        if($old_version !== $current_version){
            $this->load->dbforge();
            
            // make site_layout configuration
            if($this->cms_get_config('site_layout') == NULL){
                $this->cms_set_config('site_layout', 'default');
            }
            
            // copy from grocery_crud config from first-time to current config
            $source = APPPATH.'config/first-time/grocery_crud.php';
            if(CMS_SUBSITE == ''){
                $destination = APPPATH.'config/grocery_crud.php';
            }else{
                $destination = APPPATH.'config/site-'.CMS_SUBSITE.'/grocery_crud.php';
            }
            copy($source, $destination);
            
            // table : main navigation
            $table_name = cms_table_name('main_navigation');
            $field_list = $this->db->list_fields($table_name);
            $missing_fields = array(
                'notif_url' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                    'null' => TRUE,
                ),
                'bootstrap_glyph' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '50',
                    'null' => TRUE,
                ),
                'default_layout' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '50',
                    'null' => TRUE,
                ),
                'default_theme' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '50',
                    'null' => TRUE,
                ),
            );
            $fields = array();
            foreach($missing_fields as $key=>$value){
                if(!in_array($key, $field_list)){
                    $fields[$key] = $value;
                }
            }
            $this->dbforge->add_column($table_name, $fields);

            // write new version
            cms_config('__cms_version', $current_version);
        }
    }

    public function __construct()
    {
        parent::__construct();

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

        // load helpers and libraries
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->helper('form');
        $this->load->helper('string');
        $this->load->library('user_agent');
        $this->load->driver('session');
        $this->load->helper('cms_helper');
        $this->load->library('form_validation');
        $this->load->database();

        // accessing file is faster than accessing database
        // but I think accessing variable is faster than both of them
        $this->__cms_model_properties = array(
            'session' => array(),
            'language_dictionary' => array(),
            'config' => array()
        );
        $this->__update();

    }

    public function __destruct(){
        @$this->session->unset_userdata('cms_dynamic_widget');
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
     * @author goFrendiAsgard
     * @return array
     * @desc   get group list of current user
     */
    public function cms_user_group(){
        $query = $this->db->select('group_name')
            ->from(cms_table_name('main_group'))
            ->join(cms_table_name('main_group_user'), cms_table_name('main_group_user').'.group_id = '.cms_table_name('main_group').'.group_id')
            ->where(cms_table_name('main_group_user').'.user_id', $this->cms_user_id())
            ->get();
        $group_name = array();
        foreach($query->result() as $row){
            $group_name[] = $row->group_name;
        }
        return $group_name;
    }

    /**
     * @author goFrendiAsgard
     * @return array
     * @desc   get group list of current user
     */
    public function cms_user_group_id(){
        $query = $this->db->select('group_id')
            ->from(cms_table_name('main_group_user'))
            ->where(cms_table_name('main_group_user').'.user_id', $this->cms_user_id())
            ->get();
        $group_id = array();
        foreach($query->result() as $row){
            $group_id[] = $row->group_id;
        }
        return $group_id;
    }

    /**
     * @author goFrendiAsgard
     * @return boolean
     * @desc   TRUE if current user is super admin, FALSE otherwise
     */
    public function cms_user_is_super_admin(){
        if($this->cms_user_id()==1){
            return TRUE;
        }
        $query = $this->db->select('group_name')
            ->from(cms_table_name('main_group'))
            ->join(cms_table_name('main_group_user'), cms_table_name('main_group_user').'.group_id = '.cms_table_name('main_group').'.group_id')
            ->where(cms_table_name('main_group_user').'.user_id', $this->cms_user_id())
            ->where(cms_table_name('main_group').'.group_id', 1)
            ->get();
        return $query->num_rows()>0;
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
        $user_id    = $user_id == ''?0:$user_id;
        $not_login  = !$user_name ? "(1=1)" : "(1=2)";
        $login      = $user_name ? "(1=1)" : "(1=2)";
        $super_user = ($user_id == 1 || in_array(1,$this->cms_user_group_id())) ? "(1=1)" : "(1=2)";

        //get max_menu_depth from configuration
        if (!isset($max_menu_depth)) {
            $max_menu_depth = $this->cms_get_config('max_menu_depth');
            if(!isset($max_menu_depth)){
                $max_menu_depth = 10;
                $this->cms_set_config('max_menu_depth', $max_menu_depth);
            }
        }

        if ($max_menu_depth > 0) {
            $max_menu_depth--;
        } else {
            return array();
        }

        $where_is_root = !isset($parent_id) ? "(parent_id IS NULL)" : "parent_id = '" . addslashes($parent_id) . "'";
        $query         = $this->db->query("SELECT navigation_id, navigation_name, bootstrap_glyph, is_static, title, description, url, notif_url, active,
                    (
                        (authorization_id = 1) OR
                        (authorization_id = 2 AND $not_login) OR
                        (authorization_id = 3 AND $login) OR
                        (
                            (authorization_id = 4 AND $login) AND
                            (
                                (SELECT COUNT(*) FROM ".cms_table_name('main_group_user')." AS gu WHERE gu.group_id=1 AND gu.user_id =" . addslashes($user_id) . ")>0
                                    OR $super_user OR
                                (SELECT COUNT(*) FROM ".cms_table_name('main_group_navigation')." AS gn
                                    WHERE
                                        gn.navigation_id=n.navigation_id AND
                                        gn.group_id IN
                                            (SELECT group_id FROM ".cms_table_name('main_group_user')." WHERE user_id = " . addslashes($user_id) . ")
                                )>0
                            )
                        )
                    ) AS allowed
                FROM ".cms_table_name('main_navigation')." AS n WHERE
                    $where_is_root ORDER BY n.".$this->db->protect_identifiers('index'));
        $result        = array();
        foreach ($query->result() as $row) {
            $children              = $this->cms_navigations($row->navigation_id, $max_menu_depth);
            $have_allowed_children = false;
            foreach ($children as $child) {
                if ($child["allowed"] && $child["active"]) {
                    $have_allowed_children = true;
                    break;
                }
            }
            if ((!isset($row->url) || $row->url == '' || strpos(strtoupper($row->url), 'HTTP://') !== FALSE  || strpos(strtoupper($row->url), 'HTTPS://') !== FALSE ) && $row->is_static == 1) {
                $url = site_url('main/static_page/' . $row->navigation_name);
            } else {
                if (strpos(strtoupper($row->url), 'HTTP://') !== FALSE || strpos(strtoupper($row->url), 'HTTPS://') !== FALSE) {
                    $url = $row->url;
                } else {
                    $url = site_url($row->url);
                }
            }
            if(trim($row->notif_url) == ''){
                $notif_url = '';
            } else if (strpos(strtoupper($row->notif_url), 'HTTP://') !== FALSE || strpos(strtoupper($row->notif_url), 'HTTPS://') !== FALSE) {
                $notif_url = $row->notif_url;
            } else {
                $notif_url = site_url($row->notif_url);
            }
            $result[] = array(
                "navigation_id" => $row->navigation_id,
                "navigation_name" => $row->navigation_name,
                "bootstrap_glyph" => $row->bootstrap_glyph,
                "title" => $this->cms_lang($row->title),
                "description" => $row->description,
                "url" => $url,
                "notif_url" => $notif_url,
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
        $user_id    = $user_id == ''?0:$user_id;
        $not_login  = !$user_name ? "(1=1)" : "(1=2)";
        $login      = $user_name ? "(1=1)" : "(1=2)";
        $super_user = ($user_id == 1 || in_array(1,$this->cms_user_group_id())) ? "(1=1)" : "(1=2)";

        $query  = $this->db->query("SELECT q.navigation_id, navigation_name, bootstrap_glyph, is_static, title, description, url, notif_url, active
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
                                        (SELECT COUNT(*) FROM ".cms_table_name('main_group_user')." AS gu WHERE gu.group_id=1 AND gu.user_id =" . addslashes($user_id) . ")>0
                                            OR $super_user OR
                                        (SELECT COUNT(*) FROM ".cms_table_name('main_group_navigation')." AS gn
                                            WHERE
                                                gn.navigation_id=n.navigation_id AND
                                                gn.group_id IN
                                                    (SELECT group_id FROM ".cms_table_name('main_group_user')." WHERE user_id = " . addslashes($user_id) . ")
                                        )>0
                                    )
                                )
                            ) ORDER BY q.".$this->db->protect_identifiers('index'));
        $result = array();
        foreach ($query->result() as $row) {
            $all_children   = $this->cms_navigations($row->navigation_id);
            $children       = array();
            foreach ($all_children as $child) {
                if ($child['allowed']) {
                    unset($child['allowed']);
                    unset($child['have_allowed_children']);
                    $children[] = $child;
                }
            }
            if ((!isset($row->url) || $row->url == '') && $row->is_static == 1) {
                $url = 'main/static_page/' . $row->navigation_name;
            } else {
                if (strpos(strtoupper($row->url), 'HTTP://') !== FALSE || strpos(strtoupper($row->url), 'HTTPS://') !== FALSE) {
                    $url = $row->url;
                } else {
                    $url = site_url($row->url);
                }
            }
            if(trim($row->notif_url) == ''){
                $notif_url = '';
            } else if (strpos(strtoupper($row->notif_url), 'HTTP://') !== FALSE || strpos(strtoupper($row->notif_url), 'HTTPS://') !== FALSE) {
                $notif_url = $row->notif_url;
            } else {
                $notif_url = site_url($row->notif_url);
            }
            $result[] = array(
                "navigation_id" => $row->navigation_id,
                "navigation_name" => $row->navigation_name,
                "bootstrap_glyph" => $row->bootstrap_glyph,
                "title" => $this->cms_lang($row->title),
                "description" => $row->description,
                "url" => $url,
                "notif_url" => $notif_url,
                "is_static" => $row->is_static,
                "child" => $children,
                "active" => $row->active,
            );
        }
        return $result;
    }

    /**
     * @author  goFrendiAsgard
     * @param   slug
     * @param   widget_name
     * @return  mixed
     * @desc    return widgets
     */
    public function cms_widgets($slug = NULL, $widget_name=NULL)
    {
        // get user_name, user_id, etc
        $user_name  = $this->cms_user_name();
        $user_id    = $this->cms_user_id();
        $user_id    = $user_id == ''?0:$user_id;
        $not_login  = !$user_name ? "(1=1)" : "(1=2)";
        $login      = $user_name ? "(1=1)" : "(1=2)";
        $super_user = ($user_id == 1 || in_array(1,$this->cms_user_group_id())) ? "(1=1)" : "(1=2)";
        
        $slug_where = isset($slug)?
            "(((slug LIKE '".addslashes($slug)."') OR (slug LIKE '%".addslashes($slug)."%')) AND active=1)" :
            "1=1";
        $widget_name_where = isset($widget_name)? "widget_name LIKE '".addslashes($widget_name)."'" : "1=1";

        $SQL = "SELECT
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
                                            (SELECT group_id FROM ".cms_table_name('main_group_user')." WHERE user_id = " . addslashes($user_id) . ")
                                )>0
                            )
                        )
                    ) AND $slug_where AND $widget_name_where ORDER BY ".$this->db->protect_identifiers('index');
        $query  = $this->db->query($SQL);
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
                if($slug){
                    $content .= '<div id="__cms_widget_' . $row->widget_id . '">';
                }else{
                    $content .= '<span id="__cms_widget_' . $row->widget_id . '" style="padding:0px; margin:0px;">';
                }
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
                    $url = trim_slashes($url);
                    //$this->cms_ci_session('cms_dynamic_widget', TRUE);
                    $_REQUEST['__cms_dynamic_widget'] = 'TRUE';
                    $response = @Modules::run($url);
                    if(strlen($response) == 0){
                        $response = @Modules::run($url.'/index');
                    }       
                    unset($_REQUEST['__cms_dynamic_widget']);              
                    // fallback, Modules::run failed, use AJAX instead
                    if(strlen($response)==0){                        
                        $response = '<script type="text/javascript">';
                        $response .= '$(document).ready(function(){$("#__cms_widget_' . $row->widget_id . '").load("'.site_url($url).'?__cms_dynamic_widget=TRUE");});';
                        $response .= '</script>';
                    }
                    $content .= $response;
                    //$this->cms_unset_ci_session('cms_dynamic_widget');
                }
                
                if($slug){
                    $content .= '</div>';
                }else{
                    $content .= '</span>';
                }
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
                    "content" => $this->cms_parse_keyword($content)
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
        
        $html = '
        <script type="text/javascript">
            function __adjust_component(identifier){
                var max_height = 0;
                $(identifier).each(function(){
                    $(this).css("margin-bottom", 0);
                    if($(this).height()>max_height){
                        max_height = $(this).height();
                    }
                });
                $(identifier).each(function(){
                    var margin_bottom = 0;               
                    if($(this).height()<max_height){
                        margin_bottom = max_height - $(this).height();
                    }
                    margin_bottom += 10;
                    $(this).css("margin-bottom", margin_bottom);
                });
            }
            function __adjust_thumbnail_submenu(){
                __adjust_component(".thumbnail_submenu img");
                __adjust_component(".thumbnail_submenu div.caption");
                __adjust_component(".thumbnail_submenu");
            }
            $(window).load(function(){
                __adjust_thumbnail_submenu();
                // resize
                $(window).resize(function(){
                    __adjust_thumbnail_submenu();
                });
            });
        </script>';

        $html .= '<div class="row">';
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
            $navigation_id   = $submenu["navigation_id"];
            $navigation_name = $submenu["navigation_name"];
            $title           = $submenu["title"];
            $url             = $submenu["url"];
            $description     = $submenu["description"];
            $allowed         = $submenu["allowed"];
            $notif_url       = $submenu["notif_url"];
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
            
            $badge = '';
            if($notif_url != ''){
                $badge_id = '__cms_notif_submenu_screen_'.$navigation_id;
                $badge = '&nbsp;<span id="'.$badge_id.'" class="badge"></span>';
                $badge.= '<script type="text/javascript">
                        $(window).load(function(){
                            setInterval(function(){
                                $.ajax({
                                    dataType:"json",
                                    url: "'.addslashes($notif_url).'",
                                    success: function(response){
                                        if(response.success){
                                            $("#'.$badge_id.'").html(response.notif);
                                        }
                                        __adjust_thumbnail_submenu();
                                    }
                                });
                            }, 1000);
                        });
                    </script>
                ';
            }


            // default icon
            if ($image_file_path == '') {
                $image_file_path = 'assets/nocms/images/icons/package.png';
            }
            $html .= '<a href="' . $url . '" style="text-decoration:none;">';
            $html .= '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">';
            $html .= '<div class="thumbnail thumbnail_submenu">';
            
            if ($image_file_path != '') {
                $html .= '<img style="margin-top:10px; max-height:60px;" src="' . base_url($image_file_path) . '" />';
            }
            
            $html .= '<div class="caption">';
            $html .= '<h4>'.$title.$badge.'</h4>';
            $html .= '<p>'.$description.'</p>';
            $html .= '</div>'; // end of div.caption            
            $html .= '</div>'; // end of div.thumbnail
            $html .= '</div>'; // end of div.col-xs-6 col-sm-4 col-md-3
            $html .= '</a>';
        }
        $html .= '</div>';
        
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
        if ($query->num_rows() == 0)
            return false;
        else {
            foreach ($query->result() as $row) {
                return array(
                    "navigation_id" => $row->navigation_id,
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
        if ($query->num_rows() == 0)
            return false;
        else {
            foreach ($query->result() as $row) {
                return array(
                    "navigation_id" => $row->navigation_id,
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
        $user_id    = !isset($user_id)||is_null($user_id)?0:$user_id;
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
     * @desc    check if user authorized to navigate into a page specified in parameter
     */
    public function cms_allow_navigate($navigation_name, $navigations = NULL)
    {
        if (!isset($navigations))
            $navigations = $this->cms_navigations();
        for ($i = 0; $i < count($navigations); $i++) {
            if ($navigation_name == $navigations[$i]["navigation_name"] && $navigations[$i]['active'] && $navigations[$i]["allowed"] == 1) {
                return true;
            } else if ($this->cms_allow_navigate($navigation_name, $navigations[$i]["child"])) {
                return true;
            }
        }
        return false;
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
                    active = 1");
        $user_name = NULL;
        $user_id = NULL;
        $user_real_name = NULL;
        $user_email = NULL;
        $login_succeed = FALSE;
        if($query->num_rows()>0){
            $row            = $query->row();
            $user_name      = $row->user_name;
            $user_id        = $row->user_id;
            $user_real_name = $row->real_name;
            $user_email     = $row->email;
            $login_succeed  = TRUE;
        }else{
            require_once(APPPATH.'config/cms_extended_login.php');
            if(function_exists('extended_login')){
                $extended_login_result = extended_login($identity, $password);
                if($extended_login_result !== FALSE){
                    $query = $this->db->select('user_id, user_name')
                        ->from(cms_table_name('main_user'))
                        ->where('user_name', $identity)
                        ->get();
                    // if already exists in database
                    if($query->num_rows()>0){
                        $row = $query->row();
                        $user_id = $row->user_id;
                        $user_name = $row->user_name;
                        $login_succeed = TRUE;
                    }else{
                        $data = array();
                        $data['user_name'] = $identity;
                        $data['password'] = NULL;
                        $login_succeed = $this->db->insert(cms_table_name('main_user'), $data);
                        if($login_succeed){
                            $user_id = $this->db->insert_id();
                            $user_name = $identity;
                        }
                    }
                    if($login_succeed && is_array($extended_login_result)){
                        if(count($extended_login_result)>1){
                            $user_real_name = $extended_login_result[0];
                            $user_email = $extended_login_result[1];
                        }
                    }
                }
            }
        }

        if($login_succeed){
            $this->cms_user_name($user_name);
            $this->cms_user_id($user_id);
            $this->cms_user_real_name($user_real_name);
            $this->cms_user_email($user_email);
            // needed by kcfinder
            if(!isset($_SESSION)){
                session_start();
            }
            if(!isset($_SESSION['__cms_user_id'])){
                $_SESSION['__cms_user_id'] = $user_id;
            }            
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
        // needed by kcfinder
        if(isset($_SESSION)){
            session_unset('__cms_user_id');
        }
    }

    /**
     * @author  goFrendiAsgard
     * @param   string parent
     * @desc    re-arange index of navigation with certain parent_id
     */
    private function __cms_reindex_navigation($parent_id=NULL){
        if (isset($parent_id)) {
            $whereParentId = "(parent_id = $parent_id)";
        } else {
            $whereParentId = "(parent_id IS NULL)";
        }
        $query = $this->db->select('navigation_id,index')
            ->from(cms_table_name('main_navigation'))
            ->where($whereParentId)
            ->order_by('index')
            ->get();
        $index = 1;
        foreach($query->result() as $row){
            if($index != $row->index){
                $where = array('navigation_id'=>$row->navigation_id);
                $data = array('index'=>$index);
                $this->db->update(cms_table_name('main_navigation'), $data, $where);
            }
            $index += 1;
        }
    }

    /**
     * @author  goFrendiAsgard
     * @param   string parent
     * @desc    re-arange index of widget
     */
    private function __cms_reindex_widget(){
        $query = $this->db->select('widget_id,index')
            ->from(cms_table_name('main_widget'))
            ->order_by('index')
            ->get();
        $index = 1;
        foreach($query->result() as $row){
            if($index != $row->index){
                $where = array('widget_id'=>$row->widget_id);
                $data = array('index'=>$index);
                $this->db->update(cms_table_name('main_widget'), $data, $where);
            }
            $index += 1;
        }
    }

    /**
     * @author  goFrendiAsgard
     * @param   string parent
     * @desc    re-arange index of quicklink
     */
    private function __cms_reindex_quicklink(){
        $query = $this->db->select('quicklink_id,index')
            ->from(cms_table_name('main_quicklink'))
            ->order_by('index')
            ->get();
        $index = 1;
        foreach($query->result() as $row){
            if($index != $row->index){
                $where = array('quicklink_id'=>$row->quicklink_id);
                $data = array('index'=>$index);
                $this->db->update(cms_table_name('main_quicklink'), $data, $where);
            }
            $index += 1;
        }
    }

    /**
     * @author  goFrendiAsgard
     * @param   int navigation id
     * @desc    move quicklink up
     */
    public function cms_do_move_up_quicklink($navigation_id){
        // re-index all
        $this->__cms_reindex_quicklink();
        // get the index again
        $query = $this->db->select('quicklink_id, index')
            ->from(cms_table_name('main_quicklink'))
            ->where('navigation_id', $navigation_id)
            ->get();
        $row = $query->row();
        $this_index = $row->index;
        $this_quicklink_id = $row->quicklink_id;
        $SQL   = "
            SELECT max(".$this->db->protect_identifiers('index').") AS ".$this->db->protect_identifiers('index')."
            FROM ".cms_table_name('main_quicklink')." WHERE ".
            $this->db->protect_identifiers('index')."<".$this_index;
        $query = $this->db->query($SQL);
        $row   = $query->row();
        if(intval($row->index) > 0){
            $neighbor_index = intval($row->index);

            // update neighbor
            $data = array('index'=>$this_index);
            $where = $this->db->protect_identifiers('index'). ' = '.$neighbor_index;
            $this->db->update(cms_table_name('main_quicklink'),$data, $where);

            // update current row
            $data = array('index'=>$neighbor_index);
            $where = array('quicklink_id'=>$this_quicklink_id);
            $this->db->update(cms_table_name('main_quicklink'),$data, $where);
        }
    }

    /**
     * @author  goFrendiAsgard
     * @param   int navigation id
     * @desc    move quicklink down
     */
    public function cms_do_move_down_quicklink($navigation_id){
        // re-index all
        $this->__cms_reindex_quicklink();
        // get the index again
        $query = $this->db->select('quicklink_id, index')
            ->from(cms_table_name('main_quicklink'))
            ->where('navigation_id', $navigation_id)
            ->get();
        $row = $query->row();
        $this_index = $row->index;
        $this_quicklink_id = $row->quicklink_id;
        $SQL   = "
            SELECT min(".$this->db->protect_identifiers('index').") AS ".$this->db->protect_identifiers('index')."
            FROM ".cms_table_name('main_quicklink')." WHERE ".
            $this->db->protect_identifiers('index').">".$this_index;
        $query = $this->db->query($SQL);
        $row   = $query->row();
        if(intval($row->index) > 0){
            $neighbor_index = intval($row->index);

            // update neighbor
            $data = array('index'=>$this_index);
            $where = $this->db->protect_identifiers('index'). ' = '.$neighbor_index;
            $this->db->update(cms_table_name('main_quicklink'),$data, $where);

            // update current row
            $data = array('index'=>$neighbor_index);
            $where = array('quicklink_id'=>$this_quicklink_id);
            $this->db->update(cms_table_name('main_quicklink'),$data, $where);
        }
    }

    /**
     * @author  goFrendiAsgard
     * @param   string widget_name
     * @desc    move widget up
     */
    public function cms_do_move_up_widget($widget_name){
        // get current navigation info
        $query = $this->db->select('widget_id')
            ->from(cms_table_name('main_widget'))
            ->where('widget_name', $widget_name)
            ->get();
        $row = $query->row();
        $this_widget_id = $row->widget_id;
        // re-index all
        $this->__cms_reindex_widget();
        // get the index again
        $query = $this->db->select('index')
            ->from(cms_table_name('main_widget'))
            ->where('widget_name', $widget_name)
            ->get();
        $row = $query->row();
        $this_index = $row->index;
        $SQL   = "
            SELECT max(".$this->db->protect_identifiers('index').") AS ".$this->db->protect_identifiers('index')."
            FROM ".cms_table_name('main_widget')." WHERE ".
            $this->db->protect_identifiers('index')."<".$this_index;
        $query = $this->db->query($SQL);
        $row   = $query->row();
        if(intval($row->index) > 0){
            $neighbor_index = intval($row->index);

            // update neighbor
            $data = array('index'=>$this_index);
            $where = $this->db->protect_identifiers('index'). ' = '.$neighbor_index;
            $this->db->update(cms_table_name('main_widget'),$data, $where);

            // update current row
            $data = array('index'=>$neighbor_index);
            $where = array('widget_id'=>$this_widget_id);
            $this->db->update(cms_table_name('main_widget'),$data, $where);
        }
    }

    /**
     * @author  goFrendiAsgard
     * @param   string widget_name
     * @desc    move widget down
     */
    public function cms_do_move_down_widget($widget_name){
        // get current navigation info
        $query = $this->db->select('widget_id')
            ->from(cms_table_name('main_widget'))
            ->where('widget_name', $widget_name)
            ->get();
        $row = $query->row();
        $this_widget_id = $row->widget_id;
        // re-index all
        $this->__cms_reindex_widget();
        // get the index again
        $query = $this->db->select('index')
            ->from(cms_table_name('main_widget'))
            ->where('widget_name', $widget_name)
            ->get();
        $row = $query->row();
        $this_index = $row->index;
        $SQL   = "
            SELECT min(".$this->db->protect_identifiers('index').") AS ".$this->db->protect_identifiers('index')."
            FROM ".cms_table_name('main_widget')." WHERE ".
            $this->db->protect_identifiers('index').">".$this_index;
        $query = $this->db->query($SQL);
        $row   = $query->row();
        if(intval($row->index) > 0){
            $neighbor_index = intval($row->index);

            // update neighbor
            $data = array('index'=>$this_index);
            $where = $this->db->protect_identifiers('index'). ' = '.$neighbor_index;
            $this->db->update(cms_table_name('main_widget'),$data, $where);

            // update current row
            $data = array('index'=>$neighbor_index);
            $where = array('widget_id'=>$this_widget_id);
            $this->db->update(cms_table_name('main_widget'),$data, $where);
        }
    }

    /**
     * @author  goFrendiAsgard
     * @param   string navigation_name
     * @desc    move navigation up
     */
    public function cms_do_move_up_navigation($navigation_name){
        // get current navigation info
        $query = $this->db->select('parent_id, navigation_id')
            ->from(cms_table_name('main_navigation'))
            ->where('navigation_name', $navigation_name)
            ->get();
        $row = $query->row();
        $parent_id = $row->parent_id;
        $this_navigation_id = $row->navigation_id;
        // re-index all
        $this->__cms_reindex_navigation($parent_id);
        // get the index again
        $query = $this->db->select('index')
            ->from(cms_table_name('main_navigation'))
            ->where('navigation_name', $navigation_name)
            ->get();
        $row = $query->row();
        $this_index = $row->index;
        // select
        if (isset($parent_id)) {
            $whereParentId = "(parent_id = $parent_id)";
        } else {
            $whereParentId = "(parent_id IS NULL)";
        }
        $SQL   = "
            SELECT max(".$this->db->protect_identifiers('index').") AS ".$this->db->protect_identifiers('index')."
            FROM ".cms_table_name('main_navigation')." WHERE $whereParentId AND ".
            $this->db->protect_identifiers('index')."<".$this_index;
        $query = $this->db->query($SQL);
        $row   = $query->row();
        if(intval($row->index) > 0){
            $neighbor_index = intval($row->index);

            // update neighbor
            $data = array('index'=>$this_index);
            $where = $whereParentId. ' AND ' . $this->db->protect_identifiers('index'). ' = '.$neighbor_index;
            $this->db->update(cms_table_name('main_navigation'),$data, $where);

            // update current row
            $data = array('index'=>$neighbor_index);
            $where = array('navigation_id'=>$this_navigation_id);
            $this->db->update(cms_table_name('main_navigation'),$data, $where);
        }
    }

    /**
     * @author  goFrendiAsgard
     * @param   string navigation_name
     * @desc    move navigation down
     */
    public function cms_do_move_down_navigation($navigation_name){
        // get current navigation info
        $query = $this->db->select('parent_id, navigation_id')
            ->from(cms_table_name('main_navigation'))
            ->where('navigation_name', $navigation_name)
            ->get();
        $row = $query->row();
        $parent_id = $row->parent_id;
        $this_navigation_id = $row->navigation_id;
        // re-index all
        $this->__cms_reindex_navigation($parent_id);
        // get the index again
        $query = $this->db->select('index')
            ->from(cms_table_name('main_navigation'))
            ->where('navigation_name', $navigation_name)
            ->get();
        $row = $query->row();
        $this_index = $row->index;
        // select
        if (isset($parent_id)) {
            $whereParentId = "(parent_id = $parent_id)";
        } else {
            $whereParentId = "(parent_id IS NULL)";
        }
        $SQL   = "
            SELECT min(".$this->db->protect_identifiers('index').") AS ".$this->db->protect_identifiers('index')."
            FROM ".cms_table_name('main_navigation')." WHERE $whereParentId AND ".
            $this->db->protect_identifiers('index').">".$this_index;
        $query = $this->db->query($SQL);
        $row   = $query->row();
        if(intval($row->index) > 0){
            $neighbor_index = intval($row->index);

            // update neighbor
            $data = array('index'=>$this_index);
            $where = $whereParentId. ' AND ' . $this->db->protect_identifiers('index'). ' = '.$neighbor_index;
            $this->db->update(cms_table_name('main_navigation'),$data, $where);
            // update current row
            $data = array('index'=>$neighbor_index);
            $where = array('navigation_id'=>$this_navigation_id);
            $this->db->update(cms_table_name('main_navigation'),$data, $where);
        }

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
        $query = $this->db->select('module_id')
            ->from(cms_table_name('main_module'))
            ->where('module_name', $module_name)
            ->get();
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
        $directories = directory_map(FCPATH.'modules', 1);
        sort($directories);
        $module      = array();
        foreach ($directories as $directory) {
            $directory = str_replace(array('/','\\'),'',$directory);
            if (!is_dir(FCPATH.'modules/' . $directory))
                continue;

            if (!file_exists(FCPATH.'modules/' . $directory . '/controllers/install.php'))
                continue;

            // unpublished module should not be shown
            if(CMS_SUBSITE != ''){
                $subsite_auth_file = FCPATH.'modules/' . $directory . '/subsite_auth.php';
                if (file_exists($subsite_auth_file)){
                    unset($public);
                    unset($subsite_allowed);
                    include($subsite_auth_file);
                    if(isset($public) && is_bool($public) && !$public){
                        if(is_array($subsite_allowed) && !in_array(CMS_SUBSITE, $subsite_allowed)){
                            continue;
                        }
                    }
                }
            }

            $files              = directory_map(FCPATH.'modules/' . $directory . '/controllers', 1);
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
                "controllers" => $module_controllers,
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
            $module = $this->router->fetch_module();
            return $module;
        } else {            
            $query = $this->db->select('module_path')
                ->from(cms_table_name('main_module'))
                ->where('module_name', $module_name)
                ->get();
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
    public function cms_module_name($module_path = NULL)
    {
        if(!isset($module_path) || is_null($module_path)){
            $module_path = $this->cms_module_path();
        }
        $query = $this->db->select('module_name')
            ->from(cms_table_name('main_module'))
            ->where('module_path', $module_path)
            ->get();
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
     * @desc    get theme list
     */
    public function cms_get_theme_list()
    {
        $this->load->helper('directory');
        $directories = directory_map(FCPATH.'themes', 1);
        sort($directories);
        $themes      = array();
        foreach ($directories as $directory) {
            $directory = str_replace(array('/','\\'),'',$directory);
            if (!is_dir(FCPATH.'themes/' . $directory))
                continue;

            if(CMS_SUBSITE != ''){
                $subsite_auth_file = FCPATH.'themes/'.$directory.'/subsite_auth.php';
                if(file_exists($subsite_auth_file)){
                    unset($public);
                    unset($subsite_allowed);
                    include($subsite_auth_file);
                    if(isset($public) && is_bool($public) && !$public){
                        if(isset($subsite_allowed) && is_array($subsite_allowed) && !in_array(CMS_SUBSITE, $subsite_allowed)){
                            continue;
                        }
                    }
                }
            }

            $layout_name = $directory;

            $themes[] = array(
                "path" => $directory,
                "used" => $this->cms_get_config('site_theme') == $layout_name
            );
        }
        // the currently used theme should be on the top
        for($i=0; $i<count($themes); $i++){
            if($themes[$i]['used']){                
                if($i != 0){
                    $new_themes = array();
                    $current_theme = $themes[$i];
                    $new_themes[] = $current_theme;
                    for($j=0; $j<count($themes); $j++){
                        if($j != $i){
                            $new_themes[] = $themes[$j];
                        }
                    }
                    $themes = $new_themes;
                }
                break;
            }
        }
        return $themes;
    }

    /**
     * @author  goFrendiAsgard
     * @param   string identity
     * @param    bool send_mail
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
        if($value === NULL || !$value){
            if (!isset($this->__cms_model_properties['config'][$name])) {
                $query = $this->db->select('value')
                    ->from(cms_table_name('main_config'))
                    ->where('config_name', $name)
                    ->get();
                if($query->num_rows()>0){
                    $row    = $query->row();
                    $value  = $row->value;
                    $this->__cms_model_properties['config'][$name] = $value;
                }else{
                    $value  = NULL;
                }
            } else {
                $value = $this->__cms_model_properties['config'][$name];
            }
            cms_config($name, $value);
        }

        // if raw is false, then don't parse keyword
        if (!$raw && isset($value)) {
            $value = $this->cms_parse_keyword($value);
        }
        return $value;
}

    /**
     * @author    goFrendiAsgard
     * @param    string language
     * @return    string language
     * @desc    set language for this session only
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
     * @author    goFrendiAsgard
     * @return    array list of available languages
     * @desc    get available languages
     */
    public function cms_language_list()
    {
        $this->load->helper('file');
        $result = array();
        $language_list = get_filenames(APPPATH.'../assets/nocms/languages');
        foreach ($language_list as $language){
            if(preg_match('/\.php$/i', $language)){
                $result[] = str_ireplace('.php', '', $language);
            }
        }
        $module_list = $this->cms_get_module_list();
        $module_list[] = array('module_path'=>'main');
        foreach ($module_list as $module){
            $directory = $module['module_path'];
            $module_language_list = get_filenames(APPPATH.'../modules/'.$directory.'/assets/languages');
            if($module_language_list === FALSE) continue;
            foreach($module_language_list as $module_language){
                if(preg_match('/\.php$/i', $module_language)){
                    $module_language = str_ireplace('.php', '', $module_language);
                    if(!in_array($module_language, $result)){
                        $result[] = $module_language;
                    }
                }
            }
        }
        sort($result);
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
                    $local_language_file = APPPATH."../modules/$module_path/assets/languages/$language.php";
                    if (file_exists($local_language_file)) {
                        include($local_language_file);
                    }
                }
            }
            // nocms main module language setting override previous language setting
            $language_file = APPPATH."../modules/main/assets/languages/$language.php";
            if (file_exists($language_file)) {
                include($language_file);
            }
            // global nocms language setting override previous language setting
            $language_file = APPPATH."../assets/nocms/languages/$language.php";
            if (file_exists($language_file)) {
                include($language_file);
            }
            // language setting from current module
            $module_path         = $this->cms_module_path();
            $local_language_file = APPPATH."../modules/$module_path/assets/languages/$language.php";
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
     * @desc   parse keyword like {{ site_url  }} , {{ base_url }} , {{ user_name }} , {{ language }}
     */
    public function cms_parse_keyword($value)
    {
        $value = $this->cms_escape_template($value);
        
        if(strpos($value, '{{ ') !== FALSE){
    
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
            $module_base_url = base_url('modules/'.$module_path);
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
        }       
        
        // translate language
        if(strpos($value, '{{ ') !== FALSE){
            $pattern = '/\{\{ language:(.*?) \}\}/si';
            // execute regex
            $value   = preg_replace_callback($pattern, array(
                $this,
                '__cms_preg_replace_callback_lang'
            ), $value);
        }

        // if language, elif
        if(strpos($value, '{{ ') !== FALSE){
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
        }

        // clear un-translated language
        if(strpos($value, '{{ ') !== FALSE){
            $pattern     = array();
            $pattern     = "/\{\{ if_language:.*?\{\{ end_if \}\}/s";
            $replacement = '';
            // execute regex
            $value       = preg_replace($pattern, $replacement, $value);
        }

        // configuration
        if(strpos($value, '{{ ') !== FALSE){
            $pattern = '/\{\{ (.*?) \}\}/si';
            // execute regex
            $value   = preg_replace_callback($pattern, array(
                $this,
                '__cms_preg_replace_callback_config'
            ), $value);
        }

        return $value;
    }

    /**
     * @author goFrendiAsgard
     * @param  string user_name
     * @return bool
     * @desc   check if user already exists
     */
    public function cms_is_user_exists($identity)
    {
        $query    = $this->db->select('user_name')
            ->from(cms_table_name('main_user'))
            ->like('user_name', $identity, 'none')
            ->or_like('email', $identity, 'none')
            ->get();
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
        if(isset($raw_config_value)){
            // avoid recursion
            if(strpos($raw_config_value, '{{ '.$arr[1].' }}') !== FALSE){
                $raw_config_value = str_replace('{{ '.$arr[1].' }}', ' ', $raw_config_value);
            }
            return $this->cms_parse_keyword($raw_config_value);
        }else{
            return '{{ '.$arr[1].' }}';
        }

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
        $user_id    = !isset($user_id)||is_null($user_id)?0:$user_id;
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

class MY_Model extends CI_Model
{
}
