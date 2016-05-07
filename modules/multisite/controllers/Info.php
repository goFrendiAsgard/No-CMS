<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for new_multisite
 *
 * @author No-CMS Module Generator
 */
class Info extends CMS_Module {

    //////////////////////////////////////////////////////////////////////////////
    // NAVIGATIONS
    //////////////////////////////////////////////////////////////////////////////
    protected $NAVIGATIONS = array(
            // New Multisite
            array(
                'navigation_name'   => 'index',
                'url'               => 'multisite',
                'authorization_id'  => PRIV_EVERYONE,
                'default_layout'    => NULL,
                'title'             => 'Multisite',
                'parent_name'       => NULL,
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => 'glyphicon-dashboard',
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),
            array(
                'navigation_name'   => 'add_subsite',
                'url'               => 'add_subsite',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Add Subsite',
                'parent_name'       => 'index',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => 'glyphicon-plus',
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),

        );

    protected $BACKEND_NAVIGATIONS = array(
            // Manage Subsite
            /*
            array(
                'entity_name'       => 'subsite',
                'url'               => 'manage_subsite',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage Subsite',
                'parent_name'       => 'index',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),*/
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

        );

    //////////////////////////////////////////////////////////////////////////////
    // CONFIGURATIONS
    //////////////////////////////////////////////////////////////////////////////
    protected $CONFIGS = array();

    //////////////////////////////////////////////////////////////////////////////
    // PRIVILEGES
    //////////////////////////////////////////////////////////////////////////////
    protected $PRIVILEGES = array(
        array('privilege_name' => 'modify_subsite', 'description' => 'Modify Subsite', 'authorization_id' => 4)
    );

    //////////////////////////////////////////////////////////////////////////////
    // GROUPS
    //////////////////////////////////////////////////////////////////////////////
    protected $GROUPS = array(
            array('group_name' => 'Multisite Manager', 'description' => 'Multisite Manager'),
        );
    protected $GROUP_NAVIGATIONS = array();
    protected $GROUP_BACKEND_NAVIGATIONS = array(
            'Multisite Manager' => array('subsite', 'template')
        );
    protected $GROUP_PRIVILEGES = array();
    protected $GROUP_BACKEND_PRIVILEGES = array(
            'Multisite Manager' => array(
                'subsite' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
                'template' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
            )
        );

