<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<h4>Data</h4>
<?php echo $content; ?>
<h4>Generate Project</h4>
<label>Project: </label>
<select id="project_list">
<?php
	$project_generator_path = array(); 
	foreach($projects as $project){
		$project_id = $project->project_id;
		$project_name = $project->name;
		$generator_path = $project->generator_path;
		$project_generator_path[$project_id] = site_url($generator_path).'/'.$project_id;
		echo '<option value="'.$project_id.'">'.$project_name.'</option>';
	}
?>	
</select>
<input id="btn_generate" type="button" value="Generate" class="btn btn-primary"/>
or
<a href="<?php echo site_url('nordrassil/data/nds/project/add'); ?>" class="btn">Make a new Project</a>
<div id="status" class="alert alert-info"><b>Hint : </b>Choose project and generate</div>

<script type="text/javascript">
	var GENERATOR_PATH = <?php echo json_encode($project_generator_path); ?>;
	$(document).ready(function(){
		$('#btn_generate').click(function(){
			var project_id = $('#project_list').val();
			var url = GENERATOR_PATH[project_id];
			$('#status').html('Processing ..');
			$('#status').removeClass('alert-info alert-success alert-error alert-action');
			$('#status').addClass('alert-action');
			$.ajax({
				'url' : url,
				'dataType' : 'json',
				'success': function(response){
					if(response['success']){
						$('#status').html('<b>Project has been Generated successfully</b>');
						$('#status').removeClass('alert-info alert-success alert-error alert-action');
						$('#status').addClass('alert-success');
					}else{
						$('#status').html('<b>Something going wrong : </b><br />'+response['message']);
						$('#status').removeClass('alert-info alert-success alert-error alert-action');
						$('#status').addClass('alert-error');
					}
				},
				'error': function(){
					$('#status').html('<b>AJAX Failed</b>');
					$('#status').removeClass('alert-info alert-success alert-error alert-action');
					$('#status').addClass('alert-error');
				}
			});
		});
	})
</script>
