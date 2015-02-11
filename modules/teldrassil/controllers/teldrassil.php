<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for
 *
 * @author No-CMS Module Generator
 */
class teldrassil extends CMS_Priv_Strict_Controller {

    protected function do_override_url_map($URL_MAP){
        $module_path = $this->cms_module_path();
        $navigation_name = $this->cms_complete_navigation_name('index');
        $URL_MAP[$module_path.'/'.$module_path] = $navigation_name;
        $URL_MAP[$module_path] = $navigation_name;
        return $URL_MAP;
    }

    public function index(){
        $module_path = $this->cms_module_path();   
        $theme_name = NULL;
        $font = NULL;     
        $file_name = NULL;  // uploaded theme file
        $url_name = NULL;   // url of uploaded theme file
        $color_options = array();   // color options, extracted from uploaded theme file
        $colors = array('', '', '', '', '', '', '');    // colors
        // set file_name
        if(isset($_FILES['file_name']) && $_FILES['file_name']['name'] != ''){
            $file_name = FCPATH.'modules/'.$module_path.'/assets/uploads/'.substr(md5(rand()),5).'_'.$_FILES['file_name']['name'];
            $url_name = base_url('modules/'.$module_path.'/assets/uploads/'.$_FILES['file_name']['name']);
            move_uploaded_file($_FILES['file_name']['tmp_name'], $file_name);
            $this->session->set_userdata('teldrassil_theme_file', $file_name);
            $this->session->set_userdata('teldrassil_theme_url', $url_name);
        }else if($this->session->userdata('teldrassil_theme_file') != NULL && 
        file_exists($this->session->userdata('teldrassil_theme_file'))){
            $file_name = $this->session->userdata('teldrassil_theme_file');
            $url_name = $this->session->userdata('teldrassil_theme_url');
        }
        if($file_name != NULL){
            $this->load->helper($module_path.'/image');
            $color_options = find_dominant_color($file_name, 20);
            for($i=0; $i<7; $i++){
                if(count($color_options)>$i){
                    $colors[$i] = $color_options[$i];
                }
            }
        }

        if(!isset($_FILES['file_name']) || $_FILES['file_name']['name'] == ''){
            // get theme_name, css, colors, and font from post
            $theme_name = $this->input->post('theme_name');
            if($this->input->post('colors')){
                $colors     = $this->input->post('colors');
            }
            $font       = $this->input->post('font');
        }

        if($this->input->post('generate') == 'generate'){
            if($theme_name == NULL){
                $theme_name = substr(md5(rand()), 0, 4).date('Ymd');                
            }
            if(file_exists(FCPATH.'themes/'.$theme_name)){
                $theme_name .= substr(md5(rand()), 0, 4).date('Ymd');
            }
            $this->directory_copy(FCPATH.'modules/'.$module_path.'/assets/theme_template', FCPATH.'themes/'.$theme_name);
            $preview_name = FCPATH.'themes/'.$theme_name.'/preview.png';
            copy($file_name, $preview_name);
            $this->load->library('image_moo');
            $this->image_moo->load($preview_name)->resize(769,395, TRUE)->save($preview_name, TRUE);
            file_put_contents(FCPATH.'themes/'.$theme_name.'/assets/default/bootstrap.min.css', $this->get_css($font, $colors));
        }

        // pass the data
        $data = array(
                'file_name' => $file_name,
                'theme_name' => $theme_name,
                'url_name' => $url_name,
                'colors' => $colors,
                'font' => $font,
                'color_options' => $color_options,
                'color_descriptions' => array(
                        'body background',
                        'disabled input & button background, input addon background, nav & tabs & pagination link hover background, jumbotron background',
                        'disabled link color, input placeholder color, dropdown header color, navbar inverse & link color',
                        'nav tab link hover color',
                        'link color, primary button background, pagination active background, progress bar background, label background, panel heading color',
                        'text color, legend color, dropdown link color, panel text color, code color',
                        'navbar background',
                    ),
                'font_options' => array(
                        'Open Sans', 'Tangerine', 'Lobster', 'Inconsolata', 'Droid Sans', 'Lato',
                        'News Cycle', 'Source Sans', 'Roboto', 'Raleway', 'Josefin Sans', 
                    ),
            );
        $this->view($this->cms_module_path().'/teldrassil_index', $data,
            $this->cms_complete_navigation_name('index'));
    }

    private function directory_copy($srcdir, $dstdir){
        $this->load->helper('directory');
        //preparing the paths
        $srcdir=rtrim($srcdir,'/');
        $dstdir=rtrim($dstdir,'/');

        //creating the destination directory
        if(!is_dir($dstdir))mkdir($dstdir, 0777, true);

        //Mapping the directory
        $dir_map=directory_map($srcdir);

        foreach($dir_map as $object_key=>$object_value)
        {
            if(is_numeric($object_key))
                copy($srcdir.'/'.$object_value,$dstdir.'/'.$object_value);//This is a File not a directory
            else
                $this->directory_copy($srcdir.'/'.$object_key,$dstdir.'/'.$object_key);//this is a directory
        }
    }

    private function get_css($font, $colors){
        $module_path = $this->cms_module_path();
        $css = file_get_contents(FCPATH.'modules/'.$module_path.'/assets/theme_template.css');
        $css = str_replace(
                array(
                    '{{ FONT }}', '{{ COLOR_1 }}', '{{ COLOR_2 }}', '{{ COLOR_3 }}',
                    '{{ COLOR_4 }}', '{{ COLOR_5 }}', '{{ COLOR_6 }}', '{{ COLOR_7 }}', '{{ FONT+ }}',
                ), 
                array(
                    $font, '#'.$colors[0], '#'.$colors[1], '#'.$colors[2], '#'.$colors[3], 
                    '#'.$colors[4], '#'.$colors[5], '#'.$colors[6], implode('+', explode(' ', $font)),
                ), 
                $css
            );
        log_message('error', $css);
        return $css;
    }

    public function preview(){
        $module_path = $this->cms_module_path();
        $this->config->set_item('minify_output', FALSE);
        $font = $this->input->get('font');
        $colors = $this->input->get('colors');
        $css = $this->get_css($font, $colors);
        $data = array(
                'css' => $css,
                'module_base_url' => base_url('modules/'.$module_path).'/',
            );
        $this->load->view($this->cms_module_path().'/teldrassil_preview', $data);
    }
}