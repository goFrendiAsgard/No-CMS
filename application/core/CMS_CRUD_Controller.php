<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * CMS_CRUD_Controller class.
 *
 * @author gofrendi
 */
class CMS_CRUD_Controller extends CMS_Secure_Controller
{
    protected $TABLE_NAME = '';
    protected $COLUMN_NAMES = array();
    protected $DISPLAY_AS = array();
    protected $PRIMARY_KEY = '';
    protected $CRUD = NULL;
    protected $STATE = NULL;
    protected $STATE_INFO = NULL;
    protected $PK_VALUE = NULL;
    protected $UNSET_JQUERY = TRUE;
    protected $UNSET_READ = TRUE;
    protected $UNSET_ADD = FALSE;
    protected $UNSET_EDIT = FALSE;
    protected $UNSET_DELETE = FALSE;
    protected $UNSET_LIST = FALSE;
    protected $UNSET_BACK_TO_LIST = FALSE;
    protected $UNSET_PRINT = FALSE;
    protected $UNSET_EXPORT = FALSE;
    protected $AUTOMATICALLY_USE_PRIVILEGES = TRUE;

    protected function make_crud()
    {
        ////////////////////////////////////////////////////////////////////////
        // initialize groceryCRUD
        ////////////////////////////////////////////////////////////////////////
        $this->CRUD = $this->new_crud();

        // check state & get PK_VALUE
        $this->STATE = $this->CRUD->getState();
        $this->STATE_INFO = $this->CRUD->getStateInfo();
        $this->PK_VALUE = isset($this->STATE_INFO->primary_key)? $this->STATE_INFO->primary_key : NULL;

        // unset jquery (we use No-CMS's default jquery)
        if($this->UNSET_JQUERY){
            $this->CRUD->unset_jquery();
        }

        // privilege to read
        if($this->UNSET_READ || ($this->AUTOMATICALLY_USE_PRIVILEGES && !$this->cms_have_privilege($this->cms_complete_navigation_name('read_' . $this->TABLE_NAME)))){
            $this->CRUD->unset_read();
        }

        // privilege to add
        if($this->UNSET_ADD || ($this->AUTOMATICALLY_USE_PRIVILEGES && !$this->cms_have_privilege($this->cms_complete_navigation_name('add_' . $this->TABLE_NAME)))){
            $this->CRUD->unset_add();
        }

        // privilege to edit
        if($this->UNSET_EDIT || ($this->AUTOMATICALLY_USE_PRIVILEGES && !$this->cms_have_privilege($this->cms_complete_navigation_name('edit_' . $this->TABLE_NAME)))){
            $this->CRUD->unset_edit();
        }

        // privilege to delete
        if($this->UNSET_DELETE || ($this->AUTOMATICALLY_USE_PRIVILEGES && !$this->cms_have_privilege($this->cms_complete_navigation_name('delete_' . $this->TABLE_NAME)))){
            $this->CRUD->unset_delete();
        }

        // privilege to list (uncomment if you need it)
        if($this->UNSET_LIST || ($this->AUTOMATICALLY_USE_PRIVILEGES && !$this->cms_have_privilege($this->cms_complete_navigation_name('list_' . $this->TABLE_NAME)))){
            $this->CRUD->unset_list();
        }

        // privilege to back_to_list
        if($this->UNSET_BACK_TO_LIST || ($this->AUTOMATICALLY_USE_PRIVILEGES && !$this->cms_have_privilege($this->cms_complete_navigation_name('back_to_list_' . $this->TABLE_NAME)))){
            $this->CRUD->unset_back_to_list();
        }

        // privilege to print
        if($this->UNSET_PRINT || ($this->AUTOMATICALLY_USE_PRIVILEGES && !$this->cms_have_privilege($this->cms_complete_navigation_name('print_' . $this->TABLE_NAME)))){
            $this->CRUD->unset_print();
        }

        // privilege to export
        if($this->UNSET_EXPORT || ($this->AUTOMATICALLY_USE_PRIVILEGES && !$this->cms_have_privilege($this->cms_complete_navigation_name('export_' . $this->TABLE_NAME)))){
            $this->CRUD->unset_export();
        }

        // adjust groceryCRUD's language to No-CMS's language
        $this->CRUD->set_language($this->cms_language());

        // table name
        $this->CRUD->set_table($this->cms_complete_table_name($this->TABLE_NAME));

        // primary key
        $this->CRUD->set_primary_key($this->PRIMARY_KEY);

        // assign columns
        $this->CRUD->columns = $this->COLUMN_NAMES;
        // assign add fields
        $add_fields = $this->COLUMN_NAMES;
        if(!in_array('_created_at', $add_fields)){ $add_fields[] = '_created_at'; }
        if(!in_array('_created_by', $add_fields)){ $add_fields[] = '_created_by'; }
        $this->CRUD->add_fields = $add_fields;
        // assign edit fields
        $edit_fields = $this->COLUMN_NAMES;
        if(!in_array('_updated_at', $edit_fields)){ $edit_fields[] = '_updated_at'; }
        if(!in_array('_updated_by', $edit_fields)){ $edit_fields[] = '_updated_by'; }
        $this->CRUD->edit_fields = $edit_fields;

        // get missing_field_list
        $existing_field_list = $this->db->list_fields($this->cms_complete_table_name($this->TABLE_NAME));
        $missing_field_list = array();
        foreach($add_fields as $field_name){
            if(!in_array($field_name, $existing_field_list)){
                $missing_field_list[] = $field_name;
            }
        }
        foreach($edit_fields as $field_name){
            if(!in_array($field_name, $existing_field_list)){
                $missing_field_list[] = $field_name;
            }
        }
        // prepare to alter table and add missing fields
        $missing_fields = array();
        foreach($missing_field_list as $field_name){
            if($field_name == 'created_at' || $field_name == 'updated_at'){
                $missing_fields[$field_name] = array('type' => 'TIMESTAMP', 'null' => true);
            }else if($field_name == 'created_by' || $field_name == 'updated_by'){
                $missing_fields[$field_name] = array('type' => 'INT', 'constraint' => 20, 'unsigned' => true, 'null' => true);
            }else{
                $missing_fields[$field_name] = array('type' => 'VARCHAR', 'constraint' => 50, 'null' => true);
            }
        }
        if(count($missing_fields) > 0){
            $this->load->dbforge();
            $this->dbforge->add_column($this->cms_complete_table_name($this->TABLE_NAME), $missing_fields);
        }

        // display as
        foreach($this->DISPLAY_AS as $field=>$caption){
            $this->CRUD->display_as($field, $caption);
        }

        // callbacks
        $this->CRUD->callback_before_insert(array($this,'_internal_before_insert'));
        $this->CRUD->callback_before_update(array($this,'_internal_before_update'));
        $this->CRUD->callback_before_delete(array($this,'_internal_before_delete'));
        $this->CRUD->callback_after_insert(array($this,'_internal_after_insert'));
        $this->CRUD->callback_after_update(array($this,'_internal_after_update'));
        $this->CRUD->callback_after_delete(array($this,'_internal_after_delete'));

        $this->CRUD->callback_show_edit(array($this, '_internal_allow_and_show_edit'));
        $this->CRUD->callback_show_delete(array($this, '_internal_allow_and_show_delete'));

        // hidden fields
        $this->CRUD->field_type('_created_at', 'hidden');
        $this->CRUD->field_type('_created_by', 'hidden');
        $this->CRUD->field_type('_updated_by', 'hidden');
        $this->CRUD->field_type('_updated_at', 'hidden');

        return $this->CRUD;
    }

