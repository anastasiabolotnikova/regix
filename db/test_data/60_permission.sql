/*
-- Query: SELECT * FROM test_user.permission
LIMIT 0, 1000

-- Date: 2014-04-01 16:08
*/
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('add_controller','User can add controller to the DB.',1);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('add_group','User can add a new user group.',2);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('add_group_permission','User can grant permissions to groups.',2);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('add_group_user','User can add users to existing groups.',2);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('delete_controller','User can delete existing controller from the DB.',1);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('delete_group','User can delete an existing group.',2);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('delete_group_permission','User can revoke permissions from groups.',2);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('delete_group_user','User can delete users from existing groups.',2);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('edit_controller','User can edit existing controller.',1);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('edit_group','User can edit an existing group.',2);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('list_controllers','User can access controller list.',1);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('list_groups','User can see a list of all groups.',2);
