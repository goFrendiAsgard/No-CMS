<style type="text/css">
    <?php foreach($undeleted_id as $id){ ?>
    tr[rowid="<?=$id?>"] a.delete-row{
        display:none;
    }
    <?php } ?>
</style>
<?php
    if(count($navigation_path)>0){
        echo '<div style="padding-bottom:10px;">';
        echo '<a class="btn btn-primary" href="'.site_url('main/navigation').'">First Level Navigation</a>';
        for($i=0; $i<count($navigation_path)-1; $i++){
            $navigation = $navigation_path[$i];
            echo '&nbsp;<a class="btn btn-primary" href="'.site_url('main/navigation/'.$navigation['navigation_id']).'">'.
                $navigation['navigation_name'].' ('.$navigation['title'].')'.'</a>';
        }
        echo '</div>';
    }
	echo $output;
?>
<script type="text/javascript" src="{{ module_base_url }}assets/scripts/navigation.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        // override default_layout view
        $('#field-default_layout').hide();
        $('#default_layout_input_box').append('<select id="select-default_layout"></select>');
        // fetch layout
        fetch_layout_option();
        $('#field_default_theme_chzn > div.chzn-drop > ul.chzn-results > li').click(function(){
            fetch_layout_option();
        });
        // adjust real input
        $('#select-default_layout').live('change', function(){
            var selected_layout = $('#select-default_layout option:selected').val();
            $('#field-default_layout').val(selected_layout);
        });
    });

    function fetch_layout_option(){
        var theme = $('#field_default_theme_chzn > div.chzn-drop > ul.chzn-results > li.result-selected').html();
        if(typeof(theme) == 'undefined'){
            theme = '';
        }
        var current_layout = $('#field-default_layout').val();
        $.ajax({
            url: '{{ site_url }}main/get_layout/'+theme,
            dataType: 'json',
            success: function(response){
                $("#select-default_layout").html('');
                //$("#select-default_layout").append('<option value="'+current_layout+'" selected>'+current_layout+'</option>');
                for(var i=0; i<response.length; i++){
                    var layout = response[i];
                    if(layout == current_layout){
                        $("#select-default_layout").append('<option value="'+layout+'" selected>'+layout+'</option>');
                    }else{
                        $("#select-default_layout").append('<option value="'+layout+'">'+layout+'</option>');
                    }
                }
            }
        });
    }

    $(document).ajaxComplete(function(){
        // remove sorting
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
                'url' : '{{ MODULE_SITE_URL }}navigation/'+navigation_id+'/ajax_list',
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
    })
</script>