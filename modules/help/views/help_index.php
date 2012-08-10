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
			site_url($cms["module_path"]."/group/".$group['url']),
			$group['name']);
		$html .= '</b>';
		// topic
		$html .= '<ul>';
		foreach($group['topics'] as $topic){
			$html.= '<li>';
			$html.= anchor(
					site_url($cms["module_path"]."/topic/".$topic['url']),
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
	
	/*
	$topic_per_div = round(count($toc)/2);
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
					site_url($cms["module_path"]."/group/".$group['url']),
					$group['name']);
			echo '</b>';
			$topics = $group['topics'];
			echo '<ul>';
			for($j=0; $j<count($topics); $j++){
				$topic = $topics[$j];
				echo '<li>';
				echo anchor(
					site_url($cms["module_path"]."/topic/".$topic['url']),
					$topic['title']);
				echo '</li>';
			}
			echo '</ul>';		
		}
		echo '</div>';
	}
	*/
	echo '<div style="clear:both;"></div>';
?>