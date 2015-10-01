<?php

/**
 * Description of nnga
 *
 * @author gofrendi
 */
class Nnga extends CMS_Controller {

    //put your code here
    public function __construct(){
        parent::__construct();
    }

    private function initialize($identifier=NULL){
        $this->load->model($this->cms_module_path().'/ai_nnga');
        $identifier = isset($identifier)?$identifier:'NNGA_Default';
        $this->ai_nnga->core_initialize($identifier);
    }

    public function index($identifier=NULL){
    	$this->cms_guard_page('ai_nnga_index');
        $this->initialize($identifier);
        $this->view($this->cms_module_path().'/nnga_index', NULL, 'ai_nnga_index');
    }

    public function monitor($identifier=NULL){
        $this->initialize($identifier);
        if(!$this->ai_nnga->core_exists()){
            redirect($this->cms_module_path().'/nnga/set'.$identifier,'refresh');
        }else{
        	$this->cms_guard_page('ai_nnga_monitor');
            $data = array("identifier"=>$identifier);
            $this->view($this->cms_module_path().'/nnga_monitor', $data, 'ai_nnga_monitor');
        }
    }

    public function set($identifier=NULL){
        $this->initialize($identifier);

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
        $this->form_validation->set_rules('nn_hidden_neuron_count', 'Hidden Neuron Count');
        $this->form_validation->set_rules('nn_learning_rate', 'Learning Rate', 'required');
        $this->form_validation->set_rules('nn_max_loop', 'NN Max Loop', 'required');
        $this->form_validation->set_rules('nn_max_mse', 'Max MSE', 'required');
        $this->form_validation->set_rules('nn_dataset', 'DataSet', 'required');
        $this->form_validation->set_rules('ga_individu_count', 'Individu Count', 'required');
        $this->form_validation->set_rules('ga_max_loop', 'GA Max Loop', 'required');
        $this->form_validation->set_rules('ga_mutation_rate', 'GA Mutation Rate', 'required');
        $this->form_validation->set_rules('ga_crossover_rate', 'GA Crossover Rate', 'required');
        $this->form_validation->set_rules('ga_reproduction_rate', 'GA Reproduction Rate', 'required');
        $this->form_validation->set_rules('ga_elitism_rate', 'GA Elitism Rate', 'required');

        if($this->form_validation->run()){
            $nn_hidden_neuron_count = json_decode('['.$nn_hidden_neuron_count.']');

            $this->session->set_userdata('nn_dataset', $nn_dataset);
            $nn_dataset = json_decode('['.$nn_dataset.']');


            $neuronCount = array();
            $neuronCount[] = count($nn_dataset[0][0]);
            for($i=0; $i<count($nn_hidden_neuron_count); $i++){
            	if($nn_hidden_neuron_count[$i]>0){
                	$neuronCount[] = $nn_hidden_neuron_count[$i];
            	}
            }
            $neuronCount[] = count($nn_dataset[0][1]);

            $this->ai_nnga->set($nn_dataset, $neuronCount, $nn_learning_rate, $nn_max_mse, $nn_max_loop,
                    $ga_individu_count, $ga_max_loop, 1/(0.000001+$nn_max_mse), $ga_mutation_rate,
                    $ga_crossover_rate, $ga_reproduction_rate, $ga_elitism_rate);
            redirect($this->cms_module_path().'/nnga/monitor/'.$identifier,'refresh');

        }else{
        	$this->cms_guard_page('ai_nnga_set');
            $state = $this->ai_nnga->currentState();
            $nnState = $state["nn"];
            $gaState = $state["ga"];
            if(!isset($nnState)){
                if(!$nn_hidden_neuron_count) $nn_hidden_neuron_count = '2';
                if(!$nn_learning_rate) $nn_learning_rate = 0.1;
                if(!$nn_max_loop) $nn_max_loop = 1000;
                if(!$nn_max_mse) $nn_max_mse = 0.01;
                if(!$nn_dataset) $nn_dataset = '[[0,0],[0]], [[0,1],[0]], [[1,0],[0]], [[1,1],[1]]';
            }else{
                if(!$nn_hidden_neuron_count){
                    $nn_hidden_neuron_count = '';
                    for($i=1; $i<count($nnState["nn_neuronCount"])-1; $i++){
                        $nn_hidden_neuron_count .= $nnState["nn_neuronCount"][$i];
                        if($i<count($nnState["nn_neuronCount"])-2){
                            $nn_hidden_neuron_count .= ', ';
                        }
                    }
                }
                if(!$nn_learning_rate) $nn_learning_rate = $nnState["nn_learningRate"];
                if(!$nn_max_loop) $nn_max_loop = $nnState["nn_maxLoop"];
                if(!$nn_max_mse) $nn_max_mse = $nnState["nn_maxMSE"];
                if(!$nn_dataset){
                    $nn_dataset = "";
                    for($i=0; $i<count($nnState["nn_dataset"]); $i++){
                        $nn_dataset .= "[";
                        $nn_dataset .= json_encode($nnState["nn_dataset"][$i]["input"]);
                        $nn_dataset .= ", ";
                        $nn_dataset .= json_encode($nnState["nn_dataset"][$i]["target"]);
                        $nn_dataset .= "]";
                        if($i<count($nnState["nn_dataset"])-1){
                            $nn_dataset .= ", ";
                        }
                    }
                }
            }
            if(!isset($gaState)){
                if(!$ga_max_loop) $ga_max_loop = 50;
                if(!$ga_individu_count) $ga_individu_count = 50;
                if(!$ga_mutation_rate) $ga_mutation_rate = 0.3;
                if(!$ga_crossover_rate) $ga_crossover_rate = 0.6;
                if(!$ga_reproduction_rate) $ga_reproduction_rate = 0.1;
                if(!$ga_elitism_rate) $ga_elitism_rate = 0.1;
            }else{
                if(!$ga_max_loop) $ga_max_loop = $gaState["ga_maxLoop"];
                if(!$ga_individu_count) $ga_individu_count = $gaState["ga_individuCount"];
                if(!$ga_mutation_rate) $ga_mutation_rate = $gaState["ga_mutationRate"];
                if(!$ga_crossover_rate) $ga_crossover_rate = $gaState["ga_crossoverRate"];
                if(!$ga_reproduction_rate) $ga_reproduction_rate = $gaState["ga_reproductionRate"];
                if(!$ga_elitism_rate) $ga_elitism_rate = $gaState["ga_elitismRate"];
            }
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
                "ga_elitism_rate"=>$ga_elitism_rate,
                "identifier"=>$identifier
                );
            $this->view($this->cms_module_path().'/nnga_set',$data, 'ai_nnga_set');
        }

    }

    public function trainNN($identifier=NULL){
        $this->initialize($identifier);

        $nn_dataset = $this->session->userdata('nn_dataset');
        $nn_dataset = json_decode('['.$nn_dataset.']');
        $this->ai_nnga->train($nn_dataset, FALSE);
    }

    public function trainNNGA($identifier=NULL){
        $this->initialize($identifier);

        $nn_dataset = $this->session->userdata('nn_dataset');
        $nn_dataset = json_decode('['.$nn_dataset.']');
        $this->ai_nnga->train($nn_dataset, TRUE);
    }

    public function currentState($identifier=NULL){
        $this->initialize($identifier);

        $result = $this->ai_nnga->currentState();
        $result['loop'] = $this->input->post('loop');
        unset($result['ga_alreadyCalculatedGenes']);
        $this->cms_show_json($result);
    }

    public function state($identifier=NULL){
        $this->initialize($identifier);

        $result = $this->ai_nnga->currentState();
        $this->cms_show_variable($result);
    }

}
