<?php
/** application/libraries/MY_Form_validation **/
class MY_Form_validation extends CI_Form_validation
{
    public $CI;
    public $_field_data         = array();
    public $_config_rules       = array();
    public $_error_array        = array();
    public $_error_messages     = array();
    public $_error_prefix       = '<p>';
    public $_error_suffix       = '</p>';
    public $error_string        = '';
    public $_safe_form_data     = FALSE;
    public $validation_data     = array();

    public function is_unique($str, $field)
	{
		sscanf($field, '%[^.].%[^.]', $table, $field);
         // bypass by gofrendi because isset is not working
        return $this->CI->db
 			? ($this->CI->db->limit(1)->get_where($table, array($field => $str))->num_rows() === 0)
 			: FALSE;
        // TODO: Why is it not working?
        /*
        return isset($this->CI->db)
			? ($this->CI->db->limit(1)->get_where($table, array($field => $str))->num_rows() === 0)
			: FALSE;
        */
	}
}
