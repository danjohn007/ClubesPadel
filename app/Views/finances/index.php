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
            <h2 class="mb-4"><i class="bi bi-cash-coin"></i> Módulo Financiero</h2>
            
            <!-- Balance Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                        <div class="label">Total Ingresos</div>
                        <div class="value">$<?php echo number_format($balance['total_income'], 2); ?></div>
                        <small><?php echo date('d/m/Y', strtotime($start_date)); ?> - <?php echo date('d/m/Y', strtotime($end_date)); ?></small>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <div class="label">Total Egresos</div>
                        <div class="value">$<?php echo number_format($balance['total_expenses'], 2); ?></div>
                        <small><?php echo date('d/m/Y', strtotime($start_date)); ?> - <?php echo date('d/m/Y', strtotime($end_date)); ?></small>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="label">Utilidad Neta</div>
                        <div class="value">$<?php echo number_format($balance['net_profit'], 2); ?></div>
                        <small><?php echo date('d/m/Y', strtotime($start_date)); ?> - <?php echo date('d/m/Y', strtotime($end_date)); ?></small>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="bi bi-arrow-down-circle text-success" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Registrar Ingreso</h5>
                            <p class="text-muted">Registra un nuevo ingreso en el sistema</p>
                            <a href="<?php echo URL_BASE; ?>/finances/createIncome" class="btn btn-success">
                                <i class="bi bi-plus-circle"></i> Nuevo Ingreso
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="bi bi-arrow-up-circle text-danger" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Registrar Egreso</h5>
                            <p class="text-muted">Registra un nuevo gasto en el sistema</p>
                            <a href="<?php echo URL_BASE; ?>/finances/createExpense" class="btn btn-danger">
                                <i class="bi bi-plus-circle"></i> Nuevo Egreso
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Charts -->
            <div class="row mb-4">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Ingresos por Categoría</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="incomeChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Egresos por Categoría</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="expensesChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Transactions -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="bi bi-arrow-down-circle text-success"></i> Ingresos Recientes</h5>
                            <a href="<?php echo URL_BASE; ?>/finances/income" class="btn btn-sm btn-outline-primary">Ver Todos</a>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($recent_income)): ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($recent_income as $income): ?>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong><?php echo htmlspecialchars($income['category_name']); ?></strong><br>
                                                <small class="text-muted">
                                                    <?php echo htmlspecialchars($income['description'] ?? 'Sin descripción'); ?><br>
                                                    <?php echo date('d/m/Y', strtotime($income['transaction_date'])); ?>
                                                </small>
                                            </div>
                                            <span class="badge bg-success">$<?php echo number_format($income['amount'], 2); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center py-3">No hay ingresos recientes</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="bi bi-arrow-up-circle text-danger"></i> Egresos Recientes</h5>
                            <a href="<?php echo URL_BASE; ?>/finances/expenses" class="btn btn-sm btn-outline-primary">Ver Todos</a>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($recent_expenses)): ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($recent_expenses as $expense): ?>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong><?php echo htmlspecialchars($expense['category_name']); ?></strong><br>
                                                <small class="text-muted">
                                                    <?php echo htmlspecialchars($expense['description'] ?? 'Sin descripción'); ?><br>
                                                    <?php echo date('d/m/Y', strtotime($expense['transaction_date'])); ?>
                                                </small>
                                            </div>
                                            <span class="badge bg-danger">$<?php echo number_format($expense['amount'], 2); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center py-3">No hay egresos recientes</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Income by Category Chart
    const incomeCtx = document.getElementById('incomeChart');
    if (incomeCtx) {
        const incomeData = <?php echo json_encode($income_by_category); ?>;
        new Chart(incomeCtx, {
            type: 'doughnut',
            data: {
                labels: incomeData.map(item => item.name),
                datasets: [{
                    data: incomeData.map(item => item.total),
                    backgroundColor: ['#43e97b', '#667eea', '#f093fb', '#4facfe', '#fa709a']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
    
    // Expenses by Category Chart
    const expensesCtx = document.getElementById('expensesChart');
    if (expensesCtx) {
        const expensesData = <?php echo json_encode($expenses_by_category); ?>;
        new Chart(expensesCtx, {
            type: 'doughnut',
            data: {
                labels: expensesData.map(item => item.name),
                datasets: [{
                    data: expensesData.map(item => item.total),
                    backgroundColor: ['#f5576c', '#fa709a', '#feca57', '#ff9ff3', '#54a0ff']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
});
</script>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
