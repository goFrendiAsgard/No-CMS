<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$core_dirname = dirname(dirname(__FILE__));
if(substr($core_dirname,-1) != '/'){
    $core_dirname .= '/';
}
$core_dirname .= 'core/';
include($core_dirname.'Nds_Special_Crud_Controller.php');
/**
 * Description of Manage_table
 *
 * @author No-CMS Module Generator
 */
class Manage_table extends Nds_Special_CRUD_Controller {

    protected $URL_MAP = array();
    protected $TABLE_NAME = 'table';
    protected $COLUMN_NAMES = array('project_id', 'name', 'caption', 'priority', 'data', 'column', 'table_option');
    protected $PRIMARY_KEY = 'table_id';
    protected $UNSET_JQUERY = TRUE;
    protected $UNSET_READ = TRUE;
    protected $UNSET_ADD = FALSE;
    protected $UNSET_EDIT = FALSE;
    protected $UNSET_DELETE = FALSE;
    protected $UNSET_LIST = FALSE;
    protected $UNSET_BACK_TO_LIST = FALSE;
    protected $UNSET_PRINT = FALSE;
    protected $UNSET_EXPORT = FALSE;

    protected function make_crud($project_id = NULL){
        $crud = parent::make_crud();

        ////////////////////////////////////////////////////////////////////////
        // HINT: You can access this variables after calling parent's make_crud method:
        //      $this->CRUD
        //      $this->STATE
        //      $this->STATE_INFO
        //      $this->PK_VALUE
        ////////////////////////////////////////////////////////////////////////

        if($project_id != NULL){
            $crud->where($this->t('table').'.project_id', $project_id);
            // displayed columns on list
            $crud->columns('name', 'table_option', 'column');
        }else{
            $crud->columns('project_id', 'name', 'table_option', 'column');
        }
        $crud->order_by('project_id');
        $crud->order_by('priority');

        // set subject
        $crud->set_subject('Table');

        // displayed columns on list, edit, and add, uncomment to use
        //$crud->columns('project_id', 'name', 'caption', 'priority', 'data', 'column', 'table_option');
        $crud->edit_fields('project_id', 'name', 'caption', 'table_option', 'priority', 'data', 'column', '_updated_by', '_updated_at');
        $crud->add_fields('project_id', 'name', 'caption', 'table_option', 'priority', 'data', 'column', '_created_by', '_created_at');
        //$crud->set_read_fields('project_id', 'name', 'caption', 'priority', 'data', 'column', 'table_option');

        // caption of each columns
        $crud->display_as('project_id','Project');
        $crud->display_as('name','Name');
        $crud->display_as('caption','Caption');
        $crud->display_as('priority','Priority');
        $crud->display_as('data','Data (JSON Format)');
        $crud->display_as('column','Column');
        $crud->display_as('table_option','Table Option');

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
        if($project_id == NULL){
            $crud->required_fields('project_id', 'name', 'caption');
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
        if($project_id == NULL){
            $crud->set_relation('project_id', $this->t('project'), 'name');
        }else{
            $crud->field_type('project_id', 'hidden', $project_id);
        }

        $crud->set_field_half_width(array('name', 'caption'));


        ////////////////////////////////////////////////////////////////////////
        // HINT: Put set relation_n_n (detail many to many) codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation_n_n)
        // eg:
        //      $crud->set_relation_n_n( $field_name, $relation_table, $selection_table, $primary_key_alias_to_this_table,
        //          $primary_key_alias_to_selection_table , $title_field_selection_table, $priority_field_relation );
        ////////////////////////////////////////////////////////////////////////
        $crud->set_relation_n_n('table_option',$this->t('table_option'),
            $this->t('template_option'),'table_id','option_id','name',
            null, array('option_type'=>'table'));

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put custom field type here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/field_type)
        // eg:
        //      $crud->field_type( $field_name , $field_type, $value  );
        ////////////////////////////////////////////////////////////////////////
        $crud->field_type('priority', 'hidden');
        $crud->unset_texteditor('data');



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

    public function index($project_id=NULL){
        // create crud
        if(!is_numeric($project_id)){
            $project_id = NULL;
        }
        $crud = $this->make_crud($project_id);

        // render
        $render = $this->render_crud($crud);
        $output = $render['output'];
        $config = $render['config'];

        if(isset($project_id) && is_numeric($project_id)){
            $output->project_id = $project_id;
            $output->project_name = $this->nds_model->get_project_name($project_id);
        }

        // show the view
        $this->view($this->cms_module_path().'/Manage_table_view', $output,
            $this->n('manage_table'), $config);
    }

    public function _callback_column_name($value, $row){
        $html = '<b>' . $value . '</b>' . br() . '(' . $row->caption . ')<a id="rec-'.$row->table_id.'" name="rec-'.$row->table_id.'">&nbsp;</a>';

        if(isset($_SESSION['__mark_move_table_id'][$row->project_id]) && $_SESSION['__mark_move_table_id'][$row->project_id] != NULL){
            $mark_move_table_id = $_SESSION['__mark_move_table_id'][$row->project_id];
            if($row->table_id == $mark_move_table_id){
                // cancel link
                $html .= '<br /><a href="'.site_url($this->cms_module_path().'/manage_table/table_move_cancel/'.$row->project_id).'"><i class="glyphicon glyphicon-repeat"></i> Undo</a>';
            }else{
                // paste before, paste after, paste inside
                $html .= ' <br /><a href="'.site_url($this->cms_module_path().'/manage_table/table_move_before/'.$row->project_id.'/'.$row->table_id).'"><i class="glyphicon glyphicon-open"></i> Put Before</a>';
                $html .= ' | <a href="'.site_url($this->cms_module_path().'/manage_table/table_move_after/'.$row->project_id.'/'.$row->table_id).'"><i class="glyphicon glyphicon-save"></i> Put After</a>';
            }
        }else{
            $html .= '<br /><a href="'.site_url($this->cms_module_path().'/manage_table/table_mark_move/'.$row->project_id.'/'.$row->table_id).'"><i class="glyphicon glyphicon-share-alt"></i> Move</a>';
        }

        return $html;
    }

    // returned on insert and edit
    public function _callback_field_column($value, $primary_key){
        // Options for detail table's column with SET type
        $set_column_option_list = array();
        // Options for detail table's column with ENUM type
        $enum_column_option_list = array();
        // Detail table's one-to-many columns configurations
        $lookup_config_list = array();
        // Detail table's many-to-many columns configurations
        $many_to_many_config_list = array(
            'column_option' => array(
                'selection_table'           => 'template_option',
                'selection_pk_column'       => 'option_id',
                'selection_lookup_column'   => 'name',
                'relation_table'            => 'column_option',
                'relation_column'           => 'column_id',
                'relation_selection_column' => 'option_id',
            ),
        );
        // Prepare the data by using defined configurations and options
        $data = $this->_one_to_many_callback_field_data(
                'column', // DETAIL TABLE NAME
                'column_id', // DETAIL PK NAME
                'table_id', // DETAIL FK NAME
                $primary_key, // CURRENT TABLE PK VALUE
                $lookup_config_list, // LOOKUP CONFIGS
                $many_to_many_config_list, // MANY TO MANY CONFIGS
                $set_column_option_list, // SET OPTIONS
                $enum_column_option_list // ENUM OPTIONS
            );
        $data['primary_key'] = $primary_key;
        // re-do the table option
        $template_option_list = $this->cms_get_record_list($this->t('template_option'), 'option_type', 'column');
        $data['options']['column_option'] = array();
        foreach($template_option_list as $template_option){
            $data['options']['column_option'][] = array(
                'value' => $template_option->option_id,
                'caption' => $template_option->name,
            );
        }
        // Parse the data to the view
        return $this->load->view($this->cms_module_path().'/field_table_column',$data, TRUE);
    }

    // returned on view
    public function _callback_column_column($value, $row){
        $primary_key = $row->table_id;
        $url = site_url($this->cms_module_path().'/manage_column/index');
        $caption = 'Column';
        $result = $this->nds_model->get_column_by_table($primary_key);
        $action = $this->get_detail_action($caption, $url, $primary_key, FALSE);
        return $action.br().$this->get_detail_data($result, $url, $primary_key, 'column_id');
    }

    public function _after_insert_or_update($post_array, $primary_key){
        // SAVE CHANGES OF column
        $data = json_decode($this->input->post('md_real_field_column_col'), TRUE);
        $this->_save_one_to_many(
            'column', // FIELD NAME
            'column', // DETAIL TABLE NAME
            'column_id', // DETAIL PK NAME
            'table_id', // DETAIL FK NAME
            $primary_key, // PARENT PRIMARY KEY VALUE
            $data, // DATA
            array('column_id', 'name', 'caption', 'data_type', 'data_size', 'priority'), // REAL DETAIL COLUMN NAMES
            array(), // SET DETAIL COLUMN NAMES
            array( // MANY TO MANY CONFIG LIST
                'column_option' => array(
                    'relation_table' => 'column_option',
                    'relation_column' => 'column_id',
                    'relation_selection_column' => 'option_id',
                ),
            ) // MANY TO MANY CONFIG LIST
        );

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
        $this->_reorder_column($primary_key);

        return TRUE;
    }

    public function table_mark_move($project_id, $table_id){
        if(!isset($_SESSION) || !array_key_exists('__mark_move_table_id', $_SESSION)){
            $_SESSION['__mark_move_table_id'] = array();
        }
        $_SESSION['__mark_move_table_id'][$project_id] = $table_id;
        redirect($this->cms_module_path().'/manage_table/index/'.$project_id,'refresh');
    }

    public function table_move_cancel($project_id){
        if(!isset($_SESSION) || !array_key_exists('__mark_move_table_id', $_SESSION)){
            $_SESSION['__mark_move_table_id'] = array();
        }
        $table_id = $_SESSION['__mark_move_table_id'][$project_id];
        $_SESSION['__mark_move_table_id'][$project_id] = NULL;
        redirect($this->cms_module_path().'/manage_table/index/'.$project_id,'refresh');
    }

    public function table_move_before($project_id, $dst_table_id){
        if(!isset($_SESSION) || !array_key_exists('__mark_move_table_id', $_SESSION)){
            $_SESSION['__mark_move_table_id'] = array();
        }
        $src_table_id = $_SESSION['__mark_move_table_id'][$project_id];
        $this->do_move_table_before($project_id, $src_table_id, $dst_table_id);
        $_SESSION['__mark_move_table_id'][$project_id] = NULL;
        redirect($this->cms_module_path().'/manage_table/index/'.$project_id,'refresh');
    }
    public function table_move_after($project_id, $dst_table_id){
        if(!isset($_SESSION) || !array_key_exists('__mark_move_table_id', $_SESSION)){
            $_SESSION['__mark_move_table_id'] = array();
        }
        $src_table_id = $_SESSION['__mark_move_table_id'][$project_id];
        $this->do_move_table_after($project_id, $src_table_id, $dst_table_id);
        $_SESSION['__mark_move_table_id'][$project_id] = NULL;
        redirect($this->cms_module_path().'/manage_table/index/'.$project_id,'refresh');
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
        $this->nds_model->before_delete_table($primary_key);
        return TRUE;
    }

    public function _after_delete($primary_key){
        return TRUE;
    }

}
