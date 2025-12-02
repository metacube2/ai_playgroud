-- Migration: Create band_availability table
-- Created: 2025-12-02

CREATE TABLE IF NOT EXISTS band_availability (
    id INT AUTO_INCREMENT PRIMARY KEY,
    band_id INT NOT NULL,
    date DATE NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    notes VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (band_id) REFERENCES bands(id) ON DELETE CASCADE,
    UNIQUE KEY unique_band_date (band_id, date),
    INDEX idx_band_id (band_id),
    INDEX idx_date (date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
