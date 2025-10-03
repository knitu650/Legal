-- Create the database
CREATE DATABASE IF NOT EXISTS legal_docs;
USE legal_docs;


-- Users Table
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid` CHAR(36) NOT NULL,
    `username` VARCHAR(50) UNIQUE NOT NULL,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `phone` VARCHAR(20) UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `first_name` VARCHAR(100) NOT NULL,
    `last_name` VARCHAR(100) NOT NULL,
    `full_name` VARCHAR(200) GENERATED ALWAYS AS (CONCAT(first_name, ' ', last_name)) STORED,
    `date_of_birth` DATE NULL,
    `gender` ENUM('male', 'female', 'other') DEFAULT 'other',
    `avatar` VARCHAR(500) NULL,
    
    -- Address Information
    `address_line1` VARCHAR(255) NULL,
    `address_line2` VARCHAR(255) NULL,
    `city` VARCHAR(100) NULL,
    `state` VARCHAR(100) NULL,
    `country` VARCHAR(100) DEFAULT 'India',
    `postal_code` VARCHAR(20) NULL,
    `latitude` DECIMAL(10, 8) NULL,
    `longitude` DECIMAL(11, 8) NULL,
    
    -- KYC Information
    `pan_number` VARCHAR(10) NULL,
    `aadhar_number` VARCHAR(12) NULL,
    `kyc_verified` BOOLEAN DEFAULT FALSE,
    `kyc_verified_at` TIMESTAMP NULL,
    
    -- Account Status
    `status` ENUM('active', 'inactive', 'suspended', 'deleted', 'pending_verification') DEFAULT 'pending_verification',
    `email_verified` BOOLEAN DEFAULT FALSE,
    `phone_verified` BOOLEAN DEFAULT FALSE,
    `email_verified_at` TIMESTAMP NULL,
    `phone_verified_at` TIMESTAMP NULL,
    
    -- Security
    `two_factor_enabled` BOOLEAN DEFAULT FALSE,
    `two_factor_secret` VARCHAR(255) NULL,
    `two_factor_recovery_codes` TEXT NULL,
    `password_changed_at` TIMESTAMP NULL,
    `last_login_at` TIMESTAMP NULL,
    `last_login_ip` VARCHAR(45) NULL,
    `last_login_user_agent` TEXT NULL,
    `failed_login_attempts` INT DEFAULT 0,
    `locked_until` TIMESTAMP NULL,
    
    -- Preferences
    `preferred_language` VARCHAR(10) DEFAULT 'en',
    `timezone` VARCHAR(50) DEFAULT 'Asia/Kolkata',
    `currency` VARCHAR(3) DEFAULT 'INR',
    `theme_preference` ENUM('light', 'dark', 'auto') DEFAULT 'light',
    
    -- Referral
    `referral_code` VARCHAR(20) UNIQUE NULL,
    `referred_by` BIGINT UNSIGNED NULL,
    
    -- Location Association
    `location_id` BIGINT UNSIGNED NULL,
    
    -- Metadata
    `metadata` JSON NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_uuid` (`uuid`),
    UNIQUE KEY `unique_username` (`username`),
    UNIQUE KEY `unique_email` (`email`),
    UNIQUE KEY `unique_referral_code` (`referral_code`),
    KEY `idx_phone` (`phone`),
    KEY `idx_status` (`status`),
    KEY `idx_email_verified` (`email_verified`),
    KEY `idx_created_at` (`created_at`),
    KEY `idx_referred_by` (`referred_by`),
    KEY `idx_location` (`location_id`),
    KEY `idx_deleted_at` (`deleted_at`),
    FULLTEXT KEY `idx_fulltext_name` (`first_name`, `last_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Roles Table
CREATE TABLE `roles` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) UNIQUE NOT NULL,
    `slug` VARCHAR(50) UNIQUE NOT NULL,
    `display_name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `is_system_role` BOOLEAN DEFAULT FALSE,
    `level` INT DEFAULT 0 COMMENT 'Role hierarchy level',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_name` (`name`),
    UNIQUE KEY `unique_slug` (`slug`),
    KEY `idx_system_role` (`is_system_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Permissions Table
CREATE TABLE `permissions` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) UNIQUE NOT NULL,
    `slug` VARCHAR(100) UNIQUE NOT NULL,
    `display_name` VARCHAR(150) NOT NULL,
    `description` TEXT NULL,
    `module` VARCHAR(50) NOT NULL,
    `action` VARCHAR(50) NOT NULL COMMENT 'create, read, update, delete',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_name` (`name`),
    UNIQUE KEY `unique_slug` (`slug`),
    KEY `idx_module` (`module`),
    KEY `idx_action` (`action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User Roles (Many-to-Many)
CREATE TABLE `user_roles` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `role_id` INT UNSIGNED NOT NULL,
    `assigned_by` BIGINT UNSIGNED NULL,
    `assigned_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `expires_at` TIMESTAMP NULL,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_user_role` (`user_id`, `role_id`),
    KEY `idx_user` (`user_id`),
    KEY `idx_role` (`role_id`),
    KEY `idx_assigned_by` (`assigned_by`),
    CONSTRAINT `fk_user_roles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_user_roles_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_user_roles_assigned_by` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Role Permissions (Many-to-Many)
CREATE TABLE `role_permissions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `role_id` INT UNSIGNED NOT NULL,
    `permission_id` INT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_role_permission` (`role_id`, `permission_id`),
    KEY `idx_role` (`role_id`),
    KEY `idx_permission` (`permission_id`),
    CONSTRAINT `fk_role_permissions_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_role_permissions_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User Permissions (Direct permissions override)
CREATE TABLE `user_permissions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `permission_id` INT UNSIGNED NOT NULL,
    `granted` BOOLEAN DEFAULT TRUE,
    `granted_by` BIGINT UNSIGNED NULL,
    `granted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_user_permission` (`user_id`, `permission_id`),
    KEY `idx_user` (`user_id`),
    KEY `idx_permission` (`permission_id`),
    CONSTRAINT `fk_user_permissions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_user_permissions_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_user_permissions_granted_by` FOREIGN KEY (`granted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- LOCATION & FRANCHISE TABLES
-- ============================================================================

-- Locations Table (Cities/Regions)
CREATE TABLE `locations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(150) UNIQUE NOT NULL,
    `type` ENUM('country', 'state', 'city', 'region') DEFAULT 'city',
    `parent_id` BIGINT UNSIGNED NULL,
    
    -- Geographic Data
    `country` VARCHAR(100) DEFAULT 'India',
    `state` VARCHAR(100) NOT NULL,
    `city` VARCHAR(100) NOT NULL,
    `latitude` DECIMAL(10, 8) NULL,
    `longitude` DECIMAL(11, 8) NULL,
    `postal_codes` JSON NULL COMMENT 'Array of postal codes covered',
    
    -- Service Information
    `is_serviceable` BOOLEAN DEFAULT TRUE,
    `service_radius` INT DEFAULT 10 COMMENT 'Service radius in kilometers',
    
    -- Legal Requirements
    `stamp_duty_rates` JSON NULL COMMENT 'Document type wise stamp duty rates',
    `legal_requirements` JSON NULL COMMENT 'State specific legal requirements',
    
    -- Pricing
    `price_multiplier` DECIMAL(5, 2) DEFAULT 1.00 COMMENT 'Regional pricing multiplier',
    
    -- Status
    `status` ENUM('active', 'inactive', 'coming_soon') DEFAULT 'active',
    
    -- Metadata
    `metadata` JSON NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_slug` (`slug`),
    KEY `idx_parent` (`parent_id`),
    KEY `idx_type` (`type`),
    KEY `idx_state` (`state`),
    KEY `idx_city` (`city`),
    KEY `idx_serviceable` (`is_serviceable`),
    KEY `idx_status` (`status`),
    CONSTRAINT `fk_locations_parent` FOREIGN KEY (`parent_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Franchises Table
CREATE TABLE `franchises` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `franchise_code` VARCHAR(20) UNIQUE NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL COMMENT 'Franchise owner user ID',
    `location_id` BIGINT UNSIGNED NOT NULL,
    
    -- Business Information
    `business_name` VARCHAR(200) NOT NULL,
    `business_type` ENUM('individual', 'partnership', 'company', 'llp') NOT NULL,
    `registration_number` VARCHAR(100) NULL,
    `gst_number` VARCHAR(15) NULL,
    `pan_number` VARCHAR(10) NULL,
    
    -- Contact Information
    `contact_person` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `alternate_phone` VARCHAR(20) NULL,
    
    -- Address
    `address_line1` VARCHAR(255) NOT NULL,
    `address_line2` VARCHAR(255) NULL,
    `city` VARCHAR(100) NOT NULL,
    `state` VARCHAR(100) NOT NULL,
    `postal_code` VARCHAR(20) NOT NULL,
    
    -- Territory
    `territory` JSON NULL COMMENT 'Covered areas/postal codes',
    `exclusive_territory` BOOLEAN DEFAULT FALSE,
    
    -- Agreement Details
    `agreement_start_date` DATE NOT NULL,
    `agreement_end_date` DATE NOT NULL,
    `agreement_document` VARCHAR(500) NULL,
    
    -- Financial Details
    `initial_investment` DECIMAL(12, 2) NOT NULL,
    `security_deposit` DECIMAL(12, 2) NOT NULL,
    `commission_rate` DECIMAL(5, 2) NOT NULL COMMENT 'Commission percentage',
    `monthly_target` DECIMAL(12, 2) DEFAULT 0.00,
    
    -- Banking Details
    `bank_name` VARCHAR(100) NULL,
    `account_number` VARCHAR(50) NULL,
    `ifsc_code` VARCHAR(11) NULL,
    `account_holder_name` VARCHAR(100) NULL,
    
    -- Performance Metrics
    `total_revenue` DECIMAL(15, 2) DEFAULT 0.00,
    `total_commission` DECIMAL(15, 2) DEFAULT 0.00,
    `total_documents` INT DEFAULT 0,
    `total_customers` INT DEFAULT 0,
    `rating` DECIMAL(3, 2) DEFAULT 0.00,
    
    -- Status
    `status` ENUM('pending', 'approved', 'active', 'suspended', 'terminated') DEFAULT 'pending',
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` TIMESTAMP NULL,
    
    -- Staff Limit
    `max_staff_allowed` INT DEFAULT 5,
    
    -- Metadata
    `notes` TEXT NULL,
    `metadata` JSON NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_franchise_code` (`franchise_code`),
    KEY `idx_user` (`user_id`),
    KEY `idx_location` (`location_id`),
    KEY `idx_status` (`status`),
    KEY `idx_approved_by` (`approved_by`),
    CONSTRAINT `fk_franchises_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_franchises_location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`),
    CONSTRAINT `fk_franchises_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Franchise Staff Table
CREATE TABLE `franchise_staff` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `franchise_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `position` VARCHAR(100) NOT NULL,
    `permissions` JSON NULL COMMENT 'Franchise specific permissions',
    `salary` DECIMAL(10, 2) NULL,
    `joined_at` DATE NOT NULL,
    `left_at` DATE NULL,
    `status` ENUM('active', 'inactive', 'terminated') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_franchise_user` (`franchise_id`, `user_id`),
    KEY `idx_franchise` (`franchise_id`),
    KEY `idx_user` (`user_id`),
    KEY `idx_status` (`status`),
    CONSTRAINT `fk_franchise_staff_franchise` FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_franchise_staff_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Franchise Applications
CREATE TABLE `franchise_applications` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `application_number` VARCHAR(50) UNIQUE NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `location_id` BIGINT UNSIGNED NOT NULL,
    
    -- Applicant Information
    `full_name` VARCHAR(200) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `alternate_phone` VARCHAR(20) NULL,
    
    -- Business Proposal
    `business_experience` TEXT NULL,
    `investment_capacity` DECIMAL(12, 2) NOT NULL,
    `proposed_location` TEXT NOT NULL,
    `why_franchise` TEXT NULL,
    
    -- Documents
    `documents` JSON NULL COMMENT 'Array of uploaded documents',
    
    -- Status
    `status` ENUM('pending', 'under_review', 'approved', 'rejected', 'withdrawn') DEFAULT 'pending',
    `reviewed_by` BIGINT UNSIGNED NULL,
    `review_notes` TEXT NULL,
    `reviewed_at` TIMESTAMP NULL,
    
    -- Follow-up
    `follow_up_date` DATE NULL,
    
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_application_number` (`application_number`),
    KEY `idx_user` (`user_id`),
    KEY `idx_location` (`location_id`),
    KEY `idx_status` (`status`),
    KEY `idx_reviewed_by` (`reviewed_by`),
    CONSTRAINT `fk_franchise_apps_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_franchise_apps_location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`),
    CONSTRAINT `fk_franchise_apps_reviewed_by` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- DOCUMENT MANAGEMENT TABLES
