<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
	.toc_group{
		float: left;
		width: 40%;
		min-width: 200px;
	}
</style>
<?php  
	echo '<base href="'.base_url().'" />';
	for($i=0; $i<count($toc); $i++){
		$group = $toc[$i];
		echo '<div class="toc_group">';
		echo '<b>';
		echo anchor(
				site_url($cms["module_path"]."/group/".$group['underscored_name']),
				$group['name']);
		echo '</b>';
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
		echo '</div>';
	}
	echo '<div style="clear:both;"></div>';
?>