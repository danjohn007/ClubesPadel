<?php
/**
 * Main Configuration File
 * Auto-detects URL base and configures application settings
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('America/Mexico_City');

// Auto-detect URL base
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$script = $_SERVER['SCRIPT_NAME'] ?? '';
$base_dir = str_replace('\\', '/', dirname($script));
$base_dir = ($base_dir === '/') ? '' : $base_dir;

define('URL_BASE', $protocol . '://' . $host . $base_dir);
define('URL_ROOT', $base_dir);

// Directory paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOAD_PATH', ROOT_PATH . '/uploads');
define('TEMP_PATH', ROOT_PATH . '/temp');

// Application settings
define('APP_NAME', 'ClubesPadel');
define('APP_VERSION', '1.0.0');
define('APP_DESCRIPTION', 'Sistema de Administración de Clubes de Padel');

// Security settings
define('SECURITY_SALT', 'change_this_salt_in_production_12345');
define('SESSION_LIFETIME', 3600); // 1 hour
define('PASSWORD_MIN_LENGTH', 8);

// Pagination
define('ITEMS_PER_PAGE', 10);

// Upload settings
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif']);

// Trial period (days)
define('TRIAL_PERIOD_DAYS', 30);

// Load database configuration if exists
$db_config_file = ROOT_PATH . '/config/database.php';
if (file_exists($db_config_file)) {
    require_once $db_config_file;
} else {
    // Use example configuration for testing
    require_once ROOT_PATH . '/config/database.example.php';
}

// Autoloader
spl_autoload_register(function ($class) {
    $file = APP_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
