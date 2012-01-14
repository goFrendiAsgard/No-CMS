<style type="text/css">
    div#toc, div#toggle_toc{
        background-color :white;
        padding : 5px;  
        -moz-box-shadow:    inset 0 0 10px #000000;
        -webkit-box-shadow: inset 0 0 10px #000000;
        box-shadow:         inset 0 0 10px #000000;
    }
    div#toggle_toc{
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
<script type="text/javascript" src ="<?php echo base_url().'assets/jquery.js';?>"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#toggle_toc").click(function(){
            $("#toc").toggle();
        });
        $.ajax({
            url : '<?php echo base_url().'index.php/help?_only_content=true';?>',
            success : function(response){
                $("#toc").html(response);
            }
        })
    })
</script>
<div id="toc"></div>
<div id="toggle_toc">Table of Contents</div>
