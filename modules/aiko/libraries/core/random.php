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
    private $min;
    private $max;
    
    public function __construct($randomSet=NULL){
        if(!isset($randomSet)){
            $this->random = array();
            for($i=0; $i<1000; $i++){
                $this->random[$i] = rand(0,1000);
            }
            $this->min=0;
            $this->max=1000;
        }
        $this->property = new Property($randomSet, 'randomset');
        $this->property->load();
        $this->random = $this->property->get('rand');
        $this->min = $this->property->get('min');
        $this->max = $this->property->get('max');
        
        $this->counter = 0;
    }
    
    public function get($min=NULL, $max=NULL){
        $rnd = $this->random[$this->counter];
        if($this->counter < count($this->random)){
            $this->counter++;
        }else{
            $this->counter = 0;
        }
        
        if(isset($min) && isset($max)){
            $rnd = $min+($max-$min)*($rnd-$this->min)/($this->max-$this->min);
        }
        return $rnd;
    }
}

?>
