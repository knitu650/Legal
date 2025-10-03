# API Documentation

## Base URL

```
http://your-domain.com/api/v1
```

## Authentication

All API requests require authentication using Bearer token or API key.

### Headers

```
Authorization: Bearer {your_token}
Content-Type: application/json
Accept: application/json
```

## Endpoints

### Authentication

#### POST /auth/login
Login to get access token

**Request:**
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "success": true,
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "user@example.com",
    "role_id": 6
  }
}
```

#### POST /auth/register
Register new user

**Request:**
```json
{
  "name": "John Doe",
  "email": "user@example.com",
  "phone": "1234567890",
  "password": "password123",
  "password_confirmation": "password123"
}
```

#### POST /auth/logout
Logout current user

---

### Users

#### GET /users/me
Get current user profile

**Response:**
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "user@example.com",
  "phone": "1234567890",
  "role_id": 6,
  "created_at": "2025-01-01 10:00:00"
}
```

#### POST /users/me
Update current user profile

**Request:**
```json
{
  "name": "John Updated",
  "phone": "9876543210"
}
```

---

### Documents

#### GET /documents
Get all documents for current user

**Query Parameters:**
- `page` (int): Page number
- `per_page` (int): Items per page
- `status` (string): Filter by status
- `search` (string): Search in title

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Rental Agreement",
      "status": "completed",
      "created_at": "2025-01-01 10:00:00"
    }
  ],
  "pagination": {
    "total": 50,
    "per_page": 15,
    "current_page": 1,
    "last_page": 4
  }
}
```

#### POST /documents
Create new document

**Request:**
```json
{
  "template_id": 5,
  "title": "My Rental Agreement",
  "content": "Document content here...",
  "category_id": 2
}
```

#### GET /documents/{id}
Get specific document

**Response:**
```json
{
  "id": 1,
  "title": "Rental Agreement",
  "content": "Document content...",
  "status": "completed",
  "user": {
    "id": 1,
    "name": "John Doe"
  },
  "template": {
    "id": 5,
    "name": "Rental Agreement Template"
  },
  "created_at": "2025-01-01 10:00:00"
}
```

#### PUT /documents/{id}
Update document

**Request:**
```json
{
  "title": "Updated Title",
  "content": "Updated content..."
}
```

#### DELETE /documents/{id}
Delete document

**Response:**
```json
{
  "success": true,
  "message": "Document deleted successfully"
}
```

---

### Templates

#### GET /templates
Get all available templates

**Query Parameters:**
- `category_id` (int): Filter by category
- `search` (string): Search in name
- `page` (int): Page number

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Rental Agreement",
      "description": "Standard rental agreement template",
      "price": 299.00,
      "category": {
        "id": 2,
        "name": "Agreements"
      }
    }
  ]
}
```

#### GET /templates/{id}
Get specific template

**Response:**
```json
{
  "id": 1,
  "name": "Rental Agreement",
  "description": "Standard rental agreement template",
  "content": "Template content with {{placeholders}}",
  "fields": [
    {"name": "landlord_name", "type": "text", "required": true},
    {"name": "tenant_name", "type": "text", "required": true},
    {"name": "property_address", "type": "textarea", "required": true}
  ],
  "price": 299.00
}
```

---

### Payments

#### POST /payments/create
Create payment order

**Request:**
```json
{
  "type": "document",
  "amount": 299.00,
  "document_id": 5
}
```

**Response:**
```json
{
  "success": true,
  "order_id": "order_123456",
  "amount": 299.00,
  "currency": "INR",
  "payment_url": "https://payment-gateway.com/pay/..."
}
```

#### POST /payments/verify
Verify payment after completion

**Request:**
```json
{
  "payment_id": "pay_123456",
  "order_id": "order_123456",
  "signature": "signature_hash"
}
```

---

### Consultations

#### GET /consultations
Get user consultations

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "lawyer": {
        "id": 10,
        "name": "Adv. Jane Smith",
        "specialization": "Corporate Law"
      },
      "scheduled_at": "2025-01-15 14:00:00",
      "status": "confirmed",
      "meeting_link": "https://zoom.us/j/..."
    }
  ]
}
```

#### POST /consultations
Book new consultation

**Request:**
```json
{
  "lawyer_id": 10,
  "type": "video",
  "scheduled_at": "2025-01-15 14:00:00",
  "duration_minutes": 60
}
```

---

### Locations

#### GET /locations
Get all locations

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Mumbai Central",
      "state": "Maharashtra",
      "city": "Mumbai",
      "pincode": "400008"
    }
  ]
}
```

#### GET /locations/{id}
Get specific location details

---

### Franchises

#### GET /franchises
Get franchise list

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "business_name": "Legal Docs Mumbai",
      "location": {
        "city": "Mumbai",
        "state": "Maharashtra"
      },
      "status": "active"
    }
  ]
}
```

---

## Error Responses

### 400 Bad Request
```json
{
  "error": "Invalid request data",
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

### 401 Unauthorized
```json
{
  "error": "Unauthenticated",
  "message": "Invalid or missing authentication token"
}
```

### 403 Forbidden
```json
{
  "error": "Forbidden",
  "message": "You don't have permission to access this resource"
}
```

### 404 Not Found
```json
{
  "error": "Not Found",
  "message": "The requested resource was not found"
}
```

### 500 Server Error
```json
{
  "error": "Server Error",
  "message": "An unexpected error occurred"
}
```

## Rate Limiting

- **Rate Limit**: 60 requests per minute per IP
- **Headers**:
  - `X-RateLimit-Limit`: 60
  - `X-RateLimit-Remaining`: 45
  - `X-RateLimit-Reset`: 1609459200

## Webhooks

### Payment Webhook

**Endpoint**: `/webhooks/razorpay`

**Payload:**
```json
{
  "event": "payment.captured",
  "payload": {
    "payment_id": "pay_123456",
    "order_id": "order_123456",
    "amount": 29900,
    "status": "captured"
  }
}
```

## SDKs

Coming soon:
- PHP SDK
- JavaScript SDK
- Python SDK

## Support

For API support:
- Email: api@legaldocs.com
- Documentation: https://docs.legaldocs.com/api