    protected function build_default_callback(){
        if($this->CRUD === NULL){
            $this->make_crud();
        }

        // get column_list
        $column_list = $this->CRUD->columns;
        // add from add_fields and edit_fields
        foreach($this->CRUD->add_fields as $column){
            if(!in_array($column, $column_list)){
                $column_list[] = $column;
            }
        }
        foreach($this->CRUD->edit_fields as $column){
            if(!in_array($column, $column_list)){
                $column_list[] = $column;
            }
        }

        // automatic add callback if method exists
        foreach($column_list as $column_name){
            // callback column
            if(method_exists($this, '_callback_column_'.$column_name)){
                $this->CRUD->callback_column($column_name, array($this, '_callback_column_'.$column_name));
                $this->CRUD->callback_column($this->CRUD->basic_db_table.'.'.$column_name,
                    array($this, '_callback_column_'.$column_name));
                $this->CRUD->callback_column($this->cms_unique_field_name($column_name), array($this, '_callback_column_'.$column_name));
            }
            // callback field
            if(method_exists($this, '_callback_field_'.$column_name)){
                $this->CRUD->callback_field($column_name, array($this, '_callback_field_'.$column_name));
                $this->CRUD->callback_field($this->CRUD->basic_db_table.'.'.$column_name,
                    array($this, '_callback_field_'.$column_name));
                $this->CRUD->callback_field($this->cms_unique_field_name($column_name), array($this, '_callback_field_'.$column_name));
            }
            if(method_exists($this, $column_name.'_validation')){
                // get field caption
                $caption = array_key_exists($column_name, $this->CRUD->display_as)? $this->CRUD->display_as[$column_name]:
                    ucwords(str_replace('_', ' ', $column_name));
                // add callback
                $this->CRUD->set_rules($column_name, $caption, 'callback_'.$column_name.'_validation');
                $this->CRUD->set_rules($this->CRUD->basic_db_table.'.'.$column_name,
                    $caption, 'callback_'.$column_name.'_validation');
                $this->CRUD->set_rules($this->cms_unique_field_name($column_name), $caption, 'callback_'.$column_name.'_validation');
            }
        }
    }

