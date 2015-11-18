<?php 
    if (!defined('BASEPATH')) exit('No direct script access allowed');

    if($changed){
        echo '<div class="alert alert-info">Changes applied</div>';
    }
?>

<form enctype="multipart/form-data" class="form form-horizontal" method="post">

    <div class="form-group">
        <label class="control-label col-md-4" for="<?php echo $config_prefix; ?>moderation">Comment Moderation</label>
        <div class="controls col-md-8">
            <select id="<?php echo $config_prefix; ?>moderation" name="<?php echo $config_prefix; ?>moderation" class="form-control">
                <?php
                    $option_list = array(
                        'TRUE'=>'Yes, all comment should be moderated', 
                        'FALSE'=>'No, all comment will be automatically published');
                    foreach($option_list as $key=>$value){
                        $selected = $config_list[$config_prefix.'moderation'] == $key ? 'selected' : '';
                        echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                    }
                ?>
            </select>
            <p class="help-block">Comment should be moderated or not</p>            
        </div>
    </div>

    <div class="form-group">
        <div class="controls col-md-8 col-md-offset-4">
            <button class="btn btn-primary btn-lg">Apply Changes</button>
        </div>
    </div>

</form>