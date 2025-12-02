-- Migration: Create bookings table
-- Created: 2025-12-02

CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    band_id INT NOT NULL,
    customer_id INT NOT NULL,
    event_date DATE NOT NULL,
    event_time TIME,
    event_location VARCHAR(255) NOT NULL,
    event_type VARCHAR(100),
    budget DECIMAL(10, 2),
    guest_count INT,
    message TEXT,
    status ENUM('pending', 'accepted', 'rejected', 'completed', 'cancelled') DEFAULT 'pending',
    band_response TEXT,
    responded_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (band_id) REFERENCES bands(id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_band_id (band_id),
    INDEX idx_customer_id (customer_id),
    INDEX idx_status (status),
    INDEX idx_event_date (event_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
