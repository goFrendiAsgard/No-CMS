<?php if (!defined('BASEPATH')) { exit('No direct script access allowed');}

/**
 * Base Model of No-CMS.
 *
 * @author gofrendi
 */
class CMS_Model extends CI_Model
{
    // going to be deprecated
    public $PRIV_EVERYONE = PRIV_EVERYONE;
    public $PRIV_NOT_AUTHENTICATED = PRIV_NOT_AUTHENTICATED;
    public $PRIV_AUTHENTICATED = PRIV_AUTHENTICATED;
    public $PRIV_AUTHORIZED = PRIV_AUTHORIZED;
    public $PRIV_EXCLUSIVE_AUTHORIZED = PRIV_EXCLUSIVE_AUTHORIZED;

    // Types
    protected $TYPE_INT_UNSIGNED_AUTO_INCREMENT = array('type' => 'INT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true);
    protected $TYPE_INT_UNSIGNED_NOTNULL = array('type' => 'INT', 'constraint' => 20, 'unsigned' => true, 'null' => false);
    protected $TYPE_INT_SIGNED_NOTNULL = array('type' => 'INT', 'constraint' => 20, 'null' => false);
    protected $TYPE_INT_UNSIGNED_NULL = array('type' => 'INT', 'constraint' => 20, 'unsigned' => true, 'null' => true);
    protected $TYPE_INT_SIGNED_NULL = array('type' => 'INT', 'constraint' => 20, 'null' => true);
    protected $TYPE_DATETIME_NOTNULL = array('type' => 'TIMESTAMP', 'null' => false);
    protected $TYPE_DATE_NOTNULL = array('type' => 'DATE', 'null' => false);
    protected $TYPE_DATETIME_NULL = array('type' => 'TIMESTAMP', 'null' => true);
    protected $TYPE_DATE_NULL = array('type' => 'DATE', 'null' => true);
    protected $TYPE_FLOAT_NOTNULL = array('type' => 'FLOAT', 'null' => false);
    protected $TYPE_DOUBLE_NOTNULL = array('type' => 'DOUBLE', 'null' => false);
    protected $TYPE_FLOAT_NULL = array('type' => 'FLOAT', 'null' => true);
    protected $TYPE_DOUBLE_NULL = array('type' => 'DOUBLE', 'null' => true);
    protected $TYPE_TEXT = array('type' => 'TEXT', 'null' => true);
    protected $TYPE_VARCHAR_5_NOTNULL = array('type' => 'VARCHAR', 'constraint' => 5, 'null' => false);
    protected $TYPE_VARCHAR_10_NOTNULL = array('type' => 'VARCHAR', 'constraint' => 10, 'null' => false);
    protected $TYPE_VARCHAR_20_NOTNULL = array('type' => 'VARCHAR', 'constraint' => 20, 'null' => false);
    protected $TYPE_VARCHAR_50_NOTNULL = array('type' => 'VARCHAR', 'constraint' => 50, 'null' => false);
    protected $TYPE_VARCHAR_100_NOTNULL = array('type' => 'VARCHAR', 'constraint' => 100, 'null' => false);
    protected $TYPE_VARCHAR_150_NOTNULL = array('type' => 'VARCHAR', 'constraint' => 150, 'null' => false);
    protected $TYPE_VARCHAR_200_NOTNULL = array('type' => 'VARCHAR', 'constraint' => 200, 'null' => false);
    protected $TYPE_VARCHAR_250_NOTNULL = array('type' => 'VARCHAR', 'constraint' => 250, 'null' => false);
    protected $TYPE_VARCHAR_5_NULL = array('type' => 'VARCHAR', 'constraint' => 5, 'null' => true);
    protected $TYPE_VARCHAR_10_NULL = array('type' => 'VARCHAR', 'constraint' => 10, 'null' => true);
    protected $TYPE_VARCHAR_20_NULL = array('type' => 'VARCHAR', 'constraint' => 20, 'null' => true);
    protected $TYPE_VARCHAR_50_NULL = array('type' => 'VARCHAR', 'constraint' => 50, 'null' => true);
    protected $TYPE_VARCHAR_100_NULL = array('type' => 'VARCHAR', 'constraint' => 100, 'null' => true);
    protected $TYPE_VARCHAR_150_NULL = array('type' => 'VARCHAR', 'constraint' => 150, 'null' => true);
    protected $TYPE_VARCHAR_200_NULL = array('type' => 'VARCHAR', 'constraint' => 200, 'null' => true);
    protected $TYPE_VARCHAR_250_NULL = array('type' => 'VARCHAR', 'constraint' => 250, 'null' => true);

    public $__controller_module_path = null;
    protected static $__cms_model_properties;

    // don't update last active tolerance if less than 300 seconds
    private $last_active_tolerance = 300;

    public function cms_list_fields($table_name)
    {
        if ($this->db instanceof CI_DB_pdo_sqlite_driver) {
            $result = $this->db->get($table_name);
            $row_array = $result->row_array();
            $field_list = array();
            foreach ($row_array as $key => $value) {
                $field_list[] = $key;
            }

            return $field_list;
        } else {
            return $this->db->list_fields($table_name);
        }
    }

    public function __construct()
    {
        parent::__construct();

        // load helpers and libraries
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->helper('form');
        $this->load->helper('string');
        $this->load->library('user_agent');
        $this->load->driver('session');
        $this->load->helper('cms_helper');
        $this->load->library('form_validation');
        $this->load->library('unit_test');

        // for the first-time installation, it might not load main configuration even if the main configuration
        // is already exists. Thus we need to explicitly code it
        if(CMS_SUBSITE == '' && ENVIRONMENT == 'first-time' && file_exists(APPPATH.'config/main/database.php')){
            unset($db);
            include(APPPATH.'config/main/database.php');
            $this->load->database($db['default']);
        }else{
            $this->load->database();
        }

        // accessing file is faster than accessing database
        // but I think accessing variable is faster than both of them

        if (self::$__cms_model_properties == null || (defined('CMS_OVERRIDDEN_SUBSITE') && !defined('CMS_RESET_OVERRIDDEN_SUBSITE'))) {
            self::$__cms_model_properties = array();
        }
        $default_properties = array(
                'session' => array(),
                'language_dictionary' => array(),
                'config' => array(),
                'module_name' => array(),
                'module_path' => array(),
                'module_version' => array(),
                'navigation' => array(),        // cache raw query
                'quicklink' => array(),         // cache already built quicklink
                'widget' => array(),            // cache raw query
                'layout' => array(),            // cache as associative array layout_name => template
                'layout_id' => array(),         // cache as associative array layout_name => layout_id
                'super_admin' => null,
                'group_name' => array(),
                'group_id' => array(),
                'properties' => array(),
                'route' => array(),
                'user_language' => null,
                'user_theme' => null,
                'is_super_admin' => false,
                'is_config_cached' => false,
                'is_module_name_cached' => false,
                'is_module_path_cached' => false,
                'is_module_version_cached' => false,
                'is_user_last_active_extended' => false,
                'is_navigation_cached' => false,
                'is_quicklink_cached' => false,
                'is_widget_cached' => false,
                'is_language_dictionary_cached' => false,
                'is_group_name_cached' => false,
                'is_group_id_cached' => false,
                'is_super_admin_cached' => false,
                'is_route_cached' => false,
                'is_user_language_cached' => false,
                'is_user_theme_cached' => false,
                'is_layout_cached' => false,
                'is_private_themes_cached' => false,
                'is_private_modules_cached' => false,
                'profile_pictures' => array(),
                'private_themes' => array(),
                'private_modules' => array(),
            );
        foreach ($default_properties as $key => $val) {
            if (!array_key_exists($key, self::$__cms_model_properties)) {
                self::$__cms_model_properties[$key] = $val;
            }
        }

        // get themes and modules for this subsite
        if( (!self::$__cms_model_properties['is_private_modules_cached'] || !self::$__cms_model_properties['is_private_themes_cached']) &&  CMS_SUBSITE != '' && T_SUBSITE != ''){
            // get subsite complete table name
            $t_subsite = T_SUBSITE;
            $query = $this->db->select('modules, themes')
                ->from($t_subsite)
                ->where('name', CMS_SUBSITE)
                ->get();
            $row = $query->row();
            // get private modules and themes
            $modules = explode(',', $row->modules);
            $themes = explode(',', $row->themes);
            // get rid of spaces
            for($i=0; $i<count($modules); $i++){
                $modules[$i] = trim($modules[$i]);
            }
            for($i=0; $i<count($themes); $i++){
                $themes[$i] = trim($themes[$i]);
            }
            self::$__cms_model_properties['private_modules'] = $modules;
            self::$__cms_model_properties['private_themes'] = $themes;

            // cached
            self::$__cms_model_properties['is_private_modules_cached'] = TRUE;
            self::$__cms_model_properties['is_private_themes_cached'] = TRUE;
        }

        // cache super_admin
        if (self::$__cms_model_properties['super_admin'] === null) {
            $query = $this->db->select('user_name, real_name, email, language, theme, last_active')
                    ->from($this->cms_user_table_name())
                    ->where('user_id', 1)
                    ->get();
            $super_admin_array = $query->row_array();
            self::$__cms_model_properties['super_admin'] = $super_admin_array;
            // also cache user language and user theme if current user is super_admin.
            if($this->cms_user_id() == 1){
                if (self::$__cms_model_properties['is_user_language_cached'] === false || self::$__cms_model_properties['is_user_theme_cached'] === false){
                    self::$__cms_model_properties['user_language'] = $super_admin_array['language'];
                    self::$__cms_model_properties['user_theme'] = $super_admin_array['theme'];
                    self::$__cms_model_properties['is_user_language_cached'] = true;
                    self::$__cms_model_properties['is_user_theme_cached'] = true;

                    // last active extended
                    if($super_admin_array['last_active'] >= microtime(true) - $this->last_active_tolerance){
                        self::$__cms_model_properties['is_user_last_active_extended'] = true;
                    }

                }
            }
        }

        // if user is login, then cache theme and language (if user is also super admin this won't run)
        if($this->cms_user_id() != '' && $this->cms_user_id() > 0){
            if (self::$__cms_model_properties['is_user_language_cached'] === false || self::$__cms_model_properties['is_user_theme_cached'] === false) {
                $query = $this->db->select('language, theme, last_active')
                    ->from($this->cms_user_table_name())
                    ->where('user_id', $this->cms_user_id())
                    ->get();
                if($query->num_rows() > 0){
                    $row = $query->row();
                    self::$__cms_model_properties['user_language'] = $row->language;
                    self::$__cms_model_properties['user_theme'] = $row->theme;

                    // last active extended
                    if($row->last_active >= microtime(true) - $this->last_active_tolerance){
                        self::$__cms_model_properties['is_user_last_active_extended'] = true;
                    }
                }
                self::$__cms_model_properties['is_user_language_cached'] = true;
                self::$__cms_model_properties['is_user_theme_cached'] = true;
            }
        }

        // KCFINDER's stuffs =========
        if (!$this->input->is_ajax_request()) {
            // clear old secret files
            $clean_timer_file = APPPATH.'config/tmp/_time.php';
            if (!file_exists($clean_timer_file)) {
                $content = '<?php if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');'.PHP_EOL;
                $content .= '$last = '.time().';';
                file_put_contents($clean_timer_file, $content);
            }
            include $clean_timer_file;
            $last == isset($last) ? $last : 0;
            if (time() - $last >= 300) {
                $files = scandir(APPPATH.'config/tmp');
                foreach ($files as $file) {
                    if (in_array($file, array('.', '..'))) {
                        continue;
                    } elseif (substr($file, 0, 7) == '_secret' || substr($file, 0, 6) == '_token') {
                        $file = APPPATH.'config/tmp/'.$file;
                        if (file_exists($file) && filemtime($file) < strtotime('-1 hour')) {
                            @unlink($file);
                        }
                    }
                }
                $content = '<?php if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');'.PHP_EOL;
                $content .= '$last = '.time().';';
                file_put_contents($clean_timer_file, $content);
            }

            // create secret data and save
            $secret_data = array(
                    '__cms_base_url' => base_url(),
                    '__cms_subsite' => CMS_SUBSITE,
                    '__cms_user_id' => $this->cms_user_id(),
                );
            $secret_data = json_encode($secret_data);
            // set cookie
            if (!isset($_COOKIE['__secret_code'])) {
                $secret_code = $this->cms_random_string(20);
                setcookie('__secret_code', $secret_code, time() + 300, '/');
            } else {
                $secret_code = $_COOKIE['__secret_code'];
            }
            $secret_file = APPPATH.'config/tmp/_secret_'.$secret_code.'.php';
            // only rewrite secret if necessary
            $rewrite_secret_file = true;
            if (file_exists($secret_file)) {
                include $secret_file;
                $secret = isset($secret) ? $secret : '';
                if ($secret == $secret_data) {
                    $rewrite_secret_file = false;
                }
            }
            if ($rewrite_secret_file) {
                $content = '<?php if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');'.PHP_EOL;
                $content .= '$secret = \''.$secret_data.'\';';
                file_put_contents($secret_file, $content);
            }
        }

        // END OF KCFINDER's stuffs ====

        // extend user last active status
        $this->__cms_extend_user_last_active($this->cms_user_id());
    }

    public function cms_invalidate_cache(){
        self::$__cms_model_properties['is_super_admin'] = false;
        self::$__cms_model_properties['is_config_cached'] = false;
        self::$__cms_model_properties['is_module_name_cached'] = false;
        self::$__cms_model_properties['is_module_path_cached'] = false;
        self::$__cms_model_properties['is_module_version_cached'] = false;
        self::$__cms_model_properties['is_user_last_active_extended'] = false;
        self::$__cms_model_properties['is_navigation_cached'] = false;
        self::$__cms_model_properties['is_quicklink_cached'] = false;
        self::$__cms_model_properties['is_widget_cached'] = false;
        self::$__cms_model_properties['is_language_dictionary_cached'] = false;
        self::$__cms_model_properties['is_group_name_cached'] = false;
        self::$__cms_model_properties['is_group_id_cached'] = false;
        self::$__cms_model_properties['is_super_admin_cached'] = false;
        self::$__cms_model_properties['is_route_cached'] = false;
        self::$__cms_model_properties['is_user_language_cached'] = false;
        self::$__cms_model_properties['is_user_theme_cached'] = false;
        self::$__cms_model_properties['is_layout_cached'] = false;
        self::$__cms_model_properties['is_private_themes_cached'] = false;
        self::$__cms_model_properties['is_private_modules_cached'] = false;
    }

    public function cms_get_model_static_properties($key){
        return self::$__cms_model_properties[$key];
    }

    public function cms_get_origin_uri_string(){
        if(isset($_GET['from'])){
            $origin_uri_string = $_GET['from'];
        }else{
            $origin_uri_string = $this->uri->uri_string();
        }
        // if origin_uri_sting == '', use default controller instead
        if($origin_uri_string == ''){
            $origin_uri_string = $this->cms_get_default_controller();
        }
        return $origin_uri_string;
    }

    /*
    * @ usage $this->t('purchase')
    */
    public function t($table_name, $alias = null)
    {
        $return = $this->cms_complete_table_name($table_name);
        if ($alias !== null) {
            $return .= ' as '.$alias;
        }

        return $return;
    }

    public function tmain($table_name, $alias = null)
    {
        $return = $this->cms_complete_main_site_table_name($table_name);
        if ($alias !== null) {
            $return .= ' as '.$alias;
        }

        return $return;
    }

    public function n($navigation_name)
    {
        return $this->cms_complete_navigation_name($navigation_name);
    }

    public function  cms_get_user_theme(){
        return self::$__cms_model_properties['user_theme'];
    }

    public function cms_get_user_language(){
        return self::$__cms_model_properties['user_language'];
    }

    // This is used to define new hook. So whenever you want to make a new hook,
    // call this function.
    // This function will evaluate hooks.php
    public function cms_call_hook($hook_name, $parameters=array()){
        $return = array();
        $hook_level = array('_9', '_8', '_7', '_6', '_5', '', '_4', '_3', '_2', '_1', '_0');
        $module_list = $this->cms_get_module_list();
        foreach($hook_level as $level){
            foreach($module_list as $module){
                $active = $module['active'];
                if($active){
                    $module_path = $module['module_path'];
                    $module_name = $module['module_name'];
                    $function_prefix = 'hook_'.str_ireplace(array('.', '-', ' '), '_', $module_name);
                    if(file_exists(FCPATH.'modules/'.$module_path.'/hooks.php')){
                        require_once(FCPATH.'modules/'.$module_path.'/hooks.php');
                        $function_name = $function_prefix . '_' . $hook_name . $level;
                        if(function_exists($function_name)){
                            $return[] = call_user_func_array($function_name, $parameters);
                        }
                    }
                }
            }
        }
        return $return;
    }

    public function cms_labeled_multi_select($name, $label, $value=array(), $options = array(), $help_block='', $attributes = array(), $label_width=4){
        // fill attributes
        if(!array_key_exists('id', $attributes)){
            $attributes['id'] = $name;
        }
        // build input
        $html = '<select multiple="multiple" id="'.$name.'" name="'.$name.'"'.
            $this->__cms_unpack_attributes($attributes).'>';
        foreach($options as $option_val => $option_caption){
            $selected = in_array($option_val, $value)?' selected':'';
            $html .= '<option value="'.$option_val.'"'.$selected.'>'.$option_caption.'</option>';
        }
        $html .= '</select>';
        return $this->cms_labeled_input($name, $label, $help_block, $html, $label_width);
    }

    public function cms_labeled_select($name, $label, $value=NULL, $options = array(), $help_block='', $attributes = array(), $label_width=4){
        // fill attributes
        if(!array_key_exists('id', $attributes)){
            $attributes['id'] = $name;
        }
        // build input
        $html = '<select name="'.$name.'"'. $this->__cms_unpack_attributes($attributes).'>';
        foreach($options as $option_val => $option_caption){
            $selected = $option_val == $value?' selected':'';
            $html .= '<option value="'.$option_val.'"'.$selected.'>'.$option_caption.'</option>';
        }
        $html .= '</select>';
        return $this->cms_labeled_input($name, $label, $help_block, $html, $label_width);
    }

    public function cms_labeled_text_area($name, $label, $value=NULL, $help_block='', $attributes = array(), $label_width=4){
        // fill attributes
        if(!array_key_exists('id', $attributes)){
            $attributes['id'] = $name;
        }
        // build html
        $html = '<textarea name="'.$name.'" class="'.$input_class.'"'.
            $this->__cms_unpack_attributes($attributes).'>'.
            $value.'</textarea>';
        return $this->cms_labeled_input($name, $label, $help_block, $html, $label_width);
    }

    public function cms_labeled_text_input($name, $label, $value=NULL, $help_block='', $attributes = array(), $label_width=4){
        // fill attributes
        if(!array_key_exists('id', $attributes)){
            $attributes['id'] = $name;
        }
        // build html
        $html = '<input type="text" name="'.$name.'" value="'.$value.'"'.
            $this->__cms_unpack_attributes($attributes).' />';
        return $this->cms_labeled_input($name, $label, $help_block, $html, $label_width);
    }

    private function __cms_unpack_attributes($attributes){
        $result = '';
        foreach($attributes as $key=>$val){
            $result .= ' '.$key.'="'.$val.'"';
        }
        return $result;
    }

    public function cms_labeled_input($name, $label, $help_block='', $html=NULL, $label_width=4){
        // determine input width
        $input_width = 12 - $label_width;
        // return the html
        return '<div id="div-'.$name.'" class="form-group">
            <label class="control-label col-md-'.$label_width.'" for="'.$name.'">'.$this->cms_lang($label).'</label>
            <div class="controls col-md-'.$input_width.'">
                '.$html.'
                <p class="help-block">'.$help_block.'</p>
            </div>
        </div>';
    }

    protected function _cms_build_where($where = NULL, $values = array(), $mode = 'and', $like = FALSE, $side = 'both'){
        if($where === NULL){
            return NULL;
        }
        $mode = strtolower($mode);

        // if where and values are not array, change them into array
        if(!is_array($where)){
            $where = array($where => NULL);
        }
        if(!is_array($values)){
            $values = array($values);
        }
        // find out if where is associative array
        $is_associative_array = FALSE;
        $i = 0;
        foreach($where as $key=>$value){
            if($key != $i.''){
                $is_associative_array = TRUE;
                break;
            }
            $i++;
        }
        // turn into associative array
        if(!$is_associative_array){
            $new_where = array();
            foreach($where as $key){
                $new_where[$key] = NULL;
            }
            $where = $new_where;
        }
        // adjust where values
        if(count($values) != 0){
            $i = 0;
            foreach($where as $key=>$original_value){
                if($original_value === NULL){
                    $where[$key] = $values[$i];
                }
                $i++;
                // reset values if necessary
                if($i == count($values)){
                    $i = 0;
                }
            }
        }
        // actual code
        foreach($where as $field => $value){
            if($mode == 'and'){
                if($like){
                    $this->db->like($field, $value, $side);
                }else{
                    $this->db->where($field, $value);
                }
            }else{
                if($like){
                    $this->db->or_like($field, $value, $side);
                }else{
                    $this->db->or_where($field, $value);
                }
            }
        }
    }

    public function cms_record_exists($table_name, $where = NULL, $values = array(), $mode = 'and', $like = FALSE, $side = 'both')
    {
        $this->_cms_build_where($where, $values, $mode, $like, $side);
        // return TRUE if record exist
        return $this->db->count_all_results($table_name) > 0;
    }

    public function cms_get_record($table_name,  $where = NULL, $values = array(), $mode = 'and', $like = FALSE, $side = 'both')
    {
        $this->_cms_build_where($where, $values, $mode, $like, $side);
        $query = $this->db->get($table_name);
        // get row
        return $query->row();
    }

    public function cms_get_record_list($table_name,  $where = NULL, $values = array(), $mode = 'and', $like = FALSE, $side = 'both'){
        $this->_cms_build_where($where, $values, $mode, $like, $side);
        $query = $this->db->get($table_name);
        // return the result
        return $query->result();
    }

    public function __destruct()
    {
        @$this->session->unset_userdata('cms_dynamic_widget');
    }

    public function cms_get_super_admin()
    {
        return self::$__cms_model_properties['super_admin'];
    }

    /**
     * @author go frendi
     *
     * @param string $table_name
     *
     * @return string
     * @desc   return good table name
     */
    public function cms_complete_table_name($table_name, $module_name = null)
    {
        $module_path = $this->cms_module_path($module_name);
        if ($module_path == 'main' || $module_path == '') {
            return cms_table_name($table_name);
        } else {
            if (file_exists(FCPATH.'modules/'.$module_path.'/cms_helper.php')) {
                $this->load->helper($module_path.'/cms');
                if (function_exists('cms_complete_table_name')) {
                    return cms_complete_table_name($table_name);
                }
            }

            return cms_module_table_name($module_path, $table_name);
        }
    }

    public function cms_complete_main_site_table_name($table_name, $module_name = null)
    {
        include APPPATH.'config/main/cms_config.php';
        $table_prefix = $config['__cms_table_prefix'];

        $module_path = $this->cms_module_path($module_name);
        if ($module_path != 'main' && $module_path != '') {
            include FCPATH.'modules/'.$module_path.'/config/module_config.php';
            if(array_key_exists('module_table_prefix', $config)){
                $module_table_prefix = $config['module_table_prefix'];
                if($module_table_prefix != ''){
                    $table_prefix .= '_'.$module_table_prefix;
                }
            }
        }

        return cms_table_name($table_name, $table_prefix);
    }

    /**
     * @author go frendi
     *
     * @param string $navigation_name
     *
     * @return string
     * @desc   return good table name
     */
    public function cms_complete_navigation_name($navigation_name, $module_name = null)
    {
        $module_path = $this->cms_module_path($module_name);
        if ($module_path == 'main' or $module_path == '') {
            return $navigation_name;
        } else {
            return cms_module_navigation_name($module_path, $navigation_name);
        }
    }

    /**
     * @author go frendi
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     * @desc   if value specified, this will set CI_Session["key"], else it will return CI_session["key"]
     */
    public function cms_ci_session($key, $value = null)
    {
        if ($value !== null) {
            $this->session->set_userdata($key, $value);
            self::$__cms_model_properties['session'][$key] = $value;
        }
        // add to __cms_model_properties if not exists
        if (!array_key_exists($key, self::$__cms_model_properties['session'])) {
            self::$__cms_model_properties['session'][$key] = $this->session->userdata($key);
        }

        return self::$__cms_model_properties['session'][$key];
    }

    /**
     * @author go frendi
     *
     * @param string $key
     * @desc   unset CI_session["key"]
     */
    public function cms_unset_ci_session($key)
    {
        $this->session->set_userdata($key, NULL);
        $this->session->unset_userdata($key);
        self::$__cms_model_properties['session'][$key] = NULL;
        unset(self::$__cms_model_properties['session'][$key]);
    }

    public function cms_cached_property($key, $value = null)
    {
        if ($value !== null) {
            self::$__cms_model_properties['properties'][$key] = $value;
        }
        // add to __cms_model_properties if not exists
        if (!array_key_exists($key, self::$__cms_model_properties['properties'])) {
            self::$__cms_model_properties['properties'][$key] = null;
        }

        return self::$__cms_model_properties['properties'][$key];
    }

    public function cms_is_property_cached($key)
    {
        return array_key_exists($key, self::$__cms_model_properties['properties']);
    }

    public function cms_unique_field_name($field_name)
    {
        return 's'.substr(md5($field_name), 0, 8); //This s is because is better for a string to begin with a letter and not with a number
    }

    public function cms_unique_join_name($field_name)
    {
        return 'j'.substr(md5($field_name), 0, 8); //This j is because is better for a string to begin with a letter and not with a number
    }

    public function cms_random_string($length = 10)
    {
        $str = '';
        while (strlen($str) < $length) {
            $str .= md5(rand(0, 2000));
        }
        $str = substr($str, 0, $length);

        return $str;
    }

    /**
     * @author go frendi
     * @desc   get default_controller
     */
    public function cms_get_default_controller()
    {
        if (CMS_SUBSITE == '') {
            include APPPATH.'config/main/routes.php';
        } else {
            include APPPATH.'config/site-'.CMS_SUBSITE.'/routes.php';
        }

        return $route['default_controller'];
    }

    /**
     * @author go frendi
     *
     * @param string $value
     * @desc   set default_controller to value
     */
    public function cms_set_default_controller($value)
    {
        $pattern = array();
        $pattern[] = '/(\$route\[(\'|")default_controller(\'|")\] *= *")(.*?)(";)/si';
        $pattern[] = '/('.'\$'."route\[('|\")default_controller('|\")\] *= *')(.*?)(';)/si";
        if (CMS_SUBSITE == '') {
            $file_name = APPPATH.'config/main/routes.php';
        } else {
            $file_name = APPPATH.'config/site-'.CMS_SUBSITE.'/routes.php';
        }
        $str = file_get_contents($file_name);
        $replacement = '${1}'.addslashes($value).'${5}';
        $found = false;
        foreach ($pattern as $single_pattern) {
            if (preg_match($single_pattern, $str)) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            $str .= PHP_EOL.'$route[\'default_controller\'] = \''.addslashes($value).'\';';
        } else {
            $str = preg_replace($pattern, $replacement, $str);
        }
        @chmod($file_name, 0777);
        if (strpos($str, '<?php') !== false && strpos($str, '$route') !== false) {
            @file_put_contents($file_name, $str);
            @chmod($file_name, 0555);
            if(function_exists('opcache_invalidate')){
                opcache_invalidate($file_name);
            }
        }
    }

    /**
     * @author go frendi
     *
     * @param string $hostname
     * @param int    $port
     * @desc   is it able to go to some site?
     */
    public function cms_is_connect($hostname = null, $port = 80)
    {
        if ($this->cms_get_config('cms_internet_connectivity') === 'ONLINE') {
            return true;
        } elseif ($this->cms_get_config('cms_internet_connectivity') === 'OFFLINE') {
            return false;
        }
        $hostname = $hostname === null ? 'google.com' : $hostname;
        // return from session if we have look for it before
        if ($this->cms_ci_session('cms_connect_'.$hostname)) {
            // if last connect attempt is more than 300 seconds, try again
            if (microtime(true) - $this->cms_ci_session('cms_last_contact_'.$hostname) > 300) {
                $this->cms_unset_ci_session('cms_connect_'.$hostname);

                return $this->cms_is_connect($hostname, $port);
            }

            return $this->cms_ci_session('cms_connect_'.$hostname);
        }
        // we never look for it before, now look for it and save on session
        $connected = @fsockopen($hostname, $port, $errno, $errstr, 5);
        if ($connected) {
            $is_conn = true; //action when connected
            fclose($connected);
        } else {
            $is_conn = false; //action in connection failure
        }
        // get hostname
        $host_name = explode(':', $_SERVER['HTTP_HOST']);
        $host_name = $host_name[0];
        // if hostname is not localhost, change the UNKNOWN cms_internet_connectivity into ONLINE or OFFLINE
        if ($host_name != 'localhost' && $host_name != '127.0.0.1') {
            if ($is_conn) {
                $this->cms_set_config('cms_internet_connectivity', 'ONLINE');
            } else {
                $this->cms_set_config('cms_internet_connectivity', 'OFFLINE');
            }
        }
        $this->cms_ci_session('cms_connect_'.$hostname, $is_conn);
        $this->cms_ci_session('cms_last_contact_'.$hostname, microtime(true));

        return $is_conn;
    }

    private function __cms_get_new_pp_name($pp, $resolution){
        $pp_part = explode('/', $pp);
        $file_name = $pp_part[count($pp_part)-1];
        $new_pp_path = $pp.'_dir';
        // make pp path
        if(!file_exists($new_pp_path)){
            mkdir($new_pp_path);
        }
        $new_pp = $new_pp_path.'/'.$resolution.'_'.$file_name;
        return $new_pp;
    }
    public function cms_get_profile_picture($user_id, $resolution = 256){
        if (array_key_exists($user_id, self::$__cms_model_properties['profile_pictures'])) {
            return self::$__cms_model_properties['profile_pictures'][$user_id];
        }
        // get user
        $user_record = $this->cms_get_record($this->cms_user_table_name(), 'user_id', $user_id);
        $real_base_url = base_url();
        if(USE_SUBDOMAIN && CMS_SUBSITE != '' && !USE_ALIAS){
            $real_base_url = base_url();
            $real_base_url = str_ireplace('://'.CMS_SUBSITE.'.',  '://', $real_base_url);
        }
        // determine pp
        $pp = '';
        if($user_record === null){
            $pp = 'assets/nocms/images/default-profile-picture.png';
            $new_pp = $this->__cms_get_new_pp_name($pp, $resolution);
            if(!file_exists(FCPATH.$new_pp)){
                $this->cms_resize_image(FCPATH.$pp, $resolution, $resolution, FCPATH.$new_pp);
            }
            $pp = $real_base_url.$new_pp;
        }else{
            $pp = $user_record->profile_picture;
            if($pp == NULL && $this->cms_is_connect('www.gravatar.com')){
                // take from gravatar
                $pp = 'http://www.gravatar.com/avatar/'.md5($user_record->email).'?s='.$resolution.'&r=pg&d=identicon';
            }else{
                if($pp != NULL){
                    // take from table
                    $pp = 'assets/nocms/images/profile_picture/'.$user_record->profile_picture;
                }
                if($pp == NULL || !file_exists(FCPATH.$pp)){
                    // take from default profile picture
                    $pp = 'assets/nocms/images/default-profile-picture.png';
                }
                $new_pp = $this->__cms_get_new_pp_name($pp, $resolution);
                if(!file_exists(FCPATH.$new_pp)){
                    $this->cms_resize_image(FCPATH.$pp, $resolution, $resolution, FCPATH.$new_pp);
                }
                $pp = $real_base_url.$new_pp;
            }
        }
        // cache profile picture
        self::$__cms_model_properties['profile_pictures'][$user_id] = $pp;
        return $pp;
    }

    /**
     * @author go frendi
     *
     * @param string $user_name
     *
     * @return mixed
     * @desc   set or get CI_Session["cms_user_name"]
     */
    public function cms_user_name($user_name = null)
    {
        return $this->cms_ci_session('cms_user_name', $user_name);
    }

    /**
     * @author go frendi
     *
     * @param string $real_name
     *
     * @return mixed
     * @desc   set or get CI_Session["cms_user_real_name"]
     */
    public function cms_user_real_name($real_name = null)
    {
        $user_real_name = $this->cms_ci_session('cms_user_real_name', $real_name);
        if($user_real_name == ''){
            $user_real_name = $this->cms_user_name();
        }
        return $user_real_name;
    }

    /**
     * @author go frendi
     *
     * @param string $email
     *
     * @return mixed
     * @desc   set or get CI_Session["cms_user_email"]
     */
    public function cms_user_email($email = null)
    {
        return $this->cms_ci_session('cms_user_email', $email);
    }

    /**
     * @author go frendi
     *
     * @param int $user_id
     * @desc   set or get CI_Session["cms_user_id"]
     */
    public function cms_user_id($user_id = null)
    {
        return $this->cms_ci_session('cms_user_id', $user_id);
    }

    private function cms_adjust_group()
    {
        $group_id = array();
        $group_name = array();
        if ($this->cms_user_id() != null) {
            $t_group_user = cms_table_name('main_group_user');
            $t_group = cms_table_name('main_group');
            $query = $this->db->select($t_group_user.'.group_id, group_name')
                ->from($t_group_user)
                ->join($t_group, $t_group.'.group_id = '.$t_group_user.'.group_id')
                ->where(cms_table_name('main_group_user').'.user_id', $this->cms_user_id())
                ->get();
            foreach ($query->result() as $row) {
                $group_id[] = $row->group_id;
                $group_name[] = $row->group_name;
            }
        }
        self::$__cms_model_properties['group_id'] = $group_id;
        self::$__cms_model_properties['group_name'] = $group_name;
        self::$__cms_model_properties['is_group_id_cached'] = true;
        self::$__cms_model_properties['is_group_name_cached'] = true;
    }

    /**
     * @author go frendi
     *
     * @return array
     * @desc   get group list of current user
     */
    public function cms_user_group()
    {
        if (!self::$__cms_model_properties['is_group_name_cached']) {
            $this->cms_adjust_group();
        }

        return self::$__cms_model_properties['group_name'];
    }

    /**
     * @author go frendi
     *
     * @return array
     * @desc   get group list of current user
     */
    public function cms_user_group_id()
    {
        if (!self::$__cms_model_properties['is_group_id_cached']) {
            $this->cms_adjust_group();
        }

        return self::$__cms_model_properties['group_id'];
    }

    /**
     * @author go frendi
     *
     * @return bool
     * @desc   TRUE if current user is super admin, FALSE otherwise
     */
    public function cms_user_is_super_admin()
    {
        if(self::$__cms_model_properties['is_super_admin_cached']){
            return self::$__cms_model_properties['is_super_admin'];
        }

        $is_super_admin = FALSE;
        if ($this->cms_user_id() == 1) {
            $is_super_admin = TRUE;
        } else if (CMS_SUBSITE != '') {
            // get cms table prefix
            include APPPATH.'config/main/cms_config.php';
            $cms_table_prefix = $config['__cms_table_prefix'];
            // get module path
            $module_table_name = '';
            if ($cms_table_prefix != '') {
                $module_table_name = $cms_table_prefix.'_';
            }
            $module_table_name .= 'main_module';
            $query = $this->db->select('module_path')
                ->from($module_table_name)
                ->where('module_name', 'gofrendi.noCMS.multisite')
                ->get();
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $module_path = $row->module_path;
                // get multisite module's table prefix
                include FCPATH.'modules/'.$module_path.'/config/module_config.php';
                $module_table_prefix = $config['module_table_prefix'];
                // get complete subsite table name
                $subsite_table_name = '';
                if ($cms_table_prefix != '') {
                    $subsite_table_name = $cms_table_prefix.'_';
                    if ($module_table_prefix != '') {
                        $subsite_table_name = $subsite_table_name.$module_table_prefix.'_';
                    }
                } else {
                    if ($module_table_prefix != '') {
                        $subsite_table_name = $module_table_prefix.'_';
                    }
                }
                $subsite_table_name .= 'subsite';
                // is the current user master of current subsite
                $query = $this->db->select('user_id')
                    ->from($subsite_table_name)
                    ->where('name', CMS_SUBSITE)
                    ->get();
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    if ($row->user_id == $this->cms_user_id()) {
                        $is_super_admin = TRUE;
                    }
                }
            }
        }
        // normal flow
        if(!$is_super_admin){
            $is_super_admin = in_array(1, $this->cms_user_group_id());
        }

        // cache the result
        self::$__cms_model_properties['is_super_admin'] = $is_super_admin;
        self::$__cms_model_properties['is_super_admin_cached'] = TRUE;
        return $is_super_admin;
    }

    /**
     * @author  go frendi
     *
     * @param   int parent_id
     * @param   int max_menu_depth
     * @desc    return navigation child if parent_id specified, else it will return root navigation
     *           the max depth of menu is depended on max_menud_depth
     */
    public function cms_navigations($parent_id = null, $max_menu_depth = null)
    {
        $user_name = $this->cms_user_name();
        $user_id = $this->cms_user_id();
        $user_id = $user_id == '' ? 0 : $user_id;
        $not_login = !$user_name ? '(1=1)' : '(1=2)';
        $login = $user_name ? '(1=1)' : '(1=2)';
        $super_user = $this->cms_user_is_super_admin() ? '(1=1)' : '(1=2)';

        //get max_menu_depth from configuration
        if (!isset($max_menu_depth)) {
            $max_menu_depth = $this->cms_get_config('max_menu_depth');
            if (!isset($max_menu_depth)) {
                $max_menu_depth = 10;
                $this->cms_set_config('max_menu_depth', $max_menu_depth);
            }
        }

        if ($max_menu_depth > 0) {
            --$max_menu_depth;
        } else {
            return array();
        }

        // $where_is_root = !isset($parent_id) ? "(parent_id IS NULL)" : "parent_id = '" . addslashes($parent_id) . "'";
        if (!self::$__cms_model_properties['is_navigation_cached']) {
            $query = $this->db->query("SELECT navigation_id, navigation_name, bootstrap_glyph, is_static, title, description, url, notif_url, active, parent_id,
                    (
                        (authorization_id = 1) OR
                        (authorization_id = 2 AND $not_login) OR
                        (authorization_id = 3 AND $login) OR
                        (
                            (authorization_id = 4 AND $login) AND
                            (
                                $super_user OR
                                (SELECT COUNT(*) FROM ".cms_table_name('main_group_navigation').' AS gn
                                    WHERE
                                        gn.navigation_id=n.navigation_id AND
                                        gn.group_id IN
                                            (SELECT group_id FROM '.cms_table_name('main_group_user').' WHERE user_id = '.addslashes($user_id).")
                                )>0
                            )
                        ) OR
                        (
                            (authorization_id = 5 AND $login) AND
                            (
                                (SELECT COUNT(*) FROM ".cms_table_name('main_group_navigation').' AS gn
                                    WHERE
                                        gn.navigation_id=n.navigation_id AND
                                        gn.group_id IN
                                            (SELECT group_id FROM '.cms_table_name('main_group_user').' WHERE user_id = '.addslashes($user_id).')
                                )>0
                            )
                        )
                    ) AS allowed, hidden
                FROM '.cms_table_name('main_navigation').' AS n  ORDER BY n.'.$this->db->protect_identifiers('index'));
            self::$__cms_model_properties['is_navigation_cached'] = true;
            self::$__cms_model_properties['navigation'] = $query->result();
        }
        $result = array();
        foreach (self::$__cms_model_properties['navigation'] as $row) {
            if ($parent_id === null) {
                if ($row->parent_id != null) {
                    continue;
                }
            } else {
                if ($row->parent_id != $parent_id) {
                    continue;
                }
            }
            $children = $this->cms_navigations($row->navigation_id, $max_menu_depth);
            $have_allowed_children = false;
            foreach ($children as $child) {
                if ($child['allowed'] && $child['active']) {
                    $have_allowed_children = true;
                    break;
                }
            }
            if ((!isset($row->url) || $row->url == '' || strpos(strtoupper($row->url), 'HTTP://') !== false  || strpos(strtoupper($row->url), 'HTTPS://') !== false) && $row->is_static == 1) {
                $url = site_url('main/static_page/'.$row->navigation_name);
            } else {
                if (strpos(strtoupper($row->url), 'HTTP://') !== false || strpos(strtoupper($row->url), 'HTTPS://') !== false) {
                    $url = $row->url;
                } else {
                    $url = site_url($row->url);
                }
            }
            if (trim($row->notif_url) == '') {
                $notif_url = '';
            } elseif (strpos(strtoupper($row->notif_url), 'HTTP://') !== false || strpos(strtoupper($row->notif_url), 'HTTPS://') !== false) {
                $notif_url = $row->notif_url;
            } else {
                $notif_url = site_url($row->notif_url);
            }
            $result[] = array(
                'navigation_id' => $row->navigation_id,
                'navigation_name' => $row->navigation_name,
                'bootstrap_glyph' => $row->bootstrap_glyph,
                'title' => $this->cms_lang($row->title),
                'description' => $this->cms_lang($row->description),
                'url' => $url,
                'notif_url' => $notif_url,
                'is_static' => $row->is_static,
                'active' => $row->active,
                'child' => $children,
                'allowed' => $row->allowed,
                'have_allowed_children' => $have_allowed_children,
                'hidden' => $row->hidden,
            );
        }

        return $result;
    }

    public function cms_navigation($navigation_name){
        if (!self::$__cms_model_properties['is_navigation_cached']) {
            // cache navigations
            $this->cms_navigations();
        }
        foreach(self::$__cms_model_properties['navigation'] as $navigation){
            if($navigation->navigation_name == $navigation_name){
                return $navigation;
            }
        }
        return NULL;
    }

    /**
     * @author go frendi
     *
     * @return mixed
     * @desc   return quick links
     */
    public function cms_quicklinks()
    {
        if (self::$__cms_model_properties['is_quicklink_cached']) {
            return self::$__cms_model_properties['quicklink'];
        }

        $user_name = $this->cms_user_name();
        $user_id = $this->cms_user_id();
        $user_id = $user_id == '' ? 0 : $user_id;
        $not_login = !$user_name ? '(1=1)' : '(1=2)';
        $login = $user_name ? '(1=1)' : '(1=2)';
        $super_user = $this->cms_user_is_super_admin() ? '(1=1)' : '(1=2)';

        $query = $this->db->query("
                        SELECT q.navigation_id, navigation_name, bootstrap_glyph, is_static, title, description, url, notif_url, active, hidden,
                        (
                            (authorization_id = 1) OR
                            (authorization_id = 2 AND $not_login) OR
                            (authorization_id = 3 AND $login) OR
                            (
                                (authorization_id = 4 AND $login) AND
                                (
                                    $super_user OR
                                    (SELECT COUNT(*) FROM ".cms_table_name('main_group_navigation').' AS gn
                                        WHERE
                                            gn.navigation_id=n.navigation_id AND
                                            gn.group_id IN
                                                (SELECT group_id FROM '.cms_table_name('main_group_user').' WHERE user_id = '.addslashes($user_id).")
                                    )>0
                                )
                            ) OR
                            (
                                (authorization_id = 5 AND $login) AND
                                (
                                    (SELECT COUNT(*) FROM ".cms_table_name('main_group_navigation').' AS gn
                                        WHERE
                                            gn.navigation_id=n.navigation_id AND
                                            gn.group_id IN
                                                (SELECT group_id FROM '.cms_table_name('main_group_user').' WHERE user_id = '.addslashes($user_id).')
                                    )>0
                                )
                            )
                        ) as allowed
                        FROM
                            '.cms_table_name('main_navigation').' AS n,
                            '.cms_table_name('main_quicklink').' AS q
                        WHERE
                            (
                                q.navigation_id = n.navigation_id
                            )
                            ORDER BY q.'.$this->db->protect_identifiers('index'));
        $result = array();
        foreach ($query->result() as $row) {
            $children = $this->cms_navigations($row->navigation_id);
            $have_allowed_children = false;
            foreach ($children as $child) {
                if ($child['allowed'] && $child['active']) {
                    $have_allowed_children = true;
                    break;
                }
            }
            if ((!isset($row->url) || $row->url == '') && $row->is_static == 1) {
                $url = 'main/static_page/'.$row->navigation_name;
            } else {
                if (strpos(strtoupper($row->url), 'HTTP://') !== false || strpos(strtoupper($row->url), 'HTTPS://') !== false) {
                    $url = $row->url;
                } else {
                    $url = site_url($row->url);
                }
            }
            if (trim($row->notif_url) == '') {
                $notif_url = '';
            } elseif (strpos(strtoupper($row->notif_url), 'HTTP://') !== false || strpos(strtoupper($row->notif_url), 'HTTPS://') !== false) {
                $notif_url = $row->notif_url;
            } else {
                $notif_url = site_url($row->notif_url);
            }
            $result[] = array(
                'navigation_id' => $row->navigation_id,
                'navigation_name' => $row->navigation_name,
                'bootstrap_glyph' => $row->bootstrap_glyph,
                'allowed' => $row->allowed,
                'have_allowed_children' => $have_allowed_children,
                'title' => $this->cms_lang($row->title),
                'description' => $row->description,
                'url' => $url,
                'notif_url' => $notif_url,
                'is_static' => $row->is_static,
                'child' => $children,
                'active' => $row->active,
                'hidden' => $row->hidden,
            );
        }

        self::$__cms_model_properties['quicklink'] = $result;
        self::$__cms_model_properties['is_quicklink_cached'] = true;

        return $result;
    }

    /**
     * @author  go frendi
     *
     * @param   slug
     * @param   widget_name
     *
     * @return mixed
     * @desc    return widgets
     */
    public function cms_widgets($slug = null, $widget_name = null)
    {
        // get user_name, user_id, etc
        $user_name = $this->cms_user_name();
        $user_id = $this->cms_user_id();
        $user_id = $user_id == '' ? 0 : $user_id;
        $not_login = !$user_name ? '(1=1)' : '(1=2)';
        $login = $user_name ? '(1=1)' : '(1=2)';
        $super_user = $this->cms_user_is_super_admin() ? '(1=1)' : '(1=2)';

        if (!self::$__cms_model_properties['is_widget_cached']) {
            $SQL = 'SELECT
                        widget_id, widget_name, is_static, title,
                        description, url, slug, static_content, active
                    FROM '.cms_table_name('main_widget')." AS w WHERE
                        (
                            (authorization_id = 1) OR
                            (authorization_id = 2 AND $not_login) OR
                            (authorization_id = 3 AND $login) OR
                            (
                                (authorization_id = 4 AND $login) AND
                                (
                                    $super_user OR
                                    (SELECT COUNT(*) FROM ".cms_table_name('main_group_widget').' AS gw
                                        WHERE
                                            gw.widget_id=w.widget_id AND
                                            gw.group_id IN
                                                (SELECT group_id FROM '.cms_table_name('main_group_user').' WHERE user_id = '.addslashes($user_id).")
                                    )>0
                                )
                            ) OR
                            (
                                (authorization_id = 5 AND $login) AND
                                (
                                    (SELECT COUNT(*) FROM ".cms_table_name('main_group_widget').' AS gw
                                        WHERE
                                            gw.widget_id=w.widget_id AND
                                            gw.group_id IN
                                                (SELECT group_id FROM '.cms_table_name('main_group_user').' WHERE user_id = '.addslashes($user_id).')
                                    )>0
                                )
                            )
                        ) ORDER BY '.$this->db->protect_identifiers('index');
            $query = $this->db->query($SQL);
            self::$__cms_model_properties['widget'] = $query->result();
            self::$__cms_model_properties['is_widget_cached'] = true;
        }
        $result = array();
        foreach (self::$__cms_model_properties['widget'] as $row) {
            if (isset($slug) && $slug != '') {
                if ($row->active != 1 || stripos($row->slug === null ? '' : $row->slug, $slug) === false) {
                    continue;
                }
            }

            if (isset($widget_name)) {
                if (strtolower($row->widget_name) != strtolower($widget_name)) {
                    continue;
                }
            }

            $editing_mode_content = '';
            if ($this->cms_editing_mode() && $this->cms_allow_navigate('main_widget_management') && $this->cms_have_privilege('edit_main_widget') && $row->widget_name != 'section_custom_script' && $row->widget_name != 'section_custom_style') {
                $editing_mode_content = '<div class="__editing_widget __editing_widget_'.str_replace(' ', '_', $row->widget_name).'">'.
                    '<a style="margin-bottom:2px; margin-top:2px;" class="btn btn-default btn-xs pull-right" href="{{ SITE_URL }}main/manage_widget/index/edit/'.$row->widget_id.'?from='.$this->cms_get_origin_uri_string().'">'.
                        '<i class="glyphicon glyphicon-pencil"></i> <strong>'.ucwords(str_replace('_', ' ', $row->widget_name)). '</strong>'.
                    '</a><div style="clear:both"></div>'.
                '</div>';
            }

            // generate widget content
            $content = '';
            if ($row->is_static == 1) {
                if(trim($row->static_content) != ''){
                    $content .= $editing_mode_content;
                }
                $content .= $row->static_content;
            } else {
                // url
                $url = $row->url;
                // content
                if ($slug) {
                    $content .= '<div id="__cms_widget_'.$row->widget_id.'">';
                } else {
                    $content .= '<span id="__cms_widget_'.$row->widget_id.'" style="padding:0px; margin:0px;">';
                }
                if (strpos(strtoupper($url), 'HTTP://') !== false || strpos(strtoupper($url), 'HTTPS://') !== false) {
                    $response = null;
                    // use CURL
                    if (in_array('curl', get_loaded_extensions())) {
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_COOKIEJAR, '');
                        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
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
                        $response = preg_replace('#(href|src|action)="([^:"]*)(?:")#', '$1="'.$url.'/$2"', $response);
                        // add editing mode content
                        if(trim($row->response) != ''){
                            $content .= $editing_mode_content;
                        }
                        $content .= $response;
                    }
                } else {
                    $url = trim_slashes($url);
                    $url_segment = explode('/', $url);
                    $module_path = $url_segment[0];
                    $response = '';
                    if($url !== NULL && trim($url) != ''){
                        // ensure self::$__cms_model_properties['module_name'] exists. This variable's keys are all available module path
                        $this->cms_module_name();
                        if ($module_path == 'main' || (array_key_exists($module_path, self::$__cms_model_properties['module_name']) && self::$__cms_model_properties['module_name'][$module_path] != '')) {
                            $_REQUEST['__cms_dynamic_widget'] = 'TRUE';
                            $_REQUEST['__cms_dynamic_widget_module'] = $module_path;
                            $url = trim($url, '/');
                            $response = @Modules::run($url);
                            if (strlen($response) == 0) {
                                $response = @Modules::run($url.'/index');
                            }
                            unset($_REQUEST['__cms_dynamic_widget']);
                            unset($_REQUEST['__cms_dynamic_widget_module']);
                        }
                        // fallback, Modules::run failed, use AJAX instead
                        if (strlen($response) == 0) {
                            $response = '<script type="text/javascript">';
                            $response .= '$(document).ready(function(){$("#__cms_widget_'.$row->widget_id.'").load("'.site_url($url).'?__cms_dynamic_widget=TRUE");});';
                            $response .= '</script>';
                        }
                    }
                    // add editing mode content
                    if(trim($response) != ''){
                        $content .= $editing_mode_content;
                    }
                    $content .= $response;
                }

                if ($slug) {
                    $content .= '</div>';
                } else {
                    $content .= '</span>';
                }
            }
            // make widget based on slug
            $slugs = explode(',', $row->slug);
            foreach ($slugs as $single_slug) {
                $single_slug = trim($single_slug);
                if (!isset($result[$single_slug])) {
                    $result[$single_slug] = array();
                }
                $result[$single_slug][] = array(
                    'widget_id' => $row->widget_id,
                    'widget_name' => $row->widget_name,
                    'title' => $this->cms_lang($row->title),
                    'description' => $row->description,
                    'content' => $this->cms_parse_keyword($content),
                );
            }
        }

        return $result;
    }

    /**
     * @author  go frendi
     *
     * @param   string navigation_name
     *
     * @return string
     * @desc    return url of navigation
     */
    public function cms_navigation_url($navigation_name)
    {
        $query = $this->db->select('navigation_name, url')
            ->from(cms_table_name('main_navigation'))
            ->where('navigation_name', $navigation_name)
            ->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $url = $row->url;
            if ($url == '' || $url === null) {
                $navigation_name = $row->navigation_name;
                $url = 'main/static_page/'.$navigation_name;
            }

            return $url;
        } else {
            return '';
        }
    }

    /**
     * @author  go frendi
     *
     * @param   string navigation_name
     *
     * @return mixed
     * @desc    return navigation path, used for layout
     */
    public function cms_get_navigation_path($navigation_name = null)
    {
        if (!isset($navigation_name)) {
            return array();
        }
        // unused, just called to ensure that the navigation is already cached
        if (!self::$__cms_model_properties['is_navigation_cached']) {
            $this->cms_navigations();
        }
        $navigations = self::$__cms_model_properties['navigation'];
        // get first node
        $result = array();
        $parent_navigation_id = null;
        foreach ($navigations as $navigation) {
            if ($navigation->navigation_name == $navigation_name) {
                $result[] = array(
                        'navigation_id' => $navigation->navigation_id,
                        'navigation_name' => $navigation->navigation_name,
                        'title' => $this->cms_lang($navigation->title),
                        'description' => $navigation->description,
                        'url' => $navigation->url,
                    );
                $parent_navigation_id = $navigation->parent_id;
                break;
            }
        }
        $force_quit = FALSE;
        while ($parent_navigation_id != null && $parent_navigation_id != '' && $parent_navigation_id > 0) {
            foreach ($navigations as $navigation) {
                if ($navigation->navigation_id == $parent_navigation_id) {
                    // infinite recursion detected, don't continue
                    foreach($result as $existing_parent_navigation){
                        if($navigation->navigation_name == $existing_parent_navigation['navigation_name']){
                            $force_quit = TRUE;
                            break;
                        }
                    }
                    if($navigation_name == $navigation->navigation_name){
                        $force_quit = TRUE;
                    }
                    if($force_quit){
                        break;
                    }
                    // no infinite recursion detected, continue
                    $result[] = array(
                            'navigation_id' => $navigation->navigation_id,
                            'navigation_name' => $navigation->navigation_name,
                            'title' => $this->cms_lang($navigation->title),
                            'description' => $navigation->description,
                            'url' => $navigation->url,
                        );
                    $parent_navigation_id = $navigation->parent_id;
                    break;
                }
            }
            // infinite recursion has been detected, just quit
            if($force_quit){
                break;
            }
        }
        //result should be in reverse order
        for ($i = 0; $i < ceil(count($result) / 2); ++$i) {
            $temp = $result[$i];
            $result[$i] = $result[count($result) - 1 - $i];
            $result[count($result) - 1 - $i] = $temp;
        }

        return $result;
    }

    /**
     * @author  go frendi
     *
     * @return mixed
     * @desc    return privileges of current user
     */
    public function cms_privileges()
    {
        $user_name = $this->cms_user_name();
        $user_id = $this->cms_user_id();
        $user_id = !isset($user_id) || is_null($user_id) ? 0 : $user_id;
        $not_login = !isset($user_name) ? 'TRUE' : 'FALSE';
        $login = isset($user_name) ? 'TRUE' : 'FALSE';
        $super_user = $this->cms_user_is_super_admin() ? 'TRUE' : 'FALSE';

        $query = $this->db->query('SELECT privilege_name, title, description
                FROM '.cms_table_name('main_privilege')." AS p WHERE
                    (authorization_id = 1) OR
                    (authorization_id = 2 AND $not_login) OR
                    (authorization_id = 3 AND $login) OR
                    (
                        (authorization_id = 4 AND $login AND
                        (
                            $super_user OR
                            (SELECT COUNT(*) FROM ".cms_table_name('main_group_privilege').' AS gp
                                WHERE
                                    gp.privilege_id=p.privilege_id AND
                                    gp.group_id IN
                                        (SELECT group_id FROM '.cms_table_name('main_group_user')." WHERE user_id = '".addslashes($user_id)."')
                            )>0)
                        )
                    ) OR
                    (
                        (authorization_id = 5 AND $login AND
                        (
                            (SELECT COUNT(*) FROM ".cms_table_name('main_group_privilege').' AS gp
                                WHERE
                                    gp.privilege_id=p.privilege_id AND
                                    gp.group_id IN
                                        (SELECT group_id FROM '.cms_table_name('main_group_user')." WHERE user_id = '".addslashes($user_id)."')
                            )>0)
                        )
                    )
                    ");
        $result = array();
        foreach ($query->result() as $row) {
            $result[] = array(
                'privilege_name' => $row->privilege_name,
                'title' => $row->title,
                'description' => $row->description,
            );
        }

        return $result;
    }

    /**
     * @author  go frendi
     *
     * @param   string navigation_name
     * @param   mixed navigations
     *
     * @return bool
     * @desc    check if user authorized to navigate into a page specified in parameter
     */
    public function cms_allow_navigate($navigation_name, $navigations = null)
    {
        if (!isset($navigations)) {
            $navigations = $this->cms_navigations();
        }
        for ($i = 0; $i < count($navigations); ++$i) {
            if ($navigation_name == $navigations[$i]['navigation_name'] && $navigations[$i]['active'] && $navigations[$i]['allowed'] == 1) {
                return true;
            } elseif ($this->cms_allow_navigate($navigation_name, $navigations[$i]['child'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @author  go frendi
     *
     * @param   string privilege_name
     *
     * @return bool
     * @desc    check if user have privilege specified in parameter
     */
    public function cms_have_privilege($privilege_name)
    {
        if ($this->cms_user_is_super_admin()) {
            return true;
        } else {
            $privileges = $this->cms_privileges();
            for ($i = 0; $i < count($privileges); ++$i) {
                if ($privilege_name == $privileges[$i]['privilege_name']) {
                    return true;
                }
            }

            return false;
        }
    }

    public function cms_route_key_exists($route_key)
    {
        if (!self::$__cms_model_properties['is_route_cached']) {
            $query = $this->db->select('key, value')
                ->from(cms_table_name('main_route'))
                ->get();
            self::$__cms_model_properties['route'] = array();
            foreach ($query->result() as $row) {
                self::$__cms_model_properties['route'][$row->key] = $row->value;
            }
            self::$__cms_model_properties['is_route_cached'] = true;
        }

        return array_key_exists($route_key, self::$__cms_model_properties['route']);
    }

    /**
     * @author  go frendi
     *
     * @param   string identity
     * @param   string password
     *
     * @return bool
     * @desc    login with identity and password. Identity can be user_name or e-mail
     */
    public function cms_do_login($identity, $password)
    {
        $user_name = null;
        $user_id = null;
        $user_real_name = null;
        $user_email = null;
        $login_succeed = false;

        // define where clause to match password and subsite. subsite also look for main_site's user
        $this->db->group_start()
            ->group_start() // match with main site user
                ->where('password', cms_md5($password, $this->cms_chipper()))
                ->group_start()
                    ->where('subsite', NULL)
                    ->or_where('subsite', '')
                ->group_end()
            ->group_end();
            if(CMS_SUBSITE != ''){
                $this->db->or_group_start() // or match with subsite's user
                    ->where('password', cms_md5($password))
                    ->where('subsite', CMS_SUBSITE)
                ->group_end();
            }
        $this->db->group_end();
        // define query
        $query = $this->db->select('user_id, user_name, real_name, email')
            ->from($this->cms_user_table_name())
            ->where('active', 1)
            ->group_start()
                ->where('user_name', $identity)
                ->or_where('email', $identity)
            ->group_end()
            ->order_by('subsite', 'desc') // prioritize subsite user
            ->get();

        // if login succeed, get user_name, user_id, real_name, and email, otherwise try the hook
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $user_name = $row->user_name;
            $user_id = $row->user_id;
            $user_real_name = $row->real_name;
            $user_email = $row->email;
            $login_succeed = TRUE;
        } else {
            $extended_login_result_list = $this->cms_call_hook('cms_login', array($identity, $password));
            foreach($extended_login_result_list as $extended_login_result) {
                if ($extended_login_result !== false && $extended_login_result !== NULL) {
                    $query = $this->db->select('user_id, user_name')
                        ->from($this->cms_user_table_name())
                        ->where('user_name', $identity)
                        ->get();
                    // login succeed from hook
                    $local_login_succeed = FALSE;
                    // if already exists in database
                    if ($query->num_rows() > 0) {
                        $row = $query->row();
                        $user_id = $row->user_id;
                        $user_name = $row->user_name;
                        $local_login_succeed = true;
                    } else {
                        $data = array();
                        $data['user_name'] = $identity;
                        $data['password'] = null;
                        $local_login_succeed = $this->db->insert($this->cms_user_table_name(), $data);
                        if ($local_login_succeed) {
                            $user_id = $this->db->insert_id();
                            $user_name = $identity;
                        }
                    }
                    if($local_login_succeed){
                        $login_succeed = TRUE;
                    }
                    // if return value of hook is array
                    if ($local_login_succeed && is_array($extended_login_result)) {
                        // get field value from hook
                        $user_data = array();
                        $key_list = array('email', 'real_name', 'birthdate',
                            'sex', 'self_description', 'profile_picture');
                        foreach($user_data_key as $key_list){
                            if(array_key_exists($user_data_key, $extended_login_result)){
                                $user_data[$user_data_key] = $extended_login_result[$user_data_key];
                            }
                        }
                        // update
                        $this->db->update($this->cms_user_table_name(), $user_data,
                            array('user_id' => $user_id));
                        if(array_key_exists('real_name', $user_data)){
                            $user_real_name = $user_data['real_name'];
                        }
                        if(array_key_exists('email', $user_data)){
                            $user_email = $user_data['email'];
                        }
                    }
                }
            }
        }

        // cache it
        if ($login_succeed) {
            $this->cms_user_name($user_name);
            $this->cms_user_id($user_id);
            $this->cms_user_real_name($user_real_name);
            $this->cms_user_email($user_email);

            $this->__cms_extend_user_last_active($user_id);
            self::$__cms_model_properties['is_super_admin_cached'] = FALSE;

            return true;
        }

        return false;
    }

    private function __cms_extend_user_last_active($user_id)
    {
        if ($user_id > 0 && !self::$__cms_model_properties['is_user_last_active_extended']) {
            $query = $this->db->select('last_active')
                ->from($this->cms_user_table_name())
                ->where('user_id', $user_id)
                ->get();
            if($query->num_rows() > 0){
                $row = $query->row();
                if($row->last_active < microtime(true) - $this->last_active_tolerance){
                    $this->db->update($this->cms_user_table_name(),
                        array(
                            'last_active' => microtime(true),
                            'login' => 1, ),
                        array('user_id' => $user_id));
                }
            }
            self::$__cms_model_properties['is_user_last_active_extended'] = true;
        }
    }

    /**
     * @author  go frendi
     * @desc    logout
     */
    public function cms_do_logout()
    {
        $this->db->update($this->cms_user_table_name(),
            array('login' => 0),
            array('user_id' => $this->cms_user_id()));

        $this->cms_unset_ci_session('cms_user_name');
        $this->cms_unset_ci_session('cms_user_id');
        $this->cms_unset_ci_session('cms_user_real_name');
        $this->cms_unset_ci_session('cms_user_email');
        self::$__cms_model_properties['is_super_admin_cached'] = FALSE;
    }

    /**
     * @author  go frendi
     *
     * @param   string parent
     * @desc    re-arange index of navigation with certain parent_id
     */
    private function __cms_reindex_navigation($parent_id = null)
    {
        if (isset($parent_id)) {
            $whereParentId = "(parent_id = $parent_id)";
        } else {
            $whereParentId = '(parent_id IS NULL)';
        }
        $query = $this->db->select('navigation_id,index')
            ->from(cms_table_name('main_navigation'))
            ->where($whereParentId)
            ->order_by('index')
            ->get();
        $index = 1;
        foreach ($query->result() as $row) {
            if ($index != $row->index) {
                $where = array('navigation_id' => $row->navigation_id);
                $data = array('index' => $index);
                $this->db->update(cms_table_name('main_navigation'), $data, $where);
            }
            $index += 1;
        }
    }

    /**
     * @author  go frendi
     *
     * @param   string parent
     * @desc    re-arange index of widget
     */
    private function __cms_reindex_widget()
    {
        $query = $this->db->select('widget_id,index')
            ->from(cms_table_name('main_widget'))
            ->order_by('index')
            ->get();
        $index = 1;
        foreach ($query->result() as $row) {
            if ($index != $row->index) {
                $where = array('widget_id' => $row->widget_id);
                $data = array('index' => $index);
                $this->db->update(cms_table_name('main_widget'), $data, $where);
            }
            $index += 1;
        }
    }

    /**
     * @author  go frendi
     *
     * @param   string parent
     * @desc    re-arange index of quicklink
     */
    private function __cms_reindex_quicklink()
    {
        $query = $this->db->select('quicklink_id,index')
            ->from(cms_table_name('main_quicklink'))
            ->order_by('index')
            ->get();
        $index = 1;
        foreach ($query->result() as $row) {
            if ($index != $row->index) {
                $where = array('quicklink_id' => $row->quicklink_id);
                $data = array('index' => $index);
                $this->db->update(cms_table_name('main_quicklink'), $data, $where);
            }
            $index += 1;
        }
    }

    public function cms_do_move_widget_after($src_widget_id, $dst_widget_id)
    {
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
        foreach ($query->result() as $row) {
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
        foreach ($query->result() as $row) {
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

    public function cms_do_move_widget_before($src_widget_id, $dst_widget_id)
    {
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
        foreach ($query->result() as $row) {
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
        foreach ($query->result() as $row) {
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

    public function cms_do_move_quicklink_after($src_quicklink_id, $dst_quicklink_id)
    {
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
        foreach ($query->result() as $row) {
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
        foreach ($query->result() as $row) {
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

    public function cms_do_move_quicklink_before($src_quicklink_id, $dst_quicklink_id)
    {
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
        foreach ($query->result() as $row) {
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
        foreach ($query->result() as $row) {
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

    public function cms_do_move_navigation_after($src_navigation_id, $dst_navigation_id)
    {
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
        foreach ($query->result() as $row) {
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
        foreach ($query->result() as $row) {
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

    public function cms_do_move_navigation_before($src_navigation_id, $dst_navigation_id)
    {
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
        foreach ($query->result() as $row) {
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
        foreach ($query->result() as $row) {
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

    public function cms_do_move_navigation_into($src_navigation_id, $dst_navigation_id)
    {
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
        foreach ($query->result() as $row) {
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
        foreach ($query->result() as $row) {
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
     * @author  go frendi
     *
     * @param   int navigation id
     * @desc    move quicklink up
     */
    public function cms_do_move_up_quicklink($quicklink_id)
    {
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
        $SQL = '
            SELECT max('.$this->db->protect_identifiers('index').') AS '.$this->db->protect_identifiers('index').'
            FROM '.cms_table_name('main_quicklink').' WHERE '.
            $this->db->protect_identifiers('index').'<'.$this_index;
        $query = $this->db->query($SQL);
        $row = $query->row();
        if (intval($row->index) > 0) {
            $neighbor_index = intval($row->index);

            // update neighbor
            $data = array('index' => $this_index);
            $where = $this->db->protect_identifiers('index').' = '.$neighbor_index;
            $this->db->update(cms_table_name('main_quicklink'), $data, $where);

            // update current row
            $data = array('index' => $neighbor_index);
            $where = array('quicklink_id' => $this_quicklink_id);
            $this->db->update(cms_table_name('main_quicklink'), $data, $where);
        }
    }

    /**
     * @author  go frendi
     *
     * @param   int navigation id
     * @desc    move quicklink down
     */
    public function cms_do_move_down_quicklink($quicklink_id)
    {
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
        $SQL = '
            SELECT min('.$this->db->protect_identifiers('index').') AS '.$this->db->protect_identifiers('index').'
            FROM '.cms_table_name('main_quicklink').' WHERE '.
            $this->db->protect_identifiers('index').'>'.$this_index;
        $query = $this->db->query($SQL);
        $row = $query->row();
        if (intval($row->index) > 0) {
            $neighbor_index = intval($row->index);

            // update neighbor
            $data = array('index' => $this_index);
            $where = $this->db->protect_identifiers('index').' = '.$neighbor_index;
            $this->db->update(cms_table_name('main_quicklink'), $data, $where);

            // update current row
            $data = array('index' => $neighbor_index);
            $where = array('quicklink_id' => $this_quicklink_id);
            $this->db->update(cms_table_name('main_quicklink'), $data, $where);
        }
    }

    /**
     * @author  go frendi
     *
     * @param   string widget_name
     * @desc    move widget up
     */
    public function cms_do_move_up_widget($widget_name)
    {
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
        $SQL = '
            SELECT max('.$this->db->protect_identifiers('index').') AS '.$this->db->protect_identifiers('index').'
            FROM '.cms_table_name('main_widget').' WHERE '.
            $this->db->protect_identifiers('index').'<'.$this_index;
        $query = $this->db->query($SQL);
        $row = $query->row();
        if (intval($row->index) > 0) {
            $neighbor_index = intval($row->index);

            // update neighbor
            $data = array('index' => $this_index);
            $where = $this->db->protect_identifiers('index').' = '.$neighbor_index;
            $this->db->update(cms_table_name('main_widget'), $data, $where);

            // update current row
            $data = array('index' => $neighbor_index);
            $where = array('widget_id' => $this_widget_id);
            $this->db->update(cms_table_name('main_widget'), $data, $where);
        }
    }

    /**
     * @author  go frendi
     *
     * @param   string widget_name
     * @desc    move widget down
     */
    public function cms_do_move_down_widget($widget_name)
    {
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
        $SQL = '
            SELECT min('.$this->db->protect_identifiers('index').') AS '.$this->db->protect_identifiers('index').'
            FROM '.cms_table_name('main_widget').' WHERE '.
            $this->db->protect_identifiers('index').'>'.$this_index;
        $query = $this->db->query($SQL);
        $row = $query->row();
        if (intval($row->index) > 0) {
            $neighbor_index = intval($row->index);

            // update neighbor
            $data = array('index' => $this_index);
            $where = $this->db->protect_identifiers('index').' = '.$neighbor_index;
            $this->db->update(cms_table_name('main_widget'), $data, $where);

            // update current row
            $data = array('index' => $neighbor_index);
            $where = array('widget_id' => $this_widget_id);
            $this->db->update(cms_table_name('main_widget'), $data, $where);
        }
    }

    /**
     * @author  go frendi
     *
     * @param   string navigation_name
     * @desc    move navigation up
     */
    public function cms_do_move_up_navigation($navigation_name)
    {
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
            $whereParentId = '(parent_id IS NULL)';
        }
        $SQL = '
            SELECT max('.$this->db->protect_identifiers('index').') AS '.$this->db->protect_identifiers('index').'
            FROM '.cms_table_name('main_navigation')." WHERE $whereParentId AND ".
            $this->db->protect_identifiers('index').'<'.$this_index;
        $query = $this->db->query($SQL);
        $row = $query->row();
        if (intval($row->index) > 0) {
            $neighbor_index = intval($row->index);

            // update neighbor
            $data = array('index' => $this_index);
            $where = $whereParentId.' AND '.$this->db->protect_identifiers('index').' = '.$neighbor_index;
            $this->db->update(cms_table_name('main_navigation'), $data, $where);

            // update current row
            $data = array('index' => $neighbor_index);
            $where = array('navigation_id' => $this_navigation_id);
            $this->db->update(cms_table_name('main_navigation'), $data, $where);
        }
    }

    /**
     * @author  go frendi
     *
     * @param   string navigation_name
     * @desc    move navigation down
     */
    public function cms_do_move_down_navigation($navigation_name)
    {
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
            $whereParentId = '(parent_id IS NULL)';
        }
        $SQL = '
            SELECT min('.$this->db->protect_identifiers('index').') AS '.$this->db->protect_identifiers('index').'
            FROM '.cms_table_name('main_navigation')." WHERE $whereParentId AND ".
            $this->db->protect_identifiers('index').'>'.$this_index;
        $query = $this->db->query($SQL);
        $row = $query->row();
        if (intval($row->index) > 0) {
            $neighbor_index = intval($row->index);

            // update neighbor
            $data = array('index' => $this_index);
            $where = $whereParentId.' AND '.$this->db->protect_identifiers('index').' = '.$neighbor_index;
            $this->db->update(cms_table_name('main_navigation'), $data, $where);
            // update current row
            $data = array('index' => $neighbor_index);
            $where = array('navigation_id' => $this_navigation_id);
            $this->db->update(cms_table_name('main_navigation'), $data, $where);
        }
    }

    public function cms_user_table_name()
    {
        include APPPATH.'config/main/cms_config.php';
        $table_prefix = $config['__cms_table_prefix'];

        return cms_table_name('main_user', $table_prefix);
    }

    public function cms_chipper()
    {
        include APPPATH.'config/main/cms_config.php';

        return $config['__cms_chipper'];
    }

    /**
     * @author  go frendi
     *
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
        $data = array(
            'user_name' => $user_name,
            'email' => $email,
            'real_name' => $real_name,
            'password' => CMS_SUBSITE == '' ?
                cms_md5($password, $this->cms_chipper()) :
                cms_md5($password),
            'active' => $activation == 'automatic',
            'subsite' => CMS_SUBSITE == '' ? null : CMS_SUBSITE,
        );
        $this->db->insert($this->cms_user_table_name(), $data);
        $user_id = $this->db->insert_id();
        // send activation code if needed
        if ($activation == 'by_mail') {
            $this->cms_generate_activation_code($user_name, true, 'SIGNUP');
        } elseif ($activation == 'automatic') {
            $this->cms_do_login($user_name, $password);
        }
        return $user_id;
    }

    /**
     * @author  go frendi
     *
     * @param   string content
     * @desc    flash content to be served as metadata on next call of $this->view in controller
     */
    public function cms_flash_metadata($content)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        if (!isset($_SESSION['__cms_flash_metadata'])) {
            $_SESSION['__cms_flash_metadata'] = '';
        }
        $_SESSION['__cms_flash_metadata'] .= $content;
    }

    /**
     * @author  go frendi
     *
     * @param   string user_id
     * @param   string email
     * @param   string real_name
     * @param   string password
     * @desc    change current profile (user_name, email, real_name and password)
     */
    public function cms_do_change_profile($email, $real_name, $password = null, $user_id = null)
    {
        $user_id = $user_id === null ? $this->cms_user_id() : $user_id;
        $query = $this->db->select('user_id, user_name, subsite')
            ->from($this->cms_user_table_name())
            ->where('user_id', $user_id)
            ->get();
        if ($query->num_rows() > 0) {
            $user_row = $query->row();
            $user_name = $user_row->user_name;
            $user_subsite = $user_row->subsite;
            // update current user table
            $data = array(
                'email' => $email,
                'real_name' => $real_name,
                'active' => 1,
            );
            if (isset($password)) {
                // if user is defined in subsite then use current chipper
                if ($user_subsite == null) {
                    $data['password'] = cms_md5($password, $this->cms_chipper());
                } else {
                    $data['password'] = cms_md5($password);
                }
            }
            $where = array(
                'user_id' => $user_id,
            );
            $this->db->update($this->cms_user_table_name(), $data, $where);

            if ($user_id == $this->cms_user_id()) {
                $this->cms_user_name($user_name);
                $this->cms_user_email($email);
                $this->cms_user_real_name($real_name);
            }
        }
    }

    /**
     * @author  go frendi
     *
     * @param   string module_name
     *
     * @return bool
     * @desc    checked if module installed
     */
    public function cms_is_module_active($module_name)
    {
        if (!self::$__cms_model_properties['is_module_path_cached']) {
            $this->cms_adjust_module();
        }
        if (array_key_exists($module_name, self::$__cms_model_properties['module_path'])) {
            return true;
        }

        return false;
    }

    /**
     * @author  go frendi
     *
     * @return mixed
     * @desc    get module list
     */
    public function cms_get_module_list($keyword = null)
    {
        $this->load->helper('directory');
        $directories = directory_map(FCPATH.'modules', 1);
        sort($directories);
        // module and existing_module_name
        $existing_module_name = array();
        $module = array();
        foreach ($directories as $directory) {
            $directory = str_replace(array('/', '\\'), '', $directory);
            if (!is_dir(FCPATH.'modules/'.$directory)) {
                continue;
            }

            if (!file_exists(FCPATH.'modules/'.$directory.'/description.txt')) {
                continue;
            }

            $files = directory_map(FCPATH.'modules/'.$directory.'/controllers', 1);
            $module_controllers = array();
            if($files){
                foreach ($files as $file) {
                    $filename_array = explode('.', $file);
                    $extension = $filename_array[count($filename_array) - 1];
                    unset($filename_array[count($filename_array) - 1]);
                    $filename = implode('.', $filename_array);
                    if ($extension == 'php' && $filename != 'Info') {
                        $module_controllers[] = $filename;
                    }
                }
            }
            $module_name = $this->cms_module_name($directory);
            $json = file_get_contents(FCPATH.'modules/'.$directory.'/description.txt');
            $module_info = @json_decode($json, true);
            $module_info = $module_info === null ? array() : $module_info;
            // default values of module info
            $default_values = array(
                'name' => '',
                'description' => '',
                'dependencies' => array(),
                'version' => '0.0.0',
                'activate' => 'info/activate',
                'deactivate' => 'info/deactivate',
                'upgrade' => 'info/upgrade',
                'public' => TRUE,
                'published' => TRUE,
            );
            // if no default values exists, provide default values
            foreach ($default_values as $key => $value) {
                if (!array_key_exists($key, $module_info)) {
                    $module_info[$key] = $value;
                }
            }
            $module_name = $module_info['name'];
            $active = $this->cms_is_module_active($module_name);
            $description = $module_info['description'];
            $dependencies = $module_info['dependencies'];
            $old = $this->cms_module_version($module_name) < $module_info['version'];
            $current_version = $module_info['version'];
            $activate_link = site_url($directory.'/'.$module_info['activate']);
            $deactivate_link = site_url($directory.'/'.$module_info['deactivate']);
            $upgrade_link = site_url($directory.'/'.$module_info['upgrade']);
            $old_version = $this->cms_module_version($module_name);
            $public = $module_info['public'];
            // searching
            if ($keyword === null || ($keyword !== null && (
                stripos($module_name, $keyword) !== false ||
                stripos($directory, $keyword) !== false ||
                stripos($description, $keyword) !== false
            ))) {
                // Subsite should not be allowed to install multisite
                if(CMS_SUBSITE != '' && ($module_name == 'gofrendi.noCMS.multisite' || $module_name == 'gofrendi.noCMS.nordrassil')){
                    continue;
                }
                // if module_name in existing_module_name skip it
                if(in_array($module_name, $existing_module_name)){
                    continue;
                }else{
                    $existing_module_name[] = $module_name;
                }
                // see if module is accessible
                $published = TRUE;
                if (!$public && CMS_SUBSITE != '') {
                    $published = in_array($module_name, self::$__cms_model_properties['private_modules']);
                }
                // unpublished module should not be shown
                $module[] = array(
                    'module_name' => $module_name,
                    'module_path' => $directory,
                    'active' => $active,
                    'description' => $description,
                    'dependencies' => $dependencies,
                    'old' => $old,
                    'old_version' => $old_version,
                    'current_version' => $current_version,
                    'controllers' => $module_controllers,
                    'activate_link' => $activate_link,
                    'deactivate_link' => $deactivate_link,
                    'upgrade_link' => $upgrade_link,
                    'public' => $public,
                    'published' => $published,
                );
            }
        }
        return $module;
    }

    public function cms_adjust_module()
    {
        $query = $this->db->select('module_id, version, module_name, module_path')
            ->from(cms_table_name('main_module'))
            ->get();
        foreach ($query->result() as $row) {
            $module_name = $row->module_name;
            if (file_exists(FCPATH.'modules/'.$row->module_path.'/description.txt')) {
                $json = file_get_contents(FCPATH.'modules/'.$row->module_path.'/description.txt');
                $module_info = @json_decode($json, true);
                $module_info = $module_info === null ? array() : $module_info;

                if (array_key_exists('name', $module_info)) {
                    $module_name = $module_info['name'];
                    if ($row->module_name != $module_name) {
                        $this->db->update(cms_table_name('main_module'),
                                array('module_name' => $module_name),
                                array('module_id' => $row->module_id)
                            );
                    }
                }
            }
            self::$__cms_model_properties['module_version'][$module_name] = $row->version;
            self::$__cms_model_properties['module_name'][$row->module_path] = $module_name;
            self::$__cms_model_properties['module_path'][$row->module_name] = $row->module_path;
        }
        self::$__cms_model_properties['is_module_version_cached'] = true;
        self::$__cms_model_properties['is_module_name_cached'] = true;
        self::$__cms_model_properties['is_module_path_cached'] = true;
    }

    public function cms_module_version($module_name = null, $new_version = null)
    {
        if ($new_version !== null) {
            $this->db->update(cms_table_name('main_module'),
                array('version' => $new_version),
                array('module_name' => $module_name));
        }
        if (!self::$__cms_model_properties['is_module_version_cached']) {
            $this->cms_adjust_module();
        }
        if (array_key_exists($module_name, self::$__cms_model_properties['module_version'])) {
            return self::$__cms_model_properties['module_version'][$module_name];
        }

        return '0.0.0';
    }

    /**
     * @author  go frendi
     *
     * @param   string module_name
     *
     * @return string
     * @desc    get module_path (folder name) of specified module_name (name space)
     */
    public function cms_module_path($module_name = null)
    {
        // hack module path by changing the session, don't forget to unset !!!
        $module_path = '';
        if ($module_name === null) {
            if ($this->__controller_module_path != null) {
                // no_cms_model and no_cms_autoupdate_model is called by instance controller
                // thus the position might not be represent the current module path
                // in this case we need data from controller
                $module_path = $this->__controller_module_path;
            } else {
                $reflector = new ReflectionObject($this);
                $file_name = $reflector->getFilename();
                if (strpos($file_name, FCPATH.'modules') === 0) {
                    $file_name = trim(str_replace(FCPATH.'modules', '', $file_name), DIRECTORY_SEPARATOR);
                    $file_name_part = explode(DIRECTORY_SEPARATOR, $file_name);
                    if (count($file_name_part) >= 2) {
                        $module_path = $file_name_part[0];
                    }
                }
            }
        } else {
            if (!self::$__cms_model_properties['is_module_path_cached']) {
                $this->cms_adjust_module();
            }
            if (array_key_exists($module_name, self::$__cms_model_properties['module_path'])) {
                $module_path = self::$__cms_model_properties['module_path'][$module_name];
            }
        }

        return $module_path;
    }

    /**
     * @author  go frendi
     *
     * @param   string module_path
     *
     * @return string
     * @desc    get module_name (name space) of specified module_path (folder name)
     */
    public function cms_module_name($module_path = null)
    {
        if (!isset($module_path) || is_null($module_path)) {
            $module_path = $this->cms_module_path();
        }

        if (!self::$__cms_model_properties['is_module_name_cached']) {
            $this->cms_adjust_module();
        }
        if (array_key_exists($module_path, self::$__cms_model_properties['module_name'])) {
            return self::$__cms_model_properties['module_name'][$module_path];
        }

        return '';
    }

    /**
     * @author  go frendi
     *
     * @return mixed
     * @desc    get theme list
     */
    public function cms_get_theme_list($keyword = null)
    {
        $this->load->helper('directory');
        $directories = directory_map(FCPATH.'themes', 1);
        sort($directories);
        $themes = array();
        foreach ($directories as $directory) {
            $directory = str_replace(array('/', '\\'), '', $directory);
            if (!is_dir(FCPATH.'themes/'.$directory)) {
                continue;
            }

            $theme_name = $directory;

            $description = '';
            $published = FALSE;
            $public = FALSE;
            // description file should be exists, if not, make it
            $description_file = FCPATH.'themes/'.$directory.'/description.txt';
            if(!file_exists($description_file)){
                file_put_contents($description_file, '{"public":false,"description":"Just another theme"}');
            }
            $config = @json_decode(file_get_contents($description_file), TRUE);
            if(array_key_exists('public', $config)){
                $public = $config['public'];
            }
            if(array_key_exists('description', $config)){
                $description = $config['description'];
            }

            $published = TRUE;
            if (!$public && CMS_SUBSITE != '') {
                $published = in_array($theme_name, self::$__cms_model_properties['private_themes']);
            }

            if ($keyword === null  || ($keyword !== null && (stripos($directory, $keyword) !== false || stripos($description, $keyword) !== false))) {
                $themes[] = array(
                    'name' => $directory,
                    'public' => $public,
                    'published' => $published,
                    'path' => $directory,
                    'description' => $description,
                    'used' => $this->cms_get_config('site_theme') == $theme_name,
                );
            }
        }
        // the currently used theme should be on the top
        for ($i = 0; $i < count($themes); ++$i) {
            if ($themes[$i]['used']) {
                if ($i != 0) {
                    $new_themes = array();
                    $current_theme = $themes[$i];
                    $new_themes[] = $current_theme;
                    for ($j = 0; $j < count($themes); ++$j) {
                        if ($j != $i) {
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
     * @author  go frendi
     *
     * @param   string identity
     * @param    bool send_mail
     * @param   string reason (FORGOT, SIGNUP)
     *
     * @return bool
     * @desc    generate activation code, and send email to applicant
     */
    public function cms_generate_activation_code($identity, $send_mail = false, $reason = 'FORGOT')
    {
        // if generate activation reason is "FORGOT", then user should be active
        $where_active = '1=1';
        if ($reason == 'FORGOT') {
            $where_active = 'active = TRUE';
        }
        // generate query
        $query = $this->db->query('SELECT user_name, real_name, user_id, email FROM '.$this->cms_user_table_name()." WHERE
                    (user_name = '".addslashes($identity)."' OR email = '".addslashes($identity)."') AND
                    $where_active");
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $user_id = $row->user_id;
            $email_to_address = $row->email;
            $user_name = $row->user_name;
            $real_name = $row->real_name;
            $activation_code = random_string();

            //update, add activation_code
            $data = array(
                'activation_code' => cms_md5($activation_code, $this->cms_chipper()),
            );
            // log_message('error', 'Activation code generated : '.$activation_code);
            $where = array(
                'user_id' => $user_id,
            );
            $this->db->update($this->cms_user_table_name(), $data, $where);
            $this->load->library('email');
            if ($send_mail) {
                //prepare activation email to user
                $email_from_address = $this->cms_get_config('cms_email_reply_address');
                $email_from_name = $this->cms_get_config('cms_email_reply_name');

                $email_subject = 'Account Activation';
                $email_message = 'Dear, {{ user_real_name }}<br />Click <a href="{{ site_url }}main/activate/{{ activation_code }}">{{ site_url }}main/activate/{{ activation_code }}</a> to activate your account';
                if (strtoupper($reason) == 'FORGOT') {
                    $email_subject = $this->cms_get_config('cms_email_forgot_subject', true);
                    $email_message = $this->cms_get_config('cms_email_forgot_message', true);
                } elseif (strtoupper($reason) == 'SIGNUP') {
                    $email_subject = $this->cms_get_config('cms_email_signup_subject', true);
                    $email_message = $this->cms_get_config('cms_email_signup_message', true);
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
     * @author  go frendi
     *
     * @param   string activation_code
     * @param   string new_password
     *
     * @return bool success
     * @desc    activate user
     */
    public function cms_activate_account($activation_code, $new_password = null)
    {
        $query = $this->db->query('SELECT user_id FROM '.$this->cms_user_table_name()." WHERE
                    (activation_code = '".cms_md5($activation_code, $this->cms_chipper())."')");
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $user_id = $row->user_id;
            $data = array(
                'activation_code' => null,
                'active' => true,
            );
            if (isset($new_password)) {
                $data['password'] = cms_md5($new_password, $this->cms_chipper());
            }

            $where = array(
                'user_id' => $user_id,
            );
            $this->db->update($this->cms_user_table_name(), $data, $where);

            $this->_cms_set_user_subsite_activation($user_id, 1);

            return true;
        } else {
            return false;
        }
    }

    public function _cms_set_user_subsite_activation($user_id, $active)
    {
        if ($this->cms_is_module_active('gofrendi.noCMS.multisite')) {
            $module_path = $this->cms_module_path('gofrendi.noCMS.multisite');
            //$this->cms_override_module_path($module_path);
            $data = array('active' => $active);
            $where = array('user_id' => $user_id);
            $this->db->update($this->cms_complete_table_name('subsite', 'gofrendi.noCMS.multisite'), $data, $where);
            $this->load->model($this->cms_module_path('gofrendi.noCMS.multisite').'/subsite_model');
            $this->subsite_model->update_configs();
            //$this->cms_reset_overridden_module_path();
        }
    }

    /**
     * @author  go frendi
     *
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
        $config['useragent'] = (string) $this->cms_get_config('cms_email_useragent');
        $config['protocol'] = (string) $this->cms_get_config('cms_email_protocol');
        $config['mailpath'] = (string) $this->cms_get_config('cms_email_mailpath');
        $config['smtp_host'] = (string) $this->cms_get_config('cms_email_smtp_host');
        $config['smtp_user'] = (string) $this->cms_get_config('cms_email_smtp_user');
        $config['smtp_pass'] = (string) cms_decode($this->cms_get_config('cms_email_smtp_pass'));
        $config['smtp_port'] = (integer) $this->cms_get_config('cms_email_smtp_port');
        $config['smtp_timeout'] = (integer) $this->cms_get_config('cms_email_smtp_timeout');
        $config['wordwrap'] = (boolean) $this->cms_get_config('cms_email_wordwrap');
        $config['wrapchars'] = (integer) $this->cms_get_config('cms_email_wrapchars');
        $config['mailtype'] = (string) $this->cms_get_config('cms_email_mailtype');
        $config['charset'] = (string) $this->cms_get_config('cms_email_charset');
        $config['validate'] = (boolean) $this->cms_get_config('cms_email_validate');
        $config['priority'] = (integer) $this->cms_get_config('cms_email_priority');
        $config['crlf'] = "\r\n";
        $config['newline'] = "\r\n";
        $config['bcc_batch_mode'] = (boolean) $this->cms_get_config('cms_email_bcc_batch_mode');
        $config['bcc_batch_size'] = (integer) $this->cms_get_config('cms_email_bcc_batch_size');


        $ssl = $this->email->smtp_crypto === 'ssl' ? 'ssl://' : '';
        // if protocol is (not smtp) or (is smtp and able to connect)
        if ($config['protocol'] != 'smtp' || ($config['protocol'] == 'smtp' && $this->cms_is_connect($ssl.$config['smtp_host'], $config['smtp_port']))) {
            $message = $this->cms_parse_keyword($message);
            $subject = $this->cms_parse_keyword($subject);

            $this->email->initialize($config);
            $this->email->from($from_address, $from_name);
            $this->email->to($to_address);
            $this->email->subject($subject);
            $this->email->message($message);
            try {
                $success = $this->email->send();
                log_message('debug', $this->email->print_debugger());
            } catch (Error $error) {
                $success = false;
                log_message('error', $this->email->print_debugger());
            }
        } else {
            $success = false;
            log_message('error', 'Connection to '.$ssl.$config['smtp_host'].':'.$config['smtp_port'].' is impossible');
        }

        return $success;
    }

    public function cms_resize_image($file_name, $nWidth, $nHeight, $new_file_name = NULL)
    {
        // original code: http://stackoverflow.com/questions/16977853/resize-images-with-transparency-in-php
        if($new_file_name === NULL){
            $new_file_name = $file_name;
        }

        // read image
        $im = @imagecreatefrompng($file_name);
        if ($im) {
            $srcWidth = imagesx($im);
            $srcHeight = imagesy($im);

            // decide ratio
            $widthRatio = $nWidth / $srcWidth;
            $heightRatio = $nHeight / $srcHeight;
            if ($widthRatio > $heightRatio) {
                $ratio = $heightRatio;
            } else {
                $ratio = $heightRatio;
            }
            $nWidth = $srcWidth * $ratio;
            $nHeight = $srcHeight * $ratio;

            // make new image
            $newImg = imagecreatetruecolor($nWidth, $nHeight);
            imagealphablending($newImg, false);
            imagesavealpha($newImg, true);
            $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
            imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
            imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight,
                $srcWidth, $srcHeight);

            // write new image
            imagepng($newImg, $new_file_name);
        } else {
            $this->load->library('image_moo');
            $this->image_moo->load($file_name)->resize($nWidth, $nHeight)->save($new_file_name, true);
        }
    }

    /**
     * @author  go frendi
     *
     * @param   string activation_code
     *
     * @return bool
     * @desc    validate activation_code
     */
    public function cms_valid_activation_code($activation_code)
    {
        $query = $this->db->query('SELECT activation_code FROM '.$this->cms_user_table_name()." WHERE
                    (activation_code = '".cms_md5($activation_code, $this->cms_chipper())."') AND
                    (activation_code IS NOT NULL)");
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @author  go frendi
     *
     * @param   string name
     * @param   string value
     * @param   string description
     * @desc    set config variable
     */
    public function cms_set_config($name, $value, $description = null)
    {
        $query = $this->db->select('config_id')
            ->from(cms_table_name('main_config'))
            ->where('config_name', $name)
            ->get();
        if ($query->num_rows() > 0) {
            $data = array(
                'value' => $value,
            );
            if (isset($description)) {
                $data['description'] = $description;
            }
            $where = array(
                'config_name' => $name,
            );
            $this->db->update(cms_table_name('main_config'), $data, $where);
        } else {
            $data = array(
                'value' => $value,
                'config_name' => $name,
            );
            if (isset($description)) {
                $data['description'] = $description;
            }
            $this->db->insert(cms_table_name('main_config'), $data);
        }
        // cms_config($name, $value);
        // add-subsite-on-register setting influence whether main_register route exists or not
        if ($name == 'cms_add_subsite_on_register') {
            if ($this->cms_is_module_active('gofrendi.noCMS.multisite')) {
                $module_path = $this->cms_module_path('gofrendi.noCMS.multisite');
                // create or delete route
                if (strtoupper($value) == 'TRUE') {
                    $this->cms_add_route('main/register', $module_path.'/multisite/register');
                } else {
                    $this->cms_remove_route('main/register');
                }
            } else {
                $this->cms_remove_route('main/register');
            }
        }
        // save as __cms_model_properties too
        self::$__cms_model_properties['config'][$name] = $value;
    }

    /**
     * @author  go frendi
     *
     * @param   string name
     * @desc    unset configuration variable
     */
    public function cms_unset_config($name)
    {
        $where = array(
            'config_name' => $name,
        );
        $query = $this->db->delete(cms_table_name('main_config'), $where);
    }

    /**
     * @author  go frendi
     *
     * @param   string name, bool raw
     *
     * @return string
     * @desc    get configuration variable
     */
    public function cms_get_config($name, $raw = false)
    {
        $value = cms_config($name);
        if ($value === null || !$value) {
            if (!self::$__cms_model_properties['is_config_cached']) {
                $query = $this->db->select('value, config_name')
                    ->from(cms_table_name('main_config'))
                    ->get();
                foreach ($query->result() as $row) {
                    // get value and config_name
                    $value = $row->value;
                    $value = $value === null? '' : $value;
                    $config_name = $row->config_name;
                    // save to cache
                    self::$__cms_model_properties['config'][$config_name] = $value;
                    //cms_config($config_name, $value);
                    if ($config_name == $name) {
                        $found = true;
                    }
                }
                self::$__cms_model_properties['is_config_cached'] = true;
            }
            if (array_key_exists($name, self::$__cms_model_properties['config'])) {
                $value = self::$__cms_model_properties['config'][$name];
            } else {
                $value = null;
            }
        }

        // if raw is false, then don't parse keyword
        if (!$raw && isset($value)) {
            $value = $this->cms_parse_keyword($value);
        }

        return $value;
    }

    private function __cms_cache_layout(){
        if (!self::$__cms_model_properties['is_layout_cached']) {
            $query = $this->db->select('layout_id, layout_name, template')
                ->from(cms_table_name('main_layout'))
                ->get();
            foreach ($query->result() as $row) {
                // save to cache
                self::$__cms_model_properties['layout'][$row->layout_name] = $row->template;
                self::$__cms_model_properties['layout_id'][$row->layout_name] = $row->layout_id;
            }
            self::$__cms_model_properties['is_layout_cached'] = true;
        }
    }

    public function cms_get_layout(){
        $this->__cms_cache_layout();
        $result = array();
        foreach(self::$__cms_model_properties['layout'] as $key=>$val){
            $result[] = $key;
        }
        return $result;
    }

    public function cms_get_layout_template($layout){
        $this->__cms_cache_layout();
        return self::$__cms_model_properties['layout'][$layout];
    }

    public function cms_get_layout_id($layout){
        $this->__cms_cache_layout();
        return self::$__cms_model_properties['layout_id'][$layout];
    }

    public function cms_layout_exists($layout){
        $this->__cms_cache_layout();
        return array_key_exists($layout, self::$__cms_model_properties['layout']);
    }

    /**
     * @author    go frendi
     *
     * @param    string language
     *
     * @return string language
     * @desc    set language for this session only
     */
    public function cms_language($language = null)
    {
        if (isset($language)) {
            $this->cms_ci_session('cms_lang', $language);
        } else {
            $language = $this->cms_get_user_language();
            if($language == '' || $language == NULL){
                $language = $this->cms_ci_session('cms_lang');
                if (!$language) {
                    $language = $this->cms_get_config('site_language', true);
                    $this->cms_ci_session('cms_lang', $language);
                }
            }

            return $language;
        }
    }

    /**
     * @author    go frendi
     *
     * @return array list of available languages
     * @desc    get available languages
     */
    public function cms_language_list()
    {
        // look for available language which are probably not registered
        if (!isset($_SESSION)) {
            session_start();
        }
        if (!isset($_SESSION['__cms_language_uptodate'])) {
            $this->load->helper('file');
            $new_lang = array();
            $language_list = get_filenames(APPPATH.'../assets/nocms/languages');
            foreach ($language_list as $language) {
                if (preg_match('/\.php$/i', $language)) {
                    $lang = str_ireplace('.php', '', $language);
                    $exist = $this->db->select('code')->from(cms_table_name('main_language'))
                        ->where('code', $lang)->get()->num_rows() > 0;
                    if (!$exist) {
                        $new_lang[] = $lang;
                    }
                }
            }
            $module_list = $this->cms_get_module_list();
            $module_list[] = array('module_path' => 'main');
            foreach ($module_list as $module) {
                $directory = $module['module_path'];
                $module_language_list = get_filenames(APPPATH.'../modules/'.$directory.'/assets/languages');
                if ($module_language_list === false) {
                    continue;
                }
                foreach ($module_language_list as $module_language) {
                    if (preg_match('/\.php$/i', $module_language)) {
                        $module_language = str_ireplace('.php', '', $module_language);
                        $exist = $this->db->select('code')->from(cms_table_name('main_language'))
                            ->where('code', $module_language)->get()->num_rows() > 0;
                        if (!$exist && !in_array($module_language, $new_lang)) {
                            $new_lang[] = $module_language;
                        }
                    }
                }
            }
            // add the language to database
            foreach ($new_lang as $lang) {
                $this->db->insert(cms_table_name('language'), array('name' => $lang, 'code' => $lang));
            }
            $_SESSION['__cms_language_uptodate'] = true;
        }
        // grab it
        $result = $this->db->select('name,code,iso_code')
            ->from(cms_table_name('main_language'))
            ->order_by('name')
            ->get()->result();

        return $result;
    }

    /**
     * @author  go frendi
     *
     * @return mixed
     * @desc    get all language dictionary
     */
    public function cms_language_dictionary()
    {
        $language = $this->cms_language();
        if (!self::$__cms_model_properties['is_language_dictionary_cached']) {
            $lang = array();

            // language setting from all modules but this current module
            $modules = $this->cms_get_module_list();
            foreach ($modules as $module) {
                $module_path = $module['module_path'];
                if ($module_path != $this->cms_module_path()) {
                    $local_language_file = APPPATH."../modules/$module_path/assets/languages/$language.php";
                    if (file_exists($local_language_file)) {
                        include $local_language_file;
                    }
                }
            }
            // nocms main module language setting override previous language setting
            $language_file = APPPATH."../modules/main/assets/languages/$language.php";
            if (file_exists($language_file)) {
                include $language_file;
            }
            // global nocms language setting override previous language setting
            $language_file = APPPATH."../assets/nocms/languages/$language.php";
            if (file_exists($language_file)) {
                include $language_file;
            }
            // language setting from current module
            $module_path = $this->cms_module_path();
            $local_language_file = APPPATH."../modules/$module_path/assets/languages/$language.php";
            if (file_exists($local_language_file)) {
                include $local_language_file;
            }

            $result = $this->db->select('key, translation')
                ->from(cms_table_name('main_detail_language'))
                ->join(cms_table_name('main_language'), cms_table_name('main_detail_language').'.id_language = '.cms_table_name('main_language').'.language_id')
                ->where('name', $this->cms_language())
                ->get()->result();
            foreach ($result as $row) {
                $lang[$row->key] = $row->translation;
            }

            self::$__cms_model_properties['language_dictionary'] = $lang;
            self::$__cms_model_properties['is_language_dictionary_cached'] = true;
        }

        return self::$__cms_model_properties['language_dictionary'];
    }

    /**
     * @author  go frendi
     *
     * @param   string key
     *
     * @return string
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
     * @author go frendi
     *
     * @param  string value
     *
     * @return string
     * @desc   parse keyword like {{ site_url  }} , {{ base_url }} , {{ user_name }} , {{ language }}
     */
    public function cms_parse_keyword($value)
    {
        $value = $this->cms_escape_template($value);

        if (strpos($value, '{{ ') !== false) {
            $pattern = array();
            $replacement = array();

            // user_id
            $pattern[] = "/\{\{ user_id \}\}/si";
            $replacement[] = $this->cms_user_id();

            // user_name
            $pattern[] = "/\{\{ user_name \}\}/si";
            $replacement[] = $this->cms_user_name();

            // user_real_name
            $pattern[] = "/\{\{ user_real_name \}\}/si";
            $replacement[] = $this->cms_user_real_name();

            // user_email
            $pattern[] = "/\{\{ user_email \}\}/si";
            $replacement[] = $this->cms_user_email();

            $super_admin = $this->cms_get_super_admin();

            // admin name
            $pattern[] = "/\{\{ admin_user_name \}\}/si";
            $replacement[] = $super_admin['user_name'];

            // admin real name
            $pattern[] = "/\{\{ admin_real_name \}\}/si";
            $replacement[] = $super_admin['real_name'];

            // admin email
            $pattern[] = "/\{\{ admin_email \}\}/si";
            $replacement[] = $super_admin['email'];

            // site_url
            $site_url = site_url();
            if ($site_url[strlen($site_url) - 1] != '/') {
                $site_url .= '/';
            }
            $pattern[] = '/\{\{ site_url \}\}/si';
            $replacement[] = $site_url;

            // base_url
            $base_url = base_url();
            if ($base_url[strlen($base_url) - 1] != '/') {
                $base_url .= '/';
            }
            if (USE_SUBDOMAIN && CMS_SUBSITE != '' && !USE_ALIAS) {
                $base_url = str_ireplace('://'.CMS_SUBSITE.'.',  '://', $base_url);
            }
            $pattern[] = '/\{\{ base_url \}\}/si';
            $replacement[] = $base_url;

            // module path, name, site url and base url
            $module_path = $this->cms_module_path();
            $module_name = $this->cms_module_name($module_path);
            $module_site_url = site_url($module_path);
            $module_base_url = base_url('modules/'.$module_path);
            if ($module_site_url[strlen($module_site_url) - 1] != '/') {
                $module_site_url .= '/';
            }
            if ($module_base_url[strlen($module_base_url) - 1] != '/') {
                $module_base_url .= '/';
            }
            $pattern[] = '/\{\{ module_path \}\}/si';
            $replacement[] = $module_path;
            $pattern[] = '/\{\{ module_site_url \}\}/si';
            $replacement[] = $module_site_url;
            $pattern[] = '/\{\{ module_base_url \}\}/si';
            $replacement[] = $module_base_url;
            $pattern[] = '/\{\{ module_name \}\}/si';
            $replacement[] = $module_name;
            // also allow these syntaxes: {{ module_path:module_name }}, {{ module_site_url:module_name }}, and {{ module_base_url:module_name }}
            foreach($this->cms_get_module_list() as $module){
                if($module['active'] && $module['published']){
                    $module_name = $module['module_name'];
                    $module_path = $module['module_path'];
                    $module_site_url = site_url($module_path);
                    $module_base_url = base_url('modules/'.$module_path);
                    if ($module_site_url[strlen($module_site_url) - 1] != '/') {
                        $module_site_url .= '/';
                    }
                    if ($module_base_url[strlen($module_base_url) - 1] != '/') {
                        $module_base_url .= '/';
                    }
                    $pattern[] = '/\{\{ module_path:'.$module_name.' \}\}/si';
                    $replacement[] = $module_path;
                    $pattern[] = '/\{\{ module_site_url:'.$module_name.' \}\}/si';
                    $replacement[] = $module_site_url;
                    $pattern[] = '/\{\{ module_base_url:'.$module_name.' \}\}/si';
                    $replacement[] = $module_base_url;
                }
            }

            // language
            $pattern[] = '/\{\{ language \}\}/si';
            $replacement[] = $this->cms_language();

            // execute regex
            $value = preg_replace($pattern, $replacement, $value);
        }

        // translate language
        if (strpos($value, '{{ ') !== false) {
            $pattern = '/\{\{ language:(.*?) \}\}/si';
            // execute regex
            $value = preg_replace_callback($pattern, array(
                $this,
                '__cms_preg_replace_callback_lang',
            ), $value);
        }

        // if language, elif
        if (strpos($value, '{{ ') !== false) {
            $language = $this->cms_language();
            $pattern = array();
            $pattern[] = "/\{\{ if_language:$language \}\}(.*?)\{\{ elif_language:.*?\{\{ end_if \}\}/si";
            $pattern[] = "/\{\{ if_language:$language \}\}(.*?)\{\{ else \}\}.*?\{\{ end_if \}\}/si";
            $pattern[] = "/\{\{ if_language:$language \}\}(.*?)\{\{ end_if \}\}/si";
            $pattern[] = "/\{\{ if_language:.*?\{\{ elif_language:$language \}\}(.*?)\{\{ elif_language:.*?\{\{ end_if \}\}/si";
            $pattern[] = "/\{\{ if_language:.*?\{\{ elif_language:$language \}\}(.*?)\{\{ else \}\}.*?\{\{ end_if \}\}/si";
            $pattern[] = "/\{\{ if_language:.*?\{\{ elif_language:$language \}\}(.*?)\{\{ end_if \}\}/si";
            $pattern[] = "/\{\{ if_language:.*?\{\{ else \}\}(.*?)\{\{ end_if \}\}/si";
            $pattern[] = "/\{\{ if_language:.*?\{\{ end_if \}\}/si";
            $replacement = '$1';
            // execute regex
            $value = preg_replace($pattern, $replacement, $value);
        }

        // clear un-translated language
        if (strpos($value, '{{ ') !== false) {
            $pattern = array();
            $pattern = "/\{\{ if_language:.*?\{\{ end_if \}\}/s";
            $replacement = '';
            // execute regex
            $value = preg_replace($pattern, $replacement, $value);
        }

        // configuration
        if (strpos($value, '{{ ') !== false) {
            $pattern = '/\{\{ (.*?) \}\}/si';
            // execute regex
            $value = preg_replace_callback($pattern, array(
                $this,
                '__cms_preg_replace_callback_config',
            ), $value);
        }

        $value = $this->cms_unescape_template($value);

        return $value;
    }

    /**
     * @author go frendi
     *
     * @param  string user_name
     *
     * @return bool
     * @desc   check if user already exists
     */
    public function cms_is_user_exists($identity, $exception_user_id = 0)
    {
        // for subsite, the user should not match any user in main site and current subsite
        // for main site, the user should not match any user in main site
        if (CMS_SUBSITE == ''){
            $this->db->group_start()
                ->where('subsite', '')
                ->or_where('subsite', NULL)
            ->group_end();
        }else{
            $this->db->group_start()
                ->where('subsite', '')
                ->or_where('subsite', NULL)
                ->or_where('subsite', CMS_SUBSITE)
            ->group_end();
        }
        $query = $this->db->select('user_id, user_name')
            ->from($this->cms_user_table_name())
            ->where('user_id <>', $exception_user_id)
            ->group_start()
                ->where('user_name', $identity)
                ->or_where('email', $identity)
            ->group_end()
            ->get();
        $num_rows = $query->num_rows();
        if ($num_rows > 0) {
            return true;
        }
        return false;
    }

    /**
     * @author go frendi
     *
     * @param  string expression
     *
     * @return string
     * @desc return a "save" pattern which is not replace input value, and
     * anything between <textarea></textarea> and <option></option>
     */
    public function cms_escape_template($str)
    {
        $pattern = array();
        $pattern[] = '/(<textarea[^<>]*>)(.*?)(<\/textarea>)/si';
        $pattern[] = '/(value *= *")(.*?)(")/si';
        $pattern[] = "/(value *= *')(.*?)(')/si";

        $str = preg_replace_callback($pattern, array(
            $this,
            '__cms_preg_replace_callback_escape_template',
        ), $str);

        return $str;
    }

    /**
     * @author go frendi
     *
     * @param  string expression
     *
     * @return string
     * @desc return an "unsave" pattern which is not replace anything inside HTML tag, and
     * anything between <textarea></textarea> and <option></option>
     */
    public function cms_unescape_template($str)
    {
        $pattern = array();
        $pattern[] = '/(<textarea[^<>]*>)(.*?)(<\/textarea>)/si';
        $pattern[] = '/(value *= *")(.*?)(")/si';
        $pattern[] = "/(value *= *')(.*?)(')/si";
        $str = preg_replace_callback($pattern, array(
            $this,
            '__cms_preg_replace_callback_unescape_template',
        ), $str);

        return $str;
    }

    /**
     * @author go frendi
     *
     * @param  array arr
     *
     * @return string
     * @desc replace every '{{' and '}}' in $arr[1] into &#123; and &#125;
     */
    private function __cms_preg_replace_callback_unescape_template($arr)
    {
        $to_replace = array(
            '{{ ',
            ' }}',
        );
        $to_be_replaced = array(
            '&#123;&#123; ',
            ' &#125;&#125;',
        );

        return $arr[1].str_replace($to_be_replaced, $to_replace, $arr[2]).$arr[3];
    }

    /**
     * @author go frendi
     *
     * @param  array arr
     *
     * @return string
     * @desc replace every &#123; and &#125; in $arr[1] into '{{' and '}}';
     */
    private function __cms_preg_replace_callback_escape_template($arr)
    {
        $to_be_replaced = array(
            '{{ ',
            ' }}',
        );
        $to_replace = array(
            '&#123;&#123; ',
            ' &#125;&#125;',
        );

        return $arr[1].str_replace($to_be_replaced, $to_replace, $arr[2]).$arr[3];
    }

    /**
     * @author go frendi
     *
     * @param  array arr
     *
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
        if (isset($raw_config_value)) {
            // avoid recursion
            if (strpos($raw_config_value, '{{ '.$arr[1].' }}') !== false) {
                $raw_config_value = str_replace('{{ '.$arr[1].' }}', ' ', $raw_config_value);
            }

            return $this->cms_parse_keyword($raw_config_value);
        } else {
            return '{{ '.$arr[1].' }}';
        }
    }

    /**
     * @author go frendi
     *
     * @return array providers
     */
    public function cms_third_party_providers()
    {
        if (!in_array('curl', get_loaded_extensions())) {
            return array();
        }
        $this->load->library('Hybridauthlib');
        $providers = $this->hybridauthlib->getProviders();

        return $providers;
    }

    /**
     * @author go frendi
     *
     * @return array status
     * @desc return all status from third-party provider
     */
    public function cms_third_party_status()
    {
        if (!in_array('curl', get_loaded_extensions())) {
            return array();
        }
        $this->load->library('Hybridauthlib');
        $status = array();
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
     * @author go frendi
     *
     * @return bool success
     * @desc login/register by using third-party provider
     */
    public function cms_third_party_login($provider, $email = null)
    {
        // if provider not valid then exit
        $status = $this->cms_third_party_status();
        if (!isset($status[$provider])) {
            return false;
        }

        $identifier = $status[$provider]['identifier'];

        $user_id = $this->cms_user_id();
        $user_id = !isset($user_id) || is_null($user_id) ? 0 : $user_id;
        $query = $this->db->select('user_id')->from($this->cms_user_table_name())->where('auth_'.$provider, $identifier)->get();
        if ($query->num_rows() > 0) { // get user_id based on auth field
            $row = $query->row();
            $user_id = $row->user_id;
        } else { // no identifier match, register it to the database
            $third_party_email = $status[$provider]['email'];
            $third_party_display_name = $status[$provider]['firstName'];

            // well, twitter sucks... it doesn't allow us to retrieve user's email
            if ($third_party_email === null) {
                $third_party_email = $email != null ? $email : $new_user_name.'@unknown.com';
            }

            // if email match with the database, set $user_id
            if ($user_id == false) {
                $query = $this->db->select('user_id')->from($this->cms_user_table_name())->where('email', $third_party_email)->get();
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $user_id = $row->user_id;
                }
            }
            // if $user_id set (already_login, or $status[provider]['email'] match with database)
            if ($user_id != false) {
                $data = array(
                    'auth_'.$provider => $identifier,
                );
                $where = array(
                    'user_id' => $user_id,
                );
                $this->db->update($this->cms_user_table_name(), $data, $where);
            } else { // if not already login, register provider and id to the database
                $new_user_name = $third_party_display_name;

                // ensure there is no duplicate user name
                $duplicate = true;
                while ($duplicate) {
                    $query = $this->db->select('user_name')->from($this->cms_user_table_name())->where('user_name', $new_user_name)->get();
                    if ($query->num_rows() > 0) {
                        $query = $this->db->select('user_name')->from($this->cms_user_table_name())->get();
                        $user_count = $query->num_rows();
                        $new_user_name = 'user_'.$user_count.' ('.$new_user_name.')';
                    } else {
                        $duplicate = false;
                    }
                }

                // insert to database
                $data = array(
                    'user_name' => $new_user_name,
                    'email' => $third_party_email,
                    'auth_'.$provider => $identifier,
                );
                $this->db->insert($this->cms_user_table_name(), $data);

                // get user_id
                $query = $this->db->select('user_id')->from($this->cms_user_table_name())->where('email', $third_party_email)->get();
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $user_id = $row->user_id;
                }
            }
        }

        // set cms_user_id, cms_user_name, cms_user_email, cms_user_real_name, just as when login from the normal way
        $query = $this->db->select('user_id, user_name, email, real_name')->from($this->cms_user_table_name())->where('user_id', $user_id)->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $this->cms_user_id($row->user_id);
            $this->cms_user_name($row->user_name);
            $this->cms_user_real_name($row->real_name);
            $this->cms_user_email($row->email);

            return true;
        }

        return false;
    }

    public function cms_add_navigation_if_not_exists($navigation_name, $title, $url, $authorization_id = 1, $parent_name = null, $index = null, $description = null, $bootstrap_glyph = null, $default_theme = null, $default_layout = null, $notif_url = null, $hidden = 0, $static_content = '')
    {
        $query = $this->db->select('navigation_id')->from(cms_table_name('main_navigation'))
            ->where('navigation_name', $navigation_name)->get();
        if ($query->num_rows() == 0) {
            $this->cms_add_navigation($navigation_name, $title, $url, $authorization_id, $parent_name, $index, $description, $bootstrap_glyph, $default_theme, $default_layout, $notif_url, $hidden, $static_content);
        }
    }

    public function cms_add_navigation($navigation_name, $title, $url, $authorization_id = 1, $parent_name = null, $index = null, $description = null, $bootstrap_glyph = null, $default_theme = null, $default_layout = null, $notif_url = null, $hidden = 0, $static_content = '')
    {
        //get parent's navigation_id
        $query = $this->db->select('navigation_id, navigation_name')
            ->from(cms_table_name('main_navigation'))
            ->where('navigation_name', $parent_name)
            ->get();
        $row = $query->row();

        $parent_id = isset($row->navigation_id) && $row->navigation_name != $navigation_name  ? $row->navigation_id : null;

        //if it is null, index = max index+1
        if (!isset($index)) {
            if (isset($parent_id)) {
                $whereParentId = "(parent_id = $parent_id)";
            } else {
                $whereParentId = '(parent_id IS NULL)';
            }
            $query = $this->db->select_max('index')
                ->from(cms_table_name('main_navigation'))
                ->where($whereParentId)
                ->get();
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $index = $row->index + 1;
            }
            if (!isset($index)) {
                $index = 0;
            }
        }

        // is there any navigation with the same name?
        $dont_insert = false;
        $query = $this->db->select('navigation_id')->from(cms_table_name('main_navigation'))
            ->where('navigation_name', $navigation_name)->get();
        if ($query->num_rows() > 0) {
            $dont_insert = true;
            $row = $query->row();
            $navigation_id = $row->navigation_id;
        } else {
            // is there any navigation with same url
            $query = $this->db->select('navigation_id')->from(cms_table_name('main_navigation'))
                ->where('url', $url)->get();
            if ($query->num_rows() > 0) {
                $dont_insert = true;
                $row = $query->row();
                $navigation_id = $row->navigation_id;
            }
        }

        $data = array(
            'navigation_name' => $navigation_name,
            'title' => $title,
            'url' => $this->cms_parse_keyword($url),
            'authorization_id' => $authorization_id,
            'index' => is_int($index)? $index: 0,
            'description' => $description,
            'active' => 1,
            'bootstrap_glyph' => $bootstrap_glyph,
            'default_theme' => $default_theme,
            'default_layout' => $default_layout,
            'notif_url' => $this->cms_parse_keyword($notif_url),
            'hidden' => is_int($hidden)? $hidden : 0,
            'static_content' => $static_content,
        );
        if (isset($parent_id)) {
            $data['parent_id'] = $parent_id;
        }

        //insert it :D
        if ($dont_insert) {
            unset($data['index']);
            $this->db->update(cms_table_name('main_navigation'), $data, array('navigation_id' => $navigation_id));
        } else {
            $this->db->insert(cms_table_name('main_navigation'), $data);
        }
    }

    public function cms_remove_navigation($navigation_name)
    {
        //get navigation_id
        $query = $this->db->select('navigation_id')
            ->from(cms_table_name('main_navigation'))
            ->where('navigation_name', $navigation_name)
            ->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $navigation_id = isset($row->navigation_id) ? $row->navigation_id : null;
        }

        if (isset($navigation_id)) {
            //delete quicklink
            $where = array(
                'navigation_id' => $navigation_id,
            );
            $this->db->delete(cms_table_name('main_quicklink'), $where);
            //delete cms_group_navigation
            $where = array(
                'navigation_id' => $navigation_id,
            );
            $this->db->delete(cms_table_name('main_group_navigation'), $where);
            //delete cms_navigation
            $where = array(
                'navigation_id' => $navigation_id,
            );
            $this->db->delete(cms_table_name('main_navigation'), $where);
        }
    }

    public function cms_add_privilege_if_not_exists($privilege_name, $title, $authorization_id = 1, $description = null)
    {
        $query = $this->db->select('privilege_id')
            ->from(cms_table_name('main_privilege'))
            ->where('privilege_name', $privilege_name)
            ->get();
        if ($query->num_rows() == 0) {
            $this->cms_add_privilege($privilege_name, $title, $authorization_id, $description);
        }
    }

    public function cms_add_privilege($privilege_name, $title, $authorization_id = 1, $description = null)
    {
        $data = array(
            'privilege_name' => $privilege_name,
            'title' => $title,
            'authorization_id' => $authorization_id,
            'description' => $description,
        );
        $query = $this->db->select('privilege_id')
            ->from(cms_table_name('main_privilege'))
            ->where('privilege_name', $privilege_name)
            ->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $privilege_id = $row->privilege_id;
            $this->db->update(cms_table_name('main_privilege'), $data, array('privilege_id' => $privilege_id));
        } else {
            $this->db->insert(cms_table_name('main_privilege'), $data);
        }
    }

    public function cms_remove_privilege($privilege_name)
    {
        $query = $this->db->select('privilege_id')
            ->from(cms_table_name('main_privilege'))
            ->where('privilege_name', $privilege_name)
            ->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            $privilege_id = $row->privilege_id;
        }

        if (isset($privilege_id)) {
            //delete cms_group_privilege
            $where = array(
                'privilege_id' => $privilege_id,
            );
            $this->db->delete(cms_table_name('main_group_privilege'), $where);
            //delete cms_privilege
            $where = array(
                'privilege_id' => $privilege_id,
            );
            $this->db->delete(cms_table_name('main_privilege'), $where);
        }
    }

    public function cms_add_group_if_not_exists($group_name, $description){
        $query = $this->db->select('group_id')
            ->from(cms_table_name('main_group'))
            ->where('group_name', $group_name)
            ->get();
        if ($query->num_rows() == 0) {
            $this->cms_add_group($group_name, $description);
        }
    }

    public function cms_add_group($group_name, $description)
    {
        $data = array(
            'group_name' => $group_name,
            'description' => $description,
        );
        $query = $this->db->select('group_id')
            ->from(cms_table_name('main_group'))
            ->where('group_name', $group_name)
            ->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $group_id = $row->group_id;
            $this->db->update(cms_table_name('main_group'), $data, array('group_id' => $group_id));
        } else {
            $this->db->insert(cms_table_name('main_group'), $data);
        }
    }
    public function cms_remove_group($group_name)
    {
        $query = $this->db->select('group_id')
            ->from(cms_table_name('main_group'))
            ->where('group_name', $group_name)
            ->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            $group_id = $row->group_id;
        }

        if (isset($group_id)) {
            //delete cms_group_privilege
            $where = array(
                'group_id' => $group_id,
            );
            $this->db->delete(cms_table_name('main_group_privilege'), $where);
            //delete cms_group_user
            $where = array(
                'group_id' => $group_id,
            );
            $this->db->delete(cms_table_name('main_group_user'), $where);
            //delete cms_privilege
            $where = array(
                'group_id' => $group_id,
            );
            $this->db->delete(cms_table_name('main_group'), $where);
        }
    }

    public function cms_add_widget_if_not_exists($widget_name, $title = null, $authorization_id = 1, $url = null, $slug = null, $index = null, $description = null)
    {
        $query = $this->db->select('widget_id')
            ->from(cms_table_name('main_widget'))
            ->where('widget_name', $widget_name)
            ->get();
        if ($query->num_rows() == 0) {
            $this-> cms_add_widget($widget_name, $title, $authorization_id, $url, $slug, $index, $description);
        }
    }

    public function cms_add_widget($widget_name, $title = null, $authorization_id = 1, $url = null, $slug = null, $index = null, $description = null)
    {
        //if it is null, index = max index+1
        if (!isset($index)) {
            if (isset($slug)) {
                $whereSlug = "(slug = '".addslashes($slug)."')";
            } else {
                $whereSlug = '(slug IS NULL)';
            }
            $query = $this->db->select_max('index')
                ->from(cms_table_name('main_widget'))
                ->where($whereSlug)
                ->get();
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $index = $row->index + 1;
            }

            if (!isset($index)) {
                $index = 0;
            }
        }

        $data = array(
            'widget_name' => $widget_name,
            'title' => $title,
            'slug' => $slug,
            'index' => $index,
            'authorization_id' => $authorization_id,
            'url' => $url,
            'description' => $description,
        );
        $query = $this->db->select('widget_id')
            ->from(cms_table_name('main_widget'))
            ->where('widget_name', $widget_name)
            ->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $widget_id = $row->widget_id;
            unset($data['index']);
            $this->db->update(cms_table_name('main_widget'), $data, array('widget_id' => $widget_id));
        } else {
            $this->db->insert(cms_table_name('main_widget'), $data);
        }
    }

    public function cms_remove_widget($widget_name)
    {
        $query = $this->db->select('widget_id')
            ->from(cms_table_name('main_widget'))
            ->where('widget_name', $widget_name)
            ->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $widget_id = $row->widget_id;

            if (isset($widget_id)) {
                //delete cms_group_privilege
                $where = array(
                    'widget_id' => $widget_id,
                );
                $this->db->delete(cms_table_name('main_group_widget'), $where);
                //delete cms_privilege
                $where = array(
                    'widget_id' => $widget_id,
                );
                $this->db->delete(cms_table_name('main_widget'), $where);
            }
        }
    }

    public function cms_add_quicklink($navigation_name)
    {
        $query = $this->db->select('navigation_id')
            ->from(cms_table_name('main_navigation'))
            ->where('navigation_name', $navigation_name)
            ->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $navigation_id = $row->navigation_id;
            // index = max index+1
            $query = $this->db->select_max('index')
                ->from(cms_table_name('main_quicklink'))
                ->get();
            $row = $query->row();
            $index = $row->index + 1;
            if (!isset($index)) {
                $index = 0;
            }

            // insert
            $data = array(
                'navigation_id' => $navigation_id,
                'index' => $index,
            );
            $query = $this->db->select('navigation_id')
                ->from(cms_table_name('main_quicklink'))
                ->where('navigation_id', $navigation_id)
                ->get();
            if ($query->num_rows() == 0) {
                $this->db->insert(cms_table_name('main_quicklink'), $data);
            }
        }
    }

    public function cms_remove_quicklink($navigation_name)
    {
        $query = $this->db->select('navigation_id')
            ->from(cms_table_name('main_navigation'))
            ->where('navigation_name', $navigation_name)
            ->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $navigation_id = $row->navigation_id;

            // delete
            $where = array(
                'navigation_id' => $navigation_id,
            );
            $this->db->delete(cms_table_name('main_quicklink'), $where);
        }
    }

    public function cms_reconfig_route()
    {
        if(CMS_SUBSITE != ''){
            $config_path = APPPATH.'config/site-'.CMS_SUBSITE.'/';
        }else if(defined('CMS_OVERRIDDEN_SUBSITE') && CMS_OVERRIDDEN_SUBSITE != ''){
            $config_path = APPPATH.'config/site-'.CMS_OVERRIDDEN_SUBSITE.'/';
        }else{
            $config_path = APPPATH.'config/main/';
        }
        $extended_route_config = $config_path.'extended_routes.php';

        $query = $this->db->select('key, value')
            ->from(cms_table_name('main_route'))
            ->get();
        // build extended route content
        $content = '<?php if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');'.PHP_EOL;
        foreach ($query->result() as $row) {
            $content .= '$route[\''.$row->key.'\'] = \''.$row->value.'\';'.PHP_EOL;
        }
        // write extended route
        file_put_contents($extended_route_config, $content);
    }

    public function cms_add_route_if_not_exists($key, $value, $description = '')
    {
        $query = $this->db->select('key')
            ->from(cms_table_name('main_route'))
            ->where('key', $key)
            ->get();
        if ($query->num_rows() == 0) {
            $this->cms_add_route($key, $value, $description);
        }
    }

    public function cms_add_route($key, $value, $description = '')
    {
        $query = $this->db->select('key')
            ->from(cms_table_name('main_route'))
            ->where('key', $key)
            ->get();
        $data = array('key' => $key, 'value' => $value, 'description' => $description);
        // add if not exists, edit if exists
        if ($query->num_rows() > 0) {
            $this->db->update(cms_table_name('main_route'), $data, array('key' => $key));
        } else {
            $this->db->insert(cms_table_name('main_route'), $data);
        }
        $this->cms_reconfig_route();
    }

    public function cms_remove_route($key)
    {
        $this->db->delete(cms_table_name('main_route'),
            array('key' => $key));
        $this->cms_reconfig_route();
    }

    public function cms_add_config_if_not_exists($config_name, $value, $description = null)
    {
        $query = $this->db->select('config_id')
            ->from(cms_table_name('main_config'))
            ->where('config_name', $config_name)
            ->get();
        if ($query->num_rows() == 0) {
            $this->cms_add_config($config_name, $value, $description);
        }
    }

    public function cms_add_config($config_name, $value, $description = null)
    {
        $query = $this->db->select('config_id')
            ->from(cms_table_name('main_config'))
            ->where('config_name', $config_name)
            ->get();
        $data = array('config_name' => $config_name, 'value' => $value, 'description' => $description);
        if ($query->num_rows() > 0) {
            $config_id = $query->row()->config_id;
            $this->db->update(cms_table_name('main_config'), $data, array('config_id' => $config_id));
        } else {
            $this->db->insert(cms_table_name('main_config'), $data);
        }
    }

    public function cms_remove_config($config_name)
    {
        $this->db->delete(cms_table_name('main_config'), array('config_name' => $config_name));
    }

    public function cms_assign_navigation($navigation_name, $group_name)
    {
        $query = $this->db->select('group_id')
            ->from(cms_table_name('main_group'))
            ->where('group_name', $group_name)
            ->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $group_id = $row->group_id;

            $query = $this->db->select('navigation_id')
                ->from(cms_table_name('main_navigation'))
                ->where('navigation_name', $navigation_name)
                ->get();
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $navigation_id = $row->navigation_id;
                $query = $this->db->select('group_id')
                    ->from(cms_table_name('main_group_navigation'))
                    ->where('navigation_id', $navigation_id)
                    ->where('group_id', $group_id)
                    ->get();
                if ($query->num_rows() == 0) {
                    $this->db->insert(cms_table_name('main_group_navigation'), array(
                        'navigation_id' => $navigation_id,
                        'group_id' => $group_id, ));
                }
            }
        }
    }
    public function cms_assign_privilege($privilege_name, $group_name)
    {
        $query = $this->db->select('group_id')
            ->from(cms_table_name('main_group'))
            ->where('group_name', $group_name)
            ->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $group_id = $row->group_id;

            $query = $this->db->select('privilege_id')
                ->from(cms_table_name('main_privilege'))
                ->where('privilege_name', $privilege_name)
                ->get();
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $privilege_id = $row->privilege_id;
                $query = $this->db->select('group_id')
                    ->from(cms_table_name('main_group_privilege'))
                    ->where('privilege_id', $privilege_id)
                    ->where('group_id', $group_id)
                    ->get();
                if ($query->num_rows() == 0) {
                    $this->db->insert(cms_table_name('main_group_privilege'), array(
                        'privilege_id' => $privilege_id,
                        'group_id' => $group_id, ));
                }
            }
        }
    }
    public function cms_assign_widget($widget_name, $group_name)
    {
        $query = $this->db->select('group_id')
            ->from(cms_table_name('main_group'))
            ->where('group_name', $group_name)
            ->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $group_id = $row->group_id;

            $query = $this->db->select('widget_id')
                ->from(cms_table_name('main_widget'))
                ->where('widget_name', $widget_name)
                ->get();
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $widget_id = $row->widget_id;
                $query = $this->db->select('group_id')
                    ->from(cms_table_name('main_group_widget'))
                    ->where('widget_id', $widget_id)
                    ->where('group_id', $group_id)
                    ->get();
                if ($query->num_rows() == 0) {
                    $this->db->insert(cms_table_name('main_group_widget'), array(
                        'widget_id' => $widget_id,
                        'group_id' => $group_id, ));
                }
            }
        }
    }

    public function cms_execute_sql($SQL, $separator)
    {
        $queries = explode($separator, $SQL);
        foreach ($queries as $query) {
            if (trim($query) == '') {
                continue;
            }
            $table_prefix = cms_module_table_prefix($this->cms_module_path());
            $module_prefix = cms_module_prefix($this->cms_module_path());
            $query = preg_replace('/\{\{ complete_table_name:(.*) \}\}/si', $table_prefix == '' ? '$1' : $table_prefix.'_'.'$1', $query);
            $query = preg_replace('/\{\{ module_prefix \}\}/si', $module_prefix, $query);
            $this->db->query($query);
        }
    }

    public function cms_adjust_tables($tables, $table_prefix = ''){
        $this->load->dbforge();
        // default fields
        $default_fields = array(
            '_created_at' => array('type' => 'TIMESTAMP', 'null' => true),
            '_updated_at' => array('type' => 'TIMESTAMP', 'null' => true),
            '_created_by' => array('type' => 'INT', 'constraint' => 20, 'null' => true),
            '_updated_by' => array('type' => 'INT', 'constraint' => 20, 'null' => true),
        );
        // build tables
        foreach ($tables as $table_name => $data) {
            $table_name = $table_prefix . $table_name;
            $table_exists = $this->db->table_exists($table_name);
            $key = array_key_exists('key', $data)? $data['key'] : 'id';
            $fields = array_key_exists('fields', $data)? $data['fields'] : array();
            foreach($fields as $field_name=>$type){
                if(is_string($type) && property_exists($this, $type)){
                    $fields[$field_name] = $this->{$type};
                }
            }
            // add default fields
            foreach($default_fields as $field_name => $field_data){
                if(!array_key_exists($field_name, $fields)){
                    $fields[$field_name] = $field_data;
                }
            }
            // create table if not exists
            if(!$table_exists){
                $this->dbforge->add_field($fields);
                $this->dbforge->add_key($key, true);
                $this->dbforge->create_table($table_name);
            }else{
                $field_list = $this->db->list_fields($table_name);
                // missing fields and modified field
                $modified_fields = array();
                $missing_fields = array();
                foreach($fields as $key=>$value){
                    if(!in_array($key, $field_list)){
                        $missing_fields[$key] = $value;
                    }else{
                        $modified_fields[$key] = $value;
                    }
                }
                // add missing fields
                $this->dbforge->add_column($table_name, $missing_fields);
                // modify fields
                $this->dbforge->modify_column($table_name, $modified_fields);
            }
        }
    }

    public function cms_set_editing_mode()
    {
        $this->session->set_userdata('__cms_editing_mode', true);
    }

    public function cms_unset_editing_mode()
    {
        $this->session->set_userdata('__cms_editing_mode', false);
    }

    public function cms_editing_mode()
    {
        return $this->session->userdata('__cms_editing_mode') === true;
    }

}
