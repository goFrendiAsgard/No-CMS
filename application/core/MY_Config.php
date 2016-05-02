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

}
