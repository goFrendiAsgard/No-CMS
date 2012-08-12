<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

	include 'modules/'.$cms['module_path'].'/assets/toc_include.php';
	echo '<base href="'.base_url().'" />';
	if($content["success"]){
		echo '<h4>'.$content['title'];
		if($allow_edit_topic){
			echo '&nbsp;';
			echo anchor(
					site_url($cms["module_path"]."/help/data_topic/edit/".$content['id']),
					'edit',
					array('class'=>'btn'));
		}
		echo '</h4>';	
		echo $content['content'];
	}else{
		echo "<h4>No Topic Found</h4>";
	}
?>