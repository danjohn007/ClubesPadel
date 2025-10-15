# Guía de Configuración Completa - ClubesPadel

Esta guía te ayudará a configurar el sistema ClubesPadel desde cero con datos de ejemplo completos para pruebas y demostraciones.

## 📋 Requisitos Previos

- **PHP:** 7.4 o superior
- **MySQL:** 5.7 o superior (o MariaDB 10.2+)
- **Apache:** 2.4 o superior con mod_rewrite habilitado
- **Compositor:** (Opcional, para futuras dependencias)

## 🚀 Instalación Rápida (5 Minutos)

### Paso 1: Clonar el Repositorio

```bash
git clone https://github.com/danjohn007/ClubesPadel.git
cd ClubesPadel
```

### Paso 2: Configurar la Base de Datos

```bash
# Crear la base de datos
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS clubespadel DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Crear el esquema
mysql -u root -p clubespadel < database/schema.sql

# Cargar datos de ejemplo completos (RECOMENDADO)
mysql -u root -p clubespadel < database/enhanced_sample_data.sql
```

### Paso 3: Configurar las Credenciales

```bash
# Copiar el archivo de configuración de ejemplo
cp config/database.example.php config/database.php

# Editar con tus credenciales
nano config/database.php
```

Actualiza las credenciales de la base de datos:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'clubespadel');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');
```

### Paso 4: Configurar Apache

#### Opción A: Configuración Automática (.htaccess incluido)

El proyecto ya incluye un archivo `.htaccess` configurado. Solo asegúrate de que mod_rewrite esté habilitado:

```bash
# En Ubuntu/Debian
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### Opción B: Configuración Manual del VirtualHost

```apache
<VirtualHost *:80>
    ServerName clubespadel.local
    DocumentRoot /ruta/a/ClubesPadel
    
    <Directory /ruta/a/ClubesPadel>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/clubespadel_error.log
    CustomLog ${APACHE_LOG_DIR}/clubespadel_access.log combined
</VirtualHost>
```

Luego agrega a `/etc/hosts`:
```
127.0.0.1   clubespadel.local
```

### Paso 5: Verificar la Instalación

Abre tu navegador y visita:
```
http://localhost/ClubesPadel/test_connection.php
```

O si configuraste VirtualHost:
```
http://clubespadel.local/test_connection.php
```

Deberías ver:
- ✅ Conexión a base de datos exitosa
- ✅ URL base detectada correctamente
- ✅ Listado de tablas creadas

## 🔐 Credenciales de Acceso

Una vez instalado, puedes acceder con estas cuentas de prueba:

### SuperAdmin (Acceso Total)
```
URL: http://localhost/ClubesPadel/auth/login
Email: superadmin@clubespadel.com
Contraseña: admin123
```
**Acceso a:**
- Dashboard de SuperAdmin
- Gestión de todos los clubes
- Planes de suscripción
- Pagos y reportes globales

### Administrador del Club Demo
```
Email: admin@demo.com
Contraseña: demo123
```
**Acceso a:**
- Dashboard del club
- Gestión de canchas
- Gestión de reservaciones
- Módulo financiero
- Gestión de torneos
- Configuración del club

### Recepcionista del Club Demo
```
Email: recepcion@demo.com
Contraseña: demo123
```
**Acceso a:**
- Crear/modificar reservaciones
- Ver calendario de canchas
- Gestión de jugadores

### Jugadores del Club Demo
```
Email: jugador1@demo.com
Contraseña: demo123

Email: jugador2@demo.com
Contraseña: demo123

Email: jugador3@demo.com
Contraseña: demo123
```
**Acceso a:**
- Ver canchas disponibles
- Hacer reservaciones
- Inscribirse a torneos
- Ver su perfil

## 📊 Explorando el Sistema

### Dashboard SuperAdmin

Después de iniciar sesión como SuperAdmin:

1. **Estadísticas Generales:**
   - Total de clubes registrados
   - Clubes activos vs en prueba
   - Ingresos mensuales del sistema
   
2. **Gráficas en Tiempo Real:**
   - Crecimiento de clubes (últimos 6 meses)
   - Distribución por plan de suscripción
   
