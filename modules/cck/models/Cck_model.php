<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Grocerycrud_entity_model
 *
 * @author No-CMS Module Generator
 */
class Cck_model  extends CMS_Model{

    /**
     * Template:
     * {{ code }}
     * {{ name }}
     * {{ value }}
     * {{ foreach_option }}
     * {{ end_foreach }}
     * {{ option.value }}
     * {{ option.caption }}
     * {{ selected.caption }}
     * {{ selected.value }}
     * {{ field_name.view }}
     */

    public function __construct(){
        parent::__construct();
    }

    public function get_default_per_record_html_pattern($id_entity){
        $entity = $this->cms_get_record($this->t('entity'), 'id', $id_entity);
        if($entity != NULL && $entity->per_record_html != NULL){
            return '';
        }else{
            $field_list = $this->cms_get_record_list($this->t('field'), 'id_entity', $entity->id);
            $html = '<div class="row">'.PHP_EOL;
            foreach($field_list as $field){
                $html .= '    <div class="col-md-4"><strong>'.$field->name.'</strong></div>'.PHP_EOL;
                $html .= '    <div class="col-md-8">{{ '.$field->name.'.view }}</div>'.PHP_EOL;
            }
            $html .= '</div>';
            return $html;
        }

    }

    public function get_per_record_html_pattern($id_entity){
        $entity = $this->cms_get_record($this->t('entity'), 'id', $id_entity);
        if($entity != NULL && $entitiy->per_record_html != NULL){
            return $entity->per_record_html;
        }else{
            return $this->get_default_per_record_html_pattern($id_entity);
        }

    }


    public function get_actual_per_record_view($id_entity, $record){

    }

    public function get_input_pattern_by_template($id_template){
        $template = $this->cms_get_record($this->t('template'), 'id', $id_template);
        return $template == NULL || $template->input == NULL? '<input id="field-{name}" name="{name}" value="{value}" />' : $template->input;
    }

    public function get_view_pattern_by_template($id_template){
        $template = $this->cms_get_record($this->t('template'), 'id', $id_template);
        return $template == NULL || $template->view == NULL? '{value}' : $template->view;
    }

    public function get_actual_input($input_pattern, $value, $option_list){

    }

    public function get_actual_view($view_pattern, $value, $option_list){

    }

    public function get_actual_input_by_template($id_template, $value=NULL, $option_list){
        $pattern = $this->get_input_pattern_by_template($id_template);
        return $this->get_actual_input($pattern, $value, $option_list);
    }

    public function get_actual_view_by_template($id_template, $value=NULL, $option_list){
        $pattern = $this->get_view_pattern_template($id_template);
        return $this->get_actual_view($pattern, $value, $option_list);
    }



    public function get_field_view($id_field, $value){

    }

    public function get_field_input($id_field, $value=NULL){

    }
}
