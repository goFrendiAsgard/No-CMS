<style type="text/css">
    textarea[name="<?php echo $secret_code; ?>content"]{
        resize:none;
    }
</style>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<h4>{{ language:Contact Us }}</h4>
<?php 
    if(!$success){
        echo '<div class="alert alert-danger">'.$error_message.'</div>';
    }else if($show_success_message){
        echo '<div class="alert alert-success">{{ language:Your message has been sent }}</div>';
    }
   
    echo form_open('', 'class="form form-horizontal"');

    echo '<div class="form-group">';
    echo form_label('{{ language:Name * }}', ' for="" class="control-label col-sm-3');
    echo '<div class="col-sm-9">';
    echo form_input($secret_code.'name', $name, 
        'id="'.$secret_code.'name" placeholder="{{ language:Your Name }}" class="form-control"');
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group">';
    echo form_label('{{ language:Email * }}', ' for="" class="control-label col-sm-3');
    echo '<div class="col-sm-9">';
    echo form_input($secret_code.'email', $email, 
        'id="'.$secret_code.'email" placeholder="{{ language:Your Email }}" class="form-control"');
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group">';

    echo form_label('{{ language:Message * }}', ' for="" class="control-label col-sm-3');    
    echo '<div class="col-sm-9">';
    echo form_textarea($secret_code.'content', $content, 
        'id="'.$secret_code.'content" placeholder="{{ language:Your Message }}" class="form-control"');
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group"><div class="col-sm-offset-3 col-sm-9">';
    echo form_submit('send', 'Send', 'class="btn btn-primary"');
    echo '</div></div>';
    
    echo '<p>';
    echo '{{ language:* indicates a required field }}' ;
    echo '<p>';
?>
<script type="text/javascript" src="{{ base_url }}assets/nocms/js/jquery.autosize.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('textarea[name="<?php echo $secret_code; ?>content"]').autosize();
    });
</script>