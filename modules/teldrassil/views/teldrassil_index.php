<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<link href="{{ MODULE_BASE_URL }}assets/bootstrap-colorselector/lib/bootstrap-colorselector-0.2.0/css/bootstrap-colorselector.css" rel="stylesheet" />
<style type="text/css">
    <?php
        if($file_name == NULL){
            echo '.initially-hidden{display:none;}';
        }
    ?>
    .btn-colorselector, .color-btn{
        border:1px solid;
    }
</style>
<h3>Theme Generator</h3>
<form class="form row col-md-12" method="post" enctype="multipart/form-data">
    <div class="row">
        <?php if($generated){?>
        <div class="col-md-12 alert alert-info">
            Your theme has been generated. Click <a class="btn btn-default" href="{{ SITE_URL }}main/change_theme/<?php echo $theme_name; ?>">here</a> to use the theme.
        </div>
        <?php } ?>
        <div class="col-md-6">

            <div class="form-group">
                <label>Image</label>
                <?php
                    if($file_name != NULL){
                        echo '<img style="max-width:200px; margin:20px;" src="' .$url_name. '" /><br />';
                    }
                ?>
                <input class="form-control" type="file" name="file_name" /><br />
                <button class="btn btn-default" name="upload" value="TRUE">Upload</button>
            </div>

            <div class="form-group initially-hidden">
                <label>Theme Name</label>
                <input class="form-control" type="text" name="theme_name" value='<?php echo $theme_name; ?>' />
            </div>

            <div class="form-group initially-hidden">
                <label>Font</label>
                <select class="form-control" id="font-select" name="font">
                <?php
                    foreach($font_options as $option){
                        if($option == $font){
                            $selected = ' selected';
                        }else{
                            $selected = '';
                        }
                        echo '<option value="'.$option.'"'.$selected.'>' . $option . '</option>';
                    }
                ?>
                </select>
            </div>

        </div>
        <div class="col-md-6">
            <table class="table initially-hidden">
                <thead>
                    <tr>
                        <th>Color</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        for($i=0; $i<7; $i++){
                            echo '<tr>';
                            echo '<td><select id="color-select-'.$i.'" class="color-select" name="colors[]">';
                            foreach($color_options as $option){
                                if($option == $colors[$i]){
                                    $selected = ' selected';
                                }else{
                                    $selected = '';
                                }
                                echo '<option value="'.$option.'" data-color="#'.$option.'"'.$selected.'>#' . $option . '</option>';
                            }
                            echo '</select></td>';
                            echo '<td>'.$color_descriptions[$i].'</td>';
                            echo '</tr>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="form-group initially-hidden">
        <h4>Preview</h4>
        <iframe id="preview" style="width:100%; height:800px; border:none;">
        </iframe>
    </div>
    <div class="form-group initially-hidden">
        <button class="btn btn-primary" name="generate" value="generate">Generate</button>
    </div>
</form>
<script type="text/javascript" src="{{ MODULE_BASE_URL }}assets/bootstrap-colorselector/lib/bootstrap-colorselector-0.2.0/js/bootstrap-colorselector.js"></script>
<script type="text/javascript">

    function preview(){
        var url = "{{ MODULE_SITE_URL }}teldrassil/preview?font=" + $('#font-select').val();
        for(var i=0; i<7; i++){
            url += '&colors[]=' + $('#color-select-'+i).val();
        }
        if($( "#background_image:checked" ).length>0){
            url += '&use_background_image=TRUE';
        }
        $('#preview').attr('src', url);
    }
    $(document).ready(function(){
        $('select.color-select').colorselector();
    });

    preview();


    $('.color-select').change(function(event){
        //show_color();
        preview();
    });
    $('#font-select').change(function(event){
        preview();
    });
    $('#background_image').change(function(event){
        preview();
    });
</script>
