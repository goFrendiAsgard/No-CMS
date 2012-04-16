<?php

if(defined('BASEPATH')){
    $CI =& get_instance();
    $CI->load->library('aiko/property');
}else{
    require_once('../core/property');
}

/**
 * Description of random
 *
 * @author gofrendi
 */
class Random {
    private $property;
    private $random;
    private $counter;
    
    public function __construct($randomSet=NULL){
        if(!isset($randomSet)){
            $this->random = array();
            for($i=0; $i<1000; $i++){
                $this->random[$i] = rand(0,1000);
            }
        }
        $this->property = new Property($randomSet, 'randomset');
        $this->property->load();
        $this->random = $this->property->get('rand');
        
        $this->counter = 0;
    }
    
    public function get(){
        $rnd = $this->random[$this->counter];
        if($this->counter < count($this->random)){
            $this->counter++;
        }else{
            $this->counter = 0;
        }
        
    }
}

?>
