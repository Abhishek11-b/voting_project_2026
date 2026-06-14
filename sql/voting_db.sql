CREATE DATABASE IF NOT EXISTS voting_db;
USE voting_db;

-- ==========================
-- Admin Table
-- ==========================
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

INSERT INTO admins(username, password)
VALUES ('admin', 'admin123');

-- ==========================
-- Students
-- ==========================
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(30) UNIQUE NOT NULL,
    student_name VARCHAR(100) NOT NULL,
    department VARCHAR(100),
    class_name VARCHAR(50),
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    has_voted TINYINT(1) DEFAULT 0
);

-- ==========================
-- Election Positions
-- ==========================
CREATE TABLE positions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    position_name VARCHAR(100) NOT NULL
);

INSERT INTO positions(position_name) VALUES
('President'),
('Vice President'),
('Sports Captain'),
('Sports Vice Captain');

-- ==========================
-- Houses
-- ==========================
CREATE TABLE houses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    house_name VARCHAR(50) NOT NULL,
    house_logo VARCHAR(255)
);

INSERT INTO houses(house_name, house_logo) VALUES
('Sapphire','images/sapphire.png'),
('Coral','images/coral.png'),
('Ruby','images/ruby.png'),
('Emerald','images/emerald.png');

-- ==========================
-- Candidates
-- ==========================
CREATE TABLE candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidate_name VARCHAR(100) NOT NULL,
    position_id INT,
    house_id INT,
    photo VARCHAR(255),
    FOREIGN KEY(position_id) REFERENCES positions(id),
    FOREIGN KEY(house_id) REFERENCES houses(id)
);

-- ==========================
-- Votes
-- ==========================
CREATE TABLE votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(30),
    candidate_id INT,
    position_id INT,
    voted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(candidate_id) REFERENCES candidates(id),
    FOREIGN KEY(position_id) REFERENCES positions(id)
);