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
    - CodeIgniter development branch: https://github.com/EllisLab/CodeIgniter/tree/fb2ac41b6c914fd55b539337e381860bfcc2cf7b
    - Phil Sturgeon Template: https://github.com/philsturgeon/codeigniter-template
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
        - `/application/core/MY_CodeIgniter.php` 

            This one overwrite `system/core/CodeIgniter.php` 

        - `/application/core/MY_Lang.php`

            This one overwrite `system/core/Lang.php`

        - `/application/core/MY_Loader.php`

            This one overwrite `system/core/Loader.php`

        - `/application/core/MY_Router.php`

            This one overwrite `system/core/Router.php`

        - `/application/core/MY_Controller.php`
        - `/application/core/MY_Model.php`
        - `/application/models/no_cms_model.php`
        - `/application/models/grocery_crud_generic_model.php`
        - `/application/models/grocery_crud_model_*.php`
        - `/application/views/CMS_View.php`
        - `/application/views/grocery_CRUD.php`
        - `/application/views/welcome_message.php`
        - `/application/libraries/CMS_Asset.php`
        - `/application/libraries/Extended_Grocery_CRUD.php`

            Sometime grocery_crud is lack of things. I usually apply bug-fix here before add pull request to main groceryCRUD repository.

        - `/application/libraries/fake/*`

            This is for intelisense purpose (i.e: if user use eclipse or aptana)

        - `/application/database/*`

            Sometime I need to overwrite CodeIgniter DB Driver. Here are the files. By default CodeIgniter doesn't support to extend DB Driver.

        - `/application/config/cms_config.php`

            Contains some default configuration

        - `/application/helpers/cms_helper.php`

            Contains some functions such as `cms_table_name`

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

    - Edit `/assets/grocery_crud/themes/flexigrid/css/flexigrid.css`

        ```css
            .flexigrid div.form-div textarea
            {
                font-size: 15px;
                border: 1px solid #AAA;
                padding: 5px 5px 5px 5px;
                background: #fafafa;
            }
        ```
        into
        ```css
            .flexigrid div.form-div
            {
                font-size: 15px;
                border: 1px solid #AAA;
                padding: 5px 5px 5px 5px;
                background: #fafafa;
            }
        ```

    - Move `/application/config/*` into `/application/config/first-time/*`
    - Edit `/application/config/first-time/config.php`, modify `encryption_key` value

        ```php
            $config['encryption_key'] = 'namidanoregret';
        ```

    - Edit `/application/config/first-time/config.php`, add this code:

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
            |   Default: array(APPPATH.'themes/' => '../themes/')
            |
            */

            $config['theme_locations'] = array( 
                APPPATH.'../themes/',
                APPPATH.'themes/',
            );
        ```

    - Edit `/application/third_party/MX/Base.php` around line `51` into:

        ```php
            /* re-assign language and config for modules */
            // Modified by Ivan Tcholakov, 28-SEP-2012.
            //if ( ! is_a($LANG, 'MX_Lang')) $LANG = new MX_Lang;
            //if ( ! is_a($CFG, 'MX_Config')) $CFG = new MX_Config;
            if ( @ ! is_a($LANG, 'MX_Lang')) $LANG = new MX_Lang;
            if ( @ ! is_a($CFG, 'MX_Config')) $CFG = new MX_Config;
            //
        ```

    - Edit `/application/third_party/MX/Ci.php` line `46` into

        ```php
            self::$APP = MY_Controller::get_instance();
        ```    

     - Edit `/application/third_party/MX/Ci.php` around line `50` into:

        ```php
            /* re-assign language and config for modules */
            // Modified by Ivan Tcholakov, 28-SEP-2012.
            //if ( ! is_a($LANG, 'MX_Lang')) $LANG = new MX_Lang;
            //if ( ! is_a($CFG, 'MX_Config')) $CFG = new MX_Config;
            if ( @ ! is_a($LANG, 'MX_Lang')) $LANG = new MX_Lang;
            if ( @ ! is_a($CFG, 'MX_Config')) $CFG = new MX_Config;
            //
        ```

    - Edit `/application/third_party/MX/Loader.php` around line `49` (function initialize)

        ```php
            // Modified by Ivan Tcholakov, 28-SEP-2012.
            //if (is_a($controller, 'MX_Controller')) {
            if (@ is_a($controller, 'MX_Controller')) {
            //
        ```

    - Edit `/application/third_party/MX/Loader.php` around line `159` (function library)

        ```php
            // Modified by Ivan Tcholakov, 26-JUL-2013.
            //if (isset($this->_ci_classes[$class]) AND $_alias = $this->_ci_classes[$class])
            //    return CI::$APP->$_alias;
            //    
            //($_alias = strtolower($object_name)) OR $_alias = $class;
            if (isset($this->_ci_classes[$class])) {

                $_alias = $this->_ci_classes[$class];

                if ($_alias) {

                    return CI::$APP->$_alias;
                }
            }

            $_alias = strtolower($object_name);

            if (!$_alias) {

                $_alias = $class;
            }
            //
        ```

    - Edit `/application/third_party/MX/Loader.php` around line `228` (function model)

        ```php
            /* check application & packages */
            // Modified by Ivan Tcholakov, 30-OCT-2013.
            //parent::model($model, $object_name, $connect);
            $this->_ci_model($model, $object_name, $connect);
            //
        ```

    - Edit `/application/third_party/MX/Loader.php` around line `255` add function `_ci_model`. This function is actually copy-pasted from `/system/core/Loader.php`.

        ```php
        // Added by Ivan Tcholakov, 30-OCT-2013.
        protected function _ci_model($model, $name = '', $db_conn = FALSE)
        {
            if (empty($model))
            {
                return;
            }
            elseif (is_array($model))
            {
                foreach ($model as $key => $value)
                {
                    $this->model(is_int($key) ? $value : $key, $value);
                }
                return;
            }

            $path = '';

            // Is the model in a sub-folder? If so, parse out the filename and path.
            if (($last_slash = strrpos($model, '/')) !== FALSE)
            {
                // The path is in front of the last slash
                $path = substr($model, 0, ++$last_slash);

                // And the model name behind it
                $model = substr($model, $last_slash);
            }

            if (empty($name))
            {
                $name = $model;
            }

            if (in_array($name, $this->_ci_models, TRUE))
            {
                return;
            }

            $CI =& get_instance();
            if (isset($CI->$name))
            {
                show_error('The model name you are loading is the name of a resource that is already being used: '.$name);
            }

            if ($db_conn !== FALSE && ! class_exists('CI_DB', FALSE))
            {
                if ($db_conn === TRUE)
                {
                    $db_conn = '';
                }

                $CI->load->database($db_conn, FALSE, TRUE);
            }

            if ( ! class_exists('CI_Model', FALSE))
            {
                load_class('Model', 'core');
            }

            $model = ucfirst(strtolower($model));

            foreach ($this->_ci_model_paths as $mod_path)
            {
                if ( ! file_exists($mod_path.'models/'.$path.$model.'.php'))
                {
                    if (file_exists($mod_path.'models/'.$path.lcfirst($model).'.php'))
                    {
                        $model = lcfirst($model);
                    }
                    else
                    {
                        continue;
                    }
                }

                require_once($mod_path.'models/'.$path.$model.'.php');

                // Added by Ivan Tcholakov, 25-JUL-2013.
                $model = ucfirst($model);
                //
                $CI->$name = new $model();
                $this->_ci_models[] = $name;
                return;
            }

            // couldn't find the model
            show_error('Unable to locate the model you have specified: '.$model);
        }
        ```

    - Edit `/application/third_party/MX/Modules.php` around line `99` (function load)

        ```php
            /* load the controller class */
            // Modified by Ivan Tcholakov, 28-FEB-2012.
            //$class = $class.CI::$APP->config->item('controller_suffix');
            if (self::test_load_file(ucfirst($class).CI::$APP->config->item('controller_suffix'), $path)) {
                $class = ucfirst($class).CI::$APP->config->item('controller_suffix');
            }
            elseif (self::test_load_file($class.CI::$APP->config->item('controller_suffix'), $path)) {
                $class = $class.CI::$APP->config->item('controller_suffix');
            }
            elseif (self::test_load_file(ucfirst($class), $path)) {
                $class = ucfirst($class);
            }
            //
        ```

    - Edit `/application/MX/Modules.php` around line `89` and respective curly-bracket

        ```php
            // removed by Go Frendi Gunawan, 04-DEC-2013, since this make widget cannot be called twice if there is another widget call
            // from another widget
            //if ( ! isset(self::$registry[$alias])) {
        ```

    - Edit `/application/MX/Modules.php` around line `239` (function parse_routes)

        ```php
            // Modified by Ivan Tcholakov, 31-OCT-2012.
            //$key = str_replace(array(':any', ':num'), array('.+', '[0-9]+'), $key);
            $key = str_replace(array(':any', ':num'), array('[^/]+', '[0-9]+'), $key);
            //
        ```

    - Edit `/index.php`, replace the beginning part into this:

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

        and replace the ending part into:

        ```php
            require_once APPPATH.'core/MY_CodeIgniter.php';
        ```
        Thanks Ivan !!! :)

    - For the rest, refer to this: https://github.com/goFrendiAsgard/No-CMS/commit/fb16b2c905e631745e918fc579c007be2f39eb27