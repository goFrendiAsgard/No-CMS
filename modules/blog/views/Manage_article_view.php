<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
echo $output;
?>

<script type="text/javascript">
    // This function will add checkboxes and "Delete selected" button.
    add_delete_all_feature(
        '{{ MODULE_SITE_URL }}Manage_article/delete_selection', // url
        '{{ language:Delete Selected }}', // button caption
        '{{ language:Selected row deleted }}' // notification caption
    );

    $(document).ajaxComplete(function(){
        // TODO: Put your custom code here
    });

    function adjust_publish_date_view(){
        if($('#field-status').val() == 'scheduled'){
            $('#publish_date_field_box').show();
        }else{
            $('#publish_date_field_box').hide();
        }
    }

    $(document).ready(function(){
        adjust_publish_date_view();
        $('#field-status').change(function(event){
            adjust_publish_date_view();
        });
    });
</script>