    //////////////////////////////////////////////////////////////////////////////
    // TABLES and DATA
    //////////////////////////////////////////////////////////////////////////////
    protected $TABLES = array(
        // subsite
        'subsite' => array(
            'key'    => 'id',
            'fields' => array(
                'id'               => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'name'             => array("type" => 'varchar',    "constraint" => 100, "null" => TRUE),
                'use_subdomain'    => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'aliases'          => array("type" => 'text',       "null" => TRUE),
                'logo'             => array("type" => 'varchar',    "constraint" => 100, "null" => TRUE),
                'description'      => array("type" => 'text',       "null" => TRUE),
                'modules'          => array("type" => 'text',       "null" => TRUE),
                'themes'           => array("type" => 'text',       "null" => TRUE),
                'user_id'          => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'active'           => array("type" => 'int',        "constraint" => 10,
                                        "null" => TRUE, "default" => 1),
            ),
        ),
        // template
        'template' => array(
            'key'    => 'id',
            'fields' => array(
                'id'               => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'name'             => array("type" => 'varchar',    "constraint" => 100, "null" => TRUE),
                'icon'             => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
                'description'      => array("type" => 'text',       "null" => TRUE),
                'homepage'         => array("type" => 'text',       "null" => TRUE),
                'configuration'    => array("type" => 'text',       "null" => TRUE),
                'modules'          => array("type" => 'text',       "null" => TRUE),
            ),
        ),
    );
    protected $DATA = array(
        'template' => array(
            array('id' => '1', 'name' => 'Blog', 'icon' => 'Blog.png', 'description' => 'Blog website', 'homepage' => '{{ widget_name:blog_content }}', 'configuration' => '{}', 'modules' => 'blog, static_accessories, contact_us'),
            array('id' => '2', 'name' => 'Static Portofolio 1', 'icon' => 'Static Portofolio 1.png', 'description' => 'Static Portofolio 1. This template is perfect to show your portofolio. You might need some HTML knowledge to modify the homepage.', 'homepage' => '<!-- Page Heading -->\n<div class=\"row\">\n    <div class=\"col-lg-12\">\n        <h1 class=\"page-header\">Page Heading\n            <small>Secondary Text</small>\n        </h1>\n    </div>\n</div>\n<!-- /.row -->\n\n<!-- Project One -->\n<div class=\"row\">\n    <div class=\"col-md-7\">\n        <a href=\"#\">\n            <img class=\"img-responsive\" src=\"http://placehold.it/700x300\" alt=\"\">\n        </a>\n    </div>\n    <div class=\"col-md-5\">\n        <h3>Project One</h3>\n        <h4>Subheading</h4>\n        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium veniam exercitationem expedita laborum at voluptate. Labore, voluptates totam at aut nemo deserunt rem magni pariatur quos perspiciatis atque eveniet unde.</p>\n        <a class=\"btn btn-primary\" href=\"#\">View Project <span class=\"glyphicon glyphicon-chevron-right\"></span></a>\n    </div>\n</div>\n<!-- /.row -->\n\n<hr>\n\n<!-- Project Two -->\n<div class=\"row\">\n    <div class=\"col-md-7\">\n        <a href=\"#\">\n            <img class=\"img-responsive\" src=\"http://placehold.it/700x300\" alt=\"\">\n        </a>\n    </div>\n    <div class=\"col-md-5\">\n        <h3>Project Two</h3>\n        <h4>Subheading</h4>\n        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ut, odit velit cumque vero doloremque repellendus distinctio maiores rem expedita a nam vitae modi quidem similique ducimus! Velit, esse totam tempore.</p>\n        <a class=\"btn btn-primary\" href=\"#\">View Project <span class=\"glyphicon glyphicon-chevron-right\"></span></a>\n    </div>\n</div>\n<!-- /.row -->\n\n<hr>\n\n<!-- Project Three -->\n<div class=\"row\">\n    <div class=\"col-md-7\">\n        <a href=\"#\">\n            <img class=\"img-responsive\" src=\"http://placehold.it/700x300\" alt=\"\">\n        </a>\n    </div>\n    <div class=\"col-md-5\">\n        <h3>Project Three</h3>\n        <h4>Subheading</h4>\n        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis, temporibus, dolores, at, praesentium ut unde repudiandae voluptatum sit ab debitis suscipit fugiat natus velit excepturi amet commodi deleniti alias possimus!</p>\n        <a class=\"btn btn-primary\" href=\"#\">View Project <span class=\"glyphicon glyphicon-chevron-right\"></span></a>\n    </div>\n</div>\n<!-- /.row -->\n\n<hr>\n\n<!-- Project Four -->\n<div class=\"row\">\n\n    <div class=\"col-md-7\">\n        <a href=\"#\">\n            <img class=\"img-responsive\" src=\"http://placehold.it/700x300\" alt=\"\">\n        </a>\n    </div>\n    <div class=\"col-md-5\">\n        <h3>Project Four</h3>\n        <h4>Subheading</h4>\n        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Explicabo, quidem, consectetur, officia rem officiis illum aliquam perspiciatis aspernatur quod modi hic nemo qui soluta aut eius fugit quam in suscipit?</p>\n        <a class=\"btn btn-primary\" href=\"#\">View Project <span class=\"glyphicon glyphicon-chevron-right\"></span></a>\n    </div>\n</div>\n<!-- /.row -->\n\n<hr>\n\n<!-- Project Five -->\n<div class=\"row\">\n    <div class=\"col-md-7\">\n        <a href=\"#\">\n            <img class=\"img-responsive\" src=\"http://placehold.it/700x300\" alt=\"\">\n        </a>\n    </div>\n    <div class=\"col-md-5\">\n        <h3>Project Five</h3>\n        <h4>Subheading</h4>\n        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid, quo, minima, inventore voluptatum saepe quos nostrum provident ex quisquam hic odio repellendus atque porro distinctio quae id laboriosam facilis dolorum.</p>\n        <a class=\"btn btn-primary\" href=\"#\">View Project <span class=\"glyphicon glyphicon-chevron-right\"></span></a>\n    </div>\n</div>\n<!-- /.row -->', 'configuration' => '{}', 'modules' => 'blog, static_accessories, contact_us'),
            array('id' => '3', 'name' => 'Static Portofolio 2', 'icon' => 'Static Portofolio 2.png', 'description' => 'Static Portofolio 2. This template is perfect to show your portofolio. You might need some HTML knowledge to modify the homepage.', 'homepage' => '<!-- Page Header -->\n<div class=\"row\">\n    <div class=\"col-lg-12\">\n        <h1 class=\"page-header\">Page Heading\n            <small>Secondary Text</small>\n        </h1>\n    </div>\n</div>\n<!-- /.row -->\n\n<!-- Projects Row -->\n<div class=\"row\">\n    <div class=\"col-md-6 portfolio-item\">\n        <a href=\"#\">\n            <img class=\"img-responsive\" src=\"http://placehold.it/700x400\" alt=\"\">\n        </a>\n        <h3>\n            <a href=\"#\">Project One</a>\n        </h3>\n        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae.</p>\n    </div>\n    <div class=\"col-md-6 portfolio-item\">\n        <a href=\"#\">\n            <img class=\"img-responsive\" src=\"http://placehold.it/700x400\" alt=\"\">\n        </a>\n        <h3>\n            <a href=\"#\">Project Two</a>\n        </h3>\n        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae.</p>\n    </div>\n</div>\n<!-- /.row -->\n\n<!-- Projects Row -->\n<div class=\"row\">\n    <div class=\"col-md-6 portfolio-item\">\n        <a href=\"#\">\n            <img class=\"img-responsive\" src=\"http://placehold.it/700x400\" alt=\"\">\n        </a>\n        <h3>\n            <a href=\"#\">Project Three</a>\n        </h3>\n        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae.</p>\n    </div>\n    <div class=\"col-md-6 portfolio-item\">\n        <a href=\"#\">\n            <img class=\"img-responsive\" src=\"http://placehold.it/700x400\" alt=\"\">\n        </a>\n        <h3>\n            <a href=\"#\">Project Four</a>\n        </h3>\n        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae.</p>\n    </div>\n</div>\n<!-- /.row -->\n\n<!-- Projects Row -->\n<div class=\"row\">\n    <div class=\"col-md-6 portfolio-item\">\n        <a href=\"#\">\n            <img class=\"img-responsive\" src=\"http://placehold.it/700x400\" alt=\"\">\n        </a>\n        <h3>\n            <a href=\"#\">Project Five</a>\n        </h3>\n        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae.</p>\n    </div>\n    <div class=\"col-md-6 portfolio-item\">\n        <a href=\"#\">\n            <img class=\"img-responsive\" src=\"http://placehold.it/700x400\" alt=\"\">\n        </a>\n        <h3>\n            <a href=\"#\">Project Six</a>\n        </h3>\n        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae.</p>\n    </div>\n</div>\n<!-- /.row -->', 'configuration' => '{}', 'modules' => 'blog, static_accessories, contact_us'),
            array('id' => '4', 'name' => 'Static Small Business', 'icon' => 'Static Small Business.png', 'description' => 'Static Small Business. This template is perfect to introduce your business. You might need some HTML knowledge to modify the homepage.', 'homepage' => '<!-- Heading Row -->\n<div class=\"row\">\n    <div class=\"col-md-8\">\n        <img class=\"img-responsive img-rounded\" src=\"http://placehold.it/900x350\" alt=\"\">\n    </div>\n    <!-- /.col-md-8 -->\n    <div class=\"col-md-4\">\n        <h1>Business Name or Tagline</h1>\n        <p>This is a template that is great for small businesses. It doesn\'t have too much fancy flare to it, but it makes a great use of the standard Bootstrap core components. Feel free to use this template for any project you want!</p>\n        <a class=\"btn btn-primary btn-lg\" href=\"#\">Call to Action!</a>\n    </div>\n    <!-- /.col-md-4 -->\n</div>\n<!-- /.row -->\n\n<hr>\n\n<!-- Call to Action Well -->\n<div class=\"row\">\n    <div class=\"col-lg-12\">\n        <div class=\"well text-center\">\n            This is a well that is a great spot for a business tagline or phone number for easy access!\n        </div>\n    </div>\n    <!-- /.col-lg-12 -->\n</div>\n<!-- /.row -->\n\n<!-- Content Row -->\n<div class=\"row\">\n    <div class=\"col-md-4\">\n        <h2>Heading 1</h2>\n        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Saepe rem nisi accusamus error velit animi non ipsa placeat. Recusandae, suscipit, soluta quibusdam accusamus a veniam quaerat eveniet eligendi dolor consectetur.</p>\n        <a class=\"btn btn-default\" href=\"#\">More Info</a>\n    </div>\n    <!-- /.col-md-4 -->\n    <div class=\"col-md-4\">\n        <h2>Heading 2</h2>\n        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Saepe rem nisi accusamus error velit animi non ipsa placeat. Recusandae, suscipit, soluta quibusdam accusamus a veniam quaerat eveniet eligendi dolor consectetur.</p>\n        <a class=\"btn btn-default\" href=\"#\">More Info</a>\n    </div>\n    <!-- /.col-md-4 -->\n    <div class=\"col-md-4\">\n        <h2>Heading 3</h2>\n        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Saepe rem nisi accusamus error velit animi non ipsa placeat. Recusandae, suscipit, soluta quibusdam accusamus a veniam quaerat eveniet eligendi dolor consectetur.</p>\n        <a class=\"btn btn-default\" href=\"#\">More Info</a>\n    </div>\n    <!-- /.col-md-4 -->\n</div>\n<!-- /.row -->', 'configuration' => '{}', 'modules' => 'blog, static_accessories, contact_us'),
            array('id' => '5', 'name' => 'Static About Team', 'icon' => 'Static About Team.png', 'description' => 'Static About Team. This template is perfect to introduce your team. You might need some HTML knowledge to modify the homepage.', 'homepage' => '<!-- Introduction Row -->\n<div class=\"row\">\n    <div class=\"col-lg-12\">\n       <h1 class=\"page-header\">About Us\n           <small>It\'s Nice to Meet You!</small>\n       </h1>\n       <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sint, explicabo dolores ipsam aliquam inventore corrupti eveniet quisquam quod totam laudantium repudiandae obcaecati ea consectetur debitis velit facere nisi expedita vel?</p>\n   </div>\n</div>\n\n<!-- Team Members Row -->\n<div class=\"row\">\n   <div class=\"col-lg-12\">\n       <h2 class=\"page-header\">Our Team</h2>\n   </div>\n   <div class=\"col-lg-4 col-sm-6 text-center\">\n       <img class=\"img-circle img-responsive img-center\" src=\"http://placehold.it/200x200\" alt=\"\">\n       <h3>John Smith\n           <small>Job Title</small>\n       </h3>\n       <p>What does this team member to? Keep it short! This is also a great spot for social links!</p>\n   </div>\n   <div class=\"col-lg-4 col-sm-6 text-center\">\n       <img class=\"img-circle img-responsive img-center\" src=\"http://placehold.it/200x200\" alt=\"\">\n       <h3>John Smith\n           <small>Job Title</small>\n       </h3>\n       <p>What does this team member to? Keep it short! This is also a great spot for social links!</p>\n   </div>\n   <div class=\"col-lg-4 col-sm-6 text-center\">\n       <img class=\"img-circle img-responsive img-center\" src=\"http://placehold.it/200x200\" alt=\"\">\n       <h3>John Smith\n           <small>Job Title</small>\n       </h3>\n       <p>What does this team member to? Keep it short! This is also a great spot for social links!</p>\n   </div>\n   <div class=\"col-lg-4 col-sm-6 text-center\">\n       <img class=\"img-circle img-responsive img-center\" src=\"http://placehold.it/200x200\" alt=\"\">\n       <h3>John Smith\n           <small>Job Title</small>\n       </h3>\n       <p>What does this team member to? Keep it short! This is also a great spot for social links!</p>\n   </div>\n   <div class=\"col-lg-4 col-sm-6 text-center\">\n       <img class=\"img-circle img-responsive img-center\" src=\"http://placehold.it/200x200\" alt=\"\">\n       <h3>John Smith\n           <small>Job Title</small>\n       </h3>\n       <p>What does this team member to? Keep it short! This is also a great spot for social links!</p>\n   </div>\n   <div class=\"col-lg-4 col-sm-6 text-center\">\n       <img class=\"img-circle img-responsive img-center\" src=\"http://placehold.it/200x200\" alt=\"\">\n       <h3>John Smith\n           <small>Job Title</small>\n       </h3>\n       <p>What does this team member to? Keep it short! This is also a great spot for social links!</p>\n   </div>\n</div>', 'configuration' => '{}', 'modules' => 'blog, static_accessories, contact_us'),
        ),
    );

    public function __construct(){
        parent::__construct();
        if(CMS_SUBSITE != ''){
            $this->BACKEND_NAVIGATIONS = array();
            $this->NAVIGATIONS = array();
        }
    }

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
