<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2016, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2016, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */

/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 */
	define('ERROR_REPORTING', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */
switch (ERROR_REPORTING)
{
	case 'development':
		error_reporting(-1);
		ini_set('display_errors', 1);
	break;

	case 'testing':
	case 'production':
		ini_set('display_errors', 0);
		if (version_compare(PHP_VERSION, '5.3', '>='))
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
		}
		else
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
		}
	break;

	default:
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'The application error reporting is not set correctly.';
		exit(1); // EXIT_ERROR
}

/*
 *---------------------------------------------------------------
 * SYSTEM DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "system" directory.
 * Set the path if it is not in the same directory as this file.
 */
	$system_path = 'system';

/*
 *---------------------------------------------------------------
 * APPLICATION DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * If you want this front controller to use a different "application"
 * directory than the default one you can set its name here. The directory
 * can also be renamed or relocated anywhere on your server. If you do,
 * use an absolute (full) server path.
 * For more info please see the user guide:
 *
 * https://codeigniter.com/user_guide/general/managing_apps.html
 *
 * NO TRAILING SLASH!
 */
	$application_folder = 'application';

/*
 *---------------------------------------------------------------
 * VIEW DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * If you want to move the view directory out of the application
 * directory, set the path to it here. The directory can be renamed
 * and relocated anywhere on your server. If blank, it will default
 * to the standard location inside your application directory.
 * If you do move this, use an absolute (full) server path.
 *
 * NO TRAILING SLASH!
 */
	$view_folder = '';


/*
 * --------------------------------------------------------------------
 * DEFAULT CONTROLLER
 * --------------------------------------------------------------------
 *
 * Normally you will set your default controller in the routes.php file.
 * You can, however, force a custom routing by hard-coding a
 * specific controller class/function here. For most applications, you
 * WILL NOT set your routing here, but it's an option for those
 * special instances where you might want to override the standard
 * routing in a specific front controller that shares a common CI installation.
 *
 * IMPORTANT: If you set the routing here, NO OTHER controller will be
 * callable. In essence, this preference limits your application to ONE
 * specific controller. Leave the function name blank if you need
 * to call functions dynamically via the URI.
 *
 * Un-comment the $routing array below to use this feature
 */
	// The directory name, relative to the "controllers" directory.  Leave blank
	// if your controller is not in a sub-directory within the "controllers" one
	// $routing['directory'] = '';

	// The controller class file name.  Example:  mycontroller
	// $routing['controller'] = '';

	// The controller function you wish to be called.
	// $routing['function']	= '';


/*
 * -------------------------------------------------------------------
 *  CUSTOM CONFIG VALUES
 * -------------------------------------------------------------------
 *
 * The $assign_to_config array below will be passed dynamically to the
 * config class when initialized. This allows you to set custom config
 * items or override any default config values found in the config.php file.
 * This can be handy as it permits you to share one application between
 * multiple front controller files, with each file containing different
 * config values.
 *
 * Un-comment the $assign_to_config array below to use this feature
 */
	// $assign_to_config['name_of_config_item'] = 'value of config item';



// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------------

/*
 * ---------------------------------------------------------------
 *  NO CMS PROGRAM
 * ---------------------------------------------------------------
 */

function is_ip_address($address)
{
    // IP address consists of 4 parts, separated by dot. Every part is integer,
    // otherwise, it is not IP address
    if(preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/", $address))
    {
        $parts = explode('.', $address);
        foreach($parts as $ip_parts)
        {
            // each part of IP address must range from 0 to 255, otherwise it is not IP address
            if(intval($ip_parts) > 255 || intval($ip_parts) < 0) return FALSE;
        }
        return TRUE;
    }
    return FALSE;
}

function domain($domain)
{
    // is $hostname defined in hostname.php
    if(file_exists('hostname.php')){
        $hostname = NULL;
        include('hostname.php');
        if($hostname != NULL){
            return $hostname;
        }
    }
    // determine the domain heuristically
    $domain_part = explode('.', $domain);
    if(strtolower($domain_part[count($domain_part)-1]) == 'localhost'){
        return 'localhost';
    }
    if(preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $domain, $matches))
    {
        return $matches['domain'];
    } else {
        return $domain;
    }
}

function full_url($s, $use_forwarded_host=false)
{
    $ssl        = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
    $sp         = strtolower($s['SERVER_PROTOCOL']);
    $protocol   = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port       = $s['SERVER_PORT'];
    $port       = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
    $host       = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
    $host       = isset($host) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host . $s['REQUEST_URI'];
}

