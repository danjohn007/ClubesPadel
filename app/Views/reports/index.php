<?php 
// Ensure configuration is loaded
if (!defined('APP_PATH')) {
    require_once __DIR__ . '/../../../config/config.php';
}
require_once APP_PATH . '/Views/layouts/header.php'; 
require_once APP_PATH . '/Views/layouts/sidebar.php'; 
?>

<div class="container-fluid">
    <div class="row">
        
        
        <div class="col-12 p-4">
            <h2 class="mb-4"><i class="bi bi-file-earmark-bar-graph"></i> Reportes</h2>
            
            <div class="row">
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-calendar-check text-primary" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Reporte de Reservaciones</h5>
                            <p class="text-muted">Estadísticas y análisis de reservaciones</p>
                            <a href="<?php echo URL_BASE; ?>/reports/reservations" class="btn btn-primary btn-sm">
                                <i class="bi bi-bar-chart"></i> Ver Reporte
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-cash-coin text-success" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Reporte Financiero</h5>
                            <p class="text-muted">Balance de ingresos y egresos</p>
                            <a href="<?php echo URL_BASE; ?>/reports/finances" class="btn btn-success btn-sm">
                                <i class="bi bi-bar-chart"></i> Ver Reporte
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-people text-info" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Reporte de Usuarios</h5>
                            <p class="text-muted">Estadísticas de usuarios y jugadores</p>
                            <a href="<?php echo URL_BASE; ?>/reports/users" class="btn btn-info btn-sm">
                                <i class="bi bi-bar-chart"></i> Ver Reporte
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-trophy text-warning" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Reporte de Torneos</h5>
                            <p class="text-muted">Análisis de torneos y participación</p>
                            <a href="<?php echo URL_BASE; ?>/reports/tournaments" class="btn btn-warning btn-sm">
                                <i class="bi bi-bar-chart"></i> Ver Reporte
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
