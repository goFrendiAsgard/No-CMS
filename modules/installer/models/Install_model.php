<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Install_model extends CI_Model{

    private $__config_file = array();

    private $VERSION        = '1.1.2';
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

    public $modules         = array();
    public $configs         = array();

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
        parent::__construct();
        // automatically set table prefix based on subsite
        $this->set_subsite($this->subsite);
    }

    public function set_subsite($subsite = NULL){
        if($subsite !== NULL){
            $this->subsite = $subsite;
        }
        // sanitize subsite
        $subsite = strtolower($this->subsite);
        $sanitized_subsite = '';
        for($i=0; $i<strlen($subsite); $i++){
            $letter = substr($subsite, $i, 1);
            if(is_numeric($letter) || strpos('abcdefghijklmnopqrstuvwxyz', $letter) !== FALSE){
                $sanitized_subsite .= $letter;
            }
        }
        // site table prefix
        $this->subsite = $sanitized_subsite;
        if($this->is_subsite && $this->subsite != ''){
            $this->db_table_prefix .= '_site_'.$this->subsite;
        }
    }

    protected function build_dsn(){
        if($this->db_port == ''){
            $this->db_port = array_key_exists($this->db_protocol, $this->DEFAULT_PORT)?
                $this->DEFAULT_PORT[$this->db_protocol]: '3306';
        }
        if($this->db_protocol=='pdo_sqlite' || $this->db_protocol=='sqlite' || $this->db_protocol=='sqlite3'){
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
            'encrypt'  => FALSE,
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
        error_reporting(0);

        // try to connect, if failed, try to create database
        $db = @$this->load->database($db_config, TRUE);
        if($db->conn_id == FALSE){

            $is_mysql = $this->db_protocol=='mysql' || $this->db_protocol=='mysqli';
            $is_pdo = $db_config['dbdriver']=='pdo';
            $is_pdo_mysql = $is_pdo && (strpos($db_config['dsn'], 'mysql') == 0);
            $is_mysql = $is_mysql || $is_pdo_mysql;

            if($is_mysql){
                $db = @$this->load->database($db_config, TRUE);
                if($db->conn_id === FALSE){
                    // try to connect by using default database
                    $neutral_db_config = $db_config;
                    $neutral_db_config['database'] = '';
                    $neutral_db_config['dsn'] = str_replace(';dbname='.$this->db_name, '', $neutral_db_config['dsn']);
                    $db = @$this->load->database($neutral_db_config, TRUE);

                    if($db->conn_id !== FALSE){
                        @$this->load->dbforge($db);

                        // DROP PREVIOUSLY CREATED DATABASE IN CASE OF USER CHANGE THE DATABASE
                        if(isset($_COOKIE['created_db']) && $_COOKIE['created_db'] != '' && $_COOKIE['created_db'] != $this->db_name){
                            @$this->dbforge->drop_database($_COOKIE['created_db'], TRUE);
                            unset($_COOKIE['created_db']);
                        }

                        // CREATE DATABASE IN CASE OF IT IS NOT EXISTS
                        if(!isset($_COOKIE['created_db']) || $_COOKIE['created_db'] != $this->db_name){
                            // check first before creating
                            $query = $db->query('SHOW DATABASES');
                            foreach($query->result() as $row){
                                if($row->Database == $this->db_name){
                                    $found = TRUE;
                                    break;
                                }
                            }
                            // not, exists? create
                            if(!$found){
                                $result = @$this->dbforge->create_database($this->db_name, TRUE);
                                // save the created database
                                if($result){
                                    setcookie('created_db', $this->db_name, time() + (600), "/");
                                }
                            }
                        }
                    }
                }
            }
        }

        // try to connect again
        $db = @$this->load->database($db_config, TRUE);
        if($db->conn_id != FALSE){
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
        if($this->db_name == ''){
            $db = FALSE;
        }else{
            $db = @$this->load_database();
        }

        if($this->is_subsite){
            if($this->subsite == ''){
                $success = FALSE;
                $error_list[] = 'Subsite cannot be empty';
            }

            // get subsite table name and multisite installation status
            $t_subsite = '';
            $multisite_installed = FALSE;
            // find out whether multisite is installed or not. If multisite is installed, set multisite_installed
            if(file_exists(APPPATH.'config/main/database.php')){
                // multisite, can use GET or subdomain
                $cms_config_file = APPPATH.'config/main/cms_config.php';
                if(file_exists($cms_config_file)){
                    include($cms_config_file);
                    $cms_table_prefix = trim($config['__cms_table_prefix'])==''? '' : $config['__cms_table_prefix'].'_';
                    $query = $this->db->select('module_path')
                        ->from($cms_table_prefix.'main_module')
                        ->where('module_name', 'gofrendi.noCMS.multisite')
                        ->get();
                    // if multisite module is not installed then the subsite is valid, and it is not subdomain
                    if($query->num_rows() > 0){
                        $row = $query->row();
                        // get module path
                        $multisite_path = $row->module_path;
                        // get multisite table prefix
                        $multisite_config_file = 'modules/'.$multisite_path.'/config/module_config.php';
                        if(file_exists($multisite_config_file)){
                            include($multisite_config_file);
                            $multisite_table_prefix = trim($config['__cms_table_prefix'])==''? $cms_table_prefix : $cms_table_prefix.$config['module_table_prefix'].'_';
                            // renew multisite_installed and t_subsite
                            $t_subsite = $multisite_table_prefix.'subsite';
                            $multisite_installed = TRUE;
                        }
                    }
                }
            }

            if($multisite_installed == FALSE){ // multisite is not installed
                $success = FALSE;
                $error_list[] = 'Multisite module (gofrendi.noCMS.multisite) should be installed';
            }else{
                $query = $this->db->select('name')
                    ->from($t_subsite)
                    ->where('name', $this->subsite)
                    ->get();
                // subsite already exists
                if($query->num_rows()>0){
                    $success = FALSE;
                    $error_list[] = 'Subsite already exists';
                }
            }
        }

        // subsite doesn't need to check database
        if(!$this->is_subsite){
            // database connection
            if($db === FALSE || $db === NULL){
                $success =  FALSE;
                $error_list[] = 'Cannot connect using provided <a class="a-change-tab" href="#" tab="#tab1" component="db_protocol">Database Setting</a>';
            }
            if($this->db_name=='' && $this->db_protocol != 'pdo_sqlite'){
                $success = FALSE;
                $error_list[] = '<a class="a-change-tab" href="#" tab="#tab1" component="db_name">Database schema</a> cannot be empty';
            }
            // admin's user name
            if($this->admin_user_name==''){
                $success = FALSE;
                $error_list[] = '<a class="a-change-tab" href="#" tab="#tab2" component="admin_user_name">Super Admin\'s username</a> is empty';
            }
            // admin's real name
            if($this->admin_real_name==''){
                $success = FALSE;
                $error_list[] = '<a class="a-change-tab" href="#" tab="#tab2" component="admin_real_name">Super Admin\'s real name</a> is empty';
            }
            // admin's password
            if($this->admin_password==''){
                $success = FALSE;
                $error_list[] = '<a class="a-change-tab" href="#" tab="#tab2" component="admin_password">Super Admin\'s password</a> is empty';
            }else if ($this->admin_password != $this->admin_confirm_password){
                $success = FALSE;
                $error_list[] = '<a class="a-change-tab" href="#" tab="#tab2" component="admin_confirm_password">Super Admin\'s password confirmation</a> doesn\'t match';
            }
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
                $error_list[] = FCPATH.'assets/grocery_crud/texteditor/ckeditor is not writable';
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
        // tmp folder
        if (!is_writable(APPPATH.'config/tmp')) {
            $success = FALSE;
            $error_list[] = APPPATH."config/tmp is not writable";
        }
        // custome logo, favicon, background, and profile picture
        if (!is_writable(FCPATH.'assets/nocms/images/custom_background')) {
            $success = FALSE;
            $error_list[] = FCPATH."assets/nocms/images/custom_background is not writable";
        }
        if (!is_writable(FCPATH.'assets/nocms/images/custom_logo')) {
            $success = FALSE;
            $error_list[] = FCPATH."assets/nocms/images/custom_logo is not writable";
        }
        if (!is_writable(FCPATH.'assets/nocms/images/custom_favicon')) {
            $success = FALSE;
            $error_list[] = FCPATH."assets/nocms/images/custom_favicon is not writable";
        }
        if (!is_writable(FCPATH.'assets/nocms/images/profile_picture')) {
            $success = FALSE;
            $error_list[] = FCPATH."assets/nocms/images/profile_picture is not writable";
        }
        // third party authentication activated
        if ($this->auth_enable_facebook || $this->auth_enable_twitter || $this->auth_enable_google || $this->auth_enable_yahoo || $this->auth_enable_linkedin || $this->auth_enable_myspace || $this->auth_enable_foursquare || $this->auth_enable_windows_live || $this->auth_enable_open_id || $this->auth_enable_aol ) {
            // curl
            if (!in_array('curl', get_loaded_extensions())) {
                $success  = FALSE;
                $error_list[] = 'php-curl is not enabled';
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
        $fields['_created_at'] = array('type' => 'TIMESTAMP', 'null' => true);
        $fields['_updated_at'] = array('type' => 'TIMESTAMP', 'null' => true);
        $fields['_created_by'] = array('type' => 'INT', 'constraint' => 20, 'unsigned' => true, 'null' => true);
        $fields['_updated_by'] = array('type' => 'INT', 'constraint' => 20, 'unsigned' => true, 'null' => true);

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
            'constraint' => '255',
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
                'group_id'      => $type_primary_key,
                'group_name'    => $type_varchar_small_strict,
                'description'   => $type_text,
            );
        $sql_list[] = $this->create_table('main_group',$fields);

        // WIDGET
        $fields = array(
                'widget_id'         => $type_primary_key,
                'widget_name'       => $type_varchar_large_strict,
                'title'             => $type_varchar_small,
                'description'       => $type_text,
                'url'               => $type_varchar_large,
                'authorization_id'  => $type_foreign_key_default_1,
                'active'            => $type_boolean_true,
                'index'             => $type_index,
                'is_static'         => $type_boolean_false,
                'static_content'    => $type_text,
                'slug'              => $type_varchar_large,
            );
        $sql_list[] = $this->create_table('main_widget',$fields);

        // NAVIGATION
        $fields = array(
                'navigation_id'     => $type_primary_key,
                'navigation_name'   => $type_varchar_large_strict,
                'parent_id'         => $type_foreign_key,
                'title'             => $type_varchar_small_strict,
                'bootstrap_glyph'   => $type_varchar_small,
                'page_title'        => $type_varchar_small,
                'page_keyword'      => $type_varchar_large,
                'description'       => $type_text,
                'url'               => $type_varchar_large,
                'authorization_id'  => $type_foreign_key_default_1,
                'active'            => $type_boolean_true,
                'index'             => $type_index,
                'is_static'         => $type_boolean_false,
                'static_content'    => $type_text,
                'only_content'      => $type_boolean_false,
                'default_theme'     => $type_varchar_small,
                'default_layout'    => $type_varchar_small,
                'notif_url'         => $type_varchar_large,
                'children'          => $type_varchar_large,
                'hidden'            => $type_boolean_false,
                'custom_style'      => $type_text,
                'custom_script'     => $type_text,
                'page_twitter_card' => $type_varchar_large,
                'page_image'        => $type_text,
                'page_author'       => $type_varchar_large,
                'page_type'         => $type_varchar_large,
                'page_fb_admin'     => $type_varchar_large,
                'page_twitter_publisher_handler' => $type_varchar_large,
                'page_twitter_author_handler' => $type_varchar_large,
            );
        $sql_list[] = $this->create_table('main_navigation',$fields);

        // QUICKLINK
        $fields = array(
                'quicklink_id'      => $type_primary_key,
                'navigation_id'     => $type_foreign_key_not_null,
                'index'             => $type_index,
            );
        $sql_list[] = $this->create_table('main_quicklink',$fields);

        // PRIVILEGE
        $fields = array(
                'privilege_id'      => $type_primary_key,
                'privilege_name'    => $type_varchar_small_strict,
                'title'             => $type_varchar_small,
                'description'       => $type_text,
                'authorization_id'  => $type_foreign_key_default_1,
            );
        $sql_list[] = $this->create_table('main_privilege',$fields);

        // USER
        if(!$this->is_subsite){
            $fields = array(
                    'user_id'           => $type_primary_key,
                    'user_name'         => $type_varchar_small_strict,
                    'email'             => $type_varchar_small,
                    'password'          => $type_password,
                    'activation_code'   => $type_varchar_small,
                    'real_name'         => $type_varchar_large,
                    'active'            => $type_boolean_true,
                    'auth_OpenID'       => $type_varchar_large,
                    'auth_Facebook'     => $type_varchar_large,
                    'auth_Twitter'      => $type_varchar_large,
                    'auth_Google'       => $type_varchar_large,
                    'auth_Yahoo'        => $type_varchar_large,
                    'auth_LinkedIn'     => $type_varchar_large,
                    'auth_MySpace'      => $type_varchar_large,
                    'auth_Foursquare'   => $type_varchar_large,
                    'auth_AOL'          => $type_varchar_large,
                    'auth_Live'         => $type_varchar_large,
                    'language'          => $type_varchar_small,
                    'theme'             => $type_varchar_small,
                    'birthdate'         => $type_date,
                    'sex'               => $type_varchar_small,
                    'profile_picture'   => $type_varchar_large,
                    'self_description'  => $type_text,
                    'last_active'       => $type_varchar_small,
                    'login'             => $type_boolean_false,
                    'subsite'           => $type_varchar_large,
                );
            $sql_list[] = $this->create_table('main_user',$fields);
        }

        // GROUP WIDGET
        $fields = array(
                'id'            => $type_primary_key,
                'group_id'      => $type_foreign_key_not_null,
                'widget_id'     => $type_foreign_key_not_null,
            );
        $sql_list[] = $this->create_table('main_group_widget',$fields);

        // GROUP NAVIGATION
        $fields = array(
                'id'            => $type_primary_key,
                'group_id'      => $type_foreign_key_not_null,
                'navigation_id' => $type_foreign_key_not_null,
            );
        $sql_list[] = $this->create_table('main_group_navigation',$fields);

        // GROUP PRIVILEGE
        $fields = array(
                'id'            => $type_primary_key,
                'group_id'      => $type_foreign_key_not_null,
                'privilege_id'  => $type_foreign_key_not_null,
            );
        $sql_list[] = $this->create_table('main_group_privilege',$fields);

        // GROUP USER
        $fields = array(
                'id'        => $type_primary_key,
                'group_id'  => $type_foreign_key_not_null,
                'user_id'   => $type_foreign_key_not_null,
            );
        $sql_list[] = $this->create_table('main_group_user',$fields);

        // MODULE
        $fields = array(
                'module_id'     => $type_primary_key,
                'module_name'   => $type_varchar_small_strict,
                'module_path'   => $type_varchar_large_strict,
                'version'       => $type_varchar_small,
                'user_id'       => $type_foreign_key,
            );
        $sql_list[] = $this->create_table('main_module',$fields);

        // MODULE DEPENDENCY
        $fields = array(
                'module_dependency_id'  => $type_primary_key,
                'module_id'             => $type_foreign_key_not_null,
                'parent_id'             => $type_foreign_key_not_null,
            );
        $sql_list[] = $this->create_table('main_module_dependency',$fields);

        // CONFIG
        $fields = array(
                'config_id'     => $type_primary_key,
                'config_name'   => $type_varchar_large_strict,
                'value'         => $type_text,
                'description'   => $type_text,
            );
        $sql_list[] = $this->create_table('main_config',$fields);

        // LANGUAGE
        $fields = array(
                'language_id'   => $type_primary_key,
                'name'          => $type_varchar_small_strict,
                'code'          => $type_text,
                'iso_code'      => $type_text,
            );
        $sql_list[] = $this->create_table('main_language',$fields);

        // DETAIL LANGUAGE
        $fields = array(
                'detail_language_id'    => $type_primary_key,
                'id_language'           => $type_int,
                'key'                   => $type_varchar_small_strict,
                'translation'           => $type_varchar_small_strict,
            );
        $sql_list[] = $this->create_table('main_detail_language',$fields);

        // ROUTES
        $fields = array(
                'route_id'      => $type_primary_key,
                'key'           => $type_text,
                'value'         => $type_text,
                'description'   => $type_text,
            );
        $sql_list[] = $this->create_table('main_route', $fields);

        // LAYOUT
        $fields = array(
                'layout_id'     => $type_primary_key,
                'layout_name'   => $type_varchar_small_strict,
                'template'      => $type_text,
            );
        $sql_list[] = $this->create_table('main_layout',$fields);

        return $sql_list;
    }

    protected function insert_batch($table_name, $data){
        if(!trim($this->db_table_prefix) == ''){
            $table_name = $this->db_table_prefix.'_'.$table_name;
        }
        if(in_array($this->db_protocol, array('mysql', 'mysqli', 'pdo_pgsql', 'pdo_mysql'))){
            $this->db_no_error = $this->db->insert_batch($table_name, $data) && $this->db_no_error;
            return $this->db->last_query();
        }else{
            $return = array();
            foreach($data as $row){
                $this->db_no_error = $this->db->insert($table_name, $row) && $this->db_no_error;
                $return[] = $this->db->last_query();
            }
            return $return;
        }

    }

    protected function turn_into_associative_array($array, $keys){
        if(array_keys($array) == range(0, count($array) - 1)){
            $new_array = array();
            foreach($array as $row){
                $new_row = array();
                for($i=0; $i<count($keys); $i++){
                    if($i>=count($row)){
                        $new_row[$keys[$i]] = NULL;
                    }else{
                        $new_row[$keys[$i]] = $row[$i];
                    }
                }
                $new_array[] = $new_row;
            }
            $array = $new_array;
        }
        return $array;
    }

    protected function insert_user(){
        $this->load->helper('cms');
        if($this->is_subsite){
            include(APPPATH.'config/site-'.$this->subsite.'/cms_config.php');
            $chipper = $config['__cms_chipper'];
        }else{
            include(APPPATH.'config/main/cms_config.php');
            $chipper = $config['__cms_chipper'];
        }
        $array = array(
                'user_name' => $this->admin_user_name,
                'email' => $this->admin_email,
                'password'=> cms_md5($this->admin_password, $chipper),
                'real_name' => $this->admin_real_name
            );
        $table_name = 'main_user';
        if(!trim($this->db_table_prefix) == ''){
            $table_name = $this->db_table_prefix.'_'.$table_name;
        }
        $this->db_no_error = $this->db->insert($table_name, $array) && $this->db_no_error;
        return $this->db->last_query();
    }

    protected function insert_authorizations($data){
        $data = $this->turn_into_associative_array($data, array('authorization_name', 'description'));
        return $this->insert_batch('main_authorization', $data);
    }

    protected function insert_groups($data){
        $data = $this->turn_into_associative_array($data, array('group_name', 'description'));
        return $this->insert_batch('main_group', $data);
    }

    protected function insert_navigations($data){
        $data = $this->turn_into_associative_array($data, array('navigation_name',
            'parent_id', 'title', 'page_title', 'page_keyword', 'description',
            'url', 'authorization_id', 'index', 'active', 'is_static', 'static_content',
            'only_content', 'bootstrap_glyph', 'default_theme', 'default_layout', 'hidden',
            'custom_style', 'custom_script'));
        for($i=0; $i<count($data); $i++){
            if(!isset($data[$i]['hidden']) || $data[$i]['hidden'] == NULL){
                $data[$i]['hidden'] = 0;
            }
        }
        return $this->insert_batch('main_navigation', $data);
    }

    protected function insert_quicklinks($data){
        $data = $this->turn_into_associative_array($data, array('navigation_id', 'index'));
        return $this->insert_batch('main_quicklink', $data);
    }

    protected function insert_widgets($data){
        $data = $this->turn_into_associative_array($data, array('widget_name',
            'title', 'description', 'url', 'authorization_id', 'active', 'index',
            'is_static', 'static_content', 'slug'));
        return $this->insert_batch('main_widget', $data);
    }

    protected function insert_privileges($data){
        $data = $this->turn_into_associative_array($data, array('privilege_name',
            'title', 'description', 'authorization_id'));
        return $this->insert_batch('main_privilege', $data);
    }

    protected function insert_languages($data){
        $data = $this->turn_into_associative_array($data, array('name', 'code'));
        return $this->insert_batch('main_language', $data);
    }

    protected function insert_configs($data){
        $data = $this->turn_into_associative_array($data, array('config_name', 'value', 'description'));
        return $this->insert_batch('main_config', $data);
    }

    protected function insert_group_users($data){
        $data = $this->turn_into_associative_array($data, array('group_id', 'user_id'));
        return $this->insert_batch('main_group_user', $data);
    }

    protected function insert_layouts($data){
        $data = $this->turn_into_associative_array($data, array('layout_name', 'template'));
        return $this->insert_batch('main_layout', $data);
    }

    protected function insert_all_data($config=NULL){
        $sql_list = array();

        // authorization
        $sql_list[] = $this->insert_authorizations(array(
                array('Everyone', 'All visitor of the web are permitted (e.g:view blog content)'),
                array('Unauthenticated', 'Only non-member visitor, they who hasn\'t log in yet (e.g:view member registration page)'),
                array('Authenticated', 'Only member (e.g:change password)'),
                array('Authorized', 'Only member with certain privilege (depend on group)'),
                array('Exclusive Authorized', 'Even Super Admin cannot access this if not allowed'),
            ));

        // group
        $sql_list[] = $this->insert_groups(array(
                array('Super Admin', 'Every member of this group can do everything possible, but only programmer can turn the impossible into real :D'),
                array('Employee', 'Group Example')
            ));

        // user
        if(!$this->is_subsite){
            $sql_list[] = $this->insert_user();
            $sql_list[] = $this->insert_group_users(array(array(1,1)));
        }else{
            if(is_array($config) && array_key_exists('subsite_user_id', $config)){
                $this->insert_group_users(array(array(1, $config['subsite_user_id'])));
            }
        }

        // navigation
        if($this->is_subsite){
            if(array_key_exists('subsite_home_content', $config)){
                $main_index_content = $config['subsite_home_content'];
            }else{
                $main_index_content = '<h1>Welcome</h1>Hi, welcome to subsite '.$this->subsite;
            }
            if(array_key_exists('subsite_homepage_layout', $config)){
                $homepage_layout = $config['subsite_homepage_layout'];
            }else{
                $homepage_layout = 'slide';
            }
            $main_index_custom_style = '';
            $main_index_custom_script = '';
        }else{
            $main_index_content = '<div class="page-header">' . PHP_EOL . '    <h1>' . PHP_EOL . '        Welcome to No-CMS<br />' . PHP_EOL . '        <small>A Free CodeIgniter based CMS Framework</small>' . PHP_EOL . '    </h1>' . PHP_EOL . '</div>' . PHP_EOL . '<div class="row col-sm-12 col-md-12 col-xs-12">' . PHP_EOL . '    <div class="row col-md-12">' . PHP_EOL . '        <a style="margin-top:10px;" class="btn btn-default col-md-5 col-xs-12" href="https://github.com/goFrendiAsgard/No-CMS/archive/master.zip"><i class="glyphicon glyphicon-thumbs-up">&nbsp;</i>&nbsp;Get Stable Version</a> <a style="margin-top:10px;" class="btn btn-default col-md-5 col-xs-12 col-md-offset-2" href="https://github.com/goFrendiAsgard/No-CMS/archive/development.zip"><i class="glyphicon glyphicon-wrench">&nbsp;</i>&nbsp;Get Development Version</a>' . PHP_EOL . '    </div>' . PHP_EOL . '    <div class="row col-md-12">' . PHP_EOL . '        <a style="margin-top:10px;" class="btn btn-default col-md-5 col-xs-12" href="https://facebook.com/nocms"><i class="glyphicon glyphicon-comment">&nbsp;</i>&nbsp;No-CMS Forum</a> <a style="margin-top:10px;" class="btn btn-default col-md-5 col-xs-12 col-md-offset-2" href="https://github.com/goFrendiAsgard/No-CMS/blob/master/doc/tutorial.md"><i class="glyphicon glyphicon-book">&nbsp;</i>&nbsp;Visit User Guide</a>' . PHP_EOL . '    </div>' . PHP_EOL . '</div>' . PHP_EOL . '<p class="lead row col-sm-12 col-md-12 col-xs-12">' . PHP_EOL . '    No-CMS is not just another CodeIgniter based CMS. There are many things that will make you falling in love with it.' . PHP_EOL . '</p>' . PHP_EOL . '<div class="row col-sm-12 col-md-12 col-xs-12">' . PHP_EOL . '    <div class="col-sm-6 col-md-4">' . PHP_EOL . '        <div class="thumbnail">' . PHP_EOL . '            <img alt="..." src="{{ base_url }}modules/main/assets/images/rocket.png" />' . PHP_EOL . '            <div class="caption">' . PHP_EOL . '                <h3>' . PHP_EOL . '                    Easy Installation' . PHP_EOL . '                </h3>' . PHP_EOL . '                <p>' . PHP_EOL . '                    Installing No-CMS is very straight forward. Just follow the on-screen instruction and you will be fine.' . PHP_EOL . '                </p>' . PHP_EOL . '            </div>' . PHP_EOL . '        </div>' . PHP_EOL . '    </div>' . PHP_EOL . '    <div class="col-sm-6 col-md-4">' . PHP_EOL . '        <div class="thumbnail">' . PHP_EOL . '            <img alt="..." src="{{ base_url }}modules/main/assets/images/profle.png" />' . PHP_EOL . '            <div class="caption">' . PHP_EOL . '                <h3>' . PHP_EOL . '                    User Group' . PHP_EOL . '                </h3>' . PHP_EOL . '                <p>' . PHP_EOL . '                    Determine who can access your pages. Put your users into several managable groups' . PHP_EOL . '                </p>' . PHP_EOL . '            </div>' . PHP_EOL . '        </div>' . PHP_EOL . '    </div>' . PHP_EOL . '    <div class="col-sm-6 col-md-4">' . PHP_EOL . '        <div class="thumbnail">' . PHP_EOL . '            <img alt="..." src="{{ base_url }}modules/main/assets/images/brush-pencil.png" />' . PHP_EOL . '            <div class="caption">' . PHP_EOL . '                <h3>' . PHP_EOL . '                    Customizable Themes' . PHP_EOL . '                </h3>' . PHP_EOL . '                <p>' . PHP_EOL . '                    Choose or make your own theme. Make your very own website.' . PHP_EOL . '                </p>' . PHP_EOL . '            </div>' . PHP_EOL . '        </div>' . PHP_EOL . '    </div>' . PHP_EOL . '    <div class="col-sm-6 col-md-4">' . PHP_EOL . '        <div class="thumbnail">' . PHP_EOL . '            <img alt="..." src="{{ base_url }}modules/main/assets/images/gear.png" />' . PHP_EOL . '            <div class="caption">' . PHP_EOL . '                <h3>' . PHP_EOL . '                    Modules &amp; Module Generator' . PHP_EOL . '                </h3>' . PHP_EOL . '                <p>' . PHP_EOL . '                    Add No-CMS functionality by installing or making your own modules. We also have module generator to help you.' . PHP_EOL . '                </p>' . PHP_EOL . '            </div>' . PHP_EOL . '        </div>' . PHP_EOL . '    </div>' . PHP_EOL . '    <div class="col-sm-6 col-md-4">' . PHP_EOL . '        <div class="thumbnail">' . PHP_EOL . '            <img alt="..." src="{{ base_url }}modules/main/assets/images/flame.png" />' . PHP_EOL . '            <div class="caption">' . PHP_EOL . '                <h3>' . PHP_EOL . '                    Developer Friendly' . PHP_EOL . '                </h3>' . PHP_EOL . '                <p>' . PHP_EOL . '                    No-CMS was built on top of CodeIgniter, HMVC Extension, and Grocery CRUD.' . PHP_EOL . '                </p>' . PHP_EOL . '            </div>' . PHP_EOL . '        </div>' . PHP_EOL . '    </div>' . PHP_EOL . '    <div class="col-sm-6 col-md-4">' . PHP_EOL . '        <div class="thumbnail">' . PHP_EOL . '            <img alt="..." src="{{ base_url }}modules/main/assets/images/frames.png" />' . PHP_EOL . '            <div class="caption">' . PHP_EOL . '                <h3>' . PHP_EOL . '                    Many out-of-the-box features' . PHP_EOL . '                </h3>' . PHP_EOL . '                <p>' . PHP_EOL . '                    Third Party Authentication, Widgets, Language Management, Multisite, and many others.' . PHP_EOL . '                </p>' . PHP_EOL . '            </div>' . PHP_EOL . '        </div>' . PHP_EOL . '    </div>' . PHP_EOL . '</div>' . PHP_EOL . '<div class="alert alert-info alert-dismissable row col-sm-12 col-md-12 col-xs-12">' . PHP_EOL . '    <button aria-hidden="true" class="close" data-dismiss="alert" type="button">' . PHP_EOL . '        &times;' . PHP_EOL . '    </button>' . PHP_EOL . '    <h2>' . PHP_EOL . '        Site owner, please read these simple howtos !!!' . PHP_EOL . '    </h2>' . PHP_EOL . '    <h3>' . PHP_EOL . '        Modify this home page' . PHP_EOL . '    </h3>' . PHP_EOL . '    <p>' . PHP_EOL . '        Seeing this message means that you&#39;ve just successfully install No-CMS on your server.<br />' . PHP_EOL . '        And, we believe you won&#39;t just stop here. You have several options to modify this homepage:' . PHP_EOL . '    </p>' . PHP_EOL . '    <ul>' . PHP_EOL . '        <li>' . PHP_EOL . '            <b>Using static page</b>' . PHP_EOL . '            <p>' . PHP_EOL . '                Just go to&nbsp;<a class="btn btn-primary" href="{{ site_url }}main/manage_navigation/index/edit/20">Navigation Management</a> and modify the <b>static content</b>' . PHP_EOL . '            </p>' . PHP_EOL . '        </li>' . PHP_EOL . '        <li>' . PHP_EOL . '            <b>Using dynamic page</b>' . PHP_EOL . '            <p>' . PHP_EOL . '                You can&nbsp;<em>deactivate</em>&nbsp;<strong>static option</strong>&nbsp;on&nbsp;<a class="btn btn-primary" href="{{ site_url }}main/manage_navigation/index/edit/17">Navigation Management</a>&nbsp;and edit the corresponding view (<code>/modules/main/views/main_index.php</code>)' . PHP_EOL . '            </p>' . PHP_EOL . '        </li>' . PHP_EOL . '    </ul>' . PHP_EOL . '    <h3>' . PHP_EOL . '        Need helps?' . PHP_EOL . '    </h3>' . PHP_EOL . '    <p>' . PHP_EOL . '        You are welcomed to join No-CMS forum: <a href="http://getnocms.com/forum">http://getnocms.com/forum</a><br />' . PHP_EOL . '        or open an issue on No-CMS github repository: <a href="https://github.com/goFrendiAsgard/No-CMS/">https://github.com/goFrendiAsgard/No-CMS/</a><br />' . PHP_EOL . '        In case of you&#39;ve found a critical bug, you can also directly send email to&nbsp;<a href="mailto:gofrendiasgard@gmail.com">gofrendiasgard@gmail.com</a><br />' . PHP_EOL . '        That&#39;s all. Start your new adventure with No-CMS !!!' . PHP_EOL . '    </p>' . PHP_EOL . '</div>';
            $homepage_layout    = 'slide';
            $main_index_custom_script = '$(window).on(\'load\', function(){' . PHP_EOL . '    function __adjust_component(identifier){' . PHP_EOL . '        var max_height=0;' . PHP_EOL . '        $(identifier).each(function(){' . PHP_EOL . '            $(this).css("margin-bottom",0);' . PHP_EOL . '            if($(this).height()>max_height){' . PHP_EOL . '                max_height=$(this).height();' . PHP_EOL . '            }' . PHP_EOL . '        });' . PHP_EOL . '        $(identifier).each(function(){' . PHP_EOL . '            $(this).height(max_height);' . PHP_EOL . '            var margin_bottom=0;' . PHP_EOL . '            if($(this).height()<max_height){' . PHP_EOL . '                margin_bottom=max_height-$(this).height();' . PHP_EOL . '            }' . PHP_EOL . '            margin_bottom+=10;' . PHP_EOL . '            $(this).css("margin-bottom",margin_bottom);' . PHP_EOL . '        });' . PHP_EOL . '    }' . PHP_EOL . '' . PHP_EOL . '    function adjust_thumbnail(){' . PHP_EOL . '        __adjust_component(".thumbnail img");' . PHP_EOL . '        __adjust_component(".thumbnail div.caption");' . PHP_EOL . '    }' . PHP_EOL . '    adjust_thumbnail();' . PHP_EOL . '' . PHP_EOL . '    $(window).resize(function(){' . PHP_EOL . '        adjust_thumbnail();' . PHP_EOL . '    });' . PHP_EOL . '});';
            $main_index_custom_style = '.thumbnail .caption p{' . PHP_EOL . '    font-size:small;' . PHP_EOL . '}' . PHP_EOL . '.thumbnail{' . PHP_EOL . '    border:none;' . PHP_EOL . '}' . PHP_EOL . '.page-header{' . PHP_EOL . '    margin-top:0;' . PHP_EOL . '}' . PHP_EOL . '#__section-left-and-content hr, #__section-left-and-content .breadcrumb{' . PHP_EOL . '    margin:0;' . PHP_EOL . '}' . PHP_EOL . '#__section-left-and-content .lead{' . PHP_EOL . '    margin-top:20px;' . PHP_EOL . '}';
        }
        $sql_list[] = $this->insert_navigations(array(
                array('main_login', NULL, 'Login', 'Login', NULL, 'Visitor need to login for authentication', 'main/login',
                    2, 4, 1, 0, NULL, 0, 'glyphicon-home', NULL, 'default-one-column'),
                array('main_forgot', NULL, 'Forgot Password', 'Forgot', NULL, 'Accidentally forgot password', 'main/forgot',
                    2, 6, 1, 0, NULL, 0),
                array('main_logout', NULL, 'Logout', 'Logout', NULL, 'Logout for deauthentication', 'main/logout',
                    3, 5, 1, 0, NULL, 0),
                array('main_management', NULL, 'CMS Management', 'CMS Management', NULL, 'The main management of the CMS. Including User, Group, Privilege and Navigation Management', 'main/management',
                    4, 9, 1, 0, NULL, 0, NULL, NULL, 'default-one-column'),
                array('main_register', NULL, 'Register', 'Register', NULL, 'New User Registration', 'main/register',
                    2, 7, 1, 0, NULL, 0, NULL, NULL, 'default-one-column'),
                array('main_change_profile', NULL, 'Change Profile', 'Change Profile', NULL, 'Change Current Profile', 'main/change_profile',
                    3, 8, 1, 0, NULL, 0),
                array('main_group_management', 4, 'Group Management', 'Group Management', NULL, 'Group Management', 'main/manage_group',
                    4, 0, 1, 0, NULL, 0, NULL, NULL, 'default-one-column'),
                array('main_user_management', 4, 'User Management', 'User Management', NULL, 'Manage User', 'main/manage_user',
                    4, 1, 1, 0, NULL, 0, NULL, NULL, 'default-one-column'),
                array('main_navigation_management', 4, 'Navigation Management', 'Navigation Management', NULL, 'Navigation management', 'main/manage_navigation',
                    4, 3, 1, 0, NULL, 0, NULL, NULL, 'default-one-column'),
                array('main_privilege_management', 4, 'Privilege Management', 'Privilege Management', NULL, 'Privilege Management', 'main/manage_privilege',
                    4, 2, 1, 0, NULL, 0, NULL, NULL, 'default-one-column'),
                array('main_module_management', 4, 'Module Management', 'Module Management', NULL, 'Install Or Uninstall Thirdparty Module', 'main/module_management',
                    4, 5, 1, 0, NULL, 0, NULL, NULL, 'default-one-column'),
                array('main_change_theme', 4, 'Change Theme', 'Change Theme', NULL, 'Change Theme', 'main/change_theme',
                    4, 6, 1, 0, NULL, 0, NULL, NULL, 'default-one-column'),
                array('main_widget_management', 4, 'Widget Management', 'Widget Management', NULL, 'Manage Widgets', 'main/manage_widget',
                    4, 4, 1, 0, NULL, 0, NULL, NULL, 'default-one-column'),
                array('main_quicklink_management', 4, 'Quick Link Management', 'Quick Link Management', NULL, 'Manage Quick Link', 'main/manage_quicklink',
                    4, 7, 1, 0, NULL, 0, NULL, NULL, 'default-one-column'),
                array('main_language_management', 4, 'Language Management', 'Language Management', NULL, 'Manage Language', 'main/manage_language',
                    4, 8, 1, 0, NULL, 0, NULL, NULL, 'default-one-column'),
                array('main_config_management', 4, 'Configuration Management', 'Configuration Management', NULL, 'Manage Configuration Parameters', 'main/manage_config',
                    4, 9, 1, 0, NULL, 0, NULL, NULL, 'default-one-column'),
                array('main_route_management', 4, 'Route Management', 'Route Management', NULL, 'Manage Routes', 'main/manage_route',
                    4, 10, 1, 0, NULL, 0, NULL, NULL, 'default-one-column'),
                array('main_layout_management', 4, 'Layout Management', 'Layout Management', NULL, 'Manage Layouts', 'main/manage_layout',
                        4, 10, 1, 0, NULL, 0, NULL, NULL, 'default-one-column'),
                array('main_setting', 4, 'Setting', 'CMS Setting', NULL, 'CMS Setting', 'main/setting',
                    4, 12, 1, 0, NULL, 0, NULL, NULL, 'default-one-column'),
                array('main_index', NULL, 'Home', 'Home', NULL, 'A Free CodeIgniter Based CMS Framework', 'main/index',
                    1, 1, 1, 1, $main_index_content, 0, 'glyphicon-home', NULL, $homepage_layout, 0, $main_index_custom_style, $main_index_custom_script),
                array('main_language', NULL, 'Language', 'Language', NULL, 'Choose the language', 'main/language',
                    1, 3, 1, 0, NULL, 0),
                array('main_third_party_auth', NULL, 'Third Party Authentication', 'Third Party Authentication', NULL, 'Third Party Authentication', 'main/hauth/index',
                    1, 2, 1, 0, NULL, 0),
                array('main_404', NULL, '404 Not Found', '404 Page', NULL, '404 Not Found', 'not_found',
                    1, 9, 1, 1, '<h1>404 Page not found</h1><p>Sorry, the page does not exists.<br /><a class="btn btn-primary" href="{{ site_url }}">Please go back <i class="glyphicon glyphicon-home"></i></a></p>', 0, NULL, NULL, 'default-one-column', 1),
            ));


        // quicklink
        $sql_list[] = $this->insert_quicklinks(array(
                array(20, 1),
                array(5,2),
                array(2,3),
                array(4,4),
            ));
        // widget
        $sql_list[] = $this->insert_widgets(array(
                array('section_custom_style', '', 'Custom CSS', '',
                    1, 1, 1, 1, '',
                    NULL),
                array('section_custom_script', '', 'Custom Javascript', '',
                    1, 1, 1, 1, '',
                    NULL),
                array('section_top_fix', 'Top Fix Section', '', '',
                    1, 1, 2, 1, '{{ widget_name:top_navigation }}',
                    NULL),
                array('section_banner', 'Banner Section', '', '',
                    1, 1, 3, 1, '<div id="div-section-banner" class="jumbotron hidden-xs hidden-sm" style="margin-top:10px;">'.PHP_EOL.'  <img src ="{{ site_logo }}" style="max-width:20%; float:left; margin-right:10px; margin-bottom:10px;" />'.PHP_EOL.'  <h1>{{ site_name }}</h1>'.PHP_EOL.'  <p>{{ site_slogan }}</p>'.PHP_EOL.'  <div style="clear:both;"></div>'.PHP_EOL.'</div>'.PHP_EOL.
                    '<script type="text/javascript">'.PHP_EOL.
                    '    $(document).ready(function(){'.PHP_EOL.
                    '        $(\'#div-section-banner\').prepend($(\'.__editing_widget_section_banner\'));'.PHP_EOL.
                    '    });'.PHP_EOL.
                    '</script>',
                    NULL),
                array('section_left', 'Left Section', '', '',
                    1, 1, 4, 1, '',
                    NULL),
                array('section_right', 'Right Section', '', '',
                    1, 1, 5, 1, '{{ widget_slug:sidebar }}<hr />{{ widget_slug:advertisement }}',
                    NULL),
                array('section_bottom', 'Bottom Section', '', '',
                    1, 1, 6, 1, '<div id="div-section-bottom" class="container well">' . PHP_EOL . '    <div class="col-md-4">' . PHP_EOL .'        <h3>{{ site_name }}</h3>' . PHP_EOL .'        <p>{{ site_slogan }}</p>' . PHP_EOL .'    </div>' . PHP_EOL .'    <div class="col-md-8">' . PHP_EOL .'        <h3>About Us</h3>' . PHP_EOL .'        <p>We are {{ site_name }}</p>' . PHP_EOL .'    </div>' . PHP_EOL .'    <div class="col-md-12">{{ site_footer }}</div>' . PHP_EOL . '</div>'. PHP_EOL .
                    '<script type="text/javascript">'.PHP_EOL.
                    '    $(document).ready(function(){'.PHP_EOL.
                    '        $(\'#div-section-bottom\').prepend($(\'.__editing_widget_section_bottom\'));'.PHP_EOL.
                    '    });'.PHP_EOL.
                    '</script>',
                    NULL),
                array('left_navigation', 'Left Navigation', '', 'main/widget_left_nav',
                    1, 1, 7, 0, NULL,
                    NULL),
                array('top_navigation', 'Top Navigation', '', 'main/widget_top_nav',
                    1, 1, 8, 0, NULL,
                    NULL),
                array('quicklink', 'Quicklinks', '', 'main/widget_quicklink',
                    1, 1, 9, 0, NULL,
                    NULL),
                array('top_navigation_default', 'Top Navigation Default', '', 'main/widget_top_nav_default',
                    1, 1, 10, 0, NULL,
                    NULL),
                array('quicklink_default', 'Quicklinks Default', '', 'main/widget_quicklink_default',
                    1, 1, 11, 0, NULL,
                    NULL),
                array('top_navigation_inverse', 'Top Navigation Inverse', '', 'main/widget_top_nav_inverse',
                    1, 1, 12, 0, NULL,
                    NULL),
                array('quicklink_inverse', 'Quicklinks Inverse', '', 'main/widget_quicklink_inverse',
                    1, 1, 13, 0, NULL,
                    NULL),
                array('top_navigation_default_fixed', 'Top Navigation Default Fixed', '', 'main/widget_top_nav_default_fixed',
                    1, 1, 14, 0, NULL,
                    NULL),
                array('quicklink_default_fixed', 'Quicklinks Default Fixed', '', 'main/widget_quicklink_default_fixed',
                    1, 1, 15, 0, NULL,
                    NULL),
                array('top_navigation_inverse_fixed', 'Top Navigation Inverse Fixed', '', 'main/widget_top_nav_inverse_fixed',
                    1, 1, 16, 0, NULL,
                    NULL),
                array('quicklink_inverse_fixed', 'Quicklinks Inverse Fixed', '', 'main/widget_quicklink_inverse_fixed',
                    1, 1, 17, 0, NULL,
                    NULL),
                array('top_navigation_default_static', 'Top Navigation Default Static', '', 'main/widget_top_nav_default_static',
                    1, 1, 18, 0, NULL,
                    NULL),
                array('quicklink_default_static', 'Quicklinks Default Static', '', 'main/widget_quicklink_default_static',
                    1, 1, 19, 0, NULL,
                    NULL),
                array('top_navigation_inverse_static', 'Top Navigation Inverse Static', '', 'main/widget_top_nav_inverse_static',
                    1, 1, 20, 0, NULL,
                    NULL),
                array('quicklink_inverse_static', 'Quicklinks Inverse Static', '', 'main/widget_quicklink_inverse_static',
                    1, 1, 21, 0, NULL,
                    NULL),
                array('login', 'Login', 'Visitor need to login for authentication', 'main/widget_login',
                    2, 1, 22, 0, '<form action="{{ site_url }}main/login" method="post" accept-charset="utf-8"><label>Identity</label><br><input type="text" name="identity" value=""><br><label>Password</label><br><input type="password" name="password" value=""><br><input type="submit" name="login" value="Log In"></form>',
                    'sidebar, user_widget'),
                array('logout', 'User Info', 'Logout', 'main/widget_logout',
                    3, 1, 23, 1, '{{ user_real_name }}<br /><a href="{{ site_url }}main/logout">{{ language:Logout }}</a>',
                    'sidebar, user_widget'),
                array('social_plugin', 'Share This Page !!', 'Addthis', 'main/widget_social_plugin',
                    1, 0, 24, 1, '<div class="addthis_sharing_toolbox"></div>'.PHP_EOL.'<!-- Go to www.addthis.com/dashboard to customize your tools -->'.PHP_EOL.'<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4ee44922521f8e39"></script>',
                    'sidebar'),
                array('google_search', 'Search', 'Search from google', '',
                    1, 0, 25, 1, '<!-- Google Custom Search Element -->'.PHP_EOL.'<div id="cse" style="width: 100%;">Loading</div>'.PHP_EOL.'<script src="http://www.google.com/jsapi" type="text/javascript"></script>'.PHP_EOL.'<script type="text/javascript">// <![CDATA['.PHP_EOL.'    google.load(\'search\', \'1\'),'.PHP_EOL.'    google.setOnLoadCallback(function(){var cse = new google.search.CustomSearchControl(),cse.draw(\'cse\'),}, true),'.PHP_EOL.'// ]]></script>',
                    'sidebar'),
                array('google_translate', 'Translate !!', '<p>The famous google translate</p>', '',
                    1, 0, 26, 1, '<!-- Google Translate Element -->'.PHP_EOL.'<div id="google_translate_element" style="display:block"></div>'.PHP_EOL.'<script>'.PHP_EOL.'function googleTranslateElementInit() {'.PHP_EOL.'  new google.translate.TranslateElement({pageLanguage: "af"}, "google_translate_element"),'.PHP_EOL.'};'.PHP_EOL.'</script>'.PHP_EOL.'<script src="http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>'.PHP_EOL.'',
                    'sidebar'),
                array('calendar', 'Calendar', 'Indonesian Calendar', '',
                    1, 0, 27, 1, '<!-------Do not change below this line------->'.PHP_EOL.'<div align="center" height="200px">'.PHP_EOL.'    <iframe align="center" src="http://www.calendarlabs.com/calendars/web-content/calendar.php?cid=1001&uid=162232623&c=22&l=en&cbg=C3D9FF&cfg=000000&hfg=000000&hfg1=000000&ct=1&cb=1&cbc=2275FF&cf=verdana&cp=bottom&sw=0&hp=t&ib=0&ibc=&i=" width="170" height="155" marginwidth=0 marginheight=0 frameborder=no scrolling=no allowtransparency=\'true\'>'.PHP_EOL.'    Loading...'.PHP_EOL.'    </iframe>'.PHP_EOL.'    <div align="center" style="width:140px;font-size:10px;color:#666;">'.PHP_EOL.'        Powered by <a  href="http://www.calendarlabs.com/" target="_blank" style="font-size:10px;text-decoration:none;color:#666;">Calendar</a> Labs'.PHP_EOL.'    </div>'.PHP_EOL.'</div>'.PHP_EOL.''.PHP_EOL.'<!-------Do not change above this line------->',
                    'sidebar'),
                array('google_map', 'Map', 'google map', '',
                    1, 0, 28, 1, '<!-- Google Maps Element Code -->'.PHP_EOL.'<iframe frameborder=0 marginwidth=0 marginheight=0 border=0 style="border:0;margin:0;width:100%;height:250px;" src="http://www.google.com/uds/modules/elements/mapselement/iframe.html?maptype=roadmap&element=true" scrolling="no" allowtransparency="true"></iframe>',
                    'sidebar'),
                array('donate_nocms', 'Donate No-CMS', 'No-CMS Donation', NULL,
                    1, 1, 29, 1, '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">'.PHP_EOL.'<input type="hidden" name="cmd" value="_s-xclick">'.PHP_EOL.'<input type="hidden" name="hosted_button_id" value="YDES6RTA9QJQL">'.PHP_EOL.'<input type="image" src="{{ base_url }}assets/nocms/images/donation.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" width="165px" height="auto" style="width:165px!important;" />'.PHP_EOL.'<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">'.PHP_EOL.'</form>',
                    'advertisement'),
                array('navigation_right_partial', 'top navigation right partial', 'Right Partial of Top Navigation Bar. Use this when you want to add something like facebook login form', NULL,
                    1, 1, 30, 1, NULL,
                    NULL),
                array('online_user', 'Who\'s online', '', 'main/widget_online_user',
                    1, 1, 31, 0, NULL,
                    NULL),
                array('fb_comment', 'Facebook Comments', '', '',
                    1, 1, 32, 1, '<div id="fb-root"></div>' . PHP_EOL . '<script>(function(d, s, id) {' . PHP_EOL . '  var js, fjs = d.getElementsByTagName(s)[0];' . PHP_EOL . '  if (d.getElementById(id)) return;' . PHP_EOL . '  js = d.createElement(s), js.id = id;' . PHP_EOL . '  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=278375612355057&version=v2.0";' . PHP_EOL . '  fjs.parentNode.insertBefore(js, fjs),' . PHP_EOL . '}(document, \'script\', \'facebook-jssdk\')),</script>' . PHP_EOL . '<div class="fb-comments" data-href="{{ site_url }}" data-numposts="5" data-colorscheme="light" width="100%"></div>',
                    NULL),
                array('user_button', 'User Button', '', 'main/widget_user_button',
                    1, 1, 33, 0, NULL,
                    NULL),
            ));

        // privilege
        $privileges = array(
                array('cms_install_module', 'Install Module', 'Install Module is a very critical privilege, it allow authorized user to Install a module to the CMS.<br />By Installing module, the database structure can be changed. There might be some additional navigation and privileges added.<br /><br />You\'d be better to give this authorization only authenticated and authorized user. (I suggest to make only admin have such a privilege)'.PHP_EOL.'&nbsp;', 4),
                array('cms_manage_access', 'Manage Access', 'Manage access'.PHP_EOL.'&nbsp;', 4),
            );
        // add CRUD privileges
        $verb_list = array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export');
        $entity_list = array('config', 'group', 'language', 'layout', 'navigation', 'privilege', 'quicklink', 'route', 'user', 'widget');
        foreach($verb_list as $verb){
            foreach($entity_list as $entity){
                $privileges[] = array(
                    $verb.'_main_'.$entity,
                    $verb.' '.$entity,
                    '',
                    4
                );
            }
        }
        $sql_list[] = $this->insert_privileges($privileges);

        // config
        if($this->is_subsite){
            $site_name = $this->subsite;
            $site_slogan = $this->subsite;
        }else{
            $site_name      = 'No-CMS';
            $site_slogan    = 'A Free CodeIgniter Based CMS Framework';
        }
        $config_data = array(
                array('site_name', $site_name, 'Site title'),
                array('site_slogan', $site_slogan, 'Site slogan'),
                array('site_logo', '{{ base_url }}assets/nocms/images/No-CMS-logo.png', 'Site logo'),
                array('site_favicon', '{{ base_url }}assets/nocms/images/No-CMS-favicon.png', 'Site favicon'),
                array('site_footer', '{{ site_name }} &copy; 2013', 'Site footer'),
                array('site_theme', 'neutral', 'Site theme'),
                array('site_layout', 'default', 'Site layout'),
                array('site_language', 'english', 'Site language'),
                array('site_background_image', '', 'Background Image'),
                array('site_background_color', '', 'Background Color'),
                array('site_background_position', '', 'Background Position'),
                array('site_background_size', '', 'Background Size'),
                array('site_background_repeat', '', 'Background Repeat'),
                array('site_background_origin', '', 'Background Origin'),
                array('site_background_clip', '', 'Background Clip'),
                array('site_background_attachment', '', 'Background Attachment'),
                array('site_background_blur', '', 'Background Blur'),
                array('site_show_benchmark', 'FALSE', 'Show Benchmark'),
                array('site_developer_addr', '127.0.0.1', 'Developer Address'),
                array('site_text_color', '', 'Text Color'),
                array('max_menu_depth', '5', 'Depth of menu recursive'),
                array('cms_email_reply_address', '{{ admin_email }}', 'Email address'),
                array('cms_email_reply_name', '{{ admin_real_name }}, {{ site_name }}', 'Email name'),
                array('cms_email_forgot_subject', 'Re-activate your account at {{ site_name }}', 'Email subject sent when user forgot his/her password'),
                array('cms_email_forgot_message', '<p>Dear, {{ user_real_name }}</p><p>Click <a href="{{ site_url }}main/forgot/{{ activation_code }}">{{ site_url }}main/forgot/{{ activation_code }}</a> to reactivate your account</p>', 'Email message sent when user forgot his/her password'),
                array('cms_email_signup_subject', 'Activate your account at {{ site_name }}', 'Email subject sent to activate user'),
                array('cms_email_signup_message', '<p>Dear, {{ user_real_name }}</p><p>Click <a href="{{ site_url }}main/activate/{{ activation_code }}">{{ site_url }}main/activate/{{ activation_code }}</a> to activate your account</p>', 'Email message sent to activate user'),
                array('cms_signup_activation', 'automatic', 'Send activation email to new member. Default : automatic, Alternatives : manual, by mail'),
                array('cms_email_useragent', 'Codeigniter', 'Default : CodeIgniter'),
                array('cms_email_protocol', 'smtp', 'Default : smtp, Alternatives : mail, sendmail, smtp'),
                array('cms_email_mailpath', '/usr/sbin/sendmail','Default : /usr/sbin/sendmail'),
                array('cms_email_smtp_host', 'ssl://smtp.googlemail.com','eg : ssl://smtp.googlemail.com'),
                array('cms_email_smtp_user', 'your_gmail_address@gmail.com','eg : your_gmail_address@gmail.com'),
                array('cms_email_smtp_pass', '','your password'),
                array('cms_email_smtp_port', '465','smtp port, default : 465'),
                array('cms_email_smtp_timeout', '30','default : 30'),
                array('cms_email_wordwrap', 'TRUE', 'Enable word-wrap. Default : true, Alternatives : true, false'),
                array('cms_email_wrapchars', '76', 'Character count to wrap at.'),
                array('cms_email_mailtype', 'html', 'Type of mail. If you send HTML email you must send it as a complete web page. Make sure you do not have any relative links or relative image paths otherwise they will not work. Default : html, Alternatives : html, text'),
                array('cms_email_charset', 'utf-8', 'Character set (utf-8, iso-8859-1, etc.).'),
                array('cms_email_validate', 'FALSE', 'Whether to validate the email address. Default: true, Alternatives : true, false'),
                array('cms_email_priority', '3', '1, 2, 3, 4, 5  Email Priority. 1 = highest. 5 = lowest. 3 = normal.'),
                array('cms_email_bcc_batch_mode', 'FALSE', 'Enable BCC Batch Mode. Default: false, Alternatives: true'),
                array('cms_email_bcc_batch_size', '200', 'Number of emails in each BCC batch.'),
                array('cms_google_analytic_property_id', '', 'Google analytics property ID (e.g: UA-30285787-1). Leave blank if you don\'t want to use it.'),
                array('cms_add_subsite_on_register', 'FALSE', 'Automatically create subsite on register'),
                array('cms_subsite_use_subdomain','FALSE','Automatically use subdomain'),
                array('cms_subsite_home_content','{{ widget_name:blog_content }}','Default subsite homepage content'),
                array('cms_subsite_modules','blog,contact_us,static_accessories','Comma Separated Format, Modules that is going to be installed by default for new Subsite'),
                array('cms_subsite_configs','{}','JSON Format, Configuration value for new subsite'),
                array('cms_internet_connectivity','UNKNOWN','Is the server connected to the internet?'),
                array('meta_keyword', '', 'Keyword for SEO'),
                array('meta_description', '', 'Description for SEO'),
                array('meta_twitter_card', 'summary', 'Twitter Card for SEO'),
                array('meta_author', '', 'Author for SEO'),
                array('meta_image', '', 'Image for SEO'),
                array('meta_type', 'article', 'Type for SEO'),
                array('meta_fb_admin', '', 'FB Admin for SEO'),
                array('meta_twitter_publisher_handler', '', 'Twitter publisher handler for SEO'),
                array('meta_twitter_author_handler', '', 'Twitter author handler for SEO'),
            );
        for($i=0; $i<count($config_data); $i++){
            foreach($this->configs as $key=>$val){
                if($key == $config_data[$i][0]){
                    $config_data[$i][1] = $val;
                    break;
                }
            }
        }
        $sql_list[] = $this->insert_configs($config_data);

        // language
        $this->insert_languages(array(
                array('Afrikaans','afrikaans'),
                array('Arabic','arabic'),
                array('Bengali','bengali'),
                array('Bulgarian','bulgarian'),
                array('Catalan','catalan'),
                array('Chinese','chinese'),
                array('Czech','czech'),
                array('Danish','danish'),
                array('Dutch','dutch'),
                array('English','english'),
                array('French','french'),
                array('German','german'),
                array('Greek','greek'),
                array('Hindi','hindi'),
                array('Hungarian','hungarian'),
                array('Indonesian','indonesian'),
                array('Italian','italian'),
                array('Japanese','japanese'),
                array('Korean','korean'),
                array('Mongolian','mongolian'),
                array('Norwegian','norwegian'),
                array('Persian','persian'),
                array('Polish','polish'),
                array('Portuguese (Brazil)','pt-br.portuguese'),
                array('Portuguese','pt-pt.portuguese'),
                array('Romanian','romanian'),
                array('Russian','russian'),
                array('Slovak','slovak'),
                array('Spanish','spanish'),
                array('Thai','thai'),
                array('Turkish','turkish'),
                array('Ukrainian','ukrainian'),
                array('Vietnamese','vietnamese'),
            ));

        $layout_list = array('default', 'default-one-column', 'default-two-column', 'default-three-column', 'slide', 'slide-one-column', 'slide-two-column', 'slide-three-column', 'minimal');
        $layout_data = array();
        foreach($layout_list as $layout){
            $layout_data[] = array($layout, file_get_contents(FCPATH.'modules/installer/layouts/'.$layout.'.html'));
        }
        $this->insert_layouts($layout_data);


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
            log_message('debug', 'start create all table');
            $create_table_sql_list = $this->create_all_table();
            log_message('debug', 'finish create all table, start insert data');
            $insert_sql_list = $this->insert_all_data($config);
            log_message('debug', 'finish insert all data');
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

        // get file contents (either from cache or directly)
        if(!array_key_exists($file_name, $this->__config_file)){
            $this->__config_file[$file_name] = file_get_contents($file_name);
        }
        $str = $this->__config_file[$file_name];
        $str = preg_replace($pattern, $replacement, $str);
        // write it to cache
        $this->__config_file[$file_name] = $str;
    }

    protected function append_config($file_name, $key, $value, $key_prefix = '$config[',
    $key_suffix = ']', $value_prefix = "'", $value_suffix = "';",  $equal_sign = '='){
        if(!array_key_exists($file_name, $this->__config_file)){
            $this->__config_file[$file_name] = file_get_contents($file_name);
        }
        $str = $this->__config_file[$file_name];
        $str.= $key_prefix.$key.$key_suffix.' '.$equal_sign.' '.$value_prefix.$value.$value_suffix;
        // write it to cache
        $this->__config_file[$file_name] = $str;
    }

    protected function flush_change_config(){
        foreach($this->__config_file as $file_name => $content){
            @chmod($file_name, 0777);
            @file_put_contents($file_name, $content);
            @chmod($file_name, 0755);
        }
    }

    public function replace_tag($file_name, $tag, $value){
        $content = file_get_contents($file_name);
        $content = str_replace('{{ '.$tag.' }}', $value, $content);
        file_put_contents($file_name, $content);
    }

    public function complete_config_file_name($file){
        if($this->is_subsite){
            $file = 'site-'.$this->subsite.'/'.$file;
        }else{
            $file = 'main/'.$file;
        }
        return $file;
    }

    public function build_configuration($config = array()){
        if(!$this->is_subsite){
            // create hostname.php
            $hostname = $_SERVER['HTTP_HOST'];
            $content   = '<?php'.PHP_EOL;
            $content  .= '$hostname = "'.$hostname.'";'.PHP_EOL;
            @file_put_contents(FCPATH.'/hostname.php', $content);
        }
        // copy everything from /application/config/first-time into /application/config/ or /application/config/site-subsite
        if($this->is_subsite){
            mkdir(APPPATH.'config/site-'.$this->subsite);
            $file_list = array('cms_config.php', 'config.php', 'hybridauthlib.php', 'routes.php', 'index.html');
        }else{
            mkdir(APPPATH.'config/main');
            $file_list = scandir(APPPATH.'config/first-time', 1);
        }

        foreach($file_list as $file){
            if(!is_dir(APPPATH.'config/first-time/'.$file)){
                copy(APPPATH.'config/first-time/'.$file, APPPATH.'config/'.$this->complete_config_file_name($file));
            }
        }

        if(!$this->is_subsite){

            // ckeditor config
            copy(APPPATH.'config/first-time/third_party_config/ckeditor_config.js',
                FCPATH.'assets/grocery_crud/texteditor/ckeditor/config.js');
            $this->replace_tag(FCPATH.'assets/grocery_crud/texteditor/ckeditor/config.js', 'BASE_URL', base_url());

            // kcfinder config
            copy(APPPATH.'config/first-time/third_party_config/kcfinder_config.php',
                FCPATH.'assets/kcfinder/config.php');
            $this->replace_tag(FCPATH.'assets/kcfinder/config.php', 'BASE_URL', base_url());
            $this->replace_tag(FCPATH.'assets/kcfinder/config.php', 'FCPATH', addslashes(FCPATH));

            // database configuration
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
        }

        // cms_config
        $file_name = APPPATH.'config/'.$this->complete_config_file_name('cms_config.php');
        $key_prefix = '$config[\'';
        $key_suffix = "']";
        $value_prefix = "'";
        $value_suffix = "';";
        $equal_sign = '=';

        $this->change_config($file_name, "__cms_table_prefix", $this->db_table_prefix, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $this->change_config($file_name, "__cms_version", $this->VERSION, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $this->change_config($file_name,"__cms_chipper", md5(md5(rand().time())), $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);

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
        $this->change_config($file_name, "sess_cookie_name", $encryption_key, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);

        $table_name = 'ci_sessions';
        if(!trim($this->db_table_prefix) == ''){
            $table_name = $this->db_table_prefix.'_'.$table_name;
        }
        $this->change_config($file_name, "sess_table_name", $table_name, $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
        $value_prefix = "";
        $value_suffix = ";";
        $compress_output = $this->gzip_compression?'TRUE':'FALSE';
        $this->change_config($file_name, "minify_output", 'TRUE', $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);
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
        $this->change_config($file_name, "404_override", 'not_found', $key_prefix, $key_suffix, $value_prefix, $value_suffix, $equal_sign);

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

        // flush all changes
        $this->flush_change_config();

        // htaccess
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
        }

        // extended_route_path
        $file_name = APPPATH.'config/'.$this->complete_config_file_name('routes.php');
        if($this->is_subsite){
            $include_extended_route = 'include(APPPATH.\'config/site-'.$this->subsite.'/extended_routes.php\');';
        }else{
            $include_extended_route = 'include(APPPATH.\'config/main/extended_routes.php\');';
        }
        file_put_contents($file_name, file_get_contents($file_name).PHP_EOL.PHP_EOL.$include_extended_route);
    }

    public function install_modules(){
        if(count($this->modules) == 0){
            $modules = array('blog','contact_us','static_accessories');
        }else{
            $modules = $this->modules;
        }

        // determine user table name
        $user_table_name = 'main_user';
        if($this->is_subsite){
            include(APPPATH.'config/main/cms_config.php');
            $prefix = $config['__cms_table_prefix'];
        }else{
            $prefix = $this->db_table_prefix;
        }
        if(!trim($prefix) == ''){
            $user_table_name = $prefix.'_'.$user_table_name;
        }
        // get encrypted password as bypass
        $bypass = '';
        $query = $this->db->select('password')
            ->from($user_table_name)
            ->where('user_id', 1)
            ->get();
        if($query->num_rows()>0){
            $row = $query->row();
            $bypass = $row->password;
        }

        // call the controller
        if($bypass != ''){
            if($this->is_subsite){
                // for subsite, we should override table prefix etc
                define('CMS_OVERRIDDEN_SUBSITE', $this->subsite);
            }
            $executed_controllers = array();
            foreach($modules as $module){
                log_message('debug', 'start installing '.$module);
                if(file_exists(FCPATH.'modules/'.$module.'/description.txt')){
                    $json         = file_get_contents(FCPATH.'modules/'.$module.'/description.txt');
                    $module_info  = @json_decode($json, true);
                    $module_info  = $module_info === NULL? array() : $module_info;
                    if(is_array($module_info) && array_key_exists('activate', $module_info)){
                        $url = trim($module_info['activate'],'/');
                        $response = '';
                        // subsite just run the module, it's faster
                        $url_part = explode('/', $url);
                        $controller_name = ucfirst($url_part[0]);
                        $new_controller_name = $controller_name.'_'.md5($module);
                        $controller_file = FCPATH.'modules/'.$module.'/controllers/'.$controller_name.'.php';
                        $new_controller_file = FCPATH.'modules/'.$module.'/controllers/'.$new_controller_name.'.php';
                        if(!file_exists($new_controller_file) || date('YmdHis',filemtime($new_controller_file)) <= date('YmdHis',filemtime($controller_file))){
                            $content = file_get_contents($controller_file);
                            $content = preg_replace('/class( *)'.$controller_name.'/', 'class '.$new_controller_name, $content);
                            file_put_contents($new_controller_file, $content);
                        }
                        $url_part[0] = strtolower($new_controller_name);
                        $new_url = implode('/', $url_part);
                        log_message('debug', 'start running controller');
                        $response = Modules::run($module.'/'.$new_url, $bypass);
                        log_message('debug', 'finish running controller');
                        // look if it is succeed or failed
                        $success = FALSE;
                        if($response != ''){
                            $json = @json_decode($response, TRUE);
                            if(array_key_exists('success', $json) && $json['success']){
                                $success = TRUE;
                            }
                        }
                        if(!$success){
                            log_message('debug', 'Invalid response when installing module.'.PHP_EOL.
                                '    URL : '.$module.'/'.$url.PHP_EOL.
                                '    response : '.print_r($response, TRUE));
                        }
                        // keep it clean for non-subsite
                        if(!$this->is_subsite){
                            unlink($new_controller_file);
                        }
                    }
                }
                log_message('debug', 'finish intalling '.$module);
            }
            if($this->is_subsite){
                // put the overridden subsite back to normal
                define('CMS_RESET_OVERRIDDEN_SUBSITE', TRUE);
            }
            return TRUE;
        }
        return FALSE;
    }

}
?>
