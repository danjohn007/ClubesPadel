# Corrección de Errores: URLs de Autenticación y APP_PATH

## Problemas Identificados

### 1. Error Fatal: APP_PATH Indefinido
**Error:**
```
PHP Fatal error: Uncaught Error: Undefined constant "APP_PATH" in /home2/arosports/public_html/admin/3/app/Views/dashboard/index.php:1
```

**Causa:**
Varios archivos de vista estaban usando la constante `APP_PATH` directamente sin verificar primero si estaba definida. Esto causaba errores cuando las vistas se accedían directamente sin pasar por el router principal.

**Solución:**
Se agregó la verificación de seguridad al inicio de los siguientes archivos de vista:
- `app/Views/dashboard/index.php`
- `app/Views/finances/index.php`
- `app/Views/reservations/index.php`
- `app/Views/tournaments/index.php`
- `app/Views/courts/create.php`
- `app/Views/courts/index.php`
- `app/Views/superadmin/plans.php`
- `app/Views/superadmin/dashboard.php`
- `app/Views/superadmin/clubs.php`

**Código agregado:**
```php
<?php 
// Ensure configuration is loaded
if (!defined('APP_PATH')) {
    require_once __DIR__ . '/../../../config/config.php';
}
require_once APP_PATH . '/Views/layouts/header.php'; 
?>
```

### 2. URLs Incorrectas de Autenticación (auth/auth/login)

**Problema:**
Los enlaces de login y registro estaban mostrando rutas incorrectas como `auth/auth/login` y `auth/auth/register` en lugar de `auth/login` y `auth/register`.

**Causa:**
El archivo `.htaccess` tenía configurado `RewriteBase /` de forma estática, lo cual causaba problemas cuando la aplicación estaba instalada en un subdirectorio (como `/admin/3/`). Esto hacía que Apache manejara incorrectamente las reescrituras de URL.

**Solución:**
Se comentó la línea `RewriteBase /` en el archivo `.htaccess` para permitir que Apache detecte automáticamente el directorio base correcto. Se agregó documentación clara sobre cómo configurar el RewriteBase para instalaciones en subdirectorios.

**Cambio en .htaccess:**
```apache
# Set base directory automatically
# RewriteBase should be set to the subdirectory where the app is installed
# For root installation: RewriteBase /
# For subdirectory: RewriteBase /subdirectory/
# Leave commented to auto-detect
# RewriteBase /
```

## Configuración para Instalaciones en Subdirectorios

Si la aplicación está instalada en un subdirectorio (por ejemplo, `/admin/3/`), asegúrate de:

1. **Opción A: Dejar RewriteBase comentado (Recomendado)**
   - Apache detectará automáticamente el directorio base
   - No requiere configuración manual

2. **Opción B: Configurar RewriteBase manualmente**
   - Descomentar y configurar el RewriteBase en `.htaccess`:
   ```apache
   RewriteBase /admin/3/
   ```

## Verificación

Para verificar que las URLs se están generando correctamente:

1. Accede a `test_urls.php` en tu navegador:
   ```
   http://tudominio.com/admin/3/test_urls.php
   ```

2. Verifica que las URLs mostradas sean correctas:
   - Login: `http://tudominio.com/admin/3/auth/login` ✓
   - Register: `http://tudominio.com/admin/3/auth/register` ✓
   - NO debe mostrar: `auth/auth/login` ✗

3. Prueba los enlaces de autenticación en la interfaz:
   - Click en "Iniciar Sesión" en el navbar
   - Click en "Registrarse" en el navbar
   - Verifica que las URLs en la barra de direcciones sean correctas

## Archivos Modificados

### Archivos de Vista (9 archivos)
1. `app/Views/dashboard/index.php`
2. `app/Views/finances/index.php`
3. `app/Views/reservations/index.php`
4. `app/Views/tournaments/index.php`
5. `app/Views/courts/create.php`
6. `app/Views/courts/index.php`
7. `app/Views/superadmin/plans.php`
8. `app/Views/superadmin/dashboard.php`
9. `app/Views/superadmin/clubs.php`

### Archivos de Configuración
1. `.htaccess` - RewriteBase comentado y documentado

### Archivos de Prueba (Nuevos)
1. `test_urls.php` - Página de prueba para verificar la configuración de URLs

## Notas Adicionales

- Todos los archivos modificados pasan la validación de sintaxis PHP
- Las URLs en el código ya estaban correctamente implementadas usando `URL_BASE . '/auth/login'`
- El problema era específicamente de la configuración de Apache/htaccess
- La constante `URL_BASE` se calcula automáticamente en `config/config.php` basándose en `$_SERVER` variables

## Prevención de Problemas Futuros

1. **Para nuevas vistas:**
   - Siempre incluir la verificación de `APP_PATH` al inicio del archivo
   - Usar el siguiente patrón:
   ```php
   <?php 
   if (!defined('APP_PATH')) {
       require_once __DIR__ . '/../../../config/config.php';
   }
   require_once APP_PATH . '/Views/layouts/header.php'; 
   ?>
   ```

2. **Para URLs:**
   - Siempre usar `URL_BASE` para construir URLs absolutas
   - Formato: `<?php echo URL_BASE; ?>/ruta/deseada`
   - Nunca usar rutas relativas para enlaces de navegación principales

3. **Para despliegues:**
   - Verificar que `.htaccess` esté presente en el directorio raíz de la aplicación
   - Si la instalación es en subdirectorio, configurar o comentar RewriteBase apropiadamente
   - Probar `test_urls.php` después del despliegue para verificar la configuración
