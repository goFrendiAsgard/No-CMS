<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CMS_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model($this->cms_module_path().'/cck_model');
    }

    public function default_per_record_html_pattern($id_entity=NULL){
        echo $this->cck_model->get_default_per_record_html_pattern($id_entity);
    }

    public function input_pattern_by_template($id_template=NULL){
        echo $this->cck_model->get_input_pattern_by_template($id_template);
    }

    public function view_pattern_by_template($id_template=NULL){
        echo $this->cck_model->get_view_pattern_by_template($id_template);
    }

    // This is merely here for testing purpose
    public function test(){
        $entity_list = $this->cms_get_record_list($this->t('entity'));
        foreach($entity_list as $entity){
            $id_entity = $entity->id;
            $field_list = $this->cms_get_record_list($this->t('field'), 'id_entity', $id_entity);
            $record_list = $this->cms_get_record_list($this->t('data_'.$id_entity));
            foreach($record_list as $record){
                echo PHP_EOL.'<br />'.PHP_EOL;
                echo $this->cck_model->get_actual_per_record_view($id_entity, $record);
                foreach($field_list as $field){
                    $id_field = $field->id;
                    $value = $record->{'field_'.$id_field};
                    echo PHP_EOL.'<br />'.PHP_EOL;
                    echo $this->cck_model->get_actual_field_view($id_field, $value);
                    echo PHP_EOL.'<br />'.PHP_EOL;
                    echo $this->cck_model->get_actual_field_input($id_field, $value);
                }
            }
        }
    }

}
