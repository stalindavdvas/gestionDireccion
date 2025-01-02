<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit;
}
?>
<?php
// Conexión a la base de datos
include('../actions/db_connection.php');

// Obtener las direcciones de la base de datos
$sql = "SELECT d.iddir, c.Pnombre, c.Snombre, c.Papellido, c.Sapellido, d.Provincia, d.Canton, d.Parroquia, d.CallePrincipal, d.CalleSecundaria, d.Numero, d.Lugar, d.CodigoPostal
        FROM direcciones d
        JOIN clientes c ON d.idcli = c.idcli";
$stmt = $conn->query($sql);
$direcciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Sidebar estilos */
        .sidebar {
            position: fixed;
            top: 56px;
            /* Debajo del navbar */
            left: 0;
            height: 100vh;
            background-color: #343a40;
            transition: all 0.3s ease;
            overflow-x: hidden;
        }

        .sidebar-icon-only {
            width: 80px;
            /* Sidebar con solo iconos en pantallas pequeñas */
        }

        .sidebar-expanded {
            width: 250px;
            /* Sidebar expandida en pantallas grandes */
        }

        /* Ocultar texto en pantallas pequeñas */
        .sidebar .nav-link .menu-text {
            display: none;
        }

        /* Mostrar texto en pantallas grandes */
        @media (min-width: 992px) {
            .sidebar .nav-link .menu-text {
                display: inline;
            }

            .sidebar {
                width: 250px;
            }

            /* Espacio que debe dejar la sidebar cuando esté expandida en pantallas grandes */
            .content {
                margin-left: 250px;
                /* Ajuste para pantallas grandes */
                padding: 20px;
                transition: all 0.3s ease;
            }
        }

        /* Ajustes para pantallas pequeñas */
        @media (max-width: 991.98px) {
            .sidebar {
                width: 80px;
                /* Sidebar más pequeña en pantallas pequeñas */
            }

            .content {
                margin-left: 80px;
                /* Dejar espacio suficiente en pantallas pequeñas */
                padding: 20px;
                transition: all 0.3s ease;
            }
        }
    </style>
</head>

