<style type="text/css">
	.hidden {
		display: none!important;
		visibility: hidden;
	}
</style>
<?php
	$asset = new CMS_Asset();
	foreach($css_files as $file){
		$asset->add_css($file);
	}
	echo $asset->compile_css();

	foreach($js_files as $file){
		$asset->add_js($file);
	}
	// also add adjust.js which contain of field adjustment function
	$asset->add_module_js('scripts/adjust.js', '{{ module_path }}');
	echo $asset->compile_js();
	echo '<h4>Project</h4>';
    if($state != 'list'){
        echo '<p class="alert-warning alert">
                Import database only works for MySQL.
            </p>';
	    echo anchor(site_url('{{ module_path }}/data/nds/project/'),'All Projects','class="btn btn-primary"');
    }
	echo $output;
?>

<script type="text/javascript">
	// if document ready, call adjust when needed
	$(document).ready(function(){
		var changing_field = 'template_id';
		var affected_field = 'options';
		var get_restricted_path = '<?php echo site_url('{{ module_path }}'); ?>'+'/data/ajax/get_restricted_project_option/';
		adjust(changing_field, affected_field, get_restricted_path);
		$("select#field-"+changing_field).change(function(){
			adjust(changing_field, affected_field, get_restricted_path);
		});

        // auto fill
        var autofill = [
                ['db_server', 'localhost'],
                ['db_port', '3306'],
                ['db_user', 'root']
            ];
        for(var i=0; i<autofill.length; i++){
            if($("#field-"+autofill[i][0]).val()==''){
                $("#field-"+autofill[i][0]).val(autofill[i][1]);
            }
        }

	});


</script>
