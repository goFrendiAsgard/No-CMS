<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Subsite_Model
 *
 * @author No-CMS Module Generator
 */
class Subsite_model extends  CMS_Model{

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

    public function get_subsite_config_table_name($subsite_name){
        $cms_config_file = APPPATH.'config/site-'.$subsite_name.'/cms_config.php';
        if(file_exists($cms_config_file)){
            $config = array();
            include $cms_config_file;
            if(array_key_exists('__cms_table_prefix', $config)){
                $table_prefix = $config['__cms_table_prefix'];
            }else if(array_key_exists('cms_table_prefix', $config)){
                $table_prefix = $config['cms_table_prefix'];
            }else{
                $table_prefix = 'site_'.$subsite_name;
            }
            $config_table_name = $table_prefix.'_main_config';
        }else{
            $config_table_name = cms_table_name('site_'.$subsite_name.'_main_config');
        }
        return $config_table_name;
    }

    public function get_actual_logo($subsite_name){
        $config_table_name = $this->get_subsite_config_table_name($subsite_name);
        $table_exists = $this->db->table_exists($config_table_name);
        if($table_exists){
            $query = $this->db->select('value')->from($config_table_name)
                ->where('config_name', 'site_logo')
                ->get();
        }
        if($table_exists && $query->num_rows()>0){
            $row = $query->row();
            $logo = $row->value;
            $logo = $this->cms_parse_keyword($logo);
        }else{
            $query = $this->db->select('logo')
                ->from($this->t('subsite'))
                ->where('name',$subsite_name)
                ->get();
            $row = $query->row();
            $logo = $row->logo;
            if($logo === NULL || $logo == ''){
                $logo = base_url('modules/{{ module_path }}/assets/images/default-logo.png');
            }else{
                $logo = base_url('modules/{{ module_path }}/assets/uploads/'.$logo);
            }
        }
        return $logo;
    }

    public function delete($subsite){
        $this->db->delete($this->t('subsite'), array('name'=>$subsite));
    }

    public function get_data($keyword, $page=0){
        $limit = 9;
        $is_super_admin = in_array($this->cms_user_id(), $this->cms_user_group_id());

        $keyword = addslashes($keyword);
        $where = '(subsite.name LIKE \'%'.$keyword.'%\' OR subsite.description LIKE \'%'.$keyword.'%\')';
        if($is_super_admin){
            $where .= ' AND active = 1';
        }
        $query = $this->db->select('subsite.id, subsite.name, subsite.use_subdomain, subsite.logo, subsite.description, subsite.modules, subsite.themes, subsite.user_id, subsite.active')
            ->from($this->t('subsite').' as subsite')
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
            $result[$i]->logo = $this->get_actual_logo($result[$i]->name);
        }
        return $result;
    }

    public function get_one_data($subsite_name){
        $query = $this->db->select('subsite.id, subsite.name, subsite.aliases, subsite.use_subdomain, subsite.logo, subsite.description, subsite.modules, subsite.themes, subsite.user_id, subsite.active')
            ->from($this->t('subsite').' as subsite')
            ->where('name',$subsite_name)
            ->get();
        if($query->num_rows()>0){
            $row = $query->row();
            $current_user_id = $this->cms_user_id();
            $group_id_array = $this->cms_user_group_id();
            $row->allow_edit = $current_user_id == $row->user_id || in_array(1, $group_id_array);
            $row->themes = $this->explode_and_trim($row->themes);
            $row->modules = $this->explode_and_trim($row->modules);
            $row->logo = $this->get_actual_logo($row->name);
            return $row;
        }
        return NULL;
    }

    public function module_list($subsite=NULL){
        $cms_module_list = $this->cms_get_module_list();
        $forbidden_module_names = array('gofrendi.noCMS.blocker', 'gofrendi.noCMS.multisite');
        $module_list = array();
        foreach($cms_module_list as $module){
            // forbidden module should not be shown
            if(in_array($module['module_name'], $forbidden_module_names)){
                continue;
            }
            // public module should not be shown
            if($module['public']){
                continue;
            }
            $module_list[] = $module;
        }
        return $module_list;
    }

    public function theme_list($subsite=NULL){
        $cms_theme_list = $this->cms_get_theme_list();
        $themes         = array();
        foreach ($cms_theme_list as $theme) {
            if($theme['public']){
                continue;
            }
            $themes[] = $theme;
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

    public function public_theme_list(){
        $cms_theme_list = $this->cms_get_theme_list();
        $themes         = array();
        foreach ($cms_theme_list as $theme) {
            if(!$theme['public']){
                continue;
            }
            $themes[] = $theme['path'];
        }
        return $themes;
    }

    public function template_list(){
        $query = $this->db->select('name, icon, description')
            ->from($this->t('template'))
            ->get();
        $template = array();
        foreach($query->result() as $row){
            $template[] = array(
                    'name'        => $row->name,
                    'icon'        => $row->icon,
                    'description' => $row->description
                );
        }
        return $template;
    }

    public function get_single_template($name){
        $query = $this->db->select('name, icon, description, homepage, configuration, modules')
            ->from($this->t('template'))
            ->where('name', $name)
            ->get();
        if($query->num_rows()>0){
            $row = $query->row();
            return $row;
        }
        return NULL;
    }

}
