<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include(FCPATH.'modules/main/core/CMS_Predefined_Callback_CRUD_Controller.php');

/**
 * Description of Manage_user
 *
 * @author No-CMS Module Generator
 */
class Manage_user extends CMS_Predefined_Callback_CRUD_Controller {

    protected $URL_MAP = array();
    protected $TABLE_NAME = 'main_user';
    protected $COLUMN_NAMES = array('user_name', 'email', 'password', 'activation_code', 'real_name', 'active', 'auth_OpenID', 'auth_Facebook', 'auth_Twitter', 'auth_Google', 'auth_Yahoo', 'auth_LinkedIn', 'auth_MySpace', 'auth_Foursquare', 'auth_AOL', 'auth_Live', 'language', 'theme', 'birthdate', 'sex', 'profile_picture', 'self_description', 'last_active', 'login', 'subsite', 'group_user');
    protected $PRIMARY_KEY = 'user_id';
    protected $UNSET_JQUERY = TRUE;
    protected $UNSET_READ = TRUE;
    protected $UNSET_ADD = FALSE;
    protected $UNSET_EDIT = FALSE;
    protected $UNSET_DELETE = FALSE;
    protected $UNSET_LIST = FALSE;
    protected $UNSET_BACK_TO_LIST = FALSE;
    protected $UNSET_PRINT = FALSE;
    protected $UNSET_EXPORT = FALSE;

    protected function cms_complete_table_name($table_name){
        if($table_name == 'main_user'){
            return $this->cms_user_table_name();
        }else{
            return parent::cms_complete_table_name($table_name);
        }

    }

    protected function make_crud(){
        $crud = parent::make_crud();

        if (CMS_SUBSITE == '') {
            $crud->where('(subsite is NULL OR subsite = \'\')');
        } else {
            $crud->where('(subsite is NULL OR subsite=\''.addslashes(CMS_SUBSITE).'\')');
        }
        $crud->order_by('subsite', 'desc');
        $crud->set_subject('User');

        ////////////////////////////////////////////////////////////////////////
        // HINT: You can access this variables after calling parent's make_crud method:
        //      $this->CRUD
        //      $this->STATE
        //      $this->STATE_INFO
        //      $this->PK_VALUE
        ////////////////////////////////////////////////////////////////////////

        // set subject
        $crud->set_subject('User');

        // displayed columns on list, edit, and add, uncomment to use
        $crud->columns('user_name', 'email', 'real_name', 'active', 'group_user');
        $crud->edit_fields('user_name', 'email', 'real_name', 'active', 'group_user', 'raw_password', '_updated_by', '_updated_at');
        $crud->add_fields('user_name', 'email', 'password', 'real_name', 'active', 'group_user', 'subsite', '_created_by', '_created_at');
        //$crud->columns('user_name', 'email', 'password', 'activation_code', 'real_name', 'active', 'auth_OpenID', 'auth_Facebook', 'auth_Twitter', 'auth_Google', 'auth_Yahoo', 'auth_LinkedIn', 'auth_MySpace', 'auth_Foursquare', 'auth_AOL', 'auth_Live', 'language', 'theme', 'birthdate', 'sex', 'profile_picture', 'self_description', 'last_active', 'login', 'subsite', 'module', 'group_user');
        //$crud->edit_fields('user_name', 'email', 'password', 'activation_code', 'real_name', 'active', 'auth_OpenID', 'auth_Facebook', 'auth_Twitter', 'auth_Google', 'auth_Yahoo', 'auth_LinkedIn', 'auth_MySpace', 'auth_Foursquare', 'auth_AOL', 'auth_Live', 'language', 'theme', 'birthdate', 'sex', 'profile_picture', 'self_description', 'last_active', 'login', 'subsite', 'module', 'group_user', '_updated_by', '_updated_at');
        //$crud->add_fields('user_name', 'email', 'password', 'activation_code', 'real_name', 'active', 'auth_OpenID', 'auth_Facebook', 'auth_Twitter', 'auth_Google', 'auth_Yahoo', 'auth_LinkedIn', 'auth_MySpace', 'auth_Foursquare', 'auth_AOL', 'auth_Live', 'language', 'theme', 'birthdate', 'sex', 'profile_picture', 'self_description', 'last_active', 'login', 'subsite', 'module', 'group_user', '_created_by', '_created_at');
        //$crud->set_read_fields('user_name', 'email', 'password', 'activation_code', 'real_name', 'active', 'auth_OpenID', 'auth_Facebook', 'auth_Twitter', 'auth_Google', 'auth_Yahoo', 'auth_LinkedIn', 'auth_MySpace', 'auth_Foursquare', 'auth_AOL', 'auth_Live', 'language', 'theme', 'birthdate', 'sex', 'profile_picture', 'self_description', 'last_active', 'login', 'subsite', 'module', 'group_user');

        // caption of each columns
        $crud->display_as('user_name','User Name');
        $crud->display_as('email','Email');
        $crud->display_as('password','Password');
        $crud->display_as('activation_code','Activation Code');
        $crud->display_as('real_name','Real Name');
        $crud->display_as('active','Active');
        $crud->display_as('auth_OpenID','Auth Open ID');
        $crud->display_as('auth_Facebook','Auth Facebook');
        $crud->display_as('auth_Twitter','Auth Twitter');
        $crud->display_as('auth_Google','Auth Google');
        $crud->display_as('auth_Yahoo','Auth Yahoo');
        $crud->display_as('auth_LinkedIn','Auth Linked In');
        $crud->display_as('auth_MySpace','Auth My Space');
        $crud->display_as('auth_Foursquare','Auth Foursquare');
        $crud->display_as('auth_AOL','Auth AOL');
        $crud->display_as('auth_Live','Auth Live');
        $crud->display_as('language','Language');
        $crud->display_as('theme','Theme');
        $crud->display_as('birthdate','Birthdate');
        $crud->display_as('sex','Sex');
        $crud->display_as('profile_picture','Profile Picture');
        $crud->display_as('self_description','Self Description');
        $crud->display_as('last_active','Last Active');
        $crud->display_as('login','Login');
        $crud->display_as('subsite','Subsite');
        $crud->display_as('module','Module');
        $crud->display_as('group_user','Group User');

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
        $crud->required_fields('password');

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put required field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/unique_fields)
        // eg:
        //      $crud->unique_fields( $field1, $field2, $field3, ... );
        ////////////////////////////////////////////////////////////////////////
        $crud->unique_fields('user_name', 'email');

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


