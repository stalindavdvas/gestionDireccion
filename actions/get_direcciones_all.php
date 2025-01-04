<?php
include('db_connection.php');

// Obtener el idcli desde la URL (si es que se pasa)
$idcli = isset($_GET['idcli']) ? $_GET['idcli'] : null;

// Si se pasa un idcli, mostrar el modal para agregar la nueva dirección
$mostrarModal = $idcli ? true : false;

// Obtener todas las direcciones si estamos en la página de gestión
$sql = "SELECT d.iddir, d.CallePrincipal, d.CalleSecundaria, d.Numero, p.provincia, c.canton, pa.parroquia, d.Lugar, d.CodigoPostal, d.idcli
        FROM direcciones d
        JOIN provincias p ON d.id_provincia = p.id_provincia
        JOIN cantones c ON d.id_canton = c.id_canton
        JOIN parroquias pa ON d.id_parroquia = pa.id_parroquia";
$stmt = $conn->prepare($sql);
$stmt->execute();
$direcciones = $stmt->fetchAll();
?>
