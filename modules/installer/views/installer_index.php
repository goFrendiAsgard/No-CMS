<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install No-CMS</title>
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
        .input-xlarge{
            height: 28px!important;
        }
        #div-error-warning-message{
            position:static;
        }
        #btn-install, #img-loader, #div-error-message, #div-warning-message, #div-success-message{
            display:none;
        }
        .btn-next{
            padding-right:10px;
        }
        #div-body{
            margin-left:10px;
            margin-right:10px;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css'); ?>" />
    <script type="text/javascript" src="<?php echo base_url('assets/nocms/js/jquery.tools.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
</head>
<body>
    <div class="row-fluid">
        <div class="navbar navbar-fixed-top">
          <div class="navbar-inner">
            <div class="container-fluid">
                <a class="brand" href="#">Install No-CMS on your server</a>
            </div>
          </div>
        </div>
        <div id="div-body" class="tabbable well"> <!-- Only required for left/right tabs -->
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab1" data-toggle="tab">Database Setting</a></li>
                <li><a href="#tab2" data-toggle="tab">CMS Setting</a></li>
                <li><a href="#tab3" data-toggle="tab">Facebook</a></li>
                <li><a href="#tab4" data-toggle="tab">Twitter</a></li>
                <li><a href="#tab5" data-toggle="tab">Google</a></li>
                <li><a href="#tab6" data-toggle="tab">Yahoo</a></li>
                <li><a href="#tab7" data-toggle="tab">LinkedIn</a></li>
                <li><a href="#tab8" data-toggle="tab">MySpace</a></li>
                <li><a href="#tab9" data-toggle="tab">Foursquare</a></li>
                <li><a href="#tab10" data-toggle="tab">Windows</a></li>
                <li><a href="#tab11" data-toggle="tab">OpenID & AOL</a></li>
            </ul>
            <form class="form-horizontal" action="<?php echo site_url('installer/install'); ?>" method="post" accept-charset="utf-8">
            <div class="span8 well">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab1">
                            <h2>Database Setting</h2>
                            <div class="control-group">
                               <label class="control-label" for="db_protocol">Protocol</label>
                               <div class="controls">
                                   <select id="db_protocol" name="db_protocol" class="input-xlarge input">
                                       <option selected value="mysql">MySQL / MariaDB</option>
                                       <option value="pgsql">PosgreSQL</option>
                                       <option value="sqlite">SQLite</option>
                                   </select>
                                   <p class="help-block">Choose database protocol</p>
                               </div>
                            </div>
                            <div class="control-group">
                               <label class="control-label" for="db_host">Server</label>
                               <div class="controls">
                                   <input type="text" id="db_host" name="db_host" value="localhost" class="input-xlarge input" />
                                   <p class="help-block">Server name (e.g: 'localhost', '127.0.0.1', 'http://yourdomain.com')</p>
                               </div>
                            </div>
                            <div class="control-group">
                               <label class="control-label" for="db_port">Port</label>
                               <div class="controls">
                                   <input type="text" id="db_port" name="db_port" value="3306" class="input-xlarge input" />
                                   <p class="help-block">Database port, usually 3306 for MySQL, 5432 for PosgreSQL, and empty for sqlite</p>
                               </div>
                            </div>
                            <div class="control-group">
                               <label class="control-label" for="db_username">Username</label>
                               <div class="controls">
                                   <input type="text" id="db_username" name="db_username" value="root" class="input-xlarge input" />
                                   <p class="help-block">Database username</p>
                               </div>
                            </div>
                            <div class="control-group">
                               <label class="control-label" for="db_password">Password</label>
                               <div class="controls">
                                   <input type="password" id="db_password" name="db_password" value="" class="input-xlarge input" />
                                   <p class="help-block">Database password</p>
                               </div>
                            </div>
                            <div class="control-group">
                               <label class="control-label" for="db_name">Schema</label>
                               <div class="controls">
                                   <input type="text" id="db_name" name="db_name" value="no_cms" class="input-xlarge input" />
                                   <p class="help-block">Database schema (For MySQL/MariaDB, the installer will try to create the schema if it is not exists)</p>
                               </div>
                            </div>
                            <div class="control-group">
                               <label class="control-label" for="db_table_prefix">Table Prefix</label>
                               <div class="controls">
                                   <input type="text" id="db_table_prefix" name="db_table_prefix" value="cms" class="input-xlarge input" />
                                   <p class="help-block">Database table prefix</p>
                               </div>
                            </div>
                            <a class="btn btn-primary btn-next" href="#tab2">Next</a>
                    </div>

                    <div class="tab-pane" id="tab2">
                        <h2>CMS Setting</h2>
                        <div class="control-group">
                           <label class="control-label" for="admin_email">E mail</label>
                           <div class="controls">
                               <input type="text" id="admin_email" name="admin_email" value="admin@admin.com" class="input-xlarge input" />
                               <p class="help-block">No-CMS administrator email</p>
                           </div>
                        </div>
                        <div class="control-group">
                           <label class="control-label" for="admin_user_name">Username</label>
                           <div class="controls">
                               <input type="text" id="admin_user_name" name="admin_user_name" value="admin" class="input-xlarge input" />
                               <p class="help-block">admin's username</p>
                           </div>
                        </div>
                        <div class="control-group">
                           <label class="control-label" for="adm_real_name">Real Name</label>
                           <div class="controls">
                               <input type="text" id="admin_real_name" name="admin_real_name" value="Rina Suzuki" class="input-xlarge input" />
                               <p class="help-block">admin's realname</p>
                           </div>
                        </div>
                        <div class="control-group">
                           <label class="control-label" for="admin_password">Password</label>
                           <div class="controls">
                               <input type="password" id="admin_password" name="admin_password" value="" class="input-xlarge input" />
                               <p class="help-block">admin's password</p>
                           </div>
                        </div>
                        <div class="control-group">
                           <label class="control-label" for="admin_confirm_password">Confirm Password</label>
                           <div class="controls">
                               <input type="password" id="admin_confirm_password" name="admin_confirm_password" value="" class="input-xlarge input" />
                               <p class="help-block">admin's password again</p>
                           </div>
                        </div>
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
                        <a class="btn btn-primary btn-next" href="#tab1">Previous</a>
                        <a class="btn btn-primary btn-next" href="#tab3">Next</a>
                    </div>

                    <div class="tab-pane" id="tab3">
                        <h2>Facebook Authentication</h2>
                        <div class="control-group">
                           <label class="control-label" for="auth_enable_facebook">Allow Facebook Authentication</label>
                           <div class="controls">
                               <input type="checkbox" id="auth_enable_facebook" name="auth_enable_facebook" class="input-xlarge input" value="true" />
                               <p class="help-block">
                                    To Allow Facebook Authentication:
                                    <ol>
                                        <li>Go to <a target="__blank" href="https://www.facebook.com/developers/">https://www.facebook.com/developers/</a> and create a new application.</li>
                                        <li>Fill out any required fields such as the application name and description.</li>
                                        <li>Put your website domain in the <b>Site Url</b> field. It should match with the current hostname (<b><?php echo $_SERVER['SERVER_NAME']; ?></b>)</li>
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
                        <a class="btn btn-primary btn-next" href="#tab2">Previous</a>
                        <a class="btn btn-primary btn-next" href="#tab4">Next</a>
                    </div>

                    <div class="tab-pane" id="tab4">
                        <h2>Twitter Authentication</h2>
                        <div class="control-group">
                           <label class="control-label" for="auth_enable_twitter">Allow Twitter Authentication</label>
                           <div class="controls">
                               <input type="checkbox" id="auth_enable_twitter" name="auth_enable_twitter" class="input-xlarge input" value="true" />
                               <p class="help-block">
                                    To Allow Twitter Authentication:
                                    <ol>
                                        <li>Go to <a target="__blank" href="https://dev.twitter.com/apps">https://dev.twitter.com/apps</a> and create a new application.</li>
                                        <li>Fill out any required fields such as the application name and description.</li>
                                        <li>Put your website domain in the <b>Application Website</b> and <b>Application Callback URL</b> fields. It should match with the current hostname (<b><?php echo $_SERVER['SERVER_NAME']; ?></b>)</li>
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
                        <a class="btn btn-primary btn-next" href="#tab3">Previous</a>
                        <a class="btn btn-primary btn-next" href="#tab5">Next</a>
                    </div>

                    <div class="tab-pane" id="tab5">
                        <h2>Google Authentication</h2>
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
                                        <li>Provide this URL as the <b>Callback URL</b> for your application: <b><?php echo site_url('main/hauth/endpoint/?hauth.done=Google'); ?></b></li>
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
                        <a class="btn btn-primary btn-next" href="#tab4">Previous</a>
                        <a class="btn btn-primary btn-next" href="#tab6">Next</a>
                    </div>

                    <div class="tab-pane" id="tab6">
                        <h2>Yahoo Authentication</h2>
                        <div class="control-group">
                           <label class="control-label" for="auth_enable_yahoo">Allow Yahoo Authentication</label>
                           <div class="controls">
                               <input type="checkbox" id="auth_enable_yahoo" name="auth_enable_yahoo" class="input-xlarge input" value="true" />
                               <p class="help-block">
                                    To Allow Yahoo Authentication:
                                    <ol>
                                        <li>Go to <a target="__blank" href="https://developer.apps.yahoo.com/dashboard/createKey.html">https://developer.apps.yahoo.com/<br />dashboard/createKey.html</a> and create a new application.</li>
                                        <li>Fill out any required fields such as the application name and description.</li>
                                        <li>Put your website domain in the <b>Application URL</b> and <b>Application Domain</b> fields. It should match with the current hostname (<b><?php echo $_SERVER['SERVER_NAME']; ?></b>)</li>
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
                        <a class="btn btn-primary btn-next" href="#tab5">Previous</a>
                        <a class="btn btn-primary btn-next" href="#tab7">Next</a>
                    </div>

                    <div class="tab-pane" id="tab7">
                        <h2>LinkedIn Authentication</h2>
                        <div class="control-group">
                           <label class="control-label" for="auth_enable_linkedin">Allow linkedIn Authentication</label>
                           <div class="controls">
                               <input type="checkbox" id="auth_enable_linkedin" name="auth_enable_linkedin" class="input-xlarge input" value="true" />
                               <p class="help-block">
                                    To Allow LinkedIn Authentication:
                                    <ol>
                                        <li>Go to <a target="__blank" href="https://www.linkedin.com/secure/developer">https://www.linkedin.com/secure/developer</a> and create a new application.</li>
                                        <li>Fill out any required fields such as the application name and description.</li>
                                        <li>Put your website domain in the <b>Integration URL</b> field. It should match with the current hostname (<b><?php echo $_SERVER['SERVER_NAME']; ?></b>)</li>
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
                        <a class="btn btn-primary btn-next" href="#tab6">Previous</a>
                        <a class="btn btn-primary btn-next" href="#tab8">Next</a>
                    </div>

                    <div class="tab-pane" id="tab8">
                        <h2>MySpace Authentication</h2>
                        <div class="control-group">
                           <label class="control-label" for="auth_enable_myspace">Allow MySpace Authentication</label>
                           <div class="controls">
                               <input type="checkbox" id="auth_enable_myspace" name="auth_enable_myspace" class="input-xlarge input" value="true" />
                               <p class="help-block">
                                    To Allow MySpace Authentication:
                                    <ol>
                                        <li>Go to <a target="__blank" href="http://www.developer.myspace.com/">http://www.developer.myspace.com/</a> and create a new application.</li>
                                        <li>Fill out any required fields such as the application name and description.</li>
                                        <li>Put your website domain in the <b>External Url</b> and <b>External Callback Validation</b> fields. It should match with the current hostname (<b><?php echo $_SERVER['SERVER_NAME']; ?></b>)</li>
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
                        <a class="btn btn-primary btn-next" href="#tab7">Previous</a>
                        <a class="btn btn-primary btn-next" href="#tab9">Next</a>
                    </div>

                    <div class="tab-pane" id="tab9">
                        <h2>Foursquare Authentication</h2>
                        <div class="control-group">
                           <label class="control-label" for="auth_enable_foursquare">Allow Foursquare Authentication</label>
                           <div class="controls">
                               <input type="checkbox" id="auth_enable_foursquare" name="auth_enable_foursquare" class="input-xlarge input" value="true" />
                               <p class="help-block">
                                    To Allow Foursquare Authentication:
                                    <ol>
                                        <li>Go to <a target="_blank" href="https://www.foursquare.com/oauth/">https://www.foursquare.com/oauth/</a> and create a new application.</li>
                                        <li>Fill out any required fields such as the application name and description.</li>
                                        <li>Provide this URL as the <b>Callback URL</b> for your application: <b><?php echo site_url('main/hauth/endpoint/?hauth.done=Foursquare'); ?></b></li>
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
                        <a class="btn btn-primary btn-next" href="#tab8">Previous</a>
                        <a class="btn btn-primary btn-next" href="#tab10">Next</a>
                    </div>

                    <div class="tab-pane" id="tab10">
                        <h2>Windows Live Authentication</h2>
                        <div class="control-group">
                           <label class="control-label" for="auth_enable_windows_live">Allow Windows Live Authentication</label>
                           <div class="controls">
                               <input type="checkbox" id="auth_enable_windows_live" name="auth_enable_windows_live" class="input-xlarge input" value="true" />
                               <p class="help-block">
                                    To Allow Windows Live Authentication:
                                    <ol>
                                        <li>Go to <a target="__blank" href="https://manage.dev.live.com/ApplicationOverview.aspx">https://manage.dev.live.com/<br />ApplicationOverview.aspx</a> and create a new application.</li>
                                        <li>Fill out any required fields such as the application name and description.</li>
                                        <li>Put your website domain in the <b>Redirect Domain</b> field. It should match with the current hostname (<b><?php $_SERVER['SERVER_NAME'] ?></b>)</li>
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
                        <a class="btn btn-primary btn-next" href="#tab9">Previous</a>
                        <a class="btn btn-primary btn-next" href="#tab11">Next</a>
                    </div>

                    <div class="tab-pane" id="tab11">
                        <h2>OpenID & AOL Authentication</h2>
                        <div class="control-group">
                           <label class="control-label" for="auth_enable_open_id">Allow Open Id Authentication</label>
                           <div class="controls">
                               <input type="checkbox" id="auth_enable_open_id" name="auth_enable_open_id" class="input-xlarge input" value="true" />
                               <p class="help-block">Enable Open Id Authentication (No registration required for OpenID based providers)</p>
                           </div>
                        </div>
                        <div class="control-group">
                           <label class="control-label" for="auth_enable_aol">Allow AOL Authentication</label>
                           <div class="controls">
                               <input type="checkbox" id="auth_enable_aol" name="auth_enable_aol" class="input-xlarge input" value="true" />
                               <p class="help-block">Enable AOL Authentication (No registration required for OpenID based providers)</p>
                           </div>
                        </div>
                        <a class="btn btn-primary btn-next" href="#tab10">Previous</a>
                    </div>
                </div>
            </div>
            <div id="div-right-pane" class="span4">
                <div id="div-error-warning-message">
                    <div id="div-error-message" class="alert alert-block alert-error">
                        <strong>ERRORS:</strong>
                        <ul id="ul-error-message"></ul>
                    </div>
                    <div id="div-warning-message" class="alert alert-block alert-warning">
                        <strong>WARNINGS:</strong>
                        <ul id="ul-warning-message"></ul>
                    </div>
                    <div id="div-success-message" class="alert alert-block alert-success">
                        <strong>GREAT !!!</strong>, you can now install No-CMS without worrying anything.                        
                    </div>
                    <div style="margin-top:20px; margin-bottom:20px;">
                        <input type="submit" id="btn-install" class="btn btn-primary btn-large" name="Install" disabled="disabled" value="INSTALL NOW"  />
                        <img id="img-loader" src="<?php echo base_url('modules/installer/assets/ajax-loader.gif'); ?>" />
                    </div>                    
                </div>
            </div>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        var REQUEST
        var RUNNING_REQUEST = false;
        var SUCCESS = false;

        $(document).ready(function(){
            // just for fun, use one of SCANDAL member as default admin real name
            var real_name_list = new Array('Haruna Ono', 'Tomomi Ogawa', 
                'Mami Sasazaki', 'Rina Suzuki');
            var real_name_index = Math.floor((Math.random()*4));
            var real_name = real_name_list[real_name_index];
            $('#admin_real_name').val(real_name);
            $('#div-error-warning-message').append('<img style="width:100%; opacity:0.4;" src="<?php echo base_url('modules/installer/assets'); ?>/'+real_name+'.jpg" />');
            // magic :)
            $(document).on('scroll', function(){
                if ($('#div-error-warning-message')[0].offsetTop < ($(document).scrollTop()+60)){
                    $('#div-error-warning-message').css({position: "fixed", top:60});
                }else{
                    $('#div-error-warning-message').css({position: "static", top: 0});
                }
                $('#div-error-warning-message').width($('#div-body').width()*0.3);
            });
            // check things
            check();
        });
        $(window).resize(function() {
            $('#div-error-warning-message').width($('#div-body').width()*0.3);
        });
        $("input, select").change(function(){
            check();
        });
        $("input:not(#db_name), select").keyup(function(){
            check();
        });

        // default port
        $("#db_protocol").change(function(){
            var protocol = $("#db_protocol").val();
            var port = '';
            if(protocol == 'mysql'){
                port = '3306';
            }else if (protocol == 'pgsql'){
                port = '5432';
            }else{
                port = '';
            }
            $('#db_port').val(port);
        });

        // next or previous step
        $(".btn-next").click(function(){
            var href = $(this).attr('href');
            $("ul.nav-tabs li").removeClass('active');
            $("div.tab-pane").removeClass('active');
            console.log($("ul.nav-tabs li[href='"+href+"']"));
            $("ul.nav-tabs li a[href='"+href+"']").parent().addClass('active');
            $("div.tab-pane[id='"+href.substr(1)+"']").addClass('active');
            return false;
        });

        $("#btn-install").click(function(){
            $(this).hide();
            $("#img-loader").show();
        })


        function check(){
            if(RUNNING_REQUEST){
                REQUEST.abort();
            }
            RUNNING_REQUEST = true;
            REQUEST = $.ajax({
                type : "POST",
                url : "<?php echo site_url('installer/installer/check'); ?>",
                dataType: "json",
                async : true,
                data : {
                    db_protocol             : $("#db_protocol").val(),
                    db_host                 : $("#db_host").val(),
                    db_port                 : $("#db_port").val(),
                    db_username             : $("#db_username").val(),
                    db_password             : $("#db_password").val(),
                    db_name                 : $("#db_name").val(),
                    admin_user_name         : $("#admin_user_name").val(),
                    admin_real_name         : $("#admin_real_name").val(),
                    admin_password          : $("#admin_password").val(),
                    admin_confirm_password  : $("#admin_confirm_password").val(),
                    hide_index              : $("#hide_index").attr('checked')? $("#hide_index").val(): '',
                    auth_enable_facebook    : $("#auth_enable_facebook").attr('checked')? $("#auth_enable_facebook").val(): '',
                    auth_enable_twitter     : $("#auth_enable_twitter").attr('checked')? $("#auth_enable_twitter").val(): '',
                    auth_enable_google      : $("#auth_enable_google").attr('checked')? $("#auth_enable_google").val(): '',
                    auth_enable_yahoo       : $("#auth_enable_yahoo").attr('checked')? $("#auth_enable_yahoo").val(): '',
                    auth_enable_linkedin    : $("#auth_enable_linkedin").attr('checked')? $("#auth_enable_linkedin").val(): '',
                    auth_enable_myspace     : $("#auth_enable_myspace").attr('checked')? $("#auth_enable_myspace").val(): '',
                    auth_enable_foursquare  : $("#auth_enable_foursquare").attr('checked')? $("#auth_enable_foursquare").val(): '',
                    auth_enable_windows_live: $("#auth_enable_windows_live").attr('checked')? $("#auth_enable_windows_live").val(): '',
                    auth_enable_open_id     : $("#auth_enable_open_id").attr('checked')? $("#auth_enable_open_id").val(): '',
                    auth_enable_aol         : $("#auth_enable_aol").attr('checked')? $("#auth_enable_aol").val(): '',
                    auth_facebook_app_id        : $('#auth_facebook_app_id').val(),
                    auth_facebook_app_secret    : $('#auth_facebook_app_secret').val(),
                    auth_twitter_app_key        : $('#auth_twitter_app_key').val(),
                    auth_twitter_app_secret     : $('#auth_twitter_app_secret').val(),
                    auth_google_app_id          : $('#auth_google_app_id').val(),
                    auth_google_app_secret      : $('#auth_google_app_secret').val(),
                    auth_yahoo_app_id           : $('#auth_yahoo_app_id').val(),
                    auth_yahoo_app_secret       : $('#auth_yahoo_app_secret').val(),
                    auth_linkedin_app_key       : $('#auth_linkedin_app_key').val(),
                    auth_linkedin_app_secret    : $('#auth_linkedin_app_secret').val(),
                    auth_myspace_app_key        : $('#auth_myspace_app_key').val(),
                    auth_myspace_app_secret     : $('#auth_myspace_app_secret').val(),
                    auth_foursquare_app_id      : $('#auth_foursquare_app_id').val(),
                    auth_foursquare_app_secret  : $('#auth_foursquare_app_secret').val(),
                    auth_windows_live_app_id    : $('#auth_windows_live_app_id').val(),
                    auth_windows_live_app_secret: $('#auth_windows_live_app_secret').val(),
                },
                success : function(response){
                    SUCCESS = response.success;
                    var warning_list = response.warning_list;
                    var error_list = response.error_list;
                    // show error
                    $('#ul-error-message').html('');
                    if(error_list.length>0){
                        for(var i=0; i<error_list.length; i++){
                            var error = error_list[i];
                            $('#ul-error-message').append('<li>'+error+'</li>');
                        }
                        $('#div-error-message').show();
                    }else{
                        $('#div-error-message').hide();
                    }
                    // show warning
                    $('#ul-warning-message').html('');
                    if(warning_list.length>0){
                        for(var i=0; i<warning_list.length; i++){
                            var warning = warning_list[i];
                            $('#ul-warning-message').append('<li>'+warning+'</li>');
                        }
                        $('#div-warning-message').show();
                    }else{
                        $('#div-warning-message').hide();
                    }
                    if(error_list.length==0 && warning_list.length==0){
                        $('#div-success-message').show();
                    }else{
                        $('#div-success-message').hide();
                    }
                    // show/hide button
                    if(SUCCESS){
                        $('#btn-install').show();
                        $("#btn-install").removeAttr('disabled');
                    }else{
                        $('#btn-install').hide();
                        $("#btn-install").attr('disabled','disabled');
                    }

                }
            });
        }
    </script>
</body>