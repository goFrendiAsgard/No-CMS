<?php if (!defined('BASEPATH')) { exit('No direct script access allowed');}

class Test extends CMS_Test_Controller{

    public function index(){
        $this->test_sample_1();
        $this->test_sample_2();
        // show result
        $this->view();
    }

    private function test_sample_1(){
        $this->benchmark->mark('test_sample_1_start');
        $test = 1 + 1;
        $expected_result = 2;
        $test_name = 'Test Sample 1';
        $note = 'An example from CodeIgniter documentation. Passed if 1+1=2';
        $this->unit->run($test, $expected_result, $test_name, $note);
        $this->benchmark->mark('test_sample_1_end');
    }

    private function test_sample_2(){
        $this->benchmark->mark('test_sample_2_start');
        $test = 1 - 1;
        $expected_result = 2;
        $test_name = 'Test Sample 2';
        $note = 'A sample of wrong test, passed if 1 - 1 = 2 (and of course it\'s wrong)';
        $this->unit->run($test, $expected_result, $test_name, $note);
        $this->benchmark->mark('test_sample_2_end');
    }
}
