<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class CMS_AutoUpdate_Model extends CMS_Model
{
    // TODO: change this
    private $CURRENT_VERSION = '1.1.2';
    private static $module_updated = false;

    public function __construct()
    {
        parent::__construct();

        // core seamless update
        $this->db->trans_start();
        $this->__update();
        $this->db->trans_complete();
        // module update
        if (!self::$module_updated) {
            self::$module_updated = TRUE;
            $this->__update_module();
        }
    }

    private function __update_module()
    {
        $bypass = NULL;
        $module_list = $this->cms_get_module_list();
        foreach ($module_list as $module) {
            $module_path = $module['module_path'];
            $module_name = $module['module_name'];
            $old_version = $module['old_version'];
            $current_version = $module['current_version'];
            $active = $module['active'];
            $upgrade_link = $module['upgrade_link'];
            if ($active && $old_version != $current_version) {
                // define bypass if not exists
                if($bypass === NULL){
                    $query = $this->db->select('password')
                        ->from($this->cms_user_table_name())
                        ->where('user_id', 1)
                        ->get();
                    if ($query->num_rows() > 0) {
                        $row = $query->row();
                        $bypass = $row->password;
                    }
                }
                if($bypass === NULL){
                    // don't do anything if bypass can't be defined
                    break;
                }
                // send request
                $url = str_replace(site_url(), '', $upgrade_link);
                $url = trim($url, '/');
                $response = Modules::run($url, $bypass);
            }
        }
    }

    private function __update()
    {
        $old_version = cms_config('__cms_version');
        $current_version = $this->CURRENT_VERSION;
        // already the same version? don't do anything
        if ($old_version == $current_version) {
            return 0;
        }

        // get current version and old version components
        $current_version_component = explode('-', $current_version);
        $current_version_component = $current_version_component[0];
        $current_version_component = explode('.', $current_version_component);
        $old_version_component = explode('-', $old_version);
        $old_version_component = $old_version_component[0];
        $old_version_component = explode('.', $old_version_component);

        // the version should contains 3 parts, major, minor, and rev
        if(count($old_version_component) <3 || count($current_version_component) <3){
            return 0;
        }
        // get current's & old's major, minor and rev version
        $current_major_version = $current_version_component[0];
        $current_minor_version = $current_version_component[1];
        $current_rev_version = $current_version_component[2];
        $old_major_version = $old_version_component[0];
        $old_minor_version = $old_version_component[1];
        $old_rev_version = $old_version_component[2];

        // each part of the old and current version should be numeric
        if(!is_numeric($current_major_version) || !is_numeric($current_minor_version) || !is_numeric($current_rev_version) || !is_numeric($old_major_version) || !is_numeric($old_minor_version) || !is_numeric($old_rev_version)){
            return 0;
        }

        $this->load->dbforge();
        // assuming that maximum value of major, minor, and rev version is 100, we can make current_int & old_int
        $factor = 100;
        // if the assumption is false, than make a new factor
        while($current_major_version > $factor || $current_minor_version > $factor || $current_rev_version > $factor || $old_major_version > $factor || $old_minor_version > $factor || $old_rev_version > $factor ){
            $factor *= 10;
        }
        $major_factor = pow($factor, 2);
        $minor_factor = pow($factor, 1);
        $current_int = $current_major_version * $major_factor + $current_minor_version * $minor_factor + $current_rev_version;
        $old_int = $old_major_version * $major_factor + $old_minor_version * $minor_factor + $old_rev_version;
        for($i=$old_int+1; $i<=$current_int; $i++){
            // get back major version, minor version, and rev version
            $major_version = floor($i / $major_factor);
            $minor_version = floor(($i- $major_version*$major_factor) / $minor_factor);
            $rev_version = ($i - $major_version*$major_factor - $minor_version*$minor_factor);
            // get method name
            $method = '__update_to_'.$major_version.'_'.$minor_version.'_'.$rev_version;
            // call the method if exists
            if(method_exists($this, $method)){
                call_user_func_array(array($this, $method), array());
            }
        }

        // write new version
        if ($old_version !== null && $old_version != '' && $old_version !== $current_version) {
            cms_config('__cms_version', $current_version);
        }
    }

    private function __mutate_user_fk($table_name, $fk_name, $subsite, $module_name = null)
    {
        // GET MAIN TABLE PREFIX
        $main_config_file = APPPATH.'config/main/cms_config.php';
        if (!file_exists($main_config_file)) {
            return false;
        }
        include $main_config_file;
        $main_table_prefix = $config['__cms_table_prefix'];
        $main_table_prefix = $main_table_prefix == '' ? '' : $main_table_prefix.'_';

        // GET SUBSITE TABLE PREFIX
        $subsite_config_file = APPPATH.'config/site-'.$subsite.'/cms_config.php';
        if (!file_exists($subsite_config_file)) {
            return false;
        }
        include $subsite_config_file;
        $subsite_table_prefix = $config['__cms_table_prefix'];
        $subsite_table_prefix = $subsite_table_prefix == '' ? '' : $subsite_table_prefix.'_';

        // GET MODULE PREFIX
        $module_table_prefix = '';
        if ($module_name != null) {
            $module_path = $this->cms_module_path($module_name);
            $module_config_file = FCPATH.'modules/'.$module_path.'/config/module_config.php';
            if (!file_exists($module_config_file)) {
                return false;
            }
            // get module table prefix
            include $module_config_file;
            $module_table_prefix = $config['module_table_prefix'];
            $module_table_prefix = $module_table_prefix == '' ? '' : $module_table_prefix.'_';
        }
        $multisite_config_file = FCPATH.'modules/'.$this->cms_module_path('gofrendi.noCMS.multisite').'/config/module_config.php';
        if (!file_exists($multisite_config_file)) {
            return false;
        }
        include $multisite_config_file;
        $multisite_table_prefix = $config['module_table_prefix'];
        $multisite_table_prefix = $multisite_table_prefix == '' ? '' : $multisite_table_prefix.'_';

        // GET TABLE NAMES
        $table_name = $subsite_table_prefix.$module_table_prefix.$table_name;
        $main_user_table_name = $this->cms_user_table_name();
        $subsite_user_table_name = $subsite_table_prefix.'main_user';
        $multisite_subsite_table_name = $main_table_prefix.$multisite_table_prefix.'subsite';

        // get new admin user_id
        $new_admin_user_id = $this->db->select('user_id')
            ->from($multisite_subsite_table_name)
            ->where('name', $subsite)
            ->get()->row()->user_id;
        // update admin
        $this->db->update($table_name,
            array($fk_name => $new_admin_user_id),
            array($fk_name => 1));

        // get current existing user_name (which is not specified in current subsite)
        $existing_user_names = array();
        $forbidden_user_names = array();
        $forbidden_emails = array();
        $existing_user_query = $this->db->select('user_name, email, subsite')
            ->from($main_user_table_name)
            ->get();
        foreach ($existing_user_query->result() as $existing_user_row) {
            $existing_user_names[] = $existing_user_row->user_name;
            if ($existing_user_row->subsite != $subsite) {
                $forbidden_user_names[] = $existing_user_row->user_name;
                $forbidden_user_emails[] = $existing_user_row->email;
            }
        }

        // get all subsite user
        $query = $this->db->select('user_id, user_name, email, real_name, password, active')
            ->from($subsite_user_table_name)
            ->get();
        foreach ($query->result() as $row) {
            $user_id = $row->user_id;
            $user_name = $row->user_name;
            $real_name = $row->real_name;
            $email = $row->email;
            $password = $row->password;
            $active = $row->active;
            // set email
            if (in_array($email, $forbidden_user_emails)) {
                $email = null;
            } else {
                $forbidden_user_emails[] = $email;
            }
            // set user_name
            $new_user_name = $user_name;
            $index = 1;
            while (in_array($new_user_name, $forbidden_user_names)) {
                $new_user_name = $user_name.'_'.$index;
                ++$index;
            }
            $user_name = $new_user_name;
            $forbidden_user_names[] = $user_name;
            if (!in_array($user_name, $existing_user_names)) {
                // insert to main user table name
                $this->db->insert($main_user_table_name, array(
                        'user_name' => $user_name,
                        'real_name' => $real_name,
                        'email' => $email,
                        'password' => $password,
                        'active' => $active,
                        'subsite' => $subsite,
                    ));
                $existing_user_names[] = $user_name;
            }
            // get new user id
            $new_user_id = $this->db->select('user_id')
                ->from($main_user_table_name)
                ->where('user_name', $user_name)
                ->get()->row()->user_id;
            // update table
            $this->db->update($table_name,
                array($fk_name => $new_user_id),
                array($fk_name => $user_id));
        }
    }

    private function __update_to_0_7_6()
    {
        // new table : cms_main_route
        $t_route = cms_table_name('main_route');
        $t_navigation = cms_table_name('main_navigation');
        $this->cms_adjust_tables(array(
            $t_route => array(
                'key' => 'route_id',
                'fields' =>  array(
                        'route_id' => array('type' => 'INT', 'constraint' => 20, 'unsigned' => TRUE, 'auto_increment' => TRUE),
                        'key' => array('type' => 'TEXT'),
                        'value' => array('type' => 'TEXT'),
                        'description' => array('type' => 'TEXT'),
                )
            ),
            $t_navigation => array(
                'fields' => array('hidden' => array('type' => 'INT', 'default' => '0'))
            ),
        ));

        // modify table : cms_main_user
        if (CMS_SUBSITE == '') {
            $t_user = $this->cms_user_table_name();
            $this->cms_adjust_tables(array(
                $t_user => array(
                    'fields' => array('subsite' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE))
                )
            ));
        }

        // add navigation
        $this->cms_add_navigation('main_route_management', 'Route', 'main/route', 4, 'main_management');

        // determine config path
        $config_path = CMS_SUBSITE == '' ?
            APPPATH.'config/main/' :
            APPPATH.'config/site-'.CMS_SUBSITE.'/';
        $original_route_config = $config_path.'routes.php';
        $extended_route_config = $config_path.'extended_routes.php';
        // include extended route to default route
        file_put_contents($original_route_config,
            file_get_contents($original_route_config).PHP_EOL.
            'include(\'extended_routes.php\');'.PHP_EOL);
        // add extended routes
        file_put_contents($extended_route_config,
            '<?php if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');'.PHP_EOL.
            '$routes = array();'.PHP_EOL);

        // copy new configuration setting
        $content = file_get_contents(APPPATH.'config/first-time/third_party_config/kcfinder_config.php');
        $content = str_replace(
            array('{{ FCPATH }}', '{{ BASE_URL }}'),
            array(FCPATH, base_url()),
            $content);
        file_put_contents(FCPATH.'assets/kcfinder/config.php', $content);

        if (CMS_SUBSITE == '' && $this->cms_is_module_active('gofrendi.noCMS.multisite')) {
            $query = $this->db->select('name')
                ->from($this->cms_complete_table_name('subsite', 'gofrendi.noCMS.multisite'))
                ->get();
            foreach ($query->result() as $row) {
                $subsite = $row->name;

                if ($subsite == 'puribunda') {
                    continue;
                }

                // get module installation
                $subsite_config_file = APPPATH.'config/site-'.$subsite.'/cms_config.php';
                if (!file_exists($subsite_config_file)) {
                    return false;
                }
                include $subsite_config_file;
                $subsite_table_prefix = $config['__cms_table_prefix'];
                $subsite_table_prefix = $subsite_table_prefix == '' ? '' : $subsite_table_prefix.'_';

                // get installed module
                $query = $this->db->select('module_name')
                    ->from($subsite_table_prefix.'main_module')
                    ->get();
                $installed_module_name = array();
                foreach ($query->result() as $row) {
                    $installed_module_name[] = $row->module_name;
                }

                $this->__mutate_user_fk('main_group_user', 'user_id', $subsite);
                if (in_array('gofrendi.noCMS.blog', $installed_module_name)) {
                    $this->__mutate_user_fk('article', 'author_user_id', $subsite, 'gofrendi.noCMS.blog');
                    $this->__mutate_user_fk('comment', 'author_user_id', $subsite, 'gofrendi.noCMS.blog');
                }
                if (in_array('gofrendi.noCMS.shop', $installed_module_name)) {
                    $this->__mutate_user_fk('item', 'user_id', $subsite, 'gofrendi.noCMS.shop');
                    $this->__mutate_user_fk('order', 'user_id', $subsite, 'gofrendi.noCMS.shop');
                    $this->__mutate_user_fk('order', 'last_editor_user_id', $subsite, 'gofrendi.noCMS.shop');
                }
            }
        }
    }

    private function __update_to_0_7_7()
    {
        // make route for 404_override
        $pattern = array();
        $pattern[] = '/(\$route\[(\'|")404_override(\'|")\] *= *")(.*?)(";)/si';
        $pattern[] = '/('.'\$'."route\[('|\")404_override('|\")\] *= *')(.*?)(';)/si";
        if (CMS_SUBSITE == '') {
            $file_name = APPPATH.'config/main/routes.php';
        } else {
            $file_name = APPPATH.'config/site-'.CMS_SUBSITE.'/routes.php';
        }
        $str = file_get_contents($file_name);
        $replacement = '${1}main/not_found${5}';
        $found = false;
        foreach ($pattern as $single_pattern) {
            if (preg_match($single_pattern, $str)) {
                $found = TRUE;
                break;
            }
        }
        if (!$found) {
            $str .= PHP_EOL.'$route[\'404_override\'] = \'not_found\';';
        } else {
            $str = preg_replace($pattern, $replacement, $str);
        }
        @chmod($file_name, 0777);
        if (strpos($str, '<?php') !== false && strpos($str, '$route') !== false) {
            @file_put_contents($file_name, $str);
            @chmod($file_name, 0555);
        }

        // make register default-one-column
        $this->db->update(cms_table_name('main_navigation'),
            array('default_layout' => 'default-one-column'),
            array('navigation_name' => 'main_register'));

        // add 404 navigation
        $this->cms_add_navigation('main_404', '404 Not Found', 'not_found', 1,
                null, 9, '404 Not found page', null,
                null, 'default-one-column', null, 1,
                '<h1>404 Page not found</h1><p>Sorry, the page does not exists.<br /><a class="btn btn-primary" href="{{ site_url }}">Please go back <i class="glyphicon glyphicon-home"></i></a></p>'
            );
    }

    private function __update_to_0_7_9()
    {
        $t_route = cms_table_name('main_route');
        $this->cms_adjust_tables(array(
            $t_route => array(
                'fields' => array(
                    'description' => array('type' => 'TEXT','null' => TRUE,),
                ),
            ),
        ));
    }

    private function __update_to_0_8_0()
    {
        $t_config = cms_table_name('main_config');
        $this->cms_adjust_tables(array(
            $t_config => array(
                'fields' =>  array(
                    'config_name' => array('type' => 'varchar','constraint' => 100)
                )
            )
        ));
    }

    private function __update_to_0_8_1(){
        // make extended route inclussion absolute
        $pattern = 'include(\'extended_routes.php\');';
        if (CMS_SUBSITE == '') {
            $path = APPPATH.'config/main/routes.php';
            $replace = 'include(APPPATH.\'config/main/extended_routes.php\');';
        } else {
            $path = APPPATH.'config/site-'.CMS_SUBSITE.'/routes.php';
            $replace = 'include(APPPATH.\'config/site-'.CMS_SUBSITE.'/extended_routes.php\');';
        }
        file_put_contents($path, str_replace($pattern, $replace, file_get_contents($path)));
    }

    private function __update_to_1_0_0(){
        $this->cms_add_config('site_background_image', '', 'Background Image');
        $this->cms_add_config('site_background_color', '', 'Background Color');
        $this->cms_add_config('site_text_color', '', 'Text Color');
    }

    private function __update_to_1_0_1(){
        $this->cms_add_config('site_background_position', '', 'Background Position');
        $this->cms_add_config('site_background_size', '', 'Background Size');
        $this->cms_add_config('site_background_repeat', '', 'Background Repeat');
        $this->cms_add_config('site_background_origin', '', 'Background Origin');
        $this->cms_add_config('site_background_clip', '', 'Background Clip');
        $this->cms_add_config('site_background_attachment', '', 'Background Attachment');
        $this->cms_add_config('site_background_blur', '', 'Background Blur');
    }

    private function __update_to_1_0_2(){
        if(CMS_SUBSITE == ''){
            $t_user = $this->cms_user_table_name();
            $this->cms_adjust_tables(array(
                $t_user => array(
                    'fields' =>   array(
                        'birthdate'         => array("type"=>'date', "null"=>TRUE),
                        'sex'               => array('type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE),
                        'profile_picture'   => array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE),
                        'self_description'  => array('type' => 'TEXT', 'null' => TRUE),
                    )
                )
            ));
        }
    }

    private function __update_to_1_0_3(){
        $this->cms_add_config('site_show_benchmark', 'FALSE', 'Show Benchmark');
    }

    private function __update_to_1_0_4(){
        // Last time, I forgot to add authorization_id for all generated privileges, so here it is
        $this->db->update(cms_table_name('main_privilege'),
            array('authorization_id' => 4),
            array('privilege_id >=' => 0)
        );
    }

    private function __update_to_1_0_5(){
        // add some missing widgets
        $this->cms_add_widget('top_navigation_default', 'Top Navigation Default', 1, 'main/widget_top_nav_default');
        $this->cms_add_widget('quicklink_default', 'Quicklinks Default', 1, 'main/widget_quicklink_default');
        $this->cms_add_widget('top_navigation_inverse', 'Top Navigation Inverse', 1, 'main/widget_top_nav_inverse');
        $this->cms_add_widget('quicklink_inverse', 'Quicklinks Inverse', 1, 'main/widget_quicklink_inverse');
        $this->cms_add_widget('top_navigation_default_fixed', 'Top Navigation Default Fixed', 1, 'main/widget_top_nav_default_fixed');
        $this->cms_add_widget('quicklink_default_fixed', 'Quicklinks Default Fixed', 1, 'main/widget_quicklink_default_fixed');
        $this->cms_add_widget('top_navigation_inverse_fixed', 'Top Navigation Inverse Fixed', 1, 'main/widget_top_nav_inverse_fixed');
        $this->cms_add_widget('quicklink_inverse_fixed', 'Quicklinks Inverse Fixed', 1, 'main/widget_quicklink_inverse_fixed');
        $this->cms_add_widget('top_navigation_default_static', 'Top Navigation Default Static', 1, 'main/widget_top_nav_default_static');
        $this->cms_add_widget('quicklink_default_static', 'Quicklinks Default Static', 1, 'main/widget_quicklink_default_static');
        $this->cms_add_widget('top_navigation_inverse_static', 'Top Navigation Inverse Static', 1, 'main/widget_top_nav_inverse_static');
        $this->cms_add_widget('quicklink_inverse_static', 'Quicklinks Inverse Static', 1, 'main/widget_quicklink_inverse_static');
    }

    private function __update_to_1_0_6(){
        // clear cms_config file
        $config_file = CMS_SUBSITE == ''? APPPATH.'config/main/cms_config.php' : APPPATH.'config/site-'.CMS_SUBSITE.'/cms_config.php';
        $config = array();
        include($config_file);
        $content = '<?php  if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');';
        foreach($config as $key=>$val){
            if($key[0] == '_'){
                $content .= PHP_EOL.'$config[\''.addslashes($key).'\'] = \''.addslashes($val).'\';';
            }
        }
        if(!is_writeable($config_file)){
            @chmod($config_file,077);
        }
        if(is_writable($config_file)){
            file_put_contents($config_file, $content);
        }

        // update theme
        $this->load->helper('directory');
        $directories = directory_map(FCPATH.'themes', 1);
        $themes = array();
        foreach ($directories as $directory) {
            if(!is_dir($directory)){
                continue;
            }
            // description file
            $description_file = FCPATH.'themes/'.$directory.'/description.txt';
            $description = file_get_contents($description_file);
            $content = json_encode(array('public'=> $public, 'description' => $description));
            if(!is_writeable($description_file)){
                @chmod($config_file,077);
            }
            if(is_writable($description_file)){
                file_put_contents($description_file, $content);
            }
        }

    }

    private function __update_to_1_0_7(){
        // add new tables
        $t_main_layout = cms_table_name('main_layout');
        $this->cms_adjust_tables(array(
            $t_main_layout => array(
                'key' => 'layout_id',
                'fields' => array(
                    'layout_id'   => array('type' => 'INT', 'constraint' => 20, 'unsigned' => TRUE, 'auto_increment' => TRUE),
                    'layout_name' => array('type' => 'VARCHAR', 'constraint' => '50', 'null' => FALSE),
                    'template'    => array('type' => 'TEXT'),
                )
            ),
        ));
        // add new navigation
        $this->cms_add_navigation('main_layout_management', 'Layout Management', 'main/layout_management', 4, 'main_management', NULL, NULL, NULL,
        NULL, 'default-one-column');
        // add new privileges
        $verb_list = array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export');
        $entity_list = array('config', 'group', 'language', 'layout', 'navigation', 'privilege', 'quicklink', 'route', 'user', 'widget');
        foreach($verb_list as $verb){
            foreach($entity_list as $entity){
                $this->cms_add_privilege($verb.'_main_'.$entity, $verb.' '.$entity, 4, '');
            }
        }
        // modify some old navigations
        foreach($entity_list as $entity){
            $this->db->update(cms_table_name('main_navigation'),
                array('url' => 'main/manage_'.$entity),
                array('navigation_name' => 'main_'.$entity.'_management')
            );
        }
        // add some new fields for new standard
        if(CMS_SUBSITE == ''){
            $t_user = $this->cms_user_table_name();
            $this->cms_adjust_tables(array(
                $t_user => array(),
            ));
        }

        // add layouts
        $layout_list = array('default', 'default-one-column', 'default-two-column', 'default-three-column', 'slide', 'slide-one-column', 'slide-two-column', 'slide-three-column', 'minimal');
        $layout_data = array();
        foreach($layout_list as $layout){
            $layout_data[] = array(
                'layout_name' => $layout,
                'template' => file_get_contents(FCPATH.'modules/installer/layouts/'.$layout.'.html')
            );
        }
        $this->db->insert_batch($this->t('main_layout'), $layout_data);
        // get all theme
        if(CMS_SUBSITE == ''){
            foreach($this->cms_get_theme_list() as $theme){
                $theme = $theme['path'];
                if($theme == 'neutral'){
                    continue;
                }
                // is css.php exists? no? copy from neutral
                $css_php = FCPATH.'themes/'.$theme.'/views/css.php';
                if(!file_exists($css_php)){
                    rcopy(FCPATH.'themes/neutral/views/css.php', $css_php);
                }
                // is js.php exists? no? copy from neutral
                $js_php = FCPATH.'themes/'.$theme.'/views/js.php';
                if(!file_exists($js_php)){
                    rcopy(FCPATH.'themes/neutral/views/js.php', $js_php);
                }
                // delete views/layouts and views/partials
                if(file_exists(FCPATH.'themes/'.$theme.'/views/layouts')){
                    rrmdir(FCPATH.'themes/'.$theme.'/views/layouts');
                }
                if(file_exists(FCPATH.'themes/'.$theme.'/views/partials')){
                    rrmdir(FCPATH.'themes/'.$theme.'/views/partials');
                }
                // is assets/default exists? yes? move it
                if(file_exists(FCPATH.'themes/'.$theme.'/assets/default')){
                    // ico folder
                    if(file_exists(FCPATH.'themes/'.$theme.'/assets/default/ico')){
                        rcopy(FCPATH.'themes/'.$theme.'/assets/default/ico', FCPATH.'themes/'.$theme.'/assets/ico');
                    }
                    // images folder
                    if(file_exists(FCPATH.'themes/'.$theme.'/assets/default/images')){
                        rcopy(FCPATH.'themes/'.$theme.'/assets/default/images', FCPATH.'themes/'.$theme.'/assets/images');
                    }
                    // css and js
                    if(!file_exists(FCPATH.'themes/'.$theme.'/assets/css')){
                        mkdir(FCPATH.'themes/'.$theme.'/assets/css');
                    }
                    if(!file_exists(FCPATH.'themes/'.$theme.'/assets/js')){
                        mkdir(FCPATH.'themes/'.$theme.'/assets/js');
                    }
                    // bootstrap.min.css
                    if(file_exists(FCPATH.'themes/'.$theme.'/assets/default/bootstrap.min.css')){
                        rcopy(FCPATH.'themes/'.$theme.'/assets/default/bootstrap.min.css', FCPATH.'themes/'.$theme.'/assets/css/bootstrap.min.css');
                    }
                    // style.css
                    if(file_exists(FCPATH.'themes/'.$theme.'/assets/default/style.css')){
                        rcopy(FCPATH.'themes/'.$theme.'/assets/default/style.css', FCPATH.'themes/'.$theme.'/assets/css/style.css');
                    }
                    // style.css
                    if(file_exists(FCPATH.'themes/'.$theme.'/assets/default/script.js')){
                        rcopy(FCPATH.'themes/'.$theme.'/assets/default/script.js', FCPATH.'themes/'.$theme.'/assets/js/script.js');
                    }
                    rrmdir(FCPATH.'themes/'.$theme.'/assets/default');
                }
            }
        }
    }

    private function __update_to_1_0_8(){
        $t_main_navigation = cms_table_name('main_navigation');
        $this->cms_adjust_tables(array(
            $t_main_navigation => array(
                'fields' => array(
                    'custom_style' => array('type' => 'TEXT', 'null'=>TRUE),
                    'custom_script' =>  array('type' => 'TEXT', 'null'=>TRUE),
                )
            ),
        ));
    }

    private function __update_to_1_0_9(){
        $this->cms_add_config('site_developer_addr', '127.0.0.1', 'Developer Address');
    }

    private function __update_to_1_1_0(){
        $this->cms_add_widget('user_button', 'User Button', $this->PRIV_EVERYONE, 'main/widget_user_button');
    }

    private function __update_to_1_1_1(){
        $t_main_privilege = cms_table_name('main_privilege');
        $this->cms_adjust_tables(array(
            $t_main_privilege => array(
                'fields' => array(
                    'title' =>array('type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE)
                )
            ),
        ));
    }

    private function __update_to_1_1_2(){
        // modify navigation table
        $t_main_navigation = cms_table_name('main_navigation');
        $this->cms_adjust_tables(array(
            $t_main_navigation => array(
                'fields' => array(
                    'page_twitter_card' =>array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE),
                    'page_image' =>array('type' => 'TEXT', 'null' => TRUE),
                    'page_author' =>array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE),
                    'page_type' =>array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE),
                    'page_fb_admin' =>array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE),
                    'page_twitter_publisher_handler' =>array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE),
                    'page_twitter_author_handler' =>array('type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE),
                )
            ),
        ));

        // insert new configurations
        $this->cms_add_config('meta_keyword', '', 'keyword for SEO');
        $this->cms_add_config('meta_description', '', 'Description for SEO');
        $this->cms_add_config('meta_twitter_card', 'summary', 'Twitter Card for SEO');
        $this->cms_add_config('meta_author', '', 'Author for SEO');
        $this->cms_add_config('meta_image', '', 'Image for SEO');
        $this->cms_add_config('meta_type', 'article', 'Type for SEO');
        $this->cms_add_config('meta_fb_admin', '', 'FB Admin for SEO');
        $this->cms_add_config('meta_twitter_publisher_handler', '', 'Twitter publisher handler for SEO');
        $this->cms_add_config('meta_twitter_author_handler', '', 'Twitter author handler for SEO');
    }

    // TODO : Write your upgrade function here (__update_to_x_y_x)
}
