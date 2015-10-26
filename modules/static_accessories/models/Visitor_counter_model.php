<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Visitor_counter_model extends CMS_Model{
    public function get(){
        $already_counted = $this->session->userdata('counted')===TRUE;
        if(!$already_counted){
            $this->session->set_userdata('counted', TRUE);
            $this->load->library('user_agent');
            $this->load->helper('date');
            $this->db->insert($this->t('visitor_counter'), array(
                    'ip'=>$this->input->ip_address(),
                    'time'=>date('Y-m-d H:i:s'),
                    'agent'=>$this->agent->agent_string()
                ));
        }
        $query = $this->db->select('counter_id')
            ->from($this->t('visitor_counter'))
            ->get();
        return $query->num_rows();
    }
}