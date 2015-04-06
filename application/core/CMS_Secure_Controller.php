<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CMS_Secure_Controller class
 *
 * @author gofrendi
 */

class CMS_Secure_Controller extends CMS_Controller
{
    private $navigation_name = '';

    protected $URL_MAP = array();
    protected $ALLOW_UNKNOWN_NAVIGATION_NAME = FALSE;

    public function __construct()
    {
        parent::__construct();
        $this->URL_MAP = $this->do_override_url_map($this->URL_MAP);
        $uriString = $this->uri->uri_string();
        $navigation_name = NULL;
        if (isset($this->URL_MAP[$uriString])) {
            if (!isset($navigation_name)) {
                $navigation_name = $this->cms_navigation_name($this->URL_MAP[$uriString]);
            }
            if (!isset($navigation_name)) {
                $navigation_name = $this->URL_MAP[$uriString];
            }
        } else {
            foreach ($this->URL_MAP as $key=>$value) {
                if($uriString == $this->cms_parse_keyword($key)){
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
                    'success' => FALSE,
                    'message' => 'unauthorized access'
                );
                $this->cms_show_json($response);
                die();
            } else {
                $this->cms_redirect();
            }
        }
        $this->navigation_name = $navigation_name;
    }

    protected function do_override_url_map($URL_MAP){
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
        $config['always_allow'] = TRUE;
        return $config;
    }

    protected function view($view_url, $data = NULL, $navigation_name = NULL, $config = NULL, $return_as_string = FALSE)
    {
        if (is_bool($navigation_name) && count($config) == 0) {
            $return_as_string = $navigation_name;
            $navigation_name  = NULL;
            $config           = NULL;
        } else if (is_bool($config)) {
            $return_as_string = $config;
            $config           = NULL;
        }
        if (!isset($config) || !is_array($config)) {
            $config = array();
        }
        $navigation_name = $this->cms_override_navigation_name($navigation_name);
        $config          = $this->cms_override_config($config);
        parent::view($view_url, $data, $navigation_name, $config, $return_as_string);
    }
}

/**
 * Description of CMS_Module_Installer
 *
 * @author gofrendi
 */
class CMS_Module_Info_Controller extends CMS_Controller
{
    protected $info_model      = NULL;
    protected $DEPENDENCIES    = array();
    protected $NAME            = '';
    protected $VERSION         = '0.0.0';
    protected $DESCRIPTION     = NULL;
    protected $IS_ACTIVE       = FALSE;
    protected $IS_OLD          = FALSE;
    protected $OLD_VERSION     = '';
    protected $ERROR_MESSAGE   = '';
    protected $PUBLIC          = TRUE;
    protected $SUBSITE_ALLOWED = array();

    protected function _guard_controller(){
        // Don't do anything, only typical controller need to be guarded.
    }

