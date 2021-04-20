create database if not exists reseau;
 
use reseau;
 
drop table if exists users;
 
CREATE TABLE `users` (
`id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
`username` varchar(64) DEFAULT NULL,
`password` varchar(64) DEFAULT NULL,
`publicname` varchar(64) DEFAULT NULL,
`creationdate` varchar(64) DEFAULT NULL,
`lastjoin` varchar(64) DEFAULT NULL,
`description` varchar(64) DEFAULT NULL,
`enablepublic` varchar(64) DEFAULT NULL,
`banned` varchar(64) DEFAULT FALSE,
`bannedto` varchar(64) DEFAULT NULL,
`admin` varchar(64) DEFAULT FALSE,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;


drop table if exists posts;
 
CREATE TABLE `posts` (
`id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
`userid` varchar(64) DEFAULT NULL,
`reported` varchar(64) DEFAULT FALSE,
`reportnum` varchar(64) DEFAULT 0,
`lastreport` varchar(64) DEFAULT NULL,
`creationdate` varchar(64) DEFAULT NULL,
`content` varchar(735) DEFAULT NULL,
`likenum` varchar(64) DEFAULT 0,
`reponseId` varchar(64) DEFAULT -1,
PRIMARY KEY (`id`),
FOREIGN KEY (userid) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;