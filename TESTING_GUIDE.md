# Guía de Pruebas - ClubesPadel

Esta guía te ayudará a probar todas las funcionalidades implementadas del sistema ClubesPadel, especialmente las gráficas con datos reales.

## 🎯 Objetivo de las Pruebas

Verificar que:
1. Las páginas de login y registro funcionan correctamente (sin error 404)
2. Los dashboards cargan correctamente
3. Las gráficas muestran datos reales de la base de datos
4. Los datos son consistentes y precisos
5. Las funcionalidades básicas operan sin errores

## 📋 Prerequisitos

Antes de comenzar las pruebas, asegúrate de:
- [ ] Haber ejecutado `database/schema.sql`
- [ ] Haber ejecutado `database/enhanced_sample_data.sql`
- [ ] Apache con mod_rewrite habilitado
- [ ] PHP 7.4+ instalado
- [ ] MySQL 5.7+ corriendo
- [ ] Archivo `config/database.php` configurado

## 🧪 Plan de Pruebas

### Fase 1: Pruebas de Autenticación

#### Test 1.1: Página de Login
```
Objetivo: Verificar que la página de login carga correctamente
URL: http://localhost/ClubesPadel/auth/login
```

**Pasos:**
1. Abrir navegador
2. Navegar a `/auth/login`
3. Verificar que la página carga (NO debe mostrar error 404)

**Resultado Esperado:**
- ✅ Página de login visible
- ✅ Formulario con campos: Email, Contraseña, Recordarme
- ✅ Enlace a página de registro
- ✅ Cuentas de prueba mostradas en un recuadro

**Posibles Errores:**
- ❌ Error 404: Verificar que mod_rewrite esté habilitado
- ❌ Página en blanco: Revisar logs de PHP

#### Test 1.2: Página de Registro
```
Objetivo: Verificar que la página de registro carga correctamente
URL: http://localhost/ClubesPadel/auth/register
```

**Pasos:**
1. Navegar a `/auth/register`
2. Verificar que la página carga

**Resultado Esperado:**
- ✅ Página de registro visible
- ✅ Formulario completo con todos los campos
- ✅ Enlace de vuelta al login

#### Test 1.3: Login con SuperAdmin
```
Objetivo: Iniciar sesión como SuperAdmin
Credenciales:
  Email: superadmin@clubespadel.com
  Contraseña: admin123
```

**Pasos:**
1. Ir a `/auth/login`
2. Ingresar credenciales de SuperAdmin
3. Click en "Iniciar Sesión"

**Resultado Esperado:**
- ✅ Redirección a `/superadmin/dashboard`
- ✅ Dashboard de SuperAdmin visible
- ✅ Sin errores en consola del navegador

#### Test 1.4: Login con Admin del Club
```
Objetivo: Iniciar sesión como administrador de club
Credenciales:
  Email: admin@demo.com
  Contraseña: demo123
```

**Pasos:**
1. Cerrar sesión (si está abierta)
2. Ir a `/auth/login`
3. Ingresar credenciales de admin del club
4. Click en "Iniciar Sesión"

**Resultado Esperado:**
- ✅ Redirección a `/dashboard`
- ✅ Dashboard del club visible
- ✅ Menú lateral con opciones del club

### Fase 2: Pruebas de Dashboard SuperAdmin

#### Test 2.1: Estadísticas Generales
```
Objetivo: Verificar que las estadísticas del SuperAdmin son correctas
URL: http://localhost/ClubesPadel/superadmin/dashboard
Usuario: superadmin@clubespadel.com
```

**Pasos:**
1. Iniciar sesión como SuperAdmin
2. Observar las tarjetas de estadísticas en la parte superior

**Resultado Esperado:**
- ✅ **Total Clubes:** 8 (del enhanced_sample_data.sql)
- ✅ **Clubes Activos:** 5
- ✅ **En Prueba:** 2
- ✅ **Ingresos del Mes:** Monto > $0

