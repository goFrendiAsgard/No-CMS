<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Article_Model
 *
 * @author No-CMS Module Generator
 */
class Article_model extends  CMS_Model{
    public $page_break_separator = '<!--more-->';

    protected static $__article_properties;

    public function __construct(){
        parent::__construct();
        if(self::$__article_properties == NULL){
            self::$__article_properties = array();
        }
        $default_properties = array(
                'is_article_cached'             => FALSE,
                'is_category_cached'            => FALSE,
                'is_category_article_cached'    => FALSE,
                'articles'                      => array(),
                'categories'                    => array(),
                'article_categories'            => array(),
                'photos'                        => array(),
            );
        foreach($default_properties as $key=>$val){
            if(!array_key_exists($key, self::$__article_properties)){
                self::$__article_properties[$key] = $default_properties[$key];
            }
        }
    }

    protected function __cache_articles(){
        if(!self::$__article_properties['is_article_cached']){
            self::$__article_properties['articles'] = array();
            $query = $this->db->select('article_id, article_title, article_url, status, date, publish_date, featured, visited')
                ->from($this->t('article'))
                ->get();
            self::$__article_properties['articles'] = array();
            foreach($query->result() as $row){
                self::$__article_properties['articles'][] = array(
                        'article_id'        => $row->article_id,
                        'article_title'     => $row->article_title,
                        'article_url'       => $row->article_url,
                        'date'              => $row->status == 'scheduled'? $row->publish_date: $row->date,
                        'featured'          => $row->featured == 1,
                        'visited'           => $row->visited,
                    );
            }
            self::$__article_properties['is_article_cached'] = TRUE;
        }
        return self::$__article_properties['articles'];
    }

    protected function __cache_categories(){
        if(!self::$__article_properties['is_category_cached']){
            self::$__article_properties['categories'] = array();
            $query = $this->db->select('category_id, category_name')
                ->from($this->t('category'))
                ->get();
            self::$__article_properties['categories'] = array();
            foreach($query->result() as $row){
                self::$__article_properties['categories'][] = array(
                        'category_id'   => $row->category_id,
                        'category_name' => $row->category_name,
                    );
            }
            self::$__article_properties['is_category_cached'] = TRUE;
        }
        return self::$__article_properties['categories'];
    }

    protected function __cache_category_articles(){
        if(!self::$__article_properties['is_category_article_cached']){
            self::$__article_properties['category_articles'] = array();
            $query = $this->db->select('category_id, article_id')
                ->from($this->t('category_article'))
                ->get();
            self::$__article_properties['category_articles'] = array();
            foreach($query->result() as $row){
                self::$__article_properties['category_articles'][] = array(
                        'category_id'   => $row->category_id,
                        'article_id'    => $row->article_id,
                    );
            }
            self::$__article_properties['is_category_article_cached'] = TRUE;
        }
        return self::$__article_properties['category_articles'];
    }

    public function get_archive(){
        $data = array();
        foreach($this->__cache_articles() as $article){
            $str = substr($article['date'], 0, 7);
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
        		      SELECT COUNT(comment_id) FROM '.$this->t('comment').'
                      WHERE article_id = article.article_id
                  ) as comment_count
		      ')
			->from($this->t('article').' as article')
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

    public function get_count_article_url($article_url){
        $result = 0;
        foreach($this->__cache_articles() as $article){
            if($article['article_url'] == $article_url){
                $result ++;
            }
        }
        return $result;
    }

    public function get_article_url($article_id){
        foreach($this->__cache_articles() as $article){
            if($article['article_id'] == $article_id){
                return $article['article_url'];
            }
        }
        return FALSE;
    }


    public function get_available_category(){
        $result = array(''=>'All Category');
        foreach($this->__cache_categories() as $category){
            foreach($this->__cache_category_articles() as $category_article){
                if($category['category_id'] == $category_article['category_id']){
                    $result[$category['category_name']] = $category['category_name'];
                }
            }
        }
        return $result;
    }

    public function get_single_article($article_url){
        $where_article_url = isset($article_url)?
            "article_url = '".addslashes($article_url)."'":"(1=1)";

        $SQL = "
            SELECT
                article_id, article_title, article_url, content, date, keyword, description, allow_comment,
                real_name AS author, author_user_id, visited, status, publish_date
            FROM ".$this->t('article')."
            LEFT JOIN ".$this->cms_user_table_name()." ON (".$this->cms_user_table_name().".user_id = ".$this->t('article').".author_user_id)
            WHERE $where_article_url";

        $query = $this->db->query($SQL);
        if($query->num_rows()>0){
            $row = $query->row();
            $contents = explode($this->page_break_separator, $row->content);
            //$contents = preg_split($this->page_break_separator, $row->content);
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
                    "date" => $row->status == 'scheduled'? $row->publish_date: $row->date,
                    "allow_comment" => $row->allow_comment,
                    "comments" => $this->get_comments($row->article_id),
                    "photos" => $this->get_photos($row->article_id),
                    "categories" => $this->get_category($row->article_id),
                    "related_article"=>$this->get_related_article($row->article_id),
                    "visited"=>$row->visited,
            );
            return $result;
        }else{
            return false;
        }
    }

