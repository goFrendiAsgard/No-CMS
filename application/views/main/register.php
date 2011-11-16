<?php
    echo form_open('main/register');
    echo form_label('User Name').br();
    echo form_input('user_name', $user_name).br();
    echo form_label('E mail').br();
    echo form_input('email', $email).br();
    echo form_label('Password').br();
    echo form_password('password').br();
    echo form_label('Confirm Password').br();
    echo form_password('confirm_password').br();
    echo form_label('Real Name').br();
    echo form_input('real_name', $real_name).br();
    echo form_submit('register', 'Register');
    echo form_close();
?>
