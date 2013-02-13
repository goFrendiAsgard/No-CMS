<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of cms_controller
 *
 * @author gofrendi
 */

class CMS_Controller extends MX_Controller {
	public static $PRIV_EVERYONE = 1;
	public static $PRIV_NOT_AUTHENTICATED = 2;
	public static $PRIV_AUTHENTICATED = 3;
	public static $PRIV_AUTHORIZED = 4;

    public function __construct() {
        parent::__construct();
		
        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        // if there is old_url, then save it
        $this->load->library('session');
        $old_url = $this->session->flashdata('cms_old_url');
        if (!is_bool($old_url)) {
        	$this->session->keep_flashdata('cms_old_url');
        }
        /* ------------------ */

        $this->load->library('grocery_CRUD');
        $this->load->library('template');

        $this->load->model('CMS_Model');
    }

    /**
     * @author goFrendiAsgard
     * @param  string $key
     * @param  mixed $value
     * @return mixed
     * @desc   if value specified, this will set CI_Session["key"], else it will return CI_session["key"] 
     */
    private final function cms_ci_session($key, $value = NULL) {
        return $this->CMS_Model->cms_ci_session($key, $value);
    }

    /**
     * @author goFrendiAsgard
     * @param  string $key   
     * @desc   unset CI_session["key"] 
     */
    private final function cms_unset_ci_session($key) {
        return $this->CMS_Model->cms_unset_ci_session($key);
    }

    /**
     * @author goFrendiAsgard
     * @param  string $user_name  
     * @return mixed
     * @desc   set or get CI_Session["cms_user_name"]  
     */
    protected final function cms_user_name($user_name = NULL) {
        return $this->CMS_Model->cms_user_name($user_name);
    }
	
	/**
     * @author goFrendiAsgard
     * @param  string $real_name  
     * @return mixed
     * @desc   set or get CI_Session["cms_user_real_name"]  
     */
    protected final function cms_user_real_name($real_name = NULL) {
        return $this->CMS_Model->cms_user_real_name($real_name);
    }
	
	 /**
     * @author goFrendiAsgard
     * @param  string $email 
     * @return mixed
     * @desc   set or get CI_Session["cms_user_email"]  
     */
    protected final function cms_user_email($email = NULL) {
        return $this->CMS_Model->cms_user_email($email);
    }

    /**
     * @author goFrendiAsgard
     * @param  int $user_id
     * @desc   set or get CI_Session["cms_user_id"]
     */
    protected final function cms_user_id($user_id = NULL) {
        return $this->CMS_Model->cms_user_id($user_id);
    }

    /**
     * @author  goFrendiAsgard
     * @param   int parent_id
     * @param   int max_menu_depth
     * @desc    return navigation child if parent_id specified, else it will return root navigation
     *           the max depth of menu is depended on max_menud_depth
     */
    private final function cms_navigations($parent_id = NULL, $max_menu_depth = NULL) {
        return $this->CMS_Model->cms_navigations($parent_id, $max_menu_depth);
    }

    /**
     * @author goFrendiAsgard
     * @return mixed
     * @desc   return quick links
     */
    private final function cms_quicklinks() {
        return $this->CMS_Model->cms_quicklinks();
    }

    /**
     * @author  goFrendiAsgard
     * @return  mixed
     * @desc    return widgets
     */
    private final function cms_widgets() {
        return $this->CMS_Model->cms_widgets();
    }
    
    /**
     * @author  goFrendiAsgard
     * @return  string
     * @desc    return submenu screen
     */
    public final function cms_submenu_screen($navigation_name){
    	return $this->CMS_Model->cms_submenu_screen($navigation_name);
	}

    /**
     * @author  goFrendiAsgard
     * @param   string navigation_name
     * @return  mixed
     * @desc    return navigation path, used for layout
     */
    private final function cms_get_navigation_path($navigation_name = NULL) {
        return $this->CMS_Model->cms_get_navigation_path($navigation_name);
    }

    /**
     * @author  goFrendiAsgard
     * @return  mixed
     * @desc    return privileges of current user
     */
    private final function cms_privileges() {
        return $this->CMS_Model->cms_privileges();
    }

    /**
     * @author  goFrendiAsgard
     * @param   string navigation_name
     * @return  bool
     * @desc    check if user authorized to navigate into a page specified in parameter
     */
    protected final function cms_allow_navigate($navigation_name) {
        return $this->CMS_Model->cms_allow_navigate($navigation_name);
    }

