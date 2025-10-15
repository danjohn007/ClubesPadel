<?php require_once APP_PATH . '/Views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 p-0">
            <?php require_once APP_PATH . '/Views/layouts/sidebar.php'; ?>
        </div>
        
        <div class="col-md-9 col-lg-10 p-4">
            <div class="mb-4">
                <a href="<?php echo URL_BASE; ?>/courts" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
            
            <h2 class="mb-4"><i class="bi bi-plus-circle"></i> Nueva Cancha</h2>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="<?php echo URL_BASE; ?>/courts/create">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="court_type" class="form-label">Tipo de Cancha *</label>
                                <select class="form-select" id="court_type" name="court_type" required>
                                    <option value="outdoor">Exterior</option>
                                    <option value="indoor">Interior</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="surface" class="form-label">Superficie</label>
                                <input type="text" class="form-control" id="surface" name="surface" 
                                       placeholder="Ej: Césped sintético, Cristal">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="hourly_price" class="form-label">Precio por Hora *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="hourly_price" name="hourly_price" 
                                           step="0.01" min="0" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Estado *</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="available">Disponible</option>
                                    <option value="maintenance">En Mantenimiento</option>
                                    <option value="unavailable">No Disponible</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-check mt-4">
                                    <input type="checkbox" class="form-check-input" id="has_lighting" name="has_lighting">
                                    <label class="form-check-label" for="has_lighting">
                                        <i class="bi bi-lightbulb-fill"></i> Tiene Iluminación
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Guardar Cancha
                            </button>
                            <a href="<?php echo URL_BASE; ?>/courts" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
