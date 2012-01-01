<?php

/**
 * Description of nnga
 *
 * @author gofrendi
 */
class nnga extends CMS_Controller {
    //put your code here
    public function __construct(){
        parent::__construct();
         $this->load->model('artificial_intelligence/ai_nnga');
         $this->ai_nnga->core_initialize('Default');
    }
    
    public function index(){
        $this->view('artificial_intelligence/nnga_index', NULL, 'ai_nnga_index');
    }
    
    public function monitor(){
        $this->view('artificial_intelligence/nnga_monitor', NULL, 'ai_nnga_monitor');
    }
    
    public function set(){
        $nn_hidden_neuron_count = $this->input->post('nn_hidden_neuron_count');
        $nn_learning_rate = $this->input->post('nn_learning_rate');
        $nn_max_loop = $this->input->post('nn_max_loop');
        $nn_max_mse = $this->input->post('nn_max_mse');
        $nn_dataset = $this->input->post('nn_dataset');
        $ga_individu_count = $this->input->post('ga_individu_count');
        $ga_max_loop = $this->input->post('ga_max_loop');
        $ga_mutation_rate = $this->input->post('ga_mutation_rate');
        $ga_crossover_rate = $this->input->post('ga_crossover_rate');
        $ga_reproduction_rate = $this->input->post('ga_reproduction_rate');
        $ga_elitism_rate = $this->input->post('ga_elitism_rate');
        
        //set validation rule
        $this->form_validation->set_rules('nn_hidden_neuron_count', 'Hidden Neuron Count', 'required|xss_clean');
        $this->form_validation->set_rules('nn_learning_rate', 'Learning Rate', 'required|xss_clean');
        $this->form_validation->set_rules('nn_max_loop', 'NN Max Loop', 'required|xss_clean');
        $this->form_validation->set_rules('nn_max_mse', 'Max MSE', 'required|xss_clean');
        $this->form_validation->set_rules('nn_dataset', 'DataSet', 'required|xss_clean');
        $this->form_validation->set_rules('ga_individu_count', 'Individu Count', 'required|xss_clean');
        $this->form_validation->set_rules('ga_max_loop', 'GA Max Loop', 'required|xss_clean');
        $this->form_validation->set_rules('ga_mutation_rate', 'GA Mutation Rate', 'required|xss_clean');
        $this->form_validation->set_rules('ga_crossover_rate', 'GA Crossover Rate', 'required|xss_clean');
        $this->form_validation->set_rules('ga_reproduction_rate', 'GA Reproduction Rate', 'required|xss_clean');
        $this->form_validation->set_rules('ga_elitism_rate', 'GA Elitism Rate', 'required|xss_clean');
        
        if($this->form_validation->run()){
            $nn_hidden_neuron_count = json_decode('['.$nn_hidden_neuron_count.']');
            
            $this->session->set_userdata('nn_dataset', $nn_dataset);
            $nn_dataset = json_decode('['.$nn_dataset.']');
            

            $neuronCount = array();
            $neuronCount[] = count($nn_dataset[0][0]);
            for($i=0; $i<count($nn_hidden_neuron_count); $i++){
                $neuronCount[] = $nn_hidden_neuron_count[$i];
            }
            $neuronCount[] = count($nn_dataset[0][1]);

            $this->ai_nnga->set($neuronCount, $nn_learning_rate, $nn_max_mse, $nn_max_loop, 
                    $ga_individu_count, $ga_max_loop, 1/(0.00001+$nn_max_mse), $ga_mutation_rate, 
                    $ga_crossover_rate, $ga_reproduction_rate, $ga_elitism_rate); 
            redirect('artificial_intelligence/nnga/monitor');
            
        }else{
            if(!$nn_hidden_neuron_count) $nn_hidden_neuron_count = '2';
            if(!$nn_learning_rate) $nn_learning_rate = 0.1;
            if(!$nn_max_loop) $nn_max_loop = 1000;
            if(!$nn_max_mse) $nn_max_mse = 0.01;
            if(!$nn_dataset) $nn_dataset = '[[0,0],[0]], [[0,1],[0]], [[1,0],[0]], [[1,1],[1]]';
            if(!$ga_max_loop) $ga_max_loop = 50;
            if(!$ga_individu_count) $ga_individu_count = 50;
            if(!$ga_mutation_rate) $ga_mutation_rate = 0.3;
            if(!$ga_crossover_rate) $ga_crossover_rate = 0.6;
            if(!$ga_reproduction_rate) $ga_reproduction_rate = 0.1;
            if(!$ga_elitism_rate) $ga_elitism_rate = 0.1;
            $data = array(
                "nn_hidden_neuron_count"=>$nn_hidden_neuron_count,
                "nn_learning_rate"=>$nn_learning_rate,
                "nn_max_loop"=>$nn_max_loop,
                "nn_max_mse"=>$nn_max_mse,
                "nn_dataset"=>$nn_dataset,
                "ga_max_loop"=>$ga_max_loop,
                "ga_individu_count"=>$ga_individu_count,
                "ga_mutation_rate"=>$ga_mutation_rate,
                "ga_crossover_rate"=>$ga_crossover_rate,
                "ga_reproduction_rate"=>$ga_reproduction_rate,
                "ga_elitism_rate"=>$ga_elitism_rate
                );
            $this->view('artificial_intelligence/nnga_set',$data, 'ai_nnga_set');
        }
        
    }
    
    public function trainNN(){
        $nn_dataset = $this->session->userdata('nn_dataset');
        $nn_dataset = json_decode('['.$nn_dataset.']');
        $this->ai_nnga->train($nn_dataset, FALSE);
    }
    
    public function trainNNGA(){
        $nn_dataset = $this->session->userdata('nn_dataset');
        $nn_dataset = json_decode('['.$nn_dataset.']');
        $this->ai_nnga->train($nn_dataset, TRUE);
    }
    
    public function currentState(){
        $result = $this->ai_nnga->currentState();
        
        $nn_dataset = $this->session->userdata('nn_dataset');
        $nn_dataset = json_decode('['.$nn_dataset.']');
        $result["dataset"] = array();
        for($i=0; $i<count($nn_dataset); $i++){
            $result["dataset"][$i] = array(
                "input" => $nn_dataset[$i][0],
                "target" => $nn_dataset[$i][1],
                "output" => $this->ai_nnga->out($nn_dataset[$i][0])
            );
        }
        echo json_encode($result);
    }
    
    public function state(){
        $result = $this->ai_nnga->currentState();
        
        $nn_dataset = $this->session->userdata('nn_dataset');
        $nn_dataset = json_decode('['.$nn_dataset.']');
        $result["dataset"] = array();
        for($i=0; $i<count($nn_dataset); $i++){
            $result["dataset"][$i] = array(
                "input" => $nn_dataset[$i][0],
                "target" => $nn_dataset[$i][1],
                "output" => $this->ai_nnga->out($nn_dataset[$i][0])
            );
        }
        
        echo '<pre>';
        echo var_dump($result);
        echo '</pre>';
    }
    
}

?>
