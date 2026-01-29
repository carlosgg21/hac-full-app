# H.A.C. Renovation

**Sistema de Gestión** para empresa de construcción y renovación. Gestión de clientes, cotizaciones, proyectos y reportes.

[![Versión](https://img.shields.io/badge/versión-1.0.0-blue.svg)](install.html)
[![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4?logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?logo=mysql&logoColor=white)](https://mysql.com)

---

## 📋 Descripción

H.A.C. Renovation es una aplicación web en PHP que permite gestionar:

| Módulo | Descripción |
|--------|-------------|
| **Clientes** | Base de datos de clientes con información de contacto |
| **Cotizaciones** | Generación y seguimiento de cotizaciones |
| **Proyectos** | Gestión de proyectos derivados de cotizaciones aceptadas |
| **Reportes** | Estadísticas y reportes de cotizaciones y proyectos |
| **Preguntas** | Sistema de cuestionarios para evaluación de servicios |
| **Empresa** | Información y configuración de la compañía |

> 📖 **Guía visual**: Para una guía de instalación paso a paso con interfaz gráfica, abre [install.html](install.html) en tu navegador.

---

## ✅ Requisitos del Sistema

### Servidor Web
- **Apache** con `mod_rewrite`
- **PHP** 7.4 o superior
- **Extensiones**: PDO, PDO_MySQL, JSON

### Base de Datos
- **MySQL** 5.7+ o **MariaDB** 10.2+
- phpMyAdmin (recomendado para gestión)

### Entornos recomendados
- **Laragon** (Windows) – recomendado para desarrollo
- **XAMPP** / **WAMP** / **MAMP**

---

## 🚀 Instalación

Sigue estos pasos en orden. La instalación no debería tomar más de **15 minutos**.

### Paso 1: Descargar o clonar el proyecto

**Opción A: Con Git (recomendado)**

```bash
git clone <url-del-repositorio> hac-renovation
cd hac-renovation
```

**Opción B: Descargar ZIP**
1. Descarga el archivo ZIP del repositorio
2. Extrae los archivos en una carpeta
3. Opcional: nombra la carpeta `hac-renovation`

---

### Paso 2: Configurar el servidor web

Coloca el proyecto en el directorio de tu servidor web.

**Laragon (Windows)**
1. Abre Laragon
2. Copia la carpeta del proyecto a `C:\laragon\www\`
3. Inicia Laragon (botón "Start All")
4. Accede a: `http://localhost/hac-renovation/`

**XAMPP / WAMP / MAMP**
1. Copia la carpeta a `htdocs` (XAMPP) o `www` (WAMP/MAMP)
2. Inicia Apache y MySQL desde el panel de control
3. Accede a: `http://localhost/hac-renovation/`

---

### Paso 3: Crear la base de datos

1. Asegúrate de que **MySQL esté corriendo**.
2. Abre **phpMyAdmin** y crea una nueva base de datos:
   - **Nombre:** `hac_renovation`
   - **Intercalación:** `utf8mb4_unicode_ci`
3. Importa el schema:
   - Selecciona la base de datos `hac_renovation`
   - Pestaña **Importar** → Selecciona `backend/database/schema.sql` → **Ejecutar**

Desde línea de comandos:

```bash
mysql -u root -p < backend/database/schema.sql
```

---

### Paso 4: Configurar los archivos

Edita `backend/config/database.php` con tus datos:

```php
'host'     => 'localhost',
'database' => 'hac_renovation',
'username' => 'root',      // Cambia si es necesario
'password' => 'root',      // Cambia si es necesario
```

> ⚠️ Sin esta configuración, el sistema no podrá conectarse a la base de datos.

El archivo `backend/config/config.php` generalmente no necesita cambios (zona horaria, entorno development/production).

---

### Paso 5: Importar datos de prueba (opcional)

Si quieres datos de ejemplo:

- En phpMyAdmin: selecciona `hac_renovation` → **Importar** → `backend/database/fake_data.sql` → **Ejecutar**

O desde línea de comandos:

```bash
mysql -u root -p hac_renovation < backend/database/fake_data.sql
```

---

### Paso 6: Verificar la instalación

1. Abre el navegador y ve a: `http://localhost/hac-renovation/backend/`
2. Deberías ver la página de login o el dashboard
3. Inicia sesión con las credenciales por defecto:
   - **Usuario:** `admin`
   - **Contraseña:** `admin123`

> 🔒 **IMPORTANTE:** Cambia la contraseña del administrador después de la primera sesión, sobre todo en producción.

---

## 📁 Estructura del proyecto

```
hac-renovation/
├── backend/                    # Backend PHP
│   ├── api/                    # Endpoints API REST
│   ├── app/
│   │   ├── Controllers/        # Controladores MVC
│   │   ├── Models/             # Modelos
│   │   ├── Repositories/      # Acceso a datos
│   │   ├── Views/              # Vistas PHP
│   │   └── Helpers/            # Funciones auxiliares
│   ├── config/                 # Configuración
│   ├── core/                   # Router, Database, Auth
│   ├── database/
│   │   ├── schema.sql          # Estructura BD
│   │   └── fake_data.sql       # Datos de prueba
│   ├── public/                 # Assets (CSS, JS, imágenes)
│   ├── index.php               # Punto de entrada
│   └── .htaccess
├── css/                        # Estilos frontend
├── js/                         # JavaScript frontend
├── public/                     # Imágenes y recursos públicos
├── index.html                  # Página principal
├── install.html                # Guía de instalación (visual)
└── README.md
```

---

## 🐛 Solución de problemas

| Problema | Solución |
|----------|----------|
| **No se puede conectar a la base de datos** | Verifica que MySQL esté corriendo, revisa credenciales en `backend/config/database.php` y que exista la BD `hac_renovation`. |
| **404 Not Found o rutas no funcionan** | Comprueba que `mod_rewrite` esté habilitado en Apache y que exista `backend/.htaccess`. Reinicia Apache en Laragon. |
| **La página muestra código PHP** | Verifica que PHP esté instalado y que Apache esté configurado para ejecutar PHP. Reinicia Apache. |
| **Caracteres especiales (acentos, ñ)** | Usa intercalación `utf8mb4` en la BD, guarda archivos PHP en UTF-8 y revisa charset en `database.php`. |

---

## 🛠️ Tecnologías

- **Backend:** PHP 7.4+ (vanilla, sin frameworks)
- **Base de datos:** MySQL 5.7+ / MariaDB 10.2+
- **Frontend:** HTML5, CSS3, JavaScript, Tailwind CSS, Bootstrap Icons
- **Arquitectura:** MVC + Repository Pattern

### API REST

Endpoints en `backend/api/`:

- `auth.php` – Autenticación
- `clients.php` – Clientes
- `quotes.php` – Cotizaciones
- `projects.php` – Proyectos
- `company.php` – Información de empresa
- `reports.php` – Reportes

---

## 📄 Licencia

[Especificar licencia si aplica]

---

**H.A.C. Renovation** – Sistema de Gestión v1.0.0  
Para más información, consulta [install.html](install.html).
