<?php 
// Ensure configuration is loaded
if (!defined('APP_PATH')) {
    require_once __DIR__ . '/../../../config/config.php';
}
require_once APP_PATH . '/Views/layouts/header.php'; 
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus-fill text-primary" style="font-size: 3rem;"></i>
                        <h2 class="mt-3">Crear Cuenta</h2>
                        <p class="text-muted">Únete a ClubesPadel</p>
                    </div>
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle"></i> <?php echo $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?php echo URL_BASE; ?>/auth/register">
                        <div class="mb-3">
                            <label class="form-label">Tipo de Registro *</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="user_type" id="type_player" value="player" checked>
                                <label class="btn btn-outline-primary" for="type_player">
                                    <i class="bi bi-person"></i> Jugador
                                </label>
                                
                                <input type="radio" class="btn-check" name="user_type" id="type_club" value="club">
                                <label class="btn btn-outline-primary" for="type_club">
                                    <i class="bi bi-building"></i> Club
                                </label>
                            </div>
                        </div>
                        
                        <div id="club_name_field" class="mb-3" style="display: none;">
                            <label for="club_name" class="form-label">Nombre del Club *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-building"></i></span>
                                <input type="text" class="form-control" id="club_name" name="club_name">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Apellido *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Teléfono *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       pattern="[0-9]{10}" maxlength="10" required
                                       placeholder="10 dígitos">
                            </div>
                            <small class="text-muted">Debe tener exactamente 10 dígitos</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" 
                                       minlength="<?php echo PASSWORD_MIN_LENGTH; ?>" required>
                            </div>
                            <small class="text-muted">Mínimo <?php echo PASSWORD_MIN_LENGTH; ?> caracteres</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmar Contraseña *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>
                        
                        <?php
                        // Generate CAPTCHA numbers
                        $num1 = rand(1, 10);
                        $num2 = rand(1, 10);
                        $_SESSION['captcha_answer'] = $num1 + $num2;
                        ?>
                        <div class="mb-3">
                            <label for="captcha" class="form-label">Verificación de Seguridad *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <strong><?php echo $num1; ?> + <?php echo $num2; ?> = ?</strong>
                                </span>
                                <input type="number" class="form-control" id="captcha" name="captcha" 
                                       placeholder="Escribe el resultado" required>
                            </div>
                            <small class="text-muted">Resuelve la suma para continuar</small>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    Acepto los <a href="<?php echo URL_BASE; ?>/terms" target="_blank">Términos y Condiciones</a> 
                                    y la <a href="<?php echo URL_BASE; ?>/privacy" target="_blank">Política de Privacidad</a> *
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-person-plus"></i> Crear Cuenta
                        </button>
                    </form>
                    
                    <script>
                    // Show/hide club name field based on user type
                    document.querySelectorAll('input[name="user_type"]').forEach(radio => {
                        radio.addEventListener('change', function() {
                            const clubNameField = document.getElementById('club_name_field');
                            const clubNameInput = document.getElementById('club_name');
                            
                            if (this.value === 'club') {
                                clubNameField.style.display = 'block';
                                clubNameInput.required = true;
                            } else {
                                clubNameField.style.display = 'none';
                                clubNameInput.required = false;
                                clubNameInput.value = '';
                            }
                        });
                    });
                    </script>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <p class="mb-0">¿Ya tienes cuenta? 
                            <a href="<?php echo URL_BASE; ?>/auth/login">Inicia sesión aquí</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
