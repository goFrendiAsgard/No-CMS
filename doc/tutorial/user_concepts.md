[Up](../tutorial.md)

Installation
============
Installation is a process to install No-CMS into server, so that it become accessible by visitors (or at least by yourself). There are several things done in installation process:

* Create tables on database server
* Adjusting Configuration file's contents
* Creating .htaccess files.

If you don't understand any of the above, just relax, No-CMS installation wizard will do everything for you.

Update & Migration
==================
Update is a process to get the newest version of No-CMS without breaking your current data and settings.

Migration is a process to migrate No-CMS into another server (e.g: from your local computer to your hosting server)

Update & Migration require a bit work. It is basically easy, just make sure to be careful so that you do not break things.

Themes
======
Themes are just a visual matter. You can choose a suitable theme for your website. No-CMS come with several themes to choose. If you cannot find any suitable theme for you, please consider to hire a profesional designer, or make a theme by yourself (require some knowledge of CSS and basic HTML).

Layout Management
=================
With layout management, you can put correct widget in the correct position. You can also change several images

Widget
======
Widget is a partial part that can be attached to your pages. Top navigation is an example of a widget

Configuration
=============
Bunch of dynamic values that can be set to change the behavior of No-CMS.

Authorization
==============
Unlike other CMS there is no such a specific “control-panel” or “admin-dashboard” in No-CMS, You
have a full control about “who can do what”.
The authorization is used to ensure that only certain users can access some pages.
No-CMS provide 4 authorization types. This is already covers everything.
* __Everyone__
    The most common authorization. Every page with “Everyone” authorization can be viewed by
    all visitor, regardless of their login information. Some pages such a home-page or blog that can
    be viewed by all visitor should have “Everyone” authorization type.
* __Authenticated__
    Once a user logged in into the system, he/she will be authenticated. Every page with
    “Authenticated” authorization can only be viewed by logged-in user. Some pages that require
    login (e.g: change profile) should have this type of authorization type
* __Unauthenticated__
    Before a user logged in into the system (or once he/she log-out), he/she will be unauthenticated.
    This type of authorization is rarely used. Some pages that usually require such a authorization
    types are “forgort-password” and “register”.
* __Authorized__
This is the most strict authorization type. Only user that has logged-in and already registered in
certain user-group can access it. Some crucial pages such a “CMS-Management page” should
has such a privilege.

__PS :__ if a user is a member of "Super Admin" user-group, he/she will always be authorized to view any page.

User And Group
==============
In No-CMS any authenticated user should has a user account. The user account contains username and password. A user account can be a part of several user-group, and vice-versa, a user-group can contains several user-account. Authorization is based on user group.