    // type
    protected $TYPE_INT_UNSIGNED_AUTO_INCREMENT = array( 'type' => 'INT', 'constraint' => 20, 'unsigned' => TRUE, 'auto_increment' => TRUE, );
    protected $TYPE_INT_UNSIGNED_NOTNULL = array( 'type' => 'INT', 'constraint' => 20, 'unsigned' => TRUE, 'null' => FALSE, );
    protected $TYPE_INT_SIGNED_NOTNULL = array( 'type' => 'INT', 'constraint' => 20, 'null' => FALSE, );
    protected $TYPE_INT_UNSIGNED_NULL = array( 'type' => 'INT', 'constraint' => 20, 'unsigned' => TRUE, 'null'=>TRUE, );
    protected $TYPE_INT_SIGNED_NULL = array( 'type' => 'INT', 'constraint' => 20, 'null'=>TRUE, );
    protected $TYPE_DATETIME_NOTNULL = array( 'type' => 'TIMESTAMP', 'null' => FALSE, );
    protected $TYPE_DATE_NOTNULL = array( 'type' => 'DATE', 'null' => FALSE, );
    protected $TYPE_DATETIME_NULL = array( 'type' => 'TIMESTAMP', 'null'=>TRUE, );
    protected $TYPE_DATE_NULL = array( 'type' => 'DATE', 'null'=>TRUE, );
    protected $TYPE_FLOAT_NOTNULL = array( 'type' => 'FLOAT', 'null' => FALSE, );
    protected $TYPE_DOUBLE_NOTNULL = array( 'type' => 'DOUBLE', 'null' => FALSE, );
    protected $TYPE_FLOAT_NULL = array( 'type' => 'FLOAT', 'null'=>TRUE, );
    protected $TYPE_DOUBLE_NULL = array( 'type' => 'DOUBLE', 'null'=>TRUE, );
    protected $TYPE_TEXT = array( 'type' => 'TEXT', 'null'=> TRUE, );
    protected $TYPE_VARCHAR_5_NOTNULL = array( 'type' => 'VARCHAR', 'constraint' => 5, 'null' => FALSE, );
    protected $TYPE_VARCHAR_10_NOTNULL = array( 'type' => 'VARCHAR', 'constraint' => 10, 'null' => FALSE, );
    protected $TYPE_VARCHAR_20_NOTNULL = array( 'type' => 'VARCHAR', 'constraint' => 20, 'null' => FALSE, );
    protected $TYPE_VARCHAR_50_NOTNULL = array( 'type' => 'VARCHAR', 'constraint' => 50, 'null' => FALSE, );
    protected $TYPE_VARCHAR_100_NOTNULL = array( 'type' => 'VARCHAR', 'constraint' => 100, 'null' => FALSE, );
    protected $TYPE_VARCHAR_150_NOTNULL = array( 'type' => 'VARCHAR', 'constraint' => 150, 'null' => FALSE, );
    protected $TYPE_VARCHAR_200_NOTNULL = array( 'type' => 'VARCHAR', 'constraint' => 200, 'null' => FALSE, );
    protected $TYPE_VARCHAR_250_NOTNULL = array( 'type' => 'VARCHAR', 'constraint' => 250, 'null' => FALSE, );
    protected $TYPE_VARCHAR_5_NULL = array( 'type' => 'VARCHAR', 'constraint' => 5, 'null'=>TRUE, );
    protected $TYPE_VARCHAR_10_NULL = array( 'type' => 'VARCHAR', 'constraint' => 10, 'null'=>TRUE, );
    protected $TYPE_VARCHAR_20_NULL = array( 'type' => 'VARCHAR', 'constraint' => 20, 'null'=>TRUE, );
    protected $TYPE_VARCHAR_50_NULL = array( 'type' => 'VARCHAR', 'constraint' => 50, 'null'=>TRUE, );
    protected $TYPE_VARCHAR_100_NULL = array( 'type' => 'VARCHAR', 'constraint' => 100, 'null'=>TRUE, );
    protected $TYPE_VARCHAR_150_NULL = array( 'type' => 'VARCHAR', 'constraint' => 150, 'null'=>TRUE, );
    protected $TYPE_VARCHAR_200_NULL = array( 'type' => 'VARCHAR', 'constraint' => 200, 'null'=>TRUE, );
    protected $TYPE_VARCHAR_250_NULL = array( 'type' => 'VARCHAR', 'constraint' => 250, 'null'=>TRUE, );


    public function __construct(){
        parent::__construct();
        $module_path = $this->cms_module_path();
        $this->info_model = $this->cms_load_info_model($module_path);
        if($this->info_model != NULL){
            $this->DEPENDENCIES    = $this->info_model->DEPENDENCIES;
            $this->NAME            = $this->info_model->NAME;
            $this->VERSION         = $this->info_model->VERSION;
            $this->DESCRIPTION     = $this->info_model->DESCRIPTION;
            $this->IS_ACTIVE       = $this->info_model->IS_ACTIVE;
            $this->IS_OLD          = $this->info_model->IS_OLD;
            $this->OLD_VERSION     = $this->info_model->OLD_VERSION;
            $this->ERROR_MESSAGE   = $this->info_model->ERROR_MESSAGE;
            $this->PUBLIC          = $this->info_model->PUBLIC;
            $this->SUBSITE_ALLOWED = $this->info_model->SUBSITE_ALLOWED;
        }else{
            // get module name & module path
            $query = $this->db->select('version')
                ->from(cms_table_name('main_module'))
                ->where(array(
                    'module_name'=> $this->NAME,
                    'module_path'=> $module_path,
                  ))
                ->get();
            if ($query->num_rows() == 0) {
                $this->IS_ACTIVE = FALSE;
                $this->IS_OLD = FALSE;
                $this->OLD_VERSION = '0.0.0';
            } else {
                $this->IS_ACTIVE = TRUE;
                $row = $query->row();
                // TODO: the suck sqlite returning array
                $row = json_decode(json_encode($row), FALSE);
                if($this->OLD_VERSION == ''){
                    $this->OLD_VERSION = $row->version;
                }
                if(version_compare($this->VERSION, $this->OLD_VERSION)>0){
                    $this->IS_OLD = TRUE;
                }else{
                    $this->IS_OLD = FALSE;
                }
            }
            // load dbforge to be used later
            $this->load->dbforge();
            // get subsite authorization
            $subsite_auth_file = FCPATH.'modules/'.$this->cms_module_path().'/subsite_auth.php';
            if(file_exists($subsite_auth_file)){
                unset($public);
                unset($subsite_allowed);
                include($subsite_auth_file);
                if(isset($public) && is_bool($public)){
                    $this->PUBLIC = $public;
                }
                if(isset($subsite_allowed) && is_array($subsite_allowed)){
                    $this->SUBSITE_ALLOWED = $subsite_allowed;
                }
            }
        }
    }

