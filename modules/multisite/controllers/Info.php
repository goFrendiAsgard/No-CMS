<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for multisite
 *
 * @author No-CMS Module Generator
 */
class Info extends CMS_Module {

    public $templates;

    public function __construct(){
        parent::__construct();
        $this->templates = array(
            array(
                    'name'          => 'Blog',
                    'icon'          => 'Blog.png',
                    'description'   => 'Blog website',
                    'homepage'      => '{{ widget_name:blog_content }}',
                    'configuration' => '{}',
                    'modules'       => 'blog, static_accessories, contact_us',
                ),
            array(
                    'name'          => 'Static Portofolio 1',
                    'icon'          => 'Static Portofolio 1.png',
                    'description'   => 'Static Portofolio 1. This template is perfect to show your portofolio. You might need some HTML knowledge to modify the homepage.',
                    'homepage'      => '<!-- Page Heading -->'.PHP_EOL.'<div class="row">'.PHP_EOL.'    <div class="col-lg-12">'.PHP_EOL.'        <h1 class="page-header">Page Heading'.PHP_EOL.'            <small>Secondary Text</small>'.PHP_EOL.'        </h1>'.PHP_EOL.'    </div>'.PHP_EOL.'</div>'.PHP_EOL.'<!-- /.row -->'.PHP_EOL.''.PHP_EOL.'<!-- Project One -->'.PHP_EOL.'<div class="row">'.PHP_EOL.'    <div class="col-md-7">'.PHP_EOL.'        <a href="#">'.PHP_EOL.'            <img class="img-responsive" src="http://placehold.it/700x300" alt="">'.PHP_EOL.'        </a>'.PHP_EOL.'    </div>'.PHP_EOL.'    <div class="col-md-5">'.PHP_EOL.'        <h3>Project One</h3>'.PHP_EOL.'        <h4>Subheading</h4>'.PHP_EOL.'        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium veniam exercitationem expedita laborum at voluptate. Labore, voluptates totam at aut nemo deserunt rem magni pariatur quos perspiciatis atque eveniet unde.</p>'.PHP_EOL.'        <a class="btn btn-primary" href="#">View Project <span class="glyphicon glyphicon-chevron-right"></span></a>'.PHP_EOL.'    </div>'.PHP_EOL.'</div>'.PHP_EOL.'<!-- /.row -->'.PHP_EOL.''.PHP_EOL.'<hr>'.PHP_EOL.''.PHP_EOL.'<!-- Project Two -->'.PHP_EOL.'<div class="row">'.PHP_EOL.'    <div class="col-md-7">'.PHP_EOL.'        <a href="#">'.PHP_EOL.'            <img class="img-responsive" src="http://placehold.it/700x300" alt="">'.PHP_EOL.'        </a>'.PHP_EOL.'    </div>'.PHP_EOL.'    <div class="col-md-5">'.PHP_EOL.'        <h3>Project Two</h3>'.PHP_EOL.'        <h4>Subheading</h4>'.PHP_EOL.'        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ut, odit velit cumque vero doloremque repellendus distinctio maiores rem expedita a nam vitae modi quidem similique ducimus! Velit, esse totam tempore.</p>'.PHP_EOL.'        <a class="btn btn-primary" href="#">View Project <span class="glyphicon glyphicon-chevron-right"></span></a>'.PHP_EOL.'    </div>'.PHP_EOL.'</div>'.PHP_EOL.'<!-- /.row -->'.PHP_EOL.''.PHP_EOL.'<hr>'.PHP_EOL.''.PHP_EOL.'<!-- Project Three -->'.PHP_EOL.'<div class="row">'.PHP_EOL.'    <div class="col-md-7">'.PHP_EOL.'        <a href="#">'.PHP_EOL.'            <img class="img-responsive" src="http://placehold.it/700x300" alt="">'.PHP_EOL.'        </a>'.PHP_EOL.'    </div>'.PHP_EOL.'    <div class="col-md-5">'.PHP_EOL.'        <h3>Project Three</h3>'.PHP_EOL.'        <h4>Subheading</h4>'.PHP_EOL.'        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis, temporibus, dolores, at, praesentium ut unde repudiandae voluptatum sit ab debitis suscipit fugiat natus velit excepturi amet commodi deleniti alias possimus!</p>'.PHP_EOL.'        <a class="btn btn-primary" href="#">View Project <span class="glyphicon glyphicon-chevron-right"></span></a>'.PHP_EOL.'    </div>'.PHP_EOL.'</div>'.PHP_EOL.'<!-- /.row -->'.PHP_EOL.''.PHP_EOL.'<hr>'.PHP_EOL.''.PHP_EOL.'<!-- Project Four -->'.PHP_EOL.'<div class="row">'.PHP_EOL.''.PHP_EOL.'    <div class="col-md-7">'.PHP_EOL.'        <a href="#">'.PHP_EOL.'            <img class="img-responsive" src="http://placehold.it/700x300" alt="">'.PHP_EOL.'        </a>'.PHP_EOL.'    </div>'.PHP_EOL.'    <div class="col-md-5">'.PHP_EOL.'        <h3>Project Four</h3>'.PHP_EOL.'        <h4>Subheading</h4>'.PHP_EOL.'        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Explicabo, quidem, consectetur, officia rem officiis illum aliquam perspiciatis aspernatur quod modi hic nemo qui soluta aut eius fugit quam in suscipit?</p>'.PHP_EOL.'        <a class="btn btn-primary" href="#">View Project <span class="glyphicon glyphicon-chevron-right"></span></a>'.PHP_EOL.'    </div>'.PHP_EOL.'</div>'.PHP_EOL.'<!-- /.row -->'.PHP_EOL.''.PHP_EOL.'<hr>'.PHP_EOL.''.PHP_EOL.'<!-- Project Five -->'.PHP_EOL.'<div class="row">'.PHP_EOL.'    <div class="col-md-7">'.PHP_EOL.'        <a href="#">'.PHP_EOL.'            <img class="img-responsive" src="http://placehold.it/700x300" alt="">'.PHP_EOL.'        </a>'.PHP_EOL.'    </div>'.PHP_EOL.'    <div class="col-md-5">'.PHP_EOL.'        <h3>Project Five</h3>'.PHP_EOL.'        <h4>Subheading</h4>'.PHP_EOL.'        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid, quo, minima, inventore voluptatum saepe quos nostrum provident ex quisquam hic odio repellendus atque porro distinctio quae id laboriosam facilis dolorum.</p>'.PHP_EOL.'        <a class="btn btn-primary" href="#">View Project <span class="glyphicon glyphicon-chevron-right"></span></a>'.PHP_EOL.'    </div>'.PHP_EOL.'</div>'.PHP_EOL.'<!-- /.row -->',
                    'configuration' => '{}',
                    'modules'       => 'blog, static_accessories, contact_us',
                ),
            array(
                    'name'          => 'Static Portofolio 2',
                    'icon'          => 'Static Portofolio 2.png',
                    'description'   => 'Static Portofolio 2. This template is perfect to show your portofolio. You might need some HTML knowledge to modify the homepage.',
                    'homepage'      => '<!-- Page Header -->'.PHP_EOL.'<div class="row">'.PHP_EOL.'    <div class="col-lg-12">'.PHP_EOL.'        <h1 class="page-header">Page Heading'.PHP_EOL.'            <small>Secondary Text</small>'.PHP_EOL.'        </h1>'.PHP_EOL.'    </div>'.PHP_EOL.'</div>'.PHP_EOL.'<!-- /.row -->'.PHP_EOL.''.PHP_EOL.'<!-- Projects Row -->'.PHP_EOL.'<div class="row">'.PHP_EOL.'    <div class="col-md-6 portfolio-item">'.PHP_EOL.'        <a href="#">'.PHP_EOL.'            <img class="img-responsive" src="http://placehold.it/700x400" alt="">'.PHP_EOL.'        </a>'.PHP_EOL.'        <h3>'.PHP_EOL.'            <a href="#">Project One</a>'.PHP_EOL.'        </h3>'.PHP_EOL.'        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae.</p>'.PHP_EOL.'    </div>'.PHP_EOL.'    <div class="col-md-6 portfolio-item">'.PHP_EOL.'        <a href="#">'.PHP_EOL.'            <img class="img-responsive" src="http://placehold.it/700x400" alt="">'.PHP_EOL.'        </a>'.PHP_EOL.'        <h3>'.PHP_EOL.'            <a href="#">Project Two</a>'.PHP_EOL.'        </h3>'.PHP_EOL.'        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae.</p>'.PHP_EOL.'    </div>'.PHP_EOL.'</div>'.PHP_EOL.'<!-- /.row -->'.PHP_EOL.''.PHP_EOL.'<!-- Projects Row -->'.PHP_EOL.'<div class="row">'.PHP_EOL.'    <div class="col-md-6 portfolio-item">'.PHP_EOL.'        <a href="#">'.PHP_EOL.'            <img class="img-responsive" src="http://placehold.it/700x400" alt="">'.PHP_EOL.'        </a>'.PHP_EOL.'        <h3>'.PHP_EOL.'            <a href="#">Project Three</a>'.PHP_EOL.'        </h3>'.PHP_EOL.'        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae.</p>'.PHP_EOL.'    </div>'.PHP_EOL.'    <div class="col-md-6 portfolio-item">'.PHP_EOL.'        <a href="#">'.PHP_EOL.'            <img class="img-responsive" src="http://placehold.it/700x400" alt="">'.PHP_EOL.'        </a>'.PHP_EOL.'        <h3>'.PHP_EOL.'            <a href="#">Project Four</a>'.PHP_EOL.'        </h3>'.PHP_EOL.'        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae.</p>'.PHP_EOL.'    </div>'.PHP_EOL.'</div>'.PHP_EOL.'<!-- /.row -->'.PHP_EOL.''.PHP_EOL.'<!-- Projects Row -->'.PHP_EOL.'<div class="row">'.PHP_EOL.'    <div class="col-md-6 portfolio-item">'.PHP_EOL.'        <a href="#">'.PHP_EOL.'            <img class="img-responsive" src="http://placehold.it/700x400" alt="">'.PHP_EOL.'        </a>'.PHP_EOL.'        <h3>'.PHP_EOL.'            <a href="#">Project Five</a>'.PHP_EOL.'        </h3>'.PHP_EOL.'        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae.</p>'.PHP_EOL.'    </div>'.PHP_EOL.'    <div class="col-md-6 portfolio-item">'.PHP_EOL.'        <a href="#">'.PHP_EOL.'            <img class="img-responsive" src="http://placehold.it/700x400" alt="">'.PHP_EOL.'        </a>'.PHP_EOL.'        <h3>'.PHP_EOL.'            <a href="#">Project Six</a>'.PHP_EOL.'        </h3>'.PHP_EOL.'        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae.</p>'.PHP_EOL.'    </div>'.PHP_EOL.'</div>'.PHP_EOL.'<!-- /.row -->',
                    'configuration' => '{}',
                    'modules'       => 'blog, static_accessories, contact_us',
                ),
            array(
                    'name'          => 'Static Small Business',
                    'icon'          => 'Static Small Business.png',
                    'description'   => 'Static Small Business. This template is perfect to introduce your business. You might need some HTML knowledge to modify the homepage.',
                    'homepage'      => '<!-- Heading Row -->'.PHP_EOL.'<div class="row">'.PHP_EOL.'    <div class="col-md-8">'.PHP_EOL.'        <img class="img-responsive img-rounded" src="http://placehold.it/900x350" alt="">'.PHP_EOL.'    </div>'.PHP_EOL.'    <!-- /.col-md-8 -->'.PHP_EOL.'    <div class="col-md-4">'.PHP_EOL.'        <h1>Business Name or Tagline</h1>'.PHP_EOL.'        <p>This is a template that is great for small businesses. It doesn\'t have too much fancy flare to it, but it makes a great use of the standard Bootstrap core components. Feel free to use this template for any project you want!</p>'.PHP_EOL.'        <a class="btn btn-primary btn-lg" href="#">Call to Action!</a>'.PHP_EOL.'    </div>'.PHP_EOL.'    <!-- /.col-md-4 -->'.PHP_EOL.'</div>'.PHP_EOL.'<!-- /.row -->'.PHP_EOL.''.PHP_EOL.'<hr>'.PHP_EOL.''.PHP_EOL.'<!-- Call to Action Well -->'.PHP_EOL.'<div class="row">'.PHP_EOL.'    <div class="col-lg-12">'.PHP_EOL.'        <div class="well text-center">'.PHP_EOL.'            This is a well that is a great spot for a business tagline or phone number for easy access!'.PHP_EOL.'        </div>'.PHP_EOL.'    </div>'.PHP_EOL.'    <!-- /.col-lg-12 -->'.PHP_EOL.'</div>'.PHP_EOL.'<!-- /.row -->'.PHP_EOL.''.PHP_EOL.'<!-- Content Row -->'.PHP_EOL.'<div class="row">'.PHP_EOL.'    <div class="col-md-4">'.PHP_EOL.'        <h2>Heading 1</h2>'.PHP_EOL.'        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Saepe rem nisi accusamus error velit animi non ipsa placeat. Recusandae, suscipit, soluta quibusdam accusamus a veniam quaerat eveniet eligendi dolor consectetur.</p>'.PHP_EOL.'        <a class="btn btn-default" href="#">More Info</a>'.PHP_EOL.'    </div>'.PHP_EOL.'    <!-- /.col-md-4 -->'.PHP_EOL.'    <div class="col-md-4">'.PHP_EOL.'        <h2>Heading 2</h2>'.PHP_EOL.'        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Saepe rem nisi accusamus error velit animi non ipsa placeat. Recusandae, suscipit, soluta quibusdam accusamus a veniam quaerat eveniet eligendi dolor consectetur.</p>'.PHP_EOL.'        <a class="btn btn-default" href="#">More Info</a>'.PHP_EOL.'    </div>'.PHP_EOL.'    <!-- /.col-md-4 -->'.PHP_EOL.'    <div class="col-md-4">'.PHP_EOL.'        <h2>Heading 3</h2>'.PHP_EOL.'        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Saepe rem nisi accusamus error velit animi non ipsa placeat. Recusandae, suscipit, soluta quibusdam accusamus a veniam quaerat eveniet eligendi dolor consectetur.</p>'.PHP_EOL.'        <a class="btn btn-default" href="#">More Info</a>'.PHP_EOL.'    </div>'.PHP_EOL.'    <!-- /.col-md-4 -->'.PHP_EOL.'</div>'.PHP_EOL.'<!-- /.row -->',
                    'configuration' => '{}',
                    'modules'       => 'blog, static_accessories, contact_us',
                ),
            array(
                    'name'          => 'Static About Team',
                    'icon'          => 'Static About Team.png',
                    'description'   => 'Static About Team. This template is perfect to introduce your team. You might need some HTML knowledge to modify the homepage.',
                    'homepage'      => '<!-- Introduction Row -->'.PHP_EOL.'<div class="row">'.PHP_EOL.'    <div class="col-lg-12">'.PHP_EOL.'        <h1 class="page-header">About Us'.PHP_EOL.'            <small>It\'s Nice to Meet You!</small>'.PHP_EOL.'        </h1>'.PHP_EOL.'        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sint, explicabo dolores ipsam aliquam inventore corrupti eveniet quisquam quod totam laudantium repudiandae obcaecati ea consectetur debitis velit facere nisi expedita vel?</p>'.PHP_EOL.'    </div>'.PHP_EOL.'</div>'.PHP_EOL.''.PHP_EOL.'<!-- Team Members Row -->'.PHP_EOL.'<div class="row">'.PHP_EOL.'    <div class="col-lg-12">'.PHP_EOL.'        <h2 class="page-header">Our Team</h2>'.PHP_EOL.'    </div>'.PHP_EOL.'    <div class="col-lg-4 col-sm-6 text-center">'.PHP_EOL.'        <img class="img-circle img-responsive img-center" src="http://placehold.it/200x200" alt="">'.PHP_EOL.'        <h3>John Smith'.PHP_EOL.'            <small>Job Title</small>'.PHP_EOL.'        </h3>'.PHP_EOL.'        <p>What does this team member to? Keep it short! This is also a great spot for social links!</p>'.PHP_EOL.'    </div>'.PHP_EOL.'    <div class="col-lg-4 col-sm-6 text-center">'.PHP_EOL.'        <img class="img-circle img-responsive img-center" src="http://placehold.it/200x200" alt="">'.PHP_EOL.'        <h3>John Smith'.PHP_EOL.'            <small>Job Title</small>'.PHP_EOL.'        </h3>'.PHP_EOL.'        <p>What does this team member to? Keep it short! This is also a great spot for social links!</p>'.PHP_EOL.'    </div>'.PHP_EOL.'    <div class="col-lg-4 col-sm-6 text-center">'.PHP_EOL.'        <img class="img-circle img-responsive img-center" src="http://placehold.it/200x200" alt="">'.PHP_EOL.'        <h3>John Smith'.PHP_EOL.'            <small>Job Title</small>'.PHP_EOL.'        </h3>'.PHP_EOL.'        <p>What does this team member to? Keep it short! This is also a great spot for social links!</p>'.PHP_EOL.'    </div>'.PHP_EOL.'    <div class="col-lg-4 col-sm-6 text-center">'.PHP_EOL.'        <img class="img-circle img-responsive img-center" src="http://placehold.it/200x200" alt="">'.PHP_EOL.'        <h3>John Smith'.PHP_EOL.'            <small>Job Title</small>'.PHP_EOL.'        </h3>'.PHP_EOL.'        <p>What does this team member to? Keep it short! This is also a great spot for social links!</p>'.PHP_EOL.'    </div>'.PHP_EOL.'    <div class="col-lg-4 col-sm-6 text-center">'.PHP_EOL.'        <img class="img-circle img-responsive img-center" src="http://placehold.it/200x200" alt="">'.PHP_EOL.'        <h3>John Smith'.PHP_EOL.'            <small>Job Title</small>'.PHP_EOL.'        </h3>'.PHP_EOL.'        <p>What does this team member to? Keep it short! This is also a great spot for social links!</p>'.PHP_EOL.'    </div>'.PHP_EOL.'    <div class="col-lg-4 col-sm-6 text-center">'.PHP_EOL.'        <img class="img-circle img-responsive img-center" src="http://placehold.it/200x200" alt="">'.PHP_EOL.'        <h3>John Smith'.PHP_EOL.'            <small>Job Title</small>'.PHP_EOL.'        </h3>'.PHP_EOL.'        <p>What does this team member to? Keep it short! This is also a great spot for social links!</p>'.PHP_EOL.'    </div>'.PHP_EOL.'</div>',
                    'configuration' => '{}',
                    'modules'       => 'blog, static_accessories, contact_us',
                ),
        );
    }

