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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-grid-3x3"></i> Gestión de Canchas</h2>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <a href="<?php echo URL_BASE; ?>/courts/create" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nueva Cancha
                </a>
                <?php endif; ?>
            </div>
            
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?php
                    $messages = [
                        'created' => 'Cancha creada exitosamente',
                        'updated' => 'Cancha actualizada exitosamente',
                        'deleted' => 'Cancha eliminada exitosamente'
                    ];
                    echo $messages[$_GET['success']] ?? 'Operación exitosa';
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?php
                    $messages = [
                        'not_found' => 'Cancha no encontrada',
                        'delete_failed' => 'Error al eliminar la cancha'
                    ];
                    echo $messages[$_GET['error']] ?? 'Error en la operación';
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($courts)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Tipo</th>
                                        <th>Superficie</th>
                                        <th>Iluminación</th>
                                        <th>Precio/Hora</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($courts as $court): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($court['name']); ?></strong></td>
                                            <td>
                                                <?php 
                                                echo $court['court_type'] === 'indoor' ? 
                                                    '<span class="badge bg-info">Interior</span>' : 
                                                    '<span class="badge bg-success">Exterior</span>';
                                                ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($court['surface'] ?? 'N/A'); ?></td>
                                            <td>
                                                <?php echo $court['has_lighting'] ? 
                                                    '<i class="bi bi-lightbulb-fill text-warning"></i> Sí' : 
                                                    '<i class="bi bi-lightbulb text-muted"></i> No'; 
                                                ?>
                                            </td>
                                            <td><strong>$<?php echo number_format($court['hourly_price'], 2); ?></strong></td>
                                            <td>
                                                <?php
                                                $statusClass = [
                                                    'available' => 'success',
                                                    'maintenance' => 'warning',
                                                    'unavailable' => 'danger'
                                                ];
                                                $statusLabel = [
                                                    'available' => 'Disponible',
                                                    'maintenance' => 'Mantenimiento',
                                                    'unavailable' => 'No Disponible'
                                                ];
                                                ?>
                                                <span class="badge bg-<?php echo $statusClass[$court['status']] ?? 'secondary'; ?>">
                                                    <?php echo $statusLabel[$court['status']] ?? $court['status']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?php echo URL_BASE; ?>/courts/viewCourt/<?php echo $court['id']; ?>" 
                                                       class="btn btn-info" title="Ver detalles">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                                                    <a href="<?php echo URL_BASE; ?>/courts/edit/<?php echo $court['id']; ?>" 
                                                       class="btn btn-warning" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="<?php echo URL_BASE; ?>/courts/delete/<?php echo $court['id']; ?>" 
                                                       class="btn btn-danger" 
                                                       onclick="return confirm('¿Estás seguro de eliminar esta cancha?')"
                                                       title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-grid-3x3" style="font-size: 4rem; color: #ccc;"></i>
                            <h4 class="mt-3">No hay canchas registradas</h4>
                            <p class="text-muted">Comienza agregando tu primera cancha</p>
                            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                            <a href="<?php echo URL_BASE; ?>/courts/create" class="btn btn-primary mt-2">
                                <i class="bi bi-plus-circle"></i> Agregar Cancha
                            </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
