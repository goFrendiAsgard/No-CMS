<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Manage_Book
 *
 * @author No-CMS Module Generator
 */
class Manage_Book extends CMS_Priv_Strict_Controller {

	protected $URL_MAP = array();

	public function index(){
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// initialize groceryCRUD
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $crud = $this->new_crud();
        $crud->unset_jquery();

        // set model
        $crud->set_model($this->cms_module_path().'/grocerycrud_book_model');

        // adjust groceryCRUD's language to No-CMS's language
        $crud->set_language($this->cms_language());

        // table name
        $crud->set_table($this->cms_complete_table_name('book'));

        // set subject
        $crud->set_subject('Book');

        // displayed columns on list
        $crud->columns('isbn','title','author','publisher','description','category','physical_book');
        // displayed columns on edit operation
        $crud->edit_fields('isbn','title','author','publisher','description','category','physical_book');
        // displayed columns on add operation
        $crud->add_fields('isbn','title','author','publisher','description','category','physical_book');

        // caption of each columns
        $crud->display_as('isbn','ISBN');
        $crud->display_as('title','Title');
        $crud->display_as('author','Author');
        $crud->display_as('publisher','Publisher');
        $crud->display_as('description','Description');
        $crud->display_as('category','Category');
        $crud->display_as('physical_book','Physical Book');

		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// HINT: Put set relation (lookup) codes here
		// (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation)
		// eg:
		// 		$crud->set_relation( $field_name , $related_table, $related_title_field , $where , $order_by );
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// HINT: Put set relation_n_n (detail many to many) codes here
		// (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation_n_n)
		// eg:
		// 		$crud->set_relation_n_n( $field_name, $relation_table, $selection_table, $primary_key_alias_to_this_table,
		// 			$primary_key_alias_to_selection_table , $title_field_selection_table, $priority_field_relation );
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$crud->set_relation_n_n('category',
		    $this->cms_complete_table_name('book_category'),
		    $this->cms_complete_table_name('category'),
			'id_book', 'id_category',
			'name', NULL);

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// HINT: Put custom field type here
		// (documentation: http://www.grocerycrud.com/documentation/options_functions/field_type)
		// eg:
		// 		$crud->field_type( $field_name , $field_type, $value  );
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////



        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// HINT: Put callback here
		// (documentation: httm://www.grocerycrud.com/documentation/options_functions)
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$crud->callback_before_insert(array($this,'before_insert'));
		$crud->callback_before_update(array($this,'before_update'));
		$crud->callback_before_delete(array($this,'before_delete'));
		$crud->callback_after_insert(array($this,'after_insert'));
		$crud->callback_after_update(array($this,'after_update'));
		$crud->callback_after_delete(array($this,'after_delete'));

		$crud->callback_column('physical_book',array($this, 'callback_column_physical_book'));
		$crud->callback_field('physical_book',array($this, 'callback_field_physical_book'));

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // render
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $output = $crud->render();
        $this->view($this->cms_module_path().'/manage_book_view', $output,
            $this->cms_complete_navigation_name('manage_book'));

    }

    public function before_insert($post_array){
		return TRUE;
	}

	public function after_insert($post_array, $primary_key){
		$success = $this->after_insert_or_update($post_array, $primary_key);
		return $success;
	}

	public function before_update($post_array, $primary_key){
		return TRUE;
	}

	public function after_update($post_array, $primary_key){
		$success = $this->after_insert_or_update($post_array, $primary_key);
		return $success;
	}

	public function before_delete($primary_key){
		// delete corresponding physical_book
		$this->db->delete($this->cms_complete_table_name('physical_book'),
		      array('id'=>$primary_key));
		return TRUE;
	}

	public function after_delete($primary_key){
		return TRUE;
	}

