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
    
* Edit `/system/CodeIgniter.php` into a backward compatible code (Look this for better reference: https://github.com/EllisLab/CodeIgniter/commit/20292311636837e120d205e470e41826820feb46)

    ```php
        // Load the local application controller
        // Note: The Router class automatically validates the controller path using the router->_validate_request().
        // If this include fails it means that the default controller in the Routes.php file is not resolving to something valid.
        
        /* ORIGINAL CODE:
        $class = ucfirst($RTR->class);
        if ( ! file_exists(APPPATH.'controllers/'.$RTR->directory.$class.'.php'))
        {
            show_error('Unable to load your default controller. Please make sure the controller specified in your Routes.php file is valid.');
        }

        include(APPPATH.'controllers/'.$RTR->directory.$class.'.php');
        */

        // MY BACKWARD COMPATIBLE CODE:
        $class = $RTR->class;
        if ( ! file_exists(APPPATH.'controllers/'.$RTR->directory.ucfirst($class).'.php'))
        {
            if ( ! file_exists(APPPATH.'controllers/'.$RTR->directory.$class.'.php'))
            {
                show_error('Unable to load your default controller. Please make sure the controller specified in your Routes.php file is valid.');
            }
        }else{
            $class = ucfirst($class);
        }
        include(APPPATH.'controllers/'.$RTR->directory.$class.'.php');
        // END OF MY BACKWARD COMPATIBLE CODE
    ```

* Edit `/system/Loader.php` (Look this for better reference: https://github.com/EllisLab/CodeIgniter/commit/20292311636837e120d205e470e41826820feb46)

    ```php

        class Original_CI_Loader{
            // the original code of CI_Loader
        }

        class CI_Loader extends Original_CI_Loader{

            /**
             * Model Loader
             *
             * Loads and instantiates libraries.
             *
             * @param   string  $model      Model name
             * @param   string  $name       An optional object name to assign to
             * @param   bool    $db_conn    An optional database connection configuration to initialize
             * @return  void
             */
            public function model($model, $name = '', $db_conn = FALSE)
            {
                if (empty($model))
                {
                    return;
                }
                elseif (is_array($model))
                {
                    foreach ($model as $key => $value)
                    {
                        is_int($key) ? $this->model($value, '', $db_conn) : $this->model($key, $value, $db_conn);
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

                /* ORIGINAL CODE:

                $model = ucfirst(strtolower($model));

                foreach ($this->_ci_model_paths as $mod_path)
                {
                    if ( ! file_exists($mod_path.'models/'.$path.$model.'.php'))
                    {
                        continue;
                    }

                    require_once($mod_path.'models/'.$path.$model.'.php');

                    $CI->$name = new $model();
                    $this->_ci_models[] = $name;
                    return;
                }
                */

                // MY BACKWARD COMPATIBLE CODE
                $model = strtolower($model);

                foreach ($this->_ci_model_paths as $mod_path)
                {
                    if ( ! file_exists($mod_path.'models/'.$path.$model.'.php'))
                    {
                        continue;
                    }

                    if(file_exists($mod_path.'models/'.$path.ucfirst($model).'.php')){
                        require_once($mod_path.'models/'.$path.ucfirst($model).'.php');  
                        $CI->$name = new ucfirst($model)();  
                    }else{
                        require_once($mod_path.'models/'.$path.$model.'.php');
                        $CI->$name = new $model();
                    }
                    
                    $this->_ci_models[] = $name;
                    return;
                }
                // END OF MY BACKWARD COMPATIBLE CODE

                // couldn't find the model
                show_error('Unable to locate the model you have specified: '.$model);
            }
        }
    ```

* Edit `/system/Router.php` (Look this for better reference: https://github.com/EllisLab/CodeIgniter/commit/20292311636837e120d205e470e41826820feb46)

    ```php

        class Original_CI_Router{
            // the original code of CI_Router
        }

        class CI_Loader extends Original_CI_Router {
            /**
             * Validate request
             *
             * Attempts validate the URI request and determine the controller path.
             *
             * @param   array   $segments   URI segments
             * @return  array   URI segments
             */
            protected function _validate_request($segments)
            {
                if (count($segments) === 0)
                {
                    return $segments;
                }

                /* ORIGINAL CODE:
                $test = ucfirst($this->translate_uri_dashes === TRUE ? str_replace('-', '_', $segments[0]) : $segments[0]);

                // Does the requested controller exist in the root folder?
                if (file_exists(APPPATH.'controllers/'.$test.'.php'))
                {
                    return $segments;
                }
                */

                // MY BACKWARD COMPATIBLE CODE:
                $test = $this->translate_uri_dashes === TRUE ? str_replace('-', '_', $segments[0]) : $segments[0];
                // Does the requested controller exist in the root folder?
                if (file_exists(APPPATH.'controllers/'.ucfirst($test).'.php') || file_exists(APPPATH.'controllers/'.$test.'.php'))
                {
                    return $segments;
                }
                // END OF MY BACKWARD COMPATIBLE CODE

                // Is the controller in a sub-folder?
                if (is_dir(APPPATH.'controllers/'.$segments[0]))
                {
                    // Set the directory and remove it from the segment array
                    $this->set_directory(array_shift($segments));
                    if (count($segments) > 0)
                    {    
                        /* ORIGINAL CODE:
                        $test = ucfirst($this->translate_uri_dashes === TRUE ? str_replace('-', '_', $segments[0]) : $segments[0]);

                        // Does the requested controller exist in the sub-directory?
                        if ( ! file_exists(APPPATH.'controllers/'.$this->directory.$test.'.php'))
                        {
                            if ( ! empty($this->routes['404_override']))
                            {
                                $this->directory = '';
                                return explode('/', $this->routes['404_override'], 2);
                            }
                            else
                            {
                                show_404($this->directory.$segments[0]);
                            }
                        }
                        */
                        // MY BACKWARD COMPATIBLE CODE:
                        $test = $this->translate_uri_dashes === TRUE ? str_replace('-', '_', $segments[0]) : $segments[0];

                        // Does the requested controller exist in the sub-directory?
                        if ( ! file_exists(APPPATH.'controllers/'.$this->directory.ucfirst($test).'.php') && ! file_exists(APPPATH.'controllers/'.$this->directory.$test.'.php'))
                        {
                            if ( ! empty($this->routes['404_override']))
                            {
                                $this->directory = '';
                                return explode('/', $this->routes['404_override'], 2);
                            }
                            else
                            {
                                show_404($this->directory.$segments[0]);
                            }
                        }
                        // END OF MY BACKWARD COMPATIBLE CODE

                    }
                    else
                    {
                        // Is the method being specified in the route?

                        /* ORIGINAL CODE:
                        $segments = explode('/', $this->default_controller);
                        if ( ! file_exists(APPPATH.'controllers/'.$this->directory.ucfirst($segments[0]).'.php'))
                        {
                            $this->directory = '';
                        }
                        */

                        // MY BACKWARD COMPATIBLE CODE:
                        $segments = explode('/', $this->default_controller);
                        if ( ! file_exists(APPPATH.'controllers/'.$this->directory.ucfirst($segments[0]).'.php') && ! file_exists(APPPATH.'controllers/'.$this->directory.$segments[0].'.php'))
                        {
                            $this->directory = '';
                        }
                        // END OF MY BACKWARD COMPATIBLE CODE
                    }

                    return $segments;
                }

                // If we've gotten this far it means that the URI does not correlate to a valid
                // controller class. We will now see if there is an override
                if ( ! empty($this->routes['404_override']))
                {
                    if (sscanf($this->routes['404_override'], '%[^/]/%s', $class, $method) !== 2)
                    {
                        $method = 'index';
                    }

                    return array($class, $method);
                }

                // Nothing else to do at this point but show a 404
                show_404($segments[0]);
            }
        }
    ```