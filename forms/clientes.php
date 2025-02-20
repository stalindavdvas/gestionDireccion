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
    <title>Clientes</title>
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
            <h4>Clientes</h4>
            <div class="row">
                <div class="col">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarModal">
                        <i class="bi bi-person-add"></i> Agregar nuevo Cliente
                    </button>

                    <!-- Modal Agregar -->
                    <div class="modal fade" id="agregarModal" tabindex="-1" aria-labelledby="agregarModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="agregarModalLabel">Agregar Nuevo Cliente</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="formAgregar">
                                        <div class="mb-3">
                                            <label for="Pnombre" class="form-label">Primer Nombre</label>
                                            <input type="text" class="form-control" oninput="soloLetras(this)" title="Solo se permiten letras" id="Pnombre" name="Pnombre" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="Snombre" class="form-label">Segundo Nombre</label>
                                            <input type="text" class="form-control" oninput="soloLetras(this)" title="Solo se permiten letras" id="Snombre" name="Snombre" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="Papellido" class="form-label">Primer Apellido</label>
                                            <input type="text" class="form-control" oninput="soloLetras(this)" title="Solo se permiten letras" id="Papellido" name="Papellido" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="Sapellido" class="form-label">Segundo Apellido</label>
                                            <input type="text" class="form-control" oninput="soloLetras(this)" title="Solo se permiten letras" id="Sapellido" name="Sapellido" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="DNI" class="form-label">DNI</label>
                                            <input type="text" class="form-control" id="DNI" oninput="soloNumeros(this)" maxlength="10" minlength="10" name="DNI" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="Telefono" class="form-label">Teléfono</label>
                                            <input type="text" class="form-control" oninput="soloNumeros(this)" minlength="10" maxlength="10" title="Solo se permiten números enteros" id="Telefono" name="Telefono" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="Email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="txtEmail" name="Email" required>
                                            <p id="errorCorreo"></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="Genero" class="form-label">Género</label>
                                            <select class="form-select" id="Genero" name="Genero" required>
                                                <option value="M">Masculino</option>
                                                <option value="F">Femenino</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="FecNac" class="form-label">Fecha Nacimiento</label>
                                            <input type="date" class="form-control" id="FecNac" name="FecNac" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Agregar Cliente</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive small">
                        <!-- Tabla de Clientes -->
                        <table id="clientesTable" class="table table-striped table-responsive-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Primer Nombre</th>
                                    <th>Segundo Nombre</th>
                                    <th>Primer Apellido</th>
                                    <th>Segundo Apellido</th>
                                    <th>DNI</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Género</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                        <!-- Modal Editar -->
                        <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editarModalLabel">Editar Cliente</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formEditar">
                                            <input type="hidden" id="editIdcli">
                                            <div class="mb-3">
                                                <label for="editPnombre" class="form-label">Primer Nombre</label>
                                                <input type="text" oninput="soloLetras(this)" class="form-control" id="editPnombre">
                                            </div>
                                            <div class="mb-3">
                                                <label for="editSnombre" class="form-label">Segundo Nombre</label>
                                                <input type="text" oninput="soloLetras(this)" class="form-control" id="editSnombre">
                                            </div>
                                            <div class="mb-3">
                                                <label for="editPapellido" class="form-label">Primer Apellido</label>
                                                <input type="text" oninput="soloLetras(this)" class="form-control" id="editPapellido">
                                            </div>
                                            <div class="mb-3">
                                                <label for="editSapellido" class="form-label">Segundo Apellido</label>
                                                <input type="text" oninput="soloLetras(this)" class="form-control" id="editSapellido">
                                            </div>
                                            <div class="mb-3">
                                                <label for="editDNI" class="form-label">DNI</label>
                                                <input type="text" class="form-control" oninput="soloNumeros(this)" id="editDNI" maxlength="10" minlength="10" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editTelefono" class="form-label">Telefono</label>
                                                <input type="text" class="form-control" oninput="soloNumeros(this)" id="editTelefono" maxlength="10" minlength="10">
                                            </div>
                                            <div class="mb-3">
                                                <label for="editEmail" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="editEmail">
                                                <p id="erroreditCorreo"></p>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editGenero" class="form-label">Genero</label>
                                                <input type="text" class="form-control" id="editGenero">
                                            </div>
                                            <div class="mb-3">
                                                <label for="editFecNac" class="form-label">Fecha Nacimiento</label>
                                                <input type="date" class="form-control" id="editFecNac">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Eliminar -->
                        <div class="modal fade" id="eliminarModal" tabindex="-1" aria-labelledby="eliminarModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="eliminarModalLabel">Eliminar Cliente</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>¿Está seguro de que desea eliminar este cliente?</p>
                                        <form id="formEliminar">
                                            <input type="hidden" id="deleteIdcli">
                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal para mostrar las direcciones del cliente -->
                        <div class="modal fade" id="direccionesModal" tabindex="-1" aria-labelledby="direccionesModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="direccionesModalLabel">Direcciones del Cliente</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Botón para agregar nueva dirección -->
                                        <div class="d-flex justify-content-between mb-3">
                                            <button id="btnAgregarDireccion" class="btn btn-primary">Agregar Nueva Dirección</button>
                                        </div>

                                        <!-- Aquí se mostrarán las direcciones -->
                                        <div id="direccionesContent">
                                            <!-- Las direcciones se cargarán dinámicamente aquí -->
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
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
        $(document).ready(function() {
            // Inicializar DataTable
            $('#clientesTable').DataTable({
                "processing": true, // Muestra un indicador de "procesando"
                "lengthChange": true,
                "serverSide": true, // Activa el Server-Side
                "ajax": "../actions/procesador_cliente.php", // Archivo PHP para obtener los datos

                "pageLength": 7, // Número de registros por página
                "lengthMenu": [7, 10, 25, 50], // Opciones del dropdown de cantidad de registros
                "autoWidth": false,
                "responsive": true,
                "columns": [{
                        "width": "50px"
                    }, // Ancho para la columna 1
                    {
                        "width": "100px"
                    }, // Ancho para la columna 2
                    {
                        "width": "100px"
                    }, // Ancho para la columna 3
                    {
                        "width": "100px"
                    },
                    {
                        "width": "100px"
                    },
                    {
                        "width": "80px"
                    },
                    {
                        "width": "120px"
                    },
                    {
                        "width": "180px"
                    },
                    {
                        "width": "80px"
                    },
                    {
                        "width": "80px"
                    },
                    {
                        "width": "120px"
                    }
                ]
            });

            // Delegar el evento click en botón "Editar" en el tbody
            $('#clientesTable tbody').on('click', '.editar', function() {
                var idcli = $(this).data('id');

                // Realizar solicitud AJAX para obtener los datos del cliente
                $.ajax({
                    url: '../actions/get_cliente.php',
                    type: 'GET',
                    data: {
                        idcli: idcli
                    },
                    success: function(data) {
                        var cliente = JSON.parse(data);
                        $('#editIdcli').val(cliente.idcli);
                        $('#editPnombre').val(cliente.Pnombre);
                        $('#editSnombre').val(cliente.Snombre);
                        $('#editPapellido').val(cliente.Papellido);
                        $('#editSapellido').val(cliente.Sapellido);
                        $('#editDNI').val(cliente.DNI);
                        $('#editTelefono').val(cliente.Telefono);
                        $('#editEmail').val(cliente.Email);
                        $('#editGenero').val(cliente.Genero);
                        $('#editFecNac').val(cliente.FechaNacimiento);
                        $('#editarModal').modal('show');
                    }
                });
            });

            // Delegar el evento click en botón "Eliminar" en el tbody
            $('#clientesTable tbody').on('click', '.eliminar', function() {
                var idcli = $(this).data('id');
                $('#deleteIdcli').val(idcli);
                $('#eliminarModal').modal('show');
            });

            // Evento para abrir el modal de editar
            $('#clientesTable tbody').on('click', '.editar', function() {
                var idcli = $(this).data('id');

                // Hacer una petición AJAX para obtener los detalles del cliente
                $.ajax({
                    url: '../actions/get_cliente_id.php', // Debes crear este archivo para obtener los detalles del cliente
                    type: 'POST',
                    data: {
                        idcli: idcli
                    },
                    success: function(response) {
                        var cliente = JSON.parse(response);

                        // Rellenar los campos del formulario con los datos del cliente
                        $('#editIdcli').val(cliente.idcli);
                        $('#editPnombre').val(cliente.Pnombre);
                        $('#editSnombre').val(cliente.Snombre);
                        $('#editPapellido').val(cliente.Papellido);
                        $('#editSapellido').val(cliente.Sapellido);
                        $('#editDNI').val(cliente.DNI);
                        $('#editTelefono').val(cliente.Telefono);
                        $('#editEmail').val(cliente.Email);
                        $('#editGenero').val(cliente.Genero);
                        $('#editFecNac').val(cliente.FechaNacimiento);
                        $('#editDNI').val(cliente.DNI);

                        // Mostrar el modal de editar
                        $('#editarModal').modal('show');
                    },
                    error: function() {
                        alert('Error al obtener los datos del cliente.');
                    }
                });
            });

            // Manejar el formulario de editar
            $('#formEditar').on('submit', function(e) {
                e.preventDefault();
                var idcli = $('#editIdcli').val();
                var Pnombre = $('#editPnombre').val();
                var Snombre = $('#editSnombre').val();
                var Papellido = $('#editPapellido').val();
                var Sapellido = $('#editSapellido').val();
                var DNI = $('#editDNI').val();
                var Telefono = $('#editTelefono').val();
                var Email = $('#editEmail').val();
                var Genero = $('#editGenero').val();
                var FecNac = $('#editFecNac').val();

                $.ajax({
                    url: '../actions/update_cliente.php',
                    type: 'POST',
                    data: {
                        idcli: idcli,
                        Pnombre: Pnombre,
                        Snombre: Snombre,
                        Papellido: Papellido,
                        Sapellido: Sapellido,
                        DNI: DNI,
                        Telefono: Telefono,
                        Email: Email,
                        Genero: Genero,
                        FecNac: FecNac
                    },
                    success: function(response) {
                        try {
                            var result = JSON.parse(response); // Interpretar el JSON correctamente
                            if (result.status === 'success') {
                                $('#editarModal').modal('hide'); // Cierra el modal si fue exitoso
                                // Recargar solo los datos de la tabla
                                $('#clientesTable').DataTable().ajax.reload(null, false);
                                showToast('success', result.message); // Muestra mensaje de éxito
                            } else {
                                showToast('danger', result.message); // Muestra mensaje de error
                            }
                        } catch (error) {
                            showToast('danger', 'Hubo un problema al procesar la respuesta.');
                        }
                    },
                    error: function() {
                        showToast('danger', 'Error en la solicitud AJAX.');
                    }
                });
            });

            // Manejar el formulario de eliminar
            $('#formEliminar').on('submit', function(e) {
                e.preventDefault();
                var idcli = $('#deleteIdcli').val();

                $.ajax({
                    url: '../actions/delete_cliente.php',
                    type: 'POST',
                    data: {
                        idcli: idcli
                    },
                    success: function(response) {
                        try {
                            var result = JSON.parse(response); // Interpretar el JSON correctamente
                            if (result.status === 'success') {
                                $('#eliminarModal').modal('hide'); // Cierra el modal si fue exitoso
                                // Recargar solo los datos de la tabla
                                $('#clientesTable').DataTable().ajax.reload(null, false);
                                showToast('success', result.message); // Muestra mensaje de éxito
                            } else {
                                showToast('danger', result.message); // Muestra mensaje de error
                            }
                        } catch (error) {
                            showToast('danger', 'Hubo un problema al procesar la respuesta.');
                        }
                    },
                    error: function() {
                        showToast('danger', 'Error en la solicitud AJAX.');
                    }
                });
            });
            $('#agregarModal').on('hidden.bs.modal', function() {
                $('body').removeClass('modal-open'); // Elimina la clase modal-open del body
                $('.modal-backdrop').remove(); // Elimina cualquier backdrop restante
                $('body').css('overflow', ''); // Asegura que se reactive el scroll
                $('#formAgregar')[0].reset();
            });


            function validarCedula(cedula) {
                if (cedula.length !== 10) return false;

                const provincia = parseInt(cedula.substring(0, 2), 10);
                if (provincia < 1 || (provincia > 24 && provincia !== 30)) return false;

                const coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
                let suma = 0;

                for (let i = 0; i < 9; i++) {
                    let digito = parseInt(cedula[i]) * coeficientes[i];
                    if (digito >= 10) digito -= 9;
                    suma += digito;
                }

                const digitoVerificador = parseInt(cedula[9]);
                const sumaMod10 = suma % 10 === 0 ? 0 : 10 - (suma % 10);

                return sumaMod10 === digitoVerificador;
            }
            // Manejar el formulario de agregar cliente
            $('#formAgregar').on('submit', function(e) {
                e.preventDefault();
                const cedula = $('#DNI').val(); // Captura el valor del campo DNI
                if (!validarCedula(cedula)) {
                    showToast('danger', 'Cédula inválida');
                    return; // Detener si la cédula es inválida
                }
                $.ajax({
                    url: '../actions/add_cliente.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        try {
                            var result = JSON.parse(response); // Interpretar el JSON correctamente
                            if (result.status === 'success') {
                                $('#agregarModal').modal('hide'); // Cierra el modal si fue exitoso
                                $('body').removeClass('modal-open'); // Elimina la clase que bloquea la pantalla
                                $('.modal-backdrop').remove(); // Elimina el fondo oscuro
                                // Recargar solo los datos de la tabla
                                $('#clientesTable').DataTable().ajax.reload(null, false);
                                $('#formAgregar')[0].reset();
                                showToast('success', result.message); // Muestra mensaje de éxito
                            } else {
                                showToast('danger', result.message); // Muestra mensaje de error
                            }
                        } catch (error) {
                            showToast('danger', 'Hubo un problema al procesar la respuesta.');
                        }
                    },
                    error: function() {
                        showToast('danger', 'Error en la solicitud AJAX.');
                    }

                });
            });
            // Función para mostrar alertas
            function showAlert(type, message) {
                var alertPlaceholder = $('#alertPlaceholder');
                var alertHtml = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
                    message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                alertPlaceholder.html(alertHtml);
            }
        });

        $(document).on('click', '.ver-direcciones', function() {
            var clienteId = $(this).data('id'); // Obtener el ID del cliente del botón

            // Cargar direcciones del cliente
            $.ajax({
                url: '../actions/get_direcciones.php', // Cambia esta ruta por la correcta en tu backend
                type: 'GET',
                data: {
                    idcli: clienteId
                },
                success: function(response) {
                    // Mostrar direcciones en el modal
                    $('#direccionesContent').html(response);

                    // Abrir el modal
                    $('#direccionesModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.log('Error al cargar las direcciones:', error);
                }
            });

            // Configurar el botón para agregar nueva dirección
            $('#btnAgregarDireccion').off('click').on('click', function() {
                var moda = true;
                window.location.href = 'direcciones.php?idcli=' + clienteId + '&vermod=' + moda; // Redirige a una página para agregar dirección
            });
        });
    </script>
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
    <script>
        function soloLetras(input) {
           // Expresión regular que permite solo letras (mayúsculas y minúsculas) y espacios
    const regex = /^[A-Za-z\s]*$/;

// Si el valor del input no coincide con la expresión regular, se eliminan los caracteres no permitidos
if (!regex.test(input.value)) {
    input.value = input.value.replace(/[^A-Za-z\s]/g, '');
}
            // Convertir a mayúsculas
            input.value = input.value.toUpperCase();
        }

        function soloNumeros(input) {
            // Expresión regular que permite solo números
            const regex = /^\d*$/;

            // Si el valor del input no coincide con la expresión regular, se eliminan los caracteres no permitidos
            if (!regex.test(input.value)) {
                input.value = input.value.replace(/[^\d]/g, '');
            }
        }
        document.addEventListener("DOMContentLoaded", function () {
    const inputCorreo = document.getElementById("txtEmail");
    const mensajeError = document.getElementById("errorCorreo");
    const inputEditCorreo = document.getElementById("editEmail");
    const mensajeEditError = document.getElementById("erroreditCorreo");

    inputCorreo.addEventListener("blur", function () {
        const correo = inputCorreo.value.trim();
        const editcorreo = inputEditCorreo.value.trim();
        const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (correo === "" || regexCorreo.test(correo)) {
            mensajeError.textContent = "";
            inputCorreo.style.border = "2px solid green";
        } else {
            mensajeError.textContent = "Correo inválido";
            mensajeError.style.color = "red";
            inputCorreo.style.border = "2px solid red";
        }
        if (editcorreo === "" || regexCorreo.test(editcorreo)) {
            mensajeEditError.textContent = "";
            inputEditCorreo.style.border = "2px solid green";
        } else {
            mensajeEditError.textContent = "Correo inválido";
            mensajeEditError.style.color = "red";
            inputEditCorreo.style.border = "2px solid red";
        }
    });
});

    </script>

</body>

</html>