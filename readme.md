What is No-CMS?
================

No-CMS is a CMS-framework.

No-CMS is a basic CMS with some default features such as user authorization, menu, module and theme management.
It is fully customizable and extensible, you can make your own module and your own themes.
It provide freedom to make your very own CMS, which is not provided very well by any other CMS.

Who is it for?
--------------
No-CMS will be good for you if you say yes for majority of these statement:

* You are a web developer who use CodeIgniter framework.
* You are tired of building the same things such an authorization-authentication for every project.
* You find that some part of your old project can be used for your next project.
* You are happy with CodeIgniter but you think some plug-ins and features should be provided by default.
* You want a simple and easy to learn framework that has 100% compatibility with CodeIgniter.
* You don't want to learn too many new terms.
* You are familiar with HMVC plugins, and you think it is one of "should be exists" feature in CodeIgniter.
* You are in tight deadline, at least you need to provide the prototype to your client.

No-CMS as CMS
--------------
No-CMS is a "less assumption" CMS. It is different from Wordpress, Drupal, Joomla, Moodle or Zencart. Those CMS are built by developers for users with some special purpose in mind (e.g: blog, news, e-learning, e-commerce).
No-CMS is built by developer for developers, although everyone else can still use it as well. It already has some basic features such as authentication/authorization, widget and page management. You are free to use them or just get rid of them and make your custom code
The main purpose of this CMS is to provide a good start of web application project, especially for CodeIgniter developer.

No-CMS as Application development framework
--------------------------------------------
No-CMS is not just another CMS. No-CMS allows you to make your own module and your own themes.
This means that you (as developer) can make a module (e.g: forum module, FAQ module, etc) that can be used for several different project.

No-CMS takes advantages of CodeIgniter as its core.
It provides rich set of libraries for commonly needed task,
as well as a simple interface and logical structure to access these libraries.
The main advantage of CodeIgniter is you can creatively focus on your project
by minimizing the amount of code needed or a given task.

No-CMS is also take advantages of several popular plugins such as

* HMVC, to make fully modular separation
* Phil Sturgeon's Template, to make customizable themes
* groceryCRUD, to build CRUD application in a minute
* HybridAuth, to provide third party authentication (e.g: facebook, twitter, openID etc)

Out of all, No-CMS also provide some common features:

* Authentication & Authorization by using group, privilege, and user management.
  Not like other CMS, there is no backend-frontend in No-CMS.
  You have freedom to choose how different groups of users can access pages and modules differently.
* Change Theme.
  You can change the theme easily.
* Install/Un-install Module
  You can install/un-install module easily.

In short, if you are familiar with CodeIgniter, No-CMS is a good kickstart to make your web application


Release Information
===================

- v0.6.2 stable, May, 14, 2013


Server Requirements
===================

-  PHP version 5.3.2 or newer.


Documentation
=============

The full documentation is available in pdf format, and can be found once you download No-CMS

Tutorial 00: Installation
=========================

