[Up](../tutorial.md)

Update 
======
If you have git installed, updating No-CMS to the newest version is going to be easy. As easy as you type:
```git fetch origin master```

For non git user, you can just download the newest and safely overwrite everything except:

* `/application/config/` directory
* `/.htaccess` file
* `/site.php` file
* `/assets/kcfinder/config.php`
* `/assets/grocery_crud/texteditor/ckeditor/config.js`

Migration to server
===================

In case of you test No-CMS in local computer and want to upload it into public server, you need to change these parts:

* `RewriteBase` in `/.htaccess`

    RewriteBase should be `/` if you put No-CMS in top public directory (eg: If your web address is http://some_domain.com, then you should edit RewriteBase into `RewriteBase /`).

    RewriteBase should be `/your_folder` if you put No-CMS inside a directory in your public directory (eg: If your web address is http://some_domain.com/portal/, then you should edit RewriteBase into `RewriteBase /portal/`)

    Please consider that the `RewriteBase` entry is related to your URL not to your absolute path.

    ```
        <IfModule mod_rewrite.c>
            # Options +FollowSymLinks -Indexes
            RewriteEngine On
            RewriteBase /
            #Checks to see if the user is attempting to access a valid file,
            #such as an image or css document, if this isn't true it sends the
            #request to index.php
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule ^(.*)$ index.php/$1 [L,QSA]
        </IfModule>
        ...
    ```


* Database configuration in `/application/config/database.php`.

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