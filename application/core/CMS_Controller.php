<?php if (!defined('BASEPATH')) { exit('No direct script access allowed');}

/**
 * CMS_Controller class.
 *
 * @author gofrendi
 */
class CMS_Controller extends MX_Controller
{
    // going to be deprecated, there is already constant for this
    protected $PRIV_EVERYONE = PRIV_EVERYONE;
    protected $PRIV_NOT_AUTHENTICATED = PRIV_NOT_AUTHENTICATED;
    protected $PRIV_AUTHENTICATED = PRIV_AUTHENTICATED;
    protected $PRIV_AUTHORIZED = PRIV_AUTHORIZED;
    protected $PRIV_EXCLUSIVE_AUTHORIZED = PRIV_EXCLUSIVE_AUTHORIZED;

    protected $__cms_dynamic_widget = FALSE;
    private $__cms_widgets = NULL;
    private $__cms_navigations = NULL;
    private $__cms_navigation_path = NULL;
    private $__cms_navigation_name = NULL;
    private $__cms_quicklinks = NULL;

    protected $__cms_base_model_name = 'no_cms_autoupdate_model';

    protected function _guard_controller()
    {
        $module_path = $this->cms_module_path();
        // in case of module is not installed, but the naughty user add navigation manually, show module not installed message
        // however if description.txt doesn't exists, than it has nothing todo with the module
        if ($module_path != 'main' && $module_path != '' && file_exists(FCPATH.'modules/'.$module_path.'/description.txt')) {
            if ($this->cms_module_name($module_path) == '') {
                $this->show_404();
            }
        }
    }

    private function show_404(){
        $this->output->set_status_header('404');
        die($this->view('not_found_index', NULL, 'main_404', NULL, TRUE));
    }

