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
            <h2 class="mb-4"><i class="bi bi-gear"></i> Ajustes</h2>
            
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
                <div class="card-header">
                    <h5 class="mb-0">Configuración del Club</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo URL_BASE; ?>/settings">
                        <div class="mb-3">
                            <label for="club_name" class="form-label">Nombre del Club</label>
                            <input type="text" class="form-control" id="club_name" name="club_name" 
                                   placeholder="Nombre del club">
                        </div>
                        
                        <div class="mb-3">
                            <label for="club_email" class="form-label">Email de Contacto</label>
                            <input type="email" class="form-control" id="club_email" name="club_email" 
                                   placeholder="email@club.com">
                        </div>
                        
                        <div class="mb-3">
                            <label for="club_phone" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="club_phone" name="club_phone" 
                                   placeholder="Teléfono del club">
                        </div>
                        
                        <div class="mb-3">
                            <label for="club_address" class="form-label">Dirección</label>
                            <textarea class="form-control" id="club_address" name="club_address" rows="3" 
                                      placeholder="Dirección completa del club"></textarea>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
