<?php
class Nds_model extends CMS_Model{
    public $available_data_type = array(
            'int','varchar','char','real','text','date',
            'tinyint', 'smallint', 'mediumint', 'integer', 'bigint', 'float', 'double',
            'decimal', 'numeric', 'datetime', 'timestamp', 'time',
            'year', 'tinyblob', 'tinytext', 'blob', 'mediumblob', 'mediumtext',
            'longblob', 'longtext', 'geometry', 'point', 'linestring', 'polygon', 'multipoint',
            'multilinestring', 'multipolygon', 'geometrycollection'
        );
    public $type_without_length = array('text','date','datetime','timestamp','time','year',
            'float', 'double', 'decimal', 'tinyblob', 'tinytext', 'blob', 'mediumblob',
            'mediumtext', 'longblob', 'longtext'
        );
    public $auto_increment_data_type = array('int', 'tinyint', 'smallint', 'mediumint', 'integer', 'bigint');
    public $detault_data_type = 'varchar';

    public function get_all_project(){
        $query = $this->db->select('project_id, '.$this->t('project.name').
              ', '.$this->t('template').'.generator_path')
            ->from($this->t('project'))
            ->join($this->t('template'),
                 $this->t('project').'.template_id = '.$this->t('template').'.template_id')
            ->get();
        return $query->result();
    }
    public function get_template_option_by_template($template_id){
        $query = $this->db->select('option_id, name')
            ->from($this->t('template_option'))
            ->where('template_id', $template_id)
            ->get();
        return $query->result();
    }
    public function get_table_by_project($project_id){
        $query = $this->db->select('table_id, name, caption, data')
            ->from($this->t('table'))
            ->where('project_id', $project_id)
            ->order_by('priority')
            ->get();
        $result = $query->result();
        $new_result = array();
        // use variable to accomodate table's name
        $t_table_option = $this->t('table_option');
        $t_template_option = $this->t('template_option');
        foreach($result as $row){
            // get the options
            $query = $this->db->select('name')
                ->from($t_template_option)
                ->join($t_table_option, $t_table_option.'.option_id = '.$t_template_option.'.option_id')
                ->where($t_table_option.'.table_id', $row->table_id)
                ->get();
            $options = array();
            foreach($query->result() as $option_row){
                $options[] = $option_row->name;
            }
            $row->options = $options;
            $row->data = @json_decode($row->data);
            // add options to table
            $new_result[] = $row;
        }
        $result = $new_result;
        return $result;
    }
    public function get_column_by_table($table_id){
        $query = $this->db->select('column_id, name, caption, table_id, data_type, data_size, role,
                lookup_table_id, lookup_column_id, relation_table_id, relation_table_column_id,
                relation_selection_column_id, relation_priority_column_id,
                selection_table_id, selection_column_id')
            ->from($this->t('column'))
            ->where('table_id', $table_id)
            ->order_by('priority')
            ->get();
        $result = $query->result();
        $new_result = array();
        // use variable to accomodate table's name
        $t_column_option = $this->t('column_option');
        $t_template_option = $this->t('template_option');
        foreach($result as $row){
            // get the options
            $query = $this->db->select('name')
                ->from($t_template_option)
                ->join($t_column_option, $t_column_option.'.option_id = '.$t_template_option.'.option_id')
                ->where($t_column_option.'.column_id', $row->column_id)
                ->get();
            $options = array();
            foreach($query->result() as $option_row){
                $options[] = $option_row->name;
            }
            $row->options = $options;
            // get relationship table and column name
            $row->table_name = $this->get_table_name($row->table_id);
            $row->table_primary_key = $this->get_primary_key($row->table_id);
            $row->lookup_table_name = $this->get_table_name($row->lookup_table_id);
            $row->lookup_column_name = $this->get_column_name($row->lookup_column_id);
            $row->relation_table_name = $this->get_table_name($row->relation_table_id);
            $row->relation_table_column_name = $this->get_column_name($row->relation_table_column_id);
            $row->relation_selection_column_name = $this->get_column_name($row->relation_selection_column_id);
            $row->relation_priority_column_name = $this->get_column_name($row->relation_priority_column_id);
            $row->selection_table_name = $this->get_table_name($row->selection_table_id);
            $row->selection_table_primary_key = $this->get_primary_key($row->selection_table_id);
            $row->selection_column_name = $this->get_column_name($row->selection_column_id);
            // add options to table
            $new_result[] = $row;
        }
        $result = $new_result;
        return $result;
    }

    private function strip_table_prefix($table_name, $db_table_prefix){
        if(!isset($db_table_prefix) || $db_table_prefix == ''){
            return $table_name;
        }
        if(strpos($table_name, $db_table_prefix) === 0){
            $table_name = substr($table_name, strlen($db_table_prefix));
        }
        if(strlen($table_name)>0 && $table_name[0]=='_'){
            $table_name = substr($table_name,1);
        }
        return $table_name;
    }

    // data to generate
    public function get_project($project_id){
        $db_table_prefix = '';
        $data = FALSE;
        $query = $this->db->select('project_id, template_id, name, db_server, db_port, db_schema, db_user, db_password, db_table_prefix')
            ->from($this->t('project'))
            ->where('project_id', $project_id)
            ->get();
        if($query->num_rows()>0){
            $data = $query->row_array();
            $template_id = $data['template_id'];
            $db_table_prefix = $data['db_table_prefix'];
            $project_id = $data['project_id'];
            unset($data['template_id']);
            unset($data['project_id']);

            // get template name
            $query = $this->db->select('name')
                ->from($this->t('template'))
                ->where('template_id', $template_id)
                ->get();
            $template_name = $query->row()->name;
            $data['template'] = $template_name;

            // get project, table and column option's header
            $query = $this->db->select('option_id, name, option_type')
                ->from($this->t('template_option'))
                ->where('template_id', $template_id)
                ->get();
            $project_option_headers = array();
            $table_option_headers = array();
            $column_option_headers = array();
            foreach($query->result() as $row){
                $name = $row->name;
                $option_type = $row->option_type;
                $option_id = $row->option_id;
                switch($row->option_type){
                    case 'project': $project_option_headers[$name] = $option_id; break;
                    case 'table' : $table_option_headers[$name] = $option_id; break;
                    case 'column' : $column_option_headers[$name] = $option_id; break;
                }
            }

            // add project template_option
            $project_options = array();
            foreach($project_option_headers as $name=>$option_id){
                $query = $this->db->select('project_id, option_id')
                    ->from($this->t('project_option'))
                    ->where(array('project_id'=>$project_id, 'option_id'=>$option_id))
                    ->get();
                $project_options[$name] = $query->num_rows()>0;
            }
            $data['options'] = $project_options;

            // add tables
            $tables = array();
            $query = $this->db->select('table_id, name, caption, data')
                ->from($this->t('table'))
                ->where('project_id', $project_id)
                ->order_by('priority')
                ->get();
            foreach($query->result_array() as $row){
                $table = $row;
                $table_id = $table['table_id'];
                unset($table['table_id']);
                // get table name
                $table['name'] = addslashes($table['name']);
                if($db_table_prefix != '' && $db_table_prefix !== NULL){
                    if(strpos($table['name'], $db_table_prefix.'_') !== 0){
                        $table['name'] = $db_table_prefix.'_'.$table['name'];
                    }
                }
                $table['stripped_name'] = $this->strip_table_prefix($table['name'], $db_table_prefix);
                $table['caption'] = addslashes($table['caption']);
                $table['data'] = @json_decode($table['data'], TRUE);

                // get table options
                $table_options = array();
                foreach($table_option_headers as $name=>$option_id){
                    $query = $this->db->select('table_id, option_id')
                        ->from($this->t('table_option'))
                        ->where(array('table_id'=>$table_id, 'option_id'=>$option_id))
                        ->get();
                    $table_options[$name] = $query->num_rows()>0;
                }
                $table['options'] = $table_options;

                // get column options
                $columns = array();
                $query = $this->db->select('column_id, caption, name, data_type, data_size, role, lookup_table_id, lookup_column_id,
                    relation_table_id, relation_table_column_id, relation_selection_column_id, relation_priority_column_id,
                    selection_table_id, selection_column_id, value_selection_mode, value_selection_item')
                    ->from($this->t('column'))
                    ->where('table_id', $table_id)
                    ->order_by('priority')
                    ->get();
                foreach($query->result_array() as $row){
                    $column = $row;
                    $column_id = $column['column_id'];
                    $column['name'] = addslashes($column['name']);
                    $column['caption'] = addslashes($column['caption']);
                    unset($column['column_id']);
                    // lookup
                    $column ['lookup_table_name'] = $this->get_table_name($column['lookup_table_id']);
                    $column ['lookup_stripped_table_name'] = $this->strip_table_prefix($column['lookup_table_name'], $db_table_prefix);
                    $column ['lookup_column_name'] = $this->get_column_name($column['lookup_column_id']);
                    $column['lookup_table_primary_key'] = $this->get_primary_key($column['lookup_table_id']);
                    unset($column['lookup_table_id']);
                    unset($column['lookup_column_id']);
                    // relation
                    $column ['relation_table_name'] = $this->get_table_name($column['relation_table_id']);
                    $column ['relation_stripped_table_name'] = $this->strip_table_prefix($column['relation_table_name'], $db_table_prefix);
                    $column ['relation_table_column_name'] = $this->get_column_name($column['relation_table_column_id']);
                    $column ['relation_priority_column_name'] = $this->get_column_name($column['relation_priority_column_id']);
                    $column ['relation_selection_column_name'] = $this->get_column_name($column['relation_selection_column_id']);
                    unset($column['relation_selection_column_id']);
                    unset($column['relation_priority_column_id']);
                    unset($column['relation_table_id']);
                    unset($column['relation_table_column_id']);
                    // selection
                    $column ['selection_table_name'] = $this->get_table_name($column['selection_table_id']);
                    $column ['selection_stripped_table_name'] = $this->strip_table_prefix($column['selection_table_name'], $db_table_prefix);
                    $column ['selection_column_name'] = $this->get_column_name($column['selection_column_id']);
                    $column['selection_table_primary_key'] = $this->get_primary_key($column['selection_table_id']);
                    unset($column['selection_column_id']);
                    unset($column['selection_table_id']);
                    // value selection (for enum and set)
                    $column['value_selection_item'] = isset($column['value_selection_item'])?$column['value_selection_item']:'';
                    $column['value_selection_mode'] = isset($column['value_selection_mode'])?$column['value_selection_mode']:'';
                    if($column['value_selection_mode']!='' && ($column['data_size'] == 0 || $column['data_size'] == '' || $column['data_size'] === NULL)){
                        $column['data_size'] = 255;
                    }

                    // get table options
                    $column_options = array();
                    foreach($column_option_headers as $name=>$option_id){
                        $query = $this->db->select('column_id, option_id')
                            ->from($this->t('column_option'))
                            ->where(array('column_id'=>$column_id, 'option_id'=>$option_id))
                            ->get();
                        $column_options[$name] = $query->num_rows()>0;
                    }
                    $column['options'] = $column_options;

                    $columns[] = $column;
                }
                $table['columns'] = $columns;

                $tables[] = $table;
            }
            $data['tables'] = $tables;


        }
        return $data;
    }

    public function get_project_name($project_id){
        if($project_id == NULL){
            return '';
        }
        $query = $this->db->select('name')->from($this->t('project'))->where('project_id',$project_id)->get();
        if($query->num_rows()>0){
            $row = $query->row();
            return addslashes($row->name);
        }else{
            return '';
        }
    }

    public function get_table_name($table_id){
        if($table_id == NULL){
            return '';
        }
        $query = $this->db->select('name')->from($this->t('table'))->where('table_id',$table_id)->get();
        if($query->num_rows()>0){
            $row = $query->row();
            return addslashes($row->name);
        }else{
            return '';
        }
    }

    public function get_column_name($column_id){
        if($column_id == NULL){
            return '';
        }
        $query = $this->db->select('name')->from($this->t('column'))->where('column_id',$column_id)->get();
        if($query->num_rows()>0){
            $row = $query->row();
            return addslashes($row->name);
        }else{
            return '';
        }
    }

    public function get_primary_key($table_id){
        if($table_id == NULL){
            return '';
        }
        $query = $this->db->select('name')
            ->from($this->t('column'))
            ->where(array('table_id'=>$table_id, 'role'=>'primary'))
            ->get();
        if($query->num_rows()>0){
            $row = $query->row();
            return addslashes($row->name);
        }else{
            return '';
        }
    }

    // to install new template
    public function install_template($template_name, $generator_path, $project_options = array(), $table_options = array(), $column_options = array()){
        $data = array(
            'name'=>$template_name,
            'generator_path'=>$generator_path,
        );
        $this->db->insert($this->t('template'), $data);
        $query = $this->db->select('template_id')->from($this->t('template'))->where('name', $template_name)->get();
        if($query->num_rows()<=0) return FALSE;
        $row = $query->row();
        $template_id = $row->template_id;
        $this->add_option($template_id, 'project', $project_options);
        $this->add_option($template_id, 'table', $table_options);
        $this->add_option($template_id, 'column', $column_options);
        return TRUE;
    }

    private function add_option($template_id, $option_type, $options){
        foreach($options as $option){
            $data = array();
            if(is_array($option)>0){
                if(isset($option['name']) && isset($option['description'])){
                    $data = $option;
                }else if(count($option)>1){
                    $data['name'] = $option[0];
                    $data['description'] = $option[1];
                }else{
                    $data['name'] = $option[0];
                }
            }else{ // string
                $data['name'] = $option;
            }
            $data['option_type'] = $option_type;
            $data['template_id'] = $template_id;
            $this->db->insert($this->t('template_option'),$data);
        }
    }

    public function get_create_table_forge($tables, $default_fields = array()){
        $php = array();
        foreach($tables as $table){
            $table_name = $table['stripped_name'];
            $columns = $table['columns'];
            $primary_key_name = NULL;
            $field_list = array();
            foreach($columns as $column){
                $column_name_space = '';
                $column_type_space = '';
                $column_size_space = '';
                $column_name = $column['name'];
                $column_type = $column['data_type'];
                $column_size = $column['data_size'];
                while(strlen($column_name) + strlen($column_name_space) < 20){
                    $column_name_space .= ' ';
                }
                while(strlen($column_type) + strlen($column_type_space) < 10){
                    $column_type_space .= ' ';
                }
                while(strlen($column_size) + strlen($column_size_space) < 3){
                    $column_size_space .= ' ';
                }
                $column_value_selection_mode = $column['value_selection_mode'];
                $column_value_selection_item = $column['value_selection_item'];
                if($column['role'] == 'primary'){
                    $primary_key_name = $column_name;
                }
                $composed_type = '$this->TYPE_TYPE_VARCHAR_50_NULL';
                if($column['role'] == 'primary'){
                    $composed_type = '$this->TYPE_TYPE_INT_UNSIGNED_AUTO_INCREMENT';
                }else{
                    if($column_type == 'varchar' && $column_value_selection_mode != ''){ // SET and ENUM
                        $constraint = 'array('.$column_value_selection_item.')';
                        //$constraint = $column_value_selection_item;
                        $composed_type = 'array("type" => \''.$column_value_selection_mode.'\','.$column_type_space.' "constraint" => '.$constraint.', "null" => TRUE)';
                    }else if(in_array($column_type, $this->type_without_length)){ // column without length
                        $composed_type = 'array("type" => \''.$column_type.'\','.$column_type_space.' "null" => TRUE)';
                    }else{ // normal column
                        if(!isset($column_size) || $column_size == ''){
                            $column_size = 11;
                        }
                        if(!in_array($column_type, $this->available_data_type)){
                            $column_type = $this->detault_data_type;
                            $column_size = 255;
                        }
                        $column_type_space = '';
                        $column_size_space = '';
                        while(strlen($column_type) + strlen($column_type_space) < 10){
                            $column_type_space .= ' ';
                        }
                        while(strlen($column_size) + strlen($column_size_space) < 3){
                            $column_size_space .= ' ';
                        }
                        $composed_type = 'array("type" => \''.$column_type.'\','.$column_type_space.' "constraint" => '.$column_size.','.$column_size_space.' "null" => TRUE)';
                    }

                }
                $field_list[] = "'$column_name'" .$column_name_space. ' => '.$composed_type;
            }
            foreach($default_fields as $key=>$val){
                $column_name_space = '';
                while(strlen($key) + strlen($column_name_space) < 20){
                    $column_name_space .= ' ';
                }
                $field_list[] = "'$key'". $column_name_space. ' => '.$val;
            }
            $create_forge  = '// '.$table_name.PHP_EOL;
            $create_forge .= '        $fields = array('.PHP_EOL.'            '.implode(','.PHP_EOL.'            ', $field_list).PHP_EOL.'        );'.PHP_EOL;
            $create_forge .= '        $this->dbforge->add_field($fields);'.PHP_EOL;
            if(isset($primary_key_name)){
                $create_forge .= '        $this->dbforge->add_key(\''.$primary_key_name.'\', TRUE);'.PHP_EOL;
            }
            $create_forge .= '        $this->dbforge->create_table($this->t(\''.$table_name.'\'));'.PHP_EOL;

            $php[] = $create_forge;
        }
        return implode(PHP_EOL.'        ',$php);
    }

    public function get_drop_table_forge($tables){
        $php = array();
        foreach($tables as $table){
            $table_name = $table['stripped_name'];
            $space = '';
            while(strlen($table_name) + strlen($space) < 20){
                $space .= ' ';
            }
            $php[] = '$this->dbforge->drop_table($this->t(\''.$table_name.'\'),'.$space.' TRUE);';
        }
        $php = array_reverse($php);
        return implode(PHP_EOL.'        ',$php);
    }

    public function get_insert_table($tables){
        $php = array();
        foreach($tables as $table){
            $table_name = $table['stripped_name'];
            $data       = $table['data'];
            if(is_array($data) && count($data)>0){
                $syntax  = '$this->db->insert_batch($this->t(\''.$table_name.'\'), array(' . PHP_EOL;
                foreach($data as $record){
                    $field_pairs = array();
                    if(is_array($record)){
                        foreach($record as $key=>$value){
                            $field_pairs[] = "'".addslashes($key)."' => '".addslashes($value)."'";
                        }
                    }
                    $field_pairs = implode(', ', $field_pairs);
                    $syntax .= '            array('.$field_pairs.'),'.PHP_EOL;
                }
                $syntax .= '        ));';
                $php[]   = $syntax;
            }
        }
        $php = array_reverse($php);
        return implode(PHP_EOL.'        ',$php);
    }

    public function get_create_table_syntax($tables){

        $result_array = array();
        foreach($tables as $table){
            // create drop syntax
            $table_name = addslashes($table['name']);
            $create_table_syntax = 'CREATE TABLE `'.$table_name.'` ('.PHP_EOL;
            // add columns
            $columns = $table['columns'];
            $column_array = array();
            $primary = NULL;
            foreach($columns as $column){
                $column_name = $column['name'];
                $column_type = $column['data_type'];
                $column_size = $column['data_size'];
                $column_value_selection_mode = $column['value_selection_mode'];
                $column_value_selection_item = $column['value_selection_item'];
                $role = $column['role'];
                if($role == 'primary'){
                    if(!isset($column_size) || $column_size == ''){
                        $column_size = 11;
                    }
                    if(in_array($column_type, $this->auto_increment_data_type)){
                        $column_array[] = '  `'.$column_name.'` '.$column_type.'('.$column_size.') unsigned NOT NULL AUTO_INCREMENT';
                    }else{
                        $column_array[] = '  `'.$column_name.'` '.$column_type.'('.$column_size.') NOT NULL';
                    }
                    $primary = '  PRIMARY KEY (`'.$column_name.'`)';
                }else if($role == '' || $role == 'lookup'){
                    if($column_type == 'varchar' && $column_value_selection_mode != ''){
                        $column_array[] = '  `'.$column_name.'` '.$column_value_selection_mode.'('.$column_value_selection_item.')';
                    }else if(in_array($column_type, $this->type_without_length)){
                        $column_array[] = '  `'.$column_name.'` '.$column_type;
                    }else{
                        if(!isset($column_size) || $column_size == ''){
                            $column_size = 11;
                        }
                        if(!in_array($column_type, $this->available_data_type)){
                            $column_type = $this->detault_data_type;
                            $column_size = 255;
                        }
                        $column_array[] = '  `'.$column_name.'` '.$column_type.'('.$column_size.')';
                    }
                }
            }
            $column_string = implode(','.PHP_EOL, $column_array);
            if(isset($primary)){
                $column_string.=','.PHP_EOL.$primary;
            }
            $create_table_syntax .= $column_string.PHP_EOL;
            $create_table_syntax .= ') ENGINE=InnoDB DEFAULT CHARSET=utf8;';
            $result_array[] = $create_table_syntax;
        }
        $result = implode(PHP_EOL.'/*split*/'.PHP_EOL, $result_array);
        return $result;
    }

    public function get_insert_table_syntax($project_id, $tables){

        $query = $this->db->select()->from($this->t('project'))->where('project_id', $project_id)->get();
        if($query->num_rows()>0){
            $row = $query->row();
            $db_server = $row->db_server;
            $db_port = $row->db_port;
            $db_user = $row->db_user;
            $db_password = $row->db_password;
            $db_schema = $row->db_schema;

            $connection = @mysqli_connect($db_server, $db_user, $db_password, $db_schema, $db_port);
            @mysqli_select_db($connection, $db_schema);

            if($connection === FALSE){
                return '';
            }

            $result_array = array();
            foreach($tables as $table){
                // create drop syntax
                $table_name = addslashes($table['name']);
                // add columns
                $columns = $table['columns'];
                $column_names = array();
                foreach($columns as $column){
                    $column_names[] = $column['name'];
                }

                $raw_available_columns = array();
                $available_columns = array();
                $available_values = array();
                $SQL = "SELECT * FROM `".$table_name."`;";
                $result = mysqli_query($connection, $SQL);
                if(mysqli_num_rows($result)>0){
                    while($row = mysqli_fetch_assoc($result)){
                        $values = array();
                        foreach($row as $key=>$value){
                            // get available columns (the lazy way)
                            if(!in_array($key,$raw_available_columns) && in_array($key,$column_names)){
                                $raw_available_columns[] = $key;
                                $available_columns[] = '`'.addslashes($key).'`';
                            }
                            if(in_array($key,$raw_available_columns)){
                                $values[] = '\''.addslashes($value).'\'';
                            }
                        }
                        $available_values[] = '('.implode(', ',$values).')';
                    }

                    $available_column_list = implode(', ',$available_columns);
                    $available_value_list = implode(','.PHP_EOL, $available_values);


                    $insert_syntax = 'INSERT INTO `'.$table_name.'` ('.$available_column_list.') VALUES'.PHP_EOL;
                    $insert_syntax .= $available_value_list.';';
                    $result_array[] = $insert_syntax;

                }



            }
            $result = implode(PHP_EOL.'/*split*/'.PHP_EOL, $result_array);
            return $result;

        }
        return '';

    }

    public function get_drop_table_syntax($tables){
        $result_array = array();
        foreach($tables as $table){
            // create drop syntax
            $table_name = addslashes($table['name']);
            $result_array[] = 'DROP TABLE IF EXISTS `'.$table_name.'`; ';
        }
        $result_array = array_reverse($result_array);
        $result = implode(PHP_EOL.'/*split*/'.PHP_EOL, $result_array);
        return $result;
    }

    public function before_delete_template($id){
        $query = $this->db->select('project_id')->from($this->t('project'))->where('template_id',$id)->get();
        foreach($query->result() as $row){
            $this->before_delete_project($row->project_id);
            $this->db->delete($this->t('project'),array('project_id'=>$row->project_id));
        }
        $query = $this->db->select('option_id')->from($this->t('template_option'))->where('template_id',$id)->get();
        foreach($query->result() as $row){
            $this->before_delete_template_option($row->option_id);
            $this->db->delete($this->t('template_option'),array('option_id'=>$row->option_id));
        }
    }

    public function before_delete_template_option($id){
        $this->db->delete($this->t('project_option'),array('option_id'=>$id));
        $this->db->delete($this->t('table_option'),array('option_id'=>$id));
        $this->db->delete($this->t('column_option'),array('option_id'=>$id));
    }

    public function before_delete_project($id){
        $query = $this->db->select('table_id')->from($this->t('table'))->where('project_id',$id)->get();
        foreach($query->result() as $row){
            $this->before_delete_table($row->table_id, FALSE);
            $this->db->delete($this->t('table'),array('table_id'=>$row->table_id));
        }
        $this->db->delete($this->t('project_option'),array('project_id'=>$id));
    }

    public function before_delete_table($id, $sorting = TRUE){
        if($sorting){
            // get current project_id & priority
            $query = $this->db->select('project_id, priority')
                ->from($this->t('table'))
                ->where('table_id', $id)
                ->get();
            $row = $query->row();
            $project_id = $row->project_id;
            $priority   = $row->priority;
            // adjust priority
            $query = $this->db->select('table_id, priority')
                ->from($this->t('table'))
                ->where('project_id', $project_id)
                ->where('priority >', $priority)
                ->get();
            foreach($query->result() as $row){
                $table_id = $row->table_id;
                $priority = $row->priority;
                $data = array('priority' => $priority-1);
                $where = array('table_id'=>$table_id);
                $this->db->update($this->t('table'), $data, $where);
            }
        }
        // delete all related column
        $query = $this->db->select('column_id')->from($this->t('column'))->where('table_id',$id)->get();
        foreach($query->result() as $row){
            $this->before_delete_column($row->column_id, FALSE);
            $this->db->delete($this->t('column'),array('column_id'=>$row->column_id));
        }
        // delete all related table option
        $this->db->delete($this->t('table_option'),array('table_id'=>$id));
    }

    public function before_delete_column($id, $sorting = TRUE){
        if($sorting){
            // get current project_id & priority
            $query = $this->db->select('table_id, priority')
                ->from($this->t('column'))
                ->where('column_id', $id)
                ->get();
            $row = $query->row();
            $table_id = $row->table_id;
            $priority   = $row->priority;
            // adjust priority
            $query = $this->db->select('column_id, priority')
                ->from($this->t('column'))
                ->where('table_id', $table_id);
            if($priority != NULL){
                $query = $query->where('priority >', $priority);
            }
            $query = $query->get();
            foreach($query->result() as $row){
                $column_id = $row->column_id;
                $priority = $row->priority;
                $data = array('priority' => $priority-1);
                $where = array('column_id'=>$column_id);
                $this->db->update($this->t('column'), $data, $where);
            }
        }
        // delete column option
        $this->db->delete($this->t('column_option'),array('column_id'=>$id));
    }

    private function _pop($array, $key, $default=''){
        if(array_key_exists($key, $array)){
            return $array[$key];
        }else{
            return $default;
        }
    }

    public function import_project($seed){
        // get max project id (in case of name is empty)
        $query = $this->db->select_max('project_id')
            ->from($this->t('project'))
            ->get();
        $row = $query->row();
        $max_id = $row->project_id;

        // get project parameters
        $name               = $this->_pop($seed, 'name', 'Project_'.($max_id+1));
        $db_server          = $this->_pop($seed, 'db_server');
        $db_schema          = $this->_pop($seed, 'db_schema');
        $db_port            = $this->_pop($seed, 'db_port');
        $db_user            = $this->_pop($seed, 'db_user');
        $db_password        = $this->_pop($seed, 'db_password');
        $db_table_prefix    = $this->_pop($seed, 'db_table_prefix');
        $tables             = $this->_pop($seed, 'tables', array());
        $project_options    = $this->_pop($seed, 'options', array());
        $template_name      = $this->_pop($seed, 'template','');

        // get template id
        $query = $this->db->select('template_id')
            ->from($this->t('template'))
            ->where('name', $template_name)->get();
        if($query->num_rows()>0){
            $row = $query->row();
            $template_id = $row->template_id;
        }else{
            $template_id = 1;
        }

        // insert project and get project id
        $data = array(
                'template_id'       => $template_id,
                'name'              => $name,
                'db_server'         => $db_server,
                'db_schema'         => $db_schema,
                'db_port'           => $db_port,
                'db_user'           => $db_user,
                'db_password'       => $db_password,
                'db_table_prefix'   => $db_table_prefix,
            );
        $this->db->insert($this->t('project'), $data);
        $project_id = $this->db->insert_id();

        // insert project options
        foreach($project_options as $option=>$val){
            if(!$val) continue;
            $query = $this->db->select('option_id')
                ->from($this->t('template_option'))
                ->where('name', $option)
                ->get();
            $row = $query->row();
            $option_id = $row->option_id;
            $this->db->insert($this->t('project_option'),array(
                    'project_id'    => $project_id,
                    'option_id'     => $option_id,
                ));
        }

        // insert table
        $id_dict = array();
        $table_priority = 0;
        $relationship_column = array();
        foreach($tables as $table){
            // get table parameters
            $name           = $this->_pop($table, 'name');
            $caption        = $this->_pop($table, 'caption');
            $columns        = $this->_pop($table, 'columns', array());
            $table_options  = $this->_pop($table, 'options', array());
            $table_data     = $this->_pop($table, 'data', array());

            // insert table and get table_id
            $this->db->insert($this->t('table'), array(
                    'name'          => $name,
                    'caption'       => $caption,
                    'project_id'    => $project_id,
                    'priority'      => $table_priority,
                    'data'          => @json_encode($table_data),
                ));
            $table_id = $this->db->insert_id();
            $table_name = $name;
            $id_dict[$table_name] = array('id'=>$table_id, 'columns'=>array());
            $table_priority ++;

            // insert table option
            foreach($table_options as $option=>$val){
                if(!$val) continue;
                $query = $this->db->select('option_id')
                    ->from($this->t('template_option'))
                    ->where('name', $option)
                    ->get();
                $row = $query->row();
                $option_id = $row->option_id;
                $this->db->insert($this->t('table_option'),array(
                        'table_id'  => $table_id,
                        'option_id' => $option_id,
                    ));
            }

            // insert columns
            $column_priority = 0;
            foreach($columns as $column){
                // get column parameters
                $caption                = $this->_pop($column, 'caption');
                $name                   = $this->_pop($column, 'name');
                $data_type              = $this->_pop($column, 'data_type','varchar');
                $data_size              = $this->_pop($column, 'data_size','50');
                $role                   = $this->_pop($column, 'role');
                $value_selection_mode   = $this->_pop($column, 'value_selection_mode');
                $value_selection_item   = $this->_pop($column, 'value_selection_item');
                $column_options         = $this->_pop($column, 'options', array());
                $this->db->insert($this->t('column'), array(
                        'name'                  => $name,
                        'caption'               => $caption,
                        'data_type'             => $data_type,
                        'data_size'             => $data_size,
                        'role'                  => $role,
                        'value_selection_mode'  => $value_selection_mode,
                        'value_selection_item'  => $value_selection_item,
                        'table_id'              => $table_id,
                        'priority'              => $column_priority,
                    ));
                $column_id = $this->db->insert_id();
                $column_name = $name;
                $id_dict[$table_name]['columns'][$column_name] = $column_id;
                $column_priority ++;
                // add to relationship_column
                if($role != '' && $role != 'primary'){
                    $column['column_id']   = $column_id;
                    $relationship_column[] = $column;
                }

                // insert column option
                foreach($column_options as $option=>$val){
                    if(!$val) continue;
                    $query = $this->db->select('option_id')
                        ->from($this->t('template_option'))
                        ->where('name', $option)
                        ->get();
                    $row = $query->row();
                    $option_id = $row->option_id;
                    $this->db->insert($this->t('column_option'),array(
                            'column_id' => $column_id,
                            'option_id' => $option_id,
                        ));
                }

            } // end foreach column
        } // end foreach table

        // deal with relationship
        foreach($relationship_column as $column){
            // get parameters
            $lookup_table_name                  = $this->_pop($column, "lookup_table_name", NULL);
            $lookup_column_name                 = $this->_pop($column, "lookup_column_name", NULL);
            $relation_table_name                = $this->_pop($column, "relation_table_name", NULL);
            $relation_table_column_name         = $this->_pop($column, "relation_table_column_name", NULL);
            $relation_priority_column_name      = $this->_pop($column, "relation_priority_column_name", NULL);
            $relation_selection_column_name     = $this->_pop($column, "relation_selection_column_name", NULL);
            $selection_table_name               = $this->_pop($column, "selection_table_name", NULL);
            $selection_column_name              = $this->_pop($column, "selection_column_name", NULL);

            // get id
            $lookup_table_id                    = $this->_get_table_id($id_dict, $lookup_table_name);
            $lookup_column_id                   = $this->_get_column_id($id_dict, $lookup_table_name, $lookup_column_name);
            $relation_table_id                  = $this->_get_table_id($id_dict, $relation_table_name);
            $relation_table_column_id           = $this->_get_column_id($id_dict, $relation_table_name, $relation_table_column_name);
            $relation_priority_column_id        = $this->_get_column_id($id_dict, $relation_table_name, $relation_priority_column_name);
            $relation_selection_column_id       = $this->_get_column_id($id_dict, $relation_table_name, $relation_selection_column_name);
            $selection_table_id                 = $this->_get_table_id($id_dict, $selection_table_name);
            $selection_column_id                = $this->_get_column_id($id_dict, $selection_table_name, $selection_column_name);
            $column_id                          = $column['column_id'];

            // update column
            $this->db->update($this->t('column'), array(
                    'lookup_table_id'               => $lookup_table_id,
                    'lookup_column_id'              => $lookup_column_id,
                    'relation_table_id'             => $relation_table_id,
                    'relation_table_column_id'      => $relation_table_column_id,
                    'relation_priority_column_id'   => $relation_priority_column_id,
                    'relation_selection_column_id'  => $relation_selection_column_id,
                    'selection_table_id'            => $selection_table_id,
                    'selection_column_id'           => $selection_column_id,
                ), array(
                    'column_id' => $column_id
                ));
        }


        // return project id
        return $project_id;
    }
    private function _get_table_id($id_dict, $table_name = NULL){
        if($table_name === NULL) return NULL;
        if(!array_key_exists($table_name, $id_dict)) return NULL;
        return $id_dict[$table_name]['id'];
    }
    private function _get_column_id($id_dict, $table_name, $column_name = NULL){
        if($table_name === NULL || $column_name === NULL) return NULL;
        if(!array_key_exists($table_name, $id_dict)) return NULL;
        if(!array_key_exists($column_name, $id_dict[$table_name]['columns'])){return NULL;}
        return $id_dict[$table_name]['columns'][$column_name];
    }

    public function get_seed($project_id){
        // get the project
        $project = $this->nds_model->get_project($project_id);

        // remove unused key
        foreach($project as $project_key=>$project_val){
            // unset empty options
            if($project_key == 'options'){
                foreach($project_val as $option=>$option_val){
                    if($option_val === FALSE){
                        unset($project['options'][$project_key]);
                        unset($project_val[$project_key]);
                    }
                }
            }
            // clean up tables
            if($project_key == 'tables'){
                for($i=0; $i<count($project_val); $i++){
                    $table = $project_val[$i];
                    foreach($table as $table_key=>$table_val){
                        // unset empty table options
                        if($table_key == 'options'){
                            foreach($table_val as $option=>$option_val){
                                if($option_val === FALSE){
                                    unset($project['tables'][$i]['options'][$option]);
                                    unset($table_val[$option]);
                                }
                            }
                        }
                        // clean up columns
                        if($table_key == 'columns'){
                            for($j=0; $j<count($table_val); $j++){
                                $column = $table_val[$j];
                                foreach($column as $column_key=>$column_val){
                                    // unset empty column options
                                    if($column_key == 'options'){
                                        foreach($column_val as $option=>$option_val){
                                            if($option_val === FALSE){
                                                unset($project['tables'][$i]['columns'][$j]['options'][$option]);
                                                unset($column_val[$option]);
                                            }
                                        }
                                    }
                                    // unset empty and irelevant keys
                                    if($column_key == 'lookup_stripped_table_name' || $column_key == 'selection_stripped_table_name' ||
                                    $column_key == 'relation_stripped_table_name' || $column_val === '' || (is_array($column_val) && count($column_val)==0)){
                                        unset($project['tables'][$i]['columns'][$j][$column_key]);
                                    }
                                }
                            }
                        }
                        // unset empty and irelevant keys
                        if($table_key == 'stripped_name' || $table_val === '' || (is_array($table_val) && count($table_val)==0)){
                            unset($project['tables'][$i][$table_key]);
                        }
                    }
                }
            }
            // unset other empty keys
            if($project_val === '' || (is_array($project_val) && count($project_val)==0)){
                unset($project[$project_key]);
            }
        }

        // Print it
        if(defined('JSON_PRETTY_PRINT')){
            $str = json_encode($project, JSON_PRETTY_PRINT);
        }else{
            $str = json_encode($project);
        }
        return $str;
    }

}
?>
