<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for shop_manager
 *
 * @author No-CMS Module Generator
 */
class Install extends CMS_Module_Installer {
    /////////////////////////////////////////////////////////////////////////////
    // Default Variables
    /////////////////////////////////////////////////////////////////////////////

    protected $DEPENDENCIES = array();
    protected $NAME         = 'gofrendi.shop_manager';
    protected $DESCRIPTION  = 'Shop Manager ...';
    protected $VERSION      = '0.0.0';

    public function cms_complete_table_name($table_name){
        $this->load->helper($this->cms_module_path().'/function');
        if(function_exists('cms_complete_table_name')){
            return cms_complete_table_name($table_name);
        }else{
            return parent::cms_complete_table_name($table_name);
        }
    }


    /////////////////////////////////////////////////////////////////////////////
    // Default Functions
    /////////////////////////////////////////////////////////////////////////////

    // ACTIVATION
    protected function do_activate(){
        $this->remove_all();
        $this->build_all();
    }

    // DEACTIVATION
    protected function do_deactivate(){
        $this->backup_database(array(
            $this->cms_complete_table_name('option'),
            $this->cms_complete_table_name('payment_method')
        ));
        $this->remove_all();
    }

    // UPGRADE
    protected function do_upgrade($old_version){
        // Add your migration logic here.
    }

    // OVERRIDE THIS FUNCTION TO PROVIDE "Module Setting" FEATURE
    public function setting(){
        $module_directory = $this->cms_module_path();
        $data = array();
        $data['IS_ACTIVE'] = $this->IS_ACTIVE;
        $data['module_directory'] = $module_directory;
        if(!$this->IS_ACTIVE){
            // get setting
            $module_table_prefix = $this->input->post('module_table_prefix');
            $module_prefix       = $this->input->post('module_prefix');
            // set values
            if(isset($module_table_prefix) && $module_table_prefix !== FALSE){
                cms_module_config($module_directory, 'module_table_prefix', $module_table_prefix);
            }
            if(isset($module_prefix) && $module_prefix !== FALSE){
                cms_module_prefix($module_directory, $module_prefix);
            }
            // get values
            $data['module_table_prefix'] = cms_module_config($module_directory, 'module_table_prefix');
            $data['module_prefix']       = cms_module_prefix($module_directory);
        }
        $this->view($module_directory.'/install_setting', $data, 'main_module_management');
    }

    /////////////////////////////////////////////////////////////////////////////
    // Private Functions
    /////////////////////////////////////////////////////////////////////////////

    // REMOVE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
    private function remove_all(){
        $module_path = $this->cms_module_path();

        // remove navigations
        $this->remove_navigation($this->cms_complete_navigation_name('manage_payment_method'));
        $this->remove_navigation($this->cms_complete_navigation_name('manage_option'));
        $this->remove_navigation($this->cms_complete_navigation_name('register'));


        // remove parent of all navigations
        $this->remove_navigation($this->cms_complete_navigation_name('index'));

        // drop tables
        $this->dbforge->drop_table($this->cms_complete_table_name('payment_method'), TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('option'), TRUE);
    }

    // CREATE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
    private function build_all(){
        $module_path = $this->cms_module_path();

        // parent of all navigations
        $this->add_navigation($this->cms_complete_navigation_name('index'), 'Shop Manager',
            $module_path.'/shop_manager', $this->PRIV_EVERYONE);

        // add navigations
        $this->add_navigation($this->cms_complete_navigation_name('manage_option'), 'Manage Option',
            $module_path.'/manage_option', $this->PRIV_AUTHORIZED, $this->cms_complete_navigation_name('index')
        );
        $this->add_navigation($this->cms_complete_navigation_name('manage_payment_method'), 'Manage Payment Method',
            $module_path.'/manage_payment_method', $this->PRIV_AUTHORIZED, $this->cms_complete_navigation_name('index')
        );
        $this->add_navigation($this->cms_complete_navigation_name('register'), 'Register',
            $module_path.'/register', $this->PRIV_EVERYONE, $this->cms_complete_navigation_name('register'));


        // create tables
        // option
        $fields = array(
            'id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'user_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'payment_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'name'=> array("type"=>'varchar', "constraint"=>255, "null"=>TRUE),
            'email'=> array("type"=>'varchar', "constraint"=>255, "null"=>TRUE),
            'phone_number_1'=> array("type"=>'varchar', "constraint"=>45, "null"=>TRUE),
            'phone_number_2'=> array("type"=>'varchar', "constraint"=>45, "null"=>TRUE),
            'address'=> array("type"=>'varchar', "constraint"=>100, "null"=>TRUE),
            'town'=> array("type"=>'varchar', "constraint"=>100, "null"=>TRUE),
            'use_cart'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'use_domain'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'custom_domain'=> array("type"=>'varchar', "constraint"=>100, "null"=>TRUE),
            'use_email_notification'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'gmail_account'=> array("type"=>'varchar', "constraint"=>255, "null"=>TRUE),
            'gmail_password'=> array("type"=>'varchar', "constraint"=>255, "null"=>TRUE),
            'use_fb_share'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'use_multiple_images'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'use_print_nota'=> array("type"=>'varchar', "constraint"=>45, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('option'));

        // payment_method
        $fields = array(
            'id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'name'=> array("type"=>'varchar', "constraint"=>45, "null"=>TRUE),
            'description'=> array("type"=>'varchar', "constraint"=>45, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('payment_method'));


    }

    // EXPORT DATABASE
    private function backup_database($table_names, $limit = 100){
        if($this->db->platform() == 'mysql' || $this->db->platform() == 'mysqli'){
            $module_path = $this->cms_module_path();
            $this->load->dbutil();
            $sql = '';

            // create DROP TABLE syntax
            for($i=count($table_names)-1; $i>=0; $i--){
                $table_name = $table_names[$i];
                $sql .= 'DROP TABLE IF EXISTS `'.$table_name.'`; '.PHP_EOL;
            }
            if($sql !='')$sql.= PHP_EOL;

            // create CREATE TABLE and INSERT syntax

            $prefs = array(
                    'tables'      => $table_names,
                    'ignore'      => array(),
                    'format'      => 'txt',
                    'filename'    => 'mybackup.sql',
                    'add_drop'    => FALSE,
                    'add_insert'  => TRUE,
                    'newline'     => PHP_EOL
                  );
            $sql.= @$this->dbutil->backup($prefs);

            //write file
            chmod(FCPATH.'modules/'.$module_path.'/assets/db/', 0777);
            $file_name = 'backup_'.date('Y-m-d_G-i-s').'.sql';
            file_put_contents(
                    FCPATH.'modules/'.$module_path.'/assets/db/'.$file_name,
                    $sql
                );
        }

    }
}
