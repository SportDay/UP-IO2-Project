create database if not exists reseau;
use reseau;

-- ##########################################################
-- CLEAN (attention à bien supprimer les parents après les enfants !)

drop table if exists direct_messages;   -- REF TO USER
drop table if exists friends;           -- REF TO USER
drop table if exists reports;           -- REF TO USER/POST
drop table if exists likes;             -- REF TO USERS/POST
drop table if exists posts;             -- REF TO USERS

drop table if exists pages_liked;       -- REF TO USERS
drop table if exists follows;           -- REF TO USERS

drop table if exists users;

-- ##########################################################
-- USER DATA

CREATE TABLE `users` (
    `id`            bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `cookie_id`     bigint UNSIGNED DEFAULT NULL,

    `username`      varchar(64)     DEFAULT NULL,
    `password`      varchar(64)     DEFAULT NULL,
    `creation_date` INT UNSIGNED    DEFAULT unix_timestamp(CURRENT_TIMESTAMP),
    `last_join`     INT UNSIGNED    DEFAULT unix_timestamp(CURRENT_TIMESTAMP),
    
    `cookie_enabled`BOOLEAN         DEFAULT FALSE,     
    `cookie_pass`   varchar(64)     DEFAULT NULL,
    `cookie_expire` INT UNSIGNED    DEFAULT unix_timestamp(CURRENT_TIMESTAMP),

    `enable_public` BOOLEAN         DEFAULT FALSE,
    `public_name`   varchar(64)     DEFAULT NULL,
    `specie`        varchar(64)     DEFAULT NULL,
    `class`         varchar(64)     DEFAULT NULL,
    `title`         varchar(64)     DEFAULT NULL,
    `likes`         int UNSIGNED    DEFAULT 0,
    `description`   varchar(64)     DEFAULT NULL,
    
    `banned`        varchar(64)     DEFAULT FALSE,
    `bannedto`      INT UNSIGNED    DEFAULT unix_timestamp(CURRENT_TIMESTAMP),
    
    `admin`         varchar(64)     DEFAULT FALSE

) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE follows (
    `user_id`       bigint UNSIGNED NOT NULL,
    `follow_id`     bigint UNSIGNED NOT NULL,

    PRIMARY KEY (`user_id`),
    FOREIGN KEY (`follow_id`) REFERENCES users(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE pages_liked (
    `user_id`      bigint UNSIGNED NOT NULL,
    `like_id`      bigint UNSIGNED NOT NULL,

    PRIMARY KEY (`user_id`),
    FOREIGN KEY (`like_id`) REFERENCES users(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

-- ##########################################################
-- POSTS DATA

CREATE TABLE `posts` (
    `id`            bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`       bigint UNSIGNED NOT NULL,
    `reported`      varchar(64)     DEFAULT FALSE,
    `reportnum`     varchar(64)     DEFAULT 0,
    `last_report`   INT UNSIGNED    DEFAULT unix_timestamp(CURRENT_TIMESTAMP),
    `creation_date` INT UNSIGNED    DEFAULT unix_timestamp(CURRENT_TIMESTAMP),
    `content`       varchar(735)    DEFAULT NULL,
    `like_num`      varchar(64)     DEFAULT 0,
    `response_id`   bigint UNSIGNED DEFAULT NULL,

    PRIMARY KEY (id),
    FOREIGN KEY (user_id)      REFERENCES users(id),
    FOREIGN KEY (response_id)  REFERENCES posts(id)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE likes (
    `message_id`     bigint UNSIGNED NOT NULL,
    `user_id`        bigint UNSIGNED NOT NULL,

    PRIMARY KEY (`message_id`),
    FOREIGN KEY (`user_id`) REFERENCES users(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE reports (
    `message_id`     bigint UNSIGNED NOT NULL,
    `user_id`        bigint UNSIGNED NOT NULL,

    PRIMARY KEY (`message_id`),
    FOREIGN KEY (`user_id`) REFERENCES users(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

-- ##########################################################
-- FRIENDS

CREATE TABLE friends (
    `user_id`        bigint UNSIGNED NOT NULL,
    `friend_id`      bigint UNSIGNED NOT NULL,

    PRIMARY KEY (`user_id`),
    FOREIGN KEY (`user_id`)   REFERENCES users(`id`),
    FOREIGN KEY (`friend_id`) REFERENCES users(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

-- ##########################################################
-- DIRECT MESSAGES

CREATE TABLE direct_messages (
    `id`            bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `from_id`       bigint UNSIGNED NOT NULL,
    `to_id`         bigint UNSIGNED NOT NULL,
    `creation_date` INT UNSIGNED    DEFAULT unix_timestamp(CURRENT_TIMESTAMP),
    `content`       varchar(1024)   DEFAULT NULL,

    PRIMARY KEY(id),
    FOREIGN KEY(from_id) REFERENCES users(id),
    FOREIGN KEY(to_id)   REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;