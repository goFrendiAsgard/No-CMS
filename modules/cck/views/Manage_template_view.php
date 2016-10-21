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
        '{{ MODULE_SITE_URL }}Manage_template/delete_selection', // url
        '{{ language:Delete Selected }}', // button caption
        '{{ language:Selected row deleted }}' // notification caption
    );

    $(document).ajaxComplete(function(){
        // TODO: Put your custom code here
    });

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
        }
    });
</script>
