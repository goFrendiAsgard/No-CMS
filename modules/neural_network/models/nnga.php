<?php

/**
 * Description of nnga
 *
 * @author gofrendi
 */
class NNGA extends NN{
    //put your code here
    protected $population;  //genes: float[], fitness: float[], order: int[]
    protected $populationCount;
    protected $maxLoopGA;
    protected $mutationRate;
    protected $elitismRate;
    protected $crossoverRate;
    protected $reproductionRate;
    
    public function initialize($identifier="Default-GA"){
        parent::identifier($identifier);        
    }
    
    protected function saveSession(){
        $session_data=array(
            "weights" => (array)$this->weights,
            "neuronCount" => (array)$this->neuronCount,
            "learningRate" => $this->learningRate,
            "maxMSE" => $this->maxMSE,
            "maxLoop" => $this->maxLoop,
            "loop" =>  $this->loop,
            "MSE"=> (array)$this->MSE,
            "population" => $this->population,
            "maxLoopGA" => $this->maxLoopGa,
            "mutationRate" => $this->mutationRate,
            "crossoverRate" => $this->crossoverRate,
            "reproductionRate" => $this->reproductionRate,
            "elitismRate" => $this->elitismRate,  
            "populationCount" => $this->populationCount
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
            $this->population = $data_session["population"];
            $this->maxLoopGA = $data_session["maxLoopGA"];
            $this->mutationRate = $data_session["mutationRate"];
            $this->crossoverRate = $data_session["crossoverRate"];
            $this->reproductionRate = $data_session["reproductionRate"];
            $this->elitismRate = $data_session["elitismRate"];
            $this->populationCount = $data_session["populationCount"];
        }
    }
    
    public function set($neuronCount = array(2,3,3,2), $learningRate = 0.00001, 
            $maxMSE = 0.1, $maxLoop = 10, $populationCount = 100, $maxLoopGA = 100,
            $mutationRate = 0.3, $crossoverRate = 0.4, $reproductionRate = 0.3, $elitismRate = 0.2)
    {
        parent::set($neuronCOunt, $learningRate, $maxMSE, $maxLoop);
        $this->maxLoopGA = $maxLoopGA;
        $this->populationCount = $populationCount;
        $this->mutationRate = $mutationRate;
        $this->crossoverRate = $crossoverRate;
        $this->reproductionRate = $reproductionRate;
        $this->elitismRate = $elitismRate;
                
        $this->saveSession();        
    }
    
    public function process($dataSet, $useGA=FALSE){
        if($useGA){
            $this->population = $this->newGeneration($dataSet);
            for($i=0; $i<$this->maxLoopGA; $i++){
                $this->population = $this->newGeneration($dataSet, $this->population);
            }            
        }
        parent::process($dataSet);        
    }
    
    private function copyGene($oldGene){
        $newGene = array();
        for($i=0; $i<count($oldGene); $i++){
            $newGene[$i] = $oldGene;
        }
        return $newGene;
    }
    
    private function mutation($gene){
        //random count of mutation point, random mutation point
        for($i=0; $i<round(mt_rand(0,count($gene)-1)); $i++){
            $mutationPoint = round(mt_rand(0,count($gene)-1));
            $gene[$mutationPoint] = $gene[$mutationPoint]==0 ? 1 : 0;
        }
        //here is the mutant :D
        return $gene;
    }
    
    private function crossover($gene1, $gene2){
        $oldGene1 = $this->copyGene($gene1);
        $oldGene2 = $this->copyGene($gene2);
        
        $crossPoint = round(mt_rand(0,count($gene1)-1));
        
        for($i=0; $i<count($gene1); $i++){
            if($crossPoint<$i){
                $gene1[$i] = $oldGene2[$i];
            }else{
                $gene2[$i] = $oldGene1[$i];
            }
        }
        $genes=array(
            $gene1,
            $gene2
        );
        return $genes;
    }
    
    
    private function newIndividu(){
        $neuronCount = $this->neuronCount;
        $geneLength = 0;
        for($i=0; $i<count($neuronCount)-1; $i++){
            // each number represented by 12 bit, look at decodeGene() for more detail
            // 2^5*g0 + 2^4*g1 + 2^3*g2 + 2^2*g3 + 2^1*g4 + 2^0*g5 + 2^(-1)*g6 + 2^(-2)*g7 + 2^(-3)*g8 + 2^(-4)*g9 +
            // 2^(-5)*g10 + 2^(-6)*g11
            $geneLength += (($neuronCount[$i]+1) * $neuronCount[$i+1])*12; 
        }
        
        $result = array();
        for($i=0; $i<$geneLength; $i++){
            $result[] = round(mt_rand(0, 100)/100, 0);
        }
        
        return $result;        
    }
    
