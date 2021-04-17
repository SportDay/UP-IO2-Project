create database if not exists reseau;
 
use reseau;
 
drop table if exists users;
 
CREATE TABLE `users` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`firstname` varchar(64) DEFAULT NULL,
`lastname` varchar(64) DEFAULT NULL,
`username` varchar(64) DEFAULT NULL,
`password` varchar(64) DEFAULT NULL,
`publicname` varchar(64) DEFAULT NULL,
`creationdate` varchar(64) DEFAULT NULL,
`lastjoin` varchar(64) DEFAULT NULL,
`description` varchar(64) DEFAULT NULL,
`enablepublic` varchar(64) DEFAULT NULL,
`banned` varchar(64) DEFAULT FALSE,
`bannedto` varchar(64) DEFAULT NULL,
`follows` varchar(64) DEFAULT NULL,
`secretwords` varchar(64) DEFAULT NULL,
`admin` varchar(64) DEFAULT FALSE,
PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8;


drop table if exists posts;
 
CREATE TABLE `posts` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`author` varchar(64) DEFAULT NULL,
`username` varchar(64) DEFAULT NULL,
`userid` varchar(64) DEFAULT NULL,
`reported` varchar(64) DEFAULT FALSE,
`reportnum` varchar(64) DEFAULT 0,
`reporteddate` varchar(64) DEFAULT NULL,
`creationdate` varchar(64) DEFAULT NULL,
`title` varchar(64) DEFAULT NULL,
`content` varchar(64) DEFAULT NULL,
`likenum` varchar(64) DEFAULT 0,
PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8;