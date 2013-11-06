[Up](../tutorial.md)

Installation
============

* Download No-CMS from [No-CMS repository](https://github.com/goFrendiAsgard/No-CMS) on GitHub
* Copy and extract it on your web server directory (If you use windows, you might want to try it locally via xampp, in this case, your server directory is `c:\xampp\htdocs`. If you use linux, the web server directory is usually `/var/www`)
* Access the url (If you use xampp in your local computer, the url should be http://localhost/No-CMS)
* Click "Install Now"
* Fill any information needed (especially database & administrator password. At this point, you can also enable third party authentication)
* If there is no error, click `Install now` button
* Wait for several seconds, the installer will do everything for you (including creating database and make config files)
* Once installation finished, you can do several things to enchance performance & security

Performance & Security Enchancement
===================================
* Go to `index.php`, change these code:
```php
    if(!file_exists('./application/config/database.php')){
        define('ENVIRONMENT', 'first-time');
    }else{
        define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');
    }
```
into:
```php
    define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'production');
```
This will save extra step, so that the program doesn't need to check existance of `/application/config/database.php`

* If you are using linux, please do this:
```
    chown your_user .htaccess
    chmod ./reset-installation.sh 600
    chown -R your_user ./application/config
    chmod ./application/config 744 -R
```
This will make your site more secure (does not mean invulnerable)