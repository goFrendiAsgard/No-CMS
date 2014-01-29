<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    function show_static_content($list, $key){
        if(key_exists($key, $list) && key_exists('static_content', $list[$key])){
            echo $list[$key]['static_content'];
        }
    };
    // option for tags
    $option_tag = '';
    $selected = 'selected';
    foreach($normal_widget_list as $widget){
        $widget_name = $widget['widget_name'];
        $option_tag .= '<option '.$selected.' value="{{ widget_name:'.$widget_name.' }}">widget : '.$widget_name.'</option>';
        $selected = '';
    }
    foreach($config_list as $config_name=>$value){
        $option_tag .= '<option value="{{ '.$config_name.' }}">configuration : '.$config_name.'</option>';
        $selected = '';
    }
    // option for languages
    $option_language = '';
    foreach($language_list as $language){
        $selected = $language == $current_language ? 'selected' : '';
        $option_language .= '<option '.$selected.' value="'.$language.'">'.ucwords($language).'</option>';
    }
    // option for layouts
    $option_layout = '<option selected value="'.$config_list['site_layout'].'">'.$config_list['site_layout'].'</option>';
    foreach($layout_list as $layout){
        if($layout != $config_list['site_layout']){
            $option_layout .= '<option value="'.$layout.'">'.$layout.'</option>';
        }
    }
    
    $asset = new CMS_Asset();
    $asset->add_cms_css('grocery_crud/css/jquery_plugins/chosen/chosen.css');
    //$asset->add_cms_css('grocery_crud/themes/flexigrid/css/flexigrid.css');
    echo $asset->compile_css();
?>
<style type="text/css">
    .text-area-section{
        resize: none;
        word-wrap: no-wrap;
        white-space: pre-wrap;
        overflow-x: auto;
        width:95%;
        min-width: 385px!important;
        min-height: 75px!important;
        margin-top: 10px!important;
        font-family: Courier;
        font-size: small;
    }
