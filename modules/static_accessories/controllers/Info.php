<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for static_accessories_new
 *
 * @author No-CMS Module Generator
 */
class Info extends CMS_Module {

    //////////////////////////////////////////////////////////////////////////////
    // NAVIGATIONS
    //////////////////////////////////////////////////////////////////////////////
    protected $NAVIGATIONS = array(
            // Static Accessories New
            array(
                'navigation_name'   => 'index',
                'url'               => 'static_accessories',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => NULL,
                'title'             => 'Static Accessories',
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
            // Manage Slide
            array(
                'entity_name'       => 'slide',
                'url'               => 'manage_slide',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage Slide',
                'parent_name'       => 'index',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),
            // Manage Tab Content
            array(
                'entity_name'       => 'tab_content',
                'url'               => 'manage_tab_content',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage Tab Content',
                'parent_name'       => 'index',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),
            // Manage Visitor Counter
            array(
                'entity_name'       => 'visitor_counter',
                'url'               => 'manage_visitor_counter',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage Visitor Counter',
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
        array('config_name'=>'static_accessories_slide_height', 'value'=>400),
        array('config_name'=>'static_accessories_slide_parallax', 'value'=>'TRUE'),
        array('config_name'=>'static_accessories_slide_hide_on_smallscreen', 'value'=>'TRUE'),
        array('config_name'=>'static_accessories_slide_image_size', 'value'=>'cover'),
        array('config_name'=>'static_accessories_slide_image_top', 'value'=>''),
    );

    //////////////////////////////////////////////////////////////////////////////
    // PRIVILEGES
    //////////////////////////////////////////////////////////////////////////////
    protected $PRIVILEGES = array();

    //////////////////////////////////////////////////////////////////////////////
    // GROUPS
    //////////////////////////////////////////////////////////////////////////////
    protected $GROUPS = array(
            array('group_name' => 'Static Accessories New Manager', 'description' => 'Static Accessories New Manager'),
        );
    protected $GROUP_NAVIGATIONS = array();
    protected $GROUP_BACKEND_NAVIGATIONS = array(
            'Static Accessories New Manager' => array('slide', 'tab_content', 'visitor_counter')
        );
    protected $GROUP_PRIVILEGES = array();
    protected $GROUP_BACKEND_PRIVILEGES = array(
            'Static Accessories New Manager' => array(
                'slide' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
                'tab_content' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
                'visitor_counter' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
            )
        );

    //////////////////////////////////////////////////////////////////////////////
    // TABLES and DATA
    //////////////////////////////////////////////////////////////////////////////
    protected $TABLES = array(
        // slide
        'slide' => array(
            'key'    => 'slide_id',
            'fields' => array(
                'slide_id'             => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'image_url'            => array("type" => 'varchar',    "constraint" => 100, "null" => TRUE),
                'content'              => array("type" => 'text',       "null" => TRUE),
                'slug'                 => array("type" => 'text',       "null" => TRUE),
            ),
        ),
        // tab_content
        'tab_content' => array(
            'key'    => 'tab_id',
            'fields' => array(
                'tab_id'               => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'caption'              => array("type" => 'varchar',    "constraint" => 50,  "null" => TRUE),
                'content'              => array("type" => 'text',       "null" => TRUE),
                'slug'                 => array("type" => 'text',       "null" => TRUE),
            ),
        ),
        // visitor_counter
        'visitor_counter' => array(
            'key'    => 'counter_id',
            'fields' => array(
                'counter_id'           => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'ip'                   => array("type" => 'varchar',    "constraint" => 20,  "null" => TRUE),
                'time'                 => array("type" => 'timestamp',  "null" => TRUE),
                'agent'                => array("type" => 'varchar',    "constraint" => 300, "null" => TRUE),
            ),
        ),
    );
    protected $DATA = array();

    //////////////////////////////////////////////////////////////////////////////
    // ACTIVATION
    //////////////////////////////////////////////////////////////////////////////
    public function do_activate(){
        $module_path = $this->cms_module_path();
        // TODO : write your module activation script here
        $this->cms_add_widget($this->n('slideshow'), 'Slide Show',
            PRIV_EVERYONE, $module_path.'/static_accessories_widget/slide');
        $this->cms_add_widget($this->n('tab'), 'Tabbed Content',
            PRIV_EVERYONE, $module_path.'/static_accessories_widget/tab');
        $this->cms_add_widget($this->n('visitor_count'), 'Visitor Count',
            PRIV_EVERYONE, $module_path.'/static_accessories_widget/visitor_counter');

        // make copy of 01.jpg and insert it as new slide
        $image_path = FCPATH . 'modules/' . $this->cms_module_path().'/assets/images/slides/';
        $original_file_name = '01.jpg';
        if(CMS_SUBSITE != '' || defined('CMS_OVERRIDDEN_SUBSITE')){
            $original_file_name = str_pad(rand(3, 15), 2, "0", STR_PAD_LEFT).'.jpg';
        }
        if(defined('CMS_OVERRIDDEN_SUBSITE')){
            $file_name = CMS_OVERRIDDEN_SUBSITE.'_';
        }else if(CMS_SUBSITE != ''){
            $file_name = CMS_SUBSITE.'_';
        }else{
            $file_name = 'main_';
        }
        $file_name .= '01.jpg';
        copy($image_path.$original_file_name, $image_path.$file_name);
        $data = array('image_url'=>$file_name,'content'=>'<h1>The first slide image</h1><p>Some awesome descriptions</p>');
        $this->db->insert($this->t('slide'),$data);

        // make copy of 02.jpg and insert it as new slide
        $image_path = FCPATH . 'modules/' . $this->cms_module_path().'/assets/images/slides/';
        $original_file_name = '02.jpg';
        if(CMS_SUBSITE != '' || defined('CMS_OVERRIDDEN_SUBSITE')){
            $original_file_name = str_pad(rand(3, 15), 2, "0", STR_PAD_LEFT).'.jpg';
        }
        if(defined('CMS_OVERRIDDEN_SUBSITE')){
            $file_name = CMS_OVERRIDDEN_SUBSITE.'_';
        }else if(CMS_SUBSITE != ''){
            $file_name = CMS_SUBSITE.'_';
        }else{
            $file_name = 'main_';
        }
        $file_name .= '02.jpg';
        copy($image_path.$original_file_name, $image_path.$file_name);
        $data = array('image_url'=>$file_name,'content'=>'<h1>The second slide image</h1><p>Another awesome description</p>');
        $this->db->insert($this->t('slide'),$data);
    }

    //////////////////////////////////////////////////////////////////////////////
    // DEACTIVATION
    //////////////////////////////////////////////////////////////////////////////
    public function do_deactivate(){
        // TODO : write your module deactivation script here
        $this->cms_remove_widget($this->n('slideshow'));
        $this->cms_remove_widget($this->n('tab'));
        $this->cms_remove_widget($this->n('visitor_count'));
    }

    //////////////////////////////////////////////////////////////////////////////
    // UPGRADE
    //////////////////////////////////////////////////////////////////////////////
    // TODO: write your upgrade function: do_upgrade_to_x_x_x

}
