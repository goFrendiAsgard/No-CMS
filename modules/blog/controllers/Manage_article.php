<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Manage_article
 *
 * @author No-CMS Module Generator
 */
class Manage_article extends CMS_CRUD_Controller {

    protected $URL_MAP = array();
    protected $TABLE_NAME = 'article';
    protected $COLUMN_NAMES = array('article_title', 'article_url', 'keyword', 'description', 'date', 'author_user_id', 'content', 'allow_comment', 'status', 'visited', 'featured', 'publish_date', 'photos', 'comments', 'category_article');
    protected $PRIMARY_KEY = 'article_id';
    protected $UNSET_JQUERY = TRUE;
    protected $UNSET_READ = TRUE;
    protected $UNSET_ADD = FALSE;
    protected $UNSET_EDIT = FALSE;
    protected $UNSET_DELETE = FALSE;
    protected $UNSET_LIST = FALSE;
    protected $UNSET_BACK_TO_LIST = FALSE;
    protected $UNSET_PRINT = FALSE;
    protected $UNSET_EXPORT = FALSE;

    protected $group_id_list = array();
    protected $group_name_list = array();

    public function __construct(){
        parent::__construct();
        // get super_admin_id
        $this->group_name_list = $this->cms_user_group();
        $this->group_id_list = $this->cms_user_group_id();
    }

