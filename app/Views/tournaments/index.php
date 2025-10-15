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
                <h2><i class="bi bi-trophy"></i> Torneos</h2>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <a href="<?php echo URL_BASE; ?>/tournaments/create" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nuevo Torneo
                </a>
                <?php endif; ?>
            </div>
            
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?php
                    $messages = [
                        'created' => 'Torneo creado exitosamente',
                        'registered' => 'Inscripción exitosa al torneo'
                    ];
                    echo $messages[$_GET['success']] ?? 'Operación exitosa';
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($tournaments)): ?>
                <div class="row">
                    <?php foreach ($tournaments as $tournament): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="card-title"><?php echo htmlspecialchars($tournament['name']); ?></h5>
                                        <?php
                                        $statusClass = [
                                            'upcoming' => 'secondary',
                                            'registration_open' => 'success',
                                            'in_progress' => 'primary',
                                            'completed' => 'info',
                                            'cancelled' => 'danger'
                                        ];
                                        $statusLabel = [
                                            'upcoming' => 'Próximo',
                                            'registration_open' => 'Inscripciones Abiertas',
                                            'in_progress' => 'En Curso',
                                            'completed' => 'Finalizado',
                                            'cancelled' => 'Cancelado'
                                        ];
                                        ?>
                                        <span class="badge bg-<?php echo $statusClass[$tournament['status']] ?? 'secondary'; ?>">
                                            <?php echo $statusLabel[$tournament['status']] ?? $tournament['status']; ?>
                                        </span>
                                    </div>
                                    
                                    <p class="card-text text-muted small">
                                        <?php echo htmlspecialchars(substr($tournament['description'] ?? 'Sin descripción', 0, 100)); ?>
                                    </p>
                                    
                                    <hr>
                                    
                                    <div class="mb-2">
                                        <i class="bi bi-calendar"></i>
                                        <strong>Inicio:</strong> <?php echo date('d/m/Y', strtotime($tournament['start_date'])); ?>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <i class="bi bi-calendar-check"></i>
                                        <strong>Fin:</strong> <?php echo date('d/m/Y', strtotime($tournament['end_date'])); ?>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <i class="bi bi-people"></i>
                                        <strong>Participantes:</strong> <?php echo $tournament['participants_count']; ?>
                                        <?php if ($tournament['max_participants']): ?>
                                            / <?php echo $tournament['max_participants']; ?>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <i class="bi bi-cash"></i>
                                        <strong>Inscripción:</strong> $<?php echo number_format($tournament['registration_fee'], 2); ?>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <a href="<?php echo URL_BASE; ?>/tournaments/viewTournament/<?php echo $tournament['id']; ?>" 
                                           class="btn btn-primary btn-sm">
                                            <i class="bi bi-eye"></i> Ver Detalles
                                        </a>
                                        <?php if ($tournament['status'] === 'registration_open'): ?>
                                        <a href="<?php echo URL_BASE; ?>/tournaments/register/<?php echo $tournament['id']; ?>" 
                                           class="btn btn-success btn-sm">
                                            <i class="bi bi-person-plus"></i> Inscribirse
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-trophy" style="font-size: 4rem; color: #ccc;"></i>
                        <h4 class="mt-3">No hay torneos programados</h4>
                        <p class="text-muted">Los torneos aparecerán aquí cuando sean creados</p>
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <a href="<?php echo URL_BASE; ?>/tournaments/create" class="btn btn-primary mt-2">
                            <i class="bi bi-plus-circle"></i> Crear Primer Torneo
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
