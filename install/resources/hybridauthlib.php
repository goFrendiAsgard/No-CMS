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
				"enabled" => {{ open_id_enabled }}
			),

			"Yahoo" => array (
				"enabled" => {{ yahoo_enabled }},
				"keys"    => array ( "id" => "{{ yahoo_app_id }}", "secret" => "{{ yahoo_app_secret }}" ),
			),

			"AOL"  => array (
				"enabled" => {{ aol_enabled }}
			),

			"Google" => array (
				"enabled" => {{ google_enabled }},
				"keys"    => array ( "id" => "{{ google_app_id }}", "secret" => "{{ google_app_secret }}" ),
			),

			"Facebook" => array (
				"enabled" => {{ facebook_enabled }},
				"keys"    => array ( "id" => "{{ facebook_app_id }}", "secret" => "{{ facebook_app_secret }}" ),
			),

			"Twitter" => array (
				"enabled" => {{ twitter_enabled }},
				"keys"    => array ( "key" => "{{ twitter_app_key }}", "secret" => "{{ twitter_app_secret }}" )
			),

			// windows live
			"Live" => array (
				"enabled" => {{ windows_live_enabled }},
				"keys"    => array ( "id" => "{{ windows_live_app_id }}", "secret" => "{{ windows_live_app_secret }}" )
			),

			"MySpace" => array (
				"enabled" => {{ myspace_enabled }},
				"keys"    => array ( "key" => "{{ myspace_app_key }}", "secret" => "{{ myspace_app_secret }}" )
			),

			"LinkedIn" => array (
				"enabled" => {{ linkedin_enabled }},
				"keys"    => array ( "key" => "{{ linkedin_app_key }}", "secret" => "{{ linkedin_app_secret }}" )
			),

			"Foursquare" => array (
				"enabled" => {{ foursquare_enabled }},
				"keys"    => array ( "id" => "{{ foursquare_app_id }}", "secret" => "{{ foursquare_app_secret }}" )
			),
		),

		// if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
		"debug_mode" => (ENVIRONMENT == 'development'),

		"debug_file" => APPPATH.'/logs/hybridauth.log',
	);


/* End of file hybridauthlib.php */
/* Location: ./application/config/hybridauthlib.php */
