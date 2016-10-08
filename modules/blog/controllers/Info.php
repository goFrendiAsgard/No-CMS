<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for blog_new
 *
 * @author No-CMS Module Generator
 */
class Info extends CMS_Module {

    //////////////////////////////////////////////////////////////////////////////
    // NAVIGATIONS
    //////////////////////////////////////////////////////////////////////////////
    protected $NAVIGATIONS = array(
            // Blog New
            array(
                'navigation_name'   => 'index',
                'url'               => 'blog',
                'authorization_id'  => PRIV_EVERYONE,
                'default_layout'    => NULL,
                'title'             => 'Blog',
                'parent_name'       => NULL,
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => 'glyphicon-pencil',
                'notification_url'  => '{{ module_path }}/notif/new_comment',
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),
            array(
                'navigation_name'   => 'setting',
                'url'               => 'setting',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Blog Setting',
                'parent_name'       => 'index',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),
            array(
                'navigation_name'   => 'import',
                'url'               => 'blog/import',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => NULL,
                'title'             => 'Import From Wordpress\'s XML',
                'parent_name'       => 'setting',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => 'glyphicon-import',
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),
            /*
            array(
                'navigation_name'   => 'export',
                'url'               => 'blog/export',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => NULL,
                'title'             => 'Export To Wordpress\'s XML',
                'parent_name'       => 'setting',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => 'glyphicon-export',
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            )
            */
        );

    protected $BACKEND_NAVIGATIONS = array(
            // Manage Article
            array(
                'entity_name'       => 'article',
                'url'               => 'manage_article',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage Article',
                'parent_name'       => 'index',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => '{{ module_path }}/notif/new_comment',
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),
            // Manage Category
            array(
                'entity_name'       => 'category',
                'url'               => 'manage_category',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage Category',
                'parent_name'       => 'index',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),

        );

    //////////////////////////////////////////////////////////////////////////////
    // CONFIGURATIONS
    //////////////////////////////////////////////////////////////////////////////
    protected $CONFIGS = array(
            array('config_name' => 'blog_moderation', 'value' => 'FALSE'),
            array('config_name' => 'blog_max_slide_image', 'value' => 6),
            array(
                'config_name'   => 'blog_article_record_template',
                'value'         => NULL, // if set to NULL, the value will be taken from config/module_config.php
            ),
        );

    //////////////////////////////////////////////////////////////////////////////
    // PRIVILEGES
    //////////////////////////////////////////////////////////////////////////////
    protected $PRIVILEGES = array(
            array(
                'privilege_name'   => 'edit_article_record_template',
                'authorization_id' => PRIV_AUTHORIZED,
            ),
        );

    //////////////////////////////////////////////////////////////////////////////
    // GROUPS
    //////////////////////////////////////////////////////////////////////////////
    protected $GROUPS = array(
            array('group_name' => 'Blog Editor', 'description' => 'Can Add, Edit, Delete & Publish other\'s articles'),
            array('group_name' => 'Blog Author', 'description' => 'Can Add, Edit, Delete & Publish his/her own articles'),
            array('group_name' => 'Blog Contributor', 'description' => 'Can Add, Edit, and Delete his/her own articles'),
        );
    protected $GROUP_NAVIGATIONS = array();
    protected $GROUP_BACKEND_NAVIGATIONS = array(
            'Blog Editor'      => array('article', 'category'),
            'Blog Author'      => array('article'),
            'Blog Contributor' => array('article'),
        );
    protected $GROUP_PRIVILEGES = array();
    protected $GROUP_BACKEND_PRIVILEGES = array(
            'Blog Editor' => array(
                'article' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
                'category' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
            ),
            'Blog Author' => array(
                'article' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
            ),
            'Blog Contributor' => array(
                'article' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
            )
        );