    public function _ommit_nbsp($matches){
        return $matches[1].str_replace('&nbsp;', ' ', $matches[2]).$matches[3];
    }

    protected function render_crud($crud = NULL){
        if($crud == NULL){
            $crud = $this->CRUD;
        }
        $output = $crud->render();

        // prepare css and js, add them to config
        $config = array();
        $asset = new Cms_asset();
        foreach($output->css_files as $file){
            $asset->add_css($file);
        }
        $config['css'] = $asset->compile_css();

        foreach($output->js_files as $file){
            $asset->add_js($file);
        }
        $config['js'] = $asset->compile_js();

        $output->output = preg_replace_callback('/(<option[^<>]*>)(.*?)(<\/option>)/si', array($this,'_ommit_nbsp'), $output->output);

        return array('output'=>$output, 'config'=>$config);
    }

    protected function _save_one_to_many($field_name, $detail_table_name, $pk_column, $fk_column, $parent_pk_value,
    $DATA, $real_column_list=array(), $set_column_list=array(), $many_to_many_config_list=array()){

        $insert_records = is_array($DATA) && array_key_exists('insert', $DATA) && is_array($DATA['insert'])?
            $DATA['insert'] : array();
        $update_records = is_array($DATA) && array_key_exists('update', $DATA) && is_array($DATA['update'])?
            $DATA['update'] : array();
        $delete_records = is_array($DATA) && array_key_exists('delete', $DATA) && is_array($DATA['delete'])?
            $DATA['delete'] : array();

        ////////////////////////////////////////////////////////////////////////
        //  DELETED DATA
        ////////////////////////////////////////////////////////////////////////
        foreach($delete_records as $delete_record){
            $detail_primary_key = $delete_record['primary_key'];
            // delete many to many
            foreach($many_to_many_config_list as $field_name=>$config){
                $table_name = $this->cms_complete_table_name($config['relation_table']);
                $relation_column_name = $config['relation_column'];
                $where = array(
                    $relation_column_name => $detail_primary_key
                );
                $this->db->delete($table_name, $where);
            }
            $this->db->delete($this->cms_complete_table_name($detail_table_name),
                 array($pk_column=>$detail_primary_key));
        }
        ////////////////////////////////////////////////////////////////////////
        //  UPDATED DATA
        ////////////////////////////////////////////////////////////////////////
        foreach($update_records as $update_record){
            $detail_primary_key = $update_record['primary_key'];
            $data = array();
            foreach($update_record['data'] as $key=>$value){
                if(in_array($key, $set_column_list)){
                    $data[$key] = implode(',', $value);
                }else if(in_array($key, $real_column_list)){
                    $data[$key] = $value;
                }
            }
            $data[$fk_column] = $parent_pk_value;
            $this->db->update($this->cms_complete_table_name($detail_table_name),
                 $data, array($pk_column=>$detail_primary_key));
            ////////////////////////////////////////////////////////////////////
            // Adjust Many-to-Many Fields of Updated Data
            ////////////////////////////////////////////////////////////////////
            foreach($many_to_many_config_list as $field_name=>$config){
                $key = $field_name;
                $table_name = $this->cms_complete_table_name($config['relation_table']);
                $relation_column_name = $config['relation_column'];
                $relation_selection_column_name = $config['relation_selection_column'];
                $new_values = $update_record['data'][$key];
                $query = $this->db->select($relation_column_name.','.$relation_selection_column_name)
                    ->from($table_name)
                    ->where($relation_column_name, $detail_primary_key)
                    ->get();
                ////////////////////////////////////////////////////////////////
                // delete everything which is not in new_values
                ////////////////////////////////////////////////////////////////
                $old_values = array();
                foreach($query->result_array() as $row){
                    $old_values = array();
                    if(!in_array($row[$relation_selection_column_name], $new_values)){
                        $where = array(
                            $relation_column_name => $detail_primary_key,
                            $relation_selection_column_name => $row[$relation_selection_column_name]
                        );
                        $this->db->delete($table_name, $where);
                    }else{
                        $old_values[] = $row[$relation_selection_column_name];
                    }
                }
                ////////////////////////////////////////////////////////////////
                // add everything which is not in old_values but in new_values
                ////////////////////////////////////////////////////////////////
                foreach($new_values as $new_value){
                    if(!in_array($new_value, $old_values)){
                        $data = array(
                            $relation_column_name => $detail_primary_key,
                            $relation_selection_column_name => $new_value
                        );
                        $this->db->insert($table_name, $data);
                    }
                }
            }
        }
        ////////////////////////////////////////////////////////////////////////
        //  INSERTED DATA
        ////////////////////////////////////////////////////////////////////////
        for($i=0; $i<count($insert_records); $i++){
            $insert_record = $insert_records[$i];

            $data = array();
            foreach($insert_record['data'] as $key=>$value){
                if(in_array($key, $set_column_list)){
                    $data[$key] = implode(',', $value);
                }else if(in_array($key, $real_column_list)){
                    $data[$key] = $value;
                }
            }
            $data[$fk_column] = $parent_pk_value;
            $this->db->insert($this->cms_complete_table_name($detail_table_name), $data);
            $detail_primary_key = $this->db->insert_id();

            $DATA['insert'][$i]['primary_key'] = $detail_primary_key;
            ////////////////////////////////////////////////////////////////////
            // Adjust Many-to-Many Fields of Inserted Data
            ////////////////////////////////////////////////////////////////////
            foreach($many_to_many_config_list as $field_name=>$config){
                $key = $field_name;
                $table_name = $this->cms_complete_table_name($config['relation_table']);
                $relation_column_name = $config['relation_column'];
                $relation_selection_column_name = $config['relation_selection_column'];
                $new_values = $insert_record['data'][$key];
                $query = $this->db->select($relation_column_name.','.$relation_selection_column_name)
                    ->from($table_name)
                    ->where($relation_column_name, $detail_primary_key)
                    ->get();
                ////////////////////////////////////////////////////////////////
                // delete everything which is not in new_values
                ////////////////////////////////////////////////////////////////
                $old_values = array();
                foreach($query->result_array() as $row){
                    $old_values = array();
                    if(!in_array($row[$relation_selection_column_name], $new_values)){
                        $where = array(
                            $relation_column_name => $detail_primary_key,
                            $relation_selection_column_name => $row[$relation_selection_column_name]
                        );
                        $this->db->delete($table_name, $where);
                    }else{
                        $old_values[] = $row[$relation_selection_column_name];
                    }
                }
                ////////////////////////////////////////////////////////////////
                // add everything which is not in old_values but in new_values
                ////////////////////////////////////////////////////////////////
                foreach($new_values as $new_value){
                    if(!in_array($new_value, $old_values)){
                        $data = array(
                            $relation_column_name => $detail_primary_key,
                            $relation_selection_column_name => $new_value
                        );
                        $this->db->insert($table_name, $data);
                    }
                }
            }
        }
        return $DATA;
    }

