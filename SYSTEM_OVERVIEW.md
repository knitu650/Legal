# Legal Document Management System - Complete Overview

## Project Statistics

- **Total PHP Files**: 334
- **Total Frontend Files** (HTML/CSS/JS): 44
- **Total Configuration Files**: 12
- **Database Migration Files**: 14
- **Email Templates**: 10
- **Document Templates**: 4
- **Language Files**: 4

## System Architecture

### Technology Stack

#### Backend
- **Framework**: Custom PHP MVC Framework
- **PHP Version**: 8.2+
- **Database**: MySQL 8.0+
- **ORM**: Custom Query Builder with PDO
- **Authentication**: Session-based with bcrypt password hashing

#### Frontend
- **HTML5** with semantic markup
- **CSS3** with custom styling and Bootstrap 5
- **JavaScript** with jQuery
- **Charts**: Chart.js for data visualization
- **Tables**: DataTables for advanced table features
- **Dropdowns**: Select2 for enhanced select boxes

#### Third-Party Integrations
- **Payment Gateways**: Razorpay, Stripe, Paytm
- **Email**: SMTP, SendGrid
- **SMS**: Twilio, MSG91
- **Video Calls**: Zoom API
- **Maps**: Google Maps API
- **PDF Generation**: TCPDF, mPDF
- **Cloud Storage**: AWS S3, Google Cloud (optional)

## Directory Structure

```
legal-document-management-system/
├── app/                          # Application core
│   ├── Core/                    # Framework core files
│   ├── Models/                  # Data models
│   ├── Controllers/             # Request handlers
│   ├── Middleware/              # Request middleware
│   ├── Services/                # Business logic
│   ├── Repositories/            # Data access layer
│   ├── Helpers/                 # Helper functions
│   ├── Utils/                   # Utility classes
│   ├── Events/                  # Event system
│   ├── Listeners/               # Event listeners
│   ├── Jobs/                    # Background jobs
│   └── Traits/                  # Reusable traits
├── config/                      # Configuration files
├── database/                    # Database files
│   ├── migrations/             # Migration files
│   └── seeds/                  # Seed files
├── public/                      # Web root
│   ├── assets/                 # Public assets
│   │   ├── css/               # Stylesheets
│   │   ├── js/                # JavaScript
│   │   ├── images/            # Images
│   │   └── fonts/             # Fonts
│   └── index.php              # Entry point
├── resources/                   # Application resources
│   ├── views/                  # View templates
│   ├── lang/                   # Translations
│   └── templates/              # Document templates
├── storage/                     # Storage directory
│   ├── app/                   # Application files
│   ├── logs/                  # Log files
│   ├── cache/                 # Cache files
│   └── sessions/              # Session files
├── scripts/                     # CLI scripts
└── vendor/                      # Composer dependencies
```

## Core Modules

### 1. Web Application Module
**Purpose**: Public-facing website for users

**Features**:
- Home page with service overview
- Document template browsing
- Advanced search and filtering
- User registration and authentication
- Document creation wizard
- Payment processing
- Lawyer consultation booking
- Support system
- Knowledge base

**Files**: 20+ controllers, 30+ views

### 2. User Panel Module
**Purpose**: Dashboard for regular users

**Features**:
- Personalized dashboard
- Document management (Create, Read, Update, Delete)
- Document editor with rich text
- E-signature functionality
- Document sharing and collaboration
- Version history tracking
- Subscription management
- Billing and invoices
- Profile settings
- Notification center
- Support tickets

**Access**: Authenticated users with 'user' role

### 3. Admin Panel Module
**Purpose**: System administration and management

**Features**:
- Comprehensive dashboard with analytics
- User management (CRUD, role assignment)
- Role and permission management
- Document management and moderation
- Template management
- Category management
- Subscription plan configuration
- Transaction monitoring and refunds
- Lawyer verification and approval
- Franchise application review
- Location and coverage management
- Coupon creation and tracking
- Support ticket management
- Email/SMS template editor
- System settings
- Audit logs and activity tracking
- System health monitoring

**Access**: Admin and Super Admin roles

### 4. MIS Dashboard Module
**Purpose**: Management Information System for executives

**Features**:
- Executive dashboard with KPIs
- Revenue and financial reports
- User analytics and growth metrics
- Document statistics and trends
- Subscription performance analysis
- Franchise performance tracking
- Location-wise revenue reports
- Performance comparisons
- Trend analysis and forecasting
- Custom report builder
- Data export (Excel, PDF, CSV)
- Interactive charts and visualizations
- Real-time metrics

