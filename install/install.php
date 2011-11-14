<?php
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
    
    //connection
    $db_connection = mysql_connect($db_server.':'.$db_port,$db_username,$db_password);
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
?>
<style type="text/css">

	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body{
		margin: 0 15px 0 15px;
	}
	
	p.footer{
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}
	
	#container{
		margin: 10px;
		border: 1px solid #D0D0D0;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
	}
</style>
<h1>Installation finished</h1>
<p>If there is no error message, then you have install Neo-CMS successfully.
But <i>(depend on your situation)</i> you still have some more things to do:
<ul>
    <li>Do Installation manually<br />        
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
    <li>Delete your installation folder<br />
        Why? Because anyone can change database setting and admin user easily.
        Quiet easy, just as easy as what you have done
    </li>
    <li>Change application/config/database.php into readOnly (chmod 777 /application/config/database.php)<br />
        Why? Because anyone with ftp access or whatever can change the content of the file manually
    </li>
    <li>Click <a href="../">here</a><br />
        Why? Because you have do everything needed, you want to get rid of this page, 
        and start your new adventure with Neo-CMS
    </li>
</ul>
</p>