<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style>
    #message:empty{
        display:none;
    }
</style>
<h3>{{ language:Change Theme }}</h3>
<form class="form-inline" action="<?php echo site_url('main/change_theme');?>" method="post" accept-charset="utf-8" style="padding-bottom:20px;">
    <div class="form-group">
        <input class="form-control" type="text" name="keyword" size="20" placeholder="Keyword" value="<?php echo $keyword; ?>" />
    </div>&nbsp;
    <button type="submit" class="btn btn-primary">{{ language:Search }}</button>
</form>
<?php
    echo '<div class="row">';
    foreach($themes as $theme){
        if(!$theme['published']){
            continue;
        }
        $style = $theme['used']? 'opacity:0.5; border:none;' : '';
        echo '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">';
        echo '<div class="thumbnail" style="'.$style.'">';
        if(!$theme['used']){
            echo '<a href="'.site_url('main/change_theme/'.$theme['path']).'" style="text-decoration:none;">';
        }
        $image_path = base_url('themes/'.$theme['path'].'/preview.png');
        if(@file_get_contents($image_path,0,NULL,0,1)){
            echo '<img class="col-md-12" src="'.$image_path.'" />';
        }else{
            echo '{{ language:No Preview }}';
        }
        echo '<div class="caption">';
        echo '<h4>'.$theme['path'].'</h4>';
        echo '<p>'.$theme['description'].'</p>';

        if($theme['used']){
            echo '<p>{{ language:Theme is in use }}</p>';
        }else{
            echo '<p>{{ language:Click to use this theme }}</p>';
        }
        echo '</div>';

        if(!$theme['used']) echo '</a>';
        echo '</div>'; // end of div.thumbnail div.theme-thumbnail
        echo '</div>'; // end of div.col-xs-6
    }
    echo '</div>'; // end of div.row
    echo '<div style="clear:both"></div>';
    if(CMS_SUBSITE == '' && $upload['uploading'] && !$upload['success']){
        echo '<div id="message" class="alert alert-danger">';
        echo '<b>{{ language:Error }}:</b> '.$upload['message'];
        echo '</div>';
    }
?>
<div style="clear:both; margin: 5px;">
<?php if(CMS_SUBSITE == ''){ ?>
    <form action="<?php echo site_url('main/change_theme');?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
        <input type="file" name="userfile" size="20" />
        <br /><br />
        <input name="upload" class="btn btn-primary" type="submit" value="<?php echo $upload_new_theme_caption; ?>" />
    </form>
<?php } ?>
</div>
<script type="text/javascript">
    function __adjust_component(identifier, max_height){
        if(typeof max_height == 'undefined'){
            max_height = 0;
        }
        $(identifier).each(function(){
            $(this).css('margin-bottom', 0);
            if($(this).height()>max_height){
                max_height = $(this).height();
            }
        });
        $(identifier).each(function(){
            var margin_bottom = 0;
            $(this).height(max_height);
            if($(this).height()<max_height){
                margin_bottom = max_height - $(this).height();
            }
            margin_bottom += 10;
            $(this).css('margin-bottom', margin_bottom);
        });
    }
    function adjust_thumbnail(){
        __adjust_component('.thumbnail img', 100);
        __adjust_component('.thumbnail div.caption');
    }

    $(window).on('load', function(){
        adjust_thumbnail();
    });
    $(window).resize(function(){
        adjust_thumbnail();
    });
    $(document).ready(function(){
        adjust_thumbnail();
    });
</script>
