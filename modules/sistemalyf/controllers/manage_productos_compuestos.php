<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Manage_Productos_Compuestos
 *
 * @author No-CMS Module Generator
 */
class Manage_Productos_Compuestos extends CMS_Priv_Strict_Controller {

	protected $URL_MAP = array();

	public function index(){
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// initialize groceryCRUD
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $crud = new grocery_CRUD();
        $crud->unset_jquery();

        // set model
        $crud->set_model($this->cms_module_path().'/grocerycrud_productos_compuestos_model');

        // adjust groceryCRUD's language to No-CMS's language
        $crud->set_language($this->cms_language());

        // table name
        $crud->set_table($this->cms_complete_table_name('productos_compuestos'));

        // set subject
        $crud->set_subject('Productos Compuestos');

        // displayed columns on list
        $crud->columns('num_ext','num_parte','descripcion','precio_compra','precio_venta','ubicacion_bodega','imagen','relprocom');
        // displayed columns on edit operation
        $crud->edit_fields('num_ext','num_parte','descripcion','precio_compra','precio_venta','ubicacion_bodega','imagen','relprocom');
        // displayed columns on add operation
        $crud->add_fields('num_ext','num_parte','descripcion','precio_compra','precio_venta','ubicacion_bodega','imagen','relprocom');

        // caption of each columns
        $crud->display_as('num_ext','Num Ext');
        $crud->display_as('num_parte','Num Parte');
        $crud->display_as('descripcion','Descripcion');
        $crud->display_as('precio_compra','Precio Compra');
        $crud->display_as('precio_venta','Precio Venta');
        $crud->display_as('ubicacion_bodega','Ubicacion Bodega');
        $crud->display_as('imagen','Imagen');
        $crud->display_as('relprocom','Relprocom');
$crud->callback_edit_field('ubicacion_bodega',array($this,'dropdownlist'));
$crud->callback_add_field('ubicacion_bodega',array($this,'dropdownlist'));
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

		$crud->callback_column('relprocom',array($this, 'callback_column_relprocom'));
		$crud->callback_field('relprocom',array($this, 'callback_field_relprocom'));

		//aqui puse los callbacks  
$crud->callback_edit_field('ubicacion_bodega',array($this,'dropdownlist'));
$crud->callback_add_field('ubicacion_bodega',array($this,'dropdownlist'));
		// UPS, SORRY, My fault.....
$crud->callback_before_insert(array($this,'change_value'));
$crud->callback_before_update(array($this,'change_value'));

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // render
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $output = $crud->render();
        $this->view($this->cms_module_path().'/manage_productos_compuestos_view', $output,
            $this->cms_complete_navigation_name('manage_productos_compuestos'));

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
		// delete corresponding rel_pro_compuestos
		$this->db->delete($this->cms_complete_table_name('rel_pro_compuestos'),
		      array('priority'=>$primary_key));
		return TRUE;
	}

	public function after_delete($primary_key){
		return TRUE;
	}

