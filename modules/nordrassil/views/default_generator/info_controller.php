&lt;?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for {{ project_name }}
 *
 * @author No-CMS Module Generator
 */
class Info extends CMS_Module {

    //////////////////////////////////////////////////////////////////////////////
    // NAVIGATIONS
    //////////////////////////////////////////////////////////////////////////////
    protected $NAVIGATIONS = array(
            // {{ project_caption }}
            array(
                'navigation_name'   => '{{ navigation_parent_name }}',
                'url'               => '{{ main_controller }}',
                'authorization_id'  => PRIV_EVERYONE,
                'default_layout'    => NULL,
                'title'             => '{{ project_caption }}',
                'parent_name'       => NULL,
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),{{ frontend_navigations }}
        );

    protected $BACKEND_NAVIGATIONS = array({{ backend_navigations }}
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
            array('group_name' => '{{ default_group_name }}', 'description' => '{{ default_group_name }}'),
        );
    protected $GROUP_NAVIGATIONS = array();
    protected $GROUP_BACKEND_NAVIGATIONS = array(
            '{{ default_group_name }}' => {{ group_backend_navigations }}
        );
    protected $GROUP_PRIVILEGES = array();
    protected $GROUP_BACKEND_PRIVILEGES = array(
            '{{ default_group_name }}' => {{ group_backend_privileges }}
        );

    //////////////////////////////////////////////////////////////////////////////
    // TABLES and DATA
    //////////////////////////////////////////////////////////////////////////////
    protected $TABLES = array(
        {{ tables }}
    );
    protected $DATA = array(
        {{ data }}
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