    protected function _one_to_many_callback_field_data($table_name, $pk_column, $fk_column, $parent_pk_value,
    $lookup_config_list = array(), $many_to_many_config_list = array(), $set_column_option_list = array(), $enum_column_option_list = array()){
        $module_path = $this->cms_module_path();
        $this->config->load('grocery_crud');
        $date_format = $this->config->item('grocery_crud_date_format');

        // result
        if(!isset($parent_pk_value)) $parent_pk_value = -1;
        $query = $this->db->select('*')
            ->from($this->cms_complete_table_name($table_name))
            ->where($fk_column, $parent_pk_value)
            ->get();
        $result = $query->result_array();

        $options = array();

        // detail many to many's value and options
        foreach($many_to_many_config_list as $column=>$config){
            // unpack configs
            $relation_table = $config['relation_table'];
            $relation_column = $config['relation_column'];
            $relation_selection_column = $config['relation_selection_column'];
            $selection_table = $config['selection_table'];
            $selection_pk_column = $config['selection_pk_column'];
            $selection_lookup_column = $config['selection_lookup_column'];

            // get many to many values
            for($i=0; $i<count($result); $i++){
                $query_detail = $this->db->select($relation_selection_column)
                   ->from($this->cms_complete_table_name($relation_table))
                   ->where($relation_column, $result[$i][$pk_column])
                   ->get();
                $value = array();
                foreach($query_detail->result() as $row){
                    $value[] = $row->{$relation_selection_column};
                }
                $result[$i][$column] = $value;
            }

            // get many to many options
            $options[$column] = array();
            $query_option = $this->db->select($selection_pk_column.', '.$selection_lookup_column)
               ->from($this->cms_complete_table_name($selection_table))
               ->get();
            foreach($query_option->result() as $row){
                $options[$column][] = array(
                    'value' => $row->{$selection_pk_column},
                    'caption' => strip_tags($row->{$selection_lookup_column})
                );
            }
        }

        // set's value
        foreach($set_column_option_list as $column => $option_list){
            $result[$i][$column] = json_decode($result[$i][$column]);
        }

        // detail one to many
        foreach($lookup_config_list as $column=>$config){
            // unpack configs
            $selection_table = $config['selection_table'];
            $selection_pk_column = $config['selection_pk_column'];
            $selection_lookup_column = $config['selection_lookup_column'];

            // get one to many configs
            $options[$column] = array();
            $query_option = $this->db->select($selection_pk_column.', '.$selection_lookup_column)
               ->from($this->cms_complete_table_name($selection_table))
               ->get();
            foreach($query_option->result() as $row){
                $options[$column][] = array(
                    'value' => $row->{$selection_pk_column},
                    'caption' => strip_tags($row->{$selection_lookup_column})
                );
            }
        }

        // enum's options
        foreach($enum_column_option_list as $column => $option_list){
            $options[$column] = array();
            foreach($option_list as $option){
                $options[$column][] = array(
                    'value' => $option,
                    'caption' => $option,
                );
            }
        }

        // set's options
        foreach($set_column_option_list as $column => $option_list){
            $options[$column] = array();
            foreach($option_list as $option){
                $options[$column][] = array(
                    'value' => $option,
                    'caption' => $option,
                );
            }
        }

        // put data and options all together
        $data = array(
            'result' => $result,
            'options' => $options,
            'date_format' => $date_format,
        );
        return $data;
    }

