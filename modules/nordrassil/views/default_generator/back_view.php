&lt;?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
echo $output;
?&gt;
<?php
    if($make_frontpage){
        echo '<a class="btn btn-primary" href="{{ site_url }}{{ module_path }}/'.$front_controller_import_name.'/index">{{ language:Show Front Page }}</a>'.PHP_EOL;
    }
?>
<script type="text/javascript">
    // This function will add checkboxes and "Delete selected" button.
    add_delete_all_feature(
        '{{ MODULE_SITE_URL }}<?php echo str_replace('.php', '', $controller_name); ?>/delete_selection', // url
        '{{ language:Delete Selected }}', // button caption
        '{{ language:Selected row deleted }}' // notification caption
    );

    $(document).ajaxComplete(function(){
        // TODO: Put your custom code here
    });

    $(document).ready(function(){
        // TODO: Put your custom code here
    });
</script>
