<style type="text/css">
    textarea[name="<?php echo $secret_code; ?>content"]{
        resize:none;
    }
</style>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<h4>Contact Us</h4>
<?php 
    if(!$success){
        echo '<div class="alert alert-danger">'.$error_message.'</div>';
    }else if($show_success_message){
        echo '<div class="alert alert-success">Your message has been sent</div>';
    }
    
    echo form_open('', 'class="form form-horizontal"');

    echo '<div class="form-group">';
    echo form_label('Name *', ' for="" class="control-label col-sm-3');
    echo '<div class="col-sm-9">';
    echo form_input($secret_code.'name', $name, 
        'id="'.$secret_code.'name" placeholder="Your Name" class="form-control"');
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group">';
    echo form_label('Email *', ' for="" class="control-label col-sm-3');
    echo '<div class="col-sm-9">';
    echo form_input($secret_code.'email', $email, 
        'id="'.$secret_code.'email" placeholder="Your Email" class="form-control"');
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group">';
    echo form_label('Message *', ' for="" class="control-label col-sm-3');
    echo '<div class="col-sm-9">';
    echo form_textarea($secret_code.'content', $content, 
        'id="'.$secret_code.'content" placeholder="Your Message" class="form-control"');
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group"><div class="col-sm-offset-3 col-sm-9">';
    echo form_submit('send', 'Send', 'class="btn btn-primary"');
    echo '</div></div>';
?>
<script type="text/javascript" src="{{ base_url }}assets/nocms/js/jquery.autosize.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('textarea[name="<?php echo $secret_code; ?>content"]').autosize();
    });
</script>