    public function status($dont_fetch = FALSE){
        if($this->DESCRIPTION === NULL){
            if($dont_fetch){
                $this->DESCRIPTION = 'Just another module';
            }else{
                $this->DESCRIPTION = $this->cms_lang('Just another module');
            }
        }
        $result = array(
            'active'=>$this->IS_ACTIVE,
            'old'=>$this->IS_OLD,
            'description'=>$this->DESCRIPTION,
            'dependencies'=>$this->DEPENDENCIES,
            'name'=>$this->NAME,
            'version'=>$this->VERSION,
            'old_version'=>$this->OLD_VERSION,
            'public'=>$this->PUBLIC,
            'subsite_allowed'=>$this->SUBSITE_ALLOWED,
        );
        if($dont_fetch){
            return $result;
        }
        echo json_encode($result);
    }

    public final function index()
    {
        if ($this->cms_is_module_active($this->NAME)) {
            $this->deactivate();
        } else {
            $this->activate();
        }
    }

    public final function activate()
    {
        // login (in case of called from No-CMS installer)
        $silent   = $this->input->post('silent');
        $identity = $this->input->post('identity');
        $password = $this->input->post('password');
        if ($identity && $password) {
            $this->cms_do_login($identity, cms_decode($password));
        }

        $result = array(
            'success'      => TRUE,
            'message'      => array(),
            'module_name'  => $this->NAME,
            'module_path'  => $this->cms_module_path(),
            'dependencies' => $this->DEPENDENCIES,
        );

        if (CMS_SUBSITE != '' && !$this->PUBLIC && !in_array(CMS_SUBSITE, $this->SUBSITE_ALLOWED)){
            $result['message'][] = 'The module is not published for '.CMS_SUBSITE.' subsite';
            $result['success']   = FALSE;
        }

        // check for error
        if (!$this->cms_have_privilege('cms_install_module')) {
            $result['message'][] = 'Not enough privilege';
            $result['success']   = FALSE;
        }else{
            if($this->NAME == ''){
                $result['message'][] = 'Module name is undefined';
                $result['success']   = FALSE;
            }
            if($this->IS_ACTIVE){
                $result['message'][] = 'The module is already activated';
                $result['success']   = FALSE;
            }
            foreach ($this->DEPENDENCIES as $dependency) {
                if (!$this->cms_is_module_active($dependency)) {
                    $result['message'][] = 'Dependency error '.br().'Please activate these module first:'.ul($this->DEPENDENCIES);
                    $dependencies_error  = TRUE;
                    $result['success']   = FALSE;
                    break;
                }
            }
        }

        // try to activate
        if($result['success']){
            $this->db->trans_start();
            if($this->do_activate() !== FALSE){
                $this->register_module();
            }else{
                $result['success']   = FALSE;
                if($this->ERROR_MESSAGE != ''){
                    $result['message'][] = $this->ERROR_MESSAGE;
                }else{
                    $result['message'][] = 'Failed to activate module';
                }
            }
            $this->db->trans_complete();
        }

        $result['message'] = ul($result['message']);

        // show result
        if($silent){
            $this->cms_show_json($result);
        } else if($result['success']) {
            $module_management_url = $this->cms_navigation_url('main_module_management');
            redirect($module_management_url,'refresh');
        } else {
            $this->view('main/main_module_activation_error', $result, 'main_module_management');
        }
    }