<body>

    <!-- Incluir la barra de navegación -->
    <?php include 'navbar.php'; ?>

    <!-- Incluir el sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Contenido principal -->
    <div class="content">
        <div id="main-content">
            <div class="container mt-4">
                <h2 class="text-center">Administrar Direcciones</h2>

                <!-- Formulario para agregar una nueva dirección -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h4>Agregar Nueva Dirección</h4>
                        <form action="guardar_direccion.php" method="POST">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="cliente" class="form-label">Cliente</label>
                                        <select class="form-select" id="cliente" name="idcli" required>
                                            <option value="">Seleccionar Cliente</option>
                                            <?php
                                            // Obtener lista de clientes
                                            $clientes_sql = "SELECT idcli, CONCAT(Pnombre, ' ', Papellido) AS nombre_cliente FROM clientes";
                                            $clientes_stmt = $conn->query($clientes_sql);
                                            $clientes = $clientes_stmt->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($clientes as $cliente) {
                                                echo "<option value='{$cliente['idcli']}'>{$cliente['nombre_cliente']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="provincia" class="form-label">Provincia</label>
                                        <input type="text" class="form-control" id="provincia" name="provincia" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="canton" class="form-label">Cantón</label>
                                        <input type="text" class="form-control" id="canton" name="canton" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="parroquia" class="form-label">Parroquia</label>
                                        <input type="text" class="form-control" id="parroquia" name="parroquia" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="callePrincipal" class="form-label">Calle Principal</label>
                                        <input type="text" class="form-control" id="callePrincipal" name="callePrincipal" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="calleSecundaria" class="form-label">Calle Secundaria</label>
                                        <input type="text" class="form-control" id="calleSecundaria" name="calleSecundaria">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="numero" class="form-label">Número</label>
                                        <input type="text" class="form-control" id="numero" name="numero">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="lugar" class="form-label">Lugar</label>
                                        <input type="text" class="form-control" id="lugar" name="lugar">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="codigoPostal" class="form-label">Código Postal</label>
                                        <input type="text" class="form-control" id="codigoPostal" name="codigoPostal" required>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar Dirección</button>
                        </form>
                    </div>
                </div>

                <!-- Tabla para mostrar las direcciones -->
                <div class="row">
                    <div class="col-md-12">
                        <h4>Lista de Direcciones</h4>
                        <table id="tablaDirecciones" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Cliente</th>
                                    <th>Provincia</th>
                                    <th>Cantón</th>
                                    <th>Parroquia</th>
                                    <th>Calle Principal</th>
                                    <th>Calle Secundaria</th>
                                    <th>Número</th>
                                    <th>Lugar</th>
                                    <th>Código Postal</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($direcciones as $direccion): ?>
                                    <tr>
                                        <td><?php echo $direccion['iddir']; ?></td>
                                        <td><?php echo $direccion['Pnombre'] . ' ' . $direccion['Papellido']; ?></td>
                                        <td><?php echo $direccion['Provincia']; ?></td>
                                        <td><?php echo $direccion['Canton']; ?></td>
                                        <td><?php echo $direccion['Parroquia']; ?></td>
                                        <td><?php echo $direccion['CallePrincipal']; ?></td>
                                        <td><?php echo $direccion['CalleSecundaria']; ?></td>
                                        <td><?php echo $direccion['Numero']; ?></td>
                                        <td><?php echo $direccion['Lugar']; ?></td>
                                        <td><?php echo $direccion['CodigoPostal']; ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $direccion['iddir']; ?>">Editar</button>
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $direccion['iddir']; ?>">Eliminar</button>
                                        </td>
                                    </tr>

                                    <!-- Modal para Editar Dirección -->
                                    <div class="modal fade" id="editModal<?php echo $direccion['iddir']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Editar Dirección</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="editar_direccion.php" method="POST">
                                                        <input type="hidden" name="iddir" value="<?php echo $direccion['iddir']; ?>">
                                                        <div class="mb-3">
                                                            <label for="provincia" class="form-label">Provincia</label>
                                                            <input type="text" class="form-control" id="provincia" name="provincia" value="<?php echo $direccion['Provincia']; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="canton" class="form-label">Cantón</label>
                                                            <input type="text" class="form-control" id="canton" name="canton" value="<?php echo $direccion['Canton']; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="parroquia" class="form-label">Parroquia</label>
                                                            <input type="text" class="form-control" id="parroquia" name="parroquia" value="<?php echo $direccion['Parroquia']; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="callePrincipal" class="form-label">Calle Principal</label>
                                                            <input type="text" class="form-control" id="callePrincipal" name="callePrincipal" value="<?php echo $direccion['CallePrincipal']; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="calleSecundaria" class="form-label">Calle Secundaria</label>
                                                            <input type="text" class="form-control" id="calleSecundaria" name="calleSecundaria" value="<?php echo $direccion['CalleSecundaria']; ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="numero" class="form-label">Número</label>
                                                            <input type="text" class="form-control" id="numero" name="numero" value="<?php echo $direccion['Numero']; ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="lugar" class="form-label">Lugar</label>
                                                            <input type="text" class="form-control" id="lugar" name="lugar" value="<?php echo $direccion['Lugar']; ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="codigoPostal" class="form-label">Código Postal</label>
                                                            <input type="text" class="form-control" id="codigoPostal" name="codigoPostal" value="<?php echo $direccion['CodigoPostal']; ?>" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal para Eliminar Dirección -->
                                    <div class="modal fade" id="deleteModal<?php echo $direccion['iddir']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel">Eliminar Dirección</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>¿Estás seguro de que deseas eliminar esta dirección?</p>
                                                    <form action="eliminar_direccion.php" method="POST">
                                                        <input type="hidden" name="iddir" value="<?php echo $direccion['iddir']; ?>">
                                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>


        </div>
    </div>
    <!-- JavaScript para Bootstrap y DataTable -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tablaDirecciones').DataTable();
        });
    </script>

</body>

</html>