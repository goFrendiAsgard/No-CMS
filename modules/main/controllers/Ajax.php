<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CMS_Controller{

    public function check_registration()
    {
        if ($this->input->is_ajax_request()) {
            $user_name = $this->input->post('user_name');
            $email = $this->input->post('email');
            $user_name_exists = $this->cms_is_user_exists($user_name);
            $email_exists = $this->cms_is_user_exists($email);
            $valid_email = preg_match('/@.+\./', $email);
            $message = '';
            $error = false;
            if ($user_name == '') {
                $message = $this->cms_lang('Username is empty');
                $error = true;
            } else if ($user_name_exists) {
                $message = $this->cms_lang('Username already exists');
                $error = true;
            } else if (!$valid_email) {
                $message = $this->cms_lang('Invalid email address');
                $error = true;
            } else if ($email_exists) {
                $message = $this->cms_lang('Email already used');
                $error = true;
            } else{
                $return_set = $this->cms_call_hook('cms_validate_register', array($this->input->post()));
                foreach($return_set as $return){
                    if(is_array($return)){
                        if(array_key_exists('error', $return)){
                            $error = $return['error'];
                            if(array_key_exists('message', $return)){
                                $message = $return['message'];
                            }
                            break;
                        }
                    }
                }
            }

            $data = array(
                'exists' => $user_name_exists || $email_exists,
                'error' => $error,
                'message' => $message,
            );
            $this->cms_show_json($data);
        }
    }

    public function get_layout($theme = '')
    {
        if ($this->input->is_ajax_request()) {
            $this->cms_show_json($this->cms_get_layout());
        }
    }

    public function check_change_profile()
    {
        if ($this->input->is_ajax_request()) {
            $email = $this->input->post('email');
            $email_exists = $this->cms_is_user_exists($email, $this->cms_user_id());
            $valid_email = preg_match('/@.+\./', $email);
            $message = '';
            $error = false;
            if (!$valid_email) {
                $message = $this->cms_lang('Invalid email address');
                $error = true;
            } else if ($email_exists) {
                $message = $this->cms_lang('Email already used');
                $error = true;
            } else{
                $return_set = $this->cms_call_hook('cms_validate_change_profile', array($this->cms_user_id(), $this->input->post()));
                foreach($return_set as $return){
                    if(is_array($return)){
                        if(array_key_exists('error', $return)){
                            $error = $return['error'];
                            if(array_key_exists('message', $return)){
                                $message = $return['message'];
                            }
                            break;
                        }
                    }
                }
            }
            $data = array(
                'exists' => $email_exists,
                'error' => $error,
                'message' => $message,
            );
            $this->cms_show_json($data);
        }
    }

    public function users($keyword = ''){
        $query = $this->db->select('user_id, user_name, real_name')
            ->from($this->cms_user_table_name())
            ->like('user_name', $keyword)
            ->or_like('real_name', $keyword)
            ->limit(20)
            ->get();
        $result = array();
        foreach($query->result() as $row){
            $result[] = array(
                'value' => $row->user_id,
                'caption' => $row->user_name.' - '.$row->real_name,
            );
        }
        echo json_encode($result);
    }

    public function groups($keyword = ''){
        $query = $this->db->select('group_id, group_name')
            ->from(cms_table_name('main_group'))
            ->like('group_name', $keyword)
            ->limit(20)
            ->get();
        $result = array();
        foreach($query->result() as $row){
            $result[] = array(
                'value' => $row->group_id,
                'caption' => $row->group_name,
            );
        }
        echo json_encode($result);
    }

    public function navigations($keyword = ''){
        $query = $this->db->select('navigation_id, navigation_name')
            ->from(cms_table_name('main_navigation'))
            ->like('navigation_name', $keyword)
            ->limit(20)
            ->get();
        $result = array();
        foreach($query->result() as $row){
            $result[] = array(
                'value' => $row->navigation_id,
                'caption' => $row->navigation_name,
            );
        }
        echo json_encode($result);
    }

    public function privileges($keyword = ''){
        $query = $this->db->select('privilege_id, privilege_name')
            ->from(cms_table_name('main_privilege'))
            ->like('privilege_name', $keyword)
            ->limit(20)
            ->get();
        $result = array();
        foreach($query->result() as $row){
            $result[] = array(
                'value' => $row->privilege_id,
                'caption' => $row->privilege_name,
            );
        }
        echo json_encode($result);
    }

    public function widgets($keyword = ''){
        $query = $this->db->select('widget_id, widget_name')
            ->from(cms_table_name('main_widget'))
            ->like('widget_name', $keyword)
            ->limit(20)
            ->get();
        $result = array();
        foreach($query->result() as $row){
            $result[] = array(
                'value' => $row->widget_id,
                'caption' => $row->widget_name,
            );
        }
        echo json_encode($result);
    }
}
