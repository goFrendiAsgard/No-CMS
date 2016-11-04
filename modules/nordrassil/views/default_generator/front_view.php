<?php
    $fields = array();
    $captions = array();
    $primary_key = '';
    foreach($columns as $column){
        $column_name = $column['name'];
        $column_role = $column['role'];
        $column_caption = $column['caption'];
        if($column_role == 'primary'){
            $primary_key = $column_name;
        }else if($column_role == ''){
            $fields[] = $column_name;
            $captions[] = $column_caption;
        }else if($column_role == 'lookup'){
            $lookup_table_name = $column['lookup_table_name'];
            $lookup_column_name = $column['lookup_column_name'];
            $fields[] = $lookup_table_name.'_'.$lookup_column_name;
            $captions[] = $column_caption;
        }
    }
?>
&lt;?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?&gt;
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
<div id="form_search" class="form form-inline">
    <div class="form-group">
        <input type="text" name="search" value="" id="input_search" class="input-medium search-query form-control" placeholder="keyword" />&nbsp;
    </div>
    <button name="submit" id="btn_search" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> Search</button>&nbsp;
    &lt;?php
        if($allow_navigate_backend && $have_add_privilege){
            echo '<a href="'.$backend_url.'/add/" class="btn btn-default add_record"><i class="glyphicon glyphicon-plus"></i> Add</a>'.PHP_EOL;
        }
        if($have_edit_template_privilege){
            echo '<a href="{{ module_site_url }}{{ front_controller_import_name }}/template_config" class="btn btn-default"><i class="glyphicon glyphicon-cog"></i> Edit Record Template</a>'.PHP_EOL;
        }
    ?&gt;
</div>
<div id="record_content">&lt;?php echo $first_data ?&gt;</div>
<div class="row" style="padding-bottom:20px">
    <a id="btn_load_more" class="btn btn-default col-xs-12" style="display:none;">{{ language:Load More }}</a>
</div>
<div id="record_content_bottom" class="alert alert-success">End of Page</div>

<!--Reload data when reach bottom -->
<script type="text/javascript">
    var URL                    = '&lt;?php echo site_url($module_path."/{{ front_controller_import_name }}/get_data"); ?&gt;';
    var BACKEND_URL            = '&lt;?php echo $backend_url; ?&gt;';
    var ALLOW_NAVIGATE_BACKEND = &lt;?php echo $allow_navigate_backend ? "true" : "false"; ?&gt;;
    var HAVE_ADD_PRIVILEGE     = &lt;?php echo $have_add_privilege ? "true" : "false"; ?&gt;;
    var LOAD_MESSAGE           = 'Load more {{ table_caption }} &nbsp;<img src="{{ BASE_URL }}assets/nocms/images/ajax-loader.gif" />';
    var REACH_END_MESSAGE      = 'No more {{ table_caption }} to show';

    ////////////////////////////////////////////////////////////////////////////////
    // if SCROLL_WORK is false, the infinite scroll won't work.
    // Uncomment the line below to disable scroll
    ////////////////////////////////////////////////////////////////////////////////
    // var SCROLL_WORK = false;

    ////////////////////////////////////////////////////////////////////////////////
    // Override this function to create custom AJAX's post data. The function should
    // return json formatted data
    ////////////////////////////////////////////////////////////////////////////////
    // function prepare_search_post_data(data){
    //     return data;
    // }
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
