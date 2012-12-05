<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css">
    #login_message:not(:empty){
        background-color:#FFCACA;
	    padding: 5px 5px 5px 5px;
	    margin : 10px;
	    font-size: small;
	    min-height : 25px;
	    border-radius:5px;
	    -moz-border-radius:5px;
	    -moz-box-shadow:    1px 1px 5px 6px #ccc;
	    -webkit-box-shadow: 1px 1px 5px 6px #ccc;
	    box-shadow:         1px 1px 5px 6px #ccc;      
        max-width : 400px;
    }
</style>
<?php
    echo form_open('main/login');
    echo form_label('Identity').br();
    echo form_input('identity', $identity).br();
    echo form_label('Password').br();
    echo form_password('password').br();
    echo form_submit('login', 'Log In');
    echo form_close();
?>
<div id="login_message"><?php echo $message; ?></div>
