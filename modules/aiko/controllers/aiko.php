<?php
/**
 * Aiko is a library contains some algorithms
 *
 * @author gofrendi
 */
class Aiko extends CMS_Controller{
    function index(){
        
        //test core/property
        $this->load->library('aiko/core/property');
        $this->property->set('a','aValue');
        $this->property->save();
        $this->property->load();
        $property_test = $this->property->get();
        
        //test core/matrix
        $this->load->library('aiko/core/matrix',array(array(1,5,1,4),array(1,3,1,2),array(3,5,1,1),array(1,2,3,4)));
        $matrix_test = array("determinant"=>$this->matrix->determinant());
        
        //test core/random
        $this->load->library('aiko/core/random','rnd');
        $random_test = array("get"=>array($this->random->get(), $this->random->get()));
        
        
        $data = array();
        $data['data'] = array(
            "property_test" => $property_test,
            "matrix_test" => $matrix_test,
            "random_test" => $random_test
        );
        $data['data'] = json_encode($data);
    	$this->view('aiko_index', $data);
    }
}
?>
