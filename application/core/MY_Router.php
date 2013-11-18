<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Router class */
if (!class_exists('MX_Router', false)) {
    require APPPATH.'third_party/MX/Router.php';
}

// SEO Friendly URLS in CodeIgniter 2.0 + HMVC
// http://www.einsteinseyes.com/blog/techno-babble/seo-friendly-urls-in-codeigniter-2-0-hmvc/

// Controller location logic has been slightly modified by Ivan Tcholakov, 2012.
class MY_Router extends MX_Router {

    public $slug_defs = null;

    public function __construct() {

        parent::__construct();
    }

    /** Locate the controller **/
    public function locate($segments) {

        $this->module = '';
        $this->directory = '';

        // Use module route if available.
        if (isset($segments[0]) && $routes = Modules::parse_routes($segments[0], implode('/', $segments))) {
            $segments = $routes;
        }

        // Get the segments array elements.
        list($segment0, $segment1, $segment2) = array_pad($segments, 3, NULL);

        $segment0 = str_replace('-', '_', $segment0);
        $segment1 = str_replace('-', '_', $segment1);
        $segment2 = str_replace('-', '_', $segment2);

        // Check modules.
        foreach (Modules::$locations as $location => $offset) {

            // Module exists?
            if (is_dir($source = $location.$segment0.'/controllers/')) {

                $is_module_default_controller = false;
                $is_module_controller = false;
                $is_module_directory = false;
                $is_module_directory_default_controller = false;
                $is_module_directory_controller = false;

                $subdirectory = '';

                $this->module = $segment0;
                $this->directory = $offset.$segment0.'/controllers/';

                // module/controller
                if (
                        $segment1 
                        &&
                        (
                            $this->is_controller($source.ucfirst($segment1))
                            ||
                            $this->is_controller($source.$segment1)
                        )
                    ) {
                    $is_module_controller = true;
                }

                // module/directory
                if ($segment1 && is_dir($source.$segment1.'/')) {

                    $is_module_directory = true;

                    $source = $source.$segment1.'/';
                    $subdirectory = $this->directory.$segment1.'/';

                    // module/directory (deault_controller = directory)
                    if (
                            $this->is_controller($source.ucfirst($segment1))
                            ||
                            $this->is_controller($source.$segment1)
                        ) {
                        $is_module_directory_default_controller = true;
                    }

                    // module/directory/controller
                    if (
                            $segment2
                            &&
                            (
                                $this->is_controller($source.ucfirst($segment2))
                                ||
                                $this->is_controller($source.$segment2)
                            )
                        ) {
                        $is_module_directory_controller = true;
                    }
                }

                // module (deault_controller = module)
                if (
                        $this->is_controller($source.ucfirst($segment0))
                        ||
                        $this->is_controller($source.$segment0)
                    ) {
                    $is_module_default_controller = true;
                }

                /*
                // This is the original logic.
                if ($is_module_controller) {
                    return array_slice($segments, 1);
                } elseif ($is_module_directory) {
                    $this->directory = $subdirectory;
                    if ($is_module_directory_default_controller) {
                        return array_slice($segments, 1);
                    } elseif ($is_module_directory_controller) {
                        return array_slice($segments, 2);
                    }
                } elseif ($is_module_default_controller) {
                    return $segments;
                }
                */

                // This is the modified logic, Ivan Tcholakov, 16-JUN-2012.
                $result = false;

                if ($is_module_controller && $is_module_directory && ($is_module_directory_default_controller || $is_module_directory_controller)) {
                    $this->directory = $subdirectory;
                    if ($is_module_directory_default_controller) {
                        $result = array_slice($segments, 1);
                    } elseif ($is_module_directory_controller) {
                        $result = array_slice($segments, 2);
                    }
                } elseif ($is_module_controller) {
                    $result = array_slice($segments, 1);
                } elseif ($is_module_directory) {
                    $this->directory = $subdirectory;
                    if ($is_module_directory_controller) {
                        $result = array_slice($segments, 2);
                    } elseif ($is_module_directory_default_controller) {
                        $result = array_slice($segments, 1);
                    }
                } elseif ($is_module_default_controller) {
                    $result = $segments;
                }

                if ($result !== false) {
                    return $result;
                }
                //
            }
        }

        // Application controller exists?
        if (
                $this->is_controller(APPPATH.'controllers/'.ucfirst($segment0))
                ||
                $this->is_controller(APPPATH.'controllers/'.$segment0)
            ) {
            return $segments;
        }

        // Application sub-directory controller exists?
        if (
                $segment1
                && 
                (
                    $this->is_controller(APPPATH.'controllers/'.$segment0.'/'.ucfirst($segment1))
                    ||
                    $this->is_controller(APPPATH.'controllers/'.$segment0.'/'.$segment1)
                )
            ) {
            $this->directory = $segment0.'/';
            return array_slice($segments, 1);
        }

        // Application sub-directory default controller exists?
        if (
                $this->is_controller(APPPATH.'controllers/'.$segment0.'/'.ucfirst($this->default_controller))
                ||
                $this->is_controller(APPPATH.'controllers/'.$segment0.'/'.$this->default_controller)
            ) {
            $this->directory = $segment0.'/';
            return array($this->default_controller);
        }
    }

    public function set_class($class) {
        $this->class = str_replace('-', '_', $class).$this->config->item('controller_suffix');
    }

    public function set_method($method) {
        $this->method = str_replace('-', '_', $method);
    }

    protected function is_controller($base_path) {

        static $ext;

        if (!isset($ext)) {
            $ext = $this->config->item('controller_suffix').'.php';
        }

        return is_file($base_path.$ext) || is_file($base_path.'.php');
    }

}
