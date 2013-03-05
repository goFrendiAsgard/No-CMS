<?php

class Navigation_Model extends CMS_Model{

    public function get_all_navigation(){
        $result = array();
        $SQL = "SELECT navigation_id, navigation_name, title FROM ".$this->cms_complete_table_name('main_navigation');
        $query = $this->db->query($SQL);
        foreach($query->result() as $row){
            $result[] = array(
                "id" =>$row->navigation_id,
                "name"=>$row->navigation_name,
                "title"=>$this->cms_lang($row->title)
            );
        }
        return $result;
    }

    public function get_navigation($parent_id = NULL){
        $SQL = "";
        $result = array();
        if(!isset($parent_id)){
            $SQL = "SELECT navigation_id, title, `index`, active, (parent_id IS NULL) AS is_root
                FROM ".$this->cms_complete_table_name('main_navigation')." WHERE parent_id IS NULL ORDER BY `index`";
        }else{
            $SQL = "SELECT navigation_id, title, `index`, active, (parent_id IS NULL) AS is_root
                FROM ".$this->cms_complete_table_name('main_navigation')." WHERE parent_id = $parent_id ORDER BY `index`";
        }
        $query = $this->db->query($SQL);
        foreach($query->result() as $row){
            $result[] = array(
                "id" => $row->navigation_id,
                "title" => $this->cms_lang($row->title),
                "index" => $row->index,
                "active" => $row->active==1,
                "is_root" => $row->is_root==1,
                "children" => $this->get_navigation($row->navigation_id)
            );
        }
        return $result;
    }

    public function toggle_navigation($id){
        $SQL = "SELECT active FROM ".$this->cms_complete_table_name('main_navigation')." WHERE navigation_id = $id";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $active = $row->active;

        $data = array("active" => $active==1? 0: 1);
        $where = array("navigation_id" => $id);
        $this->db->update($this->cms_complete_table_name('main_navigation'), $data, $where);
    }

    public function promote_navigation($id){
        //me
        $SQL = "SELECT navigation_id, parent_id, (parent_id IS NULL) AS is_root, `index` FROM ".$this->cms_complete_table_name('main_navigation')." WHERE navigation_id=$id";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $my_parent_id = $row->parent_id;
        $my_index = $row->index;
        $my_navigation_id = $row->navigation_id;
        $my_is_root = $row->is_root;

        if($my_is_root==0){
            //dad
            $SQL = "SELECT navigation_id, parent_id, (parent_id IS NULL) AS is_root, `index` FROM ".$this->cms_complete_table_name('main_navigation')."
                WHERE navigation_id= $my_parent_id";
            $query = $this->db->query($SQL);
            $row = $query->row();
            $dad_parent_id = $row->parent_id;
            $dad_index = $row->index;
            $dad_navigation_id = $row->index;
            $dad_is_root = $row->is_root;


            //dad's younger bro index+=1, so that I can place the position
            $dadSibling = isset($dad_parent_id)?"parent_id=$dad_parent_id":"parent_id IS NULL";
            $this->db->set("`index`", "`index`+1", FALSE);
            $this->db->where("`index` > $dad_index AND $dadSibling");
            $this->db->update($this->cms_complete_table_name('main_navigation'));


            //I become dad's younger_bro
            $data = array(
                "parent_id"=>$dad_parent_id,
                "index"=>$dad_index+1
            );
            $where = array("navigation_id"=>$my_navigation_id);
            $this->db->update($this->cms_complete_table_name('main_navigation'), $data, $where);


            //my younger bro take my position
            $haveSameParent = isset($my_parent_id)?"parent_id=$my_parent_id":"parent_id IS NULL";
            $this->db->set("`index`", "`index`-1", FALSE);
            $this->db->where("`index` > $my_index AND $haveSameParent");
            $this->db->update($this->cms_complete_table_name('main_navigation'));
        }

    }

