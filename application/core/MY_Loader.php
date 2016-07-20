<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {

    // Modified by Go Frendi, to let MY_DB.php being used
    public function database($params = '', $return = FALSE, $query_builder = NULL)
	{
		// Grab the super object
		$CI =& get_instance();

		// Do we even need to load the database class?
		if ($return === FALSE && $query_builder === NULL && isset($CI->db) && is_object($CI->db) && ! empty($CI->db->conn_id))
		{
			return FALSE;
		}

		require_once(APPPATH.'core/database/MY_DB.php');

		if ($return === TRUE)
		{
			return DB($params, $query_builder);
		}

		// Initialize the db variable. Needed to prevent
		// reference errors with some configurations
		$CI->db = '';

		// Load the DB class
		$CI->db =& DB($params, $query_builder);
		return $this;
	}

    public function config($file, $use_sections = FALSE, $fail_gracefully = FALSE)
	{
        // For some reason, MY_Config::load cannot be overridden. Seems that MY_ prefix defined later
        // Original script:
        //    return get_instance()->config->load($file, $use_sections, $fail_gracefully);

        // Grab the super object
		$CI =& get_instance();

        $file = ($file === '') ? 'config' : str_replace('.php', '', $file);
		$loaded = FALSE;

		foreach ($CI->config->_config_paths as $path)
		{
            require_once(APPPATH.'core/config_location.php');
            $suggested_location = get_config_location($file); 

			foreach (array($file, $suggested_location) as $location)
			{
				$file_path = $path.'config/'.$location.'.php';
				if (in_array($file_path, $CI->config->is_loaded, TRUE))
				{
					return TRUE;
				}

				if ( ! file_exists($file_path))
				{
					continue;
				}

				include($file_path);

				if ( ! isset($config) OR ! is_array($config))
				{
					if ($fail_gracefully === TRUE)
					{
						return FALSE;
					}

					show_error('Your '.$file_path.' file does not appear to contain a valid configuration array.');
				}

				if ($use_sections === TRUE)
				{
					$CI->config->config[$file] = isset($CI->config->config[$file])
						? array_merge($CI->config->config[$file], $config)
						: $config;
				}
				else
				{
					$CI->config->config = array_merge($CI->config->config, $config);
				}

				$CI->config->is_loaded[] = $file_path;
				$config = NULL;
				$loaded = TRUE;
				log_message('debug', 'Config file loaded: '.$file_path);
			}
		}

		if ($loaded === TRUE)
		{
			return TRUE;
		}
		elseif ($fail_gracefully === TRUE)
		{
			return FALSE;
		}

		show_error('The configuration file '.$file.'.php does not exist.');


	}

}
