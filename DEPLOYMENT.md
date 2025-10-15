# Guía de Despliegue - ClubesPadel

## Despliegue en Servidor de Producción

### Requisitos del Servidor

- **Sistema Operativo**: Linux (Ubuntu 20.04+ recomendado)
- **Servidor Web**: Apache 2.4+ con mod_rewrite
- **PHP**: 7.4, 8.0, 8.1 o 8.2
- **Base de Datos**: MySQL 5.7+ o MariaDB 10.3+
- **RAM**: Mínimo 512MB (recomendado 1GB+)
- **Disco**: Mínimo 500MB libre

### Paso 1: Preparar el Servidor

#### Instalar Dependencias (Ubuntu/Debian)

```bash
sudo apt update
sudo apt install apache2 mysql-server php php-mysql php-mbstring php-json
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Paso 2: Configurar MySQL

```bash
# Acceder a MySQL
sudo mysql -u root -p

# Crear base de datos
CREATE DATABASE clubespadel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Crear usuario (cambiar password)
CREATE USER 'clubespadel_user'@'localhost' IDENTIFIED BY 'tu_password_seguro';
GRANT ALL PRIVILEGES ON clubespadel.* TO 'clubespadel_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Paso 3: Subir Archivos

```bash
# Clonar repositorio
cd /var/www/html
sudo git clone https://github.com/danjohn007/ClubesPadel.git clubespadel
cd clubespadel

# O subir archivos vía FTP/SFTP a /var/www/html/clubespadel
```

### Paso 4: Configurar Permisos

```bash
sudo chown -R www-data:www-data /var/www/html/clubespadel
sudo chmod -R 755 /var/www/html/clubespadel
sudo chmod -R 777 /var/www/html/clubespadel/uploads
sudo chmod -R 777 /var/www/html/clubespadel/temp
sudo chmod -R 777 /var/www/html/clubespadel/cache
```

### Paso 5: Configurar Base de Datos

```bash
# Copiar archivo de configuración
cd /var/www/html/clubespadel
sudo cp config/database.example.php config/database.php

# Editar con credenciales reales
sudo nano config/database.php
```

Actualizar:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'clubespadel');
define('DB_USER', 'clubespadel_user');
define('DB_PASS', 'tu_password_seguro');
```

### Paso 6: Importar Base de Datos

```bash
mysql -u clubespadel_user -p clubespadel < database/schema.sql
mysql -u clubespadel_user -p clubespadel < database/sample_data.sql
```

### Paso 7: Configurar Apache

Crear archivo de configuración:

```bash
sudo nano /etc/apache2/sites-available/clubespadel.conf
```

Contenido:
```apache
<VirtualHost *:80>
    ServerName tu-dominio.com
    ServerAdmin admin@tu-dominio.com
    DocumentRoot /var/www/html/clubespadel
    
    <Directory /var/www/html/clubespadel>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/clubespadel-error.log
    CustomLog ${APACHE_LOG_DIR}/clubespadel-access.log combined
</VirtualHost>
```

Activar sitio:
```bash
sudo a2ensite clubespadel.conf
sudo systemctl reload apache2
```

### Paso 8: Configurar SSL (Opcional pero Recomendado)

```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-apache

# Obtener certificado SSL
sudo certbot --apache -d tu-dominio.com
```

### Paso 9: Optimización de Producción

#### Actualizar config.php

```php
// Desactivar errores en producción
error_reporting(0);
ini_set('display_errors', 0);

// Cambiar salt de seguridad
define('SECURITY_SALT', 'generar_string_aleatorio_unico');
```

#### Optimizar PHP (php.ini)

```ini
memory_limit = 256M
max_execution_time = 60
upload_max_filesize = 10M
post_max_size = 10M
```

### Paso 10: Verificar Instalación

Acceder a:
```
https://tu-dominio.com/test_connection.php
```

Si todo está correcto, verás:
- ✅ Conexión a base de datos exitosa
- ✅ URL base detectada correctamente
- ✅ Todas las tablas creadas

### Paso 11: Configurar Backups

```bash
# Crear script de backup
sudo nano /usr/local/bin/backup-clubespadel.sh
```

Contenido:
```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/clubespadel"
mkdir -p $BACKUP_DIR

# Backup base de datos
mysqldump -u clubespadel_user -p'tu_password' clubespadel > $BACKUP_DIR/db_$DATE.sql

# Backup archivos
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/html/clubespadel/uploads

# Eliminar backups antiguos (30 días)
find $BACKUP_DIR -type f -mtime +30 -delete
```

Dar permisos y programar:
```bash
sudo chmod +x /usr/local/bin/backup-clubespadel.sh
sudo crontab -e
```

Agregar línea (backup diario a las 2 AM):
```
0 2 * * * /usr/local/bin/backup-clubespadel.sh
```

## Configuración de Dominio

### DNS

Configurar registros DNS:
```
A Record: tu-dominio.com -> IP_DEL_SERVIDOR
CNAME: www -> tu-dominio.com
```

### Subdominio por Club (Multi-tenant)

Para habilitar subdominios por club:

```apache
<VirtualHost *:80>
    ServerName *.tu-dominio.com
    ServerAlias tu-dominio.com
    DocumentRoot /var/www/html/clubespadel
    
    # Configuración igual que antes
</VirtualHost>
```

## Monitoreo

### Logs de Apache

```bash
# Ver errores en tiempo real
sudo tail -f /var/log/apache2/clubespadel-error.log

# Ver accesos
sudo tail -f /var/log/apache2/clubespadel-access.log
```

### Monitoreo de Performance

Instalar herramientas:
```bash
sudo apt install htop iotop
```

## Seguridad Adicional

### Firewall

```bash
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw allow 22/tcp
sudo ufw enable
```

### Fail2Ban (Protección contra ataques)

```bash
sudo apt install fail2ban
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

## Troubleshooting

### Error de Conexión a Base de Datos
- Verificar credenciales en config/database.php
- Confirmar que MySQL está ejecutándose: `sudo systemctl status mysql`

### Error 500
- Revisar logs: `sudo tail -f /var/log/apache2/clubespadel-error.log`
- Verificar permisos de archivos

### URLs no funcionan
- Confirmar que mod_rewrite está habilitado: `sudo a2enmod rewrite`
- Verificar .htaccess existe y tiene AllowOverride All

## Actualización del Sistema

```bash
cd /var/www/html/clubespadel
sudo git pull origin main
# Si hay cambios en DB, ejecutar migrations
sudo systemctl reload apache2
```

## Soporte

Para problemas específicos de despliegue:
- Revisa logs del servidor
- Consulta README.md
- Abre un issue en GitHub

---

**¡Tu sistema ClubesPadel está listo para producción!** 🚀
