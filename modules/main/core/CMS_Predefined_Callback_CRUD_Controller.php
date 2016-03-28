<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Manage_group
 *
 * @author No-CMS Module Generator
 */
class CMS_Predefined_Callback_CRUD_Controller extends CMS_CRUD_Controller {

    public function view($view_url, $data = null, $navigation_name = null, $config = array(), $return_as_string = false)
    {
        if(!array_key_exists('css', $config) || $config['css'] == ''){
            $config['css'] = '';
        }
        if(!array_key_exists('js', $config) || $config['js'] == ''){
            $config['js'] = '';
        }
        $asset = new Cms_asset();
        $asset->add_css(base_url('assets/grocery_crud/css/jquery_plugins/chosen/chosen.css'));
        $config['css'] .= $asset->compile_css();
        $asset->add_js(base_url('assets/grocery_crud/js/jquery_plugins/jquery.chosen.min.js'));
        $asset->add_js(base_url('assets/grocery_crud/js/jquery_plugins/config/jquery.chosen.config.js'));
        $asset->add_js(base_url('assets/nocms/js/gofrendi.chosen.ajaxify.js'));
        $config['js'] .= $asset->compile_js();

        return parent::view($view_url, $data, $navigation_name, $config, $return_as_string);
    }

    public function _callback_field_group_user($value, $primary_key){
        if($value === NULL){
            $value = array();
        }
        $query = $this->db->select('user_id, user_name')
            ->from($this->cms_user_table_name())
            ->limit(20)
            ->get();
        $html = '<select id="field-group_user" name="group_user[]" multiple="multiple" size="8" class="form-control" data-placeholder="Select users">';
        // add old values
        foreach($value as $key=>$val){
            $html .= '<option selected value = "'.$key.'" >'.$val.'</option>';
        }
        // add other values
        foreach($query->result() as $row){
            if(!array_key_exists($row->user_id, $value)){
                $html .= '<option value = "'.$row->user_id.'" >'.$row->user_name.'</option>';
            }
        }
        $html .= '</select>';
        $html .= '<script>';
        $html .= '$("#field-group_user").chosen({allow_single_deselect:true, width:"100%", search_contains: true});';
        $html .= 'chosen_ajaxify("field-group_user", "{{ SITE_URL }}main/ajax/users/");';
        $html .= '</script>';
        return $html;
    }

    public function _callback_field_group_privilege($value, $primary_key){
        if($value === NULL){
            $value = array();
        }
        $query = $this->db->select('privilege_id, privilege_name')
            ->from(cms_table_name('main_privilege'))
            ->limit(20)
            ->get();
        $html = '<select id="field-group_privilege" name="group_privilege[]" multiple="multiple" size="8" class="form-control" data-placeholder="Select privileges">';
        // add old values
        foreach($value as $key=>$val){
            $html .= '<option selected value = "'.$key.'" >'.$val.'</option>';
        }
        // add other values
        foreach($query->result() as $row){
            if(!array_key_exists($row->privilege_id, $value)){
                $html .= '<option value = "'.$row->privilege_id.'" >'.$row->privilege_name.'</option>';
            }
        }
        $html .= '</select>';
        $html .= '<script>';
        $html .= '$("#field-group_privilege").chosen({allow_single_deselect:true, width:"100%", search_contains: true});';
        $html .= 'chosen_ajaxify("field-group_privilege", "{{ SITE_URL }}main/ajax/privileges/");';
        $html .= '</script>';
        return $html;
    }

    public function _callback_field_group_navigation($value, $primary_key){
        if($value === NULL){
            $value = array();
        }
        $query = $this->db->select('navigation_id, navigation_name')
            ->from(cms_table_name('main_navigation'))
            ->limit(20)
            ->get();
        $html = '<select id="field-group_navigation" name="group_navigation[]" multiple="multiple" size="8" class="form-control" data-placeholder="Select navigations">';
        // add old values
        foreach($value as $key=>$val){
            $html .= '<option selected value = "'.$key.'" >'.$val.'</option>';
        }
        // add other values
        foreach($query->result() as $row){
            if(!array_key_exists($row->navigation_id, $value)){
                $html .= '<option value = "'.$row->navigation_id.'" >'.$row->navigation_name.'</option>';
            }
        }
        $html .= '</select>';
        $html .= '<script>';
        $html .= '$("#field-group_navigation").chosen({allow_single_deselect:true, width:"100%", search_contains: true});';
        $html .= 'chosen_ajaxify("field-group_navigation", "{{ SITE_URL }}main/ajax/navigations/");';
        $html .= '</script>';
        return $html;
    }

    public function _callback_field_group_widget($value, $primary_key){
        if($value === NULL){
            $value = array();
        }
        $query = $this->db->select('widget_id, widget_name')
            ->from(cms_table_name('main_widget'))
            ->limit(20)
            ->get();
        $html = '<select id="field-group_widget" name="group_widget[]" multiple="multiple" size="8" class="form-control" data-placeholder="Select widgets">';
        // add old values
        foreach($value as $key=>$val){
            $html .= '<option selected value = "'.$key.'" >'.$val.'</option>';
        }
        // add other values
        foreach($query->result() as $row){
            if(!array_key_exists($row->widget_id, $value)){
                $html .= '<option value = "'.$row->widget_id.'" >'.$row->widget_name.'</option>';
            }
        }
        $html .= '</select>';
        $html .= '<script>';
        $html .= '$("#field-group_widget").chosen({allow_single_deselect:true, width:"100%", search_contains: true});';
        $html .= 'chosen_ajaxify("field-group_widget", "{{ SITE_URL }}main/ajax/widgets/");';
        $html .= '</script>';
        return $html;
    }
}
