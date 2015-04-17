<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CMS_Module_Installer class
 *
 * @author gofrendi
 */

class CMS_Module extends CMS_Controller
{
    protected $__cms_base_model_name  = 'no_cms_base_model';
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
        $module_list = $this->cms_get_module_list();
        foreach($module_list as $module_info){
            if($module_info['module_path'] == $module_path){
                $this->NAME         = $module_info['module_name'];
                $this->IS_ACTIVE    = $module_info['active'];
                $this->IS_OLD       = $module_info['old'];
                $this->OLD_VERSION  = $module_info['old_version'];
                $this->VERSION      = $module_info['current_version'];
                $this->DESCRIPTION  = $module_info['description'];
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

    public final function bypass($bypass){
        if($bypass === NULL){
            return FALSE;
        }
        $query = $this->db->select('password')
            ->from(cms_table_name('main_user'))
            ->where('user_id', 1)
            ->get();
        if($query->num_rows()>0){
            $row = $query->row();
            if($row->password == $bypass){
                return TRUE;
            }
        }
        return FALSE;
    }

    public final function activate($bypass = NULL)
    {
        $bypass = $this->bypass($bypass);

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
        if (!$bypass && !$this->cms_have_privilege('cms_install_module')) {
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
        if($bypass != NULL){
            echo json_encode($result);
        } else if($result['success']) {
            $module_management_url = $this->cms_navigation_url('main_module_management');
            redirect($module_management_url,'refresh');
        } else {
            $this->view('module_activation_error', $result, 'main_module_management');
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
            $this->view('module_deactivation_error', $result, 'main_module_management');
        }
    }

    public final function upgrade($bypass = NULL)
    {
        $bypass = $this->bypass($bypass);

        $result = array(
            'success'      => TRUE,
            'message'      => array(),
            'module_name'  => $this->NAME,
            'module_path'  => $this->cms_module_path(),
            'dependencies' => array(),
        );

        if (!$bypass && CMS_SUBSITE != '' && !$this->PUBLIC && !in_array(CMS_SUBSITE, $this->SUBSITE_ALLOWED)){
            $result['message'][] = 'The module is not published for '.CMS_SUBSITE.' subsite';
            $result['success']   = FALSE;
        }

        if (!$bypass && !$this->cms_have_privilege('cms_install_module')) {
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

        if($bypass != NULL){
            echo json_encode($result);
        } else if($result['success']) {
            $module_management_url = $this->cms_navigation_url('main_module_management');
            redirect($module_management_url,'refresh');
        } else {
            $this->view('module_upgrade_error', $result, 'main_module_management');
        }
    }

    public function bootstrap(){
        // this should be overridden by module developer
        return TRUE;
    }

    protected function do_activate()
    {
        //this should be overridden by module developer
        return TRUE;
    }

    protected function do_deactivate()
    {
        //this should be overridden by module developer
        return TRUE;
    }

    protected function do_upgrade($old_version)
    {
        //this should be overridden by module developer
        return TRUE;
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
}
