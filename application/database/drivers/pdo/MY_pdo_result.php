<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_DB_pdo_result extends CI_DB_pdo_result {

	/**
	 * Number of rows in the result set
	 *
	 * @return	int
	 */
	public function num_rows()
	{
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

/* End of file pdo_result.php */
/* Location: ./system/database/drivers/pdo/pdo_result.php */