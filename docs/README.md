# H.A.C. Renovation – Docs

## API documentation

- **API.md** – Human-readable API reference (endpoints, auth, responses).
- **openapi.yaml** – OpenAPI 3.0 (Swagger) specification for the same API.

## Swagger UI (interactive docs)

1. Serve the project (e.g. Laragon, `php -S`, or your backend URL).
2. Open in the browser:
   - `https://your-domain/docs/swagger.html`
   - Or, if the project is at `/hac-tests`, something like:  
     `https://your-domain/hac-tests/docs/swagger.html`
3. Swagger UI loads the spec from `openapi.yaml` in the same folder.

**Note:** Opening `docs/swagger.html` directly as a file (`file://`) may fail to load `openapi.yaml` due to browser security. Use a local server or your usual backend URL.

## Base URL for “Try it out”

In Swagger UI, the spec uses a relative server URL `/backend/api`. To call your real API:

1. Use **Servers** at the top and set the dropdown to your base (e.g. `https://hac-tests.test/backend/api`), or
2. Add/change the `servers` entry in `openapi.yaml` to your full API base URL.

Protected endpoints (clients, quotes, projects, reports) require a session: log in first via **POST /auth** (e.g. from another tab or Postman), then use the same browser so the session cookie is sent.
