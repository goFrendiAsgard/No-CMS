<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	include 'modules/'.$cms['module_path'].'/assets/toc_include.php';
	echo '<base href="'.base_url().'" />';
	$group = $toc[0];
	echo '<b>'.$group['name'].'</b>';
	if($allow_edit_group){
		echo '&nbsp;';
		echo anchor(
				site_url($cms["module_path"]."/help/data_group/edit/".$group['id']), 
				'edit', 
				array('class'=>'btn'));
	}
	echo '<p>'.$group['content'].'</p>';
	$topics = $group['topics'];
	echo '<ul>';
	for($j=0; $j<count($topics); $j++){
		$topic = $topics[$j];
		echo '<li>';
		echo anchor(
			site_url($cms["module_path"]."/help/topic/".$topic['url']),
			$topic['title']);
		if($allow_edit_topic){
			echo '&nbsp;';
			echo anchor(
					site_url($cms["module_path"]."/help/data_topic/edit/".$topic['id']),
					'edit',
					array('class'=>'btn'));
		}
		echo '</li>';
	}
	echo '</ul>';
?>