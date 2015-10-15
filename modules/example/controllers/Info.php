<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for example
 *
 * @author No-CMS Module Generator
 */
class Info extends CMS_Module {

    //////////////////////////////////////////////////////////////////////////////
    // NAVIGATIONS
    //////////////////////////////////////////////////////////////////////////////
    protected $NAVIGATIONS = array(
            // Example
            array(
                'navigation_name'   => 'index',
                'url'               => 'example',
                'authorization_id'  => PRIV_EVERYONE,
                'default_layout'    => NULL,
                'title'             => 'Example',
                'parent_name'       => 'index',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),
            // Browse City
            array(
                'navigation_name'   => 'browse_city',
                'url'               => 'browse_city',
                'authorization_id'  => PRIV_EVERYONE,
                'default_layout'    => NULL,
                'title'             => 'Browse City',
                'parent_name'       => 'index',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),

        );

    protected $BACKEND_NAVIGATIONS = array(
            // Manage Job
            array(
                'entity_name'       => 'job',
                'url'               => 'manage_job',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage Job',
                'parent_name'       => 'index',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),
            // Manage Hobby
            array(
                'entity_name'       => 'hobby',
                'url'               => 'manage_hobby',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage Hobby',
                'parent_name'       => 'index',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),
            // Manage Country
            array(
                'entity_name'       => 'country',
                'url'               => 'manage_country',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage Country',
                'parent_name'       => 'index',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),
            // Manage Commodity
            array(
                'entity_name'       => 'commodity',
                'url'               => 'manage_commodity',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage Commodity',
                'parent_name'       => 'index',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),
            // Manage Tourism
            array(
                'entity_name'       => 'tourism',
                'url'               => 'manage_tourism',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage Tourism',
                'parent_name'       => 'index',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),
            // Manage City
            array(
                'entity_name'       => 'city',
                'url'               => 'manage_city',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage City',
                'parent_name'       => 'index',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),

        );

    //////////////////////////////////////////////////////////////////////////////
    // CONFIGURATIONS
    //////////////////////////////////////////////////////////////////////////////
    protected $CONFIGS = array();

    //////////////////////////////////////////////////////////////////////////////
    // PRIVILEGES
    //////////////////////////////////////////////////////////////////////////////
    protected $PRIVILEGES = array();

    //////////////////////////////////////////////////////////////////////////////
    // GROUPS
    //////////////////////////////////////////////////////////////////////////////
    protected $GROUPS = array(
            array('group_name' => 'Example Manager', 'description' => 'Example Manager'),
        );
    protected $GROUP_NAVIGATIONS = array();
    protected $GROUP_BACKEND_NAVIGATIONS = array(
            'Example Manager' => array('job', 'hobby', 'country', 'commodity', 'tourism', 'city')
        );
    protected $GROUP_PRIVILEGES = array();
    protected $GROUP_BACKEND_PRIVILEGES = array(
            'Example Manager' => array(
                'job' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
                'hobby' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
                'country' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
                'commodity' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
                'tourism' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
                'city' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
            )
        );

    //////////////////////////////////////////////////////////////////////////////
    // TABLES and DATA
    //////////////////////////////////////////////////////////////////////////////
    protected $TABLES = array(
        // job
        'job' => array(
            'key'    => 'job_id',
            'fields' => array(
                'job_id'               => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'name'                 => array("type" => 'varchar',    "constraint" => 20,  "null" => TRUE),
            ),
        ),
        // hobby
        'hobby' => array(
            'key'    => 'hobby_id',
            'fields' => array(
                'hobby_id'             => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'name'                 => array("type" => 'varchar',    "constraint" => 20,  "null" => TRUE),
            ),
        ),
        // country
        'country' => array(
            'key'    => 'country_id',
            'fields' => array(
                'country_id'           => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'name'                 => array("type" => 'varchar',    "constraint" => 20,  "null" => TRUE),
            ),
        ),
        // commodity
        'commodity' => array(
            'key'    => 'commodity_id',
            'fields' => array(
                'commodity_id'         => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'name'                 => array("type" => 'varchar',    "constraint" => 20,  "null" => TRUE),
            ),
        ),
        // tourism
        'tourism' => array(
            'key'    => 'tourism_id',
            'fields' => array(
                'tourism_id'           => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'name'                 => array("type" => 'varchar',    "constraint" => 20,  "null" => TRUE),
            ),
        ),
        // city
        'city' => array(
            'key'    => 'city_id',
            'fields' => array(
                'city_id'              => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'country_id'           => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'name'                 => array("type" => 'varchar',    "constraint" => 20,  "null" => TRUE),
                'tourism'              => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
                'commodity'            => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
                'citizen'              => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
            ),
        ),
        // citizen
        'citizen' => array(
            'key'    => 'citizen_id',
            'fields' => array(
                'citizen_id'           => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'city_id'              => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'name'                 => array("type" => 'varchar',    "constraint" => 50,  "null" => TRUE),
                'birthdate'            => array("type" => 'date',       "null" => TRUE),
                'job_id'               => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'hobby'                => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
            ),
        ),
        // city_commodity
        'city_commodity' => array(
            'key'    => 'id',
            'fields' => array(
                'id'                   => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'city_id'              => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'commodity_id'         => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'priority'             => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
            ),
        ),
        // city_tourism
        'city_tourism' => array(
            'key'    => 'id',
            'fields' => array(
                'id'                   => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'city_id'              => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'tourism_id'           => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
            ),
        ),
        // citizen_hobby
        'citizen_hobby' => array(
            'key'    => 'id',
            'fields' => array(
                'id'                   => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'citizen_id'           => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'hobby_id'             => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
            ),
        ),
    );
    protected $DATA = array(
        'tourism' => array(
            array('tourism_id' => '1', 'name' => 'Amusement Park'),
            array('tourism_id' => '2', 'name' => 'Beach'),
        ),
        'commodity' => array(
            array('commodity_id' => '1', 'name' => 'vegetables'),
            array('commodity_id' => '2', 'name' => 'fruits'),
            array('commodity_id' => '3', 'name' => 'diary'),
        ),
        'country' => array(
            array('country_id' => '1', 'name' => 'USA'),
            array('country_id' => '2', 'name' => 'Indonesia'),
        ),
        'hobby' => array(
            array('hobby_id' => '1', 'name' => 'Reading'),
            array('hobby_id' => '2', 'name' => 'Gardenning'),
        ),
        'job' => array(
            array('job_id' => '1', 'name' => 'Teacher'),
            array('job_id' => '2', 'name' => 'Programmer'),
        ),
    );

    //////////////////////////////////////////////////////////////////////////////
    // ACTIVATION
    //////////////////////////////////////////////////////////////////////////////
    public function do_activate(){
        // TODO : write your module activation script here
    }

    //////////////////////////////////////////////////////////////////////////////
    // DEACTIVATION
    //////////////////////////////////////////////////////////////////////////////
    public function do_deactivate(){
        // TODO : write your module deactivation script here
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

        // TODO: Add your migration logic here.

        // e.g:
        // if($major <= 0 && $minor <= 0 && $build <=0){
        //      // add some missing fields, navigations or privileges
        // }
    }

}
