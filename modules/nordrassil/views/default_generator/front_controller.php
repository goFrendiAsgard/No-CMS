&lt;?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of {{ controller_name }}
 *
 * @author No-CMS Module Generator
 */

class {{ controller_name }} extends CMS_Priv_Strict_Controller {

    protected function do_override_url_map($URL_MAP){
        $module_path = $this->cms_module_path();
        $URL_MAP[$module_path.'/'.$module_path] = {{ navigation_name }};
        $URL_MAP[$module_path] = {{ navigation_name }};
        return $URL_MAP;
    }

	public function index(){
	    $module_path = $this->cms_module_path();
		$data = array(
			'allow_navigate_backend' => $this->cms_allow_navigate({{ backend_navigation_name }}),
			'backend_url' => site_url($this->cms_module_path().'/{{ back_controller_import_name }}/index'),
		);
        $this->view($this->cms_module_path().'/{{ front_view_import_name }}',$data, {{ navigation_name }});
    }

    public function get_data(){
        $module_path = $this->cms_module_path();
    	// only accept ajax request
    	if(!$this->input->is_ajax_request()) $this->cms_redirect();
    	// get page and keyword parameter
    	$keyword = $this->input->post('keyword');
    	$page = $this->input->post('page');
    	if(!$keyword) $keyword = '';
    	if(!$page) $page = 0;
    	// get data from model
    	$this->load->model('{{ project_name }}/{{ front_model_import_name }}');
    	$this->{{ model_name }} = new {{ model_name }}();
    	$result = $this->{{ model_name }}->get_data($keyword, $page);
    	$data = array(
    		'result'=>$result,
    		'allow_navigate_backend' => $this->cms_allow_navigate({{ backend_navigation_name }}),
			'backend_url' => site_url($this->cms_module_path().'/{{ back_controller_import_name }}/index'),
    	);
    	$this->load->view($this->cms_module_path().'/{{ front_view_partial_import_name }}',$data);
	}

}