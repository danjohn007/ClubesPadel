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
                <h2><i class="bi bi-cash-coin"></i> Reporte Financiero</h2>
                <div>
                    <a href="<?php echo URL_BASE; ?>/reports" class="btn btn-secondary me-2">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                    <a href="<?php echo URL_BASE; ?>/finances" class="btn btn-info me-2">
                        <i class="bi bi-arrow-right"></i> Ir a Finanzas
                    </a>
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="bi bi-printer"></i> Imprimir
                    </button>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="start_date" class="form-label">Fecha Inicio</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="<?php echo $_GET['start_date'] ?? date('Y-m-01'); ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="form-label">Fecha Fin</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                   value="<?php echo $_GET['end_date'] ?? date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-center bg-success text-white">
                        <div class="card-body">
                            <h3>$<?php echo number_format($summary['total_income'] ?? 0, 2); ?></h3>
                            <p class="mb-0">Total Ingresos</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center bg-danger text-white">
                        <div class="card-body">
                            <h3>$<?php echo number_format($summary['total_expenses'] ?? 0, 2); ?></h3>
                            <p class="mb-0">Total Egresos</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center <?php echo ($summary['balance'] ?? 0) >= 0 ? 'bg-primary' : 'bg-warning'; ?> text-white">
                        <div class="card-body">
                            <h3>$<?php echo number_format($summary['balance'] ?? 0, 2); ?></h3>
                            <p class="mb-0">Balance</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Ingresos vs Egresos</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="financesChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Distribución por Categoría</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="categoriesChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Últimos Ingresos</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Concepto</th>
                                            <th class="text-end">Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($recent_income)): ?>
                                            <?php foreach (array_slice($recent_income, 0, 10) as $income): ?>
                                                <tr>
                                                    <td><?php echo date('d/m/Y', strtotime($income['transaction_date'])); ?></td>
                                                    <td><?php echo htmlspecialchars($income['description']); ?></td>
                                                    <td class="text-end text-success">$<?php echo number_format($income['amount'], 2); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="3" class="text-center">No hay ingresos registrados</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Últimos Egresos</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Concepto</th>
                                            <th class="text-end">Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($recent_expenses)): ?>
                                            <?php foreach (array_slice($recent_expenses, 0, 10) as $expense): ?>
                                                <tr>
                                                    <td><?php echo date('d/m/Y', strtotime($expense['transaction_date'])); ?></td>
                                                    <td><?php echo htmlspecialchars($expense['description']); ?></td>
                                                    <td class="text-end text-danger">$<?php echo number_format($expense['amount'], 2); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="3" class="text-center">No hay egresos registrados</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Finances Chart
const ctxFinances = document.getElementById('financesChart');
if (ctxFinances) {
    new Chart(ctxFinances, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($chartLabels ?? []); ?>,
            datasets: [{
                label: 'Ingresos',
                data: <?php echo json_encode($incomeData ?? []); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgb(75, 192, 192)',
                borderWidth: 1
            }, {
                label: 'Egresos',
                data: <?php echo json_encode($expenseData ?? []); ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderColor: 'rgb(255, 99, 132)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Categories Chart
const ctxCategories = document.getElementById('categoriesChart');
if (ctxCategories) {
    new Chart(ctxCategories, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($categoryLabels ?? []); ?>,
            datasets: [{
                data: <?php echo json_encode($categoryData ?? []); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)',
                    'rgba(255, 159, 64, 0.5)'
                ]
            }]
        },
        options: {
            responsive: true
        }
    });
}
</script>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
