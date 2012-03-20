<?php

/**
 * Description of ai_core
 *
 * @author gofrendi
 */
class AI_Core extends CMS_Model{
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
    
    protected function core_identifier($identifier=NULL){
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
        
        
        file_put_contents('modules/artificial_intelligence/assets/data/'.$this->core_identifier, 
                json_encode($this->core_property));
        
    }
    
    public function core_getProperty($key=NULL){
        
        if(!file_exists('modules/artificial_intelligence/assets/data/'.$this->core_identifier)){
            file_put_contents('modules/artificial_intelligence/assets/data/'.$this->core_identifier, '');
        }
        
        $content = file_get_contents('modules/artificial_intelligence/assets/data/'.$this->core_identifier);
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
    
}

?>
