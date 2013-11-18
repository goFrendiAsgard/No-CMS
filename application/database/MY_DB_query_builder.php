<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Query Builder Class
 *
 * This is the platform-independent base Query Builder implementation class.
 *
 * @package     CodeIgniter
 * @subpackage  Drivers
 * @category    Database
 * @author      EllisLab Dev Team
 * @link        http://codeigniter.com/user_guide/database/
 */

class MY_DB_query_builder extends CI_DB_query_builder {

    // below code are copy-pasted from MY_DB_driver. This is necessary since CI_DB_query_builder extends CI_DB_Driver which is overrided by MY_DB_Driver. This is also the reason I like multiple-inheritance and hate PHP :(
    // if multiple inheritence works, this class should be MY_DB_query_builder extends CI_DB_query_builder, MY_DB_Driver

    public function query($sql, $binds = FALSE, $return_object = NULL)
    {
        if ($sql === '')
        {
            log_message('error', 'Invalid query: '.$sql);
            return ($this->db_debug) ? $this->display_error('db_invalid_query') : FALSE;
        }
        elseif ( ! is_bool($return_object))
        {
            $return_object = ! $this->is_write_type($sql);
        }

        // Verify table prefix and replace if necessary
        if ($this->dbprefix !== '' && $this->swap_pre !== '' && $this->dbprefix !== $this->swap_pre)
        {
            $sql = preg_replace('/(\W)'.$this->swap_pre.'(\S+?)/', '\\1'.$this->dbprefix.'\\2', $sql);
        }

        // Compile binds if needed
        if ($binds !== FALSE)
        {
            $sql = $this->compile_binds($sql, $binds);
        }

        // Is query caching enabled? If the query is a "read type"
        // we will load the caching class and return the previously
        // cached query if it exists
        if ($this->cache_on === TRUE && $return_object === TRUE && $this->_cache_init())
        {
            $this->load_rdriver();
            if (FALSE !== ($cache = $this->CACHE->read($sql)))
            {
                return $cache;
            }
        }

        // Save the query for debugging
        if ($this->save_queries === TRUE)
        {
            $this->queries[] = $sql;
        }

        // Start the Query Timer
        $time_start = microtime(TRUE);

        // Run the Query
        if (FALSE === ($this->result_id = $this->simple_query($sql)))
        {
            if ($this->save_queries === TRUE)
            {
                $this->query_times[] = 0;
            }

            // This will trigger a rollback if transactions are being used
            $this->_trans_status = FALSE;

            // Grab the error now, as we might run some additional queries before displaying the error
            $error = $this->error();

            // Log errors
            log_message('error', 'Query error: '.$error['message'].' - Invalid query: '.$sql);

            if ($this->db_debug)
            {
                // We call this function in order to roll-back queries
                // if transactions are enabled. If we don't call this here
                // the error message will trigger an exit, causing the
                // transactions to remain in limbo.
                if ($this->_trans_depth !== 0)
                {
                    do
                    {
                        $this->trans_complete();
                    }
                    while ($this->_trans_depth !== 0);
                }

                // Display errors
                return $this->display_error(array('Error Number: '.$error['code'], $error['message'], $sql));
            }

            return FALSE;
        }

        // Stop and aggregate the query time results
        $time_end = microtime(TRUE);
        $this->benchmark += $time_end - $time_start;

        if ($this->save_queries === TRUE)
        {
            $this->query_times[] = $time_end - $time_start;
        }

        // Increment the query counter
        $this->query_count++;

        // Will we have a result object instantiated? If not - we'll simply return TRUE
        if ($return_object !== TRUE)
        {
            // If caching is enabled we'll auto-cleanup any existing files related to this particular URI
            if ($this->cache_on === TRUE && $this->cache_autodel === TRUE && $this->_cache_init())
            {
                $this->CACHE->delete();
            }

            return TRUE;
        }

        // Return TRUE if we don't need to create a result object
        if ($return_object !== TRUE)
        {
            return TRUE;
        }

        // Load and instantiate the result driver
        $driver     = $this->load_rdriver();
        $RES        = new $driver($this);

        // Is query caching enabled? If so, we'll serialize the
        // result object and save it to a cache file.
        if ($this->cache_on === TRUE && $this->_cache_init())
        {
            // We'll create a new instance of the result object
            // only without the platform specific driver since
            // we can't use it with cached data (the query result
            // resource ID won't be any good once we've cached the
            // result object, so we'll have to compile the data
            // and save it)

            // modified by Go Frendi Gunawan, 14-NOV-2013
            if(class_exists('MY_DB_result')){
                $CR = MY_DB_result($this);
            }else{
                $CR = new CI_DB_result($this);
            }
            $CR->result_object  = $RES->result_object();
            $CR->result_array   = $RES->result_array();
            $CR->num_rows       = $RES->num_rows();

            // Reset these since cached objects can not utilize resource IDs.
            $CR->conn_id        = NULL;
            $CR->result_id      = NULL;

            $this->CACHE->write($sql, $CR);
        }

        return $RES;
    }

    public function load_rdriver()
    {
        $driver = 'CI_DB_'.$this->dbdriver.'_result';
        $my_driver = 'MY_DB_'.$this->dbdriver.'_result';

        if ( ! class_exists($driver, FALSE))
        {
            include_once(BASEPATH.'database/DB_result.php');
            if ( ! class_exists($my_driver, FALSE))
            {
                $file_name = APPPATH.'database/MY_DB_result.php';
                if(file_exists($file_name)){
                    include_once($file_name);
                }
            }
            include_once(BASEPATH.'database/drivers/'.$this->dbdriver.'/'.$this->dbdriver.'_result.php');
            $file_name = APPPATH.'database/drivers/'.$this->dbdriver.'/MY_'.$this->dbdriver.'_result.php';
            if(file_exists($file_name)){
                include_once($file_name);
            }
        }
        if(class_exists($my_driver)){
            $driver = $my_driver;
        }

        return $driver;
    }
}