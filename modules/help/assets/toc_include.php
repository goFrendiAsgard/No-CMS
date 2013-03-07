<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/nocms/js/jquery_snippet/jquery.snippet.css';?>" />
<style type="text/css">
    div#toc, div.toggle_toc{
        background-color :white;
        -moz-box-shadow:    inset 0 0 10px #000000;
        -webkit-box-shadow: inset 0 0 10px #000000;
        box-shadow:         inset 0 0 10px #000000;
        margin-left : 5px;
        margin-right : 5px;
    }
    div#toc, div#toc_content{
    	padding : 10px;
    }
    div.toggle_toc{
    	padding: 5px;
        display:inline;
        float : right;
        border-bottom-left-radius : 10px;
        border-bottom-right-radius : 10px;
    }
    div#toc{
        display : none;
        border-bottom-left-radius : 10px;
        border-top-left-radius : 10px;
        border-top-right-radius : 10px;
    }
</style>
<script type="text/javascript" src ="<?php echo base_url().'assets/nocms/js/jquery_snippet/jquery.snippet.js';?>"></script>
<script type="text/javascript">
	var REQUEST_EXISTS = false;
	var REQUEST = "";
    $(document).ready(function(){
        get_toc();

        $(".toggle_toc").click(function(){
            $("#toc").slideToggle('slow');
            return false;
        });

        $("#btn_search").click(get_toc);
        $("#keyword").keyup(get_toc);

        $("pre.phpSnippet").snippet(
            "php",{
                style:"ide-eclipse",
                clipboard:"<?php echo base_url().'assets/nocms/js/jquery_snippet/ZeroClipboard.swf';?>",
                showNum:false}
        );

        $("pre.htmlSnippet").snippet(
                "html",{
                    style:"ide-eclipse",
                    clipboard:"<?php echo base_url().'assets/jquery_snippet/ZeroClipboard.swf';?>",
                    showNum:false}
            );
    });

    function get_toc(){
    	$("#img_ajax_loader").show();
        if(REQUEST_EXISTS){
        	REQUEST.abort();
        }
        REQUEST_EXISTS = true;
        REQUEST = $.ajax({
        	type : 'POST',
        	data : {
            	"keyword" : $("#keyword").val(),
            },
            url : '<?php echo site_url($cms['module_path']).'?_only_content=true';?>',
            success : function(response){
                $("#toc_content").html(response);
                REQUEST_EXISTS = false;
                $("#img_ajax_loader").hide();
            }
        });
    }
</script>
<div id="toc">
	<input id="keyword" name="keyword" type="text"></input>
	<input id="btn_search" class="btn btn-primary" name="search" type="submit" value="search"></input>
	<img id="img_ajax_loader" style="display:none;" src="<?php echo base_url('assets/nocms/images/ajax-loader.gif');?>" />
	<div style="clear:both;"></div>
	<div id="toc_content"></div>
</div>
<div class="toggle_toc"><a class="toggle_toc" href="#toc">Table of Contents</a></div>
<div style="clear:both;"></div>