3. **Gestión de Clubes:**
   - Ver todos los clubes
   - Activar/suspender clubes
   - Ver detalles de suscripción

### Dashboard del Club

Después de iniciar sesión como admin del club:

1. **Estadísticas del Día:**
   - Reservaciones de hoy
   - Ingresos del mes
   - Miembros activos
   - Torneos activos
   
2. **Gráficas Financieras:**
   - Ingresos vs Gastos (últimos 6 meses)
   - Reservaciones por cancha (último mes)
   
3. **Reservaciones Recientes:**
   - Lista de últimas 5 reservaciones
   - Estado de pago
   - Cancha asignada

### Módulos Disponibles

#### 1. Gestión de Canchas
```
URL: /courts
```
- Ver todas las canchas del club
- Crear nuevas canchas
- Editar características (tipo, superficie, precio)
- Cambiar estado (disponible, mantenimiento)

#### 2. Reservaciones
```
URL: /reservations
```
- Calendario visual de reservaciones
- Crear nuevas reservaciones
- Ver historial
- Cancelar reservaciones
- Gestionar pagos

#### 3. Finanzas
```
URL: /finances
```
- Registro de ingresos
- Registro de gastos
- Categorías personalizables
- Reportes mensuales/anuales
- Balance general

#### 4. Torneos
```
URL: /tournaments
```
- Crear torneos
- Gestionar inscripciones
- Programar partidos
- Registrar resultados
- Ver estadísticas

## 🧪 Datos de Prueba Incluidos

El archivo `enhanced_sample_data.sql` incluye:

### 8 Clubes de Ejemplo
- 3 clubes activos con diferentes planes
- 2 clubes en período de prueba
- 1 club suspendido
- Diferentes ciudades de México

### 30+ Usuarios
- 1 SuperAdmin
- 8 administradores de club
- Recepcionistas
- Entrenadores
- 20+ jugadores con diferentes niveles

### 19 Canchas
- Canchas indoor y outdoor
- Diferentes superficies
- Precios variados
- Con y sin iluminación

### 200+ Reservaciones
- Distribuidas en los últimos 6 meses
- Diferentes estados (pendiente, confirmada, completada)
- Horarios variados
- Múltiples usuarios

### 5 Torneos
- Torneos completados (histórico)
- Torneos con inscripciones abiertas
- Torneos próximos
- Diferentes formatos (eliminación, round robin)

### 100+ Transacciones Financieras
- Ingresos por reservaciones
- Ingresos por membresías
- Gastos operativos
- Datos de 6 meses para gráficas

## 📈 Verificando las Gráficas

### Dashboard SuperAdmin - Gráficas

1. **Crecimiento de Clubes:**
   - Muestra nuevos clubes por mes
   - Datos de últimos 6 meses
   - Línea de tendencia

2. **Distribución por Plan:**
   - Gráfica de dona (doughnut)
   - Porcentaje por plan
   - Básico, Profesional, Premium

### Dashboard del Club - Gráficas

1. **Ingresos vs Gastos:**
   - Gráfica de líneas comparativa
   - Últimos 6 meses
   - Formato de moneda (MXN)

2. **Reservaciones por Cancha:**
   - Gráfica de barras horizontal
   - Top 8 canchas más reservadas
   - Último mes de actividad

## 🔍 Pruebas Recomendadas

### Prueba 1: Crear una Reservación

1. Inicia sesión como admin o recepcionista
2. Ve a "Reservaciones" → "Nueva Reservación"
3. Selecciona:
   - Fecha: Mañana
   - Cancha: Cancha Central
   - Hora: 18:00 - 19:30
   - Jugador: Cualquier jugador existente
4. Guarda la reservación
5. Verifica que aparezca en el dashboard

### Prueba 2: Registrar un Ingreso

1. Inicia sesión como admin
2. Ve a "Finanzas" → "Nuevo Ingreso"
3. Ingresa:
   - Categoría: Reservaciones
   - Monto: 300.00
   - Descripción: Reserva de prueba
   - Método de pago: Efectivo
