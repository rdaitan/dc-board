--
-- Create database
--
DROP DATABASE IF EXISTS board1_1;
CREATE DATABASE board1_1;

--
-- Assume that the user have been created.
--
GRANT SELECT, INSERT, UPDATE, DELETE ON board1_1.* TO board_root@localhost;
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

CREATE TABLE user (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    username    VARCHAR(16) NOT NULL UNIQUE,
    first_name  VARCHAR(30) NOT NULL,
    last_name   VARCHAR(30) NOT NULL,
    email       VARCHAR(30) NOT NULL UNIQUE,
    password    VARCHAR(60) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE thread (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title       VARCHAR(30),
    category_id TINYINT UNSIGNED NOT NULL,
    user_id     INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (category_id) REFERENCES category(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES user(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE comment (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    thread_id   INT UNSIGNED NOT NULL,
    user_id     INT UNSIGNED NOT NULL,
    body        TEXT NOT NULL,
    created_at  TIMESTAMP NOT NULL DEFAULT 0,
    modified_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (thread_id) REFERENCES thread(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (user_id)   REFERENCES user(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE follow (
    id              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    thread_id       INT UNSIGNED NOT NULL,
    user_id         INT UNSIGNED NOT NULL,
    last_comment    INT UNSIGNED DEFAULT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (thread_id) REFERENCES thread(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (user_id)   REFERENCES user(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    UNIQUE (thread_id, user_id)
) ENGINE=InnoDB;


--
-- categories
--

START TRANSACTION;
INSERT INTO category (name) VALUES ('Anime & Manga');
INSERT INTO category (name) VALUES ('Computers');
INSERT INTO category (name) VALUES ('Movies & TV');
INSERT INTO category (name) VALUES ('Random');
INSERT INTO category (name) VALUES ('Video Games');

--
-- users
--
INSERT INTO user (username, first_name, last_name, email, password) VALUES ('renchon', 'Renge', 'Miyauchi', 'renchon@nnb.jp', '$2a$11$486dec38427dc6f51d34dOaC5qS29DLysGydcIRSWb64kwDBq58ZW');

--
-- Thread
--
INSERT INTO thread (title, category_id, user_id) VALUES ('GUTEN MORGEN', 4, 1);

--
-- Comments
--
INSERT INTO comment (thread_id, user_id, body, created_at) VALUES (1, 1, 'Guten morgen', NULL);
COMMIT;
