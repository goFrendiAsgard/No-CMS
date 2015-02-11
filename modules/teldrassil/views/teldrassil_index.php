<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css">
    <?php
        if($file_name == NULL){
            echo '.initially-hidden{display:none;}';
        }
    ?>
</style>
<h3>Theme Generator</h3>
<form class="form row col-md-12" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6">

            <div class="form-group">
                <label>Image</label>
                <?php
                    if($file_name != NULL){
                        echo '<img style="max-width:200px; margin:20px;" src="' .$url_name. '" /><br />';
                    }
                ?>    
                <input class="form-control" type="file" name="file_name" /><br />
                <button class="btn btn-default" name="upload">Upload</button>
            </div>

            <div class="form-group initially-hidden">
                <label>Theme Name</label>
                <input class="form-control" type="text" name="theme_name" value='<?=$theme_name?>' />
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
                                echo '<option value="'.$option.'"'.$selected.'>' . $option . '</option>';
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
<script type="text/javascript">
    function show_color(){
        $('.color-select').each(function(){
            var value = $(this).val();
            $(this).parent('td').css('background-color', '#' + value);
        });
    }

    function preview(){
        console.log('jalan');
        var url = "{{ MODULE_SITE_URL }}teldrassil/preview?font=" + $('#font-select').val();
        for(var i=0; i<7; i++){
            url += '&colors[]=' + $('#color-select-'+i).val();
        }
        $('#preview').attr('src', url);
    }

    $('select.color-select').children().each(function (){
        $(this).css('background-color', '#' + $(this).val())
        //$(this).attr('style', 'background-color:' + colors[$(this).val()] + ';');
    });

    show_color();
    preview();


    $('.color-select').change(function(event){
        show_color();
        preview();
    });
    $('#font-select').change(function(event){
        preview();
    })
</script>
