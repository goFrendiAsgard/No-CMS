<?php include 'modules/help/assets/toc_include.php' ?>
<h3>Install No-CMS</h3>
<p>Installing No-CMS is very easy. You should provide :</p>
<ul>
	<li>Database Information
		<ul>
			<li>Database Server
				<p>It is about your database server name, it can be IP address or computer's name
				If you install your database server is also your web server, you can provide either 'localhost' or '127.0.0.1' as Database server
				</p>
			</li>
			<li>Port
				<p>In the current version of No-CMS we only support MySQL. The default port for MySQL would be '3306'
				</p>
			</li>
			<li>Username
				<p>To use database, you must ensure that you are authorized to your database server. For authorization sake, you should provide username and password.
				The default value for the username is 'root'. If you use xampp, you can just keep this default value.
				</p>
			</li>
			<li>Password
				<p>The password to access database server, the default is blank, means no password
			</li>
			<li>Database/Schema
				<p>The default database schema is 'no_cms'. The installer will try to make the datatabase schema if it is not exists yet
				</p>
			</li>
		</ul>
	</li>
	<li>Administrator Information
		<ul>
			<li>E mail
				<p>Fill it with your email account, it can be used for your authentication</p>
			</li>
			<li>User name
				<p>Fill it with your desired user name, it will be used for your authentication</p>
			</li>			
			<li>Password
				<p>Fill it with your new password</p>
			</li>			
		</ul>
	</li>
</ul>
