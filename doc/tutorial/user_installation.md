[Up](../tutorial.md)

Installation
============

* Download No-CMS from [No-CMS repository](https://github.com/goFrendiAsgard/No-CMS) on GitHub

* Copy and extract it on your web server directory (If you use windows, you might want to try it locally via xampp, in this case, your server directory is `c:\xampp\htdocs`. If you use linux, the web server directory is usually `/var/www`)

* Access the url (If you use xampp in your local computer, the url should be http://localhost/No-CMS)

* Click "Install No-CMS"
  ![Install No-CMS button](images/user_installation_install_no_cms.png "Figure 1. Install No-CMS button")

* Fill database information. You can change it later by editing `/application/config/main/databse.php`
  ![Fill Database Information](images/user_installation_database_information.png "Figure 2. Fill Database Information")

* Click "Next", and fill CMS Setting information, especially administrator information. If your server has `mod_rewrite` installed, it is recommended to `hide index.php`. You can also check `gzip compression`, so that No-CMS will compress every response to client. This wil reduce the network-traffic, but sometime can also cause several errors. You can change CMS Setting information later by editing `/application/config/main/config.php`
  ![Fill CMS Setting Information](images/user_installation_cms_setting.png "Figure 3. Fill CMS Setting")

* You can also optionally add several third party authentication by clicking the corresponding tabs. You can change it later by accessing `CMS Management | Setting`

* If there is no error, click `Install now` button. Wait for several seconds until the installation finished, the installer will do everything for you (including creating database, make config files, and install default modules)

__PS:__ Git user can do this instead of typical download-and-extract:
```
    git clone https://github.com/goFrendiAsgard/No-CMS.git
```
