<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Test_notif extends CMS_Controller{
    function index(){
        echo json_encode(array('success'=>TRUE,'notif'=>rand(0, 100)));
    }
}