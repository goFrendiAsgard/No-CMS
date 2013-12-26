<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style>
    li.change-theme-container>a{
        width: 100%;
        height: 100%;
        display: block;
    }
    li.change-theme-container img{
        max-width: 128px;
        height: auto;
    }
    #message:empty{
        display:none;
    }
</style>
<h3>{{ language:Change Theme }}</h3>
<?php
    echo '<div class="row">';
    foreach($themes as $theme){
        $style = $theme['used']? 'opacity:0.5; border:none;' : '';
        echo '<div class="col-xs-6 col-md-3">';
        echo '<div class="thumbnail" style="height:200px; max-height:200px; '.$style.'">';
        if(!$theme['used']){
            echo '<a href="'.site_url('main/change_theme/'.$theme['path']).'" style="text-decoration:none;">';
        }
        $image_path = base_url('themes/'.$theme['path'].'/preview.png');
        if(@file_get_contents($image_path,0,NULL,0,1)){
            echo '<img src="'.$image_path.'" />';
        }else{
            echo '{{ language:No Preview }}';
        }
        echo '<div class="caption">';
        echo '<h4>'.$theme['path'].'</h4>';

        if($theme['used']){
            echo '<p>The theme is already in use</p>';
        }else{
            echo '<p>Click to use this theme</p>';
        }
        echo '</div>';
        
        if(!$theme['used']) echo '</a>';
        echo '</div>'; // end of div.thumbnail
        echo '</div>'; // end of div.col-xs-6
    }
    echo '</div>'; // end of div.row
    echo '<div style="clear:both"></div>';
    if($upload['uploading'] && !$upload['success']){
        echo '<div id="message" class="alert alert-error">';
        echo '<b>{{ language:Error }}:</b> '.$upload['message'];
        echo '</div>';
    }
?>
<div style="clear:both; margin: 5px;">
    <form action="<?php echo site_url('main/change_theme');?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
        <input type="file" name="userfile" size="20" />
        <br /><br />
        <input name="upload" class="btn btn-primary" type="submit" value="<?php echo $upload_new_theme_caption; ?>" />
    </form>
</div>