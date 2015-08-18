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

    private function __update_module(){
        $bypass = '';
        $query = $this->db->select('password')
            ->from($this->cms_user_table_name())
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

    private function __update(){

        $old_version = cms_config('__cms_version');
        $current_version = '0.7.6';
        if($old_version == $current_version){ return 0; }
        // get major, minor and rev version
        $old_version_component = explode('-', $old_version);
        $old_version_component = $old_version_component[0];
        $old_version_component = explode('.', $old_version_component);
        $major_version = $old_version_component[0];
        $minor_version = $old_version_component[1];
        $rev_version = $old_version_component[2]; 

        $this->load->dbforge();

        // 0.7.6
        if($major_version <= '0' && $minor_version <= '7' && $rev_version < '6'){
            // new table : cms_main_route
            $fields = array(
                    'route_id'      => array( 'type' => 'INT', 'constraint' => 20, 'unsigned' => TRUE, 'auto_increment' => TRUE, ),
                    'key'           => array( 'type' => 'TEXT', ),
                    'value'         => array( 'type' => 'TEXT', ),
                    'description'   => array( 'type' => 'TEXT', ),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('route_id', TRUE);
            $this->dbforge->create_table(cms_table_name('main_route'));

            // modify table : cms_main_navigation
            $fields = array('hidden' => array( 'type' => 'INT', 'default' => '0'),);
            $this->dbforge->add_column(cms_table_name('main_navigation'), $fields);

            // add navigation
            $this->cms_add_navigation('main_route_management', 'Route', 'main/route', 4, 'main_management');

            // determine config path
            $config_path = CMS_SUBSITE == ''?
                APPATH.'config/main/' :
                APPATH.'config/site-'.CMS_SUBSITE.'/';
            $original_route_config = $config_path.'routes.php';
            $extended_route_config = $config_path.'extended_routes.php';
            // include extended route to default route
            file_put_contents($original_route_config, 
                file_get_contents($original_route_config).PHP_EOL.
                'include(APPPATH.\'config/extended_routes.php\');'.PHP_EOL);
            // add extended routes
            file_put_contents($extended_route_config, 
                '<?php if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');'.PHP_EOL.
                '$routes = array();'.PHP_EOL);

            // copy new configuration setting
            $content = file_get_contents(APPPATH.'config/first-time/third_party_config/kcfinder_config.php');
            $content = str_replace(
                array('{{ FCPATH }}', '{{ BASE_URL }}'), 
                array(FCPATH, base_url()), 
                $content);
            file_put_contents(FCPATH.'assets/kcfinder/config.php', $content);
        }

        // TODO : Write your upgrade script here

        // write new version
        if($old_version !== NULL && $old_version != '' && $old_version !== $current_version){
            cms_config('__cms_version', $current_version);
        }
    }
    
}