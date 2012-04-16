<?php

if(defined('BASEPATH')){
    $CI =& get_instance();
    $CI->load->library('aiko/random');
}else{
    require_once('../core/random');
}

/**
 * Description of neuron
 *
 * @author gofrendi
 */

class Neuron {
    private $output=0;
    private $weights = array();
    private $neurons = array();
    
    public function __construct($param){
        if(is_array($param)){
            $neurons = $param;
            $this->neurons = $neurons;
            $random = new Random('rnd');
            for($i=0; $i<count($neurons); $i++){
                $this->weights[$i] = $random->get();
            }
            $this->output = 0;
        }else{
            $this->output = $param;
        }
    }
    
    public function activation_function(){
        
    }
    
    public function activation_function_derivative(){
        
    }
    
    
}

?>
