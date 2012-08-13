<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of CMS_Module_Installer
 *
 * @author gofrendi
 */
class CMS_Module_Installer extends CMS_Controller {
    protected $DEPENDENCIES = array();
    protected $NAME = '';
    
    public final function index(){
    	if($this->cms_is_module_installed($this->NAME)){
        	$this->uninstall();
    	}else{
    		$this->install();
    	}
    }
    
    public final function install(){
    	// login (in case of called from No-CMS installer)
    	$silent = $this->input->post('silent');
    	$identity = $this->input->post('identity');
    	$password = $this->input->post('password');
    	if($identity && $password){
    		$this->cms_do_login($identity, $password);
    	}
    	
        if($this->cms_have_privilege('cms_install_module')){
            $dependencies_error = FALSE;
            foreach($this->DEPENDENCIES as $dependency){
                if(!$this->cms_is_module_installed($dependency)){
                    $dependencies_error = TRUE;
                    break;
                }
            }
            
            $alreadyInstalled_error = $this->cms_is_module_installed($this->NAME);
            $undefinedName_error = $this->NAME == '';
            
            $error = $dependencies_error || $alreadyInstalled_error || $undefinedName_error;
            if($error){
            	$data=array(
            			'module_name'=>$this->NAME,
            			'module_path'=>$this->cms_module_path(),
            			'dependencies'=>$this->DEPENDENCIES,
            			'dependencies_error'=>$dependencies_error,
            			'alreadyInstalled_error'=>$alreadyInstalled_error,
            			'undefinedName_error'=>$undefinedName_error,
            			'success'=>FALSE,
            	);
            	if(!$silent){
            		$this->view('main/module_management_fail_install',$data,'main_module_management');
            	}else{
            		$this->output
	            		->set_content_type('application/json')
	            		->set_output(json_encode($data));
            	}    	
            }else{
            	$this->register_module();
            	$this->do_install();
            	if(!$silent){
            		redirect('main/module_management');
            	}else{
            		$data = array(
            				'success'=>TRUE,
            			);
            		$this->output
	            		->set_content_type('application/json')
	            		->set_output(json_encode($data));
            	}            	
            }
        }        
    }
    public final function uninstall(){
        if($this->cms_have_privilege('cms_install_module')){
        	$children = $this->child();
        	$dependencies_error = count($children) != 0;
        	$alreadyUninstalled_error = !$this->cms_is_module_installed($this->NAME); 
        	$undefinedName_error = $this->NAME == '';
        	
        	$error = $dependencies_error || $alreadyUninstalled_error || $undefinedName_error;
        	
            if($error){
            	$data=array(
            			'module_name'=>$this->NAME,
            			'module_path'=>$this->uri->segment(1),
            			'dependencies'=>$children,
            			'dependencies_error'=>$dependencies_error,
            			'alreadyUninstalled_error'=>$alreadyUninstalled_error,
            			'undefinedName_error'=>$undefinedName_error,
            	);
            	$this->view('main/module_management_fail_uninstall',$data,'main_module_management'); 
            }else{
            	$this->unregister_module();
            	$this->do_uninstall();
            	redirect('main/module_management');
            }
        }
    }
    
