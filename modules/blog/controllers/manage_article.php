<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Manage_Article
 *
 * @author No-CMS Module Generator
 */
class Manage_Article extends CMS_Priv_Strict_Controller {

    protected $URL_MAP = array();

    public function redirect($redirect){
        if($redirect){
            redirect($this->cms_module_path.'/blog/manage_article/index','refresh');
        }
    }

    public function index(){
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // initialize groceryCRUD
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $crud = $this->new_crud();
        $crud->unset_jquery();

        // check state
        $state = $crud->getState();
        $state_info = $crud->getStateInfo();
        $primary_key = isset($state_info->primary_key)? $state_info->primary_key : NULL;

        $allow_continue = TRUE;
        if($this->cms_user_id() != 1 && !in_array(1, $this->cms_user_group_id()) && isset($primary_key) && $primary_key !== NULL){
            $query = $this->db->select('author_user_id')
                ->from($this->cms_complete_table_name('article'))
                ->where(array('article_id'=> $primary_key, 'author_user_id'=> $this->cms_user_id()))
                ->get();
            if($query->num_rows() == 0){
                $allow_continue = FALSE;
            }
        }

        switch($state){
            case 'unknown': break;
            case 'list' : break;
            case 'add' :  break;
            case 'edit' : $this->redirect(!$allow_continue); break;
            case 'delete' : $this->redirect(!$allow_continue); break;
            case 'insert' : $this->redirect(!$allow_continue); break;
            case 'update' : break;
            case 'ajax_list' : break;
            case 'ajax_list_info': break;
            case 'insert_validation': break;
            case 'update_validation': break;
            case 'upload_file': break;
            case 'delete_file': break;
            case 'ajax_relation': break;
            case 'ajax_relation_n_n': break;
            case 'success': break;
            case 'export': break;
            case 'print': break;
        }

        // set model
        //$crud->set_model($this->cms_module_path().'/grocerycrud_article_model');

        // adjust groceryCRUD's language to No-CMS's language
        $crud->set_language($this->cms_language());

        // table name
        $crud->set_table($this->cms_complete_table_name('article'));
        // only super admin can edit other's article
        if($this->cms_user_id() <> 1 && !in_array(1, $this->cms_user_group_id())){
            $crud->where('author_user_id', $this->cms_user_id());
        }

        // set subject
        $crud->set_subject('Article');

        // displayed columns on list
        $crud->columns('article_title','author_user_id','allow_comment','categories','comments');
        // displayed columns on edit operation
        $crud->edit_fields('article_title','article_url','date','author_user_id','content','keyword','description','allow_comment','categories','photos','comments');
        // displayed columns on add operation
        $crud->add_fields('article_title','article_url','date','author_user_id','content','keyword','description','allow_comment','categories','photos','comments');
        $crud->required_fields('article_title');
        $crud->unique_fields('article_title');
        $crud->unset_read();

        // caption of each columns
        $crud->display_as('article_title','Article Title');
        $crud->display_as('article_url','Article URL (Permalink)');
        $crud->display_as('date','Created Date');
        $crud->display_as('author_user_id','Author');
        $crud->display_as('content','Content');
        $crud->display_as('keyword','Keyword metadata (comma separated)');
        $crud->display_as('description','Description metadata');
        $crud->display_as('allow_comment','Allow Comment');
        $crud->display_as('categories','Categories');
        $crud->display_as('photos','Photos');
        $crud->display_as('comments','Comments');

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put set relation (lookup) codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation)
        // eg:
        //      $crud->set_relation( $field_name , $related_table, $related_title_field , $where , $order_by );
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if($state == 'list' || $state == 'ajax_list' || $state == 'export' || $state == 'print'){
            $crud->set_relation('author_user_id', cms_table_name('main_user'), 'user_name');
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put set relation_n_n (detail many to many) codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation_n_n)
        // eg:
        //      $crud->set_relation_n_n( $field_name, $relation_table, $selection_table, $primary_key_alias_to_this_table,
        //          $primary_key_alias_to_selection_table , $title_field_selection_table, $priority_field_relation );
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $crud->set_relation_n_n('categories',
            $this->cms_complete_table_name('category_article'),
            $this->cms_complete_table_name('category'),
            'article_id', 'category_id',
            'category_name', NULL);

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put custom field type here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/field_type)
        // eg:
        //      $crud->field_type( $field_name , $field_type, $value  );
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if($state != 'list' && $state != 'ajax_list' && $state != 'print' && $state != 'export'){
            $crud->field_type('author_user_id', 'hidden');
        }
        $crud->field_type('date', 'hidden');
        $crud->field_type('allow_comment', 'true_false');
        $crud->unset_texteditor('description');


        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put callback here
        // (documentation: httm://www.grocerycrud.com/documentation/options_functions)
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $crud->callback_before_insert(array($this,'before_insert'));
        $crud->callback_before_update(array($this,'before_update'));
        $crud->callback_before_delete(array($this,'before_delete'));
        $crud->callback_after_insert(array($this,'after_insert'));
        $crud->callback_after_update(array($this,'after_update'));
        $crud->callback_after_delete(array($this,'after_delete'));

        $crud->callback_column('photos',array($this, 'callback_column_photos'));
        $crud->callback_field('photos',array($this, 'callback_field_photos'));
        $crud->callback_column('comments',array($this, 'callback_column_comments'));
        $crud->callback_field('comments',array($this, 'callback_field_comments'));

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // render
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $output = $crud->render();
        $this->view($this->cms_module_path().'/manage_article_view', $output,
            $this->cms_complete_navigation_name('manage_article'));

    }

    public function before_insert($post_array){
        $this->load->helper('url');
        $this->load->model($this->cms_module_path().'/article_model');
        // article url / permalink
        if($post_array['article_url'] === NULL || trim($post_array['article_url']) == ''){
            $url = url_title($post_array['article_title']);
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
        // default allow comment value
        if(!isset($post_array['allow_comment']) || !in_array($post_array['allow_comment'],array(0,1))){
            $post_array['allow_comment'] = 1;
        }
        // author and user
        $post_array['author_user_id'] = $this->cms_user_id();
        $post_array['date'] = date('Y-m-d H:i:s');
        return $post_array;
    }

    public function after_insert($post_array, $primary_key){
        $success = $this->after_insert_or_update($post_array, $primary_key);
        return $success;
    }

    public function before_update($post_array, $primary_key){
        return TRUE;
    }

    public function after_update($post_array, $primary_key){
        $success = $this->after_insert_or_update($post_array, $primary_key);
        return $success;
    }

    public function before_delete($primary_key){
        // delete corresponding photo
        $this->db->delete($this->cms_complete_table_name('photo'),
              array('photo_id'=>$primary_key));
        // delete corresponding comment
        $this->db->delete($this->cms_complete_table_name('comment'),
              array('comment_id'=>$primary_key));
        return TRUE;
    }

    public function after_delete($primary_key){
        return TRUE;
    }

    public function after_insert_or_update($post_array, $primary_key){

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //
        // SAVE CHANGES OF photo
        //  * The photo data in in json format.
        //  * It can be accessed via $_POST['md_real_field_photos_col']
        //
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $data = json_decode($this->input->post('md_real_field_photos_col'), TRUE);
        $insert_records = $data['insert'];
        $update_records = $data['update'];
        $delete_records = $data['delete'];
        $real_column_names = array('photo_id', 'url');
        $set_column_names = array();
        $many_to_many_column_names = array();
        $many_to_many_relation_tables = array();
        $many_to_many_relation_table_columns = array();
        $many_to_many_relation_selection_columns = array();
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //  DELETED DATA
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        foreach($delete_records as $delete_record){
            $detail_primary_key = $delete_record['primary_key'];
            $this->db->delete($this->cms_complete_table_name('photo'),
                 array('photo_id'=>$detail_primary_key));
        }
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //  INSERTED DATA
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        foreach($insert_records as $insert_record){
            $this->load->library('image_moo');
            $upload_path = FCPATH.'modules/'.$this->cms_module_path().'/assets/uploads/';

            $record_index = $insert_record['record_index'];
            $tmp_name = $_FILES['md_field_photos_col_url_'.$record_index]['tmp_name'];
            $file_name = $_FILES['md_field_photos_col_url_'.$record_index]['name'];
            $file_name = $this->randomize_string($file_name).$file_name;
            move_uploaded_file($tmp_name, $upload_path.$file_name);
            $data = array(
                'url' => $file_name,
            );
            $data['article_id'] = $primary_key;
            $this->db->insert($this->cms_complete_table_name('photo'), $data);

            $thumbnail_name = 'thumb_'.$file_name;
            $this->image_moo->load($upload_path.$file_name)->resize(800,75)->save($upload_path.$thumbnail_name,true);
        }


        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //
        // SAVE CHANGES OF comment
        //  * The comment data in in json format.
        //  * It can be accessed via $_POST['md_real_field_comments_col']
        //
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $data = json_decode($this->input->post('md_real_field_comments_col'), TRUE);
        $delete_records = $data['delete'];
        $real_column_names = array('comment_id', 'date', 'author_user_id', 'name', 'email', 'website', 'content');
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //  DELETED DATA
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        foreach($delete_records as $delete_record){
            $detail_primary_key = $delete_record['primary_key'];
            $this->db->delete($this->cms_complete_table_name('comment'),
                 array('comment_id'=>$detail_primary_key));
        }
        return TRUE;
    }


    // returned on insert and edit
    public function callback_field_photos($value, $primary_key){
        $module_path = $this->cms_module_path();
        $this->config->load('grocery_crud');
        $date_format = $this->config->item('grocery_crud_date_format');

        if(!isset($primary_key)) $primary_key = -1;
        $query = $this->db->select('photo_id, url')
            ->from($this->cms_complete_table_name('photo'))
            ->where('article_id', $primary_key)
            ->get();
        $result = $query->result_array();

        // get options
        $options = array();
        $data = array(
            'result' => $result,
            'options' => $options,
            'date_format' => $date_format,
            'module_path' => $this->cms_module_path(),
        );
        return $this->load->view($this->cms_module_path().'/field_article_photos',$data, TRUE);
    }

    // returned on view
    public function callback_column_photos($value, $row){
        $module_path = $this->cms_module_path();
        $query = $this->db->select('photo_id, url')
            ->from($this->cms_complete_table_name('photo'))
            ->where('article_id', $row->article_id)
            ->get();
        $num_row = $query->num_rows();
        // show how many records
        if($num_row>1){
            return $num_row .' Photos';
        }else if($num_row>0){
            return $num_row .' Photo';
        }else{
            return 'No Photo';
        }
    }

    // returned on insert and edit
    public function callback_field_comments($value, $primary_key){
        $module_path = $this->cms_module_path();
        $this->config->load('grocery_crud');
        $date_format = $this->config->item('grocery_crud_date_format');

        if(!isset($primary_key)) $primary_key = -1;
        $query = $this->db->select('comment_id, date, author_user_id, name, email, website, content')
            ->from($this->cms_complete_table_name('comment'))
            ->where('article_id', $primary_key)
            ->get();
        $result = $query->result_array();

        // change the comment status into read
        $data = array('read'=>1);
        $where = array('article_id', $primary_key);
        $this->db->update($this->cms_complete_table_name('comment'), $data, $where);

        $search = array('<', '>');
        $replace = array('&lt;', '&gt;');

        for($i=0; $i<count($result); $i++){
            $row = $result[$i];
            $user_id = $row['author_user_id'];
            if($user_id>0){
                $query_user = $this->db->select('real_name, email')
                    ->from(cms_table_name('main_user'))
                    ->where('user_id', $user_id)
                    ->get();
                $row_user = $query_user->row();
                $result[$i]['name'] = $row_user->real_name;
                $result[$i]['email'] = $row_user->email;
            }
            $result[$i]['content'] = str_replace($search, $replace, $result[$i]['content']);
            $result[$i]['website'] = prep_url($result[$i]['website']);
        }

        // get options
        $options = array();
        $data = array(
            'result' => $result,
            'options' => $options,
            'date_format' => $date_format,
        );
        return $this->load->view($this->cms_module_path().'/field_article_comments',$data, TRUE);
    }

    // returned on view
    public function callback_column_comments($value, $row){
        $module_path = $this->cms_module_path();
        $query = $this->db->select('comment_id, date, author_user_id, name, email, website, content')
            ->from($this->cms_complete_table_name('comment'))
            ->where('article_id', $row->article_id)
            ->get();
        $num_row = $query->num_rows();
        $query = $this->db->select('comment_id')
            ->from($this->cms_complete_table_name('comment'))
            ->where(array('article_id'=> $row->article_id, 'read'=>0))
            ->get();
        $unread_num_row = $query->num_rows();
        $new_str = '';
        if($unread_num_row>0){
            $new_str = ', <b>'.$unread_num_row.' new</b>';
        }
        // show how many records
        if($num_row>1){
            return $num_row .' Comments'.$new_str;
        }else if($num_row>0){
            return $num_row .' Comment'. $new_str;
        }else{
            return 'No Comment';
        }
    }


    private function randomize_string($value){
        $time = date('Y:m:d H:i:s');
        return substr(md5($value.$time),0,6);
    }

}