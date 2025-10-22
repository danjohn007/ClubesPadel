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
                    <a class="nav-link active" href="<?php echo URL_BASE; ?>/superadmin/clubs">
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
                                                    <button class="btn btn-info view-club-btn" 
                                                            data-id="<?php echo $club['id']; ?>"
                                                            data-name="<?php echo htmlspecialchars($club['name']); ?>"
                                                            data-subdomain="<?php echo htmlspecialchars($club['subdomain']); ?>"
                                                            data-email="<?php echo htmlspecialchars($club['email']); ?>"
                                                            data-phone="<?php echo htmlspecialchars($club['phone'] ?? ''); ?>"
                                                            data-address="<?php echo htmlspecialchars($club['address'] ?? ''); ?>"
                                                            data-city="<?php echo htmlspecialchars($club['city'] ?? ''); ?>"
                                                            data-state="<?php echo htmlspecialchars($club['state'] ?? ''); ?>"
                                                            data-country="<?php echo htmlspecialchars($club['country'] ?? ''); ?>"
                                                            data-plan="<?php echo htmlspecialchars($club['plan_name']); ?>"
                                                            data-status="<?php echo $club['subscription_status']; ?>"
                                                            data-created="<?php echo date('d/m/Y', strtotime($club['created_at'])); ?>"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#viewClubModal"
                                                            title="Ver detalles">
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
                                                    <?php if ($club['subscription_status'] === 'active' || $club['subscription_status'] === 'trial'): ?>
                                                    <button class="btn btn-danger suspend-club-btn" 
                                                            data-id="<?php echo $club['id']; ?>"
                                                            data-name="<?php echo htmlspecialchars($club['name']); ?>"
                                                            title="Suspender">
                                                        <i class="bi bi-pause-circle"></i>
                                                    </button>
                                                    <?php elseif ($club['subscription_status'] === 'suspended'): ?>
                                                    <button class="btn btn-success reactivate-club-btn" 
                                                            data-id="<?php echo $club['id']; ?>"
                                                            data-name="<?php echo htmlspecialchars($club['name']); ?>"
                                                            title="Reactivar">
                                                        <i class="bi bi-play-circle"></i>
                                                    </button>
                                                    <?php endif; ?>
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
                            <label class="form-label">País *</label>
                            <select class="form-select" name="country" id="create_country" required>
                                <option value="México" selected>México</option>
                                <option value="España">España</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Colombia">Colombia</option>
                                <option value="Chile">Chile</option>
                                <option value="Perú">Perú</option>
                                <option value="Estados Unidos">Estados Unidos</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Estado *</label>
                            <select class="form-select" name="state" id="create_state" required>
                                <option value="">Seleccionar...</option>
                                <option value="Aguascalientes">Aguascalientes</option>
                                <option value="Baja California">Baja California</option>
                                <option value="Baja California Sur">Baja California Sur</option>
                                <option value="Campeche">Campeche</option>
                                <option value="Chiapas">Chiapas</option>
                                <option value="Chihuahua">Chihuahua</option>
                                <option value="Ciudad de México">Ciudad de México</option>
                                <option value="Coahuila">Coahuila</option>
                                <option value="Colima">Colima</option>
                                <option value="Durango">Durango</option>
                                <option value="Estado de México">Estado de México</option>
                                <option value="Guanajuato">Guanajuato</option>
                                <option value="Guerrero">Guerrero</option>
                                <option value="Hidalgo">Hidalgo</option>
                                <option value="Jalisco">Jalisco</option>
                                <option value="Michoacán">Michoacán</option>
                                <option value="Morelos">Morelos</option>
                                <option value="Nayarit">Nayarit</option>
                                <option value="Nuevo León">Nuevo León</option>
                                <option value="Oaxaca">Oaxaca</option>
                                <option value="Puebla">Puebla</option>
                                <option value="Querétaro">Querétaro</option>
                                <option value="Quintana Roo">Quintana Roo</option>
                                <option value="San Luis Potosí">San Luis Potosí</option>
                                <option value="Sinaloa">Sinaloa</option>
                                <option value="Sonora">Sonora</option>
                                <option value="Tabasco">Tabasco</option>
                                <option value="Tamaulipas">Tamaulipas</option>
                                <option value="Tlaxcala">Tlaxcala</option>
                                <option value="Veracruz">Veracruz</option>
                                <option value="Yucatán">Yucatán</option>
                                <option value="Zacatecas">Zacatecas</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Ciudad *</label>
                            <input type="text" class="form-control" name="city" required placeholder="Ej. Guadalajara">
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
                        <label class="form-label">Nombre del Club</label>
                        <input type="text" class="form-control" name="name" id="edit_name" readonly style="background-color: #e9ecef;">
                        <small class="text-muted">El nombre del club no se puede modificar</small>
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

