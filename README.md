# H.A.C. Renovation - Sistema de Gestión

Sistema completo de gestión para empresa de construcción y renovación, incluyendo gestión de clientes, cotizaciones, proyectos y reportes.

## 📋 Descripción

H.A.C. Renovation es una aplicación web desarrollada en PHP que permite gestionar:
- **Clientes**: Base de datos de clientes con información de contacto
- **Cotizaciones**: Generación y seguimiento de cotizaciones
- **Proyectos**: Gestión de proyectos derivados de cotizaciones aceptadas
- **Reportes**: Estadísticas y reportes de cotizaciones y proyectos
- **Preguntas**: Sistema de cuestionarios para evaluación de servicios
- **Empresa**: Información y configuración de la compañía

## 🚀 Requisitos del Sistema

### Requisitos Mínimos
- **PHP**: 7.4 o superior
- **MySQL**: 5.7+ o MariaDB 10.2+
- **Apache**: Con mod_rewrite habilitado
- **Extensiones PHP**:
  - PDO
  - PDO_MySQL
  - JSON
  - Session
  - mbstring

### Entornos Recomendados
- **Laragon** (Windows) - Recomendado para desarrollo
- **XAMPP** (Windows/Mac/Linux)
- **WAMP** (Windows)
- **MAMP** (Mac)

## 📦 Instalación

### Paso 1: Clonar o Descargar el Repositorio

```bash
# Si tienes Git instalado
git clone <url-del-repositorio> hac-renovation
cd hac-renovation

# O descarga el ZIP y extrae los archivos
```

### Paso 2: Configurar el Servidor Web

#### Opción A: Laragon (Recomendado para Windows)

1. Abre Laragon
2. Coloca la carpeta del proyecto en `C:\laragon\www\` (o tu directorio configurado)
3. Inicia Laragon (Apache y MySQL)
4. Accede a: `http://localhost/hac-renovation/` o `http://hac-renovation.test/`

#### Opción B: XAMPP/WAMP/MAMP

1. Coloca la carpeta del proyecto en el directorio `htdocs` (XAMPP) o `www` (WAMP/MAMP)
2. Inicia Apache y MySQL desde el panel de control
3. Accede a: `http://localhost/hac-renovation/`

### Paso 3: Crear la Base de Datos

1. Abre phpMyAdmin o tu cliente MySQL preferido
2. Crea una nueva base de datos llamada `hac_renovation`
3. Importa el archivo `backend/database/schema.sql`:
   - En phpMyAdmin: Selecciona la base de datos → Pestaña "Importar" → Selecciona `schema.sql` → Ejecutar

O ejecuta desde la línea de comandos:

```bash
mysql -u root -p < backend/database/schema.sql
```

### Paso 4: Configurar los Archivos

#### 4.1. Configurar Base de Datos

Edita el archivo `backend/config/database.php`:

```php
return [
    'driver' => 'mysql',
    'host' => 'localhost',
    'port' => 3306,
    'database' => 'hac_renovation',
    'username' => 'root',        // Cambia según tu configuración
    'password' => 'root',        // Cambia según tu configuración
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    // ...
];
```

#### 4.2. Configurar Aplicación (Opcional)

Edita `backend/config/config.php` si necesitas cambiar:
- `APP_ENV`: 'development' o 'production'
- Zona horaria: Por defecto 'America/Mexico_City'
- URLs base: Se detectan automáticamente

### Paso 5: Importar Datos de Prueba (Opcional)

Para tener datos de ejemplo en el sistema:

```bash
mysql -u root -p hac_renovation < backend/database/fake_data.sql
```

O desde phpMyAdmin:
- Selecciona la base de datos `hac_renovation`
- Pestaña "Importar" → Selecciona `fake_data.sql` → Ejecutar

### Paso 6: Verificar la Instalación

1. Accede al backend: `http://localhost/hac-renovation/backend/`
2. Deberías ver la página de estado o el login
3. Credenciales por defecto:
   - **Usuario**: `admin`
   - **Contraseña**: `admin123`

> ⚠️ **IMPORTANTE**: Cambia la contraseña del administrador en producción.

## 📁 Estructura del Proyecto