    protected function do_install(){
        //this should be overridden by module developer
    }
    protected function do_uninstall(){
        //this should be overridden by module developer
    }
    protected final function executeSQL($SQL, $separator){
        $queries = explode($separator, $SQL);
        foreach($queries as $query){
            $this->db->query($query);
        }
    }
    protected final function add_navigation($navigation_name, $title, $url, $authorization_id=1, $parent_name=NULL, $index = NULL, $description=NULL){
            	
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
    protected final function remove_navigation($navigation_name){
        //get navigation_id
        $SQL = "SELECT navigation_id FROM cms_navigation WHERE navigation_name='".addslashes($navigation_name)."'";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $navigation_id = isset($row->navigation_id)? $row->navigation_id: NULL;
        
        if(isset($navigation_id)){
        	//delete quicklink
        	$where = array("navigation_id"=>$navigation_id);
        	$this->db->delete('cms_quicklink', $where);
            //delete cms_group_navigation
            $where = array("navigation_id" => $navigation_id);
            $this->db->delete('cms_group_navigation', $where);
            //delete cms_navigation
            $where = array("navigation_id" => $navigation_id);
            $this->db->delete('cms_navigation', $where);
        }
    }
    protected final function add_privilege($privilege_name, $title, $authorization_id=1, $parent_name=NULL, $description=NULL){
        $data = array(
            "privilege_name" => $privilege_name,
            "title" => $title,
            "authorization_id" => $authorization_id,
            "description" => $description
        );
        $this->db->insert('cms_navigation',$data);        
    }
    protected final function remove_privilege($privilege_name){
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
    
    private final function register_module(){
        //insert to cms_module
        $data = array(
        	'module_name'=>$this->NAME,
            'module_path'=>$this->uri->segment(1),
            'user_id'=>$this->cms_userid()
        );
        $this->db->insert('cms_module',$data);
        
        //get current cms_module_id as child_id
        $SQL = "SELECT module_id FROM cms_module WHERE module_name='".  addslashes($this->NAME)."'";
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
    private final function unregister_module(){
        //get current cms_module_id as child_id
        $SQL = "SELECT module_id FROM cms_module WHERE module_name='".  addslashes($this->NAME)."'";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $child_id = $row->module_id;
        
        $where = array(
            'child_id'=>$child_id
        );
        $this->db->delete('cms_module_dependency',$where);
        
        $where = array(
            'module_path'=>$this->cms_module_path()
        );
        $this->db->delete('cms_module',$where);
    }
    
    private final function child(){
        $SQL = "SELECT module_id FROM cms_module WHERE module_name='".  addslashes($this->NAME)."'";
        $query = $this->db->query($SQL);
        $row = $query->row();
        if($query->num_rows()>0){
	        $parent_id = $row->module_id;
	        
	        $SQL = "
	            SELECT module_name, module_path 
	            FROM 
	                cms_module_dependency,
	                cms_module
	            WHERE
	                module_id = child_id AND
	                parent_id=".$parent_id;
	        $query = $this->db->query($SQL);
	        $result = array();
	        foreach($query->result() as $row){
	            $result[] = array(
	            		"module_name"=>$row->module_name,
	            		"module_path"=>$row->module_name
	            	);
	        }
	        return $result;
        }else{
        	return array();
        }
    }
    
    protected final function add_widget($widget_name, $title, $authorization_id=1, $url=NULL, $slug=NULL, $index=NULL, $description=NULL){
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
    protected final function remove_widget($widget_name){
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
    
    protected final function add_quicklink($navigation_name){
    	$SQL = "SELECT navigation_id FROM cms_navigation WHERE navigation_name ='".addslashes($navigation_name)."'";
    	$query = $this->db->query($SQL);
    	if($query->num_rows()>0){
    		$row = $query->row();
    		$navigation_id = $row->navigation_id;
    		// index = max index+1
    		$SQL = "SELECT max(`index`)+1 AS newIndex FROM `cms_quicklink`";
    		$query = $this->db->query($SQL);
    		$row = $query->row();
    		$index = $row->newIndex;    			 
    		if(!isset($index)) $index = 0;
    		
    		// insert
    		$data = array(
    			"navigation_id" => $navigation_id,
    			"index" => $index
    		);
    		$this->db->insert('cms_quicklink', $data);
    	}
    }
    
    protected final function remove_quicklink($navigation_name){
    	$SQL = "SELECT navigation_id FROM cms_navigation WHERE navigation_name ='".addslashes($navigation_name)."'";
    	$query = $this->db->query($SQL);
    	if($query->num_rows()>0){
    		$row = $query->row();
    		$navigation_id = $row->navigation_id;
    	
    		// delete
    		$where = array(
    				"navigation_id" => $navigation_id
    		);
    		$this->db->delete('cms_quicklink', $where);
    	}	
    }
}

?>
