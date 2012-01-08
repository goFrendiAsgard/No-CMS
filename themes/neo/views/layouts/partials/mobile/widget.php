<?php   
    //include the widget
    function build_widget($widget_array){
        foreach($widget_array as $widget){
            
            echo '<div id="layout_widget_container_'.$widget['widget_name'].'">';
            echo '<h4>'.$widget['title'].'</h4>';
            echo '<div class="widget_content"></div>';
            if($widget['is_static']){
                $path=base_url().'index.php/main/show_static_widget/'.$widget['widget_id'].'?_only_content=true';
            }else{
                $path=base_url().'index.php/'.$widget['url'].'?_only_content=true';
            }
            echo '
                <script type="text/javascript">
                    $(document).ready(function(){

                        $.ajax({
                            url : "'.$path.'",
                            type: "POST",
                            data: {_only_content:true},
                            success : function(response){
                                $("#layout_widget_container_'.$widget['widget_name'].' .widget_content").replaceWith(response);
                                
                            }
                        });  
                        
                    });
                                      
                </script>
            ';
            echo '<br />';
            echo'</div>';
        }
    }
    build_widget($widget);    
?>