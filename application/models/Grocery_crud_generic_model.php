<?php
class Grocery_crud_generic_model  extends Grocery_crud_model  {

    public $ESCAPE_CHAR = '"';
    public $CAPABLE_CONCAT = TRUE;

    protected static $__FIELD_TYPES;

    public function __construct(){
        parent::__construct();
        // set field_types
        if (self::$__FIELD_TYPES == null) {
            self::$__FIELD_TYPES = array();
        }
        // this is a simple hack to get ESCAPE_CHAR
        $test = $this->protect_identifiers('t');
        $first_char = substr($test,0,1);
        if($first_char !== 't'){
            $this->ESCAPE_CHAR = $first_char;
        }
    }

    protected function _preg_replace_callback_identifiers($arr){
        return '{'. $this->db->protect_identifiers($arr[1]).'}';
    }

    public function protect_identifiers($value)
    {
        $use_template = strpos($value,'{') !== false;
        if($use_template){
            return preg_replace_callback('/\{(.*?)\}/si',
                array($this, '_preg_replace_callback_identifiers'),
                $value);
        }
        return $this->db->protect_identifiers($value);
    }

    // rather than mess around with this everytime, it is better to build a function for this.
    public function build_concat_from_template($template, $prefix_replacement='', $suffix_replacement='', $as=NULL){
        if($this->CAPABLE_CONCAT){
            // if CONCAT is possible in the current driver
            $concat_str =
                "CONCAT('".
                str_replace(
                    array(
                        "{",
                        "}"
                    ),
                    array(
                        "',COALESCE(".$prefix_replacement,
                        $suffix_replacement.", ''),'"
                    ),
                    str_replace("'","\\'",$template)
                ).
                "')";

        }else{
            // if CONCAT is impossible in the current driver, use || instead
            $concat_str =
                "('".
                str_replace(
                    array(
                        "{",
                        "}"
                    ),
                    array(
                        "' || COALESCE(".$prefix_replacement,
                        $suffix_replacement.", '') || '"
                    ),
                    str_replace("'","\\'",$template)
                ).
                "')";
        }
        if(isset($as)){
            $concat_str .= " as ".$as;
        }
        return $concat_str;

    }

    function get_list()
    {
        if($this->table_name === null)
            return false;

        $select = $this->protect_identifiers("{$this->table_name}").".*";

        // this variable is used to save table.column info since postgresql doesn't support "AS 'table.column'" syntax
        $additional_fields = array();
        //set_relation special queries
        if(!empty($this->relation))
        {
            foreach($this->relation as $relation)
            {
                list($field_name , $related_table , $related_field_title) = $relation;
                $unique_join_name = $this->_unique_join_name($field_name);
                $unique_field_name = $this->_unique_field_name($field_name);
                if(strstr($related_field_title,'{'))
                {
                    $related_field_title = str_replace(" ","&nbsp;",$related_field_title);
                    $select .= ", ".$this->build_concat_from_template(
                            $related_field_title,
                            $this->protect_identifiers($unique_join_name).".".$this->ESCAPE_CHAR,
                            $this->ESCAPE_CHAR,
                            $this->protect_identifiers($unique_field_name)
                        );
                    //$select .= ", CONCAT('".str_replace(array('{','}'),array("',COALESCE(".$this->protect_identifiers($unique_join_name).".".$this->ESCAPE_CHAR, $this->ESCAPE_CHAR.", ''),'"),str_replace("'","\\'",$related_field_title))."') as ".$this->protect_identifiers($unique_field_name);
                }
                else
                {
                    $select .= ', ' . $this->protect_identifiers($unique_join_name. '.'. $related_field_title).' AS '. $this->protect_identifiers($unique_field_name);
                }

                if($this->field_exists($related_field_title)){
                    $additional_fields[$this->table_name. '.'. $related_field_title] = $related_field_title;
                    // this syntax doesn't work on postgresql
                    //$select .= ', '.$this->protect_identifiers($this->table_name. '.'. $related_field_title).' AS \''.$this->table_name. '.'. $related_field_title.'\'';
                }

            }
        }

        //set_relation_n_n special queries. We prefer sub queries from a simple join for the relation_n_n as it is faster and more stable on big tables.
        if(!empty($this->relation_n_n))
        {
            $select = $this->relation_n_n_queries($select);
        }

        $this->db->select($select, false);
        $results = $this->db->get($this->table_name)->result();

        //log_message('error', $this->db->last_query());

        // add information from additional_fields
        for($i=0; $i<count($results); $i++){
            foreach($additional_fields as $alias=>$real_field){
                $results[$i]->{$alias} = $results[$i]->{$real_field};
            }
        }
        return $results;
    }

