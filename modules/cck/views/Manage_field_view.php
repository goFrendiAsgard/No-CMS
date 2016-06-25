<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
echo $output;
?>
<script type="text/javascript" src="{{ base_url }}assets/nocms/js/jquery-ace/ace/ace.js"></script>
<script type="text/javascript" src="{{ base_url }}assets/nocms/js/jquery-ace/ace/theme-eclipse.js"></script>
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
        $.post('{{ MODULE_SITE_URL }}Manage_field/delete_selection', { data: JSON.stringify(list) }, function(data) {
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

    var INPUT_CHANGED_BY_SYSTEM = false;
    var VIEW_CHANGED_BY_SYSTEM = false;

    $(document).ready(function(){
        // field input
        $("#field-input").ace({
            theme: "eclipse",
            lang: "html",
            width: "100%",
            height: "150px"
        });
        var decorator = $("#field-input").data("ace");
        if(typeof(decorator) != 'undefined'){
            var aceInstance = decorator.editor.ace;
            aceInstance.setFontSize("16px");
            aceInstance.getSession().on('change', function() {
                if(INPUT_CHANGED_BY_SYSTEM){ // not changed by user, probably AJAX CALL etc
                    return true;
                }
                if($('#field-input').val() == ''){
                    $('#field-custom_input').val('FALSE');
                    $('#input_changing_status').show();
                    set_input_to_default();
                }else{
                    $('#field-custom_input').val('TRUE');
                    $('#input_changing_status').hide();
                }
            });
        }

        // field view
        $("#field-view").ace({
            theme: "eclipse",
            lang: "html",
            width: "100%",
            height: "150px"
        });
        var decorator = $("#field-view").data("ace");
        if(typeof(decorator) != 'undefined'){
            var aceInstance = decorator.editor.ace;
            aceInstance.setFontSize("16px");
            aceInstance.getSession().on('change', function() {
                if(VIEW_CHANGED_BY_SYSTEM){ // not changed by user, probably AJAX CALL etc
                    return true;
                }
                if($('#field-view').val() == ''){
                    $('#field-custom_view').val('FALSE');
                    $('#view_changing_status').show();
                    set_view_to_default();
                }else{
                    $('#field-custom_view').val('TRUE');
                    $('#view_changing_status').hide();
                }
            });
        }

        // Adjust breadcrumb
        $('.breadcrumb a').each(function(){
            if($(this).attr('href') == '{{ module_site_url }}manage_field'){
                $(this).attr('href', '{{ module_site_url }}manage_field/index/<?php echo $id_entity; ?>');
            }
        });
    });

    /*
    function set_per_record_html_to_default(){
        PER_RECORD_HTML_CHANGED_BY_SYSTEM = true;
        $.ajax({
            'url' : '{{ module_site_url }}ajax/default_per_record_html_pattern/'+ID_ENTITY,
            'success' : function(response){
                var decorator = $("#field-per_record_html").data("ace");
                if(typeof(decorator) != 'undefined'){
                    var aceInstance = decorator.editor.ace;
                    PER_RECORD_HTML_CHANGED_BY_SYSTEM = true; // this to avoid infinite recursive caused by onchange event
                    aceInstance.session.setValue(response);
                    PER_RECORD_HTML_CHANGED_BY_SYSTEM = false;
                }
            }
        });
    }
    */
</script>
