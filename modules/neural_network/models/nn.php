<?php

/**
 * Description of nn
 *
 * @author gofrendi
 */
class NN extends CMS_Model {
    //put your code here
    private $identifier="Default";
    
    protected $weights;         // float[]  , the weight between neurons (usually called as synapse or omega)
    protected $neuronCount;     // int[]    , the count of neuron in each layer
    protected $learningRate;    // float    , the learning rate of neural network
    protected $maxMSE;          // float    , the maximum MSE to stop the iteration
    protected $maxLoop;         // int      , the maximum loop when maximum MSE is not satisfied
    protected $loop;            // int      , the current loop index
    protected $MSE;             // float[]  , the MSE of each iteration
    
    public function initialize($identifier="Default"){
        $this->identifier = $identifier; 
        $this->getSession();
    }
    
    protected function identifier(){
        return $this->identifier;
    }
    
    
    protected function saveSessionData($session_data){
        //is current session exist on db, if it is then update, else then insert
        $SQL = "SELECT nn_id FROM nn_session WHERE nn_name='".addslashes($this->identifier)."'";
        $query = $this->db->query($SQL);
        if($query->num_rows()>0){
            $data = array(
                "data" => json_encode($session_data)
            );
            $where = array(
                "nn_name" => $this->identifier
            );
            $this->db->update("nn_session", $data, $where);
        }else{
            $data = array(
                "data" => json_encode($session_data),
                "nn_name" => $this->identifier
            );
            $this->db->insert("nn_session", $data);
        }
    }
    
    protected function getSessionData(){
        $SQL = "SELECT data FROM nn_session WHERE nn_name='".  addslashes($this->identifier)."'";
        $query = $this->db->query($SQL);
        if($query->num_rows()>0){
            $row = $query->row();
            return json_decode($row->data, true);
        }else{
            return NULL;
        }
        
    }
    
    protected function saveSession(){
        $session_data=array(
            "weights" => (array)$this->weights,
            "neuronCount" => (array)$this->neuronCount,
            "learningRate" => $this->learningRate,
            "maxMSE" => $this->maxMSE,
            "maxLoop" => $this->maxLoop,
            "loop" =>  $this->loop,
            "MSE"=> (array)$this->MSE
        );
        $this->saveSessionData($session_data);
        
    }
    
    protected function getSession(){        
        $data_session = $this->getSessionData();
        if(isset($data_session)){
            $this->weights = $data_session["weights"];
            $this->neuronCount = $data_session["neuronCount"];
            $this->learningRate = $data_session["learningRate"];
            $this->maxMSE = $data_session["maxMSE"];
            $this->maxLoop = $data_session["maxLoop"];
            $this->loop = $data_session["loop"];
            $this->MSE = $data_session["MSE"];
        }
    }
    
    //if fromLayer = (-1) : from input to input layer
    protected function weight($fromLayer, $fromNeuron, $toNeuron, $value = NULL){
        
        if($fromLayer==-1){ //from input to input layer
            if($fromNeuron<$this->neuronCount[0]){
                if($toNeuron<>$fromNeuron){//THIS IS IMPOSSIBLE, if happen then something wrong
                    return NULL;
                }
                $index = $fromNeuron;
            }else{ //bias
                $startIndex = $this->neuronCount[0];
                $index = $startIndex+$toNeuron;
            }
        }else{ //from input layer to hidden layer, from hidden layer to hidden layer, from hidden layer to output layer
            $startIndex = 0;
            $startIndex += 2 * $this->neuronCount[0]; //+1 for bias
            for($i=0; $i<$fromLayer; $i++){
                $startIndex += (($this->neuronCount[$i]+1) * $this->neuronCount[$i+1]); //+1 for bias
            } 
            $index = $startIndex + ($fromNeuron * $this->neuronCount[$fromLayer+1]) +  $toNeuron; 
        }
        $index = (int) $index;
        if(isset($value)){
            $this->weights[(int)$index] = $value;
        }else{
            return $this->weights[$index];
        }
    }
    
