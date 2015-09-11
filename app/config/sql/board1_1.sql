--
-- Create database
--
DROP DATABASE board1_1;
CREATE DATABASE board1_1;
GRANT SELECT, INSERT, UPDATE, DELETE ON board1_1.* TO board_root@localhost
IDENTIFIED BY 'board_root';
FLUSH PRIVILEGES;

USE board1_1;

--
-- Create tables
--

CREATE TABLE category (
    id          TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name        VARCHAR(20),
    PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE thread (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title       VARCHAR(30),
    category_id TINYINT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (category_id) REFERENCES category(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE user (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    username    VARCHAR(16) NOT NULL UNIQUE,
    first_name  VARCHAR(30) NOT NULL,
    last_name   VARCHAR(30) NOT NULL,
    email       VARCHAR(30) NOT NULL UNIQUE,
    password    VARCHAR(60),
    PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE comment (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    thread_id   INT UNSIGNED NOT NULL,
    user_id     INT UNSIGNED NOT NULL,
    body        TEXT NOT NULL,
    created_at  TIMESTAMP NOT NULL DEFAULT 0,
    edited_at   TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (thread_id) REFERENCES thread(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (user_id)   REFERENCES user(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE follow (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    thread_id   INT UNSIGNED NOT NULL,
    user_id     INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (thread_id) REFERENCES thread(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (user_id)   REFERENCES user(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    UNIQUE (thread_id, user_id)
) ENGINE=InnoDB;
