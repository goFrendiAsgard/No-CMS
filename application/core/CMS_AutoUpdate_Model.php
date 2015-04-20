<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class CMS_AutoUpdate_Model extends CMS_Model{
    private static $module_updated = FALSE;

    public function __construct()
    {
        parent::__construct();
        
        // core seamless update
        $this->__update();
        // module update
        if(!self::$module_updated){  
            self::$module_updated = TRUE;          
            $this->__update_module();
        }
    }

    private function __update(){
        $old_version = cms_config('__cms_version');
        $current_version = '0.7.5';
        // get major, minor and rev version
        $old_version_component = explode('-', $old_version);
        $old_version_component = $old_version_component[0];
        $old_version_component = explode('.', $old_version_component);
        $major_version = $old_version_component[0];
        $minor_version = $old_version_component[1];
        $rev_version = $old_version_component[2]; 


        if($old_version !== NULL && $old_version != '' && $old_version !== $current_version){
            
            // write new version
            cms_config('__cms_version', $current_version);
        }
    }
  
    private function __update_module(){
        $bypass = '';
        $query = $this->db->select('password')
            ->from(cms_table_name('main_user'))
            ->where('user_id', 1)
            ->get();
        if($query->num_rows()>0){
            $row = $query->row();
            $bypass = $row->password;
        }
        if($bypass != ''){
            $module_list = $this->cms_get_module_list();
            foreach($module_list as $module){
                $module_path     = $module['module_path'];
                $module_name     = $module['module_name'];
                $old_version     = $module['old_version'];
                $current_version = $module['current_version'];
                $active          = $module['active'];
                $upgrade_link    = $module['upgrade_link'];
                if($active && $old_version != $current_version){
                    $url = str_replace(site_url(), '', $upgrade_link);
                    $url = trim($url, '/');
                    $response = @Modules::run($url, $bypass);
                }
            }
        }
    }

    
}