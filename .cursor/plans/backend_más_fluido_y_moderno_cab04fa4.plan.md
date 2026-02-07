---
name: Backend más fluido y moderno
overview: "Plan de mejoras para hacer el backend PHP más fluido y moderno: unificar la API bajo un solo router, añadir capa de validación, Request/Response y manejo de errores centralizado, configuración por entorno (.env), y mejoras opcionales como paginación estándar y Composer/PSR-4."
todos: []
isProject: false
---

# Recomendaciones para un backend más fluido y moderno

El backend actual es PHP custom (MVC + Repository), con API en archivos separados por recurso (`api/clients.php`, `api/projects.php`, etc.) y web manejada por [backend/core/Router.php](backend/core/Router.php). Estas mejoras lo acercan a prácticas actuales sin reescribir todo.

---

## 1. Unificar la API en un solo router

**Problema:** Cada recurso tiene su propio archivo en `api/` que repite: `Auth::requireAuth()`, parsing de `REQUEST_URI`, `REQUEST_METHOD`, `php://input`, y un `switch` por método. Hay duplicación y dos “mundos” (web vs API).

**Recomendación:** Definir todas las rutas API en un único lugar y usar el mismo Router (o un sub-router) para API.

- En [backend/config/routes.php](backend/config/routes.php) (o un nuevo `routes_api.php`) definir rutas como `GET:/api/clients`, `GET:/api/clients/:id`, `POST:/api/clients`, etc.
- En [backend/index.php](backend/index.php), cuando la ruta empiece por `api/`, seguir usando el mismo `Router` pero con un prefijo (ej. quitar `api/` y despachar a controladores que siempre devuelvan JSON para esas rutas).
- Eliminar los archivos individuales de [backend/api/](backend/api/) (clients.php, projects.php, etc.) y que [backend/api/index.php](backend/api/index.php) solo cargue configuración, CORS y luego incluya un único “router API” que lea las rutas y llame a los controladores.

**Resultado:** Un solo punto donde se ven todas las rutas API; menos código repetido; más fácil añadir middleware o versionado (`/api/v1/`).

---

## 2. Objeto Request y uso consistente del body

**Problema:** Los controladores y los endpoints API leen de `$_GET`, `$_POST`, `$_SERVER['REQUEST_URI']` y `file_get_contents('php://input')` repartido por todo el código. Es frágil y difícil de testear.

**Recomendación:** Introducir una clase `Request` (en `core/Request.php`) que se construya una vez al inicio:

- `Request::getMethod()`, `Request::path()`, `Request::query($key)`, `Request::input($key)` (unifica POST + JSON body), `Request::all()`, `Request::bearerToken()`.
- En API, leer el body JSON una sola vez en `Request` y exponerlo como array.
- Pasar `Request` al controlador (o acceder vía `Request::current()` estático) para que no sigan usándose superglobals en la lógica de negocio.

**Resultado:** Lógica más clara, pruebas más fáciles y un solo lugar donde se normaliza el input (incluido soporte para `application/json`).

---

## 3. Validación centralizada

**Problema:** La validación está repartida en controladores con `if (empty(...))` y mensajes ad hoc. No hay formato estándar de errores por campo (útil para el front).

**Recomendación:** Añadir una capa de validación reutilizable:

