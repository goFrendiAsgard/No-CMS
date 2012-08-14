<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
	.toc_group{
		float: left;
		width: 40%;
		min-width: 200px;
	}
</style>
<?php  
	function create_div($html){
		echo '<div class="toc_group">';
		echo $html;
		echo '</div>';
	}

	$div_count = 2;	
	echo '<base href="'.base_url().'" />';
	
	// calculate how many topics per div
	$topic_count = 0;
	foreach($toc as $group){
		$topic_count += $group['topic_count'];
	}
	$topic_per_div = round($topic_count/2);
	
	// make html
	$html = "";
	$limit = $topic_per_div;
	$topic_count = 0;
	foreach($toc as $group){
		// group
		$html .= '<b>';
		$html .= anchor(
			site_url($cms["module_path"]."/help/group/".$group['url']),
			$group['name']);
		$html .= '</b>';
		// topic
		$html .= '<ul>';
		foreach($group['topics'] as $topic){
			$html.= '<li>';
			$html.= anchor(
					site_url($cms["module_path"]."/help/topic/".$topic['url']),
					$topic['title']);
			$html.= '</li>';
		}
		$html .= '</ul>';	

		$topic_count += $group['topic_count'];
		if($topic_count>$limit){
			create_div($html);
			$topic_count = 0;
			$html = '';	
		}
	}
	if($html != ''){
		create_div($html);
	}
	echo '<div style="clear:both;"></div>';
?>