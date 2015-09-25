&lt;?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for {{ project_name }}
 *
 * @author No-CMS Module Generator
 */
class Info extends CMS_Module {    
    
    //////////////////////////////////////////////////////////////////////////////
    // ACTIVATION
    //////////////////////////////////////////////////////////////////////////////
    public function do_activate(){
        $this->remove_all();
        $this->build_all();
    }

    //////////////////////////////////////////////////////////////////////////////
    // DEACTIVATION
    //////////////////////////////////////////////////////////////////////////////
    public function do_deactivate(){
        $this->backup_database(array(
            {{ table_list }}
        ));
        $this->remove_all();
    }

    //////////////////////////////////////////////////////////////////////////////
    // REMOVE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
    //////////////////////////////////////////////////////////////////////////////
    private function remove_all(){
        $module_path = $this->cms_module_path();

        // remove navigations
        {{ remove_navigations }}

        // remove parent of all navigations
        $this->cms_remove_navigation($this->cms_complete_navigation_name('{{ navigation_parent_name }}'));

        // remove privileges
        {{ remove_privileges }}

        // drop tables
        {{ drop_table_forge }}
    }

    //////////////////////////////////////////////////////////////////////////////
    // UPGRADE
    //////////////////////////////////////////////////////////////////////////////
    public function do_upgrade($old_version){
        $version_part = explode('.', $old_version);
        $major        = $version_part[0];
        $minor        = $version_part[1];
        $build        = $version_part[2];
        $module_path  = $this->cms_module_path();

        //////////////////////////////////////////////////////////////////////////////
        // TODO: Add your migration logic here.
        // e.g:
        // if($major <= 0 && $minor <= 0 && $build <=0){
        //      // add some missing fields, navigations or privileges
        // }
        //////////////////////////////////////////////////////////////////////////////
    }

    //////////////////////////////////////////////////////////////////////////////
    // CREATE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
    //////////////////////////////////////////////////////////////////////////////
    private function build_all(){
        $this->build_navigations();
        $this->build_privileges();
        $this->build_configs();
        $this->build_widgets();
        $this->build_tables();
        $this->insert_data();
    }

    //////////////////////////////////////////////////////////////////////////////
    // ADD NAVIGATIONS
    //////////////////////////////////////////////////////////////////////////////
    private function build_navigations(){
        $module_path = $this->cms_module_path();

        // NAVIGATION'S PARENT
        $this->cms_add_navigation(
                $this->cms_complete_navigation_name('{{ navigation_parent_name }}'),  // Navigation name
                '{{ project_caption }}',  // Title
                $module_path.'/{{ main_controller }}',  // URL Path
                $this->PRIV_EVERYONE,  // Authorization
                NULL, // Parent Navigation Name
                NULL, // Index
                NULL, // Description
                NULL, // Bootstrap Glyph Class
                NULL, // Default Theme
                NULL, // Default Layout
                NULL, // Notification URL Path
                0,    // Hidden
                ''    // Static Content
            );

        {{ add_navigations }}
    }

    //////////////////////////////////////////////////////////////////////////////
    // ADD PRIVILEGES
    //////////////////////////////////////////////////////////////////////////////
    private function build_privileges(){
        $module_path = $this->cms_module_path();
        
        {{ add_privileges }}
    }

    //////////////////////////////////////////////////////////////////////////////
    // ADD CONFIGS
    //////////////////////////////////////////////////////////////////////////////
    private function build_configs(){
        $module_path = $this->cms_module_path();
        // TODO: add configs
    }

    //////////////////////////////////////////////////////////////////////////////
    // ADD WIDGETS
    //////////////////////////////////////////////////////////////////////////////
    private function build_widgets(){
        $module_path = $this->cms_module_path();
        // TODO: add widgets
    }

    //////////////////////////////////////////////////////////////////////////////
    // ADD TABLES
    //////////////////////////////////////////////////////////////////////////////
    private function build_tables(){
        $module_path = $this->cms_module_path();

        {{ create_table_forge }}
    }

    //////////////////////////////////////////////////////////////////////////////
    // INSERT DATA
    //////////////////////////////////////////////////////////////////////////////
    private function insert_data(){
        $module_path = $this->cms_module_path();
        
        {{ insert_table }}
    }

    //////////////////////////////////////////////////////////////////////////////
    // EXPORT DATABASE
    //////////////////////////////////////////////////////////////////////////////
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
