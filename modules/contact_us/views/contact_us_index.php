<style type="text/css">
    textarea[name="<?php echo $secret_code; ?>content"]{width:90%;}
</style>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<h4>Contact Us</h4>
<?php 
    if(!$success){
        echo '<div class="alert alert-danger">'.$error_message.'</div>';
    }else if($show_success_message){
        echo '<div class="alert alert-success">Your message has been sent</div>';
    }
    
    echo form_open();
    echo form_label('Name *');
    echo form_input($secret_code.'name', $name).br();
    echo form_label('Email *');
    echo form_input($secret_code.'email', $email).br();
    echo form_label('Message *');
    echo form_textarea($secret_code.'content', $content).br();
    echo form_submit('send', 'Send', 'class="btn btn-primary"');
    echo form_close();
    
    echo '<hr />';
    echo $menu;
?>