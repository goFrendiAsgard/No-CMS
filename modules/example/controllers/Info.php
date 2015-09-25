<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for example
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

    //////////////////////////////////////////////////////////////////////////////
    // REMOVE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
    //////////////////////////////////////////////////////////////////////////////
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

        // remove privileges
        // job
        $this->cms_remove_privilege($this->cms_complete_navigation_name('read_job'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('add_job'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('edit_job'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('delete_job'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('list_job'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('back_to_list_job'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('print_job'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('export_job'));
        
        // hobby
        $this->cms_remove_privilege($this->cms_complete_navigation_name('read_hobby'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('add_hobby'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('edit_hobby'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('delete_hobby'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('list_hobby'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('back_to_list_hobby'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('print_hobby'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('export_hobby'));
        
        // country
        $this->cms_remove_privilege($this->cms_complete_navigation_name('read_country'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('add_country'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('edit_country'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('delete_country'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('list_country'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('back_to_list_country'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('print_country'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('export_country'));
        
        // commodity
        $this->cms_remove_privilege($this->cms_complete_navigation_name('read_commodity'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('add_commodity'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('edit_commodity'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('delete_commodity'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('list_commodity'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('back_to_list_commodity'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('print_commodity'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('export_commodity'));
        
        // tourism
        $this->cms_remove_privilege($this->cms_complete_navigation_name('read_tourism'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('add_tourism'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('edit_tourism'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('delete_tourism'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('list_tourism'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('back_to_list_tourism'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('print_tourism'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('export_tourism'));
        
        // city
        $this->cms_remove_privilege($this->cms_complete_navigation_name('read_city'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('add_city'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('edit_city'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('delete_city'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('list_city'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('back_to_list_city'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('print_city'));
        $this->cms_remove_privilege($this->cms_complete_navigation_name('export_city'));

        // drop tables
        $this->dbforge->drop_table($this->cms_complete_table_name('citizen_hobby'),        TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('city_tourism'),         TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('city_commodity'),       TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('citizen'),              TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('city'),                 TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('tourism'),              TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('commodity'),            TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('country'),              TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('hobby'),                TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('job'),                  TRUE);
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
                $this->cms_complete_navigation_name('index'),  // Navigation name
                'Example',  // Title
                $module_path.'/example',  // URL Path
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

        // browse_city
        $this->cms_add_navigation(
                $this->cms_complete_navigation_name('browse_city'),  //  Navigation name
                'Browse City',  // Title
                $module_path.'/browse_city',  // URL Path 
                $this->PRIV_EVERYONE,   // Authorization
                $this->cms_complete_navigation_name('index'),  // Parent Navigation Name
                NULL, // Index
                NULL, // Description
                NULL, // Bootstrap Glyph Class
                NULL, // Default Theme
                NULL, // Default Layout
                NULL, // Notification URL Path
                0,    // Hidden
                ''    // Static Content
            );
        
        // manage_job
        $this->cms_add_navigation(
                $this->cms_complete_navigation_name('manage_job'),  //  Navigation name
                'Manage Job',  // Title
                $module_path.'/manage_job',  // URL Path 
                $this->PRIV_AUTHORIZED,   // Authorization
                $this->cms_complete_navigation_name('index'),  // Parent Navigation Name
                NULL,                   // Index
                NULL,                   // Description
                NULL,                   // Bootstrap Glyph Class
                NULL,                   // Default Theme
                'default-one-column',   // Default Layout
                NULL,                   // Notification URL Path
                0,                      // Hidden
                ''                      // Static Content
            );
            
        // manage_hobby
        $this->cms_add_navigation(
                $this->cms_complete_navigation_name('manage_hobby'),  //  Navigation name
                'Manage Hobby',  // Title
                $module_path.'/manage_hobby',  // URL Path 
                $this->PRIV_AUTHORIZED,   // Authorization
                $this->cms_complete_navigation_name('index'),  // Parent Navigation Name
                NULL,                   // Index
                NULL,                   // Description
                NULL,                   // Bootstrap Glyph Class
                NULL,                   // Default Theme
                'default-one-column',   // Default Layout
                NULL,                   // Notification URL Path
                0,                      // Hidden
                ''                      // Static Content
            );
            
        // manage_country
        $this->cms_add_navigation(
                $this->cms_complete_navigation_name('manage_country'),  //  Navigation name
                'Manage Country',  // Title
                $module_path.'/manage_country',  // URL Path 
                $this->PRIV_AUTHORIZED,   // Authorization
                $this->cms_complete_navigation_name('index'),  // Parent Navigation Name
                NULL,                   // Index
                NULL,                   // Description
                NULL,                   // Bootstrap Glyph Class
                NULL,                   // Default Theme
                'default-one-column',   // Default Layout
                NULL,                   // Notification URL Path
                0,                      // Hidden
                ''                      // Static Content
            );
            
        // manage_commodity
        $this->cms_add_navigation(
                $this->cms_complete_navigation_name('manage_commodity'),  //  Navigation name
                'Manage Commodity',  // Title
                $module_path.'/manage_commodity',  // URL Path 
                $this->PRIV_AUTHORIZED,   // Authorization
                $this->cms_complete_navigation_name('index'),  // Parent Navigation Name
                NULL,                   // Index
                NULL,                   // Description
                NULL,                   // Bootstrap Glyph Class
                NULL,                   // Default Theme
                'default-one-column',   // Default Layout
                NULL,                   // Notification URL Path
                0,                      // Hidden
                ''                      // Static Content
            );
            
        // manage_tourism
        $this->cms_add_navigation(
                $this->cms_complete_navigation_name('manage_tourism'),  //  Navigation name
                'Manage Tourism',  // Title
                $module_path.'/manage_tourism',  // URL Path 
                $this->PRIV_AUTHORIZED,   // Authorization
                $this->cms_complete_navigation_name('index'),  // Parent Navigation Name
                NULL,                   // Index
                NULL,                   // Description
                NULL,                   // Bootstrap Glyph Class
                NULL,                   // Default Theme
                'default-one-column',   // Default Layout
                NULL,                   // Notification URL Path
                0,                      // Hidden
                ''                      // Static Content
            );
            
        // manage_city
        $this->cms_add_navigation(
                $this->cms_complete_navigation_name('manage_city'),  //  Navigation name
                'Manage City',  // Title
                $module_path.'/manage_city',  // URL Path 
                $this->PRIV_AUTHORIZED,   // Authorization
                $this->cms_complete_navigation_name('index'),  // Parent Navigation Name
                NULL,                   // Index
                NULL,                   // Description
                NULL,                   // Bootstrap Glyph Class
                NULL,                   // Default Theme
                'default-one-column',   // Default Layout
                NULL,                   // Notification URL Path
                0,                      // Hidden
                ''                      // Static Content
            );
    }

    //////////////////////////////////////////////////////////////////////////////
    // ADD PRIVILEGES
    //////////////////////////////////////////////////////////////////////////////
    private function build_privileges(){
        $module_path = $this->cms_module_path();
        
        // job
        $this->cms_add_privilege($this->cms_complete_navigation_name('read_job'),          'read job');
        $this->cms_add_privilege($this->cms_complete_navigation_name('add_job'),           'add job');
        $this->cms_add_privilege($this->cms_complete_navigation_name('edit_job'),          'edit job');
        $this->cms_add_privilege($this->cms_complete_navigation_name('delete_job'),        'delete job');
        $this->cms_add_privilege($this->cms_complete_navigation_name('list_job'),          'list job');
        $this->cms_add_privilege($this->cms_complete_navigation_name('back_to_list_job'),  'back to list job');
        $this->cms_add_privilege($this->cms_complete_navigation_name('print_job'),         'print job');
        $this->cms_add_privilege($this->cms_complete_navigation_name('export_job'),        'export job');

        // hobby
        $this->cms_add_privilege($this->cms_complete_navigation_name('read_hobby'),          'read hobby');
        $this->cms_add_privilege($this->cms_complete_navigation_name('add_hobby'),           'add hobby');
        $this->cms_add_privilege($this->cms_complete_navigation_name('edit_hobby'),          'edit hobby');
        $this->cms_add_privilege($this->cms_complete_navigation_name('delete_hobby'),        'delete hobby');
        $this->cms_add_privilege($this->cms_complete_navigation_name('list_hobby'),          'list hobby');
        $this->cms_add_privilege($this->cms_complete_navigation_name('back_to_list_hobby'),  'back to list hobby');
        $this->cms_add_privilege($this->cms_complete_navigation_name('print_hobby'),         'print hobby');
        $this->cms_add_privilege($this->cms_complete_navigation_name('export_hobby'),        'export hobby');

        // country
        $this->cms_add_privilege($this->cms_complete_navigation_name('read_country'),          'read country');
        $this->cms_add_privilege($this->cms_complete_navigation_name('add_country'),           'add country');
        $this->cms_add_privilege($this->cms_complete_navigation_name('edit_country'),          'edit country');
        $this->cms_add_privilege($this->cms_complete_navigation_name('delete_country'),        'delete country');
        $this->cms_add_privilege($this->cms_complete_navigation_name('list_country'),          'list country');
        $this->cms_add_privilege($this->cms_complete_navigation_name('back_to_list_country'),  'back to list country');
        $this->cms_add_privilege($this->cms_complete_navigation_name('print_country'),         'print country');
        $this->cms_add_privilege($this->cms_complete_navigation_name('export_country'),        'export country');

        // commodity
        $this->cms_add_privilege($this->cms_complete_navigation_name('read_commodity'),          'read commodity');
        $this->cms_add_privilege($this->cms_complete_navigation_name('add_commodity'),           'add commodity');
        $this->cms_add_privilege($this->cms_complete_navigation_name('edit_commodity'),          'edit commodity');
        $this->cms_add_privilege($this->cms_complete_navigation_name('delete_commodity'),        'delete commodity');
        $this->cms_add_privilege($this->cms_complete_navigation_name('list_commodity'),          'list commodity');
        $this->cms_add_privilege($this->cms_complete_navigation_name('back_to_list_commodity'),  'back to list commodity');
        $this->cms_add_privilege($this->cms_complete_navigation_name('print_commodity'),         'print commodity');
        $this->cms_add_privilege($this->cms_complete_navigation_name('export_commodity'),        'export commodity');

        // tourism
        $this->cms_add_privilege($this->cms_complete_navigation_name('read_tourism'),          'read tourism');
        $this->cms_add_privilege($this->cms_complete_navigation_name('add_tourism'),           'add tourism');
        $this->cms_add_privilege($this->cms_complete_navigation_name('edit_tourism'),          'edit tourism');
        $this->cms_add_privilege($this->cms_complete_navigation_name('delete_tourism'),        'delete tourism');
        $this->cms_add_privilege($this->cms_complete_navigation_name('list_tourism'),          'list tourism');
        $this->cms_add_privilege($this->cms_complete_navigation_name('back_to_list_tourism'),  'back to list tourism');
        $this->cms_add_privilege($this->cms_complete_navigation_name('print_tourism'),         'print tourism');
        $this->cms_add_privilege($this->cms_complete_navigation_name('export_tourism'),        'export tourism');

        // city
        $this->cms_add_privilege($this->cms_complete_navigation_name('read_city'),          'read city');
        $this->cms_add_privilege($this->cms_complete_navigation_name('add_city'),           'add city');
        $this->cms_add_privilege($this->cms_complete_navigation_name('edit_city'),          'edit city');
        $this->cms_add_privilege($this->cms_complete_navigation_name('delete_city'),        'delete city');
        $this->cms_add_privilege($this->cms_complete_navigation_name('list_city'),          'list city');
        $this->cms_add_privilege($this->cms_complete_navigation_name('back_to_list_city'),  'back to list city');
        $this->cms_add_privilege($this->cms_complete_navigation_name('print_city'),         'print city');
        $this->cms_add_privilege($this->cms_complete_navigation_name('export_city'),        'export city');
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

        // job
        $fields = array(
            'job_id'               => $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'name'                 => array("type" => 'varchar',    "constraint" => 20,  "null" => TRUE),
            '_created_at'          => $this->TYPE_DATETIME_NULL,
            '_updated_at'          => $this->TYPE_DATETIME_NULL,
            '_created_by'          => $this->TYPE_INT_SIGNED_NULL,
            '_updated_by'          => $this->TYPE_INT_SIGNED_NULL
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('job_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('job'));

        // hobby
        $fields = array(
            'hobby_id'             => $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'name'                 => array("type" => 'varchar',    "constraint" => 20,  "null" => TRUE),
            '_created_at'          => $this->TYPE_DATETIME_NULL,
            '_updated_at'          => $this->TYPE_DATETIME_NULL,
            '_created_by'          => $this->TYPE_INT_SIGNED_NULL,
            '_updated_by'          => $this->TYPE_INT_SIGNED_NULL
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('hobby_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('hobby'));

        // country
        $fields = array(
            'country_id'           => $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'name'                 => array("type" => 'varchar',    "constraint" => 20,  "null" => TRUE),
            '_created_at'          => $this->TYPE_DATETIME_NULL,
            '_updated_at'          => $this->TYPE_DATETIME_NULL,
            '_created_by'          => $this->TYPE_INT_SIGNED_NULL,
            '_updated_by'          => $this->TYPE_INT_SIGNED_NULL
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('country_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('country'));

        // commodity
        $fields = array(
            'commodity_id'         => $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'name'                 => array("type" => 'varchar',    "constraint" => 20,  "null" => TRUE),
            '_created_at'          => $this->TYPE_DATETIME_NULL,
            '_updated_at'          => $this->TYPE_DATETIME_NULL,
            '_created_by'          => $this->TYPE_INT_SIGNED_NULL,
            '_updated_by'          => $this->TYPE_INT_SIGNED_NULL
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('commodity_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('commodity'));

        // tourism
        $fields = array(
            'tourism_id'           => $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'name'                 => array("type" => 'varchar',    "constraint" => 20,  "null" => TRUE),
            '_created_at'          => $this->TYPE_DATETIME_NULL,
            '_updated_at'          => $this->TYPE_DATETIME_NULL,
            '_created_by'          => $this->TYPE_INT_SIGNED_NULL,
            '_updated_by'          => $this->TYPE_INT_SIGNED_NULL
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('tourism_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('tourism'));

        // city
        $fields = array(
            'city_id'              => $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'country_id'           => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
            'name'                 => array("type" => 'varchar',    "constraint" => 20,  "null" => TRUE),
            'tourism'              => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
            'commodity'            => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
            'citizen'              => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
            '_created_at'          => $this->TYPE_DATETIME_NULL,
            '_updated_at'          => $this->TYPE_DATETIME_NULL,
            '_created_by'          => $this->TYPE_INT_SIGNED_NULL,
            '_updated_by'          => $this->TYPE_INT_SIGNED_NULL
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('city_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('city'));

        // citizen
        $fields = array(
            'citizen_id'           => $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'city_id'              => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
            'name'                 => array("type" => 'varchar',    "constraint" => 50,  "null" => TRUE),
            'birthdate'            => array("type" => 'date',       "null" => TRUE),
            'job_id'               => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
            'hobby'                => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
            '_created_at'          => $this->TYPE_DATETIME_NULL,
            '_updated_at'          => $this->TYPE_DATETIME_NULL,
            '_created_by'          => $this->TYPE_INT_SIGNED_NULL,
            '_updated_by'          => $this->TYPE_INT_SIGNED_NULL
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('citizen_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('citizen'));

        // city_commodity
        $fields = array(
            'id'                   => $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'city_id'              => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
            'commodity_id'         => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
            'priority'             => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
            '_created_at'          => $this->TYPE_DATETIME_NULL,
            '_updated_at'          => $this->TYPE_DATETIME_NULL,
            '_created_by'          => $this->TYPE_INT_SIGNED_NULL,
            '_updated_by'          => $this->TYPE_INT_SIGNED_NULL
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('city_commodity'));

        // city_tourism
        $fields = array(
            'id'                   => $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'city_id'              => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
            'tourism_id'           => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
            '_created_at'          => $this->TYPE_DATETIME_NULL,
            '_updated_at'          => $this->TYPE_DATETIME_NULL,
            '_created_by'          => $this->TYPE_INT_SIGNED_NULL,
            '_updated_by'          => $this->TYPE_INT_SIGNED_NULL
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('city_tourism'));

        // citizen_hobby
        $fields = array(
            'id'                   => $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'citizen_id'           => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
            'hobby_id'             => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
            '_created_at'          => $this->TYPE_DATETIME_NULL,
            '_updated_at'          => $this->TYPE_DATETIME_NULL,
            '_created_by'          => $this->TYPE_INT_SIGNED_NULL,
            '_updated_by'          => $this->TYPE_INT_SIGNED_NULL
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('citizen_hobby'));

    }

    //////////////////////////////////////////////////////////////////////////////
    // INSERT DATA
    //////////////////////////////////////////////////////////////////////////////
    private function insert_data(){
        $module_path = $this->cms_module_path();
        
        $this->db->insert_batch($this->cms_complete_table_name('tourism'), array(
            array('tourism_id' => '1', 'name' => 'Amusement Park'),
            array('tourism_id' => '2', 'name' => 'Beach'),
        ));
        $this->db->insert_batch($this->cms_complete_table_name('country'), array(
            array('country_id' => '1', 'name' => 'USA'),
            array('country_id' => '2', 'name' => 'Indonesia'),
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
