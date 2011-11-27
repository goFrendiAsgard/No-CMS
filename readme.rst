###################
WHAT IS?
###################
Neo-CMS = CodeIgniter + HMVC + groceryCRUD + Phil Sturgeon's Template + My Own Logics
Neo-CMS already has perfect working user authorization and authentication.
Thus, you can focus only on your logic

Neo-CMS is CodeIgniter. This means that since you didn't install it, Neo-CMS is only CodeIgniter with some plugins added.

Once you've install Neo-CMS, you can still make any code just as in CodeIgniter.
Any code worked in CodeIgniter will also worked in Neo-CMS

###################
MODULES
###################
You can make your own modules in modules directory

In CodeIgniter, your controller will extends CI_Controller:
    class MyController extends CI_Controller{}

In Neo-CMS, you can still do the same, but it is recommended that your controller extends CMS_Controller:
    class MyController extends CMS_Controller{}

In CodeIgniter, you might load a view by using:
    $this->load->view('some_view');

In Neo-CMS, you can still do the same, but it is recommended to use this way:
    $this->view('some_view');
If you want to pass some data, you can use:
    $this->view('some_view', $data);

For Authorization sake, you can also add navigation and privilege parameter:
    $this->view('some_view', $data, 'main_user', 'manage_cms');
That would means: Only user who can see main_user page and have manage_cms privilege will see some_view,
another user will see 404 not found

If you want to use grocery_crud feature, you can load view:
    $this->view('grocery_CRUD', $output);

###################
LAYOUT
###################
You can make your own template in template directory (please refer to default theme)

For your layout setting (header, slogan, etc) you can edit
application/config/cms.php <-- promise me you won't break anything :D

###################
CONTRIBUTORS
###################
* goFrendiAsgard <-- The one who make Neo-CMS based on already exists plugins, that's me :D
* EllisLab <-- A company who make codeIgniter and make it available for free. There is no Neo-CMS without codeIgniter
* WanWizard <-- The one who make HMVC plugin. The plugin he made allowed me to make separation between modules
* Phil Sturgeon <-- The one who make Phil Sturgeon's template. The plugin he made allowed me to make separation between layouts
* John Skoumbourdis <-- The one who make groceryCRUD. It boost the development of Neo-CMS by provide very easy CRUD
* Wahyu Eka Putra <-- The one who reports bug(s)

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
* Forgot password (You should edit application/config/cms.php and application/config/email.php)

###################
FUTURE FEATURES
###################
* Widget and Widget Management
* Readmore in blog module
* Automatically read module name without define $module_name in module/module_name/install.php

#####################
BUGS AND KNOWN ISSUES
#####################
* core/CMS_Module_Installer.php line 112 undefined variable userid [Reported by: Wahyu Eka Putra, 2011-11-19, status: repaired, 2011-11-20]
* Recursive navigation menu can bring to a problem (not really, but yeah I fixed it) [Reported by: goFrendiAsgard, 2011-11-19, status: repaired, 2011-11-20]
* Trigger not created [Reported by: goFrendiAsgard, 2011-11-20, status: repaired, 2011-11-20]
* The installation progress can be cheated by point to http://localhost/Neo-CMS/install.php directly [Reported by: goFrendiAsgard, 2011-11-19, status: repaired, 2011-11-20]
* The configuration files should be writeable, but installation progress doesn't check this [Reported by: goFrendiAsgard, 2011-11-19, status : repaired, 2011-11-20]

* Admin group can be deleted [Reported by: goFrendiAsgard, 2011-11-19, status : fixed, but need to change error messages]
* The super user can also be deleted [Reported by: goFrendiAsgard, 2011-11-19, status : fixed, but need to change error messages]

* The super user can be deactivate [Reported by: goFrendiAsgard, 2011-11-20]
* Need grammatical check since I'm not a native english speaker :D [Reported by: goFrendiAsgard, 2011-11-19]



goFrendiAsgard(c) 2011
My Own logics are under GNU license
CodeIgniter, HMVC, groceryCRUD, Phil Sturgeon's template are under their own licenses