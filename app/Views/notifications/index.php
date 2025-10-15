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
            <h2 class="mb-4"><i class="bi bi-bell"></i> Notificaciones</h2>
            
            <div class="card">
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="bi bi-bell" style="font-size: 4rem; color: #ccc;"></i>
                        <h4 class="mt-3">No hay notificaciones</h4>
                        <p class="text-muted">Aquí aparecerán tus notificaciones importantes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
