<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
echo '<div style="padding-bottom: 10px;">';
echo anchor(site_url('{{ module_path }}/manage_map/index'),'All maps','class="btn btn-primary"');
if(isset($map_id)){
    echo '&nbsp;';
    echo anchor(site_url('{{ module_path }}/manage_map/index/edit/'.$map_id),'map "<b>'.$map_name.'</b>"','class="btn btn-primary"');
}
echo '</div>';
echo $output;
?>
<script type="text/javascript" src="{{ module_base_url }}assets/scripts/navigation.js"></script>
<script type="text/javascript" src="{{ base_url }}assets/nocms/js/jquery-ace/ace/ace.js"></script>
<script type="text/javascript" src="{{ base_url }}assets/nocms/js/jquery-ace/ace/theme-eclipse.js"></script>
<script type="text/javascript" src="{{ base_url }}assets/nocms/js/jquery-ace/ace/mode-sql.js"></script>
<script type="text/javascript" src="{{ base_url }}assets/nocms/js/jquery-ace/ace/mode-html.js"></script>
<script type="text/javascript" src="{{ base_url }}assets/nocms/js/jquery-ace/jquery-ace.min.js"></script>
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
        $.post('{{ MODULE_SITE_URL }}Manage_layer/delete_selection', { data: JSON.stringify(list) }, function(data) {
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
        // adjust breadcumb
        $('.breadcrumb a').each(function(){
            if($(this).attr('href') == '{{ module_site_url }}manage_layer'){
                $(this).attr('href', '{{ module_site_url }}manage_layer/index/<?php echo $map_id; ?>');
            }
        });
        // adjust input
        adjust_input();
        // json_popup_content
        $("#field-json_popup_content").ace({
            theme: "eclipse",
            lang: "html",
            width: "100%",
            height: "100px"
        });
        var decorator = $("#field-json_popup_content").data("ace");
        if(typeof(decorator) != 'undefined'){
            var aceInstance = decorator.editor.ace;
            aceInstance.setFontSize("16px");
        }
        // json_label
        $("#field-json_label").ace({
            theme: "eclipse",
            lang: "html",
            width: "100%",
            height: "50px"
        });
        var decorator = $("#field-json_label").data("ace");
        if(typeof(decorator) != 'undefined'){
            var aceInstance = decorator.editor.ace;
            aceInstance.setFontSize("16px");
        }
        // search_result_content
        $("#field-search_result_content").ace({
            theme: "eclipse",
            lang: "html",
            width: "100%",
            height: "100px"
        });
        var decorator = $("#field-search_result_content").data("ace");
        if(typeof(decorator) != 'undefined'){
            var aceInstance = decorator.editor.ace;
            aceInstance.setFontSize("16px");
        }
        // json_sql
        $("#field-json_sql").ace({
            theme: "eclipse",
            lang: "sql",
            width: "100%",
            height: "100px"
        });
        var decorator = $("#field-json_sql").data("ace");
        if(typeof(decorator) != 'undefined'){
            var aceInstance = decorator.editor.ace;
            aceInstance.setFontSize("16px");
        }
        // search_sql
        $("#field-search_sql").ace({
            theme: "eclipse",
            lang: "sql",
            width: "100%",
            height: "100px"
        });
        var decorator = $("#field-search_sql").data("ace");
        if(typeof(decorator) != 'undefined'){
            var aceInstance = decorator.editor.ace;
            aceInstance.setFontSize("16px");
        }

    });

    $('#crudForm input').change(function(){
        adjust_input();
    });

    function adjust_input(){
        // use json url
        if($('#field-use_json_url-true:checked').length > 0){
            $('#json_url_field_box').show();
            $('#json_sql_field_box').hide();
            $('#json_shape_column_field_box').hide();
            $('#json_popup_content_field_box').hide();
            $('#json_label_field_box').hide();
        }else{
            $('#json_url_field_box').hide();
            $('#json_sql_field_box').show();
            $('#json_shape_column_field_box').show();
            $('#json_popup_content_field_box').show();
            $('#json_label_field_box').show();
        }
        // searchable
        if($('#field-searchable-true:checked').length > 0){
            $('#use_search_url_field_box').show();
            if($('#field-use_search_url-true:checked').length > 0){
                $('#search_url_field_box').show();
                $('#search_sql_field_box').hide();
                $('#search_result_content_field_box').hide();
                $('#search_result_x_column_field_box').hide();
                $('#search_result_y_column_field_box').hide();
            }else{
                $('#search_url_field_box').hide();
                $('#search_sql_field_box').show();
                $('#search_result_content_field_box').show();
                $('#search_result_x_column_field_box').show();
                $('#search_result_y_column_field_box').show();
            }
        }else{
            $('#use_search_url_field_box').hide();
            $('#search_url_field_box').hide();
            $('#search_sql_field_box').hide();
            $('#search_result_content_field_box').hide();
            $('#search_result_x_column_field_box').hide();
            $('#search_result_y_column_field_box').hide();
        }
    }
</script>
