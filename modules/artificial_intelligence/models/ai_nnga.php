<?php
require_once(APPPATH.'../modules/artificial_intelligence/models/ai_core.php');
require_once(APPPATH.'../modules/artificial_intelligence/models/ai_neural_network.php');
require_once(APPPATH.'../modules/artificial_intelligence/models/ai_genetics_algorithm.php');

/**
 * Description of ai_nnga
 *
 * @author gofrendi
 */
class AI_GA_For_NN extends AI_Genetics_Algorithm{
    //dear, future me, you have save everything in database right?
    //just pull NN's property to have a fitness function
    //I have made nn->out(input, customWeight) to help you
    protected $NN;
    private $dataSet;
    
    public function __construct(){
        parent::__construct();
        $this->NN = new AI_Neural_Network(); 
    }
    
    public function core_initialize($identifier){
        $prefix = substr($identifier,0,3);
        if($prefix != 'GA_') $identifier = 'GA_'.$identifier;
        parent::core_initialize($identifier);
        
        $this->NN->core_initialize(substr($identifier,3));
    }
    
    private function neuronCount(){
        $currentState = $this->NN->currentState();
        return $currentState["nn_neuronCount"];
    }
    
    private function decodeGene($gene){
        // 1st bit for sign
        // 32 bit for number
        
        $result = array();
        for($i=0; $i<strlen($gene); $i+=33){
            $num = bindec(substr($gene, $i+1, 32));
            if($gene[$i]==0) $num *= -1;
            $num *= 0.00000001;
            $result[] = $num;
        }
        return $result;
    }
    
    public function defineDataSet($dataSet){
        $this->dataSet = $dataSet;
    }
    
    public function set($individuCount = 100, $maxLoop = 100, $minFitness = 1000,
            $mutationRate = 0.3, $crossoverRate = 0.4, $reproductionRate = 0.3, $elitismRate = 0.2)
    {
        $chromosomeLength = 0;
        $neuronCount = $this->neuronCount();
        
        //from input to input layer, each number represented by 32 bit + 1 sign bit
        $chromosomeLength = 2*$neuronCount[0] * 33;
        for($i=0; $i<count($neuronCount)-1; $i++){
            //between layers, each number represented by 32 bit + 1 sign bit          
            $chromosomeLength += (($neuronCount[$i]+1) * $neuronCount[$i+1])*33; 
        }
        parent::set($individuCount, $chromosomeLength, $maxLoop, $minFitness, $mutationRate, $crossoverRate, $reproductionRate, $elitismRate);       
    }
    
    protected function calculateFitness($gene){
        $weights = $this->decodeGene($gene);
        $dataSet = $this->dataSet;
        $MSE = 0;
        for($i=0; $i<count($dataSet); $i++){
            $output = $this->NN->out($dataSet[$i][0], $weights);
            $desiredOutput = $dataSet[$i][1];
            for($j=0; $j<count($output); $j++){
                $MSE += pow($desiredOutput[$j]-$output[$j],2);
            }
        }
        $MSE /= count($dataSet[0][1]) * count($dataSet);
        
        return 1/($MSE+0.000001); //since MSE is 0 or positive, I do this to avoid division by zero
    }
    
    public function bestWeight(){
        $bestIndex = $this->ga_population["order"][0];
        $bestGene = $this->ga_population["genes"][$bestIndex];
        return $this->decodeGene($bestGene);
    }
    
}

class AI_NNGA extends AI_Neural_Network {
    //put your code here
    protected $GA;
    
    public function __construct(){
        parent::__construct();
        $this->GA = new AI_GA_For_NN();
    }
    
    public function core_initialize($identifier){
        parent::core_initialize($identifier);
        $this->GA->core_initialize('GA_'.$identifier);
    }
    
    public function set($neuronCount = array(2,3,3,2), $learningRate = 0.00001, $maxMSE = 0.1, $maxLoop = 100, 
            $individuCount = 100, $maxGALoop = 100, $minFitness = 1000, $mutationRate = 0.3, 
            $crossoverRate = 0.4, $reproductionRate = 0.3, $elitismRate = 0.2)
    {
        parent::set($neuronCount, $learningRate, $maxMSE, $maxLoop);
        $this->GA->set($individuCount, $maxGALoop, $minFitness, $mutationRate, $crossoverRate, $reproductionRate, $elitismRate);
    }
    
    public function train($dataSet, $use_GA = TRUE){        
        if($use_GA){
            $this->GA->core_initialize('GA_'.$this->core_identifier());
            $this->GA->defineDataSet($dataSet);
            $this->GA->process();
            $this->begin();
            $this->nn_weights = $this->GA->bestWeight();
            $this->end();
        }
        parent::train($dataSet);        
    }
    
    public function currentState(){
        $result = array(
            "nn" => parent::currentState(),
            "ga" => $this->GA->currentState()
        );
        return $result;
    }
    
}



?>
