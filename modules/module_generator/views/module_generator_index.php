<style type="text/css"> 
    div.form{
        float:left;
    }
    div.form label{
        display:block;
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
    div.form select{
    	height:300px;
    }
    /* small */
    @media (max-width: 479px){
    	div.form{
	        width:200px;
	    }
	    div.form label{
	        text-align:left;
	    }
    }
    /* large */
    @media (min-width: 480px){
    	div.form{
	        width:400px;
	    }
	    div.form label{
	        text-align:right;
	    }
    }
    
</style>
<script type="text/javascript" src ="<?php echo base_url().'assets/nocms/js/jquery.js';?>"></script>
<script type="text/javascript">
    $(document).ready(function(){
    });
</script>
<h3>Module Generator</h3>
<div class="form">     
    <?php    
    echo form_open($cms['module_path'].'/make', array('id'=>'form'));
    echo form_label('New Module Namespace');
    echo form_input('module_namespace');
    echo form_label('New Module Directory');
    echo form_input('module_directory');
    echo form_label('Tables<br /><i>(press ctrl+click to select multiple tables)</i>');
    $options = $tables;
    $selected = array();
    echo form_multiselect('tables[]', $options, $selected);
    echo form_submit('make', 'Make the module !!!');
    echo form_close();
    ?>
</div>