    //////////////////////////////////////////////////////////////////////////////
    // TABLES and DATA
    //////////////////////////////////////////////////////////////////////////////
    protected $TABLES = array(
        // article
        'article' => array(
            'key'    => 'article_id',
            'fields' => array(
                'article_id'       => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'article_title'    => array("type" => 'text',       "null" => TRUE),
                'article_url'      => array("type" => 'text',       "null" => TRUE),
                'keyword'          => array("type" => 'text',       "null" => TRUE),
                'description'      => array("type" => 'text',       "null" => TRUE),
                'date'             => array("type" => 'datetime',   "null" => TRUE),
                'author_user_id'   => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'content'          => array("type" => 'text',       "null" => TRUE),
                'allow_comment'    => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'status'           => array("type" => 'varchar',    "constraint" => 20,  "null" => TRUE, "default" => "draft"),
                'visited'          => array("type" => 'int',        "constraint" => 10,  "null" => TRUE, "default" => 0),
                'featured'         => array("type" => 'int',        "constraint" => 10,  "null" => TRUE, "default" => 0),
                'publish_date'     => array("type" => 'datetime',   "null" => TRUE),
                'photos'           => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
                'comments'         => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
                'category_article' => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
            ),
        ),
        // category
        'category' => array(
            'key'    => 'category_id',
            'fields' => array(
                'category_id'      => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'category_name'    => array("type" => 'varchar',    "constraint" => 50,  "null" => TRUE),
                'description'      => array("type" => 'text',       "null" => TRUE),
                'category_article' => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
            ),
        ),
        // category_article
        'category_article' => array(
            'key'    => 'category_article_id',
            'fields' => array(
                'category_article_id' => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'category_id'     => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'article_id'      => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
            ),
        ),
        // comment
        'comment' => array(
            'key'    => 'comment_id',
            'fields' => array(
                'comment_id'       => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'article_id'       => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'date'             => array("type" => 'timestamp',  "null" => TRUE),
                'author_user_id'   => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'name'             => array("type" => 'varchar',    "constraint" => 255,  "null" => TRUE),
                'email'            => array("type" => 'varchar',    "constraint" => 255,  "null" => TRUE),
                'website'          => array("type" => 'varchar',    "constraint" => 255,  "null" => TRUE),
                'content'          => array("type" => 'text',       "null" => TRUE),
                'parent_comment_id'=> array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'read'             => array("type" => 'int',        "constraint" => 10,  "null" => TRUE, "default" => 0),
                'approved'         => array("type" => 'int',        "constraint" => 10,  "null" => TRUE, "default" => 0),
            ),
        ),
        // photo
        'photo' => array(
            'key'    => 'photo_id',
            'fields' => array(
                'photo_id'        => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'article_id'      => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'url'             => array("type" => 'text',       "null" => TRUE),
                'index'           => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'caption'         => array("type" => 'text',       "null" => TRUE),
            ),
        ),
        // publication_status
        'publication_status' => array(
            'key'    => 'status',
            'fields' => array(
                'status'          => array("type" => 'varchar',    "constraint" => 50,  "null" => FALSE),
            ),
        ),
    );
    protected $DATA = array(
        'publication_status' => array(
            array('status' => 'draft'),
            array('status' => 'published'),
            array('status' => 'scheduled'),
        ),
        'photo' => array(
            array('photo_id' => '1', 'article_id' => '1', 'url' => 'main_01.jpg', 'index' => '1', 'caption' => 'Caption for the #1 photo.'),
            array('photo_id' => '2', 'article_id' => '1', 'url' => 'main_02.jpg', 'index' => '2', 'caption' => 'Caption for the #2 photo.'),
            array('photo_id' => '3', 'article_id' => '1', 'url' => 'main_03.jpg', 'index' => '3', 'caption' => 'Caption for the #3 photo.'),
            array('photo_id' => '4', 'article_id' => '1', 'url' => 'main_04.jpg', 'index' => '4', 'caption' => 'Caption for the #4 photo.'),
            array('photo_id' => '5', 'article_id' => '1', 'url' => 'main_05.jpg', 'index' => '5', 'caption' => 'Caption for the #5 photo.'),
            array('photo_id' => '6', 'article_id' => '1', 'url' => 'main_06.jpg', 'index' => '6', 'caption' => 'Caption for the #6 photo.'),
            array('photo_id' => '7', 'article_id' => '1', 'url' => 'main_07.jpg', 'index' => '7', 'caption' => 'Caption for the #7 photo.'),
            array('photo_id' => '8', 'article_id' => '1', 'url' => 'main_08.jpg', 'index' => '8', 'caption' => 'Caption for the #8 photo.'),
        ),
        'comment' => array(
            array('comment_id' => '1', 'article_id' => '1', 'date' => '2013-03-25 09:53:16', 'author_user_id' => '1', 'name' => '', 'email' => '', 'website' => '', 'content' => 'Great comment for great article', 'parent_comment_id' => NULL, 'read' => '0', 'approved' => '1'),
        ),
        'category_article' => array(
            array('category_article_id' => '1', 'category_id' => '2', 'article_id' => '1'),
        ),
        'category' => array(
            array('category_id' => '1', 'category_name' => 'News', 'description' => ''),
            array('category_id' => '2', 'category_name' => 'Fun', 'description' => ''),
        ),
        'article' => array(
            array('article_id' => '1', 'article_title' => 'Scandal, A Pop Rock Girl Band From Osaka', 'article_url' => 'scandal', 'keyword' => 'scandal, pop rock, girl, band, osaka', 'description' => 'Scandal is a pop rock girl band from Osaka, Japan, formed by four high school girls', 'date' => '2013-03-25 09:50:49', 'author_user_id' => '1', 'content' => '<p style="text-align: justify;">\n    SCANDAL (スキャンダル Sukyandaru, stylized as SCANDAL) is a Japanese pop rock girl band from Osaka, Japan. Formed in August 2006 by four high school girls, they started playing street lives until they were noticed and signed to the indie label Kitty Records. In 2008, they released three singles and a mini-album while performing shows in the United States, France, and Hong Kong. That October, Scandal released their major debut single, "Doll", under Epic Records Japan.\n</p>\n<p style="text-align: justify;">\n    The band has performed the theme songs for many anime, including "Shojo S" for Bleach and "Shunkan Sentimental" for Fullmetal Alchemist: Brotherhood. With numerous overseas performances and anime theme songs, Scandal has built a considerable international fanbase.\n</p>\n<!--more-->\n<h3 style="text-align: justify;">\n    Indie career\n</h3>\n<p style="text-align: justify;">\n    Scandal was formed in August 2006 by four high school girls. The girls, Haruna, Mami, Tomomi, and Rina, met in an Osaka vocal and dance school called Caless. Shortly thereafter, they started performing street lives every weekend at Shiroten in Osaka Castle Park. Soon, they started getting offers from clubs in Osaka and Kyoto. The band\'s name originates from a sign near Studio Brotherz, a studio where they practiced in their early days. The studio is on the sixth floor of a building shared with other businesses, namely adult shops. The girls decided to choose the biggest sign among the shops, "Scandal" (スキャンダル Sukyandaru), as the name for their band.\n</p>\n<p style="text-align: justify;">\n    Scandal signed with indie label Kitty Records and released three singles exclusive to Tower Records in 2008. The first, "Space Ranger", ranked #2 on the Tower indie charts and the other two, "Koi Moyou" and "Kagerou", ranked #1. In March, they embarked on the Japan Nite US tour 2008, touring six major cities in the United States. They also performed at Sakura-Con, one of the largest anime conventions in the United States. In July, they performed in front of 10,000 people at France\'s Japan Expo and also at Hong Kong\'s Animation-Comic-Game Hong Kong in August. Scandal concluded their indie career with the release of their first mini-album, Yah! Yah! Yah! Hello Scandal: Maido! Scandal Desu! Yah Yah Yah!.\n</p>\n<h3 style="text-align: justify;">\n    Major debut\n</h3>\n<p style="text-align: justify;">\n    2008 continued to be an eventful year for Scandal. In October, they made their major debut on Epic Records Japan with the single "Doll". It gave them more exposure, including appearances on mainstream music television shows like Music Station. The band released their second major single "Sakura Goodbye" in March 2009 to commemorate Mami and Tomomi\'s high school graduation. The song is a new version of their indie song, "Sakura", only heard live. The following month, their then upcoming third major single "Shoujo S" was used as the tenth opening theme for the anime Bleach. This brought their popularity up even higher as the single ranked #6 on the Oricon charts when it was released two months later in June.\n</p>\n<p style="text-align: justify;">\n    On October 14th, Scandal released their fourth major single, "Yumemiru Tsubasa" which was followed with their major debut album, Best Scandal, the next week. The album ranked #5 on the Oricon weekly chart, making them the first girl band since Zone to have a debut album chart in the top five. In December, Scandal embarked on their first one-man tour. Concluding the year, the band won a New Artist Award at the 51st Japan Record Award, but lost the Best New Artist Award to Big Bang.\n</p>\n<p style="text-align: justify;">\n    2010 began with Scandal\'s fifth major single in February, "Shunkan Sentimental". It was used as the fourth ending theme for the anime Fullmetal Alchemist: Brotherhood. The following month, they embarked on a spring tour, Scandal: Shunkan Sakura Zensen Tour 2010 Spring. Prior to its start, the band conducted a Twitter poll to choose a song to cover for the tour. The winner was "Secret Base (Kimi ga Kureta Mono)", which was chosen out of over 600 candidates. In June, Scandal released a pop tune for summer, "Taiyou to Kimi ga Egaku Story", followed by their first original ballad, "Namida no Regret", in July.\n</p>\n<p style="text-align: justify;">\n    Between the end of July and the beginning of August, Scandal traveled to Hong Kong. The band performed for the third consecutive year at the Animation-Comic-Game Hong Kong convention and held their first one-man live concert in Hong Kong, which sold out. SCANDAL was also featured on the cover of the Hong Kong magazine re:spect music magazine, and their previously released single "Taiyou to Kimi ga Egaku Story" reached #1 on the Radio Television Hong Kong J-pop chart. Earlier in the year, they were awarded a bronze newcomer award by RTHK, similar to the Hong Kong Grammys.\n</p>\n<p style="text-align: justify;">\n    After returning to Japan, Scandal released their second album, Temptation Box, on August 11. The album debuted at #3 on the Oricon weekly chart, making them the first girl band to have an album chart in the top three in over a year since Chatmonchy\'s Kokuhaku. The album was also released in 42 other countries worldwide.\n</p>\n<p style="text-align: justify;">\n    Later in August, Scandal provided the theme, insert, and ending songs for the animated film Loups=Garous, which premiered on the 28th in most of Japan. The songs were "Midnight Television", "Koshi-Tantan", and "Sayonara My Friend", respectively. The band also appeared as themselves, marking their big-screen debut as a band. They were shown in a musical performance scene that was created with the help of motion capture, providing a realistic representation of the band\'s movements. Each member also had a role voicing a minor character.\n</p>\n<p style="text-align: justify;">\n    Two months following Temptation Box, Scandal released their eighth major single, "Scandal Nanka Buttobase", on October 6, 2010. The title track was written and composed by the husband-and-wife duo Yoko Aki and Ryudo Uzaki, who are known for creating many songs for Momoe Yamaguchi. The limited edition DVDs contains performances from the band\'s first television show, Shiteki Ongaku Jijou, which ran for 13 episodes from July to September 2010. In November, Scandal released a cover mini-album called R-Girl\'s Rock!. It features songs by female artists that they respect from the last three decades, including their cover of "Secret Base (Kimi ga Kureta Mono)" from May. Rina undertook her first lead vocal on the song "Sunny Day Sunday".\n</p>\n<p style="text-align: justify;">\n    Scandal continued into 2011 with their ninth major single on February 9. Titled "Pride", the song was used as the second ending theme for the anime Star Driver: Kagayaki no Takuto. The single also includes the tracks "Cute!", a collaboration with Sanrio\'s Cinnamoroll, and "Emotion", their first song that was written solely by a band member. Their tenth major single, "Haruka", was released on April 20. The title track was used as the theme song for the animated film Tofu Kozou, while the song "Satisfaction" was later used as the promotional song for the release of Windows 8. This was followed by their eleventh major single, "Love Survive", as well as their third studio album Baby Action. They also embarked on their first Asian Tour, performing to sell out crowds in Hong Kong, Taiwan and Singapore.\n</p>\n<p style="text-align: justify;">\n    2012 proved to be a year of firsts for Scandal. The title track of their twelfth single, "Harukaze", was used as the the opening theme for the anime Bleach. The following month, they released their first Best Of album, Scandal Show, as well as holding their first concert at the Nippon Budokan, thus becoming the fastest girl band to perform there since their debut. In July, they released their thirteenth major single, "Taiyou Scandalous". This single marked the first official release of their subunits, Dobondobondo (Mami x Tomomi) and Almond Crush (Haruna x Rina). This was followed by their fourteenth major single, "Pin Heel Surfer", and their fourth major album, Queens Are Trumps: Kirifuda wa Queen. With this release, they became the first girl band to achieve fur consecutive top 5 positions in the Oricon Weekly charts. They also held a concert in Malaysia in December, becoming the first Japanese band to hold a solo concert there.\n</p>\n<p style="text-align: justify;">\n    Scandal started 2013 by fulfilling one of their biggest dreams they had since their formation by performing in their hometown at the Osaka-jou Hall in March. Later that month they also performed to sellout crowds on their 2nd Asian Tour in Indonesia, Singapore and Thailand. During this period they also announced their fourteenth major single "Awanai Tsumori no, Genki de ne" released in May, with the title track being used as the theme song for the movie "Ore wa Mada Honki Dashitenai Dake".\n</p>', 'allow_comment' => '1', 'status' => 'published', 'visited' => '0', 'featured' => '0', 'publish_date' => NULL),
        ),
    );


