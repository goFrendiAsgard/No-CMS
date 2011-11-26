<?php
    echo form_open('main/forgot');
    echo form_label('Identity').br();
    echo form_input('identity', $identity).br();
    echo form_submit('send_activation_code', 'Send Activation Code to My E mail');
    echo form_close();
?>
