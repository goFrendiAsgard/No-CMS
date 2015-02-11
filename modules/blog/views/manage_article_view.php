<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

echo $output;

?>
<a class="btn btn-primary" href="{{ site_url }}{{ module_path }}/blog/index">Show Blog</a>
<script type="text/javascript">
    function adjust_publish_date(){
        if($('#field-status option:selected').val() == 'scheduled'){
            $('#publish_date_field_box').show();
        }else{
            $('#publish_date_field_box').hide();
        }
    }
    $('#field-status').change(function(){
        adjust_publish_date();
    });
    $(document).ready(function(){
        adjust_publish_date();
    });
</script>