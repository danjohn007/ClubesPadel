# Guía de Instalación - Actualizaciones SuperAdmin

## 🚀 Pasos para Aplicar las Actualizaciones

### 1. Respaldo de la Base de Datos (IMPORTANTE)
Antes de aplicar cualquier cambio, haz un respaldo completo de tu base de datos:

```bash
mysqldump -u tu_usuario -p clubespadel > backup_clubespadel_$(date +%Y%m%d).sql
```

### 2. Aplicar Migración SQL

#### Opción A: Desde línea de comandos
```bash
cd /ruta/a/ClubesPadel
mysql -u tu_usuario -p clubespadel < database/migration_superadmin_enhancements.sql
```

#### Opción B: Usando phpMyAdmin
1. Abre phpMyAdmin
2. Selecciona la base de datos `clubespadel`
3. Ve a la pestaña "SQL"
4. Abre el archivo `database/migration_superadmin_enhancements.sql` en un editor de texto
5. Copia todo el contenido
6. Pégalo en el área de texto de phpMyAdmin
7. Haz clic en "Continuar" o "Go"

#### Opción C: Usando MySQL Workbench
1. Abre MySQL Workbench
2. Conecta a tu servidor
3. Ve a File > Open SQL Script
4. Selecciona `database/migration_superadmin_enhancements.sql`
5. Haz clic en el icono de rayo (⚡) para ejecutar

### 3. Verificar la Instalación

Ejecuta estas consultas para verificar que las tablas se crearon correctamente:

```sql
-- Verificar tablas de Desarrollos
SHOW TABLES LIKE 'developments';
SHOW TABLES LIKE 'development_clubs';

-- Verificar tablas de Organizaciones Deportivas
SHOW TABLES LIKE 'sports_organizations';
SHOW TABLES LIKE 'organization_clubs';

-- Verificar tablas de Patrocinadores
SHOW TABLES LIKE 'sponsors';
SHOW TABLES LIKE 'sponsor_clubs';

-- Verificar tablas del Sistema de Lealtad
SHOW TABLES LIKE 'loyalty_%';

-- Verificar campos nuevos en tabla clubs
DESCRIBE clubs;
```

### 4. Limpiar Caché del Navegador

Después de aplicar los cambios, asegúrate de limpiar el caché del navegador:

**Chrome/Edge:**
- Presiona `Ctrl + Shift + Delete` (Windows/Linux)
- Presiona `Cmd + Shift + Delete` (Mac)
- Selecciona "Caché" o "Imágenes y archivos en caché"
- Haz clic en "Borrar datos"

**Firefox:**
- Presiona `Ctrl + Shift + Delete`
- Selecciona "Caché"
- Haz clic en "Limpiar ahora"

### 5. Probar las Nuevas Funcionalidades

#### 5.1 Probar Gestión de Clubes
1. Inicia sesión como SuperAdmin
2. Ve a **SuperAdmin > CRM Clubes**
3. Prueba las siguientes acciones:
   - ✅ Haz clic en el botón "Ver" (ojo azul) para ver detalles de un club
   - ✅ Haz clic en "Editar" y verifica que el nombre no se puede cambiar
   - ✅ Si hay un club activo, prueba suspenderlo
   - ✅ Reactiva el club suspendido
   - ✅ Crea un nuevo club y verifica los campos de País, Estado y Ciudad

#### 5.2 Probar Nuevo Menú
1. Verifica que el menú lateral muestra los 11 elementos:
   - Dashboard
   - CRM Usuarios
   - CRM Clubes
   - CRM Desarrollos
   - CRM Deportivas
   - Patrocinadores
   - Sistema de Lealtad
   - Planes
   - Pagos
   - Reportes Financieros
   - Configuración

2. Haz clic en cada elemento del menú y verifica:
   - Los módulos funcionales (Dashboard, Usuarios, Clubes, Planes, Pagos, Reportes, Configuración) cargan correctamente
   - Los módulos en desarrollo (Desarrollos, Deportivas, Patrocinadores, Lealtad) muestran un mensaje apropiado

