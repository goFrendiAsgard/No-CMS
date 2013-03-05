<?php
/**
 * Description of widget_model
 *
 * @author gofrendi
 */
class Widget_Model extends CMS_Model {
    public function get_widget($slug){
        $result = array();
        $SQL = "SELECT widget_id, title, `index`, slug, active FROM ".$this->cms_complete_table_name('main_widget')."
            WHERE slug='".  addslashes($slug)."' ORDER BY `index`";
        $query = $this->db->query($SQL);
        foreach($query->result() as $row){
            $result[] = array(
                "id" => $row->widget_id,
                "title" => $this->cms_lang($row->title),
                "index"=> $row->index,
                "slug" => $row->slug,
                "active" => $row->active==1
            );
        }
        return $result;
    }

    public function up_widget($id){
        //me
        $SQL = "SELECT widget_id, `index`, slug FROM ".$this->cms_complete_table_name('main_widget')." WHERE widget_id = $id";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $my_widget_id = $row->widget_id;
        $my_index = $row->index;
        $my_slug = $row->slug;

        //bro
        $SQL = "SELECT widget_id, `index` FROM ".$this->cms_complete_table_name('main_widget')." WHERE
            `index` = (SELECT MAX(`index`) FROM ".$this->cms_complete_table_name('main_widget')." WHERE `index`<$my_index AND slug='$my_slug') AND
            slug = '$my_slug'";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $bro_widget_id = $row->widget_id;
        $bro_index = $row->index;

        if(isset($bro_widget_id)){
            $this->db->update($this->cms_complete_table_name('main_widget'), array("index"=>$my_index), array("widget_id"=>$bro_widget_id));
            $this->db->update($this->cms_complete_table_name('main_widget'), array("index"=>$bro_index), array("widget_id"=>$my_widget_id));
        }

    }

    public function down_widget($id){
        //me
        $SQL = "SELECT widget_id, `index`, slug FROM ".$this->cms_complete_table_name('main_widget')." WHERE widget_id = $id";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $my_widget_id = $row->widget_id;
        $my_index = $row->index;
        $my_slug = $row->slug;

        //bro
        $SQL = "SELECT widget_id, `index` FROM ".$this->cms_complete_table_name('main_widget')." WHERE
            `index` = (SELECT MIN(`index`) FROM ".$this->cms_complete_table_name('main_widget')." WHERE `index`>$my_index AND slug='$my_slug') AND
            slug = '$my_slug'";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $bro_widget_id = $row->widget_id;
        $bro_index = $row->index;

        if(isset($bro_widget_id)){
            $this->db->update('cms_widget', array("index"=>$my_index), array("widget_id"=>$bro_widget_id));
            $this->db->update('cms_widget', array("index"=>$bro_index), array("widget_id"=>$my_widget_id));
        }
    }

    public function toggle_widget($id){
        $SQL = "SELECT widget_id, active FROM ".$this->cms_complete_table_name('main_widget')." WHERE widget_id = $id";
        $query = $this->db->query($SQL);
        $row = $query->row();
        $my_active = $row->active;

        $this->db->update($this->cms_complete_table_name('main_widget'), array("active"=>$my_active==1?0:1), array("widget_id"=>$id));

    }
}
