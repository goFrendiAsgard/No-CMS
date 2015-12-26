<?php

	$this->set_css($this->default_theme_path.'/no-flexigrid/css/flexigrid.css');
	$this->set_js_lib($this->default_theme_path.'/no-flexigrid/js/jquery.form.js');
	$this->set_js_config($this->default_theme_path.'/no-flexigrid/js/flexigrid-edit.js');

	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');
?>
<div class="flexigrid crud-form" data-unique-hash="<?php echo $unique_hash; ?>">
	<div class="mDiv">
		<div class="ftitle">
			<div class='ftitle-left'>
				<h3><?php echo $this->l('list_record'); ?> <?php echo $subject?></h3>
			</div>
			<div class='clear'></div>
		</div>
	</div>
<div id='main-table-box'>
	<?php echo form_open( $read_url, 'method="post" id="crudForm" autocomplete="off" enctype="multipart/form-data"'); ?>
	<div class='form-div'>
		<?php
		$counter = 0;
			foreach($fields as $field)
			{
				$even_odd = $counter % 2 == 0 ? 'odd' : 'even';
				$counter++;
		?>
			<div class='form-field-box <?php echo $even_odd?>' id="<?php echo $field->field_name; ?>_field_box">
				<div class='form-display-as-box col-md-4' id="<?php echo $field->field_name; ?>_display_as_box">
					<label>
                        <?php echo $input_fields[$field->field_name]->display_as?><?php echo ($input_fields[$field->field_name]->required)? "<span class='required'>*</span> " : ""?> :
                    </label>
				</div>
				<div class='form-input-box col-md-8' id="<?php echo $field->field_name; ?>_input_box">
					<?php echo $input_fields[$field->field_name]->input?>
				</div>
				<div class='clear'></div>
			</div>
		<?php }?>
		<?php if(!empty($hidden_fields)){?>
		<!-- Start of hidden inputs -->
			<?php
				foreach($hidden_fields as $hidden_field){
					echo $hidden_field->input;
				}
			?>
		<!-- End of hidden inputs -->
		<?php }?>
		<?php if ($is_ajax) { ?><input type="hidden" name="is_ajax" value="true" /><?php }?>
		<div id='report-error' class='report-div error'></div>
		<div id='report-success' class='report-div success'></div>
	</div>
	<div class="pDiv">
		<div class='form-button-box'>
			<input type='button' value='<?php echo $this->l('form_back_to_list'); ?>' class="btn btn-large back-to-list" id="cancel-button" />
		</div>
		<div class='form-button-box'>
			<div class='small-loading' id='FormLoading'><?php echo $this->l('form_update_loading'); ?></div>
		</div>
		<div class='clear'></div>
	</div>
	<?php echo form_close(); ?>
</div>
</div>
<script>
	var validation_url = '<?php echo $validation_url?>';
	var list_url = '<?php echo $list_url?>';

	var message_alert_edit_form = "<?php echo $this->l('alert_edit_form')?>";
	var message_update_error = "<?php echo $this->l('update_error')?>";
</script>
