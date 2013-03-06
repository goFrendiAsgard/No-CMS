<?php
function get_input($key)
{
    $result = isset($_POST[$key]) ? $_POST[$key] : "";
    return $result;
}

function get_secure_input($key)
{
    return addslashes(get_input($key));
}

function replace($str, $search, $replace)
{
    if (count($search) == count($replace)) {
        for ($i = 0; $i < count($search); $i++) {
            $str = str_replace($search, $replace, $str);
        }
    }
    return $str;
}

function get_current_url()
{
    if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
        $protocol = 'https://';
    } else {
        $protocol = 'http://';
    }

    $url = $protocol . $_SERVER['HTTP_HOST'];

    // use port if non default
    $url .= isset($_SERVER['SERVER_PORT']) && (($protocol === 'http://' && $_SERVER['SERVER_PORT'] != 80) || ($protocol === 'https://' && $_SERVER['SERVER_PORT'] != 443)) ? ':' . $_SERVER['SERVER_PORT'] : '';
    $url .= $_SERVER['PHP_SELF'];

    // return current url
    return $url;
}

function get_callback_url($provider)
{
    $current_url      = get_current_url();
    $current_url_arr  = explode('/', $current_url);
    $stripped_url_arr = array();
    for ($i = 0; $i < count($current_url_arr) - 2; $i++) {
        $stripped_url_arr[] = $current_url_arr[$i];
    }
    $stripped_url = implode('/', $stripped_url_arr);
    $callback_url = $stripped_url . '/index.php/main/hauth/endpoint/?hauth.done=' . $provider;
    return $callback_url;
}

function get_test_path($segments)
{
    $path              = $_SERVER['SCRIPT_FILENAME'];
    $path_arr          = explode('/', $path);
    $stripped_path_arr = array();
    for ($i = 0; $i < count($path_arr) - 1; $i++) {
        $stripped_path_arr[] = $path_arr[$i];
    }
    $stripped_path = implode('/', $stripped_path_arr);
    $test_path     = $stripped_path . '/test/' . $segments;
    return $test_path;
}

function get_test_url($segments)
{
    $current_url      = get_current_url();
    $current_url_arr  = explode('/', $current_url);
    $stripped_url_arr = array();
    for ($i = 0; $i < count($current_url_arr) - 1; $i++) {
        $stripped_url_arr[] = $current_url_arr[$i];
    }
    $stripped_url = implode('/', $stripped_url_arr);
    $test_url     = $stripped_url . '/test/' . $segments;
    return $test_url;
}

function get_test_rewrite_base()
{
    $rewrite_base_arr          = explode('/', $_SERVER["REQUEST_URI"]);
    $stripped_rewrite_base_arr = array();
    for ($i = 0; $i < count($rewrite_base_arr) - 1; $i++) {
        $stripped_rewrite_base_arr[] = $rewrite_base_arr[$i];
    }
    $stripped_rewrite_base = implode('/', $stripped_rewrite_base_arr);
    $rewrite_base          = $stripped_rewrite_base . '/test/';
    return $rewrite_base;
}

