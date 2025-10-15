# Database Scripts - ClubesPadel

Este directorio contiene todos los scripts SQL necesarios para configurar y poblar la base de datos del sistema ClubesPadel.

## Archivos Disponibles

### 1. `schema.sql`
**Descripción:** Script de esquema completo de la base de datos.

**Contenido:**
- Estructura de todas las tablas del sistema
- Relaciones y claves foráneas
- Índices para optimización
- Restricciones de integridad

**Uso:**
```bash
mysql -u tu_usuario -p < schema.sql
```

**Tablas incluidas:**
- `subscription_plans` - Planes de suscripción
- `clubs` - Clubes registrados (multi-tenant)
- `users` - Usuarios del sistema
- `courts` - Canchas de pádel
- `reservations` - Reservaciones de canchas
- `tournaments` - Torneos y competencias
- `income_transactions` / `expense_transactions` - Transacciones financieras
- `notifications` - Sistema de notificaciones
- `activity_log` - Registro de actividades
- Y más...

### 2. `sample_data.sql`
**Descripción:** Datos de ejemplo básicos para pruebas iniciales.

**Contenido:**
- 3 clubes de ejemplo
- Usuarios básicos (admin, jugadores)
- Algunas canchas
- Reservaciones de muestra
- Datos financieros mínimos

**Uso:**
```bash
mysql -u tu_usuario -p clubespadel < sample_data.sql
```

### 3. `enhanced_sample_data.sql` ⭐ **RECOMENDADO**
**Descripción:** Datos de ejemplo extensos y realistas para demostraciones y pruebas completas.

**Contenido:**
- **8 clubes** con diferentes estados (activos, en prueba, suspendidos)
- **30+ usuarios** distribuidos en diferentes roles y clubes
- **19 canchas** con diversas características
- **200+ reservaciones** de los últimos 6 meses
- **5 torneos** en diferentes estados
- **100+ transacciones financieras** (ingresos y gastos)
- **Pagos de suscripción** históricos
- **Configuraciones** de clubes
- **Logs de actividad** del sistema
- **Notificaciones** de ejemplo

**Características especiales:**
- Datos distribuidos en los últimos 6 meses para gráficas realistas
- Múltiples tipos de usuarios (admin, recepcionista, entrenador, jugadores)
- Reservaciones con diferentes estados (pendiente, confirmada, completada)
- Transacciones financieras variadas para reportes
- Torneos en diferentes fases (próximos, en registro, completados)

**Uso:**
```bash
# Primero ejecuta el esquema si aún no lo has hecho
mysql -u tu_usuario -p < schema.sql

# Luego carga los datos mejorados
mysql -u tu_usuario -p < enhanced_sample_data.sql
```

**⚠️ IMPORTANTE:** Este script limpia todos los datos existentes antes de insertar los nuevos datos. No lo ejecutes en producción.

## Guía de Instalación Completa

### Opción 1: Instalación Nueva (Recomendado)

```bash
# 1. Crear la base de datos
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS clubespadel DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 2. Crear el esquema
mysql -u root -p clubespadel < schema.sql

# 3. Cargar datos de ejemplo mejorados
mysql -u root -p clubespadel < enhanced_sample_data.sql
```

### Opción 2: Actualización desde sample_data.sql

```bash
# Si ya tienes datos básicos y quieres los mejorados
mysql -u root -p clubespadel < enhanced_sample_data.sql
```

## Credenciales de Acceso

Después de cargar cualquiera de los scripts de datos, puedes acceder con estas cuentas:

### SuperAdmin
- **Email:** superadmin@clubespadel.com
- **Contraseña:** admin123
- **Acceso:** Panel de SuperAdmin completo

### Club Demo - Administrador
- **Email:** admin@demo.com
- **Contraseña:** demo123
- **Acceso:** Administración del Club Demo

### Club Demo - Recepcionista
- **Email:** recepcion@demo.com
- **Contraseña:** demo123
- **Acceso:** Gestión de reservaciones

### Club Demo - Entrenador
- **Email:** entrenador@demo.com
- **Contraseña:** demo123
- **Acceso:** Gestión de clases y entrenamientos

### Club Demo - Jugadores
- **Email:** jugador1@demo.com
- **Contraseña:** demo123
- **Acceso:** Realizar reservaciones, ver torneos

