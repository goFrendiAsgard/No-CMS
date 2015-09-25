<head>
</head>
<body>
	<?php
		$code = isset($_GET['code'])? $_GET['code'] : 'default';
		echo $code;
		$refresh = isset($_GET['refresh'])? $_GET['refresh'] : '0';
	?>
	<script>
		<?php if($refresh == 1){ ?>
			window.location = 'http://gofrendi.dev/coba.php?code=<?=$code?>';
		<?php } ?>
	</script>
</body>