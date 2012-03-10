<?php

/**
 * Description of CMS_Module_Installer
 *
 * @author gofrendi
 */
class CMS_Module_Installer extends CMS_Controller {
    protected $DEPENDENCIES = array();
    
    public function index(){
        $this->install();
    }
    
    public function install(){
        if($this->cms_have_privilege('cms_install_module')){
            $dependenciesOK = TRUE;
            foreach($this->DEPENDENCIES as $dependency){
                if(!$this->cms_is_module_installed($dependency)){
                    $dependenciesOK = FALSE;
                    break;
                }
            }
            if($dependenciesOK){
                $this->register_module();
                $this->do_install();
                redirect('main/module_management');
            }else{
                $data=array(
                    'module_name'=>$this->uri->segment(1),
                    'dependencies'=>$this->DEPENDENCIES
                );
                $this->view('main/module_management_fail_install',$data,'main_module_management');
            }
        }        
    }
    public function uninstall(){
        if($this->cms_have_privilege('cms_install_module')){
            $child = $this->child();
            if(count($child)==0){
                $this->unregister_module();
                $this->do_uninstall();
                redirect('main/module_management');
            }else{
                $data=array(
                    'module_name'=>$this->uri->segment(1),
                    'dependencies'=>$child
                );
                $this->view('main/module_management_fail_uninstall',$data,'main_module_management');
            }
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
    protected function add_navigation($navigation_name, $title, $url, $authorization_id=1, $parent_name=NULL, $index = NULL, $description=NULL){
            	
    	//get parent's navigation_id
        $SQL = "SELECT navigation_id FROM cms_navigation WHERE navigation_name='".addslashes($parent_name)."'";
        $query = $this->db->query($SQL);
        $row = $query->row();
        
        $parent_id = isset($row->navigation_id)? $row->navigation_id: NULL;
        
        //if it is null, index = max index+1
        if(!isset($index)){
        	if(isset($parent_id)){
        		$whereParentId = "(parent_id = $parent_id)";
        	}else{
        		$whereParentId = "(parent_id IS NULL)";
        	}
        	$SQL = "SELECT max(`index`)+1 AS newIndex FROM `cms_navigation` WHERE $whereParentId";
        	$query = $this->db->query($SQL);
        	$row = $query->row();
        	$index = $row->newIndex;
        	if(!isset($index)) $index = 0;
        }
            
        //insert it :D
        $data = array(
            "navigation_name" => $navigation_name,
            "title" => $title,
            "url" => $url,
            "authorization_id" => $authorization_id,
            "index" => $index,
            "description" => $description
        );
        if(isset($parent_id)){
            $data['parent_id'] = $parent_id;
        }      
        $this->db->insert('cms_navigation',$data);
    }
    protected function remove_navigation($navigation_name){
        //get navigation_id
        $SQL = "SELECT navigation_id FROM cms_navigation WHERE navigation_name='".addslashes($navigation_name)."'";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $navigation_id = isset($row->navigation_id)? $row->navigation_id: NULL;
        
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
    
    private function register_module(){
        //insert to cms_module
        $data = array(
            'module_name'=>$this->uri->segment(1),
            'user_id'=>$this->cms_userid()
        );
        $this->db->insert('cms_module',$data);
        
        //get current cms_module_id as child_id
        $SQL = "SELECT module_id FROM cms_module WHERE module_name='".  addslashes($this->uri->segment(1))."'";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $child_id = $row->module_id;
            
        //get parent_id
        if(isset($child_id)){
            foreach($this->DEPENDENCIES as $dependency){
                $SQL = "SELECT module_id FROM cms_module WHERE module_name='".  addslashes($dependency)."'";
                $query = $this->db->query($SQL);
                $row = $query->row();
                $parent_id = $row->module_id;
                $data = array(
                    "parent_id" => $parent_id,
                    "child_id" => $child_id
                );
                $this->db->insert('cms_module_dependency', $data);
                
            }
        }
        
    }
    private function unregister_module(){
        //get current cms_module_id as child_id
        $SQL = "SELECT module_id FROM cms_module WHERE module_name='".  addslashes($this->uri->segment(1))."'";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $child_id = $row->module_id;
        
        $where = array(
            'child_id'=>$child_id
        );
        $this->db->delete('cms_module_dependency',$where);
        
        $where = array(
            'module_name'=>$this->uri->segment(1)
        );
        $this->db->delete('cms_module',$where);
    }
    
    private function child(){
        $SQL = "SELECT module_id FROM cms_module WHERE module_name='".  addslashes($this->uri->segment(1))."'";
        $query = $this->db->query($SQL);
        $query = $this->db->query($SQL);
        $row = $query->row();
        $parent_id = $row->module_id;
        
        $SQL = "
            SELECT module_name 
            FROM 
                cms_module_dependency,
                cms_module
            WHERE
                module_id = child_id AND
                parent_id=".$parent_id;
        $query = $this->db->query($SQL);
        $result = array();
        foreach($query->result() as $row){
            $result[] = $row->module_name;
        }
        return $result;
    }
    
    protected function add_widget($widget_name, $title, $authorization_id=1, $url=NULL, $slug=NULL, $index=NULL, $description=NULL){
    	//if it is null, index = max index+1
    	if(!isset($index)){
    		if(isset($slug)){
    			$whereSlug = "(slug = '$slug')";
    		}else{
    			$whereSlug = "(slug IS NULL)";
    		}
    		$SQL = "SELECT max(`index`)+1 AS newIndex FROM `cms_widget` WHERE $whereSlug";
    		$query = $this->db->query($SQL);
    		$row = $query->row();
    		$index = $row->newIndex;
    		
    		if(!isset($index)) $index = 0;
    	}
    	
    	$data = array(
            "widget_name" => $widget_name,
            "title" => $title,
            "slug" => $slug,
            "index" => $index,
            "authorization_id" => $authorization_id,
            "url" => $url,
            "description" => $description
        );
        $this->db->insert('cms_widget',$data);        
    }
    protected function remove_widget($widget_name){
        $SQL = "SELECT widget_id FROM cms_widget WHERE widget_name='".addslashes($widget_name)."'";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $widget_id = $row->widget_id;
        
        if(isset($widget_id)){
            //delete cms_group_privilege
            $where = array("widget_id"=>$widget_id);
            $this->db->delete("cms_group_widget",$where);
            //delete cms_privilege
            $where = array("widget_id"=>$widget_id);
            $this->db->delete("cms_widget",$where);
        }
    }
}

?>