    protected function relation_n_n_queries($select)
    {
        $this_table_primary_key = $this->get_primary_key();
        foreach($this->relation_n_n as $relation_n_n)
        {
            list($field_name, $relation_table, $selection_table, $primary_key_alias_to_this_table,
                        $primary_key_alias_to_selection_table, $title_field_selection_table, $priority_field_relation_table) = array_values((array)$relation_n_n);

            $primary_key_selection_table = $this->get_primary_key($selection_table);

            $field = "";
            $use_template = strpos($title_field_selection_table,'{') !== false;
            $field_name_hash = $this->_unique_field_name($title_field_selection_table);
            if($use_template)
            {
                $title_field_selection_table = str_replace(" ", "&nbsp;", $title_field_selection_table);
                $field .= $this->build_concat_from_template($this->protect_identifiers($title_field_selection_table));
                //$field .= "CONCAT('".str_replace(array('{','}'),array("',COALESCE(",", ''),'"),str_replace("'","\\'",$this->protect_identifiers($title_field_selection_table)))."')";
            }
            else
            {
                $field .= $this->protect_identifiers($selection_table.'.'.$title_field_selection_table);
            }
            //Sorry Codeigniter but you cannot help me with the subquery!
            $select .= ", ".
              $this->build_relation_n_n_subquery($field, $selection_table, $relation_table, $primary_key_alias_to_selection_table, $primary_key_selection_table, $primary_key_alias_to_this_table, $field_name);
        }

        return $select;
    }

    function get_total_results()
    {
        //set_relation_n_n special queries. We prefer sub queries from a simple join for the relation_n_n as it is faster and more stable on big tables.
        if(!empty($this->relation_n_n))
        {
            $select = $this->protect_identifiers($this->table_name).'.'.'*';
            $select = $this->relation_n_n_queries($select);

            $this->db->select($select,false);

            return $this->db->get($this->table_name)->num_rows();
        }

        return $this->db->count_all_results($this->table_name);
    }

    function join_relation($field_name , $related_table , $related_field_title)
    {
        $related_primary_key = $this->get_primary_key($related_table);

        if($related_primary_key !== false)
        {
            $unique_name = $this->_unique_join_name($field_name);
            $this->build_db_join_relation($related_table, $unique_name, $related_primary_key, $field_name);

            $this->relation[$field_name] = array($field_name , $related_table , $related_field_title);

            return true;
        }

        return false;
    }

    function get_relation_array($field_name , $related_table , $related_field_title, $where_clause, $order_by, $limit = null, $search_like = null)
    {
        $relation_array = array();
        $field_name_hash = $this->_unique_field_name($field_name);

        $related_primary_key = $this->get_primary_key($related_table);

        $select = $this->protect_identifiers($related_table).'.'.$this->protect_identifiers($related_primary_key).', ';

        if(strstr($related_field_title,'{'))
        {
            $related_field_title = str_replace(" ", "&nbsp;", $related_field_title);
            $select .= $this->build_concat_from_template(
                    $related_field_title,
                    $this->ESCAPE_CHAR,
                    $this->ESCAPE_CHAR,
                    $this->protect_identifiers($field_name_hash)
                );
            //$select .= "CONCAT('".str_replace(array('{','}'),array("',COALESCE(".$this->ESCAPE_CHAR , $this->ESCAPE_CHAR.", ''),'"),str_replace("'","\\'", $related_field_title))."') as ".$this->protect_identifiers($field_name_hash);
        }
        else
        {
            $select .= $this->protect_identifiers($related_table.'.'.$related_field_title).' as '.$this->protect_identifiers($field_name_hash);
        }

        $this->db->select($select,false);
        if($where_clause !== null)
            $this->db->where($where_clause);

        if($where_clause !== null)
            $this->db->where($where_clause);

        if($limit !== null)
            $this->db->limit($limit);

        if($search_like !== null)
            $this->db->having($this->protect_identifiers($field_name_hash)." LIKE '%".$this->db->escape_like_str($search_like)."%'");

        $order_by !== null
            ? $this->db->order_by($order_by)
            : $this->db->order_by($field_name_hash);

        $results = $this->db->get($related_table)->result();

        foreach($results as $row)
        {
            $relation_array[$row->$related_primary_key] = $row->$field_name_hash;
        }

        return $relation_array;
    }

