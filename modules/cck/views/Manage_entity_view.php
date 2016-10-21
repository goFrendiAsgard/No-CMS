<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
echo $output;
?>
<script type="text/javascript" src="{{ base_url }}assets/nocms/js/jquery-ace/ace/ace.js"></script>
<script type="text/javascript" src="{{ base_url }}assets/nocms/js/jquery-ace/ace/theme-eclipse.js"></script>
<script type="text/javascript" src="{{ base_url }}assets/nocms/js/jquery-ace/ace/mode-html.js"></script>
<script type="text/javascript" src="{{ base_url }}assets/nocms/js/jquery-ace/jquery-ace.min.js"></script>
<script type="text/javascript">
    // This function will add checkboxes and "Delete selected" button.
    add_delete_all_feature(
        '{{ MODULE_SITE_URL }}Manage_entity/delete_selection', // url
        '{{ language:Delete Selected }}', // button caption
        '{{ language:Selected row deleted }}' // notification caption
    );

    $(document).ajaxComplete(function(){
        // TODO: Put your custom code here
    });

    var PER_RECORD_HTML_CHANGED_BY_SYSTEM = false;
    var ID_ENTITY = '<?php echo $id_entity; ?>';

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
            aceInstance.getSession().on('change', function() {
                if(PER_RECORD_HTML_CHANGED_BY_SYSTEM){ // not changed by user, probably AJAX CALL etc
                    return true;
                }
                if($('#field-per_record_html').val() == ''){
                    $('#field-custom_per_record_html').val('FALSE');
                    $('#per_record_html_changing_status').show();
                    set_per_record_html_to_default();
                }else{
                    $('#field-custom_per_record_html').val('TRUE');
                    $('#per_record_html_changing_status').hide();
                }
            });
        }

        // is custom per record html edited?
        var custom_per_record_html = $('#field-custom_per_record_html').val() == 'TRUE';
        if(!custom_per_record_html){
            $('#per_record_html_input_box').prepend('<div id="per_record_html_changing_status" class="alert alert-info">Filled automatically. Will be updated when Entity saved. <i>Do not edit unless you are sure.</i></div>');
            set_per_record_html_to_default();
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

    function set_per_record_html_to_default(){
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
