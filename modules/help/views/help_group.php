<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	include 'modules/'.$cms['module_path'].'/assets/toc_include.php';
	echo '<base href="'.base_url().'" />';
	$group = $toc[0];
	echo '<b>'.$group['name'].'</b>';
	echo '<p>'.$group['content'].'</p>';
	$topics = $group['topics'];
	echo '<ul>';
	for($j=0; $j<count($topics); $j++){
		$topic = $topics[$j];
		echo '<li>';
		echo anchor(
			site_url($cms["module_path"]."/topic/".$topic['underscored_title']),
			$topic['title']);
		echo '</li>';
	}
	echo '</ul>';
?>