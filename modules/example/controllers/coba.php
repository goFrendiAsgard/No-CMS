<?php

/**
 * Example to look how module and view work
 *
 * @author goFrendiAsgard
 */
class Coba extends CMS_Controller{
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
    				"url"=>$this->cms_module_path('gofrendi.noCMS.example').'/index', 
    				"title"=>'index()',
    				"description"=>'$this->view(\'example/example_index\', $this->data, \'example_index\');'.br().
    					'This is the most common call'
    			),
		    	array(
    	    		"url"=>$this->cms_module_path('gofrendi.noCMS.example').'/view_1', 
    	    		"title"=>'view_1()',
    	    		"description"=>'$this->view(\'example/example_index\', $this->data);'.br().
    	    			'Navigation_name will be guessed by the system'
		    	),
		    	array(
		    	    "url"=>$this->cms_module_path('gofrendi.noCMS.example').'/view_2', 
		    	    "title"=>'view_2()',
		    	    "description"=>'echo $this->view(\'example/example_index\', $this->data, true);'.br().
		    	    	'The result would be a string, that\'s why we use echo'
		    	),
		    	array(
		    	    "url"=>$this->cms_module_path('gofrendi.noCMS.example').'/view_3', 
		    	    "title"=>'view_3()',
		    	    "description"=>'$this->view(\'example/example_index\',  $this->data, NULL, NULL, \'orange\');'.br().
		    	    	'Change the theme into orange for only this request'
		    	),
    			array(
    			    "url"=>$this->cms_module_path('gofrendi.noCMS.example').'/view_4', 
    			    "title"=>'view_4()',
    			    "description"=>'$this->view(\'example/example_index\', $this->data, NULL, NULL, \'neutral\', \'mobile\');'.br().
    			    	'Change the theme into neutral and layout into mobile for only this request'
    			)
    			
    		)
    	);
    }    
	
    public function index(){
        $this->view($this->cms_module_path('gofrendi.noCMS.example').'/example_index', $this->data, 'example_index');
    }

    public function view_1(){
    	$this->view($this->cms_module_path('gofrendi.noCMS.example').'/example_index', $this->data);
    }
    
    public function view_2(){
    	echo $this->view($this->cms_module_path('gofrendi.noCMS.example').'/example_index', $this->data, true);
    }
    
    public function view_3(){
    	$this->view($this->cms_module_path('gofrendi.noCMS.example').'/example_index', $this->data, NULL, NULL, 'orange');
    }
    
    public function view_4(){
    	$this->view($this->cms_module_path('gofrendi.noCMS.example').'/example_index', $this->data, NULL, NULL, 'neutral', 'mobile');
    }
}

?>
