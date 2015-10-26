<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for static_accessories
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
        $this->remove_all();
    }

    // UPGRADE
    public function do_upgrade($old_version){
        // Add your migration logic here.
        $this->cms_remove_navigation('static_accessories_setting');
        $this->cms_set_config('static_accessories_slide_height', '400');
    }

    // REMOVE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
    private function remove_all(){
        $module_path = $this->cms_module_path();

        // remove widgets
        $this->cms_remove_widget($this->n('slideshow'));
        $this->cms_remove_widget($this->n('tab'));
        $this->cms_remove_widget($this->n('visitor_count'));

        // remove navigations
        $this->cms_remove_navigation($this->n('manage_visitor_counter'));
        $this->cms_remove_navigation($this->n('manage_tab_content'));
        $this->cms_remove_navigation($this->n('manage_slide'));

        // remove parent of all navigations
        $this->cms_remove_navigation($this->n('index'));

        // drop tables
        $this->dbforge->drop_table($this->t('visitor_counter'), TRUE);
        $this->dbforge->drop_table($this->t('tab_content'), TRUE);
        $this->dbforge->drop_table($this->t('slide'), TRUE);
    }

    // CREATE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
    private function build_all(){
        $module_path = $this->cms_module_path();

        $this->cms_set_config('static_accessories_slide_height', '400');

        // parent of all navigations
        $this->cms_add_navigation($this->n('index'), 'Accessories Widgets',
            $module_path.'/static_accessories', PRIV_AUTHORIZED, 'main_management');

        // add navigations
        $this->cms_add_navigation($this->n('manage_slide'), 'Slideshow',
            $module_path.'/manage_slide', PRIV_AUTHORIZED, $this->n('index')
        );
        $this->cms_add_navigation($this->n('manage_tab_content'), 'Tabbed Content',
            $module_path.'/manage_tab_content', PRIV_AUTHORIZED, $this->n('index')
        );
        $this->cms_add_navigation($this->n('manage_visitor_counter'), 'Visitor',
            $module_path.'/manage_visitor_counter', PRIV_AUTHORIZED, $this->n('index')
        );

        $this->cms_add_widget($this->n('slideshow'), 'Slide Show',
            PRIV_EVERYONE, $module_path.'/static_accessories_widget/slide');
        $this->cms_add_widget($this->n('tab'), 'Tabbed Content',
            PRIV_EVERYONE, $module_path.'/static_accessories_widget/tab');
        $this->cms_add_widget($this->n('visitor_count'), 'Visitor Count',
            PRIV_EVERYONE, $module_path.'/static_accessories_widget/visitor_counter');


        // create tables
        // slide
        $fields = array(
            'slide_id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'image_url'=> array("type"=>'varchar', "constraint"=>100, "null"=>TRUE),
            'content'=> array("type"=>'text', "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('slide_id', TRUE);
        $this->dbforge->create_table($this->t('slide'));

        // tab_content
        $fields = array(
            'tab_id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'caption'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'content'=> array("type"=>'text', "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('tab_id', TRUE);
        $this->dbforge->create_table($this->t('tab_content'));

        // visitor_counter
        $fields = array(
            'counter_id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'ip'=> array("type"=>'varchar', "constraint"=>20, "null"=>TRUE),
            'time'=> $this->TYPE_DATETIME_NULL,
            'agent'=> array("type"=>'varchar', "constraint"=>300, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('counter_id', TRUE);
        $this->dbforge->create_table($this->t('visitor_counter'));

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

}
