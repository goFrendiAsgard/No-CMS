<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css">
    #login_message:empty{
        display:none;
    }
    #login_message{
        margin-top:20px;
    }
</style>
<?php
    echo form_open('main/login');
    echo form_label('{{ language:Identity }}');
    echo form_input('identity', $identity, 'placeholder="identity" class="form-control"').br();
    echo form_label('{{ language:Password }}');
    echo form_password('password','','placeholder="password" class="form-control"').br();
    echo form_submit('login', $login_caption, 'class="btn btn-primary"');
    if($allow_register){
        echo '&nbsp';
        echo anchor(site_url('main/register'), $register_caption, array('class'=>'btn btn-default'));
    }
    echo form_close();
    if(count($providers)>0){
        echo '{{ language:Or Login with }}:'.br();
        foreach($providers as $provider=>$connected){
            echo anchor(site_url('main/hauth/login/'.$provider), '<img src="'.base_url('modules/main/assets/third_party/'.$provider.'.png').'" />');
        }
    }
?>
<div id="login_message" class="alert alert-danger"><?php echo isset($message)?$message:''; ?></div>
