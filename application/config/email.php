<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');  
/* 
| ------------------------------------------------------------------- 
| EMAIL CONFING 
| ------------------------------------------------------------------- 
| Configuration of outgoing mail server. 
| */   
$config['protocol']='smtp';  
$config['smtp_host']='ssl://smtp.googlemail.com';  
$config['smtp_port']='465';  
$config['smtp_timeout']='30';  
$config['smtp_user']='your_gmail_address@gmail.com';  
$config['smtp_pass']='your_password';  
$config['charset']='utf-8';  
$config['newline']="\r\n";  
  
/* End of file email.php */  
/* Location: ./system/application/config/email.php */  