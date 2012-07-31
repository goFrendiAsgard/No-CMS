<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

	include 'modules/'.$cms['module_path'].'/assets/toc_include.php';
	echo '<base href="'.base_url().'" />';
	echo '<h4>'.$content[0]['title'].'</h4>';
	echo $content[0]['content'];
?>