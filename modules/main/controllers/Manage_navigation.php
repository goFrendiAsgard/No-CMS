<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include(FCPATH.'modules/main/core/CMS_Predefined_Callback_CRUD_Controller.php');

/**
 * Description of Manage_navigation
 *
 * @author No-CMS Module Generator
 */
class Manage_navigation extends CMS_Predefined_Callback_CRUD_Controller {

    protected $URL_MAP = array();
    protected $TABLE_NAME = 'main_navigation';
    protected $COLUMN_NAMES = array('navigation_name', 'parent_id', 'title', 'bootstrap_glyph', 'page_title', 'page_keyword', 'description', 'url', 'authorization_id', 'active', 'index', 'is_static', 'static_content', 'custom_style', 'custom_script', 'only_content', 'default_theme', 'default_layout', 'notif_url', 'children', 'hidden', 'group_navigation', 'page_twitter_card', 'page_image', 'page_author', 'page_type', 'page_fb_admin', 'page_twitter_publisher_handler', 'page_twitter_author_handler');
    protected $PRIMARY_KEY = 'navigation_id';
    protected $UNSET_JQUERY = TRUE;
    protected $UNSET_READ = TRUE;
    protected $UNSET_ADD = FALSE;
    protected $UNSET_EDIT = FALSE;
    protected $UNSET_DELETE = FALSE;
    protected $UNSET_LIST = FALSE;
    protected $UNSET_BACK_TO_LIST = FALSE;
    protected $UNSET_PRINT = FALSE;
    protected $UNSET_EXPORT = FALSE;

    // These navigations should not be deleted
    protected $default_navigations = array(
        'main_login',
        'main_forgot',
        'main_logout',
        'main_management',
        'main_register',
        'main_change_profile',
        'main_group_management',
        'main_user_management',
        'main_navigation_management',
        'main_privilege_management',
        'main_module_management',
        'main_change_theme',
        'main_widget_management',
        'main_quicklink_management',
        'main_language_management',
        'main_config_management',
        'main_route_management',
        'main_layout_management',
        'main_setting',
        'main_index',
        'main_language',
        'main_third_party_auth',
        'main_404');

    protected $default_navigation_id_list = array();

    public function __construct(){
        parent::__construct();
        $navigation_list = $this->cms_get_record_list(cms_table_name('main_navigation'));
        foreach($navigation_list as $navigation){
            if(in_array($navigation->navigation_name, $this->default_navigations)){
                $this->default_navigation_id_list[] = $navigation->navigation_id;
                // completed, no need to seek anymore
                if(count($this->default_navigation_id_list) >= $this->default_navigations){
                    break;
                }
            }
        }
    }

