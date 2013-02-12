<?php	
	include('function.php');
	
	$server_name = $_SERVER["SERVER_NAME"];
	$google_callback_url = get_callback_url('Google');
	$foursquare_callback_url = get_callback_url('Foursquare');	
?>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/style.css" />
    <link rel="stylesheet" type="text/css" href="../assets/bootstrap/css/bootstrap.min.css" />

    <script type="text/javascript" src="../assets/nocms/js/jquery.tools.min.js"></script>
    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>    
    <script type="text/javascript">
        var DATABASE_CLICKED = true;
        var ADMIN_CLICKED = false;
        var PREFERENCE_CLICKED = false;
        var AUTHENTICATION_CLICKED = false;
        var SUCCESS = false;
        var REQUEST
        var RUNNING_REQUEST = false;
        
        $(document).ready(function(){
            check_all();  
            $(".input").keyup(function(event){
                check_all();
            });
            $(".input").change(function(event){
                check_all();
            });
            
            $('a[href="#database"]').click(function(){
                $('fieldset#admin').hide();
                $('fieldset#preference').hide();
                $('fieldset#authentication').hide();
                $('fieldset#database').slideDown('slow');
                DATABASE_CLICKED = true;
                show_hide_button()
                return false;
            });
            
            $('a[href="#admin"]').click(function(){
            	$('fieldset#database').hide();
            	$('fieldset#preference').hide();
            	$('fieldset#authentication').hide();
            	$('fieldset#admin').slideDown('slow');
            	ADMIN_CLICKED = true;
            	show_hide_button();
            	return false;
            });
            
            $('a[href="#preference"]').click(function(){
                $('fieldset#database').hide();
                $('fieldset#admin').hide();
                $('fieldset#authentication').hide();
                $('fieldset#preference').slideDown('slow');
                PREFERENCE_CLICKED = true;
                show_hide_button();
                return false;
            });
            
            $('a[href="#authentication"]').click(function(){
                $('fieldset#database').hide();
                $('fieldset#admin').hide();
                $('fieldset#preference').hide();
                $('fieldset#authentication').slideDown('slow');
                AUTHENTICATION_CLICKED = true;
                show_hide_button();
                return false;
            });
            
            $('#btn_install').click(function(){
            	$('#btn_install').hide();
            	$('#img_loader_install').show();
            	return true;
            });
            
        });
        
        function show_hide_button(){
        	if(SUCCESS && DATABASE_CLICKED && ADMIN_CLICKED && PREFERENCE_CLICKED && AUTHENTICATION_CLICKED){
                $(".button_install").show();
                $('.button_install').removeAttr('disabled');  
            }else{
                $(".button_install").hide();
                $('.button_install').attr('disabled', 'disabled');
            }
        }
        
        function check_all(){
        	$('#button_install').attr('disabled', 'disabled');
        	if(RUNNING_REQUEST){
                REQUEST.abort();
            }
        	$('#div_loader_message').show();
        	RUNNING_REQUEST = true;
        	REQUEST = $.ajax({
                type : "POST",
                url : "check_all.php",
                dataType: "json",
                async : true,
                data : {
                    db_server : $("#db_server").val(),
                    db_port : $("#db_port").val(),
                    db_username : $("#db_username").val(),
                    db_password : $("#db_password").val(),
                    db_schema : $("#db_schema").val(),
                    adm_password : $("#adm_password").val(),
                    adm_confirmpassword : $("#adm_confirmpassword").val(),
                    hide_index : $("#hide_index").attr('checked')? $("#hide_index").val(): '',
					auth_enable_facebook : $("#auth_enable_facebook").attr('checked')? $("#auth_enable_facebook").val(): '',
					auth_enable_twitter : $("#auth_enable_twitter").attr('checked')? $("#auth_enable_twitter").val(): '',
					auth_enable_google : $("#auth_enable_google").attr('checked')? $("#auth_enable_google").val(): '',
					auth_enable_yahoo : $("#auth_enable_yahoo").attr('checked')? $("#auth_enable_yahoo").val(): '',
					auth_enable_linkedin : $("#auth_enable_linkedin").attr('checked')? $("#auth_enable_linkedin").val(): '',
					auth_enable_myspace : $("#auth_enable_myspace").attr('checked')? $("#auth_enable_myspace").val(): '',
					auth_enable_foursquare : $("#auth_enable_foursquare").attr('checked')? $("#auth_enable_foursquare").val(): '',
					auth_enable_windows_live : $("#auth_enable_windows_live").attr('checked')? $("#auth_enable_windows_live").val(): '',
					auth_enable_open_id : $("#auth_enable_open_id").attr('checked')? $("#auth_enable_open_id").val(): '',
					auth_enable_aol : $("#auth_enable_aol").attr('checked')? $("#auth_enable_aol").val(): '',
                },
                success : function(response){
                    SUCCESS = response.success;
                    var warnings = response.warnings;
                    var errors = response.errors;
                    
                    var message = ''
                    if(errors.length>0){
                    	message += '<b>ERRORS</b> (You should fix these in order to install No-CMS) : ';
                    	message += '<ul>';
                    	for(i=0; i<errors.length; i++){
                    		message += '<li>'+errors[i]+'</li>';
                    	}
                    	message += '</ul>';
                    }
                    if(warnings.length>0){
                    	message += '<b>WARNINGS</b> (There might be errors after installation) : ';
                        message += '<ul>';
                        for(i=0; i<warnings.length; i++){
                            message += '<li>'+warnings[i]+'</li>';
                        }
                        message += '</ul>';
                    }
                    $("#infoMessage").html(message);
                    if(message != ''){
                    	$('#infoMessage').show();
                    }else{
                    	$('#infoMessage').hide();
                    }
                    $('#div_loader_message').hide();
                    show_hide_button();
                    
                }
            });
        }
        
    </script>
    