    public function __construct(){
        parent::__construct();
        if(CMS_SUBSITE != '' || defined('CMS_OVERRIDDEN_SUBSITE')){
            $this->DATA['photo'] = array();
            $this->DATA['comment'] = array();
            $this->DATA['category_article'] = array();
            $this->DATA['article'] = array();
        }else{
            for($i=1; $i<9; $i++){
                $file_name = $this->duplicate_file('0'.$i.'.jpg');
            }
        }
    }

    private function duplicate_file($original_file_name){
        $image_path = FCPATH . 'modules/' . $this->cms_module_path().'/assets/uploads/';
        $file_name = (CMS_SUBSITE==''?'main_':CMS_SUBSITE) . $original_file_name;
        if(!file_exists($image_path.$file_name)){
            copy($image_path.$original_file_name, $image_path.$file_name);

            $thumbnail_name = 'thumb_'.$file_name;
            $this->cms_resize_image($image_path.$file_name, 800, 75, $image_path.$thumbnail_name);
        }

        return $file_name;
    }

    //////////////////////////////////////////////////////////////////////////////
    // ACTIVATION
    //////////////////////////////////////////////////////////////////////////////
    public function do_activate(){
        $module_path = $this->cms_module_path();
        if($module_path == 'blog'){
            $controller_path = 'blog';
        }else{
            $controller_path = $module_path.'/blog';
        }
        // Add quicklink
        $this->cms_add_quicklink($this->n('index'));
        // Add widgets
        $this->cms_add_widget($this->n('newest_article'), 'Newest Articles',
            PRIV_EVERYONE, $module_path.'/blog_widget/newest','sidebar');
        $this->cms_add_widget($this->n('popular_article'), 'Popular Articles',
            PRIV_EVERYONE, $module_path.'/blog_widget/popular','sidebar');
        $this->cms_add_widget($this->n('featured_article'), 'Featured Articles',
            PRIV_EVERYONE, $module_path.'/blog_widget/featured','sidebar');
        $this->cms_add_widget($this->n('article_category'), 'Article Categories',
            PRIV_EVERYONE, $module_path.'/blog_widget/category','sidebar');
        $this->cms_add_widget($this->n('content'), 'Blog Content',
            PRIV_EVERYONE, $module_path);
        $this->cms_add_widget($this->n('archive'), 'Archive',
            PRIV_EVERYONE, $module_path.'/blog_widget/archive', 'sidebar');
        // Add routes
        $this->cms_add_route($controller_path.'/(:any)\.html',    $controller_path.'/index/$1',
            'Route to blog\'s article');
        $this->cms_add_route($controller_path.'/category/(:any)', $controller_path.'/index//$1',
            'Route to blog\'s category');
        $this->cms_add_route($controller_path.'/archive/(:any)',  $controller_path.'/index///$1',
            'Route to blog\'s archive');
        $this->cms_add_route($controller_path.'/category',  $controller_path.'/index/',
            'Route to blog\'s category');
        $this->cms_add_route($controller_path.'/archive',  $controller_path.'/index/',
            'Route to blog\'s archive');
    }

