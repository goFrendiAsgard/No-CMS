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

    protected function _execute($row, $rules, $postdata = NULL, $cycles = 0)
	{
        // If the $_POST data is an array we will run a recursive call
		if (is_array($postdata))
		{
			foreach ($postdata as $key => $val)
			{
				$this->_execute($row, $rules, $val, $key);
			}

			return;
		}

		// If the field is blank, but NOT required, no further tests are necessary
		$callback = FALSE;
		if ( ! in_array('required', $rules) && ($postdata === NULL OR $postdata === ''))
		{
			// Before we bail out, does the rule contain a callback?
			foreach ($rules as &$rule)
			{
				if (is_string($rule))
				{
					if (strncmp($rule, 'callback_', 9) === 0)
					{
						$callback = TRUE;
						$rules = array(1 => $rule);
						break;
					}
				}
				elseif (is_callable($rule))
				{
					$callback = TRUE;
					$rules = array(1 => $rule);
					break;
				}
			}

			if ( ! $callback)
			{
				return;
			}
		}

		// Isset Test. Typically this rule will only apply to checkboxes.
		if (($postdata === NULL OR $postdata === '') && ! $callback)
		{
			if (in_array('isset', $rules, TRUE) OR in_array('required', $rules))
			{
				// Set the message type
				$type = in_array('required', $rules) ? 'required' : 'isset';

				// Check if a custom message is defined
				if (isset($this->_field_data[$row['field']]['errors'][$type]))
				{
					$line = $this->_field_data[$row['field']]['errors'][$type];
				}
				elseif (isset($this->_error_messages[$type]))
				{
					$line = $this->_error_messages[$type];
				}
				elseif (FALSE === ($line = $this->CI->lang->line('form_validation_'.$type))
					// DEPRECATED support for non-prefixed keys
					&& FALSE === ($line = $this->CI->lang->line($type, FALSE)))
				{
					$line = 'The field was not set';
				}

				// Build the error message
				$message = $this->_build_error_msg($line, $this->_translate_fieldname($row['label']));

				// Save the error message
				$this->_field_data[$row['field']]['error'] = $message;

				if ( ! isset($this->_error_array[$row['field']]))
				{
					$this->_error_array[$row['field']] = $message;
				}
			}

			return;
		}

		// --------------------------------------------------------------------

		// Cycle through each rule and run it
		foreach ($rules as $rule)
		{
			$_in_array = FALSE;

			// We set the $postdata variable with the current data in our master array so that
			// each cycle of the loop is dealing with the processed data from the last cycle
			if ($row['is_array'] === TRUE && is_array($this->_field_data[$row['field']]['postdata']))
			{
				// We shouldn't need this safety, but just in case there isn't an array index
				// associated with this cycle we'll bail out
				if ( ! isset($this->_field_data[$row['field']]['postdata'][$cycles]))
				{
					continue;
				}

				$postdata = $this->_field_data[$row['field']]['postdata'][$cycles];
				$_in_array = TRUE;
			}
			else
			{
				// If we get an array field, but it's not expected - then it is most likely
				// somebody messing with the form on the client side, so we'll just consider
				// it an empty field
				$postdata = is_array($this->_field_data[$row['field']]['postdata'])
					? NULL
					: $this->_field_data[$row['field']]['postdata'];
			}

			// Is the rule a callback?
			$callback = $callable = FALSE;
			if (is_string($rule))
			{
				if (strpos($rule, 'callback_') === 0)
				{
					$rule = substr($rule, 9);
					$callback = TRUE;
				}
			}
			elseif (is_callable($rule))
			{
				$callable = TRUE;
			}
			elseif (is_array($rule) && isset($rule[0], $rule[1]) && is_callable($rule[1]))
			{
				// We have a "named" callable, so save the name
				$callable = $rule[0];
				$rule = $rule[1];
			}

			// Strip the parameter (if exists) from the rule
			// Rules can contain a parameter: max_length[5]
			$param = FALSE;
			if ( ! $callable && preg_match('/(.*?)\[(.*)\]/', $rule, $match))
			{
				$rule = $match[1];
				$param = $match[2];
			}

			// Call the function that corresponds to the rule
			if ($callback OR $callable !== FALSE)
			{
				if ($callback)
				{
					if ( ! method_exists($this->CI, $rule))
					{
						log_message('debug', 'Unable to find callback validation rule: '.$rule);
						$result = FALSE;
					}
					else
					{
						// Run the function and grab the result
						$result = $this->CI->$rule($postdata, $param);
					}
				}
				else
				{
					$result = is_array($rule)
						? $rule[0]->{$rule[1]}($postdata)
						: $rule($postdata);

					// Is $callable set to a rule name?
					if ($callable !== FALSE)
					{
						$rule = $callable;
					}
				}

				// Re-assign the result to the master data array
				if ($_in_array === TRUE)
				{
					$this->_field_data[$row['field']]['postdata'][$cycles] = is_bool($result) ? $postdata : $result;
				}
				else
				{
					$this->_field_data[$row['field']]['postdata'] = is_bool($result) ? $postdata : $result;
				}

				// If the field isn't required and we just processed a callback we'll move on...
				if ( ! in_array('required', $rules, TRUE) && $result !== FALSE)
				{
					continue;
				}
			}
			elseif ( ! method_exists($this, $rule))
			{
				// If our own wrapper function doesn't exist we see if a native PHP function does.
				// Users can use any native PHP function call that has one param.
				if (function_exists($rule))
				{
					// Native PHP functions issue warnings if you pass them more parameters than they use
					$result = ($param !== FALSE) ? $rule($postdata, $param) : $rule($postdata);

					if ($_in_array === TRUE)
					{
						$this->_field_data[$row['field']]['postdata'][$cycles] = is_bool($result) ? $postdata : $result;
					}
					else
					{
						$this->_field_data[$row['field']]['postdata'] = is_bool($result) ? $postdata : $result;
					}
				}
				else
				{
					log_message('debug', 'Unable to find validation rule: '.$rule);
					$result = FALSE;
				}
			}
			else
			{
				$result = $this->$rule($postdata, $param);

				if ($_in_array === TRUE)
				{
					$this->_field_data[$row['field']]['postdata'][$cycles] = is_bool($result) ? $postdata : $result;
				}
				else
				{
					$this->_field_data[$row['field']]['postdata'] = is_bool($result) ? $postdata : $result;
				}
			}

			// Did the rule test negatively? If so, grab the error.
			if ($result === FALSE)
			{
				// Callable rules might not have named error messages
				if ( ! is_string($rule))
				{
					return;
				}

				// Check if a custom message is defined
				if (isset($this->_field_data[$row['field']]['errors'][$rule]))
				{
					$line = $this->_field_data[$row['field']]['errors'][$rule];
				}
				elseif ( ! isset($this->_error_messages[$rule]))
				{
					if (FALSE === ($line = $this->CI->lang->line('form_validation_'.$rule))
						// DEPRECATED support for non-prefixed keys
						&& FALSE === ($line = $this->CI->lang->line($rule, FALSE)))
					{
                        log_message('error', print_r($rule,TRUE));
						$line = $this->CI->lang->line('form_validation_error_message_not_set').'('.$rule.')';
					}
				}
				else
				{
					$line = $this->_error_messages[$rule];
				}

				// Is the parameter we are inserting into the error message the name
				// of another field? If so we need to grab its "field label"
				if (isset($this->_field_data[$param], $this->_field_data[$param]['label']))
				{
					$param = $this->_translate_fieldname($this->_field_data[$param]['label']);
				}

				// Build the error message
				$message = $this->_build_error_msg($line, $this->_translate_fieldname($row['label']), $param);

				// Save the error message
				$this->_field_data[$row['field']]['error'] = $message;

				if ( ! isset($this->_error_array[$row['field']]))
				{
					$this->_error_array[$row['field']] = $message;
				}

				return;
			}
		}
	}

}
