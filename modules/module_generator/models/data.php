<?php

/**
 * Description of data
 *
 * @author gofrendi
 */
class data extends CMS_Model{
    public function __construct() {
        parent::__construct();
    }
    public function get_tables(){
        $tables = $this->db->list_tables();
        return $tables;
    }
    
    public function get_fields($tableName){
        $fields = $this->db->field_data($tableName);
        return $fields;
    }
}

?>
