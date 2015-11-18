<?php
require_once(dirname(__FILE__).'/Ai_core.php');

/**
 * Description of nn
 *
 * @author gofrendi
 */
class Ai_neural_network extends AI_Core {

    protected $nn_weights;          // float[]  , the weight between neurons (usually called as synapse or omega)
    protected $nn_neuronCount;      // int[]    , the count of neuron in each layer
    protected $nn_learningRate;     // float    , the learning rate of neural network
    protected $nn_maxMSE;           // float    , the maximum MSE to stop the iteration
    protected $nn_maxLoop;          // int      , the maximum loop when maximum MSE is not satisfied
    protected $nn_loop;             // int      , the current loop index
    protected $nn_MSE;              // float[]  , the MSE of each iteration
    protected $nn_time;             // float    , the time
    protected $nn_dataset;          // float[][], the dataset for training

    public function __construct(){
        parent::__construct();
    }

    protected function begin(){
        $property = $this->core_getProperty();
        $this->nn_weights       = $property["nn_weights"];
        $this->nn_neuronCount   = $property["nn_neuronCount"];
        $this->nn_learningRate  = $property["nn_learningRate"];
        $this->nn_maxMSE        = $property["nn_maxMSE"];
        $this->nn_maxLoop       = $property["nn_maxLoop"];
        $this->nn_loop          = $property["nn_loop"];
        $this->nn_MSE           = $property["nn_MSE"];
        $this->nn_time          = $property["nn_time"];
        $this->nn_dataset       = $property["nn_dataset"];
    }

    protected function end(){
        $this->core_saveProperty(
                array(
                    "nn_weights",
                    "nn_neuronCount",
                    "nn_learningRate",
                    "nn_maxMSE",
                    "nn_maxLoop",
                    "nn_loop",
                    "nn_MSE",
                    "nn_time",
                    "nn_dataset",
                ),
                array(
                    $this->nn_weights,
                    $this->nn_neuronCount,
                    $this->nn_learningRate,
                    $this->nn_maxMSE,
                    $this->nn_maxLoop,
                    $this->nn_loop,
                    $this->nn_MSE,
                    $this->nn_time,
                    $this->nn_dataset
                )
              );
    }

    private function weightIndex($fromLayer, $fromNeuron, $toNeuron){
        //$this->begin();
        $index = NULL;
        if($fromLayer==-1){ //from input to input layer
            if($fromNeuron<$this->nn_neuronCount[0]){
                if($toNeuron<>$fromNeuron){//THIS IS IMPOSSIBLE, if happen then something wrong
                    return NULL;
                }
                $index = $fromNeuron;
            }else{ //bias
                $startIndex = $this->nn_neuronCount[0];
                $index = $startIndex+$toNeuron;
            }
        }else{ //from input layer to hidden layer, from hidden layer to hidden layer, from hidden layer to output layer
            $startIndex = 0;
            $startIndex += 2 * $this->nn_neuronCount[0]; //+1 for bias
            for($i=0; $i<$fromLayer; $i++){
                $startIndex += (($this->nn_neuronCount[$i]+1) * $this->nn_neuronCount[$i+1]); //+1 for bias
            }
            $index = $startIndex + ($fromNeuron * $this->nn_neuronCount[$fromLayer+1]) +  $toNeuron;
        }
        $index = (int) $index;
        return $index;
    }

    //if fromLayer = (-1) : from input to input layer
    protected function weight($fromLayer, $fromNeuron, $toNeuron, $value = NULL){
        $index = $this->weightIndex($fromLayer, $fromNeuron, $toNeuron);
        if(!isset($index)){
            echo $fromLayer.' '.$fromNeuron.' '.$toNeuron.'<br />';
        }
        if(isset($value)){
            $this->nn_weights[$index] = $value;
        }else{
            return $this->nn_weights[$index];
        }
    }

    protected function bias($fromLayer, $toNeuron, $value = NULL){
        if($fromLayer == -1){
            $fromNeuron = $this->nn_neuronCount[0]; //from input
        }else{
            $fromNeuron = $this->nn_neuronCount[$fromLayer]; //from input layer
        }
        return $this->weight($fromLayer, $fromNeuron, $toNeuron, $value);
    }


    public function set($dataset = array(array(array(0,0),array(0))),$neuronCount = array(2,3,3,2), $learningRate = 0.00001, $maxMSE = 0.1, $maxLoop = 10){
        $this->nn_dataset = $dataset;
        $this->nn_neuronCount = $neuronCount;
        $this->nn_learningRate = $learningRate;
        $this->nn_maxMSE = $maxMSE;
        $this->nn_maxLoop = $maxLoop;
        $this->nn_weights = array();
        $this->nn_loop = 0;
        $this->nn_MSE = array();
        $this->nn_time = 0;

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
        $this->end();
    }

