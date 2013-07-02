<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Article_Model
 *
 * @author No-CMS Module Generator
 */
class Article_Model extends  CMS_Model{
    public $page_break_separator = '';

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
        $this->page_break_separator = '/(<div style=\"page-break-after: always;\">\s*<span style=\"display: none;\">&nbsp;<\/span><\/div>)/i';
    }

    public function get_count_article_url($article_url){
        $SQL = "SELECT article_id FROM ".$this->cms_complete_table_name('article').
        " WHERE article_url ='".addslashes($article_url)."'";
        $query = $this->db->query($SQL);
        return $query->num_rows();
    }

    public function get_article_url($article_id){
        $SQL = "
            SELECT
                article_id, article_url
            FROM ".$this->cms_complete_table_name('article')."
            WHERE article_id = $article_id";
        $query = $this->db->query($SQL);
        if($query->num_rows()>0){
            $row = $query->row();
            return $row->article_url;
        }else{
            return false;
        }
    }


    public function get_available_category(){
        $result = array(''=>'All Category');
        $SQL = "SELECT category_name FROM ".$this->cms_complete_table_name('category');
        $query = $this->db->query($SQL);
        foreach($query->result() as $row){
            $result[$row->category_name] = $row->category_name;
        }
        return $result;
    }

    public function get_single_article($article_url){
        $where_article_url = isset($article_url)?
            "article_url = '".addslashes($article_url)."'":"TRUE";

        $SQL = "
            SELECT
                article_id, article_title, article_url, content, date, allow_comment,
                real_name AS author
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
                    "content" => $this->cms_parse_keyword($content),
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

    public function get_articles($page, $limit, $category, $search){
        $words = $search? explode(' ', $search) : array();

        $data = array();

        $where_category = isset($category) && ($category!="")? "article_id IN
            (SELECT article_id FROM ".$this->cms_complete_table_name('category_article').", ".$this->cms_complete_table_name('category')."
            WHERE ".$this->cms_complete_table_name('category').".category_id = ".$this->cms_complete_table_name('category_article').".category_id
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

        $offset = $page * $limit;
        $SQL = "
            SELECT
                article_id, article_title, article_url, content, date, allow_comment,
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
                $where_search
            ORDER BY date DESC, article_id DESC
            LIMIT $offset, $limit";

        $query = $this->db->query($SQL);
        foreach($query->result() as $row){
            $contents = preg_split($this->page_break_separator, $row->content);
            $content = $contents[0];

            $data[] = array(
                    "id" => $row->article_id,
                    "title" => $row->article_title,
                    "article_url" => $row->article_url,
                    "content" => $this->cms_parse_keyword($content),
                    "author" => $row->author,
                    "date" => $row->date,
                    "comment_count" => $row->comment_count,
                    "photos" => $this->get_photos($row->article_id)
            );
        }
        return $data;
    }

    public function add_comment($article_id, $name, $email, $website, $content){
        $SQL = "SELECT allow_comment FROM ".$this->cms_complete_table_name('article').
        " WHERE article_id = $article_id";
        $query = $this->db->query($SQL);
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
            );
            if(isset($cms_user_id) && ($cms_user_id>0)){
                $data['author_user_id'] = $cms_user_id;
            }
            $this->db->insert($this->cms_complete_table_name('comment'), $data);
        }
    }

    public function get_photos($article_id){
        $SQL = "SELECT url FROM ".$this->cms_complete_table_name('photo')." WHERE article_id = '".$article_id."'";
        $query = $this->db->query($SQL);

        $data = array();
        foreach($query->result() as $row){
            $result = array(
                    "url" => $row->url
            );
            $data[] = $result;
        }
        return $data;
    }

    public function get_comments($article_id){
        $search = array('<', '>');
        $replace = array('&lt;', '&gt;');

        $SQL = "SELECT comment_id, date, author_user_id, name, email, website, content
        FROM ".$this->cms_complete_table_name('comment')."
        WHERE article_id = '$article_id' ORDER BY `date` asc";
        $query = $this->db->query($SQL);

        $data = array();
        foreach($query->result() as $row){
            $user_id = $row->author_user_id;
            if(isset($user_id) && $user_id>0){
                $query_user = $this->db->select('real_name, user_name')
                    ->from(cms_table_name('main_user'))
                    ->where('user_id', $user_id)
                    ->get();
                $row_user = $query_user->row();
                $name = trim($row_user->real_name)==''? $row_user->user_name: $row_user->real_name;
            }else{
                $name = $row->name;
            }
            $this->load->helper('url');
            $result = array(
                    "date" => date('Y-m-d'),
                    "content" => str_replace($search, $replace, $row->content),
                    "name" => $name,
                    "website" => prep_url($row->website)
            );
            $data[] = $result;
        }
        return $data;
    }

}