* Download No-CMS from [No-CMS repository](https://github.com/goFrendiAsgard/No-CMS) on GitHub
* Copy and extract it on your web server directory (You might want to try it locally via xampp, in this case, your server directory is c:\xampp\htdocs)
* Access the url (If you use xampp in your local computer, the url should be http://localhost/No-CMS)
* Click "Install Now"
* Fill any information needed (including your administrator password)


Tutorial 01: Navigations & Pages
================================

A website is actually collection of web-pages. Making a page is a very crucial feature in every common-used CMS.

No-CMS allow you to make your own page:

* Login to No-CMS with your admin user.
* Open `Complete Menu | CMS Management | Navigation Management` or `CMS Management | Navigation Management`
* Add a new page by clicking `Add Navigation (Page)`
* Set `Navigation Code` into `new_page`
* Set `Navigation Title` into `Go to My Page`
* Set `Page Title` into `My First Page`
* Set `Static` into `active`
* Set `Static Content` into `Hello World !!!`
* Set `Authorization` into `Everyone`
* Click `Save and Go Back to List`

You can access your new page by clicking `Complete Menu | Go to My Page`.
This new page can be accessed by `Everyone`. No-CMS has several authorization type:
* __Everyone__: Can be accessed by all visitor.
* __Unauthenticated__: Can only be accessed by not currently login visitor.
* __Authenticated__: Can only be accessed by already login visitor
* __Authorized__: Can only be accessed by already login visitor who is also member of certain `group`

Let's add another page as sub-page:

* Open `Complete Menu | CMS Management | Navigation Management` or `CMS Management | Navigation Management`
* Add a new page by clicking `Add Navigation (Page)`
* Set `Navigation Code` into `new_subpage`
* Set `Parent` into `new_page` (Navigation Code of our previous page)
* Set `Navigation Title` into `Go to My Subpage`
* Set `Page Title` into `My Second Page`
* Set `Static` into `active`
* Set `Static Content` into `Great, you made it !!!`
* Set `Authorization` into `Everyone`
* Click `Save and Go Back to List`

You can access this page by clicking `Complete Menu | Go to My Page | Go to My Subpage`

As you application grow, some frequently accessed page might be burried down in the navigation hierarchy.
No-CMS has `quick link` to solve such a problem.

Now let's make a quick link:

* Open `Complete Menu | CMS Management | Quick Link Management` or `CMS Management | Quick Link Management`
* Add new quick link by clicking `Add Quick Link`
* Set `Navigation Code` into `new_subpage`
* Click `Save and Go Back to List`

Now, you can access `new_subpage` directly.


Tutorial 02: Widgets
====================

Widgets are part of the website that always appear in all pages.
No-CMS has several built-in widgets.

Let's add new widget
* Open `Complete Menu | CMS Management | Widget Management` or `CMS Management | Widget Management`. There is already several built-in widgets
* Add new widget by clicking `Add Widget`
* Set `Widget Code` into `fb_badge`
* Set `Static` into `active`
* Set `Static Content` into

```html
    <!-- Facebook Badge START -->
    <a href="https://www.facebook.com/goFrendiAsgard" target="_TOP"
        style="font-family: &quot;lucida grande&quot;,tahoma,verdana,arial,sans-serif; font-size: 11px; font-variant: normal; font-style: normal; font-weight: normal; color: #3B5998; text-decoration: none;"
        title="Go Frendi Asgard">Go Frendi Asgard
    </a><br/>
    <a href="https://www.facebook.com/goFrendiAsgard" target="_TOP"
        title="Go Frendi Asgard">
        <img src="https://badge.facebook.com/badge/696121596.3142.1524306206.png" style="border: 0px;" />
    </a><br/>
    <a href="https://www.facebook.com/badges/" target="_TOP"
        style="font-family: &quot;lucida grande&quot;,tahoma,verdana,arial,sans-serif; font-size: 11px; font-variant: normal; font-style: normal; font-weight: normal; color: #3B5998; text-decoration: none;"
        title="Make your own badge!">Create Your Badge
    </a>
    <!-- Facebook Badge END -->
```
* Set Slug into `sidebar`
* Click `Save and Go Back to List`

Now look at the right side of your site. There should be a my facebook badge.

You can put this widget everywhere.
For example, open up `/themes/neutral/views/layouts/default.php` and put `{{ widget_name:fb_badge }}`
The changes in themes will be applied globally in all of your page.

You can put a single widget by using `{{ widget_name:your_widget_name }}`.
You can also put a group of widgets by using `{{ widget_slug:your_widget_slug }}`.
You can even put widget as part of your page by editing your page static content.

Tutorial 03: Themes
===================

You can change No-CMS theme by accessing `Complete Menu | CMS Management | Change Theme` or `CMS Management | Change Theme`.

To set per-page theme, you can access `Complete Menu | Page Management` and set `Default Theme`

Themes are located at `/themes/` folder. With such a structure:
```
    /themes
        |--- [your_theme]
                |--- /assets
                |       |--- /default                       (consists of js, css, images etc)
                |       |--- /[other_layout]                (optional)
                |
                |--- /views
                        |--- /layouts
                        |       |--- default.php            (here is the main UI script)
                        |       |--- [other_layout].php     (optional)
                        |
                        |--- /partials
                                |--- /default
                                |--- /other_layout
```
For more information about themes, please refer to the documentation provided.

Tutorial 04: Modules
====================

No-CMS use HMVC (Hierarchical Model-View-Controller) architecture.
Each module consists of MVC triad.

Let's start to make a new module:
```
    /modules
        |--- /new_module
                |--- /controllers
                |       |--- pokemon.php
                |       |--- install.php
                |
                |--- /models
                |       |--- pokemon_model.php
                |
                |--- /views
                        |--- pokemon_index.php
```

Make a controller
-----------------
Modify your `/modules/new_module/controllers/pokemon.php` into this:
```php
    <?php
    class Pokemon extends CMS_Controller{

        function show(){
            echo '<ul>
                    <li>pikachu</li>
                    <li>charmender</li>
                    <li>bulbasur</li>
                    <li>squirtle</li>
                </ul>';
        }

    }
```
Now, you have a controller in `/modules/new_module/controllers/pokemon.php`.
A controller should extend `CMS_Controller`, `CMS_Priv_Strict_Controller`, or `CI_Controller` and should has the same name as the file.
Since your file name is `pokemon.php`, your controller class name should be `Pokemon`.
In the `Pokemon` Controller, you have a function called `show` that return a bunch of html.

Now open up your browser and access this page: `http://localhost/No-CMS_directory/new_module/pokemon/show`.
You will see a page contains some pokemons.

Notice that since No-CMS is based on CodeIgniter, the url doesn't imply directory structure.
Basically, this is the general rule:
````
    http://server:port/No-CMS_directory/module_name/controller_name/function_name/parameter_1/parameter_2
````
Please take a look at CodeIgniter & HMVC user guide and tutorials if you are not familiar with this.

Make a view
-----------
It is better to separate your presentation from your controller. Therefore we should have a view.

Now edit your `/modules/new_module/controllers/pokemon.php` into this:
```php
    <?php
    class Pokemon extends CMS_Controller{

        function show(){
            $pokemon_list = array('pikachu',
                'bulbasur', 'charmender',
                'squirtle', 'caterpie',
                'articuno', 'ekans', 'koffing'
            );
            $data['pokemon_list'] = $pokemon_list;
            $data['name'] = 'goFrendi';
            $this->view('new_module/pokemon_index',
                $data, 'main_index');
        }

    }
```

And modify your `/modules/new_module/views/pokemon_index.php` into this:
```php
    <?php echo "Welcome, ".$name; ?>
    <ul>
        <?php
        foreach($pokemon_list as $pokemon){
            echo '<li>'.$pokemon.'</li>';
        }
        ?>
    </ul>
```

The controller is now handling `$data` to `new_module/views/pokemon_index` and show the page with `main_index` privilege.
In `pokemon_index.php`, every key on `$data` are become new variables (in this case `$data['name']` become `$name` and `$data['pokemon_list']` become `$pokemon_list`).

A bit more about view
---------------------
* To load a view by using controller, and parse some data on it, you can write:

```php
    $this->view('view_name', $data);
```

* To load a view by using controller, and make sure that only users with certain navigation can see it, you can write:

```php
    $this->view('view_name', $data, 'navigation_code_required');
```

* To load a view by using controller, and make sure that only users with certain navigation & privileges can see it, and use custom title and keyword, you can write:

```php
    $config = array(
        'privileges' => array('priv_1', 'priv_2'),
        'title' => 'page_title',
        'keyword' => 'home page, No-CMS, cool',
    );
    $this->view('view_name', $data, 'navigation_code_required', $config);
```

* If you want to have the result returned as variable instead of written to output buffer, you can add 5th parameter:

```php
    $config = array(
        'privileges' => array('priv_1', 'priv_2'),
        'title' => 'page_title',
        'keyword' => 'home page, No-CMS, cool',
    );
    $result = $this->view('view_name', $data, 'navigation_code_required', $config, TRUE);
```

* In your view, you can also write some "magical" keywords, e.g:

```html
    <h3>{{ language:Welcome }} {{ user_name }}</h3>
    <p>Your current language setting is {{ language }}</p>
    {{ if_language:english }}
        <p>Nice to meet you, {{ user_real_name }}</p>
    {{ elif_language:japanese }}
        <p>始めまして, {{ user_real_name }}　さん</p>
    {{ elif_language:indonesian }}
        <p>Senang bertemu dengan anda, {{ user_real_name }}</p>
    {{ end_if }}
    <script type="text/javascript" src="{{ base_url }}/assets/nocms/js/jquery.js"></script>
```

* Here is the list of those magical keywords (or also known as tag). Read the documentation for complete list:

```
    {{ user_id }}
    {{ user_name }}
    {{ user_real_name }}
    {{ user_email }}
    {{ site_url }}
    {{ base_url }}
    {{ module_path }}
    {{ widget_slug:slug }}
    {{ widget_name:widget_name }}
    {{ language }}
    {{ language:some_word }}

    {{ if_language:a_language }}
       something that will be appeared for a language
    {{ elif_language:another_language }}
        something that will be appeared for another language
    {{ else }}
        something that will be appeared for another case
    {{ end_if }}
```


Make a model
------------

Right now, we have already learn about controller and view. How about model?
Model is the heart of your application. It should define what your module can do. In CodeIgniter, this is usually violated. Some user put logic on controller. It is okay, but not the best practice.
Let's say you have a pokemon table in No-CMS database:
```sql
    CREATE TABLE `pokemons` (
      `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
      `name` varchar(45) DEFAULT NULL,
      `description` text,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    INSERT INTO `pokemons`(`name`) VALUES
        ('pikachu'),('bulbasur'),('squirtle'),('charmender'),('caterpie'),
        ('ekans'),('koffing'),('magnemite'),('articuno'),('ivy');
```
You want to show list of pokemons based on the table content.

Now edit your `/modules/new_module/models/pokemon_model.php` into this:
```php
    <?php
    class Pokemon_Model extends CMS_Controller{

        function get(){
            $query = $this->db->get('pokemons');
            // or you can use this too:
            //  $query = $this->db->query('SELECT * FROM pokemons')->get();
            $pokemon_list = array();
            foreach($query->row() as $row){
                $pokemon_list[] = $row->name;
            }
            return $pokemon_list;
        }

    }
```

Then edit your `/modules/new_module/controllers/pokemon.php` into this:
```php
    <?php
    class Pokemon extends CMS_Controller{

        function show(){
            $this->load->model('new_module/pokemon_model');
            $data['pokemon_list'] = $this->pokemon_model->get();
            $data['name'] = 'goFrendi';
            $this->view('new_module/pokemon_index',
                $data, 'main_index');
        }

    }
```

You can use the same model in many controllers. This will keep your application DRY (Don't repeat yourself).

__NOTE:__ Actually I do not like how CodeIgniter map `$this->load->model('new_module/pokemon_model')` into `$this->pokemon_model`.
This is both make IDE autocompletion doesn't work and make application less implicit. However you can use this code to keep autocompletion work with very small performance drawback:
```php
    $this->load->model('new_module/pokemon_model');
    $this->pokemon_model = new Pokemon_Model();
```
This way, you will have autocompletion and full controll of instance name.


Register the page and better authorization
------------------------------------------
So far we can access the page with directly access the url `http://localhost/No-CMS_directory/new_module/pokemon/show`.
Now, let's integrate it with No-CMS so that use can access it by clicking `Complete Menu | Pokemon List`.

Just simply make a page as in our previous tutorial:
* Login to No-CMS with your admin user.
* Open `Complete Menu | CMS Management | Navigation Management` or `CMS Management | Navigation Management`
* Add a new page by clicking `Add Navigation (Page)`
* Set `Navigation Code` into `pokemon_list`
* Set `Navigation Title` into `Pokemon List`
* Set `Page Title` into `Get these Pokemons`
* Set `Static` into `inactive`
* Set `url` into `new_module/pokemon/show`
* Set `Authorization` into `Everyone`
* Click `Save and Go Back to List`

After register the page, now change your controller a bit:

```php
    <?php
    class Pokemon extends CMS_Priv_Strict_Controller{

        function show(){
            $this->load->model('new_module/pokemon_model');
            $data['pokemon_list'] = $this->pokemon_model->get();
            $data['name'] = 'goFrendi';
            $this->view('new_module/pokemon_index',
                $data);
        }

    }
```

By using `CMS_Priv_Strict_Controller` you do not need to define `navigation_code` when calling `$this->view()`.

__Note:__ If you want your url to have the same privilege as other url in the same controller, you can override
```php
   <?php
   class Your_Controller_Name extends CMS_Priv_Strict_Controller{
        /*
         * URL_MAP will be used in case of you have "unregistered function"
         * (e.g : you don't have any navigation name that refer to
         * 'your_module_name/your_controller_name/unregistered_function',
         * but you want to make sure that the url has the same authorization as 'a_navigation_name')
         */
        protected function do_override_url_map($URL_MAP){
           $URL_MAP['your_module_name/your_controller_name/unregistered_function'] = 'a_navigation_name';
           return $URL_MAP;
        }

        /*
         * This is the normal way. You can access below function by using this url:
         * http://your_domain.com/No-CMS_installation_folder/your_module_name/your_controller_name/show
         */
        public function show(){
            $this->load->model('your_model_name');
            $data = array();
            $data['result'] = $this->your_model_name->get_data();
            $this->view('your_view_name', $data);
        }

        /*
         * This is gonna be work to, even if the url is not registered.
         * You can access below function by using this url:
         * http://your_domain.com/No-CMS_installation_folder/your_module_name/your_controller_name/strict
         */
        public function unregistered_function(){
            $this->load->model('your_model_name'); // this will not be run if visitor not authorized
            $data = array();
            $data['result'] = $this->your_model_name->get_data();
            $this->view('your_view_name', $data);
        }
   }
   ?>
```


Make module installable
-----------------------
Make installable module is basically can be done by making automation script of page registration.
Modify your `new_module/controllers/install.php` into this:
```php
    <?php
    class Install extends CMS_Module_Installer {
        /////////////////////////////////////////////////////////////////////////////
        // Default Variables
        /////////////////////////////////////////////////////////////////////////////

        protected $DEPENDENCIES = array();
        protected $NAME         = 'your_name.new_module'; // namespace of your module
        protected $DESCRIPTION  = 'New Module based on tutorial to show pokemons';
        protected $VERSION      = '0.0.1';


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
            $this->backup_database(array(
                'pokemons',
            ));
            $this->remove_all();
        }

        // UPGRADE
        protected function do_upgrade($old_version){
            // Add your migration logic here.
        }

        /////////////////////////////////////////////////////////////////////////////
        // Private Functions
        /////////////////////////////////////////////////////////////////////////////

        // REMOVE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
        private function remove_all(){
            // remove navigations
            $this->remove_navigation('pokemon_list');
            // import uninstall.sql
            $this->db->query('DROP TABLE IF EXISTS `pokemons`');
        }

        // CREATE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
        private function build_all(){
            $module_path = $this->cms_module_path();

            // parent of all navigations
            $this->add_navigation('pokemon_list', 'Pokemon List',
                $module_path.'/pokemon/show', $this->PRIV_EVERYONE);


            $this->db->query('CREATE TABLE `pokemons` (
                  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
                  `name` varchar(45) DEFAULT NULL,
                  `description` text,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
        }

        // EXPORT DATABASE
        private function backup_database($table_names, $limit = 100){
            $module_path = $this->cms_module_path();

            $this->load->dbutil();
            $sql = '';

            // create DROP TABLE syntax
            for($i=count($table_names)-1; $i>=0; $i--){
                $table_name = $table_names[$i];
                $sql .= 'DROP TABLE IF EXISTS `'.$table_name.'`; '.PHP_EOL;
            }
            if($sql !='')$sql.= PHP_EOL;

            // create CREATE TABLE and INSERT syntax
            $prefs = array(
                    'tables'      => $table_names,
                    'ignore'      => array(),
                    'format'      => 'txt',
                    'filename'    => 'mybackup.sql',
                    'add_drop'    => FALSE,
                    'add_insert'  => TRUE,
                    'newline'     => PHP_EOL
                  );
            $sql.= $this->dbutil->backup($prefs);

            //write file
            $file_name = 'backup_'.date('Y-m-d_G:i:s').'.sql';
            file_put_contents(
                    BASEPATH.'../modules/'.$module_path.'/assets/db/'.$file_name,
                    $sql
                );

        }
    }
```

Now, you can go to `CMS Management | Module Management` and activate/deactivate new_module


Tutorial 05: Module Generator (Nordrassil)
==========================================

* Go to `CMS Management | Module Generator`
* Make a new project. (You can make a project based on database)
* Edit tables and columns of your project
* Click generate, and it is.

Contributing
============

It is my honor to accepts contributions of code and documentation from you.
These contributions are made in the form
of Issues or [Pull Requests](http://help.github.com/send-pull-requests/) on
the [No-CMS repository](https://github.com/goFrendiAsgard/No-CMS/) on GitHub.

Issues are a quick way to point out a bug. If you find a bug or documentation
error in No-CMS then please check a few things first:

- There is not already an open Issue
- The issue has already been fixed (check the develop branch, or look for
  closed Issues)
- Is it something really obvious that you fix it yourself?

Reporting issues is helpful but an even better approach is to send a Pull
Request, which is done by "Forking" the main repository and committing to your
own copy. This will require you to use the version control system called Git.
To use github, you should first read [Github help](http://help.github.com/)

Another simple way to contribute is by sending some donation
[![Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YDES6RTA9QJQL)


License
=======

GPL & MIT License


Acknowledgement
===============

I would like to thank all the contributors to the No-CMS project and you, the No-CMS user.
Here are some names of considerable contributors:

* goFrendiAsgard <-- (It is me) Make No-CMS based on CodeIgniter and some existing plug-ins.
* EllisLab <-- Make CodeIgniter and make it available for free. There is no No-CMS without codeIgniter
* wiredesignz <-- Creator of HMVC plugin.
  The plug-in he made is known widely among CodeIgniter developer.
  It allowed me to make separation between modules
* Phil Sturgeon <-- Creator CodeIgniter-template-library.
  The plugin he made allowed me to make separation between themes elements
  He is a member of CodeIgniter Reactor Engineer. His pyro-CMS also inspire me a lot (although I take different approach)
* John Skoumbourdis <-- Creator of groceryCRUD.
  It boost the development of No-CMS by provide very easy CRUD.
  He also give me some moral support to continue the development of No-CMS.
* Zusana Pudyastuti <-- She was my English Lecturer, A very good one who encourage me to speak English.
  It is a miracle for me to write this section in English :D
* Mukhlies Amien <-- He is one of my best friends. In this project, his role is advisor and tester.
* Gembong Edhi Setiawan <-- He is also one of my best friends. He gives some support and feature requests.
* Wahyu Eka Putra <-- He was my student. One of some best students in my class.
  He is the first one who discover a critical bug in the first stage of development.
* I Komang Ari Mogi <-- He is my classmate in my graduate program. He has some experience in design.
  That is why he can propose some fix in the very early stage of development.
* Ibnoe <-- The one who gives some suggestions and bug report.
* Panega <-- The one who also report a crucial bug.
* Alexandre Mota <-- The one who report a bug related to page authorization
* Gusro <-- Find out bug related to static page. Since his report, static page has 2 versions. A dynamic page which is overwritten by static content, or pure static page without any View URL needed
* Gangsar Swapurba <-- Discover some missleading-behavior. He made a lot of modification and found trivial but disgusting bugs of No-CMS. One of his report make me consider to provide an option to hide index.php
* alwin4711 <-- German translation contributor
* David Moeljadi <-- Japanese translation contributor
* Andrew Podner <-- His one day hardwork solve problem of $this scope in anonymous function
* David Oster <-- Greek translation contributor
* Glenn Bennett <-- Kindly provide free hosting for http://www.getnocms.com
* Abu Tuffah Bayashoot <-- Find bug on configuration management at v0.6.1, and propose solution
* Ann Low <-- Spain translation contributor
* Everyone who was involved by creating issue & pull requests in github. I cannot write every names there. But No-CMS can't be better without them :)


Changelog and New Features
==========================

- For more detail information, please take look at [github commit log](https://github.com/goFrendiAsgard/No-CMS/commits)

v0.5.0
+ (done, tested) add backend template as suggested by mbuurman at http://codeigniter.com/forums/viewthread/209171/P10/
+ (done, tested) fix user management bug as reported by panega at https://github.com/goFrendiAsgard/No-CMS/issues/6?_nid=28877585
+ (done, tested) costumizable Site Logo
+ (done, tested) costumizable Language

v0.5.1
+ (done, tested) make .htaccess automatically to hide index.php
+ (done, tested) finishing WYSIWIG (navigation language quicklink, widget)
+ (in progress) documentation
+ (cancelled) Fully using AR, so that we can support more than just MySQL (inspired by django)
+ (done, tested) Add "module_name" function in CMS_Module_Installer
+ (done, tested) Change all hardcoded URL in modules & installation by using module_name, so that the modules will be more portable
+ (cancelled) Change "install" into a module, and use db_forge instead of hardcode-sql
+ (done, tested) bootstrap integration and new responsive theme
+ (done, tested) use CI 2.1.2, HMVC 5.4, Phil Sturgeon template 1.9, groceryCRUD 1.2.3

v0.5.5
+ (done, tested) add scrollbar
+ (done, tested) flexigrid should also be responsive
+ (done, tested) use "slow slidetoggle" in help and bootstrap theme
+ (done, tested) use bootstrap for installation
+ (done, tested) use database for help module
+ (done, tested) upload new module feature
+ (done, tested) upload new theme feature
+ (done, tested) use 'title' for blog url
+ (cancelled) change default controller programmatically and use it on $this->view
+ (done, tested) add how to change default_controller instructions
+ (done, tested) module generated by module generator should backup all needed database everytime uninstalled
+ (done, tested) put main controllers and views in module directory
+ (done, tested) use grocery-CRUD 1.3 stable

v0.6.0
+ (done, tested) add "toggle" navigation feature on table view (also will be applied for widget etc)
+ (done, tested) wysiwyg error on IE
+ (done, tested) ensure installer also works in xampp (htaccess issue)
+ (done, tested) more simple widget
+ (cancelled) asset management library using head.js
+ (done, tested) asset management library using jsmin
+ (done, tested) using MX_Controller as CMS_Controller base class
+ (cancelled) master-detail by using grocery-crud
+ (done, tested) add only_content in navigation management
+ (done, tested) preconditional check for cms_show_json_encode()
+ (done, tested) use UTF 8 as default collation
+ (done, tested) repairing WYSIWYG upload for favicon and logo
+ (done, tested) add "fake" library to make autocompletion work (as suggested by Skombourdis here http://www.web-and-development.com/codeigniter-and-eclipse-autocomplete/)
+ (done, tested) use CodeIgniter 2.1.3
+ (done, tested) make navigation page more interactive when editing static content
+ (done, tested) repair login and logout widget since {{ site_url }} is already add trailing slash automatically
+ (done, tested) wysiwyg upload limit problem
+ (done, tested) make widget order works
+ (done, tested) make dynamic widget works properly
+ (done, tested) add per-session language setting
+ (done, tested) better language handling
+ (done, tested) fix infinite-recursion bugs as reported by Joseph Marikle, by adding "raw" parameter in cms_get_config
+ (done, tested) add "sign-up" email notification setting
+ (done, tested) more complete keyword, such as {{ activation_code }}, {{ real_name }}, {{ if_language:indonesia}} ... {{ end_if_language }}
+ (done, tested) keyword is now also works for dynamic pages, except for value property and textarea
+ (done, tested) use groceryCRUD 1.3.3 stable
+ (done, tested) use HMVC commit 868e975
+ (done, tested) use Module::run instead of AJAX to show widget
+ (done, tested) facebook, twitter and open ID login
+ (done, tested) avoid SQL injection on login. Damn, just know about it. Thank you for Idris Sardi
+ (done, tested) fix active & inactive link on navigation management and widget management
+ (done, tested) fix theme appearance for "inactive page", Thank you for Sugeng Widodo
+ (done, tested) add theme setting for each page
+ (done, tested) multi slug for widget
+ (done, tested) change view mechanism
+ (done, tested) per page keyword and per page title

v0.6.1
+ (done, tested) Include JQuery by default,
+ (cancelled) use jquerytools CDN if possible <-- using file_get_content or CURL is very slow
+ (done, tested) add google analytic configuration
+ (done, tested) automatically create thumbnail for blog's photos
+ (done, tested) add `cms_guard_page` function for better authorization
+ (done, tested) create thumbnail automatically for blog module's photo
+ (done, tested) add "admin controller" like bonfire and other CI Based framework
+ (done, tested) delete the legacy old_gray theme
+ (done, tested) Rename CMS_Controller into MY_Controller, put CMS_Model in MY_Model
+ (done, tested) Add widget and navigation tag
+ (done, tested) Update module scenario
+ (done, tested) add cms & module prefix setting
+ (done, tested) Make nordrassil generated code extends CMS_Priv_Controller
+ (done, tested) Create default setting for nordrassil generated code
+ (done, tested) Recode all module to fullfill No-CMS new standard (table prefix & module prefix)
+ (done, tested) Fix nordrassil bug when table prefix is empty

v0.6.2
+ (cancelled) Drupal's CCK like mechanism <-- It is impossible to build such a feature with groceryCRUD
+ (done, tested) `{{ widget:slug }}`, `{{ quicklink }}`, `{{ navigation_top }}`, `{{ navigation_top_quicklink }}`, and `{{ navigation_left }}` tag is deprecated.
+ (done, tested) `$cms` is deprecated, and can be fully replaced by using tags.
+ (done, tested) `{{ widget_name:widget_code }}` and `{{ widget_slug:slug }}`
+ (done, tested) quicklink, navigation_top, and navigation_left are now widgets and can be called as needed by using tag.
+ (done, tested) fix Nordrassil generation bug
+ (done, tested) static page
+ (done, tested) fix nordrassil generated code on insert without goback to list
+ (done, tested) fix invalid `{{ module_path }}` tag
+ (done, tested) fix theme bug
+ (done, tested) fix AJAX delete feature on blog module
+ (done, tested) add donation button
+ (done, tested) bugfix: immediately apply language changes when user change language without login

v0.6.3
+ (proposed) automatically create thumbnail in wysiwyg, use better uploader library