<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for pmb_online
 *
 * @author No-CMS Module Generator
 */
class Install extends CMS_Module_Installer {
    /////////////////////////////////////////////////////////////////////////////
    // Default Variables
    /////////////////////////////////////////////////////////////////////////////

    protected $DEPENDENCIES = array();
    protected $NAME         = 'admin.pmb_online';
    protected $DESCRIPTION  = 'STIKI PMB Online (MySQL)';
    protected $VERSION      = '0.0.0';


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
        $module_path = $this->cms_module_path();

        $this->backup_database(array(
            $this->cms_complete_table_name('agama'),
            $this->cms_complete_table_name('asal_info_stiki'),
            $this->cms_complete_table_name('jurusan_sma_smk'),
            $this->cms_complete_table_name('mahasiswa'),
            $this->cms_complete_table_name('pekerjaan'),
            $this->cms_complete_table_name('prodi'),
            $this->cms_complete_table_name('provinsi')
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
        $this->remove_navigation($this->cms_complete_navigation_name('manage_provinsi'));
        $this->remove_navigation($this->cms_complete_navigation_name('manage_prodi'));
        $this->remove_navigation($this->cms_complete_navigation_name('manage_pekerjaan'));
        $this->remove_navigation($this->cms_complete_navigation_name('manage_mahasiswa'));
        $this->remove_navigation($this->cms_complete_navigation_name('manage_jurusan_sma_smk'));
        $this->remove_navigation($this->cms_complete_navigation_name('manage_asal_info_stiki'));
        $this->remove_navigation($this->cms_complete_navigation_name('manage_agama'));
        $this->remove_navigation($this->cms_complete_navigation_name('manage_agama'));


        // remove parent of all navigations
        $this->remove_navigation($this->cms_complete_navigation_name('index'));

		$this->remove_navigation($this->cms_complete_navigation_name('FrontEnd_Mahasiswa'));

        // import uninstall.sql
        $this->import_sql(BASEPATH.'../modules/'.$module_path.
            '/assets/db/uninstall.sql');

    }

    // CREATE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
    private function build_all(){
        $module_path = $this->cms_module_path();

		$this->add_navigation($this->cms_complete_navigation_name('FrontEnd_Mahasiswa'), 'Registrasi Mahasiswa',
			$module_path.'/frontend_mahasiswa/index/add', $this->PRIV_EVERYONE);

        // parent of all navigations
        $this->add_navigation($this->cms_complete_navigation_name('index'), 'Pmb Online',
            $module_path.'/pmb_online', $this->PRIV_EVERYONE);

        // add navigations
        $this->add_navigation($this->cms_complete_navigation_name('manage_agama'), 'Manage Agama',
            $module_path.'/manage_agama', $this->PRIV_AUTHORIZED, $this->cms_complete_navigation_name('index')
        );
        $this->add_navigation($this->cms_complete_navigation_name('manage_asal_info_stiki'), 'Manage Asal Info Stiki',
            $module_path.'/manage_asal_info_stiki', $this->PRIV_AUTHORIZED, $this->cms_complete_navigation_name('index')
        );
        $this->add_navigation($this->cms_complete_navigation_name('manage_jurusan_sma_smk'), 'Manage Jurusan Sma Smk',
            $module_path.'/manage_jurusan_sma_smk', $this->PRIV_AUTHORIZED, $this->cms_complete_navigation_name('index')
        );
        $this->add_navigation($this->cms_complete_navigation_name('manage_mahasiswa'), 'Manage Mahasiswa',
            $module_path.'/manage_mahasiswa', $this->PRIV_AUTHORIZED, $this->cms_complete_navigation_name('index')
        );
        $this->add_navigation($this->cms_complete_navigation_name('manage_pekerjaan'), 'Manage Pekerjaan',
            $module_path.'/manage_pekerjaan', $this->PRIV_AUTHORIZED, $this->cms_complete_navigation_name('index')
        );
        $this->add_navigation($this->cms_complete_navigation_name('manage_prodi'), 'Manage Prodi',
            $module_path.'/manage_prodi', $this->PRIV_AUTHORIZED, $this->cms_complete_navigation_name('index')
        );
        $this->add_navigation($this->cms_complete_navigation_name('manage_provinsi'), 'Manage Provinsi',
            $module_path.'/manage_provinsi', $this->PRIV_AUTHORIZED, $this->cms_complete_navigation_name('index')
        );


        // import install.sql
        $this->import_sql(BASEPATH.'../modules/'.$module_path.
            '/assets/db/install.sql');
    }

    // IMPORT SQL FILE
    private function import_sql($file_name){
        $this->execute_SQL(file_get_contents($file_name), '/*split*/');
    }

    // EXPORT DATABASE
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
        /*
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
		 *
		 */

    }
}