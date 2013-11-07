[Up](../tutorial.md)

Installation
============
Installation is a process to install No-CMS into server, so that it become accessible by visitors (or at least by yourself). There are several things done in installation process:

* Create tables on database server
* Adjusting Configuration file's contents
* Creating .htaccess files.

If you don't understand any of the above, just relax, No-CMS installation wizard will do everything for you.
For more detail explanation about installation, please click [here](user_installation.md)

Update & Migration
==================
* Update is a process to get the newest version of No-CMS without breaking your current data and settings.
* Migration is a process to migrate No-CMS into another server (e.g: from your local computer to your hosting server)

Update & Migration require a bit work. It is basically easy, just make sure to be careful so that you do not break things.
For more detail explanation about update and migration, please click [here](user_update_and_migration.md)

Themes
======
Themes are just a visual matter. You can choose a suitable theme for your website. No-CMS come with several themes to choose. If you cannot find any suitable theme for you, please consider to hire a profesional designer, or make a theme by yourself (require some knowledge of CSS and basic HTML).
For more detail explanation about installation, please click [here](user_themes.md). If you are a web designer, you might also like to visit [themes guide for designer](designer_themes.md).

Layout Management
=================
With layout management, you can put correct widget in the correct position. You can also change logo, favicon, site title and jargon using layout management.
For more detail explanation about layout management, please click [here](user_layout.md).

Widget
======
Widget is a partial part that can be attached to your pages. Top navigation is an example of a widget.
For more detail explanation about widget, please click [here](user_widget.md).

Configuration
=============
Bunch of dynamic values that can be set to change the behavior of No-CMS.
For more detail explanation about configuration, please click [here](user_configuration.md).

Authorization
==============
Unlike other CMS there is no such a specific “control-panel” or “admin-dashboard” in No-CMS, You
have a full control about “who can do what”.
The authorization is used to ensure that only certain users can access some pages.
No-CMS provide 4 authorization types. This is already covers everything.
* __Everyone__
    The most common authorization. Every page/widget with “Everyone” authorization can be viewed by
    all visitor, regardless of their login information. Some pages such a home-page or blog that can
    be viewed by all visitor should have “Everyone” authorization type.
* __Authenticated__
    Once a user logged in into the system, he/she will be authenticated. Every page/widget with
    “Authenticated” authorization can only be viewed by logged-in user. Some pages that require
    login (e.g: change profile) should have this type of authorization type
* __Unauthenticated__
    Before a user logged in into the system (or once he/she log-out), he/she will be unauthenticated.
    This type of authorization is rarely used. Some pages/widgets that usually require such a authorization
    types are “forgort-password” and “register”.
* __Authorized__
    This is the most strict authorization type. Only user that has logged-in and already registered in
    certain user-group can access it. Some crucial pages such a “CMS-Management page” should
    has such a privilege.

__PS :__ if a user is a member of "Super Admin" user-group, he/she will always be authorized to view any page.

User And Group
==============
In No-CMS any authenticated user should has a user account. The user account contains username and password. A user account can be a part of several user-group, and vice-versa, a user-group can contains several user-account. Authorization is based on user group.
For more detail explanation about user and group, please click [here](user_and_group.md).

Modules
=======
Modules are the biggest power of No-CMS. Imagine it as wordpress plugin. Every module can contains several pages or widgets. This will surely enrich your website to unlimited potential.
For more detail explanation about module, please click [here](user_modules.md). If you are a programmer, you might also want to make your own module. Click [here](programmer_modules.md) to understand the structure of a module, and click [here](programmer_module_generator) to learn how to use module generator and make your life easier.