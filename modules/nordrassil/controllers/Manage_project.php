<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$core_dirname = dirname(dirname(__FILE__));
if(substr($core_dirname,-1) != '/'){
    $core_dirname .= '/';
}
$core_dirname .= 'core/';
include($core_dirname.'Nds_Special_Crud_Controller.php');
/**
 * Description of Manage_project
 *
 * @author No-CMS Module Generator
 */
class Manage_project extends Nds_Special_CRUD_Controller {

    protected $URL_MAP = array();
    protected $TABLE_NAME = 'project';
    protected $COLUMN_NAMES = array('name', 'template_id', 'db_server', 'db_port', 'db_schema', 'db_user', 'db_password', 'db_table_prefix', 'project_option', 'table');
    protected $PRIMARY_KEY = 'project_id';
    protected $UNSET_JQUERY = TRUE;
    protected $UNSET_READ = TRUE;
    protected $UNSET_ADD = FALSE;
    protected $UNSET_EDIT = FALSE;
    protected $UNSET_DELETE = FALSE;
    protected $UNSET_LIST = FALSE;
    protected $UNSET_BACK_TO_LIST = FALSE;
    protected $UNSET_PRINT = FALSE;
    protected $UNSET_EXPORT = FALSE;

    protected function make_crud(){
        $crud = parent::make_crud();

        ////////////////////////////////////////////////////////////////////////
        // HINT: You can access this variables after calling parent's make_crud method:
        //      $this->CRUD
        //      $this->STATE
        //      $this->STATE_INFO
        //      $this->PK_VALUE
        ////////////////////////////////////////////////////////////////////////

        // set subject
        $crud->set_subject('Project');

        $crud->add_action('Export','glyphicon glyphicon-export',
            $this->cms_module_path().'/manage_project/get_seed', 'btn btn-default blank');

        $crud->add_action('Synchronize','glyphicon glyphicon-repeat',
            $this->cms_module_path().'/manage_project/resynchronize_from_db', 'btn btn-default blank');

        // displayed columns on list, edit, and add, uncomment to use
        $crud->columns('name', 'project_option', 'table');
        //$crud->edit_fields('template_id', 'name', 'db_server', 'db_port', 'db_schema', 'db_user', 'db_password', 'db_table_prefix', 'table', 'project_option', '_updated_by', '_updated_at');
        //$crud->add_fields('template_id', 'name', 'db_server', 'db_port', 'db_schema', 'db_user', 'db_password', 'db_table_prefix', 'table', 'project_option', '_created_by', '_created_at');
        //$crud->set_read_fields('template_id', 'name', 'db_server', 'db_port', 'db_schema', 'db_user', 'db_password', 'db_table_prefix', 'table', 'project_option');

        // caption of each columns
        $crud->display_as('template_id','Template');
        $crud->display_as('name','Name');
        $crud->display_as('db_server','Database Server');
        $crud->display_as('db_port','Database Port');
        $crud->display_as('db_schema','Database Schema');
        $crud->display_as('db_user','Database User');
        $crud->display_as('db_password','Database Password');
        $crud->display_as('db_table_prefix','Table Prefix (For Filtering)');
        $crud->display_as('table','Tables');
        $crud->display_as('project_option','Options');

        $crud->set_field_one_third_width(array('db_server', 'db_port', 'db_schema', 'db_user', 'db_password', 'db_table_prefix'));

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
        $crud->required_fields('template_id', 'name');

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put required field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/unique_fields)
        // eg:
        //      $crud->unique_fields( $field1, $field2, $field3, ... );
        ////////////////////////////////////////////////////////////////////////
        $crud->unique_fields('name');

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
        $crud->set_relation('template_id', $this->t('template'), 'name');

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put set relation_n_n (detail many to many) codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation_n_n)
        // eg:
        //      $crud->set_relation_n_n( $field_name, $relation_table, $selection_table, $primary_key_alias_to_this_table,
        //          $primary_key_alias_to_selection_table , $title_field_selection_table, $priority_field_relation );
        ////////////////////////////////////////////////////////////////////////
        $crud->set_relation_n_n('project_option',$this->t('project_option'),
            $this->t('template_option'),'project_id','option_id','name',
            null, array('option_type'=>'project'));

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put custom field type here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/field_type)
        // eg:
        //      $crud->field_type( $field_name , $field_type, $value  );
        ////////////////////////////////////////////////////////////////////////
        $crud->set_field_half_width(array('template_id', 'name'));



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

    public function index(){
        // create crud
        $crud = $this->make_crud();

        // render
        $render = $this->render_crud($crud);
        $output = $render['output'];
        $config = $render['config'];

        // show the view
        $this->view($this->cms_module_path().'/Manage_project_view', $output,
            $this->n('manage_project'), $config);
    }

    public function resynchronize_from_db($project_id){
        $this->synchronize_model->synchronize($project_id);
        redirect(site_url($this->cms_module_path().'/manage_project/index/edit/'.$project_id));
    }

    public function get_seed($project_id){
        $this->load->helper('inflector');
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

        echo $this->nds_model->get_seed($project_id);
    }

    public function _callback_column_name($value, $row){
        $template_row = $this->cms_get_record($this->t('template'), 'template_id', $row->template_id);
        $template_name = '';
        if($template_row != NULL){
            $template_name = $template_row->name;
        }
        return '<b>'.$value.'</b>'.br().$template_name;
    }


    // returned on insert and edit
    public function _callback_field_table($value, $primary_key){
        // Options for detail table's column with SET type
        $set_column_option_list = array();
        // Options for detail table's column with ENUM type
        $enum_column_option_list = array();
        // Detail table's one-to-many columns configurations
        $lookup_config_list = array();
        // Detail table's many-to-many columns configurations
        $many_to_many_config_list = array(
            'table_option' => array(
                'selection_table'           => 'template_option',
                'selection_pk_column'       => 'option_id',
                'selection_lookup_column'   => 'name',
                'relation_table'            => 'table_option',
                'relation_column'           => 'table_id',
                'relation_selection_column' => 'option_id',
            ),
        );
        // Prepare the data by using defined configurations and options
        $this->db->order_by('priority');
        $data = $this->_one_to_many_callback_field_data(
                'table', // DETAIL TABLE NAME
                'table_id', // DETAIL PK NAME
                'project_id', // DETAIL FK NAME
                $primary_key, // CURRENT TABLE PK VALUE
                $lookup_config_list, // LOOKUP CONFIGS
                $many_to_many_config_list, // MANY TO MANY CONFIGS
                $set_column_option_list, // SET OPTIONS
                $enum_column_option_list // ENUM OPTIONS
            );
        $data['primary_key'] = $primary_key;
        // re-do the table option
        $template_option_list = $this->cms_get_record_list($this->t('template_option'), 'option_type', 'table');
        $data['options']['table_option'] = array();
        foreach($template_option_list as $template_option){
            $data['options']['table_option'][] = array(
                'value' => $template_option->option_id,
                'caption' => $template_option->name,
            );
        }
        // Parse the data to the view
        return $this->load->view($this->cms_module_path().'/field_project_table',$data, TRUE);
    }

    // returned on view
    public function _callback_column_table($value, $row){
        $primary_key = $row->project_id;
        $url = site_url($this->cms_module_path().'/manage_table/index');
        $caption = 'Table';
        $action =  $this->get_detail_action($caption, $url, $primary_key, FALSE);
        $result = $this->nds_model->get_table_by_project($primary_key);
        return $action.br().$this->get_detail_data($result, $url, $primary_key, 'table_id');
    }


    public function _after_insert_or_update($post_array, $primary_key){
        // SAVE CHANGES OF table
        $data = json_decode($this->input->post('md_real_field_table_col'), TRUE);
        $this->_save_one_to_many(
            'table', // FIELD NAME
            'table', // DETAIL TABLE NAME
            'table_id', // DETAIL PK NAME
            'project_id', // DETAIL FK NAME
            $primary_key, // PARENT PRIMARY KEY VALUE
            $data, // DATA
            array('table_id', 'name', 'caption', 'priority'), // REAL DETAIL COLUMN NAMES
            array(), // SET DETAIL COLUMN NAMES
            array( // MANY TO MANY CONFIG LIST
                'table_option' => array(
                    'relation_table' => 'table_option',
                    'relation_column' => 'table_id',
                    'relation_selection_column' => 'option_id',
                ),
            )
        );

        $this->_reorder_table($primary_key);
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
        return $post_array;
    }

    public function _after_insert($post_array, $primary_key){
        set_time_limit(60);
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
        return TRUE;
    }

    public function _before_update($post_array, $primary_key){
        return $post_array;
    }

    public function _after_update($post_array, $primary_key){
        return TRUE;
    }

    public function _before_delete($primary_key){
        $this->nds_model->before_delete_project($primary_key);
        return TRUE;
    }

    public function _after_delete($primary_key){
        return TRUE;
    }

}
