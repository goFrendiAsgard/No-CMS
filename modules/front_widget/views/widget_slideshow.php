<!--
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css'); ?>" />
<script type="text/javascript" src="http://localhost/no-cms3/assets/nocms/js/jquery.tools.min.js"></script>
-->

<div class="carousel slide" id="myCarousel">
	<!-- Carousel items -->
	<div class="carousel-inner">
		<?php $active = 'active';?>
		<?php foreach($result as $row){ ?>
			<div class="item <?php echo $active; ?>">
			<?php
				$active ='';
				$file_name = $row['url'];
				echo '<img style="height:285px;" src="'.base_url('modules/front_widget/assets/slideshows/'.$file_name).'" >';
			?>
			</div>
		<?php } ?>
	</div>
	<a class="carousel-control left" data-slide="prev" href="#myCarousel">&lsaquo;</a> 
	<a class="carousel-control right" data-slide="next" href="#myCarousel">&rsaquo;</a>
</div>
<script type ="text/javascript" src="'.base_url('assets/bootstrap/js/bootstrap-carousel.js').'"></script>
<script type ="text/javascript">
	$('#myCarousel').carousel('cycle');
</script>
<!--
<script type="text/javascript" src="http://localhost/no-cms3/assets/caches/a802737c74960fbd44d3f6969d6335cc.js"></script>
-->