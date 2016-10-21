<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    echo $output;
    $asset = new Cms_asset();
    $asset->add_cms_js("grocery_crud/js/jquery_plugins/jquery.chosen.min.js");
    $asset->add_cms_js("nocms/js/jquery-ace/ace/ace.js");
    $asset->add_cms_js("nocms/js/jquery-ace/ace/theme-eclipse.js");
    $asset->add_cms_js("nocms/js/jquery-ace/ace/mode-html.js");
    $asset->add_cms_js("nocms/js/jquery-ace/ace/mode-javascript.js");
    $asset->add_cms_js("nocms/js/jquery-ace/jquery-ace.min.js");
    echo $asset->compile_js();
?>
<script type="text/javascript">
</script>
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
        // TODO: Put your custom code here
        $("#field-homepage").ace({
            theme: "eclipse",
            lang: "html",
            width: "100%",
            height: "200px"
        });
        $("#field-homepage").each(function(){
            var decorator = $(this).data("ace");
            var aceInstance = decorator.editor.ace;
            aceInstance.setFontSize("16px");
        });
    });
</script>