// define ENVIRONMENT, CMS_SUBSITE and USE_SUBDOMAIN contants
$ENVIRONMENT        = 'first-time';
$CMS_SUBSITE        = '';
$USE_SUBDOMAIN      = FALSE;
$INVALID_SUBSITE    = FALSE;
$USE_ALIAS          = FALSE;
$available_site     = array();
if(file_exists('./'.$application_folder.'/config/main/database.php')){
    // multisite, can use GET or subdomain
    // create site.php
    if(!file_exists('./site.php')){
        $data = '<?php'.PHP_EOL;
        @file_put_contents('./site.php', $data);
    }
    if(function_exists('opcache_invalidate')){
        opcache_invalidate('./site.php');
    }
    require_once('./site.php');
    if(isset($available_site) && is_array($available_site)){
        if(isset($_GET['__cms_subsite']) && $_GET['__cms_subsite']!== NULL){
            $CMS_SUBSITE = $_GET['__cms_subsite'];
        }else{
            $actual_host_name   = $_SERVER['HTTP_HOST'];
            $address            = full_url($_SERVER);
            $parsed_url         = parse_url($address);
            $check              = is_ip_address($parsed_url['host']);
            $stripped_host_name = $parsed_url['host'];
            if ($check == FALSE){
                $stripped_host_name = $stripped_host_name == '' ?
                    domain($stripped_host_name) : domain($address);
            }

            $actual_host_name_parts   = explode('.', $actual_host_name);
            $stripped_host_name_parts = explode('.', $stripped_host_name);
            // if there is an alias defined
            if(array_key_exists($actual_host_name, $site_alias) && $site_alias[$actual_host_name] != ''){
                $CMS_SUBSITE   = $site_alias[$actual_host_name];
                $USE_ALIAS     = TRUE;
            }
            // If there is subdomain, subsite determine from subdomain.
            else if (strlen($actual_host_name)> 0 && ( count($actual_host_name_parts) > count($stripped_host_name_parts))
            ){
                $CMS_SUBSITE = $actual_host_name_parts[0];
                $USE_SUBDOMAIN = TRUE;
            }
        }
        // define cms_subsite and wether it is valid or not
        $INVALID_SUBSITE = ($CMS_SUBSITE == '' || in_array($CMS_SUBSITE, $available_site)) ? FALSE : TRUE;
    }else{
        $INVALID_SUBSITE    = FALSE;
        $USE_SUBDOMAIN      = FALSE;
    }
    // change the environment based on multisite
    $ENVIRONMENT = $CMS_SUBSITE !='' ? 'site-'.$CMS_SUBSITE : 'main';
}
define('ENVIRONMENT',       $ENVIRONMENT);
define('CMS_SUBSITE',       $CMS_SUBSITE);
define('USE_SUBDOMAIN',     $USE_SUBDOMAIN);
define('INVALID_SUBSITE',   $INVALID_SUBSITE);
define('USE_ALIAS',         $USE_ALIAS);

// No-CMS privilege constants
define('PRIV_EVERYONE',             1);
define('PRIV_NOT_AUTHENTICATED',    2);
define('PRIV_AUTHENTICATED',        3);
define('PRIV_AUTHORIZED',           4);
define('PRIV_EXCLUSIVE_AUTHORIZED', 5);

// is subsite is invalid then redirect to the main website.
if( INVALID_SUBSITE || (CMS_SUBSITE != '' && !is_dir('./'.$application_folder.'/config/site-'.CMS_SUBSITE)) ){
    $address = full_url($_SERVER);
    // determine redirection url
    if(USE_SUBDOMAIN){
        $address_part  = explode('.', $address);
        // get the protocol first
        $protocol      = $address_part[0];
        $protocol_part = explode('://', $protocol);
        $protocol      = $protocol_part[0].'://';
        // remove subdomain
        $address_part  = array_slice($address_part, 1);
        $address       = implode('.', $address_part);
        // add the protocol again
        $address       = $protocol.$address;
    }else{
        $address_part     = explode('/', $address);
        $new_address_part = array();
        for($i=0; $i<count($address_part); $i++){
            // remove site-subsite part
            if($address_part[$i] == 'site-'.CMS_SUBSITE){
                break;
            }
            $new_address_part[] = $address_part[$i];
        }
        $address = implode('/', $new_address_part);
    }
    // redirect location
    header('Location: '.$address);
    exit(1); // EXIT_* constants not yet defined; 1 is EXIT_ERROR, a generic error.
}

