<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    echo form_open('main/forgot/'.$activation_code);
    echo form_label('{{ language:New Password }}').br();
    echo form_password('password').br();
    echo form_label('{{ language:New Password (again) }}').br();
    echo form_password('confirm_password').br();
    echo form_submit('change', $change_caption);
    echo form_close();