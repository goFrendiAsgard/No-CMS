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

- Please look at [github tag](https://github.com/goFrendiAsgard/No-CMS/tags)


Changelog and New Features
==========================

- Please look at [github commit log](https://github.com/goFrendiAsgard/No-CMS/commits)


Server Requirements
===================

-  PHP version 5.3.2 or newer.


Documentation
=============

The full documentation and developer guide is still under progress and can be found once you have install No-CMS

Installation
------------

* Download No-CMS from [No-CMS repository](https://github.com/goFrendiAsgard/No-CMS) on GitHub
* Copy and extract it on your web server directory (You might want to try it locally via xampp, in this case, your server directory is c:\xampp\htdocs)
* Access the url (If you use xampp in your local computer, the url should be http://localhost/No-CMS)
* Click "Install Now"
* Fill any information needed (including your administrator password)

CMS Management
--------------

* To manage your CMS you should first login.
* Open CMS Management, there are a lot of things you can do
* Navigation management can be used to manage menu
* User, group, and privilege management are used to manage authorization
* You can change the theme via Change Theme
* You can install new module via Module Management
* You can manage your widgets via Widget Management
* The most important (and a bit dangerous) is Configuration Management. Here you can
  change the site header, slogan, and copyright. Some configuration should be handled with care.
  A mistake to configure this part can make your web-site inaccessible

Developer Guide
===============

For CodeIgniter developer who want to use No-CMS for their project, developer guide is for you

Module
------
* Your module must be located at modules directory (your_no_cms_installation_path/modules)
* Your module can have an "installer controller" to make it install-able
* Your module must be consist of at least 3 subdirectories (models, views, and controllers)
* If you are not familiar with CodeIgniter MVC pattern, you should read CodeIgniter documentation first

Controller
----------
* Controllers deal with every process in your module
* Controllers must be located at your_no_cms_installation_path/modules/your_module_name/controllers
* Every controller musth contains a class which extends CMS_Controller:

```php
   <?php
    class Your_Controller_Name extends CMS_Controller{
    	// You can access below function by using this url: 
    	// http://your_domain.com/No-CMS_installation_folder/your_module_name/your_controller_name/show
    	public function show(){
   			$this->load->model('your_model_name');
   			$data = array();
   			$data['result'] = $this->your_model_name->get_data();
   			$this->view('your_view_name', $data, 'navigation_name');
   		}
    }
   ?>
```

Installer Controller
--------------------
* Installer controller must be located at your_no_cms_installation_path/modules/your_module_name/controllers
* Installer controller must be named "Install.php"
* Installer controller must extends "CMS_Module_Installer"
* You should provide do_install() and do_uninstall() method to make it fully work

```php
   <?php
   class Install extends CMS_Module_Installer {
   		// in order to install this module, a user should install prerequisites modules first
		protected $DEPENDENCIES = array('prerequisites_module_1', 'prerequisites_module_2');
		// the module name space, please ensure this is unique for each module, adding your name as the first part is always a good idea
		protected $NAME = 'your_name.your_module_name';
		
		// WHEN USER INSTALL THIS MODULE, THIS WILL BE EXECUTED
		protected function do_install(){
			// add a new navigation point to module_name/controller_name/function_name that can only be accessed by authorized user       
	        $this->add_navigation("navigation_name", "navigation_title", "module_name/controller_name/function_name", $this->PRIV_AUTHORIZED);        
	        // add quicklink of that navigation (optional)
	        $this->add_quicklink("navigation_name");
	        // add widget that can be accessed by everyone		
			$this->add_widget("widget_name", "widget_title", $this->PRIV_EVERYONE, "module_name/other_controller_name/function_name", "sidebar");
		}
		
		// WHEN USER UNINSTALL THIS MODULE, THIS WILL BE EXECUTED
		protected function do_uninstall(){
			// remove the quicklink
			$this->remove_quicklink("navigation_name");
			// remove the navigation
	        $this->remove_navigation("navigation_name");
	        // remove the widget
			$this->remove_widget("widget_name");	        
		}
   }
   ?>
```

Model
-----
* Models deal with every data in your module
* Models must be located at your_no_cms_installation_path/modules/your_module_name/models
* Every model musth contains a class which extends CMS_Model:

```php
   <?php
   class Your_Model_Name extends CMS_Model{
   		// Get some data or whatever ...
   		public function get_data(){
   			$query = $this->db->get('table_name');
   			return $query->result();
   		}
   }
   ?>
```

Views
-----
* Views deal with every output in your module
* Views must be located at your_no_cms_installation_path/modules/your_module_name/views
* Every view must be php file
* To load a view by using controller, you can write:

```php
     $this->view('view_name');
```

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

License
=======

GPL & MIT License


Acknowledgement
===============

I would like to thank all the contributors to the No-CMS project and you, the No-CMS user.
Here are some names of considerable contributors:

* goFrendiAsgard <-- It's me, I am the one who make No-CMS based on CodeIgniter and some existing plug-ins.
* EllisLab <-- A company who make codeIgniter and make it available for free. 
  There is no No-CMS without codeIgniter
* wiredesignz <-- He is the one who make HMVC plugin. 
  The plug-in he made is known widely among CodeIgniter developer. 
  It allowed me to make separation between modules
* Phil Sturgeon <-- He is the one who make CodeIgniter-template. 
  The plugin he made allowed me to make separation between themes elements
  He is a member of CodeIgniter Reactor Engineer. His pyro-CMS also inspire me a lot (although I take different approach)   
* John Skoumbourdis <-- He is the one who make groceryCRUD. 
  It boost the development of No-CMS by provide very easy CRUD. 
  He also give me some moral support to continue the development of No-CMS.
* Zusana Pudyastuti <-- She was my English Lecturer, A very good one who encourage me to speak English.
  It is a miracle for me to write this section in English :D
* Mukhlies Amien <-- He is one of my best friends. In this project, his role is advisor and tester.
* Gembong Edhi Setiawan <-- He is also one of my best friends. He gives some support and feature requests.
* Wahyu Eka Putra <-- He was my student. One of some best students in my class. 
  He is the first one who discover a critical bug in the first stage of development.
* I Komang Ari Mogi <-- He is my classmate in my graduate program. He has some experience in design. 
  That's why he can propose some fix in the very early stage of development. 
* Ibnoe <-- The one who gives some suggestions and bug report. I don't know much about him
* Panega <-- The one who also report a crucial bug. I don't know much about him
* Alexandre Mota <-- The one who report a bug related to page authorization
* Gusro <-- Find out bug related to static page. Since his report, static page has 2 versions. A dynamic page which is overwritten by static content, or pure static page without any View URL needed
* Gangsar Swapurba <-- Discover some missleading-behavior. He made a lot of modification and found trivial but disgusting bugs of No-CMS. One of his report make me consider to provide an option to hide index.php
* alwin4711 <-- German translation contributor
* David Moeljadi <-- Japanese translation contributor
* Andrew Podner <-- His one day hardwork solve problem of $this scope in anonymous function
* Everyone who was involved by creating issue & pull requests in github. I can't write every names there. But No-CMS can't be better without them :)

Roadmap
===============

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

v0.6.1 (development)
+ (proposed) Drupal's CCK like mechanism
+ (proposed) Make nordrassil generated code easier to be edited.