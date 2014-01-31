<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    echo form_open('main/forgot', 'class="form"');
    echo form_label('{{ language:Identity }}', 'class="form-label"');
    echo form_input('identity', $identity, 'class="form-control" placeholder="email or username"').br();
    echo form_submit('send_activation_code', $send_activation_code_caption, 'class="btn btn-default"');
    echo form_close();