	public function after_insert_or_update($post_array, $primary_key){

		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//
		// SAVE CHANGES OF physical_book
		//  * The physical_book data in in json format.
		//  * It can be accessed via $_POST['md_real_field_physical_book_col']
		//
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$data = json_decode($this->input->post('md_real_field_physical_book_col'), TRUE);
		$insert_records = $data['insert'];
		$update_records = $data['update'];
		$delete_records = $data['delete'];
		$real_column_names = array('id', 'code');
		$set_column_names = array();
		$many_to_many_column_names = array();
		$many_to_many_relation_tables = array();
		$many_to_many_relation_table_columns = array();
		$many_to_many_relation_selection_columns = array();
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//  DELETED DATA
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		foreach($delete_records as $delete_record){
			$detail_primary_key = $delete_record['primary_key'];
			// delete many to many
			for($i=0; $i<count($many_to_many_column_names); $i++){
				$table_name = $this->cms_complete_table_name($many_to_many_relation_tables[$i]);
				$relation_column_name = $many_to_many_relation_table_columns[$i];
				$relation_selection_column_name = $many_to_many_relation_selection_columns[$i];
				$where = array(
					$relation_column_name => $detail_primary_key
				);
				$this->db->delete($table_name, $where);
			}
			$this->db->delete($this->cms_complete_table_name('physical_book'),
			     array('id'=>$detail_primary_key));
		}
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //  UPDATED DATA
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		foreach($update_records as $update_record){
			$detail_primary_key = $update_record['primary_key'];
			$data = array();
			foreach($update_record['data'] as $key=>$value){
				if(in_array($key, $set_column_names)){
					$data[$key] = implode(',', $value);
				}else if(in_array($key, $real_column_names)){
					$data[$key] = $value;
				}
			}
			$data['book_id'] = $primary_key;
			$this->db->update($this->cms_complete_table_name('physical_book'),
			     $data, array('id'=>$detail_primary_key));
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// Adjust Many-to-Many Fields of Updated Data
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////
			for($i=0; $i<count($many_to_many_column_names); $i++){
				$key = 	$many_to_many_column_names[$i];
				$new_values = $update_record['data'][$key];
				$table_name = $this->cms_complete_table_name($many_to_many_relation_tables[$i]);
				$relation_column_name = $many_to_many_relation_table_columns[$i];
				$relation_selection_column_name = $many_to_many_relation_selection_columns[$i];
				$query = $this->db->select($relation_column_name.','.$relation_selection_column_name)
					->from($table_name)
					->where($relation_column_name, $detail_primary_key)
					->get();
				/////////////////////////////////////////////////////////////////////////////////////////////////////////
				// delete everything which is not in new_values
				/////////////////////////////////////////////////////////////////////////////////////////////////////////
				$old_values = array();
				foreach($query->result_array() as $row){
					$old_values = array();
					if(!in_array($row[$relation_selection_column_name], $new_values)){
						$where = array(
							$relation_column_name => $detail_primary_key,
							$relation_selection_column_name => $row[$relation_selection_column_name]
						);
						$this->db->delete($table_name, $where);
					}else{
						$old_values[] = $row[$relation_selection_column_name];
					}
				}
				/////////////////////////////////////////////////////////////////////////////////////////////////////////
				// add everything which is not in old_values but in new_values
				/////////////////////////////////////////////////////////////////////////////////////////////////////////
				foreach($new_values as $new_value){
					if(!in_array($new_value, $old_values)){
						$data = array(
							$relation_column_name => $detail_primary_key,
							$relation_selection_column_name => $new_value
						);
						$this->db->insert($table_name, $data);
					}
				}
			}
		}
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //  INSERTED DATA
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		foreach($insert_records as $insert_record){
			$data = array();
			foreach($insert_record['data'] as $key=>$value){
				if(in_array($key, $set_column_names)){
					$data[$key] = implode(',', $value);
				}else if(in_array($key, $real_column_names)){
					$data[$key] = $value;
				}
			}
			$data['book_id'] = $primary_key;
			$this->db->insert($this->cms_complete_table_name('physical_book'), $data);
			$detail_primary_key = $this->db->insert_id();
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // Adjust Many-to-Many Fields of Inserted Data
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////
			for($i=0; $i<count($many_to_many_column_names); $i++){
				$key = 	$many_to_many_column_names[$i];
				$new_values = $insert_record['data'][$key];
				$table_name = $this->cms_complete_table_name($many_to_many_relation_tables[$i]);
				$relation_column_name = $many_to_many_relation_table_columns[$i];
				$relation_selection_column_name = $many_to_many_relation_selection_columns[$i];
				$query = $this->db->select($relation_column_name.','.$relation_selection_column_name)
					->from($table_name)
					->where($relation_column_name, $detail_primary_key)
					->get();
				/////////////////////////////////////////////////////////////////////////////////////////////////////////
				// delete everything which is not in new_values
				/////////////////////////////////////////////////////////////////////////////////////////////////////////
				$old_values = array();
				foreach($query->result_array() as $row){
					$old_values = array();
					if(!in_array($row[$relation_selection_column_name], $new_values)){
						$where = array(
							$relation_column_name => $detail_primary_key,
							$relation_selection_column_name => $row[$relation_selection_column_name]
						);
						$this->db->delete($table_name, $where);
					}else{
						$old_values[] = $row[$relation_selection_column_name];
					}
				}
				/////////////////////////////////////////////////////////////////////////////////////////////////////////
				// add everything which is not in old_values but in new_values
				/////////////////////////////////////////////////////////////////////////////////////////////////////////
				foreach($new_values as $new_value){
					if(!in_array($new_value, $old_values)){
						$data = array(
							$relation_column_name => $detail_primary_key,
							$relation_selection_column_name => $new_value
						);
						$this->db->insert($table_name, $data);
					}
				}
			}
		}

        return TRUE;
	}


	// returned on insert and edit
	public function callback_field_physical_book($value, $primary_key){
	    $module_path = $this->cms_module_path();
		$this->config->load('grocery_crud');
        $date_format = $this->config->item('grocery_crud_date_format');

		if(!isset($primary_key)) $primary_key = -1;
		$query = $this->db->select('id, code')
			->from($this->cms_complete_table_name('physical_book'))
			->where('book_id', $primary_key)
			->get();
		$result = $query->result_array();

		// get options
		$options = array();
		$data = array(
			'result' => $result,
			'options' => $options,
			'date_format' => $date_format,
		);
		return $this->load->view($this->cms_module_path().'/field_book_physical_book',$data, TRUE);
	}

	// returned on view
	public function callback_column_physical_book($value, $row){
	    $module_path = $this->cms_module_path();
		$query = $this->db->select('id, code')
			->from($this->cms_complete_table_name('physical_book'))
			->where('book_id', $row->id)
			->get();
		$num_row = $query->num_rows();
		// show how many records
		if($num_row>1){
			return $num_row .' Physical Books';
		}else if($num_row>0){
			return $num_row .' Physical Book';
		}else{
			return 'No Physical Book';
		}
	}

}