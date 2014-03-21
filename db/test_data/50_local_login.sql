/*
-- Query: SELECT * FROM test_user.local_login
LIMIT 0, 1000

-- Date: 2014-03-21 20:10
*/
INSERT INTO `local_login` (`user_id`,`login`,`salt`,`hash`,`email`) VALUES (2,'test','$6$rounds=5000$MjkyNTg3MzAxNTMyYzdiMTlkMDc0Zj','$6$rounds=5000$MjkyNTg3MzAxNTMy$cU8jZ3xUKvHCiiHyhqxEg/De/EhXvb51ifvTc4Fi.0FZJdxoJbAUjquhqfM5xaSBny110fl31OTs2cNrxZCp2/','test1@example.com');
INSERT INTO `local_login` (`user_id`,`login`,`salt`,`hash`,`email`) VALUES (3,'test_two','$6$rounds=5000$MTcxNjQzMzg0OTUzMmM3YjU0N2ZkYz','$6$rounds=5000$MTcxNjQzMzg0OTUz$0y6vlPWzVRYJZ7RdpVpM28A5FXLKcmf2ZBbsvhVJONbbuZ7bdcwHpXp4wnd0BB2E30iSS6ORygd.3PXOmT2Jh0','test2@example.com');
