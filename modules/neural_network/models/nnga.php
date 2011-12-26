<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of nnga
 *
 * @author gofrendi
 */
class NNGA extends NN{
    //put your code here
    private $genes;
    private $populationCount;
    
    public function initialize($identifier="Default-GA"){
        parent::identifier($identifier);        
    }
    
    protected function saveSession(){
        parent::saveSession();
        /*
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
         * 
         */
        
    }
    
    protected function getSession(){  
        parent::getSession();
        /*
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
         * 
         */
    }
    
    public function process($dataSet){
        
    }
    
    private function mutation(&$gene){
        $mutationPoint = round(mt_rand(0,count($gene)-1));
        $gene[$mutationPoint] = $gene[$mutationPoint] + (mt_rand(0, 100)/50)-1;
    }
    
    private function crossover(&$gene1, &$gene2){
        $oldGene1 = array();
        $oldGene2 = array();
        for($i=0; $i<count($gene1); $i++){
            $oldGene1[$i] = $gene1[$i];
            $oldGene2[$i] = $gene2[$i];
        }
        $crossPoint = round(mt_rand(0,count($gene1)-1));
        
        for($i=0; $i<count($gene1); $i++){
            if($crossPoint<$i){
                $gene1[$i] = $oldGene2[$i];
            }else{
                $gene2[$i] = $oldGene1[$i];
            }
        }
    }
    
    private function randomGene(){
        
        $config = $this->currentState();
        $neuronCount = $config["neuronCount"];
        $geneLength = 0;
        for($i=0; $i<count($neuronCount)-1; $i++){
            $geneLength += ($neuronCount[$i]+1) * $neuronCount[$i+1];
        }
        
        $result = array();
        for($i=0; $i<$geneLength; $i++){
            $result[] = (mt_rand(0, 100)/50)-1;
        }
        
        return $result;
        
    }
    
    private function newGeneration(){
        if(count($this->genes)==0){
            for($i=0; $i<count($this->genes); $i++){
                $this->genes[$i] = $this->randomGene();
            }
        }else{
            $result = array();
        }
        
    }
}

?>
