<?php

    $this->set_css($this->default_theme_path.'/no-flexigrid/css/flexigrid.css');
    $this->set_js_lib($this->default_theme_path.'/no-flexigrid/js/jquery.form.js');
    $this->set_js_config($this->default_theme_path.'/no-flexigrid/js/flexigrid-add.js');

    $this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
    $this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');
?>
<div class="flexigrid crud-form" style='width: 100%;' data-unique-hash="<?php echo $unique_hash; ?>">
    <div class="mDiv">
        <div class="ftitle">
            <div class='ftitle-left'>
                <h3><?php echo $this->l('form_add'); ?> {{ language:<?php echo $subject?> }}</h3>
            </div>
            <div class='clear'></div>
        </div>
    </div>
<div id='main-table-box'>
    <?php echo form_open( $insert_url, 'method="post" id="crudForm" autocomplete="off" enctype="multipart/form-data"'); ?>
        <div class='form-div'>
            <?php
                if(!isset($this->tabs)){
                    $this->tabs = NULL;
                }

                // make tabs
                if($this->tabs !== NULL){
                    echo '<ul class="nav nav-tabs" role="tablist">';
                    $active = 'active';
                    $tab_key = array();
                    foreach($this->tabs as $key=>$val){
                        $tab_key[] = $key;
                        echo '<li class="'.$active.'"><a href="#'.str_replace(' ','',$key).'" role="tab" data-toggle="tab">'.$key.'</a></li>';
                        $active = '';
                    }
                    echo '</ul>';
                }

                $counter = 0;
                $tab_index=0;
                $tab_item_counter = 0;
                if($this->tabs !== NULL){
                    echo '<div class="tab-content">';
                }
                foreach($fields as $field)
                {
                    if($this->tabs !== NULL){
                        if($counter == 0){
                            echo '<div class="tab-pane active" id="'.str_replace(' ','',$tab_key[0]).'">';
                            echo '<h4>'.$tab_key[0].'</h4>';
                        }else if($tab_item_counter == $this->tabs[$tab_key[$tab_index]] && $tab_index<count($tab_key)-1){
                            $tab_index ++;
                            $tab_item_counter = 0;
                            echo '</div>';
                            echo '<div class="tab-pane" id="'.str_replace(' ','',$tab_key[$tab_index]).'">';
                            echo '<h4>'.$tab_key[$tab_index].'</h4>';
                        }
                        //echo $tab_item_counter.' '.$tab_index.' '.print_r($this->tabs,TRUE).' '.print_r($tab_key,TRUE).'<br />';
                    }
                    $even_odd = $counter % 2 == 0 ? 'odd' : 'even';
                    $counter++;
                    $tab_item_counter ++;
            ?>
                    <div class='form-field-box <?php echo $even_odd?>' id="<?php echo $field->field_name; ?>_field_box">
                        <div class='form-display-as-box' id="<?php echo $field->field_name; ?>_display_as_box">
                            {{ language:<?php echo $input_fields[$field->field_name]->display_as; ?> }}<?php echo ($input_fields[$field->field_name]->required)? "<span class='required'>*</span> " : ""; ?> :
                        </div>
                        <div class='form-input-box' id="<?php echo $field->field_name; ?>_input_box">
                            <?php echo $input_fields[$field->field_name]->input?>
                        </div>
                        <div class='clear'></div>
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

            ?>
            <!-- Start of hidden inputs -->
                <?php
                    foreach($hidden_fields as $hidden_field){
                        echo $hidden_field->input;
                    }
                ?>
            <!-- End of hidden inputs -->
            <?php if ($is_ajax) { ?><input type="hidden" name="is_ajax" value="true" /><?php }?>

            <div id='report-error' class='report-div error alert alert-danger'></div>
            <div id='report-success' class='report-div success alert alert-success'></div>
        </div>
        <div class="pDiv">
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
</script>