<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* load the MX_Loader class */
if (!class_exists('MX_Loader', false)) {
    require APPPATH.'third_party/MX/Loader.php';
}

class MY_Loader extends MX_Loader {

    // Added by Ivan Tcholakov, 23-MAR-2013.
    public function set_module($module) {

        $this->_module = $module;
    }

    // Modified by Ivan Tcholakov, 12-OCT-2013.
    protected function _ci_load_class($class, $params = NULL, $object_name = NULL, $try_with_lcfirst = false)
    {
        $original = compact('class', 'params', 'object_name');

        // Get the class name, and while we're at it trim any slashes.
        // The directory path can be included as part of the class name,
        // but we don't want a leading slash
        $class = str_replace('.php', '', trim($class, '/'));

        // Was the path included with the class name?
        // We look for a slash to determine this
        if (($last_slash = strrpos($class, '/')) !== FALSE)
        {
            // Extract the path
            $subdir = substr($class, 0, ++$last_slash);

            // Get the filename from the path
            $class = substr($class, $last_slash);
        }
        else
        {
            $subdir = '';
        }

        if (!$try_with_lcfirst) {
            $class = ucfirst($class);
        } else {
            $class = lcfirst($class);
        }
        $subclass = APPPATH.'libraries/'.$subdir.config_item('subclass_prefix').$class.'.php';

        // Is this a class extension request?
        if (file_exists($subclass))
        {
            $baseclass = BASEPATH.'libraries/'.$subdir.$class.'.php';

            // A modification by Ivan Tcholakov, 07-APR-2013.
            // A fix for loaddnig Session library.
            //if ( ! file_exists($baseclass))
            if ($class != 'Session' && ! file_exists($baseclass))
            //
            {
                log_message('error', 'Unable to load the requested class: '.$class);
                show_error('Unable to load the requested class: '.$class);
            }

            // Safety: Was the class already loaded by a previous call?
            if (class_exists(config_item('subclass_prefix').$class, FALSE))
            {
                // Before we deem this to be a duplicate request, let's see
                // if a custom object name is being supplied. If so, we'll
                // return a new instance of the object
                if ($object_name !== NULL)
                {
                    $CI =& get_instance();
                    if ( ! isset($CI->$object_name))
                    {
                        return $this->_ci_init_class($class, config_item('subclass_prefix'), $params, $object_name);
                    }
                }

                log_message('debug', $class.' class already loaded. Second attempt ignored.');
                return;
            }

            // A modification by Ivan Tcholakov, 07-APR-2013.
            //include_once($baseclass);
            if ($class != 'Session')
            {
                include_once $baseclass;
            }
            //
            include_once $subclass;

            return $this->_ci_init_class($class, config_item('subclass_prefix'), $params, $object_name);
        }

        // Let's search for the requested library file and load it.
        foreach ($this->_ci_library_paths as $path)
        {
            $filepath = $path.'libraries/'.$subdir.$class.'.php';

            // Safety: Was the class already loaded by a previous call?
            if (class_exists($class, FALSE))
            {
                // Before we deem this to be a duplicate request, let's see
                // if a custom object name is being supplied. If so, we'll
                // return a new instance of the object
                if ($object_name !== NULL)
                {
                    $CI =& get_instance();
                    if ( ! isset($CI->$object_name))
                    {
                        return $this->_ci_init_class($class, '', $params, $object_name);
                    }
                }

                log_message('debug', $class.' class already loaded. Second attempt ignored.');
                return;
            }
            // Does the file exist? No? Bummer...
            elseif ( ! file_exists($filepath))
            {
                continue;
            }

            include_once $filepath;
            return $this->_ci_init_class($class, '', $params, $object_name);
        }

        if (!$try_with_lcfirst) {
            $this->_ci_load_class($original['class'], $original['params'], $original['object_name'], true);
        }

        // One last attempt. Maybe the library is in a subdirectory, but it wasn't specified?
        if ($subdir === '')
        {
            return $this->_ci_load_class($class.'/'.$class, $params, $object_name);
        }

        // If we got this far we were unable to find the requested class.
        log_message('error', 'Unable to load the requested class: '.$class);
        show_error('Unable to load the requested class: '.$class);
    }

    public function is_loaded($class) {

        //return array_search(ucfirst($class), $this->_ci_classes, TRUE);
        return array_search(strtolower($class), array_map('strtolower', $this->_ci_classes));   // Case insensitive search.
    }

