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
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/developments">
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
                    <a class="nav-link active" href="<?php echo URL_BASE; ?>/superadmin/plans">
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
            <h2 class="mb-4"><i class="bi bi-card-checklist"></i> Planes de Suscripción</h2>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle"></i> <?php echo $success; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
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
                                    <button class="btn btn-outline-primary btn-sm edit-plan-btn"
                                            data-id="<?php echo $plan['id']; ?>"
                                            data-name="<?php echo htmlspecialchars($plan['name']); ?>"
                                            data-description="<?php echo htmlspecialchars($plan['description'] ?? ''); ?>"
                                            data-price-monthly="<?php echo $plan['price_monthly']; ?>"
                                            data-price-yearly="<?php echo $plan['price_yearly']; ?>"
                                            data-max-users="<?php echo $plan['max_users'] ?? ''; ?>"
                                            data-max-courts="<?php echo $plan['max_courts'] ?? ''; ?>"
                                            data-max-tournaments="<?php echo $plan['max_tournaments'] ?? ''; ?>"
                                            data-max-storage="<?php echo $plan['max_storage_mb'] ?? ''; ?>"
                                            data-features="<?php echo htmlspecialchars($plan['features'] ?? ''); ?>"
                                            data-is-active="<?php echo $plan['is_active']; ?>"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editPlanModal">
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

<!-- Edit Plan Modal -->
<div class="modal fade" id="editPlanModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="<?php echo URL_BASE; ?>/superadmin/plans">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="plan_id" id="edit_plan_id">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil"></i> Editar Plan de Suscripción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre del Plan *</label>
                        <input type="text" class="form-control" name="name" id="edit_plan_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="description" id="edit_plan_description" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Precio Mensual *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" name="price_monthly" id="edit_price_monthly" 
                                       step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Precio Anual</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" name="price_yearly" id="edit_price_yearly" 
                                       step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Máximo de Usuarios</label>
                            <input type="number" class="form-control" name="max_users" id="edit_max_users" 
                                   placeholder="Dejar vacío para ilimitado">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Máximo de Canchas</label>
                            <input type="number" class="form-control" name="max_courts" id="edit_max_courts" 
                                   placeholder="Dejar vacío para ilimitado">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Máximo de Torneos</label>
                            <input type="number" class="form-control" name="max_tournaments" id="edit_max_tournaments" 
                                   placeholder="Dejar vacío para ilimitado">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Almacenamiento (MB)</label>
                            <input type="number" class="form-control" name="max_storage_mb" id="edit_max_storage" 
                                   placeholder="En megabytes">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Características Adicionales</label>
                        <textarea class="form-control" name="features" id="edit_features" rows="3" 
                                  placeholder="Características especiales del plan"></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active" value="1">
                        <label class="form-check-label" for="edit_is_active">
                            Plan Activo
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Fill edit modal with plan data
document.querySelectorAll('.edit-plan-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('edit_plan_id').value = this.dataset.id;
        document.getElementById('edit_plan_name').value = this.dataset.name;
        document.getElementById('edit_plan_description').value = this.dataset.description;
        document.getElementById('edit_price_monthly').value = this.dataset.priceMonthly;
        document.getElementById('edit_price_yearly').value = this.dataset.priceYearly;
        document.getElementById('edit_max_users').value = this.dataset.maxUsers;
        document.getElementById('edit_max_courts').value = this.dataset.maxCourts;
        document.getElementById('edit_max_tournaments').value = this.dataset.maxTournaments;
        document.getElementById('edit_max_storage').value = this.dataset.maxStorage;
        document.getElementById('edit_features').value = this.dataset.features;
        document.getElementById('edit_is_active').checked = this.dataset.isActive == '1';
    });
});
</script>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
