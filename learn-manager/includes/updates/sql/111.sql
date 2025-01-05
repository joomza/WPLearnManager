REPLACE INTO `#__js_learnmanager_config` (`configname`, `configvalue`, `configfor`) VALUES ('versioncode','1.1.1','default');
REPLACE INTO `#__js_learnmanager_config` (`configname`, `configvalue`, `configfor`) VALUES ('productversion', '111', 'default');

CREATE TABLE IF NOT EXISTS `#__js_learnmanager_session` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `usersessionid` char(64) NOT NULL,
  `sessionmsg` text CHARACTER SET utf8 NOT NULL,
  `sessionexpire` bigint(32) NOT NULL,
  `sessionfor` varchar(125) NOT NULL,
  `msgkey`varchar(125) NOT NULL  
) ENGINE=InnoDB DEFAULT CHARSET=latin1
