<?php
class Synchronize_model extends CMS_Model{
    private $connection;
    private $db_server;
    private $db_user;
    private $db_port;
    private $db_password;
    private $db_schema;
    private $numeric_data_type = array(
            'int','real','tinyint', 'smallint', 'mediumint',
            'integer', 'bigint', 'float', 'double',
            'decimal', 'numeric',
        );

    public function humanize($name){
        $UPPER = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $LOWER = 'abcdefghijklmnopqrstuvwxyz';
        $i=0;
        while($i<strlen($name)-1){
            $char = substr($name, $i, 1);
            // just pass by if character is space
            if($char == ' '){ $i++; continue;}
            $before_chunk = $i == 0 ? '' : substr($name, 0, $i);
            $after_chunk = $i == strlen($name)-1 ? '' : substr($name, $i+1);
            // just pass by if character
            if($before_chunk == '' || $after_chunk == ''){ $i++; continue;};
            $before_char = substr($before_chunk, -1, 1);
            $after_char = substr($after_chunk, 0,1);
            // lower case preceed upper case
            if(strpos($LOWER, $before_char) !== FALSE && strpos($UPPER, $char) != FALSE){
                $char  = ' '.$char;
                $name = $before_chunk . $char . $after_chunk;
            }
            // upper case, upper case than lower case
            if(strpos($UPPER, $before_char) !== FALSE && strpos($UPPER, $char) != FALSE && strpos($LOWER, $after_char) != FALSE){
                $char = ' '.$char;
                $name = $before_chunk . $char . $after_chunk;
            }
            $i++;
        }
        $name = ucwords(str_replace('_', ' ', $name));
        return $name;
    }

