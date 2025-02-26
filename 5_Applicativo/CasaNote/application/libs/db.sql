CREATE DATABASE CasaNote;
USE CasaNote;
CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       username VARCHAR(255) NOT NULL,
                       email VARCHAR(255) NOT NULL,
                       password VARCHAR(255) NOT NULL
);

CREATE TABLE note (
                      id INT AUTO_INCREMENT PRIMARY KEY,
                      title VARCHAR(255) NOT NULL,
                      date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                      date_last_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
INSERT INTO note (title, date_creation, date_last_update) VALUES
                                                           ('Prima nota', '2025-02-24 10:30:00', '2025-02-24 10:30:00'),
                                                           ('Seconda nota', '2025-02-25 11:00:00', '2025-02-25 11:00:00'),
                                                           ('Terza nota', '2025-02-26 12:15:00', '2025-02-26 12:15:00');
