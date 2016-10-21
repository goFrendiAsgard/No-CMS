<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
echo $output;
?>
<script type="text/javascript">
    // This function will add checkboxes and "Delete selected" button.
    add_delete_all_feature(
        '{{ MODULE_SITE_URL }}Manage_view/delete_selection', // url
        '{{ language:Delete Selected }}', // button caption
        '{{ language:Selected row deleted }}' // notification caption
    );

    $(document).ajaxComplete(function(){
        remove_empty_elements();
    });

    $(document).ready(function(){
        remove_empty_elements();
    });

    function remove_empty_elements(){
        // remove empty element
        $('.remove-if-empty').each(function(){
            console.log($(this));
            if($(this).attr('real-value') == ''){
                $(this).remove();
            }
        });
    }
</script>