**Verificación SQL:**
```sql
-- Ejecutar en MySQL para verificar
SELECT COUNT(*) as total_clubs FROM clubs;
-- Debe retornar: 8

SELECT COUNT(*) as active_clubs FROM clubs WHERE subscription_status = 'active';
-- Debe retornar: 5

SELECT COUNT(*) as trial_clubs FROM clubs WHERE subscription_status = 'trial';
-- Debe retornar: 2
```

#### Test 2.2: Gráfica de Crecimiento de Clubes
```
Objetivo: Verificar que la gráfica de crecimiento muestra datos reales
```

**Pasos:**
1. En el dashboard de SuperAdmin, scroll hacia abajo
2. Localizar la gráfica "Crecimiento de Clubes"
3. Verificar que muestra una línea con datos

**Resultado Esperado:**
- ✅ Gráfica de línea visible
- ✅ Eje X muestra meses (últimos 6 meses)
- ✅ Eje Y muestra cantidad de clubes
- ✅ Puntos en la línea (no todos en cero)
- ✅ Tooltip al pasar mouse sobre puntos

**Verificación SQL:**
```sql
-- Verificar datos de crecimiento
SELECT 
    DATE_FORMAT(created_at, '%Y-%m') as month,
    DATE_FORMAT(created_at, '%b') as month_name,
    COUNT(*) as count
FROM clubs 
WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
GROUP BY DATE_FORMAT(created_at, '%Y-%m')
ORDER BY month ASC;
```

#### Test 2.3: Gráfica de Distribución por Plan
```
Objetivo: Verificar que la gráfica de planes muestra la distribución correcta
```

**Pasos:**
1. En el dashboard de SuperAdmin
2. Localizar la gráfica "Distribución por Plan"
3. Verificar que es una gráfica de dona/doughnut

**Resultado Esperado:**
- ✅ Gráfica de dona visible
- ✅ 3 segmentos: Básico, Profesional, Premium
- ✅ Colores diferentes para cada segmento
- ✅ Leyenda en la parte inferior
- ✅ Tooltip muestra porcentaje al pasar mouse

**Verificación SQL:**
```sql
-- Verificar distribución de planes
SELECT 
    sp.name,
    COUNT(c.id) as count
FROM clubs c
JOIN subscription_plans sp ON c.subscription_plan_id = sp.id
WHERE c.is_active = 1
GROUP BY sp.id, sp.name
ORDER BY sp.id ASC;
-- Debe mostrar cantidades para cada plan
```

#### Test 2.4: Lista de Clubes Recientes
```
Objetivo: Verificar que la tabla de clubes recientes se carga
```

**Pasos:**
1. En el dashboard de SuperAdmin
2. Scroll hacia abajo a "Clubes Recientes"
3. Verificar que hay una tabla con clubes

**Resultado Esperado:**
- ✅ Tabla con al menos 5 clubes
- ✅ Columnas: Nombre, Subdominio, Email, Estado, Fecha de Registro
- ✅ Badge de estado con color (Activo=verde, Prueba=azul, etc.)
- ✅ Botón de "Ver" en cada fila

### Fase 3: Pruebas de Dashboard del Club

#### Test 3.1: Estadísticas del Día
```
Objetivo: Verificar estadísticas del club
URL: http://localhost/ClubesPadel/dashboard
Usuario: admin@demo.com
```

**Pasos:**
1. Iniciar sesión como admin del club
2. Observar las tarjetas de estadísticas superiores

**Resultado Esperado:**
- ✅ **Reservaciones Hoy:** Número >= 0
- ✅ **Ingresos del Mes:** Monto > $0
- ✅ **Miembros Activos:** Número > 0
- ✅ **Torneos Activos:** Número >= 0

**Verificación SQL:**
```sql
-- Verificar reservaciones de hoy del Club Demo (ID=1)
SELECT COUNT(*) as reservations_today 
FROM reservations 
WHERE club_id = 1 AND reservation_date = CURDATE();

-- Verificar ingresos del mes
SELECT COALESCE(SUM(amount), 0) as income_month 
FROM income_transactions 
WHERE club_id = 1 AND MONTH(transaction_date) = MONTH(CURDATE());

-- Verificar miembros activos
SELECT COUNT(*) as active_members 
FROM users 
WHERE club_id = 1 AND is_active = 1 AND role = 'player';
```

