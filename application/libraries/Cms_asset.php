<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cms_asset
{
    private $ci;

    private $styles;
    private $scripts;

    private $skipped_resources;

    public function __construct()
    {
        $this->ci =& get_instance();
        $this->styles  = array();
        $this->scripts = array();
        $this->skipped_resources = array(base_url('assets/grocery_crud/texteditor/ckeditor/ckeditor.js'));
    }

    public function add_skipped_resource($path){
        $this->skipped_resources[] = $this->parse_path($path);
    }

    public function set_skipped_resource($path_array){
        $this->skipped_resources = array();
        foreach($path_array as $path){
            $this->add_skipped_resource($path);
        }
    }

    public function add_internal_js($content)
    {
        $this->scripts[] = array(
            'path' => NULL,
            'content' => $content
        );
    }

    public function add_internal_css($content)
    {
        $this->styles[] = array(
            'path' => NULL,
            'content' => $content
        );
    }

    public function add_js($path)
    {
        $this->scripts[] = array(
            'path' => $path,
            'content' => ''
        );
    }

    public function add_css($path)
    {
        $this->styles[] = array(
            'path' => $path,
            'content' => ''
        );
    }

    public function add_themes_js($path, $theme)
    {
        $this->add_js(base_url('themes/' . $theme . '/assets/' . $path));
    }

    public function add_themes_css($path, $theme)
    {
        $this->add_css(base_url('themes/' . $theme . '/assets/' . $path));
    }

    public function add_module_js($path, $module)
    {
        $this->add_js(base_url('modules/' . $module . '/assets/' . $path));
    }

    public function add_module_css($path, $module)
    {
        $this->add_css(base_url('modules/' . $module . '/assets/' . $path));
    }

    public function add_cms_css($path)
    {
        $this->add_css(base_url('assets/' . $path));
    }

    public function add_cms_js($path)
    {
        $this->add_js(base_url('assets/' . $path));
    }

    private function parse_path($path){
        $this->ci->load->model('no_cms_model');
        $used_theme = $this->ci->session->userdata('__cms_used_theme');
        $path = $this->ci->no_cms_model->cms_parse_keyword($path);
        return str_ireplace('{{ used_theme }}', $used_theme, $path);
    }

    public function minify($content, $mode='css'){
        // trim every line
        $content = explode(PHP_EOL, $content);
        $new_content = '';
        // variables for html
        $in_pre = FALSE;
        $in_text_area = FALSE;
        foreach($content as $line){
            if($mode == 'html'){
                // check pre and textarea status
                if(stripos($line, '<pre') !== FALSE){
                    $in_pre = TRUE;
                }else if(stripos($line, '</pre>') !== FALSE){
                    $in_pre = FALSE;
                }else if(stripos($line, '<textarea') !== FALSE){
                    $in_text_area = TRUE;
                }else if(stripos($line, '</textarea>') !== FALSE){
                    $in_text_area = FALSE;
                }
            }
            // don't trim if it is in pre in html mode
            if($mode == 'html' && ($in_pre || $in_text_area)){
                $new_content .= $line;
            }else{
                $new_content .= trim($line);
            }
            if($mode == 'js' || $mode == 'html' || stripos($line, '//') !== FALSE){
                $new_content .= PHP_EOL;
            }
        }
        return $new_content;
    }

    // mode can be css or js
    // type : internal, external, cdn
    private function combine($resources, $mode='css'){
        // get all resource
        $compiled_resources = array();
        $base_url = base_url();
        foreach ($resources as $resource) {
            $last_index = count($compiled_resources)-1;
            if (isset($resource['path']) && $resource['path'] !== NULL && trim($resource['path']) !== '') {
                $path = $this->parse_path($resource['path']);
                $path_part = explode('/', $path);
                $file_name = $path_part[count($path_part)-1];
                $dir_path  = substr($path, 0, strlen($path)-strlen($file_name));
                /*
                if(in_array($dir_path.$file_name, $this->skipped_resources)){
                    $type = 'skipped';
                    $dir_path = str_ireplace($base_url, FCPATH, $dir_path);
                    if(file_exists($dir_path.$file_name)){
                        $last_modified_date = date('YmdHis',filemtime($dir_path.$file_name));
                    }else{
                        $last_modified_date = 0;
                    }
                }else if(strpos($dir_path, $base_url) === 0){
                    $type = 'external';
                    $dir_path = str_ireplace($base_url, FCPATH, $dir_path);
                    if(file_exists($dir_path.$file_name)){
                        $last_modified_date = date('YmdHis',filemtime($dir_path.$file_name));
                    }else{
                        $last_modified_date = 0;
                    }
                }else{
                    $type = 'cdn';
                    $last_modified_date = 0;
                }*/
                $type = 'cdn';
                $last_modified_date = 0;

                if(count($compiled_resources)>0 && $compiled_resources[$last_index]['type'] == $type && $compiled_resources[$last_index]['dir_path'] == $dir_path){
                    $compiled_resources[$last_index]['file_name'][] = $file_name;
                    if($last_modified_date > $compiled_resources[$last_index]['modified_time']){
                        $compiled_resources[$last_index]['modified_time'] = $last_modified_date;
                    }
                }else{
                    $compiled_resources[]=array(
                        'file_name' => array($file_name),
                        'dir_path' => $dir_path,
                        'type' => $type,
                        'modified_time' => $last_modified_date
                    );
                }
            } else {
                $content = $resource['content'];
                $content = $this->minify($content, $mode);
                if(count($compiled_resources)>0 && $compiled_resources[$last_index]['type'] == 'internal'){
                    $compiled_resources[$last_index]['content'].= PHP_EOL.$content;
                }else{
                    $compiled_resources[] = array(
                            'type' => 'internal',
                            'content' => $content
                        );
                }
            }
        }

        // make string to represent resources
        $real_base_url = $base_url;
        if(USE_SUBDOMAIN && CMS_SUBSITE != '' && !USE_ALIAS){
            $real_base_url = str_ireplace('://'.CMS_SUBSITE.'.',  '://', $real_base_url);
        }
        $str = '';
        foreach($compiled_resources as $compiled_resource){
            if($compiled_resource['type'] == 'internal'){
                if($mode == 'js'){
                    $str .= '<script type="text/javascript">'.$compiled_resource['content'].'</script>';
                }else{
                    $str .= '<style type="text/css">'.$compiled_resource['content'].'</style>';
                }
            }else if($compiled_resource['type'] == 'cdn' || $compiled_resource['type'] == 'skipped'){
                foreach($compiled_resource['file_name'] as $file_name){
                    $dir_path = $compiled_resource['dir_path'];
                    if($compiled_resource['type'] = 'skipped'){
                        $dir_path = str_ireplace(FCPATH, $real_base_url, $dir_path);
                    }
                    if($mode == 'js'){
                        $str .= '<script type="text/javascript" src="'.$dir_path.$file_name.'"></script>';
                    }else{
                        $str .= '<link rel="stylesheet" type="text/css" href="'.$dir_path.$file_name.'" />';
                    }
                }
            }else{
                $dir_path = $compiled_resource['dir_path'];
                $compiled_file_name = md5(implode('|', $compiled_resource['file_name']));
                if($mode == 'js'){
                    $compiled_file_name = '_cache_'.$compiled_file_name.'.js';
                }else{
                    $compiled_file_name = '_cache_'.$compiled_file_name.'.css';
                }
                // cache is old or doesn't exists, create a new one
                if(!file_exists($dir_path.$compiled_file_name) || (file_exists($dir_path.$compiled_file_name) && $compiled_resource['modified_time'] > date('YmdHis',filemtime($dir_path.$compiled_file_name)))){
                    $content = array();
                    foreach($compiled_resource['file_name'] as $file_name){
                        $content[] = $this->minify(file_get_contents($dir_path.$file_name), $mode);
                    }
                    $content = implode(PHP_EOL, $content);
                    file_put_contents($dir_path.$compiled_file_name, $content);
                }
                // read the file
                $into_internal = FALSE;
                if(filesize($dir_path.$compiled_file_name) < 1024){
                    $content = file_get_contents($dir_path.$compiled_file_name);
                    if(strpos($content, '@import') === FALSE){
                        if($mode == 'js'){
                            $str .= '<script type="text/javascript">' . $content . '</script>';
                        }else{
                            $str .= '<style type="text/css">' . $content . '</style>';
                        }
                        $into_internal = TRUE;
                    }
                }
                // if more than 1024 byte
                if(!$into_internal){
                    // change fcpath
                    $dir_path = str_ireplace(FCPATH, $real_base_url, $dir_path);
                    if($mode == 'js'){
                        $str .= '<script type="text/javascript" src="'.$dir_path.$compiled_file_name.'"></script>';
                    }else{
                        $str .= '<link rel="stylesheet" type="text/css" href="'.$dir_path.$compiled_file_name.'" />';
                    }
                }
            }
        }
        return $str;
    }

    public function compile_css($combine = TRUE)
    {
        if ($combine) {
            $return = $this->combine($this->styles, 'css');
            $this->styles = array();
        } else {
            $return = '';
            foreach ($this->styles as $style) {
                if (isset($style['path'])) {
                    $return .= '<link rel="stylesheet" type="text/css" href="' . $style['path'] . '" />';
                } else {
                    $return .= '<style type="text/css">' . $style['content'] . '</style>';
                }
            }
            $this->styles = array();
        }
        return $return;
    }

    public function compile_js($combine = TRUE)
    {
        if ($combine) {
            $return = $this->combine($this->scripts, 'js');
            $this->scripts = array();
        } else {
            $return = '';
            foreach ($this->scripts as $script) {
                if (isset($script['path'])) {
                    $return .= '<script type="text/javascript" src="' . $script['path'] . '"></script>';
                } else {
                    $return .= '<script type="text/javascript">' . $script['content'] . '</script>';
                }
            }
            $this->scripts = array();
        }
        return $return;
    }
}
