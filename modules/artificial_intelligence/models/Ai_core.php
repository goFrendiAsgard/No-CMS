<?php

/**
 * Description of ai_core
 *
 * @author gofrendi
 */
class Ai_core extends CMS_Model{
    private $core_identifier;    //identifier, should be different for each intance
    private $core_property;    //all property should be putted here
    
    public function __construct(){
        parent::__construct();
    }
    
    //Call this on every controller after load model
    public function core_initialize($identifier){
        $this->core_identifier($identifier);
        $this->core_property = $this->core_getProperty();
    }
    
    public function core_identifier($identifier=NULL){
        if(isset($identifier)){
            $this->core_identifier = $identifier;
        }else{
            return $this->core_identifier;
        }
    }
    
    public function core_saveProperty($key=NULL, $value=NULL){       
               
        $this->core_property = $this->core_getProperty();
        if(isset($key)){
            if(count($key)>0){
                for($i=0; $i<count($key); $i++){
                    $this->core_property[$key[$i]] = $value[$i];
                }
            }else{
                $this->core_property[$key] = $value;
            }
            
        }
        
        
        file_put_contents($this->core_config_filename(), 
                json_encode($this->core_property));
        
    }
    
    public function core_getProperty($key=NULL){
        
        if(!$this->core_exists()){
            file_put_contents($this->core_config_filename(), '');
        }
        
        $content = file_get_contents($this->core_config_filename());
        $content = json_decode($content, true);
        
        if($content){
            if(isset($key)){
                $result = $content[$key];
            }else{
                $result = $content;
            }
        }else{
            $result = NULL;
        }
        
        return $result;
        
    }
    
    public function core_exists(){
        return file_exists($this->core_config_filename());
    }
    
    private function core_config_filename(){
        return 'modules/'.$this->cms_module_path().'/assets/data/'.$this->cms_user_id().'_'.$this->core_identifier;
    }
    
}
