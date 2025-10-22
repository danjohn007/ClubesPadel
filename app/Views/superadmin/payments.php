<?php 
// Ensure configuration is loaded
if (!defined('APP_PATH')) {
    require_once __DIR__ . '/../../../config/config.php';
}
require_once APP_PATH . '/Views/layouts/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <!-- SuperAdmin Sidebar -->
        <div class="col-md-3 col-lg-2 p-0">
            <div class="sidebar p-3">
                <div class="mb-4">
                    <h5 class="text-white"><i class="bi bi-shield-check"></i> SuperAdmin</h5>
                </div>
                
                <nav class="nav flex-column">
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/dashboard">
                        <i class="bi bi-house-door"></i> Dashboard
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/clubs">
                        <i class="bi bi-building"></i> Clubes
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/plans">
                        <i class="bi bi-card-checklist"></i> Planes
                    </a>
                    <a class="nav-link active" href="<?php echo URL_BASE; ?>/superadmin/payments">
                        <i class="bi bi-credit-card"></i> Pagos
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/reports">
                        <i class="bi bi-graph-up"></i> Reportes
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/settings">
                        <i class="bi bi-gear"></i> Configuración
                    </a>
                </nav>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-credit-card"></i> Pagos de Clubes</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPaymentModal">
                    <i class="bi bi-plus-circle"></i> Registrar Pago
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
                    <?php if (!empty($payments)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Club</th>
                                        <th>Monto</th>
                                        <th>Fecha de Pago</th>
                                        <th>Método de Pago</th>
                                        <th>Estado</th>
                                        <th>Creado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($payments as $payment): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($payment['id']); ?></td>
                                            <td><strong><?php echo htmlspecialchars($payment['club_name']); ?></strong></td>
                                            <td><strong>$<?php echo number_format($payment['amount'], 2); ?></strong></td>
                                            <td><?php echo date('d/m/Y', strtotime($payment['payment_date'])); ?></td>
                                            <td>
                                                <?php 
                                                $methods = [
                                                    'credit_card' => '<i class="bi bi-credit-card"></i> Tarjeta de Crédito',
                                                    'debit_card' => '<i class="bi bi-credit-card-2-front"></i> Tarjeta de Débito',
                                                    'bank_transfer' => '<i class="bi bi-bank"></i> Transferencia',
                                                    'cash' => '<i class="bi bi-cash"></i> Efectivo'
                                                ];
                                                echo $methods[$payment['payment_method']] ?? htmlspecialchars($payment['payment_method']);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = [
                                                    'completed' => 'success',
                                                    'pending' => 'warning',
                                                    'failed' => 'danger',
                                                    'refunded' => 'secondary'
                                                ];
                                                $statusLabel = [
                                                    'completed' => 'Completado',
                                                    'pending' => 'Pendiente',
                                                    'failed' => 'Fallido',
                                                    'refunded' => 'Reembolsado'
                                                ];
                                                ?>
                                                <span class="badge bg-<?php echo $statusClass[$payment['status']] ?? 'secondary'; ?>">
                                                    <?php echo $statusLabel[$payment['status']] ?? $payment['status']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($payment['created_at'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-credit-card" style="font-size: 4rem; color: #ccc;"></i>
                            <h4 class="mt-3">No hay pagos registrados</h4>
                            <p class="text-muted">Los pagos de los clubes aparecerán aquí</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Payment Modal -->
<div class="modal fade" id="createPaymentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="<?php echo URL_BASE; ?>/superadmin/payments">
                <input type="hidden" name="action" value="create">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-credit-card"></i> Registrar Nuevo Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Club *</label>
                        <select class="form-select" name="club_id" required>
                            <option value="">Seleccionar club...</option>
                            <?php if (!empty($clubs)): ?>
                                <?php foreach ($clubs as $club): ?>
                                    <option value="<?php echo $club['id']; ?>">
                                        <?php echo htmlspecialchars($club['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Monto *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" name="amount" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Moneda</label>
                            <select class="form-select" name="currency">
                                <option value="MXN">MXN - Peso Mexicano</option>
                                <option value="USD">USD - Dólar</option>
                                <option value="EUR">EUR - Euro</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Método de Pago</label>
                            <select class="form-select" name="payment_method">
                                <option value="">Seleccionar...</option>
                                <option value="credit_card">Tarjeta de Crédito</option>
                                <option value="debit_card">Tarjeta de Débito</option>
                                <option value="bank_transfer">Transferencia Bancaria</option>
                                <option value="cash">Efectivo</option>
                                <option value="paypal">PayPal</option>
                                <option value="other">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select" name="status">
                                <option value="completed">Completado</option>
                                <option value="pending">Pendiente</option>
                                <option value="failed">Fallido</option>
                                <option value="refunded">Reembolsado</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ID de Transacción</label>
                        <input type="text" class="form-control" name="transaction_id" placeholder="Referencia o ID de transacción">
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fecha de Pago</label>
                            <input type="date" class="form-control" name="payment_date" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Inicio del Período</label>
                            <input type="date" class="form-control" name="period_start">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fin del Período</label>
                            <input type="date" class="form-control" name="period_end">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notas</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Notas adicionales sobre el pago"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Registrar Pago
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
