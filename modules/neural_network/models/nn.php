<?php

/**
 * Description of nn
 *
 * @author gofrendi
 */
class NN extends CMS_Model {
    //put your code here
    private $identifier;
    
    protected $weights;
    protected $neuronCount;
    protected $learningRate;
    protected $maxMSE;
    protected $maxLoop;
    protected $loop;
    protected $MSE;
    
    public function initialize($identifier="Default"){
        $this->identifier = $identifier;        
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
    
    protected function weight($fromLayer, $fromNeuron, $toNeuron, $value = NULL){
        $startIndex = 0;
        for($i=0; $i<$fromLayer; $i++){
            $startIndex += (($this->neuronCount[$i]+1) * $this->neuronCount[$i+1]); 
        }       
        
        $index = $startIndex + ($fromNeuron * $this->neuronCount[$fromLayer+1]) +  $toNeuron; 
        $index = (int) $index;
        if(isset($value)){
            $this->weights[(int)$index] = $value;
        }else{
            return $this->weights[$index];
        }
    }
    
    protected function bias($fromLayer, $toNeuron, $value = NULL){
        $fromNeuron = $this->neuronCount[$fromLayer];
        return $this->weight($fromLayer, $fromNeuron, $toNeuron, $value);
    }
    
    
    public function set($neuronCount = array(2,3,3,2), $learningRate = 0.00001, $maxMSE = 0.1, $maxLoop = 10, $name="Default"){
        $this->neuronCount = $neuronCount;
        $this->learningRate = $learningRate;
        $this->maxMSE = $maxMSE;
        $this->maxLoop = $maxLoop;
        $this->weights = array(); 
        $this->loop = 0;
        $this->MSE = array();
        
        for($i=0; $i<count($neuronCount)-1; $i++){ //fromLayer
            for($j=0; $j<$neuronCount[$i+1]; $j++){ //toNeuron
                for($k=0; $k<$neuronCount[$i]; $k++){ //fromNeuron
                    $this->weight($i, $k, $j, (mt_rand(0, 100)/50)-1 );
                }
                $this->bias($i, $j, mt_rand(0, 1000)/1000);
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
        return $input;
        //return $this->activationFunction($input)*$this->activationFunction(1-$input); //It's called bell function or what,.... I don't sure :P
    } 
    
    protected function forward($input, &$allOut=NULL, &$out=NULL){
        $this->getSession();
        $allOut = array();
        $lastNeuron = array();
        for($layer=0; $layer<count($this->neuronCount); $layer++){                
            if($layer==0){
                $lastNeuron = array();
                for($i=0; $i<$this->neuronCount[0]; $i++){
                    $lastNeuron[$i] = $this->activationFunction($input[$i]);
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
        
        //get deltas
        $deltas = array();
        for($layer=count($this->neuronCount)-1; $layer>=0; $layer--){
            if($layer==count($this->neuronCount)-1){
                for($i=0; $i<$this->neuronCount[$layer]; $i++){
                    $deltas[$layer][$i] = ($desiredOutput[$i]-$output[$i]);
                }
                
            }else{
                for($i=0; $i<$this->neuronCount[$layer]; $i++){ //fromNeuron
                    $deltas[$layer][$i] = 0;
                    for($j=0; $j<$this->neuronCount[$layer+1]; $j++){ //toNeuron
                        $deltas[$layer][$i] += $deltas[$layer+1][$j] * $this->weight($layer, $i, $j);
                    }
                }
                
                $biasIndex = $this->neuronCount[$layer];
                $deltas[$layer][$biasIndex] = 0;
                for($j=0; $j<$this->neuronCount[$layer+1]; $j++){ //toNeuron
                    $deltas[$layer][$biasIndex] += $deltas[$layer+1][$j] * $this->bias($layer,$j);
                }
                
            }                
        }
        
        
        //adjust weights
        for($layer=0; $layer<count($this->neuronCount)-1; $layer++){ 
            
            //adjust weights
            for($i=0; $i<$this->neuronCount[$layer]; $i++){ //from neuron                
                for($j=0; $j<$this->neuronCount[$layer+1]; $j++){  //to neuron
                    
                    $value = 
                        $this->weight($layer, $i, $j) + 
                        (
                            $this->learningRate*
                            $deltas[$layer+1][$j]*
                            $this->activationFunctionDerivative($allOutput[$layer][$i])
                        );
                    
                    $this->weight($layer, $i, $j, $value);
                }
            }
            //adjust bias
            $biasIndex = $this->neuronCount[$layer];
            for($j=0; $j<$this->neuronCount[$layer+1]; $j++){  //to neuron

                $value = 
                    $this->bias($layer, $j) + 
                    (
                            $this->learningRate*
                            $deltas[$layer+1][$j]*
                            $this->activationFunctionDerivative(-1)
                    );

                $this->bias($layer, $j, $value);
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
            if(($this->MSE[$this->loop-1] <= $this->maxMSE) || $this->cms_ci_session("Neural_Network_Stop_".$this->identifier)){
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
    
}

?>
