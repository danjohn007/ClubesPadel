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
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/clubs">
                        <i class="bi bi-building"></i> Clubes
                    </a>
                    <a class="nav-link active" href="<?php echo URL_BASE; ?>/superadmin/plans">
                        <i class="bi bi-card-checklist"></i> Planes
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/payments">
                        <i class="bi bi-credit-card"></i> Pagos
                    </a>
                </nav>
            </div>
        </div>
        
        <div class="col-md-9 col-lg-10 p-4">
            <h2 class="mb-4"><i class="bi bi-card-checklist"></i> Planes de Suscripción</h2>
            
            <?php if (!empty($plans)): ?>
                <div class="row">
                    <?php foreach ($plans as $plan): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-header text-center bg-primary text-white">
                                    <h4><?php echo htmlspecialchars($plan['name']); ?></h4>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <h2 class="text-primary">$<?php echo number_format($plan['price_monthly'], 2); ?></h2>
                                        <small class="text-muted">por mes</small>
                                    </div>
                                    
                                    <p class="text-muted"><?php echo htmlspecialchars($plan['description']); ?></p>
                                    
                                    <hr>
                                    
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <i class="bi bi-check-circle text-success"></i>
                                            <strong>Usuarios:</strong> <?php echo $plan['max_users'] ?? 'Ilimitados'; ?>
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-check-circle text-success"></i>
                                            <strong>Canchas:</strong> <?php echo $plan['max_courts'] ?? 'Ilimitadas'; ?>
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-check-circle text-success"></i>
                                            <strong>Torneos:</strong> <?php echo $plan['max_tournaments'] ?? 'Ilimitados'; ?>
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-check-circle text-success"></i>
                                            <strong>Almacenamiento:</strong> <?php echo $plan['max_storage_mb']; ?> MB
                                        </li>
                                    </ul>
                                    
                                    <?php if ($plan['features']): ?>
                                        <hr>
                                        <p class="small text-muted"><?php echo htmlspecialchars($plan['features']); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer text-center">
                                    <button class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-pencil"></i> Editar Plan
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-card-checklist" style="font-size: 4rem; color: #ccc;"></i>
                        <h4 class="mt-3">No hay planes configurados</h4>
                        <button class="btn btn-primary mt-2">
                            <i class="bi bi-plus-circle"></i> Crear Primer Plan
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
