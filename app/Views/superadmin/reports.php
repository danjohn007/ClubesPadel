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
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/users">
                        <i class="bi bi-people"></i> CRM Usuarios
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/clubs">
                        <i class="bi bi-building"></i> CRM Clubes
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/developments">
                        <i class="bi bi-buildings"></i> CRM Desarrollos
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/sports">
                        <i class="bi bi-trophy"></i> CRM Deportivas
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/sponsors">
                        <i class="bi bi-briefcase"></i> Patrocinadores
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/loyalty">
                        <i class="bi bi-star"></i> Sistema de Lealtad
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/plans">
                        <i class="bi bi-card-checklist"></i> Planes
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/payments">
                        <i class="bi bi-credit-card"></i> Pagos
                    </a>
                    <a class="nav-link active" href="<?php echo URL_BASE; ?>/superadmin/reports">
                        <i class="bi bi-graph-up"></i> Reportes Financieros
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/settings">
                        <i class="bi bi-gear"></i> Configuración
                    </a>
                </nav>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 p-4">
            <h2 class="mb-4"><i class="bi bi-graph-up"></i> Reportes del Sistema</h2>
            
            <!-- Revenue Report -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-currency-dollar"></i> Reporte de Ingresos (Últimos 12 Meses)</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($revenue_report)): ?>
                        <canvas id="revenueChart" style="max-height: 300px;"></canvas>
                        
                        <div class="table-responsive mt-4">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Mes</th>
                                        <th>Pagos Recibidos</th>
                                        <th>Monto Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $totalRevenue = 0;
                                    $totalPayments = 0;
                                    foreach ($revenue_report as $report): 
                                        $totalRevenue += $report['total'];
                                        $totalPayments += $report['count'];
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($report['month_name']); ?></td>
                                            <td><?php echo $report['count']; ?></td>
                                            <td><strong>$<?php echo number_format($report['total'], 2); ?></strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-active">
                                        <th>Total</th>
                                        <th><?php echo $totalPayments; ?></th>
                                        <th><strong>$<?php echo number_format($totalRevenue, 2); ?></strong></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No hay datos de ingresos disponibles</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Club Growth Report -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-building"></i> Crecimiento de Clubes (Últimos 12 Meses)</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($club_growth_report)): ?>
                        <canvas id="clubGrowthChart" style="max-height: 300px;"></canvas>
                        
                        <div class="table-responsive mt-4">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Mes</th>
                                        <th>Nuevos Clubes</th>
                                        <th>Total Acumulado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($club_growth_report as $report): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($report['month_name']); ?></td>
                                            <td><span class="badge bg-success"><?php echo $report['new_clubs']; ?></span></td>
                                            <td><strong><?php echo $report['total_clubs']; ?></strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No hay datos de crecimiento disponibles</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Subscription Status Report -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Estado de Suscripciones</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($subscription_report)): ?>
                        <div class="row">
                            <div class="col-md-6">
                                <canvas id="subscriptionChart" style="max-height: 300px;"></canvas>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Estado</th>
                                            <th>Cantidad</th>
                                            <th>Porcentaje</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($subscription_report as $report): ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    $statusLabels = [
                                                        'trial' => 'Período de prueba',
                                                        'active' => 'Activo',
                                                        'suspended' => 'Suspendido',
                                                        'cancelled' => 'Cancelado'
                                                    ];
                                                    echo $statusLabels[$report['subscription_status']] ?? $report['subscription_status'];
                                                    ?>
                                                </td>
                                                <td><strong><?php echo $report['count']; ?></strong></td>
                                                <td><?php echo number_format($report['percentage'], 2); ?>%</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No hay datos de suscripciones disponibles</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Plans Performance -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-card-checklist"></i> Rendimiento por Plan</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($plans_performance)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Plan</th>
                                        <th>Clubes Activos</th>
                                        <th>Ingresos Totales</th>
                                        <th>Promedio por Club</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($plans_performance as $plan): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($plan['name']); ?></strong></td>
                                            <td><?php echo $plan['clubs_count']; ?></td>
                                            <td>$<?php echo number_format($plan['total_revenue'], 2); ?></td>
                                            <td>
                                                <?php 
                                                $avg = $plan['clubs_count'] > 0 ? $plan['total_revenue'] / $plan['clubs_count'] : 0;
                                                echo '$' . number_format($avg, 2);
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No hay datos de rendimiento disponibles</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// Revenue Chart
<?php if (!empty($revenue_report)): ?>
const revenueCtx = document.getElementById('revenueChart');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: [<?php echo "'" . implode("','", array_column($revenue_report, 'month_name')) . "'"; ?>],
        datasets: [{
            label: 'Ingresos Mensuales',
            data: [<?php echo implode(',', array_column($revenue_report, 'total')); ?>],
            borderColor: 'rgb(40, 167, 69)',
            backgroundColor: 'rgba(40, 167, 69, 0.1)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            }
        }
    }
});
<?php endif; ?>

// Club Growth Chart
<?php if (!empty($club_growth_report)): ?>
const clubGrowthCtx = document.getElementById('clubGrowthChart');
new Chart(clubGrowthCtx, {
    type: 'bar',
    data: {
        labels: [<?php echo "'" . implode("','", array_column($club_growth_report, 'month_name')) . "'"; ?>],
        datasets: [{
            label: 'Nuevos Clubes',
            data: [<?php echo implode(',', array_column($club_growth_report, 'new_clubs')); ?>],
            backgroundColor: 'rgba(13, 110, 253, 0.7)',
            borderColor: 'rgb(13, 110, 253)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
<?php endif; ?>

// Subscription Status Chart
<?php if (!empty($subscription_report)): ?>
const subscriptionCtx = document.getElementById('subscriptionChart');
new Chart(subscriptionCtx, {
    type: 'doughnut',
    data: {
        labels: [
            <?php 
            $labels = [];
            foreach ($subscription_report as $report) {
                $statusLabels = [
                    'trial' => 'Período de prueba',
                    'active' => 'Activo',
                    'suspended' => 'Suspendido',
                    'cancelled' => 'Cancelado'
                ];
                $labels[] = "'" . ($statusLabels[$report['subscription_status']] ?? $report['subscription_status']) . "'";
            }
            echo implode(',', $labels);
            ?>
        ],
        datasets: [{
            data: [<?php echo implode(',', array_column($subscription_report, 'count')); ?>],
            backgroundColor: [
                'rgba(255, 193, 7, 0.7)',
                'rgba(40, 167, 69, 0.7)',
                'rgba(220, 53, 69, 0.7)',
                'rgba(108, 117, 125, 0.7)'
            ],
            borderColor: [
                'rgb(255, 193, 7)',
                'rgb(40, 167, 69)',
                'rgb(220, 53, 69)',
                'rgb(108, 117, 125)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
<?php endif; ?>
</script>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
