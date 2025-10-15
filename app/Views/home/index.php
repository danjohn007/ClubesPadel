<?php require_once APP_PATH . '/Views/layouts/header.php'; ?>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container text-center">
        <h1 class="display-3 fw-bold mb-4">
            <i class="bi bi-trophy-fill"></i> Bienvenido a ClubesPadel
        </h1>
        <p class="lead mb-5">Sistema completo de gestión para clubes de pádel. Administra canchas, reservaciones, torneos y finanzas en un solo lugar.</p>
        <div>
            <a href="<?php echo URL_BASE; ?>/auth/login" class="btn btn-light btn-lg me-3">
                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
            </a>
            <a href="<?php echo URL_BASE; ?>/auth/register" class="btn btn-outline-light btn-lg">
                <i class="bi bi-person-plus"></i> Registrarse
            </a>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="container mb-5">
    <h2 class="text-center mb-5">Características Principales</h2>
    
    <div class="row">
        <div class="col-md-4 text-center mb-4">
            <div class="feature-icon">
                <i class="bi bi-calendar-check"></i>
            </div>
            <h4>Reservaciones</h4>
            <p class="text-muted">Sistema completo de reservas de canchas con calendario visual y confirmaciones automáticas.</p>
        </div>
        
        <div class="col-md-4 text-center mb-4">
            <div class="feature-icon">
                <i class="bi bi-cash-coin"></i>
            </div>
            <h4>Módulo Financiero</h4>
            <p class="text-muted">Control total de ingresos, egresos y reportes contables con gráficas detalladas.</p>
        </div>
        
        <div class="col-md-4 text-center mb-4">
            <div class="feature-icon">
                <i class="bi bi-trophy"></i>
            </div>
            <h4>Torneos</h4>
            <p class="text-muted">Organiza torneos, gestiona inscripciones y lleva el registro de resultados.</p>
        </div>
        
        <div class="col-md-4 text-center mb-4">
            <div class="feature-icon">
                <i class="bi bi-people"></i>
            </div>
            <h4>Gestión de Usuarios</h4>
            <p class="text-muted">Administra jugadores, entrenadores y personal con roles y permisos personalizados.</p>
        </div>
        
        <div class="col-md-4 text-center mb-4">
            <div class="feature-icon">
                <i class="bi bi-building"></i>
            </div>
            <h4>Multi-Tenant</h4>
            <p class="text-muted">Sistema SaaS con aislamiento completo entre clubes y gestión centralizada.</p>
        </div>
        
        <div class="col-md-4 text-center mb-4">
            <div class="feature-icon">
                <i class="bi bi-graph-up"></i>
            </div>
            <h4>Reportes</h4>
            <p class="text-muted">Reportes detallados de actividades, ingresos y métricas clave del negocio.</p>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="container mb-5">
    <div class="card bg-primary text-white">
        <div class="card-body text-center p-5">
            <h3 class="mb-3">¿Listo para empezar?</h3>
            <p class="mb-4">Únete a cientos de clubes que ya confían en ClubesPadel</p>
            <a href="<?php echo URL_BASE; ?>/auth/register" class="btn btn-light btn-lg">
                Comienza tu prueba gratis por 30 días
            </a>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
