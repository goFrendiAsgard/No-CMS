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
		$limit = 10;
		$query = $this->db->select('subsite.id, subsite.name, subsite.use_subdomain, subsite.logo, subsite.description, subsite.modules, subsite.themes')
			->from($this->cms_complete_table_name('subsite').' as subsite')
			->like('subsite.name', $keyword)
			->or_like('subsite.description', $keyword)
			->limit($limit, $page*$limit)
			->get();
		$result = $query->result();
        for($i=0; $i<count($result); $i++){
            $result[$i]->themes = $this->explode_and_trim($result[$i]->themes);
            $result[$i]->modules = $this->explode_and_trim($result[$i]->modules);
        }
		return $result;
	}

    public function get_one_data($subsite_name){
        $query = $this->db->select('subsite.id, subsite.name, subsite.use_subdomain, subsite.logo, subsite.description, subsite.modules, subsite.themes')
            ->from($this->cms_complete_table_name('subsite').' as subsite')
            ->where('name',$subsite_name)
            ->get();
        $row = $query->row();
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

}