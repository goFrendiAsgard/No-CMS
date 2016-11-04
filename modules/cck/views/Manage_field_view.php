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
        '{{ MODULE_SITE_URL }}Manage_field/delete_selection', // url
        '{{ language:Delete Selected }}', // button caption
        '{{ language:Selected row deleted }}' // notification caption
    );

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

        // id_template changed
        $('#field-id_template').change(function(){
            // set custom input
            $('#field-custom_input').val('FALSE');
            $('#input_changing_status').show();
            set_input_to_default();
            // set custom view
            $('#field-custom_view').val('FALSE');
            $('#view_changing_status').show();
            set_view_to_default();
        });

        var custom_input = $('#field-custom_input').val() == 'TRUE';
        if(!custom_input){
            $('#input_input_box').prepend('<div id="input_changing_status" class="alert alert-info">Filled automatically. Will be updated when Field saved. <i>Do not edit unless you are sure.</i></div>');
            set_input_to_default();
        }
        var custom_view = $('#field-custom_view').val() == 'TRUE';
        if(!custom_view){
            $('#view_input_box').prepend('<div id="view_changing_status" class="alert alert-info">Filled automatically. Will be updated when Field saved. <i>Do not edit unless you are sure.</i></div>');
            set_view_to_default();
        }


        // Adjust breadcrumb
        $('.breadcrumb a').each(function(){
            if($(this).attr('href') == '{{ module_site_url }}manage_field'){
                $(this).attr('href', '{{ module_site_url }}manage_field/index/<?php echo $id_entity; ?>');
            }
        });
    });


    function set_input_to_default(){
        var decorator = $("#field-input").data("ace");
        if(typeof(decorator) != 'undefined'){
            var aceInstance = decorator.editor.ace;
            var id_template = $('#field-id_template').val();
            console.log(id_template);
            if(id_template > 0){ // id_template is set
                $.ajax({
                    'url' : '{{ module_site_url }}ajax/input_pattern_by_template/'+id_template,
                    'success' : function(response){
                        INPUT_CHANGED_BY_SYSTEM = true; // this to avoid infinite recursive caused by onchange event
                        aceInstance.session.setValue(response);
                        INPUT_CHANGED_BY_SYSTEM = false;
                    }
                });
            }else{ // id_template is not set, the text area should be empty
                INPUT_CHANGED_BY_SYSTEM = true; // this to avoid infinite recursive caused by onchange event
                aceInstance.session.setValue('');
                INPUT_CHANGED_BY_SYSTEM = false;
            }
        }
    }

    function set_view_to_default(){
        var decorator = $("#field-view").data("ace");
        if(typeof(decorator) != 'undefined'){
            var id_template = $('#field-id_template').val();
            var aceInstance = decorator.editor.ace;
            if(id_template > 0){ // id_template is set
                $.ajax({
                    'url' : '{{ module_site_url }}ajax/view_pattern_by_template/'+id_template,
                    'success' : function(response){
                        VIEW_CHANGED_BY_SYSTEM = true; // this to avoid infinite recursive caused by onchange event
                        aceInstance.session.setValue(response);
                        VIEW_CHANGED_BY_SYSTEM = false;
                    }
                });
            }else{ // id_template is not set, the text area should be empty
                VIEW_CHANGED_BY_SYSTEM = true; // this to avoid infinite recursive caused by onchange event
                aceInstance.session.setValue('');
                VIEW_CHANGED_BY_SYSTEM = false;
            }
        }
    }

</script>
