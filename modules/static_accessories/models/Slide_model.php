<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Slide_model extends CMS_Model{
    public function get($slug = NULL){
        if($slug != NULL){
            $this->db->like('slug', $slug);
        }else{
            $this->db->where('slug', '');
            $this->db->or_where('slug', NULL);
        }
        $query = $this->db->select('slide_id, image_url, content')
            ->from($this->t('slide'))
            ->get();
        return $query->result_array();
    }

    public function get_slug(){
        $slug_list = array();
        $query = $this->db->select('slug')
            ->from($this->t('slide'))
            ->get();
        foreach($query->result() as $row){
            $slugs = explode(',', $row->slug);
            foreach($slugs as $slug){
                $slug = strtolower(trim($slug));
                if(!in_array($slug, $slug_list) && $slug != ''){
                    $slug_list[] = $slug;
                }
            }
        }
        return $slug_list;
    }

    public function adjust_widget(){
        foreach($this->get_slug() as $slug){
            $url = $this->cms_module_path().'/static_accessories_widget/slide/'.$slug;
            $widget_name = $this->n('slideshow_'.$slug);
            $this->cms_add_widget_if_not_exists($widget_name, 'Slide '.$slug, 1, $url, NULL, NULL, NULL);
        }
    }
}
