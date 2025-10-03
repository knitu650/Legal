<?php

/**
 * Migration: Create Roles Table
 */

$sql = "
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO roles (id, name, slug, description) VALUES
(1, 'Super Admin', 'super_admin', 'Full system access'),
(2, 'Admin', 'admin', 'Administrative access'),
(3, 'MIS', 'mis', 'MIS dashboard access'),
(4, 'Franchise', 'franchise', 'Franchise panel access'),
(5, 'Lawyer', 'lawyer', 'Lawyer panel access'),
(6, 'User', 'user', 'Regular user access');
";

return $sql;
