<?php require_once APP_PATH . '/Views/layouts/header.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-trophy-fill text-primary" style="font-size: 3rem;"></i>
                        <h2 class="mt-3">Iniciar Sesión</h2>
                        <p class="text-muted">Accede a tu cuenta de ClubesPadel</p>
                    </div>
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?php echo URL_BASE; ?>/auth/login">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" 
                                       placeholder="tu@email.com" required autofocus>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="••••••••" required>
                            </div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Recordarme</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                        </button>
                    </form>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <p class="mb-0">¿No tienes cuenta? 
                            <a href="<?php echo URL_BASE; ?>/auth/register">Regístrate aquí</a>
                        </p>
                    </div>
                    
                    <div class="mt-4 p-3 bg-light rounded">
                        <p class="mb-1 small"><strong>Cuentas de prueba:</strong></p>
                        <p class="mb-1 small"><strong>SuperAdmin:</strong> superadmin@clubespadel.com / admin123</p>
                        <p class="mb-1 small"><strong>Admin Club:</strong> admin@demo.com / demo123</p>
                        <p class="mb-0 small"><strong>Jugador:</strong> jugador1@demo.com / demo123</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
