<style type="text/css"> 
    div.form{
        width:400px;
        float:left;
    }
    div.form label{
        display:block;
        text-align:right;
        width:140px;
        float:left;
    }
    div.form input, div.form textarea, div.form checkbox, div.form select{
        float:left;
        font-size:12px;
        padding:4px 2px;
        border:solid 1px #aacfe4;
        width:200px;
        margin:2px 0 20px 10px;
    }
</style>
<script type="text/javascript" src ="<?php echo base_url().'assets/nocms/js/jquery.js';?>"></script>
<script type="text/javascript">
    $(document).ready(function(){
    });
</script>

<div class="form">     
    <?php    
    echo form_open('module_generator/make', array('id'=>'form'));
    echo form_label('New Module Name');
    echo form_input('module_name');
    echo form_label('Tables<br /><i>(press ctrl+click to select multiple tables)</i>');
    $options = $tables;
    $selected = array();
    echo form_multiselect('tables[]', $options, $selected);
    echo form_submit('make', 'Make the module !!!');
    echo form_close();
    ?>
</div>
