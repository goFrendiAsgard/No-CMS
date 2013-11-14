<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MY_DB_result extends CI_DB_result {
	public function num_rows()
	{
        echo 'MY_DB_RESULT HERE';
        var_dump($this->num_rows);
        var_dump($this->result_array);
        var_dump($this->result_object);
		if (is_int($this->num_rows))
		{
			return $this->num_rows;
		}
		elseif (count($this->result_array) > 0)
		{
			return $this->num_rows = count($this->result_array);
		}
		elseif (count($this->result_object) > 0)
		{
			return $this->num_rows = count($this->result_object);
		}

		return $this->num_rows = count($this->result_array());
	}
}