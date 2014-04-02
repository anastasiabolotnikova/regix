/*
-- Query: SELECT * FROM test_user.event
LIMIT 0, 1000

-- Date: 2014-03-21 20:10
*/
INSERT INTO `event`(`calendar_id`, `user_id`, `description`, `assigned_user`, `assigned_service`, `from`, `to`) VALUES (1,2,'I have a problem',4,'tserv1', TIMESTAMP '2014-3-12 8:00:00',TIMESTAMP '2014-3-12 9:00:00');
INSERT INTO `event`(`calendar_id`, `user_id`, `description`, `assigned_user`, `assigned_service`, `from`, `to`) VALUES (1,3,'I have a problem',5,'tserv2', TIMESTAMP '2014-3-12 10:00:00',TIMESTAMP '2014-3-12 11:00:00');
INSERT INTO `event`(`calendar_id`, `user_id`, `description`, `assigned_user`, `assigned_service`, `from`, `to`) VALUES (1,2,'I have a problem',6,'tserv3', TIMESTAMP '2014-3-12 11:00:00',TIMESTAMP '2014-3-12 12:00:00');
INSERT INTO `event`(`calendar_id`, `user_id`, `description`, `assigned_user`, `assigned_service`, `from`, `to`) VALUES (1,3,'I have a problem',4,'tserv1',TIMESTAMP '2014-3-12 15:00:00',TIMESTAMP '2014-3-12 16:00:00');