    //OVERRIDE THIS !!!
    protected function activationFunction($input){
        return 1/(1+pow(M_E,-$input)); //M_E is e (2.7.....)
    }

    //OVERRIDE THIS !!!
    protected function activationFunctionDerivative($input){
        return $this->activationFunction($input)*(1-$this->activationFunction($input));
    }

    protected function forward($input=NULL, &$allNeuronOutput=NULL, &$allNeuronInput=NULL){

        $allNeuronInput = array(); //input of every neuron
        $allNeuronOutput = array(); //output of every neuron activationFunction(input)

        $lastIn = array();
        $lastOut = array();


        for($layer=0; $layer<count($this->nn_neuronCount); $layer++){
            if($layer==0){
                $lastOut = array();
                for($i=0; $i<$this->nn_neuronCount[0]; $i++){ //toNeuron
                    $value = 0;
                    $value += $this->weight(-1, $i, $i) * $input[$i];
                    $value += $this->bias(-1, $i) * -1;
                    $lastIn[$i] = $value;
                    $lastOut[$i] = $this->activationFunction($value);
                }
            }else{
                $nextOut = array();
                for($i=0; $i<$this->nn_neuronCount[$layer]; $i++){ //toNeuron
                    $value = 0;
                    for($j=0; $j<$this->nn_neuronCount[$layer-1]; $j++){ //fromNeuron
                        $value += $this->weight($layer-1, $j, $i) * $lastOut[$j];
                    }
                    $value += $this->bias($layer-1, $i) * -1;
                    $lastIn[$i] = $value;
                    $nextOut[$i] = $this->activationFunction($value);
                }
                unset($lastOut);
                $lastOut = $nextOut;
            }
            $allNeuronInput[$layer] = $lastIn;
            $allNeuronOutput[$layer] = $lastOut;
        }

        if(count($allNeuronOutput)>0){
            $output= $allNeuronOutput[count($allNeuronOutput)-1];
            return $output;
        }

    }



    protected function backward($input, $desiredOutput, $output, $allNeuronOutput, $allNeuronInput){

        $errors = array();

        for($layer=count($this->nn_neuronCount)-1; $layer>=0; $layer--){

            for($neuron=0; $neuron<$this->nn_neuronCount[$layer]; $neuron++){
                //delta = target_output - real_output
                if($layer==count($this->nn_neuronCount)-1){
                    $delta = $desiredOutput[$neuron]-$allNeuronOutput[$layer][$neuron];
                }else{
                    $delta = 0;
                    for($neuronTo=0; $neuronTo<$this->nn_neuronCount[$layer+1]; $neuronTo++){
                        $delta += $errors[$layer+1][$neuronTo] * $this->weight($layer,$neuron,$neuronTo);
                    }
                }
                $input_neuron = 0;
                //$error = $allNeuronOutput[$layer][$neuron]*(1-$allNeuronOutput[$layer][$neuron]) * $delta;
                $error = $this->activationFunctionDerivative($allNeuronInput[$layer][$neuron]) * ($delta);
                $errors[$layer][$neuron] = $error;
            }

            for($neuron=0; $neuron<$this->nn_neuronCount[$layer]; $neuron++){ //alpha

                $layerFrom = $layer-1;
                if($layerFrom>0){
                    $maxNeuronFrom = $this->nn_neuronCount[$layerFrom];
                }else{
                    $maxNeuronFrom = $this->nn_neuronCount[0];
                }
                //adjust weights
                if($layerFrom>=0){
                    for($neuronFrom = 0; $neuronFrom<$maxNeuronFrom; $neuronFrom++){
                        $value = $this->weight($layerFrom, $neuronFrom, $neuron)+
                                $this->nn_learningRate*$errors[$layer][$neuron]*$allNeuronOutput[$layerFrom][$neuronFrom];

                        $this->weight($layerFrom, $neuronFrom, $neuron, $value);
                    }
                }else{
                    $value = $this->weight($layerFrom, $neuron, $neuron)+
                            $this->nn_learningRate*$errors[$layer][$neuron]*$input[$neuron];
                    $this->weight($layerFrom, $neuron, $neuron, $value);

                }
                //adjust biases
                if($layerFrom>=0){
                    $value = $this->bias($layerFrom, $neuron)+
                            $this->nn_learningRate*$errors[$layer][$neuron]*-1;
                }else{
                    $value = $this->bias($layerFrom, $neuron)+
                            $this->nn_learningRate*$errors[$layer][$neuron]*-1;
                }
                $this->bias($layerFrom, $neuron, $value);

            }

        }

    }

