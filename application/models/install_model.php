<?php
class Install_Model extends CI_Model{

    public $db_protocol     = 'mysql';
    public $db_host         = 'localhost';
    public $db_port         = '3306';
    public $db_name         = '';
    public $db_username     = 'root';
    public $db_password     = '';
    public $db_table_prefix = 'cms';

    public $admin_email     = 'admin@admin.com';
    public $admin_user_name = 'admin';
    public $admin_real_name = 'Rina Suzuki';
    public $admin_password  = 'admin';

    public $hide_index       = FALSE;
    public $gzip_compression = FALSE;

    public $auth_enable_facebook         = FALSE;
    public $auth_facebook_app_id         = '';
    public $auth_facebook_app_secret     = '';
    public $auth_enable_twitter          = FALSE;
    public $auth_twitter_app_key         = '';
    public $auth_twitter_app_secret      = '';
    public $auth_enable_google           = FALSE;
    public $auth_google_app_id           = '';
    public $auth_google_app_secret       = '';
    public $auth_enable_yahoo            = FALSE;
    public $auth_yahoo_app_id            = '';
    public $auth_yahoo_app_secret        = '';
    public $auth_enable_linkedin         = FALSE;
    public $auth_linkedin_app_key        = '';
    public $auth_linkedin_app_secret     = '';
    public $auth_enable_myspace          = FALSE;
    public $auth_myspace_app_key         = '';
    public $auth_myspace_app_secret      = '';
    public $auth_enable_foursquare       = FALSE;
    public $auth_foursquare_app_id       = '';
    public $auth_foursquare_app_secret   = '';
    public $auth_enable_windows_live     = FALSE;
    public $auth_windows_live_app_id     = '';
    public $auth_windows_live_app_secret = '';
    public $auth_enable_open_id          = FALSE;
    public $auth_enable_aol              = FALSE;

    protected $db_no_error = TRUE;
    protected $table_drop_list = array(
            'main_config','main_module_dependency','main_module','main_group_privilege',
            'main_group_navigation','main_group_widget','main_group_user','main_group',
            'main_quicklink','main_navigation','main_widget','main_privilege','main_user',
            'main_authorization','ci_sessions'
        );

    protected $DEFAULT_PORT = array(
            'mysql' => '3306',
            'pgsql' => '5432',
            'sqlite' => '',
        );

    protected function build_dsn(){
        if($this->db_port == ''){
            $this->db_port = $this->DEFAULT_PORT[$this->db_protocol];
        }
        if($this->db_protocol=='sqlite'){
            $dsn = 'sqlite:'.FCPATH.'db.sqlite';
        }else{
            $dsn = $this->db_protocol.':host='.$this->db_host.';port='.$this->db_port;
            if($this->db_name != ''){
                $dsn .= ';dbname='.$this->db_name;
            }
        }
        return $dsn;
    }

    protected function load_database(){
        $dsn = $this->build_dsn();
        $db_config = array(
            'dsn' => $dsn,
            'hostname' => $this->db_host,
            'username' => $this->db_username,
            'password' => $this->db_password,
            'database' => $this->db_name,
            'dbdriver' => 'pdo',
            'dbprefix' => '',
            'pconnect' => TRUE,
            'db_debug' => FALSE,
            'cache_on' => FALSE,
            'cachedir' => '',
            'char_set' => 'utf8',
            'dbcollat' => 'utf8_general_ci',
            'swap_pre' => '',
            'autoinit' => TRUE,
            'encrypt' => FALSE,
            'compress' => FALSE,
            'stricton' => FALSE,
            'failover' => array()
        );
        $db = $this->load->database($db_config, TRUE);
        if($db->conn_id === FALSE){
            return FALSE;
        }else{
            $this->db = $db;
            $this->dbutil = $this->load->dbutil($db, TRUE);
            $this->dbforge = $this->load->dbforge($db, TRUE);
            return $db;
        }
    }

    public function check_connection(){
        $success = TRUE;
        $error_list = array();
        $warning_list = array();
        $db = $this->load_database();
        if($db === FALSE){
            $success =  FALSE;
            $error_list[] = 'Cannot connect using provided database setting';
        }
        return array(
                'success' => $success,
                'error_list' => $error_list,
                'warning_list' => $warning_list,
            );
    }