        ////////////////////////////////////////////////////////////////////////
        // HINT: Put set relation_n_n (detail many to many) codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation_n_n)
        // eg:
        //      $crud->set_relation_n_n( $field_name, $relation_table, $selection_table, $primary_key_alias_to_this_table,
        //          $primary_key_alias_to_selection_table , $title_field_selection_table, $priority_field_relation );
        ////////////////////////////////////////////////////////////////////////
        $crud->set_relation_n_n('group_user',
            $this->t('main_group_user'),
            $this->t('main_group'),
            'user_id', 'group_id',
            'group_name', NULL);

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put custom field type here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/field_type)
        // eg:
        //      $crud->field_type( $field_name , $field_type, $value  );
        ////////////////////////////////////////////////////////////////////////
        $crud->field_type('active', 'true_false');
        $crud->field_type('subsite', 'hidden');

        if ($crud->getState() == 'edit') {
            $state_info = $crud->getStateInfo();
            $primary_key = $state_info->primary_key;

            // get old data
            $query = $this->db->select('user_id, user_name, subsite')
                ->from($this->cms_user_table_name())
                ->where('user_id', $primary_key)
                ->get();
            $user_row = $query->row();

            // if the user is not belonged to this site, then the site admin
            // is only able to modify group
            if($user_row->subsite != CMS_SUBSITE){
                $crud->edit_fields('user_name', 'active', 'group_user');

                $crud->callback_edit_field('user_name', array(
                    $this,
                    '_callback_field_read_only_user_name',
                ));
            }

            // if the user is not belonged to this site, or is current user, or is super admin,
            // then can't be deactivated
            if ($primary_key == $this->cms_user_id() || $primary_key == 1 || $user_row->subsite != CMS_SUBSITE) {
                $crud->callback_edit_field('active', array(
                    $this,
                    '_callback_field_read_only_active',
                ));
            }

        }



        ////////////////////////////////////////////////////////////////////////
        // HINT: Put Tabs (if needed)
        // usage:
        //     $crud->set_outside_tab($how_many_field_outside_tab);
        //     $crud->set_tabs(array(
        //        'First Tab Caption'  => $how_many_field_on_first_tab,
        //        'Second Tab Caption' => $how_many_field_on_second_tab,
        //     ));
        ////////////////////////////////////////////////////////////////////////
        $crud->set_field_half_width(array('user_name', 'email'));

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
        $this->view($this->cms_module_path().'/Manage_user_view', $output,
            $this->n('main_user_management'), $config);
    }

    public function _callback_field_raw_password($value, $row)
    {
        $input = '<input name="new_password" value="" type="input" class="form-control" placeholder="New password or leave blank" />';

        return $input;
    }

    public function _callback_field_read_only_active($value, $row)
    {
        $input = '<input name="active" value="'.$value.'" type="hidden" />';
        $caption = $value == 0 ? 'Inactive' : 'Active';

        return $input.$caption;
    }

    public function _callback_field_read_only_user_name($value, $row)
    {
        $input = '<input name="user_name" value="'.$value.'" type="hidden" />';
        $caption = $value;

        return $input.$caption;
    }

    public function _callback_field_group_user($value, $primary_key){
        if($value === NULL){
            $value = array();
        }
        $query = $this->db->select('group_id, group_name')
            ->from($this->t('main_group'))
            ->limit(20)
            ->get();
        $html = '<select id="field-group_user" name="group_user[]" multiple="multiple" size="8" class="form-control" data-placeholder="Select groups">';
        // add old values
        foreach($value as $key=>$val){
            $html .= '<option selected value = "'.$key.'" >'.$val.'</option>';
        }
        // add other values
        foreach($query->result() as $row){
            if(!array_key_exists($row->group_id, $value)){
                $html .= '<option value = "'.$row->group_id.'" >'.$row->group_name.'</option>';
            }
        }
        $html .= '</select>';
        $html .= '<script>';
        $html .= '$("#field-group_user").chosen({allow_single_deselect:true, width:"100%", search_contains: true});';
        $html .= 'chosen_ajaxify("field-group_user", "{{ SITE_URL }}main/ajax/groups/");';
        $html .= '</script>';
        return $html;
    }



    public function _after_insert_or_update($post_array, $primary_key){

        return TRUE;
    }

    public function _before_insert_or_update($post_array, $primary_key=NULL){
        return $post_array;
    }

    public function _show_edit($primary_key){
        return TRUE;
    }

    public function _show_delete($primary_key){
        if (($primary_key == 1) || ($primary_key == $this->cms_user_id())) {
            return FALSE;
        }
        return TRUE;
    }

    public function _allow_edit($primary_key){
        return TRUE;
    }

    public function _allow_delete($primary_key){
        return $this->_show_delete($primary_key);
    }

    public function _before_insert($post_array){
        // password
        $post_array['password'] = CMS_SUBSITE == '' ?
            cms_md5($post_array['password'], $this->cms_chipper()) :
            cms_md5($post_array['password']);
        // subsite
        $post_array['subsite'] = CMS_SUBSITE == '' ? null : CMS_SUBSITE;
        return $post_array;
    }

    public function _after_insert($post_array, $primary_key){
        return TRUE;
    }

    public function _before_update($post_array, $primary_key){
        return $post_array;
    }

    public function _after_update($post_array, $primary_key){
        if (CMS_SUBSITE == '') {
            // get user activation status
            $user_id = $primary_key;
            $result = $this->db->select('active')
                ->from($this->cms_user_table_name())
                ->where('user_id', $user_id)
                ->get();
            $row = $result->row();
            $active = $row->active;
            // update subsite
            $this->_cms_set_user_subsite_activation($user_id, $active);
        }

        // is new password set?
        $new_password = $this->input->post('new_password');
        if($new_password == '' || $new_password == FALSE){
            $new_password = NULL;
        }

        $user = $this->cms_get_record($this->cms_user_table_name(), 'user_id', $primary_key);
        $new_email = $user->email;
        $new_real_name = $user->real_name;
        if(array_key_exists('email', $post_array)){
            $new_email = $post_array['email'];
        }
        if(array_key_exists('real_name', $post_array)){
            $new_real_name = $post_array['real_name'];
        }

        // change profile
        $this->cms_do_change_profile($new_email,
            $new_real_name, $new_password, $primary_key);

        return TRUE;
    }

    public function _before_delete($primary_key){
        return TRUE;
    }

    public function _after_delete($primary_key){
        return TRUE;
    }

}
