<?php
/**
 * Database Connection and Configuration Test
 * Tests database connection and displays URL base configuration
 */

require_once __DIR__ . '/config/config.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Conexión - ClubesPadel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .test-result { margin: 20px 0; padding: 15px; border-radius: 5px; }
        .success { background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .error { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .info { background-color: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">ClubesPadel - Test de Conexión y Configuración</h1>
        
        <!-- URL Base Configuration -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Configuración de URL Base</h5>
            </div>
            <div class="card-body">
                <div class="test-result info">
                    <strong>✓ URL Base detectada automáticamente:</strong><br>
                    <code><?php echo URL_BASE; ?></code>
                </div>
                <table class="table table-sm">
                    <tr>
                        <th>URL Root:</th>
                        <td><code><?php echo URL_ROOT; ?></code></td>
                    </tr>
                    <tr>
                        <th>Root Path:</th>
                        <td><code><?php echo ROOT_PATH; ?></code></td>
                    </tr>
                    <tr>
                        <th>App Path:</th>
                        <td><code><?php echo APP_PATH; ?></code></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Database Connection Test -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Test de Conexión a Base de Datos</h5>
            </div>
            <div class="card-body">
                <?php
                try {
                    $db = Core\Database::getInstance();
                    $conn = $db->getConnection();
                    
                    echo '<div class="test-result success">';
                    echo '<strong>✓ Conexión exitosa a la base de datos</strong><br>';
                    echo '<small>Host: ' . DB_HOST . ' | Database: ' . DB_NAME . '</small>';
                    echo '</div>';
                    
                    // Test query
                    $stmt = $conn->query("SELECT VERSION() as version");
                    $result = $stmt->fetch();
                    
                    echo '<table class="table table-sm mt-3">';
                    echo '<tr><th>MySQL Version:</th><td>' . $result['version'] . '</td></tr>';
                    echo '<tr><th>Character Set:</th><td>' . DB_CHARSET . '</td></tr>';
                    echo '<tr><th>Connection Status:</th><td><span class="badge bg-success">Activa</span></td></tr>';
                    echo '</table>';
                    
                    // Check if tables exist
                    $tables = $conn->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);
                    
                    if (count($tables) > 0) {
                        echo '<div class="mt-3">';
                        echo '<strong>Tablas encontradas (' . count($tables) . '):</strong>';
                        echo '<ul class="list-group mt-2">';
                        foreach ($tables as $table) {
                            echo '<li class="list-group-item">' . $table . '</li>';
                        }
                        echo '</ul>';
                        echo '</div>';
                    } else {
                        echo '<div class="alert alert-warning mt-3">';
                        echo '<strong>⚠ No se encontraron tablas en la base de datos.</strong><br>';
                        echo 'Por favor ejecuta el script SQL <code>database/schema.sql</code> para crear las tablas.';
                        echo '</div>';
                    }
                    
                } catch (Exception $e) {
                    echo '<div class="test-result error">';
                    echo '<strong>✗ Error de conexión:</strong><br>';
                    echo $e->getMessage();
                    echo '</div>';
                    
                    echo '<div class="alert alert-info mt-3">';
                    echo '<strong>Pasos para solucionar:</strong>';
                    echo '<ol>';
                    echo '<li>Verifica que MySQL esté ejecutándose</li>';
                    echo '<li>Copia <code>config/database.example.php</code> a <code>config/database.php</code></li>';
                    echo '<li>Actualiza las credenciales en <code>config/database.php</code></li>';
                    echo '<li>Crea la base de datos: <code>CREATE DATABASE clubespadel;</code></li>';
                    echo '<li>Ejecuta el script SQL: <code>database/schema.sql</code></li>';
                    echo '</ol>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
        
        <!-- System Information -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Información del Sistema</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>PHP Version:</th>
                        <td><?php echo PHP_VERSION; ?></td>
                    </tr>
                    <tr>
                        <th>App Name:</th>
                        <td><?php echo APP_NAME; ?></td>
                    </tr>
                    <tr>
                        <th>App Version:</th>
                        <td><?php echo APP_VERSION; ?></td>
                    </tr>
                    <tr>
                        <th>Session Status:</th>
                        <td><?php echo session_status() === PHP_SESSION_ACTIVE ? 'Activa' : 'Inactiva'; ?></td>
                    </tr>
                    <tr>
                        <th>Timezone:</th>
                        <td><?php echo date_default_timezone_get(); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="text-center mb-5">
            <a href="<?php echo URL_BASE; ?>" class="btn btn-primary">Ir al Sistema</a>
            <a href="<?php echo URL_BASE; ?>/superadmin" class="btn btn-secondary">Panel SuperAdmin</a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