    public function __construct()
    {
        parent::__construct();

        // for the first-time installation, it might not load main configuration even if the main configuration
        // is already exists. Thus we need to explicitly code it
        if(CMS_SUBSITE == '' && ENVIRONMENT == 'first-time' && file_exists(APPPATH.'config/main/database.php')){
            unset($db);
            include(APPPATH.'config/main/database.php');
            $this->load->database($db['default']);
        }else{
            @$this->load->database();
        }

        // load helpers and libraries
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->helper('form');
        $this->load->helper('string');
        $this->load->library('user_agent');
        $this->load->driver('session');
        $this->load->helper('cms_helper');
        $this->load->library('form_validation');
        $this->load->library('unit_test');
        $this->form_validation->CI = &$this;

        $this->JQUERY_PATH = base_url('assets/grocery_crud/js/'.JQUERY_FILE_NAME);

        $module_path = $this->cms_module_path();
        $this->load->model($this->__cms_base_model_name);
        $this->{$this->__cms_base_model_name}->__controller_module_path = $module_path;

        $this->session = $this->{$this->__cms_base_model_name}->session;

        // hook cms_construct
        $this->cms_call_hook('cms_controller_construct');

        // unpublished modules should never be accessed.
        if (CMS_SUBSITE != '' && $module_path != 'main' && $module_path != '') {
            $module_list = $this->cms_get_module_list();
            // is current module_path exists in module_list?
            $allowed = FALSE;
            foreach($module_list as $module){
                if($module['module_path'] == $module_path && $module['published']){
                    $allowed = TRUE;
                }
            }
            // if not allowed than show 404
            if (!$allowed) {
                $this->show_404();
            }
        }

        $this->_guard_controller();

        if (isset($_REQUEST['__cms_dynamic_widget'])) {
            $this->__cms_dynamic_widget = TRUE;
        }

        if (!$this->__cms_dynamic_widget) {
            // if there is old_url, then save it
            $old_url = $this->session->userdata('cms_old_url');
        }

        if (!isset($_COOKIE['__sso_login'])) {
            // just use for temporary fix
            $_COOKIE['__sso_login'] = FALSE;
        }
        if (!$this->input->is_ajax_request() && !$this->__cms_dynamic_widget && !$_COOKIE['__sso_login'] && $this->__cms_base_model_name == 'no_cms_autoupdate_model' && CMS_SUBSITE != '' && USE_SUBDOMAIN && $this->cms_user_id() <= 0) {
            setcookie('__sso_login', TRUE, time() + 600, '/');
            if ($this->input->get('__origin') == NULL || $this->input->get('__token') == NULL) {
                include BASEPATH.'../hostname.php';
                $url = current_url();
                $ssl = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? TRUE : FALSE;
                $sp = strtolower($_SERVER['SERVER_PROTOCOL']);
                $protocol = substr($sp, 0, strpos($sp, '/')).(($ssl) ? 's' : '');
                $port = $_SERVER['SERVER_PORT'];
                $port = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':'.$port;
                $host = isset($hostname) ? $hostname.$_SERVER['SCRIPT_NAME'].$port : $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].$port;
                $server_name = $_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'].$port;
                $redirection = $protocol.'://'.$host.'/main/check_login?__origin='.urlencode($url).'&__server_name='.$server_name;
                redirect($redirection);
            }
        }

    }

    public function __call($method, $args){
        if(method_exists($this->{$this->__cms_base_model_name}, $method)){
            return call_user_func_array(array($this->{$this->__cms_base_model_name}, $method), $args);
        }else{
            log_message('error', 'Method '.$method.' is not defined in '.$this->__cms_base_model_name);
            return NULL;
        }
    }

    /**
     * @author goFrendiAsgard
     *
     * @return Grocery_CRUD
     * @desc   return Grocery_CRUD
     */
    protected function new_crud()
    {
        $this->load->library('Extended_grocery_crud');
        $crud = new Extended_grocery_crud();
        $crud->set_theme('no-flexigrid');

        return $crud;
    }

    /**
     * @author  goFrendiAsgard
     *
     * @param   string navigation_name
     *
     * @return string
     * @desc    return submenu screen
     */
    protected function cms_submenu_screen($navigation_name)
    {
        $submenus = array();
        if (!isset($navigation_name)) {
            $submenus = $this->cms_navigations(NULL, 1);
        } else {
            $query = $this->db->select('navigation_id')
                ->from(cms_table_name('main_navigation'))
                ->where('navigation_name', $navigation_name)
                ->get();
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $navigation_id = $row->navigation_id;
                $submenus = $this->cms_navigations($navigation_id, 1);
            } else {
                return '';
            }
        }

        // remove hidden submenus
        $shown_submenus = array();
        foreach($submenus as $submenu){
            if(!$submenu['hidden']){
                $shown_submenus[] = $submenu;
            }
        }
        $submenus = $shown_submenus;

        // prepare html
        $html = '
        <script type="text/javascript">
            function __adjust_component(identifier){
                var max_height = 0;
                $(identifier).each(function(){
                    $(this).css("margin-bottom", 0);
                    if($(this).height()>max_height){
                        max_height = $(this).height();
                    }
                });
                $(identifier).each(function(){
                    $(this).height(max_height);
                    var margin_bottom = 0;
                    if($(this).height()<max_height){
                        margin_bottom = max_height - $(this).height();
                    }
                    margin_bottom += 10;
                    $(this).css("margin-bottom", margin_bottom);
                });
            }
            function __adjust_thumbnail_submenu(){
                __adjust_component(".thumbnail_submenu img");
                __adjust_component(".thumbnail_submenu div.caption");
                __adjust_component(".thumbnail_submenu");
            }
            $(window).on(\'load\', function(){
                __adjust_thumbnail_submenu();
                // resize
                $(window).resize(function(){
                    __adjust_thumbnail_submenu();
                });
            });
        </script>';

        $html .= '<div class="row">';

        $submenu_count = count($submenus);
        foreach ($submenus as $submenu) {
            $navigation_id = $submenu['navigation_id'];
            $navigation_name = $submenu['navigation_name'];
            $title = $submenu['title'];
            $url = $submenu['url'];
            $description = $submenu['description'];
            $allowed = $submenu['allowed'];
            $notif_url = $submenu['notif_url'];
            if (!$allowed) {
                continue;
            }

            $stripped_url = substr($url, strlen(base_url()));
            $url_part = explode('/', $stripped_url);
            $icon_found = FALSE;
            $navigation_icon = '';
            if(count($url_part)>0 && $url_part[0] != 'main'){
                $module_prefix = $url_part[0];
                $module_dir = FCPATH.'modules/'.$module_prefix.'/';
                if(file_exists($module_dir) && is_dir($module_dir)){
                    $navigation_icon_path = $module_dir.'assets/navigation_icon/';
                    $module_config = $module_dir.'config/module_config.php';
                    if(file_exists($module_config)){
                        include($module_config);
                        $module_prefix = $config['module_prefix'];
                        if(substr($navigation_name, 0, strlen($module_prefix)+1) == $module_prefix.'_'){
                            $navigation_icon = $navigation_icon_path.substr($navigation_name, strlen($module_prefix)+1).'.png';
                        }
                    }
                }
            }else{
                $navigation_icon_path = FCPATH.'modules/main/assets/navigation_icon/';
                $navigation_icon = $navigation_icon_path.$navigation_name.'.png';
            }

            // default icon
            if(!file_exists($navigation_icon)){
                $navigation_icon = FCPATH.'assets/nocms/images/icons/package.png';
            }
            $navigation_icon = substr($navigation_icon, strlen(FCPATH));

            $badge = '';
            if ($notif_url != '') {
                $badge_id = '__cms_notif_submenu_screen_'.$navigation_id;
                $badge = '&nbsp;<span id="'.$badge_id.'" class="badge"></span>';
                $badge .= '<script type="text/javascript">
                        $(window).on(\'load\', function(){
                            setInterval(function(){
                                $.ajax({
                                    dataType:"json",
                                    url: "'.addslashes($notif_url).'",
                                    success: function(response){
                                        if(response.success){
                                            $("#'.$badge_id.'").html(response.notif);
                                        }
                                        __adjust_thumbnail_submenu();
                                    }
                                });
                            }, 300000);
                        });
                    </script>
                ';
            }

            $html .= '<a href="'.$url.'" style="text-decoration:none;">';
            if ($submenu_count <= 2) {
                $html .= '<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">';
            } else if ($submenu_count % 4 == 0) {
                $html .= '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">';
            } else if ($submenu_count % 3 == 0) {
                $html .= '<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">';
            } else {
                $html .= '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">';
            }
            $html .= '<div class="thumbnail thumbnail_submenu">';

            $html .= '<img style="margin-top:10px; max-height:60px;" src="'.base_url($navigation_icon).'" />';

            $html .= '<div class="caption">';
            $html .= '<h4>'.$title.$badge.'</h4>';
            $html .= '<p>'.$description.'</p>';
            $html .= '</div>'; // end of div.caption
            $html .= '</div>'; // end of div.thumbnail
            $html .= '</div>'; // end of div.col-xs-6 col-sm-4 col-md-3
            $html .= '</a>';
        }
        $html .= '</div>';

        return $html;
    }

    /**
     * @author  goFrendiAsgard
     *
     * @param   string module_name
     *
     * @return string
     * @desc    get module_path (folder name) of specified module_name (name space)
     */
    protected function cms_module_path($module_name = NULL)
    {
        if ($module_name === NULL) {
            $module_path = '';
            $reflector = new ReflectionObject($this);
            $file_name = $reflector->getFilename();
            if (strpos($file_name, FCPATH.'modules') === 0) {
                $file_name = trim(str_replace(FCPATH.'modules', '', $file_name), DIRECTORY_SEPARATOR);
                $file_name_part = explode(DIRECTORY_SEPARATOR, $file_name);
                if (count($file_name_part) >= 2) {
                    $module_path = $file_name_part[0];
                }
            }

            return $module_path;
        } else {
            return $this->{$this->__cms_base_model_name}->cms_module_path($module_name);
        }
    }

    /**
     * @author goFrendiAsgard
     *
     * @param  string url_string
     *
     * @return bool
     * @desc   guess the navigation name of an url
     */
    protected function cms_navigation_name($url_string = NULL)
    {
        if (!isset($url_string)) {
            $url_string = $this->uri->uri_string();
        }

        if ($this->db->platform() == 'pdo' && $this->db->subdriver == 'sqlite') {
            $url_pattern = "url || '%'";
        } else {
            $url_pattern = "CONCAT(url, '%')";
        }

        // not only original url_string, but also every matched url from routes
        $url_string_list = array($url_string);
        if(CMS_SUBSITE == ''){
            include(APPPATH.'config/main/routes.php');
        }else{
            include(APPPATH.'config/site-'.CMS_SUBSITE.'/routes.php');
        }
        foreach($route as $key=>$val){
            // skip reserved routes
            if($key == 'default_controller' || $key == '404_override' || $key == 'translate_uri_dashes'){
                continue;
            }
            // translate route into regex pattern
            $key = str_replace(':any', '[^/]+', $key);
            $key = str_replace(':num', '[0-9]+', $key);
            $key = '$' . $key .'$';
            // if match, try to translate
            if(preg_match($key, $url_string)){
                $url_string_list[] = preg_replace($key, $val, $url_string);
            }
        }
        // build where syntax
        $where = array();
        foreach($url_string_list as $url_string){
            $where[] = "'".addslashes($url_string)."' LIKE ".$url_pattern."
        		OR '/".addslashes($url_string)."/' LIKE ".$url_pattern."
                OR '/".addslashes($url_string)."' LIKE ".$url_pattern."
                OR '".addslashes($url_string)."/' LIKE ".$url_pattern;
        }
        $where = implode(' OR ', $where);

        $SQL = 'SELECT navigation_name
            FROM '.cms_table_name('main_navigation')."
            WHERE ".$where.'
            ORDER BY LENGTH(url) DESC';
        $query = $this->db->query($SQL);

        $navigation_name = NULL;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $navigation_name = stripslashes($row->navigation_name);
        }

        return $navigation_name;
    }

    /**
     * @author goFrendiAsgard
     * @desc   redirect to main/login page
     */
    protected function cms_redirect()
    {
        $uriString = $this->uri->uri_string();
        $old_url = $this->session->userdata('old_url');
        if (!isset($old_url)) {
            // AJAX request should not be used for redirection
            if (!$this->input->is_ajax_request()) {
                $this->session->set_userdata('cms_old_url', $uriString);
            }
        }

        $login_url = $this->cms_navigation_url('main_login');
        if ($this->cms_allow_navigate('main_login') && ($uriString != $login_url)) {
            redirect($login_url, 'refresh');
        } else {
            $navigation_name = $this->cms_navigation_name($this->router->default_controller);
            if (!isset($navigation_name)) {
                $navigation_name = $this->cms_navigation_name($this->router->default_controller.'/index');
            }
            // redirect to default controller
            if (isset($navigation_name) && $this->cms_allow_navigate($navigation_name) &&
            ($uriString != '') && ($uriString != $this->router->default_controller) &&
            ($uriString != $this->router->default_controller.'/index')) {
                redirect('', 'refresh');
            } else {
                show_404();
            }
        }
    }

    /**
     * @author goFrendiAsgard
     *
     * @param string navigation_name
     * @param string or array privilege_required
     * @desc guard a page from unauthorized access
     */
    protected function cms_guard_page($navigation_name = NULL, $privilege_required = NULL)
    {
        $privilege_required = isset($privilege_required) ? $privilege_required : array();
        // check if allowed
        if (!isset($navigation_name) || $this->cms_allow_navigate($navigation_name)) {
            if (!isset($privilege_required)) {
                $allowed = TRUE;
            } elseif (is_array($privilege_required)) {
                // privilege_required is array
                $allowed = TRUE;
                foreach ($privilege_required as $privilege) {
                    $allowed = $allowed && $this->cms_have_privilege($privilege);
                    if (!$allowed) {
                        break;
                    }
                }
            } else { // privilege_required is string
                $allowed = $this->cms_have_privilege($privilege_required);
            }
        } else {
            $allowed = FALSE;
        }
        // if not allowed then redirect
        if (!$allowed) {
            $this->cms_redirect();
        }
    }

    private function cms_ck_adjust_script()
    {
        $base_url = base_url();
        $site_logo = $this->cms_get_config('site_logo');
        $site_favicon = $this->cms_get_config('site_favicon');
        $save_base_url = str_replace('/', '\\/', $base_url);
        $save_site_logo = str_replace('/', '\\/', $site_logo);
        $save_site_favicon = str_replace('/', '\\/', $site_favicon);
        $ck_editor_adjust_script = '
            var BOOTSTRAP_INCLUSION = "<link href=\"'.$base_url.'assets/bootstrap/css/bootstrap.min.css\" id=\"ck_adjust_style\" rel=\"stylesheet\" type=\"text/css\" />";
            BOOTSTRAP_INCLUSION += "<script src=\"'.$this->JQUERY_PATH.'\" type=\"text/javascript\"><\/script>";

            function __adjust_ck_editor(){
                for (instance in CKEDITOR.instances) {
                    /* ck_instance */
                    ck_instance = CKEDITOR.instances[instance];
                    var name = CKEDITOR.instances[instance].name;
                    var $ck_textarea = $("#cke_"+name+" textarea");
                    var $ck_iframe = $("#cke_"+name+" iframe");
                    var content = ck_instance.getData();
                    /* view-source mode */
                    if($ck_textarea.length > 0){
                        content = content.replace(
                            BOOTSTRAP_INCLUSION,
                            ""
                        );
                        /* site_favicon */
                        content = content.replace(
                            /(src=".*?)('.$save_site_favicon.')(.*?")/gi,
                            "$1{"+"{ site_favicon }}$3"
                        );
                        /* site_logo */
                        content = content.replace(
                            /(src=".*?)('.$save_site_logo.')(.*?")/gi,
                            "$1{"+"{ site_logo }}$3"
                        );
                        /* base_url */
                        content = content.replace(
                            /(src=".*?)('.$save_base_url.')(.*?")/gi,
                            "$1{"+"{ base_url }}$3"
                        );
                        ck_instance.setData(content);
                    }
                    /* ck-editor mode */
                    else if ($ck_iframe.length > 0){
                        /* translate site_favicon */
                        re = new RegExp(\'(src=".*?)({\'+\'{ site_favicon }})(.*?")\',"gi");
                        content = content.replace(
                            re,
                            "$1'.$site_favicon.'$3"
                        );
                        /* translate site_logo */
                        re = new RegExp(\'(src=".*?)({\'+\'{ site_logo }})(.*?")\',"gi");
                        content = content.replace(
                            re,
                            "$1'.$site_logo.'$3"
                        );
                        /* translate base_url */
                        re = new RegExp(\'(src=".*?)({\'+\'{ base_url }})(.*?")\',"gi");
                        content = content.replace(
                            re,
                            "$1'.$base_url.'$3"
                        );
                        if(content.search(BOOTSTRAP_INCLUSION) == -1){
                            content = BOOTSTRAP_INCLUSION + content;
                        }
                        ck_instance.setData(content);
                    }
                    ck_instance.updateElement();
                }
            }

            function __set_ck_editor_actual_value(){
                for (instance in CKEDITOR.instances) {
                    /* ck_instance */
                    ck_instance = CKEDITOR.instances[instance];
                    var name = CKEDITOR.instances[instance].name;
                    var $original_textarea = $("textarea#"+name);
                    var content = ck_instance.getData();
                    content = content.replace(
                        BOOTSTRAP_INCLUSION,
                        ""
                    );
                    content = content.replace(
                        /(src=".*?)('.$save_base_url.')(.*?")/gi,
                        "$1{"+"{ base_url }}$3"
                    );
                    ck_instance.setData(content);
                }
            }

            $(document).ready(function(){
                if ($("#crudForm").length > 0 && typeof(CKEDITOR) != "undefined"){

                    /* when instance ready or mode changed, adjust ck editor */
                    CKEDITOR.on("instanceReady", function(){
                        __adjust_ck_editor();
                        for (instance in CKEDITOR.instances) {
                            /* ck_instance */
                            ck_instance = CKEDITOR.instances[instance];
                            ck_instance.on("mode", function(){
                                __adjust_ck_editor();
                            });
                        }
                        /*hide some unused and broken plugin*/
                        $(".cke_button__pagebreak").hide();
                        $(".cke_button__widgetbootstrapalert").hide();
                    });

                    /* ensure that adjust_ck_editor executed */
                    __adjust_ck_editor();

                    $("#crudForm").submit(function(){
                        __set_ck_editor_actual_value();
                    });
                    $(document).ajaxComplete(function(event, xhr, settings){
                        if(settings.url == $("#crudForm").attr("action")){
                            __adjust_ck_editor();
                        }
                    });

                }
            });
        ';

        return $ck_editor_adjust_script;
    }

    /**
     * @author  goFrendiAsgard
     *
     * @param   string view_url
     * @param   string data
     * @param   string navigation_name
     * @param   array config
     * @param   bool return_as_string
     *
     * @return string or null
     * @desc    replace $this->load->view. This method will also load header, menu etc except there is _only_content parameter via GET or POST
     */
    protected function view($view_url, $data = NULL, $navigation_name = NULL, $config = NULL, $return_as_string = FALSE)
    {
        $result = NULL;
        $view_url = $this->cms_parse_keyword($view_url);

        /*
         * PREPARE PARAMETERS *********************************************************************************************
         */
        // get dynamic widget status
        // (this is necessary since sometime the function called directly without run the constructor, i.e: when using Modules::run)

        if (isset($_REQUEST['__cms_dynamic_widget'])) {
            $this->__cms_dynamic_widget = TRUE;
        }

        /*
         * PREPARE PARAMETERS *********************************************************************************************
         */

        // this method can be called as $this->view('view_path', $data, true);
        // or $this->view('view_path', $data, $navigation_name, true);
        if (is_bool($navigation_name) && count($config) == 0) {
            $return_as_string = $navigation_name;
            $navigation_name = NULL;
            $config = NULL;
        } else if (is_bool($config)) {
            $return_as_string = $config;
            $config = NULL;
        }

        if (!isset($return_as_string)) {
            $return_as_string = FALSE;
        }
        if (!isset($config)) {
            $config = array();
        }

        $privilege_required = isset($config['privileges']) ? $config['privileges'] : array();
        $custom_theme = isset($config['theme']) ? $config['theme'] : NULL;
        $custom_layout = isset($config['layout']) ? $config['layout'] : NULL;
        $custom_title = isset($config['title']) ? $config['title'] : NULL;
        $custom_metadata = isset($config['metadata']) ? $config['metadata'] : array();
        $custom_partial = isset($config['partials']) ? $config['partials'] : NULL;
        $custom_keyword = isset($config['keyword']) ? $config['keyword'] : NULL;
        $custom_description = isset($config['description']) ? $config['description'] : NULL;
        $custom_author = isset($config['author']) ? $config['author'] : NULL;
        $only_content = isset($config['only_content']) ? $config['only_content'] : NULL;
        $always_allow = isset($config['always_allow']) ? $config['always_allow'] : FALSE;
        $custom_css = isset($config['css']) ? $config['css'] : '';
        $custom_js = isset($config['js']) ? $config['js'] : '';

        $custom_twitter_card = isset($config['twitter_card']) ? $config['twitter_card'] : NULL;
        $custom_author = isset($config['author']) ? $config['author'] : NULL;
        $custom_image = isset($config['image']) ? $config['image'] : NULL;
        $custom_type = isset($config['type']) ? $config['type'] : NULL;
        $custom_fb_admin = isset($config['fb_admin']) ? $config['fb_admin'] : NULL;
        $custom_twitter_publisher_handler = isset($config['twitter_publisher_handler']) ? $config['twitter_publisher_handler'] : NULL;
        $custom_twitter_twitter_handler= isset($config['twitter_author_handler']) ? $config['twitter_author_handler'] : NULL;

        /*
         * GUESS $navigation_name THROUGH ITS URL  ***********************************************************************
         */
        $navigation_name_provided = TRUE;
        if (!isset($navigation_name) && !$this->__cms_dynamic_widget) {
            $navigation_name = $this->cms_navigation_name();
            if (!$navigation_name) {
                $navigation_name_provided = FALSE;
            }
        }

        /*
         * CHECK IF THE CURRENT NAVIGATION IS ACCESSIBLE  *****************************************************************
         */
        if (!$always_allow) {
            $this->cms_guard_page($navigation_name, $privilege_required);
        }
        // privilege is absolute
        $this->cms_guard_page(NULL, $privilege_required);

        /*
         * CHECK IF THE PAGE IS STATIC  **********************************************************************************
         */
        $data = (array) $data;
        $row_navigation = NULL;
        if ($navigation_name != NULL) {
            $query = $this->db->select('navigation_id, title, page_title, page_keyword, description, default_theme, default_layout, only_content, is_static, static_content, custom_style, custom_script, page_twitter_card, page_author, page_image, page_type, page_fb_admin, page_twitter_publisher_handler, page_twitter_author_handler')
                ->from(cms_table_name('main_navigation'))
                ->where(array('navigation_name' => $navigation_name))
                ->get();
            if ($query->num_rows() > 0) {
                $row_navigation = $query->row();
            }
        }
        if ($navigation_name_provided && !isset($data['_content']) && $row_navigation != NULL) {
            if ($row_navigation->is_static == 1) {
                $static_content = $row_navigation->static_content;
                // static_content should contains string
                if (!$static_content) {
                    $static_content = '';
                }
                $data['cms_content'] = $static_content;
                $view_url = 'CMS_View';
            }
        }

        /*
         * SHOW THE PAGE IF IT IS ACCESSIBLE  *****************************************************************************
         */

        // GET THE THEME, TITLE & ONLY_CONTENT FROM DATABASE
        $theme = '';
        $title = '';
        $keyword = '';
        $default_theme = NULL;
        $default_layout = NULL;
        $page_title = NULL;
        $page_keyword = NULL;
        $page_description = NULL;
        $page_author = NULL;
        $page_twitter_card = NULL;
        $page_image = NULL;
        $page_type = NULL;
        $page_fb_admin = NULL;
        $page_twitter_publisher_handler = NULL;
        $page_twitter_author_handler = NULL;
        $page_css = '';
        $page_js = '';
        if ($navigation_name_provided && $row_navigation != NULL) {
            $default_theme = $row_navigation->default_theme;
            $default_layout = $row_navigation->default_layout;
            // title
            if (isset($row_navigation->page_title) && ($row_navigation->page_title !== NULL) && $row_navigation->page_title != '') {
                $page_title = $row_navigation->page_title;
            } elseif (isset($row_navigation->title) && ($row_navigation->title !== NULL) && $row_navigation->title != '') {
                $page_title = $row_navigation->title;
            }
            $page_title = isset($page_title) && $page_title !== NULL ? $page_title : '';
            // keyword
            $page_keyword = isset($row_navigation->page_keyword) && $row_navigation->page_keyword !== NULL ? $row_navigation->page_keyword : '';
            // description 
            $page_description = isset($row_navigation->description) && $row_navigation->description !== NULL ? $row_navigation->description : '';
            $page_author = isset($row_navigation->page_author) && $row_navigation->page_author !== NULL ? $row_navigation->page_author : '';
            // meta
            $page_twitter_card = isset($row_navigation->page_twitter_card) && $row_navigation->page_twitter_card !== NULL ? $row_navigation->page_twitter_card : '';
            $page_image = isset($row_navigation->page_image) && $row_navigation->page_image !== NULL ? $row_navigation->page_image : '';
            $page_fb_admin = isset($row_navigation->page_fb_admin) && $row_navigation->page_fb_admin !== NULL ? $row_navigation->page_fb_admin : '';
            $page_twitter_publisher_handler = isset($row_navigation->page_twitter_publisher_handler) && $row_navigation->page_twitter_publisher_handler !== NULL ? $row_navigation->page_twitter_publisher_handler : '';
            $page_twitter_author_handler = isset($row_navigation->page_twitter_author_handler) && $row_navigation->page_twitter_author_handler !== NULL ? $row_navigation->page_twitter_author_handler : '';
            // only content
            if (!isset($only_content)) {
                $only_content = ($row_navigation->only_content == 1);
            }
            // page css and page js
            $page_css = $row_navigation->custom_style;
            $page_js = $row_navigation->custom_script;
        }

        // ASSIGN THEME
        if (isset($custom_theme) && $custom_theme !== NULL && $custom_theme != '') {
            $theme = $custom_theme;
        } elseif (isset($default_theme) && $default_theme != NULL && $default_theme != '') {
            $themes = $this->cms_get_theme_list();
            $theme_path = array();
            foreach ($themes as $theme) {
                $theme_path[] = $theme['path'];
            }
            if (in_array($default_theme, $theme_path)) {
                $theme = $default_theme;
            }
        } else {
            $theme = $this->cms_get_user_theme();
            if($theme == NULL || $theme == ''){
                $theme = $this->cms_get_config('site_theme');
            }
        }

        // ASSIGN TITLE
        $title = '';
        if (isset($custom_title) && $custom_title !== NULL && $custom_title != '') {
            $title = $this->cms_get_config('site_name').' - '.$custom_title;
        } elseif (isset($page_title) && $page_title !== NULL && $page_title != '') {
            $title = $this->cms_get_config('site_name').' - '.$page_title;
        } else {
            $title = $this->cms_get_config('site_name');
        }

        // ASSIGN KEYWORD
        if (isset($custom_keyword) && $custom_keyword != NULL && $custom_keyword != '') {
            $keyword = $custom_keyword;
        } elseif (isset($page_keyword) && $page_keyword !== NULL && $page_keyword != '') {
            $keyword = $page_keyword;
            if ($custom_keyword != '') {
                $keyword .= ', '.$custom_keyword;
            }
        } else {
            $keyword = $this->cms_get_config('meta_keyword');
        }

        // ASSIGN DESCRIPTION
        if (isset($custom_description) && $custom_description != NULL && $custom_description != '') {
            $description = $custom_description;
        } elseif (isset($page_description) && $page_description !== NULL && $page_description != '') {
            $description = $page_description;
            if ($custom_description != '') {
                $description .= ', '.$custom_description;
            }
        } else {
            $description = $this->cms_get_config('meta_description');
        }

        // ASSIGN AUTHOR
        if (isset($custom_author) && $custom_author != NULL && $custom_author != '') {
            $author = $custom_author;
        } elseif (isset($page_author) && $page_author !== NULL && $page_author != '') {
            $author = $page_author;
        } else {
            $author = $this->cms_get_config('meta_author');
            if($author == '' || $author == NULL){
                $super_admin = $this->{$this->__cms_base_model_name}->cms_get_super_admin();
                $author = $super_admin['real_name'];
             }
        }

        if (isset($custom_twitter_card) && $custom_twitter_card != NULL && $custom_twitter_card != '') {
            $twitter_card = $custom_twitter_card;
        } elseif (isset($page_twitter_card) && $page_twitter_card !== NULL && $page_twitter_card != '') {
            $twitter_card = $page_twitter_card;
        } else {
            $twitter_card = $this->cms_get_config('meta_twitter_card');
        }
        
        if (isset($custom_image) && $custom_image != NULL && $custom_image != '') {
            $image = $custom_image;
        } elseif (isset($page_image) && $page_image !== NULL && $page_image != '') {
            $image = '{{ base_url }}modules/main/assets/uploads/'.$page_image;
        } else {
            $image = $this->cms_get_config('meta_image');
        }

        if (isset($custom_type) && $custom_type != NULL && $custom_type != '') {
            $type = $custom_type;
        } elseif (isset($page_type) && $page_type !== NULL && $page_type != '') {
            $type = $page_type;
        } else {
            $type = $this->cms_get_config('meta_type');
        }


        if (isset($custom_fb_admin) && $custom_fb_admin != NULL && $custom_fb_admin != '') {
            $fb_admin = $custom_fb_admin;
        } elseif (isset($page_fb_admin) && $page_fb_admin !== NULL && $page_fb_admin != '') {
            $fb_admin = $page_fb_admin;
        } else {
            $fb_admin = $this->cms_get_config('meta_fb_admin');
        }

        if (isset($custom_twitter_publisher_handler) && $custom_twitter_publisher_handler != NULL && $custom_twitter_publisher_handler != '') {
            $twitter_publisher_handler = $custom_twitter_publisher_handler;
        } elseif (isset($page_twitter_publisher_handler) && $page_twitter_publisher_handler !== NULL && $page_twitter_publisher_handler != '') {
            $twitter_publisher_handler = $page_twitter_publisher_handler;
        } else {
            $twitter_publisher_handler = $this->cms_get_config('meta_twitter_publisher_handler');
        }
        
        if (isset($custom_twitter_author_handler) && $custom_twitter_author_handler != NULL && $custom_twitter_author_handler != '') {
            $twitter_author_handler = $custom_twitter_author_handler;
        } elseif (isset($page_twitter_author_handler) && $page_twitter_author_handler !== NULL && $page_twitter_author_handler != '') {
            $twitter_author_handler = $page_twitter_author_handler;
        } else {
            $twitter_author_handler = $this->cms_get_config('meta_twitter_author_handler');
        }


        // GET THE LAYOUT
        if (isset($custom_layout)) {
            $layout = $custom_layout;
        } else if (isset($default_layout) && $default_layout != '') {
            $layout = $default_layout;
        } else {
            $layout = $this->cms_get_config('site_layout');
        }

        // GET SHOW BENCHMARK
        $developer_addr = $this->cms_get_config('site_developer_addr');
        // benchmark only shown if this page is not ajax request, not a widget, configuration site_show_benchmark == TRUE, and the site is accessed from developer's machine
        $show_benchmark = !$this->input->is_ajax_request() && ! $this->__cms_dynamic_widget && strtoupper(trim($this->cms_get_config('site_show_benchmark'))) == 'TRUE' && ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1' || $_SERVER['REMOTE_ADDR'] == $developer_addr || preg_match('/'.$developer_addr.'/si', $_SERVER['REMOTE_ADDR']));

        // ADJUST THEME
        if (!file_exists(FCPATH.'themes/'.$theme) || !is_dir(FCPATH.'themes/'.$theme)) {
            $theme = 'neutral';
        }
        // ADJUST LAYOUT
        if(!$this->cms_layout_exists($layout)){
            $layout = 'default';
        }
        // save used_theme
        $this->session->set_userdata('__cms_used_theme', $theme);

        // editing_mode_content
        $editing_mode_content = '';
        if ($this->cms_editing_mode()) {
            $editing_mode_content = '<div class="row" style="padding-top:10px; padding-bottom:10px; text-align:right;">';
            if($this->cms_allow_navigate('main_layout_management') && $this->cms_have_privilege('edit_main_layout')){
                // get row layout
                $layout_id = $this->cms_get_layout_id($layout);
                if($layout_id != NULL){
                    // edit layout
                    $editing_mode_content .= '<a class="btn btn-default" href="{{ SITE_URL }}main/manage_layout/index/edit/'.$layout_id.'?from='.$this->cms_get_origin_uri_string().'">'.
                        '<i class="glyphicon glyphicon-edit"></i> Edit Current Layout'.
                    '</a>';
                }
            }
            if($row_navigation != NULL && $this->cms_allow_navigate('main_navigation_management') && $this->cms_have_privilege('edit_main_navigation')){
                // edit page
                $editing_mode_content .= '<a style="margin-left:10px;" class="btn btn-default" href="{{ SITE_URL }}main/manage_navigation/index/edit/'.$row_navigation->navigation_id.'?from='.$this->cms_get_origin_uri_string().'">'.
                    '<i class="glyphicon glyphicon-pencil"></i> Edit Current Page'.
                '</a>';
            }
            if(isset($_GET['from'])){
                // Go back
                $editing_mode_content .= '<a style="margin-left:10px;" class="btn btn-default" href="{{ SITE_URL }}'.$_GET['from'].'">'.
                    '<i class="glyphicon glyphicon-circle-arrow-left"></i> Back'.
                '</a>';
            }
            $editing_mode_content .= '</div>';
        }

        // IT'S SHOW TIME
        if ($only_content || $this->__cms_dynamic_widget || (isset($_REQUEST['_only_content'])) || $this->input->is_ajax_request()) {
            $result = $this->load->view($view_url, $data, TRUE);
            if($page_css != NULL){
                $custom_css .= '<style type="text/css">'.$page_css.'</style>';
            }
            if($page_js != NULL){
                $custom_js .= '<script type="text/javascript">'.$page_js.'</script>';
            }
            $result = $custom_css.$custom_js.$result;
        } else {
            // save navigation name
            $this->cms_ci_session('__cms_navigation_name', $navigation_name);

            // set layout variables
            $layout_title = $title;
            $layout_theme = $theme;
            $layout_metadata = '';
            $layout_js = '';
            $layout_css = '';
            $layout_body = $editing_mode_content . $this->load->view($view_url, $data, TRUE);

            // set keyword metadata
            if ($keyword != '') {
                $layout_metadata .= '<meta name="keyword" content="'.$keyword.'">';
            }
            // set description metadata
            if ($description != '') {
                $layout_metadata .= '<meta name="description" content="'.$description.'">';
                $layout_metadata .= '<meta name="twitter:description" content="'.$description.'">';
                $layout_metadata .= '<meta property="og:description" content="'.$description.'">';
            }
            // set author metadata
            if ($author != '') {
                $layout_metadata .= '<meta name="author" content="'.$author.'">';
            }
            // set image
            if ($image != '') {
                $layout_metadata .= '<meta name="twitter:image" content="'.$image.'">';
                $layout_metadata .= '<meta property="og:image" content="'.$image.'">';
            }
            // set twitter_card
            if ($twitter_card != '') {
                $layout_metadata .= '<meta name="twitter:card" content="'.$twitter_card.'">';
            }
            // set type
            if ($type != '') {
                $layout_metadata .= '<meta property="og:type" content="'.$type.'">';
            }
            // set fb_admin
            if ($fb_admin != '') {
                $layout_metadata .= '<meta property="fb:admins" content="'.$fb_admin.'">';
            }
            // set twitter publisher handler
            if ($twitter_publisher_handler != '') {
                $layout_metadata .= '<meta name="twitter:site" content="'.$twitter_publisher_handler.'">';
            }
            // set twitter author handler
            if ($twitter_author_handler != '') {
                $layout_metadata .= '<meta name="twitter:site" content="'.$twitter_author_handler.'">';
            }
            // set title
            $layout_metadata .= '<meta property="og:site_name" content="{{ site_name }}">';


            // add IE compatibility and width viewport
            $layout_metadata .= '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">';

            // add hack if exists
            if (!isset($_SESSION)) {
                session_start();
            }
            if (isset($_SESSION['__cms_flash_metadata'])) {
                $layout_metadata .= $_SESSION['__cms_flash_metadata'];
                unset($_SESSION['__cms_flash_metadata']);
            }

            // config metadata
            foreach ($custom_metadata as $metadata) {
                $layout_metadata .= $metadata;
            }

            $asset = new Cms_asset();
            $asset->add_js($this->JQUERY_PATH);
            // ckeditor adjustment thing
            $asset->add_internal_js($this->cms_ck_adjust_script());
            // add javascript base_url for ckeditor
            $asset->add_internal_js('var __cms_base_url = "'.base_url().'";');
            // add page js
            $asset->add_internal_js($page_js);

            // inject css for background
            $injected_css = 'body{';
            $css_configuration = array(
                'site_background_image' =>  'background-image',
                'site_background_color' =>  'background-color',
                'site_text_color' =>  'color',
                'site_background_position' =>  'background-position',
                'site_background_size' =>  'background-size',
                'site_background_repeat' =>  'background-repeat',
                'site_background_origin' =>  'background-origin',
                'site_background_clip' =>  'background-clip',
                'site_background_attachment' =>  'background-attachment',
            );
            foreach($css_configuration as $config => $css_key){
                if(trim($this->cms_get_config($config)) != ''){
                    // get value from config
                    $value = $this->cms_get_config($config);
                    // if key is site_background_image, add "url" part
                    if($config == 'site_background_image'){
                        $value = 'url(\''.addslashes($value).'\')';
                    }
                    $injected_css .= $css_key . ':' . $value . '!important;';
                }
            }
            $injected_css .= '}';
            $asset->add_internal_css($injected_css);
            // add page css
            $asset->add_internal_css($page_css);

            // check login status
            $login_code = '';
            if ($this->cms_user_id() > 0) {
                $login_code .= 'var __cms_is_login = true;';
            } else {
                $login_code .= 'var __cms_is_login = false;';
            }
            $login_code .= 'setInterval(function(){
                $.ajax({
                    url : "{{ site_url }}main/json_login_info",
                    dataType: "json",
                    success: function(response){
                        if(response.is_login != __cms_is_login){
                            window.location = $(location).attr("href");
                        }
                    }
                });
            },300000);';
            $asset->add_internal_js($login_code);
            // normal users should see warning when he/she attempt to access developer console
            if(!$show_benchmark){
                $console_warning = 'console.log("%cStop"+"%c\nThis is a browser feature intended for developers. If someone told you to copy-paste anything here, it might be a self-xss attempt", "color:red; font-weight:bold; font-size:200%;", "font-size:150%;font-weight:bold;");';
                $asset->add_internal_js($console_warning);
            }

            // google analytic
            $analytic_property_id = $this->cms_get_config('cms_google_analytic_property_id');
            if (trim($analytic_property_id) != '') {
                if ($this->cms_is_connect('google-analytics.com')) {
                    // create analytic code
                    $analytic_code = 'var _gaq = _gaq || [];
                        _gaq.push([\'_setAccount\', \''.$analytic_property_id.'\']);
                        _gaq.push([\'_trackPageview\']);
                        (function() {
                            var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;
                            ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';
                            var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
                        })(); ';
                    $asset->add_internal_js($analytic_code);
                }
            }

            // append custom css & js
            $layout_js .= $asset->compile_js() . $custom_js;
            $layout_css .= $asset->compile_css() . $custom_css;

            // theme js
            $theme_js_file = FCPATH.'themes/'.$layout_theme.'/views/js.php';
            if(file_exists($theme_js_file)){
                ob_start();
                include($theme_js_file);
                $layout_js .= ob_get_clean();
            }
            // theme css
            $theme_css_file = FCPATH.'themes/'.$layout_theme.'/views/css.php';
            if(file_exists($theme_css_file)){
                ob_start();
                include($theme_css_file);
                $layout_css .= ob_get_clean();
            }

            $layout_string = $this->cms_get_layout_template($layout);

            $search = array('{{ layout:title }}', '{{ layout:metadata }}', '{{ layout:js }}', '{{ layout:css }}', '{{ layout:body }}');
            $replace = array($layout_title, $layout_metadata, $layout_js, $layout_css, $layout_body);
            $result = str_ireplace($search, $replace, $layout_string);
        }

        // parse keyword
        $result = $this->cms_parse_keyword($result);

        // parse widgets used_theme & navigation_path
        $result = $this->__cms_parse_widget_theme_path($result, $theme, $layout, $navigation_name);
        $this->load->library('cms_asset');
        $asset = new Cms_asset();
        $result = $asset->minify($result, 'html');

        if ($return_as_string) {
            return $result;
        } else {
            // Profiler
            if($show_benchmark){
                $this->output->enable_cms_profiler(TRUE);
                $this->output->set_cms_data($data);
            }
            $this->cms_show_html($result);
        }
    }

    private function __cms_parse_widget_theme_path($html, $theme, $layout, $navigation_name, $recursive_level = 5)
    {
        // take a note on the current state to ensure no endless-recursion
        // i.e: when nothing changed, stop this.
        $original_html = $html;
        if (strpos($html, '{{ ') !== false) {
            $html = $this->{$this->__cms_base_model_name}->cms_escape_template($html);

            // parse widget
            if (strpos($html, '{{ ') !== false) {
                $pattern = '/\{\{ widget([a-zA-Z0-9-_]*?):(.*?) \}\}/si';
                // execute regex
                $html = preg_replace_callback($pattern, array(
                    $this,
                    '__cms_preg_replace_callback_widget',
                ), $html);
            }

            // prepare pattern and replacement for theme and path
            if (strpos($html, '{{ ') !== false) {
                $pattern = array();
                $replacement = array();

                // theme
                $pattern[] = "/\{\{ used_theme \}\}/si";
                $replacement[] = $theme;
                $nav_path = $this->__cms_build_nav_path($navigation_name);
                $pattern[] = "/\{\{ navigation_path \}\}/si";
                $replacement[] = $nav_path;

                $html = preg_replace($pattern, $replacement, $html);

                $html = $this->{$this->__cms_base_model_name}->cms_unescape_template($html);
            }

            --$recursive_level;
            // recursively search widget inside widget
            if (strpos($html, '{{ ') !== false && $recursive_level > 0 && $original_html != $html) {
                $html = $this->__cms_parse_widget_theme_path($html, $theme, $layout, $navigation_name, $recursive_level);
            }

            $html = $this->{$this->__cms_base_model_name}->cms_unescape_template($html);
        }

        return $html;
    }

    private function __cms_build_left_nav($navigations = null, $first = true)
    {
        if (!isset($navigations)) {
            if (!isset($this->__cms_navigations)) {
                $navigations = $this->cms_navigations();
                $this->__cms_navigations = $navigations;
            } else {
                $navigations = $this->__cms_navigations;
            }
        }
        if (count($navigations) == 0) {
            return '';
        }

        if ($first) {
            $style = 'display: block; position: static; border:none; margin:0px; background-color:light-gray;';
        } else {
            $style = 'background-color:light-gray;';
        }
        $result = '<ul  class="dropdown-menu nav nav-pills nav-stacked" style="'.$style.'">';
        foreach ($navigations as $navigation) {
            if (($navigation['allowed'] && $navigation['active']) || $navigation['have_allowed_children']) {
                // make text
                if ($navigation['allowed'] && $navigation['active']) {
                    $text = '<a class="dropdown-toggle" href="'.$navigation['url'].'">'.$navigation['title'].'</a>';
                } else {
                    $text = $navigation['title'];
                }

                if (count($navigation['child']) > 0 && $navigation['have_allowed_children']) {
                    $result .= '<li class="dropdown-submenu">'.$text.$this->__cms_build_left_nav($navigation['child'], false).'</li>';
                } else {
                    $result .= '<li>'.$text.'</li>';
                }
            }
        }
        $result .= '</ul>';

        return $result;
    }

    private function __cms_build_top_nav_btn($navigations = null, $caption = 'Complete Menu', $first = true)
    {
        if (!isset($navigations)) {
            if (!isset($this->__cms_navigations)) {
                $navigations = $this->cms_navigations();
                $this->__cms_navigations = $navigations;
            } else {
                $navigations = $this->__cms_navigations;
            }
        }
        if (count($navigations) == 0) {
            return '';
        }

        $result = '';
        $result .= '<ul class="dropdown-menu">';
        foreach ($navigations as $navigation) {
            if (($navigation['allowed'] && $navigation['active']) || $navigation['have_allowed_children']) {
                // make text
                if ($navigation['allowed'] && $navigation['active']) {
                    $text = '<a href="'.$navigation['url'].'">'.$navigation['title'].'</a>';
                } else {
                    $text = '<a href="#">'.$navigation['title'].'</a>';
                }

                if (count($navigation['child']) > 0 && $navigation['have_allowed_children']) {
                    $result .= '<li class="dropdown-submenu">'.$text.$this->__cms_build_top_nav_btn($navigation['child'], $caption, false).'</li>';
                } else {
                    $result .= '<li>'.$text.'</li>';
                }
            }
        }
        $result .= '</ul>';
        if ($first) {
            $result = '<ul class="nav"><li class="dropdown">'.
                '<a class="dropdown-toggle" data-toggle="dropdown" href="#">'.$caption.' <span class="caret"></span></a>'.
                $result.
                '</li></ul>';
        }

        return $result;
    }

    private function __cms_build_quicklink()
    {
        if (isset($this->__cms_quicklinks)) {
            $quicklinks = $this->__cms_quicklinks;
        } else {
            $quicklinks = $this->cms_quicklinks();
        }
        if (count($quicklinks) == 0) {
            return '';
        }
        $html = '<ul class="nav">';
        foreach ($quicklinks as $quicklink) {
            $html .= '<li>';
            $html .= anchor($quicklink['url'], $quicklink['title']);
            $html .= '</li>';
        }
        $html .= '</ul>';

        return $html;
    }

    private function __cms_build_widget($slug = null, $widget_name = null)
    {
        $widgets = $this->cms_widgets($slug, $widget_name);
        $html = '';
        if (isset($widget_name)) {
            foreach ($widgets as $slug_widgets) {
                if (count($slug_widgets) > 0) {
                    $widget = $slug_widgets[0];
                    $html = $widget['content'];
                    break;
                }
            }
        } elseif (isset($slug) && isset($widgets[$slug])) {
            $html = '<div class="cms-widget-slug-'.$slug.'">';
            foreach ($widgets[$slug] as $widget) {
                $html .= '<div class="cms-widget-container">';
                $html .= '<h3>'.$widget['title'].'</h3>';
                $html .= '<div class="cms-widget-content" style="margin-bottom:20px;">'.$widget['content'].'</div>';
                $html .= '</div>';
            }
            $html .= '</div>';
        }

        return $html;
    }

    private function __cms_build_nav_path($navigation_name)
    {
        $path = $this->cms_get_navigation_path($navigation_name);
        $html = '<ol class="breadcrumb">';
        for ($i = 0; $i < count($path); ++$i) {
            $current_path = $path[$i];
            $html .= '<li>'.anchor($current_path['url'], $current_path['title']).'</li>';
        }
        $html .= '</ol>';

        return $html;
    }

    private function __cms_preg_replace_callback_widget($arr)
    {
        $html = '';
        if (count($arr) > 2) {
            $option = $arr[1];
            $slug = NULL;
            $widget_name = NULL;
            if ($option == '' || $option == '_slug') {
                $slug = $arr[2];
            } elseif ($option == '_name' || $option == '_code') {
                $widget_name = $arr[2];
            }
            $html = $this->__cms_build_widget($slug, $widget_name);
        }

        return $html;
    }

    private function __cms_cache($time = 5)
    {
        // cache
        $this->load->driver('cache');
        $this->output->cache($time);
    }

    /**
     * @author  goFrendiAsgard
     *
     * @param   mixed variable
     * @param   int options
     * @desc    show variable in json encoded form
     */
    protected function cms_show_json($variable, $options = 0)
    {
        $result = '';
        // php 5.3.0 accepts 2 parameters, while lower version only accepts 1 parameter
        if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
            $result = json_encode($variable, $options);
        } else {
            $result = json_encode($variable);
        }
        // show the json
        $this->output->set_content_type('application/json')->set_output($result);
    }

    /**
     * @author  goFrendiAsgard
     *
     * @param   mixed variable
     * @desc    show variable for debugging purpose
     */
    protected function cms_show_variable($variable)
    {
        $data = array(
            'cms_content' => '<pre>'.print_r($variable, TRUE).'</pre>',
        );
        $this->load->view('CMS_View', $data);
    }

    /**
     * @author  goFrendiAsgard
     *
     * @param   string html
     * @desc    you are encouraged to use this instead of echo $html
     */
    protected function cms_show_html($html)
    {
        $data = array(
            'cms_content' => $html,
        );
        $this->load->view('CMS_View', $data);
    }

    protected function cms_editing_mode()
    {
        if ($this->cms_user_is_super_admin()) {
            return $this->{$this->__cms_base_model_name}->cms_editing_mode();
        } else {
            return FALSE;
        }
    }
}
