<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<h4>Generate Project</h4>
<div class="form form-inline well" style="margin-bottom:20px;">
    <label>Choose a project </label>
    <select id="project_list" class="form-control">
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
    <a href="<?php echo site_url('nordrassil/data/nds/project/add'); ?>" class="btn btn-success">Make a new Project</a>
</div>
<div id="status" class="alert alert-info"><b>Hint : </b>Choose project and generate</div>

<h4>Data</h4>
<?php echo $content; ?>

<script type="text/javascript">
    var GENERATOR_PATH = <?php echo json_encode($project_generator_path); ?>;
    $(document).ready(function(){
        $('#btn_generate').click(function(){
            var project_id = $('#project_list').val();
            var url = GENERATOR_PATH[project_id];
            $('#status').html('Processing ..');
            $('#status').removeClass('alert-info alert-success alert-danger alert-warning');
            $('#status').addClass('alert-warning');
            $.ajax({
                'url' : url,
                'dataType' : 'json',
                'success': function(response){
                    if(response['success']){
                        $('#status').html('<b>Project has been Generated successfully</b> go to <a class="btn btn-default" href="{{ site_url }}main/module_management">Module Management</a> to activate the module');
                        $('#status').removeClass('alert-info alert-success alert-danger alert-warning');
                        $('#status').addClass('alert-success');
                    }else{
                        $('#status').html('<b>Something going wrong : </b><br />'+response['message']);
                        $('#status').removeClass('alert-info alert-success alert-danger alert-warning');
                        $('#status').addClass('alert-danger');
                    }
                },
                'error': function(){
                    $('#status').html('<b>AJAX Failed</b>');
                    $('#status').removeClass('alert-info alert-success alert-danger alert-action');
                    $('#status').addClass('alert-danger');
                }
            });
        });
    })
</script>
