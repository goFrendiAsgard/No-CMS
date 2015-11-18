<?php
require_once(dirname(__FILE__).'/Ai_core.php');

/**
 * Description of ai_genetics_algorithm
 *
 * @author gofrendi
 */
class Ai_genetics_algorithm extends AI_Core{
    //put your code here
    protected $ga_population;           // genes: int[], fitness: float[], order : int[]
    protected $ga_fitness;              // the copy of ga_population fitness, without same individu
    protected $ga_genes;                // the copy of ga_population genes, without same individu

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
    protected $ga_alreadyCalculatedGenes;// pair {genes : float}

    public function __construct(){
        parent::__construct();
    }

    protected function begin(){
        $property = $this->core_getProperty();
        $this->ga_genes                 = $property["ga_genes"];
        $this->ga_fitness               = $property["ga_fitness"];
        $this->ga_fitnessOrder          = $property["ga_fitnessOrder"];
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
        $this->ga_alreadyCalculatedGenes= $property["ga_alreadyCalculatedGenes"];
    }

    protected function end(){
        $this->core_saveProperty(
                array(
                    "ga_genes",
                    "ga_fitness",
                    "ga_fitnessOrder",
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
                    "ga_bestFitness",
                    "ga_alreadyCalculatedGenes"
                ),
                array(
                    $this->ga_genes,
                    $this->ga_fitness,
                    $this->ga_fitnessOrder,
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
                    $this->ga_bestFitness,
                    $this->ga_alreadyCalculatedGenes,
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
        //$this->ga_population = array();
        $this->ga_genes = array();
        $this->ga_fitness = array();
        $this->ga_fitnessOrder = array();


        $this->end();
    }

    public function process(){
        set_time_limit(0);                   // ignore php timeout
        ignore_user_abort(true);             // keep on going even if user pulls the plug*
        while (ob_get_level())
            ob_end_clean();                  // remove output buffers
        ob_implicit_flush(true);             // output stuff directly
        $this->begin();
        for($i=0; $i<$this->ga_maxLoop; $i++){
            $this->begin();
            $startTime = microtime(true);

            $this->newGeneration();
            $this->ga_loop++;

            $endTime = microtime(true);
            $this->ga_time += $endTime-$startTime;
            $this->end();

            if($this->ga_bestFitness[count($this->ga_bestFitness)-1]>$this->ga_minFitness){
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
        //mt_srand(5);
        for($i=0; $i<mt_rand(0, strlen($gene)-1); $i++){
            //mt_srand(6);
            $mutationPoint = mt_rand(0, strlen($gene)-1);
            $gene[$mutationPoint] = $gene[$mutationPoint]==0 ? 1 : 0;
        }
        //here is the mutant :D
        return $gene;
    }

    protected function crossover($gene1, $gene2){

        //mt_srand(4);
        $crossPoint = mt_rand(0, strlen($gene1)-1);

        $gene1_1 = substr($gene1, 0, $crossPoint);
        $gene1_2 = substr($gene1, $crossPoint);

        $gene2_1 = substr($gene2, 0, $crossPoint);
        $gene2_2 = substr($gene2, $crossPoint);

        $gene1 = $gene1_1 . $gene2_2;
        $gene2 = $gene2_1 . $gene1_2;


        $genes=array(
            $gene1,
            $gene2
        );
        return $genes;
    }


    private function newIndividu(){

        $result = "";
        for($i=0; $i<$this->ga_chromosomeLength; $i++){
            //mt_srand(3);
            $result.= mt_rand(0, 1);
        }

        return $result;
    }

    private function getRandomGene(){
        //the bigger fitness value, the bigger chance
        $cdf = array();
        $sum = 0;
        for($i=0; $i<count($this->ga_genes); $i++){
            $sum += $this->ga_fitness[$i];
            $cdf[$i]=$sum;
        }
        //mt_srand(2);
        $dice = $sum * mt_rand(0,100)/100;
        //$dice = mt_rand(0, $sum);

        $choosenIndex = 0;
        for($i=0; $i<count($this->ga_genes); $i++){
            if($dice<=$cdf[$i]){
                $choosenIndex = $i;
                break;
            }
        }

        return $this->ga_genes[$choosenIndex];

    }

    private function newGeneration(){
        $genes = array();
        if(count($this->ga_genes)==0){
            for($i=0; $i<$this->ga_individuCount; $i++){
                $genes[] = $this->newIndividu();
            }
        }else{
            //make a new generation from oldPopulation
            //elitism first
            for($i=0; $i<round($this->ga_elitismRate*$this->ga_individuCount); $i++){
                $genes[$i] = $this->copyGene($this->ga_genes[$this->ga_fitnessOrder[$i]]);
            }
            //next we deal with mutation, crossover or reproduction
            for($i=count($genes); $i<$this->ga_individuCount; $i++){
                $dice = mt_rand(0, $this->ga_mutationRate + $this->ga_crossoverRate + $this->ga_reproductionRate);
                if($dice<$this->ga_mutationRate){
                    //perform mutation here
                    $gene = $this->getRandomGene();
                    $genes[$i] = $this->mutation($gene);
                }else if($dice<($this->ga_mutationRate+$this->ga_crossoverRate)){
                    //preform crossover here
                    $gene1 = $this->getRandomGene();
                    $gene2 = $this->getRandomGene();
                    $offspring = $this->crossover($gene1, $gene2);
                    $genes[$i] = $offspring[0];
                    if($i<$this->ga_individuCount-1){
                        $i++;
                        $genes[$i] = $offspring[1];
                    }
                }else{
                    //perform reproduction here
                    $genes[$i] = $this->getRandomGene();
                }
            }
        }


        $this->ga_genes = array();
        $this->ga_fitness = array();
        $this->ga_fitnessOrder = array();
        for($i=0; $i<count($genes); $i++){
            $alreadyExists = FALSE;
            for($j=0; $j<$i; $j++){
                if($genes[$i]==$genes[$j]){
                    $alreadyExists = TRUE;
                    break;
                }
            }
            if(!$alreadyExists){
                $this->ga_genes[] = $genes[$i];
                $this->ga_fitness[] = $this->calculateFitness($genes[$i]);
                $this->ga_fitnessOrder[] = count($this->ga_fitnessOrder);
            }
        }

        //sort order
        for($i=0; $i<count($this->ga_genes)-1; $i++){
            $indexMax = $i;
            for($j=$i+1; $j<count($this->ga_genes)-1; $j++){
                if($this->ga_fitness[$j]>$this->ga_fitness[$indexMax]){
                    $indexMax = $j;
                }
            }
            $tmp = $this->ga_fitnessOrder[$i];
            $this->ga_fitnessOrder[$i] = $this->ga_fitnessOrder[$indexMax];
            $this->ga_fitnessOrder[$indexMax] = $tmp;
        }

        $this->ga_bestFitness[] = $this->ga_fitness[$this->ga_fitnessOrder[0]];

    }

    //absolutely need to override this
    protected function calculateFitness($gene){
        return 1;
    }

    public function currentState(){
        $state = $this->core_getProperty();
        return $state;
    }
}