    public function get_articles($page, $limit, $category, $archive='', $search=NULL, $featured=FALSE, $order_by='date'){
        $words = $search? explode(' ', $search) : array();

        $data = array();

        $where_category = isset($category) && ($category!="")? "article_id IN
            (SELECT article_id FROM ".$this->t('category_article').", ".$this->t('category')."
            WHERE ".$this->t('category').".category_id = ".$this->t('category_article').".category_id
            AND category_name ='".addslashes(urldecode($category))."'
            )" : "(1=1)";

        $where_featured = $featured? 'featured=1' : '(1=1)';

        // to use LIKE in postgre, the field should be converted to text
        if((isset($this->db->driver) && $this->db->driver == 'postgre') || (isset($this->db->subdriver) && $this->db->subdriver == 'pgsql')){
            $date_field_as_string = 'date::text';
        }else{
            $date_field_as_string = 'date';
        }

        if($search){
            // relevance (this sql works for mysql)
            if((isset($this->db->driver) && ($this->db->driver == 'mysql' || $this->db->driver == 'mysqli')) || (isset($this->db->subdriver) && ($this->db->subdriver == 'mysql' || $this->db->subdriver == 'mysqli'))){
                $key = 'COUNT(article_id)>0';
            }else if((isset($this->db->driver) && $this->db->driver == 'postgre') || (isset($this->db->subdriver) &&$this->db->subdriver == 'pgsql')){
                $key = '(COUNT(article_id)>0)::int';
            }else{
                $key = 'COUNT(article_id)';
            }
            $relevance = '( 0';
            foreach($words as $word){
                $relevance .= '+ (SELECT '.$key.' FROM '.$this->t('article')." WHERE article_title LIKE '%".addslashes($word)."%' OR content LIKE '%".addslashes($word)."%')";
            }
            $relevance .= ')';
            // where search
            $where_search = "(FALSE ";
            foreach($words as $word){
                $where_search .= " OR (article_title LIKE '%".addslashes($word)."%' OR content LIKE '%".addslashes($word)."%')";
            }
            $where_search .=")";
        }else{
            $relevance = '1';
            $where_search = "(1=1)";
        }
        $current_date = date('Y-m-d').' 23:59:59';
        $offset = $page * $limit;
        $SQL = "
            SELECT
                article_id, article_title, article_url, content, date, allow_comment, author_user_id,
                real_name AS author, publish_date, status, visited,
                (
                  SELECT COUNT(comment_id) FROM ".$this->t('comment')."
                  WHERE article_id = ".$this->t('article').".article_id
                ) as comment_count,
                ".$relevance." AS relevance
            FROM ".$this->t('article')."
            LEFT JOIN ".$this->cms_user_table_name().
                " ON (".$this->cms_user_table_name().".user_id = ".$this->t('article').".author_user_id)
            WHERE
                $where_category AND
                $where_search AND
                $where_featured AND
                $date_field_as_string LIKE '$archive%' AND
                (status = 'published' OR (status='scheduled' AND publish_date <= '".$current_date."'))
            ORDER BY relevance DESC, ".$order_by." DESC, article_id DESC
            LIMIT $limit OFFSET $offset";

        $query = $this->db->query($SQL);
        foreach($query->result() as $row){
            //$contents = preg_split($this->page_break_separator, $row->content);
            $contents = explode($this->page_break_separator, $row->content);
            $content = $contents[0];

            $data[] = array(
                    "id" => $row->article_id,
                    "title" => $row->article_title,
                    "article_url" => $row->article_url,
                    "author_user_id" => $row->author_user_id,
                    "content" => $this->cms_parse_keyword($content),
                    "author" => $row->author,
                    "date" => $row->status == 'scheduled'? $row->publish_date: $row->date,
                    "comment_count" => $row->comment_count,
                    "photos" => $this->get_photos($row->article_id),
                    "categories" => $this->get_category($row->article_id),
                    "visited" => $row->visited,
            );
        }
        return $data;
    }

