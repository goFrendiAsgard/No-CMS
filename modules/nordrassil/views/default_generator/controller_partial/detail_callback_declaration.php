<?php
    $view_name = 'field_'.underscore($stripped_master_table_name).'_'.underscore($master_column_name);
    $detail_columns = $detail_table['columns'];
    $detail_column_captions = array();
    $detail_column_names = array();
    $detail_real_column_names = array(); // only column names which is primary or lookup or without role
    $detail_column_role = array();
    $detail_primary_key = '';
    $enum_script = '';
    $set_script  = '';
    $lookup_script = '';
    $many_to_many_script = '';
    foreach($detail_columns as $detail_column){
        $caption = $detail_column['caption'];
        $name = $detail_column['name'];
        $role = $detail_column['role'];
        $value_selection_mode = $detail_column['value_selection_mode'];
        if($name == $detail_foreign_key_name) continue;
        $detail_column_captions[] = $caption;
        $detail_column_names[] = $name;
        $detail_column_role[] = $role;
        if($role=='primary' || $role=='lookup' || $role==''){
            $detail_real_column_names[] = $name;
            if($role=='primary'){
                $detail_primary_key = $name;
            }
        }
    }
    // build options and config
    for($i=0; $i<count($detail_columns); $i++){
        $column = $detail_columns[$i];
        $name = $column['name'];
        $role = $column['role'];
        $data_type = $column['data_type'];
        $selection_mode = $column['value_selection_mode'];
        $selection_item = $column['value_selection_item'];
        if($role == 'primary' || $name == $detail_foreign_key_name) continue;
        if($data_type=='varchar' && $selection_mode == 'enum'){
            // enum options
            if($enum_script == ''){
                $enum_script = PHP_EOL;
            }
            $enum_script .= '            \''.$name.'\' => array('.$selection_item.'),'.PHP_EOL;
        }else if($data_type=='varchar' && $selection_mode == 'set'){
            // set options
            if($set_script == ''){
                $set_script = PHP_EOL;
            }
            $set_script  .= '            \''.$name.'\' => array('.$selection_item.'),'.PHP_EOL;
        }else if($role=='lookup'){
            $lookup_table_name = $column['lookup_stripped_table_name'];
            $lookup_column_name = $column['lookup_column_name'];
            $lookup_table_primary_key = $column['lookup_table_primary_key'];
            // lookup options
            if($lookup_script == ''){
                $lookup_script = PHP_EOL;
            }
            $lookup_script .= '            \''.$name.'\' => array('.PHP_EOL;
            $lookup_script .= '                \'selection_table\'         => \''.$lookup_table_name.'\','.PHP_EOL;
            $lookup_script .= '                \'selection_pk_column\'     => \''.$lookup_table_primary_key.'\','.PHP_EOL;
            $lookup_script .= '                \'selection_lookup_column\' => \''.$lookup_column_name.'\','.PHP_EOL;
            $lookup_script .= '            ),'.PHP_EOL;

        }else if($role=='detail many to many'){
            $selection_table_name = $column['selection_stripped_table_name']; // hobby
            $selection_column_name = $column['selection_column_name']; // name
            $selection_table_primary_key = $column['selection_table_primary_key']; // hobby id
            $relation_table_name = $column['relation_stripped_table_name'];// citizen_hobby
            $relation_column_name = $column['relation_table_column_name']; // citizen_id
            $relation_selection_column_name = $column['relation_selection_column_name']; // hobby_id
            // many to many script
            if($many_to_many_script == ''){
                $many_to_many_script = PHP_EOL;
            }
            $many_to_many_script .= '            \''.$name.'\' => array('.PHP_EOL;
            $many_to_many_script .= '                \'selection_table\'           => \''.$selection_table_name.'\','.PHP_EOL;
            $many_to_many_script .= '                \'selection_pk_column\'       => \''.$selection_table_primary_key.'\','.PHP_EOL;
            $many_to_many_script .= '                \'selection_lookup_column\'   => \''.$selection_column_name.'\','.PHP_EOL;
            $many_to_many_script .= '                \'relation_table\'            => \''.$relation_table_name.'\','.PHP_EOL;
            $many_to_many_script .= '                \'relation_column\'           => \''.$relation_column_name.'\','.PHP_EOL;
            $many_to_many_script .= '                \'relation_selection_column\' => \''.$relation_selection_column_name.'\','.PHP_EOL;
            $many_to_many_script .= '            ),'.PHP_EOL;
        }
    }
    if($enum_script != ''){
        $enum_script .= '        ';
    }
    if($set_script != ''){
        $set_script .= '        ';
    }
    if($lookup_script != ''){
        $lookup_script .= '        ';
    }
    if($many_to_many_script != ''){
        $many_to_many_script .= '        ';
    }
?>

    // returned on insert and edit
    public function _callback_field_{{ field_name }}($value, $primary_key){
        // Options for detail table's column with SET type
        $set_column_option_list = array(<?php echo $set_script; ?>);
        // Options for detail table's column with ENUM type
        $enum_column_option_list = array(<?php echo $enum_script; ?>);
        // Detail table's one-to-many columns configurations
        $lookup_config_list = array(<?php echo $lookup_script; ?>);
        // Detail table's many-to-many columns configurations
        $many_to_many_config_list = array(<?php echo $many_to_many_script; ?>);
        // Prepare the data by using defined configurations and options
        $data = $this->_one_to_many_callback_field_data(
                '<?php echo $detail_table_name; ?>', // DETAIL TABLE NAME
                '<?php echo $detail_primary_key; ?>', // DETAIL PK NAME
                '<?php echo $detail_foreign_key_name; ?>', // DETAIL FK NAME
                $primary_key, // CURRENT TABLE PK VALUE
                $lookup_config_list, // LOOKUP CONFIGS
                $many_to_many_config_list, // MANY TO MANY CONFIGS
                $set_column_option_list, // SET OPTIONS
                $enum_column_option_list // ENUM OPTIONS
            );
        // Parse the data to the view
        return $this->load->view($this->cms_module_path().'/<?php echo $view_name; ?>',$data, TRUE);
    }

    // returned on view
    public function _callback_column_{{ field_name }}($value, $row){
        return $this->_humanized_record_count(
                '<?php echo $detail_table_name; ?>', // DETAIL TABLE NAME
                '<?php echo $detail_foreign_key_name; ?>', // DETAIL FK NAME
                $row-><?php echo $master_primary_key_name; ?>, // CURRENT TABLE PK VALUE
                array( // CAPTIONS
                    'single_caption'    => '<?php echo $detail_table['caption']; ?>',
                    'multiple_caption'  => '<?php echo $detail_table['caption']; ?>s',
                    'zero_caption'      => 'No <?php echo $detail_table['caption']; ?>',
                )
            );
    }
