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
                <h2><i class="bi bi-calendar3"></i> Calendario de Reservaciones</h2>
                <div>
                    <a href="<?php echo URL_BASE; ?>/reservations" class="btn btn-secondary me-2">
                        <i class="bi bi-list"></i> Lista
                    </a>
                    <a href="<?php echo URL_BASE; ?>/reservations/create" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Nueva Reservación
                    </a>
                </div>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet">

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/locales/es.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día'
        },
        events: '<?php echo URL_BASE; ?>/reservations/api/events',
        eventClick: function(info) {
            if (confirm('¿Deseas ver los detalles de esta reservación?')) {
                window.location.href = '<?php echo URL_BASE; ?>/reservations?id=' + info.event.id;
            }
        },
        eventDidMount: function(info) {
            info.el.style.cursor = 'pointer';
        }
    });
    
    calendar.render();
});
</script>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
