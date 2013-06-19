<?php
class grocery_crud_model_PDO extends grocery_CRUD_Generic_Model{

    public $NO_CONCAT_DRIVER = array('sqlite', 'pgsql');
    public $subdriver = '';

    public function __construct(){
        parent::__construct();
        $this->subdriver = $this->db->subdriver;
    }

    function get_primary_key($table_name = null)
    {
        // let's see what the parent can do
        $primary_key = parent::get_primary_key($table_name);
        if($primary_key !== FALSE){
            return $primary_key;
        }

        // set default value for table_name if not set.
        if(!isset($table_name)){
            $table_name = $this->table_name;
        }

        // postgre need this
        if($this->subdriver=='pgsql'){
            $SQL = "SELECT
                  pg_attribute.attname
                FROM pg_index, pg_class, pg_attribute
                WHERE
                  pg_class.oid = '".$table_name."'::regclass AND
                  indrelid = pg_class.oid AND
                  pg_attribute.attrelid = pg_class.oid AND
                  pg_attribute.attnum = any(pg_index.indkey);";
            $query = $this->db->query($SQL);
            $row = $query->row();
            $primary_key = $row->attname;
        }

        return $primary_key;
    }

    function get_list()
    {
        if($this->table_name === null)
            return false;

        $select = $this->protect_identifiers("{$this->table_name}").".*";

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
                    // some DBMS doesn't have "CONCAT" function
                    if(in_array($this->subdriver,$this->NO_CONCAT_DRIVER)){
                        $select .= ", ('".str_replace(array('{','}'),array("' || COALESCE(".$this->protect_identifiers($unique_join_name).".".$this->ESCAPE_CHAR, $this->ESCAPE_CHAR.", '') || '"),str_replace("'", "\\'", $related_field_title))."') as ".$this->protect_identifiers($unique_field_name);
                    }else{
                        $select .= ", CONCAT('".str_replace(array('{','}'),array("',COALESCE(".$this->protect_identifiers($unique_join_name).".".$this->ESCAPE_CHAR, $this->ESCAPE_CHAR.", ''),'"),str_replace("'","\\'",$related_field_title))."') as ".$this->protect_identifiers($unique_field_name);
                    }
                }
                else
                {
                    $select .= ', ' . $this->protect_identifiers($unique_join_name).'.'. $this->protect_identifiers($related_field_title).' AS '. $this->protect_identifiers($unique_field_name);
                }

