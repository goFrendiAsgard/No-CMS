&lt;?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of {{ controller_name }}
 *
 * @author No-CMS Module Generator
 */

class {{ controller_name }} extends CMS_Secure_Controller {

    protected function do_override_url_map($URL_MAP){
        $module_path = $this->cms_module_path();
        $navigation_name = $this->n('{{ navigation_name }}');
        $URL_MAP[$module_path.'/{{ front_controller_import_name }}'] = $navigation_name;
        $URL_MAP[$module_path] = $navigation_name;
        $URL_MAP[$module_path.'/{{ front_controller_import_name }}/get_data'] = $navigation_name;
        $URL_MAP[$module_path.'/get_data'] = $navigation_name;
        return $URL_MAP;
    }

    public function index(){
        $module_path = $this->cms_module_path();
        $data = array(
            'allow_navigate_backend'    => $this->cms_allow_navigate($this->n('{{ backend_navigation_name }}')),
            'have_add_privilege'        => $this->cms_have_privilege($this->n('add_{{ stripped_table_name }}')),
            'have_edit_privilege'       => $this->cms_have_privilege($this->n('edit_{{ stripped_table_name }}')),
            'have_delete_privilege'     => $this->cms_have_privilege($this->n('delete_{{ stripped_table_name }}')),
            'backend_url'               => site_url($module_path.'/{{ back_controller_import_name }}/index'),
            'module_path'               => $module_path,
            'first_data'                => Modules::run($module_path.'/{{ front_controller_import_name }}/get_data', 0, '')
        );
        $this->view($module_path.'/{{ front_view_import_name }}',$data,
            $this->n('{{ navigation_name }}'));
    }

    public function get_data($page = 0, $keyword = ''){
        $module_path = $this->cms_module_path();
        // get page and keyword parameter
        $post_keyword   = $this->input->post('keyword');
        $post_page      = $this->input->post('page');
        if($keyword == '' && $post_keyword != NULL) $keyword = $post_keyword;
        if($page == 0 && $post_page != NULL) $page = $post_page;
        // get data from model
        $this->load->model($module_path.'/{{ front_model_import_name }}');
        $result = $this->{{ front_model_import_name }}->get_data($keyword, $page);
        $data = array(
            'result'                 =>$result,
            'allow_navigate_backend' => $this->cms_allow_navigate($this->n('{{ backend_navigation_name }}')),
            'have_add_privilege'     => $this->cms_have_privilege($this->n('add_{{ stripped_table_name }}')),
            'have_edit_privilege'    => $this->cms_have_privilege($this->n('edit_{{ stripped_table_name }}')),
            'have_delete_privilege'  => $this->cms_have_privilege($this->n('delete_{{ stripped_table_name }}')),
            'backend_url'            => site_url($module_path.'/{{ back_controller_import_name }}/index'),
        );
        $config = array('only_content'=>TRUE);
        $this->view($module_path.'/{{ front_view_partial_import_name }}',$data,
           $this->n('{{ navigation_name }}'), $config);
    }

}
