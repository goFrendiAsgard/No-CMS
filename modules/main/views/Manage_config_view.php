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
    // This function will add checkboxes and "Delete selected" button.
    add_delete_all_feature(
        '{{ MODULE_SITE_URL }}Manage_config/delete_selection', // url
        '{{ language:Delete Selected }}', // button caption
        '{{ language:Selected row deleted }}' // notification caption
    );

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
