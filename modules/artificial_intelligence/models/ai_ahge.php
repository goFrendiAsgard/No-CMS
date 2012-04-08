<?php
require_once(APPPATH.'../modules/artificial_intelligence/models/ai_core.php');
require_once(APPPATH.'../modules/artificial_intelligence/models/ai_genetics_algorithm.php');

function nonzero($number){
    if($number==0)$number=1;
    return $number;
}

class AI_AHGE extends AI_Genetics_Algorithm{
    private $dataSet;       // (float)dataSet[$i][0] = output, (array)dataSet[$i][1] = input
    private $grammar;       // (object)
    private $maxFeature = 3;
    private $startExpression        = "[feature]";
    private $varExpression          = "[var]";
    private $numExpression          = '[num]';
    private $constantExpression     = '[constant]';
    
            
    public function defineMaxFeature($maxFeature=2){
        $this->maxFeature = $maxFeature;
    }
    
    public function defineDataSet($dataSet=NULL){
        if(!isset($dataSet)){
            /**pangkat2
            $dataSet = array(
                array( 1, array(1, 2)), 
                array( 4, array(2, 3)), 
                array( 9, array(3, 4)),
                array(16, array(4, 5)),
                array(25, array(5, 6)), 
                array(36, array(6, 7)), 
                array(49, array(7, 8)),
                array(64, array(8, 9))
            );
             * 
             */
            
            /**pangkat3*/
            $dataSet = array(
                array(1,array(1)),  
                array(8,array(2)),
                array(27,array(3)),  
                array(64,array(4)),  
            );
            
            /** case 1
            $dataSet = array(
                array(29.750, array(1.0)),  
                array(19.125, array(1.5)),
                array(14.375, array(2.0)),
                array( 9.500, array(3.0)),   
                array( 7.125, array(4.0)),   
                array( 5.625, array(5.0)) 
            );
             * 
             */
            
            /** case 17
            $dataSet = array(
                array(26.76, array(27)),
                array(27.28, array(40)),
                array(26.89, array(50)),
                array(26.79, array(30))
            );
             * 
             */
            
        }
        $this->dataSet = $dataSet;
    }
    
    public function defineGrammar($grammar=NULL){
        if(!isset($grammar)){
            $grammar = array(
                $this->startExpression => array(
                    "$this->varExpression"=>0.5,
                    "($this->varExpression [op] $this->varExpression)"=>0.1,
                    "($this->varExpression [op] $this->numExpression)"=>0.1,
                    "($this->numExpression [op] $this->varExpression)"=>0.1,
                    "($this->startExpression [op] $this->startExpression)"=>0.1,
                    "($this->varExpression / nonzero($this->varExpression))"=>0.1,
                    "($this->varExpression / nonzero($this->numExpression))"=>0.1,
                    "($this->numExpression / nonzero($this->varExpression))"=>0.1,
                    "($this->startExpression / nonzero($this->startExpression))"=>0.1,
                    "[uniparam_function]($this->startExpression)"=>0,
                    "[biparam_function]($this->startExpression, $this->startExpression)"=>0
                ),
                "[op]"  => array(
                    "+"=>0.25,
                    "-"=>0.25,
                    "*"=>0.25
                ),
                $this->numExpression => array(
                    "[digit].[digit]"=>0.1,
                    "[digit]"=>0.9
                ),
                "[digit]" => array(
                    "[digit][digit]"=>0.05,
                    "0"=>0.05,
                    "1"=>0.1,
                    "2"=>0.1,
                    "3"=>0.1,
                    "4"=>0.1,
                    "5"=>0.1,
                    "6"=>0.1,
                    "7"=>0.1,
                    "8"=>0.1,
                    "9"=>0.1
                ),                
                "[uniparam_function]" => array(
                    "sin"=>0.33,
                    "cos"=>0.33,
                    "tan"=>0.33
                ),
                "[biparam_function]" => array(
                    "pow"=>1
                ),
                $this->varExpression => array() //generated automatically by system                
            );
        }
        $this->grammar = $grammar;
    }
    
