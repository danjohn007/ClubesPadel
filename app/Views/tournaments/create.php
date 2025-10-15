<?php 
// Ensure configuration is loaded
if (!defined('APP_PATH')) {
    require_once __DIR__ . '/../../../config/config.php';
}
require_once APP_PATH . '/Views/layouts/header.php'; 
require_once APP_PATH . '/Views/layouts/sidebar.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-trophy"></i> Crear Torneo</h2>
                <a href="<?php echo URL_BASE; ?>/tournaments" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
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
                    <form method="POST" action="<?php echo URL_BASE; ?>/tournaments/create" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre del Torneo *</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tournament_type" class="form-label">Tipo de Torneo *</label>
                                        <select class="form-select" id="tournament_type" name="tournament_type" required>
                                            <option value="">Selecciona un tipo</option>
                                            <option value="single">Individual</option>
                                            <option value="double">Dobles</option>
                                            <option value="mixed">Mixto</option>
                                            <option value="team">Por Equipos</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="category" class="form-label">Categoría</label>
                                        <select class="form-select" id="category" name="category">
                                            <option value="">Selecciona una categoría</option>
                                            <option value="open">Abierto</option>
                                            <option value="beginner">Principiante</option>
                                            <option value="intermediate">Intermedio</option>
                                            <option value="advanced">Avanzado</option>
                                            <option value="professional">Profesional</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="start_date" class="form-label">Fecha de Inicio *</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="end_date" class="form-label">Fecha de Fin *</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="registration_start" class="form-label">Inicio de Inscripciones *</label>
                                        <input type="date" class="form-control" id="registration_start" name="registration_start" required>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="registration_end" class="form-label">Fin de Inscripciones *</label>
                                        <input type="date" class="form-control" id="registration_end" name="registration_end" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="max_participants" class="form-label">Máximo de Participantes *</label>
                                    <input type="number" class="form-control" id="max_participants" name="max_participants" 
                                           min="2" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="registration_fee" class="form-label">Cuota de Inscripción *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="registration_fee" 
                                               name="registration_fee" step="0.01" min="0" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="prize_pool" class="form-label">Bolsa de Premios</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="prize_pool" 
                                               name="prize_pool" step="0.01" min="0">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="image" class="form-label">Imagen del Torneo</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <small class="text-muted">Formatos: JPG, PNG, GIF (máx. 5MB)</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="status" class="form-label">Estado</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="draft">Borrador</option>
                                        <option value="registration">Inscripciones Abiertas</option>
                                        <option value="upcoming">Próximo</option>
                                        <option value="in_progress">En Curso</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo URL_BASE; ?>/tournaments" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Crear Torneo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Set minimum dates for date inputs
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('start_date').min = today;
    document.getElementById('end_date').min = today;
    document.getElementById('registration_start').min = today;
    document.getElementById('registration_end').min = today;
    
    // Validate end date is after start date
    document.getElementById('end_date').addEventListener('change', function() {
        const startDate = document.getElementById('start_date').value;
        const endDate = this.value;
        if (startDate && endDate && endDate < startDate) {
            alert('La fecha de fin debe ser posterior a la fecha de inicio');
            this.value = '';
        }
    });
    
    // Validate registration end is before start date
    document.getElementById('registration_end').addEventListener('change', function() {
        const startDate = document.getElementById('start_date').value;
        const regEnd = this.value;
        if (startDate && regEnd && regEnd >= startDate) {
            alert('Las inscripciones deben cerrar antes del inicio del torneo');
            this.value = '';
        }
    });
});
</script>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
