<?php

/** This file is part of KCFinder project
  *
  *      @desc Base configuration file
  *   @package KCFinder
  *   @version 2.51
  *    @author Pavel Tzonkov <pavelc@users.sourceforge.net>
  * @copyright 2010, 2011 KCFinder Project
  *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
  *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
  *      @link http://kcfinder.sunhater.com
  */

// IMPORTANT!!! Do not remove uncommented settings in this file even if
// you are using session configuration.
// See http://kcfinder.sunhater.com/install for setting descriptions

$_FCPATH = '{{ FCPATH }}';

// define basepath
if(!defined('BASEPATH')){ define('BASEPATH',''); }

// load secret information
$info_exists = FALSE;
if(isset($_COOKIE['__secret_code'])){
    $secret_code = $_COOKIE['__secret_code'];
    $file_name   = $_FCPATH.'application/config/tmp/_secret_'.$secret_code.'.php';
    if(file_exists($file_name)){
        include($file_name);
        $secret         = json_decode($secret, TRUE);
        // get base url
        $_BASE_URL      = $secret['__cms_base_url'];
        // get subsite
        $_cms_subsite   = $secret['__cms_subsite'];
        // get user_id
        $_cms_user_id   = $secret['__cms_user_id'];
        $info_exists    = TRUE;
    }
}

// provide detail information if 
if(!$info_exists){
    $_BASE_URL      = '{{ BASE_URL }}';
    $_cms_subsite    = '';
    $_cms_user_id    = NULL;
}

// determine user_dir
$_user_dir = $_cms_user_id !== NULL ?  $_cms_user_id : 'no_user';
$_user_dir = $_cms_subsite == ''? '/main-'.$_user_dir : '/site-'.$_cms_subsite.'-'.$_user_id;

if(!is_dir($_FCPATH."assets/kcfinder/upload".$_user_dir)){
    mkdir($_FCPATH."assets/kcfinder/upload".$_user_dir);
    chmod($_FCPATH."assets/kcfinder/upload".$_user_dir, 0777);
}

$_CONFIG = array(
    'disabled' => $_cms_user_id === NULL,
    'denyZipDownload' => false,
    'denyUpdateCheck' => false,
    'denyExtensionRename' => false,

    'theme' => "oxygen",

    'uploadURL' => $_BASE_URL."assets/kcfinder/upload".$_user_dir,
    'uploadDir' => $_FCPATH."assets/kcfinder/upload".$_user_dir,

    'dirPerms' => 0755,
    'filePerms' => 0644,

    'access' => array(

        'files' => array(
            'upload' => true,
            'delete' => true,
            'copy' => true,
            'move' => true,
            'rename' => true
        ),

        'dirs' => array(
            'create' => true,
            'delete' => true,
            'rename' => true
        )
    ),

    'deniedExts' => "exe com msi bat php phps phtml php3 php4 cgi pl",

    'types' => array(

        // CKEditor & FCKEditor types
        'files'   =>  "",
        'flash'   =>  "swf",
        'images'  =>  "*img",

        // TinyMCE types
        'file'    =>  "",
        'media'   =>  "swf flv avi mpg mpeg qt mov wmv asf rm",
        'image'   =>  "*img",
    ),

    'filenameChangeChars' => array(/*
        ' ' => "_",
        ':' => "."
    */),

    'dirnameChangeChars' => array(/*
        ' ' => "_",
        ':' => "."
    */),

    'mime_magic' => "",

    'maxImageWidth' => 0,
    'maxImageHeight' => 0,

    'thumbWidth' => 100,
    'thumbHeight' => 100,

    'thumbsDir' => ".thumbs",

    'jpegQuality' => 90,

    'cookieDomain' => "",
    'cookiePath' => "",
    'cookiePrefix' => 'KCFINDER_',

    // THE FOLLOWING SETTINGS CANNOT BE OVERRIDED WITH SESSION CONFIGURATION
    '_check4htaccess' => FALSE,
    //'_tinyMCEPath' => "/tiny_mce",

    '_sessionVar' => &$_SESSION['KCFINDER'],
    //'_sessionLifetime' => 30,
    //'_sessionDir' => "/full/directory/path",

    //'_sessionDomain' => ".mysite.com",
    //'_sessionPath' => "/my/path",
);

?>