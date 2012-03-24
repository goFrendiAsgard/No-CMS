<?php

/**
 * The code of Advance Heuristic Grammatical Evolution (A new method by gofrendi)
 *
 * @author gofrendi
 */
class ahge extends CMS_Controller{
    //put your code here
    public function __construct(){
        parent::__construct();
    }
    
    private function initialize($identifier=NULL){
        $this->load->model('artificial_intelligence/ai_ahge');
        $identifier = isset($identifier)?$identifier:'ahge_default';
        $this->ai_ahge->core_initialize($identifier);
    }
    
    public function index($identifier=NULL){
        $this->initialize($identifier);
        $this->ai_ahge->set();        
        
        $dummy_gene = array(
            '10111110100010101011111110101011111010001010101111111010',
            '11101010101010111011111110101011111010001010101111011010',
            '10001101010101110111101011010101011111010001010101111011',
            '11011001110110111001111010101011101111101000101010111011',
            '10111111010101111110101011011101110111110100010101011111',
            '10100011110111110101110101011110111110100010101011111010',
        );
        
        for($i=0; $i<count($dummy_gene); $i++){
            $phenotype = $this->ai_ahge->makePhenotype($dummy_gene[$i]);
            
            echo 'Individu #'.$i.' is : <br>';
            $str = '$output = ';
            for($j=0; $j<count($phenotype['feature']); $j++){
                $str .= $phenotype['constant'][$j].' * ('.$phenotype['feature'][$j].') + ';
            }
            $str .= $phenotype['constant'][count($phenotype['feature'])].';';
            echo $str;
            
            echo '<pre>';
            echo var_dump($phenotype);
            echo '</pre>';
        }
    }
}

?>