    public function synchronize($project_id){
        // make project_id save of SQL injection
        $save_project_id = addslashes($project_id);

        // select the current nordrassil_project
        $query = $this->db->select('db_server, db_user, db_password, db_schema, db_port, db_table_prefix')
            ->from($this->t('project'))
            ->where(array('project_id'=>$project_id))
            ->get();
        // No project available, skip it
        if($query->num_rows() == 0){
            return FALSE;
        }

        $row = $query->row();
        $this->db_server = $row->db_server;
        $this->db_port = $row->db_port;
        $this->db_user = $row->db_user;
        $this->db_password = $row->db_password;
        $this->db_schema = $row->db_schema;
        $this->db_table_prefix = $row->db_table_prefix;
        if(!isset($this->db_port) || $this->db_port == ''){
            $this->db_port = '3306';
        }
        $this->connection = @mysqli_connect($this->db_server, $this->db_user, $this->db_password, 'information_schema', $this->db_port);
        if(!$this->connection){
            return FALSE;
        }
        mysqli_select_db($this->connection, 'information_schema');

        // import table based on information schema
        $this->create_table($project_id);

        // add foreign key (lookup based on )
        $result = mysqli_query($this->connection,
            'SELECT TABLE_SCHEMA, TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
            FROM KEY_COLUMN_USAGE
            WHERE REFERENCED_TABLE_NAME IS NOT NULL AND REFERENCED_COLUMN_NAME IS NOT NULL AND TABLE_SCHEMA = \''.addslashes($this->db_schema).'\';');
        while($row = mysqli_fetch_array($result)){
            // get table
            $table = $this->cms_get_record($this->t('table'), array(
                'project_id' => $project_id,
                'name' => $row['TABLE_NAME']
            ));
            // get referenced table
            $referenced_table = $this->cms_get_record($this->t('table'), array(
                'project_id' => $project_id,
                'name' => $row['REFERENCED_TABLE_NAME']
            ));
            // table and referenced column should not be empty
            if($table == NULL || $referenced_table == NULL){
                continue;
            }
            // get column
            $column = $this->cms_get_record($this->t('column'), array(
                'table_id' => $table->table_id,
                'name' => $row['COLUMN_NAME']
            ));
            // get lookup column
            $lookup_column = $this->cms_get_record($this->t('column'), array(
                'table_id' => $referenced_table->table_id,
                'role !=' => 'primary'
            ));
            if($lookup_column == NULL){
                // if no lookup column found, use referenced column
                $lookup_column = $this->cms_get_record($this->t('column'), array(
                    'table_id' => $referenced_table->table_id,
                    'name' => $row['REFERENCED_COLUMN_NAME']
                ));
            }
            // column and lookup_column should not be empty
            if($column == NULL || $lookup_column == NULL){
                continue;
            }
            $this->db->update($this->t('column'),
                array(
                    'role' => 'lookup',
                    'lookup_table_id' => $referenced_table->table_id,
                    'lookup_column_id' => $lookup_column->column_id,
                ),
                array('column_id' => $column->column_id)
            );
        }

        // get tables for further process
        $t_query = $this->db->select('table_id, name, caption')
            ->from($this->t('table'))
            ->where('project_id', $project_id)
            ->get();
        $table_result = array();
        foreach($t_query->result() as $row){
            // build stripped_name
            $stripped_name = $row->name;
            if(substr($stripped_name, 0, strlen($this->db_table_prefix)) == $this->db_table_prefix){
                $stripped_name = substr($stripped_name, strlen($this->db_table_prefix));
                $stripped_name = trim($stripped_name, '_');
                $stripped_name = trim($stripped_name, '-');
            }
            $row->stripped_name = $stripped_name;
            $table_result[] = $row;
        }
        // sort table by name's length, descending
        for($i=count($table_result)-1; $i>0; $i--){
            for($j=0; $j<$i; $j++){
                if(strlen($table_result[$j]->name) > strlen($table_result[$j+1]->name)){
                    $tmp = $table_result[$j];
                    $table_result[$j] = $table_result[$j+1];
                    $table_result[$j+1] = $tmp;
                }
            }
        }

        // loop for each table, add possible self-lookup relationship
        foreach($table_result as $current_table){
            $stripped_table_name = $current_table->stripped_name;
            // loop for each column to determine self-lookup relationship
            $column_query = $this->db->select('column_id, name, role, caption')
                ->from($this->t('column'))
                ->where('table_id', $current_table->table_id)
                ->get();
            // get self lookup column
            $lookup_column = NULL;
            $primary_column = NULL;
            foreach($column_query->result() as $column){
                if($column->role == 'primary'){
                    $primary_column = $column;
                }
                if($column->role == ''){
                    $lookup_column = $column;
                }
                if($lookup_column != NULL){
                    break;
                }
            }
            // if lookup column not found, use primary column as lookup column too
            if($lookup_column == NULL){
                $lookup_column = $primary_column;
            }
            foreach($column_query->result() as $column){
                if($column->role != ''){continue;}
                $column_name = $column->name;
                // is it match?
                if(strtolower($column_name) == 'id_parent' || strtolower($column_name) == 'parent_id' || strtolower($column_name) == 'id-parent' || strtolower($column_name) == 'parent-id' || strtolower($column_name) == 'idparent' || strtolower($column_name) == 'parentid'){
                    // update the column
                    $this->db->update($this->t('column'),
                        array(
                                'role' => 'lookup',
                                'lookup_table_id' => $current_table->table_id,
                                'lookup_column_id' => $lookup_column->column_id,
                                'caption' => trim(trim($column->caption, 'Id'), ' '),
                            ),
                        array('column_id' => $column->column_id));
                }
            }
        }

        // now loop for every column in all tables to automatically determine lookup relationship
        foreach($table_result as $current_table){
            // get columns of current_table
            $current_column_query = $this->db->select('column_id, name, role, caption')
                ->from($this->t('column'))
                ->where('table_id', $current_table->table_id)
                ->get();
            foreach($current_column_query->result() as $current_column){
                if($current_column->role != ''){continue;}
                // get another tables and compare the field
                foreach($table_result as $other_table){
                    // lookup to current table is not handled
                    if($current_table->table_id == $other_table->table_id){continue;}
                    // get stripped table name
                    $stripped_table_name = $other_table->stripped_name;
                    // if name matched
                    if(preg_match('/(.*)id(.*)'.$stripped_table_name.'(.*)/i', $current_column->name) == 1 || preg_match('/(.*)'.$stripped_table_name.'(.*)id(.*)/i', $current_column->name) == 1){
                        // get lookup column of other table
                        $other_lookup_column = NULL;
                        $primary_column      = NULL;
                        $other_column_query = $this->db->select('column_id, name, role')
                            ->from($this->t('column'))
                            ->where('table_id', $other_table->table_id)
                            ->get();
                        foreach($other_column_query->result() as $other_column){
                            if($other_column->role == 'primary'){
                                $primary_column = $other_column;
                            }
                            if($other_column->role == ''){
                                $other_lookup_column = $other_column;
                            }
                            if($other_lookup_column != NULL){
                                break;
                            }
                        }
                        // if lookup column not found, use primary column as lookup column too
                        if($other_lookup_column == NULL){
                            $other_lookup_column = $primary_column;
                        }
                        // build relationship
                        $this->db->update($this->t('column'),
                            array(
                                    'role' => 'lookup',
                                    'lookup_table_id' => $other_table->table_id,
                                    'lookup_column_id' => $other_lookup_column->column_id,
                                    'caption' => trim(trim($current_column->caption, 'Id'), ' '),
                                ),
                            array('column_id' => $current_column->column_id));
                    }
                }
            }
        }

        // loop for each table, add master-detail relationship
        foreach($table_result as $current_table){
            $stripped_table_name = $current_table->stripped_name;
            // get max priority
            $max_priority = $this->db->select_max('priority')
                ->from($this->t('column'))
                ->where('table_id', $current_table->table_id)
                ->get()->row()->priority;
            $priority = $max_priority==NULL? 1: $max_priority+1;
            // loop for other tables, look for master-detail relationship
            foreach($table_result as $other_table){
                // master-detail to current table is not handled
                if($current_table->table_id == $other_table->table_id){continue;}
                $other_stripped_table_name = $other_table->stripped_name;
                // loop for each column to determine one to many relationship
                $other_column_query = $this->db->select('column_id, name, role, caption, lookup_table_id, lookup_column_id')
                    ->from($this->t('column'))
                    ->where('table_id', $other_table->table_id)
                    ->get();
                $column_count = $other_column_query->num_rows();
                // get other's lookup column
                $other_fk_column_to_this = NULL;
                $other_fk_column_to_selection = NULL;
                foreach($other_column_query->result() as $other_column){
                    // determine foreign key to current table, and determine foreign key to selection table
                    if($other_column->role == 'lookup'){
                        if($other_fk_column_to_this == NULL && $other_column->lookup_table_id == $current_table->table_id){
                            $other_fk_column_to_this = $other_column;
                        } else if( $other_fk_column_to_selection == NULL && $other_column->lookup_table_id != $current_table->table_id){
                            $other_fk_column_to_selection = $other_column;
                        }
                    }
                    // if column count > 3 then, this other table is certainly master-detail table, not many-to-many relation table. Otherwise, this probably relation table
                    if($other_fk_column_to_this != NULL && ($column_count > 3 || $other_fk_column_to_selection != NULL)){
                        break;
                    }
                }
                if($other_fk_column_to_this != NULL){
                    // set this variable to true if selection_column_id found
                    $many_to_many = FALSE;
                    if($column_count <= 3 && $other_fk_column_to_selection != NULL){
                        // this is likely many-to-many relation, look for selection table
                        $selection_column_query = $this->db->select('column_id, name, role')
                            ->from($this->t('column'))
                            ->where('table_id', $other_fk_column_to_selection->lookup_table_id)
                            ->get();
                        $selection_column_id = NULL;
                        // get selection column id
                        foreach($selection_column_query->result() as $selection_column){
                            if($selection_column->role == ''){
                                $selection_column_id = $selection_column->column_id;
                            }
                            if($selection_column_id != NULL){
                                $many_to_many = TRUE;
                                break;
                            }
                        }
                        if($many_to_many){
                            // insert
                            $this->db->insert($this->t('column'), array(
                                'table_id' => $current_table->table_id,
                                'name' => $other_stripped_table_name,
                                'caption' => $other_table->caption,
                                'role' => 'detail many to many',
                                'relation_table_id' => $other_table->table_id,
                                'relation_table_column_id' => $other_fk_column_to_this->column_id,
                                'relation_selection_column_id' => $other_fk_column_to_selection->column_id,
                                'selection_table_id' => $other_fk_column_to_selection->lookup_table_id,
                                'selection_column_id' => $selection_column_id,
                                'priority' => $priority,
                            ));
                        }
                    }
                    if(!$many_to_many){
                        // this is master-detail
                        $this->db->insert($this->t('column'), array(
                            'table_id' => $current_table->table_id,
                            'name' => $other_stripped_table_name,
                            'caption' => $other_table->caption,
                            'role' => 'detail one to many',
                            'relation_table_id' => $other_table->table_id,
                            'relation_table_column_id' => $other_fk_column_to_this->column_id,
                            'priority' => $priority,
                        ));
                    }
                    $priority++;
                }
            }
        }

        return TRUE;

    }

