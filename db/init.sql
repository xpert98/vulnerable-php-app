-- Schema + seed for the "vulnerable PHP app" testbed.
-- Runs automatically: docker-compose mounts this into /docker-entrypoint-initdb.d.

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

-- Seed a demo user.
-- NOTE: password is intentionally stored in PLAINTEXT here, matching this
-- testbed's theme. On the first successful login the app transparently
-- upgrades it to a bcrypt hash (see includes/auth.php).
INSERT INTO users (username, password)
VALUES ('alice', 'password123')
ON DUPLICATE KEY UPDATE username = username;

INSERT INTO products (name, price)
VALUES ('Wireless Mouse', '24.99'),
       ('Mechanical Keyboard', '89.00'),
       ('USB-C Hub', '39.50')
ON DUPLICATE KEY UPDATE name = name;