                if($this->field_exists($related_field_title))
                    $select .= ', '.$this->protect_identifiers($this->table_name. '.'. $related_field_title).' AS '.$this->protect_identifiers($this->table_name. '.'. $related_field_title);
            }
        }

        //set_relation_n_n special queries. We prefer sub queries from a simple join for the relation_n_n as it is faster and more stable on big tables.
        if(!empty($this->relation_n_n))
        {
            $select = $this->relation_n_n_queries($select);
        }

        $this->db->select($select, false);

        $results = $this->db->get($this->table_name)->result();

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
                // some DBMS doesn't have "CONCAT" function
                if(in_array($this->subdriver,$this->NO_CONCAT_DRIVER)){
                    $field .= "('".str_replace(array('{','}'),array("',COALESCE(",", '') || '"),str_replace("'","\\'",$title_field_selection_table))."')";
                }else{
                    $field .= "CONCAT('".str_replace(array('{','}'),array("',COALESCE(",", ''),'"),str_replace("'","\\'",$title_field_selection_table))."')";
                }
            }
            else
            {
                $field .= $this->protect_identifiers($selection_table.'.'.$title_field_selection_table);
            }

            //Sorry Codeigniter but you cannot help me with the subquery!
            // some DBMS doesn't have "GROUP_CONCAT" function, dunno yet :P
            if($this->subdriver=='pgsql'){
                $select .= ", (SELECT string_agg(".$this->protect_identifiers($field).", ',') FROM ".$this->protect_identifiers($selection_table)
                    ." LEFT JOIN ".$this->protect_identifiers($relation_table)." ON ".$this->protect_identifiers($relation_table.".".$primary_key_alias_to_selection_table)." = ".$this->protect_identifiers($selection_table.".".$primary_key_selection_table)
                    ." WHERE cast(".$this->protect_identifiers($relation_table.".".$primary_key_alias_to_this_table)." as character varying ) = cast(".$this->protect_identifiers($this->table_name.".".$this_table_primary_key)."  as character varying ) GROUP BY ".$this->protect_identifiers($relation_table.".".$primary_key_alias_to_this_table).") AS ".$this->protect_identifiers($field_name);
            }else{
                $select .= ", (SELECT GROUP_CONCAT(DISTINCT ".$this->protect_identifiers($field).") FROM ".$this->protect_identifiers($selection_table)
                    ." LEFT JOIN ".$this->protect_identifiers($relation_table)." ON ".$this->protect_identifiers($relation_table.".".$primary_key_alias_to_selection_table)." = ".$this->protect_identifiers($selection_table.".".$primary_key_selection_table)
                    ." WHERE ".$this->protect_identifiers($relation_table.".".$primary_key_alias_to_this_table)." = ".$this->protect_identifiers($this->table_name.".".$this_table_primary_key)." GROUP BY ".$this->protect_identifiers($relation_table.".".$primary_key_alias_to_this_table).") AS ".$this->protect_identifiers($field_name);
            }
        }

        return $select;
    }

    function join_relation($field_name , $related_table , $related_field_title)
    {
        $related_primary_key = $this->get_primary_key($related_table);

        if($related_primary_key !== false)
        {
            $unique_name = $this->_unique_join_name($field_name);
            if($this->subdriver=='pgsql'){
                $this->db->join( $this->protect_identifiers($related_table).' as '.$this->protect_identifiers($unique_name) ,
                    'cast('.$this->protect_identifiers($unique_name.'.'.$related_primary_key).' as character varying) = cast('. $this->protect_identifiers($this->table_name.'.'.$field_name).' as character varying)', 'left', FALSE);

            }else{
                $this->db->join( $this->protect_identifiers($related_table).' as '.$this->protect_identifiers($unique_name) , $this->protect_identifiers($unique_name.'.'.$related_primary_key).' = '. $this->protect_identifiers($this->table_name.'.'.$field_name),'left');
            }

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

        $select = "$related_table.$related_primary_key, ";

        if(strstr($related_field_title,'{'))
        {
            $related_field_title = str_replace(" ", "&nbsp;", $related_field_title);
            // some DBMS doesn't have "CONCAT" function
            if(in_array($this->subdriver,$this->NO_CONCAT_DRIVER)){
                $select .= "('".str_replace(array('{','}'),array("' || COALESCE(",", '') || '"),str_replace("'","\\'", $this->protect_identifiers($related_field_title)))."') as ".$this->protect_identifiers($field_name_hash);
            }else{
                $select .= "CONCAT('".str_replace(array('{','}'),array("',COALESCE(",", ''),'"),str_replace("'","\\'", $this->protect_identifiers($related_field_title)))."') as ".$this->protect_identifiers($field_name_hash);
            }
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
            if(in_array($this->subdriver,$this->NO_CONCAT_DRIVER)){
                $select .= "('".str_replace(array('{','}'),array("'|| COALESCE(",", '') || '"),str_replace("'","\\'",$related_field_title))."') as $field_name_hash";
            }else{
                $select .= "CONCAT('".str_replace(array('{','}'),array("',COALESCE(",", ''),'"),str_replace("'","\\'",$related_field_title))."') as $field_name_hash";
            }
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
            if(in_array($this->subdriver,$this->NO_CONCAT_DRIVER)){
                $select .= "('".str_replace(array('{','}'),array("'|| COALESCE(",", '') || '"),str_replace("'","\\'",$related_field_title))."') as $field_name_hash";
            }else{
                $select .= "CONCAT('".str_replace(array('{','}'),array("',COALESCE(",", ''),'"),str_replace("'","\\'",$related_field_title))."') as $field_name_hash";
            }
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

}