</style>
<div id="div-body" class="tabbable"> <!-- Only required for left/right tabs -->
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab1" data-toggle="tab">Configurations</a></li>
        <li><a href="#tab2" data-toggle="tab">Images</a></li>
        <li><a href="#tab3" data-toggle="tab">Sections</a></li>
    </ul>
    <form enctype="multipart/form-data" class="form form-horizontal" method="post">
        <div class="tab-content">
                                
            <div class="tab-pane" id="tab1"> 
                <h3>Configurations</h3>
                <div class="form-group">                   
                   <label class="control-label col-md-4" for="site_layout">Default Layout</label>                   
                   <div class="controls col-md-8">                       
                       <select id="site_language" name="site_layout" class="form-control"><?php echo $option_layout; ?></select>
                       <p class="help-block">Default layout used</p>
                   </div>
                </div>
                <div class="form-group">
                   <label class="control-label col-md-4" for="site_language">Default Language</label>
                   <div class="controls col-md-8">
                       <select id="site_language" name="site_language" class="form-control"><?php echo $option_language; ?></select>
                       <p class="help-block">Default language used</p>
                   </div>
                </div>                
                <div class="form-group">
                   <label class="control-label col-md-4" for="site_name">Site Name</label>
                   <div class="controls col-md-8">
                       <input type="text" id="site_name" name="site_name" value="<?php echo $config_list['site_name'] ?>" class="form-control">
                       <p class="help-block">Site name (e.g: No-CMS, My Company website, etc)</p>
                   </div>
                </div>
                <div class="form-group">
                   <label class="control-label col-md-4" for="site_slogan">Site Slogan</label>
                   <div class="controls col-md-8">
                       <input type="text" id="site_slogan" name="site_slogan" value="<?php echo $config_list['site_slogan'] ?>" class="form-control">
                       <p class="help-block">Your site slogan (e.g: "There is no place like home", "Song song and song", etc)</p>
                   </div>
                </div>
                <div class="form-group">
                   <label class="control-label col-md-4" for="site_footer">Site Footer</label>
                   <div class="controls col-md-8">
                       <input type="text" id="site_footer" name="site_footer" value="<?php echo $config_list['site_footer'] ?>" class="form-control">
                       <p class="help-block">Site footer &amp; attribution (e.g: "Powered by No-CMS © 2013", etc)</p>
                   </div>
                </div>
            </div>
            
            <div class="tab-pane" id="tab2">
                <h3>Images</h3>
                <div class="form-group">
                   <label class="control-label col-md-4" for="site_logo">Site Logo</label>
                   <div class="controls col-md-8">
                       <img src="<?php echo $config_list['site_logo'] ?>"><br>
                       <input type="file" id="site_logo" name="site_logo" class="form-control">
                       <p class="help-block">Image used as site Logo</p>
                   </div>
                </div>                
                <div class="form-group">
                   <label class="control-label col-md-4" for="site_favicon">Site Favicon</label>
                   <div class="controls col-md-8">
                       <img src="<?php echo $config_list['site_favicon'] ?>"><br>
                       <input type="file" id="site_favicon" name="site_favicon" class="form-control">
                       <p class="help-block">Image used as favicon</p>
                   </div>
                </div>
            </div>
            
            <div class="tab-pane active" id="tab3">
                <h3>Sections</h3>
                <div class="form-group">
                   <label class="control-label col-md-4" for="section_top_fix">Custom Script</label>
                   <div class="controls col-md-8">
                       <div class="div-normal-widget">
                           <select class="chosen-select"><?php echo $option_tag; ?></select> <a class="btn-tag-add btn btn-primary" href="#">Add Tag</a>
                       </div>
                       <textarea id="section_custom_script" name="section_custom_script" class="text-area-section"><?php show_static_content($section_widget_list, 'section_custom_script'); ?></textarea>                       
                       <p class="help-block">HTML &amp; tags of top section</p>
                   </div>
                </div> 
                <div class="form-group">
                   <label class="control-label col-md-4" for="section_top_fix">Top Section</label>
                   <div class="controls col-md-8">
                       <div class="div-normal-widget">
                           <select class="chosen-select"><?php echo $option_tag; ?></select> <a class="btn-tag-add btn btn-primary" href="#">Add Tag</a>
                       </div>
                       <textarea id="section_top_fix" name="section_top_fix" class="text-area-section"><?php echo show_static_content($section_widget_list, 'section_top_fix'); ?></textarea>                       
                       <p class="help-block">HTML &amp; tags of top section</p>
                   </div>
                </div> 
                <div class="form-group">
                   <label class="control-label col-md-4" for="section_top_fix">Navigation Bar's Right Partial</label>
                   <div class="controls col-md-8">
                       <div class="div-normal-widget">
                           <select class="chosen-select"><?php echo $option_tag; ?></select> <a class="btn-tag-add btn btn-primary" href="#">Add Tag</a>
                       </div>
                       <textarea id="navigation_right_partial" name="navigation_right_partial" class="text-area-section"><?php show_static_content($section_widget_list, 'navigation_right_partial'); ?></textarea>                       
                       <p class="help-block">HTML &amp; tags of navigation bar's right partial (don't put too much thing here)</p>
                   </div>
                </div>               
                <div class="form-group">
                   <label class="control-label col-md-4" for="section_banner">Banner Section</label>
                   <div class="controls col-md-8">
                       <div class="div-normal-widget">
                           <select class="chosen-select"><?php echo $option_tag; ?></select> <a class="btn-tag-add btn btn-primary" href="#">Add Tag</a>
                       </div>
                       <textarea id="section_banner" name="section_banner" class="text-area-section"><?php show_static_content($section_widget_list, 'section_banner'); ?></textarea>                       
                       <p class="help-block">HTML &amp; tags of banner section</p>
                   </div>
                </div>                
                <div class="form-group">
                   <label class="control-label col-md-4" for="section_left">Left Section</label>
                   <div class="controls col-md-8">
                       <div class="div-normal-widget">
                           <select class="chosen-select"><?php echo $option_tag; ?></select> <a class="btn-tag-add btn btn-primary" href="#">Add Tag</a>
                       </div>
                       <textarea id="section_left" name="section_left" class="text-area-section"><?php show_static_content($section_widget_list, 'section_left'); ?></textarea>                       
                       <p class="help-block">HTML &amp; tags of left Section</p>
                   </div>
                </div>                
                <div class="form-group">
                   <label class="control-label col-md-4" for="section_right">Right Section</label>
                   <div class="controls col-md-8">
                       <div class="div-normal-widget">
                           <select class="chosen-select"><?php echo $option_tag; ?></select> <a class="btn-tag-add btn btn-primary" href="#">Add Tag</a>
                       </div>
                       <textarea id="section_right" name="section_right" class="text-area-section"><?php show_static_content($section_widget_list, 'section_right'); ?></textarea>                       
                       <p class="help-block">HTML &amp; tags of right section</p>
                   </div>
                </div>
                <div class="form-group" style="height:260px;">
                   <label class="control-label col-md-4" for="section_bottom">Bottom Section</label>
                   <div class="controls col-md-8">
                       <div class="div-normal-widget">
                           <select class="chosen-select"><?php echo $option_tag; ?></select> <a class="btn-tag-add btn btn-primary" href="#">Add Tag</a>
                       </div>
                       <textarea id="section_bottom" name="section_bottom" class="text-area-section"><?php show_static_content($section_widget_list, 'section_bottom'); ?></textarea>                       
                       <p class="help-block">HTML &amp; tags of bottom section</p>
                   </div>
                </div>                
            </div>
            
        </div>
        <input type="submit" class="btn btn-primary btn-lg" value="Apply Changes">
    </form>    
</div>
<script type="text/javascript" src="{{ base_url }}assets/nocms/js/jquery.autosize.js"></script>
<?php
    $asset->add_cms_js("grocery_crud/js/jquery_plugins/jquery.chosen.min.js");
    $asset->add_cms_js("grocery_crud/js/jquery_plugins/config/jquery.chosen.config.js");
    echo $asset->compile_js();
?>
<script type="text/javascript">
    // magic to do insertAtCaret
    $.fn.extend({
    insertAtCaret: function(myValue){
      return this.each(function(i) {
        if (document.selection) {
          //For browsers like Internet Explorer
          this.focus();
          var sel = document.selection.createRange();
          sel.text = myValue;
          this.focus();
        }
        else if (this.selectionStart || this.selectionStart == '0') {
          //For browsers like Firefox and Webkit based
          var startPos = this.selectionStart;
          var endPos = this.selectionEnd;
          var scrollTop = this.scrollTop;
          this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
          this.focus();
          this.selectionStart = startPos + myValue.length;
          this.selectionEnd = startPos + myValue.length;
          this.scrollTop = scrollTop;
        } else {
          this.value += myValue;
          this.focus();
        }
      });
    }
    });

    $(document).ready(function(){
        // when calling chosen, the select should be visible, that's why I need to do this:
        $('#tab3').removeClass('active');
        $('#tab1').addClass('active');
        // make text area autosize
        $('.text-area-section').autosize();

        // add widget or whatever to the section at current caret
        $('.btn-tag-add').click(function(){
            var select_component = $(this).parent().children('select');
            var text_area_component = $(this).parent().parent().children('.text-area-section');
            var selected_item = select_component.val();
            text_area_component.insertAtCaret(selected_item);
            return false;
        });
    })
</script>
