&lt;?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for
 *
 * @author No-CMS Module Generator
 */
class Install extends CMS_Module_Installer {
    protected $DEPENDENCIES = array();
    protected $NAME = '{{ namespace }}';

    //this should be what happen when user install this module
    protected function do_install(){        
        $this->remove_all();
        $this->build_all();
    }
    //this should be what happen when user uninstall this module
    protected function do_uninstall(){
        $this->backup_database(array({{ table_list }}));
        $this->remove_all();
    }
    
    private function remove_all(){
        $module_path = $this->cms_module_path();
    
        // remove navigations
{{ remove_navigations }}
        
        // remove parent of all navigations
        $this->remove_navigation("{{ navigation_parent_name }}");

        // import uninstall.sql
        $this->import_sql(BASEPATH.'../modules/'.$module_path.
            '/assets/db/uninstall.sql');
            
    }
    
    private function build_all(){
        $module_path = $this->cms_module_path();
    
        // parent of all navigations
        $this->add_navigation("{{ navigation_parent_name }}", "{{ project_caption }}", 
            $module_path."/{{ main_controller }}", $this->PRIV_EVERYONE);
        
        // add navigations
{{ add_navigations }}
        
        // import install.sql
        $this->import_sql(BASEPATH.'../modules/'.$module_path.
            '/assets/db/install.sql');
    }
    
    private function import_sql($file_name){
        $sql_array = explode('/*split*/',
                file_get_contents($file_name)                
            );
        foreach($sql_array as $sql){
            $this->db->query($sql);
        }
    }
    
    private function backup_database($table_names, $limit = 100){
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
        $sql.= $this->dbutil->backup($prefs); 
        
        //write file
        $file_name = 'backup_'.date('Y-m-d_G:i:s').'.sql';
        file_put_contents(
                BASEPATH.'../modules/'.$module_path.'/assets/db/'.$file_name,
                $sql
            );     
        
    }
}
