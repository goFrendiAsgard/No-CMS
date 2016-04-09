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
        $this->remove_all();
    }

    // UPGRADE
    public function do_upgrade($old_version){
        // Add your migration logic here.
    }


    // REMOVE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
    private function remove_all(){
        $module_path = $this->cms_module_path();
        // remove navigation
        $this->cms_remove_navigation($this->n('index'));
    }

    // CREATE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
    private function build_all(){
        $module_path = $this->cms_module_path();
        // create navigation
        $this->cms_add_navigation($this->n('index'), 'Theme Generator',
            $module_path == 'teldrassil'? $module_path: $module_path.'/teldrassil', PRIV_AUTHORIZED,
                "main_management", NULL, 'Theme Generator', NULL, NULL, 'default-one-column');
    }

}
