<head>
	<?php
	include('function.php');
	$result = check_all();
	if(!$result['success']){
		header('location:index.php');
	}

	$site_url = '../';
	if(!isset($_POST['hide_index'])){
		$site_url .= 'index.php/';
	}
	$adm_username = '';
	if(isset($_POST['adm_username'])){
		$adm_username = $_POST['adm_username'];
	}
	$adm_password = '';
	if(isset($_POST['adm_password'])){
		$adm_password = $_POST['adm_password'];
	}
	?>
	<link rel="stylesheet" type="text/css" href="assets/style.css"></link>
	<link rel="stylesheet" type="text/css" href="../assets/bootstrap/css/bootstrap.min.css" />

	<style type="text/css">
		div#if_error{
			display: none;
		}
		div#if_no_error{
			display: none;
		}
	</style>
	<script type="text/javascript" src="../assets/nocms/js/jquery.tools.min.js"></script>
	<script src="../assets/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){

			if($('#php_error').html()!=""){
				$('div#if_error').show();
				$('div#if_no_error').hide();
			}else{
				$('div#php_error').hide();
				$('div#if_error').hide();
				var modules =  ['wysiwyg', 'nordrassil', 'blog'];
				var done = 0;
				var site_url = '<?php echo $site_url;?>';
				for(var i=0; i<modules.length; i++){
					var module = modules[i];
					$.ajax({
						'url': site_url+module+'/install/install/',
						'type': 'POST',
						'dataType': 'json',
						'async': true,
						'data':{
								'silent' : true,
								'identity': '<?php echo $adm_username;?>',
								'password': '<?php echo $adm_password;?>'
							},
						'success': function(response){
								if(!response['success']){
									console.log('error installing '+response['module_path']);
								}
							},
						'error': function(response){
								console.log('error send request');
							},
						'complete' : function(){
								done ++;
								if(done == modules.length){
									$('div#if_no_error').show();
									$('div#installation_process').hide();
								}
							}
					});
				}

			}
		});
		function install_module(){

		}
	</script>
</head>
<body>
	<div id="container-fluid">
		<div class="row-fluid">
			<div class="navbar navbar-fixed-top">
		      <div class="navbar-inner">
		        <div class="container-fluid">
		            <a class="brand" href="#">Installation finished</a>
		        </div>
		      </div>
		    </div>

		    <div id="installation_process" class="well span9">
		    	Installing Modules &nbsp; <img id="img_loader" src="./assets/ajax-loader.gif" />
		    </div>

			<div id="if_no_error" class="well span9">
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
				    <li><strong>Click</strong> <a class="btn btn-primary" href="../"><strong>here</strong></a> to start your adventure using No-CMS<br />
				    </li>
				</ol>
			</div>

			<div id="if_error" class="well span9">
				<p><strong>Unfortunately, there are some error occurred</strong><br />
				Here are some suggestions:
				</p>
				<ol>
					<li>Delete everything, start the installation from the beginning. This time ensure you have enter valid parameters</li>
					<li>Do manual installation:
						<ol>
							<li>Open <b>application/config/database.php</b>, Edit these lines as your database connection configuration:
								<code>
									$db['default']['hostname'] = 'your_server:your_port';<br />
							        $db['default']['username'] = 'username';<br />
							        $db['default']['password'] = 'password';<br />
							        $db['default']['database'] = 'schema';
								</code>
							</li>
							<li>Import database from <b>install/resources/database.sql</b></li>
							<li>Execute this query:
								<code>
									UPDATE cms_user SET `user_name` = 'admin', `password` = '21232f297a57a5a743894a0e4a801fc3', `email` = 'admin@admin.com' WHERE `user_id` = 1
								</code>
							</li>
							<li>By performing query above, your user name and password is:
								<ul>
									<li><b>Username : </b>admin</li>
									<li><b>Password : </b>admin</li>
								</ul>
							</li>
						</ol>
					</li>
					<li>If manual installation doesn't work, you can ask in forum, open an issue in github, or put comment in No-CMS blog</li>
				</ol>
				<div id="php_error" class="message"><?php
				    check_all(true);
				?></div>
			</div>

			<p class="well span9">
			    CodeIgniter forum member can visit  No-CMS thread here:  <a href="http://codeigniter.com/forums/viewthread/209171/">http://codeigniter.com/forums/viewthread/209171/</a><br />
			    Github user can visit No-CMS repo:  <a href="https://github.com/goFrendiAsgard/No-CMS/">https://github.com/goFrendiAsgard/No-CMS/</a><br />
			    While normal people can visit No-CMS blog: <a href="http://www.getnocms.com/">http://www.getnocms.com/</a><br />
			    In case of you've found a critical bug, you can also email me at <a href="mailto:gofrendiasgard@gmail.com">gofrendiasgard@gmail.com</a><br />
			    That's all. Start your new adventure with No-CMS !!!
		    </p>
	    </div>
	</div>
</body>