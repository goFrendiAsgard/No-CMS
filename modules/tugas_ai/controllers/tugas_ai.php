<?php

/**
 * Description of artificial_intelligence
 *
 * @author gofrendi
 */
class tugas_ai extends CMS_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('artificial_intelligence/ai_nnga');
        $this->ai_nnga->core_initialize('Default'); 
    }
    public function index(){
        $this->view('tugas_ai_index', NULL, 'tugas_ai_index');
    }
    public function data_iris(){
        $this->view('tugas_ai_data_iris', NULL, 'tugas_ai_data_iris');
    }
    public function train_iris(){
        $this->load->model('tugas_ai/iris_data');
        $dataset = $this->iris_data->get();
        $this->ai_nnga->set($dataset,array(count($dataset[0][0]),3,count($dataset[0][1])), 0.1, 0.01, 1000);
        redirect('artificial_intelligence/nnga/set');
    }
    public function train_and(){
        $dataset = array(
             array(
                 array(0,0),
                 array(0)
             ),
             array(
                 array(0,1),
                 array(0)
             ),
             array(
                 array(1,0),
                 array(0)
             ),
             array(
                 array(1,1),
                 array(1)
             ),
        );
        $this->ai_nnga->set($dataset,array(count($dataset[0][0]),3,count($dataset[0][1])), 0.1, 0.01, 1000);
        redirect('artificial_intelligence/nnga/set');
    }
    public function train_or(){
        $dataset = array(
             array(
                 array(0,0),
                 array(0)
             ),
             array(
                 array(0,1),
                 array(1)
             ),
             array(
                 array(1,0),
                 array(1)
             ),
             array(
                 array(1,1),
                 array(1)
             ),
        );
        $this->ai_nnga->set($dataset,array(count($dataset[0][0]),3,count($dataset[0][1])), 0.1, 0.01, 1000);
        redirect('artificial_intelligence/nnga/set');
    }
    public function train_xor(){
        $dataset = array(
             array(
                 array(0,0),
                 array(0)
             ),
             array(
                 array(0,1),
                 array(1)
             ),
             array(
                 array(1,0),
                 array(1)
             ),
             array(
                 array(1,1),
                 array(0)
             ),
        );
        $this->ai_nnga->set($dataset,array(count($dataset[0][0]),3,count($dataset[0][1])), 0.1, 0.01, 1000);
        redirect('artificial_intelligence/nnga/set');
    }
}

?>
