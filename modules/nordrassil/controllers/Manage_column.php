<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$core_dirname = dirname(dirname(__FILE__));
if(substr($core_dirname,-1) != '/'){
    $core_dirname .= '/';
}
$core_dirname .= 'core/';
include($core_dirname.'Nds_Special_Crud_Controller.php');
/**
 * Description of Manage_column
 *
 * @author No-CMS Module Generator
 */
class Manage_column extends Nds_Special_CRUD_Controller {

    protected $URL_MAP = array();
    protected $TABLE_NAME = 'column';
    protected $COLUMN_NAMES = array('table_id', 'name', 'caption', 'data_type', 'data_size', 'role', 'lookup_table_id', 'lookup_column_id', 'relation_table_id', 'relation_table_column_id', 'relation_selection_column_id', 'relation_priority_column_id', 'selection_table_id', 'selection_column_id', 'priority', 'value_selection_mode', 'value_selection_item', 'column_option');
    protected $PRIMARY_KEY = 'column_id';
    protected $UNSET_JQUERY = TRUE;
    protected $UNSET_READ = TRUE;
    protected $UNSET_ADD = FALSE;
    protected $UNSET_EDIT = FALSE;
    protected $UNSET_DELETE = FALSE;
    protected $UNSET_LIST = FALSE;
    protected $UNSET_BACK_TO_LIST = FALSE;
    protected $UNSET_PRINT = FALSE;
    protected $UNSET_EXPORT = FALSE;

