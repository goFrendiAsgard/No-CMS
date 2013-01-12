<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css">
    #login_message:empty{
        display:none;
    }
</style>
<?php
    echo form_open('main/login');
    echo form_label('Identity').br();
    echo form_input('identity', $identity).br();
    echo form_label('Password').br();
    echo form_password('password').br();
    echo form_submit('login', 'Log In', 'class="btn btn-primary"');
	echo '&nbsp';
	echo anchor(site_url('main/register'),'Register',array('class'=>'btn'));
    echo form_close();	
	echo 'Or login with:'.br();
	foreach($providers as $provider=>$connected){
		echo anchor(site_url('main/hauth/login/'.$provider), '<img src="'.base_url('modules/main/assets/third_party/'.$provider.'.png').'" />');
	}
?>
<div id="login_message"><?php echo isset($message)?$message:''; ?></div>
