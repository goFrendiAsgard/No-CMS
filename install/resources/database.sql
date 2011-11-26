DROP TRIGGER IF EXISTS `trg_before_insert_cms_navigation`;
/*split*/
DROP TRIGGER IF EXISTS `trg_before_update_cms_navigation`;
/*split*/
DROP TABLE IF EXISTS `cms_module`;
/*split*/
DROP TABLE IF EXISTS `cms_group_privilege`;
/*split*/
DROP TABLE IF EXISTS `cms_group_navigation`;
/*split*/
DROP TABLE IF EXISTS `cms_group_user`;
/*split*/
DROP TABLE IF EXISTS `cms_group`;
/*split*/
DROP TABLE IF EXISTS `cms_navigation`;
/*split*/
DROP TABLE IF EXISTS `cms_privilege`;
/*split*/
DROP TABLE IF EXISTS `cms_user`;
/*split*/
DROP TABLE IF EXISTS `cms_authorization`;
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

CREATE TABLE `cms_navigation` (
  `navigation_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `navigation_name` varchar(45) NOT NULL,
  `parent_id` int(20) unsigned DEFAULT NULL,
  `title` varchar(45) NOT NULL,
  `description` text,
  `url` varchar(45) DEFAULT NULL,
  `authorization_id` tinyint(4) unsigned NOT NULL DEFAULT '1',
  `is_root` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`navigation_id`),
  UNIQUE KEY `navigation_name` (`navigation_name`),
  KEY `parent_id` (`parent_id`),
  KEY `authorization_id` (`authorization_id`),
  CONSTRAINT `cms_navigation_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `cms_navigation` (`navigation_id`),
  CONSTRAINT `cms_navigation_ibfk_2` FOREIGN KEY (`authorization_id`) REFERENCES `cms_authorization` (`authorization_id`)
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
  `user_id` int(20) unsigned NOT NULL,
  PRIMARY KEY (`module_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `cms_module_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `cms_user` (`user_id`),
  UNIQUE KEY `module_name` (`module_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*split*/



DELIMITER ;;
/*split*/
CREATE TRIGGER `trg_before_insert_cms_navigation` BEFORE INSERT ON `cms_navigation` FOR EACH ROW IF NEW.is_root = 1 THEN
 SET NEW.parent_id = NULL; 
END IF
;;
/*split*/
DELIMITER ;
/*split*/

DELIMITER ;;
/*split*/
CREATE TRIGGER `trg_before_update_cms_navigation` BEFORE UPDATE ON `cms_navigation` FOR EACH ROW IF NEW.is_root = 1 THEN
 SET NEW.parent_id = NULL; 
END IF
;;
/*split*/
DELIMITER ;
/*split*/

INSERT INTO `cms_authorization` (`authorization_id`, `authorization_name`, `description`) VALUES
(1, 'Everyone', 'All visitor of the web are permitted (e.g:view blog content)'),
(2, 'Unauthenticated', 'Only non-member visitor, they who hasn''t log in yet (e.g:view member registration page)'),
(3, 'Authenticated (Regardless of Authorization)', 'Only member (e.g:change password)'),
(4, 'Authenticated And Authorized', 'Only member with certain privilege (depend on group)');
/*split*/

INSERT INTO `cms_group` (`group_id`, `group_name`, `description`) VALUES
(1, 'Admin', 'Every member of this group can do everything possible, but only programmer can turn the impossible into real :D'),
(2, 'Employee', 'Just an example, employee role');
/*split*/

INSERT INTO `cms_user` (`user_id`, `user_name`, `email`, `password`, `real_name`, `active`) VALUES
(1, '@adm_username', '@adm_email', '@adm_password', '@adm_realname', 1);
/*split*/


INSERT INTO `cms_navigation` (`navigation_id`, `navigation_name`, `parent_id`, `title`, `description`, `url`, `authorization_id`, `is_root`) VALUES
(1, 'main_login', NULL, 'Login', '<p>Visitor need to login for authentication</p>', 'main/login', 2, 1),
(2, 'main_forgot', NULL, 'Forgot password', '<p>Accidentally forgot password</p>', 'main/forgot', 2, 1),
(3, 'main_logout', NULL, 'logout', '<p>Logout for deauthentication</p>', 'main/logout', 3, 1),
(4, 'main_management', NULL, 'CMS Management', '<p>The main management of the CMS. Including User, Group, Privilege and Navigation Management</p>', 'main/management', 4, 1),
(5, 'main_register', NULL, 'Register', '<p>New User Registration</p>', 'main/register', 2, 1),
(6, 'main_change_profile', NULL, 'Change Profile', '<p>Change Current Profile</p>', 'main/change_profile', 3, 1),
(7, 'main_group_management', 4, 'Group Management', '<p>Group Management</p>', 'main/group', 4, 0),
(8, 'main_navigation_management', 4, 'Navigation Management', '<p>Navigation management</p>', 'main/navigation', 4, 0),
(9, 'main_privilege_management', 4, 'Privilege Management', '<p>Privilege Management</p>', 'main/privilege', 4, 0),
(10, 'main_user_management', 4, 'User Management', '<p>Manage User</p>', 'main/user', 4, 0),
(11, 'main_module_management', 4, 'Module Management', '<p>Install Or Uninstall Thirdparty Module</p>', 'main/module_list', 4, 0),
(12, 'main_index', NULL, 'Home', '<p>There is no place like home :D</p>', 'main/index', 1, 1);
/*split*/

INSERT INTO `cms_privilege` (`privilege_id`, `privilege_name`, `title`, `description`, `authorization_id`) VALUES
(1, 'cms_install_module', 'Install Module', '<p>Install Module is a very critical privilege, it allow authorized user to Install a module to the CMS.<br />By Installing module, the database structure can be changed. There might be some additional navigation and privileges added.<br /><br />You''d be better to give this authorization only authenticated and authorized user. (I suggest to make only admin have such a privilege)</p>\n<p>&nbsp;</p>', 4),
(2, 'cms_manage_access', 'Manage Access', '<p>Manage access</p>\n<p>&nbsp;</p>', 4);
/*split*/

INSERT INTO `cms_group_user` (`group_id`, `user_id`) VALUES
(1, 1);
/*split*/
