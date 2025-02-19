<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE);
error_reporting(0);
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit;
}
?>
<?php
// Incluir la conexión a la base de datos
include('../actions/db_connection.php');

// Recuperar el idcli de la URL
$idcli = isset($_GET['idcli']) ? $_GET['idcli'] : null;
$verModa = isset($_GET['vermod']);

if ($idcli) {
    // Consultar los datos del cliente con el idcli proporcionado
    $stmt = $conn->prepare("SELECT Pnombre, Papellido FROM clientes WHERE idcli = :idcli");
    $stmt->bindParam(':idcli', $idcli, PDO::PARAM_INT);
    $stmt->execute();
    $cliente = $stmt->fetch();
}

// Obtener las direcciones de la base de datos
$direcciones = [];
try {
    $stmt = $conn->prepare("
        SELECT d.*, 
           p.provincia AS provincia, 
           c.canton AS canton, 
           pa.parroquia AS parroquia,
           cl.idcli, CONCAT(cl.Pnombre, ' ', cl.Papellido) AS nombre_cliente
    FROM direcciones d
    LEFT JOIN provincias p ON d.id_provincia = p.id_provincia
    LEFT JOIN cantones c ON d.id_canton = c.id_canton
    LEFT JOIN parroquias pa ON d.id_parroquia = pa.id_parroquia
    LEFT JOIN clientes cl ON d.idcli = cl.idcli
    ");
    $stmt->execute();
    $direcciones = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error al obtener direcciones: " . $e->getMessage();
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
            <h4>Direcciones</h4>
            <div class="row">
                <div class="col">

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarDireccion">
                        <i class="bi bi-pin-map"></i> Agregar Direccion
                    </button>
                    <!-- Modal Agregar Direccion desde cliente -->
                    <div class="modal fade" id="modalAgregarDireccionCli" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Nueva Direccion</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="formAgregarDireccion" action="agregar_direccion.php" method="POST">
                                        <input type="hidden" name="idcli" value="<?php echo $idcli; ?>">
                                        <div class="mb-3">
                                            <!-- Mostrar el nombre del cliente -->
                                            <div class="mb-3">
                                                <label for="cliente" class="form-label">Cliente</label>
                                                <input type="text" class="form-control" id="cliente" value="<?php echo $cliente['Pnombre'] . ' ' . $cliente['Papellido']; ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="id_provincia" class="form-label">Provincia</label>
                                            <select class="form-select" id="id_provincia" name="id_provincia" required>
                                                <option value="" disabled selected>Selecciona una provincia</option>
                                                <?php
                                                $stmt = $conn->prepare("SELECT id_provincia, provincia FROM provincias");
                                                $stmt->execute();
                                                $provincias = $stmt->fetchAll();
                                                foreach ($provincias as $provincia) {
                                                    echo "<option value='" . $provincia['id_provincia'] . "'>" . $provincia['provincia'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="id_canton" class="form-label">Cantón</label>
                                            <select class="form-select" id="id_canton" name="id_canton" required>
                                                <option value="" disabled selected>Selecciona un cantón</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="id_parroquia" class="form-label">Parroquia</label>
                                            <select class="form-select" id="id_parroquia" name="id_parroquia" required>
                                                <option value="" disabled selected>Selecciona una parroquia</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="CallePrincipal" class="form-label">Calle Principal</label>
                                            <input type="text" class="form-control" id="CallePrincipal" name="CallePrincipal" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="CalleSecundaria" class="form-label">Calle Secundaria</label>
                                            <input type="text" class="form-control" id="CalleSecundaria" name="CalleSecundaria">
                                        </div>
                                        <div class="mb-3">
                                            <label for="Numero" class="form-label">Número</label>
                                            <input type="text" class="form-control" id="Numero" name="Numero">
                                        </div>
                                        <label for="tipo">Tipo de Dirección</label>
                                        <select class="form-select" id="tipo" name="tipo" required>
                                            <option value="">Seleccione el tipo de dirección</option>
                                            <option value="hogar">Hogar</option>
                                            <option value="trabajo">Trabajo</option>
                                            <option value="escuela">Escuela</option>
                                            <option value="oficina">Oficina</option>
                                        </select>
                                        <div class="mb-3">
                                            <label for="CodigoPostal" class="form-label">Código Postal</label>
                                            <input type="number" class="form-control" pattern="\d+" minlength="6" maxlength="6" title="Solo se permiten números enteros" id="CodigoPostal" name="CodigoPostal" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Guardar Dirección</button>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Agregar Direccion desde sidebar -->
                    <div class="modal fade" id="modalAgregarDireccion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Nueva Direccion</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="formAgregarDireccionSi" action="agregar_direccion.php" method="POST">
                                        <input type="hidden" name="idcli" value="<?php echo $idcli; ?>">
                                        <div class="mb-3">
                                            <!-- Mostrar el nombre del cliente -->
                                            <label for="idcli" class="form-label">Cliente</label>
                                            <select class="form-select" id="idcli" name="idcli" required>
                                                <option value="" disabled selected>Selecciona un cliente</option>
                                                <?php
                                                // Usamos PDO para obtener los clientes
                                                $stmt = $conn->prepare("SELECT idcli, CONCAT(Pnombre, ' ', Papellido) AS nombre_completo FROM clientes");
                                                $stmt->execute();
                                                $clientes = $stmt->fetchAll();
                                                foreach ($clientes as $cliente) {
                                                    echo "<option value='" . $cliente['idcli'] . "'>" . $cliente['nombre_completo'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="id_provincia" class="form-label">Provincia</label>
                                            <select class="form-select" id="id_provinciaS" name="id_provincia" required>
                                                <option value="" disabled selected>Selecciona una provincia</option>
                                                <?php
                                                $stmt = $conn->prepare("SELECT id_provincia, provincia FROM provincias");
                                                $stmt->execute();
                                                $provincias = $stmt->fetchAll();
                                                foreach ($provincias as $provincia) {
                                                    echo "<option value='" . $provincia['id_provincia'] . "'>" . $provincia['provincia'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="id_canton" class="form-label">Cantón</label>
                                            <select class="form-select" id="id_cantonS" name="id_canton" required>
                                                <option value="" disabled selected>Selecciona un cantón</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="id_parroquia" class="form-label">Parroquia</label>
                                            <select class="form-select" id="id_parroquiaS" name="id_parroquia" required>
                                                <option value="" disabled selected>Selecciona una parroquia</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="CallePrincipal" class="form-label">Calle Principal</label>
                                            <input type="text" class="form-control" id="CallePrincipal" name="CallePrincipal" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="CalleSecundaria" class="form-label">Calle Secundaria</label>
                                            <input type="text" class="form-control" id="CalleSecundaria" name="CalleSecundaria">
                                        </div>
                                        <div class="mb-3">
                                            <label for="Numero" class="form-label">Número</label>
                                            <input type="text" class="form-control" id="Numero" name="Numero">
                                        </div>
                                        <label for="tipo">Tipo de Dirección</label>
                                        <select class="form-select" id="tipo" name="tipo" required>
                                            <option value="">Seleccione el tipo de dirección</option>
                                            <option value="hogar">Hogar</option>
                                            <option value="trabajo">Trabajo</option>
                                            <option value="escuela">Escuela</option>
                                            <option value="oficina">Oficina</option>
                                        </select>
                                        <div class="mb-3">
                                            <label for="CodigoPostal" class="form-label">Código Postal</label>
                                            <input type="number" class="form-control" id="CodigoPostal" pattern="\d+" maxlength="6" title="Solo se permiten números enteros" name="CodigoPostal" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Guardar Dirección</button>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col">
                    <!-- Tabla de direcciones -->
                    <div class="table-responsive small">
                        <table id="direccionesTable" class="table table-striped table-responsive-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Calle Principal</th>
                                    <th>Calle Secundaria</th>
                                    <th>Numero</th>
                                    <th>Provincia</th>
                                    <th>Cantón</th>
                                    <th>Parroquia</th>
                                    <th>Lugar</th>
                                    <th>C. Postal</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($direcciones as $direccion): ?>
                                    <tr>
                                        <td><?php echo $direccion['iddir']; ?></td>
                                        <td><?php echo $direccion['nombre_cliente']; ?></td>
                                        <td><?php echo $direccion['CallePrincipal']; ?></td>
                                        <td><?php echo $direccion['CalleSecundaria']; ?></td>
                                        <td><?php echo $direccion['Numero']; ?></td>
                                        <td><?php echo $direccion['provincia']; ?></td>
                                        <td><?php echo $direccion['canton']; ?></td>
                                        <td><?php echo $direccion['parroquia']; ?></td>
                                        <td><?php echo $direccion['Lugar']; ?></td>
                                        <td><?php echo $direccion['CodigoPostal']; ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning d-inline" onclick="editarDireccion(<?php echo $direccion['iddir']; ?>)"><i class="bi bi-pen"></i></button>
                                            <button class="btn btn-sm btn-danger d-inline" onclick="eliminarDireccion(<?php echo $direccion['iddir']; ?>)"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <!-- Modal de Editar Dirección -->
                        <div class="modal fade" id="modalEditarDireccion" tabindex="-1" aria-labelledby="modalEditarDireccionLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalEditarDireccionLabel">Editar Dirección</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formEditarDireccion" action="editar_direccion.php" method="POST">
                                            <input type="hidden" id="editIddir" name="iddir">
                                            <div class="mb-3">
                                                <input type="hidden" id="editIdcli" name="idclied">
                                                <label for="cliente" class="form-label">Cliente</label>
                                                <input type="text" class="form-control" id="clientenombre" name="cliente" disabled>
                                            </div>

                                            <!-- Provincia -->
                                            <div class="mb-3">
                                                <label for="editProvincia" class="form-label">Provincia</label>
                                                <select class="form-control" id="editProvincia" name="id_provincia" required>
                                                    <option value="" disabled selected>Selecciona una provincia</option>
                                                    <!-- Las opciones se cargarán dinámicamente con AJAX -->
                                                </select>
                                            </div>

                                            <!-- Cantón -->
                                            <div class="mb-3">
                                                <label for="editCanton" class="form-label">Cantón</label>
                                                <select class="form-control" id="editCanton" name="id_canton" required>
                                                    <option value="" disabled selected>Selecciona un cantón</option>
                                                    <!-- Las opciones se cargarán dinámicamente con AJAX -->
                                                </select>
                                            </div>

                                            <!-- Parroquia -->
                                            <div class="mb-3">
                                                <label for="editParroquia" class="form-label">Parroquia</label>
                                                <select class="form-control" id="editParroquia" name="id_parroquia" required>
                                                    <option value="" disabled selected>Selecciona una parroquia</option>
                                                    <!-- Las opciones se cargarán dinámicamente con AJAX -->
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editCallePrincipal" class="form-label">Calle Principal</label>
                                                <input type="text" class="form-control" id="editCallePrincipal" name="CallePrincipal" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editCalleSecundaria" class="form-label">Calle Secundaria</label>
                                                <input type="text" class="form-control" id="editCalleSecundaria" name="CalleSecundaria">
                                            </div>
                                            <div class="mb-3">
                                                <label for="editNumero" class="form-label">Número</label>
                                                <input type="text" class="form-control" id="editNumero" name="Numero">
                                            </div>
                                            <div class="mb-3">
                                                <label for="editCodigoPostal" class="form-label">Código Postal</label>
                                                <input type="text" class="form-control" id="editCodigoPostal" name="CodigoPostal">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Actualizar Dirección</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal de confirmación para eliminar -->
                        <div class="modal fade" id="modalEliminarDireccion" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalEliminarLabel">Confirmar eliminación</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        ¿Estás seguro de que deseas eliminar esta dirección?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-danger" id="confirmarEliminar">Eliminar</button>
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

        // Acciones del sidebar
        document.addEventListener("DOMContentLoaded", function() {
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
        });
    </script>
    <script>
        // Carga dinámica de provincia, cantón y parroquia
        $(document).ready(function() {
            // Cuando se cambie la provincia, cargar los cantones correspondientes
            $('#id_provincia').change(function() {
                var idProvincia = $(this).val();
                if (idProvincia) {
                    $.ajax({
                        url: '../actions/get_cantones.php', // Este archivo obtendrá los cantones
                        type: 'POST',
                        data: {
                            id_provincia: idProvincia
                        },
                        success: function(response) {
                            $('#id_canton').html(response);
                            $('#id_parroquia').html('<option value="" disabled selected>Selecciona una parroquia</option>');
                        }
                    });
                }
            });

            // Cuando se cambie el cantón, cargar las parroquias correspondientes
            $('#id_canton').change(function() {
                var idCanton = $(this).val();
                if (idCanton) {
                    $.ajax({
                        url: '../actions/get_parroquias.php', // Este archivo obtendrá las parroquias
                        type: 'POST',
                        data: {
                            id_canton: idCanton
                        },
                        success: function(response) {
                            $('#id_parroquia').html(response);
                        }
                    });
                }
            });

            // Cuando se cambie la provincia en el segundo modal, cargar los cantones correspondientes
            $('#id_provinciaS').change(function() {
                var idProvincia = $(this).val();
                if (idProvincia) {
                    $.ajax({
                        url: '../actions/get_cantones.php', // Este archivo obtendrá los cantones
                        type: 'POST',
                        data: {
                            id_provincia: idProvincia
                        },
                        success: function(response) {
                            $('#id_cantonS').html(response);
                            $('#id_parroquiaS').html('<option value="" disabled selected>Selecciona una parroquia</option>');
                        }
                    });
                }
            });

            // Cuando se cambie el cantón en el segundo modal, cargar las parroquias correspondientes
            $('#id_cantonS').change(function() {
                var idCanton = $(this).val();
                if (idCanton) {
                    $.ajax({
                        url: '../actions/get_parroquias.php', // Este archivo obtendrá las parroquias
                        type: 'POST',
                        data: {
                            id_canton: idCanton
                        },
                        success: function(response) {
                            $('#id_parroquiaS').html(response);
                        }
                    });
                }
            });

            // Cargar modal cuando elegimos desde la página cliente
            var vermodal = <?php echo json_encode($verModa); ?>; // Usa json_encode para pasar el valor de PHP a JS

            // Lógica para decidir si abrir el modal
            if (vermodal) {
                var miModal = new bootstrap.Modal(document.getElementById('modalAgregarDireccionCli'));
                miModal.show();
            }

            // Inicializar DataTable
            $('#direccionesTable').DataTable({
                "lengthChange": true,
                "pageLength": 7, // Número de registros por página
                "lengthMenu": [7, 10, 25, 50], // Opciones del dropdown de cantidad de registros
                "autoWidth": false,
                "responsive": true,
                "columns": [{
                        "width": "30px"
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
                        "width": "30px"
                    },
                    {
                        "width": "100px"
                    },
                    {
                        "width": "80px"
                    },
                    {
                        "width": "60px"
                    },
                    {
                        "width": "60px"
                    },
                    {
                        "width": "60px"
                    }
                ]
            });

            // Verifica si hay un mensaje en sessionStorage
            var toastMessage = sessionStorage.getItem('toastMessage');
            var toastType = sessionStorage.getItem('toastType'); // Puede ser success, danger, etc.

            if (toastMessage) {
                // Muestra el toast
                showToast(toastType, toastMessage);

                // Limpia sessionStorage después de mostrar el mensaje
                sessionStorage.removeItem('toastMessage');
                sessionStorage.removeItem('toastType');
            }
        });
    </script>
    <script>
        // Acciones de ventanas modales
        $(document).ready(function() {
            // Agregar nueva dirección
            $('#formAgregarDireccion').submit(function(e) {
                e.preventDefault(); // Prevenir el envío del formulario
                var formData = $(this).serialize(); // Serializar los datos del formulario

                $.ajax({
                    url: '../actions/agregar_direccion.php',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            showToast('success', response.message);
                            $('#modalAgregarDireccionCli').modal('hide'); // Cerrar modal
                            // Guarda el mensaje en sessionStorage
                            sessionStorage.setItem('toastMessage', response.message);
                            sessionStorage.setItem('toastType', 'success');
                            // Recarga la página
                            location.reload();
                        } else {
                            showToast('danger', response.message);
                        }
                    },
                    error: function() {
                        showToast('danger', 'Error en la solicitud');
                    }
                });
            });

            // Agregar nueva dirección desde el segundo modal
            $('#formAgregarDireccionSi').submit(function(e) {
                e.preventDefault(); // Prevenir el envío del formulario
                var formData = $(this).serialize(); // Serializar los datos del formulario

                $.ajax({
                    url: '../actions/agregar_direccion.php',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            showToast('success', response.message);
                            $('#modalAgregarDireccion').modal('hide'); // Cerrar modal
                            // Guarda el mensaje en sessionStorage
                            sessionStorage.setItem('toastMessage', response.message);
                            sessionStorage.setItem('toastType', 'success');
                            // Recarga la página
                            location.reload();
                        } else {
                            showToast('danger', response.message);
                        }
                    },
                    error: function() {
                        showToast('danger', 'Error en la solicitud');
                    }
                });
            });

            // Editar dirección
            $('#formEditarDireccion').submit(function(event) {
                event.preventDefault(); // Prevenir el envío del formulario de manera normal
                var formData = $(this).serialize(); // Serializar los datos del formulario

                $.ajax({
                    url: '../actions/editar_direccion.php',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            // Cerrar el modal
                            $('#modalEditarDireccion').modal('hide');
                            // Mostrar mensaje de éxito
                            showToast('success', response.message);
                            // Guarda el mensaje en sessionStorage
                            sessionStorage.setItem('toastMessage', response.message);
                            sessionStorage.setItem('toastType', 'success');
                            // Recarga la página
                            location.reload();
                        } else {
                            // Mostrar mensaje de error
                            showToast('danger', response.message);
                        }
                    },
                    error: function() {
                        showToast('danger', 'Error en la solicitud.');
                    }
                });
            });

            // Eliminar dirección
            var idDireccionEliminar; // Variable global para almacenar la ID de la dirección a eliminar

            window.eliminarDireccion = function(iddir) {
                idDireccionEliminar = iddir; // Almacenar la ID en una variable global
                var modal = new bootstrap.Modal(document.getElementById('modalEliminarDireccion'));
                modal.show();
            }

            // Evento de clic en el botón de confirmación
            $('#confirmarEliminar').click(function() {
                $.ajax({
                    url: '../actions/eliminar_direccion.php',
                    type: 'POST',
                    data: {
                        iddir: idDireccionEliminar
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            showToast('success', response.message);
                            $('#modalEliminarDireccion').modal('hide');
                            // Guarda el mensaje en sessionStorage
                            sessionStorage.setItem('toastMessage', response.message);
                            sessionStorage.setItem('toastType', 'success');
                            // Recarga la página
                            location.reload();
                        } else {
                            showToast('danger', response.message);
                        }
                    },
                    error: function() {
                        showToast('danger', 'Error en la solicitud');
                    }
                });
            });

            // Función para editar dirección
            window.editarDireccion = function(iddir) {
                 
                $.ajax({
                    url: '../actions/obtener_direccion.php',
                    type: 'GET',
                    data: {
                        iddir: iddir
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            // Rellenar el formulario con los datos obtenidos
                            $('#editIddir').val(response.data.iddir);
                            $('#editIdcli').val(response.data.idcli);
                            $('#clientenombre').val(response.data.cliente);
                            $('#editCallePrincipal').val(response.data.CallePrincipal);
                            $('#editCalleSecundaria').val(response.data.CalleSecundaria);
                            $('#editNumero').val(response.data.Numero);
                            $('#editCodigoPostal').val(response.data.CodigoPostal);

                            // Cargar las opciones del select de provincia y seleccionar la correcta
                            $.ajax({
                                url: '../actions/get_provincias.php',
                                type: 'GET',
                                dataType: 'json',
                                success: function(provincias) {
                                    $('#editProvincia').empty(); // Limpiar el select de provincia
                                    $.each(provincias, function(index, provincia) {
                                        $('#editProvincia').append('<option value="' + provincia.id_provincia + '">' + provincia.provincia + '</option>');
                                    });
                                    $('#editProvincia').val(response.data.id_provincia); // Seleccionar la provincia correspondiente
                                }
                            });

                            // Cargar las opciones del select de cantón basado en la provincia seleccionada
                            $.ajax({
                                url: '../actions/get_cantones.php',
                                type: 'POST',
                                data: {
                                    id_provincia: response.data.id_provincia
                                },
                                success: function(cantones) {
                                    $('#editCanton').empty(); // Limpiar el select de cantón
                                    $('#editCanton').html(cantones); // Llenar el select con las opciones
                                    $('#editCanton').val(response.data.id_canton); // Seleccionar el cantón correspondiente
                                }
                            });

                            // Cargar las opciones del select de parroquia basado en el cantón seleccionado
                            $.ajax({
                                url: '../actions/get_parroquias.php',
                                type: 'POST',
                                data: {
                                    id_canton: response.data.id_canton
                                },
                                success: function(parroquias) {
                                    $('#editParroquia').empty(); // Limpiar el select de parroquia
                                    $('#editParroquia').html(parroquias); // Llenar el select con las opciones
                                    $('#editParroquia').val(response.data.id_parroquia); // Seleccionar la parroquia correspondiente
                                }
                            });

                            // Abre el modal de edición
                            var modal = new bootstrap.Modal(document.getElementById('modalEditarDireccion'));
                            modal.show();
                        } else {
                            showToast('danger', 'Error al obtener los datos de la dirección');
                        }
                    },
                    error: function() {
                        showToast('danger', 'Error en la solicitud');
                    }
                });
            }
        });
    </script>
</body>

</html>