    /**
     * @author  goFrendiAsgard
     * @param   string privilege_name
     * @return  bool
     * @desc    check if user have privilege specified in parameter
     */
    protected final function cms_have_privilege($privilege_name) {
        return $this->CMS_Model->cms_have_privilege($privilege_name);
    }

    /**
     * @author  goFrendiAsgard
     * @param   string identity
     * @param   string password
     * @return  bool
     * @desc    login with identity and password. Identity can be user_name or e-mail
     */
    protected final function cms_do_login($identity, $password) {
        return $this->CMS_Model->cms_do_login($identity, $password);
    }

    /**
     * @author  goFrendiAsgard
     * @desc    logout
     */
    protected final function cms_do_logout() {
        $this->CMS_Model->cms_do_logout();
    }

    /**
     * @author  goFrendiAsgard
     * @param   string user_name
     * @param   string email
     * @param   string real_name
     * @param   string password
     * @desc    register new user
     */
    protected final function cms_do_register($user_name, $email, $real_name, $password) {
        return $this->CMS_Model->cms_do_register($user_name, $email, $real_name, $password);
    }

    /**
     * @author  goFrendiAsgard
     * @param   string user_name
     * @param   string email
     * @param   string real_name
     * @param   string password
     * @desc    change current profile (user_name, email, real_name and password)
     */
    protected final function cms_do_change_profile($user_name, $email, $real_name, $password=NULL) {
        return $this->CMS_Model->cms_do_change_profile($user_name, $email, $real_name, $password);
    } 

    /**
     * @author  goFrendiAsgard
     * @param   string module_name
     * @return  bool
     * @desc    checked if module installed
     */
    protected final function cms_is_module_installed($module_name) {
    	return $this->CMS_Model->cms_is_module_installed($module_name);
    }
    
    /**
     * @author  goFrendiAsgard
	 * @return  mixed
     * @desc    get module list
     */
    public final function cms_get_module_list() {
    	return $this->CMS_Model->cms_get_module_list();
    }
    
    /**
     * @author  goFrendiAsgard
     * @param   string module_name
     * @return  string
     * @desc    get module_path (folder name) of specified module_name (name space)
     */
    public final function cms_module_path($name=NULL){
    	return $this->CMS_Model->cms_module_path($name);
    }
    
    /**
     * @author  goFrendiAsgard
     * @param   string module_path
     * @return  string
     * @desc    get module_name (name space) of specified module_path (folder name)
     */
    public final function cms_module_name($path){
    	return $this->CMS_Model->cms_module_name($path);
    }
    
    /**
     * @author  goFrendiAsgard
     * @return  mixed  
     * @desc    get layout list
     */
    protected final function cms_get_layout_list() {
    	return $this->CMS_Model->cms_get_layout_list();
    }
    
    /**
     * @author  goFrendiAsgard
     * @param   string identity 
	 * @param	bool send_mail
	 * @param   string reason (FORGOT, SIGNUP)
     * @return  bool
     * @desc    generate activation code, and send email to applicant 
     */
    protected final function cms_generate_activation_code($identity, $send_mail = FALSE, $reason='FORGOT') {
    	return $this->CMS_Model->cms_generate_activation_code($identity, $send_mail, $reason);
    }
    
    /**
     * @author  goFrendiAsgard
     * @param   string activation_code
     * @param   string new_password
	 * @return  bool success
     * @desc    activate user
     */
    protected final function cms_activate_account($activation_code, $new_password=NULL) {
    	return $this->CMS_Model->cms_activate_account($activation_code, $new_password);
    }
    
    /**
     * @author  goFrendiAsgard
     * @param   string from_address
     * @param   string from_name
     * @param   string to_address
     * @param   string subject
     * @param   string message
     * @desc    send email 
     */
    protected final function cms_send_email($from_address, $from_name, $to_address, $subject, $message) {
    	return $this->CMS_Model->cms_send_email($from_address, $from_name, $to_address, $subject, $message);
    }
    
    /**
     * @author  goFrendiAsgard
     * @param   string activation_code
     * @return  bool 
     * @desc    validate activation_code
     */
    protected final function cms_valid_activation_code($activation_code) {
    	return $this->CMS_Model->cms_valid_activation_code($activation_code);
    }
    
