<?php
    echo form_open('main/change_profile');
    echo form_label('User Name').br();
    echo form_input('user_name', $user_name).br();
    echo form_label('E mail').br();
    echo form_input('email', $email).br();
    echo form_label('Real Name').br();
    echo form_input('real_name', $real_name).br();
    echo form_label('Password').br();
    echo form_password('password').br();
    echo form_label('Confirm Password').br();
    echo form_password('confirm_password').br();    
    echo form_submit('change_profile', 'Change Profile');
    echo form_close();
?>
