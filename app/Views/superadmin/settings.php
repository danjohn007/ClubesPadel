<?php 
// Ensure configuration is loaded
if (!defined('APP_PATH')) {
    require_once __DIR__ . '/../../../config/config.php';
}
require_once APP_PATH . '/Views/layouts/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <!-- SuperAdmin Sidebar -->
        <div class="col-md-3 col-lg-2 p-0">
            <div class="sidebar p-3">
                <div class="mb-4">
                    <h5 class="text-white"><i class="bi bi-shield-check"></i> SuperAdmin</h5>
                </div>
                
                <nav class="nav flex-column">
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/dashboard">
                        <i class="bi bi-house-door"></i> Dashboard
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/clubs">
                        <i class="bi bi-building"></i> Clubes
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/plans">
                        <i class="bi bi-card-checklist"></i> Planes
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/payments">
                        <i class="bi bi-credit-card"></i> Pagos
                    </a>
                    <a class="nav-link" href="<?php echo URL_BASE; ?>/superadmin/reports">
                        <i class="bi bi-graph-up"></i> Reportes
                    </a>
                    <a class="nav-link active" href="<?php echo URL_BASE; ?>/superadmin/settings">
                        <i class="bi bi-gear"></i> Configuración
                    </a>
                </nav>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 p-4">
            <h2 class="mb-4"><i class="bi bi-gear"></i> Configuración del Sistema</h2>
            
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
            
            <form method="POST" action="<?php echo URL_BASE; ?>/superadmin/settings">
                <!-- Payment Configuration -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-credit-card"></i> Configuración de Pagos</h5>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-3">PayPal</h6>
                        <?php if (isset($settings['payment'])): ?>
                            <?php foreach ($settings['payment'] as $setting): ?>
                                <?php if (strpos($setting['setting_key'], 'paypal') !== false): ?>
                                    <div class="mb-3">
                                        <label class="form-label"><?php echo ucwords(str_replace('_', ' ', $setting['setting_key'])); ?></label>
                                        <?php if (strpos($setting['setting_key'], 'secret') !== false || strpos($setting['setting_key'], 'password') !== false): ?>
                                            <input type="password" class="form-control" name="setting_<?php echo $setting['setting_key']; ?>" 
                                                   value="<?php echo htmlspecialchars($setting['setting_value']); ?>">
                                        <?php else: ?>
                                            <input type="text" class="form-control" name="setting_<?php echo $setting['setting_key']; ?>" 
                                                   value="<?php echo htmlspecialchars($setting['setting_value']); ?>">
                                        <?php endif; ?>
                                        <?php if ($setting['description']): ?>
                                            <small class="text-muted"><?php echo htmlspecialchars($setting['description']); ?></small>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <hr>
                        <h6 class="mb-3">Información Bancaria</h6>
                        <?php if (isset($settings['payment'])): ?>
                            <?php foreach ($settings['payment'] as $setting): ?>
                                <?php if ($setting['setting_key'] === 'bank_account_info'): ?>
                                    <div class="mb-3">
                                        <label class="form-label">Información de Cuentas Bancarias</label>
                                        <textarea class="form-control" name="setting_<?php echo $setting['setting_key']; ?>" 
                                                  rows="4"><?php echo htmlspecialchars($setting['setting_value']); ?></textarea>
                                        <small class="text-muted">Formato: Banco | Cuenta | CLABE (una por línea)</small>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Email Configuration -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-envelope"></i> Configuración de Correo</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($settings['email'])): ?>
                            <?php foreach ($settings['email'] as $setting): ?>
                                <div class="mb-3">
                                    <label class="form-label"><?php echo ucwords(str_replace('_', ' ', $setting['setting_key'])); ?></label>
                                    <?php if (strpos($setting['setting_key'], 'password') !== false): ?>
                                        <input type="password" class="form-control" name="setting_<?php echo $setting['setting_key']; ?>" 
                                               value="<?php echo htmlspecialchars($setting['setting_value']); ?>">
                                    <?php else: ?>
                                        <input type="text" class="form-control" name="setting_<?php echo $setting['setting_key']; ?>" 
                                               value="<?php echo htmlspecialchars($setting['setting_value']); ?>">
                                    <?php endif; ?>
                                    <?php if ($setting['description']): ?>
                                        <small class="text-muted"><?php echo htmlspecialchars($setting['description']); ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Financial Configuration -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-currency-exchange"></i> Configuración Financiera</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($settings['financial'])): ?>
                            <?php foreach ($settings['financial'] as $setting): ?>
                                <div class="mb-3">
                                    <label class="form-label"><?php echo ucwords(str_replace('_', ' ', $setting['setting_key'])); ?></label>
                                    <input type="text" class="form-control" name="setting_<?php echo $setting['setting_key']; ?>" 
                                           value="<?php echo htmlspecialchars($setting['setting_value']); ?>">
                                    <?php if ($setting['description']): ?>
                                        <small class="text-muted"><?php echo htmlspecialchars($setting['description']); ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- General Site Settings -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-globe"></i> Configuración del Sitio Público</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($settings['general'])): ?>
                            <?php foreach ($settings['general'] as $setting): ?>
                                <?php if (in_array($setting['setting_key'], ['site_name', 'site_logo', 'site_description', 'site_keywords'])): ?>
                                    <div class="mb-3">
                                        <label class="form-label"><?php echo ucwords(str_replace('_', ' ', $setting['setting_key'])); ?></label>
                                        <?php if ($setting['setting_key'] === 'site_description'): ?>
                                            <textarea class="form-control" name="setting_<?php echo $setting['setting_key']; ?>" 
                                                      rows="3"><?php echo htmlspecialchars($setting['setting_value']); ?></textarea>
                                        <?php else: ?>
                                            <input type="text" class="form-control" name="setting_<?php echo $setting['setting_key']; ?>" 
                                                   value="<?php echo htmlspecialchars($setting['setting_value']); ?>">
                                        <?php endif; ?>
                                        <?php if ($setting['description']): ?>
                                            <small class="text-muted"><?php echo htmlspecialchars($setting['description']); ?></small>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Legal Settings -->
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="bi bi-file-text"></i> Términos y Condiciones</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($settings['legal'])): ?>
                            <?php foreach ($settings['legal'] as $setting): ?>
                                <div class="mb-3">
                                    <label class="form-label"><?php echo ucwords(str_replace('_', ' ', $setting['setting_key'])); ?></label>
                                    <textarea class="form-control" name="setting_<?php echo $setting['setting_key']; ?>" 
                                              rows="6"><?php echo htmlspecialchars($setting['setting_value']); ?></textarea>
                                    <?php if ($setting['description']): ?>
                                        <small class="text-muted"><?php echo htmlspecialchars($setting['description']); ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Communication Settings -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-whatsapp"></i> Configuración de Comunicación</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($settings['communication'])): ?>
                            <?php foreach ($settings['communication'] as $setting): ?>
                                <div class="mb-3">
                                    <label class="form-label"><?php echo ucwords(str_replace('_', ' ', $setting['setting_key'])); ?></label>
                                    <?php if ($setting['setting_key'] === 'whatsapp_enabled'): ?>
                                        <select class="form-select" name="setting_<?php echo $setting['setting_key']; ?>">
                                            <option value="0" <?php echo $setting['setting_value'] == '0' ? 'selected' : ''; ?>>Deshabilitado</option>
                                            <option value="1" <?php echo $setting['setting_value'] == '1' ? 'selected' : ''; ?>>Habilitado</option>
                                        </select>
                                    <?php else: ?>
                                        <input type="text" class="form-control" name="setting_<?php echo $setting['setting_key']; ?>" 
                                               value="<?php echo htmlspecialchars($setting['setting_value']); ?>">
                                    <?php endif; ?>
                                    <?php if ($setting['description']): ?>
                                        <small class="text-muted"><?php echo htmlspecialchars($setting['description']); ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Contact Information -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-telephone"></i> Información de Contacto</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($settings['contact'])): ?>
                            <?php foreach ($settings['contact'] as $setting): ?>
                                <div class="mb-3">
                                    <label class="form-label"><?php echo ucwords(str_replace('_', ' ', $setting['setting_key'])); ?></label>
                                    <?php if ($setting['setting_key'] === 'business_hours'): ?>
                                        <textarea class="form-control" name="setting_<?php echo $setting['setting_key']; ?>" 
                                                  rows="4"><?php echo htmlspecialchars($setting['setting_value']); ?></textarea>
                                    <?php else: ?>
                                        <input type="text" class="form-control" name="setting_<?php echo $setting['setting_key']; ?>" 
                                               value="<?php echo htmlspecialchars($setting['setting_value']); ?>">
                                    <?php endif; ?>
                                    <?php if ($setting['description']): ?>
                                        <small class="text-muted"><?php echo htmlspecialchars($setting['description']); ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle"></i> Guardar Configuración
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
