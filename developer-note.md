Git Commands
============

* Set up git

        git config --global user.name "goFrendiAsgard"
        git config --global user.email goFrendiAsgard@gmail.com

* Init git repo (Just here for historical purpose):

        mkdir No-CMS
        cd No-CMS
        git init
        touch README
        git add README
        git commit -m 'first commit'
        git remote add origin git@github.com:goFrendiAsgard/No-CMS.git
        git push -u origin master

* Something wrong with my computer (e.g: after re-install OS)

        cd No-CMS
        git remote add origin git@github.com:goFrendiAsgard/No-CMS.git
        git push -u origin master

* Commit changes

        git add . -A
        git commit -m 'the comment'
        git tag -a v1.4 -m 'version 1.4'
        git push -u origin master --tags

* Wrong tag

        git tag -d 7.x-3.x-alpha3
        git push origin :refs/tags/7.x-3.x-alpha3

* Revert

        git  reset --hard


HOW TO MAKE NO-CMS
===================

* Ingredients:
    - CodeIgniter 3.0.6
    - HMVC: https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc/src
    - GroceryCRUD: http://www.grocerycrud.com/downloads
    - Phil Sturgeon unzip library: https://github.com/philsturgeon/codeigniter-unzip
    - Phil Sturgeon Format Library
    - Image_moo library
    - jsmin.php
    - CodeIgniter HybridAuth Library
    - kcfinder
    - Some No-CMS specially written files:
        - `/assets/nocms/*`
        - `/assets/bootstrap/*`
        - `/assets/languages/*`
        - `/assets/navigation_icon/*`
        - `/themes/*`
        - `/modules/*`
        - `/license/license-No-CMS.txt`
        - `/license/license-grocery-crud.txt`
        - `/license/license-codeigniter.txt`
        - `/license/license-gpl3.txt`
        - `/license/license-mit.txt`
        - `/readme.md`
        - `/developer-note.md`
        - `/reset-installation.sh`
        - `/application/config/first-time/third_party_config/*`
        - `/application/core/MY_Lang.php`
        - `/application/core/MY_Loader.php`
        - `/application/core/MY_Router.php`
        - `/application/core/CMS_Model.php`
        - `/application/core/CMS_AutoUpdate_Model.php`
        - `/application/core/CMS_Controller.php`
        - `/application/core/CMS_Secure_Controller.php`
        - `/application/core/CMS_Module.php`
        - `/application/core/CMS_REST_Controller.php`
        - `/application/models/No_cms_model.php`
        - `/application/models/No_cms_autoupdate_model.php`
        - `/application/models/grocery_crud_generic_model.php`
        - `/application/models/grocery_crud_model_*.php`
        - `/application/views/CMS_View.php`
        - `/application/views/grocery_CRUD.php`
        - `/application/views/welcome_message.php`
        - `/application/libraries/Cms_Asset.php`
        - `/application/libraries/Extended_grocery_crud.php`
        - `/application/config/cms_config.php`
        - `/application/helpers/cms_helper.php`
        - `index.php`

* Steps:
    - Put CodeIgniter and ingredients all together. Don't overwrite autoload.php (beware of Phil's template). Put KCFinder under `/assets` directory
    - Download jquery min.map (adjust with the one used by groceryCRUD, in current case 1.10.2, from http://jquery.com/download/) and put it on `/assets/grocery_crud/js`
    - Download newest CKEditor, replace groceryCRUD's CKEditor with that one
    - Rename `/assets/kcfinder/config.php` into `/assets/kcfinder/config-ori.php`
    - Rename `/assets/grocery_crud/texteditor/ckeditor/config.js` into `/assets/grocery_crud/texteditor/ckeditor/config-ori.js`
    - Modify `/assets/kcfinder/core/uploader.php`

```php
        protected function get_htaccess() {
            // Modified by Go Frendi Gunawan, 23 Nov 2013
            return '';
            //
            /**
            return "<IfModule mod_php4.c>
      php_value engine off
    </IfModule>
    <IfModule mod_php5.c>
      php_value engine off
    </IfModule>
    ";
           **/
        }
```

and (line 138)


```php
    switch ($this->cms) {
        case "drupal": break;
        // Modified by Go Frendi Gunawan, 23 Nov 2013
        default: if(!isset($_SESSION))session_start(); break;
        //
        //default: session_start(); break;
    }
```

and (line 228)

```php
    if (!is_dir($this->config['uploadDir'])){
        @mkdir($this->config['uploadDir'], $this->config['dirPerms']);
        @file_put_contents($this->config['uploadDir'].'index.html', 'Directory Access is forbidden');
    }
```

    - Add this code to `system/database/DB_forge.php` line 782, function `_process_fields`

```
    // BUG FIX ==========
    $new_constraints = array();
    foreach($attributes['CONSTRAINT'] as $constraint){
        $constraint = trim($constraint, '\'');
        $new_constraints[] = $constraint;
    }
    $attributes['CONSTRAINT'] = $new_constraints;
    // END OF BUG FIX ===
```
