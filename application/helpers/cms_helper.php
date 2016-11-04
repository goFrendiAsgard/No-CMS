<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function __cms_config($key, $value = NULL, $delete = FALSE, $file_name, $config_load_alias){
    if(!file_exists($file_name)) return FALSE;
    $pattern = array();
    $pattern[] = '/(\$config\[(\'|")'.$key.'(\'|")\] *= *")(.*?)(";)/si';
    $pattern[] = "/(".'\$'."config\[('|\")".$key."('|\")\] *= *')(.*?)(';)/si";

    if(strpos($value, '\';') !== FALSE || strpos($value, '";') !== FALSE){
        $delete = TRUE;
        $value = NULL;
    }

    if($delete){
        $replacement = '';
        $str = file_get_contents($file_name);
        $str = preg_replace($pattern, $replacement, $str);
        @chmod($file_name,0777);
        if(is_writable($file_name) && strpos($str, '<?php') !== FALSE && strpos($str, '$config') !== FALSE){
            // strip php tag
            $executable_str = trim(trim(trim($str), '<?php'),'?>');
            $valid_executable = @eval($executable_str . PHP_EOL . 'return TRUE;');
            // Only write str if it is valid PHP
            if($valid_executable){
                @file_put_contents($file_name, $str);
                @chmod($file_name,0555);
            }
        }
        return FALSE;
    }else{
        if($value === NULL){

            // enforce refresh
            if(function_exists('opcache_invalidate')){
                opcache_invalidate($file_name);
            }
            include($file_name);

            if(!isset($config)){
                $config = array();
            }
            if(key_exists($key, $config)){
                $value = stripslashes($config[$key]);
            }else{
                $value = '';
            }
            return $value;
        }else{
            $str = file_get_contents($file_name);
            $replacement = '${1}'.addslashes($value).'${5}';
            $found = FALSE;
            foreach($pattern as $single_pattern){
                if(preg_match($single_pattern,$str)){
                    $found = TRUE;
                    break;
                }
            }
            if(!$found){
                $str .= PHP_EOL.'$config[\''.$key.'\'] = \''.addslashes($value).'\';';
            }
            else{
                $str = preg_replace($pattern, $replacement, $str);
            }
            @chmod($file_name,0777);
            if(is_writable($file_name) && strpos($str, '<?php') !== FALSE && strpos($str, '$config') !== FALSE){
                // strip php tag
                $executable_str = trim(trim(trim($str), '<?php'),'?>');
                $valid_executable = @eval($executable_str . PHP_EOL . 'return TRUE;');
                // Only write str if it is valid php
                if($valid_executable){
                    @file_put_contents($file_name, $str, LOCK_EX);
                    @chmod($file_name,0555);
                }
            }
            return $value;
        }
    }

}

/**
 * @author goFrendiAsgard
 * @param string $key
 * @param string $value
 * @param bool $delete
 * @desc get/set cms configuration value. if delete == TRUE, then the key will be deleted
 */
function cms_config($key, $value = NULL, $delete = FALSE){
    if(defined('CMS_SUBSITE') && CMS_SUBSITE != ''){
        $file_name = APPPATH.'config/site-'.CMS_SUBSITE.'/cms_config.php';
    }else if(!defined('CMS_RESET_OVERRIDDEN_SUBSITE') && defined('CMS_OVERRIDDEN_SUBSITE') && CMS_OVERRIDDEN_SUBSITE != ''){
        $file_name = APPPATH.'config/site-'.CMS_OVERRIDDEN_SUBSITE.'/cms_config.php';
    }else{
        $file_name = APPPATH.'config/main/cms_config.php';
    }
    $config_load_alias = 'cms_config';
    return __cms_config($key, $value, $delete, $file_name, $config_load_alias);
}

/**
 * @author goFrendiAsgard
 * @param string $key
 * @param string $value
 * @param bool $delete
 * @desc get/set module configuration value. if delete == TRUE, then the key will be deleted
 */
