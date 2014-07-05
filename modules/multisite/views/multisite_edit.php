<?php defined('BASEPATH') OR exit('No direct script access allowed');
    $asset = new CMS_Asset();
    $asset->add_cms_css('grocery_crud/css/jquery_plugins/chosen/chosen.css');
    $asset->add_cms_css('grocery_crud/themes/flexigrid/css/flexigrid.css');
    echo $asset->compile_css();
?>
<style type="text/css">
    textarea#description{
        resize: none;
        word-wrap: no-wrap;
        white-space: pre-wrap;
        overflow-x: auto;
        width:95%;
        min-width: 385px!important;
        min-height: 75px!important;
        margin-top: 10px!important;
        font-family: Courier;
        font-size: small;
    }
    .chzn-container-multi .chzn-choices .search-field input{
        height:28px;
    }
</style>
<?php
    echo '<h3> Edit '.$name.' sub-site</h3>';
    if($save){
        echo '<div class="alert alert-success">Changes has been saved</div>';
    }
    echo form_open_multipart($edit_url, 'class="form form-horizontal"');

    echo '<div class="form-group">';
    echo form_label('Logo', ' for="" class="control-label col-sm-4');
    echo '<div class="col-sm-8">';
    if($logo === NULL || $logo == ''){
        $image_path = base_url('modules/{{ module_path }}/assets/images/default-logo.png');
    }else{
        $image_path = base_url('modules/{{ module_path }}/assets/uploads/'.$logo);
    }
    echo '<img src="'.$image_path.'" style="max-height:64px; max-width:64px;" />';
    echo '<input name="logo" type="file" />';
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group">';
    echo '<div class="col-sm-offset-4 col-sm-8">';
    echo form_checkbox('use_subdomain','True',$use_subdomain==1, 'id="use_subdomain"');
    echo form_label('Use Subdomain', ' for="" class="control-label');
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group">';
    echo form_label('Aliases', ' for="" class="control-label col-sm-4');
    echo '<div class="col-sm-8">';
    echo form_input('aliases', $aliases,
        'id="aliases" placeholder="Aliases (comma separated)" class="form-control"');
    echo '</div>';
    echo '</div>';
    if($is_super_admin){
        echo '<div class="form-group">';
        echo form_label('Private Themes Allowed', ' for="" class="control-label col-sm-4');
        echo '<div class="col-sm-8">';
        echo form_dropdown('themes[]', $theme_list, $themes, ' multiple id="themes" placeholder="Allowed Themes" class="form-control chosen-multiple-select"');
        echo '</div>';
        echo '</div>';

        echo '<div class="form-group">';
        echo form_label('Private Modules Allowed', ' for="" class="control-label col-sm-4');
        echo '<div class="col-sm-8">';
        echo form_dropdown('modules[]', $module_list, $modules, ' multiple id="modules" placeholder="Allowed Themes" class="form-control chosen-multiple-select"');
        echo '</div>';
        echo '</div>';
    }

    echo '<div class="form-group">';
    echo form_label('Description', ' for="" class="control-label col-sm-4');
    echo '<div class="col-sm-8">';
    echo form_textarea('description', $description,
        'id="description" placeholder="Description" class="form-control"');
    echo '</div>';
    echo '</div>';


    echo '<div class="form-group"><div class="col-sm-offset-4 col-sm-8">';
    echo form_submit('btn_save', 'Save', 'class="btn btn-primary"');
    echo '</div></div>';
    echo form_close();
?>
<?php
    $asset->add_cms_js("nocms/js/jquery.autosize.js");
    $asset->add_cms_js("grocery_crud/js/jquery_plugins/jquery.chosen.min.js");
    $asset->add_cms_js("grocery_crud/js/jquery_plugins/config/jquery.chosen.config.js");
    echo $asset->compile_js();
?>
<script type="text/javascript">
    $(document).ready(function(){
        // make text area autosize
        $('textarea#description').autosize();
    })
</script>
</script>
