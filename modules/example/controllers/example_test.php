<?php
class Example_Test extends CMS_Controller{

	// test load library
	public function test_library(){
		$this->load->library('example/example_library');
		echo $this->example_library->hello();
	}

	// test load helper
	public function test_helper(){
		$this->load->helper('example/example');
		echo hello();
	}
}
?>