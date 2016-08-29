<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for
 *
 * @author No-CMS Module Generator
 */
class Teldrassil extends CMS_Secure_Controller {

    protected function do_override_url_map($URL_MAP){
        $module_path = $this->cms_module_path();
        $navigation_name = $this->n('index');
        $URL_MAP[$module_path.'/'.$module_path] = $navigation_name;
        $URL_MAP[$module_path] = $navigation_name;
        return $URL_MAP;
    }

    private function get_data_from_post($key){
        if($this->input->post($key) !== NULL){
            $value = $this->input->post($key);
            $this->session->set_userdata('teldrassil_'.$key, $value);
            return $value;
        }else{
            return $this->session->userdata('teldrassil_'.$key);
        }
    }

    public function index(){
        $module_path = $this->cms_module_path();
        $theme_name = NULL;
        $font = NULL;
        $file_name = NULL;  // uploaded theme file
        $url_name = NULL;   // url of uploaded theme file
        $color_options = array();   // color options, extracted from uploaded theme file
        $background_image = FALSE;
        $colors = array('', '', '', '', '', '', '');    // colors
        // set file_name
        if(isset($_FILES['file_name']) && $_FILES['file_name']['name'] != '' && file_exists($_FILES['file_name']['tmp_name'])){
            // delete previous file
            if(file_exists($this->session->userdata('teldrassil_theme_file'))){
                unlink($this->session->userdata('teldrassil_theme_file'));
            }
            // default theme name
            if($theme_name == ''){
                $theme_name = explode('.',$_FILES['file_name']['name'])[0];
            }
            $rand = substr(md5(rand()),5);
            $file_name = FCPATH.'modules/'.$module_path.'/assets/uploads/'.$rand.'_'.$_FILES['file_name']['name'];
            $url_name = base_url('modules/'.$module_path.'/assets/uploads/'.$rand.'_'.$_FILES['file_name']['name']);
            move_uploaded_file($_FILES['file_name']['tmp_name'], $file_name);
            @chmod($file_name, 644);
            $this->load->helper($module_path.'/image');
            try{
                $color_options = find_dominant_color($file_name, 20);
                // add default color
                $color_default = ['00', '40', '80', 'C0', 'FF'];
                foreach($color_default as $r){
                    foreach($color_default as $g){
                        foreach($color_default as $b){
                            $complete_color =$r.$g.$b.'';
                            if(!in_array($complete_color, $color_options)){
                                $color_options[] = $complete_color;
                            }
                        }
                    }
                }
                $this->session->set_userdata('teldrassil_theme_file', $file_name);
                $this->session->set_userdata('teldrassil_theme_url', $url_name);
                $this->session->set_userdata('teldrassil_color_options', $color_options);
                for($i=0; $i<7; $i++){
                    if(count($color_options)>$i){
                        $colors[$i] = $color_options[$i];
                    }
                }
                $this->session->set_userdata('teldrassil_colors', $colors);
            }catch(Exception $e){
                // do nothing
                $file_name = '';
                $theme_name = '';
                $url_name = '';
            }
        }else if($this->session->userdata('teldrassil_theme_file') != NULL &&
        file_exists($this->session->userdata('teldrassil_theme_file'))){
            $file_name = $this->session->userdata('teldrassil_theme_file');
            $url_name = $this->session->userdata('teldrassil_theme_url');
        }

        if(!isset($_FILES['file_name']) || $_FILES['file_name']['name'] == ''){
            // get theme_name, css, colors, and font from post
            $color_options = $this->get_data_from_post('color_options');
            $theme_name = $this->get_data_from_post('theme_name');
            $colors = $this->get_data_from_post('colors');
            $font = $this->get_data_from_post('font');
            $background_image = $this->get_data_from_post('background_image')==TRUE;
        }

        if($this->input->post('generate') == 'generate'){
            if($theme_name == NULL){
                $theme_name = substr(md5(rand()), 0, 4).date('Ymd');
            }
            if(file_exists(FCPATH.'themes/'.$theme_name)){
                $theme_name .= substr(md5(rand()), 0, 4).date('Ymd');
            }
            $this->load->helper('inflector');
            $theme_name = underscore($theme_name);
            $this->directory_copy(FCPATH.'modules/'.$module_path.'/assets/theme_template', FCPATH.'themes/'.$theme_name);
            // copy images
            $preview_name = FCPATH.'themes/'.$theme_name.'/preview.png';
            copy($file_name, $preview_name);
            $this->cms_resize_image($preview_name, 769, 395);
            file_put_contents(FCPATH.'themes/'.$theme_name.'/assets/css/bootstrap.min.css',
                $this->get_css($font, $colors));
            // create description file
            $description = json_encode(array('public' => FALSE, 'description' => 'A theme generated by teldrassil'));
            file_put_contents(FCPATH.'themes/'.$theme_name.'/description.txt', $description);
            unlink($file_name);
        }

        // pass the data
        $data = array(
                'generated' => $this->input->post('generate') == 'generate',
                'file_name' => $file_name,
                'theme_name' => $theme_name,
                'url_name' => $url_name,
                'colors' => $colors,
                'background_image'=>$background_image,
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
                        'News Cycle', 'Source Sans', 'Roboto', 'Raleway', 'Josefin Sans', 'Josefin Slab',
                        'Arvo', 'Vollkorn', 'Abril Fatface', 'Ubuntu', 'PT Sans', 'PT Serif', 'Stalemate',
                    ),
            );
        $this->view($this->cms_module_path().'/teldrassil_index', $data,
            $this->n('index'));
    }

    private function directory_copy($srcdir, $dstdir, $mode = 0755){
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
            if(is_numeric($object_key)){
                copy($srcdir.'/'.$object_value, $dstdir.'/'.$object_value);//This is a File not a directory
                chmod($dstdir.'/'.$object_value, $mode);
            }
            else{
                $this->directory_copy($srcdir.'/'.$object_key, $dstdir.'/'.$object_key);//this is a directory
                chmod($dstdir.'/'.$object_key, $mode);
            }
        }

    }

    private function get_css($font, $colors){
        $module_path = $this->cms_module_path();
        $background_color = '#__background-color{'.PHP_EOL.
            '    background-color: #'.$colors[0].';'.PHP_EOL.
            '    z-index: -99998;'.PHP_EOL.
            '    width: 100%;'.PHP_EOL.
            '    height: 100%;'.PHP_EOL.
            '    position: fixed;'.PHP_EOL.
            '    top:0px;'.PHP_EOL.
            '}';

        $css = file_get_contents(FCPATH.'modules/'.$module_path.'/assets/theme_template.css');
        $css = str_replace(
                array(
                    '{{ FONT }}', '{{ COLOR_1 }}', '{{ COLOR_2 }}', '{{ COLOR_3 }}',
                    '{{ COLOR_4 }}', '{{ COLOR_5 }}', '{{ COLOR_6 }}', '{{ COLOR_7 }}', '{{ FONT+ }}',
                    '{{ BACKGROUND_COLOR }}'
                ),
                array(
                    $font, '#'.$colors[0], '#'.$colors[1], '#'.$colors[2], '#'.$colors[3],
                    '#'.$colors[4], '#'.$colors[5], '#'.$colors[6], implode('+', explode(' ', $font)),
                    $background_color,
                ),
                $css
            );
        return $css;
    }

    public function preview(){
        $module_path = $this->cms_module_path();
        $this->config->set_item('minify_output', FALSE);
        $font = $this->input->get('font');
        $colors = $this->input->get('colors');
        $background_image = $this->input->get('use_background_image') == 'TRUE';
        if($background_image){
            $background_image = $this->session->userdata('teldrassil_theme_url');
        }else{
            $background_image = NULL;
        }
        $css = $this->get_css($font, $colors, $background_image);
        $data = array(
                'css' => $css,
                'module_base_url' => base_url('modules/'.$module_path).'/',
            );
        $this->load->view($this->cms_module_path().'/teldrassil_preview', $data);
    }
}
