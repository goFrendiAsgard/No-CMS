<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for teldrassil
 *
 * @author No-CMS Module Generator
 */
class Info extends CMS_Module {

    public function do_activate(){
        $this->remove_all();
        $this->build_all();
    }

    // DEACTIVATION
    public function do_deactivate(){
        $this->backup_database(array(

        ));
        $this->remove_all();
    }

    // UPGRADE
    public function do_upgrade($old_version){
        // Add your migration logic here.
    }


    // REMOVE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
    private function remove_all(){
        $module_path = $this->cms_module_path();

        // remove navigations


        // remove parent of all navigations
        $this->cms_remove_navigation($this->n('index'));

        // drop tables

    }

    // CREATE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
    private function build_all(){
        $module_path = $this->cms_module_path();

        // parent of all navigations
        $this->cms_add_navigation($this->n('index'), 'Theme Generator',
            $module_path == 'teldrassil'? $module_path: $module_path.'/teldrassil', PRIV_AUTHORIZED,
                "main_management", NULL, 'Theme Generator', NULL, NULL, 'default-one-column');

        // add navigations


        // create tables


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

}
