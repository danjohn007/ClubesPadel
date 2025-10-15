# ClubesPadel - Sistema de Administración de Clubes de Pádel

![ClubesPadel](https://img.shields.io/badge/version-1.0.0-blue)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple)
![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-blueviolet)

Sistema SaaS completo para la gestión de clubes de pádel. Incluye módulos de administración de canchas, reservaciones, finanzas, torneos y más.

## 🚀 Características Principales

### SuperAdmin (Nivel SaaS)
- ✅ Registro y gestión de clubes (multi-tenant)
- ✅ Gestión de planes de suscripción (Básico, Profesional, Premium)
- ✅ Dashboard con métricas globales
- ✅ Control de pagos y facturación
- ✅ Reportes globales del sistema

### Módulo de Club (Tenant)
- 🧍‍♂️ **Gestión de Usuarios**: Roles (admin, recepcionista, entrenador, jugador)
- 🏟️ **Gestión de Canchas**: Alta, edición, horarios, precios
- 📅 **Reservaciones**: Sistema con calendario visual
- 🧾 **Módulo Financiero**: Ingresos, egresos, reportes contables
- 💳 **Pagos**: Integración con pasarelas de pago
- 🏆 **Torneos**: Organización y seguimiento de competencias
- 📨 **Comunicación**: Notificaciones y mensajería

## 📋 Requisitos del Sistema

- **PHP**: 7.4 o superior
- **MySQL**: 5.7 o superior
- **Servidor Web**: Apache 2.4+ con mod_rewrite habilitado
- **Extensiones PHP requeridas**:
  - PDO
  - PDO_MySQL
  - mbstring
  - json

## 🔧 Instalación

### 1. Clonar o Descargar el Repositorio

```bash
git clone https://github.com/danjohn007/ClubesPadel.git
cd ClubesPadel
```

### 2. Configurar el Servidor Web (Apache)

El sistema está diseñado para funcionar en cualquier directorio de Apache. La configuración de URL base se detecta automáticamente.

#### Opción A: Directorio raíz del servidor
Coloca los archivos en `/var/www/html/` (Linux) o `C:\xampp\htdocs\` (Windows/XAMPP)

#### Opción B: Subdirectorio
Coloca los archivos en cualquier subdirectorio, por ejemplo:
- `/var/www/html/clubespadel/`
- `C:\xampp\htdocs\clubespadel\`

El sistema detectará automáticamente la URL base.

### 3. Configurar la Base de Datos

#### a) Crear la base de datos

```sql
CREATE DATABASE clubespadel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### b) Configurar las credenciales

```bash
cp config/database.example.php config/database.php
```

Edita `config/database.php` con tus credenciales:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'clubespadel');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');
```

#### c) Importar el esquema de la base de datos

```bash
mysql -u tu_usuario -p clubespadel < database/schema.sql
```

#### d) Importar datos de ejemplo (opcional)

```bash
mysql -u tu_usuario -p clubespadel < database/sample_data.sql
```

### 4. Configurar Permisos (Linux/Mac)

```bash
chmod -R 755 .
chmod -R 777 uploads/
chmod -R 777 temp/
chmod -R 777 cache/
```

### 5. Habilitar mod_rewrite en Apache

#### Ubuntu/Debian:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### En el archivo de configuración del sitio, asegúrate de tener:
```apache
<Directory /var/www/html/clubespadel>
    AllowOverride All
