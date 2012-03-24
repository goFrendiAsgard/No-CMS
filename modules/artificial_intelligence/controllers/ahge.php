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
        
        $dummy_gene='101010101010101010111111101010111110100010101010101010101011001011010001011111111111111001010101010001001101000100110010001000111111000000000111111111101010101010101010101010101010101010101111111111100000000000101010101010';
        echo '<pre>';
        echo var_dump($this->ai_ahge->makePhenotype($dummy_gene));
        echo '</pre>';
    }
}

?>
