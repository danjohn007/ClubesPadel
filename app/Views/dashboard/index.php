<?php require_once APP_PATH . '/Views/layouts/header.php'; ?>

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
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
