<?php
    $real_input_id = 'md_real_field_'.$master_column_name.'_col';
    $detail_columns = $detail_table['columns'];
    $quoted_real_column_names = array(); // only column names which is primary or lookup or without role
    $quoted_set_column_names = array(); // only column names which is enum
    $quoted_many_to_many_column_names = array();
    $quoted_many_to_many_relation_tables = array();
    $quoted_many_to_many_relation_table_columns = array();
    $quoted_many_to_many_relation_selection_columns = array();

    $many_to_many_script = '';
    foreach($detail_columns as $detail_column){
        $name = $detail_column['name'];
        $role = $detail_column['role'];
        $selection_mode = $detail_column['value_selection_mode'];
        if($name == $detail_foreign_key_name) continue;
        if($role=='primary' || $role=='lookup' || $role==''){
            if($selection_mode == 'set'){
                $quoted_set_column_names = '\''.$name.'\'';
            }
            $quoted_real_column_names[] = '\''.$name.'\'';
        }else if($role=='detail many to many'){
            if($many_to_many_script == ''){
                $many_to_many_script = PHP_EOL;
            }
            $many_to_many_script .= '                \''.$name.'\' => array('.PHP_EOL;
            $many_to_many_script .= '                    \'relation_table\' => \''.$detail_column['relation_stripped_table_name'].'\','.PHP_EOL;
            $many_to_many_script .= '                    \'relation_column\' => \''.$detail_column['relation_table_column_name'].'\','.PHP_EOL;
            $many_to_many_script .= '                    \'relation_selection_column\' => \''.$detail_column['relation_selection_column_name'].'\','.PHP_EOL;
            $many_to_many_script .= '                ),'.PHP_EOL;
        }
    }
    if($many_to_many_script != ''){
        $many_to_many_script .= '            ';
    }
?>
        // SAVE CHANGES OF <?php echo $detail_table_name.PHP_EOL; ?>
        $data = json_decode($this->input->post('<?php echo $real_input_id; ?>'), TRUE);
        $this->_save_one_to_many(
            '<?php echo $master_column_name; ?>', // FIELD NAME
            '<?php echo $detail_table_name; ?>', // DETAIL TABLE NAME
            '<?php echo $detail_primary_key_name; ?>', // DETAIL PK NAME
            '<?php echo $detail_foreign_key_name; ?>', // DETAIL FK NAME
            $primary_key, // PARENT PRIMARY KEY VALUE
            $data, // DATA
            array(<?php echo implode(', ', $quoted_real_column_names); ?>), // REAL DETAIL COLUMN NAMES
            array(<?php echo implode(', ', $quoted_set_column_names); ?>), // SET DETAIL COLUMN NAMES
            $many_to_many_config_list=array(<?php echo $many_to_many_script; ?>)
        );
