<?php

use App\Core\Router;

$router = new Router();

// ============================================================================
// WEB ROUTES
// ============================================================================

// Public Pages
$router->get('/', 'Web\HomeController@index');
$router->get('/about', 'Web\HomeController@about');
$router->get('/contact', 'Web\HomeController@contact');
$router->post('/contact', 'Web\HomeController@sendContact');
$router->get('/pricing', 'Web\HomeController@pricing');

// Authentication Routes
$router->get('/login', 'Web\AuthController@showLogin');
$router->post('/login', 'Web\AuthController@login');
$router->get('/register', 'Web\AuthController@showRegister');
$router->post('/register', 'Web\AuthController@register');
$router->get('/logout', 'Web\AuthController@logout');
$router->get('/forgot-password', 'Web\AuthController@showForgotPassword');
$router->post('/forgot-password', 'Web\AuthController@forgotPassword');
$router->get('/reset-password/{token}', 'Web\AuthController@showResetPassword');
$router->post('/reset-password', 'Web\AuthController@resetPassword');

// Documents (Public)
$router->get('/documents', 'Web\DocumentController@index');
$router->get('/documents/search', 'Web\SearchController@search');
$router->get('/templates', 'Web\TemplateController@index');
$router->get('/templates/{id}', 'Web\TemplateController@show');

// Consultation
$router->get('/consultation', 'Web\ConsultationController@index');
$router->get('/lawyers', 'Web\ConsultationController@lawyers');

// Knowledge Base
$router->get('/knowledge-base', 'Web\KnowledgeBaseController@index');
$router->get('/faq', 'Web\KnowledgeBaseController@faq');

// ============================================================================
// USER PANEL ROUTES (Protected)
// ============================================================================

$router->group(['prefix' => 'user', 'middleware' => 'auth'], function($router) {
    // Dashboard
    $router->get('/dashboard', 'User\DashboardController@index');
    
    // Documents
    $router->get('/documents', 'User\DocumentController@index');
    $router->get('/documents/create', 'User\DocumentController@create');
    $router->post('/documents', 'User\DocumentController@store');
    $router->get('/documents/{id}', 'User\DocumentController@show');
    $router->get('/documents/{id}/edit', 'User\DocumentController@edit');
    $router->post('/documents/{id}', 'User\DocumentController@update');
    $router->delete('/documents/{id}', 'User\DocumentController@delete');
    $router->get('/documents/{id}/download', 'User\DocumentController@download');
    $router->post('/documents/{id}/share', 'User\DocumentController@share');
    
    // My Documents
    $router->get('/my-documents', 'User\MyDocumentsController@index');
    $router->get('/my-documents/drafts', 'User\MyDocumentsController@drafts');
    $router->get('/my-documents/completed', 'User\MyDocumentsController@completed');
    
    // Signatures
    $router->get('/signatures', 'User\SignatureController@index');
    $router->post('/signatures/create', 'User\SignatureController@create');
    $router->post('/documents/{id}/sign', 'User\SignatureController@sign');
    
    // Subscriptions
    $router->get('/subscription', 'User\SubscriptionController@index');
    $router->get('/subscription/plans', 'User\SubscriptionController@plans');
    $router->post('/subscription/subscribe', 'User\SubscriptionController@subscribe');
    $router->post('/subscription/cancel', 'User\SubscriptionController@cancel');
    
    // Billing
    $router->get('/billing', 'User\BillingController@index');
    $router->get('/billing/invoices', 'User\BillingController@invoices');
    $router->get('/billing/payment-methods', 'User\BillingController@paymentMethods');
    
    // Consultations
    $router->get('/consultations', 'User\ConsultationController@index');
    $router->get('/consultations/book', 'User\ConsultationController@book');
    $router->post('/consultations', 'User\ConsultationController@store');
    $router->get('/consultations/{id}', 'User\ConsultationController@show');
    
    // Referrals
    $router->get('/referrals', 'User\ReferralController@index');
    
    // Support Tickets
    $router->get('/support', 'User\SupportTicketController@index');
    $router->get('/support/create', 'User\SupportTicketController@create');
    $router->post('/support', 'User\SupportTicketController@store');
    $router->get('/support/{id}', 'User\SupportTicketController@show');
    
    // Settings
    $router->get('/settings', 'User\SettingsController@index');
    $router->post('/settings/profile', 'User\SettingsController@updateProfile');
    $router->post('/settings/password', 'User\SettingsController@changePassword');
    $router->post('/settings/notifications', 'User\SettingsController@updateNotifications');
});