    protected function _humanized_record_count($table_name, $fk_column, $parent_pk_value, $config){
        // get captions
        $single_caption = array_key_exists('single_caption', $config)? $config['single_caption']: ucwords(str_replace('_', ' ', $table_name));
        $multiple_caption = array_key_exists('multiple_caption', $config)? $config['multiple_caption'] : $single_caption.'s';
        $zero_caption = array_key_exists('zero_caption', $config)? $config['zero_caption'] : 'no '.$single_caption;

        // get num_rows
        $query = $this->db->select('*')
            ->from($this->cms_complete_table_name($table_name))
            ->where($fk_column, $parent_pk_value)
            ->get();
        $num_rows = $query->num_rows();

        // show how many records
        if($num_rows>1){
            return $num_rows .' '.$multiple_caption;
        }else if($num_rows>0){
            return $num_rows .' '.$single_caption;
        }else{
            return $zero_caption;
        }
    }

    public final function _internal_allow_and_show_edit($primary_key){
        return $this->_allow_edit($primary_key) && $this->_show_edit($primary_key);
    }

    public final function _internal_allow_and_show_delete($primary_key){
        return $this->_allow_delete($primary_key) && $this->_show_delete($primary_key);
    }

    public final function _internal_before_insert($post_array){
        $post_array = $this->_before_insert_or_update($post_array);
        $post_array = $this->_before_insert($post_array);
        if(is_array($post_array)){
            if(array_key_exists('_created_at', $post_array)){
                $post_array['_created_at'] = date('Y-m-d H:i:s');
            }
            if(array_key_exists('_created_by', $post_array)){
                $post_array['_created_by'] = $this->cms_user_id();
            }
        }
        return $post_array;
    }

