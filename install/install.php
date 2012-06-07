<?php
    include('function.php');
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
    
    $check_db = check_db($db_server, $db_port, $db_username, $db_password);
    if(($adm_password == '') || ($adm_password!=$adm_confirmpassword) || 
       (!$check_db["success"]) || !is_writable('../application/config/database.php') || 
       !is_writable('../application/config/routes.php'))
    {
        header('location:index.html');
    }
    
    //database.php
    $str = file_get_contents('./resources/database.php');
    $str = replace($str,
            array('@db_server','@db_port','@db_username','@db_password','@db_schema'),
            array($db_server,$db_port,$db_username,$db_password,$db_schema)
           );
    file_put_contents('../application/config/database.php',$str);
    
    //routes.php
    $str = file_get_contents('./resources/routes.php');
    file_put_contents('../application/config/routes.php',$str);
    //routes.php
    $str = file_get_contents('./resources/config.php');
    file_put_contents('../application/config/config.php',$str);
    
    //connection
    $db_connection = @mysql_connect($db_server.':'.$db_port,$db_username,$db_password);
    $db_exists = mysql_select_db($db_schema, $db_connection);
    if(!$db_exists){
        mysql_query('CREATE DATABASE '.$db_schema, $db_connection);
        mysql_select_db($db_schema, $db_connection);
    }
    
    //database.sql    
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
    	//config.php
    	$str = file_get_contents('./resources/config.php');
    	$str = replace($str,
    			array('@index_page'),
    			array('')
    		   );
    	file_put_contents('../application/config/config.php', $str);
    	
    	//.htaccess
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
    	//config.php
    	$str = file_get_contents('./resources/config.php');
    	$str = replace($str,
    			array('@index_page'),
    			array('index.php')
    	);
    	file_put_contents('../application/config/config.php', $str);
    	
    	//.htaccess
    	file_put_contents('../.htaccess', '');
    }
    
    
?>
<link rel="stylesheet" type="text/css" href="assets/style.css"></link>
<h1>Installation finished</h1>
<p>If there is no error message, then you have install No-CMS successfully.
But <i>(depend on your situation)</i> you still have some more things to do:
<ul>    
    <li><strong>Delete your installation folder</strong><br />
        Why? Because anyone can change database setting and admin user easily.
        Quiet easy, just as easy as what you have done
    </li>
    <li><strong>Change application/config/database.php into readOnly (chmod 755 /application/config/database.php)</strong><br />
        Why? Because anyone with ftp access or whatever can change the content of the file manually
    </li>
    <li><strong>In case of no error appeared : Click</strong> <a href="../"><strong>here</strong></a><br />
        If there is no error appeared, and you have do the above steps, then there is nothing more you should do.
        Start your new adventure with No-CMS
    </li>
    <li><strong>In case of failure Installation : Do Installation manually</strong><br />        
        Why? Because you look some error appeared. Here is the manual installation detail:
        <ol>
            <li>Open application/config/database.php</li>
            <li>Edit these lines as your database connection configuration:
            <code>
                $db['default']['hostname'] = 'your_server:your_port';<br />
                $db['default']['username'] = 'username';<br />
                $db['default']['password'] = 'password';<br />
                $db['default']['database'] = 'schema';
            </code>
            </li>
            <li>Import database from install/resources/database.sql</li>
        </ol>
    </li>
</ul>
</p>
