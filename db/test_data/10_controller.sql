/*
-- Query: SELECT * FROM test_user.controller
LIMIT 0, 1000

-- Date: 2014-04-14 22:38
*/
INSERT INTO `controller` (`id`,`name`,`description`,`enabled`,`uri_name`,`file_path`) VALUES (1,'MainPageController','Main page controller',1,'main','controllers/MainPageController.php');
INSERT INTO `controller` (`id`,`name`,`description`,`enabled`,`uri_name`,`file_path`) VALUES (2,'ControllerManagerController','Management of Regix controllers',1,'controllers','controllers/ControllerManagerController.php');
INSERT INTO `controller` (`id`,`name`,`description`,`enabled`,`uri_name`,`file_path`) VALUES (4,'RegistrationPageController','Registration page',1,'reg','controllers/RegistrationPageController.php');
INSERT INTO `controller` (`id`,`name`,`description`,`enabled`,`uri_name`,`file_path`) VALUES (5,'ProfilePageController','Profile page',1,'prof','controllers/ProfilePageController.php');
INSERT INTO `controller` (`id`,`name`,`description`,`enabled`,`uri_name`,`file_path`) VALUES (7,'LoginPageController','Local login page',1,'login','controllers/LoginPageController.php');
INSERT INTO `controller` (`id`,`name`,`description`,`enabled`,`uri_name`,`file_path`) VALUES (8,'UserManagerController','Management of user accounts and groups.',1,'uac','controllers/UserManagerController.php');
INSERT INTO `controller` (`id`,`name`,`description`,`enabled`,`uri_name`,`file_path`) VALUES (9,'CalendarController','Calendar overview.',1,'calendar','controllers/CalendarController.php');
INSERT INTO `controller` (`id`,`name`,`description`,`enabled`,`uri_name`,`file_path`) VALUES (10,'GroupManagerController','Management of groups and permissions.',1,'groups','controllers/GroupManagerController.php');
INSERT INTO `controller` (`id`,`name`,`description`,`enabled`,`uri_name`,`file_path`) VALUES (11,'PermissionManagerController','Add and remove permissions used by the Regix. Note that permissions are granted via group manager.',1,'permissions','controllers/PermissionManagerController.php');
INSERT INTO `controller` (`id`,`name`,`description`,`enabled`,`uri_name`,`file_path`) VALUES (12,'LatestController','Access to updates.',1,'latest','controllers/LatestController.php');
INSERT INTO `controller` (`id`,`name`,`description`,`enabled`,`uri_name`,`file_path`) VALUES (13,'EventManagerController','Event management.',1,'emc','controllers/EventManagerController.php');
INSERT INTO `controller` (`id`,`name`,`description`,`enabled`,`uri_name`,`file_path`) VALUES (14,'ContactPageController','Contact page controller.',1,'contact','controllers/ContactPageController.php');
INSERT INTO `controller` (`id`,`name`,`description`,`enabled`,`uri_name`,`file_path`) VALUES (15,'MyPlanController','Plan for the day (employee view).',1,'myplan','controllers/MyPlanController.php');
