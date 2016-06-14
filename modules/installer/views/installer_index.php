<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Install No-CMS</title>
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 5px;
        }
        #div-error-warning-message{
            margin-top:10px;
            position:static;
        }
        #btn-install, #img-loader, #div-error-message, #div-warning-message, #div-success-message{
            display:none;
        }
        .btn-change-tab{
            padding-right:10px;
        }
        #div-body{
            margin-left:10px;
            margin-right:10px;
        }
        .tab-content{
            overflow:inherit!important;
        }
        .help-block, .controls ol{
            font-size:small;
        }
        .a-change-tab, .a-change-tab:visited{
            color:#b94a48!important;
            text-decoration: none;
            font-weight:bold;
        }
        .a-change-tab:hover{
            color:#b94a48!important;
            text-decoration: underline;
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css'); ?>" />
</head>
<body>
    <div class="">
        <div class="navbar navbar-fixed-top navbar-default">
            <div class="navbar-header"><a class="navbar-brand" href="#">No-CMS Installation Wizard</a></div>
        </div>
        <div id="div-body" class="tabbable"> <!-- Only required for left/right tabs -->
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
                <li><a href="#tab11" data-toggle="tab">OpenID &amp; AOL</a></li>
            </ul>
            <form class="form-horizontal" action="<?php echo site_url('installer/install'); ?>" method="post" accept-charset="utf-8">
            <div class="col-sm-8 col-md-8 col-xs-8">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab1">
                             <h3>Database Setting</h3>
                             <div class="form-group">
                                <label class="control-label col-md-3" for="db_protocol">Protocol</label>
                                <div class="controls col-md-8">
                                     <select id="db_protocol" name="db_protocol" class="input form-control" placeholder="database driver">
                                        <?php if($mysql_installed){ ?>
                                            <option value="mysql">MySQL/MariaDB (with mysql driver)</option>
                                        <?php } ?>
                                        <?php if($mysqli_installed){ // default to this ?>
                                            <option selected value="mysqli">MySQL/MariaDB (with mysqli driver)</option>
                                        <?php } ?>
                                        <?php if($pdo_mysql_installed){ ?>
                                            <option value="pdo_mysql">MySQL/MariaDB (with PDO driver)</option>
                                        <?php } ?>
                                        <?php if($pdo_pgsql_installed){ ?>
                                            <option value="pdo_pgsql">PostgreSQL (with PDO driver), Experimental</option>
                                        <?php } ?>
                                        <?php if($pdo_sqlite_installed){ ?>
                                            <option value="pdo_sqlite">SQLite (with PDO driver), Experimental</option>
                                        <?php } ?>
                                     </select>
                                </div>
                             </div>
                             <div class="form-group">
                                 <label class="control-label col-md-3" for="db_host">Server</label>
                                 <div class="controls col-md-8">
                                     <input type="text" id="db_host" name="db_host" value="127.0.0.1" class="input form-control" placeholder="Server name (e.g: 'localhost', '127.0.0.1', 'yourDatabaseServer.com')">
                                 </div>
                             </div>
                             <div class="form-group">
                                <label class="control-label col-md-3" for="db_port">Port</label>
                                <div class="controls col-md-8">
                                    <input type="text" id="db_port" name="db_port" value="3306" class="input form-control" placeholder="Port">
                                    <p class="help-block">Database port, usually 3306 for MySQL, 5432 for PosgreSQL, and empty for sqlite</p>
                                </div>
                             </div>
                             <div class="form-group">
                                <label class="control-label col-md-3" for="db_username">Username</label>
                                <div class="controls col-md-8">
                                    <input type="text" id="db_username" name="db_username" value="root" class="input form-control" placeholder="Database username">
                                </div>
                             </div>
                             <div class="form-group">
                                <label class="control-label col-md-3" for="db_password">Password</label>
                                <div class="controls col-md-8">
                                    <input type="password" id="db_password" name="db_password" value="" class="input form-control" placeholder="Database password">
                                </div>
                             </div>
                             <div class="form-group">
                                <label class="control-label col-md-3" for="db_name">Schema</label>
                                <div class="controls col-md-8">
                                    <input type="text" id="db_name" name="db_name" value="no_cms" class="input form-control" placeholder="Database Schema">
                                    <p class="help-block">If you have database's root privilege and use mysql/mysqli driver, the installer will try to make the schema for you, otherwise you should ensure that your schema is already exists</p>
                                </div>
                             </div>
                             <div class="form-group">
                                <label class="control-label col-md-3" for="db_table_prefix">Table Prefix</label>
                                <div class="controls col-md-8">
                                    <input type="text" id="db_table_prefix" name="db_table_prefix" value="cms" class="input form-control" placeholder="Table Prefix">
                                </div>
                             </div>
                             <a class="btn btn-primary btn-change-tab" href="#tab2">Next</a>
                    </div>

                    <div class="tab-pane" id="tab2">
                        <h3>CMS Setting</h3>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="admin_email">Super admin's E-mail</label>
                            <div class="controls col-md-8">
                                <input type="text" id="admin_email" name="admin_email" value="admin@admin.com" class="input form-control" placeholder="Super admin's email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="admin_user_name">Super admin's Username</label>
                            <div class="controls col-md-8">
                                <input type="text" id="admin_user_name" name="admin_user_name" value="admin" class="input form-control" placeholder="Super admin's username">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="adm_real_name">Super admin's Real Name</label>
                            <div class="controls col-md-8">
                                <input type="text" id="admin_real_name" name="admin_real_name" value="Rina Suzuki" class="input form-control" placeholder="Super admin's real name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="admin_password">Super admin's Password</label>
                            <div class="controls col-md-8">
                                <input type="password" id="admin_password" name="admin_password" value="" class="input form-control" placeholder="Super admin's password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="admin_confirm_password">Password Confirmation</label>
                            <div class="controls col-md-8">
                                <input type="password" id="admin_confirm_password" name="admin_confirm_password" value="" class="input form-control" placeholder="Super admin's password (again)">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="hide_index">Hide Index.php</label>
                            <div class="controls col-md-8">
                                <input type="checkbox" id="hide_index" name="hide_index" class="input" value="true" checked="">&nbsp; Hide 'index.php' from url (recommended, and required for multisite)
                                <p class="help-block">Require mod rewrite. Hide index.php will produce a more SEO-friendly URL (i.e: http://your_domain.com/main/index instead of http://your_domain.com/index.php/main/index)</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="gzip_compression">Use GZIP compression</label>
                            <div class="controls col-md-8">
                                <input type="checkbox" id="gzip_compression" name="gzip_compression" class="input" value="true">&nbsp; Compress output
                                <p class="help-block">For compression to work, nothing can be sent before the output buffer is called by the output class.  Do not 'echo' any values with compression enabled (some browser might not work well with gzip)</p>
                            </div>
                        </div>
                        <a class="btn btn-primary btn-change-tab" href="#tab1">Previous</a>
                        <a class="btn btn-primary btn-change-tab" href="#tab3">Next</a>
                    </div>

                    <div class="tab-pane" id="tab3">
                        <h3>Facebook Authentication</h3>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_enable_facebook">Allow Facebook Authentication</label>
                            <div class="controls col-md-8">
                                <input type="checkbox" id="auth_enable_facebook" name="auth_enable_facebook" class="input" value="true">
                                <p class="help-block">
                                    To Allow Facebook Authentication:
                                </p>
                                <ol>
                                    <li>Go to <a target="__blank" href="https://www.facebook.com/developers/">https://www.facebook.com/developers/</a> and create a new application.</li>
                                    <li>Fill out any required fields such as the application name and description.</li>
                                    <li>Put your website domain in the <b>Site Url</b> field. It should match with the current hostname (<b><?php echo $_SERVER['SERVER_NAME']; ?></b>)</li>
                                    <li>Once you have registered, copy and paste the created application credentials into this setup page.</li>
                                </ol>
                                <p></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_facebook_app_id">Facebook Application ID</label>
                            <div class="controls col-md-8">
                                <input type="text" id="auth_facebook_app_id" name="auth_facebook_app_id" value="" class="input form-control">
                                <p class="help-block">Facebook Application ID</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_facebook_app_secret">Facebook Application Secret</label>
                            <div class="controls col-md-8">
                                <input type="text" id="auth_facebook_app_secret" name="auth_facebook_app_secret" value="" class="input form-control">
                                <p class="help-block">Facebook Application Secret</p>
                            </div>
                        </div>
                        <a class="btn btn-primary btn-change-tab" href="#tab2">Previous</a>
                        <a class="btn btn-primary btn-change-tab" href="#tab4">Next</a>
                    </div>

                    <div class="tab-pane" id="tab4">
                        <h3>Twitter Authentication</h3>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_enable_twitter">Allow Twitter Authentication</label>
                            <div class="controls col-md-8">
                                <input type="checkbox" id="auth_enable_twitter" name="auth_enable_twitter" class="input" value="true">
                                <p class="help-block">
                                    To Allow Twitter Authentication:
                                     </p><ol>
                                         <li>Go to <a target="__blank" href="https://dev.twitter.com/apps">https://dev.twitter.com/apps</a> and create a new application.</li>
                                         <li>Fill out any required fields such as the application name and description.</li>
                                         <li>Put your website domain in the <b>Application Website</b> and <b>Application Callback URL</b> fields. It should match with the current hostname (<b><?php echo $_SERVER['SERVER_NAME']; ?></b>)</li>
                                         <li>Set the Default Access Type to <b>Read</b>, <b>Write</b>, and <b>Direct Messages</b>.</li>
                                         <li>Once you have registered, copy and paste the created application credentials into this setup page.</li>
                                     </ol>
                                <p></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_twitter_app_id">Twitter Application Key</label>
                            <div class="controls col-md-8">
                                <input type="text" id="auth_twitter_app_key" name="auth_twitter_app_key" value="" class="input form-control">
                                <p class="help-block">Twitter Application Key</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_twitter_app_secret">Twitter Application Secret</label>
                            <div class="controls col-md-8">
                                <input type="text" id="auth_twitter_app_secret" name="auth_twitter_app_secret" value="" class="input form-control">
                                <p class="help-block">Twitter Application Secret</p>
                            </div>
                        </div>
                        <a class="btn btn-primary btn-change-tab" href="#tab3">Previous</a>
                        <a class="btn btn-primary btn-change-tab" href="#tab5">Next</a>
                    </div>

                    <div class="tab-pane" id="tab5">
                        <h3>Google Authentication</h3>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_enable_google">Allow Google Authentication</label>
                            <div class="controls col-md-8">
                                <input type="checkbox" id="auth_enable_google" name="auth_enable_google" class="input" value="true">
                                <p class="help-block">
                                    To Allow Google Authentication:
                                     </p><ol>
                                         <li>Go to <a target="_blank" href="https://code.google.com/apis/console/">https://code.google.com/apis/console/</a> and create a new application.</li>
                                         <li>Fill out any required fields such as the application name and description.</li>
                                         <li>On the <b>"Create Client ID"</b> popup switch to advanced settings by clicking on <b>(more options)</b>.</li>
                                         <li>Provide this URL as the <b>Callback URL</b> for your application: <b><?php echo site_url('main/hauth/endpoint/?hauth.done=Google'); ?></b></li>
                                         <li>Once you have registered, copy and paste the created application credentials into this setup page.</li>
                                     </ol>
                                <p></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_google_app_id">Google Application ID</label>
                            <div class="controls col-md-8">
                                <input type="text" id="auth_google_app_id" name="auth_google_app_id" value="" class="input form-control">
                                <p class="help-block">Google Application ID</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_google_app_secret">Google Application Secret</label>
                            <div class="controls col-md-8">
                                <input type="text" id="auth_google_app_secret" name="auth_google_app_secret" value="" class="input form-control">
                                <p class="help-block">Google Application Secret</p>
                            </div>
                        </div>
                        <a class="btn btn-primary btn-change-tab" href="#tab4">Previous</a>
                        <a class="btn btn-primary btn-change-tab" href="#tab6">Next</a>
                    </div>

                    <div class="tab-pane" id="tab6">
                        <h3>Yahoo Authentication</h3>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_enable_yahoo">Allow Yahoo Authentication</label>
                            <div class="controls col-md-8">
                                <input type="checkbox" id="auth_enable_yahoo" name="auth_enable_yahoo" class="input" value="true">
                                <p class="help-block">
                                    To Allow Yahoo Authentication:
                                     </p><ol>
                                         <li>Go to <a target="__blank" href="https://developer.apps.yahoo.com/dashboard/createKey.html">https://developer.apps.yahoo.com/<br>dashboard/createKey.html</a> and create a new application.</li>
                                         <li>Fill out any required fields such as the application name and description.</li>
                                         <li>Put your website domain in the <b>Application URL</b> and <b>Application Domain</b> fields. It should match with the current hostname (<b><?php echo $_SERVER['SERVER_NAME']; ?></b>)</li>
                                         <li>Set the Kind of Application to <b>Web-based</b>.</li>
                                         <li>Once you have registered, copy and paste the created application credentials into this setup page.</li>
                                     </ol>
                                <p></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_yahoo_app_id">Yahoo Application ID</label>
                            <div class="controls col-md-8">
                                <input type="text" id="auth_yahoo_app_id" name="auth_yahoo_app_id" value="" class="input form-control">
                                <p class="help-block">Yahoo Application ID</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_yahoo_app_secret">Yahoo Application Secret</label>
                            <div class="controls col-md-8">
                                <input type="text" id="auth_yahoo_app_secret" name="auth_yahoo_app_secret" value="" class="input form-control">
                                <p class="help-block">Yahoo Application Secret</p>
                            </div>
                        </div>
                        <a class="btn btn-primary btn-change-tab" href="#tab5">Previous</a>
                        <a class="btn btn-primary btn-change-tab" href="#tab7">Next</a>
                    </div>

                    <div class="tab-pane" id="tab7">
                        <h3>LinkedIn Authentication</h3>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_enable_linkedin">Allow linkedIn Authentication</label>
                            <div class="controls col-md-8">
                                <input type="checkbox" id="auth_enable_linkedin" name="auth_enable_linkedin" class="input" value="true">
                                <p class="help-block">
                                    To Allow LinkedIn Authentication:
                                     </p><ol>
                                         <li>Go to <a target="__blank" href="https://www.linkedin.com/secure/developer">https://www.linkedin.com/secure/developer</a> and create a new application.</li>
                                         <li>Fill out any required fields such as the application name and description.</li>
                                         <li>Put your website domain in the <b>Integration URL</b> field. It should match with the current hostname (<b><?php echo $_SERVER['SERVER_NAME']; ?></b>)</li>
                                         <li>Set the Application Type to <b>Web Application</b>.</li>
                                         <li>Once you have registered, copy and paste the created application credentials into this setup page.</li>
                                     </ol>
                                <p></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_linkedin_app_key">LinkedIn Application Key</label>
                            <div class="controls col-md-8">
                                <input type="text" id="auth_linkedin_app_key" name="auth_linkedin_app_key" value="" class="input form-control">
                                <p class="help-block">LinkedIn Application Key</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_linkedin_app_secret">LinkedIn Application Secret</label>
                            <div class="controls col-md-8">
                                <input type="text" id="auth_linkedin_app_secret" name="auth_linkedin_app_secret" value="" class="input form-control">
                                <p class="help-block">LinkedIn Application Secret</p>
                            </div>
                        </div>
                        <a class="btn btn-primary btn-change-tab" href="#tab6">Previous</a>
                        <a class="btn btn-primary btn-change-tab" href="#tab8">Next</a>
                    </div>

                    <div class="tab-pane" id="tab8">
                        <h3>MySpace Authentication</h3>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_enable_myspace">Allow MySpace Authentication</label>
                            <div class="controls col-md-8">
                                <input type="checkbox" id="auth_enable_myspace" name="auth_enable_myspace" class="input" value="true">
                                <p class="help-block">
                                    To Allow MySpace Authentication:
                                     </p><ol>
                                         <li>Go to <a target="__blank" href="http://www.developer.myspace.com/">http://www.developer.myspace.com/</a> and create a new application.</li>
                                         <li>Fill out any required fields such as the application name and description.</li>
                                         <li>Put your website domain in the <b>External Url</b> and <b>External Callback Validation</b> fields. It should match with the current hostname (<b><?php echo $_SERVER['SERVER_NAME']; ?></b>)</li>
                                         <li>Once you have registered, copy and paste the created application credentials into this setup page.</li>
                                     </ol>
                                <p></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_myspace_app_id">MySpace Application Key</label>
                            <div class="controls col-md-8">
                                <input type="text" id="auth_myspace_app_key" name="auth_myspace_app_key" value="" class="input form-control">
                                <p class="help-block">MySpace Application Key</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_myspace_app_secret">MySpace Application Secret</label>
                            <div class="controls col-md-8">
                                <input type="text" id="auth_myspace_app_secret" name="auth_myspace_app_secret" value="" class="input form-control">
                                <p class="help-block">MySpace Application Secret</p>
                            </div>
                        </div>
                        <a class="btn btn-primary btn-change-tab" href="#tab7">Previous</a>
                        <a class="btn btn-primary btn-change-tab" href="#tab9">Next</a>
                    </div>

                    <div class="tab-pane" id="tab9">
                        <h3>Foursquare Authentication</h3>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_enable_foursquare">Allow Foursquare Authentication</label>
                            <div class="controls col-md-8">
                                <input type="checkbox" id="auth_enable_foursquare" name="auth_enable_foursquare" class="input" value="true">
                                <p class="help-block">
                                    To Allow Foursquare Authentication:
                                     </p><ol>
                                         <li>Go to <a target="_blank" href="https://www.foursquare.com/oauth/">https://www.foursquare.com/oauth/</a> and create a new application.</li>
                                         <li>Fill out any required fields such as the application name and description.</li>
                                         <li>Provide this URL as the <b>Callback URL</b> for your application: <b><?php echo site_url('main/hauth/endpoint/?hauth.done=Foursquare'); ?></b></li>
                                         <li>Once you have registered, copy and paste the created application credentials into this setup page.</li>
                                     </ol>
                                <p></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_foursquare_app_id">Foursquare Application ID</label>
                            <div class="controls col-md-8">
                                <input type="text" id="auth_foursquare_app_id" name="auth_foursquare_app_id" value="" class="input form-control">
                                <p class="help-block">Foursquare Application ID</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_foursquare_app_secret">foursquare Application Secret</label>
                            <div class="controls col-md-8">
                                <input type="text" id="auth_foursquare_app_secret" name="auth_foursquare_app_secret" value="" class="input form-control">
                                <p class="help-block">Foursquare Application Secret</p>
                            </div>
                        </div>
                        <a class="btn btn-primary btn-change-tab" href="#tab8">Previous</a>
                        <a class="btn btn-primary btn-change-tab" href="#tab10">Next</a>
                    </div>

                    <div class="tab-pane" id="tab10">
                        <h3>Windows Live Authentication</h3>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_enable_windows_live">Allow Windows Live Authentication</label>
                            <div class="controls col-md-8">
                                <input type="checkbox" id="auth_enable_windows_live" name="auth_enable_windows_live" class="input" value="true">
                                <p class="help-block">
                                    To Allow Windows Live Authentication:
                                     </p><ol>
                                         <li>Go to <a target="__blank" href="https://manage.dev.live.com/ApplicationOverview.aspx">https://manage.dev.live.com/<br>ApplicationOverview.aspx</a> and create a new application.</li>
                                         <li>Fill out any required fields such as the application name and description.</li>
                                         <li>Put your website domain in the <b>Redirect Domain</b> field. It should match with the current hostname (<b><?php $_SERVER['SERVER_NAME'] ?></b>)</li>
                                         <li>Once you have registered, copy and paste the created application credentials into this setup page.</li>
                                     </ol>
                                <p></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_windows_live_app_id">Windows Live Application ID</label>
                            <div class="controls col-md-8">
                                <input type="text" id="auth_windows_live_app_id" name="auth_windows_live_app_id" value="" class="input form-control">
                                <p class="help-block">Windows Live Application ID</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_windows_live_app_secret">windows_live Application Secret</label>
                            <div class="controls col-md-8">
                                <input type="text" id="auth_windows_live_app_secret" name="auth_windows_live_app_secret" value="" class="input form-control">
                                <p class="help-block">Windows Live Application Secret</p>
                            </div>
                        </div>
                        <a class="btn btn-primary btn-change-tab" href="#tab9">Previous</a>
                        <a class="btn btn-primary btn-change-tab" href="#tab11">Next</a>
                    </div>

                    <div class="tab-pane" id="tab11">
                        <h3>Open ID Authentication</h3>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_enable_open_id">Allow Open Id Authentication</label>
                            <div class="controls col-md-8">
                                <input type="checkbox" id="auth_enable_open_id" name="auth_enable_open_id" class="input" value="true">
                                <p class="help-block">Enable Open Id Authentication (No registration required for OpenID based providers)</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="auth_enable_aol">Allow AOL Authentication</label>
                            <div class="controls col-md-8">
                                <input type="checkbox" id="auth_enable_aol" name="auth_enable_aol" class="input" value="true">
                                <p class="help-block">Enable AOL Authentication (No registration required for OpenID based providers)</p>
                            </div>
                        </div>
                        <a class="btn btn-primary btn-change-tab" href="#tab10">Previous</a>
                    </div>
                </div>
            </div>
            <div id="div-right-pane" class="col-sm-4 col-md-4 col-xs-4">
                <div id="div-error-warning-message">
                    <div id="div-error-message" class="alert alert-danger">
                        <strong>ERRORS:</strong>
                        <ul id="ul-error-message"></ul>
                    </div>
                    <div id="div-warning-message" class="alert alert-warning">
                        <strong>WARNINGS:</strong>
                        <ul id="ul-warning-message"></ul>
                    </div>
                    <div id="div-success-message" class="alert alert-success">
                        <strong>GREAT !!!</strong>, you can now install No-CMS without worrying anything.
                    </div>
                    <div id="div-info-message" class="alert alert-info">
                        <strong>Checking ...</strong>, please wait for a while
                    </div>
                    <div style="margin-top:20px; margin-bottom:20px;">
                        <button id="btn-install" class="btn btn-primary btn-lg" name="Install" disabled="disabled" value="INSTALL NOW">INSTALL NOW</button>
                        <img id="img-loader" src="<?php echo base_url('modules/installer/assets/ajax-loader.gif'); ?>">
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="<?php echo base_url('assets/grocery_crud/js/'.JQUERY_FILE_NAME); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
    <script type="text/javascript">
        var REQUEST;
        var RUNNING_REQUEST = false;
        var SUCCESS = false;

        function adjust_error_warning_message(){
            $('#div-error-warning-message').width($('#div-body').width()*0.3);
            var navbar_height = $('.navbar-fixed-top').height();
            var tab_height = $('.nav-tabs').height();
            if (($('.nav-tabs')[0].offsetTop+tab_height) < ($(document).scrollTop()+navbar_height)){
                $('#div-error-warning-message').css({position: "fixed", top:navbar_height+10});
            }else if ($('.form-horizontal')[0].offsetTop < ($(document).scrollTop()+navbar_height)){
                $('#div-error-warning-message').css({position: "fixed", top:navbar_height+tab_height+10});
            }else{
                $('#div-error-warning-message').css({position: "static", top: 10});
            }
        }

        $(document).ready(function(){
            // just for fun, use one of SCANDAL member as default admin real name
            var real_name_list = new Array('Haruna Ono', 'Tomomi Ogawa',
                'Mami Sasazaki', 'Rina Suzuki');
            var real_name_index = Math.floor((Math.random()*4));
            var real_name = real_name_list[real_name_index];
            $('#admin_real_name').val(real_name);
            $('#div-error-warning-message').append('<img style="opacity:0.4; max-width:100%; max-height:70%;" src="<?php echo base_url('modules/installer/assets'); ?>/'+real_name+'.jpg" />');
            // magic :)
            $(document).on('scroll', function(){
                adjust_error_warning_message();
            });
            adjust_error_warning_message();
            // check things
            check();
        });
        $(window).resize(function() {
            adjust_error_warning_message();
        });
        $("input, select").change(function(){
            check();
        });
        $("input:not(#db_name), select").keyup(function(event){
            var code = event.keyCode || event.which;
            if(code == 13 || code == 9){
                check();
            }else if($(this).attr('id') == 'admin_password' || $(this).attr('id') == 'admin_confirm_password'){
                if($('#admin_password').val() == $('#admin_confirm_password').val()){
                    check();
                }
            }
        });

        // default port
        $("#db_protocol").change(function(){
            var protocol = $("#db_protocol").val();
            var port = '';
            if(protocol == 'mysql' || protocol == 'mysqli' || protocol == 'pdo_mysql'){
                port = '3306';
            }else if (protocol == 'pdo_pgsql'){
                port = '5432';
            }else{
                port = '';
            }
            $('#db_port').val(port);
        });

        // next or previous step
        $(".btn-change-tab").click(function(){
            var href = $(this).attr('href');
            $("ul.nav-tabs li").removeClass('active');
            $("div.tab-pane").removeClass('active');
            $("ul.nav-tabs li a[href='"+href+"']").parent().addClass('active');
            $("div.tab-pane[id='"+href.substr(1)+"']").addClass('active');
            return false;
        });

        // from error message
        $('body').on('click', '.a-change-tab', function(){
            var tab = $(this).attr('tab');
            var component = $(this).attr('component');
            $("ul.nav-tabs li").removeClass('active');
            $("div.tab-pane").removeClass('active');
            $("ul.nav-tabs li a[href='"+tab+"']").parent().addClass('active');
            $("div.tab-pane[id='"+tab.substr(1)+"']").addClass('active');
            if(typeof(component) != 'undefined' && component != ''){
                $('#'+component).focus();
            }
            return false;
        });

        $("#btn-install").click(function(){
            $(this).hide();
            $("#img-loader").show();
        });


        function check(){
            if(RUNNING_REQUEST){
                REQUEST.abort();
            }
            $('#btn-install').hide();
            $("#btn-install").attr('disabled','disabled');
            $('#div-success-message').hide();
            $('#div-warning-message').hide();
            $('#div-error-message').hide();
            $('#div-info-message').show();
            RUNNING_REQUEST = true;
            REQUEST = $.ajax({
                type : "POST",
                url : "<?php echo site_url('installer/installer/check'); ?>",
                dataType: "json",
                async : true,
                data : {
                    db_protocol                 : $("#db_protocol").val(),
                    db_host                     : $("#db_host").val(),
                    db_port                     : $("#db_port").val(),
                    db_username                 : $("#db_username").val(),
                    db_password                 : $("#db_password").val(),
                    db_name                     : $("#db_name").val(),
                    admin_user_name             : $("#admin_user_name").val(),
                    admin_real_name             : $("#admin_real_name").val(),
                    admin_password              : $("#admin_password").val(),
                    admin_confirm_password      : $("#admin_confirm_password").val(),
                    hide_index                  : $("#hide_index").attr('checked')? $("#hide_index").val(): '',
                    auth_enable_facebook        : $("#auth_enable_facebook").attr('checked')? $("#auth_enable_facebook").val(): '',
                    auth_enable_twitter         : $("#auth_enable_twitter").attr('checked')? $("#auth_enable_twitter").val(): '',
                    auth_enable_google          : $("#auth_enable_google").attr('checked')? $("#auth_enable_google").val(): '',
                    auth_enable_yahoo           : $("#auth_enable_yahoo").attr('checked')? $("#auth_enable_yahoo").val(): '',
                    auth_enable_linkedin        : $("#auth_enable_linkedin").attr('checked')? $("#auth_enable_linkedin").val(): '',
                    auth_enable_myspace         : $("#auth_enable_myspace").attr('checked')? $("#auth_enable_myspace").val(): '',
                    auth_enable_foursquare      : $("#auth_enable_foursquare").attr('checked')? $("#auth_enable_foursquare").val(): '',
                    auth_enable_windows_live    : $("#auth_enable_windows_live").attr('checked')? $("#auth_enable_windows_live").val(): '',
                    auth_enable_open_id         : $("#auth_enable_open_id").attr('checked')? $("#auth_enable_open_id").val(): '',
                    auth_enable_aol             : $("#auth_enable_aol").attr('checked')? $("#auth_enable_aol").val(): '',
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
                    $('#div-info-message').hide();
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
                },
                error: function(xhr, textStatus, errorThrown){
                    if(textStatus != 'abort'){
                        setTimeout(check, 50000);
                    }
                }
            });
        }
    </script>
</body>