    public final function deactivate()
    {
        $result = array(
            'success'      => TRUE,
            'message'      => array(),
            'module_name'  => $this->NAME,
            'module_path'  => $this->cms_module_path(),
            'dependencies' => array(),
        );

        if (CMS_SUBSITE != '' && !$this->PUBLIC && !in_array(CMS_SUBSITE, $this->SUBSITE_ALLOWED)){
            $result['message'][] = 'The module is not published for '.CMS_SUBSITE.' subsite';
            $result['success']   = FALSE;
        }

        // check for error
        if (!$this->cms_have_privilege('cms_install_module')) {
            $result['message'][] = 'Not enough privilege';
            $result['success']   = FALSE;
        } else {
            $children                = $this->child_module();
            if ($this->NAME == '') {
                $result['message'][] = 'Module name is undefined';
                $result['success']   = FALSE;
            }
            if (!$this->IS_ACTIVE) {
                $result['message'][] = 'The module is already deactivated';
                $result['success']   = FALSE;
            }
            if (count($children) != 0) {
                $result['message'][] = 'Dependency error '.br().'Please deactivate these module first:'.ul($this->children);
                $dependencies_error  = TRUE;
                $result['success']   = FALSE;
                break;
            }
        }

        // try to deactivate
        if($result['success']){
            $this->db->trans_start();
            if($this->do_deactivate() !== FALSE){
                $this->unregister_module();
            }else{
                $result['success']   = FALSE;
                if($this->ERROR_MESSAGE != ''){
                    $result['message'][] = $this->ERROR_MESSAGE;
                }else{
                    $result['message'][] = 'Failed to deactivate module';
                }
            }
            $this->db->trans_complete();
        }

        $result['message'] = ul($result['message']);

        if($result['success']) {
            $module_management_url = $this->cms_navigation_url('main_module_management');
            redirect($module_management_url,'refresh');
        } else {
            $this->view('main/main_module_deactivation_error', $result, 'main_module_management');
        }
    }

    public final function upgrade()
    {
        $result = array(
            'success'      => TRUE,
            'message'      => array(),
            'module_name'  => $this->NAME,
            'module_path'  => $this->cms_module_path(),
            'dependencies' => array(),
        );

        if (CMS_SUBSITE != '' && !$this->PUBLIC && !in_array(CMS_SUBSITE, $this->SUBSITE_ALLOWED)){
            $result['message'][] = 'The module is not published for '.CMS_SUBSITE.' subsite';
            $result['success']   = FALSE;
        }

        if (!$this->cms_have_privilege('cms_install_module')) {
            $result['message'][] = 'Not enough privilege';
            $result['success']   = FALSE;
        }else{
            if ($this->NAME == '') {
                $result['message'][] = 'Module name is undefined';
                $result['success']   = FALSE;
            }
            if (!$this->IS_ACTIVE) {
                $result['message'][] = 'The module is inactive';
                $result['success']   = FALSE;
            }
        }
        if($result['success']){
            // from _upgrade model
            $this->db->trans_start();
            $module_path = $this->cms_module_path();
            $model_alias = 'm_'.$module_path.'_info';
            if(file_exists(FCPATH.'modules/'.$module_path.'/models/_info.php')){
                $this->load->model($module_path.'/_info', $model_alias);
                $module_install_model = $this->{$model_alias};
                if(method_exists($module_install_model,'do_upgrade')){
                    $module_install_model->do_upgrade($this->OLD_VERSION);
                }
            }
            // from do_upgrade function
            if($this->do_upgrade($this->OLD_VERSION) !== FALSE){
                $data  = array('version' => $this->VERSION);
                $where = array('module_name' => $this->NAME);
                $this->db->update(cms_table_name('main_module'), $data, $where);
                $this->db->trans_complete();
            }else{
                $result['success']   = FALSE;
                if($this->ERROR_MESSAGE != ''){
                    $result['message'][] = $this->ERROR_MESSAGE;
                }else{
                    $result['message'][] = 'Failed to upgrade module';
                }
            }
        }

        $result['message'] = ul($result['message']);

        if($result['success']) {
            $module_management_url = $this->cms_navigation_url('main_module_management');
            redirect($module_management_url,'refresh');
        } else {
            $this->view('main/module_upgrade_error', $result, 'main_module_management');
        }
    }

    public function setting(){
        $module_management_url = $this->cms_navigation_url('main_module_management');
        $data['cms_content'] = '<p>Setting is not available</p>'.anchor(site_url($module_management_url),'Back');
        $this->view('CMS_View',$data,'main_module_management');
    }

    protected function do_install()
    {        
        // deprecated function, please use do_activate instead
        return FALSE;
    }
    protected function do_uninstall()
    {
        // deprecated function, please use do_deactivate instead
        return FALSE;
    }

    protected function do_activate()
    {
        //this should be overridden by module developer
        if(method_exists($this->info_model,'do_activate')){
            return $this->info_model->do_activate();
        }else{
            return $this->do_install();
        }
    }

    protected function do_deactivate()
    {
        //this should be overridden by module developer
        if(method_exists($this->info_model,'do_deactivate')){
            return $this->info_model->do_deactivate();
        }else{
            return $this->do_install();
        }
    }

    protected function do_upgrade($old_version)
    {
        //this should be overridden by module developer
        if(method_exists($this->info_model,'do_upgrade')){
            return $this->info_model->do_upgrade($old_version);
        }else{
            return FALSE;
        }
    }

