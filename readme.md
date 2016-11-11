What is No-CMS?
================

No-CMS is a CMS-framework.

No-CMS is a basic and "less-assumption" CMS with some default features such as user authorization (including third party authentication),
menu, module and theme management.
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

Batteries Included
--------------------------------------------
No-CMS come with several batteries included:

* HMVC, to make fully modular separation
* groceryCRUD, to build CRUD application in a minute
* HybridAuth, to provide third party authentication (e.g: facebook, twitter, openID etc)
* Widget system
* Navigation system
* Module system
* Custom Theme system
* Custom language
* Tagging system


Release Information
===================

- v1.1.3, Nov, 11, 2016


Server Requirements
===================

- PHP version 5.3.2 or newer.
- Apache 2
- MySQL 5 or PostgreSQL 8.4 (depend on your database choice). Sqlite is still experimental
- php-curl extension
- mod-rewrite extension (optional)
- php-pdo or php-mysql extension (depend on your database choice)

License
=======

__GPL & MIT License__: In short, you can use No-CMS for whatever purpose, modify the code, and gain money by using it.

Links
=====
- [Change Logs](doc/changelog.md)
- [Contributor List](doc/contributors.md)
- [__User Guide And Tutorials__](doc/tutorial.md)
- [Official Website](http://www.getnocms.com)
- [Blog](http://www.getnocms.com/blog)
- [Facebook Page](http://facebook.com/nocms)
- [No-CMS Krabby Patty Recipe (you might not need this)](developer-note.md)


Contributing
============

I made No-CMS, but you can make it better. There are many way you can do to make No-CMS better:

- __Donate few amount of money:__ I make No-CMS freely available, but electricity, food, and internet access is not provided for free. I need them to keep alive so that I can continue the development of No-CMS. I also need to invest several time to develop, debug, and add features to No-CMS. If No-CMS help you to save your time and money, please consider to click this cute yellow button, and keep No-CMS's development: [![Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YDES6RTA9QJQL)

- __Bug report:__ I usually check and test my code carefully before publish it on github. However I am just a mere mortal, and can do mistakes. Whenever you see any unexpected behavior when using No-CMS, you can always open an issue on github, make a post on No-CMS forum, or email me directly. This will not only help you, but also help everyone using No-CMS for their projects (including me)

- __Code contribution:__ Sometime I make mistakes, and to make it worse, sometime I do not even know how to fix those mistakes (It is a rare case however). If you are also a PHP coder, and you know how to fix things or make things better, just submit an issue or [Pull Request](http://help.github.com/send-pull-requests/) on the [__development branch of No-CMS repository__](https://github.com/goFrendiAsgard/No-CMS/tree/development) on GitHub.

- __Improve user guide:__ I am not a native English speaker. Sometime I also find dificulty to explain things. If you think you can improve No-CMS's user guide, please have a visit [here](https://github.com/goFrendiAsgard/No-CMS/blob/development/doc/tutorial.md) and edit things.

- __Translation:__ I know several programming languages, but human natural languages is much more harder to learn. If you want your native language to be available in No-CMS, please do some translation and submit a pull request on github. However, if github scare you, do not worry. Just email me directly.

- __Tell your friends:__ If you think No-CMS is great, tell your friend. With more users & contributors, No-CMS will surely become better since there will be many people test it on different environments.

Post Installation
=================
In production server, please run `post-installation.sh` in order to set correct directory/file access permission to your files. This is important for security purpose.

Reset Installation
==================
To reset installation, please run this:

```bash
#!/bin/bash
sudo chmod 777 . -R
sudo rm -f ./application/config/.saved
sudo rm -f ./application/config/*.php
sudo echo "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');" > ./application/config/constants.php
sudo rm -Rf ./session
sudo rm -f ./application/logs/log*.php
sudo rm -f ./application/logs/hybridauth.log
sudo touch ./application/logs/hybridauth.log
sudo rm -f ./application/config/tmp/*.php
sudo rm -f ./assets/grocery_crud/texteditor/ckeditor/config.js
sudo rm -f ./assets/kcfinder/config.php
sudo rm -Rf ./assets/kcfinder/upload/main-*
sudo rm -Rf ./assets/kcfinder/upload/site-*
sudo rm -f ./.htaccess
sudo rm -f ./hostname.php
sudo rm -Rf ./application/config/site-*
sudo rm -Rf ./application/config/main
sudo rm -f ./modules/*/config/module_config_*.php
sudo rm -f modules/*/controllers/Info_*.php
sudo chmod 777 .
sudo chmod 755 * -R
sudo chmod 777 application/config -R
sudo chmod 777 application/logs -R
sudo chmod 755 assets/kcfinder -R
sudo chmod 777 assets/kcfinder
sudo chmod 777 assets/kcfinder/upload
sudo chmod 755 assets/kcfinder/upload/.htaccess
sudo chmod 755 assets/kcfinder/upload/index.html
sudo chmod 755 assets/grocery_crud/texteditor/ckeditor -R
sudo chmod 777 assets/grocery_crud/texteditor/ckeditor
sudo chmod 777 assets/uploads -R
sudo chmod 755 assets/uploads/index.html
sudo chmod 755 assets/uploads/.htaccess
sudo chmod 777 assets/nocms/images -R
sudo chmod 644 assets/nocms/images/*.png
sudo chmod 755 assets/nocms/images/*/.htaccess
sudo chmod 755 assets/nocms/images/*/index.html
sudo chmod 777 modules/*/assets/uploads -R
sudo chmod 755 modules/*/assets/uploads/.htaccess
sudo chmod 755 modules/*/assets/uploads/index.html
sudo chmod 644 modules/*/assets/uploads/*.jpg
sudo chmod 644 modules/*/assets/uploads/*.png
sudo chmod 755 modules/*/controllers -R
sudo chmod 777 modules/*/controllers
sudo chmod 777 modules/artificial_intelligence/assets/data -R
sudo chmod 666 modules/artificial_intelligence/assets/data/1_*_Default
```

The bash script will delete your configuration uploaded files and everything. Thus, you should do the installation from scractch. __warning__ : A new salt will be generated, thus your previous user-password will be unusable
