<?php require_once APP_PATH . '/Views/layouts/header.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="error-page">
                <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 5rem;"></i>
                <h1 class="display-1 mt-4">404</h1>
                <h2 class="mb-4">Página No Encontrada</h2>
                <p class="lead text-muted mb-4">
                    Lo sentimos, la página que estás buscando no existe o ha sido movida.
                </p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="<?php echo URL_BASE; ?>" class="btn btn-primary btn-lg">
                        <i class="bi bi-house-fill"></i> Ir al Inicio
                    </a>
                    <a href="javascript:history.back()" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-arrow-left"></i> Volver Atrás
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
