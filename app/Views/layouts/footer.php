    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="bi bi-trophy-fill"></i> <?php echo APP_NAME; ?></h5>
                    <p class="text-muted"><?php echo APP_DESCRIPTION; ?></p>
                    <p class="text-muted small">Versión <?php echo APP_VERSION; ?></p>
                </div>
                <div class="col-md-3">
                    <h6>Enlaces</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo URL_BASE; ?>" class="text-muted">Inicio</a></li>
                        <li><a href="<?php echo URL_BASE; ?>/home/about" class="text-muted">Acerca de</a></li>
                        <li><a href="<?php echo URL_BASE; ?>/auth/login" class="text-muted">Iniciar Sesión</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Contacto</h6>
                    <p class="text-muted small">
                        <i class="bi bi-envelope"></i> info@clubespadel.com<br>
                        <i class="bi bi-telephone"></i> +52 555-0100
                    </p>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center text-muted small">
                <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
</body>
</html>
