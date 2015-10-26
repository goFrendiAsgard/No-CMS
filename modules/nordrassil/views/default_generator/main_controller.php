&lt;?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for
 *
 * @author No-CMS Module Generator
 */
class {{ main_controller }} extends CMS_Secure_Controller {

    protected function do_override_url_map($URL_MAP){
        $module_path = $this->cms_module_path();
        $navigation_name = $this->n('{{ navigation_parent_name }}');
        $URL_MAP[$module_path.'/'.$module_path] = $navigation_name;
        $URL_MAP[$module_path] = $navigation_name;
        return $URL_MAP;
    }

    public function index(){
    	$data['content'] = $this->cms_submenu_screen($this->n('{{ navigation_parent_name }}'));
        $this->view($this->cms_module_path().'/{{ main_controller }}_index', $data,
            $this->n('{{ navigation_parent_name }}'));
    }
}