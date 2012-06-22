<head>
	<link rel="stylesheet" type="text/css" href="assets/style.css"></link>
	<style type="text/css">
		div#if_error{
			display: none;
		}
		div#if_no_error{
			display: block;
		}
	</style>
	<script type="text/javascript" src="../assets/nocms/js/jquery.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			if($('#php_error').html()!=""){
				$('div#if_error').show();
				$('div#if_no_error').hide();
			}else{
				$('div#php_error').hide();
				$('div#if_error').hide();
				$('div#if_no_error').show();
			}
		});
	</script>
</head>
<body>	
	<div id="container">
		<div id="php_error" class="message"><?php
		    include('function.php');
		    check_all(true);		    
		?></div>
		
		<h1>Installation finished</h1>
		
		<div id="if_no_error">
			<p><strong>Installation succeed !!!</strong><br />
			You still have some little (but important) things to do:
			</p>
			<ol>    
			    <li><strong>Delete your installation folder or make it inaccessible</strong><br />
			        Why? Because anyone can change database setting and admin user easily.
			        Quiet easy, just as easy as what you have done
			    </li>
			    <li><strong>Change configuration files into readOnly (chmod 755 /application/config/database.php)</strong><br />
			        Why? Because anyone with ftp access or whatever can change the content of the file manually
			        <code>
			        	chmod 755 -R ./application/config/<br />
			        	chmod 755 ./.htaccess<br />
			        </code>
			    </li>
			    <li><strong>Click</strong> <a href="../"><strong>here</strong></a> to start your adventure using No-CMS<br />
			    </li>
			</ol>
		</div>
		
		<div id="if_error">
			<p><strong>Unfortunately, there are some error occurred</strong><br /> 
			Here are some suggestions:
			</p>
			<ol>
				<li>Delete everything, start the installation from the beginning. This time ensure you have enter valid parameters</li>
				<li>Do manual installation:
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
				<li>Ask in forum, open an issue in github, or put comment in No-CMS blog</li>
			</ol>
		</div>
		
		
	    CodeIgniter forum member can visit  No-CMS thread here:  <a href="http://codeigniter.com/forums/viewthread/209171/">http://codeigniter.com/forums/viewthread/209171/</a><br />
	    Github user can visit No-CMS repo:  <a href="https://github.com/goFrendiAsgard/No-CMS/">https://github.com/goFrendiAsgard/No-CMS/</a><br />
	    While normal people can visit No-CMS blog: <a href="http://www.getnocms.com/">http://www.getnocms.com/</a><br />
	    That's all. Start your new adventure with No-CMS !!!
	</div>
</body>