    // ACTIVATION
    public function do_activate(){
        $this->remove_all();
        $this->build_all();
    }

    // DEACTIVATION
    public function do_deactivate(){
        $this->backup_database(array(
            $this->t('subsite')
        ));
        $this->remove_all();
    }

    // UPGRADE
    public function do_upgrade($old_version){
        $version_part = explode('.', $old_version);
        $major        = $version_part[0];
        $minor        = $version_part[1];
        $build        = $version_part[2];
        $module_path  = $this->cms_module_path();

        if($major <= 0 && $minor <= 0 && $build <= 1){
            // Add your migration logic here.
            // table : subsite
            $table_name = $this->t('subsite');
            $field_list = $this->db->list_fields($table_name);
            $missing_fields = array(
                'user_id'=>array("type"=>'int', "constraint"=>10, "null"=>TRUE),
                'active'=>array("type"=>'int', "constraint"=>10, "null"=>TRUE, "default"=>1),
            );
            $fields = array();
            foreach($missing_fields as $key=>$value){
                if(!in_array($key, $field_list)){
                    $fields[$key] = $value;
                }
            }
            $this->dbforge->add_column($table_name, $fields);
        }
        if($major <= 0 && $minor <= 0 && $build <= 2){
            $fields = array(
                'name' => array("type"=>'varchar', "constraint"=>100, "null"=>TRUE),
            );
            $this->dbforge->modify_column($this->t('subsite'), $fields);
        }
        if($major <= 0 && $minor <= 0 && $build <= 3){
            $fields = array(
                'id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
                'name'=> array("type"=>'varchar', "constraint"=>100, "null"=>TRUE),
                'icon'=> array("type"=>'varchar', "constraint"=>255, "null"=>TRUE),
                'description'=> array("type"=>'text', "null"=>TRUE),
                'homepage'=>array("type"=>'text', "null"=>TRUE),
                'configuration'=>array("type"=>'text', "null"=>TRUE),
                'modules'=>array("type"=>'text', "null"=>TRUE),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table($this->t('template'));

            if(CMS_SUBSITE == ''){
                $this->cms_add_navigation($this->n('template'), 'Manage Template',
                    $module_path.'/manage_template', PRIV_AUTHORIZED, $this->n('index'),
                    NULL, NULL, NULL, NULL, 'default-one-column'
                );
            }
        }

        $this->insert_templates();
    }

    /////////////////////////////////////////////////////////////////////////////
    // Private Functions
    /////////////////////////////////////////////////////////////////////////////

    // REMOVE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
    private function remove_all(){
        $module_path = $this->cms_module_path();

        if(CMS_SUBSITE == ''){
            // remove navigations
            $this->cms_remove_navigation($this->n('add_subsite'));
            $this->cms_remove_navigation($this->n('manage_template'));
            // remove privileges
            $this->cms_remove_privilege('modify_subsite');
        }


        // remove parent of all navigations
        $this->cms_remove_navigation($this->n('index'));

        // drop tables
        $this->dbforge->drop_table($this->t('subsite'), TRUE);
        $this->dbforge->drop_table($this->t('template'), TRUE);

        // remove route
        $this->cms_remove_route('main/register');
    }

    // CREATE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
    private function build_all(){
        $module_path = $this->cms_module_path();

        // parent of all navigations
        $this->cms_add_navigation($this->n('index'), 'Multisite',
            ($module_path == 'multisite'? $module_path : $module_path.'/multisite'), PRIV_EVERYONE, NULL,
            NULL, 'Browse subsites', 'glyphicon-dashboard');


        if(CMS_SUBSITE == ''){
            // add privileges
            $this->cms_add_privilege('modify_subsite', 'Modify subsite');
            // add navigations
            $this->cms_add_navigation($this->n('add_subsite'), 'Add Subsite',
                $module_path.'/add_subsite', PRIV_AUTHORIZED, $this->n('index'),
                NULL, 'Browse subsites', 'glyphicon-plus', NULL, 'default-one-column'
            );

            $this->cms_add_navigation($this->n('manage_template'), 'Manage Template',
                $module_path.'/manage_template', PRIV_AUTHORIZED, $this->n('index'),
                NULL, NULL, NULL, NULL, 'default-one-column'
            );
        }


        // create tables
        $fields = array(
            'id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'name'=> array("type"=>'varchar', "constraint"=>100, "null"=>TRUE),
            'use_subdomain'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'aliases'=> array("type"=>'text', "null"=>TRUE),
            'logo'=> array("type"=>'varchar', "constraint"=>100, "null"=>TRUE),
            'description'=> array("type"=>'text', "null"=>TRUE),
            'modules'=>array("type"=>'text', "null"=>TRUE),
            'themes'=>array("type"=>'text', "null"=>TRUE),
            'user_id'=>array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'active'=>array("type"=>'int', "constraint"=>10, "null"=>TRUE, "default"=>1),
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($this->t('subsite'));

        $fields = array(
            'id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'name'=> array("type"=>'varchar', "constraint"=>100, "null"=>TRUE),
            'icon'=> array("type"=>'varchar', "constraint"=>255, "null"=>TRUE),
            'description'=> array("type"=>'text', "null"=>TRUE),
            'homepage'=>array("type"=>'text', "null"=>TRUE),
            'configuration'=>array("type"=>'text', "null"=>TRUE),
            'modules'=>array("type"=>'text', "null"=>TRUE),
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($this->t('template'));

        $this->insert_templates();

        if(strtoupper($this->cms_get_config('cms_add_subsite_on_register')) == 'TRUE'){
            $this->cms_add_route('main/register', $module_path.'/multisite/register');
        }

    }

    public function insert_templates(){
        foreach($this->templates as $template){
            $query = $this->db->select('name')
                ->from($this->t('template'))
                ->where('name', $template['name'])
                ->get();
            if($query->num_rows() == 0){
                $this->db->insert($this->t('template'),$template);
            }
        }
    }

}
