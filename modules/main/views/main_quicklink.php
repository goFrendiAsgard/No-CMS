<?php
	echo $output;
?>
<script type="text/javascript">
    $(document).ready(function(){
        $('.field-sorting').removeClass('field-sorting');
    });
    $(document).ajaxComplete(function(){
        $('.field-sorting').removeClass('field-sorting');
    });
</script>
