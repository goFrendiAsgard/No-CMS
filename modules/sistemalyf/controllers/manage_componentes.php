<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Manage_Componentes
 *
 * @author No-CMS Module Generator
 */
class Manage_Componentes extends CMS_Priv_Strict_Controller {

	protected $URL_MAP = array();

	public function index(){
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// initialize groceryCRUD
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $crud = new grocery_CRUD();
        $crud->unset_jquery();

        // set model
        $crud->set_model($this->cms_module_path().'/grocerycrud_componentes_model');

        // adjust groceryCRUD's language to No-CMS's language
        $crud->set_language($this->cms_language());

        // table name
        $crud->set_table($this->cms_complete_table_name('componentes'));

        // set subject
        $crud->set_subject('Componentes');

        // displayed columns on list
        $crud->columns('num_ext','num_parte','descripcion','precio_compra','precio_venta','proveedor','ubicacion_bodega','imagen');
        // displayed columns on edit operation
        $crud->edit_fields('num_ext','num_parte','descripcion','precio_compra','precio_venta','proveedor','ubicacion_bodega','imagen');
        // displayed columns on add operation
        $crud->add_fields('num_ext','num_parte','descripcion','precio_compra','precio_venta','proveedor','ubicacion_bodega','imagen');

        // caption of each columns
        $crud->display_as('num_ext','Num Ext');
        $crud->display_as('num_parte','Num Parte');
        $crud->display_as('descripcion','Descripcion');
        $crud->display_as('precio_compra','Precio Compra');
        $crud->display_as('precio_venta','Precio Venta');
        $crud->display_as('proveedor','Proveedor');
        $crud->display_as('ubicacion_bodega','Ubicacion Bodega');
        $crud->display_as('imagen','Imagen');

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
        $this->view($this->cms_module_path().'/manage_componentes_view', $output,
            $this->cms_complete_navigation_name('manage_componentes'));

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

		return TRUE;
	}

	public function after_delete($primary_key){
		return TRUE;
	}

	public function after_insert_or_update($post_array, $primary_key){

        return TRUE;
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
