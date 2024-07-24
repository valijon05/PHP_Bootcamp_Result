CREATE DATABASE todo_list;

USE todo_list;

CREATE TABLE todos (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       title VARCHAR(255) NOT NULL,
                       completed BOOLEAN NOT NULL DEFAULT 0
);



CREATE TABLE users (
                                       id INT AUTO_INCREMENT PRIMARY KEY,
                                       `add` VARCHAR(255),
                                       `check` VARCHAR(255),
                                       `uncheck` VARCHAR(255),
                                       `delete` VARCHAR(255)
);