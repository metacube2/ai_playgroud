-- Migration: Create band_media table
-- Created: 2025-12-02

CREATE TABLE IF NOT EXISTS band_media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    band_id INT NOT NULL,
    type ENUM('image', 'video') NOT NULL,
    url VARCHAR(500) NOT NULL,
    title VARCHAR(255),
    is_featured BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (band_id) REFERENCES bands(id) ON DELETE CASCADE,
    INDEX idx_band_id (band_id),
    INDEX idx_type (type),
    INDEX idx_sort_order (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
