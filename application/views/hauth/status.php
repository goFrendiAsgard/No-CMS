<?
	if (isset($_GET['json'])) {
		echo json_encode($status);
	} else {
		show_error($status);
	}
?>