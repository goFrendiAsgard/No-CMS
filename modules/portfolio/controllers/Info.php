<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for portfolio
 *
 * @author No-CMS Module Generator
 */
class Info extends CMS_Module {

    //////////////////////////////////////////////////////////////////////////////
    // NAVIGATIONS
    //////////////////////////////////////////////////////////////////////////////
    protected $NAVIGATIONS = array(
            // Portfolio
            array(
                'navigation_name'   => 'index',
                'url'               => 'portfolio',
                'authorization_id'  => PRIV_EVERYONE,
                'default_layout'    => NULL,
                'title'             => 'Categories and Portfolios',
                'parent_name'       => NULL,
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => 'glyphicon-th-large',
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),
            // Browse Portfolio
            array(
                'navigation_name'   => 'browse_portfolio',
                'url'               => 'browse_portfolio',
                'authorization_id'  => PRIV_EVERYONE,
                'default_layout'    => NULL,
                'title'             => 'Portfolio',
                'parent_name'       => NULL,
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => 'glyphicon-folder-open',
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),

        );

    protected $BACKEND_NAVIGATIONS = array(
            // Manage Category
            array(
                'entity_name'       => 'category',
                'url'               => 'manage_category',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage Category',
                'parent_name'       => 'index',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),
            // Manage Portfolio
            array(
                'entity_name'       => 'portfolio',
                'url'               => 'manage_portfolio',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage Portfolio',
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
    protected $CONFIGS = array(

            // Browse Portfolio
            array(
                'config_name'   => 'portfolio_record_template',
                'value'         => NULL, // if set to NULL, the value will be taken from config/module_config.php
            ),

    );

    //////////////////////////////////////////////////////////////////////////////
    // PRIVILEGES
    //////////////////////////////////////////////////////////////////////////////
    protected $PRIVILEGES = array(

            // Record Template Portfolio
            array(
                'privilege_name'   => 'edit_portfolio_record_template',
                'authorization_id' => PRIV_AUTHORIZED,
            ),
    );

    //////////////////////////////////////////////////////////////////////////////
    // GROUPS
    //////////////////////////////////////////////////////////////////////////////
    protected $GROUPS = array(
            array('group_name' => 'Portfolio Manager', 'description' => 'Portfolio Manager'),
        );
    protected $GROUP_NAVIGATIONS = array();
    protected $GROUP_BACKEND_NAVIGATIONS = array(
            'Portfolio Manager' => array('category', 'portfolio')
        );
    protected $GROUP_PRIVILEGES = array();
    protected $GROUP_BACKEND_PRIVILEGES = array(
            'Portfolio Manager' => array(
                'category' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
                'portfolio' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
            )
        );

    //////////////////////////////////////////////////////////////////////////////
    // TABLES and DATA
    //////////////////////////////////////////////////////////////////////////////
    protected $TABLES = array(
        // category
        'category' => array(
            'key'    => 'id',
            'fields' => array(
                'id'                   => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'name'                 => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
            ),
        ),
        // portfolio
        'portfolio' => array(
            'key'    => 'id',
            'fields' => array(
                'id'                   => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'name'                 => array("type" => 'varchar',    "constraint" => 100, "null" => TRUE),
                'id_category'          => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'url'                  => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
                'image'                => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
                'description'          => array("type" => 'text',       "null" => TRUE),
            ),
        ),
    );
    protected $DATA = array(

    );

    //////////////////////////////////////////////////////////////////////////////
    // ACTIVATION
    //////////////////////////////////////////////////////////////////////////////
    public function do_activate(){
        // TODO : write your module activation script here
        $this->cms_add_quicklink($this->n('browse_portfolio'));
    }

    //////////////////////////////////////////////////////////////////////////////
    // DEACTIVATION
    //////////////////////////////////////////////////////////////////////////////
    public function do_deactivate(){
        // TODO : write your module deactivation script here
        $this->cms_remove_quicklink($this->n('browse_portfolio'));
    }

    //////////////////////////////////////////////////////////////////////////////
    // UPGRADE
    //////////////////////////////////////////////////////////////////////////////
    // TODO: write your upgrade function: do_upgrade_to_x_x_x

}