	public function after_insert_or_update($post_array, $primary_key){

		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//
		// SAVE CHANGES OF rel_pro_compuestos
		//  * The rel_pro_compuestos data in in json format.
		//  * It can be accessed via $_POST['md_real_field_relprocom_col']
		//
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$data = json_decode($this->input->post('md_real_field_relprocom_col'), TRUE);
		$insert_records = $data['insert'];
		$update_records = $data['update'];
		$delete_records = $data['delete'];
		$real_column_names = array('priority', 'relpro', 'cantidad');
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
			$this->db->delete($this->cms_complete_table_name('rel_pro_compuestos'),
			     array('priority'=>$detail_primary_key));
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
			$data['relprocom'] = $primary_key;
			$this->db->update($this->cms_complete_table_name('rel_pro_compuestos'),
			     $data, array('priority'=>$detail_primary_key));
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
			$data['relprocom'] = $primary_key;
			$this->db->insert($this->cms_complete_table_name('rel_pro_compuestos'), $data);
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
	public function callback_field_relprocom($value, $primary_key){
	    $module_path = $this->cms_module_path();
		$this->config->load('grocery_crud');
        $date_format = $this->config->item('grocery_crud_date_format');

		if(!isset($primary_key)) $primary_key = -1;
		$query = $this->db->select('priority, relpro, cantidad')
			->from($this->cms_complete_table_name('rel_pro_compuestos'))
			->where('relprocom', $primary_key)
			->get();
		$result = $query->result_array();

		// get options
		$options = array();
		$options['relpro'] = array();
		$query = $this->db->select('num_int_pro,descripcion')
           ->from($this->cms_complete_table_name('productos'))
           ->get();
		foreach($query->result() as $row){
			$options['relpro'][] = array('value' => $row->num_int_pro, 'caption' => $row->descripcion);
		}
		$data = array(
			'result' => $result,
			'options' => $options,
			'date_format' => $date_format,
		);
		return $this->load->view($this->cms_module_path().'/field_productos_compuestos_relprocom',$data, TRUE);
	}

	// returned on view
	public function callback_column_relprocom($value, $row){
	    $module_path = $this->cms_module_path();
		$query = $this->db->select('priority, relpro, cantidad')
			->from($this->cms_complete_table_name('rel_pro_compuestos'))
			->where('relprocom', $row->num_int_procom)
			->get();
		$num_row = $query->num_rows();
		// show how many records
		if($num_row>1){
			return $num_row .' Rel Pro Compuestoss';
		}else if($num_row>0){
			return $num_row .' Rel Pro Compuestos';
		}else{
			return 'No Rel Pro Compuestos';
		}
	}
	//esta es la funcion del primer callback---- first callback function
	function dropdownlist($value=NULL, $primary_key=NULL)
    {
         // assuming you use '-' as delimiter. In the database it should be saved as DONDE-COLUMNA-FILA
         if(isset($value)){
             $value_array = explode('-', $value);
         }else{
             // on insert mode, it is empty
             $value_array = array('1','2','3');
         }
         $selected_donde = $value_array[0];
         $selected_columna = $value_array[1];
         $selected_fila = $value_array[2];

         $donde_options = array('Estante', 'Caja');
         $columna_options = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
         $fila_options = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50);
         
         $html = '';
         $html .= '<select name="DONDE">';
         foreach($donde_options as $donde_option){
             if($donde_option==$selected_donde){
                  $html .= '<option value="'.$donde_option.'" selected>'.$donde_option.'</option>';
             }else{
                  $html .= '<option value="'.$donde_option.'">'.$donde_option.'</option>';
             }
         }
         $html .= '</select>';

         // do the same for columna and fila ...
         $html .= '<select name="COLUMNA">';
         foreach($columna_options as $columna_option){
             if($columna_option==$selected_columna){
                  $html .= '<option value="'.$columna_option.'" selected>'.$columna_option.'</option>';
             }else{
                  $html .= '<option value="'.$columna_option.'">'.$columna_option.'</option>';
             }
         }
         $html .= '</select>';
         $html .= '<select name="FILA">';
         foreach($fila_options as $fila_option){
             if($fila_option==$selected_fila){
                  $html .= '<option value="'.$fila_option.'" selected>'.$fila_option.'</option>';
             }else{
                  $html .= '<option value="'.$fila_option.'">'.$fila_option.'</option>';
             }
         }
         $html .= '</select>';

         return $html;
    }
    function change_value($post_array, $primary_key=NULL){
        log_message('error', print_r($post_array, TRUE));
        // omit donde, columna and fila, make ubicacion_bodega instead
        $donde = $post_array['DONDE'];
        $columna = $post_array['COLUMNA'];
        $fila = $post_array['FILA'];    
        $ubicacion_bodega = $donde.'-'.$columna.'-'.$fila;
        $post_array['ubicacion_bodega'] = $ubicacion_bodega;
        unset($post_array['DONDE']);
        unset($post_array['COLUMNA']);
        unset($post_array['FILA']);
        return $post_array;        
    }
}
