<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wp_exim extends CMS_Model{

    public function export(){

    }

    public function import($xml_string){
        $success = TRUE;
        $message = 'Import Success';
        // get structure
        $structure      = $this->load_structure_from_xml_string($xml_string);
        if($structure == NULL){
            return array('success' => FALSE, 'message' => 'invalid XML');
        }
        $wp_title       = $this->get_from_array($structure, 'title', '');
        $wp_link        = $this->get_from_array($structure, 'link', '');
        $wp_post_list   = $this->get_from_array($structure, 'post_list', array());
        // get navigation index
        $navigation_index = 0;
        $navigation_row = $this->db->select_max('index')
            ->from(cms_table_name('main_navigation'))
            ->where('parent_id', NULL)
            ->get()->row();
        if($navigation_row != NULL){
            $navigation_index = $navigation_row->index;
        }
        // page can be children of another page, we need to note down the actual id inserted to database
        $page_real_id_list = array();
        // import article and page
        $this->db->trans_start();
        foreach($wp_post_list as $wp_post){
            $page_real_id = NULL;
            // start to import
            $title              = $this->get_from_array($wp_post, 'title'); // Hello world
            $link               = $this->get_from_array($wp_post, 'link'); // http://domain/y/m/d/hello-world/
            $pubdate            = $this->get_from_array($wp_post, 'pubdate'); // Mon, 18 Apr 2016 01:27:30 +0000
            $creator            = $this->get_from_array($wp_post, 'creator', $this->cms_user_name()); // admin
            $description        = $this->get_from_array($wp_post, 'description');
            $content_encoded    = $this->get_from_array($wp_post, 'content_encoded'); // Welcome to wordpress ...
            $post_id            = $this->get_from_array($wp_post, 'post_id'); // 1
            $post_date          = $this->get_from_array($wp_post, 'post_date'); // 2016-04-18 01:27:30
            $post_date_gmt      = $this->get_from_array($wp_post, 'post_date_gmt'); // 2016-04-18 01:27:30
            $comment_status     = $this->get_from_array($wp_post, 'comment_status'); // open
            $ping_status        = $this->get_from_array($wp_post, 'ping_status'); // open
            $post_name          = $this->get_from_array($wp_post, 'post_name'); // hello-world
            $status             = $this->get_from_array($wp_post, 'status'); // publish
            $post_parent        = $this->get_from_array($wp_post, 'post_parent'); // 0
            $menu_order         = $this->get_from_array($wp_post, 'menu_order'); // 0
            $post_type          = $this->get_from_array($wp_post, 'post_type', 'post'); // post
            $post_password      = $this->get_from_array($wp_post, 'post_password');
            $is_sticky          = $this->get_from_array($wp_post, 'is_sticky'); // 0
            $guid_is_permalink  = $this->get_from_array($wp_post, 'quid_is_permalink'); // "false"
            $guid               = $this->get_from_array($wp_post, 'guid');
            $categories         = $this->get_from_array($wp_post, 'categories', array());
            $comments           = $this->get_from_array($wp_post, 'comments', array());
            // this is blog article
            if($post_type == 'post'){
                // current user become the author
                $user = $this->cms_get_record($this->cms_user_table_name(), 'user_id', $this->cms_user_id());
                // get article title and url
                $article_title = $title;
                $article_url = url_title($title);
                // if there are already the same article title then keep trying find a new one
                $new_article_title = $article_title;
                $i = 1;
                while($this->cms_record_exists($this->t('article'), 'article_title', $new_article_title)){
                    $new_article_title = $article_title . $i;
                    $i++;
                }
                $article_title = $new_article_title;
                // if there are already the same article url then keep trying find a new one
                $new_article_url = $article_url;
                $i = 1;
                while($this->cms_record_exists($this->t('article'), 'article_url', $new_article_url)){
                    $new_article_url = $article_url . $i;
                    $i++;
                }
                $article_url = $new_article_url;
                // insert article
                $this->db->insert($this->t('article'), array(
                    'article_title' => $article_title,
                    'article_url' => $article_url,
                    'description' => $description,
                    'date' => $post_date,
                    'author_user_id' => $user->user_id,
                    'content' => $content_encoded,
                    'allow_comment' => $comment_status == 'open'? 1: 0,
                    'status' => $status == 'publish'? 'published': 'draft',
                    'visited' => 0,
                    'featured' => $is_sticky,
                    'publish_date' => NULL,
                ));
                // get article
                $article = $this->cms_get_record($this->t('article'), 'article_title', $article_title);
                // categories
                foreach($categories as $category){
                    $category_name = $category['name'];
                    $category_name = substr($category_name, 0, 50);
                    if($category_name == 'Uncategorized'){
                        continue;
                    }
                    // get category
                    $category = $this->cms_get_record($this->t('category'), 'category_name', $category_name);
                    if($category === NULL){
                        $this->db->insert($this->t('category'), array('category_name' => $category_name));
                        $category = $this->cms_get_record($this->t('category'), 'category_name', $category_name);
                    }
                    // insert category article
                    $this->db->insert($this->t('category_article'), array(
                        'category_id' => $category->category_id,
                        'article_id' => $article->article_id,
                    ));
                }
                // comments
                $comment_id_map = array();
                foreach($comments as $comment){
                    $comment_id = $comment['id'];
                    $author = $comment['author'];
                    $author_email = $comment['author_email'];
                    $author_url = $comment['author_url'];
                    $date = $comment['date'];
                    $approved = $comment['approved'];
                    $user_id = $comment['user_id'];
                    $content = $comment['content'];
                    $parent = $comment['parent'];
                    // adjust parent
                    if($parent != 0 && array_key_exists($parent_comment_id, $comment_id_map)){
                        $parent_comment_id = $comment_id_map[$parent];
                    }else{
                        $parent_comment_id = NULL;
                    }
                    // adjust length
                    $author = substr($author, 0, 255);
                    $author_email = substr($author_email, 0, 255);
                    $author_url = substr($author_url, 0, 255);
                    // insert
                    $this->db->insert($this->t('comment'), array(
                        'date' => $date,
                        'author_user_id' => NULL,
                        'name' => $author,
                        'email' => $author_email,
                        'website' => $author_url,
                        'content' => $content,
                        'approved' => $approved,
                        'read' => 1,
                        'parent_comment_id' => $parent_comment_id,
                        'article_id' => $article->article_id,
                    ));
                    $new_comment_id = $this->db->insert_id();
                    // save id map
                    $commend_id_map[$comment_id] = $new_comment_id;
                }
            }else{ // this is static page
                if(trim($title) == ''){
                    $title = 'Page';
                }
                // assembly navigation_name
                $navigation_name = url_title($title);
                // if there is already the same navigation name then keep trying find a new one
                $new_navigation_name = $navigation_name;
                $i = 1;
                while($this->cms_record_exists(cms_table_name('main_navigation'), 'navigation_name', $new_navigation_name)){
                    $new_navigation_name = $navigation_name . $i;
                    // the navigation name should not be more than 100 character
                    if(strlen($new_navigation_name) > 100){
                        $navigation_name = substr($navigation_name,0, 100-strlen($i));
                        $new_navigation_name = $navigation_name;
                        $i=0;
                    }
                    $i++;
                }
                $navigation_name = $new_navigation_name;
                // the title length should be 50
                $title = substr($title, 0, 50);
                // add navigation
                $this->db->insert(cms_table_name('main_navigation'), array(
                    'navigation_name' => $navigation_name,
                    'title' => $title,
                    'page_title' => $title,
                    'authorization_id' => PRIV_EVERYONE,
                    'description' => $description,
                    'active' => 1,
                    'is_static' => 1,
                    'only_content' => 0,
                    'hidden' => 0,
                    'static_content' => $content_encoded,
                    'index' => $navigation_index
                ));
                $page_real_id = $this->db->insert_id();
                $navigation_index ++;
            }
            // append page real id list
            $page_real_id_list[$post_id] = $page_real_id;
        }
        // adjust parent id for newly pages
        for($i=0; $i<count($wp_post_list); $i++){
            $wp_post        = $wp_post_list[$i];
            $post_id    = $this->get_from_array($wp_post, 'post_id');
            $post_parent    = $this->get_from_array($wp_post, 'post_parent');
            $post_type      = $this->get_from_array($wp_post, 'post_type', 'post');
            if($post_type == 'post' || ($post_parent == 0)){
                continue;
            }
            // update
            $this->db->update(cms_table_name('main_navigation'),
                array('parent_id'=>$page_real_id_list[$post_parent]),
                array('navigation_id'=>$page_real_id_list[$post_id])
            );
        }
        $this->db->trans_complete();
        return array('success'=>$success, 'message'=>$message);
    }

    private function get_from_array($array, $key, $default = NULL){
        if(array_key_exists($key, $array)){
            return $array[$key];
        }else{
            return $default;
        }
    }

    private function load_structure_from_xml_string($contents){
        // taken from: https://gist.github.com/chrismeller/4941933
        date_default_timezone_set('UTC');
    	error_reporting(0);
    	ini_set('display_errors', true);
    	$dom = new DOMDocument( '1.0', 'utf-8' );
    	$dom->loadXML( $contents, LIBXML_NOCDATA );
    	$xpath = new DOMXPath( $dom );
    	$channel = $xpath->query( './channel' )->item(0);
        if($channel == NULL){
            return NULL;
        }
    	$title = $xpath->query( './title', $channel )->item(0)->nodeValue;
    	$link = $xpath->query( './link', $channel )->item(0)->nodeValue;
    	$description = $xpath->query( './description', $channel )->item(0)->nodeValue;
    	$pubdate = $xpath->query( './pubDate', $channel )->item(0)->nodeValue;
    	$generator = $xpath->query( './generator', $channel )->item(0)->nodeValue;
    	$language = $xpath->query( './language', $channel )->item(0)->nodeValue;
    	$base_site_url = $xpath->query( './wp:base_site_url', $channel )->item(0)->nodeValue;
    	$base_blog_url = $xpath->query( './wp:base_blog_url', $channel )->item(0)->nodeValue;
    	$wxr_version = $xpath->query( './wp:wxr_version', $channel )->item(0)->nodeValue;
    	$categories = $xpath->query( './wp:category', $channel );
    	$cats = array();
    	foreach ( $categories as $category ) {
    		$nicename = $xpath->query( './wp:category_nicename', $category )->item(0)->nodeValue;
    		$parent = $xpath->query( './wp:category_parent', $category )->item(0)->nodeValue;
    		$name = $xpath->query( './wp:cat_name', $category )->item(0)->nodeValue;
    		$cats[] = array(
    			'nicename' => $nicename,
    			'parent' => $parent,
    			'name' => $name,
    		);
    	}
    	$tags = $xpath->query( './wp:tag', $channel );
    	$ts = array();
    	foreach ( $tags as $tag ) {
    		$slug = $xpath->query( './wp:tag_slug', $tag )->item(0)->nodeValue;
    		$name = $xpath->query( './wp:tag_name', $tag )->item(0)->nodeValue;
    		$ts[] = array(
    			'slug' => $slug,
    			'name' => $name,
    		);
    	}
    	$items = $xpath->query( './item', $channel );
    	$is = array();
    	foreach ( $items as $item ) {
    		$i = array(
    			'title' => $xpath->query( './title', $item )->item(0)->nodeValue,
    			'link' => $xpath->query( './link', $item )->item(0)->nodeValue,
    			'pubdate' => $xpath->query( './pubDate', $item )->item(0)->nodeValue,
    			'creator' => $xpath->query( './dc:creator', $item )->item(0)->nodeValue,
    			'description' => $xpath->query( './description', $item )->item(0)->nodeValue,
    			'content_encoded' => $xpath->query( './content:encoded', $item )->item(0)->nodeValue,
    			'post_id' => $xpath->query( './wp:post_id', $item )->item(0)->nodeValue,
    			'post_date' => $xpath->query( './wp:post_date', $item )->item(0)->nodeValue,
    			'post_date_gmt' => $xpath->query( './wp:post_date_gmt', $item )->item(0)->nodeValue,
    			'comment_status' => $xpath->query( './wp:comment_status', $item )->item(0)->nodeValue,
    			'ping_status' => $xpath->query( './wp:ping_status', $item )->item(0)->nodeValue,
    			'post_name' => $xpath->query( './wp:post_name', $item )->item(0)->nodeValue,
    			'status' => $xpath->query( './wp:status', $item )->item(0)->nodeValue,
    			'post_parent' => $xpath->query( './wp:post_parent', $item )->item(0)->nodeValue,
    			'menu_order' => $xpath->query( './wp:menu_order', $item )->item(0)->nodeValue,
    			'post_type' => $xpath->query( './wp:post_type', $item )->item(0)->nodeValue,
    			'post_password' => $xpath->query( './wp:post_password', $item )->item(0)->nodeValue,
    			'excerpt_encoded' => null,
    			'is_sticky' => null,
    		);
    		$excerpt = $xpath->query( './excerpt:encoded', $item );
    		if ( $excerpt->length > 0 ) {
    			$i['excerpt_encoded'] = $excerpt->item(0)->nodeValue;
    		}
    		$is_sticky = $xpath->query( './wp:is_sticky', $item );
    		if ( $is_sticky->length > 0 ) {
    			$i['is_sticky'] = $is_sticky->item(0)->nodeValue;
    		}
    		$guid = $xpath->query( './guid', $item )->item(0);
    		$i['guid_is_permalink'] = $guid->getAttribute( 'isPermaLink' );
    		$i['guid'] = $guid->nodeValue;
    		$categories = $xpath->query( './category', $item );
    		$i['categories'] = array();
    		foreach ( $categories as $category ) {
    			$cat = array(
    				'name' => $category->nodeValue,
    				'domain' => $category->getAttribute( 'domain' ),
    				'nicename' => $category->getAttribute( 'nicename' ),
    			);
    			$i['categories'][] = $cat;
    		}
    		$comments = $xpath->query( './wp:comment', $item );
    		$i['comments'] = array();
    		foreach ( $comments as $comment ) {
    			$c = array(
    				'id' => $xpath->query( './wp:comment_id', $comment )->item(0)->nodeValue,
    				'author' => $xpath->query( './wp:comment_author', $comment )->item(0)->nodeValue,
    				'author_email' => $xpath->query( './wp:comment_author_email', $comment )->item(0)->nodeValue,
    				'author_url' => $xpath->query( './wp:comment_author_url', $comment )->item(0)->nodeValue,
    				'author_ip' => $xpath->query( './wp:comment_author_IP', $comment )->item(0)->nodeValue,
    				'date' => $xpath->query( './wp:comment_date', $comment )->item(0)->nodeValue,
    				'date_gmt' => $xpath->query( './wp:comment_date_gmt', $comment )->item(0)->nodeValue,
    				'content' => $xpath->query( './wp:comment_content', $comment )->item(0)->nodeValue,
    				'approved' => $xpath->query( './wp:comment_approved', $comment )->item(0)->nodeValue,
    				'type' => $xpath->query( './wp:comment_type', $comment )->item(0)->nodeValue,
    				'parent' => $xpath->query( './wp:comment_parent', $comment )->item(0)->nodeValue,
    				'user_id' => $xpath->query( './wp:comment_user_id', $comment )->item(0)->nodeValue,
    			);
    			$i['comments'][] = $c;
    		}
    		$meta = $xpath->query( './wp:postmeta', $item );
    		$i['meta'] = array();
    		foreach ( $meta as $metar ) {
    			$key = $xpath->query( './wp:meta_key', $metar )->item(0)->nodeValue;
    			$value = $xpath->query( './wp:meta_value', $metar )->item(0)->nodeValue;
    			$i['meta'][ $key ] = $value;
    		}
    		$is[] = $i;
    	}
    	return array('title' => $title, 'link' => $link, 'post_list'=>$is);
    }

}