    protected function _ci_init_class($class, $prefix = '', $config = FALSE, $object_name = NULL)
    {
        // Is there an associated config file for this class? Note: these should always be lowercase
        if ($config === NULL)
        {
            // Fetch the config paths containing any package paths
            $config_component = $this->_ci_get_component('config');

            if (is_array($config_component->_config_paths))
            {
                // Break on the first found file, thus package files
                // are not overridden by default paths
                foreach ($config_component->_config_paths as $path)
                {
                    // We test for both uppercase and lowercase, for servers that
                    // are case-sensitive with regard to file names. Check for environment
                    // first, global next
                    if (file_exists($path.'config/'.ENVIRONMENT.'/'.strtolower($class).'.php'))
                    {
                        include($path.'config/'.ENVIRONMENT.'/'.strtolower($class).'.php');
                        break;
                    }
                    elseif (file_exists($path.'config/'.ENVIRONMENT.'/'.ucfirst(strtolower($class)).'.php'))
                    {
                        include($path.'config/'.ENVIRONMENT.'/'.ucfirst(strtolower($class)).'.php');
                        break;
                    }
                    elseif (file_exists($path.'config/'.strtolower($class).'.php'))
                    {
                        include($path.'config/'.strtolower($class).'.php');
                        break;
                    }
                    elseif (file_exists($path.'config/'.ucfirst(strtolower($class)).'.php'))
                    {
                        include($path.'config/'.ucfirst(strtolower($class)).'.php');
                        break;
                    }
                }
            }
        }

        if ($prefix === '')
        {
            if (class_exists('CI_'.$class, FALSE))
            {
                $name = 'CI_'.$class;
            }
            elseif (class_exists(config_item('subclass_prefix').$class, FALSE))
            {
                $name = config_item('subclass_prefix').$class;
            }
            else
            {
                $name = $class;
            }
        }
        else
        {
            $name = $prefix.$class;
        }

        // Is the class name valid?
        if ( ! class_exists($name, FALSE))
        {
            log_message('error', 'Non-existent class: '.$name);
            show_error('Non-existent class: '.$name);
        }

        // Added by Ivan Tcholakov, 25-JUL-2013.
        $class = strtolower($class);
        //

        // Set the variable name we will assign the class to
        // Was a custom class name supplied? If so we'll use it
        if (empty($object_name))
        {
            $object_name = strtolower($class);
            if (isset($this->_ci_varmap[$object_name]))
            {
                $object_name = $this->_ci_varmap[$object_name];
            }
        }

        // Don't overwrite existing properties
        $CI =& get_instance();
        if (isset($CI->$object_name))
        {
            if ($CI->$object_name instanceof $name)
            {
                log_message('debug', $class." has already been instantiated as '".$object_name."'. Second attempt aborted.");
                return;
            }

            show_error("Resource '".$object_name."' already exists and is not a ".$class." instance.");
        }


        // Save the class name and object name
        // Modified by Ivan Tcholakov, 25-JUL-2013.
        //$this->_ci_classes[$object_name] = $class;
        $this->_ci_classes[$class] = $object_name;
        //

        // Instantiate the class
        $CI->$object_name = isset($config)
            ? new $name($config)
            : new $name();
    }

    // Modified by Go Frendi Gunawan, 12-NOV-2013
    /** Load a module model **/
    public function model($model, $object_name = NULL, $connect = FALSE) {
        
        if (is_array($model)) return $this->models($model);

        ($_alias = $object_name) OR $_alias = basename($model);

        if (in_array($_alias, $this->_ci_models, TRUE)) 
            return CI::$APP->$_alias;
            
        /* check module */
        list($path, $_model) = Modules::find(strtolower($model), $this->_module, 'models/');
        
        if ($path == FALSE) {
            
            /* check application & packages */
            $this->__original_model($model, $object_name, $connect);
            
        } else {
            
            class_exists('CI_Model', FALSE) OR load_class('Model', 'core');
            
            if ($connect !== FALSE AND ! class_exists('CI_DB', FALSE)) {
                if ($connect === TRUE) $connect = '';
                $this->database($connect, FALSE, TRUE);
            }
            
            Modules::load_file($_model, $path);
            
            $model = ucfirst($_model);
            CI::$APP->$_alias = new $model();
            
            $this->_ci_models[] = $_alias;
        }
        
        return CI::$APP->$_alias;
    }
    public function __original_model($model, $name = '', $db_conn = FALSE)
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
            }else{
                require_once($mod_path.'models/'.$path.$model.'.php');                
            }
            $model = ucfirst($model);
            $CI->$name = new $model();

            $this->_ci_models[] = $name;
            return;
        }
        // END OF MY BACKWARD COMPATIBLE CODE

        // couldn't find the model
        show_error('Unable to locate the model you have specified: '.$model);
    }


}