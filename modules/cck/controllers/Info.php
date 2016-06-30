<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for cck
 *
 * @author No-CMS Module Generator
 */
class Info extends CMS_Module {

    //////////////////////////////////////////////////////////////////////////////
    // NAVIGATIONS
    //////////////////////////////////////////////////////////////////////////////
    protected $NAVIGATIONS = array(
            // CCK
            array(
                'navigation_name'   => 'index',
                'url'               => 'cck',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => NULL,
                'title'             => 'CCK (Content Construction Kit)',
                'parent_name'       => 'main_management',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),

        );

    protected $BACKEND_NAVIGATIONS = array(
            // Manage Template
            array(
                'entity_name'       => 'template',
                'url'               => 'manage_template',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage Template',
                'parent_name'       => 'index',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),
            // Manage Entity
            array(
                'entity_name'       => 'entity',
                'url'               => 'manage_entity',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage Entity',
                'parent_name'       => 'index',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),
            // Manage Field
            array(
                'entity_name'       => 'field',
                'url'               => 'manage_field',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage Field',
                'parent_name'       => 'manage_entity',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => 1,
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
            array('group_name' => 'Cck Manager', 'description' => 'Cck Manager'),
        );
    protected $GROUP_NAVIGATIONS = array();
    protected $GROUP_BACKEND_NAVIGATIONS = array(
            'Cck Manager' => array('template', 'entity', 'field', 'option')
        );
    protected $GROUP_PRIVILEGES = array();
    protected $GROUP_BACKEND_PRIVILEGES = array(
            'Cck Manager' => array(
                'template' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
                'entity' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
                'field' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
            )
        );

    //////////////////////////////////////////////////////////////////////////////
    // TABLES and DATA
    //////////////////////////////////////////////////////////////////////////////
    protected $TABLES = array(
        // template
        'template' => array(
            'key'    => 'id',
            'fields' => array(
                'id'                   => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'name'                 => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
                'input'                => array("type" => 'text',       "null" => TRUE),
                'view'                 => array("type" => 'text',       "null" => TRUE),
                'field'                => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
            ),
        ),
        // entity
        'entity' => array(
            'key'    => 'id',
            'fields' => array(
                'id'                   => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'name'                 => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
                'max_record_per_user'  => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'per_user_limitation'  => array("type" => 'int',        "constraint" => 10,  "null" => TRUE, 'default' => 1),
                'id_authorization_view' => array("type" => 'int',        "constraint" => 10,  "null" => TRUE, 'default' => 4),
                'id_authorization_add' => array("type" => 'int',        "constraint" => 10,  "null" => TRUE, 'default' => 4),
                'id_authorization_edit' => array("type" => 'int',        "constraint" => 10,  "null" => TRUE, 'default' => 4),
                'id_authorization_delete' => array("type" => 'int',        "constraint" => 10,  "null" => TRUE, 'default' => 4),
                'id_authorization_browse' => array("type" => 'int',        "constraint" => 10,  "null" => TRUE, 'default' => 1),
                'field'                => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
                'group_entity_add'     => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
                'group_entity_edit'    => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
                'group_entity_view'    => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
                'group_entity_browse'  => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
                'group_entity_delete'  => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
                'per_record_html'      => array("type" => 'text',       "null" => TRUE),
            ),
        ),
        // field
        'field' => array(
            'key'    => 'id',
            'fields' => array(
                'id'                   => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'name'                 => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
                'id_template'          => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'id_entity'            => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'input'                => array("type" => 'text',       "null" => TRUE),
                'view'                 => array("type" => 'text',       "null" => TRUE),
                'shown_on_add'         => array("type" => 'int',        "constraint" => 10,  "null" => TRUE, 'default' => 1),
                'shown_on_edit'        => array("type" => 'int',        "constraint" => 10,  "null" => TRUE, 'default' => 1),
                'shown_on_view'        => array("type" => 'int',        "constraint" => 10,  "null" => TRUE, 'default' => 1),
                'option'               => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
            ),
        ),
        // group_entity_browse
        'group_entity_browse' => array(
            'key'    => 'id',
            'fields' => array(
                'id_group'             => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'id_entity'            => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'id'                   => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
            ),
        ),
        // group_entity_delete
        'group_entity_delete' => array(
            'key'    => 'id',
            'fields' => array(
                'id_group'             => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'id_entity'            => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'id'                   => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
            ),
        ),
        // group_entity_edit
        'group_entity_edit' => array(
            'key'    => 'id',
            'fields' => array(
                'id_group'             => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'id_entity'            => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'id'                   => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
            ),
        ),
        // group_entity_add
        'group_entity_add' => array(
            'key'    => 'id',
            'fields' => array(
                'id_group'             => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'id_entity'            => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'id'                   => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
            ),
        ),
        // group_entity_view
        'group_entity_view' => array(
            'key'    => 'id',
            'fields' => array(
                'id_group'       => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'id_entity'            => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'id'                   => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
            ),
        ),
        // option
        'option' => array(
            'key'    => 'id',
            'fields' => array(
                'id'                   => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'name'                 => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
                'shown'                => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
                'id_field'             => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
            ),
        ),
    );
    protected $DATA = array(
        'template' => array(
            array('id' => 1, 'name' => 'text', 'input' => '<input id="field-{{ code }}" name="{{ code }}" value="{{ value }}" />', 'view' => '{{ value }}'),
            array('id' => 2, 'name' => 'textarea', 'input' => '<textarea id="field-{{ code }}" name="{{ code }}">{{ value }}</textarea>', 'view' => '{{ value }}'),
            array('id' => 3, 'name' => 'dropdown', 'input' => '<select id="field-{{ code }}" name="{{ code }}">\n{{ foreach_option }}\n    <option {{ if_selected:selected }} value={{ option.value }}>{{ option.caption }}</option>\n{{ end_foreach }}\n</select>', 'view' => '{{ selected.caption }}'),
            array('id' => 4, 'name' => 'multiselect', 'input' => '<select id="field-{{ code }}" name="{{ code }}[]" multiple>\n{{ foreach_option }}\n    <option {{ if_selected:selected }} value={{ option.value }}>{{ option.caption }}</option>\n{{ end_foreach }}\n</select>', 'view' => '{{ selected.caption }}'),
            array('id' => 5, 'name' => 'file', 'input' => '<a real-value="{{ value }}" class="remove-if-empty" href="{{ module_base_url }}assets/uploads/{{ value }}" target="blank">{{ value }}</a><br />\n<input id="field-{{ code }}" name="{{ code }}" type="file"" />', 'view' => '<a real-value="{{ value }}" class="remove-if-empty" href="{{ module_base_url }}assets/uploads/{{ value }}" target="blank">{{ value }}</a>'),
            array('id' => 6, 'name' => 'image', 'input' => '<a real-value="{{ value }}" class="remove-if-empty" href="{{ module_base_url }}assets/uploads/{{ value }}" target="blank"><img src="{{ module_base_url }}assets/uploads/{{ value }}" style="max-height:100px; max-width:100%;" /></a><br />\n<input id="field-{{ code }}" name="{{ code }}" type="file"" />', 'view' => '<a real-value="{{ value }}" class="remove-if-empty" href="{{ module_base_url }}assets/uploads/{{ value }}" target="blank"><img src="{{ module_base_url }}assets/uploads/{{ value }}" style="max-height:100px; max-width:100%;" /></a>'),
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
