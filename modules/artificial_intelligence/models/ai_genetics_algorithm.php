<?php
require_once(APPPATH.'../modules/artificial_intelligence/models/ai_core.php');

/**
 * Description of ai_genetics_algorithm
 *
 * @author gofrendi
 */
class AI_Genetics_Algorithm extends AI_Core{
    //put your code here
    protected $ga_population;           // genes: int[], fitness: float[], order : int[]
    protected $ga_individuCount;        // int
    protected $ga_chromosomeLength;     // int
    protected $ga_maxLoop;              // int
    protected $ga_loop;                 // int
    protected $ga_minFitness;           // float
    protected $ga_mutationRate;         // float
    protected $ga_elitismRate;          // float
    protected $ga_crossoverRate;        // float
    protected $ga_reproductionRate;     // float
    protected $ga_time;                 // float
    protected $ga_bestFitness;          // float[]
    
    public function __construct(){
        parent::__construct();
    }
    
    protected function begin(){
        $property = $this->core_getProperty();
        $this->ga_population            = $property["ga_population"];
        $this->ga_individuCount         = $property["ga_individuCount"];
        $this->ga_chromosomeLength      = $property["ga_chromosomeLength"];
        $this->ga_maxLoop               = $property["ga_maxLoop"];
        $this->ga_loop                  = $property["ga_loop"];
        $this->ga_minFitness            = $property["ga_minFitness"];
        $this->ga_mutationRate          = $property["ga_mutationRate"];
        $this->ga_elitismRate           = $property["ga_elitismRate"];
        $this->ga_crossoverRate         = $property["ga_crossoverRate"];
        $this->ga_reproductionRate      = $property["ga_reproductionRate"];
        $this->ga_time                  = $property["ga_time"];
        $this->ga_bestFitness           = $property["ga_bestFitness"];
    }
    
    protected function end(){
        $this->core_saveProperty(
                array(
                    "ga_population",
                    "ga_individuCount",
                    "ga_chromosomeLength",
                    "ga_maxLoop",
                    "ga_loop",
                    "ga_minFitness",
                    "ga_mutationRate",
                    "ga_elitismRate",
                    "ga_crossoverRate",
                    "ga_reproductionRate",
                    "ga_time",
                    "ga_bestFitness"
                ), 
                array(
                    $this->ga_population,
                    $this->ga_individuCount,
                    $this->ga_chromosomeLength,
                    $this->ga_maxLoop,
                    $this->ga_loop,
                    $this->ga_minFitness,
                    $this->ga_mutationRate,
                    $this->ga_elitismRate,
                    $this->ga_crossoverRate,
                    $this->ga_reproductionRate,
                    $this->ga_time,
                    $this->ga_bestFitness
                )
              );
    }
    
    public function set($individuCount = 100, $chromosomeLength=20, $maxLoop = 100, $minFitness = 1000,
            $mutationRate = 0.3, $crossoverRate = 0.4, $reproductionRate = 0.3, $elitismRate = 0.2)
    {
        $this->ga_individuCount = $individuCount;
        $this->ga_chromosomeLength = $chromosomeLength;
        $this->ga_maxLoop = $maxLoop;
        $this->ga_minFitness = $minFitness;
        $this->ga_mutationRate = $mutationRate;
        $this->ga_crossoverRate = $crossoverRate;
        $this->ga_reproductionRate = $reproductionRate;
        $this->ga_elitismRate = $elitismRate;
        $this->ga_loop = 0;
        $this->ga_time = 0;
        $this->ga_bestFitness = array();
        
                
        $this->end();        
    }
    
    public function process(){
        $this->begin();
        if(!isset($this->ga_population["genes"])){
            $this->ga_population = $this->newGeneration();
            $this->end();
        }
        for($i=0; $i<$this->ga_maxLoop; $i++){
            $this->begin();
            $startTime = microtime(true);
            
            $this->ga_population = $this->newGeneration($this->ga_population);
            $this->ga_loop++;
            $this->ga_bestFitness[] = $this->ga_population["fitness"][$this->ga_population["order"][0]];
            
            $endTime = microtime(true);
            $this->ga_time += $endTime-$startTime;
            $this->end();
            
            $bestIndex = $this->ga_population["order"][0];
            if($this->ga_population["fitness"][$bestIndex]>$this->ga_minFitness){
                break;
            }
        } 
    }
    
