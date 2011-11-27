<?php
    echo form_open('main/forgot/'.$activation_code);
    echo form_label('New Password').br();
    echo form_password('password').br();
    echo form_label('New Password (again)').br();
    echo form_password('confirm_password').br();
    echo form_submit('change', 'Change');
    echo form_close();
?>
