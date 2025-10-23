CREATE DATABASE IF NOT EXISTS video_platform CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE video_platform;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  display_name VARCHAR(120) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  slug VARCHAR(150) NOT NULL UNIQUE,
  image_url VARCHAR(255) DEFAULT NULL,
  color_hex VARCHAR(7) DEFAULT '#444444',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE videos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  youtube_url VARCHAR(255) NOT NULL,
  thumbnail_url VARCHAR(255) DEFAULT NULL,
  created_by INT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO users (username, password_hash, display_name)
VALUES ('demo', '$2y$10$XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX', 'Demo User');

INSERT INTO categories (name, slug, image_url, color_hex) VALUES
('NCS Music', 'ncs-music', 'assets/cat_ncs.jpg', '#1db954'),
('Chill', 'chill', 'assets/cat_chill.jpg', '#ff6b6b'),
('Gaming', 'gaming', 'assets/cat_gaming.jpg', '#6b6bff');,

ALTER TABLE users ADD COLUMN is_admin TINYINT(1) DEFAULT 0;
