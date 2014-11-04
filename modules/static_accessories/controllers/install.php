<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for static_accessories
 *
 * @author No-CMS Module Generator
 */
class Install extends CMS_Module_Installer {
    /////////////////////////////////////////////////////////////////////////////
    // Default Variables
    /////////////////////////////////////////////////////////////////////////////

    protected $DEPENDENCIES = array();
    protected $NAME         = 'gofrendi.noCMS.static_accessories';
    protected $DESCRIPTION  = 'This widget contains several widgets such a slideshow, tabbed content, and visitor counter';
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
        }else{
            $slideshow_height = $this->input->post('slideshow_height');
            if(isset($slideshow_height) && $slideshow_height !== FALSE){
                cms_module_config($module_directory, 'slideshow_height', $slideshow_height);
            }
            // get values
            $data['slideshow_height'] = cms_module_config($module_directory, 'slideshow_height');
        }
        $navigation_name = $this->cms_navigation_name($module_directory.'/install/setting');
        if($navigation_name === NULL || $navigation_name == ''){
            $navigation_name = 'main_module_management';
        }
        $this->view($module_directory.'/install_setting', $data, $navigation_name);
    }

    /////////////////////////////////////////////////////////////////////////////
    // Private Functions
    /////////////////////////////////////////////////////////////////////////////

    // REMOVE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
    private function remove_all(){
        $module_path = $this->cms_module_path();

        // remove widgets
        $this->remove_widget($this->cms_complete_navigation_name('slideshow'));
        $this->remove_widget($this->cms_complete_navigation_name('tab'));
        $this->remove_widget($this->cms_complete_navigation_name('visitor_count'));

        // remove navigations
        $this->remove_navigation($this->cms_complete_navigation_name('manage_visitor_counter'));
        $this->remove_navigation($this->cms_complete_navigation_name('manage_tab_content'));
        $this->remove_navigation($this->cms_complete_navigation_name('manage_slide'));
        $this->remove_navigation($this->cms_complete_navigation_name('setting'));


        // remove parent of all navigations
        $this->remove_navigation($this->cms_complete_navigation_name('index'));

        // drop tables
        $this->dbforge->drop_table($this->cms_complete_table_name('visitor_counter'), TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('tab_content'), TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('slide'), TRUE);
    }

    // CREATE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
    private function build_all(){
        $module_path = $this->cms_module_path();

        // parent of all navigations
        $this->add_navigation($this->cms_complete_navigation_name('index'), 'Accessories Widgets',
            $module_path.'/static_accessories', $this->PRIV_AUTHORIZED, 'main_management');

        // add navigations
        $this->add_navigation($this->cms_complete_navigation_name('manage_slide'), 'Slideshow',
            $module_path.'/manage_slide', $this->PRIV_AUTHORIZED, $this->cms_complete_navigation_name('index')
        );
        $this->add_navigation($this->cms_complete_navigation_name('manage_tab_content'), 'Tabbed Content',
            $module_path.'/manage_tab_content', $this->PRIV_AUTHORIZED, $this->cms_complete_navigation_name('index')
        );
        $this->add_navigation($this->cms_complete_navigation_name('manage_visitor_counter'), 'Visitor',
            $module_path.'/manage_visitor_counter', $this->PRIV_AUTHORIZED, $this->cms_complete_navigation_name('index')
        );
        $this->add_navigation($this->cms_complete_navigation_name('setting'), 'Setting',
            $module_path.'/install/setting', $this->PRIV_AUTHORIZED, $this->cms_complete_navigation_name('index')
        );

        $this->add_widget($this->cms_complete_navigation_name('slideshow'), 'Slide Show',
            $this->PRIV_EVERYONE, $module_path.'/static_accessories_widget/slide');
        $this->add_widget($this->cms_complete_navigation_name('tab'), 'Tabbed Content',
            $this->PRIV_EVERYONE, $module_path.'/static_accessories_widget/tab');
        $this->add_widget($this->cms_complete_navigation_name('visitor_count'), 'Visitor Count',
            $this->PRIV_EVERYONE, $module_path.'/static_accessories_widget/visitor_counter');


        // create tables
        // slide
        $fields = array(
            'slide_id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'image_url'=> array("type"=>'varchar', "constraint"=>100, "null"=>TRUE),
            'content'=> array("type"=>'text', "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('slide_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('slide'));

        // tab_content
        $fields = array(
            'tab_id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'caption'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'content'=> array("type"=>'text', "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('tab_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('tab_content'));

        // visitor_counter
        $fields = array(
            'counter_id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'ip'=> array("type"=>'varchar', "constraint"=>20, "null"=>TRUE),
            'time'=> $this->TYPE_DATETIME_NULL,
            'agent'=> array("type"=>'varchar', "constraint"=>300, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('counter_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('visitor_counter'));

        // make copy of 01.jpg and insert it as new slide
        $image_path = FCPATH . 'modules/' . $this->cms_module_path().'/assets/images/slides/';
        $original_file_name = '01.jpg';
        if(CMS_SUBSITE != ''){
            $original_file_name = str_pad(rand(3, 11), 2, "0", STR_PAD_LEFT).'.jpg';
        }
        $file_name = (CMS_SUBSITE==''?'main_':CMS_SUBSITE) . '01.jpg';
        copy($image_path.$original_file_name, $image_path.$file_name);
        $data = array('image_url'=>$file_name,'content'=>'<h1>The first slide image</h1><p>Some awesome descriptions</p>');
        $this->db->insert($this->cms_complete_table_name('slide'),$data);

        // make copy of 02.jpg and insert it as new slide
        $image_path = FCPATH . 'modules/' . $this->cms_module_path().'/assets/images/slides/';
        $original_file_name = '02.jpg';
        if(CMS_SUBSITE != ''){
            $original_file_name = str_pad(rand(3, 11), 2, "0", STR_PAD_LEFT).'.jpg';
        }
        $file_name = (CMS_SUBSITE==''?'main_':CMS_SUBSITE) . '02.jpg';
        copy($image_path.$original_file_name, $image_path.$file_name);
        $data = array('image_url'=>$file_name,'content'=>'<h1>The second slide image</h1><p>Another awesome description</p>');
        $this->db->insert($this->cms_complete_table_name('slide'),$data);
    }
}
