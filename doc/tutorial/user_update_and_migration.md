[Up](../tutorial.md)

Update from 1.0.0 to 1.0.x
==========================
If you have git installed, updating No-CMS to the newest version is going to be easy. As easy as you type:
```git fetch origin master```

If you don't use git, you can just download the newest version and safely overwrite everything except:

* `/application/config/` directory
* `/.htaccess`
* `/assets/kcfinder/config.php`
* `/assets/grocery_crud/texteditor/ckeditor/config.js`

Update from 0.7.5 to 1.0.0
==========================
No-CMS 1.0.0 use CodeIgniter 3.0.0 which is very different from CodeIgniter 2.x.x.

__PS:__ Before do the update, please backup everything.

First of all, delete contains of these folders:
* `/application/core`
* `/application/controllers`
* `/application/models`
* `/application/helpers`
* `/application/libraries`
* `/application/third_party`
* `/modules/main`
* `/modules/installer`

Every `controllers`, `models`, `helpers` and `libraries` on every default modules (blog, contact_us, static_accessories, multisite)

Then you should override these:

* `/system`
* `/assets`
* `/application`
* `/modules/main`
* `/modules/installer`
* Every default modules

Then, follow these steps:

* Create an empty file `/application/config/constants.php`
* Update your modules:
    - Use ucfirst for both class name and file name
    - Rename `CMS_Module_Info_Controller` into `CMS_Module`
    - Rename `CMS_Priv_Strict_Controller` into `CMS_Controller`
    - Every modules should contains `description.txt` and `controllers/Info.php` you can take `blog` module as your reference.
