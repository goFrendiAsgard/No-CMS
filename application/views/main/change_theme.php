<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style>
	div.change-theme-container{
		display:block;
		float: left;
		margin: 10px;
		width: 200px;
		height: 150px;
		background-color:#EEEEEE;
	    padding: 5px 5px 5px 5px;
	    margin : 10px;
	    font-size: small;
	    min-height : 25px;
	    border-radius:5px;
	    -moz-border-radius:5px;
	    -moz-box-shadow:    1px 1px 1px 1px #ccc;
	    -webkit-box-shadow: 1px 1px 1px 1px #ccc;
	    box-shadow:         1px 1px 1px 1px #ccc;	
	    text-align: center;	
	}
	div.change-theme-container>a{
		width: 100%;
		height: 100%;
		display: block;		
	}
	div.change-theme-container img{
		max-width: 90%;
		height: auto;
	}
</style>
<h3>Change Theme</h3>
<?php
    foreach($themes as $theme){
        $str_status = $theme['used']?'used':'not used';
        echo '<div class="change-theme-container">';
        if(!$theme['used']){
        	echo '<a href="'.site_url('main/change_theme/'.$theme['path']).'">Change to ';
        }else{
        	echo 'Currently use ';
        }
        echo '<b><i>'.$theme['path'].'</i></b> theme<br /><br />';
        $image_path = base_url('themes/'.$theme['path'].'/preview.png');
        if(@file_get_contents($image_path,0,NULL,0,1)){
        	echo '<img src="'.$image_path.'" />';
        }else{
        	echo 'No Preview';
        }
        if(!$theme['used']) echo '</a>';
        echo '</div>';
    }
?>