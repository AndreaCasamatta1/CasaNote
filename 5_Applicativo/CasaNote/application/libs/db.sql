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
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_last_update DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE attachment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_file VARCHAR(50) NOT NULL,
    percorso_file VARCHAR(255) NOT NULL,
    mime_type VARCHAR(50) NOT NULL,
    note_id INT,
    FOREIGN KEY (note_id) REFERENCES note(id) ON DELETE CASCADE
);

INSERT INTO users (username, email, password) VALUES
('MarioRossi', 'mario@example.com', 'hashedpassword');

INSERT INTO note (title, date_creation, date_last_update, user_id) VALUES
('Prima nota', '2025-02-24 10:30:00', '2025-02-24 10:30:00', 1),
('Seconda nota', '2025-02-25 11:00:00', '2025-02-25 11:00:00', 1),
('Terza nota', '2025-02-26 12:15:00', '2025-02-26 12:15:00', 1);