    private function decodeGene($gene){
        $result = array();
        for($i=0; $i<count($gene); $i+=12){
            $num = 0;
            for($j=0; $j<12; $j++){
                $num += pow(2,(5-j));
            }
            $result[] = $num;
        }
        return $result;
    }
    
    private function getRandomGene($population){
        $sum = 0;
        for($i=0; $i<count($population->genes); $i++){
            $sum += 1/$population->fitness[$i];
        }
        $dice = $sum * mt_rand(0,1000)/1000;
        
        $wheel = 0;
        $choosenIndex = 0;
        for($i=0; $i<count($population->genes); $i++){
            $wheel += 1/$population->fitness[$i];
            if($dice<$wheel){
                $choosenIndex = $i;
                break;
            }
        }
        
        return $this->copyGene($population->genes[$choosenIndex]);
        
    }
    
    private function newGeneration($dataSet, $oldPopulation = NULL){
        $genes = array();
        if(!isset($oldPopulation)){
            for($i=0; $i<count($this->populationCount); $i++){
                $genes[] = $this->newIndividu();
            }
        }else{
            //make a new generation from oldPopulation
            //elitism first
            for($i=0; $i<round($this->elitismRate*$this->populationCount); $i++){
                $genes[$i] = $this->copyGene($oldPopulation->genes[$oldPopulation->order[$i]]);
            }
            //next we deal with mutation, crossover or reproduction
            for($i=count($genes); $i<$this->populationCount; $i++){
                $dice = mt_rand(0, $this->mutationRate + $this->crossoverRate + $this->reproductionRate);
                if($dice<$this->mutationRate){
                    //perform mutation here
                    $gene = $this->getRandomGene($oldPopulation);
                    $genes[$i] = $this->mutation($gene);
                }else if($dice<($this->mutationRate+$this->crossoverRate)){
                    //preform crossover here
                    $gene1 = $this->getRandomGene($oldPopulation);
                    $gene2 = $this->getRandomGene($oldPopulation);
                    $offspring = $this->crossover($gene1, $gene2);
                    $genes[$i] = $offspring[0];
                    if($i<$this->populationCount-1){
                        $i++;
                        $genes[$i] = $offspring[1];
                    }
                }else{
                    //perform reproduction here
                    $genes[$i] = $this->getRandomGene($oldPopulation);
                }
            }
        }
        
        $population = object();
        $population->genes = $genes;
        $poputation->fitness = array();
        $population->order = array();
        //get fitness
        for($i=0; $i<count($genes); $i++){
            $this->weights = $this->decodeGene($genes[$i]);
            $MSE = 0;
            for($j=0; $j<count($dataSet); $j++){
                $output = $this->out($dataSet[$j][0]);
                $desiredOutput = $dataSet[$j][1];
                for($k=0; $k<count($output); $k++){
                    $MSE += pow($desiredOutput[$k]-$output[$k],2);
                }
            }
            $outputCount = count($dataSet[$j][1]);
            $MSE /= $outputCount*count($dataSet);
            $population->fitness[$i] = $MSE;            
        }
        
        //prepare order
        for($i=0; $i<count($population->fitness); $i++){
            $population->order[$i] = $i;
        }
        
        for($i=count($population)-1; $i>=0; $i--){//index terkanan
            $max = 0;
            for($j=0; $j<=$i; $j++){
                if($population->order[$j]>=$max){
                    $max = $j;
                }
            }
            $tmp=$population->order[$max];
            $population->order[$max] = $population->order[$i];
            $population->order[$i] = $tmp;            
        }
        
        
        return $population;
    }
}

?>
