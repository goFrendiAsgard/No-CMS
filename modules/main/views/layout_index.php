<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

    $option_tag = '';
    $selected = 'selected';
    foreach($normal_widget_list as $widget){
        $widget_name = $widget['widget_name'];
        $option_tag .= '<option '.$selected.' value="{{ widget_name:'.$widget_name.' }}">widget : '.$widget_name.'</option>';
        $selected = '';
    }
    foreach($config_list as $config){
        $config_name = $config['config_name'];
        $option_tag .= '<option value="{{ '.$config_name.' }}">configuration : '.$config_name.'</option>';
        $selected = '';
    }
    
    $asset = new CMS_Asset();
    $asset->add_cms_css('grocery_crud/css/jquery_plugins/chosen/chosen.css');
    $asset->add_cms_css('grocery_crud/themes/flexigrid/css/flexigrid.css');
    echo $asset->compile_css();

    $asset->add_cms_js("grocery_crud/js/jquery_plugins/jquery.chosen.min.js");
    $asset->add_cms_js("grocery_crud/js/jquery_plugins/config/jquery.chosen.config.js");
    echo $asset->compile_js();
?>
<style type="text/css">
    .text-area-section{
        resize: none;
        white-space: nowrap; 
        overflow: auto;
        min-width: 385px!important;
        min-height: 75px!important;
        margin-top: 10px!important;
    }
</style>

<div id="div-body" class="tabbable well"> <!-- Only required for left/right tabs -->
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab1" data-toggle="tab">Sections</a></li>
        <li><a href="#tab2" data-toggle="tab">Values</a></li>
    </ul>
    <form class="form-horizontal">
        <div class="tab-content">        
            <div class="tab-pane active" id="tab1">
                
                <div class="control-group">
                   <label class="control-label" for="section_top_nav">Top Navigation Section</label>
                   <div class="controls">
                       <div class="div-normal-widget">
                           <select class="chosen-select"><?php echo $option_tag; ?></select> <a class="btn-tag-add btn btn-primary" href="#">Add Tag</a>
                       </div>
                       <textarea id="section_top_nav" name="section_top_nav" class="text-area-section"><?php echo $section_widget_list['section_top_fix']['static_content']; ?></textarea>                       
                       <p class="help-block">HTML &amp; tags of top navigation section</p>
                   </div>
                </div>
                
                <div class="control-group">
                   <label class="control-label" for="section_banner">Banner Section</label>
                   <div class="controls">
                       <div class="div-normal-widget">
                           <select class="chosen-select"><?php echo $option_tag; ?></select> <a class="btn-tag-add btn btn-primary" href="#">Add Tag</a>
                       </div>
                       <textarea id="section_banner" name="section_banner" class="text-area-section"><?php echo $section_widget_list['section_banner']['static_content']; ?></textarea>                       
                       <p class="help-block">HTML &amp; tags of banner section</p>
                   </div>
                </div>
                
                <div class="control-group">
                   <label class="control-label" for="section_left">Left Section</label>
                   <div class="controls">
                       <div class="div-normal-widget">
                           <select class="chosen-select"><?php echo $option_tag; ?></select> <a class="btn-tag-add btn btn-primary" href="#">Add Tag</a>
                       </div>
                       <textarea id="section_left" name="section_left" class="text-area-section"><?php echo $section_widget_list['section_left']['static_content']; ?></textarea>                       
                       <p class="help-block">HTML &amp; tags of left Section</p>
                   </div>
                </div>
                
                <div class="control-group">
                   <label class="control-label" for="section_right">Right Section</label>
                   <div class="controls">
                       <div class="div-normal-widget">
                           <select class="chosen-select"><?php echo $option_tag; ?></select> <a class="btn-tag-add btn btn-primary" href="#">Add Tag</a>
                       </div>
                       <textarea id="section_right" name="section_right" class="text-area-section"><?php echo $section_widget_list['section_right']['static_content']; ?></textarea>                       
                       <p class="help-block">HTML &amp; tags of right section</p>
                   </div>
                </div>
                
                <div class="control-group">
                   <label class="control-label" for="section_bottom">Bottom Section</label>
                   <div class="controls">
                       <div class="div-normal-widget">
                           <select class="chosen-select"><?php echo $option_tag; ?></select> <a class="btn-tag-add btn btn-primary" href="#">Add Tag</a>
                       </div>
                       <textarea id="section_bottom" name="section_bottom" class="text-area-section"><?php echo $section_widget_list['section_bottom']['static_content']; ?></textarea>                       
                       <p class="help-block">HTML &amp; tags of bottom section</p>
                   </div>
                </div>
                         
            </div>
            <div class="tab-pane" id="tab2">
                <h2>Values</h2> 
            </div>
        </div>
    </form>
</div>
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
    
    // 
    $('.btn-tag-add').click(function(){
        var select_component = $(this).parent().children('select');
        var text_area_component = $(this).parent().parent().children('.text-area-section');
        var selected_item = select_component.val();
        text_area_component.insertAtCaret(selected_item);
        return false;
    })
</script>
