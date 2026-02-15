CREATE DATABASE IF NOT EXISTS blogy CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE blogy;

CREATE TABLE IF NOT EXISTS categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_name (name)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS articles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    image VARCHAR(500) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    text TEXT NOT NULL,
    views_count INT UNSIGNED DEFAULT 0,
    published_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS article_category (
    article_id INT UNSIGNED NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (article_id, category_id),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS article_views (
    article_id INT UNSIGNED NOT NULL,
    visitor_id VARCHAR(64) NOT NULL,
    viewed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (article_id, visitor_id),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    INDEX idx_visitor (visitor_id)
) ENGINE=InnoDB;