    function get_relation_n_n_selection_array($primary_key_value, $field_info)
    {
        $select = "";
        $related_field_title = $field_info->title_field_selection_table;
        $use_template = strpos($related_field_title,'{') !== false;;
        $field_name_hash = $this->_unique_field_name($related_field_title);
        if($use_template)
        {
            $related_field_title = str_replace(" ", "&nbsp;", $related_field_title);
            $select .= $this->build_concat_from_template(
                    $related_field_title,
                    $this->ESCAPE_CHAR,
                    $this->ESCAPE_CHAR,
                    $this->protect_identifiers($field_name_hash)
                );
            //$select .= "CONCAT('".str_replace(array('{','}'),array("',COALESCE(",", ''),'"),str_replace("'","\\'",$related_field_title))."') as $field_name_hash";
        }
        else
        {
            $select .= "$related_field_title as $field_name_hash";
        }
        $this->db->select('*, '.$select,false);

        $selection_primary_key = $this->get_primary_key($field_info->selection_table);

        if(empty($field_info->priority_field_relation_table))
        {
            if(!$use_template){
                $this->db->order_by("{$field_info->selection_table}.{$field_info->title_field_selection_table}");
            }
        }
        else
        {
            $this->db->order_by("{$field_info->relation_table}.{$field_info->priority_field_relation_table}");
        }
        $this->db->where($field_info->primary_key_alias_to_this_table, $primary_key_value);
        $this->db->join(
                $field_info->selection_table,
                "{$field_info->relation_table}.{$field_info->primary_key_alias_to_selection_table} = {$field_info->selection_table}.{$selection_primary_key}"
            );
        $results = $this->db->get($field_info->relation_table)->result();

        $results_array = array();
        foreach($results as $row)
        {
            $results_array[$row->{$field_info->primary_key_alias_to_selection_table}] = $row->{$field_name_hash};
        }

        return $results_array;
    }

    function get_relation_n_n_unselected_array($field_info, $selected_values)
    {
        $use_where_clause = !empty($field_info->where_clause);

        $select = "";
        $related_field_title = $field_info->title_field_selection_table;
        $use_template = strpos($related_field_title,'{') !== false;
        $field_name_hash = $this->_unique_field_name($related_field_title);

        if($use_template)
        {
            $related_field_title = str_replace(" ", "&nbsp;", $related_field_title);
            $select .= $this->build_concat_from_template(
                    $related_field_title,
                    $this->ESCAPE_CHAR,
                    $this->ESCAPE_CHAR,
                    $this->protect_identifiers($field_name_hash)
                );
            //$select .= "CONCAT('".str_replace(array('{','}'),array("',COALESCE(",", ''),'"),str_replace("'","\\'",$related_field_title))."') as $field_name_hash";
        }
        else
        {
            $select .= "$related_field_title as $field_name_hash";
        }
        $this->db->select('*, '.$select,false);

        if($use_where_clause){
            $this->db->where($field_info->where_clause);
        }

        $selection_primary_key = $this->get_primary_key($field_info->selection_table);
        if(!$use_template)
            $this->db->order_by("{$field_info->selection_table}.{$field_info->title_field_selection_table}");
        $results = $this->db->get($field_info->selection_table)->result();

        $results_array = array();
        foreach($results as $row)
        {
            if(!isset($selected_values[$row->$selection_primary_key]))
                $results_array[$row->$selection_primary_key] = $row->{$field_name_hash};
        }

        return $results_array;
    }

    function get_field_types_basic_table()
    {
        $db_field_types = array();
        foreach($this->get_field_types($this->table_name) as $db_field_type)
        {
            $db_type = $db_field_type->type;
            $length = $db_field_type->max_length;
            $db_field_types[$db_field_type->name]['db_max_length'] = $length;
            $db_field_types[$db_field_type->name]['db_type'] = $db_type;
            $db_field_types[$db_field_type->name]['db_null'] = true;
            $db_field_types[$db_field_type->name]['db_extra'] = '';
        }

        $results = $this->get_field_types($this->table_name);
        foreach($results as $num => $row)
        {
            $row = (array)$row;
            $results[$num] = (object)( array_merge($row, $db_field_types[$row['name']])  );
        }
        return $results;
    }