    protected function make_crud(){
        $crud = parent::make_crud();

        if($this->STATE == 'edit'){
            $this->db->update($this->t('comment'),
                array('read' => 1),
                array('article_id' => $this->PK_VALUE)
            );
        }

        $crud->order_by('date', 'desc');
        if(!$this->cms_user_is_super_admin() && !in_array('Blog Editor', $this->group_name_list)){
            $crud->where('author_user_id', $this->cms_user_id());
        }

        ////////////////////////////////////////////////////////////////////////
        // HINT: You can access this variables after calling parent's make_crud method:
        //      $this->CRUD
        //      $this->STATE
        //      $this->STATE_INFO
        //      $this->PK_VALUE
        ////////////////////////////////////////////////////////////////////////

        // set subject
        $crud->set_subject('Article');

        // displayed columns on list, edit, and add, uncomment to use
        $crud->columns('article_title', 'author_user_id', 'status', 'publish_date', 'featured' , 'category_article', 'comments');
        $crud->edit_fields('article_title', 'date', 'status', 'publish_date', 'author_user_id', 'content', 'category_article', 'photos', 'keyword', 'description', 'article_url', 'featured', 'allow_comment', 'comments',  '_updated_by', '_updated_at');
        $crud->add_fields('article_title', 'date', 'status', 'publish_date', 'author_user_id', 'content', 'category_article', 'photos', 'keyword', 'description', 'article_url', 'featured', 'allow_comment', 'comments',  '_created_by', '_created_at');
        //$crud->set_read_fields('article_title', 'article_url', 'keyword', 'description', 'date', 'author_user_id', 'content', 'allow_comment', 'status', 'visited', 'featured', 'publish_date', 'photo', 'comment', 'category_article');

        // caption of each columns
        $crud->display_as('article_title','Article Title');
        $crud->display_as('article_url','Article Url (Left blank for default)');
        $crud->display_as('keyword','Keyword Metadata (Comma Separated)');
        $crud->display_as('description','Description Metadata');
        $crud->display_as('date','Creation Date');
        $crud->display_as('author_user_id','Author User');
        $crud->display_as('content','Content');
        $crud->display_as('allow_comment','Allow Comment');
        $crud->display_as('status','Status');
        $crud->display_as('visited','Visited');
        $crud->display_as('featured','Featured');
        $crud->display_as('publish_date','Publish Date');
        $crud->display_as('photo','Photos');
        $crud->display_as('comment','Comments');
        $crud->display_as('category_article','Categories');

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
        $crud->required_fields('article_title','status');

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put required field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/unique_fields)
        // eg:
        //      $crud->unique_fields( $field1, $field2, $field3, ... );
        ////////////////////////////////////////////////////////////////////////
        $crud->unique_fields('article_title','article_url');

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
        if($this->STATE == 'list' || $this->STATE == 'ajax_list' || $this->STATE == 'export' || $this->STATE == 'print' || $this->STATE == 'success'){
            $crud->set_relation('author_user_id', $this->cms_user_table_name(), 'user_name');
        }
        if($this->cms_user_is_super_admin() || in_array('Blog Editor', $this->group_name_list) || in_array('Blog Author', $this->group_name_list)){
            $crud->set_relation('status', $this->t('publication_status'), 'status');
        }

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put set relation_n_n (detail many to many) codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation_n_n)
        // eg:
        //      $crud->set_relation_n_n( $field_name, $relation_table, $selection_table, $primary_key_alias_to_this_table,
        //          $primary_key_alias_to_selection_table , $title_field_selection_table, $priority_field_relation );
        ////////////////////////////////////////////////////////////////////////
        $crud->set_relation_n_n('category_article',
            $this->t('category_article'),
            $this->t('category'),
            'article_id', 'category_id',
            'category_name', NULL);

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put custom field type here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/field_type)
        // eg:
        //      $crud->field_type( $field_name , $field_type, $value  );
        ////////////////////////////////////////////////////////////////////////
        if($this->STATE != 'list' && $this->STATE != 'ajax_list' && $this->STATE != 'print' && $this->STATE != 'export'){
            $crud->field_type('author_user_id', 'hidden');
        }
        $crud->field_type('date', 'hidden');
        $crud->field_type('allow_comment', 'true_false');
        $crud->field_type('featured', 'true_false');
        $crud->unset_texteditor('article_title');
        $crud->unset_texteditor('article_url');
        $crud->unset_texteditor('keyword');
        $crud->unset_texteditor('description');

        $crud->set_outside_tab(5);
        $crud->set_tabs(array(
                'Photos'    => 1,
                'Setting'   => 5,
                'Comments'  => 1,
            ));
        $crud->set_tab_glyphicons(array(
                'Photos'    => 'glyphicon-picture',
                'Setting'   => 'glyphicon-th-list',
                'Comments'  => 'glyphicon-comment',
            ));

        if(!$this->cms_user_is_super_admin() && !in_array('Blog Editor', $this->group_name_list) && !in_array('Blog Author', $this->group_name_list)){
            $crud->field_type('status', 'hidden', 'draft');
            $crud->field_type('publish_date', 'hidden');
        }else{
            $crud->field_type('publish_date', 'datetime');
        }

        $crud->set_field_half_width(array('featured', 'allow_comment'));



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

        $output->title = $this->input->post('title');
        $output->content = $this->input->post('content');
        $output->status = $this->input->post('status');
        // if they are null, make them empty string
        if($output->title === NULL){$output->title = '';}
        if($output->content === NULL){$output->content = '';}
        if($output->status === NULL){$output->status = '';}

        // author can only make a draft
        $user_group = $this->cms_user_group();
        if(!in_array('Blog Editor', $user_group) && !in_array('Blog Author', $user_group) && !$this->cms_user_is_super_admin()){
            $output->status = 'draft';
        }

        // show the view
        $this->view($this->cms_module_path().'/Manage_article_view', $output,
            $this->n('manage_article'), $config);
    }


    // returned on insert and edit
    public function _callback_field_photos($value, $primary_key){
        // Options for detail table's column with SET type
        $set_column_option_list = array();
        // Options for detail table's column with ENUM type
        $enum_column_option_list = array();
        // Detail table's one-to-many columns configurations
        $lookup_config_list = array();
        // Detail table's many-to-many columns configurations
        $many_to_many_config_list = array();
        // Prepare the data by using defined configurations and options
        $data = $this->_one_to_many_callback_field_data(
                'photo', // DETAIL TABLE NAME
                'photo_id', // DETAIL PK NAME
                'article_id', // DETAIL FK NAME
                $primary_key, // CURRENT TABLE PK VALUE
                $lookup_config_list, // LOOKUP CONFIGS
                $many_to_many_config_list, // MANY TO MANY CONFIGS
                $set_column_option_list, // SET OPTIONS
                $enum_column_option_list // ENUM OPTIONS
            );
        // add date_format
        $this->config->load('grocery_crud');
        $data['date_format'] = $this->config->item('grocery_crud_date_format');
        $data['module_path'] = $this->cms_module_path();

        // Parse the data to the view
        return $this->load->view($this->cms_module_path().'/field_article_photos',$data, TRUE);
    }

    public function _callback_column_publish_date($value, $row){
        if($value == ''){
            return $row->date;
        }else{
            return $value;
        }
    }

    // returned on view
    public function _callback_column_photos($value, $row){
        return $this->_humanized_record_count(
                'photo', // DETAIL TABLE NAME
                'article_id', // DETAIL FK NAME
                $row->article_id, // CURRENT TABLE PK VALUE
                array( // CAPTIONS
                    'single_caption'    => 'Photo',
                    'multiple_caption'  => 'Photos',
                    'zero_caption'      => 'No Photo',
                )
            );
    }


    // returned on insert and edit
    public function _callback_field_comments($value, $primary_key){
        // Options for detail table's column with SET type
        $set_column_option_list = array();
        // Options for detail table's column with ENUM type
        $enum_column_option_list = array();
        // Detail table's one-to-many columns configurations
        $lookup_config_list = array();
        // Detail table's many-to-many columns configurations
        $many_to_many_config_list = array();
        // Prepare the data by using defined configurations and options
        $data = $this->_one_to_many_callback_field_data(
                'comment', // DETAIL TABLE NAME
                'comment_id', // DETAIL PK NAME
                'article_id', // DETAIL FK NAME
                $primary_key, // CURRENT TABLE PK VALUE
                $lookup_config_list, // LOOKUP CONFIGS
                $many_to_many_config_list, // MANY TO MANY CONFIGS
                $set_column_option_list, // SET OPTIONS
                $enum_column_option_list // ENUM OPTIONS
            );
        for($i=0; $i<count($data['result']); $i++){
            if($data['result'][$i]['name'] == '' && $data['result'][$i]['author_user_id'] != ''){
                $user = $this->cms_get_record($this->cms_user_table_name(), 'user_id', $data['result'][$i]['author_user_id']);
                $data['result'][$i]['name'] = $user->real_name == '' ? $user->user_name : $user->real_name;
            }
        }
        // Parse the data to the view
        return $this->load->view($this->cms_module_path().'/field_article_comments',$data, TRUE);
    }

    // returned on view
    public function _callback_column_comments($value, $row){
        $return = $this->_humanized_record_count(
                'comment', // DETAIL TABLE NAME
                'article_id', // DETAIL FK NAME
                $row->article_id, // CURRENT TABLE PK VALUE
                array( // CAPTIONS
                    'single_caption'    => 'Comment',
                    'multiple_caption'  => 'Comments',
                    'zero_caption'      => 'No Comment',
                )
            );

        $unread_record = $this->cms_get_record_list($this->t('comment'), array('read !=' => 1, 'article_id' => $row->article_id));
        $unread_count = count($unread_record);
        if($unread_count > 0){
            $return = '<b>' . $return . ', ' . $unread_count . ' new </b>';
        }
        return $return;
    }


    public function _after_insert_or_update($post_array, $primary_key){
        // SAVE CHANGES OF photo
        $data = json_decode($this->input->post('md_real_field_photos_col'), TRUE);
        // upload files and change the data before saving
        $insert_records = $data['insert'];
        for($i=0; $i<count($insert_records); $i++){
            $insert_record = $insert_records[$i];
            $this->load->library('image_moo');
            $upload_path = FCPATH.'modules/'.$this->cms_module_path().'/assets/uploads/';

            $record_index = $insert_record['record_index'];
            $tmp_name = $_FILES['md_field_photos_col_url_'.$record_index]['tmp_name'];
            $file_name = $_FILES['md_field_photos_col_url_'.$record_index]['name'];
            $file_name = $this->randomize_string($file_name).$file_name;
            move_uploaded_file($tmp_name, $upload_path.$file_name);
            @chmod($upload_path.$file_name, 644);
            $data['insert'][$i]['data']['url'] = $file_name;

            $thumbnail_name = 'thumb_'.$file_name;
            $this->cms_resize_image($upload_path.$file_name, 800, 75, $upload_path.$thumbnail_name);
        }
        // save
        $this->_save_one_to_many(
            'photo', // FIELD NAME
            'photo', // DETAIL TABLE NAME
            'photo_id', // DETAIL PK NAME
            'article_id', // DETAIL FK NAME
            $primary_key, // PARENT PRIMARY KEY VALUE
            $data, // DATA
            $real_column_list=array('photo_id', 'url', 'index', 'caption'), // REAL DETAIL COLUMN NAMES
            $set_column_list=array(), // SET DETAIL COLUMN NAMES
            $many_to_many_config_list=array()
        );

        // SAVE CHANGES OF comment
        $data = json_decode($this->input->post('md_real_field_comments_col'), TRUE);
        $this->_save_one_to_many(
            'comment', // FIELD NAME
            'comment', // DETAIL TABLE NAME
            'comment_id', // DETAIL PK NAME
            'article_id', // DETAIL FK NAME
            $primary_key, // PARENT PRIMARY KEY VALUE
            $data, // DATA
            $real_column_list=array('comment_id', 'date', 'author_user_id', 'name', 'email', 'website', 'content', 'parent_comment_id', 'read', 'approved'), // REAL DETAIL COLUMN NAMES
            $set_column_list=array(), // SET DETAIL COLUMN NAMES
            $many_to_many_config_list=array()
        );

        return TRUE;
    }

    public function _before_insert_or_update($post_array, $primary_key=NULL){
        if($post_array['status'] == 'scheduled'){
            if($post_array['publish_date'] === NULL || trim($post_array['publish_date']) == ''){
                $post_array['publish_date'] = date('Y-m-d', strtotime('+ 30 days'));
            }
        }
        $this->load->helper('url');
        $this->load->model('article_model');
        // article url / permalink
        if($post_array['article_url'] === NULL || trim($post_array['article_url']) == ''){
            $url = urlencode(url_title($this->cms_parse_keyword($post_array['article_title'])));
            $count_url = $this->article_model->get_count_article_url($url);
            if($count_url>0){
                $index = $count_url;
                while($this->article_model->get_count_article_url($url.'_'.$index)>0){
                    $index++;
                }
                $url .= '_'.$index;
            }
            $post_array['article_url'] = $url;
        }

        return $post_array;
    }

    public function _show_edit($primary_key){
        return $this->_allow_edit($primary_key);
    }

    public function _show_delete($primary_key){
        return $this->_allow_edit($primary_key);
    }

    public function _allow_edit($primary_key){
        if($this->cms_user_is_super_admin() || in_array('Blog Editor', $this->group_name_list)  || in_array('Blog Author', $this->group_name_list)){
            return TRUE;
        }else if(in_array('Blog Contributor', $this->group_name_list)){
            return $this->cms_record_exists($this->t('article'), array(
                    'author_user_id' => $this->cms_user_id(),
                    'article_id' => $primary_key,
                ));
        }
        return FALSE;
    }

    public function _allow_delete($primary_key){
        return $this->_allow_edit($primary_key);
    }

    public function _before_insert($post_array){
        // default allow comment value
        if(!isset($post_array['allow_comment']) || !in_array($post_array['allow_comment'],array(0,1))){
            $post_array['allow_comment'] = 1;
        }
        // author and user
        $post_array['author_user_id'] = $this->cms_user_id();
        $post_array['date'] = date('Y-m-d H:i:s');

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
         // delete corresponding photo
        $this->db->delete($this->t('photo'),
              array('photo_id'=>$primary_key));
        // delete corresponding comment
        $this->db->delete($this->t('comment'),
              array('comment_id'=>$primary_key));
        return TRUE;
    }

    public function _after_delete($primary_key){
        return TRUE;
    }

    private function randomize_string($value){
        $time = date('Y:m:d H:i:s');
        return substr(md5($value.$time),0,6);
    }

}