    public function demote_navigation($id){
        //me
        $SQL = "SELECT navigation_id, parent_id, `index` FROM ".$this->cms_complete_table_name('main_navigation')." WHERE navigation_id=$id";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $my_parent_id = $row->parent_id;
        $my_index = $row->index;
        $my_navigation_id = $row->navigation_id;

        //big bro
        $haveSameParent = isset($my_parent_id)?"parent_id=$my_parent_id":"parent_id IS NULL";
        $SQL = "SELECT navigation_id, parent_id, `index`,
            (SELECT MAX(`index`) FROM ".$this->cms_complete_table_name('main_navigation')." WHERE parent_id = bro.navigation_id) AS last_child_index
            FROM ".$this->cms_complete_table_name('main_navigation')." bro
            WHERE
                $haveSameParent AND
                `index`=(SELECT MAX(`index`) FROM ".$this->cms_complete_table_name('main_navigation')." WHERE `index`<$my_index AND $haveSameParent) ";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $bro_navigation_id = $row->navigation_id;
        $bro_index = $row->index;
        $bro_last_child_index = isset($row->last_child_index) ? $row->last_child_index : 0;

        if(isset($bro_navigation_id)){
            //I become big bro youngest son
            $data = array(
                "parent_id"=>$bro_navigation_id,
                "index"=>$bro_last_child_index+1
            );
            $where = array("navigation_id"=>$my_navigation_id);
            $this->db->update($this->cms_complete_table_name('main_navigation'), $data, $where);

            //my younger bro take my position
            $this->db->set("`index`", "`index`-1", FALSE);
            $this->db->where("`index` > $my_index AND $haveSameParent");
            $this->db->update($this->cms_complete_table_name('main_navigation'));
        }

    }

    public function down_navigation($id){
        //me
        $SQL = "SELECT parent_id, `index` FROM ".$this->cms_complete_table_name('main_navigation')." WHERE navigation_id=$id";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $my_parent_id = $row->parent_id;
        $my_index = $row->index;

        //little bro
        $where_parent = isset($my_parent_id)? "parent_id = $my_parent_id": "parent_Id IS NULL";
        $SQL = "SELECT navigation_id, `index`  FROM ".$this->cms_complete_table_name('main_navigation')." bro
            WHERE $where_parent AND
                `index` = (SELECT MIN(`index`) FROM ".$this->cms_complete_table_name('main_navigation')." WHERE `index`>$my_index AND $where_parent)";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $bro_navigation_id = $row->navigation_id;
        $bro_index = $row->index;

        if(isset($bro_navigation_id)){
            //swap
            $this->db->update($this->cms_complete_table_name('main_navigation'), array("index"=>$bro_index), array("navigation_id"=>$id));
            $this->db->update($this->cms_complete_table_name('main_navigation'), array("index"=>$my_index), array("navigation_id"=>$bro_navigation_id));
        }

    }

    public function up_navigation($id){
        //me
        $SQL = "SELECT parent_id, `index` FROM ".$this->cms_complete_table_name('main_navigation')." WHERE navigation_id=$id";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $my_parent_id = $row->parent_id;
        $my_index = $row->index;

        //big bro
        $where_parent = isset($my_parent_id)? "parent_id = $my_parent_id": "parent_Id IS NULL";
        $SQL = "SELECT navigation_id, `index`  FROM ".$this->cms_complete_table_name('main_navigation')." bro
            WHERE $where_parent AND
                `index` = (SELECT MAX(`index`) FROM ".$this->cms_complete_table_name('main_navigation')." WHERE `index`<$my_index AND $where_parent)";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $bro_navigation_id = $row->navigation_id;
        $bro_index = $row->index;

        if(isset($bro_navigation_id)){
            //swap
            $this->db->update($this->cms_complete_table_name('main_navigation'), array("index"=>$bro_index), array("navigation_id"=>$id));
            $this->db->update($this->cms_complete_table_name('main_navigation'), array("index"=>$my_index), array("navigation_id"=>$bro_navigation_id));
        }

    }

}
