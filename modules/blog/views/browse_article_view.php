<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css">
    .comment_normal{
        display:none!important;
    }
    #record_content{
        margin-top: 5px;
        margin-bottom: 20px;
    }
    #record_content:empty{
        display:none;
    }
    .record_container{
        margin:10px;
    }
    .edit_delete_record_container{
        margin-top: 10px;
    }
    #record_content_bottom{
        margin-top: 5px;
        display:none;
    }
    img.photo_thumbnail{
        width: auto;
        height: 75px;
        margin: 5px;
    }
    div#article-comment{
        margin-top : 30px;
        padding-top : 20px;
        border-top : 1px solid gray;
    }
    div.comment-item{
        padding-top : 10px;
        padding-bottom : 10px;
    }
    div.comment-header{
        font-size : small;
        font-weight: bold;
    }
    div.edit_delete_record_container{
        margin-bottom:45px;
    }
    textarea[name="<?php echo $secret_code; ?>xcontent"]{
        width:90%;
        resize:none;
    }
    #search-form .form-group{
        margin-right:10px;
    }
    .text-area-comment{
        resize: none;
        word-wrap: no-wrap;
        white-space: pre-wrap;
        overflow-x: auto;
        min-width: 385px!important;
        min-height: 75px!important;
        margin-top: 10px!important;
    }
</style>

