<?php
class Property{
    private $id;
    private $data;
    private $path;
    
    public function __construct($id=NULL, $path=NULL){
        $this->id = isset($id)?$id:str_replace(' ','_',microtime());
        $this->path = isset($path)?$path:'property';
    }
    
    public function get_id(){
        return $this->id;
    }
    
    private function _filename(){
        if(defined('BASEPATH')){
            return BASEPATH.'../modules/aiko/data/'.$this->path.'/'.$this->id;
        }else{
            return '../../data/'.$this->path.'/'.$this->id;
        }
    }
    
    public function save(){
        $filename = $this->_filename();
        return file_put_contents($filename, json_encode($this->data));
    }
    
    public function load(){
        $filename = $this->_filename();
        $this->data = json_decode(file_get_contents($filename),true);
        return $this->data;
    }
    
    public function set($key, $value = NULL){        
        if(isset($value)){
            //both are array
            if(is_array($key) && is_array($value) && count($key)==count($value)){
                for($i=0; $i<count($key); $i++){
                    $this->data[$key[$i]] = $value[$i];
                }
            }
            //first parameter is string
            else if(is_string($key)){
                $this->data[$key] = $value;
            }
            //error
            else{
                throw new Exception('Incompatible key and value');
            }
        }else{
            $this->data = $key;
        }
    }
    
    public function get($key = NULL){
        if(isset($key)){
            if(is_string($key)){
                return $this->data[$key];
            }else if(is_array($key)){
                $data = array();
                for($i=0; $i<count($key); $i++){
                    $data[$key]=$this->data[$key];
                }
            }else{
                return NULL;
            }
                        
        }else{
            return $this->data;
        }
    }
    
}
?>
