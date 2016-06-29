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
    div#article-comment{
        margin-top : 30px;
        padding-top : 20px;
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
        min-height: 75px!important;
        margin-top: 10px!important;
    }
    .photo_thumbnail, .photo_more{
        width : 150px;
        height : 75px;
        margin:5px;
        display:inline-block;
    }
    .photo_thumbnail{
        background-color : black;
        background-repeat : no-repeat;
        background-position:center;
    }
    .small_photo{
        text-align:center;
    }
    .photo_caption{
        display:none;
    }
    .photo_link{
        display:inline-block;
    }
</style>

<?php if($allow_navigate_backend){?>
<div class="col-xs-12" style="margin-bottom:20px;">
    <h4>{{ language: Manage }}</h4>
    <?php echo $submenu_screen; ?>
</div>
<?php } ?>
<!-- Modal -->
<div class="modal fade col-xs-12" id="photo-modal" role="dialog">
    <div class="modal-dialog col-xs-12" style="width:100%!important;">
        <!-- Modal content-->
        <div class="modal-content" style="width:100%!important;">
            <div class="modal-header">
                <button id="btn-close-photo" style="padding:5px;" type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 id="photo-modal-title" class="modal-title" style="display:inline-block;">Image</h4>
                <button id="btn-prev-photo" class="btn btn-default" style="padding:5px">&lt;</button>
                <button id="btn-next-photo" class="btn btn-default" style="padding:5px">&gt;</button>
                <div style="clear:both"></div>
            </div>
            <div id="photo-modal-body" class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<form id="search-form" class="form-inline col-xs-12" role="form" style="margin-bottom:20px;">
    <div class="form-group">
        <label class="sr-only" for="input_category">Category</label>
        <select id="input_category" class="select-category form-control">
            <?php
                foreach($categories as $key=>$value){
                    $selected = '';
                    if($key == urldecode($chosen_category)){
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
        <button name="submit" value="Search" id="btn_search" class="btn btn-primary">
            <i class="glyphicon glyphicon-search"></i> Search
        </button>
    </div>
</form>

<?php if($allow_navigate_backend){?>
<!-- Quick Write Form -->
<div class="col-xs-12" style="margin-bottom:20px;">
    <h4>{{ language: Quick Write }}</h4>
    <form  method="post" action="<?php echo $backend_url; ?>/add/">
        <div class="col-xs-12" style="margin-bottom:10px;">
            <input id="new_article_title" name="title" class="form-control" placeholder="{{ language:Title }}" />
        </div>
        <div class="col-xs-12" style="margin-bottom:10px;">
            <textarea id="new_article_content" name="content" class="form-control" placeholder="{{ language:Content }}" style="resize:none;"></textarea>
        </div>

        <?php if($can_publish){ ?>
            <div class="col-xs-12 col-md-6" style="margin-bottom:10px;">
                <select id="new_article_status" name="status" class="form-control">
                    <option value="published" selected>Published</option>
                    <option value="draft">Draft</option>
                </select>
            </div>
        <?php } ?>
        <div class="col-xs-12 col-sm-6 col-md-3" style="margin-bottom:10px;">
            <button id="new_article_save" class="col-xs-12 btn btn-primary">
                <i class="glyphicon glyphicon-share-alt"></i> {{ language:Save }}
            </button>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3" style="margin-bottom:10px;">
            <button id="new_article_edit" class="col-xs-12 btn btn-primary">
                <i class="glyphicon glyphicon-pencil"></i> {{ language:Detail Edit }}
            </button>
        </div>
    </form>
</div>
<?php } ?>
<!-- Record contents -->
<div id="record_content" class="col-xs-12">
    <?php
        if($first_data != NULL){
            // A lot of articles
            echo $first_data;
        }else if(isset($article) && $article !== FALSE){
            // Single Article
            echo '<h2>'.$article['title'].'</h2>';
            echo '('.$article['author'].', '.$article['date'].')'.br();

            if(count($article['photos'])>0){
                // photos
                echo '<div id="small_photo_'.$article['id'].'" class="small_photo well">';
                $index = 0;
                foreach($article['photos'] as $photo){
                    $is_first = $index == 0 ? 1 : 0;
                    $is_last = $index == (count($article['photos'])-1) ? 1 : 0;
                    echo '<a data-toggle="modal" data-target="#photo-modal" class="photo_link" article_id="'.$article['id'].'" index="'.$index.'" photo_id="'.$photo['id'].'" img="'.base_url('modules/{{ module_path }}/assets/uploads/'.$photo['url']).'" is_first="'.$is_first.'" is_last="'.$is_last.'" href="#">';
                    echo '<div class="photo_thumbnail" style="background-image:url('.base_url('modules/{{ module_path }}/assets/uploads/thumb_'.$photo['url']).');"></div>';
                    echo '<div id="photo_caption_'.$photo['id'].'" class="photo_caption">'.$photo['caption'].'</div>';
                    echo '</a>';
                    $index++;
                }
                echo '</div>';
            }


            // content
            echo '<div>';
            echo $article['content'];
            echo '<div style="clear:both;"></div>';
            echo '</div>';

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
                    if($category_route_exists){
                        $url = $module_url.'/category/'.$category['name'];
                    }else{
                        $url = $module_url.'/index?category='.$category['name'];
                    }
                    echo '<a href="'.site_url($url).'"><span class="label label-primary">'.$category['name'].'</span></a> ';
                }
                // also get related article
                if(count($article['related_article'])>0){
                    echo '<div class="col-md-12 panel" style="margin-top:20px; margin-bottom:20px; padding-top:10px; padding-bottom:10px;">';
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
                                    '<div class="row col-md-6" style="margin-top:10px; margin-bottom:10px;">'.
                                    '<div class="col-md-4" style="min-height:100px; background-repeat: no-repeat;
                                        background-attachment: cover; background-position: center;
                                        background-size:cover; background-color:black;
                                        background-image:url(\''.$photo.'\')"></div>'.
                                    '<div class="col-md-8" style="vertical-align:top;">'.
                                        '<h5>'.$related_article['title'].'</h5>'.
                                        $related_article['date'].
                                    '</div>'.
                                    '</div>');
                    }
                    echo '</div>';
                }
                echo '</div>';
            }


            // comment
            echo '<a name="comment-form"></a>';
            echo '<div id="article-comment" class="col-md-12 panel">';
            echo '<h4>Comment</h4>';
            $odd_row = TRUE;
            foreach($article['comments'] as $comment){
                echo '<div class="comment-item well" style="margin-left:'.($comment['level']*20).'px;">';
                echo '<div class="comment-header">';
                echo '<img style="margin-right:20px; margin-bottom:5px; margin-top:5px; float:left; width:50px;" src="'.$comment['gravatar_url'].'" />';
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
<div class="row" style="padding-bottom:20px">
    <a id="btn_load_more" class="btn btn-default col-xs-12" style="display:none;">{{ language:Load More }}</a>
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

    function adjust_load_more_button(){
        if(SCROLL_WORK){
            if(screen.width >= 1024){
                $('#btn_load_more').hide();
                $('#record_content_bottom').show();
            }else{
                $('#btn_load_more').show();
                $('#record_content_bottom').hide();
            }
        }else{
            $('#btn_load_more').hide();
        }
    }


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
                'archive' : '<?php echo isset($_GET["archive"])? $_GET["archive"] : ""; ?>',
                'keyword' : keyword,
                'page' : PAGE,
            },
            'success'  : function(response){
                if(response.trim() == ''){
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
        adjust_load_more_button();
    }

    // main program
    $(document).ready(function(){
        adjust_load_more_button();

        $('#new_article_content').autosize();
        $('textarea[name="<?php echo $secret_code; ?>xcontent"]').autosize();

        if(SCROLL_WORK && screen.width >= 1024){
            //reset_content();
            $('#record_content_bottom').show();
        }

        // save article
        $('#new_article_save').click(function(event){
            var article_title   = $('#new_article_title').val();
            var article_content = $('#new_article_content').val();
            var article_status  = $('#new_article_status option:selected').val();
            $.ajax({
                url: '{{ module_site_url }}blog/quick_write',
                type: 'post',
                data: {
                    'title'   : article_title,
                    'content' : article_content,
                    'status'  : article_status
                },
                success: function(response){
                    $('#new_article_title').val('');
                    $('#new_article_content').val('');
                    reset_content();
                }
            });
            event.preventDefault();
        });

        // delete click
        $('body').on('click', '.delete_record',function(){
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
            if(screen.width >= 1024 && !LOADING && SCROLL_WORK){
                if($('#record_content_bottom').position().top <= $(window).scrollTop() + $(window).height() ){
                    LOADING = true;
                    fetch_more_data(true);
                    LOADING = false;
                }
            }
        });

        $('#btn_load_more').click(function(event){
            if(!LOADING && SCROLL_WORK){
                LOADING = true;
                fetch_more_data(true);
                LOADING = false;
            }
            $(this).hide();
            event.preventDefault();
        });

        // big photo
        var _PREV_PHOTO_COMPONENT = null;
        var _NEXT_PHOTO_COMPONENT = null;
        function _load_photo_by_link($component){
            var img = $component.attr('img');
            var photo_id = $component.attr('photo_id');
            var caption = $('#photo_caption_'+photo_id).html();
            var photo_index = parseInt($component.attr('index'));
            var title = '{{ language: Image }} #' + (photo_index+1);
            var article_id = $component.attr('article_id');
            var is_first = $component.attr('is_first') == '1';
            var is_last = $component.attr('is_last') == '1';
            // set next photo component and prev photo component
            if(is_last){
                _NEXT_PHOTO_COMPONENT = $('.photo_link[article_id='+article_id+'][is_first=1]');
            }else{
                _NEXT_PHOTO_COMPONENT = $('.photo_link[article_id='+article_id+'][index='+(photo_index+1)+']');
            }
            if(is_first){
                _PREV_PHOTO_COMPONENT = $('.photo_link[article_id='+article_id+'][is_last=1]');
            }else{
                _PREV_PHOTO_COMPONENT = $('.photo_link[article_id='+article_id+'][index='+(photo_index-1)+']');
            }
            $('#photo-modal-title').html(title);
            $('#photo-modal-body').html('<div style="text-align:center">'+
                '<img style="max-width:100%;" src="'+img+'" />'+
                '</div>'+
                '<div style="margin-top:20px;" class="col-xs-12">'+caption+'</div><div style="clear:both;"></div>');
        }
        // photo_link.click
        $('body').on('click', '.photo_link', function(event){
            _load_photo_by_link($(this));
        });
        // prev and next
        $('#btn-prev-photo').click(function(){
            _load_photo_by_link(_PREV_PHOTO_COMPONENT);
        });
        $('#btn-next-photo').click(function(){
            _load_photo_by_link(_NEXT_PHOTO_COMPONENT);
        });

        $(document).on('keydown',  function (e) {
            if($('#photo-modal').is(':visible')) {
                var key = e.which;
                if (key == 27) { // escape
                    $('#btn-close-photo').trigger('click');
                }else if(key == 37){
                    _load_photo_by_link(_PREV_PHOTO_COMPONENT);
                }else if(key == 39){
                    _load_photo_by_link(_NEXT_PHOTO_COMPONENT);
                }
            }
        });
    });

</script>
