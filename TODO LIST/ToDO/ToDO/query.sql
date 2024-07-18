CREATE DATABASE todo_list;

USE todo_list;

CREATE TABLE todos (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       title VARCHAR(255) NOT NULL,
                       completed BOOLEAN NOT NULL DEFAULT 0
);
