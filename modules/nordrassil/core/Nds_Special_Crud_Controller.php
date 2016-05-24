<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nds_Special_CRUD_Controller extends CMS_CRUD_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model($this->cms_module_path().'/nds_model');
        $this->load->model($this->cms_module_path().'/synchronize_model');
    }

    public function _reorder_table($project_id){
        $this->db->order_by('priority');
        $priority = 0;
        foreach($this->cms_get_record_list($this->t('table'), 'project_id', $project_id) as $row){
            if($row->priority != $priority){
                $this->db->update($this->t('table'),
                    array('priority' => $priority),
                    array('table_id' => $row->table_id));
            }
            $priority ++;
        }
    }

    public function _reorder_column($table_id){
        $this->db->order_by('priority');
        $priority = 0;
        foreach($this->cms_get_record_list($this->t('column'), 'table_id', $table_id) as $row){
            if($row->priority != $priority){
                $this->db->update($this->t('column'),
                    array('priority' => $priority),
                    array('column_id' => $row->column_id));
            }
            $priority ++;
        }
    }

    protected function preprocess_url($url){
        if(strlen($url)>0){
            if($url[strlen($url)-1] != '/'){
                $url .= '/';
            }
        }
        return $url;
    }

    protected function get_detail_action($caption, $link, $primary_key, $narrow = FALSE){
        $link = $this->preprocess_url($link);
        $html = '';
        // add new
        $html .= anchor(
                $link.$primary_key.'/add',
                '<i class="glyphicon glyphicon-plus"></i> Add New '.$caption
            );
        // separator
        if($narrow){
            $html .= br();
        }else{
            $html .= '&nbsp; | &nbsp;';
        }
        // manage
        $html .= anchor(
                $link.$primary_key,
                '<i class="glyphicon glyphicon-list"></i> Manage '.$caption
            );
        return $html;
    }

    protected function get_detail_data($model_result, $link, $primary_key, $primary_key_lookup_field='id', $title_lookup_field=NULL){
        $link = $this->preprocess_url($link);
        $arr = array();
        $char_count=0;
        foreach($model_result as $row){
            $caption = '';
            if($title_lookup_field == NULL){
                // get the options
                if(isset($row->options) && is_array($row->options) && count($row->options)>0){
                    $options = implode(' | ', $row->options);
                }else{
                    $options = '';
                }
                // guess what should be appeared :)
                if(isset($row->name) && isset($row->caption)){
                    if(strlen($row->name) > 43){
                        $row->name = substr($row->name, 0, 39).' ...';
                    }
                    if(strlen($row->caption) > 21){
                        $row->caption = substr($row->caption, 0, 17).' ...';
                    }
                    if(strlen($options)>70){
                        $options = substr($options, 0, 66).' ...';
                    }
                    if(isset($row->data_type) && $row->data_type !== NULL && $row->data_type != '' && $row->role != 'detail one to many' && $row->role != 'detail many to many'){
                        if(isset($row->role) && $row->role !== NULL && $row->role != ''){
                            $role_description = '<span class="badge">'.$row->role.'</span>';
                            if($row->role == 'lookup'){
                                $role_description .= br().$row->lookup_table_name.'.'.$row->lookup_column_name;
                            }
                            $caption .= '<b>'.$row->name.'</b>'. ' ('.$row->caption.')<br />'.
                                $row->data_type.'('.$row->data_size.') '. $role_description;
                            $caption .= $options == ''? '' : br().$options;
                        }else{
                            $caption .= '<b>'.$row->name.'</b>'. ' ('.$row->caption.')<br />'.
                                $row->data_type.'('.$row->data_size.')';
                            $caption .= $options == ''? '' : ' | '.$options;
                        }
                    }else if(isset($row->role) && $row->role !== NULL && $row->role != ''){
                        $role_description = '<span class="badge">'.$row->role.'</span>';
                        if($row->role == 'detail one to many'){
                            $role_description .= br().$row->table_name.'.'.$row->table_primary_key.' = '.$row->relation_table_name.'.'.$row->relation_table_column_name;
                        }else if($row->role == 'detail many to many'){
                            $role_description .= br().$row->selection_table_name.'.'.$row->selection_column_name.br().
                                $row->relation_table_name.'.'.$row->relation_selection_column_name.' = '.$row->selection_table_name.'.'.$row->selection_table_primary_key.br().
                                $row->relation_table_name.'.'.$row->relation_table_column_name.' = '.$row->table_name.'.'.$row->table_primary_key;
                        }
                        // explode

                        $caption .= '<b>'.$row->name.'</b>'. ' ('.$row->caption.')<br />'.
                            $role_description;
                        $caption .= $options == ''? '' : ' | '.$options;
                    }else{
                        $caption .= '<b>'.$row->name.'</b>'. ' ('.$row->caption.')';
                        $caption .= $options == ''? '' : '<br />'.$options;
                    }
                }
            }else{
                $caption = $row->{$title_lookup_field};
            }
            $arr[] =
                 '<li>' . anchor(
                    $link.$primary_key.'/edit/'.$row->{$primary_key_lookup_field},
                    $caption
                ) . '</li>';
        }
        $html = '<ul style="padding-left:15px; padding-top:5px; font-size:12px;">' . implode('',$arr) . '</ul>';
        return $html;
    }

}
