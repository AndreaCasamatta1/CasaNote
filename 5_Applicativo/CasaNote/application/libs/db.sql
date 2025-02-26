CREATE DATABASE CasaNote;
USE CasaNote;
CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       username VARCHAR(255) NOT NULL,
                       password VARCHAR(255) NOT NULL,
                       notes TEXT
);
INSERT INTO users (username, password, notes)
VALUES
    ('user1', 'password1', 'Note for user1'),
    ('user2', 'password2', 'Note for user2'),
    ('user3', 'password3', 'Note for user3');
