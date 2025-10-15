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
            <?php require_once APP_PATH . '/Views/layouts/sidebar.php'; ?>
        </div>
        
        <div class="col-md-9 col-lg-10 p-4">
            <h2 class="mb-4"><i class="bi bi-people"></i> Gestión de Usuarios</h2>
            
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($users)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>Rol</th>
                                        <th>Estado</th>
                                        <th>Registrado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td><?php echo htmlspecialchars($user['phone'] ?? '-'); ?></td>
                                            <td>
                                                <?php
                                                $roleClass = [
                                                    'admin' => 'danger',
                                                    'receptionist' => 'warning',
                                                    'player' => 'primary'
                                                ];
                                                $roleLabel = [
                                                    'admin' => 'Administrador',
                                                    'receptionist' => 'Recepcionista',
                                                    'player' => 'Jugador'
                                                ];
                                                ?>
                                                <span class="badge bg-<?php echo $roleClass[$user['role']] ?? 'secondary'; ?>">
                                                    <?php echo $roleLabel[$user['role']] ?? $user['role']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($user['is_active']): ?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactivo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-people" style="font-size: 4rem; color: #ccc;"></i>
                            <h4 class="mt-3">No hay usuarios registrados</h4>
                            <p class="text-muted">Los usuarios aparecerán aquí cuando se registren</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
