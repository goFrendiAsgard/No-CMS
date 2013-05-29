<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css">
    #login_message:empty{
        display:none;
    }
</style>
<h3>{{ language:Login }}</h3>
<?php
    echo form_open('main/login');
    echo form_label('{{ language:lang_login_identity }}');
    echo form_input('identity', $identity).br();
    echo form_label('{{ language:lang_login_password }}');
    echo form_password('password').br();
    echo form_submit('login', $this->No_CMS_Model->cms_lang('lang_login_submit'), 'class="btn btn-primary"');
	echo '&nbsp';
	echo anchor(site_url('main/register'),'{{ language:lang_login_register_btn }}',array('class'=>'btn'));
    echo form_close();

	if(count($providers)>0){
		echo '{{ language:lang_login_hauth }}:'.br();
		foreach($providers as $provider=>$connected){
			echo anchor(site_url('main/hauth/login/'.$provider), '<img src="'.base_url('modules/main/assets/third_party/'.$provider.'.png').'" />');
		}
	}
?>
<div id="login_message"><?php echo isset($message)?$message:''; ?></div>
