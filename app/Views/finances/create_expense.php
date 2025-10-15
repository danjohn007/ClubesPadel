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
                <h2><i class="bi bi-arrow-up-circle text-danger"></i> Registrar Egreso</h2>
                <a href="<?php echo URL_BASE; ?>/finances" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="<?php echo URL_BASE; ?>/finances/createExpense">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Categoría *</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Selecciona una categoría</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>">
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="1">Mantenimiento</option>
                                    <option value="2">Servicios Públicos</option>
                                    <option value="3">Salarios</option>
                                    <option value="4">Suministros</option>
                                    <option value="5">Otros</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="amount" class="form-label">Monto *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="amount" name="amount" 
                                       step="0.01" min="0" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="transaction_date" class="form-label">Fecha de Transacción *</label>
                            <input type="date" class="form-control" id="transaction_date" name="transaction_date" 
                                   value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Método de Pago</label>
                            <select class="form-select" id="payment_method" name="payment_method">
                                <option value="">Selecciona un método</option>
                                <option value="cash">Efectivo</option>
                                <option value="credit_card">Tarjeta de Crédito</option>
                                <option value="debit_card">Tarjeta de Débito</option>
                                <option value="bank_transfer">Transferencia Bancaria</option>
                                <option value="check">Cheque</option>
                                <option value="other">Otro</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control" id="description" name="description" rows="3" 
                                      placeholder="Detalles adicionales del egreso"></textarea>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?php echo URL_BASE; ?>/finances" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-check-circle"></i> Registrar Egreso
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
