<?php
    $this->set_css($this->default_theme_path.'/no-flexigrid/css/flexigrid.css');
    $this->set_js_lib($this->default_theme_path.'/no-flexigrid/js/jquery.form.js');
    $this->set_js_config($this->default_theme_path.'/no-flexigrid/js/flexigrid-add.js');
    $this->set_js_config($this->default_theme_path.'/no-flexigrid/js/flexigrid-form.js');

    $this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
    $this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');

    if(isset($_GET['from'])){
		if(strpos($list_url, '&from=') === FALSE && strpos($list_url, '?from=') === FALSE){
    		if(strpos($list_url, '?') !== FALSE){
    			$list_url .= '&from='.$_GET['from'];
    		}else{
    			$list_url .= '?from='.$_GET['from'];
    		}
        }
        if(strpos($insert_url, '&from=') === FALSE && strpos($insert_url, '?from=') === FALSE){
    		if(strpos($insert_url, '?') !== FALSE){
    			$insert_url .= '&from='.$_GET['from'];
    		}else{
    			$insert_url .= '?from='.$_GET['from'];
    		}
        }
	}
?>
<div class="flexigrid crud-form" data-unique-hash="<?php echo $unique_hash; ?>">
    <div class="mDiv">
        <div class="ftitle">
            <div class='ftitle-left'>
                <h3><?php echo $this->l('form_add'); ?> {{ language:<?php echo $subject?> }}</h3>
            </div>
            <div class='clear'></div>
        </div>
    </div>
