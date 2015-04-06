<?php
class Grocery_crud_automatic_model  extends Grocery_crud_generic_model{

    private $class_name;

    public function __construct(){
        parent::__construct();
        $this->load->database();
        $db_driver = $this->db->platform();
        $this->class_name = 'grocery_crud_model_'.$db_driver;
    }

    public function __call($method, $args) {
        return call_user_func($this->class_name.'::'.$method, $args);
    }


}