<!-- View Club Modal -->
<div class="modal fade" id="viewClubModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-eye"></i> Detalles del Club</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nombre del Club</label>
                        <p class="form-control-plaintext" id="view_name"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Subdominio</label>
                        <p class="form-control-plaintext"><code id="view_subdomain"></code></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email</label>
                        <p class="form-control-plaintext" id="view_email"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Teléfono</label>
                        <p class="form-control-plaintext" id="view_phone"></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label fw-bold">Dirección</label>
                        <p class="form-control-plaintext" id="view_address"></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Ciudad</label>
                        <p class="form-control-plaintext" id="view_city"></p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Estado</label>
                        <p class="form-control-plaintext" id="view_state"></p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">País</label>
                        <p class="form-control-plaintext" id="view_country"></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Plan</label>
                        <p class="form-control-plaintext"><span class="badge bg-info" id="view_plan"></span></p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Estado</label>
                        <p class="form-control-plaintext"><span class="badge" id="view_status"></span></p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Fecha de Registro</label>
                        <p class="form-control-plaintext" id="view_created"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
// Fill view modal with club data
document.querySelectorAll('.view-club-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('view_name').textContent = this.dataset.name;
        document.getElementById('view_subdomain').textContent = this.dataset.subdomain;
        document.getElementById('view_email').textContent = this.dataset.email;
        document.getElementById('view_phone').textContent = this.dataset.phone || 'N/A';
        document.getElementById('view_address').textContent = this.dataset.address || 'N/A';
        document.getElementById('view_city').textContent = this.dataset.city || 'N/A';
        document.getElementById('view_state').textContent = this.dataset.state || 'N/A';
        document.getElementById('view_country').textContent = this.dataset.country || 'N/A';
        document.getElementById('view_plan').textContent = this.dataset.plan;
        document.getElementById('view_created').textContent = this.dataset.created;
        
        const statusBadge = document.getElementById('view_status');
        const statusMap = {
            'trial': { label: 'Prueba', class: 'bg-info' },
            'active': { label: 'Activo', class: 'bg-success' },
            'suspended': { label: 'Suspendido', class: 'bg-warning' },
            'cancelled': { label: 'Cancelado', class: 'bg-danger' }
        };
        const status = statusMap[this.dataset.status] || { label: this.dataset.status, class: 'bg-secondary' };
        statusBadge.textContent = status.label;
        statusBadge.className = 'badge ' + status.class;
    });
});

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

// Handle suspend club
document.querySelectorAll('.suspend-club-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        if (confirm('¿Está seguro de suspender el club "' + this.dataset.name + '"? El club no podrá acceder al sistema hasta que sea reactivado.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo URL_BASE; ?>/superadmin/clubs';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'suspend';
            
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'club_id';
            idInput.value = this.dataset.id;
            
            form.appendChild(actionInput);
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
});

// Handle reactivate club
document.querySelectorAll('.reactivate-club-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        if (confirm('¿Está seguro de reactivar el club "' + this.dataset.name + '"?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo URL_BASE; ?>/superadmin/clubs';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'reactivate';
            
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'club_id';
            idInput.value = this.dataset.id;
            
            form.appendChild(actionInput);
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
});
</script>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
