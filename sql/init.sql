-- Create database and tables for Categories API
CREATE DATABASE IF NOT EXISTS `api_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `api_db`;

CREATE TABLE IF NOT EXISTS `api_keys` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `api_key` TEXT NOT NULL,
  `user_id` INT DEFAULT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `parent_id` INT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (`slug`),
  FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Note: do NOT insert plain API keys here. Use the provided PHP seed script to generate a secure hash and insert it.
