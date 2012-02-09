<?php

/**
 * Example to look how module and view work
 *
 * @author goFrendiAsgard
 */
class Example extends CMS_Controller{
	/**
	 * This is the data attribute
	 * @var ArrayObject::
	 */
	private $data;
	
	/**
	 * @author goFrendiAsgard
	 * @desc this is the constructor, everything you write here will automatically 
	 */
    public function __construct(){
    	parent::__construct();
    	$this->data = array(
    		"anchors" => array(
    		
    			array(
    				"url"=>'example/index', 
    				"title"=>'index()',
    				"description"=>'$this->view(\'example/example_index\', $this->data, \'example_index\');'.br().
    					'This is the most common call'
    			),
		    	array(
    	    		"url"=>'example/view_1', 
    	    		"title"=>'view_1()',
    	    		"description"=>'$this->view(\'example/example_index\', $this->data);'.br().
    	    			'Navigation_name will be guessed by the system'
		    	),
		    	array(
		    	    "url"=>'example/view_2', 
		    	    "title"=>'view_2()',
		    	    "description"=>'echo $this->view(\'example/example_index\', $this->data, true);'.br().
		    	    	'The result would be a string, that\'s why we use echo'
		    	),
		    	array(
		    	    "url"=>'example/view_3', 
		    	    "title"=>'view_3()',
		    	    "description"=>'$this->view(\'example/example_index\',  $this->data, NULL, NULL, \'orange\');'.br().
		    	    	'Change the theme into orange for only this request'
		    	),
    			array(
    			    "url"=>'example/view_4', 
    			    "title"=>'view_4()',
    			    "description"=>'$this->view(\'example/example_index\', $this->data);'.br().
    			    	'Change the theme into orange and layout into mobile for only this request'
    			)
    			
    		)
    	);
    }    
	
    public function index(){
        $this->view('example/example_index', $this->data, 'example_index');
    }

    public function view_1(){
    	$this->view('example/example_index', $this->data);
    }
    
    public function view_2(){
    	echo $this->view('example/example_index', $this->data, true);
    }
    
    public function view_3(){
    	$this->view('example/example_index', $this->data, NULL, NULL, 'orange');
    }
    
    public function view_4(){
    	$this->view('example/example_index', $this->data, NULL, NULL, 'orange', 'mobile');
    }
}

?>