    private function copyGene($oldGene){
        $newGene = $oldGene;
        return $newGene;
    }
    
    protected function mutation($gene){
        //random count of mutation point, random mutation point
        for($i=0; $i<mt_rand(0, strlen($gene)-1); $i++){
            $mutationPoint = mt_rand(0, strlen($gene)-1);
            $gene[$mutationPoint] = $gene[$mutationPoint]==0 ? 1 : 0;
        }
        //here is the mutant :D
        return $gene;
    }
    
    protected function crossover($gene1, $gene2){
        
        $crossPoint = mt_rand(0, strlen($gene1)-1);
        
        $gene1_1 = substr($gene1, 0, $crossPoint);
        $gene1_2 = substr($gene1, $crossPoint);
        
        $gene2_1 = substr($gene2, 0, $crossPoint);
        $gene2_2 = substr($gene2, $crossPoint);
        
        
        $genes=array(
            $gene1,
            $gene2
        );
        return $genes;
    }
    
    
    private function newIndividu(){
        
        $result = "";
        for($i=0; $i<$this->ga_chromosomeLength; $i++){
            $result.= mt_rand(0, 1);
        }
        
        return $result;        
    }
    
    private function getRandomGene($population){
        //the bigger fitness value, the bigger chance
        $sum = 0;
        for($i=0; $i<count($population["genes"]); $i++){
            $sum += $population["fitness"][$i];
        }
        $dice = $sum * mt_rand(0,1000)/1000;
        
        $wheel = 0;
        $choosenIndex = 0;
        for($i=0; $i<count($population["genes"]); $i++){
            $wheel += $population["fitness"][$i];
            if($dice<$wheel){
                $choosenIndex = $i;
                break;
            }
        }
        
        return $this->copyGene($population["genes"][$choosenIndex]);
        
    }
    
    private function newGeneration($oldPopulation = NULL){
        $genes = array();
        if(!isset($oldPopulation)){
            for($i=0; $i<$this->ga_individuCount; $i++){
                $genes[] = $this->newIndividu();
            }
        }else{
            //make a new generation from oldPopulation
            //elitism first            
            for($i=0; $i<round($this->ga_elitismRate*$this->ga_individuCount); $i++){
                $genes[$i] = $this->copyGene($oldPopulation["genes"][$oldPopulation["order"][$i]]);
            }
            //next we deal with mutation, crossover or reproduction
            for($i=count($genes); $i<$this->ga_individuCount; $i++){
                $dice = mt_rand(0, $this->ga_mutationRate + $this->ga_crossoverRate + $this->ga_reproductionRate);
                if($dice<$this->ga_mutationRate){
                    //perform mutation here
                    $gene = $this->getRandomGene($oldPopulation);
                    $genes[$i] = $this->mutation($gene);
                }else if($dice<($this->ga_mutationRate+$this->ga_crossoverRate)){
                    //preform crossover here
                    $gene1 = $this->getRandomGene($oldPopulation);
                    $gene2 = $this->getRandomGene($oldPopulation);
                    $offspring = $this->crossover($gene1, $gene2);
                    $genes[$i] = $offspring[0];
                    if($i<$this->ga_individuCount-1){
                        $i++;
                        $genes[$i] = $offspring[1];
                    }
                }else{
                    //perform reproduction here
                    $genes[$i] = $this->getRandomGene($oldPopulation);
                }
            }
        }
        
        $population["genes"] = $genes;
        $poputation["fitness"] = array();
        $population["order"] = array();
        
        //get fitness, prepare order
        for($i=0; $i<count($genes); $i++){
            $population["fitness"][$i] = $this->calculateFitness($genes[$i]);
            $population["order"][$i] = $i;            
        }
        //sort order
        for($i=0; $i<count($population["genes"])-1; $i++){
            $indexMax = $i;
            for($j=$i+1; $j<count($population["genes"])-1; $j++){
                if($population["fitness"][$j]>$population["fitness"][$indexMax]){
                    $indexMax = $j;
                }
            }
            $tmp = $population["order"][$i];
            $population["order"][$i] = $population["order"][$indexMax];
            $population["order"][$indexMax] = $tmp;
        }
        
        
        return $population;
    }
    
    //absolutely need to override this
    protected function calculateFitness($gene){
        return 1;
    }
    
    public function currentState(){
        return $this->core_getProperty();
    }
}

?>
