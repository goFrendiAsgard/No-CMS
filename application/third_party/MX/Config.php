<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Modular Extensions - HMVC
 *
 * Adapted from the CodeIgniter Core Classes
 * @link        http://codeigniter.com
 *
 * Description:
 * This library extends the CodeIgniter CI_Config class
 * and adds features allowing use of modules and the HMVC design pattern.
 *
 * Install this file as application/third_party/MX/Config.php
 *
 * @copyright   Copyright (c) 2011 Wiredesignz
 * @version     5.4
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 **/
class MX_Config extends CI_Config 
{
    public function load($file = 'config', $use_sections = FALSE, $fail_gracefully = FALSE, $_module = '') {

        if (in_array($file, $this->is_loaded, TRUE)) return $this->item($file);

        $_module OR $_module = CI::$APP->router->fetch_module();
        list($path, $file) = Modules::find($file, $_module, 'config/');

        if ($path === FALSE) {
            parent::load($file, $use_sections, $fail_gracefully);
            return $this->item($file);
        }

        if ($config = Modules::load_file($file, $path, 'config')) {

            /* reference to the config array */
            $current_config =& $this->config;

            if ($use_sections === TRUE) {

                if (isset($current_config[$file])) {
                    $current_config[$file] = array_merge($current_config[$file], $config);
                } else {
                    $current_config[$file] = $config;
                }

            } else {
                $current_config = array_merge($current_config, $config);
            }
            $this->is_loaded[] = $file;
            unset($config);
            return $this->item($file);
        }
    }

    // Added by Go Frendi Gunawan, 20-DEC-2013 to override site_url 

    /**
     * Site URL
     *
     * Returns base_url . index_page [. uri_string]
     *
     * @uses    CI_Config::_uri_string()
     *
     * @param   string|string[] $uri    URI string or an array of segments
     * @param   string  $protocol
     * @return  string
     */
    public function site_url($uri = '', $protocol = NULL)
    {
        $base_url = $this->slash_item('base_url');

        $index_page = $this->item('index_page');
        if(CMS_SUBSITE !== '' && !USE_SUBDOMAIN){
            if($index_page == ''){
                $index_page = 'site-'.CMS_SUBSITE;
            }else{
                $index_page = $this->slash_item('index_page').CMS_SUBSITE;
            }
        }

        if (isset($protocol))
        {
            $base_url = $protocol.substr($base_url, strpos($base_url, '://'));
        }

        if (empty($uri))
        {
            return $base_url.$index_page;
            //return $base_url.$this->item('index_page');
        }

        $uri = $this->_uri_string($uri);

        if ($this->item('enable_query_strings') === FALSE)
        {
            $suffix = isset($this->config['url_suffix']) ? $this->config['url_suffix'] : '';

            if ($suffix !== '')
            {
                if (($offset = strpos($uri, '?')) !== FALSE)
                {
                    $uri = substr($uri, 0, $offset).$suffix.substr($uri, $offset);
                }
                else
                {
                    $uri .= $suffix;
                }
            }

            //return $base_url.$this->slash_item('index_page').$uri;
            if($index_page != '' && substr($index_page, -1) != '/'){
                $index_page .= '/';
            }
            return $base_url.$index_page.$uri;
        }
        elseif (strpos($uri, '?') === FALSE)
        {
            $uri = '?'.$uri;
        }

        //return $base_url.$this->item('index_page').$uri;
        return $base_url.$index_page.$uri;
    }
}