* Update `/assets/kcfinder/config.php`, modify the first lines into this (change `{{ FCPATH }}` with your current No-CMS installation directory's absolute path):

    ```php
        <?php

        /** This file is part of KCFinder project
          *
          *      @desc Base configuration file
          *   @package KCFinder
          *   @version 2.51
          *    @author Pavel Tzonkov <pavelc@users.sourceforge.net>
          * @copyright 2010, 2011 KCFinder Project
          *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
          *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
          *      @link http://kcfinder.sunhater.com
          */

        // IMPORTANT!!! Do not remove uncommented settings in this file even if
        // you are using session configuration.
        // See http://kcfinder.sunhater.com/install for setting descriptions

        $_FCPATH = '{{ FCPATH }}'; // if you use linux, you might change this into /var/www/. If you use windows, this might be C:\xampp\htdocs\

        // get helper & chipper to decode cookie
        if(!defined('BASEPATH')){ define('BASEPATH',''); }
        include($_FCPATH.'application/config/main/cms_config.php');
        if(array_key_exists('__cms_chipper', $config)){
            $chipper = $config['__cms_chipper'];
        }else{
            $chipper = 'Love Song Storm Gravity Tonight End of Sorrow Rosier';
        }
        require_once($_FCPATH.'application/helpers/cms_helper.php');

        // function to make things easier
        if(!function_exists('get_decoded_cookie')){
            function get_decoded_cookie($key, $chipper){
                $key = cms_encode($key, $chipper);
                if(array_key_exists($key, $_COOKIE)){
                    return cms_decode($_COOKIE[$key], $chipper);
                }
                return NULL;
            }
        }

        // get base url
        $_BASE_URL = get_decoded_cookie('__cms_base_url', $chipper);
        $_BASE_URL = $_BASE_URL !== NULL? $_BASE_URL : '{{ BASE_URL }}';

        // get subsite
        $_cms_subsite = get_decoded_cookie('__cms_subsite', $chipper);
        $_cms_subsite = $_cms_subsite !== NULL? $_cms_subsite : '';
        // get user_id
        $_cms_user_id = get_decoded_cookie('__cms_user_id', $chipper);
        $_cms_user_id = $_cms_user_id !== NULL? $_cms_user_id : NULL;
        $_user_dir = $_cms_user_id !== NULL ?  $_cms_user_id : 'no_user';
        $_user_dir = $_cms_subsite == ''? '/main-'.$_user_dir : '/site-'.$_cms_subsite.'-'.$_user_id;

        // LEAVE THE REST AS IS ...
    ```


Migration to server
===================

In case of you test No-CMS in local computer and want to upload it into public server, you need to change these parts:

* `RewriteBase` in `/.htaccess`

    RewriteBase should be `/` if you put No-CMS in top public directory (eg: If your web address is http://some_domain.com, then you should edit RewriteBase into `RewriteBase /`).

    RewriteBase should be `/your_folder` if you put No-CMS inside a directory in your public directory (eg: If your web address is http://some_domain.com/portal/, then you should edit RewriteBase into `RewriteBase /portal/`)

    Please consider that the `RewriteBase` entry is related to your URL not to your absolute path.

    ```
        <IfModule mod_rewrite.c>
            Options +FollowSymLinks -Indexes
            RewriteEngine On
            RewriteBase /
            # DO NOT MODIFY UNTIL "END OF DENY"
            # {{ DENY }}
            # {{ END OF DENY }}

            #Checks to see if the user is attempting to access a valid file,
            #such as an image or css document, if this isn't true it sends the
            #request to index.php

            # multisite
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule ^site-([a-zA-Z0-9]*)$ index.php/?__cms_subsite=$1 [L,QSA]
            RewriteRule ^site-([a-zA-Z0-9]*)/(.*)$ index.php/$2?__cms_subsite=$1 [L,QSA]

            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule ^(.*)$ index.php/$1 [L,QSA]
        </IfModule>
        ...
    ```


* Database configuration in `/application/config/main/database.php`.

    If you are not sure about your database configuration, ask your hosting provider.

    ```php
        $db['default'] = array(
            'dsn'        => 'mysql:host=127.0.0.1;port=3306;dbname=no_cms', // This will be used if you chose 'pdo' as your 'dbdriver'
            'hostname'   => '127.0.0.1', // database server IP Address/domain. Using IP Address usually improve performance
            'username'   => 'root', // database user (probably different from your CPanel login)
            'password'   => 'toor', // password to access database
            'database'   => 'no_cms', // the database schema provided by server
            'dbdriver'   => 'mysqli',
            'dbprefix'   => '',
            'pconnect'   => TRUE,
            'db_debug'   => TRUE,
            'cache_on'   => FALSE,
            'cachedir'   => '',
            'char_set'   => 'utf8',
            'dbcollat'   => 'utf8_general_ci',
            'swap_pre'   => '',
            'autoinit'   => TRUE,
            'encrypt'    => FALSE,
            'compress'   => FALSE,
            'stricton'   => FALSE,
            'failover'   => array()
        );
    ```

* If you have multisite installed and have several sub-site, you should also modify database configuration for each subsite (`application/config/site-*/database.php`).

* `$hostname` in `hostname.php`

* `$_BASE_URL` and `$_FCPATH` in `assets/kcfinder/config.php`

    `$_BASE_URL` should contains No-CMS url with trailing slash. So, if you access your website by using `http://some_domain.com`, the `$_BASE_URL` should contains `http://some_domain.com`

    `$_FCPATH` should contains absolute path of your No-CMS installation folder with trailing slash. So your installation folder is `/home/your_user/public_html`, the `$_FCPATH` should contains `/home/your_user/public_html/`. If you are not sure about this, please ask your hosting provider.

    ```php
        $_BASE_URL = 'http://some_domain.com/';
        $_FCPATH = '/home/your_user/public_html/';
    ```

* `BASE_URL` in `assets/grocery_crud/texteditor/ckeditor/config.js`

    `BASE_URL` should contains No-CMS url with trailing slash. So, if you access your website by using `http://some_domain.com`, the `BASE_URL` should contains `http://some_domain.com`

    ```js
        var BASE_URL = 'http://some_domain.com/'
    ```
