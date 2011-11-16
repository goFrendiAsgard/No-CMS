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
        $this->view("blog_view", NULL, 'blog');
    }
    public function manage(){
        $this->view("manage_view", NULL, 'blog_management');
    }
    public function article(){
        $crud = new grocery_CRUD();

        $crud->set_table('blog_article');
        $crud->columns('article_title','content');
        $crud->edit_fields('article_title','content');
        $crud->add_fields('article_title','content');
        $crud->display_as('article_title','Title')
                 ->display_as('content','Content');
        $crud->set_subject('Article');
        $crud->set_relation_n_n('Categories', 'blog_category_article', 'blog_category', 'category_id', 'article_id' , 'category_name');

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
        $crud->set_relation_n_n('Articles', 'blog_category_article', 'blog_article', 'article_id', 'category_id' , 'article_title');

        $output = $crud->render();

        $this->view('grocery_CRUD', $output, 'blog_category');
        
    }
}

?>
