<?php
class grocery_crud_model_pdo extends grocery_CRUD_Generic_Model{

    public $subdriver = '';

    public function __construct(){
        parent::__construct();
        $this->subdriver = $this->db->subdriver;
        // these drivers doesn't have CONCAT command
        if(in_array($this->subdriver, array('sqlite', 'pgsql'))){
            $this->CAPABLE_CONCAT = FALSE;
        }
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

    function get_field_types_basic_table()
    {
        $results = parent::get_field_types_basic_table();
        if($this->subdriver == 'pgsql'){
            $results = array_reverse($results);
        }
        return $results;
    }

    function build_db_join_relation($related_table, $unique_name, $related_primary_key, $field_name){
        if($this->subdriver=='pgsql'){
            $this->db->join( $this->protect_identifiers($related_table).' as '.$this->protect_identifiers($unique_name) ,
                'cast('.$this->protect_identifiers($unique_name.'.'.$related_primary_key).' as character varying) = cast('. $this->protect_identifiers($this->table_name.'.'.$field_name).' as character varying)', 'left', FALSE);

        }else{
            parent::build_db_join_relation($related_table, $unique_name, $related_primary_key, $field_name);
        }
    }

    function build_relation_n_n_subquery($field, $selection_table, $relation_table, $primary_key_alias_to_selection_table, $primary_key_selection_table, $primary_key_alias_to_this_table, $field_name){
        if($this->subdriver=='pgsql'){
            return "(SELECT string_agg(".$this->protect_identifiers($field).", ',') FROM ".$this->protect_identifiers($selection_table)
                ." LEFT JOIN ".$this->protect_identifiers($relation_table)." ON ".$this->protect_identifiers($relation_table.".".$primary_key_alias_to_selection_table)." = ".$this->protect_identifiers($selection_table.".".$primary_key_selection_table)
                ." WHERE cast(".$this->protect_identifiers($relation_table.".".$primary_key_alias_to_this_table)." as character varying ) = cast(".$this->protect_identifiers($this->table_name.".".$this->get_primary_key())."  as character varying ) GROUP BY ".$this->protect_identifiers($relation_table.".".$primary_key_alias_to_this_table).") AS ".$this->protect_identifiers($field_name);
        }else{
            return parent::build_relation_n_n_subquery($field, $selection_table, $relation_table, $primary_key_alias_to_selection_table, $primary_key_selection_table, $primary_key_alias_to_this_table, $field_name);
        }
    }


}