<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
echo $output;
?>
<script type="text/javascript">
    $(document).ajaxComplete(function () {
        //ADD COMPONENTS
        if($('.pDiv2 .delete_all_button').length == 0 && $('#flex1 tbody td .delete-row').length != 0) { //check if element already exists (for ajax refresh purposes)
            $('.pDiv2').prepend('<div class="pGroup"><a class="delete_all_button btn btn-default" href="#"><i class="glyphicon glyphicon-remove"></i> {{ language:Delete Selected }}</a></div>');
        }
        if($('#flex1 thead td .checkall').length == 0 && $('#flex1 tbody td .delete-row').length != 0){
            $('#flex1 thead tr').prepend('<td><input type="checkbox" class="checkall" /></td>');
            $('#flex1 tbody tr').each(function(){
                $(this).prepend('<td><input type="checkbox" value="' + $(this).attr('rowId') + '" /></td>');
            });
        }
    });

    // CHECK ALL
    $('body').on('click', '.checkall', function(){
        $(this).parents('table:eq(0)').find(':checkbox').attr('checked', this.checked);
    });

    // DELETE ALL
    $('body').on('click', '.delete_all_button', function(event){
        event.preventDefault();
        var list = new Array();
        $('input[type=checkbox]').each(function() {
            if (this.checked) {
                //create list of values that will be parsed to controller
                list.push(this.value);
            }
        });
        //send data to delete
        $.post('{{ MODULE_SITE_URL }}Manage_article/delete_selection', { data: JSON.stringify(list) }, function(data) {
            for(i=0; i<list.length; i++){
                //remove selection rows
                $('#flex1 tr[rowId="' + list[i] + '"]').remove();
            }
            alert('{{ language:Selected row deleted }}');
        });
    });

    function adjust_publish_date(){
        if($('#field-status option:selected').val() == 'scheduled'){
            $('#publish_date_field_box').show();
        }else{
            $('#publish_date_field_box').hide();
        }
    }
    $('#field-status').change(function(){
        adjust_publish_date();
    });

    $(document).ajaxComplete(function(){
        // TODO: Put your custom code here
    });

    $(document).ready(function(){
        // TODO: Put your custom code here
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
        adjust_publish_date();

        // Add WPMore plugin to CKEDITOR
        if ($("#crudForm").length > 0 && typeof(CKEDITOR) != "undefined"){
            // Customize CKEditor
            CKEDITOR.config.extraPlugins = 'wpmore'; // Add 'WPMore' plugin - must be in plugins folder
            CKEDITOR.config.toolbar = [
                ['WPMore'] // Add 'WPMore' button to toolbar
            ];
        }
    });
</script>
