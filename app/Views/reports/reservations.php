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
                <h2><i class="bi bi-calendar-check"></i> Reporte de Reservaciones</h2>
                <div>
                    <a href="<?php echo URL_BASE; ?>/reports" class="btn btn-secondary me-2">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="bi bi-printer"></i> Imprimir
                    </button>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Fecha Inicio</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="<?php echo $_GET['start_date'] ?? date('Y-m-01'); ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">Fecha Fin</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                   value="<?php echo $_GET['end_date'] ?? date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Estado</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Todos</option>
                                <option value="pending">Pendiente</option>
                                <option value="confirmed">Confirmada</option>
                                <option value="completed">Completada</option>
                                <option value="cancelled">Cancelada</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-primary"><?php echo $stats['total'] ?? 0; ?></h3>
                            <p class="text-muted mb-0">Total Reservaciones</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-success"><?php echo $stats['confirmed'] ?? 0; ?></h3>
                            <p class="text-muted mb-0">Confirmadas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-warning"><?php echo $stats['pending'] ?? 0; ?></h3>
                            <p class="text-muted mb-0">Pendientes</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="text-info">$<?php echo number_format($stats['revenue'] ?? 0, 2); ?></h3>
                            <p class="text-muted mb-0">Ingresos Totales</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Reservaciones por Día</h5>
                </div>
                <div class="card-body">
                    <canvas id="reservationsChart" height="100"></canvas>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Detalle de Reservaciones</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Cancha</th>
                                    <th>Usuario</th>
                                    <th>Monto</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($reservations)): ?>
                                    <?php foreach ($reservations as $reservation): ?>
                                        <tr>
                                            <td><?php echo date('d/m/Y', strtotime($reservation['reservation_date'])); ?></td>
                                            <td><?php echo date('H:i', strtotime($reservation['start_time'])); ?> - 
                                                <?php echo date('H:i', strtotime($reservation['end_time'])); ?></td>
                                            <td><?php echo htmlspecialchars($reservation['court_name']); ?></td>
                                            <td><?php echo htmlspecialchars($reservation['user_name']); ?></td>
                                            <td>$<?php echo number_format($reservation['total_amount'], 2); ?></td>
                                            <td>
                                                <?php
                                                $badges = [
                                                    'pending' => 'warning',
                                                    'confirmed' => 'success',
                                                    'completed' => 'primary',
                                                    'cancelled' => 'danger'
                                                ];
                                                $badge = $badges[$reservation['status']] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?php echo $badge; ?>">
                                                    <?php echo ucfirst($reservation['status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No hay reservaciones en el período seleccionado</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Chart for reservations by day
const ctx = document.getElementById('reservationsChart');
if (ctx) {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($chartLabels ?? []); ?>,
            datasets: [{
                label: 'Reservaciones',
                data: <?php echo json_encode($chartData ?? []); ?>,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true
                }
            }
        }
    });
}
</script>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