#### Test 3.2: Gráfica de Ingresos vs Gastos
```
Objetivo: Verificar que la gráfica financiera muestra datos reales
```

**Pasos:**
1. En el dashboard del club, scroll hacia abajo
2. Localizar la gráfica "Ingresos vs Gastos (Últimos 6 Meses)"
3. Verificar que muestra dos líneas

**Resultado Esperado:**
- ✅ Gráfica de líneas visible
- ✅ Dos líneas: verde (Ingresos) y roja (Gastos)
- ✅ Eje X muestra meses
- ✅ Eje Y muestra montos en formato $X,XXX.XX
- ✅ Leyenda visible (Ingresos y Gastos)
- ✅ Tooltip formateado como moneda MXN

**Verificación SQL:**
```sql
-- Verificar datos de ingresos por mes
SELECT 
    DATE_FORMAT(transaction_date, '%b') as month,
    COALESCE(SUM(amount), 0) as total
FROM income_transactions 
WHERE club_id = 1 
  AND transaction_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
GROUP BY DATE_FORMAT(transaction_date, '%Y-%m')
ORDER BY DATE_FORMAT(transaction_date, '%Y-%m') ASC;

-- Verificar datos de gastos por mes
SELECT 
    DATE_FORMAT(transaction_date, '%b') as month,
    COALESCE(SUM(amount), 0) as total
FROM expense_transactions 
WHERE club_id = 1 
  AND transaction_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
GROUP BY DATE_FORMAT(transaction_date, '%Y-%m')
ORDER BY DATE_FORMAT(transaction_date, '%Y-%m') ASC;
```

#### Test 3.3: Gráfica de Reservaciones por Cancha
```
Objetivo: Verificar que la gráfica de canchas muestra las más reservadas
```

**Pasos:**
1. En el dashboard del club
2. Localizar la gráfica "Reservaciones por Cancha"
3. Verificar que es una gráfica de barras horizontales

**Resultado Esperado:**
- ✅ Gráfica de barras horizontales visible
- ✅ Máximo 8 canchas mostradas
- ✅ Barras con colores diferentes
- ✅ Nombres de canchas en el eje Y
- ✅ Cantidad de reservaciones en el eje X
- ✅ Sin leyenda (no necesaria)

**Verificación SQL:**
```sql
-- Verificar reservaciones por cancha (último mes)
SELECT 
    c.name,
    COUNT(r.id) as count
FROM courts c
LEFT JOIN reservations r ON c.id = r.court_id 
    AND r.club_id = 1 
    AND r.reservation_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
WHERE c.club_id = 1
GROUP BY c.id, c.name
ORDER BY count DESC
LIMIT 8;
```

#### Test 3.4: Lista de Reservaciones Recientes
```
Objetivo: Verificar que se muestran las reservaciones recientes
```

**Pasos:**
1. En el dashboard del club
2. Localizar la sección "Reservaciones Recientes"
3. Verificar que hay una tabla con reservaciones

**Resultado Esperado:**
- ✅ Tabla con hasta 5 reservaciones
- ✅ Columnas: Cancha, Jugador, Fecha, Hora, Estado
- ✅ Badge de estado con colores
- ✅ Formato de fecha: dd/mm/yyyy
- ✅ Formato de hora: HH:mm

### Fase 4: Pruebas de Navegación

#### Test 4.1: Navegación entre Dashboards
```
Objetivo: Verificar que se puede navegar entre diferentes áreas
```

**Pasos:**
1. Login como SuperAdmin
2. Ir a Dashboard SuperAdmin
3. Logout
4. Login como admin del club
5. Ir a Dashboard del club

**Resultado Esperado:**
- ✅ Cada dashboard muestra contenido diferente
- ✅ No hay errores 404
- ✅ Logout funciona correctamente

