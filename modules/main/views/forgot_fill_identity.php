<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
    echo '<h3>{{ language:Forgot Password }}</h3>';
    echo form_open('main/forgot');
    echo form_label('{{ language:lang_ffi_identity }}').br();
    echo form_input('identity', $identity).br();
    echo form_submit('send_activation_code', $this->No_CMS_Model->cms_lang('lang_ffi_submit'), 'class="btn btn-primary"');
    echo form_close();
