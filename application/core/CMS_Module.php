<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * CMS_Module_Installer class.
 *
 * @author gofrendi
 */

class CMS_Module extends CMS_Controller
{
    protected $__cms_base_model_name = 'no_cms_model';

    protected $DEPENDENCIES = array();
    protected $NAME = '';
    protected $VERSION = '0.0.0';
    protected $DESCRIPTION = null;
    protected $IS_ACTIVE = false;
    protected $IS_OLD = false;
    protected $OLD_VERSION = '';
    protected $ERROR_MESSAGE = '';
    protected $PUBLIC = true;
    protected $SUBSITE_ALLOWED = array();

    // These should be overridden by module developer
    protected $BACKEND_NAVIGATIONS = array(); // manage blah blah blah, has privileges too, associative array
    protected $CONFIGS = array(); // Configurations and values, associative array
    protected $PRIVILEGES = array(); // additional privileges
    protected $NAVIGATIONS = array(); // additional navigations
    protected $GROUPS = array(); // name and description, associative array
    protected $GROUP_NAVIGATIONS = array(); // associative array
    protected $GROUP_BACKEND_NAVIGATIONS = array(); // associative array
    protected $GROUP_BACKEND_PRIVILEGES = array();
    protected $GROUP_PRIVILEGES = array();
    protected $TABLES = array(); // associative array
    protected $DATA = array(); // array of associative array

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

    protected function _guard_controller()
    {
        // Don't do anything, only typical controller need to be guarded.
    }


    public function __construct()
    {
        parent::__construct();
        $module_path = $this->cms_module_path();
        $module_list = $this->cms_get_module_list();
        foreach ($module_list as $module_info) {
            if ($module_info['module_path'] == $module_path) {
                $this->NAME = $module_info['module_name'];
                $this->IS_ACTIVE = $module_info['active'];
                $this->IS_OLD = $module_info['old'];
                $this->OLD_VERSION = $module_info['old_version'];
                $this->VERSION = $module_info['current_version'];
                $this->DESCRIPTION = $module_info['description'];
                $this->DEPENDENCIES = $module_info['dependencies'];
            }
        }
        // load dbforge to be used later
        $this->load->dbforge();
        // get subsite authorization
        $subsite_auth_file = FCPATH.'modules/'.$this->cms_module_path().'/subsite_auth.php';
        if (file_exists($subsite_auth_file)) {
            unset($public);
            unset($subsite_allowed);
            include $subsite_auth_file;
            if (isset($public) && is_bool($public)) {
                $this->PUBLIC = $public;
            }
            if (isset($subsite_allowed) && is_array($subsite_allowed)) {
                $this->SUBSITE_ALLOWED = $subsite_allowed;
            }
        }
    }

    final public function status($dont_fetch = false)
    {
        if ($this->DESCRIPTION === null) {
            if ($dont_fetch) {
                $this->DESCRIPTION = 'Just another module';
            } else {
                $this->DESCRIPTION = $this->cms_lang('Just another module');
            }
        }
        $result = array(
            'active' => $this->IS_ACTIVE,
            'old' => $this->IS_OLD,
            'description' => $this->DESCRIPTION,
            'dependencies' => $this->DEPENDENCIES,
            'name' => $this->NAME,
            'version' => $this->VERSION,
            'old_version' => $this->OLD_VERSION,
            'public' => $this->PUBLIC,
            'subsite_allowed' => $this->SUBSITE_ALLOWED,
        );
        echo json_encode($result);
    }

    final public function index()
    {
        if ($this->cms_is_module_active($this->NAME)) {
            $this->deactivate();
        } else {
            $this->activate();
        }
    }

    final public function bypass($bypass)
    {
        if ($bypass === null) {
            return false;
        }
        $query = $this->db->select('user_id, password')
            ->from($this->cms_user_table_name())
            ->where('user_id', 1)
            ->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            if ($row->password == $bypass) {
                return true;
            }
        }

