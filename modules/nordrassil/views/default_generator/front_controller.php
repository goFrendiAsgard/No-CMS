&lt;?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of {{ controller_name }}
 *
 * @author No-CMS Module Generator
 */
class {{ controller_name }} extends CMS_Controller {

	public function index(){
		$data = NULL;
        $this->view('{{ project_name }}/front/{{ controller_name }}_index',$data, '{{ navigation_name }}');
    }
    
    public function get_data(){
    	// get page and keyword parameter
    	$keyword = $this->input->post('keyword');
    	$page = $this->input->post('page');
    	if(!$keyword) $keyword = '';
    	if(!$page) $page = 0;
    	// get data from model
    	$this->load->model('{{ project_name }}/front/{{ controller_name }}_model');
    	$result = $this->{{ controller_name }}_model->get_data($keyword, $page);
		$this->cms_show_json($result);
	}
    
}

?&gt;