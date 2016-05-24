<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css">
    #textarea-seed{
        font-family:courier;
        font-size:small;
    }
    #status{
        padding: 10px;
        margin-top: 10px;
    }
</style>
<h4>Generate Project</h4>
<div class="form form-inline well" style="margin-bottom:20px;">
    <label>Choose a project </label> &nbsp;
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
    </select> &nbsp;
    and &nbsp;
    <input id="btn_generate" type="button" value="Generate" class="btn btn-primary"/> &nbsp;
    or &nbsp;
    <a href="{{ module_site_url }}manage_project/index/add" class="btn btn-success">Make a new Project</a>
    <div id="status" class="alert-info"><b>Hint : </b>Choose a project and generate</div>
</div>

<h4>Manage Project (Manage existing project/template)</h4>
<?php echo $content; ?>

<h4>Import Project (Paste project JSON-seed and import)</h4>
<form method="post" action="{{ module_site_url }}nordrassil/import">
    <textarea id="textarea-seed" name="seed" class="form-control"></textarea><br />
    <button class="btn btn-primary"><i class="glyphicon glyphicon-import"></i> Import</button>
</form>

<script type="text/javascript" src="{{ BASE_URL }}assets/nocms/js/jquery.autosize.js"></script>
<script type="text/javascript">
    var GENERATOR_PATH = <?php echo json_encode($project_generator_path); ?>;
    $(document).ready(function(){
        $('#textarea-seed').autosize();
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
                        $('#status').html('<b>Something goes wrong : </b><br />'+response['message']);
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
