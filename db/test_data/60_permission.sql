/*
-- Query: SELECT * FROM test_user.permission
LIMIT 0, 1000

-- Date: 2014-03-31 14:13
*/
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('add_controller','User can add controller to the DB.',1);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('delete_controller','User can delete existing controller from the DB.',1);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('edit_controller','User can edit existing controller.',1);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('list_controllers','User can access controller list.',1);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('test_permission',NULL,NULL);
