<?php
/**
 * Description of dummyblog
 *
 * @author gofrendi
 */
class DummyBlog extends CMS_Controller {
    public function index(){
        $this->load->model('amazon');
        $this->amazon->get_product();
        
        $this->load->model('ixr');
        $this->amazon->post();
    }
}

?>
