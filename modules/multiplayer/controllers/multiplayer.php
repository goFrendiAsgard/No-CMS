<?php
class Multiplayer extends CMS_Controller{
    
    //buat board yang ber canvas
    public function index(){
        $this->view('multiplayer_index',NULL,'multiplayer');
    }
    
    //kirim posisi ke client
    public function get_position(){        
        $SQL = "SELECT user_name, x,y,z,r,g,b FROM cms_user 
            LEFT JOIN multiplayer_position 
            ON cms_user.user_id = multiplayer_position.user_id";
        $query = $this->db->query($SQL);
        
        $return = array();
        foreach($query->result() as $row){
            $arr = array(
                "user_name"=>$row->user_name,
                "x"=>$row->x,
                "y"=>$row->y,
                "z"=>$row->z,
                "r"=>$row->r,
                "g"=>$row->g,
                "b"=>$row->b,
            );
            $return[] = $arr;
        }
        
        echo json_encode($return);
    }
    
    //dapatkan posisi dari client, simpan di database
    public function set_position(){
        $deltaX = $this->input->post('deltaX');
        $deltaY = $this->input->post('deltaY');
        $SQL = "SELECT x,y,z FROM multiplayer_position WHERE user_id='".$this->cms_userid()."'";
        $query = $this->db->query($SQL);
        if($query->num_rows()==0){
            $data = array(
                "x"=>$deltaX,
                "y"=>$deltaY,
                "user_id"=>$this->cms_userid()
            );
            $this->db->insert('multiplayer_position', $data);
        }else{
            foreach($query->result() as $row){
                $data = array(
                    "x"=>$deltaX+$row->x,
                    "y"=>$deltaY+$row->y
                );
                $where = array(
                    "user_id"=>$this->cms_userid()
                );
                $this->db->update('multiplayer_position', $data, $where);
            }
        }
    }
    
    //dapatkan warna dari client, simpan di database
    public function set_color(){
        $r = $this->input->post('r');
        $g = $this->input->post('g');
        $b = $this->input->post('b');
        $SQL = "SELECT r,g,b FROM multiplayer_position WHERE user_id='".$this->cms_userid()."'";
        $query = $this->db->query($SQL);
        if($query->num_rows()==0){
            $data = array(
                "r"=>$r,
                "g"=>$g,
                "b"=>$b,
                "user_id"=>$this->cms_userid()
            );
            $this->db->insert('multiplayer_position', $data);
        }else{
            foreach($query->result() as $row){
                $data = array(
                    "r"=>$r,
                    "g"=>$g,
                    "b"=>$b
                );
                $where = array(
                    "user_id"=>$this->cms_userid()
                );
                $this->db->update('multiplayer_position', $data, $where);
            }
        }
    }
}
?>