    /**
     * @author  goFrendiAsgard
     * @param   string name
     * @param   string value
     * @param   string description
     * @desc    set config variable
     */
    protected final function cms_set_config($name, $value, $description = NULL) {
    	return $this->CMS_Model->cms_set_config($name, $value, $description);
    }
    
    /**
     * @author  goFrendiAsgard
     * @param   string name
     * @desc    unset configuration variable
     */
    protected final function cms_unset_config($name) {
    	return $this->CMS_Model->cms_unset_config($name);
    }
    
    /**
     * @author  goFrendiAsgard
     * @param   string name, bool raw
     * @return  string
     * @desc    get configuration variable
     */
    public final function cms_get_config($name, $raw=False) {
    	return $this->CMS_Model->cms_get_config($name, $raw);
    }
	
	/**
	 * @author	goFrendiAsgard
	 * @param	string language
	 * @return	string language
	 * @desc	set language for this session only 
	 */
	protected final function cms_language($language=NULL){
		return $this->CMS_Model->cms_language($language);
	}
	
	/**
	 * @author	goFrendiAsgard
	 * @return	array list of available languages
	 * @desc	get available languages 
	 */
	public final function cms_language_list(){
        return $this->CMS_Model->cms_language_list();
    }
    
    /**
     * @author  goFrendiAsgard
     * @param   string key
     * @return  string
     * @desc    get translation of key in site_language
     */
    protected final function cms_lang($key, $module =NULL) {
    	return $this->CMS_Model->cms_lang($key, $module);
    }
    
    /**
     * @author goFrendiAsgard
     * @param  string value
     * @return string
     * @desc   parse keyword like @site_url and @base_url 
     */
    public final function cms_parse_keyword($value) {
    	return $this->CMS_Model->cms_parse_keyword($value);
    }
    
    /**
     * @author goFrendiAsgard
     * @param  string user_name
     * @return bool
     * @desc   check if user already exists
     */
    public final function cms_is_user_exists($username){
    	return $this->CMS_Model->cms_is_user_exists($username);
    }
	
