# API Endpoints Documentation

## User Management

### Get All Users
- **Endpoint:** `GET /users`
- **Authentication:** Required (Bearer Token)
- **Expected Parameters:** None
- **Example Response:**
  ```json
  {
    "data": [
      {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "company": "Example Corp",
        "deal_stage": "Lead",
        "publisher_id": 123,
        "is_active": true,
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z"
      }
    ]
  }
  ```

### Create User (Admin Only)
- **Endpoint:** `POST /users`
- **Authentication:** Required (Bearer Token)
- **Expected Parameters:**
  - `name` (string, required)
  - `email` (string, required, unique)
  - `password` (string, required)
  - `company` (string, required)
  - `deal_stage` (string, required)
  - `publisher_id` (integer, optional)
- **Example Response:**
  ```json
  {
    "data": {
      "id": 2,
      "name": "Jane Doe",
      "email": "jane@example.com",
      "company": "Jane Corp",
      "deal_stage": "Prospect",
      "publisher_id": 456,
      "is_active": true,
      "created_at": "2023-01-02T00:00:00.000000Z",
      "updated_at": "2023-01-02T00:00:00.000000Z"
    }
  }
  ```

### Update User (Admin Only)
- **Endpoint:** `PUT /users/{user}`
- **Authentication:** Required (Bearer Token)
- **Expected Parameters:**
  - `name` (string, optional)
  - `email` (string, optional)
  - `company` (string, optional)
  - `deal_stage` (string, optional)
  - `publisher_id` (integer, optional)
- **Example Response:**
  ```json
  {
    "data": {
      "id": 1,
      "name": "John Updated",
      "email": "john@example.com",
      "company": "Updated Corp",
      "deal_stage": "Customer",
      "publisher_id": 123,
      "is_active": true,
      "created_at": "2023-01-01T00:00:00.000000Z",
      "updated_at": "2023-01-03T00:00:00.000000Z"
    }
  }
  ```

### Update User by HubSpot
- **Endpoint:** `POST /users/hubspot-update`
- **Authentication:** Required (Bearer Token)
- **Expected Parameters:**
  - `email` (string, required)
  - `name` (string, required)
  - `company` (string, required)
  - `deal_stage` (string, required)
- **Example Response:**
  ```json
  {
    "data": {
      "id": 1,
      "name": "John HubSpot",
      "email": "john@example.com",
      "company": "HubSpot Corp",
      "deal_stage": "Lead",
      "publisher_id": 123,
      "is_active": true,
      "created_at": "2023-01-01T00:00:00.000000Z",
      "updated_at": "2023-01-04T00:00:00.000000Z"
    }
  }
  ``` 