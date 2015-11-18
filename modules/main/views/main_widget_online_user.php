<div id="__online_user">
    Loading ...
</div>
<script type="text/javascript">
    $(document).ready(function(){
        function __reload_online_user(){
            $.ajax({
                'url' : '<?php echo site_url('main/widget_online_user_ajax'); ?>',
                'success' : function(response){
                    $('#__online_user').html(response);
                }
            });
        }
        __reload_online_user();
        setInterval(__reload_online_user,60000);
    });
</script>