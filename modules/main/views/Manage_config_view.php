<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
echo $output;
$asset = new CMS_Asset();
$asset->add_cms_js("nocms/js/jquery-ace/ace/ace.js");
$asset->add_cms_js("nocms/js/jquery-ace/ace/theme-eclipse.js");
$asset->add_cms_js("nocms/js/jquery-ace/ace/mode-html.js");
$asset->add_cms_js("nocms/js/jquery-ace/ace/mode-javascript.js");
$asset->add_cms_js("nocms/js/jquery-ace/ace/mode-css.js");
$asset->add_cms_js("nocms/js/jquery-ace/jquery-ace.min.js");
echo $asset->compile_js();
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
        $.post('{{ MODULE_SITE_URL }}Manage_config/delete_selection', { data: JSON.stringify(list) }, function(data) {
            for(i=0; i<list.length; i++){
                //remove selection rows
                $('#flex1 tr[rowId="' + list[i] + '"]').remove();
            }
            alert('{{ language:Selected row deleted }}');
        });
    });

    $(document).ajaxComplete(function(){
        // TODO: Put your custom code here
    });

    $(document).ready(function(){
        // TODO: Put your custom code here
        $("#field-value").ace({
            theme: "eclipse",
            lang: "html",
            width: "100%",
            height: "150px"
        });
        $("#field-value").each(function(){
            var decorator = $(this).data("ace");
            var aceInstance = decorator.editor.ace;
            aceInstance.setFontSize("16px");
        });
    });
</script>
