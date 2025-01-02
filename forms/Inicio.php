<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE);
error_reporting(0);
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <!-- Agregar Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>
    <!-- Botón toggle de Bootstrap -->
    <button class="btn sidebar-toggler" type="button" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>
    <!-- Sidebar -->
    <?php include('sidebar.php'); ?>

    <!-- Contenido principal -->
    <div class="content">
        <div class="container-fluid">
            <h4>Inicio</h4>
            <div class="row">
                <div class="col">
                </div>   
            </div>
            <div class="row">
                <div class="col">
                  
                </div>
            </div>
            <!-- Toast container -->
            <div class="toast-container position-fixed bottom-0 end-0 p-3">
                <div id="liveToast" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body" id="toastMessage">
                            Este es un mensaje de ejemplo.
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Agregar jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <!-- Agregar Bootstrap 5 (solo una vez) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Agregar DataTables JS -->
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>

   
    <script>
        // Función para mostrar toast
        function showToast(type, message) {
            var toastElement = document.getElementById('liveToast');
            var toastMessage = document.getElementById('toastMessage');

            // Restablecer las clases del toast
            toastElement.className = 'toast align-items-center border-0';

            // Cambiar el estilo del toast según el tipo
            if (type === 'success') {
                toastElement.classList.add('text-bg-success');
            } else if (type === 'danger') {
                toastElement.classList.add('text-bg-danger');
            } else if (type === 'warning') {
                toastElement.classList.add('text-bg-warning');
            } else {
                toastElement.classList.add('text-bg-primary'); // Default a primary si no es otro tipo
            }

            // Cambiar el mensaje del toast
            toastMessage.textContent = message;

            // Mostrar el toast
            var toast = new bootstrap.Toast(toastElement);
            toast.show();
        }
    </script>
    <script>
        // Seleccionamos el botón y el sidebar
        const sidebar = document.getElementById('sidebar');
        const sidebarToggleButton = document.getElementById('sidebarToggle');

        // Escuchamos el clic en el botón toggle
        sidebarToggleButton.addEventListener('click', function() {
            sidebar.classList.toggle('show'); // Usamos la clase show para mostrar u ocultar el sidebar
        });

        // Asegurarse de que el sidebar se vuelva a mostrar en pantallas grandes
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                sidebar.classList.add('show');
                sidebar.classList.remove('collapse'); // Aseguramos que el sidebar esté visible en pantallas grandes
            } else {
                // Si se colapsa en pantallas pequeñas, se mantiene colapsado
                if (!sidebar.classList.contains('show')) {
                    sidebar.classList.add('collapse');
                }
            }
        });

        // Inicializar el estado del sidebar para pantallas pequeñas
        if (window.innerWidth <= 768) {
            sidebar.classList.remove('show');
            sidebar.classList.add('collapse');
        }
    </script>

</body>

</html>