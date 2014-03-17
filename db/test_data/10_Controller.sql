/*
-- Query: SELECT * FROM test_user.Controller
LIMIT 0, 1000

-- Date: 2014-03-12 09:48
*/
INSERT INTO `Controller` (`id`,`name`,`description`,`enabled`,`uri_name`,`file_path`) VALUES (1,'MainPageController','Main page controller',1,'main','controllers/MainPageController.php');
INSERT INTO `Controller` (`id`,`name`,`description`,`enabled`,`uri_name`,`file_path`) VALUES (2,'TestEnabledController','Test controller (enabled).',1,'test_en','controllers/TestEnabledController.php');
INSERT INTO `Controller` (`id`,`name`,`description`,`enabled`,`uri_name`,`file_path`) VALUES (3,'TestDisabledController','Test controller (disabled).',0,'test_dis','controllers/TestDisabledController.php');
INSERT INTO `Controller` (`id`,`name`,`description`,`enabled`,`uri_name`,`file_path`) VALUES (7,'LoginPageController','Login page',1,'login','controllers/LoginPageController.php');
INSERT INTO `Controller` (`id`,`name`,`description`,`enabled`,`uri_name`,`file_path`) VALUES (4,'RegistrationPageController','Registration page',1,'reg','controllers/RegistrationPageController.php');