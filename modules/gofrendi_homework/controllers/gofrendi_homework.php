<?php

/**
 * Description of artificial_intelligence
 *
 * @author gofrendi
 */
class gofrendi_homework extends CMS_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('artificial_intelligence/ai_nnga');
        $this->ai_nnga->core_initialize('Default'); 
    }
    public function index(){
        $this->view('homework_index', NULL, 'gofrendi_homework_index');
    }
    public function data_iris(){
        $this->view('homework_iris_data', NULL, 'gofrendi_homework_data_iris');
    }
    public function train_iris(){
        $this->load->model('gofrendi_homework/iris_data');
        
        $dataset = $this->iris_data->get(); 
        $neuronCount = array(count($dataset[0][0]), 2, count($dataSet[0][1]));
        $nn_learning_rate = 0.1;
        $nn_max_mse = 0.01;
        $nn_max_loop = 1000;
        $ga_individu_count = 150;
        $ga_max_loop = 200;
        $ga_mutation_rate = 0.8;
        $ga_crossover_rate = 0.2;
        $ga_reproduction_rate = 0;
        $ga_elitism_rate = 0.15;
        
        $this->ai_nnga->core_initialize('iris');
        $this->ai_nnga->set(
                $dataset, 
                $neuronCount, 
                $nn_learning_rate, 
                $nn_max_mse, 
                $nn_max_loop,
                $ga_individu_count, 
                $ga_max_loop, 
                1/(0.000001+$nn_max_mse), 
                $ga_mutation_rate, 
                $ga_crossover_rate, 
                $ga_reproduction_rate, 
                $ga_elitism_rate
        ); 
        redirect('artificial_intelligence/nnga/set/iris');
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
        $neuronCount = array(count($dataset[0][0]), 0, count($dataSet[0][1]));
        $nn_learning_rate = 0.1;
        $nn_max_mse = 0.01;
        $nn_max_loop = 1000;
        $ga_individu_count = 150;
        $ga_max_loop = 200;
        $ga_mutation_rate = 0.8;
        $ga_crossover_rate = 0.2;
        $ga_reproduction_rate = 0;
        $ga_elitism_rate = 0.15;
        
        $this->ai_nnga->core_initialize('and');
        $this->ai_nnga->set(
                $dataset, 
                $neuronCount, 
                $nn_learning_rate, 
                $nn_max_mse, 
                $nn_max_loop,
                $ga_individu_count, 
                $ga_max_loop, 
                1/(0.000001+$nn_max_mse), 
                $ga_mutation_rate, 
                $ga_crossover_rate, 
                $ga_reproduction_rate, 
                $ga_elitism_rate
        ); 
        redirect('artificial_intelligence/nnga/set/and');
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
        $neuronCount = array(count($dataset[0][0]), 0, count($dataSet[0][1]));
        $nn_learning_rate = 0.1;
        $nn_max_mse = 0.01;
        $nn_max_loop = 1000;
        $ga_individu_count = 150;
        $ga_max_loop = 200;
        $ga_mutation_rate = 0.8;
        $ga_crossover_rate = 0.2;
        $ga_reproduction_rate = 0;
        $ga_elitism_rate = 0.15;
        
        $this->ai_nnga->core_initialize('or');
        $this->ai_nnga->set(
                $dataset, 
                $neuronCount, 
                $nn_learning_rate, 
                $nn_max_mse, 
                $nn_max_loop,
                $ga_individu_count, 
                $ga_max_loop, 
                1/(0.000001+$nn_max_mse), 
                $ga_mutation_rate, 
                $ga_crossover_rate, 
                $ga_reproduction_rate, 
                $ga_elitism_rate
        ); 
        redirect('artificial_intelligence/nnga/set/or');
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
        $neuronCount = array(count($dataset[0][0]), 2, count($dataSet[0][1]));
        $nn_learning_rate = 0.1;
        $nn_max_mse = 0.01;
        $nn_max_loop = 1000;
        $ga_individu_count = 150;
        $ga_max_loop = 200;
        $ga_mutation_rate = 0.8;
        $ga_crossover_rate = 0.2;
        $ga_reproduction_rate = 0;
        $ga_elitism_rate = 0.15;
        
        $this->ai_nnga->core_initialize('xor');
        $this->ai_nnga->set(
                $dataset, 
                $neuronCount, 
                $nn_learning_rate, 
                $nn_max_mse, 
                $nn_max_loop,
                $ga_individu_count, 
                $ga_max_loop, 
                1/(0.000001+$nn_max_mse), 
                $ga_mutation_rate, 
                $ga_crossover_rate, 
                $ga_reproduction_rate, 
                $ga_elitism_rate
        ); 
        redirect('artificial_intelligence/nnga/set/xor');
    }
}

?>
