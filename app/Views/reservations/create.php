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
                <h2><i class="bi bi-calendar-plus"></i> Nueva Reservación</h2>
                <a href="<?php echo URL_BASE; ?>/reservations" class="btn btn-secondary">
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
                    <form method="POST" action="<?php echo URL_BASE; ?>/reservations/create">
                        <?php if (!empty($users)): ?>
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Usuario *</label>
                            <select class="form-select" id="user_id" name="user_id" required>
                                <option value="">Selecciona un usuario</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?php echo $user['id']; ?>">
                                        <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name'] . ' (' . $user['email'] . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="court_id" class="form-label">Cancha *</label>
                            <select class="form-select" id="court_id" name="court_id" required>
                                <option value="">Selecciona una cancha</option>
                                <?php foreach ($courts as $court): ?>
                                    <?php if ($court['status'] === 'available'): ?>
                                        <option value="<?php echo $court['id']; ?>" data-price="<?php echo $court['hourly_price']; ?>">
                                            <?php echo htmlspecialchars($court['name']); ?> 
                                            - $<?php echo number_format($court['hourly_price'], 2); ?>/hora
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="reservation_date" class="form-label">Fecha *</label>
                                <input type="date" class="form-control" id="reservation_date" name="reservation_date" 
                                       min="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="start_time" class="form-label">Hora Inicio *</label>
                                <input type="time" class="form-control" id="start_time" name="start_time" required>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="end_time" class="form-label">Hora Fin *</label>
                                <input type="time" class="form-control" id="end_time" name="end_time" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notas</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                      placeholder="Notas adicionales sobre la reservación"></textarea>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Total estimado:</strong> <span id="total_price">$0.00</span>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?php echo URL_BASE; ?>/reservations" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Crear Reservación
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Calculate total price
function calculateTotal() {
    const courtSelect = document.getElementById('court_id');
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;
    
    if (courtSelect.value && startTime && endTime) {
        const hourlyPrice = parseFloat(courtSelect.options[courtSelect.selectedIndex].dataset.price || 0);
        
        // Calculate duration in hours
        const start = new Date('2000-01-01 ' + startTime);
        const end = new Date('2000-01-01 ' + endTime);
        const durationMs = end - start;
        const durationHours = durationMs / (1000 * 60 * 60);
        
        if (durationHours > 0) {
            const total = hourlyPrice * durationHours;
            document.getElementById('total_price').textContent = '$' + total.toFixed(2);
        } else {
            document.getElementById('total_price').textContent = '$0.00';
        }
    }
}

document.getElementById('court_id').addEventListener('change', calculateTotal);
document.getElementById('start_time').addEventListener('change', calculateTotal);
document.getElementById('end_time').addEventListener('change', calculateTotal);
</script>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
