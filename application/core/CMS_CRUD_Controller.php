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
    protected $TABLE_NAME;
    protected $PRIMARY_KEY;
    protected $CRUD;
    protected $STATE;
    protected $STATE_INFO;
    protected $PK_VALUE;
    protected $UNSET_JQUERY = TRUE;
    protected $UNSET_READ = TRUE;
    protected $UNSET_ADD = FALSE;
    protected $UNSET_EDIT = FALSE;
    protected $UNSET_DELETE = FALSE;
    protected $UNSET_LIST = FALSE;
    protected $UNSET_BACK_TO_LIST = FALSE;
    protected $UNSET_PRINT = FALSE;
    protected $UNSET_EXPORT = FALSE;

    protected function make_crud()
    {
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // initialize groceryCRUD
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $this->CRUD = $this->new_crud();

        // check state & get PK_VALUE
        $this->STATE = $this->CRUD->getState();
        $this->STATE_INFO = $this->CRUD->getStateInfo();
        $this->PK_VALUE = isset($this->STATE_INFO->PK_VALUE)? $this->STATE_INFO->PK_VALUE : NULL;

        // unset jquery (we use No-CMS's default jquery)
        $this->CRUD->unset_jquery();

        // privilege to read
        if($this->UNSET_READ || !$this->cms_have_privilege($this->cms_complete_navigation_name('read_' . $this->TABLE_NAME))){
            $this->CRUD->unset_read();
        }

        // privilege to add
        if($this->UNSET_ADD || !$this->cms_have_privilege($this->cms_complete_navigation_name('add_' . $this->TABLE_NAME))){
            $this->CRUD->unset_add();
        }

        // privilege to edit
        if($this->UNSET_EDIT || !$this->cms_have_privilege($this->cms_complete_navigation_name('edit_' . $this->TABLE_NAME))){
            $this->CRUD->unset_edit();
        }

        // privilege to delete
        if($this->UNSET_DELETE || !$this->cms_have_privilege($this->cms_complete_navigation_name('delete_' . $this->TABLE_NAME))){
            $this->CRUD->unset_delete();
        }

        // privilege to list (uncomment if you need it)
        if($this->UNSET_LIST || !$this->cms_have_privilege($this->cms_complete_navigation_name('list_' . $this->TABLE_NAME))){
            $this->CRUD->unset_list();
        }

        // privilege to back_to_list
        if($this->UNSET_BACK_TO_LIST || !$this->cms_have_privilege($this->cms_complete_navigation_name('back_to_list_' . $this->TABLE_NAME))){
            $this->CRUD->unset_back_to_list();
        }

        // privilege to print
        if($this->UNSET_PRINT || !$this->cms_have_privilege($this->cms_complete_navigation_name('print_' . $this->TABLE_NAME))){
            $this->CRUD->unset_print();
        }

        // privilege to export
        if($this->UNSET_EXPORT || !$this->cms_have_privilege($this->cms_complete_navigation_name('export_' . $this->TABLE_NAME))){
            $this->CRUD->unset_export();
        }

        // adjust groceryCRUD's language to No-CMS's language
        $this->CRUD->set_language($this->cms_language());

        // table name
        $this->CRUD->set_table($this->cms_complete_table_name($this->TABLE_NAME));

        // primary key
        $this->CRUD->set_primary_key($this->PRIMARY_KEY);

        // callbacks
        $this->CRUD->callback_before_insert(array($this,'_before_insert'));
        $this->CRUD->callback_before_update(array($this,'_before_update'));
        $this->CRUD->callback_before_delete(array($this,'_before_delete'));
        $this->CRUD->callback_after_insert(array($this,'_after_insert'));
        $this->CRUD->callback_after_update(array($this,'_after_update'));
        $this->CRUD->callback_after_delete(array($this,'_after_delete'));

        // hidden fields
        $this->CRUD->field_type('_created_at', 'hidden');
        $this->CRUD->field_type('_created_by', 'hidden');
        $this->CRUD->field_type('_updated_by', 'hidden');
        $this->CRUD->field_type('_updated_at', 'hidden');

        return $this->CRUD;
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
    $data, $real_column_list=array(), $set_column_list=array(), $many_to_many_config_list=array()){

        $insert_records = $data['insert'];
        $update_records = $data['update'];
        $delete_records = $data['delete'];

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //  DELETED DATA
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //  UPDATED DATA
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // Adjust Many-to-Many Fields of Updated Data
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
                /////////////////////////////////////////////////////////////////////////////////////////////////////////
                // delete everything which is not in new_values
                /////////////////////////////////////////////////////////////////////////////////////////////////////////
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
                /////////////////////////////////////////////////////////////////////////////////////////////////////////
                // add everything which is not in old_values but in new_values
                /////////////////////////////////////////////////////////////////////////////////////////////////////////
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
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //  INSERTED DATA
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        foreach($insert_records as $insert_record){
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
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // Adjust Many-to-Many Fields of Inserted Data
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
                /////////////////////////////////////////////////////////////////////////////////////////////////////////
                // delete everything which is not in new_values
                /////////////////////////////////////////////////////////////////////////////////////////////////////////
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
                /////////////////////////////////////////////////////////////////////////////////////////////////////////
                // add everything which is not in old_values but in new_values
                /////////////////////////////////////////////////////////////////////////////////////////////////////////
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

    public function delete_selection(){
        $crud = $this->make_crud();
        if(!$crud->unset_delete){
            $id_list = json_decode($this->input->post('data'));
            foreach($id_list as $id){
                if($this->_before_delete($id)){
                    $this->db->delete($this->cms_complete_table_name('job'),
                        array($this->PRIMARY_KEY=>$id));
                    $this->_after_delete($id);
                }
            }
        }
    }

    public function _before_insert($post_array){
        $post_array = $this->_before_insert_or_update($post_array);
        if(array_key_exists('_created_at', $post_array)){
            $post_array['_created_at'] = date('Y-m-d H:i:s');
        }
        if(array_key_exists('_created_by', $post_array)){
            $post_array['_created_by'] = $this->cms_user_id();
        }
        return $post_array;
    }

    public function _after_insert($post_array, $PK_VALUE){
        $success = $this->_after_insert_or_update($post_array, $PK_VALUE);
        return $success;
    }

    public function _before_update($post_array, $PK_VALUE){
        $post_array = $this->_before_insert_or_update($post_array, $PK_VALUE);
        if(array_key_exists('_updated_at', $post_array)){
            $post_array['_updated_at'] = date('Y-m-d H:i:s');
        }
        if(array_key_exists('_updated_by', $post_array)){
            $post_array['_updated_by'] = $this->cms_user_id();
        }
        return $post_array;
    }

    public function _after_update($post_array, $PK_VALUE){
        $success = $this->_after_insert_or_update($post_array, $PK_VALUE);
        return $success;
    }

    public function _before_delete($PK_VALUE){
        return TRUE;
    }

    public function _after_delete($PK_VALUE){
        return TRUE;
    }

    public function _after_insert_or_update($post_array, $PK_VALUE){
        return TRUE;
    }

    public function _before_insert_or_update($post_array, $PK_VALUE=NULL){
        return $post_array;
    }

}
