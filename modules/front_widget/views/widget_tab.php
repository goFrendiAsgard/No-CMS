<script>
  $(function () {
    $('#myTab a:last').tab('show');
  })
</script>

<div class="tabbable">
	<ul class="nav nav-tabs" id="myTab">
		<?php $x=0; foreach($result as $row){ 
			if($x == 0) $class = "active";
			else $class = "";
		?>
			<li class="<?php echo $class; ?>">
				<a href="#content<?php echo $x; ?>" data-toggle="tab"> 
					<?php echo $row['tittle'];?>
				</a>
			</li>
		<?php $x++; } ?>
	</ul>

	<div class="tab-content">
		<?php $y=0; foreach($result as $row){ 
			if($x == 0) $class = "active";
			else $class = "";		
		?>
			<div class="tab-pane <?php echo $class; ?>" id="content<?php echo $y; ?>">
				<?php echo $row['content']; ?>
			</div>
		<?php $y++; } ?>
	</div>	
</div>