// PHP 5.3 ask for timezone, and throw a warning whenever it is not available
// so, just give this one :)
$timezone = @date_default_timezone_get();
if (!isset($timezone) || $timezone == '') {
    $timezone = @ini_get('date.timezone');
}
if (!isset($timezone) || $timezone == '') {
    $timezone = 'UTC';
}
date_default_timezone_set($timezone);

$session_save_path = session_save_path();
if($session_save_path == ''){
    // in case of session_save_path is not defined
    $session_save_path = getcwd() . DIRECTORY_SEPARATOR . 'session';
    // make .htaccess
    if(!file_exists($session_save_path.DIRECTORY_SEPARATOR.'session/.htaccess')){
        mkdir($session_save_path);
        file_put_contents($session_save_path.DIRECTORY_SEPARATOR.'session/.htaccess',
            '<IfModule authz_core_module>'.PHP_EOL.
            '   Require all denied'.PHP_EOL.
            '</IfModule>'.PHP_EOL.
            '<IfModule !authz_core_module>'.PHP_EOL.
            '   Deny from all'.PHP_EOL.
            '</IfModule>');
    }
    session_save_path($session_save_path);
}


/*
 * ---------------------------------------------------------------
 *  END OF NO CMS PROGRAM
 * ---------------------------------------------------------------
 */

/*
 * ---------------------------------------------------------------
 *  Resolve the system path for increased reliability
 * ---------------------------------------------------------------
 */

	// Set the current directory correctly for CLI requests
	if (defined('STDIN'))
	{
		chdir(dirname(__FILE__));
	}

	if (($_temp = realpath($system_path)) !== FALSE)
	{
		$system_path = $_temp.DIRECTORY_SEPARATOR;
	}
	else
	{
		// Ensure there's a trailing slash
		$system_path = strtr(
			rtrim($system_path, '/\\'),
			'/\\',
			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
		).DIRECTORY_SEPARATOR;
	}

	// Is the system path correct?
	if ( ! is_dir($system_path))
	{
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'Your system folder path does not appear to be set correctly. Please open the following file and correct this: '.pathinfo(__FILE__, PATHINFO_BASENAME);
		exit(3); // EXIT_CONFIG
	}

/*
 * -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
 * -------------------------------------------------------------------
 */
	// The name of THIS file
	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

	// Path to the system directory
	define('BASEPATH', $system_path);

	// Path to the front controller (this file) directory
	define('FCPATH', dirname(__FILE__).DIRECTORY_SEPARATOR);

	// Name of the "system" directory
	define('SYSDIR', basename(BASEPATH));

	// The path to the "application" directory
	if (is_dir($application_folder))
	{
		if (($_temp = realpath($application_folder)) !== FALSE)
		{
			$application_folder = $_temp;
		}
		else
		{
			$application_folder = strtr(
				rtrim($application_folder, '/\\'),
				'/\\',
				DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
			);
		}
	}
	elseif (is_dir(BASEPATH.$application_folder.DIRECTORY_SEPARATOR))
	{
		$application_folder = BASEPATH.strtr(
			trim($application_folder, '/\\'),
			'/\\',
			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
		);
	}
	else
	{
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'Your application folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
		exit(3); // EXIT_CONFIG
	}

	define('APPPATH', $application_folder.DIRECTORY_SEPARATOR);

	// The path to the "views" directory
	if ( ! isset($view_folder[0]) && is_dir(APPPATH.'views'.DIRECTORY_SEPARATOR))
	{
		$view_folder = APPPATH.'views';
	}
	elseif (is_dir($view_folder))
	{
		if (($_temp = realpath($view_folder)) !== FALSE)
		{
			$view_folder = $_temp;
		}
		else
		{
			$view_folder = strtr(
				rtrim($view_folder, '/\\'),
				'/\\',
				DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
			);
		}
	}
	elseif (is_dir(APPPATH.$view_folder.DIRECTORY_SEPARATOR))
	{
		$view_folder = APPPATH.strtr(
			trim($view_folder, '/\\'),
			'/\\',
			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
		);
	}
	else
	{
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'Your view folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
		exit(3); // EXIT_CONFIG
	}

	define('VIEWPATH', $view_folder.DIRECTORY_SEPARATOR);

/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 *
 * And away we go...
 */
require_once BASEPATH.'core/CodeIgniter.php';
