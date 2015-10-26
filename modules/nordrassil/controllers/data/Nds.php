<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Nordrassil
 *
 * @author Go Frendi Gunawan
 */
class Nds extends CMS_Controller {

    public function template(){
        $this->cms_guard_page($this->n('template'));
        $crud = $this->new_crud();
        $crud->unset_jquery();

        $crud->set_subject('Generator Template');

        // table name
        $crud->set_table($this->t('template'));

        // displayed columns on list
        $crud->columns('name','generator_path','options');
        // displayed columns on edit operation
        $crud->edit_fields('name','generator_path','options');
        // displayed columns on add operation
        $crud->add_fields('name','generator_path');

        $crud->unset_read();

        $crud->required_fields('name','generator_path');
        $crud->unique_fields('name');

        // caption of each columns
        $crud->display_as('name','Name');
        $crud->display_as('generator_path','Generator Path');
        $crud->display_as('options','Options');
        $crud->display_as('manage_options','Manage Options');

        $crud->callback_column('options',array($this,'_callback_column_template_options'));
        $crud->callback_edit_field('options',array($this,'_callback_edit_field_template_options'));

        $crud->callback_before_delete(array($this, '_callback_template_before_delete'));


        // adjust grocery-crud language
        $crud->set_language($this->cms_language());

        // render
        $output = $crud->render();
        $output->module_path = $this->cms_module_path();
        $this->view($this->cms_module_path()."/data/nds_template", $output, $this->n('template'));
    }

    public function _callback_template_before_delete($primary_key){
        $this->load->model($this->cms_module_path().'/data/nds_model');
        $this->nds_model->before_delete_template($primary_key);
        return TRUE;
    }

    public function _callback_column_template_options($value, $row){
        $primary_key = $row->template_id;
        $url = site_url($this->cms_module_path().'/data/nds/template_option');
        $caption = 'Option';
        $this->load->model($this->cms_module_path().'/data/nds_model');
        $result = $this->nds_model->get_template_option_by_template($primary_key);
        $action = $this->get_detail_action($caption, $url, $primary_key);
        $data = $this->get_detail_data($result, $url, $primary_key, 'option_id', 'name');
        return $action.br().$data;
    }

    public function _callback_edit_field_template_options($value, $primary_key){
        $url = site_url($this->cms_module_path().'/data/nds/template_option');
        $caption = 'Option';
        $this->load->model($this->cms_module_path().'/data/nds_model');
        $result = $this->nds_model->get_template_option_by_template($primary_key);
        $action = $this->get_detail_action($caption, $url, $primary_key);
        $data = $this->get_detail_data($result, $url, $primary_key, 'option_id', 'name');
        return $action.br().$data;
    }

    public function template_option($template_id=NULL){
        $this->cms_guard_page($this->n('template'));
        $crud = $this->new_crud();
        $crud->unset_jquery();

        // table name
        $crud->set_table($this->t('template_option'));

        // displayed columns on list
        $crud->columns('template_id','name','description','option_type');
        // displayed columns on edit operation
        $crud->edit_fields('template_id','name','description','option_type');
        // displayed columns on add operation
        $crud->add_fields('template_id','name','description','option_type');

        $crud->required_fields('name','option_type');

        // caption of each columns
        $crud->display_as('template_id','Template');
        $crud->display_as('name','Name');
        $crud->display_as('description','Description');
        $crud->display_as('option_type','Option Type');

        $crud->unset_texteditor('description');
        $crud->field_type('option_type', 'enum', array('project','table','column'));

        if(isset($template_id) && intval($template_id)>0){
            $crud->where($this->t('template_option').'.template_id', $template_id);
            $crud->field_type('template_id', 'hidden', $template_id);
        }else{
            $crud->set_relation('template_id',$this->t('template'),'name');
        }

        $crud->callback_column($this->unique_field_name('template_id'),array($this,'_callback_column_template_option_template_id'));

        $crud->callback_before_delete(array($this,'_callback_template_option_before_delete'));

        // adjust grocery-crud language
        $crud->set_language($this->cms_language());

        // render
        $output = $crud->render();
        if(isset($template_id)){
            $output->template_id = $template_id;
        }
        $this->view($this->cms_module_path()."/data/nds_template_option", $output, $this->n('template'));
    }

    public function _callback_template_option_before_delete($primary_key){
        $this->load->model($this->cms_module_path().'/data/nds_model');
        $this->nds_model->before_delete_template_option($primary_key);
        return TRUE;
    }

    public function _callback_column_template_option_template_id($value, $row){
        $parent_key = $row->template_id;
        $url = site_url($this->cms_module_path().'/data/nds/template');
        return $this->get_back_to_parent_action($value, $url, $parent_key);
    }

