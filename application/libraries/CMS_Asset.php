<?php defined('BASEPATH') OR exit('No direct script access allowed');
class CMS_Asset{
	private $ci;
	
	private $styles;
	private $scripts;
	
	public function __construct(){
		$this->ci =& get_instance();
		$this->styles = array();
		$this->scripts = array();
	}
	
	public function add_string_js($content){
		$this->scripts[] = array(
				'path'=>NULL,
				'content'=>$content
			);
	}
	
	public function add_string_css($content){
		$this->styles[] = array(
				'path'=>NULL,
				'content'=>$content
		);
	}
	
	public function add_js($path){		
		$this->scripts[] = array(
				'path'=>$path,
				'content'=>''
			);	
	}
	
	public function add_css($path){		
		$this->styles[] = array(
				'path'=>$path,
				'content'=>''
		);
	}
	
	public function add_themes_js($path, $theme, $layout=NULL){
		if(isset($layout)){
			$this->add_js(base_url('themes/'.$theme.'/assets/'.$layout.'/'.$path));
		}else{
			$this->add_js(base_url('themes/'.$theme.'/assets/'.$path));
		}
	}
	
	public function add_themes_css($path, $theme, $layout=NULL){
		if(isset($layout)){
			$this->add_css(base_url('themes/'.$theme.'/assets/'.$layout.'/'.$path));
		}else{
			$this->add_css(base_url('themes/'.$theme.'/assets/'.$path));
		}
	}
	
	public function add_module_js($path, $module){
		$this->add_js(base_url('modules/'.$module.'/assets/'.$path));
	}
	
	public function add_module_css($path, $module){
		$this->add_css(base_url('modules/'.$module.'/assets/'.$path));
	}
	
	public function add_cms_css($path){
		$this->add_css(base_url('assets/'.$path));
	}
		
	public function add_cms_js($path){
		$this->add_js(base_url('assets/'.$path));
	}
	
	private function combine_css($resources, $extension = 'css'){
		$long_name = '';
		foreach($resources as $resource){
			if(isset($resource['path'])){			
				$long_name .= $resource['path'];
			}else{
				$long_name .= $resource['content'];
			}
		}
		$md5_name = md5($long_name);
		$file_name = BASEPATH.'../assets/cache/'.$md5_name.'.'.$extension;
		$file_url = base_url('assets/cache/'.$md5_name.'.'.$extension);
		if(!file_exists($file_name)){
			if(file_exists($file_name)) unlink($file_name);
			foreach($resources as $resource){
				if(isset($resource['path'])){
					$path = $resource['path'];
					$handle = fopen($path, "rb");
					// write content
					while (!feof($handle)) {
						$str = fread($handle, 8192);
						$content = $str;
						file_put_contents($file_name, $content, FILE_APPEND);
					}
					if($content[strlen($content)-1] != ';'){
						file_put_contents($file_name, ';', FILE_APPEND);
					}
					fclose($handle);
					
				}else{
					// write content
					$content = $resource['content'];
					file_put_contents($file_name, $content, FILE_APPEND);
				}
				file_put_contents($file_name, PHP_EOL, FILE_APPEND);
			}			
			
		}
		return $file_url;
	}
	
	private function combine_js($resources, $extension = 'js'){
		$long_name = '';
		foreach($resources as $resource){
			if(isset($resource['path'])){
				$long_name .= $resource['path'];
			}else{
				$long_name .= $resource['content'];
			}
		}
		$md5_name = md5($long_name);
		$file_name = BASEPATH.'../assets/cache/'.$md5_name.'.'.$extension;
		$file_url = base_url('assets/cache/'.$md5_name.'.'.$extension);
		
		if(!file_exists($file_name)){
			$content = '';
			$tail_content = '';
			if(file_exists($file_name)) unlink($file_name);
			$external_js = array();
			foreach($resources as $resource){
				if(isset($resource['path'])){
					$path = $resource['path'];
					$content .= 'head.js("'.$path.'",function(){';
					$tail_content .= '});';					
				}else{
					$content .= $resource['content'];
				}				
			}
			file_put_contents($file_name, $content.$tail_content, FILE_APPEND);
				
		}
		return $file_url;
		
	}
	
	public function compile_css($combine = FALSE){
		if($combine){
			$file_name = $this->combine_css($this->styles,'css');			
			return '<link rel="stylesheet" type="text/css" href="'.$file_name.'" />';
			$this->styles = array();
		}else{
			$return = '';
			foreach($this->styles as $style){
				if(isset($style['path'])){
					$return .= '<link rel="stylesheet" type="text/css" href="'.$style['path'].'" />';
				}else{
					$return .= '<style type="text/css">'.$style['content'].'</style>';
				}
			}
			$this->styles = array();
			return $return;
		}
	}
	
	public function compile_js($combine = FALSE){
		if($combine){
			$file_name = $this->combine_js($this->scripts,'js');
			$head_js_file = base_url('assets/nocms/js/head.min.js');
			$return = '<script type="text/javascript" src="'.$head_js_file.'"></script>';			
			$return .= '<script type="text/javascript" src="'.$file_name.'"></script>';
			$this->scripts = array();
			return $return;			
		}else{
			$return = '';
			foreach($this->scripts as $script){
				if(isset($script['path'])){
					$return .= '<script type="text/javascript" src="'.$script['path'].'"></script>';
				}else{
					$return .= '<script type="text/javascript"><!-- '.$script['content'].' --></script>';
				}
			}
			$this->scripts = array();
			return $return;
		}
	}
}

?>