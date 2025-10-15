<?php require_once APP_PATH . '/Views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 p-0">
            <?php require_once APP_PATH . '/Views/layouts/sidebar.php'; ?>
        </div>
        
        <div class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-calendar-check"></i> Reservaciones</h2>
                <a href="<?php echo URL_BASE; ?>/reservations/create" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nueva Reservación
                </a>
            </div>
            
            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="<?php echo URL_BASE; ?>/reservations" class="row g-3">
                        <div class="col-md-4">
                            <label for="date" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="date" name="date" 
                                   value="<?php echo $filters['date'] ?? ''; ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="court_id" class="form-label">Cancha</label>
                            <select class="form-select" id="court_id" name="court_id">
                                <option value="">Todas las canchas</option>
                                <?php foreach ($courts as $court): ?>
                                    <option value="<?php echo $court['id']; ?>" 
                                            <?php echo (isset($filters['court_id']) && $filters['court_id'] == $court['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($court['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-funnel"></i> Filtrar
                            </button>
                            <a href="<?php echo URL_BASE; ?>/reservations" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <a href="<?php echo URL_BASE; ?>/reservations/calendar" class="btn btn-outline-primary w-100">
                        <i class="bi bi-calendar3"></i> Ver Calendario
                    </a>
                </div>
            </div>
            
            <!-- Reservations List -->
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($reservations)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Cancha</th>
                                        <th>Jugador</th>
                                        <th>Duración</th>
                                        <th>Precio</th>
                                        <th>Estado</th>
                                        <th>Pago</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reservations as $reservation): ?>
                                        <tr>
                                            <td><?php echo date('d/m/Y', strtotime($reservation['reservation_date'])); ?></td>
                                            <td><?php echo date('H:i', strtotime($reservation['start_time'])); ?> - 
                                                <?php echo date('H:i', strtotime($reservation['end_time'])); ?></td>
                                            <td><?php echo htmlspecialchars($reservation['court_name']); ?></td>
                                            <td><?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?></td>
                                            <td><?php echo $reservation['duration_hours']; ?>h</td>
                                            <td>$<?php echo number_format($reservation['total_price'], 2); ?></td>
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
                                            <td>
                                                <?php
                                                $paymentClass = [
                                                    'unpaid' => 'danger',
                                                    'paid' => 'success',
                                                    'refunded' => 'warning'
                                                ];
                                                $paymentLabel = [
                                                    'unpaid' => 'No Pagado',
                                                    'paid' => 'Pagado',
                                                    'refunded' => 'Reembolsado'
                                                ];
                                                ?>
                                                <span class="badge bg-<?php echo $paymentClass[$reservation['payment_status']] ?? 'secondary'; ?>">
                                                    <?php echo $paymentLabel[$reservation['payment_status']] ?? $reservation['payment_status']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($reservation['status'] !== 'cancelled'): ?>
                                                    <a href="<?php echo URL_BASE; ?>/reservations/cancel/<?php echo $reservation['id']; ?>" 
                                                       class="btn btn-sm btn-danger"
                                                       onclick="return confirm('¿Cancelar esta reservación?')">
                                                        <i class="bi bi-x-circle"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x" style="font-size: 4rem; color: #ccc;"></i>
                            <h4 class="mt-3">No hay reservaciones</h4>
                            <p class="text-muted">Las reservaciones aparecerán aquí</p>
                            <a href="<?php echo URL_BASE; ?>/reservations/create" class="btn btn-primary mt-2">
                                <i class="bi bi-plus-circle"></i> Crear Reservación
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
