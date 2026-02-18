# REST API Documentation

## Base URL
```
http://localhost:8000/api/v1
```

## Authentication

This API uses JWT (JSON Web Token) authentication. To access protected endpoints, you need to include the JWT token in the Authorization header.

### Authentication Endpoints

#### Register
Create a new user account and receive a JWT token.

**Endpoint:** `POST /api/auth/register`

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "SecureP@ss123",
  "password_confirmation": "SecureP@ss123"
}
```

**Password Requirements:**
- Minimum 8 characters
- At least one uppercase letter
- At least one lowercase letter
- At least one number
- At least one special symbol

**Success Response (201):**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "created_at": "2024-01-01T00:00:00.000000Z"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600
  }
}
```

**Error Response (422):**
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "email": ["The email has already been taken."],
    "password": ["The password field must be at least 8 characters."]
  }
}
```

**Example:**
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "SecureP@ss123",
    "password_confirmation": "SecureP@ss123"
  }'
```

#### Login
Authenticate an existing user and receive a JWT token.

**Endpoint:** `POST /api/auth/login`

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "SecureP@ss123"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600
  }
}
```

**Error Response (401):**
```json
{
  "success": false,
  "message": "Invalid credentials"
}
```

**Example:**
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "SecureP@ss123"
  }'
```

#### Get Current User
Get the authenticated user's information.

**Endpoint:** `GET /api/auth/me`

**Headers:**
```
Authorization: Bearer {your_token_here}
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "email_verified_at": null,
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    }
  }
}
```

**Error Response (401):**
```json
{
  "message": "Unauthenticated."
}
```

**Example:**
```bash
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc..."
```

#### Refresh Token
Refresh your JWT token to extend the session.

**Endpoint:** `POST /api/auth/refresh`

**Headers:**
```
Authorization: Bearer {your_token_here}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Token refreshed successfully",
  "data": {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600
  }
}
```

**Example:**
```bash
curl -X POST http://localhost:8000/api/auth/refresh \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc..."
```

#### Logout
Invalidate the current JWT token.

**Endpoint:** `POST /api/auth/logout`

**Headers:**
```
Authorization: Bearer {your_token_here}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Successfully logged out"
}
```

**Example:**
```bash
curl -X POST http://localhost:8000/api/auth/logout \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc..."
```

### Using JWT Token for Protected Endpoints

For all protected endpoints, include the JWT token in the Authorization header:

```bash
curl -X GET http://localhost:8000/api/v1/products \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc..."
```

### Authentication Flow Example

1. **Register a new user:**
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Jane Smith",
    "email": "jane@example.com",
    "password": "MySecure123!",
    "password_confirmation": "MySecure123!"
  }'
```

2. **Save the access_token from the response**

3. **Use the token to access protected endpoints:**
```bash
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer {your_access_token}"
```

4. **When the token is about to expire, refresh it:**
```bash
curl -X POST http://localhost:8000/api/auth/refresh \
  -H "Authorization: Bearer {your_access_token}"
```

5. **When done, logout:**
```bash
curl -X POST http://localhost:8000/api/auth/logout \
  -H "Authorization: Bearer {your_access_token}"
```

## Available Endpoints

### Users
- `GET /api/v1/users` - Get all users
- `GET /api/v1/users/{id}` - Get a specific user
- `POST /api/v1/users` - Create a new user
- `PUT /api/v1/users/{id}` - Update a user
- `DELETE /api/v1/users/{id}` - Delete a user

#### Create User Example
```bash
curl -X POST http://localhost:8000/api/v1/users \
  -H "Content-Type: application/json" \
  -d '{"name":"John Doe","email":"john@example.com","password":"password123"}'
```

### Products
- `GET /api/v1/products` - Get all products (paginated)
- `GET /api/v1/products/{id}` - Get a specific product
- `POST /api/v1/products` - Create a new product
- `PUT /api/v1/products/{id}` - Update a product
- `DELETE /api/v1/products/{id}` - Delete a product

#### Create Product Example
```bash
curl -X POST http://localhost:8000/api/v1/products \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Product Name",
    "description": "Product description",
    "price": 99.99,
    "quantity": 100,
    "published": true,
    "inStock": true,
    "category_id": 1,
    "brand_id": 1
  }'
```

#### Products Filtering
Products support filtering via query parameters:
- `brands[]` - Filter by brand IDs
- `categories[]` - Filter by category IDs  
- `prices[from]` - Minimum price
- `prices[to]` - Maximum price
- `per_page` - Items per page (default: 15)

Example:
```bash
curl "http://localhost:8000/api/v1/products?brands[]=1&categories[]=2&prices[from]=10&prices[to]=100&per_page=20"
```

### Categories
- `GET /api/v1/categories` - Get all categories
- `GET /api/v1/categories/{id}` - Get a specific category
- `POST /api/v1/categories` - Create a new category
- `PUT /api/v1/categories/{id}` - Update a category
- `DELETE /api/v1/categories/{id}` - Delete a category

#### Create Category Example
```bash
curl -X POST http://localhost:8000/api/v1/categories \
  -H "Content-Type: application/json" \
  -d '{"name":"Electronics"}'
```

### Brands
- `GET /api/v1/brands` - Get all brands
- `GET /api/v1/brands/{id}` - Get a specific brand
- `POST /api/v1/brands` - Create a new brand
- `PUT /api/v1/brands/{id}` - Update a brand
- `DELETE /api/v1/brands/{id}` - Delete a brand

#### Create Brand Example
```bash
curl -X POST http://localhost:8000/api/v1/brands \
  -H "Content-Type: application/json" \
  -d '{"name":"Samsung"}'
```

### Orders
- `GET /api/v1/orders` - Get all orders
- `GET /api/v1/orders/{id}` - Get a specific order
- `POST /api/v1/orders` - Create a new order
- `PUT /api/v1/orders/{id}` - Update an order
- `DELETE /api/v1/orders/{id}` - Delete an order

#### Create Order Example
```bash
curl -X POST http://localhost:8000/api/v1/orders \
  -H "Content-Type: application/json" \
  -d '{
    "total_price": 199.99,
    "status": "pending",
    "user_address_id": 1,
    "created_by": 1
  }'
```

## Response Format

All endpoints return JSON responses with a consistent format:

### Success Response
```json
{
  "success": true,
  "data": { ... },
  "message": "Optional success message"
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error description"
}
```

## CORS
CORS is enabled for all origins. You can make requests from any frontend application.

## Starting the Server
```bash
php artisan serve
```

The API will be available at `http://localhost:8000/api/v1`
