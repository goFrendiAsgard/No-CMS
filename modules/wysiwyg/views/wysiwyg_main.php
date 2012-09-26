<style type="text/css">
    div#wysiwyg{
        border : 1px solid black;
        background : white;
        padding : 10px;
    }
    div#wysiwyg div{		
        min-width : 20px;
        min-height : 20px;
        overflow : auto;		
    }
    div#wysiwyg div.clear{		
        min-width : 0px;
        min-height : 0px;	
    }
    div#wysiwyg div.float-left{
        float : left;
        display : block;
    }
    div#wysiwyg div.float-right{
        float : right;
        display : block;
    }
    div#wysiwyg div.clear, div#wysiwyg_setting div.clear{
        clear : both;
    }
    div#wysiwyg div.min-height-100{
        min-height : 100px;
    }
    div#wysiwyg div.min-height-40{
        min-height : 40px;
    }
    div#wysiwyg div.padding-10{
        padding : 10px;
    }
    div#wysiwyg div#center{
        border-top : 1px solid gray;
        border-bottom : 1px solid gray;
        padding-top : 10px;
        padding-bottom : 10px;
    }
    div#wysiwyg div#left{
        width : 250px;
        border-right : 1px solid gray;
    }
    div#wysiwyg div#right{
        width : 150px;
        border-left : 1px solid gray;
    }
    div#wysiwyg div#favicon{
        background-color: #0066FF;
        padding : 1px;
    }
    div#wysiwyg div#favicon img{
        width : 20px;
    }
    div#wysiwyg .font-size-xx-large{
        font-size : xx-large;
    }
    div#wysiwyg .font-size-x-large{
        font-size : x-large;
    }
    div#wysiwyg .font-size-large{
        font-size : large;
    }
    div#wysiwyg .hidden{
        display : none
    }
    div#wysiwyg ul{
        list-style-type: none;
        -webkit-padding-start: 15px;
    }
    div#wysiwyg div#quicklink{
        padding : 1px;
    }
    div#wysiwyg div#quicklink span.quicklink{
        border : 1px solid lightgray;
        margin-right : 5px;
        font-size : small;
        padding : 2px;
    }
    div#wysiwyg_setting{
        margin-top : 10px;
        margin-bottom : 10px;
        width:250px;
    }
    div#wysiwyg_setting div.form_label{
        width : 250px;
        display: block;
        float:left;
    }
    div#wysiwyg_setting div.form_input{
        float:left;
        width:250px;
    }
    div#logo{
    	max-width: 120px;
    }
    img.image-logo{
    	max-width: 100px;
    	height: auto;
    }
    div#name{
    	min-height: 40px!important;
    	padding: 5px;
    }
    div#slogan{
    	min-height: 25px!important;
    	padding: 5px;
    }
    div#quicklink{
    	min-height: 35px!important;
    }
    div#wysiwyg div#left ul{
        margin: 0 0 0 0;
    }
    
    div#wysiwyg a.down_widget,
    div#wysiwyg a.toggle_widget,
    div#wysiwyg a.up_widget,
    div#wysiwyg a.demote_navigation,
    div#wysiwyg a.promote_navigation,
    div#wysiwyg a.up_navigation,
    div#wysiwyg a.down_navigation,
    div#wysiwyg a.toggle_navigation,
    div#wysiwyg a.left_quicklink,
    div#wysiwyg a.right_quicklink,
    div#wysiwyg a.remove_quicklink
    {
    	display: none;
    }
    
    img.image-logo, img.image-favicon,
    span.text_content, div#name, div#slogan, div#footer
	{
		cursor:pointer;
	}
	
	span.inactive{
    	color: #D3D3D3;
    }
	
	/* small */
    @media (max-width: 479px){
    	div#content{
    		width: 100%!important;
    		float: left;
    	}
    	div#left{
	        width:100%;
	        float: right!important;
	    }
	    div#right{
	    	width: 100%!important;
	    	float: right;
	    }
    }
    /* large */
    @media (min-width: 480px){
    	div#right{
	    	float: right;
	    }
    	div#content{
    		float: left;
    	}
    	div#left{
	        float: left;
	    }
	    
    }
    
</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/nocms/js/fileuploader/fileuploader.css" />