-- ============================================================================

-- Document Categories
CREATE TABLE `document_categories` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `parent_id` INT UNSIGNED NULL,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(150) UNIQUE NOT NULL,
    `description` TEXT NULL,
    `icon` VARCHAR(100) NULL,
    `image` VARCHAR(500) NULL,
    `display_order` INT DEFAULT 0,
    `is_active` BOOLEAN DEFAULT TRUE,
    `is_featured` BOOLEAN DEFAULT FALSE,
    `seo_title` VARCHAR(200) NULL,
    `seo_description` TEXT NULL,
    `seo_keywords` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_slug` (`slug`),
    KEY `idx_parent` (`parent_id`),
    KEY `idx_active` (`is_active`),
    KEY `idx_featured` (`is_featured`),
    KEY `idx_display_order` (`display_order`),
    CONSTRAINT `fk_doc_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `document_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Document Templates
CREATE TABLE `document_templates` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid` CHAR(36) NOT NULL,
    `category_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(250) UNIQUE NOT NULL,
    `short_description` TEXT NULL,
    `description` TEXT NULL,
    
    -- Template Content
    `content` LONGTEXT NOT NULL,
    `fields` JSON NOT NULL COMMENT 'Dynamic form fields configuration',
    `validation_rules` JSON NULL COMMENT 'Field validation rules',
    
    -- Pricing
    `base_price` DECIMAL(10, 2) DEFAULT 0.00,
    `discounted_price` DECIMAL(10, 2) NULL,
    `stamp_duty_applicable` BOOLEAN DEFAULT FALSE,
    `processing_fee` DECIMAL(10, 2) DEFAULT 0.00,
    
    -- Legal Information
    `legal_validity` TEXT NULL,
    `applicable_states` JSON COMMENT 'Array of state codes',
    `legal_references` TEXT NULL,
    `terms_conditions` TEXT NULL,
    
    -- Usage Statistics
    `usage_count` INT DEFAULT 0,
    `view_count` INT DEFAULT 0,
    `popularity_score` DECIMAL(5, 2) DEFAULT 0.00,
    `average_rating` DECIMAL(3, 2) DEFAULT 0.00,
    `total_ratings` INT DEFAULT 0,
    
    -- Versioning
    `version` VARCHAR(20) DEFAULT '1.0',
    `parent_template_id` BIGINT UNSIGNED NULL,
    
    -- Status
    `status` ENUM('draft', 'published', 'archived', 'deprecated') DEFAULT 'draft',
    `is_featured` BOOLEAN DEFAULT FALSE,
    `is_premium` BOOLEAN DEFAULT FALSE,
    `is_customizable` BOOLEAN DEFAULT TRUE,
    
    -- SEO
    `seo_title` VARCHAR(200) NULL,
    `seo_description` TEXT NULL,
    `seo_keywords` TEXT NULL,
    
    -- Metadata
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `published_at` TIMESTAMP NULL,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_uuid` (`uuid`),
    UNIQUE KEY `unique_slug` (`slug`),
    KEY `idx_category` (`category_id`),
    KEY `idx_status` (`status`),
    KEY `idx_featured` (`is_featured`),
    KEY `idx_premium` (`is_premium`),
    KEY `idx_parent` (`parent_template_id`),
    KEY `idx_created_by` (`created_by`),
    KEY `idx_popularity` (`popularity_score`),
    FULLTEXT KEY `idx_search` (`name`, `short_description`, `description`),
    CONSTRAINT `fk_doc_templates_category` FOREIGN KEY (`category_id`) REFERENCES `document_categories` (`id`),
    CONSTRAINT `fk_doc_templates_parent` FOREIGN KEY (`parent_template_id`) REFERENCES `document_templates` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_doc_templates_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_doc_templates_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Documents (User Generated)
CREATE TABLE `documents` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid` CHAR(36) NOT NULL,
    `document_number` VARCHAR(100) UNIQUE NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `template_id` BIGINT UNSIGNED NULL,
    `franchise_id` BIGINT UNSIGNED NULL,
    `location_id` BIGINT UNSIGNED NULL,
    
    -- Document Details
    `title` VARCHAR(200) NOT NULL,
    `document_type` VARCHAR(100) NULL,
    
    -- Content
    `content` LONGTEXT NOT NULL,
    `filled_data` JSON COMMENT 'User filled form data',
    `variables` JSON COMMENT 'Document variables/placeholders',
    
    -- File Information
    `file_path` VARCHAR(500) NULL,
    `file_name` VARCHAR(255) NULL,
    `file_size` BIGINT COMMENT 'Size in bytes',
    `file_type` VARCHAR(50) DEFAULT 'application/pdf',
    `file_hash` VARCHAR(64) COMMENT 'SHA-256 hash',
    
    -- Document Status
    `status` ENUM('draft', 'pending_payment', 'processing', 'pending_signature', 'completed', 'signed', 'archived', 'cancelled', 'expired') DEFAULT 'draft',
    `previous_status` VARCHAR(50) NULL,
    
    -- Signatures
    `requires_signature` BOOLEAN DEFAULT FALSE,
    `required_signatures` INT DEFAULT 0,
    `completed_signatures` INT DEFAULT 0,
    `all_signed` BOOLEAN DEFAULT FALSE,
    `signed_at` TIMESTAMP NULL,
    
    -- Stamp Paper
    `stamp_duty_paid` BOOLEAN DEFAULT FALSE,
    `stamp_duty_amount` DECIMAL(10, 2) DEFAULT 0.00,
    `stamp_paper_number` VARCHAR(100) NULL,
    `stamp_paper_value` DECIMAL(10, 2) NULL,
    
    -- Notarization
    `notarization_required` BOOLEAN DEFAULT FALSE,
    `notarized` BOOLEAN DEFAULT FALSE,
    `notarized_by` VARCHAR(200) NULL,
    `notarized_at` TIMESTAMP NULL,
    
    -- Validity
    `valid_from` DATE NULL,
    `valid_until` DATE NULL,
    `is_expired` BOOLEAN DEFAULT FALSE,
    
    -- Security
    `is_encrypted` BOOLEAN DEFAULT FALSE,
    `encryption_key` VARCHAR(255) NULL,
    `access_password` VARCHAR(255) NULL,
    `watermark_enabled` BOOLEAN DEFAULT TRUE,
    `download_protection` BOOLEAN DEFAULT FALSE,
    
    -- Sharing
    `is_shared` BOOLEAN DEFAULT FALSE,
    `share_token` VARCHAR(64) NULL,
    `share_expires_at` TIMESTAMP NULL,
    `share_count` INT DEFAULT 0,
    `view_count` INT DEFAULT 0,
    `download_count` INT DEFAULT 0,
    
    -- Pricing
    `base_amount` DECIMAL(10, 2) DEFAULT 0.00,
    `stamp_duty` DECIMAL(10, 2) DEFAULT 0.00,
    `processing_fee` DECIMAL(10, 2) DEFAULT 0.00,
    `discount` DECIMAL(10, 2) DEFAULT 0.00,
    `total_amount` DECIMAL(10, 2) DEFAULT 0.00,
    
    -- Priority
    `priority` ENUM('low', 'normal', 'high', 'urgent') DEFAULT 'normal',
    `processing_time` INT DEFAULT 24 COMMENT 'Processing time in hours',
    
    -- Notes
    `user_notes` TEXT NULL,
    `admin_notes` TEXT NULL,
    
    -- Metadata
    `metadata` JSON NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `completed_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_uuid` (`uuid`),
    UNIQUE KEY `unique_document_number` (`document_number`),
    KEY `idx_user` (`user_id`),
    KEY `idx_template` (`template_id`),
    KEY `idx_franchise` (`franchise_id`),
    KEY `idx_location` (`location_id`),
    KEY `idx_status` (`status`),
    KEY `idx_created_at` (`created_at`),
    KEY `idx_share_token` (`share_token`),
    KEY `idx_deleted_at` (`deleted_at`),
    FULLTEXT KEY `idx_search` (`title`, `content`),
    CONSTRAINT `fk_documents_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_documents_template` FOREIGN KEY (`template_id`) REFERENCES `document_templates` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_documents_franchise` FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_documents_location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Document Versions
CREATE TABLE `document_versions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `document_id` BIGINT UNSIGNED NOT NULL,
    `version_number` INT NOT NULL,
    `content` LONGTEXT NOT NULL,
    `file_path` VARCHAR(500) NULL,
    `changes_description` TEXT NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_doc_version` (`document_id`, `version_number`),
    KEY `idx_document` (`document_id`),
    KEY `idx_created_by` (`created_by`),
    CONSTRAINT `fk_doc_versions_document` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_doc_versions_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Document Collaborators
