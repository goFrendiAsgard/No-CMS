<?php
    $view_name = 'field_'.underscore($stripped_master_table_name).'_'.underscore($master_column_name);
    $detail_columns = $detail_table['columns'];
    $detail_column_captions = array();
    $detail_column_names = array();
    $detail_real_column_names = array(); // only column names which is primary or lookup or without role
    $detail_column_role = array();
    $detail_primary_key = '';
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
?>

    // returned on insert and edit
    public function _callback_field_{{ field_name }}($value, $primary_key){
        $module_path = $this->cms_module_path();
        $this->config->load('grocery_crud');
        $date_format = $this->config->item('grocery_crud_date_format');

        if(!isset($primary_key)) $primary_key = -1;
        $query = $this->db->select('<?php echo implode(', ', $detail_real_column_names); ?>')
            ->from($this->cms_complete_table_name('<?php echo $detail_table_name; ?>'))
            ->where('<?php echo $detail_foreign_key_name; ?>', $primary_key)
            ->get();
        $result = $query->result_array();
<?php
    for($i=0; $i<count($detail_columns); $i++){
        $column = $detail_columns[$i];
        $name = $column['name'];
        $role = $column['role'];
        $data_type = $column['data_type'];
        $selection_mode = $column['value_selection_mode'];
        $selection_item = $column['value_selection_item'];
        if($role == 'primary' || $name == $detail_foreign_key_name || !(($data_type=='varchar' && $selection_mode == 'set') || $role=='detail many to many')) continue;
        echo '        // add "'.$name.'" to $result'.PHP_EOL;
        echo '        for($i=0; $i<count($result); $i++){'.PHP_EOL;
        if($data_type=='varchar' && $selection_mode == 'set'){
            echo '            $value = json_decode(\'[\'.$result[$i][\''.$name.'\'].\']\');'.PHP_EOL;
            echo '            $result[$i][\''.$name.'\'] = $value;'.PHP_EOL;
        }
        if($role=='detail many to many'){
            $relation_table_name = $column['relation_stripped_table_name'];
            $relation_table_column_name = $column['relation_table_column_name'];
            $relation_selection_column_name = $column['relation_selection_column_name'];
            echo '            $query_detail = $this->db->select(\''.$relation_selection_column_name.'\')'.PHP_EOL;
            echo '               ->from($this->cms_complete_table_name(\''.$relation_table_name.'\'))'.PHP_EOL;
            echo '               ->where(array(\''.$relation_table_column_name.'\'=>$result[$i][\''.$detail_primary_key.'\']))->get();'.PHP_EOL;
            echo '            $value = array();'.PHP_EOL;
            echo '            foreach($query_detail->result() as $row){'.PHP_EOL;
            echo '                $value[] = $row->'.$relation_selection_column_name.';'.PHP_EOL;
            echo '            }'.PHP_EOL;
            echo '            $result[$i][\''.$name.'\'] = $value;'.PHP_EOL;
        }
        echo '        }'.PHP_EOL;
    }
?>

        // get options
        $options = array();
<?php
    for($i=0; $i<count($detail_columns); $i++){
        $column = $detail_columns[$i];
        $name = $column['name'];
        $role = $column['role'];
        $data_type = $column['data_type'];
        $selection_mode = $column['value_selection_mode'];
        $selection_item = $column['value_selection_item'];
        if($role == 'primary' || $name == $detail_foreign_key_name) continue;
        if($data_type=='varchar' && ($selection_mode == 'enum' || $selection_mode == 'set')){
            echo '        $options[\''.$name.'\'] = array();'.PHP_EOL;
            echo '        $selection_items = array('.$selection_item.');'.PHP_EOL;
            echo '        foreach($selection_items as $item){'.PHP_EOL;
            echo '            $options[\''.$name.'\'][] = array(\'value\' => $item, \'caption\' => $item);'.PHP_EOL;
            echo '        }'.PHP_EOL;
        }
        if($role=='lookup'){
            $lookup_table_name = $column['lookup_stripped_table_name'];
            $lookup_column_name = $column['lookup_column_name'];
            $lookup_table_primary_key = $column['lookup_table_primary_key'];
            echo '        $options[\''.$name.'\'] = array();'.PHP_EOL;
            echo '        $query = $this->db->select(\''.$lookup_table_primary_key.','.$lookup_column_name.'\')'.PHP_EOL;
            echo '           ->from($this->cms_complete_table_name(\''.$lookup_table_name.'\'))'.PHP_EOL;
            echo '           ->get();'.PHP_EOL;
            echo '        foreach($query->result() as $row){'.PHP_EOL;
            echo '            $options[\''.$name.'\'][] = array(\'value\' => $row->'.$lookup_table_primary_key.', \'caption\' => $row->'.$lookup_column_name.');'.PHP_EOL;
            echo '        }'.PHP_EOL;
        }
        if($role=='detail many to many'){
            $selection_table_name = $column['selection_stripped_table_name'];
            $selection_column_name = $column['selection_column_name'];
            $selection_table_primary_key = $column['selection_table_primary_key'];
            echo '        $options[\''.$name.'\'] = array();'.PHP_EOL;
            echo '        $query = $this->db->select(\''.$selection_table_primary_key.','.$selection_column_name.'\')'.PHP_EOL;
            echo '           ->from($this->cms_complete_table_name(\''.$selection_table_name.'\'))->get();'.PHP_EOL;
            echo '        foreach($query->result() as $row){'.PHP_EOL;
            echo '            $options[\''.$name.'\'][] = array(\'value\' => $row->'.$selection_table_primary_key.', \'caption\' => strip_tags($row->'.$selection_column_name.'));'.PHP_EOL;
            echo '        }'.PHP_EOL;
        }
    }
?>
        $data = array(
            'result' => $result,
            'options' => $options,
            'date_format' => $date_format,
        );
        return $this->load->view($this->cms_module_path().'/<?php echo $view_name; ?>',$data, TRUE);
    }

    // returned on view
    public function _callback_column_{{ field_name }}($value, $row){
        $module_path = $this->cms_module_path();
        $query = $this->db->select('<?php echo implode(', ', $detail_real_column_names); ?>')
            ->from($this->cms_complete_table_name('<?php echo $detail_table_name; ?>'))
            ->where('<?php echo $detail_foreign_key_name; ?>', $row-><?php echo $master_primary_key_name; ?>)
            ->get();
        $num_row = $query->num_rows();
        // show how many records
        if($num_row>1){
            return $num_row .' <?php echo $detail_table['caption'] ?>s';
        }else if($num_row>0){
            return $num_row .' <?php echo $detail_table['caption'] ?>';
        }else{
            return 'No <?php echo $detail_table['caption'] ?>';
        }
    }