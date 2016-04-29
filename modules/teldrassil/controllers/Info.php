<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for teldrassil
 *
 * @author No-CMS Module Generator
 */
class Info extends CMS_Module {

    protected $NAVIGATIONS = array(
        // Static Accessories New
        array(
            'navigation_name'   => 'index',
            'url'               => 'teldrassil',
            'authorization_id'  => PRIV_AUTHORIZED,
            'default_layout'    => 'default-one-column',
            'title'             => 'Theme Generator',
            'parent_name'       => 'main_management',
            'index'             => NULL,
            'description'       => NULL,
            'bootstrap_glyph'   => NULL,
            'notification_url'  => NULL,
            'hidden'            => NULL,
            'static_content'    => NULL,
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
    // TODO: write your upgrade function: do_upgrade_to_x_x_x

}