    public function set($individuCount = 100, $maxLoop = 1000, $minFitness = 1000,
            $mutationRate = 0.3, $crossoverRate = 0.4, $reproductionRate = 0.3, $elitismRate = 0.2)
    {        
        if(!isset($this->maxFeature)) $this->defineMaxFeature();
        if(!isset($this->grammar)) $this->defineGrammar(NULL);
        if(!isset($this->dataSet)) $this->defineDataSet(NULL);        
       
        $this->grammar[$this->varExpression]=array();
        
        for($i=0; $i<count($this->dataSet[0][1]); $i++){
            //TODO : choose variable with the biggest chance (closest standard deviation with output)
            $this->grammar[$this->varExpression]['$input['.$i.']'] = 0.1; 
        }
        
        $chromosomeLength = 100;
        parent::set($individuCount, $chromosomeLength, $maxLoop, $minFitness, $mutationRate, $crossoverRate, $reproductionRate, $elitismRate);       
    }
    
    private function getGeneSegmentValue($gene,$index){
        //7 means we will take 7 bit (0-128)
        while(($index+1)*7>strlen($gene)){
            $gene.=$gene;
        }
        return bindec(substr($gene,$index*7,7));
    }      
    
    private function evolution($phenotype, &$grammar, $gene, &$index){
        $evolutionNeeded = FALSE;
        $evolutionNode = '';
        $evolutionPosition = -1;
        foreach(array_keys($this->grammar) as $node){
            $nodePosition = strpos($phenotype, $node);
            if(!is_bool($nodePosition) && ($evolutionPosition==-1 || $evolutionPosition>$nodePosition)){
                $evolutionNode = $node;
                $evolutionPosition = $nodePosition;
                $evolutionNeeded = TRUE;
            }
        }
        if(!$evolutionNeeded){
            return $phenotype;
        }else{
            //number for rule choosing
            $ratio = $this->getGeneSegmentValue($gene,$index)/127;
            $index++;
            
            $evolutionSelected='';
            $totalRatio=0;
            foreach(array_keys($this->grammar[$evolutionNode]) as $evolutionOption){
                $totalRatio += $this->grammar[$evolutionNode][$evolutionOption];
            }
            $currentRatio=0;
            foreach(array_keys($this->grammar[$evolutionNode]) as $evolutionOption){
                $evolutionSelected=$evolutionOption;
                $currentRatio += $this->grammar[$evolutionNode][$evolutionOption];
                if($currentRatio/$totalRatio >= $ratio){
                    break;
                }
            }
            
            $phenotype = 
                substr($phenotype, 0, $evolutionPosition).
                $evolutionSelected.
                substr($phenotype, $evolutionPosition+strlen($evolutionNode), strlen($phenotype)-($evolutionPosition+strlen($evolutionNode)));
            if($index<100){
                $phenotype = $this->evolution($phenotype, $grammar, $gene, $index);
            }
            return $phenotype;            
        }
    }
    
