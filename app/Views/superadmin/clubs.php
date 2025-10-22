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
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/reports">
                        <i class="bi bi-graph-up"></i> Reportes
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/settings">
                        <i class="bi bi-gear"></i> Configuración
                    </a>
                </nav>
            </div>
        </div>
        
        <div class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-building"></i> Gestión de Clubes</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createClubModal">
                    <i class="bi bi-plus-circle"></i> Nuevo Club
                </button>
            </div>
            
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
                                                    <button class="btn btn-warning edit-club-btn" 
                                                            data-id="<?php echo $club['id']; ?>"
                                                            data-name="<?php echo htmlspecialchars($club['name']); ?>"
                                                            data-email="<?php echo htmlspecialchars($club['email']); ?>"
                                                            data-phone="<?php echo htmlspecialchars($club['phone'] ?? ''); ?>"
                                                            data-address="<?php echo htmlspecialchars($club['address'] ?? ''); ?>"
                                                            data-city="<?php echo htmlspecialchars($club['city'] ?? ''); ?>"
                                                            data-state="<?php echo htmlspecialchars($club['state'] ?? ''); ?>"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editClubModal"
                                                            title="Editar">
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

<!-- Create Club Modal -->
<div class="modal fade" id="createClubModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="<?php echo URL_BASE; ?>/superadmin/clubs">
                <input type="hidden" name="action" value="create">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-building"></i> Crear Nuevo Club</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre del Club *</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Subdominio</label>
                            <input type="text" class="form-control" name="subdomain" placeholder="Se generará automáticamente si se deja vacío">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" name="phone" pattern="[0-9]{10}" maxlength="10">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <input type="text" class="form-control" name="address">
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Ciudad</label>
                            <input type="text" class="form-control" name="city">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Estado</label>
                            <input type="text" class="form-control" name="state">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">País</label>
                            <input type="text" class="form-control" name="country" value="México">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Plan de Suscripción *</label>
                        <select class="form-select" name="subscription_plan_id" required>
                            <?php foreach ($plans as $plan): ?>
                                <option value="<?php echo $plan['id']; ?>">
                                    <?php echo htmlspecialchars($plan['name']); ?> - $<?php echo number_format($plan['price_monthly'], 2); ?>/mes
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Crear Club
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Club Modal -->
<div class="modal fade" id="editClubModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="<?php echo URL_BASE; ?>/superadmin/clubs">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="club_id" id="edit_club_id">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil"></i> Editar Club</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre del Club *</label>
                        <input type="text" class="form-control" name="name" id="edit_name" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" id="edit_email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" name="phone" id="edit_phone" pattern="[0-9]{10}" maxlength="10">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <input type="text" class="form-control" name="address" id="edit_address">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ciudad</label>
                            <input type="text" class="form-control" name="city" id="edit_city">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estado</label>
                            <input type="text" class="form-control" name="state" id="edit_state">
                        </div>
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
// Fill edit modal with club data
document.querySelectorAll('.edit-club-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('edit_club_id').value = this.dataset.id;
        document.getElementById('edit_name').value = this.dataset.name;
        document.getElementById('edit_email').value = this.dataset.email;
        document.getElementById('edit_phone').value = this.dataset.phone;
        document.getElementById('edit_address').value = this.dataset.address;
        document.getElementById('edit_city').value = this.dataset.city;
        document.getElementById('edit_state').value = this.dataset.state;
    });
});
</script>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
