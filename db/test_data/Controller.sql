/*
-- Query: SELECT * FROM test_user.Controller
LIMIT 0, 1000

-- Date: 2014-03-11 13:46
*/
INSERT INTO `Controller` (`id`,`name`,`description`,`enabled`,`uri_name`,`file_path`) VALUES (1,'MainPageController','Main page controller',1,'main','MainPageController.php');
INSERT INTO `Controller` (`id`,`name`,`description`,`enabled`,`uri_name`,`file_path`) VALUES (2,'TestEnabledController','Test controller (enabled).',1,'test_en','TestEnabledController.php');
INSERT INTO `Controller` (`id`,`name`,`description`,`enabled`,`uri_name`,`file_path`) VALUES (3,'TestDisabledController','Test controller (disabled).',0,'test_dis','TestDisabledController.php');