    public function check_writable_file(){
        $success = TRUE;
        $error_list = array();
        $warning_list = array();
        if (!is_writable(FCPATH)) {
            $success  = FALSE;
            $error_list[] = 'No-CMS directory is not writable';
        }
        if (!is_writable(FCPATH.'assets/caches')) {
            $success  = FALSE;
            $error_list[] = "Asset cache directory (".FCPATH."assets/caches) is not writable";
        }
        if (!is_writable(APPPATH.'config/config.php')) {
            $success  = FALSE;
            $error_list[] = APPPATH."config/config.php is not writable";
        }
        if (!is_writable(APPPATH.'config/cms_config.php')) {
            $success  = FALSE;
            $error_list[] = APPPATH."config/cms_config.php is not writable";
        }
        if (!is_writable(APPPATH.'config/database.php')) {
            $success  = FALSE;
            $error_list[] = APPPATH."config/database.php is not writable";
        }
        if (!is_writable(APPPATH.'config/hybridauthlib.php')) {
            $success  = FALSE;
            $error_list[] = APPPATH."config/hybridauthlib.php is not writable";
        }
        if (!is_writable(APPPATH.'logs')) {
            $success  = FALSE;
            $error_list[] = APPPATH."logs is not writable";
        }
        return array(
                'success' => $success,
                'error_list' => $error_list,
                'warning_list' => $warning_list,
            );
    }

    protected function create_table($table_name, $fields, $primary_key=NULL){
        $this->dbforge->add_field($fields);
        if(!isset($primary_key)){
            foreach($fields as $key=>$value){
                $primary_key = $key;
                break;
            }
        }
        if(isset($primary_key)){
            $this->dbforge->add_key($primary_key, TRUE);
        }
        $this->db_no_error = $this->dbforge->create_table($this->db_table_prefix.'_'.$table_name) && $this->db_no_error;
        return $this->db->last_query();
    }