- Clase `Validator` o usar una librería ligera vía Composer (ej. [Respect/Validation](https://respect-validation.readthedocs.io/) o reglas propias en un array).
- Por recurso (ej. “clients”): definir reglas (required, email, max length, etc.) y mensajes.
- Antes de `store()`/`update()` en el controlador, llamar algo como `Validator::validate($request->all(), ClientRules::store())`. Si falla, responder con `422` y `errors` por campo (ej. `{ "errors": { "email": ["The email field is required."] } }`).

**Resultado:** Validación en un solo lugar, respuestas 422 coherentes y mensajes reutilizables en web y API.

---

## 4. Manejo global de excepciones y errores

**Problema:** No hay un manejador global de excepciones. Las que se lanzan (p. ej. en [backend/core/Database.php](backend/core/Database.php) o en modelos) pueden terminar en pantalla blanca o HTML de error en una petición API.

**Recomendación:**

- Registrar en [backend/index.php](backend/index.php) (y en el punto de entrada API si es distinto) un `set_exception_handler()` que:
  - Capture cualquier `Throwable`.
  - Si la petición es API (por cabecera `Accept: application/json` o por ruta `/api/`): responder con JSON `{ "success": false, "message": "..." }` y código 500 (o 400 si es lógica de negocio).
  - En producción no incluir el mensaje interno en la respuesta; sí registrar en `error_log`.
- Opcional: usar `set_error_handler()` para convertir errores fatales en excepciones y manejarlos igual.

**Resultado:** Respuestas JSON consistentes en errores y mejor seguridad en producción.

---

## 5. Configuración por entorno (.env)

**Problema:** Credenciales y entorno están en [backend/config/config.php](backend/config/config.php) y [backend/config/database.php](backend/config/database.php) (valores fijos o detectados por `REQUEST_URI`). No hay separación clara por entorno y las credenciales suelen estar en el repo.

**Recomendación:**

- Añadir Composer al proyecto (ver punto 7) y usar [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv): cargar `.env` al arranque (solo una vez).
- Definir en `.env`: `APP_ENV`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `APP_DEBUG`, `APP_URL`, etc.
- En `config/database.php` y `config/config.php`, leer de `getenv()` (o `$_ENV`) en lugar de valores por defecto hardcodeados.
- Añadir `.env` al [.gitignore](.gitignore) y commitear un `.env.example` con claves sin valores sensibles.

**Resultado:** Un solo archivo para configurar entorno y credenciales; más seguro y cómodo para desarrollo/producción.

---

## 6. Paginación estándar en la API

**Problema:** Los listados (ej. clientes) no tienen paginación estándar; si crece el volumen, la respuesta será pesada y lenta.

**Recomendación:**

- Aceptar en la API parámetros estándar: `page` (por defecto 1) y `per_page` (por defecto 20, máximo 100). Leerlos desde `Request::query('page')` y `Request::query('per_page')`.
- En [backend/app/Repositories/BaseRepository.php](backend/app/Repositories/BaseRepository.php) añadir un método `paginate($conditions, $orderBy, $page, $perPage)` que devuelva `['data' => [...], 'meta' => ['total' => N, 'current_page' => P, 'per_page' => Q, 'last_page' => L]]`.
- Los endpoints de listado (GET `/api/clients`, etc.) usar este método y responder con esa estructura (ej. `data` + `meta` en el JSON).

**Resultado:** API predecible, menos carga y mejor experiencia en el front al paginar o hacer scroll infinito.

---

## 7. Composer y autoload PSR-4 (opcional pero muy recomendable)

**Problema:** No hay Composer; el autoload en [backend/index.php](backend/index.php) es una lista de rutas sin namespaces. Añadir librerías (validación, dotenv, etc.) y hacer tests es más incómodo.

**Recomendación:**

- Ejecutar `composer init` en `backend/`.
- Configurar PSR-4: por ejemplo namespace `App` para `app/` (Controllers, Models, Repositories, Helpers).
- Mover clases a namespaces (ej. `App\Controllers\ClientController`, `App\Repositories\ClientRepository`) y actualizar `use` en todos los archivos.
- Cargar solo el autoload de Composer (`require __DIR__.'/vendor/autoload.php'`) y quitar el `spl_autoload_register` actual.
- Instalar con Composer: `vlucas/phpdotenv`, y si se añade validación, la librería elegida.

**Resultado:** Código más organizado, dependencias gestionadas y preparado para tests (autoload de `tests/` si se quiere).

---

## 8. Resumen de prioridades


| Prioridad | Mejora                             | Impacto                                 |
| --------- | ---------------------------------- | --------------------------------------- |
| Alta      | Manejo global de excepciones (4)   | Estabilidad y respuesta JSON en errores |
| Alta      | Unificar API en un solo router (1) | Menos duplicación y mantenimiento       |
| Alta      | Objeto Request (2)                 | Código más limpio y predecible          |
| Media     | Validación centralizada (3)        | Mejor DX y respuestas 422 consistentes  |
| Media     | Configuración .env (5)             | Seguridad y entornos                    |
| Media     | Paginación API (6)                 | Escalabilidad y UX                      |
| Baja      | Composer + PSR-4 (7)               | Base para crecer y usar librerías       |


---

## Orden sugerido de implementación

1. **Manejo de excepciones (4)** – Rápido y evita fallos feos en API.
2. **Request (2)** – Base para validación y para no tocar superglobals en controladores.
3. **Unificar API (1)** – Refactor de rutas y eliminación de `api/*.php` por recurso.
4. **Validación (3)** – Usando Request y reglas por recurso.
5. **.env (5)** – Requiere Composer; se puede hacer un “mini Composer” solo para dotenv o introducir Composer aquí.
6. **Paginación (6)** – Sobre el nuevo Request y repositorios.
7. **Composer + PSR-4 (7)** – Se puede hacer antes de .env si se quiere usar Composer para todo (dotenv, validación, etc.).

Si quieres, el siguiente paso puede ser bajar al detalle de una de estas partes (por ejemplo, firma exacta del `Request` y cómo inyectarlo en el Router) o priorizar solo 2–3 mejoras para un primer sprint.