- **Email:** jugador2@demo.com
- **Contraseña:** demo123

- **Email:** jugador3@demo.com
- **Contraseña:** demo123

## Estadísticas de Datos (enhanced_sample_data.sql)

- **Clubes totales:** 8
- **Usuarios totales:** 30+
- **Canchas totales:** 19
- **Reservaciones totales:** 200+
- **Torneos totales:** 5
- **Transacciones de ingreso:** 50+
- **Transacciones de gasto:** 15+
- **Pagos de suscripción:** 15+

## Beneficios de enhanced_sample_data.sql

### 1. **Gráficas Funcionales**
Los datos están distribuidos en los últimos 6 meses, lo que permite que las gráficas del dashboard muestren tendencias realistas:
- Crecimiento de clubes
- Distribución por planes
- Ingresos vs gastos mensuales
- Reservaciones por cancha

### 2. **Pruebas Completas**
Con múltiples clubes, usuarios y transacciones, puedes probar:
- Filtros multi-tenant
- Reportes financieros
- Gestión de torneos
- Sistema de reservaciones
- Notificaciones

### 3. **Demostraciones Profesionales**
Los datos realistas permiten hacer demostraciones convincentes:
- Dashboard con estadísticas reales
- Reportes con datos significativos
- Gráficas con tendencias visuales
- Sistema completo en funcionamiento

### 4. **Testing de Rendimiento**
Con 200+ reservaciones y múltiples transacciones:
- Probar la velocidad de consultas
- Validar índices de base de datos
- Optimizar queries lentas
- Simular carga real

## Verificación Post-Instalación

Después de cargar los datos, puedes verificar la instalación con estas consultas:

```sql
-- Ver resumen de datos cargados
SELECT 'Clubes' as Tabla, COUNT(*) as Total FROM clubs
UNION ALL
SELECT 'Usuarios', COUNT(*) FROM users
UNION ALL
SELECT 'Canchas', COUNT(*) FROM courts
UNION ALL
SELECT 'Reservaciones', COUNT(*) FROM reservations
UNION ALL
SELECT 'Torneos', COUNT(*) FROM tournaments;

-- Ver ingresos totales del Club Demo
SELECT CONCAT('$', FORMAT(SUM(amount), 2)) as 'Ingresos Totales'
FROM income_transactions 
WHERE club_id = 1;

-- Ver clubes por estado
SELECT subscription_status, COUNT(*) as cantidad
FROM clubs
GROUP BY subscription_status;
```

## Mantenimiento

### Limpiar Datos de Prueba

Si necesitas limpiar todos los datos y empezar de nuevo:

```sql
-- Esto eliminará TODOS los datos pero mantendrá la estructura
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE activity_log;
TRUNCATE TABLE club_settings;
TRUNCATE TABLE notifications;
TRUNCATE TABLE tournament_matches;
TRUNCATE TABLE tournament_participants;
TRUNCATE TABLE tournaments;
TRUNCATE TABLE expense_transactions;
TRUNCATE TABLE expense_categories;
TRUNCATE TABLE income_transactions;
TRUNCATE TABLE income_categories;
TRUNCATE TABLE reservations;
TRUNCATE TABLE court_schedules;
TRUNCATE TABLE courts;
TRUNCATE TABLE users;
TRUNCATE TABLE club_payments;
TRUNCATE TABLE clubs;
TRUNCATE TABLE subscription_plans;
SET FOREIGN_KEY_CHECKS = 1;
```

Luego puedes volver a cargar los datos con:
```bash
mysql -u root -p clubespadel < enhanced_sample_data.sql
```

## Soporte

Para problemas con los scripts de base de datos:
1. Verifica que MySQL esté ejecutándose
2. Confirma que tienes permisos suficientes
3. Revisa que la codificación sea UTF-8 (utf8mb4)
4. Consulta los logs de MySQL para errores específicos

## Próximos Pasos

Después de cargar los datos:
1. Accede al sistema en tu navegador
2. Prueba las diferentes cuentas de usuario
3. Explora los dashboards y gráficas
4. Crea nuevas reservaciones
5. Prueba el módulo de torneos
6. Revisa los reportes financieros

---

**Nota:** Los scripts están diseñados para MySQL 5.7+ y son compatibles con MariaDB 10.2+