```
hac-renovation/
├── backend/                 # Backend PHP
│   ├── api/                # Endpoints API REST
│   ├── app/
│   │   ├── Controllers/    # Controladores MVC
│   │   ├── Models/        # Modelos de datos
│   │   ├── Repositories/  # Capa de acceso a datos
│   │   ├── Views/         # Vistas PHP
│   │   └── Helpers/       # Funciones auxiliares
│   ├── config/            # Archivos de configuración
│   ├── core/              # Clases core (Router, Database, Auth)
│   ├── database/          # Scripts SQL
│   │   ├── schema.sql     # Estructura de base de datos
│   │   └── fake_data.sql  # Datos de prueba
│   ├── public/            # Archivos públicos (assets)
│   ├── index.php          # Punto de entrada
│   └── .htaccess          # Configuración Apache
├── css/                   # Estilos frontend
├── js/                    # JavaScript frontend
├── public/                # Imágenes y recursos públicos
├── index.html             # Página principal frontend
└── README.md             # Este archivo
```

## ⚙️ Configuración

### Variables de Configuración Importantes

**backend/config/database.php**
- `host`: Servidor de base de datos (default: localhost)
- `database`: Nombre de la base de datos
- `username`: Usuario de MySQL
- `password`: Contraseña de MySQL

**backend/config/config.php**
- `APP_ENV`: Entorno ('development' o 'production')
- `BASE_URL`: URL base (se detecta automáticamente)
- `SESSION_LIFETIME`: Duración de sesión en segundos

### Permisos de Directorios

Asegúrate de que estos directorios tengan permisos de escritura:
- `backend/public/uploads/` (para archivos subidos)
- `backend/logs/` (si existe, para logs de errores)

## 🔐 Credenciales por Defecto

Después de importar `schema.sql`, el usuario administrador es:

- **Username**: `admin`
- **Password**: `admin123`
- **Email**: `admin@hacrenovation.com`

> ⚠️ **Cambia estas credenciales inmediatamente en producción.**

## 🐛 Solución de Problemas

### Error: "No se puede conectar a la base de datos"
- Verifica que MySQL esté corriendo
- Revisa las credenciales en `backend/config/database.php`
- Asegúrate de que la base de datos `hac_renovation` existe

### Error: "404 Not Found" o rutas no funcionan
- Verifica que `mod_rewrite` esté habilitado en Apache
- Revisa el archivo `.htaccess` en `backend/`
- En Laragon, verifica que Apache esté corriendo

### Error: "Class not found"
- Verifica que el autoloader esté funcionando
- Asegúrate de que todas las clases estén en sus directorios correctos
- Revisa los permisos de archivos

### La página muestra código PHP en lugar de ejecutarlo
- Verifica que PHP esté instalado y corriendo
- Revisa la configuración de Apache para archivos PHP
- En Laragon, reinicia Apache

### Problemas con caracteres especiales (acentos, ñ)
- Asegúrate de que la base de datos use `utf8mb4`
- Verifica que los archivos PHP estén guardados en UTF-8
- Revisa la configuración de charset en `database.php`

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 7.4+ (Vanilla PHP, sin frameworks)
- **Base de Datos**: MySQL 5.7+ / MariaDB 10.2+
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Estilos**: Tailwind CSS (CDN)
- **Iconos**: Bootstrap Icons
- **Servidor**: Apache con mod_rewrite
- **Arquitectura**: MVC + Repository Pattern

## 📝 Desarrollo

### Estructura MVC

- **Models**: Lógica de negocio y acceso a datos
- **Repositories**: Acceso directo a la base de datos
- **Controllers**: Manejo de peticiones HTTP
- **Views**: Presentación de datos

### Helpers Disponibles

- `Helper`: Funciones de formateo de fechas
- `JsonHelper`: Funciones para trabajar con JSON (campos MySQL JSON)

### API REST

Los endpoints API están en `backend/api/`:
- `/api/auth.php` - Autenticación
- `/api/clients.php` - Gestión de clientes
- `/api/quotes.php` - Gestión de cotizaciones
- `/api/projects.php` - Gestión de proyectos
- `/api/company.php` - Información de la empresa
- `/api/reports.php` - Reportes

## 📄 Licencia

[Especificar licencia si aplica]

## 👥 Contribución

[Instrucciones de contribución si aplica]

## 📧 Contacto

Para soporte o preguntas sobre la instalación, contacta al equipo de desarrollo.

---

**Versión**: 1.0.0  
**Última actualización**: Enero 2025