4. Guarda el ingreso
5. Verifica que se actualice en el dashboard

### Prueba 3: Ver Estadísticas

1. Inicia sesión como SuperAdmin
2. Ve al Dashboard SuperAdmin
3. Verifica las gráficas:
   - Deben mostrar datos reales
   - Crecimiento de clubes
   - Distribución por planes
4. Cambia a admin del club
5. Verifica las gráficas del club:
   - Ingresos vs Gastos
   - Reservaciones por cancha

## 🐛 Solución de Problemas

### Problema: Error 404 en todas las páginas

**Solución:**
```bash
# Verificar que mod_rewrite esté habilitado
sudo a2enmod rewrite
sudo systemctl restart apache2

# Verificar que .htaccess existe
ls -la .htaccess

# Verificar AllowOverride en Apache config
# Debe ser "AllowOverride All" no "AllowOverride None"
```

### Problema: "Database connection failed"

**Solución:**
```bash
# Verificar que MySQL esté corriendo
sudo systemctl status mysql

# Verificar credenciales en config/database.php
nano config/database.php

# Probar conexión manualmente
mysql -u tu_usuario -p -e "USE clubespadel; SELECT COUNT(*) FROM users;"
```

### Problema: Gráficas no se muestran

**Solución:**
```bash
# 1. Verificar que Chart.js esté cargado
# Abre el navegador, presiona F12, ve a la consola
# No debe haber errores de "Chart is not defined"

# 2. Verificar datos en la base de datos
mysql -u root -p clubespadel -e "SELECT COUNT(*) FROM reservations;"

# 3. Limpiar caché del navegador
# Ctrl+Shift+Delete (Chrome/Firefox)
```

### Problema: No puedo iniciar sesión

**Solución:**
```bash
# Verificar que el usuario existe en la base de datos
mysql -u root -p clubespadel -e "SELECT email, role FROM users WHERE email = 'admin@demo.com';"

# Si no existe, volver a cargar los datos
mysql -u root -p clubespadel < database/enhanced_sample_data.sql
```

### Problema: Permisos de archivos (Linux)

**Solución:**
```bash
# Dar permisos correctos
sudo chown -R www-data:www-data /ruta/a/ClubesPadel
sudo chmod -R 755 /ruta/a/ClubesPadel
sudo chmod -R 777 /ruta/a/ClubesPadel/uploads
```

## 🔄 Actualizar Datos de Prueba

Si quieres volver a cargar los datos de ejemplo:

```bash
# Esto eliminará los datos actuales y cargará nuevos
mysql -u root -p clubespadel < database/enhanced_sample_data.sql
```

**⚠️ ADVERTENCIA:** Esto eliminará TODOS los datos actuales en la base de datos.

## 📚 Documentación Adicional

- [README.md](README.md) - Información general del proyecto
- [FIXES.md](FIXES.md) - Correcciones implementadas
- [database/README.md](database/README.md) - Guía de scripts de base de datos
- [CHANGELOG.md](CHANGELOG.md) - Historial de cambios

## 🎯 Próximos Pasos

1. **Explorar todos los módulos** del sistema
2. **Crear tu propio club** de prueba
3. **Personalizar** canchas y horarios
4. **Probar** el sistema de torneos
5. **Revisar** los reportes financieros
6. **Configurar** notificaciones

## 💡 Consejos

- Usa **SuperAdmin** para ver la vista global del sistema
- Usa **admin del club** para gestión diaria
- Los datos de ejemplo son realistas para demos
- Puedes eliminar clubes/usuarios de prueba cuando estés listo
- Las gráficas se actualizan automáticamente con nuevos datos

## 📞 Soporte

Si encuentras problemas:
1. Revisa esta guía
2. Consulta [FIXES.md](FIXES.md)
3. Verifica los logs de Apache: `tail -f /var/log/apache2/error.log`
4. Verifica los logs de MySQL: `tail -f /var/log/mysql/error.log`
5. Crea un issue en GitHub con detalles del error

---

**¡Listo!** Ahora tienes un sistema ClubesPadel completamente funcional con datos de prueba realistas. 🎉
