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
No-CMS is a good enough CMS. It is different from Wordpress, Drupal or Joomla. Those CMS are built from developers for users. 
No-CMS is built by developer for developers, although everyone else can still use it as well.
The main purpose of this CMS is to provide a good start of web application project, especially for CodeIgniter developer.

No-CMS as Application development framework
--------------------------------------------
No-CMS is not just another CMS. No-CMS allows you to make your own module and your own themes.
This means that you (as developer) can make a module that can be used for several project.

No-CMS takes advantages of CodeIgniter as its core. 
It provides rich set of libraries for commonly needed task, 
as well as a simple interface and logical structure to access these libraries.
The main advantage of CodeIgniter is you can creatively focus on your project 
by minimizing the amount of code needed or a given task.

No-CMS is also take advantages of several popular plugins such as

* HMVC, to make fully modular separation
* Phil Sturgeon's Template, to make customizable themes
* groceryCRUD, to build CRUD application in a minute

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
* Your module must be located at modules directory (your_neo_cms_installation_path/modules)
* Your module must have an "installer controller" to make it install-able
* Your module must be consist of at least 3 subdirectories (models, views, and controllers)
* If you are not familiar with CodeIgniter MVC pattern, you should read CodeIgniter documentation first

Controller
----------
* Controllers deal with every process in your module
* Controllers must be located at your_neo_cms_installation_path/modules/your_module_name/controllers
* Main controller must have the same name as your_module_name (your_module_name.php)
* Every controller musth contains a class which extends CMS_Controller:

```html
   <?php
    class Your_Module_Name extends CMS_Controller{Your logic goes here.....}
   ?>
```

Installer Controller
--------------------
* Installer controller must be located at your_neo_cms_installation_path/modules/your_module_name/controllers
* Installer controller must be named "Install.php"
* Installer controller must extends "CMS_Module_Installer"
* You should provide do_install() and do_uninstall() method to make it fully work

Model
-----
* Models deal with every data in your module
* Models must be located at your_neo_cms_installation_path/modules/your_module_name/models
* Every model musth contains a class which extends CMS_Model:

```html
   <?php
   class Your_Model_Name extends CMS_Model{
     //Your logic goes here.....
   }
   ?>
```

Views
-----
* Views deal with every output in your module
* Views must be located at your_neo_cms_installation_path/modules/your_module_name/views
* Every view must be php file
* To load a view by using controller, you can write:

```html
   <?php
     $this->view('view_name');
   ?>
```

* To load a view by using controller, and parse some data on it, you can write:

```html
   <?php
    $this->view('view_name', $data);
   ?>
```

* To load a view by using controller, and make sure that only users with certain navigation can see it, you can write:

```html
   <?php
    $this->view('view_name', $data, 'navigation_code_required');
   ?>
```

* To load a view by using controller, and make sure that only users with certain navigation & privileges can see it, you can write:

```html
   <?php
    $this->view('view_name', $data, 'navigation_code_required', array('privilege_1_required', 'privilege_2_required'));
   ?>
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