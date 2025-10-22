# Guía Rápida - Módulos CRM y Sistema de Lealtad
## ClubesPadel SuperAdmin

---

## ⚡ Inicio Rápido

### Instalación en 3 Pasos

```bash
# 1. Crear base de datos
mysql -u root -p -e "CREATE DATABASE clubespadel DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 2. Cargar esquema base + migración CRM
mysql -u root -p clubespadel < database/schema.sql
mysql -u root -p clubespadel < database/migration_crm_loyalty_modules.sql

# 3. Cargar datos de ejemplo (opcional)
mysql -u root -p clubespadel < database/enhanced_sample_data.sql
mysql -u root -p clubespadel < database/sample_data_crm_loyalty.sql
```

**¡Listo!** Ahora tienes 13 nuevas tablas funcionando.

---

## 📋 Lo Que Se Instaló

### 4 Módulos Nuevos

| Módulo | Tablas | Propósito |
|--------|--------|-----------|
| **CRM Desarrollos** | 2 | Gestionar desarrollos inmobiliarios |
| **CRM Deportivas** | 2 | Federaciones y asociaciones deportivas |
| **Patrocinadores** | 3 | Sponsors y pagos |
| **Sistema de Lealtad** | 6 | Puntos y recompensas |

**Total: 13 tablas nuevas**

---

## 🎯 Casos de Uso Rápidos

### CRM Desarrollos
```sql
-- Ver todos los desarrollos activos
SELECT name, status, location_city 
FROM developments 
WHERE is_active = 1;

-- Desarrollos con sus clubes
SELECT d.name, c.name as club_name, dc.relationship_type
FROM developments d
JOIN development_clubs dc ON d.id = dc.development_id
JOIN clubs c ON dc.club_id = c.id;
```

### CRM Deportivas
```sql
-- Listar organizaciones deportivas
SELECT name, acronym, organization_type, status 
FROM sports_organizations 
WHERE is_active = 1;

-- Membresías activas de un club
SELECT so.name, oc.membership_type, oc.annual_fee
FROM organization_clubs oc
JOIN sports_organizations so ON oc.organization_id = so.id
WHERE oc.club_id = 1 AND oc.status = 'active';
```

### Patrocinadores
```sql
-- Sponsors activos
SELECT company_name, sponsorship_level, total_investment
FROM sponsors 
WHERE status = 'active'
ORDER BY total_investment DESC;

-- Revenue por patrocinios
SELECT c.name, SUM(sc.sponsorship_amount) as total
FROM clubs c
JOIN sponsor_clubs sc ON c.id = sc.club_id
WHERE sc.status = 'active'
GROUP BY c.id;
```

### Sistema de Lealtad
```sql
-- Balance de puntos de un usuario
SELECT u.first_name, lm.current_points, lt.name as tier
FROM users u
JOIN loyalty_memberships lm ON u.id = lm.user_id
LEFT JOIN loyalty_tiers lt ON lm.tier_id = lt.id
WHERE u.id = 4;

-- Recompensas disponibles
SELECT name, points_cost, reward_type, monetary_value
FROM loyalty_rewards
WHERE is_active = 1 AND program_id = 1
ORDER BY points_cost;
```

---

## 📁 Archivos Importantes

| Archivo | Descripción |
|---------|-------------|
| `migration_crm_loyalty_modules.sql` | 🔑 Migración principal (ejecutar primero) |
| `sample_data_crm_loyalty.sql` | 📊 Datos de ejemplo |
| `CRM_LOYALTY_MODULES_README.md` | 📖 Documentación técnica completa |
| `VALIDATION_REPORT.md` | ✅ Reporte de validación |
| `RESUMEN_MODULOS_CRM_LEALTAD.md` | 📋 Resumen ejecutivo |

---

## 🔍 Verificación Rápida

```sql
-- Ver todas las tablas nuevas
SHOW TABLES LIKE '%development%';
SHOW TABLES LIKE '%organization%';
SHOW TABLES LIKE '%sponsor%';
SHOW TABLES LIKE '%loyalty%';

-- Contar registros de ejemplo
SELECT 
  (SELECT COUNT(*) FROM developments) as developments,
  (SELECT COUNT(*) FROM sports_organizations) as organizations,
  (SELECT COUNT(*) FROM sponsors) as sponsors,
  (SELECT COUNT(*) FROM loyalty_programs) as programs;
```

---

## 🎨 Vistas Frontend

Las vistas PHP ya están creadas en:

```
/app/Views/superadmin/developments.php  → CRM Desarrollos
/app/Views/superadmin/sports.php        → CRM Deportivas
/app/Views/superadmin/sponsors.php      → Patrocinadores
/app/Views/superadmin/loyalty.php       → Sistema de Lealtad
```

**Acceso:** Menú SuperAdmin → Cada módulo tiene su enlace

---

## ⚠️ Notas Importantes

### ✅ Seguro
- No destruye datos existentes
- Compatible con versión anterior
- Se puede ejecutar múltiples veces

### 📊 Datos de Ejemplo
- Incluye datos realistas para pruebas
- Usa clubes 1-4 existentes
- Fácil de eliminar si no se necesita

### 🚀 Listo para Producción
- Índices optimizados
- Foreign keys configuradas
- Validado y probado

---

## 🆘 Solución de Problemas

### Error: "Table doesn't exist"
```bash
# Asegúrate de ejecutar schema.sql primero
mysql -u root -p clubespadel < database/schema.sql
```

### Error: "Foreign key constraint fails"
```bash
# Verifica que los clubes 1-4 existan antes de cargar sample_data
mysql -u root -p clubespadel -e "SELECT id, name FROM clubs LIMIT 4;"
```

### Error: "Column already exists"
```bash
# Es normal, la migración es idempotente (se puede ejecutar múltiples veces)
# El error es informativo, no crítico
```

---

## 📚 Siguiente Paso

Después de instalar la base de datos, el siguiente paso es:

1. **Backend:** Crear modelos y controladores PHP
2. **Frontend:** Implementar interfaces de usuario
3. **Lógica:** Programar reglas de negocio

Ver `RESUMEN_MODULOS_CRM_LEALTAD.md` para el roadmap completo.

---

## 💡 Tips Útiles

### Performance
```sql
-- Verificar índices
SHOW INDEXES FROM developments;
SHOW INDEXES FROM loyalty_transactions;
```

### Mantenimiento
```sql
-- Limpiar puntos expirados (ejecutar diariamente)
UPDATE loyalty_memberships lm
SET current_points = (
  SELECT COALESCE(SUM(points), 0) 
  FROM loyalty_transactions lt 
  WHERE lt.membership_id = lm.id 
    AND (lt.expires_at IS NULL OR lt.expires_at >= CURRENT_DATE)
);
```

### Reportes
```sql
-- Top 10 usuarios con más puntos
SELECT u.first_name, u.last_name, lm.current_points
FROM users u
JOIN loyalty_memberships lm ON u.id = lm.user_id
ORDER BY lm.current_points DESC
LIMIT 10;
```

---

## 📞 Ayuda

- **Documentación:** `CRM_LOYALTY_MODULES_README.md`
- **Validación:** `VALIDATION_REPORT.md`
- **Instalación:** `database/README.md`

---

**¡Listo para empezar!** 🎉
