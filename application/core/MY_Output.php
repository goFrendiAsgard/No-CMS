<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Output extends CI_Output{
    // Added by gofrendi in order to make a better profiler
    public $cms_profile = '';
    public $cms_profiler_enabled = FALSE;
    public $cms_data = NULL;

    // Added by gofrendi in order to make a better profiler
    public function __construct(){
        parent::__construct();
        // old-version-PHP doesn't allow us to directly assign array() as property's default value
        if($this->cms_data === NULL){
            $this->cms_data = array();
        }
    }

    // Added by gofrendi in order to make a better profiler
    public function enable_cms_profiler($val = TRUE)
	{
		$this->cms_profiler_enabled = is_bool($val) ? $val : TRUE;
		return $this;
	}

    // Added by gofrendi in order to make a better profiler
    public function set_cms_data($data){
        $this->cms_data = $data;
    }

    public function build_cms_profile(){
        $CI =& get_instance();

        // start
        $sql_keywords = array('SELECT', 'DISTINCT', 'FROM', 'WHERE', 'AND', 'LEFT JOIN', 'JOIN' , 'ORDER BY', 'GROUP BY', 'LIMIT', 'INSERT', 'INTO', 'VALUES', 'UPDATE', ' OR ', ' OR<br />', '<br />OR ', 'HAVING', 'OFFSET', 'NOT IN', 'IN', 'LIKE', 'NOT LIKE', 'COUNT', 'MAX', 'MIN', 'ON', 'AS', 'AVG', 'SUM', '(', ')');
        $class_name = $CI->router->class;
        $method_name = $CI->router->method;
        $uri_string = $CI->uri->uri_string();
        $config = $CI->config->config;
        $unit_result = $CI->unit->result();
        $memory_usage = $CI->benchmark->memory_usage();
        $elapsed_time = $CI->benchmark->elapsed_time();
        $markers = $CI->benchmark->marker;
        $profiles = array(); // associative array, benchmark time for each marker
		foreach ($markers as $key => $val){
			if (preg_match('/(.+?)_end$/i', $key, $match) && isset($CI->benchmark->marker[$match[1].'_end'], $CI->benchmark->marker[$match[1].'_start'])){
				$profiles[$match[1]] = $CI->benchmark->elapsed_time($match[1].'_start', $key);
			}
		}

        $total_query_time = 0;
        foreach($CI->db->query_times as $key=>$val){
            $total_query_time += $val;
        }
        $profiles['database_query'] = $total_query_time;

        // get a gist
        $data = array(
            'class_name' => $class_name,
            'method_name' => $method_name,
            'memory_usage' => $memory_usage,
            'elapsed_time' => $elapsed_time,
            'profiles' => $profiles,
            'markers' => $markers,
            'server' => $_SERVER,
            'post' => $_POST,
            'get' => $_GET,
            'session' => $_SESSION,
            'cookie' => $_COOKIE,
            'db_queries' => $CI->db->queries,
            'db_query_times' => $CI->db->query_times,
            'sql_keywords' => $sql_keywords,
            'config' => $config,
            'uri_string' => $uri_string,
            'unit_result' => $unit_result,
            'variables' => $this->cms_data,
        );
        return $CI->load->view('CMS_Profile', $data, TRUE);
    }

    public function _display($output = '')
	{
		// Note:  We use load_class() because we can't use $CI =& get_instance()
		// since this function is sometimes called by the caching mechanism,
		// which happens before the CI super object is available.
		$BM =& load_class('Benchmark', 'core');
		$CFG =& load_class('Config', 'core');

		// Grab the super object if we can.
		if (class_exists('CI_Controller', FALSE))
		{
			$CI =& get_instance();
		}

		// --------------------------------------------------------------------

		// Set the output data
		if ($output === '')
		{
			$output =& $this->final_output;
		}

        // Added by gofrendi in order to make a better profiler
        if($this->cms_profiler_enabled){
            $output .= $this->build_cms_profile();
        }

		// --------------------------------------------------------------------

		// Do we need to write a cache file? Only if the controller does not have its
		// own _output() method and we are not dealing with a cache file, which we
		// can determine by the existence of the $CI object above
		if ($this->cache_expiration > 0 && isset($CI) && ! method_exists($CI, '_output'))
		{
			$this->_write_cache($output);
		}

		// --------------------------------------------------------------------

		// Parse out the elapsed time and memory usage,
		// then swap the pseudo-variables with the data

		$elapsed = $BM->elapsed_time('total_execution_time_start', 'total_execution_time_end');

		if ($this->parse_exec_vars === TRUE)
		{
			$memory	= round(memory_get_usage() / 1024 / 1024, 2).'MB';
			$output = str_replace(array('{elapsed_time}', '{memory_usage}'), array($elapsed, $memory), $output);
		}

		// --------------------------------------------------------------------

		// Is compression requested?
		if (isset($CI) // This means that we're not serving a cache file, if we were, it would already be compressed
			&& $this->_compress_output === TRUE
			&& isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== FALSE)
		{
			ob_start('ob_gzhandler');
		}

		// --------------------------------------------------------------------

		// Are there any server headers to send?
		if (count($this->headers) > 0)
		{
			foreach ($this->headers as $header)
			{
				@header($header[0], $header[1]);
			}
		}

		// --------------------------------------------------------------------

		// Does the $CI object exist?
		// If not we know we are dealing with a cache file so we'll
		// simply echo out the data and exit.
		if ( ! isset($CI))
		{
			if ($this->_compress_output === TRUE)
			{
				if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== FALSE)
				{
					header('Content-Encoding: gzip');
					header('Content-Length: '.strlen($output));
				}
				else
				{
					// User agent doesn't support gzip compression,
					// so we'll have to decompress our cache
					$output = gzinflate(substr($output, 10, -8));
				}
			}

			echo $output;
			log_message('info', 'Final output sent to browser');
			log_message('debug', 'Total execution time: '.$elapsed);
			return;
		}

		// --------------------------------------------------------------------

		// Do we need to generate profile data?
		// If so, load the Profile class and run it.
		if ($this->enable_profiler === TRUE)
		{
			$CI->load->library('profiler');
			if ( ! empty($this->_profiler_sections))
			{
				$CI->profiler->set_sections($this->_profiler_sections);
			}

			// If the output data contains closing </body> and </html> tags
			// we will remove them and add them back after we insert the profile data
			$output = preg_replace('|</body>.*?</html>|is', '', $output, -1, $count).$CI->profiler->run();
			if ($count > 0)
			{
				$output .= '</body></html>';
			}
		}

		// Does the controller contain a function named _output()?
		// If so send the output there.  Otherwise, echo it.
		if (method_exists($CI, '_output'))
		{
			$CI->_output($output);
		}
		else
		{
			echo $output; // Send it to the browser!
		}

		log_message('info', 'Final output sent to browser');
		log_message('debug', 'Total execution time: '.$elapsed);
	}

}
