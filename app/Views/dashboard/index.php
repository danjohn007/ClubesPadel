<?php 
// Ensure configuration is loaded
if (!defined('APP_PATH')) {
    require_once __DIR__ . '/../../../config/config.php';
}
require_once APP_PATH . '/Views/layouts/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 p-0">
            <?php require_once APP_PATH . '/Views/layouts/sidebar.php'; ?>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Dashboard</h2>
                <div>
                    <span class="badge bg-success">
                        <i class="bi bi-circle-fill"></i> Activo
                    </span>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="label">Reservaciones Hoy</div>
                                <div class="value"><?php echo $stats['reservations_today'] ?? 0; ?></div>
                            </div>
                            <div class="icon">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="label">Ingresos del Mes</div>
                                <div class="value">$<?php echo number_format($stats['income_month'] ?? 0, 2); ?></div>
                            </div>
                            <div class="icon">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="label">Miembros Activos</div>
                                <div class="value"><?php echo $stats['active_members'] ?? 0; ?></div>
                            </div>
                            <div class="icon">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="label">Torneos Activos</div>
                                <div class="value"><?php echo $stats['active_tournaments'] ?? 0; ?></div>
                            </div>
                            <div class="icon">
                                <i class="bi bi-trophy"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="row mt-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Reservaciones Recientes</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($stats['recent_reservations'])): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Cancha</th>
                                                <th>Jugador</th>
                                                <th>Fecha</th>
                                                <th>Hora</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($stats['recent_reservations'] as $reservation): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($reservation['court_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?></td>
                                                    <td><?php echo date('d/m/Y', strtotime($reservation['reservation_date'])); ?></td>
                                                    <td><?php echo date('H:i', strtotime($reservation['start_time'])); ?></td>
                                                    <td>
                                                        <?php
                                                        $statusClass = [
                                                            'pending' => 'warning',
                                                            'confirmed' => 'success',
                                                            'cancelled' => 'danger',
                                                            'completed' => 'info'
                                                        ];
                                                        $statusLabel = [
                                                            'pending' => 'Pendiente',
                                                            'confirmed' => 'Confirmada',
                                                            'cancelled' => 'Cancelada',
                                                            'completed' => 'Completada'
                                                        ];
                                                        ?>
                                                        <span class="badge bg-<?php echo $statusClass[$reservation['status']] ?? 'secondary'; ?>">
                                                            <?php echo $statusLabel[$reservation['status']] ?? $reservation['status']; ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center py-4">
                                    <i class="bi bi-inbox"></i><br>
                                    No hay reservaciones recientes
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-info-circle"></i> Acceso Rápido</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <?php if ($role !== 'player'): ?>
                                <a href="<?php echo URL_BASE; ?>/reservations/create" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Nueva Reservación
                                </a>
                                <a href="<?php echo URL_BASE; ?>/courts" class="btn btn-outline-primary">
                                    <i class="bi bi-grid-3x3"></i> Ver Canchas
                                </a>
                                <?php endif; ?>
                                <a href="<?php echo URL_BASE; ?>/tournaments" class="btn btn-outline-success">
                                    <i class="bi bi-trophy"></i> Ver Torneos
                                </a>
                                <a href="<?php echo URL_BASE; ?>/profile" class="btn btn-outline-secondary">
                                    <i class="bi bi-person"></i> Mi Perfil
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Próximos Eventos</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small">
                                <i class="bi bi-trophy"></i> Torneo Primavera 2024<br>
                                <small>En 15 días</small>
                            </p>
                            <hr>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-calendar-check"></i> Ver calendario completo
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Charts Section -->
        <div class="row mt-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-graph-up"></i> Ingresos vs Gastos (Últimos 6 Meses)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="incomeExpensesChart" height="100"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Reservaciones por Cancha</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="courtReservationsChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Dashboard charts with real data
document.addEventListener('DOMContentLoaded', function() {
    // Income vs Expenses Chart
    const ctxIncome = document.getElementById('incomeExpensesChart');
    if (ctxIncome) {
        <?php 
        // Prepare income data
        $incomeLabels = [];
        $incomeData = [];
        $expenseData = [];
        
        // Get last 6 months
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i month"));
            $monthName = date('M', strtotime("-$i month"));
            $months[$date] = $monthName;
            $incomeLabels[] = $monthName;
            $incomeData[$date] = 0;
            $expenseData[$date] = 0;
        }
        
        // Fill income data
        if (!empty($stats['income_by_month'])) {
            foreach ($stats['income_by_month'] as $row) {
                foreach ($months as $date => $name) {
                    if ($row['month'] === $name) {
                        $incomeData[$date] = (float)$row['total'];
                    }
                }
            }
        }
        
        // Fill expense data
        if (!empty($stats['expenses_by_month'])) {
            foreach ($stats['expenses_by_month'] as $row) {
                foreach ($months as $date => $name) {
                    if ($row['month'] === $name) {
                        $expenseData[$date] = (float)$row['total'];
                    }
                }
            }
        }
        
        $incomeValues = array_values($incomeData);
        $expenseValues = array_values($expenseData);
        ?>
        new Chart(ctxIncome, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($incomeLabels); ?>,
                datasets: [{
                    label: 'Ingresos',
                    data: <?php echo json_encode($incomeValues); ?>,
                    borderColor: '#43e97b',
                    backgroundColor: 'rgba(67, 233, 123, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Gastos',
                    data: <?php echo json_encode($expenseValues); ?>,
                    borderColor: '#f5576c',
                    backgroundColor: 'rgba(245, 87, 108, 0.1)',
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
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += '$' + context.parsed.y.toLocaleString('es-MX', {minimumFractionDigits: 2});
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString('es-MX');
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Court Reservations Chart
    const ctxCourts = document.getElementById('courtReservationsChart');
    if (ctxCourts) {
        <?php 
        // Prepare court reservations data
        $courtLabels = [];
        $courtData = [];
        if (!empty($stats['reservations_by_court'])) {
            foreach ($stats['reservations_by_court'] as $row) {
                $courtLabels[] = $row['name'];
                $courtData[] = (int)$row['count'];
            }
        }
        if (empty($courtLabels)) {
            $courtLabels = ['Sin datos'];
            $courtData = [0];
        }
        ?>
        new Chart(ctxCourts, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($courtLabels); ?>,
                datasets: [{
                    label: 'Reservaciones',
                    data: <?php echo json_encode($courtData); ?>,
                    backgroundColor: [
                        '#667eea',
                        '#f093fb',
                        '#4facfe',
                        '#43e97b',
                        '#fa709a',
                        '#feca57',
                        '#48dbfb',
                        '#ff9ff3'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Reservaciones: ' + context.parsed.x;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
});
</script>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
