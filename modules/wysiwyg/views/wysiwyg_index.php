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
    div#wysiwyg div.float-left{
        float : left;
        display : block;
    }
    div#wysiwyg div.float-right{
        float : right;
        display : block;
    }
    div#wysiwyg div.clear{
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
        width : 200px;
        border-right : 1px solid gray;
    }
    div#wysiwyg div#right{
        width : 200px;
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
</style>
<script type="text/javascript">
    function adjust_width(){
        var wysiwyg_width = $("div#wysiwyg").width();
        $("div#center").width(wysiwyg_width-2);
        $("div#header").width(wysiwyg_width-2);
		
        var center_width = $("div#center").width();
        var left_width = $("div#left").width();
        var right_width = $("div#right").width();		
        $("div#content").width(center_width-left_width-right_width-6-60);
		
    }
	
    $(document).ready(function(){
        adjust_width();
        
        //change the name
        $("div#wysiwyg div#name").click(function(){
            $("div#wysiwyg input#change_name").toggle();
             $("div#wysiwyg input#change_name").val($("div#wysiwyg div#name").html());
        });
        $("div#wysiwyg input#change_name").keyup(function(){
            var value = $(this).val();
            $.ajax({
                "url" : "wysiwyg/change_name/",
                "type" : "POST",
                "data" : {"value" : value},
                "success" : function(response){
                    $("div#wysiwyg div#name").html(value);
                }
            });
        });
        
        //change the slogan
        $("div#wysiwyg div#slogan").click(function(){
            $("div#wysiwyg input#change_slogan").toggle();
             $("div#wysiwyg input#change_slogan").val($("div#wysiwyg div#slogan").html());
        });
        $("div#wysiwyg input#change_slogan").keyup(function(){
            var value = $(this).val();
            $.ajax({
                "url" : "wysiwyg/change_slogan/",
                "type" : "POST",
                "data" : {"value" : value},
                "success" : function(response){
                    $("div#wysiwyg div#slogan").html(value);
                }
            });
        });
        
        //change the footer
        $("div#wysiwyg div#footer").click(function(){
            $("div#wysiwyg input#change_footer").toggle();
             $("div#wysiwyg input#change_footer").val($("div#wysiwyg div#footer").html());
        });
        $("div#wysiwyg input#change_footer").keyup(function(){
            var value = $(this).val();
            $.ajax({
                "url" : "wysiwyg/change_footer/",
                "type" : "POST",
                "data" : {"value" : value},
                "success" : function(response){
                    $("div#wysiwyg div#footer").html(value);
                }
            });
        });
        
    });
    
    $(document).resize(function(){
        adjust_width();
    });	
    
    
</script>
<div id="wysiwyg">
    <div id="favicon"><img src="<?php echo $site_favicon; ?>" /></div>
    <div id="header" class="padding-10">
        <div id="logo" class="float-left"><img src="<?php echo $site_logo; ?>" /></div>
        <form id="change_logo">
        </form>    
        <div class="float-left">
        <div id="name" class="font-size-xx-large"><?php echo $site_name ?></div>
        <input id="change_name" class="hidden" />
        <div id="slogan" class="font-size-x-large"><?php echo $site_slogan ?></div>
        <input id="change_slogan" class="hidden" />
        <div id="quicklink" class="font-size-large">Quick Link</div>
        </div>
        <div class="clear"></div>
    </div>
    <div id="center">
        <div id="left" class="float-left min-height-100 padding-10">Left Panel</div>
        <div id="right" class="float-right min-height-100 padding-10">Right Panel</div>
        <div id="content" class="float-left min-height-100 padding-10">This is the content</div>
    </div>
    <div id="footer" class="padding-10"><?php echo $site_footer?></div>  
    <input id="change_footer" class="hidden" />
</div>
*) You need to refresh the page (F5) to see the real changes