<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style>
	#message::empty{
        display:none;
    }
    .disabled {
       pointer-events: none;
       cursor: default;
       display: none;
    }
    .module_icon a{
       margin-right:10px;
       margin-top:10px;
    }
</style>
<h3>{{ language:Module Management }}</h3>
<form class="form-inline" action="<?php echo site_url('main/module_management');?>" method="post" accept-charset="utf-8" style="padding-bottom:20px;">
    <div class="form-group">
        <input class="form-control" type="text" name="keyword" size="20" placeholder="Keyword" value="<?=$keyword?>" />
    </div>&nbsp;
    <button type="submit" class="btn btn-primary">Search</button>
</form>
<?php
    for($i=0; $i<count($modules); $i++){        
        $module = $modules[$i];
        $str_status = $module['active']?'module_active':'module_not_active';
        echo '<div class="row well">';
        echo '<div class="col-sm-4 module_icon">';
        echo '<b><i>'.$module['module_path'].'</i></b><br /><br />';
        $image_path = BASEPATH.'../modules/'.$module['module_path'].'/icon.png';
        if(file_exists($image_path)){
            $image_path = base_url('modules/'.$module['module_path'].'/icon.png');
        	echo '<img class="logo" src="'.$image_path.'" />';
        }else{
        	echo '<img class="logo" src="'.base_url('assets/nocms/images/icons/package.png').'" />';
        }
        echo '  <br />';
        if($module['active']){
            if($module['old']){
                $status = '{{ language:Need upgrade }}';
                echo '  <a id="module_'.$i.'_upgrade" class="btn btn-warning" href="'.site_url($module['module_path'].'/_info/upgrade').'"><i class="icon-arrow-up"></i>&nbsp;{{ language:Upgrade }}</a>';
            }else{
                $status = '{{ language:Active }}';
            }
            echo '  <a id="module_'.$i.'_deactivate" class="btn btn-danger" href="'.site_url($module['module_path'].'/_info/deactivate').'"><i class="icon-remove"></i>&nbsp;{{ language:Deactivate }}</a>';
            echo '  <a id="module_'.$i.'_setting" class="btn btn-warning" href="'.site_url($module['module_path'].'/_info/setting').'"><i class="icon-wrench"></i>&nbsp;{{ language:Settings }}</a>';
        
        }else{
            $status = '{{ language:Inactive }}';
            echo '  <a id="module_'.$i.'_activate" class="btn btn-success" href="'.site_url($module['module_path'].'/_info/activate').'"><i class="icon-ok"></i>&nbsp;{{ language:Activate }}</a>';
            echo '  <a id="module_'.$i.'_setting" class="btn btn-warning" href="'.site_url($module['module_path'].'/_info/setting').'"><i class="icon-wrench"></i>&nbsp;{{ language:Settings }}</a>';
        }
        echo '</div>';
        echo '<div class="col-sm-8">';
        echo '  <br />';
        echo '  <div id="div_module_'.$i.'_info" class="col-sm-12">';
        echo '      ('.$module['module_name'].')<br />';
        echo '      <p>'.$module['description'].'</p>';
        echo '      <strong>{{ language:Registered Version }}</strong> : '. $module['old_version'].' | ';
        echo '      <strong>{{ language:Current Version }}</strong> : '. $module['current_version'].' | ';
        echo '      <strong>{{ language:Status }}</strong> : '.$status;
        echo '  </div>';
        echo '</div>';
        echo '</div>';
    }
	echo '<div style="clear:both"></div>';
    if(CMS_SUBSITE == '' && $upload['uploading'] && !$upload['success']){
    	echo '<div id="message" class="alert alert-danger">';
    	echo '<b>{{ language:Error }}:</b> '.$upload['message'];
    	echo '</div>';
    }
?>
<div style="clear:both; margin: 5px;">
<?php if(CMS_SUBSITE == ''){ ?>
	<form action="<?php echo site_url('main/module_management');?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
		<input type="file" name="userfile" size="20" />
		<br /><br />
		<input name="upload" class="btn btn-primary" type="submit" value="<?php echo $upload_new_module_caption; ?>" />
	</form>
<?php } ?>
</div>

