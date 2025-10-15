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
                <h2><i class="bi bi-file-earmark-arrow-up"></i> Importación Masiva de Usuarios</h2>
                <a href="<?php echo URL_BASE; ?>/users" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
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
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Cargar Archivo</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data" 
                                  action="<?php echo URL_BASE; ?>/users/import">
                                <div class="mb-3">
                                    <label for="import_file" class="form-label">Seleccionar Archivo *</label>
                                    <input type="file" class="form-control" id="import_file" 
                                           name="import_file" required
                                           accept=".csv,.xlsx,.xls,.xml">
                                    <small class="text-muted">
                                        Formatos aceptados: Excel (.xlsx, .xls), CSV (.csv), XML (.xml)
                                    </small>
                                </div>
                                
                                <div class="alert alert-info">
                                    <strong><i class="bi bi-info-circle"></i> Información:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>El archivo debe contener las siguientes columnas: nombre, apellido, email, teléfono</li>
                                        <li>Columnas opcionales: rol, nivel_habilidad, tipo_membresia, fecha_vencimiento_membresia</li>
                                        <li>Los usuarios duplicados (por email) serán omitidos</li>
                                        <li>Se generará una contraseña temporal para cada usuario</li>
                                    </ul>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-upload"></i> Importar Usuarios
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <?php if (!empty($import_result)): ?>
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-check-circle"></i> Resultado de Importación</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <h3 class="text-primary"><?php echo $import_result['total']; ?></h3>
                                    <p class="text-muted">Total Registros</p>
                                </div>
                                <div class="col-md-4">
                                    <h3 class="text-success"><?php echo $import_result['successful']; ?></h3>
                                    <p class="text-muted">Importados</p>
                                </div>
                                <div class="col-md-4">
                                    <h3 class="text-danger"><?php echo $import_result['failed']; ?></h3>
                                    <p class="text-muted">Errores</p>
                                </div>
                            </div>
                            
                            <?php if (!empty($import_result['errors'])): ?>
                            <hr>
                            <h6>Errores Encontrados:</h6>
                            <ul class="list-group">
                                <?php foreach ($import_result['errors'] as $error): ?>
                                    <li class="list-group-item list-group-item-danger">
                                        <?php echo htmlspecialchars($error); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Plantillas de Importación</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Descarga una plantilla para facilitar la importación:</p>
                            
                            <div class="d-grid gap-2">
                                <a href="<?php echo URL_BASE; ?>/users/download-template?format=xlsx" 
                                   class="btn btn-outline-success">
                                    <i class="bi bi-file-earmark-excel"></i> Plantilla Excel
                                </a>
                                <a href="<?php echo URL_BASE; ?>/users/download-template?format=csv" 
                                   class="btn btn-outline-primary">
                                    <i class="bi bi-filetype-csv"></i> Plantilla CSV
                                </a>
                                <a href="<?php echo URL_BASE; ?>/users/download-template?format=xml" 
                                   class="btn btn-outline-warning">
                                    <i class="bi bi-filetype-xml"></i> Plantilla XML
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0">Formato del Archivo</h5>
                        </div>
                        <div class="card-body">
                            <h6>Excel/CSV:</h6>
                            <pre class="bg-light p-2 small">
nombre,apellido,email,telefono,rol
Juan,Pérez,juan@email.com,5512345678,player
María,García,maria@email.com,5587654321,player</pre>
                            
                            <h6 class="mt-3">XML:</h6>
                            <pre class="bg-light p-2 small">
&lt;usuarios&gt;
  &lt;usuario&gt;
    &lt;nombre&gt;Juan&lt;/nombre&gt;
    &lt;apellido&gt;Pérez&lt;/apellido&gt;
    &lt;email&gt;juan@email.com&lt;/email&gt;
    &lt;telefono&gt;5512345678&lt;/telefono&gt;
    &lt;rol&gt;player&lt;/rol&gt;
  &lt;/usuario&gt;
&lt;/usuarios&gt;</pre>
                        </div>
                    </div>
                    
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0">Historial de Importaciones</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($import_history)): ?>
                                <div class="list-group">
                                    <?php foreach (array_slice($import_history, 0, 5) as $history): ?>
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between">
                                                <small class="text-muted">
                                                    <?php echo date('d/m/Y H:i', strtotime($history['created_at'])); ?>
                                                </small>
                                                <span class="badge bg-<?php echo $history['status'] === 'completed' ? 'success' : 'warning'; ?>">
                                                    <?php echo $history['status']; ?>
                                                </span>
                                            </div>
                                            <div class="small mt-1">
                                                Exitosos: <?php echo $history['successful_records']; ?> | 
                                                Fallidos: <?php echo $history['failed_records']; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center">No hay importaciones previas</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
