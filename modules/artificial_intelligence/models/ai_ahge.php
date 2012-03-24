<?php
require_once(APPPATH.'../modules/artificial_intelligence/models/ai_core.php');
require_once(APPPATH.'../modules/artificial_intelligence/models/ai_genetics_algorithm.php');

class AI_AHGE extends AI_Genetics_Algorithm{
    private $dataSet;       // (float)dataSet[$i][0] = output, (array)dataSet[$i][1] = input
    private $grammar;       // (object)
    
    public function defineDataSet($dataSet=NULL){
        if(!isset($dataSet)){
            $dataSet = array(
                array(1, array(1,2)), 
                array(4, array(2,2)), 
                array(9, array(3,2))
            );
        }
        $this->dataSet = $dataSet;
    }
    
    public function defineGrammar($grammar=NULL){
        if(!isset($grammar)){
            $grammar = array(
                "<feature>" => array(
                    "<var>",
                    "(<var> <op> <var>)",
                    "(<var> <op> <num>)",
                    "(<num> <op> <var>)",
                    "(<feature> <op> <feature>)",
                    "<uniparam_function>(<feature>)",
                    "<biparam_function>(<feature>, <feature>)"
                ),
                "<op>"  => array(
                    "+","-","*","/","%"
                ),
                "<num>" => array(
                    "<digit>.<digit>",
                    "<digit>"
                ),
                "<digit>" => array(
                    "<digit><digit>",
                    "0","1","2","3","4","5","6","7","8","9"
                ),
                "<uniparam_function>" => array(
                    "sin","cos","tan"
                ),
                "<biparam_function>" => array(
                    "power"
                )
                
            );
        }
        $this->grammar = $grammar;
    }
    
    public function set($individuCount = 100, $maxLoop = 1000, $minFitness = 1000,
            $mutationRate = 0.3, $crossoverRate = 0.4, $reproductionRate = 0.3, $elitismRate = 0.2)
    {        
        if(!isset($this->grammar)) $this->defineGrammar(NULL);
        if(!isset($this->dataSet)) $this->defineDataSet(NULL);
        
        $chromosomeLength = 100;
        parent::set($individuCount, $chromosomeLength, $maxLoop, $minFitness, $mutationRate, $crossoverRate, $reproductionRate, $elitismRate);       
    }
    
    
    
    private function getConstants($features){
        
    }
    
    private function binaryDigitNeeded($node){
        $ruleCount = count($this->grammar[$node]);
        $digitNeeded = ceil(log($ruleCount,2));
        return $digitNeeded;
    }
    
    private function fetchRule($expression, $gene, &$geneIndex=0){
        $minNode = false;
        $minNodePosition = false;
        foreach($grammar as $node=>$evolutions){
            $nodePosition = strpos($expression, $node);
            if($nodePosition && ($minNode==false || $minNode>$nodePosition)){
                $minNode = $node;
                $minNodePosition = $nodePosition;
            }
        }
        if($minNode !== false){
            $evolutionDigitNeeded = $this->binaryDigitNeeded($node);
            $evolutionGene = bindec(substr($gene, $geneIndex, $evolutionDigitNeeded));
            $evolutionRuleIndex = $evolutionGene % count($this->grammar[$node]);
            
            $expressionEvolved = $this->grammar[$minNode][$evolutionRuleIndex];
            
            $expression1=substr($expression,0,$minNodePosition);
            $expression2=substr($expression,$minNodePosition+strlen($minNode));
            
            $expression = $expression1.$expressionEvolved.$expression2;
            $geneIndex += $evolutionDigitNeeded;
            
            //recursion part
            $this->fetchRule($expression, $gene, $geneIndex);
        }
        return $expression;
                
    }
    
    //TODO : make it private
    public function makePhenotype($gene){
        //maximum feature count
        $maxFeatureCount = count($this->dataSet);
        
        //get the first segment of the gene. Used to determine feature count
        $featureDigitNeeded = $this->binaryDigitNeeded('<feature>');
        $featureGene = bindec(substr($gene, 0, $featureDigitNeeded));
        $featureCount = $featureGene % $maxFeatureCount;
        
        //the first gene index used for evolution
        $geneIndex = $featureDigitNeeded; 
        for($i=0; $i<$featureCount; $i++){
            $result['constant'][$i]=0;
            $result['feature'][$i]=$this->fetchRule($expression, $gene, $geneIndex);
        }
        $result['constant'][$featureLength] = 0;
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
