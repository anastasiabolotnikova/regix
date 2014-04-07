/*
-- Query: SELECT * FROM test_user.permission
LIMIT 0, 1000

-- Date: 2014-04-01 19:11
*/
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('add_controller','User can add controller to the DB.',1);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('add_group','User can add a new user group.',2);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('add_group_permission','User can grant permissions to groups.',2);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('add_group_user','User can add users to existing groups.',2);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('add_permission','User can add new permissions to the system.',4);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('add_permission_category','User can add permission categories.',4);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('add_user','User can add new users to the system (note that registration components use different permissions).',3);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('delete_controller','User can delete existing controller from the DB.',1);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('delete_group','User can delete an existing group.',2);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('delete_group_permission','User can revoke permissions from groups.',2);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('delete_group_user','User can delete users from existing groups.',2);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('delete_permission','User can delete any permission in the system.',4);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('delete_permission_category','User can delete any permission category.',4);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('delete_user','User can delete any system user.',3);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('edit_controller','User can edit existing controller.',1);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('edit_group','User can edit an existing group.',2);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('edit_permission','User can edit all permissions.',4);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('edit_permission_category','User can edit all permission categories.',4);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('edit_user','User can edit any system user.',3);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('list_controllers','User can access controller list.',1);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('list_groups','User can see a list of all groups.',2);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('list_permissions','User can see a list of all permissions in the system.',4);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('list_permission_categories','User can see a list of all permission categories in the system.',4);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('list_users','User can list all system users and see their personal data.',3);
INSERT INTO `permission` (`name`,`description`,`permission_category_id`) VALUES ('event_users','User can see list of events and change it (update/delete)',0);
