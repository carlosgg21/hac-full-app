# H.A.C. Renovation – API Documentation

REST API for the H.A.C. Renovation backend. All responses are JSON.

---

## Base URL

```
{BASE}/api
```

- **BASE** is the backend base path (e.g. `/backend` or `/hac-tests/backend`).
- Example: `https://example.com/backend/api/services`

---

## Response format

### Success

```json
{
  "success": true,
  "message": "Description message",
  "data": { ... }
}
```

- `data` is omitted when there is no payload.

### Error

```json
{
  "success": false,
  "message": "Error description",
  "errors": null
}
```

- `errors` may contain validation or extra details when provided.

### HTTP status codes

| Code | Meaning        |
|------|----------------|
| 200  | OK             |
| 201  | Created        |
| 400  | Bad Request    |
| 401  | Unauthorized   |
| 403  | Forbidden      |
| 404  | Not Found      |
| 405  | Method Not Allowed |

---

## Authentication

Endpoints marked **Auth required** expect a valid session (cookie) from a prior login.

### POST `/api/auth` – Login

**Public.** Creates a session.

**Request (JSON or form):**

| Field    | Type   | Required | Description |
|----------|--------|----------|-------------|
| username | string | Yes      | User login  |
| password | string | Yes      | Password    |

**Success (200):**

```json
{
  "success": true,
  "message": "Login successful",
  "data": { "user": { "id", "username", ... } }
}
```

**Errors:** `400` (missing username/password), `401` (invalid credentials).

---

### GET `/api/auth` – Check session

**Public.** Returns current user if authenticated.

**Success (200):** `data.user` with user object.  
**Error:** `401` if not authenticated.

---

### DELETE `/api/auth` – Logout

**Public.** Destroys the current session.

**Success (200):** `"message": "Session closed"`.

---

## Public endpoints (no auth)

### GET `/api/company` – Company info

Returns the first company record and related info (contact, social, stats).

**Success (200):**

```json
{
  "success": true,
  "message": "Company information retrieved",
  "data": {
    "id": 1,
    "name": "...",
    "logo": "...",
    "acronym": "...",
    "slogan": "...",
    "email": "...",
    "phone": "...",
    "address": "...",
    "social_media": { ... },
    "info": { "years_experience", "total_projects", "client_satisfaction", ... }
  }
}
```

**Error:** `404` if no company exists.

---

### GET `/api/services` – Active services

List of active services. Used by the frontend (e.g. quote form).

**Query parameters:**

| Parameter  | Type  | Default              | Description                                      |
|------------|-------|----------------------|--------------------------------------------------|
| fields     | string| `id,name,description`| Comma-separated columns (whitelist in backend).  |
| order_by   | string| `name ASC`           | Sort, e.g. `name ASC`, `created_at DESC`.        |

**Success (200):**

```json
{
  "success": true,
  "message": "Services retrieved",
  "data": [
    { "id": 1, "name": "Service name", "description": "..." }
  ]
}
```

**Allowed columns:** `id`, `name`, `description`, `is_active`, `created_at`, `updated_at`.

---

### GET `/api/questions` – Questions

List of active questions, optionally filtered by service.

**Query parameters:**

| Parameter   | Type  | Description                          |
|-------------|-------|--------------------------------------|
| service_id  | int   | If present, only questions for that service. |

**Success (200):**

```json
{
  "success": true,
  "message": "Questions retrieved",
  "data": [
    { "id", "service_id", "text", "order_index", "is_active", ... }
  ]
}
```

---

### POST `/api/quote-request` – Submit quote request (frontend wizard)

**Public.** Creates a client and a quote in status `request` from the landing-page wizard.

**Request (JSON or form):**

| Field             | Type   | Required | Description                    |
|-------------------|--------|----------|--------------------------------|
| name              | string | Yes      | Full name (split into first/last). |
| email             | string | Yes      | Email                          |
| phone             | string | No       | Phone                          |
| address           | string | No       | Address                        |
| message           | string | No       | Notes / message                |
| project_type      | string | No       | Project type                   |
| portfolio_project | string | No       | Portfolio project name         |
| property_type     | string | No       | Residential / Commercial       |
| square_feet       | string | No       | Area                           |
| budget            | string | No       | Budget range                   |
| timeline          | string | No       | Timeline                       |
| preferred_contact | string | No       | Phone / Email / Text           |
| is_owner          | string | No       | Property owner                 |
| privacy_policy    | string | No       | Privacy acceptance             |
| bathroom_goal     | string | No       | Bathroom wizard                |
| bathroom_layout   | string | No       | Bathroom wizard                |
| kitchen_goal      | string | No       | Kitchen wizard                 |
| kitchen_layout    | string | No       | Kitchen wizard                 |
| city, state, zip_code, country | string | No | Address parts                  |