        return false;
    }

    final public function activate($bypass = null)
    {
        $bypass = $this->bypass($bypass);

        $result = array(
            'success' => true,
            'message' => array(),
            'module_name' => $this->NAME,
            'module_path' => $this->cms_module_path(),
            'dependencies' => $this->DEPENDENCIES,
        );

        if (CMS_SUBSITE != '' && !$this->PUBLIC && !in_array(CMS_SUBSITE, $this->SUBSITE_ALLOWED)) {
            $result['message'][] = 'The module is not published for '.CMS_SUBSITE.' subsite';
            $result['success'] = false;
        }

        // check for error
        if (!$bypass && !$this->cms_have_privilege('cms_install_module')) {
            $result['message'][] = 'Not enough privilege';
            $result['success'] = false;
        } else {
            if ($this->NAME == '') {
                $result['message'][] = 'Module name is undefined';
                $result['success'] = false;
            }
            if ($this->IS_ACTIVE) {
                $result['message'][] = 'The module is already activated';
                $result['success'] = false;
            }
            foreach ($this->DEPENDENCIES as $dependency) {
                if (!$this->cms_is_module_active($dependency)) {
                    $result['message'][] = 'Dependency error '.br().'Please activate these module first:'.ul($this->DEPENDENCIES);
                    $dependencies_error = true;
                    $result['success'] = false;
                    break;
                }
            }
        }

        // try to activate
        if ($result['success']) {
            $this->db->trans_start();
            $this->__remove_all();
            $this->__build_all();
            if ($this->do_activate() !== false) {
                $this->register_module();
            } else {
                $result['success'] = false;
                if ($this->ERROR_MESSAGE != '') {
                    $result['message'][] = $this->ERROR_MESSAGE;
                } else {
                    $result['message'][] = 'Failed to activate module';
                }
            }
            $this->db->trans_complete();
        }

        $result['message'] = ul($result['message']);

        // show result
        if ($bypass) {
            echo json_encode($result);
        } elseif ($result['success']) {
            $module_management_url = $this->cms_navigation_url('main_module_management');
            redirect($module_management_url, 'refresh');
        } else {
            $this->view('module_activation_error', $result, 'main_module_management');
        }
    }

    final public function deactivate()
    {
        $result = array(
            'success' => true,
            'message' => array(),
            'module_name' => $this->NAME,
            'module_path' => $this->cms_module_path(),
            'dependencies' => array(),
        );

        if (CMS_SUBSITE != '' && !$this->PUBLIC && !in_array(CMS_SUBSITE, $this->SUBSITE_ALLOWED)) {
            $result['message'][] = 'The module is not published for '.CMS_SUBSITE.' subsite';
            $result['success'] = false;
        }

        // check for error
        if (!$this->cms_have_privilege('cms_install_module')) {
            $result['message'][] = 'Not enough privilege';
            $result['success'] = false;
        } else {
            $children = $this->child_module();
            if ($this->NAME == '') {
                $result['message'][] = 'Module name is undefined';
                $result['success'] = false;
            }
            if (!$this->IS_ACTIVE) {
                $result['message'][] = 'The module is already deactivated';
                $result['success'] = false;
            }
            if (count($children) != 0) {
                $result['message'][] = 'Dependency error '.br().'Please deactivate these module first:'.ul($this->children);
                $dependencies_error = true;
                $result['success'] = false;
                break;
            }
        }

        // try to deactivate
        if ($result['success']) {
            $this->db->trans_start();
            // backup database
            $table_names = array();
            foreach ($this->TABLES as $table_name => $data) {
                $table_names[] = $this->cms_complete_table_name($table_name);
            }
            $this->backup_database($table_names);
            // remove all
            $this->__remove_all();
            if ($this->do_deactivate() !== false) {
                $this->unregister_module();
            } else {
                $result['success'] = false;
                if ($this->ERROR_MESSAGE != '') {
                    $result['message'][] = $this->ERROR_MESSAGE;
                } else {
                    $result['message'][] = 'Failed to deactivate module';
                }
            }
            $this->db->trans_complete();
        }

        $result['message'] = ul($result['message']);

        if ($result['success']) {
            $module_management_url = $this->cms_navigation_url('main_module_management');
            redirect($module_management_url, 'refresh');
        } else {
            $this->view('module_deactivation_error', $result, 'main_module_management');
        }
    }

    final public function upgrade($bypass = null)
    {
        $bypass = $this->bypass($bypass);

        $result = array(
            'success' => true,
            'message' => array(),
            'module_name' => $this->NAME,
            'module_path' => $this->cms_module_path(),
            'dependencies' => array(),
        );

        if (!$bypass && CMS_SUBSITE != '' && !$this->PUBLIC && !in_array(CMS_SUBSITE, $this->SUBSITE_ALLOWED)) {
            $result['message'][] = 'The module is not published for '.CMS_SUBSITE.' subsite';
            $result['success'] = false;
        }

        if (!$bypass && !$this->cms_have_privilege('cms_install_module')) {
            $result['message'][] = 'Not enough privilege';
            $result['success'] = false;
        } else {
            if ($this->NAME == '') {
                $result['message'][] = 'Module name is undefined';
                $result['success'] = false;
            }
            if (!$this->IS_ACTIVE) {
                $result['message'][] = 'The module is inactive';
                $result['success'] = false;
            }
        }
        if ($result['success']) {
            // from _upgrade model
            $this->db->trans_start();
            $module_path = $this->cms_module_path();
            $this->__build_all('upgrade');
            // from do_upgrade function
            if ($this->do_upgrade($this->OLD_VERSION) !== false) {
                $data = array('version' => $this->VERSION);
                $where = array('module_name' => $this->NAME);
                $this->db->update(cms_table_name('main_module'), $data, $where);
                $this->db->trans_complete();
            } else {
                $result['success'] = false;
                if ($this->ERROR_MESSAGE != '') {
                    $result['message'][] = $this->ERROR_MESSAGE;
                } else {
                    $result['message'][] = 'Failed to upgrade module';
                }
            }
        }

        $result['message'] = ul($result['message']);

        if ($bypass != null) {
            echo json_encode($result);
        } elseif ($result['success']) {
            $module_management_url = $this->cms_navigation_url('main_module_management');
            redirect($module_management_url, 'refresh');
        } else {
            $this->view('module_upgrade_error', $result, 'main_module_management');
        }
    }

    final private function __remove_all()
    {
        // GET MODULE PATH
        $module_path = $this->cms_module_path();

        // REMOVE CONFIGS
        foreach ($this->CONFIGS as $config) {
            $config_name = $this->__get_from_array($config, 'config_name', '');
            $this->cms_remove_config($this->cms_complete_navigation_name($config_name));
        }

        // REMOVE NAVIGATIONS & BACKEND NAVIGATIONS
        foreach ($this->__get_all_navigations() as $navigation) {
            $navigation_name = $this->__get_from_array($navigation, 'navigation_name', '');
            $this->cms_remove_navigation($this->cms_complete_navigation_name($navigation_name));
        }

        // REMOVE PRIVILEGES & BACKEND PRIVILEGES
        foreach ($this->__get_all_privileges() as $privilege) {
            $privilege_name = $this->__get_from_array($privilege, 'privilege_name', '');
            $this->cms_remove_privilege($this->cms_complete_navigation_name($privilege_name));
        }

        // REMOVE GROUPS
        foreach ($this->GROUPS as $group) {
            $group_name = $this->__get_from_array($group, 'group_name', '');
            $this->cms_remove_group($group_name);
        }

        // REMOVE TABLES
        foreach ($this->TABLES as $table_name => $data) {
            $this->dbforge->drop_table($this->cms_complete_table_name($table_name), true);
        }
    }

    final private function __build_all($mode='insert')
    {
        // GET MODULE PATH
        $module_path = $this->cms_module_path();

        // ADD CONFIGS
        foreach ($this->CONFIGS as $config) {
            $config_name = $this->__get_from_array($config, 'config_name', '');
            $value = $this->__get_from_array($config, 'value', '');
            $this->cms_add_config($this->cms_complete_navigation_name($config_name), $value);
        }

        // ADD NAVIGATIONS & BACKEND NAVIGATIONS
        foreach ($this->__get_all_navigations() as $navigation) {
            $navigation_name = $this->__get_from_array($navigation, 'navigation_name', '');
            $title = $this->__get_from_array($navigation, 'title', ucwords(str_replace('_', ' ', $navigation_name)));
            $url = $this->__get_from_array($navigation, 'url', $navigation_name);
            $authorization_id = $this->__get_from_array($navigation, 'authorization_id', 1);
            $parent_name = $this->__get_from_array($navigation, 'parent_name', null);
            $index = $this->__get_from_array($navigation, 'index', null);
            $description = $this->__get_from_array($navigation, 'description', null);
            $bootstrap_glyph = $this->__get_from_array($navigation, 'bootstrap_glyph', null);
            $default_theme = $this->__get_from_array($navigation, 'default_theme', null);
            $default_layout = $this->__get_from_array($navigation, 'default_layout', null);
            $notif_url = $this->__get_from_array($navigation, 'notif_url', null);
            $static_content = $this->__get_from_array($navigation, 'static_content', '');
            $hidden = $this->__get_from_array($navigation, 'hidden', 0);
            if($hidden === NULL){
                $hidden = 0;
            }
            $this->cms_add_navigation(
                    $this->cms_complete_navigation_name($navigation_name),
                    $title,
                    $module_path.'/'.$url,
                    $authorization_id,
                    $parent_name,
                    $index,
                    $description,
                    $bootstrap_glyph,
                    $default_theme,
                    $default_layout,
                    $notif_url,
                    $hidden,
                    $static_content
                );
        }

        // ADD PRIVILEGES & BACKEND PRIVILEGES
        foreach ($this->__get_all_privileges() as $privilege) {
            $privilege_name = $this->__get_from_array($privilege, 'privilege_name', '');
            $description = $this->__get_from_array($privilege, 'description', '');
            $this->cms_add_privilege($this->cms_complete_navigation_name($privilege_name), $description);
        }

        // ADD GROUPS
        foreach ($this->GROUPS as $group) {
            $group_name = $this->__get_from_array($group, 'group_name', '');
            $description = $this->__get_from_array($group, 'description', '');
            $this->cms_add_group($group_name, $description);
        }

        // ASSIGN NAVIGATIONS
        foreach ($this->GROUP_NAVIGATIONS as $group_name => $navigation_names) {
            foreach($navigation_names as $navigation_name){
                $this->cms_assign_navigation($this->cms_complete_navigation_name($navigation_name), $group_name);
            }
        }
        // ASSIGN BACKEND NAVIGATIONS
        foreach ($this->GROUP_BACKEND_NAVIGATIONS as $group_name => $navigation_names) {
            foreach($navigation_names as $navigation_name){
                $this->cms_assign_navigation($this->cms_complete_navigation_name('manage_'.$navigation_name), $group_name);
            }
        }

        // ASSIGN PRIVILEGE
        foreach ($this->GROUP_PRIVILEGES as $group_name => $privilege_names) {
            foreach($privilege_names as $privilege_name){
                $this->cms_assign_privilege($this->cms_complete_navigation_name($privilege_name), $group_name);
            }
        }

        // ASSIGN BACKEND PRIVILEGES
        foreach ($this->GROUP_BACKEND_PRIVILEGES as $group_name => $privilege_detail) {
            foreach ($privilege_detail as $privilege_name => $verb_list) {
                foreach ($verb_list as $verb) {
                    $this->cms_assign_privilege($this->cms_complete_navigation_name($verb.'_'.$privilege_name), $group_name);
                }
            }
        }

        if($mode == 'insert'){
            // CREATE TABLES
            foreach ($this->TABLES as $table_name => $data) {
                $key = $this->__get_from_array($data, 'key', 'id');
                $fields = $this->__get_from_array($data, 'fields', array());
                foreach($fields as $field_name=>$type){
                    if(is_string($type) && property_exists($this, $type)){
                        $fields[$field_name] = $this->{$type};
                    }
                }
                $fields['_created_at'] = $this->TYPE_DATETIME_NULL;
                $fields['_updated_at'] = $this->TYPE_DATETIME_NULL;
                $fields['_created_by'] = $this->TYPE_INT_SIGNED_NULL;
                $fields['_updated_by'] = $this->TYPE_INT_SIGNED_NULL;
                $this->dbforge->add_field($fields);
                $this->dbforge->add_key($key, true);
                $this->dbforge->create_table($this->cms_complete_table_name($table_name));
            }

            // INSERT DATA
            foreach ($this->DATA as $table_name => $data) {
                $this->db->insert_batch($this->cms_complete_table_name($table_name), $data);
            }
        }else{
            // TABLES
            foreach($this->TABLES as $table_name => $data){
                $fields = $this->__get_from_array($data, 'fields', array());
                foreach($fields as $field_name=>$type){
                    if(is_string($type) && property_exists($this, $type)){
                        $fields[$field_name] = $this->{$type};
                    }
                }
                $field_list = $this->db->list_fields($this->cms_complete_table_name($table_name));
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
                $this->dbforge->add_column($this->cms_complete_table_name($table_name), $missing_fields);
                // modify fields
                $this->dbforge->modify_column($this->cms_complete_table_name($table_name), $modified_fields);
            }
            // INSERT OR UPDATE DATA
            foreach ($this->DATA as $table_name => $data) {
                $table = $this->__get_from_array($this->TABLES, $table_name, array());
                $key = $this->__get_From_array($table, 'key', 'id');
                foreach($data as $record){
                    // is the record already exists?
                    $found = FALSE;
                    if(array_key_exists($key, $data)){
                        $query = $this->db->select($key)
                            ->from($this->cms_complete_table_name($table_name))
                            ->where($key, $record[$key])
                            ->get();
                        if($query->num_rows() > 0){
                            $found = TRUE;
                        }
                    }
                    // if record already exists, update. Else, insert
                    if($found){
                        $this->db->update($this->cms_complete_table_name($table_name), $record,
                            array($key=>$record[$key]));
                    }else{
                        $this->db->insert($this->cms_complete_table_name($table_name), $record);
                    }
                }
            }
        }
    }

    final private function __get_from_array($array, $key, $default)
    {
        if (is_array($array) && array_key_exists($key, $array)) {
            return $array[$key];
        } else {
            return $default;
        }
    }

    final private function __get_all_navigations()
    {
        // COMBINE NAVIGATIONS AND BACKEND NAVIGATIONS
        $NAVIGATIONS = array();
        foreach ($this->NAVIGATIONS as $navigation) {
            $NAVIGATIONS[] = $navigation;
        }
        foreach ($this->BACKEND_NAVIGATIONS as $navigation) {
            $navigation['navigation_name'] = 'manage_'.$this->__get_from_array($navigation, 'entity_name', '');
            $navigation['authorization_id'] = $this->__get_from_array($navigation, 'authorization_id', 4);
            $navigation['url'] = $this->__get_from_array($navigation, 'url', $this->__get_from_array($navigation, 'navigation_name', ''));
            $NAVIGATIONS[] = $navigation;
        }
        // If parent doesn't exists, try to cms_complete_navigation_name() first
        for($i=0; $i<count($NAVIGATIONS); $i++){
            $navigation = $NAVIGATIONS[$i];
            $parent_name = $navigation['parent_name'];
            $query = $this->db->select('navigation_id')
                ->from(cms_table_name('main_navigation'))
                ->where('navigation_name', $parent_name)
                ->get();
            if($query->num_rows() == 0){
                $NAVIGATIONS[$i]['parent_name'] = $this->cms_complete_navigation_name($parent_name);
            }
        }

        return $NAVIGATIONS;
    }

    final private function __get_all_privileges()
    {
        $PRIVILEGES = array();
        // ADD PRIVILEGES
        foreach ($this->PRIVILEGES as $privilege) {
            $PRIVILEGES[] = $privilege;
        }
        // ADD BACKEND PRIVILEGES
        $verb_list = array('read','add','edit','delete','list','back_to_list','print','export');
        foreach ($this->BACKEND_NAVIGATIONS as $navigation) {
            foreach ($verb_list as $verb) {
                $PRIVILEGES[] = array(
                'privilege_name' => $verb.'_'.$this->__get_from_array($navigation, 'entity_name', ''),
                'description' => $verb.' '.$this->__get_from_array($navigation, 'entity_name', ''),
            );
            }
        }

        return $PRIVILEGES;
    }


    protected function do_activate()
    {
        //this should be overridden by module developer
        return true;
    }

    protected function do_deactivate()
    {
        //this should be overridden by module developer
        return true;
    }

    protected function do_upgrade($old_version)
    {
        //this should be overridden by module developer
        return true;
    }

    final private function register_module()
    {
        //insert to cms_module
        $data = array(
            'module_name' => $this->NAME,
            'module_path' => $this->cms_module_path(),
            'version' => $this->VERSION,
            'user_id' => $this->cms_user_id(),
        );
        $this->db->insert(cms_table_name('main_module'), $data);

        //get current cms_module_id as child_id
        $SQL = 'SELECT module_id FROM '.cms_table_name('main_module')." WHERE module_name='".addslashes($this->NAME)."'";
        $query = $this->db->query($SQL);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $child_id = $row->module_id;
        }

        //get parent_id
        if (isset($child_id)) {
            foreach ($this->DEPENDENCIES as $dependency) {
                $SQL = 'SELECT module_id FROM '.cms_table_name('main_module')." WHERE module_name='".addslashes($dependency)."'";
                $query = $this->db->query($SQL);
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $parent_id = $row->module_id;
                    $data = array(
                        'parent_id' => $parent_id,
                        'module_id' => $child_id,
                    );
                    $this->db->insert(cms_table_name('main_module_dependency'), $data);
                }
            }
        }
    }

    final private function unregister_module()
    {
        //get current cms_module_id as child_id
        $SQL = 'SELECT module_id FROM '.cms_table_name('main_module')." WHERE module_name='".addslashes($this->NAME)."'";
        $query = $this->db->query($SQL);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $child_id = $row->module_id;

            $where = array(
                'module_id' => $child_id,
            );
            $this->db->delete(cms_table_name('main_module_dependency'), $where);

            $where = array(
                'module_path' => $this->cms_module_path(),
            );
            $this->db->delete(cms_table_name('main_module'), $where);
        }
    }

    final private function child_module()
    {
        $SQL = 'SELECT module_id FROM '.cms_table_name('main_module')." WHERE module_name='".addslashes($this->NAME)."'";
        $query = $this->db->query($SQL);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $parent_id = $row->module_id;

            $SQL = '
	            SELECT module_name, module_path
	            FROM
	                '.cms_table_name('main_module_dependency').',
	                '.cms_table_name('main_module').'
	            WHERE
	                '.cms_table_name('main_module').'.module_id = '.cms_table_name('main_module_dependency').'.module_id AND
	                parent_id='.$parent_id;
            $query = $this->db->query($SQL);
            $result = array();
            foreach ($query->result() as $row) {
                $result[] = array(
                    'module_name' => $row->module_name,
                    'module_path' => $row->module_name,
                );
            }

            return $result;
        } else {
            return array();
        }
    }

    final protected function backup_database($table_names, $limit = 3000)
    {
        if(!is_array($table_names) || (is_array($table_names) && count($table_names) == 0)){
            return NULL;
        }
        if ($this->db->platform() == 'mysql' || $this->db->platform() == 'mysqli') {
            $module_path = $this->cms_module_path();
            $this->load->dbutil();
            $sql = '';

            // create DROP TABLE syntax
            for ($i = count($table_names) - 1; $i >= 0; --$i) {
                $table_name = $table_names[$i];
                $sql .= 'DROP TABLE IF EXISTS `'.$table_name.'`; '.PHP_EOL;
            }
            if ($sql != '') {
                $sql .= PHP_EOL;
            }

            // create CREATE TABLE and INSERT syntax

            $prefs = array(
                    'tables' => $table_names,
                    'ignore' => array(),
                    'format' => 'txt',
                    'filename' => 'mybackup.sql',
                    'add_drop' => false,
                    'add_insert' => true,
                    'newline' => PHP_EOL,
                  );
            $sql .= @$this->dbutil->backup($prefs);

            //write file
            @chmod(FCPATH.'modules/'.$module_path.'/assets/db/', 0777);
            $file_name = 'backup_'.date('Y-m-d_G-i-s').'.sql';
            file_put_contents(
                    FCPATH.'modules/'.$module_path.'/assets/db/'.$file_name,
                    $sql
                );
        }
    }
}
