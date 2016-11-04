<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css">
    #record_content{
        margin-top: 5px;
        margin-bottom: 10px;
    }
    .record_container{
        margin:10px;
    }
    .edit_delete_record_container{
        margin-top: 10px;
    }
</style>
<div class="form form-inline">
    <div class="form-group">
        <input type="text" name="search" value="" id="input_search" class="input-medium search-query form-control" placeholder="keyword" />&nbsp;
    </div>
    <button name="submit" id="btn_search" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> Search</button>&nbsp;
    <?php
        if($allow_navigate_backend && $have_add_privilege){
            echo '<a href="'.$backend_url.'/add/" class="btn btn-default add_record"><i class="glyphicon glyphicon-plus"></i> Add</a>'.PHP_EOL;
        }
        if($have_edit_template_privilege){
            echo '<a href="{{ module_site_url }}browse_portfolio/template_config" class="btn btn-default"><i class="glyphicon glyphicon-cog"></i> Edit Record Template</a>'.PHP_EOL;
        }
    ?>
</div>
<div id="record_content"><?php echo $first_data ?></div>
<div class="row" style="padding-bottom:20px">
    <a id="btn_load_more" class="btn btn-default col-xs-12" style="display:none;">{{ language:Load More }}</a>
</div>
<div id="record_content_bottom" class="alert alert-success">End of Page</div>

<!--Reload data when reach bottom -->
<script type="text/javascript">
    var URL                    = '<?php echo site_url($module_path."/browse_portfolio/get_data"); ?>';
    var BACKEND_URL            = '<?php echo $backend_url; ?>';
    var ALLOW_NAVIGATE_BACKEND = <?php echo $allow_navigate_backend ? "true" : "false"; ?>;
    var HAVE_ADD_PRIVILEGE     = <?php echo $have_add_privilege ? "true" : "false"; ?>;
    var LOAD_MESSAGE           = 'Load more portfolio &nbsp;<img src="{{ BASE_URL }}assets/nocms/images/ajax-loader.gif" />';
    var REACH_END_MESSAGE      = 'No more portfolio to show';
</script>
<script type="text/javascript" src="{{ base_url }}assets/nocms/js/cms_front_view.js"></script>
<!-- End of reload script -->

<script type="text/javascript">

    // main program
    $(document).ready(function(){

        // input keyup
        $('#input_search').keyup(function(){
            reset_content();
        });

        // button search click
        $('#btn_search').click(function(){
            reset_content();
        });

    });

</script>