function cms_module_config($module_directory, $key, $value = NULL, $delete = FALSE){
    $main_config_file_name = FCPATH.'modules/'.$module_directory.'/config/module_config.php';
    if(!file_exists($main_config_file_name)){
        $content  = "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');".PHP_EOL.PHP_EOL;
        $content .= '$config[\'module_table_prefix\']  = \'\';'.PHP_EOL;
        $content .= '$config[\'module_prefix\']        = \'\';'.PHP_EOL;
        file_put_contents($main_config_file_name, $content);
    }
    if(defined('CMS_SUBSITE') && CMS_SUBSITE != ''){
        $file_name = FCPATH.'modules/'.$module_directory.'/config/module_config_'.CMS_SUBSITE.'.php';
        if(!file_exists($file_name)){
            copy($main_config_file_name, $file_name);
        }
    }else if((!defined('CMS_RESET_OVERRIDDEN_SUBSITE') && defined('CMS_OVERRIDDEN_SUBSITE') && CMS_OVERRIDDEN_SUBSITE != '')){
        $file_name = FCPATH.'modules/'.$module_directory.'/config/module_config_'.CMS_OVERRIDDEN_SUBSITE.'.php';
        if(!file_exists($file_name)){
            copy($main_config_file_name, $file_name);
        }
    }else{
        $file_name = $main_config_file_name;
    }
    $config_load_alias = $module_directory.'/module_config';
    return __cms_config($key, $value, $delete, $file_name, $config_load_alias);
}


function cms_table_prefix($new_prefix = NULL){
    return cms_config('__cms_table_prefix', $new_prefix);
}

function cms_module_table_prefix($module_directory, $new_prefix = NULL){
    $module_table_prefix = cms_module_config($module_directory, 'module_table_prefix', $new_prefix);
    if($module_table_prefix == ''){
        return cms_table_prefix();
    }else{
        return cms_table_name($module_table_prefix);
    }
}

function cms_module_prefix($module_directory, $new_prefix = NULL){
    return $module_table_prefix = cms_module_config($module_directory, 'module_prefix', $new_prefix);
}

function cms_table_name($table_name, $table_prefix = NULL){
    if($table_prefix === NULL){
        $table_prefix = cms_table_prefix();
    }
    if($table_prefix != ''){
        return $table_prefix.'_'.$table_name;
    }else{
        return $table_name;
    }
}

function cms_module_table_name($module_directory, $table_name){
    $table_prefix = cms_module_table_prefix($module_directory);
    if($table_prefix != ''){
        return $table_prefix.'_'.$table_name;
    }else{
        return $table_name;
    }
}

function cms_module_navigation_name($module_directory, $name){
    $module_prefix = cms_module_prefix($module_directory);
    if($module_prefix != ''){
        return $module_prefix.'_'.$name;
    }else{
        return $name;
    }
}

function cms_half_md5($data){
    return md5(md5(md5($data)));
}
function cms_md5($data, $chipper = NULL){
    $chipper = $chipper === NULL? cms_config('__cms_chipper') : $chipper;
    $return = crypt(cms_half_md5(cms_half_md5($data)), $chipper);
    return $return;
}
function _xor($data, $chipper=array(1,2,3,4,5,6,7)){
    while(count($chipper) < count($data)){
        $chipper = array_merge($chipper, $chipper);
    }
    $new_data = array();
    for($i=0; $i<count($data); $i++){
        $new_data[] = ($data[$i]+0) ^ ($chipper[$i]+0);
    }
    return $new_data;
}

function cms_encode($data, $chipper = NULL){
    $chipper = $chipper === NULL? cms_config('__cms_chipper') : $chipper;
    $data .= '';
    $data_array = array();
    $chipper_array = array();
    for($i=0; $i<strlen($data); $i++){
        $data_array[] = ord($data[$i]);
    }
    for($i=0; $i<strlen($chipper); $i++){
        $chipper_array[] = ord($chipper[$i]);
    }
    $encoded_array = _xor($data_array, $chipper_array);
    $encoded_str = '';
    foreach($encoded_array as $char){
        $encoded_str .= urlencode(chr($char));
    }
    return $encoded_str;
}
function cms_decode($data, $chipper = NULL){
    $chipper = $chipper === NULL? cms_config('__cms_chipper') : $chipper;
    $data = urldecode($data);
    $data_array = array();
    for($i=0; $i<strlen($data); $i++){
        $data_array[] = ord($data[$i]);
    }

    $chipper_array = array();
    for($i=0; $i<strlen($chipper); $i++){
        $chipper_array[] = ord($chipper[$i]);
    }
    $decoded_array = _xor($data_array, $chipper_array);
    $decoded_str = '';
    for($i=0; $i<count($decoded_array); $i++){
        $decoded_str .= chr($decoded_array[$i]);
    }
    return $decoded_str;
}