    protected function make_crud($table_id = NULL){
        $crud = parent::make_crud();

        ////////////////////////////////////////////////////////////////////////
        // HINT: You can access this variables after calling parent's make_crud method:
        //      $this->CRUD
        //      $this->STATE
        //      $this->STATE_INFO
        //      $this->PK_VALUE
        ////////////////////////////////////////////////////////////////////////

        if($table_id != NULL){
            $crud->where($this->t('column').'.table_id', $table_id);
            // displayed columns on list
            $crud->columns('name', 'column_option');
        }else{
            $crud->columns('table_id', 'name', 'column_option');
        }
        $crud->order_by('table_id');
        $crud->order_by('priority');

        // set subject
        $crud->set_subject('Column');

        // displayed columns on list, edit, and add, uncomment to use
        //$crud->edit_fields('table_id', 'name', 'caption', 'data_type', 'data_size', 'role', 'lookup_table_id', 'lookup_column_id', 'relation_table_id', 'relation_table_column_id', 'relation_selection_column_id', 'relation_priority_column_id', 'selection_table_id', 'selection_column_id', 'priority', 'value_selection_mode', 'value_selection_item', 'column_option');
        //$crud->edit_fields('table_id', 'name', 'caption', 'data_type', 'data_size', 'role', 'lookup_table_id', 'lookup_column_id', 'relation_table_id', 'relation_table_column_id', 'relation_selection_column_id', 'relation_priority_column_id', 'selection_table_id', 'selection_column_id', 'priority', 'value_selection_mode', 'value_selection_item', 'column_option', '_updated_by', '_updated_at');
        //$crud->add_fields('table_id', 'name', 'caption', 'data_type', 'data_size', 'role', 'lookup_table_id', 'lookup_column_id', 'relation_table_id', 'relation_table_column_id', 'relation_selection_column_id', 'relation_priority_column_id', 'selection_table_id', 'selection_column_id', 'priority', 'value_selection_mode', 'value_selection_item', 'column_option', '_created_by', '_created_at');
        //$crud->set_read_fields('table_id', 'name', 'caption', 'data_type', 'data_size', 'role', 'lookup_table_id', 'lookup_column_id', 'relation_table_id', 'relation_table_column_id', 'relation_selection_column_id', 'relation_priority_column_id', 'selection_table_id', 'selection_column_id', 'priority', 'value_selection_mode', 'value_selection_item', 'column_option');

        // caption of each columns
        $crud->display_as('table_id','Table');
        $crud->display_as('name','Name');
        $crud->display_as('caption','Caption');
        $crud->display_as('data_type','Data Type');
        $crud->display_as('data_size','Data Size');
        $crud->display_as('role','Role');
        $crud->display_as('lookup_table_id','Lookup Table');
        $crud->display_as('lookup_column_id','Lookup Column');
        $crud->display_as('relation_table_id','Relation Table');
        $crud->display_as('relation_table_column_id','Relation Table Column');
        $crud->display_as('relation_selection_column_id','Relation Selection Column');
        $crud->display_as('relation_priority_column_id','Relation Ordering Column');
        $crud->display_as('selection_table_id','Selection Table');
        $crud->display_as('selection_column_id','Selection Column');
        $crud->display_as('priority','Priority');
        $crud->display_as('value_selection_mode','Value Selection Mode');
        $crud->display_as('value_selection_item','Value Selection Item');
        $crud->display_as('column_option','Options');

        ////////////////////////////////////////////////////////////////////////
        // This function will automatically detect every methods in this controller and link it to corresponding column
        // if the name is match by convention. In other word, you don't need to manually define callback.
        // Here is the convention (replace COLUMN_NAME with your column's name)
        //
        // * callback column (called when viewing the data as list):
        //      public function _callback_column_COLUMN_NAME($value, $row){}
        //
        // * callback field (called when show add and edit form):
        //      public function _callback_field_COLUMN_NAME($value, $primary_key){}
        //
        // * validation rule callback (field validation when adding/editing data)
        //      public function COLUMN_NAME_validation($value){}
        ////////////////////////////////////////////////////////////////////////
        $this->build_default_callback();

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put required field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/required_fields)
        // eg:
        //      $crud->required_fields( $field1, $field2, $field3, ... );
        ////////////////////////////////////////////////////////////////////////
        if($table_id == NULL){
            $crud->required_fields('table_id', 'name', 'caption');
        }else{
            $crud->required_fields('name', 'caption');
        }

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put required field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/unique_fields)
        // eg:
        //      $crud->unique_fields( $field1, $field2, $field3, ... );
        ////////////////////////////////////////////////////////////////////////


        ////////////////////////////////////////////////////////////////////////
        // HINT: Put field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_rules)
        // eg:
        //      $crud->set_rules( $field_name , $caption, $filter );
        ////////////////////////////////////////////////////////////////////////


        ////////////////////////////////////////////////////////////////////////
        // HINT: Put set relation (lookup) codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation)
        // eg:
        //      $crud->set_relation( $field_name , $related_table, $related_title_field , $where , $order_by );
        ////////////////////////////////////////////////////////////////////////
        $crud->set_relation('lookup_table_id', $this->t('table'), 'name');
        $crud->set_relation('lookup_column_id', $this->t('column'), 'name');
        $crud->set_relation('relation_table_id', $this->t('table'), 'name');
        $crud->set_relation('relation_table_column_id', $this->t('column'), 'name');
        $crud->set_relation('relation_selection_column_id', $this->t('column'), 'name');
        $crud->set_relation('relation_priority_column_id', $this->t('column'), 'name');
        $crud->set_relation('selection_table_id', $this->t('table'), 'name');
        $crud->set_relation('selection_column_id', $this->t('column'), 'name');

        if($table_id == NULL){
            $crud->set_relation('table_id', $this->t('table'), 'name');
        }else{
            $crud->field_type('table_id', 'hidden', $table_id);
        }

        $crud->set_field_half_width(array('name', 'caption'));

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put set relation_n_n (detail many to many) codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation_n_n)
        // eg:
        //      $crud->set_relation_n_n( $field_name, $relation_table, $selection_table, $primary_key_alias_to_this_table,
        //          $primary_key_alias_to_selection_table , $title_field_selection_table, $priority_field_relation );
        ////////////////////////////////////////////////////////////////////////
        $crud->set_relation_n_n('column_option',$this->t('column_option'),
            $this->t('template_option'),'column_id','option_id','name',
            null, array('option_type'=>'column'));

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put custom field type here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/field_type)
        // eg:
        //      $crud->field_type( $field_name , $field_type, $value  );
        ////////////////////////////////////////////////////////////////////////
        $crud->field_type('data_type', 'enum', $this->nds_model->available_data_type);
        $crud->field_type('role', 'enum', array('primary','lookup','detail many to many','detail one to many'));
        $crud->field_type('value_selection_mode', 'enum', array('set','enum'));
        $crud->field_type('priority', 'hidden');


        ////////////////////////////////////////////////////////////////////////
        // HINT: Put Tabs (if needed)
        // usage:
        //     $crud->set_outside_tab($how_many_field_outside_tab);
        //     $crud->set_tabs(array(
        //        'First Tab Caption'  => $how_many_field_on_first_tab,
        //        'Second Tab Caption' => $how_many_field_on_second_tab,
        //     ));
        ////////////////////////////////////////////////////////////////////////

        ////////////////////////////////////////////////////////////////////////
        // HINT: Create custom search form (if needed)
        // usage:
        //     $crud->unset_default_search();
        //     // Your custom form
        //     $html =  '<div class="row container col-md-12" style="margin-bottom:10px;">';
        //     $html .= '</div>';
        //     $html .= '<input name="keyword" placeholder="Keyword" value="'.$keyword.'" /> &nbsp;';
        //     $html .= '<input type="button" value="Search" class="crud_search btn btn-primary form-control" id="crud_search" />';
        //     $crud->set_search_form_components($html);
        ////////////////////////////////////////////////////////////////////////



        ////////////////////////////////////////////////////////////////////////
        // HINT: Put callback here
        // (documentation: httm://www.grocerycrud.com/documentation/options_functions)
        ////////////////////////////////////////////////////////////////////////


        ////////////////////////////////////////////////////////////////////////
        // HINT: Put custom error message here
        // (documentation: httm://www.grocerycrud.com/documentation/set_lang_string)
        ////////////////////////////////////////////////////////////////////////
        // $crud->set_lang_string('delete_error_message', 'Cannot delete the record');
        // $crud->set_lang_string('update_error',         'Cannot edit the record'  );
        // $crud->set_lang_string('insert_error',         'Cannot add the record'   );

        $this->CRUD = $crud;
        return $crud;
    }

    public function index($table_id = NULL){
        // create crud
        if(!is_numeric($table_id)){
            $table_id = NULL;
        }
        $crud = $this->make_crud($table_id);

        // render
        $render = $this->render_crud($crud);
        $output = $render['output'];
        $config = $render['config'];

        if(isset($table_id) && is_numeric($table_id)){
            $query = $this->db->select('project_id')->from($this->t('table'))->where('table_id',$table_id)->get();
            $row = $query->row();
            $project_id = $row->project_id;
            $output->project_id = $project_id;
            $output->project_name = $this->nds_model->get_project_name($project_id);
            $output->table_id = $table_id;
            $output->table_name = $this->nds_model->get_table_name($table_id);
        }

        // show the view
        $this->view($this->cms_module_path().'/Manage_column_view', $output,
            $this->n('manage_column'), $config);
    }

    public function column_mark_move($table_id, $column_id){
        if(!isset($_SESSION) || !array_key_exists('__mark_move_column_id', $_SESSION)){
            $_SESSION['__mark_move_column_id'] = array();
        }
        $_SESSION['__mark_move_column_id'][$table_id] = $column_id;
        redirect($this->cms_module_path().'/manage_column/index/'.$table_id,'refresh');
    }

    public function column_move_cancel($table_id){
        if(!isset($_SESSION) || !array_key_exists('__mark_move_column_id', $_SESSION)){
            $_SESSION['__mark_move_column_id'] = array();
        }
        $column_id = $_SESSION['__mark_move_column_id'][$table_id];
        $_SESSION['__mark_move_column_id'][$table_id] = NULL;
        redirect($this->cms_module_path().'/manage_column/index/'.$table_id,'refresh');
    }

    public function column_move_before($table_id, $dst_column_id){
        if(!isset($_SESSION) || !array_key_exists('__mark_move_column_id', $_SESSION)){
            $_SESSION['__mark_move_column_id'] = array();
        }
        $src_column_id = $_SESSION['__mark_move_column_id'][$table_id];
        $this->do_move_column_before($table_id, $src_column_id, $dst_column_id);
        $_SESSION['__mark_move_column_id'][$table_id] = NULL;
        redirect($this->cms_module_path().'/manage_column/index/'.$table_id,'refresh');
    }
    public function column_move_after($table_id, $dst_column_id){
        if(!isset($_SESSION) || !array_key_exists('__mark_move_column_id', $_SESSION)){
            $_SESSION['__mark_move_column_id'] = array();
        }
        $src_column_id = $_SESSION['__mark_move_column_id'][$table_id];
        $this->do_move_column_after($table_id, $src_column_id, $dst_column_id);
        $_SESSION['__mark_move_column_id'][$table_id] = NULL;
        redirect($this->cms_module_path().'/manage_column/index/'.$table_id,'refresh');
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

    public function _callback_column_name($value, $row){
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
                $html .= '<br /><a href="'.site_url($this->cms_module_path().'/manage_column/column_move_cancel/'.$row->table_id).'"><i class="glyphicon glyphicon-repeat"></i> Undo</a>';
            }else{
                // paste before, paste after, paste inside
                $html .= '<br /><a href="'.site_url($this->cms_module_path().'/manage_column/column_move_before/'.$row->table_id.'/'.$row->column_id).'"><i class="glyphicon glyphicon-open"></i> Put Before</a>';
                $html .= ' | <a href="'.site_url($this->cms_module_path().'/manage_column/column_move_after/'.$row->table_id.'/'.$row->column_id).'"><i class="glyphicon glyphicon-save"></i> Put After</a>';
            }
        }else{
            $html .= '<br /><a href="'.site_url($this->cms_module_path().'/manage_column/column_mark_move/'.$row->table_id.'/'.$row->column_id).'"><i class="glyphicon glyphicon-share-alt"></i> Move</a>';
        }

        return $html;
    }


    public function _after_insert_or_update($post_array, $primary_key){
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

    public function _before_insert_or_update($post_array, $primary_key=NULL){
        return $post_array;
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
        if($post_array['priority'] == '' || $post_array['priority'] == NULL){
            $post_array['priority'] = 5000;
        }
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
        $this->nds_model->before_delete_column($primary_key);
        return TRUE;
    }

    public function _after_delete($primary_key){
        return TRUE;
    }

}
