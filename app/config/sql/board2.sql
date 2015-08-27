GRANT SELECT, INSERT, UPDATE, DELETE, DROP ON board.* TO board_root@localhost
IDENTIFIED BY 'board_root';
FLUSH PRIVILEGES;

--
-- Create database
--
CREATE DATABASE IF NOT EXISTS board;

USE board;

--
-- Restart from scratch.
--

DROP TABLE IF EXISTS comment;
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS thread;

--
-- Create tables
--

CREATE TABLE IF NOT EXISTS thread (
    id        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title     VARCHAR(255),
    created   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS user (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    username    VARCHAR(16) NOT NULL,
    email       VARCHAR(30) NOT NULL,
    password    VARCHAR(60),
    PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS comment (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    thread_id   INT UNSIGNED NOT NULL,
    user_id     INT UNSIGNED NOT NULL,
    body        TEXT NOT NULL,
    created     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (thread_id) REFERENCES thread(id),
    FOREIGN KEY (user_id)   REFERENCES user(id),
    INDEX (created)
) ENGINE=InnoDB;
