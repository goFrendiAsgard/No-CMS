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
	echo '<ul class="thumbnails row-fluid">';
    foreach($themes as $theme){
        $str_status = $theme['used']?'used':'not used';
        echo '<li class=" well module-management-container change-theme-container" style="width:150px!important; height:120px!important; float:left!important; list-style-type:none;">';
        if(!$theme['used']){
        	echo '<a href="'.site_url('main/change_theme/'.$theme['path']).'">{{ language:Use theme }} : ';
        }else{
        	echo '{{ language:Currently use theme }} : ';
        }
        echo '<b><i>'.$theme['path'].'</i></b><br /><br />';
        $image_path = base_url('themes/'.$theme['path'].'/preview.png');
        if(@file_get_contents($image_path,0,NULL,0,1)){
        	echo '<img src="'.$image_path.'" />';
        }else{
        	echo '{{ language:No Preview }}';
        }
        if(!$theme['used']) echo '</a>';
        echo '</li>';
    }
	echo '</ul>';
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