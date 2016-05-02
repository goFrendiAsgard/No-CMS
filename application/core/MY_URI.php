<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_URI extends CI_URI {

	// this one should be overridden
	protected function _parse_request_uri()
	{
		if ( ! isset($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME']))
		{
			return '';
		}

		// additional code by Go Frendi. This is necessary to make request_uri preceed by '/site-*' loaded correctly
		$request_uri = $_SERVER['REQUEST_URI'];
		if(CMS_SUBSITE != ''){
			$subsite_signature = '/site-'.CMS_SUBSITE;
			$script_name = $_SERVER['SCRIPT_NAME'];
			$folder_name = substr($script_name, 0, strlen($script_name)-strlen('/index.php'));
			if(strpos($request_uri, $subsite_signature) !== FALSE){
				$request_uri = substr($request_uri, strlen($folder_name . $subsite_signature));
			}
			// request_uri should start with '/'
			if(strlen($request_uri) == 0 || $request_uri[0] != '/'){
				$request_uri = '/'.$request_uri;
			}
		}

		$uri = parse_url($request_uri);
		$query = isset($uri['query']) ? $uri['query'] : '';
		$uri = isset($uri['path']) ? $uri['path'] : '';

		if (isset($_SERVER['SCRIPT_NAME'][0]))
		{
			if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0)
			{
				$uri = (string) substr($uri, strlen($_SERVER['SCRIPT_NAME']));
			}
			elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0)
			{
				$uri = (string) substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
			}
		}

		// This section ensures that even on servers that require the URI to be in the query string (Nginx) a correct
		// URI is found, and also fixes the QUERY_STRING server var and $_GET array.
		if (trim($uri, '/') === '' && strncmp($query, '/', 1) === 0)
		{
			$query = explode('?', $query, 2);
			$uri = $query[0];
			$_SERVER['QUERY_STRING'] = isset($query[1]) ? $query[1] : '';
		}
		else
		{
			$_SERVER['QUERY_STRING'] = $query;
		}

		parse_str($_SERVER['QUERY_STRING'], $_GET);

		if ($uri === '/' OR $uri === '')
		{
			return '/';
		}

		// Do some final cleaning of the URI and return it
		return $this->_remove_relative_directory($uri);
	}

}