#### Test 4.2: Página 404
```
Objetivo: Verificar que la página 404 se muestra correctamente
```

**Pasos:**
1. Navegar a una URL inexistente: `/pagina-que-no-existe`
2. Verificar la respuesta

**Resultado Esperado:**
- ✅ HTTP Status Code: 404
- ✅ Página 404 personalizada visible
- ✅ Mensaje en español
- ✅ Botones de navegación (Ir al Inicio, Volver Atrás)

### Fase 5: Pruebas de Consola del Navegador

#### Test 5.1: Errores de JavaScript
```
Objetivo: Verificar que no hay errores JS en la consola
```

**Pasos:**
1. Abrir DevTools (F12)
2. Ir a la pestaña "Console"
3. Navegar por todos los dashboards

**Resultado Esperado:**
- ✅ No hay errores rojos en consola
- ✅ Chart.js se carga correctamente
- ✅ No hay errores de "undefined"

#### Test 5.2: Recursos Cargados
```
Objetivo: Verificar que todos los recursos se cargan
```

**Pasos:**
1. Abrir DevTools (F12)
2. Ir a la pestaña "Network"
3. Recargar la página (Ctrl+R)

**Resultado Esperado:**
- ✅ bootstrap.min.css - Status 200
- ✅ chart.js - Status 200
- ✅ bootstrap.bundle.min.js - Status 200
- ✅ Sin recursos 404

### Fase 6: Pruebas de Responsividad

#### Test 6.1: Vista Móvil
```
Objetivo: Verificar que el sitio es responsive
```

**Pasos:**
1. Abrir DevTools (F12)
2. Activar modo responsive (Ctrl+Shift+M)
3. Cambiar a tamaño móvil (375x667)

**Resultado Esperado:**
- ✅ Layout se adapta a móvil
- ✅ Menú se convierte en hamburger
- ✅ Gráficas son responsive
- ✅ Tarjetas de estadísticas en columna

#### Test 6.2: Vista Tablet
```
Objetivo: Verificar vista en tablet
```

**Pasos:**
1. En modo responsive, cambiar a tablet (768x1024)

**Resultado Esperado:**
- ✅ Layout intermedio adecuado
- ✅ Gráficas visibles y legibles
- ✅ Navegación funcional

## 📊 Checklist de Verificación

### Autenticación
- [ ] Login de SuperAdmin funciona
- [ ] Login de Admin del club funciona
- [ ] Login de jugador funciona
- [ ] Registro de usuario funciona
- [ ] Logout funciona
- [ ] No hay error 404 en /auth/login
- [ ] No hay error 404 en /auth/register

### Dashboard SuperAdmin
- [ ] Estadísticas muestran números correctos
- [ ] Gráfica de crecimiento tiene datos
- [ ] Gráfica de planes tiene 3 segmentos
- [ ] Tabla de clubes se carga
- [ ] Navegación funciona

### Dashboard del Club
- [ ] Estadísticas del día son correctas
- [ ] Gráfica de ingresos/gastos muestra dos líneas
- [ ] Gráfica de reservaciones muestra barras
- [ ] Tabla de reservaciones se carga
- [ ] Accesos rápidos funcionan

### Gráficas (Chart.js)
- [ ] Chart.js se carga sin errores
- [ ] Gráficas son interactivas (tooltips)
- [ ] Datos coinciden con base de datos
- [ ] Formato de moneda correcto (MXN)
- [ ] Colores son legibles

### Funcionalidad General
- [ ] URLs amigables funcionan
- [ ] Página 404 personalizada se muestra
- [ ] No hay errores en consola
- [ ] Todos los recursos cargan (200)
- [ ] Sitio es responsive

## 🔍 Verificación de Datos

### Script SQL de Verificación

Ejecuta este script en MySQL para verificar que los datos se cargaron correctamente:

