<?php
require_once(dirname(__FILE__).'/Ai_core.php');
require_once(dirname(__FILE__).'/Ai_neural_network.php');
require_once(dirname(__FILE__).'/Ai_genetics_algorithm.php');

/**
 * Description of ai_nnga
 *
 * @author gofrendi
 */
class AI_GA_For_NN extends AI_Genetics_Algorithm{
    protected $NN;
    private $dataSet;
    private $bitLength=29;
    private $SHR = 26;
    private $decodedGene;

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
        // $this->bitLength for number

        if(isset($this->decodedGene[$gene])){
            return $this->decodedGene[$gene];
        }

        $result = array();
        for($i=0; $i<strlen($gene); $i+=($this->bitLength+1)){
            $num = bindec(substr($gene, $i+1, $this->bitLength));
            $num = $num >> $this->SHR;
            if($gene[$i]==0) $num *= -1;
            $result[] = $num;
        }
        $this->decodedGene[$gene] = $result;
        return $result;
    }

    public function defineDataSet($dataSet){
        $this->dataSet = $dataSet;
    }

    public function set($individuCount = 100, $chromosomeLength=20, $maxLoop = 1000, $minFitness = 1000,
            $mutationRate = 0.3, $crossoverRate = 0.4, $reproductionRate = 0.3, $elitismRate = 0.2)
    {
        $chromosomeLength = 0;
        $neuronCount = $this->neuronCount();

        //from input to input layer, each number represented by $this->bitLength + 1 sign bit
        $chromosomeLength = 2*$neuronCount[0] * ($this->bitLength+1);
        for($i=0; $i<count($neuronCount)-1; $i++){
            //between layers, each number represented by $this->bitLength + 1 sign bit
            $chromosomeLength += (($neuronCount[$i]+1) * $neuronCount[$i+1])*($this->bitLength+1);
        }
        parent::set($individuCount, $chromosomeLength, $maxLoop, $minFitness, $mutationRate, $crossoverRate, $reproductionRate, $elitismRate);
    }

    protected function calculateFitness($gene){
        if(isset($this->ga_alreadyCalculatedGenes[$gene])){
            return $this->ga_alreadyCalculatedGenes[$gene];
        }else{
            $weights = $this->decodeGene($gene);
            $dataSet = $this->dataSet;
            $MSE = 0;
            $dataSetCount = count($dataSet);
            $outputCount = count($dataSet[0][1]);
            for($i=0; $i<$dataSetCount; $i++){
                $output = $this->NN->out($dataSet[$i][0], $weights);
                $desiredOutput = $dataSet[$i][1];
                for($j=0; $j<$outputCount; $j++){
                    $MSE += pow($desiredOutput[$j]-$output[$j],2);
                }
            }
            $MSE /= $dataSetCount * $outputCount;

            $this->ga_alreadyCalculatedGenes[$gene] = 1/($MSE+0.000001); //since MSE is 0 or positive, I do this to avoid division by zero
            return $this->ga_alreadyCalculatedGenes[$gene];
        }
    }

    public function bestWeight(){
        $bestGene = $this->ga_genes[$this->ga_fitnessOrder[0]];
        return $this->decodeGene($bestGene);
    }

}

class Ai_nnga extends AI_Neural_Network {
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

    public function set($dataSet = array(array(array(0,0),array(0))),$neuronCount = array(2,3,3,2), $learningRate = 0.1, $maxMSE = 0.1, $maxLoop = 100,
            $individuCount = 100, $maxGALoop = 100, $minFitness = 1000, $mutationRate = 0.3,
            $crossoverRate = 0.4, $reproductionRate = 0.3, $elitismRate = 0.2)
    {
        parent::set($dataSet, $neuronCount, $learningRate, $maxMSE, $maxLoop);
        $this->GA->set($individuCount, 20, $maxGALoop, $minFitness, $mutationRate, $crossoverRate, $reproductionRate, $elitismRate);
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
