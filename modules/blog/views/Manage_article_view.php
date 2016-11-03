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
        <?php
            echo 'var title = \''.str_replace('\'', '\\\'', $title).'\';';
            echo 'var content = \''.str_replace('\'', '\\\'',
                str_replace(array("\r","\n"),"", $content)).'\';';
            echo 'var status = \''.str_replace('\'', '\\\'', $status).'\';';
        ?>
        if(title != ''){
            $('#field-article_title').html(title);
        }
        if(content != ''){
            CKEDITOR.instances['field-content'].setData(content);
        }
        if(status != ''){
            $('#field-status').val(status);
            $('#field-status').trigger("chosen:updated");
        }
    });
</script>
