-- Schema + seed for Tech Wire (vulnerable PHP testbed).
-- Local dev: auto-runs via docker-entrypoint-initdb.d mount.
-- Prebuilt deploys: executed idempotently by db/seed.php (one-shot container).

CREATE DATABASE IF NOT EXISTS vulnapp
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE vulnapp;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(64) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user VARCHAR(64) NOT NULL,
  comment TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(128) NOT NULL,
  price DECIMAL(10,2) NOT NULL
) ENGINE=InnoDB;

-- Demo users. alice keeps its original plaintext password for the
-- classic demo login (upgraded to bcrypt on first login by includes/auth.php).
INSERT INTO users (username, password)
VALUES ('alice', 'password123'),
       ('maya', 'password123'),
       ('jonah', 'password123'),
       ('priya', 'password123'),
       ('theo', 'password123'),
       ('sam', 'password123'),
       ('luna', 'password123'),
       ('marcus', 'password123')
ON DUPLICATE KEY UPDATE username = username;

-- Community posts/resources. First three rows preserve the original
-- testbed names so existing search payloads ("mouse", "keyboard", "hub") still hit.
INSERT INTO products (id, name, price)
VALUES (1, 'Wireless Mouse', '24.99'),
       (2, 'Mechanical Keyboard', '89.00'),
       (3, 'USB-C Hub', '39.50'),
       (4, 'Home Lab Setup Guide — Proxmox on a mini PC', '12.00'),
       (5, 'Python Debugging Playbook — from traceback to fix', '15.00'),
       (6, 'Self-Hosted RSS — escape the algorithm', '5.00'),
       (7, 'Git Crash Course Handbook', '3.50'),
       (8, 'Sprint Planning Template for side projects', '9.99'),
       (9, 'Cable Management Guide — what actually survives moves', '18.75'),
       (10, 'Houseplants for Home Offices (low light edition)', '7.25'),
       (11, 'Noise-Cancelling Earbuds Review', '129.00'),
       (12, 'Deploy Checklist — Friday-safe releases', '2.00')
ON DUPLICATE KEY UPDATE name = VALUES(name), price = VALUES(price);

INSERT INTO comments (user, comment) VALUES
('maya', 'Welcome to Tech Wire! Introduce yourself on someone’s wall.'),
('jonah', 'Debugging tip: read the last frame first, not the first. Trust me.'),
('priya', 'FreshRSS + the OPML pack in the library got me off the algorithm entirely.'),
('alice', 'Just uploaded my homelab photos to the library. Cable labels changed my life.'),
('theo', 'Anyone want to split a mini-PC group buy this weekend?'),
('sam', 'The USB-C hub in post #3 drives both library monitors, can confirm.'),
('luna', 'Best focus setup: noise-cancelling + a 25-minute timer. You’re welcome.'),
('marcus', 'Earbuds review is spot on — battery lasts through two standups + gym.'),
('maya', 'Code review script that actually works: one specific question + what you tried.'),
('jonah', 'Keyboard post saved my wrists. Brown switches + wrist rest FTW.'),
('priya', 'Self-host tip: start with RSS, then add read-later. Don’t boil the ocean.'),
('alice', 'Deploy checklist is live — Fridays are a feature, not a fear.'),
('theo', 'Cable guide should be required reading for new homelabs.'),
('sam', 'Plants that survive home offices: pothos, snake plant, ZZ. Everything else is a gamble.'),
('luna', 'Sprint template + weekly review = three shipped side projects so far.'),
('marcus', 'Git handbook tip #7 (atomic commits) actually keeps rebases painless.')
ON DUPLICATE KEY UPDATE user = user;