</head>
<body>
<div class="container-fluid">
	<div class="row-fluid">
	    <div class="navbar navbar-fixed-top">
	      <div class="navbar-inner">
	        <div class="container-fluid">
	            <a class="brand" href="#">Install No-CMS on your server</a>
	            <div class="nav-collapse">
	               <ul class="nav">
	                   <li><a href="#database">Database Settings</a></li>
	                   <li><a href="#admin">No-CMS Administrator Settings</a></li>
	                   <li><a href="#preference">Preferences</a></li>
	                   <li><a href="#authentication">Third Party Authentication</a></li>
	               </ul>
	            </div> 
	        </div>  	          
	      </div>
	    </div>
	    <div class="span8">
	        <form class="form-horizontal well" action="install.php" method="post" accept-charset="utf-8">
			    <fieldset id="database">
			        <legend>Step #1 Database Settings</legend>
			        <div class="control-group">
			           <label class="control-label" for="db_server">Server</label>
			           <div class="controls">
			               <input type="text" id="db_server" name="db_server" value="localhost" class="input-xlarge input" />
			               <p class="help-block">Server name (e.g: 'localhost', '127.0.0.1', 'http://yourdomain.com')</p>
			           </div>
			        </div>
			        <div class="control-group">
			           <label class="control-label" for="db_port">Port</label>
			           <div class="controls">
			               <input type="text" id="db_port" name="db_port" value="3306" class="input-xlarge input" />
			               <p class="help-block">MySQL port, usually 3306</p>
			           </div>
			        </div>
			        <div class="control-group">
			           <label class="control-label" for="db_username">Username</label>
			           <div class="controls">
			               <input type="text" id="db_username" name="db_username" value="root" class="input-xlarge input" />
			               <p class="help-block">MySQL username</p>
			           </div>
			        </div>
			        <div class="control-group">
			           <label class="control-label" for="db_password">Password</label>
			           <div class="controls">
			               <input type="password" id="db_password" name="db_password" value="" class="input-xlarge input" />
			               <p class="help-block">MySQL password</p>
			           </div>
			        </div>
			        <div class="control-group">
			           <label class="control-label" for="db_schema">Schema</label>
			           <div class="controls">
			               <input type="text" id="db_schema" name="db_schema" value="no_cms" class="input-xlarge input" />
			               <p class="help-block">Database schema (will be created if not exists)</p>
			           </div>
			        </div>
			        <div>                    
	                    <a class="btn btn-primary" href="#admin">Next Step</a>
	                </div>
			    </fieldset>
			    
			    <fieldset id="admin">
			        <legend>Step #2 No-CMS Administrator Settings</legend>
			        <div class="control-group">
			           <label class="control-label" for="adm_email">E mail</label>
			           <div class="controls">
			               <input type="text" id="adm_email" name="adm_email" value="admin@admin.com" class="input-xlarge input" />
			               <p class="help-block">No-CMS administrator email</p>
			           </div>
			        </div>
			        <div class="control-group">
                       <label class="control-label" for="adm_username">Username</label>
                       <div class="controls">
                           <input type="text" id="adm_username" name="adm_username" value="admin" class="input-xlarge input" />
                           <p class="help-block">admin's username</p>
                       </div>
                    </div>
                    <div class="control-group">
                       <label class="control-label" for="adm_realname">Real Name</label>
                       <div class="controls">
                           <input type="text" id="adm_realname" name="adm_realname" value="Me Gusta" class="input-xlarge input" />
                           <p class="help-block">admin's realname</p>
                       </div>
                    </div>
                    <div class="control-group">
                       <label class="control-label" for="adm_password">Password</label>
                       <div class="controls">
                           <input type="password" id="adm_password" name="adm_password" value="" class="input-xlarge input" />
                           <p class="help-block">admin's password</p>
                       </div>
                    </div>
                    <div class="control-group">
                       <label class="control-label" for="adm_confirmpassword">Confirm Password</label>
                       <div class="controls">
                           <input type="password" id="adm_confirmpassword" name="adm_confirmpassword" value="" class="input-xlarge input" />
                           <p class="help-block">admin's password again</p>
                       </div>
                    </div>
                    <div>                   
	                    <a class="btn" href="#database">Back</a>
	                    <a class="btn btn-primary" href="#preference">Next Step</a>
	                </div>
			    </fieldset> 
			    
			    <fieldset id="preference">
			        <legend>Step #3 Preferences</legend>
			        <div class="control-group">
                       <label class="control-label" for="hide_index">Hide Index.php</label>
                       <div class="controls">
                           <input type="checkbox" id="hide_index" name="hide_index" class="input-xlarge input" value="true" />
                           <p class="help-block">(Hide 'index.php' from url, please make sure that mod_rewrite is activated)</p>
                       </div>
                    </div>
                    <div class="control-group">
                       <label class="control-label" for="gzip_compression">Use GZIP compression</label>
                       <div class="controls">
                           <input type="checkbox" id="gzip_compression" name="gzip_compression" class="input-xlarge input" value="true" />
                           <p class="help-block">(For compression to work, nothing can be sent before the output buffer is called by the output class.  Do not 'echo' any values with compression enabled)</p>
                       </div>
                    </div>
                    <div>
	                    <a class="btn" href="#admin">Back</a>
	                    <a class="btn btn-primary" href="#authentication">Next Step</a>	                    
	                </div>
			    </fieldset>
			    <fieldset id="authentication">
			        <legend>Step #4 Third Party Authentication</legend>
			        
			        <div style="text-align:right;">
			        	<legend>Facebook <img src="./assets/icons/facebook.png"/></legend>			        	
			        </div>
			        <div class="control-group">                       
                       <label class="control-label" for="auth_enable_facebook">Allow Facebook Authentication</label>
                       <div class="controls">
                           <input type="checkbox" id="auth_enable_facebook" name="auth_enable_facebook" class="input-xlarge input" value="true" />
                           <p class="help-block">
                           		To Allow Facebook Authentication:
								<ol>
									<li>Go to <a target="__blank" href="https://www.facebook.com/developers/">https://www.facebook.com/developers/</a> and create a new application.</li>
									<li>Fill out any required fields such as the application name and description.</li>
									<li>Put your website domain in the <b>Site Url</b> field. It should match with the current hostname (<b><?php echo $server_name; ?></b>)</li>
									<li>Once you have registered, copy and paste the created application credentials into this setup page.</li>
								</ol>
                           </p>
                       </div>
                    </div>
                    <div class="control-group">
                       <label class="control-label" for="auth_facebook_app_id">Facebook Application ID</label>
                       <div class="controls">
                           <input type="text" id="auth_facebook_app_id" name="auth_facebook_app_id" value="" class="input-xlarge input" />
                           <p class="help-block">Facebook Application ID</p>
                       </div>
                    </div>
                    <div class="control-group">
                       <label class="control-label" for="auth_facebook_app_secret">Facebook Application Secret</label>
                       <div class="controls">
                           <input type="text" id="auth_facebook_app_secret" name="auth_facebook_app_secret" value="" class="input-xlarge input" />
                           <p class="help-block">Facebook Application Secret</p>
                       </div>
                    </div>                    
                    
                    <div style="text-align:right; border-top: 1px solid #E5E5E5; margin-top: 50px;">
			        	<legend>Twitter <img src="./assets/icons/twitter.png"/></legend>			        	
			        </div>
                    <div class="control-group">   
                       <label class="control-label" for="auth_enable_twitter">Allow Twitter Authentication</label>
                       <div class="controls">
                           <input type="checkbox" id="auth_enable_twitter" name="auth_enable_twitter" class="input-xlarge input" value="true" />
                           <p class="help-block">
                           		To Allow Twitter Authentication:
								<ol>
									<li>Go to <a target="__blank" href="https://dev.twitter.com/apps">https://dev.twitter.com/apps</a> and create a new application.</li>
									<li>Fill out any required fields such as the application name and description.</li>
									<li>Put your website domain in the <b>Application Website</b> and <b>Application Callback URL</b> fields. It should match with the current hostname (<b><?php echo $server_name; ?></b>)</li>
									<li>Set the Default Access Type to <b>Read</b>, <b>Write</b>, and <b>Direct Messages</b>.</li>
									<li>Once you have registered, copy and paste the created application credentials into this setup page.</li>
								</ol>
						   </p>
                       </div>
                    </div>
                    <div class="control-group">
                       <label class="control-label" for="auth_twitter_app_id">Twitter Application Key</label>
                       <div class="controls">
                           <input type="text" id="auth_twitter_app_key" name="auth_twitter_app_key" value="" class="input-xlarge input" />
                           <p class="help-block">Twitter Application Key</p>
                       </div>
                    </div>
                    <div class="control-group">
                       <label class="control-label" for="auth_twitter_app_secret">Twitter Application Secret</label>
                       <div class="controls">
                           <input type="text" id="auth_twitter_app_secret" name="auth_twitter_app_secret" value="" class="input-xlarge input" />
                           <p class="help-block">Twitter Application Secret</p>
                       </div>
                    </div>
                    
                    <div style="text-align:right; border-top: 1px solid #E5E5E5; margin-top: 50px;">
			        	<legend>Google <img src="./assets/icons/google.png"/></legend>			        	
			        </div>
                    <div class="control-group">
                       <label class="control-label" for="auth_enable_google">Allow Google Authentication</label>
                       <div class="controls">
                           <input type="checkbox" id="auth_enable_google" name="auth_enable_google" class="input-xlarge input" value="true" />
                           <p class="help-block">
                           	    To Allow Google Authentication:
								<ol>
									<li>Go to <a target="_blank" href="https://code.google.com/apis/console/">https://code.google.com/apis/console/</a> and create a new application.</li>
									<li>Fill out any required fields such as the application name and description.</li>									
									<li>On the <b>"Create Client ID"</b> popup switch to advanced settings by clicking on <b>(more options)</b>.</li>									
									<li>Provide this URL as the <b>Callback URL</b> for your application: <b><?php echo $google_callback_url; ?></b></li>
									<li>Once you have registered, copy and paste the created application credentials into this setup page.</li>
								</ol>
						   </p>
                       </div>
                    </div>
                    <div class="control-group">
                       <label class="control-label" for="auth_google_app_id">Google Application ID</label>
                       <div class="controls">
                           <input type="text" id="auth_google_app_id" name="auth_google_app_id" value="" class="input-xlarge input" />
                           <p class="help-block">Google Application ID</p>
                       </div>
                    </div>
                    <div class="control-group">
                       <label class="control-label" for="auth_google_app_secret">Google Application Secret</label>
                       <div class="controls">
                           <input type="text" id="auth_google_app_secret" name="auth_google_app_secret" value="" class="input-xlarge input" />
                           <p class="help-block">Google Application Secret</p>
                       </div>
                    </div>
                    
					<div style="text-align:right; border-top: 1px solid #E5E5E5; margin-top: 50px;">
			        	<legend>Yahoo <img src="./assets/icons/yahoo.png"/></legend>			        	
			        </div>
                    <div class="control-group">
                       <label class="control-label" for="auth_enable_yahoo">Allow Yahoo Authentication</label>
                       <div class="controls">
                           <input type="checkbox" id="auth_enable_yahoo" name="auth_enable_yahoo" class="input-xlarge input" value="true" />
                           <p class="help-block">
                           		To Allow Yahoo Authentication:
								<ol>
									<li>Go to <a target="__blank" href="https://developer.apps.yahoo.com/dashboard/createKey.html">https://developer.apps.yahoo.com/<br />dashboard/createKey.html</a> and create a new application.</li>
									<li>Fill out any required fields such as the application name and description.</li>
									<li>Put your website domain in the <b>Application URL</b> and <b>Application Domain</b> fields. It should match with the current hostname (<b><?php echo $server_name; ?></b>)</li>
									<li>Set the Kind of Application to <b>Web-based</b>.</li>
									<li>Once you have registered, copy and paste the created application credentials into this setup page.</li>
								</ol>
						   </p>
                       </div>
                    </div>
                    <div class="control-group">
                       <label class="control-label" for="auth_yahoo_app_id">Yahoo Application ID</label>
                       <div class="controls">
                           <input type="text" id="auth_yahoo_app_id" name="auth_yahoo_app_id" value="" class="input-xlarge input" />
                           <p class="help-block">Yahoo Application ID</p>
                       </div>
                    </div>
                    <div class="control-group">
                       <label class="control-label" for="auth_yahoo_app_secret">Yahoo Application Secret</label>
                       <div class="controls">
                           <input type="text" id="auth_yahoo_app_secret" name="auth_yahoo_app_secret" value="" class="input-xlarge input" />
                           <p class="help-block">Yahoo Application Secret</p>
                       </div>
                    </div>
                    
					<div style="text-align:right; border-top: 1px solid #E5E5E5; margin-top: 50px;">
			        	<legend>LinkedIn <img src="./assets/icons/linkedin.png"/></legend>			        	
			        </div>
                    <div class="control-group">
                       <label class="control-label" for="auth_enable_linkedin">Allow linkedIn Authentication</label>
                       <div class="controls">
                           <input type="checkbox" id="auth_enable_linkedin" name="auth_enable_linkedin" class="input-xlarge input" value="true" />
                           <p class="help-block">
                           		To Allow LinkedIn Authentication:
								<ol>
									<li>Go to <a target="__blank" href="https://www.linkedin.com/secure/developer">https://www.linkedin.com/secure/developer</a> and create a new application.</li>
									<li>Fill out any required fields such as the application name and description.</li>
									<li>Put your website domain in the <b>Integration URL</b> field. It should match with the current hostname (<b><?php echo $server_name; ?></b>)</li>
									<li>Set the Application Type to <b>Web Application</b>.</li>
									<li>Once you have registered, copy and paste the created application credentials into this setup page.</li>
								</ol>
						   </p>
                       </div>
                    </div>
                    <div class="control-group">
                       <label class="control-label" for="auth_linkedin_app_key">LinkedIn Application Key</label>
                       <div class="controls">
                           <input type="text" id="auth_linkedin_app_key" name="auth_linkedin_app_key" value="" class="input-xlarge input" />
                           <p class="help-block">LinkedIn Application Key</p>
                       </div>
                    </div>
                    <div class="control-group">
                       <label class="control-label" for="auth_linkedin_app_secret">LinkedIn Application Secret</label>
                       <div class="controls">
                           <input type="text" id="auth_linkedin_app_secret" name="auth_linkedin_app_secret" value="" class="input-xlarge input" />
                           <p class="help-block">LinkedIn Application Secret</p>
                       </div>
                    </div>
                    
					<div style="text-align:right; border-top: 1px solid #E5E5E5; margin-top: 50px;">
			        	<legend>MySpace <img src="./assets/icons/myspace.png"/></legend>			        	
			        </div>
                    <div class="control-group">
                       <label class="control-label" for="auth_enable_myspace">Allow MySpace Authentication</label>
                       <div class="controls">
                           <input type="checkbox" id="auth_enable_myspace" name="auth_enable_myspace" class="input-xlarge input" value="true" />
                           <p class="help-block">
                           		To Allow MySpace Authentication:
								<ol>
									<li>Go to <a target="__blank" href="http://www.developer.myspace.com/">http://www.developer.myspace.com/</a> and create a new application.</li>
									<li>Fill out any required fields such as the application name and description.</li>
									<li>Put your website domain in the <b>External Url</b> and <b>External Callback Validation</b> fields. It should match with the current hostname (<b><?php echo $server_name; ?></b>)</li>
									<li>Once you have registered, copy and paste the created application credentials into this setup page.</li>
								</ol>
						   </p>
                       </div>
                    </div>
                    <div class="control-group">
                       <label class="control-label" for="auth_myspace_app_id">MySpace Application Key</label>
                       <div class="controls">
                           <input type="text" id="auth_myspace_app_key" name="auth_myspace_app_key" value="" class="input-xlarge input" />
                           <p class="help-block">MySpace Application Key</p>
                       </div>
                    </div>
                    <div class="control-group">
                       <label class="control-label" for="auth_myspace_app_secret">MySpace Application Secret</label>
                       <div class="controls">
                           <input type="text" id="auth_myspace_app_secret" name="auth_myspace_app_secret" value="" class="input-xlarge input" />
                           <p class="help-block">MySpace Application Secret</p>
                       </div>
                    </div>
                    
					<div style="text-align:right; border-top: 1px solid #E5E5E5; margin-top: 50px;">
			        	<legend>Foursquare <img src="./assets/icons/foursquare.png"/></legend>			        	
			        </div>
                    <div class="control-group">
                       <label class="control-label" for="auth_enable_foursquare">Allow Foursquare Authentication</label>
                       <div class="controls">
                           <input type="checkbox" id="auth_enable_foursquare" name="auth_enable_foursquare" class="input-xlarge input" value="true" />
                           <p class="help-block">
                           	    To Allow Foursquare Authentication:
								<ol>
									<li>Go to <a target="_blank" href="https://www.foursquare.com/oauth/">https://www.foursquare.com/oauth/</a> and create a new application.</li>
									<li>Fill out any required fields such as the application name and description.</li>									
									<li>Provide this URL as the <b>Callback URL</b> for your application: <b><?php echo $foursquare_callback_url; ?></b></li>
									<li>Once you have registered, copy and paste the created application credentials into this setup page.</li>
								</ol>
						   </p>
                       </div>
                    </div>
                    <div class="control-group">
                       <label class="control-label" for="auth_foursquare_app_id">Foursquare Application ID</label>
                       <div class="controls">
                           <input type="text" id="auth_foursquare_app_id" name="auth_foursquare_app_id" value="" class="input-xlarge input" />
                           <p class="help-block">Foursquare Application ID</p>
                       </div>
                    </div>
                    <div class="control-group">
                       <label class="control-label" for="auth_foursquare_app_secret">foursquare Application Secret</label>
                       <div class="controls">
                           <input type="text" id="auth_foursquare_app_secret" name="auth_foursquare_app_secret" value="" class="input-xlarge input" />
                           <p class="help-block">Foursquare Application Secret</p>
                       </div>
                    </div>
                    
					<div style="text-align:right; border-top: 1px solid #E5E5E5; margin-top: 50px;">
			        	<legend>Windows Live <img src="./assets/icons/live.png"/></legend>			        	
			        </div>
                    <div class="control-group">
                       <label class="control-label" for="auth_enable_windows_live">Allow Windows Live Authentication</label>
                       <div class="controls">
                           <input type="checkbox" id="auth_enable_windows_live" name="auth_enable_windows_live" class="input-xlarge input" value="true" />
                           <p class="help-block">
                           		To Allow Windows Live Authentication:
								<ol>
									<li>Go to <a target="__blank" href="https://manage.dev.live.com/ApplicationOverview.aspx">https://manage.dev.live.com/<br />ApplicationOverview.aspx</a> and create a new application.</li>
									<li>Fill out any required fields such as the application name and description.</li>
									<li>Put your website domain in the <b>Redirect Domain</b> field. It should match with the current hostname (<b><?php echo $server_name; ?></b>)</li>
									<li>Once you have registered, copy and paste the created application credentials into this setup page.</li>
								</ol>
						   </p>
                       </div>
                    </div>
                    <div class="control-group">
                       <label class="control-label" for="auth_windows_live_app_id">Windows Live Application ID</label>
                       <div class="controls">
                           <input type="text" id="auth_windows_live_app_id" name="auth_windows_live_app_id" value="" class="input-xlarge input" />
                           <p class="help-block">Windows Live Application ID</p>
                       </div>
                    </div>
                    <div class="control-group">
                       <label class="control-label" for="auth_windows_live_app_secret">windows_live Application Secret</label>
                       <div class="controls">
                           <input type="text" id="auth_windows_live_app_secret" name="auth_windows_live_app_secret" value="" class="input-xlarge input" />
                           <p class="help-block">Windows Live Application Secret</p>
                       </div>
                    </div>
                    
					<div style="text-align:right; border-top: 1px solid #E5E5E5; margin-top: 50px;">
			        	<legend>Open ID <img src="./assets/icons/openid.png"/></legend>			        	
			        </div>
                    <div class="control-group">
                       <label class="control-label" for="auth_enable_open_id">Allow Open Id Authentication</label>
                       <div class="controls">
                           <input type="checkbox" id="auth_enable_open_id" name="auth_enable_open_id" class="input-xlarge input" value="true" />
                           <p class="help-block">Enable Open Id Authentication (No registration required for OpenID based providers)</p>
                       </div>
                    </div>
                    
                    <div style="text-align:right; border-top: 1px solid #E5E5E5; margin-top: 50px;">
			        	<legend>AOL <img src="./assets/icons/aol.png"/></legend>			        	
			        </div>
                    <div class="control-group">
                       <label class="control-label" for="auth_enable_aol">Allow AOL Authentication</label>
                       <div class="controls">
                           <input type="checkbox" id="auth_enable_aol" name="auth_enable_aol" class="input-xlarge input" value="true" />
                           <p class="help-block">Enable AOL Authentication (No registration required for OpenID based providers)</p>
                       </div>
                    </div>
                    <div>
	                    <a class="btn" href="#preference">Back</a>	                    
	                </div>
			    </fieldset>
			    <div style="margin-top: 10px;">
			        <input type="submit" id="btn_install" class="button_install btn btn-primary" name="Install" value="INSTALL NOW"  />
			        <img id="img_loader_install" style="display:none;" src="./assets/ajax-loader.gif" />
			    </div>
			</form>    
	    </div>
	    <div id="ajax_result" class="absolute">	       	    
	       <div id="infoMessage" class="alert alert-error"></div>
	       <div id="div_loader_message" class="span12" style="display:none;">
	           Checking <img src="./assets/ajax-loader.gif" />
	       </div>	       
	    </div>
	
	</div>
</div>
</body>