    public function project(){
        $this->cms_guard_page($this->n('project'));
        $crud = $this->new_crud();
        $crud->unset_jquery();

        $crud->set_subject('Project');

        // table name
        $crud->set_table($this->t('project'));

        // displayed columns on list
        $crud->columns('name','options','tables');
        // displayed columns on edit operation
        $crud->edit_fields('template_id','name','options','db_server','db_port','db_schema','db_user','db_password','db_table_prefix','tables');
        // displayed columns on add operation
        $crud->add_fields('template_id','name','options','db_server','db_port','db_schema','db_user','db_password','db_table_prefix');

        $crud->required_fields('template_id','name');
        $crud->unique_fields('name');

        $crud->unset_read();

        // caption of each columns
        $crud->display_as('template_id','Template');
        $crud->display_as('name','Name');
        $crud->display_as('options','Options');
        $crud->display_as('db_server','Database Server');
        $crud->display_as('db_port','Database Port');
        $crud->display_as('db_schema','Database Schema');
        $crud->display_as('db_user','Database User');
        $crud->display_as('db_password','Database Password');
        $crud->display_as('db_table_prefix','Database Table Prefix (For Filtering)');
        $crud->display_as('tables','Tables');

        $crud->field_type('db_password','password');

        $crud->set_relation('template_id',$this->t('template'),'name');
        $crud->set_relation_n_n('options',$this->t('project_option'),
            $this->t('template_option'),'project_id','option_id','name',
            null, array('option_type'=>'project'));


        $crud->callback_after_insert(array($this, '_callback_project_after_insert'));
        $crud->callback_before_delete(array($this, '_callback_project_before_delete'));

        $crud->callback_column($this->t('project').'.name',array($this,'_callback_column_project_name'));
        $crud->callback_column('tables',array($this,'_callback_column_project_tables'));
        $crud->callback_edit_field('tables',array($this,'_callback_edit_field_project_tables'));

        $crud->add_action('Export','glyphicon glyphicon-export',
            $this->cms_module_path().'/data/nds/get_seed', 'btn btn-default blank');

        // adjust grocery-crud language
        $crud->set_language($this->cms_language());

        // render
        $output = $crud->render();
        $output->module_path = $this->cms_module_path();
        $output->state = $crud->getState();
        $this->view($this->cms_module_path()."/data/nds_project", $output, $this->n('project'));
    }

    public function _callback_project_after_insert($post_array, $primary_key){
        set_time_limit(60);
        $this->load->model($this->cms_module_path().'/data/synchronize_model');
        if(!isset($primary_key) || $primary_key == NULL){
            $query = $this->db->select('project_id')->from($this->t('project'))
                ->where(array('name'=>$post_array['name']))
                ->get();
            if($query->num_rows()>0){
                $row = $query->row();
                $primary_key = $row->project_id;
            }
        }
        $this->synchronize_model->synchronize($primary_key);
        return true;
    }

    public function _callback_project_before_delete($primary_key){
        $this->load->model($this->cms_module_path().'/data/nds_model');
        $this->nds_model->before_delete_project($primary_key);
        return TRUE;
    }

    public function _callback_column_project_name($value, $row){
        $query = $this->db->select('name')
            ->from($this->t('template'))
            ->where('template_id', $row->template_id)
            ->get();
        $template_row = $query->row();
        $template_name = $template_row->name;
        return '<b>'.$value.'</b>'.br().$template_name;
    }

    public function _callback_column_project_tables($value, $row){
        $primary_key = $row->project_id;
        $url = site_url($this->cms_module_path().'/data/nds/table');
        $caption = 'Table';
        $this->load->model('/data/nds_model');
        $action =  $this->get_detail_action($caption, $url, $primary_key, FALSE);
        $result = $this->nds_model->get_table_by_project($primary_key);
        return $action.br().$this->get_detail_data($result, $url, $primary_key, 'table_id');
    }

    public function _callback_edit_field_project_tables($value, $primary_key){
        $url = site_url($this->cms_module_path().'/data/nds/table');
        $caption = 'Table';
        $this->load->model('/data/nds_model');
        $result = $this->nds_model->get_table_by_project($primary_key, FALSE);
        // action
        $action = $this->get_detail_action($caption, $url, $primary_key);
        $action.= ' | '.anchor(
            site_url($this->cms_module_path().'/data/nds/resynchronize_from_db/'.$primary_key),
            '<i class="glyphicon glyphicon-refresh"></i> Fetch From Database');
        $data = $this->get_detail_data($result, $url, $primary_key, 'table_id');
        return $action.br().$data;
    }

