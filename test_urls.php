<?php
/**
 * URL Configuration Test
 * Tests that URLs are generated correctly based on installation directory
 */

require_once __DIR__ . '/config/config.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de URLs - ClubesPadel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .test-result { 
            margin: 10px 0; 
            padding: 15px; 
            border-radius: 5px; 
            background-color: #f8f9fa;
            border-left: 4px solid #0d6efd;
        }
        .url-display {
            font-family: monospace;
            background-color: #e9ecef;
            padding: 8px;
            border-radius: 4px;
            margin: 5px 0;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Test de Configuración de URLs</h1>
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Configuración Base</h5>
            </div>
            <div class="card-body">
                <div class="test-result">
                    <strong>URL_BASE:</strong><br>
                    <div class="url-display"><?php echo URL_BASE; ?></div>
                </div>
                <div class="test-result">
                    <strong>URL_ROOT:</strong><br>
                    <div class="url-display"><?php echo URL_ROOT; ?></div>
                </div>
                <div class="test-result">
                    <strong>APP_PATH defined:</strong><br>
                    <div class="url-display"><?php echo defined('APP_PATH') ? 'Yes: ' . APP_PATH : 'No'; ?></div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">URLs de Autenticación</h5>
            </div>
            <div class="card-body">
                <div class="test-result">
                    <strong>Login URL:</strong><br>
                    <div class="url-display"><?php echo URL_BASE . '/auth/login'; ?></div>
                    <a href="<?php echo URL_BASE; ?>/auth/login" class="btn btn-sm btn-primary mt-2">
                        Probar enlace
                    </a>
                </div>
                <div class="test-result">
                    <strong>Register URL:</strong><br>
                    <div class="url-display"><?php echo URL_BASE . '/auth/register'; ?></div>
                    <a href="<?php echo URL_BASE; ?>/auth/register" class="btn btn-sm btn-primary mt-2">
                        Probar enlace
                    </a>
                </div>
                <div class="test-result">
                    <strong>Logout URL:</strong><br>
                    <div class="url-display"><?php echo URL_BASE . '/auth/logout'; ?></div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Otras URLs del Sistema</h5>
            </div>
            <div class="card-body">
                <div class="test-result">
                    <strong>Home:</strong><br>
                    <div class="url-display"><?php echo URL_BASE; ?></div>
                    <a href="<?php echo URL_BASE; ?>" class="btn btn-sm btn-primary mt-2">
                        Ir al inicio
                    </a>
                </div>
                <div class="test-result">
                    <strong>Dashboard:</strong><br>
                    <div class="url-display"><?php echo URL_BASE . '/dashboard'; ?></div>
                </div>
                <div class="test-result">
                    <strong>SuperAdmin:</strong><br>
                    <div class="url-display"><?php echo URL_BASE . '/superadmin/dashboard'; ?></div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Información del Servidor</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>HTTP_HOST:</th>
                        <td><?php echo $_SERVER['HTTP_HOST'] ?? 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <th>SCRIPT_NAME:</th>
                        <td><?php echo $_SERVER['SCRIPT_NAME'] ?? 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <th>REQUEST_URI:</th>
                        <td><?php echo $_SERVER['REQUEST_URI'] ?? 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <th>DOCUMENT_ROOT:</th>
                        <td><?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'N/A'; ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="alert alert-info">
            <strong>Nota:</strong> Si las URLs muestran "auth/auth/login" o "auth/auth/register", 
            verifica que el archivo .htaccess tenga el RewriteBase comentado (o configurado correctamente 
            para el subdirectorio donde está instalada la aplicación).
        </div>
        
        <div class="text-center mb-5">
            <a href="<?php echo URL_BASE; ?>" class="btn btn-primary">Volver al Sistema</a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
