<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2013-11-14 11:23:07 --> Unable to connect to the database
ERROR - 2013-11-14 11:23:07 --> Unable to connect to the database
ERROR - 2013-11-14 11:23:07 --> Unable to connect to the database
ERROR - 2013-11-14 11:23:10 --> Unable to connect to the database
ERROR - 2013-11-14 11:23:10 --> Unable to connect to the database
ERROR - 2013-11-14 11:23:10 --> Unable to connect to the database
ERROR - 2013-11-14 11:23:10 --> Unable to connect to the database
ERROR - 2013-11-14 11:23:10 --> Unable to connect to the database
ERROR - 2013-11-14 11:23:10 --> Unable to connect to the database
ERROR - 2013-11-14 11:23:10 --> Unable to connect to the database
ERROR - 2013-11-14 11:23:10 --> Unable to connect to the database
ERROR - 2013-11-14 11:23:10 --> Unable to connect to the database
ERROR - 2013-11-14 11:23:23 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_login', NULL, 'Login', 'Login', NULL, 'Visitor need to login for authentication', 'main/login', 2, 1, 1, 0, NULL, 0, 'icon-th-large')
ERROR - 2013-11-14 11:23:23 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_forgot', NULL, 'Forgot Password', 'Forgot', NULL, 'Accidentally forgot password', 'main/forgot', 2, 3, 1, 0, NULL, 0, 'icon-th-large')
ERROR - 2013-11-14 11:23:23 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_logout', NULL, 'Logout', 'Logout', NULL, 'Logout for deauthentication', 'main/logout', 3, 2, 1, 0, NULL, 0, 'icon-th-large')
ERROR - 2013-11-14 11:23:23 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_management', NULL, 'CMS Management', 'CMS Management', NULL, 'The main management of the CMS. Including User, Group, Privilege and Navigation Management', 'main/management', 4, 6, 1, 0, NULL, 0, 'icon-th-large')
ERROR - 2013-11-14 11:23:23 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_register', NULL, 'Register', 'Register', NULL, 'New User Registration', 'main/register', 2, 4, 1, 0, NULL, 0, 'icon-th-large')
ERROR - 2013-11-14 11:23:23 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_change_profile', NULL, 'Change Profile', 'Change Profile', NULL, 'Change Current Profile', 'main/change_profile', 3, 5, 1, 0, NULL, 0, 'icon-th-large')
ERROR - 2013-11-14 11:23:24 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_index', NULL, 'Home', 'Home', NULL, 'There is no place like home :D', 'main/index', 1, 0, 1, 1, '<h2>\n  Welcome {{ user_name }}</h2>\n<p>\n This is the home page. You have several options to modify this page.</p>\n<ul>\n    <li>\n      <b>Using static page</b>\n      <p>\n           You can <em>activate</em> <strong>static option</strong> and <em>edit</em> the <strong>static content</strong> by using <a href=\"{{ site_url }}main/navigation/edit/17\">Navigation Management</a><br />\n           This is the most recommended way to do.</p>\n   </li>\n <li>\n      <b>Redirect default controller</b>\n        <p>\n           You can modify <code>$route[&#39;default_controller&#39;]</code> variable on<br />\n            <code>/application/config/routes.php</code>, around line 41.<br />\n            Please make sure that your default controller is valid.<br />\n         This is recommended if you also want your own page to be a default homepage.</p>\n  </li>\n <li>\n      <b>Using dynamic page and edit the view manually</b>\n      <p>\n           You can <em>deactivate</em>&nbsp;<strong>static option</strong> by using <a href=\"{{ site_url }}main/navigation/edit/17\">Navigation Management</a><br />\n          and edit the corresponding view on <code>/modules/main/index.php</code></p>\n   </li>\n</ul>\n<p>\n <div class=\"alert alert-info\"><b>Any other question? : </b><br />\n   Visit No-CMS forum here: <a href=\"http://getnocms.com/forum\">http://getnocms.com/forum</a><br />\n  Github user can visit No-CMS repo: <a href=\"https://github.com/goFrendiAsgard/No-CMS/\">https://github.com/goFrendiAsgard/No-CMS/</a><br />\n    While normal people can visit No-CMS blog: <a href=\"http://www.getnocms.com/\">http://www.getnocms.com/</a><br />\n  In case of you&#39;ve found a critical bug, you can also email me at <a href=\"mailto:gofrendiasgard@gmail.com\">gofrendiasgard@gmail.com</a><br />\n That&#39;s all. Start your new adventure with No-CMS !!!</p>\n</div>', 0, 'icon-home')
ERROR - 2013-11-14 11:23:24 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_language', NULL, 'Language', 'Language', NULL, 'Choose the language', 'main/language', 1, 0, 1, 0, NULL, 0, 'icon-th-large')
ERROR - 2013-11-14 11:23:24 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_third_party_auth', NULL, 'Third Party Authentication', 'Third Party Authentication', NULL, 'Third Party Authentication', 'main/hauth/index', 1, 0, 1, 0, NULL, 0, 'icon-th-large')
ERROR - 2013-11-14 11:23:26 --> Severity: Warning  --> copy(): The first argument to copy() function cannot be a directory /home/gofrendi/public_html/No-CMS/modules/installer/models/install_model.php 942
ERROR - 2013-11-14 11:23:26 --> Severity: Warning  --> copy(): The first argument to copy() function cannot be a directory /home/gofrendi/public_html/No-CMS/modules/installer/models/install_model.php 942
ERROR - 2013-11-14 11:23:32 --> Severity: Notice  --> Undefined index: default_controller /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 627
ERROR - 2013-11-14 11:23:32 --> Severity: Notice  --> Undefined index: default_controller /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 629
ERROR - 2013-11-14 11:23:32 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:27:18 --> Severity: Notice  --> Undefined index: default_controller /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 627
ERROR - 2013-11-14 11:27:18 --> Severity: Notice  --> Undefined index: default_controller /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 629
ERROR - 2013-11-14 11:27:18 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:28:14 --> Severity: Notice  --> Undefined index: default_controller /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 628
ERROR - 2013-11-14 11:28:14 --> Severity: Notice  --> Undefined index: default_controller /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 630
ERROR - 2013-11-14 11:28:14 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:28:15 --> Severity: Notice  --> Undefined index: default_controller /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 628
ERROR - 2013-11-14 11:28:15 --> Severity: Notice  --> Undefined index: default_controller /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 630
ERROR - 2013-11-14 11:28:15 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:28:16 --> Severity: Notice  --> Undefined index: default_controller /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 628
ERROR - 2013-11-14 11:28:16 --> Severity: Notice  --> Undefined index: default_controller /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 630
ERROR - 2013-11-14 11:28:16 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:28:16 --> Severity: Notice  --> Undefined index: default_controller /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 628
ERROR - 2013-11-14 11:28:16 --> Severity: Notice  --> Undefined index: default_controller /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 630
ERROR - 2013-11-14 11:28:16 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:28:17 --> Severity: Notice  --> Undefined index: default_controller /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 628
ERROR - 2013-11-14 11:28:17 --> Severity: Notice  --> Undefined index: default_controller /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 630
ERROR - 2013-11-14 11:28:17 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:28:17 --> Severity: Notice  --> Undefined index: default_controller /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 628
ERROR - 2013-11-14 11:28:17 --> Severity: Notice  --> Undefined index: default_controller /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 630
ERROR - 2013-11-14 11:28:17 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:28:17 --> Severity: Notice  --> Undefined index: default_controller /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 628
ERROR - 2013-11-14 11:28:17 --> Severity: Notice  --> Undefined index: default_controller /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 630
ERROR - 2013-11-14 11:28:17 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:28:58 --> Severity: Notice  --> Undefined index: default_controller /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 630
ERROR - 2013-11-14 11:28:58 --> Severity: Notice  --> Undefined index: default_controller /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 632
ERROR - 2013-11-14 11:28:58 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:34:18 --> Severity: Notice  --> Undefined index: default_controller /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 630
ERROR - 2013-11-14 11:34:18 --> Severity: Notice  --> Undefined index: default_controller /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 632
ERROR - 2013-11-14 11:34:18 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:35:16 --> Severity: Notice  --> Undefined index: default_controller /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 630
ERROR - 2013-11-14 11:35:16 --> Severity: Notice  --> Undefined index: default_controller /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 632
ERROR - 2013-11-14 11:35:16 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:36:22 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:36:50 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:37:46 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:38:24 --> Severity: Notice  --> Undefined variable: navigaiton_name /home/gofrendi/public_html/No-CMS/application/core/MY_Controller.php 631
ERROR - 2013-11-14 11:38:24 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:38:33 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:39:09 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:39:28 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:43:51 --> Unable to connect to the database
ERROR - 2013-11-14 11:43:51 --> Unable to connect to the database
ERROR - 2013-11-14 11:43:51 --> Unable to connect to the database
ERROR - 2013-11-14 11:43:53 --> Unable to connect to the database
ERROR - 2013-11-14 11:43:53 --> Unable to connect to the database
ERROR - 2013-11-14 11:43:53 --> Unable to connect to the database
ERROR - 2013-11-14 11:43:54 --> Unable to connect to the database
ERROR - 2013-11-14 11:43:54 --> Unable to connect to the database
ERROR - 2013-11-14 11:43:54 --> Unable to connect to the database
ERROR - 2013-11-14 11:43:54 --> Unable to connect to the database
ERROR - 2013-11-14 11:43:54 --> Unable to connect to the database
ERROR - 2013-11-14 11:43:54 --> Unable to connect to the database
ERROR - 2013-11-14 11:44:06 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_login', NULL, 'Login', 'Login', NULL, 'Visitor need to login for authentication', 'main/login', 2, 1, 1, 0, NULL, 0, 'icon-th-large')
ERROR - 2013-11-14 11:44:06 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_forgot', NULL, 'Forgot Password', 'Forgot', NULL, 'Accidentally forgot password', 'main/forgot', 2, 3, 1, 0, NULL, 0, 'icon-th-large')
ERROR - 2013-11-14 11:44:06 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_logout', NULL, 'Logout', 'Logout', NULL, 'Logout for deauthentication', 'main/logout', 3, 2, 1, 0, NULL, 0, 'icon-th-large')
ERROR - 2013-11-14 11:44:06 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_management', NULL, 'CMS Management', 'CMS Management', NULL, 'The main management of the CMS. Including User, Group, Privilege and Navigation Management', 'main/management', 4, 6, 1, 0, NULL, 0, 'icon-th-large')
ERROR - 2013-11-14 11:44:06 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_register', NULL, 'Register', 'Register', NULL, 'New User Registration', 'main/register', 2, 4, 1, 0, NULL, 0, 'icon-th-large')
ERROR - 2013-11-14 11:44:06 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_change_profile', NULL, 'Change Profile', 'Change Profile', NULL, 'Change Current Profile', 'main/change_profile', 3, 5, 1, 0, NULL, 0, 'icon-th-large')
ERROR - 2013-11-14 11:44:06 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_index', NULL, 'Home', 'Home', NULL, 'There is no place like home :D', 'main/index', 1, 0, 1, 1, '<h2>\n  Welcome {{ user_name }}</h2>\n<p>\n This is the home page. You have several options to modify this page.</p>\n<ul>\n    <li>\n      <b>Using static page</b>\n      <p>\n           You can <em>activate</em> <strong>static option</strong> and <em>edit</em> the <strong>static content</strong> by using <a href=\"{{ site_url }}main/navigation/edit/17\">Navigation Management</a><br />\n           This is the most recommended way to do.</p>\n   </li>\n <li>\n      <b>Redirect default controller</b>\n        <p>\n           You can modify <code>$route[&#39;default_controller&#39;]</code> variable on<br />\n            <code>/application/config/routes.php</code>, around line 41.<br />\n            Please make sure that your default controller is valid.<br />\n         This is recommended if you also want your own page to be a default homepage.</p>\n  </li>\n <li>\n      <b>Using dynamic page and edit the view manually</b>\n      <p>\n           You can <em>deactivate</em>&nbsp;<strong>static option</strong> by using <a href=\"{{ site_url }}main/navigation/edit/17\">Navigation Management</a><br />\n          and edit the corresponding view on <code>/modules/main/index.php</code></p>\n   </li>\n</ul>\n<p>\n <div class=\"alert alert-info\"><b>Any other question? : </b><br />\n   Visit No-CMS forum here: <a href=\"http://getnocms.com/forum\">http://getnocms.com/forum</a><br />\n  Github user can visit No-CMS repo: <a href=\"https://github.com/goFrendiAsgard/No-CMS/\">https://github.com/goFrendiAsgard/No-CMS/</a><br />\n    While normal people can visit No-CMS blog: <a href=\"http://www.getnocms.com/\">http://www.getnocms.com/</a><br />\n  In case of you&#39;ve found a critical bug, you can also email me at <a href=\"mailto:gofrendiasgard@gmail.com\">gofrendiasgard@gmail.com</a><br />\n That&#39;s all. Start your new adventure with No-CMS !!!</p>\n</div>', 0, 'icon-home')
ERROR - 2013-11-14 11:44:06 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_language', NULL, 'Language', 'Language', NULL, 'Choose the language', 'main/language', 1, 0, 1, 0, NULL, 0, 'icon-th-large')
ERROR - 2013-11-14 11:44:06 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_third_party_auth', NULL, 'Third Party Authentication', 'Third Party Authentication', NULL, 'Third Party Authentication', 'main/hauth/index', 1, 0, 1, 0, NULL, 0, 'icon-th-large')
ERROR - 2013-11-14 11:44:08 --> Severity: Warning  --> copy(): The first argument to copy() function cannot be a directory /home/gofrendi/public_html/No-CMS/modules/installer/models/install_model.php 942
ERROR - 2013-11-14 11:44:08 --> Severity: Warning  --> copy(): The first argument to copy() function cannot be a directory /home/gofrendi/public_html/No-CMS/modules/installer/models/install_model.php 942
ERROR - 2013-11-14 11:44:12 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:46:14 --> Unable to connect to the database
ERROR - 2013-11-14 11:46:14 --> Unable to connect to the database
ERROR - 2013-11-14 11:46:14 --> Unable to connect to the database
ERROR - 2013-11-14 11:46:17 --> Unable to connect to the database
ERROR - 2013-11-14 11:46:17 --> Unable to connect to the database
ERROR - 2013-11-14 11:46:17 --> Unable to connect to the database
ERROR - 2013-11-14 11:46:17 --> Unable to connect to the database
ERROR - 2013-11-14 11:46:17 --> Unable to connect to the database
ERROR - 2013-11-14 11:46:17 --> Unable to connect to the database
ERROR - 2013-11-14 11:46:17 --> Unable to connect to the database
ERROR - 2013-11-14 11:46:17 --> Unable to connect to the database
ERROR - 2013-11-14 11:46:17 --> Unable to connect to the database
ERROR - 2013-11-14 11:46:18 --> Unable to connect to the database
ERROR - 2013-11-14 11:46:26 --> Query error: Unknown table 'cms_main_config' - Invalid query: DROP TABLE `cms_main_config`
ERROR - 2013-11-14 11:46:26 --> Query error: Unknown table 'cms_main_module_dependency' - Invalid query: DROP TABLE `cms_main_module_dependency`
ERROR - 2013-11-14 11:46:26 --> Query error: Unknown table 'cms_main_module' - Invalid query: DROP TABLE `cms_main_module`
ERROR - 2013-11-14 11:46:26 --> Query error: Unknown table 'cms_main_group_privilege' - Invalid query: DROP TABLE `cms_main_group_privilege`
ERROR - 2013-11-14 11:46:26 --> Query error: Unknown table 'cms_main_group_navigation' - Invalid query: DROP TABLE `cms_main_group_navigation`
ERROR - 2013-11-14 11:46:26 --> Query error: Unknown table 'cms_main_group_widget' - Invalid query: DROP TABLE `cms_main_group_widget`
ERROR - 2013-11-14 11:46:26 --> Query error: Unknown table 'cms_main_group_user' - Invalid query: DROP TABLE `cms_main_group_user`
ERROR - 2013-11-14 11:46:26 --> Query error: Unknown table 'cms_main_group' - Invalid query: DROP TABLE `cms_main_group`
ERROR - 2013-11-14 11:46:26 --> Query error: Unknown table 'cms_main_quicklink' - Invalid query: DROP TABLE `cms_main_quicklink`
ERROR - 2013-11-14 11:46:26 --> Query error: Unknown table 'cms_main_navigation' - Invalid query: DROP TABLE `cms_main_navigation`
ERROR - 2013-11-14 11:46:26 --> Query error: Unknown table 'cms_main_widget' - Invalid query: DROP TABLE `cms_main_widget`
ERROR - 2013-11-14 11:46:26 --> Query error: Unknown table 'cms_main_privilege' - Invalid query: DROP TABLE `cms_main_privilege`
ERROR - 2013-11-14 11:46:26 --> Query error: Unknown table 'cms_main_user' - Invalid query: DROP TABLE `cms_main_user`
ERROR - 2013-11-14 11:46:26 --> Query error: Unknown table 'cms_main_authorization' - Invalid query: DROP TABLE `cms_main_authorization`
ERROR - 2013-11-14 11:46:26 --> Query error: Unknown table 'cms_ci_sessions' - Invalid query: DROP TABLE `cms_ci_sessions`
ERROR - 2013-11-14 11:46:28 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_login', NULL, 'Login', 'Login', NULL, 'Visitor need to login for authentication', 'main/login', 2, 1, 1, 0, NULL, 0, 'icon-th-large')
ERROR - 2013-11-14 11:46:28 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_forgot', NULL, 'Forgot Password', 'Forgot', NULL, 'Accidentally forgot password', 'main/forgot', 2, 3, 1, 0, NULL, 0, 'icon-th-large')
ERROR - 2013-11-14 11:46:28 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_logout', NULL, 'Logout', 'Logout', NULL, 'Logout for deauthentication', 'main/logout', 3, 2, 1, 0, NULL, 0, 'icon-th-large')
ERROR - 2013-11-14 11:46:28 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_management', NULL, 'CMS Management', 'CMS Management', NULL, 'The main management of the CMS. Including User, Group, Privilege and Navigation Management', 'main/management', 4, 6, 1, 0, NULL, 0, 'icon-th-large')
ERROR - 2013-11-14 11:46:28 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_register', NULL, 'Register', 'Register', NULL, 'New User Registration', 'main/register', 2, 4, 1, 0, NULL, 0, 'icon-th-large')
ERROR - 2013-11-14 11:46:28 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_change_profile', NULL, 'Change Profile', 'Change Profile', NULL, 'Change Current Profile', 'main/change_profile', 3, 5, 1, 0, NULL, 0, 'icon-th-large')
ERROR - 2013-11-14 11:46:28 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_index', NULL, 'Home', 'Home', NULL, 'There is no place like home :D', 'main/index', 1, 0, 1, 1, '<h2>\n  Welcome {{ user_name }}</h2>\n<p>\n This is the home page. You have several options to modify this page.</p>\n<ul>\n    <li>\n      <b>Using static page</b>\n      <p>\n           You can <em>activate</em> <strong>static option</strong> and <em>edit</em> the <strong>static content</strong> by using <a href=\"{{ site_url }}main/navigation/edit/17\">Navigation Management</a><br />\n           This is the most recommended way to do.</p>\n   </li>\n <li>\n      <b>Redirect default controller</b>\n        <p>\n           You can modify <code>$route[&#39;default_controller&#39;]</code> variable on<br />\n            <code>/application/config/routes.php</code>, around line 41.<br />\n            Please make sure that your default controller is valid.<br />\n         This is recommended if you also want your own page to be a default homepage.</p>\n  </li>\n <li>\n      <b>Using dynamic page and edit the view manually</b>\n      <p>\n           You can <em>deactivate</em>&nbsp;<strong>static option</strong> by using <a href=\"{{ site_url }}main/navigation/edit/17\">Navigation Management</a><br />\n          and edit the corresponding view on <code>/modules/main/index.php</code></p>\n   </li>\n</ul>\n<p>\n <div class=\"alert alert-info\"><b>Any other question? : </b><br />\n   Visit No-CMS forum here: <a href=\"http://getnocms.com/forum\">http://getnocms.com/forum</a><br />\n  Github user can visit No-CMS repo: <a href=\"https://github.com/goFrendiAsgard/No-CMS/\">https://github.com/goFrendiAsgard/No-CMS/</a><br />\n    While normal people can visit No-CMS blog: <a href=\"http://www.getnocms.com/\">http://www.getnocms.com/</a><br />\n  In case of you&#39;ve found a critical bug, you can also email me at <a href=\"mailto:gofrendiasgard@gmail.com\">gofrendiasgard@gmail.com</a><br />\n That&#39;s all. Start your new adventure with No-CMS !!!</p>\n</div>', 0, 'icon-home')
ERROR - 2013-11-14 11:46:28 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_language', NULL, 'Language', 'Language', NULL, 'Choose the language', 'main/language', 1, 0, 1, 0, NULL, 0, 'icon-th-large')
ERROR - 2013-11-14 11:46:28 --> Query error: Column 'parent_id' cannot be null - Invalid query: INSERT INTO `cms_main_navigation` (`navigation_name`, `parent_id`, `title`, `page_title`, `page_keyword`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`, `only_content`, `bootstrap_glyph`) VALUES ('main_third_party_auth', NULL, 'Third Party Authentication', 'Third Party Authentication', NULL, 'Third Party Authentication', 'main/hauth/index', 1, 0, 1, 0, NULL, 0, 'icon-th-large')
ERROR - 2013-11-14 11:46:30 --> Severity: Warning  --> copy(): The first argument to copy() function cannot be a directory /home/gofrendi/public_html/No-CMS/modules/installer/models/install_model.php 942
ERROR - 2013-11-14 11:46:30 --> Severity: Warning  --> copy(): The first argument to copy() function cannot be a directory /home/gofrendi/public_html/No-CMS/modules/installer/models/install_model.php 942
ERROR - 2013-11-14 11:46:33 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:52:13 --> Unable to connect to the database
ERROR - 2013-11-14 11:52:13 --> Unable to connect to the database
ERROR - 2013-11-14 11:52:13 --> Unable to connect to the database
ERROR - 2013-11-14 11:52:16 --> Unable to connect to the database
ERROR - 2013-11-14 11:52:16 --> Unable to connect to the database
ERROR - 2013-11-14 11:52:16 --> Unable to connect to the database
ERROR - 2013-11-14 11:52:16 --> Unable to connect to the database
ERROR - 2013-11-14 11:52:16 --> Unable to connect to the database
ERROR - 2013-11-14 11:52:16 --> Unable to connect to the database
ERROR - 2013-11-14 11:52:16 --> Unable to connect to the database
ERROR - 2013-11-14 11:52:16 --> Unable to connect to the database
ERROR - 2013-11-14 11:52:16 --> Unable to connect to the database
ERROR - 2013-11-14 11:52:29 --> Severity: Warning  --> copy(): The first argument to copy() function cannot be a directory /home/gofrendi/public_html/No-CMS/modules/installer/models/install_model.php 943
ERROR - 2013-11-14 11:52:29 --> Severity: Warning  --> copy(): The first argument to copy() function cannot be a directory /home/gofrendi/public_html/No-CMS/modules/installer/models/install_model.php 943
ERROR - 2013-11-14 11:52:33 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:55:14 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:55:16 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:55:16 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:55:17 --> 404 Page Not Found --> 
ERROR - 2013-11-14 11:57:06 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:06:53 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:08:54 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:10:47 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:11:21 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:14:23 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:19:22 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:19:37 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:21:51 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:22:22 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:22:29 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:22:45 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:22:51 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:22:58 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:25:07 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:26:10 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:27:33 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:27:34 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:27:37 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:27:45 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:27:51 --> Severity: Error  --> Uncaught exception 'Exception' with message 'It seems that the folder "/home/gofrendi/public_html/No-CMS/modules/pendaftaran_wisuda/assets/uploads" for the field name
                    "foto" doesn't exists. Please create the folder and try again.' in /home/gofrendi/public_html/No-CMS/application/libraries/Grocery_CRUD.php:4922
Stack trace:
#0 /home/gofrendi/public_html/No-CMS/modules/pendaftaran_wisuda/controllers/manage_calon_wisudawan.php(212): Grocery_CRUD->set_field_upload('foto', 'modules/pendaft...')
#1 [internal function]: Manage_Calon_Wisudawan->index()
#2 /home/gofrendi/public_html/No-CMS/system/core/CodeIgniter.php(375): call_user_func_array(Array, Array)
#3 /home/gofrendi/public_html/No-CMS/index.php(279): require_once('/home/gofrendi/...')
#4 {main}
  thrown /home/gofrendi/public_html/No-CMS/application/libraries/Grocery_CRUD.php 4922
ERROR - 2013-11-14 12:29:16 --> Severity: Warning  --> date() expects parameter 2 to be long, object given /home/gofrendi/public_html/No-CMS/modules/pendaftaran_wisuda/views/manage_calon_wisudawan_view.php 29
ERROR - 2013-11-14 12:29:16 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:32:47 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:34:39 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:34:48 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:35:03 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:36:39 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:36:47 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:36:51 --> Severity: Warning  --> call_user_func() expects parameter 1 to be a valid callback, function 'readonly' not found or invalid function name /home/gofrendi/public_html/No-CMS/application/libraries/Grocery_CRUD.php 2671
ERROR - 2013-11-14 12:36:51 --> Severity: Warning  --> call_user_func() expects parameter 1 to be a valid callback, function 'readonly' not found or invalid function name /home/gofrendi/public_html/No-CMS/application/libraries/Grocery_CRUD.php 2671
ERROR - 2013-11-14 12:36:51 --> Severity: Warning  --> call_user_func() expects parameter 1 to be a valid callback, function 'readonly' not found or invalid function name /home/gofrendi/public_html/No-CMS/application/libraries/Grocery_CRUD.php 2671
ERROR - 2013-11-14 12:36:51 --> Severity: Warning  --> call_user_func() expects parameter 1 to be a valid callback, function 'readonly' not found or invalid function name /home/gofrendi/public_html/No-CMS/application/libraries/Grocery_CRUD.php 2671
ERROR - 2013-11-14 12:36:51 --> Severity: Warning  --> call_user_func() expects parameter 1 to be a valid callback, function 'readonly' not found or invalid function name /home/gofrendi/public_html/No-CMS/application/libraries/Grocery_CRUD.php 2671
ERROR - 2013-11-14 12:36:51 --> Severity: Warning  --> call_user_func() expects parameter 1 to be a valid callback, function 'readonly' not found or invalid function name /home/gofrendi/public_html/No-CMS/application/libraries/Grocery_CRUD.php 2671
ERROR - 2013-11-14 12:36:51 --> Severity: Warning  --> Cannot modify header information - headers already sent by (output started at /home/gofrendi/public_html/No-CMS/system/core/Exceptions.php:186) /home/gofrendi/public_html/No-CMS/system/libraries/Session/drivers/Session_cookie.php 734
ERROR - 2013-11-14 12:36:51 --> Severity: Warning  --> Cannot modify header information - headers already sent by (output started at /home/gofrendi/public_html/No-CMS/system/core/Exceptions.php:186) /home/gofrendi/public_html/No-CMS/system/libraries/Session/drivers/Session_cookie.php 734
ERROR - 2013-11-14 12:36:51 --> Severity: Warning  --> Cannot modify header information - headers already sent by (output started at /home/gofrendi/public_html/No-CMS/system/core/Exceptions.php:186) /home/gofrendi/public_html/No-CMS/system/libraries/Session/drivers/Session_cookie.php 734
ERROR - 2013-11-14 12:36:51 --> Severity: Warning  --> Cannot modify header information - headers already sent by (output started at /home/gofrendi/public_html/No-CMS/system/core/Exceptions.php:186) /home/gofrendi/public_html/No-CMS/system/libraries/Session/drivers/Session_cookie.php 734
ERROR - 2013-11-14 12:36:51 --> Severity: Warning  --> Cannot modify header information - headers already sent by (output started at /home/gofrendi/public_html/No-CMS/system/core/Exceptions.php:186) /home/gofrendi/public_html/No-CMS/system/libraries/Session/drivers/Session_cookie.php 734
ERROR - 2013-11-14 12:36:51 --> Severity: Warning  --> Cannot modify header information - headers already sent by (output started at /home/gofrendi/public_html/No-CMS/system/core/Exceptions.php:186) /home/gofrendi/public_html/No-CMS/system/libraries/Session/drivers/Session_cookie.php 734
ERROR - 2013-11-14 12:36:51 --> Severity: Warning  --> Cannot modify header information - headers already sent by (output started at /home/gofrendi/public_html/No-CMS/system/core/Exceptions.php:186) /home/gofrendi/public_html/No-CMS/system/libraries/Session/drivers/Session_cookie.php 734
ERROR - 2013-11-14 12:36:51 --> Severity: Warning  --> Cannot modify header information - headers already sent by (output started at /home/gofrendi/public_html/No-CMS/system/core/Exceptions.php:186) /home/gofrendi/public_html/No-CMS/system/libraries/Session/drivers/Session_cookie.php 734
ERROR - 2013-11-14 12:36:51 --> Severity: Warning  --> Cannot modify header information - headers already sent by (output started at /home/gofrendi/public_html/No-CMS/system/core/Exceptions.php:186) /home/gofrendi/public_html/No-CMS/system/libraries/Session/drivers/Session_cookie.php 734
ERROR - 2013-11-14 12:36:51 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:37:58 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:43:00 --> Severity: Error  --> Call to undefined method Extended_Grocery_CRUD::append_field() /home/gofrendi/public_html/No-CMS/modules/pendaftaran_wisuda/controllers/manage_calon_wisudawan.php 107
ERROR - 2013-11-14 12:44:15 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:46:29 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:47:01 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:47:12 --> Severity: Notice  --> Undefined index: nrp /home/gofrendi/public_html/No-CMS/application/libraries/Extended_Grocery_CRUD.php 202
ERROR - 2013-11-14 12:47:35 --> Severity: Notice  --> Undefined index: nrp /home/gofrendi/public_html/No-CMS/application/libraries/Extended_Grocery_CRUD.php 202
ERROR - 2013-11-14 12:54:51 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:55:46 --> Severity: Warning  --> move_uploaded_file(modules/pendaftaran_wisuda/assets/uploads/bfd6d-383211_286105538088221_267192846646157_902080_307726032_n.jpg): failed to open stream: Permission denied /home/gofrendi/public_html/No-CMS/application/libraries/Grocery_CRUD.php 5233
ERROR - 2013-11-14 12:55:46 --> Severity: Warning  --> move_uploaded_file(): Unable to move '/tmp/phpzg6f78' to 'modules/pendaftaran_wisuda/assets/uploads/bfd6d-383211_286105538088221_267192846646157_902080_307726032_n.jpg' /home/gofrendi/public_html/No-CMS/application/libraries/Grocery_CRUD.php 5233
ERROR - 2013-11-14 12:55:46 --> Severity: Warning  --> filesize(): stat failed for modules/pendaftaran_wisuda/assets/uploads/bfd6d-383211_286105538088221_267192846646157_902080_307726032_n.jpg /home/gofrendi/public_html/No-CMS/application/libraries/Grocery_CRUD.php 5243
ERROR - 2013-11-14 12:55:46 --> Severity: Warning  --> unlink(modules/pendaftaran_wisuda/assets/uploads/bfd6d-383211_286105538088221_267192846646157_902080_307726032_n.jpg): No such file or directory /home/gofrendi/public_html/No-CMS/application/libraries/Grocery_CRUD.php 5256
ERROR - 2013-11-14 12:55:46 --> Severity: Warning  --> Cannot modify header information - headers already sent by (output started at /home/gofrendi/public_html/No-CMS/system/core/Exceptions.php:186) /home/gofrendi/public_html/No-CMS/application/libraries/Grocery_CRUD.php 5313
ERROR - 2013-11-14 12:55:46 --> Severity: Warning  --> Cannot modify header information - headers already sent by (output started at /home/gofrendi/public_html/No-CMS/system/core/Exceptions.php:186) /home/gofrendi/public_html/No-CMS/application/libraries/Grocery_CRUD.php 5323
ERROR - 2013-11-14 12:56:56 --> Severity: Notice  --> Undefined offset: 1 /home/gofrendi/public_html/No-CMS/application/libraries/Grocery_CRUD.php 1101
ERROR - 2013-11-14 12:56:56 --> Severity: Notice  --> Undefined offset: 2 /home/gofrendi/public_html/No-CMS/application/libraries/Grocery_CRUD.php 1101
ERROR - 2013-11-14 12:56:56 --> Severity: Warning  --> mktime() expects parameter 5 to be long, string given /home/gofrendi/public_html/No-CMS/application/libraries/Grocery_CRUD.php 1101
ERROR - 2013-11-14 12:56:56 --> Severity: Warning  --> rename(/home/gofrendi/public_html/No-CMS//modules/pendaftaran_wisuda/assets/uploads/81b8c-383211_286105538088221_267192846646157_90208,/home/gofrendi/public_html/No-CMS//modules/pendaftaran_wisuda/assets/uploads/06180183_Edwin Kartosudiro .jpg): No such file or directory /home/gofrendi/public_html/No-CMS/modules/pendaftaran_wisuda/controllers/manage_calon_wisudawan.php 316
ERROR - 2013-11-14 12:57:04 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:57:09 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:57:11 --> 404 Page Not Found --> 
ERROR - 2013-11-14 12:57:21 --> 404 Page Not Found --> 
ERROR - 2013-11-14 13:01:42 --> 404 Page Not Found --> 
ERROR - 2013-11-14 13:02:08 --> 404 Page Not Found --> 
ERROR - 2013-11-14 13:03:04 --> 404 Page Not Found --> 
ERROR - 2013-11-14 13:09:18 --> 404 Page Not Found --> 
ERROR - 2013-11-14 13:09:22 --> 404 Page Not Found --> 
ERROR - 2013-11-14 13:11:19 --> 404 Page Not Found --> 
ERROR - 2013-11-14 13:11:37 --> 404 Page Not Found --> 