	/**
     * @author goFrendiAsgard
     * @param  string url_string
     * @return bool
     * @desc   guess the navigation name of an url
     */
	private final function cms_navigation_name($url_string=NULL){
		if(!isset($url_string)){
			$uriString = $this->uri->uri_string();
		}		
        $SQL = "SELECT navigation_name FROM cms_navigation WHERE url = '" . addslashes($uriString) . "'";
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
	 * @param string navigation_name
	 * @param string or array privilege_required
	 * @desc guard a page from unauthorized access
	 */
	public final function cms_guard_page($navigation_name = NULL, $privilege_required = NULL){
		$privilege_required = isset($privilege_required) ? $privilege_required : array();
		
		// check if allowed
		if (!isset($navigation_name) || $this->cms_allow_navigate($navigation_name)) {
            if (!isset($privilege_required)) {
                $allowed = true;
            } else if (is_array($privilege_required)) {
                // privilege_required is array
                $allowed = true;
                foreach ($privilege_required as $privilege) {
                    $allowed = $allowed && $this->cms_have_privilege($privilege);
                    if (!$allowed)
                        break;
                }
            }else {// privilege_required is string
                $allowed = $this->cms_have_privilege($privilege_required);
            }
        } else {
            $allowed = false;
        }
		
		// if not allowed then redirect
		if(!$allowed){
			$uriString = $this->uri->uri_string();                        
            $old_url = $this->session->flashdata('old_url');            
            if (is_bool($old_url)) {                
                $this->session->set_flashdata('cms_old_url', $uriString);                
            }
            
            if($this->cms_allow_navigate('main_login')){            	
				redirect('main/login');
            }else{
            	$navigation_name = $this->cms_navigation_name($this->router->routes['default_controller']);
				if(!isset($navigation_name)){
					$navigation_name = $this->cms_navigation_name($this->router->routes['default_controller'].'/index');
				}
				// redirect to default controller
				if(isset($navigation_name) && $this->cms_allow_navigate($navigation_name)){
					redirect('');
				}else{
					show_404();
				}                
            }
		}
	}    

    /**
     * @author  goFrendiAsgard
     * @param   string view_url
     * @param   string data
     * @param   string navigation_name
     * @param   array config 
     * @param   bool return_as_string
     * @return  string or null
     * @desc    replace $this->load->view. This method will also load header, menu etc except there is _only_content parameter via GET or POST
     */
    protected final function view($view_url, $data = NULL, $navigation_name = NULL, $config = NULL, $return_as_string = FALSE) {
    	    	
    	$result = NULL;
        $this->load->helper('url');
		
		/**
		 * PREPARE PARAMETERS *********************************************************************************************
		 */

        // this method can be called as $this->view('view_path', $data, true);
        // or $this->view('view_path', $data, $navigation_name, true);
        if (is_bool($navigation_name) && count($config)==0) {
            $return_as_string = $navigation_name;
            $navigation_name = NULL;
			$config = NULL;
        } else if (is_bool($config)) {
            $return_as_string = $config;
			$config = NULL;
        }
		
		if(!isset($return_as_string)) $return_as_string = FALSE;
		if(!isset($config)) $config = array();
		
		$privilege_required = isset($config['privileges']) ? $config['privileges'] : array();
		$custom_theme = isset($config['theme']) ? $config['theme'] : NULL;
		$custom_layout = isset($config['layout']) ? $config['layout'] : NULL;
		$custom_title = isset($config['title']) ? $config['title'] : NULL;
		$custom_metadata = isset($config['metadata']) ? $config['metadata'] : array();
		$custom_partial = isset($config['partials']) ? $config['partials'] : NULL;
		$custom_keyword = isset($config['keyword']) ? $config['keyword'] : NULL;
		$only_content = isset($config['only_content']) ? $config['only_content'] : NULL;
		
		/** 
		 * CHECK IF IT IS WIDGET
		 */ 
		$dynamic_widget = $this->cms_ci_session('cms_dynamic_widget');
		$this->cms_unset_ci_session('cms_dynamic_widget');

        /**
		 * GUESS $navigation_name THROUGH ITS URL  ***********************************************************************
		 */
        if (!$dynamic_widget && !isset($navigation_name)) {
            $navigation_name = $this->cms_navigation_name();
        }
		
		/**
		 * CHECK IF THE CURRENT NAVIGATION IS ACCESSIBLE  *****************************************************************
		 */
        $this->cms_guard_page($navigation_name, $privilege_required);
        
        /**
		 * CHECK IF THE PAGE IS STATIC  **********************************************************************************
		 */        
        $data = (array) $data;
        if(isset($navigation_name) && !isset($data['_content'])){
            $SQL = "SELECT static_content FROM cms_navigation WHERE is_static=1 AND navigation_name='$navigation_name'";
            $query = $this->db->query($SQL);
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $static_content = $row->static_content;
                // static_content should contains string
                if(!$static_content){
                	$static_content = '';
                }
				$static_content = $this->cms_parse_keyword($static_content);
				$data['cms_content'] = $static_content;
				$view_url = 'CMS_View';
				 
            }            
        }
        

        /**
		 * SHOW THE PAGE IF IT IS ACCESSIBLE  *****************************************************************************
		 */    	
    	
		
		// GET THE THEME, TITLE & ONLY_CONTENT FROM DATABASE
		$theme = '';
		$title = '';
		$keyword = '';			
		$default_theme = NULL;
		$page_title = NULL;
		$page_keyword = NULL;
		if (isset($navigation_name)) {
			$SQL = "SELECT title, page_title, page_keyword, default_theme, only_content FROM cms_navigation WHERE navigation_name = '".addslashes($navigation_name)."'";
			$query = $this->db->query($SQL);
			// get default_theme, and default_title of this page				
			if ($query->num_rows() > 0) {
				$row = $query->row();
				$default_theme = $row->default_theme;
				if(isset($row->page_title) && $row->page_title != ''){
					$page_title = $row->page_title;
				}else if(isset($row->title) && $row->title != ''){
					$page_title = $row->title;
				}
				$page_keyword = isset($row->page_keyword) ? $row->page_keyword : '';
				if(!isset($only_content)){
					$only_content = ($row->only_content == 1);
				}										
			}				
		}
		if(!isset($only_content)){
			$only_content = TRUE;
		}
		
		// ASSIGN THEME
		if (isset($custom_theme)) {
    		$theme = $custom_theme;
    	} else if (isset($default_theme) && $default_theme != ''){
			$themes = $this->cms_get_layout_list();
			$theme_path = array();
			foreach($themes as $theme){
				$theme_path[] = $theme['path'];
			}
			if(in_array($default_theme, $theme_path)){
				$theme = $default_theme;
			}
		} else {
    		$theme = $this->cms_get_config('site_theme');
    	}
		
		// ASSIGN TITLE
		$title = '';
		if(isset($custom_title)){
			$title = $custom_title;
		} else if (isset($page_title) && $page_title != '') {
			$title = $page_title;
		} else {
			$title = $this->cms_get_config('site_name');
		}
		
		// ASSIGN KEYWORD
		if(isset($page_keyword) && $page_keyword != ''){
			$keyword = $page_keyword;
			if($custom_keyword != ''){
				$keyword .= ', '.$custom_keyword;
			}
		}else{
			$keyword = $custom_keyword;
		}
		
    	
    	// GET THE LAYOUT
    	if (isset($custom_layout)) {
    		$layout = $custom_layout;
    	} else { 
    		$this->load->library('user_agent');
    		$layout = $this->agent->is_mobile() ? 'mobile' : 'default';
    	}  	
    	
    	
    	// ADJUST THEME AND LAYOUT
    	if (!$this->cms_layout_exists($theme, $layout)) {
    		if ($layout == 'mobile' && $this->cms_layout_exists($theme, 'default')) {
    			$layout = 'default';
    		} else {
    			$theme = 'neutral';
    		}
    	}
    	
    	// BACKEND LAYOUT (in case of user has logged in)
    	$cms_user_id = $this->cms_user_id();
    	if (isset($cms_user_id) && $cms_user_id) {
    		if ($this->cms_layout_exists($theme, $layout . '_backend')) {
    			$layout = $layout . '_backend';
    		}
    	}
		
		// PREPARE SETTINGS
    	$cms['site_name'] = $this->cms_get_config('site_name');
    	$cms['site_slogan'] = $this->cms_get_config('site_slogan');
    	$cms['site_footer'] = $this->cms_get_config('site_footer');
    	$cms['site_theme'] = $theme;
    	$cms['site_logo'] = $this->cms_get_config('site_logo');
    	$cms['site_favicon'] = $this->cms_get_config('site_favicon'); 
    	$cms['user_id'] = $this->cms_user_id();
    	$cms['user_name'] = $this->cms_user_name();
    	$cms['quicklinks'] = $this->cms_quicklinks();
    	$cms['module_path'] = $this->cms_module_path();
    	$cms['module_name'] = $this->cms_module_name($cms['module_path']);
		
		// GET WIDGET AND NAVIGATION ONLY IF NEEDED.
		// THE ONLY_CONTENT PAGE, DYNAMIC WIDGET, AND AJAX REQUESTED PAGE DOESN'T NEED THOSE
		if ($only_content || $dynamic_widget || (isset($_REQUEST['_only_content'])) || $this->input->is_ajax_request()) {
			$cms['widget'] = array();
			$cms['navigations'] = array();
			$cms['navigation_path'] = array();
		}else{
			// GET WIDGET
			$widget = $this->cms_widgets();       	
        	$cms['widget'] = $widget;				
			
			// GET NAVIGATIONS
        	$navigations = $this->cms_navigations();
        	$navigation_path = $this->cms_get_navigation_path($navigation_name);
        	$cms['navigations'] = $navigations;
        	$cms['navigation_path'] = $navigation_path;
		}
		
		// DEFINE $data			
		$data['cms'] = $cms;
		
					
    	// IT'S SHOW TIME
        if ($only_content || $dynamic_widget || (isset($_REQUEST['_only_content'])) || $this->input->is_ajax_request()) {            	
            $result = $this->load->view($view_url, $data, TRUE);
			$result = $this->cms_parse_keyword($result);
			if($return_as_string){
				return $result;
			}else{
				$this->cms_show_html($result);
			}
        } else {    
			
            // set theme, layout and title
            $this->template->title($title);               
            $this->template->set_theme($theme);
            $this->template->set_layout($layout);
			
			// set keyword metadata
			if($keyword != ''){
				$keyword_metadata = '<meta name="keyword" content="'.$keyword.'">';
				$this->template->append_metadata($keyword_metadata);
			}			
			
			// include jquery
			$jquery_cdn_path = 'http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js';
			$jquery_local_path = base_url('assets/nocms/js/jquery.tools.min.js');
			if(!$this->session->userdata('cms_jquery_source')){
				$headers = @get_headers($jquery_cdn_path);
				if(strpos($headers[0],'200') === FALSE){					
					$this->session->set_userdata('cms_jquery_source', 'Local');
				}else{
					$this->session->set_userdata('cms_jquery_source', 'CDN');
				}				
			}
			if($this->session->userdata('cms_jquery_source') == 'CDN'){
				$jquery_path = $jquery_cdn_path;
			}else if($this->session->userdata('cms_jquery_source') == 'Local'){
				$jquery_path = $jquery_local_path;
			}
			
			$this->template->append_metadata('<script type="text/javascript" src="'.$jquery_path.'"></script>');
			
			// google analytic
			$analytic_property_id = $this->cms_get_config('cms_google_analytic_property_id');
			if(trim($analytic_property_id) != ''){
				// create analytic code
				$analytic_code = '<script type="text/javascript">
				  var _gaq = _gaq || [];
				  _gaq.push([\'_setAccount\', \''.$analytic_property_id.'\']);
				  _gaq.push([\'_trackPageview\']);
				  (function() {
				    var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;
				    ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';
				    var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
				  })();
				</script>';
				// add to the template
				$this->template->append_metadata($analytic_code);	
			}
			
			
			// config metadata
			foreach($custom_metadata as $metadata){
				$this->template->append_metadata($metadata);
			}
			
            
            $this->load->helper('directory');
            $partial_path = BASEPATH.'../themes/' . $theme . '/views/partials/' . $layout.'/';
            if(is_dir($partial_path)){
                $partials = directory_map($partial_path, 1);
                foreach($partials as $partial){
                	// if is directory or is not php, then ignore it
                	if (is_dir($partial))
                		continue;
                	$partial_extension = pathinfo($partial_path.$partial, PATHINFO_EXTENSION);
                	if(strtoupper($partial_extension) != 'PHP')
                		continue;
                	
                	// add partial to template
                	$partial_name = pathinfo($partial_path.$partial, PATHINFO_FILENAME);
					if(isset($custom_partial[$partial_name])){
						$this->template->inject_partial($partial_name, $custom_partial[$partial_name]);
					}else{
						$this->template->set_partial($partial_name, 'partials/' . $layout . '/'.$partial, $data);
					}	                	                	                	
                }
            }
			
            $result = $this->template->build($view_url, $data, TRUE);
			$result = $this->cms_parse_keyword($result);
			
			if($return_as_string){
				return $result;
			}else{
				$this->cms_show_html($result);
			}
        }
    }

    private final function cms_layout_exists($theme, $layout) {
        return is_file('themes/' . $theme . '/views/layouts/' . $layout . '.php');
    }
    
    private final function cms_cache($time = 5){
        // cache
    	$this->load->driver('cache');
    	$this->output->cache($time);
    }

    
    
    /**
     * @author  goFrendiAsgard
     * @param   mixed variable
     * @param   int options
     * @desc    show variable in json encoded form
     */
    protected final function cms_show_json($variable, $options = 0){
    	$result = '';
    	// php 5.3.0 accepts 2 parameters, while lower version only accepts 1 parameter
    	if(version_compare(PHP_VERSION, '5.3.0') >= 0){
    		$result = json_encode($variable, $options);
    	}else{
    		$result = json_encode($variable);
    	}
    	// show the json
    	$this->output
	    	->set_content_type('application/json')
	    	->set_output($result);
    }
    
    /**
     * @author  goFrendiAsgard
     * @param   mixed variable
     * @desc    show variable for debugging purpose
     */
    protected final function cms_show_variable($variable){
		$data = array('cms_content' => '<pre>'.print_r($variable,TRUE).'</pre>');
		$this->load->view('CMS_View',$data);
    }
    
    /**
     * @author  goFrendiAsgard
     * @param   string html
     * @desc    you are encouraged to use this instead of echo $html
     */
    protected final function cms_show_html($html){
		$data = array('cms_content' => $html);
		$this->load->view('CMS_View',$data);
    }
	
	/**
	 * @author goFrendiAsgard
	 * @return array providers
	 */
	public function cms_third_party_providers(){
		return $this->CMS_Model->cms_third_party_providers();
	}
	
	/**
	 * @author goFrendiAsgard
	 * @return array status
	 * @desc return all status from third-party provider
	 */
	public function cms_third_party_status(){
		return $this->CMS_Model->cms_third_party_status();
	}
	
	/**
	 * @author goFrendiAsgard
	 * @return boolean success
	 * @desc login/register by using third-party provider
	 */
	public function cms_third_party_login($provider){
		return $this->CMS_Model->cms_third_party_login($provider);
	}

}