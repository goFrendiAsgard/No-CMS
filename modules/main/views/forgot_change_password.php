<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    echo '<h3>{{ language:Forgot Password }}</h3>';
    echo form_open('main/forgot/'.$activation_code);
    echo form_label('{{ language:lang_fcp_new }}').br();
    echo form_password('password').br();
    echo form_label('{{ language:lang_fcp_new_again }}').br();
    echo form_password('confirm_password').br();
    echo form_submit('change', $this->No_CMS_Model->cms_lang('lang_fcp_submit'), 'class="btn btn-primary"');
    echo form_close();
