<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    // from_url
    $from_url = '';
    if(isset($_GET['from'])){
		$from_url = '?from='.$_GET['from'];
	}

    // show navigation path
    if(count($navigation_path)>0){
        echo '<div style="padding-bottom:10px;">';
        echo '<a class="btn btn-primary" href="'.site_url('main/manage_navigation').$from_url.'">{{ language:First Level Navigation }}</a>';
        for($i=0; $i<count($navigation_path)-1; $i++){
            $navigation = $navigation_path[$i];
            echo '&nbsp;<a class="btn btn-primary" href="'.site_url('main/manage_navigation/index/'.$navigation['navigation_id']) . $from_url . '">'.
                $navigation['navigation_name'].' ('.$navigation['title'].')'.'</a>';
        }
        echo '</div>';
    }
    // show grid/form
    echo $output;
?>
<script type="text/javascript" src="{{ module_base_url }}assets/scripts/navigation.js"></script>
<script type="text/javascript" src="{{ base_url }}assets/nocms/js/jquery-ace/ace/ace.js"></script>
<script type="text/javascript" src="{{ base_url }}assets/nocms/js/jquery-ace/ace/theme-eclipse.js"></script>
<script type="text/javascript" src="{{ base_url }}assets/nocms/js/jquery-ace/ace/mode-css.js"></script>
<script type="text/javascript" src="{{ base_url }}assets/nocms/js/jquery-ace/ace/mode-javascript.js"></script>
<script type="text/javascript" src="{{ base_url }}assets/nocms/js/jquery-ace/jquery-ace.min.js"></script>
<script type="text/javascript">
    $(document).ajaxComplete(function(){
        /// remove sorting
        $('.field-sorting').removeClass('field-sorting');
        // add children
        $('.need-child').each(function(){
            $(this).removeClass('need-child');
            var navigation_id = $(this).val();
            var $current_tr = $(this).parent().parent().parent();
            var $table = $current_tr.parent().parent();
            var child_id = 'child-' + navigation_id;
            var filler_id = 'filler-' + navigation_id;
            // make child
            var html = '<tr id="'+child_id+'"><td style="padding-left:25px; padding-right:0px; border-top:0px;" colspan="2">No-Children</td></tr>';
            $table.append(html);
            var $child = $('#'+child_id);
            // make filler
            var html = '<tr id="'+filler_id+'"><td colspan="2"></td></tr>';
            $table.append(html);
            var $filler = $('#'+filler_id);
            // move it
            $child.insertAfter($current_tr);
            $filler.insertAfter($current_tr);
            // hide everything for surprise :)
            //$child.hide();
            $filler.hide();
            // ajax thing
            $.ajax({
                'url' : '{{ MODULE_SITE_URL }}manage_navigation/index/'+navigation_id+'/ajax_list<?php echo $from_url; ?>',
                'success' : function(response){
                    $('#' + child_id + ' td').html(response);
                    $('#' + child_id + ' .bDiv').css('padding-right', '0px');
                    $('#' + child_id + ' thead').remove();
                    hash = window.location.hash;
                    hash = hash.replace('#', '');
                    if($('a[name="' + hash + '"]').offset() != undefined){
                        $(document.body).scrollTop($('a[name="' + hash + '"]').offset().top);
                    }
                }
            });
        });
    });

    $(document).ready(function(){
        // is insert
        <?php if($is_insert){?>
            $("#field-is_static-true").attr("checked", "checked");
            $('#field-is_static-true').click();
        <?php } ?>

        // custom style
        $("#field-custom_style").ace({
            theme: "eclipse",
            lang: "css",
            width: "100%",
            height: "200px"
        });
        var decorator = $("#field-custom_style").data("ace");
        if(typeof(decorator) != 'undefined'){
            var aceInstance = decorator.editor.ace;
            aceInstance.setFontSize("16px");
        }

        // custom script
        $("#field-custom_script").ace({
            theme: "eclipse",
            lang: "javascript",
            width: "100%",
            height: "200px"
        });
        var decorator = $("#field-custom_script").data("ace");
        if(typeof(decorator) != 'undefined'){
            var aceInstance = decorator.editor.ace;
            aceInstance.setFontSize("16px");
        }
    });
</script>
