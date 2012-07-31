DROP TABLE IF EXISTS `cms_config`;
/*split*/
DROP TABLE IF EXISTS `cms_module_dependency`;
/*split*/
DROP TABLE IF EXISTS `cms_module`;
/*split*/
DROP TABLE IF EXISTS `cms_group_privilege`;
/*split*/
DROP TABLE IF EXISTS `cms_group_navigation`;
/*split*/
DROP TABLE IF EXISTS `cms_group_widget`;
/*split*/
DROP TABLE IF EXISTS `cms_group_user`;
/*split*/
DROP TABLE IF EXISTS `cms_group`;
/*split*/
DROP TABLE IF EXISTS `cms_quicklink`;
/*split*/
DROP TABLE IF EXISTS `cms_navigation`;
/*split*/
DROP TABLE IF EXISTS `cms_widget`;
/*split*/
DROP TABLE IF EXISTS `cms_privilege`;
/*split*/
DROP TABLE IF EXISTS `cms_user`;
/*split*/
DROP TABLE IF EXISTS `cms_authorization`;
/*split*/
DROP TABLE IF EXISTS `ci_sessions`;
/*split*/
DROP TABLE IF EXISTS `help_topic`;
/*split*/
DROP TABLE IF EXISTS `help_group`;
/*split*/