    private function create_table($project_id){
        $this->load->helper('inflector');
        $save_db_schema = addslashes($this->db_schema);
        $save_db_table_prefix = addslashes($this->db_table_prefix);
        $SQL = "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA='$save_db_schema' AND TABLE_NAME like'$save_db_table_prefix%'";
        $result = mysqli_query($this->connection, $SQL);

        // get table initial priority (for ordering)
        $query = $this->db->select_max('priority')
                ->from($this->t('table'))
                ->where('project_id',$project_id)
                ->get();
        $row = $query->row();
        if(is_numeric($row->priority)){
            $priority = $row->priority + 1;
        }else{
            $priority = 0;
        }
        // loop through existing table in database, import it to nordrassil
        while($row = mysqli_fetch_array($result)){
            // create caption
            $caption = '';
            $prefix_length = isset($this->db_table_prefix)?strlen($this->db_table_prefix):0;
            if($prefix_length>0){
                $caption = substr($row['TABLE_NAME'], $prefix_length);
                $caption = $this->humanize($caption);
                $caption = trim($caption);
                if(strlen($caption)==0){
                    $caption = $this->humanize($row['TABLE_NAME']);
                }
            }else{
                $caption = $this->humanize($row['TABLE_NAME']);
            }
            $table_name = $row['TABLE_NAME'];
            $save_table_name = addslashes($table_name);

            // get column names
            $SQL =
                "SELECT
                    COLUMN_NAME
                FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='$save_db_schema' AND TABLE_NAME='$save_table_name'";
            $result_column = mysqli_query($this->connection, $SQL);
            $column_names = array();
            while($row_column = mysqli_fetch_array($result_column)){
                $column_names[] = $row_column['COLUMN_NAME'];
            }
            // get the data and turn it into json
            $table_data = array();
            $SQL = 'SELECT * FROM '.$save_db_schema.'.'.$save_table_name;
            $result_table = mysqli_query($this->connection, $SQL);
            while($row_table = mysqli_fetch_array($result_table)){
                $record = array();
                foreach($column_names as $column_name){
                    $record[$column_name] = $row_table[$column_name];
                }
                $table_data[] = $record;
            }

            // inserting the table
            $data = array(
                    'project_id' => $project_id,
                    'name'       => $table_name,
                    'caption'    => $caption,
                    'priority'   => $priority,
                    'data'       => @json_encode($table_data),
                );
            $query = $this->db->select('table_id')
                ->from($this->t('table'))
                ->where(array('project_id'=>$project_id, 'name'=>$table_name))
                ->get();
            if($query->num_rows()>0){
                $row = $query->row();
                $table_id = $row->table_id;
                // don't change caption and priority
                unset($data['caption']);
                unset($data['priority']);
                $this->db->update($this->t('table'),
                    $data,
                    array('table_id' => $table_id));
            }else{
                $this->db->insert($this->t('table'), $data);
                $priority++;
                $table_id = $this->db->insert_id();
            }
            // inserting the field
            $table_name = $table_name;
            $this->create_field($table_id, $table_name);
        }
    }

