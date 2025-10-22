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

### 4. `migration_crm_loyalty_modules.sql` ⭐ **NUEVO - SuperAdmin Modules**
**Descripción:** Migración completa para los nuevos módulos de SuperAdmin: CRM Desarrollos, CRM Deportivas, Patrocinadores y Sistema de Lealtad.

**Contenido:**
- **13 nuevas tablas** para los módulos de CRM y Lealtad
- Módulo **CRM Desarrollos** (desarrollos inmobiliarios)
- Módulo **CRM Deportivas** (organizaciones deportivas)
- Módulo **Patrocinadores** (sponsors y alianzas comerciales)
- Módulo **Sistema de Lealtad** (programas de puntos y recompensas)
- Mejoras a la tabla `clubs` con campos adicionales
- Índices de rendimiento optimizados
- Datos de ejemplo iniciales

**Características especiales:**
- ✅ **Idempotente** - Se puede ejecutar múltiples veces de forma segura
- ✅ **Compatible** con datos existentes - No destructivo
- ✅ **Listo para producción** - Con índices y restricciones apropiadas
- ✅ **Verificación automática** - Revisa existencia de columnas e índices antes de crear

**Tablas incluidas:**
- `developments`, `development_clubs` - CRM Desarrollos
- `sports_organizations`, `organization_clubs` - CRM Deportivas
- `sponsors`, `sponsor_clubs`, `sponsor_payments` - Patrocinadores
- `loyalty_programs`, `loyalty_tiers`, `loyalty_memberships`, `loyalty_transactions`, `loyalty_rewards`, `loyalty_redemptions` - Sistema de Lealtad

**Uso:**
```bash
# Ejecutar después de tener el schema base
mysql -u root -p clubespadel < migration_crm_loyalty_modules.sql
```

**⚠️ NOTA:** Este script requiere que el schema base (`schema.sql`) ya esté cargado, ya que hace referencia a tablas como `clubs` y `users`.

### 5. `sample_data_crm_loyalty.sql`
**Descripción:** Datos de ejemplo para los nuevos módulos de CRM y Lealtad.

**Contenido:**
- **4 desarrollos inmobiliarios** con diferentes estados
- **5 organizaciones deportivas** (federaciones, asociaciones, ligas)
- **5 patrocinadores** con diferentes niveles de sponsorización
- **2 programas de lealtad** (global y específico de club)
- **7 niveles/tiers** de lealtad
- **7 recompensas** canjeables
- **Transacciones de ejemplo** y relaciones entre entidades

**Uso:**
```bash
# Ejecutar después de la migración de CRM y Lealtad
mysql -u root -p clubespadel < sample_data_crm_loyalty.sql
```

### 6. `CRM_LOYALTY_MODULES_README.md`
**Descripción:** Documentación técnica completa de los módulos de CRM y Lealtad.

**Contiene:**
- Descripción detallada de cada módulo
- Estructura de todas las tablas nuevas
- Relaciones y foreign keys
- Ejemplos de consultas SQL
- Casos de uso
- Guía de mantenimiento
- Troubleshooting

## Guía de Instalación Completa

### Opción 1: Instalación Nueva con Módulos CRM y Lealtad (Recomendado) ⭐

```bash
# 1. Crear la base de datos
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS clubespadel DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 2. Crear el esquema base
mysql -u root -p clubespadel < schema.sql

# 3. Aplicar migración de módulos CRM y Lealtad
mysql -u root -p clubespadel < migration_crm_loyalty_modules.sql

# 4. Cargar datos de ejemplo mejorados (incluye datos básicos)
mysql -u root -p clubespadel < enhanced_sample_data.sql

# 5. Cargar datos de ejemplo de CRM y Lealtad
mysql -u root -p clubespadel < sample_data_crm_loyalty.sql
```

### Opción 2: Instalación Básica (sin módulos CRM y Lealtad)

```bash
# 1. Crear la base de datos
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS clubespadel DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 2. Crear el esquema
mysql -u root -p clubespadel < schema.sql

# 3. Cargar datos de ejemplo mejorados
mysql -u root -p clubespadel < enhanced_sample_data.sql
```

### Opción 3: Actualizar Sistema Existente con Nuevos Módulos

```bash
# Si ya tienes el sistema funcionando y quieres agregar los módulos CRM y Lealtad
mysql -u root -p clubespadel < migration_crm_loyalty_modules.sql

# Opcionalmente, cargar datos de ejemplo
mysql -u root -p clubespadel < sample_data_crm_loyalty.sql
```

### Opción 4: Actualización desde sample_data.sql

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