    protected function bias($fromLayer, $toNeuron, $value = NULL){
        if($fromLayer == -1){
            $fromNeuron = $this->neuronCount[0]; //from input
        }else{
            $fromNeuron = $this->neuronCount[$fromLayer]; //from input layer
        }
        return $this->weight($fromLayer, $fromNeuron, $toNeuron, $value);
    }
    
    
    public function set($neuronCount = array(2,3,3,2), $learningRate = 0.00001, $maxMSE = 0.1, $maxLoop = 10){
        $this->neuronCount = $neuronCount;
        $this->learningRate = $learningRate;
        $this->maxMSE = $maxMSE;
        $this->maxLoop = $maxLoop;
        $this->weights = array(); 
        $this->loop = 0;
        $this->MSE = array();
        
        //from layer -1
        for($i=0; $i<$neuronCount[0]; $i++){ //from input to input neuron
            $this->weight(-1, $i, $i, (mt_rand(0, 100)/50)-1 );
        };
        for($i=0; $i<$neuronCount[0]; $i++){ //from bias to input neuron
            $this->bias(-1, $i, mt_rand(0, 1000)/1000);
        }
        //from layer 0 until from layer n-1
        for($i=0; $i<count($neuronCount)-1; $i++){ //fromLayer
            for($j=0; $j<$neuronCount[$i]; $j++){ //fromNeuron
                for($k=0; $k<$neuronCount[$i+1]; $k++){ //toNeuron                
                    $this->weight($i, $j, $k, (mt_rand(0, 100)/50)-1 );
                }
            }
            for($j=0; $j<$neuronCount[$i+1]; $j++){ //toNeuron
                $this->bias($i, $j, (mt_rand(0, 100)/50)-1);
            }
        }
        
        $this->saveSession();
    }
    
    //OVERRIDE THIS !!!
    protected function activationFunction($input){
        return 1/(1+pow(M_E,-$input)); //M_E is e (2.7.....)
    }
    
    //OVERRIDE THIS !!!
    protected function activationFunctionDerivative($input){       
        //damn, the $input is already activated ( $input = faiz(sigma(omega*x)) )
        //therfore, activation derivative should be $input*(1-$input)
        return $input*(1-$input);
    } 
    
    protected function forward($input, &$allOut=NULL, &$out=NULL){
        $this->getSession();
        $allOut = array();
        $lastNeuron = array();
        for($layer=0; $layer<count($this->neuronCount); $layer++){                
            if($layer==0){
                $lastNeuron = array();
                for($i=0; $i<$this->neuronCount[0]; $i++){ //toNeuron
                    $value = 0;
                    $value += $this->weight(-1, $i, $i) * $input[$i];
                    $value += $this->bias(-1, $i) * -1;
                    $lastNeuron[$i] = $this->activationFunction($value);
                }
            }else{
                $nextNeuron = array();
                for($i=0; $i<$this->neuronCount[$layer]; $i++){ //toNeuron
                    $value = 0;
                    for($j=0; $j<$this->neuronCount[$layer-1]; $j++){ //fromNeuron 
                        $value += $this->weight($layer-1, $j, $i) * $lastNeuron[$j];
                    }
                    $value += $this->bias($layer-1, $i) * -1;
                    $nextNeuron[$i] = $this->activationFunction($value);
                }
                unset($lastNeuron);
                $lastNeuron = $nextNeuron;
            }
            $allOut[$layer] = $lastNeuron;
        } 
        if(count($allOut)>0){
            $out= $allOut[count($allOut)-1];
        }
    }
    
    public function out($input){
        $output = array();
        $allOutput = array();
        $this->forward($input, $allOutput, $output);   
        return $output;        
    }
    