    private function create_field($table_id, $table_name){
        $required_option_id = NULL;
        $no_required_option = FALSE;

        $this->load->helper('inflector');
        $save_db_schema = addslashes($this->db_schema);
        $save_table_name = addslashes($table_name);
        $SQL =
            "SELECT
                COLUMN_NAME, DATA_TYPE, IS_NULLABLE, CHARACTER_MAXIMUM_LENGTH,
                NUMERIC_PRECISION, NUMERIC_SCALE, COLUMN_KEY
            FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='$save_db_schema' AND TABLE_NAME='$save_table_name'";
        $result = mysqli_query($this->connection, $SQL);

        // get field initial priority (for ordering)
        $query = $this->db->select_max('priority')
                ->from($this->t('column'))
                ->where('table_id',$table_id)
                ->get();
        $row = $query->row();
        if(is_numeric($row->priority)){
            $priority = $row->priority + 1;
        }else{
            $priority = 0;
        }
        // loop through existing field in database, import it to nordrassil
        while($row = mysqli_fetch_array($result)){
            if($row['COLUMN_KEY'] == 'PRI'){
                $role = 'primary';
            }else{
                $role = '';
            }
            $is_nullable = (strtolower($row['IS_NULLABLE']) == 'yes')? TRUE: FALSE;
            $data_type = $row['DATA_TYPE'];
            $length = NULL;
            $value_selection_mode = NULL;
            $value_selection_item = NULL;
            // get enum or set
            if($data_type == 'set' || $data_type == 'enum'){
                $pattern = '/^'.$data_type.'\((.*)\)$/';
                $value_selection_mode = $data_type;
                $data_type = 'varchar';
                $length = 255;

                $column_sql = "SHOW COLUMNS FROM $save_db_schema.$save_table_name WHERE field ='".addslashes($row['COLUMN_NAME'])."'";
                $column_result = mysqli_query($this->connection, $column_sql);
                $column_row = mysqli_fetch_array($column_result);
                $type = $column_row['Type'];
                $matches = array();
                if(preg_match($pattern, $type, $matches)>0){
                    $value_selection_item = $matches[1];
                }
            }else{
                // get length (data_size) of the column
                if(in_array($data_type, $this->numeric_data_type)){
                    $length = $row['NUMERIC_PRECISION'];
                }else{
                    $length = $row['CHARACTER_MAXIMUM_LENGTH'];
                }
                if(!isset($length)){
                    $length = 11;
                }
            }
            if($role == 'primary'){
                $data_type = 'int';
                $length = 10;
            }
            // inserting the field
            $data = array(
                    'table_id' => $table_id,
                    'name'=> $row['COLUMN_NAME'],
                    'caption' => $this->humanize($row['COLUMN_NAME']),
                    'data_type' => $data_type,
                    'data_size' => $length,
                    'role' => $role,
                    'value_selection_mode'=>$value_selection_mode,
                    'value_selection_item'=>$value_selection_item,
                    'priority' => $priority,
                );
            $query = $this->db->select('column_id')
                ->from($this->t('column'))
                ->where(array('table_id'=>$table_id, 'name'=>$row['COLUMN_NAME']))
                ->get();
            if($query->num_rows()>0){
                $row = $query->row();
                $column_id = $row->column_id;
                // update
                $where = array('column_id' => $column_id);
                unset($data['priority']);
                unset($data['role']);
                $this->db->update($this->t('column'), $data, $where);
            }else{
                $this->db->insert($this->t('column'), $data);
                $priority++;
                $column_id = $this->db->insert_id();
            }
            // get template id
            if(!$is_nullable && $role != 'primary'){
                // get required option id if needed
                if(!$no_required_option && $required_option_id === NULL){
                    $t_template_option = $this->t('template_option');
                    $t_project = $this->t('project');
                    $t_table = $this->t('table');
                    $query = $this->db->select('option_id')
                        ->from($t_template_option)
                        ->join($t_project, "$t_project.template_id = $t_template_option.template_id")
                        ->join($t_table, "$t_table.project_id = $t_project.project_id")
                        ->where("$t_table.table_id", $table_id)
                        ->where("$t_template_option.name", 'required')
                        ->get();
                    $row = $query->row();
                    if($row !== NULL){
                        $required_option_id = $row->option_id;
                    }else{
                        $no_required_option = FALSE;
                    }
                }
                // insert if not available
                if($required_option_id !== NULL){
                    $t_column_option = $this->t('column_option');
                    $query = $this->db->select('*')
                        ->from($t_column_option)
                        ->where(array('column_id'=>$column_id, 'option_id'=>$required_option_id))
                        ->get();
                    $row = $query->row();
                    if($row === NULL){
                        $data = array('option_id'=>$required_option_id, 'column_id'=>$column_id);
                        $this->db->insert($t_column_option, $data);
                    }
                }
            }
        }

        // add primary key if not exists
        $query = $this->db->select('column_id, name, role')
            ->from($this->t('column'))
            ->where('table_id', $table_id)
            ->get();
        $primary_key_exists = FALSE;
        $id_exists = FALSE;
        foreach($query->result() as $row){
            if($row->role == 'primary'){
                $primary_key_exists = TRUE;
                break;
            }
            if($row->name == 'id'){
                $id_exists = TRUE;
            }
        }
        if(!$primary_key_exists){
            if($id_exists){
                $this->db->update($this->t('column'),
                    array('role' => 'primary', 'data_type' => 'int', 'data_size' => 10),
                    array('table_id' => $table_id, 'name' => 'id'));
            }else{
                $this->db->insert($this->t('column'),
                    array('name' => 'id', 'role' => 'primary', 'table_id' => $table_id, 'data_type' => 'int', 'data_size' => 10));
            }
        }

    }
}
?>