<div id="main-table-loading"><img id="img-loader" src="<?php echo base_url('assets/nocms/images/ajax-loader.gif'); ?>" /></div>
<div id='main-table-box' style="display:none">
    <?php echo form_open( $insert_url, 'method="post" id="crudForm" autocomplete="off" enctype="multipart/form-data"'); ?>
    <div class='form-div form-horizontal row'>
        <?php
            $this->tabs = isset($this->tabs)? $this->tabs : NULL;
            $this->tab_glyphicons = isset($this->tab_glyphicons)? $this->tab_glyphicons : NULL;
            $this->outside_tab = isset($this->outside_tab)? $this->outside_tab : 0;
            $counter = 0;
            $tab_index=-1;
            $tab_item_counter = 0;
            $width_accumulator = 0;
            foreach($fields as $field)
            {
                if($this->tabs !== NULL){
                    $tab_key = array();
                    foreach($this->tabs as $key=>$val){
                        $tab_key[] = $key;
                    }
                    if($counter >= $this->outside_tab){
                        if($counter == $this->outside_tab){
                            $tab_index ++;
                            $tab_item_counter = 0;
                            // tab header
                            echo '<div class="tab-content row col-md-12">';
                            echo '<ul class="nav nav-tabs" role="tablist">';
                            $active = 'active';
                            foreach($this->tabs as $key=>$val){
                                $caption = $key;
                                if(array_key_exists($key, $this->tab_glyphicons)){
                                    $caption = '<i class ="glyphicon ' . $this->tab_glyphicons[$key] . '"></i>&nbsp;' . $key;
                                }
                                echo '<li class="'.$active.'"><a href="#'.str_replace(' ','',$key).'" role="tab" data-toggle="tab">{{ language:'.$caption.' }}</a></li>';
                                $active = '';
                            }
                            echo '</ul>';
                            echo '</div>';
                            // tab content
                            echo '<div class="tab-content row col-md-12 well">';
                            echo '<div class="tab-pane col-md-12 active" id="'.str_replace(' ','',$tab_key[0]).'">';
                        }else if($tab_item_counter == ($this->tabs[$tab_key[$tab_index]]) && $tab_index<count($tab_key)-1){
                            $tab_index ++;
                            $tab_item_counter = 0;
                            $width_accumulator = 0;

                            echo '</div>';
                            echo '<div class="tab-pane col-md-12" id="'.str_replace(' ','',$tab_key[$tab_index]).'">';
                        }
                    }
                }
                $even_odd = $counter % 2 == 0 ? 'odd' : 'even';
                $counter++;
                if($counter >= $this->outside_tab){
                    $tab_item_counter ++;
                }

                $width_addition = 0;
                if(isset($this->field_half_width) && in_array($field->field_name, $this->field_half_width)){
                    $box_width = 6;
                    $label_width = 4;
                    $input_width = 8;
                    $width_addition = 0.5;
                }else if(isset($this->field_quarter_width) && in_array($field->field_name, $this->field_quarter_width)){
                    $box_width = 3;
                    $label_width = 12;
                    $input_width = 12;
                    $width_addition = 0.25;
                }else if(isset($this->field_one_third_width) && in_array($field->field_name, $this->field_one_third_width)){
                    $box_width = 4;
                    $label_width = 6;
                    $input_width = 6;
                    $width_addition = 0.33;
                }else if(isset($this->field_two_third_width) && in_array($field->field_name, $this->field_two_third_width)){
                    $box_width = 8;
                    $label_width = 3;
                    $input_width = 9;
                    $width_addition = 0.67;
                }else{
                    $box_width = 12;
                    $label_width = 2;
                    $input_width = 10;
                    $width_addition = 1;
                }

                if($width_accumulator + $width_addition > 1){
                    echo '<div style="clear:both;"></div>';
                    $width_accumulator = $width_addition;
                }else{
                    $width_accumulator += $width_addition;
                }

                $form_field_box_class = '';
                $input_box_class = '';
                if($width_addition < 1){
                    if($width_accumulator == $width_addition){
                        //$input_box_class .= ' first-input-box';
                        $form_field_box_class .= ' first-form-field-box';
                    }
                    if($width_accumulator >= 0.9 && $width_addition < 1){
                        $form_field_box_class .= ' last-form-field-box';
                        $input_box_class .= ' last-input-box';
                    }
                }
        ?>
                    <div class='form-field-box form-group col-xs-12 col-md-<?php echo $box_width; ?> <?php echo $even_odd; ?> <?php echo $form_field_box_class; ?>' id="<?php echo $field->field_name; ?>_field_box">
                        <label for="field-<?php echo $field->field_name; ?>" class='form-display-as-box col-xs-12 col-md-<?php echo $label_width; ?>' id="<?php echo $field->field_name; ?>_display_as_box">
                            {{ language:<?php echo $input_fields[$field->field_name]->display_as; ?> }}<?php echo ($input_fields[$field->field_name]->required)? "<span class='required'>*</span> " : ""; ?>
                        </label>
                        <div class='form-input-box col-xs-12 col-md-<?php echo $input_width; ?> <?php echo $input_box_class; ?>' id="<?php echo $field->field_name; ?>_input_box">
                            <?php echo $input_fields[$field->field_name]->input?>
                        </div>
                    </div>
        <?php
                if($this->tabs !== NULL){
                    if($counter == count($fields)){
                        echo '</div>';
                    }
                }
            }
            if($this->tabs !== NULL){
                echo '</div>';
            }

            if(!empty($hidden_fields)){
                foreach($hidden_fields as $hidden_field){
                    echo $hidden_field->input;
                }
            }
            if ($is_ajax) { ?><input type="hidden" name="is_ajax" value="true" /><?php }?>

            <div id='report-error' class='report-div error alert alert-danger container col-md-12'></div>
            <div id='report-success' class='report-div success alert alert-success container col-md-12'></div>
        </div>
        <div class="pDiv col-md-12">
            <div class='form-button-box'>
                <input id="form-button-save" type='submit' value='<?php echo $this->l('form_save'); ?>'  class="btn btn-default btn-large"/>
            </div>
<?php     if(!$this->unset_back_to_list) { ?>
            <div class='form-button-box'>
                <input type='button' value='<?php echo $this->l('form_save_and_go_back'); ?>' id="save-and-go-back-button"  class="btn btn-default btn-large"/>
            </div>
            <div class='form-button-box'>
                <input type='button' value='<?php echo $this->l('form_cancel'); ?>' class="btn btn-default btn-large" id="cancel-button" />
            </div>
<?php     } ?>
            <div class='form-button-box'>
                <div class='small-loading' id='FormLoading'><?php echo $this->l('form_insert_loading'); ?></div>
            </div>
            <div class='clear'></div>
        </div>
    <?php echo form_close(); ?>
</div>
</div>
<script>
    var validation_url = '<?php echo $validation_url?>';
    var list_url = '<?php echo $list_url?>';

    var message_alert_add_form = "<?php echo $this->l('alert_add_form')?>";
    var message_insert_error = "<?php echo $this->l('insert_error')?>";
    $(document).ready(function(){
        $('#main-table-loading').hide();
        $('#main-table-box').show();
    });
</script>
