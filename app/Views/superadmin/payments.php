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
        <!-- SuperAdmin Sidebar -->
        <!-- Sidebar moved to overlay -->
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
        <div class="col-12 p-4">
            <h2 class="mb-4"><i class="bi bi-credit-card"></i> Pagos de Clubes</h2>
            
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

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
