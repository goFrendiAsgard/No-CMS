<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of blog
 *
 * @author gofrendi
 */
class Blog extends CMS_Controller {
    //put your code here
    public function index(){
        $category = $this->input->post('category');
        $search = $this->input->post('search');
        $words = $search? explode(' ', $search) : array();
        
        $data = array();
        $data['category'] = $category? $category : '';
        $data['search'] = $search? $search : '';
        
        $data['available_category'] = array(''=>'No Category');
        $SQL = "SELECT category_name FROM blog_category";
        $query = $this->db->query($SQL);
        foreach($query->result() as $row){
            $result = array(
                $row->category_name => $row->category_name
            );
            $data['available_category'][] = $result;
        }
        
        $data['article'] = array();
        $where_category = $category && ($category!="")? "article_id IN 
            (SELECT article_id FROM blog_category_article, blog_category 
                WHERE blog_category.category_id = blog_category_article.category_id
                 AND category_name ='".addslashes($category)."'
            )" : "TRUE";
        
        if($search){
            $where_search = "(FALSE ";
            foreach($words as $word){
                $where_search .= " OR (article_title LIKE '%".addslashes($word)."%' OR content LIKE '%".addslashes($word)."%')";
            }
            $where_search .=")";
        }else{
            $where_search = "TRUE";
        }
        $SQL = "SELECT article_title, content, 
                    real_name AS author
                FROM blog_article
                LEFT JOIN cms_user ON (cms_user.user_id = blog_article.author_user_id)
                WHERE 
                    $where_category AND
                    $where_search";
        $query = $this->db->query($SQL);
        foreach($query->result() as $row){
            $result = array(
                "title" => $row->article_title,
                "content" => $row->content,
                "author" => $row->author
            );
            $data['article'][] = $result;
        }
        
        $this->view("blog_view", $data, 'blog');
    }
    
    public function manage(){
        $this->view("manage_view", NULL, 'blog_management');
    }
    public function article(){
        $crud = new grocery_CRUD();

        $crud->set_table('blog_article');
        $crud->columns('article_title','content');
        $crud->edit_fields('article_title','content', 'Categories');
        $crud->add_fields('article_title','content', 'Categories');
        $crud->display_as('article_title','Title')
                 ->display_as('content','Content');
        $crud->set_subject('Article');
        $crud->set_relation_n_n('Categories', 'blog_category_article', 'blog_category', 'article_id', 'category_id' , 'category_name');

        $output = $crud->render();

        $this->view('grocery_CRUD', $output, 'blog_article');
        
    }
    public function category(){
        $crud = new grocery_CRUD();

        $crud->set_table('blog_category');
        $crud->columns('category_name','description');
        $crud->edit_fields('category_name','description');
        $crud->add_fields('category_name','description');
        $crud->display_as('category_name','Category')
                 ->display_as('description','Description');
        $crud->set_subject('Category');
        $crud->set_relation_n_n('Articles', 'blog_category_article', 'blog_article', 'category_id', 'article_id' , 'article_title');

        $output = $crud->render();

        $this->view('grocery_CRUD', $output, 'blog_category');
        
    }
}

?>