    //////////////////////////////////////////////////////////////////////////////
    // DEACTIVATION
    //////////////////////////////////////////////////////////////////////////////
    public function do_deactivate(){
        // TODO : write your module deactivation script here
        $module_path = $this->cms_module_path();
        if($module_path == 'blog'){
            $controller_path = 'blog';
        }else{
            $controller_path = $module_path.'/blog';
        }

        $this->cms_remove_quicklink($this->n('index'));

        // remove widgets
        $this->cms_remove_widget($this->n('newest_article'));
        $this->cms_remove_widget($this->n('popular_article'));
        $this->cms_remove_widget($this->n('featured_article'));
        $this->cms_remove_widget($this->n('article_category'));
        $this->cms_remove_widget($this->n('content'));
        $this->cms_remove_widget($this->n('archive'));

        // remove route
        $this->cms_remove_route($controller_path.'/(:any)\.html');
        $this->cms_remove_route($controller_path.'/category/(:any)');
        $this->cms_remove_route($controller_path.'/archive/(:any)');
        $this->cms_remove_route($controller_path.'/category');
        $this->cms_remove_route($controller_path.'/archive');
    }

    //////////////////////////////////////////////////////////////////////////////
    // UPGRADE
    //////////////////////////////////////////////////////////////////////////////
    public function do_upgrade_to_0_1_0(){
        $article_list = $this->db->get($this->t('article'));
        foreach($article_list as $article){
            $content = $article->content;
            $this->preg_replace("/<div(\s)*style(\s)*=(\s)*\"page-break-after(\s)*:(\s)*always(;)*\"(\s)*>(\s)*<span(\s)*style(\s)*=(\s)*\"display(\s)*:(\s)*none(;)*\">(\s)*&nbsp;(\s)*<\/span>(\s)*<\/div>/i", '<!--more-->', $content);
            $this->db->update($this->t('article'),
                array('content' => $content),
                array('article_id' => $article->article_id)
            );
        }
    }

}
