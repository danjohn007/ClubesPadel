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
                <h2><i class="bi bi-arrow-up-circle text-danger"></i> Egresos</h2>
                <div>
                    <a href="<?php echo URL_BASE; ?>/finances" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                    <a href="<?php echo URL_BASE; ?>/finances/createExpense" class="btn btn-danger">
                        <i class="bi bi-plus-circle"></i> Nuevo Egreso
                    </a>
                </div>
            </div>
            
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle"></i> Egreso registrado exitosamente
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($expenses)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Categoría</th>
                                        <th>Descripción</th>
                                        <th>Método de Pago</th>
                                        <th class="text-end">Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total = 0;
                                    foreach ($expenses as $expense): 
                                        $total += $expense['amount'];
                                    ?>
                                        <tr>
                                            <td><?php echo date('d/m/Y', strtotime($expense['transaction_date'])); ?></td>
                                            <td>
                                                <span class="badge bg-danger">
                                                    <?php echo htmlspecialchars($expense['category_name'] ?? 'Sin categoría'); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($expense['description'] ?? '-'); ?></td>
                                            <td>
                                                <?php 
                                                $methods = [
                                                    'cash' => 'Efectivo',
                                                    'credit_card' => 'T. Crédito',
                                                    'debit_card' => 'T. Débito',
                                                    'bank_transfer' => 'Transferencia',
                                                    'check' => 'Cheque',
                                                    'other' => 'Otro'
                                                ];
                                                echo $methods[$expense['payment_method']] ?? $expense['payment_method'];
                                                ?>
                                            </td>
                                            <td class="text-end">
                                                <strong class="text-danger">$<?php echo number_format($expense['amount'], 2); ?></strong>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                        <td class="text-end">
                                            <strong class="text-danger fs-5">$<?php echo number_format($total, 2); ?></strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-arrow-up-circle text-danger" style="font-size: 4rem; opacity: 0.3;"></i>
                            <h4 class="mt-3">No hay egresos registrados</h4>
                            <p class="text-muted">Comienza registrando tu primer egreso</p>
                            <a href="<?php echo URL_BASE; ?>/finances/createExpense" class="btn btn-danger mt-2">
                                <i class="bi bi-plus-circle"></i> Registrar Egreso
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
