<style type="text/css">
	.navigation_active{
		cursor: pointer;
		text-decoration: underline;
		color: blue;
	}
</style>
<?php 
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<script type="text/javascript">
	$(document).ready(function(){
		
		$(".navigation_active").click(function(){
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
<?php echo $output; ?>