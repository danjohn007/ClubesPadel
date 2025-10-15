# Guía de Inicio Rápido - ClubesPadel ⚡

## ¡Listo en 5 Minutos!

### Prerequisitos
- XAMPP, WAMP o servidor Apache con PHP 7.4+ y MySQL 5.7+

### Instalación Express

#### 1️⃣ Descargar
```bash
git clone https://github.com/danjohn007/ClubesPadel.git
cd ClubesPadel
```

#### 2️⃣ Crear Base de Datos
Abre phpMyAdmin o tu cliente MySQL favorito:
```sql
CREATE DATABASE clubespadel;
```

#### 3️⃣ Configurar Conexión
```bash
cp config/database.example.php config/database.php
```

Edita `config/database.php` con tus credenciales.

#### 4️⃣ Importar Datos
En phpMyAdmin:
1. Selecciona base de datos `clubespadel`
2. Click en "Importar"
3. Selecciona `database/schema.sql` → Ejecutar
4. Selecciona `database/sample_data.sql` → Ejecutar

#### 5️⃣ ¡Listo!
Abre tu navegador:
```
http://localhost/ClubesPadel/
```

## Primer Login 🔐

### SuperAdmin (Control Total)
```
Email: superadmin@clubespadel.com
Pass: admin123
```

### Admin de Club Demo
```
Email: admin@demo.com
Pass: demo123
```

### Jugador
```
Email: jugador1@demo.com
Pass: demo123
```

## Estructura Básica 📁

```
ClubesPadel/
├── app/
│   ├── Controllers/    # Lógica de negocio
│   ├── Models/         # Acceso a datos
│   ├── Views/          # Interfaces de usuario
│   └── Core/           # Sistema MVC
├── config/             # Configuración
├── database/           # SQL schemas
├── public/             # Assets (CSS, JS)
└── index.php           # Punto de entrada
```

## Rutas Principales 🗺️

```
/                       → Página de inicio
/auth/login            → Iniciar sesión
/auth/register         → Registro
/dashboard             → Dashboard usuario
/superadmin/dashboard  → Dashboard SuperAdmin
/courts                → Gestión de canchas
/reservations          → Reservaciones
/finances              → Módulo financiero
/tournaments           → Torneos
```

## Comandos Útiles 🛠️

### Verificar Instalación
```
http://localhost/ClubesPadel/test_connection.php
```

### Resetear Datos
```bash
mysql -u root clubespadel < database/schema.sql
mysql -u root clubespadel < database/sample_data.sql
```

### Actualizar Sistema
```bash
git pull origin main
```

## Funcionalidades Clave ⚡

### Como SuperAdmin
1. Dashboard → Ver métricas globales
2. Clubes → Gestionar todos los clubes
3. Planes → Administrar suscripciones

### Como Admin de Club
1. Canchas → Agregar/editar canchas
2. Reservaciones → Gestionar bookings
3. Finanzas → Control de ingresos/egresos
4. Torneos → Crear y gestionar torneos
5. Usuarios → Administrar jugadores y staff

### Como Jugador
1. Ver torneos disponibles
2. Inscribirse a torneos
3. Ver historial de reservaciones

## Personalización Rápida 🎨

### Cambiar Nombre del Sistema
En `config/config.php`:
```php
define('APP_NAME', 'Mi Club de Padel');
```

### Cambiar Logo
Edita `app/Views/layouts/header.php`:
```html
<a class="navbar-brand" href="...">
    <img src="tu-logo.png"> Mi Club
</a>
```

### Agregar Nueva Página
1. Crear controller: `app/Controllers/MiController.php`
2. Crear vista: `app/Views/mi/vista.php`
3. Acceder: `http://localhost/ClubesPadel/mi`

## Soporte y Recursos 📚

- 📖 **Documentación Completa**: README.md
- 🚀 **Guía de Instalación**: INSTALL.md
- 🏭 **Despliegue Producción**: DEPLOYMENT.md
- 🤝 **Contribuir**: CONTRIBUTING.md
- 📝 **Cambios**: CHANGELOG.md

## Problemas Comunes 🔧

### ❌ Error de Conexión DB
→ Verifica credenciales en `config/database.php`

### ❌ Página en Blanco
→ Habilita errores en `config/config.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### ❌ URLs no funcionan
→ Verifica que `.htaccess` existe
→ Habilita mod_rewrite en Apache

### ❌ Permisos de Escritura
```bash
chmod -R 777 uploads/ temp/ cache/
```

## Próximos Pasos 🎯

1. ✅ Cambiar contraseñas por defecto
2. ✅ Configurar tu primer club
3. ✅ Agregar canchas
4. ✅ Crear usuarios
5. ✅ Hacer primera reservación
6. ✅ Registrar transacciones financieras
7. ✅ Crear tu primer torneo

## Comunidad 💬

- 🐛 **Reportar Bugs**: GitHub Issues
- 💡 **Sugerencias**: GitHub Discussions
- 🌟 **Star el Proyecto**: Si te gusta, dale una estrella!

---

**¡Disfruta ClubesPadel!** 🎾

Para más información, consulta README.md