function check_db($server, $port, $username, $password, $schema)
{
    $return = array(
        "success" => true,
        "error_message" => "",
        "warning_message" => ""
    );

    $connection = @mysql_connect($server . ':' . $port, $username, $password);
    if (!$connection) {
        $return["success"] = false;
        $return["error_message"] .= "Cannot connect to database";
    } else {
        $result = @mysql_query('SHOW VARIABLES LIKE \'have_innodb\';', $connection);
        $row    = mysql_fetch_array($result);
        $innodb = $row['Value'];
        if (!$innodb) {
            $return["success"] = false;
            $return["error_message"] .= "Your database doesn't support Innodb";
        }
    }

    if ($return["success"]) {
        if ($schema == '') {
            $return["error_message"] = 'Database Schema is empty';
            $return["success"]       = false;
        } else {
            $db_exists = @mysql_select_db($schema, $connection);
            if (!$db_exists) {
                $SQL    = "show grants for `$username`@`$server`;";
                $result = @mysql_query($SQL, $connection);
                if ($result === false) {
                    $return["error_message"] = 'Cannot check database privilege';
                    $return["success"]       = false;
                } else {
                    $privilege_exists = false;
                    while ($row = mysql_fetch_row($result)) {
                        if ((strpos($row[0], 'ALL PRIVILEGES') > -1 || strpos($row[0], 'CREATE,') > -1) && strpos($row[0], 'ON *.*')) {
                            $privilege_exists = true;
                            break;
                        }
                    }
                    if (!$privilege_exists) {
                        $return["error_message"] = 'No create database privilege, please select the already exists one';
                        $return["success"]       = false;
                    }
                }

            }
        }

    }

    return $return;
}

function show_mysql_error($query)
{
    $search  = array(
        "<",
        ">"
    );
    $replace = array(
        "&lt;",
        "&gt;"
    );
    echo "<p><b>A Fatal MySQL error occured</b>.\n<br />
			<b>Query:</b> <pre>" . replace($query, $search, $replace) . "</pre><br />\n
			<b>Error:</b> (" . mysql_errno() . ") " . mysql_error() . "</p>";
    return true;
}

function exec_sql($query, $db_connection)
{
    mysql_query($query, $db_connection) or show_mysql_error($query);
}

function get_base_url()
{
    $pieces = explode('/', $_SERVER["REQUEST_URI"]);
    for ($i = 0; $i < 2; $i++) {
        unset($pieces[count($pieces) - 1]);
    }
    $path = implode('/', $pieces) . '/';
    return $path;
}

