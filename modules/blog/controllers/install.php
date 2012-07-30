<?php

/**
 * Description of install
 *
 * @author gofrendi
 */
class Install extends CMS_Module_Installer {
	protected $DEPENDENCIES = array();
	protected $NAME = 'gofrendi.blog';
	
    //this should be what happen when user install this module
    protected function do_install(){
        $this->remove_all();
        $this->build_all();
    }
    //this should be what happen when user uninstall this module
    protected function do_uninstall(){
        $this->remove_all();
    }
    
    private function remove_all(){
    	$this->db->query("DROP TABLE IF EXISTS `blog_comment`;");        
        $this->db->query("DROP TABLE IF EXISTS `blog_photo`;");
        $this->db->query("DROP TABLE IF EXISTS `blog_category_article`;");
        $this->db->query("DROP TABLE IF EXISTS `blog_article`;");
        $this->db->query("DROP TABLE IF EXISTS `blog_category`;");
        
        
        $this->remove_navigation("blog_comment");
        $this->remove_navigation("blog_photo");
        $this->remove_navigation("blog_article");
        $this->remove_navigation("blog_category");
        $this->remove_navigation("blog_management");
        $this->remove_navigation("blog_index");         
    }
    
    private function build_all(){
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `blog_article` (
              `article_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
              `article_title` varchar(100) NOT NULL,
              `date` DATE NOT NULL,
              `author_user_id` int(20) unsigned NOT NULL,
              `content` text,
              `allow_comment` tinyint(3) unsigned NOT NULL DEFAULT '0',
              PRIMARY KEY (`article_id`),
        	  UNIQUE KEY (`article_title`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
         ");
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `blog_comment` (
              `comment_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
              `article_id` int(20) unsigned NOT NULL,
              `date` DATE NOT NULL,
              `author_user_id` int(20) unsigned NULL,
              `name` varchar(50),
              `email` varchar(50),
              `website` varchar(50), 
              `content` text,
              PRIMARY KEY (`comment_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
         ");
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `blog_photo` (
              `photo_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
              `article_id` int(20) unsigned NOT NULL,
              `url` varchar(50),
              PRIMARY KEY (`photo_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
         ");
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `blog_category` (
              `category_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
              `category_name` varchar(100) NOT NULL,
              `description` text,
              PRIMARY KEY (`category_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
         ");
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `blog_category_article` (
              `category_id` int(20) unsigned NOT NULL,
              `article_id` int(20) unsigned NOT NULL,
              PRIMARY KEY (`category_id`,`article_id`),
              KEY `article_id` (`article_id`),
              CONSTRAINT `blog_category_article_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `blog_article` (`article_id`),
              CONSTRAINT `blog_category_article_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `blog_category` (`category_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
         ");
        
        $this->add_navigation("blog_index","Blog", $this->cms_module_path());
        $this->add_navigation("blog_management", "Manage Blog", $this->cms_module_path()."/manage", 4);
        $this->add_navigation("blog_category", "Manage Category", $this->cms_module_path()."/category", 4, "blog_management");
        $this->add_navigation("blog_article", "Manage Article", $this->cms_module_path()."/article", 4, "blog_management");
        $this->add_navigation("blog_photo", "Manage Photo", $this->cms_module_path()."/photo", 4, "blog_management");
        $this->add_navigation("blog_comment", "Manage Comment", $this->cms_module_path()."/comment", 4, "blog_management");
    }
}

?>