**Access**: MIS role

### 5. Franchise Panel Module
**Purpose**: Management interface for franchise partners

**Features**:
- Franchise-specific dashboard
- Application submission and tracking
- Customer management
- Document processing for customers
- Revenue tracking
- Commission reports and calculations
- Payout management
- Inventory management (stamp papers)
- Staff management and permissions
- Territory and coverage monitoring
- Daily, monthly, and custom reports
- Banking and payout settings
- Support ticket system

**Access**: Franchise role

### 6. Lawyer Panel Module
**Purpose**: Interface for legal professionals

**Features**:
- Lawyer dashboard with statistics
- Profile management and verification
- Consultation management
- Schedule and availability calendar
- Client management
- Document review requests
- Earnings and transaction history
- Withdrawal request management
- Ratings and reviews display
- Calendar integration
- Video call integration (Zoom)

**Access**: Lawyer role

### 7. API Module
**Purpose**: RESTful API for third-party integrations

**Features**:
- Authentication endpoints
- User management API
- Document CRUD operations
- Template listing
- Payment processing
- Consultation booking
- Location services
- Franchise data access

**Version**: v1 (with v2 structure ready)
**Authentication**: Token-based (API keys)

## Database Schema

### Core Tables
- **users**: User accounts and profiles
- **roles**: User roles (Admin, User, Lawyer, etc.)
- **permissions**: System permissions
- **role_permissions**: Role-permission mapping
- **user_roles**: User-role mapping

### Document Tables
- **documents**: Main document records
- **document_templates**: Reusable templates
- **document_categories**: Document categorization
- **document_versions**: Version control
- **document_collaborators**: Collaboration tracking
- **signatures**: E-signature records
- **signature_requests**: Signature request tracking

### Subscription & Payment Tables
- **subscriptions**: User subscriptions
- **subscription_plans**: Available plans
- **transactions**: Payment transactions
- **invoices**: Invoice records
- **coupons**: Discount coupons
- **referrals**: Referral tracking

### Consultation Tables
- **lawyers**: Lawyer profiles
- **consultations**: Consultation bookings
- **lawyer_availability**: Schedule management
- **reviews**: Lawyer reviews and ratings

### Franchise Tables
- **franchises**: Franchise partners
- **franchise_applications**: Application tracking
- **locations**: Geographic locations
- **territories**: Territory assignments

### Support Tables
- **support_tickets**: Customer support
- **ticket_replies**: Support responses
- **notifications**: User notifications
- **audit_logs**: System audit trail
- **activity_logs**: User activity tracking

## Security Features

### Authentication & Authorization
- Session-based authentication
- Password hashing with bcrypt
- Role-based access control (RBAC)
- Permission-based feature access
- Account activation via email
- Password reset functionality
- Session timeout handling

### Data Protection
- CSRF token validation
- XSS prevention through input sanitization
- SQL injection prevention via PDO prepared statements
- File upload validation
- Rate limiting on API endpoints
- Secure password storage
- Encrypted sensitive data storage

### Application Security
- HTTPS enforcement
- Secure headers configuration
- Protection against clickjacking
- Input validation and sanitization
- Output encoding
- Secure session configuration
- Protection against CSRF attacks

## Performance Optimization

### Caching Strategy
- File-based caching system
- View caching
- Route caching
- Query result caching
- Session caching

### Database Optimization
- Proper indexing on frequently queried columns
- Query optimization
- Lazy loading relationships
- Database connection pooling
- Efficient pagination

### Frontend Optimization
- Asset minification support
- Image optimization
- Lazy loading images
- CDN ready architecture
- Browser caching headers

## Email System

### Email Templates
- Welcome email on registration
- Password reset email
- Email verification
- Document created notification
- Document signed notification
- Document shared notification
- Payment receipt
- Invoice generation
- Subscription renewal
- Consultation booking confirmation
- Consultation reminder
- Franchise application status
- Commission reports

### Email Configuration
- SMTP support
- SendGrid integration
- Template-based system
- Queue support for bulk emails
- Email tracking and logs

## Payment Integration

### Supported Gateways
1. **Razorpay** (Primary - India)
2. **Stripe** (International)
3. **Paytm** (India)

### Payment Features
- One-time payments
- Recurring subscriptions
- Refund processing
- Invoice generation
- Payment history
- Multiple currency support
- Webhook handling
- Payment failure retry

## Notification System

### Channels
- Email notifications
- SMS notifications (Twilio)
- In-app notifications
- Push notifications (ready)
- WhatsApp (integration ready)