    public final function _internal_after_insert($post_array, $primary_key){
        $success = $this->_after_insert_or_update($post_array, $primary_key);
        $success = $success && $this->_after_insert($post_array, $primary_key);
        return $success;
    }

    public final function _internal_before_update($post_array, $primary_key){
        // First check if update allowed
        if(!$this->_allow_edit($primary_key)){
            return FALSE;
        }
        // call before insert or update
        $post_array = $this->_before_insert_or_update($post_array, $primary_key);
        $post_array = $this->_before_update($post_array, $primary_key);
        // add additional post data
        if(is_array($post_array)){
            if(array_key_exists('_updated_at', $post_array)){
                $post_array['_updated_at'] = date('Y-m-d H:i:s');
            }
            if(array_key_exists('_updated_by', $post_array)){
                $post_array['_updated_by'] = $this->cms_user_id();
            }
        }
        return $post_array;
    }

    public final function _internal_after_update($post_array, $primary_key){
        $success = $this->_after_insert_or_update($post_array, $primary_key);
        $success = $success && $this->_after_update($post_array, $primary_key);
        return $success;
    }

    public final function _internal_before_delete($primary_key){
        // First check if delete allowed
        if(!$this->_allow_delete($primary_key)){
            return FALSE;
        }
        return $this->_before_delete($primary_key);
    }

    public final function _internal_after_delete($primary_key){
        return $this->_after_delete($primary_key);
    }

    public function delete_selection(){
        $crud = $this->make_crud();
        if(!$crud->unset_delete){
            $id_list = json_decode($this->input->post('data'));
            foreach($id_list as $id){
                if($this->_internal_before_delete($id)){
                    $this->db->delete($crud->basic_db_table,
                        array($this->PRIMARY_KEY=>$id));
                    $this->_internal_after_delete($id);
                }
            }
        }
    }

    public function _show_edit($primary_key){
        return TRUE;
    }

    public function _show_delete($primary_key){
        return TRUE;
    }

    public function _allow_edit($primary_key){
        return TRUE;
    }

    public function _allow_delete($primary_key){
        return TRUE;
    }

    public function _before_insert($post_array){
        return $post_array;
    }

    public function _after_insert($post_array, $primary_key){
        return TRUE;
    }

    public function _before_update($post_array, $primary_key){
        return $post_array;
    }

    public function _after_update($post_array, $primary_key){
        return TRUE;
    }

    public function _before_delete($primary_key){
        return TRUE;
    }

    public function _after_delete($primary_key){
        return TRUE;
    }

    public function _after_insert_or_update($post_array, $primary_key){
        return TRUE;
    }

    public function _before_insert_or_update($post_array, $primary_key=NULL){
        return $post_array;
    }

}
