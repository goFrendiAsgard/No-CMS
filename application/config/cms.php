<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//created by gofrendi

//layouts
$config['site_title']	= 'Neo-CMS';
$config['site_slogan']  = 'Your web kickstart';
$config['site_footer']  = 'goFrendiAsgard &copy; 2011';
$config['site_theme']   = 'default';

//maximum navigation depth recursion
$config['max_menu_depth'] = 5;

//forgot password email template.
$config['cms_email_address'] = 'no-reply@Neo-CMS.com';
$config['cms_email_name'] = 'admin of Neo-CMS';
$config['cms_email_forgot_subject'] = 'Re-activate your account at Neo-CMS'; 
$config['cms_email_forgot_message'] = 
    'Dear, @realname<br />
     Click <a href="@activation_link">@activation_link</a> to reactivate your account'; 