CREATE TABLE `cms_authorization` (
  `authorization_id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `authorization_name` varchar(45) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`authorization_id`),
  UNIQUE KEY `authorization_name` (`authorization_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*split*/

CREATE TABLE `cms_group` (
  `group_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(45) NOT NULL,
  `description` text,
  PRIMARY KEY (`group_id`),
  UNIQUE KEY `group_name` (`group_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*split*/

CREATE TABLE `cms_widget` (
  `widget_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `widget_name` varchar(45) NOT NULL,
  `title` varchar(45) NOT NULL,
  `description` text,
  `url` varchar(45) DEFAULT NULL,
  `authorization_id` tinyint(4) unsigned NOT NULL DEFAULT '1',
  `active` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `index` int(20) NOT NULL DEFAULT '0',
  `is_static` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `static_content` text,
  `slug` varchar(50),
  PRIMARY KEY (`widget_id`),
  UNIQUE KEY `widget_name` (`widget_name`),
  KEY `authorization_id` (`authorization_id`),
  CONSTRAINT `cms_widget_ibfk_1` FOREIGN KEY (`authorization_id`) REFERENCES `cms_authorization` (`authorization_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*split*/

CREATE TABLE IF NOT EXISTS `cms_navigation` (
  `navigation_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `navigation_name` varchar(45) NOT NULL,
  `parent_id` int(20) unsigned DEFAULT NULL,
  `title` varchar(45) NOT NULL,
  `description` text,
  `url` varchar(100) DEFAULT NULL,
  `authorization_id` tinyint(4) unsigned NOT NULL DEFAULT '1',
  `index` int(20) NOT NULL DEFAULT '0',
  `active` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `is_static` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `static_content` text,
  PRIMARY KEY (`navigation_id`),
  UNIQUE KEY `navigation_name` (`navigation_name`),
  KEY `parent_id` (`parent_id`),
  KEY `authorization_id` (`authorization_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*split*/

CREATE TABLE `cms_quicklink` (
  `quicklink_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `navigation_id` int(20) unsigned NOT NULL,
  `index` int(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`quicklink_id`),
  UNIQUE KEY `navigation_id` (`navigation_id`),
  CONSTRAINT `cms_quicklink_ibfk_1` FOREIGN KEY (`navigation_id`) REFERENCES `cms_navigation` (`navigation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*split*/

CREATE TABLE `cms_privilege` (
  `privilege_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `privilege_name` varchar(45) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `description` text,
  `authorization_id` tinyint(4) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`privilege_id`),
  UNIQUE KEY `privilege_name` (`privilege_name`),
  KEY `authorization_id` (`authorization_id`),
  CONSTRAINT `cms_privilege_ibfk_1` FOREIGN KEY (`authorization_id`) REFERENCES `cms_authorization` (`authorization_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*split*/

CREATE TABLE `cms_user` (
  `user_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `activation_code` varchar(45) NULL,
  `real_name` varchar(45) DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT '1',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*split*/

CREATE TABLE `cms_group_widget` (
  `group_id` int(20) unsigned NOT NULL,
  `widget_id` int(20) unsigned NOT NULL,
  PRIMARY KEY (`group_id`,`widget_id`),
  KEY `widget_id` (`widget_id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `cms_group_widget_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `cms_group` (`group_id`),
  CONSTRAINT `cms_group_widget_ibfk_2` FOREIGN KEY (`widget_id`) REFERENCES `cms_widget` (`widget_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*split*/

CREATE TABLE `cms_group_navigation` (
  `group_id` int(20) unsigned NOT NULL,
  `navigation_id` int(20) unsigned NOT NULL,
  PRIMARY KEY (`group_id`,`navigation_id`),
  KEY `navigation_id` (`navigation_id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `cms_group_navigation_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `cms_group` (`group_id`),
  CONSTRAINT `cms_group_navigation_ibfk_2` FOREIGN KEY (`navigation_id`) REFERENCES `cms_navigation` (`navigation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*split*/

CREATE TABLE `cms_group_privilege` (
  `group_id` int(20) unsigned NOT NULL,
  `privilege_id` int(20) unsigned NOT NULL,
  PRIMARY KEY (`privilege_id`,`group_id`),
  KEY `group_id` (`group_id`),
  KEY `privilege_id` (`privilege_id`),
  CONSTRAINT `cms_group_privilege_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `cms_group` (`group_id`),
  CONSTRAINT `cms_group_privilege_ibfk_2` FOREIGN KEY (`privilege_id`) REFERENCES `cms_privilege` (`privilege_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*split*/

CREATE TABLE `cms_group_user` (
  `group_id` int(20) unsigned NOT NULL,
  `user_id` int(20) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `cms_group_user_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `cms_group` (`group_id`),
  CONSTRAINT `cms_group_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `cms_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*split*/

CREATE TABLE `cms_module` (
  `module_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `module_name` varchar(50) NOT NULL,
  `module_path` varchar(50) NOT NULL,
  `user_id` int(20) unsigned NOT NULL,
  PRIMARY KEY (`module_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `cms_module_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `cms_user` (`user_id`),
  UNIQUE KEY `module_path` (`module_path`),
  UNIQUE KEY `module_name` (`module_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*split*/

CREATE TABLE `cms_module_dependency` (
  `module_dependency_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(20) unsigned NOT NULL,
  `child_id` int(20) unsigned NOT NULL,
  PRIMARY KEY (`module_dependency_id`),
  KEY `parent_id` (`parent_id`),
  KEY `child_id` (`child_id`),
  CONSTRAINT `cms_module_dependency_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `cms_module` (`module_id`),
  CONSTRAINT `cms_module_dependency_ibfk_2` FOREIGN KEY (`child_id`) REFERENCES `cms_module` (`module_id`),
  UNIQUE KEY `module_parent_child` (`parent_id`, `child_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*split*/

CREATE TABLE `cms_config` (
  `config_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `config_name` varchar(50) NOT NULL,
  `value` varchar(200) NULL,
  `description` text,
  PRIMARY KEY (`config_id`),
  UNIQUE KEY `config_name` (`config_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*split*/

CREATE TABLE IF NOT EXISTS  `ci_sessions` (
    `session_id` varchar(40) DEFAULT '0' NOT NULL,
    `ip_address` varchar(16) DEFAULT '0' NOT NULL,
    `user_agent` varchar(120) NOT NULL,
    `last_activity` int(10) unsigned DEFAULT 0 NOT NULL,
    `user_data` text NOT NULL,
    PRIMARY KEY (`session_id`),
    KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;;
/*split*/

CREATE TABLE IF NOT EXISTS `help_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `content` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
/*split*/

CREATE TABLE IF NOT EXISTS `help_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `title` varchar(60) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
/*split*/

INSERT INTO `cms_authorization` (`authorization_id`, `authorization_name`, `description`) VALUES
(1, 'Everyone', 'All visitor of the web are permitted (e.g:view blog content)'),
(2, 'Unauthenticated', 'Only non-member visitor, they who hasn''t log in yet (e.g:view member registration page)'),
(3, 'Authenticated', 'Only member (e.g:change password)'),
(4, 'Authorized', 'Only member with certain privilege (depend on group)');
/*split*/

INSERT INTO `cms_group` (`group_id`, `group_name`, `description`) VALUES
(1, 'Admin', 'Every member of this group can do everything possible, but only programmer can turn the impossible into real :D'),
(2, 'Employee', 'Just an example, employee role');
/*split*/

INSERT INTO `cms_user` (`user_id`, `user_name`, `email`, `password`, `real_name`, `active`) VALUES
(1, '@adm_username', '@adm_email', '@adm_password', '@adm_realname', 1);
/*split*/

INSERT INTO `cms_module` (`module_id`, `module_name`, `module_path`, `user_id`) VALUES
(1, 'gofrendi.noCMS.wysiwyg', 'wysiwyg', 1),
(2, 'gofrendi.noCMS.moduleGenerator', 'module_generator', 1),
(3, 'gofrendi.noCMS.help', 'help', 1);
/*split*/


INSERT INTO `cms_navigation` (`navigation_id`, `navigation_name`, `parent_id`, `title`, `description`, `url`, `authorization_id`, `index`, `active`, `is_static`, `static_content`) VALUES
(1, 'main_login', NULL, 'Login', 'Visitor need to login for authentication', 'main/login', 2, 1, 1, 0, NULL),
(2, 'main_forgot', NULL, 'Forgot password', 'Accidentally forgot password', 'main/forgot', 2, 3, 1, 0, NULL),
(3, 'main_logout', NULL, 'Logout', 'Logout for deauthentication', 'main/logout', 3, 2, 1, 0, NULL),
(4, 'main_management', NULL, 'CMS Management', 'The main management of the CMS. Including User, Group, Privilege and Navigation Management', 'main/management', 4, 6, 1, 0, NULL),
(5, 'main_register', NULL, 'Register', 'New User Registration', 'main/register', 2, 4, 1, 0, NULL),
(6, 'main_change_profile', NULL, 'Change Profile', 'Change Current Profile', 'main/change_profile', 3, 5, 1, 0, NULL),
(7, 'main_group_management', 4, 'Group Management', 'Group Management', 'main/group', 4, 0, 1, 0, NULL),
(8, 'main_navigation_management', 4, 'Navigation Management', 'Navigation management', 'main/navigation', 4, 3, 1, 0, NULL),
(9, 'main_privilege_management', 4, 'Privilege Management', 'Privilege Management', 'main/privilege', 4, 2, 1, 0, NULL),
(10, 'main_user_management', 4, 'User Management', 'Manage User', 'main/user', 4, 1, 1, 0, NULL),
(11, 'main_module_management', 4, 'Module Management', 'Install Or Uninstall Thirdparty Module', 'main/module_management', 4, 5, 1, 0, NULL),
(12, 'main_change_theme', 4, 'Change Theme', 'Change Theme', 'main/change_theme', 4, 6, 1, 0, NULL),
(13, 'main_widget_management', 4, 'Widget Management', 'Manage Widgets', 'main/widget', 4, 4, 1, 0, NULL),
(14, 'main_quicklink_management', 4, 'Quick Link Management', 'Manage Quick Link', 'main/quicklink', 4, 7, 1, 0, NULL),
(15, 'main_config_management', 4, 'Configuration Management', 'Manage Configuration Parameters', 'main/config', 4, 8, 1, 0, NULL),
(16, 'main_index', NULL, 'Home', 'There is no place like home :D', 'main/index', 1, 0, 1, 0, NULL),
(17, 'wysiwyg_index', 4, 'WYSIWYG', NULL, 'wysiwyg', 4, 9, 1, 0, NULL),
(18, 'module_generator_index', 4, 'Module Generator', NULL, 'module_generator/index', 3, 10, 1, 0, NULL),
(19, 'help_index', NULL, 'No-CMS User Guide', NULL, 'help/index', 1, 11, 1, 0, NULL),
(20, 'help_help_group', 19, 'Topic Group', NULL, 'help/help_help_group', 4, 0, 1, 0, NULL),
(21, 'help_help_topic', 19, 'Topic', NULL, 'help/help_help_topic', 4, 1, 1, 0, NULL);

/*split*/

INSERT INTO `cms_quicklink` (`quicklink_id`, `navigation_id`, `index`) VALUES
(1, 16, 0),
(2, 5, 1),
(3, 2, 2),
(4, 19, 3);
/*split*/

INSERT INTO `cms_widget` (`widget_id`, `widget_name`, `title`, `description`, `url`, `authorization_id`, `active`, `index`, `is_static`, `static_content`, `slug`) VALUES
(1, 'login', 'Login', 'Visitor need to login for authentication', 'main/widget_login', 2, 1, 0, 0, NULL, 'sidebar'),
(2, 'logout', 'User Info', 'Logout', 'main/widget_logout', 3, 1, 1, 0, NULL, 'sidebar'),
(3, 'social_plugin', 'Share This Page !!', 'Addthis', 'main/widget_social_plugin', 1, 1, 2, 1, '<!-- AddThis Button BEGIN -->\n<div class="addthis_toolbox addthis_default_style "><a class="addthis_button_preferred_1"></a> <a class="addthis_button_preferred_2"></a> <a class="addthis_button_preferred_3"></a> <a class="addthis_button_preferred_4"></a> <a class="addthis_button_preferred_5"></a> <a class="addthis_button_preferred_6"></a> <a class="addthis_button_preferred_7"></a> <a class="addthis_button_preferred_8"></a> <a class="addthis_button_preferred_9"></a> <a class="addthis_button_preferred_10"></a> <a class="addthis_button_preferred_11"></a> <a class="addthis_button_preferred_12"></a> <a class="addthis_button_preferred_13"></a> <a class="addthis_button_preferred_14"></a> <a class="addthis_button_preferred_15"></a> <a class="addthis_button_preferred_16"></a> <a class="addthis_button_compact"></a> <a class="addthis_counter addthis_bubble_style"></a></div>\n<script src="http://s7.addthis.com/js/250/addthis_widget.js?domready=1" type="text/javascript"></script>\n<!-- AddThis Button END -->', 'sidebar'),
(4, 'google_search', 'Search', 'Search from google', '', 1, 0, 3, 1, '<!-- Google Custom Search Element -->\n<div id="cse" style="width: 100%;">Loading</div>\n<script src="http://www.google.com/jsapi" type="text/javascript"></script>\n<script type="text/javascript">// <![CDATA[\n    google.load(''search'', ''1'');\n    google.setOnLoadCallback(function(){var cse = new google.search.CustomSearchControl();cse.draw(''cse'');}, true);\n// ]]></script>', 'sidebar'),
(5, 'google_translate', 'Translate !!', '<p>The famous google translate</p>', '', 1, 0, 4, 1, '<!-- Google Translate Element -->\n<div id="google_translate_element" style="display:block"></div>\n<script>\nfunction googleTranslateElementInit() {\n  new google.translate.TranslateElement({pageLanguage: "af"}, "google_translate_element");\n};\n</script>\n<script src="http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>\n', 'sidebar'),
(6, 'calendar', 'Calendar', 'Indonesian Calendar', '', 1, 0, 5, 1, '<!-------Do not change below this line------->\n<div align="center" height="200px">\n    <iframe align="center" src="http://www.calendarlabs.com/calendars/web-content/calendar.php?cid=1001&uid=162232623&c=22&l=en&cbg=C3D9FF&cfg=000000&hfg=000000&hfg1=000000&ct=1&cb=1&cbc=2275FF&cf=verdana&cp=bottom&sw=0&hp=t&ib=0&ibc=&i=" width="170" height="155" marginwidth=0 marginheight=0 frameborder=no scrolling=no allowtransparency=''true''>\n    Loading...\n    </iframe>\n    <div align="center" style="width:140px;font-size:10px;color:#666;">\n        Powered by <a  href="http://www.calendarlabs.com/" target="_blank" style="font-size:10px;text-decoration:none;color:#666;">Calendar</a> Labs\n    </div>\n</div>\n\n<!-------Do not change above this line------->', 'sidebar'),
(7, 'google_map', 'Map', 'google map', '', 1, 0, 6, 1, '<!-- Google Maps Element Code -->\n<iframe frameborder=0 marginwidth=0 marginheight=0 border=0 style="border:0;margin:0;width:150px;height:250px;" src="http://www.google.com/uds/modules/elements/mapselement/iframe.html?maptype=roadmap&element=true" scrolling="no" allowtransparency="true"></iframe>', 'sidebar'),
(8, 'donate_nocms', 'Donate No-CMS', 'No-CMS Donation', NULL, 1, 1, 7, 1, '<div><form action="https://www.paypal.com/cgi-bin/webscr" method="post">\n<input type="hidden" name="cmd" value="_s-xclick" />\n<input type="hidden" name="hosted_button_id" value="VT38TLVZEZ9JN" />\n<input type="image" src="@base_url/assets/nocms/images/donation.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" width="165px" height="auto" />\n<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1" />\n</form></div>', 'advertisement');

/*split*/

INSERT INTO `cms_privilege` (`privilege_id`, `privilege_name`, `title`, `description`, `authorization_id`) VALUES
(1, 'cms_install_module', 'Install Module', 'Install Module is a very critical privilege, it allow authorized user to Install a module to the CMS.<br />By Installing module, the database structure can be changed. There might be some additional navigation and privileges added.<br /><br />You''d be better to give this authorization only authenticated and authorized user. (I suggest to make only admin have such a privilege)\n&nbsp;', 4),
(2, 'cms_manage_access', 'Manage Access', 'Manage access\n&nbsp;', 4);
/*split*/

INSERT INTO `cms_config` (`config_id`, `config_name`, `value`, `description`) VALUES
(1, 'site_name', 'No-CMS', 'Site title'),
(2, 'site_slogan', 'A Free CodeIgniter Based CMS Framework', 'Site slogan'),
(3, 'site_logo', '@base_url/assets/nocms/images/No-CMS-logo.png', 'Site logo'),
(4, 'site_favicon', '@base_url/assets/nocms/images/No-CMS-favicon.png', 'Site favicon'),
(5, 'site_footer', 'goFrendiAsgard &copy; 2011', 'Site footer'),
(6, 'site_theme', 'bootstrap', 'Site theme'),
(7, 'site_language', 'english', 'Site language'),
(8, 'max_menu_depth', '5', 'Depth of menu recursive'),
(9, 'cms_email_address', 'no-reply@No-CMS.com', 'Email address'),
(10, 'cms_email_name', 'admin of No-CMS', 'Email name'),
(11, 'cms_email_forgot_subject', 'Re-activate your account at No-CMS', 'Email subject'),
(12, 'cms_email_forgot_message', 'Dear, @realname<br />Click <a href="@activation_link">@activation_link</a> to reactivate your account', 'Email message'),
(13, 'cms_email_useragent', 'Codeigniter', 'Default : CodeIgniter'),
(14, 'cms_email_protocol', 'smtp', 'Default : smtp, Alternatives : mail, sendmail, smtp'),
(15, 'cms_email_mailpath', '/usr/sbin/sendmail','Default : /usr/sbin/sendmail'),
(16, 'cms_email_smtp_host', '','eg : ssl://smtp.googlemail.com'),
(17, 'cms_email_smtp_user', '','eg : your_gmail_address@gmail.com'),
(18, 'cms_email_smtp_pass', '','your password'),
(19, 'cms_email_smtp_port', '465','smtp port, default : 465'),
(20, 'cms_email_smtp_timeout', '30','default : 30'),
(21, 'cms_email_wordwrap', 'TRUE', 'Enable word-wrap. Default : true, Alternatives : true, false'),
(22, 'cms_email_wrapchars', '76	', 'Character count to wrap at.'),
(23, 'cms_email_mailtype', 'html', 'Type of mail. If you send HTML email you must send it as a complete web page. Make sure you do not have any relative links or relative image paths otherwise they will not work. Default : html, Alternatives : html, text'),
(24, 'cms_email_charset', 'utf-8', 'Character set (utf-8, iso-8859-1, etc.).'),
(25, 'cms_email_validate', 'FALSE', 'Whether to validate the email address. Default: true, Alternatives : true, false'),
(26, 'cms_email_priority', '3', '1, 2, 3, 4, 5	Email Priority. 1 = highest. 5 = lowest. 3 = normal.'),
(27, 'cms_email_crlf', '\n', '"\r\n" or "\n" or "\r"	Newline character. (Use "\r\n" to comply with RFC 822).'),
(28, 'cms_email_newline', '\n', '"\r\n" or "\n" or "\r"	Newline character. (Use "\r\n" to comply with RFC 822).'),
(29, 'cms_email_bcc_batch_mode', 'FALSE', 'Enable BCC Batch Mode. Default: false, Alternatives: true'),
(30, 'cms_email_bcc_batch_size', '200', 'Number of emails in each BCC batch.');
/*split*/

INSERT INTO `cms_group_user` (`group_id`, `user_id`) VALUES
(1, 1);
/*split*/

INSERT INTO `help_group` (`id`, `name`, `content`) VALUES
(1, 'Basic Info', 'This section contains basic info of No-CMS'),
(2, 'Installation', 'This section contains installation of No-CMS'),
(3, 'Getting Started', 'Fasten your seatbelt and get started'),
(4, 'More With Module', 'More advance topics about modules'),
(5, 'More With Theme', 'More advance topics about theme'),
(6, 'Module Generator', 'Make your life easier with Module Generator'),
(7, 'FAQ', 'Frequently Asked Questions');
/*split*/


INSERT INTO `help_topic` (`id`, `group_id`, `title`, `content`) VALUES
(1, 1, 'Overview', '<p>\n   No-CMS is a CMS-framework. It is a <a href="help/topic/no-cms_as_cms">CMS</a> and a <a href="help/topic/no-cms_as_framework">framework</a> in the same time. No-CMS is a basic CMS with some default features such as user authorization, menu, module and theme management. It is fully customizable and extensible, you can make your own module and your own themes. It provide freedom to make your very own CMS, which is not provided very well by any other CMS.</p>\n'),
(2, 1, 'Who is No-CMS for', 'No CMS will be good for you if you say yes for majority of these\nstatement:\n<ul>\n   <li>You are a web developer who use CodeIgniter framework.</li>\n   <li>You are tired of building the same things such an\n     authorization-authentication for every project.</li>\n  <li>You find that some part of your old project can be used for your\n      next project.</li>\n    <li>You are happy with CodeIgniter but you think some plug-ins and\n        features should be provided by default.</li>\n  <li>You want a simple and easy to learn framework that has 100%\n       compatibility with CodeIgniter.</li>\n  <li>You don''t want to learn too many new terms.</li>\n <li>You are familiar with HMVC plugins, and you think it is one of\n        "should be exists" feature in CodeIgniter.</li>\n   <li>You are in tight deadline, at least you need to provide the\n       prototype to your client.</li>\n</ul>'),
(3, 1, 'No-CMS as CMS', '<p>\n  No-CMS is a good enough CMS. It is different from Wordpress, Drupal or Joomla. Those CMS are built from developers for users. No-CMS is built by developer for developers, although everyone else can still use it as well. The main purpose of this CMS is to provide a good start of web application project, especially for CodeIgniter developer.</p>\n'),
(4, 1, 'No-CMS as framework', '<p>No-CMS is not just another CMS. No-CMS allows you to make your own\n  module and your own themes. This means that you (as developer) can make\n   a module that can be used for several project.</p>\n\n<p>No-CMS takes advantages of CodeIgniter as its core. It provides rich\n set of libraries for commonly needed task, as well as a simple\n    interface and logical structure to access these libraries. The main\n   advantage of CodeIgniter is you can creatively focus on your project by\n   minimizing the amount of code needed or a given task.</p>\n\n<p>No-CMS is also take advantages of several popular plugins such as</p>\n<ul>\n   <li>HMVC, to make fully modular separation</li>\n   <li>Phil Sturgeon''s Template, to make customizable themes</li>\n   <li>groceryCRUD, to build CRUD application in a minute</li>\n</ul>\n\n<p>Out of all, No-CMS also provide some common features:</p>\n<ul>\n  <li>Authentication and Authorization by using group, privilege, and user\n      management.\n       <p>Not like other CMS, there is no backend-frontend in No-CMS. You\n            have freedom to choose how different groups of users can access pages\n         and modules differently.</p>\n  </li>\n <li>Change Theme.\n     <p>You can change the theme easily.</p>\n   </li>\n <li>Install/Un-install Module\n     <p>You can install/un-install module easily.</p>\n  </li>\n</ul>'),
(5, 1, 'Server Requirement', '<p>To install No-CMS, you should have these in your server :</p>\n<ul>\n    <li>PHP 5.3.2 or newer</li>\n    <li>MySQL 5.0 or newer</li>\n</ul>\n<p>I recommend to use apache2 as web server. It seems that CodeIgniter is not doing well with nginx</p>'),
(6, 1, 'License', '<p>No-CMS has dual license</p>\n<ul>\n   <li>GPL</li>\n  <li>MIT License</li>\n</ul>\n<p>In short, you can do everything to No-CMS, make money from it, share\n  it with your friend, keep it in your disk, etc.</p>\n<p>No-CMS is built with no warranty, but with best wishes. There will be\n no one responsible for any damage made by No-CMS</p>\n<p>Please also consider, that this license is only applied to the CMS\n   itself. Third party Modules and Themes are property of their creator.</p>'),
(7, 1, 'Credits', '<p>I would like to thank all the contributors to the No-CMS project and\n    you, the No-CMS user. Here are some names of considerable contributors:\n</p>\n<ul>\n   <li>goFrendiAsgard : It''s me, I am the one who make No-CMS based on\n      CodeIgniter and some existing plug-ins.</li>\n  <li>EllisLab : A company who make codeIgniter and make it available for\n       free. There is no No-CMS without codeIgniter</li>\n <li>wiredesignz : He is the one who make HMVC plugin. The plug-in he\n      made is known widely among CodeIgniter developer. It allowed me to\n        make separation between modules</li>\n  <li>Phil Sturgeon : He is the one who make CodeIgniter-template. The\n      plugin he made allowed me to make separation between themes elements\n      He is a member of CodeIgniter Reactor Engineer. His pyro-CMS also\n     inspire me a lot (although I take different approach)</li>\n    <li>John Skoumbourdis : He is the one who make groceryCRUD. It boost\n      the development of No-CMS by provide very easy CRUD. He also give me\n      some moral support to continue the development of No-CMS.</li>\n    <li>Zusana Pudyastuti : She was my English Lecturer, A very good one\n      who encourage me to speak English. It is a miracle for me to write\n        this section in English :D</li>\n   <li>Mukhlies Amien : He is one of my best friends. In this project, his\n       role is advisor and tester.</li>\n  <li>Gembong Edhi Setiawan : He is also one of my best friends. He gives\n       some support and feature requests.</li>\n   <li>Wahyu Eka Putra : He was my student. One of some best students in\n     my class. He is the first one who discover a critical bug in the first\n        stage of development.</li>\n    <li>I Komang Ari Mogi : He is my classmate in my graduate program. He\n     has some experience in design. That''s why he can propose some fix in\n     the very early stage of development.</li>\n</ul>'),
(8, 2, 'Download No-CMS', '<p>\n    You can download No CMS from <a\n       href="https://github.com/goFrendiAsgard/No-CMS">No-CMS github\n     repository</a>\n</p>'),
(9, 2, 'Install No-CMS', '<p>Installing No-CMS is very easy. You should provide :</p>\n<ul>\n   <li>Database Information\n      <ul>\n          <li>Database Server\n               <p>It is about your database server name, it can be IP address or\n                 computer''s name If you install your database server is also your\n                 web server, you can provide either ''localhost'' or ''127.0.0.1'' as\n                  Database server</p>\n           </li>\n         <li>Port\n              <p>In the current version of No-CMS we only support MySQL. The\n                    default port for MySQL would be ''3306''</p>\n          </li>\n         <li>Username\n              <p>To use database, you must ensure that you are authorized to your\n                   database server. For authorization sake, you should provide\n                   username and password. The default value for the username is\n                  ''root''. If you use xampp, you can just keep this default value.</p>\n         </li>\n         <li>Password\n              <p>The password to access database server, the default is blank,\n                  means no password\n         \n          </li>\n         <li>Database/Schema\n               <p>The default database schema is ''no_cms''. The installer will try\n                  to make the datatabase schema if it is not exists yet</p>\n         </li>\n     </ul>\n </li>\n <li>Administrator Information\n     <ul>\n          <li>E mail\n                <p>Fill it with your email account, it can be used for your\n                   authentication</p>\n            </li>\n         <li>User name\n             <p>Fill it with your desired user name, it will be used for your\n                  authentication</p>\n            </li>\n         <li>Password\n              <p>Fill it with your new password</p>\n         </li>\n     </ul>\n </li>\n</ul>'),
(10, 3, 'User', ''),
(11, 3, 'Group', ''),
(12, 3, 'Navigation', ''),
(13, 3, 'Privilege', ''),
(14, 3, 'Module', ''),
(15, 3, 'Widget', ''),
(16, 3, 'Theme', ''),
(17, 3, 'Quick Link', ''),
(18, 3, 'Configuration', ''),
(19, 4, 'Module Directory Structure', '<p>\n    Making your own module is very easy if you are familiar with codeIgniter before. What you need to do is to make a directory inside modules directory of your No-CMS. So when you install No-CMS in <b>/var/www/No-CMS</b>, the modules directory will be <b>/var/www/No-CMS/modules</b> Every modules should contains at least 3 subdirectories. Those are</p>\n<ul>\n  <li>\n      <b>models</b><br />\n       This directory contains every models for a specific module</li>\n   <li>\n      <b>views</b><br />\n        This directory contains every views for a specific module</li>\n    <li>\n      <b>controllers</b><br />\n      This directory contains every controllers for a specific module</li>\n</ul>\n<p>\n  For example, if you want to make <b>&quot;damn_simple_module&quot;</b>, you should make a directory named &quot;damn_simple_module&quot; in the modules directory. The directory structure would be like this:<br />\n  <img src="modules/help/assets/images/modules_directory_structure.png" style="float: left; margin: 10px; padding: 10px; " /> Now, take a look at controllers directory. We should have at least 2 files there, i.e.:</p>\n<ul>\n <li>\n      <b>Main module controller file (in this case damn_simple_module.php)</b><br />\n        This file contains a class which extends CMS_Controller. It must also have the same name as your module directory<br />\n       <pre class="phpSnippet">\n    class Damn_simple_module extends CMS_Controller{\n        //Your logic goes here.....\n    }\n</pre>\n        For more detail about this file, please read Module&#39;s Controller section</li>\n <li>\n      <b>Module installer file (in this case install.php)</b><br />\n     A class which extends CMS_Module_Installer is needed in this file. Name the file as Install.php. Here you can specify what would be done when your module installed or un-installed. For more detail about this file, please read How to Make Your Module Install-able section</li>\n</ul>\n<p>\n   Okay, now you can make as many module as you want. But you need to read the next section to know how to make a useful module :D</p>\n'),
(20, 4, 'Module API', '<b>Model</b>\n<p>\n  To read this section you must be familiar with CodeIgniter MVC pattern. The models in No-CMS&#39;module are pretty similar with models in CodeIgniter. Whenever you make a model in CodeIgniter, you usually write this:</p>\n<pre class="phpSnippet">\n    class My_Model extends CI_Model{\n        //Your logic goes here.....\n    }\n</pre>\n<p>\n If you are familiar with CodeIgniter, you must be familiear to the code above.</p>\n<p>\n   To make a module&#39;s model is quiet easy. Just as easy as make regular CodeIgniter Model. Remember to always put your module&#39;s models in <b>/modules/your_module_name/models</b>. You can gain some advantages by extend your model from CMS_Model instead of CI_Model. Some additional features such as get user name, user id, etc are already embedded in CMS_Model. To extend your model from CMS_Model, you can simply write:</p>\n<pre class="phpSnippet">\n    class Damn_Simple_Module_Model extends CMS_Model{\n        //Your logic goes here.....\n    }\n</pre>\n'),
(21, 5, 'Theme Directory Structure', '<p>\n No-CMS uses Phil Sturgeon''s template as it''s template engine.\n   The advantage of using Phil''s template is you can start with the big picture\n before go to details\n</p>\n<img src="<?php echo base_url();?>modules/help/assets/images/No-CMS-layout.png"\n                   style="float: right; margin: 10px; padding: 10px; width: 70%" />\n<p>\n One of the main idea behind No-CMS themes is to reduce verbosity of writing the same\n  things over and over again. The header, widget, navigation link, and footer usually not changed that much.\n    Only authorization differ navigation link appeared. \n  By using such an approach you can focus on the content and left everything else to be done automatically by No-CMS\n</p>\n<p>\n In No-CMS, a page is divided into several segment. These segment is called "partials". \n   Bassically there are header, footer, left, right, navigation_path and content partial.\n    All of those partials except content are handled by No-CMS itself. \n</p>\n<p>\n    Making your costum themes is easy, but there are conventions that should be fullfilled.\n   What you need to do is to make a directory inside themes directory of your No-CMS. \n   So if you install No-CMS in <b>/var/www/No-CMS</b>,\n   the modules directory will be <b>/var/www/No-CMS/themes</b>. Every\n    themes should contains at least 3 subdirectories. Those are views, assets, and lib. Each are explained below :\n</p>\n<ul>\n    <li><b>views</b><br /> This is the most important directory. There should be 2 subdirectories here\n        <ul>\n          <li><b>layouts</b><br /> No-CMS will recognize your layouts based on everything you write here.\n               You can have different layout for different device (e.g: desktop and mobile). Your client might also like to have ''admin'' and ''regular'' theme.\n                In the most simple case, only default.php is required. But depended on requirement, you might also like to write some additional templates.\n               <ul>\n                  <li><b>default.php</b><br />This is the basic and should be exists layout.</li>\n                   <li><b>mobile.php</b><br />This is the optional mobile layout. No-CMS uses user_agent to gain information about visitor''s device.\n                        If the visitor uses mobile device (e.g: android smartphone), this layout will be activated.\n                   </li>\n                 <li><b>default_backend.php</b><br />This is the optional ''admin'' layout</li>\n                    <li><b>mobile_backend.php</b><br />This is the optional ''admin'' layout for mobile user</li>\n             </ul>           \n          </li>\n         <li><b>partials</b><br />               \n              You should make some directory as much as your layout here.\n               For example, if you have default.php and mobile.php in the layouts directory, then you should also have\n               default and mobile sub-directories here. Each of those directories should consists of 5 files:              \n              <ul>\n                  <li><b>header.php</b><br /></li>\n                  <li><b>footer.php</b><br /></li>\n                  <li><b>left.php</b><br /></li>\n                    <li><b>right.php</b><br /></li>\n                   <li><b>navigation_path.php</b><br /></li>\n             </ul>\n             Those files are consists of header, footer, left, right, and navigation_path partial respectively.              \n          </li>\n     </ul>\n </li>\n <li><b>assets</b><br /> This directory contains every static file that you want to use in your themes (e.g : javascript, css, images etc)</li>\n    <li><b>lib</b><br /> This directory contains of some additional "logics" to show the theme correctly</li>\n</ul>'),
(22, 5, 'Theme API', '<p>It is a good idea to check out Phil''s template documentation. \nBassically they are some variables you can use in your layout:\n</p>\n<ul>\n  <li><b>$template[''title'']</b><br />This is generated by Phil''s template</li>\n   <li><b>$template[''partials''][''header'']</b><br />Generated by header.php in the respective partial</li>\n    <li><b>$template[''partials''][''footer'']</b><br />Generated by footer.php in the respective partial</li>\n    <li><b>$template[''partials''][''right'']</b><br />Generated by right.php in the respective partial</li>\n  <li><b>$template[''partials''][''left'']</b><br />Generated by left.php in the respective partial</li>\n    <li><b>$template[''partials''][''navigation_path'']</b><br />Generated by navigation_path.php in the respective partial</li>\n  <li><b>$template[''body'']</b><br />Your content</li>\n <li><b>$cms[''site_name'']</b><br />Site name from the configuration</li>\n    <li><b>$cms[''site_slogan'']</b><br />Site slogan from the configuration</li>\n    <li><b>$cms[''site_footer'']</b><br />Site footer from the configuration</li>\n    <li><b>$cms[''site_theme'']</b><br />Site theme from the configuration</li>\n    <li><b>$cms[''site_logo'']</b><br />Site logo from the configuration</li>\n    <li><b>$cms[''site_favicon'']</b><br />Site favicon from the configuration</li>\n    <li><b>$cms[''navigations'']</b><br />Navigations in array format, need some logic to show it in an appropriate way</li>\n    <li><b>$cms[''navigation_path'']</b><br />Navigation path, need some logic to show it in an appropriate way</li>\n    <li><b>$cms[''widget'']</b><br />Widget, need some logic to show it in an appropriate way</li>\n    <li><b>$cms[''user_name'']</b><br />Current user name</li>\n    <li><b>$cms[''quicklinks'']</b><br />Quick Links, need some logic to show it in an appropriate way</li>\n    <li><b>$cms[''module_name'']</b><br />Current module name</li>           \n</ul>\n<p>The $cms variables can also be used in the partials as well\n</p>\n<p>This is an example default.php content:</p>\n<pre class="htmlSnippet">\n    &lt;html&gt;\n        &lt;head&gt;\n          &lt;title&gt;&lt;?php echo $template[''title''];?&gt;&lt;/title&gt;\n           &lt;link rel="icon" href="&lt;?php echo $cms[''site_favicon''];?&gt;"&gt;\n         &lt;script type="text/javascript" src ="&lt;?php echo base_url().''assets/nocms/js/jquery.js'';?&gt;"&gt;&lt;/script&gt;\n          &lt;link rel="stylesheet" type="text/css" href="&lt;?php echo base_url()."themes/".$cms[''site_theme'']."/assets/default/style.css";?&gt;"&gt;&lt;/link&gt;\n           &lt;script type="text/javascript" src="&lt;?php echo base_url()."themes/".$cms[''site_theme'']."/assets/default/script.js";?&gt;"&gt;&lt;/script&gt;\n      &lt;/head&gt;\n     &lt;body&gt;       \n           \n          &lt;div id="layout_header"&gt;&lt;?php echo $template[''partials''][''header''];?&gt;&lt;/div&gt;\n         \n          &lt;div id="layout_center"&gt;\n                &lt;div id="layout_right"&gt;&lt;?php echo $template[''partials''][''right''] ?&gt;&lt;/div&gt;\n               &lt;div id="layout_content"&gt;\n                   &lt;div id="layout_nav_path"&gt;You are here : &lt;?php echo $template[''partials''][''navigation_path'']?&gt;&lt;/div&gt;\n                    &lt;br /&gt;\n                  &lt;?php echo $template[''body''];?&gt;\n               &lt;/div&gt;\n              &lt;div class="layout_clear"&gt;&lt;/div&gt;\n          &lt;/div&gt;\n          \n          &lt;div id="layout_footer"&gt;&lt;?php echo $template[''partials''][''footer''];?&gt;&lt;/div&gt; \n        &lt;/body&gt;\n &lt;/html&gt;\n</pre>\n<p>While this is an example header.php partials:</p>\n<pre class="htmlSnippet">\n    &lt;?php require_once BASEPATH."../themes/".$cms[''site_theme'']."/lib/function.php";?&gt;\n    &lt;img class="layout_float_left" src ="&lt;?php echo $cms[''site_logo''];?&gt;" /&gt;\n    &lt;div class="layout_float_left layout_large_left_padding"&gt;\n       &lt;h1&gt;&lt;?php echo $cms[''site_name''];?&gt;&lt;/h1&gt;\n        &lt;h2&gt;&lt;?php echo $cms[''site_slogan''];?&gt;&lt;/h2&gt;\n        &lt;?php echo build_quicklink($cms[''quicklinks'']);?&gt;\n    &lt;/div&gt;\n    &lt;div class="layout_clear"&gt;&lt;/div&gt;\n</pre>\n<p>I hope you can get the idea :)</p>');

