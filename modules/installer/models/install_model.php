<?php defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($_SESSION)){
    session_start();
}
class Install_Model extends CI_Model{
    public $is_subsite      = FALSE;
    public $subsite         = '';
    public $subsite_aliases = '';

    public $db_protocol     = 'mysqli';
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
    public $admin_confirm_password = 'admin';

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
            'mysqli' => '3306',
            'pdo_mysql' => '3306',
            'pdo_pgsql' => '5432',
            'pdo_sqlite' => '',
        );

    public function __construct(){
        $timezone = @date_default_timezone_get();
        if (!isset($timezone) || $timezone == '') {
            $timezone = @ini_get('date.timezone');
        }
        if (!isset($timezone) || $timezone == '') {
            $timezone = 'UTC';
        }
        date_default_timezone_set($timezone);
        parent::__construct();
        // automatically set table prefix based on subsite
        $this->set_subsite($this->subsite);
    }

    public function set_subsite($subsite = NULL){
        if($subsite !== NULL){
            $this->subsite = $subsite;
        }
        if($this->is_subsite && $this->subsite != ''){
            $this->db_table_prefix .= '_site_'.$this->subsite;
        }
    }

    protected function build_dsn(){
        if($this->db_port == ''){
            $this->db_port = $this->DEFAULT_PORT[$this->db_protocol];
        }
        if($this->db_protocol=='pdo_sqlite'){
            $dsn = 'sqlite:'.FCPATH.'db.sqlite';
        }else{
            $db_protocol = '';
            switch($this->db_protocol){
                case 'mysql': $db_protocol = 'mysql'; break;
                case 'mysqli': $db_protocol = 'mysql'; break;
                case 'pdo_mysql': $db_protocol = 'mysql'; break;
                case 'pdo_pgsql': $db_protocol = 'pgsql'; break;
            }
            $dsn = $db_protocol.':host='.$this->db_host.';port='.$this->db_port;
            if($this->db_name != ''){
                $dsn .= ';dbname='.$this->db_name;
            }
        }
        return $dsn;
    }

    protected function get_db_driver(){
        $db_driver = '';
        switch($this->db_protocol){
            case 'mysql': $db_driver = 'mysql'; break;
            case 'mysqli': $db_driver = 'mysqli'; break;
            case 'pdo_mysql': $db_driver = 'pdo'; break;
            case 'pdo_pgsql': $db_driver = 'pdo'; break;
            case 'pdo_sqlite': $db_driver = 'pdo'; break;
        }
        return $db_driver;

    }

    protected function build_db_config(){
        $dsn = $this->build_dsn();
        $db_driver = $this->get_db_driver();
        return array(
            'dsn' => $dsn,
            'hostname' => $this->db_host,
            'username' => $this->db_username,
            'password' => $this->db_password,
            'database' => $this->db_name,
            'dbdriver' => $db_driver,
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
    }

    protected function load_database(){
        $db_config = $this->build_db_config();

        // if we make a subsite, use the current database setting
        if($this->is_subsite){
            $db = $this->load->database('default', TRUE);
            $this->db = $db;
            $this->dbutil = $this->load->dbutil($db, TRUE);
            $this->dbforge = $this->load->dbforge($db, TRUE);
            return $db;
        }

        $db = @$this->load->database($db_config, TRUE);

        $is_mysql = $this->db_protocol=='mysql' || $this->db_protocol=='mysqli';
        $allow_create = FALSE;
        $allow_drop = FALSE;
        if($is_mysql){
            // get the "allow_create" and "allow_drop" privileges
            @mysql_connect($this->db_host.':'.$this->db_port , $this->db_username, $this->db_password);
            $result = @mysql_query('SHOW GRANTS FOR CURRENT_USER;');
            if($result !== NULL && $result !== FALSE){
                while($row = mysql_fetch_row($result)){
                    if(strpos($row[0], 'ALL PRIVILEGES')){
                        $allow_drop = TRUE;
                        $allow_create = TRUE;
                        break;
                    }
                    if(strpos($row[0], 'CREATE')){
                        $allow_create = TRUE;
                    }
                    if(strpos($row[0], 'DROP')){
                        $allow_drop = TRUE;
                    }
                    if($allow_create && $allow_drop){
                        break;
                    }
                }
            }
        }

        if($is_mysql){
            // try to drop the previously created database
            if($allow_drop && isset($_SESSION['created_db']) && $_SESSION['created_db'] != '' && $_SESSION['created_db'] != $this->db_name){
                @mysql_query('DROP DATABASE IF EXISTS '.$_SESSION['created_db']);
                unset($_SESSION['created_db']);
            }
        }

        $success = TRUE;
        if($db->conn_id === FALSE){
            $success = FALSE;
            // if it is MySQL, try to make database
            if($is_mysql){
                if($allow_create && (!isset($_SESSION['created_db']) || $_SESSION['created_db'] != $this->db_name ) ){
                    $result = @mysql_query('CREATE DATABASE ' . $this->db_name . ' DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;');
                    // save the created database
                    if($result){
                        $_SESSION['created_db'] = $this->db_name;
                    }
                }
                // try to connect again
                $db_config = $this->build_db_config();
                $db = $this->load->database($db_config, TRUE);
                if($db->conn_id !== FALSE){
                    $success = TRUE;
                }
            }
        }

        // return value
        if($success){
            $this->db = $db;
            $this->dbutil = $this->load->dbutil($db, TRUE);
            $this->dbforge = $this->load->dbforge($db, TRUE);
            return $db;
        }else{
            return FALSE;
        }
    }

    public function check_installation(){
        $success = TRUE;
        $error_list = array();
        $warning_list = array();
        $db = $this->load_database();

        if($this->is_subsite){
            include(FCPATH.'site.php');
            if($this->subsite == ''){
                $success = FALSE;
                $error_list[] = 'Subsite cannot be empty';
            }
            if(in_array($this->subsite, $available_site)){
                $success = FALSE;
                $error_list[] = 'Subsite already exists';
            }
        }

        // subsite doesn't need to check database
        if(!$this->is_subsite){
            // database connection
            if($db === FALSE){
                $success =  FALSE;
                $error_list[] = 'Cannot connect using provided <a class="a-change-tab" href="#" tab="#tab1" component="db_protocol">Database Setting</a>';
            }
            if($this->db_name=='' && $this->db_protocol != 'pdo_sqlite'){
                $success = FALSE;
                $error_list[] = '<a class="a-change-tab" href="#" tab="#tab1" component="db_name">Database schema</a> cannot be empty';
            }
        }
        if($this->admin_user_name==''){
            $success = FALSE;
            $error_list[] = '<a class="a-change-tab" href="#" tab="#tab2" component="admin_user_name">Super Admin\'s username</a> is empty';
        }
        if($this->admin_real_name==''){
            $success = FALSE;
            $error_list[] = '<a class="a-change-tab" href="#" tab="#tab2" component="admin_real_name">Super Admin\'s real name</a> is empty';
        }
        if($this->admin_password==''){
            $success = FALSE;
            $error_list[] = '<a class="a-change-tab" href="#" tab="#tab2" component="admin_password">Super Admin\'s password</a> is empty';
        }else if ($this->admin_password != $this->admin_confirm_password){
            $success = FALSE;
            $error_list[] = '<a class="a-change-tab" href="#" tab="#tab2" component="admin_confirm_password">Super Admin\'s password confirmation</a> doesn\'t match';
        }

        // subsite doesn't need this
        if(!$this->is_subsite){
            // No-CMS directory
            if (!is_writable(FCPATH)) {
                $success  = FALSE;
                $error_list[] = FCPATH.' is not writable';
            }
            // kcfinder upload
            if (!is_writable(FCPATH.'assets/kcfinder/upload')){
                $success = FALSE;
                $error_list[] = FCPATH.'assets/kcfinder/upload is not writable';
            }
            // kcfinder config
            if (!is_writable(FCPATH.'assets/kcfinder')){
                $success = FALSE;
                $error_list[] = FCPATH.'assets/kcfinder is not writable';
            }
            // ckeditor config
            if (!is_writable(FCPATH.'assets/grocery_crud/texteditor/ckeditor')){
                $success = FALSE;
                $error_list[] = FCPATH.'assets/grocery_crud/texteditor/ckeditor';
            }
            // assets/caches
            if (!is_writable(FCPATH.'assets/caches')) {
                $success  = FALSE;
                $error_list[] = "Asset cache directory (".FCPATH."assets/caches) is not writable";
            }
        }

        // application/config/
        if (!is_writable(APPPATH.'config')) {
            $success  = FALSE;
            $error_list[] = "Config directory (".APPPATH."config) is not writable";
        }
        // hybridauthlib log file
        if (!is_writable(APPPATH.'logs/hybridauth.log')) {
            $success  = FALSE;
            $error_list[] = APPPATH."logs/hybridauth.log is not writable";
        }
        // third party authentication activated
        if ($this->auth_enable_facebook || $this->auth_enable_twitter || $this->auth_enable_google || $this->auth_enable_yahoo || $this->auth_enable_linkedin || $this->auth_enable_myspace || $this->auth_enable_foursquare || $this->auth_enable_windows_live || $this->auth_enable_open_id || $this->auth_enable_aol ) {
            // curl
            if (!in_array('curl', get_loaded_extensions())) {
                $success  = FALSE;
                $error_list[] = 'Third party authentication require php-curl, but it is not enabled';
            }
            // facebook
            if($this->auth_enable_facebook){
                if($this->auth_facebook_app_id == ''){
                    $success = FALSE;
                    $error_list[] = '<a class="a-change-tab" href="#" tab="#tab3" component="auth_facebook_app_id">Facebook application id</a> cannot be empty';
                }
                if($this->auth_facebook_app_secret == ''){
                    $success = FALSE;
                    $error_list[] = '<a class="a-change-tab" href="#" tab="#tab3" component="auth_facebook_app_secret">Facebook application secret</a> cannot be empty';
                }
            }
            // twitter
            if($this->auth_enable_twitter){
                if($this->auth_twitter_app_key == ''){
                    $success = FALSE;
                    $error_list[] = '<a class="a-change-tab" href="#" tab="#tab4" component="auth_twitter_app_key">Twitter application key</a> cannot be empty';
                }
                if($this->auth_twitter_app_secret == ''){
                    $success = FALSE;
                    $error_list[] = '<a class="a-change-tab" href="#" tab="#tab4" component="auth_twitter_app_secret">Twitter application secret</a> cannot be empty';
                }
            }
            // google
            if($this->auth_enable_google){
                if($this->auth_google_app_id == ''){
                    $success = FALSE;
                    $error_list[] = '<a class="a-change-tab" href="#" tab="#tab5" component="auth_google_app_id">Google application id</a> cannot be empty';
                }
                if($this->auth_google_app_secret == ''){
                    $success = FALSE;
                    $error_list[] = '<a class="a-change-tab" href="#" tab="#tab5" component="auth_google_app_secret">Google application secret</a> cannot be empty';
                }
            }
            // yahoo
            if($this->auth_enable_yahoo){
                if($this->auth_yahoo_app_id == ''){
                    $success = FALSE;
                    $error_list[] = '<a class="a-change-tab" href="#" tab="#tab6" component="auth_yahoo_app_id">Yahoo application id</a> cannot be empty';
                }
                if($this->auth_yahoo_app_secret == ''){
                    $success = FALSE;
                    $error_list[] = '<a class="a-change-tab" href="#" tab="#tab6" component="auth_yahoo_app_secret">Yahoo application secret</a> cannot be empty';
                }
            }
            // linkedin
            if($this->auth_enable_linkedin){
                if($this->auth_linkedin_app_key == ''){
                    $success = FALSE;
                    $error_list[] = '<a class="a-change-tab" href="#" tab="#tab7" component="auth_linkedin_app_key">Linkedin application key</a> cannot be empty';
                }
                if($this->auth_linkedin_app_secret == ''){
                    $success = FALSE;
                    $error_list[] = '<a class="a-change-tab" href="#" tab="#tab7" component="auth_linkedin_app_secret">Linkedin application secret</a> cannot be empty';
                }
            }
            // myspace
            if($this->auth_enable_myspace){
                if($this->auth_myspace_app_key == ''){
                    $success = FALSE;
                    $error_list[] = '<a class="a-change-tab" href="#" tab="#tab8" component="auth_myspace_app_key">Myspace application key</a> cannot be empty';
                }
                if($this->auth_myspace_app_secret == ''){
                    $success = FALSE;
                    $error_list[] = '<a class="a-change-tab" href="#" tab="#tab8" component="auth_myspace_app_secret">Myspace application secret</a> cannot be empty';
                }
            }
            // foursquare
            if($this->auth_enable_foursquare){
                if($this->auth_foursquare_app_id == ''){
                    $success = FALSE;
                    $error_list[] = '<a class="a-change-tab" href="#" tab="#tab9" component="auth_foursquare_app_id">Foursquare application id</a> cannot be empty';
                }
                if($this->auth_foursquare_app_secret == ''){
                    $success = FALSE;
                    $error_list[] = '<a class="a-change-tab" href="#" tab="#tab9" component="auth_foursquare_app_secret">Foursquare application secret</a> cannot be empty';
                }
            }
            // windows_live
            if($this->auth_enable_windows_live){
                if($this->auth_windows_live_app_id == ''){
                    $success = FALSE;
                    $error_list[] = '<a class="a-change-tab" href="#" tab="#tab10" component="auth_windows_live_app_id">Windows Live application id</a> cannot be empty';
                }
                if($this->auth_windows_live_app_secret == ''){
                    $success = FALSE;
                    $error_list[] = '<a class="a-change-tab" href="#" tab="#tab10" component="auth_windows_live_app_secret">Windows Live application secret</a> cannot be empty';
                }
            }
        }

        // subsite doesn't need this
        if(!$this->is_subsite){
            // hide index: mod_rewrite should be active, but there is no way to absolutely determine this
            if($this->hide_index){
                $mod_rewrite = FALSE;
                if (function_exists('apache_get_modules')) {
                    $modules = apache_get_modules();
                    if (in_array('mod_rewrite', $modules)) {
                        $mod_rewrite = TRUE;
                    }
                }
                if (!$mod_rewrite && isset($_SERVER["HTTP_MOD_REWRITE"])) {
                    if (strtoupper($_SERVER["HTTP_MOD_REWRITE"]) == "ON") {
                        $mod_rewrite = TRUE;
                    }
                }
                if (!$mod_rewrite) {
                    if (strtoupper(getenv('HTTP_MOD_REWRITE')) == "ON") {
                        $mod_rewrite = TRUE;
                    }
                }
                if(!$mod_rewrite){
                    $warning_list[] = "Rewrite Base is possibly not activated, this is needed when you choose to hide index.php. If you are sure that your mod_rewrite is activated, you can continue at your own risk";
                }
            }
            // log directory
            if (!is_writable(APPPATH.'logs')) {
                $success  = FALSE;
                $error_list[] = APPPATH."logs is not writable";
            }
        }
        return array(
                'success' => $success,
                'error_list' => $error_list,
                'warning_list' => $warning_list,
            );
    }

    protected function create_table($table_name, $fields, $primary_key=NULL){
        $table_list = $this->db->list_tables();
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
        if(!trim($this->db_table_prefix) == ''){
            $table_name = $this->db_table_prefix.'_'.$table_name;
        }
        if(in_array($table_name, $table_list)){
            $this->db_no_error = $this->db_no_error && $this->dbforge->drop_table($table_name);
        }
        $this->db_no_error = $this->db_no_error && $this->dbforge->create_table($table_name);
        return $this->db->last_query();
    }

    protected function create_all_table(){
        $sql_list = array();

        // define frequently used types
        $type_primary_key = array(
                'type' => 'INT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            );
        $type_int = array(
            'type' => 'INT',
            'constraint' => 20,
            'unsigned' => TRUE
        );
        $type_foreign_key = array(
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'null' => TRUE,
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
        $type_user_agent = array(
                'type' => 'VARCHAR',
                'constraint' => '120',
                'null' => FALSE,
            );
        $type_user_agent = array(
                'type' => 'VARCHAR',
                'constraint' => '120',
                'null' => FALSE,
            );
        $type_ip_address = array(
                'type' => 'VARCHAR',
                'constraint' => '16',
                'null' => FALSE,
            );
        $type_date = array("type"=>'date', "null"=>TRUE);

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
                'widget_name' => $type_varchar_large_strict,
                'title' => $type_varchar_small,
                'description' => $type_text,
                'url' => $type_varchar_large,
                'authorization_id' => $type_foreign_key_default_1,
                'active' => $type_boolean_true,
                'index' => $type_index,
                'is_static' => $type_boolean_false,
                'static_content' => $type_text,
                'slug' => $type_varchar_large,
            );
        $sql_list[] = $this->create_table('main_widget',$fields);

        // NAVIGATION
        $fields = array(
                'navigation_id' => $type_primary_key,
                'navigation_name' => $type_varchar_large_strict,
                'parent_id' => $type_foreign_key,
                'title' => $type_varchar_small_strict,
                'bootstrap_glyph' => $type_varchar_small,
                'page_title' => $type_varchar_small,
                'page_keyword' => $type_varchar_large,
                'description' => $type_text,
                'url' => $type_varchar_large,
                'authorization_id' => $type_foreign_key_default_1,
                'active' => $type_boolean_true,
                'index' => $type_index,
                'is_static' => $type_boolean_false,
                'static_content' => $type_text,
                'only_content' => $type_boolean_false,
                'default_theme' => $type_varchar_small,
                'default_layout' => $type_varchar_small,
                'notif_url'=>$type_varchar_large,
                'children'=>$type_varchar_large,
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
                'email' => $type_varchar_small,
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
                'language' => $type_varchar_small,
                'theme' => $type_varchar_small,
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
        $sql_list[] = $this->create_table('main_module_dependency',$fields);

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
                'ip_address' => $type_ip_address,
                'user_agent' => $type_user_agent,
                'last_activity' => $type_int,
                'user_data' => $type_text,
            );
        $sql_list[] = $this->create_table('ci_sessions',$fields);

        // LANGUAGE
        $fields = array(
                'language_id' => $type_primary_key,
                'name' => $type_varchar_small_strict,
                'code' => $type_varchar_small,
                'iso_code' => $type_varchar_small,
            );
        $sql_list[] = $this->create_table('main_language',$fields);
        // DETAIL LANGUAGE
        $fields = array(
                'detail_language_id' => $type_primary_key,
                'id_language' => $type_int,
                'key' => $type_varchar_small_strict,
                'translation' => $type_varchar_small_strict,
            );
        $sql_list[] = $this->create_table('main_detail_language',$fields);

        return $sql_list;
    }

    protected function insert_authorization($authorization_name, $description){
        $array = array(
                'authorization_name' => $authorization_name,
                'description' => $description,
            );
        $table_name = 'main_authorization';
        if(!trim($this->db_table_prefix) == ''){
            $table_name = $this->db_table_prefix.'_'.$table_name;
        }
        $this->db_no_error = $this->db->insert($table_name, $array) && $this->db_no_error;
        return $this->db->last_query();
    }

    protected function insert_group($group_name, $description){
        $array = array(
                'group_name' => $group_name,
                'description' => $description,
            );
        $table_name = 'main_group';
        if(!trim($this->db_table_prefix) == ''){
            $table_name = $this->db_table_prefix.'_'.$table_name;
        }
        $this->db_no_error = $this->db->insert($table_name, $array) && $this->db_no_error;
        return $this->db->last_query();
    }

    protected function insert_user(){
        $array = array(
                'user_name' => $this->admin_user_name,
                'email' => $this->admin_email,
                'password'=> md5($this->admin_password),
                'real_name' => $this->admin_real_name
            );
        $table_name = 'main_user';
        if(!trim($this->db_table_prefix) == ''){
            $table_name = $this->db_table_prefix.'_'.$table_name;
        }
        $this->db_no_error = $this->db->insert($table_name, $array) && $this->db_no_error;
        return $this->db->last_query();
    }

    protected function insert_navigation($navigation_name, $parent_id, $title, $page_title, $page_keyword, $description, $url, $authorization_id, $index, $active, $is_static, $static_content, $only_content, $bootstrap_glyph = 'glyphicon-th-large', $default_theme=NULL, $default_layout=NULL){
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
                'only_content' => $only_content,
                'bootstrap_glyph' => $bootstrap_glyph,
                'default_theme' => $default_theme,
                'default_layout' => $default_layout,
            );
        $table_name = 'main_navigation';
        if(!trim($this->db_table_prefix) == ''){
            $table_name = $this->db_table_prefix.'_'.$table_name;
        }
        $this->db_no_error = $this->db->insert($table_name, $array) && $this->db_no_error;
        return $this->db->last_query();
    }

    protected function insert_quicklink($navigation_id, $index){
        $array = array(
                'navigation_id' => $navigation_id,
                'index' => $index,
            );
        $table_name = 'main_quicklink';
        if(!trim($this->db_table_prefix) == ''){
            $table_name = $this->db_table_prefix.'_'.$table_name;
        }
        $this->db_no_error = $this->db->insert($table_name, $array) && $this->db_no_error;
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
        $table_name = 'main_widget';
        if(!trim($this->db_table_prefix) == ''){
            $table_name = $this->db_table_prefix.'_'.$table_name;
        }
        $this->db_no_error = $this->db->insert($table_name, $array) && $this->db_no_error;
        return $this->db->last_query();
    }

    protected function insert_privilege($privilege_name, $title, $description, $authorization_id){
        $array = array(
                'privilege_name' => $privilege_name,
                'title' => $title,
                'description' => $description,
                'authorization_id' => $authorization_id
            );
        $table_name = 'main_privilege';
        if(!trim($this->db_table_prefix) == ''){
            $table_name = $this->db_table_prefix.'_'.$table_name;
        }
        $this->db_no_error = $this->db->insert($table_name, $array) && $this->db_no_error;
        return $this->db->last_query();
    }

    protected function insert_config($config_name, $value, $description){
        $array = array(
                'config_name' => $config_name,
                'value' => $value,
                'description' => $description
            );
        $table_name = 'main_config';
        if(!trim($this->db_table_prefix) == ''){
            $table_name = $this->db_table_prefix.'_'.$table_name;
        }
        $this->db_no_error = $this->db->insert($table_name, $array) && $this->db_no_error;
        return $this->db->last_query();
    }

    protected function insert_group_user(){
        $array = array(
                'group_id' => 1,
                'user_id' => 1,
            );
        $table_name = 'main_group_user';
        if(!trim($this->db_table_prefix) == ''){
            $table_name = $this->db_table_prefix.'_'.$table_name;
        }
        $this->db_no_error = $this->db->insert($table_name, $array) && $this->db_no_error;
        return $this->db->last_query();
    }

    protected function insert_all_data($config=NULL){
        $sql_list = array();
        // authorization
        $sql_list[] = $this->insert_authorization('Everyone', 'All visitor of the web are permitted (e.g:view blog content)');
        $sql_list[] = $this->insert_authorization('Unauthenticated', 'Only non-member visitor, they who hasn\'t log in yet (e.g:view member registration page)');
        $sql_list[] = $this->insert_authorization('Authenticated', 'Only member (e.g:change password)');
        $sql_list[] = $this->insert_authorization('Authorized', 'Only member with certain privilege (depend on group)');
        $sql_list[] = $this->insert_authorization('Exclusive Authorized', 'Even Super Admin cannot access this if not allowed');
        // group
        $sql_list[] = $this->insert_group('Super Admin', 'Every member of this group can do everything possible, but only programmer can turn the impossible into real :D');
        $sql_list[] = $this->insert_group('Employee', 'Group Example');
        // user
        $sql_list[] = $this->insert_user();
        // navigation
        $sql_list[] = $this->insert_navigation('main_login', NULL, 'Login', 'Login', NULL, 'Visitor need to login for authentication', 'main/login',
                2, 4, 1, 0, NULL, 0, 'glyphicon-home', NULL, 'default-one-column');
        $sql_list[] = $this->insert_navigation('main_forgot', NULL, 'Forgot Password', 'Forgot', NULL, 'Accidentally forgot password', 'main/forgot',
                2, 6, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_logout', NULL, 'Logout', 'Logout', NULL, 'Logout for deauthentication', 'main/logout',
                3, 5, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_management', NULL, 'CMS Management', 'CMS Management', NULL, 'The main management of the CMS. Including User, Group, Privilege and Navigation Management', 'main/management',
                4, 9, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_register', NULL, 'Register', 'Register', NULL, 'New User Registration', 'main/register',
                2, 7, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_change_profile', NULL, 'Change Profile', 'Change Profile', NULL, 'Change Current Profile', 'main/change_profile',
                3, 8, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_group_management', 4, 'Group Management', 'Group Management', NULL, 'Group Management', 'main/group',
                4, 0, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_navigation_management', 4, 'Navigation Management', 'Navigation Management', NULL, 'Navigation management', 'main/navigation',
                4, 3, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_privilege_management', 4, 'Privilege Management', 'Privilege Management', NULL, 'Privilege Management', 'main/privilege',
                4, 2, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_user_management', 4, 'User Management', 'User Management', NULL, 'Manage User', 'main/user',
                4, 1, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_module_management', 4, 'Module Management', 'Module Management', NULL, 'Install Or Uninstall Thirdparty Module', 'main/module_management',
                4, 5, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_change_theme', 4, 'Change Theme', 'Change Theme', NULL, 'Change Theme', 'main/change_theme',
                4, 6, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_widget_management', 4, 'Widget Management', 'Widget Management', NULL, 'Manage Widgets', 'main/widget',
                4, 4, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_quicklink_management', 4, 'Quick Link Management', 'Quick Link Management', NULL, 'Manage Quick Link', 'main/quicklink',
                4, 7, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_language_management', 4, 'Language Management', 'Language Management', NULL, 'Manage Language', 'main/language_management',
                4, 8, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_config_management', 4, 'Configuration Management', 'Configuration Management', NULL, 'Manage Configuration Parameters', 'main/config',
                4, 9, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_setting', 4, 'Setting', 'CMS Setting', NULL, 'CMS Setting', 'main/setting',
                4, 10, 1, 0, NULL, 0);

        if($this->is_subsite){
            if(array_key_exists('subsite_home_content', $config)){
                $main_index_content = $config['subsite_home_content'];
            }else{
                $main_index_content = '<h1>Welcome</h1>Hi, welcome to subsite '.$this->subsite;
            }
        }else{
            $main_index_content = '<style type="text/css">.thumbnail .caption p{' . PHP_EOL . 'font-size:small;' . PHP_EOL . '}' . PHP_EOL . '.thumbnail{' . PHP_EOL . 'border:none!important;' . PHP_EOL . '}' . PHP_EOL . '.page-header, .page-header h1{' . PHP_EOL . 'margin-top:0px;' . PHP_EOL . '}' . PHP_EOL . '#__section-left-and-content hr, #__section-left-and-content .breadcrumb{' . PHP_EOL . 'margin:0px;' . PHP_EOL . '}' . PHP_EOL . '#__section-left-and-content p.lead{' . PHP_EOL . 'margin-top:20px;' . PHP_EOL . '}' . PHP_EOL . '</style>' . PHP_EOL . '<div class="page-header">' . PHP_EOL . '    <h1>' . PHP_EOL . '        Welcome to No-CMS<br />' . PHP_EOL . '        <small>A Free CodeIgniter based CMS Framework</small>' . PHP_EOL . '    </h1>' . PHP_EOL . '</div>' . PHP_EOL . '<div class="row col-sm-12 col-md-12 col-xs-12">' . PHP_EOL . '    <div class="row col-md-12">' . PHP_EOL . '        <a style="margin-top:10px;" class="btn btn-default col-md-5 col-xs-12" href="https://github.com/goFrendiAsgard/No-CMS/archive/master.zip"><i class="glyphicon glyphicon-thumbs-up">&nbsp;</i>&nbsp;Get Stable Version</a> <a style="margin-top:10px;" class="btn btn-default col-md-5 col-xs-12 col-md-offset-2" href="https://github.com/goFrendiAsgard/No-CMS/archive/development.zip"><i class="glyphicon glyphicon-wrench">&nbsp;</i>&nbsp;Get Development Version</a>' . PHP_EOL . '    </div>' . PHP_EOL . '    <div class="row col-md-12">' . PHP_EOL . '        <a style="margin-top:10px;" class="btn btn-default col-md-5 col-xs-12" href="http://www.getnocms.com//forum"><i class="glyphicon glyphicon-comment">&nbsp;</i>&nbsp;No-CMS Forum</a> <a style="margin-top:10px;" class="btn btn-default col-md-5 col-xs-12 col-md-offset-2" href="https://github.com/goFrendiAsgard/No-CMS/blob/master/doc/tutorial.md"><i class="glyphicon glyphicon-book">&nbsp;</i>&nbsp;Visit User Guide</a>' . PHP_EOL . '    </div>' . PHP_EOL . '</div>' . PHP_EOL . '<p class="lead row col-sm-12 col-md-12 col-xs-12">' . PHP_EOL . '    No-CMS is not just another CodeIgniter based CMS. There are many things that will make you falling in love with it.' . PHP_EOL . '</p>' . PHP_EOL . '<div class="row col-sm-12 col-md-12 col-xs-12">' . PHP_EOL . '    <div class="col-sm-6 col-md-4">' . PHP_EOL . '        <div class="thumbnail">' . PHP_EOL . '            <img alt="..." src="{{ base_url }}modules/main/assets/images/rocket.png" />' . PHP_EOL . '            <div class="caption">' . PHP_EOL . '                <h3>' . PHP_EOL . '                    Easy Installation' . PHP_EOL . '                </h3>' . PHP_EOL . '                <p>' . PHP_EOL . '                    Installing No-CMS is very straight forward. Just follow the on-screen instruction and you will be fine.' . PHP_EOL . '                </p>' . PHP_EOL . '            </div>' . PHP_EOL . '        </div>' . PHP_EOL . '    </div>' . PHP_EOL . '    <div class="col-sm-6 col-md-4">' . PHP_EOL . '        <div class="thumbnail">' . PHP_EOL . '            <img alt="..." src="{{ base_url }}modules/main/assets/images/profle.png" />' . PHP_EOL . '            <div class="caption">' . PHP_EOL . '                <h3>' . PHP_EOL . '                    User Group' . PHP_EOL . '                </h3>' . PHP_EOL . '                <p>' . PHP_EOL . '                    Determine who can access your pages. Put your users into several managable groups' . PHP_EOL . '                </p>' . PHP_EOL . '            </div>' . PHP_EOL . '        </div>' . PHP_EOL . '    </div>' . PHP_EOL . '    <div class="col-sm-6 col-md-4">' . PHP_EOL . '        <div class="thumbnail">' . PHP_EOL . '            <img alt="..." src="{{ base_url }}modules/main/assets/images/brush-pencil.png" />' . PHP_EOL . '            <div class="caption">' . PHP_EOL . '                <h3>' . PHP_EOL . '                    Customizable Themes' . PHP_EOL . '                </h3>' . PHP_EOL . '                <p>' . PHP_EOL . '                    Choose or make your own theme. Make your very own website.' . PHP_EOL . '                </p>' . PHP_EOL . '            </div>' . PHP_EOL . '        </div>' . PHP_EOL . '    </div>' . PHP_EOL . '    <div class="col-sm-6 col-md-4">' . PHP_EOL . '        <div class="thumbnail">' . PHP_EOL . '            <img alt="..." src="{{ base_url }}modules/main/assets/images/gear.png" />' . PHP_EOL . '            <div class="caption">' . PHP_EOL . '                <h3>' . PHP_EOL . '                    Modules &amp; Module Generator' . PHP_EOL . '                </h3>' . PHP_EOL . '                <p>' . PHP_EOL . '                    Add No-CMS functionality by installing or making your own modules. We also have module generator to help you.' . PHP_EOL . '                </p>' . PHP_EOL . '            </div>' . PHP_EOL . '        </div>' . PHP_EOL . '    </div>' . PHP_EOL . '    <div class="col-sm-6 col-md-4">' . PHP_EOL . '        <div class="thumbnail">' . PHP_EOL . '            <img alt="..." src="{{ base_url }}modules/main/assets/images/flame.png" />' . PHP_EOL . '            <div class="caption">' . PHP_EOL . '                <h3>' . PHP_EOL . '                    Developer Friendly' . PHP_EOL . '                </h3>' . PHP_EOL . '                <p>' . PHP_EOL . '                    No-CMS was built on top of CodeIgniter, HMVC Extension, and Grocery CRUD.' . PHP_EOL . '                </p>' . PHP_EOL . '            </div>' . PHP_EOL . '        </div>' . PHP_EOL . '    </div>' . PHP_EOL . '    <div class="col-sm-6 col-md-4">' . PHP_EOL . '        <div class="thumbnail">' . PHP_EOL . '            <img alt="..." src="{{ base_url }}modules/main/assets/images/frames.png" />' . PHP_EOL . '            <div class="caption">' . PHP_EOL . '                <h3>' . PHP_EOL . '                    Many out-of-the-box features' . PHP_EOL . '                </h3>' . PHP_EOL . '                <p>' . PHP_EOL . '                    Third Party Authentication, Widgets, Language Management, Multisite, and many others.' . PHP_EOL . '                </p>' . PHP_EOL . '            </div>' . PHP_EOL . '        </div>' . PHP_EOL . '    </div>' . PHP_EOL . '</div>' . PHP_EOL . '<div class="alert alert-info alert-dismissable row col-sm-12 col-md-12 col-xs-12">' . PHP_EOL . '    <button aria-hidden="true" class="close" data-dismiss="alert" type="button">' . PHP_EOL . '        &times;' . PHP_EOL . '    </button>' . PHP_EOL . '    <h2>' . PHP_EOL . '        Site owner, please read these simple howtos !!!' . PHP_EOL . '    </h2>' . PHP_EOL . '    <h3>' . PHP_EOL . '        Modify this home page' . PHP_EOL . '    </h3>' . PHP_EOL . '    <p>' . PHP_EOL . '        Seeing this message means that you&#39;ve just successfully install No-CMS on your server.<br />' . PHP_EOL . '        And, we believe you won&#39;t just stop here. You have several options to modify this homepage:' . PHP_EOL . '    </p>' . PHP_EOL . '    <ul>' . PHP_EOL . '        <li>' . PHP_EOL . '            <b>Using static page</b>' . PHP_EOL . '            <p>' . PHP_EOL . '                Just go to&nbsp;<a class="btn btn-primary" href="{{ site_url }}main/navigation/edit/18">Navigation Management</a> and modify the <b>static content</b>' . PHP_EOL . '            </p>' . PHP_EOL . '        </li>' . PHP_EOL . '        <li>' . PHP_EOL . '            <b>Redirect default controller</b>' . PHP_EOL . '            <p>' . PHP_EOL . '                If you are familiar with CodeIgniter, you can modify&nbsp;<code>$route[&#39;default_controller&#39;]</code>&nbsp;variable on&nbsp;<code>/application/config/routes.php</code>, around line 41.&nbsp;Please make sure that your default controller is valid.&nbsp;This is recommended if you also want your own page to be a default homepage.' . PHP_EOL . '            </p>' . PHP_EOL . '        </li>' . PHP_EOL . '        <li>' . PHP_EOL . '            <b>Using dynamic page</b>' . PHP_EOL . '            <p>' . PHP_EOL . '                You can&nbsp;<em>deactivate</em>&nbsp;<strong>static option</strong>&nbsp;on&nbsp;<a class="btn btn-primary" href="{{ site_url }}main/navigation/edit/17">Navigation Management</a>&nbsp;and edit the corresponding view (<code>/modules/main/views/main_index.php</code>)' . PHP_EOL . '            </p>' . PHP_EOL . '        </li>' . PHP_EOL . '    </ul>' . PHP_EOL . '    <h3>' . PHP_EOL . '        Need helps?' . PHP_EOL . '    </h3>' . PHP_EOL . '    <p>' . PHP_EOL . '        You are welcomed to join No-CMS forum: <a href="http://getnocms.com/forum">http://getnocms.com/forum</a><br />' . PHP_EOL . '        or open an issue on No-CMS github repository: <a href="https://github.com/goFrendiAsgard/No-CMS/">https://github.com/goFrendiAsgard/No-CMS/</a><br />' . PHP_EOL . '        In case of you&#39;ve found a critical bug, you can also directly send email to&nbsp;<a href="mailto:gofrendiasgard@gmail.com">gofrendiasgard@gmail.com</a><br />' . PHP_EOL . '        That&#39;s all. Start your new adventure with No-CMS !!!' . PHP_EOL . '    </p>' . PHP_EOL . '</div><script type="text/javascript">$(window).load(function(){function __adjust_component(identifier){var max_height=0;$(identifier).each(function(){$(this).css("margin-bottom",0);if($(this).height()>max_height){max_height=$(this).height();}});$(identifier).each(function(){var margin_bottom=0;if($(this).height()<max_height){margin_bottom=max_height-$(this).height();}margin_bottom+=10;$(this).css("margin-bottom",margin_bottom);});}function adjust_thumbnail(){__adjust_component(".thumbnail img");__adjust_component(".thumbnail div.caption");}adjust_thumbnail();$(window).resize(function(){adjust_thumbnail();});});</script>';
        }
        $sql_list[] = $this->insert_navigation('main_index', NULL, 'Home', 'Home', NULL, 'A Free CodeIgniter Based CMS Framework', 'main/index',
                1, 1, 1, 1,$main_index_content, 0, 'glyphicon-home', NULL, 'slide');
        $sql_list[] = $this->insert_navigation('main_language', NULL, 'Language', 'Language', NULL, 'Choose the language', 'main/language',
                1, 3, 1, 0, NULL, 0);
        $sql_list[] = $this->insert_navigation('main_third_party_auth', NULL, 'Third Party Authentication', 'Third Party Authentication', NULL, 'Third Party Authentication', 'main/hauth/index',
                1, 2, 1, 0, NULL, 0);
        // quicklink
        $sql_list[] = $this->insert_quicklink(18, 1);
        $sql_list[] = $this->insert_quicklink( 5, 2);
        $sql_list[] = $this->insert_quicklink( 2, 3);
        $sql_list[] = $this->insert_quicklink( 4, 4);
        // widget
        $sql_list[] = $this->insert_widget('section_custom_script', 'Script', '', '',
                1, 1, 1, 1, '<style type="text/css"></style><script type="text/javascript"></script>',
                NULL);
        $sql_list[] = $this->insert_widget('section_top_fix', 'Top Fix Section', '', '',
                1, 1, 2, 1, '{{ widget_name:top_navigation }}',
                NULL);
        $sql_list[] = $this->insert_widget('section_banner', 'Banner Section', '', '',
                1, 1, 3, 1, '<div class="jumbotron hidden-xs hidden-sm" style="margin-top:10px;">'.PHP_EOL.'  <img src ="{{ site_logo }}" style="max-width:20%; float:left; margin-right:10px; margin-bottom:10px;" />'.PHP_EOL.'  <h1>{{ site_name }}</h1>'.PHP_EOL.'  <p>{{ site_slogan }}</p>'.PHP_EOL.'</div>',
                NULL);
        $sql_list[] = $this->insert_widget('section_left', 'Left Section', '', '',
                1, 1, 4, 1, '',
                NULL);
        $sql_list[] = $this->insert_widget('section_right', 'Right Section', '', '',
                1, 1, 5, 1, '{{ widget_slug:sidebar }}<hr />{{ widget_slug:advertisement }}',
                NULL);
        $sql_list[] = $this->insert_widget('section_bottom', 'Bottom Section', '', '',
                1, 1, 6, 1, '{{ site_footer }}',
                NULL);
        $sql_list[] = $this->insert_widget('left_navigation', 'Left Navigation', '', 'main/widget_left_nav',
                1, 1, 7, 0, NULL,
                NULL);
        $sql_list[] = $this->insert_widget('top_navigation', 'Top Navigation', '', 'main/widget_top_nav',
                1, 1, 8, 0, NULL,
                NULL);
        $sql_list[] = $this->insert_widget('quicklink', 'Quicklinks', '', 'main/widget_quicklink',
                1, 1, 9, 0, NULL,
                NULL);
        $sql_list[] = $this->insert_widget('login', 'Login', 'Visitor need to login for authentication', 'main/widget_login',
                2, 1, 10, 0, '<form action="{{ site_url }}main/login" method="post" accept-charset="utf-8"><label>Identity</label><br><input type="text" name="identity" value=""><br><label>Password</label><br><input type="password" name="password" value=""><br><input type="submit" name="login" value="Log In"></form>',
                'sidebar, user_widget');
        $sql_list[] = $this->insert_widget('logout', 'User Info', 'Logout', 'main/widget_logout',
                3, 1, 11, 1, '{{ language:Welcome }} {{ user_name }}<br />'.PHP_EOL.'<a href="{{ site_url }}main/logout">{{ language:Logout }}</a><br />',
                'sidebar, user_widget');
        $sql_list[] = $this->insert_widget('social_plugin', 'Share This Page !!', 'Addthis', 'main/widget_social_plugin',
                1, 1, 12, 1, '<div class="addthis_sharing_toolbox"></div>'.PHP_EOL.'<!-- Go to www.addthis.com/dashboard to customize your tools -->'.PHP_EOL.'<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4ee44922521f8e39"></script>',
                'sidebar');
        $sql_list[] = $this->insert_widget('google_search', 'Search', 'Search from google', '',
                1, 0, 13, 1, '<!-- Google Custom Search Element -->'.PHP_EOL.'<div id="cse" style="width: 100%;">Loading</div>'.PHP_EOL.'<script src="http://www.google.com/jsapi" type="text/javascript"></script>'.PHP_EOL.'<script type="text/javascript">// <![CDATA['.PHP_EOL.'    google.load(\'search\', \'1\');'.PHP_EOL.'    google.setOnLoadCallback(function(){var cse = new google.search.CustomSearchControl();cse.draw(\'cse\');}, true);'.PHP_EOL.'// ]]></script>',
                'sidebar');
        $sql_list[] = $this->insert_widget('google_translate', 'Translate !!', '<p>The famous google translate</p>', '',
                1, 0, 14, 1, '<!-- Google Translate Element -->'.PHP_EOL.'<div id="google_translate_element" style="display:block"></div>'.PHP_EOL.'<script>'.PHP_EOL.'function googleTranslateElementInit() {'.PHP_EOL.'  new google.translate.TranslateElement({pageLanguage: "af"}, "google_translate_element");'.PHP_EOL.'};'.PHP_EOL.'</script>'.PHP_EOL.'<script src="http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>'.PHP_EOL.'',
                'sidebar');
        $sql_list[] = $this->insert_widget('calendar', 'Calendar', 'Indonesian Calendar', '',
                1, 0, 15, 1, '<!-------Do not change below this line------->'.PHP_EOL.'<div align="center" height="200px">'.PHP_EOL.'    <iframe align="center" src="http://www.calendarlabs.com/calendars/web-content/calendar.php?cid=1001&uid=162232623&c=22&l=en&cbg=C3D9FF&cfg=000000&hfg=000000&hfg1=000000&ct=1&cb=1&cbc=2275FF&cf=verdana&cp=bottom&sw=0&hp=t&ib=0&ibc=&i=" width="170" height="155" marginwidth=0 marginheight=0 frameborder=no scrolling=no allowtransparency=\'true\'>'.PHP_EOL.'    Loading...'.PHP_EOL.'    </iframe>'.PHP_EOL.'    <div align="center" style="width:140px;font-size:10px;color:#666;">'.PHP_EOL.'        Powered by <a  href="http://www.calendarlabs.com/" target="_blank" style="font-size:10px;text-decoration:none;color:#666;">Calendar</a> Labs'.PHP_EOL.'    </div>'.PHP_EOL.'</div>'.PHP_EOL.''.PHP_EOL.'<!-------Do not change above this line------->',
                'sidebar');
        $sql_list[] = $this->insert_widget('google_map', 'Map', 'google map', '',
                1, 0, 16, 1, '<!-- Google Maps Element Code -->'.PHP_EOL.'<iframe frameborder=0 marginwidth=0 marginheight=0 border=0 style="border:0;margin:0;width:150px;height:250px;" src="http://www.google.com/uds/modules/elements/mapselement/iframe.html?maptype=roadmap&element=true" scrolling="no" allowtransparency="true"></iframe>',
                'sidebar');
        $sql_list[] = $this->insert_widget('donate_nocms', 'Donate No-CMS', 'No-CMS Donation', NULL,
                1, 1, 17, 1, '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">'.PHP_EOL.'<input type="hidden" name="cmd" value="_s-xclick">'.PHP_EOL.'<input type="hidden" name="hosted_button_id" value="YDES6RTA9QJQL">'.PHP_EOL.'<input type="image" src="{{ base_url }}assets/nocms/images/donation.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" width="165px" height="auto" style="width:165px!important;" />'.PHP_EOL.'<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">'.PHP_EOL.'</form>',
                'advertisement');
        $sql_list[] = $this->insert_widget('navigation_right_partial', 'top navigation right partial', 'Right Partial of Top Navigation Bar. Use this when you want to add something like facebook login form', NULL,
                1, 1, 18, 1, NULL,
                NULL);
        // privilege
        $sql_list[] = $this->insert_privilege('cms_install_module', 'Install Module', 'Install Module is a very critical privilege, it allow authorized user to Install a module to the CMS.<br />By Installing module, the database structure can be changed. There might be some additional navigation and privileges added.<br /><br />You\'d be better to give this authorization only authenticated and authorized user. (I suggest to make only admin have such a privilege)'.PHP_EOL.'&nbsp;', 4);
        $sql_list[] = $this->insert_privilege('cms_manage_access', 'Manage Access', 'Manage access'.PHP_EOL.'&nbsp;', 4);
        // config
        if($this->is_subsite){
            $sql_list[] = $this->insert_config('site_name', $this->subsite, 'Site title');
            $sql_list[] = $this->insert_config('site_slogan', $this->subsite, 'Site slogan');
        }else{
            $sql_list[] = $this->insert_config('site_name', 'No-CMS', 'Site title');
            $sql_list[] = $this->insert_config('site_slogan', 'A Free CodeIgniter Based CMS Framework', 'Site slogan');
        }
        $sql_list[] = $this->insert_config('site_logo', '{{ base_url }}assets/nocms/images/No-CMS-logo.png', 'Site logo');
        $sql_list[] = $this->insert_config('site_favicon', '{{ base_url }}assets/nocms/images/No-CMS-favicon.png', 'Site favicon');
        $sql_list[] = $this->insert_config('site_footer', 'Powered by No-CMS &copy; 2013', 'Site footer');
        $sql_list[] = $this->insert_config('site_theme', 'neutral', 'Site theme');
        $sql_list[] = $this->insert_config('site_layout', 'default', 'Site layout');
        $sql_list[] = $this->insert_config('site_language', 'english', 'Site language');
        $sql_list[] = $this->insert_config('max_menu_depth', '5', 'Depth of menu recursive');
        $sql_list[] = $this->insert_config('cms_email_reply_address', 'no-reply@No-CMS.com', 'Email address');
        $sql_list[] = $this->insert_config('cms_email_reply_name', 'admin of No-CMS', 'Email name');
        $sql_list[] = $this->insert_config('cms_email_forgot_subject', 'Re-activate your account at No-CMS', 'Email subject sent when user forgot his/her password');
        $sql_list[] = $this->insert_config('cms_email_forgot_message', 'Dear, {{ user_real_name }}<br />Click <a href="{{ site_url }}main/forgot/{{ activation_code }}">{{ site_url }}main/forgot/{{ activation_code }}</a> to reactivate your account', 'Email message sent when user forgot his/her password');
        $sql_list[] = $this->insert_config('cms_email_signup_subject', 'Activate your account at No-CMS', 'Email subject sent to activate user');
        $sql_list[] = $this->insert_config('cms_email_signup_message', 'Dear, {{ user_real_name }}<br />Click <a href="{{ site_url }}main/activate/{{ activation_code }}">{{ site_url }}main/activate/{{ activation_code }}</a> to activate your account', 'Email message sent to activate user');
        $sql_list[] = $this->insert_config('cms_signup_activation', 'automatic', 'Send activation email to new member. Default : automatic, Alternatives : manual, by mail');
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
        $sql_list[] = $this->insert_config('cms_add_subsite_on_register', 'FALSE', 'Automatically create subsite on register');
        $sql_list[] = $this->insert_config('cms_subsite_use_subdomain','FALSE','Automatically use subdomain');
        $sql_list[] = $this->insert_config('cms_subsite_home_content','{{ widget_name:blog_content }}','Default subsite homepage content');
        // group user
        $sql_list[] = $this->insert_group_user();

        // TODO: make this better
        $table_name = 'main_language';
        if(!trim($this->db_table_prefix) == ''){
            $table_name = $this->db_table_prefix.'_'.$table_name;
        }
        $this->db->insert($table_name,array('name'=>'Afrikaans','code'=>'afrikaans'));
        $this->db->insert($table_name,array('name'=>'Arabic','code'=>'arabic'));
        $this->db->insert($table_name,array('name'=>'Bengali','code'=>'bengali'));
        $this->db->insert($table_name,array('name'=>'Bulgarian','code'=>'bulgarian'));
        $this->db->insert($table_name,array('name'=>'Catalan','code'=>'catalan'));
        $this->db->insert($table_name,array('name'=>'Chinese','code'=>'chinese'));
        $this->db->insert($table_name,array('name'=>'Czech','code'=>'czech'));
        $this->db->insert($table_name,array('name'=>'Danish','code'=>'danish'));
        $this->db->insert($table_name,array('name'=>'Dutch','code'=>'dutch'));
        $this->db->insert($table_name,array('name'=>'English','code'=>'english'));
        $this->db->insert($table_name,array('name'=>'French','code'=>'french'));
        $this->db->insert($table_name,array('name'=>'German','code'=>'german'));
        $this->db->insert($table_name,array('name'=>'Greek','code'=>'greek'));
        $this->db->insert($table_name,array('name'=>'Hindi','code'=>'hindi'));
        $this->db->insert($table_name,array('name'=>'Hungarian','code'=>'hungarian'));
        $this->db->insert($table_name,array('name'=>'Indonesian','code'=>'indonesian'));
        $this->db->insert($table_name,array('name'=>'Italian','code'=>'italian'));
        $this->db->insert($table_name,array('name'=>'Japanese','code'=>'japanese'));
        $this->db->insert($table_name,array('name'=>'Korean','code'=>'korean'));
        $this->db->insert($table_name,array('name'=>'Mongolian','code'=>'mongolian'));
        $this->db->insert($table_name,array('name'=>'Norwegian','code'=>'norwegian'));
        $this->db->insert($table_name,array('name'=>'Persian','code'=>'persian'));
        $this->db->insert($table_name,array('name'=>'Polish','code'=>'polish'));
        $this->db->insert($table_name,array('name'=>'Portuguese (Brazil)','code'=>'pt-br.portuguese'));
        $this->db->insert($table_name,array('name'=>'Portuguese','code'=>'pt-pt.portuguese'));
        $this->db->insert($table_name,array('name'=>'Romanian','code'=>'romanian'));
        $this->db->insert($table_name,array('name'=>'Russian','code'=>'russian'));
        $this->db->insert($table_name,array('name'=>'Slovak','code'=>'slovak'));
        $this->db->insert($table_name,array('name'=>'Spanish','code'=>'spanish'));
        $this->db->insert($table_name,array('name'=>'Thai','code'=>'thai'));
        $this->db->insert($table_name,array('name'=>'Turkish','code'=>'turkish'));
        $this->db->insert($table_name,array('name'=>'Ukrainian','code'=>'ukrainian'));
        $this->db->insert($table_name,array('name'=>'Vietnamese','code'=>'vietnamese'));


        return $sql_list;
    }

    public function build_database($config=array()){
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
                if(!trim($this->db_table_prefix) == ''){
                    $table_name = $this->db_table_prefix.'_'.$table_name;
                }
                $this->dbforge->drop_table($table_name, TRUE);
            }
            $create_table_sql_list = $this->create_all_table();
            $insert_sql_list = $this->insert_all_data($config);
            $sql_list = array_merge($sql_list, $create_table_sql_list);
            $sql_list = array_merge($sql_list, $insert_sql_list);
            $success = !$this->db_no_error;
        }

        if($this->db_protocol == 'pdo_sqlite'){
            @chmod(FCPATH.'db.sqlite', 0777);
        }

        return array(
                'success' => $success,
                'error_list' => $error_list,
                'warning_list' => $warning_list,
                'sql_list' => $sql_list,
            );

    }

    protected function change_config($file_name, $key, $value, $key_prefix = '$config[',
    $key_suffix = ']', $value_prefix = "'", $value_suffix = "';",  $equal_sign = '='){
        if(!file_exists($file_name)) return FALSE;
        $key = preg_quote($key);
        $key_prefix = preg_quote($key_prefix);
        $key_suffix = preg_quote($key_suffix);
        $value_prefix = preg_quote($value_prefix);
        $value_suffix = preg_quote($value_suffix);
        $equal_sign = preg_quote($equal_sign);
        $pattern = '/( *'.$key_prefix.$key.$key_suffix.' *'.$equal_sign.' *'.$value_prefix.')(.*?)('.$value_suffix.')/si';
        $replacement = '${1}'.$value.'${3}';

        $str = file_get_contents($file_name);
        $awal = $str;
        $str = preg_replace($pattern, $replacement, $str);

        @chmod($file_name,0777);
        @file_put_contents($file_name, $str);
    }

    public function replace_tag($file_name, $tag, $value){
        $content = file_get_contents($file_name);
        $content = str_replace('{{ '.$tag.' }}', $value, $content);
        file_put_contents($file_name, $content);
    }

    public function complete_config_file_name($file){
        if($this->is_subsite){
            $file = 'site-'.$this->subsite.'/'.$file;
        }
        return $file;
    }

    public function build_configuration($config = array()){
        // copy everything from /application/config/first-time.php into /application/config/ or /application/config/site-subsite
        if($this->is_subsite){
            // add site.php entry
            $content = file_get_contents(FCPATH.'/site.php');
            // available_site
            $content .= PHP_EOL.PHP_EOL.'// SUBSITE : '.$this->subsite.PHP_EOL;
            $content .= PHP_EOL.'$available_site[] = \''.$this->subsite.'\';';
            // aliases
            $alias_list = explode(',', $this->subsite_aliases);
            foreach($alias_list as $alias){
                $alias = trim(addslashes($alias));
                if($alias == '') continue;
                $content .= PHP_EOL.'$site_alias[\''.$alias.'\'] = \''.$this->subsite.'\';';
            }
            @chmod(FCPATH.'/site.php', 0777);
            @file_put_contents(FCPATH.'/site.php', $content);
            // make subsite config directory
            mkdir(APPPATH.'config/site-'.$this->subsite);
        }
        $file_list = scandir(APPPATH.'config/first-time', 1);
        foreach($file_list as $file){
            if(!is_dir(APPPATH.'config/first-time/'.$file)){
                if($file == 'database.php' && $this->is_subsite){
                    continue;
                }
                if($file=='cms_extended_login.php' && $this->is_subsite){
                    continue;
                }
                copy(APPPATH.'config/first-time/'.$file, APPPATH.'config/'.$this->complete_config_file_name($file));
            }
        }

        // ckeditor config
        copy(APPPATH.'config/first-time/third_party_config/ckeditor_config.js',
            FCPATH.'assets/grocery_crud/texteditor/ckeditor/config.js');
        $this->replace_tag(FCPATH.'assets/grocery_crud/texteditor/ckeditor/config.js', 'BASE_URL', base_url());

        // kcfinder config
        copy(APPPATH.'config/first-time/third_party_config/kcfinder_config.php',
            FCPATH.'assets/kcfinder/config.php');
        $this->replace_tag(FCPATH.'assets/kcfinder/config.php', 'BASE_URL', base_url());
        $this->replace_tag(FCPATH.'assets/kcfinder/config.php', 'FCPATH', addslashes(FCPATH));

        // database config
        if(!$this->is_subsite){
            $file_name = APPPATH.'config/'.$this->complete_config_file_name('database.php');
            $key_prefix = "'";
            $key_suffix = "'";
            $value_prefix = "'";
            $value_suffix = "',";
            $equal_sign = '=>';

            $db_driver = $this->get_db_driver();
            $this->change_config($file_name, "dsn", $this->build_dsn(), $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
            $this->change_config($file_name, "hostname", $this->db_host, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
            $this->change_config($file_name, "database", $this->db_name, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
            $this->change_config($file_name, "username", $this->db_username, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
            $this->change_config($file_name, "password", $this->db_password, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
            $this->change_config($file_name, "dbdriver", $db_driver, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        } else {
            $file = 'database.php';
            copy(APPPATH.'config/'.$file, APPPATH.'config/'.$this->complete_config_file_name($file));
        }

        // cms_config
        $file_name = APPPATH.'config/'.$this->complete_config_file_name('cms_config.php');
        $key_prefix = '$config[\'';
        $key_suffix = "']";
        $value_prefix = "'";
        $value_suffix = "';";
        $equal_sign = '=';

        $this->change_config($file_name, "cms_table_prefix", $this->db_table_prefix, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        // config
        $file_name = APPPATH.'config/'.$this->complete_config_file_name('config.php');
        $key_prefix = '$config[\'';
        $key_suffix = "']";
        $value_prefix = "'";
        $value_suffix = "';";
        $equal_sign = '=';

        $index_page = $this->hide_index?'':'index.php';
        $this->change_config($file_name, "index_page", $index_page, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $encryption_key = md5(time() . rand());
        $this->change_config($file_name, "encryption_key", $encryption_key, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $table_name = 'ci_sessions';
        if(!trim($this->db_table_prefix) == ''){
            $table_name = $this->db_table_prefix.'_'.$table_name;
        }
        $this->change_config($file_name, "sess_table_name", $table_name, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $value_prefix = "";
        $value_suffix = ";";
        $compress_output = $this->gzip_compression?'TRUE':'FALSE';
        $this->change_config($file_name, "minify_output", 'FALSE', $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $this->change_config($file_name, "compress_output", $compress_output, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $this->change_config($file_name, "sess_use_database", 'TRUE', $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $this->change_config($file_name, "sess_expiration", '86400', $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $this->change_config($file_name, "sess_encrypt_cookie", 'TRUE', $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);

        // routes
        $file_name = APPPATH.'config/'.$this->complete_config_file_name('routes.php');
        $key_prefix = '$route[\'';
        $key_suffix = "']";
        $value_prefix = "'";
        $value_suffix = "';";
        $equal_sign = '=';

        $this->change_config($file_name, "default_controller", 'main', $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);

        // hybridauth
        $file_name = APPPATH.'config/'.$this->complete_config_file_name('hybridauthlib.php');
        $key_prefix = '$';
        $key_suffix = "";
        $value_prefix = "";
        $value_suffix = ";";
        $equal_sign = '=';
        $val = $this->auth_enable_facebook?'TRUE':'FALSE';
        $this->change_config($file_name, "auth_enable_facebook", $val, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $val = $this->auth_enable_twitter?'TRUE':'FALSE';
        $this->change_config($file_name, "auth_enable_twitter", $val, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $val = $this->auth_enable_google?'TRUE':'FALSE';
        $this->change_config($file_name, "auth_enable_google", $val, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $val = $this->auth_enable_yahoo?'TRUE':'FALSE';
        $this->change_config($file_name, "auth_enable_yahoo", $val, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $val = $this->auth_enable_linkedin?'TRUE':'FALSE';
        $this->change_config($file_name, "auth_enable_linkedin", $val, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $val = $this->auth_enable_myspace?'TRUE':'FALSE';
        $this->change_config($file_name, "auth_enable_myspace", $val, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $val = $this->auth_enable_windows_live?'TRUE':'FALSE';
        $this->change_config($file_name, "auth_enable_foursquare", $val, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $val = $this->auth_enable_windows_live?'TRUE':'FALSE';
        $this->change_config($file_name, "auth_enable_windows_live", $val, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $val = $this->auth_enable_foursquare?'TRUE':'FALSE';
        $this->change_config($file_name, "auth_enable_foursquare", $val, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $val = $this->auth_enable_aol?'TRUE':'FALSE';
        $this->change_config($file_name, "auth_enable_aol", $val, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $val = $this->auth_enable_open_id?'TRUE':'FALSE';
        $this->change_config($file_name, "auth_enable_open_id", $val, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);

        $value_prefix = "'";
        $value_suffix = "';";
        $this->change_config($file_name, "auth_facebook_app_id", $this->auth_facebook_app_id, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $this->change_config($file_name, "auth_facebook_app_secret", $this->auth_facebook_app_secret, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $this->change_config($file_name, "auth_twitter_app_key", $this->auth_twitter_app_key, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $this->change_config($file_name, "auth_twitter_app_secret", $this->auth_twitter_app_secret, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $this->change_config($file_name, "auth_google_app_id", $this->auth_google_app_id, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $this->change_config($file_name, "auth_google_app_secret", $this->auth_google_app_secret, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $this->change_config($file_name, "auth_yahoo_app_id", $this->auth_yahoo_app_id, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $this->change_config($file_name, "auth_yahoo_app_secret", $this->auth_yahoo_app_secret, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $this->change_config($file_name, "auth_linkedin_app_key", $this->auth_linkedin_app_key, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $this->change_config($file_name, "auth_linkedin_app_secret", $this->auth_linkedin_app_secret, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $this->change_config($file_name, "auth_myspace_app_key", $this->auth_myspace_app_key, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $this->change_config($file_name, "auth_myspace_app_secret", $this->auth_myspace_app_secret, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $this->change_config($file_name, "auth_windows_live_app_id", $this->auth_windows_live_app_id, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $this->change_config($file_name, "auth_windows_live_app_secret", $this->auth_windows_live_app_secret, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $this->change_config($file_name, "auth_foursquare_app_id", $this->auth_foursquare_app_id, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $this->change_config($file_name, "auth_foursquare_app_secret", $this->auth_foursquare_app_secret, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);

        if(!$this->is_subsite){
            // make htaccess
            $rewrite_base = str_replace('index.php', '',$_SERVER['SCRIPT_NAME']);
            $data = array('rewrite_base'=>$rewrite_base);
            if($this->hide_index){
                $view_name = 'installer/htaccess_hide_index';
            }else{
                $view_name = 'installer/htaccess_not_hide_index';
            }
            $htaccess_content = $this->load->view($view_name, $data, TRUE);
            file_put_contents(FCPATH.'.htaccess', $htaccess_content);

            // site content
            $view_name = 'installer/site';
            $site_content = $this->load->view($view_name, NULL, TRUE);
            file_put_contents(FCPATH.'site.php', $site_content);
        }
    }

    public function install_modules(){
        if (in_array('curl', get_loaded_extensions())) {
            $modules = array('blog','contact_us','static_accessories');
            if(!$this->is_subsite){
                $modules[] = 'nordrassil';
                $modules[] = 'multisite';
            }
            foreach($modules as $module){
                $ch = curl_init();
                if($this->is_subsite){
                    $url = site_url($module.'/install/activate?__cms_subsite='.$this->subsite);
                }else{
                    $url = site_url($module.'/install/activate');
                }
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 3);
                curl_setopt($ch, CURLOPT_POSTFIELDS, 'silent=true&identity='.$this->admin_user_name.'&password='.$this->admin_password);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                @curl_exec($ch);
                curl_close($ch);
            }
            return TRUE;
        }
        return FALSE;
    }

}
?>
