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
    - CodeIgniter: http://codeigniter.com/
    - Phil Sturgeon Template: https://github.com/philsturgeon/codeigniter-template
    - HMVC template: https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc/src
    - GroceryCRUD: http://www.grocerycrud.com/downloads
    - Phil Sturgeon unzip library: https://github.com/philsturgeon/codeigniter-unzip
    - Some No-CMS specially written files:
        - /assets/
        - /themes/
        - /modules/
        - /license/
        - /readme.md
        - /developer-note.md
        - /reset-installation.sh
        - /application/core/*
        - /application/models/*
        - /application/views/*
        - /application/libraries/*
* Steps:
    - Put CodeIgniter and ingredients all together. Don't overwrite autoload.php (beware of Phil's template)
    - Move `/application/config/*` into `/application/config/first-time/*`
    - Edit `/application/config/first-time/config.php`, add `encryption_key`
    - Edit `/application/third_party/MX/Ci.php` line `46` into

        ```php
            self::$APP = CMS_Controller::get_instance();
        ```

    - Edit `/application/config/config.php`, add this code:

        ```php
            /*
            |--------------------------------------------------------------------------
            | Modules Location (HMVC plugin)
            |--------------------------------------------------------------------------
            |
            | The modules location is put outside application folder
            |
            */
            $config['modules_locations'] = array(APPPATH . '../modules/' => '../../modules/');
            ```
        - Edit `/application/config/template.php`, change the last part into:
            ```php
            /*
            |--------------------------------------------------------------------------
            | Theme
            |--------------------------------------------------------------------------
            |
            | Where should we expect to see themes?
            |
            |	Default: array(APPPATH.'themes/' => '../themes/')
            |
            */

            $config['theme_locations'] = array(	
                APPPATH.'../themes/',
                APPPATH.'themes/',
            );
        ```

* Edit `/application/third_party/MX/Base.php`, use `isinstanceof` instead of `is_a`

    ```php
        //if ( ! is_a($LANG, 'MX_Lang')) $LANG = new MX_Lang;
        //if ( ! is_a($CFG, 'MX_Config')) $CFG = new MX_Config;
        if ( ! ($LANG instanceof MX_Lang)) $LANG = new MX_Lang;
        if ( ! ($CFG instanceof MX_Config)) $CFG = new MX_Config;
    ```

* Edit `/application/third_party/MX/Loader.php`

    ```php
        // if (is_a($controller, 'MX_Controller')) {
        if ($controller instanceof MX_Controller) {
    ```

* Edit `/index.php`, replace the beginning part into this:

    ```php
        if(!file_exists('./application/config/database.php')){
            define('ENVIRONMENT', 'first-time');
        }else{
            define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');
        }

        /*
         *---------------------------------------------------------------
         * ERROR REPORTING
         *---------------------------------------------------------------
         *
         * Different environments will require different levels of error reporting.
         * By default development will show errors but testing and live will hide them.
         */
        switch (ENVIRONMENT)
        {
            case 'development':
                error_reporting(-1);
                ini_set('display_errors', 1);
            break;

            case 'first-time':
            case 'testing':
            case 'production':
                error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_STRICT);
                ini_set('display_errors', 0);
            break;

            default:
                header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
                echo 'The application environment is not set correctly.';
                exit(1); // EXIT_* constants not yet defined; 1 is EXIT_ERROR, a generic error.
        }
    ```
    
* Edit /system/CodeIgniter.php (Look this for better reference: https://github.com/EllisLab/CodeIgniter/commit/20292311636837e120d205e470e41826820feb46)
* Edit /system/Loader.php (Look this for better reference: https://github.com/EllisLab/CodeIgniter/commit/20292311636837e120d205e470e41826820feb46)
* Edit /system/Router.php (Look this for better reference: https://github.com/EllisLab/CodeIgniter/commit/20292311636837e120d205e470e41826820feb46)
