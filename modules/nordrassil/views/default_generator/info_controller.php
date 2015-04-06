&lt;?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for {{ project_name }}
 *
 * @author No-CMS Module Generator
 */
class Info extends CMS_Module {    

    // ACTIVATION
    public function do_activate(){
        $this->remove_all();
        $this->build_all();
    }

    // DEACTIVATION
    public function do_deactivate(){
        $this->backup_database(array(
            {{ table_list }}
        ));
        $this->remove_all();
    }

    // UPGRADE
    public function do_upgrade($old_version){
        $version_part = explode('.', $old_version);
        $major        = $version_part[0];
        $minor        = $version_part[1];
        $build        = $version_part[2];
        $module_path  = $this->cms_module_path();
        // TODO: Add your migration logic here.
    }


    // REMOVE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
    private function remove_all(){
        $module_path = $this->cms_module_path();

        // remove navigations
{{ remove_navigations }}

        // remove parent of all navigations
        $this->cms_remove_navigation($this->cms_complete_navigation_name('{{ navigation_parent_name }}'));

        // drop tables
        {{ drop_table_forge }}
    }

    // CREATE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
    private function build_all(){
        $module_path = $this->cms_module_path();

        // parent of all navigations
        $this->cms_add_navigation($this->cms_complete_navigation_name('{{ navigation_parent_name }}'), '{{ project_caption }}',
            $module_path.'/{{ main_controller }}', $this->PRIV_EVERYONE);

        // add navigations
{{ add_navigations }}

        // create tables
        {{ create_table_forge }}

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
