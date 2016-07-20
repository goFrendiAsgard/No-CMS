<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Config extends CI_Config {

	// Modified by Go Frendi, HTTP_HOST is more reliable than SERVER_ADDR
	// in case of we have different sites in the same server.
	public function __construct()
	{
		$this->config =& get_config();

		// Set the base_url automatically if none was provided
		if (empty($this->config['base_url']))
		{
			if (isset($_SERVER['HTTP_HOST']))
			{
				if (strpos($_SERVER['HTTP_HOST'], ':') !== FALSE)
				{
					$server_addr = '['.$_SERVER['HTTP_HOST'].']';
				}
				else
				{
					$server_addr = $_SERVER['HTTP_HOST'];
				}

				$base_url = (is_https() ? 'https' : 'http').'://'.$server_addr
					.substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], basename($_SERVER['SCRIPT_FILENAME'])));
			}
			else
			{
				$base_url = 'http://localhost/';
			}

			$this->set_item('base_url', $base_url);
		}

		// Added by Go Frendi
		if(CMS_SUBSITE != '' && !USE_SUBDOMAIN){
			$subsite_signature = 'site-'.CMS_SUBSITE;
			$index_page = $this->config['index_page'];
			if($index_page != ''){
				$this->set_item('index_page', $index_page.'/'.$subsite_signature);
			}else{
				$this->set_item('index_page', $subsite_signature);
			}
		}

		log_message('info', 'MY_Config Class Initialized');
	}

    public function load($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
	{
		$file = ($file === '') ? 'config' : str_replace('.php', '', $file);
		$loaded = FALSE;

		foreach ($this->_config_paths as $path)
		{
            require_once(APPPATH.'core/config_location.php');
            $suggested_location = get_config_location($file); 

			foreach (array($file, $suggested_location) as $location)
			{
				$file_path = $path.'config/'.$location.'.php';
				if (in_array($file_path, $this->is_loaded, TRUE))
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
					$this->config[$file] = isset($this->config[$file])
						? array_merge($this->config[$file], $config)
						: $config;
				}
				else
				{
					$this->config = array_merge($this->config, $config);
				}

				$this->is_loaded[] = $file_path;
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
