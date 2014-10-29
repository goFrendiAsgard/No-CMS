<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Article_Model
 *
 * @author No-CMS Module Generator
 */
class Article_Model extends  CMS_Model{
    public $page_break_separator = '';

    public function get_archive(){
        $query = $this->db->select('date')
            ->from($this->cms_complete_table_name('article'))
            ->get();
        $data = array();
        foreach($query->result() as $row){
            $str = substr($row->date, 0, 7);
            if(!in_array($str, $data)){
                $data[] = $str;
            }
        }
        $return = array();
        foreach($data as $year_month){
            $return[$year_month] = date('F Y', strtotime($year_month.'-01 00:00:00'));
        }
        return $return;
    }

	public function get_data($keyword, $page=0){
		$limit = 10;
		$query = $this->db->select('article.article_id, article.article_title,
		      article.article_url, article.date, article.author_user_id,
		      article.content, article.allow_comment,
    		      (
        		      SELECT COUNT(comment_id) FROM '.$this->cms_complete_table_name('comment').'
                      WHERE article_id = article.article_id
                  ) as comment_count
		      ')
			->from($this->cms_complete_table_name('article').' as article')
			->like('article.article_title', $keyword)
			->or_like('article.article_url', $keyword)
			->or_like('article.date', $keyword)
			->or_like('article.author_user_id', $keyword)
			->or_like('article.content', $keyword)
			->or_like('article.allow_comment', $keyword)
			->limit($limit, $page*$limit)
			->get();
		$result = $query->result();
		return $result;
	}


    public function __construct(){
        parent::__construct();
        $this->page_break_separator = '/(<div style=\"page-break-after: always;\">\s*<span style=\"display: none;\">\s*&nbsp;\s*<\/span>\s*<\/div>)/i';
    }

    public function get_count_article_url($article_url){
        $query = $this->db->select('article_id')
            ->from($this->cms_complete_table_name('article'))
            ->where('article_url', $article_url)
            ->get();
        return $query->num_rows();
    }

    public function get_article_url($article_id){
        $query = $this->db->select('article_id, article_url')
            ->from($this->cms_complete_table_name('article'))
            ->where('article_id', $article_id)
            ->get();
        if($query->num_rows()>0){
            $row = $query->row();
            return $row->article_url;
        }else{
            return false;
        }
    }


    public function get_available_category(){
        $result = array(''=>'All Category');
        $query = $this->db->select('category_name')
            ->from($this->cms_complete_table_name('category'))
            ->get();
        foreach($query->result() as $row){
            $result[$row->category_name] = $row->category_name;
        }
        return $result;
    }

    public function get_single_article($article_url){
        $where_article_url = isset($article_url)?
            "article_url = '".addslashes($article_url)."'":"(1=1)";

        $SQL = "
            SELECT
                article_id, article_title, article_url, content, date, keyword, description, allow_comment,
                real_name AS author, author_user_id
            FROM ".$this->cms_complete_table_name('article')."
            LEFT JOIN ".cms_table_name('main_user')." ON (".cms_table_name('main_user').".user_id = ".$this->cms_complete_table_name('article').".author_user_id)
            WHERE $where_article_url";

        $query = $this->db->query($SQL);
        if($query->num_rows()>0){
            $row = $query->row();
            $contents = preg_split($this->page_break_separator, $row->content);
            $content = implode('', $contents);
            $result = array(
                    "id" => $row->article_id,
                    "title" => $row->article_title,
                    "article_url" => $row->article_url,
                    "author_user_id" => $row->author_user_id,
                    "content" => $this->cms_parse_keyword($content),
                    "keyword" => $this->cms_parse_keyword($row->keyword),
                    "description" => $this->cms_parse_keyword($row->description),
                    "author" => $row->author,
                    "date" => $row->date,
                    "allow_comment" => $row->allow_comment,
                    "comments" => $this->get_comments($row->article_id),
                    "photos" => $this->get_photos($row->article_id)
            );
            return $result;
        }else{
            return false;
        }
    }

    public function get_articles($page, $limit, $category, $archive, $search){
        $words = $search? explode(' ', $search) : array();

        $data = array();

        $where_category = isset($category) && ($category!="")? "article_id IN
            (SELECT article_id FROM ".$this->cms_complete_table_name('category_article').", ".$this->cms_complete_table_name('category')."
            WHERE ".$this->cms_complete_table_name('category').".category_id = ".$this->cms_complete_table_name('category_article').".category_id
            AND category_name ='".addslashes($category)."'
            )" : "(1=1)";

        if($search){
            $where_search = "(FALSE ";
            foreach($words as $word){
                $where_search .= " OR (article_title LIKE '%".addslashes($word)."%' OR content LIKE '%".addslashes($word)."%')";
            }
            $where_search .=")";
        }else{
            $where_search = "(1=1)";
        }

        $offset = $page * $limit;
        $SQL = "
            SELECT
                article_id, article_title, article_url, content, date, allow_comment, author_user_id,
                real_name AS author,
                (
                  SELECT COUNT(comment_id) FROM ".$this->cms_complete_table_name('comment')."
                  WHERE article_id = ".$this->cms_complete_table_name('article').".article_id
                ) as comment_count
            FROM ".$this->cms_complete_table_name('article')."
            LEFT JOIN ".cms_table_name('main_user').
                " ON (".cms_table_name('main_user').".user_id = ".$this->cms_complete_table_name('article').".author_user_id)
            WHERE
                $where_category AND
                $where_search AND 
                date LIKE '$archive%'
            ORDER BY date DESC, article_id DESC
            LIMIT $limit OFFSET $offset";

        $query = $this->db->query($SQL);
        foreach($query->result() as $row){
            $contents = preg_split($this->page_break_separator, $row->content);
            $content = $contents[0];

            $data[] = array(
                    "id" => $row->article_id,
                    "title" => $row->article_title,
                    "article_url" => $row->article_url,
                    "author_user_id" => $row->author_user_id,
                    "content" => $this->cms_parse_keyword($content),
                    "author" => $row->author,
                    "date" => $row->date,
                    "comment_count" => $row->comment_count,
                    "photos" => $this->get_photos($row->article_id)
            );
        }
        return $data;
    }

    public function add_comment($article_id, $name, $email, $website, $content, $parent_comment_id=NULL){
        $query = $this->db->select('allow_comment')
            ->from($this->cms_complete_table_name('article'))
            ->where('article_id', $article_id)
            ->order_by('date')
            ->get();
        $row = $query->row();
        if(isset($row->allow_comment) && ($row->allow_comment == 1)){
            $cms_user_id = $this->cms_user_id();

            $data = array(
                    'article_id' => $article_id,
                    'name' => $name,
                    'email' => $email,
                    'website' => $website,
                    'content' => $content,
                    'date' => date('Y-m-d H:i:s'),
                    'read' => 0,
                    'parent_comment_id'=>$parent_comment_id,
            );
            if(isset($cms_user_id) && ($cms_user_id>0)){
                $data['author_user_id'] = $cms_user_id;
            }
            $this->db->insert($this->cms_complete_table_name('comment'), $data);
        }
    }

    public function get_photos($article_id){
        $query = $this->db->select('url')
            ->from($this->cms_complete_table_name('photo'))
            ->where('article_id', $article_id)
            ->get();

        $data = array();
        foreach($query->result() as $row){
            $result = array(
                    "url" => $row->url
            );
            $data[] = $result;
        }
        return $data;
    }
    
    private function preprocess_comment($row){
        $search = array('<', '>');
        $replace = array('&lt;', '&gt;');
        $user_id = $row->author_user_id;
        if(isset($user_id) && $user_id>0){
            $query_user = $this->db->select('real_name, user_name, email')
                ->from(cms_table_name('main_user'))
                ->where('user_id', $user_id)
                ->get();
            $row_user = $query_user->row();
            $name = trim($row_user->real_name)==''? $row_user->user_name: $row_user->real_name;
            $email = $row_user->email;
        }else{
            $name = $row->name;
            $email = $row->email;
        }
        $email = $email === NULL ? '' : $email;
        $website = $row->website === NULL ? '' : $row->website;
        $this->load->helper('url');
        $result = array(
                "comment_id" => $row->comment_id,
                "date" => date('Y-m-d'),
                "content" => str_replace($search, $replace, $row->content),
                "name" => $name,
                "website" => prep_url($website),
                "email" => $email,
                "gravatar_url" => 'http://www.gravatar.com/avatar/'.md5($email).'?s=32&r=pg&d=identicon'
        );
        return $result;
    }

    public function get_comments($article_id, $nested=TRUE){
        $this->db->select('comment_id, date, author_user_id, name, email, website, content')
            ->from($this->cms_complete_table_name('comment'))
            ->where('article_id', $article_id)
            ->order_by('date');
        if($nested){
            $this->db->where('parent_comment_id', NULL);
        }
        $query = $this->db->get();

        $data = array();
        foreach($query->result() as $row){
            $result = $this->preprocess_comment($row);
            $result['level'] = 0;
            $data[] = $result;
            $children = $this->get_child_comment($row->comment_id, 0);
            foreach($children as $child){
                $data[] = $child;
            }
        }
        return $data;
    }
    
    public function get_child_comment($comment_id, $level){
        $query = $this->db->select('comment_id, date, author_user_id, name, email, website, content')
            ->from($this->cms_complete_table_name('comment'))
            ->order_by('date')
            ->where('parent_comment_id', $comment_id)
            ->get();

        $data = array();
        foreach($query->result() as $row){
            $result = $this->preprocess_comment($row);
            $result['level'] = $level+1;
            $data[] = $result;
            $children = $this->get_child_comment($row->comment_id, $level+1);
            foreach($children as $child){
                $data[] = $child;
            }
        }
        return $data;
    }

    public function new_comment_num(){
        $notif = 0;
        if($this->cms_allow_navigate($this->cms_complete_navigation_name('manage_article'))){
            $query = $this->db->select('comment_id')
                ->from($this->cms_complete_table_name('comment'))
                ->where('read',0)
                ->get();
            $num_rows = $query->num_rows();
            if($num_rows > 0){
                $notif = $num_rows;
            }
        }
        return $notif;
    }

}