    public function add_comment($article_id, $name, $email, $website, $content, $parent_comment_id=NULL){
        $query = $this->db->select('allow_comment')
            ->from($this->t('article'))
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
                    'approved' => $this->cms_get_config('blog_moderation') == 'TRUE'? 0 : 1,
            );
            if(isset($cms_user_id) && ($cms_user_id>0)){
                $data['author_user_id'] = $cms_user_id;
            }
            $this->db->insert($this->t('comment'), $data);
        }
    }

    public function get_photos($article_id){
        // return from cache if cache exists
        if(array_key_exists($article_id, self::$__article_properties['photos'])){
            return self::$__article_properties['photos'][$article_id];
        }
        $query = $this->db->select('photo_id, caption, url')
            ->from($this->t('photo'))
            ->where('article_id', $article_id)
            ->order_by('index')
            ->get();

        $data = array();
        foreach($query->result() as $row){
            $result = array(
                    "id"      => $row->photo_id,
                    "caption" => $row->caption,
                    "url"     => $row->url
                );
            $data[] = $result;
        }
        self::$__article_properties['photos'][$article_id] = $data;
        return $data;
    }

    public function get_category($article_id){
        $data = array();
        foreach($this->__cache_category_articles() as $category_article){
            if($category_article['article_id'] == $article_id){
                foreach($this->__cache_categories() as $category){
                    if($category['category_id'] == $category_article['category_id']){
                        $result = array(
                                'id'=>$category['category_id'],
                                'name'=>$category['category_name']
                            );
                        $data[] = $result;
                    }
                }
            }
        }
        return $data;
    }

    public function get_related_article($article_id){
        $categories = $this->get_category($article_id);
        if(count($categories) == 0){
            $where_category = '(1=1)';
        }else{
            $where_category = array();
            foreach($categories as $category){
                $category_id = $category['id'];
                $where_category[] = 'category_id = '.$category_id;
            }
            $where_category = implode(' OR ', $where_category);
        }
        $sql = 'SELECT DISTINCT a.article_id, a.article_title, a.article_url, a.status, a.date, a.publish_date
            FROM '.$this->t('article').' as a, '.
            $this->t('category_article').' as ca '.
            'WHERE ca.article_id = a.article_id AND ca.article_id <> '.$article_id.' AND ('.$where_category.') LIMIT 4';
        $query = $this->db->query($sql);
        $data = array();
        foreach($query->result() as $row){
            $result = array(
                    'id'=>$row->article_id,
                    'title'=>$row->article_title,
                    'article_url'=>$row->article_url,
                    'date' => $row->status == 'scheduled'? $row->publish_date: $row->date,
                    'photos' => $this->get_photos($row->article_id),
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
                ->from($this->cms_user_table_name())
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

        // get profile picture
        $pp = $this->cms_get_profile_picture($user_id, 50);

        $result = array(
                "comment_id" => $row->comment_id,
                "date" => date('Y-m-d', strtotime($row->date)),
                "content" => str_replace($search, $replace, $row->content),
                "name" => $name,
                "website" => prep_url($website),
                "email" => $email,
                "gravatar_url" => $pp,
        );
        return $result;
    }

    public function get_comments($article_id, $nested=TRUE){
        $this->db->select('comment_id, date, author_user_id, name, email, website, content, parent_comment_id')
            ->from($this->t('comment'))
            ->where('article_id', $article_id)
            ->where('approved', 1)
            ->order_by('date');
        $query = $this->db->get();
        // comment's record set
        $recordset = $query->result();

        $data = array();
        foreach($recordset as $row){
            if($nested && $row->parent_comment_id != ''){
                continue;
            }
            $result = $this->preprocess_comment($row);
            $result['level'] = 0;
            $data[] = $result;
            $children = $this->get_child_comment($row->comment_id, 0, $recordset);
            foreach($children as $child){
                $data[] = $child;
            }
        }
        return $data;
    }

    public function get_child_comment($comment_id, $level, $recordset){
        $data = array();
        foreach($recordset as $row){
            if($row->parent_comment_id != $comment_id){
                continue;
            }
            $result = $this->preprocess_comment($row);
            $result['level'] = $level+1;
            $data[] = $result;
            $children = $this->get_child_comment($row->comment_id, $level+1, $recordset);
            foreach($children as $child){
                $data[] = $child;
            }
        }
        return $data;
    }

    public function new_comment_num(){
        $notif = 0;
        if($this->cms_allow_navigate($this->n('manage_article'))){
            $query = $this->db->select('comment_id')
                ->from($this->t('comment'))
                ->where('read',0)
                ->get();
            $num_rows = $query->num_rows();
            if($num_rows > 0){
                $notif = $num_rows;
            }
        }
        return $notif;
    }

    public function build_content($str){
        $str_parts = explode(PHP_EOL, $str);
        $content = '';
        foreach($str_parts as $part){
            $part = str_replace(array("\r","\n"),"", $part);
            if($part != ''){
                $content .= '<p>'.str_replace(PHP_EOL, '<br />', $part).'</p>';
            }
        }
        return $content;
    }

}