    function get_field_types($table_name)
    {
        // take from cache if it is exists
        if(array_key_exists($table_name, self::$__FIELD_TYPES)){
            return self::$__FIELD_TYPES[$table_name];
        }

        $results = $this->db->field_data($table_name);
        // some driver doesn't provide primary_key information
        foreach($results as $num => $row)
        {
            $row = (array)$row;
            if(!array_key_exists('primary_key', $row)){
                $results[$num]->primary_key = 0;
            }
        }
        // save to cache
        self::$__FIELD_TYPES[$table_name] = $results;
        return $results;
    }

    function db_insert($post_array)
    {
        $insert = $this->db->insert($this->table_name,$post_array);
        if($insert)
        {
            $primary_key = $this->get_primary_key();
            // if user already define a value for the primary key, then just return it
            // postgresql use LASTVAL() to retrieve insert_id which would cause an error if the sequence is not used.
            if(array_key_exists($primary_key, $post_array)){
                return $post_array[$primary_key];
            }
            return $this->db->insert_id();
        }
        return false;
    }

    function build_db_join_relation($related_table, $unique_name, $related_primary_key, $field_name){
        $this->db->join($this->protect_identifiers($related_table).' as '.$this->protect_identifiers($unique_name) , $this->protect_identifiers($unique_name.'.'.$related_primary_key).' = '. $this->protect_identifiers($this->table_name.'.'.$field_name),'left');
    }

    function build_relation_n_n_subquery($field, $selection_table, $relation_table, $primary_key_alias_to_selection_table, $primary_key_selection_table, $primary_key_alias_to_this_table, $field_name){
        return "(SELECT GROUP_CONCAT(DISTINCT ".$this->protect_identifiers($field).") FROM ".$this->protect_identifiers($selection_table)
                    ." LEFT JOIN ".$this->protect_identifiers($relation_table)." ON ".$this->protect_identifiers($relation_table.".".$primary_key_alias_to_selection_table)." = ".$this->protect_identifiers($selection_table.".".$primary_key_selection_table)
                    ." WHERE ".$this->protect_identifiers($relation_table.".".$primary_key_alias_to_this_table)." = ".$this->protect_identifiers($this->table_name.".".$this->get_primary_key($this->table_name))." GROUP BY ".$this->protect_identifiers($relation_table.".".$primary_key_alias_to_this_table).") AS ".$this->protect_identifiers($field_name);
    }

    function db_delete($primary_key_value)
    {
        $primary_key_field = $this->get_primary_key();

        if($primary_key_field === false)
            return false;

        $this->db->delete($this->table_name,array( $primary_key_field => $primary_key_value));
        if( $this->db->affected_rows() != 1)
            return false;
        else
            return true;
    }

    function field_exists($field,$table_name = null)
    {
        if(empty($table_name))
        {
            $table_name = $this->table_name;
        }

        // sqlite doesn't support this $this->db->field_exists($field,$table_name)
        $field_data_list = $this->db->field_data($table_name);
        foreach($field_data_list as $field_data){
            if($field_data->name == $field) return TRUE;
        }
        return FALSE;
    }

    function get_edit_values($primary_key_value)
    {
        $result = parent::get_edit_values($primary_key_value);
        if($result === NULL){
            $result = new stdClass();
        }
        // some driver like postgresql doesn't return string
        foreach($result as $key => $value) {
            $result->$key = (string)$value;
        }

        return $result;
    }

    function having($key, $value = NULL, $escape = TRUE)
    {
        $this->db->having( $key, $value, $escape);
    }

    function db_relation_n_n_update($field_info, $post_data ,$main_primary_key){
        // addition by gofrendi: eliminate the possibility of empty primary key on n_n relation
        $new_post_data = array();
        foreach($post_data as $primary_key_value){
            if($primary_key_value != ''){
                $new_post_data[] = $primary_key_value;
            }
        }
        $post_data = $new_post_data;
        return parent::db_relation_n_n_update($field_info, $post_data, $main_primary_key);
    }

}