CREATE TABLE `document_collaborators` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `document_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `email` VARCHAR(255) NOT NULL,
    `name` VARCHAR(200) NULL,
    `permission` ENUM('view', 'edit', 'sign', 'admin') DEFAULT 'view',
    `invited_by` BIGINT UNSIGNED NULL,
    `invitation_token` VARCHAR(64) NULL,
    `invitation_status` ENUM('pending', 'accepted', 'rejected', 'expired') DEFAULT 'pending',
    `message` TEXT NULL,
    `invited_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `responded_at` TIMESTAMP NULL,
    `last_accessed_at` TIMESTAMP NULL,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_doc_email` (`document_id`, `email`),
    KEY `idx_document` (`document_id`),
    KEY `idx_user` (`user_id`),
    KEY `idx_invited_by` (`invited_by`),
    KEY `idx_token` (`invitation_token`),
    KEY `idx_status` (`invitation_status`),
    CONSTRAINT `fk_doc_collab_document` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_doc_collab_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_doc_collab_invited_by` FOREIGN KEY (`invited_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Document Activities
CREATE TABLE `document_activities` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `document_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `activity_type` VARCHAR(50) NOT NULL COMMENT 'created, updated, shared, signed, etc.',
    `description` VARCHAR(500) NOT NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `metadata` JSON NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    KEY `idx_document` (`document_id`),
    KEY `idx_user` (`user_id`),
    KEY `idx_type` (`activity_type`),
    KEY `idx_created_at` (`created_at`),
    CONSTRAINT `fk_doc_activities_document` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_doc_activities_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- SIGNATURE MANAGEMENT TABLES
-- ============================================================================

-- Signatures
CREATE TABLE `signatures` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid` CHAR(36) NOT NULL,
    `document_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    
    -- Signature Data
    `signature_type` ENUM('drawn', 'typed', 'uploaded', 'digital', 'esign') NOT NULL,
    `signature_data` LONGTEXT NOT NULL COMMENT 'Base64 or file path',
    
    -- Signer Information
    `signer_name` VARCHAR(200) NOT NULL,
    `signer_email` VARCHAR(255) NOT NULL,
    `signer_phone` VARCHAR(20) NULL,
    `signer_ip` VARCHAR(45) NULL,
    `signer_location` VARCHAR(255) NULL,
    `signer_device` VARCHAR(255) NULL,
    
    -- Digital Signature (For Aadhaar eSign)
    `certificate_data` TEXT NULL,
    `certificate_serial` VARCHAR(100) NULL,
    `certificate_issuer` VARCHAR(255) NULL,
    `signature_algorithm` VARCHAR(100) NULL,
    
    -- Position in Document
    `page_number` INT NULL,
    `x_position` INT NULL,
    `y_position` INT NULL,
    `width` INT NULL,
    `height` INT NULL,
    
    -- Verification
    `is_verified` BOOLEAN DEFAULT FALSE,
    `verification_code` VARCHAR(64) NULL,
    `verification_method` VARCHAR(50) NULL,
    `verified_at` TIMESTAMP NULL,
    
    -- OTP Verification (for eSign)
    `otp_sent` BOOLEAN DEFAULT FALSE,
    `otp_verified` BOOLEAN DEFAULT FALSE,
    `otp_sent_at` TIMESTAMP NULL,
    
    -- Timestamp
    `signed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_uuid` (`uuid`),
    KEY `idx_document` (`document_id`),
    KEY `idx_user` (`user_id`),
    KEY `idx_verification_code` (`verification_code`),
    KEY `idx_signed_at` (`signed_at`),
    CONSTRAINT `fk_signatures_document` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_signatures_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Signature Requests
CREATE TABLE `signature_requests` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `document_id` BIGINT UNSIGNED NOT NULL,
    `requester_id` BIGINT UNSIGNED NOT NULL,
    `signer_email` VARCHAR(255) NOT NULL,
    `signer_name` VARCHAR(200) NULL,
    `signer_user_id` BIGINT UNSIGNED NULL,
    
    -- Request Details
    `signing_order` INT DEFAULT 1,
    `message` TEXT NULL,
    `request_token` VARCHAR(64) UNIQUE NOT NULL,
    `reminder_sent` INT DEFAULT 0,
    `last_reminder_at` TIMESTAMP NULL,
    
    -- Status
    `status` ENUM('pending', 'sent', 'viewed', 'signed', 'declined', 'expired', 'cancelled') DEFAULT 'pending',
    
    -- Timestamps
    `expires_at` TIMESTAMP NULL,
    `sent_at` TIMESTAMP NULL,
    `viewed_at` TIMESTAMP NULL,
    `signed_at` TIMESTAMP NULL,
    `declined_at` TIMESTAMP NULL,
    `decline_reason` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_request_token` (`request_token`),
    KEY `idx_document` (`document_id`),
    KEY `idx_requester` (`requester_id`),
    KEY `idx_signer_user` (`signer_user_id`),
    KEY `idx_status` (`status`),
    KEY `idx_email` (`signer_email`),
    CONSTRAINT `fk_sign_requests_document` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_sign_requests_requester` FOREIGN KEY (`requester_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_sign_requests_signer` FOREIGN KEY (`signer_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- PAYMENT & SUBSCRIPTION TABLES
-- ============================================================================

-- Subscription Plans
CREATE TABLE `subscription_plans` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(150) UNIQUE NOT NULL,
    `short_description` VARCHAR(255) NULL,
    `description` TEXT NULL,
    
    -- Pricing
    `price` DECIMAL(10, 2) NOT NULL,
    `billing_cycle` ENUM('daily', 'weekly', 'monthly', 'quarterly', 'yearly', 'lifetime') NOT NULL,
    `currency` VARCHAR(3) DEFAULT 'INR',
    `setup_fee` DECIMAL(10, 2) DEFAULT 0.00,
    `discount_percentage` DECIMAL(5, 2) DEFAULT 0.00,
    
    -- Trial
    `trial_days` INT DEFAULT 0,
    `trial_price` DECIMAL(10, 2) DEFAULT 0.00,
    
    -- Features & Limits
    `features` JSON COMMENT 'Array of plan features',
    `document_limit` INT DEFAULT 10 COMMENT '-1 for unlimited',
    `template_access` JSON COMMENT 'Array of accessible template IDs or "all"',
    `storage_limit` BIGINT DEFAULT 1073741824 COMMENT 'Storage in bytes',
    `signature_limit` INT DEFAULT -1,
    `user_limit` INT DEFAULT 1,
    `support_type` ENUM('email', 'chat', 'phone', 'priority') DEFAULT 'email',
    `api_access` BOOLEAN DEFAULT FALSE,
    `white_label` BOOLEAN DEFAULT FALSE,
    
    -- Display
    `is_active` BOOLEAN DEFAULT TRUE,
    `is_popular` BOOLEAN DEFAULT FALSE,
    `is_featured` BOOLEAN DEFAULT FALSE,
    `display_order` INT DEFAULT 0,
    `badge` VARCHAR(50) NULL,
    
    -- Restrictions
    `max_subscriptions` INT DEFAULT -1 COMMENT 'Max active subscriptions, -1 for unlimited',
    `restricted_to_locations` JSON NULL COMMENT 'Location restrictions',
    
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_slug` (`slug`),
    KEY `idx_active` (`is_active`),
    KEY `idx_popular` (`is_popular`),
    KEY `idx_display_order` (`display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User Subscriptions
CREATE TABLE `user_subscriptions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `plan_id` INT UNSIGNED NOT NULL,
    `subscription_number` VARCHAR(50) UNIQUE NOT NULL,
    
    -- Status
    `status` ENUM('trial', 'active', 'cancelled', 'expired', 'suspended', 'past_due') DEFAULT 'active',
    
    -- Billing
    `amount_paid` DECIMAL(10, 2) NOT NULL,
    `currency` VARCHAR(3) DEFAULT 'INR',
    `billing_cycle` VARCHAR(20) NOT NULL,
    
    -- Trial
    `is_trial` BOOLEAN DEFAULT FALSE,
    `trial_ends_at` TIMESTAMP NULL,
    
    -- Usage Tracking
    `documents_used` INT DEFAULT 0,
    `storage_used` BIGINT DEFAULT 0 COMMENT 'Bytes',
    `signatures_used` INT DEFAULT 0,
    
    -- Dates
    `started_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `current_period_start` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `current_period_end` TIMESTAMP NOT NULL,
    `expires_at` TIMESTAMP NOT NULL,
    `cancelled_at` TIMESTAMP NULL,
    `cancellation_reason` TEXT NULL,
    
    -- Auto Renewal
    `auto_renew` BOOLEAN DEFAULT TRUE,
    `next_billing_date` DATE NULL,
    
    -- Payment Gateway
    `gateway_subscription_id` VARCHAR(100) NULL,
    `gateway_customer_id` VARCHAR(100) NULL,
    
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_subscription_number` (`subscription_number`),
    KEY `idx_user` (`user_id`),
    KEY `idx_plan` (`plan_id`),
    KEY `idx_status` (`status`),
    KEY `idx_expires` (`expires_at`),
    KEY `idx_auto_renew` (`auto_renew`),
    CONSTRAINT `fk_user_subs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_user_subs_plan` FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Transactions
CREATE TABLE `transactions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `transaction_id` VARCHAR(100) UNIQUE NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    
    -- Transaction Details
    `type` ENUM('document_purchase', 'subscription', 'subscription_renewal', 'stamp_duty', 'consultation', 'refund', 'commission_payout', 'wallet_credit') NOT NULL,
    `amount` DECIMAL(12, 2) NOT NULL,
    `currency` VARCHAR(3) DEFAULT 'INR',
    `exchange_rate` DECIMAL(10, 4) DEFAULT 1.0000,
    
    -- Payment Gateway
    `gateway` VARCHAR(50) NOT NULL COMMENT 'razorpay, stripe, paytm, etc.',
    `gateway_transaction_id` VARCHAR(200) NULL,
    `gateway_order_id` VARCHAR(200) NULL,
    `gateway_response` JSON NULL,
    
    -- Related Entities
    `document_id` BIGINT UNSIGNED NULL,
    `subscription_id` BIGINT UNSIGNED NULL,
    `franchise_id` BIGINT UNSIGNED NULL,
    
    -- Status
    `status` ENUM('pending', 'processing', 'completed', 'failed', 'refunded', 'cancelled', 'disputed') DEFAULT 'pending',
    
    -- Payment Details
    `payment_method` VARCHAR(50) NULL COMMENT 'card, upi, netbanking, wallet',
    `card_last_four` VARCHAR(4) NULL,
    `card_brand` VARCHAR(50) NULL,
    `upi_id` VARCHAR(100) NULL,
    `bank_name` VARCHAR(100) NULL,
    
    -- Fees & Taxes
    `subtotal` DECIMAL(12, 2) NOT NULL,
    `tax_amount` DECIMAL(12, 2) DEFAULT 0.00,
    `tax_percentage` DECIMAL(5, 2) DEFAULT 18.00,
    `discount_amount` DECIMAL(12, 2) DEFAULT 0.00,
    `processing_fee` DECIMAL(10, 2) DEFAULT 0.00,
    `gateway_fee` DECIMAL(10, 2) DEFAULT 0.00,
    
    -- Refund Details
    `refund_amount` DECIMAL(12, 2) DEFAULT 0.00,
    `refund_reason` TEXT NULL,
    `refunded_at` TIMESTAMP NULL,
    
    -- Notes
    `description` VARCHAR(500) NULL,
    `notes` TEXT NULL,
    `metadata` JSON NULL,
    
    -- Timestamps
    `completed_at` TIMESTAMP NULL,
    `failed_at` TIMESTAMP NULL,
    `failed_reason` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_transaction_id` (`transaction_id`),
    KEY `idx_user` (`user_id`),
    KEY `idx_gateway_txn` (`gateway_transaction_id`),
    KEY `idx_status` (`status`),
    KEY `idx_type` (`type`),
    KEY `idx_document` (`document_id`),
    KEY `idx_subscription` (`subscription_id`),
    KEY `idx_franchise` (`franchise_id`),
    KEY `idx_created_at` (`created_at`),
    CONSTRAINT `fk_transactions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_transactions_document` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_transactions_subscription` FOREIGN KEY (`subscription_id`) REFERENCES `user_subscriptions` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_transactions_franchise` FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Invoices
CREATE TABLE `invoices` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `invoice_number` VARCHAR(50) UNIQUE NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `transaction_id` BIGINT UNSIGNED NULL,
    `franchise_id` BIGINT UNSIGNED NULL,
    
    -- Invoice Type
    `invoice_type` ENUM('sale', 'subscription', 'refund', 'commission') DEFAULT 'sale',
    
    -- Amounts
    `subtotal` DECIMAL(12, 2) NOT NULL,
    `tax_amount` DECIMAL(12, 2) DEFAULT 0.00,
    `discount_amount` DECIMAL(12, 2) DEFAULT 0.00,
    `total_amount` DECIMAL(12, 2) NOT NULL,
    `currency` VARCHAR(3) DEFAULT 'INR',
    
    -- Tax Information
    `tax_percentage` DECIMAL(5, 2) DEFAULT 18.00,
    `cgst` DECIMAL(12, 2) DEFAULT 0.00,
    `sgst` DECIMAL(12, 2) DEFAULT 0.00,
    `igst` DECIMAL(12, 2) DEFAULT 0.00,
    `gstin` VARCHAR(15) NULL,
    `place_of_supply` VARCHAR(100) NULL,
    
    -- Customer Details
    `customer_name` VARCHAR(200) NOT NULL,
    `customer_email` VARCHAR(255) NOT NULL,
    `customer_phone` VARCHAR(20) NULL,
    `billing_address` TEXT NULL,
    
    -- Company Details
    `company_name` VARCHAR(200) NULL,
    `company_address` TEXT NULL,
    `company_gstin` VARCHAR(15) NULL,
    
    -- Line Items
    `items` JSON NOT NULL COMMENT 'Invoice line items',
    
    -- Status
    `status` ENUM('draft', 'sent', 'paid', 'partially_paid', 'overdue', 'cancelled', 'refunded') DEFAULT 'draft',
    
    -- Files
    `pdf_path` VARCHAR(500) NULL,
    
    -- Dates
    `issue_date` DATE NOT NULL,
    `due_date` DATE NULL,
    `paid_at` TIMESTAMP NULL,
    
    -- Notes
    `notes` TEXT NULL,
    `terms` TEXT NULL,
    
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_invoice_number` (`invoice_number`),
    KEY `idx_user` (`user_id`),
    KEY `idx_transaction` (`transaction_id`),
    KEY `idx_franchise` (`franchise_id`),
    KEY `idx_status` (`status`),
    KEY `idx_issue_date` (`issue_date`),
    CONSTRAINT `fk_invoices_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_invoices_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_invoices_franchise` FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Franchise Commissions
CREATE TABLE `franchise_commissions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `franchise_id` BIGINT UNSIGNED NOT NULL,
    `transaction_id` BIGINT UNSIGNED NOT NULL,
    `document_id` BIGINT UNSIGNED NULL,
    
    -- Commission Details
    `sale_amount` DECIMAL(12, 2) NOT NULL,
    `commission_rate` DECIMAL(5, 2) NOT NULL,
    `commission_amount` DECIMAL(12, 2) NOT NULL,
    
    -- Status
    `status` ENUM('pending', 'approved', 'paid', 'cancelled') DEFAULT 'pending',
    
    -- Payout Details
    `payout_id` BIGINT UNSIGNED NULL,
    `paid_amount` DECIMAL(12, 2) DEFAULT 0.00,
    `paid_at` TIMESTAMP NULL,
    
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    KEY `idx_franchise` (`franchise_id`),
    KEY `idx_transaction` (`transaction_id`),
    KEY `idx_document` (`document_id`),
    KEY `idx_status` (`status`),
    CONSTRAINT `fk_commissions_franchise` FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_commissions_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_commissions_document` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Commission Payouts
CREATE TABLE `commission_payouts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `payout_number` VARCHAR(50) UNIQUE NOT NULL,
    `franchise_id` BIGINT UNSIGNED NOT NULL,
    
    -- Payout Details
    `total_commission` DECIMAL(12, 2) NOT NULL,
    `tds_amount` DECIMAL(12, 2) DEFAULT 0.00,
    `tds_percentage` DECIMAL(5, 2) DEFAULT 0.00,
    `net_payout` DECIMAL(12, 2) NOT NULL,
    `currency` VARCHAR(3) DEFAULT 'INR',
    
    -- Payment Details
    `payment_method` ENUM('bank_transfer', 'cheque', 'upi', 'wallet') NOT NULL,
    `transaction_reference` VARCHAR(200) NULL,
    
    -- Status
    `status` ENUM('pending', 'processing', 'completed', 'failed', 'cancelled') DEFAULT 'pending',
    
    -- Dates
    `period_start` DATE NOT NULL,
    `period_end` DATE NOT NULL,
    `processed_at` TIMESTAMP NULL,
    
    -- Notes
    `notes` TEXT NULL,
    
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_payout_number` (`payout_number`),
    KEY `idx_franchise` (`franchise_id`),
    KEY `idx_status` (`status`),
    CONSTRAINT `fk_payouts_franchise` FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- CONSULTATION TABLES
-- ============================================================================

-- Lawyers/Legal Experts
CREATE TABLE `lawyers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    
    -- Professional Details
    `license_number` VARCHAR(100) UNIQUE NOT NULL,
    `bar_council_name` VARCHAR(200) NOT NULL,
    `bar_council_state` VARCHAR(100) NOT NULL,
    `enrollment_date` DATE NOT NULL,
    `specializations` JSON COMMENT 'Array of specialization areas',
    `practice_areas` JSON COMMENT 'Detailed practice areas',
    
    -- Experience
    `years_of_experience` INT NOT NULL,
    `total_cases` INT DEFAULT 0,
    `cases_won` INT DEFAULT 0,
    `success_rate` DECIMAL(5, 2) DEFAULT 0.00,
    
    -- Availability
    `is_available` BOOLEAN DEFAULT TRUE,
    `consultation_fee` DECIMAL(10, 2) NOT NULL,
    `consultation_duration` INT DEFAULT 30 COMMENT 'Minutes',
    
    -- Ratings & Reviews
    `average_rating` DECIMAL(3, 2) DEFAULT 0.00,
    `total_reviews` INT DEFAULT 0,
    `total_consultations` INT DEFAULT 0,
    
    -- Profile
    `bio` TEXT NULL,
    `education` JSON COMMENT 'Educational qualifications',
    `certifications` JSON NULL,
    `languages` JSON COMMENT 'Languages spoken',
    `courts` JSON COMMENT 'Courts practicing in',
    
    -- Verification
    `is_verified` BOOLEAN DEFAULT FALSE,
    `verification_documents` JSON NULL,
    `verified_at` TIMESTAMP NULL,
    `verified_by` BIGINT UNSIGNED NULL,
    
    -- Location
    `location_id` BIGINT UNSIGNED NULL,
    `office_address` TEXT NULL,
    
    -- Status
    `status` ENUM('pending', 'approved', 'active', 'suspended', 'inactive') DEFAULT 'pending',
    
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_user` (`user_id`),
    UNIQUE KEY `unique_license` (`license_number`),
    KEY `idx_verified` (`is_verified`),
    KEY `idx_available` (`is_available`),
    KEY `idx_status` (`status`),
    KEY `idx_location` (`location_id`),
    CONSTRAINT `fk_lawyers_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_lawyers_verified_by` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_lawyers_location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Lawyer Availability
CREATE TABLE `lawyer_availability` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `lawyer_id` BIGINT UNSIGNED NOT NULL,
    `day_of_week` TINYINT NOT NULL COMMENT '0=Sunday, 6=Saturday',
    `start_time` TIME NOT NULL,
    `end_time` TIME NOT NULL,
    `slot_duration` INT DEFAULT 30 COMMENT 'Minutes',
    `is_active` BOOLEAN DEFAULT TRUE,
    
    PRIMARY KEY (`id`),
    KEY `idx_lawyer` (`lawyer_id`),
    KEY `idx_day` (`day_of_week`),
    KEY `idx_active` (`is_active`),
    CONSTRAINT `fk_lawyer_avail_lawyer` FOREIGN KEY (`lawyer_id`) REFERENCES `lawyers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Lawyer Blocked Slots
CREATE TABLE `lawyer_blocked_slots` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `lawyer_id` BIGINT UNSIGNED NOT NULL,
    `blocked_date` DATE NOT NULL,
    `start_time` TIME NULL,
    `end_time` TIME NULL,
    `reason` VARCHAR(255) NULL,
    `is_full_day` BOOLEAN DEFAULT FALSE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    KEY `idx_lawyer` (`lawyer_id`),
    KEY `idx_date` (`blocked_date`),
    CONSTRAINT `fk_blocked_slots_lawyer` FOREIGN KEY (`lawyer_id`) REFERENCES `lawyers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Consultations
CREATE TABLE `consultations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `consultation_number` VARCHAR(50) UNIQUE NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `lawyer_id` BIGINT UNSIGNED NOT NULL,
    
    -- Consultation Details
    `consultation_type` ENUM('chat', 'voice', 'video', 'in_person') NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,
    `category` VARCHAR(100) NULL,
    
    -- Scheduling
    `scheduled_date` DATE NOT NULL,
    `scheduled_time` TIME NOT NULL,
    `scheduled_at` DATETIME GENERATED ALWAYS AS (CONCAT(scheduled_date, ' ', scheduled_time)) STORED,
    `duration_minutes` INT DEFAULT 30,
    `timezone` VARCHAR(50) DEFAULT 'Asia/Kolkata',
    
    -- Status
    `status` ENUM('scheduled', 'confirmed', 'in_progress', 'completed', 'cancelled', 'no_show', 'rescheduled') DEFAULT 'scheduled',
    `cancellation_reason` TEXT NULL,
    `cancelled_by` ENUM('user', 'lawyer', 'admin') NULL,
    
    -- Meeting Details
    `meeting_link` VARCHAR(500) NULL,
    `meeting_id` VARCHAR(100) NULL,
    `meeting_password` VARCHAR(50) NULL,
    `meeting_platform` VARCHAR(50) NULL COMMENT 'zoom, google_meet, etc.',
    
    -- Payment
    `fee` DECIMAL(10, 2) NOT NULL,
    `currency` VARCHAR(3) DEFAULT 'INR',
    `payment_status` ENUM('pending', 'paid', 'refunded', 'failed') DEFAULT 'pending',
    `transaction_id` BIGINT UNSIGNED NULL,
    
    -- Follow-up
    `follow_up_required` BOOLEAN DEFAULT FALSE,
    `follow_up_date` DATE NULL,
    `follow_up_notes` TEXT NULL,
    
    -- Summary & Notes
    `lawyer_notes` TEXT NULL,
    `user_notes` TEXT NULL,
    `consultation_summary` TEXT NULL,
    `attachments` JSON NULL,
    
    -- Rating & Review
    `rating` TINYINT NULL CHECK (`rating` >= 1 AND `rating` <= 5),
    `review` TEXT NULL,
    `reviewed_at` TIMESTAMP NULL,
    
    -- Timestamps
    `started_at` TIMESTAMP NULL,
    `completed_at` TIMESTAMP NULL,
    `cancelled_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_consultation_number` (`consultation_number`),
    KEY `idx_user` (`user_id`),
    KEY `idx_lawyer` (`lawyer_id`),
    KEY `idx_status` (`status`),
    KEY `idx_scheduled_at` (`scheduled_at`),
    KEY `idx_transaction` (`transaction_id`),
    CONSTRAINT `fk_consultations_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_consultations_lawyer` FOREIGN KEY (`lawyer_id`) REFERENCES `lawyers` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_consultations_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- NOTIFICATION TABLES
-- ============================================================================

-- Notifications
CREATE TABLE `notifications` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    
    -- Notification Details
    `type` VARCHAR(50) NOT NULL COMMENT 'document_created, payment_success, etc.',
    `title` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `icon` VARCHAR(100) NULL,
    
    -- Related Entity
    `entity_type` VARCHAR(50) NULL COMMENT 'document, consultation, payment, etc.',
    `entity_id` BIGINT UNSIGNED NULL,
    
    -- Action
    `action_url` VARCHAR(500) NULL,
    `action_text` VARCHAR(100) NULL,
    
    -- Status
    `is_read` BOOLEAN DEFAULT FALSE,
    `read_at` TIMESTAMP NULL,
    
    -- Delivery Channels
    `send_email` BOOLEAN DEFAULT TRUE,
    `email_sent` BOOLEAN DEFAULT FALSE,
    `email_sent_at` TIMESTAMP NULL,
    
    `send_sms` BOOLEAN DEFAULT FALSE,
    `sms_sent` BOOLEAN DEFAULT FALSE,
    `sms_sent_at` TIMESTAMP NULL,
    
    `send_push` BOOLEAN DEFAULT TRUE,
    `push_sent` BOOLEAN DEFAULT FALSE,
    `push_sent_at` TIMESTAMP NULL,
    
    -- Priority
    `priority` ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    
    -- Data
    `data` JSON NULL,
    
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `expires_at` TIMESTAMP NULL,
    
    PRIMARY KEY (`id`),
    KEY `idx_user` (`user_id`),
    KEY `idx_read` (`is_read`),
    KEY `idx_type` (`type`),
    KEY `idx_created` (`created_at`),
    KEY `idx_entity` (`entity_type`, `entity_id`),
    CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Notification Preferences
CREATE TABLE `notification_preferences` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    
    -- Global Channel Preferences
    `email_enabled` BOOLEAN DEFAULT TRUE,
    `sms_enabled` BOOLEAN DEFAULT FALSE,
    `push_enabled` BOOLEAN DEFAULT TRUE,
    
    -- Event Preferences (JSON structure)
    `document_notifications` JSON NULL,
    `payment_notifications` JSON NULL,
    `consultation_notifications` JSON NULL,
    `marketing_notifications` JSON NULL,
    `system_notifications` JSON NULL,
    
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_user_preferences` (`user_id`),
    CONSTRAINT `fk_notif_prefs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- COMMUNICATION TABLES
-- ============================================================================

-- Email Queue
CREATE TABLE `email_queue` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `recipient_email` VARCHAR(255) NOT NULL,
    `recipient_name` VARCHAR(200) NULL,
    `cc` VARCHAR(500) NULL,
    `bcc` VARCHAR(500) NULL,
    
    -- Email Details
    `subject` VARCHAR(255) NOT NULL,
    `body` LONGTEXT NOT NULL,
    `template_name` VARCHAR(100) NULL,
    `template_data` JSON NULL,
    
    -- Attachments
    `attachments` JSON NULL COMMENT 'Array of file paths',
    
    -- Priority
    `priority` ENUM('low', 'medium', 'high') DEFAULT 'medium',
    
    -- Status
    `status` ENUM('pending', 'sending', 'sent', 'failed') DEFAULT 'pending',
    `attempts` INT DEFAULT 0,
    `max_attempts` INT DEFAULT 3,
    
    -- Error Handling
    `error_message` TEXT NULL,
    `last_error_at` TIMESTAMP NULL,
    
    -- Tracking
    `message_id` VARCHAR(255) NULL,
    `opened` BOOLEAN DEFAULT FALSE,
    `opened_at` TIMESTAMP NULL,
    `clicked` BOOLEAN DEFAULT FALSE,
    `clicked_at` TIMESTAMP NULL,
    
    -- Timestamps
    `scheduled_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `sent_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    KEY `idx_status` (`status`),
    KEY `idx_scheduled` (`scheduled_at`),
    KEY `idx_recipient` (`recipient_email`),
    KEY `idx_priority` (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SMS Queue
CREATE TABLE `sms_queue` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `phone_number` VARCHAR(20) NOT NULL,
    
    -- SMS Details
    `message` TEXT NOT NULL,
    `template_name` VARCHAR(100) NULL,
    `template_variables` JSON NULL,
    
    -- Status
    `status` ENUM('pending', 'sending', 'sent', 'failed') DEFAULT 'pending',
    `attempts` INT DEFAULT 0,
    `max_attempts` INT DEFAULT 3,
    
    -- Provider
    `provider` VARCHAR(50) NULL COMMENT 'twilio, msg91, etc.',
    `provider_message_id` VARCHAR(100) NULL,
    `provider_response` JSON NULL,
    
    -- Error Handling
    `error_message` TEXT NULL,
    `last_error_at` TIMESTAMP NULL,
    
    -- Timestamps
    `scheduled_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `sent_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    KEY `idx_status` (`status`),
    KEY `idx_scheduled` (`scheduled_at`),
    KEY `idx_phone` (`phone_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- SUPPORT & KNOWLEDGE BASE TABLES
-- ============================================================================

-- Support Tickets
CREATE TABLE `support_tickets` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ticket_number` VARCHAR(50) UNIQUE NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `assigned_to` BIGINT UNSIGNED NULL,
    
    -- Ticket Details
    `subject` VARCHAR(200) NOT NULL,
    `description` TEXT NOT NULL,
    `category` VARCHAR(50) NULL COMMENT 'technical, billing, general',
    `department` VARCHAR(50) NULL,
    `priority` ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    
    -- Status
    `status` ENUM('open', 'in_progress', 'waiting_customer', 'waiting_internal', 'resolved', 'closed', 'reopened') DEFAULT 'open',
    
    -- Attachments
    `attachments` JSON NULL,
    
    -- SLA
    `first_response_due` TIMESTAMP NULL,
    `first_responded_at` TIMESTAMP NULL,
    `resolution_due` TIMESTAMP NULL,
    
    -- Ratings
    `rating` TINYINT NULL CHECK (`rating` >= 1 AND `rating` <= 5),
    `feedback` TEXT NULL,
    `feedback_at` TIMESTAMP NULL,
    
    -- Timestamps
    `resolved_at` TIMESTAMP NULL,
    `closed_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_ticket_number` (`ticket_number`),
    KEY `idx_user` (`user_id`),
    KEY `idx_assigned` (`assigned_to`),
    KEY `idx_status` (`status`),
    KEY `idx_priority` (`priority`),
    KEY `idx_category` (`category`),
    CONSTRAINT `fk_tickets_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_tickets_assigned` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ticket Replies
CREATE TABLE `ticket_replies` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ticket_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    
    `message` TEXT NOT NULL,
    `is_internal` BOOLEAN DEFAULT FALSE,
    `is_system` BOOLEAN DEFAULT FALSE,
    
    -- Attachments
    `attachments` JSON NULL,
    
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    KEY `idx_ticket` (`ticket_id`),
    KEY `idx_user` (`user_id`),
    KEY `idx_created` (`created_at`),
    CONSTRAINT `fk_ticket_replies_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_ticket_replies_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Knowledge Base Articles
CREATE TABLE `kb_articles` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `category` VARCHAR(100) NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(250) UNIQUE NOT NULL,
    `content` LONGTEXT NOT NULL,
    `excerpt` TEXT NULL,
    
    -- Metadata
    `view_count` INT DEFAULT 0,
    `helpful_count` INT DEFAULT 0,
    `not_helpful_count` INT DEFAULT 0,
    
    -- Status
    `status` ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    `is_featured` BOOLEAN DEFAULT FALSE,
    
    -- SEO
    `meta_title` VARCHAR(200) NULL,
    `meta_description` TEXT NULL,
    `meta_keywords` TEXT NULL,
    
    -- Tags
    `tags` JSON NULL,
    
    -- Author
    `author_id` BIGINT UNSIGNED NULL,
    
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `published_at` TIMESTAMP NULL,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_slug` (`slug`),
    KEY `idx_category` (`category`),
    KEY `idx_status` (`status`),
    KEY `idx_author` (`author_id`),
    FULLTEXT KEY `idx_search` (`title`, `content`),
    CONSTRAINT `fk_kb_articles_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- MISCELLANEOUS TABLES
-- ============================================================================

-- Coupons
CREATE TABLE `coupons` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(50) UNIQUE NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    
    -- Discount Details
    `discount_type` ENUM('percentage', 'fixed', 'free_shipping') NOT NULL,
    `discount_value` DECIMAL(10, 2) NOT NULL,
    `max_discount_amount` DECIMAL(10, 2) NULL,
    `min_purchase_amount` DECIMAL(10, 2) DEFAULT 0.00,
    
    -- Usage Limits
    `usage_limit` INT DEFAULT -1 COMMENT '-1 for unlimited',
    `usage_count` INT DEFAULT 0,
    `per_user_limit` INT DEFAULT 1,
    
    -- Applicable To
    `applicable_to` JSON NULL COMMENT 'document_templates, subscriptions, consultations',
    `applicable_users` JSON NULL COMMENT 'Array of user IDs or "all"',
    
    -- Restrictions
    `first_purchase_only` BOOLEAN DEFAULT FALSE,
    `minimum_subscription_months` INT NULL,
    
    -- Status
    `is_active` BOOLEAN DEFAULT TRUE,
    
    -- Validity
    `valid_from` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `valid_until` TIMESTAMP NULL,
    
    `created_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_code` (`code`),
    KEY `idx_active` (`is_active`),
    KEY `idx_validity` (`valid_from`, `valid_until`),
    KEY `idx_created_by` (`created_by`),
    CONSTRAINT `fk_coupons_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Coupon Usage
CREATE TABLE `coupon_usage` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `coupon_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `transaction_id` BIGINT UNSIGNED NULL,
    
    `discount_applied` DECIMAL(10, 2) NOT NULL,
    
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    KEY `idx_coupon` (`coupon_id`),
    KEY `idx_user` (`user_id`),
    KEY `idx_transaction` (`transaction_id`),
    CONSTRAINT `fk_coupon_usage_coupon` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_coupon_usage_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_coupon_usage_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Referrals
CREATE TABLE `referrals` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `referrer_id` BIGINT UNSIGNED NOT NULL,
    `referred_email` VARCHAR(255) NOT NULL,
    `referred_user_id` BIGINT UNSIGNED NULL,
    
    `referral_code` VARCHAR(50) UNIQUE NOT NULL,
    
    -- Status
    `status` ENUM('pending', 'registered', 'completed', 'expired') DEFAULT 'pending',
    
    -- Rewards
    `referrer_reward_type` ENUM('credit', 'discount', 'commission') DEFAULT 'credit',
    `referrer_reward_amount` DECIMAL(10, 2) DEFAULT 0.00,
    `referred_reward_type` ENUM('credit', 'discount', 'free_document') DEFAULT 'discount',
    `referred_reward_amount` DECIMAL(10, 2) DEFAULT 0.00,
    `rewards_paid` BOOLEAN DEFAULT FALSE,
    `rewards_paid_at` TIMESTAMP NULL,
    
    -- Timestamps
    `registered_at` TIMESTAMP NULL,
    `completed_at` TIMESTAMP NULL,
    `expires_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_referral_code` (`referral_code`),
    KEY `idx_referrer` (`referrer_id`),
    KEY `idx_referred_user` (`referred_user_id`),
    KEY `idx_status` (`status`),
    KEY `idx_email` (`referred_email`),
    CONSTRAINT `fk_referrals_referrer` FOREIGN KEY (`referrer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_referrals_referred` FOREIGN KEY (`referred_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Reviews & Ratings
CREATE TABLE `reviews` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    
    -- Reviewable Entity (polymorphic)
    `reviewable_type` VARCHAR(50) NOT NULL COMMENT 'template, lawyer, franchise',
    `reviewable_id` BIGINT UNSIGNED NOT NULL,
    
    -- Review Details
    `rating` TINYINT NOT NULL CHECK (`rating` >= 1 AND `rating` <= 5),
    `title` VARCHAR(200) NULL,
    `comment` TEXT NULL,
    
    -- Moderation
    `is_approved` BOOLEAN DEFAULT FALSE,
    `is_featured` BOOLEAN DEFAULT FALSE,
    `is_verified_purchase` BOOLEAN DEFAULT FALSE,
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` TIMESTAMP NULL,
    
    -- Engagement
    `helpful_count` INT DEFAULT 0,
    `not_helpful_count` INT DEFAULT 0,
    
    -- Response
    `response` TEXT NULL,
    `responded_by` BIGINT UNSIGNED NULL,
    `responded_at` TIMESTAMP NULL,
    
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    KEY `idx_user` (`user_id`),
    KEY `idx_reviewable` (`reviewable_type`, `reviewable_id`),
    KEY `idx_rating` (`rating`),
    KEY `idx_approved` (`is_approved`),
    CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_reviews_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_reviews_responded_by` FOREIGN KEY (`responded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- File Uploads
CREATE TABLE `file_uploads` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    
    -- File Details
    `original_filename` VARCHAR(255) NOT NULL,
    `stored_filename` VARCHAR(255) NOT NULL,
    `file_path` VARCHAR(500) NOT NULL,
    `file_size` BIGINT NOT NULL COMMENT 'Bytes',
    `mime_type` VARCHAR(100) NOT NULL,
    `file_extension` VARCHAR(10) NOT NULL,
    `file_hash` VARCHAR(64) NOT NULL,
    
    -- Classification
    `entity_type` VARCHAR(50) NULL,
    `entity_id` BIGINT UNSIGNED NULL,
    `category` VARCHAR(50) NULL,
    
    -- Storage
    `storage_provider` VARCHAR(50) DEFAULT 'local' COMMENT 'local, s3, cloudinary',
    `storage_path` VARCHAR(500) NULL,
    `storage_url` VARCHAR(500) NULL,
    
    -- Status
    `is_temporary` BOOLEAN DEFAULT FALSE,
    `is_public` BOOLEAN DEFAULT FALSE,
    
    -- Virus Scan
    `virus_scanned` BOOLEAN DEFAULT FALSE,
    `scan_result` VARCHAR(50) NULL,
    `scanned_at` TIMESTAMP NULL,
    
    -- Access Control
    `download_count` INT DEFAULT 0,
    `last_accessed_at` TIMESTAMP NULL,
    
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `expires_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    
    PRIMARY KEY (`id`),
    KEY `idx_user` (`user_id`),
    KEY `idx_entity` (`entity_type`, `entity_id`),
    KEY `idx_hash` (`file_hash`),
    KEY `idx_temporary` (`is_temporary`),
    CONSTRAINT `fk_file_uploads_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- System Settings
CREATE TABLE `system_settings` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `setting_key` VARCHAR(100) UNIQUE NOT NULL,
    `setting_value` TEXT NULL,
    `setting_type` ENUM('string', 'number', 'boolean', 'json', 'text') DEFAULT 'string',
    `category` VARCHAR(50) DEFAULT 'general',
    `description` TEXT NULL,
    `is_public` BOOLEAN DEFAULT FALSE,
    `is_editable` BOOLEAN DEFAULT TRUE,
    
    `updated_by` BIGINT UNSIGNED NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_key` (`setting_key`),
    KEY `idx_category` (`category`),
    CONSTRAINT `fk_settings_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- AUDIT & LOGGING TABLES
-- ============================================================================

-- Audit Logs
CREATE TABLE `audit_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NULL,
    
    -- Action Details
    `action` VARCHAR(100) NOT NULL COMMENT 'created, updated, deleted, etc.',
    `entity_type` VARCHAR(50) NOT NULL,
    `entity_id` BIGINT UNSIGNED NULL,
    
    -- Changes
    `old_values` JSON NULL,
    `new_values` JSON NULL,
    
    -- Request Information
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `request_method` VARCHAR(10) NULL,
    `request_url` VARCHAR(500) NULL,
    
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    KEY `idx_user` (`user_id`),
    KEY `idx_entity` (`entity_type`, `entity_id`),
    KEY `idx_action` (`action`),
    KEY `idx_created` (`created_at`),
    CONSTRAINT `fk_audit_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Activity Logs
CREATE TABLE `activity_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    
    `activity_type` VARCHAR(50) NOT NULL,
    `description` VARCHAR(500) NOT NULL,
    `metadata` JSON NULL,
    
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    KEY `idx_user` (`user_id`),
    KEY `idx_type` (`activity_type`),
    KEY `idx_created` (`created_at`),
    CONSTRAINT `fk_activity_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Error Logs
CREATE TABLE `error_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NULL,
    
    -- Error Details
    `error_type` VARCHAR(100) NOT NULL,
    `error_message` TEXT NOT NULL,
    `error_code` VARCHAR(50) NULL,
    `stack_trace` TEXT NULL,
    `file` VARCHAR(500) NULL,
    `line` INT NULL,
    
    -- Context
    `request_url` VARCHAR(500) NULL,
    `request_method` VARCHAR(10) NULL,
    `request_data` JSON NULL,
    
    -- Environment
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `server_name` VARCHAR(100) NULL,
    
    -- Status
    `is_resolved` BOOLEAN DEFAULT FALSE,
    `resolved_at` TIMESTAMP NULL,
    `resolved_by` BIGINT UNSIGNED NULL,
    `resolution_notes` TEXT NULL,
    
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    KEY `idx_user` (`user_id`),
    KEY `idx_type` (`error_type`),
    KEY `idx_resolved` (`is_resolved`),
    KEY `idx_created` (`created_at`),
    CONSTRAINT `fk_error_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_error_logs_resolved_by` FOREIGN KEY (`resolved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- SESSION & SECURITY TABLES
-- ============================================================================

-- User Sessions
CREATE TABLE `user_sessions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `session_token` VARCHAR(255) UNIQUE NOT NULL,
    `session_data` TEXT NULL,
    
    -- Session Details
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `device_type` VARCHAR(50) NULL,
    `device_name` VARCHAR(100) NULL,
    `browser` VARCHAR(50) NULL,
    `platform` VARCHAR(50) NULL,
    
    -- Location
    `country` VARCHAR(100) NULL,
    `city` VARCHAR(100) NULL,
    `latitude` DECIMAL(10, 8) NULL,
    `longitude` DECIMAL(11, 8) NULL,
    
    -- Status
    `is_active` BOOLEAN DEFAULT TRUE,
    
    -- Timestamps
    `last_activity_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `expires_at` TIMESTAMP NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_session_token` (`session_token`),
    KEY `idx_user` (`user_id`),
    KEY `idx_active` (`is_active`),
    KEY `idx_expires` (`expires_at`),
    CONSTRAINT `fk_sessions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Password Reset Tokens
CREATE TABLE `password_reset_tokens` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) UNIQUE NOT NULL,
    
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    
    `used` BOOLEAN DEFAULT FALSE,
    `used_at` TIMESTAMP NULL,
    
    `expires_at` TIMESTAMP NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_token` (`token`),
    KEY `idx_user` (`user_id`),
    KEY `idx_email` (`email`),
    CONSTRAINT `fk_email_verify_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Search History
CREATE TABLE `search_history` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NULL,
    
    `search_query` VARCHAR(500) NOT NULL,
    `search_type` VARCHAR(50) NULL,
    `filters` JSON NULL,
    `results_count` INT DEFAULT 0,
    
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    KEY `idx_user` (`user_id`),
    KEY `idx_created` (`created_at`),
    FULLTEXT KEY `idx_query` (`search_query`),
    CONSTRAINT `fk_search_history_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- STORED PROCEDURES
-- ============================================================================

DELIMITER //

-- Update Document Statistics
CREATE PROCEDURE `sp_update_document_stats`(IN p_document_id BIGINT)
BEGIN
    -- Update signature count
    UPDATE `documents` 
    SET `completed_signatures` = (
        SELECT COUNT(*) 
        FROM `signatures` 
        WHERE `document_id` = p_document_id
    )
    WHERE `id` = p_document_id;
    
    -- Check if all signatures are completed
    UPDATE `documents` d
    SET `all_signed` = (
        d.`required_signatures` > 0 AND 
        d.`completed_signatures` >= d.`required_signatures`
    )
    WHERE d.`id` = p_document_id;
END //

-- Update Template Usage
CREATE PROCEDURE `sp_update_template_usage`(IN p_template_id BIGINT)
BEGIN
    UPDATE `document_templates` 
    SET 
        `usage_count` = (
            SELECT COUNT(*) 
            FROM `documents` 
            WHERE `template_id` = p_template_id
        ),
        `view_count` = `view_count` + 1
    WHERE `id` = p_template_id;
END //

-- Update User Subscription Usage
CREATE PROCEDURE `sp_update_subscription_usage`(IN p_user_id BIGINT)
BEGIN
    UPDATE `user_subscriptions` us
    SET 
        `documents_used` = (
            SELECT COUNT(*) 
            FROM `documents` 
            WHERE `user_id` = p_user_id 
            AND `status` = 'completed'
            AND `created_at` >= us.`current_period_start`
        ),
        `storage_used` = (
            SELECT COALESCE(SUM(`file_size`), 0)
            FROM `documents` 
            WHERE `user_id` = p_user_id 
            AND `deleted_at` IS NULL
        )
    WHERE `user_id` = p_user_id 
    AND `status` IN ('trial', 'active');
END //

-- Update Lawyer Rating
CREATE PROCEDURE `sp_update_lawyer_rating`(IN p_lawyer_id BIGINT)
BEGIN
    UPDATE `lawyers` 
    SET 
        `average_rating` = (
            SELECT COALESCE(AVG(`rating`), 0)
            FROM `consultations` 
            WHERE `lawyer_id` = p_lawyer_id 
            AND `rating` IS NOT NULL
        ),
        `total_reviews` = (
            SELECT COUNT(*) 
            FROM `consultations` 
            WHERE `lawyer_id` = p_lawyer_id 
            AND `rating` IS NOT NULL
        ),
        `total_consultations` = (
            SELECT COUNT(*) 
            FROM `consultations` 
            WHERE `lawyer_id` = p_lawyer_id 
            AND `status` = 'completed'
        )
    WHERE `id` = p_lawyer_id;
END //

-- Update Franchise Statistics
CREATE PROCEDURE `sp_update_franchise_stats`(IN p_franchise_id BIGINT)
BEGIN
    UPDATE `franchises` f
    SET 
        `total_documents` = (
            SELECT COUNT(*) 
            FROM `documents` 
            WHERE `franchise_id` = p_franchise_id
        ),
        `total_customers` = (
            SELECT COUNT(DISTINCT `user_id`) 
            FROM `documents` 
            WHERE `franchise_id` = p_franchise_id
        ),
        `total_revenue` = (
            SELECT COALESCE(SUM(t.`amount`), 0)
            FROM `transactions` t
            WHERE t.`franchise_id` = p_franchise_id
            AND t.`status` = 'completed'
        ),
        `total_commission` = (
            SELECT COALESCE(SUM(`commission_amount`), 0)
            FROM `franchise_commissions`
            WHERE `franchise_id` = p_franchise_id
            AND `status` IN ('approved', 'paid')
        )
    WHERE `id` = p_franchise_id;
END //

-- Generate Document Number
CREATE PROCEDURE `sp_generate_document_number`(OUT p_document_number VARCHAR(100))
BEGIN
    DECLARE v_year VARCHAR(4);
    DECLARE v_month VARCHAR(2);
    DECLARE v_sequence INT;
    
    SET v_year = YEAR(NOW());
    SET v_month = LPAD(MONTH(NOW()), 2, '0');
    
    SELECT COALESCE(MAX(CAST(SUBSTRING(`document_number`, -6) AS UNSIGNED)), 0) + 1
    INTO v_sequence
    FROM `documents`
    WHERE `document_number` LIKE CONCAT('DOC', v_year, v_month, '%');
    
    SET p_document_number = CONCAT('DOC', v_year, v_month, LPAD(v_sequence, 6, '0'));
END //

-- Generate Invoice Number
CREATE PROCEDURE `sp_generate_invoice_number`(OUT p_invoice_number VARCHAR(50))
BEGIN
    DECLARE v_year VARCHAR(4);
    DECLARE v_month VARCHAR(2);
    DECLARE v_sequence INT;
    
    SET v_year = YEAR(NOW());
    SET v_month = LPAD(MONTH(NOW()), 2, '0');
    
    SELECT COALESCE(MAX(CAST(SUBSTRING(`invoice_number`, -6) AS UNSIGNED)), 0) + 1
    INTO v_sequence
    FROM `invoices`
    WHERE `invoice_number` LIKE CONCAT('INV', v_year, v_month, '%');
    
    SET p_invoice_number = CONCAT('INV', v_year, v_month, LPAD(v_sequence, 6, '0'));
END //

-- Calculate Commission
CREATE PROCEDURE `sp_calculate_commission`(
    IN p_franchise_id BIGINT,
    IN p_transaction_id BIGINT
)
BEGIN
    DECLARE v_sale_amount DECIMAL(12,2);
    DECLARE v_commission_rate DECIMAL(5,2);
    DECLARE v_commission_amount DECIMAL(12,2);
    
    -- Get franchise commission rate
    SELECT `commission_rate` INTO v_commission_rate
    FROM `franchises`
    WHERE `id` = p_franchise_id;
    
    -- Get transaction amount
    SELECT `amount` INTO v_sale_amount
    FROM `transactions`
    WHERE `id` = p_transaction_id;
    
    -- Calculate commission
    SET v_commission_amount = (v_sale_amount * v_commission_rate / 100);
    
    -- Insert commission record
    INSERT INTO `franchise_commissions` (
        `franchise_id`, 
        `transaction_id`, 
        `sale_amount`, 
        `commission_rate`, 
        `commission_amount`
    ) VALUES (
        p_franchise_id, 
        p_transaction_id, 
        v_sale_amount, 
        v_commission_rate, 
        v_commission_amount
    );
END //

DELIMITER ;

-- ============================================================================
-- TRIGGERS
-- ============================================================================

DELIMITER //

-- Auto-generate UUID for users
CREATE TRIGGER `tr_users_before_insert` 
BEFORE INSERT ON `users` 
FOR EACH ROW
BEGIN
    IF NEW.uuid IS NULL OR NEW.uuid = '' THEN
        SET NEW.uuid = UUID();
    END IF;
    IF NEW.referral_code IS NULL OR NEW.referral_code = '' THEN
        SET NEW.referral_code = UPPER(CONCAT(
            LEFT(NEW.first_name, 2),
            LEFT(NEW.last_name, 2),
            LPAD(FLOOR(RAND() * 10000), 4, '0')
        ));
    END IF;
END //

-- Auto-generate UUID for documents
CREATE TRIGGER `tr_documents_before_insert` 
BEFORE INSERT ON `documents` 
FOR EACH ROW
BEGIN
    IF NEW.uuid IS NULL OR NEW.uuid = '' THEN
        SET NEW.uuid = UUID();
    END IF;
    IF NEW.document_number IS NULL OR NEW.document_number = '' THEN
        CALL sp_generate_document_number(@doc_number);
        SET NEW.document_number = @doc_number;
    END IF;
END //

-- Auto-generate UUID for templates
CREATE TRIGGER `tr_templates_before_insert` 
BEFORE INSERT ON `document_templates` 
FOR EACH ROW
BEGIN
    IF NEW.uuid IS NULL OR NEW.uuid = '' THEN
        SET NEW.uuid = UUID();
    END IF;
END //

-- Update template usage when document is created
CREATE TRIGGER `tr_documents_after_insert` 
AFTER INSERT ON `documents` 
FOR EACH ROW
BEGIN
    IF NEW.template_id IS NOT NULL THEN
        UPDATE `document_templates` 
        SET `usage_count` = `usage_count` + 1 
        WHERE `id` = NEW.template_id;
    END IF;
    
    -- Update subscription usage
    CALL sp_update_subscription_usage(NEW.user_id);
    
    -- Update franchise stats if applicable
    IF NEW.franchise_id IS NOT NULL THEN
        CALL sp_update_franchise_stats(NEW.franchise_id);
    END IF;
END //

-- Update document stats after signature
CREATE TRIGGER `tr_signatures_after_insert` 
AFTER INSERT ON `signatures` 
FOR EACH ROW
BEGIN
    CALL sp_update_document_stats(NEW.document_id);
END //

-- Update lawyer rating after consultation rating
CREATE TRIGGER `tr_consultations_after_update` 
AFTER UPDATE ON `consultations` 
FOR EACH ROW
BEGIN
    IF NEW.rating IS NOT NULL AND (OLD.rating IS NULL OR OLD.rating != NEW.rating) THEN
        CALL sp_update_lawyer_rating(NEW.lawyer_id);
    END IF;
END //

-- Calculate commission after transaction completion
CREATE TRIGGER `tr_transactions_after_update` 
AFTER UPDATE ON `transactions` 
FOR EACH ROW
BEGIN
    IF NEW.status = 'completed' AND OLD.status != 'completed' AND NEW.franchise_id IS NOT NULL THEN
        CALL sp_calculate_commission(NEW.franchise_id, NEW.id);
    END IF;
END //

-- Update coupon usage count
CREATE TRIGGER `tr_coupon_usage_after_insert` 
AFTER INSERT ON `coupon_usage` 
FOR EACH ROW
BEGIN
    UPDATE `coupons` 
    SET `usage_count` = `usage_count` + 1 
    WHERE `id` = NEW.coupon_id;
END //

-- Log user activity on login
CREATE TRIGGER `tr_users_after_update_login` 
AFTER UPDATE ON `users` 
FOR EACH ROW
BEGIN
    IF NEW.last_login_at != OLD.last_login_at THEN
        INSERT INTO `activity_logs` (
            `user_id`, 
            `activity_type`, 
            `description`, 
            `ip_address`
        ) VALUES (
            NEW.id, 
            'login', 
            'User logged in', 
            NEW.last_login_ip
        );
    END IF;
END //

-- Update franchise stats when status changes
CREATE TRIGGER `tr_franchises_after_update` 
AFTER UPDATE ON `franchises` 
FOR EACH ROW
BEGIN
    IF NEW.status != OLD.status THEN
        INSERT INTO `activity_logs` (
            `user_id`, 
            `activity_type`, 
            `description`,
            `metadata`
        ) VALUES (
            NEW.user_id, 
            'franchise_status_change', 
            CONCAT('Franchise status changed from ', OLD.status, ' to ', NEW.status),
            JSON_OBJECT('franchise_id', NEW.id, 'old_status', OLD.status, 'new_status', NEW.status)
        );
    END IF;
END //

DELIMITER ;

-- ============================================================================
-- INDEXES FOR PERFORMANCE OPTIMIZATION
-- ============================================================================

-- Composite indexes for common queries
CREATE INDEX `idx_documents_user_status_created` ON `documents`(`user_id`, `status`, `created_at`);
CREATE INDEX `idx_transactions_user_status_created` ON `transactions`(`user_id`, `status`, `created_at`);
CREATE INDEX `idx_consultations_lawyer_status_scheduled` ON `consultations`(`lawyer_id`, `status`, `scheduled_at`);
CREATE INDEX `idx_notifications_user_read_created` ON `notifications`(`user_id`, `is_read`, `created_at`);
CREATE INDEX `idx_franchise_location_status` ON `franchises`(`location_id`, `status`);

-- ============================================================================
-- VIEWS FOR REPORTING AND MIS DASHBOARD
-- ============================================================================

-- Active Subscriptions View
CREATE VIEW `v_active_subscriptions` AS
SELECT 
    us.`id`,
    us.`subscription_number`,
    us.`user_id`,
    u.`email`,
    u.`full_name`,
    sp.`name` as `plan_name`,
    sp.`billing_cycle`,
    us.`status`,
    us.`documents_used`,
    sp.`document_limit`,
    us.`storage_used`,
    sp.`storage_limit`,
    us.`current_period_start`,
    us.`current_period_end`,
    us.`auto_renew`
FROM `user_subscriptions` us
INNER JOIN `users` u ON us.`user_id` = u.`id`
INNER JOIN `subscription_plans` sp ON us.`plan_id` = sp.`id`
WHERE us.`status` IN ('trial', 'active') 
AND us.`expires_at` > NOW();

-- Document Statistics View
CREATE VIEW `v_document_statistics` AS
SELECT 
    d.`id`,
    d.`uuid`,
    d.`document_number`,
    d.`title`,
    d.`status`,
    u.`email` as `user_email`,
    u.`full_name` as `user_name`,
    dt.`name` as `template_name`,
    f.`business_name` as `franchise_name`,
    l.`name` as `location_name`,
    d.`total_amount`,
    d.`completed_signatures`,
    d.`required_signatures`,
    d.`created_at`,
    d.`completed_at`,
    DATEDIFF(d.`completed_at`, d.`created_at`) as `days_to_complete`
FROM `documents` d
INNER JOIN `users` u ON d.`user_id` = u.`id`
LEFT JOIN `document_templates` dt ON d.`template_id` = dt.`id`
LEFT JOIN `franchises` f ON d.`franchise_id` = f.`id`
LEFT JOIN `locations` l ON d.`location_id` = l.`id`
WHERE d.`deleted_at` IS NULL;

-- Franchise Performance View
CREATE VIEW `v_franchise_performance` AS
SELECT 
    f.`id`,
    f.`franchise_code`,
    f.`business_name`,
    u.`full_name` as `owner_name`,
    l.`name` as `location_name`,
    f.`status`,
    f.`total_documents`,
    f.`total_customers`,
    f.`total_revenue`,
    f.`total_commission`,
    f.`commission_rate`,
    (f.`total_revenue` - f.`total_commission`) as `net_revenue`,
    f.`rating`,
    DATEDIFF(NOW(), f.`agreement_start_date`) as `days_active`,
    (f.`total_revenue` / NULLIF(DATEDIFF(NOW(), f.`agreement_start_date`), 0)) as `avg_daily_revenue`
FROM `franchises` f
INNER JOIN `users` u ON f.`user_id` = u.`id`
INNER JOIN `locations` l ON f.`location_id` = l.`id`;

-- Lawyer Performance View
CREATE VIEW `v_lawyer_performance` AS
SELECT 
    l.`id`,
    u.`full_name` as `lawyer_name`,
    u.`email`,
    l.`license_number`,
    l.`bar_council_name`,
    l.`years_of_experience`,
    l.`average_rating`,
    l.`total_reviews`,
    l.`total_consultations`,
    COUNT(c.`id`) as `total_bookings`,
    SUM(CASE WHEN c.`status` = 'completed' THEN 1 ELSE 0 END) as `completed_consultations`,
    SUM(CASE WHEN c.`status` = 'cancelled' THEN 1 ELSE 0 END) as `cancelled_consultations`,
    SUM(CASE WHEN c.`status` = 'completed' THEN c.`fee` ELSE 0 END) as `total_earnings`,
    l.`is_available`,
    l.`consultation_fee`
FROM `lawyers` l
INNER JOIN `users` u ON l.`user_id` = u.`id`
LEFT JOIN `consultations` c ON l.`id` = c.`lawyer_id`
WHERE l.`status` = 'active'
GROUP BY l.`id`;

-- Revenue Summary View
CREATE VIEW `v_revenue_summary` AS
SELECT 
    DATE(t.`created_at`) as `transaction_date`,
    t.`type` as `transaction_type`,
    COUNT(*) as `transaction_count`,
    SUM(t.`amount`) as `total_amount`,
    SUM(t.`tax_amount`) as `total_tax`,
    AVG(t.`amount`) as `average_amount`,
    SUM(CASE WHEN t.`status` = 'completed' THEN t.`amount` ELSE 0 END) as `completed_amount`,
    SUM(CASE WHEN t.`status` = 'failed' THEN 1 ELSE 0 END) as `failed_count`
FROM `transactions` t
GROUP BY DATE(t.`created_at`), t.`type`;

-- Location Performance View
CREATE VIEW `v_location_performance` AS
SELECT 
    l.`id`,
    l.`name` as `location_name`,
    l.`state`,
    l.`city`,
    COUNT(DISTINCT f.`id`) as `total_franchises`,
    COUNT(DISTINCT d.`id`) as `total_documents`,
    COUNT(DISTINCT d.`user_id`) as `total_customers`,
    SUM(d.`total_amount`) as `total_revenue`,
    l.`is_serviceable`,
    l.`price_multiplier`
FROM `locations` l
LEFT JOIN `franchises` f ON l.`id` = f.`location_id` AND f.`status` = 'active'
LEFT JOIN `documents` d ON l.`id` = d.`location_id` AND d.`deleted_at` IS NULL
GROUP BY l.`id`;

-- ============================================================================
-- SEED DATA
-- ============================================================================

-- Insert Default Roles
INSERT INTO `roles` (`name`, `slug`, `display_name`, `description`, `is_system_role`, `level`) VALUES
('super_admin', 'super-admin', 'Super Administrator', 'Full system access with all privileges', TRUE, 100),
('admin', 'admin', 'Administrator', 'System administrator with management access', TRUE, 90),
('mis_manager', 'mis-manager', 'MIS Manager', 'Access to MIS dashboard and reports', TRUE, 80),
('franchise_owner', 'franchise-owner', 'Franchise Owner', 'Franchise owner with location management', TRUE, 70),
('franchise_staff', 'franchise-staff', 'Franchise Staff', 'Franchise staff with limited access', TRUE, 60),
('lawyer', 'lawyer', 'Legal Expert', 'Verified lawyer/legal consultant', TRUE, 50),
('user', 'user', 'Regular User', 'Standard user account', TRUE, 10),
('guest', 'guest', 'Guest User', 'Limited access guest', TRUE, 0);

-- Insert Default Permissions
INSERT INTO `permissions` (`name`, `slug`, `display_name`, `module`, `action`) VALUES
-- User Management
('users.view', 'users-view', 'View Users', 'users', 'read'),
('users.create', 'users-create', 'Create Users', 'users', 'create'),
('users.update', 'users-update', 'Update Users', 'users', 'update'),
('users.delete', 'users-delete', 'Delete Users', 'users', 'delete'),

-- Document Management
('documents.view_own', 'documents-view-own', 'View Own Documents', 'documents', 'read'),
('documents.view_all', 'documents-view-all', 'View All Documents', 'documents', 'read'),
('documents.create', 'documents-create', 'Create Documents', 'documents', 'create'),
('documents.update', 'documents-update', 'Update Documents', 'documents', 'update'),
('documents.delete', 'documents-delete', 'Delete Documents', 'documents', 'delete'),

-- Template Management
('templates.view', 'templates-view', 'View Templates', 'templates', 'read'),
('templates.create', 'templates-create', 'Create Templates', 'templates', 'create'),
('templates.update', 'templates-update', 'Update Templates', 'templates', 'update'),
('templates.delete', 'templates-delete', 'Delete Templates', 'templates', 'delete'),

-- Franchise Management
('franchises.view', 'franchises-view', 'View Franchises', 'franchises', 'read'),
('franchises.manage', 'franchises-manage', 'Manage Franchises', 'franchises', 'update'),
('franchises.approve', 'franchises-approve', 'Approve Franchises', 'franchises', 'create'),

-- MIS Access
('mis.view_dashboard', 'mis-view-dashboard', 'View MIS Dashboard', 'mis', 'read'),
('mis.view_reports', 'mis-view-reports', 'View Reports', 'mis', 'read'),
('mis.export_data', 'mis-export-data', 'Export Data', 'mis', 'read'),

-- System Settings
('settings.view', 'settings-view', 'View Settings', 'settings', 'read'),
('settings.update', 'settings-update', 'Update Settings', 'settings', 'update');

-- Assign all permissions to super_admin
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 1, `id` FROM `permissions`;

-- Insert Default System Settings
INSERT INTO `system_settings` (`setting_key`, `setting_value`, `setting_type`, `category`, `is_public`) VALUES
('site_name', 'Legal Document Management System', 'string', 'general', TRUE),
('site_email', 'support@legaldocs.com', 'string', 'general', TRUE),
('site_phone', '+91-1234567890', 'string', 'general', TRUE),
('default_currency', 'INR', 'string', 'general', TRUE),
('tax_percentage', '18', 'number', 'billing', TRUE),
('session_timeout', '3600', 'number', 'security', FALSE),
('enable_2fa', 'false', 'boolean', 'security', FALSE),
('maintenance_mode', 'false', 'boolean', 'system', FALSE),
('max_file_size', '10485760', 'number', 'uploads', FALSE),
('allowed_file_types', '["pdf","doc","docx","jpg","png"]', 'json', 'uploads', FALSE);

-- Insert Default Subscription Plans
INSERT INTO `subscription_plans` (`name`, `slug`, `short_description`, `price`, `billing_cycle`, `document_limit`, `is_popular`) VALUES
('Free', 'free', 'Perfect for trying out our service', 0.00, 'monthly', 3, FALSE),
('Basic', 'basic', 'For individuals with regular needs', 299.00, 'monthly', 10, FALSE),
('Professional', 'professional', 'Most popular for professionals', 799.00, 'monthly', 50, TRUE),
('Business', 'business', 'For businesses with high volume', 1999.00, 'monthly', -1, FALSE);

-- Insert Default Document Categories
INSERT INTO `document_categories` (`name`, `slug`, `description`, `display_order`) VALUES
('Affidavits', 'affidavits', 'Legal affidavits and sworn statements', 1),
('Rental Agreements', 'rental-agreements', 'Property rental and lease agreements', 2),
('Name Change', 'name-change', 'Legal name change documents', 3),
('Contracts', 'contracts', 'Business and personal contracts', 4),
('Power of Attorney', 'power-of-attorney', 'Power of attorney documents', 5),
('Partnership Deeds', 'partnership-deeds', 'Business partnership agreements', 6),
('Sale Agreements', 'sale-agreements', 'Property and goods sale agreements', 7),
('Loan Agreements', 'loan-agreements', 'Personal and business loan documents', 8);

-- ============================================================================
-- FINAL OPTIMIZATION
-- ============================================================================

-- Analyze tables for query optimization
ANALYZE TABLE `users`, `documents`, `document_templates`, `transactions`, `franchises`, `consultations`;

-- ============================================================================
-- DATABASE COMPLETE
-- Total Tables: 50+
-- Total Views: 6
-- Total Stored Procedures: 8
-- Total Triggers: 10
-- Complete with Relationships, Indexes, and Optimizations
-- ============================================================================KEY `idx_expires` (`expires_at`),
    CONSTRAINT `fk_pwd_reset_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Email Verification Tokens
CREATE TABLE `email_verification_tokens` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) UNIQUE NOT NULL,
    
    `verified` BOOLEAN DEFAULT FALSE,
    `verified_at` TIMESTAMP NULL,
    
    `expires_at` TIMESTAMP NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_token` (`token`),
    KEY `idx_user` (`user_id`),
    KEY `idx_email` (`email`),