<?php

/**
 * Description of install
 *
 * @author gofrendi
 */
class Install extends CMS_Module_Installer {
    //this should be what happen when user install this module
    protected function do_install(){
        $this->remove_all();
        $this->build_all();
        redirect('blog');
    }
    //this should be what happen when user uninstall this module
    protected function do_uninstall(){
        $this->remove_all();
        redirect('main');
    }
    
    private function remove_all(){
        $this->db->query("DROP TABLE IF EXISTS `blog_category_article`;");
        $this->db->query("DROP TABLE IF EXISTS `blog_article`;");
        $this->db->query("DROP TABLE IF EXISTS `blog_category`;");
        
        $this->remove_navigation("blog_article");
        $this->remove_navigation("blog_category");
        $this->remove_navigation("blog_management");
        $this->remove_navigation("blog");         
    }
    
    private function build_all(){
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `blog_article` (
              `article_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
              `article_title` varchar(100) NOT NULL,
              `date` DATE NOT NULL,
              `author_user_id` int(20) unsigned NOT NULL,
              `content` text,
              PRIMARY KEY (`article_id`)
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
        
        $this->add_navigation("blog","Blog", "blog");
        $this->add_navigation("blog_management", "Manage Blog", "blog/manage", 4);
        $this->add_navigation("blog_category", "Manage Category", "blog/category", 4, "blog_management");
        $this->add_navigation("blog_article", "Manage Article", "blog/article", 4, "blog_management");
    }
}

?>
