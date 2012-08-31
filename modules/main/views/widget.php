<style type="text/css">
	.widget_active{
		cursor: pointer;
		text-decoration: underline;
		color: blue;
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
	echo $asset->compile_js(TRUE);
?>
<script type="text/javascript">
	$(document).ready(function(){
		
		$(".widget_active").click(function(){
			var str = $(this).html();
			var $this = $(this);
			$.ajax({
				url: $(this).attr('target'),
				dataType: 'json',
				success: function(response){					
					if(str == 'Active'){
						str = 'Inactive';
					}else{
						str = 'Active';
					}
					if(response.success){
						console.log(str);
						$this.html(str);
					}
				}
			});
		});
		
	});
</script>
<?php 	
	echo $output;
?>