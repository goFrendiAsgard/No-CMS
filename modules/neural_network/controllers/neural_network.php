<?php
/**
 * Description of neural_network
 *
 * @author gofrendi
 */
class neural_network extends CMS_Controller{ 
     
    public function __construct(){
         parent::__construct();
         $this->load->model('nn');
         $this->nn->initialize('Default');
         
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
    }
    
    public function set(){
        $hiddenNeuronCount = array(5);
        $learningRate = 0.1;
        $maxLoop = 200;
        $maxMSE = 0.1;                
        
        
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
        //echo json_encode((array)$this->nn->currentState());
        echo '<h4>State</h4>';
        echo '<pre>';
        echo var_dump($this->nn->currentState());
        echo '</pre>';
    }
     
    public function train(){
        $this->nn->process($this->dataSet);
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
        //echo json_encode($after);
        echo '<h4>Output</h4>';
        echo '<pre>';
        echo var_dump($after);
        echo '</pre>';
    }
    
    public function desired_output(){
        
        $output = array();
        foreach($this->dataSet as $data){
            $output[] = $data[1];
        }
        //echo json_encode($after);
        echo '<h4>Desired Output</h4>';
        echo '<pre>';
        echo var_dump(output);
        echo '</pre>';
    }
}

?>