<script type="text/javascript" src="<?php echo base_url(); ?>assets/nocms/js/fileuploader/fileuploader.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/nocms/js/jquery.js"></script>
<script type="text/javascript">
	<?php 
		$MODULE_URL = base_url('modules/'.$cms["module_path"]);
		$CONTROLLER_URL = site_url($cms["module_path"].'/wysiwyg');
	?>
	var MODULE_URL = '<?php echo $MODULE_URL; ?>';
    var CONTROLLER_URL = '<?php echo $CONTROLLER_URL; ?>'; 
    
    function parse_navigation(objs){
        var html="";
        var inactive_class = "";
        if(objs.length>0){
            html +="<ul>";
            for(var i=0; i<objs.length; i++){
                obj = objs[i];
                if(!obj.active){
                    inactive_class = "inactive";
                }else{
                    inactive_class = "";
                }
                html += '<li>';
                html += '<span class="text_content '+inactive_class+'">'+obj.title+'</span>';
                html += '<input type="hidden" class="navigation_id" value="'+obj.id+'" />';
                if(!obj.is_root){
                    html += ' <a href="#" class="promote_navigation"><img width="10px" src="'+MODULE_URL+'/assets/images/left.png" /></a>';
                }
                if(i>0){
                    html += ' <a href="#" class="demote_navigation"><img width="10px" src="'+MODULE_URL+'/assets/images/right.png" /></a>';
                    html += ' <a href="#" class="up_navigation"><img width="10px" src="'+MODULE_URL+'/assets/images/up.png" /></a>';
                }  
                if(i<(objs.length-1)){
                    html += ' <a href="#" class="down_navigation"><img width="10px" src="'+MODULE_URL+'/assets/images/down.png" /></a>';
                }                
                if(obj.active){
                    html += ' <a href="#" class="toggle_navigation"><img width="10px" src="'+MODULE_URL+'/assets/images/eye-open.png" /></a>';
                }else{
                    html += ' <a href="#" class="toggle_navigation"><img width="10px" src="'+MODULE_URL+'/assets/images/eye-close.png" /></a>';
                }
                html += parse_navigation(obj.children);
                html += '</li>';
            }
            html += "</ul>";
        }
        return html;
    }
    
    function get_navigation(){
        $.ajax({
            "url" : CONTROLLER_URL+"/get_navigation",
            "dataType" : "json",
            "type" : "POST",
            "success" : function(response){
                var str = parse_navigation(response);
                $("div#wysiwyg #left").html(str);
            }
        })
    }
    
    function parse_quicklink(objs){
        var html = "";
        if(objs.length>0){
            for(var i=0; i<objs.length; i++){
                obj = objs[i];
                html+='<span class="quicklink">';
                html += '<span class="text_content">'+obj.title+'</span>';
                html += '<input type="hidden" class="quicklink_id" value="'+obj.id+'" />';
                if(i>0){
                    html += ' <a href="#" class="left_quicklink"><img width="10px" src="'+MODULE_URL+'/assets/images/left.png" /></a>';
                }  
                if(i<(objs.length-1)){
                    html += ' <a href="#" class="right_quicklink"><img width="10px" src="'+MODULE_URL+'/assets/images/right.png" /></a>';
                }
                html += ' <a href="#" class="remove_quicklink"><img width="10px" src="'+MODULE_URL+'/assets/images/delete.png" /></a>';
                html+="</span>";
            }
        }
        return html;
    }
    
    function get_quicklink(){
        $.ajax({
            "url" :  CONTROLLER_URL+"/get_quicklink",
            "dataType" : "json",
            "type" : "POST",
            "success" : function(response){
                var str = parse_quicklink(response);
                $("div#wysiwyg #quicklink").html(str);
            }
        })
    }
    
    function parse_widget(objs){
        var html = "";
        var inactive_class = "";
        if(objs.length>0){
            for(var i=0; i<objs.length; i++){
                obj = objs[i];
                html+='<div class="widget">';
                if(!obj.active){
                    inactive_class = "inactive";
                }else{
                    inactive_class = "";
                }
                html += '<span class="text_content '+inactive_class+'">'+obj.title+'</span>';
                html += '<input type="hidden" class="widget_id" value="'+obj.id+'" />';
                html += '<input type="hidden" class="widget_slug" value="'+obj.slug+'" />';
                if(i>0){
                    html += ' <a href="#" class="up_widget"><img width="10px" src="'+MODULE_URL+'/assets/images/up.png" /></a>';
                }  
                if(i<(objs.length-1)){
                    html += ' <a href="#" class="down_widget"><img width="10px" src="'+MODULE_URL+'/assets/images/down.png" /></a>';
                }
                if(obj.active){
                    html += ' <a href="#" class="toggle_widget"><img width="10px" src="'+MODULE_URL+'/assets/images/eye-open.png" /></a>';
                }else{
                    html += ' <a href="#" class="toggle_widget"><img width="10px" src="'+MODULE_URL+'/assets/images/eye-close.png" /></a>';
                }
                html+="</div>";
                
            }
        }
        return html;
    }
    
    function get_widget(){
        $.ajax({
            "url" : CONTROLLER_URL+'/get_widget/sidebar',
            "dataType" : "json",
            "type" : "POST",
            "success": function(response){
                var str = parse_widget(response);
                $("div#wysiwyg #sidebar").html(str);
            }
        });       
        
        //load the advertisement
        $.ajax({
            "url" :  CONTROLLER_URL+'/get_widget/advertisement',
            "dataType" : "json",
            "type" : "POST",
            "success": function(response){
                var str = parse_widget(response);
                $("div#wysiwyg #advertisement").html(str);
            }
        }); 
    }
    
    function reload_all(){
        get_navigation();
        get_quicklink();
        get_widget();
    }
    
    
	
    $(document).ready(function(){
        reload_all();
        $('div#upload-favicon').hide();
        $('div#upload-logo').hide();
        
        //change the name
        $("div#wysiwyg div#name").click(function(){
        	$("div#wysiwyg input#change_name").css('visibility', 'visible');
            $("div#wysiwyg input#change_name").toggle();            
            $("div#wysiwyg input#change_name").val($("div#wysiwyg div#name").html());
            $("div#wysiwyg input#change_name").select(); 
        });
        $("div#wysiwyg input#change_name").keyup(function(event){
            if(event.keyCode==13){
                $(this).hide();
                return true;
            }
            var value = $(this).val();
            $.ajax({
                "url" :  CONTROLLER_URL+'/change_name/',
                "type" : "POST",
                "data" : {"value" : value},
                "success" : function(response){
                    $("div#wysiwyg div#name").html(value);
                }
            });
        });
        
        //change the slogan
        $("div#wysiwyg div#slogan").click(function(){
        	$("div#wysiwyg input#change_slogan").css('visibility', 'visible');
            $("div#wysiwyg input#change_slogan").toggle();            
            $("div#wysiwyg input#change_slogan").val($("div#wysiwyg div#slogan").html());
            $("div#wysiwyg input#change_slogan").select();             
        });
        $("div#wysiwyg input#change_slogan").keyup(function(event){
            if(event.keyCode==13){
                $(this).hide();
                return true;
            }
            var value = $(this).val();
            $.ajax({
                "url" :  CONTROLLER_URL+'/change_slogan/',
                "type" : "POST",
                "data" : {"value" : value},
                "success" : function(response){
                    $("div#wysiwyg div#slogan").html(value);
                }
            });
        });
        
        //change the footer
        $("div#wysiwyg div#footer").click(function(){
        	$("div#wysiwyg input#change_footer").css('visibility', 'visible');
            $("div#wysiwyg input#change_footer").toggle();
            $("div#wysiwyg input#change_footer").val($("div#wysiwyg div#footer").html());
            $("div#wysiwyg input#change_footer").select(); 
        });
        $("div#wysiwyg input#change_footer").keyup(function(event){
            if(event.keyCode==13){
                $(this).hide();
                return true;
            }
            var value = $(this).val();
            $.ajax({
                "url" :  CONTROLLER_URL+'/change_footer/',
                "type" : "POST",
                "data" : {"value" : value},
                "success" : function(response){
                    $("div#wysiwyg div#footer").html(value);
                }
            });
        });

        // upload logo
        $("div#wysiwyg img.image-logo").click(function(){
        	$("div#wysiwyg div#upload-logo").toggle();
        });
     	// upload favicon
        $("div#wysiwyg img.image-favicon").click(function(){
        	$("div#wysiwyg div#upload-favicon").toggle();
        });
        // every icon
        $("div#wysiwyg span.text_content").live('click', function(){
            $(this).parent().children('a').toggle();
        });
        
        //toggle_navigation
        $(".toggle_navigation").live('click', function(){
            var parent = $(this).parent("li");
            var navigation_id = parent.children("input.navigation_id").val();
            $.ajax({
                "url" :  CONTROLLER_URL+'/toggle_navigation',
                "type" : "POST",
                "data" : {"id" : navigation_id},
                "success" : function(){
                    get_navigation();
                }
            });
            return false;
        });
        
        //promote_navigation
        $(".promote_navigation").live('click', function(){
            var parent = $(this).parent("li");
            var navigation_id = parent.children("input.navigation_id").val();
            $.ajax({
                "url" :  CONTROLLER_URL+'/promote_navigation',
                "type" : "POST",
                "data" : {"id" : navigation_id},
                "success" : function(){
                    get_navigation();
                }
            });
            return false;
        });
        
        //demote_navigation
        $(".demote_navigation").live('click', function(){
            var parent = $(this).parent("li");
            var navigation_id = parent.children("input.navigation_id").val();
            $.ajax({
                "url" :  CONTROLLER_URL+'/demote_navigation',
                "type" : "POST",
                "data" : {"id" : navigation_id},
                "success" : function(){
                    get_navigation();
                }
            });
            return false;
        });
        
        //up_navigation
        $(".up_navigation").live('click', function(){
            var parent = $(this).parent("li");
            var navigation_id = parent.children("input.navigation_id").val();
            $.ajax({
                "url" :  CONTROLLER_URL+'/up_navigation',
                "type" : "POST",
                "data" : {"id" : navigation_id},
                "success" : function(){
                    get_navigation();
                }
            });
            return false;
        });
        
        //dow_navigation
        $(".down_navigation").live('click', function(){
            var parent = $(this).parent("li");
            var navigation_id = parent.children("input.navigation_id").val();
            $.ajax({
                "url" :  CONTROLLER_URL+'/down_navigation',
                "type" : "POST",
                "data" : {"id" : navigation_id},
                "success" : function(){
                    get_navigation();
                }
            });
            return false;
        });
        
        //left_quicklink
        $(".left_quicklink").live('click', function(){
            var parent = $(this).parent("span");
            var quicklink_id = parent.children("input.quicklink_id").val();
            $.ajax({
                "url" :  CONTROLLER_URL+'/left_quicklink',
                "type" : "POST",
                "data" : {"id" : quicklink_id},
                "success" : function(){
                    get_quicklink();
                }
            });
            return false;
        });
        
        //right_quicklink
        $(".right_quicklink").live('click', function(){
            var parent = $(this).parent("span");
            var quicklink_id = parent.children("input.quicklink_id").val();
            $.ajax({
                "url" :  CONTROLLER_URL+'/right_quicklink',
                "type" : "POST",
                "data" : {"id" : quicklink_id},
                "success" : function(){
                    get_quicklink();
                }
            });
            return false;
        });
        
        //remove_quicklink
        $(".remove_quicklink").live('click', function(){
            var parent = $(this).parent("span");
            var quicklink_id = parent.children("input.quicklink_id").val();
            $.ajax({
                "url" :  CONTROLLER_URL+'/remove_quicklink',
                "type" : "POST",
                "data" : {"id" : quicklink_id},
                "success" : function(){
                    get_quicklink();
                }
            });
            return false;
        });
        
        //add_quicklink
        $("#add_quicklink").click(function(){
            var navigation_id = $("#navigation_list option:selected").val();
            $.ajax({
                "url" :  CONTROLLER_URL+'/add_quicklink',
                "type" : "POST",
                "data" : {"id" : navigation_id},
                "success" : function(){
                    get_quicklink();
                }
            });
            return false;
        });
        
        //change language
        $("#language_list").click(function(){
            var language = $("#language_list option:selected").val();
            $.ajax({
                "url" :  CONTROLLER_URL+'/change_language',
                "type" : "POST",
                "data" : {"value" : language},
                "success" : function(response){
                    reload_all();
                }
            })
        });
        
        //toggle_navigation
        $(".toggle_widget").live('click', function(){
            var parent = $(this).parent("div");
            var widget_id = parent.children("input.widget_id").val();
            $.ajax({
                "url" :  CONTROLLER_URL+'/toggle_widget',
                "type" : "POST",
                "data" : {"id" : widget_id},
                "success" : function(){
                    get_widget();
                }
            });
            return false;
        });
        
        //up_widget
        $(".up_widget").live('click', function(){
            var parent = $(this).parent("div");
            var widget_id = parent.children("input.widget_id").val();
            $.ajax({
                "url" :  CONTROLLER_URL+'/up_widget',
                "type" : "POST",
                "data" : {"id" : widget_id},
                "success" : function(){
                    get_widget();
                }
            });
            return false;
        });
        
        //down_widget
        $(".down_widget").live('click', function(){
            var parent = $(this).parent("div");
            var widget_id = parent.children("input.widget_id").val();
            $.ajax({
                "url" :  CONTROLLER_URL+'/down_widget',
                "type" : "POST",
                "data" : {"id" : widget_id},
                "success" : function(){
                    get_widget();
                }
            });
            return false;
        });
        
        
        
        
    });
    
    jQuery(function(){
        new qq.FileUploader({
            element: $("div#upload-favicon")[0],
            action: CONTROLLER_URL+'/upload_favicon',
            allowedExtensions: ['jpeg', 'jpg', 'gif', 'png', 'ico'],        
            // each file size limit in bytes
            // this option isn't supported in all browsers
            sizeLimit: 3072000, // max size   
            minSizeLimit: 0, // min size
            onComplete: function(id, fileName, responseJSON){
                if(responseJSON["success"]){
                    $.ajax({
                        url: CONTROLLER_URL+'/get_favicon',
                        dataType:'json',
                        success : function(response){
                            $('.image-favicon').attr('src',response['value']);
                        }
                    })
                }
            }
        });
        
        new qq.FileUploader({
            element: $("div#upload-logo")[0],
            action: CONTROLLER_URL+'/upload_logo',
            allowedExtensions: ['jpeg', 'jpg', 'gif', 'png', 'ico'],
            // each file size limit in bytes
            // this option isn't supported in all browsers
            sizeLimit: 10240000, // max size   
            minSizeLimit: 0, // min size
            onComplete: function(id, fileName, responseJSON){
                if(responseJSON["success"]){
                    $.ajax({
                        url: CONTROLLER_URL+'/get_logo',
                        dataType:'json',
                        success : function(response){
                            $('.image-logo').attr('src',response['value']);
                        }
                    })
                }
            }
        });

    })
    
    
