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
        $current_version = '0.7.0-beta';

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

            $query = $this->db->select('authorization_id')
                ->from(cms_table_name('main_authorization'))
                ->where('authorization_id',5)
                ->get();
            if($query->num_rows() == 0){
                $this->db->insert(cms_table_name('main_authorization'), array(
                    'authorization_id'=>5,
                    'authorization_name'=>'Exclusive Authorized',
                    'description'=>'Even Super Admin cannot access this if not allowed'
                ));
            }

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

            // table : main_user
            $table_name = cms_table_name('main_user');
            $field_list = $this->db->list_fields($table_name);
            $missing_fields = array(
                'language' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '50',
                    'null' => TRUE,
                ),
                'theme' => array(
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

            // table : multisite_subsite
            if($this->cms_is_module_active('gofrendi.noCMS.multisite')){
                $table_name = cms_table_name('multisite_subsite');
                $field_list = $this->db->list_fields($table_name);
                $missing_fields = array(
                    'active' => array(
                        'type' => 'INT',
                        'constraint' => '11',
                        'null' => TRUE,
                        'default' => 1,
                    )
                );
                $fields = array();
                foreach($missing_fields as $key=>$value){
                    if(!in_array($key, $field_list)){
                        $fields[$key] = $value;
                    }
                }
            }

            $table_list = $this->db->list_tables();
            // add main-language-detail
            if(! in_array(cms_table_name('main_detail_language'), $table_list)){
                $fields = array(
                    'detail_language_id'=> array('type' => 'INT', 'constraint' => 20, 'unsigned' => TRUE, 'auto_increment' => TRUE,),
                    'id_language'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
                    'key'=> array("type"=>'text', "null"=>TRUE),
                    'translation'=> array("type"=>'text', "null"=>TRUE)
                );
                $this->dbforge->add_field($fields);
                $this->dbforge->add_key('detail_language_id', TRUE);
                $this->dbforge->create_table(cms_table_name('main_detail_language'));
            }

            // add main_language
            if(! in_array(cms_table_name('main_language'), $table_list)){
                $fields = array(
                    'language_id'=> array('type' => 'INT', 'constraint' => 20, 'unsigned' => TRUE, 'auto_increment' => TRUE,),
                    'name'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
                    'code'=>array("type"=>'varchar',"constraint"=>50, "null"=>TRUE),
                    'iso_code'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
                    'translations'=> array("type"=>'varchar', "constraint"=>255, "null"=>TRUE)
                );
                $this->dbforge->add_field($fields);
                $this->dbforge->add_key('language_id', TRUE);
                $this->dbforge->create_table(cms_table_name('main_language'));
            }

            // update main_layout into main_setting
            $data = array(
                'navigation_name' => 'main_setting',
                'url' => 'main/setting',
                'title' => 'Setting',
                'description' => 'CMS Setting'
            );
            $this->db->update(cms_table_name('main_navigation'),
                $data,
                array('navigation_name' => 'main_layout'));
            // add manage language
            $query = $this->db->select('navigation_id')
                ->from(cms_table_name('main_navigation'))
                ->where('navigation_name','main_language_management')
                ->get();
            if($query->num_rows() == 0){
                $parent_id = $this->db->select('navigation_id')
                    ->from(cms_table_name('main_navigation'))
                    ->where('navigation_name', 'main_management')
                    ->get()->row()->navigation_id;
                $this->db->insert(cms_table_name('main_navigation'),array(
                    'navigation_name'=>'main_language_management',
                    'title' => 'Language Management',
                    'page_title' => 'Language Management',
                    'description' => 'Language Management',
                    'url'=>'main/language_management',
                    'parent_id'=>$parent_id,
                    'authorization_id'=>4));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Afrikaans','code'=>'afrikaans'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Arabic','code'=>'arabic'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Bengali','code'=>'bengali'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Bulgarian','code'=>'bulgarian'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Catalan','code'=>'catalan'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Chinese','code'=>'chinese'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Czech','code'=>'czech'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Danish','code'=>'danish'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Dutch','code'=>'dutch'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'English','code'=>'english'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'French','code'=>'french'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'German','code'=>'german'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Greek','code'=>'greek'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Hindi','code'=>'hindi'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Hungarian','code'=>'hungarian'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Indonesian','code'=>'indonesian'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Italian','code'=>'italian'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Japanese','code'=>'japanese'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Korean','code'=>'korean'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Mongolian','code'=>'mongolian'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Norwegian','code'=>'norwegian'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Persian','code'=>'persian'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Polish','code'=>'polish'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Portuguese (Brazil)','code'=>'pt-br.portuguese'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Portuguese','code'=>'pt-pt.portuguese'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Romanian','code'=>'romanian'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Russian','code'=>'russian'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Slovak','code'=>'slovak'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Spanish','code'=>'spanish'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Thai','code'=>'thai'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Turkish','code'=>'turkish'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Ukrainian','code'=>'ukrainian'));
                $this->db->insert(cms_table_name('main_language'),array('name'=>'Vietnamese','code'=>'vietnamese'));
            }

            // new configuration, cms_add_subsite_on_register
            $exists = $this->db->select('config_name')
                ->from(cms_table_name('main_config'))
                ->where('config_name','cms_add_subsite_on_register')
                ->get()->num_rows() > 0;
            if(!$exists){
                $this->db->insert(cms_table_name('main_config'),array(
                    'config_name' => 'cms_add_subsite_on_register',
                    'value' => 'FALSE',
                    'description' => 'Automatically create subsite on register'
                ));
            }
            // new configuration, cms_subsite_use_subdomain
            $exists = $this->db->select('config_name')
                ->from(cms_table_name('main_config'))
                ->where('config_name','cms_subsite_use_subdomain')
                ->get()->num_rows() > 0;
            if(!$exists){
                $this->db->insert(cms_table_name('main_config'),array(
                    'config_name' => 'cms_subsite_use_subdomain',
                    'value' => 'FALSE',
                    'description' => 'Automatically use subdomain'
                ));
            }
            // new configuration, cms_subsite_home_content
            $exists = $this->db->select('config_name')
                ->from(cms_table_name('main_config'))
                ->where('config_name','cms_subsite_home_content')
                ->get()->num_rows() > 0;
            if(!$exists){
                $this->db->insert(cms_table_name('main_config'),array(
                    'config_name' => 'cms_subsite_home_content',
                    'value' => '{{ widget_name:blog_content }}',
                    'description' => 'Default subsite homepage content'
                ));
            }

            // blog widget
            if($this->cms_is_module_active('gofrendi.noCMS.blog')){
                $result = $this->db->select('widget_name')
                    ->from(cms_table_name('main_widget'))
                    ->where('widget_name', 'blog_content')
                    ->get();
                if($result->num_rows() == 0){
                    $result = $this->db->select_max('index')
                        ->from(cms_table_name('main_widget'))
                        ->get();
                    $row = $result->row();
                    $max_index = $row->index;
                    $max_index = is_numeric($max_index)? $max_index : 0;
                    $this->db->insert(cms_table_name('main_widget'), array(
                        'widget_name ' => 'blog_content',
                        'title' => 'Blog Content',
                        'description' => 'Blog Content',
                        'url' => 'blog',
                        'authorization_id' => 1,
                        'active' => 1,
                        'index' => $max_index + 1,
                        'is_static' => 0
                    ));
                }
            }

            // update module name
            $this->db->update(cms_table_name('main_module'), 
                array('module_name'=>'gofrendi.noCMS.multisite'),
                array('module_name'=>'admin.multisite'));

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
        // BASE URL, needed by kcfinder
        if(!isset($_SESSION)){
            session_start();
        }
        if(!isset($_SESSION['__base_url'])){
            $_SESSION['__cms_base_url'] = base_url();
        }
        // seamless update
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
        if(!isset($_SESSION)){
            session_start();
        }
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
                        ) OR
                        (
                            (authorization_id = 5 AND $login) AND
                            (
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
                                ) OR
                                (
                                    (authorization_id = 5 AND $login) AND
                                    (
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
                        ) OR
                        (
                            (authorization_id = 5 AND $login) AND
                            (
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
                    $url_segment = explode('/', $url);
                    $_REQUEST['__cms_dynamic_widget'] = 'TRUE';
                    $_REQUEST['__cms_dynamic_widget_module'] = $url_segment[0];
                    $response = @Modules::run($url);
                    if(strlen($response) == 0){
                        $response = @Modules::run($url.'/index');
                    }
                    unset($_REQUEST['__cms_dynamic_widget']);
                    unset($_REQUEST['__cms_dynamic_widget_module']);
                    // fallback, Modules::run failed, use AJAX instead
                    if(strlen($response)==0){
                        $response = '<script type="text/javascript">';
                        $response .= '$(document).ready(function(){$("#__cms_widget_' . $row->widget_id . '").load("'.site_url($url).'?__cms_dynamic_widget=TRUE");});';
                        $response .= '</script>';
                    }
                    $content .= $response;
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
                    $(this).height(max_height);
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
                    ) OR
                    (
                        (authorization_id = 5 AND $login AND
                        (
                            (SELECT COUNT(*) FROM ".cms_table_name('main_group_privilege')." AS gp
                                WHERE
                                    gp.privilege_id=p.privilege_id AND
                                    gp.group_id IN
                                        (SELECT group_id FROM ".cms_table_name('main_group_user')." WHERE user_id = '" . addslashes($user_id) . "')
                            )>0)
                        )
                    )
                    ");
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

    public function cms_do_move_widget_after($src_widget_id, $dst_widget_id){
        $table_name = cms_table_name('main_widget');
        $this->__cms_reindex_widget();
        // get src record index
        $query = $this->db->select('index')
            ->from($table_name)
            ->where('widget_id', $src_widget_id)
            ->get();
        $row = $query->row();
        $src_index = $row->index;
        // reduce index of everything after src record
        $query = $this->db->select('widget_id, index')
            ->from($table_name)
            ->where('index >', $src_index)
            ->get();
        foreach($query->result() as $row){
            $widget_id = $row->widget_id;
            $index = $row->index - 1;
            $this->db->update($table_name,
                array('index' => $index),
                array('widget_id' => $widget_id));
        }
        // get dst record index
        $query = $this->db->select('index')
            ->from($table_name)
            ->where('widget_id', $dst_widget_id)
            ->get();
        $row = $query->row();
        $dst_index = $row->index;
        // add index of everything after dst record
        $query = $this->db->select('widget_id, index')
            ->from($table_name)
            ->where('index >', $dst_index)
            ->get();
        foreach($query->result() as $row){
            $widget_id = $row->widget_id;
            $index = $row->index + 1;
            $this->db->update($table_name,
                array('index' => $index),
                array('widget_id' => $widget_id));
        }
        // put src after dst
        $this->db->update($table_name,
            array('index' => $dst_index + 1),
            array('widget_id' => $src_widget_id));
        $this->__cms_reindex_widget();
    }

    public function cms_do_move_widget_before($src_widget_id, $dst_widget_id){
        $table_name = cms_table_name('main_widget');
        $this->__cms_reindex_widget();
        // get src record index
        $query = $this->db->select('index')
            ->from($table_name)
            ->where('widget_id', $src_widget_id)
            ->get();
        $row = $query->row();
        $src_index = $row->index;
        // reduce index of everything after src record
        $query = $this->db->select('widget_id, index')
            ->from($table_name)
            ->where('index >', $src_index)
            ->get();
        foreach($query->result() as $row){
            $widget_id = $row->widget_id;
            $index = $row->index - 1;
            $this->db->update($table_name,
                array('index' => $index),
                array('widget_id' => $widget_id));
        }
        // get dst record index
        $query = $this->db->select('index')
            ->from($table_name)
            ->where('widget_id', $dst_widget_id)
            ->get();
        $row = $query->row();
        $dst_index = $row->index;
        // add index of dst record and everything after dst record
        $query = $this->db->select('widget_id, index')
            ->from($table_name)
            ->where('index >=', $dst_index)
            ->get();
        foreach($query->result() as $row){
            $widget_id = $row->widget_id;
            $index = $row->index + 1;
            $this->db->update($table_name,
                array('index' => $index),
                array('widget_id' => $widget_id));
        }
        // put src after dst
        $this->db->update($table_name,
            array('index' => $dst_index),
            array('widget_id' => $src_widget_id));
        $this->__cms_reindex_widget();
    }

    public function cms_do_move_quicklink_after($src_quicklink_id, $dst_quicklink_id){
        $table_name = cms_table_name('main_quicklink');
        $this->__cms_reindex_quicklink();
        // get src record index
        $query = $this->db->select('index')
            ->from($table_name)
            ->where('quicklink_id', $src_quicklink_id)
            ->get();
        $row = $query->row();
        $src_index = $row->index;
        // reduce index of everything after src record
        $query = $this->db->select('quicklink_id, index')
            ->from($table_name)
            ->where('index >', $src_index)
            ->get();
        foreach($query->result() as $row){
            $quicklink_id = $row->quicklink_id;
            $index = $row->index - 1;
            $this->db->update($table_name,
                array('index' => $index),
                array('quicklink_id' => $quicklink_id));
        }
        // get dst record index
        $query = $this->db->select('index')
            ->from($table_name)
            ->where('quicklink_id', $dst_quicklink_id)
            ->get();
        $row = $query->row();
        $dst_index = $row->index;
        // add index of everything after dst record
        $query = $this->db->select('quicklink_id, index')
            ->from($table_name)
            ->where('index >', $dst_index)
            ->get();
        foreach($query->result() as $row){
            $quicklink_id = $row->quicklink_id;
            $index = $row->index + 1;
            $this->db->update($table_name,
                array('index' => $index),
                array('quicklink_id' => $quicklink_id));
        }
        // put src after dst
        $this->db->update($table_name,
            array('index' => $dst_index + 1),
            array('quicklink_id' => $src_quicklink_id));
        $this->__cms_reindex_quicklink();
    }

    public function cms_do_move_quicklink_before($src_quicklink_id, $dst_quicklink_id){
        $table_name = cms_table_name('main_quicklink');
        $this->__cms_reindex_quicklink();
        // get src record index
        $query = $this->db->select('index')
            ->from($table_name)
            ->where('quicklink_id', $src_quicklink_id)
            ->get();
        $row = $query->row();
        $src_index = $row->index;
        // reduce index of everything after src record
        $query = $this->db->select('quicklink_id, index')
            ->from($table_name)
            ->where('index >', $src_index)
            ->get();
        foreach($query->result() as $row){
            $quicklink_id = $row->quicklink_id;
            $index = $row->index - 1;
            $this->db->update($table_name,
                array('index' => $index),
                array('quicklink_id' => $quicklink_id));
        }
        // get dst record index
        $query = $this->db->select('index')
            ->from($table_name)
            ->where('quicklink_id', $dst_quicklink_id)
            ->get();
        $row = $query->row();
        $dst_index = $row->index;
        // add index of dst record and everything after dst record
        $query = $this->db->select('quicklink_id, index')
            ->from($table_name)
            ->where('index >=', $dst_index)
            ->get();
        foreach($query->result() as $row){
            $quicklink_id = $row->quicklink_id;
            $index = $row->index + 1;
            $this->db->update($table_name,
                array('index' => $index),
                array('quicklink_id' => $quicklink_id));
        }
        // put src after dst
        $this->db->update($table_name,
            array('index' => $dst_index),
            array('quicklink_id' => $src_quicklink_id));
        $this->__cms_reindex_quicklink();
    }

    public function cms_do_move_navigation_after($src_navigation_id, $dst_navigation_id){
        $table_name = cms_table_name('main_navigation');
        // get src record index
        $query = $this->db->select('parent_id, index')
            ->from($table_name)
            ->where('navigation_id', $src_navigation_id)
            ->get();
        $row = $query->row();
        $src_index = $row->index;
        $src_parent_id = $row->parent_id;
        $this->__cms_reindex_navigation($src_parent_id);
        // reduce index of everything after src record
        $query = $this->db->select('navigation_id, index')
            ->from($table_name)
            ->where('parent_id', $src_parent_id)
            ->where('index >', $src_index)
            ->get();
        foreach($query->result() as $row){
            $navigation_id = $row->navigation_id;
            $index = $row->index - 1;
            $this->db->update($table_name,
                array('index' => $index),
                array('navigation_id' => $navigation_id));
        }
        // get dst record index
        $query = $this->db->select('parent_id, index')
            ->from($table_name)
            ->where('navigation_id', $dst_navigation_id)
            ->get();
        $row = $query->row();
        $dst_index = $row->index;
        $dst_parent_id = $row->parent_id;
        $this->__cms_reindex_navigation($dst_parent_id);
        // add index of everything after dst record
        $query = $this->db->select('navigation_id, index')
            ->from($table_name)
            ->where('parent_id', $dst_parent_id)
            ->where('index >', $dst_index)
            ->get();
        foreach($query->result() as $row){
            $navigation_id = $row->navigation_id;
            $index = $row->index + 1;
            $this->db->update($table_name,
                array('index' => $index),
                array('navigation_id' => $navigation_id));
        }
        // put src after dst
        $this->db->update($table_name,
            array('index' => $dst_index + 1, 'parent_id' => $dst_parent_id),
            array('navigation_id' => $src_navigation_id));
        $this->__cms_reindex_navigation($src_parent_id);
        $this->__cms_reindex_navigation($dst_parent_id);
    }

    public function cms_do_move_navigation_before($src_navigation_id, $dst_navigation_id){
        $table_name = cms_table_name('main_navigation');
        // get src record index
        $query = $this->db->select('parent_id, index')
            ->from($table_name)
            ->where('navigation_id', $src_navigation_id)
            ->get();
        $row = $query->row();
        $src_index = $row->index;
        $src_parent_id = $row->parent_id;
        $this->__cms_reindex_navigation($src_parent_id);
        // reduce index of everything after src record
        $query = $this->db->select('navigation_id, index')
            ->from($table_name)
            ->where('parent_id', $src_parent_id)
            ->where('index >', $src_index)
            ->get();
        foreach($query->result() as $row){
            $navigation_id = $row->navigation_id;
            $index = $row->index - 1;
            $this->db->update($table_name,
                array('index' => $index),
                array('navigation_id' => $navigation_id));
        }
        // get dst record index
        $query = $this->db->select('parent_id, index')
            ->from($table_name)
            ->where('navigation_id', $dst_navigation_id)
            ->get();
        $row = $query->row();
        $dst_index = $row->index;
        $dst_parent_id = $row->parent_id;
        $this->__cms_reindex_navigation($dst_parent_id);
        // add index of dst record and everything after dst record
        $query = $this->db->select('navigation_id, index')
            ->from($table_name)
            ->where('parent_id', $dst_parent_id)
            ->where('index <=', $dst_index)
            ->get();
        foreach($query->result() as $row){
            $navigation_id = $row->navigation_id;
            $index = $row->index + 1;
            $this->db->update($table_name,
                array('index' => $index),
                array('navigation_id' => $navigation_id));
        }
        // put src after dst
        $this->db->update($table_name,
            array('index' => $dst_index, 'parent_id' => $dst_parent_id),
            array('navigation_id' => $src_navigation_id));
        $this->__cms_reindex_navigation($src_parent_id);
        $this->__cms_reindex_navigation($dst_parent_id);
    }

    public function cms_do_move_navigation_into($src_navigation_id, $dst_navigation_id){
        $table_name = cms_table_name('main_navigation');
        // get src record index
        $query = $this->db->select('parent_id, index')
            ->from($table_name)
            ->where('navigation_id', $src_navigation_id)
            ->get();
        $row = $query->row();
        $src_index = $row->index;
        $src_parent_id = $row->parent_id;
        $this->__cms_reindex_navigation($src_parent_id);
        // reduce index of everything after src record
        $query = $this->db->select('navigation_id, index')
            ->from($table_name)
            ->where('parent_id', $src_parent_id)
            ->where('index >', $src_index)
            ->get();
        foreach($query->result() as $row){
            $navigation_id = $row->navigation_id;
            $index = $row->index - 1;
            $this->db->update($table_name,
                array('index' => $index),
                array('navigation_id' => $navigation_id));
        }
        // get dst record index
        $query = $this->db->select('parent_id, index')
            ->from($table_name)
            ->where('navigation_id', $dst_navigation_id)
            ->get();
        $row = $query->row();
        $dst_index = $row->index;
        $dst_parent_id = $row->parent_id;
        $this->__cms_reindex_navigation($dst_parent_id);
        // add index of everything inside dst record
        $query = $this->db->select('navigation_id, index')
            ->from($table_name)
            ->where('parent_id', $dst_navigation_id)
            ->get();
        foreach($query->result() as $row){
            $navigation_id = $row->navigation_id;
            $index = $row->index + 1;
            $this->db->update($table_name,
                array('index' => $index),
                array('navigation_id' => $navigation_id));
        }
        // put src after dst
        $this->db->update($table_name,
            array('index' => 1, 'parent_id' => $dst_navigation_id),
            array('navigation_id' => $src_navigation_id));
        $this->__cms_reindex_navigation($src_parent_id);
        $this->__cms_reindex_navigation($dst_id);
    }

    /**
     * @author  goFrendiAsgard
     * @param   int navigation id
     * @desc    move quicklink up
     */
    public function cms_do_move_up_quicklink($quicklink_id){
        // re-index all
        $this->__cms_reindex_quicklink();
        // get the index again
        $query = $this->db->select('quicklink_id, index')
            ->from(cms_table_name('main_quicklink'))
            ->where('quicklink_id', $quicklink_id)
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
    public function cms_do_move_down_quicklink($quicklink_id){
        // re-index all
        $this->__cms_reindex_quicklink();
        // get the index again
        $query = $this->db->select('quicklink_id, index')
            ->from(cms_table_name('main_quicklink'))
            ->where('quicklink_id', $quicklink_id)
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
        $activation = $this->cms_get_config('cms_signup_activation');
        $data            = array(
            "user_name" => $user_name,
            "email" => $email,
            "real_name" => $real_name,
            "password" => md5($password),
            "active" => $activation == 'automatic'
        );
        $this->db->insert(cms_table_name('main_user'), $data);
        // send activation code if needed
        if ($activation == 'by_mail') {
            $this->cms_generate_activation_code($user_name, TRUE, 'SIGNUP');
        }

        if($this->cms_is_module_active('gofrendi.noCMS.multisite') && $this->cms_get_config('cms_add_subsite_on_register') == 'TRUE'){
            $current_user_id = $this->db->select('user_id')
                ->from(cms_table_name('main_user'))
                ->where('user_name', $user_name)
                ->get()->row()->user_id;
            $module_path = $this->cms_module_path('gofrendi.noCMS.multisite');
            $this->load->model('installer/install_model');
            $this->load->model($module_path.'/subsite_model');
            $install_model = new Install_Model();
            $subsite_model = new Subsite_Model();
            // get these from old setting
            $this->install_model->db_table_prefix              = cms_table_prefix();
            $this->install_model->is_subsite                   = TRUE;
            $this->install_model->subsite                      = strtolower(str_replace(' ', '',$user_name));
            $this->install_model->subsite_aliases              = '';
            $this->install_model->set_subsite();
            $this->install_model->admin_email                  = $email;
            $this->install_model->admin_real_name              = $real_name;
            $this->install_model->admin_user_name              = $user_name;
            $this->install_model->admin_password               = $password;
            $this->install_model->admin_confirm_password       = $password;
            $this->install_model->hide_index                   = TRUE;
            $this->install_model->gzip_compression             = FALSE;
            $check_installation = $this->install_model->check_installation();
            $success = $check_installation['success'];
            $module_installed = FALSE;
            if($success){
                $config = array('subsite_home_content'=> $this->cms_get_config('cms_subsite_home_content', TRUE));
                $this->install_model->build_database($config);
                $this->install_model->build_configuration($config);
                $module_installed = $this->install_model->install_modules();
            }
            if(!isset($_SESSION)){
                session_start();
            }
            // hack module path by changing the session, don't forget to unset !!!
            $this->cms_override_module_path($module_path);
            $data = array(
                'name'=> $this->install_model->subsite,
                'description'=>$user_name.' website',
                'use_subdomain'=>$this->cms_get_config('cms_subsite_use_subdomain')=='TRUE'?1:0,
                'user_id'=>$current_user_id,
                'active'=>$activation == 'automatic'
            );
            $this->db->insert($this->cms_complete_table_name('subsite'), $data);
            $this->load->model($this->cms_module_path().'/subsite_model');
            $this->subsite_model->update_configs();
            $this->cms_reset_overriden_module_path();

            if(!$module_installed){
                // hack script, will be added and removed in next view
                $install_module_script = '<script type="text/javascript">
                    $(document).ready(function(){
                        var modules =  ["blog", "static_accessories", "contact_us"];
                        var done = 0;
                        for(var i=0; i<modules.length; i++){
                            var module = modules[i];
                            $.ajax({
                                "url": "{{ SITE_URL }}/"+module+"/install/activate/?__cms_subsite='.$this->install_model->subsite.'",
                                "type": "POST",
                                "dataType": "json",
                                "async": true,
                                "data":{
                                        "silent" : true,
                                        "identity": "'.$user_name.'",
                                        "password": "'.$password.'"
                                    },
                                "success": function(response){
                                        if(!response["success"]){
                                            console.log("error installing "+response["module_path"]);
                                        }
                                    },
                            });
                        }
                    });</script>';
                $this->cms_flash_metadata($install_module_script);
            }
        }

    }

    /**
     * @author  goFrendiAsgard
     * @param   string tmp_module_path
     * @desc    pretend to be tmp_module_path to adjust the table prefix. This only affect table name
     */
    public function cms_override_module_path($tmp_module_path){
        if(!isset($_SESSION)){
            session_start();
        }
        $_SESSION['__cms_override_module_path'] = $tmp_module_path;
    }

    /**
     * @author  goFrendiAsgard
     * @desc    cancel effect created by cms_override_module_path
     */
    public function cms_reset_overriden_module_path(){
        if(!isset($_SESSION)){
            session_start();
        }
        unset($_SESSION['__cms_override_module_path']);
    }

    /**
     * @author  goFrendiAsgard
     * @param   string content
     * @desc    flash content to be served as metadata on next call of $this->view in controller
     */
    public function cms_flash_metadata($content){
        if(!isset($_SESSION)){
            session_start();
        }
        if(!isset($_SESSION['__cms_flash_metadata'])){
            $_SESSION['__cms_flash_metadata'] = '';
        }
        $_SESSION['__cms_flash_metadata'] .= $content;
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
                        if(!isset($subsite_allowed) || (is_array($subsite_allowed) && !in_array(CMS_SUBSITE, $subsite_allowed))){
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
        // hack module path by changing the session, don't forget to unset !!!
        if(isset($_SESSION['__cms_override_module_path'])){
            return $_SESSION['__cms_override_module_path'];
        }else{
            if (!isset($module_name) || $module_name === NULL) {
                if(isset($_REQUEST['__cms_dynamic_widget_module'])){
                    $module = $_REQUEST['__cms_dynamic_widget_module'];
                }else{
                    $module = $this->router->fetch_module();
                }
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
                        if(!isset($subsite_allowed) || (is_array($subsite_allowed) && !in_array(CMS_SUBSITE, $subsite_allowed))){
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
                    $email_subject = $this->cms_get_config('cms_email_forgot_subject', TRUE);
                    $email_message = $this->cms_get_config('cms_email_forgot_message', TRUE);
                } else if (strtoupper($reason) == 'SIGNUP') {
                    $email_subject = $this->cms_get_config('cms_email_signup_subject', TRUE);
                    $email_message = $this->cms_get_config('cms_email_signup_message', TRUE);
                }

                $email_message = str_replace('{{ user_real_name }}', $real_name, $email_message);
                $email_message = str_replace('{{ activation_code }}', $activation_code, $email_message);
                //send email to user
                log_message('ERROR', var_export(array($email_message, $real_name, $activation_code), TRUE));
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

            $this->_cms_set_user_subsite_activation($user_id, 1);

            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function _cms_set_user_subsite_activation($user_id, $active){
        if($this->cms_is_module_active('gofrendi.noCMS.multisite')){
            $module_path = $this->cms_module_path('gofrendi.noCMS.multisite');
            $this->cms_override_module_path($module_path);
            $data = array('active'=>$active);
            $where = array('user_id'=>$user_id);
            $this->db->update($this->cms_complete_table_name('subsite'), $data, $where);
            log_message('ERROR', $this->cms_module_path());
            log_message('ERROR', $module_path);
            $this->load->model($this->cms_module_path().'/subsite_model');
            $this->subsite_model->update_configs();
            $this->cms_reset_overriden_module_path();
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
        // look for available language which are probably not registered
        if(!isset($_SESSION)){
            session_start();
        }
        if(!isset($_SESSION['__cms_language_uptodate'])){
            $this->load->helper('file');
            $new_lang = array();
            $language_list = get_filenames(APPPATH.'../assets/nocms/languages');
            foreach ($language_list as $language){
                if(preg_match('/\.php$/i', $language)){
                    $lang = str_ireplace('.php', '', $language);
                    $exist = $this->db->select('code')->from(cms_table_name('main_language'))
                        ->where('code',$lang)->get()->num_rows() > 0;
                    if(!$exist){
                        $new_lang[] = $lang;
                    }
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
                        $exist = $this->db->select('code')->from(cms_table_name('main_language'))
                            ->where('code',$module_language)->get()->num_rows() > 0;
                        if(!$exist && !in_array($module_language, $new_lang)){
                            $new_lang[] = $module_language;
                        }
                    }
                }
            }
            // add the language to database
            foreach($new_lang as $lang){
                $this->db->insert(cms_table_name('language'),array('name'=>$lang,'code'=>$lang));
            }
            $_SESSION['__cms_language_uptodate'] = TRUE;
        }
        // grab it
        $result = $this->db->select('name,code,iso_code')
            ->from(cms_table_name('main_language'))
            ->order_by('name')
            ->get()->result();
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

            $result = $this->db->select('key, translation')
                ->from(cms_table_name('main_detail_language'))
                ->join(cms_table_name('main_language'), cms_table_name('main_detail_language').'.id_language = '.cms_table_name('main_language').'.language_id')
                ->where('name', $this->cms_language())
                ->get()->result();
            foreach($result as $row){
                $lang[$row->key] = $row->translation;
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
