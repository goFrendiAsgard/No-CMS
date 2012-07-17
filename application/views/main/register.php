<style type="text/css">
    #message:not(:empty){
        background-color: #FFFFA0;
        border-radius: 15px;
        border : 1px solid black;
        color : black;
        padding : 5px;        
        width : 400px;
    }
</style>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/nocms/js/jquery.js"></script>
<script type="text/javascript">
    function check_user_exists(){
        var user_name =  $('input[name="user_name"]').val();
        var password = $('input[name="password"]').val();
        var confirm_password = $('input[name="confirm_password"]').val();
        $.ajax({
            "url" : "check_registration",
            "type" : "POST",
            "data" : {"user_name":user_name},
            "dataType" : "json",
            "success" : function(data){
            	if(!data.exists && user_name!='' && password!='' && password==confirm_password){
                    $('input[name="register"]').show();
                    $('input[name="register"]').removeAttr('disabled');                    
                }else{
                    $('input[name="register"]').hide();
                    $('input[name="register"]').attr('disabled', 'disabled');
                }
                
                if(data.message != $('#message').html()){
                    $('#message').html(data.message);
                }
            }
        });
    }
    
    $(document).ready(function(){
        check_user_exists();
        $('input').keyup(function(){
            check_user_exists();
        });
    })
</script> 

<?php
    echo form_open('main/register');
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
    echo form_submit('register', 'Register');
    echo form_close();
    echo br();
?>
<span id="message"></span>