    protected function backward($input, $desiredOutput, $output, $allOutput){
        $this->getSession();
        
        $errors = array();
        
        for($layer=count($this->neuronCount)-1; $layer>=0; $layer--){
            for($neuron=0; $neuron<$this->neuronCount[$layer]; $neuron++){
                if($layer==count($this->neuronCount)-1){
                    $delta = $desiredOutput[$neuron]-$allOutput[$layer][$neuron];
                }else{
                    $delta = 0;
                    for($neuronTo=0; $neuronTo<$this->neuronCount[$layer+1]; $neuronTo++){
                        $delta += $errors[$layer+1][$neuronTo] * $this->weight($layer,$neuron,$neuronTo);
                    }
                }
                //E = faiz'(output) * target-output
                $error = $this->activationFunctionDerivative($allOutput[$layer][$neuron]) * ($delta);
                $errors[$layer][$neuron] = $error;
            }
            
            for($neuron=0; $neuron<$this->neuronCount[$layer]; $neuron++){ //alpha
                
                $layerFrom = $layer-1;
                if($layerFrom>0){
                    $maxNeuronFrom = $this->neuronCount[$layerFrom]; 
                }else{
                    $maxNeuronFrom = $this->neuronCount[0];
                }
                //adjust weights
                for($neuronFrom = 0; $neuronFrom<$maxNeuronFrom; $neuronFrom++){ //a
                    if($layerFrom>0){
                        $value = $this->weight($layerFrom, $neuronFrom, $neuron)+
                                $this->learningRate*$errors[$layer][$neuron]*$allOutput[$layerFrom][$neuronFrom];
                    }else{
                        $value = $this->weight($layerFrom, $neuronFrom, $neuron)+
                                $this->learningRate*$errors[$layer][$neuron]*$input[$neuronFrom];
                    }
                    $this->weight($layerFrom, $neuronFrom, $neuron, $value);
                }
                //adjust biases
                if($layerFrom>0){
                    $value = $this->bias($layerFrom, $neuron)+
                            $this->learningRate*$errors[$layer][$neuron]*-1;
                }else{
                    $value = $this->bias($layerFrom, $neuron)+
                            $this->learningRate*$errors[$layer][$neuron]*-1;
                }
                $this->bias($layerFrom, $neuron, $value);
                
            }
            
        }
        
        $this->saveSession();
    }
    
    public function process($dataSet){
        $this->getSession();
        for($loop=0; $loop<$this->maxLoop; $loop++){
            
            
            $this->getSession();        
        
            $output = array(); //output of the last neuron
            $desiredOutput = array(); //desired output, target
            $allOutput = array(); //output of every neuron
            $MSE = 0;

            $dataSetCount = count($dataSet);
            $outputCount = count($dataSet[0][1]);

            for($data=0; $data<$dataSetCount; $data++){
                $input = $dataSet[$data][0];
                $desiredOutput = $dataSet[$data][1];
                $this->forward($input, $allOutput, $output); 
                $this->backward($input, $desiredOutput, $output, $allOutput);
                for($i=0; $i<$outputCount; $i++){
                    $delta = $output[$i] - $desiredOutput[$i];
                    $MSE += pow($delta, 2);
                }
            }
            $MSE /= $dataSetCount * $outputCount;
            $this->MSE[] = (float)$MSE;
            
            $this->loop++;
            $this->saveSession();
            
            //exit if there is stop signal or MSE lower than maxMSE
            if( $this->cms_ci_session("Neural_Network_Stop_".$this->identifier) || ($this->MSE[$this->loop-1] <= $this->maxMSE) ){
                $this->cms_unset_ci_session("Neural_Network_Stop_".$this->identifier);
                break;                
            } 
            
        }
    }
    
    
    
    public function currentState(){
        return $this->getSessionData($this->identifier);
    }
    
    public function stop(){
        $this->cms_ci_session("Neural_Network_Stop_".$this->identifier, TRUE);        
    }
    
    public function getWeight($fromLayer, $fromNeuron, $toNeuron){
        return $this->weight($fromLayer, $fromNeuron, $toNeuron);
    }
    public function getBias($fromLayer, $toNeuron){
        return $this->bias($fromLayer, $toNeuron);
    }
    
}

?>