### Notification Types
- Document updates
- Payment confirmations
- Subscription reminders
- Consultation reminders
- System announcements
- Support ticket updates

## Document Management

### Document Types
- Personal documents (Affidavits, POA, Will)
- Business documents (Contracts, NDA, Employment)
- Property documents (Rental, Lease, Sale Deed)
- Court documents
- Notices

### Document Features
- Template-based creation
- Rich text editor
- Field validation
- Auto-save functionality
- Version control
- Collaboration support
- E-signature integration
- PDF generation
- Document sharing
- Access control
- Download tracking

## Reporting System

### Available Reports
- Revenue reports (daily, monthly, yearly)
- User growth analytics
- Document statistics
- Subscription metrics
- Franchise performance
- Location-wise analysis
- Financial statements
- Custom reports

### Export Formats
- Excel (XLSX)
- PDF
- CSV
- JSON (API)

## Localization

### Supported Languages
- English (en) - Default
- Hindi (hi) - In progress
- Marathi (mr) - Planned

### Translatable Elements
- UI labels and messages
- Email templates
- Error messages
- Validation messages
- Document templates

## API Documentation

### Authentication
```
POST /api/v1/auth/login
POST /api/v1/auth/register
POST /api/v1/auth/logout
```

### Documents
```
GET    /api/v1/documents
POST   /api/v1/documents
GET    /api/v1/documents/{id}
PUT    /api/v1/documents/{id}
DELETE /api/v1/documents/{id}
```

### Templates
```
GET /api/v1/templates
GET /api/v1/templates/{id}
```

### Payments
```
POST /api/v1/payments/initiate
POST /api/v1/payments/verify
GET  /api/v1/payments/history
```

## Maintenance & Monitoring

### Log Files
- **app.log**: General application logs
- **error.log**: Error logs
- **access.log**: Access logs
- **payment.log**: Payment transaction logs
- **api.log**: API request logs
- **audit.log**: Security audit logs

### Scheduled Tasks (Cron Jobs)
- Generate daily reports (2 AM)
- Clean up temporary files (Every 6 hours)
- Update subscription status (1 AM)
- Send reminder emails (Hourly)
- Backup database (3 AM)
- Generate analytics (4 AM)

### Health Checks
- Database connectivity
- File storage availability
- Email service status
- Payment gateway status
- API response time
- Disk space monitoring

## Deployment Requirements

### Minimum Server Specifications
- **CPU**: 2 cores
- **RAM**: 4GB
- **Storage**: 50GB SSD
- **Bandwidth**: 100GB/month

### Recommended Server Specifications
- **CPU**: 4+ cores
- **RAM**: 8GB+
- **Storage**: 100GB+ SSD
- **Bandwidth**: Unlimited
- **CDN**: CloudFlare or similar

## Default Users (After Seeding)

| Email | Password | Role |
|-------|----------|------|
| superadmin@legaldocs.com | Admin@123 | Super Admin |
| admin@legaldocs.com | Admin@123 | Admin |
| mis@legaldocs.com | Mis@123 | MIS |
| user@legaldocs.com | User@123 | User |

**Important**: Change these passwords immediately after first login in production!

## Support & Documentation

### Internal Documentation
- API Documentation: `/docs/api/`
- Installation Guide: `INSTALLATION.md`
- Deployment Guide: `DEPLOYMENT_GUIDE.md`
- Configuration Guide: `docs/configuration.md`

### External Resources
- Official Website: https://legaldocs.com
- Documentation: https://docs.legaldocs.com
- Support Email: support@legaldocs.com
- Phone: +91 9876543210

## License

This project is proprietary software. All rights reserved.
For licensing inquiries, contact: legal@legaldocs.com

## Version Information

- **Current Version**: 1.0.0
- **Release Date**: October 5, 2025
- **PHP Version Required**: 8.0+
- **MySQL Version Required**: 8.0+
- **Browser Support**: Modern browsers (Chrome, Firefox, Safari, Edge)

## Future Roadmap

### Planned Features (v2.0)
- Two-factor authentication
- Real-time collaboration
- AI-powered document suggestions
- Mobile applications (iOS/Android)
- Blockchain document verification
- Advanced analytics with ML
- WhatsApp Business integration
- Voice-to-text document creation
- Aadhaar-based digital signature
- Multi-language OCR
- Document comparison tool
- Automated legal compliance checking

---

**Last Updated**: October 5, 2025
**Maintained By**: Legal Docs Development Team
