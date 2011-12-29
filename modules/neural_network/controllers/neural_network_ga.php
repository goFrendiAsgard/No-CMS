<?php
/**
 * Description of neural_network
 *
 * @author gofrendi
 */
class neural_network_ga extends CMS_Controller{ 
     
    public function __construct(){
         parent::__construct();
         $this->load->model('nnga', 'nn');
         $this->nn->initialize('Default');
         /*AND*/
         $this->dataSet = array(
             array(
                 array(0,0),
                 array(0)
             ),
             array(
                 array(0,1),
                 array(0)
             ),
             array(
                 array(1,0),
                 array(0)
             ),
             array(
                 array(1,1),
                 array(1)
             )
         );
         
         /*XOR*/
         /**
         $this->dataSet = array(
             array(
                 array(0,0),
                 array(0)
             ),
             array(
                 array(0,1),
                 array(1)
             ),
             array(
                 array(1,0),
                 array(1)
             ),
             array(
                 array(1,1),
                 array(0)
             )
         );
          * 
          */
         
         /*Suyanto*/ 
         /**
         $this->dataSet = array(
             array(
                 array(3,3,2),
                 array(1)
             ),
             array(
                 array(3,2,2),
                 array(1)
             ),
             array(
                 array(3,2,1),
                 array(1)
             ),
             array(
                 array(3,1,1),
                 array(0)
             ),
             array(
                 array(2,3,2),
                 array(1)
             ),
             array(
                 array(2,2,2),
                 array(1)
             ),
             array(
                 array(2,2,1),
                 array(1)
             ),
             array(
                 array(2,1,1),
                 array(0)
             ),
             array(
                 array(1,3,2),
                 array(1)
             ),
             array(
                 array(1,2,1),
                 array(0)
             ),
             array(
                 array(1,1,2),
                 array(1)
             ),
         );
          * 
          */
         
    }
    
    public function set(){
        $hiddenNeuronCount = array();
        $learningRate = 0.1;
        $maxLoop = 100;
        $maxMSE = 0.01;                
        
        
        $neuronCount = array();
        $neuronCount[] = count($this->dataSet[0][0]);
        for($i=0; $i<count($hiddenNeuronCount); $i++){
            $neuronCount[] = $hiddenNeuronCount[$i];
        }
        $neuronCount[] = count($this->dataSet[0][1]);
        
        $this->nn->set($neuronCount, $learningRate, $maxMSE, $maxLoop); 
    }
    
    public function stop(){
        $this->nn->stop();
    }
     
    public function index(){
        $this->view('neural_network_index', NULL, 'neural_network');
    }
     
    public function state(){
        echo json_encode((array)$this->nn->currentState());
    }
     
    public function train(){
        $this->nn->process($this->dataSet, TRUE);
    }
    
    public function output(){
        
        $input = array();
        foreach($this->dataSet as $data){
            $input[] = $data[0];
        }
        $after = array(); 
        foreach($input as $singleInput){
            $after[] = $this->nn->out($singleInput);
        }
        echo json_encode($after);
    }
    
    public function data(){
        echo json_encode($this->dataSet);
    }
    
    public function getWeight($fromLayer, $fromNeuron, $toNeuron){
        echo $this->nn->getWeight($fromLayer, $fromNeuron, $toNeuron);
    }
    public function getBias($fromLayer, $toNeuron){
        echo $this->nn->getBias($fromLayer, $toNeuron);
    }
}

?>