    private final function register_module()
    {
        //insert to cms_module
        $data = array(
            'module_name' => $this->NAME,
            'module_path' => $this->cms_module_path(),
            'version'     => $this->VERSION,
            'user_id'     => $this->cms_user_id()
        );
        $this->db->insert(cms_table_name('main_module'), $data);

        //get current cms_module_id as child_id
        $SQL      = "SELECT module_id FROM ".cms_table_name('main_module')." WHERE module_name='" . addslashes($this->NAME) . "'";
        $query    = $this->db->query($SQL);
        if ($query->num_rows() > 0) {
            $row      = $query->row();
            $child_id = $row->module_id;
        }

        //get parent_id
        if (isset($child_id)) {
            foreach ($this->DEPENDENCIES as $dependency) {
                $SQL       = "SELECT module_id FROM ".cms_table_name('main_module')." WHERE module_name='" . addslashes($dependency) . "'";
                $query     = $this->db->query($SQL);
                if ($query->num_rows() > 0) {
                    $row       = $query->row();
                    $parent_id = $row->module_id;
                    $data      = array(
                        "parent_id" => $parent_id,
                        "module_id" => $child_id
                    );
                    $this->db->insert(cms_table_name('main_module_dependency'), $data);
                }

            }
        }

    }
    private final function unregister_module()
    {
        //get current cms_module_id as child_id
        $SQL      = "SELECT module_id FROM ".cms_table_name('main_module')." WHERE module_name='" . addslashes($this->NAME) . "'";
        $query    = $this->db->query($SQL);
        if ($query->num_rows() > 0) {
            $row      = $query->row();
            $child_id = $row->module_id;

            $where = array(
                'module_id' => $child_id
            );
            $this->db->delete(cms_table_name('main_module_dependency'), $where);

            $where = array(
                'module_path' => $this->cms_module_path()
            );
            $this->db->delete(cms_table_name('main_module'), $where);
        }
    }

    private final function child_module()
    {
        $SQL   = "SELECT module_id FROM ".cms_table_name('main_module')." WHERE module_name='" . addslashes($this->NAME) . "'";
        $query = $this->db->query($SQL);
        if ($query->num_rows() > 0) {
            $row   = $query->row();
            $parent_id = $row->module_id;

            $SQL    = "
	            SELECT module_name, module_path
	            FROM
	                ".cms_table_name('main_module_dependency').",
	                ".cms_table_name('main_module')."
	            WHERE
	                ".cms_table_name('main_module').".module_id = ".cms_table_name('main_module_dependency').".module_id AND
	                parent_id=" . $parent_id;
            $query  = $this->db->query($SQL);
            $result = array();
            foreach ($query->result() as $row) {
                $result[] = array(
                    "module_name" => $row->module_name,
                    "module_path" => $row->module_name
                );
            }
            return $result;
        } else {
            return array();
        }
    }

    protected final function add_navigation($navigation_name, $title, $url, $authorization_id = 1, 
        $parent_name = NULL, $index = NULL, $description = NULL, $bootstrap_glyph=NULL,
        $default_theme=NULL, $default_layout=NULL, $notif_url=NULL)
    {
        $this->cms_add_navigation($navigation_name, $title, $url, $authorization_id, 
            $parent_name, $index, $description, $bootstrap_glyph,
            $default_theme, $default_layout, $notif_url);
    }

    protected final function remove_navigation($navigation_name)
    {
        $this->cms_remove_navigation($navigation_name);
    }

    protected final function add_privilege($privilege_name, $title, $authorization_id = 1, $description = NULL)
    {
        $this->cms_add_privilege($privilege_name, $title, $authorization_id, $description);
    }
    protected final function remove_privilege($privilege_name)
    {
        $this->cms_remove_privilege($privilege_name);
    }

    protected function add_widget($widget_name, $title=NULL, $authorization_id = 1, $url = NULL, $slug = NULL, 
        $index = NULL, $description = NULL)
    {
        $this->cms_add_widget($widget_name, $title, $authorization_id, $url, $slug, $index, 
            $description);
    }

    protected function remove_widget($widget_name)
    {
        $this->cms_remove_widget($widget_name);
    }

    protected function add_quicklink($navigation_name)
    {
        $this->cms_add_quicklink($navigation_name);
    }

    protected function remove_quicklink($navigation_name)
    {
        $this->cms_remove_quicklink($navigation_name);
    }

    public final function execute_sql($SQL, $separator)
    {
        $this->cms_execute_sql($SQL, $separator);
    }
}
