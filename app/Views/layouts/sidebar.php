<div class="sidebar p-3" id="sidebar">
    <button type="button" class="sidebar-close" id="sidebarClose">
        <i class="bi bi-x-lg"></i>
    </button>
    
    <div class="mb-4 mt-4">
        <h5 class="text-white"><i class="bi bi-speedometer2"></i> Panel de Control</h5>
    </div>
    
    <nav class="nav flex-column">
        <a class="nav-link active" href="<?php echo URL_BASE; ?>/dashboard">
            <i class="bi bi-house-door"></i> Inicio
        </a>
        
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'player'): ?>
        <div class="nav-section mt-3">
            <small class="text-uppercase text-muted px-3">Gestión</small>
            
            <a class="nav-link" href="<?php echo URL_BASE; ?>/courts">
                <i class="bi bi-grid-3x3"></i> Canchas
            </a>
            
            <a class="nav-link" href="<?php echo URL_BASE; ?>/reservations">
                <i class="bi bi-calendar-check"></i> Reservaciones
            </a>
            
            <a class="nav-link" href="<?php echo URL_BASE; ?>/users">
                <i class="bi bi-people"></i> Usuarios
            </a>
            
            <?php if (in_array($_SESSION['user_role'], ['admin', 'superadmin'])): ?>
            <a class="nav-link" href="<?php echo URL_BASE; ?>/finances">
                <i class="bi bi-cash-coin"></i> Finanzas
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <div class="nav-section mt-3">
            <small class="text-uppercase text-muted px-3">Competencias</small>
            
            <a class="nav-link" href="<?php echo URL_BASE; ?>/tournaments">
                <i class="bi bi-trophy"></i> Torneos
            </a>
        </div>
        
        <?php if (isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['admin', 'superadmin'])): ?>
        <div class="nav-section mt-3">
            <small class="text-uppercase text-muted px-3">Comunicación</small>
            
            <a class="nav-link" href="<?php echo URL_BASE; ?>/notifications">
                <i class="bi bi-bell"></i> Notificaciones
            </a>
        </div>
        
        <div class="nav-section mt-3">
            <small class="text-uppercase text-muted px-3">Configuración</small>
            
            <a class="nav-link" href="<?php echo URL_BASE; ?>/settings">
                <i class="bi bi-gear"></i> Ajustes
            </a>
            
            <a class="nav-link" href="<?php echo URL_BASE; ?>/reports">
                <i class="bi bi-file-earmark-bar-graph"></i> Reportes
            </a>
        </div>
        <?php endif; ?>
    </nav>
</div>
