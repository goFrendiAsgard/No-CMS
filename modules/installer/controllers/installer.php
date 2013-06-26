<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Installer extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('installer/install_model');
        $this->install_model = new Install_Model();
    }

    public function index(){
        $this->install_model->db_protocol = 'pgsql';
        $this->install_model->db_host = 'localhost';
        $this->install_model->db_name = 'newdb';
        $this->install_model->db_port = '5432';
        $this->install_model->db_username = 'root';
        $this->install_model->db_password = 'toor';
        $this->install_model->db_table_prefix = 'coba';
        $this->install_model->build_database();
        //$this->install_model->build_configuration();
    }

}
