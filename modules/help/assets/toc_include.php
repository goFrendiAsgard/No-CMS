<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/nocms/js/jquery_snippet/jquery.snippet.css';?>" />
<style type="text/css">
    div#toc, div.toggle_toc{
        background-color :white;
        padding : 5px;  
        -moz-box-shadow:    inset 0 0 10px #000000;
        -webkit-box-shadow: inset 0 0 10px #000000;
        box-shadow:         inset 0 0 10px #000000;
        margin-left : 5px;
        margin-right : 5px;
    }
    div.toggle_toc{
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
<script type="text/javascript" src ="<?php echo base_url().'assets/nocms/js/jquery.js';?>"></script>
<script type="text/javascript" src ="<?php echo base_url().'assets/nocms/js/jquery_snippet/jquery.snippet.js';?>"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".toggle_toc").click(function(){
            $("#toc").slideToggle('slow');
            return false;
        });
        
        $.ajax({
            url : '<?php echo site_url($cms['module_path']).'?_only_content=true';?>',
            success : function(response){
                $("#toc").html(response);
            }
        })
        
        $("pre.phpSnippet").snippet(
            "php",{
                style:"ide-eclipse",
                clipboard:"<?php echo base_url().'assets/jquery_snippet/ZeroClipboard.swf';?>",
                showNum:false}
        );

        $("pre.htmlSnippet").snippet(
                "html",{
                    style:"ide-eclipse",
                    clipboard:"<?php echo base_url().'assets/jquery_snippet/ZeroClipboard.swf';?>",
                    showNum:false}
            );
    })
</script>
<div id="toc"></div>
<div class="toggle_toc"><a class="toggle_toc" href="#toc">Table of Contents</a></div>
<div style="clear:both;"></div>
