		// delete corresponding <?php echo $detail_table_name.PHP_EOL; ?>
		$this->db->delete(<?php echo $detail_table_name; ?>,array('<?php echo $detail_primary_key_name; ?>'=>$primary_key));
		