```sql
USE clubespadel;

-- Resumen general
SELECT '=== RESUMEN DE DATOS ===' as '';

SELECT 'Clubes' as Tabla, COUNT(*) as Total FROM clubs
UNION ALL
SELECT 'Usuarios', COUNT(*) FROM users
UNION ALL
SELECT 'Canchas', COUNT(*) FROM courts
UNION ALL
SELECT 'Reservaciones', COUNT(*) FROM reservations
UNION ALL
SELECT 'Torneos', COUNT(*) FROM tournaments
UNION ALL
SELECT 'Ingresos', COUNT(*) FROM income_transactions
UNION ALL
SELECT 'Gastos', COUNT(*) FROM expense_transactions;

-- Clubes por estado
SELECT '=== CLUBES POR ESTADO ===' as '';
SELECT subscription_status, COUNT(*) as cantidad
FROM clubs
GROUP BY subscription_status;

-- Usuarios por rol
SELECT '=== USUARIOS POR ROL ===' as '';
SELECT role, COUNT(*) as cantidad
FROM users
GROUP BY role
ORDER BY cantidad DESC;

-- Ingresos totales del Club Demo
SELECT '=== FINANZAS CLUB DEMO ===' as '';
SELECT 
    CONCAT('$', FORMAT(SUM(amount), 2)) as 'Ingresos Totales'
FROM income_transactions 
WHERE club_id = 1;

SELECT 
    CONCAT('$', FORMAT(SUM(amount), 2)) as 'Gastos Totales'
FROM expense_transactions 
WHERE club_id = 1;

-- Reservaciones del mes actual
SELECT '=== RESERVACIONES ESTE MES ===' as '';
SELECT COUNT(*) as 'Reservaciones del Mes'
FROM reservations
WHERE club_id = 1 
  AND MONTH(reservation_date) = MONTH(CURDATE());
```

**Resultados Esperados:**
```
Clubes: 8
Usuarios: 30+
Canchas: 19
Reservaciones: 200+
Torneos: 5
Ingresos: 50+
Gastos: 15+

Clubes Activos: 5
Clubes en Prueba: 2
Clubes Suspendidos: 1

Ingresos Totales Club Demo: > $10,000.00
Gastos Totales Club Demo: > $5,000.00
```

## 🐛 Reporte de Bugs

Si encuentras algún problema durante las pruebas:

### Template de Reporte
```markdown
**Descripción del Bug:**
[Descripción clara del problema]

**Pasos para Reproducir:**
1. [Primer paso]
2. [Segundo paso]
3. [etc.]

**Resultado Esperado:**
[Lo que debería pasar]

**Resultado Actual:**
[Lo que realmente pasó]

**Captura de Pantalla:**
[Si es posible, adjuntar imagen]

**Información del Sistema:**
- Sistema Operativo: [ej. Ubuntu 22.04]
- Navegador: [ej. Chrome 120]
- PHP Version: [ej. 7.4.33]
- MySQL Version: [ej. 5.7.44]

**Logs de Error:**
```
[Pegar logs relevantes aquí]
```
```

## ✅ Criterios de Aceptación

El sistema pasa las pruebas si:

1. ✅ **Todas las páginas cargan sin error 404**
2. ✅ **Todas las gráficas muestran datos reales**
3. ✅ **Los datos coinciden con la base de datos**
4. ✅ **No hay errores en consola del navegador**
5. ✅ **Todos los recursos (CSS, JS) cargan correctamente**
6. ✅ **La navegación entre páginas funciona**
7. ✅ **El login/logout funciona para todos los roles**
8. ✅ **Las gráficas son interactivas (tooltips)**
9. ✅ **El formato de moneda es correcto (MXN)**
10. ✅ **El sitio es responsive (móvil, tablet, desktop)**

## 📝 Notas Finales

- Ejecutar estas pruebas después de cada cambio importante
- Mantener esta guía actualizada
- Documentar nuevos casos de prueba
- Reportar bugs encontrados
- Celebrar cuando todo pase ✅ 🎉

---

**Versión:** 1.0
**Última actualización:** Octubre 15, 2025
**Autor:** GitHub Copilot para ClubesPadel
