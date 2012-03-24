<?php
require_once(APPPATH.'../modules/artificial_intelligence/models/ai_core.php');
require_once(APPPATH.'../modules/artificial_intelligence/models/ai_genetics_algorithm.php');

class AI_AHGE extends AI_Genetics_Algorithm{
    private $dataSet;       // (float)dataSet[$i][0] = output, (array)dataSet[$i][1] = input
    private $grammar;       // (object)
    private $startExpression = "[feature]";
    private $varExpression = "[var]";
            
    
    public function defineDataSet($dataSet=NULL){
        if(!isset($dataSet)){
            $dataSet = array(
                array(1, array(1,2,3)), 
                array(4, array(2,2,3)), 
                array(9, array(3,2,3))
            );
        }
        $this->dataSet = $dataSet;
    }
    
    public function defineGrammar($grammar=NULL){
        if(!isset($grammar)){
            $grammar = array(
                $this->startExpression => array(
                    "[var]",
                    "([var] [op] [var])",
                    "([var] [op] [num])",
                    "([num] [op] [var])",
                    "([feature] [op] [feature])",
                    "[uniparam_function]([feature])",
                    "[biparam_function]([feature], [feature])"
                ),
                "[op]"  => array(
                    "+","-","*","/","%"
                ),
                "[num]" => array(
                    "[digit].[digit]",
                    "[digit]"
                ),
                "[digit]" => array(
                    "[digit][digit]",
                    "0","1","2","3","4","5","6","7","8","9"
                ),
                "[uniparam_function]" => array(
                    "sin","cos","tan"
                ),
                "[biparam_function]" => array(
                    "power", "log"
                ),
                $this->varExpression => array() //generated automatically by system                
            );
        }
        $this->grammar = $grammar;
    }
    
    public function set($individuCount = 100, $maxLoop = 1000, $minFitness = 1000,
            $mutationRate = 0.3, $crossoverRate = 0.4, $reproductionRate = 0.3, $elitismRate = 0.2)
    {        
        if(!isset($this->grammar)) $this->defineGrammar(NULL);
        if(!isset($this->dataSet)) $this->defineDataSet(NULL);        
       
        $this->grammar[$this->varExpression]=array();
        
        for($i=0; $i<count($this->dataSet[0][1]); $i++){
            $this->grammar[$this->varExpression][$i] = '$input['.$i.']'; 
        }
        
        $chromosomeLength = 100;
        parent::set($individuCount, $chromosomeLength, $maxLoop, $minFitness, $mutationRate, $crossoverRate, $reproductionRate, $elitismRate);       
    }
    
    
    
    private function getConstants($features){
        //TODO: this should be deterministic rather than random
        $constants = array();
        for($i=0; $i<count($features)+1; $i++){
            $constants[$i] = rand(0,10);
        }
        return $constants;
    }
    
    private function binaryDigitNeeded($node){
        $ruleCount = count($this->grammar[$node]);
        $digitNeeded = ceil(log($ruleCount,2));
        return $digitNeeded;
        return 2;
    }
    
    private function fetchRule($expression, $gene, &$geneIndex=0){
        $minNode = false;
        $minNodePosition = false;
        foreach(array_keys($this->grammar) as $node){
            $nodePosition = strpos($expression, $node);
            if(!is_bool($nodePosition) && ($minNode==false || $minNode>$nodePosition)){
                $minNode = $node;
                $minNodePosition = $nodePosition;
            }
        }
        if(!is_bool($minNode) && isset($this->grammar[$minNode]) && count($this->grammar[$minNode])>0){            
            $evolutionDigitNeeded = $this->binaryDigitNeeded($minNode);
            while(strlen($gene)<$geneIndex+$evolutionDigitNeeded+1){
                $gene .= $gene;
            }
            $evolutionGene = bindec(substr($gene, $geneIndex, $evolutionDigitNeeded));
            $evolutionRuleIndex = $evolutionGene % count($this->grammar[$minNode]);
            //echo $minNode.' -NeedDigit'.$evolutionDigitNeeded.' -RuleCount'.count($this->grammar[$minNode]).' -EvolutionGene'.$evolutionGene.' -RuleChoosen'.$evolutionRuleIndex.'<br>';
            $expressionEvolved = $this->grammar[$minNode][$evolutionRuleIndex];
            
            $expression1=substr($expression,0,$minNodePosition);
            $expression2=substr($expression,$minNodePosition+strlen($minNode));
            
            $expression = $expression1.$expressionEvolved.$expression2;
            $geneIndex += $evolutionDigitNeeded;
            
            //recursion part
            $expression = $this->fetchRule($expression, $gene, $geneIndex);
        }
        return $expression;
                
    }
    
    //TODO : make it private
    public function makePhenotype($gene){
        //maximum feature count
        $maxFeatureCount = count($this->dataSet);
        
        //get the first segment of the gene. Used to determine feature count
        $featureDigitNeeded = $this->binaryDigitNeeded($this->startExpression);
        $featureGene = bindec(substr($gene, 0, $featureDigitNeeded));
        $featureCount = $featureGene % $maxFeatureCount+1;
        
        //the first gene index used for evolution
        $geneIndex = $featureDigitNeeded; 
        
        for($i=0; $i<$featureCount; $i++){            
            do{
                $forbidden = false;
                $result['feature'][$i]=$this->fetchRule($this->startExpression, $gene, $geneIndex);
                
                //HEURISTIC_1 : a-a, b-b, a/a should not be there
                
                //HEURISTIC_2 : there should not be 2 exactly identic features
                for($j=0; $j<$i; $j++){
                    if($result['feature'][$i]==$result['feature'][$j]){
                        $forbidden = true;
                        break;
                    }
                }
                
            }while($forbidden);            
        }
        $result['constant'] = $this->getConstants($result['feature']);
        return $result;
    }
    
    protected function calculateFitness($gene){
        if(isset($this->ga_alreadyCalculatedGenes[$gene])){
            return $this->ga_alreadyCalculatedGenes[$gene];
        }else{            
            //TODO : make the real code
            $this->ga_alreadyCalculatedGenes[$gene] = 1;
            return $this->ga_alreadyCalculatedGenes[$gene];
        }
    }
}

?>
