<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for example
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
            $this->cms_complete_table_name('job'),
            $this->cms_complete_table_name('hobby'),
            $this->cms_complete_table_name('country'),
            $this->cms_complete_table_name('commodity'),
            $this->cms_complete_table_name('tourism'),
            $this->cms_complete_table_name('city'),
            $this->cms_complete_table_name('citizen'),
            $this->cms_complete_table_name('city_commodity'),
            $this->cms_complete_table_name('city_tourism'),
            $this->cms_complete_table_name('citizen_hobby')
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
        $this->cms_remove_navigation($this->cms_complete_navigation_name('browse_city'));
        $this->cms_remove_navigation($this->cms_complete_navigation_name('manage_city'));
        $this->cms_remove_navigation($this->cms_complete_navigation_name('manage_tourism'));
        $this->cms_remove_navigation($this->cms_complete_navigation_name('manage_commodity'));
        $this->cms_remove_navigation($this->cms_complete_navigation_name('manage_country'));
        $this->cms_remove_navigation($this->cms_complete_navigation_name('manage_hobby'));
        $this->cms_remove_navigation($this->cms_complete_navigation_name('manage_job'));


        // remove parent of all navigations
        $this->cms_remove_navigation($this->cms_complete_navigation_name('index'));

        // drop tables
        $this->dbforge->drop_table($this->cms_complete_table_name('citizen_hobby'), TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('city_tourism'), TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('city_commodity'), TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('citizen'), TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('city'), TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('tourism'), TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('commodity'), TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('country'), TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('hobby'), TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('job'), TRUE);
    }

    // CREATE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
    private function build_all(){
        $module_path = $this->cms_module_path();

        // parent of all navigations
        $this->cms_add_navigation($this->cms_complete_navigation_name('index'), 'Example',
            $module_path.'/example', $this->PRIV_EVERYONE);

        // add navigations
        $this->cms_add_navigation($this->cms_complete_navigation_name('browse_city'), 'Browse City',
            $module_path.'/browse_city', $this->PRIV_EVERYONE, $this->cms_complete_navigation_name('index')
        );
        $this->cms_add_navigation($this->cms_complete_navigation_name('manage_job'), 'Manage Job',
            $module_path.'/manage_job', $this->PRIV_AUTHORIZED, $this->cms_complete_navigation_name('index')
        );
        $this->cms_add_navigation($this->cms_complete_navigation_name('manage_hobby'), 'Manage Hobby',
            $module_path.'/manage_hobby', $this->PRIV_AUTHORIZED, $this->cms_complete_navigation_name('index')
        );
        $this->cms_add_navigation($this->cms_complete_navigation_name('manage_country'), 'Manage Country',
            $module_path.'/manage_country', $this->PRIV_AUTHORIZED, $this->cms_complete_navigation_name('index')
        );
        $this->cms_add_navigation($this->cms_complete_navigation_name('manage_commodity'), 'Manage Commodity',
            $module_path.'/manage_commodity', $this->PRIV_AUTHORIZED, $this->cms_complete_navigation_name('index')
        );
        $this->cms_add_navigation($this->cms_complete_navigation_name('manage_tourism'), 'Manage Tourism',
            $module_path.'/manage_tourism', $this->PRIV_AUTHORIZED, $this->cms_complete_navigation_name('index')
        );
        $this->cms_add_navigation($this->cms_complete_navigation_name('manage_city'), 'Manage City',
            $module_path.'/manage_city', $this->PRIV_AUTHORIZED, $this->cms_complete_navigation_name('index')
        );

        // create tables
        // job
        $fields = array(
            'job_id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'name'=> array("type"=>'varchar', "constraint"=>20, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('job_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('job'));

        // hobby
        $fields = array(
            'hobby_id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'name'=> array("type"=>'varchar', "constraint"=>20, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('hobby_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('hobby'));

        // country
        $fields = array(
            'country_id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'name'=> array("type"=>'varchar', "constraint"=>20, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('country_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('country'));

        // commodity
        $fields = array(
            'commodity_id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'name'=> array("type"=>'varchar', "constraint"=>20, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('commodity_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('commodity'));

        // tourism
        $fields = array(
            'tourism_id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'name'=> array("type"=>'varchar', "constraint"=>20, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('tourism_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('tourism'));

        // city
        $fields = array(
            'city_id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'country_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'name'=> array("type"=>'varchar', "constraint"=>20, "null"=>TRUE),
            'tourism'=> array("type"=>'varchar', "constraint"=>11, "null"=>TRUE),
            'commodity'=> array("type"=>'varchar', "constraint"=>11, "null"=>TRUE),
            'citizen'=> array("type"=>'varchar', "constraint"=>11, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('city_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('city'));

        // citizen
        $fields = array(
            'citizen_id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'city_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'name'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'birthdate'=> array("type"=>'date', "null"=>TRUE),
            'job_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'hobby'=> array("type"=>'varchar', "constraint"=>11, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('citizen_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('citizen'));

        // city_commodity
        $fields = array(
            'id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'city_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'commodity_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'priority'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('city_commodity'));

        // city_tourism
        $fields = array(
            'id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'city_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'tourism_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('city_tourism'));

        // citizen_hobby
        $fields = array(
            'id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'citizen_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'hobby_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('citizen_hobby'));



        // insert data
        $this->db->insert_batch($this->cms_complete_table_name('tourism'), array(
            array('tourism_id' => '1', 'name' => 'Amusement Park'),
            array('tourism_id' => '2', 'name' => 'Beach'),
        ));
        $this->db->insert_batch($this->cms_complete_table_name('country'), array(
            array('country_id' => '1', 'name' => 'USA'),
            array('country_id' => '2', 'name' => 'Indonesia'),
            array('country_id' => '3', 'name' => 'Japan'),
        ));
        $this->db->insert_batch($this->cms_complete_table_name('hobby'), array(
            array('hobby_id' => '1', 'name' => 'Reading'),
            array('hobby_id' => '2', 'name' => 'Gardenning'),
        ));
        $this->db->insert_batch($this->cms_complete_table_name('job'), array(
            array('job_id' => '1', 'name' => 'Teacher'),
            array('job_id' => '2', 'name' => 'Programmer'),
        ));

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