function get_decoded_cookie($key, $chipper){
    $key = cms_encode($key, $chipper);
    if(!array_key_exists($key, $_COOKIE)){
        $key = urldecode($key);
    }
    if(array_key_exists($key, $_COOKIE)){
        return cms_decode($_COOKIE[$key], $chipper);
    }
    return NULL;
}

// This is going to be used by hook function to call any CMS_Model's function
function cms_function(){
    // get number of arguments
    $numargs = func_num_args();
    // The first argument is method, the rest are args
    $arg_list = func_get_args();
    $method = $arg_list[0];
    $args = array();
    for($i=1; $i<$numargs; $i++){
        $args[] = $arg_list[$i];
    }
    // get ci instance
    $ci = &get_instance();
    if(property_exists($ci, 'no_cms_autoupdate_model')){
        return call_user_func_array(array($ci->no_cms_autoupdate_model, $method), $args);
    }else if(property_exists($ci, 'no_cms_model')){
        return call_user_func_array(array($ci->no_cms_model, $method), $args);
    }else{
        return NULL;
    }
}

function build_md_html_table($md_key, $detail_table_caption, $field_captions = array(), $build_action_column=TRUE, $allow_add=TRUE, $action_html = ''){
    $th = '';
    foreach($field_captions as $caption){
        $th .= '<th>'.$caption.'</th>';
    }
    if($build_action_column){
        $th .= '<th>{{ language:Action }}';
    }

    $action = '';
    if($action_html != ''){
        $action .= $action_html;
    }
    if($allow_add){
        $action .= '<span id="md_field_'.$md_key.'_add" class="add btn btn-default">
                <i class="glyphicon glyphicon-plus-sign"></i> Add '.$detail_table_caption.'
            </span>';
    }
    if($action != ''){
        $action = '<div class="fbuton">' . $action . '</div>';
    }

    $html =
        '<style type="text/css">
            #md_table_'.$md_key.' .chzn-drop input[type="text"]{
                max-width:240px;
            }
            #md_table_'.$md_key.' th:last-child, #md_table_'.$md_key.' td:last-child{
                width: 60px;
            }
        </style>

        <div id="md_table_'.$md_key.'_container">
            <div id="no-datamd_table_'.$md_key.'">No data</div>
            <table id="md_table_'.$md_key.'" class="table table-striped table-bordered" style="display:none">
                <thead>
                    <tr>'.$th.'</tr>
                </thead>
                <tbody>
                    <!-- the data presentation be here -->
                </tbody>
            </table>'.$action.'
            <br />
            <!-- This is the real input. If you want to catch the data, please json_decode this input\'s value -->
            <input id="md_real_field_'.$md_key.'_col" name="md_real_field_'.$md_key.'_col" type="hidden" />
        </div>';
    return $html;
}

