<?php

/**
 * Description of CMS_Module_Installer
 *
 * @author gofrendi
 */
class CMS_Module_Installer extends CMS_Controller {
    //put your code here
    public function index($parameter = "install"){
        $this->install();
    }
    
    public function install(){
        if($this->have_privilege('cms_install_module')){
            $this->do_install();
        }        
    }
    public function uninstall(){
        if($this->have_privilege('cms_install_module')){
            $this->do_uninstall();
        }
    }
    
    protected function do_install(){
        //this should be overridden by module developer
    }
    protected function do_uninstall(){
        //this should be overridden by module developer
    }
    protected function executeSQL($SQL, $separator){
        $queries = explode($separator, $SQL);
        foreach($queries as $query){
            $this->db->query($query);
        }
    }
    protected function add_navigation($navigation_name, $title, $url, $authorization_id=1, $parent_name=NULL, $description=NULL){
        //get parent's navigation_id
        $SQL = "SELECT navigation_id FROM cms_navigation WHERE navigation_name='".addslashes($parent_name)."'";
        $query = $this->db->query($SQL);
        
        foreach($query->result() as $row){
            $parent_id = $row->navigation_id;
        }
        //insert it :D
        $data = array(
            "navigation_name" => $navigation_name,
            "title" => $title,
            "url" => $url,
            "authorization_id" => $authorization_id,
            "description" => $description
        );
        if(isset($parent_id)){
            $data['parent_id'] = $parent_id;
            $data['is_root'] = 0;
        }else{
            $data['is_root'] = 1;
        }        
        $this->db->insert('cms_navigation',$data);
    }
    protected function remove_navigation($navigation_name){
        //get navigation_id
        $SQL = "SELECT navigation_id FROM cms_navigation WHERE navigation_name='".addslashes($navigation_name)."'";
        $query = $this->db->query($SQL);
        
        foreach($query->result() as $row){
            $navigation_id = $row->navigation_id;
        }
        
        if(isset($navigation_id)){
            //delete cms_group_navigation
            $where = array("navigation_id" => $navigation_id);
            $this->db->delete('cms_group_navigation', $where);
            //delete cms_navigation
            $where = array("navigation_id" => $navigation_id);
            $this->db->delete('cms_navigation', $where);
        }
    }
    protected function add_privilege($privilege_name, $title, $authorization_id=1, $parent_name=NULL, $description=NULL){
        $data = array(
            "privilege_name" => $privilege_name,
            "title" => $title,
            "authorization_id" => $authorization_id,
            "description" => $description
        );
        $this->db->insert('cms_navigation',$data);        
    }
    protected function remove_privilege($privilege_name){
        $SQL = "SELECT privilege_id FROM cms_privilege WHERE privilege_name='".addslashes($privilege_name)."'";
        $query = $this->db->query($SQL);
        
        foreach($query->result() as $row){
            $privilege_id = $row->privilege_id;
        }
        
        if(isset($privilege_id)){
            //delete cms_group_privilege
            $where = array("privilege_id"=>$privilege_id);
            $this->db->delete("cms_group_privilege",$where);
            //delete cms_privilege
            $where = array("privilege_id"=>$privilege_id);
            $this->db->delete("cms_privilege",$where);
        }
    }
}

?>
