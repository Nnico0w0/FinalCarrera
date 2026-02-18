# Authentication Guide

## Overview

This application provides two authentication methods:
1. **Web Authentication** - Session-based authentication for the web interface (Laravel Breeze)
2. **API Authentication** - JWT-based authentication for API endpoints

## Web Authentication (Session-based)

### Login
- **URL**: `http://localhost:8000/login`
- Uses session cookies
- Protected by CSRF tokens
- Includes rate limiting (5 attempts per minute)

### Register
- **URL**: `http://localhost:8000/register`
- Password requirements:
  - Minimum 8 characters
  - Must include uppercase and lowercase letters
  - Must include numbers
  - Must include symbols
- Automatic login after registration

### Features
- Remember me functionality
- Password reset via email
- Email verification (optional)
- Profile management

## API Authentication (JWT)

### Quick Start

1. **Register a new user:**
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

2. **Login:**
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "SecureP@ss123"
  }'
```

3. **Use the token:**
```bash
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Available Endpoints

- `POST /api/auth/register` - Register new user
- `POST /api/auth/login` - Login and get JWT token
- `GET /api/auth/me` - Get authenticated user info
- `POST /api/auth/refresh` - Refresh JWT token
- `POST /api/auth/logout` - Invalidate JWT token

### Token Information

- **Token Type**: Bearer
- **Expiration**: 60 minutes (3600 seconds)
- **Storage**: Client-side (localStorage, sessionStorage, or memory)
- **Format**: `Authorization: Bearer {token}`

## Security Best Practices

### Password Requirements
- Minimum 8 characters
- At least one uppercase letter (A-Z)
- At least one lowercase letter (a-z)
- At least one number (0-9)
- At least one symbol (!@#$%^&*, etc.)

### Rate Limiting
- Login attempts: 5 per minute per IP
- Registration: Standard API rate limit
- Token refresh: No specific limit (use responsibly)

### Token Management
- Store tokens securely (never in localStorage for sensitive apps)
- Refresh tokens before expiration
- Always logout when done
- Never share tokens

## Testing

Run the authentication test suite:
```bash
php artisan test --filter=AuthControllerTest
```

Expected output: 10 tests, 51 assertions, all passing

## Common Issues

### "Invalid credentials"
- Check email and password are correct
- Ensure password meets requirements
- Verify user exists in database

### "Token expired"
- Use the refresh endpoint to get a new token
- Or login again to get a fresh token

### "Unauthenticated"
- Ensure token is included in Authorization header
- Verify token format: `Bearer {token}`
- Check token hasn't expired

## Integration Examples

### JavaScript/Fetch
```javascript
// Login
const response = await fetch('http://localhost:8000/api/auth/login', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    email: 'user@example.com',
    password: 'SecureP@ss123'
  })
});

const data = await response.json();
const token = data.data.access_token;

// Use token
const userResponse = await fetch('http://localhost:8000/api/auth/me', {
  headers: {
    'Authorization': `Bearer ${token}`
  }
});
```

### Axios
```javascript
// Login
const { data } = await axios.post('http://localhost:8000/api/auth/login', {
  email: 'user@example.com',
  password: 'SecureP@ss123'
});

const token = data.data.access_token;

// Configure axios to use token
axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

// Make authenticated requests
const user = await axios.get('http://localhost:8000/api/auth/me');
```

### Python/Requests
```python
import requests

# Login
response = requests.post('http://localhost:8000/api/auth/login', json={
    'email': 'user@example.com',
    'password': 'SecureP@ss123'
})

token = response.json()['data']['access_token']

# Use token
headers = {'Authorization': f'Bearer {token}'}
user_response = requests.get('http://localhost:8000/api/auth/me', headers=headers)
```

## Support

For more details, see:
- [API Documentation](API_DOCUMENTATION.md)
- [Laravel JWT Auth Documentation](https://jwt-auth.readthedocs.io/)
- [Laravel Breeze Documentation](https://laravel.com/docs/10.x/starter-kits#laravel-breeze)
