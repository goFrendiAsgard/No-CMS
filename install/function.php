<?php
	function get_input($key){
		$result = isset($_POST[$key])?$_POST[$key]:"";
		return $result;
	}
	
	function get_secure_input($key){
		return addslashes(get_input($key));
	}
	
	function replace($str,$search,$replace){
		if(count($search)==count($replace)){
			for($i=0; $i<count($search); $i++){
				$str = str_replace($search, $replace, $str);
			}
		}
		return $str;
	}
	
	function check_db($server, $port, $username, $password, $schema){
		$return = array(
				"success"=>true,
				"error_message"=>"",
				"warning_message"=>"",
		);
		 
		$connection = @mysql_connect($server.':'.$port,$username,$password);
		if(!$connection){
			$return["success"] = false;
			$return["error_message"] .= "Cannot connect to database";
		}else{
			$result = @mysql_query('SHOW VARIABLES LIKE \'have_innodb\';', $connection);
			$row = mysql_fetch_array($result);
			$innodb = $row['Value'];
			if(!$innodb){
				$return["success"] = false;
				$return["error_message"] .= "Your database doesn't support Innodb";
			}
		}
		
		if($return["success"]){
			$db_exists = mysql_select_db($schema, $connection);
			if(!$db_exists){
				$SQL = "show grants for `$username`@`$server`;";
				$result = mysql_query($SQL, $connection);
				if($result === false){
					$return["error_message"] = 'Cannot check database privilege';
					$return["success"] = false;
				}else{
					$privilege_exists = false;
					while($row = mysql_fetch_row($result)){
						if(strpos($row[0],'ALL PRIVILEGES')>=0){
							$privilege_exists = true;
							break;
						}
						if(strpos($row[0],'CREATE,')>=0){
							$privilege_exists = true;
							break;
						}
					}
					if(!$privilege_exists){
						$return["error_message"] = 'No create database privilege, please select the alredy exists one';
						$return["success"] = false;
					}
				}
				
			}			
		}
		 
		return $return;
	}
	
	function show_mysql_error($query){
		$search = array("<", ">");
		$replace = array("&lt;", "&gt;");
		echo "<p><b>A Fatal MySQL error occured</b>.\n<br />
			<b>Query:</b> <pre>".replace($query, $search, $replace)."</pre><br />\n
			<b>Error:</b> (" . mysql_errno() . ") " .mysql_error()."</p>";
		return true;
	}
	
	function exec_sql($query, $db_connection){
		mysql_query($query, $db_connection) or show_mysql_error($query);
	}
	
	function get_base_url(){
		$pieces = explode('/', $_SERVER["REQUEST_URI"]);
		for ($i=0; $i<2; $i++){
			unset($pieces[count($pieces)-1]);
		}
		$path = implode('/',$pieces) . '/';
		return $path;
	}
	
	function check_all($install=NULL){
		$db_server = get_input("db_server");
		$db_port = get_input("db_port");
		$db_username = get_input("db_username");
		$db_password = get_input("db_password");
		$db_schema = get_input("db_schema");
		
		$adm_username = get_secure_input("adm_username");
		$adm_email = get_secure_input("adm_email");
		$adm_realname = get_secure_input("adm_realname");
		$adm_password = get_secure_input("adm_password");
		$adm_confirmpassword = get_secure_input("adm_confirmpassword");
		
		$hide_index = get_secure_input("hide_index");
		
		// Main program
		
		$success = true;
		$errors = array();
		$warnings = array();
		// curl
		if(!in_array  ('curl', get_loaded_extensions())){
			$warnings[] = 'CURL is not enabled. Some modules might require it';
		}
		// database
		$result = check_db($db_server, $db_port, $db_username, $db_password, $db_schema);
		if(!$result['success']){
			$success = FALSE;
		}
		if($result['error_message']!=''){
			$errors[] = $result['error_message'];
		}
		if($result['warning_message']!=''){
			$warnings[] = $result['warning_message'];
		}
		// writable
		if(!is_writable('../application/config/database.php')){
			$success = FALSE;
			$errors[] = "application/config/database.php is not writable";
		}
		if(!is_writable('../application/config/routes.php')){
			$success = FALSE;
			$errors[] = "application/config/routes.php is not writable";
		}
		if(!is_writable('../application/config/config.php')){
			$success = FALSE;
			$errors[] = "application/config/config.php is not writable";
		}
		if(!is_writable('../assets/grocery_crud/js/jquery_plugins/config/jquery.ckeditor.config.js')){
			$success = FALSE;
			$errors[] = 'assets/grocery_crud/js/jquery_plugins/config/jquery.ckeditor.config.js is not writable';
		}
		if(!is_writable('../')){
			if($hide_index != ""){
				$success = FALSE;
				$errors[] = "No-CMS directory is not writeable, we can't make .htaccess there";
			}
		}
		// admin password
		if($adm_password == ""){
			$success = FALSE;
			$errors[] = "Admin's password is empty";
		}
		if($adm_password != $adm_confirmpassword){
			$success = FALSE;
			$errors[] = "Admin's password confirmation doesn't match";
		}
		
		// if not installed, than just return the warnings, errors and success
		if(!isset($install)){
			$data = array(
					"success" => $success,
					"errors" => $errors,
					"warnings" => $warnings,
			);			
			return $data;
		}else{ // installation			
			if(!$success){ // redirect if not success
				header('location:index.html');
			}else{ // perform installation
				
				// connection
				$db_connection = mysql_connect($db_server.':'.$db_port,$db_username,$db_password);
				$db_exists = mysql_select_db($db_schema, $db_connection);
				if(!$db_exists){
					$query = 'CREATE DATABASE '.$db_schema;
					exec_sql($query, $db_connection);
					mysql_select_db($db_schema, $db_connection);
				}
				
				// database.sql
				$sql = file_get_contents('./resources/database.sql');
				$sql = replace($sql,
						array('@adm_username','@adm_email','@adm_password','@adm_realname'),
						array($adm_username,$adm_email,md5($adm_password),$adm_realname)
				);
				$queries = explode('/*split*/', $sql);
				foreach($queries as $query){
					exec_sql($query, $db_connection);
				}
				
				// database.php
				$str = file_get_contents('./resources/database.php');
				$str = replace($str,
						array('@db_server','@db_port','@db_username','@db_password','@db_schema'),
						array($db_server,$db_port,$db_username,$db_password,$db_schema)
				);
				file_put_contents('../application/config/database.php',$str);
				
				// routes.php
				$str = file_get_contents('./resources/routes.php');
				file_put_contents('../application/config/routes.php',$str);
				
				// jquery.ckeditor.config.js
				$str = file_get_contents('./resources/jquery.ckeditor.config.js');
				$base_path = get_base_url();
				$str = replace($str,
						array('@base_path'),
						array($base_path)
				);
				file_put_contents('../assets/grocery_crud/js/jquery_plugins/config/jquery.ckeditor.config.js', $str);
				
				
				if($hide_index != ""){
					// config.php
					$str = file_get_contents('./resources/config.php');
					$str = replace($str,
							array('@index_page'),
							array('')
					);
					file_put_contents('../application/config/config.php', $str);
					 
					// .htaccess					
					$str = file_get_contents('./resources/htaccess');
					$str = replace($str,
							array('@base_path'),
							array($base_path)
					);
					file_put_contents('../.htaccess', $str);
				}else{
					// config.php
					$str = file_get_contents('./resources/config.php');
					$str = replace($str,
							array('@index_page'),
							array('index.php')
					);
					file_put_contents('../application/config/config.php', $str);
					 
					// .htaccess
					file_put_contents('../.htaccess', '');
				}
			}
			
		}
	}
?>