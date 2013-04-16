<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

$config =
	array(
		// set on "base_url" the relative url that point to HybridAuth Endpoint
		'base_url' => '/main/hauth/endpoint',
		
		'proxy' => '',

		"providers" => array (
			// openid providers
			"OpenID" => array (
				"enabled" => FALSE
			),

			"Yahoo" => array (
				"enabled" => FALSE,
				"keys"    => array ( "id" => "", "secret" => "" ),
			),

			"AOL"  => array (
				"enabled" => FALSE
			),

			"Google" => array (
				"enabled" => FALSE,
				"keys"    => array ( "id" => "", "secret" => "" ),
			),

			"Facebook" => array (
				"enabled" => TRUE,
				"keys"    => array ( "id" => "528252883865751", "secret" => "8c12c30a60301758916234aa761d919e" ),
			),

			"Twitter" => array (
				"enabled" => TRUE,
				"keys"    => array ( "key" => "IyBxxOyJXHGzkg9yNAHErA", "secret" => "xXwrRiTHXFTGnaDxjywMLxOMrwOHvReBsTjWgDFxJs" )
			),

			// windows live
			"Live" => array (
				"enabled" => FALSE,
				"keys"    => array ( "id" => "", "secret" => "" )
			),

			"MySpace" => array (
				"enabled" => FALSE,
				"keys"    => array ( "key" => "", "secret" => "" )
			),

			"LinkedIn" => array (
				"enabled" => FALSE,
				"keys"    => array ( "key" => "", "secret" => "" )
			),

			"Foursquare" => array (
				"enabled" => FALSE,
				"keys"    => array ( "id" => "", "secret" => "" )
			),
		),

		// if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
		"debug_mode" => (ENVIRONMENT == 'development'),

		"debug_file" => APPPATH.'/logs/hybridauth.log',
	);


/* End of file hybridauthlib.php */
/* Location: ./application/config/hybridauthlib.php */
