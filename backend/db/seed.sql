-- -----------------------------------------------------------
-- Database: tech_navigator
-- -----------------------------------------------------------

CREATE DATABASE IF NOT EXISTS tech_navigator;
USE tech_navigator;

-- -----------------------------------------------------------
-- Table: categories
-- -----------------------------------------------------------
DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(100)
);

-- -----------------------------------------------------------
-- Table: items
-- -----------------------------------------------------------
DROP TABLE IF EXISTS items;
CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(100) NOT NULL,
    type ENUM('language','framework','tool') NOT NULL,
    description TEXT,
    difficulty_level TINYINT,
    performance_score TINYINT,
    popularity_score TINYINT,
    learning_curve ENUM('easy','medium','hard'),
    release_year INT,
    developer VARCHAR(100),
    official_site VARCHAR(255),
    logo VARCHAR(255),
    
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- -----------------------------------------------------------
-- Table: tags
-- -----------------------------------------------------------
DROP TABLE IF EXISTS tags;
CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL
);

-- -----------------------------------------------------------
-- Table: item_tags (Many-to-Many)
-- -----------------------------------------------------------
DROP TABLE IF EXISTS item_tags;
CREATE TABLE item_tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT,
    tag_id INT,
    FOREIGN KEY (item_id) REFERENCES items(id),
    FOREIGN KEY (tag_id) REFERENCES tags(id)
);

-- -----------------------------------------------------------
-- Insert Seed Categories
-- -----------------------------------------------------------
INSERT INTO categories (name, description, icon) VALUES
('Full Stack Development', 'Frontend + backend + database development.', 'fullstack.svg'),
('Backend Development', 'Server-side APIs, datastores, and logic.', 'backend.svg'),
('Frontend Web Development', 'UI, UX, layouts, and browser scripting.', 'frontend.svg'),
('Systems Programming', 'Low level OS development and high performance computing.', 'system.svg'),
('AI / Machine Learning', 'ML models, data processing, neural networks.', 'ai.svg'),
('Mobile App Development', 'Building Android, iOS, and cross-platform apps.', 'mobile.svg'),
('Desktop App Development', 'Windows/Mac/Linux application development.', 'desktop.svg');

-- -----------------------------------------------------------
-- Insert Items (Languages / Frameworks / Tools)
-- -----------------------------------------------------------

-- Languages
INSERT INTO items (category_id, name, type, description, difficulty_level, performance_score, popularity_score, learning_curve, release_year, developer, official_site)
VALUES
(1, 'JavaScript', 'language', 'Most popular language for web and full-stack apps.', 2, 3, 5, 'easy', 1995, 'Netscape', 'https://developer.mozilla.org'),
(1, 'Python', 'language', 'Easy-to-learn general-purpose language used everywhere.', 1, 2, 5, 'easy', 1991, 'Guido van Rossum', 'https://python.org'),
(2, 'Java', 'language', 'Strong, reliable language for enterprise backends.', 3, 4, 5, 'medium', 1995, 'Sun/Oracle', 'https://oracle.com/java'),
(4, 'C++', 'language', 'High-performance language used in OS, games, engines.', 5, 5, 4, 'hard', 1985, 'Bjarne Stroustrup', 'https://isocpp.org'),
(4, 'Rust', 'language', 'Memory-safe systems programming language.', 4, 5, 4, 'hard', 2010, 'Mozilla', 'https://rust-lang.org');

-- Frameworks
INSERT INTO items (category_id, name, type, description, difficulty_level, performance_score, popularity_score, learning_curve, release_year, developer, official_site)
VALUES
(1, 'React', 'framework', 'UI library for building fast single-page apps.', 2, 4, 5, 'medium', 2013, 'Meta', 'https://react.dev'),
(1, 'Node.js', 'framework', 'JavaScript runtime for backend servers.', 2, 4, 5, 'easy', 2009, 'Node Foundation', 'https://nodejs.org'),
(2, 'Django', 'framework', 'High-level Python backend framework.', 2, 3, 4, 'medium', 2005, 'Django Software Foundation', 'https://djangoproject.com'),
(2, 'Laravel', 'framework', 'Elegant PHP backend framework.', 2, 3, 5, 'easy', 2011, 'Taylor Otwell', 'https://laravel.com'),
(6, 'Flutter', 'framework', 'Cross-platform mobile app framework.', 3, 4, 5, 'medium', 2017, 'Google', 'https://flutter.dev');

-- Tools
INSERT INTO items (category_id, name, type, description, difficulty_level, performance_score, popularity_score, learning_curve, release_year, developer, official_site)
VALUES
(1, 'Git', 'tool', 'Version control system for developers.', 1, 4, 5, 'medium', 2005, 'Linus Torvalds', 'https://git-scm.com'),
(1, 'VS Code', 'tool', 'Most popular code editor.', 1, 3, 5, 'easy', 2015, 'Microsoft', 'https://code.visualstudio.com'),
(2, 'MySQL', 'tool', 'Popular relational database.', 2, 4, 5, 'medium', 1995, 'Oracle', 'https://mysql.com'),
(2, 'PostgreSQL', 'tool', 'Advanced open-source database.', 3, 5, 5, 'hard', 1996, 'Postgres Global Dev Group', 'https://postgresql.org');

-- -----------------------------------------------------------
-- Insert Tags
-- -----------------------------------------------------------
INSERT INTO tags (name) VALUES
('backend'),
('frontend'),
('fullstack'),
('mobile'),
('desktop'),
('high-performance'),
('easy'),
('popular'),
('secure'),
('cross-platform'),
('enterprise'),
('beginner-friendly');

-- -----------------------------------------------------------
-- Insert Item-Tag Relations
-- -----------------------------------------------------------

INSERT INTO item_tags (item_id, tag_id) VALUES
-- JavaScript
(1, 3),  -- fullstack
(1, 2),  -- frontend
(1, 8),  -- popular
(1, 7),  -- easy

-- Python
(2, 1),  -- backend
(2, 12), -- beginner-friendly
(2, 8),  -- popular

-- Java
(3, 1),
(3, 11),

-- C++
(4, 6),  -- high-performance
(4, 9),  -- secure

-- Rust
(5, 6),
(5, 9),

-- React
(6, 2),
(6, 8),

-- Node.js
(7, 1),
(7, 3),
(7, 8),

-- Django
(8, 1),
(8, 12),

-- Laravel
(9, 1),
(9, 12),

-- Flutter
(10, 4),
(10, 10),

-- Git
(11, 3),

-- VS Code
(12, 12),

-- MySQL
(13, 1),

-- PostgreSQL
(14, 1),
(14, 9);