</Directory>
```

### 6. Verificar la Instalación

Abre tu navegador y accede a:

```
http://localhost/test_connection.php
```

Este archivo verificará:
- ✅ Conexión a la base de datos
- ✅ Detección correcta de URL base
- ✅ Tablas creadas correctamente
- ✅ Configuración del sistema

## 🎯 Uso del Sistema

### Acceso Inicial

Una vez instalado, accede a la aplicación:

```
http://localhost/
```

O si está en un subdirectorio:

```
http://localhost/clubespadel/
```

### Cuentas de Prueba

El sistema incluye datos de ejemplo con las siguientes cuentas:

#### SuperAdmin (Gestión SaaS)
- **Email**: superadmin@clubespadel.com
- **Contraseña**: admin123

#### Administrador de Club
- **Email**: admin@demo.com
- **Contraseña**: demo123

#### Recepcionista
- **Email**: recepcion@demo.com
- **Contraseña**: demo123

#### Jugador
- **Email**: jugador1@demo.com
- **Contraseña**: demo123

## 📁 Estructura del Proyecto

```
ClubesPadel/
├── app/
│   ├── Controllers/      # Controladores MVC
│   ├── Core/            # Clases core (Database, Router, Controller)
│   ├── Models/          # Modelos de datos
│   └── Views/           # Vistas (HTML/PHP)
│       ├── auth/        # Vistas de autenticación
│       ├── dashboard/   # Dashboard principal
│       ├── home/        # Página de inicio
│       ├── layouts/     # Layouts compartidos
│       └── superadmin/  # Panel SuperAdmin
├── config/
│   ├── config.php           # Configuración principal
│   └── database.example.php # Ejemplo de configuración DB
├── database/
│   ├── schema.sql          # Esquema de base de datos
│   └── sample_data.sql     # Datos de ejemplo
├── public/
│   └── assets/            # CSS, JS, imágenes
├── uploads/               # Archivos subidos
├── temp/                  # Archivos temporales
├── .htaccess             # Reglas de reescritura Apache
├── index.php             # Punto de entrada
├── test_connection.php   # Test de conexión
└── README.md            # Este archivo
```

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP puro (sin frameworks)
- **Base de Datos**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework CSS**: Bootstrap 5.3
- **Iconos**: Bootstrap Icons
- **Gráficas**: Chart.js
- **Calendario**: FullCalendar.js (implementación futura)
- **Arquitectura**: MVC (Model-View-Controller)

## 🔐 Seguridad

El sistema implementa las siguientes medidas de seguridad:

- ✅ Autenticación con `password_hash()` y `password_verify()`
- ✅ Sesiones seguras PHP
- ✅ Protección contra SQL Injection (PDO con prepared statements)
- ✅ Protección XSS con `htmlspecialchars()`
- ✅ CSRF tokens (implementar en producción)
- ✅ Headers de seguridad HTTP
- ✅ Aislamiento multi-tenant (cada club solo ve sus datos)

## 📊 Módulos Implementados

### ✅ Completados
- [x] Estructura MVC
- [x] Sistema de autenticación
- [x] Panel SuperAdmin
- [x] Dashboard de club
- [x] Gestión de usuarios
- [x] Base de datos completa
- [x] Detección automática de URL base
- [x] Test de conexión

### 🚧 En Desarrollo
- [ ] CRUD completo de canchas
- [ ] Sistema de reservaciones con calendario
- [ ] Módulo financiero completo
- [ ] Gestión de torneos
- [ ] Sistema de notificaciones
- [ ] Integración de pagos
- [ ] Reportes PDF/Excel

## 🔄 URLs Amigables

El sistema utiliza URLs amigables:

```
http://localhost/                    # Página de inicio
http://localhost/auth/login         # Login
http://localhost/dashboard          # Dashboard del usuario
http://localhost/superadmin/dashboard # Dashboard SuperAdmin
http://localhost/courts             # Gestión de canchas
http://localhost/reservations       # Reservaciones
```

## 🐛 Solución de Problemas

### Error: "Database connection failed"
- Verifica las credenciales en `config/database.php`
- Asegúrate de que MySQL esté ejecutándose
- Confirma que la base de datos existe

### Error 404 en todas las páginas excepto index.php
- Habilita `mod_rewrite` en Apache
- Verifica que `.htaccess` esté presente
- Confirma que `AllowOverride All` esté configurado

### Las URLs no funcionan correctamente
- Ejecuta `test_connection.php` para verificar la URL base
- Revisa la configuración de `RewriteBase` en `.htaccess`

## 📝 Licencia

Este proyecto es de código abierto y está disponible bajo la licencia MIT.

## 👥 Contribuciones

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📞 Soporte

Para soporte o preguntas:
- **Email**: info@clubespadel.com
- **Issues**: [GitHub Issues](https://github.com/danjohn007/ClubesPadel/issues)

## 🎓 Documentación Adicional

Para más información sobre los módulos y funcionalidades, consulta:
- [Documentación de API](docs/API.md) (próximamente)
- [Guía de Usuario](docs/USER_GUIDE.md) (próximamente)
- [Guía de Desarrollo](docs/DEVELOPMENT.md) (próximamente)

---

**ClubesPadel** - Sistema de Administración de Clubes de Pádel v1.0.0