    public function train($dataSet, $use_GA=TRUE){

        set_time_limit(0);                   // ignore php timeout
        ignore_user_abort(true);             // keep on going even if user pulls the plug*
        while (ob_get_level())
            ob_end_clean();                  // remove output buffers
        ob_implicit_flush(true);             // output stuff directly

        $this->begin();
        if(!isset($dataSet)){
            $dataSet = $this->nn_dataset;
        }

        for($loop=0; $loop<$this->nn_maxLoop; $loop++){


            $this->begin();

            $startTime = microtime(true);


            $output = array(); //output of the last neuron
            $desiredOutput = array(); //desired output, target
            $allOutput = array(); //output of every neuron
            $allInput = array();
            $MSE = 0;

            $dataSetCount = count($dataSet);
            $outputCount = count($dataSet[0][1]);

            for($data=0; $data<$dataSetCount; $data++){
                $datasetInput = $dataSet[$data][0];
                $desiredOutput = $dataSet[$data][1];
                $output = $this->forward($datasetInput, $allOutput, $allInput);
                $this->backward($datasetInput, $desiredOutput, $output, $allOutput, $allInput);
                for($i=0; $i<$outputCount; $i++){
                    $delta = $output[$i] - $desiredOutput[$i];
                    $MSE += pow($delta, 2);
                }
            }
            $MSE /= ($dataSetCount * $outputCount);
            $this->nn_MSE[] = (float)$MSE;

            $this->nn_loop++;

            $endTime = microtime(true);
            $this->nn_time += $endTime - $startTime;

            $this->end();

            //exit if there is stop signal
            if($this->cms_ci_session("Neural_Network_Stop_".$this->core_identifier())){
                $this->cms_ci_session("Neural_Network_Stop_".$this->core_identifier(), FALSE);
                break;
            }

            //exit if MSE lower than maxMSE
            if($this->nn_MSE[$this->nn_loop-1] <= $this->nn_maxMSE){
                break;
            }

        }

        $this->commit();
    }

    public function out($input, $customWeights = NULL){
        $this->begin();
        if(isset($customWeights)){
            $realWeight = $this->nn_weights;
            $this->nn_weights = $customWeights;
        }
        $result = $this->forward($input);
        if(isset($customWeights)){
            $this->begin();
            $this->nn_weights = $realWeight;
            $this->end();
        }
        return $result;
    }

    public function currentState(){


        $this->begin();

        $property = $this->core_getProperty();
        if(!isset($property)) return NULL;

        $weights = array();
        for($i=0; $i<$this->nn_neuronCount[0]; $i++){
            $weights[] = array(
                "fromLayer"=>-1,
                "fromNeuron"=>$i,
                "toNeuron"=>$i,
                "value"=>$this->weight(-1, $i, $i)
            );
            $weights[] = array(
                "fromLayer"=>-1,
                "fromNeuron"=>$this->nn_neuronCount[0],
                "toNeuron"=>$i,
                "value"=>$this->bias(-1, $i)
            );
        }
        for($i=0; $i<count($this->nn_neuronCount)-1; $i++){//fromLayer
            for($j=0; $j<$this->nn_neuronCount[$i+1]; $j++){//toNeuron
                for($k=0; $k<$this->nn_neuronCount[$i]; $k++){//fromNeuron
                    $weights[] = array(
                        "fromLayer"=>$i,
                        "fromNeuron"=>$k,
                        "toNeuron"=>$j,
                        "value"=>$this->weight($i, $k, $j)
                    );
                }
                $weights[] = array(
                        "fromLayer"=>$i,
                        "fromNeuron"=>$this->nn_neuronCount[$i],
                        "toNeuron"=>$j,
                        "value"=>$this->bias($i, $j)
                    );
            }
        }

        $property["nn_weights"] = $weights;

        $dataset = array();
        for($i=0; $i<count($this->nn_dataset); $i++){
            $dataset[$i] = array(
                "input" => $this->nn_dataset[$i][0],
                "target" => $this->nn_dataset[$i][1],
                "output" => $this->out($this->nn_dataset[$i][0])
            );
        }
        $property["nn_dataset"] = $dataset;

        return $property;
    }

    public function stop(){
        $this->cms_ci_session("Neural_Network_Stop_".$this->core_identifier(), TRUE);
    }

    public function getWeight($fromLayer, $fromNeuron, $toNeuron){
        $this->begin();
        return $this->weight($fromLayer, $fromNeuron, $toNeuron);
    }
    public function getBias($fromLayer, $toNeuron){
        $this->begin();
        return $this->bias($fromLayer, $toNeuron);
    }

}