function is_mod_rewrite_active()
{
    $mod_rewrite = NULL;
    if (function_exists('apache_get_modules')) {
        $modules = apache_get_modules();
        if (in_array('mod_rewrite', $modules)) {
            $mod_rewrite = TRUE;
        }
    }
    if (!isset($mod_rewrite) && isset($_SERVER["HTTP_MOD_REWRITE"])) {
        if (strtoupper($_SERVER["HTTP_MOD_REWRITE"]) == "ON") {
            $mod_rewrite = TRUE;
        }
    }
    if (!isset($mod_rewrite)) {
        if (strtoupper(getenv('HTTP_MOD_REWRITE')) == "ON") {
            $mod_rewrite = TRUE;
        }
    }
    if (!isset($mod_rewrite) && in_array('curl', get_loaded_extensions())) {
        file_put_contents(get_test_path('.htaccess'), $htaccess_content);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, get_test_url('test_mod_rewrite'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        unlink(get_test_path('.htaccess'));
        if ($response == 'ok') {
            $mod_rewrite = TRUE;
        }
    }
    if (!isset($mod_rewrite)) {
        $htaccess_content = '<IfModule mod_rewrite.c>' . PHP_EOL;
        $htaccess_content .= '   Options +FollowSymLinks -Indexes' . PHP_EOL;
        $htaccess_content .= '   RewriteEngine On' . PHP_EOL;
        $htaccess_content .= '   RewriteBase ' . get_test_rewrite_base() . PHP_EOL;
        $htaccess_content .= '   # fake rule to verify if mod rewriting works (if there are unbearable restrictions..)' . PHP_EOL;
        $htaccess_content .= '   RewriteRule ^test_mod_rewrite$    test.php' . PHP_EOL;
        $htaccess_content .= '</IfModule>';
        file_put_contents(get_test_path('.htaccess'), $htaccess_content);
        $response = @file_get_contents(get_test_url('test_mod_rewrite'));
        unlink(get_test_path('.htaccess'));
        if ($response == 'ok') {
            $mod_rewrite = TRUE;
        }
    }
    if (!isset($mod_rewrite)) {
        $mod_rewrite = FALSE;
    }
    return $mod_rewrite;
}

function check_all($install = NULL)
{
    $db_server           = get_input("db_server");
    $db_port             = get_input("db_port");
    $db_username         = get_input("db_username");
    $db_password         = get_input("db_password");
    $db_schema           = get_input("db_schema");
    $db_table_prefix     = get_input("db_table_prefix");

    $adm_username        = get_secure_input("adm_username");
    $adm_email           = get_secure_input("adm_email");
    $adm_realname        = get_secure_input("adm_realname");
    $adm_password        = get_secure_input("adm_password");
    $adm_confirmpassword = get_secure_input("adm_confirmpassword");

    $hide_index          = get_secure_input("hide_index");
    $gzip_compression    = get_secure_input("gzip_compression");

    $auth_enable_facebook         = get_secure_input("auth_enable_facebook");
    $auth_facebook_app_id         = get_secure_input("auth_facebook_app_id");
    $auth_facebook_app_secret     = get_secure_input("auth_facebook_app_secret");
    $auth_enable_twitter          = get_secure_input("auth_enable_twitter");
    $auth_twitter_app_key         = get_secure_input("auth_twitter_app_key");
    $auth_twitter_app_secret      = get_secure_input("auth_twitter_app_secret");
    $auth_enable_google           = get_secure_input("auth_enable_google");
    $auth_google_app_id           = get_secure_input("auth_google_app_id");
    $auth_google_app_secret       = get_secure_input("auth_google_app_secret");
    $auth_enable_yahoo            = get_secure_input("auth_enable_yahoo");
    $auth_yahoo_app_id            = get_secure_input("auth_yahoo_app_id");
    $auth_yahoo_app_secret        = get_secure_input("auth_yahoo_app_secret");
    $auth_enable_linkedin         = get_secure_input("auth_enable_linkedin");
    $auth_linkedin_app_key        = get_secure_input("auth_linkedin_app_key");
    $auth_linkedin_app_secret     = get_secure_input("auth_linkedin_app_secret");
    $auth_enable_myspace          = get_secure_input("auth_enable_myspace");
    $auth_myspace_app_key         = get_secure_input("auth_myspace_app_key");
    $auth_myspace_app_secret      = get_secure_input("auth_myspace_app_secret");
    $auth_enable_foursquare       = get_secure_input("auth_enable_foursquare");
    $auth_foursquare_app_id       = get_secure_input("auth_foursquare_app_id");
    $auth_foursquare_app_secret   = get_secure_input("auth_foursquare_app_secret");
    $auth_enable_windows_live     = get_secure_input("auth_enable_windows_live");
    $auth_windows_live_app_id     = get_secure_input("auth_windows_live_app_id");
    $auth_windows_live_app_secret = get_secure_input("auth_windows_live_app_secret");
    $auth_enable_open_id          = get_secure_input("auth_enable_open_id");
    $auth_enable_aol              = get_secure_input("auth_enable_aol");

    // Main program

    $success  = true;
    $errors   = array();
    $warnings = array();

    // database
    $result = check_db($db_server, $db_port, $db_username, $db_password, $db_schema);
    if (!$result['success']) {
        $success = FALSE;
    }
    if ($result['error_message'] != '') {
        $errors[] = $result['error_message'];
    }
    if ($result['warning_message'] != '') {
        $warnings[] = $result['warning_message'];
    }
    // writable
    if (!is_writable('../assets/caches')) {
        $success  = FALSE;
        $errors[] = "Asset cache directory (assets/caches) is not writable";
    }
    if (!is_writable('../application/config')) {
        $success  = FALSE;
        $errors[] = "application/config is not writable";
    }
    if (!is_writable('../assets/grocery_crud/js/jquery_plugins/config/jquery.ckeditor.config.js')) {
        $success  = FALSE;
        $errors[] = 'assets/grocery_crud/js/jquery_plugins/config/jquery.ckeditor.config.js is not writable';
    }
    if (!is_writable('./')) {
        $success  = FALSE;
        $errors[] = 'install directory is not writable';
    }
    if (!is_writable('./test/')) {
        $success  = FALSE;
        $errors[] = 'install/test directory is not writable';
    }
    if (!is_writable('../application/logs')) {
        $success  = FALSE;
        $errors[] = "Log directory (application/logs) is not writable";
    }
    if ($hide_index !== "") {
        if (!is_writable('../')) {
            $success  = FALSE;
            $errors[] = "No-CMS directory is not writeable, we can't make .htaccess there";
        }
        if (!is_mod_rewrite_active()) {
            $success  = FALSE;
            $errors[] = "mod_rewrite is not enabled";
        }
    }

    // admin password
    if ($adm_password == "") {
        $success  = FALSE;
        $errors[] = "Admin's password is empty";
    }
    if ($adm_password != $adm_confirmpassword) {
        $success  = FALSE;
        $errors[] = "Admin's password confirmation doesn't match";
    }

    // third party authentication
    if ($auth_enable_facebook !== "" || $auth_enable_twitter !== "" || $auth_enable_google !== "" || $auth_enable_yahoo !== "" || $auth_enable_linkedin !== "" || $auth_enable_myspace !== "" || $auth_enable_foursquare !== "" || $auth_enable_windows_live !== "" || $auth_enable_open_id !== "" || $auth_enable_aol !== "") {
        // curl
        if (!in_array('curl', get_loaded_extensions())) {
            $success  = FALSE;
            $errors[] = 'Third party authentication require php-curl, but it is not enabled';
        }
        // hybridauthlib configuration file
        if (!is_writable('../application/config/hybridauthlib.php')) {
            $success  = FALSE;
            $errors[] = "application/config/hybridauthlib.php is not writable";
        }
        // hybridauthlib log file
        if (!is_writable('../application/logs/hybridauth.log')) {
            $success  = FALSE;
            $errors[] = "application/logs/hybridauth.log is not writable";
        }
    }

    // if not installed, than just return the warnings, errors and success
    if (!isset($install)) {
        $data = array(
            "success" => $success,
            "errors" => $errors,
            "warnings" => $warnings
        );
        return $data;
    } else { // installation
        if (!$success) { // redirect if not success
            return false;
        } else { // perform installation

            // connection
            $db_connection = mysql_connect($db_server . ':' . $db_port, $db_username, $db_password);
            $db_exists     = mysql_select_db($db_schema, $db_connection);
            if (!$db_exists) {
                $query = 'CREATE DATABASE ' . $db_schema . ' DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;';
                exec_sql($query, $db_connection);
                mysql_select_db($db_schema, $db_connection);
            }

            // cms_config
            $cms_config = file_get_contents('./resources/cms_config.php.txt');
            $cms_config = replace($cms_config, array(
                '{{ db_table_prefix }}',
            ), array(
                $db_table_prefix,
            ));
            file_put_contents('../application/config/cms_config.php', $cms_config);
            @chmod('../application/config/cms_config.php', 0555);

            // database.sql
            $sql     = file_get_contents('./resources/database.sql.txt');
            $sql     = replace($sql, array(
                '{{ adm_username }}',
                '{{ adm_email }}',
                '{{ adm_password }}',
                '{{ adm_realname }}',
                '{{ db_table_prefix }}',
            ), array(
                $adm_username,
                $adm_email,
                md5($adm_password),
                $adm_realname,
                $db_table_prefix == ''? '': $db_table_prefix.'_',
            ));
            $queries = explode('/*split*/', $sql);
            foreach ($queries as $query) {
                exec_sql($query, $db_connection);
            }

            // database.php
            $str = file_get_contents('./resources/database.php.txt');
            $str = replace($str, array(
                '{{ db_server }}',
                '{{ db_port }}',
                '{{ db_username }}',
                '{{ db_password }}',
                '{{ db_schema }}'
            ), array(
                $db_server,
                $db_port,
                $db_username,
                $db_password,
                $db_schema
            ));
            file_put_contents('../application/config/database.php', $str);
            @chmod('../application/config/database.php', 0555);

            // routes.php
            $str = file_get_contents('./resources/routes.php.txt');
            file_put_contents('../application/config/routes.php', $str);
            @chmod('../application/config/routes.php', 0555);

            // jquery.ckeditor.config.js
            $str       = file_get_contents('./resources/jquery.ckeditor.config.js.txt');
            $base_path = get_base_url();
            $str       = replace($str, array(
                '{{ base_path }}'
            ), array(
                $base_path
            ));
            file_put_contents('../assets/grocery_crud/js/jquery_plugins/config/jquery.ckeditor.config.js', $str);
            @chmod('../assets/grocery_crud/js/jquery_plugins/config/jquery.ckeditor.config.js', 0555);


            // config.php
            $key_config     = array();
            $replace_config = array();
            $str = file_get_contents('./resources/config.php.txt');
            $str = replace($str, array(
                '{{ gzip }}',
                '{{ index_page }}',
                '{{ db_table_prefix }}'
            ), array(
                $gzip_compression != ''? 'TRUE' : 'FALSE',
                $hide_index != ''? '' : 'index.php',
                $db_table_prefix == ''? '': $db_table_prefix.'_',
            ));
            file_put_contents('../application/config/config.php', $str);
            @chmod('../application/config/config.php', 0555);

            // .htaccess
            if ($hide_index !== "") {
                $str = file_get_contents('./resources/htaccess.txt');
                $str = replace($str, array(
                    '{{ base_path }}'
                ), array(
                    $base_path
                ));
                file_put_contents('../.htaccess', $str);
                @chmod('../.htaccess', 0555);
            } else {
                $str  = '<IfModule mod_php5.c>' . PHP_EOL;
                $str .= '	php_value output_handler none' . PHP_EOL;
                $str .= '	php_flag register_globals off' . PHP_EOL;
                $str .= '	php_flag safe_mode off' . PHP_EOL;
                $str .= '</IfModule>' . PHP_EOL;
                $str .= '<IfModule mod_php4.c>' . PHP_EOL;
                $str .= '	php_value output_handler none' . PHP_EOL;
                $str .= '	php_flag register_globals off' . PHP_EOL;
                $str .= '	php_flag safe_mode off' . PHP_EOL;
                $str .= '</IfModule>';
                file_put_contents('../.htaccess', $str);
                @chmod('../.htaccess', 0555);
            }

            // hybridauthlib.php
            if ($auth_enable_facebook !== "" || $auth_enable_twitter !== "" || $auth_enable_google !== "" || $auth_enable_yahoo !== "" || $auth_enable_linkedin !== "" || $auth_enable_myspace !== "" || $auth_enable_foursquare !== "" || $auth_enable_windows_live !== "" || $auth_enable_open_id !== "" || $auth_enable_aol !== "") {
                $key_config     = array(
                    '{{ facebook_app_id }}',
                    '{{ facebook_app_secret }}',
                    '{{ twitter_app_key }}',
                    '{{ twitter_app_secret }}',
                    '{{ google_app_id }}',
                    '{{ google_app_secret }}',
                    '{{ yahoo_app_id }}',
                    '{{ yahoo_app_secret }}',
                    '{{ linkedin_app_key }}',
                    '{{ linkedin_app_secret }}',
                    '{{ myspace_app_key }}',
                    '{{ myspace_app_secret }}',
                    '{{ foursquare_app_id }}',
                    '{{ foursquare_app_secret }}',
                    '{{ windows_live_app_id }}',
                    '{{ windows_live_app_secret }}'
                );
                $replace_config = array(
                    $auth_facebook_app_id,
                    $auth_facebook_app_secret,
                    $auth_twitter_app_key,
                    $auth_twitter_app_secret,
                    $auth_google_app_id,
                    $auth_google_app_secret,
                    $auth_yahoo_app_id,
                    $auth_yahoo_app_secret,
                    $auth_linkedin_app_key,
                    $auth_linkedin_app_secret,
                    $auth_myspace_app_key,
                    $auth_myspace_app_secret,
                    $auth_foursquare_app_id,
                    $auth_foursquare_app_secret,
                    $auth_windows_live_app_id,
                    $auth_windows_live_app_secret
                );


                if ($auth_enable_facebook != "") {
                    $key_config[]     = '{{ facebook_enabled }}';
                    $replace_config[] = 'TRUE';
                } else {
                    $key_config[]     = '{{ facebook_enabled }}';
                    $replace_config[] = 'FALSE';
                }

                if ($auth_enable_twitter != "") {
                    $key_config[]     = '{{ twitter_enabled }}';
                    $replace_config[] = 'TRUE';
                } else {
                    $key_config[]     = '{{ twitter_enabled }}';
                    $replace_config[] = 'FALSE';
                }

                if ($auth_enable_google != "") {
                    $key_config[]     = '{{ google_enabled }}';
                    $replace_config[] = 'TRUE';
                } else {
                    $key_config[]     = '{{ google_enabled }}';
                    $replace_config[] = 'FALSE';
                }

                if ($auth_enable_yahoo != "") {
                    $key_config[]     = '{{ yahoo_enabled }}';
                    $replace_config[] = 'TRUE';
                } else {
                    $key_config[]     = '{{ yahoo_enabled }}';
                    $replace_config[] = 'FALSE';
                }

                if ($auth_enable_linkedin != "") {
                    $key_config[]     = '{{ linkedin_enabled }}';
                    $replace_config[] = 'TRUE';
                } else {
                    $key_config[]     = '{{ linkedin_enabled }}';
                    $replace_config[] = 'FALSE';
                }

                if ($auth_enable_myspace != "") {
                    $key_config[]     = '{{ myspace_enabled }}';
                    $replace_config[] = 'TRUE';
                } else {
                    $key_config[]     = '{{ myspace_enabled }}';
                    $replace_config[] = 'FALSE';
                }

                if ($auth_enable_foursquare != "") {
                    $key_config[]     = '{{ foursquare_enabled }}';
                    $replace_config[] = 'TRUE';
                } else {
                    $key_config[]     = '{{ foursquare_enabled }}';
                    $replace_config[] = 'FALSE';
                }

                if ($auth_enable_windows_live != "") {
                    $key_config[]     = '{{ windows_live_enabled }}';
                    $replace_config[] = 'TRUE';
                } else {
                    $key_config[]     = '{{ windows_live_enabled }}';
                    $replace_config[] = 'FALSE';
                }

                if ($auth_enable_open_id != "") {
                    $key_config[]     = '{{ open_id_enabled }}';
                    $replace_config[] = 'TRUE';
                } else {
                    $key_config[]     = '{{ open_id_enabled }}';
                    $replace_config[] = 'FALSE';
                }

                if ($auth_enable_aol != "") {
                    $key_config[]     = '{{ aol_enabled }}';
                    $replace_config[] = 'TRUE';
                } else {
                    $key_config[]     = '{{ aol_enabled }}';
                    $replace_config[] = 'FALSE';
                }

                $str = file_get_contents('./resources/hybridauthlib.php.txt');
                $str = replace($str, $key_config, $replace_config);
                file_put_contents('../application/config/hybridauthlib.php', $str);
                @chmod('../application/config/config.php', 0555);
            }

            // put htaccess in install directory
            file_put_contents('.htaccess', 'Deny from all');
            @chmod('.htaccess', 0555);
            return true;
        }


    }
}
?>