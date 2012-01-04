<?php

/**
 * Description of ai_core
 *
 * @author gofrendi
 */
class AI_Core extends CMS_Model{
    private $core_identifier;    //identifier, should be different for each intance
    private $core_property;    //all property should be putted here
    
    public function __construct(){
        parent::__construct();
    }
    
    //Call this on every controller after load model
    public function core_initialize($identifier){
        $this->core_identifier($identifier);
        $this->core_property = $this->core_getProperty();
    }
    
    protected function core_identifier($identifier=NULL){
        if(isset($identifier)){
            $this->core_identifier = $identifier;
        }else{
            return $this->core_identifier;
        }
    }
    
    public function core_saveProperty($key=NULL, $value=NULL){
        $this->core_property = $this->core_getProperty();
        if(isset($key)){
            if(count($key)>0){
                for($i=0; $i<count($key); $i++){
                    $this->core_property[$key[$i]] = $value[$i];
                }
            }else{
                $this->core_property[$key] = $value;
            }
            
        }        
        
        //is current session exist on db, if it is then update, else then insert
        $SQL = "
            SELECT identifier 
            FROM ai_session 
            WHERE 
                identifier='".addslashes($this->core_identifier)."' AND
                user_id=".$this->cms_userid();
        $query = $this->db->query($SQL);
        if($query->num_rows()>0){
            $data = array(
                "data" => json_encode($this->core_property)
            );
            $where = array(
                "identifier" => $this->core_identifier,
                "user_id" => $this->cms_userid()
            );
            $this->db->update("ai_session", $data, $where);
        }else{
            $data = array(
                "data" => json_encode($this->core_property),
                "identifier" => $this->core_identifier,
                "user_id" => $this->cms_userid()
            );
            $this->db->insert("ai_session", $data);
        }
    }
    
    public function core_getProperty($key=NULL){
        $SQL = "
            SELECT data 
            FROM ai_session 
            WHERE 
                identifier='".addslashes($this->core_identifier)."' AND
                user_id=".$this->cms_userid();
        $query = $this->db->query($SQL);
        if($query->num_rows()>0){
            $row = $query->row();
            $this->core_property = json_decode($row->data, true);
            if(isset($key)){
                $data = $this->core_property[$key];
            }else{
                $data = $this->core_property;
            }
            return $data;
        }else{
            return NULL;
        }
        
    }
    
}

?>
