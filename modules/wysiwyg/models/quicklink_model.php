<?php

/**
 * quicklink_model
 *
 * @author gofrendi
 */
class Quicklink_Model extends CMS_Model {
    public function get_quicklink(){
        $result = array();
        $SQL = "SELECT quicklink_id, title, cms_quicklink.`index` 
            FROM cms_quicklink, cms_navigation 
            WHERE cms_quicklink.navigation_id = cms_navigation.navigation_id
            ORDER BY `index`";
        $query = $this->db->query($SQL);
        foreach($query->result() as $row){
            $result[] = array(
                "id" => $row->quicklink_id,
                "title" => $this->cms_lang($row->title),
                "index" => $row->index
            );
        }
        return $result;
    }
    
    public function add_quicklink($navigation_id){
        $SQL = "SELECT MAX(`index`) AS lastIndex FROM cms_quicklink";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $lastIndex = $row->lastIndex;
        
        $data = array(
            "navigation_id" => $navigation_id,
            "index" => $lastIndex + 1
        );
        $this->db->insert("cms_quicklink", $data);
    }
    
    public function remove_quicklink($id){
        $SQL = "SELECT quicklink_id, `index` FROM cms_quicklink WHERE quicklink_id = $id";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $my_index = $row->index;
        $my_quicklink_id = $row->quicklink_id;
        
        $this->db->where("`index` >", $my_index, FALSE);
        $this->db->set("`index`", "`index`-1", FALSE);
        $this->db->update("cms_quicklink");
        
        $this->db->delete("cms_quicklink", array("quicklink_id"=>$id));
    }
    
    public function left_quicklink($id){
        $SQL = "SELECT quicklink_id, `index` FROM cms_quicklink WHERE quicklink_id = $id";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $my_index = $row->index;
        $my_quicklink_id = $row->quicklink_id;
        
        $SQL = " SELECT quicklink_id, `index` FROM cms_quicklink WHERE `index` =
            (SELECT MAX(`index`) FROM cms_quicklink 
            WHERE `index`<$my_index)";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $left_index = $row->index;
        $left_quicklink_id = $row->quicklink_id;
        
        if(isset($left_quicklink_id)){
            $this->db->update("cms_quicklink", array("index"=>$my_index), array("quicklink_id"=>$left_quicklink_id));
            $this->db->update("cms_quicklink", array("index"=>$left_index), array("quicklink_id"=>$my_quicklink_id));
        }
        
    }
    
    public function right_quicklink($id){
        $SQL = "SELECT quicklink_id, `index` FROM cms_quicklink WHERE quicklink_id = $id";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $my_index = $row->index;
        $my_quicklink_id = $row->quicklink_id;
        
        $SQL = " SELECT quicklink_id, `index` FROM cms_quicklink WHERE `index` =
            (SELECT MIN(`index`) FROM cms_quicklink 
            WHERE `index`>$my_index)";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $right_index = $row->index;
        $right_quicklink_id = $row->quicklink_id;
        
        if(isset($right_quicklink_id)){
            $this->db->update("cms_quicklink", array("index"=>$my_index), array("quicklink_id"=>$right_quicklink_id));
            $this->db->update("cms_quicklink", array("index"=>$right_index), array("quicklink_id"=>$my_quicklink_id));
        }
        
    }
}

?>