    public function resynchronize_from_db($project_id){
        $this->load->model($this->cms_module_path().'/data/synchronize_model');
        $this->synchronize_model->synchronize($project_id);
        redirect(site_url($this->cms_module_path().'/data/nds/project/edit/'.$project_id));
    }

    public function get_seed($project_id){
        $this->load->helper('inflector');
        $this->load->model($this->cms_module_path().'/data/nds_model');
        $row = $this->db->select('name')->from($this->t('project'))
            ->where('project_id', $project_id)->get()->row();
        $project_name = $row->name;
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.
            basename(underscore($project_name).
            '.nordrassil_seed.json'));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        // get the project
        $project = $this->nds_model->get_project($project_id);

        // remove unused key
        foreach($project as $project_key=>$project_val){
            // unset empty options
            if($project_key == 'options'){
                foreach($project_val as $option=>$option_val){
                    if($option_val === FALSE){
                        unset($project['options'][$project_key]);
                        unset($project_val[$project_key]);
                    }
                }
            }
            // clean up tables
            if($project_key == 'tables'){
                for($i=0; $i<count($project_val); $i++){
                    $table = $project_val[$i];
                    foreach($table as $table_key=>$table_val){
                        // unset empty table options
                        if($table_key == 'options'){
                            foreach($table_val as $option=>$option_val){
                                if($option_val === FALSE){
                                    unset($project['tables'][$i]['options'][$option]);
                                    unset($table_val[$option]);
                                }
                            }
                        }
                        // clean up columns
                        if($table_key == 'columns'){
                            for($j=0; $j<count($table_val); $j++){
                                $column = $table_val[$j];
                                foreach($column as $column_key=>$column_val){
                                    // unset empty column options
                                    if($column_key == 'options'){
                                        foreach($column_val as $option=>$option_val){
                                            if($option_val === FALSE){
                                                unset($project['tables'][$i]['columns'][$j]['options'][$option]);
                                                unset($column_val[$option]);
                                            }
                                        }
                                    }
                                    // unset empty and irelevant keys
                                    if($column_key == 'lookup_stripped_table_name' || $column_key == 'selection_stripped_table_name' ||
                                    $column_key == 'relation_stripped_table_name' || $column_val === '' || (is_array($column_val) && count($column_val)==0)){
                                        unset($project['tables'][$i]['columns'][$j][$column_key]);
                                    }
                                }
                            }
                        }
                        // unset empty and irelevant keys
                        if($table_key == 'stripped_name' || $table_val === '' || (is_array($table_val) && count($table_val)==0)){
                            unset($project['tables'][$i][$table_key]);
                        }
                    }
                }
            }
            // unset other empty keys
            if($project_val === '' || (is_array($project_val) && count($project_val)==0)){
                unset($project[$project_key]);
            }
        }