**Success (201):**

```json
{
  "success": true,
  "message": "Quote request submitted",
  "data": { "id": 123, "client_id": 456 }
}
```

**Errors:** `400` if `name` or `email` is missing.

---

## Protected endpoints (auth required)

All endpoints below require a valid session (login via `POST /api/auth`).

---

### Clients – `/api/clients`

| Method   | Path              | Description        |
|----------|-------------------|--------------------|
| GET      | `/api/clients`    | List clients       |
| GET      | `/api/clients/:id`| One client + quotes|
| POST     | `/api/clients`    | Create client      |
| PUT/PATCH| `/api/clients/:id`| Update client      |
| DELETE   | `/api/clients/:id`| Delete client      |

- **GET list:** Optional query `search` for filtering.
- **POST/PUT body (JSON):** `first_name`, `last_name`, `email`, `phone`, `address`, `city`, `state`, `zip_code`, `country`, `notes`.
- **PUT/DELETE** require `:id` in the path; missing id returns `400` (ID required).

---

### Quotes – `/api/quotes`

| Method   | Path                        | Description          |
|----------|-----------------------------|----------------------|
| GET      | `/api/quotes`               | List quotes          |
| GET      | `/api/quotes/:id`           | One quote            |
| GET      | `/api/quotes/:id/send`      | Send quote (e.g. email) |
| POST     | `/api/quotes`               | Create quote         |
| POST     | `/api/quotes/:id/send`      | Send quote           |
| PUT/PATCH| `/api/quotes/:id`           | Update quote         |
| DELETE   | `/api/quotes/:id`           | Delete quote         |

- **POST create:** Body must include `client_id`; optional `status`, `total_amount`, `currency`, `notes`, `metadata`, `answers` (question answers).
- **PUT/DELETE** require `:id`; missing id returns `400`.

---

### Projects – `/api/projects`

| Method   | Path                  | Description     |
|----------|-----------------------|-----------------|
| GET      | `/api/projects`       | List projects   |
| GET      | `/api/projects/:id`   | One project     |
| POST     | `/api/projects`       | Create project  |
| PUT/PATCH| `/api/projects/:id`   | Update project  |

- **POST create:** Requires `quote_id` (and related fields as per controller).
- **PUT** requires `:id` in path; missing id returns `400`.

---

### Reports – `/api/reports`

| Method | Path                    | Description      |
|--------|-------------------------|------------------|
| GET    | `/api/reports`          | Reports index    |
| GET    | `/api/reports/quotes`   | Quotes report    |
| GET    | `/api/reports/projects` | Projects report  |

- **GET** only. Other methods return `405`.

---

## CORS

Allowed methods: `GET`, `POST`, `PUT`, `DELETE`, `OPTIONS`.  
Allowed headers: `Content-Type`, `Authorization`.  
Origins are configured in `config/config.php` (`CORS_ALLOWED_ORIGINS`).

---

## Summary table

| Endpoint           | Auth   | GET | POST | PUT/PATCH | DELETE |
|--------------------|--------|-----|------|-----------|--------|
| `/api/auth`        | No     | ✓   | ✓    | –         | ✓      |
| `/api/company`     | No     | ✓   | –    | –         | –      |
| `/api/services`    | No     | ✓   | –    | –         | –      |
| `/api/questions`   | No     | ✓   | –    | –         | –      |
| `/api/quote-request` | No   | –   | ✓    | –         | –      |
| `/api/clients`     | Yes    | ✓   | ✓    | ✓         | ✓      |
| `/api/quotes`      | Yes    | ✓   | ✓    | ✓         | ✓      |
| `/api/projects`    | Yes    | ✓   | ✓    | ✓         | –      |
| `/api/reports`     | Yes    | ✓   | –    | –         | –      |

---

*Last updated from backend API implementation.*
