<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Manage_Article
 *
 * @author No-CMS Module Generator
 */
class Manage_article extends CMS_Secure_Controller {

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

        $group_name_list = $this->cms_user_group();
        $group_id_list = $this->cms_user_group_id();

        // check state
        $state = $crud->getState();
        $state_info = $crud->getStateInfo();
        $primary_key = isset($state_info->primary_key)? $state_info->primary_key : NULL;

        $super_admin_user_id = array(1);
        if(CMS_SUBSITE != ''){
            // GET MAIN TABLE PREFIX
            $main_config_file = APPPATH.'config/main/cms_config.php';
            if(file_exists($main_config_file)){ 
                include($main_config_file);
                $main_table_prefix   = $config['__cms_table_prefix'];
                $main_table_prefix   = $main_table_prefix == ''? '' : $main_table_prefix.'_';

                // GET MODULE TABLE PREFIX
                $query = $this->db->select('module_path')
                    ->from($main_table_prefix.'main_module')
                    ->where('module_name', 'gofrendi.noCMS.module')
                    ->get();
                if($query->num_rows()>0){
                    $row = $query->row;
                    $module_path = $row->module_path;
                    $module_config_file = FCPATH.'modules/'.$module_path.'/config/module_config.php';
                    if(!file_exists($module_config_file)){
                        // get module table prefix
                        include($module_config_file);
                        $module_table_prefix = $config['module_table_prefix'];
                        $module_table_prefix = $module_table_prefix == ''? '' : $module_table_prefix.'_';

                        $subsite_table_ = $main_table_prefix.$module_table_prefix.'subsite';

                        $query = $this->db->select('user_id')
                            ->from($subsite_table)
                            ->where('name', CMS_SUBSITE)
                            ->get();
                        if($query->num_rows() > 0){
                            $row = $query->row();
                            $super_admin_user_id[] = $row->user_id;
                        }
                    }
                }
            }
        }

