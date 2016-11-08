<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Factory_reset extends CMS_Controller{

    private $input_user_name;
    private $input_password;

    public function __construct(){
        parent::__construct();
        // set input_user_name and input_password
        if(!array_key_exists('factory_user_name', $_SESSION)){
            $_SESSION['factory_user_name'] = md5(crypt('user_name', md5(rand(0,100))));
        }
        $this->input_user_name = $_SESSION['factory_user_name'];
        if(!array_key_exists('factory_password', $_SESSION)){
            $_SESSION['factory_password'] = md5(crypt('password', md5(rand(0,100))));
        }
        $this->input_password = $_SESSION['factory_password'];
    }

    public function index(){
        $this->cms_do_logout();
        // get user_name, password, and login
        $user_name = $this->input->post($this->input_user_name);
        $password = $this->input->post($this->input_password);
        $this->cms_do_login($user_name, $password);
        if($this->cms_user_is_super_admin()){
            // reset factory
            $this->_reset_widget();
            $this->_reset_privilege();
            $this->_reset_navigation();
            $this->_reset_configuration();
            $this->_reset_layout();
            if(isset($_GET['from'])){
                redirect($_GET['from']);
            }else{
                redirect('main/index');
            }
        }else{
            // show the form
            /* Note: A very crude way for translation addition, since we can't pass ->cms_lang to view 
			 * (or I don't have capability, researching it)
			 * If we change normal $this->view() to this controller, we stuck up with re-login loop, not able to see factory reset at all*/
            $data = array(
                'input_user_name' => $this->input_user_name,
                'input_password' => $this->input_password,
                'user_name' => $user_name,
                'title' => $this->cms_lang('Factory Reset'),
                'quote' => $this->cms_lang('"Even in the midst of darkness, there is a little spark of hope"'),
                'description1' => $this->cms_lang('Factory reset only reset some default layouts, widgets, navigations, privileges, and configurations.'),
                'description2' => $this->cms_lang('Factory reset will not delete your static pages, custom widgets, or blog posts.'),
                'description3' => $this->cms_lang('ou might need to re-configure some things after factory reset.'),
                'description4' => $this->cms_lang('If you are okay with this, please let us know your super-admin\'s user name and password, and continue'),
                'error1' => $this->cms_lang('Login Failed'),
                'error2' => $this->cms_lang('You must login as Super Admin'),
                'label_user' => $this->cms_lang('Super Admin\'s Username'),
                'label_password' => $this->cms_lang('Super Admin\'s Password'),
                'label_button' => $this->cms_lang('Click To Reset')		
            );
            $this->load->view('main/factory_reset_index', $data);
        }
    }

    private function _reset_widget(){
        $widget_fields = array('widget_name',
            'title', 'description', 'url', 'authorization_id', 'active', 'index',
            'is_static', 'static_content', 'slug');
        $widget_list = array(
                array('section_custom_style', '', 'Custom CSS', '',
                    1, 1, 1, 1, '',
                    NULL),
                array('section_custom_script', '', 'Custom Javascript', '',
                    1, 1, 1, 1, '',
                    NULL),
                array('section_top_fix', 'Top Fix Section', '', '',
                    1, 1, 2, 1, '{{ widget_name:top_navigation }}',
                    NULL),
                array('section_banner', 'Banner Section', '', '',
                    1, 1, 3, 1, '<div id="div-section-banner" class="jumbotron hidden-xs hidden-sm" style="margin-top:10px;">'.PHP_EOL.'  <img src ="{{ site_logo }}" style="max-width:20%; float:left; margin-right:10px; margin-bottom:10px;" />'.PHP_EOL.'  <h1>{{ site_name }}</h1>'.PHP_EOL.'  <p>{{ site_slogan }}</p>'.PHP_EOL.'  <div style="clear:both;"></div>'.PHP_EOL.'</div>'.PHP_EOL.
                    '<script type="text/javascript">'.PHP_EOL.
                    '    $(document).ready(function(){'.PHP_EOL.
                    '        $(\'#div-section-banner\').prepend($(\'.__editing_widget_section_banner\'));'.PHP_EOL.
                    '    });'.PHP_EOL.
                    '</script>',
                    NULL),
                array('section_left', 'Left Section', '', '',
                    1, 1, 4, 1, '',
                    NULL),
                array('section_right', 'Right Section', '', '',
                    1, 1, 5, 1, '{{ widget_slug:sidebar }}<hr />{{ widget_slug:advertisement }}',
                    NULL),
                array('section_bottom', 'Bottom Section', '', '',
                    1, 1, 6, 1, '<div id="div-section-bottom" class="container well">' . PHP_EOL . '    <div class="col-md-4">' . PHP_EOL .'        <h3>{{ site_name }}</h3>' . PHP_EOL .'        <p>{{ site_slogan }}</p>' . PHP_EOL .'    </div>' . PHP_EOL .'    <div class="col-md-8">' . PHP_EOL .'        <h3>About Us</h3>' . PHP_EOL .'        <p>We are {{ site_name }}</p>' . PHP_EOL .'    </div>' . PHP_EOL .'    <div class="col-md-12">{{ site_footer }}</div>' . PHP_EOL . '</div>'. PHP_EOL .
                    '<script type="text/javascript">'.PHP_EOL.
                    '    $(document).ready(function(){'.PHP_EOL.
                    '        $(\'#div-section-bottom\').prepend($(\'.__editing_widget_section_bottom\'));'.PHP_EOL.
                    '    });'.PHP_EOL.
                    '</script>',
                    NULL),
                array('left_navigation', 'Left Navigation', '', 'main/widget_left_nav',
                    1, 1, 7, 0, NULL,
                    NULL),
                array('top_navigation', 'Top Navigation', '', 'main/widget_top_nav',
                    1, 1, 8, 0, NULL,
                    NULL),
                array('quicklink', 'Quicklinks', '', 'main/widget_quicklink',
                    1, 1, 9, 0, NULL,
                    NULL),
                array('top_navigation_default', 'Top Navigation Default', '', 'main/widget_top_nav_default',
                    1, 1, 10, 0, NULL,
                    NULL),
                array('quicklink_default', 'Quicklinks Default', '', 'main/widget_quicklink_default',
                    1, 1, 11, 0, NULL,
                    NULL),
                array('top_navigation_inverse', 'Top Navigation Inverse', '', 'main/widget_top_nav_inverse',
                    1, 1, 12, 0, NULL,
                    NULL),
                array('quicklink_inverse', 'Quicklinks Inverse', '', 'main/widget_quicklink_inverse',
                    1, 1, 13, 0, NULL,
                    NULL),
                array('top_navigation_default_fixed', 'Top Navigation Default Fixed', '', 'main/widget_top_nav_default_fixed',
                    1, 1, 14, 0, NULL,
                    NULL),
                array('quicklink_default_fixed', 'Quicklinks Default Fixed', '', 'main/widget_quicklink_default_fixed',
                    1, 1, 15, 0, NULL,
                    NULL),
                array('top_navigation_inverse_fixed', 'Top Navigation Inverse Fixed', '', 'main/widget_top_nav_inverse_fixed',
                    1, 1, 16, 0, NULL,
                    NULL),
                array('quicklink_inverse_fixed', 'Quicklinks Inverse Fixed', '', 'main/widget_quicklink_inverse_fixed',
                    1, 1, 17, 0, NULL,
                    NULL),
                array('top_navigation_default_static', 'Top Navigation Default Static', '', 'main/widget_top_nav_default_static',
                    1, 1, 18, 0, NULL,
                    NULL),
                array('quicklink_default_static', 'Quicklinks Default Static', '', 'main/widget_quicklink_default_static',
                    1, 1, 19, 0, NULL,
                    NULL),
                array('top_navigation_inverse_static', 'Top Navigation Inverse Static', '', 'main/widget_top_nav_inverse_static',
                    1, 1, 20, 0, NULL,
                    NULL),
                array('quicklink_inverse_static', 'Quicklinks Inverse Static', '', 'main/widget_quicklink_inverse_static',
                    1, 1, 21, 0, NULL,
                    NULL),
                array('login', 'Login', 'Visitor need to login for authentication', 'main/widget_login',
                    2, 1, 22, 0, '<form action="{{ site_url }}main/login" method="post" accept-charset="utf-8"><label>Identity</label><br><input type="text" name="identity" value=""><br><label>Password</label><br><input type="password" name="password" value=""><br><input type="submit" name="login" value="Log In"></form>',
                    'sidebar, user_widget'),
                array('logout', 'User Info', 'Logout', 'main/widget_logout',
                    3, 1, 23, 1, '{{ user_real_name }}<br /><a href="{{ site_url }}main/logout">{{ language:Logout }}</a>',
                    'sidebar, user_widget'),
                array('social_plugin', 'Share This Page !!', 'Addthis', 'main/widget_social_plugin',
                    1, 0, 24, 1, '<div class="addthis_sharing_toolbox"></div>'.PHP_EOL.'<!-- Go to www.addthis.com/dashboard to customize your tools -->'.PHP_EOL.'<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4ee44922521f8e39"></script>',
                    'sidebar'),
                array('google_search', 'Search', 'Search from google', '',
                    1, 0, 25, 1, '<!-- Google Custom Search Element -->'.PHP_EOL.'<div id="cse" style="width: 100%;">Loading</div>'.PHP_EOL.'<script src="http://www.google.com/jsapi" type="text/javascript"></script>'.PHP_EOL.'<script type="text/javascript">// <![CDATA['.PHP_EOL.'    google.load(\'search\', \'1\'),'.PHP_EOL.'    google.setOnLoadCallback(function(){var cse = new google.search.CustomSearchControl(),cse.draw(\'cse\'),}, true),'.PHP_EOL.'// ]]></script>',
                    'sidebar'),
                array('google_translate', 'Translate !!', '<p>The famous google translate</p>', '',
                    1, 0, 26, 1, '<!-- Google Translate Element -->'.PHP_EOL.'<div id="google_translate_element" style="display:block"></div>'.PHP_EOL.'<script>'.PHP_EOL.'function googleTranslateElementInit() {'.PHP_EOL.'  new google.translate.TranslateElement({pageLanguage: "af"}, "google_translate_element"),'.PHP_EOL.'};'.PHP_EOL.'</script>'.PHP_EOL.'<script src="http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>'.PHP_EOL.'',
                    'sidebar'),
                array('calendar', 'Calendar', 'Indonesian Calendar', '',
                    1, 0, 27, 1, '<!-------Do not change below this line------->'.PHP_EOL.'<div align="center" height="200px">'.PHP_EOL.'    <iframe align="center" src="http://www.calendarlabs.com/calendars/web-content/calendar.php?cid=1001&uid=162232623&c=22&l=en&cbg=C3D9FF&cfg=000000&hfg=000000&hfg1=000000&ct=1&cb=1&cbc=2275FF&cf=verdana&cp=bottom&sw=0&hp=t&ib=0&ibc=&i=" width="170" height="155" marginwidth=0 marginheight=0 frameborder=no scrolling=no allowtransparency=\'true\'>'.PHP_EOL.'    Loading...'.PHP_EOL.'    </iframe>'.PHP_EOL.'    <div align="center" style="width:140px;font-size:10px;color:#666;">'.PHP_EOL.'        Powered by <a  href="http://www.calendarlabs.com/" target="_blank" style="font-size:10px;text-decoration:none;color:#666;">Calendar</a> Labs'.PHP_EOL.'    </div>'.PHP_EOL.'</div>'.PHP_EOL.''.PHP_EOL.'<!-------Do not change above this line------->',
                    'sidebar'),
                array('google_map', 'Map', 'google map', '',
                    1, 0, 28, 1, '<!-- Google Maps Element Code -->'.PHP_EOL.'<iframe frameborder=0 marginwidth=0 marginheight=0 border=0 style="border:0;margin:0;width:150px;height:250px;" src="http://www.google.com/uds/modules/elements/mapselement/iframe.html?maptype=roadmap&element=true" scrolling="no" allowtransparency="true"></iframe>',
                    'sidebar'),
                array('donate_nocms', 'Donate No-CMS', 'No-CMS Donation', NULL,
                    1, 1, 29, 1, '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">'.PHP_EOL.'<input type="hidden" name="cmd" value="_s-xclick">'.PHP_EOL.'<input type="hidden" name="hosted_button_id" value="YDES6RTA9QJQL">'.PHP_EOL.'<input type="image" src="{{ base_url }}assets/nocms/images/donation.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" width="165px" height="auto" style="width:165px!important;" />'.PHP_EOL.'<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">'.PHP_EOL.'</form>',
                    'advertisement'),
                array('navigation_right_partial', 'top navigation right partial', 'Right Partial of Top Navigation Bar. Use this when you want to add something like facebook login form', NULL,
                    1, 1, 30, 1, NULL,
                    NULL),
                array('online_user', 'Who\'s online', '', 'main/widget_online_user',
                    1, 1, 31, 0, NULL,
                    NULL),
                array('fb_comment', 'Facebook Comments', '', '',
                    1, 1, 32, 1, '<div id="fb-root"></div>' . PHP_EOL . '<script>(function(d, s, id) {' . PHP_EOL . '  var js, fjs = d.getElementsByTagName(s)[0];' . PHP_EOL . '  if (d.getElementById(id)) return;' . PHP_EOL . '  js = d.createElement(s), js.id = id;' . PHP_EOL . '  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=278375612355057&version=v2.0";' . PHP_EOL . '  fjs.parentNode.insertBefore(js, fjs),' . PHP_EOL . '}(document, \'script\', \'facebook-jssdk\')),</script>' . PHP_EOL . '<div class="fb-comments" data-href="{{ site_url }}" data-numposts="5" data-colorscheme="light" width="100%"></div>',
                    NULL),
            );
        $widget_data = array();
        foreach($widget_list as $widget){
            $row = array();
            for($i=0; $i<count($widget); $i++){
                $row[$widget_fields[$i]] = $widget[$i];
            }
            $widget_data[] = $row;
        }
        // reset widget
        $this->_reset_data($this->t('main_widget'), 'widget_name', $widget_data);
    }

    private function _reset_privilege(){
        $verb_list = array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export');
        $entity_list = array('config', 'group', 'language', 'layout', 'navigation', 'privilege', 'quicklink', 'route', 'user', 'widget');
        $privilege_data = array();
        foreach($verb_list as $verb){
            foreach($entity_list as $entity){
                $privilege_data[] = array(
                    'privilege_name' => $verb.'_main_'.$entity,
                    'title' => $verb.' '.$entity,
                    'authorization_id' => 4,
                );
            }
        }
        // reset privilege
        $this->_reset_data($this->t('main_privilege'), 'privilege_name', $privilege_data);
    }

    private function _reset_navigation(){
        $entity_list = array('config', 'group', 'language', 'layout', 'navigation', 'privilege', 'quicklink', 'route', 'user', 'widget');
        // modify some old navigations
        $navigation_data = array();
        foreach($entity_list as $entity){
            $navigation_data[] = array(
                'url' => 'main/manage_'.$entity,
                'navigation_name' => 'main_'.$entity.'_management',
                'authorization_id' => 4,
                'is_static' => 0,
                'active' => 1,
            );
        }
        // reset navigation
        $this->_reset_data($this->t('main_navigation'), 'navigation_name', $navigation_data);
    }

    private function _reset_configuration(){

    }

    private function _reset_layout(){
        // get default list
        $layout_list = array('default', 'default-one-column', 'default-two-column', 'default-three-column', 'slide', 'slide-one-column', 'slide-two-column', 'slide-three-column', 'minimal');
        $layout_data = array();
        foreach($layout_list as $layout){
            $layout_data[] = array(
                'layout_name' => $layout,
                'template' => file_get_contents(FCPATH.'modules/installer/layouts/'.$layout.'.html')
            );
        }
        // reset layout
        $this->_reset_data($this->t('main_layout'), 'layout_name', $layout_data);
    }

    private function _reset_data($table_name, $key, $data_list){
        foreach($data_list as $data){
            if($this->cms_record_exists($table_name, $key, $data[$key])){
                // update
                $this->db->update($table_name, $data, array($key=>$data[$key]));
            }else{
                // insert
                $this->db->insert($table_name, $data);
            }
        }
    }
}
