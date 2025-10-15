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
                    <a class="nav-link active" href="<?php echo URL_BASE; ?>/superadmin/clubs">
                        <i class="bi bi-building"></i> Clubes
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/plans">
                        <i class="bi bi-card-checklist"></i> Planes
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/payments">
                        <i class="bi bi-credit-card"></i> Pagos
                    </a>
                </nav>
            </div>
        </div>
        
        <div class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-building"></i> Gestión de Clubes</h2>
                <button class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nuevo Club
                </button>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($clubs)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Subdominio</th>
                                        <th>Email</th>
                                        <th>Plan</th>
                                        <th>Estado</th>
                                        <th>Fecha Registro</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($clubs as $club): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($club['name']); ?></strong></td>
                                            <td><code><?php echo htmlspecialchars($club['subdomain']); ?></code></td>
                                            <td><?php echo htmlspecialchars($club['email']); ?></td>
                                            <td>
                                                <span class="badge bg-info"><?php echo htmlspecialchars($club['plan_name']); ?></span>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = [
                                                    'trial' => 'info',
                                                    'active' => 'success',
                                                    'suspended' => 'warning',
                                                    'cancelled' => 'danger'
                                                ];
                                                $statusLabel = [
                                                    'trial' => 'Prueba',
                                                    'active' => 'Activo',
                                                    'suspended' => 'Suspendido',
                                                    'cancelled' => 'Cancelado'
                                                ];
                                                ?>
                                                <span class="badge bg-<?php echo $statusClass[$club['subscription_status']] ?? 'secondary'; ?>">
                                                    <?php echo $statusLabel[$club['subscription_status']] ?? $club['subscription_status']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($club['created_at'])); ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-info" title="Ver detalles">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button class="btn btn-warning" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">No hay clubes registrados</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
