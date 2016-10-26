&lt;?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of {{ controller_name }}
 *
 * @author No-CMS Module Generator
 */

class {{ controller_name }} extends CMS_Front_Controller {
    protected $CONTROLLER_PATH      = '{{ front_controller_import_name }}';
    protected $NAVIGATION_NAME      = '{{ navigation_name }}';
    protected $BACK_CONTROLLER_PATH = '{{ back_controller_import_name }}';
    protected $BACK_NAVIGATION_NAME = '{{ backend_navigation_name }}';

    protected $ADD_PRIVILEGE_NAME           = 'add_{{ stripped_table_name }}';
    protected $EDIT_PRIVILEGE_NAME          = 'edit_{{ stripped_table_name }}';
    protected $DELETE_PRIVILEGE_NAME        = 'delete_{{ stripped_table_name }}';
    protected $EDIT_TEMPLATE_PRIVILEGE_NAME = '{{ record_template_privilege_name }}';

    protected $CONFIG_TEMPLATE_NAME = '{{ record_template_configuration_name }}';

    protected $VIEW_PATH            = '{{ front_view_import_name }}';
    protected $PARTIAL_VIEW_PATH    = '{{ front_view_partial_import_name }}';
    protected $CONFIG_VIEW_PATH     = '{{ front_config_view_name }}';

    protected $MODEL_PATH = '{{ front_model_import_name }}';

    protected $SEARCH_POST_KEYS             = array('keyword');
    protected $SEARCH_POST_DEFAULT_VALUES   = array(
        'keyword' => '',
    );

    public function index(){
        $module_path = $this->cms_module_path();
        $data = $this->_index();
        $this->view($module_path.'/'.$this->VIEW_PATH, $data,
            $this->n($this->NAVIGATION_NAME));
    }

    public function get_data(){
        $module_path = $this->cms_module_path();
        $arg_list = func_get_args();
        $data = call_user_func_array(array($this, '_get_data'), $arg_list);
        $config = array('only_content'=>TRUE);
        $this->view($module_path.'/'.$this->PARTIAL_VIEW_PATH, $data,
            $this->n($this->NAVIGATION_NAME), $config);
    }
}