        // Print it
        if(defined('JSON_PRETTY_PRINT')){
            $str = json_encode($project, JSON_PRETTY_PRINT);
        }else{
            $str = json_encode($project);
        }
        echo $str;
    }

    public function table($project_id = NULL){
        $this->cms_guard_page($this->n('project'));
        $crud = $this->new_crud();
        $crud->unset_jquery();

        $crud->set_subject('Table');

        // table name
        $crud->set_table($this->t('table'));

        if(isset($project_id) && intval($project_id)>0){
            $crud->where($this->t('table').'.project_id', $project_id);
            // displayed columns on list
            $crud->columns('name', 'options', 'columns');
        }else{
            // displayed columns on list
            $crud->columns('project_id', 'name', 'options', 'columns');
        }
        $crud->order_by('priority');

        $crud->required_fields('name','caption');

        $crud->unset_read();

        // displayed columns on edit operation
        $crud->edit_fields('project_id','name','caption','priority','options','columns', 'data');
        // displayed columns on add operation
        $crud->add_fields('project_id','name','caption','priority','options');

        $crud->unset_texteditor('data');

        $crud->set_rules('priority','Priority','numeric');

        // caption of each columns
        $crud->display_as('project_id','Project');
        $crud->display_as('name','Name');
        $crud->display_as('priority','Order Priority');
        $crud->display_as('options','Options');
        $crud->display_as('columns','Columns');
        $crud->display_as('data', 'Data (JSON Format)');

        $crud->set_relation_n_n('options',$this->t('table_option'),
            $this->t('template_option'),'table_id','option_id','name',
            null, array('option_type'=>'table'));

        if(isset($project_id) && intval($project_id)>0){
            $crud->field_type('project_id', 'hidden', $project_id);
        }else{
            $crud->set_relation('project_id',$this->t('project'),'name');
        }

        $crud->callback_before_insert(array($this, '_callback_table_before_insert'));
        $crud->callback_after_insert(array($this, '_callback_table_after_insert'));
        $crud->callback_after_update(array($this, '_callback_table_after_update'));
        $crud->callback_before_delete(array($this, '_callback_table_before_delete'));
        $crud->callback_after_delete(array($this, '_callback_table_after_delete'));

        $crud->callback_column('name',array($this,'_callback_column_table_name'));
        $crud->callback_column('columns',array($this,'_callback_column_table_columns'));
        $crud->callback_edit_field('columns',array($this,'_callback_edit_field_table_columns'));
        $crud->callback_column($this->unique_field_name('project_id'),array($this,'_callback_column_table_project_id'));

        // adjust grocery-crud language
        $crud->set_language($this->cms_language());

        // render
        $output = $crud->render();
        $output->module_path = $this->cms_module_path();
        if(isset($project_id) && is_numeric($project_id)){
            $output->project_id = $project_id;
            $this->load->model($this->cms_module_path().'/data/nds_model');
            $output->project_name = $this->nds_model->get_project_name($project_id);
        }
        $this->view($this->cms_module_path()."/data/nds_table", $output, $this->n('project'));
    }

    public function _reorder_table($project_id){
        $result = $this->db->select('*')
            ->from($this->t('table'))
            ->where('project_id', $project_id)
            ->order_by('priority')
            ->get()
            ->result();
        $priority = 0;
        foreach($result as $row){
            if($row->priority != $priority){
                $this->db->update($this->t('table'),
                    array('priority' => $priority),
                    array('table_id' => $row->table_id));
            }
            $priority ++;
        }
    }

    public function _callback_table_before_insert($post_array){
        if($post_array['priority'] == '' || $post_array['priority'] == NULL){
            $post_array['priority'] = 5000;
        }
        return $post_array;
    }

    public function _callback_table_after_insert($post_array, $primary_key){
        $success = $this->_callback_table_after_insert_or_update($post_array, $primary_key);
        return $success;
    }
    public function _callback_table_after_update($post_array, $primary_key){
        $success = $this->_callback_table_after_insert_or_update($post_array, $primary_key);
        return $success;
    }
    public function _callback_table_after_insert_or_update($post_array, $primary_key){
        $project_id = $post_array['project_id'];
        $priority = $post_array['priority'];
        $table_id = $primary_key;
        $t_table = $this->t('table');
        $sql = "
            UPDATE $t_table SET priority = priority+1
            WHERE project_id = $project_id AND
                  table_id <> $table_id AND
                  priority >= $priority;";
        $this->db->query($sql);
        $this->_reorder_table($project_id);
        return TRUE;
    }

    public function _callback_table_before_delete($primary_key){
        $this->load->model($this->cms_module_path().'/data/nds_model');
        $this->nds_model->before_delete_table($primary_key);
        return TRUE;
    }

    public function _callback_column_table_project_id($value, $row){
        $parent_key = $row->project_id;
        $url = site_url($this->cms_module_path().'/data/nds/project');
        return $this->get_back_to_parent_action($value, $url, $parent_key);
    }

    public function _callback_column_table_name($value, $row){
        $html = '<b>' . $value . '</b>' . br() . '(' . $row->caption . ')<a id="rec-'.$row->table_id.'" name="rec-'.$row->table_id.'">&nbsp;</a>';

        // TODO: code this
        if(isset($_SESSION['__mark_move_table_id'][$row->project_id]) && $_SESSION['__mark_move_table_id'][$row->project_id] != NULL){
            $mark_move_table_id = $_SESSION['__mark_move_table_id'][$row->project_id];
            if($row->table_id == $mark_move_table_id){
                // cancel link
                $html .= '<br /><a href="'.site_url($this->cms_module_path().'/data/nds/table_move_cancel/'.$row->project_id).'"><i class="glyphicon glyphicon-repeat"></i> Undo</a>';
            }else{
                // paste before, paste after, paste inside
                $html .= ' <br /><a href="'.site_url($this->cms_module_path().'/data/nds/table_move_before/'.$row->project_id.'/'.$row->table_id).'"><i class="glyphicon glyphicon-open"></i> Put Before</a>';
                $html .= ' | <a href="'.site_url($this->cms_module_path().'/data/nds/table_move_after/'.$row->project_id.'/'.$row->table_id).'"><i class="glyphicon glyphicon-save"></i> Put After</a>';
            }
        }else{
            $html .= '<br /><a href="'.site_url($this->cms_module_path().'/data/nds/table_mark_move/'.$row->project_id.'/'.$row->table_id).'"><i class="glyphicon glyphicon-share-alt"></i> Move</a>';
        }

        return $html;
    }

    public function _callback_column_table_columns($value, $row){
        $primary_key = $row->table_id;
        $url = site_url($this->cms_module_path().'/data/nds/column');
        $caption = 'Column';
        $this->load->model('/data/nds_model');
        $result = $this->nds_model->get_column_by_table($primary_key);
        $action = $this->get_detail_action($caption, $url, $primary_key, FALSE);
        return $action.br().$this->get_detail_data($result, $url, $primary_key, 'column_id');
    }

    public function _callback_edit_field_table_columns($value, $primary_key){
        $url = site_url($this->cms_module_path().'/data/nds/column');
        $caption = 'Column';
        $this->load->model('/data/nds_model');
        $result = $this->nds_model->get_column_by_table($primary_key);
        $action = $this->get_detail_action($caption, $url, $primary_key, FALSE);
        $data = $this->get_detail_data($result, $url, $primary_key, 'column_id');
        return $action.br().$data;
    }

    public function column($table_id=NULL){
        $this->cms_guard_page($this->n('project'));
        $this->load->model($this->cms_module_path().'/data/nds_model');
        $crud = $this->new_crud();
        $crud->unset_jquery();

        $crud->set_subject('Column');

        // table name
        $crud->set_table($this->t('column'));
        if(isset($table_id) && intval($table_id)>0){
            $crud->where($this->t('column').'.table_id', $table_id);
            // displayed columns on list
            $crud->columns('name','options');
        }else{
            // displayed columns on list
            $crud->columns('table_id','name','options');
        }
        $crud->order_by('priority');

        $crud->required_fields('name','caption');

        $crud->unset_read();

        // displayed columns on edit operation
        $crud->edit_fields('table_id','name','caption','role','data_type','data_size','value_selection_mode','value_selection_item','options','priority','lookup_table_id','lookup_column_id','relation_table_id','relation_table_column_id',
            'relation_selection_column_id','relation_priority_column_id','selection_table_id','selection_column_id');
        // displayed columns on add operation
        $crud->add_fields('table_id','name','caption','role','data_type','data_size','value_selection_mode','value_selection_item','options','priority','lookup_table_id','lookup_column_id','relation_table_id','relation_table_column_id',
            'relation_selection_column_id','relation_priority_column_id','selection_table_id','selection_column_id');

        $crud->set_rules('priority','Priority','numeric');

        // caption of each columns
        $crud->display_as('table_id','Table');
        $crud->display_as('name','Name');
        $crud->display_as('caption','Caption');
        $crud->display_as('data_type','Data Type');
        $crud->display_as('data_size','Size');
        $crud->display_as('role','Role');
        $crud->display_as('lookup_table_id','Lookup Table');
        $crud->display_as('lookup_column_id','Lookup Shown Column');
        $crud->display_as('relation_table_id','Relation Table');
        $crud->display_as('relation_table_column_id','Relation Column To This Table');
        $crud->display_as('relation_selection_column_id','Relation Column To Selection Table');
        $crud->display_as('relation_priority_column_id','Relation Priority Column');
        $crud->display_as('selection_table_id','Selection Table');
        $crud->display_as('selection_column_id','Selection Shown Column');
        $crud->display_as('value_selection_mode','Selection Mode');
        $crud->display_as('value_selection_item','Selection Item');
        $crud->display_as('priority', 'Order Priority');

        $crud->field_type('data_type', 'enum', $this->nds_model->available_data_type);
        $crud->field_type('role', 'enum', array('primary','lookup','detail many to many','detail one to many'));
        $crud->field_type('value_selection_mode', 'enum', array('set','enum'));

        $crud->set_relation_n_n('options',$this->t('column_option'),
          $this->t('template_option'),'column_id','option_id','name',
            null, array('option_type'=>'column'));

        $crud->set_relation('lookup_table_id',$this->t('table'),'name');
        $crud->set_relation('relation_table_id',$this->t('table'),'name');
        $crud->set_relation('selection_table_id',$this->t('table'),'name');

        $crud->set_relation('lookup_column_id',$this->t('column'),'name');
        $crud->set_relation('relation_table_column_id',$this->t('column'),'name');
        $crud->set_relation('relation_selection_column_id',$this->t('column'),'name');
        $crud->set_relation('relation_priority_column_id',$this->t('column'),'name');
        $crud->set_relation('selection_column_id',$this->t('column'),'name');

        if(isset($table_id) && intval($table_id)>0){
            $crud->field_type('table_id', 'hidden', $table_id);
        }else{
            $crud->set_relation('table_id',$this->t('table'),'name');
        }

        $crud->callback_before_insert(array($this, '_callback_column_before_insert'));
        $crud->callback_after_insert(array($this, '_callback_column_after_insert'));
        $crud->callback_after_update(array($this, '_callback_column_after_update'));
        $crud->callback_before_delete(array($this,'_callback_column_before_delete'));

        $crud->callback_column($this->t('column').'.name',array($this,'_callback_column_column_name'));
        $crud->callback_column($this->unique_field_name('table_id'),array($this,'_callback_column_column_table_id'));

        // adjust grocery-crud language
        $crud->set_language($this->cms_language());

        // render
        $output = $crud->render();
        $output->module_path = $this->cms_module_path();
        if(isset($table_id) && is_numeric($table_id)){
            $this->load->model($this->cms_module_path().'/data/nds_model');
            $query = $this->db->select('project_id')->from($this->t('table'))->where('table_id',$table_id)->get();
            $row = $query->row();
            $project_id = $row->project_id;
            $output->project_id = $project_id;
            $output->project_name = $this->nds_model->get_project_name($project_id);
            $output->table_id = $table_id;
            $output->table_name = $this->nds_model->get_table_name($table_id);
        }
        $this->view($this->cms_module_path()."/data/nds_column", $output, $this->n('project'));
    }

    public function _reorder_column($table_id){
        $result = $this->db->select('*')
            ->from($this->t('column'))
            ->where('table_id', $table_id)
            ->order_by('priority')
            ->get()
            ->result();
        $priority = 0;
        foreach($result as $row){
            if($row->priority != $priority){
                $this->db->update($this->t('column'),
                    array('priority' => $priority),
                    array('column_id' => $row->column_id));
            }
            $priority ++;
        }
    }

    public function _callback_column_before_insert($post_array){
        if($post_array['priority'] == '' || $post_array['priority'] == NULL){
            $post_array['priority'] = 5000;
        }
        return $post_array;
    }

    public function _callback_column_after_insert($post_array, $primary_key){
        $success = $this->_callback_column_after_insert_or_update($post_array, $primary_key);
        return $success;
    }
    public function _callback_column_after_update($post_array, $primary_key){
        $success = $this->_callback_column_after_insert_or_update($post_array, $primary_key);
        return $success;
    }
    public function _callback_column_after_insert_or_update($post_array, $primary_key){
        $table_id = $post_array['table_id'];
        $priority = $post_array['priority'];
        $column_id = $primary_key;
        $t_column = $this->t('column');
        $sql = "
            UPDATE $t_column SET priority = priority+1
            WHERE table_id = $table_id AND
                  column_id <> $column_id AND
                  priority >= $priority;";
        $this->db->query($sql);
        $this->_reorder_column($table_id);
        return TRUE;
    }

    public function _callback_column_before_delete($primary_key){
        $this->load->model($this->cms_module_path().'/data/nds_model');
        $this->nds_model->before_delete_column($primary_key);
        return TRUE;
    }

    public function _callback_column_column_name($value, $row){
        $this->load->model($this->cms_module_path().'/nds_model');
        if($row->role == NULL || $row->role == '' || $row->role == 'primary' || $row->role == 'lookup'){
            $description = $row->data_type. '(' . $row->data_size . ')';
            if($row->role != NULL && $row->role != ''){
                $description .= ' <span class="badge">'.ucfirst($row->role).'</span>';
                if($row->role == 'lookup'){
                    // get lookup detail
                    $lookup_table_name = $this->nds_model->get_table_name($row->lookup_table_id);
                    $lookup_column_name = $this->nds_model->get_column_name($row->lookup_column_id);
                    $description .= br().$lookup_table_name.'.'.$lookup_column_name;
                }
            }
        }else{
            $description = '<span class="badge">'.ucfirst($row->role).'</span>';
            // get detail description
            $table_name = $this->nds_model->get_table_name($row->table_id);
            $table_primary_key = $this->nds_model->get_primary_key($row->table_id);
            $relation_table_name = $this->nds_model->get_table_name($row->relation_table_id);
            $relation_table_column_name = $this->nds_model->get_column_name($row->relation_table_column_id);
            if($row->role == 'detail one to many'){
                $description .= br().$table_name.'.'.$table_primary_key.' = '.$relation_table_name.'.'.$relation_table_column_name;
            }else if($row->role == 'detail many to many'){
                $selection_table_name = $this->nds_model->get_table_name($row->selection_table_id);
                $selection_table_primary_key = $this->nds_model->get_primary_key($row->selection_table_id);
                $selection_column_name = $this->nds_model->get_column_name($row->selection_column_id);
                $relation_selection_column_name = $this->nds_model->get_column_name($row->relation_selection_column_id);
                $description .= br().$selection_table_name.'.'.$selection_column_name.br().
                    $relation_table_name.'.'.$relation_selection_column_name.' = '.$selection_table_name.'.'.$selection_table_primary_key.br().
                    $relation_table_name.'.'.$relation_table_column_name.' = '.$table_name.'.'.$table_primary_key;
            }
        }
        $html = '<b>' . $value . '</b> (' . $row->caption . ')'. br() .
            $description . '<a id="rec-'.$row->column_id.'" name="rec-'.$row->column_id.'">&nbsp;</a>';

        // TODO: code this
        if(isset($_SESSION['__mark_move_column_id'][$row->table_id]) && $_SESSION['__mark_move_column_id'][$row->table_id] != NULL){
            $mark_move_column_id = $_SESSION['__mark_move_column_id'][$row->table_id];
            if($row->column_id == $mark_move_column_id){
                // cancel link
                $html .= '<br /><a href="'.site_url($this->cms_module_path().'/data/nds/column_move_cancel/'.$row->table_id).'"><i class="glyphicon glyphicon-repeat"></i> Undo</a>';
            }else{
                // paste before, paste after, paste inside
                $html .= '<br /><a href="'.site_url($this->cms_module_path().'/data/nds/column_move_before/'.$row->table_id.'/'.$row->column_id).'"><i class="glyphicon glyphicon-open"></i> Put Before</a>';
                $html .= ' | <a href="'.site_url($this->cms_module_path().'/data/nds/column_move_after/'.$row->table_id.'/'.$row->column_id).'"><i class="glyphicon glyphicon-save"></i> Put After</a>';
            }
        }else{
            $html .= '<br /><a href="'.site_url($this->cms_module_path().'/data/nds/column_mark_move/'.$row->table_id.'/'.$row->column_id).'"><i class="glyphicon glyphicon-share-alt"></i> Move</a>';
        }

        return $html;
    }

    public function _callback_column_column_table_id($value, $row){
        $parent_key = $row->table_id;
        $url = site_url($this->cms_module_path().'/data/nds/table');
        return $this->get_back_to_parent_action($value, $url, $parent_key);
    }

    public function table_mark_move($project_id, $table_id){
        if(!isset($_SESSION)){
            session_start();
            $_SESSION['__mark_move_table_id'] = array();
        }
        $_SESSION['__mark_move_table_id'][$project_id] = $table_id;
        redirect($this->cms_module_path().'/data/Nds/table/'.$project_id,'refresh');
    }

    public function table_move_cancel($project_id){
        if(!isset($_SESSION)){
            session_start();
            $_SESSION['__mark_move_table_id'] = array();
        }
        $table_id = $_SESSION['__mark_move_table_id'][$project_id];
        $_SESSION['__mark_move_table_id'][$project_id] = NULL;
        redirect($this->cms_module_path().'/data/Nds/table/'.$project_id,'refresh');
    }

    public function table_move_before($project_id, $dst_table_id){
        if(!isset($_SESSION)){
            session_start();
            $_SESSION['__mark_move_table_id'] = array();
        }
        $src_table_id = $_SESSION['__mark_move_table_id'][$project_id];
        $this->do_move_table_before($project_id, $src_table_id, $dst_table_id);
        $_SESSION['__mark_move_table_id'][$project_id] = NULL;
        redirect($this->cms_module_path().'/data/Nds/table/'.$project_id,'refresh');
    }
    public function table_move_after($project_id, $dst_table_id){
        if(!isset($_SESSION)){
            session_start();
            $_SESSION['__mark_move_table_id'] = array();
        }
        $src_table_id = $_SESSION['__mark_move_table_id'][$project_id];
        $this->do_move_table_after($project_id, $src_table_id, $dst_table_id);
        $_SESSION['__mark_move_table_id'][$project_id] = NULL;
        redirect($this->cms_module_path().'/data/Nds/table/'.$project_id,'refresh');
    }

    private function do_move_table_before($project_id, $src_table_id, $dst_table_id){
        $priority = $this->db->select('priority')
            ->from($this->t('table'))
            ->where('table_id', $dst_table_id)
            ->get()->row()->priority;
        // move other tables down
        $query = $this->db->select('table_id, priority')
            ->from($this->t('table'))
            ->where('project_id', $project_id)
            ->where('priority >=', $priority)
            ->get();
        foreach($query->result() as $row){
            $this->db->update($this->t('table'),
                array('priority' => $row->priority+1),
                array('table_id' => $row->table_id));
        }
        // put this table in the right
        $this->db->update($this->t('table'),
            array('priority' => $priority),
            array('table_id' => $src_table_id));
    }

    private function do_move_table_after($project_id, $src_table_id, $dst_table_id){
        $priority = $this->db->select('priority')
            ->from($this->t('table'))
            ->where('table_id', $dst_table_id)
            ->get()->row()->priority;
        // move other tables down
        $query = $this->db->select('table_id, priority')
            ->from($this->t('table'))
            ->where('project_id', $project_id)
            ->where('priority >', $priority)
            ->get();
        foreach($query->result() as $row){
            $this->db->update($this->t('table'),
                array('priority' => $row->priority+1),
                array('table_id' => $row->table_id));
        }
        // put this table in the right
        $this->db->update($this->t('table'),
            array('priority' => $priority+1),
            array('table_id' => $src_table_id));
    }

    public function column_mark_move($table_id, $column_id){
        if(!isset($_SESSION)){
            session_start();
            $_SESSION['__mark_move_column_id'] = array();
        }
        $_SESSION['__mark_move_column_id'][$table_id] = $column_id;
        redirect($this->cms_module_path().'/data/Nds/column/'.$table_id,'refresh');
    }

    public function column_move_cancel($table_id){
        if(!isset($_SESSION)){
            session_start();
            $_SESSION['__mark_move_column_id'] = array();
        }
        $column_id = $_SESSION['__mark_move_column_id'][$table_id];
        $_SESSION['__mark_move_column_id'][$table_id] = NULL;
        redirect($this->cms_module_path().'/data/Nds/column/'.$table_id,'refresh');
    }

    public function column_move_before($table_id, $dst_column_id){
        if(!isset($_SESSION)){
            session_start();
            $_SESSION['__mark_move_column_id'] = array();
        }
        $src_column_id = $_SESSION['__mark_move_column_id'][$table_id];
        $this->do_move_column_before($table_id, $src_column_id, $dst_column_id);
        $_SESSION['__mark_move_column_id'][$table_id] = NULL;
        redirect($this->cms_module_path().'/data/Nds/column/'.$table_id,'refresh');
    }
    public function column_move_after($table_id, $dst_column_id){
        if(!isset($_SESSION)){
            session_start();
            $_SESSION['__mark_move_column_id'] = array();
        }
        $src_column_id = $_SESSION['__mark_move_column_id'][$table_id];
        $this->do_move_column_after($table_id, $src_column_id, $dst_column_id);
        $_SESSION['__mark_move_column_id'][$table_id] = NULL;
        redirect($this->cms_module_path().'/data/Nds/column/'.$table_id,'refresh');
    }

    private function do_move_column_before($table_id, $src_column_id, $dst_column_id){
        $priority = $this->db->select('priority')
            ->from($this->t('column'))
            ->where('column_id', $dst_column_id)
            ->get()->row()->priority;
        // move other tables down
        $query = $this->db->select('column_id, priority')
            ->from($this->t('column'))
            ->where('table_id', $table_id)
            ->where('priority >=', $priority)
            ->get();
        foreach($query->result() as $row){
            $this->db->update($this->t('column'),
                array('priority' => $row->priority+1),
                array('column_id' => $row->column_id));
        }
        // put this table in the right
        $this->db->update($this->t('column'),
            array('priority' => $priority),
            array('column_id' => $src_column_id));
    }

    private function do_move_column_after($table_id, $src_column_id, $dst_column_id){
        $priority = $this->db->select('priority')
            ->from($this->t('column'))
            ->where('column_id', $dst_column_id)
            ->get()->row()->priority;
        // move other tables down
        $query = $this->db->select('column_id, priority')
            ->from($this->t('column'))
            ->where('table_id', $table_id)
            ->where('priority >', $priority)
            ->get();
        foreach($query->result() as $row){
            $this->db->update($this->t('column'),
                array('priority' => $row->priority+1),
                array('column_id' => $row->column_id));
        }
        // put this table in the right
        $this->db->update($this->t('column'),
            array('priority' => $priority+1),
            array('column_id' => $src_column_id));
    }

    private function unique_field_name($field_name) {
            return 's'.substr(md5($field_name),0,8); //This s is because is better for a string to begin with a letter and not with a number
    }

    private function preprocess_url($url){
        if(strlen($url)>0){
            if($url[strlen($url)-1] != '/'){
                $url .= '/';
            }
        }
        return $url;
    }

    private function get_back_to_parent_action($value, $link, $parent_key){
        $link = $this->preprocess_url($link);
        $html = anchor(
                $link.'edit/'.$parent_key,
                $value,
                array('class'=>'btn btn-mini')
            );
        return $html;
    }

    private function get_detail_action($caption, $link, $primary_key, $narrow = FALSE){
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

    private function get_detail_data($model_result, $link, $primary_key, $primary_key_lookup_field='id', $title_lookup_field=NULL){
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
