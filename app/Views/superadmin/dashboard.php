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
                    <a class="nav-link active" href="<?php echo URL_BASE; ?>/superadmin/dashboard">
                        <i class="bi bi-house-door"></i> Dashboard
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/clubs">
                        <i class="bi bi-building"></i> Clubes
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/plans">
                        <i class="bi bi-card-checklist"></i> Planes
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/payments">
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
            <h2 class="mb-4">Dashboard SuperAdmin</h2>
            
            <!-- Stats Cards -->
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="label">Total Clubes</div>
                                <div class="value"><?php echo $stats['total_clubs'] ?? 0; ?></div>
                            </div>
                            <div class="icon">
                                <i class="bi bi-building"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="label">Clubes Activos</div>
                                <div class="value"><?php echo $stats['active_clubs'] ?? 0; ?></div>
                            </div>
                            <div class="icon">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="label">En Prueba</div>
                                <div class="value"><?php echo $stats['trial_clubs'] ?? 0; ?></div>
                            </div>
                            <div class="icon">
                                <i class="bi bi-clock-history"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="label">Ingresos del Mes</div>
                                <div class="value">$<?php echo number_format($monthly_revenue ?? 0, 2); ?></div>
                            </div>
                            <div class="icon">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Charts -->
            <div class="row mt-4">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-graph-up"></i> Crecimiento de Clubes</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="clubsGrowthChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Distribución por Plan</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="plansDistributionChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Charts -->
            <div class="row mt-4">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-currency-dollar"></i> Ingresos Mensuales</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="revenueChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-activity"></i> Estado de Suscripciones</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="subscriptionStatusChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Clubs -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-building"></i> Clubes Recientes</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_clubs)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Subdominio</th>
                                        <th>Email</th>
                                        <th>Estado</th>
                                        <th>Fecha de Registro</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_clubs as $club): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($club['name']); ?></td>
                                            <td><code><?php echo htmlspecialchars($club['subdomain']); ?></code></td>
                                            <td><?php echo htmlspecialchars($club['email']); ?></td>
                                            <td>
                                                <?php
                                                $statusClass = [
                                                    'trial' => 'info',
                                                    'active' => 'success',
                                                    'suspended' => 'warning',
                                                    'cancelled' => 'danger'
                                                ];
                                                $statusLabel = [
                                                    'trial' => 'Prueba',
                                                    'active' => 'Activo',
                                                    'suspended' => 'Suspendido',
                                                    'cancelled' => 'Cancelado'
                                                ];
                                                ?>
                                                <span class="badge bg-<?php echo $statusClass[$club['subscription_status']] ?? 'secondary'; ?>">
                                                    <?php echo $statusLabel[$club['subscription_status']] ?? $club['subscription_status']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($club['created_at'])); ?></td>
                                            <td>
                                                <a href="<?php echo URL_BASE; ?>/superadmin/clubs/view/<?php echo $club['id']; ?>" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">
                            <i class="bi bi-inbox"></i><br>
                            No hay clubes registrados
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Real data charts from database
document.addEventListener('DOMContentLoaded', function() {
    // Clubs Growth Chart
    const ctxGrowth = document.getElementById('clubsGrowthChart');
    if (ctxGrowth) {
        <?php 
        // Prepare data for JavaScript
        $growthLabels = [];
        $growthData = [];
        if (!empty($clubs_growth)) {
            foreach ($clubs_growth as $row) {
                $growthLabels[] = $row['month_name'];
                $growthData[] = (int)$row['count'];
            }
        }
        // Fill in missing months if needed
        if (empty($growthLabels)) {
            $growthLabels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'];
            $growthData = [0, 0, 0, 0, 0, 0];
        }
        ?>
        new Chart(ctxGrowth, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($growthLabels); ?>,
                datasets: [{
                    label: 'Nuevos Clubes',
                    data: <?php echo json_encode($growthData); ?>,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
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
    }
    
    // Plans Distribution Chart
    const ctxPlans = document.getElementById('plansDistributionChart');
    if (ctxPlans) {
        <?php 
        // Prepare data for JavaScript
        $planLabels = [];
        $planData = [];
        if (!empty($plans_distribution)) {
            foreach ($plans_distribution as $row) {
                $planLabels[] = $row['name'];
                $planData[] = (int)$row['count'];
            }
        }
        if (empty($planLabels)) {
            $planLabels = ['Básico', 'Profesional', 'Premium'];
            $planData = [0, 0, 0];
        }
        ?>
        new Chart(ctxPlans, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($planLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($planData); ?>,
                    backgroundColor: ['#667eea', '#f093fb', '#43e97b'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Revenue Chart
    const ctxRevenue = document.getElementById('revenueChart');
    if (ctxRevenue) {
        <?php 
        // Prepare revenue data for JavaScript
        $revenueLabels = [];
        $revenueChartData = [];
        if (!empty($revenue_data)) {
            foreach ($revenue_data as $row) {
                $revenueLabels[] = $row['month_name'];
                $revenueChartData[] = (float)$row['total'];
            }
        }
        if (empty($revenueLabels)) {
            $revenueLabels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'];
            $revenueChartData = [0, 0, 0, 0, 0, 0];
        }
        ?>
        new Chart(ctxRevenue, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($revenueLabels); ?>,
                datasets: [{
                    label: 'Ingresos ($)',
                    data: <?php echo json_encode($revenueChartData); ?>,
                    backgroundColor: 'rgba(67, 233, 123, 0.7)',
                    borderColor: '#43e97b',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Ingresos: $' + context.parsed.y.toLocaleString();
                            }
                        }
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
    }
    
    // Subscription Status Chart
    const ctxSubscription = document.getElementById('subscriptionStatusChart');
    if (ctxSubscription) {
        <?php 
        // Prepare subscription status data for JavaScript
        $subscriptionLabels = [];
        $subscriptionData = [];
        $statusLabels = [
            'trial' => 'Período de prueba',
            'active' => 'Activo',
            'suspended' => 'Suspendido',
            'cancelled' => 'Cancelado'
        ];
        if (!empty($subscription_status)) {
            foreach ($subscription_status as $row) {
                $subscriptionLabels[] = $statusLabels[$row['subscription_status']] ?? $row['subscription_status'];
                $subscriptionData[] = (int)$row['count'];
            }
        }
        if (empty($subscriptionLabels)) {
            $subscriptionLabels = ['Período de prueba', 'Activo', 'Suspendido'];
            $subscriptionData = [0, 0, 0];
        }
        ?>
        new Chart(ctxSubscription, {
            type: 'polarArea',
            data: {
                labels: <?php echo json_encode($subscriptionLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($subscriptionData); ?>,
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.7)',
                        'rgba(67, 233, 123, 0.7)',
                        'rgba(240, 147, 251, 0.7)',
                        'rgba(220, 53, 69, 0.7)'
                    ],
                    borderColor: [
                        '#667eea',
                        '#43e97b',
                        '#f093fb',
                        '#dc3545'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed.r || 0;
                                return label + ': ' + value + ' clubes';
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
