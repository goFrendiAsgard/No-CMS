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
	
	function check_db($server, $port, $username, $password){
		$return = array(
				"success"=>true,
				"message"=>""
		);
		 
		$connection = @mysql_connect($server.':'.$port,$username,$password);
		if(!$connection){
			$return["success"] = false;
			$return["message"] .= "Cannot connect to database";
		}else{
			$result = @mysql_query('SHOW VARIABLES LIKE \'have_innodb\';', $connection);
			$row = mysql_fetch_array($result);
			$innodb = $row['Value'];
			if(!$innodb){
				$return["success"] = false;
				$return["message"] .= "Your database doesn't support Innodb";
			}
		}
		 
		return $return;
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
		if(function_exists('curl_version') == "Enabled"){
			$warnings[] = 'CURL is not enabled. Some modules might require it';
		}
		// database
		$result = check_db($db_server, $db_port, $db_username, $db_password);
		if(!$result['success']){
			$success = FALSE;
			$errors[] = $result['message'];
		}
		// writable
		if(!is_writable('../application/config/database.php')){
			$success = FALSE;
			$errors[] = "application/config/database.php is not writeable";
		}
		if(!is_writable('../application/config/routes.php')){
			$success = FALSE;
			$errors[] = "application/config/routes.php is not writeable";
		}
		if(!is_writable('../application/config/config.php')){
			$success = FALSE;
			$errors[] = "application/config/config.php is not writeable";
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
				
				// connection
				$db_connection = mysql_connect($db_server.':'.$db_port,$db_username,$db_password);
				$db_exists = mysql_select_db($db_schema, $db_connection);
				if(!$db_exists){
					mysql_query('CREATE DATABASE '.$db_schema, $db_connection);
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
					mysql_query($query, $db_connection);
				}
				
				if($hide_index != ""){
					// config.php
					$str = file_get_contents('./resources/config.php');
					$str = replace($str,
							array('@index_page'),
							array('')
					);
					file_put_contents('../application/config/config.php', $str);
					 
					// .htaccess
					$pieces = explode('/', $_SERVER["REQUEST_URI"]);
					for ($i=0; $i<2; $i++){
						unset($pieces[count($pieces)-1]);
					}
					$path = '/' . implode('/',$pieces) . '/';
					$str = file_get_contents('./resources/htaccess');
					$str = replace($str,
							array('@rewrite_base'),
							array($path)
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