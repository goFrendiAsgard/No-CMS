[Up](../tutorial.md)

Update 
======
If you have git installed, updating No-CMS to the newest version is going to be easy. As easy as you type:
```git fetch origin master```

For non git user, you can just download the newest and safely overwrite everything except:
* `/application/config/` directory
* `/.htaccess` file

Migration to server
===================

In case of you test No-CMS in local computer and want to upload it into public server, you need to change these parts:
* `RewriteBase` in `/.htaccess`
* Database configuration in `/application/config/database.php`

RewriteBase should be `/` if you put No-CMS in top public directory (eg: * If your web address is http://some_domain.com, then you should edit RewriteBase into `RewriteBase /`).

RewriteBase should be `/your_folder` if you put No-CMS inside a directory in your public directory (eg: * If your web address is http://some_domain.com/portal/, then you should edit RewriteBase into `RewriteBase /portal/`)