function build_md_global_variable_script($md_key, $primary_key_name, $date_format, $result, $options){
    $js =
        'var DATE_FORMAT = \''.$date_format.'\';
        var OPTIONS_'.$md_key.' = '.json_encode($options).';
        var RECORD_INDEX_'.$md_key.' = 0;
        var DATA_'.$md_key.' = {update:new Array(), insert:new Array(), delete:new Array()};

        /* Populate DATA */
        var old_data = '.json_encode($result).';
        for(var i=0; i<old_data.length; i++){
            var row          = old_data[i];
            var record_index = i;
            var primary_key  = row[\''.$primary_key_name.'\'];
            var data         = row;
            delete data[\''.$primary_key_name.'\'];
            DATA_'.$md_key.'.update.push({
                \'record_index\' : record_index,
                \'primary_key\'  : primary_key,
                \'data\'         : data,
            });
        }';
    return $js;
}

function build_md_event_script($md_key, $insert_url, $update_url, $allow_delete = TRUE){
    $js =
        '$(document).ready(function(){

            function apply_add_table_row_'.$md_key.'(data){
                // Hide div#no-data
                $("#no-datamd_table_'.$md_key.'").hide();
                $("#md_table_'.$md_key.'").show();
                // Get input
                var inputs = [];
                if(typeof(add_table_row_'.$md_key.') === "function"){
                    inputs = add_table_row_'.$md_key.'(data);
                }
                // Build row
                var html = \'<tr id="md_field_'.$md_key.'_tr_\'+RECORD_INDEX_'.$md_key.'+\'" class="md_field_'.$md_key.'_tr">\';
                for(var i=0; i<inputs.length; i++){
                    // Build columns
                    var input = inputs[i];
                    html += \'<td>\' + input + \'</td>\';
                }
                // determine "allow_delete"
                var allow_delete = '.($allow_delete?'true':'false').';
                // Build action list
                var actions = [];
                if(typeof(add_table_row_'.$md_key.'_action) === "function"){
                    actions = add_table_row_'.$md_key.'_action(data);
                }
                // build action buttons
                var html_action = \'\';
                for(var i=0; i<actions.length; i++){
                    var action = actions[i];
                    html_action = action;
                }
                if(html_action != \'\' || allow_delete){
                    html += \'<td>\';
                    html += html_action;
                    if(allow_delete){
                        // Build delete button
                        html += \'<span class="delete-icon btn btn-default md_field_'.$md_key.'_delete" record_index="\'+RECORD_INDEX_'.$md_key.'+\'">\';
                        html += \'<i class="glyphicon glyphicon-minus-sign"></i>\';
                        html += \'</span>\';
                    }
                    html += \'</td>\';
                }
                // End of row
                html += \'</tr>\';
                // Add row to table
                $(\'#md_table_'.$md_key.' tbody\').append(html);
                __mutate_input(\'md_table_'.$md_key.'\');
            }

            // INITIALIZATION
            __synchronize(\'md_real_field_'.$md_key.'_col\', DATA_'.$md_key.');
            for(var i=0; i<DATA_'.$md_key.'.update.length; i++){
                apply_add_table_row_'.$md_key.'(DATA_'.$md_key.'.update[i].data);
                RECORD_INDEX_'.$md_key.'++;
            }

            // ADD EVENT
            $(\'#md_field_'.$md_key.'_add\').click(function(){
                // new data
                var data = default_row_'.$md_key.'();

                // insert data to the DATA_'.$md_key.'
                DATA_'.$md_key.'.insert.push({
                    \'record_index\' : RECORD_INDEX_'.$md_key.',
                    \'primary_key\'  : \'\',
                    \'data\'         : data,
                });

                // add table\'s row
                apply_add_table_row_'.$md_key.'(data);
                // add  by 1
                RECORD_INDEX_'.$md_key.'++;

                // synchronize to the md_real_field_'.$md_key.'_col
                __synchronize(\'md_real_field_'.$md_key.'_col\', DATA_'.$md_key.');
            });


            // DELETE EVENT
            $(\'body\').on(\'click\', \'.md_field_'.$md_key.'_delete\', function(){
                var record_index = $(this).attr(\'record_index\');
                // remove the component
                $(\'#md_field_'.$md_key.'_tr_\'+record_index).remove();

                var record_index_found = false;
                for(var i=0; i<DATA_'.$md_key.'.insert.length; i++){
                    if(DATA_'.$md_key.'.insert[i].record_index == record_index){
                        record_index_found = true;
                        // delete element from insert
                        DATA_'.$md_key.'.insert.splice(i,1);
                        break;
                    }
                }
                if(!record_index_found){
                    for(var i=0; i<DATA_'.$md_key.'.update.length; i++){
                        if(DATA_'.$md_key.'.update[i].record_index == record_index){
                            record_index_found = true;
                            var primary_key = DATA_'.$md_key.'.update[i].primary_key;
                            // delete element from update
                            DATA_'.$md_key.'.update.splice(i,1);
                            // add it to delete
                            DATA_'.$md_key.'.delete.push({
                                \'record_index\':record_index,
                                \'primary_key\':primary_key
                            });
                            break;
                        }
                    }
                }
                __synchronize(\'md_real_field_'.$md_key.'_col\', DATA_'.$md_key.');
            });


            // CHANGE EVENT
            $(\'body\').on(\'change\', \'.md_field_'.$md_key.'_col\', function(){
                var value = $(this).val();
                var column_name = $(this).attr(\'column_name\');
                var record_index = $(this).attr(\'record_index\');
                var record_index_found = false;
                // date picker
                if($(this).hasClass(\'datepicker-input\')){
                    value = js_date_to_php(value);
                }
                else if($(this).hasClass(\'datetime-input\')){
                    value = js_datetime_to_php(value);
                }
                if(typeof(value)==\'undefined\'){
                    value = \'\';
                }
                for(var i=0; i<DATA_'.$md_key.'.insert.length; i++){
                    if(DATA_'.$md_key.'.insert[i].record_index == record_index){
                        record_index_found = true;
                        // insert value
                        eval(\'DATA_'.$md_key.'.insert[\'+i+\'].data.\'+column_name+\' = \'+JSON.stringify(value)+\';\');
                        break;
                    }
                }
                if(!record_index_found){
                    for(var i=0; i<DATA_'.$md_key.'.update.length; i++){
                        if(DATA_'.$md_key.'.update[i].record_index == record_index){
                            record_index_found = true;
                            // edit value
                            eval(\'DATA_'.$md_key.'.update[\'+i+\'].data.\'+column_name+\' = \'+JSON.stringify(value)+\';\');
                            break;
                        }
                    }
                }
                __synchronize(\'md_real_field_'.$md_key.'_col\', DATA_'.$md_key.');
            });


        });

        // RESET DETAIL FIELD ON SAVE
        $(document).ajaxSuccess(function(event, xhr, settings) {
            if (settings.url == "'.$insert_url.'") {
                response = $.parseJSON(xhr.responseText);
                if(response.success == true){
                    DATA_'.$md_key.' = {update:new Array(), insert:new Array(), delete:new Array()};
                    $(\'#md_table_'.$md_key.' tr\').not(\':first\').remove();
                    __synchronize(\'md_real_field_'.$md_key.'_col\', DATA_'.$md_key.');
                }
            }else{
                // avoid detail inserted twice on update
                update_url = "'.$update_url.'";
                if(settings.url.substr(0, update_url.length) == update_url){
                    response = $.parseJSON(xhr.responseText);
                    if(response.success == true){
                        $(\'#form-button-save\').attr(\'disabled\', \'disabled\');
                        $(\'#save-and-go-back-button\').attr(\'disabled\', \'disabled\');
                        $(\'#cancel-button\').attr(\'disabled\', \'disabled\');
                    }
                }
            }
        });';
    return $js;
}

function rrmdir($dir) {
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file){
            if ($file != "." && $file != ".."){
                rrmdir("$dir/$file");
            }
        }
        @chmod($dir, 777);
        rmdir($dir);
    }
    else if (file_exists($dir)){
        @chmod($dir, 777);
        unlink($dir);
    }
}

function rcopy($src, $dst) {
    if (file_exists ( $dst )){
        @chmod($dst, 777);
        rrmdir ( $dst );
    }
    if (is_dir ( $src )) {
        mkdir ( $dst );
        $files = scandir ( $src );
        foreach ( $files as $file )
            if ($file != "." && $file != "..")
                rcopy ( "$src/$file", "$dst/$file" );
    } else if (file_exists ( $src )){
        copy ( $src, $dst );
    }
}

function parse_record($record, $config=array()){
    $record = (array)$record; // cast to associative array
    $record_template = array_key_exists('record_template', $config)? $config['record_template']: '';
    $backend_url = array_key_exists('backend_url', $config)? $config['backend_url']: NULL;
    $allow_edit = array_key_exists('allow_edit', $config)? $config['allow_edit']: FALSE;
    $allow_delete = array_key_exists('allow_delete', $config)? $config['allow_delete']: FALSE;
    $primary_key = array_key_exists('primary_key', $config)? $config['primary_key']: 'id';
    // determine if new record template should be created or not
    $create_record_template = $record_template == '';
    // create default record template if necessary
    if($create_record_template){
        $record_template = '<div id="record_{{ record:'.$primary_key.' }}" class="record_container panel panel-default">';
        $record_template .= '<div class="panel-body">';
    }
    // build search and replace
    $search = array();
    $replace = array();
    foreach(array_keys($record) as $key){
        $search[] = '{{ record:'.$key.' }}';
        if(is_array($record[$key]) || is_object($record[$key])){
            $replace[] = '';
        }else{
            $replace[] = $record[$key];
        }
        // add default record template if necessary
        if($create_record_template){
            $record_template .= '<div class="row">';
            $record_template .= '<div class="col-md-4"><strong>'. ucwords(str_replace('_', ' ', $key)) . '</strong></div>';
            $record_template .= '<div class="col-md-8">{{ record:'.$key.' }}</div>';
            $record_template .= '</div>';
        }
    }
    // add default record template if necessary
    if($create_record_template){
        $record_template .= '<div class="edit_delete_record_container pull-right">{{ backend_urls }}</div>';
        $record_template .= '<div style="clear:both;"></div>';
        $record_template .= '</div>'; // end of div.panel-body
        $record_template .= '</div>'; // end of div.record_container
    }
    // build backend urls
    $backend_urls = '';
    if($backend_url != ''){
        if($allow_edit){
            $backend_urls .= '<a href="'.$backend_url.'/edit/'.$record[$primary_key].'" class="btn btn-default edit_record" primary_key="'.$record[$primary_key].'">Edit</a>';
            if($allow_delete){
                $backend_urls .= '&nbsp;';
            }
        }
        if($allow_delete){
            $backend_urls .= '<a href="'.$backend_url.'/delete/'.$record[$primary_key].'" class="btn btn-danger delete_record" primary_key="'.$record[$primary_key].'">Delete</a>';
        }
    }
    // add search and replace pattern
    $search[] = '{{ backend_urls }}';
    $replace[] = $backend_urls;
    return str_replace($search, $replace, $record_template);
}

function create_labeled_form_input($id, $label, $input_html){
    $input_html = str_replace('{{ id }}', $id, $input_html);
    $html = '';
    $html .= '<div class="form-group col-sm-12">';
    $html .= form_label($label, $id, array('class' => 'control-label col-sm-4'));
    $html .= '<div class="col-sm-8">';
    $html .= $input_html;
    $html .= '</div>';
    $html .= '</div>';
    return $html;
}

function build_register_input($secret_code, $user_name, $email, $real_name){
    $html = '';

    $id = $secret_code.'user_name';
    $input_html = form_input($id, $user_name,
        'id="{{ id }}" placeholder="User Name" class="form-control"');
    $html .= create_labeled_form_input($id, '{{ language:User Name }}', $input_html);

    $id = $secret_code.'real_name';
    $input_html = form_input($id, $real_name,
        'id="{{ id }}" placeholder="Real Name" class="form-control"');
    $html .= create_labeled_form_input($id, '{{ language:Real Name }}', $input_html);

    $id = $secret_code.'email';
    $input_html = form_input($id, $email,
        'id="{{ id }}" placeholder="Email" class="form-control"');
    $html .= create_labeled_form_input($id, '{{ language:Email }}', $input_html);


    $id = $secret_code.'password';
    $input_html = form_password($id, '',
        'id="{{ id }}" placeholder="Password" class="form-control"');
    $html .= create_labeled_form_input($id, '{{ language:Password }}', $input_html);

    $id = $secret_code.'confirm_password';
    $input_html = form_password($id, '',
        'id="{{ id }}" placeholder="Password (again)" class="form-control"');
    $html .= create_labeled_form_input($id, '{{ language:Confirm Password }}', $input_html);

    return $html;
}
