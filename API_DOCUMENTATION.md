# REST API Documentation

## Base URL
```
http://localhost:8000/api/v1
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