    protected function create_all_table(){
        $sql_list = array();

        // define frequently used types
        $type_primary_key = array(
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            );
        $type_int = array(
            'type' => 'INT',
            'constraint' => 5,
            'unsigned' => TRUE
        );
        $type_foreign_key = array(
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE
            );
        $type_foreign_key_not_null = array(
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'null' => FALSE,
            );
        $type_foreign_key_default_1 = array(
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'default'=> 1,
                'null' => FALSE,
            );
        $type_index = array(
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'default'=> 0,
                'null' => FALSE,
            );
        $type_boolean_true = array(
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'default'=> 1,
                'null' => FALSE,
            );
        $type_boolean_false = array(
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'default'=> 0,
                'null' => FALSE,
            );
        $type_text = array(
                'type' => 'TEXT',
                'null' => TRUE,
            );
        $type_varchar_small = array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE,
            );
        $type_varchar_large = array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE,
            );
        $type_password = array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            );
        $type_varchar_small_strict = array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => FALSE,
            );
        $type_varchar_large_strict = array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => FALSE,
            );

        // MAKE TABLES ====================================================

        // AUTHORIZATION
        $fields = array(
                'authorization_id' => $type_primary_key,
                'authorization_name' => $type_varchar_small_strict,
                'description' => $type_text,
            );
        $sql_list[] = $this->create_table('main_authorization',$fields);

        // GROUP
        $fields = array(
                'group_id' => $type_primary_key,
                'group_name' => $type_varchar_small_strict,
                'description' => $type_text,
            );
        $sql_list[] = $this->create_table('main_group',$fields);

        // WIDGET
        $fields = array(
                'widget_id' => $type_primary_key,
                'widget_name' => $type_varchar_small_strict,
                'title' => $type_varchar_small_strict,
                'description' => $type_text,
                'url' => $type_varchar_large,
                'authorization_id' => $type_foreign_key_default_1,
                'active' => $type_boolean_false,
                'index' => $type_index,
                'is_static' => $type_boolean_false,
                'static_content' => $type_text,
                'slug' => $type_varchar_large,
            );
        $sql_list[] = $this->create_table('main_widget',$fields);

        // NAVIGATION
        $fields = array(
                'navigation_id' => $type_primary_key,
                'navigation_name' => $type_varchar_small_strict,
                'parent_id' => $type_foreign_key,
                'title' => $type_varchar_small_strict,
                'page_title' => $type_varchar_small,
                'page_keyword' => $type_varchar_large,
                'description' => $type_text,
                'url' => $type_varchar_large,
                'authorization_id' => $type_foreign_key_default_1,
                'active' => $type_boolean_false,
                'index' => $type_index,
                'is_static' => $type_boolean_false,
                'static_content' => $type_text,
                'only_content' => $type_boolean_false,
                'default_theme' => $type_varchar_small,
            );
        $sql_list[] = $this->create_table('main_navigation',$fields);

        // QUICKLINK
        $fields = array(
                'quicklink_id' => $type_primary_key,
                'navigation_id' => $type_foreign_key_not_null,
                'index' => $type_index,
            );
        $sql_list[] = $this->create_table('main_quicklink',$fields);

        // PRIVILEGE
        $fields = array(
                'privilege_id' => $type_primary_key,
                'privilege_name' => $type_varchar_small_strict,
                'title' => $type_varchar_small_strict,
                'description' => $type_text,
                'authorization_id' => $type_foreign_key_default_1,
            );
        $sql_list[] = $this->create_table('main_privilege',$fields);

        // USER
        $fields = array(
                'user_id' => $type_primary_key,
                'user_name' => $type_varchar_small_strict,
                'email' => $type_varchar_small_strict,
                'password' => $type_password,
                'activation_code' => $type_varchar_small,
                'real_name' => $type_varchar_large,
                'active' => $type_boolean_true,
                'auth_OpenID' => $type_varchar_large,
                'auth_Facebook' => $type_varchar_large,
                'auth_Twitter' => $type_varchar_large,
                'auth_Yahoo' => $type_varchar_large,
                'auth_LinkedIn' => $type_varchar_large,
                'auth_MySpace' => $type_varchar_large,
                'auth_Foursquare' => $type_varchar_large,
                'auth_AOL' => $type_varchar_large,
                'auth_Live' => $type_varchar_large,
            );
        $sql_list[] = $this->create_table('main_user',$fields);

        // GROUP WIDGET
        $fields = array(
                'id' => $type_primary_key,
                'group_id' => $type_foreign_key_not_null,
                'widget_id' => $type_foreign_key_not_null,
            );
        $sql_list[] = $this->create_table('main_group_widget',$fields);

        // GROUP NAVIGATION
        $fields = array(
                'id' => $type_primary_key,
                'group_id' => $type_foreign_key_not_null,
                'navigation_id' => $type_foreign_key_not_null,
            );
        $sql_list[] = $this->create_table('main_group_navigation',$fields);

        // GROUP PRIVILEGE
        $fields = array(
                'id' => $type_primary_key,
                'group_id' => $type_foreign_key_not_null,
                'privilege_id' => $type_foreign_key_not_null,
            );
        $sql_list[] = $this->create_table('main_group_privilege',$fields);

        // GROUP USER
        $fields = array(
                'id' => $type_primary_key,
                'group_id' => $type_foreign_key_not_null,
                'user_id' => $type_foreign_key_not_null,
            );
        $sql_list[] = $this->create_table('main_group_user',$fields);

        // MODULE
        $fields = array(
                'module_id' => $type_primary_key,
                'module_name' => $type_varchar_small_strict,
                'module_path' => $type_varchar_large_strict,
                'version' => $type_varchar_small,
                'user_id' => $type_foreign_key,
            );
        $sql_list[] = $this->create_table('main_module',$fields);

        // MODULE DEPENDENCY
        $fields = array(
                'module_dependency_id' => $type_primary_key,
                'module_id' => $type_foreign_key_not_null,
                'parent_id' => $type_foreign_key_not_null,
            );
        $sql_list[] = $this->create_table('main_group_user',$fields);

        // CONFIG
        $fields = array(
                'config_id' => $type_primary_key,
                'config_name' => $type_varchar_small_strict,
                'value' => $type_text,
                'description' => $type_text,
            );
        $sql_list[] = $this->create_table('main_config',$fields);

        // CI SESSION
        $fields = array(
                'session_id' => $type_varchar_small_strict,
                'ip_address' => $type_varchar_small_strict,
                'user_agent' => $type_varchar_small_strict,
                'last_activity' => $type_int,
                'user_data' => $type_text,
            );
        $sql_list[] = $this->create_table('ci_sessions',$fields);

        return $sql_list;
    }

    protected function insert_authorization($authorization_name, $description){
        $array = array(
                'authorization_name' => $authorization_name,
                'description' => $description,
            );
        $this->db_no_error = $this->db->insert($this->db_table_prefix.'_main_authorization', $array) && $this->db_no_error;
        return $this->db->last_query();
    }

    protected function insert_group($group_name, $description){
        $array = array(
                'group_name' => $group_name,
                'description' => $description,
            );
        $this->db_no_error = $this->db->insert($this->db_table_prefix.'_main_group', $array) && $this->db_no_error;
        return $this->db->last_query();
    }

    protected function insert_user(){
        $array = array(
                'user_name' => $this->admin_user_name,
                'email' => $this->admin_email,
                'password'=> md5($this->admin_password),
                'real_name' => $this->admin_real_name
            );
        $this->db_no_error = $this->db->insert($this->db_table_prefix.'_main_user', $array) && $this->db_no_error;
        return $this->db->last_query();
    }

    protected function insert_navigation($navigation_name, $parent_id, $title, $page_title, $page_keyword, $description, $url, $authorization_id, $index, $active, $is_static, $static_content, $only_content){
        $array = array(
                'navigation_name' => $navigation_name,
                'parent_id' => $parent_id,
                'title' => $title,
                'page_title' => $page_title,
                'page_keyword' => $page_keyword,
                'description' => $description,
                'url' => $url,
                'authorization_id' => $authorization_id,
                'index' => $index,
                'active' => $active,
                'is_static' => $is_static,
                'static_content' => $static_content,
                'only_content' => $only_content
            );
        $this->db_no_error = $this->db->insert($this->db_table_prefix.'_main_navigation', $array) && $this->db_no_error;
        return $this->db->last_query();
    }

    protected function insert_quicklink($navigation_id, $index){
        $array = array(
                'navigation_id' => $navigation_id,
                'index' => $index,
            );
        $this->db_no_error = $this->db->insert($this->db_table_prefix.'_main_quicklink', $array) && $this->db_no_error;
        return $this->db->last_query();
    }

    protected function insert_widget($widget_name, $title, $description, $url, $authorization_id, $active, $index, $is_static, $static_content, $slug){
        $array = array(
                'widget_name' => $widget_name,
                'title' => $title,
                'description' => $description,
                'url' => $url,
                'authorization_id' => $authorization_id,
                'active' => $active,
                'index' => $index,
                'is_static' => $is_static,
                'static_content' => $static_content,
                'slug' => $slug
            );
        $this->db_no_error = $this->db->insert($this->db_table_prefix.'_main_widget', $array) && $this->db_no_error;
        return $this->db->last_query();
    }

    protected function insert_privilege($privilege_name, $title, $description, $authorization_id){
        $array = array(
                'privilege_name' => $privilege_name,
                'title' => $title,
                'description' => $description,
                'authorization_id' => $authorization_id
            );
        $this->db_no_error = $this->db->insert($this->db_table_prefix.'_main_privilege', $array) && $this->db_no_error;
        return $this->db->last_query();
    }

    protected function insert_config($config_name, $value, $description){
        $array = array(
                'config_name' => $config_name,
                'value' => $value,
                'description' => $description
            );
        $this->db_no_error = $this->db->insert($this->db_table_prefix.'_main_config', $array) && $this->db_no_error;
        return $this->db->last_query();
    }

    protected function insert_group_user(){
        $array = array(
                'group_id' => 1,
                'user_id' => 1,
            );
        $this->db_no_error = $this->db->insert($this->db_table_prefix.'_main_group_user', $array) && $this->db_no_error;
        return $this->db->last_query();
    }

    protected function insert_all_data(){
        $sql_list = array();
        // authorization
        $sql_list[] = $this->insert_authorization('Everyone', 'All visitor of the web are permitted (e.g:view blog content)');
        $sql_list[] = $this->insert_authorization('Unauthenticated', 'Only non-member visitor, they who hasn\'t log in yet (e.g:view member registration page)');
        $sql_list[] = $this->insert_authorization('Authenticated', 'Only member (e.g:change password)');
        $sql_list[] = $this->insert_authorization('Authorized', 'Only member with certain privilege (depend on group)');
        // group
        $sql_list[] = $this->insert_group('Admin', 'Every member of this group can do everything possible, but only programmer can turn the impossible into real :D');
        $sql_list[] = $this->insert_group('Employee', 'Example');
        // user
        $sql_list[] = $this->insert_user();
        // navigation
        $sql_list[] = $this->insert_navigation('main_login', NULL, 'Login', 'Login', NULL, 'Visitor need to login for authentication', 'main/login', 2, 1, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_forgot', NULL, 'Forgot Password', 'Forgot', NULL, 'Accidentally forgot password', 'main/forgot', 2, 3, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_logout', NULL, 'Logout', 'Logout', NULL, 'Logout for deauthentication', 'main/logout', 3, 2, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_management', NULL, 'CMS Management', 'CMS Management', NULL, 'The main management of the CMS. Including User, Group, Privilege and Navigation Management', 'main/management', 4, 6, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_register', NULL, 'Register', 'Register', NULL, 'New User Registration', 'main/register', 2, 4, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_change_profile', NULL, 'Change Profile', 'Change Profile', NULL, 'Change Current Profile', 'main/change_profile', 3, 5, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_group_management', 4, 'Group Management', 'Group Management', NULL, 'Group Management', 'main/group', 4, 0, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_navigation_management', 4, 'Navigation Management', 'Navigation Management', NULL, 'Navigation management', 'main/navigation', 4, 3, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_privilege_management', 4, 'Privilege Management', 'Privilege Management', NULL, 'Privilege Management', 'main/privilege', 4, 2, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_user_management', 4, 'User Management', 'User Management', NULL, 'Manage User', 'main/user', 4, 1, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_module_management', 4, 'Module Management', 'Module Management', NULL, 'Install Or Uninstall Thirdparty Module', 'main/module_management', 4, 5, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_change_theme', 4, 'Change Theme', 'Change Theme', NULL, 'Change Theme', 'main/change_theme', 4, 6, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_widget_management', 4, 'Widget Management', 'Widget Management', NULL, 'Manage Widgets', 'main/widget', 4, 4, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_quicklink_management', 4, 'Quick Link Management', 'Quick Link Management', NULL, 'Manage Quick Link', 'main/quicklink', 4, 7, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_config_management', 4, 'Configuration Management', 'Configuration Management', NULL, 'Manage Configuration Parameters', 'main/config', 4, 8, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_index', NULL, 'Home', 'Home', NULL, 'There is no place like home :D', 'main/index', 1, 0, 1, 1, '<h3>\n  Welcome {{ user_name }}</h3>\n<p>\n This is the home page. You have several options to modify this page.</p>\n<ul>\n    <li>\n      <b>Using static page</b>\n      <p>\n           You can <em>activate</em> <strong>static option</strong> and <em>edit</em> the <strong>static content</strong> by using <a href="{{ site_url }}main/navigation/edit/16">Navigation Management</a><br />\n           This is the most recommended way to do.</p>\n   </li>\n <li>\n      <b>Redirect default controller</b>\n        <p>\n           You can modify <code>$route[&#39;default_controller&#39;]</code> variable on<br />\n            <code>/application/config/routes.php</code>, around line 41.<br />\n            Please make sure that your default controller is valid.<br />\n         This is recommended if you also want your own page to be a default homepage.</p>\n  </li>\n <li>\n      <b>Using dynamic page and edit the view manually</b>\n      <p>\n           You can <em>deactivate</em>&nbsp;<strong>static option</strong> by using <a href="{{ site_url }}main/navigation/edit/16">Navigation Management</a><br />\n          and edit the corresponding view on <code>/modules/main/index.php</code></p>\n   </li>\n</ul>\n<hr />\n<p>\n <b>Any other question? : </b><br />\n   CodeIgniter forum member can visit No-CMS thread here: <a href="http://codeigniter.com/forums/viewthread/209171/">http://codeigniter.com/forums/viewthread/209171/</a><br />\n  Github user can visit No-CMS repo: <a href="https://github.com/goFrendiAsgard/No-CMS/">https://github.com/goFrendiAsgard/No-CMS/</a><br />\n    While normal people can visit No-CMS blog: <a href="http://www.getnocms.com/">http://www.getnocms.com/</a><br />\n  In case of you&#39;ve found a critical bug, you can also email me at <a href="mailto:gofrendiasgard@gmail.com">gofrendiasgard@gmail.com</a><br />\n That&#39;s all. Start your new adventure with No-CMS !!!</p>\n', 0);
        $sql_list[] = $this->insert_navigation('main_language', NULL, 'Language', 'Language', NULL, 'Choose the language', 'main/language', 1, 0, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_third_party_auth', NULL, 'Third Party Authentication', 'Third Party Authentication', NULL, 'Third Party Authentication', 'main/hauth/index', 1, 0, 1, 0, NULL, 0);
        // quicklink
        $sql_list[] = $this->insert_quicklink(16, 0);
        $sql_list[] = $this->insert_quicklink(5, 1);
        $sql_list[] = $this->insert_quicklink(2, 2);
        $sql_list[] = $this->insert_quicklink(4, 3);
        // widget
        $sql_list[] = $this->insert_widget('left_navigation', 'Left Navigation', '', 'main/widget_left_nav', 1, 1, 1, 0, NULL, NULL);
        $sql_list[] = $this->insert_widget('top_navigation', 'Top Navigation', '', 'main/widget_top_nav', 1, 1, 1, 0, NULL, NULL);
        $sql_list[] = $this->insert_widget('quicklink', 'Quicklinks', '', 'main/widget_quicklink', 1, 1, 1, 0, NULL, NULL);
        $sql_list[] = $this->insert_widget('login', 'Login', 'Visitor need to login for authentication', 'main/widget_login', 2, 1, 0, 0, '<form action="{{ site_url }}main/login" method="post" accept-charset="utf-8"><label>Identity</label><br><input type="text" name="identity" value=""><br><label>Password</label><br><input type="password" name="password" value=""><br><input type="submit" name="login" value="Log In"></form>', 'sidebar, user_widget');
        $sql_list[] = $this->insert_widget('logout', 'User Info', 'Logout', 'main/widget_logout', 3, 1, 1, 1, '{{ language:Welcome }} {{ user_name }}<br />\n<a href="{{ site_url }}main/logout">{{ language:Logout }}</a><br />', 'sidebar, user_widget');
        $sql_list[] = $this->insert_widget('social_plugin', 'Share This Page !!', 'Addthis', 'main/widget_social_plugin', 1, 1, 2, 1, '<!-- AddThis Button BEGIN -->\n<div class="addthis_toolbox addthis_default_style "><a class="addthis_button_preferred_1"></a> <a class="addthis_button_preferred_2"></a> <a class="addthis_button_preferred_3"></a> <a class="addthis_button_preferred_4"></a> <a class="addthis_button_preferred_5"></a> <a class="addthis_button_preferred_6"></a> <a class="addthis_button_preferred_7"></a> <a class="addthis_button_preferred_8"></a> <a class="addthis_button_preferred_9"></a> <a class="addthis_button_preferred_10"></a> <a class="addthis_button_preferred_11"></a> <a class="addthis_button_preferred_12"></a> <a class="addthis_button_preferred_13"></a> <a class="addthis_button_preferred_14"></a> <a class="addthis_button_preferred_15"></a> <a class="addthis_button_preferred_16"></a> <a class="addthis_button_compact"></a> <a class="addthis_counter addthis_bubble_style"></a></div>\n<script src="http://s7.addthis.com/js/250/addthis_widget.js?domready=1" type="text/javascript"></script>\n<!-- AddThis Button END -->', 'sidebar');
        $sql_list[] = $this->insert_widget('google_search', 'Search', 'Search from google', '', 1, 0, 3, 1, '<!-- Google Custom Search Element -->\n<div id="cse" style="width: 100%;">Loading</div>\n<script src="http://www.google.com/jsapi" type="text/javascript"></script>\n<script type="text/javascript">// <![CDATA[\n    google.load(\'search\', \'1\');\n    google.setOnLoadCallback(function(){var cse = new google.search.CustomSearchControl();cse.draw(\'cse\');}, true);\n// ]]></script>', 'sidebar');
        $sql_list[] = $this->insert_widget('google_translate', 'Translate !!', '<p>The famous google translate</p>', '', 1, 0, 4, 1, '<!-- Google Translate Element -->\n<div id="google_translate_element" style="display:block"></div>\n<script>\nfunction googleTranslateElementInit() {\n  new google.translate.TranslateElement({pageLanguage: "af"}, "google_translate_element");\n};\n</script>\n<script src="http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>\n', 'sidebar');
        $sql_list[] = $this->insert_widget('calendar', 'Calendar', 'Indonesian Calendar', '', 1, 0, 5, 1, '<!-------Do not change below this line------->\n<div align="center" height="200px">\n    <iframe align="center" src="http://www.calendarlabs.com/calendars/web-content/calendar.php?cid=1001&uid=162232623&c=22&l=en&cbg=C3D9FF&cfg=000000&hfg=000000&hfg1=000000&ct=1&cb=1&cbc=2275FF&cf=verdana&cp=bottom&sw=0&hp=t&ib=0&ibc=&i=" width="170" height="155" marginwidth=0 marginheight=0 frameborder=no scrolling=no allowtransparency=\'true\'>\n    Loading...\n    </iframe>\n    <div align="center" style="width:140px;font-size:10px;color:#666;">\n        Powered by <a  href="http://www.calendarlabs.com/" target="_blank" style="font-size:10px;text-decoration:none;color:#666;">Calendar</a> Labs\n    </div>\n</div>\n\n<!-------Do not change above this line------->', 'sidebar');
        $sql_list[] = $this->insert_widget('google_map', 'Map', 'google map', '', 1, 0, 6, 1, '<!-- Google Maps Element Code -->\n<iframe frameborder=0 marginwidth=0 marginheight=0 border=0 style="border:0;margin:0;width:150px;height:250px;" src="http://www.google.com/uds/modules/elements/mapselement/iframe.html?maptype=roadmap&element=true" scrolling="no" allowtransparency="true"></iframe>', 'sidebar');
        $sql_list[] = $this->insert_widget('donate_nocms', 'Donate No-CMS', 'No-CMS Donation', NULL, 1, 1, 7, 1, '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">\n<input type="hidden" name="cmd" value="_s-xclick">\n<input type="hidden" name="hosted_button_id" value="YDES6RTA9QJQL">\n<input type="image" src="{{ base_url }}assets/nocms/images/donation.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" width="165px" height="auto" style="width:165px!important;" />\n<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">\n</form>', 'advertisement');
        // privilege
        $sql_list[] = $this->insert_privilege('cms_install_module', 'Install Module', 'Install Module is a very critical privilege, it allow authorized user to Install a module to the CMS.<br />By Installing module, the database structure can be changed. There might be some additional navigation and privileges added.<br /><br />You\'d be better to give this authorization only authenticated and authorized user. (I suggest to make only admin have such a privilege)\n&nbsp;', 4);
        $sql_list[] = $this->insert_privilege('cms_manage_access', 'Manage Access', 'Manage access\n&nbsp;', 4);
        // config
        $sql_list[] = $this->insert_config('site_name', 'No-CMS', 'Site title');
        $sql_list[] = $this->insert_config('site_slogan', 'A Free CodeIgniter Based CMS Framework', 'Site slogan');
        $sql_list[] = $this->insert_config('site_logo', '{{ base_url }}assets/nocms/images/No-CMS-logo.png', 'Site logo');
        $sql_list[] = $this->insert_config('site_favicon', '{{ base_url }}assets/nocms/images/No-CMS-favicon.png', 'Site favicon');
        $sql_list[] = $this->insert_config('site_footer', 'goFrendiAsgard &copy; 2011', 'Site footer');
        $sql_list[] = $this->insert_config('site_theme', 'neutral', 'Site theme');
        $sql_list[] = $this->insert_config('site_language', 'english', 'Site language');
        $sql_list[] = $this->insert_config('max_menu_depth', '5', 'Depth of menu recursive');
        $sql_list[] = $this->insert_config('cms_email_reply_address', 'no-reply@No-CMS.com', 'Email address');
        $sql_list[] = $this->insert_config('cms_email_reply_name', 'admin of No-CMS', 'Email name');
        $sql_list[] = $this->insert_config('cms_email_forgot_subject', 'Re-activate your account at No-CMS', 'Email subject sent when user forgot his/her password');
        $sql_list[] = $this->insert_config('cms_email_forgot_message', 'Dear, {{ user_real_name }}<br />Click <a href="{{ site_url }}main/forgot/{{ activation_code }}">{{ site_url }}main/forgot/{{ activation_code }}</a> to reactivate your account', 'Email message sent when user forgot his/her password');
        $sql_list[] = $this->insert_config('cms_email_signup_subject', 'Activate your account at No-CMS', 'Email subject sent to activate user');
        $sql_list[] = $this->insert_config('cms_email_signup_message', 'Dear, {{ user_real_name }}<br />Click <a href="{{ site_url }}main/activate/{{ activation_code }}">{{ site_url }}main/activate/{{ activation_code }}</a> to activate your account', 'Email message sent to activate user');
        $sql_list[] = $this->insert_config('cms_signup_activation', 'FALSE', 'Send activation email to new member. Default : false, Alternatives : true, false');
        $sql_list[] = $this->insert_config('cms_email_useragent', 'Codeigniter', 'Default : CodeIgniter');
        $sql_list[] = $this->insert_config('cms_email_protocol', 'smtp', 'Default : smtp, Alternatives : mail, sendmail, smtp');
        $sql_list[] = $this->insert_config('cms_email_mailpath', '/usr/sbin/sendmail','Default : /usr/sbin/sendmail');
        $sql_list[] = $this->insert_config('cms_email_smtp_host', 'ssl://smtp.googlemail.com','eg : ssl://smtp.googlemail.com');
        $sql_list[] = $this->insert_config('cms_email_smtp_user', 'your_gmail_address@gmail.com','eg : your_gmail_address@gmail.com');
        $sql_list[] = $this->insert_config('cms_email_smtp_pass', '','your password');
        $sql_list[] = $this->insert_config('cms_email_smtp_port', '465','smtp port, default : 465');
        $sql_list[] = $this->insert_config('cms_email_smtp_timeout', '30','default : 30');
        $sql_list[] = $this->insert_config('cms_email_wordwrap', 'TRUE', 'Enable word-wrap. Default : true, Alternatives : true, false');
        $sql_list[] = $this->insert_config('cms_email_wrapchars', '76', 'Character count to wrap at.');
        $sql_list[] = $this->insert_config('cms_email_mailtype', 'html', 'Type of mail. If you send HTML email you must send it as a complete web page. Make sure you do not have any relative links or relative image paths otherwise they will not work. Default : html, Alternatives : html, text');
        $sql_list[] = $this->insert_config('cms_email_charset', 'utf-8', 'Character set (utf-8, iso-8859-1, etc.).');
        $sql_list[] = $this->insert_config('cms_email_validate', 'FALSE', 'Whether to validate the email address. Default: true, Alternatives : true, false');
        $sql_list[] = $this->insert_config('cms_email_priority', '3', '1, 2, 3, 4, 5  Email Priority. 1 = highest. 5 = lowest. 3 = normal.');
        $sql_list[] = $this->insert_config('cms_email_bcc_batch_mode', 'FALSE', 'Enable BCC Batch Mode. Default: false, Alternatives: true');
        $sql_list[] = $this->insert_config('cms_email_bcc_batch_size', '200', 'Number of emails in each BCC batch.');
        $sql_list[] = $this->insert_config('cms_google_analytic_property_id', '', 'Google analytics property ID (e.g: UA-30285787-1). Leave blank if you don\'t want to use it.');
        // group user
        $sql_list[] = $this->insert_group_user();
        return $sql_list;
    }

    public function build_database(){
        $success = TRUE;
        $error_list = array();
        $warning_list = array();
        $sql_list = array();
        // init db
        $this->load_database();
        if($this->db === FALSE){
            $success = FALSE;
            $error_list[] = 'Cannot connect to the database with the provided configuration';
        }else{
            foreach($this->table_drop_list as $table_name){
                $this->dbforge->drop_table($this->db_table_prefix.'_'.$table_name);
            }
            $create_table_sql_list = $this->create_all_table();
            $insert_sql_list = $this->insert_all_data();
            $sql_list = array_merge($sql_list, $create_table_sql_list);
            $sql_list = array_merge($sql_list, $insert_sql_list);
            $success = !$this->db_no_error;
        }

        return array(
                'success' => $success,
                'error_list' => $error_list,
                'warning_list' => $warning_list,
                'sql_list' => $sql_list,
            );

    }

    protected function change_config($file_name, $key, $value, $key_prefix = '\$config\[', $key_suffix = '\]'){
        if(!file_exists($file_name)) return FALSE;
        $pattern = array();
        $pattern[] = '/('.$key_prefix.'(\'|")'.$key.'(\'|")'.$key_suffix.' *= *")(.*?)(";)/si';
        $pattern[] = "/(".$key_prefix."('|\")".$key."('|\")".$key_suffix." *= *')(.*?)(';)/si";

        $str = file_get_contents($file_name);
        $replacement = '${1}'.$value.'${5}';
        $str = preg_replace($pattern, $replacement, $str);
        @chmod($file_name,0777);
        @file_put_contents($file_name, $str);
    }

    public function build_configuration(){

    }

}
?>