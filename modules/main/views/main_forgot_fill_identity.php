<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    echo form_open('main/forgot');
    echo form_label('{{ language:Identity }}').br();
    echo form_input('identity', $identity).br();
    echo form_submit('send_activation_code', $send_activation_code_caption);
    echo form_close();