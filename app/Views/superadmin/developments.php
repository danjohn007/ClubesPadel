<?php 
// Ensure configuration is loaded
if (!defined('APP_PATH')) {
    require_once __DIR__ . '/../../../config/config.php';
}
require_once APP_PATH . '/Views/layouts/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 p-0">
            <div class="sidebar p-3">
                <div class="mb-4">
                    <h5 class="text-white"><i class="bi bi-shield-check"></i> SuperAdmin</h5>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/dashboard">
                        <i class="bi bi-house-door"></i> Dashboard
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/users">
                        <i class="bi bi-people"></i> CRM Usuarios
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/clubs">
                        <i class="bi bi-building"></i> CRM Clubes
                    </a>
                    <a class="nav-link active" href="<?php echo URL_BASE; ?>/superadmin/developments">
                        <i class="bi bi-buildings"></i> CRM Desarrollos
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/sports">
                        <i class="bi bi-trophy"></i> CRM Deportivas
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/sponsors">
                        <i class="bi bi-briefcase"></i> Patrocinadores
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/loyalty">
                        <i class="bi bi-star"></i> Sistema de Lealtad
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/plans">
                        <i class="bi bi-card-checklist"></i> Planes
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/payments">
                        <i class="bi bi-credit-card"></i> Pagos
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/reports">
                        <i class="bi bi-graph-up"></i> Reportes Financieros
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/settings">
                        <i class="bi bi-gear"></i> Configuración
                    </a>
                </nav>
            </div>
        </div>
        
        <div class="col-md-9 col-lg-10 p-4">
            <h2><i class="bi bi-buildings"></i> CRM Desarrollos</h2>
            <p class="text-muted mb-4">Gestión de desarrollos inmobiliarios y complejos deportivos</p>
            
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Módulo en desarrollo. Próximamente podrás gestionar desarrollos inmobiliarios asociados a clubes deportivos.
            </div>
            
            <div class="card">
                <div class="card-body">
                    <p class="text-muted text-center py-5">
                        <i class="bi bi-buildings" style="font-size: 3rem;"></i><br>
                        No hay desarrollos registrados
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
