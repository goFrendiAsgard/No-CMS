<?php 
class widget extends CMS_Controller {
	public function slideshow (){
		$this->db->select('url')
		->from ($this->cms_complete_table_name('slideshow'));
		$query=$this->db->get();
		$result=$query->result_array();
		$data['result']=$result;
		$this->load->view('front_widget/widget_slideshow',$data);
		
	}
	
	 public function tab(){	
		$this->db->select('content,tittle')	
		->from ($this->cms_complete_table_name('tab'));
		$query=$this->db->get();
		$result=$query->result_array();
		$data['result']=$result;
		$this->load->view('front_widget/widget_tab',$data);
	}
}


?>