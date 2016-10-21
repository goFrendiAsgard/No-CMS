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
    // This function will add checkboxes and "Delete selected" button.
    add_delete_all_feature(
        '{{ MODULE_SITE_URL }}Manage_table/delete_selection', // url
        '{{ language:Delete Selected }}', // button caption
        '{{ language:Selected row deleted }}' // notification caption
    );


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
