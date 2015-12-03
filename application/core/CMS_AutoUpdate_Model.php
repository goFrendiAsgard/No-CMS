<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class CMS_AutoUpdate_Model extends CMS_Model
{
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
            self::$module_updated = true;
            $this->__update_module();
        }
    }

    private function __update_module()
    {
        $bypass = '';
        $query = $this->db->select('password')
            ->from($this->cms_user_table_name())
            ->where('user_id', 1)
            ->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $bypass = $row->password;
        }
        if ($bypass != '') {
            $module_list = $this->cms_get_module_list();
            foreach ($module_list as $module) {
                $module_path = $module['module_path'];
                $module_name = $module['module_name'];
                $old_version = $module['old_version'];
                $current_version = $module['current_version'];
                $active = $module['active'];
                $upgrade_link = $module['upgrade_link'];
                if ($active && $old_version != $current_version) {
                    $url = str_replace(site_url(), '', $upgrade_link);
                    $url = trim($url, '/');
                    $response = Modules::run($url, $bypass);
                }
            }
        }
    }

    private function __update()
    {
        $old_version = cms_config('__cms_version');
        // TODO: change this
        $current_version = '1.0.1';

        if ($old_version == $current_version) {
            return 0;
        }
        // get current major, minor and rev version
        $current_version_component = explode('-', $current_version);
        $current_version_component = $current_version_component[0];
        $current_version_component = explode('.', $current_version_component);
        $current_major_version = $current_version_component[0];
        $current_minor_version = $current_version_component[1];
        $current_rev_version = $current_version_component[2];

        // get old major, minor and rev version
        $old_version_component = explode('-', $old_version);
        $old_version_component = $old_version_component[0];
        $old_version_component = explode('.', $old_version_component);
        $old_major_version = $old_version_component[0];
        $old_minor_version = $old_version_component[1];
        $old_rev_version = $old_version_component[2];

        $this->load->dbforge();

        if($old_major_version <= $current_major_version){
            for($i = $old_major_version; $i <= $current_major_version; $i++){
                // determine max minor version
                $max_minor_version = 9;
                $min_minor_version = 0;
                if($i == $current_major_version){
                    $max_minor_version = $current_minor_version;
                }
                if($i == $old_major_version){
                    $min_minor_version = $old_minor_version;
                }
                // do until max minor version
                for($j=$min_minor_version; $j <= $max_minor_version; $j++){
                    // determine max minor version
                    $max_rev_version = 9;
                    $min_rev_version = 0;
                    if($i == $current_major_version && $j == $max_minor_version){
                        $max_rev_version = $current_rev_version;
                    }
                    if($i == $old_major_version && $j == $old_minor_version){
                        $min_rev_version = $old_rev_version + 1;
                    }
                    if($min_rev_version > 9){
                        break;
                    }
                    // do until max rev version
                    for($k=$min_rev_version; $k<=$max_rev_version; $k++){
                        $method = '__update_to_'.$i.'_'.$j.'_'.$k;
                        if(method_exists($this, $method)){
                            call_user_func_array(array($this, $method), array());
                        }
                    }
                }
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
        $fields = array(
                'route_id' => array('type' => 'INT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true),
                'key' => array('type' => 'TEXT'),
                'value' => array('type' => 'TEXT'),
                'description' => array('type' => 'TEXT'),
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('route_id', true);
        $this->dbforge->create_table(cms_table_name('main_route'));

        // modify table : cms_main_navigation
        $fields = array('hidden' => array('type' => 'INT', 'default' => '0'));
        $this->dbforge->add_column(cms_table_name('main_navigation'), $fields);

        // modify table : cms_main_user
        if (CMS_SUBSITE == '') {
            $fields = array('subsite' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => true));
            $this->dbforge->add_column(cms_table_name('main_user'), $fields);
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
                $found = true;
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

    private function __update_to_0_7_8(){

    }

    private function __update_to_0_7_9()
    {
        $fields = array(
                'description' => array(
                        'null' => true,
                    ),
            );
        $this->dbforge->modify_column(cms_table_name('main_route'), $fields);
    }

    private function __update_to_0_8_0()
    {
        $fields = array(
                'config_name' => array(
                        'constraint' => 100,
                    ),
            );
        $this->dbforge->modify_column(cms_table_name('main_config'), $fields);
    }

    private function __update_to_0_8_1(){
        // make extended route inclussion absolute
        $pattern = 'include(\'extended_routes.php\');';
        if (CMS_SUBSITE == '') {
            $path = APPPATH.'/config/main/routes.php';
            $replace = 'include(APPPATH.\'config/main/extended_routes.php\');';
        } else {
            $path = APPPATH.'/config/site-'.CMS_SUBSITE.'/routes.php';
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

    // TODO : Write your upgrade function here (__update_to_x_y_x)
}
