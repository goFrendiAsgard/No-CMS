<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
echo '<div style="padding-bottom: 10px;">';
echo anchor(site_url('{{ module_path }}/manage_project/index'),'All Projects','class="btn btn-primary"');
if(isset($project_id)){
    echo '&nbsp;';
    echo anchor(site_url('{{ module_path }}/manage_project/index/edit/'.$project_id),'Project "<b>'.$project_name.'</b>"','class="btn btn-primary"');
}
echo '</div>';
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
        $.post('{{ MODULE_SITE_URL }}Manage_table/delete_selection', { data: JSON.stringify(list) }, function(data) {
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
        $('.breadcrumb a').each(function(){
            if($(this).attr('href') == '{{ module_site_url }}manage_table'){
                $(this).attr('href', '{{ module_site_url }}manage_table/index/<?php echo $project_id; ?>');
            }
        });
    });
</script>