</script>
<div id="wysiwyg-container">
	<div id="wysiwyg">   
	    <div id="favicon">
	        <img class="image-favicon" src="<?php echo $site_favicon; ?>" style="float:left;"></img>	         
	    </div>
	    <div id="upload-favicon" style="float:left;">       
	        <noscript>          
	            <p>Please enable JavaScript to use file uploader.</p>
	        </noscript>         
	    </div>
	    <div id="header" class="padding-10">
	        <div id="logo" class="float-left">
	        	<img class="image-logo" src="<?php echo $site_logo; ?>" />
	        	<div id="upload-logo" style="float:left;">       
		        	<noscript>          
		            	<p>Please enable JavaScript to use file uploader.</p>
		            </noscript>         
		        </div> 
	        </div>
	        
	        <div class="float-left">
	            <div id="name" class="font-size-xx-large"><?php echo $site_name ?></div>
	            <input id="change_name" class="hidden" />
	            <div id="slogan" class="font-size-x-large"><?php echo $site_slogan ?></div>
	            <input id="change_slogan" class="hidden" />                    
	        </div>
	        <div class="clear"></div>
	        <div id="quicklink" class="font-size-large">Quick Link</div>
	        <div class="clear"></div>
	    </div>
	    <div id="center">  
	        <div id="left" class="min-height-100">Left Panel</div>	
	        <div id="content" class="min-height-100 padding-10">
	        	This is the content
	        	<div id="wysiwyg_setting">
				    <div class="form_label">Add Quick Link : </div> 
			        <div class="form_input">
			            <?php echo form_dropdown('navigation', $navigation_list, NULL,'id="navigation_list"'); ?>&nbsp;
			            <a href="#" id="add_quicklink"><img width="20px" src="<?php echo $MODULE_URL;?>/assets/images/add.png" /></a>
			        </div>
			        <div class="clear"></div>
				    <div class="form_label">Change Language : </div> 
			        <div class="form_input">
			            <?php echo form_dropdown('navigation', $language_list, $language,'id="language_list"'); ?>
			        </div>	
				</div>
				<a href="<?php echo site_url($cms["module_path"]);?>">See the changes</a>
	        </div>
	        <div id="right" class="min-height-100 padding-10">
	            <div><b>Side-bar</b></div>
	            <div id="sidebar"></div>
	            <div><b>Advertisement</b></div>
	            <div id="advertisement"></div>
	        </div> 
	        
	        <div class="clear"></div>
	    </div>
	    <div id="footer" class="padding-10"><?php echo $site_footer?></div>  
	    <input id="change_footer" class="hidden" />
	</div>
	
</div>


