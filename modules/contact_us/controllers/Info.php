<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for contact_us
 *
 * @author No-CMS Module Generator
 */
class Info extends CMS_Module {
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
            $this->t('message')
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

        $this->cms_remove_quicklink($this->n('index'));

        // remove navigations
        $this->cms_remove_navigation($this->n('manage_message'));


        // remove parent of all navigations
        $this->cms_remove_navigation($this->n('index'));

        // drop tables
        $this->dbforge->drop_table($this->t('message'), TRUE);
    }

    // CREATE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
    private function build_all(){
        $module_path = $this->cms_module_path();

        // parent of all navigations
        $this->cms_add_navigation($this->n('index'), 'Contact Us',
            $module_path.'/contact_us', PRIV_EVERYONE, NULL, NULL, 'Contact Us Menu', 'glyphicon-envelope');

        // add navigations
        $this->cms_add_navigation($this->n('manage_message'), 'Manage Message',
            $module_path.'/manage_message', PRIV_AUTHORIZED, $this->n('index')
        );

        $this->cms_add_quicklink($this->n('index'));


        // create tables
        // message
        $fields = array(
            'id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'name'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'content'=> array("type"=>'text', "null"=>TRUE),
            'email'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($this->t('message'));


    }
}