### 6. Verificación de Permisos

Si estás en Linux/Mac, asegúrate de que los permisos sean correctos:

```bash
cd /ruta/a/ClubesPadel
chmod -R 755 app/Views/superadmin/
chmod -R 755 app/Controllers/
chmod 644 database/migration_superadmin_enhancements.sql
```

### 7. Solución de Problemas Comunes

#### Error: "Table already exists"
Si ves este error, significa que algunas tablas ya existen. Esto es normal si has ejecutado la migración antes. El script usa `CREATE TABLE IF NOT EXISTS` por lo que es seguro ejecutarlo múltiples veces.

#### Error: "Cannot add foreign key constraint"
Esto puede ocurrir si:
1. La tabla referenciada no existe aún
2. Los tipos de datos no coinciden

**Solución:**
- Asegúrate de ejecutar el archivo SQL completo, no en partes
- Verifica que todas las tablas base (clubs, users, etc.) existen

#### Error: "Duplicate column name"
Esto ocurre si intentas agregar una columna que ya existe.

**Solución:**
- El script usa `ADD COLUMN IF NOT EXISTS` cuando es posible
- Si el error persiste, puedes comentar las líneas que causan el error

#### Las vistas no se muestran correctamente
**Solución:**
1. Verifica que los archivos se hayan subido correctamente
2. Limpia el caché del navegador
3. Verifica los permisos de los archivos
4. Revisa el log de errores de PHP

### 8. Rollback (En caso de problemas)

Si necesitas revertir los cambios:

```bash
# Restaurar el respaldo
mysql -u tu_usuario -p clubespadel < backup_clubespadel_YYYYMMDD.sql
```

**Nota:** Esto eliminará TODOS los cambios realizados después del respaldo, no solo los de esta migración.

### 9. Verificación Final

Ejecuta esta consulta para verificar que todo está correcto:

```sql
-- Contar tablas nuevas
SELECT COUNT(*) as total_new_tables 
FROM information_schema.tables 
WHERE table_schema = 'clubespadel' 
AND table_name IN (
    'developments', 'development_clubs',
    'sports_organizations', 'organization_clubs',
    'sponsors', 'sponsor_clubs',
    'loyalty_programs', 'loyalty_tiers', 'loyalty_memberships',
    'loyalty_transactions', 'loyalty_rewards', 'loyalty_redemptions'
);
-- Deberías ver: total_new_tables = 12

-- Verificar campos nuevos en clubs
SELECT COLUMN_NAME 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'clubespadel' 
AND TABLE_NAME = 'clubs'
AND COLUMN_NAME IN ('postal_code', 'latitude', 'longitude', 'timezone', 
                    'facebook_url', 'instagram_url', 'twitter_url',
                    'business_name', 'tax_id', 'business_type');
-- Deberías ver 10 columnas listadas
```

### 10. Soporte

Si encuentras algún problema durante la instalación:

1. Revisa el archivo `SUPERADMIN_UPDATES.md` para más detalles
2. Verifica los logs de error de MySQL/MariaDB
3. Verifica los logs de error de PHP
4. Crea un issue en: https://github.com/danjohn007/ClubesPadel/issues

---

## ✅ Checklist de Instalación

- [ ] Respaldo de base de datos creado
- [ ] Migración SQL ejecutada sin errores
- [ ] Tablas nuevas verificadas (12 tablas)
- [ ] Campos nuevos en clubs verificados (10 campos)
- [ ] Caché del navegador limpiado
- [ ] Login como SuperAdmin exitoso
- [ ] Menú lateral muestra 11 elementos
- [ ] Vista de detalles de club funciona
- [ ] Suspender/Reactivar club funciona
- [ ] Editar club (nombre readonly) funciona
- [ ] Crear club con nuevos campos funciona
- [ ] Todas las vistas del menú cargan correctamente

---

**Fecha:** Octubre 22, 2025  
**Versión:** 1.1.0  
**Tiempo estimado de instalación:** 5-10 minutos
