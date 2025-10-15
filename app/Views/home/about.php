<?php 
// Ensure configuration is loaded
if (!defined('APP_PATH')) {
    require_once __DIR__ . '/../../../config/config.php';
}
require_once APP_PATH . '/Views/layouts/header.php'; 
?>

<div class="container my-5">
    <h1 class="mb-4">Acerca de ClubesPadel</h1>
    
    <div class="card">
        <div class="card-body">
            <h3>Sistema SaaS de Administración de Clubes de Pádel</h3>
            <p class="lead">ClubesPadel es una plataforma completa diseñada para modernizar y optimizar la gestión de clubes de pádel.</p>
            
            <h4 class="mt-4">Tecnología</h4>
            <ul>
                <li>PHP puro con arquitectura MVC</li>
                <li>MySQL 5.7+ para base de datos</li>
                <li>Bootstrap 5 para diseño responsivo</li>
                <li>Chart.js para visualización de datos</li>
                <li>FullCalendar para gestión de reservas</li>
            </ul>
            
            <h4 class="mt-4">Módulos Principales</h4>
            <ul>
                <li><strong>SuperAdmin:</strong> Gestión de clubes, planes y facturación</li>
                <li><strong>Gestión de Usuarios:</strong> Roles y permisos personalizados</li>
                <li><strong>Canchas:</strong> Administración completa de instalaciones</li>
                <li><strong>Reservaciones:</strong> Sistema avanzado con calendario</li>
                <li><strong>Finanzas:</strong> Control de ingresos, egresos y reportes</li>
                <li><strong>Torneos:</strong> Organización y seguimiento de competencias</li>
                <li><strong>Comunicación:</strong> Notificaciones y mensajería</li>
            </ul>
            
            <h4 class="mt-4">Versión</h4>
            <p><?php echo APP_VERSION; ?></p>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
