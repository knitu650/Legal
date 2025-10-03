<?php

// User Roles
define('ROLE_SUPER_ADMIN', 1);
define('ROLE_ADMIN', 2);
define('ROLE_MIS', 3);
define('ROLE_FRANCHISE', 4);
define('ROLE_LAWYER', 5);
define('ROLE_USER', 6);

// Document Status
define('DOC_STATUS_DRAFT', 'draft');
define('DOC_STATUS_PENDING', 'pending');
define('DOC_STATUS_COMPLETED', 'completed');
define('DOC_STATUS_SIGNED', 'signed');
define('DOC_STATUS_ARCHIVED', 'archived');

// Payment Status
define('PAYMENT_PENDING', 'pending');
define('PAYMENT_COMPLETED', 'completed');
define('PAYMENT_FAILED', 'failed');
define('PAYMENT_REFUNDED', 'refunded');

// Subscription Status
define('SUBSCRIPTION_ACTIVE', 'active');
define('SUBSCRIPTION_EXPIRED', 'expired');
define('SUBSCRIPTION_CANCELLED', 'cancelled');

// Consultation Status
define('CONSULTATION_PENDING', 'pending');
define('CONSULTATION_CONFIRMED', 'confirmed');
define('CONSULTATION_COMPLETED', 'completed');
define('CONSULTATION_CANCELLED', 'cancelled');

// Franchise Status
define('FRANCHISE_PENDING', 'pending');
define('FRANCHISE_APPROVED', 'approved');
define('FRANCHISE_ACTIVE', 'active');
define('FRANCHISE_SUSPENDED', 'suspended');

// Ticket Status
define('TICKET_OPEN', 'open');
define('TICKET_IN_PROGRESS', 'in_progress');
define('TICKET_RESOLVED', 'resolved');
define('TICKET_CLOSED', 'closed');

// File Upload Paths
define('UPLOAD_DOCUMENTS', 'storage/app/documents/');
define('UPLOAD_SIGNATURES', 'storage/app/signatures/');
define('UPLOAD_PROFILES', 'storage/app/uploads/profiles/');

// Pagination
define('ITEMS_PER_PAGE', 15);