    public function fillConstantExpression($phenotype, &$grammar, $gene, &$index){
        $duplicateFeatureIndex = array();
        //extract feature expression
        $featureExpression = explode(' + '.$this->constantExpression.'*',$phenotype);
        $featureCount = count($featureExpression);
        for($i=0; $i<$featureCount; $i++){
            $featureExpression[$i] = str_replace($this->constantExpression.'*', '', $featureExpression[$i]);
            for($j=0; $j<$i; $j++){
                if($featureExpression[$i] == $featureExpression[$j]){
                    $duplicateFeatureIndex[]=$i;
                    break;
                }
            }
        }
        $duplicateFeatureCount = count($duplicateFeatureIndex);
        
        /*
        echo 'Feature Expression : ';
        echo '<pre>';
        echo var_dump($featureExpression);
        echo '</pre>';
        echo 'Bad Feature Index : ';
        echo '<pre>';
        echo var_dump($duplicateFeatureIndex);
        echo '</pre>';
         * 
         */
        
        $feature = array();
        $output = array();
        
        //feature (level 0)
        $feature[0] = array();
        $output[0] = array();
        for($i=0; $i<$featureCount; $i++){
            $feature[0][$i] = array();
            $input = $this->dataSet[$i][1];
            for($j=0; $j<$featureCount; $j++){
                eval('$feature[0][$i][$j] = '.$featureExpression[$j].';');
            }
            $output[0][$i] = $this->dataSet[$i][0];
        }
        
        
        //feature next level
        for($level=0; $level<$featureCount-1; $level++){
            $nextLevel = $level+1;
            $eliminated = $level;
            $feature[$nextLevel]=array();
            $output[$nextLevel]=array();
            
            $isDuplicatedFeature = FALSE;
            for($i=0; $i<count($duplicateFeatureIndex); $i++){
                if($duplicateFeatureIndex[$i]==$eliminated){
                    $isDuplicatedFeature = TRUE;
                    break;
                }
            }
            
            for($i=0; $i<count($feature[$level])-1; $i++){
                $factor1=$feature[$level][0][$eliminated];
                $factor2=$feature[$level][$i+1][$eliminated];
                for($j=0; $j<$featureCount; $j++){
                    if($isDuplicatedFeature){ //elimination has been performed to the previous exactly same feature
                        $feature[$nextLevel][$i][$j] = 
                            $feature[$level][$i+1][$j];
                    }else{
                        $feature[$nextLevel][$i][$j] = 
                            $factor2 * $feature[$level][0][$j] -
                            $factor1 * $feature[$level][$i+1][$j];
                    }
                }
                if($isDuplicatedFeature){ //elimination has been performed to the previous exactly same feature
                    $output[$nextLevel][$i] = 
                        $output[$level][$i+1];
                }else{
                    $output[$nextLevel][$i] = 
                        $factor2 * $output[$level][0] -
                        $factor1 * $output[$level][$i+1];
                }
            }
        }
        
        /*
        echo 'Feature : ';
        echo '<pre>';
        echo var_dump($feature);
        echo '</pre>';
        echo 'Output : ';
        echo '<pre>';
        echo var_dump($output);
        echo '</pre>';
         * 
         */
        
        $eliminationSuccess = TRUE;
        $constant=array();
        for($level=$featureCount-1; $level>=0; $level--){
            if($feature[$level][0][$level]==0){
                $constant[$level]=0;
            }else{
                $constant[$level] = $output[$level][0];
                for($i=$featureCount-1; $i>$level; $i--){
                    $constant[$level] -= $constant[$i]*$feature[$level][0][$i];
                }
                $constant[$level] /= $feature[$level][0][$level];
            }
        }
        /*
        echo 'Constant : ';
        echo '<pre>';
        echo var_dump($constant);
        echo '</pre>';
         * 
         */
        
        //adjust constant <-- TODO : this should be the mighty elimination algorithm
        if($eliminationSuccess){
            for($i=0; $i<$featureCount; $i++){
                $nodePosition = strpos($phenotype, $this->constantExpression);
                $phenotype = 
                    substr($phenotype, 0, $nodePosition).
                    $constant[$i].
                    substr($phenotype, $nodePosition+strlen($this->constantExpression), strlen($phenotype)-($nodePosition+strlen($this->constantExpression)));
                
            }
        }
        else{
            $phenotype = str_replace($this->constantExpression, $this->numExpression, $phenotype);
            $phenotype = $this->evolution($phenotype, $this->grammar, $gene, $index);            
        }
        return $phenotype;        
    }
    
    private function makePhenotype($gene){
        $grammar = $this->grammar;
        $index=0;
        $featureCount=$this->getGeneSegmentValue($gene, $index)%$this->maxFeature+1;
        //the very start expression
        $phenotype = '';
        for($i=0; $i<$featureCount; $i++){
            $phenotype .= $this->constantExpression.'*('.$this->startExpression.') + ';
        }
        $phenotype .= $this->constantExpression.'*1';
        
        //evolution
        $index++;
        $phenotype = $this->evolution($phenotype, $grammar, $gene, $index);
        
        //constants
        $phenotype = $this->fillConstantExpression($phenotype, $grammar, $gene, $index);
        
        return $phenotype;
        
    }
    
    //TODO : make it protected
    public function calculateFitness($gene){
        if(isset($this->ga_alreadyCalculatedGenes[$gene])){
            return $this->ga_alreadyCalculatedGenes[$gene];
        }else{            
            //TODO : make the real code
            $phenotype = $this->makePhenotype($gene);
            
            $dataSet = $this->dataSet;
            $MSE = 0;
            $dataSetCount = count($dataSet);
            for($i=0; $i<$dataSetCount; $i++){                
                
                $desiredOutput = $dataSet[$i][0];
                
                $input = $this->dataSet[$i][1];
                $output = 0;
                eval('$output='.$phenotype.';');
                
                $MSE += pow($desiredOutput-$output,2);
            }
            $MSE /= $dataSetCount;
            
            //since MSE is 0 or positive, I do this to avoid division by zero
            $this->ga_alreadyCalculatedGenes[$gene] = $phenotype.' <b> fitness : '. 1/($MSE+0.000001).'</b>';             
            return $this->ga_alreadyCalculatedGenes[$gene];
        }
    }
}

?>
