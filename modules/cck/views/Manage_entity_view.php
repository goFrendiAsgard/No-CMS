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
        $.post('{{ MODULE_SITE_URL }}Manage_entity/delete_selection', { data: JSON.stringify(list) }, function(data) {
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
        // field per_record_html
        $("#field-per_record_html").ace({
            theme: "eclipse",
            lang: "html",
            width: "100%",
            height: "150px"
        });
        var decorator = $("#field-per_record_html").data("ace");
        if(typeof(decorator) != 'undefined'){
            var aceInstance = decorator.editor.ace;
            aceInstance.setFontSize("16px");
        }

        // define verb_list and call adjust_authorization_input
        var authorization_verb_list = ['browse', 'view', 'add', 'edit', 'delete'];
        // define event
        adjust_authorization_input(authorization_verb_list);
        for(var i=0; i<authorization_verb_list.length; i++){
            var verb = authorization_verb_list[i];
            $('#field-id_authorization_'+verb).change(function(event){
                adjust_authorization_input(authorization_verb_list);
            });
        }
    });

    function adjust_authorization_input(authorization_verb_list){
        for(var i=0; i<authorization_verb_list.length; i++){
            var verb = authorization_verb_list[i];
            if($('#field-id_authorization_'+verb).val() >= 4 ){
                $('div#group_entity_'+verb+'_field_box').show();
                // field_box
                $('div#group_entity_'+verb+'_field_box, div#id_authorization_'+verb+'_field_box').removeClass('col-md-12').addClass('col-md-6');
                // label
                $('label#group_entity_'+verb+'_display_as_box, label#id_authorization_'+verb+'_display_as_box').removeClass('col-md-2').addClass('col-md-4');
                // input
                $('#group_entity_'+verb+'_input_box, #id_authorization_'+verb+'_input_box').removeClass('col-md-10').addClass('col-md-8');
            }else{
                $('div#group_entity_'+verb+'_field_box').hide();
                // field_box
                $('div#group_entity_'+verb+'_field_box, div#id_authorization_'+verb+'_field_box').removeClass('col-md-6').addClass('col-md-12');
                // label
                $('label#group_entity_'+verb+'_display_as_box, label#id_authorization_'+verb+'_display_as_box').removeClass('col-md-4').addClass('col-md-2');
                // input
                $('#group_entity_'+verb+'_input_box, #id_authorization_'+verb+'_input_box').removeClass('col-md-8').addClass('col-md-10');
            }
        }
    }
</script>