// ============================================================================
// ADMIN PANEL ROUTES (Protected + Admin)
// ============================================================================

$router->group(['prefix' => 'admin', 'middleware' => 'auth,admin'], function($router) {
    // Dashboard
    $router->get('/dashboard', 'Admin\DashboardController@index');
    
    // User Management
    $router->get('/users', 'Admin\UserManagementController@index');
    $router->get('/users/create', 'Admin\UserManagementController@create');
    $router->post('/users', 'Admin\UserManagementController@store');
    $router->get('/users/{id}', 'Admin\UserManagementController@show');
    $router->get('/users/{id}/edit', 'Admin\UserManagementController@edit');
    $router->post('/users/{id}', 'Admin\UserManagementController@update');
    $router->delete('/users/{id}', 'Admin\UserManagementController@delete');
    
    // Roles & Permissions
    $router->get('/roles', 'Admin\RolePermissionController@index');
    $router->post('/roles', 'Admin\RolePermissionController@store');
    $router->get('/roles/{id}/permissions', 'Admin\RolePermissionController@permissions');
    $router->post('/roles/{id}/permissions', 'Admin\RolePermissionController@updatePermissions');
    
    // Document Management
    $router->get('/documents', 'Admin\DocumentManagementController@index');
    $router->get('/documents/{id}', 'Admin\DocumentManagementController@show');
    $router->post('/documents/{id}/approve', 'Admin\DocumentManagementController@approve');
    $router->post('/documents/{id}/reject', 'Admin\DocumentManagementController@reject');
    
    // Template Management
    $router->get('/templates', 'Admin\TemplateManagementController@index');
    $router->get('/templates/create', 'Admin\TemplateManagementController@create');
    $router->post('/templates', 'Admin\TemplateManagementController@store');
    $router->get('/templates/{id}/edit', 'Admin\TemplateManagementController@edit');
    $router->post('/templates/{id}', 'Admin\TemplateManagementController@update');
    $router->delete('/templates/{id}', 'Admin\TemplateManagementController@delete');
    
    // Category Management
    $router->get('/categories', 'Admin\CategoryManagementController@index');
    $router->post('/categories', 'Admin\CategoryManagementController@store');
    $router->post('/categories/{id}', 'Admin\CategoryManagementController@update');
    $router->delete('/categories/{id}', 'Admin\CategoryManagementController@delete');
    
    // Subscription Management
    $router->get('/subscriptions', 'Admin\SubscriptionManagementController@index');
    $router->get('/subscriptions/plans', 'Admin\SubscriptionManagementController@plans');
    $router->post('/subscriptions/plans', 'Admin\SubscriptionManagementController@storePlan');
    $router->post('/subscriptions/plans/{id}', 'Admin\SubscriptionManagementController@updatePlan');
    
    // Transactions
    $router->get('/transactions', 'Admin\TransactionController@index');
    $router->get('/transactions/{id}', 'Admin\TransactionController@show');
    $router->post('/transactions/{id}/refund', 'Admin\TransactionController@refund');
    
    // Lawyer Management
    $router->get('/lawyers', 'Admin\LawyerManagementController@index');
    $router->get('/lawyers/pending', 'Admin\LawyerManagementController@pending');
    $router->get('/lawyers/{id}', 'Admin\LawyerManagementController@show');
    $router->post('/lawyers/{id}/approve', 'Admin\LawyerManagementController@approve');
    $router->post('/lawyers/{id}/reject', 'Admin\LawyerManagementController@reject');
    
    // Consultation Management
    $router->get('/consultations', 'Admin\ConsultationManagementController@index');
    $router->get('/consultations/{id}', 'Admin\ConsultationManagementController@show');
    
    // Franchise Management
    $router->get('/franchises', 'Admin\FranchiseManagementController@index');
    $router->get('/franchises/applications', 'Admin\FranchiseManagementController@applications');
    $router->get('/franchises/{id}', 'Admin\FranchiseManagementController@show');
    $router->post('/franchises/{id}/approve', 'Admin\FranchiseManagementController@approve');
    
    // Location Management
    $router->get('/locations', 'Admin\LocationManagementController@index');
    $router->post('/locations', 'Admin\LocationManagementController@store');
    $router->post('/locations/{id}', 'Admin\LocationManagementController@update');
    
    // Reports
    $router->get('/reports', 'Admin\ReportController@index');
    $router->get('/reports/revenue', 'Admin\ReportController@revenue');
    $router->get('/reports/users', 'Admin\ReportController@users');
    $router->get('/reports/documents', 'Admin\ReportController@documents');
    
    // Analytics
    $router->get('/analytics', 'Admin\AnalyticsController@index');
    
    // Support Tickets
    $router->get('/support', 'Admin\SupportTicketController@index');
    $router->get('/support/{id}', 'Admin\SupportTicketController@show');
    $router->post('/support/{id}/reply', 'Admin\SupportTicketController@reply');
    $router->post('/support/{id}/close', 'Admin\SupportTicketController@close');
    
    // Coupons
    $router->get('/coupons', 'Admin\CouponController@index');
    $router->post('/coupons', 'Admin\CouponController@store');
    $router->post('/coupons/{id}', 'Admin\CouponController@update');
    $router->delete('/coupons/{id}', 'Admin\CouponController@delete');
    
    // Settings
    $router->get('/settings', 'Admin\SettingsController@index');
    $router->post('/settings/general', 'Admin\SettingsController@updateGeneral');
    $router->post('/settings/payment', 'Admin\SettingsController@updatePayment');
    $router->post('/settings/email', 'Admin\SettingsController@updateEmail');
    
    // Email Templates
    $router->get('/email-templates', 'Admin\EmailTemplateController@index');
    $router->post('/email-templates/{id}', 'Admin\EmailTemplateController@update');
    
    // Audit Logs
    $router->get('/audit-logs', 'Admin\AuditLogController@index');
    
    // System Health
    $router->get('/system-health', 'Admin\SystemHealthController@index');
});

