<style type="text/css">
	.hidden {
		display: none!important;
		visibility: hidden;
	}
</style>
<?php
	$asset = new Cms_asset();
	foreach($css_files as $file){
		$asset->add_css($file);
	}
	echo $asset->compile_css();

	foreach($js_files as $file){
		$asset->add_js($file);
	}
	// also add adjust.js which contain of field adjustment function
	$asset->add_module_js('scripts/adjust.js', $module_path);
	echo $asset->compile_js();
	echo '<h4>Table</h4>';
    echo '<div style="padding-bottom: 10px;">';
    echo anchor(site_url('{{ module_path }}/data/nds/project/'.$project_id),'All Projects','class="btn btn-primary"');
	if(isset($project_id)){
	    echo '&nbsp;';
		echo anchor(site_url('{{ module_path }}/data/nds/project/edit/'.$project_id),'Project "<b>'.$project_name.'</b>"','class="btn btn-primary"');
	}
    echo '</div>';
	echo $output;
?>

<script type="text/javascript">
	// if document ready, call adjust when needed
	$(document).ready(function(){
	    // remove sorting
	    $('.field-sorting').removeClass('field-sorting');
	    // change field
		var changing_field = 'project_id';
		var affected_field = 'options';
		var get_restricted_path = '<?php echo site_url('{{ module_path }}'); ?>'+'/data/ajax/get_restricted_table_option/';
		adjust(changing_field, affected_field, get_restricted_path);
		$("select#field-"+changing_field).change(function(){
			adjust(changing_field, affected_field, get_restricted_path);
		});
	});

	$(document).ajaxComplete(function(){
        $('.field-sorting').removeClass('field-sorting');
        // path array
        var path_array = window.location.pathname.split( '/' );
        if (path_array.length > 1){
            if (path_array[path_array.length - 2] == 'success'){
                var id = path_array[path_array.length - 1];
                var position = $('#rec-' + id).position();
                if(position != undefined){
                    var top = position.top;
                    $(window).scrollTop( top );
                }
            }
        }
    });

</script>
