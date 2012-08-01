<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
	.toc_group{
		float: left;
		width: 40%;
		min-width: 200px;
	}
</style>
<?php  
	$div_count = 2;
	$topic_per_div = round(count($toc)/2);
	echo '<base href="'.base_url().'" />';
	for($div_index=0; $div_index<$div_count; $div_index++){
		$bottom_limit = $div_index * $topic_per_div;
		$up_limit = ($div_index+1) * $topic_per_div;
		if($up_limit>count($toc)){
			$up_limit = count($toc);
		}
		echo '<div class="toc_group">';
		for($i=$bottom_limit; $i<$up_limit; $i++){
			$group = $toc[$i];		
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
		}
		echo '</div>';
	}
	echo '<div style="clear:both;"></div>';
?>