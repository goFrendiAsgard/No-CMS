<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Subsite_Model
 *
 * @author No-CMS Module Generator
 */
class Subsite_Model extends  CMS_Model{

    private function explode_and_trim($variable){
        $list = explode(',', $variable);
        for($i=0; $i<count($list); $i++){
            $list[$i] = trim($list[$i]);
        }
        if(count($list)==1 && $list[0] == ''){
            $list = array();
        }
        return $list;
    }

    public function get_data($keyword, $page=0){
        $limit = 9;
        $where = 'active = 1 AND(
                subsite.name LIKE \'%'.$keyword.'%\' OR
                subsite.description LIKE \'%'.$keyword.'%\'
            )';
        $query = $this->db->select('subsite.id, subsite.name, subsite.use_subdomain, subsite.logo, subsite.description, subsite.modules, subsite.themes, subsite.user_id')
            ->from($this->cms_complete_table_name('subsite').' as subsite')
            ->where($where)
            ->order_by('subsite.id','desc')
            ->limit($limit, $page*$limit)
            ->get();
        $result = $query->result();
        $current_user_id = $this->cms_user_id();
        $group_id_array = $this->cms_user_group_id();
        for($i=0; $i<count($result); $i++){
            $result[$i]->allow_edit = $current_user_id == $result[$i]->user_id || in_array(1, $group_id_array);
            $result[$i]->themes = $this->explode_and_trim($result[$i]->themes);
            $result[$i]->modules = $this->explode_and_trim($result[$i]->modules);
        }
        return $result;
    }

    public function get_one_data($subsite_name){
        $query = $this->db->select('subsite.id, subsite.name, subsite.aliases, subsite.use_subdomain, subsite.logo, subsite.description, subsite.modules, subsite.themes, subsite.user_id')
            ->from($this->cms_complete_table_name('subsite').' as subsite')
            ->where('name',$subsite_name)
            ->get();
        $row = $query->row();
        $current_user_id = $this->cms_user_id();
        $group_id_array = $this->cms_user_group_id();
        $row->allow_edit = $current_user_id == $row->user_id || in_array(1, $group_id_array);
        $row->themes = $this->explode_and_trim($row->themes);
        $row->modules = $this->explode_and_trim($row->modules);
        return $row;
    }

    public function module_list($subsite=NULL){
        $this->load->helper('directory');
        $directories = directory_map(FCPATH.'modules', 1);
        sort($directories);
        $module      = array();
        foreach ($directories as $directory) {
            $directory = str_replace(array('/','\\'),'',$directory);
            if (!is_dir(FCPATH . 'modules/' . $directory))
                continue;

            if (!file_exists(FCPATH . 'modules/' . $directory . '/controllers/install.php'))
                continue;

            // unpublished module should not be shown
            $subsite_auth_file = FCPATH . 'modules/' . $directory . '/subsite_auth.php';
            if (file_exists($subsite_auth_file)){
                unset($public);
                unset($subsite_allowed);
                include($subsite_auth_file);
                if(isset($public) && is_bool($public) && !$public){
                    $module[] = $directory;
                }
            }
        }
        return $module;
    }

    public function theme_list($subsite=NULL){
        $this->load->helper('directory');
        $directories = directory_map(FCPATH.'themes', 1);
        sort($directories);
        $themes      = array();
        foreach ($directories as $directory) {
            $directory = str_replace(array('/','\\'),'',$directory);
            if (!is_dir(FCPATH.'themes/' . $directory))
                continue;

            $subsite_auth_file = FCPATH.'themes/'.$directory.'/subsite_auth.php';
            if(file_exists($subsite_auth_file)){
                unset($public);
                unset($subsite_allowed);
                include($subsite_auth_file);
                if(isset($public) && is_bool($public) && !$public){
                    $themes[] = $directory;
                }
            }
        }
        return $themes;
    }

    private function write_php_array($variable_name, $array){
        $new_array = array();
        foreach($array as $element){
            $new_array[] = "'".addslashes(trim($element))."'";
        }
        $str = implode(', ', $new_array);
        $content = '$'.$variable_name.' = array('.$str.');';
        return $content;
    }

    public function update_configs(){
        $query = $this->db->select('name, aliases, use_subdomain, modules, themes')
            ->from($this->cms_complete_table_name('subsite'))
            ->where('active', 1)
            ->get();
        $name_list = array();
        $alias_list = array();
        $module_auth_list = array();
        $theme_auth_list = array();
        foreach($query->result() as $row){
            $name_list[] = $row->name;
            // aliases
            if($row->aliases != ''){
                $aliases_array = explode(',', $row->aliases);
                foreach($aliases_array as $alias){
                    $alias_list[trim($alias)] = $row->name;
                }
            }
            // modules
            if($row->modules != ''){
                $modules_array = explode(',', $row->modules);
                foreach($modules_array as $module){
                    $module = trim($module);
                    if(!isset($module_auth_list[$module])){
                        $module_auth_list[$module] = array();
                    }
                    $module_auth_list[$module][] = $row->name;
                }
            }
            // themes
            if($row->themes != ''){
                $themes_array = explode(',', $row->themes);
                foreach($themes_array as $theme){
                    $theme = trim($theme);
                    if(!isset($theme_auth_list[$theme])){
                        $theme_auth_list[$theme] = array();
                    }
                    $theme_auth_list[$theme][] = $row->name;
                }
            }
        }
        // site.php
        $content = '<?php'.PHP_EOL.'// GENERATED AUTOMATICALLY, DO NOT EDIT THIS FILE !!!'.PHP_EOL;
        $content .= $this->write_php_array('available_site', $name_list).PHP_EOL;
        foreach($alias_list as $alias => $name){
            $content .= '$site_alias[\''.addslashes(trim($alias)).'\'] = \''.$name.'\';'.PHP_EOL;
        }
        $file_name = FCPATH.'site.php';
        @chmod($file_name,0777);
        file_put_contents($file_name, $content);

        // subsite_auth.php
        foreach($module_auth_list as $module=>$subsite_array){
            $content = '<?php defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');'.PHP_EOL.'// GENERATED AUTOMATICALLY, DO NOT EDIT THIS FILE !!!'.PHP_EOL;
            $content .= '// Is the module published for every subsite?'.PHP_EOL;
            $content .= '$public = FALSE;'.PHP_EOL;
            $content .= '// In case of $public is FALSE, what are subsites allowed to use this module?'.PHP_EOL;
            $content .= $this->write_php_array('subsite_allowed', $subsite_array);
            $file_name = FCPATH.'modules/'.$module.'/subsite_auth.php';
            @chmod($file_name,0777);
            file_put_contents($file_name, $content);
        }
        foreach($theme_auth_list as $theme=>$subsite_array){
            $content = '<?php defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');'.PHP_EOL.'// GENERATED AUTOMATICALLY, DO NOT EDIT THIS FILE !!!'.PHP_EOL;
            $content .= '// Is the theme published for every subsite?'.PHP_EOL;
            $content .= '$public = FALSE;'.PHP_EOL;
            $content .= '// In case of $public is FALSE, what are subsites allowed to use this module?'.PHP_EOL;
            $content .= $this->write_php_array('subsite_allowed', $subsite_array);
            $file_name = FCPATH.'themes/'.$theme.'/subsite_auth.php';
            @chmod($file_name,0777);
            file_put_contents($file_name, $content);
        }

    }

}