        $allow_continue = TRUE;
        if(!in_array($this->cms_user_id(), $super_admin_user_id) && !in_array(1, $group_id_list) && !in_array('Blog Editor', $group_name_list) && isset($primary_key) && $primary_key !== NULL){
            $query = $this->db->select('author_user_id')
                ->from($this->t('article'))
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
        $crud->set_table($this->t('article'));

        // only super admin or blog editor able to edit other's article
        if(!in_array($this->cms_user_id(), $super_admin_user_id) && !in_array(1, $group_id_list) && !in_array('Blog Editor', $group_name_list)){
            $crud->where('author_user_id', $this->cms_user_id());
        }

        // set subject
        $crud->set_subject('Article');

        // displayed columns on list
        $crud->columns('article_title','author_user_id','status','publish_date','featured','allow_comment','categories','comments');
        // displayed columns on edit operation
        $crud->edit_fields('article_title','article_url','date','status','publish_date','author_user_id','content','categories','keyword','description','featured','allow_comment','photos','comments');
        // displayed columns on add operation
        $crud->add_fields('article_title','article_url','date','status','publish_date','author_user_id','content','categories','keyword','description','featured','allow_comment','photos','comments');
        $crud->required_fields('article_title','status');
        $crud->unique_fields('article_title','article_url');
        $crud->unset_read();

        // caption of each columns
        $crud->display_as('article_title','Article Title');
        $crud->display_as('status', 'Publication Status');
        $crud->display_as('article_url','Permalink (Left blank for default)');
        $crud->display_as('date','Created Date');
        $crud->display_as('author_user_id','Author');
        $crud->display_as('content','Content');
        $crud->display_as('keyword','Keyword metadata (comma separated)');
        $crud->display_as('description','Description metadata');
        $crud->display_as('featured','Featured');
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
        if($state == 'list' || $state == 'ajax_list' || $state == 'export' || $state == 'print' || $state == 'success'){
            $crud->set_relation('author_user_id', $this->cms_user_table_name(), 'user_name');
        }
        if(in_array($this->cms_user_id(), $super_admin_user_id) || in_array(1, $group_id_list) || in_array('Blog Editor', $group_name_list) || in_array('Blog Author', $group_name_list)){
            $crud->set_relation('status', $this->t('publication_status'), 'status');
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put set relation_n_n (detail many to many) codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation_n_n)
        // eg:
        //      $crud->set_relation_n_n( $field_name, $relation_table, $selection_table, $primary_key_alias_to_this_table,
        //          $primary_key_alias_to_selection_table , $title_field_selection_table, $priority_field_relation );
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $crud->set_relation_n_n('categories',
            $this->t('category_article'),
            $this->t('category'),
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
        $crud->field_type('featured', 'true_false');
        $crud->unset_texteditor('article_title');
        $crud->unset_texteditor('article_url');
        $crud->unset_texteditor('keyword');
        $crud->unset_texteditor('description');

        $crud->set_outside_tab(6);
        $crud->set_tabs(array(
                'Setting'   => 4,
                'Photos'    => 1,
                'Comments'  => 1,
            ));
        $crud->set_tab_glyphicons(array(
                'Setting'   => 'glyphicon-th-list',
                'Photos'    => 'glyphicon-picture',
                'Comments'  => 'glyphicon-comment',
            ));

        if(!in_array($this->cms_user_id(), $super_admin_user_id) && !in_array(1, $group_id_list) && !in_array('Blog Editor', $group_name_list) && !in_array('Blog Author', $group_name_list)){
            $crud->field_type('status', 'hidden', 'draft');
            $crud->field_type('publish_date', 'hidden');
        }else{
            $crud->field_type('publish_date', 'datetime');
        }


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

        // prepare css & js, add them to config
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

        // add values from post
        $this->load->model('blog/article_model');
        $output->title = $this->input->post('title');
        $output->content = $this->article_model->build_content($this->input->post('content'));
        $output->status = $this->input->post('status');

        // show the view
        $this->view($this->cms_module_path().'/manage_article_view', $output,
            $this->n('manage_article'), $config);

    }

    public function before_insert_or_update($post_array, $primary_key = NULL){
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

    public function before_insert($post_array){
        $post_array = $this->before_insert_or_update($post_array);        

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
        $post_array = $this->before_insert_or_update($post_array);
        return $post_array;
    }

    public function after_update($post_array, $primary_key){
        $success = $this->after_insert_or_update($post_array, $primary_key);
        return $success;
    }

    public function before_delete($primary_key){
        // delete corresponding photo
        $this->db->delete($this->t('photo'),
              array('photo_id'=>$primary_key));
        // delete corresponding comment
        $this->db->delete($this->t('comment'),
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
        $real_column_names = array('photo_id', 'url', 'caption', 'index');
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
            $this->db->delete($this->t('photo'),
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
                'index' => $insert_record['data']['index'],
            );
            $data['article_id'] = $primary_key;
            $this->db->insert($this->t('photo'), $data);

            $thumbnail_name = 'thumb_'.$file_name;
            $this->image_moo->load($upload_path.$file_name)->resize(800,75)->save($upload_path.$thumbnail_name,true);
        }
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //  UPDATED DATA
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        foreach($update_records as $update_record){
            $detail_primary_key = $update_record['primary_key'];
            $data = array();
            foreach($update_record['data'] as $key=>$value){
                if(in_array($key, $set_column_names)){
                    $data[$key] = implode(',', $value);
                }else if(in_array($key, $real_column_names)){
                    $data[$key] = $value;
                }
            }
            $data['article_id'] = $primary_key;
            $this->db->update($this->t('photo'),
                 $data, array('photo_id'=>$detail_primary_key));            
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
        $update_records = $data['update'];
        $real_column_names = array('comment_id', 'date', 'author_user_id', 'name', 'email', 'website', 'content', 'approved');
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //  DELETED DATA
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        foreach($delete_records as $delete_record){
            $detail_primary_key = $delete_record['primary_key'];
            $this->db->delete($this->t('comment'),
                 array('comment_id'=>$detail_primary_key));
        }
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //  UPDATED DATA
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        foreach($update_records as $update_record){
            $detail_primary_key = $update_record['primary_key'];
            $data = array();
            foreach($update_record['data'] as $key=>$value){
                if(in_array($key, $set_column_names)){
                    $data[$key] = implode(',', $value);
                }else if(in_array($key, $real_column_names)){
                    $data[$key] = $value;
                }
            }
            $data['article_id'] = $primary_key;
            $this->db->update($this->t('comment'),
                 $data, array('comment_id'=>$detail_primary_key));            
        }
        return TRUE;
    }


    // returned on insert and edit
    public function callback_field_photos($value, $primary_key){
        $module_path = $this->cms_module_path();
        $this->config->load('grocery_crud');
        $date_format = $this->config->item('grocery_crud_date_format');

        if(!isset($primary_key)) $primary_key = -1;
        $query = $this->db->select('photo_id, url, caption, index')
            ->from($this->t('photo'))
            ->where('article_id', $primary_key)
            ->order_by('index')
            ->get();
        $result = $query->result_array();

        for($i=0; $i<count($result); $i++){
            if($result[$i]['caption'] === NULL){
                $result[$i]['caption'] = '';
            }
        }

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
            ->from($this->t('photo'))
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
        $query = $this->db->select('comment_id, date, author_user_id, name, email, website, content, approved')
            ->from($this->t('comment'))
            ->where('article_id', $primary_key)
            ->order_by('comment_id', 'desc')
            ->order_by('approved')
            ->get();
        $result = $query->result_array();

        // change the comment status into read
        $data = array('read'=>1);
        $where = array('article_id'=> $primary_key);
        $this->db->update($this->t('comment'), $data, $where);

        $search = array('<', '>');
        $replace = array('&lt;', '&gt;');

        for($i=0; $i<count($result); $i++){
            $row = $result[$i];
            $user_id = $row['author_user_id'];
            if($user_id>0){
                $query_user = $this->db->select('real_name, email')
                    ->from($this->cms_user_table_name())
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
            ->from($this->t('comment'))
            ->where('article_id', $row->article_id)
            ->get();
        $num_row = $query->num_rows();
        $query = $this->db->select('comment_id')
            ->from($this->t('comment'))
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