// ============================================================================
// MIS DASHBOARD ROUTES (Protected + MIS)
// ============================================================================

$router->group(['prefix' => 'mis', 'middleware' => 'auth,mis'], function($router) {
    // Dashboard
    $router->get('/dashboard', 'MIS\DashboardController@index');
    
    // Reports
    $router->get('/reports/revenue', 'MIS\RevenueReportController@index');
    $router->get('/reports/users', 'MIS\UserReportController@index');
    $router->get('/reports/documents', 'MIS\DocumentReportController@index');
    $router->get('/reports/subscriptions', 'MIS\SubscriptionReportController@index');
    $router->get('/reports/franchises', 'MIS\FranchiseReportController@index');
    $router->get('/reports/locations', 'MIS\LocationReportController@index');
    $router->get('/reports/performance', 'MIS\PerformanceReportController@index');
    $router->get('/reports/financial', 'MIS\FinancialReportController@index');
    $router->get('/reports/custom', 'MIS\CustomReportController@index');
    
    // Trend Analysis
    $router->get('/trends', 'MIS\TrendAnalysisController@index');
    
    // Charts
    $router->get('/charts', 'MIS\ChartController@index');
    
    // Export
    $router->get('/export/excel', 'MIS\ExportController@excel');
    $router->get('/export/pdf', 'MIS\ExportController@pdf');
    $router->get('/export/csv', 'MIS\ExportController@csv');
});

// ============================================================================
// FRANCHISE PANEL ROUTES (Protected + Franchise)
// ============================================================================

$router->group(['prefix' => 'franchise', 'middleware' => 'auth,franchise'], function($router) {
    // Dashboard
    $router->get('/dashboard', 'Franchise\DashboardController@index');
    
    // Application
    $router->get('/application', 'Franchise\ApplicationController@index');
    $router->post('/application', 'Franchise\ApplicationController@submit');
    $router->get('/application/status', 'Franchise\ApplicationController@status');
    
    // Customers
    $router->get('/customers', 'Franchise\CustomerController@index');
    $router->post('/customers', 'Franchise\CustomerController@store');
    $router->get('/customers/{id}', 'Franchise\CustomerController@show');
    
    // Documents
    $router->get('/documents', 'Franchise\DocumentController@index');
    $router->post('/documents', 'Franchise\DocumentController@store');
    
    // Revenue
    $router->get('/revenue', 'Franchise\RevenueController@index');
    $router->get('/revenue/commissions', 'Franchise\CommissionController@index');
    
    // Location
    $router->get('/location', 'Franchise\LocationController@index');
    
    // Inventory
    $router->get('/inventory', 'Franchise\InventoryController@index');
    $router->post('/inventory/request', 'Franchise\InventoryController@request');
    
    // Staff
    $router->get('/staff', 'Franchise\StaffController@index');
    $router->post('/staff', 'Franchise\StaffController@store');
    
    // Reports
    $router->get('/reports', 'Franchise\ReportController@index');
    $router->get('/reports/daily', 'Franchise\ReportController@daily');
    $router->get('/reports/monthly', 'Franchise\ReportController@monthly');
    
    // Settings
    $router->get('/settings', 'Franchise\SettingsController@index');
    $router->post('/settings', 'Franchise\SettingsController@update');
    
    // Support
    $router->get('/support', 'Franchise\SupportController@index');
});

