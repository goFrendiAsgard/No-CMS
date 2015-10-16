<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

$auth_enable_facebook         = FALSE;
$auth_facebook_app_id         = '';
$auth_facebook_app_secret     = '';
$auth_enable_twitter          = FALSE;
$auth_twitter_app_key         = '';
$auth_twitter_app_secret      = '';
$auth_enable_google           = FALSE;
$auth_google_app_id           = '';
$auth_google_app_secret       = '';
$auth_enable_yahoo            = FALSE;
$auth_yahoo_app_id            = '';
$auth_yahoo_app_secret        = '';
$auth_enable_linkedin         = FALSE;
$auth_linkedin_app_key        = '';
$auth_linkedin_app_secret     = '';
$auth_enable_myspace          = FALSE;
$auth_myspace_app_key         = '';
$auth_myspace_app_secret      = '';
$auth_enable_foursquare       = FALSE;
$auth_foursquare_app_id       = '';
$auth_foursquare_app_secret   = '';
$auth_enable_windows_live     = FALSE;
$auth_windows_live_app_id     = '';
$auth_windows_live_app_secret = '';
$auth_enable_open_id          = FALSE;
$auth_enable_aol              = FALSE;

$config =
	array(
		// set on "base_url" the relative url that point to HybridAuth Endpoint
		'base_url' => '/main/hauth/endpoint',

		"providers" => array (
			// openid providers
			"OpenID" => array (
				"enabled" => $auth_enable_open_id,
			),

			"Yahoo" => array (
				"enabled" => $auth_enable_yahoo,
				"keys"    => array ( "id" => $auth_yahoo_app_id, "secret" => $auth_yahoo_app_secret ),
			),

			"AOL"  => array (
				"enabled" => $auth_enable_aol,
			),

			"Google" => array (
				"enabled" => $auth_enable_google,
				"keys"    => array ( "id" => $auth_google_app_id, "secret" => $auth_google_app_secret ),
			),

			"Facebook" => array (
				"enabled" => $auth_enable_facebook,
				"keys"    => array ( "id" => $auth_facebook_app_id, "secret" => $auth_facebook_app_secret ),
			),

			"Twitter" => array (
				"enabled" => $auth_enable_twitter,
				"keys"    => array ( "key" => $auth_twitter_app_key, "secret" => $auth_twitter_app_secret )
			),

			// windows live
			"Live" => array (
				"enabled" => $auth_enable_windows_live,
				"keys"    => array ( "id" => $auth_windows_live_app_id, "secret" => $auth_windows_live_app_secret )
			),

			"MySpace" => array (
				"enabled" => $auth_enable_myspace,
				"keys"    => array ( "key" => $auth_myspace_app_key, "secret" => $auth_myspace_app_secret )
			),

			"LinkedIn" => array (
				"enabled" => $auth_enable_linkedin,
				"keys"    => array ( "key" => $auth_linkedin_app_key, "secret" => $auth_linkedin_app_secret )
			),

			"Foursquare" => array (
				"enabled" => $auth_enable_foursquare,
				"keys"    => array ( "id" => $auth_foursquare_app_id, "secret" => $auth_foursquare_app_secret )
			),
		),

		// if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
		"debug_mode" => (ERROR_REPORTING == 'production'),

		"debug_file" => APPPATH.'/logs/hybridauth.log',
	);


/* End of file hybridauthlib.php */
/* Location: ./application/config/hybridauthlib.php */
