&lt;?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// For every content of option tag, this will replace '&nbsp;' with ' '
function __ommit_nbsp($matches){
    return $matches[1].str_replace('&nbsp;', ' ', $matches[2]).$matches[3];
}
echo preg_replace_callback('/(<option[^<>]*>)(.*?)(<\/option>)/si', '__ommit_nbsp', $output);
?&gt;
<?php
    if($make_frontpage){
        echo '<a class="btn btn-primary" href="{{ site_url }}{{ module_path }}/'.$front_controller_import_name.'/index">{{ language:Show Front Page }}</a>';
    }
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
    $('.checkall').live('click', function(){
        $(this).parents('table:eq(0)').find(':checkbox').attr('checked', this.checked);
    });
    // DELETE ALL
    $('.delete_all_button').live('click', function(event){
        event.preventDefault();
        var list = new Array();
        $('input[type=checkbox]').each(function() {
            if (this.checked) {
                //create list of values that will be parsed to controller
                list.push(this.value);
            }
        });
        //send data to delete
        $.post('{{ MODULE_SITE_URL }}<?php echo str_replace('.php', '', $controller_name); ?>/delete_selection', { data: JSON.stringify(list) }, function(data) {
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
    })
</script>