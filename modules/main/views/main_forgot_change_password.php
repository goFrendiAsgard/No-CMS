<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    echo form_open('main/forgot/'.$activation_code);
    echo form_label('{{ language:New Password }}','class="form-label"').br();
    echo form_password('password', '', 'class="form-control" placeholder="New Password"').br();
    echo form_label('{{ language:New Password (again) }}').br();
    echo form_password('confirm_password', '', 'class="form-control" placeholder="New Password (Again)"').br();
    echo form_submit('change', $change_caption, 'class="btn btn-default"');
    echo form_close();
