# Guía de Instalación Rápida - ClubesPadel

## Requisitos Previos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor Apache con mod_rewrite habilitado
- Extensiones PHP: PDO, PDO_MySQL, mbstring, json

## Instalación Rápida (5 pasos)

### 1. Descargar o Clonar el Repositorio

```bash
git clone https://github.com/danjohn007/ClubesPadel.git
cd ClubesPadel
```

### 2. Crear la Base de Datos

```sql
CREATE DATABASE clubespadel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Configurar Credenciales de Base de Datos

```bash
cp config/database.example.php config/database.php
```

Edita `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'clubespadel');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');
```

### 4. Importar Esquema y Datos de Ejemplo

```bash
mysql -u tu_usuario -p clubespadel < database/schema.sql
mysql -u tu_usuario -p clubespadel < database/sample_data.sql
```

### 5. Configurar Permisos (Solo Linux/Mac)

```bash
chmod -R 755 .
chmod -R 777 uploads/ temp/ cache/
```

## Verificación de Instalación

Abre tu navegador y accede a:
```
http://localhost/test_connection.php
```

## Iniciar Sesión

### SuperAdmin
- Email: superadmin@clubespadel.com
- Contraseña: admin123

### Admin del Club Demo
- Email: admin@demo.com
- Contraseña: demo123

## Problemas Comunes

### Error de conexión a base de datos
- Verifica que MySQL esté ejecutándose
- Confirma las credenciales en config/database.php

### Error 404 en todas las páginas
- Habilita mod_rewrite: `sudo a2enmod rewrite`
- Reinicia Apache: `sudo systemctl restart apache2`

### URLs no funcionan correctamente
- Verifica que .htaccess exista en la raíz
- Confirma AllowOverride All en la configuración de Apache

## ¿Necesitas Ayuda?

Consulta el README.md para documentación completa o abre un issue en GitHub.
