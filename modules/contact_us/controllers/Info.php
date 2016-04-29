<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for new_contact_us
 *
 * @author No-CMS Module Generator
 */
class Info extends CMS_Module {

    //////////////////////////////////////////////////////////////////////////////
    // NAVIGATIONS
    //////////////////////////////////////////////////////////////////////////////
    protected $NAVIGATIONS = array(
            // New Contact Us
            array(
                'navigation_name'   => 'index',
                'url'               => 'contact_us',
                'authorization_id'  => PRIV_EVERYONE,
                'default_layout'    => NULL,
                'title'             => 'Contact Us',
                'parent_name'       => NULL,
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => '{{ MODULE_PATH }}/notif/new_message',
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),

        );

    protected $BACKEND_NAVIGATIONS = array(
            // Manage Message
            array(
                'entity_name'       => 'message',
                'url'               => 'manage_message',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage Message',
                'parent_name'       => 'index',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => '{{ MODULE_PATH }}/notif/new_message',
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
            array('group_name' => 'Contact Us Manager', 'description' => 'New Contact Us Manager'),
        );
    protected $GROUP_NAVIGATIONS = array();
    protected $GROUP_BACKEND_NAVIGATIONS = array(
            'Contact Us Manager' => array('message')
        );
    protected $GROUP_PRIVILEGES = array();
    protected $GROUP_BACKEND_PRIVILEGES = array(
            'Contact Us Manager' => array(
                'message' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
            )
        );

    //////////////////////////////////////////////////////////////////////////////
    // TABLES and DATA
    //////////////////////////////////////////////////////////////////////////////
    protected $TABLES = array(
        // message
        'message' => array(
            'key'    => 'id',
            'fields' => array(
                'id'        => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'name'      => array("type" => 'varchar',    "constraint" => 50,  "null" => TRUE),
                'content'   => array("type" => 'text',       "null" => TRUE),
                'email'     => array("type" => 'varchar',    "constraint" => 50,  "null" => TRUE),
                'read'      => array("type" => 'int',        "constraint" => 10,  "null" => TRUE, "default" => 0),
            ),
        ),
    );
    protected $DATA = array(

    );

    //////////////////////////////////////////////////////////////////////////////
    // ACTIVATION
    //////////////////////////////////////////////////////////////////////////////
    public function do_activate(){
        $this->cms_add_quicklink($this->n('index'));
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
    // TODO: write your upgrade function: do_upgrade_to_x_x_x

}