// ============================================================================
// LAWYER PANEL ROUTES (Protected + Lawyer)
// ============================================================================

$router->group(['prefix' => 'lawyer', 'middleware' => 'auth,lawyer'], function($router) {
    // Dashboard
    $router->get('/dashboard', 'Lawyer\DashboardController@index');
    
    // Profile
    $router->get('/profile', 'Lawyer\ProfileController@index');
    $router->post('/profile', 'Lawyer\ProfileController@update');
    
    // Consultations
    $router->get('/consultations', 'Lawyer\ConsultationController@index');
    $router->get('/consultations/{id}', 'Lawyer\ConsultationController@show');
    $router->post('/consultations/{id}/complete', 'Lawyer\ConsultationController@complete');
    
    // Availability
    $router->get('/availability', 'Lawyer\AvailabilityController@index');
    $router->post('/availability', 'Lawyer\AvailabilityController@update');
    
    // Clients
    $router->get('/clients', 'Lawyer\ClientController@index');
    $router->get('/clients/{id}', 'Lawyer\ClientController@show');
    
    // Document Review
    $router->get('/document-reviews', 'Lawyer\DocumentReviewController@index');
    $router->get('/document-reviews/{id}', 'Lawyer\DocumentReviewController@show');
    $router->post('/document-reviews/{id}/complete', 'Lawyer\DocumentReviewController@complete');
    
    // Earnings
    $router->get('/earnings', 'Lawyer\EarningsController@index');
    $router->post('/earnings/withdraw', 'Lawyer\EarningsController@withdraw');
    
    // Schedule
    $router->get('/schedule', 'Lawyer\ScheduleController@index');
    
    // Settings
    $router->get('/settings', 'Lawyer\SettingsController@index');
    $router->post('/settings', 'Lawyer\SettingsController@update');
});

// ============================================================================
// API ROUTES (V1)
// ============================================================================

$router->group(['prefix' => 'api/v1', 'middleware' => 'api'], function($router) {
    // Authentication
    $router->post('/auth/login', 'Api\V1\AuthApiController@login');
    $router->post('/auth/register', 'Api\V1\AuthApiController@register');
    $router->post('/auth/logout', 'Api\V1\AuthApiController@logout');
    
    // Users
    $router->get('/users/me', 'Api\V1\UserApiController@me');
    $router->post('/users/me', 'Api\V1\UserApiController@update');
    
    // Documents
    $router->get('/documents', 'Api\V1\DocumentApiController@index');
    $router->post('/documents', 'Api\V1\DocumentApiController@store');
    $router->get('/documents/{id}', 'Api\V1\DocumentApiController@show');
    $router->put('/documents/{id}', 'Api\V1\DocumentApiController@update');
    $router->delete('/documents/{id}', 'Api\V1\DocumentApiController@delete');
    
    // Templates
    $router->get('/templates', 'Api\V1\TemplateApiController@index');
    $router->get('/templates/{id}', 'Api\V1\TemplateApiController@show');
    
    // Payments
    $router->post('/payments/create', 'Api\V1\PaymentApiController@create');
    $router->post('/payments/verify', 'Api\V1\PaymentApiController@verify');
    
    // Consultations
    $router->get('/consultations', 'Api\V1\ConsultationApiController@index');
    $router->post('/consultations', 'Api\V1\ConsultationApiController@store');
    
    // Locations
    $router->get('/locations', 'Api\V1\LocationApiController@index');
    $router->get('/locations/{id}', 'Api\V1\LocationApiController@show');
    
    // Franchises
    $router->get('/franchises', 'Api\V1\FranchiseApiController@index');
});

// Payment Webhooks
$router->post('/webhooks/razorpay', 'Web\PaymentController@razorpayWebhook');
$router->post('/webhooks/stripe', 'Web\PaymentController@stripeWebhook');

return $router;
