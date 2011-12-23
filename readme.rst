###################
WHAT IS?
###################
Neo-CMS = CodeIgniter + HMVC + groceryCRUD + Phil Sturgeon's Template + My Own Logics.
Neo-CMS already has perfect working user authorization and authentication.
Thus, you can just focus on your business process.

Neo-CMS is CodeIgniter. 
=======================
This means that since you didn't install it, Neo-CMS is only CodeIgniter with some additional plugins.
Any code worked in CodeIgniter will also worked in Neo-CMS

Neo-CMS is modular
=======================
You can make your own module, your own widget, and you can extend Neo-CMS to be your own CMS.
However your module should obey CodeIgniter CMS pattern

###################
DOCUMENTATION
###################
You can read the full Neo-CMS documentation after install it.
Here I will give you some overview: 

Module
======

* Your module must be located at modules directory (your_neo_cms_installation_path/modules)
* Your module must have an "installer controller" to make it install-able
* Your module must be consist of at least 3 subdirectories (models, views, and controllers)
* If you are not familiar with CodeIgniter MVC pattern, you should read CodeIgniter documentation first

Controller
==========
* Controllers deal with every process in your module
* Controllers must be located at your_neo_cms_installation_path/modules/your_module_name/controllers
* Main controller must have the same name as your_module_name (your_module_name.php)
* Every controller musth contains a class which extends CMS_Controller:
    class Your_Module_Name extends CMS_Controller{Your logic goes here.....}

Models
==========
* Models deal with every data in your module
* Models must be located at your_neo_cms_installation_path/modules/your_module_name/models
* Every model musth contains a class which extends CMS_Model:
    class Your_Model_Name extends CMS_Model{//Your logic goes here.....}

Views
==========
* Views deal with every output in your module
* Views must be located at your_neo_cms_installation_path/modules/your_module_name/views
* Every view must be php file
* To load a view by using controller, you can write:
    $this->view('view_name');
* To load a view by using controller, and parse some data on it, you can write:
    $this->view('view_name', $data);
* To load a view by using controller, and make sure that only users with certain navigation can see it, you can write:
    $this->view('view_name', $data, 'navigation_code_required');
* To load a view by using controller, and make sure that only users with certain navigation & privileges can see it, you can write:
    $this->view('view_name', $data, 'navigation_code_required', array('privilege_1_required', 'privilege_2_required'));

Installer Controller
====================
* Installer controller must be located at your_neo_cms_installation_path/modules/your_module_name/controllers
* Installer controller must be named "Install.php"
* Installer controller must extends "CMS_Module_Installer"
* You should override do_install() and do_uninstall() to make it fully work

###################
CONTRIBUTORS
###################
* goFrendiAsgard <-- The one who make Neo-CMS based on already exists plugins, that's me :D
* EllisLab <-- A company who make codeIgniter and make it available for free. There is no Neo-CMS without codeIgniter
* wiredesignz <-- The one who make HMVC plugin. The plugin he made allowed me to make separation between modules
* Phil Sturgeon <-- The one who make Phil Sturgeon's template. The plugin he made allowed me to make separation between layouts
* John Skoumbourdis <-- The one who make groceryCRUD. It boost the development of Neo-CMS by provide very easy CRUD
* Wahyu Eka Putra <-- The one who reports bug(s)
* I Komang Ari Mogi <-- The one who proposed to use javascript for div layout_center's height
* Zusana Pudyastuti <-- The one who checked grammatical error in the documentation (not in this readme file)

###################
FEATURES LIST
###################
* Group Management
* User Management
* Privilege Management
* Navigation Management
* Module Management
* Integrated groceryCRUD
* Mobile and Desktop Layout
* Friendly installation
* Forgot password
* Readmore in blog module
* Widget and Widget Management
* Widget and module are the same.

###################
FUTURE FEATURES
###################
* Comment in blog module
* Module Generator
* Photo album module (included in blog module)
* E commerce module

###################
TODO
###################
* Use cms prefix for every public function inside CMS_Controller & CMS_Model
* Documentation
* Live Demo

#####################
BUGS AND KNOWN ISSUES
#####################
* core/CMS_Module_Installer.php line 112 undefined variable userid [Reported by: Wahyu Eka Putra, 2011-11-19, status: repaired, 2011-11-20]
* Recursive navigation menu can bring to a problem (not really, but yeah I fixed it) [Reported by: goFrendiAsgard, 2011-11-19, status: repaired, 2011-11-20]
* Trigger not created [Reported by: goFrendiAsgard, 2011-11-20, status: repaired, 2011-11-20]
* The installation progress can be cheated by point to http://localhost/Neo-CMS/install.php directly [Reported by: goFrendiAsgard, 2011-11-19, status: repaired, 2011-11-20]
* The configuration files should be writeable, but installation progress doesn't check this [Reported by: goFrendiAsgard, 2011-11-19, status : repaired, 2011-11-20]
* Not Automatically read module name without define $module_name in module/module_name/install.php [Reported by: goFrendiAsgard, 2011-11-27, status: repaired, 2011-11-27]
* Grocery-CRUD flexigrid theme have 960px by default, so it's not fit in a screen [Reported by: goFrendiAsgard, 2011-11-27, status: repaired, 2011-11-27]
* div layout_center's height fixed  [Reported by: goFrendiAsgard, 2011-11-30, fix Proposed by: Ari Mogi, status: repaired, 2011-11-30]
* widget with HTML and javascript doesn't viewed properly [Reported by: goFrendiAsgard, 2011-12-14, status: repaired, 2011-12-14] <-- This need CURL to be installed

* Admin group can be deleted [Reported by: goFrendiAsgard, 2011-11-19, status : fixed, but need to change error messages]
* The super user can also be deleted [Reported by: goFrendiAsgard, 2011-11-19, status : fixed, but need to change error messages]

* The super user can be deactivate [Reported by: goFrendiAsgard, 2011-11-20]
* Need grammatical check since I'm not a native english speaker :D [Reported by: goFrendiAsgard, 2011-11-19]



goFrendiAsgard(c) 2011,
My Own logics are under GNU license,
CodeIgniter, HMVC, groceryCRUD, Phil Sturgeon's template are under their own licenses