<div id="submenu_screen"><?php echo $submenu_screen; ?></div>
<form id="search-form" class="form-inline" role="form">
    <div class="form-group">
        <label class="sr-only" for="input_category">Category</label>
        <select id="input_category" class="select-category form-control">
            <?php
                foreach($categories as $key=>$value){
                    $selected = '';
                    if($key == $chosen_category){
                        $selected = 'selected = "selected"';
                    }
                    echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label class="sr-only" for="input_search">Keyword</label>
        <input type="text" name="search" value="<?php echo isset($keyword)? $keyword: ''; ?>" id="input_search" class="input-medium search-query form-control" placeholder="Keyword" />
    </div>    
    <div class="form-group">
        <input type="submit" name="submit" value="Search" id="btn_search" class="btn btn-primary" />
        <?php
            // show add record button
            if($allow_navigate_backend){
                echo '&nbsp;<a href="'.$backend_url.'/add/" class="btn btn-default add_record">Add</a>'.PHP_EOL;
            }
        ?>
    </div>
</form>
<div id="record_content">
    <?php
        if($first_data != NULL){
            echo $first_data;
        }else if(isset($article) && $article !== FALSE){
            echo '<h2>'.$article['title'].'</h2>';
            echo '('.$article['author'].', '.$article['date'].')'.br();

            // photos
            echo '<div id="small_photo_'.$article['id'].'">';            
            foreach($article['photos'] as $photo){
                echo '<a class="photo_'.$article['id'].'" href="'.base_url('modules/{{ module_path }}/assets/uploads/'.$photo['url']).'">';
                echo '<img class="photo_thumbnail" src="'.base_url('modules/{{ module_path }}/assets/uploads/thumb_'.$photo['url']).'" />';
                echo '</a>';
            }
            echo '</div>';
            echo '<div id="big_photo_'.$article['id'].'"></div>';
            echo '<script type="text/javascript">
                $(".photo_'.$article['id'].'").click(function(event){
                    LOADING = true;
                    $("#big_photo_'.$article['id'].'").hide();
                    $("#big_photo_'.$article['id'].'").html(
                        "<div class=\"col-md-12\" style=\"text-align:right; margin-bottom:10px;\"><a id=\"close_big_photo_'.$article['id'].'\" class=\"btn btn-danger\" href=\"#\"><i class=\"glyphicon glyphicon-remove\"></i></a></div>"+
                        "<img class=\"col-md-12\" src=\"" + $(this).attr("href") + "\" /><br />");
                    $("#big_photo_'.$article['id'].'").fadeIn();
                    $("html, body").animate({
                        scrollTop: $("#small_photo_'.$article['id'].'").offset().top - 60
                    }, 1000, "swing", function(){LOADING = false});
                    $(".photo_'.$article['id'].'").css("opacity", 1);            
                    $(this).css("opacity", 0.3);
                    event.preventDefault();
                });
                $("#close_big_photo_'.$article['id'].'").live("click", function(event){
                    event.preventDefault();
                    $(".photo_'.$article['id'].'").css("opacity", 1);
                    $("#big_photo_'.$article['id'].'").fadeOut();
                });
            </script>';
            

            // content
            echo '<div>';
            echo $article['content'];
            echo '<div style="clear:both;"></div>';
            echo '</div>';

            // categories
            if(count($article['categories'])>0){
                if($module_path == 'blog'){
                    $module_url = 'blog';
                }else{
                    $module_url = $module_path.'/blog';
                }
                echo '<div style="margin-bottom:20px;">';
                echo '<b>Categories</b> :&nbsp;';
                foreach($article['categories'] as $category){
                    echo '<a href="'.site_url($module_url.'/index?category='.$category['name']).'"><span class="label label-primary">'.$category['name'].'</span></a>&nbsp;';
                }
                // also get related article
                if(count($article['related_article'])>0){
                    echo '<h4>{{ language:Related Article }}</h4>';
                    foreach($article['related_article'] as $related_article){
                        // get image
                        if(count($related_article['photos'])>0){
                            $photo = $related_article['photos'][0]['url'];
                            $photo = base_url('modules/'.$module_path.'/assets/uploads/'.$photo);
                        }else{
                            $photo = base_url('modules/'.$module_path.'/assets/images/text.jpeg');
                        }
                        if($module_path == 'blog'){
                            $url = site_url('blog/index/'.$related_article['article_url']);
                        }else{
                            $url = site_url($module_path.'/blog/index/'.$related_article['article_url']);
                        }
                        echo anchor($url,
                                    '<div class="row well">'.
                                    '<div class="col-md-4" style="min-height:200px; background-repeat: no-repeat;
                                        background-attachment: cover; background-position: center; 
                                        background-color:black;
                                        background-image:url(\''.$photo.'\')"></div>'.
                                    '<div class="col-md-8" style="vertical-align:top;">'.
                                        $related_article['date'].
                                        '<h4>'.$related_article['title'].'</h4>'.
                                    '</div>'.
                                    '</div>');
                    }
                }
                echo '</div>';
            }
            // edit and delete button
            if($allow_navigate_backend){
                echo '<div class="edit_delete_record_container">';
                if($is_super_admin || $article['author_user_id'] == $user_id){
                    echo '<a href="'.$backend_url.'/edit/'.$article['id'].'" class="btn btn-default edit_record" primary_key = "'.$article['id'].'">Edit</a>';
                    echo '&nbsp;';
                    echo '<a href="'.$backend_url.'/delete/'.$article['id'].'" class="btn btn-danger delete_record" primary_key = "'.$article['id'].'">Delete</a>';
                }
                echo '</div>';
            }

            // comment
            echo '<a name="comment-form"></a>';
            echo '<div id="article-comment">';
            $odd_row = TRUE;
            foreach($article['comments'] as $comment){
                echo '<div class="comment-item well" style="margin-left:'.($comment['level']*20).'px;">';
                echo '<div class="comment-header">';
                echo '<img style="margin-right:20px; margin-bottom:5px; margin-top:5px; float:left;" src="'.$comment['gravatar_url'].'" />';
                echo '<span stylel="float:left;">';
                echo $comment['name'].', '.$comment['date'].br();
                if($comment['website'] != ''){
                    echo anchor($comment['website'], '('.$comment['website'].')');
                }else{
                    echo '&nbsp;';
                }
                echo '</span>';
                echo '</div>';
                echo '<div style="clear:both; margin-top:10px;">';
                echo str_replace(PHP_EOL, '<br />', $comment['content']);
                echo '</div>';
                echo '<div>';
                echo '<a id="reply_comment_link_'.$comment['comment_id'].'" class="reply_comment_link" href="#" comment_id="'.$comment['comment_id'].'">Reply</a>';
                echo '<a id="reply_cancel_link_'.$comment['comment_id'].'" class="reply_cancel_link" style="display:none;" href="#" comment_id="'.$comment['comment_id'].'">Cancel</a>';
                echo '</div>';
                echo '<div id="reply_comment_form_'.$comment['comment_id'].'"></div>';
                echo '</div>';
            }
            echo br();
            // comment form            
            if($article['allow_comment']){
                echo '<div id="comment-box">';
                echo '<a name="comment-form" style="margin-top: 50px;">&nbsp;</a>';
                echo '<h4>Comment</h4>';
                // show error message if any
                if($success !== NULL){
                    if(!$success){
                        echo '<div style="margin-top: 10px;" class="alert alert-danger">'.$error_message.'</div>';
                    }else{
                        echo '<div style="margin-top: 10px;" class="alert alert-success">Success</div>';
                    }
                }
                echo form_open($form_url,'class="form  form-horizontal"');
                echo form_hidden('article_id', $article['id']);
                echo form_input(array('name'=>'name', 'value'=>'', 'class'=>'comment_normal'));
                echo form_input(array('name'=>'email', 'value'=>'', 'class'=>'comment_normal'));
                echo form_input(array('name'=>'website', 'value'=>'', 'class'=>'comment_normal'));
                echo form_input(array('name'=>'content', 'value'=>'', 'class'=>'comment_normal'));
                echo form_input(array('name'=>'parent_comment_id', 'value'=>$parent_comment_id, 'id'=>'parent_comment_id', 'class'=>'comment_normal'));
                if(!$is_user_login){

                    echo '<div class="form-group">';
                    echo form_label('Name', ' for="" class="control-label col-sm-2');
                    echo '<div class="col-sm-8">';
                    echo form_input($secret_code.'xname', $name, 
                        'id="'.$secret_code.'xname" placeholder="Your name" class="form-control"');
                    echo '</div>';
                    echo '</div>';

                    echo '<div class="form-group">';
                    echo form_label('Email', ' for="" class="control-label col-sm-2');
                    echo '<div class="col-sm-8">';
                    echo form_input($secret_code.'xemail', $email, 
                        'id="'.$secret_code.'xemail" placeholder="Your email address" class="form-control"');
                    echo '</div>';
                    echo '</div>';
                }
                echo '<div class="form-group">';
                echo form_label('Website', ' for="" class="control-label col-sm-2');
                echo '<div class="col-sm-8">';
                echo form_input($secret_code.'xwebsite', $website, 
                    'id="'.$secret_code.'xwebsite" placeholder="Your website" class="form-control"');
                echo '</div>';
                echo '</div>';

                echo '<div class="form-group">';
                echo form_label('Comment', ' for="" class="control-label col-sm-2');
                echo '<div class="col-sm-8">';
                echo form_textarea($secret_code.'xcontent', $content, 
                    'id="'.$secret_code.'xcontent" placeholder="Your Comment" class="form-control text-area-comment"');
                echo '</div>';
                echo '</div>';

                echo '<div class="form-group"><div class="col-sm-offset-2 col-sm-8">';
                echo form_submit('submit', 'Comment', 'class="btn btn-primary"');
                echo '</div></div>';
                echo '</div>'; // end of comment-box
            }
            echo '</div>';
        }else if(isset($article) && $article == FALSE){
            echo '<div class="alert alert-danger">No article found</div>';
        }
    ?>
</div>
<div id="record_content_bottom" class="alert alert-success">End of Page</div>
<script type="text/javascript" src="{{ base_url }}assets/nocms/js/jquery.autosize.js"></script>
<script type="text/javascript">
    var PAGE = 1;
    var URL = '<?php echo site_url($module_path."/blog/get_data"); ?>';
    var ALLOW_NAVIGATE_BACKEND = <?php echo $allow_navigate_backend ? "true" : "false"; ?>;
    var BACKEND_URL = '<?php echo $backend_url; ?>';
    var LOADING = false;
    var REQUEST;
    var RUNNING_REQUEST = false;
    var SCROLL_WORK = true;

    <?php if(isset($article)){
        echo 'SCROLL_WORK = false;';
    }
    ?>


    function fetch_more_data(async){
        if(typeof(async) == 'undefined'){
            async = true;
        }
        $('#record_content_bottom').html('Load more Article &nbsp;<img src="{{ BASE_URL }}assets/nocms/images/ajax-loader.gif" />');
        var keyword = $('#input_search').val();
        var category = $('#input_category').val();
        // Don't start another request until the first one completed
        if(RUNNING_REQUEST){
            return 0;
        }
        RUNNING_REQUEST = true;
        REQUEST = $.ajax({
            'url'  : URL,
            'type' : 'POST',
            'async': async,
            'data' : {
                'category' : category,
                'archive' : '<?=isset($_GET["archive"])? $_GET["archive"] : ""?>',
                'keyword' : keyword,
                'page' : PAGE,
            },
            'success'  : function(response){
                if(response == ''){
                    SCROLL_WORK = false;
                }
                // show contents
                $('#record_content').append(response);

                // show bottom contents
                var bottom_content = 'No more Article to show.';
                if(ALLOW_NAVIGATE_BACKEND){
                    bottom_content += '&nbsp; <a href="<?php echo $backend_url; ?>/add/" class="add_record btn btn-default">Add new</a>';
                }
                $('#record_content_bottom').html(bottom_content);
                RUNNING_REQUEST = false;
                PAGE ++;
            },
            'complete' : function(response){
                RUNNING_REQUEST = false;
            }
        });

    }

    function reset_content(){
        SCROLL_WORK = true;
        $('#record_content_bottom').show();
        $('#record_content').html('');
        PAGE = 0;
        fetch_more_data();
    }

    // main program
    $(document).ready(function(){

        $('textarea[name="<?php echo $secret_code; ?>xcontent"]').autosize();

        if(SCROLL_WORK){
            //reset_content();
            $('#record_content_bottom').show();
        }

        // delete click
        $('.delete_record').live('click',function(){
            var url = $(this).attr('href');
            var primary_key = $(this).attr('primary_key');
            if (confirm("Do you really want to delete?")) {
                $.ajax({
                    url : url,
                    dataType : 'json',
                    success : function(response){
                        if(response.success){
                            $('div#record_'+primary_key).remove();
                        }
                    }
                });
            }
            return false;
        });

        // input keyup
        $('#input_search').keyup(function(){
            reset_content();
        });

        // button search click
        $('#btn_search').click(function(event){
            event.preventDefault();
            reset_content();
        });
        // input category change
        $('#input_category').change(function(){
            reset_content();
        });
        
        // reply_comment_link
        $('.reply_comment_link').click(function(event){
            var comment_id = $(this).attr('comment_id');
            // move the form
            var html = $('#comment-box').html();
            $('#reply_comment_form_'+comment_id).html(html);
            $('#comment-box').html('');
            // initialize parent_comment_id
            $('#parent_comment_id').val(comment_id);
            // hide this, and show that
            $(this).hide();
            $('#reply_cancel_link_'+comment_id).show();
            // that's enough, no redirect please
            event.preventDefault();
        });
        
        // reply_cancel_link
        $('.reply_cancel_link').click(function(event){
            var comment_id = $(this).attr('comment_id');
            // move the form
            var html = $('#reply_comment_form_'+comment_id).html();
            $('#comment-box').html(html);
            $('#reply_comment_form_'+comment_id).html('');
            // initialize parent_comment_id
            $('#parent_comment_id').val('');
            // hide this, and show that
            $(this).hide();
            $('#reply_comment_link_'+comment_id).show();
            event.preventDefault();
        });

        // scroll
        $(window).scroll(function(){
            if(!LOADING && SCROLL_WORK){
                if($('#record_content_bottom').position().top <= $(window).scrollTop() + $(window).height() ){
                    LOADING = true;
                    fetch_more_data(true);
                    LOADING = false;
                }
            }
        });

    });

</script>