    protected function make_crud($parent_id = NULL){
        $crud = parent::make_crud();
        $crud->order_by('index', 'asc');

        ////////////////////////////////////////////////////////////////////////
        // HINT: You can access this variables after calling parent's make_crud method:
        //      $this->CRUD
        //      $this->STATE
        //      $this->STATE_INFO
        //      $this->PK_VALUE
        ////////////////////////////////////////////////////////////////////////

        // set subject
        $crud->set_subject('Navigation');

        // displayed columns on list, edit, and add, uncomment to use
        $crud->columns('navigation_name');
        $crud->edit_fields('navigation_name', 'parent_id', 'bootstrap_glyph', 'title', 'page_title', 'active', 'add_to_quicklink', 'hidden', 'only_content', 'is_static', 'static_content', 'url', 'notif_url', 'authorization_id', 'group_navigation', 'custom_style', 'custom_script', 'default_theme', 'default_layout', 'page_keyword', 'description', 'page_type', 'page_author', 'page_fb_admin', 'page_twitter_card', 'page_twitter_author_handler', 'page_twitter_publisher_handler', 'page_image', 'index', '_updated_by', '_updated_at');
        $crud->add_fields('navigation_name', 'parent_id', 'bootstrap_glyph', 'title', 'page_title', 'active', 'add_to_quicklink', 'hidden', 'only_content', 'is_static', 'static_content', 'url', 'notif_url', 'authorization_id', 'group_navigation', 'custom_style', 'custom_script', 'default_theme', 'default_layout', 'page_keyword', 'description', 'page_type', 'page_author', 'page_fb_admin', 'page_twitter_card', 'page_twitter_author_handler', 'page_twitter_publisher_handler', 'page_image', 'index', '_created_by', '_created_at');
        //$crud->set_read_fields('navigation_name', 'parent_id', 'title', 'bootstrap_glyph', 'page_title', 'page_keyword', 'description', 'url', 'authorization_id', 'active', 'index', 'is_static', 'static_content', 'only_content', 'default_theme', 'default_layout', 'notif_url', 'children', 'hidden', 'quicklink', 'group_navigation');

        // caption of each columns
        $crud->display_as('navigation_name','Navigation Name');
        $crud->display_as('parent_id','Parent');
        $crud->display_as('title','Menu Title');
        $crud->display_as('bootstrap_glyph','Bootstrap Glyph');
        $crud->display_as('page_title','Page Title');
        $crud->display_as('url','Url');
        $crud->display_as('authorization_id','Authorization');
        $crud->display_as('active','Active');
        $crud->display_as('index','Index');
        $crud->display_as('is_static','Is Static');
        $crud->display_as('static_content','Static Content');
        $crud->display_as('only_content','Only Content');
        $crud->display_as('default_theme','Default Theme');
        $crud->display_as('default_layout','Default Layout');
        $crud->display_as('notif_url','Notif Url');
        $crud->display_as('children','Children');
        $crud->display_as('hidden','Hidden');
        $crud->display_as('group_navigation','Groups');
        $crud->display_as('custom_style','Custom Style (CSS)');
        $crud->display_as('custom_script','Custom Script (Javascript)');
        $crud->display_as('page_keyword', 'Meta Keyword');
        $crud->display_as('description', 'Meta Description');
        $crud->display_as('page_author', 'Meta Author');
        $crud->display_as('page_type', 'Meta Type');
        $crud->display_as('page_fb_admin', 'Meta FB Admin');
        $crud->display_as('page_twitter_card', 'Meta Twitter Card');
        $crud->display_as('page_twitter_author_handler', 'Meta Twitter Author Handler');
        $crud->display_as('page_twitter_publisher_handler', 'Meta Twitter Publisher Handler');
        $crud->display_as('page_image', 'Meta Image');

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
        $editable = !in_array($this->PK_VALUE, $this->default_navigation_id_list);
        if($editable){
            $crud->required_fields('navigation_name', 'title');
        }else{
            $crud->field_type('navigation_name', 'readonly');
            $crud->required_fields('title');
        }

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put required field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/unique_fields)
        // eg:
        //      $crud->unique_fields( $field1, $field2, $field3, ... );
        ////////////////////////////////////////////////////////////////////////
        $crud->unique_fields('navigation_name', 'title', 'url');


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
        //$crud->set_relation('parent_id', $this->t('main_navigation'), 'navigation_name');
        $crud->set_relation('authorization_id', $this->t('main_authorization'), 'authorization_name');

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put set relation_n_n (detail many to many) codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation_n_n)
        // eg:
        //      $crud->set_relation_n_n( $field_name, $relation_table, $selection_table, $primary_key_alias_to_this_table,
        //          $primary_key_alias_to_selection_table , $title_field_selection_table, $priority_field_relation );
        ////////////////////////////////////////////////////////////////////////
        $crud->set_relation_n_n('group_navigation',
            $this->t('main_group_navigation'),
            $this->t('main_group'),
            'navigation_id', 'group_id',
            'group_name', NULL);

        ////////////////////////////////////////////////////////////////////////
        // HINT: Put custom field type here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/field_type)
        // eg:
        //      $crud->field_type( $field_name , $field_type, $value  );
        ////////////////////////////////////////////////////////////////////////
        $themes = $this->cms_get_theme_list();
        $theme_path = array();
        foreach ($themes as $theme) {
            $theme_path[] = $theme['path'];
        }
        $crud->field_type('default_theme', 'enum', $theme_path);

        $crud->field_type('default_layout', 'enum', $this->cms_get_layout());

        $crud->unset_texteditor('description');
        $crud->unset_texteditor('custom_style');
        $crud->unset_texteditor('custom_script');
        $crud->set_field_upload('page_image', 'modules/main/assets/uploads');
        $crud->field_type('only_content', 'true_false');
        $crud->field_type('active', 'true_false');
        $crud->field_type('is_static', 'true_false');
        $crud->field_type('hidden', 'true_false');
        $crud->field_type('bootstrap_glyph', 'enum', array('glyphicon-adjust', 'glyphicon-align-center', 'glyphicon-align-justify', 'glyphicon-align-left', 'glyphicon-align-right', 'glyphicon-arrow-down', 'glyphicon-arrow-left', 'glyphicon-arrow-right', 'glyphicon-arrow-up', 'glyphicon-asterisk', 'glyphicon-backward', 'glyphicon-ban-circle', 'glyphicon-barcode', 'glyphicon-bell', 'glyphicon-bold', 'glyphicon-book', 'glyphicon-bookmark', 'glyphicon-briefcase', 'glyphicon-bullhorn', 'glyphicon-calendar', 'glyphicon-camera', 'glyphicon-certificate', 'glyphicon-check', 'glyphicon-chevron-down', 'glyphicon-chevron-left', 'glyphicon-chevron-right', 'glyphicon-chevron-up', 'glyphicon-circle-arrow-down', 'glyphicon-circle-arrow-left', 'glyphicon-circle-arrow-right', 'glyphicon-circle-arrow-up', 'glyphicon-cloud', 'glyphicon-cloud-download', 'glyphicon-cloud-upload', 'glyphicon-cog', 'glyphicon-collapse-down', 'glyphicon-collapse-up', 'glyphicon-comment', 'glyphicon-compressed', 'glyphicon-copyright-mark', 'glyphicon-credit-card', 'glyphicon-cutlery', 'glyphicon-dashboard', 'glyphicon-download', 'glyphicon-download-alt', 'glyphicon-earphone', 'glyphicon-edit', 'glyphicon-eject', 'glyphicon-envelope', 'glyphicon-euro', 'glyphicon-exclamation-sign', 'glyphicon-expand', 'glyphicon-export', 'glyphicon-eye-close', 'glyphicon-eye-open', 'glyphicon-facetime-video', 'glyphicon-fast-backward', 'glyphicon-fast-forward', 'glyphicon-file', 'glyphicon-film', 'glyphicon-filter', 'glyphicon-fire', 'glyphicon-flag', 'glyphicon-flash', 'glyphicon-floppy-disk', 'glyphicon-floppy-open', 'glyphicon-floppy-remove', 'glyphicon-floppy-save', 'glyphicon-floppy-saved', 'glyphicon-folder-close', 'glyphicon-folder-open', 'glyphicon-font', 'glyphicon-forward', 'glyphicon-fullscreen', 'glyphicon-gbp', 'glyphicon-gift', 'glyphicon-glass', 'glyphicon-globe', 'glyphicon-hand-down', 'glyphicon-hand-left', 'glyphicon-hand-right', 'glyphicon-hand-up', 'glyphicon-hd-video', 'glyphicon-hdd', 'glyphicon-header', 'glyphicon-headphones', 'glyphicon-heart', 'glyphicon-heart-empty', 'glyphicon-home', 'glyphicon-import', 'glyphicon-inbox', 'glyphicon-indent-left', 'glyphicon-indent-right', 'glyphicon-info-sign', 'glyphicon-italic', 'glyphicon-leaf', 'glyphicon-link', 'glyphicon-list', 'glyphicon-list-alt', 'glyphicon-lock', 'glyphicon-log-in', 'glyphicon-log-out', 'glyphicon-magnet', 'glyphicon-map-marker', 'glyphicon-minus', 'glyphicon-minus-sign', 'glyphicon-move', 'glyphicon-music', 'glyphicon-new-window', 'glyphicon-off', 'glyphicon-ok', 'glyphicon-ok-circle', 'glyphicon-ok-sign', 'glyphicon-open', 'glyphicon-paperclip', 'glyphicon-pause', 'glyphicon-pencil', 'glyphicon-phone', 'glyphicon-phone-alt', 'glyphicon-picture', 'glyphicon-plane', 'glyphicon-play', 'glyphicon-play-circle', 'glyphicon-plus', 'glyphicon-plus-sign', 'glyphicon-print', 'glyphicon-pushpin', 'glyphicon-qrcode', 'glyphicon-question-sign', 'glyphicon-random', 'glyphicon-record', 'glyphicon-refresh', 'glyphicon-registration-mark', 'glyphicon-remove', 'glyphicon-remove-circle', 'glyphicon-remove-sign', 'glyphicon-repeat', 'glyphicon-resize-full', 'glyphicon-resize-horizontal', 'glyphicon-resize-small', 'glyphicon-resize-vertical', 'glyphicon-retweet', 'glyphicon-road', 'glyphicon-save', 'glyphicon-saved', 'glyphicon-screenshot', 'glyphicon-sd-video', 'glyphicon-search', 'glyphicon-send', 'glyphicon-share', 'glyphicon-share-alt', 'glyphicon-shopping-cart', 'glyphicon-signal', 'glyphicon-sort', 'glyphicon-sort-by-alphabet', 'glyphicon-sort-by-alphabet-alt', 'glyphicon-sort-by-attributes', 'glyphicon-sort-by-attributes-alt', 'glyphicon-sort-by-order', 'glyphicon-sort-by-order-alt', 'glyphicon-sound-5-1', 'glyphicon-sound-6-1', 'glyphicon-sound-7-1', 'glyphicon-sound-dolby', 'glyphicon-sound-stereo', 'glyphicon-star', 'glyphicon-star-empty', 'glyphicon-stats', 'glyphicon-step-backward', 'glyphicon-step-forward', 'glyphicon-stop', 'glyphicon-subtitles', 'glyphicon-tag', 'glyphicon-tags', 'glyphicon-tasks', 'glyphicon-text-height', 'glyphicon-text-width', 'glyphicon-th', 'glyphicon-th-large', 'glyphicon-th-list', 'glyphicon-thumbs-down', 'glyphicon-thumbs-up', 'glyphicon-time', 'glyphicon-tint', 'glyphicon-tower', 'glyphicon-transfer', 'glyphicon-trash', 'glyphicon-tree-conifer', 'glyphicon-tree-deciduous', 'glyphicon-unchecked', 'glyphicon-upload', 'glyphicon-usd', 'glyphicon-user', 'glyphicon-volume-down', 'glyphicon-volume-off', 'glyphicon-volume-up', 'glyphicon-warning-sign', 'glyphicon-wrench', 'glyphicon-zoom-in', 'glyphicon-zoom-out'));
        $crud->field_type('index', 'hidden');

        $crud->set_field_one_third_width(array('active', 'add_to_quicklink', 'hidden', 'only_content', 'is_static'));
        $crud->set_field_half_width(array('title', 'bootstrap_glyph', 'authorization_id', 'group_navigation', 'default_theme', 'default_layout', 'page_author', 'page_fb_admin', 'page_twitter_publisher_handler', 'page_twitter_author_handler'));


        if (!array_key_exists('search_text', $this->input->post()) || $this->input->post('search_text') == '') {
            if (isset($parent_id) && intval($parent_id) > 0) {
                $crud->where(cms_table_name('main_navigation').'.parent_id', $parent_id);
                $state = $crud->getState();
                if ($state == 'add') {
                    $crud->field_type('parent_id', 'hidden', $parent_id);
                } elseif ($state == 'edit') {
                    $crud->set_relation('parent_id', cms_table_name('main_navigation'), 'navigation_name');
                }
            } else {
                $crud->where(array(cms_table_name('main_navigation').'.parent_id' => null));
                $crud->set_relation('parent_id', cms_table_name('main_navigation'), 'navigation_name');
            }
        }


        ////////////////////////////////////////////////////////////////////////
        // HINT: Put Tabs (if needed)
        // usage:
        //     $crud->set_outside_tab($how_many_field_outside_tab);
        //     $crud->set_tabs(array(
        //        'First Tab Caption'  => $how_many_field_on_first_tab,
        //        'Second Tab Caption' => $how_many_field_on_second_tab,
        //     ));
        ////////////////////////////////////////////////////////////////////////
        $crud->set_outside_tab(10);
        $crud->set_tabs(array(
            'General' => 5,
            'Custom' => 4,
            'SEO' => 8,
        ));

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

    public function index($parent_id = NULL){
        // create crud
        $crud = $this->make_crud($parent_id);

        // render
        $render = $this->render_crud($crud);
        $output = $render['output'];
        $config = $render['config'];

        $navigation_path = array();
        if (isset($parent_id) && intval($parent_id) > 0) {
            $this->db->select('navigation_name')
                ->from(cms_table_name('main_navigation'))
                ->where('navigation_id', $parent_id);
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $navigation_name = $row->navigation_name;
                $navigation_path = $this->cms_get_navigation_path($navigation_name);
            }
        }
        $output->navigation_path = $navigation_path;
        $output->is_insert = $crud->getState() == 'add';

        // show the view
        $this->view($this->cms_module_path().'/Manage_navigation_view', $output,
            $this->n('main_navigation_management'), $config);
    }

    public function navigation_mark_move($navigation_id)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['__mark_move_navigation_id'] = $navigation_id;
        redirect($this->cms_module_path().'/manage_navigation/index#'.$navigation_id, 'refresh');
    }

    public function navigation_move_cancel()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $navigation_id = $_SESSION['__mark_move_navigation_id'];
        unset($_SESSION['__mark_move_navigation_id']);
        redirect($this->cms_module_path().'/manage_navigation/index#'.$navigation_id, 'refresh');
    }

    public function navigation_move_before($dst_navigation_id)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $src_navigation_id = $_SESSION['__mark_move_navigation_id'];
        $this->cms_do_move_navigation_before($src_navigation_id, $dst_navigation_id);
        unset($_SESSION['__mark_move_navigation_id']);
        redirect($this->cms_module_path().'/manage_navigation/index#'.$src_navigation_id, 'refresh');
    }
    public function navigation_move_after($dst_navigation_id)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $src_navigation_id = $_SESSION['__mark_move_navigation_id'];
        $this->cms_do_move_navigation_after($src_navigation_id, $dst_navigation_id);
        unset($_SESSION['__mark_move_navigation_id']);
        redirect($this->cms_module_path().'/manage_navigation/index#'.$src_navigation_id, 'refresh');
    }
    public function navigation_move_into($dst_navigation_id)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $src_navigation_id = $_SESSION['__mark_move_navigation_id'];
        $this->cms_do_move_navigation_into($src_navigation_id, $dst_navigation_id);
        unset($_SESSION['__mark_move_navigation_id']);
        redirect($this->cms_module_path().'/manage_navigation/index#'.$src_navigation_id, 'refresh');
    }

    public function toggle_navigation_active($navigation_id)
    {
        if ($this->input->is_ajax_request()) {
            $this->db->select('active')->from(cms_table_name('main_navigation'))->where('navigation_id', $navigation_id);
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $new_value = ($row->active == 0) ? 1 : 0;
                $this->db->update(cms_table_name('main_navigation'), array(
                    'active' => $new_value,
                ), array(
                    'navigation_id' => $navigation_id,
                ));
                $this->cms_show_json(array(
                    'success' => true,
                ));
            } else {
                $this->cms_show_json(array(
                    'success' => false,
                ));
            }
        }
    }

    public function _callback_column_navigation_name($value, $row)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $this->db->select('navigation_id')
            ->from(cms_table_name('main_navigation'))
            ->where('parent_id', $row->navigation_id);
        $query = $this->db->get();
        $child_count = $query->num_rows();
        // determine need_child class
        if ($child_count > 0) {
            $can_be_expanded = true;
            $need_child = ' need-child';
        } else {
            $can_be_expanded = false;
            $need_child = '';
        }

        $html = '<a name="'.$row->navigation_id.'"></a>';
        $html .= '<span>'.$value.'<br />('.$row->title.')</span>';
        $html .= '<input type="hidden" class="navigation_id'.$need_child.'" value="'.$row->navigation_id.'" /><br />';
        // active or not
        $target = site_url($this->cms_module_path().'/manage_navigation/toggle_navigation_active/'.$row->navigation_id);
        if ($row->active == 0) {
            $html .= '<a href="#" target="'.$target.'" class="navigation_active"><i class="glyphicon glyphicon-eye-open"></i> <span>Inactive</span></a>';
        } else {
            $html .= '<a href="#" target="'.$target.'" class="navigation_active"><i class="glyphicon glyphicon-eye-open"></i> <span>Active</span></a>';
        }
        // expand
        if ($can_be_expanded) {
            $html .= ' | <a href="#" class="expand-collapse-children" target="'.$row->navigation_id.'"><i class="glyphicon glyphicon-chevron-up"></i> Collapse</a>';
        }
        $from = '';
        if(isset($_GET['from'])){
            $from .= '?from='.$_GET['from'];
        }
        // add children
        $html .= ' | <a href="'.site_url($this->cms_module_path().'/manage_navigation/index/'.$row->navigation_id).'/add'.$from.'">'.
            '<i class="glyphicon glyphicon-plus"></i> '.$this->cms_lang('Add Child')
            .'</a>';

        if (isset($_SESSION['__mark_move_navigation_id'])) {
            $mark_move_navigation_id = $_SESSION['__mark_move_navigation_id'];
            if ($row->navigation_id == $mark_move_navigation_id) {
                // cancel link
                $html .= ' | <a href="'.site_url($this->cms_module_path().'/manage_navigation/navigation_move_cancel').'"><i class="glyphicon glyphicon-repeat"></i> Undo</a>';
            } else {
                // paste before, paste after, paste inside
                $html .= ' | <a href="'.site_url($this->cms_module_path().'/manage_navigation/navigation_move_before/'.$row->navigation_id).'"><i class="glyphicon glyphicon-open"></i> Put Before</a>';
                $html .= ' | <a href="'.site_url($this->cms_module_path().'/manage_navigation/navigation_move_after/'.$row->navigation_id).'"><i class="glyphicon glyphicon-save"></i> Put After</a>';
                $html .= ' | <a href="'.site_url($this->cms_module_path().'/manage_navigation/navigation_move_into/'.$row->navigation_id).'"><i class="glyphicon glyphicon-import"></i> Put Into</a>';
            }
        } else {
            $html .= ' | <a href="'.site_url($this->cms_module_path().'/manage_navigation/navigation_mark_move/'.$row->navigation_id).'"><i class="glyphicon glyphicon-share-alt"></i> Move</a>';
        }

        return $html;
    }

    public function _callback_field_add_to_quicklink($value, $primary_key){
        $quicklink_exists = $this->cms_record_exists(cms_table_name('main_quicklink'), 'navigation_id', $primary_key);
        $active_checked = '';
        $inactive_checked = '';
        $span_active_checked = '';
        $span_inactive_checked = '';
        if($quicklink_exists){
            $active_checked = 'checked="checked"';
            $span_active_checked = 'checked';
        }else{
            $inactive_checked = 'checked="checked"';
            $span_inactive_checked = 'checked';
        }
        $html = '<div class="pretty-radio-buttons">
            <label>
                <input id="field-add_to_quicklink-true" class="radio-uniform" type="radio" name="quicklink" value="1" '.$active_checked.'>
                active
            </label>
            <label>
                <input id="field-add_to_quicklink-false" class="radio-uniform" type="radio" name="quicklink" value="0" '.$inactive_checked.'>
                inactive
            </label>
        </div>';
        return $html;
    }

    public function _callback_field_group_navigation($value, $primary_key){
        if($value === NULL){
            $value = array();
        }
        $query = $this->db->select('group_id, group_name')
            ->from(cms_table_name('main_group'))
            ->limit(20)
            ->get();
        $html = '<select id="field-group_navigation" name="group_navigation[]" multiple="multiple" size="8" class="form-control" data-placeholder="Select navigations">';
        // add old values
        foreach($value as $key=>$val){
            $html .= '<option selected value = "'.$key.'" >'.$val.'</option>';
        }
        // add other values
        foreach($query->result() as $row){
            if(!array_key_exists($row->group_id, $value)){
                $html .= '<option value = "'.$row->group_id.'" >'.$row->group_name.'</option>';
            }
        }
        $html .= '</select>';
        $html .= '<script>';
        $html .= '$("#field-group_navigation").chosen({allow_single_deselect:true, width:"100%", search_contains: true});';
        $html .= 'chosen_ajaxify("field-group_navigation", "{{ SITE_URL }}main/ajax/groups/");';
        $html .= '</script>';
        return $html;
    }

    public function _after_insert_or_update($post_array, $primary_key){
        // add or remove quicklink
        $add_to_quicklink = $this->input->post('quicklink');
        $quicklink_exists = $this->cms_record_exists(cms_table_name('main_quicklink'), 'navigation_id', $primary_key);
        if($add_to_quicklink == 0 && $quicklink_exists){
            $this->cms_remove_quicklink($post_array['navigation_name']);
        }else if($add_to_quicklink == 1 && !$quicklink_exists){
            $this->cms_add_quicklink($post_array['navigation_name']);
        }
        return TRUE;
    }

    public function _before_insert_or_update($post_array, $primary_key=NULL){
        return $post_array;
    }

    public function _show_edit($primary_key){
        return TRUE;
    }

    public function _show_delete($primary_key){
        // default navigation cannot be deleted
        return !in_array($primary_key, $this->default_navigation_id_list);
    }

    public function _allow_edit($primary_key){
        return TRUE;
    }

    public function _allow_delete($primary_key){
        return $this->_show_delete($primary_key);
    }

    public function _before_insert($post_array){
        //get parent's navigation_id
        $query = $this->db->select('navigation_id')
            ->from(cms_table_name('main_navigation'))
            ->where('navigation_id', is_int($post_array['parent_id']) ? $post_array['parent_id'] : null)
            ->get();
        $row = $query->row();

        $parent_id = isset($row->navigation_id) ? $row->navigation_id : null;

        //index = max index+1
        $query = $this->db->select_max('index')
            ->from(cms_table_name('main_navigation'))
            ->where('parent_id', $parent_id)
            ->get();
        $row = $query->row();
        $index = $row->index;
        if (!isset($index)) {
            $index = 1;
        } else {
            $index = $index + 1;
        }

        $post_array['index'] = $index;

        if (!isset($post_array['authorization_id']) || $post_array['authorization_id'] == '') {
            $post_array['authorization_id'] = 1;
        }
        return $post_array;
    }

    public function _after_insert($post_array, $primary_key){
        return TRUE;
    }

    public function _before_update($post_array, $primary_key){
        if (array_key_exists('parent_id', $post_array)) {
            if ($post_array['parent_id'] == $primary_key) {
                $post_array['parent_id'] = null;
            } else {
                $query = $this->db->select('navigation_name')
                    ->from(cms_table_name('main_navigation'))
                    ->where('navigation_id', $primary_key)
                    ->get();
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $navigation_path = $this->cms_get_navigation_path($row->navigation_name);
                    foreach ($navigation_path as $navigation) {
                        if ($navigation['navigation_id'] == $post_array['parent_id']) {
                            $post_array['parent_id'] = null;
                            break;
                        }
                    }
                }
            }
        }
        return $post_array;
    }

    public function _after_update($post_array, $primary_key){
        return TRUE;
    }

    public function _before_delete($primary_key){
        $query = $this->db->select('navigation_id')
            ->from(cms_table_name('main_navigation'))
            ->where('navigation_id', $primary_key)
            ->like('navigation_name', 'main_', 'after')
            ->like('url', 'main/', 'after')
            ->get();
        if ($query->num_rows() == 0) {
            return true;
        } else {
            return false;
        }
        return TRUE;
    }

    public function _after_delete($primary_key){
        $this->db->delete(cms_table_name('main_quicklink'), array('navigation_id' => $primary_key));
        $this->db->delete(cms_table_name('main_group_navigation'), array('navigation_id' => $primary_key));
        return TRUE;
    }

}
