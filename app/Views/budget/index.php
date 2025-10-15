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
                <h2><i class="bi bi-calculator"></i> Presupuesto Anual</h2>
                <div>
                    <select class="form-select d-inline-block w-auto me-2" id="yearSelector">
                        <?php
                        $currentYear = date('Y');
                        for ($i = $currentYear - 2; $i <= $currentYear + 2; $i++):
                        ?>
                            <option value="<?php echo $i; ?>" <?php echo $i == $currentYear ? 'selected' : ''; ?>>
                                <?php echo $i; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="bi bi-printer"></i> Imprimir
                    </button>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4 class="text-success">$<?php echo number_format($yearSummary['total_estimated_income'] ?? 0, 2); ?></h4>
                            <p class="text-muted mb-0">Ingresos Estimados</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4 class="text-danger">$<?php echo number_format($yearSummary['total_estimated_expenses'] ?? 0, 2); ?></h4>
                            <p class="text-muted mb-0">Egresos Estimados</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4 class="text-info">$<?php echo number_format($yearSummary['total_actual_income'] ?? 0, 2); ?></h4>
                            <p class="text-muted mb-0">Ingresos Reales</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4 class="text-warning">$<?php echo number_format($yearSummary['total_actual_expenses'] ?? 0, 2); ?></h4>
                            <p class="text-muted mb-0">Egresos Reales</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Comparativa Mensual</h5>
                </div>
                <div class="card-body">
                    <canvas id="budgetChart" height="80"></canvas>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Detalle Mensual</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mes</th>
                                    <th class="text-end">Ingresos Estimados</th>
                                    <th class="text-end">Ingresos Reales</th>
                                    <th class="text-end">Egresos Estimados</th>
                                    <th class="text-end">Egresos Reales</th>
                                    <th class="text-end">Balance Estimado</th>
                                    <th class="text-end">Balance Real</th>
                                    <th class="text-center">Variación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $months = [
                                    1 => 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                                    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                                ];
                                
                                foreach ($months as $monthNum => $monthName):
                                    $budget = $budgets[$monthNum] ?? [
                                        'estimated_income' => 0,
                                        'actual_income' => 0,
                                        'estimated_expenses' => 0,
                                        'actual_expenses' => 0
                                    ];
                                    
                                    $estimatedBalance = $budget['estimated_income'] - $budget['estimated_expenses'];
                                    $actualBalance = $budget['actual_income'] - $budget['actual_expenses'];
                                    $variation = $actualBalance - $estimatedBalance;
                                    $variationPercent = $estimatedBalance != 0 ? ($variation / $estimatedBalance) * 100 : 0;
                                ?>
                                <tr>
                                    <td><strong><?php echo $monthName; ?></strong></td>
                                    <td class="text-end">$<?php echo number_format($budget['estimated_income'], 2); ?></td>
                                    <td class="text-end text-success">$<?php echo number_format($budget['actual_income'], 2); ?></td>
                                    <td class="text-end">$<?php echo number_format($budget['estimated_expenses'], 2); ?></td>
                                    <td class="text-end text-danger">$<?php echo number_format($budget['actual_expenses'], 2); ?></td>
                                    <td class="text-end">$<?php echo number_format($estimatedBalance, 2); ?></td>
                                    <td class="text-end <?php echo $actualBalance >= 0 ? 'text-success' : 'text-danger'; ?>">
                                        $<?php echo number_format($actualBalance, 2); ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-<?php echo $variation >= 0 ? 'success' : 'danger'; ?>">
                                            <?php echo $variation >= 0 ? '+' : ''; ?><?php echo number_format($variationPercent, 1); ?>%
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo URL_BASE; ?>/budget/edit?month=<?php echo $monthNum; ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Budget Chart
const ctx = document.getElementById('budgetChart');
if (ctx) {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            datasets: [{
                label: 'Ingresos Estimados',
                data: <?php echo json_encode($chartData['estimated_income'] ?? array_fill(0, 12, 0)); ?>,
                borderColor: 'rgb(75, 192, 192)',
                borderDash: [5, 5],
                tension: 0.1
            }, {
                label: 'Ingresos Reales',
                data: <?php echo json_encode($chartData['actual_income'] ?? array_fill(0, 12, 0)); ?>,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }, {
                label: 'Egresos Estimados',
                data: <?php echo json_encode($chartData['estimated_expenses'] ?? array_fill(0, 12, 0)); ?>,
                borderColor: 'rgb(255, 99, 132)',
                borderDash: [5, 5],
                tension: 0.1
            }, {
                label: 'Egresos Reales',
                data: <?php echo json_encode($chartData['actual_expenses'] ?? array_fill(0, 12, 0)); ?>,
                borderColor: 'rgb(255, 99, 132)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Year selector
document.getElementById('yearSelector').addEventListener('change', function() {
    window.location.href = '<?php echo URL_BASE; ?>/budget?year=' + this.value;
});
</script>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
